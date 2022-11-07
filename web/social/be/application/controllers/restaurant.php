<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

error_reporting(1);
    
class Restaurant extends MY_Controller {
    
   /* function __construct() {
        parent::__construct();
        $this->load->model('Ml_discover_cuisine_model');
    }*/
	
	//code for Hotel selections module, added by sushma mishra on 07 dec 2015 starts from here
    /**
	Method will call to show the listing of Hotel Selections
	*/
    public function restaurantSelection(){
		$this->load->model('Restaurants_Selections_Model');
		$this->load->model('City_Model');
        $this->load->library('pagination');
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Restaurants Selections';
        $data['content']= 'restaurant/restaurant_selection';
        $config['uri_segment'] = 3;
        $config['per_page'] = 20;
        $config["num_links"] = 14;
        $config['base_url'] = 'restaurant/ajax_restaurantSelection';
		$a = new Restaurants_Selections_Model();
        $total = $a->count();
		$res = $a->order_by('id')->get();
		$arrRselection = $a->all_to_array();
		$c = new City_Model();
		$rarr = array();
		$i=0;
		foreach($arrRselection as $rsel){
		$cityInfo = $c->getbyid($rsel['city_id']);
		$rarr[$i]['id'] = $rsel['id'];
		$rarr[$i]['city_id'] = $rsel['city_id'];
		$rarr[$i]['city_name'] = $cityInfo['name'];
		$rarr[$i]['img'] = $rsel['img'];
		$rarr[$i]['count'] = $rsel['count'];
		$rarr[$i]['selection_type'] = $rsel['selection_type'];
		$rarr[$i]['published'] = $rsel['published'];		
		$i++;
		}
        $config['total_rows'] = $total;        
        $data['aitems']= $rarr;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
		$data['jsIncludes'] = array('edit_page.js');
        $this->load->view('template', $data);
	}
	public function ajax_restaurantSelection($start = 0){
		 $this->load->library('pagination');
        $this->load->model('Restaurants_Selections_Model');
        $config['uri_segment'] = 3;
        $config['per_page'] = 500;
        $config["num_links"] = 14;
        $config['base_url'] = 'restaurant/ajax_restaurantSelection';
        $a = new Restaurants_Selections_Model();
        $total = $a->count();
        $config['total_rows'] = $total;
        $limit = $total;
		$res = $a->order_by('id')->get(500, $start);
		$arrRselection = $a->all_to_array();
		$c = new City_Model();
		$rarr = array();
		$i=0;
		foreach($arrRselection as $rsel){
		$cityInfo = $c->getbyid($rsel['city_id']);
		$rarr[$i]['id'] = $rsel['id'];
		$rarr[$i]['city_id'] = $rsel['city_id'];
		$rarr[$i]['city_name'] = $cityInfo['name'];
		$rarr[$i]['img'] = $rsel['img'];
		$rarr[$i]['count'] = $rsel['count'];
		$rarr[$i]['selection_type'] = $rsel['selection_type'];
		$rarr[$i]['published'] = $rsel['published'];		
		$i++;
		}		
        $data['aitems']= $rarr;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $this->load->view('restaurant/ajax_restaurantSelection', $data);
    }
	/**
	Method will call to show the form to add Restaurant Selections
	*/
	public function addrselection(){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Add Restaurant Selections';
        $data['content']= 'restaurant/rselection_form';
        $data['jsIncludes'] = array('edit_page.js');
        $this->load->view('template', $data);
    }
	/**
	Method will call to show the form to edit Restaurant Selections
	*/
	public function editrselection($id){
        $session_data = $this->session->userdata('logged_in');
		$this->load->model('City_Model');		
		$c = new City_Model();
        $data['username'] = $session_data['username'];
        $data['title']= 'Edit Restaurant Selections';
        $data['content']= 'restaurant/rselection_form';
        $h = new Restaurants_Selections_Model();
        $h->where('id', $id)->get();
		$hsel = $h->to_array();
		$cityInfo = $c->getbyid($hsel['city_id']);
		$arrHselection['id'] = $hsel['id'];
		$arrHselection['city_id'] = $hsel['city_id'];
		$arrHselection['city_name'] = $cityInfo['name'];
		$arrHselection['img'] = $hsel['img'];
		$arrHselection['count'] = $hsel['count'];
		$arrHselection['selection_type'] = $hsel['selection_type'];
		$arrHselection['published'] = $hsel['published'];
        $data['item']= $arrHselection;
		$data['jsIncludes'] = array('edit_page.js');
        $this->load->view('template', $data);
    }
	/**
	Method will call to add/edit Restaurant Selections data
	*/
	public function rselection_submit(){	
		$userdata = $this->session->userdata('logged_in');
		$id = $this->input->post('id');
		$cityId = $this->input->post('cityId');		
		$hm = new Global_Restaurant_Model();
		$count = $hm->where('city_id', $cityId)->count();
		$this->load->model('City_Model');		
		$c = new City_Model();
		$cityInfo = $c->getbyid($cityId);
		$cname =$cityInfo['name'];
		$h = new Restaurants_Selections_Model();
		if($id <> '' ){
			$h->where('id', $id)->get();
		}		
		$h->count = $count;		
		$h->selection_type = $this->input->post('seltype');
		$h->published = $this->input->post('published');		
		$h->city_id = $cityId;		
		$clean_title = $cname;		
		$config['upload_path'] = '/media/images/selection';
		$config['allowed_types'] = 'gif|jpeg|jpg|png';
		$config['max_size'] = 1024 * 10000;
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload', $config);
		$upload_success = $this->upload->do_upload('image');
		if($upload_success){
			if($h->image != '' || $id == ''){
				$old_image = '/media/images/selection/'.$h->image;
				unlink($old_image);
			}
			$upload_data = $this->upload->data();
			$original_filename = $upload_data['file_name'];
			$extension = explode(".",$upload_data['file_name']);
			
			$new_name = $clean_title.'_'.time().'.'.end($extension);
			$basic_path = '/media/images/selection/';
			rename($basic_path.$original_filename, $basic_path.$new_name);
			$path = $basic_path.$new_name;
			$h->img = $new_name;
		}
		$h->save();		
		redirect('restaurant/restaurantSelection/', 'refresh');
	}
	/**
	Method will call to delete Restaurant Selections
	*/
	public function ajaxrsel_delete($id){
		$userdata = $this->session->userdata('logged_in');
		$d = new Restaurants_Selections_Model();
		$success = FALSE;
		if($id <>'') {
			$d->where('id', $id )->get();			
			$success = $d->delete();			
		}
		echo json_encode (array('success' => $success));
	}	
	//code for Hotel selections module, added by sushma mishra on 07 dec 2015 ends here
	
