<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_Restaurant extends CI_Controller {
    var $db_name = "ttmongo";
    public function index() {
        if($this->session->userdata('logged_in')){
            $this->load->library('pagination');
            $config['uri_segment'] = 3;
            $config['per_page'] = 500;
            $config["num_links"] = 14;
            $config['base_url'] = 'm_restaurant/ajax_list';
            $m = new MongoClient();
            $db = $m->selectDB($this->db_name);
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title']= 'Manage restaurants';
            $data['content']= 'restaurant/m_list';
            $h = $db->discover_restaurants;
            $filter = array(
                'country' => array('$regex' => 'fr', '$options' => 'i')
            );
            $config['total_rows'] = $h->find($filter)->count();
            $d = $h->find($filter)->sort(array("_id" => 1))->limit(500);
            $data['restaurants'] = $d;
            $data['cc'] = 'fr';
            $data['jsIncludes'] = array('restaurant.js');
            $this->pagination->initialize($config);
            $data['links'] = $this->pagination->create_links();
            $this->load->view('template', $data);
        }
        else redirect('login', 'refresh');
    }
    
    function insert_file($filename, $restaurant_id){
        $m = new Mongo();
        $db = $m->selectDB($this->db_name);
        
        $query = array(
            'id' => $restaurant_id
        );
        $update = array(
            '$push' => array(
                "images" => array(
                    "id" => uniqid(),
                    "restaurant_id" => $restaurant_id,
                    "filename" => $filename
                )
            )
        );
        $result = $db->discover_restaurants->update($query, $update);
        return $result;
    }
    
    function delete_file($id, $restaurant_id){
        $m = new Mongo();
        $db = $m->selectDB($this->db_name);
        $query = array(
            'id' => $restaurant_id,
            'images.id' => $id
        );
        $update = array(
            '$pull' => array(
                "images" => array(
                    "id" => $id
                )
            )
        );
        $result = $db->discover_restaurants->update($query, $update);
        echo json_encode(array('status' => 'success', 'msg' => $result));
    }
    
    public function ajax_upload(){
        $restaurant_id = $this->input->post('id');
        $m = new MongoClient();
        $db = $m->selectDB($this->db_name);
        $status = "";
        $msg = "";
        $file_element_name = 'userfile';
        if ($status != "error")
        {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpeg|jpg|png';
            $config['max_size'] = 1024 * 8;
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);
            $return_data = $this->upload->do_multi_upload($file_element_name);
            if (!$return_data)
            {
                $status = 'error';
                $msg = $this->upload->display_errors('', '');
            }
            else
            {
                foreach ( $return_data as $data){
                   $thumb_conf['image_library'] = 'GD2';
                   $thumb_conf['maintain_ratio'] = TRUE;
                   $thumb_conf['quality'] = '100%';
                   $thumb_conf['width'] = 200;
                   $thumb_conf['height'] = 200;
                   $thumb_conf['source_image'] = "./uploads/" . $data['file_name'];
                   $thumb_conf['new_image'] = "./uploads/thumb/";
                   $this->image_lib->initialize($thumb_conf);

                   if(!$this->image_lib->resize()) $msg =  $this->image_lib->display_errors();
                   $file_id = $this->insert_file($data['file_name'], $restaurant_id);
                    if($file_id)
                    {
                        $status = "success";
                        $msg .= "File successfully uploaded";
                    }
                    else
                    {
                        unlink($data['full_path']);
                        $status = "error";
                        $msg = "Something went wrong when saving the file, please try again. fileid=".$file_id;
                    }
                }
            }
            @unlink($_FILES[$file_element_name]);
        }
        echo json_encode(array('status' => $status, 'msg' => $msg));
    }
    
    public function files($id)
    {
        $m = new MongoClient();
        $db = $m->selectDB($this->db_name);
        $h = $db->discover_restaurants->findOne(array("id" => $id));
        $this->load->view('restaurant/files', array('files' => $h['images']));
    }
    
    function ajax_list($start = 0){
        $this->load->library('pagination');
        $config['uri_segment'] = 3;
        $config['per_page'] = 500;
        $config["num_links"] = 14;
        $config['base_url'] = 'm_restaurant/ajax_list';
        $cityName = $this->input->post('ci');
        $name = $this->input->post('re');
        $countryCode = $this->input->post('cc');
        $m = new MongoClient();
        $db = $m->selectDB($this->db_name);
//        $h = $db->discover_restaurants;
        $h = $db->discover_restaurants;
        $filter = array(
            'city' => array('$regex' => $cityName, '$options' => 'i'),
            '$or' => array(
                array('sa_name' => array('$regex' => $name, '$options' => 'i')),
                array('name' => array('$regex' => $name, '$options' => 'i'))
            ),
            'country' => array('$regex' => $countryCode, '$options' => 'i')
        );
        $config['total_rows'] = $h->find($filter)->count();
        $d = $h->find($filter)->sort(array('_id' => 1))->skip($start)->limit(500);
        $data['restaurants'] = $d;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $this->load->view('restaurant/m_ajax_list', $data);
    }
    
    public function update_map_image(){
        $m = new MongoClient();
        $db = $m->selectDB($this->db_name);
        $this->load->helper('map_image');
        $id = $this->input->post('id');
        $h = $db->discover_restaurants->findOne(array("id" => $id));
        $latitude = $h['latitude'];
        $longitude = $h['longitude'];
        $oldfile = './uploads/';
        if(isset($h['map_image']))
            $oldfile.$h['map_image'];
        if(!is_dir($oldfile) && file_exists($oldfile))
            unlink($oldfile);
        mt_srand();
        $filename = md5(uniqid(mt_rand())).'.png';
        $query = array('id' => $id);
        $update = array('$set' => array(
           'map_image' => $filename 
        ));
        $db->discover_restaurants->update($query, $update);
        getStaticMapImage($latitude, $longitude, 'R', $filename);
        $map_img_name = 'uploads/'.$filename;
        $this->load->view('restaurant/map_img', array('map_image' => $map_img_name));
    }
    
    public function view($id){
        if($this->session->userdata('logged_in')){
            $m = new MongoClient();
            $db = $m->selectDB($this->db_name);
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title'] = 'View restaurant';
            $data['content'] = 'restaurant/m_view';
            $h = $db->discover_restaurants->findOne(array("id" => $id));
            $data['restaurant'] = $h;
            $data['reviews'] = isset($h['reviews']) ? $h['reviews'] : array();
            
            $cuisine_ids = array();
            $cuisine_titles = '';
            if(isset($p['discover_cuisines']) && count($p['discover_cuisines'])){
                foreach($p['discover_cuisines'] as $mongo_i){
                    $i = MongoDBRef::get($db, $mongo_i);
                    $cuisine_ids[] = $i['_id']->{'$id'};
                    $cuisine_titles .= $i['title'].', ';
                }
            }
            $cuisine_titles = rtrim($cuisine_titles, ", ");
            $data['restaurant_cuisine_titles'] = $cuisine_titles;
            $t = $db->discover_cuisines;
            $c = $t->find()->sort(array("title" => 1));
            $result = array();
            foreach($c as $i){
                $selected = FALSE;
                if(in_array($i['_id']->{'$id'}, $cuisine_ids))
                    $selected = TRUE;
                $result[] = array('id' => $i['_id']->{'$id'}, 'text' => $i['title'], 'selected' => $selected);
            }
            $data['cuisines_all'] = $result;
            
            $this->load->model('city_search_model');
            $city_res = $this->city_search_model->getbyid($h['city_id']);
            $city = '';
            if(count($city_res)){
                $city = $city_res['text'];
            }
            $data['cityName'] = $city;
            
            $data['files'] = $h['images'];
            $data['jsIncludes'] = array('restaurant.js');
            $data['cssIncludes'] = array('hotel.css');
            if(isset($h['map_image']) && $h['map_image'] <> '')
                $data['map_image'] = 'uploads/'.$h['map_image'];
            $this->load->view('template', $data);
        }
        else redirect('login', 'refresh');
    }
    
    public function ajax_save_cuisines(){
        $id = $this->input->post('id');
        $m = new MongoClient();
        $db = $m->selectDB($this->db_name);
        $ids = $this->input->post('ids');
        $discover_cuisines = array();
        if(isset($ids) && $ids <> ""){
            foreach(explode(',' , $ids)  as $d_f_id){
                $mongo_id = new MongoId($d_f_id);
                $discover_cuisines[] = MongoDBRef::create("discover_cuisines", $mongo_id);
            }
        }
        $query = array(
            'id' => $id
        );
        $update = array(
            '$set' => array("discover_cuisines" => $discover_cuisines)
        );
        
        $db->discover_restaurant->update($query, $update);
        $p = $db->discover_restaurant->findOne(array("id" => $id));
        $data['restaurant'] = $p;
        
        $cuisine_ids = array();
        $cuisine_titles = '';
        if(isset($p['discover_cuisines']) && count($p['discover_cuisines'])){
            foreach($p['discover_cuisines'] as $mongo_i){
                $i = MongoDBRef::get($db, $mongo_i);
                $cuisine_ids[] = $i['_id']->{'$id'};
                $cuisine_titles .= $i['title'].', ';
            }
        }
        $cuisine_titles = rtrim($cuisine_titles, ", ");
        $data['restaurant_cuisine_titles'] = $cuisine_titles;
        $t = $db->discover_cuisines;
        $c = $t->find()->sort(array("title" => 1));
        $result = array();
        foreach($c as $i){
            $selected = FALSE;
            if(in_array($i['_id']->{'$id'}, $cuisine_ids))
                $selected = TRUE;
            $result[] = array('id' => $i['_id']->{'$id'}, 'text' => $i['title'], 'selected' => $selected);
        }
        $data['cuisines_all'] = $result;
        
        $this->load->view('restaurant/ajax_restaurant_cuisines', $data);
    }
    
    function restaurant_insert($db){
        $id = uniqid();
        $filename = '';
        if($this->input->post('latitude') <> '' && $this->input->post('longitude') <> ''){
            $this->load->helper('map_image');
            $latitude = $this->input->post('latitude');
            $longitude = $this->input->post('longitude');
            mt_srand();
            $filename = md5(uniqid(mt_rand())).'.png';
            getStaticMapImage($latitude, $longitude, 'R', $filename);
        }
        $new_restaurant = array(
            "id" => $id,
            "stars" => 0,
            "name" => $this->input->post('name'),
            "loc" => array("lon" => floatval($this->input->post('longitude')), "lat" => floatval($this->input->post('latitude'))),
            "country" => $this->input->post('country'),
            "city" => $this->input->post('city'),
            "address" => $this->input->post('address'),
            "about" => $this->input->post('about'),
            "description" => $this->input->post('description'),
            "facilities" => $this->input->post('facilities'),
            "map_image" => $filename,
            "zoom_order" => "",
            "images" => array(),
            "reviews" => array(),
            "discover_cuisines" => array()
        );
        $city_id = $this->input->post('cityId');
        $this->load->model('city_search_model');
        $city_res = $this->city_search_model->getbyid($city_id);
        if(count($city_res)){
            $new_restaurant['webgeocity'] = array(
                'id' => $city_res['id'],
                'name' => $city_res['name']
            );
            $new_restaurant['city_id'] = $city_id;
        }
        $db->discover_restaurants->insert($new_restaurant);
        return $id;
    }
    
    function restaurant_update($id, $db){
        $query = array(
            'id' => $id
        );
        $update = array(
            '$set' => array(
                "name" => $this->input->post('name'),
                "loc" => array("lon" => floatval($this->input->post('longitude')), "lat" => floatval($this->input->post('latitude'))),
                "country" => $this->input->post('country'),
                "city" => $this->input->post('city'),
                "address" => $this->input->post('address'),
                "about" => $this->input->post('about'),
                "description" => $this->input->post('description'),
                "facilities" => $this->input->post('facilities')
            )
        );
        $city_id = $this->input->post('cityId');
        $this->load->model('city_search_model');
        $city_res = $this->city_search_model->getbyid($city_id);
        if(count($city_res)){
            $update['$set']['webgeocity'] = array(
                'id' => $city_res['id'],
                'name' => $city_res['name']
            );
            $update['$set']['city_id'] = $city_id;
        }
        $db->discover_restaurants->update($query, $update);
    }
    
    public function submit(){
        $m = new MongoClient();
        $db = $m->selectDB($this->db_name);
        $id = $this->input->post('id');
        if($id <> '')
            $this->restaurant_update($id, $db);
        else
            $id = $this->restaurant_insert($db);
        redirect('m_restaurant/view/'.$id, 'refresh');
    }
    
    public function edit($id){
        if($this->session->userdata('logged_in')){
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title']= 'Edit restaurant';
            $data['content']= 'restaurant/m_form';
            $m = new MongoClient();
            $db = $m->selectDB($this->db_name);
            $h = $db->discover_restaurants->findOne(array("id" => $id));
            $data['restaurant']= $h;
            $data['jsIncludes'] = array('edit_page.js');
            $this->load->view('template', $data);
        }
        else redirect('login', 'refresh');
    }
    
    public function add(){
        if($this->session->userdata('logged_in')){
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title']= 'Add restaurant';
            $data['content']= 'restaurant/m_form';
            $data['jsIncludes'] = array('edit_page.js');
            $this->load->view('template', $data);
        }
        else redirect('login', 'refresh');
    }
    
    function review_add($id){
        if($this->session->userdata('logged_in')){
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title']= 'Add review';
            $data['restaurant_id']= $id;
            $data['content']= 'restaurant/form_review';
            $this->load->view('template', $data);
        }
        else redirect('login', 'refresh');
    }
    
    public function review_edit($id, $restaurant_id){
        if($this->session->userdata('logged_in')){
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title']= 'Edit review';
            $data['content']= 'restaurant/form_review';
            $m = new Mongo();
            $db = $m->selectDB($this->db_name);
            
            $match = array(
                array(
                    '$project' => array(
                        "reviews"   => 1,
                    )
                ),
                array('$unwind' => '$reviews'),
                array(
                '$match' => array(
                    'reviews.id' => $id
                    )
                )
            );
            $r = $db->discover_restaurants->aggregate($match);
            $review = $r['result']['0']['reviews'];
            $data['restaurant_id'] = $restaurant_id;
            $data['review'] = $review;
            $this->load->view('template', $data);
        }
        else redirect('login', 'refresh');
    }
    
    function review_update(){
        $m = new Mongo();
        $db = $m->selectDB($this->db_name);
        $id = $this->input->post('id');
        $restaurant_id = $this->input->post('restaurant_id');
        
        $query = array(
            'id' => $restaurant_id,
            'reviews.id' => $id
        );
        $update = array(
            '$set' => array(
                "reviews.$.title" => $this->input->post('title'),
                "reviews.$.description" => $this->input->post('description')
            )
        );
        $db->discover_restaurants->update($query, $update);
    }
    
    function review_insert(){
        $m = new Mongo();
        $db = $m->selectDB($this->db_name);
        $restaurant_id = $this->input->post('restaurant_id');
        
        $query = array(
            'id' => $restaurant_id
        );
        $update = array(
            '$push' => array(
                "reviews" => array(
                    "id" => uniqid(),
                    "restaurant_id" => $restaurant_id,
                    "title" => $this->input->post('title'),
                    "description" => $this->input->post('description')
                )
            )
        );
        $db->discover_restaurants->update($query, $update);
    }
    
    function review_submit(){
        if($this->session->userdata('logged_in')){
            $id = $this->input->post('id');
            if($id <>'') {
                $this->review_update();
            }
            else {
                $this->review_insert();
            }
            redirect('m_restaurant/view/'.$this->input->post('restaurant_id'), 'refresh');
        }
        else redirect('login', 'refresh');
    }
    
    function review_delete($id, $restaurant_id){
        if($this->session->userdata('logged_in')){
            $m = new Mongo();
            $db = $m->selectDB($this->db_name);
            $query = array(
                'id' => $restaurant_id,
                'reviews.id' => $id
            );
            $update = array(
                '$pull' => array(
                    "reviews" => array(
                        "id" => $id
                    )
                )
            );
            $db->discover_restaurants->update($query, $update);
            redirect('m_restaurant/view/'.$restaurant_id, 'refresh');
        }
        else redirect('login', 'refresh');
    }
    
    function ajax_delete($id){
        $m = new Mongo();
        $db = $m->selectDB($this->db_name);
        $success = FALSE;
        if($id <> ''){
            $query = array(
                'id' => $id
            );
            $success = $db->discover_restaurants->remove($query);
        }
        echo json_encode (array('success' => $success));
    }
    
    public function cuisines(){
        if($this->session->userdata('logged_in')){
            $m = new Mongo();
            $db = $m->selectDB($this->db_name);
            $c = $db->discover_cuisines->find();
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title']= 'Manage cuisines';
            $data['cuisines']= $c;
            $data['content']= 'restaurant/cuisines';
            $data['jsIncludes'] = array('cuisine.js');
            $this->load->view('template', $data);
        }
        else redirect('login', 'refresh');
    }
 
    function cuisine_new(){
        if($this->session->userdata('logged_in')){
            $m = new Mongo();
            $db = $m->selectDB($this->db_name);
            $title = $this->input->post('title');
            $db->discover_cuisines->insert(array(
                'id' => uniqid(),
                'title' => $title
            ));
            redirect('m_restaurant/cuisines', 'refresh');
        }
        else redirect('login', 'refresh');
    }
 
    function ajax_cuisine_save(){
        $m = new Mongo();
        $db = $m->selectDB($this->db_name);
        $id = explode('_', $this->input->post('id'));
        $value = $this->input->post('title');
        $query = array(
            'id' => $id[1]
        );
        $update = array(
            '$set' => array('title' => $value)
        );
        $success = $db->discover_cuisines->update($query, $update);
        echo json_encode(array('success' => $success));
    }
}