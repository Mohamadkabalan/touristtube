<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ml_Hotel_Model extends DataMapper{
    var $table = 'ml_hotel';  
    function __construct(){
        parent::__construct();
    }
    
    public function update_ml_hotel($entity_id,$lang_code,$value,$value2) {
        $a = array('hotel_id'=>$entity_id, 'lang_code'=>$lang_code, 'description' => $value, 'name' => $value2);
        $sql = $this->db->select('id')->from('ml_hotel')->where('hotel_id', $a['hotel_id'])->where('lang_code', $a['lang_code'])->get();  
        if( $sql->num_rows() !=0 ) {
            // id exists
            $this->db->where('hotel_id', $a['hotel_id']);
            $this->db->where('lang_code', $a['lang_code']);
            $this->db->update('ml_hotel', $a);
            $result = TRUE;
        } else {
           // id doesn't exist 
           $this->db->insert('ml_hotel', $a);
           $result = TRUE;
        }
        return $result;

    }
    

}

     