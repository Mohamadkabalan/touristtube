<?php
require_once('TTElasticSearchClient.php');

$elasticSearchClient = new elasticSearchClient();
$conn = $elasticSearchClient->elasticSearchClientConnection();

$index = "tt_media";
$type = "media"; 

$query = "SELECT cv.id, cv.last_modified as last_updated, cv.code, cv.name, cv.fullpath, cv.relativepath, cv.type, cv.title, cv.title AS title_key, cv.description, cv.category, cv.placetakenat, cv.keywords, cv.country, cv.location, cv.pdate, cv.dimension, cv.duration, cv.userid, cv.published, cv.nb_views, cv.nb_comments, cv.nb_ratings, cv.rating, cv.nb_shares, cv.like_value as nb_likes, cv.up_votes, cv.lattitude, cv.longitude, cv.cityid, cv.cityname, IF(cv.is_public, 'true', 'false') as is_public, cv.trip_id, cv.image_video, cv.location_id, cv.video_url, cv.hash_id, cv.channelid, IF(cv.can_share, 'true', 'false') as can_share, IF(cv.can_comment, 'true', 'false') as can_comment, fc_getallowedmediausers(cv.id, cv.userid, 1) as allowed_users,
                 w.id as cityId, w.name as cityName, w.popularity as cityPopularity, w.latitude as cityLatitude, w.longitude as cityLongitude,
                 cc.id as countryId, cc.code as countryCode, cc.name as countryName,
                 cu.FullName as userName, cu.YourEmail as userEmail
          FROM cms_videos AS cv
          LEFT JOIN webgeocities w ON w.id = cv.cityid 
          LEFT JOIN cms_countries cc ON cc.code = cv.country
          LEFT JOIN cms_users cu ON cu.id = cv.userid
          WHERE cv.published = 1
          AND cv.is_public = 2";

$rs = mysqli_query($conn,$query);
if($rs = mysqli_query($conn,$query)){
    
    $count = mysqli_num_rows($rs);
    if($count < 1)
            die('No records to import');

    echo "\n".date('c')."\n\n$type count:: $count\n";


    $limit = 1000;
    $counter = 0;
    $i=1;
    while($row = mysqli_fetch_assoc($rs)) {
        echo date('c')." id: ".$row['id']."\n";
        $languageArray=array();

        $esRow = $row;
        $q = "SELECT title
              FROM cms_allcategories
              WHERE id=".$row['category'];

        if($result = mysqli_query($conn,$q)) {
            while($data = mysqli_fetch_assoc($result)) {
                $category= $data['title'];
            }
            mysqli_free_result($result);
        }
        $esRow['category'] = array('id'=>$row['category'],'name'=>$category);
        
        $esRow['stats'] = array('can_comment'=>$esRow['can_comment'],'can_share'=>$esRow['can_share'],'nb_likes'=>$esRow['nb_likes'],'nb_comments'=>$esRow['nb_comments'],'nb_ratings'=>$esRow['nb_ratings'],'nb_shares'=>$esRow['nb_shares'],'nb_views'=>$esRow['nb_views'],'rating'=>$esRow['rating'],'up_votes'=>$esRow['up_votes']);
        
        $esRow['media'] = array('fullpath'=>$esRow['fullpath'],'relativepath'=>$esRow['relativepath'],'name'=>$esRow['name'],'type'=>$esRow['type']);
        
        $esRow['user'] = array('fullName'=>$esRow['userName'],'email'=>$esRow['userEmail'],'id'=>$esRow['userid']);
       
        
        $esRow['location'] = array(
            'city' => array('id' => $esRow['cityId'],'name' => $esRow['cityName'],'name_key' => $esRow['cityName'],'popularity' => $esRow['cityPopularity']),
            'country' => array('id' => $esRow['countryId'],'code' => $esRow['countryCode'],'name' => $esRow['countryName'],'name_key' => $esRow['countryName']),
            'coordinates' => array('lat' => floatval($esRow['cityLatitude']),'lon' => floatval($esRow['cityLongitude']))
        );
        $query1 = "SELECT  lang_code, title, description, placetakenat, keywords
                   FROM ml_videos 
                   WHERE video_id=".$esRow['id']."
                   AND published = 1";

        if($rs1 = mysqli_query($conn,$query1)) {
            while($row1 = mysqli_fetch_assoc($rs1)) {
                $languageArray[]=array(
                    'title_'.$row1['lang_code'] => $row1['title'],
                    'description_'.$row1['lang_code'] => $row1['description'],
                    'placetakenat_'.$row1['lang_code'] => $row1['placetakenat'],
                    'keywords_'.$row1['lang_code'] => $row1['keywords']
                );
            }
            mysqli_free_result($rs1);
        }
        $esRow['translation'] = $languageArray;
        unset($esRow['can_comment']);unset($esRow['can_share']);unset($esRow['nb_likes']);unset($esRow['nb_comments']);unset($esRow['nb_ratings']);unset($esRow['nb_shares']);unset($esRow['nb_views']);unset($esRow['rating']);unset($esRow['up_votes']);unset($esRow['fullpath']);unset($esRow['relativepath']);unset($esRow['name']);unset($esRow['type']);unset($esRow['userName']);unset($esRow['userEmail']);unset($esRow['userid']);unset($esRow['cityId']);unset($esRow['cityName']);unset($esRow['cityPopularity']);unset($esRow['countryId']);unset($esRow['countryCode']);unset($esRow['countryName']);unset($esRow['cityLatitude']);unset($esRow['cityLongitude']);
        
        $params[] = json_encode(array(
               'index' => array('_id' =>$esRow['id'],'_index'=>$index,'_type'=>$type )
               )); 
        $params[] = json_encode($esRow);

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