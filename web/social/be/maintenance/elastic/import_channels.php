<?php

require_once('TTElasticSearchClient.php');

$elasticSearchClient = new elasticSearchClient();
$conn = $elasticSearchClient->elasticSearchClientConnection();

$index = "tt_channels";
$type= "channels"; // type name to be used for elastic


$query = "SELECT ch.id, ch.channel_name as name, ch.channel_name as name_key, ch.owner_id, ch.logo, ch.small_description as summary, ca.title AS catname, ch.category AS catid, ch.channel_url as url, ch.nb_comments, ch.up_votes, (nb_comments + up_votes) AS social_weight, ch.create_ts as last_updated, ch.header, ch.popularity AS popularity,
                 w.id as cityId, w.name as cityName, w.popularity as cityPopularity, w.latitude as cityLatitude, w.longitude as cityLongitude,
                 cc.id as countryId, cc.code as countryCode, cc.name as countryName,
                 cu.FullName as ownerName
          FROM cms_channel_category ca, cms_channel ch 
          LEFT JOIN webgeocities w ON w.id = ch.city_id 
          LEFT JOIN cms_countries cc ON cc.code = country 
          LEFT JOIN cms_users cu ON cu.id = ch.owner_id
          WHERE ch.category = ca.id 
          AND ch.published = 1";

if($rs=mysqli_query($conn,$query)){
    $count=mysqli_num_rows($rs);
    if($count<1)
        die('No data to import');

    echo "\n".date('c')."\n\n$type count:: $count\n";

    $limit = 1000;
    $counter = 0;
    $i=1;
    while($row=mysqli_fetch_assoc($rs)){
        echo date('c')." id: ".$row['id']."\n";
        
        $row['stats'] = array('nb_comments'=>$row['nb_comments'],'up_votes'=>$row['up_votes'],'social_weight'=>$row['social_weight']);
        
        $row['owner'] = array('id'=>$row['owner_id'],'name'=>$row['ownerName']);
        
        $row['category'] = array('id'=>$row['catid'],'name'=>$row['catname']);
        
        $row['media'] = array('images'=>array('header'=>$row['header'],'logo'=>$row['logo']));
        
        $row['location'] = array(
            'city' => array('id' => $row['cityId'],'name' => $row['cityName'],'name_key' => $row['cityName'],'popularity' => $row['cityPopularity']),
            'country' => array('id' => $row['countryId'],'code' => $row['countryCode'],'name' => $row['countryName']),
            'coordinates' => array('lat' => floatval($row['cityLatitude']),'lon' => floatval($row['cityLongitude'])),
        );
        
        unset($row['header']);unset($row['logo']);unset($row['cityId']);unset($row['cityName']);unset($row['cityPopularity']);unset($row['countryId']);unset($row['countryCode']);unset($row['countryName']);unset($row['cityLatitude']);unset($row['cityLongitude']);unset($row['catid']);unset($row['catname']);unset($row['ownerName']);unset($row['owner_id']);unset($row['nb_comments']);unset($row['up_votes']);unset($row['social_weight']);
        
        
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

