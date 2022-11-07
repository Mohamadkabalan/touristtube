<?php
class Videos_Catalogs_Model extends DataMapper {
  var $table = 'cms_videos_catalogs';
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}