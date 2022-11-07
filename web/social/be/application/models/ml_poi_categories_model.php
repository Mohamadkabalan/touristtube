<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ml_Poi_Categories_Model extends DataMapper{
    function __construct(){
        parent::__construct();
    }
    
    public function update_ml_poi_categories($entity_id,$lang_code,$value) {
        $a = array('entity_id'=>$entity_id, 'lang_code'=>$lang_code, 'title' => $value);
        $sql = $this->db->select('id')->from('ml_poi_categories')->where('entity_id', $a['entity_id'])->where('lang_code', $a['lang_code'])->get();  
        if( $sql->num_rows() !=0 ) {
            // id exists
            $this->db->where('entity_id', $a['entity_id']);
            $this->db->where('lang_code', $a['lang_code']);
            $this->db->update('ml_poi_categories', $a);
            $result = TRUE;
        } else {
           // id doesn't exist 
           $this->db->insert('ml_poi_categories', $a);
           $result = TRUE;
        }
        return $result;

    }
    

}

     