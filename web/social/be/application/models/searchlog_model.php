<?php
class Searchlog_Model extends DataMapper {
  var $table = 'cms_search_log';
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}