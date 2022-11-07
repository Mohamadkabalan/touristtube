<?php
    $id = intval($request->request->get('id', 0));
    $user_id = userGetID();

    if( userBagItemDelete( $user_id , $id ) ){
            $ret_arr['status'] = 'ok';
    }else{
            $ret_arr['status'] = 'error';
            $ret_arr['error'] = "Couldn't delete item please try again later.";
    }
?>