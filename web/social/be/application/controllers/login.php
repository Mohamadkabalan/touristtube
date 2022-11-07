<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller{

    public static $s3 = null;
    
    function __construct(){
        parent::__construct();
    }

    public function index($msg = NULL){
       $data['title'] = "Login";
       $data['msg'] = $msg;
       $data['input_span'] = 'col-md-2';
       $this->load->view('login_view', $data);

    }
    public function info(){phpinfo();}
}