<?php
	
	
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//	if (isset($_REQUEST['S']) && (isset($_REQUEST['fid'])) && (isset($_REQUEST['msg'])) )
	if (isset($submit_post_get['S']) && (isset($submit_post_get['fid'])) && (isset($submit_post_get['msg'])) )
	{
//		$frndid = $_REQUEST['fid'];
//		$msg = $_REQUEST['msg'];
//			
//		session_id($_REQUEST['S']); 
		$frndid = $submit_post_get['fid'];
		$msg = $submit_post_get['msg'];
			
		session_id($submit_post_get['S']); 
        session_start();
		
		$expath = "../";
		
		include($expath."heart.php");
		include($path."services/lib/chat.inc.php");
		
		$userID = $_SESSION['id'];
		//echo $userID;
		if (userAddFriend($userID,$frndid,$msg))
		{
			echo 'true';	
		}else
		{
			echo 'false';	
		}
	}