<?php

$expath = "../";
header('Content-type: application/json');
include($expath."heart.php");
$item_per_page = 10;
$user_ID = mobileIsLogged($_REQUEST['S']);

global $dbConn;
$params = array();  
$chatviewlogstatus = "UPDATE `cms_chat_log` SET `viewed` = '1' WHERE `to_user` =:User_ID AND `from_user`= :Sender;";
$params[] = array(  "key" => ":User_ID",
                    "value" =>$user_ID);
$params[] = array(  "key" => ":Sender",
                    "value" =>$_REQUEST['sender']);
$select = $dbConn->prepare($chatviewlogstatus);
PDO_BIND_PARAM($select,$params);
$result    = $select->execute();

function chatGetUserLogsCount($userid=0,$from_userid=0){
    global $dbConn;
    $params = array();  
    $query = "SELECT COUNT(id) as cnt FROM `cms_chat_log` where `to_user` IN (:Userid,:From_userid) AND `from_user` IN (:Userid,:From_userid) AND from_user <> to_user";
    $params[] = array(  "key" => ":Userid",
                        "value" =>$userid);
    $params[] = array(  "key" => ":From_userid",
                        "value" =>$from_userid);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $ret    = $select->execute();
    $count = $select->fetch();
    return $count['cnt'];
}	

function chatGetUserLogs($userid=0,$from_userid=0,$page_number=0, $item_per_page){
    global $dbConn;
    $params = array(); 
    $query = "(SELECT `id` , `from_user` , `to_user` , `msg_txt` ,`msg_ts`,`viewed`, `voice_message`, `file_share`, `file_id`, `location_share`, `latitude`, `longitude`, `status`, `message_id` FROM `cms_chat_log` where `to_user` IN (:Userid,:From_userid) AND `from_user` IN (:Userid,:From_userid) AND from_user <> to_user ORDER BY `id` DESC LIMIT $page_number, $item_per_page) ORDER BY id ASC";
    $params[] = array(  "key" => ":Userid",
                        "value" =>$userid);
    $params[] = array(  "key" => ":From_userid",
                        "value" =>$from_userid);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret_arr = array();
    $ret    = $select->rowCount();
    if ( !$res || $ret == 0 ) return array();
    $row = $select->fetchAll();
    foreach($row as $row_item){
            $new_row = $row_item;
            if($row_item['location_share'] == '1'){
                $type = 'locationShare';
            }
            else{
                $type = 'text';
            }
            $new_row['type'] = $type;
            $ret_arr[] = $new_row;
    }
    return $ret_arr;
}

$page_number = filter_var($_REQUEST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);

$logs = chatGetUserLogs($user_ID,$_REQUEST['sender'],$page_number * $item_per_page,$item_per_page);

$totallogs = chatGetUserLogsCount($user_ID,$_REQUEST['sender']);

$total_pages = ceil($totallogs/$item_per_page); 
$i = 0;
foreach($logs as $log){
    $userInfo= getUserInfo($log['from_user']);
    $userName= returnUserDisplayName( $userInfo );
    $logs[$i]['utc_time'] = gmdate("r", strtotime($log['msg_ts']) );
    $logs[$i]['userName'] = $userName;
    $logs[$i]['msg_txt'] = strip_tags($logs[$i]['msg_txt']);
    $i++;
}
        
echo json_encode(array('logs' => $logs, 'total_count' => $totallogs, 'total_pages' => $total_pages));