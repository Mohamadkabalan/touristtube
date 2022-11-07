<?php
class Cms_Amenity_Model extends DataMapper {
    var $table = 'cms_amenity';
    var $has_many = array(
        'hotel' => array(	
            'class' => 'cms_hotel_model',
            'other_field' => 'amenity',
            'join_self_as' => 'amenity',
            'join_other_as' => 'hotel',	
            'join_table' => 'cms_hotel_amenity')
    );
  
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}