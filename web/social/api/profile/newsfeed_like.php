<?php
/*! \file
 * 
 * \brief This api is used to like any entity
 * 
 * 
 * @param S  session id
 * @param entity_type entity type
 * @param channel_id channel id
 * @param entity_id entity id
 * 
 * @return JSON list with the following keys:
 * @return <pre> 
 * @return       <b>error</b> any error
 * @return       <b>status</b> if not empty event succeed
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */

/*
* Returns the events sponsored by a given channel.
* Param S: The session id.
* Param [limit]: Optional, the max rows to get, default 100.
* Param [page]: Optional, the current page.
* Param [fromdate]: Optional, from date.
* Param [todate]: Optional, to date.
*/
	
//	session_id($_REQUEST['S']);
//	session_start();
	$expath = "../";			
	//header("content-type: application/xml; charset=utf-8");  
        header('Content-type: application/json');
	include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
        
//        $user_id = $_SESSION['id'];
        
//        $user_id = mobileIsLogged($_REQUEST['S']);
        
        $user_id = mobileIsLogged($submit_post_get['S']);
        if( !$user_id ) die();

//        if( !$userID ) die();
        
//        $media = $_REQUEST['entity_type'];
//	$channelid = $_REQUEST['channel_id'];
//	$mediaid = $_REQUEST['entity_id'];
        $media = $submit_post_get['entity_type'];
	$channelid = intval($submit_post_get['channel_id']);
	$mediaid = intval($submit_post_get['entity_id']);
        
	$Result = array();
        
        $entity_info = socialEntityInfo($media, $mediaid);
        if( $entity_info != null){
            if(intval($entity_info['channel_id'])>0) $channelid = $entity_info['channel_id'];
            else if(intval($entity_info['channelid'])>0) $channelid = $entity_info['channelid'];
	}
        
	if($media==6){
		$media = SOCIAL_ENTITY_JOURNAL;
	}else if($media==11){
		$media = SOCIAL_ENTITY_NEWS;
	}else if($media==12){
		$media = SOCIAL_ENTITY_EVENTS;
	}else if($media==13){
		$media = SOCIAL_ENTITY_BROCHURE;
	}
	
	if( socialLiked ($user_id, $mediaid, $media) ){
                
		$Result['error'] = _('You have already liked this post');
	}else{
		$Result['status'] = 'ok';
		if( !$likes=socialLikeAdd ($user_id, $mediaid, $media, 1, $channelid) ){
			$Result['error'] = _('Error while posting your request');
		}
		
	}
	
	echo json_encode( $Result );