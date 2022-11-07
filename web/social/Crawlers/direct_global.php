<meta http-equiv="Content-Type" content="text/html; 
charset=UTF-8" />
<body onload="hide()" style="background-color: #333; color: #ccc;">
<?php
error_reporting(e_all);
$conn = mysqli_connect("localhost","root","tt","tt");
if (!mysqli_set_charset($conn , "utf8")) {
    printf("Error loading character set utf8: %s\n", mysqli_error($conn));
} else {
    printf("Current character set: %s\n", mysqli_character_set_name($conn));
}  
//*********************Get country_code,webgeocities state_name,restaurants state***************************

$cc_code=$_GET['cc_code'];
$w_state=$_GET['w_state']; 
$g_state=$_GET['g_state'];
$limit	=$_GET['limit'];


print "<form action='#' method='post'>";
//*********************select webgeocities_id as city_id***************************
$sql="SELECT g.id as g_id,w.id as w_id,g.locality,w.name, g.latitude,g.longitude,w.latitude,w.longitude FROM global_restaurants as g join `webgeocities` as w on g.country=w.country_code and w.name= trim(substring_index(g.locality,' ', 1)) and g.locality<>'' and g.city_id=0 and g.region=w.state_code and g.country='us' group by g.id having count(g.id)=1"; 
$result1 = mysqli_query($conn,$sql) or die( mysqli_error());
 $i=0;
while($row = mysqli_fetch_array($result1))
{
	  $w_id 		= 	$row['w_id'];
	  $g_id			= 	$row['g_id']; 
	  
//*********************update city_id***************************	  
	  $Query="update tt.global_restaurants set city_id=$w_id,status=4 where id=$g_id";
 if (mysqli_query($conn, $Query ))
		    {
				echo " $g_id restaurants id updated successfully webgeocities_id-$w_id<br>";
		    } 
		    else
		    {
			echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
			}
$i++;
}


?>
<input type="text" name="check_val" value="<?php echo $i;?>"/>
</form>
</body>