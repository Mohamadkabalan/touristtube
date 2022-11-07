<?php
//	$id = intval($_POST['id']);
	$id = intval($request->request->get('id', 0));
	$user_id = userGetID();
	
	$join_info = joinEventInfo($id);
	$event_info = socialEntityInfo(SOCIAL_ENTITY_EVENTS,$join_info['event_id']);
	$channel_info = socialEntityInfo(SOCIAL_ENTITY_CHANNEL,$event_info['channelid']);
	$media_owner = $channel_info['owner_id'];
	
	if( $media_owner == $user_id ){
		if( joinEventEdit(array('id'=>$id,'event_id'=>$join_info['event_id'],'published'=>1)) ){
			$ret_arr['status'] = 'ok';
		}else{
			$ret_arr['status'] = 'error';
			$ret_arr['error'] = _('Couldn\'t process request. Please try again later');
		}
	}else{
		$ret_arr['status'] = 'error';
		$ret_arr['error'] = _('Couldn\'t process request. Please try again later');
	}
	
?>
