<?php
require_once('TTElasticSearchClient.php');

$elasticSearchClient = new elasticSearchClient();
$conn = $elasticSearchClient->elasticSearchClientConnection();

$index = "tt_airports";
$type= "airports";

$query = "SELECT a.id, a.name, a.airport_code as airportCode, a.map_image, a.world_area_code, a.city, a.country, a.state_code, a.longitude, a.latitude, a.gmt_offset, a.map_image, a.email, a.fax, a.published, a.runway_elevation, a.runway_length, IF(a.show_on_map, 'true', 'false') as show_on_map, a.stars, a.telephone, a.website, a.zoom_order, a.last_modified, IF(a.used_by_sabre, 'true', 'false') as used_by_sabre, a.popularity,
                 w.id as cityId, w.name as cityName, null as code, w.popularity as cityPopularity, w.latitude as cityLatitude, w.longitude as cityLongitude,
                 cc.id as countryId, cc.code as countryCode, cc.name as countryName
          FROM airport a 
          INNER JOIN webgeocities w ON w.id = a.city_id
          LEFT JOIN states as st ON st.state_code = w.state_code AND st.country_code = w.country_code 
          LEFT JOIN cms_countries as cc ON cc.code = w.country_code 
          WHERE a.published = 1 AND NOT (a.name LIKE 'Bus %' OR a.name LIKE '% Bus %' OR a.name LIKE '% Bus') 
		  AND NOT (a.name LIKE 'Train %' OR a.name LIKE '% Train %' OR a.name LIKE '% Train') 
		  AND NOT (a.name LIKE 'Rail%' OR a.name LIKE '% Rail%')";

if($rs=mysqli_query($conn,$query)){
    $count=mysqli_num_rows($rs);

    if($count < 1)
        die('No data to import');

    echo "\n".date('c')."\n\n$type count:: $count\n";

    $limit = 1000;
    $counter = 0;
    $i=1;
    while($row=mysqli_fetch_assoc($rs)){
        echo date('c')." id: ".$row['id']."\n";
        
        $row['titleLocation']= $row['name'].' '.$row['city'].' '.(isset($row['countryName'])?$row['countryName']:'');
        $row['useForBooking']= $row['used_by_sabre'];
        $row['name_key']= $row['name'];
        $row['media']= array('map_image'=>$row['map_image']);
        $row['code'] = $row['airportCode'];
        $row['vendor'] = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'location' => array(
                'world_area_code' => $row['world_area_code'],
                'city' => array('code' => null,'name' => $row['city']),
                'country' => array('code' => $row['country'],'name' => null),
                'state' => array('code' => $row['state_code'],'name' => null),
                'coordinates' => array('lat' => floatval($row['latitude']),'lon' => floatval($row['longitude']))
            ),
            "timezone" => array("gmt_offset" => $row['gmt_offset'])
        );
        $row['last_updated']= $row['last_modified'];
        $row['location'] = array(
            'city' => array('id' => $row['cityId'],'name' => $row['cityName'],'name_key' => $row['cityName'],'popularity' => $row['cityPopularity']),
            'country' => array('id' => $row['countryId'],'code' => $row['countryCode'],'name' => $row['countryName']),
            'coordinates' => array('lat' => floatval($row['cityLatitude']),'lon' => floatval($row['cityLongitude'])),
        );
        unset($row['world_area_code']);unset($row['city']);unset($row['country']);unset($row['state_code']);unset($row['latitude']);unset($row['longitude']);unset($row['gmt_offset']);unset($row['cityId']);unset($row['cityName']);unset($row['cityId']);unset($row['cityPopularity']);unset($row['countryId']);unset($row['countryCode']);unset($row['countryName']);unset($row['cityLatitude']);unset($row['cityLongitude']);unset($row['map_image']);unset($row['used_by_sabre']);unset($row['airportCode']);unset($row['last_modified']);
        
        $params[] = json_encode(array(
               'index' => array('_id' =>$row['id'],'_index'=>$index,'_type'=>$type )
               )); 
        $params[] = json_encode($row);

        if ($i % $limit==0) { 
            $counter = $counter +1 ;
                
            $params = implode("\n", $params);
            $params .= "\n";
            $elasticSearchClient->insertBulk($params, $type,$index);

            echo "new $limit records have added on: ".date('c')." current estimated records in ES [" . ($limit * $counter) . "]\n" ; 
            
            unset($params);
            unset($responses);
        }
        if ($i==$count && !empty($params)) {
            $params = implode("\n", $params);
            $params .= "\n";
            $elasticSearchClient->insertBulk($params, $type,$index);

            echo "\n".date('c')."importing last left records";
            unset($params);
            unset($responses);
        }
        $i++;
    }
    echo "\n".date('c')." - Finished importing data";
    mysqli_free_result($rs);
}

?>

