<?php

$expath = "../";
header("content-type: application/json; charset=utf-8");
include("../heart.php");

//$id = intval($_REQUEST['id']);
//$limit = ( $_REQUEST['limit'] ) ? intval($_REQUEST['limit']) : 100;
//$page = ( $_REQUEST['page'] ) ? intval($_REQUEST['page']) : 0;
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$id = intval($submit_post_get['id']);
$limit = ( $submit_post_get['limit'] ) ? intval($submit_post_get['limit']) : 100;
$page = ( $submit_post_get['page'] ) ? intval($submit_post_get['page']) : 0;

$channelInfo = channelGetInfo($id);

$options = array(
    'channelid' => $channelInfo['id'],
    'limit' => $limit,
    'page' => $page
);

$channelalbumInfo = userCatalogchannelSearch($options);

$output = "";

if ($channelalbumInfo) {

    $output .= "<channel_albums>\n";

    foreach ($channelalbumInfo as $album) {
        $output .= "
			<album>
				<id>" . $album['id'] . "</id>
				<album_name>" . htmlEntityDecode($album['catalog_name']) . "</album_name>
				<album_create_date>" . date('d/m/Y', strtotime($album['create_ts'])) . "</album_create_date>
				<album_n_media>" . $album['n_media'] . "</album_n_media>
			</album>";
    }

    $output .= "</channel_albums>";
}

//echo $output;
$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $output);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string wit