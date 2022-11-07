<?php
$expath = "../";
header('Content-type: application/json');
include("../heart.php");
include_once('../profile/feeds_display_function.php');
$post_id = intval(UriGetArg('post'));
$submit_post_get = array_merge($request->query->all(),$request->request->all());
if(isset($submit_post_get['timezone'])){
    global $mobile_timezone;
    $mobile_timezone = $submit_post_get['timezone'];
}
//$channel_id = intval($_REQUEST['channel_id']);
//$user_id = mobileIsLogged($_REQUEST['S']);
$channel_id = intval($submit_post_get['channel_id']);
$user_id = mobileIsLogged($submit_post_get['S']);
$channelInfo = channelFromID($channel_id);
//$search_filter = xss_sanitize($_REQUEST['search_filter']);
$search_filter = $submit_post_get['search_filter'];
$search_filter = strtolower($search_filter);
$search_filter = ( $search_filter == "all" ) ? null : $search_filter;

$entity_type = null;
$action_type = null;
$media_type = null;

switch ($search_filter) {
    case "photos":
        $entity_type = SOCIAL_ENTITY_MEDIA;
        $media_type = 'i';
        break;
    case "videos":
        $entity_type = SOCIAL_ENTITY_MEDIA;
        $media_type = 'v';
        break;
    case "albums":
        $entity_type = SOCIAL_ENTITY_ALBUM;
        break;
    case "brochures":
        $entity_type = SOCIAL_ENTITY_BROCHURE;
        break;
    case "events":
        $entity_type = SOCIAL_ENTITY_EVENTS;
        break;
    case "news":
        $entity_type = SOCIAL_ENTITY_NEWS;
        break;
    case "posts":
        $entity_type = SOCIAL_ENTITY_POST;
        break;
    case "updates":
        $action_type = SOCIAL_ACTION_UPDATE;
        break;
}

//if( !$user_id || $user_id != intval($channelInfo['owner_id'])) die('not owner');

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

$from_date = date('Y') . '-' . date('n') . '-1';
$to_date = date('Y-m-t', strtotime($from_date));

$options_log = array(
    'limit' => $limit,
    'page' => $page,
    'orderby' => 'feed_ts',
    'is_visible' => 1,
    'order' => 'd',
    //'from_ts' => $from_date,
    'to_ts' => $to_date,
    'channel_id' => $channel_id,
    'entity_type' => $entity_type,
    'action_type' => $action_type,
    'media_type' => $media_type
);
$news_feed = newsfeedGroupingLogSearch($options_log); 

$res = feeds_display($news_feed);
echo json_encode($res);
//echo json_encode($news_feed);exit();
//include_once('feeds_display.php');
//exit();