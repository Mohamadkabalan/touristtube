<meta http-equiv="Content-Type" content="text/html; 
charset=UTF-8" />
<?php
error_reporting(e_all);

$conn = mysqli_connect("localhost","root","tt","tt");
if (!mysqli_set_charset($conn , "utf8")) {
    printf("Error loading character set utf8: %s\n", mysqli_error($link));
} else {
    printf("Current character set: %s\n", mysqli_character_set_name($link));
}

print "<form action='#' method='post'>";

/*query for city update*/

$sql="SELECT d.id AS d_id, p.id, d.name, p.poi_name, p.city as city
FROM tt.discover_poi_new AS d
JOIN tt.poi_gogobot_new AS p ON p.poi_name = d.name
AND p.country_code = d.country
AND p.country_code = 'AI'
AND d.from_source = 'gogobot' AND d.city_id=0
GROUP BY p.poi_name
HAVING count( p.poi_name ) >=1
ORDER BY p.poi_name ASC "; 
/*query for city_id update*/
/*
$sql= "SELECT w.id as w_id, d.id as d_id, w.name, d.city
FROM `discover_poi_new` AS d
JOIN webgeocities AS w ON w.name = d.city
AND w.country_code = d.country
AND d.country = 'NZ'
AND d.from_source = 'gogobot' AND city_id=0 
GROUP BY d.id
HAVING count( d.id ) >=1";
*/
$result1 = mysqli_query($conn,$sql) or die( mysqli_error());
 
while($row = mysqli_fetch_array($result1))
{
  	
		$d_id= 	$row['d_id'];
		$w_id=	$row['w_id'];
		$city= 	str_replace("'","''",trim($row['city']));
    

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
 

 /*update city-----*/
  echo $Query="update tt.discover_poi_new set city='$city', status=40 where id=$d_id";
  
  /*update city_id*/
 
 //$Query="update tt.discover_poi_new set city_id=$w_id, status=41 where id= $d_id";
  
 if (mysqli_query($conn, $Query ))
		    {
				echo " $d_id New record updated successfully $w_id<br>";
		    } 
		    else
		    {
			echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
			}
 

}  
?>
<input type="submit" name="submit" value="Submit"/>
</form>