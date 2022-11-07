<?php

class Passenger_detail_Model extends DataMapper {
  
    var $table = 'passenger_detail';
    
    var $has_one = array(
      'pnr'=> array(
          'class' => 'passenger_name_record_model',
              'other_field' => 'detail',
              'join_self_as' => 'detail',
              'join_other_as' => 'pnr',
              'join_table' => 'passenger_name_record')
        );

    function __construct($id = NULL){
        parent::__construct($id);
    }
    
    function post_model_init($from_cache = FALSE){
        
    }

}