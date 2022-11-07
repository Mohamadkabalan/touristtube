<?php
class Payment_Model extends DataMapper {

    var $table = 'payment';
        
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}