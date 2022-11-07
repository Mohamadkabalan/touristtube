<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class User_Model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
 function login($username, $password) {
   $this -> db -> select('uid, username, password');
   $this -> db -> from('users');
   $this -> db -> where('username', $username);
   $this -> db -> where('password', MD5($password));
   $this -> db -> limit(1);
   $query = $this -> db -> get();
   if($query -> num_rows() == 1)    return $query->result();
   else  return false;
   }

}