<?php
require_once('TTElasticSearchClient.php');

$elasticSearchClient = new elasticSearchClient();
$conn = $elasticSearchClient->elasticSearchClientConnection();

$index = "tt_hrshotels";
$type= "hrshotels"; // type name to be used for elastic
$query = "SELECT h.id, h.name, LCASE(h.name) as name_key, h.description, h.stars, h.popularity, chs.is_active AS published, h.map_image, h.city, h.city_id, h.latitude, h.longitude, h.address, h.street,h.district, h.zip_code, 0 as dupe_pool_id, h.downtown, h.distance_from_downtown, h.airport, h.distance_from_airport, h.train_station, h.distance_from_train_station,
                 chs.location_id, chs.source, chs.source_id,
                 w.id as cityId, w.name as cityName, w.popularity as cityPopularity, w.latitude as cityLatitude, w.longitude as cityLongitude,w.state_code as state_code,
                 cc.id as countryId, cc.code as countryCode, cc.name as countryName,
                 NOW() as last_updated
          FROM cms_hotel as h 
          INNER JOIN cms_hotel_source as chs ON (chs.hotel_id = h.id AND chs.is_active = 1)
          LEFT JOIN webgeocities as w ON (w.id = h.city_id)
          LEFT JOIN cms_countries as cc ON (cc.code = h.country_code)";

if($rs=mysqli_query($conn,$query)){
    $count=mysqli_num_rows($rs);
    if($count<1)
        die('No data to import');

    echo "\n".date('c')."\n\n$type count:: $count\n";

    $limit = 1000;
    $counter = 0;
    $i=1;
    $vendorQuery = "SELECT  tv.id ,tv.name
                    FROM   `tt_vendors` AS tv
                    WHERE   tv.id = 3";

    if($rsVendor=mysqli_query($conn,$vendorQuery)){
        $rowVendor = mysqli_fetch_assoc($rsVendor);
        mysqli_free_result($rsVendor);
    }
    while($row=mysqli_fetch_assoc($rs)){
        echo date('c')." id: ".$row['id']."\n";
        $row['vendor'][] = array("coordinates" => array("lat" => floatval($row['latitude']), "lon"=>floatval($row['longitude'])),"city" => array("name" => $row['city'], "id"=>$row['city_id']), "name" => $rowVendor['name'], "id" => $rowVendor['id'], "entity_id" => $row['source_id']);
        
        
        $images = array();
        $imagesQuery = "SELECT  chi.filename, chi.location, chi.default_pic
                    FROM   `cms_hotel_image` AS chi
                    WHERE   chi.hotel_id = ".$row['id'];

        if($rsImages=mysqli_query($conn,$imagesQuery)){
            while ($rowImages = mysqli_fetch_assoc($rsImages)) {
                $images[] = array("filename" => $rowImages['filename'], "id" => $rowImages['default_pic'], "location" => $rowImages['location'], "dupe_pool_id" => $row['dupe_pool_id']);
            }
            mysqli_free_result($rsImages);
        }
        $row['media'][] = $images;
            
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
        $row['amenities'] = $amenities;   
        $row['slug'] = str_replace(' ', '+', $row['name']);
        
        $row['location'][] = array("id" => $row['location_id'],"title" => $row['source'],"zip_code" => $row['zip_code'],"downtown" => $row['downtown'],"address_line_1" => $row['street']." ".$row['district']." ".$row['zip_code'],"address_line_2" => $row['address'],"city" => array("id" => $row['cityId'],"name" => $row['cityName'],"coordinates" => array("lat" =>floatval($row['cityLatitude']),"lon"=>floatval($row['cityLongitude']))),"country" => array("code" => $row['countryCode'],"name" => $row['countryName']),"coordinates" => array("lat"=>floatval($row['latitude']),"lon"=>floatval($row['longitude'])));
        
        $row['distance'][] = array(
                                    array("id" => 0,"from" => $row['downtown'], "name" => "downtown", "value" => $row['distance_from_downtown']),
                                    array("id" => 0,"from" => $row['airport'], "name" => "airport", "value" => $row['distance_from_airport']),
                                    array("id" => 0,"from" => $row['train_station'], "name" => "train_station", "value" => $row['distance_from_train_station'])
                                   );
        $translation = array();
        $translationQuery = " SELECT mh.`lang_code` , mh.`description` , mh.`name`
                          FROM `ml_hotel` AS mh
                          LEFT JOIN cms_hotel AS h ON h.id = mh.hotel_id
                          WHERE h.id =".$row['id'];

        if($rsTranslation=mysqli_query($conn,$translationQuery)){
            while ($rowTranslation = mysqli_fetch_assoc($rsTranslation)) {
                $translation[] = array("name_".$rowTranslation['lang_code'] => $rowTranslation['name'], "description_".$rowTranslation['lang_code'] => $rowTranslation['description']);
            }
            mysqli_free_result($rsTranslation);
        }
        unset($row['latitude']);unset($row['longitude']);unset($row['city']);unset($row['source_id']);unset($row['location_id']);unset($row['source']);unset($row['zip_code']);unset($row['downtown']);unset($row['street']);unset($row['district']);unset($row['address']);unset($row['cityId']);unset($row['cityName']);unset($row['cityLatitude']);unset($row['cityLongitude']);unset($row['countryCode']);unset($row['countryName']);unset($row['distance_from_downtown']);unset($row['distance_from_airport']);unset($row['distance_from_train_station']);unset($row['airport']);unset($row['train_station']);
        
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

