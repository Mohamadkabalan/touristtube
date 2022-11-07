<?php
$expath = "../";
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());

$uid = 0;
if (isset($submit_post_get['S']))
	$uid = $submit_post_get['S'];

$user_id = mobileIsLogged($uid);
if(!$user_id){
    $ret_arr['status'] = 'error';
    echo json_encode($ret_arr);
    exit;
}
$bag_id = intval($submit_post_get['bag_id']);
$new_bag = intval($submit_post_get['new_bag']);
if($new_bag){
    $bag_name = $submit_post_get['name'];
    $add_res = addNewBag($user_id,$bag_name);
    if($add_res){
        $bag_id = $add_res;
        $ret_arr['status'] = 'ok';
    }else{
        $ret_arr['status'] = 'error';
    }
}
if($ret_arr['status'] != 'error'){
    $entity_type = intval($submit_post_get['entity_type']);
    $item_id = intval($submit_post_get['entity_id']);

    $bag_item_count=userBagItemsAdd($user_id,$entity_type,$item_id,$bag_id);
    if( $bag_item_count ){
        $ret_arr['status'] = 'ok';
        $ret_arr['count'] = $bag_item_count;
    }else{
        $ret_arr['status'] = 'error';
    }
}
echo json_encode($ret_arr);