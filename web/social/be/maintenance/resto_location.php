<pre><?php
set_time_limit ( 0 );
ini_set('display_errors',0);
$database_name = "touristtube";


//mysql_connect( "192.168.2.5" , "root" , "7mq17psb" );
mysql_connect('localhost','root','mysql_root');
mysql_select_db($database_name);

mysql_query("SET NAMES utf8");
/*$sql="SELECT DISTINCT `locality` , `region` , `country`, `admin_region` FROM `global_restaurants` where published=1";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);

while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
    $sqr= "INSERT INTO `global_restaurants_location`(`city_id`, `locality`, `region`, `country`, `admin_region`) VALUES (0,'".$r['locality']."','".$r['region']."','".$r['country']."' ,'".$r['admin_region']."')";
    mysql_query($sqr);    
}*/

/*$sql="SELECT * FROM `india_postal_code`";

$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);

while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
    $sqr= "INSERT INTO `cms_zipcodes`(`country_code`, `zip_code`, `city`, `state`, `longitude`, `lattitude`) VALUES ('IN','".$r['pincode']."','".$r['district']."','".$r['state']."','','')";    
    mysql_query($sqr);    
}*/