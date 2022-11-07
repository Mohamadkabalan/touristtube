<?php
class Landmark_Model extends DataMapper {
  var $table = 'landmarks';
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }
}