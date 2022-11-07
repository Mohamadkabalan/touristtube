<?php
$expath = "../";
//header('Content-type: application/json');
include("../heart.php");
include_once('feeds_display_function.php');
$submit_post_get = array_merge($request->query->all(),$request->request->all());
if(isset($submit_post_get['timezone'])){
    global $mobile_timezone;
    $mobile_timezone = $submit_post_get['timezone'];
}
$post_id = intval(UriGetArg('post'));
//$user_id = mobileIsLogged($_REQUEST['S']);
//$other_user_id = $_REQUEST['uid'];
$user_id = mobileIsLogged($submit_post_get['S']);
$other_user_id = $submit_post_get['uid'];
if( !$user_id && !isset($other_user_id)) die();
$feed_user_id = $user_id;
if(isset($other_user_id) && intval($other_user_id) > 0){
    $feed_user_id = $other_user_id;
}
//if(isset($_REQUEST['limit']))
//    $limit = intval( $_REQUEST['limit'] );
if(isset($submit_post_get['limit']))
    $limit = intval( $submit_post_get['limit'] );
else
    $limit = 10;
//if(isset($_REQUEST['page']))
//    $page = intval( $_REQUEST['page'] ); 
if(isset($submit_post_get['page']))
    $page = intval( $submit_post_get['page'] ); 
else
    $page = 0;
//$activities_filter= xss_sanitize($_REQUEST['activities_filter']);
$activities_filter= $submit_post_get['activities_filter'];
if($activities_filter==""){
    $activities_filter=NULL;	
}
$user_is_logged=0;
if($user_id){
    $user_is_logged=1;
}
$is_owner =1;
if(intval($other_user_id) > 0 && intval($user_id) != intval($other_user_id)){
    $is_owner = 0;
}
$is_owner_visible=1;
if($is_owner ==1){
    $is_owner_visible=-1;
}
if($activities_filter=="hidden"){
    $activities_filter=NULL;
    $is_owner_visible=0;	
}
$from_date = date('Y') . '-' . (date('n')) . '-1';
$to_date = date('Y-m-t', strtotime($from_date));
$news_feed = newsfeedGroupingLogSearch( array(
        'limit' => $limit,
        'page' => $page,
        'to_ts' => $to_date,
        'orderby' => 'id',
        'is_visible' => $is_owner_visible,
        'order' => 'd',
        'userid' => $feed_user_id,
        'activities_filter' => $activities_filter
) );
global $CONFIG_EXEPT_ARRAY;
$exept_array = $CONFIG_EXEPT_ARRAY;
$feeds = feeds_display($news_feed);
echo json_encode($feeds);