<?php
class Deal_Option_Model extends DataMapper {
  var $table = 'deal_option';
  
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}