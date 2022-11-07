<?php
class Cms_Allcategories_Model extends DataMapper {
  var $table = 'cms_allcategories';
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}