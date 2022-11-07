<pre>
<?php
ini_set("error_reporting", E_ALL);
ini_set("display_errors", 1);
mysql_connect('localhost', 'root', 'mysql_root') or die("Database error");
//mysql_connect('localhost', 'tourist', 'touristMysqlP@ssw0rd') or die("Database error");
//mysql_select_db('touristtube');
mysql_select_db('touristtube_POI');

/*$query = "select * from POI_fr limit 60000, 30000"; 
$result = mysql_query($query);
$ids ='';
while ($row=  mysql_fetch_array($result)){

	$long=$row['longitude'];
    $lat=$row['latitude'];
    $name=$row['name'];

   echo  $sql="SELECT count(*) FROM discover_poi WHERE longitude = '$long' AND latitude = '$lat' "; //AND name ='".addslashes($name)."'
   echo "\n";
    $res = mysql_query($sql);
    $r=  mysql_fetch_array($res);
    if($r[0] > 0 ) {
    	$ids .= $row['Id'].", ";
    }
}
echo '||'.$ids.'||';
*/

$query = "select * from POI_uk limit 30000, 30000";
$result = mysql_query($query);
$ids ='';
while ($row=  mysql_fetch_array($result)){

	$long=$row['longitude'];
	$lat=$row['latitude'];
	$name=$row['name'];

	echo  $sql="SELECT count(*) FROM discover_poi WHERE longitude = '$long' AND latitude = '$lat' "; //AND name ='".addslashes($name)."'
	echo "\n";
	$res = mysql_query($sql);
	$r=  mysql_fetch_array($res);
	if($r[0] > 0 ) {
		$ids .= $row['Id'].", ";
	}
}
echo '||'.$ids.'||';