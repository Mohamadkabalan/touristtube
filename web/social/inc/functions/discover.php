<?php
/**
 * add reviews entity for a given user 
 * @param integer $user_id the user id
 * @param integer $entity_type the entity type
 * @param integer $entity_id the entity id
 * @param string $title the reviews title
 * @param string $description the reviews description
 * @return integer | false the reviews id or false if not inserted
 */
function userReviewsAdd($user_id,$entity_type,$entity_id,$title,$description){
    global $dbConn;
    $params = array(); 
    $table ="";
    $entity_value ="";
    $review_type ="";
    if($entity_type == SOCIAL_ENTITY_HOTEL){
        $table ="discover_hotels_reviews";
        $entity_value ="hotel_id";
        $review_type =SOCIAL_ENTITY_HOTEL_REVIEWS;
    }else if($entity_type == SOCIAL_ENTITY_RESTAURANT){
//        $table ="discover_restaurants_reviews";
//        $entity_value ="restaurant_id";
//        $review_type =SOCIAL_ENTITY_RESTAURANT_REVIEWS;
    }else if($entity_type == SOCIAL_ENTITY_LANDMARK){
        $table ="discover_poi_reviews";
        $entity_value ="poi_id";
        $review_type =SOCIAL_ENTITY_LANDMARK_REVIEWS;
    }else if($entity_type == SOCIAL_ENTITY_AIRPORT){
        $table ="airport_reviews";
        $entity_value ="airport_id";
        $review_type =SOCIAL_ENTITY_AIRPORT_REVIEWS;
    }
    if($table==""){
        return false;
    }else{
        $query = "INSERT INTO ".$table." (user_id,".$entity_value.",title,description) VALUES (:User_id,:Entity_id,:Title,:Description)";
	$params[] = array(  "key" => ":User_id", "value" =>$user_id);
	$params[] = array(  "key" => ":Entity_id", "value" =>$entity_id);
	$params[] = array(  "key" => ":Title", "value" =>$title);
	$params[] = array(  "key" => ":Description", "value" =>$description);
	$insert = $dbConn->prepare($query);
	PDO_BIND_PARAM($insert,$params);
	$ret = $insert->execute();        
        if( $ret ){
            $rev_id = $dbConn->lastInsertId(); 
            newsfeedAdd($user_id, $rev_id , SOCIAL_ACTION_UPLOAD , $rev_id , $review_type , USER_PRIVACY_PUBLIC, null);
            return $rev_id;
        }else{
            return false;
        }
    }
}
/**
 * add reviews entity for a given user 
 * @param integer $user_id the user id
 * @param integer $entity_type the entity type
 * @param integer $entity_id the entity id
 * @param string $filename the image name
 * @return integer | false the reviews id or false if not inserted
 */
function userDiscoverImagesAdd($user_id,$entity_type,$entity_id,$filename){
    global $dbConn;
    $params = array();  
    $table ="";
    $table0 ="";
    $entity_value ="";
    if($entity_type == SOCIAL_ENTITY_HOTEL){
        $table0 ="discover_hotels";
        $table ="discover_hotels_images";
        $entity_value ="hotel_id";
    }else if($entity_type == SOCIAL_ENTITY_RESTAURANT){
//        $table0 ="global_restaurants";
//        $table ="discover_restaurants_images";
//        $entity_value ="restaurant_id";
    }else if($entity_type == SOCIAL_ENTITY_LANDMARK){
        $table0 ="discover_poi";
        $table ="discover_poi_images";
        $entity_value ="poi_id";
    }else if($entity_type == SOCIAL_ENTITY_AIRPORT){
        $table0 ="airport";
        $table ="airport_images";
        $entity_value ="airport_id";
    }
    if($table==""){
        return false;
    }else{
        $query = "INSERT INTO ".$table." (user_id,".$entity_value.",filename) VALUES (:User_id,:Entity_id,:Filename)";        
	$params[] = array(  "key" => ":User_id", "value" =>$user_id);
	$params[] = array(  "key" => ":Entity_id", "value" =>$entity_id);
	$params[] = array(  "key" => ":Filename", "value" =>$filename);
	$insert = $dbConn->prepare($query);
	PDO_BIND_PARAM($insert,$params);
	$ret = $insert->execute();
        if( $ret ){        
            $rev_id = $dbConn->lastInsertId();
            $params=array();
            $query = "UPDATE $table0 SET zoom_order = 20 WHERE id = :Id";
            $params[] = array(  "key" => ":Id", "value" =>$entity_id);
            $update = $dbConn->prepare($query);
            PDO_BIND_PARAM($update,$params);
            $ret = $update->execute();
            return $rev_id;
        }else{
            return false;
        }
    }
}
function userCmsHotelImagesAdd($user_id,$hotel_id,$filename,$location){
    global $dbConn;
    $params = array();
    $query = "INSERT INTO `cms_hotel_image`(`user_id`, `filename`, `hotel_id`, `location`) VALUES (:User_id,:Filename,:Hotel_id,:Location)";        
    $params[] = array(  "key" => ":User_id", "value" =>$user_id);
    $params[] = array(  "key" => ":Filename", "value" =>$filename);
    $params[] = array(  "key" => ":Hotel_id", "value" =>$hotel_id);
    $params[] = array(  "key" => ":Location", "value" =>$location);
    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params);
    $ret = $insert->execute();
    if( $ret ){        
        return $dbConn->lastInsertId();
    }else{
        return false;
    }
}
function getHotelImagesP($hotelId) {
    global $dbConn;
    $params1 = array();
    $query = "SELECT * FROM `cms_hotel_image` WHERE hotel_id = :Id ORDER BY default_pic DESC LIMIT 1";
    $params1[] = array(  "key" => ":Id", "value" =>$hotelId);    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params1);
    $ret = $select->execute();
    return $select->fetch(PDO::FETCH_ASSOC);
}
function getHotelHRSInfo($hotelId,$lang='en') {
    global $dbConn;
    $params1 = array();
    if($lang=='en'){
        $query = "SELECT h.longitude , h.latitude , h.name, 'h' as types, h.stars, h.id, h.zip_code, h.address, h.street FROM cms_hotel as h WHERE h.id = :Id LIMIT 1";
    }else{
        $query = "SELECT h.longitude , h.latitude , h.name, 'h' as types, h.stars, h.id, h.zip_code, h.address, h.street, ml.name as ml_name, ml.description as ml_description FROM cms_hotel as h LEFT JOIN ml_hotel AS ml on ml.hotel_id=h.id AND ml.lang_code=:Lang_code WHERE h.id = :Id LIMIT 1";
        $params1[] = array(  "key" => ":Lang_code", "value" =>$lang); 
    }    
    $params1[] = array(  "key" => ":Id", "value" =>$hotelId);    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params1);
    $ret = $select->execute();
    return $select->fetch(PDO::FETCH_ASSOC);
}
function getHotelSearchRequestById($hotel_request_id,$limit=50) {
    global $dbConn;
    $params1 = array();
    $query = "SELECT * FROM hotel_search_response WHERE hotel_search_request_id = :Id LIMIT 0,$limit";
    $params1[] = array(  "key" => ":Id", "value" =>$hotel_request_id);    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params1);
    $ret = $select->execute();
    return $select->fetchAll(PDO::FETCH_ASSOC);
}
/**
 * delete image for given id
 * @param integer $user_id the user id
 * @param integer $entity_type the entity type
 * @param integer $id the record id
 * @return true | false 
 */
function userDiscoverImagesDelete($user_id, $entity_type ,$id){
//  Changed by Anthony Malak 20-04-2015 to PDO database
//  <start>
    global $dbConn; 
    $params = array();  
    $table ="";
    if($entity_type == SOCIAL_ENTITY_HOTEL){
        $table ="discover_hotels_images";
    }else if($entity_type == SOCIAL_ENTITY_RESTAURANT){
        $table ="discover_restaurants_images";
    }else if($entity_type == SOCIAL_ENTITY_LANDMARK){
        $table ="discover_poi_images";
    }else if($entity_type == SOCIAL_ENTITY_AIRPORT){
        $table ="airport_images";
    }
    if($table==""){
        return false;
    }else{
//        $query = "DELETE FROM ".$table." WHERE user_id=$user_id AND id=$id";  
//        $ret = db_query($query);
        $query = "DELETE FROM ".$table." WHERE user_id=:User_id AND id=:Id"; 
	$params[] = array(  "key" => ":User_id",
                            "value" =>$user_id);
	$params[] = array(  "key" => ":Id",
                            "value" =>$id);
	$delete = $dbConn->prepare($query);
	PDO_BIND_PARAM($delete,$params);
	$ret = $delete->execute();

        if( $ret ){                       
            return true;
        }else{
            return false;
        }
    }
}
function userCmsHotelImagesDelete( $id , $user_id ){
    global $dbConn; 
    $params = array(); 
    $query = "DELETE FROM cms_hotel_image WHERE user_id=:User_id AND id=:Id"; 
    $params[] = array(  "key" => ":User_id", "value" =>$user_id);
    $params[] = array(  "key" => ":Id", "value" =>$id);
    $delete = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete,$params);
    $ret = $delete->execute();
    if( $ret ){                       
	return true;
    }else{
	return false;
    }
}
/**
 * gets the reviews record for a given id 
 * @param integer $id the reviews id
 * @param integer $entity_type the entity type
 * @return array | false the reviews record or false if not found
 */
function userReviewsInfo($id,$entity_type){
//  Changed by Anthony Malak 20-04-2015 to PDO database
//  <start>
        global $dbConn;
        $userReviewsInfo = tt_global_get('userReviewsInfo');      //Added By Devendra
	$params = array();  
        if(isset($userReviewsInfo[$id][$entity_type]) && $userReviewsInfo[$id]!=''){
        return $userReviewsInfo[$id][$entity_type];
    }
        $table ="";
        if($entity_type == SOCIAL_ENTITY_HOTEL || $entity_type == SOCIAL_ENTITY_HOTEL_REVIEWS){
            $table ="discover_hotels_reviews";
        }else if($entity_type == SOCIAL_ENTITY_RESTAURANT || $entity_type == SOCIAL_ENTITY_RESTAURANT_REVIEWS){
//            $table ="discover_restaurants_reviews";
        }else if($entity_type == SOCIAL_ENTITY_LANDMARK || $entity_type == SOCIAL_ENTITY_LANDMARK_REVIEWS){
            $table ="discover_poi_reviews";
        }else if($entity_type == SOCIAL_ENTITY_AIRPORT || $entity_type == SOCIAL_ENTITY_AIRPORT_REVIEWS){
            $table ="airport_reviews";
        }
        if($table==""){
            return false;
        }else{
//            $query = "SELECT * FROM ".$table." WHERE id='$id' AND published=1";            
//            $ret = db_query($query);
            $query = "SELECT * FROM ".$table." WHERE id=:Id AND published=1";   
            $params[] = array(  "key" => ":Id", "value" =>$id);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res = $select->execute();

            $ret    = $select->rowCount(); 
            if($res && $ret!=0 ){
                $row = $select->fetch(PDO::FETCH_ASSOC);
                $userReviewsInfo[$id][$entity_type] =   $row;
                return $row;
            }else{
                $userReviewsInfo[$id][$entity_type] =   false;
                return false;
            }
        }
//  Changed by Anthony Malak 20-04-2015 to PDO database
//  <end>
}
/**
 * gets the reviews list for a given entity id 
 * @param integer $limit the maximum number of user records returned. default 10
 * @param integer $entity_id the entity id
 * @param integer $entity_type the entity type
 * @param boolean $n_results return records or number of results. default false.
 * @return array | false the reviews list or false if not found
 */
