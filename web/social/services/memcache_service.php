<?php
//set_time_limit(0);
$path = "../";
$bootOptions = array("loadDb" => 1, "requireLogin" => 0, "loadLocation" => 0);
include_once ( $path . "inc/service_bootstrap.php" );
tt_global_set('isLogged', true);

$CachedUserVideos = array();
$thisRun = null;

while (TRUE){
    $lastRun = $thisRun;
    $thisRun = date("Y-m-d H:i:s");
    $datenow = date("Y-m-d");
    $row = array();
    // query db for all logged in users
//    $query_logged_in_users = "SELECT DISTINCT (`user_id`) FROM `cms_tubers` WHERE user_id IS NOT NULL and user_id>0 AND DATE(expiry_date)>='$datenow'";
//
//    $select = $dbConn->prepare($query_logged_in_users);
//    $res = $select->execute();
//    $row = $select->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($row as $row_item){
        $userId = $row_item['user_id'];
//        $userId = 1;
        $feedlimit = 20;
        $options = array ( 
            'limit' => $feedlimit , 
            'page' => 0 , 
            'userid' => $userId , 
            'orderby' => 'id' ,
            'order' => 'd',
            'channel_id' => null , 
            'is_visible' => -1
        );
        $newsFeeds = newsfeedPageSearch( $options );
        
        $options = array(
            'limit' => $feedlimit,
            'page' => 0,
            'orderby' => 'id',
            'order' => 'd',
            'is_notification' => 1,
            'userid' => $userId
        );
        
        $Notifications_feed = newsfeedGroupingNotificationsSearch( $options );
        
        $news_feedTT_count = newsfeedGroupingLogSearch( array(
            'is_visible' => -1,
            'userid' => $userId,
            'owner_id' => $userId,
            'n_results' => true
        ) );
        $options = array(
            'limit' => 10,
            'page' => 0,
            'orderby' => 'id',
            'is_visible' => -1,
            'order' => 'd',
            'owner_id' => $userId,
            'userid' => $userId
        );        
        $news_feedTT = newsfeedGroupingLogSearch( $options );        
    }
    echo 'done';
    //sleep for 180s
    sleep(180);
    
}