    public function index($cc = 'all', $start = 0){
       $this->load->library('pagination');
       $session_data = $this->session->userdata('logged_in');
       $data['username'] = $session_data['username'];
       $data['title']= 'Manage restaurants';
       $data['content']= 'restaurant/list';
       $r = new Global_Restaurant_Model();
       $config['uri_segment'] = 3;
       $config['per_page'] = 500;
       $config["num_links"] = 14;
       $config['base_url'] = 'restaurant/ajax_list'; 
       $total = $r->like('country', 'fr')->count();
       $config['total_rows'] = $total;  
       $r->like('country', 'fr')->order_by('id')->get(500);
       $data['restaurants']= $r;

       $this->pagination->initialize($config);
       $data['links'] = $this->pagination->create_links();
       $data['cc']= 'fr';
       $data['jsIncludes'] = array('restaurant.js');
       $this->load->view('template', $data);
     }
    function ajax_list($start = 0){
       $this->load->library('pagination');
       $cityName = $this->input->post('ci');
       $name = $this->input->post('re');
       $countryCode = $this->input->post('cc');
       $r = new Global_Restaurant_Model();
       $config['uri_segment'] = 3;
       $config['per_page'] = 500;
       $config["num_links"] = 14;
       $config['base_url'] = 'restaurant/ajax_list';
       $total = $r->like('name', $name)->like('city', $cityName)->like('country', $countryCode)->count();
       $config['total_rows'] = $total;
       $limit = $total;
   //    if($limit > 20000)
   //        $limit = 20000;
       $r->like('name', $name)->like('city', $cityName)->like('country', $countryCode)->order_by('id')->get(500, $start);
       $data['restaurants']= $r;
       $this->pagination->initialize($config);
       $data['links'] = $this->pagination->create_links();
       $this->load->view('restaurant/ajax_list', $data);
    }
    function ajax_upload(){
       $userdata = $this->session->userdata('logged_in');
       $status = "";
       $msg = "";
       $file_element_name = 'userfile';
       $restaurant_id = $this->input->post('id');
       $this->load->helper('discover_title_helper');
       $type= 'restaurant';
       if ($status != "error")
       {
           $config['upload_path'] = '../media/discover';
           $config['allowed_types'] = 'gif|jpeg|jpg|png';
           $config['max_size'] = 1024 * 8;
           $config['encrypt_name'] = TRUE;

           $this->load->library('upload', $config);
           $this->load->model('restaurant_image_model');
           $this->load->model('activitylog_model');
           $return_data = $this->upload->do_multi_upload($file_element_name);
     
           if (!$return_data)
           {
               $status = 'error';
               $msg = $this->upload->display_errors('', '');
           }
           else
           {
                foreach ( $return_data as $data){
                    $original_filename = $data['file_name'];
                    $extension = explode(".",$data['file_name']);
                    $new_name = time();
                    rename('../media/discover/'.$original_filename, '../media/discover/'.$new_name.'.'.end($extension)); 
                    $thumb_conf['image_library'] = 'GD2';
                    $thumb_conf['maintain_ratio'] = TRUE;
                    $thumb_conf['quality'] = '100%';
                    /* change file name */
                    $new_full_name = get_discover_restaurants_title($restaurant_id).'_'.$type.'_'.$new_name. '.' .end($extension);
                    /* Thumb Size Image  */
                    $thumb_conf['width'] = 175;
                    $thumb_conf['height'] = 109;
                    $thumb_conf['source_image'] = "../media/discover/" . $new_name.'.'.end($extension);
                    $thumb_conf['new_image'] = "../media/discover/thumb/".$new_full_name;
                    $this->image_lib->initialize($thumb_conf);
                    if(!$this->image_lib->resize()) $msg =  $this->image_lib->display_errors();
                    /* Normal Size Image  */
                    $thumb_conf['width'] = 585;
                    $thumb_conf['height'] = 256;
                    $thumb_conf['new_image'] = "../media/discover/".$new_full_name;
                    $this->image_lib->initialize($thumb_conf);
                    if(!$this->image_lib->resize()){ 
                        $msg .= ', ' . $this->image_lib->display_errors();   
                    }
                    /* Large Size Image */
                    $thumb_conf['width'] = 994;
                    $thumb_conf['height'] = 530;
                    $thumb_conf['new_image'] = "../media/discover/large/".$new_full_name;
                    $this->image_lib->initialize($thumb_conf);
                    if(!$this->image_lib->resize()){ 
                        $msg .= ', ' . $this->image_lib->display_errors();   
                    }
                    $file_id = $this->restaurant_image_model->insert_file($new_full_name, $this->input->post('id'));
                    if($file_id)
                    {
                        $this->activitylog_model->insert_log($userdata['uid'], RESTAURANT_IMAGE_INSERT, $file_id);
                        $status = "success";
                        $msg .= "File successfully uploaded";
                    }
                    else
                    {
                        unlink($data['full_path']);
                        $status = "error";
                        $msg = "Something went wrong when saving the file, please try again.";
                    }
                    }
                }
                @unlink($_FILES[$file_element_name]);
       }
       echo json_encode(array('status' => $status));
   }

