<?php
class Ml_Videos_Model extends DataMapper{
    var $table = 'ml_videos';
    var $has_one = array(
        'media' => array(
            'class' => 'media_model',
            'other_field' => 'ml_videos',
            'join_self_as' => 'ml_videos',
            'join_other_as' => 'video'
        )
    );
    function __construct($id = NULL)
    {
        parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }
}