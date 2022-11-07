<?php

class Flight_info_Model extends DataMapper {
  
    var $table = 'flight_info';
    
    var $has_one = array(
      'pnr'=> array(
          'class' => 'passenger_name_record_model',
              'other_field' => 'flightinfo',
              'join_self_as' => 'flightinfo',
              'join_other_as' => 'pnr',
              'join_table' => 'passenger_name_record')
        );

    function __construct($id = NULL){
        parent::__construct($id);
    }
    
    function post_model_init($from_cache = FALSE){
        
    }

}