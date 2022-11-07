<?php
//	session_id($_REQUEST['S']); 
//        session_start();
	$bootOptions = array ( "loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);
	
	$expath = '../../';
	include_once("../../heart.php");
//	$id = $_SESSION['id'];
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//	$id = mobileIsLogged($_REQUEST['S']);
	$id = mobileIsLogged($submit_post_get['S']);
        if( !$id ) die();
	$new_username = $_REQUEST['new_username'];
	
	if (userNameisUnique($id,$new_username))
	{
		echo _('Valid');	
	}else
	{
		echo _('Invalid');	
	}