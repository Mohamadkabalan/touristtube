<pre><?php
set_time_limit ( 0 );
ini_set('display_errors',1);
$database_name = "touristtube";


mysql_connect('localhost','root','mysql_root');
mysql_select_db($database_name);

//$sql="SELECT h.id, sc.city_id FROM `datastellar_hotel` as h inner join datastellar_country_sub_sub_sub_to_hotel as sh on h.id = sh.hotel_id inner join datastellar_country_sub_sub_sub as sc on sh.country_sub_sub_sub_id=sc.id  WHERE h.city_id =0 LIMIT 0, 20000";

//$sql="SELECT h.id, sc.city_id FROM `datastellar_hotel` as h inner join datastellar_country_sub_sub_to_hotel as sh on h.id = sh.hotel_id inner join datastellar_country_sub_sub as sc on sh.country_sub_sub_id=sc.id  WHERE h.city_id =0 LIMIT 0, 40000";

$sql="SELECT h.id, sc.city_id FROM `datastellar_hotel` as h inner join datastellar_country_sub_to_hotel as sh on h.id = sh.hotel_id and h.country_sub_id=sh.country_sub_id inner join datastellar_country_sub as sc on sh.country_sub_id=sc.id  WHERE h.city_id =0 LIMIT 0, 40000";

$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
    $sqr= "UPDATE `datastellar_hotel` SET `city_id`=".$r['city_id']." WHERE id=".$r['id'];
    mysql_query($sqr);    
}
