<?php
	
$expath = "../";
header('Content-type: application/json');
include("../heart.php");

$submit_post_get = array_merge($request->query->all(),$request->request->all());

if(isset($submit_post_get['limit']))
	$limit = intval( $submit_post_get['limit'] );
else
	$limit = 10;

if(isset($submit_post_get['page']))
	$page = intval( $submit_post_get['page'] );
else
	$page = 0;

$region_id = intval($submit_post_get['region_id']);

$thingstodoList = getThingstodoCountryList($region_id, $api_language, $page, $limit);
//$thingstodoList = thingstodoSearch($options1);
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

        if($thingstodo['image'])
		{
            $image = 'media/thingstodo/'.$thingstodo['image'];
        }
		else
		{
            $image = '';
        }
		
        $output[] = array(
            'id' => $thingstodo['id'],
            'title' => (isset($thingstodo['ml_title']) && $thingstodo['ml_title']?$thingstodo['ml_title']:$thingstodo['title']),
            'image' => $image,
            'description' => (isset($thingstodo['ml_description']) && $thingstodo['ml_description']?htmlEntityDecode($thingstodo['ml_description']):htmlEntityDecode($thingstodo['description']))
            // , 'create_date' => date('d/m/Y', strtotime($news['create_ts'])),
        );
    }
}

echo json_encode($output);