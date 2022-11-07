<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invite_Model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
public function request_invite() {
   $this -> db -> select('*');
   $this -> db -> from('cms_invite');
   $this -> db -> where('from_id', '-1');
   $query = $this -> db -> get();
   return $query->result();
}
public function get_by_id($id){
    $this->db->select('*');
    $this->db->from('cms_invite');
    $this->db->where('id', $id);
    $query = $this->db->get();
    if($query->num_rows() > 0){
        $row = $query->row_array();
        return $row;
    }
    return false;
}   
public function remove_invite($id){
    $q = array('from_id' => 0);
    $this->db->where('id', $id);
    $success = $this->db->update('cms_invite', $q);
    return $success;
}
}