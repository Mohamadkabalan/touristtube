<meta http-equiv="Content-Type" content="text/html; 
charset=UTF-8" />
<?php
error_reporting(e_all);
require_once 'simple_html_dom.php';

$conn = mysqli_connect("localhost","root","tt","tt");
if (!mysqli_set_charset($conn , "utf8")) {
    printf("Error loading character set utf8: %s\n", mysqli_error($conn));
} else {
    printf("Current character set: %s\n", mysqli_character_set_name($conn));
}  

$sql="SELECT id,poi_link FROM tt.poi_gogobot_new WHERE `country_code`='AI' and status=1 order by poi_name ASC";

$result1 = mysqli_query($conn,$sql) or die( mysqli_error());
 
while($row = mysqli_fetch_array($result1))
{
 $id= $row['id'];
 $link=$row['poi_link'];  
//die();
 $detail = file_get_html($link); 
	echo $id;
	echo "City:".$city		= $detail->find(".regionName",0)->plaintext;
	
	echo "------------------------------------------</br>";
	//die();
	//$about=$detail->find(".categoriesList",0)->plaintext;
 //$about= str_replace("'","&apos;",trim($about));
 if($city)
 {

 
 echo $Query="update tt.poi_gogobot_new set city ='$city',status=5 where id=$id";
 //die();
 if (mysqli_query($conn, $Query ))
		    {
				echo "$id New record updated successfully<br>";
		    } 
		    else
		    {
			echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
			}

			
}
else
{
echo $Query="update tt.poi_gogobot_new set status=3 where id=$id";
 if (mysqli_query($conn, $Query ))
		    {
				echo "$id New record updated successfully<br>";
		    } 
		    else
		    {
			echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
			}
}

$detail='';
$city='';
}

?>