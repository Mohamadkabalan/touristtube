<?php
class Hotels_Selections_Model extends DataMapper {
	var $table = 'cms_hotels_selections';  
	function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }
}