function userReviewsList($entity_id,$entity_type,$limit=6,$page=0,$n_results=false){
//  Changed by Anthony Malak 20-04-2015 to PDO database
//  <start>
    global $dbConn;
    $params  = array();
    $table ="";
    $entity_value ="";
    $start = $page*$limit;
    if($entity_type == SOCIAL_ENTITY_HOTEL){
        $table ="discover_hotels_reviews";
        $entity_value ="hotel_id";
    }else if($entity_type == SOCIAL_ENTITY_RESTAURANT){
//        $table ="discover_restaurants_reviews";
//        $entity_value ="restaurant_id";
    }else if($entity_type == SOCIAL_ENTITY_LANDMARK){
        $table ="discover_poi_reviews";
        $entity_value ="poi_id";
    }else if($entity_type == SOCIAL_ENTITY_AIRPORT){
        $table ="airport_reviews";
        $entity_value ="airport_id";
    }
    if($table==""){
        return false;
    }else{
        if($n_results==false){
//            $query = "SELECT * FROM ".$table." WHERE ".$entity_value." = $entity_id AND published=1 ORDER BY id DESC LIMIT ".$start.", ".$limit;
            $query = "SELECT * FROM ".$table." WHERE ".$entity_value." = :Entity_id AND published=1 ORDER BY id DESC LIMIT :Start, :Limit";
            $params[] = array(  "key" => ":Entity_id", "value" =>$entity_id);
            $params[] = array(  "key" => ":Start", "value" =>$start, "type" =>"::PARAM_INT");
            $params[] = array(  "key" => ":Limit", "value" =>$limit, "type" =>"::PARAM_INT");
        
//            $ret = db_query($query);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res = $select->execute();

            $ret    = $select->rowCount(); 
//            if($ret && db_num_rows($ret)!=0 ){
//                $reviews_array = array();
//                while ($row = db_fetch_array($ret)) {
//                    $reviews_array[] = $row;
//                }
//                return $reviews_array;
//            }else{
//                return false;
//            }
            if($res && $ret!=0 ){
                $reviews_array = $select->fetchAll();
                return $reviews_array;
            }else{
                return false;
            }  
        }else{
//            $query = "SELECT COUNT(id) FROM ".$table." WHERE ".$entity_value." = $entity_id";
//		
//            $ret = db_query($query);
//            $row = db_fetch_row($ret);
            $params  = array();
            $query = "SELECT COUNT(id) FROM ".$table." WHERE ".$entity_value." = :Entity_id AND published=1";
            $params[] = array(  "key" => ":Entity_id",
                                "value" =>$entity_id);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $select->execute();
            $row = $select->fetch();
            return $row[0];
        }        
    }
//  Changed by Anthony Malak 20-04-2015 to PDO database
//  <end>
}
/**
 * gets the hotel record for a given id 
 * @param integer $id the hotel id
 * @return array | false the hotel record or false if not found
 */
