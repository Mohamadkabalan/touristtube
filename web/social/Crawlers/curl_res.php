<?php
error_reporting(e_all);

$conn = mysqli_connect("localhost","root","tt","tt");
if (!mysqli_set_charset($conn , "utf8")) {
    printf("Error loading character set utf8: %s\n", mysqli_error($conn));
} else {
    printf("Current character set: %s\n", mysqli_character_set_name($conn));
}  

$sql="SELECT id, locality FROM tt.global_restaurants_city where country='kr' and en_city='not found'";   
$result1 = mysqli_query($conn,$sql) or die( mysqli_error()); 
 

while($row = mysqli_fetch_array($result1))
{
  $d_id		= 	$row['id'];  
  $d_locality 	= 	$row['locality'];


	$url="https://maps.googleapis.com/maps/api/geocode/json?address=$d_locality&language=en&country=kr&key=AIzaSyARydAdxfHXz7e9c0A04X9F_r6MZoDBdqM";
	 $timeout = 3;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
    $data = curl_exec($ch);
    curl_close($ch);
	
	echo date('h:i:s');
	 $data1 = json_decode($data, TRUE);
//print_r($data1);
if ($data1['status'] != 'OK') {
  echo 'An error has occured: ' . print_r($data1);
  $Query="update tt.global_restaurants_city set  en_city='not found' where id=$d_id";
 if (mysqli_query($conn, $Query ))
		    {
			echo " $d_id not updated <br>";
		    } 
		    else
		    {
			echo "Error: $d_id " . $Query  . "<br>" . mysqli_error($conn);
			}
}

else 
	{
	 
	 $city='Null';
	 
	 $found	=	false;
	 
		
		foreach($data1['results'] as $key=>$address_componenets):
		if($found)
			continue;
		//['address_components']
			foreach($address_componenets['address_components'] as $key=>$types):
				//print_r($types);
				if($types['types'][0]=='locality'){
					$city = $types["long_name"];
					echo "city:";
					echo $city = str_replace("'","''",trim($city));
					$found=true; 
				}
			endforeach;
			
			
		
				
		endforeach;
		//die();

$Query="update tt.global_restaurants_city set en_city='$city',status=2 where id=$d_id";
 if (mysqli_query($conn, $Query ))
		    {
			echo " $d_id updated successfully <br>";
		    } 
		    else
		    {
			echo "Error: $d_id " . $Query  . "<br>" . mysqli_error($conn);
			}

	 
	
	 // print "<tr><td>$d_id</td><td>$d_poi </td><td>$d_lat</td><td>$d_long</td><td>$address</td></tr>";
	 //endfor;
	}
	
	usleep(2);
}
//print "</table>";

?>