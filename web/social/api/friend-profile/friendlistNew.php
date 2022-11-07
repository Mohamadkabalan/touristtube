<?php


$submit_post_get = array_merge($request->query->all(),$request->request->all());
session_id($submit_post_get['S']);

$expath = "../";

include($expath . "heart.php");
include($path . "services/lib/chat.inc.php");

//$userID = $_REQUEST['uid'];
$userID = $submit_post_get['uid'];
$myID = $_SESSION['id'];
$datas = userGetFreindList($userID);

//var_dump($datas);

$xml_output = "<friendlist order='friendfriendlist'>";
foreach ($datas as $data) {
    if ($myID != $data['id']) {
        $xml_output .= "<friend>";
        $xml_output .= "<id>" . $data['id'] . "</id>";
        $xml_output .= "<fullname>" . safeXML($data['FullName']) . "</fullname>";
        $xml_output .= "<username>" . $data['YourUserName'] . "</username>";
        if (userIsFriend($myID, $data['id'])) {
            $xml_output .= "<isfriend>YES</isfriend>";
        } else {
            $xml_output .= "<isfriend>NO</isfriend>";
        }
        $userInfo = getUserInfo($data['id']);
        if ($userInfo['profile_Pic'] != "") {
            $xml_output .= "<chatprofilepic>" . "/media/images/tubers/crop_" . substr($userInfo['profile_Pic'], 0, strlen($userInfo['profile_Pic']) - 3) . "png" . "</chatprofilepic>";
        } else {
            $xml_output .= "<chatprofilepic>" . "media/images/tubers/na.png" . "</chatprofilepic>";
        }

        $xml_output .= "<prof_pic>" . "media/images/tubers/" . $data['profile_Pic'] . "</prof_pic>";
        $xml_output .= "</friend>";
    }
}
$xml_output .= "</friendlist>";
header("content-type: application/json; charset=utf-8");
//echo $xml_output;

$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $xml_output);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string wit
