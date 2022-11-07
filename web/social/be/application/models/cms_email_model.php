<?php
class Cms_Email_Model extends DataMapper {
    var $table = 'cms_emails';
    
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

    public function persist( $data = array() ){
        
        $this->db->insert('cms_emails', $data);
        
        return $this->db->insert_id();
    }

}