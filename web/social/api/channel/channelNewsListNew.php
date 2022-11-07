<?php
	
	$expath = "../";			
	header("content-type: application/json; charset=utf-8");  
	include("../heart.php");
	
//	$id = intval( $_REQUEST['id'] );
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
	$id = intval( $submit_post_get['id'] );
	
	$options = array(
		'limit' => 999,
		'channelid' => $id
	);
	
	$channelnewsInfo = channelnewsSearch($options);
	
	$output = "";
	
	if($channelnewsInfo){
		
		$output .= "<channel_news>\n";
		
		foreach($channelnewsInfo as $news){
			$output .= "
			<news>
				<id>".$news['id']."</id>
				<news_description>".htmlEntityDecode($news['description'])."</news_description>
				<news_create_date>".date('d/m/Y', strtotime($news['create_ts']))."</news_create_date>
			</news>";
		}
		
		$output .= "</channel_news>";
		
	}
	
	//echo $output;
        $xml_cnt = str_replace(array("\n", "\r", "\t"), '', $output);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string wit