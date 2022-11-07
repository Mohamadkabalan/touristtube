<?php
require_once('TTElasticSearchClient.php');

$elasticSearchClient = new elasticSearchClient();
$conn = $elasticSearchClient->elasticSearchClientConnection();

$index = "tt_hotelscities";
$type = "hotelsCities"; 

$query = "SELECT w.id, w.name as name, w.name as name_key, w.popularity, w.longitude, w.latitude,
                 st.id as stateId, st.state_code as code, st.state_name as stateName, st.state_name as stateNameKey,
                 cc.id as countryId, cc.code as countryCode, cc.iso3, cc.name as countryName, cc.name as countryNameKey 
          FROM webgeocities as w
          INNER JOIN amadeus_hotel_city as ahc ON ahc.city_id = w.id
          LEFT JOIN states as st ON st.state_code = w.state_code AND st.country_code = w.country_code 
          LEFT JOIN cms_countries as cc ON cc.code = w.country_code";
if ($rs    = mysqli_query($conn, $query)) {
    $count = mysqli_num_rows($rs);
    if (!$count) die('No data to import');

    echo "\n".date('c')."\n\n$type count:: $count\n";
    
    $limit = 1000;
    $counter = 0;
    $i=1;
    while ($row = mysqli_fetch_assoc($rs)) {
        
        echo date('c')." id: ".$row['id']."\n";
        
        $row['tags'] = $elasticSearchClient->elasticSearchCreateTagsFromName($row['name']);
        
        $row['coordinates']['lat'] = floatval($row['latitude']);
        $row['coordinates']['lon'] = floatval($row['longitude']);
        $row['state']= array("id"=>$row['stateId'],"code"=>$row['code'],"name"=>$row['stateName'],"name_key"=>$row['stateNameKey']);
        $row['country']= array("id"=>$row['countryId'],"code"=>$row['countryCode'],"name"=>$row['countryName'],"name_key"=>$row['countryNameKey'],"iso3"=>$row['iso3']);
        
        unset($row['latitude']);unset($row['longitude']);unset($row['stateId']);unset($row['state_code']);unset($row['stateName']);unset($row['stateNameKey']);unset($row['countryId']);unset($row['countryCode']);unset($row['countryName']);unset($row['countryNameKey']);unset($row['iso3']);unset($row['code']);

        $params[] = json_encode(array(
               'index' => array('_id' =>$row['id'],'_index'=>$index,'_type'=>$type )
               )); 
        $params[] = json_encode($row);

        if ($i % $limit==0) { 
            $counter = $counter +1 ;
                
            $params = implode("\n", $params);
            //$params = json_encode($params);
            $params .= "\n";
            $elasticSearchClient->insertBulk($params, $type,$index);

            echo "new $limit records have added on: ".date('c')." current estimated records in ES [" . ($limit * $counter) . "]\n" ; 
            
            unset($params);
               // unset the bulk response when you are done to save memory
            unset($responses);
        }
        if ($i==$count && !empty($params)) {
            $params = implode("\n", $params);
            //$params = json_encode($params);
            $params .= "\n";
            $elasticSearchClient->insertBulk($params, $type,$index);

            echo "\n".date('c')."importing last left records";
//               $params = array();
            unset($params);
            unset($responses);
        }
        $i++;
    }
    echo "\n".date('c')." - Finished importing data";
    mysqli_free_result($rs);
}
?>

