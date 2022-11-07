<?php
/*
* Returns the events sponsored by a given channel.
* param S: The session id.
* param fk: The foreign key (journal id, event id...).
* param [cid]: The channel id (needed in all channel pages, not needed in tt pages).
* param entity: The entity type.
* param hide: The hide value (0 - 1).
* 
* Returns 1 on success, 0 on failure.
*/


$submit_post_get = array_merge($request->query->all(),$request->request->all());
//	session_id($_REQUEST['S']); 
	session_id($submit_post_get['S']); 
        session_start();
	
	$expath = "../";			
//	header("content-type: application/xml; charset=utf-8");  
	include("../heart.php");
	
	$user_id = $_SESSION['id'];
	
	
//	$user_id = intval( $_REQUEST['uid'] );
//	$fk = intval( $_REQUEST['fk'] );
//	if($_REQUEST['cid'])
//		$channel_id = intval( $_REQUEST['cid'] );
	$fk = intval( $submit_post_get['fk'] );
	if($_REQUEST['cid'])
		$channel_id = intval( $submit_post_get['cid'] );
	else
		$channel_id = null;
//	$entity_type = xss_sanitize( $_REQUEST['entity'] );
//	$hide = intval( $_REQUEST['hide'] );
	$entity_type = $submit_post_get['entity'] ;
	$hide = intval( $submit_post_get['hide'] );
	$status = 0;
	
	
	// Case of a journal (tt page).
	if($entity_type == SOCIAL_ENTITY_JOURNAL){
		$options = array(
						'is_visible' => $hide
						);
		if(journalEditDetails($user_id, $fk, $options))
			$status = 1;
	}
	// Case of a friend (tt page).
	else if($entity_type == SOCIAL_ENTITY_PROFILE_FRIENDS){
		$options = array(
						'receipient_id' => $fk,
						'requester_id' => $user_id,
						'is_visible' => $hide
						);
		if(userFriendEdit($options))
			$status = 1;
	}
	// Case of an event (channel page).
	else if($entity_type == SOCIAL_ENTITY_EVENTS){
		$options = array(
						'id' => $fk,
						'channelid' => $channel_id,
						'is_visible' => $hide
						);
		if(channelEventEdit($options))
			$status = 1;
	}
	// Case of a brochure (channel page).
	else if($entity_type == SOCIAL_ENTITY_BROCHURE){
		$options = array(
						'id' => $fk,
						'channelid' => $channel_id,
						'is_visible' => $hide
						);
		if(channelBrochurelEdit($options))
			$status = 1;
	}
	// Case of a news (channel page).
	else if($entity_type == SOCIAL_ENTITY_NEWS){
		$options = array(
						'id' => $fk,
						'channelid' => $channel_id,
						'is_visible' => $hide
						);
		if(channelNewsEdit($options))
			$status = 1;
	}
	
	
	
	
	
	// Start the XML section.
	$output = $status;
			
	
	
	echo $output;