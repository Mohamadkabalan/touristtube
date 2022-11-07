<?php
	 
//        session_start();
$expath = "../";
$submit_post_get = array_merge($request->query->all(),$request->request->all());	
include($expath."heart.php");	
//$userID = $_SESSION['id'];	
//$userID = mobileIsLogged($_REQUEST['S']);
$userID = mobileIsLogged($submit_post_get['S']);
//if( !$userID ) die();
echo $userID;