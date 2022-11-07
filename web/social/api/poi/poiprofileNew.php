<?php

$lid_get = $request->query->get('lid','');
//if (isset($_GET['lid'])) {
//    $location_id = $_GET['lid'];
if ($lid_get) {
    $location_id = $lid_get;
} else {
    exit();
}

header("content-type: application/json; charset=utf-8");


$expath = "../";
include($expath . "heart.php");

$datas = locationGet($location_id);

//var_dump($datas);
$res = "<poi type='profile'>";
$res .= "<accent_name>" . $datas['accent_name'] . "</accent_name>";
$res .= "<name>" . safeXML($datas['name']) . "</name>";
$res .= "<latitude>" . $datas['latitude'] . "</latitude>";
$res .= "<longitude>" . $datas['longitude'] . "</longitude>";
$res .= "<country>" . $datas['country'] . "</country>";
$res .= "<n_review>" . $datas['nb_ratings'] . "</n_review>";
$res .= "<rating>" . $datas['rating'] . "</rating>";

$res .= "<city_id>" . $datas['city_id'] . "</city_id>";
$city = getCityById($datas['city_id']);
$res .= "<city_accent>" . $city['accent'] . "</city_accent>";

$res .= "<category_id>" . $datas['category_id'] . "</category_id>";
$res .= "<address>" . $datas['address'] . "</address>";
$res .= "<cmt>" . safeXML($datas['cmt']) . "</cmt>";

$res .= "<desc>" . safeXML($datas['desc']) . "</desc>";
$res .= "<website_url>" . htmlEntityDecode($datas['website_url']) . "</website_url>";
//$res .= "<link>".$datas['link']."</link>";

$datas2 = locationGetStatistics($location_id);
//var_dump($datas2);
$res .= "<n_views>" . $datas2['nb_views'] . "</n_views>";
$res .= "<n_likes>" . $datas2['like_value'] . "</n_likes>";

$res .= "<up_likes>" . $datas['up_votes'] . "</up_likes>";
$res .= "<down_likes>" . $datas['down_votes'] . "</down_likes>";
//$res .= "<n_comments>".$datas2['n_comments']."</n_comments>";
$res .= "<n_rating>" . $datas2['nb_ratings'] . "</n_rating>";
//$res .= "<rating>".$datas2['rating']."</rating>";

$res .= "</poi>";


//echo $res;
$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $res);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string wit