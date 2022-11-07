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
    'cache' => $theLink . 'twig_cache/', 'debug' => true,
));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('bag.twig');


$user_id = userGetID();
$includes = array('css/bagStyle.css', 'js/bag.js');
tt_global_set('includes', $includes);

$bag_code = intval(UriGetArg(0));
$bag_code = isset($bag_code) ? $bag_code : 0;
if( !userIsLogged() ){
    header('Location:' . ReturnLink('/') );
}
include($path . "twig_parts/topHeader.php");
global $dbConn;
$params = array(); 
$data['insideSearch'] = 1;


$briefcases = '';
$briefcases_share = '';
$bag_name = '';
$is_owner = 1;
if($bag_code>0){
    $owner_id = socialEntityOwner(SOCIAL_ENTITY_BAG,$bag_code);
    if($user_id != $owner_id){ 
        $is_owner = 0;
    }
}
$bagsList=array();
$i = 0;
if($is_owner == 0){
    $options = array(
        'limit' => null,
        'entity_id' => $bag_code,
        'entity_type' => SOCIAL_ENTITY_BAG,
        'share_type' => SOCIAL_SHARE_TYPE_SHARE,
        'published' => 1
    );
    $share_list = socialSharesGet($options);

    $tuber_ids = array();        
    foreach($share_list as $share_item){
        $u_list = getSharedUsersList($share_item['id']);
        if($u_list){
            $tuber_ids = array_merge( $u_list , $tuber_ids );
        }
    }
    if(!in_array( $user_id ,$tuber_ids)) header('Location:' . ReturnLink('/') );
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
        $data['bagCity'] = $city_info['name'];
    }else if($state_code!=''){        
        $state_info = worldStateInfo($country_code,$state_code);
        $state_name = $state_info['state_name'];        
        $bag_name = $state_name;        
        $country_info = countryGetInfo($country_code);        
        $country_name = $country_info['name'];
        $bag_name .= ' - '.$country_name;
        $data['bagCity'] = $country_info['name'];
    }else if($country_code!=''){
        $country_info = countryGetInfo($country_code);        
        $country_name = $country_info['name'];
        $bag_name = $country_name;
        $data['bagCity'] = $country_info['name'];
    }
    $bag_id = $bag_Info['id'];
    $bguser_id = $bag_Info['user_id'];
    $userBagItemsCount = userBagItemsCount($bag_Info['user_id'],$country_code,$state_code,$city_id);
    $data['bagCount'] = $userBagItemsCount;
    $data['bagName'] = $bag_name;
}else{
    $bguser_id = $user_id;
    $bagsList = userBagList($bguser_id);    
}

