<?php
class Restaurant_Model extends DataMapper {
  var $table = 'discover_restaurants';
  var    $has_many = array('review'=>
      array('class' => 'restaurant_review_model',
            'other_field' => 'restaurant',
            'join_self_as' => 'restaurant',
            'join_other_as' => 'review',
            'join_table' => 'discover_restaurants_reviews'), 
        );
  
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}