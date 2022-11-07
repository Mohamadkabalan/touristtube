<?php
/*! \file
 * 
 * \brief This api returns comments for a specific event.
 * 
 * 
 * @param id event id
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * 
 * @return <b>output</b> JSON list with the following keys:
 * @return <pre> 
 * @return       <b>event_comment_count</b> event number of comments 
 * @return       <b>event</b> List with the following keys:
 * @return       		<b>id</b> event id
 * @return       		<b>user_id</b> event user id
 * @return       		<b>username</b> event user name
 * @return       		<b>user</b> event user full name
 * @return       		<b>display_fullname</b> event display user full name or not 
 * @return       		<b>userprofilepic</b> event user profile picture path
 * @return       		<b>text</b> event comment text
 * @return       		<b>date</b> event comment date added
 * @return       		<b>published</b> event comment published or not
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */
/*
* Returns the comments for a specific event.
* Param id: The event id
* Param [limit]: Optional, the max rows to get, default 100.
* Param [page]: Optional, the current page.
*/
	
	$expath = "../";			
	header('Content-type: application/json');
	include("../heart.php");
	
//	$id = intval( $_REQUEST['id'] );
//	if(isset($_REQUEST['limit']))
//		$limit = intval( $_REQUEST['limit'] );
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
	$id = intval( $submit_post_get['id'] );
	if(isset($submit_post_get['limit']))
		$limit = intval( $submit_post_get['limit'] );
	else
		$limit = 100;
//	if(isset($_REQUEST['page']))
//		$page = intval( $_REQUEST['page'] );
	if(isset($submit_post_get['page']))
		$page = intval( $submit_post_get['page'] );
	else
		$page = 0;
	
	
	$options = array(
                        'page' => $page,
                        'limit' => $limit,
                        'orderby' => 'comment_date',
                        'order' => 'd',
                        'entity_id' => $id,
                        'published' => 1,
                        'is_visible' => 1,
                        'entity_type' => SOCIAL_ENTITY_EVENTS
                        );
	$allComments = socialCommentsGet($options);
	if($allComments)
		$comments_count = count($allComments);
	else
		$comments_count = 0;
	
	if($comments_count > 0):

		// Fill in the details for every comment.
		foreach($allComments as $comment):
		/*code added by sushma mishra on 30-sep-2015 to get userinfo for further usage starts from here*/
			$userDetail = getUserInfo($comment['user_id']);
		/*code added by sushma mishra on 30-sep-2015 ends here*/
			$event[]= array(
                                    'id'=>$comment['id'],
                                    'user_id'=>$comment['user_id'],
                                    'username'=>$comment['YourUserName'],
			/*code added by sushma mishra on 30-sep-2015 to get fullname using returnUserDisplayName function starts from here*/
                                    //'fullname'=>htmlEntityDecode($comment['FullName']),
									'fullname'=>returnUserDisplayName($userDetail),
			/*code added by sushma mishra on 30-sep-2015 ends here*/
                                    'display_fullname'=>$comment['display_fullname'],
                                    'userprofilepic'=>"media/tubers/" . $comment['profile_Pic'],
                                    'text'=>htmlEntityDecode($comment['comment_text']),
                                    'date'=>returnSocialTimeFormat($comment['comment_date'], 1),
                                    'published'=>$comment['published'],
                                    );
		endforeach;
	endif;
        
    $result = array();
    $result =array(
            'event_comment_count'=>$comments_count,
            'event'=>$event,
    );
    
   echo json_encode($result);
