<?php

include("connection.php");

$type= "hotel"; // type name to be used for elastic

//$query = "SELECT h.*, wc.name as city, states.state_code, cms_countries.name as country, concat(h.latitude, ', ', h.longitude) as geolocation, dht.title as propertyName FROM discover_hotels h LEFT JOIN discover_hotels_type as dht ON h.propertyType = dht.id, webgeocities wc, states, cms_countries  WHERE h.city_id =wc.id AND wc.country_code=cms_countries.code AND wc.state_code=states.state_code AND states.country_code=cms_countries.code AND h.published='1' ";
$query = "SELECT h.*,h.hotelName as hotelNamePh, wc.name as city, st.state_code, co.name as country, concat(h.latitude, ', ', h.longitude) as geolocation, dht.title as propertyName FROM discover_hotels as h LEFT JOIN discover_hotels_type as dht ON h.propertyType = dht.id INNER JOIN webgeocities as wc ON h.city_id =wc.id INNER JOIN cms_countries as co ON wc.country_code=co.code LEFT JOIN states as st ON wc.state_code=st.state_code AND st.country_code=co.code WHERE h.published=1 group by h.id";
//$query = "SELECT * FROM `discover_hotels` WHERE `id` = 195795 ";
if($rs = mysqli_query($conn,$query)) {
    $count = mysqli_num_rows($rs);
    
    if(!$count)
		die('No data to import');
	
	$query1 = "SELECT * from `discover_hotels_images` ";
	
	if($rs1 = mysqli_query($conn, $query1)) {
		while($row1 = mysqli_fetch_assoc($rs1)){
			$imagesArray[$row1['hotel_id']][]=$row1; 
		}
		
		mysqli_free_result($rs1);
	}
	
    $i = 1;
    while($row=mysqli_fetch_assoc($rs)) {
        $row['features'] = array();
        $query2 = "SELECT dhf.hotel_feature_id,df.title FROM discover_hotels_feature_to_hotel as dhf LEFT JOIN discover_hotels_feature as df on df.id= dhf.hotel_feature_id WHERE hotel_id=".$row['id'];
		if($rs2 = mysqli_query($conn, $query2)) {
			while($row2 = mysqli_fetch_assoc($rs2)) {
				$row['features'][] = $row2;
			}
			
			mysqli_free_result($rs2);
        }

        $row['images']=array();
        $imageExists = 0;
        if(isset($imagesArray[$row['id']])){
            $hotelid = $row['id'];
            $row['images']	=	$imagesArray[$hotelid];
            $imageExists	=	1;
        }
        $row['imageExists']	=	$imageExists;
        $row['titleLocation'] = $row['hotelName'].' '.$row['cityName'].' '.$row['stateName'].' '.$row['country'];
        $params['body'][] = array(
            'index' => array('_id' =>$row['id'],'_index'=>$index,'_type'=>$type )
        ); 
        $params['body'][] = json_encode($row);

        if ($i % 10000==0) { echo "importing every ".$i." records \n";
               $responses = $client->bulk($params);
               // erase the old bulk request
//               $params = array();
               unset($params);
               // unset the bulk response when you are done to save memory
               unset($responses);
           }
        if ($i==$count && !empty($params)) { echo "importing last left records";
               $responses = $client->bulk($params);
//               $params = array();
               unset($params);
               unset($responses);
        }
        $i++;
    }
	
	mysqli_free_result($rs);
}

?>
