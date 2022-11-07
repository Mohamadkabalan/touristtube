<meta http-equiv="Content-Type" content="text/html; 
charset=UTF-8" />
<?php
error_reporting(e_all);
/*$var = "Ağzibüyük Köyü";

echo $abc= htmlentities($var)."</br>";

echo html_entity_decode($abc);




die();*/

$conn = mysqli_connect("localhost","root","tt","tt");
if (!mysqli_set_charset($conn , "utf8")) {
    printf("Error loading character set utf8: %s\n", mysqli_error($link));
} else {
    printf("Current character set: %s\n", mysqli_character_set_name($link));
}
//mysqli_query ("set character_set_results='utf8'"); 
$st_code=$_GET['st_code']; 
$state= $_GET['state']; 
$status=$_GET['status'];
print "<form action='#' method='post'>";
$sql="SELECT city AS z_city, longitude1, latitude1
FROM tt.cms_zipcodes
WHERE `country_code` LIKE 'TR'
AND `state` = '$state'
AND `city_id` =0
AND id NOT
IN (
SELECT id
FROM `cms_zipcodes`
WHERE country_code = 'TR'
AND city_id =0
AND city = ''
)
AND id NOT
IN ( SELECT id
FROM `cms_zipcodes`
WHERE country_code = 'TR'
AND city_id =0
AND city = '%(%')
GROUP BY z_city
HAVING COUNT( z_city ) >=1"; 
$result1 = mysqli_query($conn,$sql) or die( mysqli_error());
 
while($row = mysqli_fetch_array($result1))
{
  //$w_id = 	$row['w_id'];
  //$z_id= 	$row['z_id'];
  //$name=	$row['name']; 
 echo $name=	$row['z_city']."</br>";
  $w_name = str_replace("'","''",trim($name));
  //$latitude=		$row['latitude'];
  $w_latitude1=		$row['longitude1'];
  //$longitude=		$row['longitude'];
  $w_longitude1=	$row['latitude1']; 
  //$check_value=	$w_id."_".$z_id;
  
$w_statecode=$st_code;
$w_country="TR";
$time_zone="Europe/Istanbul";   
/*
print $Query= "INSERT INTO tt.webgeocities_OLD(`country_code`, `state_code`, `name`, `latitude`, `longitude`, `status`,timezoneid) VALUES ('$w_country','$w_statecode','$w_name','$w_latitude1','$w_longitude1',$status,'$time_zone')";

if (mysqli_query($conn, $Query ))
		    {
				echo "New record created successfully</br>";
		    } 
		    else
		    {
			echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
			}
*/


 /*
  $Query="update tt.cms_zipcodes set city_id=$w_id,status=4 where id=$z_id";
  
 if (mysqli_query($conn, $Query ))
		    {
				echo " $z_id New record updated successfully-$w_id<br>";
		    } 
		    else
		    {
			echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
			}
 
*/
}
?>
<input type="submit" name="submit" value="Submit"/>
</form>