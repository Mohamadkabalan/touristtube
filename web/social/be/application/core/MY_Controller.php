<?php 

class MY_Controller extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in'))
        { 
            redirect('login', 'refresh');
        }
        else{
            $session_data = $this->session->userdata('logged_in');
            $controller = $this->router->class;
            $role = $session_data['role'];
            if($role == 'dealer' && $controller != 'deal' && $controller != 'dashboard'){
                redirect('dashboard', 'refresh');
            }
            if($role == 'copywriter' && $controller != 'thingstodo' && $controller != 'dashboard'){
                redirect('dashboard', 'refresh');
            }
            if($role == 'hotel_desc_writer' && $controller != 'hotels' && $controller != 'dashboard' && $controller != 'thingstodo'){
                redirect('dashboard', 'refresh');
            }
			//added new role user_translator_admin in condition by sushma mishra on 28-08-2015
            if((($role == 'translator' || $role == 'user_translator' || $role == 'user_translator_admin') && $controller != 'ml' && $controller != 'dashboard') || ($role == 'editor' && $controller == 'user')){
                redirect('dashboard', 'refresh');
            }
            if(($role == "user_translator" || $role == "user_translator_admin") && $controller != 'dashboard'){
                redirect('dashboard', 'refresh');
                return;
            }
        }
    }
}