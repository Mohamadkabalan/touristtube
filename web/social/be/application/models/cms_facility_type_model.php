<?php
class Cms_Facility_Type_Model extends DataMapper {
    var $table = 'cms_facility_type';
  
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}