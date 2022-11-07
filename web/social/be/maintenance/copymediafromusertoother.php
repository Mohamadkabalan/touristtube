<?php

set_time_limit ( 0 );
ini_set('display_errors',0);
$database_name = "touristtube";


mysql_connect( "192.168.2.5" , "root" , "7mq17psb" );
mysql_select_db($database_name);


//$arr = array(1596,1594,1593,1592,1590,1589,1588,1587,1585,1584,1582,1580,1579,1578,1577,1576,1575,1574,1572,1571);
$arr = array(1596,1575,1574,1572,1571);

foreach($arr as $arr_item) {
    $sql="SELECT * FROM `cms_videos` WHERE `id` >= 49211 AND `userid` = 483 LIMIT 0, 50";
    $results = mysql_query($sql) or die( mysql_error());
    $total =mysql_num_rows($results);
     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {     
        $sqr= "UPDATE `cms_videos` SET `userid`='$arr_item' WHERE id=".$r['id'];
        mysql_query($sqr);    
        $sqr= "UPDATE `cms_users_privacy_extand` SET `user_id`='$arr_item' WHERE entity_id=".$r['id']." AND entity_type=1";
        mysql_query($sqr);    
        $sqr= "UPDATE `cms_social_newsfeed` SET `owner_id`='$arr_item',`user_id`='$arr_item' WHERE entity_id=".$r['id']." AND entity_type=1";
        mysql_query($sqr);    
    }
}