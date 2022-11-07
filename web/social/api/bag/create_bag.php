<?php

$expath = "../";
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());

$uid = 0;
if (isset($submit_post_get['S']))
	$uid = $submit_post_get['S'];

$user_id = mobileIsLogged($uid);

if(!$user_id)
{
    $ret_arr['status'] = 'error';
    echo json_encode($ret_arr);
    exit;
}

$bag_name = $submit_post_get['name'];
$add_res = addNewBag($user_id, $bag_name);

if($add_res)
{
    $ret_arr['bag_id'] = $add_res;
    $ret_arr['name'] = $bag_name;
    $ret_arr['status'] = 'ok';
}
else
{
    $ret_arr['status'] = 'error';
}

echo json_encode($ret_arr);