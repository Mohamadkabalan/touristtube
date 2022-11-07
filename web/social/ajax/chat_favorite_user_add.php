<?php

	$path = "../";

    $bootOptions = array("loadDb" => 1 , 'requireLogin' => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
    include_once ( $path . "inc/functions/users.php" );
	include_once ( $path . "inc/functions/videos.php" );
	
	$userID = userGetID();
//	$fav_id = intval($_POST['fav_id']);
	$fav_id = intval($request->request->get('fav_id', 0));
	$ret = array();
	
	if( !userIsLogged() ){
		$ret['status'] = 'error';
		$ret['type'] = 'session';
		$ret['msg'] = _('Please login to complete this task.');
		echo json_encode($ret);
		exit;
	}
	
	if( userFavoriteUserAdded($userID, $fav_id) ){
		$ret['status'] = 'error';
		$ret['msg'] = _('already a favorite user');
	}else if( userFavoriteUserAdd($userID, $fav_id) ){
		$ret['status'] = 'ok';
		$ret['msg'] = _('user added to favorites');
	}else{
		$ret['status'] = 'error';
		$ret['msg'] = _('Couldn\'t Process Request. Please try again later.');
	}
	
	echo json_encode($ret);