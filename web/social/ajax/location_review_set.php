<?php

	$path = '../';
	$bootOptions = array("loadDb" => 1 , 'requireLogin' => 1);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
	include_once ( $path . "inc/functions/users.php" );
    
	$user_id = userGetID();
	
//	$uid = isset($_POST['uid']) ? intval($_POST['uid']) : 0;
//	$review = xss_sanitize($_POST['review']);
//	$rating = intval($_POST['score']);
	$uid = intval($request->request->get('uid', 0));
	$review = $request->request->get('review', '');
	$rating = intval($request->request->get('score', 0));
	
	$ret = array();
	$stats = locationReviewSet($uid, $user_id, $review, $rating);
	
	if( $stats!= false ){
		$ret['status'] = 'ok';
		$ret['n_review'] = $stats['n_review'];
		$ret['rating'] = $stats['rating'];
	}else{
		$ret['status'] = 'error';
		$ret['msg'] = 'Couldn\'t save review. Please try again later.';
	}
	
	echo json_encode($ret);