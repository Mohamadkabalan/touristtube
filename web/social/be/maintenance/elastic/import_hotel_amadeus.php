<?php
require_once('TTElasticSearchClient.php');

$elasticSearchClient = new elasticSearchClient();
$conn = $elasticSearchClient->elasticSearchClientConnection();

$index = "tt_hotels";
$type= "hotels"; // type name to be used for elastic


$countQuery = " SELECT count(1)
                FROM amadeus_hotel as h
                LEFT JOIN amadeus_hotel_city as c ON (c.id = h.amadeus_city_id)
                WHERE h.published = 1
                ORDER BY h.id ASC";


if($rs=mysqli_query($conn,$countQuery)){
    $countInfo = mysqli_fetch_assoc($rs);
    
    $count = $countInfo['count(1)'];
    if(!$count || $count == 0)
        die('No data to import');
        mysqli_free_result($rs);
        
        echo "\n".date('c')."\n\n$type count:: $count\n";
        
        $limit = 1000;
        $loopCounter = 0;
        
        while($loopCounter < $count){
            
            $sqlQuery = "SELECT h.id, h.dupe_pool_id, IF(h.images_downloaded, 'true', 'false') as 'images_downloaded', h.last_updated, h.description, h.stars, h.published, h.phone, h.popularity,
                            h.property_name as name, h.property_name as name_key, null as downtown, null as distance_from_downtown,null as train_station, null as distance_from_train_station, null as airport,
                            null as distance_from_airport, h.amadeus_city_id, h.logo, h.district, h.zip_code, w.state_code, h.address_line_1, h.address_line_2, h.slug, h.map_image,
                            h.fax, h.location, h.latitude, h.longitude,
                            w.id as city_id,w.name as city_name, w.popularity as cityPopularity, w.latitude as cityLatitude, w.longitude as cityLongitude,
                            c.id as amadeus_city_id,c.city_name as city_name, c.city_code as city_code,
                            cc.code as country_code,cc.name as country_name
                      FROM amadeus_hotel as h
                      LEFT JOIN amadeus_hotel_city as c ON (c.id = h.amadeus_city_id)
                      LEFT JOIN webgeocities as w ON (w.id = c.city_id)
                      LEFT JOIN cms_countries as cc ON (cc.code = w.country_code)
                      WHERE h.published = 1
                      ORDER BY h.id ASC
                      LIMIT ".$loopCounter.",".$limit;
            
            $rsSql=mysqli_query($conn,$sqlQuery);
            $params = array();
            $esRow = array();
            //
            while ($row = mysqli_fetch_assoc($rsSql)) {
                echo date('c')." id: ".$row['id']."\n";
                $params[] = json_encode(array(
                    'index' => array('_id' => $row['id'], '_index' => $index, '_type' => $type)
                ));
                $esRow = $row;
                //
                unset($esRow["location"]);
                $vendor = array();
                $vendorQuery = "  SELECT ahs.tt_vendor_id as vendorId, ahs.source as sourceName, ahs.tt_source_id as sourceId,
                                         ttv.name as vendorName
                                  FROM `amadeus_hotel_source` AS ahs
                                  LEFT JOIN tt_vendors AS ttv ON ttv.id = ahs.tt_vendor_id
                                  WHERE ahs.hotel_id =".$row['id'];
                if($rsVendor=mysqli_query($conn,$vendorQuery)){
                    while ($rowVendor = mysqli_fetch_assoc($rsVendor)) {
                        $vendorId = $rowVendor['vendorId'] ;
                        $vendorName = $rowVendor['vendorName'] ;
                        $vendor[] = array("id" => $rowVendor['sourceId'], "name" => $rowVendor['sourceName']);
                    }
                    mysqli_free_result($rsVendor);
                }
                $esRow['available_vendors'][] = array("id" => $vendorId,"name" => $vendorName,'source'=>$vendor);
                $esRow['vendor'][] = array("coordinates" => array("lat" => floatval($row['latitude']), "lon"=>floatval($row['longitude'])),"city" => array("name" => $row['city_name'], "id"=>$row['amadeus_city_id']), "name" => "amadeus", "id" => $row['dupe_pool_id'], "entity_id" => 0, "coordinates" => array("lat" => floatval($row['latitude']), "lon"=>floatval($row['longitude'])));
                
                $esRow['distance'][] = array("name" => $row['downtown'], "from" => "downtown", "id" => 0, "value" => $row['distance_from_downtown'] );
                $esRow['distance'][] = array("name" => $row['airport'], "from" => "airport", "id" => 0, "value" => $row['distance_from_airport'] );
                $esRow['distance'][] = array("name" => $row['train_station'], "from" => "train_station", "id" => 0, "value" => $row['distance_from_train_station'] );
                $esRow['location'][] = array(
                    "country" => array("code" => $row['country_code'], "name"=>$row['country_name']),
                    "city" => array("code" => $row['city_code'], "name"=>$row['city_name'], "id" => $row['city_id'], "popularity"=>$row['cityPopularity']),
                    "coordinates" => array("lon" => floatval($row['cityLongitude']), "lat" =>floatval($row['cityLatitude'])),
                    "district" => $row['district'],
                    "address_line_1" => $row['address_line_1'],
                    "downtown" => $row['downtown'],
                    "id" => 0,
                    "code" => $row['location'],
                    "address_line_2" => $row['address_line_2'],
                    "state_code" => $row['state_code'],
                    "zip_code" => $row['zip_code']);
                
                unset($esRow["code"]);
                unset($esRow["country_name"]);
                unset($esRow["city_code"]);
                unset($esRow["city_name"]);unset($esRow["city_id"]);unset($esRow["district"]);unset($esRow["address_line_1"]);unset($esRow["address_line_2"]);unset($esRow["state_code"]);unset($esRow["zip_code"]);unset($esRow["longitude"]);unset($esRow["latitude"]);unset($esRow["downtown"]);unset($esRow["distance_from_downtown"]);unset($esRow["airport"]);unset($esRow["distance_from_airport"]);unset($esRow["train_station"]);unset($esRow["distance_from_train_station"]);unset($esRow["city_name"]);unset($esRow["amadeus_city_id"]);unset($esRow["dupe_pool_id"]);
                $translation = array();
                $translationQuery = " SELECT mh.`lang_code` , mh.`description` , mh.`name`
                                  FROM `ml_hotel` AS mh
                                  LEFT JOIN amadeus_hotel AS h ON h.id = mh.hotel_id
                                  WHERE h.id =".$row['id'];
                
                if($rsTranslation=mysqli_query($conn,$translationQuery)){
                    while ($rowTranslation = mysqli_fetch_assoc($rsTranslation)) {
                        $translation[] = array("name_".$rowTranslation['lang_code'] => $rowTranslation['name'], "description_".$rowTranslation['lang_code'] => $rowTranslation['description']);
                    }
                    mysqli_free_result($rsTranslation);
                }
                $amenities = array();
                $amenitiesQuery = " SELECT chf.facility_id as id,cf.name as name
                                FROM   `cms_hotel_facility` AS chf
                                LEFT JOIN `cms_facility` AS cf ON cf.id = chf.facility_id
                                WHERE   chf.hotel_id = ".$row['id'];
                
                if($rsAmenities=mysqli_query($conn,$amenitiesQuery)){
                    while ($rowAmenities = mysqli_fetch_assoc($rsAmenities)) {
                        $amenities[] = array("name" => $rowAmenities['name'], "id" => $rowAmenities['id']);
                    }
                    mysqli_free_result($rsAmenities);
                }
                $esRow['amenities'] = $amenities;
                $images = array();
                $imagesQuery = "SELECT  ahi.filename, ahi.location, ahi.default_pic, ahi.dupe_pool_id
                            FROM   `amadeus_hotel_image` AS ahi
                            WHERE   ahi.hotel_id = ".$row['id'];
                
                if($rsImages=mysqli_query($conn,$imagesQuery)){
                    while ($rowImages = mysqli_fetch_assoc($rsImages)) {
                        $images[] = array("filename" => $rowImages['filename'], "id" => $rowImages['default_pic'], "location" => $rowImages['location'], "dupe_pool_id" => $rowImages['dupe_pool_id']);
                    }
                    mysqli_free_result($rsImages);
                }
                $esRow['media'] = $images;
                $params[] = json_encode($esRow);
            }
            
            $params = implode("\n", $params);
            //$params = json_encode($params);
            $params .= "\n";
            
            
            $elasticSearchClient->insertBulk($params, $type,$index);
            
            echo "new $limit records have added on: ".date('c'). " current estimated records in ES [" . ($limit * $loopCounter) . "]\n" ;
            
            $loopCounter = $loopCounter + $limit;
        }
        echo "\n".date('c')." - Finished importing data";
        mysqli_free_result($rsSql);
}

?>

