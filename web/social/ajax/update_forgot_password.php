<?php
    $path = "../";

    $bootOptions = array ( "loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0 );
	include_once ( $path . "inc/common.php" );
	include_once ( $path . "inc/bootstrap.php" );
	include_once ( $path . "inc/functions/users.php" );
    
    $user_id = userGetID();
//	$email = xss_sanitize(@$_POST['email']);
//	$new_pass = db_sanitize($_POST['new_pass']);
	$email = $request->request->get('email', '');
	$new_pass = $request->request->get('new_pass', '');
	
	$ret = array();
	
	$user_info=checkUserEmailMD5( $email );
		
	if( $user_info ){
		if( strlen($new_pass) < 8 ){
			$ret['status'] = 'error';
			$ret['error'] = 'Your password is too short';
			$ret['error_no'] = 2;
		}else if( !preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $new_pass) ){
			$ret['status'] = 'error';
			$ret['error'] = 'Your password is not secure';
			$ret['error_no'] = 2;
		}else if( userChangePassword($user_info['id'], $new_pass) ){
			$ret['status'] = 'ok';                       
		}else{
			$ret['status'] = 'error';
			$ret['error'] = 'Couldnt process. Please try again later';
		}		
	}else{
		$ret['status'] = 'error ';
		$ret['error'] = 'Couldnt process. Please try again later';
	}
	
	echo json_encode($ret);
