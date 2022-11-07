<?php
/*! \file
 * 
 * \brief This api returns true/false when user remove favourites to a video
 * 
 * \todo <b><i>Change from String to Json object</i></b>
 * 
 * @param S session id
 * @param vid video id 
 * 
 * @return String removed/error if user remove favourites to a video
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */
$expath = "../";
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//if (isset($_REQUEST['S']))
//{
if (isset($submit_post_get['S']))
{
//	session_id($_REQUEST['S']); 
//        session_start();
				
	//header("content-type: application/xml; charset=utf-8");  
	
	
//	$id = $_SESSION['id'];
//        $id = mobileIsLogged($_REQUEST['S']);
//        if( !$id ) die();
//	$vid = $_REQUEST['vid'];
        $id = mobileIsLogged($submit_post_get['S']);
        if( !$id ) die();
	$vid = $submit_post_get['vid'];
	if (userFavoriteDelete($id, $vid))
	{
		echo _('removed');	
	}else
	{
		echo _('error');	
	}
}