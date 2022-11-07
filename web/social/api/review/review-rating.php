<?php
/*! \file
 * 
 * \brief This api returns review rating of hotel,restaurant,landmark and airport
 * 
 * 
 * @param S session id
 * @param entity_id entity id
 * @param entity_type entity type
 * @param globchannelid channel id
 * 
 * @return <b>result</b> JSON List of information (array)
 * @return <pre> 
 * @return       <b>info</b> List of entity information (array)
 * @return       		<b>title</b> entity title
 * @return       		<b>address</b> entity address
 * @return       		<b>image</b> entity image path
 * @return       		<b>lat</b> entity latitude
 * @return       		<b>lng</b> entity longitude
 * @return       <b>global_rating</b> entity new average rating
 * @return       <b>average_rating</b> entity old average rating
 * @return       <b>nb_reviews</b> entity number of reviews
 * @return       <b>nb_votes</b> entity number of likes
 * @return       <b>mix_rating</b> List of entity information (array)
 * @return       		<b>entity_type</b> entity type
 * @return       		<b>rating_value</b> entity rating value
 * @return       		<b>name</b> entity name
 * @return       <b>photos</b> List of media information(array)
 * @return       		<b>id</b> media id
 * @return       		<b>img</b> media image path
 * @return       		<b>discover_imgbig</b> media big image path
 * @return       <b>user_photos</b> List of user media information(array)
 * @return       		<b>id</b> user media id
 * @return       		<b>img</b> user media image path
 * @return       		<b>discover_imgbig</b> user media big image path
 * @return       <b>share_url</b> share link
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>

 * 
 *  */
$expath = "../";
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$uricurserver = currentServerURL().'/';
//echo $uricurserver;

//$user_id = mobileIsLogged($_REQUEST['S']);
$user_id = mobileIsLogged($submit_post_get['S']);

//if( !$user_id ) die();


//$entity_id = intval($_REQUEST['entity_id']);
//$entity_type = intval($_REQUEST['entity_type']);
//$globchannelid= intval($_REQUEST['globchannelid']);
$entity_id = intval($submit_post_get['entity_id']);
$entity_type = intval($submit_post_get['entity_type']);
$globchannelid= isset($submit_post_get['globchannelid'])? intval($submit_post_get['globchannelid']) : 0;
$link = ReturnLink('media/discover') . '/';


/*Global rating*/
//$irating = socialRateAverage($entity_id, $entity_type);
if($user_id){
    $irating = socialRated($user_id, $entity_id, $entity_type);
    
}
if(!isset($irating)) {$irating=0;}

