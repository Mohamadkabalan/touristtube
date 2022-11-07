<?php


$submit_post_get = array_merge($request->query->all(),$request->request->all());
session_id($submit_post_get['S']);

$expath = "../";

include($expath . "heart.php");

$userID = $_SESSION['id'];

$default_opts = array(
    'limit' => 15000,
    'page' => 0,
    'public' => 1,
    'orderby' => 'id',
    'order' => 'a',
//    'search_string' => $_REQUEST['str'],
    'search_string' => $submit_post_get['str'],
    'search_strict' => 0,
    'search_where' => 'a'
);

$datas = userSearch($default_opts);

//var_dump($datas);

$xml_output = "<friendlist order='userSearch'>";
foreach ($datas as $data) {
    if ($userID != $data['id']) {
        $xml_output .= "<friend>";
        $xml_output .= "<id>" . $data['id'] . "</id>";
        //$xml_output .= "<rid>".$data['id']."</rid>";
        if ($data['FullName'] != '')
            $xml_output .= "<fullname>" . safeXML($data['FullName']) . "</fullname>";
        else
            $xml_output .= "<fullname>" . safeXML($data['YourUserName']) . "</fullname>";
        $xml_output .= "<username>" . safeXML($data['YourUserName']) . "</username>";
        //$xml_output .= "<fullname>".safeXML($data['YourUserName'])."</fullname>";
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
        if ($userInfo['profile_Pic'] != "") {
            $xml_output .= "<chatprofilepic>" . "/media/images/tubers/crop_" . substr($userInfo['profile_Pic'], 0, strlen($userInfo['profile_Pic']) - 3) . "png" . "</chatprofilepic>";
        } else {
            $xml_output .= "<chatprofilepic>" . "media/images/tubers/na.png" . "</chatprofilepic>";
        }

        $xml_output .= "<n_friends>" . $userInfo['n_friends'] . "</n_friends>";
        $xml_output .= "<n_following>" . $userInfo['n_following'] . "</n_following>";

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