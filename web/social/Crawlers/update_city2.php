<?php
error_reporting(e_all);
$conn = mysqli_connect("localhost","root","tt","tt");
$cc_code=$_GET['cc_code'];
$state= $_GET['state'];
$w_state_name=$_GET['w_state'];
if(isset($_POST['insert']))
{if(!empty($_POST['checked']))
{

foreach($_POST['checked'] as $ad)
{
 $ad;
 $selected=$_POST["check_value$ad"];

$new_sel1= explode('_',$selected);
//print_r($new_sel1);

 $nw_id=$new_sel1[0];
 $nz_id=$new_sel1[1];
 $w_name=$new_sel1[2];
 $w_longitude1=$new_sel1[3];
 $w_latitude1=$new_sel1[4];
 $w_country=$new_sel1[5];
 $z_statename=$new_sel1[6];
 $status=56;
 /*if($z_statename=="ANDAMAN & NICOBAR ISLANDS")
 {
 $z_statename="Andaman and Nicobar Islands";
 }
  if($z_statename=="CHATTISGARH")
 {
 $z_statename="Chhattisgarh";
 }
   if($z_statename=="DELHI")
 {
 $z_statename="New Delhi (NCR/NCT)";
 }
  if($z_statename=="JAMMU & KASHMIR")
 {
 $z_statename="Kashmir";
 }
 if($z_statename=="LAKSHADWEEP")
 {
 $z_statename="Laccadives";
 }
  if($z_statename=="ODISHA")
 {
 $z_statename="Orissa";
 }
 if($z_statename=="PONDICHERRY")
 {
 $z_statename="Union Territory of Pondicherry";
 }
  if($z_statename=="WEST BENGAL")
 {
 $z_statename="Bengal";
 }*/

/* $query1="select state_code from tt.states where country_code='$w_country' and state_name='$z_statename'";
 $result2 = mysqli_query($conn,$query1) or die( mysqli_error());
 while($row2 = mysqli_fetch_array($result2))
{
 $w_statecode=$row2['state_code'];
 $status=1;
 
 if ($w_statecode=="") 
 {
 $w_statecode=$z_statename;
 
 $status=2;}
}*/
 
 $timezoneid="Asia/Kolkata";
 
print $Query= "INSERT INTO tt.webgeocities_OLD(`country_code`, `state_code`, `name`, `latitude`, `longitude`,timezoneid, `status`) VALUES ('$w_country','$w_statecode','$w_name','$w_latitude1','$w_longitude1',$timezoneid,$status)";

if (mysqli_query($conn, $Query ))
		    {
				echo "New record created successfully";
		    } 
		    else
		    {
			echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
			}

}
}
}

if(isset($_POST['submit']))
{

if(!empty($_POST['checked']))
{
foreach($_POST['checked'] as $ad)
{
 echo $ad;
 $selected=$_POST["check_value$ad"];
 
$new_sel= explode('_',$selected);
 $nw_id=$new_sel[0];
 $nz_id=$new_sel[1];

 

 $Query="update tt.cms_zipcodes set city_id=$nw_id,status=14 where id=$nz_id";
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
$sql="SELECT w.id AS w_id, z.id AS z_id, w.name, z.city as z_city, w.latitude, z.longitude1, w.longitude, z.latitude1, z.state, w.state_code AS w_statecode, s.state_name, w.country_code AS w_cc_code
FROM tt.webgeocities AS w
JOIN tt.cms_zipcodes AS z
JOIN tt.states AS s ON z.country_code = w.country_code
AND z.country_code = 'PT' 
AND w.name like '%SUBSTRING_INDEX( z.city, ' ', -1 ) as z_ss%' AND w.state_code=s.state_code
AND z.state = '$state' AND w.state_code='$w_state_name'
AND z.country_code = s.country_code
AND z.city_id =0 group by z.city
ORDER BY z.city, z.state limit 500"; 
$result1 = mysqli_query($conn,$sql) or die( mysqli_error());
 $i=0;
print "<table border=1>
<tr><td>w_id</td><td>z_id</td><td>w_name</td><td>z_name</td><td>w_state</td><td>z_state</td><td>w_latitude</td><td>z_latitude</td><td>w_longitude</td><td>z_longitude</td><td>check</td></tr>";
while($row = mysqli_fetch_array($result1))
{
  $w_id = 	$row['w_id'];
  $z_id= 	$row['z_id'];
  $name=	$row['name'];
  $city=	trim($row['z_city']);
  $z_state= $row['state']; 
  $w_state_code= $row['w_statecode'];
  //$z_zipcode=$row['zip_code'];
  $w_state=$row['state_name'];
  $latitude=		$row['latitude'];
  $latitude1=		$row['latitude1'];
  $longitude=		$row['longitude'];
  $longitude1=		$row['longitude1'];
  $country=		$row['w_cc_code'];
  $check_value=	$w_id."_".$z_id."_".$city."_".$longitude1."_".$latitude1."_".$country."_".$w_state_code ; 
  
 print "<tr><td>$w_id</td><td>$z_id</td><td>$name</td><td>$city</td><td>$w_state</td><td>$z_state</td><td>$latitude</td><td>$latitude1</td><td>$longitude</td><td>$longitude1</td></td><td><input type= checkbox name=checked[$i] value=$i><textarea name=check_value$i type=hidden style='display:none;'>$check_value</textarea>
</td></tr>";
 $i++;
}
print "</table>";

?>

<input type="submit" name="submit" value="Submit"/>
<input type="submit" name="insert" value="insert"/>
<input type="text" name="check_val" value="<?php echo $i;?>"/>
</form>