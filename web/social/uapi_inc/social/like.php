<?php

//	$entity_id = intval($_POST['entity_id']);
//	$entity_type = intval($_POST['entity_type']);
//	$like_value = intval($_POST['like_value']);
//	$channel_id = isset($_POST['channel_id']) ? intval($_POST['channel_id']) : null;

	$entity_id = intval($request->request->get('entity_id', 0));
	$entity_type = intval($request->request->get('entity_type', 0));
	$like_value = intval($request->request->get('like_value', 0));
	$channel_id = intval($request->request->get('channel_id', 0));
	if($channel_id==0) $channel_id=null;
	$user_id = userGetID();
	
	$like_record = socialLikeRecordGet($user_id, $entity_id, $entity_type);
	$curr_like = intval($like_record['like_value']);
        
        $entity_info = socialEntityInfo($entity_type, $entity_id);
        if( $entity_info != null){
            if( isset($entity_info['channel_id']) && intval($entity_info['channel_id'])>0) $channel_id = $entity_info['channel_id'];
            else if( isset($entity_info['channelid']) && intval($entity_info['channelid'])>0) $channel_id = $entity_info['channelid'];
	}
	if( $like_record == false ){
            if( socialLikeAdd ($user_id, $entity_id, $entity_type, $like_value, $channel_id) ){
                $ret_arr['status'] = 'ok';
            }else{
                $ret_arr['status'] = 'error';
                $ret_arr['error_msg'] = 'could not like please try again later';
            }
	}else if( $curr_like === $like_value ){
		$ret_arr['status'] = 'error';
		// Display the correct error message.
		if($like_value == 1)
			$ret_arr['error_msg'] = 'already liked';
		else
			$ret_arr['error_msg'] = 'already disliked';
	}else if( ($like_value == 1) && socialLikeEdit ($user_id, $entity_id, $entity_type, $like_value ) ){
		$ret_arr['status'] = 'ok';
	}else if( ($like_value == -1) && socialLikeEdit ($user_id, $entity_id, $entity_type, $like_value ) ){
		$ret_arr['status'] = 'ok';
	}else if( ($like_value == 0) && socialLikeDelete($like_record['id']) ){		
		$ret_arr['status'] = 'ok';
	}else{
		$ret_arr['status'] = 'error';
		$ret_arr['error_msg'] = 'could not like please try again later';
	}
	$options1 = array(
            'entity_id' => $entity_id,
            'entity_type' => $entity_type,
            'like_value' => 1,
            'n_results' => true
        );
	$allLikesnum = socialLikesGet($options1);
        $ret_arr['nb_likes'] = $allLikesnum;
        
        echo json_encode($ret_arr);
exit;