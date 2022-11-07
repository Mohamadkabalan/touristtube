<?php
class Media_Model extends DataMapper{
    var $table = 'cms_videos';
    var $has_many = array(
        'ml_videos' => array(
            'class' => 'ml_videos_model',
            'other_field' => 'media',
            'join_self_as' => 'video',
            'join_other_as' => 'ml_videos'
        ),
      
        
        
    );
    function __construct($id = NULL)
    {
        parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }
}