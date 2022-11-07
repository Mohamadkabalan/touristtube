<?php
/*! \file
 * 
 * \brief This api returns the average rate of a video
 * 
 * \todo <b><i>Change from String to Json object</i></b>
 * 
 * @param S session id
 * @param vid video id
 * 
 * @return String with the video rating value or "0"
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//	if (isset($_REQUEST['S']))
//	{
	if (isset($submit_post_get['S']))
	{
//		session_id($_REQUEST['S']); 
//                session_start();
	
		$expath = "../";
		require_once($expath."heart.php");
	
//		$userID = $_SESSION['id'];
//                $userID = mobileIsLogged($_REQUEST['S']);
//		$vid = intval($_REQUEST['vid']);
                $userID = mobileIsLogged($submit_post_get['S']);
		$vid = intval($submit_post_get['vid']);
	
		if($vid == 0) die('die');
	
		$row = videoUserRatingGet($vid, $userID);
	
		$ret = "";//array();
	
		if(!$row)
		{
			$ret .= "0";
		}
		else
		{
			//$ret['status'] = 1;
			$ret .= $row;
			//$ret['comment'] = $row['comment'];
		}
	
		echo $ret;
	}
