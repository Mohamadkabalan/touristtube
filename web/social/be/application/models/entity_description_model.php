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

class Entity_Description_Model extends CI_Model {
    function __construct(){
        parent::__construct();
    }
    
    public function whereis($start, $limit){
//        $start = $page * $limit;
        $query =  "SELECT ced.id, ced.title, ced.link, ced.description, ced.entity_type, ced.entity_id,
                          (CASE WHEN ced.entity_type = ".SOCIAL_ENTITY_COUNTRY." THEN ct.name 
                               WHEN ced.entity_type = ".SOCIAL_ENTITY_STATE." THEN st.state_name
                               WHEN ced.entity_type = ".SOCIAL_ENTITY_CITY." THEN wgc.name END) AS name
                   FROM cms_entity_description ced
                   LEFT JOIN cms_countries ct ON ct.id = ced.entity_id AND ced.entity_type = ".SOCIAL_ENTITY_COUNTRY."
                   LEFT JOIN states st ON st.id = ced.entity_id AND ced.entity_type = ".SOCIAL_ENTITY_STATE."
                   LEFT JOIN webgeocities wgc ON wgc.id = ced.entity_id AND ced.entity_type = ".SOCIAL_ENTITY_CITY;
        $res = $this->db->query($query);
        return $res->result();
    }
    
    public function whereis_total(){
        $query = "SELECT count(id) AS count FROM cms_entity_description WHERE entity_type = ".SOCIAL_ENTITY_COUNTRY." OR entity_type = ".SOCIAL_ENTITY_STATE." OR entity_type = ".SOCIAL_ENTITY_CITY;
        $res = $this->db->query($query);
        return $res->row()->count;
    }
    
    public function getbyid($id){
        $query =  "SELECT ced.id, ced.title, ced.link, ced.description, ced.entity_type, ced.entity_id,
                  (CASE WHEN ced.entity_type = ".SOCIAL_ENTITY_COUNTRY." THEN ct.name 
                       WHEN ced.entity_type = ".SOCIAL_ENTITY_STATE." THEN st.state_name
                       WHEN ced.entity_type = ".SOCIAL_ENTITY_CITY." THEN wgc.name END) AS name
           FROM cms_entity_description ced
           LEFT JOIN cms_countries ct ON ct.id = ced.entity_id AND ced.entity_type = ".SOCIAL_ENTITY_COUNTRY."
           LEFT JOIN states st ON st.id = ced.entity_id AND ced.entity_type = ".SOCIAL_ENTITY_STATE."
           LEFT JOIN webgeocities wgc ON wgc.id = ced.entity_id AND ced.entity_type = ".SOCIAL_ENTITY_CITY."
           WHERE ced.id = $id
           LIMIT 1
          ";
        $res = $this->db->query($query);
        $final = $res->result();
        $ret = array();
        if(count($final) > 0){
            $ret['id'] = $final[0]->id;
            $ret['title'] = $final[0]->title;
            $ret['link'] = $final[0]->link;
            $ret['name'] = $final[0]->name;
            $ret['description'] = $final[0]->description;
            $ret['entity_type'] = $final[0]->entity_type;
            $ret['entity_id'] = $final[0]->entity_id;
        }
        return $ret;
    }
    
    public function update($id, $entity_type, $entity_id, $description, $title, $link){
        $data = array(
            'title' => $title,
            'link' => $link,
            'entity_type' => $entity_type,
            'entity_id' => $entity_id,
            'description' => $description
        );
        $this->db->where('id', $id);
        $result = $this->db->update('cms_entity_description', $data);
        return $result;
    }
    
    public function insert($entity_type, $entity_id, $description, $title, $link){
        $data = array(
            'title' => $title,
            'link' => $link,
            'entity_type' => $entity_type,
            'entity_id' => $entity_id,
            'description' => $description
        );
        $result = $this->db->insert('cms_entity_description', $data);
        return $this->db->insert_id();;
    }
    
    public function delete($id){
        return $this->db->delete('cms_entity_description', array('id' => $id));
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