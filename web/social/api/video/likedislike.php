<?php
/*! \file
 * 
 * \brief This api for liking or unliking any video
 * 
 * \todo <b><i>Change from comma separated string to Json object and should be like/ remove like instead of upvote/downvote</i></b>
 * 
 * @param S session id
 * @param vid user video id
 * @param v equals 1 if like 0 for dislike
 * 
 * @return  either 'error' string for any error or a comma separated string
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */

//	session_id($_REQUEST['S']); 
//        session_start();
	
	
	$expath = "../";
	//header("content-type: application/xml; charset=utf-8");  
	header('Content-type: application/json');
	include($expath."heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//	$userId = $_SESSION['id'];
//        $userId = mobileIsLogged($_REQUEST['S']);
        $userId = mobileIsLogged($submit_post_get['S']);
        if( !$userId ) { echo 'error'; die(); }
//	if((isset($_REQUEST['vid'])) && (isset($_REQUEST['v'])))
	if((isset($submit_post_get['vid'])) && (isset($submit_post_get['v'])))
	{
//		if ( (is_numeric($_REQUEST['vid'])) && (is_numeric($_REQUEST['v'])) )
		if ( (is_numeric($submit_post_get['vid'])) && (is_numeric($submit_post_get['v'])) )
		{
			$vote = 0;
//			if ($_REQUEST['v']==0)
			if ($submit_post_get['v']==0)
			{
					$vote = -1;
			}
			else
			{
				$vote = 1;
			}
//			if(videoVote($_REQUEST['vid'],$userId,$vote))
//			{
//				$vii = getVideoInfo($_REQUEST['vid']);
			/*code changed by sushma mishra on 01 oct 2015 to return response in json format starts from here*/	
			if(videoVote($submit_post_get['vid'],$userId,$vote))
			{ 
				$vii = getVideoInfo($submit_post_get['vid']);	
                $ret['up_votes'] = $vii['up_votes'];
				$ret['down_votes'] = $vii['down_votes'];
				$ret['status'] = 'ok';
				//echo  $vii['up_votes'].",".$vii['down_votes'];
			}
			else
			{
				$ret['status'] = 'error';
			}
			echo json_encode($ret);
			/*code changed by sushma mishra on 01 oct 2015 ends here*/
		}
	}