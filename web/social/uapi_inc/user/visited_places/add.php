<?php
//	$location = xss_sanitize($_POST['location']);
//	$longitude = isset($_POST['longitude']) ? doubleval($_POST['longitude']) : 0;
//	$lattitude = isset($_POST['lattitude']) ? doubleval($_POST['lattitude']) : 0;
	$location = $request->request->get('location', '');
	$longitude = doubleval($request->request->get('longitude', 0));
	$lattitude = doubleval($request->request->get('lattitude', 0));
	$user_id = userGetID();

	if( $location_id=userVisitedPlacesAdd($user_id,$location,$longitude,$lattitude) ){
		
		$ret_arr['status'] = 'ok';
		$ret_arr['id'] = $location_id;
	}else{
		$ret_arr['status'] = 'error';
		$ret_arr['msg'] = _('Couldn\'t save the information. Please try again later.');
	}
	
?>