    public function files($id){
        $this->load->model('restaurant_image_model');
        $files = $this->restaurant_image_model->get_files($id);
        $files_array = array();
        foreach($files as $file){
            $files_array[] = array('id' => $file->id, 'filename' => $file->filename);
        }
        $this->load->view('restaurant/files', array('files' => $files_array));
    }

    public function view($id){
       $this->load->model('restaurant_image_model');
       $session_data = $this->session->userdata('logged_in');
       $data['username'] = $session_data['username'];
       $data['title']= 'View restaurant';
       $data['content']= 'restaurant/view';
       $r = new Global_Restaurant_Model();
       $r->where('id', $id)->get();
       $r->review->get();
       $r->cuisine->get();
       $data['restaurant']= $r->to_array();
       $data['reviews'] = $r->review->all_to_array();

       $cuisine_ids = array();
        $cuisine_titles = '';
        foreach($r->cuisine as $i){
            $cuisine_ids[] = $i->id;
            $cuisine_titles .= $i->title.', ';
        }
        $cuisine_titles = rtrim($cuisine_titles, ", ");
        $data['restaurant_cuisine_titles'] = $cuisine_titles;
        $c = new Cuisine_Model();
        $c->order_by('title')->get();
        $result = array();
        foreach($c as $i){
            $selected = FALSE;
            if(in_array($i->id, $cuisine_ids))
                $selected = TRUE;
            $result[] = array('id' => $i->id, 'text' => $i->title, 'selected' => $selected);
        }
        $data['cuisines_all'] = $result;

        $this->load->model('city_search_model');
        $city_res = $this->city_search_model->getbyid($r->city_id);
        $city = '';
        if(count($city_res)){
            $city = $city_res['text'];
        }
        $data['cityName'] = $city;

       $files = $this->restaurant_image_model->get_files($id);
       $files_array = array();
       foreach($files as $file){
           $files_array[] = array('id' => $file->id, 'filename' => $file->filename,'default_pic' => $file->default_pic);
       }
       $data['files'] = $files_array;
       $data['jsIncludes'] = array('restaurant.js');
       $data['cssIncludes'] = array('hotel.css');
       if(isset($r->map_image) && $r->map_image <> '')
           $data['map_image'] = 'uploads/'.$r->map_image;
       $this->load->view('template', $data);
     }
 
