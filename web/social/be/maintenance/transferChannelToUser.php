<?php

set_time_limit ( 0 );
ini_set('display_errors',0);
$database_name = "touristtube";


mysql_connect( "192.168.2.5" , "root" , "7mq17psb" );
mysql_select_db($database_name);


$usid=1628;
$chid="576";
$new_user = 1262;
$sql="SELECT * FROM `cms_channel` WHERE `id` IN( $chid) AND owner_id = $usid ";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
$chArray = mysql_fetch_array($results, MYSQL_ASSOC);

$sqr= "UPDATE `cms_channel` SET `owner_id`='$new_user' WHERE id IN ($chid)";
mysql_query($sqr);

$sql="SELECT * FROM `cms_videos` WHERE userid = $usid and channelid IN($chid)";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
    $sqr= "UPDATE `cms_videos` SET `userid`='$new_user' WHERE id=".$r['id'];
    mysql_query($sqr);    
    $sqr= "UPDATE `cms_users_privacy_extand` SET `user_id`='$new_user' WHERE entity_id=".$r['id']." AND entity_type=1";
    mysql_query($sqr);    
    $sqr= "UPDATE `cms_social_newsfeed` SET `owner_id`='$new_user',`user_id`='$new_user' WHERE user_id=$usid and entity_id=".$r['id']." AND entity_type=1";
    mysql_query($sqr);
    $sqr= "UPDATE `cms_social_newsfeed` SET `owner_id`='$new_user' WHERE entity_id=".$r['id']." AND entity_type=1";
    mysql_query($sqr);
}

$sql="SELECT * FROM `cms_social_posts` WHERE user_id = $usid and channel_id IN($chid)";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
    $sqr= "UPDATE `cms_social_posts` SET `user_id`='$new_user' WHERE id=".$r['id'];
    mysql_query($sqr);
    $sqr= "UPDATE `cms_users_privacy_extand` SET `user_id`='$new_user' WHERE entity_id=".$r['id']." AND entity_type=15";
    mysql_query($sqr);
    $sqr= "UPDATE `cms_social_newsfeed` SET `owner_id`='$new_user',`user_id`='$new_user' WHERE user_id=$usid and entity_id=".$r['id']." AND entity_type=15";
    mysql_query($sqr);
    $sqr= "UPDATE `cms_social_newsfeed` SET `owner_id`='$new_user' WHERE entity_id=".$r['id']." AND entity_type=15";
    mysql_query($sqr);
}
$sqr= "UPDATE `cms_social_newsfeed` SET `owner_id`='$new_user',`user_id`='$new_user' WHERE user_id=$usid and channel_id IN($chid) ";
mysql_query($sqr);