$link = 'media/discover/';
$theLink = $CONFIG ['server']['root'];
/*anything you could say about hotel/Restaurant/Poi */
global $dbConn;
$params  = array();  
$params2 = array();  
$params3 = array();  
$params4 = array();  
$params5 = array(); 
$params6 = array(); 
$params7 = array(); 
$params8 = array(); 
$phone = "";
switch($entity_type){
    case SOCIAL_ENTITY_HOTEL:
        /*Average Rating */
        //$irating_average = socialRateAverage($entity_id, SOCIAL_ENTITY_HOTEL);
        /*Nbr of reviews*/
        $nb_reviews = userReviewsList($entity_id, SOCIAL_ENTITY_HOTEL, 6, 0, true);
        
        /* hotel information */
        $hotel_data = getHotelInfo($entity_id);
        $phone = $hotel_data["phone"];
//        $irating_average = $hotel_data['avg_rating'];
        
        if($hotel_data['rating'] > 0){
            $irating_average = ceil(($hotel_data['rating']/2)*10)/10;
        }else{
            $irating_average = socialRateAverage($entity_id, array($entity_type));
        }
        $irating_average = ceil(($irating_average)*10)/10;
        
        $nb_votes = isset($hotel_data['nb_votes']) ? $hotel_data['nb_votes'] : 0;
        $title = $hotel_data['hotelName'].', '.$hotel_data['title_type'];
        $lat = $hotel_data['latitude'];
        $long = $hotel_data['longitude'];
        $locationText = '';
        if ($hotel_data['city_id'] != '0') {
            $city_array = worldcitiespopInfo(intval($hotel_data['city_id']));
            $city_name = $city_array['name'];
            if ($city_name != '') {
                $locationText .= $city_name;
            }
            $state_array = worldStateInfo($city_array['country_code'], $city_array['state_code']);
            $state_name = $state_array['state_name'];
            if ($state_name != '') {
                $locationText .= ', ' . $state_name;
            }
            $country_array = countryGetInfo($city_array['country_code']);
            $country_name = $country_array['name'];
            if ($country_name != '') {
                $locationText .= ', ' . $country_name. ' ';
            }
            $locationText .=  $hotel_data['address'];
        } else {
            $locationText = $hotel_data['location'];
        }
        if($locationText=='') $locationText = $hotel_data['address'];

        $media_hotels = getHotelDefaultPic($entity_id);
        if( $media_hotels ){
        $image = $link . 'thumb/' . $media_hotels['img'];
        $bigim=$media_hotels['img'];
        $for = strrpos($bigim, '_') + 1;
        $extbig = substr($bigim, $for);    
        if(file_exists( $theLink . 'media/discover/large/' .$extbig ) ){
           $image = $link . 'large/' . $extbig;
        }else{
            $image = $link . 'large/' . $media_hotels['img'];
        }
        }else{
            $image = ReturnLink('media/images/hotel-icon-image3.jpg');
        }
        
        /* Hotel Mix  Rating*/
        $toRateArr = array();
	$irating1=0;
	$irating2=0;
	$irating3=0;
	$irating4=0;
	$irating5=0;
	$irating6=0;
	$irating7=0;
	$irating8=0;
        if($user_id){
            $irating1 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_HOTEL_AIRPOT);
            if(!$irating1) $irating1=0;
            $irating2 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_HOTEL_SERVICE);
            if(!$irating2) $irating2=0;
            $irating3 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_HOTEL_CLEANLINESS);
            if(!$irating3) $irating3=0;
            $irating4 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_HOTEL_INTERIOR);
            if(!$irating4) $irating4=0;
            $irating5 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_HOTEL_PRICE);
            if(!$irating5) $irating5=0;
            $irating6 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_HOTEL_FOODDRINK);
            if(!$irating6) $irating6=0;
            $irating7 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_HOTEL_INTERNET);
            if(!$irating7) $irating7=0;
            $irating8 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_HOTEL_NOISE);
            if(!$irating8) $irating8=0;
        }
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_HOTEL_AIRPOT,'val'=>$irating1,'name'=>_('Good for airport access'));
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_HOTEL_SERVICE,'val'=>$irating2,'name'=>_('Service'));
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_HOTEL_CLEANLINESS,'val'=>$irating3,'name'=>_('Cleanliness'));
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_HOTEL_INTERIOR,'val'=>$irating4,'name'=>_('Interior'));
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_HOTEL_PRICE,'val'=>$irating5,'name'=>_('Value for price'));
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_HOTEL_FOODDRINK,'val'=>$irating6,'name'=>_('Food and drink'));
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_HOTEL_INTERNET,'val'=>$irating7,'name'=>_('Internet'));
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_HOTEL_NOISE,'val'=>$irating8,'name'=>_('Noise level'));
        
        /*Show Hotel Photos By User*/
        $media_hotelsi = array();
        if ($user_id) { 
//            $query_hotelsi = "SELECT id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = $entity_id AND user_id=$user_id ORDER BY id DESC";
            $query_hotelsi = "SELECT id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = :Entity_id AND user_id=:User_id ORDER BY id DESC";
            $params[] = array(  "key" => ":Entity_id",
                                "value" =>$entity_id);
            $params[] = array(  "key" => ":User_id",
                                "value" =>$user_id);
            $select = $dbConn->prepare($query_hotelsi);
            PDO_BIND_PARAM($select,$params);
            $ret_hotelsi    = $select->execute();
           
//            $ret_hotelsi = db_query($query_hotelsi);
            $row = $select->fetchAll();
//            while ($row = db_fetch_array($ret_hotelsi)) {
            foreach($row as $row_item){
                $bigim=$row_item['img'];
                $for = strrpos($bigim, '_') + 1;
                $extbig = substr($bigim, $for);    
                if(file_exists( $theLink . 'media/discover/large/' .$extbig ) ){
                    $row_item['discover_imgbig'] = $link . 'large/' . $extbig;
                }else{
                    $row_item['discover_imgbig'] = $link . 'large/' . $row_item['img'];
                }
                $media_hotelsi[] = $row_item;
            }
        }
        
        /*Show Hotel Photos By Other User*/
        $user_media_hotelsi = array();
        if($user_id){
//            $query_hotelsi1 = "SELECT id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = $entity_id AND user_id!=$user_id ORDER BY id DESC";
            $query_hotelsi1 = "SELECT id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = :Entity_id AND user_id!=:User_id ORDER BY id DESC";
            $params2[] = array(  "key" => ":Entity_id",
                                "value" =>$entity_id);
            $params2[] = array(  "key" => ":User_id",
                                "value" =>$user_id);
        }else{
//            $query_hotelsi1 = "SELECT id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = $entity_id ORDER BY id DESC";
            $query_hotelsi1 = "SELECT id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = :Entity_id ORDER BY id DESC";
            $params2[] = array(  "key" => ":Entity_id",
                                "value" =>$entity_id);
        }
