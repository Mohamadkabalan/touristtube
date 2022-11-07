<?php

header("content-type: application/json; charset=utf-8");
//	session_id($_REQUEST['S']);

$expath = "../";


include($expath . "heart.php");

//$longitude = $_GET['long'];
//$latitude = $_GET['lat'];
//$radius = $_GET['rad'];
$longitude = $request->query->get('long','');
$latitude = $request->query->get('lat','');
$radius = $request->query->get('rad','');

$data = getTubersWithInfo($latitude, $longitude, $radius);
if (sizeof($data)) {
    $res = "<tubers>";
    $res .= $data;
    $res .= "</tubers>";
    //echo $res;
} else {
    //echo "<tubers></tubers>";
}

$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $res);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string wit