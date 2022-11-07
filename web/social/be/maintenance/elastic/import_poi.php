<?php
require_once('TTElasticSearchClient.php');

$elasticSearchClient = new elasticSearchClient();
$conn = $elasticSearchClient->elasticSearchClientConnection();

$index = "tt_poi";
$type= "poi"; 

$query = "SELECT dp.id, dp.name, dp.name as name_key, dp.description, dp.email, dp.fax,dp.phone, dp.last_modified as last_updated, dp.popularity, dp.price as poiPrice, dp.published, IF(dp.show_on_map, 'true', 'false') as show_on_map, dp.stars, dp.status, dp.website, dp.cat as catName, dp.sub_cat, dp.city_id, dp.city, dp.country, dp.from_source, dp.longitude, dp.latitude, dp.address,
                 w.id as cityId, w.name as cityName, w.popularity as cityPopularity, w.latitude as cityLatitude, w.longitude as cityLongitude,
                 cc.id as countryId, cc.code as countryCode, cc.name as countryName
          FROM discover_poi dp
          LEFT JOIN webgeocities w ON w.id = dp.city_id 
          LEFT JOIN cms_countries cc ON cc.code = dp.country
          WHERE dp.published=1";
if($rs = mysqli_query($conn,$query)){
    $count = mysqli_num_rows($rs);
    if($count < 1)
            die('No data to import');

    echo "\n\n$type count:: $count\n";

    $limit = 1000;
    $counter = 0;
    $i = 1;
    while($row = mysqli_fetch_assoc($rs)) {
        echo date('c')." id: ".$row['id']."\n";
        $poiCatArray = array();
        $imagesArray = array();
        
        $row['price'] = array('currency_code'=>null, 'price' => $row['poiPrice']);
        $query2 = " SELECT dpc.categ_id, dc.title
                    FROM discover_poi_categ as dpc
                    LEFT JOIN discover_categs as dc ON dc.id = dpc.categ_id
                    WHERE dpc.poi_id = ".$row['id'];
        if($rs2 = mysqli_query($conn, $query2)) {
            while($row2 = mysqli_fetch_assoc($rs2)) {
                $poiCatArray[] = array('id' => $row2['categ_id'], 'name' => $row2['title']);
            }
            mysqli_free_result($rs2);
        }
        $row['category'] = $poiCatArray;
        $row['tags'] = array($row['catName'],$row['sub_cat']);


        $query3 = " SELECT dpi.id, dpi.filename, dpi.default_pic, dpi.user_id
                    FROM discover_poi_images as dpi
                    WHERE dpi.poi_id = ".$row['id'];
        if($rs3 = mysqli_query($conn, $query3)) {
            while($row3 = mysqli_fetch_assoc($rs3)){
                $imagesArray[] = array(
                        'id' => $row3['id'],
                        'default_pic' => $row3['default_pic'],
                        'filename' => $row3['filename'],
                        'user_id' => $row3['user_id']
                );
            }
            mysqli_free_result($rs3);
        }
        $row['images']= $imagesArray;
        
       
        $row['vendor'] = array(
            'id' => null,
            'name' => $row['from_source'],
            'location' => array(
                'city' => array('id' => $row['city_id'],'name' => $row['city'],'code' => null),
                'country' => array('code' => $row['country'],'name' => null),
                'state' => array('code' => null,'name' => null),
                'coordinates' => array('lat' => floatval($row['latitude']),'lon' => floatval($row['longitude']))
            )
        );
        $titlelocation = $row['name'].' '.$row['cityName'].' '.$row['countryName'];
        
        $row['location'] = array(
            'city' => array('id' => $row['cityId'],'name' => $row['cityName'],'name_key' => $row['cityName'],'popularity' => $row['cityPopularity']),
            'country' => array('id' => $row['countryId'],'code' => $row['countryCode'],'name' => $row['countryName'],'name_key' => $row['countryName']),
            'coordinates' => array('lat' => floatval($row['cityLatitude']),'lon' => floatval($row['cityLongitude'])),
            'titleLocation' => $titlelocation,
            'address' => $row['address']
        );
        unset($row['poiPrice']);unset($row['catName']);unset($row['sub_cat']);unset($row['city_id']);unset($row['city']);unset($row['country']);unset($row['latitude']);unset($row['longitude']);unset($row['cityId']);unset($row['cityName']);unset($row['cityPopularity']);unset($row['countryId']);unset($row['countryCode']);unset($row['countryName']);unset($row['cityLatitude']);unset($row['cityLongitude']);unset($row['address']);unset($row['from_source']);
        
        $params[] = json_encode(array(
               'index' => array('_id' =>$row['id'],'_index'=>$index,'_type'=>$type )
               )); 
        $params[] = json_encode($row);

        if ($i % $limit==0) { 
            $counter = $counter +1 ;
                
            $params = implode("\n", $params);
            $params .= "\n";
            
            $elasticSearchClient->insertBulk($params, $type,$index);

            echo "new $limit records have added on: ".date('c') . " current estimated records in ES [" . ($limit * $counter) . "]\n" ; 
            
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

