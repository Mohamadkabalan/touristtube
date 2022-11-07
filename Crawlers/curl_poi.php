<?php
error_reporting(e_all);

$conn = mysqli_connect("localhost","root","tt","tt");
if (!mysqli_set_charset($conn , "utf8")) {
    printf("Error loading character set utf8: %s\n", mysqli_error($conn));
} else {
    printf("Current character set: %s\n", mysqli_character_set_name($conn));
}  
//die();
$sql="SELECT id, name, latitude, longitude, city
FROM tt.prague_poi
WHERE country = 'FI' 
AND status=14 and state='Null'
ORDER BY name limit 500  ";   
$result1 = mysqli_query($conn,$sql) or die( mysqli_error()); 
 
//print "<table border=1 id='rowCtr' ><tr><td>id</td><td>poi_name</td><td>d_lat</td><td>d_long</td><td>address</td></tr>";
while($row = mysqli_fetch_array($result1))
{
  $d_id		= 	$row['id']; 
  $d_poi 	= 	$row['name'];
  $d_lat	=	$row['latitude']; 
  $d_long	= 	$row['longitude'];
  
//for($i=0;$i<10;$i++): 
	$url="https://maps.googleapis.com/maps/api/geocode/json?latlng=$d_lat,$d_long&key=AIzaSyBgg7PNGgpsWLLgdPpYqou8TIWtkjgK1u4";
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
  $Query="update tt.prague_poi set  status=13 where id=$d_id";
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
	 $state='Null';
	 $city='Null';
	 $found	=	false;
	 $found_city	=	false;
		
		foreach($data1['results'] as $key=>$address_componenets):
		if($found)
			continue;
			foreach($address_componenets['address_components'] as $key=>$types):
				
				if($types['types'][0]=='administrative_area_level_1'){
					$state1 = $types["long_name"];
					echo "state:";
					echo $state = str_replace("'","''",trim($state1));
					$found=true; 
				}
			endforeach;
			//for cities
			/*foreach($address_componenets['address_components'] as $key=>$types):
				//print_r($types);
				/*if($types['types'][0]=='locality'){
					$city1 = $types["long_name"];  
					echo "city:";
					echo $city = str_replace("'","''",trim($city1));
					$found_city=true;
				}  
			endforeach;*/

				
		endforeach;
		

$Query="update tt.prague_poi set state='$state', status=15 where id=$d_id";
 if (mysqli_query($conn, $Query ))
		    {
			echo " $d_id updated successfully <br>";
		    } 
		    else
		    {
			echo "Error: $d_id " . $Query  . "<br>" . mysqli_error($conn);
			}

	 
	
	 }
	
	usleep(2);
}

?>