<?php

$expath = "../";
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$user_id = mobileIsLogged($submit_post_get['S']);
if(!$user_id){
    $ret_arr['status'] = 'error';
    echo json_encode($ret_arr);
    exit;
}
$item_id = intval($submit_post_get['item_id']);
$bagItemInfo = bagItemInfo($item_id);
if(!$bagItemInfo || $bagItemInfo['user_id'] != $user_id){
    $ret_arr['status'] = 'error';
}else{
    if(userBagItemDelete($user_id, $item_id)){
        $ret_arr['status'] = 'ok';
    }else{
        $ret_arr['status'] = 'error';
    }
}
echo json_encode($ret_arr);