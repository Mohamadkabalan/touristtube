<?php
class Category_Model extends DataMapper {
  var $table = 'discover_categs';
  var $has_many = array(
        'poi' => array(	
            'class' => 'poi_model',
            'other_field' => 'categ',
            'join_self_as' => 'categ',
            'join_other_as' => 'poi',	
            'join_table' => 'discover_poi_categ')
    );
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}