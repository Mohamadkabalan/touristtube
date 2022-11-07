<?php
//	$entity_id = intval($_POST['entity_id']);
//	$entity_type = intval($_POST['entity_type']);
//	$channel_id = isset($_POST['channel_id']) ? intval($_POST['channel_id']) : null;
//	$msg = isset($_POST['msg']) ? xss_sanitize($_POST['msg']) : '';
//	$share_type = intval( $_POST['share_type'] );
//	$share_with_arr = $_POST['share_with'];
//	$sponsor_id = intval($_POST['sponsor_id']);
	$entity_id = intval($request->request->get('entity_id', 0));
	$entity_type = intval($request->request->get('entity_type', 0));
	$channel_id = intval($request->request->get('channel_id', 0));
	if($channel_id==0) $channel_id=null;
	$msg = $request->request->get('msg', '');
	$share_type = intval($request->request->get('share_type', 3));
	$share_with_arr = $request->request->get('share_with', '');
	$sponsor_id = intval($request->request->get('sponsor_id', 0));
        $share_with_arr = ($share_with_arr=='')? array():$share_with_arr;
        
	$entity_info = socialEntityInfo($entity_type, $entity_id);
        if( $entity_info != null){
            if( isset($entity_info['channel_id']) && intval($entity_info['channel_id'])>0) $channel_id = $entity_info['channel_id'];
            else if( isset($entity_info['channelid']) && intval($entity_info['channelid'])>0) $channel_id = $entity_info['channelid'];
	}
        
	$share_ids = array();
	$share_emails = array();
	
	foreach($share_with_arr as $share_with){
		if( isset($share_with['connections']) ){
			
			$friends_res = channelConnectedTubersSearch(array('is_visible'=>-1,  'channelid' => $sponsor_id));
			foreach($friends_res as $friend):
				if (!in_array($friend['id'], $share_ids)) {
					$share_ids[] = $friend['userid'];
				}
			endforeach;
			
		}else if( isset($share_with['email']) ){
			
			if (filter_var($share_with['email'], FILTER_VALIDATE_EMAIL) && !in_array( $share_with['email'], $share_emails)) {
				$share_emails[] = $share_with['email'];
			}
			
		}else if( isset($share_with['id']) ){
			
			$share_id = intval( $share_with['id'] );
			if (!in_array($share_id, $share_ids)) {
				$share_ids[] = $share_id;
			}	
			
		}
		
	}
	
	
	if( socialShareAdd($sponsor_id, $share_ids, $share_emails, $msg, $entity_id, $entity_type, $share_type, $channel_id) ){
		$ret_arr['status'] = 'ok';
	}else{
		$ret_arr['status'] = 'error';
		$ret_arr['error_msg'] = _('Couldn\'t process sponsor request. Please try again later.');
	}
	
?>
