<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Hotelchain extends MY_Controller {

    function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('login', 'refresh');
        } else {
            $session_data = $this->session->userdata('logged_in');
            $action = $this->router->fetch_method();
            $role = $session_data['role'];
            if ($role == 'hotel_desc_writer') {
                $allowed = array('index', 'ajax_list', 'view', 'edit', 'submit', 'facilities', 'amenities', 'ajax_get_ameneties', 'ajax_amenity_save', 'amenity_new', 'ajax_get_facilities', 
                    'ajax_save_facilities', 'ajax_facility_save', 'facility_save', 'facility_new', 'fsearch', 'ajax_facility_type_save', 'ajax_has_count_save', 'edit_amenities', 'ajax_save_amenities', 'save_hotel_facilities', 'hotel_facilities');
                if (!in_array($action, $allowed))
                    redirect('thingstodo/index', 'refresh');
            }
        }
    }

    public function index($cc = 'all', $start = 0) {
        $this->load->library('pagination');
        $session_data = $this->session->userdata('logged_in');
        $user_id = $session_data['uid'];
//        $entity_types = json_decode($session_data['entity_types'], true);
        
        $entity_types = '';
        
        $user_hotel = new User_Model();
        $user_hotel->where('id', $user_id)->where('is_active', '1')->get();
        $result = $user_hotel->to_array();
        $entity_types = json_decode($result['entity_types'], true);
        
        if(!isset($entity_types['hotels']) || empty($entity_types['hotels']) ) 
            redirect('dashboard', 'refresh');
        
        $hotels_all = $entity_types['hotels'];
        $data['username'] = $session_data['username'];
        $data['title'] = 'Manage hotels';
        $data['content'] = 'hotelchain/list';
        $hotels = new Cms_Hotel_Model();
        $config['base_url'] = 'hotelchain/ajax_list';
        $res = $hotels->where_in('id', $hotels_all)->get();
        $data['hotels'] = $hotels;
//        $data['jsIncludes'] = array('hotels.js');
        $data['jsIncludes'] = array('hotels.js', 'slick.min.js');
        $data['cssIncludes'] = array('hotels.css', 'slick.css', 'slick-theme.css');
        $this->load->view('template', $data);
    }

    function ajax_list($start = 0) {
        $this->load->library('pagination');
        $cityName = $this->input->post('ci');
        $hotelName = $this->input->post('ho');
        $hotelId = intval($this->input->post('hi'));
//        $countryCode = $this->input->post('cc');
        $stars = $this->input->post('s');
        $countryName = $this->input->post('co');
        $hotels = new Cms_Hotel_Model();
        $config['uri_segment'] = 3;
        $config['per_page'] = 500;
        $config["num_links"] = 14;
        $config['base_url'] = 'hotelchain/ajax_list';
        if($hotelId > 0){
            $total = $hotels->where('id', $hotelId)->count();
        }
        else{
            $total = $hotels->like('name', $hotelName)->like('city', $cityName)->like('country_code', $countryName)->like('stars', $stars)->count();
        }
        $config['total_rows'] = $total;
        $limit = $total;
//    if($limit > 20000)
//        $limit = 20000;
//    $h->like('countryName', $countryName)->like('hotelName', $hotelName)->like('cityName', $cityName)->like('countryCode', $countryCode)->order_by('id')->get(200, $start);
        if($hotelId > 0){
            $res = $hotels->where('id', $hotelId)->get(500, $start);
        }
        else{
            $res = $hotels->like('name', $hotelName)->like('city', $cityName)->like('country_code', $countryName)->like('stars', $stars)->order_by('id')->get(500, $start);
        }
        
        $data['hotels'] = $hotels;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $this->load->view('hotelchain/ajax_list', $data);
    }
    
    public function discover_search(){
        $term = $this->input->post('term');
        $entity_type = intval($this->input->post('type'));
        $city_id = intval($this->input->post('city_id'));
        switch($entity_type){
            case 28:
                $h = new Cms_Hotel_Model();
                if($city_id > 0){
                    $h->where('published', 1)->where('city_id', $city_id)->like('name', $term)->get(30);
                }
                else{
                    $h->where('published', 1)->like('name', $term)->get(30);
                }
                $ret = $h->all_to_array();
                break;
            case 29:
                $h = new Global_Restaurant_Model();
                $h->where('published', 1)->like('name', $term)->get(30);
                $ret = $h->all_to_array();
                break;
            case 30:
                $h = new Poi_Model();
                $h->where('published', 1)->where('city_id', $city_id)->like('name', $term)->get(30);
                $ret = $h->all_to_array();
                break;
            case 63:
                $h = new Airport_Model();
                $h->where('published', 1)->where('city_id', $city_id)->like('name', $term)->get(30);
                $ret = $h->all_to_array();
                break;
        }
        $res = array();
        foreach($ret as $item){
            $id = $item['id'];
            $text = $item['name'];
            $res[] = array('id'=> $id, 'text'=> $text);
        }
        echo json_encode($res);
    }
   
    public function discoverbyid(){
        $id = $this->input->post('id');
        $entity_type = $this->input->post('type');
        $res = array();
        switch($entity_type){
            case 28:
                $h = new Cms_Hotel_Model();
                $h->where('id', $id)->get();
                $ret = $h->to_array();
                $res = array('id' => $ret['id'], 'text' => $ret['name']);
                break;
            case 29:
                $h = new Global_Restaurant_Model();
                $h->where('id', $id)->get();
                $ret = $h->to_array();
                $res = array('id' => $ret['id'], 'text' => $ret['name']);
                break;
            case 30:
                $h = new Poi_Model();
                $h->where('id', $id)->get();
                $ret = $h->to_array();
                $res = array('id' => $ret['id'], 'text' => $ret['name']);
                break;
            case 63:
                $h = new Airport_Model();
                $h->where('id', $id)->get();
                $ret = $h->to_array();
                $res = array('id' => $ret['id'], 'text' => $ret['name']);
                break;
        }
        echo json_encode($res);
    }
    
    public function hotel_search(){
        $this->load->library('pagination');
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title'] = 'Manage hotel search';
        $data['content'] = 'hotelchain/hotel_search';
        $hotel_searches = new Cms_Hotel_Search_Model();
        //echo '<pre>';print_r($data['countryes']);
        $config['uri_segment'] = 3;
        $config['per_page'] = 500;
        $config["num_links"] = 14;
        $config['base_url'] = 'hotelchain/ajax_hotel_search';
        $total = $hotel_searches->count();
        $config['total_rows'] = $total;
        $res = $hotel_searches->order_by('id')->get(500);
        $data['hotel_searches'] = $hotel_searches;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['jsIncludes'] = array('hotel_search.js');
        $this->load->view('template', $data);
    }
    
    public function hotel_search_ajax(){
        $this->load->library('pagination');
        $name = trim($this->input->post('name'));
        $keyword = trim($this->input->post('keyword'));
        $hotel_searches = new Cms_Hotel_Search_Model();
        $config['uri_segment'] = 3;
        $config['per_page'] = 500;
        $config["num_links"] = 14;
        $config['base_url'] = 'hotelchain/ajax_hotel_search';
        $total = $hotel_searches->like('name', $name)->like('keyword', $keyword)->count();
        $config['total_rows'] = $total;
        $hotel_searches->like('name', $name)->like('keyword', $keyword)->order_by('id')->get(500);
//        $hotel_searches->order_by('id')->get(500);
        $data['hotel_searches'] = $hotel_searches;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $this->load->view('hotelchain/ajax_hotel_search', $data);
    }
    
    public function hotel_search_delete($id){ 
        $userdata = $this->session->userdata('logged_in');
        $d1 = new Cms_Hotel_Search_Details_Model();
        $success = FALSE;
        if ($id <> '') {
            $d1->where('hotel_booking_id', $id)->get();
            foreach($d1 as $item){
                $item->delete();
            }
        }
        $d = new Cms_Hotel_Search_Model();
        if ($id <> '') {
            $d->where('id', $id)->get();
            $success = $d->delete();
        }
        echo json_encode(array('success' => $success));
    }
    
    public function ajax_delete_hotel_search_detail($id){
        $userdata = $this->session->userdata('logged_in');
        $d = new Cms_Hotel_Search_Details_Model();
        $success = FALSE;
        if ($id <> '') {
            $d->where('id', $id)->get();
            $success = $d->delete();
        }
        echo json_encode(array('success' => $success));
    }

    function ajax_upload_hotels(){   
        $userdata = $this->session->userdata('logged_in');
        $id = $this->input->post('id');
        $select = $this->input->post('select');
        $title = $this->input->post('title');
        $date = new DateTime();
        $todaytime = $date->getTimestamp();
        $status = "";
        $msg = "";
        $file_element_name = 'userfile';
        $type= 'hotels';
        if ($status != "error")
        { 
            $config['upload_path'] = '../media/hotels/'.$id.'/'.$select;
            $config['allowed_types'] = 'gif|jpeg|jpg|png';
            $config['encrypt_name'] = TRUE;
            $filename = str_replace(' ', '-', $title).'-'.$todaytime;
            $this->load->library('upload', $config);
            $this->load->model('cms_hotel_image_model');
    //        $this->load->model('activitylog_model');
            if (!file_exists(($config['upload_path'])))
                mkdir($config['upload_path'], 0775);
            $return_data = $this->upload->do_multi_upload($file_element_name);

            echo $this->upload->display_errors('', '');
            if (!$return_data)
            {
                $status = 'error';
                $msg = $this->upload->display_errors('', '');
                print_r($msg);exit;
            }
            else
            {   
                $i=0;
                foreach ( $return_data as $data){
                    $filename = $filename.$i.'.jpg';
                    $original_filename = $data['file_name'];
                    $extension = explode(".",$data['file_name']);
                    rename('../media/hotels/'.$id.'/'.$select.'/'.$original_filename, '../media/hotels/'.$id.'/'.$select.'/'.$filename);
                    $thumb_conf['image_library'] = 'GD2';
                    $thumb_conf['maintain_ratio'] = TRUE;
                    $thumb_conf['quality'] = '100%';
                   /* Large Size Image */
                   $thumb_conf['width'] = 994;
                   $thumb_conf['height'] = 530;
                   $thumb_conf['source_image'] = '../media/hotels/'.$id.'/'.$select.'/'.$filename;
                   $thumb_conf['new_image'] =  '../media/hotels/'.$id.'/'.$select.'/'.$filename;
                   $this->image_lib->initialize($thumb_conf);
                   if(!$this->image_lib->resize()){
                       $msg .= ', ' . $this->image_lib->display_errors();   
                   }
                   $file_id = $this->cms_hotel_image_model->insert_file($filename,$select, $id);
                    if($file_id)
                    {
    //                    $this->activitylog_model->insert_log($userdata['uid'], HOTEL_IMAGE_INSERT, $file_id);
                        $status = "success";
                        $msg .= "File successfully uploaded";
                    }
                    else
                    {
                        unlink($data['full_path']);
                        $status = "error";
                        $msg = "Something went wrong when saving the file, please try again.";
                    }
                    $i++;
                }
            }
            @unlink($_FILES[$file_element_name]);
        }
        echo json_encode(array('status' => $status));
    }

    function ajax_edit_location(){
        $userdata = $this->session->userdata('logged_in');
        $image_id = $this->input->post('image_id');
        $hotel_id = $this->input->post('hotel_id');
        $image_name = $this->input->post('image_name');
        $old_location = $this->input->post('old_location');
        $select = $this->input->post('image_types');
        $old_path = '../media/hotels/'.$hotel_id.'/'.$old_location.'/'.$image_name;
        $new_path = '../media/hotels/'.$hotel_id.'/'.$select.'/'.$image_name;
        
        if (file_exists($old_path)){
            $thenew_path = '../media/hotels/'.$hotel_id.'/'.$select;
            if (!file_exists(($thenew_path)))
                mkdir($thenew_path, 0775);
            copy($old_path, $new_path);
            unlink($old_path);
            $this->load->model('cms_hotel_image_model');
            if($this->cms_hotel_image_model->update_location_pic($image_id,$old_location,$select)){
                redirect('hotelchain/view/'.$hotel_id, 'refresh');        
            }
        }
        
    }
    
    public function hotel_search_view($id){
        $data['title'] = '';
        $data['content'] = 'hotelchain/hotel_search_view';
        $hotel_search = new Cms_Hotel_Search_Model();
        $hotel_search->where('id', $id)->get();
        $data['hotel_search'] = $hotel_search->to_array();
        $hotel_search_details = new Cms_Hotel_Search_Details_Model();
        $hotel_search_details->where('hotel_booking_id', $id)->get();
//        $data['hotel_search_details'] = $hotel_search_details->all_to_array();
        $data['hotel_search_details'] = $hotel_search_details;
        $this->load->view('template', $data);
    }
    
    public function hotel_search_add(){
        $data['title'] = 'Add Hotel search';
        $data['content'] = 'hotelchain/hotel_search_form';
        $this->load->view('template', $data);
    }
    
    public function hotel_search_edit($id){
        $hotel_search = new Cms_Hotel_Search_Model();
        $hotel_search->where('id', $id)->get();
        $data['title'] = 'Edit Hotel search';
        $data['content'] = 'hotelchain/hotel_search_form';
        $data['hotel_search'] = $hotel_search->to_array();
        $this->load->view('template', $data);
    }
    
    public function hotel_search_submit(){
        $id = intval($this->input->post('id'));
        $name = $this->input->post('name');
        $keyword = $this->input->post('keyword');
        $hotel_search = new Cms_Hotel_Search_Model();
        if($id > 0){
            $hotel_search->where('id', $id)->get();
        }
        $hotel_search->name = $name;
        $hotel_search->keyword = $keyword;
        $hotel_search->save();
        redirect('hotelchain/hotel_search_view/'.$hotel_search->id, 'refresh');
    }
    
    public function search_detail_add($id){
        $hotel_search = new Cms_Hotel_Search_Model();
        $hotel_search->where('id', $id)->get();
        $data['title'] = $hotel_search->keyword;
        $data['content'] = 'hotelchain/hotel_search_detail_form';
        $data['hotel_search'] = $hotel_search->to_array();
        $data['hotel_search_id'] = $hotel_search->id;
        $data['jsIncludes'] = array('hotel_search_detail_add.js');
        $this->load->view('template', $data);
    }
    
    public function search_detail_edit($id){
        $hotel_search_details = new Cms_Hotel_Search_Details_Model();
        $hotel_search_details->where('id', $id)->get();
        $hotel_search = new Cms_Hotel_Search_Model();
        $hotel_search->where('id', $hotel_search_details->hotel_booking_id)->get();
        $data['title'] = $hotel_search->keyword;
        $data['content'] = 'hotelchain/hotel_search_detail_form';
        $data['hotel_search_details'] = $hotel_search_details->to_array();
        $data['hotel_search_id'] = $hotel_search_details->hotel_booking_id;
        $data['jsIncludes'] = array('hotel_search_detail_add.js');
        $this->load->view('template', $data);
    }
    
    public function search_detail_submit(){
        $id = intval($this->input->post('id'));
        $hotel_booking_id = intval($this->input->post('hotel_booking_id'));
        $name = $this->input->post('name');
        $type = intval($this->input->post('entity_type'));
        $popular = ($this->input->post('popular')) ? $this->input->post('popular') : 0;
        $country_id = intval($this->input->post('country_id'));
        $state_id = intval($this->input->post('state_id'));
        $city_id = intval($this->input->post('city_id'));
        $hotel_id = intval($this->input->post('hotel_id'));
        $poi_id = intval($this->input->post('poi_id'));
        $airport_id = intval($this->input->post('airport_id'));
        
        $hotel_search_detail = new Cms_Hotel_Search_Details_Model();
        if($id > 0){
            $hotel_search_detail->where('id', $id)->get();
        }
        $hotel_search_detail->hotel_booking_id = $hotel_booking_id;
        $hotel_search_detail->name = $name;
        $hotel_search_detail->entity_type = $type;
        $hotel_search_detail->popular = $popular;
        if(!empty($name) && $type > 0){
            $valid = TRUE;
            switch($type){
                case SOCIAL_ENTITY_HOTEL:
                    if($hotel_id > 0){
                        $hotel_search_detail->entity_id = $hotel_id;
                        $hotel = new Cms_Hotel_Model();
                        $hotel->where('id', $hotel_id)->get();
                        $hotel_search_detail->latitude = $hotel->latitude;
                        $hotel_search_detail->longitude = $hotel->longitude;
                    }
                    else{
                        $valid = FALSE;
                    }
                    break;
                case SOCIAL_ENTITY_LANDMARK:
                    if($poi_id > 0){
                        $hotel_search_detail->entity_id = $poi_id;
                        $poi = new Poi_Model();
                        $poi->where('id', $poi_id)->get();
                        $hotel_search_detail->latitude = $poi->latitude;
                        $hotel_search_detail->longitude = $poi->longitude;
                    }
                    else{
                        $valid = FALSE;
                    }
                    break;
                case SOCIAL_ENTITY_AIRPORT:
                    if($airport_id > 0){
                        $hotel_search_detail->entity_id = $airport_id;
                        $airport = new Airport_Model();
                        $airport->where('id', $airport_id)->get();
                        $hotel_search_detail->latitude = $airport->latitude;
                        $hotel_search_detail->longitude = $airport->longitude;
                    }
                    else{
                        $valid = FALSE;
                    }
                    break;
                case SOCIAL_ENTITY_CITY:
                case SOCIAL_ENTITY_DOWNTOWN:
                    if($city_id > 0){
                        $hotel_search_detail->entity_id = $city_id;
                        $webgeocity = new WebGeoCity_Model();
                        $webgeocity->where('id', $city_id)->get();
                        $hotel_search_detail->latitude = $webgeocity->latitude;
                        $hotel_search_detail->longitude = $webgeocity->longitude;
                    }
                    else{
                        $valid = TRUE;
                    }
                    break;
                case SOCIAL_ENTITY_COUNTRY:
                    if($country_id > 0){
                        $hotel_search_detail->entity_id = $country_id;
                        $country = new Country_Model();
                        $country->where('id', $country_id)->get();
                        $hotel_search_detail->latitude = $country->latitude;
                        $hotel_search_detail->longitude = $country->longitude;
                        $hotel_search_detail->country_code = $country->code;
                    }
                    else{
                        $valid = FALSE;
                    }
                    break;
                case SOCIAL_ENTITY_STATE:
                    if($state_id > 0){
                        $hotel_search_detail->entity_id = $state_id;
//                        $country = new Cms_State_Model();
//                        $country->where('id', $country_id)->get();
//                        $hotel_search_detail->latitude = $webgeocity->latitude;
//                        $hotel_search_detail->longitude = $webgeocity->longitude;
                    }
                    else{
                        $valid = FALSE;
                    }
                    break;
            }
            if($valid){
                $hotel_search_detail->save();
            }
        }
        redirect('hotelchain/hotel_search_view/'.$hotel_booking_id, 'refresh');
    }
    
    public function default_pic(){
        $image_id = intval($this->input->post('image_id'));
        $hotel_image = new Cms_Hotel_Image_Model();
        $hotel_image->where('id', $image_id)->get();
        $hotel_id = $hotel_image->hotel_id;
        $hotel_image->hotel_id = 0;
        $hotel_image->save();
        $hotel_images_obj = new Cms_Hotel_Image_Model();
        $hotel_images_obj->where('hotel_id', $hotel_id)->get();
        $hotel_images = $hotel_images_obj->all_to_array();
        $this->load->view('hotelchain/images', array('hotel_images' => $hotel_images));
    }
    
    public function ajax_delete_image(){
        $image_id = intval($this->input->post('image_id'));
        $hotel_image = new Cms_Hotel_Image_Model();
        $hotel_image->where('id', $image_id)->get();
        $hotel_id = $hotel_image->hotel_id;
        $location = $hotel_image->location;
        $hotel_image->hotel_id = 0;
        $hotel_image->save();
        $hotel_images_obj = new Cms_Hotel_Image_Model();
        $hotel_images_obj->where('hotel_id', $hotel_id)->get();
        $hotel_images = $hotel_images_obj->all_to_array();
        $locations = ['appartment','approachMap','ballRoom','banquet','bar','bathroom','beach','beautyCenter','bowlingAlley','breakfastRoom','brineBath','buffet','businessRoom','cafeBistro','certificate','comfortRoom','conferenceFoyer','conferenceOffice','conferenceRoom','conferences','congressHall','cosmetic','doubleRoomComfort','doubleRoomEconomy','doubleRoomStandard','economyRoom','events','exhibitionArea','exteriorView','familyRoom','fitness','fourBedRoom','garden','golfCourse','groupRoom','guestRoom','hall','hamam','hotelIndoorArea','hotelKitchen','hotelOutdoorArea','info','inside','juniorSuite','kitchen','kitchenInRoom','manege','massageRoom','meetingRoom','none','outlook','quietArea','rasulBath','readingRoom','reception','restaurant','restaurant1','restaurant2','restaurantBreakfastRoom','room','roomSketch','roomWithBalcony','roomWithGardenView','roomWithLakeView','roomWithMountainsView','roomWithPoolView','roomWithRiverView','roomWithSeaView','roomWithTerrace','sanarium','sauna','seatingPlan','seminarRoom','shop','singleRoomComfort','singleRoomEconomy','singleRoomStandard','skittlingAlley','sportsFacilities','standardRoom','steamBath','suite','superiorRoom ','surrounding','swimmingPool','televisionRoom','tennisCourt','terrace','threeBedRoom','trainingRoom','wellness','wellnessFitness','whirlpool'];
        $this->load->view('hotelchain/images', array('hotel_images' => $hotel_images, 'id' =>$hotel_id, 'locations' => $locations ));
//        echo json_encode (array('success' => TRUE));
        //$success = $hotel_image->delete();
        //echo json_encode (array('success' => $success));
    }

    public function view($id) {
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title'] = 'View hotel';
        $data['content'] = 'hotelchain/view';
        $hotel = new Cms_Hotel_Model();
        $hotel->where('id', $id)->get();
        $hotel->facility->where('published = 1')->order_by('name')->get();
        $hotel_array = $hotel->to_array();
        $data['hotel'] = $hotel_array;
        
        $location = array();
        
        $hotel->image->get();
        $data['hotel_images'] = $hotel->image->all_to_array();
        
        $location = ['appartment','approachMap','ballRoom','banquet','bar','bathroom','beach','beautyCenter','bowlingAlley','breakfastRoom','brineBath','buffet','businessRoom','cafeBistro','certificate','comfortRoom','conferenceFoyer','conferenceOffice','conferenceRoom','conferences','congressHall','cosmetic','doubleRoomComfort','doubleRoomEconomy','doubleRoomStandard','economyRoom','events','exhibitionArea','exteriorView','familyRoom','fitness','fourBedRoom','garden','golfCourse','groupRoom','guestRoom','hall','hamam','hotelIndoorArea','hotelKitchen','hotelOutdoorArea','info','inside','juniorSuite','kitchen','kitchenInRoom','manege','massageRoom','meetingRoom','none','outlook','quietArea','rasulBath','readingRoom','reception','restaurant','restaurant1','restaurant2','restaurantBreakfastRoom','room','roomSketch','roomWithBalcony','roomWithGardenView','roomWithLakeView','roomWithMountainsView','roomWithPoolView','roomWithRiverView','roomWithSeaView','roomWithTerrace','sanarium','sauna','seatingPlan','seminarRoom','shop','singleRoomComfort','singleRoomEconomy','singleRoomStandard','skittlingAlley','sportsFacilities','standardRoom','steamBath','suite','superiorRoom ','surrounding','swimmingPool','televisionRoom','tennisCourt','terrace','threeBedRoom','trainingRoom','wellness','wellnessFitness','whirlpool'];
        
        $facility_ids = array();
        $facility_titles = '';
        foreach ($hotel->facility as $i) {
            $facility_ids[] = $i->id;
            $facility_titles .= $i->name . ', ';
        }
        $facility_titles = rtrim($facility_titles, ", ");
        $data['hotel_facility_titles'] = $facility_titles;
        $data['locations'] = $location;
        $facilities = new Cms_Facility_Model();
        $facilities->order_by('name')->get();
        $result = array();
        foreach ($facilities as $i) {
            $selected = FALSE;
            if (in_array($i->id, $facility_ids))
                $selected = TRUE;
            $result[] = array('id' => $i->id, 'text' => $i->name, 'selected' => $selected);
        }
        $data['facilities_all'] = $result;
        
        $amenity_titles = '';
        $hotel->amenity->include_join_fields()->get();
        foreach ($hotel->amenity as $amenity) {
            $count_value_str = ($amenity->join_count_value > 0 ? $amenity->join_count_value . ' ' : '');
            $amenity_titles .= $count_value_str . $amenity->name . ', ';
        }
        $amenity_titles = rtrim($amenity_titles, ", ");
        $data['amenity_titles'] = $amenity_titles;

        $data['jsIncludes'] = array('hotels.js', 'slick.min.js');
        $data['cssIncludes'] = array('hotels.css', 'slick.css', 'slick-theme.css');
        $this->load->view('template', $data);
    }

    public function view_old($id) {
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title'] = 'View hotel';
        $data['content'] = 'hotelchain/view';
        $hotel = new Cms_Hotel_Model();
        $hotel->where('id', $id)->get();
        $hotel->facility->get();
        $data['hotel'] = $hotel->to_array();
        $facilities = array();
        $facility_ids = '';
        $facility_titles = '';
        $facility_ids_arr = array();
        foreach ($hotel->facility as $facility) {
            $facilities[] = array('id' => $facility->id, 'text' => $facility->name);
            $facility_ids_arr[] = $facility->id;
            $facility_ids .= $facility->id . ',';
            $facility_titles .= $facility->name . ', ';
        }
        $facility_ids = rtrim($facility_ids, ",");
        $facility_titles = rtrim($facility_titles, ", ");
        $data['facility_ids_arr'] = $facility_ids_arr;
        $data['hotel_facilities'] = json_encode($facilities);
        $data['hotel_facility_ids'] = $facility_ids;
        $data['hotel_facility_titles'] = $facility_titles;

        $facilities_all = new Cms_Facility_Model();
        $facilities_all->order_by('name')->get();
        $data['facilities_all'] = $facilities_all->all_to_array();

        $amenity_titles = '';
        $hotel->amenity->include_join_fields()->get();
        foreach ($hotel->amenity as $amenity) {
            $count_value_str = ($amenity->join_count_value > 0 ? $amenity->join_count_value . ' ' : '');
            $amenity_titles .= $count_value_str . $amenity->name . ', ';
        }
        $amenity_titles = rtrim($amenity_titles, ", ");
        $data['amenity_titles'] = $amenity_titles;

        $data['cityName'] = $hotel->city;
        $data['jsIncludes'] = array('hotels.js');
        $data['cssIncludes'] = array('hotels.css');

        $this->load->view('template', $data);
    }

    public function edit($id) {
        $session_data = $this->session->userdata('logged_in');
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title'] = 'Edit hotel';
        if ($session_data['role'] == 'hotel_desc_writer') {
            $data['content'] = 'hotelchain/hdw_form';
        } else {
            $data['content'] = 'hotelchain/form';
        }
        $hotel = new Cms_Hotel_Model();
        $hotel->where('id', $id)->get();
        $data['hotel'] = $hotel->to_array();
        $data['jsIncludes'] = array('edit_page.js');
        $this->load->view('template', $data);
    }

    public function add() {
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title'] = 'Add hotel';
        $data['content'] = 'hotelchain/form';
        $data['jsIncludes'] = array('edit_page.js');
        $this->load->view('template', $data);
    }

    public function submit() {
        $userdata = $this->session->userdata('logged_in');
        $id = $this->input->post('id');
        $hotel = new Cms_Hotel_Model();
        if ($id <> '') {
            $hotel->where('id', $id)->get();
        }
        if ($userdata['role'] == 'hotel_desc_writer') {
            $hotel->description = $this->input->post('description');
        } else {
            $hotel->name = $this->input->post('name');
            $stars = $this->input->post('stars');
            if (!empty($stars)) {
                $hotel->stars = $stars;
            }
            $latitude = $this->input->post('latitude');
            if (!empty($latitude)) {
                $hotel->latitude = $latitude;
            }
            $longitude = $this->input->post('longitude');
            if (!empty($longitude)) {
                $hotel->longitude = $longitude;
            }
            $hotel->description = $this->input->post('description');
            $hotel->city = $this->input->post('city');
            //        $hotel->city_id = $this->input->post('cityId');
            $hotel->zip_code = $this->input->post('zipcode');
        }

        $hotel->save();
        $this->load->model('activitylog_model');
        $activity_code = $id == '' ? HOTEL_INSERT : HOTEL_UPDATE;
        $this->activitylog_model->insert_log($userdata['uid'], $activity_code, $hotel->id);
        redirect('hotelchain/view/' . $hotel->id, 'refresh');
    }

    public function amenities() {
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title'] = 'Manage amenities';
        $amenities = new Cms_Amenity_Model();
        $data['amenities'] = $amenities->get()->all_to_array();
        $data['content'] = 'hotelchain/amenities';
        $data['jsIncludes'] = array('amenities.js');
        $this->load->view('template', $data);
    }

    public function ajax_get_ameneties() {
        $q = $this->input->post('q');
        $amenities = new Cms_Amenity_Model();
        $amenities->like('name', $q)->order_by('name')->get(10);
        $result = array();
        foreach ($amenities as $amenity) {
            $result[] = array('id' => $amenity->id, 'text' => $amenity->name);
        }
        echo json_encode($result);
    }

    function ajax_amenity_save() {
        $id = explode('_', $this->input->post('id'));
        $value = $this->input->post('name');
        $amenity = new Cms_Amenity_Model();
        $amenity->where('id', $id[1])->get();
        $amenity->name = $value;
        $success = $amenity->save();
        echo json_encode(array('success' => $success));
    }

    function amenity_new() {
        $name = $this->input->post('name');
        $has_count = intval($this->input->post('has_count'));
        if ($has_count > 1 || $has_count < 0) {
            $has_count = 0;
        }
        $amenity = new Cms_Amenity_Model();
        $amenity->name = $name;
        $amenity->has_count = $has_count;
        $amenity->save();
        redirect('hotelchain/amenities', 'refresh');
    }

    public function facilities() {
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title'] = 'Manage facilities';
        $facilities = new Cms_Facility_Model();
        $data['facilities'] = $facilities->where('published', 1)->get()->all_to_array();
        $facility_types = new Cms_Facility_Type_Model();
        $data['facility_types'] = $facility_types->get()->all_to_array();
        $data['content'] = 'hotelchain/facilities';
        $data['jsIncludes'] = array('facilities.js');
        $this->load->view('template', $data);
    }
    
    public function ajax_delete_facility($id){
        $userdata = $this->session->userdata('logged_in');
        $facility = new Cms_Facility_Model();
        $success = FALSE;
        if ($id <> '') {
            $facility->where('id', $id)->get();
            $facility->published = -2;
            $success = $facility->save();
        }
        echo json_encode(array('success' => $success));
    }

    public function ajax_get_facilities() {
        $q = $this->input->post('q');
        $facilities = new Cms_Facility_Model();
        $facilities->like('name', $q)->order_by('name')->get(10);
        $result = array();
        foreach ($facilities as $facility) {
            $result[] = array('id' => $facility->id, 'text' => $facility->name);
        }
        echo json_encode($result);
    }
    
    public function save_hotel_facilities(){
        $this->load->model('Ci_Facility_Model');
        $hotel_id = $this->input->post('id');
        $facility_ids = $this->input->post('facilities');
        $hotel = new Cms_Hotel_Model();
        $hotel->where('id', $hotel_id)->get();
        $hotel->facility->order_by('name')->get();
        $current_facility_ids = [];
        $hotel_facilities = $hotel->facility->all_to_array();
        foreach($hotel_facilities as $facility){
            $current_facility_ids[] = $facility['id'];
        }
        $deleted = array_diff($current_facility_ids, $facility_ids);
        $added = array_diff($facility_ids, $current_facility_ids);
        $hotel_facility_model = new Ci_Facility_Model();
        $hotel_facility_model->add_hotel_facilites($hotel_id, $added);
        $hotel_facility_model->delete_hotel_facilities($hotel_id, $deleted);
//        echo json_encode(array('deleted' => $deleted, 'added' => $added, 'current' => $current_facility_ids, 'new' => $facility_ids));
    }
    
    public function hotel_facilities($id){
        $this->load->model('Ci_Facility_Model');
        $hotel_facilities = new Ci_Facility_Model();
        $hotel_facilities_result = $hotel_facilities->hotel_facilities($id);
        $facility_types = [];
        $facility_types_counter = [];
        foreach($hotel_facilities_result as $item){
            if(!isset($facility_types[$item['type_id']])){
                $facility_types[$item['type_id']] = array(
                    'id' => $item['type_id'],
                    'name' => $item['type_name'],
                    'selected' => '0',
                    'facilities' => array()
                );
            }
            if(!isset($facility_types_counter[$item['type_id']])){
                $facility_types_counter[$item['type_id']] = array(
                    'id' => $item['type_id'],
                    'selected' => 0,
                    'total' => 0
                );
            }
            $facility_types[$item['type_id']]['facilities'][] = array(
                'id' => $item['id'],
                'name' => $item['name'],
                'selected' => $item['selected']
            );
            if($item['selected'] == '1'){
                $facility_types_counter[$item['type_id']]['selected']++;
            }
            $facility_types_counter[$item['type_id']]['total']++;
        }
        foreach($facility_types as $item){
            if($facility_types_counter[$item['id']]['total'] == $facility_types_counter[$item['id']]['selected']){
                $facility_types[$item['id']]['selected'] = '1';
            }
        }
        
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $hotel = new Cms_Hotel_Model();
        $hotel->where('id', $id)->get();
        $hotel_array = $hotel->to_array();
        $data['hotel'] = $hotel_array;
        $data['title'] = 'Edit hotel facilities (' . $hotel_array['name'] . ')';
        $data['content'] = 'hotelchain/hotel_facilities';
//        echo json_encode($facility_types);exit;
        
        $data['facility_types'] = $facility_types;
        $data['jsIncludes'] = array('hotel_facilities.js', 'masonry.pkgd.min.js', 'jquery.loading.block.js');
        $data['cssIncludes'] = array('hotel_facilities.css');
        $this->load->view('template', $data);
    }
    
    public function hotel_facilities_test($id){
        $this->load->model('Ci_Facility_Model');
        $hf_model = new Ci_Facility_Model();
        $result = $hf_model->hotel_facilities($id);
//        echo json_encode($result);exit;
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title'] = 'Edit hotel facilities';
        $data['content'] = 'hotelchain/hotel_facilities';
        $hotel = new Cms_Hotel_Model();
        $hotel->where('id', $id)->get();
        $hotel->facility->order_by('name')->get();
        $hotel_facilities = $hotel->facility->all_to_array();
//        echo json_encode($hotel_facilities);
        $facilities = new Cms_Facility_Model();
        $facilities->order_by('name')->get();
        $all_facilities = $facilities->all_to_array();
//        echo json_encode($all_facilities);
        echo json_encode(array_merge($all_facilities, $hotel_facilities));
        return;
        $hotel_array = $hotel->to_array();
        $data['hotel'] = $hotel_array;
        
        $facility_ids = array();
        $facility_titles = '';
        foreach ($hotel->facility as $i) {
            $facility_ids[] = $i->id;
            $facility_titles .= $i->name . ', ';
        }
        $facility_titles = rtrim($facility_titles, ", ");
        $data['hotel_facility_titles'] = $facility_titles;
        $facilities = new Cms_Facility_Model();
        $facilities->order_by('name')->get();
        $result = array();
        foreach ($facilities as $i) {
            $selected = FALSE;
            if (in_array($i->id, $facility_ids))
                $selected = TRUE;
            $result[] = array('id' => $i->id, 'text' => $i->name, 'selected' => $selected);
        }
        $data['facilities_all'] = $result;

        $data['jsIncludes'] = array('hotels.js', 'slick.min.js');
        $data['cssIncludes'] = array('hotels.css', 'slick.css', 'slick-theme.css');
        $this->load->view('template', $data);
    }

    public function ajax_save_facilities() {
        $userdata = $this->session->userdata('logged_in');
        $id = $this->input->post('id');
        $hotel = new Cms_Hotel_Model();
        $hotel->where('id', $id)->get();
        $ids = $this->input->post('ids');
        $f_del = new Cms_Facility_Model();
        if (isset($ids) && $ids <> "") {
            $f_del->where('id NOT IN (' . $ids . ')')->get();
        } else {
            $f_del->get();
        }
        $hotel->delete_facility($f_del->all);
        if (isset($ids) && $ids <> "") {
            $f = new Cms_Facility_Model();
            $f->where('id IN (' . $ids . ')')->get();
            $hotel->save(array('facility' => $f->all));
        }
        $this->load->model('activitylog_model');
        $this->activitylog_model->insert_log($userdata['uid'], HOTEL_UPDATE, $hotel->id);
        $data['hotel'] = $hotel;
        $hotel->facility->order_by('name')->get();

        $facility_ids = array();
        $facility_titles = '';
        foreach ($hotel->facility as $i) {
            $facility_ids[] = $i->id;
            $facility_titles .= $i->name . ', ';
        }
        $facility_titles = rtrim($facility_titles, ", ");
        $data['hotel_facility_titles'] = $facility_titles;
        $facilities = new Cms_Facility_Model();
        $facilities->order_by('name')->get();
        $result = array();
        foreach ($facilities as $i) {
            $selected = FALSE;
            if (in_array($i->id, $facility_ids))
                $selected = TRUE;
            $result[] = array('id' => $i->id, 'text' => $i->name, 'selected' => $selected);
        }
        $data['facilities_all'] = $result;
        $this->load->view('hotelchain/ajax_hotel_facilities', $data);
    }

    public function ajax_save_facilities_old() {
        $userdata = $this->session->userdata('logged_in');
        $id = $this->input->post('id');
        $hotel = new Cms_Hotel_Model();
        $hotel->where('id', $id)->get();
        $ids = $this->input->post('ids');
        $f_del = new Cms_Facility_Model();
        if (isset($ids) && $ids <> "") {
            $f_del->where('id NOT IN (' . $ids . ')')->get();
        } else {
            $f_del->get();
        }
        $hotel->delete_facility($f_del->all);
        if (isset($ids) && $ids <> "") {
            $f = new Cms_Facility_Model();
            $f->where('id IN (' . $ids . ')')->get();
            $hotel->save(array('facility' => $f->all));
        }
        $this->load->model('activitylog_model');
        $this->activitylog_model->insert_log($userdata['uid'], HOTEL_UPDATE, $hotel->id);
        $data['hotel'] = $hotel;
        $hotel->facility->get();
        $facilities = array();
        $facility_ids = '';
        $facility_titles = '';
        foreach ($hotel->facility as $i) {
            $facilities[] = array('id' => $i->id, 'text' => $i->name);
            $facility_ids .= $i->id . ',';
            $facility_titles .= $i->name . ', ';
        }
        $facility_ids = rtrim($facility_ids, ",");
        $facility_titles = rtrim($facility_titles, ", ");
        $data['hotel_facilities'] = json_encode($facilities);
        $data['hotel_facility_ids'] = $facility_ids;
        $data['hotel_facility_titles'] = $facility_titles;
        $this->load->view('hotelchain/ajax_hotel_facilities', $data);
    }

    function ajax_facility_save() {
        $id = explode('_', $this->input->post('id'));
        $value = $this->input->post('name');
        $facility = new Cms_Facility_Model();
        $facility->where('id', $id[1])->get();
        $facility->name = $value;
        $success = $facility->save();
        echo json_encode(array('success' => $success));
    }

    function facility_save() {
        $id = explode('_', $this->input->post('id'));
        $value = $this->input->post('value');
        $facility = new Cms_Facility_Model();
        $facility->where('id', $id[1])->get();
        $facility->name = $value;
        $facility->save();
        echo $value;
    }
    
    function facility_type_new(){
        $name = $this->input->post('name');
        $facility_type = new Cms_Facility_Type_Model();
        $facility_type->name = $name;
        $facility_type->save();
        redirect('hotelchain/facilities', 'refresh');
    }

    function facility_new() {
        $name = $this->input->post('name');
        $type = $this->input->post('type');
        $facility = new Cms_Facility_Model();
        $facility->name = $name;
        $facility->type_id = $type;
        $facility->save();
        redirect('hotelchain/facilities', 'refresh');
    }

    function fsearch() {
        $str = $_GET['term'];
        $facilities = new Cms_Facility_Model();
        $facilities->where('name like', "%$str%")->get();
        $i = 0;
        foreach ($facilities as $f) {
            $arr[$i] = array('id' => $f->id, 'value' => $f->name, 'label' => $f->name);
            $i++;
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($arr));
    }

    function ajax_delete($id) {
        $userdata = $this->session->userdata('logged_in');
        $d = new Cms_Hotel_Model();
        $success = FALSE;
        if ($id <> '') {
            $d->where('id', $id)->get();
            $success = $d->delete();
            $this->load->model('activitylog_model');
            $this->activitylog_model->insert_log($userdata['uid'], HOTEL_DELETE, $id);
        }
        echo json_encode(array('success' => $success));
    }

    function delete($id) {
        $d = new Cms_Hotel_Model();
        if ($id <> '') {
            $d->where('id', $id)->get();
            $city = $d->cityName;
            $d->delete();
        }
        redirect('hotelchain/index/' . $city, 'refresh');
    }
    
    function ajax_level_save() {
        $id = explode('_', $this->input->post('id'));
        $value = $this->input->post('level');
        $facility = new Cms_Facility_Model();
        $facility->where('id', $id[1])->get();
        $facility->amenity_level = $value;
        $success = $facility->save();
        echo json_encode(array('success' => $success));
    }

    function ajax_facility_type_save() {
        $id = explode('_', $this->input->post('id'));
        $value = $this->input->post('type_id');
        $facility = new Cms_Facility_Model();
        $facility->where('id', $id[1])->get();
        $facility->type_id = $value;
        $success = $facility->save();
        echo json_encode(array('success' => $success));
    }

    function ajax_has_count_save() {
        $id = explode('_', $this->input->post('id'));
        $has_count = intval($this->input->post('has_count'));
        if ($has_count > 1 || $has_count < 0) {
            $has_count = 0;
        }
        $amenity = new Cms_Amenity_Model();
        $amenity->where('id', $id[1])->get();
        $amenity->has_count = $has_count;
        $success = $amenity->save();
        echo json_encode(array('success' => $success));
    }

    function edit_amenities($id) {
        $hotel = new Cms_Hotel_Model();
        $hotel->where('id', $id)->get();
        $data['hotel_id'] = $hotel->id;
        $data['title'] = 'Manage amenities for ' . $hotel->name . ' hotel';

        $amenities = $hotel->amenity->include_join_fields()->get();
        $data['hotel_amenities'] = $amenities;

        $amenities_all = new Cms_Amenity_Model();
        $amenities_all->order_by('name')->get();
        $data['amenities_all'] = $amenities_all->all_to_array();

        $data['jsIncludes'] = array('hotel_amenities.js');
        $data['content'] = 'hotelchain/hotel_amenities';
        $this->load->view('template', $data);
    }

    function ajax_save_amenities() {
        $userdata = $this->session->userdata('logged_in');
        $hotel_id = intval($this->input->post('hotel_id'));
        $amenities = $this->input->post('amenities');
        $hotel = new Cms_Hotel_Model();
        $hotel->where('id', $hotel_id)->get();
        $a_del = new Cms_Amenity_Model();
        $a_del->get();
        $hotel->delete_amenity($a_del->all);
        foreach ($amenities as $amenity) {
            $hotel_amenity = new Cms_Hotel_Amenity_Model();
            $hotel_amenity->hotel_id = $hotel_id;
            $hotel_amenity->amenity_id = intval($amenity['id']);
            $hotel_amenity->count_value = intval($amenity['count']);
            $hotel_amenity->save();
        }
        $this->load->model('activitylog_model');
        $this->activitylog_model->insert_log($userdata['uid'], HOTEL_UPDATE, $hotel->id);
        echo json_encode(array('success' => 1));
    }

    function default_image_update($hotel_id,$image_id){
        $data['content']= 'hotelchain/view';    
        if(!$hotel_id){
            $hotel_id = $this->uri->segment(3);
            $image_id = $this->uri->segment(4);
        }
        if(!$image_id){
            $hotel_id = $this->uri->segment(3);
            $image_id = $this->uri->segment(4);
        }
        $this->load->model('cms_hotel_image_model');
        if($this->cms_hotel_image_model->update_default_pic($hotel_id,$image_id)){
            redirect('hotelchain/view/'.$hotel_id, 'refresh');        
        }
    }
}
