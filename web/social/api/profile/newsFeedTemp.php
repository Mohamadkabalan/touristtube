<?php

ini_set('display_errors', 0);
header('Content-type: application/json');
include("../heart.php");
$news_feed = newsfeedSearch( array(
		'limit' => 2,
		'page' => 1,
		'orderby' => 'feed_ts',
		'is_visible' => -1,
		'order' => 'd',
		'show_owner' => 1,
		'userid' => 42
	) );
echo 'test';
print_r($news_feed);
echo json_encode($news_feed);