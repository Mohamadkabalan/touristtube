<?php
error_reporting(e_all);
$conn = mysqli_connect("localhost","root","tt","tt");

print "<form action='#' method='post'>";
$sql="SELECT w1.id AS old_web_id, w2.id AS new_web_id, w1.name AS old_city, w2.name AS new_city, w1.country_code, w2.country_code
FROM `webgeocities_OLD` AS w1
JOIN webgeocities AS w2 ON w1.status <>0
AND w2.name = w1.name
AND w1.state_code = w2.state_code
AND w1.country_code = 'GB'
AND w2.from_source = 'new'
ORDER BY `w2`.`name` ASC  "; 
$result1 = mysqli_query($conn,$sql) or die( mysqli_error());
 
while($row = mysqli_fetch_array($result1))
{
  $old_web_id= 	$row['old_web_id'];
  $new_web_id= 	$row['new_web_id'];
  /*$name=	$row['name'];
  $city=	$row['city'];
  $latitude=		$row['latitude'];
  $latitude1=		$row['latitude1'];
  $longitude=		$row['longitude'];
  $longitude1=		$row['longitude1']; 
  $check_value=	$w_id."_".$z_id;*/

  
  $Query="update tt.cms_zipcodes set city_id=$new_web_id,status=15 where city_id=$old_web_id";
  
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