function getHotelInfo($id){    
    global $dbConn;  
    $getHotelInfo = tt_global_get('getHotelInfo');      //Added By Devendra
    $params = array();  
    if(isset($getHotelInfo[$id]) && $getHotelInfo[$id]!=''){
    return $getHotelInfo[$id];
    }
    $query = "SELECT h.*,t.title as title_type  FROM `discover_hotels` as h INNER JOIN discover_hotels_type as t ON t.id=h.propertyType WHERE h.id = :Id LIMIT 1";
    $params[] = array(  "key" => ":Id", "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();
    if($res && $ret!=0 ){
	$row = $select->fetch(PDO::FETCH_ASSOC);
        $getHotelInfo[$id] =   $row;
        return $row;
    }else{
        $getHotelInfo[$id] =   false;
        return false;
    }
        
}
/**
 * gets the hotel features record for a given hotel id 
 * @param integer $id the hotel id
 * @return array | false the hotel features record or false if not found
 */
function getHotelFeatures($id){
//  Changed by Anthony Malak 20-04-2015 to PDO database
//  <start>
    global $dbConn;
    $params = array(); 
//    $query = "SELECT fh.*, f.title as ftitle, t.title as ttitle, f.feature_type FROM discover_hotels_feature_to_hotel AS fh INNER JOIN discover_hotels_feature AS f ON f.id = fh.hotel_feature_id INNER JOIN discover_hotels_feature_type AS t ON t.id = f.feature_type WHERE fh.hotel_id =$id ORDER BY f.title ASC";
//
//    $ret = db_query($query);
//    if($ret && db_num_rows($ret)!=0 ){
//        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
//        return $ret_arr;
//    }else{
//        return false;
//    }
    $query = "SELECT fh.*, f.title as ftitle, t.title as ttitle, f.feature_type FROM discover_hotels_feature_to_hotel AS fh INNER JOIN discover_hotels_feature AS f ON f.id = fh.hotel_feature_id INNER JOIN discover_hotels_feature_type AS t ON t.id = f.feature_type WHERE fh.hotel_id =:Id ORDER BY f.title ASC";

    $params[] = array(  "key" => ":Id", "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();
    if($res && $ret!=0 ){
        $ret_arr = $select->fetchAll();
        return $ret_arr;
    }else{
        return false;
    }
//  Changed by Anthony Malak 20-04-2015 to PDO database
//  <end>
        
}

/**
 * gets the restaurant record for a given id 
 * @param integer $id the restaurant id
 * @return array | false the restaurant record or false if not found
 */
function getRestaurantInfo($id){   
    return false;
    global $dbConn;
    $params = array(); 
    $getRestaurantInfo = tt_global_get('getRestaurantInfo');
        if(isset($getRestaurantInfo[$id]) && $getRestaurantInfo[$id]!='')
             return $getRestaurantInfo[$id];
// Query change by Devendra
    $query = "SELECT * FROM `global_restaurants` WHERE id = :Id LIMIT 1";
    $params[] = array(  "key" => ":Id", "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();
    if($res && $ret!=0 ){
	$row = $select->fetch(PDO::FETCH_ASSOC);
            $getRestaurantInfo[$id] =   $row;
        
        return $row;
    }else{
        $getRestaurantInfo[$id] =   false;
        return false;
    }
//  Changed by Anthony Malak 20-04-2015 to PDO database
//  <end> 
}
/**
 * gets the poi record for a given id 
 * @param integer $id the poi id
 * @return array | false the poi record or false if not found
 */
function getPoiInfo($id){      
//  Changed by Anthony Malak 20-04-2015 to PDO database
//  <start>
	global $dbConn;
        $getPoiInfo = tt_global_get('getPoiInfo');
	$params = array(); 
        // Added by Devendra      
        if(isset($getPoiInfo[$id]) && $getPoiInfo[$id]!='')
             return $getPoiInfo[$id];
//Added By Devendra
//    $query = "SELECT * FROM `discover_poi` WHERE id = :Id LIMIT 1";  //Added By Devendra
    $query = "SELECT `id`, `longitude`, `latitude`, `name`, `stars`, `country`, `city`, `zoom_order`, `show_on_map`, `cat`, `sub_cat`, `map_image`, `city_id`, `zipcode`, `phone`, `fax`, `email`, `website`, `price`, `description`, `published`, `last_modified`, `address` FROM `discover_poi` WHERE id = :Id LIMIT 1";
    $params[] = array(  "key" => ":Id", "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();
    if($res && $ret!=0 ){
        $row = $select->fetch(PDO::FETCH_ASSOC);
            $getPoiInfo[$id] =   $row;
            return $row;
    }else{
        $getPoiInfo[$id] =   false;
        return false;
    }
//  Changed by Anthony Malak 20-04-2015 to PDO database
//  <end>
        
}
/**
 * gets the Airport record for a given id 
 * @param integer $id the Airport id
 * @return array | false the Airport record or false if not found
 */
function getAirportInfo($id){       
//  Changed by Anthony Malak 08-05-2015 to PDO database
//  <start>
    global $dbConn;
    $params = array();  
    $getAirportInfo = tt_global_get('getAirportInfo');
    // Added by Devendra      
        if(isset($getAirportInfo[$id]) && $getAirportInfo[$id]!='')
             return $getAirportInfo[$id];
    //$query = "SELECT * FROM `airport` WHERE id = :Id LIMIT 1";  //hide by devendra
    $query = "SELECT `id`, `name`, `airport_code`, `world_area_code`, `country`, `runway_length`, `runway_elevation`, `city`, `zoom_order`, `show_on_map`, `state_code`, `longitude`, `latitude`, `gmt_offset`, `telephone`, `fax`, `website`, `email`, `stars`, `city_id`, map_image, `published`, `last_modified` FROM `airport` WHERE id = :Id LIMIT 1";  //Added by Devendra
    $params[] = array(  "key" => ":Id", "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if($res && $ret!=0 ){
        $row = $select->fetch(PDO::FETCH_ASSOC);
            $getAirportInfo[$id] =   $row;
        
        return $row;
    }else{
        $getAirportInfo[$id] =   false;
            return false;
    }
//  Changed by Anthony Malak 08-05-2015 to PDO database
//  <end> 
}

/**
 * gets the reviews entity list (restaurant or hotel or poi ) for a given user id 
 * @param integer $user_id the user id, 
 * @param integer $entity_type the entity type
 * @return string comma separated | empty the reviews entity list or empty if not found
 */
function userReviewsEntityList($user_id,$entity_type){
//  Changed by Anthony Malak 20-04-2015 to PDO database
//  <start>
    global $dbConn;
	$params = array();  
    $table ="";
    $entity_value ="";
    if($entity_type == SOCIAL_ENTITY_HOTEL){
        $table ="discover_hotels_reviews";
        $entity_value ="hotel_id";
    }else if($entity_type == SOCIAL_ENTITY_RESTAURANT){
//        $table ="discover_restaurants_reviews";
//        $entity_value ="restaurant_id";
    }else if($entity_type == SOCIAL_ENTITY_LANDMARK){
        $table ="discover_poi_reviews";
        $entity_value ="poi_id";
    }else if($entity_type == SOCIAL_ENTITY_AIRPORT){
        $table ="airport_reviews";
        $entity_value ="airport_id";
    }
    if($table==""){
        return false;
    }else{        
//        $query = "SELECT GROUP_CONCAT( DISTINCT ".$entity_value." SEPARATOR ',' ) AS entity_list FROM ".$table." WHERE published =1 AND user_id=$user_id";
//        $ret = db_query($query);
        $query = "SELECT GROUP_CONCAT( DISTINCT ".$entity_value." SEPARATOR ',' ) AS entity_list FROM ".$table." WHERE published =1 AND user_id=:User_id";
	$params[] = array(  "key" => ":User_id", "value" =>$user_id);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res = $select->execute();

	$ret    = $select->rowCount();
        if($res && $ret!=0 ){
            $row = $select->fetch(PDO::FETCH_ASSOC);
            return $row['entity_list'];
        }else{               
            return ''; 
        }
    }
//  Changed by Anthony Malak 20-04-2015 to PDO database
//  <end>
}

/**
 * gets the discover Entity visited list for a given user and city id
 * @param integer $user_id the user id, 
 * @param integer $city_id the city id,
 * @return array | false the discover_restaurants record or null if not found
 */
function discoverEntityVisitedPlacesList($user_id,$city_id=0){
    global $dbConn;
    $params  = array(); 
    $params2 = array(); 
    $params3 = array(); 
    $params4 = array(); 
    
    $city_array = worldcitiespopInfo($city_id);
    $diff_angle=0.01;
    
    $longitude = $city_array['longitude'];
    $latitude = $city_array['latitude'];
    $longitude_search0 = $longitude - $diff_angle;
    $longitude_search1 = $longitude + $diff_angle;
    $latitude_search0 = $latitude - $diff_angle;
    $latitude_search1 = $latitude + $diff_angle;

    $ret_arr = array();

//    $query = "SELECT V.id, V.name, '0' as stars, R.create_ts,I.default_pic, R.description, R.title, I.filename AS img, avg(RA.rating_value) as rating_avg FROM discover_restaurants_reviews R INNER JOIN global_restaurants V ON V.id = R.restaurant_id LEFT JOIN discover_restaurants_images as I on I.restaurant_id=V.id LEFT JOIN cms_social_ratings as RA on RA.entity_id=V.id AND RA.entity_type=".SOCIAL_ENTITY_RESTAURANT." AND RA.rate_type=0 JOIN (SELECT restaurant_id, MAX(id) as max_id FROM discover_restaurants_reviews WHERE user_id=:User_id GROUP BY restaurant_id) R1 ON R.id = R1.max_id WHERE R.user_id = :User_id";
//    $params[] = array(  "key" => ":User_id", "value" =>$user_id);
//    if($city_id!=0){
//        $query .=" AND V.longitude BETWEEN :Longitude_search0 AND :Longitude_search1 AND V.latitude BETWEEN :Latitude_search0 AND :Latitude_search1";
//        $params[] = array(  "key" => ":Longitude_search0",
//                            "value" =>$longitude_search0);
//        $params[] = array(  "key" => ":Longitude_search1",
//                            "value" =>$longitude_search1);
//        $params[] = array(  "key" => ":Latitude_search0",
//                            "value" =>$latitude_search0);
//        $params[] = array(  "key" => ":Latitude_search1",
//                            "value" =>$latitude_search1);
//    }
//    $query .=" GROUP BY V.id ORDER BY I.default_pic DESC";
//    $select = $dbConn->prepare($query);
//    PDO_BIND_PARAM($select,$params);
//    $res    = $select->execute();
//
//    $ret    = $select->rowCount();
//    $ret_arr['restaurant'] = array();
//    if(!$res || ($ret == 0) ){
//
//    }else{
//	$row = $select->fetchAll();
//        foreach($row as $row_item){
//            $ret_arr['restaurant'][] = $row_item;
//        }
//    }
    
    $query2 = "SELECT V.id, V.hotelName AS name, V.stars,I.default_pic, R.create_ts, R.description, R.title, I.filename AS img, avg(RA.rating_value) as rating_avg FROM discover_hotels_reviews R INNER JOIN discover_hotels AS V ON V.id = R.hotel_id LEFT JOIN discover_hotels_images as I on I.hotel_id=V.id LEFT JOIN cms_social_ratings as RA on RA.entity_id=V.id AND RA.entity_type=".SOCIAL_ENTITY_HOTEL." AND RA.rate_type=0 JOIN (SELECT hotel_id, MAX(id) as max_id FROM discover_hotels_reviews WHERE user_id=:User_id
	  GROUP BY hotel_id) R1 ON R.id = R1.max_id WHERE R.user_id = :User_id";
    $params2[] = array(  "key" => ":User_id",
                         "value" =>$user_id);
    
    if($city_id!=0){
//        $query .=" AND V.city_id='$city_id'";
        $query2 .=" AND V.city_id=:City_id";
        $params2[] = array(  "key" => ":City_id",
                             "value" =>$city_id);
    }
    $query2 .=" GROUP BY V.id ORDER BY I.default_pic DESC";
//    $ret = db_query($query);
    $select2 = $dbConn->prepare($query2);
    PDO_BIND_PARAM($select2,$params2);
    $res     = $select2->execute();

    $ret     = $select2->rowCount();
    $ret_arr['hotel'] = array();
//    if(!$ret || (db_num_rows($ret) == 0) ){
    if(!$res || ($ret == 0) ){
       
    }else{
//        while($row = db_fetch_array($ret)){
	$row = $select2->fetchAll();
        foreach($row as $row_item){
           $ret_arr['hotel'][] = $row_item;
        }
    }
    $query3 = "SELECT V.id, V.name, V.stars, R.create_ts,I.default_pic, R.description, R.title, I.filename AS img, avg(RA.rating_value) as rating_avg FROM discover_poi_reviews R INNER JOIN discover_poi AS V ON V.id = R.poi_id LEFT JOIN discover_poi_images as I on I.poi_id=V.id LEFT JOIN cms_social_ratings as RA on RA.entity_id=V.id AND RA.entity_type=".SOCIAL_ENTITY_LANDMARK." AND RA.rate_type=0 JOIN (SELECT poi_id, MAX(id) as max_id FROM discover_poi_reviews WHERE user_id=:User_id
	  GROUP BY poi_id) R1 ON R.id = R1.max_id WHERE R.user_id = :User_id";
    $params3[] = array(  "key" => ":User_id",
                         "value" =>$user_id);
    if($city_id!=0){
//        $query .=" AND V.city_id='$city_id'";
        $query3 .=" AND V.city_id=:City_id";
        $params3[] = array(  "key" => ":City_id",
                             "value" =>$city_id);
    }
    $query3 .=" GROUP BY V.id ORDER BY I.default_pic DESC";
    
//    $ret = db_query($query);
    $select = $dbConn->prepare($query3);
    PDO_BIND_PARAM($select,$params3);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    $ret_arr['poi'] = array();
//    if(!$ret || (db_num_rows($ret) == 0) ){
    if(!$res || ($ret == 0) ){
       
    }else{
//        while($row = db_fetch_array($ret)){
	$row = $select->fetchAll();
        foreach($row as $row_item){
            $ret_arr['poi'][] = $row_item;
        }
    }
    $query = "SELECT V.id, V.name, V.stars, R.create_ts,I.default_pic, R.description, R.title, I.filename AS img, avg(RA.rating_value) as rating_avg FROM airport_reviews R INNER JOIN airport AS V ON V.id = R.airport_id LEFT JOIN airport_images as I on I.airport_id=V.id LEFT JOIN cms_social_ratings as RA on RA.entity_id=V.id AND RA.entity_type=".SOCIAL_ENTITY_AIRPORT." AND RA.rate_type=0 JOIN (SELECT airport_id, MAX(id) as max_id FROM airport_reviews WHERE user_id=:User_id
	  GROUP BY airport_id) R1 ON R.id = R1.max_id WHERE R.user_id = :User_id";
    $params4[] = array(  "key" => ":User_id",
                         "value" =>$user_id);
    if($city_id!=0){
//        $query .=" AND V.city_id='$city_id'";
        $query .=" AND V.city_id=:City_id";
    $params4[] = array(  "key" => ":City_id",
                         "value" =>$city_id);
    }
    $query .=" GROUP BY V.id ORDER BY I.default_pic DESC";
//    $ret = db_query($query);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params4);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    $ret_arr['airport'] = array();
//    if(!$ret || (db_num_rows($ret) == 0) ){
    if(!$res || ($ret == 0) ){
       
    }else{
//        while($row = db_fetch_array($ret)){
	$row = $select->fetchAll();
        foreach($row as $row_item){
            $ret_arr['airport'][] = $row_item;
        }
    }
    return $ret_arr;
}
/**
 * add reviews entity for a given user 
 * @param integer $user_id the user id
 * @param integer $entity_type the entity type
 * @param integer $entity_id the entity id
 * @param string $title the reviews title * 
 * @param string $description the reviews description
 * @param string $date the travel date
 * @param string $near the near place
 * @param string $themes the trip theme
 * @return integer | false the reviews id or false if not inserted
 */
function userReviewsExtraAdd($user_id,$entity_type,$entity_id,$title,$description , $date , $near , $themes , $hideUser=0 ){
    global $dbConn;
    $params  = array(); 
    $table ="";
    $entity_value ="";
    $review_type ="";
    if($entity_type == SOCIAL_ENTITY_HOTEL){
        $table ="discover_hotels_reviews";
        $entity_value ="hotel_id";
        $review_type =SOCIAL_ENTITY_HOTEL_REVIEWS;
    }else if($entity_type == SOCIAL_ENTITY_RESTAURANT){
//        $table ="discover_restaurants_reviews";
//        $entity_value ="restaurant_id";
//        $review_type =SOCIAL_ENTITY_RESTAURANT_REVIEWS;
    }else if($entity_type == SOCIAL_ENTITY_LANDMARK){
        $table ="discover_poi_reviews";
        $entity_value ="poi_id";
        $review_type =SOCIAL_ENTITY_LANDMARK_REVIEWS;
    }else if($entity_type == SOCIAL_ENTITY_AIRPORT){
        $table ="airport_reviews";
        $entity_value ="airport_id";
        $review_type =SOCIAL_ENTITY_AIRPORT_REVIEWS;
    }
    if($table==""){
        return false;
    }else{
        $query = "INSERT INTO ".$table." (user_id,".$entity_value.",title,description,hide_user) VALUES (:User_id,:Entity_id,:Title,:Description,:HideUser)";
	$params[] = array(  "key" => ":User_id", "value" =>$user_id);
	$params[] = array(  "key" => ":Entity_id", "value" =>$entity_id);
	$params[] = array(  "key" => ":Title", "value" =>$title);
	$params[] = array(  "key" => ":Description", "value" =>$description);
	$params[] = array(  "key" => ":HideUser", "value" =>$hideUser);
	$insert = $dbConn->prepare($query);
	PDO_BIND_PARAM($insert,$params);
	$res    = $insert->execute();
        if( $res ){
            $rev_id = $dbConn->lastInsertId();
            newsfeedAdd($user_id, $rev_id , SOCIAL_ACTION_UPLOAD , $rev_id , $review_type , USER_PRIVACY_PUBLIC, null);
            if( $date!='' || $near!='' || $themes!='' ){
                $query1 = "INSERT INTO discover_reviews_extra (user_id,entity_id,entity_type,date,near,theme) VALUES (:User_id,:Entity_id,:Entity_type,:Dates,:Near,:Themes)";
                $params = array();
                $params[] = array(  "key" => ":User_id", "value" =>$user_id);
                $params[] = array(  "key" => ":Entity_id", "value" =>$entity_id);
                $params[] = array(  "key" => ":Entity_type", "value" =>$entity_type);
                $params[] = array(  "key" => ":Dates", "value" =>$date);
                $params[] = array(  "key" => ":Near", "value" =>$near);
                $params[] = array(  "key" => ":Themes", "value" =>$themes);
                $insert = $dbConn->prepare($query1);
                PDO_BIND_PARAM($insert,$params);
                $insert->execute();
            }
            return $rev_id;
        }else{
            return false;
        }
    }
}
function userCmsHotelReviewsAdd($user_id,$entity_id,$description ){
    global $dbConn;
    $params  = array();
    $query = "INSERT INTO cms_hotel_reviews (user_id,hotel_id,description) VALUES (:User_id,:Entity_id,:Description)";
    $params[] = array(  "key" => ":User_id", "value" =>$user_id);
    $params[] = array(  "key" => ":Entity_id", "value" =>$entity_id);
    $params[] = array(  "key" => ":Description", "value" =>$description);
    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params);
    $res    = $insert->execute();
    if( $res ){
        $rev_id = $dbConn->lastInsertId();        
        return $rev_id;
    }else{
        return false;
    }
}
function userReviewsExtraDelete($user_id, $entity_type ,$id){
    global $dbConn;
    $params = array();  
    $table ="";
    if($entity_type == SOCIAL_ENTITY_HOTEL){
        $table ="discover_hotels_reviews";
        $entity_typeRV = SOCIAL_ENTITY_HOTEL_REVIEWS;
    }else if($entity_type == SOCIAL_ENTITY_RESTAURANT){
//        $table ="discover_restaurants_reviews";
//        $entity_typeRV = SOCIAL_ENTITY_RESTAURANT_REVIEWS;
    }else if($entity_type == SOCIAL_ENTITY_LANDMARK){
        $table ="discover_poi_reviews";
        $entity_typeRV = SOCIAL_ENTITY_LANDMARK_REVIEWS;
    }else if($entity_type == SOCIAL_ENTITY_AIRPORT){
        $table ="airport_reviews";
        $entity_typeRV = SOCIAL_ENTITY_AIRPORT_REVIEWS;
    }
    if($table==""){
        return false;
    }else{
        if( deleteMode() == TT_DEL_MODE_PURGE ){//           
            $query = "DELETE FROM $table where user_id=:User_id AND id=:Id";
	}else if( deleteMode() == TT_DEL_MODE_FLAG ){
            $query = "UPDATE $table SET published=".TT_DEL_MODE_FLAG." WHERE user_id=:User_id AND id=:Id";
	}
	$params[] = array(  "key" => ":User_id", "value" =>$user_id);
	$params[] = array(  "key" => ":Id", "value" =>$id);
        
	newsfeedDeleteAll($id, $entity_typeRV);
	
	//delete comments
	socialCommentsDelete($id, $entity_typeRV);
	
	//delete likes
	socialLikesDelete($id, $entity_typeRV);
	
	//delete shares
	socialSharesDelete($id, $entity_typeRV);	
	
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
	return $res;
    }
//  Changed by Anthony Malak 20-04-2015 to PDO database
//  <end>
}
/**
 * Gets cuisine list
 * @return array
 */
