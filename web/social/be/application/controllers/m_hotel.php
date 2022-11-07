<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_Hotel extends CI_Controller {
    var $db_name = "ttmongo";
    public function index() {
        if($this->session->userdata('logged_in')){
            $this->load->library('pagination');
            $config['uri_segment'] = 3;
            $config['per_page'] = 500;
            $config["num_links"] = 14;
            $config['base_url'] = 'm_hotel/ajax_list';
            $m = new MongoClient();
            $db = $m->selectDB($this->db_name);
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title']= 'Manage hotels';
            $data['content']= 'hotel/m_list';
            $h = $db->discover_hotels;
            $filter = array(
                'cityName' => array('$regex' => 'paris', '$options' => 'i')
            );
            $config['total_rows'] = $h->find($filter)->count();
            $d = $h->find($filter)->sort(array("_id" => 1))->limit(500);
            $data['hotels'] = $d;
            $data['ci'] = 'paris';
            $data['jsIncludes'] = array('hotel.js');
            $this->pagination->initialize($config);
            $data['links'] = $this->pagination->create_links();
            $this->load->view('template', $data);
          }
          else redirect('login', 'refresh');
    }
    
    public function ajax_list($start = 0){
        $this->load->library('pagination');
        $config['uri_segment'] = 3;
        $config['per_page'] = 500;
        $config["num_links"] = 14;
        $config['base_url'] = 'm_hotel/ajax_list';
        $cityName = $this->input->post('ci');
        $hotelName = $this->input->post('ho');
        $countryName = $this->input->post('co');
        $countryCode = $this->input->post('cc');
        $m = new MongoClient();
        $db = $m->selectDB($this->db_name);
        $h = $db->discover_hotels;
        $filter = array(
            'cityName' => array('$regex' => $cityName, '$options' => 'i'),
            'hotelName' => array('$regex' => $hotelName, '$options' => 'i'),
            'countryName' => array('$regex' => $countryName, '$options' => 'i'),
            'countryCode' => array('$regex' => $countryCode, '$options' => 'i')
        );
        $config['total_rows'] = $h->find($filter)->count();
        $d = $h->find($filter)->sort(array('_id' => 1))->skip($start)->limit(500);
        $data['hotels'] = $d;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $this->load->view('hotel/m_ajax_list', $data);
    }
    
    public function update_map_image(){
        $m = new MongoClient();
        $db = $m->selectDB($this->db_name);
        $this->load->helper('map_image');
        $id = $this->input->post('id');
        $h = $db->discover_hotels->findOne(array("id" => $id));
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
        $db->discover_hotels->update($query, $update);
        getStaticMapImage($latitude, $longitude, 'H', $filename);
        $map_img_name = 'uploads/'.$filename;
        $this->load->view('hotel/map_img', array('map_image' => $map_img_name));
    }
    
    public function ajax_upload(){
        $hotel_id = $this->input->post('id');
        $m = new MongoClient();
        $db = $m->selectDB($this->db_name);
        $status = "";
        $msg = "";
        $file_element_name = 'userfile';
        if ($status <> "error")
        {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpeg|jpg|png';
            $config['max_size'] = 1024 * 8;
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);
//            $this->load->model('hotel_image_model');
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
                   $file_id = $this->insert_file($data['file_name'], $hotel_id);
//                   $file_id = $this->hotel_image_model->insert_file($data['file_name'], $_POST['id']);
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
        $h = $db->discover_hotels->findOne(array("id" => $id));
        $this->load->view('hotel/files', array('files' => $h['images']));
    }

    public function view($id){
        if($this->session->userdata('logged_in')){
            $m = new MongoClient();
            $db = $m->selectDB($this->db_name);
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title'] = 'View hotel';
            $data['content'] = 'hotel/m_view';
            $h = $db->discover_hotels->findOne(array("id" => $id));
            $data['hotel'] = $h;
            $data['rooms'] = $h['rooms'];
            $data['reviews'] = isset($h['reviews']) ? $h['reviews'] : array();
            
            $facilities = array();
            $facility_ids = '';
            $facility_titles = '';
            if(isset($h['discover_facilities']) && count($h['discover_facilities'])){
                foreach($h['discover_facilities'] as $mongo_i){
                    $i = MongoDBRef::get($db, $mongo_i);
                    $facilities[] = array('id' => $i['_id']->{'$id'}, 'text' => $i['title']);
                    $facility_ids .= $i['_id']->{'$id'}.',';
                    $facility_titles .= $i['title'].', ';
                }
            }
            $facility_ids = rtrim($facility_ids, ",");
            $facility_titles = rtrim($facility_titles, ", ");
            $data['hotel_facilities'] = json_encode($facilities);
            $data['hotel_facility_ids'] = $facility_ids;
            $data['hotel_facility_titles'] = $facility_titles;
            
            $this->load->model('city_search_model');
            $city_res = $this->city_search_model->getbyid($h['city_id']);
            $city = '';
            if(count($city_res)){
                $city = $city_res['text'];
            }
            $data['cityName'] = $city;
            
            $data['files'] = $h['images'];
            $data['jsIncludes'] = array('hotel.js');
            $data['cssIncludes'] = array('hotel.css');
            if(isset($h['map_image']) && $h['map_image'] <> '')
                $data['map_image'] = 'uploads/'.$h['map_image'];
            $this->load->view('template', $data);
        }
        else redirect('login', 'refresh');
    }
    
    function insert_file($filename, $hotel_id){
        $m = new Mongo();
        $db = $m->selectDB($this->db_name);
        
        $query = array(
            'id' => $hotel_id
        );
        $update = array(
            '$push' => array(
                "images" => array(
                    "id" => uniqid(),
                    "hotel_id" => $hotel_id,
                    "filename" => $filename
                )
            )
        );
        $result = $db->discover_hotels->update($query, $update);
        return $result;
    }
    
    function delete_file($id, $hotel_id){
        $m = new Mongo();
        $db = $m->selectDB($this->db_name);
        $query = array(
            'id' => $hotel_id,
            'images.id' => $id
        );
        $update = array(
            '$pull' => array(
                "images" => array(
                    "id" => $id
                )
            )
        );
        $result = $db->discover_hotels->update($query, $update);
        echo json_encode(array('status' => 'success', 'msg' => $result));
    }
    
    function hotel_insert($db){
        $id = uniqid();
        $filename = '';
        if($this->input->post('latitude') <> '' && $this->input->post('longitude') <> ''){
            $this->load->helper('map_image');
            $latitude = $this->input->post('latitude');
            $longitude = $this->input->post('longitude');
            mt_srand();
            $filename = md5(uniqid(mt_rand())).'.png';
            getStaticMapImage($latitude, $longitude, 'H', $filename);
        }
        $new_hotel = array(
            "id" => $id,
            "hotelName" => $this->input->post('hotelName'),
            "stars" => intval($this->input->post('stars')),
            "price" => intval($this->input->post('price')),
            "cityName" => $this->input->post('cityName'),
            "stateName" => $this->input->post('stateName'),
            "countryCode" => $this->input->post('countryCode'),
            "countryName" => $this->input->post('countryName'),
            "address" => $this->input->post('address'),
            "location" => $this->input->post('location'),
            "loc" => array("lon" => floatval($this->input->post('longitude')), "lat" => floatval($this->input->post('latitude'))),
            "propertyType" => $this->input->post('propertyType'),
            "rating" => $this->input->post('rating'),
            "about" => $this->input->post('about'),
            "description" => $this->input->post('description'),
            "services" => $this->input->post('services'),
            "map_image" => $filename,
            "url" => "",
            "tripadvisorUrl" => "",
            "latlong" => "",
            "chainId" => "0",
            "rooms" => array(),
            "facilities" => "",
            "checkIn" => "",
            "checkOut" => "",
            "general_facilities" => "",
            "room_order" => 0,
            "reviews" => array(),
            "images" => array(),
            "discover_facilities" => array()
        );
        $city_id = $this->input->post('cityId');
        $this->load->model('city_search_model');
        $city_res = $this->city_search_model->getbyid($city_id);
        if(count($city_res)){
            $new_hotel['webgeocity'] = array(
                'id' => intval($city_res['id']),
                'name' => $city_res['name']
            );
            $new_hotel['city_id'] = $city_id;
        }
        $db->discover_hotels->insert($new_hotel);
        return $id;
    }
    
    function hotel_update($id, $db){
        $query = array(
            'id' => $id
        );
        $update = array(
            '$set' => array(
                "hotelName" => $this->input->post('hotelName'),
                "stars" => intval($this->input->post('stars')),
                "price" => intval($this->input->post('price')),
                "stateName" => $this->input->post('stateName'),
                "countryCode" => $this->input->post('countryCode'),
                "countryName" => $this->input->post('countryName'),
                "address" => $this->input->post('address'),
                "location" => $this->input->post('location'),
                "loc" => array("lon" => floatval($this->input->post('longitude')), "lat" => floatval($this->input->post('latitude'))),
                "propertyType" => $this->input->post('propertyType'),
                "rating" => $this->input->post('rating'),
                "about" => $this->input->post('about'),
                "description" => $this->input->post('description'),
                "services" => $this->input->post('services')
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
        $db->discover_hotels->update($query, $update);
    }
    
    public function submit(){
        $m = new MongoClient();
        $db = $m->selectDB($this->db_name);
        $id = $this->input->post('id');
        if($id <> '')
            $this->hotel_update($id, $db);
        else
            $id = $this->hotel_insert($db);
        redirect('m_hotel/view/'.$id, 'refresh');
    }
    
    public function ajax_get_facilities(){
        $q = $this->input->post('q');
        $m = new MongoClient();
        $db = $m->selectDB($this->db_name);
        $f = $db->discover_facilities;
        $filter = array(
            'title' => array('$regex' => $q, '$options' => 'i')
        );
        $d = $f->find($filter)->sort(array('title' => 1))->limit(10);
        $result = array();
        foreach($d as $i){
            $result[] = array('id' => $i['_id']->{'$id'}, 'text' => $i['title']);
        }
        echo json_encode($result);
    }
    
    public function ajax_save_facilities(){
        $id = $this->input->post('id');
        $m = new MongoClient();
        $db = $m->selectDB($this->db_name);
        $ids = $this->input->post('ids');
        $discover_facilities = array();
        if(isset($ids) && $ids <> ""){
            foreach(explode(',' , $ids)  as $d_f_id){
                $mongo_id = new MongoId($d_f_id);
                $discover_facilities[] = MongoDBRef::create("discover_facilities", $mongo_id);
            }
        }
        $query = array(
            'id' => $id
        );
        $update = array(
            '$set' => array("discover_facilities" => $discover_facilities)
        );
        
        $db->discover_hotels->update($query, $update);
        $h = $db->discover_hotels->findOne(array("id" => $id));
        $data['hotel'] = $h;
        $facilities = array();
        $facility_ids = '';
        $facility_titles = '';
        if(isset($h['discover_facilities']) && count($h['discover_facilities'])){
            foreach($h['discover_facilities'] as $mongo_i){
                $i = MongoDBRef::get($db, $mongo_i);
                $facilities[] = array('id' => $i['_id']->{'$id'}, 'text' => $i['title']);
                $facility_ids .= $i['_id']->{'$id'}.',';
                $facility_titles .= $i['title'].', ';
            }
        }
        $facility_ids = rtrim($facility_ids, ",");
        $facility_titles = rtrim($facility_titles, ", ");
        $data['hotel_facilities'] = json_encode($facilities);
        $data['hotel_facility_ids'] = $facility_ids;
        $data['hotel_facility_titles'] = $facility_titles;
        
        $this->load->view('hotel/ajax_hotel_facilities', $data);
    }
    
    public function edit($id){
        if($this->session->userdata('logged_in')){
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title']= 'Edit hotel';
            $data['content']= 'hotel/m_form';
            $m = new MongoClient();
            $db = $m->selectDB($this->db_name);
            $h = $db->discover_hotels->findOne(array("id" => $id));
            $data['hotel']= $h;
            $data['jsIncludes'] = array('edit_page.js');
            $this->load->view('template', $data);
        }
        else redirect('login', 'refresh');
    }
    
    public function add(){
        if($this->session->userdata('logged_in')){
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title']= 'Add hotel';
            $data['content']= 'hotel/m_form';
            $data['jsIncludes'] = array('edit_page.js');
            $this->load->view('template', $data);
        }
        else redirect('login', 'refresh');
    }
    
    public function room_edit($id, $hotel_id){
        if($this->session->userdata('logged_in')){
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title']= 'Edit room';
            $data['content']= 'hotel/form_room';
            $m = new Mongo();
            $db = $m->selectDB($this->db_name);
            
            $match = array(
                        array(
                            '$project' => array(
                                "rooms"   => 1,
                            )
                        ),
                        array('$unwind' => '$rooms'),
                        array(
                        '$match' => array(
                            'rooms.id' => $id
                            )
                        )
                    );
            $r = $db->discover_hotels->aggregate($match);
            $room = $r['result']['0']['rooms'];
            $data['hotel_id'] = $hotel_id;
            $data['room'] = $room;
            $this->load->view('template', $data);
        }
        else redirect('login', 'refresh');
    }
    
    public function room_add($id){
        if($this->session->userdata('logged_in')){
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title']= 'Add room';
            $data['hotel_id']= $id;
            $data['content']= 'hotel/form_room';
            $this->load->view('template', $data);
        }
        else redirect('login', 'refresh');
    }
    
    function room_update($r1, $r2, $r3){
        $m = new Mongo();
        $db = $m->selectDB($this->db_name);
        $id = $this->input->post('id');
        $hotel_id = $this->input->post('hotel_id');
        $pic1 = '';
        $pic2 = '';
        $pi3 = '';
        if($r1<>'') 
            $pic1 = $r1['file_name'];
        else 
            $pic1 = $this->input->post('pic1name'); 
        if($r2<>'') 
            $pic2 = $r2['file_name'];
        else
            $pic2 = $this->input->post('pic2name');
        if($r3<>'') 
            $pic3 = $r3['file_name'];
        else
            $pic3 = $this->input->post('pic3name');
        
        $query = array(
            'id' => $hotel_id,
            'rooms.id' => $id
        );
        $update = array(
            '$set' => array(
                "rooms.$.title" => $this->input->post('title'),
                "rooms.$.description" => $this->input->post('description'),
                "rooms.$.num_person" => $this->input->post('num_person'),
                "rooms.$.price" => $this->input->post('price'),
                "rooms.$.pic1" => $pic1,
                "rooms.$.pic2" => $pic2,
                "rooms.$.pic3" => $pic3
            )
        );
        $db->discover_hotels->update($query, $update);
    }
    
    function room_insert($r1, $r2, $r3){
        $m = new Mongo();
        $db = $m->selectDB($this->db_name);
        $hotel_id = $this->input->post('hotel_id');
        $pic1 = '';
        $pic2 = '';
        $pic3 = '';
        if($r1<>'') 
            $pic1 = $r1['file_name'];
        else 
            $pic1 = $this->input->post('pic1name'); 
        if($r2<>'') 
            $pic2 = $r2['file_name'];
        else
            $pic2 = $this->input->post('pic2name');
        if($r3<>'') 
            $pic3 = $r3['file_name'];
        else
            $pic3 = $this->input->post('pic3name');
        
        $query = array(
            'id' => $hotel_id
        );
        $update = array(
            '$push' => array(
                "rooms" => array(
                    "id" => uniqid(),
                    "hotel_id" => $hotel_id,
                    "title" => $this->input->post('title'),
                    "description" => $this->input->post('description'),
                    "num_person" => $this->input->post('num_person'),
                    "price" => $this->input->post('price'),
                    "pic1" => $pic1,
                    "pic2" => $pic2,
                    "pic3" => $pic3
                )
            )
        );
        $db->discover_hotels->update($query, $update);
    }
    
    function room_submit(){
        if($this->session->userdata('logged_in')){
            $id = $this->input->post('id');
            $config['upload_path'] = './uploads/rooms/original';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size'] = 1024 * 8;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            if($this->upload->do_upload('pic1')) {
                $r1 = $this->upload->data();
                $this->Resize_Room_Pic($r1['file_name']);
            }
            else $r1='';
            if($this->upload->do_upload('pic2')){
                $r2 = $this->upload->data(); 
                $this->Resize_Room_Pic($r2['file_name']);
            }
            else $r2='';
            if($this->upload->do_upload('pic3')){
                $r3 = $this->upload->data(); 
                $this->Resize_Room_Pic($r3['file_name']);
            }
            else $r3='';
            if($id <>'') {
                $this->room_update($r1, $r2, $r3);
            }
            else {
                $this->room_insert($r1, $r2, $r3);
            }
            redirect('m_hotel/view/'.$this->input->post('hotel_id'), 'refresh');
        }
        else redirect('login', 'refresh');
    }
 
    function Resize_Room_Pic($file_name){
       $thumb_conf['image_library'] = 'GD2';
       $thumb_conf['maintain_ratio'] = TRUE;
       $thumb_conf['width'] = 200;
       $thumb_conf['height'] = 200;
       $thumb_conf['source_image'] = "./uploads/rooms/original/" . $file_name;
       $thumb_conf['new_image'] = "./uploads/rooms/thumb";
       $this->image_lib->initialize($thumb_conf);
       $this->image_lib->resize();
       $thumb_conf['width'] = 600;
       $thumb_conf['height'] = 600;
       $thumb_conf['new_image'] = "./uploads/rooms";
       $this->image_lib->initialize($thumb_conf);
       $this->image_lib->resize();
    }
    
    function room_delete($id, $hotel_id){
        if($this->session->userdata('logged_in')){
            $m = new Mongo();
            $db = $m->selectDB($this->db_name);
            $query = array(
                'id' => $hotel_id,
                'rooms.id' => $id
            );
            $update = array(
                '$pull' => array(
                    "rooms" => array(
                        "id" => $id
                    )
                )
            );
            $db->discover_hotels->update($query, $update);
            redirect('m_hotel/view/'.$hotel_id, 'refresh');
        }
        else redirect('login', 'refresh');
    }
    
    function review_add($id){
        if($this->session->userdata('logged_in')){
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title']= 'Add review';
            $data['hotel_id']= $id;
            $data['content']= 'hotel/form_review';
            $this->load->view('template', $data);
        }
        else redirect('login', 'refresh');
    }
    
    public function review_edit($id, $hotel_id){
        if($this->session->userdata('logged_in')){
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title']= 'Edit review';
            $data['content']= 'hotel/form_review';
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
            $r = $db->discover_hotels->aggregate($match);
            $review = $r['result']['0']['reviews'];
            $data['hotel_id'] = $hotel_id;
            $data['review'] = $review;
            $this->load->view('template', $data);
        }
        else redirect('login', 'refresh');
    }
    
    function review_update(){
        $m = new Mongo();
        $db = $m->selectDB($this->db_name);
        $id = $this->input->post('id');
        $hotel_id = $this->input->post('hotel_id');
        
        $query = array(
            'id' => $hotel_id,
            'reviews.id' => $id
        );
        $update = array(
            '$set' => array(
                "reviews.$.title" => $this->input->post('title'),
                "reviews.$.description" => $this->input->post('description')
            )
        );
        $db->discover_hotels->update($query, $update);
    }
    
    function review_insert(){
        $m = new Mongo();
        $db = $m->selectDB($this->db_name);
        $hotel_id = $this->input->post('hotel_id');
        
        $query = array(
            'id' => $hotel_id
        );
        $update = array(
            '$push' => array(
                "reviews" => array(
                    "id" => uniqid(),
                    "hotel_id" => $hotel_id,
                    "title" => $this->input->post('title'),
                    "description" => $this->input->post('description')
                )
            )
        );
        $db->discover_hotels->update($query, $update);
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
            redirect('m_hotel/view/'.$this->input->post('hotel_id'), 'refresh');
        }
        else redirect('login', 'refresh');
    }
    
    function review_delete($id, $hotel_id){
        if($this->session->userdata('logged_in')){
            $m = new Mongo();
            $db = $m->selectDB($this->db_name);
            $query = array(
                'id' => $hotel_id,
                'reviews.id' => $id
            );
            $update = array(
                '$pull' => array(
                    "reviews" => array(
                        "id" => $id
                    )
                )
            );
            $db->discover_hotels->update($query, $update);
            redirect('m_hotel/view/'.$hotel_id, 'refresh');
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
            $success = $db->discover_hotels->remove($query);
        }
        echo json_encode (array('success' => $success));
    }
    
    public function facilities(){
        if($this->session->userdata('logged_in')){
            $m = new Mongo();
            $db = $m->selectDB($this->db_name);
            $c = $db->discover_facilities->find();
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $data['title']= 'Manage facilities';
            $data['facilities']= $c;
            $data['content']= 'hotel/facilities';
            $data['jsIncludes'] = array('facility.js');
            $this->load->view('template', $data);
        }
        else redirect('login', 'refresh');
    }
 
    function facility_new(){
        if($this->session->userdata('logged_in')){
            $m = new Mongo();
            $db = $m->selectDB($this->db_name);
            $title = $this->input->post('title');
            $db->discover_facilities->insert(array(
                'id' => uniqid(),
                'title' => $title
            ));
            redirect('m_hotel/facilities', 'refresh');
        }
        else redirect('login', 'refresh');
    }
 
    function ajax_facility_save(){
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
        $success = $db->discover_facilities->update($query, $update);
        echo json_encode(array('success' => $success));
    }
}
