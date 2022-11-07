<?php
    $path = "../";

    $bootOptions = array("loadDb" => 1 , 'requireLogin' => true);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );

    include_once ( $path . "inc/functions/users.php" );
    
    $user_id = userGetID();
//    $subscribe_to = intval($_POST['id']);
    $subscribe_to = intval($request->request->get('id', 0));
	
	$ret = array();
	
	if($user_id == $subscribe_to){
		$ret['status'] = 'error';
		$ret['msg'] = _('Cant follow self');
	}
    
	//userAddFriend($user_id, $subscribe_to, "subscribe");
	if( userSubscribe($user_id, $subscribe_to) ){
		$ret['status'] = 'ok';
		$ret['msg'] = _('You are now following this tuber');
	}else{
		$ret['status'] = 'error';
		$ret['msg'] = _('Already following this user');
	}
	
	echo json_encode($ret);