foreach($bagsList as $bag){
    $country_code = $bag['country_code'];
    $state_code = $bag['state_code'];
    $city_id = $bag['city_id'];
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
        if($i == 0) $data['bagCity'] = $city_info['name'];
    }else if($state_code!=''){        
        $state_info = worldStateInfo($country_code,$state_code);
        $state_name = $state_info['state_name'];        
        $bag_name = $state_name;        
        $country_info = countryGetInfo($country_code);        
        $country_name = $country_info['name'];
        $bag_name .= ' - '.$country_name;
        if($i == 0) $data['bagCity'] = $country_info['name'];
    }else if($country_code!=''){
        $country_info = countryGetInfo($country_code);        
        $country_name = $country_info['name'];
        $bag_name = $country_name;
        if($i == 0) $data['bagCity'] = $country_info['name'];
    }else{
        continue;
    }
    if($i == 0){
        $bag_id = $bag['id'];
        $userBagItemsCount = userBagItemsCount($bguser_id,$country_code,$state_code,$city_id);
        $data['bagCount'] = $userBagItemsCount;
        $data['bagName'] = $bag_name;
    }
    $briefcases .= '<option value="'.$bag['id'].'">'.$bag_name.'</option>';
    
    $i++;
}
    $BagItm = userBagItemsList($bguser_id,$bag_id);
    foreach($BagItm as $each){
        $params=array();
        $bag_object=array();
        $locationText ='';
        $item_id = $each['item_id'];
        $params[] = array(  "key" => ":Item_id", "value" =>$item_id);
        if($each['type'] == SOCIAL_ENTITY_RESTAURANT){
            $query_restaurants = "SELECT $each[0] as bag, id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = :Item_id and filename != '' ORDER BY default_pic DESC LIMIT 1";
            $select11 = $dbConn->prepare($query_restaurants);
            PDO_BIND_PARAM($select11,$params);
            $ret_restaurants    = $select11->execute();
            $rows_res = $select11->fetch();
            $rows_res['bag'] = $each[0];
            $media_resto = $rows_res;
            $query = "SELECT h.*, d.image as dimage FROM `global_restaurants` as h LEFT JOIN cms_thingstodo_details as d on d.entity_id=:Item_id AND d.entity_type=".SOCIAL_ENTITY_RESTAURANT." WHERE h.id = :Item_id LIMIT 1";
            $select12 = $dbConn->prepare($query);
            PDO_BIND_PARAM($select12,$params);
            $retR    = $select12->execute();
            $restaurants = $select12->fetch();
            
            $page_link = returnRestaurantReviewLink($restaurants['id'],htmlEntityDecode($restaurants['name']));
            $bg_name = htmlEntityDecode($restaurants['name']);
            if ( intval($restaurants['city_id']) >0) {
                $city_array = worldcitiespopInfo(intval($restaurants['city_id']));
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
                $city_name = $restaurants['locality'];
                if ($city_name != '') {
                    $locationText .= $city_name;
                }
                $region_name = $restaurants['region'];
                $admin_region_name = $restaurants['admin_region'];
                if ($region_name != '' && $region_name !=$city_name ) {
                    $locationText .= ', ' . $region_name;
                }else if ($admin_region_name != '' && $admin_region_name !=$city_name) {
                    $locationText .= ', ' . $admin_region_name;
                }
                $country_cd = $restaurants['country'];
                if ($country_cd != '') {
                    $country_array = countryGetInfo($country_cd);
                    $country_name = $country_array['name'];
                    if ($country_name != '') {
                        $locationText .= ', ' . $country_name;
                    }
                }
            }
            if ($locationText == '') {
                $locationText = $restaurants['address'];
            }else if ($restaurants['address'] != '') {
                $locationText .= '<br>'.$restaurants['address'];
            }
            $cityName = $locationText;
            if( !is_null($restaurants['dimage']) && $restaurants['dimage'] && $restaurants['dimage']!='' ){
                $dimage = ReturnLink("media/videos/uploads/thingstodo/".$restaurants['dimage']);
            }else if( $media_resto['img'] && $media_resto['img']!=''){
                $dimage = $link.'thumb/'.$media_resto['img'];
            }else{
                $dimage = ReturnLink('images/restaurant-icon1.jpg');
            }    
            $bag = $media_resto['bag'];
            $icon = ReturnLink('images/food-eat-icon.png');

        }else if($each['type'] == SOCIAL_ENTITY_HOTEL){
            $query_hotels = "SELECT $each[0] as bag,id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = :Item_id and filename != '' ORDER BY default_pic DESC LIMIT 1";
            $select14 = $dbConn->prepare($query_hotels);
            PDO_BIND_PARAM($select14,$params);
            $ret_hotels    = $select14->execute();
            $rows_res = $select14->fetch();
            $rows_res['bag'] = $each[0];
            $media_hotels = $rows_res;

            $query = "SELECT h.*, d.image as dimage FROM `discover_hotels` as h LEFT JOIN cms_thingstodo_details as d on d.entity_id=:Item_id AND d.entity_type=".SOCIAL_ENTITY_HOTEL." WHERE h.id = :Item_id LIMIT 1";
            $select15 = $dbConn->prepare($query);
            PDO_BIND_PARAM($select15,$params);
            $retH    = $select15->execute();
            $hotels = $select15->fetch();
            
            $page_link = returnHotelReviewLink($hotels['id'],htmlEntityDecode($hotels['hotelName']));
            $bg_name = htmlEntityDecode($hotels['hotelName']);

            if ($hotels['city_id'] != '0') {
                $city_array = worldcitiespopInfo(intval($hotels['city_id']));
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
                $locationText .= '<br/>'.$hotels['address'];
            } else {
                $locationText = $hotels['location'];
            }
            if($locationText=='') $locationText = $hotels['address'];
            $cityName = $locationText;          
            if( !is_null($hotels['dimage']) && $hotels['dimage'] && $hotels['dimage']!='' ){
                $dimage = ReturnLink("media/videos/uploads/thingstodo/".$hotels['dimage']);
            }else if( $media_hotels['img'] && $media_hotels['img']!=''){
                $dimage =  $link.'thumb/'.$media_hotels['img'];
            }else{
                $dimage = ReturnLink('images/hotel-icon-image1.jpg');
            }
            $bag =  $media_hotels['bag'];
            $icon =  ReturnLink('images/placestostay_icon.png');
        }else if($each['type'] == SOCIAL_ENTITY_LANDMARK){
            $query_poi = "SELECT $each[0] as bag, id , filename as img , poi_id FROM `discover_poi_images` WHERE poi_id = :Item_id ORDER BY default_pic DESC LIMIT 1";
            $select17 = $dbConn->prepare($query_poi);
            PDO_BIND_PARAM($select17,$params);
            $ret_poi    = $select17->execute();
            $rows_res = $select17->fetch();
            $rows_res['bag'] = $each[0];
            $media_poi = $rows_res;

            $query = "SELECT h.*, d.image as dimage FROM `discover_poi` as h LEFT JOIN cms_thingstodo_details as d on d.entity_id=:Item_id AND d.entity_type=".SOCIAL_ENTITY_LANDMARK." WHERE h.id = :Item_id LIMIT 1";
            $select18 = $dbConn->prepare($query);
            PDO_BIND_PARAM($select18,$params);
            $retP    = $select18->execute();
            $poi = $select18->fetch();
            
            $page_link = returnThingstodoReviewLink($poi['id'],htmlEntityDecode($poi['name']));
            $bg_name = htmlEntityDecode($poi['name']);
            
            $poi_cityName = $poi['cityName'];
            if( !is_null($poi['dimage']) && $poi['dimage'] && $poi['dimage']!='' ){
                $dimage = ReturnLink("media/videos/uploads/thingstodo/".$poi['dimage']);
            }else if( $media_poi['img'] && $media_poi['img']!=''){
                $dimage =  $link.'thumb/'.$media_poi['img'];
            }else{
                $dimage = ReturnLink('images/landmark-icon1.jpg');
            }

            if ($poi['city_id'] != '0') {
                $city_array = worldcitiespopInfo(intval($poi['city_id']));
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
                $locationText = $poi['address'];
            }
            if ($locationText == '') {
                $locationText = $poi['address'];
            }else if ($poi['address'] != '') {
                $locationText .= '<br>'.$media_poi['address'];
            }
            $cityName =  $locationText;
            $bag = $media_poi['bag'];
            $icon =  ReturnLink('images/thingstodo-icon.png');
    
        }else if($each['type'] == SOCIAL_ENTITY_EVENTS){
            $arrEventDetails   = channelEventInfo($item_id,-1);
            $arrEventDetails['bag'] = $each['id'];
            $event_item = $arrEventDetails;
            
            $icon =  ReturnLink('images/event-icon.png');
            $page_link = ReturnLink('channel-events-detailed/'.$event_item['id']);
            $dimage = ($event_item['photo']) ? photoReturneventImage($event_item) : ReturnLink('images/channel/eventthemephoto.jpg');
            $bg_name = htmlEntityDecode($event_item['name']);
            
            $cityName= htmlEntityDecode($event_item['location']);
            $bag = $event_item['bag'];
        }
        
        if (strlen($bg_name) > 40) {
            $bg_name = cut_sentence_length($bg_name, 40, 12);
        }
        $bag_object['name'] = $bg_name;
        $bag_object['bag']= $bag;
        $bag_object['icon']= $icon;
        $bag_object['img']= $dimage;
        $bag_object['page_link']= $page_link;
        $bag_object['cityName']= $cityName;
        $poi_Array[] = $bag_object;
    }
