<?php

	$path = '../';
	$bootOptions = array("loadDb" => 1 , 'requireLogin' => 0);
        include_once ( $path . "inc/common.php" );
        include_once ( $path . "inc/bootstrap.php" );
	include_once ( $path . "inc/functions/users.php" );
    
//	$latitude =  floatval($_POST['latitude']);
//	$longitude =  floatval($_POST['longitude']);
	$latitude =  floatval($request->request->get('latitude', 0));
	$longitude =  floatval($request->request->get('longitude', 0));
	
	$uid =  $_COOKIE["lt"];
	$user_id = userIsLogged() ? userGetID() : null;
	
	userSetLocation($uid, $user_id, $latitude, $longitude);