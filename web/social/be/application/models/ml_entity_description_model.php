<?php
//class Entity_Description_Model extends DataMapper {
//    var $table = 'cms_entity_description';
//    function __construct($id = NULL)
//    {
//        parent::__construct($id);
//    }
//    function post_model_init($from_cache = FALSE)
//    {
//
//    }
//}

class Ml_Entity_Description_Model extends CI_Model {
    function __construct(){
        parent::__construct();
    }
    
    public function getbyparentidandlang($id,$lang){
        $query =  "SELECT * from ml_entity_description where parent_id='$id' and language='$lang'";
        $res = $this->db->query($query);
        return $res->result();
    }
    
    public function update($id, $parent_id, $language, $description, $title){
        $data = array(
            'parent_id' => $parent_id,
            'language' => $language,
            'description' => $description,
            'title' => $title,
        );
        $this->db->where('id', $id);
        $result = $this->db->update('ml_entity_description', $data);
        return $result;
    }
    
    public function insert($parent_id, $language, $description, $title){
        $data = array(
            'parent_id' => $parent_id,
            'language' => $language,
            'description' => $description,
            'title' => $title,
        );
        $result = $this->db->insert('ml_entity_description', $data);
    }
    
    public function delete($id){
        return $this->db->delete('ml_entity_description', array('id' => $id));
    }
    
    public function check_entitytype_entityid_exist($entity_type, $entity_id){
        $query =  "SELECT * from cms_entity_description where entity_type='$entity_type' and entity_id = '$entity_id'";
        $res = $this->db->query($query);
        if($res == TRUE){
            $final = $res->result_array();
            //print_r($final);die;
            return $final;
        }else{
            return false;
        }
        
    }
}