<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_Poi extends CI_Controller {
    var $db_name = "ttmongo";
    public function index(){
        if($this->session->userdata('logged_in')){
            $this->load->library('pagination');
            $config['uri_segment'] = 3;
            $config['per_page'] = 500;
            $config["num_links"] = 14;
            $config['base_url'] = 'm_poi/ajax_list';
            $m = new MongoClient();
            $db = $m->selectDB($this->db_name);
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title']= 'Manage poi';
            $data['content']= 'poi/m_list';
            $p = $db->discover_poi;
            $filter = array(
                'country' => array('$regex' => 'fr', '$options' => 'i')
            );
            $config['total_rows'] = $p->find($filter)->count();
            $d = $p->find($filter)->sort(array("_id" => 1))->limit(500);
            $data['pois'] = $d;
            $data['cc'] = 'fr';
            $data['jsIncludes'] = array('poi.js');
            $this->pagination->initialize($config);
            $data['links'] = $this->pagination->create_links();
            $this->load->view('template', $data);
      }
      else redirect('login', 'refresh');
    }
    
    function insert_file($filename, $poi_id){
        $m = new Mongo();
        $db = $m->selectDB($this->db_name);
        
        $query = array(
            'id' => $poi_id
        );
        $update = array(
            '$push' => array(
                "images" => array(
                    "id" => uniqid(),
                    "poi_id" => $poi_id,
                    "filename" => $filename
                )
            )
        );
        $result = $db->discover_poi->update($query, $update);
        return $result;
    }
    
    function delete_file($id, $poi_id){
        $m = new Mongo();
        $db = $m->selectDB($this->db_name);
        $query = array(
            'id' => $poi_id,
            'images.id' => $id
        );
        $update = array(
            '$pull' => array(
                "images" => array(
                    "id" => $id
                )
            )
        );
        $result = $db->discover_poi->update($query, $update);
        echo json_encode(array('status' => 'success', 'msg' => $result));
    }
    
    public function ajax_upload(){
        $poi_id = $this->input->post('id');
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
                   $thumb_conf['quality'] = '100%';
                   $thumb_conf['maintain_ratio'] = TRUE;
                   $thumb_conf['width'] = 200;
                   $thumb_conf['height'] = 200;
                   $thumb_conf['source_image'] = "./uploads/" . $data['file_name'];
                   $thumb_conf['new_image'] = "./uploads/thumb/";
                   $this->image_lib->initialize($thumb_conf);

                   if(!$this->image_lib->resize()) $msg =  $this->image_lib->display_errors();
                   $file_id = $this->insert_file($data['file_name'], $poi_id);
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
    
    public function files($id){
        $m = new MongoClient();
        $db = $m->selectDB($this->db_name);
        $p = $db->discover_poi->findOne(array("id" => $id));
        $this->load->view('poi/files', array('files' => $p['images']));
    }
    
    public function ajax_list($start = 0){
        $this->load->library('pagination');
        $config['uri_segment'] = 3;
        $config['per_page'] = 500;
        $config["num_links"] = 14;
        $config['base_url'] = 'm_poi/ajax_list';
        $cityName = $this->input->post('ci');
        $name = $this->input->post('poi');
        $countryCode = $this->input->post('cc');
        $m = new MongoClient();
        $db = $m->selectDB($this->db_name);
        $p = $db->discover_poi;
        $filter = array(
            'city' => array('$regex' => $cityName, '$options' => 'i'),
            'name' => array('$regex' => $name, '$options' => 'i'),
            'country' => array('$regex' => $countryCode, '$options' => 'i')
        );
        $config['total_rows'] = $p->find($filter)->count();
        $d = $p->find($filter)->sort(array('_id' => 1))->skip($start)->limit(500);
        $data['pois'] = $d;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $this->load->view('poi/m_ajax_list', $data);
    }
    
    public function view($id){
        if($this->session->userdata('logged_in')){
            $m = new MongoClient();
            $db = $m->selectDB($this->db_name);
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title'] = 'View poi';
            $data['content'] = 'poi/m_view';
            $p = $db->discover_poi->findOne(array("id" => $id));
            $data['poi'] = $p;
            $data['reviews'] = isset($p['reviews']) ? $p['reviews'] : array();
            
            $category_ids = array();
            $category_titles = '';
            if(isset($p['discover_categories']) && count($p['discover_categories'])){
                foreach($p['discover_categories'] as $mongo_i){
                    $i = MongoDBRef::get($db, $mongo_i);
                    $category_ids[] = $i['_id']->{'$id'};
                    $category_titles .= $i['title'].', ';
                }
            }
            $category_titles = rtrim($category_titles, ", ");
            $data['poi_category_titles'] = $category_titles;
            $t = $db->discover_categories;
            $c = $t->find()->sort(array("title" => 1));
            $result = array();
            foreach($c as $i){
                $selected = FALSE;
                if(in_array($i['_id']->{'$id'}, $category_ids))
                    $selected = TRUE;
                $result[] = array('id' => $i['_id']->{'$id'}, 'text' => $i['title'], 'selected' => $selected);
            }
            $data['categories_all'] = $result;
            
            $this->load->model('city_search_model');
            $city_res = $this->city_search_model->getbyid($p['city_id']);
            $city = '';
            if(count($city_res)){
                $city = $city_res['text'];
            }
            $data['cityName'] = $city;
            
            $data['files'] = $p['images'];
            
            $data['jsIncludes'] = array('poi.js');
            $data['cssIncludes'] = array('hotel.css');
            if(isset($p['map_image']) && $p['map_image'] <> '')
                $data['map_image'] = 'uploads/'.$p['map_image'];
            $this->load->view('template', $data);
        }
        else redirect('login', 'refresh');
    }
    
    public function update_map_image(){
        $m = new MongoClient();
        $db = $m->selectDB($this->db_name);
        $this->load->helper('map_image');
        $id = $this->input->post('id');
        $p = $db->discover_poi->findOne(array("id" => $id));
        $latitude = $p['latitude'];
        $longitude = $p['longitude'];
        $oldfile = './uploads/';
        if(isset($p['map_image']))
            $oldfile.$p['map_image'];
        if(!is_dir($oldfile) && file_exists($oldfile))
            unlink($oldfile);
        mt_srand();
        $filename = md5(uniqid(mt_rand())).'.png';
        $query = array('id' => $id);
        $update = array('$set' => array(
           'map_image' => $filename 
        ));
        $db->discover_poi->update($query, $update);
        getStaticMapImage($latitude, $longitude, 'L', $filename);
        $map_img_name = 'uploads/'.$filename;
        $this->load->view('poi/map_img', array('map_image' => $map_img_name));
    }
    
    public function ajax_save_categories(){
        $id = $this->input->post('id');
        $m = new MongoClient();
        $db = $m->selectDB($this->db_name);
        $ids = $this->input->post('ids');
        $discover_categories = array();
        if(isset($ids) && $ids <> ""){
            foreach(explode(',' , $ids)  as $d_f_id){
                $mongo_id = new MongoId($d_f_id);
                $discover_categories[] = MongoDBRef::create("discover_categories", $mongo_id);
            }
        }
        $query = array(
            'id' => $id
        );
        $update = array(
            '$set' => array("discover_categories" => $discover_categories)
        );
        
        $db->discover_poi->update($query, $update);
        $p = $db->discover_poi->findOne(array("id" => $id));
        $data['poi'] = $p;
        
        $category_ids = array();
        $category_titles = '';
        if(isset($p['discover_categories']) && count($p['discover_categories'])){
            foreach($p['discover_categories'] as $mongo_i){
                $i = MongoDBRef::get($db, $mongo_i);
                $category_ids[] = $i['_id']->{'$id'};
                $category_titles .= $i['title'].', ';
            }
        }
        $category_titles = rtrim($category_titles, ", ");
        $data['poi_category_titles'] = $category_titles;
        $t = $db->discover_categories;
        $c = $t->find()->sort(array("title" => 1));
        $result = array();
        foreach($c as $i){
            $selected = FALSE;
            if(in_array($i['_id']->{'$id'}, $category_ids))
                $selected = TRUE;
            $result[] = array('id' => $i['_id']->{'$id'}, 'text' => $i['title'], 'selected' => $selected);
        }
        $data['categories_all'] = $result;
        
        $this->load->view('poi/ajax_poi_categories', $data);
    }
    
    public function edit($id){
        if($this->session->userdata('logged_in')){
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title']= 'Edit poi';
            $data['content']= 'poi/m_form';
            $m = new MongoClient();
            $db = $m->selectDB($this->db_name);
            $p = $db->discover_poi->findOne(array("id" => $id));
            $data['poi']= $p;
            $data['jsIncludes'] = array('poi.js', 'edit_page.js');
            $this->load->view('template', $data);
        }
        else redirect('login', 'refresh');
    }
    
    function poi_insert($db){
        $id = uniqid();
        $filename = '';
        if($this->input->post('latitude') <> '' && $this->input->post('longitude') <> ''){
            $this->load->helper('map_image');
            $latitude = $this->input->post('latitude');
            $longitude = $this->input->post('longitude');
            mt_srand();
            $filename = md5(uniqid(mt_rand())).'.png';
            getStaticMapImage($latitude, $longitude, 'L', $filename);
        }
        $show_on_map = 1;
        if($this->input->post('show_on_map') == "0")
            $show_on_map = 0;
        $new_poi = array(
            "id" => $id,
            "name" => $this->input->post('name'),
            "city" => $this->input->post('city'),
            "country" => $this->input->post('country'),
            "address" => $this->input->post('address'),
            "loc" => array("lon" => floatval($this->input->post('longitude')), "lat" => floatval($this->input->post('latitude'))),
            "about" => $this->input->post('about'),
            "description" => $this->input->post('description'),
            "map_image" => $filename,
            "show_on_map" => $show_on_map,
            "stars" => 0,
            "room_order" => 0,
            "reviews" => array(),
            "images" => array(),
            "discover_categories" => array()
        );
        $city_id = $this->input->post('cityId');
        $this->load->model('city_search_model');
        $city_res = $this->city_search_model->getbyid($city_id);
        if(count($city_res)){
            $new_poi['$set']['webgeocity'] = array(
                'id' => $city_res['id'],
                'name' => $city_res['name']
            );
            $new_poi['$set']['city_id'] = $city_id;
        }
        $db->discover_poi->insert($new_poi);
        return $id;
    }
    
    function poi_update($id, $db){
        $query = array(
            'id' => $id
        );
        $show_on_map = 1;
        if($this->input->post('show_on_map') == "0")
            $show_on_map = 0;
        $update = array(
            '$set' => array(
                "name" => $this->input->post('name'),
                "city" => $this->input->post('city'),
                "country" => $this->input->post('country'),
                "address" => $this->input->post('address'),
                "loc" => array("lon" => floatval($this->input->post('longitude')), "lat" => floatval($this->input->post('latitude'))),
                "about" => $this->input->post('about'),
                "description" => $this->input->post('description'),
                "show_on_map" => $show_on_map
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
        $db->discover_poi->update($query, $update);
    }
    
    public function add(){
        if($this->session->userdata('logged_in')){
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title']= 'Add poi';
            $data['content']= 'poi/m_form';
            $data['jsIncludes'] = array('poi.js', 'edit_page.js');
            $this->load->view('template', $data);
        }
        else redirect('login', 'refresh');
    }
    
    public function submit(){
        $m = new MongoClient();
        $db = $m->selectDB($this->db_name);
        $id = $this->input->post('id');
        if($id <> '')
            $this->poi_update($id, $db);
        else
            $id = $this->poi_insert($db);
        redirect('m_poi/view/'.$id, 'refresh');
    }
    
    function review_add($id){
        if($this->session->userdata('logged_in')){
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title']= 'Add review';
            $data['poi_id']= $id;
            $data['content']= 'poi/form_review';
            $this->load->view('template', $data);
        }
        else redirect('login', 'refresh');
    }
    
    public function review_edit($id, $poi_id){
        if($this->session->userdata('logged_in')){
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title']= 'Edit review';
            $data['content']= 'poi/form_review';
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
            $r = $db->discover_poi->aggregate($match);
            $review = $r['result']['0']['reviews'];
            $data['poi_id'] = $poi_id;
            $data['review'] = $review;
            $this->load->view('template', $data);
        }
        else redirect('login', 'refresh');
    }
    
    function review_update(){
        $m = new Mongo();
        $db = $m->selectDB($this->db_name);
        $id = $this->input->post('id');
        $poi_id = $this->input->post('poi_id');
        
        $query = array(
            'id' => $poi_id,
            'reviews.id' => $id
        );
        $update = array(
            '$set' => array(
                "reviews.$.title" => $this->input->post('title'),
                "reviews.$.description" => $this->input->post('description')
            )
        );
        $db->discover_poi->update($query, $update);
    }
    
    function review_insert(){
        $m = new Mongo();
        $db = $m->selectDB($this->db_name);
        $poi_id = $this->input->post('poi_id');
        
        $query = array(
            'id' => $poi_id
        );
        $update = array(
            '$push' => array(
                "reviews" => array(
                    "id" => uniqid(),
                    "poi_id" => $poi_id,
                    "title" => $this->input->post('title'),
                    "description" => $this->input->post('description')
                )
            )
        );
        $db->discover_poi->update($query, $update);
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
            redirect('m_poi/view/'.$this->input->post('poi_id'), 'refresh');
        }
        else redirect('login', 'refresh');
    }
    
    function review_delete($id, $poi_id){
        if($this->session->userdata('logged_in')){
            $m = new Mongo();
            $db = $m->selectDB($this->db_name);
            $query = array(
                'id' => $poi_id,
                'reviews.id' => $id
            );
            $update = array(
                '$pull' => array(
                    "reviews" => array(
                        "id" => $id
                    )
                )
            );
            $db->discover_poi->update($query, $update);
            redirect('m_poi/view/'.$poi_id, 'refresh');
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
            $success = $db->discover_poi->remove($query);
        }
        echo json_encode (array('success' => $success));
    }
    
    public function categories(){
        if($this->session->userdata('logged_in')){
            $m = new Mongo();
            $db = $m->selectDB($this->db_name);
            $c = $db->discover_categories->find();
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title']= 'Manage categories';
            $data['categories']= $c;
            $data['content']= 'poi/categories';
            $data['jsIncludes'] = array('category.js');
            $this->load->view('template', $data);
        }
        else redirect('login', 'refresh');
    }
 
    function category_new(){
        if($this->session->userdata('logged_in')){
            $m = new Mongo();
            $db = $m->selectDB($this->db_name);
            $title = $this->input->post('title');
            $db->discover_categories->insert(array(
                'id' => uniqid(),
                'title' => $title
            ));
            redirect('m_poi/categories', 'refresh');
        }
        else redirect('login', 'refresh');
    }
 
    function ajax_category_save(){
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
        $success = $db->discover_categories->update($query, $update);
        echo json_encode(array('success' => $success));
    }
}