function getCuisine() {
//  Changed by Anthony Malak 20-04-2015 to PDO database
//  <start>
    global $dbConn;
    $params = array(); 
//    $lang_code = LanguageGet();
    $languageSel = '';
    $languageWhere = '';
    $languageJoin = '';
    if ($lang_code != 'en') {
        $languageSel = ',ml.title as ml_title';
//        $languageWhere= " Where ml.lang_code = '$lang_code'";
        $languageWhere= " Where ml.lang_code = :Lang_code";
        $languageJoin = ' INNER JOIN ml_discover_cuisine ml on c.id = ml.entity_id ';
	$params[] = array(  "key" => ":Lang_code",
                            "value" =>$lang_code);
    }
    $query = "SELECT c.id as id,c.title as title$languageSel FROM `discover_cuisine` as c $languageJoin $languageWhere  ORDER by c.title";
//    exit($query);
//    $ret = db_query($query);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();
    
    $ret    = $select->rowCount();
    
    if ($res && $ret != 0) {
        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            if ($lang_code == 'en') {
//                $ret1 = $row;
//            } else {
//                $ret1['id'] = $row['id'];
//                $ret1['title'] = htmlEntityDecode($row['ml_title']);
//            }
//            $ret_arr[] = $ret1;
//        }
	$row = $select->fetchAll();
        foreach($row as $row_item){
            if ($lang_code == 'en') {
                $ret1 = $row_item;
            } else {
                $ret1['id'] = $row_item['id'];
                $ret1['title'] = htmlEntityDecode($row_item['ml_title']);
            }
            $ret_arr[] = $ret1;
        }
        return $ret_arr;
    } else {
        return array();
    }
//  Changed by Anthony Malak 20-04-2015 to PDO database
//  <end>
}
function returnHotelReviewLink($id,$title){
    $titled = cleanTitle($title);
//    return ReturnLink('hotel-review/id/'.$id.$titled);
    return ReturnLink($titled.'-review-H'.$id);
    
}
function returnRestaurantReviewLink($id,$title){
    $titled = cleanTitle($title);
//    return ReturnLink('restaurant-review/id/'.$id.$titled);
    return ReturnLink($titled.'-review-R'.$id,null,0,'restaurants');
}
function returnThingstodoReviewLink($id,$title){
    $titled = cleanTitle($title);
//    return ReturnLink('things2do-review/id/'.$id.$titled);
    return ReturnLink($titled.'-review-T'.$id);
}
function returnAirportReviewLink($id,$title){
    $titled = cleanTitle($title);
//    return ReturnLink('airport-review/id/'.$id.$titled);
    return ReturnLink($titled.'-review-A'.$id);
}
function returnTopThingstodoLink($id){
    $aliasInfo = aliasContentInfo($id);
    if($aliasInfo){
        return ReturnLink($aliasInfo['alias']);
    }else{
        return '';
    }
}
//function returnRestaurantCuisineLink($cuisine,$search,$letter,$page){
//    $url = '/Restaurants-';
//    if($cuisine){
//        $url .= $cuisine;
//    }
//    if($search){
//        $url .= '-'.$search.'-';
//    }
//    if($letter){
//        if( !$search && (!$cuisine || $cuisine ) ){
//            $url .= '--';
//        }
//        $url .= $letter;
//    }
//    if($page){
//        if(!$letter){
//            $url .= '-';
//        }
//        $url .= '_'.$page;
//    }
//    $url .= '_C1';
//    
//    return ReturnLink($url);
//}
function returnRestaurantCuisineLink($cuisine,$search,$letter,$page){
    $url = 'Restaurants-'.$cuisine.'-'.$search.'-'.$letter.'_'.$page.'_C1';
    return ReturnLink($url,null,0,'restaurants');
}
function getHotelImages($hotelId, $limit = null) {
    global $dbConn;
    $params  = array();
    $query_hotels = "SELECT hi.hotel_id, hi.filename as image_source, hi.location as imageLocation FROM `cms_hotel_image` as hi WHERE hi.hotel_id = :hotelId order by hi.default_pic DESC LIMIT 1";
    $params[] = array( "key" => ":hotelId", "value" =>$hotelId);
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();
    $ret = $select->rowCount();
    if( $res && $ret>0){
        $media_hotels =  $select->fetch();    
        return $media_hotels;     
    }else{
        return array();
    }
}
function getHotelDefaultPic($txt_id){
//  Changed by Anthony Malak 20-04-2015 to PDO database
//  <start>
	global $dbConn;
	$params  = array();  
	$params2 = array();  
//    $query_hotels = "SELECT id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = $txt_id and default_pic=1 DESC LIMIT 1";
//    $ret_hotels = db_query($query_hotels);
//    if( !$ret_hotels || db_num_rows($ret_hotels)==0){
//        $query_hotels = "SELECT id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = $txt_id ORDER BY RAND() DESC LIMIT 1";
//        $ret_hotels = db_query($query_hotels); 
//    }
//    if( $ret_hotels && db_num_rows($ret_hotels)>0){
//        $media_hotels = db_fetch_array($ret_hotels);   
//        return $media_hotels;     
//    }else{
//        return false;
//    }
    $query_hotels = "SELECT id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = :Txt_id and default_pic=1 DESC LIMIT 1";
    $params[] = array(  "key" => ":Txt_id",
                        "value" =>$txt_id);
    
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();
    if( !$res|| $ret==0){
        $query_hotels = "SELECT id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = :Txt_id ORDER BY id ASC LIMIT 1";
        $params2[] = array(  "key" => ":Txt_id",
                             "value" =>$txt_id);
	$select = $dbConn->prepare($query_hotels);
	PDO_BIND_PARAM($select,$params2);
        $res = $select->execute();

        $ret    = $select->rowCount();
    }
    if( $res && $ret>0){
        $media_hotels =  $select->fetch();    
        return $media_hotels;     
    }else{
        return false;
    }
//  Changed by Anthony Malak 20-04-2015 to PDO database
//  <end>
}
function getRestaurantDefaultPic($txt_id){
//  Changed by Anthony Malak 20-04-2015 to PDO database
//  <start>
	global $dbConn;
	$params  = array(); 
	$params2 = array(); 
//    $query_hotels = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = $txt_id and default_pic=1 DESC LIMIT 1";
//    $ret_hotels = db_query($query_hotels);
//    if( !$ret_hotels || db_num_rows($ret_hotels)==0){
//        $query_hotels = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = $txt_id ORDER BY RAND() DESC LIMIT 1";
//        $ret_hotels = db_query($query_hotels); 
//    }
//    if( $ret_hotels && db_num_rows($ret_hotels)>0){
//        $media_hotels = db_fetch_array($ret_hotels);   
//        return $media_hotels;     
//    }else{
//        return false;
//    }
    $query_hotels = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = :Txt_id and default_pic=1 DESC LIMIT 1";
    $params[] = array(  "key" => ":Txt_id",
                        "value" =>$txt_id);

    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();
    if( !$res || $ret==0){
        $query_hotels = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = :Txt_id ORDER BY id ASC LIMIT 1";
        $params2[] = array(  "key" => ":Txt_id",
                             "value" =>$txt_id);

	$select = $dbConn->prepare($query_hotels);
	PDO_BIND_PARAM($select,$params2);
        $res = $select->execute();

        $ret    = $select->rowCount();
    }
    if( $res && $ret>0){
        $media_hotels = $select->fetch();  
        return $media_hotels;     
    }else{
        return false;
    }
//  Changed by Anthony Malak 20-04-2015 to PDO database
//  <end>
}
function getPOIDefaultPic($txt_id){
//  Changed by Anthony Malak 20-04-2015 to PDO database
//  <start>
	global $dbConn;
	$params  = array(); 
	$params2 = array(); 
//    $query_hotels = "SELECT id , filename as img , poi_id FROM `discover_poi_images` WHERE poi_id = $txt_id and default_pic=1 DESC LIMIT 1";
//    $ret_hotels = db_query($query_hotels);
//    if( !$ret_hotels || db_num_rows($ret_hotels)==0){
//        $query_hotels = "SELECT id , filename as img , poi_id FROM `discover_poi_images` WHERE poi_id = $txt_id ORDER BY RAND() DESC LIMIT 1";
//        $ret_hotels = db_query($query_hotels); 
//    }
//    if( $ret_hotels && db_num_rows($ret_hotels)>0){
//        $media_hotels = db_fetch_array($ret_hotels);   
//        return $media_hotels;     
//    }else{
//        return false;
//    }
    $query_hotels = "SELECT id , filename as img , poi_id FROM `discover_poi_images` WHERE poi_id = :Txt_id and default_pic=1 DESC LIMIT 1";
    $params[] = array(  "key" => ":Txt_id",
                        "value" =>$txt_id);
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();
    if( !$res || $ret==0){
        $query_hotels = "SELECT id , filename as img , poi_id FROM `discover_poi_images` WHERE poi_id = :Txt_id ORDER BY id ASC LIMIT 1";
        $params2[] = array(  "key" => ":Txt_id",
                             "value" =>$txt_id);
        $select = $dbConn->prepare($query_hotels);
        PDO_BIND_PARAM($select,$params2);
        $res = $select->execute();

        $ret    = $select->rowCount();
    }
    if( $res && $ret>0){
        $media_hotels = $select->fetch();   
        return $media_hotels;     
    }else{
        return false;
    }
//  Changed by Anthony Malak 20-04-2015 to PDO database
//  <end>
}
function getAirportDefaultPic($txt_id){
//  Changed by Anthony Malak 08-05-2015 to PDO database
//  <start>
    global $dbConn;
    $params  = array();  
    $params2 = array();  
//    $query_hotels = "SELECT id , filename as img , airport_id FROM `airport_images` WHERE airport_id = $txt_id and default_pic=1 DESC LIMIT 1";
    $query_hotels = "SELECT id , filename as img , airport_id FROM `airport_images` WHERE airport_id = :Txt_id and default_pic=1 DESC LIMIT 1";
    $params[] = array(  "key" => ":Txt_id",
                        "value" =>$txt_id);
//    $ret_hotels = db_query($query_hotels);
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params);
    $res_hotels    = $select->execute();

    $ret_hotels    = $select->rowCount();
//    if( !$ret_hotels || db_num_rows($ret_hotels)==0){
    if( !$res_hotels || $ret_hotels==0){
//        $query_hotels = "SELECT id , filename as img , airport_id FROM `airport_images` WHERE airport_id = $txt_id ORDER BY RAND() DESC LIMIT 1";
        $query_hotels = "SELECT id , filename as img , airport_id FROM `airport_images` WHERE airport_id = :Txt_id ORDER BY id ASC LIMIT 1";
        $params2[] = array(  "key" => ":Txt_id",
                             "value" =>$txt_id);
//        $ret_hotels = db_query($query_hotels); 
        $select = $dbConn->prepare($query_hotels);
        PDO_BIND_PARAM($select,$params2);
        $res_hotels    = $select->execute();

        $ret_hotels    = $select->rowCount();
    }
//    if( $ret_hotels && db_num_rows($ret_hotels)>0){
    if( $res_hotels && $ret_hotels>0){
//        $media_hotels = db_fetch_array($ret_hotels);   
        $media_hotels = $select->fetch();   
        return $media_hotels;     
    }else{
        return false;
    }
//  Changed by Anthony Malak 08-05-2015 to PDO database
//  <end>
}
/**
 * get list of things to do regions
 * @return array | false
 */
