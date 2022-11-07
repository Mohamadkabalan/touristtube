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

$item_id = intval($submit_post_get['item_id']);
$bag_id = intval($submit_post_get['bag_id']);
$bagItemInfo = bagItemInfo($item_id);

if( !$bagItemInfo || $bagItemInfo['user_id']!= $user_id )
{
    $ret_arr['status'] = 'error';
}
else
{
    
    if($bagItemInfo['bag_id'] == $bag_id)
	{
        $ret_arr['status'] = 'ok';
    }
	else if(updateItemBagId($item_id, $bag_id, $bagItemInfo['type'], $bagItemInfo['item_id']))
	{
        $ret_arr['status'] = 'ok';
    }
	else
	{
        $ret_arr['status'] = 'error';
    }
}

echo json_encode($ret_arr);