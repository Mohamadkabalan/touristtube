<?php
//	$inviteid = intval($_POST['inviteid']);
//	$uid = intval($_POST['uid']);
	$inviteid = intval($request->request->get('inviteid', 0));
	$uid = intval($request->request->get('uid', 0));
	$user_id = userGetID();
	
	$invite_info = socialShareGet($inviteid);
	if( !is_null($invite_info['channel_id']) ){
		$channel_info = socialEntityInfo(SOCIAL_ENTITY_CHANNEL,$invite_info['channel_id']);
		$media_owner = $channel_info['owner_id'];
	}else{
		$media_owner = socialEntityOwner($invite_info['entity_type'], $invite_info['entity_id']);	
	}
	
	if( $media_owner == $user_id ){
		if(socialInvitedUserDelete($uid,$invite_info['entity_id'],$invite_info['entity_type'],$invite_info['share_type']) ){
			
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
