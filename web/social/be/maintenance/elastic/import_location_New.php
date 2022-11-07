<?php
require_once('TTElasticSearchClient.php');


$elasticSearchClient = new elasticSearchClient();
$conn = $elasticSearchClient->elasticSearchClientConnection();

$index = "tt_location";
$type  = "location";



$query3 = "SELECT id, 'country' as type, code as contryCode, name as name, name as name_key, popularity, full_name as fullName, popularity, latitude, longitude, ioc_code as countryThreeCode, iso3 as countryISO3Code, IF(used_by_safe_charge, 'true', 'false') as usedBySafeCharge, flag_icon as flagIcon, ST_AsText(ST_GeomFromWKB(ST_AsWKB(bounding_box))) as boundingBox
          FROM cms_countries as cc";
if ($rs3    = mysqli_query($conn, $query3)) {
    $count = mysqli_num_rows($rs3);
    if ($count < 1) die('No data to import');
    
    echo "\n".date('c')."\n\n$type count:: $count\n";
    
    $translation= array();
    $limit = 1000;
    $counter = 0;
    $i = 1;
    while ($row3 = mysqli_fetch_assoc($rs3)) {
        echo date('c')." id: ".$row3['id']."\n";
        $translationQuery='SELECT *
                           FROM ml_countries
                           WHERE code = "'.$row3['contryCode'].'"';
        if ($rsTranslation = mysqli_query($conn, $translationQuery)) {
            while ($rowTranslation = mysqli_fetch_assoc($rsTranslation)){
                $translation['code'] = $rowTranslation['code'];
                $translation['name_'.$rowTranslation['lang_code']] = $rowTranslation['name'];
            }
        }
        $row3['translation'] = $translation;
        mysqli_free_result($rsTranslation);
        
        $params[] = json_encode(array(
               'index' => array('_id' =>$row3['type'].'_'.$row3['id'],'_index'=>$index,'_type'=>$type )
               )); 
           $params[] = json_encode($row3);

        if ($i % $limit==0) { 
            $counter = $counter +1 ;
                
            $params = implode("\n", $params);
            $params .= "\n";
            $elasticSearchClient->insertBulk($params, $type,$index);

            echo "new $limit records have added on: ".date('c'). " current estimated records in ES [" . ($limit * $counter) . "]\n" ; 
            
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
    mysqli_free_result($rs3);
    
}


$query2 = "SELECT id, 'state' as type, country_code as contryCode, state_code as stateCode, state_name as name, state_name as name_key, popularity, IF(used_by_safe_charge, 'true', 'false') as usedBySafeCharge
          FROM states as s";
if ($rs2    = mysqli_query($conn, $query2)) {
    $count = mysqli_num_rows($rs2);
    if ($count < 1) die('No data to import');
    
    echo "\n\n$type count:: $count\n";
    
    $translation= array();
    $limit = 1000;
    $counter = 0;
    $i = 1;
    while ($row2 = mysqli_fetch_assoc($rs2)) {
        echo date('c')." id: ".$row2['id']."\n";
        
        $translationQuery='SELECT *
                           FROM ml_states
                           WHERE country_code = "'.$row2['contryCode'].'"
                           AND state_code = "'.$row2['stateCode'].'"';
        if ($rsTranslation = mysqli_query($conn, $translationQuery)) {
            while ($rowTranslation = mysqli_fetch_assoc($rsTranslation)){
                $translation['stateCode'] = $rowTranslation['state_code'];
                $translation['countryCode'] = $rowTranslation['country_code'];
                $translation['name_'.$rowTranslation['lang_code']] = $rowTranslation['state_name'];
            }
        }
        $row2['translation'] = $translation;
        mysqli_free_result($rsTranslation);
        $params[] = json_encode(array(
               'index' => array('_id' =>$row2['type'].'_'.$row2['id'],'_index'=>$index,'_type'=>$type )
               )); 
           $params[] = json_encode($row2);

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
    mysqli_free_result($rs2);
    
}

$query = "SELECT id, 'city' as type, country_code as contryCode, state_code as stateCode, accent as fullName, name, name as name_key, latitude, longitude, popularity
          FROM webgeocities as w";
if ($rs    = mysqli_query($conn, $query)) {
    $count = mysqli_num_rows($rs);
    if ($count < 1) die('No data to import');
    
    echo "\n\n$type count:: $count\n";
    
    $translation= array();
    $limit = 1000;
    $counter = 0;
    $i = 1;
    while ($row = mysqli_fetch_assoc($rs)) {
        echo date('c')." id: ".$row['id']."\n";
        
        $translationQuery='SELECT *
                           FROM ml_webgeocities
                           WHERE city_id = "'.$row['id'].'"';
        if ($rsTranslation = mysqli_query($conn, $translationQuery)) {
            while ($rowTranslation = mysqli_fetch_assoc($rsTranslation)){
                $translation['id'] = $rowTranslation['city_id'];
                $translation['name_'.$rowTranslation['lang_code']] = $rowTranslation['name'];
            }
        }
        $row2['translation'] = $translation;
        mysqli_free_result($rsTranslation);
        
        $params[] = json_encode(array(
               'index' => array('_id' =>$row['type'].'_'.$row['id'],'_index'=>$index,'_type'=>$type )
               )); 
           $params[] = json_encode($row);

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
    
}
    mysqli_free_result($rs);
?>
