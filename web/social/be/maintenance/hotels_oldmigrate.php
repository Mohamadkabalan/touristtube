<pre><?php
set_time_limit ( 0 );
ini_set('display_errors',0);
$database_name = "touristtube";


//mysql_connect( "192.168.2.5" , "root" , "7mq17psb" );
mysql_connect('localhost','root','mysql_root');
mysql_select_db($database_name);

/*

$sql="SELECT f.id, t.id as tid FROM `discover_hotels_feature` as f inner join discover_hotels_feature_type AS t on t.title=f.feature_type WHERE 1";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {     
    $sqr= 'UPDATE `discover_hotels_feature` SET `feature_type`="'.$r['tid'].'" WHERE id='.$r['id'];
    mysql_query($sqr);    
}*/

/*
$sql="SELECT f.id, h.h_id FROM `discover_hotels_facilities` AS f
        INNER JOIN discover_hotels AS h ON f.hotel_id = h.id
      WHERE 1 ";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {     
    $sqr= "UPDATE `discover_hotels_facilities` SET `hotel_id`=".$r['h_id']." WHERE id=".$r['id'];
    mysql_query($sqr);    
}

$sql="SELECT f.id, h.h_id FROM `discover_hotels_images` AS f
        INNER JOIN discover_hotels AS h ON f.hotel_id = h.id
      WHERE 1 ";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {     
    $sqr= "UPDATE `discover_hotels_images` SET `hotel_id`=".$r['h_id']." WHERE id=".$r['id'];
    mysql_query($sqr);    
}

$sql="SELECT f.id, h.h_id FROM `discover_hotels_rooms` AS f
        INNER JOIN discover_hotels AS h ON f.hotel_id = h.id
      WHERE 1 ";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {     
    $sqr= "UPDATE `discover_hotels_rooms` SET `hotel_id`=".$r['h_id']." WHERE id=".$r['id'];
    mysql_query($sqr);    
}

$sql="SELECT f.id, h.h_id FROM `discover_hotels_reviews` AS f
        INNER JOIN discover_hotels AS h ON f.hotel_id = h.id
      WHERE 1 ";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {     
    $sqr= "UPDATE `discover_hotels_reviews` SET `hotel_id`=".$r['h_id']." WHERE id=".$r['id'];
    mysql_query($sqr);    
}*/