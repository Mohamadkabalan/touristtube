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
$sql="SELECT id, lattitude, longitude
FROM tt.cms_zipcodes
WHERE country_code = 'ES' and status=13 limit 250";   
$result1 = mysqli_query($conn,$sql) or die( mysqli_error());
 
//print "<table border=1 id='rowCtr' ><tr><td>id</td><td>poi_name</td><td>d_lat</td><td>d_long</td><td>address</td></tr>";
while($row = mysqli_fetch_array($result1))
{
  $d_id		= 	$row['id'];  
  $d_lat	=	$row['lattitude'];  
  $d_long	= 	$row['longitude'];
  
//for($i=0;$i<10;$i++): 
	$url="https://maps.googleapis.com/maps/api/geocode/json?latlng=$d_long,$d_lat&key=AIzaSyAmiruPgOma0q3A5grcnwqfEuXyM0z_h4Q";
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
  echo 'An error has occured: '. print_r($data1);
  $Query="update tt.cms_zipcodes set  status=13 where id=$d_id";
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
	 $zip='';
	 $found	=	false;
	 $found_city	=	false;
	 $found_zip	=	false;
	 
	foreach($data1['results'] as $key=>$address_componenets):
		if($found)
			continue;
			foreach($address_componenets['address_components'] as $key=>$types):
				
				if($types['types'][0]=='administrative_area_level_1')
				{
					$state = $types["long_name"];
					echo "state:";
					echo $state = str_replace("'","''",trim($state));
					$found=true; 
				}
			endforeach;
	endforeach;
	
	foreach($data1['results'] as $key=>$address_componenets):
		if($found_city)
			continue;
			foreach($address_componenets['address_components'] as $key=>$types):
				
				if($types['types'][0]=='locality')
				{
					$city_local = $types["long_name"];
					echo "city:";
					echo $city_local = str_replace("'","''",trim($city_local));
					$found_city=true; 
				}
			endforeach;
	endforeach;
	
	foreach($data1['results'] as $key=>$address_componenets):
		if($found_zip)
			continue;
			foreach($address_componenets['address_components'] as $key=>$types):
				
				if($types['types'][0]=='postal_code')
				{
					$zip = $types["long_name"];
					echo "zip:";
					echo $zip = str_replace("'","''",trim($zip));
					$found_zip=true; 
				}
			endforeach;
	endforeach;
        /*
		foreach($data1['results'][0]['address_components'] as $address_componenets) 
		{
		if($address_componenets["types"][0]=="country") 
			{
        $country = $address_componenets["long_name"];
			}
		if($address_componenets["types"][0]=="administrative_area_level_1") 
			{
         $state = $address_componenets["long_name"];
		 $state = str_replace("'","''",trim($state1));
			}   
		if($address_componenets["types"][0]=="administrative_area_level_2")
			{
        $city_admin = $address_componenets["long_name"];
		$city_admin = str_replace("'","''",trim($city_admin1));
			}   
		if($address_componenets["types"][0]=="postal_code")
			{
        $zip = $address_componenets["long_name"];
		$zip = str_replace("'","''",trim($zip));
			}   
		if($address_componenets["types"][0]=="locality") 
			{
        $city_local = $address_componenets["long_name"];
		$city_local = str_replace("'","''",trim($city_local1));
		

			}   
		}
	*/
$Query="update tt.cms_zipcodes set n_state='$state',n_city_local='$city_local', n_city_admin='$city_admin',n_zip='$zip', status=14 where id=$d_id";
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