function getThingstodoRegionList($lang) {
    global $dbConn;
    $params  = array();
    
    if($lang == 'en'){
        $query = "SELECT * FROM cms_thingstodo_region WHERE published = 1";
        $select = $dbConn->prepare($query);
    }
    else{
        $query = "SELECT r.*, mlr.title as ml_title, mlr.description as ml_description FROM cms_thingstodo_region r "
                . "INNER JOIN ml_thingstodo_region mlr ON r.id = mlr.parent_id AND mlr.language = :Lang "
                . "WHERE r.published = 1";
        $params[] = array( "key" => ":Lang",
                           "value" => $lang);
        $select = $dbConn->prepare($query);
        PDO_BIND_PARAM($select, $params);
    }
    $res    = $select->execute();
    $ret    = $select->rowCount();
    if ($res && $ret > 0) {
        $row = $select->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }else{        
        return array();
    }
}

/**
 * get list of things to do countries
 * @return array | false
 */
function getThingstodoCountryList($region_id, $lang, $page = 0, $limit = 10) {
    global $dbConn;
    $params  = array();
    $skip = intval($page) * $limit;
    if($lang == 'en'){
        $query = "SELECT * FROM cms_thingstodo_country c "
                . "WHERE c.published = 1 AND c.parent_id = :Region_id"
                . " LIMIT $skip, $limit";
    }
    else{
        $query = "SELECT c.*, mlc.title as ml_title, mlc.description as ml_description FROM cms_thingstodo_country c "
                . "INNER JOIN ml_thingstodo_country mlc ON c.id = mlc.parent_id AND mlc.language = :Lang "
                . "WHERE c.published = 1 AND c.parent_id = :Region_id"
                . " LIMIT $skip, $limit";
        $params[] = array( "key" => ":Lang",
                           "value" => $lang);
    }
    $params[] = array( "key" => ":Region_id",
                        "value" => $region_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select, $params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    if ($res && $ret > 0) {
        $row = $select->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }else{        
        return array();
    }
}

/**
 * get list of things to do list
 * @return array | false
 */
function getThingstodoList($lang) {
    global $dbConn;
    $params  = array();
    $query = "SELECT * FROM cms_thingstodo WHERE published = 1 AND language=:Lang ORDER BY order_display DESC";
    $params[] = array(  "key" => ":Lang",
                        "value" =>$lang);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    if ($res && $ret > 0) {
        $row = $select->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }else{        
        return array();
    }
}
/**
 * gets the things to do for a given id 
 * @param integer $id the things to do id
 * @return array | false the cms_thingstodo record or false if not found
 */
function getThingstodoInfo($id) {
    global $dbConn;
    $getThingstodoInfo = tt_global_get('getThingstodoInfo');
    $params = array();
    if(isset($getThingstodoInfo[$id]) && $getThingstodoInfo[$id]!=''){
        return $getThingstodoInfo[$id];
    }
    $query = "SELECT * FROM `cms_thingstodo` WHERE id=:Id AND published=1";
    $params[] = array(  "key" => ":Id", "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        $getThingstodoInfo[$id] =   $row;
        return $row;
    } else {
        $getThingstodoInfo[$id] =   false;
        return false;
    }
}

function getThingstodoDivisions( $ttdId ) {
    global $dbConn;
    $params = array();
    
    $query = "SELECT td.id as td_id, td.division_category_id as division_category_id, td.parent_id as parent_id"
        . " FROM `thingstodo_division` td"
        . " LEFT JOIN thingstodo_division tds ON tds.parent_id = td.id AND td.parent_id IS NOT NULL"
        . " INNER JOIN thingstodo_division_category tdc ON tdc.id = td.division_category_id"
        . " WHERE td.ttd_id=:TTd_id ORDER BY td.id ASC";
    $params[] = array(  "key" => ":TTd_id", "value" =>$ttdId);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$select || ($ret == 0)) {
        return array();
    } else {
        return $select->fetchAll(PDO::FETCH_ASSOC);
    }
}
/**
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>id</b>: things to do id<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of tabl default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>search_string</b>: the search string. default null<br/>
 * <b>escape_id</b>: escape things to do id related.<br/>
 * @param array $srch_options 
 * @return array | false an array of 'cms_thingstodo' records or false if none found.
 */
