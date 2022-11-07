<?php
//    $parent_id = intval($_POST['parent_id']);
//    $channel_id = intval($_POST['channel_id']);
//    $relation_type = intval($_POST['relation_type']);
    $parent_id = intval($request->request->get('parent_id', 0));
    $channel_id = intval($request->request->get('channel_id', 0));
    $relation_type = intval($request->request->get('relation_type', 0));
    $user_id = userGetID();
    if($relation_type==1){
        $channel_info=channelGetInfo($channel_id);
        $entity_id = $parent_id;
    }else{
        $channel_info=channelGetInfo($parent_id);
        $entity_id = $channel_id;
    }    
    $media_owner = $channel_info['owner_id'];
	
    if( $media_owner == $user_id ){
        $opts = array(
            'channelid' => $channel_id,
            'parent_id' => $parent_id,
            'published' => 1
        );
        if( channelRelationEdit($opts) ){
            $info = channelRelationInfo($channel_id,$parent_id);
            if($relation_type==CHANNEL_RELATION_TYPE_PARENT){
                newsfeedAdd($channel_info['id'], $info['id'] , SOCIAL_ACTION_RELATION_PARENT, $entity_id, SOCIAL_ENTITY_CHANNEL , USER_PRIVACY_PUBLIC, $entity_id);
            }else{
                newsfeedAdd($channel_info['id'], $info['id'] , SOCIAL_ACTION_RELATION_SUB, $entity_id, SOCIAL_ENTITY_CHANNEL , USER_PRIVACY_PUBLIC, $entity_id);
            }            
            $ret_arr['status'] = 'ok';
        }else{
            $ret_arr['status'] = 'error';
            $ret_arr['msg'] = _('couldn\'t save the information. Please try again later.');
        }
    }else{
        $ret_arr['status'] = 'error';
        $ret_arr['msg'] = _('couldn\'t save the information. Please try again later.');
    }
	
