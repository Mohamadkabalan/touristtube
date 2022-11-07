<?php
class WebGeoCity_Model extends DataMapper {
  var $table = 'webgeocities';
  var $has_many = array(
        'hotel' => array(
            'class' => 'hotel_model',
            'other_field' => 'webgeocity',
            'join_self_as' => 'city',
            'join_other_as' => 'hotel'
        ),
        'poi' => array(
            'class' => 'poi_model',
            'other_field' => 'webgeocity',
            'join_self_as' => 'city',
            'join_other_as' => 'poi'
        ),
        'restaurant' => array(
            'class' => 'global_restaurant_model',
            'other_field' => 'webgeocity',
            'join_self_as' => 'city',
            'join_other_as' => 'restaurant'
        ),
        'hotels' => array(
            'class' => 'cms_hotel_model',
            'other_field' => 'webgeocity',
            'join_self_as' => 'city',
            'join_other_as' => 'hotels'
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