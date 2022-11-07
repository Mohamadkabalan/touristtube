<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(1);
class Log extends MY_Controller {
    
    
/*    public function searchLogs($start = 0){
        
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Search logs';
//        $l = new Searchlog_Model();
//        $l->order_by('search_ts desc')->get();
//        $data['logs']= $l->all_to_array();
//        $data['content']= 'log/searchLogs';
//        $this->load->view('template', $data);
        $this->load->library('pagination');
//        $config['uri_segment'] = 2;
        $config['per_page'] = 200;
        $config["num_links"] = 14;
        $config['base_url'] = 'log/searchlogs';
        $l = new Searchlog_Model();
        $l->select('search_string, search_page, count(*) as cnt')
            ->group_by('search_string, search_page')->get();
        $arr = $l->all_to_array();
        $total = count($arr);
        $l = new Searchlog_Model();
        $l->select('search_string, search_page, count(*) as cnt')
            ->group_by('search_string, search_page')
            ->order_by('cnt desc')
            ->order_by('search_ts desc')
            ->get(200, $start);
        
        $config['total_rows'] = $total;
        $data['logs']= $l;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['content']= 'log/searchLogs';
        $this->load->view('template', $data);
    }  */
    
       function __construct() {
            parent::__construct();
            $this->load->model('Invite_Model');
            $this->load->model('Report_Model');
            $this->load->model('Cms_Tubers_Login_Tracking_Model');
       }
    
       public function searchLogs($start = 0){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Search logs';

//        $l = new Searchlog_Model();
//        $l->order_by('search_ts desc')->get();
//        $data['logs']= $l->all_to_array();
//        $data['content']= 'log/searchLogs';
//        $this->load->view('template', $data);
        $this->load->library('pagination');
        $config['uri_segment'] = 2;
        $config['per_page'] = 50;
        $config["num_links"] = 14;
        $config['base_url'] = 'log/ajax_searchLogs';
        $searc_type = array('W_MEDIA'=>'W_MEDIA','W_TUBER'=>'W_TUBER','W_CHANNEL'=>'W_CHANNEL','M_MEDIA'=>'M_MEDIA','M_TUBER'=>'M_TUBER','M_CHANNEL'=>'M_CHANNEL') ;


        $start_date = date('Y/m/d', strtotime('-6 days'));
        $end_date = date('Y/m/d');

        $where_date_bet = "date(search_ts) between '".$start_date."' and '".$end_date."'";
		
        $l = new Searchlog_Model();
        $l->select('search_string, search_type,search_ts, count(*) as cnt')
            ->where($where_date_bet)
            ->group_by('search_string, search_type')->get();
        $arr = $l->all_to_array();
        $total = count($arr);
		
        $l = new Searchlog_Model();
        $l->select('search_string, search_type, count(*) as cnt')
            ->where($where_date_bet)
            ->group_by('search_string, search_type')
            ->order_by('cnt desc')
            ->order_by('search_ts desc')
            ->get(50, $start);
        
				
        $config['total_rows'] = $total;
        $data['logs']= $l;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['content']= 'log/searchLogs';
        $data['jsIncludes'] = array('log.js');
        $data['cssIncludes'] = array('activity.css');
        $data['searc_type'] = $searc_type;
        $this->load->view('template', $data);
    }
    
     /*
	function name:ajax_searchLogs
	purpose: To get search logs with in a date range
	params: start limit
	Author: Pradeep
	Modified: 27 Aug 2014
	*/
	
