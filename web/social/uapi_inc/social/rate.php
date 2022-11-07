<?php
	$user_id = userGetID();
	$entity_id = intval($request->request->get('entity_id', 0));
	$entity_type = intval($request->request->get('entity_type', 0));
	$score = intval($request->request->get('score', 0));
	$channel_id = intval($request->request->get('channel_id', 0));
	
	$row = socialRateGet($user_id, $entity_id, $entity_type);	
        $entity_info = socialEntityInfo($entity_type, $entity_id);
        if( $entity_info != null){
            if( isset($entity_info['channel_id']) && intval($entity_info['channel_id'])>0) $channel_id = $entity_info['channel_id'];
            else if( isset($entity_info['channelid']) && intval($entity_info['channelid'])>0) $channel_id = $entity_info['channelid'];
	}
	if( $row === false ){
		$rate_ret = socialRateAdd($user_id, $entity_id, $entity_type, $score, $channel_id);
	}else{
		$rate_ret = socialRateEdit($user_id, $entity_id, $entity_type, $score);
	}	
	
	if($rate_ret === false){
		$ret_arr['status'] = 'error';
		$ret_arr['msg'] = "Couldn't set rating please try again later.";
	}else{
		$ret_arr['status'] = 'ok';
		$ret_arr['rating'] = $rate_ret['rating'];
		$ret_arr['newrate'] = $rate_ret['nb_ratings'];
		$ret_arr['msg'] = "You have successfully rated this video.";
	}
?>