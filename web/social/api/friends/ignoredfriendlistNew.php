<?php


$submit_post_get = array_merge($request->query->all(),$request->request->all());
session_id($submit_post_get['S']);

$expath = "../";

include($expath . "heart.php");
include($path . "services/lib/chat.inc.php");

$userID = $_SESSION['id'];
$str = null;
$str_get = $request->query->get('str','');
//if (isset($_GET['str'])) {
//    $str = $_GET['str'];
if ($str_get) {
    $str = $str_get;
}

$default_opts = array(
    'limit' => 15000,
    'page' => 0,
    'type' => array(3),
    'userid' => $userID,
    'orderby' => 'request_ts',
    'order' => 'a',
    'search_string' => $str
);

$datas = userFriendSearch($default_opts);


//var_dump($datas);

$xml_output = "<friendlist order='blockedlist'>";
foreach ($datas as $data) {
    $xml_output .= "<friend>";
    $xml_output .= "<id>" . $data['id'] . "</id>";
    $xml_output .= "<fullname>" . safeXML($data['FullName']) . "</fullname>";
    $xml_output .= "<username>" . safeXML($data['YourUserName']) . "</username>";

    $userInfo = getUserInfo($data['id']);
    if ($userInfo['profile_Pic'] != "") {
        $xml_output .= "<chatprofilepic>" . "/media/images/tubers/crop_" . substr($userInfo['profile_Pic'], 0, strlen($userInfo['profile_Pic']) - 3) . "png" . "</chatprofilepic>";
    } else {
        $xml_output .= "<chatprofilepic>" . "media/images/tubers/na.png" . "</chatprofilepic>";
    }

    $xml_output .= "<prof_pic>" . "media/images/tubers/" . $data['profile_Pic'] . "</prof_pic>";
    $xml_output .= "</friend>";
}
$xml_output .= "</friendlist>";
header("content-type: application/json; charset=utf-8");
//echo $xml_output;
$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $xml_output);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string wit