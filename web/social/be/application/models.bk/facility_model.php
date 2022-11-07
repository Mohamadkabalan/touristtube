<?php
class Facility_Model extends DataMapper {
  var $table = 'discover_facilities';
  var $has_many = array(
        'hotel' => array(	
            'class' => 'hotel_model',
            'other_field' => 'facility',
            'join_self_as' => 'facility',
            'join_other_as' => 'hotel',	
            'join_table' => 'discover_hotels_facilities')
    );
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}