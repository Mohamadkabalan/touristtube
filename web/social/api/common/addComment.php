<?php
/*
* Returns the events sponsored by a given channel.
* param S: The session id.
* param fk: The foreign key (item id).
* param [cid]: The channel id (needed in all channel pages, not needed in tt pages).
* param entity: The entity type.
* param comment: The comment text.
*
* Returns the new comment's ID on success, 0 on failure.
*/


//	session_id($_REQUEST['S']); 
//        session_start();
	
	$expath = "../";			
//	header("content-type: application/xml; charset=utf-8");  
	include("../heart.php");
	
//	$user_id = $_SESSION['id'];
//        $user_id = mobileIsLogged($_REQUEST['S']);
$submit_post_get = array_merge($request->query->all(),$request->request->all());
        $user_id = mobileIsLogged($submit_post_get['S']);
        if( !$user_id ) die();
	
//	$user_id = intval( $_REQUEST['uid'] );
//	$fk = intval( $_REQUEST['fk'] );
//	if($_REQUEST['cid'])
//		$channel_id = intval( $_REQUEST['cid'] );
	$fk = intval( $submit_post_get['fk'] );
	if($submit_post_get['cid'])
		$channel_id = intval( $submit_post_get['cid'] );
	else
		$channel_id = 0;
//	$entity_type = xss_sanitize( $_REQUEST['entity'] );
//	$comment = xss_sanitize( $_REQUEST['comment'] );
	$entity_type = $submit_post_get['entity'] ;
	$comment = $submit_post_get['comment'] ;
	
	$comment_id = socialCommentAdd($user_id, $fk, $entity_type, $comment, $channel_id);
	if($comment_id)
		$status = $comment_id;
	else
		$status = 0;
	
	// Start the XML section.
	$output = $status;
			
	
	
	echo $output;