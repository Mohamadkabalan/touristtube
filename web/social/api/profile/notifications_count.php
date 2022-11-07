<?php
$expath = "../";
header('Content-type: application/json');
include("../heart.php");

$submit_post_get = array_merge($request->query->all(),$request->request->all());
$user_id = mobileIsLogged($submit_post_get['S']);
if( !$user_id ) die();

global $mobile_timezone;
if(!isset($mobile_timezone) || trim($mobile_timezone) === ''){
    $mobile_timezone = 0;
}
$interval = new DateInterval('PT'.$mobile_timezone.'M');
$from_time_dt = new DateTime($submit_post_get['from_time']);
if($mobile_timezone > 0){
    $from_time_dt->sub($interval);
}
else{
    $from_time_dt->add($interval);
}

//$from_time = $submit_post_get['from_time'];

$from_time = $from_time_dt->format('Y-m-d H:i:s');

$news_feed_echoes = newsfeedGroupingNotificationsSearch( array(            
    'is_notification' => 1,
    'userid' => $user_id,
    'entity_type' => SOCIAL_ENTITY_FLASH,
    'from_time' => $from_time,
    'n_results' => true
) );

$tubers = newsfeedGroupingNotificationsSearch(array(
    'is_notification' => 1,
    'userid' => $user_id,
    'from_time' => $from_time,
    'escape_entity_type' => SOCIAL_ENTITY_FLASH,
    'n_results' => true
));

$channelListAll = channelSearch(array('owner_id' => $user_id, 'published' => 1, 'page' => 0, 'limit' => 5));
$channels = 0;
if( $channelListAll && is_array($channelListAll) ){
    foreach($channelListAll as $channel){
        $options_log = array(
            'from_time' => $from_time,
            'channel_id' => $channel['id'],
            'n_results' => true
        );
        $news_feed_channel = newsfeedGroupingNotificationsSearch( $options_log );
        $channels += $news_feed_channel;
    }
}
echo json_encode(array('count' => $news_feed_echoes + $tubers + $channels));