//        $ret_hotelsi1 = db_query($query_hotelsi1);
	$select = $dbConn->prepare($query_hotelsi1);
	PDO_BIND_PARAM($select,$params2);
	$ret_hotelsi1    = $select->execute();
	$rows = $select->fetchAll();
//        while ($rows = db_fetch_array($ret_hotelsi1)) {
        foreach($rows as $rows_item){
            $bigim=$rows_item['img'];
            $for = strrpos($bigim, '_') + 1;
            $extbig = substr($bigim, $for);    
            if(file_exists( $theLink . 'media/discover/large/' .$extbig ) ){
                $rows_item['discover_imgbig'] = $link . 'large/' . $extbig;
            }else{
                $rows_item['discover_imgbig'] = $link . 'large/' . $rows_item['img'];
            }
            $user_media_hotelsi[] = $rows_item;
        }
            
        
        /*Share url for hotel*/
        $hotel_name_clean = cleanTitle($hotel_data['hotelName']);
        $share_url = $uricurserver.$hotel_name_clean.'-review-H'.$entity_id;
        break;
        
    /*Restaurant*/
    case SOCIAL_ENTITY_RESTAURANT:
        /*Average Rating */
//        $irating_average = socialRateAverage($entity_id, SOCIAL_ENTITY_RESTAURANT);
        /*Nbr of reviews*/
        $nb_reviews = userReviewsList($entity_id, SOCIAL_ENTITY_RESTAURANT, 6, 0, true);
        
        /* Restaurant  information */
        $hotel_data = getRestaurantInfo($entity_id); 
        $phone = $hotel_data["tel"];
//        $irating_average = $hotel_data['avg_rating'];
        
        if($hotel_data['avg_rating'] > 0){
            $irating_average = ceil(($hotel_data['avg_rating']/2)*10)/10;
        }else{
            $irating_average = socialRateAverage($entity_id, array($entity_type));
        }
        $irating_average = ceil(($irating_average)*10)/10;
        
        $nb_votes = isset($hotel_data['nb_votes']) ? $hotel_data['nb_votes'] : 0;
        
        $title = $hotel_data['name'];
        $lat = $hotel_data['latitude'];
        $long = $hotel_data['longitude'];
        $country_array = countryGetInfo($hotel_data['country']);
        $locationText = format_address($hotel_data, 'restaurant', $country_array['name']);
