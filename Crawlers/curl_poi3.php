<meta http-equiv="Content-Type" content="text/html; 
charset=UTF-8" />

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
WHERE country = 'JP' 
AND STATUS=13 ORDER BY name LIMIT 500";   
$result1 = mysqli_query($conn,$sql) or die( mysqli_error());
 
//print "<table border=1 id='rowCtr' ><tr><td>id</td><td>poi_name</td><td>d_lat</td><td>d_long</td><td>address</td></tr>";
while($row = mysqli_fetch_array($result1))
{
  $d_id		= 	$row['id']; 
  $d_poi 	= 	$row['name'];
  $d_lat	=	$row['latitude'];  
  $d_long	= 	$row['longitude'];
  
//for($i=0;$i<10;$i++):  
	$url="https://maps.googleapis.com/maps/api/geocode/json?latlng=$d_lat,$d_long&key=AIzaSyARydAdxfHXz7e9c0A04X9F_r6MZoDBdqM";
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
    //print_r($data1); die;
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
	 $city_admin='Null';
	 $city_local='Null';
	 $country='Null';
		$address = $data1['results'][0]['formatted_address'];
                
		foreach($data1['results'][0]['address_components'] as $address_componenets) 
		{
		if($address_componenets["types"][0]=="country") 
			{
        $country = $address_componenets["long_name"];
			}
		if($address_componenets["types"][0]=="administrative_area_level_1") 
			{
         $state1 = $address_componenets["long_name"];
		 $state = str_replace("'","''",trim($state1));
		 $st_code="select state_code from tt.states where country_code='JP' and state_name='$state'";
		 $st_result = mysqli_query($conn,$st_code) or die( mysqli_error());
		 //echo $st_result;
		 $state_code='N';
		 while ($stat = mysqli_fetch_array($st_result))
		 {
		  echo $state_code=$stat['state_code'];
		 }
		 
		 if ($state_code=='N')
		 {
		 $state_code=$country;
		 }
		  //$state_code= $address_componenets["short_name"]; 
		 }   
		if($address_componenets["types"][0]=="administrative_area_level_2")
			{
        $city_admin1 = $address_componenets["long_name"];
		$city_admin = str_replace("'","''",trim($city_admin1));
			}   
		if($address_componenets["types"][0]=="locality") 
			{
        $city_local1 = $address_componenets["long_name"];
		$city_local = str_replace("'","''",trim($city_local1));
		
setlocale(LC_ALL, "en_US.utf8");
$city_local = iconv("utf-8", "ascii//TRANSLIT", $city_local);
			}   
		}

$Query="update tt.discover_poi_latest set state='$state',n_city_local='$city_local',state_code='$state_code', n_city_admin='$city_admin', status=14 where id=$d_id";
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