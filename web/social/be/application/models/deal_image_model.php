<?php
class Deal_Image_Model extends DataMapper {
  var $table = 'deal_image';
  
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}