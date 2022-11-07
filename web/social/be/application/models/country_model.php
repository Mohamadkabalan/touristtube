<?php
class Country_Model extends DataMapper {
  var $table = 'cms_countries';
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}