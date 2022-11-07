<?php
	
	$expath = "../";			
	header("content-type: application/xml; charset=utf-8");  
	include("../heart.php");
	
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//	$id = intval( $_REQUEST['id'] );
	$id = intval( $submit_post_get['id'] );
	
	$options = array(
		'id' => $id
	);
	
	$channelnewsInfo = channelnewsSearch($options);
	$news = $channelnewsInfo[0];
        $date = returnSocialTimeFormat( $news['create_ts'] ,3);
	$output = "<news>
			<id>".$news['id']."</id>
			<news_description>".htmlEntityDecode($news['description'])."</news_description>
			<news_create_date>".$date."</news_create_date>
		</news>";
		
	echo $output;