<?php
//if (isset($_REQUEST['S']))
$submit_post_get = array_merge($request->query->all(),$request->request->all());
if (isset($submit_post_get['S']))
{
//	session_id($_REQUEST['S']); 
//        session_start();
	$expath = "../";			
	//header("content-type: application/xml; charset=utf-8");  
	include("../heart.php");
	
	//$id = $_SESSION['id'];
//        $id = mobileIsLogged($_REQUEST['S']);
        $id = mobileIsLogged($submit_post_get['S']);
        if( !$id ) die();
//	$uid = xss_sanitize($_REQUEST['uid']);
	$uid = xss_sanitize($submit_post_get['uid']);
	if (userFavoriteUserDelete($id, $uid))
	{
		echo 'removed';	
	}else
	{
		echo 'error';	
	}
}