<?php
//    $id = intval($_POST['id']);
//    $entity_type = intval($_POST['entity_type']);
    $id = intval($request->request->get('id', 0));
    $entity_type = intval($request->request->get('entity_type', 0));
    $user_id = userGetID();
    if ( !userIsLogged() ) {
        $ret_arr['status'] = 'error';
        $ret_arr['msg'] = _('you need to have a').' <a class="black_link" href="'+ReturnLink('/register')+'">'.t("TT account").'</a> '.t("in order to remove this image");
    }else{
        if( userDiscoverImagesDelete( $user_id , $entity_type , $id ) ){
            $ret_arr['status'] = 'ok';
        }else{
            $ret_arr['status'] = 'error';
            $ret_arr['msg'] = _('Couldn\'t save the information. Please try again later.');
        }
    }
?>
