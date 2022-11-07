<?php
/*! \file
 * 
 * \brief This api returns true/false when unsubscribes one user to another
 * 
 * \todo <b><i>Change from String to Json object</i></b>
 * 
 * @param S session id
 * @param to subscribe To 
 * 
 * @return String true/false if user unsubscribes to another
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */
//	if (isset($_REQUEST['S']))
//	{
//		if (isset($_REQUEST['to']))
$expath = "../";			
//header("content-type: application/xml; charset=utf-8");  
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
	if (isset($submit_post_get['S']))
	{
		if (isset($submit_post_get['to']))
		{
			
//				session_id($_REQUEST['S']); 
//        session_start();
				
				

//				$userId = $_SESSION['id'];
//                                $userId = mobileIsLogged($_REQUEST['S']);
//                                if( !$userId ) die();
//				$subscribeTo = intval($_REQUEST['to']);
                                $userId = mobileIsLogged($submit_post_get['S']);
                                if( !$userId ) die();
				$subscribeTo = intval($submit_post_get['to']);
				
				if (userUnsubscribe($userId,$subscribeTo))
				{
					echo 'true';
				}
				else
				{
					echo 'false';
				}
			
		}
	}