//            if ($hotel_data['city_id'] != '0') {
//                $city_array = worldcitiespopInfo(intval($hotel_data['city_id']));
//                $city_name = $city_array['name'];
//                if ($city_name != '') {
//                    $locationText .= $city_name;
//                }
//                $state_array = worldStateInfo($city_array['country_code'], $city_array['state_code']);
//                $state_name = $state_array['state_name'];
//                if ($state_name != '') {
//                    $locationText .= ', ' . $state_name;
//                }
//                $country_array = countryGetInfo($city_array['country_code']);
//                $country_name = $country_array['name'];
//                if ($country_name != '') {
//                    $locationText .= ', ' . $country_name;
//                }
//            } else {
//                $city_name = $hotel_data['locality'];
//                if ($city_name != '') {
//                    $locationText .= $city_name;
//                }
//                $region_name = $hotel_data['region'];
//                $admin_region_name = $hotel_data['admin_region'];
//                if ($region_name != '' && $region_name !=$city_name ) {
//                    $locationText .= ', ' . $region_name;
//                }else if ($admin_region_name != '' && $admin_region_name !=$city_name) {
//                    $locationText .= ', ' . $admin_region_name;
//                }
//                $country_cd = $hotel_data['country'];
//                if ($country_cd != '') {
//                    $country_array = countryGetInfo($country_cd);
//                    $country_name = $country_array['name'];
//                    if ($country_name != '') {
//                        $locationText .= ', ' . $country_name;
//                    }
//                }
//            }
//            if ($locationText == '') {
//                $locationText = $hotel_data['address'];
//            }else if ($hotel_data['address'] != '') {
//                $locationText .= $hotel_data['address'];
//            }

        $media_hotels = getRestaurantDefaultPic($entity_id);
        if( $media_hotels ){
            $image = $link . 'thumb/' . $media_hotels['img'];
            $bigim=$media_hotels['img'];
            $for = strrpos($bigim, '_') + 1;
            $extbig = substr($bigim, $for);    
            if(file_exists( $theLink . 'media/discover/large/' .$extbig ) ){
                $image = $link . 'large/' . $extbig;
            }else{
                $image = $link . 'large/' . $media_hotels['img'];
            }
        }else{
            $image = ReturnLink('media/images/restaurant-icon3.jpg');
        }
        
         $irating1=0;
         $irating2=0;
         $irating3=0;
         $irating4=0;
         $irating5=0;
         $irating6=0;
         
         /* Restaurant Mix  Rating*/
        $toRateArr = array();
        if($user_id){
            $irating1 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_RESTAURANT_CUISINE);
            if(!$irating1) $irating1=0;
            $irating2 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_RESTAURANT_SERVICE);
            if(!$irating2) $irating2=0;
            $irating3 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_RESTAURANT_ATMOSPHERE);
            if(!$irating3) $irating3=0;
            $irating4 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_RESTAURANT_PRICE);
            if(!$irating4) $irating4=0;
            $irating5 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_RESTAURANT_NOISE);
            if(!$irating5) $irating5=0;
            $irating6 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_RESTAURANT_TIME);
            if(!$irating6) $irating6=0;
        }
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_RESTAURANT_CUISINE,'val'=>$irating1,'name'=>_('Cuisine'));
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_RESTAURANT_SERVICE,'val'=>$irating2,'name'=>_('Service'));
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_RESTAURANT_ATMOSPHERE,'val'=>$irating3,'name'=>_('Atmosphere'));
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_RESTAURANT_PRICE,'val'=>$irating4,'name'=>_('Value for price'));
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_RESTAURANT_NOISE,'val'=>$irating5,'name'=>_('Noise level'));
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_RESTAURANT_TIME,'val'=>$irating6,'name'=>_('Waiting time'));
        
        /*Show RESTAURANT Photos By User*/
        $media_hotelsi = array();
        if ($user_id) {
//           $query_hotelsi = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = $entity_id AND user_id=$user_id ORDER BY id DESC";
           $query_hotelsi = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = :Entity_id AND user_id=:User_id ORDER BY id DESC";
            $params3[] = array(  "key" => ":Entity_id",
                                 "value" =>$entity_id);
            $params3[] = array(  "key" => ":User_id",
                                 "value" =>$user_id);
            $select = $dbConn->prepare($query_hotelsi);
            PDO_BIND_PARAM($select,$params3);
            $ret_hotelsi    = $select->execute();
//            $ret_hotelsi = db_query($query_hotelsi);
            $row = $select->fetchAll();
//            while ($row = db_fetch_array($ret_hotelsi)) {
            foreach($row as $row_item){
                $bigim=$row_item['img'];
                $for = strrpos($bigim, '_') + 1;
                $extbig = substr($bigim, $for);    
                if(file_exists( $theLink . 'media/discover/large/' .$extbig ) ){
                    $row_item['discover_imgbig'] = $link . 'large/' . $extbig;
                }else{
                    $row_item['discover_imgbig'] = $link . 'large/' . $row_item['img'];
                }
                $media_hotelsi[] = $row_item;
            }
        }
        
        /*Show restaurant Photos By Other User*/
        $user_media_hotelsi = array();
        if($user_id){
//            $query_hotelsi1 = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = $entity_id AND user_id!=$user_id ORDER BY id DESC";
            $query_hotelsi1 = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = :Entity_id AND user_id!=:User_id ORDER BY id DESC";
            $params4[] = array(  "key" => ":Entity_id",
                                 "value" =>$entity_id);
            $params4[] = array(  "key" => ":User_id",
                                 "value" =>$user_id);
        }
        else{
//            $query_hotelsi1 = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = $entity_id ORDER BY id DESC";
            $query_hotelsi1 = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = :Entity_id ORDER BY id DESC";
            $params4[] = array(  "key" => ":Entity_id",
                                 "value" =>$entity_id);
        }
        $select = $dbConn->prepare($query_hotelsi1);
        PDO_BIND_PARAM($select,$params4);
        $ret_hotelsi1    = $select->execute();
