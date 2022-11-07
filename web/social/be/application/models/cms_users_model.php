<?php

class Cms_Users_Model extends CI_Model{   
    public function get_users_id(){
       $res = 	$this->db->select('cms_users.id')
				->from('cms_users')
				->join('bo_users', 'cms_users.YourUserName = bo_users.username')
				->where('bo_users.role', 'user_translator')       
				->get()				
                ->result();				
        return $res;
    }
   
}