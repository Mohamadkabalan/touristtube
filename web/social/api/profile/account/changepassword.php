<?php
/*! \file
 * 
 * \brief This api to change password
 * 
 * \todo <b><i>Change from string to Json object</i></b>
 * 
 * @param S session id
 * @param oldpass old password
 * @param newpass new password
 * 
 * @return string of result if changed or not
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */

//	session_id($_REQUEST['S']); 
//        session_start();
	$bootOptions = array ( "loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);
	
	$expath = '../../';
	include_once("../../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
	//$id = $_SESSION['id'];
//        $id = mobileIsLogged($_REQUEST['S']);
        $id = mobileIsLogged($submit_post_get['S']);
        if( !$id ) die();
//	$old_pass = xss_sanitize($_REQUEST['oldpass']);
//	$new_pass = xss_sanitize($_REQUEST['newpass']);
	$old_pass = $submit_post_get['oldpass'];
	$new_pass = $submit_post_get['newpass'];
	
	if (userPasswordCorrect($id,$old_pass))
	{
		if (userChangePassword($id,$new_pass))
		{
			echo _('Password Changed');	
		}else
		{
			echo _('Unknown error occured');
		}
	}else
	{
		echo _('Wrong old password');	
	}