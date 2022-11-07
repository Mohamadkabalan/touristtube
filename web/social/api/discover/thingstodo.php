<?php

$expath = "../";			
// header("content-type: application/xml; charset=utf-8");
header('Content-type: application/json');
include("../heart.php");

$submit_post_get = array_merge($request->query->all(),$request->request->all());

if(isset($submit_post_get['limit']))
	$limit = intval($submit_post_get['limit']);
else
	$limit = 10;
	
if(isset($submit_post_get['page']))
	$page = intval($submit_post_get['page']);
else
	$page = 0;

$parent_id = intval($submit_post_get['country_id']);

$options1 = array(
		'parent_id' => $parent_id,
		'limit' => $limit , 
		'page' => $page , 
		'orderby' => 'order_display',
		'order' => 'd',
		'lang' => $api_language
	);
	
$thingstodoList = thingstodoSearch($options1);
$output = array();

if($thingstodoList)
{
	foreach($thingstodoList as $thingstodo)
	{
		$options = array(
			'parent_id' => $thingstodo['id'],
			'orderby' => 'order_display',
			'order' => 'd',
			'limit' => 1,
			'has_image' => 1
		);
		
		$thingstodoDetails = getThingstodoDetailList($options);
		$bg_image = '';
		
		//echo "<pre>";print_r($thingstodoDetails); die; 
		if($thingstodoDetails)
		{
			foreach($thingstodoDetails as $thingstodoDetail)
			{ 
				if($thingstodoDetail['image'])
					$bg_image = 'media/thingstodo/'.$thingstodoDetail['image'];
			}
			
		}
		
		$pois_count = 0;
		
		$options1 = array(
			'parent_id' => $thingstodo['id'],
			'limit' => 1
		);
		
		$thingstodoDetails1 = getThingstodoDetailList($options1);
		
		if($thingstodoDetails1)
			$pois_count = count($thingstodoDetails1);
		
		// echo "<pre>";print_r($thingstodoDetails);    
		
		if($thingstodo['image'])
			$image = 'media/thingstodo/'.$thingstodo['image'];
		else
			$image = '';
		
		$output[] = array(
				'id' => $thingstodo['id'],
				'title' => (isset($thingstodo['ml_title']) && $thingstodo['ml_title']?$thingstodo['ml_title']:$thingstodo['title']), 
				'image' => $image,
				'bg_image' => $bg_image,
				'description' => (isset($thingstodo['ml_description']) && $thingstodo['ml_description']?htmlEntityDecode($thingstodo['ml_description']):htmlEntityDecode($thingstodo['description'])),
				'city_id' => $thingstodo['city_id'],
				'has_pois' => "". $pois_count .""
				// 'create_date'=>date('d/m/Y', strtotime($news['create_ts'])),
			);
	}
}

echo json_encode($output);