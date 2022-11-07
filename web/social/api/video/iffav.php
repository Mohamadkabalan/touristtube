<?php
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//if (isset($_REQUEST['S']))
if (isset($submit_post_get['S']))
{
//	session_id($_REQUEST['S']); 
//        session_start();
	$expath = "../";			
	//header("content-type: application/xml; charset=utf-8");  
	include("../heart.php");
	
//	$id = $_SESSION['id'];
//        $id = mobileIsLogged($_REQUEST['S']);
//	$vid = $_REQUEST['vid'];
        $id = mobileIsLogged($submit_post_get['S']);
	$vid = $submit_post_get['vid'];
	if (userFavoriteAdded($id, $vid))
	{
		echo 'YES';	
	}else
	{
		echo 'NO';	
	}
}