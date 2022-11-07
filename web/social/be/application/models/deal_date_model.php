<?php
class Deal_Date_Model extends DataMapper {
  var $table = 'deal_date';
  
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}