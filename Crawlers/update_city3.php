<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

 <body onload="hide()" style="background-color: #333; color: #ccc;">
<?php
 
$st_code=$_GET['st_code'];
$state= $_GET['state'];
error_reporting(e_all);
$conn = mysqli_connect("localhost","root","tt","tt");
if (!mysqli_set_charset($conn , "utf8")) {
    printf("Error loading character set utf8: %s\n", mysqli_error($conn));
} else {
    printf("Current character set: %s\n", mysqli_character_set_name($conn));
}  

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

 

 $Query="update tt.cms_zipcodes set city_id=$nw_id,status=22 where id=$nz_id";
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
$sql="SELECT w.id AS w_id, z.id AS z_id, w.name, z.city, z.n_city_local,z.zip_code,z.n_zip,w.latitude, z.longitude1 AS z_long, w.longitude, z.latitude1 AS z_lat, z.state, s.state_name FROM tt.webgeocities AS w JOIN tt.cms_zipcodes AS z JOIN tt.states AS s ON z.country_code = w.country_code AND w.name like trim(substring_index( z.n_city_local, 'A', -1 )) AND z.state = 'Galicia' AND s.state_code = '58' AND s.state_code = w.state_code AND z.country_code = s.country_code AND z.country_code = 'ES' AND z.status =14 AND z.zip_code=z.n_zip and z.state=z.n_state ORDER BY z.id ASC limit 500"; 
 
$i=0;
$result1 = mysqli_query($conn,$sql) or die( mysqli_error());
 
print "<table border=1>
<tr><td>w_id</td><td>z_id</td><td>w_name</td><td>z_name</td><td>w_state</td><td>z_state</td><td>w_latitude</td><td>z_latitude</td><td>w_longitude</td><td>z_longitude</td><td>check</td></tr>";
while($row = mysqli_fetch_array($result1))
{
$n_state= $row['n_state'];
$n_zip= $row['n_zip'];
$zip_code=$row['zip_code'];

  $w_id = 	$row['w_id'];
  $z_id= 	$row['z_id'];
  $name=	$row['name'];
  $city=	$row['city'];
  $z_state=$row['state'];
  $w_state=$row['state_name'];
  $latitude=		$row['latitude'];
  $latitude1=		$row['z_lat'];
  $longitude=		$row['longitude'];
  $longitude1=		$row['z_long'];
  $n_city_local=	$row['n_city_local'];
  $check_value=	$w_id."_".$z_id;  
  
 print "<tr><td>$w_id</td><td>$z_id</td><td>$name =>($n_state)($n_zip)($zip_code)</td><td>$city($n_city_local)</td><td>$w_state</td><td>$z_state</td><td>$latitude</td><td>$latitude1</td><td>$longitude</td><td>$longitude1</td></td><td><input type= checkbox name=checked[] value=$check_value>$r_id</td></tr>";
 
$i++;
}
print "</table>";
?>
<input type="submit" name="submit" value="Submit"/>
<input type="text" name="check_val" value="<?php echo $i;?>"/>
</form>
</body>