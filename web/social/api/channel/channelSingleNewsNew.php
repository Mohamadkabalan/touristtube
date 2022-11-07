<?php

$expath = "../";
header("content-type: application/json; charset=utf-8");
include("../heart.php");

$submit_post_get = array_merge($request->query->all(),$request->request->all());
//$id = intval($_REQUEST['id']);
$id = intval($submit_post_get['id']);

$options = array(
    'id' => $id
);

$channelnewsInfo = channelnewsSearch($options);
$news = $channelnewsInfo[0];

$output[] = array(
'news' => array(
			'id'=>$news['id'],
			'news_description'=>htmlEntityDecode($news['description']),
			'news_create_date'=>date('d/m/Y', strtotime($news['create_ts'])),
		));

echo json_encode($output);
