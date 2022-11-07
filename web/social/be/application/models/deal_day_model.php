<?php
class Deal_Day_Model extends DataMapper {
  var $table = 'deal_day';
  
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}