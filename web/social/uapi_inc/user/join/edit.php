<?php
//	$id = intval($_POST['id']);
//	$val = intval($_POST['val']);
//	$data = xss_sanitize($_POST['data']);
	$id = intval($request->request->get('id', 0));
	$val = intval($request->request->get('val', 0));
	$data = $request->request->get('data', '');
	$user_id = userGetID();
	
	$join_info = joinUserEventInfo($id);
	$event_info = socialEntityInfo(SOCIAL_ENTITY_USER_EVENTS,$join_info['event_id']);
	$media_owner = $event_info['user_id'];
	
	if( $media_owner == $user_id ){
		if( joinUserEventEdit(array('id'=>$id, 'event_id'=>$join_info['event_id'], $data=>$val)) ){
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
