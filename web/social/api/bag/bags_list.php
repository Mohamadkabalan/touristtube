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
$bags_array = array();

if ($bags_list)
{
	foreach($bags_list as $bag)
	{
		$item = array();
		$item['id'] = $bag['id'];
		$item['name'] = $bag['name'];
		$item['count'] = userBagItemsCount($user_id, $bag['id']);
		$item['image'] = '';
		if( $bag['imgname']!='' ){
		   $item['image'] = createBagItemThumbs( $bag['imgname'] , $bag['imgpath'] ,0,0,284,162,'bagthumb-284162');
		}
		$bags_array[] = $item;
	}
}

$ret_arr['status'] = 'ok';
$ret_arr['bags'] = $bags_array;
echo json_encode($ret_arr);