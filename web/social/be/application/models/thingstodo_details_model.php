<?php
class Thingstodo_Details_Model extends DataMapper {
    var $table = 'cms_thingstodo_details';
    var $has_one = array(
        'thingstodo' => array(
            'class' => 'thingstodo_model',
            'other_field' => 'details',
            'join_self_as' => 'details',
            'join_other_as' => 'parent'
        )
    );
  
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}