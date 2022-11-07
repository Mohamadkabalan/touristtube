<?php

$lid_get = $request->query->get('lid','');
//if (isset($_GET['lid'])) {
//    $location_id = $_GET['lid'];
if ($lid_get)
{
    $location_id = $lid_get;
} else {
    exit();
}

header("content-type: application/json; charset=utf-8");


$limit = 25;
$page = 0;

$expath = "../";
include($expath . "heart.php");

$limit_get  = $request->query->get('limit','');
$p_get      = $request->query->get('p','');
//if (isset($_GET['limit'])) {
//    $limit = $_GET['limit'];
if ($limit_get) {
    $limit = $limit_get;
}
//if (isset($_GET['p'])) {
//    $page = $_GET['p'];
if ($p_get) {
    $page = $p_get;
}

$options = array('limit' => $limit, 'type' => 'v', 'orderby' => 'id', 'order' => 'a', 'page' => $page, 'location_id' => $location_id);
$datas = mediaSearch($options);

$res = "<videos order='related to location'>";
foreach ($datas as $data) {
    $res .= '<video>';
    $res .= '<id>' . $data['id'] . '</id>';
    $res .= '<title>' . safeXML($data['title']) . '</title>';
    $res .= '<description>' . safeXML($data['description']) . '</description>';

    $userinfo = getUserInfo($data['userid']);
    $res .= '<user>' . $userinfo['YourUserName'] . '</user>';
    $res .= '<n_views>' . $data['nb_views'] . '</n_views>';
    $res .= '<duration>' . $data['duration'] . '</duration>';

    $res .= '<rating>' . $stats['rating'] . '</rating>';
    $res .= '<nb_rating>' . $stats['nb_ratings'] . '</nb_rating>';
    $res .= '<nb_comments>' . $stats['nb_comments'] . '</nb_comments>';

    $res .= '<up_vote>' . $data['up_votes'] . '</up_vote>';
    $res .= '<down_vote>' . $data['down_votes'] . '</down_vote>';
    //$res .= '<rating>'.$data['rating'].'</rating>';				
    //$res .= '<comments>'.$data['comments'].'</comments>';
    $res .= '<videolink>' . '' . $data['fullpath'] . $data['name'] . '</videolink>';
    $res .= '<thumblink>' . '' . substr(getVideoThumbnail($data['id'], $path . $data['fullpath'], 0), strlen($path)) . '</thumblink>';
    /////$res .= '&lt;item&gt;http://172.16.124.254:8181/touristtube/'.substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path)).'&lt;&frasl;item&gt;<br>';
    //echo $data['id']." ".$data['fullpath'];
    //var_dump(getVideoThumbnail($data['id'],$data['fullpath'],0));

    $res .= '</video>';
}

$res .= "</videos>";
//echo $res;
$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $res);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string wit