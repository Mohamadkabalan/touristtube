<?php
class Cms_Channel_Category_Model extends DataMapper {
  var $table = 'cms_channel_category';
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}