    public function update_map_image(){
        $userdata = $this->session->userdata('logged_in');
        $this->load->helper('map_image');
        $id = $this->input->post('id');
        $h = new Global_Restaurant_Model();
        $h->where('id', $id)->get();
        $latitude = $h->latitude;
        $longitude = $h->longitude;
        $oldfile = './uploads/' . $h->map_image;
        if(!is_dir($oldfile) && file_exists($oldfile))
            unlink($oldfile);
        mt_srand();
        $filename = md5(uniqid(mt_rand())).'.png';
        $h->map_image = $filename;
        $h->save();
        $this->load->model('activitylog_model');
        $this->activitylog_model->insert_log($userdata['uid'], RESTAURANT_UPDATE, $h->id);
        getStaticMapImage($latitude, $longitude, 'R', $filename);
        $map_img_name = 'uploads/'.$filename;
        $this->load->view('restaurant/map_img', array('map_image' => $map_img_name));
    }

    public function ajax_get_cuisines(){
        $q = $this->input->post('q');
        $c = new Cuisine_Model();
        $c->ilike('title', $q)->order_by('title')->get(10);
        $result = array();
        foreach($c as $i){
            $result[] = array('id' => $i->id, 'text' => $i->title);
        }
        echo json_encode($result);
    }

    public function ajax_save_cuisines(){
        $userdata = $this->session->userdata('logged_in');
        $id = $this->input->post('id');
        $r = new Global_Restaurant_Model();
        $r->where('id', $id)->get();
        $ids = $this->input->post('ids');
        $c_del = new Cuisine_Model();
        if(isset($ids) && $ids <> ""){
            $c_del->where('id NOT IN ('.$ids.')')->get();
        }
        else{
            $c_del->get();
        }
        $r->delete_cuisine($c_del->all);
        if(isset($ids) && $ids <> ""){
            $c = new Cuisine_Model();
            $c->where('id IN ('.$ids.')')->get();
            $r->save(array('cuisine' => $c->all));
        }
        $this->load->model('activitylog_model');
        $this->activitylog_model->insert_log($userdata['uid'], RESTAURANT_UPDATE, $r->id);
        $r->cuisine->get();

        $cuisine_ids = array();
        $cuisine_titles = '';
        foreach($r->cuisine as $i){
            $cuisine_ids[] = $i->id;
            $cuisine_titles .= $i->title.', ';
        }
        $cuisine_titles = rtrim($cuisine_titles, ", ");
        $data['restaurant_cuisine_titles'] = $cuisine_titles;
        $c = new Cuisine_Model();
        $c->order_by('title')->get();
        $result = array();
        foreach($c as $i){
            $selected = FALSE;
            if(in_array($i->id, $cuisine_ids))
                $selected = TRUE;
            $result[] = array('id' => $i->id, 'text' => $i->title, 'selected' => $selected);
        }
        $data['cuisines_all'] = $result;

        $this->load->view('restaurant/ajax_restaurant_cuisines', $data);
    }
 
    public function cuisines(){
       $session_data = $this->session->userdata('logged_in');
       $data['username'] = $session_data['username'];
       $data['title']= 'Manage cuisines';
       $c = new Cuisine_Model();
       $data['cuisines']= $c->get()->all_to_array();
       $data['content']= 'restaurant/cuisines';
       $data['jsIncludes'] = array('cuisine.js');
       $this->load->view('template', $data);
    }
 
