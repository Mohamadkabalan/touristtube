<?php
/*
* Returns the events sponsored by a given channel.
* Param S: The session id.
* Param [limit]: Optional, the max rows to get, default 100.
* Param [page]: Optional, the current page.
* Param [fromdate]: Optional, from date.
* Param [todate]: Optional, to date.
*/
	
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//	session_id($_REQUEST['S']);
	session_id($submit_post_get['S']);
	
	$expath = "../";			
	header("content-type: application/json; charset=utf-8");  
	include("../heart.php");
	
	$user_id = $_SESSION['id'];
//	if(isset($_REQUEST['limit']))
//		$limit = intval( $_REQUEST['limit'] );
	if(isset($submit_post_get['limit']))
		$limit = intval( $submit_post_get['limit'] );
	else
		$limit = 100;
//	if(isset($_REQUEST['page']))
//		$page = intval( $_REQUEST['page'] );
	if(isset($submit_post_get['page']))
		$page = intval( $submit_post_get['page'] );
	else
		$page = 0;
//	if(isset($_REQUEST['fromdate']))
//		$from_date = intval( $_REQUEST['fromdate'] );
	if(isset($submit_post_get['fromdate']))
		$from_date = intval( $submit_post_get['fromdate'] );
	else
		$from_date = null;
//	if(isset($_REQUEST['todate']))
//		$to_date = intval( $_REQUEST['todate'] );
	if(isset($submit_post_get['todate']))
		$to_date = intval( $submit_post_get['todate'] );
	else
		$to_date = null;
	
	// An array of keys to rename.
	$rename_keys = array(
						
						);
	
	
	// Get the news feed.
	$news_feed = newsfeedSearch( array(
		'limit' => $limit,
		'page' => $page,
		'from_ts' => $from_date,
		'to_ts' => $to_date,
		'orderby' => 'feed_ts',
		'is_visible' => -1,
		'order' => 'd',
		'show_owner' => 1,
		'userid' => $user_id
	) );
	
// ** Special conditions tags.
// Make a sweep through the whole array to add new tags.
foreach($news_feed as $key => $feed_item):
	$news_feed[$key]['is_liked'] = '';
	if($feed_item['entity_type'] && $feed_item['entity_id']){
		if(socialLiked($user_id, $feed_item['entity_id'], $feed_item['entity_type']) == 1)
			$news_feed[$key]['is_liked'] = 1;
	}
endforeach;
	

 
// creating object of SimpleXMLElement
$xml = new SimpleXMLElement("<news_feed></news_feed>");

// function call to convert array to xml
array_to_xml($news_feed,$xml);

//saving generated xml file
$v= $xml->asXML();
$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $v);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string wit


// function to convert array to xml
function array_to_xml($input_array, &$xml) {
	// Declare the renaming array.
	global $rename_keys;
	
	// ** Special conditions tags.
	// Special condition to mix the "fullpath" and the "name" into 1 link -> "thumbnail"
	if($input_array['fullpath'] && $input_array['name'])
		$input_array['thumbnail'] = ReturnLink($input_array['fullpath'] . $input_array['name']);
	
    foreach($input_array as $key => $value) {
		
		// Rename the tag if it is listed to be renamed.
		if($rename_keys[$key])
			$key = $rename_keys[$key];
		
		// ** Special conditions tags.
		// Return a link to the profile picture.
		if($key === 'profile_Pic')
			$value = 'media/images/tubers/' . $value;
		
        if(is_array($value)) {
            if(!is_numeric($key)){
                $subnode = $xml->addChild("$key");
                array_to_xml($value, $subnode);
            }
            else{
                $subnode = $xml->addChild("item");
                array_to_xml($value, $subnode);
            }
        }
        else {
            $xml->addChild("$key","$value");
        }
    }
}