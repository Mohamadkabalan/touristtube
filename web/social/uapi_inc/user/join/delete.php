<?php
//	$id = intval($_POST['id']);
	$id = intval($request->request->get('id', 0));
	$user_id = userGetID();
	
	$join_info = joinUserEventInfo($id);
	$event_info = socialEntityInfo(SOCIAL_ENTITY_USER_EVENTS,$join_info['event_id']);
	
	$media_owner = $event_info['user_id'];
	
	if( $media_owner == $user_id ){
		if( joinUserEventDelete($id) ){
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
