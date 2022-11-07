<?php
error_reporting(e_all);
$conn = mysqli_connect("localhost","root","tt","tt");

print "<form action='#' method='post'>";
$sql="SELECT w.id AS w_id, z.id AS z_id, w.name, z.city, w.latitude, z.longitude1, w.longitude, z.latitude1, z.state, z.zip_code
FROM tt.webgeocities AS w
JOIN tt.cms_zipcodes AS z
JOIN tt.states AS s ON z.country_code = w.country_code
AND w.name = SUBSTRING_INDEX( z.city, '(', 1 )

AND z.city_id =0
AND z.state = s.state_name
AND z.country_code = 'ES'
AND s.state_code = w.state_code
AND z.country_code = s.country_code
GROUP BY z.id
HAVING count( z.id ) =1
ORDER BY z.city ";
$result1 = mysqli_query($conn,$sql) or die( mysqli_error());
 
while($row = mysqli_fetch_array($result1))
{
  $w_id = 	$row['w_id'];
  $z_id= 	$row['z_id'];
  $name=	$row['name'];
  $city=	$row['city'];
  $latitude=		$row['latitude'];
  $latitude1=		$row['latitude1'];
  $longitude=		$row['longitude'];
  $longitude1=		$row['longitude1']; 
  $check_value=	$w_id."_".$z_id;

  
  $Query="update tt.cms_zipcodes set city_id=$w_id,status=10 where id=$z_id";
  
 if (mysqli_query($conn, $Query ))
		    {
				echo " $z_id New record updated successfully-$w_id<br>";
		    } 
		    else
		    {
			echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
			}
 

}
?>
<input type="submit" name="submit" value="Submit"/>
</form>