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
		if( socialInvitedUserADD($uid,$inviteid ) ){
			$action_type=SOCIAL_ACTION_SHARE;
			if($invite_info['share_type']==SOCIAL_SHARE_TYPE_INVITE){
				$action_type=SOCIAL_ACTION_INVITE;
			}else if($invite_info['share_type']==SOCIAL_SHARE_TYPE_SPONSOR){
				$action_type=SOCIAL_ACTION_SPONSOR;
			}
			newsfeedAdd($uid, $inviteid, $action_type,$invite_info['entity_id'],$invite_info['entity_type'],USER_PRIVACY_PRIVATE,NULL);
			$ret_arr['status'] = 'ok';
		}else{
			$ret_arr['status'] = 'error';
			$ret_arr['error'] = _('Couldn\'t process request. Please try again later').'2';
		}
	}else{
		$ret_arr['status'] = 'error';
		$ret_arr['error'] = _('Couldn\'t process request. Please try again later').'2';
	}
	
?>
