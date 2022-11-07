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
FROM tt.discover_poi_latest
WHERE status=13 ORDER BY name limit 100";   
$result1 = mysqli_query($conn,$sql) or die( mysqli_error()); 
 
//print "<table border=1 id='rowCtr' ><tr><td>id</td><td>poi_name</td><td>d_lat</td><td>d_long</td><td>address</td></tr>";
while($row = mysqli_fetch_array($result1))
{
  $d_id		= 	$row['id']; 
  $d_poi 	= 	$row['name'];
  $d_lat	=	$row['latitude']; 
  $d_long	= 	$row['longitude'];
  
//for($i=0;$i<10;$i++): 
	$url="https://maps.googleapis.com/maps/api/geocode/json?latlng=$d_lat,$d_long&key=AIzaSyAmiruPgOma0q3A5grcnwqfEuXyM0z_h4Q";
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
  $Query="update tt.discover_poi_latest set  status=13 where id=$d_id";
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
	 $found_country=false;
	 $found_admin= false;
	$admin_city='';
	$country='';
	
		
		foreach($data1['results'] as $key=>$address_componenets):
		if($found)
			continue;
		//['address_components']
			foreach($address_componenets['address_components'] as $key=>$types):
				//print_r($types);
				if($types['types'][0]=='administrative_area_level_1'){
					$state = $types["long_name"];
					echo "state:";
					echo $state = str_replace("'","''",trim($state));
					$found=true; 
				}
			endforeach;
			if($found_city)
			continue;
			foreach($address_componenets['address_components'] as $key=>$types):
				//print_r($types);
				if($types['types'][0]=='locality'){
					$city = $types["long_name"];
					echo "city:";
					echo $city = str_replace("'","''",trim($city));
					$found_city=true;
				}
			endforeach;
			if($found_country)
			continue;
			foreach($address_componenets['address_components'] as $key=>$types):
				//print_r($types);
				if($types['types'][0]=='country'){
					$country = $types["short_name"];
					echo "country:";
					echo $country = str_replace("'","''",trim($country));
					$found_country=true;
				}
			endforeach;
			if($found_admin)
			continue;
			foreach($address_componenets['address_components'] as $key=>$types):
				//print_r($types);
				if($types['types'][0]=='administrative_area_level_2'){
					$admin_city = $types["long_name"];
					echo "admin_city:";
					echo $admin_city = str_replace("'","''",trim($admin_city));
					$found_admin=true;
				}
			endforeach;
			
				/*if($address_componenets["types"][0]=="administrative_area_level_1") 
				{
			 $state1 = $address_componenets["long_name"];
			 $state = str_replace("'","''",trim($state1));
				} */  		
				
				
		endforeach;
		//die();

$Query="update tt.discover_poi_latest set state='$state',n_city_local='$city', website= '$country',n_city_admin='$admin_city',status=10 where id=$d_id";
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