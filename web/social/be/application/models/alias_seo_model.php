<?php
class Alias_Seo_Model extends DataMapper {
	var $table = 'alias_seo';  
	function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }
}