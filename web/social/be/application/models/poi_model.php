<?php
class Poi_Model extends DataMapper {
    var $table = 'discover_poi';
    var $has_many = array(
            'review' => array(
                'class' => 'poi_review_model',
                'other_field' => 'poi',
                'join_self_as' => 'poi',
                'join_other_as' => 'review',
                'join_table' => 'discover_poi_reviews'), 
            'category' => array(
                'class' => 'category_model',
                'other_field' => 'poi',
                'join_self_as' => 'poi',
                'join_other_as' => 'categ',
                'join_table' => 'discover_poi_categ'
            )
        );
    var $has_one = array(
        'webgeocity' => array(
            'class' => 'webgeocity_model',
            'other_field' => 'poi',
            'join_self_as' => 'poi',
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