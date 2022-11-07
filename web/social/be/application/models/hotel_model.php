<?php
class Hotel_Model extends DataMapper {
    var $table = 'discover_hotels';
    var $has_many = array(
        'room'=> array(
            'class' => 'room_model',
            'other_field' => 'hotel',
            'join_self_as' => 'hotel',
            'join_other_as' => 'room',
            'join_table' => 'discover_hotels_rooms'), 
        'review'=> array(
            'class' => 'review_model',
            'other_field' => 'hotel',
            'join_self_as' => 'hotel',
            'join_other_as' => 'review',
            'join_table' => 'discover_hotels_reviews'), 
        'hotel_feature' => array(			
            'class' => 'hotel_feature_model',
            'other_field' => 'hotel',
            'join_self_as' => 'hotel',
            'join_other_as' => 'hotel_feature',
            'join_table' => 'discover_hotels_feature_to_hotel')
    );
    var $has_one = array(
        'webgeocity' => array(
            'class' => 'webgeocity_model',
            'other_field' => 'hotel',
            'join_self_as' => 'hotel',
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