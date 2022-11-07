<?php

/**
 * Passenger Name Record model Entity
 */
class Passenger_name_record_Model extends DataMapper {
    var $table = 'passenger_name_record';

	var $has_many = array(
		'flight' => array(
			'class' => 'flight_detail_Model',
			'other_field' => 'pnr',
			'join_self_as' => 'pnr',
			'join_other_as' => 'flight',
			'join_table' => 'flight_detail'
		),
		'detail' => array(
			'class' => 'passenger_detail_Model',
			'other_field' => 'pnr',
			'join_self_as' => 'pnr',
			'join_other_as' => 'detail',
			'join_table' => 'passenger_detail'
		)
	);

	var $has_one = array(
        'flighinfo' => array(
            'class' => 'flight_info_model',
            'other_field' => 'pnr',
            'join_self_as' => 'pnr',
            'join_other_as' => 'flightinfo'
        )     
    );

    function __construct($id = NULL){
        parent::__construct($id);
    }
    
    function post_model_init($from_cache = FALSE){

    }

}