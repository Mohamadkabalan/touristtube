<?php
/*! \file
 * 
 * \brief This api returns point of interest all reviews
 * 
 * \todo <b><i>Change from comma seprated string to Json object and should be like/ remove like instead of upvote/downvote</i></b>
 * 
 * @param S session id
 * @param lid location id
 * @param v vote
 * 
 * @return comma seprated string(if error string "error"):
 * @return <pre> 
 * @return        <b>up_likes</b> likes 
 * @return        <b>down_likes</b> dislikes
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */
//	session_id($_REQUEST['S']); 
$submit_post_get = array_merge($request->query->all(),$request->request->all());
	session_id($submit_post_get['S']); 
        session_start();
	$expath = "../";
	//header("content-type: application/xml; charset=utf-8");  
	include($expath."heart.php");
	$userId = $_SESSION['id'];
//	if((isset($_REQUEST['lid'])) && (isset($_REQUEST['v'])))
	if((isset($submit_post_get['lid'])) && (isset($submit_post_get['v'])))
	{
//		if ( (is_numeric($_REQUEST['lid'])) && (is_numeric($_REQUEST['v'])) )
		if ( (is_numeric($submit_post_get['lid'])) && (is_numeric($submit_post_get['v'])) )
		{
			$vote = 0;
//			if ($_REQUEST['v']==0)
			if ($submit_post_get['v']==0)
			{
				$vote = -1;
			}else
			{
				$vote = 1;
			}
//			if(locationLike($_REQUEST['lid'],$userId,$vote))
			if(locationLike($submit_post_get['lid'],$userId,$vote))
			{
//				$vii = locationGet($_REQUEST['lid']);
				$vii = locationGet($submit_post_get['lid']);
				echo  $vii['up_likes'].",".$vii['down_likes'];
			}else
			{
				echo 'error';
			}
		}
	}