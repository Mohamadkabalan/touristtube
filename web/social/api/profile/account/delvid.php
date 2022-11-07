<?php 
/*! \file
 * 
 * \brief This api to delete a video 
 * 
 * 
 * @param S session id
 * @param vid video id
 * 
 * @return true/false if video is deleted or not
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */
//	session_id($_REQUEST['S']); 
//        session_start();
	//$bootOptions = array ( "loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);
	
	$expath = '../../';
	include_once("../../heart.php");
//	$id = $_SESSION['id'];
//	$id = mobileIsLogged($_REQUEST['S']);
$submit_post_get = array_merge($request->query->all(),$request->request->all());
	$id = mobileIsLogged($submit_post_get['S']);
        if( !$id ) die();
//	$vid = xss_sanitize($_REQUEST['vid']);
	$vid = $submit_post_get['vid'];
	
	$vinfo = getVideoInfo($vid);
	
	echo $vinfo['userid']."-".$id."-";
	if ($vinfo['userid']==$id)
	{
		echo videoDelete($vid);	
	}