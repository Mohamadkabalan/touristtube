<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Landmark extends CI_Controller {
public function index($cc = 'all', $start = 0){

    if($this->session->userdata('logged_in')){
   $this->load->library('pagination');
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'Manage landmarks';
   $data['content']= 'landmark/list';
   $h = new Landmark_Model();
   $config['uri_segment'] = 4;
   $config['per_page'] = 100;
   $config["num_links"] = 14;
   if($cc=='all') {
    $config['total_rows'] = $h->count();  
    $config['base_url'] = 'landmark/index/all'; 
    $h->limit(200)->get(200,$start);  
   }else{
    $config['total_rows'] = $h->where('city',$cc)->count(); 
    $config['base_url'] = 'landmark/index/'.$cc;
    $h->where('city',$cc)->limit(200)->get(200,$start);  
   }
      
   
   $data['landmarks']= $h;
   $c = new Landmark_Model();
   $c->select('city, count(*) as num')->group_by('city')->order_by('city')->get(); 
   $data['cities']= $c;
   
   $this->pagination->initialize($config);
   $data['links'] = $this->pagination->create_links();
   $data['cc']=$cc;
   $this->load->view('template', $data);
  }
  else redirect('login', 'refresh');
 }
 function ajax_upload(){ 
    $status = "";
    $msg = "";
    $file_element_name = 'userfile';
    if ($status != "error")
    {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 1024 * 8;
        $config['encrypt_name'] = TRUE;
 
        $this->load->library('upload', $config);
        $this->load->model('files_model');
        $return_data = $this->upload->do_multi_upload($file_element_name);
        if (!$return_data)
        {
            $status = 'error';
            $msg = $this->upload->display_errors('', '');
        }
        else
        {
            foreach ( $return_data as $data){
                $id_post = $request->request->get('id', '');
//            $file_id = $this->files_model->insert_file($data['file_name'], $_POST['id']);
            $file_id = $this->files_model->insert_file($data['file_name'], $id_post);
            if($file_id)
            {
                $status = "success";
                $msg = "File successfully uploaded";
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
    echo json_encode(array('status' => $status, 'msg' => $msg));
}
public function files($id)
{
$this->load->model('files_model');
$files = $this->files_model->get_files($id);
$this->load->view('landmark/files', array('files' => $files));
}

 public function view($id){
if($this->session->userdata('logged_in')){
   $this->load->model('files_model');
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'View landmark';
   $data['content']= 'landmark/view';
   $h = new Global_Restaurant_Model();
   $h->where('id', $id)->get();
   $data['landmark']= $h;
     
    $data['files'] = $this->files_model->get_files($id);

   $this->load->view('template', $data);
  }
  else redirect('login', 'refresh');
 }
 
 public function edit($id){
  if($this->session->userdata('logged_in')){
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'Edit landmark';
   $data['content']= 'landmark/form';
   $h = new Global_Restaurant_Model();
   $h->where('id', $id)->get();
   $data['landmark']= $h;
   $this->load->view('template', $data);
  }
  else redirect('login', 'refresh');
 }
public function submit(){
  if($this->session->userdata('logged_in')){
  $id = $this->input->post('id');
  $h = new Global_Restaurant_Model();
  $h->where('id', $id)->get();
  $h->landmarkName = $this->input->post('landmarkName');
  $h->stars = $this->input->post('stars');
  $price = $this->input->post('price');
  $h->cityName = $this->input->post('cityName');
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
  $h->description = $this->input->post('description');
  $h->save();
  redirect('landmark/view/'.$id, 'refresh');

  }
  else redirect('login', 'refresh');
 }
 
 public function facilities(){
  if($this->session->userdata('logged_in')){
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'Manage facilities';
   $h = new Facility_Model();
   $data['facilities']= $h->get();
   $data['content']= 'landmark/facilities';
   $this->load->view('template', $data);
  }
  else redirect('login', 'refresh');
 }
 
 
 
 function delete_file($file_id){
              $this->load->model('files_model');
  if ($this->files_model->delete_file($file_id))
    {
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

 function facility_save(){
  if($this->session->userdata('logged_in')){
  $id = explode('_',$this->input->post('id'));
  $value = $this->input->post('value');
   $f = new Facility_Model();
   $f->where('id', $id[1])->get();
   $f->title=$value;
   $f->save();
   echo $value;
  }
  else redirect('login', 'refresh');
 }
 
 function facility_new(){
  if($this->session->userdata('logged_in')){
  $title = $this->input->post('title');
   $f = new Facility_Model();
   $f->title=$title;
   $f->save();
   redirect('landmark/facilities', 'refresh');
  }
  else redirect('login', 'refresh');
 }
}
