<?php
	
	$path = '../';
	$bootOptions = array("loadDb" => 1 , 'requireLogin' => 1);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
	include_once ( $path . "inc/functions/users.php" );
	$user_id = userGetID();
//	$loc_id = isset($_POST['uid']) ? intval($_POST['uid']) : 0;
	$loc_id = intval($request->request->get('uid', 0));
	$ret = array();
	$row = locationReviewGet($loc_id, $user_id);
	
	if($row !== false){
		$ret['status'] = 'ok';
		$ret['rating'] = $row['rating'];
		$ret['review'] = $row['review'];
	}else{
		$ret['status'] = 'none';
	}
	echo json_encode($ret);