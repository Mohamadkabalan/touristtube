<?php


$submit_post_get = array_merge($request->query->all(),$request->request->all());
session_id($submit_post_get['S']);

$expath = "../";

include($expath . "heart.php");
include($path . "services/lib/chat.inc.php");

$userID = $_SESSION['id'];
if ($userID == '')
    $xml_output = "No session";
else {

    $datas = userGetFreindList($userID);

    //var_dump($datas);

    $xml_output = "<friendlist order='friendlist'>";
    foreach ($datas as $data) {
        if (userIsProfileBlocked($userID, $data['id'])) {
            //continue;
        }
        $xml_output .= "<friend>";
        $xml_output .= "<id>" . $data['id'] . "</id>";
        $xml_output .= "<fullname>" . safeXML($data['FullName']) . "</fullname>";
        $xml_output .= "<username>" . safeXML($data['YourUserName']) . "</username>";

        if (userIsFriend($userID, $data['id'])) {
            $xml_output .= "<isfriend>YES</isfriend>";
        } else {
            $xml_output .= "<isfriend>NO</isfriend>";
        }
        if (userSubscribed($userID, $data['id'])) {
            $xml_output .= "<isfollowed>YES</isfollowed>";
        } else {
            $xml_output .= "<isfollowed>NO</isfollowed>";
        }

        $userInfo = getUserInfo($data['id']);
        $xml_output .= "<status>" . chatGetUserStatus($data['id']) . "</status>";
        if ($userInfo['profile_Pic'] != "") {
            $xml_output .= "<chatprofilepic>" . "/media/images/tubers/crop_" . substr($userInfo['profile_Pic'], 0, strlen($userInfo['profile_Pic']) - 3) . "png" . "</chatprofilepic>";
        } else {
            $xml_output .= "<chatprofilepic>" . "media/images/tubers/na.png" . "</chatprofilepic>";
        }

        $xml_output .= "<prof_pic>" . "media/images/tubers/" . $data['profile_Pic'] . "</prof_pic>";
        if (userFavoriteUserAdded($userID, $data['id'])) {
            $xml_output .= "<is_fav>YES</is_fav>";
        } else {
            $xml_output .= "<is_fav>NO</is_fav>";
        }
        $xml_output .= "</friend>";
    }
    $xml_output .= "</friendlist>";
}
header("content-type: application/json; charset=utf-8");
//echo $xml_output;
$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $xml_output);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string wit