//        $ret_hotelsi1 = db_query($query_hotelsi1);
	$row = $select->fetchAll();
        foreach($row as $row_item){
//        while ($rows = db_fetch_array($ret_hotelsi1)) {
            $bigim=$row_item['img'];
            $for = strrpos($bigim, '_') + 1;
            $extbig = substr($bigim, $for);    
            if(file_exists( $theLink . 'media/discover/large/' .$extbig ) ){
                $row_item['discover_imgbig'] = $link . 'large/' . $extbig;
            }else{
                $row_item['discover_imgbig'] = $link . 'large/' . $row_item['img'];
            }
            $user_media_hotelsi[] = $row_item;
        }
        
        /*Share url for restaurant*/
        $rest_name_clean = cleanTitle($title);
//        $share_url = $uricurserver.'/restaurant-review/id/'.$entity_id.'/'.$rest_name_clean;
        //$share_url = $uricurserver.$rest_name_clean.'-review-R'.$entity_id;
        $share_url = generateLangURL($rest_name_clean.'-review-R'.$entity_id, 'restaurants');
        break;
        
    /*poi*/
    case SOCIAL_ENTITY_LANDMARK:
         /*Average Rating */
//        $irating_average = socialRateAverage($entity_id, SOCIAL_ENTITY_LANDMARK);
        /*Nbr of reviews*/
        $nb_reviews = userReviewsList($entity_id, SOCIAL_ENTITY_LANDMARK, 6, 0, true);
        
        /* poi information */
        $hotel_data = getPoiInfo($entity_id);
        $phone = $hotel_data["phone"];
        $irating_average = socialRateAverage($entity_id, array($entity_type));
        $irating_average = ceil(($irating_average)*10)/10;