function thingstodoSearch($srch_options) {
    global $dbConn;
    $params = array();  
    $default_opts = array(
        'limit' => 100,
        'page' => 0,
        'id' => null,
        'escape_id' => -1,
        'search_string' => null,
        'orderby' => 'id',
        'order' => 'a',
        'skip' => 0,
        'n_results' => false,
        'lang' => 'en',
        'parent_id' => null
    );

    $options = array_merge($default_opts, $srch_options);

    $where = '';

    if (!is_null($options['id'])) {
        if ($where != '') $where .= ' AND ';
        $where .= " t.id=:Id ";
        $params[] = array(  "key" => ":Id", "value" =>$options['id']);
    }
    if (!is_null($options['parent_id'])) {
        if ($where != '') $where .= ' AND ';
        $where .= " t.parent_id=:Parent_id ";
        $params[] = array(  "key" => ":Parent_id", "value" =>$options['parent_id']);
    }
    if ( $options['escape_id']!=-1 ) {
        if ($where != '') $where .= ' AND ';
        $where .= " t.id<>:Escape_id ";
        $params[] = array(  "key" => ":Escape_id", "value" =>$options['escape_id']);
    }
//    if(!is_null($options['lang']) && $options['lang']!=''){
//        if ($where != '') $where .= ' AND ';
//        $where .= " language=:Lang ";
//        $params[] = array(  "key" => ":Lang", "value" =>$options['lang']);
//    }
    if (!is_null($options['search_string']) && $options['search_string']!='' ) {
        $options['search_string'] = strtolower($options['search_string']);
        if ($where != '') $where = " ( $where ) AND ";
        $search_strings = explode(' ', $options['search_string']);
        $wheres = array();
        $i=0;
        foreach ($search_strings as $search_string_loop) {
            $wheres[] = "(				
                    LOWER(t.title) LIKE :Wheres$i
            )";
            $params[] = array(  "key" => ":Wheres$i", "value" =>'%'.$search_string_loop.'%');
            $i++;
        }
        $where .= "( " . implode(' AND ', $wheres) . ")";
    }
    if ($where != '') $where .= ' AND ';
    $where .= " t.published=1 ";

    if ($where != '') {
        $where = "WHERE $where";
    }

    $orderby = $options['orderby'];
    $order='';
    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }
    $nlimit = '';
    if (!is_null($options['limit'])) {
        $nlimit = intval($options['limit']);
        $skip = intval($options['page']) * $nlimit + intval($options['skip']);
    }

    if ($options['n_results'] == false) {
        if($options['lang'] == 'en'){
            $query = "SELECT t.* FROM cms_thingstodo t "
                . " $where ORDER BY t.$orderby $order";
        }
        else{
            $query = "SELECT t.*, mlt.title as ml_title, mlt.description as ml_description FROM cms_thingstodo t "
                . "INNER JOIN ml_thingstodo mlt ON mlt.parent_id = t.id AND mlt.language = '".$options['lang']
                . "' $where ORDER BY t.$orderby $order";
        }
        if (!is_null($options['limit'])) {
            $query .= " LIMIT :Skip, :Nlimit";
            $params[] = array(  "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
            $params[] = array(  "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
        }
//        echo $query; echo '<br>';
//        print_r($params);exit;
        $select = $dbConn->prepare($query);
        PDO_BIND_PARAM($select,$params);
        $res    = $select->execute();
        $ret    = $select->rowCount();           
        if (!$select || ($ret == 0)) {
            return false;
        } else {
            $ret_arr = array();
            $ret_arr = $select->fetchAll();            
            return $ret_arr;
        }
    } else {
		$query = "SELECT COUNT(id) FROM cms_thingstodo $where";
		$select = $dbConn->prepare($query);
		PDO_BIND_PARAM($select,$params);
		$res    = $select->execute();
		$row = $select->fetch();
        return $row[0];
    }
}
/**
 * get list of things to do list
 * @param integer $parent_id the cms_thingstodo id
 * @return array
 */
function getThingstodoDetailList($srch_options) {
    global $dbConn;
    $params = array();
    $default_opts = array(
        'orderby' => 'id',
        'order' => 'a',
        'limit' => null,
        'page' => 0,
        'id' => null,
        'has_image' => 0,
        'parent_id' => null,
        'country' => null,
        'city_id' => 0,
        'lang' => 'en'
    );
    $options = array_merge($default_opts, $srch_options);
    $where = '';
    if (!is_null($options['id'])) {
        if ($where != '') $where .= ' AND ';
        $where .= " t.id=:Id ";
        $params[] = array(  "key" => ":Id", "value" =>$options['id']);
    }
    if (!is_null($options['country'])) {
        if ($where != '') $where .= ' AND ';
        $where .= " t.country=:Country ";
        $params[] = array(  "key" => ":Country", "value" =>$options['country']);
    }
    if ( $options['has_image']==1 ) {
        if ($where != '') $where .= ' AND ';
        $where .= " t.image<>'' ";
    }
    if (!is_null($options['parent_id'])) {
        if ($where != '') $where .= ' AND ';
        $where .= " t.parent_id=:Parent_id ";
        $params[] = array(  "key" => ":Parent_id", "value" =>$options['parent_id']);
    }
    if ($options['city_id']>0) {
        if ($where != '') $where .= ' AND ';
        $where .= " t.city_id=:City_id ";
        $params[] = array(  "key" => ":City_id", "value" =>$options['city_id']);
    }
    
    $orderby = $options['orderby'];
    $order='';
    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }
    
    if($options['lang'] == 'en'){
        $query = "SELECT t.*, td.id AS division_id, td.division_category_id AS division_category_id FROM cms_thingstodo_details t"
            . " LEFT JOIN thingstodo_division td ON td.ttd_id = t.id AND td.parent_id IS NULL"
            . " WHERE $where AND published = 1 ORDER BY t.$orderby $order";
    }
    else{
        $query = "SELECT t.*, mlt.title as ml_title, mlt.description as ml_description, td.id AS division_id, td.division_category_id AS division_category_id FROM cms_thingstodo_details t "
            . "INNER JOIN ml_thingstodo_details mlt ON mlt.parent_id = t.id AND mlt.language = '".$options['lang']
            . " LEFT JOIN thingstodo_division td ON td.ttd_id = t.id AND td.parent_id IS NULL"
            . "' WHERE $where AND published = 1 ORDER BY t.$orderby $order";
    }

    if (!is_null($options['limit'])) {
        $nlimit = intval($options['limit']);
        $skip = intval($options['page']) * $nlimit;
        $query .= " LIMIT :Skip, :Nlimit";
        $params[] = array(  "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
        $params[] = array(  "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
    }
//    echo $query;exit;
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    if ($res && $ret > 0) {
        $row = $select->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }else{        
        return array();
    }
}
function getReviewsCount($entity_type,$entity_id){
    global $dbConn;
    $params = array(); 
    $table ="";
    $entity_value ="";
    if($entity_type == SOCIAL_ENTITY_HOTEL){
        $table ="discover_hotels_reviews";
        $entity_value ="hotel_id";
    }else if($entity_type == SOCIAL_ENTITY_RESTAURANT){
//        $table ="discover_restaurants_reviews";
//        $entity_value ="restaurant_id";
    }else if($entity_type == SOCIAL_ENTITY_LANDMARK){
        $table ="discover_poi_reviews";
        $entity_value ="poi_id";
    }else if($entity_type == SOCIAL_ENTITY_AIRPORT){
        $table ="airport_reviews";
        $entity_value ="airport_id";
    }
    if($table==""){
        return 0;
    }else{
        $query = "SELECT COUNT(id) FROM `$table` WHERE $entity_value = :Entity_id AND published=1";
	$params[] = array(  "key" => ":Entity_id", "value" =>$entity_id);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$ret = $select->execute();
        $row = $select->fetch();
        if( $ret ){
            return $row[0];
        }else{
            return 0;
        }
    }
    
    
}
function getReviewsImage($entity_type,$entity_id){
    global $dbConn;
    $params = array(); 
    $table ="";
    $entity_value ="";
    if($entity_type == SOCIAL_ENTITY_HOTEL){
        $table ="discover_hotels_images";
        $entity_value ="hotel_id";
    }else if($entity_type == SOCIAL_ENTITY_RESTAURANT){
//        $table ="discover_restaurants_images";
//        $entity_value ="restaurant_id";
    }else if($entity_type == SOCIAL_ENTITY_LANDMARK){
        $table ="discover_poi_images";
        $entity_value ="poi_id";
    }else if($entity_type == SOCIAL_ENTITY_AIRPORT){
        $table ="airport_images";
        $entity_value ="airport_id";
    }
    if($table==""){
        return 0;
    }else{
        $query = "SELECT IFNULL(filename, '') FROM `$table` WHERE $entity_value = :Entity_id";
	$params[] = array(  "key" => ":Entity_id", "value" =>$entity_id);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$ret = $select->execute();
        $row = $select->fetch();
        if( $ret ){
            return $row[0];
        }else{
            return 0;
        }
    }
    
    
}
/**
 * get list of poi list
 * @return array
 */
function getPoiList($srch_options){
    global $dbConn;
    $params = array();
    $default_opts = array(
        'limit' => 12,
        'page' => 0,
        'letter' => '',
        'string_search' => null,        
        'longitude_min' => null,        
        'longitude_max' => null,        
        'latitude_min' => null,        
        'latitude_max' => null,        
        'cat_search' => null,        
        'country' => null,        
        'city_id' => null,
        'n_results' => false
    );
    $options = array_merge($default_opts, $srch_options);
    $where = '';
    if ( !is_null($options['string_search']) && $options['string_search']!='' ){
        if ( $where != '' ) $where .= ' AND';
        $where .= ' (p.city LIKE :String_search OR p.city_id=:City_id OR (p.longitude BETWEEN :Longitude_search0 AND :Longitude_search1  AND p.latitude BETWEEN :Latitude_search0 AND :Latitude_search1) )';
        $params[] = array(  "key" => ":String_search", "value" =>"%".$options['string_search']."%");
        $params[] = array(  "key" => ":City_id", "value" =>$options['city_id']);
        $params[] = array(  "key" => ":Longitude_search0", "value" =>$options['longitude_min']);
        $params[] = array(  "key" => ":Longitude_search1", "value" =>$options['longitude_max']);
        $params[] = array(  "key" => ":Latitude_search0", "value" =>$options['latitude_min']);
        $params[] = array(  "key" => ":Latitude_search1", "value" =>$options['latitude_max']);
    }
    if (!is_null($options['country']) && $options['country']!='' ) {
        if ($where != '') $where .= ' AND ';
        $where .= " p.country=:Country ";
        $params[] = array(  "key" => ":Country", "value" =>$options['country']);
    }
    if (!is_null($options['cat_search']) && $options['cat_search']!='' ) {
        if ($where != '') $where .= ' AND ';
        $where .= " (p.cat like :Cat_search OR p.sub_cat like :Cat_search) ";
        $params[] = array(  "key" => ":Cat_search", "value" =>"%".$options['cat_search']."%");
    }
    if(!is_null($options['letter']) &&$options['letter']!=''){
        if ($where != '') $where .= ' AND ';
        $where .= ' p.name like Lower(:Letter) AND';
        $params[] = array(  "key" => ":Letter",
                            "value" =>$options['letter'].'%');
    }
    if ( $options['n_results']==false ) {
        $query = "SELECT p.*, i.id as i_id , i.filename as img FROM `discover_poi` as p LEFT JOIN discover_poi_images as i on i.poi_id=p.id WHERE";
        $query .= " $where AND p.published=1 GROUP BY p.id ORDER BY i.default_pic DESC";
        if (!is_null($options['limit'])) {
            $query .= " LIMIT :Start,:Limit";
            $start = $options['page']*$options['limit'];
            $params[] = array(  "key" => ":Limit", "value" =>$options['limit'], "type" =>"::PARAM_INT");
            $params[] = array(  "key" => ":Start", "value" =>$start, "type" =>"::PARAM_INT");
        }
        $select = $dbConn->prepare($query);
        PDO_BIND_PARAM($select,$params);
        $res    = $select->execute();
        $ret    = $select->rowCount();
        if ($res && $ret > 0) {
            $row = $select->fetchAll(PDO::FETCH_ASSOC);
            return $row;
        }else{        
            return array();
        }
    }else{
        $query = "SELECT count(p.id) FROM `discover_poi` as p WHERE $where AND p.published=1";
        $select = $dbConn->prepare($query);
        PDO_BIND_PARAM($select,$params);
        $select->execute();
        $row = $select->fetch();
        return $row[0];
    }
}

/**
 * gets most reviewed number of hotels based on the limit given
 * @return array
 */
function getSuggestedHotels($options){
    global $dbConn;
    $params = array();  
    $default_opts = array(
        'limit' => 12,
        'page' => 0,
        'limit' => '',
        'letter' => '',
        'require_image' => 0,
        'search_string' => '',
        'orderby' => 'id',
        'order' => 'a',
        'n_results' => false
    );
    $options = array_merge($default_opts, $options);
    $limit='';
    
    $nlimit = $options['limit'];
    $skip = $options['page'] * $nlimit;
    
    $orderby = $options['orderby'];
    $order='';
    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }
    $where = '';
    if(!is_null($options['search_string']) && $options['search_string']!=''){
        $where = 'INNER JOIN cms_countries AS dhc ON dh.countryCode = dhc.code
                  LEFT JOIN webgeocities  AS dhw ON dh.city_id     = dhw.id
                  WHERE ( dhw.name LIKE LOWER( :Search_string )
                        OR dh.hotelName LIKE LOWER( :Search_string )
                        OR dhc.name LIKE LOWER( :Search_string )
                        )';
        $params[] = array(  "key" => ":Search_string",
                            "value" =>'%'.$options['search_string'].'%');
    }
    if(!is_null($options['letter']) && $options['letter']!=''){
        if($where == '' || is_null($where)) { 
            $where = 'where dh.hotelName like LOWER(:Letter)';
        }else{
            $where .= 'AND dh.hotelName like LOWER(:Letter)';
        }
        $params[] = array(  "key" => ":Letter",
                            "value" =>$options['letter'].'%');
    }
    if($where!='') $where.=' AND';
    else $where.='WHERE';
    $where.='  dh.published=1';
    if ( $options['n_results']==false ) { 
        $limit = 'LIMIT :Skip, :Limit';
        $params[] = array(  "key" => ":Limit",
                            "value" =>$options['limit'],
                            "type" =>"::PARAM_INT");
        $params[] = array(  "key" => ":Skip",
                            "value" =>$skip,
                            "type" =>"::PARAM_INT");
        $query_hotels = "SELECT dh.id, dh.map_image, dh.hotelName, dh.address, dh.location, dh.latitude, dh.longitude, dh.cityName,dh.rating, dh.countryCode AS country, dh.city_id AS city ";
        if($options['require_image']==1){
            $query_hotels .= ", IFNULL(dhi.filename, '') AS filename ";
        }
        $query_hotels .= " FROM discover_hotels as dh ";
        if($options['require_image']==1){
            $query_hotels .= " INNER JOIN discover_hotels_images as dhi on dh.id = dhi.hotel_id ";
        }
        $query_hotels .= "$where
                         GROUP BY dh.id
                         ORDER BY $orderby $order, dh.hotelName 
                         $limit";
        $select = $dbConn->prepare($query_hotels);
        PDO_BIND_PARAM($select,$params);
        $res    = $select->execute();
        $ret    = $select->rowCount();
        if($res && $ret != 0){
            $hotels_arr = array();
            $hotels_arr = $select->fetchAll(PDO::FETCH_ASSOC);
            return $hotels_arr;
        }else{
            return false;
        }
    }else{
        $query_hotels = "SELECT count( dh.id ) as count FROM discover_hotels as dh $where";
        $select = $dbConn->prepare($query_hotels);
        PDO_BIND_PARAM($select,$params);
        $res    = $select->execute();
        $ret    = $select->rowCount();
        if($res && $ret != 0){
            $hotels_arr = array();
            $hotels_arr = $select->fetchAll(PDO::FETCH_ASSOC);
            return $hotels_arr[0];
        }else{
            return false;
        }
    }
}

/**
 * gets most reviewed number of hotels based on the limit given
 * @return array
 */
function getSuggestedRestaurants($options) {
    global $dbConn;
    $params = array();  
    $default_opts = array(
        'limit' => 12,
        'page' => 0,
        'letter'=> '',
        'require_image' => 0,
        'search_string' => null,
        'orderby' => 'id',
        'order' => 'a',
        'n_results' => false
    );
    $options = array_merge($default_opts, $options);
    
    $limit='';
    $nlimit = $options['limit'];
    $skip = $options['page'] * $nlimit;
    
    $orderby = $options['orderby'];
    $order='';
    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }
    $where = '';
    if(!is_null($options['search_string']) && $options['search_string'] !='' ){
        $where = 'INNER JOIN cms_countries AS dhc ON dh.country = dhc.code
                  WHERE (
                        dh.locality LIKE LOWER( :Search_string ) OR dh.region LIKE LOWER( :Search_string ) or dh.admin_region LIKE LOWER( :Search_string )
                        OR dh.name LIKE LOWER( :Search_string )
                        OR dhc.name LIKE LOWER( :Search_string )
                        )';
        $params[] = array(  "key" => ":Search_string",
                            "value" =>'%'.$options['search_string'].'%');
    }
    if(!is_null($options['letter']) && $options['letter'] != ''){
        if($where == '' || is_null($where)) { 
            $where = 'where dh.name like Lower(:Letter)';
        }else{
            $where .= 'AND dh.name like Lower(:Letter)';
        }
        $params[] = array(  "key" => ":Letter",
                            "value" =>$options['letter'].'%');
    }
    
    if($where!='') $where.=' AND';
    else $where.='WHERE';
    $where.='  dh.published=1';
    if ( $options['n_results']==false ) {
        $limit = 'LIMIT :Skip, :Limit';
        $params[] = array(  "key" => ":Limit",
                            "value" =>$options['limit'],
                            "type" =>"::PARAM_INT");
        $params[] = array(  "key" => ":Skip",
                            "value" =>$skip,
                            "type" =>"::PARAM_INT");
        $query_Restaurants = "SELECT dh.id, dh.name, dh.map_image, dh.avg_rating as rating, dh.address, dh.latitude, dh.longitude, dh.locality, dh.region, dh.admin_region, dh.country, dh.city_id AS city ";
        if($options['require_image']==1){
            $query_Restaurants .= ", IFNULL(dhi.filename, '') AS filename ";
        }
        $query_Restaurants .= " FROM global_restaurants as dh ";
        if($options['require_image']==1){
            $query_Restaurants .= " INNER JOIN discover_restaurants_images as dhi on dh.id = dhi.restaurant_id ";
        }
        $query_Restaurants .= " $where";
        if($options['require_image']==1){
            $query_Restaurants .=   " GROUP BY dh.id ";
        }
            $query_Restaurants .= " ORDER BY $orderby $order, dh.name 
                                    $limit";
        $select = $dbConn->prepare($query_Restaurants);
        PDO_BIND_PARAM($select,$params);
        $restaurants_arr    = $select->execute();
        $ret                = $select->rowCount();
        if($restaurants_arr && $ret != 0){
            $row = $select->fetchAll(PDO::FETCH_ASSOC);
            return $row;
        }else{
            return false;
        }
    }else{
        $query_Restaurants = "SELECT count(dh.id) AS count FROM global_restaurants as dh $where";
        $select = $dbConn->prepare($query_Restaurants);
        PDO_BIND_PARAM($select,$params);
        $restaurants_arr    = $select->execute();
        $ret                = $select->rowCount();
        if($restaurants_arr && $ret != 0){
            $row = $select->fetchAll(PDO::FETCH_ASSOC);
            return $row[0];
        }else{
            return false;
        }
        
    }
    
}

