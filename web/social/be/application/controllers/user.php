<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {
    
    var $user_activities = array(
	'1' => 'Hotel insert',
	'2' => 'Hotel update',
	'3' => 'Hotel delete',
	'4' => 'Hotel room insert',
	'5' => 'Hotel room update',
	'6' => 'Hotel room delete',
	'7' => 'Hotel review insert',
	'8' => 'Hotel review update',
	'9' => 'Hotel review delete',
	'10' => 'Hotel image insert',
	'11' => 'Hotel image delete',
	'12' => 'Restaurant insert',
	'13' => 'Restaurant update',
	'14' => 'Restaurant delete',
	'15' => 'Restaurant review insert',
	'16' => 'Restaurant review update',
	'17' => 'Restaurant review delete',
	'18' => 'Restaurant image insert',
	'19' => 'Restaurant image delete',
	'20' => 'Poi insert',
	'21' => 'Poi update',
	'22' => 'Poi delete',
	'23' => 'Poi review insert',
	'24' => 'Poi review update',
	'25' => 'Poi review delete',
	'26' => 'Poi image insert',
	'27' => 'Poi image delete',
	'28' => 'User login',
	'29' => 'User logout',
	'30' => 'Media translate English',
	'31' => 'Media translate Hindi',
	'32' => 'Media translate French',
	'33' => 'Media translate Chinese',
	'34' => 'Media translate Spanish',
	'35' => 'Media translate Portuguese',
	'36' => 'Media translate Italian',
	'37' => 'Media translate Deutsch',
	'38' => 'Media translate Filipino',
	'39' => 'Media delete',
	'40' => 'Album delete',
        
        '41' => 'TTD region insert',
        '42' => 'TTD region update',
        '43' => 'TTD region delete',
        '44' => 'TTD country insert',
        '45' => 'TTD country update',
        '46' => 'TTD country delete',
        '47' => 'TTD city insert',
        '48' => 'TTD city update',
        '49' => 'TTD city delete',
        '50' => 'TTD poi insert',
        '51' => 'TTD poi update',
        '52' => 'TTD poi delete',
        
        '53' => 'Seo Alias insert',
        '54' => 'Seo Alias update',
        '55' => 'Seo Alias delete',
        '56' => 'Seo Alias translation'
    );
    
    function index(){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Manage users';
        $data['content']= 'user/list';
        $u = new User_Model();
        $u->get();
        $data['users'] = $u;
        $this->load->view('template', $data);
    }
    
    function view($id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'View user';
        $data['content']= 'user/view';
        $u = new User_Model();
        $u->where('id', $id)->get();
        $data['user'] = $u->to_array();
        $this->load->view('template', $data);
    }
    
    function add(){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Add user';
        $data['content']= 'user/form';
        $data['jsIncludes'] = array('user.js');
        $this->load->view('template', $data);
    }
    
    function edit($id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Edit user';
        $data['content']= 'user/form';
        $u = new User_Model();
        $u->where('id', $id)->get();
        $data['user']= $u->to_array();
        $this->load->view('template', $data);
    }
    
    function submit(){
        $session_data = $this->session->userdata('logged_in');
        $id = $this->input->post('id');
        $password = $this->input->post('password');
        $role = $this->input->post('role');
        $u = new User_Model();
        if($id <> ''){
            $u->where('id', $id)->get();
        }
        $u->fname = $this->input->post('fname');
        $u->lname = $this->input->post('lname');
        $u->username = $this->input->post('username');
        if($password <> ''){
            $u->password = MD5($password);
        }
		// added the user translator admin role in below condition by Sushma Mishra on 28-08-2015
        if($role <> 'admin' && $role <> 'editor' && $role <> 'translator' && $role <> 'user_translator' && $role <> 'user_translator_admin' && $role <> 'dealer' && $role <> 'copywriter' && $role <> 'hotel_desc_writer' && $role <> 'hotel_desc_writer' && $role <> 'hotel_chain')
            $role = 'editor';
        $u->role = $role;
        $u->save();
        redirect('user/view/'.$u->id, 'refresh');
    }
    
    function ajax_delete($id){
        $session_data = $this->session->userdata('logged_in');
        if(!$session_data || !$session_data['is_admin'])
            return;
        
        $success = FALSE;
        if($id <>'') {
            $this->load->model('activitylog_model');
            $has_activities = $this->activitylog_model->UserHasActivities($id);
            if(!$has_activities){
                $d = new User_Model();
                $d->where('id', $id )->get();
                $success = $d->delete();
            }
        }
        echo json_encode (array('success' => $success));
    }
    
    function activities(){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $this->load->model('activitylog_model');
        $activities = $this->activitylog_model->All(date('Y/m/d', strtotime('-6 days')), date('Y/m/d'));
        $data['title']= 'Users activities';
        $data['content']= 'user/activities';
        $data['activities'] = $activities;
        $data['jsIncludes'] = array('activity.js');
        $data['cssIncludes'] = array('activity.css');
        $this->load->view('template', $data);
    }
    
    function ajax_activities(){
        $session_data = $this->session->userdata('logged_in');
        if(!$session_data || !$session_data['is_admin'])
            return;
        $start_date_str = $this->input->post('start_date');
        $end_date_str = $this->input->post('end_date');
        $start_date = date('Y/m/d', strtotime($start_date_str));
        $end_date = date('Y/m/d', strtotime($end_date_str));
        $this->load->model('activitylog_model');
        $activities = $this->activitylog_model->All($start_date, $end_date);
        $data['activities'] = $activities;
        $this->load->view('user/ajax_activities', $data);
    }
    
  
	/*
	function name:userActivityDetails
	purpose: to get user activities on page Refresh
	params: user_id, activity type
	Author: Pradeep
	Modified: 26 Aug 2014
	*/
	
	function userActivityDetails($user_id, $activity_type=0){
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            $this->load->model('activitylog_model');
            $activities = $this->activitylog_model->userActivityDetail($user_id, $activity_type, date('Y/m/d', strtotime('-6 days')), date('Y/m/d'));
            $data['title']= 'Users Activities Details';
            $data['content']= 'user/useractivitydetails';
            $data['activities'] = $activities;
            $data['user_id']  = $user_id;
//            $activity_type = $this->activitylog_model->getActivityList();
            $activity_type = $this->user_activities;
            $data['activity_types'] = $activity_type;
            $data['jsIncludes'] = array('activity_details.js');
            $data['cssIncludes'] = array('activity.css');
            $this->load->view('template', $data);
	}
	
	
	/*
	function name:ajax_user_activities_details
	purpose: to get user activities using ajax
	params: user_id, activity type
	Author: Pradeep
	Modified: 26 Aug 2014
	*/
	
	function ajax_user_activities_details($user_id,$activity_type=0){
            $session_data = $this->session->userdata('logged_in');
            if(!$session_data || !$session_data['is_admin'])
                return;
            $start_date_str = $this->input->post('start_date');
            $end_date_str = $this->input->post('end_date');
//          Added By Anthony Malak 04-07-2015 to fix the filter by the Hash id added by the user
//          <start>
            $hash_id = $this->input->post('hash_id');
//          Added By Anthony Malak 04-07-2015 to fix the filter by the Hash id added by the user
//          <end>

			//code added by Sushma Mishra on 26-08-2015 to add the filter by the cms video id starts from here
            $cmsvideo_id = $this->input->post('cmsvideo_id');
			//code added by Sushma Mishra on 26-08-2015 to add the filter by the cms video id ends here

            $start_date = date('Y/m/d', strtotime($start_date_str));
            $end_date = date('Y/m/d', strtotime($end_date_str));
            $this->load->model('activitylog_model');
            $activities = $this->activitylog_model->userActivityDetail($user_id, $activity_type, $start_date, $end_date, $hash_id, $cmsvideo_id);// also add another argument for the select to check on the hash Id added by the user
            $data['activities'] = $activities;
            $data['activity_types'] = $this->user_activities;
            $this->load->view('user/ajax_user_activities_details', $data);
	}
        
        function get_dealers(){
            $dealers = array();
            $u = new User_Model();
            $u->where('role', 'dealer')->order_by('fname')->get();
            $dealers = $u->to_array();
            return $dealers;
        }
}

