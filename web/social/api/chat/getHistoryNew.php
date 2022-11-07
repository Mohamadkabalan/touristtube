<?php

//if (isset($_REQUEST['S'])) {
//    session_id($_REQUEST['S']);
$submit_post_get = array_merge($request->query->all(),$request->request->all());
if (isset($submit_post_get['S'])) {
    session_id($submit_post_get['S']);
    $expath = "../";

    include("../heart.php");
    $id = $_SESSION['id'];
//    $uid = $_REQUEST['uid'];
    $uid = $submit_post_get['uid'];
    $toUserName = getUserInfo($uid);
    $page = 0;
    $limit = 50;
    $page_get = $request->query->get('page','');
    $limit_get = $request->query->get('limit','');
//    if (isset($_GET['page']) && isset($_GET['limit'])) {
    if ($page_get && $limit_get) {
        $page = intval($page_get);
        $limit = intval($limit_get);
    }
    $historyget = userGetChatHistory($id, $uid, $page, $limit);

    $res = "";
    $res .= "<messages>";

    foreach ($historyget as $message) {
        $res .= "<message>";
        $res .= "<id>" . $message['id'] . "</id>";
        $res .= "<from_user>" . $message['from_user'] . "</from_user>";
        $res .= "<to_user>" . $message['to_user'] . "</to_user>";
        $res .= "<msg_txt>" . safeXML(htmlentities($message['msg_txt'])) . "</msg_txt>";
        $res .= "<from_tz>" . $message['from_tz'] . "</from_tz>";
        $res .= "<delivered_ts>" . $message['delivered_ts'] . "</delivered_ts>";
        $res .= "<msg_ts>" . $message['msg_ts'] . "</msg_ts>";

        $res .= "</message>";
    }
    $res .= "</messages>";
    header("content-type: application/json; charset=utf-8");
    //echo $res;
    $xml_cnt = str_replace(array("\n", "\r", "\t"), '', $res);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
    $xml_cnt = trim(str_replace('"', "'", $xml_cnt));
    $simpleXml = simplexml_load_string($xml_cnt);

    echo json_encode($simpleXml);    // returns a string wit
}