/**
 * gets most reviewed number of hotels based on the limit given
 * @return array
 */
function getSuggestedPois($options){
    
    global $dbConn;
    $params = array();  
    $default_opts = array(
        'limit' => 12,
        'page' => 0,
        'letter' => '',
        'require_image' => 0,
        'search_string' => '',
        'orderby' => 'dh.id',
        'order' => 'a',
        'n_results' => false
    );
    $options = array_merge($default_opts, $options);
    
    $limit='';
    
    $nlimit = $options['limit'];
    $skip = $options['page'] * $nlimit;
    $orderby = $options['orderby'];
    $order='';
    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }
    $where = '';
    if(!is_null($options['search_string']) && $options['search_string']!=''){
        $where = 'INNER JOIN cms_countries AS dhc ON dh.country  = dhc.code
                  LEFT JOIN webgeocities  AS dhw ON dh.city_id  = dhw.id
                  WHERE ( dhw.name LIKE LOWER( :Search_string )
                        OR dh.name LIKE LOWER( :Search_string )
                        OR dhc.name LIKE LOWER( :Search_string )
                        )';
        $params[] = array(  "key" => ":Search_string",
                            "value" =>'%'.$options['search_string'].'%');
    }
    if(!is_null($options['letter']) && $options['letter']!=''){
        if($where == '' || is_null($where)) { 
            $where = 'where dh.name like LOWER(:Letter)';
        }else{
            $where .= 'AND dh.name like LOWER(:Letter)';
        }
        $params[] = array(  "key" => ":Letter",
                            "value" =>$options['letter'].'%');
    }
    
    if($where!='') $where.=' AND';
    else $where.='WHERE';
    $where.='  dh.published=1';
    if ( $options['n_results']==false ) {
        $limit = 'LIMIT :Skip, :Limit';
        $params[] = array(  "key" => ":Limit",
                            "value" =>$options['limit'],
                            "type" =>"::PARAM_INT");
        $params[] = array(  "key" => ":Skip",
                            "value" =>$skip,
                            "type" =>"::PARAM_INT");
        $query_pois = "SELECT dh.id, dh.address, dh.latitude, dh.map_image, dh.longitude , dh.name AS name, IFNULL(dhi.filename, '') AS filename, dh.country AS country, dh.city_id AS city
                         FROM discover_poi as dh ";
        if($options['require_image']==1){
            $query_pois .= " INNER JOIN discover_poi_images as dhi on dh.id = dhi.poi_id";
        }else{
            $query_pois .= " LEFT JOIN discover_poi_images as dhi on dh.id = dhi.poi_id";
        }  
        $query_pois .= " $where
                         GROUP BY dh.id
                         ORDER BY $orderby $order, dh.name 
                         $limit";$select = $dbConn->prepare($query_pois);
        $select = $dbConn->prepare($query_pois);
        PDO_BIND_PARAM($select,$params);
        $res    = $select->execute();
        $ret    = $select->rowCount();
        if($res && $ret != 0){
            $hotels_arr = array();
            $hotels_arr = $select->fetchAll(PDO::FETCH_ASSOC);
            return $hotels_arr;
        }else{
            return false;
        }
    }else{
        $query_pois = "SELECT count( dh.id )  AS count FROM discover_poi as dh $where";
        $select = $dbConn->prepare($query_pois);
        PDO_BIND_PARAM($select,$params);
        $res    = $select->execute();
        $ret    = $select->rowCount();
        if($res && $ret != 0){
            $hotels_arr = array();
            $hotels_arr = $select->fetchAll(PDO::FETCH_ASSOC);
            return $hotels_arr[0];
        }else{
            return false;
        }
    }
    
}
/**
 * return image for map
 * 
 * @param string $lat
 * @param string $lon
 * @param int $type (SOCIAL_ENTITY_HOTEL, SOCIAL_ENTITY_RESTAURANT, SOCIAL_ENTITY_LANDMARK,SOCIAL_ENTITY_AIRPORT)
 * @param int $zoom
 * @param string $size ([width]x[height])
 * 
 * @return string the image url
 */
