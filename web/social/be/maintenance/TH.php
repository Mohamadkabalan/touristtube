<pre><?php
set_time_limit ( 0 );
ini_set('display_errors',1);
$database_name = "touristtube";


mysql_connect('172.16.124.204','mysql_root','Mr4+%FINDZm,:AGL');
mysql_select_db($database_name);

$couters =0;
$ct=0;
$ct2=0;
$ct3=0;
$limit=2500;


$skval =$couters*$limit;    
//$sql="SELECT id, name, latitude, longitude,country_code,city FROM cms_hotel WHERE published=1 AND longitude <> 0 AND latitude <> 0 LIMIT $skval, $limit;";
$sql="SELECT `city_id` FROM `cms_thingstodo_details` WHERE 1 GROUP BY `city_id`";
//$couters++;
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {    
    $city_id = $r['city_id'];    
    $sql="SELECT * FROM `webgeocities` WHERE `id` =$city_id";
    $results1 = mysql_query($sql) or die( mysql_error());
    $total1 =mysql_num_rows($results1);

    if($total1==1){
	$r1 = mysql_fetch_array($results1, MYSQL_ASSOC);
	$name = addslashes($r1['name']);
	//$name = str_replace('-', ' ', $name);
	$sqlN="UPDATE `global_restaurants` SET `city_id`=".$r1['id']." WHERE `locality` LIKE '".$name."' AND `country` LIKE '".$r1['country_code']."';";
	print_r($sqlN);
	echo PHP_EOL;
	$couters++;
	//mysql_query($sql) or die( mysql_error());
    }
 }
echo "Fin: ".$couters++;