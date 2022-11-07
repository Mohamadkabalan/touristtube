<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//class User_Model extends CI_Model{
//    function __construct(){
//        parent::__construct();
//    }
//    
// function login($username, $password) {
//   $this -> db -> select('uid, username, password, is_admin');
//   $this -> db -> from('bo_users');
//   $this -> db -> where('username', $username);
//   $this -> db -> where('password', MD5($password));
//   $this -> db -> limit(1);
//   $query = $this -> db -> get();
//   if($query -> num_rows() == 1)    return $query->result();
//   else  return false;
//   }
//
//}

class User_Model extends DataMapper{
    var $table = 'bo_users';
    function __construct($id = NULL)
    {
        parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }
    
     function get_dealers(){
            $dealers = array();
            $u = new User_Model();
            $u->select('id,fname,lname,is_admin,role');
            $u->where('role', 'dealer')->order_by('fname')->get();
            $dealers = $u->all_to_array();
            return $dealers;
    }
}