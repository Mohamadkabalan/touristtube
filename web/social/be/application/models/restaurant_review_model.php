<?php
class Restaurant_Review_Model extends DataMapper {
  var $table = 'discover_restaurants_reviews';
  var    $has_one = array('restaurant'=>
      array('class' => 'global_restaurant_model',
            'other_field' => 'review',
            'join_self_as' => 'review',
            'join_other_as' => 'restaurant',
            'join_table' => 'discover_restaurants'));
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }
}