<?php

$path = "../";

$bootOptions = array("loadDb" => 1, 'requireLogin' => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );

include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );

$ret = array();

if (!userIsLogged()) {
    $ret['status'] = 'error';
    $ret['type'] = 'session';
    $ret['msg'] = _('Please login to complete this task.');
    echo json_encode($ret);
    exit;
}
$ret_arr =array();
$entity_type = intval($request->request->get('entity_type', 0));
$item_id = intval($request->request->get('item_id', 0));
$filename = $request->request->get('filename', '');
$user_id = userGetID();
if ( !userIsLogged() ) {
    $ret_arr['status'] = 'error';
    $ret_arr['msg'] = _('you need to have a').' <a class="black_link" href="'+ReturnLink('/register')+'">'.t("TT account").'</a> '.t("in order to add hotel image");
}else{
    if( $img_id = userDiscoverImagesAdd( $user_id , $entity_type , $item_id , $filename ) ){
        $ret_arr['status'] = 'ok';
        $ret_arr['id'] = $img_id;
    }else{
        $ret_arr['status'] = 'error';
        $ret_arr['msg'] = _('Couldn\'t save the information. Please try again later.');
    }
}

echo json_encode($ret_arr);