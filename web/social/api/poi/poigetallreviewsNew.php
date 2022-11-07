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

$nlimit = 30;
$page = 0;
$sortby = "review_ts";
$sort = "ASC";

$limit_get  = $request->query->get('limit','');
$p_get      = $request->query->get('p','');
$sb_get     = $request->query->get('sb','');
$s_get      = $request->query->get('s','');
//if (isset($_GET['limit'])) {
//    $nlimit = $_GET['limit'];
if ($limit_get) {
    $nlimit = $limit_get;
}
//if (isset($_GET['p'])) {
//    $nlimit = $_GET['p'];
if ($p_get) {
    $nlimit = $p_get;
}
//if (isset($_GET['sb'])) {
//    $nlimit = $_GET['sb'];
if ($sb_get) {
    $nlimit = $sb_get;
}
//if (isset($_GET['s'])) {
if ($s_get) {
    $nlimit = $s_get;
}
$datas = LocationGetComments($location_id, $nlimit, $page, $sortby, $sort);

$res .= "<poi_reviews>";
foreach ($datas as $data) {
    $res .= "<poi_review>";
    $res .= "<rating>" . $data['rating'] . "</rating>";
    $res .= "<review>" . $data['review'] . "</review>";
    $res .= "<review_ts>" . $data['review_ts'] . "</review_ts>";
    $res .= "<username>" . $data['username'] . "</username>";
    $res .= "</poi_review>";
}

$res .= "</poi_reviews>";


//echo $res;

$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $res);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string wit