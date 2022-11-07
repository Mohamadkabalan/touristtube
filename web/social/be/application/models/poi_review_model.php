<?php
class Poi_Review_Model extends DataMapper {
  var $table = 'discover_poi_reviews';
  var    $has_one = array('poi'=>
      array('class' => 'poi_model',
            'other_field' => 'review',
            'join_self_as' => 'review',
            'join_other_as' => 'poi',
            'join_table' => 'discover_poi'));
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }
}