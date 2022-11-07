<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VerifyLogin extends CI_Controller {

 function __construct()
 {
   parent::__construct();
   $this->load->model('user_model','',TRUE);
 }

 function index()
 {
   //This method will have the credentials validation
   $this->load->library('form_validation');

   $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
   $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');
   
   if($this->form_validation->run() == FALSE)
   {
     //Field validation failed.  User redirected to login page
     $this->load->view('login_view');
   }
   else
   {
     //Go to private area
     redirect('dashboard', 'refresh');
   }

 }

 function check_database($password)
 {
   //Field validation succeeded.  Validate against database
   $username = $this->input->post('username');
   //query the database
//   $result = $this->user_model->login($username, $password);
   $u = new User_Model();
   $u->where('username', $username)->where('password', MD5($password))->where('is_active', '1')->get();
   $result = $u->to_array();
   if(isset($result['id']))
   {
     $sess_array = array(
         'uid' => $result['id'],
         'username' => $result['username'],
         'is_admin' => $result['is_admin'],
         'role' => $result['role']
     );
     if($result['role'] == 'user_translator'){
        $u_res = $this->db->select()
              ->from('cms_users')
              ->where('YourUserName', $result['username'])
              ->get()
              ->row();
        $sess_array['ttuid'] = $u_res->id;
     }
     $u->where('id', $result['id'])->update('last_login', date('Y/m/d H:i:s'));
    $this->session->set_userdata('logged_in', $sess_array);
    $this->load->model('activitylog_model');
    $this->activitylog_model->insert_log($result['id'], USER_LOGIN, $result['id']);
//     foreach($result as $row)
//     {
//       $sess_array = array(
//         'uid' => $row->uid,
//         'username' => $row->username,
//         'is_admin' => $row->is_admin
//       );
//       $this->session->set_userdata('logged_in', $sess_array);
//     }

     return TRUE;
   }
   else
   {
     $this->form_validation->set_message('check_database', 'Invalid username or password');
     return false;
   }
 }
}
?>