//        $irating_average = isset($hotel_data['avg_rating']) ? $hotel_data['avg_rating'] : 0;
        $nb_votes = isset($hotel_data['nb_votes']) ? $hotel_data['nb_votes'] : 0;
        
        $title = $hotel_data['name'];
        $lat = $hotel_data['latitude'];
        $long = $hotel_data['longitude'];
        $locationText = '';
        if ($hotel_data['city_id'] != '0') {
            $city_array = worldcitiespopInfo(intval($hotel_data['city_id']));
            $city_name = $city_array['name'];
            if ($city_name != '') {
                $locationText .= $city_name;
            }
            $state_array = worldStateInfo($city_array['country_code'], $city_array['state_code']);
            $state_name = $state_array['state_name'];
            if ($state_name != '') {
                $locationText .= ', ' . $state_name;
            }
            $country_array = countryGetInfo($city_array['country_code']);
            $country_name = $country_array['name'];
            if ($country_name != '') {
                $locationText .= ', ' . $country_name;
            }
        } else {
            $locationText = $hotel_data['location'];
        }
        if ($locationText == '') {
            $locationText = $hotel_data['address'];
        }else if ($hotel_data['address'] != '') {
            $locationText .= '<br>'.$hotel_data['address'];
        }

        $media_hotels = getPOIDefaultPic($entity_id);
        if( $media_hotels ){
        $image = $link . 'thumb/' . $media_hotels['img'];
        $bigim=$media_hotels['img'];
        $for = strrpos($bigim, '_') + 1;
        $extbig = substr($bigim, $for);    
        if(file_exists( $theLink . 'media/discover/large/' .$extbig ) ){
            $image = $link . 'large/' . $extbig;
        }else{
            $image = $link . 'large/' . $media_hotels['img'];
        }
        }else{
            $image = ReturnLink('media/images/landmark-icon3.jpg');
        }
        
         /* Poi Mix  Rating*/
        if($user_id){
            $irating1 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_LANDMARK_FOODAVAILABLE);
            if(!$irating1) $irating1=0;
            $irating2 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_LANDMARK_STAIRS);
            if(!$irating2) $irating2=0;
            $irating3 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_LANDMARK_WHEELCHAIR);
            if(!$irating3) $irating3=0;
            $irating4 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_LANDMARK_PARKING);
            if(!$irating4) $irating4=0;
            $irating5 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_LANDMARK_BATHROOMFACILITIES);
            if(!$irating5) $irating5=0;
            $irating6 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_LANDMARK_STORAGE);
            if(!$irating6) $irating6=0;
        }
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_LANDMARK_FOODAVAILABLE,'val'=>$irating1,'name'=>_('Food available'));
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_LANDMARK_STAIRS,'val'=>$irating2,'name'=>_('Stairs, elevator'));
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_LANDMARK_WHEELCHAIR,'val'=>$irating3,'name'=>_('Wheelchair access'));
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_LANDMARK_PARKING,'val'=>$irating4,'name'=>_('Stroller parking'));
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_LANDMARK_BATHROOMFACILITIES,'val'=>$irating5,'name'=>_('Bathroom facilities'));
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_LANDMARK_STORAGE,'val'=>$irating6,'name'=>_('Lockers, storage'));
        
        /*Show poi Photos By User*/
        $media_hotelsi = array();
        if ($user_id) {
//           $query_hotelsi = "SELECT id , filename as img , poi_id FROM `discover_poi_images` WHERE poi_id = $entity_id AND user_id=$user_id ORDER BY id DESC";
           $query_hotelsi = "SELECT id , filename as img , poi_id FROM `discover_poi_images` WHERE poi_id = :Entity_id AND user_id=:User_id ORDER BY id DESC";
            $params5[] = array(  "key" => ":Entity_id",
                                 "value" =>$entity_id);
            $params5[] = array(  "key" => ":User_id",
                                 "value" =>$user_id);
            $select = $dbConn->prepare($query_hotelsi);
            PDO_BIND_PARAM($select,$params5);
            $ret_hotelsi    = $select->execute();
//            $ret_hotelsi = db_query($query_hotelsi);
            $row = $select->fetchAll();
//            while ($row = db_fetch_array($ret_hotelsi)) {
            foreach($row as $row_item){
                $bigim=$row_item['img'];
                $for = strrpos($bigim, '_') + 1;
                $extbig = substr($bigim, $for);    
                if(file_exists( $theLink . 'media/discover/large/' .$extbig ) ){
                    $row_item['discover_imgbig'] = $link . 'large/' . $extbig;
                }else{
                    $row_item['discover_imgbig'] = $link . 'large/' . $row_item['img'];
                }
                $media_hotelsi[] = $row_item;
            }
        }
        /*Show restaurant Photos By Other User*/
        $user_media_hotelsi = array();
        if($user_id){
//            $query_hotelsi1 = "SELECT id , filename as img , poi_id FROM `discover_poi_images` WHERE poi_id = $entity_id AND user_id!=$user_id ORDER BY id DESC";
            $query_hotelsi1 = "SELECT id , filename as img , poi_id FROM `discover_poi_images` WHERE poi_id = :Entity_id AND user_id!=:User_id ORDER BY id DESC";
            $params6[] = array(  "key" => ":Entity_id",
                                 "value" =>$entity_id);
            $params6[] = array(  "key" => ":User_id",
                                 "value" =>$user_id);
        }
        else{
//            $query_hotelsi1 = "SELECT id , filename as img , poi_id FROM `discover_poi_images` WHERE poi_id = $entity_id ORDER BY id DESC";
            $query_hotelsi1 = "SELECT id , filename as img , poi_id FROM `discover_poi_images` WHERE poi_id = :Entity_id ORDER BY id DESC";
            $params6[] = array(  "key" => ":Entity_id",
                                 "value" =>$entity_id);
        }
	$select = $dbConn->prepare($query_hotelsi1);
	PDO_BIND_PARAM($select,$params6);
	$ret_hotelsi1    = $select->execute();
//        $ret_hotelsi1 = db_query($query_hotelsi1);
	$row = $select->fetchAll();
        foreach($row as $row_item){
//        while ($rows = db_fetch_array($ret_hotelsi1)) {
            $bigim=$row_item['img'];
            $for = strrpos($bigim, '_') + 1;
            $extbig = substr($bigim, $for);    
            if(file_exists( $theLink . 'media/discover/large/' .$extbig ) ){
                $row_item['discover_imgbig'] = $link . 'large/' . $extbig;
            }else{
                $row_item['discover_imgbig'] = $link . 'large/' . $row_item['img'];
            }
            $user_media_hotelsi[] = $row_item;
        }
        
         /*Share url for poi*/
        $poi_name_clean = cleanTitle($title);
//        $share_url = $uricurserver.'/things2do-review/id/'.$entity_id.'/'.$poi_name_clean;
        $share_url = $uricurserver.$poi_name_clean.'-review-T'.$entity_id;
        break;
        
        //for Airport
        
    case SOCIAL_ENTITY_AIRPORT:
         /*Average Rating */
//        $irating_average = socialRateAverage($entity_id, SOCIAL_ENTITY_AIRPORT);
        /*Nbr of reviews*/
        $nb_reviews = userReviewsList($entity_id, SOCIAL_ENTITY_AIRPORT, 6, 0, true);
        
        /* Airport information */
        $hotel_data = getAirportInfo($entity_id);
        $irating_average = socialRateAverage($entity_id, array($entity_type));
        $irating_average = ceil(($irating_average)*10)/10;
