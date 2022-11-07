<?php
class Airport_Model extends DataMapper {
    var $table = 'airport';
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}