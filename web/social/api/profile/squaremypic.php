<?php
/*! \file
 * 
 * \brief This api to change the picture to square image
 * 
 * \todo <b><i>Change from string to Json object</i></b>
 * 
 * @param S session id
 * 
 * @return string that contains the path of the image
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */

//	session_id($_REQUEST['S']);
//	session_start();
	$expath = "../";
	include($expath."heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
	
//	$userID = $_SESSION['id'];
//        $userID = mobileIsLogged($_REQUEST['S']);
        $userID = mobileIsLogged($submit_post_get['S']);
        if( !$userID ) die();
	$userInfo = getUserInfo( $userID ); 

	//$xml_output ="<myChatInfo>";
	if ($userInfo['profile_Pic'] != "")
	{
		echo "media/tubers/" . $userInfo['profile_Pic'];
		//echo "media/tubers/crop_".substr($userInfo['profile_Pic'],0,strlen($userInfo['profile_Pic'])-3)."png";
	}else
	{
		echo "media/tubers/na.png";
	}