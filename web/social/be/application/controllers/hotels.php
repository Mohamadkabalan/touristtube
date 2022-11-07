<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Hotels extends MY_Controller {

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
        $data['username'] = $session_data['username'];
        $data['title'] = 'Manage hotels';
        $data['content'] = 'hotels/list';
        $hotels = new Cms_Hotel_Model();
        $this->load->model('Cms_Countries_Model');
        $data['country'] = $this->Cms_Countries_Model->get_all_countries_name();
        //echo '<pre>';print_r($data['countryes']);
        $config['uri_segment'] = 3;
        $config['per_page'] = 500;
        $config["num_links"] = 14;
        $config['base_url'] = 'hotels/ajax_list';
        $total = $hotels->count();
        $config['total_rows'] = $total;
        $res = $hotels->order_by('id')->get(500);
        $data['hotels'] = $hotels;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
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
        $config['base_url'] = 'hotels/ajax_list';
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
        $this->load->view('hotels/ajax_list', $data);
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
        $data['content'] = 'hotels/hotel_search';
        $hotel_searches = new Cms_Hotel_Search_Model();
        //echo '<pre>';print_r($data['countryes']);
        $config['uri_segment'] = 3;
        $config['per_page'] = 500;
        $config["num_links"] = 14;
        $config['base_url'] = 'hotels/ajax_hotel_search';
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
        $config['base_url'] = 'hotels/ajax_hotel_search';
        $total = $hotel_searches->like('name', $name)->like('keyword', $keyword)->count();
        $config['total_rows'] = $total;
        $hotel_searches->like('name', $name)->like('keyword', $keyword)->order_by('id')->get(500);
//        $hotel_searches->order_by('id')->get(500);
        $data['hotel_searches'] = $hotel_searches;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $this->load->view('hotels/ajax_hotel_search', $data);
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
        $title = $this->cleanTitle($this->input->post('title'));
        $date = new DateTime();
        $todaytime = $date->getTimestamp();
        $filename = $title.'_'.$select.'-'.$todaytime;
        $status = "";
        $msg = "";
        $file_element_name = 'userfile';
        $type= 'hotels';

        if ($status != "error"){

            $this->load->model('cms_hotel_image_model');

            if( $this->config->item('upload_src') == "s3" ){
                try{
                    $s3 = new S3($this->config->item('accessKey'), $this->config->item('secretKey'));


                    $base_name = 'media/hotels/'.$id.'/'.$select;

                    foreach( $_FILES[$file_element_name]['name'] as $k => $file ){

                        $file_name = $_FILES[$file_element_name]['tmp_name'][$k];

                        $file_base_name = $_FILES[$file_element_name]['name'][$k];

                        $ext_info = pathinfo($file_base_name);
                        $ext = $ext_info["extension"];

                        $file_base_name = $filename.$k.".".$ext;

                        if ($s3->putObjectFile($file_name, $this->config->item('bucketName'),$base_name . '/' .$file_base_name , S3::ACL_PUBLIC_READ)) {

                            $file_id = $this->cms_hotel_image_model->insert_file($file_base_name,$select, $id);

                            if($file_id){
                                $status = "success";
                                $msg .= "File successfully uploaded";
                            }else{
                                unlink($data['full_path']);
                                $status = "error";
                                $msg .= "Something went wrong when saving the file, please try again.";
                            }

                        }else{
                            $status = "error";
                            $msg .= "Something went wrong when saving the file, please try again.";
                        }
                    }
                }
                catch(Exception $e){
                    $status = "error";
                    $msg = $e->getMessage();
                }
            }else{
                $config['upload_path'] = '../media/hotels/'.$id.'/'.$select;

                $config['allowed_types'] = 'gif|jpeg|jpg|png';

                $config['encrypt_name'] = TRUE;

                $this->load->library('upload', $config);
                if (!file_exists(($config['upload_path']))){
                    mkdir($config['upload_path'], 0775, true);
                }

                $return_data = $this->upload->do_multi_upload($file_element_name);

                echo $this->upload->display_errors('', '');

                if (!$return_data){

                    $status = 'error';

                    $msg = $this->upload->display_errors('', '');

                    print_r($msg);exit;

                }else{  

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

                        if($file_id){
                            $status = "success";
                            $msg .= "File successfully uploaded";
                        }else{
                            unlink($data['full_path']);
                            $status = "error";
                            $msg .= "Something went wrong when saving the file, please try again.";
                        }
                        $i++;
                    }
                }
                @unlink($_FILES[$file_element_name]);
            }
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
        
        if( $this->config->item('upload_src') == "s3" ){
            $prefix = 'https://' . $this->config->item('bucketName') . ".". S3::$endpoint;

            S3::copyObject($this->config->item('bucketName'),'media/hotels/'.$hotel_id.'/'.$old_location.'/'.$image_name,$this->config->item('bucketName'),'media/hotels/'.$hotel_id.'/'.$select.'/'.$image_name);

            S3::deleteObject($this->config->item('bucketName'),'media/hotels/'.$hotel_id.'/'.$old_location.'/'.$image_name);
        }
        else{
            $old_path = '../media/hotels/'.$hotel_id.'/'.$old_location.'/'.$image_name;
            $new_path = '../media/hotels/'.$hotel_id.'/'.$select.'/'.$image_name;

            if (file_exists($old_path)){
                $thenew_path = '../media/hotels/'.$hotel_id.'/'.$select;
                if (!file_exists(($thenew_path)))
                    mkdir($thenew_path, 0775);
                copy($old_path, $new_path);
                unlink($old_path);
            }
        }

        $this->load->model('cms_hotel_image_model');
        if($this->cms_hotel_image_model->update_location_pic($image_id,$old_location,$select)){
            redirect('hotels/view/'.$hotel_id, 'refresh');        
        }


    }

    public function hotel_search_view($id){
        $data['title'] = '';
        $data['content'] = 'hotels/hotel_search_view';
        $hotel_search = new Cms_Hotel_Search_Model();
        $hotel_search->where('id', $id)->get();
        $data['hotel_search'] = $hotel_search->to_array();
        $hotel_search_details = new Cms_Hotel_Search_Details_Model();
        $hotel_search_details->where('hotel_booking_id', $id)->where('published', '1')->get();
//        $data['hotel_search_details'] = $hotel_search_details->all_to_array();
        $data['hotel_search_details'] = $hotel_search_details;
        $this->load->view('template', $data);
    }

    public function hotel_search_add(){
        $data['title'] = 'Add Hotel search';
        $data['content'] = 'hotels/hotel_search_form';
        $this->load->view('template', $data);
    }

    public function hotel_search_edit($id){
        $hotel_search = new Cms_Hotel_Search_Model();
        $hotel_search->where('id', $id)->get();
        $data['title'] = 'Edit Hotel search';
        $data['content'] = 'hotels/hotel_search_form';
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
        redirect('hotels/hotel_search_view/'.$hotel_search->id, 'refresh');
    }

    public function search_detail_add($id){
        $hotel_search = new Cms_Hotel_Search_Model();
        $hotel_search->where('id', $id)->get();
        $data['title'] = $hotel_search->keyword;
        $data['content'] = 'hotels/hotel_search_detail_form';
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
        $data['content'] = 'hotels/hotel_search_detail_form';
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
                case SOCIAL_ENTITY_REGION:
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
            }
            if($valid){
                $hotel_search_detail->save();
            }
        }
        redirect('hotels/hotel_search_view/'.$hotel_booking_id, 'refresh');
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
        $this->load->view('hotels/images', array('hotel_images' => $hotel_images));
    }

    public function ajax_delete_image(){
        $image_id = intval($this->input->post('image_id'));
        $hotel_image = new Cms_Hotel_Image_Model();
        $hotel_image->where('id', $image_id)->get();
        $hotel_id = $hotel_image->hotel_id;

        $location_condition = "'appartment','bathroom','budgetRoom','businessRoom','comfortRoom','doubleRoomComfort','doubleRoomEconomy','doubleRoomStandard','economyRoom','familyRoom','fourBedRoom','groupRoom','guestRoom','juniorSuite','room','roomSketch','roomWithBalcony','roomWithGardenView','roomWithLakeView','roomWithMountainsView','roomWithPoolView','roomWithRiverView','roomWithSeaView','roomWithTerrace','singleRoomComfort','singleRoomEconomy','singleRoomStandard','standardRoom','suite','superiorRoom','threeBedRoom'";
        $hotel_image->hotel_id = 0;
        $hotel_image->save();
        $hotel_images_obj = new Cms_Hotel_Image_Model();
        $hotel_images_obj->where('hotel_id', $hotel_id)->where('location  IN (' . $location_condition . ')')->get();
        $hotel_images = $hotel_images_obj->all_to_array();

        $hotel_images_obj2 = new Cms_Hotel_Image_Model();
        $hotel_images_obj2->where('hotel_id', $hotel_id)->where('location  NOT IN (' . $location_condition . ')')->get();
        $hotel_images_conditions2 = array_merge($hotel_images, $hotel_images_obj2->all_to_array());

        $locations = ['appartment','approachMap','ballRoom','banquet','bar','bathroom','beach','beautyCenter','bowlingAlley','breakfastRoom','brineBath','budgetRoom','buffet','businessRoom','cafeBistro','certificate','comfortRoom','conferenceFoyer','conferenceOffice','conferenceRoom','conferences','congressHall','cosmetic','doubleRoomComfort','doubleRoomEconomy','doubleRoomStandard','economyRoom','events','exhibitionArea','exteriorView','familyRoom','fitness','fourBedRoom','garden','golfCourse','groupRoom','guestRoom','hall','hamam','hotelIndoorArea','hotelKitchen','hotelOutdoorArea','info','inside','juniorSuite','kitchen','kitchenInRoom','manege','massageRoom','meetingRoom','none','outlook','quietArea','rasulBath','readingRoom','reception','restaurant','restaurant1','restaurant2','restaurantBreakfastRoom','room','roomSketch','roomWithBalcony','roomWithGardenView','roomWithLakeView','roomWithMountainsView','roomWithPoolView','RoomWithOceanView','roomWithRiverView','roomWithSeaView','roomWithTerrace','sanarium','sauna','seatingPlan','seminarRoom','shop','singleRoomComfort','singleRoomEconomy','singleRoomStandard','skittlingAlley','sportsFacilities','standardRoom','steamBath','suite','superiorRoom ','surrounding','swimmingPool','televisionRoom','tennisCourt','terrace','threeBedRoom','trainingRoom','wellness','wellnessFitness','whirlpool'];
        $location_condition2 = ['appartment','bathroom','budgetRoom','businessRoom','comfortRoom','doubleRoomComfort','doubleRoomEconomy','doubleRoomStandard','economyRoom','familyRoom','fourBedRoom','groupRoom','guestRoom','juniorSuite','room','roomSketch','roomWithBalcony','roomWithGardenView','roomWithLakeView','roomWithMountainsView','roomWithPoolView','RoomWithOceanView','roomWithRiverView','roomWithSeaView','roomWithTerrace','singleRoomComfort','singleRoomEconomy','singleRoomStandard','standardRoom','suite','superiorRoom','threeBedRoom'];
        $this->load->view('hotels/images', array('hotel_images' => $hotel_images_conditions2, 'id' =>$hotel_id, 'locations' => $locations, 'location_condition2' => $location_condition2 ));
//        echo json_encode (array('success' => TRUE));
        //$success = $hotel_image->delete();
        //echo json_encode (array('success' => $success));
    }

    public function ajax_delete_image2($id){
        $image_id = intval($id);
        $hotel_image = new Cms_Hotel_Image_Model();
        $hotel_image->where('id', $image_id)->get();
        $hotel_id = $hotel_image->hotel_id;
        $hotel_image->delete();
        redirect('hotels/view/'.$hotel_id, 'refresh');     
    }

    public function view($id) {
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];

        $data['title'] = 'View hotel';
        $data['content'] = 'hotels/view';

        $location_condition = "'appartment','bathroom','businessRoom','budgetRoom','comfortRoom','doubleRoomComfort','doubleRoomEconomy','doubleRoomStandard','economyRoom','familyRoom','fourBedRoom','groupRoom','guestRoom','juniorSuite','room','roomSketch','roomWithBalcony','roomWithGardenView','roomWithLakeView','roomWithMountainsView','roomWithPoolView','RoomWithOceanView','roomWithRiverView','roomWithSeaView','roomWithTerrace','singleRoomComfort','singleRoomEconomy','singleRoomStandard','standardRoom','suite','superiorRoom','threeBedRoom'";
        $location_condition2 = ['appartment','bathroom','businessRoom','budgetRoom','comfortRoom','doubleRoomComfort','doubleRoomEconomy','doubleRoomStandard','economyRoom','familyRoom','fourBedRoom','groupRoom','guestRoom','juniorSuite','room','roomSketch','roomWithBalcony','roomWithGardenView','roomWithLakeView','roomWithMountainsView','roomWithPoolView','RoomWithOceanView','roomWithRiverView','roomWithSeaView','roomWithTerrace','singleRoomComfort','singleRoomEconomy','singleRoomStandard','standardRoom','suite','superiorRoom','threeBedRoom'];
        $hotel = new Cms_Hotel_Model();
        $hotel->where('id', $id)->get();
        $hotel->facility->where('published = 1')->order_by('name')->get();
        $hotel_array = $hotel->to_array();
        $data['hotel'] = $hotel_array;

        $location = array();

        $hotel->image->where('location  IN (' . $location_condition . ')')->get();
        $hotel_images_conditions = $hotel->image->all_to_array();

        $hotel->image->where('location  NOT IN (' . $location_condition . ')')->get();
        $hotel_images_conditions2 = array_merge($hotel_images_conditions, $hotel->image->all_to_array());