    function cuisine_new(){
     $title = $this->input->post('title');
     $c = new Cuisine_Model();
     $c->title=$title;
     $c->save();
     redirect('restaurant/cuisines', 'refresh');
   }
 
    function ajax_cuisine_save(){
      $id = explode('_',$this->input->post('id'));
      $value = $this->input->post('title');
      $c = new Cuisine_Model();
      $c->where('id', $id[1])->get();
      $c->title=$value;
      $success = $c->save();
      echo json_encode(array('success' => $success));
   }
 
    public function edit($id){
      $session_data = $this->session->userdata('logged_in');
      $data['username'] = $session_data['username'];
      $data['title']= 'Edit restaurant';
      $data['content']= 'restaurant/form';
      $r = new Global_Restaurant_Model();
      $r->where('id', $id)->get();
      $data['restaurant']= $r->to_array();
      $data['jsIncludes'] = array('edit_page.js');
      $this->load->view('template', $data);
    }
 
    public function review_edit($id){
      $session_data = $this->session->userdata('logged_in');
      $data['username'] = $session_data['username'];
      $data['title']= 'Edit review';
      $data['content']= 'restaurant/form_review';
      $r = new Restaurant_Review_Model();
      $r->where('id', $id)->get();
      $r->restaurant->get();
      $data['restaurant_id']= $r->restaurant->id;
      $data['review']= $r->to_array();
      $this->load->view('template', $data);
    }
     public function add(){
      $session_data = $this->session->userdata('logged_in');
      $data['username'] = $session_data['username'];
      $data['title']= 'Add restaurant';
      $data['content']= 'restaurant/form';
      $data['jsIncludes'] = array('edit_page.js');
      $this->load->view('template', $data);
    }
      public function room_add($id){
      $session_data = $this->session->userdata('logged_in');
      $data['username'] = $session_data['username'];
      $data['title']= 'Add room';
      $data['restaurant_id']= $id;
      $data['content']= 'restaurant/form_room';
      $this->load->view('template', $data);
    }
    public function review_add($id){
      $session_data = $this->session->userdata('logged_in');
      $data['username'] = $session_data['username'];
      $data['title']= 'Add review';
      $data['restaurant_id']= $id;
      $data['content']= 'restaurant/form_review';
      $this->load->view('template', $data);
    }
   public function submit(){
     $userdata = $this->session->userdata('logged_in');
     $id = $this->input->post('id');
     $r = new Global_Restaurant_Model();
     if($id <> '' ){
         $r->where('id', $id)->get();
     }
     else{
         $r->city = '';
     }
     $r->name = $this->input->post('name');
     $r->latitude = $this->input->post('latitude');
     $r->longitude = $this->input->post('longitude');
     $r->country = $this->input->post('country');
   //  $r->city = $this->input->post('city');
     $r->address = $this->input->post('address');
     $r->about = $this->input->post('about');
     $r->description = $this->input->post('description');
     $r->facilities = $this->input->post('facilities');
     $r->city_id = $this->input->post('cityId');
     $r->zipcode = $this->input->post('zipcode');
     $r->phone = $this->input->post('phone');
     $r->fax = $this->input->post('fax');
     $r->email = $this->input->post('email');
     $r->website = $this->input->post('website');
     $r->opening_hours = $this->input->post('opening_hours');
     $r->opening_days = $this->input->post('opening_days');
     if($id == '' && $r->latitude != '' && $r->longitude != ''){
       $this->load->helper('map_image');
       $latitude = $r->latitude;
       $longitude = $r->longitude;
       mt_srand();
       $filename = md5(uniqid(mt_rand())).'.png';
       $r->map_image = $filename;
       getStaticMapImage($latitude, $longitude, 'R', $filename);
     }
     $r->save();
     $this->load->model('activitylog_model');
     $activity_code = $id == '' ? RESTAURANT_INSERT : RESTAURANT_UPDATE;
     $this->activitylog_model->insert_log($userdata['uid'], $activity_code, $r->id);
     redirect('restaurant/view/'.$r->id, 'refresh');
    }



    function delete_file($file_id){
     $userdata = $this->session->userdata('logged_in');   
     $this->load->model('activitylog_model');
     $this->load->model('restaurant_image_model');
     if ($this->restaurant_image_model->delete_file($file_id))
       {
         $this->activitylog_model->insert_log($userdata['uid'], RESTAURANT_IMAGE_DELETE, $file_id);
           $status = 'success';
           $msg = 'File successfully deleted';
       }
       else
       {
           $status = 'error';
           $msg = 'Something went wrong when deleteing the file, please try again';
       }
       echo json_encode(array('status' => $status, 'msg' => $msg));

    }


