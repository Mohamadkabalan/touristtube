<?php
$path = "";

$bootOptions = array("loadDb" => 1, "loadLocation" => 1, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/functions/bag.php" );
include_once ( $path . "inc/twigFct.php" );

$link = ReturnLink('media/discover') . '/';
//$link = '/backend/uploads/';

$theLink = $CONFIG ['server']['root'];
//require_once $theLink . 'vendor/autoload.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($theLink . 'twig_templates/');
$twig = new Twig_Environment($loader, array(
    'cache' => $theLink . 'twig_cache/', 'debug' => false,
));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('bagTwig.twig');

$user_id = userGetID();
$includes = array('css/discover.css', 'js/bag.js','media'=>'css_media_query/media_style_static_page.css', 'media1'=>'css_media_query/things_to_do_bag.css');


$bag_code = intval(UriGetArg(0));
$bag_code = isset($bag_code) ? $bag_code : 0;

if( !userIsLogged() ){
    header('Location:' . ReturnLink('/') );
}

global $dbConn;
$params = array();     


$restaurants = array();
$hotels = array();
$todos = array();
$media_hotels = array();
$media_resto = array();
$media_poi = array();
$media_channel_event = array();
$poi = array();
$briefcases = '';
$briefcases_share = '';
$is_owner = 1;
if($bag_code>0){
    $owner_id = socialEntityOwner(SOCIAL_ENTITY_BAG,$bag_code);
    if($user_id != $owner_id){ 
        $is_owner = 0;
    }
}

if($bag_code==0 || $is_owner==1){
    $all = userBagList($user_id);
    foreach($all as $eachBag){
        $country_code = $eachBag['country_code'];
        $state_code = $eachBag['state_code'];
        $city_id = $eachBag['city_id'];
        $bag_name = '';
        if($city_id!=0){
            $city_info = worldcitiespopInfo($city_id);
            $bag_name = $city_info['name'];
            $country_code = strtoupper($city_info['country_code']);
            $state_code = $city_info['state_code'];        
            $state_info = worldStateInfo($country_code,$state_code);
            $state_name = $state_info['state_name'];        
            $bag_name .= ' - '.$state_name;        
            $country_info = countryGetInfo($country_code);        
            $country_name = $country_info['name'];
            $bag_name .= ' - '.$country_name;
        }else if($state_code!=''){        
            $state_info = worldStateInfo($country_code,$state_code);
            $state_name = $state_info['state_name'];        
            $bag_name = $state_name;        
            $country_info = countryGetInfo($country_code);        
            $country_name = $country_info['name'];
            $bag_name .= ' - '.$country_name;
        }else if($country_code!=''){
            $country_info = countryGetInfo($country_code);        
            $country_name = $country_info['name'];
            $bag_name = $country_name;
        }else{
            continue;
        }
        if($eachBag['id']==$bag_code){
            $briefcases .= '<option value="'.$eachBag['id'].'" selected="selected">'.$bag_name.'</option>';
            $briefcases_share .= '<option value="'.$eachBag['id'].'" selected="selected">'.$bag_name.'</option>'; 
        }else{
            $briefcases .= '<option value="'.$eachBag['id'].'">'.$bag_name.'</option>';
            $briefcases_share .= '<option value="'.$eachBag['id'].'">'.$bag_name.'</option>'; 
        }        
        $BagItm = userBagItemsList($user_id,$eachBag['id']);
        foreach($BagItm as $each){
            $params = array();
            $item_id = $each['item_id'];
            if($each['type'] == SOCIAL_ENTITY_RESTAURANT){
//                $query_reviews = "SELECT COUNT(id) FROM `discover_restaurants_reviews` WHERE restaurant_id = $item_id AND published=1";
                $query_reviews = "SELECT COUNT(id) FROM `discover_restaurants_reviews` WHERE restaurant_id = :Item_id AND published=1";
                $params[] = array(  "key" => ":Item_id",
                                    "value" =>$item_id);
//                $retquery_reviews_count = db_query($query_reviews);
                $select = $dbConn->prepare($query_reviews);
                PDO_BIND_PARAM($select,$params);
                $retquery_reviews_count    = $select->execute();
//                $row_reviews_count = db_fetch_array($retquery_reviews_count);
                $row_reviews_count = $select->fetch();
                $n_results = $row_reviews_count[0];

                $query_restaurants = "SELECT $each[0] as bag,$n_results as review, id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = :Item_id and filename != '' ORDER BY default_pic DESC LIMIT 1";                
                $select2 = $dbConn->prepare($query_restaurants);
                PDO_BIND_PARAM($select2,$params);
                $ret_restaurants    = $select2->execute();
                
                $rows_res = $select2->fetch();
                $rows_res['bag'] = $each[0];
                $media_resto[] = $rows_res;
                
                $query = "SELECT h.*, d.image as dimage FROM `global_restaurants` as h LEFT JOIN cms_thingstodo_details as d on d.entity_id=:Item_id AND d.entity_type=".SOCIAL_ENTITY_RESTAURANT." WHERE h.id = :Item_id LIMIT 1";
                $select3 = $dbConn->prepare($query);
                PDO_BIND_PARAM($select3,$params);
                $retR    = $select3->execute();
                $restaurants[] = $select3->fetch();
            }else if($each['type'] == SOCIAL_ENTITY_HOTEL){
                $query_reviews = "SELECT COUNT(id) FROM `discover_hotels_reviews` WHERE hotel_id = :Item_id AND published=1";
                $params[] = array(  "key" => ":Item_id",
                                    "value" =>$item_id);
                $select4 = $dbConn->prepare($query_reviews);
                PDO_BIND_PARAM($select4,$params);
                $retquery_reviews_count    = $select4->execute();
                $row_reviews_count = $select4->fetch();
                $n_results = $row_reviews_count[0];

                $query_hotels = "SELECT $each[0] as bag,$n_results as review, id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = :Item_id and filename != '' ORDER BY default_pic DESC LIMIT 1";
                
                $select5 = $dbConn->prepare($query_hotels);
                PDO_BIND_PARAM($select5,$params);
                $ret_hotels    = $select5->execute();
                $rows_res = $select5->fetch();
                $rows_res['bag'] = $each[0];
                $media_hotels[] = $rows_res;                
                
                $query = "SELECT h.*, d.image as dimage FROM `discover_hotels` as h LEFT JOIN cms_thingstodo_details as d on d.entity_id=:Item_id AND d.entity_type=".SOCIAL_ENTITY_HOTEL." WHERE h.id = :Item_id LIMIT 1";
                $select6 = $dbConn->prepare($query);
                PDO_BIND_PARAM($select6,$params);
                $retH    = $select6->execute();
                $hotels[] = $select6->fetch();
            }else if($each['type'] == SOCIAL_ENTITY_LANDMARK){
//                $query_reviews = "SELECT COUNT(id) FROM `discover_poi_reviews` WHERE poi_id = $item_id AND published=1";
                $query_reviews = "SELECT COUNT(id) FROM `discover_poi_reviews` WHERE poi_id = :Item_id AND published=1";
                $params[] = array(  "key" => ":Item_id",
                                    "value" =>$item_id);
                $select7 = $dbConn->prepare($query_reviews);
                PDO_BIND_PARAM($select7,$params);
                $retquery_reviews_count    = $select7->execute();
//                $retquery_reviews_count = db_query($query_reviews);
//                $row_reviews_count = db_fetch_array($retquery_reviews_count);
                $row_reviews_count = $select7->fetch();
                $n_results = $row_reviews_count[0];

                $query_poi = "SELECT $each[0] as bag,$n_results as review, id , filename as img , poi_id FROM `discover_poi_images` WHERE poi_id = :Item_id ORDER BY default_pic DESC LIMIT 1";
                $select8 = $dbConn->prepare($query_poi);
                PDO_BIND_PARAM($select8,$params);
                $ret_poi    = $select8->execute();
                $rows_res = $select8->fetch();
                $rows_res['bag'] = $each[0];
                $media_poi[] = $rows_res;

                $query = "SELECT h.*, d.image as dimage FROM `discover_poi` as h LEFT JOIN cms_thingstodo_details as d on d.entity_id=:Item_id AND d.entity_type=".SOCIAL_ENTITY_LANDMARK." WHERE h.id = :Item_id LIMIT 1";
                $select9 = $dbConn->prepare($query);
                PDO_BIND_PARAM($select9,$params);
                $retP    = $select9->execute();
                $poi[] = $select9->fetch();
            }else if($each['type'] == SOCIAL_ENTITY_EVENTS){
                $arrEventDetails   = channelEventInfo($item_id,-1);
                $arrEventDetails['bag'] = $each['id'];
                $media_channel_event[] = $arrEventDetails;
            }
        }
    }
}else{
    if($user_id != $owner_id){    
        $options = array(
            'limit' => null,
            'entity_id' => $bag_code,
            'entity_type' => SOCIAL_ENTITY_BAG,
            'share_type' => SOCIAL_SHARE_TYPE_SHARE,
            'published' => 1
        );
        $share_list = socialSharesGet($options);
        
        $tuber_ids = array(42,44);        
        foreach($share_list as $share_item){
            $u_list = getSharedUsersList($share_item['id']);
            if($u_list){
                $tuber_ids = array_merge( $u_list , $tuber_ids );
            }
        }
        if(!in_array( $user_id ,$tuber_ids)) header('Location:' . ReturnLink('/') );
    }else{
      header('Location:' . ReturnLink('bag') );  
    }
    
    $bag_Info = bagInfo($bag_code);
    $country_code = $bag_Info['country_code'];
    $state_code = $bag_Info['state_code'];
    $city_id = $bag_Info['city_id'];
    $bag_name = '';
    if($city_id!=0){
        $city_info = worldcitiespopInfo($city_id);
        $bag_name = $city_info['name'];
        $country_code = strtoupper($city_info['country_code']);
        $state_code = $city_info['state_code'];        
        $state_info = worldStateInfo($country_code,$state_code);
        $state_name = $state_info['state_name'];        
        $bag_name .= ' - '.$state_name;        
        $country_info = countryGetInfo($country_code);        
        $country_name = $country_info['name'];
        $bag_name .= ' - '.$country_name;
    }else if($state_code!=''){        
        $state_info = worldStateInfo($country_code,$state_code);
        $state_name = $state_info['state_name'];        
        $bag_name = $state_name;        
        $country_info = countryGetInfo($country_code);        
        $country_name = $country_info['name'];
        $bag_name .= ' - '.$country_name;
    }else if($country_code!=''){
        $country_info = countryGetInfo($country_code);        
        $country_name = $country_info['name'];
        $bag_name = $country_name;
    }
    
    $BagItm = userBagItemsList($bag_Info['user_id'],$bag_code);
    foreach($BagItm as $each){
        $params=array();
        $item_id = $each['item_id'];
        $params[] = array(  "key" => ":Item_id", "value" =>$item_id);
        if($each['type'] == SOCIAL_ENTITY_RESTAURANT){
//            $query_reviews = "SELECT COUNT(id) FROM `discover_restaurants_reviews` WHERE restaurant_id = $item_id AND published=1";
            $query_reviews = "SELECT COUNT(id) FROM `discover_restaurants_reviews` WHERE restaurant_id = :Item_id AND published=1";
            $select10 = $dbConn->prepare($query_reviews);
            PDO_BIND_PARAM($select10,$params);
            $retquery_reviews_count    = $select10->execute();
//            $retquery_reviews_count = db_query($query_reviews);
//            $row_reviews_count = db_fetch_array($retquery_reviews_count);
            $row_reviews_count = $select10->fetch();
            $n_results = $row_reviews_count[0];

            $query_restaurants = "SELECT $each[0] as bag,$n_results as review, id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = :Item_id and filename != '' ORDER BY default_pic DESC LIMIT 1";
            $select11 = $dbConn->prepare($query_restaurants);
            PDO_BIND_PARAM($select11,$params);
            $ret_restaurants    = $select11->execute();
//            $ret_restaurants = db_query($query_restaurants);
            $rows_res = $select11->fetch();
            $rows_res['bag'] = $each[0];
            $media_resto[] = $rows_res;
            $query = "SELECT h.*, d.image as dimage FROM `global_restaurants` as h LEFT JOIN cms_thingstodo_details as d on d.entity_id=:Item_id AND d.entity_type=".SOCIAL_ENTITY_RESTAURANT." WHERE h.id = :Item_id LIMIT 1";
            $select12 = $dbConn->prepare($query);
            PDO_BIND_PARAM($select12,$params);
            $retR    = $select12->execute();
            $restaurants[] = $select12->fetch();
        }else if($each['type'] == SOCIAL_ENTITY_HOTEL){
//            $query_reviews = "SELECT COUNT(id) FROM `discover_hotels_reviews` WHERE hotel_id = $item_id AND published=1";
            $query_reviews = "SELECT COUNT(id) FROM `discover_hotels_reviews` WHERE hotel_id = :Item_id AND published=1";
            $select13 = $dbConn->prepare($query_reviews);
            PDO_BIND_PARAM($select13,$params);
            $retquery_reviews_count    = $select13->execute();
//            $retquery_reviews_count = db_query($query_reviews);
//            $row_reviews_count = db_fetch_array($retquery_reviews_count);
            $row_reviews_count = $select13->fetch();
            $n_results = $row_reviews_count[0];

            $query_hotels = "SELECT $each[0] as bag,$n_results as review, id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = :Item_id and filename != '' ORDER BY default_pic DESC LIMIT 1";
            $select14 = $dbConn->prepare($query_hotels);
            PDO_BIND_PARAM($select14,$params);
            $ret_hotels    = $select14->execute();
            $rows_res = $select14->fetch();
            $rows_res['bag'] = $each[0];
            $media_hotels[] = $rows_res;

            $query = "SELECT h.*, d.image as dimage FROM `discover_hotels` as h LEFT JOIN cms_thingstodo_details as d on d.entity_id=:Item_id AND d.entity_type=".SOCIAL_ENTITY_HOTEL." WHERE h.id = :Item_id LIMIT 1";
            $select15 = $dbConn->prepare($query);
            PDO_BIND_PARAM($select15,$params);
            $retH    = $select15->execute();
            $hotels[] = $select15->fetch();
        }else if($each['type'] == SOCIAL_ENTITY_LANDMARK){
//            $query_reviews = "SELECT COUNT(id) FROM `discover_poi_reviews` WHERE poi_id = $item_id AND published=1";
            $query_reviews = "SELECT COUNT(id) FROM `discover_poi_reviews` WHERE poi_id = :Item_id AND published=1";
            $select16 = $dbConn->prepare($query_reviews);
            PDO_BIND_PARAM($select16,$params);
            $retquery_reviews_count    = $select16->execute();
//            $retquery_reviews_count = db_query($query_reviews);
//            $row_reviews_count = db_fetch_array($retquery_reviews_count);
            $row_reviews_count = $select16->fetch();
            $n_results = $row_reviews_count[0];

            $query_poi = "SELECT $each[0] as bag,$n_results as review, id , filename as img , poi_id FROM `discover_poi_images` WHERE poi_id = :Item_id ORDER BY default_pic DESC LIMIT 1";
            $select17 = $dbConn->prepare($query_poi);
            PDO_BIND_PARAM($select17,$params);
            $ret_poi    = $select17->execute();
            $rows_res = $select17->fetch();
            $rows_res['bag'] = $each[0];
            $media_poi[] = $rows_res;

            $query = "SELECT h.*, d.image as dimage FROM `discover_poi` as h LEFT JOIN cms_thingstodo_details as d on d.entity_id=:Item_id AND d.entity_type=".SOCIAL_ENTITY_LANDMARK." WHERE h.id = :Item_id LIMIT 1";
            $select18 = $dbConn->prepare($query);
            PDO_BIND_PARAM($select18,$params);
            $retP    = $select18->execute();
            $poi[] = $select18->fetch();
        }else if($each['type'] == SOCIAL_ENTITY_EVENTS){
            $arrEventDetails   = channelEventInfo($item_id,-1);
            $arrEventDetails['bag'] = $each['id'];
            $media_channel_event[] = $arrEventDetails;
        }
    }
}
$data['bag_code'] =   $bag_code;
$data['is_owner'] =   $is_owner;
if($bag_code==0 || $is_owner==1 ){


    $baskets['bag1'] = array(
        'todo'=> array(3, 4, 5, 6),
        'hotel'=> array(5377, 2581, 2968, 4500),
        'restaurant'=> array(3, 4, 5)
    );
    $basket = $baskets['bag1'];
    $todos = getBagHotels($basket['hotel'], $conn);
    $options = array(
        'type' => array(1) , 
        'userid' => $user_id ,
        'n_results'=> true
    );
    $friend_array_count = userFriendSearch($options);

    $options = array(
        'userid' => $user_id,
        'n_results'=> true
    );
    $followers_array_count = userSubscriberSearch($options);


}
$data['briefcases'] =   $briefcases;
$data['briefcases_share'] =   $briefcases_share;
$data['friend_array_count'] =   $friend_array_count;
$data['followers_array_count'] =   $followers_array_count;
$data['bag_name'] =   $bag_name;

$media_channel_eventArray =   array();
foreach($media_channel_event as $event_item){
    $aMedia_channel_eventArray =   array();
    $aMedia_channel_eventArray['page_link']    =   $page_link = ReturnLink('channel-events-detailed/'.$event_item['id']);
    $aMedia_channel_eventArray['image_pic']    =   $image_pic = ($event_item['photo']) ? photoReturneventImage($event_item) : ReturnLink('images/channel/eventthemephoto.jpg');
    $event_name = htmlEntityDecode($event_item['name']);
    if (strlen($event_name) > 40) {
        $event_name = cut_sentence_length($event_name, 40, 12);
    }
    $aMedia_channel_eventArray['name']    =   $event_name;
    $aMedia_channel_eventArray['location']=   htmlEntityDecode($event_item['location']);
    $aMedia_channel_eventArray['bag']       =    $event_item['bag'];

    $media_channel_eventArray[] =   $aMedia_channel_eventArray;

}

$data['media_channel_eventArray'] =   $media_channel_eventArray;
$data['count_poi']  =   count($poi);
$data['poi']        =   $poi;
$data['media_resto'] =   $media_resto;
$data['link'] =   $link;

$poi_Array = array();
for($r=0;$r<count($poi);$r++){
    $poi_Array1 =   array();
    $page_link = returnThingstodoReviewLink($poi[$r]['id'],htmlEntityDecode($poi[$r]['name']));
    $poi_name = htmlEntityDecode($poi[$r]['name']);
    if (strlen($poi_name) > 40) {
        $poi_name = cut_sentence_length($poi_name, 40, 12);
    }
    $poi_cityName = $poi[$r]['cityName'];
    $poi_review = intval($media_poi[$r]['review']);
    if( !is_null($poi[$r]['dimage']) && $poi[$r]['dimage'] && $poi[$r]['dimage']!='' ){
        $poi_img = ReturnLink("media/videos/uploads/thingstodo/".$poi[$r]['dimage']);
    }else if( $media_poi[$r]['img'] && $media_poi[$r]['img']!=''){
        $poi_img =  $link.'thumb/'.$media_poi[$r]['img'];
    }else{
        $poi_img = ReturnLink('images/landmark-icon1.jpg');
    }
    $poi_bag = $media_poi[$r]['bag'];
    
    $poi_Array1['page_link'] = $page_link;
    $poi_Array1['name'] =  $poi_name;
    $poi_Array1['cityName'] =  $poi_cityName;
    if($poi_review>0) $poi_Array1['review'] =  displayReviewsCount($poi_review);
    else $poi_Array1['review'] ='';
    $poi_Array1['img'] =  $poi_img;
    $poi_Array1['bag'] =  $poi_bag;
    
    $poi_Array[] = $poi_Array1;
}
$data['page_link']  =   $page_link;
$data['poi_Array']  =   $poi_Array;
$data['hotels'] =   $hotels;
$data['count_hotels'] =   count($hotels);

$hotels_Arr = array();
for($h=0;$h<count($hotels);$h++){
    $hotels_Arr1 = array();
    $page_link = returnHotelReviewLink($hotels[$h]['id'],htmlEntityDecode($hotels[$h]['hotelName']));
    $hotels_Arr1['page_link'] = $page_link;
    $hotel_name = htmlEntityDecode($hotels[$h]['hotelName']);    
    if (strlen($hotel_name) > 32) {
        $hotel_name = cut_sentence_length($hotel_name, 32, 12);
    }
    $hotels_Arr1['hotelName'] = $hotel_name;
    $hotels_Arr1['cityName'] = $hotels[$h]['cityName'];
    
    $image_val='';
    for($s=0;$s<min($hotels[$h]['stars'],5);$s++){
        $image_val .= '<img src="'.ReturnLink('images/hotels/whiteStar.png').'" class="whitestar">';
    }
    $hotels_Arr1['image_val'] = $image_val;
    if(intval($media_hotels[$h]['review'])>0) $hotels_Arr1['review'] =  displayReviewsCount(intval($media_hotels[$h]['review']));
    else $hotels_Arr1['review'] ='';
    if( !is_null($hotels[$h]['dimage']) && $hotels[$h]['dimage'] && $hotels[$h]['dimage']!='' ){
        $hotels_Arr1['img'] = ReturnLink("media/videos/uploads/thingstodo/".$hotels[$h]['dimage']);
    }else if( $media_hotels[$h]['img'] && $media_hotels[$h]['img']!=''){
        $hotels_Arr1['img'] =  $link.'thumb/'.$media_hotels[$h]['img'];
    }else{
        $hotels_Arr1['img'] = ReturnLink('images/hotel-icon-image1.jpg');
    }
    $hotels_Arr1['bag'] =  $media_hotels[$h]['bag'];
    
    $hotels_Arr[] = $hotels_Arr1;
}
$data['hotels_Arr']   =   $hotels_Arr;
$data['page_link'] =   $page_linkArr;

$data['restaurants']   =   $restaurants;
$data['count_restaurants']    =   count($restaurants);

$restaurants_Arr = array();
for($r=0;$r<count($restaurants);$r++){
    $restaurants_Arr1 = array();
    $restaurants_Arr1['page_link'] = returnRestaurantReviewLink($restaurants[$r]['id'],htmlEntityDecode($restaurants[$r]['name']));
    $restaurant_name = htmlEntityDecode($restaurants[$r]['name']);
    if (strlen($restaurant_name) > 40) {
        $restaurant_name = cut_sentence_length($restaurant_name, 40, 12);
    }
    $restaurants_Arr1['name'] = $restaurant_name;
    
    if($restaurants[$r]['locality'] != ''){
        $restaurants_Arr1['cityName'] = $restaurants[$r]['locality'];   
    }elseif($restaurants[$r]['region'] == ''){
        $restaurants_Arr1['cityName'] = $restaurants[$r]['region']; 
    }else{
        $restaurants_Arr1['cityName'] = $restaurants[$r]['admin_region']; 
    }
    if( !is_null($restaurants[$r]['dimage']) && $restaurants[$r]['dimage'] && $restaurants[$r]['dimage']!='' ){
        $restaurants_Arr1['img'] = ReturnLink("media/videos/uploads/thingstodo/".$restaurants[$r]['dimage']);
    }else if( $media_resto[$r]['img'] && $media_resto[$r]['img']!=''){
        $restaurants_Arr1['img'] = $link.'thumb/'.$media_resto[$r]['img'];
    }else{
        $restaurants_Arr1['img'] = ReturnLink('images/restaurant-icon1.jpg');
    }    
    $restaurants_Arr1['bag'] = $media_resto[$r]['bag'];
    if(intval($media_resto[$r]['review'])>0) $restaurants_Arr1['review'] = displayReviewsCount(intval($media_resto[$r]['review']));
    else $restaurants_Arr1['review'] ='';
    
    $restaurants_Arr[] = $restaurants_Arr1;
}
$data['restaurants_Arr']   =   $restaurants_Arr;

if (userIsLogged() && userIsChannel()) {
     array_unshift($includes, 'css/channel-header.css');
    tt_global_set('includes', $includes);
    include($theLink . "twig_parts/_headChannel.php");
} else {
    tt_global_set('includes', $includes);
    include($theLink . "twig_parts/_head.php");
}

include($theLink . "twig_parts/_foot.php");
echo $template->render($data);
