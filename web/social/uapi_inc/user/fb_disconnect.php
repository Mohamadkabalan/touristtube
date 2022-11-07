<?php
    $user_id = userGetID();

    if( userEdit( array( 'id'=> userGetID() , 'fb_token'=>'' , 'fb_user'=>'' ) ) ){
        $ret_arr['status'] = 'ok';
    }else{
        $ret_arr['status'] = 'error';
        $ret_arr['msg'] = _('Couldn\'t save the information. Please try again later.');
    }
?>
