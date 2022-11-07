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
$cc_code=$_GET['cc_code'];
if(isset($_POST['submit']))
{
if(isset($_POST['submit']))
{
//*********************update city_id***************************
if(!empty($_POST['checked']))
{
foreach($_POST['checked'] as $ad)
{
 echo $ad;
 $selected=$_POST["check_value$ad"];
 
$new_sel= explode('_',$selected);
 $nw_id=$new_sel[0];
 $ng_id=$new_sel[1];

 

 $Query="update tt.global_restaurants set city_id=$nw_id,status=3 where id=$ng_id";
 if (mysqli_query($conn, $Query ))
		    {
				echo " $ng_id New record updated successfully-$nw_id<br>";
		    } 
		    else
		    {
			echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
			}


}
}
}
}

if(isset($_POST['notmatch']))
{

if(!empty($_POST['checked']))
{
foreach($_POST['checked'] as $ad)
{
 echo $ad;
 $selected=$_POST["check_value$ad"];
 
$new_sel= explode('_',$selected);
 $nw_id=$new_sel[0];
 $ng_id=$new_sel[1];

 

 $Query="update tt.global_restaurants set status=9 where id=$ng_id";
 if (mysqli_query($conn, $Query ))
      {
    echo " $ng_id Not matched record updated successfully <br>";
      } 
      else
      {
   echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
   }

}
}
}



print "<form action='#' method='post'>";
$sql=" SELECT g.id as g_id, w.id as w_id,g.locality as city,w.name as w_city,g.region as g_state,g.admin_region AS admin,w.state_code as w_state,s.state_name as s_state,g.latitude as g_lat, w.latitude as w_lat, g.longitude as g_long, w.longitude as w_long  
FROM `global_restaurants` as g
 join webgeocities as w
 join states as s
 on g.country=w.country_code
 and g.country=s.country_code and
 s.country_code=w.country_code and
 s.state_code=w.state_code 
 where g.locality=w.name and 
 g.country='$cc_code' and 
 g.city_id=0 and g.status=0
 group by g.id having count(g.id)=1 limit 100  "; 
$result1 = mysqli_query($conn,$sql) or die( mysqli_error());
 $i=0;
print	"<table border=1>
		<tr><td>g_id</td>
		<td>w_id</td>
		<td>g_city</td>
		<td>w_city</td>
		<td>g_state</td>
		<td>w_state</td>
		<td>g_lat</td>
		<td>w_lat</td>
		<td>g_long</td>
		<td>w_long</td>
		<td>check</td></tr>";
while($row = mysqli_fetch_array($result1))
{
	  $w_id 		= 	$row['w_id'];
	  $g_id			= 	$row['g_id'];
	  $w_city		=	$row['w_city'];
	  $city			=	$row['city'];
	  $g_state		=	$row['g_state'];
	  $w_state		=	$row['w_state'];
	  $g_lat		=	$row['g_lat'];
	  $g_long		=	$row['g_long'];
	  $w_lat		=	$row['w_lat'];
	  $w_long		=	$row['w_long'];
	  $s_state		=	$row['s_state'];
	  $g_admin		=	$row['admin'];
	  $check_value	=	$w_id."_".$g_id;
	  
 print "<tr><td>$g_id</td>
		<td>$w_id</td>
		<td>$city</td>
		<td>$w_city</td>
		<td>$g_state($g_admin)</td>
		<td>$s_state($w_state)</td>
		<td>$g_lat</td>
		<td>$w_lat</td>
		<td>$g_long</td>
		<td>$w_long</td>
		<td><input type= checkbox name=checked[$i] value=$i><textarea name=check_value$i id='check' type=hidden style='display:none;'>$check_value</textarea>
</td></tr>";
 $i++;

}
print "</table>";
?>
<input type="text" name="check_val" value="<?php echo $i;?>"/>
<input type="submit" name="submit" value="Submit"/>
<input type="submit" name="notmatch" value="notmatch"/>
</form>
</body>