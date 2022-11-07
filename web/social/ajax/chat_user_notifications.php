<?php
	$path = "../";

    $bootOptions = array("loadDb" => 1 , 'requireLogin' => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );

    include_once ( $path . "inc/functions/videos.php" );
	include_once ( $path . "inc/functions/users.php" );
	
	$ret = array();
	
	if( !userIsLogged() ){
		$ret['status'] = 'error';
		$ret['type'] = 'session';
		$ret['msg'] = _('Please login to complete this task.');
		echo json_encode($ret);
		exit;
	}
	
	$user_id = userGetID();
//	$friend_id = intval($_POST['friend_id']);
//	$notification = intval($_POST['notification']);
	$friend_id = intval($request->request->get('friend_id', 0));
	$notification = intval($request->request->get('notification', 0));
	
	// 0 removed, 1 added
	
	if( userFriendNotificationSet ($user_id, $friend_id, $notification) ){
		
		$ret['status'] = 'ok';
		
		if($notification == 0)
		{
			$ret['msg'] = _('Friend has been removed');
		}else if($notification == 1){	
			$ret['msg'] = _('Friend has been added');	
		}
		
	}else{
		$ret['status'] = 'error';
		$ret['msg'] = _('Couldn\'t process request. Please try again later');
	}
	
	echo json_encode($ret);