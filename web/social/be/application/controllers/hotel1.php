<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hotel1 extends CI_Controller {
public function index($cc = 'all', $start = 0){
    $cc = urldecode($cc);
    if($this->session->userdata('logged_in')){
    $m = new MongoClient();
    $this->db = $m->tt;
   
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'Manage hotels';
   $data['content']= 'hotel/list_1';
   if($cc=='all') $r = $this->db->discover_hotels->find();
   else $r = $this->db->discover_hotels->find(array("cityName" => $cc)); 
   $data['hotels']= $r;

   $ct = $this->db->command(array("distinct" => "discover_hotels", "key" => "cityName"));
   $data['cities'] = $ct['values'];
   
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
        $config['allowed_types'] = 'jpeg|jpg|png';
        $config['max_size'] = 1024 * 8;
        $config['encrypt_name'] = TRUE;
 
        $this->load->library('upload', $config);
        $this->load->model('hotel_image_model');
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
            $id_post =$_POST['id'];
//               $file_id = $this->hotel_image_model->insert_file($data['file_name'], $_POST['id']);
               $file_id = $this->hotel_image_model->insert_file($data['file_name'],$id_post);
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
public function files($id)
{
$this->load->model('hotel_image_model');
$files = $this->hotel_image_model->get_files($id);
$this->load->view('hotel/files', array('files' => $files));
}

 public function view($id){
if($this->session->userdata('logged_in')){
    $m = new MongoClient();
    $this->db = $m->tt;
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'View hotel';
   $data['content']= 'hotel/view_1';
   $h = $this->db->discover_hotels->findOne(array("id" => $id));
   $data['hotel']= $h;
     
   $this->load->view('template', $data);
  }
  else redirect('login', 'refresh');
 }
 
 public function edit($id){
  if($this->session->userdata('logged_in')){
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'Edit hotel';
   $data['content']= 'hotel/form';
   $h = new Hotel_Model();
   $h->where('id', $id)->get();
   $data['hotel']= $h;
   $this->load->view('template', $data);
  }
  else redirect('login', 'refresh');
 }
 
 public function room_edit($id){
  if($this->session->userdata('logged_in')){
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'Edit room';
   $data['content']= 'hotel/form_room';
   $r = new Room_Model();
   $r->where('id', $id)->get();
   $r->hotel->get();
   $data['hotel_id']= $r->hotel->id;
   $data['room']= $r;
   $this->load->view('template', $data);
  }
  else redirect('login', 'refresh');
 }
  public function review_edit($id){
  if($this->session->userdata('logged_in')){
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'Edit review';
   $data['content']= 'hotel/form_review';
   $r = new Review_Model();
   $r->where('id', $id)->get();
   $r->hotel->get();
   $data['hotel_id']= $r->hotel->id;
   $data['review']= $r;
   $this->load->view('template', $data);
  }
  else redirect('login', 'refresh');
 }
  public function add(){
  if($this->session->userdata('logged_in')){
   $session_data = $this->session->userdata('logged_in');
   $data['username'] = $session_data['username'];
   $data['title']= 'Add hotel';
   $data['content']= 'hotel/form';
   //$h = new Hotel_Model();
   //$h->where('id', $id)->get();
   //$data['hotel']= $h;
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
 public function review_add($id){
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
public function submit(){
  if($this->session->userdata('logged_in')){
  $id = $this->input->post('id');
  $h = new Hotel_Model();
  if($id <> '' )$h->where('id', $id)->get();
  $h->hotelName = $this->input->post('hotelName');
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
  $h->about = $this->input->post('about');
  $h->description = $this->input->post('description');
  $h->general_facilities = $this->input->post('general_facilities');
  $h->services = $this->input->post('services');
  $f = new Facility_Model();
  $f->where('id IN ('.str_replace('|',',',$h->facilities).'999999999)')->get();
  
  $h->save(array('facility' => $f->all));
  redirect('hotel/view/'.$h->id, 'refresh');

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
   $data['content']= 'hotel/facilities';
   $this->load->view('template', $data);
  }
  else redirect('login', 'refresh');
 }
 
 
 
 function delete_file($file_id){
              $this->load->model('hotel_image_model');
  if ($this->hotel_image_model->delete_file($file_id))
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
 
  function room_submit(){
  if($this->session->userdata('logged_in')){
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
   
   $config['upload_path'] = './uploads/rooms/';
   $config['allowed_types'] = 'jpg|jpeg|png';
   $config['max_size'] = 1024 * 8;
   $config['encrypt_name'] = TRUE;
   $this->load->library('upload', $config);
   if($this->upload->do_upload('pic1')) $r1 = $this->upload->data(); else $r1='';
   if($this->upload->do_upload('pic2')) $r2 = $this->upload->data(); else $r2='';
   if($this->upload->do_upload('pic3')) $r3 = $this->upload->data(); else $r3='';
   if($r1<>'') $d->pic1 = $r1['file_name']; 
   if($r2<>'') $d->pic2 = $r2['file_name'];
   if($r3<>'') $d->pic3 = $r3['file_name'];
   $d->save();
   redirect('hotel/view/'.$this->input->post('hotel_id'), 'refresh');
  }
  else redirect('login', 'refresh');
 }
 
 
 function review_submit(){
  if($this->session->userdata('logged_in')){
   $r = new Review_Model();
   $id = $this->input->post('id');
   if($id <>'') $r->where('id', $id )->get();
   $r->hotel_id=$this->input->post('hotel_id');
   $r->title=$this->input->post('title');
   $r->description=$this->input->post('description');
   $r->save();
   redirect('hotel/view/'.$this->input->post('hotel_id'), 'refresh');
  }
  else redirect('login', 'refresh');
 }
 
   function room_delete($id){
  if($this->session->userdata('logged_in')){
   $d = new Room_Model();
   if($id <>'') $d->where('id', $id )->get();
   $hotel_id = $d->hotel_id;
   $d->delete();
   redirect('hotel/view/'.$hotel_id, 'refresh');
  }
  else redirect('login', 'refresh');
 }
 
    function review_delete($id){
  if($this->session->userdata('logged_in')){
   $d = new Review_Model();
   if($id <>'') $d->where('id', $id )->get();
   $hotel_id = $d->hotel_id;
   $d->delete();
   redirect('hotel/view/'.$hotel_id, 'refresh');
  }
  else redirect('login', 'refresh');
 }
 
 function facility_new(){
  if($this->session->userdata('logged_in')){
  $title = $this->input->post('title');
   $f = new Facility_Model();
   $f->title=$title;
   $f->save();
   redirect('hotel/facilities', 'refresh');
  }
  else redirect('login', 'refresh');
 }
 
 function fsearch(){
    $str = $_GET['term'];
    $facilities = new Facility_Model();
    $facilities->where('title like', "%$str%" )->get();
    $i=0;
    foreach($facilities as $f) {$arr[$i]= array('id'=>$f->id, 'value'=>$f->title, 'label'=> $f->title); $i++;}
    $this->output->set_content_type('application/json') ->set_output(json_encode($arr));
 }
 
function delete($id){
  if($this->session->userdata('logged_in')){
   $d = new Hotel_Model();
   if($id <>'') {
       $d->where('id', $id )->get();
       $city = $d->cityName;
       $d->delete();

   }
   redirect('hotel/index/'.$city, 'refresh');
  }
  else redirect('login', 'refresh');
 }
 
}