if($is_owner==1){  
    $options = array(
        'type' => array(1) , 
        'userid' => $bguser_id ,
        'n_results'=> true
    );
    $friend_array_count = userFriendSearch($options);

    $options = array(
        'userid' => $bguser_id,
        'n_results'=> true
    );
    $followers_array_count = userSubscriberSearch($options);
    $data['friend_array_count'] =   $friend_array_count;
    $data['followers_array_count'] =   $followers_array_count;
    $action_text = 'Share %s bag';
    $action_array=array();
    $action_array[]=$data['bagCity'];
    $action_text_display = vsprintf(_($action_text), $action_array);
    $data['sharetext'] = $action_text_display;
}
$data['briefcases'] = $briefcases;
$data['RESTAURANT'] = SOCIAL_ENTITY_RESTAURANT ;
$data['HOTEL'] = SOCIAL_ENTITY_HOTEL ;
$data['LANDMARK'] = SOCIAL_ENTITY_LANDMARK ;
$data['SOCIAL_ENTITY_EVENTS'] = SOCIAL_ENTITY_EVENTS ;
$data['MAP'] = 1 ;
$data['poi_Array']   =   $poi_Array;
$data['bag_id'] = $bag_id;
$data['is_owner'] = $is_owner;
$data['show_on_map']     =   ReturnLink('parts/show-on-map.php?type=b&id=' .$bag_id);

include($path . "twig_parts/footer.php");
echo $template->render($data);