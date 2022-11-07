<?php

//getConnectedtubers($channel_id);
session_id($_REQUEST['S']);

$expath = "../";
header("content-type: application/json; charset=utf-8");
include("../heart.php");

$userID = $_SESSION['id'];

$xml_output = "";
$xml_output .= "<friendlist order='channel_connections'>";

$cid = null;
//if (isset($_REQUEST['cid'])) {
//    $cid = $_REQUEST['cid'];
$submit_post_get = array_merge($request->query->all(),$request->request->all());
if (isset($submit_post_get['cid'])) {
    $cid = $submit_post_get['cid'];
}
//$connetedUsers = getConnectedtubers($cid, "true");
$connetedUsers = getSponsoredChannel($cid);
foreach ($connetedUsers as $connectedUser) {
    $data = getUserInfo($connectedUser);
    if ($userID != $data['id']) {
        /* $userInfo = getUserInfo($connetedUser);

          $uname = $userInfo['YourUserName'];
          $profile_pic = "media/images/tubers/" . $userInfo['profile_Pic'];
          $fname = htmlEntityDecode($userInfo['fname']);
          $lname = htmlEntityDecode($userInfo['lname']);
          if($fname == '') list($fname,$lname) = explode(' ',$userInfo['FullName'], 2);

          $res .= '<user>';
          $res .= '<id>'.$connetedUser.'</id>';
          $res .= '<uname>' . $uname . '</uname>';
          $res .= '<fname>'. $fname . '</fname>';
          $res .= '<lname>' . $lname . '</lname>';
          $res .= '<prof_pic>' . $profile_pic . '</prof_pic>';
          $res .= '</user>';
         */
        //var_dump($data);
        $xml_output .= "<friend>";
        $xml_output .= "<id>" . $data['id'] . "</id>";
        //$xml_output .= "<rid>".$data['id']."</rid>";
        if ($data['FullName'] != '')
            $xml_output .= "<fullname>" . safeXML($data['FullName']) . "</fullname>";
        else
            $xml_output .= "<fullname>" . safeXML($data['YourUserName']) . "</fullname>";
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

        // $userInfo = getUserInfo( $data['id'] );
        if ($data['profile_Pic'] != "") {
            $xml_output .= "<chatprofilepic>" . "/media/images/tubers/crop_" . substr($data['profile_Pic'], 0, strlen($data['profile_Pic']) - 3) . "png" . "</chatprofilepic>";
        } else {
            $xml_output .= "<chatprofilepic>" . "media/images/tubers/na.png" . "</chatprofilepic>";
        }

        /* $xml_output .= "<n_friends>".$userInfo['n_friends'] . "</n_friends>";
          $xml_output .= "<n_following>".$userInfo['n_following'] . "</n_following>"; */

        $xml_output .= "<prof_pic>" . "media/images/tubers/" . $data['profile_Pic'] . "</prof_pic>";
        $xml_output .= "</friend>";
    }
}

//var_dump($data);

$xml_output .= "</friendlist>";
header("content-type: application/json; charset=utf-8");
//echo $xml_output;

$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $xml_output);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string wit