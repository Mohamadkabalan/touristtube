<?php
/*
* Returns the events sponsored by a given channel.
* Param S: The session id.
* Param [limit]: Optional, the max rows to get, default 100.
* Param [page]: Optional, the current page.
* Param [fromdate]: Optional, from date.
* Param [todate]: Optional, to date.
*/
	
//	session_id($_REQUEST['S']);
//	session_start();
	$expath = "../";			
	//header("content-type: application/xml; charset=utf-8");  
        header('Content-type: application/json');
	include("../heart.php");
        include_once('feeds_display_function.php');
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
        
        
//	ini_set('display_errors', 1);
//	$user_id = $_SESSION['id'];
//        $user_id = mobileIsLogged($_REQUEST['S']);
        $user_id = mobileIsLogged($submit_post_get['S']);
        if( !$user_id ) die();
        if(isset($submit_post_get['timezone'])){
            global $mobile_timezone;
            $mobile_timezone = $submit_post_get['timezone'];
        }
//        echo $user_id;
//	if(isset($_REQUEST['limit']))
//		$limit = intval( $_REQUEST['limit'] );
	if(isset($submit_post_get['limit']))
		$limit = intval( $submit_post_get['limit'] );
	else
		$limit = 10;
//	if(isset($_REQUEST['page']))
//		$page = intval( $_REQUEST['page'] );
	if(isset($submit_post_get['page']))
		$page = intval( $submit_post_get['page'] );
	else
		$page = 0;
//	if(isset($_REQUEST['fromdate']))
//		$from_date = intval( $_REQUEST['fromdate'] );
	if(isset($submit_post_get['fromdate']))
		$from_date = intval( $submit_post_get['fromdate'] );
	else
		$from_date = null;
//	if(isset($_REQUEST['todate']))
//		$to_date = intval( $_REQUEST['todate'] );
	if(isset($submit_post_get['todate']))
		$to_date = intval( $submit_post_get['todate'] );
	else
		$to_date = null;
//        $from_filter = xss_sanitize($_REQUEST['from_filter']);
        $from_filter = (isset($submit_post_get['from_filter']) && $submit_post_get['from_filter']?$submit_post_get['from_filter']:null);
		
//        $activities_filter = xss_sanitize($_REQUEST['activities_filter']);
        $activities_filter = (isset($submit_post_get['activities_filter']) && $submit_post_get['activities_filter']?$submit_post_get['activities_filter']:null);
	
	// An array of keys to rename.
	$rename_keys = array(
						
                            );
	
	
	// Get the news feed.
//	$news_feed = newsfeedSearch( array(
//		'limit' => $limit,
//		'page' => $page,
//		'from_ts' => $from_date,
//		'to_ts' => $to_date,
//		'orderby' => 'feed_ts',
//		'is_visible' => -1,
//		'order' => 'd',
//		'show_owner' => 1,
//		'userid' => $user_id
//	) );
        
        $options = array ( 
            'limit' => $limit , 
            'page' => $page , 
            'userid' => $user_id ,
            'orderby' => 'id' ,
            'order' => 'd', 
            'channel_id' => null ,
            'from_filter' => $from_filter, 
            'activities_filter' => $activities_filter,
            //'action_type' => SOCIAL_ACTION_SHARE ,
            //'from_ts' => $from_date, 
            //'to_ts' => $to_date, 
            'is_visible' => 1 
        );
        $news_feed = newsfeedPageSearch( $options );
//        include_once('feeds_display.php');
        $feeds = feeds_display($news_feed);
        echo json_encode($feeds);