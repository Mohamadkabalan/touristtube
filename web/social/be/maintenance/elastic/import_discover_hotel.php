<?php
require_once('TTElasticSearchClient.php');

$elasticSearchClient = new elasticSearchClient();
$conn = $elasticSearchClient->elasticSearchClientConnection();

$index = "tt_dischotels";
$type= "dischotels";

$query = "SELECT h.id,h.hotelName as name, h.hotelName as name_key, h.stars, h.popularity, h.published, h.email, h.url, h.phone, h.rooms, h.fax, h.check_in, h.check_out, h.description, h.last_modified, h.address, h.address_short, h.location, h.longitude, h.latitude, h.city_id, h.cityName, h.countryCode, h.stateName, h.price, h.price_from, h.local_currency_code as currencyCode, h.rating, h.rating_overall_text, h.rating_cleanliness, h.rating_dining, h.rating_facilities, h.rating_location, h.rating_rooms, h.rating_service, h.rating_points, h.avg_rating, h.reviews_count, h.reviews_summary_positive, h.reviews_summary_negative, h.nb_votes, h.images_count, h.map_image,
                 dht.title as propertyName, dht.title as propertyName_key,
                 wc.id as city_id, wc.name as city_name, wc.popularity as cityPopularity, wc.latitude as cityLatitude, wc.longitude as cityLongitude,
                 co.code as country_code, co.name as country_name,
                 st.state_code as state_code, st.state_name as state_name
          FROM discover_hotels as h 
          LEFT JOIN discover_hotels_type as dht ON h.propertyType = dht.id 
          INNER JOIN webgeocities as wc ON h.city_id =wc.id 
          INNER JOIN cms_countries as co ON wc.country_code=co.code 
          LEFT JOIN states as st ON wc.state_code=st.state_code AND st.country_code=co.code 
          WHERE h.published=1 
          GROUP BY h.id";

