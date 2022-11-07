<?php
class Global_Restaurant_Model extends DataMapper {
  var $table = 'global_restaurants';
  var    $has_many = array(
            'review'=> array(
                'class' => 'restaurant_review_model',
                'other_field' => 'restaurant',
                'join_self_as' => 'restaurant',
                'join_other_as' => 'review',
                'join_table' => 'discover_restaurants_reviews'), 
            'cuisine' => array(
                'class' => 'cuisine_model',
                'other_field' => 'restaurant',
                'join_self_as' => 'restaurant',
                'join_other_as' => 'cuisine',
                'join_table' => 'discover_restaurants_cuisine'
            )
        );
  var $has_one = array(
        'webgeocity' => array(
            'class' => 'webgeocity_model',
            'other_field' => 'restaurant',
            'join_self_as' => 'restaurant',
            'join_other_as' => 'city'
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