<?php
/*
* Returns the events sponsored by a given channel.
* param S: The session id.
* param fk: The foreign key (journal id, event id, friend id...).
* param [cid]: The channel id (needed in all channel pages, not needed in tt pages).
* param [entity]: The entity type, used when the item to be deleted has a preset entity type.
* param [secent]: A secondary entity type, as preset string, to be used where the entity is not defined (check below for possible values).
* 
* Returns 1 on success, 0 on failure.
*/

/*
Possible values for secent:
userfavoritedelete		: Deletes a favorite from the user's list of favorites.
channelremovesponsor	: Deletes a sponsor the channel has.
channelremoveconnection	: Deletes a channel connection.
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
//	else
//		$channel_id = null;
//	$entity_type = xss_sanitize( @$_REQUEST['entity'] );
//	$secondary_entity_type = strtolower( xss_sanitize( @$_REQUEST['secent'] ) );
	$fk = intval( $submit_post_get['fk'] );
	if($_REQUEST['cid'])
		$channel_id = intval( $submit_post_get['cid'] );
	else
		$channel_id = null;
	$entity_type = $submit_post_get['entity'] ;
	$secondary_entity_type = strtolower( $submit_post_get['secent'] );
	
	// If a channel id is provided, get the channel details (to know if the user is the owner.
	if($channel_id != null)
		$channel_info = channelGetInfo($channel_id);

	
	$status = 0;
	
	
	// Case of a journal (tt page).
	if($entity_type == SOCIAL_ENTITY_JOURNAL){
		$options = array(
						'published' => '-2'
						);
		if(journalEditDetails($user_id, $fk, $options))
			$status = 1;
	}
	// Case of a friend (tt page).
	else if($entity_type == SOCIAL_ENTITY_PROFILE_FRIENDS){
		if(userRejectFriendRequest($user_id,$fk)) // fk is the friend's id.
			$status = 1;
	}
	// Case of a user's favorite (tt page).
	else if($secondary_entity_type == "userfavoritedelete"){
		if(userFavoriteDelete($user_id,$fk))
			$status = 1;
	}
	// Case of a user's media (tt page).
	else if($entity_type == SOCIAL_ENTITY_MEDIA && $channel_id == null){
		// Get the media info to see if the user has the right to delete it.
		$vinfo = getVideoInfo($fk);
		if($vinfo['userid'] == $user_id)
			if(videoDeleteFlag($fk))
				$status = 1;
	}
	// Case of a user's album (tt page).
	else if($entity_type == SOCIAL_ENTITY_ALBUM && $channel_id == null){
		// Get the media info to see if the user has the right to delete it.
		if( $user_id == userCatalogOwner($fk) )
			if(userCatalogDelete($fk))
				$status = 1;
	}
	// Case of a user's event (tt page).
	else if($entity_type == SOCIAL_ENTITY_EVENTS && $channel_id == null){
		if(unitDeleteUserEvent($user_id, $fk))
			$status = 1;
	}
	
	
	
	// Case of an event (channel page).
	else if($entity_type == SOCIAL_ENTITY_EVENTS && $channel_id != null && $user_id == intval($channelInfo['owner_id'])){
		if(unitDeleteChannelEvent($channel_id,$fk))
			$status = 1;
	}
	// Case of a brochure (channel page).
	else if($entity_type == SOCIAL_ENTITY_BROCHURE && $user_id == intval($channelInfo['owner_id'])){
		if(unitDeleteChannelBrochure($channel_id,$fk))
			$status = 1;
	}
	// Case of a news (channel page).
	else if($entity_type == SOCIAL_ENTITY_NEWS && $user_id == intval($channelInfo['owner_id'])){
		if(unitDeleteChannelnews($channel_id, $fk))
			$status = 1;
	}
	// Case of an album (channel page).
	else if($entity_type == SOCIAL_ENTITY_ALBUM && $channel_id != null && $user_id == intval($channelInfo['owner_id'])){
		if(userCatalogDelete($fk)) // fk is the album id (catalog id).
			$status = 1;
	}
	// Case of a picture or a video (channel page).
	else if($entity_type == SOCIAL_ENTITY_MEDIA && $channel_id != null && $user_id == intval($channelInfo['owner_id'])){
		if(videoDelete($fk))
			$status = 1;
	}
	// Case of a channel's sponsor (channel page).
	else if($secondary_entity_type == "channelremovesponsor" && $user_id == intval($channelInfo['owner_id'])){
		if(socialShareDelete($fk))
			$status = 1;
	}
	// Case of a channel's connection (channel page).
	else if($secondary_entity_type == "channelremoveconnection" && $user_id == intval($channelInfo['owner_id'])){
		if(RemoveChannelConnectedTuber($channel_id,$fk))
			$status = 1;
	}
	
	
	
	
	
	// Start the XML section.
	$output = $status;
			
	
	
	echo $output;