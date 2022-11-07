<?php
$conn = mysqli_connect("localhost","root","tt","tt");
if (!mysqli_set_charset($conn , "utf8")) {
    printf("Error loading character set utf8: %s\n", mysqli_error($conn));
} else {
    printf("Current character set: %s\n", mysqli_character_set_name($conn));
}  

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

?>