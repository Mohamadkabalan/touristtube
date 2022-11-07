<?php
require_once('TTElasticSearchClient.php');

$elasticSearchClient = new elasticSearchClient();
$conn = $elasticSearchClient->elasticSearchClientConnection();

$index = "tt_dictionary";
$type = "dictionary"; 
$query = "SELECT chs.id,chs.keyword,chs.name,chs.published 
          FROM `cms_hotel_search` AS chs 
          WHERE published=1";

if($rs = mysqli_query($conn,$query)) {
    $count = mysqli_num_rows($rs);
    
    if(!$count) die('No data to import');
    echo "\n".date('c')."\n\n$type count:: $count\n";
    
    $limit = 100;
    
    $counter = 0;
    $i = 1;
    while($row=mysqli_fetch_assoc($rs)) {
            echo date('c')." id: ".$row['id']."\n";
        $row['results'] = array();
        
        $query2 = "SELECT chsd.id, chsd.entity_id, chsd.entity_type, 'hotels' AS type, chsd.name, chsd.popularity, chsd.popular AS entity_popularity, chsd.published, chsd.longitude, chsd.latitude, chsd.country_code
                   FROM `cms_hotel_search_details` AS chsd WHERE chsd.`hotel_booking_id` = ".$row['id']." AND chsd.published=1 ORDER BY chsd.`popularity`";

        if($rs2 = mysqli_query($conn, $query2)) {
            while($row2 = mysqli_fetch_assoc($rs2)) {
                $row2['coordinates']['lon'] = floatval($row2['longitude']);
                $row2['coordinates']['lat'] = floatval($row2['latitude']);
                unset($row2['longitude']);
                unset($row2['latitude']);
                
                $row['results'][] = $row2;
            }
            mysqli_free_result($rs2);
        }
        if( sizeof($row['results'])>0){
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

                echo "new $limit records have added on: ".date('c'). " current estimated records in ES [" . ($limit * $counter) . "]\n" ; 
                echo "\n".date('c')."importing every ".$i." records \n";
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
        }
        $i++;
    }
    echo "\n".date('c')." - Finished importing data";
    mysqli_free_result($rs);
}

?>
