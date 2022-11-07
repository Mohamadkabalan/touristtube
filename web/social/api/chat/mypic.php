<?php
 
//        session_start();
	
$expath = "../";
include($expath."heart.php");

//$userID = $_SESSION['id'];
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//$userID = mobileIsLogged($_REQUEST['S']);
$userID = mobileIsLogged($submit_post_get['S']);
if( !$userID ) die();
$userInfo = getUserInfo( $userID );

//$xml_output ="<myChatInfo>";
if ($userInfo['profile_Pic'] != "")
{
    echo "media/tubers/crop_".substr($userInfo['profile_Pic'],0,strlen($userInfo['profile_Pic'])-3)."png";
}else
{
    echo "media/tubers/na.png";
}