//        print_r($hotel_images_conditions2);exit;
        $duplicate_image = array();
//        foreach($hotel_images_conditions2 as $hotel){
//            if($hotel['filename'])
//        }

        $data['hotel_images'] = $hotel_images_conditions2;
        $location = ['appartment','approachMap','ballRoom','banquet','bar','bathroom','beach','beautyCenter','bowlingAlley','breakfastRoom','brineBath','budgetRoom','buffet','businessRoom','cafeBistro','certificate','comfortRoom','conferenceFoyer','conferenceOffice','conferenceRoom','conferences','congressHall','cosmetic','doubleRoomComfort','doubleRoomEconomy','doubleRoomStandard','economyRoom','events','exhibitionArea','exteriorView','familyRoom','fitness','fourBedRoom','garden','golfCourse','groupRoom','guestRoom','hall','hamam','hotelIndoorArea','hotelKitchen','hotelOutdoorArea','info','inside','juniorSuite','kitchen','kitchenInRoom','manege','massageRoom','meetingRoom','none','outlook','quietArea','rasulBath','readingRoom','reception','restaurant','restaurant1','restaurant2','restaurantBreakfastRoom','room','roomSketch','roomWithBalcony','roomWithGardenView','roomWithLakeView','roomWithMountainsView','roomWithPoolView','RoomWithOceanView','roomWithRiverView','roomWithSeaView','roomWithTerrace','sanarium','sauna','seatingPlan','seminarRoom','shop','singleRoomComfort','singleRoomEconomy','singleRoomStandard','skittlingAlley','sportsFacilities','standardRoom','steamBath','suite','superiorRoom','surrounding','swimmingPool','televisionRoom','tennisCourt','terrace','threeBedRoom','trainingRoom','wellness','wellnessFitness','whirlpool'];

        $facility_ids = array();
        $facility_titles = '';
        foreach ($hotel->facility as $i) {
            $facility_ids[] = $i->id;
            $facility_titles .= $i->name . ', ';
        }
        $facility_titles = rtrim($facility_titles, ", ");
        $data['hotel_facility_titles'] = $facility_titles;
        $data['locations'] = $location;
        $data['location_condition2'] = $location_condition2;
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
        $data['content'] = 'hotels/view';
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
        $data['username'] = $session_data['username'];
        $data['title'] = 'Edit hotel';
        if ($session_data['role'] == 'hotel_desc_writer') {
            $data['content'] = 'hotels/hdw_form';
        } else {
            $data['content'] = 'hotels/form';
        }
        $hotel = new Cms_Hotel_Model();
        $hotel->where('id', $id)->get();
        $data['hotel'] = $hotel->to_array();
        $hoteldescriptions = new Ml_Hotel_Model();
        $hotelDescriptionAllLanguages = $hoteldescriptions->where('hotel_id', $id)->get()->all_to_array();
        $description_array = array();
        foreach($hotelDescriptionAllLanguages as $s){
            $description = $s['description'];
            $name = $s['name'];
            $lang = $s['lang_code'];
            $description_array['description'.$lang] = $description;
            $description_array['name'.$lang] = $name;
        }
        $data['hotel'] = array_merge($data['hotel'],$description_array);
        $data['jsIncludes'] = array('edit_page.js');
        $this->load->view('template', $data);
    }

    public function add() {
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title'] = 'Add hotel';
        $data['content'] = 'hotels/form';
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
            $hotel->zip_code = $this->input->post('zipcode');
        }
        $hotel->save();
        $this->load->model('ml_hotel_model');
        $hoteldescriptions = new Ml_Hotel_Model();
        if ($id <> '') {
            $hoteldescriptions->where('hotel_id', $id)->get();
        }
        $entity_id = $id;
        if( $this->input->post('descriptionFr') || $this->input->post('nameFr') ){
            if($this->input->post('descriptionFr')){
                $descriptionFr=$this->input->post('descriptionFr');
            }else{
                $descriptionFr='';
            }
            if($this->input->post('nameFr')){
                $nameFr=$this->input->post('nameFr');
            }else{
                $nameFr='';
            }  
            $hoteldescriptions->update_ml_hotel($entity_id,'fr',$descriptionFr,$nameFr);
        }
        if( $this->input->post('descriptionIn') || $this->input->post('nameIn') ){
            if($this->input->post('descriptionIn')){
                $descriptionIn=$this->input->post('descriptionIn');
            }else{
                $descriptionIn='';
            }
            if($this->input->post('nameIn')){
                $nameIn=$this->input->post('nameIn');
            }else{
                $nameIn='';
            }  
            $hoteldescriptions->update_ml_hotel($entity_id,'in',$descriptionIn,$nameIn);
        }
        if( $this->input->post('descriptionCn') || $this->input->post('nameCn') ){
            if($this->input->post('descriptionCn')){
                $descriptionCn=$this->input->post('descriptionCn');
            }else{
                $descriptionCn='';
            }
            if($this->input->post('nameCn')){
                $nameCn=$this->input->post('nameCn');
            }else{
                $nameCn='';
            }  
            $hoteldescriptions->update_ml_hotel($entity_id,'cn',$descriptionCn,$nameCn);
        }

        $this->load->model('activitylog_model');
        $activity_code = $id == '' ? HOTEL_INSERT : HOTEL_UPDATE;
        $this->activitylog_model->insert_log($userdata['uid'], $activity_code, $hotel->id);
        redirect('hotels/view/' . $hotel->id, 'refresh');
    }

    public function amenities() {
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title'] = 'Manage amenities';
        $amenities = new Cms_Amenity_Model();
        $data['amenities'] = $amenities->get()->all_to_array();
        $data['content'] = 'hotels/amenities';
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
        redirect('hotels/amenities', 'refresh');
    }

    public function facilities() {
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title'] = 'Manage facilities';
        $facilities = new Cms_Facility_Model();
        $data['facilities'] = $facilities->where('published', 1)->get()->all_to_array();
        $facility_types = new Cms_Facility_Type_Model();
        $data['facility_types'] = $facility_types->get()->all_to_array();
        $data['content'] = 'hotels/facilities';
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
        $data['content'] = 'hotels/hotel_facilities';
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
        $data['content'] = 'hotels/hotel_facilities';
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
        $this->load->view('hotels/ajax_hotel_facilities', $data);
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
        $this->load->view('hotels/ajax_hotel_facilities', $data);
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
        redirect('hotels/facilities', 'refresh');
    }

    function facility_new() {
        $name = $this->input->post('name');
        $type = $this->input->post('type');
        $facility = new Cms_Facility_Model();
        $facility->name = $name;
        $facility->type_id = $type;
        $facility->save();
        redirect('hotels/facilities', 'refresh');
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
        redirect('hotels/index/' . $city, 'refresh');
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
        $data['content'] = 'hotels/hotel_amenities';
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
        $data['content']= 'hotels/view';    
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
            redirect('hotels/view/'.$hotel_id, 'refresh');        
        }
    }
    function cleanTitle($title) {
     $ret = html_entity_decode($title);
     $ret = $this->remove_accents($ret);
     $ret = str_replace(' ', '-', $ret);
     $ret = str_replace(' ', '-', $ret);
     $ret = str_replace('.', '', $ret);
     $ret = preg_replace('/[^a-z0-9A-Z\-]/', '', $ret);
     $ret = str_replace('--', '-', $ret);

     return $ret;
 }
