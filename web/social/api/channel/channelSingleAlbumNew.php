<?php
	
	$expath = "../";			
	header("content-type: application/json; charset=utf-8");  
	include("../heart.php");
	
//	$catid = intval( $_REQUEST['id'] );
//	$channelid = intval( $_REQUEST['channelid'] );
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
	$catid = intval( $submit_post_get['id'] );
	$channelid = intval( $submit_post_get['channelid'] );
	
	$options = array(
		'limit' => 999,
		'page' => 0,
		'catalog_id' => $catid,
		'channel_id' => $channelid,
		'orderby' => 'pdate',
		'type' => 'a',
		'order' => 'd'
	);
	
	$media_info = mediaSearch($options);
	
	$output = "<album>\n";
	
	foreach($media_info as $media){
		$output .= "
		<media>
			<id>".$media['id']."</id>
			<type>".$media['image_video']."</type>
			<thumbnail>".photoReturnThumbSrc($media)."</thumbnail>
		</media>";
	}
	
	$output .= "</album>";
	
	//echo $output;
        
        $xml_cnt = str_replace(array("\n", "\r", "\t"), '', $output);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string wit