function mapImageURL($lat,$lon,$type,$zoom=12,$size='200x200',$info_array){
    global $CONFIG;
    $map_image = $info_array['map_image'];
    if($map_image!='' && file_exists($CONFIG['server']['root'].'media/gmap/'.$map_image) ){
        return ReturnLink('media/gmap/'.$map_image);
    }
    $serveruri = currentServerURL();   
    if ($type === SOCIAL_ENTITY_HOTEL) {
        $pin = $serveruri.'/media/images/pin_hot.png';
    } else if ($type === SOCIAL_ENTITY_RESTAURANT) {
        $pin = $serveruri.'/media/images/pin_rest.png';
    } else if ($type === SOCIAL_ENTITY_LANDMARK) {
        $pin = $serveruri.'/media/images/pin_lmk.png';
    } else if ($type === SOCIAL_ENTITY_AIRPORT) {
        $pin = $serveruri.'/media/images/pin_empty.png';
    }
    $pin .= '|'.$lat.','.$lon;//marker position    
    $url = "http://maps.googleapis.com/maps/api/staticmap?key=AIzaSyCL53RGsSAL-vteodkWJJZCaRksk3HB02E&center=$lat,$lon&markers=icon:$pin&zoom=$zoom&size=$size&sensor=false";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
    $raw = curl_exec($ch);
    curl_close ($ch);
    
	$image_saved = ''; // added just to define the variable in case the below if statement is not true.
	$image_path = '';
    if($ch){
        $image_saved = $type."-".$info_array['id']."-".time().".png";
        // $image_path = $CONFIG['server']['root'].'media/gmap/'.$image_saved;
		
		$image_path = get_folder_path_from_string_parts('', get_string_parts(str_replace('-', '', $image_saved), 5, '\.', null), array('stop_at_part' => '.'));
		
		$image_system_dir = $CONFIG['server']['root'].'media/gmap/'.$image_path;
		@mkdir($image_system_dir, 0755, true);
		
        $fp = fopen($image_system_dir.$image_saved, 'x');
        fwrite($fp, $raw);
        fclose($fp);
		
        $update_image = updateMapImage($type,$info_array['id'], $image_path.$image_saved);            
    }
    return ReturnLink('media/gmap/'.($image_saved?$image_path.$image_saved:''));
}

function updateMapImage($type,$id,$image_link) {
    global $dbConn;
    $params = array();  
    
    if ($type === SOCIAL_ENTITY_HOTEL) {
        $table = "discover_hotels";
    } else if ($type === SOCIAL_ENTITY_RESTAURANT) {
        $table = "global_restaurants";
    } else if ($type === SOCIAL_ENTITY_LANDMARK) {
        $table = "discover_poi";
    } else if ($type === SOCIAL_ENTITY_AIRPORT) {
        $table = "airport";
    }

    $query = "UPDATE $table SET map_image = :Img_link WHERE id = :Id";
    $params[] = array( "key" => ":Img_link",
                        "value" =>$image_link);
    $params[] = array( "key" => ":Id",
                        "value" =>$id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res = $update->execute();
    if($res){
        return true;
    }else{
        return false;
    }        
}

function topCitiesByHotelCount($country_code_list, $limit = null, $options = array())
{
    global $dbConn;
	
	$default_options = array('min_hotels_count' => 50, 'allow_empty_country_code_list' => false);
	
	$effective_options = array_merge($default_options, $options);
	
	if (!$country_code_list && !$effective_options['allow_empty_country_code_list'])
		return array();
	
	if (!is_array($country_code_list))
		$country_code_list = array($country_code_list);
	
	$sql['country_codes'] = array();
	
	if ($country_code_list)
	{
		foreach($country_code_list as $c_index => $country_code)
			$sql['country_codes'][':c'.$c_index] = $country_code;
	}
	
	/*
		20151117:: Removed h.countryCode IN ($country) because some values are wrong, example: 
		
		select distinct h.countryCode, c.country_code 
		from discover_hotels h 
		inner join webgeocities c on (c.id = h.city_id) 
		where h.city_id = 2449869;
	*/
	
    $sql_select = "SELECT count(h.id) AS cnt, c.country_code, h.city_id, c.name 
			FROM discover_hotels AS h 
			INNER JOIN webgeocities AS c on (c.id = h.city_id".($country_code_list?" AND c.country_code IN (".implode(', ', array_keys($sql['country_codes'])).")":'').") 
			WHERE h.city_id > 0 AND h.published = 1 
			GROUP BY c.country_code, h.city_id, c.name 
			HAVING count(h.id) >= ".$effective_options['min_hotels_count']." 
			ORDER BY cnt DESC".($limit?" LIMIT $limit ":'');
	
    $statement = $dbConn->prepare($sql_select);
    $statement->execute($sql['country_codes']);
    $cities = $statement->fetchAll(PDO::FETCH_ASSOC); 
    if($cities !== false && $cities)
	{
        return $cities;
    }
	else
	{
        return array();
    }           
}

function cityHotelsPlaces($country_code_list, $limit = null, $options = array())
{
    global $dbConn;
	
	$default_options = array('min_hotels_count' => 50, 'allow_empty_country_code_list' => false);
	
	$effective_options = array_merge($default_options, $options);
	
	if (!$country_code_list && !$effective_options['allow_empty_country_code_list'])
		return array();
	
	if (!is_array($country_code_list))
		$country_code_list = array($country_code_list);
	
	$sql['country_codes'] = array();
	
	if ($country_code_list)
	{
		foreach($country_code_list as $c_index => $country_code)
			$sql['country_codes'][':c'.$c_index] = $country_code;
	}
	
	/*
		20151117:: Removed h.countryCode IN ($country) because some values are wrong, example: 
		
		select distinct h.countryCode, c.country_code 
		from discover_hotels h 
		inner join webgeocities c on (c.id = h.city_id) 
		where h.city_id = 2449869;
	*/
	
    $sql_select = "SELECT count(h.id) AS cnt, c.country_code, h.city_id, c.name 
				FROM discover_hotels AS h 
				INNER JOIN webgeocities AS c on (c.id = h.city_id".($country_code_list?" AND c.country_code IN (".implode(', ', array_keys($sql['country_codes'])).")":'').") 
				WHERE h.city_id > 0 AND h.published = 1 
				GROUP BY c.country_code, h.city_id, c.name 
				HAVING count(h.id) >= ".$effective_options['min_hotels_count']." 
				ORDER BY cnt DESC".($limit?" LIMIT $limit ":'');
    
    $statement = $dbConn->prepare($sql_select);
    $statement->execute($sql['country_codes']);
    $cities = $statement->fetchAll(PDO::FETCH_ASSOC); 
    if($cities !== false && $cities)
	{
		$qr = "INSERT INTO cms_places_stay (name, country_code, city_id, count) VALUES (:name, :country_code, :city_id, :cnt)";
		$statement = $dbConn->prepare($qr);
		
        foreach($cities as $city_details)
		{
			$city_details_values = array();
			foreach($city_details as $column => $value)
				$city_details_values[":$column"] = $value;
			
            $statement->execute($city_details_values);
        }
		
        return $cities;
    }
	else
	{
        return array();
    }          
}

function restaurantsAllCuisine() {
    global $dbConn;
    $params = array();  
    $qr = "SELECT * FROM `factual_cuisine` ";
    $select = $dbConn->prepare($qr);
    $select->execute();
    $media = $select->fetchAll(PDO::FETCH_ASSOC); 
    if($media){
        return $media;
    }else{
        return array();
    }           
}

function countryCodesFromContinent($continent_list, $options = array())
{
    global $dbConn;
    
	$default_options = array('allow_empty_continent_list' => false);
	
	$effective_options = array_merge($default_options, $options);
	
	if (!$continent_list && !$effective_options['allow_empty_continent_list'])
		return array();
	
	if (!is_array($continent_list))
		$continent_list = array($continent_list);
	
	$sql['continent_codes'] = array();
	
	if ($continent_list)
	{
		foreach($continent_list as $c_index => $continent_code)
			$sql['continent_codes'][':c'.$c_index] = $continent_code;
	}
	
    $sql_select = "SELECT code 
		FROM cms_countries" 
		.($continent_list?" WHERE continent_code IN (".implode(', ', array_keys($sql['continent_codes'])).")":'');
    
    $statement = $dbConn->prepare($sql_select);
    $statement->execute($sql['continent_codes']);
    $country_codes = $statement->fetchAll(PDO::FETCH_COLUMN);
	
    if ($country_codes !== false) 
		return $country_codes;
	else
		return array();
}

/**
 * return string into parts defined by some rules. If the rules do not apply, return the input string itself.
 * 
 * Example: get_string_parts('282659281446030124.png', 5, '\.', null) returns ('28265', '92814', '46030', '124', '.', 'png')
 *
 * @param string $input_string The input string.
 * @param string $part_length Defines the length of each string part.
 * @param string $delimiter This character will show in the array as a delimiter (single array element) when it's encountered in the input string. This parameter is currently passed as is to a regular expression matching function. TODO:: add regexp-specific auto-escaping when necessary.
 * @param array(string, string, ...) suppress_prefixes An array of characters to be suppressed. Must be fixed, but for the time being, we don't rely on this parameter.
 * 
 * @return string Array of string parts, or the input string if the given rules do not apply.
 */
function get_string_parts($input_string, $part_length = 4, $delimiter = '@', $suppress_prefixes = array('.'))
{
	if ($part_length < 1)
		$part_length = 1;
	
	// $r = "/(.+?(?=@)|[^.@]+)/"; //  ==> L1234567890 for L1234567890@idm.net.lb
	
	// $r = "/(?(?=@).|[^.@][^@]{1,3})+?/";
	
	$r = "/(?(?=$delimiter).|[^".($suppress_prefixes?implode('', $suppress_prefixes):'')."$delimiter][^$delimiter]{0,".($part_length - 1)."})+?/";
	
	if (preg_match_all($r, $input_string, $matches, PREG_SET_ORDER))
	{
		$string_parts = array();
		
		foreach ($matches as $string_match)
			$string_parts[] = $string_match[0];
		
		return $string_parts;
	}
	
	return $input_string;
}

/**
 * Return the directory path given a base_path, string_parts (as returned by function get_string_parts), and some options.
 * 
 * @param string $base_path The base path for the directory.
 * @param string $string_parts The output from function get_string_parts().
 * @param string $options Options such as: stop_at_part (default: '') This is usually the delimiter character specified in the call to get_string_parts, inclusive_stop_part (default: false) Tells whether the character to stop at should be included in the directory path string.
 * 
 * @return string Directory path constructed using the given parameters.
 */
function get_folder_path_from_string_parts($base_path, $string_parts, $options)
{
	$default_options = array('stop_at_part' => '', 'inclusive_stop_part' => false);
	
	if (!$options) $options = array();
	if (!is_array($options)) $options = array($options);
	$options = array_merge($default_options, $options);
	
	$folder_path = $base_path;
	if ($folder_path && substr($folder_path, strlen($folder_path) - 1) != DIRECTORY_SEPARATOR)
		$folder_path .= DIRECTORY_SEPARATOR;
	
	foreach ($string_parts as $string_part)
	{
		if ($string_part == $options['stop_at_part'] && !$options['inclusive_stop_part'])
			break;
		
		$folder_path .= $string_part.'/';
		
		if ($string_part == $options['stop_at_part'])
			break;
	}
	
	return $folder_path;
}

function entity_description($entity_type, $entity_id){
    global $dbConn;
    $query = "SELECT * FROM cms_entity_description WHERE entity_type=:Entity_type AND entity_id=:Entity_id";   
    $params[] = array(  "key" => ":Entity_type", "value" => $entity_type);
    $params[] = array(  "key" => ":Entity_id", "value" => $entity_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();
    $ret = $select->rowCount(); 
    if($res && $ret!=0 ){
        $row = $select->fetch(PDO::FETCH_ASSOC);
        return $row['description'];
    }else{
        return '';
    }
}