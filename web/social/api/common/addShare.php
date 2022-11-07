<?php
/*
* Returns the events sponsored by a given channel.
* param S: The session id.
* param fk: The foreign key (item id).
* param [cid]: The channel id (needed in all channel pages, not needed in tt pages).
* param entity: The entity type.
* param [msg]: Optional, the share text.
* param [ids]: Optional, comma-separated list of IDs to share with (can be skipped if only wild-cards are used).
* param [wc]: Optional, comma-separated list of wild-cards: connections, friends, followers, friendsconnections.
* param [em]: Optional, comma-separated list of emails to share with.
* param [sharetype]: Optional, the share type: SOCIAL_SHARE_TYPE_SHARE, SOCIAL_SHARE_TYPE_INVITE or SOCIAL_SHARE_TYPE_SPONSOR. Default: SOCIAL_SHARE_TYPE_SHARE.
* 
* Returns blank on success, 'error' on failure.
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
//	$entity_type = xss_sanitize( $_REQUEST['entity'] );
//	
//	if($_REQUEST['cid'])
//		$channel_id = intval( $_REQUEST['cid'] );
	$fk = intval( $submit_post_get['fk'] );
	$entity_type = $submit_post_get['entity'] ;
	
	if($submit_post_get['cid'])
		$channel_id = intval( $submit_post_get['cid'] );
	else
		$channel_id = null;
	
//	if($_REQUEST['msg'])
//		$share_message = xss_sanitize( $_REQUEST['msg'] );
	if($submit_post_get['msg'])
		$share_message = $submit_post_get['msg'] ;
	else
		$share_message = '';
	
//	if($_REQUEST['ids'])
//		$share_ids = explode(',', xss_sanitize( $_REQUEST['ids'] ));
	if($submit_post_get['ids'])
		$share_ids = explode(',', $submit_post_get['ids'] );
	else
		$share_ids = array();
	
//	if($_REQUEST['wc'])
//		$share_wildcards = explode(',', strtolower(xss_sanitize( $_REQUEST['wc'] )));
	if($submit_post_get['wc'])
		$share_wildcards = explode(',', strtolower( $submit_post_get['wc'] ));
	else
		$share_wildcards = array();
	
//	if($_REQUEST['em'])
//		$share_emails = explode(',', xss_sanitize( $_REQUEST['em'] ));
	if($submit_post_get['em'])
		$share_emails = explode(',', $submit_post_get['em'] );
	else
		$share_emails = array();
		
//	if($_REQUEST['sharetype'])
//		$share_type = xss_sanitize( $_REQUEST['sharetype'] );
	if($submit_post_get['sharetype'])
		$share_type = $submit_post_get['sharetype'] ;
	else
		$share_type = SOCIAL_SHARE_TYPE_SHARE;
	
	// The final list of ids to be saved to the db. Includes the ids and the wildcards made into ids.
	$share_ids = array();
	// The final list of emails to be saved to the db.
	$share_emails = array();
	
	// Case of the ids.
	foreach($share_ids as $id):
		$share_id = intval( $id );
		// If it doesn't already exist in the final array, add it.
		if (!in_array($share_id, $share_ids)) {
			$share_ids[] = $share_id;
		}
	endforeach;
	
	// Case of wildcards.
	foreach($share_wildcards as $wildcard):
		// Connections.
		if( $wildcard == 'connections' ){
			
			$friends_res = channelConnectedTubersSearch(array('is_visible'=>-1,  'channelid' => $channel_id));
			foreach($friends_res as $friend):
				if (!in_array($friend['id'], $share_ids)) {
					$share_ids[] = $friend['userid'];
				}
			endforeach;
		// Friends.
		}else if( $wildcard == 'friends' ){
			
			$friends_res = userGetFreindList($user_id);
			foreach($friends_res as $friend):
				if (!in_array($friend['id'], $share_ids)) {
					$share_ids[] = $friend['id'];
				}
			endforeach;
		}
	endforeach;
	
	// Case of emails.
	foreach($share_emails as $email):
		if (filter_var($email, FILTER_VALIDATE_EMAIL) && !in_array( $share_with['email'], $share_emails)) {
			$share_emails[] = $email;
		}
	endforeach;
	
	
	// Save the share to the db.
	if( socialShareAdd($user_id, $share_ids, $share_emails, $share_message, $fk, $entity_type, $share_type, $channel_id) ){
		$status = '';
	}else{
		$status = 'error';
	}
	
	echo $status;