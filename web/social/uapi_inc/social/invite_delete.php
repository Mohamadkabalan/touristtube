<?php
//	$id = intval($_POST['id']);
	$id = intval($request->request->get('id', 0));
	$user_id = userGetID();
	
	$invite_info = socialShareGet($id);
	$channel_info = socialEntityInfo(SOCIAL_ENTITY_CHANNEL,$invite_info['channel_id']);
	$media_owner = $channel_info['owner_id'];
	
	if( $media_owner == $user_id ){
		if( socialShareDelete($id) ){
			$ret_arr['status'] = 'ok';
		}else{
			$ret_arr['status'] = 'error';
			$ret_arr['msg'] = _('Couldn\'t process request. Please try again later');
		}
	}else{
		$ret_arr['status'] = 'error';
		$ret_arr['msg'] = _('Couldn\'t process request. Please try again later');
	}
	
?>
