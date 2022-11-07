<?php

set_time_limit ( 0 );
ini_set('display_errors',0);
$database_name = "touristtube";


mysql_connect( "192.168.2.5" , "root" , "7mq17psb" );
mysql_select_db($database_name);

/*$date_now=date('Y-m-d');
$date_now_before1= date('Y-m-d', strtotime($date_now) - 7776000) ;
$date_now_before2= date('Y-m-d', strtotime($date_now) - 604800) ;

$sql="SELECT * FROM `cms_videos` WHERE 1";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) { 
    $int= rand(strtotime($date_now_before1),strtotime($date_now_before2));
    $date_now_now= date('Y-m-d H:i:s', $int) ;
    $sqr= "UPDATE `cms_videos` SET `pdate`='".$date_now_now."' WHERE id=".$r['id'];
    mysql_query($sqr); 
}
exit;*/
//SELECT * FROM `cms_videos` as v WHERE `userid`=319 and NOT EXISTS (SELECT c.id from cms_videos_catalogs as c where c.video_id=v.id) and v.published<>-2
//SELECT u.id,u.YourUserName,c.catalog_name FROM `cms_users` as u LEFT join cms_users_catalogs as c on c.user_id=u.id and c.published=1 WHERE (u.id>=501 and u.id<=939) or u.id=331 or u.id=319
/*$date_now=date('Y-m-d');
$date_now_before1= date('Y-m-d', strtotime($date_now) - 3369600) ;
$date_now_before2= date('Y-m-d', strtotime($date_now) - 259200) ;
$sql="SELECT * FROM `cms_users` WHERE `YourEmail`='user@touristtube.com' ";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) { 
        $int= rand(strtotime($date_now_before1),strtotime($date_now_before2));
        $date_now_now= date('Y-m-d H:i:s', $int) ;
        $sqr= "UPDATE `cms_users` SET RegisteredDate='".$date_now_now."' WHERE id=".$r['id'];
        mysql_query($sqr);
      //$userArray[]=array($r['id'],$r['YourUserName']);  
}*/

/*$userArray=array();
$sql="SELECT c.id FROM `cms_users` as u LEFT join cms_users_catalogs as c on c.user_id=u.id and c.published=1 WHERE (u.id>=501 and u.id<=939) or u.id=331 or u.id=319";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) { 
       $sql = "DELETE FROM cms_videos_catalogs where catalog_id='".$r['id']."'";
       mysql_query($sql);
       $sql= "UPDATE `cms_users_catalogs` SET `published`='-2' WHERE id=".$r['id'];
       mysql_query($sql);
}
exit;*/
/*$sql="SELECT * FROM `cms_users` WHERE `id` >= 939 AND `id` <= 950 ";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) { 
       $userArray[]=array($r['id'],$r['YourUserName']);  
}*/
$str ='<table><tr><td>user id</td><td>user name</td><td>album name</td></tr>';
    
    /*$sql="SELECT * FROM `cms_users_catalogs` WHERE `user_id` IN(319) and id NOT IN(381,351) AND `published` =1 and channelid=0";
    $results = mysql_query($sql) or die( mysql_error());
    $total =mysql_num_rows($results);
    $i=0;
     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {     
            $sql1="SELECT * FROM `cms_videos_catalogs` WHERE `catalog_id` = ".$r['id'];
            $results1 = mysql_query($sql1) or die( mysql_error());
            $user_arr = $userArray[$i];
            $i++;
            $arr_item = $user_arr[0];
            $arr_item_name = $user_arr[1];
            $cat_name = $r['catalog_name'];
            $str .="<tr><td>$arr_item</td><td>$arr_item_name</td><td>$cat_name</td></tr>";
            $sqr= "UPDATE `cms_users_catalogs` SET `user_id`='$arr_item' WHERE id=".$r['id'];
            mysql_query($sqr);    
            $sqr= "UPDATE `cms_users_privacy_extand` SET `user_id`='$arr_item' WHERE entity_id=".$r['id']." AND entity_type=3";
            mysql_query($sqr);    
            $sqr= "UPDATE `cms_social_newsfeed` SET `owner_id`='$arr_item' WHERE entity_id=".$r['id']." AND entity_type=3";
            mysql_query($sqr);
        while($r1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {     
            $sqr= "UPDATE `cms_videos` SET `userid`='$arr_item' WHERE id=".$r1['video_id'];
            mysql_query($sqr);    
            $sqr= "UPDATE `cms_users_privacy_extand` SET `user_id`='$arr_item' WHERE entity_id=".$r1['video_id']." AND entity_type=1";
            mysql_query($sqr);    
            $sqr= "UPDATE `cms_social_newsfeed` SET `owner_id`='$arr_item' WHERE entity_id=".$r1['video_id']." AND entity_type=1";
            mysql_query($sqr);    
        }
    }*/
    
    //$sql="SELECT * FROM `cms_videos` WHERE ( (userid >=672 and userid<=680) ) and published <>-2 and channelid=0";
    //$sql="SELECT * FROM `cms_videos` WHERE ( (userid >=682 and userid<=687) OR (userid >=689 and userid<=704) ) and published <>-2 and channelid=0";
    /*$sql="SELECT * FROM `cms_videos` WHERE userid IN (628,816) and published <>-2 and channelid=0";
        $results = mysql_query($sql) or die( mysql_error());
        $total =mysql_num_rows($results);
        $i=0;
         while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
            $arr_item = 625;
            $sqr= "UPDATE `cms_users` SET `published`='0' WHERE id=".$r['userid'];
            mysql_query($sqr);    
            $sqr= "UPDATE `cms_videos` SET `userid`='$arr_item' WHERE id=".$r['id'];
            mysql_query($sqr);    
            $sqr= "UPDATE `cms_users_privacy_extand` SET `user_id`='$arr_item' WHERE entity_id=".$r['id']." AND entity_type=1";
            mysql_query($sqr);    
            $sqr= "UPDATE `cms_social_newsfeed` SET `owner_id`='$arr_item' WHERE entity_id=".$r['id']." AND entity_type=1";
            mysql_query($sqr);
        }
$str .='<table>';
echo $str;*/