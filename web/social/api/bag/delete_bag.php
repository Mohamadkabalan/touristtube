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

$bag_id = intval($submit_post_get['bag_id']);
$bagInfo = bagInfo($bag_id);

if(!$bagInfo || $bagInfo['user_id'] != $user_id)
{
    $ret_arr['status'] = 'error';
}
else
{
    if(userBagIDelete($bag_id))
	{
        $ret_arr['status'] = 'ok';
    }
	else
	{
        $ret_arr['status'] = 'error';
    }
}

echo json_encode($ret_arr);