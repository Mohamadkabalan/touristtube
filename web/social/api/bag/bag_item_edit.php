<?php

$expath = "../";
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());

$ret_arr = array();

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

$item_id = intval($submit_post_get['item_id']);
$bagItemInfo = bagItemInfo($item_id);

if(!$bagItemInfo || intval($bagItemInfo['user_id']) != intval($user_id))
{
    $ret_arr['status'] = 'error';
}
else
{
    $bagsList = userBagList($user_id);
    $bags = array();
	
	
	if ($bagsList)
		foreach ($bagsList as $item)
		{
			$bags[] = array(
				'id' => $item['id'],
				'name' => $item['name']
			);
		}
	
    $ret_arr['status'] = 'ok';
    $ret_arr['bags'] = $bags;
    $ret_arr['selected_bag'] = $bagItemInfo['bag_id'];
}
echo json_encode($ret_arr);