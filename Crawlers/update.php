<?php
error_reporting(e_all);
$conn = mysqli_connect("localhost","root","tt","zomato");


if(isset($_POST['submit']))
{

if(!empty($_POST['checked']))
{
foreach($_POST['checked'] as $selected)
{

$new_sel= explode('_',$selected);
 $dis_id=$new_sel[0];
 $review_id=$new_sel[1]; 



 $Query="update zomato.global_restaurants set status=0 where id=$review_id";
 if (mysqli_query($conn, $Query ))
		    {
				echo " $review_id New record updated successfully<br>";
		    } 
		    else
		    {
			echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
			}


}
}
}



print "<form action='#' method='post'>";
$sql="SELECT r.id AS zomato, g.id AS
global , r.res_name, g.name, r.location, g.address, r.res_latitude, g.latitude, r.res_longitude, g.longitude
FROM zomato.zomato_in_restaurant AS r
JOIN zomato.global_restaurants AS g ON 
AND cast( g.latitude_dec AS decimal( 5, 1 ) ) = cast( r.res_latitude AS decimal( 5, 1 ) )
AND cast( g.longitude_dec AS decimal( 5, 1 ) ) = cast( r.res_longitude AS decimal( 5, 1 ) )

AND r.city = 'Delhi/NCR' AND g.status<>0
ORDER BY `r`.`res_name` ASC ";
$result1 = mysqli_query($conn,$sql) or die( mysqli_error());

print "<table border=1>
<tr><td>r_id</td><td>g_id</td><td>r_address</td><td>g_address</td><td>r_latitude</td><td>g_latitude</td><td>r_longitude</td><td>g_longitude</td><td>check</td></tr>";
while($row = mysqli_fetch_array($result1))
{
  $r_hotel= 	$row['res_name'];
  $d_hotel= 	$row['name'];
  $r_address=	$row['location'];
  $d_address=	$row['address'];
  $r_city=		$row['res_latitude'];
  $d_city=		$row['latitude'];
  $r_state=		$row['res_longitude'];
  $d_state=		$row['longitude'];
  $d_id= 		$row['zomato'];
  $r_id	=		$row['global'];
  $check_value=	$d_id."_".$r_id;
  
 print "<tr><td>$r_hotel</td><td>$d_hotel</td><td>$r_address</td><td>$d_address</td><td>$r_city,$r_id</td><td>$d_city,$d_id</td><td>$r_state</td><td>$d_state</td></td><td><input type= checkbox name=checked[] value=$check_value>$r_id</td></tr>";
 

}
print "</table>";
?>
<input type="submit" name="submit" value="Submit"/>
</form>