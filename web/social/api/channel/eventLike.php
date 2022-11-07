<?php
/*
* Returns the events sponsored by a given channel.
* param s: The session id.
* param eid: The event id.
* param cid: The channel_id.
* param like_value: Like or unlike. 1 / -1.
* 
* Returns 1 on success, 0 on failure.
*/
	
	$expath = "../";			
	header("content-type: application/xml; charset=utf-8");  
	include("../heart.php");
	
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//	session_id($_REQUEST['S']); 
	session_id($submit_post_get['S']); 
        session_start();
	$user_id = $_SESSION['id'];
	
//	$user_id = intval( $_REQUEST['uid'] );
//	$event_id = intval( $_REQUEST['eid'] );
//	$channel_id = intval( $_REQUEST['cid'] );
//	$like_value = intval( $_REQUEST['like_value'] );
	$event_id = intval( $submit_post_get['eid'] );
	$channel_id = intval( $submit_post_get['cid'] );
	$like_value = intval( $submit_post_get['like_value'] );
	$status = 0;
	
	
	// Like.
	if($like_value == 1){
		// If t he user has not yet liked this item.
		if( !socialLiked ($user_id, $event_id, SOCIAL_ENTITY_EVENTS) ){
			if( socialLikeAdd ($user_id, $event_id, SOCIAL_ENTITY_EVENTS, 1, $channel_id) ){
				$status = 1;
			}
		}
	}
	// Unlike.
	else{
		// Get the like ID.
		$res = socialLikeRecordGet ($user_id, $event_id, SOCIAL_ENTITY_EVENTS);
		if($res){
			if( socialLikeDelete ($res['id']) ){
				$status = 1;
			}
		}
	}
	
	// Start the XML section.
	$output .= "<status>" . $status . "</status>";
	echo $output;