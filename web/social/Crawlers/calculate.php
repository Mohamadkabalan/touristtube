<?php
error_reporting(E_ALL);
$conn = mysqli_connect("localhost","root","tt","tt");

$sql="select char_length(concat(name,address,locality,region,admin_region)) as count_char from global_restaurants where country in ('cn','jp','hk','tw','kr','il','eg','th','ru') ";
 
$result1 = mysqli_query($conn,$sql) or die( mysqli_error());
 $final_count=0;
while($row = mysqli_fetch_array($result1))
{
  $count = 	$row['count_char'];
  $fin_cn=$final_count+$count;
  $final_count=$fin_cn;
 }
 
 echo $final_count;
?>

