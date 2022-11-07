<?php
/*! \file
 * 
 * \brief This api returns if the rate on a video succeded or not
 * 
 * \todo <b><i>Change from String to Json object</i></b>
 * 
 * @param S session id
 * @param vid video id
 * @param rate video rate
 * 
 * @return String if succeeded("ok") or not("error")
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */

$submit_post_get = array_merge($request->query->all(),$request->request->all());
//	if (isset($_REQUEST['S']))
	if (isset($submit_post_get['S']))
	{
//		session_id($_REQUEST['S']); 
//              session_start();
		$expath = "../";
		require_once($expath."heart.php");

//		$userID = $_SESSION['id'];
//                $userID = mobileIsLogged($_REQUEST['S']);
//                if( !$userID ) { echo "Error"; die(); }
//		$vid = intval($_REQUEST['vid']);
//		$rating = $_REQUEST['rate'];
                $userID = mobileIsLogged($submit_post_get['S']);
                if( !$userID ) { echo "Error"; die(); }
		$vid = intval($submit_post_get['vid']);
		$rating = $submit_post_get['rate'];
		if($vid == 0) die('');
		
		$row = videoUserRatingSet($vid, $userID, $rating);
	
		if($row === false)
		{
			echo _("Error");
		}
		else
		{
			echo _("ok");
		}
	}
	else
	{
		echo _("Error");
	}
