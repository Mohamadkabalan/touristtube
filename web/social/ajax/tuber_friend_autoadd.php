<?php
	$path = "../";

    $bootOptions = array("loadDb" => 1 , 'requireLogin' => 1);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
    include_once ( $path . "inc/functions/users.php" );
    
    $user_id = userGetID();
//	$tuber_name = db_sanitize($_POST['ss']);
	$tuber_name = $request->request->get('ss', '');
	
	$ret = array();
	
	$fid = userFindByUsername($tuber_name);
	
	if($fid == false){
		$ret['status'] = 'error';
		$ret['msg'] = _('No such tuber found.');
	}else if($user_id == $fid){
		$ret['status'] = 'error';
		$ret['msg'] = _('Can\'t friend yourself!');
	}else if(userFreindRequestMade($user_id, $fid)){
		$ret['status'] = 'error';
		$ret['msg'] = _('Friend request already sent');
	}else if( userAddFriend($user_id, $fid, '') ){
		$ret['status'] = 'ok';
		$ret['msg'] = _('Friend request sent');
	}else{
		$ret['status'] = 'error';
		$ret['msg'] = _('Couldn\'t process friend request. Please Try again later');
	}
	
	echo json_encode($ret);
