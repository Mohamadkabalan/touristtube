<?php
//    $entity_id = intval($_POST['entity_id']);
    $entity_id = intval($request->request->get('entity_id', 0));
    $user_id = userGetID();

    if($reply_id = flashReechoeAdd( $user_id , $entity_id , SOCIAL_ENTITY_FLASH ) ){
        $ret_arr['status'] = 'ok';
    }else{
        $ret_arr['status'] = 'error';
        $ret_arr['error'] = _('Couldn\'t save the information. Please try again later.');
    }