/**
 * Converts all accent characters to ASCII characters.
 *
 * If there are no accent characters, then the string given is just returned.
 *
 * @param string $string Text that might have accent characters
 * @return string Filtered string with replaced "nice" characters.
 */
function remove_accents($string) {
	if (!preg_match('/[\x80-\xff]/', $string))
		return $string;

	if ($this->seems_utf8($string)) {
		$chars = array(
			// Decompositions for Latin-1 Supplement
			chr(195) . chr(128) => 'A', chr(195) . chr(129) => 'A',
			chr(195) . chr(130) => 'A', chr(195) . chr(131) => 'A',
			chr(195) . chr(132) => 'A', chr(195) . chr(133) => 'A',
			chr(195) . chr(135) => 'C', chr(195) . chr(136) => 'E',
			chr(195) . chr(137) => 'E', chr(195) . chr(138) => 'E',
			chr(195) . chr(139) => 'E', chr(195) . chr(140) => 'I',
			chr(195) . chr(141) => 'I', chr(195) . chr(142) => 'I',
			chr(195) . chr(143) => 'I', chr(195) . chr(145) => 'N',
			chr(195) . chr(146) => 'O', chr(195) . chr(147) => 'O',
			chr(195) . chr(148) => 'O', chr(195) . chr(149) => 'O',
			chr(195) . chr(150) => 'O', chr(195) . chr(153) => 'U',
			chr(195) . chr(154) => 'U', chr(195) . chr(155) => 'U',
			chr(195) . chr(156) => 'U', chr(195) . chr(157) => 'Y',
			chr(195) . chr(159) => 's', chr(195) . chr(160) => 'a',
			chr(195) . chr(161) => 'a', chr(195) . chr(162) => 'a',
			chr(195) . chr(163) => 'a', chr(195) . chr(164) => 'a',
			chr(195) . chr(165) => 'a', chr(195) . chr(167) => 'c',
			chr(195) . chr(168) => 'e', chr(195) . chr(169) => 'e',
			chr(195) . chr(170) => 'e', chr(195) . chr(171) => 'e',
			chr(195) . chr(172) => 'i', chr(195) . chr(173) => 'i',
			chr(195) . chr(174) => 'i', chr(195) . chr(175) => 'i',
			chr(195) . chr(177) => 'n', chr(195) . chr(178) => 'o',
			chr(195) . chr(179) => 'o', chr(195) . chr(180) => 'o',
			chr(195) . chr(181) => 'o', chr(195) . chr(182) => 'o',
			chr(195) . chr(182) => 'o', chr(195) . chr(185) => 'u',
			chr(195) . chr(186) => 'u', chr(195) . chr(187) => 'u',
			chr(195) . chr(188) => 'u', chr(195) . chr(189) => 'y',
			chr(195) . chr(191) => 'y',
			// Decompositions for Latin Extended-A
			chr(196) . chr(128) => 'A', chr(196) . chr(129) => 'a',
			chr(196) . chr(130) => 'A', chr(196) . chr(131) => 'a',
			chr(196) . chr(132) => 'A', chr(196) . chr(133) => 'a',
			chr(196) . chr(134) => 'C', chr(196) . chr(135) => 'c',
			chr(196) . chr(136) => 'C', chr(196) . chr(137) => 'c',
			chr(196) . chr(138) => 'C', chr(196) . chr(139) => 'c',
			chr(196) . chr(140) => 'C', chr(196) . chr(141) => 'c',
			chr(196) . chr(142) => 'D', chr(196) . chr(143) => 'd',
			chr(196) . chr(144) => 'D', chr(196) . chr(145) => 'd',
			chr(196) . chr(146) => 'E', chr(196) . chr(147) => 'e',
			chr(196) . chr(148) => 'E', chr(196) . chr(149) => 'e',
			chr(196) . chr(150) => 'E', chr(196) . chr(151) => 'e',
			chr(196) . chr(152) => 'E', chr(196) . chr(153) => 'e',
			chr(196) . chr(154) => 'E', chr(196) . chr(155) => 'e',
			chr(196) . chr(156) => 'G', chr(196) . chr(157) => 'g',
			chr(196) . chr(158) => 'G', chr(196) . chr(159) => 'g',
			chr(196) . chr(160) => 'G', chr(196) . chr(161) => 'g',
			chr(196) . chr(162) => 'G', chr(196) . chr(163) => 'g',
			chr(196) . chr(164) => 'H', chr(196) . chr(165) => 'h',
			chr(196) . chr(166) => 'H', chr(196) . chr(167) => 'h',
			chr(196) . chr(168) => 'I', chr(196) . chr(169) => 'i',
			chr(196) . chr(170) => 'I', chr(196) . chr(171) => 'i',
			chr(196) . chr(172) => 'I', chr(196) . chr(173) => 'i',
			chr(196) . chr(174) => 'I', chr(196) . chr(175) => 'i',
			chr(196) . chr(176) => 'I', chr(196) . chr(177) => 'i',
			chr(196) . chr(178) => 'IJ', chr(196) . chr(179) => 'ij',
			chr(196) . chr(180) => 'J', chr(196) . chr(181) => 'j',
			chr(196) . chr(182) => 'K', chr(196) . chr(183) => 'k',
			chr(196) . chr(184) => 'k', chr(196) . chr(185) => 'L',
			chr(196) . chr(186) => 'l', chr(196) . chr(187) => 'L',
			chr(196) . chr(188) => 'l', chr(196) . chr(189) => 'L',
			chr(196) . chr(190) => 'l', chr(196) . chr(191) => 'L',
			chr(197) . chr(128) => 'l', chr(197) . chr(129) => 'L',
			chr(197) . chr(130) => 'l', chr(197) . chr(131) => 'N',
			chr(197) . chr(132) => 'n', chr(197) . chr(133) => 'N',
			chr(197) . chr(134) => 'n', chr(197) . chr(135) => 'N',
			chr(197) . chr(136) => 'n', chr(197) . chr(137) => 'N',
			chr(197) . chr(138) => 'n', chr(197) . chr(139) => 'N',
			chr(197) . chr(140) => 'O', chr(197) . chr(141) => 'o',
			chr(197) . chr(142) => 'O', chr(197) . chr(143) => 'o',
			chr(197) . chr(144) => 'O', chr(197) . chr(145) => 'o',
			chr(197) . chr(146) => 'OE', chr(197) . chr(147) => 'oe',
			chr(197) . chr(148) => 'R', chr(197) . chr(149) => 'r',
			chr(197) . chr(150) => 'R', chr(197) . chr(151) => 'r',
			chr(197) . chr(152) => 'R', chr(197) . chr(153) => 'r',
			chr(197) . chr(154) => 'S', chr(197) . chr(155) => 's',
			chr(197) . chr(156) => 'S', chr(197) . chr(157) => 's',
			chr(197) . chr(158) => 'S', chr(197) . chr(159) => 's',
			chr(197) . chr(160) => 'S', chr(197) . chr(161) => 's',
			chr(197) . chr(162) => 'T', chr(197) . chr(163) => 't',
			chr(197) . chr(164) => 'T', chr(197) . chr(165) => 't',
			chr(197) . chr(166) => 'T', chr(197) . chr(167) => 't',
			chr(197) . chr(168) => 'U', chr(197) . chr(169) => 'u',
			chr(197) . chr(170) => 'U', chr(197) . chr(171) => 'u',
			chr(197) . chr(172) => 'U', chr(197) . chr(173) => 'u',
			chr(197) . chr(174) => 'U', chr(197) . chr(175) => 'u',
			chr(197) . chr(176) => 'U', chr(197) . chr(177) => 'u',
			chr(197) . chr(178) => 'U', chr(197) . chr(179) => 'u',
			chr(197) . chr(180) => 'W', chr(197) . chr(181) => 'w',
			chr(197) . chr(182) => 'Y', chr(197) . chr(183) => 'y',
			chr(197) . chr(184) => 'Y', chr(197) . chr(185) => 'Z',
			chr(197) . chr(186) => 'z', chr(197) . chr(187) => 'Z',
			chr(197) . chr(188) => 'z', chr(197) . chr(189) => 'Z',
			chr(197) . chr(190) => 'z', chr(197) . chr(191) => 's',
			// Euro Sign
			chr(226) . chr(130) . chr(172) => 'E',
			// GBP (Pound) Sign
			chr(194) . chr(163) => '');

$string = strtr($string, $chars);
} else {
		// Assume ISO-8859-1 if not UTF-8
  $chars['in'] = chr(128) . chr(131) . chr(138) . chr(142) . chr(154) . chr(158)
  . chr(159) . chr(162) . chr(165) . chr(181) . chr(192) . chr(193) . chr(194)
  . chr(195) . chr(196) . chr(197) . chr(199) . chr(200) . chr(201) . chr(202)
  . chr(203) . chr(204) . chr(205) . chr(206) . chr(207) . chr(209) . chr(210)
  . chr(211) . chr(212) . chr(213) . chr(214) . chr(216) . chr(217) . chr(218)
  . chr(219) . chr(220) . chr(221) . chr(224) . chr(225) . chr(226) . chr(227)
  . chr(228) . chr(229) . chr(231) . chr(232) . chr(233) . chr(234) . chr(235)
  . chr(236) . chr(237) . chr(238) . chr(239) . chr(241) . chr(242) . chr(243)
  . chr(244) . chr(245) . chr(246) . chr(248) . chr(249) . chr(250) . chr(251)
  . chr(252) . chr(253) . chr(255);

  $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

  $string = strtr($string, $chars['in'], $chars['out']);
  $double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
  $double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
  $string = str_replace($double_chars['in'], $double_chars['out'], $string);
}
return $string;
}
function seems_utf8($str) {
	$length = strlen($str);
	for ($i = 0; $i < $length; $i++) {
     $c = ord($str[$i]);
     if ($c < 0x80)
		$n = 0;# 0bbbbbbb
 elseif (($c & 0xE0) == 0xC0)
		$n = 1;# 110bbbbb
 elseif (($c & 0xF0) == 0xE0)
		$n = 2;# 1110bbbb
 elseif (($c & 0xF8) == 0xF0)
		$n = 3;# 11110bbb
 elseif (($c & 0xFC) == 0xF8)
		$n = 4;# 111110bb
 elseif (($c & 0xFE) == 0xFC)
		$n = 5;# 1111110b
 else
		return false;# Does not match any model
	    for ($j = 0; $j < $n; $j++) { # n bytes matching 10bbbbbb follow ?
          if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
              return false;
      }
  }
  return true;
}
}
