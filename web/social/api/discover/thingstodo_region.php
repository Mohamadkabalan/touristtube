<?php
$expath = "../";			
//header("content-type: application/xml; charset=utf-8");
header('Content-type: application/json');
include("../heart.php");

$thingstodoRegionList = getThingstodoRegionList($api_language);
$output = array();

if($thingstodoRegionList)
{
	foreach($thingstodoRegionList as $thingstodo)
	{
		if($thingstodo['image'] && $thingstodo['mobile_image'])
		{
			$image = 'media/thingstodo/'.$thingstodo['mobile_image'];
		}
		else
		{
                    $image = 'media/thingstodo/'.$thingstodo['image'];

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