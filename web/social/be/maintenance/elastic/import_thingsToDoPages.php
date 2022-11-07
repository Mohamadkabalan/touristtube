<?php
require_once('TTElasticSearchClient.php');

$elasticSearchClient = new elasticSearchClient();
$conn = $elasticSearchClient->elasticSearchClientConnection();

$index = "tt_thingstodo";
$type= "thingstodo"; // type name to be used for elastic

$query = "SELECT cttd.id, cttd.title as name, cttd.title as name_key, cttd.description, 'city' as type, cttd.alias_id as aliasId, a.alias as alias, cttd.country_code as countryCode, cttd.city_id as cityId
          FROM cms_thingstodo as cttd
          INNER JOIN alias as a ON a.id = cttd.alias_id
          WHERE cttd.published = 1";
if($rs=mysqli_query($conn,$query)){
    $count=mysqli_num_rows($rs);

    if($count<1)
        die('No data to import');

    
    $limit = 1000;
    $counter = 0;
    $i=1;
    while($row=mysqli_fetch_assoc($rs)){
        echo date('c')." id: ".$row['id']."-".$row['type']."\n";
        $params[] = json_encode(array(
               'index' => array('_id' =>$row['id']."-".$row['type'],'_index'=>$index,'_type'=>$type )
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








$query2 = "SELECT cttd.id, cttd.title as name, cttd.title as name_key, cttd.description, 'country' as type, cttd.alias_id as aliasId, a.alias as alias, cttd.country_code as countryCode, 0 as cityId
          FROM cms_thingstodo_country as cttd
          INNER JOIN alias as a ON a.id = cttd.alias_id
          WHERE cttd.published = 1";
if($rs2=mysqli_query($conn,$query2)){
    $count=mysqli_num_rows($rs2);

    if($count<1)
        die('No data to import');

    
    $limit = 1000;
    $counter = 0;
    $i=1;
    while($row2=mysqli_fetch_assoc($rs2)){
        echo date('c')." id: ".$row2['id']."\n";
        $params[] = json_encode(array(
               'index' => array('_id' =>$row2['id']."-".$row2['type'],'_index'=>$index,'_type'=>$type )
               )); 
        $params[] = json_encode($row2);

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
    
    mysqli_free_result($rs2);
}








$query3 = "SELECT cttd.id, cttd.title as name, cttd.title as name_key, cttd.description, 'region' as type, cttd.alias_id as aliasId, a.alias as alias, '' as countryCode, 0 as cityId
          FROM cms_thingstodo_region as cttd
          INNER JOIN alias as a ON a.id = cttd.alias_id
          WHERE cttd.published = 1";
if($rs3=mysqli_query($conn,$query3)){
    $count=mysqli_num_rows($rs3);

    if($count<1)
        die('No data to import');

    
    $limit = 1000;
    $counter = 0;
    $i=1;
    while($row3=mysqli_fetch_assoc($rs3)){
        echo date('c')." id: ".$row3['id']."\n";
        $params[] = json_encode(array(
               'index' => array('_id' =>$row3['id']."-".$row3['type'],'_index'=>$index,'_type'=>$type )
               )); 
        $params[] = json_encode($row3);

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
    mysqli_free_result($rs3);
}

?>

