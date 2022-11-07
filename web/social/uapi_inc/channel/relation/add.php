<?php
//    $parent_id = intval($_POST['parent_id']);
//    $channel_id = intval($_POST['channel_id']);
//    $relation_type = intval($_POST['relation_type']);
    $parent_id = intval($request->request->get('parent_id', 0));
    $channel_id = intval($request->request->get('channel_id', 0));
    $relation_type = intval($request->request->get('relation_type', 0));
    $relatiion = addChannelRelation($channel_id,$parent_id,$relation_type);
    if( $relatiion==1 ){		
        $ret_arr['status'] = 'ok';
    }else if( $relatiion==2 ){		
        $ret_arr['status'] = 'error';
        $ret_arr['msg'] = _('channel already existing.');
    }else{
        $ret_arr['status'] = 'error';
        $ret_arr['msg'] = _('couldn\'t save the information. Please try again later.');
    }
