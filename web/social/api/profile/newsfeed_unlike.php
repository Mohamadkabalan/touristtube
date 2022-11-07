<?php
/*! \file
 * 
 * \brief This api is used to unlike any entity
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
        
        //$user_id = $_SESSION['id'];
        
//        $user_id = mobileIsLogged($_REQUEST['S']);
        $user_id = mobileIsLogged($submit_post_get['S']);
        if( !$user_id ) die();

//        if( !$userID ) die();
        
//        $user_id = userGetID();
//	$media = $_REQUEST['entity_type'];
//	$channelid = $_REQUEST['channel_id'];
//	$mediaid = $_REQUEST['entity_id'];
	$media = $submit_post_get['entity_type'];
	$channelid = $submit_post_get['channel_id'];
	$mediaid = $submit_post_get['entity_id'];
	$Result = array();
	
	if($media==11){
		$media = SOCIAL_ENTITY_NEWS;
	}else if($media==12){
		$media = SOCIAL_ENTITY_EVENTS;
	}else if($media==13){
		$media = SOCIAL_ENTITY_BROCHURE;
	}

        $res = socialLikeRecordGet ($user_id, $mediaid, $media);
        if($res){
                $Result['status'] = 'ok';
                if( !socialLikeDelete ($res['id']) ){
                        $Result['error'] = _('Error while posting your request');
                }

        }
	$Result['status'] = 'ok';
	
	echo json_encode( $Result );