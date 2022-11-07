<?php
class Poi_Model extends DataMapper {
  var $table = 'discover_poi';
  var    $has_many = array('review'=>
      array('class' => 'poi_review_model',
            'other_field' => 'poi',
            'join_self_as' => 'poi',
            'join_other_as' => 'review',
            'join_table' => 'discover_poi_reviews'), 
        );
  
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}