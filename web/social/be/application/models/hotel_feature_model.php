<?php
class Hotel_Feature_Model extends DataMapper {
 var $table = 'discover_hotels_feature';
  var $has_many = array(
        'hotel' => array(	
            'class' => 'hotel_model',
            'other_field' => 'hotel_feature',
            'join_self_as' => 'hotel_feature',
            'join_other_as' => 'hotel',	
            'join_table' => 'discover_hotels_feature_to_hotel')
    );
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }

}