<?php
/*! \file
 * 
 * \brief This api shares a news feed
 * 
 * 
 * @param S session id
 * @param real_user not used
 * @param entity_id entity id of the shared feed
 * @param entity_type entity type of the shared feed
 * @param channel_id channel id of the shared feed
 * @param addToFeeds add To Feeds of the shared feed
 * @param msg message added of the shared feed
 * @param friends share to friends choosen either 0 or 1 
 * @param friendsandfollowers share to friends and followers either 0 or 1 
 * @param followers share to followers either 0 or 1 
 * @param emails array of emails added on share
 * @param ids array of ids added on share
 * 
 * @return String "ok"
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
//        ini_set('display_errors', 1);
        
//        $user_id = $_SESSION['id'];
//
//        if( !$userID ) die();
        
//        $user_id = userGetID();
//        $user_id = mobileIsLogged($_REQUEST['S']);
//        if( !$user_id ) die();
//	$real_user = isset($_REQUEST['real_user']) ? $_REQUEST['real_user'] : '';
//	$entity_id = intval($_REQUEST['entity_id']);
//	$entity_type = intval($_REQUEST['entity_type']);
//	$channel_id = isset($_REQUEST['channel_id']) ? intval($_REQUEST['channel_id']) : null;
//	$addToFeeds = isset($_REQUEST['addToFeeds']) ? intval($_REQUEST['addToFeeds']) : 1;
//	$msg = isset($_REQUEST['msg']) ? $_REQUEST['msg'] : '';
        $user_id = mobileIsLogged($submit_post_get['S']);
        if( !$user_id ) die();
	$real_user = isset($submit_post_get['real_user']) ? $submit_post_get['real_user'] : '';
	$entity_id = intval($submit_post_get['entity_id']);
	$entity_type = intval($submit_post_get['entity_type']);
	$channel_id = isset($submit_post_get['channel_id']) ? intval($submit_post_get['channel_id']) : null;
	$addToFeeds = isset($submit_post_get['addToFeeds']) ? intval($submit_post_get['addToFeeds']) : 1;
	$msg = isset($submit_post_get['msg']) ? $submit_post_get['msg'] : '';
	$share_type = 1;   
//	$share_with_arr = $_POST['share_with'];
        $share_with_arr = array();
//        $friends = isset($_REQUEST['friends'])? $_REQUEST['friends'] : 0;
//        $friendsandfollowers = isset($_REQUEST['friendsandfollowers'])? $_REQUEST['friendsandfollowers'] : 0;
//        $followers = isset($_REQUEST['followers'])? $_REQUEST['followers'] : 0;
//        $share_with_emails = isset($_REQUEST['emails']) ? explode(',',$share_with_emails) : array();
//        $share_with_ids = isset($_REQUEST['ids']) ?  explode(',',$share_with_ids) : array();
        $friends = isset($submit_post_get['friends'])? $submit_post_get['friends'] : 0;
        $friendsandfollowers = isset($submit_post_get['friendsandfollowers'])? $submit_post_get['friendsandfollowers'] : 0;
        $followers = isset($submit_post_get['followers'])? $submit_post_get['followers'] : 0;
        //code changed by sushma mishra on 21-09-2015 to store email ids starts from here
        //$share_with_emails = isset($submit_post_get['emails']) ? explode(',',$share_with_emails) : array();
        $share_with_emails = isset($submit_post_get['emails']) ? explode(',',$submit_post_get['emails']) : array();
        //code changed by sushma mishra on 21-09-2015 ends here
        $share_with_ids = isset($submit_post_get['ids']) ?  explode(',',$submit_post_get['ids']) : array();
        foreach($share_with_emails as $email){
            $share_with_arr[]['email'] = $email;
        }
        foreach($share_with_ids as $id){
            $share_with_arr[]['id'] = $id;
        }
        if($friends != 0)
            $share_with_arr[]['friends'] = 1;
        if($friendsandfollowers != 0)
            $share_with_arr[]['friendsandfollowers'] = 1;
        if($followers != 0)
            $share_with_arr[]['followers'] = 1;
//        print_r($share_with_arr);exit();
	$share_ids = array();
	$share_emails = array();
        
        
        $entity_info = socialEntityInfo($entity_type, $entity_id);
        if( $entity_info != null){
            if(intval($entity_info['channel_id'])>0) $channel_id = $entity_info['channel_id'];
            else if(intval($entity_info['channelid'])>0) $channel_id = $entity_info['channelid'];
	}
        $real_user_id=0;
        if($real_user!=''){
            $SelectUserSQL = checkUserEmailMD5( $real_user );
            $real_user_id=$SelectUserSQL['id'];
        }
        $connetList_resid=array();
	if($share_type==SOCIAL_SHARE_TYPE_INVITE && $entity_type==SOCIAL_ENTITY_CHANNEL ){
            $connetList_res = channelConnectedTubersSearch(array('is_visible'=>-1,  'channelid' => $entity_id));
            foreach($connetList_res as $tid):
                if (!in_array($tid['uid'], $connetList_resid)) {
                        $connetList_resid[] = $tid['uid'];
                }
            endforeach;
        }
        
	foreach($share_with_arr as $share_with){
		
		if( isset($share_with['connections']) ){
			
			$friends_res = channelConnectedTubersSearch(array('is_visible'=>-1,  'channelid' => $channel_id));
			foreach($friends_res as $friend):
				if (!in_array($friend['uid'], $share_ids)) {
					$share_ids[] = $friend['uid'];
				}
			endforeach;
			
		}else if( isset($share_with['friends']) ){
			
			$friends_res = userGetFreindList($user_id);
			foreach($friends_res as $friend):
				if (!in_array($friend['id'], $share_ids) && !in_array($friend['id'], $connetList_resid)) {
					$share_ids[] = $friend['id'];
				}
			endforeach;
			
		}else if( isset($share_with['email']) ){
			
			if (filter_var($share_with['email'], FILTER_VALIDATE_EMAIL) && !in_array( $share_with['email'], $share_emails)) {
				$share_emails[] = $share_with['email'];
			}
			
		}else if( isset($share_with['id']) ){
			$share_id = intval( $share_with['id'] );
			if (!in_array($share_id, $share_ids) && !in_array($share_id, $connetList_resid)) {
				$share_ids[] = $share_id;
			}	
			
		}
	}
        $ret_arr['status'] = 'ok';
        if(sizeof($share_emails)>0 || sizeof($share_ids)>0){ 	
            $res = socialShareAdd($user_id, $share_ids, $share_emails, $msg, $entity_id, $entity_type, $share_type, $channel_id, $real_user_id, $addToFeeds);
        }
        if(!$res){
            $ret_arr['status'] = 'error';
        }
        echo json_encode( $ret_arr );