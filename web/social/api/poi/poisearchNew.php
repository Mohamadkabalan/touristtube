<?php

header("content-type: application/json; charset=utf-8");
//	session_id($_REQUEST['S']);

$expath = "../";


include($expath . "heart.php");

//$page = $_GET['page'];
//$keyword = $_GET['key'];
//$long = $_GET['long'];
//$lat = $_GET['lat'];
//$rad = $_GET['rad'];
$page = $request->query->get('page','');
$keyword = $request->query->get('key','');
$long = $request->query->get('long','');
$lat = $request->query->get('lat','');
$rad = $request->query->get('rad','');

$res = "<pois order='search'>";

$default_opts = array(
    'limit' => 30,
    'page' => $page,
    'latitude' => $long,
    'longitude' => $lat,
    'radius' => $rad,
    'search_string' => $keyword,
    'type' => 'h',
);

$data = locationSearch($default_opts);

$default_opts2 = array(
    'limit' => 30,
    'page' => $page,
    'latitude' => $long,
    'longitude' => $lat,
    'radius' => $rad,
    'search_string' => $keyword,
    'type' => 'r',
);

$data2 = locationSearch($default_opts2);
if (sizeof($data)) {

    foreach ($data as $poi) {
        $res .= "<poi>";
        $res .= "<id>" . $poi['id'] . "</id>";
        $res .= "<name>" . safeXML($poi['name']) . "</name>";
        $res .= "<longitude>" . $poi['longitude'] . "</longitude>";
        $res .= "<latitude>" . $poi['latitude'] . "</latitude>";
        $res .= "<rating>" . $poi['rating'] . "</rating>";
        $res .= "<category_id>" . $poi['category_id'] . "</category_id>";
        $res .= "<n_review>" . $poi['n_review'] . "</n_review>";
        $res .= "</poi>";
    }
    foreach ($data2 as $poi2) {
        $res .= "<poi>";
        $res .= "<id>" . $poi2['id'] . "</id>";
        $res .= "<name>" . safeXML($poi2['name']) . "</name>";
        $res .= "<longitude>" . $poi2['longitude'] . "</longitude>";
        $res .= "<latitude>" . $poi2['latitude'] . "</latitude>";
        $res .= "<rating>" . $poi2['rating'] . "</rating>";
        $res .= "<category_id>" . $poi2['category_id'] . "</category_id>";
        $res .= "<n_review>" . $poi2['n_review'] . "</n_review>";
        $res .= "</poi>";
    }
    $res .= getFastCityTT($keyword);
    $res .= getCountryCodeTT($keyword);
} else {
    //echo "<pois></pois>";
    //$res = "<pois order='search'>";
    $res .= getFastCityTT($keyword);
    $res .= getCountryCodeTT($keyword);
    //$res .= "</pois>";
    //echo $res;
}

$res .= "</pois>";
//echo $res;
$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $res);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string wit