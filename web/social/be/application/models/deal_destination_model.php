<?php
class Deal_Destination_Model extends DataMapper {
  var $table = 'deal_destination';
  
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}