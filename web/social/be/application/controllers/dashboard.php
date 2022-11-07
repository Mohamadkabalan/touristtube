<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Controller {

function index()
 {
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['title']= 'Dashboard';
    $data['content']= 'dashboard';
    $this->load->view('template', $data);
 }

 function logout()
 {
    $userdata = $this->session->userdata('logged_in');
    $this->load->model('activitylog_model');
    $this->activitylog_model->insert_log($userdata['uid'], USER_LOGOUT, $userdata['uid']);
    $this->session->unset_userdata('logged_in');
    redirect('login', 'refresh');
 }
 
 
    
    private function check_isvalidated(){
        if(! $this->session->userdata('validated')){
            redirect('login');
        }
    }
}