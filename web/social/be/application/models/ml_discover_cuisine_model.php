<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ml_Discover_Cuisine_Model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    public function update_ml_cuisine($entity_id,$lang_code,$value) {
        $a = array('entity_id'=>$entity_id, 'lang_code'=>$lang_code, 'title' =>$value);
        $sql = $this->db->select('id')->from('ml_discover_cuisine')->where('entity_id', $a['entity_id'])->where('lang_code', $a['lang_code'])->get();  
        if( $sql->num_rows() !=0 ) {
            // id exists
            $this->db->where('entity_id', $a['entity_id']);
            $this->db->where('lang_code', $a['lang_code']);
            $this->db->update('ml_discover_cuisine', $a);
            $result = TRUE;
        } else {
           // id doesn't exist 
           $this->db->insert('ml_discover_cuisine', $a);
           $result = TRUE;
        }
        return $result;

    }
    

}

     