//        $irating_average = isset($hotel_data['avg_rating']) ? $hotel_data['avg_rating'] : 0;
        $nb_votes = isset($hotel_data['nb_votes']) ? $hotel_data['nb_votes'] : 0;
        
        $title = $hotel_data['name'];
        $lat = $hotel_data['latitude'];
        $long = $hotel_data['longitude'];
        $locationText = '';
        if ($hotel_data['city_id'] != '0') {
            $city_array = worldcitiespopInfo(intval($hotel_data['city_id']));
            $city_name = $city_array['name'];
            if ($city_name != '') {
                $locationText .= $city_name;
            }
            $state_array = worldStateInfo($city_array['country_code'], $city_array['state_code']);
            $state_name = $state_array['state_name'];
            if ($state_name != '') {
                $locationText .= ', ' . $state_name;
            }
            $country_array = countryGetInfo($city_array['country_code']);
            $country_name = $country_array['name'];
            if ($country_name != '') {
                $locationText .= ', ' . $country_name;
            }
        } else {
            $locationText = $hotel_data['location'];
        }
        if ($locationText == '') {
            $locationText = $hotel_data['address'];
        }else if ($hotel_data['address'] != '') {
            $locationText .= '<br>'.$hotel_data['address'];
        }

        $media_hotels = getAirportDefaultPic($entity_id);
        if( $media_hotels ){
            $image = $link . 'thumb/' . $media_hotels['img'];
            $bigim=$media_hotels['img'];
            $for = strrpos($bigim, '_') + 1;
            $extbig = substr($bigim, $for);    
            if(file_exists( $theLink . 'media/discover/large/' .$extbig ) ){
                $image = $link . 'large/' . $extbig;
            }else{
                $image = $link . 'large/' . $media_hotels['img'];
            }
        }else{
            $image = ReturnLink('media/images/airport-icon.jpg');
        }
        
         /* Airport Mix  Rating*/
        $irating1 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_AIRPORT_RECEPTION);
        if(!$irating1) $irating1=0;
        $irating2 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_AIRPORT_LOUNGE);
        if(!$irating2) $irating2=0;
        $irating3 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_AIRPORT_LUGGAGE);
        if(!$irating3) $irating3=0;
        $irating4 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_AIRPORT_FOOD);
        if(!$irating4) $irating4=0;
        $irating5 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_AIRPORT_DUTYFREE);
        if(!$irating5) $irating5=0;
        //$irating6 = socialRated($user_id, $entity_id, SOCIAL_ENTITY_LANDMARK_STORAGE);
        //if(!$irating6) $irating6=0;

        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_AIRPORT_RECEPTION,'val'=>$irating1,'name'=>_('Reception'));
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_AIRPORT_LOUNGE,'val'=>$irating2,'name'=>_('Lounge'));
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_AIRPORT_LUGGAGE,'val'=>$irating3,'name'=>_('Luggage service'));
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_AIRPORT_FOOD,'val'=>$irating4,'name'=>_('Food'));
        $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_AIRPORT_DUTYFREE,'val'=>$irating5,'name'=>_('Duty free'));
       // $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_LANDMARK_STORAGE,'val'=>$irating6,'name'=>_('Lockers, storage'));
        
        /*Show Airport Photos By User*/
        $media_hotelsi = array();
        if ($user_id) {
//           $query_hotelsi = "SELECT id , filename as img , airport_id FROM `airport_images` WHERE airport_id = $entity_id AND user_id=$user_id ORDER BY id DESC";
           $query_hotelsi = "SELECT id , filename as img , airport_id FROM `airport_images` WHERE airport_id = :Entity_id AND user_id=:User_id ORDER BY id DESC";
            $params7[] = array(  "key" => ":Entity_id",
                                 "value" =>$entity_id);
            $params7[] = array(  "key" => ":User_id",
                                 "value" =>$user_id);
           
//            $ret_hotelsi = db_query($query_hotelsi);
            $select = $dbConn->prepare($query_hotelsi);
            PDO_BIND_PARAM($select,$params7);
            $ret_hotelsi    = $select->execute();
            $row = $select->fetchAll();
//            while ($row = db_fetch_array($ret_hotelsi)) {
            foreach($row as $row_item){
                $bigim=$row_item['img'];
                $for = strrpos($bigim, '_') + 1;
                $extbig = substr($bigim, $for);    
                if(file_exists( $theLink . 'media/discover/large/' .$extbig ) ){
                    $row_item['discover_imgbig'] = $link . 'large/' . $extbig;
                }else{
                    $row_item['discover_imgbig'] = $link . 'large/' . $row_item['img'];
                }
                $media_hotelsi[] = $row_item;
            }
        }
        
        /*Show Airport Photos By Other User*/
        $user_media_hotelsi = array();
		if($user_id){
//        $query_hotelsi1 = "SELECT id , filename as img , airport_id FROM `airport_images` WHERE airport_id = $entity_id ORDER BY id DESC";
			$query_hotelsi1 = "SELECT id , filename as img , airport_id FROM `airport_images` WHERE airport_id = :Entity_id AND user_id!=:User_id ORDER BY id DESC";
            $params8[] = array(  "key" => ":Entity_id",
                                 "value" =>$entity_id);
			$params8[] = array(  "key" => ":User_id",
								"value" =>$user_id);
		}
		else{
            $query_hotelsi1 = "SELECT id , filename as img , airport_id FROM `airport_images` WHERE airport_id = :Entity_id ORDER BY id DESC";
            $params8[] = array(  "key" => ":Entity_id",
                                 "value" =>$entity_id);
        }