if($rs = mysqli_query($conn,$query)) {
    $count = mysqli_num_rows($rs);
    
    if(!$count)
        die('No data to import');
	
	
    echo "\n\n$type count:: $count\n";

    $limit = 10000;
    $counter = 0;
    $i=1;
    $esRow = array();
    while($row=mysqli_fetch_assoc($rs)) {
        
        echo date('c')." id: ".$row['id']."\n";
        $esRow = $row;
        
        $esRow['vendor'] = array(
            'city' => array('id' => $esRow['city_id'] , 'name' => $esRow['cityName']),
            'country' => array('code' => $esRow['countryCode'] , 'name' => ''),
            'state' => array('code' => '', 'name' => $esRow['stateName']),
            'coordinates' => array("lon" => floatval($row['longitude']), "lat" =>floatval($row['latitude']))
        ) ;
        
        unset($esRow["location"]);
        $esRow['location'] = array(
            'address' => $esRow['address'],
            'address_short' => $esRow['address_short'],
            'location' => $row['location'],
            'coordinates' => array("lon" => $row['cityLongitude'], "lat" =>$row['cityLatitude']),
            'city' => array('id' => $esRow['city_id'] , 'name' => $esRow['city_name'] ,'cityPopularity' => $esRow['cityPopularity']),
            'country' => array('code' => $esRow['country_code'] , 'name' => $esRow['country_name']),
            'state' => array('code' => $esRow['state_code'] , 'name' => $esRow['state_name'])
        );
        
        $esRow['stats'] = array(
            'rating' => $esRow['rating'],
            'rating_cleanliness' => $esRow['rating_cleanliness'],
            'rating_dining' => $esRow['rating_dining'],
            'rating_location' => $esRow['rating_location'],
            'rating_rooms' => $esRow['rating_rooms'],
            'rating_service' => $esRow['rating_service'],
            'reviews_count' => $esRow['reviews_count'],
            'rating_facilities' => $esRow['rating_facilities'],
            'avg_rating' => $esRow['avg_rating'],
            'nb_votes' => $esRow['nb_votes'],
            'rating_points' => $esRow['rating_points'],
            'rating_overall_text' => $esRow['rating_overall_text'],
            'reviews_summary_positive' => $esRow['reviews_summary_positive'],
            'reviews_summary_negative' => $esRow['reviews_summary_negative']
        ) ;
        
        $esRow['prices'] = array(
            'price' => $esRow['price'],
            'currencyCode' => $esRow['currencyCode'],
            'priceFrom' => $esRow['price_from']
        );
        
        
        $esRow['features'] = array();
        $query2 = "SELECT dhf.hotel_feature_id,df.title,df.title as title_key FROM discover_hotels_feature_to_hotel as dhf LEFT JOIN discover_hotels_feature as df on df.id= dhf.hotel_feature_id WHERE hotel_id=".$row['id'];
        if($rs2 = mysqli_query($conn, $query2)) {
            while($row2 = mysqli_fetch_assoc($rs2)) {
                $esRow['features'][] = $row2;
            }
            mysqli_free_result($rs2);
        }
        $esRow['media'] = array(
          'images_count' => $esRow['images_count'],
          'map_image' => $esRow['map_image']
        );
        unset($esRow["images_count"]);unset($esRow["map_image"]);unset($esRow["price"]);unset($esRow["currencyCode"]);unset($esRow["price_from"]);unset($esRow["rating"]);unset($esRow["rating_cleanliness"]);unset($esRow["rating_dining"]);unset($esRow["rating_location"]);unset($esRow["rating_rooms"]);unset($esRow["rating_service"]);unset($esRow["reviews_count"]);unset($esRow["avg_rating"]);unset($esRow["nb_votes"]);unset($esRow["rating_overall_text"]);unset($esRow["reviews_summary_positive"]);unset($esRow["reviews_summary_negative"]);unset($esRow["rating_points"]);unset($esRow["rating_facilities"]);unset($esRow["address"]);unset($esRow["address_short"]);unset($esRow["cityLongitude"]);unset($esRow["cityLatitude"]);unset($esRow["city_id"]);unset($esRow["city_name"]);unset($esRow["country_code"]);unset($esRow["country_name"]);unset($esRow["state_code"]);unset($esRow["state_name"]);unset($esRow["city_id"]);unset($esRow["cityName"]);unset($esRow["countryCode"]);unset($esRow["stateName"]);unset($esRow["longitude"]);unset($esRow["latitude"]);
        
        $query1 = "SELECT * 
                   FROM `discover_hotels_images` 
                   WHERE hotel_id = ".$row['id']." 
                   LIMIT 1";
        // limit 1 for number of record of images to many elastic was craching
        if($rs1 = mysqli_query($conn, $query1)) {
            $esRow['media']['imageExists'] = 1;
            while($row1 = mysqli_fetch_assoc($rs1)){
                $imagesArray[]=$row1; 
            }
            $esRow['media']['images'] = $imagesArray;
            mysqli_free_result($rs1);
        }
        $imagesArray = array();
        $params[] = json_encode(array(
               'index' => array('_id' =>$esRow['id'],'_index'=>$index,'_type'=>$type )
               )); 
        $params[] = json_encode($esRow);

        
        if ($i % $limit==0) { 
            $counter = $counter +1 ;
                
            $params = implode("\n", $params);
            $params .= "\n";
            $elasticSearchClient->insertBulk($params, $type,$index);

            echo "new $limit records have added on: ".date('l jS \of F Y h:i:s A') . " current estimated records in ES [" . ($limit * $counter) . "]\n" ; 
            
            unset($params);
            unset($responses);
        }
        if ($i==$count && !empty($params)) {
            $params = implode("\n", $params);
            $params .= "\n";
            $elasticSearchClient->insertBulk($params, $type,$index);

            echo "importing last left records";
            unset($params);
            unset($responses);
        }
        $i++;
    }
    mysqli_free_result($rs);
}

?>
