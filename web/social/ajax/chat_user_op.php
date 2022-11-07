<?php
    $path = "../";

    $bootOptions = array("loadDb" => 1 , 'requireLogin' => 1);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );

    include_once ( $path . "inc/functions/users.php" );
    
    $user_id = userGetID();
	
	$ret = array();
	
//	$op = isset($_POST['op']) ? $_POST['op'] : null;
//	$fid = isset($_POST['fid']) ? $_POST['fid'] : null;
	$op = $request->request->get('op', null);
	$fid = $request->request->get('fid', null);
	
	if($fid != null){
	
		switch($op){
			case 'accept_frnd':
				if( userAcceptFriendRequest($user_id, $fid)){
					$ret['status'] = 'ok';
				}else{
					$ret['status'] = 'error';
					$ret['msg'] = "Couldn't process. please try again later.";
				}
				break;
			case 'rjct_frnd':
				if( userRejectFriendRequest($user_id, $fid)){
					$ret['status'] = 'ok';
				}else{
					$ret['status'] = 'error';
					$ret['msg'] = "Couldn't process. please try again later.";
				}
				break;
			case 'block_frnd':
				if( userBlockFriend($user_id, $fid) ){
					$ret['status'] = 'ok';
				}else{
					$ret['status'] = 'error';
					$ret['msg'] = "Couldn't process. please try again later.";
				}
				break;
			case 'unblock_frnd':
				if( userUnblockFriend($user_id, $fid)){
					$ret['status'] = 'ok';
				}else{
					$ret['status'] = 'error';
					$ret['msg'] = "Couldn't process. please try again later.";
				}
				break;
			default:
				$ret['status'] = 'error';
				$ret['msg'] = 'Invalid operation';
				break;
		}
		
	}
	
	echo json_encode($ret);