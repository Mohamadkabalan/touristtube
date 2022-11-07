<?php
$submit_post_get = array_merge($request->query->all(),$request->request->all());

//	$username = isset($_REQUEST['username']) ? db_sanitize($_REQUEST['username']) : null;
//	$password = isset($_REQUEST['password']) ? db_sanitize($_REQUEST['password']) : null;
	$username = isset($submit_post_get['username']) ? $submit_post_get['username'] : null;
	$password = isset($submit_post_get['password']) ? $submit_post_get['password'] : null;
	if( is_null($username) ){
		$ret_arr['status'] = 'error';
		$ret_arr['error_msg'] = 'Missing username';
	}else if( is_null($password) ){
		$ret_arr['status'] = 'error';
		$ret_arr['error_msg'] = 'Missing password';
	}else if( ($row = userLogin($username, $password, CLIENT_WEB)) == false ){
		$ret_arr['status'] = 'error';
		$ret_arr['error_msg'] = 'Invalid Login';
	}else{

		userSetSession($row);
		
		$ret_arr['status'] = 'ok';
	}
?>
