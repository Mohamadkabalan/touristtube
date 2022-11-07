<?php
require_once('TTElasticSearchClient.php');

$elasticSearchClient = new elasticSearchClient();
$conn = $elasticSearchClient->elasticSearchClientConnection();

$index = "tt_deals";
$type  = "deals"; // type name to be used for elastic
$query = "SELECT 'deals' as type, dd.id, dd.deal_code as code, dd.deal_name as name, dd.deal_name as name_key, dd.description as description, dd.deal_api_id as api_id, dd.deal_type_id as type_id, dd.start_time as start, dd.end_time as end, dd.duration as duration, dd.currency as currency_code, dd.price_before_promo as amount_before_discount, dd.price as amount, dd.popularity as popularity, dd.published as published, dd.updated_at as last_updated,
                 dc.id as vendorCityId, dc.city_code as vendorCityCode, dc.parent_city_code as vendorCityParentCode, dc.city_name as vendorCityName, dc.state as vendorStateCode,
                 dco.country_code as vendorCountryCode, dco.country_name as vendorCountryName,
                 w.id as cityId, w.name as cityName, w.popularity as cityPopularity, w.latitude as cityLatitude, w.longitude as cityLongitude,
                 cc.id as countryId, cc.code as countryCode, cc.name as countryName
        FROM deal_details dd 
        LEFT JOIN deal_city as dc ON dc.id = dd.deal_city_id 
        LEFT JOIN deal_country as dco ON dco.id = dd.country_id 
        INNER JOIN webgeocities w ON w.id = dc.city_id
        LEFT JOIN states as st ON st.state_code = w.state_code AND st.country_code = w.country_code 
        LEFT JOIN cms_countries as cc ON cc.code = w.country_code 
        WHERE dd.published = 1";
if ($rs    = mysqli_query($conn, $query)) {
    $count = mysqli_num_rows($rs);
    if ($count < 1) die('No data to import');
    
    echo "\n".date('c')."\n\n$type count:: $count\n";
    
    $translation= array();
    $limit = 1000;
    $counter = 0;
    $i = 1;
    while ($row = mysqli_fetch_assoc($rs)) {
        echo date('c')." id: ".$row['id']."\n";
        
        $row['tags'] = $elasticSearchClient->elasticSearchCreateTagsFromName($row['cityName']);

        $row['titleLocation'] = $row['name'].' '.$row['vendorCityName'].(isset($row['vendorCountryName']) ? ' '.$row['vendorCountryName'] : '');
        $row['details'] = array("api_id"=>$row['api_id'],"type_id"=>$row['type_id'],"start"=>$row['start'],"end"=>$row['end'],"duration"=>$row['duration']);
//        $row['media'] = array("image"=>$row['image']);
        $row['vendor'] = array(
                "id"=>null,
                "name"=>null,
                "description"=>null,
                "location"=>array(
                    "city"=> array("id"=>$row['vendorCityId'],"code"=>$row['vendorCityCode'],"parent_code"=>$row['vendorCityParentCode'],"name"=>$row['vendorCityName']),
                    "country"=> array("code"=>$row['vendorCountryCode'],"name"=>$row['vendorCountryName']),
                    "state"=> array("code"=>$row['vendorStateCode'],"name"=>null),
                    "coordinates"=> array("lat"=>0,"lon"=>0)
                )
            );
        $row['location'] = array(
                "city"=> array("id"=>$row['cityId'],"name"=>$row['cityName'],"name_key"=>$row['cityName'],"popularity"=>$row['cityPopularity']),
                "country"=> array("id"=>$row['countryId'],"code"=>$row['countryCode'],"name"=>$row['countryName'],"name_key"=>$row['countryName']),
                "coordinates"=> array("lat"=>floatval($row['cityLatitude']),"lon"=>floatval($row['cityLongitude']))
            );
        $row['stats'] = array("nb_views"=> null);
        $row['price'] = array("currency_code"=> $row['currency_code'],"amount_before_discount"=>$row['amount_before_discount'],"amount"=>$row['amount']);
        $row['stars'] = null;
        
        $query5 = "SELECT * from `ml_deal_texts` where deal_code = '".$row['code']."'";
        if ($rs5    = mysqli_query($conn, $query5)) {
            while ($row5 = mysqli_fetch_assoc($rs5)) {
                $translation['name_'.$row5['lang_code']] = $row5['deal_name'];
                $translation['description_'.$row5['lang_code']] = $row5['deal_description'];
                $translation['highlights_'.$row5['lang_code']] = $row5['deal_highlights'];
                $translation['city_'.$row5['lang_code']] = $row5['deal_city'];
            }
            $row['translation'] = $translation;
            mysqli_free_result($rs5);
        }
        
        unset($row['api_id']);unset($row['type_id']);unset($row['duration']);unset($row['start']);unset($row['end']);
        unset($row['vendorCityId']);unset($row['vendorCityCode']);unset($row['vendorCityParentCode']);unset($row['vendorCityName']);unset($row['vendorCountryCode']);
        unset($row['vendorCountryName']);unset($row['vendorStateCode']);unset($row['cityId']);unset($row['cityName']);unset($row['cityPopularity']);
        unset($row['countryId']);unset($row['countryCode']);unset($row['cityLatitude']);unset($row['cityLongitude']);unset($row['countryName']);
        unset($row['currency_code']);unset($row['amount_before_discount']);unset($row['amount']);unset($row['image']);
        
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