   function review_submit(){
      $userdata = $this->session->userdata('logged_in');
      $r = new Restaurant_Review_Model();
      $id = $this->input->post('id');
      if($id <>'') $r->where('id', $id )->get();
      $r->restaurant_id=$this->input->post('restaurant_id');
      $r->title=$this->input->post('title');
      $r->description=$this->input->post('description');
      $r->save();
      $this->load->model('activitylog_model');
      $activity_code = $id == '' ? RESTAURANT_REVIEW_INSERT : RESTAURANT_REVIEW_UPDATE;
      $this->activitylog_model->insert_log($userdata['uid'], $activity_code, $r->id);
      redirect('restaurant/view/'.$this->input->post('restaurant_id'), 'refresh');
    }


   function review_delete($id){
      $userdata = $this->session->userdata('logged_in');
      $d = new Restaurant_Review_Model();
      if($id <>'') $d->where('id', $id )->get();
      $restaurant_id = $d->restaurant_id;
      $d->delete();
      $this->load->model('activitylog_model');
      $this->activitylog_model->insert_log($userdata['uid'], RESTAURANT_REVIEW_DELETE, $id);
      redirect('restaurant/view/'.$restaurant_id, 'refresh');
    }

     function ajax_delete($id){
       $userdata = $this->session->userdata('logged_in');
       $d = new Global_Restaurant_Model();
       $success = FALSE;
       if($id <>'') {
           $d->where('id', $id )->get();
           $success = $d->delete();
           $this->load->model('activitylog_model');
           $this->activitylog_model->insert_log($userdata['uid'], RESTAURANT_DELETE, $id);
       }
       echo json_encode (array('success' => $success));
    }

    function delete($id){
      $d = new Global_Restaurant_Model();
      if($id <>'') {
          $d->where('id', $id )->get();
          $country = $d->country;
          $d->delete();

      }
      redirect('restaurant/index/'.$country, 'refresh');
    }
    /*
	function name:ajax_ml_cuisine_save
	purpose: Save multilanguage Cuisine in ml_discover_cuisine table
	Author: Mukesh
	Created: 2 dec 2014
    */
    function ajax_ml_cuisine_save(){
      $this->load->model('ml_discover_cuisine_model');
      $id = explode('_',$this->input->post('id'));
      $value = $this->input->post('title');
      $entity_id = $id[1];
      $lang_code = $id[0];
      $success = $this->ml_discover_cuisine_model->update_ml_cuisine($entity_id,$lang_code,$value);
      echo json_encode(array('success' => $success));
    }
    /*
	function name:default_image 
	purpose: Set default_image 1 for discover_restaurants_images table
	Author: Mukesh
	Created: 12 dec 2014
    */
    function default_image($id){
        $userdata = $this->session->userdata('logged_in');
        $data['content']= 'restaurant/view';
        $restaurant_id = $this->uri->segment(3);
        $image_id = $this->uri->segment(4);        
        $this->load->model('restaurant_image_model');
        if($this->restaurant_image_model->update_default_pic($restaurant_id,$image_id)){
            redirect('restaurant/view/'.$restaurant_id, 'refresh');        
        }
    }
    
    public function addedByUser(){
       $this->load->library('pagination');
       $session_data = $this->session->userdata('logged_in');
       $data['username'] = $session_data['username'];
       $data['title']= 'Added By User Restaurants';
       $data['content']= 'restaurant/addedByUserList';
       $r = new Global_Restaurant_Model();
       $total = $r->where('published',-1)->count();
       //$r->where('published',-1)->get(500);
        $config['uri_segment'] = 3;
        $config['per_page'] = 500;
        $config["num_links"] = 14;
        $config['base_url'] = 'restaurant/addedByUser';
        $config['total_rows'] = $total;
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $r->where('published',-1)->order_by('id')->get($config['per_page'], $page);
        $data['restaurants']= $r;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['jsIncludes'] = array('restaurant.js');
        $this->load->view('template', $data);
    }
    function ajax_accept($id){ //echo $id;die;
        $userdata = $this->session->userdata('logged_in');
        $d = new Global_Restaurant_Model();
        $success = FALSE;
        if($id <>'') {
            $success=$d->where('id', $id )->update('published','1');
        }
        echo json_encode (array('success' => $success));
    }
    
}
