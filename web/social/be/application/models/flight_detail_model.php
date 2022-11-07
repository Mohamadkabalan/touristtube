<?php

class Flight_detail_Model extends DataMapper {
  
    var $table = 'flight_detail';
    
    var $has_one = array(
      'pnr'=> array(
          'class' => 'passenger_name_record_model',
              'other_field' => 'flight',
              'join_self_as' => 'flight',
              'join_other_as' => 'pnr',
              'join_table' => 'passenger_name_record')
        );

    function __construct($id = NULL){
        parent::__construct($id);
    }
    
    function post_model_init($from_cache = FALSE){
        
    }

}