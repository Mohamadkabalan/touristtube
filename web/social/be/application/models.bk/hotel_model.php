<?php
class Hotel_Model extends DataMapper {
  var $table = 'discover_hotels';
  var    $has_many = array('room'=>
      array('class' => 'room_model',
            'other_field' => 'hotel',
            'join_self_as' => 'hotel',
            'join_other_as' => 'room',
            'join_table' => 'discover_hotels_rooms'), 'review'=>
      array('class' => 'review_model',
            'other_field' => 'hotel',
            'join_self_as' => 'hotel',
            'join_other_as' => 'review',
            'join_table' => 'discover_hotels_reviews'), 
      'facility' => array(			
            'class' => 'facility_model',
            'other_field' => 'hotel',
            'join_self_as' => 'hotel',
            'join_other_as' => 'facility',
            'join_table' => 'discover_hotels_facilities')
          );
  
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}