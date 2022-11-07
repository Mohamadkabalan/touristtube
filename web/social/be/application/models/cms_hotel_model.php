<?php
class Cms_Hotel_Model extends DataMapper {
    var $table = 'cms_hotel';
    var $has_many = array(
        'facility' => array(			
            'class' => 'cms_facility_model',
            'other_field' => 'hotel',
            'join_self_as' => 'hotel',
            'join_other_as' => 'facility',
            'join_table' => 'cms_hotel_facility'),
        'amenity' => array(			
            'class' => 'cms_amenity_model',
            'other_field' => 'hotel',
            'join_self_as' => 'hotel',
            'join_other_as' => 'amenity',
            'join_table' => 'cms_hotel_amenity'),
        'image' => array(
            'class' => 'cms_hotel_image_model',
            'other_field' => 'hotel',
            'join_self_as' => 'hotel',
            'join_other_as' => 'image'
        )
    );
    
    var $has_one = array(
        'webgeocity' => array(
            'class' => 'webgeocity_model',
            'other_field' => 'hotels',
            'join_self_as' => 'hotels',
            'join_other_as' => 'city'
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