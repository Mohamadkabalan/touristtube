<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Hotel extends MY_Controller {
	//code for Hotel selections module, added by sushma mishra on 04 dec 2015 starts from here
	/**
	Method will call to show the listing of Hotel Selections
	*/
    public function hotelSelection($start = 0){
      $this->load->library('pagination');
      $this->load->model('City_Model');        
      $session_data = $this->session->userdata('logged_in');
      $data['username'] = $session_data['username'];
      $data['title']= 'Hotels Selections';
      $data['content']= 'hotel/hotel_selection';
      $a = new Hotels_Selections_Model();
      $config['uri_segment'] = 3;
      $config['per_page'] = 500;
      $config["num_links"] = 14;
      $config['base_url'] = 'hotel/ajax_hotelSelection';		
      $total = $a->count();		
      $config['total_rows'] = $total; 
      $res = $a->order_by('id')->get(500);
      $arrHselection = $a->all_to_array();
      $c = new City_Model();
      $harr = array();
      $i=0;		
      foreach($arrHselection as $hsel){
          $cityInfo = $c->getbyid($hsel['city_id']);
          $harr[$i]['id'] = $hsel['id'];
          $harr[$i]['city_id'] = $hsel['city_id'];
          $harr[$i]['city_name'] = $cityInfo['name'];
          $harr[$i]['img'] = $hsel['img'];
          $harr[$i]['count'] = $hsel['count'];
          $harr[$i]['selection_type'] = $hsel['selection_type'];
          $harr[$i]['published'] = $hsel['published'];		
          $i++;
      }           
      $data['aitems']= $harr;
      $this->pagination->initialize($config);
      $data['links'] = $this->pagination->create_links();
      $data['jsIncludes'] = array('edit_page.js');
      $this->load->view('template', $data);
  }

  public function ajax_hotelSelection($start = 0){
      $this->load->library('pagination');
      $this->load->model('City_Model');     
      $config['uri_segment'] = 3;
      $config['per_page'] = 500;
      $config["num_links"] = 14;
      $config['base_url'] = 'hotel/ajax_hotelSelection';
      $a = new Hotels_Selections_Model();
      $total = $a->order_by('id')->count();
      $config['total_rows'] = $total;
      $limit = $total;
      $res = $a->order_by('id')->get(500, $start);
      $arrRselection = $a->all_to_array();
      $c = new City_Model();
      $harr = array();
      $i=0;
      foreach($arrRselection as $rsel){
          $cityInfo = $c->getbyid($rsel['city_id']);
          $harr[$i]['id'] = $rsel['id'];
          $harr[$i]['city_id'] = $rsel['city_id'];
          $harr[$i]['city_name'] = $cityInfo['name'];
          $harr[$i]['img'] = $rsel['img'];
          $harr[$i]['count'] = $rsel['count'];
          $harr[$i]['selection_type'] = $rsel['selection_type'];
          $harr[$i]['published'] = $rsel['published'];		
          $i++;
      }		
      $data['aitems']= $harr;
      $this->pagination->initialize($config);
      $data['links'] = $this->pagination->create_links();
      $this->load->view('hotel/ajax_hotelSelection', $data);
  }
	/**
	Method will call to show the form to add Hotel Selections
	*/
	public function addhselection(){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Add Hotel Selections';
        $data['content']= 'hotel/hselection_form';
        $data['jsIncludes'] = array('edit_page.js');
        $this->load->view('template', $data);
    }
	/**
	Method will call to show the form to edit Hotel Selections
	*/
	public function edithselection($id){
        $session_data = $this->session->userdata('logged_in');
        $this->load->model('City_Model');		
        $c = new City_Model();
        $data['username'] = $session_data['username'];
        $data['title']= 'Edit Hotel Selections';
        $data['content']= 'hotel/hselection_form';
        $h = new Hotels_Selections_Model();
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
	Method will call to add/edit Hotel Selections data
	*/
	public function hselection_submit(){
		$userdata = $this->session->userdata('logged_in');
		$id = $this->input->post('id');
		$cityId = $this->input->post('cityId');		
		$hm = new Hotel_Model();
		$count = $hm->where('city_id', $cityId)->count();
		$this->load->model('City_Model');		
		$c = new City_Model();
		$cityInfo = $c->getbyid($cityId);
		$cname =$cityInfo['name'];
		$h = new Hotels_Selections_Model();
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
		redirect('hotel/hotelSelection/', 'refresh');
	}
	
	/**
	Method will call to delete Hotel Selections
	*/
	public function ajaxhsel_delete($id){
		$userdata = $this->session->userdata('logged_in');
		$d = new Hotels_Selections_Model();
		$success = FALSE;
		if($id <>'') {
			$d->where('id', $id )->get();			
			$success = $d->delete();			
		}
		echo json_encode (array('success' => $success));
	}	
	//code for Hotel selections module, added by sushma mishra on 04 dec 2015 ends here
	
    public function fixImages(){
        ini_set("max_execution_time", 0);
        $originalpath = getcwd().'/uploads/original/';
        $mediumpath = getcwd().'/uploads';
        $thumbpath = getcwd().'/uploads/thumb';
        $dir = new DirectoryIterator($originalpath);
        $thumb_conf['image_library'] = 'GD2';
        $thumb_conf['quality'] = '90%';
        $thumb_conf['maintain_ratio'] = TRUE;
        $errors = '';
//    $count = 1;
        foreach($dir as $fileinfo){
            if(!$fileinfo->isDir()){
                $fullpath = $fileinfo->getPathname();
                $thumb_conf['source_image'] = $fullpath;
//            if(!file_exists($mediumpath.'/'.$fileinfo->getFilename())){
                $thumb_conf['new_image'] = $mediumpath;
                $thumb_conf['source_image'] = $fullpath;
                list($width, $height, $type, $attr) = getimagesize($fullpath);
                $new_height = 256;
                $new_width = 585;
                $scaleWidth= 585/$width;
                $scaleHeight= 256/$height;
                if($scaleWidth > $scaleHeight){
                    $new_width = $width*$scaleWidth;
                    $new_height = $height*$scaleWidth;
                }else{
                    $new_width = $width*$scaleHeight;
                    $new_height = $height*$scaleHeight;
                }
                $thumb_conf['height'] = $new_height;
                $thumb_conf['width'] = $new_width;
                
                $this->image_lib->initialize($thumb_conf);
                if(!$this->image_lib->resize()){
                    $errors .= $this->image_lib->display_errors();
                }
//            }
//            if(!file_exists($thumbpath.'/'.$fileinfo->getFilename())){
                $thumb_conf['new_image'] = $thumbpath;
                $thumb_conf['width'] = 200;
                $thumb_conf['height'] = 200;
                $this->image_lib->initialize($thumb_conf);
                if(!$this->image_lib->resize()){
                    $errors .= $this->image_lib->display_errors();
                }
//            }
            }
        }
        if($errors != '')
            print_r($errors);
        else
            print_r('Done.');
    }

    public function fixRoomsImages(){
        $originalpath = getcwd().'/uploads/rooms/original/';
        $mediumpath = getcwd().'/uploads/rooms';
        $thumbpath = getcwd().'/uploads/rooms/thumb';
        $dir = new DirectoryIterator($originalpath);
        $thumb_conf['image_library'] = 'GD2';
        $thumb_conf['quality'] = '90%';
        $thumb_conf['maintain_ratio'] = TRUE;
        $errors = '';
        foreach($dir as $fileinfo){
            if(!$fileinfo->isDir()){
                $fullpath = $fileinfo->getPathname();
                $thumb_conf['source_image'] = $fullpath;
//            if(!file_exists($mediumpath.'/'.$fileinfo->getFilename())){
                $thumb_conf['new_image'] = $mediumpath;
                $thumb_conf['source_image'] = $fullpath;
                list($width, $height, $type, $attr) = getimagesize($fullpath);
                $new_height = 177;
                $new_width = 308;
                $scaleWidth= 308/$width;
                $scaleHeight= 177/$height;
                if($scaleWidth > $scaleHeight){
                    $new_width = $width*$scaleWidth;
                    $new_height = $height*$scaleWidth;
                }else{
                    $new_width = $width*$scaleHeight;
                    $new_height = $height*$scaleHeight;
                }
                $thumb_conf['height'] = $new_height;
                $thumb_conf['width'] = $new_width;
                $this->image_lib->initialize($thumb_conf);
                if(!$this->image_lib->resize()){
                    $errors .= $this->image_lib->display_errors();
                }
//            }
//            if(!file_exists($thumbpath.'/'.$fileinfo->getFilename())){
                $thumb_conf['new_image'] = $thumbpath;
                $thumb_conf['width'] = 200;
                $thumb_conf['height'] = 200;
                $this->image_lib->initialize($thumb_conf);
                if(!$this->image_lib->resize()){
                    $errors .= $this->image_lib->display_errors();
                }
//            }
            }
        }
        if($errors != '')
            print_r($errors);
        else
            print_r('Done.');
    }

    public function index($cc = 'all', $start = 0){
       $this->load->library('pagination');
       $session_data = $this->session->userdata('logged_in');
       $data['username'] = $session_data['username'];
       $data['title']= 'Manage hotels';
       $data['content']= 'hotel/list';
       $h = new Hotel_Model();
       $this->load->model('Cms_Countries_Model');
       $data['country']= $this->Cms_Countries_Model->get_all_countries_name();
   //echo '<pre>';print_r($data['countryes']);
       $config['uri_segment'] = 3;
       $config['per_page'] = 500;
       $config["num_links"] = 14;
       $config['base_url'] = 'hotel/ajax_list';
       $total = $h->like('cityName', 'paris')->count();
       $config['total_rows'] = $total;
       $res = $h->like('cityName', 'paris')->order_by('id')->get(500);
       $data['hotels']= $h;
       $this->pagination->initialize($config);
       $data['links'] = $this->pagination->create_links();
       $data['ci']= 'paris';
       $data['jsIncludes'] = array('hotel.js');
       $this->load->view('template', $data);
   }
   function ajax_list($start = 0){
    $this->load->library('pagination');
    $cityName = $this->input->post('ci');
    $hotelName = $this->input->post('ho');
    $countryCode = $this->input->post('cc');
    $countryName = $this->input->post('co');
    $h = new Hotel_Model();
    $config['uri_segment'] = 3;
    $config['per_page'] = 500;
    $config["num_links"] = 14;
    $config['base_url'] = 'hotel/ajax_list';
    $total = $h->like('hotelName', $hotelName)->like('cityName', $cityName)->like('countryCode', $countryCode)->like('countryCode', $countryName)->count();
    $config['total_rows'] = $total;
    $limit = $total;
//    if($limit > 20000)
//        $limit = 20000;
//    $h->like('countryName', $countryName)->like('hotelName', $hotelName)->like('cityName', $cityName)->like('countryCode', $countryCode)->order_by('id')->get(200, $start);
    $res = $h->like('hotelName', $hotelName)->like('cityName', $cityName)->like('countryCode', $countryCode)->like('countryCode', $countryName)->order_by('id')->get(500, $start);
    $data['hotels']= $h;
    $this->pagination->initialize($config);
    $data['links'] = $this->pagination->create_links();
    $this->load->view('hotel/ajax_list', $data);
}

public function update_map_image(){
    $userdata = $this->session->userdata('logged_in');
    $this->load->helper('map_image');
    $id = $this->input->post('id');
    $h = new Hotel_Model();
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
    $this->activitylog_model->insert_log($userdata['uid'], HOTEL_UPDATE, $h->id);
    getStaticMapImage($latitude, $longitude, 'H', $filename);
    $map_img_name = 'uploads/'.$filename;
    $this->load->view('hotel/map_img', array('map_image' => $map_img_name));
    
}

function ajax_upload(){   
    $userdata = $this->session->userdata('logged_in');
    $status = "";
    $msg = "";
    $file_element_name = 'userfile';
    $hotel_id = $this->input->post('id');
    $this->load->helper('discover_title_helper');
    $type= 'hotel';
    if ($status != "error")
    { 
        $this->load->model('hotel_image_model');
        $this->load->model('activitylog_model');
        if( $this->config->item('upload_src') == "s3" ){
            try{
                $s3 = new S3($this->config->item('accessKey'), $this->config->item('secretKey'));

                $base_name = 'media/discover';

                foreach( $_FILES[$file_element_name]['name'] as $k => $file ){

                    $file_name = $_FILES[$file_element_name]['tmp_name'][$k];

                    $file_base_name = $_FILES[$file_element_name]['name'][$k];

                    $ext_info = pathinfo($file_base_name);
                    $ext = $ext_info["extension"];
                    $new_name = time();

                    $file_base_name = get_discover_hotels_title($hotel_id).'_'.$type.'_'.$new_name. '.'.$ext;

                    if ($s3->putObjectFile($file_name, $this->config->item('bucketName'),$base_name . '/' .$file_base_name , S3::ACL_PUBLIC_READ)) {

                        $file_id = $this->hotel_image_model->insert_file($file_base_name, $hotel_id);

                        if($file_id){
                            $this->activitylog_model->insert_log($userdata['uid'], HOTEL_IMAGE_INSERT, $file_id);
                            $status = "success";
                            $msg .= "File successfully uploaded";
                        }else{
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
            $config['upload_path'] = '../media/discover';
            $config['allowed_types'] = 'gif|jpeg|jpg|png';
            $config['max_size'] = 1024 * 8;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            $return_data = $this->upload->do_multi_upload($file_element_name);

            echo $this->upload->display_errors('', '');
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
                    $new_full_name = get_discover_hotels_title($hotel_id).'_'.$type.'_'.$new_name. '.' .end($extension);

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
                   $file_id = $this->hotel_image_model->insert_file($new_full_name, $hotel_id);
                   if($file_id)
                   {
                    $this->activitylog_model->insert_log($userdata['uid'], HOTEL_IMAGE_INSERT, $file_id);
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

}
echo json_encode(array('status' => $status));
}

public function files($id)
{
    $this->load->model('hotel_image_model');
    $files = $this->hotel_image_model->get_files($id);
    $files_array = array();
    foreach($files as $file){
        $files_array[] = array('id' => $file->id, 'filename' => $file->filename);
    }
    $this->load->view('hotel/files', array('files' => $files_array));
}

public function view($id){ 
    $this->load->model('hotel_image_model');
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['title']= 'View hotel';
    $data['content']= 'hotel/view';
    $h = new Hotel_Model();
    $h->where('id', $id)->get();
    $h->hotel_feature->get();
    $h->room->get();
    $h->review->get();
    $data['hotel']= $h->to_array();
    $data['rooms'] = $h->room->all_to_array();
    $data['reviews'] = $h->review->all_to_array();
    $facilities = array();
    $facility_ids = '';
    $facility_titles = '';
    foreach($h->hotel_feature as $i){
        $facilities[] = array('id' => $i->id, 'text' => $i->title);
        $facility_ids .= $i->id.',';
        $facility_titles .= $i->title.', ';
    }
    $facility_ids = rtrim($facility_ids, ",");
    $facility_titles = rtrim($facility_titles, ", ");
    $data['hotel_facilities'] = json_encode($facilities);
    $data['hotel_facility_ids'] = $facility_ids;
    $data['hotel_facility_titles'] = $facility_titles;

    $this->load->model('city_search_model');
    $city_res = $this->city_search_model->getbyid($h->city_id);
    $city = '';
    if(count($city_res)){
        $city = $city_res['text'];
    }
    $data['cityName'] = $city;

    $files = $this->hotel_image_model->get_files($id);
    $files_array = array();
    foreach($files as $file){
        $files_array[] = array('id' => $file->id, 'filename' => $file->filename,'default_pic' => $file->default_pic);
    }
    $data['files'] = $files_array;
    $data['jsIncludes'] = array('hotel.js');
    $data['cssIncludes'] = array('hotel.css');
//        $map_img_name = 'uploads/map_h_'.$h->id.'.png';
    if(isset($h->map_image) && $h->map_image <> '')
        $data['map_image'] = 'uploads/'.$h->map_image;

    $this->load->view('template', $data);

}

/*** ajax_get_facilities has changed ajax_get_features ****/

public function ajax_get_features(){  
    $q = $this->input->post('q');
    $f = new Hotel_Feature_Model();
    $f->like('title', $q)->order_by('title')->get(10);
    $result = array();
    foreach($f as $i){
        $result[] = array('id' => $i->id, 'text' => $i->title);
    }
    echo json_encode($result);
}

/*** ajax_save_facilities has changed ajax_save_features ****/

public function ajax_save_features(){
    $userdata = $this->session->userdata('logged_in');
    $id = $this->input->post('id');
    $h = new Hotel_Model();
    $h->where('id', $id)->get();
    $ids = $this->input->post('ids');
    $f_del = new Hotel_Feature_Model();
    if(isset($ids) && $ids <> ""){
        $f_del->where('id NOT IN ('.$ids.')')->get();
    }
    else{
        $f_del->get();
    }
    $h->delete_hotel_feature($f_del->all);
    if(isset($ids) && $ids <> ""){
        $f = new Hotel_Feature_Model();
        $f->where('id IN ('.$ids.')')->get();
        $h->save(array('hotel_feature' => $f->all));
        
    }
    $this->load->model('activitylog_model');
    $this->activitylog_model->insert_log($userdata['uid'], HOTEL_UPDATE, $h->id);
    $data['hotel'] = $h;
    $h->hotel_feature->get();
    $facilities = array();
    $facility_ids = '';
    $facility_titles = '';
    foreach($h->hotel_feature as $i){
        $facilities[] = array('id' => $i->id, 'text' => $i->title);
        $facility_ids .= $i->id.',';
        $facility_titles .= $i->title.', ';
    }
    $facility_ids = rtrim($facility_ids, ",");
    $facility_titles = rtrim($facility_titles, ", ");
    $data['hotel_facilities'] = json_encode($facilities);
    $data['hotel_facility_ids'] = $facility_ids;
    $data['hotel_facility_titles'] = $facility_titles;
    $this->load->view('hotel/ajax_hotel_facilities', $data);
}

public function edit($id){
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'Edit hotel';
   $data['content']= 'hotel/form';
   $h = new Hotel_Model();
   $h->where('id', $id)->get();
   $data['hotel']= $h->to_array();
   $data['jsIncludes'] = array('edit_page.js');
   $this->load->view('template', $data);
}

public function room_edit($id){
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'Edit room';
   $data['content']= 'hotel/form_room';
   $r = new Room_Model();
   $r->where('id', $id)->get();
   $r->hotel->get();
   $data['hotel_id']= $r->hotel->id;
   $data['room']= $r->to_array();
   $this->load->view('template', $data);
}
public function review_edit($id){
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'Edit review';
   $data['content']= 'hotel/form_review';
   $r = new Review_Model();
   $r->where('id', $id)->get();
   $r->hotel->get();
   $data['hotel_id']= $r->hotel->id;
   $data['review']= $r->to_array();
   $this->load->view('template', $data);
}
public function add(){
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'Add hotel';
   $data['content']= 'hotel/form';
   $data['jsIncludes'] = array('edit_page.js');
   //$h = new Hotel_Model();
   //$h->where('id', $id)->get();
   //$data['hotel']= $h;
   $this->load->view('template', $data);
}
public function room_add($id){
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'Add room';
   $data['hotel_id']= $id;
   $data['content']= 'hotel/form_room';
   $this->load->view('template', $data);
}
public function review_add($id){
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'Add review';
   $data['hotel_id']= $id;
   $data['content']= 'hotel/form_review';
   $this->load->view('template', $data);
}
public function submit(){
  $userdata = $this->session->userdata('logged_in');
  $id = $this->input->post('id');
  $h = new Hotel_Model();
  if($id <> '' ){
      $h->where('id', $id)->get();
  }
  else{
      $h->cityName = '';
  }
  $h->hotelName = $this->input->post('hotelName');
  $h->stars = $this->input->post('stars');
  $h->price = $this->input->post('price');
//  $h->cityName = $this->input->post('cityName');
  $h->stateName = $this->input->post('stateName');
  $h->countryCode = $this->input->post('countryCode');
  $h->countryName = $this->input->post('countryName');
  $h->address = $this->input->post('address');
  $h->location = $this->input->post('location');
  $h->tripadvisorUrl = $this->input->post('tripadvisorUrl');
  $h->latitude = $this->input->post('latitude');
  $h->longitude = $this->input->post('longitude');
  $h->latlong = $this->input->post('latlong');
  $h->propertyType = $this->input->post('propertyType');
  $h->chainId = $this->input->post('chainId');
  $h->rooms = $this->input->post('rooms');
  $h->facilities = $this->input->post('facilities');
  $h->checkIn = $this->input->post('checkIn');
  $h->checkOut = $this->input->post('checkOut');
  $h->rating = $this->input->post('rating');
  $h->about = $this->input->post('about');
  $h->description = $this->input->post('description');
  $h->general_facilities = $this->input->post('general_facilities');
  $h->services = $this->input->post('services');
  $h->city_id = $this->input->post('cityId');
  $h->zipcode = $this->input->post('zipcode');
  $h->phone = $this->input->post('phone');
  $h->fax = $this->input->post('fax');
  $h->email = $this->input->post('email');
  $h->website = $this->input->post('website');
  $f = new Hotel_Feature_Model();
  $f->where('id IN ('.str_replace('|',',',$h->facilities).'999999999)')->get();
  if($id == '' && $h->latitude != '' && $h->longitude != ''){
    $this->load->helper('map_image');
    $latitude = $h->latitude;
    $longitude = $h->longitude;
    mt_srand();
    $filename = md5(uniqid(mt_rand())).'.png';
    $h->map_image = $filename;
    getStaticMapImage($latitude, $longitude, 'H', $filename);
}
$h->save(array('hotel_feature' => $f->all));
$this->load->model('activitylog_model');
$activity_code = $id == '' ? HOTEL_INSERT : HOTEL_UPDATE;
$this->activitylog_model->insert_log($userdata['uid'], $activity_code, $h->id);
redirect('hotel/view/'.$h->id, 'refresh');
}

public function features(){
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'Manage features';
   $h = new Hotel_Feature_Model();
   $data['facilities']= $h->get()->all_to_array();
   $t = new Discover_Hotels_Feature_Type_Model();
   $data['feature_type'] = $t->get()->all_to_array();
   $data['content']= 'hotel/features';
   $data['jsIncludes'] = array('feature.js');
   $this->load->view('template', $data);
}



function delete_file($file_id){
  $userdata = $this->session->userdata('logged_in');   
  $this->load->model('activitylog_model');
  $this->load->model('hotel_image_model');

  if( $this->config->item('upload_src') == "s3" ){
    $file_ids = $this->hotel_image_model->get_file($file_id);
    S3::deleteObject($this->config->item('bucketName'),'media/discover/'.$file_ids->filename);
}

if ($this->hotel_image_model->delete_file($file_id))
{
    $this->activitylog_model->insert_log($userdata['uid'], HOTEL_IMAGE_DELETE, $file_id);
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

/*** ajax_facility_save function name has changed ajax_feature_save ****/ 
function ajax_feature_save(){
    $id = explode('_',$this->input->post('id'));
    $value = $this->input->post('title');
    $f = new Hotel_Feature_Model();
    $f->where('id', $id[1])->get();
    $f->title=$value;
    $success = $f->save();
    echo json_encode(array('success' => $success));
}

function facility_save(){
  $id = explode('_',$this->input->post('id'));
  $value = $this->input->post('value');
  $f = new Hotel_Feature_Model();
  $f->where('id', $id[1])->get();
  $f->title=$value;
  $f->save();
  echo $value;
}

function room_submit(){
   $userdata = $this->session->userdata('logged_in');
   $d = new Room_Model();
   $id = $this->input->post('id');
   if($id <>'') $d->where('id', $id )->get();
   $d->hotel_id=$this->input->post('hotel_id');
   $d->title=$this->input->post('title');
   $d->description=$this->input->post('description');
   $d->num_person=$this->input->post('num_person');
   $d->price=$this->input->post('price');
   $d->pic1=$this->input->post('pic1name');
   $d->pic2=$this->input->post('pic2name');
   $d->pic3=$this->input->post('pic3name');

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
   if($r1<>'') $d->pic1 = $r1['file_name']; 
   if($r2<>'') $d->pic2 = $r2['file_name'];
   if($r3<>'') $d->pic3 = $r3['file_name'];
   $d->save();
   $this->load->model('activitylog_model');
   $activity_code = $id == '' ? HOTEL_ROOM_INSERT : HOTEL_ROOM_UPDATE;
   $this->activitylog_model->insert_log($userdata['uid'], $activity_code, $d->id);
   redirect('hotel/view/'.$this->input->post('hotel_id'), 'refresh');
}

function Resize_Room_Pic($file_name){
    $thumb_conf['image_library'] = 'GD2';
    $thumb_conf['maintain_ratio'] = TRUE;
    $thumb_conf['quality'] = '100%';
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

function review_submit(){
   $userdata = $this->session->userdata('logged_in');
   $r = new Review_Model();
   $id = $this->input->post('id');
   if($id <>'') $r->where('id', $id )->get();
   $r->hotel_id=$this->input->post('hotel_id');
   $r->title=$this->input->post('title');
   $r->description=$this->input->post('description');
   $r->save();
   $this->load->model('activitylog_model');
   $activity_code = $id == '' ? HOTEL_REVIEW_INSERT : HOTEL_REVIEW_UPDATE;
   $this->activitylog_model->insert_log($userdata['uid'], $activity_code, $r->id);
   redirect('hotel/view/'.$this->input->post('hotel_id'), 'refresh');
}

function room_delete($id){
   $userdata = $this->session->userdata('logged_in');
   $d = new Room_Model();
   if($id <>'') $d->where('id', $id )->get();
   $hotel_id = $d->hotel_id;
   $d->delete();
   $this->load->model('activitylog_model');
   $this->activitylog_model->insert_log($userdata['uid'], HOTEL_ROOM_DELETE, $id);
   redirect('hotel/view/'.$hotel_id, 'refresh');
}

function review_delete($id){
    $userdata = $this->session->userdata('logged_in');
    $d = new Review_Model();
    if($id <>'') $d->where('id', $id )->get();
    $hotel_id = $d->hotel_id;
    $d->delete();
    $this->load->model('activitylog_model');
    $this->activitylog_model->insert_log($userdata['uid'], HOTEL_REVIEW_DELETE, $id);
    redirect('hotel/view/'.$hotel_id, 'refresh');
}

function feature_new(){
   $title = $this->input->post('title');
   $title_type = $this->input->post('type');
   $f = new Hotel_Feature_Model();
   $f->title=$title;
   $f->feature_type=$title_type;
   $f->save();
   redirect('hotel/features', 'refresh');
}

function fsearch(){
    $str = $_GET['term'];
    $facilities = new Hotel_Feature_Model();
    $facilities->where('title like', "%$str%" )->get();
    $i=0;
    foreach($facilities as $f) {$arr[$i]= array('id'=>$f->id, 'value'=>$f->title, 'label'=> $f->title); $i++;}
    $this->output->set_content_type('application/json') ->set_output(json_encode($arr));
}

function ajax_delete($id){
    $userdata = $this->session->userdata('logged_in');
    $d = new Hotel_Model();
    $success = FALSE;
    if($id <>'') {
        $d->where('id', $id )->get();
        $this->load->model('hotel_image_model');
        if( $this->config->item('upload_src') == "s3" ){

            $file_ids = $this->hotel_image_model->get_files($id);
            foreach ($file_ids as $key => $file_info) {
                S3::deleteObject($this->config->item('bucketName'),'media/discover/'.$file_info->filename);
            }
        }
        
        $success = $d->delete();
        $this->load->model('activitylog_model');
        $this->activitylog_model->insert_log($userdata['uid'], HOTEL_DELETE, $id);
    }
    echo json_encode (array('success' => $success));
}

function delete($id){
   $d = new Hotel_Model();
   if($id <>'') {
       $d->where('id', $id )->get();
       $city = $d->cityName;
       $d->delete();
   }
   redirect('hotel/index/'.$city, 'refresh');
}
    /*
	function name:default_image 
	purpose: Set default_image 1 for discover_hotel_images table
	Author: Mukesh
	Created: 15 dec 2014
    */
    function default_image($id){
        $userdata = $this->session->userdata('logged_in');
        $data['content']= 'hotel/view';
        $hotel_id = $this->uri->segment(3);
        $image_id = $this->uri->segment(4);        
        $this->load->model('hotel_image_model');
        if($this->hotel_image_model->update_default_pic($hotel_id,$image_id)){
            redirect('hotel/view/'.$hotel_id, 'refresh');        
        }
    }
    /*
	function name:ajax_feature_type_save 
	Author: Mukesh
	Created: 18 dec 2014
    */
    function ajax_feature_type_save(){
        $id = explode('_',$this->input->post('id'));
        $value = $this->input->post('feature_type');
        $ft = new Hotel_Feature_Model();
        $ft->where('id', $id[1])->get();
        $ft->feature_type=$value;
        $success = $ft->save();
        echo json_encode(array('success' => $success));
    }
    /*
	function name:ajax_feature_type_save 
	Author: Mukesh
	Created: 06 May 2015
    */
    public function addedByUser($cc = 'all', $start = 0){
        $this->load->library('pagination');
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Added By User Hotels';
        $data['content']= 'hotel/addedByUserList';
        $h = new Hotel_Model();
        $total = $h->where('published',-1)->count();
        //$h->where('published',1)->get(500);
        $config['uri_segment'] = 3;
        $config['per_page'] = 500;
        $config["num_links"] = 14;
        $config['base_url'] = 'hotel/addedByUser';
        $config['total_rows'] = $total;
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $h->where('published',-1)->order_by('id')->get($config['per_page'], $page);
        $data['hotels']=$h;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['jsIncludes'] = array('hotel.js');
        $this->load->view('template', $data);
    }
    
    function ajax_accept($id){ //echo $id;die;
        $userdata = $this->session->userdata('logged_in');
        $d = new Hotel_Model();
        $success = FALSE;
        if($id <>'') {
            $success=$d->where('id', $id )->update('published','1');
        }
        echo json_encode (array('success' => $success));
    }

}