//            $ret_hotelsi = db_query($query_hotelsi);
        $select = $dbConn->prepare($query_hotelsi1);
        PDO_BIND_PARAM($select,$params8);
        $ret_hotelsi1    = $select->execute();
//        $ret_hotelsi1 = db_query($query_hotelsi1);
        $row = $select->fetchAll();
//        while ($rows = db_fetch_array($ret_hotelsi1)) {
        foreach($row as $row_item){
            $bigim=$row_item['img'];
            $for = strrpos($bigim, '_') + 1;
            $extbig = substr($bigim, $for);    
            if(file_exists( $theLink . 'media/discover/large/' .$extbig ) ){
                $row_item['discover_imgbig'] = $link . 'large/' . $extbig;
            }else{
                $row_item['discover_imgbig'] = $link . 'large/' . $row_item['img'];
            }
            $user_media_hotelsi[] = $row_item;
        }
        
         /*Share url for airport*/
        $airport_name_clean = cleanTitle($title);
//        $share_url = $uricurserver.'/airport-review/id/'.$entity_id.'/'.$airport_name_clean;
        $share_url = $uricurserver.$airport_name_clean.'-review-A'.$entity_id;
        break;
        
        //Devendra
    }    
    $locationText = html_entity_decode($locationText, ENT_QUOTES | ENT_XHTML, 'UTF-8');
    $title = html_entity_decode($title, ENT_QUOTES | ENT_XHTML, 'UTF-8');
    /*All info*/
    $info = array();
    $info[]=array(
            'title'=>$title,
            'address'=>$locationText,
            'image'=>$image,
            'lat'=>$lat,
            'lng'=>$long,
            
    );
    /*All Mix Rating*/
    foreach ( $toRateArr as $toRate )
    {              
        $res[] =array(
                'entity_type'=>$toRate['entity_type'],
                'rating_value'=>$toRate['val'] ? $toRate['val'] : 0,
                'name'=>$toRate['name'],
            );
    }
    $media=array();
    /*All Media*/
    foreach ( $media_hotelsi as $media_hotels )    
    {
        $media[] =array(
            'id'=>$media_hotels['id'],
            'img'=>"media/discover/thumb/".$media_hotels['img'],
            'discover_imgbig'=>$media_hotels['discover_imgbig']
        );
    }
    $user_media=array();
    foreach ( $user_media_hotelsi as $user_media_hotels )   
    {
        $user_media[] =array(
            'id'=>$user_media_hotels['id'],
            'img'=>"media/discover/thumb/".$user_media_hotels['img'],
            'discover_imgbig'=>$user_media_hotels['discover_imgbig']
        );
    }
    if($user_id){
        $is_bag = checkUserBagItem($user_id, $entity_type , $entity_id );
        if($is_bag){
            $is_bag = "1";
        }
    }
    else{
        $is_bag = "0";
    }
    

    
    $result =array(
        'info'=>$info,
        'global_rating'=>$irating,
        'average_rating'=>$irating_average,
        'nb_reviews'=> $nb_reviews,
        'nb_votes'=>$nb_votes,
        'mix_rating'=>$res,
        'photos'=>$media,
        'user_photos'=>$user_media,
        'share_url'=>$share_url,
        'added_to_bag'=>$is_bag,
        'phone'=>$phone
    );
   echo json_encode($result);