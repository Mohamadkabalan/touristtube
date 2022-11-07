<?php
class Cuisine_Model extends DataMapper {
  var $table = 'discover_cuisine';
  var $has_many = array(
        'restaurant' => array(	
            'class' => 'global_restaurant_model',
            'other_field' => 'cuisine',
            'join_self_as' => 'cuisine',
            'join_other_as' => 'restaurant',	
            'join_table' => 'discover_restaurants_cuisine')
    );
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}