<?php
class Deal_Hotel_Room_Model extends DataMapper {
  var $table = 'deal_hotel_room';
  
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}