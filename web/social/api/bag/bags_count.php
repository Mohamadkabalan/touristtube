<?php

$expath = "../";
include_once($expath."heart.php");
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

$bags_list = userBagList($user_id);

if($bags_list !== false)
{
    $ret_arr['status'] = 'ok';
    $ret_arr['count'] = count($bags_list);
}
else
{
    $ret_arr['status'] = 'error';
}

echo json_encode($ret_arr);