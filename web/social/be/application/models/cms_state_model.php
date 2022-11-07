<?php
class Cms_State_Model extends DataMapper {
  var $table = 'states';
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}