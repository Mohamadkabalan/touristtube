<?php
	$user_id = userGetID();
//	$real_user = isset($_POST['real_user']) ? xss_sanitize($_POST['real_user']) : '';
//	$entity_id = intval($_POST['entity_id']);
//	$entity_type = intval($_POST['entity_type']);
//	$channel_id = isset($_POST['channel_id']) ? intval($_POST['channel_id']) : null;
//	$addToFeeds = isset($_POST['addToFeeds']) ? intval($_POST['addToFeeds']) : 1;
//	$msg = isset($_POST['msg']) ? xss_sanitize($_POST['msg']) : '';
//	$share_type = intval( $_POST['share_type'] );
//	$share_with_arr = $_POST['share_with'];
	$real_user = $request->request->get('real_user', '');
	$entity_id = intval($request->request->get('entity_id', 0));
	$entity_type = intval($request->request->get('entity_type', 0));
	$channel_id = intval($request->request->get('channel_id', 0));
	if($channel_id==0) $channel_id=null;
	$addToFeeds = intval($request->request->get('addToFeeds', 1));
	$msg = $request->request->get('msg', '');
	$share_type = intval($request->request->get('share_type', 1));
	$share_with_arr = $request->request->get('share_with', '');
	$share_ids = array();
	$share_emails = array();
        $share_with_arr = ($share_with_arr=='')? array():$share_with_arr;
        
        $entity_info = socialEntityInfo($entity_type, $entity_id);
        if( $entity_info != null){
            if( isset($entity_info['channel_id']) && intval($entity_info['channel_id'])>0) $channel_id = $entity_info['channel_id'];
            else if( isset($entity_info['channelid']) && intval($entity_info['channelid'])>0) $channel_id = $entity_info['channelid'];
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
        if(sizeof($share_emails)>0 || sizeof($share_ids)>0) socialShareAdd($user_id, $share_ids, $share_emails, $msg, $entity_id, $entity_type, $share_type, $channel_id,$real_user_id,$addToFeeds);