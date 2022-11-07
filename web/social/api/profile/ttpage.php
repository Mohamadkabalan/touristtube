<?php
$expath = "../";
//header('Content-type: application/json');
include("../heart.php");
include_once('feeds_display_function.php');
$post_id = intval(UriGetArg('post'));
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//$user_id = mobileIsLogged($_REQUEST['S']);
$user_id = mobileIsLogged($submit_post_get['S']);
if( !$user_id ) die();

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

$user_is_logged=0;
if($user_id){
    $user_is_logged=1;
}
$is_owner =1;
$is_owner_visible=1;
if($is_owner ==1){
    $is_owner_visible=-1;
}

//if($post_id!=0){
//    $news_feed_count = newsfeedGroupingLogSearch( array(			
//        'entity_type' => SOCIAL_ENTITY_POST,
//        'entity_id' => $post_id,
//        'is_visible' => $is_owner_visible,
//        'userid' => $userId,
//        'n_results' => true
//    ) );
//
//    $news_feed = newsfeedGroupingLogSearch( array(
//        'limit' => $limit,
//        'page' => $page,			
//        'entity_type' => SOCIAL_ENTITY_POST,
//        'entity_id' => $post_id,
//        'orderby' => 'id',
//        'is_visible' => $is_owner_visible,
//        'order' => 'd',
//        'userid' => $userId
//    ) );
//}else{
//    $news_feed_count = newsfeedGroupingLogSearch( array(
//        //'from_ts' => $from_date,
//        'to_ts' => $to_date,
//        'is_visible' => $is_owner_visible,
//        'userid' => $userId,
//        'n_results' => true
//    ) );
//    if( $news_feed_count==0 ){
//        $options_log = array(
//            'limit' => $limit,
//            'page' => $page,
//            'orderby' => 'id',
//            'is_visible' => $is_owner_visible,
//            'order' => 'd',
//            'userid' => $userId
//        );
//    }else{
//        $options_log = array(
//            'limit' => 10,
//            'page' => 0,
//            'to_ts' => $to_date,
//            'orderby' => 'id',
//            'is_visible' => $is_owner_visible,
//            'order' => 'd',
//            'userid' => $userId
//        );
//    }
//    $news_feed = newsfeedGroupingLogSearch( $options_log );
//}
//echo json_encode($news_feed);exit();
$from_date = date('Y') . '-' . (date('n')) . '-1';
$to_date = date('Y-m-t', strtotime($from_date));
$news_feed = newsfeedGroupingLogSearch( array(
        'limit' => $limit,
        'page' => $page,
        'to_ts' => $to_date,
        'orderby' => 'id',
        'is_visible' => 1,
        'order' => 'd',
        'userid' => $user_id
) );

$exept_array = array(SOCIAL_ENTITY_HOTEL_AIRPOT,SOCIAL_ENTITY_HOTEL_SERVICE,SOCIAL_ENTITY_HOTEL_CLEANLINESS,SOCIAL_ENTITY_HOTEL_INTERIOR,SOCIAL_ENTITY_HOTEL_PRICE,SOCIAL_ENTITY_HOTEL_FOODDRINK,SOCIAL_ENTITY_HOTEL_INTERNET,SOCIAL_ENTITY_HOTEL_NOISE,SOCIAL_ENTITY_LANDMARK_FOODAVAILABLE,SOCIAL_ENTITY_LANDMARK_BATHROOMFACILITIES,SOCIAL_ENTITY_LANDMARK_STAIRS,SOCIAL_ENTITY_LANDMARK_STORAGE,SOCIAL_ENTITY_LANDMARK_PARKING,SOCIAL_ENTITY_LANDMARK_WHEELCHAIR,SOCIAL_ENTITY_RESTAURANT_TIME,SOCIAL_ENTITY_RESTAURANT_NOISE,SOCIAL_ENTITY_RESTAURANT_PRICE,SOCIAL_ENTITY_RESTAURANT_ATMOSPHERE,SOCIAL_ENTITY_RESTAURANT_SERVICE,SOCIAL_ENTITY_RESTAURANT_CUISINE,SOCIAL_ENTITY_HOTEL,SOCIAL_ENTITY_RESTAURANT,SOCIAL_ENTITY_LANDMARK);
//include_once('feeds_display.php');
$feeds = feeds_display($news_feed);
echo json_encode($feeds);