<?php

$expath = "../";
header('Content-type: application/json');
include("../heart.php");
include_once('feeds_display_function.php');
//$user_id = mobileIsLogged($_REQUEST['S']);
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$user_id = mobileIsLogged($submit_post_get['S']);
if( !$user_id ) die();
if(isset($submit_post_get['timezone'])){
    global $mobile_timezone;
    $mobile_timezone = $submit_post_get['timezone'];
}
//if(isset($_REQUEST['limit']))
//    $limit = intval( $_REQUEST['limit'] );
if(isset($submit_post_get['limit']))
    $limit = intval( $submit_post_get['limit'] );
else
    $limit = 25;
//if(isset($_REQUEST['page']))
//    $page = intval( $_REQUEST['page'] ); 
if(isset($submit_post_get['page']))
    $page = intval( $submit_post_get['page'] ); 
else
    $page = 0;
//if(isset($_REQUEST['type']))
//    $type = intval( $_REQUEST['type'] ); 
if(isset($submit_post_get['type']))
    $type =  $submit_post_get['type'] ; 
$channel_id = null;
if(isset($submit_post_get['channel_id']))
    $channel_id = $submit_post_get['channel_id'];


global $CONFIG_EXEPT_ARRAY;
$exept_array = $CONFIG_EXEPT_ARRAY;
//$exept_array = array(SOCIAL_ENTITY_HOTEL_AIRPOT,SOCIAL_ENTITY_HOTEL_SERVICE,SOCIAL_ENTITY_HOTEL_CLEANLINESS,SOCIAL_ENTITY_HOTEL_INTERIOR,SOCIAL_ENTITY_HOTEL_PRICE,SOCIAL_ENTITY_HOTEL_FOODDRINK,SOCIAL_ENTITY_HOTEL_INTERNET,SOCIAL_ENTITY_HOTEL_NOISE,SOCIAL_ENTITY_LANDMARK_FOODAVAILABLE,SOCIAL_ENTITY_LANDMARK_BATHROOMFACILITIES,SOCIAL_ENTITY_LANDMARK_STAIRS,SOCIAL_ENTITY_LANDMARK_STORAGE,SOCIAL_ENTITY_LANDMARK_PARKING,SOCIAL_ENTITY_LANDMARK_WHEELCHAIR,SOCIAL_ENTITY_RESTAURANT_TIME,SOCIAL_ENTITY_RESTAURANT_NOISE,SOCIAL_ENTITY_RESTAURANT_PRICE,SOCIAL_ENTITY_RESTAURANT_ATMOSPHERE,SOCIAL_ENTITY_RESTAURANT_SERVICE,SOCIAL_ENTITY_RESTAURANT_CUISINE,SOCIAL_ENTITY_HOTEL,SOCIAL_ENTITY_RESTAURANT,SOCIAL_ENTITY_LANDMARK);
$res = array();
if(isset($type)){
    switch($type){
        case 'tuber';
            $news_feed_tuber = newsfeedGroupingNotificationsSearch( array(                    
                'limit' => $limit,
                'page' => $page,
                'orderby' => 'id',
                'order' => 'd',
                'is_notification' => 1,
                'userid' => $user_id,
                'escape_entity_type' => SOCIAL_ENTITY_FLASH
            ) );
            $res['tuber'] = feeds_display($news_feed_tuber);
            break;
        case 'echoes':
            $news_feed_echoes = newsfeedGroupingNotificationsSearch( array(                    
                'limit' => $limit,
                'page' => $page,
                'orderby' => 'id',
                'order' => 'd',
                'is_notification' => 1,
                'userid' => $user_id,
                'entity_type' => SOCIAL_ENTITY_FLASH
            ) );
            $res['echoes'] = feeds_display($news_feed_echoes);
            break;
        case 'channels':
            $options_log = array(
                'limit' => $limit,
                'page' => $page,            
                'orderby' => 'id',
                'order' => 'd',
                'channel_id' => $channel_id
            );
            $news_feed_channel = newsfeedGroupingNotificationsSearch( $options_log );
            $channelInfo = channelGetInfo($channel_id);
            $channel_feeds = feeds_display($news_feed_channel);
            $channel = array('id'=>$channel_id, 'name'=>$channelInfo['channel_name'], 'feeds'=>$channel_feeds);
            $res['channels'][] = $channel;
            break;
    }
}
else{
    $news_feed_echoes = newsfeedGroupingNotificationsSearch( array(                    
        'limit' => $limit,
        'page' => $page,
        'orderby' => 'id',
        'order' => 'd',
        'is_notification' => 1,
        'userid' => $user_id,
        'entity_type' => SOCIAL_ENTITY_FLASH
    ) );

    $news_feed_tuber = newsfeedGroupingNotificationsSearch( array(                    
        'limit' => $limit,
        'page' => $page,
        'orderby' => 'id',
        'order' => 'd',
        'is_notification' => 1,
        'userid' => $user_id,
        'escape_entity_type' => SOCIAL_ENTITY_FLASH
    ) );
    $res['tuber'] = feeds_display($news_feed_tuber);
    $res['echoes'] = feeds_display($news_feed_echoes);
    $res['channels'] = array();
    $exept_array = array();
    $options2 = array('owner_id' => $user_id, 'published' => 1, 'page' => 0, 'limit' => 5);
    $channelListAll = channelSearch($options2);
	
	if ($channelListAll)
		foreach($channelListAll as $channel){
			$options_log = array(
				'limit' => $limit,
				'page' => $page,            
				'orderby' => 'id',
				'order' => 'd',
				//'from_ts' => $from_date,
				//'to_ts' => $to_date,
				'channel_id' => $channel['id']
			);
			$news_feed_channel = newsfeedGroupingNotificationsSearch( $options_log );

			$channel_feeds = feeds_display($news_feed_channel);
			$channel = array('id'=>$channel['id'], 'name'=>$channel['channel_name'], 'feeds'=>$channel_feeds);
			$res['channels'][] = $channel;
		}
}
echo json_encode($res);
