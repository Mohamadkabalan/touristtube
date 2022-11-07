<?php
/*! \file
 * 
 * \brief This api adds comment
 * 
 * \todo <b><i>convert from string to JSON object</i></b>
 * 
 * @param S session id
 * @param vid user video id
 * @param txt comment text
 * 
 * @return String either 'error' for any error or 'done' if succeeded
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */
$expath = "../";			
		//header("content-type: application/xml; charset=utf-8");  
		include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//	if (isset($_REQUEST['S']))
	if (isset($submit_post_get['S']))
	{
//		session_id($_REQUEST['S']); 
//        session_start();
//		$id = $_SESSION['id'];
//                $userID = mobileIsLogged($_REQUEST['S']);
                $userID = mobileIsLogged($submit_post_get['S']);
                if( !$userID ) { echo 'error'; die(); }
//		if (isset($_REQUEST['vid']))
//		{
//			if (is_numeric($_REQUEST['vid']))
//			{
//				if (isset($_REQUEST['txt']))
		if (isset($submit_post_get['vid']))
		{
			if (is_numeric($submit_post_get['vid']))
			{
				if (isset($submit_post_get['txt']))
				{
//					if(commentAdd($userID,$_REQUEST['vid'],$_REQUEST['txt'])===false)
					if(commentAdd($userID,$submit_post_get['vid'],$submit_post_get['txt'])===false)
					{
						echo 'error';
					}else
					{
						echo 'done';	
					}
				}
				
			}
		}

	}