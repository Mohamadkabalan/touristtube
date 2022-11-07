<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Poi extends MY_Controller {
  public function index($cc = 'all', $start = 0){
   $this->load->library('pagination');
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'Manage poi';
   $data['content']= 'poi/list';
   $r = new Poi_Model();
   $config['uri_segment'] = 3;
   $config['per_page'] = 500;
   $config["num_links"] = 14;
   $config['base_url'] = 'poi/ajax_list'; 
   $total = $r->like('country', 'fr')->count();
   $config['total_rows'] = $total;  
   $r->like('country', 'fr')->order_by('id')->get(500);
   $data['pois']= $r;
   
   $this->pagination->initialize($config);
   $data['links'] = $this->pagination->create_links();
   $data['cc']= 'fr';
   $data['jsIncludes'] = array('poi.js');
   $this->load->view('template', $data);
 }
 function ajax_list($start = 0){
  $this->load->library('pagination');
  $name = $this->input->post('poi');
  $countryCode = $this->input->post('cc');
  $city = intval($this->input->post('ci'));
  $r = new Poi_Model();
  $config['uri_segment'] = 3;
  $config['per_page'] = 500;
  $config["num_links"] = 14;
  $config['base_url'] = 'poi/ajax_list';
  if($city > 0){
    $total = $r->like('name', $name)->like('country', $countryCode)->where('city_id', $city)->count();
  }
  else{
    $total = $r->like('name', $name)->like('country', $countryCode)->count();
  }
  $config['total_rows'] = $total;
  $limit = $total;
//    if($limit > 20000)
//        $limit = 20000;
  if($city > 0){
    $r->like('name', $name)->like('country', $countryCode)->where('city_id', $city)->order_by('id')->get(500, $start);
  }
  else{
    $r->like('name', $name)->like('country', $countryCode)->order_by('id')->get(500, $start);
  }

  $data['pois']= $r;
  $this->pagination->initialize($config);
  $data['links'] = $this->pagination->create_links();
  $this->load->view('poi/ajax_list', $data);
}
function ajax_upload(){
  $userdata = $this->session->userdata('logged_in');
  $status = "";
  $msg = "";
  $file_element_name = 'userfile';
  $poi_id = $this->input->post('id');
  $this->load->helper('discover_title_helper');
  $type= 'poi';
  if ($status != "error")
  {
    $this->load->model('poi_image_model');
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

          $file_base_name = get_discover_hotels_title($poi_id).'_'.$type.'_'.$new_name. '.'.$ext;

          if ($s3->putObjectFile($file_name, $this->config->item('bucketName'),$base_name . '/' .$file_base_name , S3::ACL_PUBLIC_READ)) {

            $file_id = $this->poi_image_model->insert_file($file_base_name, $poi_id);

            if($file_id){
              $this->activitylog_model->insert_log($userdata['uid'], POI_IMAGE_INSERT, $file_id);
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
          $new_full_name = get_discover_poi_title($poi_id).'_'.$type.'_'.$new_name. '.' .end($extension);
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
          $file_id = $this->poi_image_model->insert_file($new_full_name, $this->input->post('id'));
          if($file_id)
          {
            $this->activitylog_model->insert_log($userdata['uid'], POI_IMAGE_INSERT, $file_id);
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

public function files($id){
  $this->load->model('poi_image_model');
  $files = $this->poi_image_model->get_files($id);
  $files_array = array();
  foreach($files as $file){
    $files_array[] = array('id' => $file->id, 'filename' => $file->filename);
  }
  $this->load->view('poi/files', array('files' => $files_array));
}

public function view($id){
  $this->load->model('poi_image_model');
  $session_data = $this->session->userdata('logged_in');
  $data['username'] = $session_data['username'];
  $data['title']= 'View poi';
  $data['content']= 'poi/view';
  $r = new Poi_Model();
  $r->where('id', $id)->get();
  $r->review->get();
  $r->category->get();
  $poi_array = $r->to_array();
  $data['poi']= $poi_array;

  $nearby = array();
  $nearby_array = explode(',', $poi_array['nearby_includes']);
  $nearby['hotel_nearby'] = in_array('h', $nearby_array) ? "Yes" : "No";
  $nearby['restaurant_nearby'] = in_array('r', $nearby_array) ? "Yes" : "No";
  $nearby['poi_nearby'] = in_array('p', $nearby_array) ? "Yes" : "No";
  $data['nearby'] = $nearby;
  $data['reviews'] = $r->review->all_to_array();

  $category_ids = array();
  $category_titles = '';
  foreach($r->category as $i){
    $category_ids[] = $i->id;
    $category_titles .= $i->title.', ';
  }
  $category_titles = rtrim($category_titles, ", ");
  $data['poi_category_titles'] = $category_titles;
  $c = new Category_Model();
  $c->order_by('title')->get();
  $result = array();
  foreach($c as $i){
    $selected = FALSE;
    if(in_array($i->id, $category_ids))
      $selected = TRUE;
    $result[] = array('id' => $i->id, 'text' => $i->title, 'selected' => $selected);
  }
  $data['categories_all'] = $result;

  $this->load->model('city_search_model');
  $city_res = $this->city_search_model->getbyid($r->city_id);
  $city = '';
  if(count($city_res)){
    $city = $city_res['text'];
  }
  $data['cityName'] = $city;

  $files = $this->poi_image_model->get_files($id);
  $files_array = array();
  foreach($files as $file){
    $files_array[] = array('id' => $file->id, 'filename' => $file->filename, 'default_pic' => $file->default_pic);
  }

  $data['files'] = $files_array;
  $data['jsIncludes'] = array('poi.js');
  $data['cssIncludes'] = array('hotel.css');
  if(isset($r->map_image) && $r->map_image <> '')
    $data['map_image'] = 'uploads/'.$r->map_image;
  $this->load->view('template', $data);
}

public function update_map_image(){
  $userdata = $this->session->userdata('logged_in');
  $this->load->helper('map_image');
  $id = $this->input->post('id');
  $h = new Poi_Model();
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
  $this->activitylog_model->insert_log($userdata['uid'], POI_UPDATE, $h->id);
  getStaticMapImage($latitude, $longitude, 'L', $filename);
  $map_img_name = 'uploads/'.$filename;
  $this->load->view('poi/map_img', array('map_image' => $map_img_name));
}


public function ajax_get_categories(){
  $q = $this->input->post('q');
  $c = new Category_Model();
  $c->ilike('title', $q)->order_by('title')->get(10);
  $result = array();
  foreach($c as $i){
    $result[] = array('id' => $i->id, 'text' => $i->title);
  }
  echo json_encode($result);
}

public function ajax_save_categories(){
  $userdata = $this->session->userdata('logged_in');
  $id = $this->input->post('id');
  $p = new Poi_Model();
  $p->where('id', $id)->get();
  $ids = $this->input->post('ids');
  $c_del = new Category_Model();
  if(isset($ids) && $ids <> ""){
    $c_del->where('id NOT IN ('.$ids.')')->get();
  }
  else{
    $c_del->get();
  }
  $p->delete_category($c_del->all);
  if(isset($ids) && $ids <> ""){
    $c = new Category_Model();
    $c->where('id IN ('.$ids.')')->get();
    $p->save(array('category' => $c->all));
  }
  $this->load->model('activitylog_model');
  $this->activitylog_model->insert_log($userdata['uid'], POI_UPDATE, $p->id);
  $p->category->get();

  $category_ids = array();
  $category_titles = '';
  foreach($p->category as $i){
    $category_ids[] = $i->id;
    $category_titles .= $i->title.', ';
  }
  $category_titles = rtrim($category_titles, ", ");
  $data['poi_category_titles'] = $category_titles;
  $c = new Category_Model();
  $c->order_by('title')->get();
  $result = array();
  foreach($c as $i){
    $selected = FALSE;
    if(in_array($i->id, $category_ids))
      $selected = TRUE;
    $result[] = array('id' => $i->id, 'text' => $i->title, 'selected' => $selected);
  }
  $data['categories_all'] = $result;

  $this->load->view('poi/ajax_poi_categories', $data);
}

public function categories(){
  $session_data = $this->session->userdata('logged_in');
  $data['username'] = $session_data['username'];
  $data['title']= 'Manage categories';
  $c = new Category_Model();
  $data['categories']= $c->get()->all_to_array();
  $data['content']= 'poi/categories';
  $data['jsIncludes'] = array('category.js');
  $this->load->view('template', $data);
}

function category_new(){
 $title = $this->input->post('title');
 $c = new Category_Model();
 $c->title=$title;
 $c->save();
 redirect('poi/categories', 'refresh');
}

function ajax_category_save(){
  $id = explode('_',$this->input->post('id'));
  $value = $this->input->post('title');
  $c = new Category_Model();
  $c->where('id', $id[1])->get();
  $c->title=$value;
  $success = $c->save();
  echo json_encode(array('success' => $success));
}

function category_save(){
  $id = explode('_',$this->input->post('id'));
  $value = $this->input->post('value');
  $c = new Category_Model();
  $c->where('id', $id[1])->get();
  $c->title=$value;
  $c->save();
  echo $value;
}

public function edit($id){
 $session_data = $this->session->userdata('logged_in');
 $data['username'] = $session_data['username'];
 $data['title']= 'Edit poi';
 $data['content']= 'poi/form';
 $r = new Poi_Model();
 $r->where('id', $id)->get();
 $poi_array = $r->to_array();
 $data['poi']= $poi_array;
 $nearby = array();
 $nearby_array = explode(',', $poi_array['nearby_includes']);
 $nearby['hotel_nearby'] = in_array('h', $nearby_array) ? "1" : "0";
 $nearby['restaurant_nearby'] = in_array('r', $nearby_array) ? "1" : "0";
 $nearby['poi_nearby'] = in_array('p', $nearby_array) ? "1" : "0";
 $data['nearby'] = $nearby;
 $data['jsIncludes'] = array('poi.js', 'edit_page.js');
 $this->load->view('template', $data);
}

public function review_edit($id){
 $session_data = $this->session->userdata('logged_in');
 $data['username'] = $session_data['username'];
 $data['title']= 'Edit review';
 $data['content']= 'poi/form_review';
 $r = new Poi_Review_Model();
 $r->where('id', $id)->get();
 $r->poi->get();
 $data['poi_id']= $r->poi->id;
 $data['review']= $r->to_array();
 $this->load->view('template', $data);
}
public function add(){
 $session_data = $this->session->userdata('logged_in');
 $data['username'] = $session_data['username'];
 $data['title']= 'Add poi';
 $data['content']= 'poi/form';
 $data['jsIncludes'] = array('poi.js', 'edit_page.js');
 $this->load->view('template', $data);
}
public function room_add($id){
 $session_data = $this->session->userdata('logged_in');
 $data['username'] = $session_data['username'];
 $data['title']= 'Add room';
 $data['poi_id']= $id;
 $data['content']= 'poi/form_room';
 $this->load->view('template', $data);
}
public function review_add($id){
 $session_data = $this->session->userdata('logged_in');
 $data['username'] = $session_data['username'];
 $data['title']= 'Add review';
 $data['poi_id']= $id;
 $data['content']= 'poi/form_review';
 $this->load->view('template', $data);
}
public function submit(){
  $userdata = $this->session->userdata('logged_in');
  $id = $this->input->post('id');
  $r = new Poi_Model();
  if($id <> '' )
    $r->where('id', $id)->get();
  else
    $r->city = '';
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
  $r->price = intval($this->input->post('price'));
  $r->description = $this->input->post('description');
  $show_on_map = TRUE;
  if($this->input->post('show_on_map') == "0")
    $show_on_map = FALSE;
  $r->show_on_map = $show_on_map;
  $hotel_nearby = $this->input->post('hotel_nearby');
  $restaurant_nearby = $this->input->post('restaurant_nearby');
  $poi_nearby = $this->input->post('poi_nearby');
  $nearby_includes = array();
  if($hotel_nearby != "0"){
    $nearby_includes[] = 'h';
  }
  if($restaurant_nearby != "0"){
    $nearby_includes[] = 'r';
  }
  if($poi_nearby != "0"){
    $nearby_includes[] = 'p';
  }
  $r->nearby_includes = implode(',', $nearby_includes);
  if($id == '' && $r->latitude != '' && $r->longitude != ''){
    $this->load->helper('map_image');
    $latitude = $r->latitude;
    $longitude = $r->longitude;
    mt_srand();
    $filename = md5(uniqid(mt_rand())).'.png';
    $r->map_image = $filename;
    getStaticMapImage($latitude, $longitude, 'L', $filename);
  }
  $r->save();
  $this->load->model('activitylog_model');
  $activity_code = $id == '' ? POI_INSERT : POI_UPDATE;
  $this->activitylog_model->insert_log($userdata['uid'], $activity_code, $r->id);
  redirect('poi/view/'.$r->id, 'refresh');
}



function delete_file($file_id){
  $userdata = $this->session->userdata('logged_in');   
  $this->load->model('activitylog_model');
  $this->load->model('poi_image_model');

  if( $this->config->item('upload_src') == "s3" ){
    $file_ids = $this->poi_image_model->get_file($file_id);
    S3::deleteObject($this->config->item('bucketName'),'media/discover/'.$file_ids->filename);
  }

  if ($this->poi_image_model->delete_file($file_id))
  {
    $this->activitylog_model->insert_log($userdata['uid'], POI_IMAGE_DELETE, $file_id);
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
  $r = new Poi_Review_Model();
  $id = $this->input->post('id');
  if($id <>'') $r->where('id', $id )->get();
  $r->poi_id=$this->input->post('poi_id');
  $r->title=$this->input->post('title');
  $r->description=$this->input->post('description');
  $r->save();
  $this->load->model('activitylog_model');
  $activity_code = $id == '' ? POI_REVIEW_INSERT : POI_REVIEW_UPDATE;
  $this->activitylog_model->insert_log($userdata['uid'], $activity_code, $r->id);
  redirect('poi/view/'.$this->input->post('poi_id'), 'refresh');
}


function review_delete($id){
  $userdata = $this->session->userdata('logged_in');
  $d = new Poi_Review_Model();
  if($id <>'') $d->where('id', $id )->get();
  $poi_id = $d->poi_id;
  $d->delete();
  $this->load->model('activitylog_model');
  $this->activitylog_model->insert_log($userdata['uid'], POI_REVIEW_DELETE, $id);
  redirect('poi/view/'.$poi_id, 'refresh');
}
function ajax_delete($id){
 $userdata = $this->session->userdata('logged_in');
 $d = new Poi_Model();
 $success = FALSE;
 if($id <>'') {
  $d->where('id', $id )->get();
  if( $this->config->item('upload_src') == "s3" ){
    $this->load->model('poi_image_model');
    $file_ids = $this->poi_image_model->get_files($id);
    foreach ($file_ids as $key => $file_info) {
      S3::deleteObject($this->config->item('bucketName'),'media/discover/'.$file_info->filename);
    }
  }
  $d->published = -2;
  $success = $d->save();
  $this->load->model('activitylog_model');
  $this->activitylog_model->insert_log($userdata['uid'], POI_DELETE, $id);
}
echo json_encode (array('success' => $success));
}
function delete($id){
 $d = new Poi_Model();
 if($id <>'') {
   $d->where('id', $id )->get();
   $country = $d->country;
   $d->delete();

 }
 redirect('poi/index/'.$country, 'refresh');
}
    /*
     * Mukesh
     * Ajax ml_poi_categories
     * Date:5 Dec 2014
     */

    function ajax_ml_poi_categories_save(){
      $this->load->model('ml_poi_categories_model');
      $id = explode('_',$this->input->post('id'));
      $value = $this->input->post('title');
      $entity_id = $id[1];
      $lang_code = $id[0];
      $success = $this->ml_poi_categories_model->update_ml_poi_categories($entity_id,$lang_code,$value);
      echo json_encode(array('success' => $success));
    }
    /*
	function name:default_image 
	purpose: Set default_image 1 for discover_poi_images table
	Author: Mukesh
	Created: 15 dec 2014
    */
  function default_image($id){
    $userdata = $this->session->userdata('logged_in');
    $data['content']= 'poi/view';
    $poi_id = $this->uri->segment(3);
    $image_id = $this->uri->segment(4);        
    $this->load->model('poi_image_model');
    if($this->poi_image_model->update_default_pic($poi_id,$image_id)){
      redirect('poi/view/'.$poi_id, 'refresh');        
    }
  }

  public function addedByUser(){
    $this->load->library('pagination');
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['title']= 'Added By User Poi';
    $data['content']= 'poi/addedByUserList';
    $r = new Poi_Model();
    $total = $r->where('published',-1)->count();
        //$r->where('published',-1)->get(500);
    $config['uri_segment'] = 3;
    $config['per_page'] = 500;
    $config["num_links"] = 14;
    $config['base_url'] = 'poi/addedByUser';
    $config['total_rows'] = $total;
    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
    $r->where('published',-1)->order_by('id')->get($config['per_page'], $page);
    $data['pois']= $r;
    $this->pagination->initialize($config);
    $data['links'] = $this->pagination->create_links();
    $data['jsIncludes'] = array('poi.js');
    $this->load->view('template', $data);
  }

    function ajax_accept($id){ //echo $id;die;
      $userdata = $this->session->userdata('logged_in');
      $d = new Poi_Model();
      $success = FALSE;
      if($id <>'') {
        $success=$d->where('id', $id )->update('published','1');
      }
      echo json_encode (array('success' => $success));
    }

  }
