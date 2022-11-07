<?php
class Cms_Facility_Model extends DataMapper {
    var $table = 'cms_facility';
    var $has_many = array(
        'hotel' => array(	
            'class' => 'cms_hotel_model',
            'other_field' => 'facility',
            'join_self_as' => 'facility',
            'join_other_as' => 'hotel',	
            'join_table' => 'cms_hotel_facility')
    );
  
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}