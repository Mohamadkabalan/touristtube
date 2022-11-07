<?php

$expath = "../";
header("content-type: application/json; charset=utf-8");
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
/*echo '<?xml version="1.0" encoding="utf-8"?>';

/**
 * returns a video's comments
 * @param integer $vid the video ID
 * @param integer $nlimit the maximum  umber of comment record to return
 * @param integer $page number of pages of comment records to skip
 * @param string $sortby column name to sort by
 * @param string $sort ascending or descending sort 'ASC' | 'DESC'
 * @return mixed array of the video's comment records or false if none found
 */
//if (isset($_REQUEST['vid'])) {
//    $vid = $_REQUEST['vid'];
//}
//if (isset($_REQUEST['page'])) {
//    $page = $_REQUEST['page'];
//}
//if (isset($_REQUEST['limit'])) {
//    $limit = $_REQUEST['limit'];
//}
if (isset($submit_post_get['vid'])) {
    $vid = $submit_post_get['vid'];
}
if (isset($submit_post_get['page'])) {
    $page = $submit_post_get['page'];
}
if (isset($submit_post_get['limit'])) {
    $limit = $submit_post_get['limit'];
}

if (is_numeric($page) && is_numeric($limit) && is_numeric($vid)) {
    $res = "<comments>";
    $datas = videoGetComments($vid, $limit, $page);

    foreach ($datas as $data) {
        $res .= "<comment>";
        $res .= "<id>" . $data['id'] . "</id>";

        $userinfo = getUserInfo($data['user_id']);
        $res .= '<user>' . $userinfo['YourUserName'] . '</user>';

        if ($userinfo['profile_Pic'] != "") {
            //$res .= "<userprofilepic>"."media/images/tubers/crop_".substr($userinfo['profile_Pic'],0,strlen($userinfo['profile_Pic'])-3)."png"."</userprofilepic>";
            $res .= "<userprofilepic>" . "media/images/tubers/" . $userinfo['profile_Pic'] . "</userprofilepic>";
        } else {
            $res .= "<userprofilepic>" . "media/images/tubers/na.png" . "</userprofilepic>";
        }
        $res .= "<video_id>" . $data['video_id'] . "</video_id>";
        $res .= "<text>" . safeXML($data['comment_text']) . "</text>";
        $res .= "<date>" . $data['comment_date'] . "</date>";
        $res .= "<likevalue>" . $data['like_value'] . "</likevalue>";
        $res .= "<upvotes>" . $data['down_votes'] . "</upvotes>";
        $res .= "<downvotes>" . $data['up_votes'] . "</downvotes>";
        $res .= "</comment>";
    }
    $res .= "</comments>";
    //comment_id	user_id	video_id	comment_text	comment_date	comment_published	like_value up_votes - down_votes	up_votes	down_votes
    //echo $res;
    $xml_cnt = str_replace(array("\n", "\r", "\t"), '', $res);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
    $xml_cnt = trim(str_replace('"', "'", $xml_cnt));
    $simpleXml = simplexml_load_string($xml_cnt);

    echo json_encode($simpleXml);    // returns a string wit
}