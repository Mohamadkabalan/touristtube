<?php
error_reporting(e_all);
$conn = mysqli_connect("localhost","root","tt","tt");
$cc_code=$_GET['cc_code'];
if(isset($_POST['submit']))
{

if(!empty($_POST['checked']))
{
foreach($_POST['checked'] as $selected)
{

$new_sel= explode('_',$selected);
 $nw_id=$new_sel[0];
 $nz_id=$new_sel[1];

 

 $Query="update tt.cms_zipcodes set city_id=$nw_id,status=6 where id=$nz_id";
 if (mysqli_query($conn, $Query ))
		    {
				echo " $nz_id New record updated successfully-$nw_id<br>";
		    } 
		    else
		    {
			echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
			}


}
}
}



print "<form action='#' method='post'>";
$sql="SELECT w.id AS w_id, z.id AS z_id, w.name, z.city, w.latitude, z.longitude1, w.longitude, z.latitude1, z.state, s.state_name
FROM tt.webgeocities AS w
JOIN tt.cms_zipcodes AS z
JOIN tt.states AS s ON z.country_code = w.country_code
AND z.city = w.name 
AND s.state_code = w.state_code AND cast( z.longitude1 AS decimal( 5, 1 ) ) = cast( w.longitude AS decimal( 5, 1 ) )
AND z.country_code = s.country_code
AND z.city_id =0
ORDER BY z.city ASC "; 
$result1 = mysqli_query($conn,$sql) or die( mysqli_error());
 
print "<table border=1>
<tr><td>w_id</td><td>z_id</td><td>w_name</td><td>z_name</td><td>w_state</td><td>z_state</td><td>w_latitude</td><td>z_latitude</td><td>w_longitude</td><td>z_longitude</td><td>check</td></tr>";
while($row = mysqli_fetch_array($result1))
{
  $w_id = 	$row['w_id'];
  $z_id= 	$row['z_id'];
  $name=	$row['name'];
  $city=	$row['city'];
  $z_state=$row['state'];
  $w_state=$row['state_name'];
  $latitude=		$row['latitude'];
  $latitude1=		$row['latitude1'];
  $longitude=		$row['longitude'];
  $longitude1=		$row['longitude1'];
  $check_value=	$w_id."_".$z_id;
  
 print "<tr><td>$w_id</td><td>$z_id</td><td>$name</td><td>$city</td><td>$w_state</td><td>$z_state</td><td>$latitude</td><td>$latitude1</td><td>$longitude</td><td>$longitude1</td></td><td><input type= checkbox name=checked[] value=$check_value>$r_id</td></tr>";
 

}
print "</table>";
?>
<input type="submit" name="submit" value="Submit"/>
</form>
