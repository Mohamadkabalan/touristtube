<?php
class Room_Model extends DataMapper {
  var $table = 'discover_hotels_rooms';
  var    $has_one = array('hotel'=>
      array('class' => 'hotel_model',
            'other_field' => 'room',
            'join_self_as' => 'room',
            'join_other_as' => 'hotel',
            'join_table' => 'discover_hotels'));
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }
}