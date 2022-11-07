<?php
class Cms_Hotel_Search_Model extends DataMapper {
    var $table = 'cms_hotel_search';
    var $has_many = array(
        
    );
  
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}