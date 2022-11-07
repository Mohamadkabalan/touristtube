<?php
class Thingstodo_Model extends DataMapper {
    var $table = 'cms_thingstodo';
    var $has_many = array(
        'details'=> array(
            'class' => 'thingstodo_details_model',
            'other_field' => 'thingstodo',
            'join_self_as' => 'parent',
            'join_other_as' => 'details')
    );
  
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}