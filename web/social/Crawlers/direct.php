<?php
error_reporting(E_ALL);
$conn = mysqli_connect("localhost","root","tt","tt");
/*$st_code=$_GET['st_code'];
$state= $_GET['state'];
$status=$_GET['status'];
*/

/*print "<form action='#' method='post'>";
$sql="SELECT w.id AS w_id, z.id AS z_id, w.name,trim(SUBSTRING_INDEX( z.city,  '(', 1 )) as z_city, w.latitude, z.longitude1, w.longitude, z.latitude1, z.state, w.state_code as w_statecode,s.state_name,w.country_code as w_cc_code
FROM tt.webgeocities_OLD AS w
JOIN tt.cms_zipcodes AS z
JOIN tt.states AS s ON z.country_code = w.country_code
AND w.name =  z.city  AND z.country_code='TR' AND z.state= '$state'
AND w.state_code='$st_code'    
AND s.state_code = w.state_code 
AND z.country_code = s.country_code 
AND z.city_id =0 
GROUP BY z.id  
HAVING count( z.id ) =1   
ORDER BY z.city, z.state" ; */  

$sql=" SELECT w.id AS w_id, z.id AS z_id, w.name, z.name, w.latitude, z.latitude, z.longitude, w.longitude, z.state,w.state_code,s.state_code FROM tt.webgeocities AS w JOIN tt.zipcodes_new AS z join states as s on s.country_code=w.country_code and s.country_code=z.country_code and s.state_code=w.state_code and z.country_code = w.country_code AND w.name=left(z.name,LOCATE(' ',z.name) - 1) AND z.state = s.state_name and z.city_id=0 group by z.id having count(z.id)=1";
 
$result1 = mysqli_query($conn,$sql) or die( mysqli_error());
 
while($row = mysqli_fetch_array($result1))
{
  $w_id = 	$row['w_id'];
  $z_id= 	$row['z_id'];
  /* $name=	$row['name'];  
  $city=	$row['city'];
  $latitude=		$row['latitude'];
  $latitude1=		$row['latitude1']; 
  $longitude=		$row['longitude'];
  $longitude1=		$row['longitude1']; 
  $check_value=	$w_id."_".$z_id;*/

  
 $Query="update tt.zipcodes_new set city_id=$w_id,status=2 where id=$z_id ";
  
 if (mysqli_query($conn, $Query ))
		    {
				//echo " $z_id New record updated successfully-$w_id<br>";
				echo " $z_id-$w_id<br>";
		    } 
		    else
		    {
			echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
			}
 

}
?>

<!--
<input type="submit" name="submit" value="Submit"/>
</form>-->