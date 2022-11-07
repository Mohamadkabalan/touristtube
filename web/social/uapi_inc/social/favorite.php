<?php

    $user_id = userGetID();
//    $entity_id = intval($_POST['entity_id']);
//	$entity_type = intval($_POST['entity_type']);
//	$channel_id = isset($_POST['channel_id']) ? intval($_POST['channel_id']) : null;
	$entity_id = intval($request->request->get('entity_id', 0));
	$entity_type = intval($request->request->get('entity_type', 0));
	$channel_id = intval($request->request->get('channel_id', 0));
    
	$favorited = false;
	if( socialFavoriteAdded($user_id, $entity_id, $entity_type) ){
		$favorited = true;
	}
	
	if( $favorited ){
		
		if(socialFavoriteDelete($user_id,$entity_id,$entity_type)){
			$ret_arr['status'] = 'ok';
			$ret_arr['favorite'] = 0;
		}else{
			$ret_arr['status'] = 'error';
                        $ret_arr['msg'] = 'you have to sign in, in order to complete this action';
		}
	}else{
		if(socialFavoriteAdd($user_id, $entity_id, $entity_type, $channel_id)){
			$ret_arr['status'] = 'ok';
			$ret_arr['favorite'] = 1;
		}else{
			$ret_arr['status'] = 'error';
                        $ret_arr['msg'] = 'you have to sign in, in order to complete this action';
		}
	}
?>
