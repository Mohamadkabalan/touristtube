<?php

	$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
	include_once ( $path . "inc/common.php" );
	include_once ( $path . "inc/bootstrap.php" );
	include_once ( $path . "inc/functions/users.php" );
	$arg = UriGetArg(0);
	$decoded = base64_decode($arg);
	$decoded = json_decode($decoded,true);
	$user_id = $decoded['uid'];
	$code = $decoded['code'];
	if(userSecretCommit($user_id, $code) ){	
		$uinfo = getUserInfo($user_id);
		$username = $uinfo['YourUserName'];	
		userLogin($username, $code, CLIENT_WEB);	
		userSetSession($uinfo);	
		header('Location: ' . ReturnLink('') );
	}