<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Poi extends CI_Controller {
public function index($cc = 'all', $start = 0){

    if($this->session->userdata('logged_in')){
   $this->load->library('pagination');

   
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'Manage poi';
   $data['content']= 'poi/list';
   $r = new Poi_Model();
   $config['uri_segment'] = 4;
   $config['per_page'] = 20000;
   $config["num_links"] = 14;
   if($cc=='all') {
    $config['total_rows'] = $r->count();  
    $config['base_url'] = 'poi/index/all'; 
    $r->order_by('country')->get(20000,$start);  
   }else{
    $config['total_rows'] = $r->where('country', $cc)->count(); 
    $config['base_url'] = 'poi/index/'.$cc;
    $r->where('country',$cc)->order_by('id')->get(20000,$start);  
   }
   $data['pois']= $r;
   $c = new Poi_Model();
   $c->select('country, count(*) as num')->group_by('country')->order_by('country')->get(); 
   $data['countries']= $c;
   
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
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = 1024 * 8;
        $config['encrypt_name'] = TRUE;
 
        $this->load->library('upload', $config);
        $this->load->model('poi_image_model');
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
               $thumb_conf['width'] = 200;
               $thumb_conf['height'] = 200;
               $thumb_conf['source_image'] = "./uploads/" . $data['file_name'];
               $thumb_conf['new_image'] = "./uploads/thumb/";
               $this->image_lib->initialize($thumb_conf);
               
               if(!$this->image_lib->resize()) $msg =  $this->image_lib->display_errors();

               $file_id = $this->poi_image_model->insert_file($data['file_name'], $_POST['id']);
            if($file_id)
            {
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
    echo json_encode(array('status' => $status, 'msg' => $msg));
}

public function files($id){
$this->load->model('poi_image_model');
$files = $this->poi_image_model->get_files($id);
$this->load->view('poi/files', array('files' => $files));
}

public function view($id){
if($this->session->userdata('logged_in')){
   $this->load->model('poi_image_model');
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'View poi';
   $data['content']= 'poi/view';
   $r = new Poi_Model();
   $r->where('id', $id)->get();
   $r->review->get();
   $data['poi']= $r;
     
    $data['files'] = $this->poi_image_model->get_files($id);

   $this->load->view('template', $data);
  }
  else redirect('login', 'refresh');
 }
 
 public function edit($id){
  if($this->session->userdata('logged_in')){
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'Edit poi';
   $data['content']= 'poi/form';
   $r = new Poi_Model();
   $r->where('id', $id)->get();
   $data['poi']= $r;
   $this->load->view('template', $data);
  }
  else redirect('login', 'refresh');
 }
 
 public function review_edit($id){
  if($this->session->userdata('logged_in')){
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'Edit review';
   $data['content']= 'poi/form_review';
   $r = new Poi_Review_Model();
   $r->where('id', $id)->get();
   $r->poi->get();
   $data['poi_id']= $r->poi->id;
   $data['review']= $r;
   $this->load->view('template', $data);
  }
  else redirect('login', 'refresh');
 }
  public function add(){
  if($this->session->userdata('logged_in')){
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'Add poi';
   $data['content']= 'poi/form';
   $this->load->view('template', $data);
  }
  else redirect('login', 'refresh');
 }
   public function room_add($id){
  if($this->session->userdata('logged_in')){
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'Add room';
   $data['poi_id']= $id;
   $data['content']= 'poi/form_room';
   $this->load->view('template', $data);
  }
  else redirect('login', 'refresh');
 }
 public function review_add($id){
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
public function submit(){
  if($this->session->userdata('logged_in')){
  $id = $this->input->post('id');
  $r = new Poi_Model();
  if($id <> '' )$r->where('id', $id)->get();
  $r->name = $this->input->post('name');
  $r->latitude = $this->input->post('latitude');
  $r->longitude = $this->input->post('longitude');
  $r->country = $this->input->post('country');
  $r->city = $this->input->post('city');
  $r->address = $this->input->post('address');
  $r->about = $this->input->post('about');
  $r->description = $this->input->post('description');
  $r->facilities = $this->input->post('facilities');
  $r->save();
  redirect('poi/view/'.$r->id, 'refresh');

  }
  else redirect('login', 'refresh');
 }
 

 
 function delete_file($file_id){
              $this->load->model('poi_image_model');
  if ($this->poi_image_model->delete_file($file_id))
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


function review_submit(){
  if($this->session->userdata('logged_in')){
   $r = new Poi_Review_Model();
   $id = $this->input->post('id');
   if($id <>'') $r->where('id', $id )->get();
   $r->poi_id=$this->input->post('poi_id');
   $r->title=$this->input->post('title');
   $r->description=$this->input->post('description');
   $r->save();
   redirect('poi/view/'.$this->input->post('poi_id'), 'refresh');
  }
  else redirect('login', 'refresh');
 }
 

function review_delete($id){
  if($this->session->userdata('logged_in')){
   $d = new Poi_Review_Model();
   if($id <>'') $d->where('id', $id )->get();
   $poi_id = $d->poi_id;
   $d->delete();
   redirect('poi/view/'.$poi_id, 'refresh');
  }
  else redirect('login', 'refresh');
 }
  function delete($id){
  if($this->session->userdata('logged_in')){
   $d = new Poi_Model();
   if($id <>'') {
       $d->where('id', $id )->get();
       $country = $d->country;
       $d->delete();

   }
   redirect('poi/index/'.$country, 'refresh');
  }
  else redirect('login', 'refresh');
 }
 
}
