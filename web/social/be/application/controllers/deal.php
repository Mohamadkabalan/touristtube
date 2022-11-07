<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Deal extends MY_Controller {

    private $deal_categories = array(
        "Family", "SPA", "Sightseeing", "Guided Tours", "Bus Tours", "Air Tours", "Adventure Tours", "Culinary Tours",
        "Cruises", "Music and Concert", "Opera", "Theatre", "Fashion Show", "Sports", "Forests", "Mountains", "Natural Landmarks", "Wildlife",
        "Honeymoon", "Beach and Resorts", "Air Sports", "Bungee Jumping", "Camping", "Caving", "Climbing", "Diving", "Fishing", "Hiking", "Skiing",
        "Skydiving", "Games", "Golf", "Events", "Theme Parks", "Nighlife", "Festival", "Museums", "Casino", "Educational", "Safari", "Group Travel"
    );
    public function index(){
        $this->load->library('pagination');
        $session_data = $this->session->userdata('logged_in');
        $role = $session_data['role'];
        $data['username'] = $session_data['username'];
        $data['title']= 'List of Deals';
        $data['content']= 'deal/list';
        $h = new Deal_Model();
        //echo '<pre>';print_r($data['countryes']);
        $config['uri_segment'] = 3;
        $config['per_page'] = 100;
        $config["num_links"] = 14;
        $config['base_url'] = 'deal/ajax_list';
        if($role == 'admin'){
            $total = $h->where('published !=', -1)->count();
            $config['total_rows'] = $total;
            $res = $h->where('published !=', -1)->order_by('id')->get(100);
        }
        else{
            $total = $h->where('dealer_id', $session_data['uid'])->where('published !=', -1)->count();
            $config['total_rows'] = $total;
            $res = $h->where('dealer_id', $session_data['uid'])->where('published !=', -1)->order_by('id')->get(100);
        }
        $data['deals']= $h;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['jsIncludes'] = array('deal.js');
        $data['cssIncludes'] = array('deal.css');
        $this->load->view('template', $data);
    }
    
    function ajax_list($start = 0){
       $this->load->library('pagination');
       $session_data = $this->session->userdata('logged_in');
       $dealName = $this->input->post('dn');
       $h = new Deal_Model();
       $config['uri_segment'] = 3;
       $config['per_page'] = 100;
       $config["num_links"] = 14;
       $config['base_url'] = 'deal/ajax_list';
       $total = $h->where('dealer_id', $session_data['uid'])->where('published !=', -1)->like('name', $dealName)->count();
       $config['total_rows'] = $total;
       $res = $h->where('dealer_id', $session_data['uid'])->where('published !=', -1)->like('name', $dealName)->order_by('id')->get(100, $start);
       $data['deals']= $h;
       $this->pagination->initialize($config);
       $data['links'] = $this->pagination->create_links();
       $this->load->view('deal/ajax_list', $data);
    }
    
    function ajax_delete($id){
        $d = new Deal_Model();
        $success = FALSE;
        if($id <>'') {
            $d->where('id', $id )->get();
            $d->published = -1;
            $d->save();
            $success = TRUE;
//            $success = $d->delete();
//            $destinations = new Deal_Destination_Model();
//            $destinations->where('deal_id', $id)->get();
//            $destinations->delete();
            
//            $this->load->model('activitylog_model');
//            $this->activitylog_model->insert_log($userdata['uid'], HOTEL_DELETE, $id);
        }
        echo json_encode (array('success' => $success));
    }
    
    private function secureDeal($deal){
        $session_data = $this->session->userdata('logged_in');
        $role = $session_data['role'];
        if($role == 'admin'){
            return true;
        }
        if(intval($deal->dealer_id) != intval($session_data['uid'])){
            redirect('dashboard', 'refresh');
            return false;
        }
        return true;
    }
    
    public function edit($id){
        $session_data = $this->session->userdata('logged_in');
        $this->load->model('user_model');
        $data['username'] = $session_data['username'];
        $data['role'] = $session_data['is_admin'];
        $data['title']= 'Edit main info';
        $data['content']= 'deal/form';
        $h = new Deal_Model();
        $h->where('id', $id)->get();
        if(!$this->secureDeal($h)){
            exit;
        }
        $data['deal']= $h->to_array();
        $data['categories'] = $this->deal_categories;
        $data['dealers'] = $this->user_model->get_dealers();
        $data['cssIncludes'] = array('deal_view_edit.css');
        $data['jsIncludes'] = array('deal_form.js');
        $this->load->view('template', $data);
    }
 
    public function add(){
        $session_data = $this->session->userdata('logged_in');
        $this->load->model('user_model');
        $data['username'] = $session_data['username'];
        $data['role'] = $session_data['is_admin'];
        $data['title']= 'Add deal';
        $data['content']= 'deal/form';
        $data['categories'] = $this->deal_categories;
        $data['dealers'] = $this->user_model->get_dealers();
        $data['cssIncludes'] = array('deal_view_edit.css');
        $data['jsIncludes'] = array('deal_form.js');
        $this->load->view('template', $data);
    }
    
    public function view($id){ 
       $session_data = $this->session->userdata('logged_in');
       $data['username'] = $session_data['username'];
       $this->load->model('cms_countries_model');
       $deal = new Deal_Model();
       $deal->where('id', $id)->get();
       if(!$this->secureDeal($deal)){
            exit;
        }
       $data['deal']= $deal->to_array();
       
       $detail = new Deal_Detail_Model();
       $detail->where('deal_id', $deal->id)->get();
       $data['details'] = $detail->all_to_array();
       
       $destinations = new Deal_Destination_Model();
       $destinations->where('deal_id', $deal->id)->get();
       $data['destinations'] = $destinations->all_to_array();
       $i = 0;
       foreach($data['destinations'] as $val){
        $ret = $this->cms_countries_model->getbycode($val['country_code']);
         if($ret){
             $data['destinations'][$i]['country_name'] = $ret['text'];
         }else{
            $data['destinations'][$i]['country_name'] = "";
         }
         $i++;
       }
      
       $options = new Deal_Option_Model();
       $options->where('deal_id', $deal->id)->get();
       $data['options'] = $options->all_to_array();
       
       $images = new Deal_Image_Model();
       $images->where('deal_id', $deal->id)->order_by('image_order')->get();
       $data['files'] = $images->all_to_array();
   
       $data['title']= 'Deal view';
       $data['content']= 'deal/view';
       $data['jsIncludes'] = array('deal_view.js');
       $data['cssIncludes'] = array('deal_view.css');
       $this->load->view('template', $data);
   }
    
    public function submit(){
        $userdata = $this->session->userdata('logged_in');
        $this->load->model('user_model');
        $data['role'] = $userdata['is_admin'];
        $data['dealers'] = $this->user_model->get_dealers();
//        print_r($userdata);exit();
          $id = $this->input->post('id');
          $this->form_validation->set_error_delimiters('<em>&nbsp;</em><span> &nbsp;</span><p class="errormsg">', '</p>');
        //if(!empty($_POST)) {
           
            $this->form_validation->set_rules('title', 'title', 'trim|required|xss_clean|callback_alpha_numeric_space');
            $this->form_validation->set_rules('subtitle', 'subtitle', 'trim|required|xss_clean|callback_alpha_numeric_space');
            $this->form_validation->set_rules('tourFromDate', 'tour start date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('tourToDate', 'tour end date', 'trim|required|xss_clean|callback_compareDate');
            $this->form_validation->set_rules('minDays', 'minimum days', 'trim|xss_clean|integer');
            $this->form_validation->set_rules('maxDays', 'maximum days', 'trim|required|xss_clean|integer');
            $this->form_validation->set_rules('category', 'category', 'trim|required|xss_clean');
//            if($data['role'] == ''){
//                $this->form_validation->set_rules('dealer', 'dealer', 'trim|required|xss_clean');
//            }
//            $this->form_validation->set_rules('country_code', 'targeted population', 'trim|required|xss_clean');
            $this->form_validation->set_rules('summaryTitle', 'deal title', 'trim|xss_clean');
            $this->form_validation->set_rules('summary', 'deal description', 'trim|xss_clean');
            $this->form_validation->set_rules('hotelSummaryTitle', 'hotel title', 'trim|xss_clean');
            $this->form_validation->set_rules('hotelSummary', 'hotel description', 'trim|xss_clean');
            $this->form_validation->set_rules('termsConditions', 'term and condition', 'trim|xss_clean');
            $this->form_validation->set_rules('highlights', 'highlights', 'trim|xss_clean');
            $this->form_validation->set_rules('dealIncludes', 'deal include', 'trim|xss_clean');
            $this->form_validation->set_rules('dealNotIncludes', 'deal not include', 'trim|xss_clean');
            
            
            if ($this->form_validation->run() == TRUE) {
                $h = new Deal_Model();
                if($id <> '' ){
                    $h->where('id', $id)->get();
                    if(!$this->secureDeal($h)){
                    exit;
                }
                }
                $h->name = $this->input->post('title');
                $h->subtitle = $this->input->post('subtitle');

                if($this->input->post('tourFromDate') == ''){
                    $h->tour_from_date = date('Y-m-d');
                }
                else{
                    $tourFromDate = date('Y-m-d', strtotime($this->input->post('tourFromDate')));
                    $h->tour_from_date = $tourFromDate;
                }

                if($this->input->post('tourToDate') == ''){
                    $h->tour_to_date = date('Y-m-d');
                }
                else{
                    $tourToDate = date('Y-m-d', strtotime($this->input->post('tourToDate')));
                    $h->tour_to_date = $tourToDate;
                }
                $h->highlights = $this->input->post('highlights');
                $h->summary_title = $this->input->post('summaryTitle');
                $h->summary = $this->input->post('summary');
                $h->hotel_summary_title = $this->input->post('hotelSummaryTitle');
                $h->hotel_summary = $this->input->post('hotelSummary');
                $h->terms_conditions = $this->input->post('termsConditions');
                $h->deal_includes = $this->input->post('dealIncludes');
                $h->deal_not_include = $this->input->post('dealNotInclude');
                $h->category = $this->input->post('category');
                $h->min_days = intval($this->input->post('minDays'));
                $h->max_days = intval($this->input->post('maxDays'));
                $h->target_population = $this->input->post('country_code');
                if($data['role'] == 1){
                    $h->dealer_id = $this->input->post('dealer');
                }else{
                   if($id == '' ){
                         $h->dealer_id = $userdata['uid'];
                    } 
                }
                
                $h->save();
                //  $this->load->model('activitylog_model');
                //  $activity_code = $id == '' ? HOTEL_INSERT : HOTEL_UPDATE;
                //  $this->activitylog_model->insert_log($userdata['uid'], $activity_code, $h->id);
                redirect('deal/view/'.$h->id, 'refresh');
            }else{
                
                if($id <> ''){
                    $data['title']= 'Edit main info';
                    $h = new Deal_Model();
                    $h->where('id', $id)->get();
                    if(!$this->secureDeal($h)){
                        exit;
                    }
                    $data['deal']= $h->to_array();
                }else{
                   $data['title']= 'Add deal'; 
                }
                $data['content']= 'deal/form';
                $data['categories'] = $this->deal_categories;
                $data['cssIncludes'] = array('deal_view_edit.css');
                $data['jsIncludes'] = array('deal_form.js');
                $this->load->view('template', $data);
            }
      //  }  
    }
    
    public function files($id)
    {
        $deal_images = new Deal_Image_Model();
        $deal_images->where('deal_id', $id)->get();
        $files_array = $deal_images->all_to_array();
        $this->load->view('deal/files', array('files' => $files_array));
    }
    
    public function delete_image($file_id, $deal_id){
        $deal_image = new Deal_Image_Model();
        $status = 'error';
        if($file_id <>'') {
            $deal_image->where('id', $file_id )->get();
            $res = $deal_image->delete();
            if($res){
                $status = 'success';
            }
        }
        echo json_encode(array('status' => $status));
    }
    
    function ajax_upload(){   
        $userdata = $this->session->userdata('logged_in');
        $status = "";
        $msg = "";
        $file_element_name = 'userfile';
        $deal_id = $this->input->post('id');
        $this->load->helper('discover_title_helper');
        $type= 'deal';
        if ($status != "error")
        { 
            $config['upload_path'] = '../media/deals';
            $config['allowed_types'] = 'gif|jpeg|jpg|png';
            $config['max_size'] = 1024 * 8;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            $return_data = $this->upload->do_multi_upload($file_element_name);
//            print_r($return_data);exit;
//            echo $this->upload->display_errors('', '');
//            print_r($this->upload->display_errors('', ''));exit;
            if (!$return_data)
            {
                $status = 'error';
                $msg = $this->upload->display_errors();
            }
            else
            { 
                //Get image order
                $max_order = $this->getImageOrder($deal_id);
                $image_order = $max_order + 1;
                
                foreach ( $return_data as $data){
                    $original_filename = $data['file_name'];
                    $extension = explode(".",$data['file_name']);
                    $new_name = microtime();
                    $new_name = str_replace('0.', '', $new_name);
                    $new_name = str_replace(' ', '', $new_name);
                    $new_full_name = $type.'_'.$new_name. '.' .end($extension);
                    rename('../media/deals/'.$original_filename, '../media/deals/'.$new_full_name);
//                    $thumb_conf['image_library'] = 'GD2';
//                    $thumb_conf['maintain_ratio'] = TRUE;
//                    $thumb_conf['quality'] = '100%';
//                   /* change file name */
//                   $new_full_name = $type.'_'.$new_name. '.' .end($extension);

                   /* Thumb Size Image  */
//                   $thumb_conf['width'] = 175;
//                   $thumb_conf['source_image'] = "../media/deals/" . $new_name.'.'.end($extension);
//                   $thumb_conf['new_image'] = "../media/deals/".$new_full_name;
//                   $this->image_lib->initialize($thumb_conf);
//                   if(!$this->image_lib->resize()) $msg =  $this->image_lib->display_errors();
//                  $file_id = $this->hotel_image_model->insert_file($new_full_name, $hotel_id);
                   $deal_image = new Deal_Image_Model();
                   $deal_image->deal_id = $deal_id;
                   $deal_image->path = $new_full_name;
                   $deal_image->image_order = $image_order;
                   $deal_image->save();
                   $file_id = $deal_image->id;
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
    
    public function option_view($id){
       $session_data = $this->session->userdata('logged_in');
       $data['username'] = $session_data['username'];
       $option = new Deal_Option_Model();
       $option->where('id', $id)->get();
       $data['option']= $option->to_array();
       
       $data['title']= 'Hotel detailed Info';
       $data['content']= 'deal/hotel_view';
       $data['cssIncludes'] = array('deal_hotel.css');
       $this->load->view('template', $data);
    }
    
    public function option_edit($id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $option = new Deal_Option_Model();
        $option->where('id', $id)->get();
        $data['option'] = $option->to_array();
        $data['title']= 'edit deal option';
        $data['content']= 'deal/option_form';
        $data['cssIncludes'] = array('deal_hotel_room.css');
        $data['jsIncludes'] = array('room_form.js');
        $this->load->view('template', $data);
    }
    
    public function option_add($deal_id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['deal_id']  = $deal_id;
        $data['title']= 'add deal option';
        $data['content']= 'deal/option_form';
        $data['cssIncludes'] = array('deal_hotel_room.css');
        $data['jsIncludes'] = array('room_form.js');
        $this->load->view('template', $data);
    }
    
    public function option_submit(){
        $userdata = $this->session->userdata('logged_in');
         $this->form_validation->set_error_delimiters('<em>&nbsp;</em><span> &nbsp;</span><p class="errormsg">', '</p>');
        //if(!empty($_POST)) {
            $id = $this->input->post('id');
            $deal_id= $this->input->post('deal_id');
            $this->form_validation->set_rules('title', 'title', 'trim|required|xss_clean|callback_alpha_numeric_space');
            $this->form_validation->set_rules('description', 'description', 'trim|required|xss_clean');
            $this->form_validation->set_rules('currency', 'currency', 'trim|required|xss_clean');
            $this->form_validation->set_rules('price', 'price', 'trim|required|xss_clean|decimal');
            $this->form_validation->set_rules('availability', 'availability', 'trim|xss_clean|integer');
            $this->form_validation->set_rules('nb_persons', 'nb persons', 'trim|required|xss_clean|integer');
            
            if ($this->form_validation->run() == TRUE) {
                $option = new Deal_Option_Model();
                if($id <> '' ){
                    $option->where('id', $id)->get();
                }
                $option->title = $this->input->post('title');
                $option->description = $this->input->post('description');
                $option->currency_id = intval($this->input->post('currency'));
                $option->price = $this->input->post('price');
                $option->availability = intval($this->input->post('availability'));
                $option->nb_persons = $this->input->post('nb_persons');
                $option->deal_id = intval($this->input->post('deal_id'));
                $option->save();
                redirect('deal/view/'.$option->deal_id, 'refresh');
            }else{
                if($id <> '' ){
                    $option = new Deal_Option_Model();
                    $option->where('id', $id)->get();
                    $data['option'] = $option->to_array();
                    $data['title']= 'edit deal option';
                }else{
                    $data['title']= 'add deal option';  
                }
                
                $data['deal_id']  = $deal_id;
                $data['content']= 'deal/option_form';
                $data['cssIncludes'] = array('deal_hotel_room.css');
                $data['jsIncludes'] = array('room_form.js');
                $this->load->view('template', $data);
            }
        //}
    }
    
    public function ajax_delete_option($id){
        $option = new Deal_Option_Model();
        $success = FALSE;
        if($id <> ''){
            $option->where('id', $id)->get();
            $success = $option->delete();
        }
        echo json_encode (array('success' => $success));
    }
    
    public function hotel_view($id){
       $session_data = $this->session->userdata('logged_in');
       $data['username'] = $session_data['username'];
       $h = new Deal_Hotel_Model();
       $h->where('id', $id)->get();
       $data['hotel']= $h->to_array();
       
       $r = new Deal_Hotel_Room_Model();
       $r->where('hotel_id', $h->id)->get();
       $data['rooms'] = $r->all_to_array();
       
       $data['title']= 'Hotel detailed Info';
       $data['content']= 'deal/hotel_view';
       $data['cssIncludes'] = array('deal_hotel.css');
       $this->load->view('template', $data);
    }
    
    public function hotel_edit($id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $d = new Deal_Hotel_Model();
        $d->where('id', $id)->get();
        $date = new Deal_Date_Model();
        $date->where('id', $d->date_id)->get();
        $data['date'] = $date->to_array();
        $destination = new Deal_Destination_Model();
        $destination->where('id', $d->destination_id)->get();
        $data['destination'] = $destination->to_array();
        $data['hotel'] = $d->to_array();
        $data['title']= 'edit hotel';
        $data['content']= 'deal/hotel_form';
        $data['cssIncludes'] = array('hotel_edit.css');
//        $data['jsIncludes'] = array('optional_tour_form.js');
        $this->load->view('template', $data);
    }
    
    public function hotel_add($date_id, $destination_id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['date_id']  = $date_id;
        $data['destination_id'] = $destination_id;
        $date = new Deal_Date_Model();
        $date->where('id', $date_id)->get();
        $data['date'] = $date->to_array();
        $destination = new Deal_Destination_Model();
        $destination->where('id', $destination_id)->get();
        $data['destination'] = $destination->to_array();
        $data['title']= 'add hotel';
        $data['content']= 'deal/hotel_form';
        $data['cssIncludes'] = array('hotel_edit.css');
//        $data['jsIncludes'] = array('optional_tour_form.js');
        $this->load->view('template', $data);
    }
    
    public function hotel_submit(){
        $userdata = $this->session->userdata('logged_in');
        $id = $this->input->post('id');
        $d = new Deal_Hotel_Model();
        if($id <> '' ){
            $d->where('id', $id)->get();
        }
        $d->name = $this->input->post('name');
        $d->stars = floatval($this->input->post('stars'));
        $d->date_id = intval($this->input->post('date_id'));
        $d->destination_id = intval($this->input->post('destination_id'));
        $d->save();
        redirect('deal/hotel_view/'.$d->id, 'refresh');
    }
    
    public function ajax_delete_hotel($id){
        $userdata = $this->session->userdata('logged_in');
        $d = new Deal_Hotel_Model();
        $success = FALSE;
        if($id <>'') {
            $d->where('id', $id )->get();
            $success = $d->delete();
            $r = new Deal_Hotel_Room_Model();
            $r->where('hotel_id', $id);
            $r->delete();
//            $success = $d->delete();
//            $destinations = new Deal_Destination_Model();
//            $destinations->where('deal_id', $id)->get();
//            $destinations->delete();
            
//            $this->load->model('activitylog_model');
//            $this->activitylog_model->insert_log($userdata['uid'], HOTEL_DELETE, $id);
        }
        echo json_encode (array('success' => $success));
    }
    
    public function room_edit($id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $d = new Deal_Hotel_Room_Model();
        $d->where('id', $id)->get();
        $hotel = new Deal_Hotel_Model();
        $hotel->where('id', $d->hotel_id)->get();
        $data['hotel'] = $hotel->to_array();
        $date = new Deal_Date_Model();
        $date->where('id', $hotel->date_id)->get();
        $data['date'] = $date->to_array();
        $destination = new Deal_Destination_Model();
        $destination->where('id', $hotel->destination_id)->get();
        $data['destination'] = $destination->to_array();
        $data['room'] = $d->to_array();
        $data['title']= 'Edit room';
        $data['content']= 'deal/room_form';
        $data['cssIncludes'] = array('deal_hotel_room.css');
        $data['jsIncludes'] = array('room_form.js');
        $this->load->view('template', $data);
    }
    
    public function room_add($hotel_id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $hotel = new Deal_Hotel_Model();
        $hotel->where('id', $hotel_id)->get();
        $data['hotel'] = $hotel->to_array();
        $data['hotel_id'] = $hotel->id;
//        $data['date_id']  = $date_id;
//        $data['destination_id']  = $destination_id;
        $date = new Deal_Date_Model();
        $date->where('id', $hotel->date_id)->get();
        $data['date'] = $date->to_array();
        $destination = new Deal_Destination_Model();
        $destination->where('id', $hotel->destination_id)->get();
        $data['destination'] = $destination->to_array();
        $data['title']= 'Add room';
        $data['content']= 'deal/room_form';
        $data['cssIncludes'] = array('deal_hotel_room.css');
        $data['jsIncludes'] = array('room_form.js');
        $this->load->view('template', $data);
    }
    
    public function room_submit(){
        $userdata = $this->session->userdata('logged_in');
        $id = $this->input->post('id');
        $d = new Deal_Hotel_Room_Model();
        if($id <> '' ){
            $d->where('id', $id)->get();
        }
        $d->name = $this->input->post('name');
        $d->price = floatval($this->input->post('price'));
        $d->currency_id = intval($this->input->post('currency'));
        $d->seats_left = intval($this->input->post('seats_left'));
        $d->n_persons = intval($this->input->post('nPersons'));
        $d->discount = intval($this->input->post('discount'));
        $d->hotel_id = intval($this->input->post('hotel_id'));
        $d->save();
        redirect('deal/hotel_view/'.$d->hotel_id, 'refresh');
    }
    
    public function ajax_delete_room($id){
        $userdata = $this->session->userdata('logged_in');
        $d = new Deal_Hotel_Room_Model();
        $success = FALSE;
        if($id <>'') {
            $d->where('id', $id )->get();
            $success = $d->delete();
            
//            $this->load->model('activitylog_model');
//            $this->activitylog_model->insert_log($userdata['uid'], HOTEL_DELETE, $id);
        }
        echo json_encode (array('success' => $success));
    }
    
    public function optional_tour_edit($id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $d = new Deal_Optional_Tour_Model();
        $d->where('id', $id)->get();
        $date = new Deal_Date_Model();
        $date->where('id', $d->date_id)->get();
        $data['date'] = $date->to_array();
        $destination = new Deal_Destination_Model();
        $destination->where('id', $d->destination_id)->get();
        $data['destination'] = $destination->to_array();
        $data['optional_tour'] = $d->to_array();
        $data['title']= 'Edit optional tour';
        $data['content']= 'deal/optional_tour_form';
        $data['cssIncludes'] = array('optional_tour_edit.css');
        $data['jsIncludes'] = array('optional_tour_form.js');
        $this->load->view('template', $data);
    }
    
    public function optional_tour_add($date_id, $destination_id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['date_id']  = $date_id;
        $data['destination_id']  = $destination_id;
        $date = new Deal_Date_Model();
        $date->where('id', $date_id)->get();
        $data['date'] = $date->to_array();
        $destination = new Deal_Destination_Model();
        $destination->where('id', $destination_id)->get();
        $data['destination'] = $destination->to_array();
        $data['title']= 'Add date';
        $data['content']= 'deal/optional_tour_form';
        $data['cssIncludes'] = array('optional_tour_edit.css');
        $data['jsIncludes'] = array('optional_tour_form.js');
        $this->load->view('template', $data);
    }
    
    public function optional_tour_submit(){
        $userdata = $this->session->userdata('logged_in');
        $id = $this->input->post('id');
        $d = new Deal_Optional_Tour_Model();
        if($id <> '' ){
            $d->where('id', $id)->get();
        }
        $d->name = $this->input->post('name');
        $d->price = floatval($this->input->post('price'));
        $d->currency_id = intval($this->input->post('currency'));
        $d->seats_left = intval($this->input->post('seats_left'));
        $d->n_persons = intval($this->input->post('nPersons'));
        $d->discount = intval($this->input->post('discount'));
        $d->date_id = intval($this->input->post('date_id'));
        $d->destination_id = intval($this->input->post('destination_id'));
        if($this->input->post('date') == ''){
            $d->tour_date = date('Y-m-d H:i');
        }
        else{
            $fromDate = date('Y-m-d H:i', strtotime($this->input->post('date')));
            $d->tour_date = $fromDate;
        }
        $d->save();
        redirect('deal/date_view/'.$d->date_id, 'refresh');
    }
    
    public function ajax_delete_optional_tour($id){
        $userdata = $this->session->userdata('logged_in');
        $d = new Deal_Optional_Tour_Model();
        $success = FALSE;
        if($id <>'') {
            $d->where('id', $id )->get();
            $success = $d->delete();
            
//            $this->load->model('activitylog_model');
//            $this->activitylog_model->insert_log($userdata['uid'], HOTEL_DELETE, $id);
        }
        echo json_encode (array('success' => $success));
    }
    
    public function date_view($id){
       $session_data = $this->session->userdata('logged_in');
       $data['username'] = $session_data['username'];
       $date = new Deal_Date_Model();
       $date->where('id', $id)->get();
       $data['date']= $date->to_array();
       
       $dm = new Deal_Destination_Model();
       $dm->where('deal_id', $date->deal_id)->get();
       $destinations = array();
       $destinations_array = $dm->all_to_array();
       foreach($destinations_array as $item){
           $destination = array();
           $destination['destination'] = array(
               'id'=>$item['id'],
               'name'=>$item['name']
           );
           $hm = new Deal_Hotel_Model();
           $hm->where('destination_id', $item['id'])->where('date_id', $date->id)->get();
           $destination['hotels'] = $hm->all_to_array();
           $otm = new Deal_Optional_Tour_Model();
           $otm->where('destination_id', $item['id'])->where('date_id', $date->id)->get();
           $destination['optional_tours'] = $otm->all_to_array();
           $destinations[] = $destination;
       }
       $data['destinations'] = $destinations;
       $data['title']= 'Date view';
       $data['content']= 'deal/date_view';
       $data['cssIncludes'] = array('deal_tour_date.css');
       $this->load->view('template', $data);
    }
    
    public function date_edit($id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $d = new Deal_Date_Model();
        $d->where('id', $id)->get();
        $data['date'] = $d->to_array();
        $data['title']= 'Edit date';
        $data['content']= 'deal/date_form';
        $data['cssIncludes'] = array('deal_view_edit.css');
        $data['jsIncludes'] = array('date_form.js');
        $this->load->view('template', $data);
    }
    
    public function date_add($id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['deal_id']  = $id;
        $data['title']= 'Add date';
        $data['content']= 'deal/date_form';
        $data['cssIncludes'] = array('deal_view_edit.css');
        $data['jsIncludes'] = array('date_form.js');
        $this->load->view('template', $data);
    }
    
    public function date_submit(){
        $userdata = $this->session->userdata('logged_in');
        $id = $this->input->post('id');
        $d = new Deal_Date_Model();
        if($id <> '' ){
            $d->where('id', $id)->get();
        }
        if($this->input->post('fromDate') == ''){
            $d->from_date = date('Y-m-d');
        }
        else{
            $fromDate = date('Y-m-d', strtotime($this->input->post('fromDate')));
            $d->from_date = $fromDate;
        }
        
        if($this->input->post('toDate') == ''){
            $d->to_date = date('Y-m-d');
        }
        else{
            $toDate = date('Y-m-d', strtotime($this->input->post('toDate')));
            $d->to_date = $toDate;
        }
        $d->deal_id = $this->input->post('deal_id');
        $d->save();
        redirect('deal/date_view/'.$d->id, 'refresh');
    }
    
    public function day_edit($id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $d = new Deal_Day_Model();
        $d->where('id', $id)->get();
        $data['day'] = $d->to_array();
        $data['title']= 'Edit day';
        $data['content']= 'deal/day_form';
        $data['cssIncludes'] = array('deal_view_day.css');
//        $data['jsIncludes'] = array('deal_form.js');
        $this->load->view('template', $data);
    }
    
    public function day_add($id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['deal_id']  = $id;
        $data['title']= 'Add day';
        $data['content']= 'deal/day_form';
        $data['cssIncludes'] = array('deal_view_day.css');
//        $data['jsIncludes'] = array('deal_form.js');
        $this->load->view('template', $data);
    }
    
    public function day_submit(){
        $userdata = $this->session->userdata('logged_in');
        $id = $this->input->post('id');
        $d = new Deal_Day_Model();
        if($id <> '' ){
            $d->where('id', $id)->get();
        }
        $d->name = $this->input->post('name');
        $d->title = $this->input->post('title');
        $d->description = $this->input->post('description');
        $d->deal_id = $this->input->post('deal_id');
        $d->save();
        redirect('deal/view/'.$d->deal_id, 'refresh');
    }
    
    public function destination_edit($id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $d = new Deal_Destination_Model();
        $d->where('id', $id)->get();
        $data['destination'] = $d->to_array();
        $data['title']= 'Edit destination';
        $data['content']= 'deal/destination_form';
        $data['cssIncludes'] = array('deal_view_edit.css');
        $data['jsIncludes'] = array('destination.js');
        $this->load->view('template', $data);
    }
    
    public function destination_add($id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['deal_id']  = $id;
        $data['title']= 'Add destination';
        $data['content']= 'deal/destination_form';
        $data['cssIncludes'] = array('deal_view_edit.css');
        $data['jsIncludes'] = array('destination.js');
        $this->load->view('template', $data);
    }
    
    public function destination_submit(){
        $userdata = $this->session->userdata('logged_in');
        $this->form_validation->set_error_delimiters('<em>&nbsp;</em><span> &nbsp;</span><p class="errormsg">', '</p>');
       // if(!empty($_POST)) {
            $id = $this->input->post('id');
            $deal_id= $this->input->post('deal_id');
            $this->form_validation->set_rules('name', 'destination', 'trim|required|xss_clean');
            $this->form_validation->set_rules('latitude', 'latitude', 'trim|required|xss_clean');
            $this->form_validation->set_rules('longitude', 'longitude', 'trim|required|xss_clean');
            $this->form_validation->set_rules('country_code', 'country', 'trim|required|xss_clean');
            
            if ($this->form_validation->run() == TRUE) {
                $d = new Deal_Destination_Model();
                if($id <> '' ){
                    $d->where('id', $id)->get();
                }
                $d->name = $this->input->post('name');
                $d->latitude = floatval($this->input->post('latitude'));
                $d->longitude = floatval($this->input->post('longitude'));
                $d->country_code = $this->input->post('country_code');
                $d->deal_id = $this->input->post('deal_id');
                $d->save();
                redirect('deal/view/'.$d->deal_id, 'refresh');
            }else{
                if($id <> '' ){
                    $d = new Deal_Destination_Model();
                    $d->where('id', $id)->get();
                    $data['destination'] = $d->to_array();
                    $data['title']= 'Edit destination';
                }else{
                    $data['title']= 'Add destination';
                }
                    $data['deal_id']  = $deal_id;
                    $data['content']= 'deal/destination_form';
                    $data['cssIncludes'] = array('deal_view_edit.css');
                    $data['jsIncludes'] = array('destination.js');
                    $this->load->view('template', $data);
            }
        //}
    }
    
    public function ajax_delete_destination($id){
        $userdata = $this->session->userdata('logged_in');
        $d = new Deal_Destination_Model();
        $success = FALSE;
        if($id <>'') {
            $d->where('id', $id )->get();
            $success = $d->delete();
            
//            $this->load->model('activitylog_model');
//            $this->activitylog_model->insert_log($userdata['uid'], HOTEL_DELETE, $id);
        }
        echo json_encode (array('success' => $success));
    }
   
    public function currencySearch(){
        $term = $this->input->post('term');
        $d = new Currency_Model();
        $d->like('name', $term)->or_like('code', $term)->get();
        $result = $d->all_to_array();
        $currencies = array();
        foreach($result as $item){
            $currencies[] = array(
                'id' => $item['id'],
                'text' => $item['name']
            );
        }
        echo json_encode($currencies);
    }
   
    public function currencybyid(){
        $id = $this->input->post('id');
        $d = new Currency_Model();
        $d->where('id', $id)->get();
        $ret = $d->to_array();
        $currency = array(
            'id' => $ret['id'],
            'text' => $ret['name']
        );
        echo json_encode($currency);
    }
   
    public function viewEdit($id){ 
       $session_data = $this->session->userdata('logged_in');
       $data['username'] = $session_data['username'];
       $data['title']= 'Deal view edit';
       $data['content']= 'deal/form';
       $data['cssIncludes'] = array('deal_view_edit.css');
       $this->load->view('template', $data);
   }
   
    public function viewDestination($id){ 
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Deal view destination';
        $data['content']= 'deal/view-destination';
        $data['cssIncludes'] = array('deal_view_destination.css');
        $this->load->view('template', $data);
    }
    
    public function viewDay($id){ 
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Deal view day';
        $data['content']= 'deal/view-day';
        $data['cssIncludes'] = array('deal_view_day.css');
        $this->load->view('template', $data);
    }
    
    public function viewDate($id){ 
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Deal view tour date';
        $data['content']= 'deal/view-tour-date';
        $data['cssIncludes'] = array('deal_tour_date.css');
        $this->load->view('template', $data);
    }
    public function viewHotel($id){ 
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Deal hotel';
        $data['content']= 'deal/hotel';
        $data['cssIncludes'] = array('deal_hotel.css');
        $this->load->view('template', $data);
    }
    public function viewRoom($id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Hotel room';
        $data['content']= 'deal/room';
        $data['cssIncludes'] = array('deal_hotel_room.css');
        $this->load->view('template', $data);
    }
    public function viewOptionalTour($id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Optional tour';
        $data['content']= 'deal/edit_optional_tour';
        $data['cssIncludes'] = array('optional_tour_edit.css');
        $this->load->view('template', $data);
    }
    public function editHotel(){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Deal hotel';
        $data['content']= 'deal/edit_hotel';
        $data['cssIncludes'] = array('hotel_edit.css');
        $this->load->view('template', $data);
    }
    
    public function getImageOrder($id){
        $max_order = 0;
        $images = new Deal_Image_Model();
        $res = $images->where('deal_id', $id)->order_by('image_order','desc')->get(1);
        if ($res) {
            $max_order = $res->image_order; 
        } 
        return $max_order;
    }
    
    public function updateImageOrder(){
        $idArray = explode(",",$_POST['ids']);
        $count = 1;
        foreach ($idArray as $id){
                $images = new Deal_Image_Model();
                $success= $images->where('id', $id )->update('image_order',$count);
                $count ++;	
        }
        return true;
    }
    
    function ajax_thumb_upload(){ 
        $status = "";
        $msg = "";
        $file_element_name = 'thumb';
        $deal_id = $this->input->post('id');
        $this->load->helper('discover_title_helper');
        $type= 'thumb_deal';
        if ($status != "error")
        { 
            $config['upload_path'] = '../media/deals';
            $config['allowed_types'] = 'gif|jpeg|jpg|png';
            $config['max_size'] = 1024 * 8;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            if($this->upload->do_upload($file_element_name)){
                $upload_data = $this->upload->data();
                $original_filename = $upload_data['file_name'];
                $extension = explode(".",$upload_data['file_name']);
                $new_name = microtime();
                $new_name = str_replace('0.', '', $new_name);
                $new_name = str_replace(' ', '', $new_name);
                
                /* change file name */
                $new_full_name = $type.'_'.$new_name. '.' .end($extension);

                rename('../media/deals/'.$original_filename, '../media/deals/'.$new_full_name);
//                $thumb_conf['image_library'] = 'GD2';
//                $thumb_conf['maintain_ratio'] = TRUE;
//                $thumb_conf['quality'] = '100%';
//                
//                
//
//                /* Thumb Size Image  */
//                $thumb_conf['height'] = 150;
//                $thumb_conf['width'] = 265;
//                $thumb_conf['source_image'] = "../media/deals/" . $new_name.'.'.end($extension);
//                $thumb_conf['new_image'] = "../media/deals/".$new_full_name;
//                $this->image_lib->initialize($thumb_conf);
//                if(!$this->image_lib->resize())
//                {
//                    $msg =  $this->image_lib->display_errors();
//                }
                $deal_image = new Deal_Model();
                $deal_image->where('id', $deal_id )->update('thumb',$new_full_name);
                
                unlink($upload_data['full_path']);
                $status = "success";
                $msg = "File successfully uploaded";
            }else{
                $status = "error";
//                $msg = "Something went wrong when saving the file, please try again.";
                $msg = $this->upload->display_errors();
            }
            echo json_encode(array('status' => $status,'path'=>$new_full_name, 'msg' => $msg));
        }
    }
    
     public function detail_add($id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['deal_id']  = $id;
        $data['title']= 'Add Description';
        $data['content']= 'deal/detail_form';
        $data['cssIncludes'] = array('deal_view_edit.css');
        $data['jsIncludes'] = array('deal_view.js');
        $this->load->view('template', $data);
    }
    
     public function detail_submit(){
        $userdata = $this->session->userdata('logged_in');
         $this->form_validation->set_error_delimiters('<em>&nbsp;</em><span> &nbsp;</span><p class="errormsg">', '</p>');
       // if(!empty($_POST)) {
            $id = $this->input->post('id');
            $deal_id= $this->input->post('deal_id');
            $this->form_validation->set_rules('title', 'title', 'trim|required|xss_clean|callback_alpha_numeric_space');
            $this->form_validation->set_rules('description', 'description', 'trim|required|xss_clean');
            $this->form_validation->set_rules('starttime', 'starttime', 'trim|required|xss_clean');
            $this->form_validation->set_rules('endtime', 'endtime', 'trim|required|xss_clean');
            $this->form_validation->set_rules('breakfast', 'breakfast', 'trim|xss_clean');
            $this->form_validation->set_rules('lunch', 'lunch', 'trim|xss_clean');
            $this->form_validation->set_rules('dinner', 'dinner', 'trim|xss_clean');
            
            if ($this->form_validation->run() == TRUE) {
                $d = new Deal_Detail_Model();
                if($id <> '' ){
                    $d->where('id', $id)->get();
                }
                $d->title = $this->input->post('title');
                $d->description = $this->input->post('description');
                $d->start_time = date("G:i", strtotime($this->input->post('starttime')));
                $d->end_time = date("G:i", strtotime($this->input->post('endtime')));
                $post_breakfast = $this->input->post('breakfast');
                $post_lunch = $this->input->post('lunch');
                $post_dinner = $this->input->post('dinner');
                $d->breakfast_include = !empty($post_breakfast) ? 1 : 0;
                $d->lunch_include = !empty($post_lunch) ? 1 : 0;
                $d->dinner_include = !empty($post_dinner) ? 1 : 0;
                $d->deal_id = $deal_id;
                $d->save();
                redirect('deal/view/'.$d->deal_id, 'refresh');
            }else{
                if($id <> '' ){
                    $d = new Deal_Detail_Model();
                    $d->where('id', $id)->get();
                    $data['detail'] = $d->to_array();
                    $data['title']= 'Edit Description';
                }else{
                     $data['deal_id']  = $deal_id;
                     $data['title']= 'Add Description';
                }
                $data['content']= 'deal/detail_form';
                $data['cssIncludes'] = array('deal_view_edit.css');
                $data['jsIncludes'] = array('deal_view.js');
                $this->load->view('template', $data);
            }
        //}
       
    }
    
     public function detail_edit($id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $d = new Deal_Detail_Model();
        $d->where('id', $id)->get();
        $data['detail'] = $d->to_array();
        $data['title']= 'Edit Description';
        $data['content']= 'deal/detail_form';
        $data['cssIncludes'] = array('deal_view_edit.css');
        $data['jsIncludes'] = array('deal_view.js');
        $this->load->view('template', $data);
    }
    
    public function ajax_delete_detail($id){
        $userdata = $this->session->userdata('logged_in');
        $d = new Deal_Detail_Model();
        $success = FALSE;
        if($id <>'') {
            $d->where('id', $id )->get();
            $success = $d->delete();
        }
        echo json_encode (array('success' => $success));
    }
    
    function add_duplicate($id){
        $deal = new Deal_Model();
        $status = "error";
        if($id <>'') {
            $deal->where('id', $id )->get();
            $deal->to_array();
         
            $d = new Deal_Model();
            $d->name = $deal->name; 
            $d->price = $deal->price;
            $d->currency_id  = $deal->currency_id;
            $d->published  = $deal->published;
            $d->highlights = $deal->highlights;
            $d->subtitle = $deal->subtitle;
            $d->summary_title = $deal->summary_title;
            $d->summary = $deal->summary;
            $d->hotel_summary_title = $deal->hotel_summary_title;
            $d->hotel_summary = $deal->hotel_summary;
            $d->tour_from_date = $deal->tour_from_date;
            $d->tour_to_date = $deal->tour_to_date;
            $d->min_days = $deal->min_days;
            $d->max_days = $deal->max_days;
            $d->terms_conditions = $deal->terms_conditions;
            $d->optional_terms_conditions = $deal->optional_terms_conditions;
            $d->deal_includes = $deal->deal_includes;
            $d->deal_not_include = $deal->deal_not_include;
            $d->dealer_id = $deal->dealer_id;
            $d->tour_route = $deal->tour_route;
            $d->category = $deal->category;
            $d->thumb = $deal->thumb;
            $d->save();

            $deal_id = $d->id;
            
            $detail = new Deal_Detail_Model();
            $detail->where('deal_id', $deal->id)->get();
            $details = $detail->all_to_array();
            foreach($details as $detail_value)
            {
                $d_detail = new Deal_Detail_Model();
                $d_detail->deal_id = $deal_id;
                $d_detail->title =  $detail_value['title'];
                $d_detail->description =  $detail_value['description'];
                $d_detail->save();
            }
           
            $destination = new Deal_Destination_Model();
            $destination->where('deal_id', $deal->id)->get();
            $destinations = $destination->all_to_array();
            foreach($destinations as $destination_value)
            {
                $d_destination = new Deal_Destination_Model();
                $d_destination->name =  $destination_value['name'];
                $d_destination->latitude =  $destination_value['latitude'];
                $d_destination->longitude =  $destination_value['longitude'];
                $d_destination->deal_id = $deal_id;
                $d_destination->save();
            }
            
            $option = new Deal_Option_Model();
            $option->where('deal_id', $deal->id)->get();
            $options = $option->all_to_array();
            foreach($options as $option_value)
            {
                $d_option = new Deal_Option_Model();
                $d_option->title =  $option_value['title'];
                $d_option->description =  $option_value['description'];
                $d_option->availability =  $option_value['availability'];
                $d_option->price =  $option_value['price'];
                $d_option->currency_id =  $option_value['currency_id'];
                $d_option->deal_id = $deal_id;
                $d_option->save();
            }
            
            $image = new Deal_Image_Model();
            $image->where('deal_id', $deal->id)->order_by('image_order')->get();
            $images = $image->all_to_array();
            $i = 1;
            foreach($images as $image_value)
            {
                $d_image = new Deal_Image_Model();
                $d_image->path =  $image_value['path'];
                $d_image->deal_id =  $deal_id;
                $d_image->image_order = $i;
                $d_image->save();
                $i++;
            }
            
            $status = "success";
        }
        echo json_encode (array('status' => $status));
    }
    
    /* function for form validation - to allow alphabet and spaces only*/
    function alpha_space($str_in){
		if (! preg_match("/^([-a-z ])+$/i", $str_in)) {
			$this->form_validation->set_message('alpha_space', 'The %s field may only contain alpha characters and spaces.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
        
    function compareDate() {
     $startDate = strtotime($_POST['tourFromDate']);
     $endDate = strtotime($_POST['tourToDate']);

        if ($endDate >= $startDate)
          return True;
        else {
          $this->form_validation->set_message('compareDate', '%s should be greater than tour Start Date.');
          return False;
        }
    }
    
    function alpha_numeric_space($str)
    {
        return ( ! preg_match("/^([a-z0-9 ])+$/i", $str)) ? FALSE : TRUE;
    }
}