	public function ajax_searchLogs($start = 0){
//            $session_data = $this->session->userdata('logged_in');
//            $data['username'] = $session_data['username'];
//            $data['title']= 'Search logs';
            $this->load->library('pagination');
            $config['uri_segment'] = 2;
            $config['per_page'] = 50;
            $config["num_links"] = 14;
            $config['base_url'] = 'log/ajax_searchLogs';

            $where_search_type  ='';
            $where_date_bet ='';
            $post = $this->input->post();
            if(!empty($post) > 0){
                $start_date_str = $this->input->post('start_date');
                $end_date_str = $this->input->post('end_date');
                $start_date = date('Y-m-d', strtotime($start_date_str));
                $end_date = date('Y-m-d', strtotime($end_date_str));
                $where_date_bet = "date(search_ts) between '".$start_date."' and '".$end_date."'";
            }

            if(!empty($post['search_type'])){
                    $search_type = $this->input->post('search_type');
                    $where_search_type = "(search_type='".$search_type."') ";
            }else {
                    $where_search_type = "(search_type!='') ";
            }

            $l = new Searchlog_Model();

            $l->select('search_string, search_type,search_ts, count(*) as cnt')
                            ->where($where_search_type)
                            ->where($where_date_bet)
                ->group_by('search_string, search_type')->get();
            $arr = $l->all_to_array();
            $total = count($arr);

            $l = new Searchlog_Model();
            $l->select('search_string, search_type, count(*) as cnt')
                            ->where($where_search_type)
                            ->where($where_date_bet)
                ->group_by('search_string, search_type')
                ->order_by('cnt desc')
                ->order_by('search_ts desc')
                ->get(50, $start);


            $config['total_rows'] = $total;
            $data['logs']= $l;
            $this->pagination->initialize($config);
            $data['links'] = $this->pagination->create_links();
//            $data['content']= 'log/searchLogs';
//            $data['jsIncludes'] = array('log.js');
//            $data['cssIncludes'] = array('activity.css');
            $this->load->view('log/ajax_search_logs', $data);
        
        }
    /*
	function name:requestInvitation
	purpose: To get all Request Invitation
	Author: Mukesh
	Created: 28 Nov 2014
    */        
    public function requestInvitation(){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Request Invitation';
        $data['content']= 'log/request_invitation_list';
        $data['users'] = $this->Invite_Model->request_invite();
        $data['jsIncludes'] = array('invitations.js');
        $this->load->view('template', $data);
    }
    
    /*
	function name:requestInvitation
	purpose: To get all Request Invitation
	Author: Mukesh
	Created: 28 Nov 2014
    */        
    public function report(){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Report';
        $data['content']= 'log/report_list';
        //echo 'hi';
        $data['reports'] = $this->Report_Model->report();
        //print_r($data['report']);
        $this->load->view('template', $data);
    }
    
    public function ajax_invite($id){
        $salt = "$):6!T|\oj|2*9,1h/zl";
        $salt = md5($salt);
//        $this->load->helper('path');
//        $this->load->file('../vendor/autoload.php');
//        $this->load->file('../inc/functions/lang.php');
//        LanguageSet('en');
//        $this->load->file('../inc/functions/emails.php');
//        $success = $this->Invite_Model->remove_invite($id);
//        echo json_encode (array('success' => $success));
        
        $invite = $this->Invite_Model->get_by_id($id);
        $email = $invite['to_email'];
        $name = $invite['to_name'];
        $url = "http://para-tube/ajax/account_invite.php?lang=en";
//        $url = "https://www.touristtube.com/ajax/account_invite.php?lang=en";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('id' => $id, 'email' => $email, 'name' => $name, 'key' => $salt)));
        $result = curl_exec($ch);
        curl_close($ch);
        echo $result;
    }
    
    public function userTracking(){
        $this->load->library('pagination');
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'User Tracking Logs';
        $data['content']= 'log/usertracking_list';
        
        $config['uri_segment'] = 3;
        $config['per_page'] = 200;
        $config["num_links"] = 14;
        $config['base_url'] = 'log/userTracking';
        $total = $this->Cms_Tubers_Login_Tracking_Model->log_report_total();
        $config['total_rows'] = $total[0]->cnt;
        $start = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['logs'] = $this->Cms_Tubers_Login_Tracking_Model->log_report($start, $config['per_page']);
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        //print_r($data['logs']);die;
        $this->load->view('template', $data);
    }
}
