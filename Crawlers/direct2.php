<?php
error_reporting(e_all);
$conn = mysqli_connect("localhost","root","tt","tt");

print "<form action='#' method='post'>";
$sql="SELECT city, latitude1, longitude1
FROM `cms_zipcodes`
WHERE city_id =0
AND state = 'Asturias'
AND country_code = 'ES'
GROUP BY city
HAVING count( city ) >=1 "; 
$result1 = mysqli_query($conn,$sql) or die( mysqli_error());
 
while($row = mysqli_fetch_array($result1))
{
  //$w_id = 	$row['w_id'];
  //$z_id= 	$row['z_id'];
  //$name=	$row['name'];
  $name=	$row['city'];
  $w_name = str_replace("'","''",trim($name));
  //$latitude=		$row['latitude'];
  $w_latitude1=		$row['latitude1'];
  //$longitude=		$row['longitude'];
  $w_longitude1=		$row['longitude1']; 
  //$check_value=	$w_id."_".$z_id;
  
$w_statecode="34";
$w_country="ES";
$time_zone="Europe/Madrid";

print $Query= "INSERT INTO tt.webgeocities(`country_code`, `state_code`, `name`, `latitude`, `longitude`, `status`,timezoneid) VALUES ('$w_country','$w_statecode','$w_name','$w_latitude1','$w_longitude1',3,'$time_zone')";

if (mysqli_query($conn, $Query ))
		    {
				echo "New record created successfully</br>";
		    } 
		    else
		    {
			echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
			}



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