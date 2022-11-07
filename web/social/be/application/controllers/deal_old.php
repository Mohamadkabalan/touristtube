<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Deal extends MY_Controller {

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
        $data['username'] = $session_data['username'];
        $data['title']= 'Edit main info';
        $data['content']= 'deal/form';
        $h = new Deal_Model();
        $h->where('id', $id)->get();
        if(!$this->secureDeal($h)){
            exit;
        }
        $data['deal']= $h->to_array();
        $data['cssIncludes'] = array('deal_view_edit.css');
        $data['jsIncludes'] = array('deal_form.js');
        $this->load->view('template', $data);
    }
 
    public function add(){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Add deal';
        $data['content']= 'deal/form';
        $data['cssIncludes'] = array('deal_view_edit.css');
        $data['jsIncludes'] = array('deal_form.js');
        $this->load->view('template', $data);
    }
    
    public function view($id){ 
       $session_data = $this->session->userdata('logged_in');
       $data['username'] = $session_data['username'];
       $deal = new Deal_Model();
       $deal->where('id', $id)->get();
       if(!$this->secureDeal($deal)){
            exit;
        }
       $data['deal']= $deal->to_array();
       $c = new Currency_Model();
       $c->where('id', $deal->currency_id)->get();
       $currency = $c->to_array();
       
       $destinations = new Deal_Destination_Model();
       $destinations->where('deal_id', $deal->id)->get();
       $data['destinations'] = $destinations->all_to_array();
       
       $days = new Deal_Day_Model();
       $days->where('deal_id', $deal->id)->get();
       $data['days'] = $days->all_to_array();
       
       $dates = new Deal_Date_Model();
       $dates->where('deal_id', $deal->id)->get();
       $data['dates'] = $dates->all_to_array();
       
       $data['currency'] = $currency['name'];
       $data['title']= 'Deal view';
       $data['content']= 'deal/view';
       $data['cssIncludes'] = array('deal_view.css');
       $this->load->view('template', $data);
   }
    
    public function submit(){
        $userdata = $this->session->userdata('logged_in');
//        print_r($userdata);exit();
        $id = $this->input->post('id');
        $h = new Deal_Model();
        if($id <> '' ){
            $h->where('id', $id)->get();
            if(!$this->secureDeal($h)){
            exit;
        }
        }
        $h->name = $this->input->post('title');
        $h->subtitle = $this->input->post('subtitle');
        $h->price = $this->input->post('price');
        if($h->price == ''){
            $h->price = 0;
        }
        $h->currency_id = $this->input->post('currency');
        if($h->currency_id == ''){
            $h->currency_id = 0;
        }
        
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
        
        $h->n_days = $this->input->post('nbrDays');
        if($h->n_days == ''){
            $h->n_days = 0;
        }
        $h->tour_route = $this->input->post('tourRoute');
        
        $h->summary_title = $this->input->post('summaryTitle');
        $h->summary = $this->input->post('summary');
        $h->terms_conditions = $this->input->post('termsConditions');
        $h->optional_terms_conditions = $this->input->post('optionalTermsConditions');
        $h->deal_includes = $this->input->post('dealIncludes');
        $h->deal_not_include = $this->input->post('dealNotInclude');
        if($id == '' ){
            $h->dealer_id = $userdata['uid'];
        }
        $h->save();
        $day_count = new Deal_Day_Model();
        $count = $day_count->where('deal_id', $h->id)->count();
        if($id == '' || $count == 0){
            for($i=1; $i<=$h->n_days; $i++){
                $day = new Deal_Day_Model();
                $day->name = 'day '.$i;
                $day->deal_id = $h->id;
                $day->save();
            }
        }
        //  $this->load->model('activitylog_model');
        //  $activity_code = $id == '' ? HOTEL_INSERT : HOTEL_UPDATE;
        //  $this->activitylog_model->insert_log($userdata['uid'], $activity_code, $h->id);
        redirect('deal/view/'.$h->id, 'refresh');
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
        $id = $this->input->post('id');
        $option = new Deal_Option_Model();
        if($id <> '' ){
            $option->where('id', $id)->get();
        }
        $option->title = $this->input->post('title');
        $option->description = $this->input->post('description');
        $option->currency_id = intval($this->input->post('currency'));
        $option->price = $this->input->post('price');
        $option->availability = intval($this->input->post('availability'));
        $option->deal_id = intval($this->input->post('deal_id'));
        $option->save();
        redirect('deal/view/'.$option->deal_id, 'refresh');
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
        $id = $this->input->post('id');
        $d = new Deal_Destination_Model();
        if($id <> '' ){
            $d->where('id', $id)->get();
        }
        $d->name = $this->input->post('name');
        $d->latitude = floatval($this->input->post('latitude'));
        $d->longitude = floatval($this->input->post('longitude'));
        $d->deal_id = $this->input->post('deal_id');
        $d->save();
        redirect('deal/view/'.$d->deal_id, 'refresh');
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
}
