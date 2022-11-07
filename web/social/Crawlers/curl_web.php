<?php
error_reporting(e_all);

/*make connection for database*/
$conn = mysqli_connect("localhost","root","tt","tt"); 
/*********************************************end*************************************************/

/*set_charset utf8 for DB*/ 
if (!mysqli_set_charset($conn , "utf8")) 
	{
		printf("Error loading character set utf8: %s\n", mysqli_error($conn));
	} 
else
	{
		printf("Current character set: %s\n", mysqli_character_set_name($conn));
	}  
/*********************************************end*************************************************/
/*Query for fetching Records*/
$sql	=	"SELECT w.id as w_id,replace(concat(w.name,',',s.state_name),' ','+')as w_name FROM `webgeocities` as w join states as s on w.country_code=s.country_code and w.state_code=s.state_code and w.country_code='FR' AND w.`status` = 0 ORDER BY w.id DESC limit 100";   
$result1= 	mysqli_query($conn,$sql) or die( mysqli_error()); 
 
while($row = mysqli_fetch_array($result1))
	{
	  $d_id		= 	$row['w_id']; 
	  $d_poi_city 	= 	$row['w_name'];
	 
	  /*$d_lat	=	$row['latitude']; 
	  $d_long	= 	$row['longitude'];*/
	  //echo $url="https://maps.googleapis.com/maps/api/geocode/json?address=$d_poi_city,italy&key=AIzaSyAmiruPgOma0q3A5grcnwqfEuXyM0z_h4Q";
	  echo $url="https://maps.googleapis.com/maps/api/geocode/json?address=$d_poi_city,italy&key=AIzaSyARydAdxfHXz7e9c0A04X9F_r6MZoDBdqM";
	  echo"</br>";
	  $timeout = 3;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
				$data = curl_exec($ch); 
				curl_close($ch);		
		$data1 = json_decode($data, TRUE);
if ($data1['status'] != 'OK') 
	{
			  echo 'An error has occured: ' . print_r($data1);
			  $Query="update tt.webgeocities set  status=9 where id=$d_id";
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
			 $city				=	'';
			 $found_city		=	false;
			 $found_locality	=	false;
			 $locality			=	'';
foreach($data1['results'] as $key=>$locality_type):
	foreach($locality_type as $types=>$typ):
		if($found_city)		
			continue;
				if($typ[0]=='locality')
					{
				foreach($locality_type['address_components'] as $city_en=>$tyyp):
						if($tyyp['types'][0]=='locality')
							{
							$city=$tyyp['long_name'];
							echo $city=str_replace("'","''",trim($city));
							$found_city=true;
							echo "<br>";
							}
				endforeach;
				
					}
	endforeach;
			
	foreach($locality_type['address_components'] as $city_ln=>$t_type):
			if($found_locality)		
				continue;
					if($t_type['types'][0]=='locality')
						{
						$locality=$t_type['long_name'];
						echo $locality=str_replace("'","''",trim($locality));
						$found_locality=true;
						
						}
	endforeach;
		
endforeach;

/*Query for update table*/		
$Query="update webgeocities set locality='$locality',en_locality='$city', status= 3 where id=$d_id";
	if (mysqli_query($conn, $Query ))
		    {
			echo " $d_id updated successfully <br>";
		    } 
		    else
		    {
			echo "Error: $d_id " . $Query  . "<br>" . mysqli_error($conn);
			}

	}
		usleep(1000000);
		$data1='';
}
/*********************************************end*************************************************/

?>