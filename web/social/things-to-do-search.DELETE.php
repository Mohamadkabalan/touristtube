<?php

if (!isset($bootOptions)) {
    $path = "";

    $bootOptions = array("loadDb" => 1, "loadLocation" => 1, "requireLogin" => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );

    include_once ( $path . "inc/functions/videos.php" );
    include_once ( $path . "inc/functions/users.php" );
    include_once ( $path . "inc/functions/bag.php" );
}
include_once ( $path . "inc/twigFct.php" );

$theLink = $CONFIG ['server']['root'];
//require_once $theLink . 'vendor/autoload.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($theLink . 'twig_templates/');
$twig = new Twig_Environment($loader, array(
    'cache' => $theLink . 'twig_cache/', 'debug' => true,
        ));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('thingsToDoStep.twig');

$user_is_logged = 0;
if (userIsLogged()) {
    $user_is_logged = 1;
}
$user_id = userGetID();
ob_clean();
$limit=12;
$page=0;
$string_search="";
$city_search = intval( UriGetArg('C') );
$city_search = (isset($city_search) && $city_search>0) ? $city_search : 0;

$txt_code = UriGetArg('CO');
$txt_code = isset($txt_code) ? $txt_code : '';
if ($city_search == 0 && $txt_code=="") {
    header('Location:' . ReturnLink('things-to-do'));    
}
$diff_angle = 0.1;
if ($txt_code == 'LB') {
    $city_search = 2330805;
    $diff_angle = 0.2;
    $string_search = 'beirut';
}
$longitude_search0=null;
$longitude_search1=null;
$latitude_search0=null;
$latitude_search1=null;
if ( $city_search != 0 ){
    $city_info = worldcitiespopInfo($city_search);
    $string_search=$city_info['name'];
    $txt_code=$city_info['country_code'];
    $longitude = $city_info['longitude'];
    $latitude = $city_info['latitude'];
    if ($string_search == 'dubai') {
        $diff_angle = 0.4;
    } else if ($string_search == 'abu dhabi') {
        $diff_angle = 0.4;
    }
    
    $longitude_search0 = $longitude - $diff_angle;
    $longitude_search1 = $longitude + $diff_angle;
    $latitude_search0 = $latitude - $diff_angle;
    $latitude_search1 = $latitude + $diff_angle;
}
$row_count = getPoiList(array(
    'string_search' => $string_search,        
    'longitude_min' => $longitude_search0,        
    'longitude_max' => $longitude_search1,        
    'latitude_min' => $latitude_search0,        
    'latitude_max' => $latitude_search1,        
    'country' => $txt_code,        
    'city_id' => $city_search,
    'n_results'=>true
));
$tohideLoad = 0;
$pagcount = ceil($row_count / $limit);
if (($page + 1) == $pagcount || $pagcount == 0) {
    $tohideLoad = 1;
}
$tohideLoadclass = '';
if($tohideLoad==1){
    $tohideLoadclass = ' displaynone';
}
$data['tohideLoadclass'] = $tohideLoadclass;
$row = getPoiList(array(
    'limit' => $limit,
    'page' => $page,
    'string_search' => $string_search,        
    'longitude_min' => $longitude_search0,        
    'longitude_max' => $longitude_search1,        
    'latitude_min' => $latitude_search0,        
    'latitude_max' => $latitude_search1,        
    'country' => $txt_code,        
    'city_id' => $city_search
));

$PropertyList = array();
foreach($row as $row_item){
    $entity_name = htmlEntityDecode($row_item['name']);
    $id = $row_item['id'];
    $entity_link = returnThingstodoReviewLink($id,$entity_name);
    $nb_review = getReviewsCount(SOCIAL_ENTITY_LANDMARK, $id);
    $nb_reviewtext = displayReviewsCount($nb_review,1);
    if($nb_review==0) $nb_reviewtext='';
    if( $row_item['img']!='' ){
        $entity_pic=ReturnLink('media/discover/thumb/' . $row_item['img']);
    }else{
        $entity_pic=ReturnLink('images/landmark-icon1.jpg');;
    }    
    $entity_is_bag = checkUserBagItem($user_id, SOCIAL_ENTITY_LANDMARK, $id);
    $entity_is_bag = ($entity_is_bag)?1:0;
    $PropertyList[] = array("id"=>$id, "entity_type"=>SOCIAL_ENTITY_LANDMARK, "entity_link"=>$entity_link, "entity_pic"=>$entity_pic , "entity_name"=>$entity_name, "nb_reviewtext"=>$nb_reviewtext, "entity_is_bag"=>$entity_is_bag);
}
$data['PropertyList'] = $PropertyList;
$userBagItemsCount=0;
if($user_is_logged==1) $userBagItemsCount = userAllBagItemsCount($user_id);
if($userBagItemsCount>99) $userBagItemsCount='99+';
$action_array='<a class="bagcontainer_a bagcontainer_a'.$user_is_logged.'" href="'. ReturnLink('bag').'" title="'._('bag').'"><div class="bagcontainer"><div class="bag_count">'.$userBagItemsCount.'</div></div></a>';

$data['action_text'] = $action_array;

$options = array(
    'orderby' => 'rand',
    'order' => 'd',
    'status' => 3,
    'page' => 0,
    'longitude_min' => $longitude_search0,
    'longitude_max' => $longitude_search1,
    'latitude_min' => $latitude_search0,
    'latitude_max' => $latitude_search1,
    'is_visible' => 1,
    'limit' => 5
);
if ($txt_code != '') {
    $options['country']=$txt_code;
}
$map_events_data = channeleventSearch($options);
$map_events_data = (!$map_events_data)? array():$map_events_data;

$options = array(
    'status' => 3,
    'longitude_min' => $longitude_search0,
    'longitude_max' => $longitude_search1,
    'latitude_min' => $latitude_search0,
    'latitude_max' => $latitude_search1,
    'is_visible' => 1,
    'n_results' => true
);
if ($txt_code != '') {
    $options['country']=$txt_code;
}
$map_events_count = channeleventSearch($options);


$data_menu = array();
$data_menu[] = array("menu_title"=>"All","menu_label"=>"", "menu_class"=>"active" , "menu_count" => $row_count);
$cat_search = getPoiList(array(
    'string_search' => $string_search,        
    'longitude_min' => $longitude_search0,        
    'longitude_max' => $longitude_search1,        
    'latitude_min' => $latitude_search0,        
    'latitude_max' => $latitude_search1,
    'cat_search' => 'activities',      
    'country' => $txt_code,        
    'city_id' => $city_search,
    'n_results'=>true
));
$data_menu[] = array("menu_title"=>"Activities","menu_label"=>"activities", "menu_class"=>"" , "menu_count" => $cat_search);
$cat_search = getPoiList(array(
    'string_search' => $string_search,        
    'longitude_min' => $longitude_search0,        
    'longitude_max' => $longitude_search1,        
    'latitude_min' => $latitude_search0,        
    'latitude_max' => $latitude_search1,
    'cat_search' => 'shopping',      
    'country' => $txt_code,        
    'city_id' => $city_search,
    'n_results'=>true
));
$data_menu[] = array("menu_title"=>"Shopping","menu_label"=>"shopping", "menu_class"=>"" , "menu_count" => $cat_search);
$cat_search = getPoiList(array(
    'string_search' => $string_search,        
    'longitude_min' => $longitude_search0,        
    'longitude_max' => $longitude_search1,        
    'latitude_min' => $latitude_search0,        
    'latitude_max' => $latitude_search1,
    'cat_search' => 'entertainment',      
    'country' => $txt_code,        
    'city_id' => $city_search,
    'n_results'=>true
));
$data_menu[] = array("menu_title"=>"Entertainment","menu_label"=>"entertainment", "menu_class"=>"" , "menu_count" => $cat_search);
$cat_search = getPoiList(array(
    'string_search' => $string_search,        
    'longitude_min' => $longitude_search0,        
    'longitude_max' => $longitude_search1,        
    'latitude_min' => $latitude_search0,        
    'latitude_max' => $latitude_search1,
    'cat_search' => 'sights',      
    'country' => $txt_code,        
    'city_id' => $city_search,
    'n_results'=>true
));
$data_menu[] = array("menu_title"=>"Sights","menu_label"=>"sights", "menu_class"=>"" , "menu_count" => $cat_search);
$cat_search = getPoiList(array(
    'string_search' => $string_search,        
    'longitude_min' => $longitude_search0,        
    'longitude_max' => $longitude_search1,        
    'latitude_min' => $latitude_search0,        
    'latitude_max' => $latitude_search1,
    'cat_search' => 'transport',      
    'country' => $txt_code,        
    'city_id' => $city_search,
    'n_results'=>true
));
$data_menu[] = array("menu_title"=>"Transport","menu_label"=>"transport", "menu_class"=>"" , "menu_count" => $cat_search);
$cat_search = getPoiList(array(
    'string_search' => $string_search,        
    'longitude_min' => $longitude_search0,        
    'longitude_max' => $longitude_search1,        
    'latitude_min' => $latitude_search0,        
    'latitude_max' => $latitude_search1,
    'cat_search' => 'tours',      
    'country' => $txt_code,        
    'city_id' => $city_search,
    'n_results'=>true
));
$data_menu[] = array("menu_title"=>"Tours","menu_label"=>"tours", "menu_class"=>"" , "menu_count" => $cat_search);

$data_menu[] = array("menu_title"=>"Events","menu_label"=>"events", "menu_class"=>"" , "menu_count" => $map_events_count);

$data['data_menu'] = $data_menu;
$today = date('Y-m-d');
$data['data_date'] = returnSocialTimeFormat($time_date,3);

$string_search =strtolower($string_search);
$weather_woeid = "http://query.yahooapis.com/v1/public/yql?q=" . urlencode("select woeid from geo.places where text='$string_search' limit 1");
$file_woeid = @file_get_contents($weather_woeid, true);

if ($file_woeid != "") {
    $file_woeid8 = iconv(iconv_get_encoding($file_woeid), "UTF-8//TRANSLIT", $file_woeid);
    $xml_woeid = simplexml_load_string($file_woeid8);
    $woeid = $xml_woeid->results->place->woeid;
    $file = @file_get_contents('http://weather.yahooapis.com/forecastrss?w=' . $woeid . '&u=c');
}

if ($file != "") {
    $file8 = iconv(iconv_get_encoding($file), "UTF-8//TRANSLIT", $file);
    $xml = simplexml_load_string($file8);
    $today = $xml->channel->item->children('yweather', TRUE)->condition->attributes()->temp;
    $code = $xml->channel->item->children('yweather', TRUE)->condition->attributes()->code;
    $forecast='';
    foreach ($condition = $xml->channel->item->children('yweather', TRUE)->forecast as $forecast) {
    $forecast   .=    '<span class="thingsToDoStep_weather_desc">' . $forecast->attributes()->low . '&deg; - ' . $forecast->attributes()->high . '&deg;'
                        . '<img class="weekimg" src="http://l.yimg.com/a/i/us/we/52/' . $forecast->attributes()->code . '.gif"  alt=""/>'
                    . '</span>';
    }
    $data['forecast'] =   $forecast;
}

$slider_container  = array();
foreach($map_events_data as $event_item){
    $image_title = htmlEntityDecode($event_item['name']);
    $page_link = ReturnLink('channel-events-detailed/' . $event_item['id']);
    $image_pic = ($event_item['photo']) ? photoReturneventImage($event_item) : ReturnLink('images/channel/eventthemephoto.jpg');
    $slider_container[] = array("image_title"=>$image_title, "image_link"=>$image_pic , "page_link"=>$page_link);
}

$data['image_slider'] = $slider_container;

$slider_container2 = array();
$topthingstodoList = getThingstodoDetailList(array(
    'orderby'=>'rand',
    'has_image'=>1,
    'city_id'=>$city_search,
    'country'=>$txt_code,
    'limit'=>5
));
foreach($topthingstodoList as $list_item){
    $image_title = htmlEntityDecode($list_item['title']);
    $page_link = '';
    $entity_type = $list_item['entity_type'];
    if( $entity_type == SOCIAL_ENTITY_HOTEL ){
        $dis_data = getHotelInfo($list_item['entity_id']);
        $linkpoi_name = htmlEntityDecode($dis_data['hotelName']);
        $page_link = returnHotelReviewLink($dis_data['id'],$linkpoi_name);        
    }else if( $entity_type == SOCIAL_ENTITY_RESTAURANT ){
        $dis_data = getRestaurantInfo($list_item['entity_id']);
        $linkpoi_name = htmlEntityDecode($dis_data['name']);
        $page_link = returnRestaurantReviewLink($dis_data['id'],$linkpoi_name);        
    }else if( $entity_type == SOCIAL_ENTITY_AIRPORT ){
        $dis_data = getAirportInfo($list_item['entity_id']);
        $linkpoi_name = htmlEntityDecode($dis_data['name']);
        $page_link = returnAirportReviewLink($dis_data['id'],$linkpoi_name);        
    }else {
        $dis_data = getPoiInfo($list_item['entity_id']);
        $linkpoi_name = htmlEntityDecode($dis_data['name']);
        $page_link = returnThingstodoReviewLink($dis_data['id'],$linkpoi_name);        
    }
    $image_pic = '';
    if($list_item['image']!=""){
        $image_pic = ReturnLink("media/videos/uploads/thingstodo/".$list_item['image']);
    }
    $slider_container2[] = array("image_title"=>$image_title, "image_link"=>$image_pic , "page_link"=>$page_link);
}
if( sizeof($topthingstodoList)==0 ){
    /*if ($city_search == 0) {
        $srch_options = array(
            'limit' => 5,
            'page' => 0,
            'orderby' => 'rand',
            'is_public' => 2,
            'order' => 'd',
            'type' => 'a',
            'country' => $txt_code
        );
    } else {
        $srch_options = array(
            'limit' => 5,
            'page' => 0,
            'orderby' => 'rand',
            'is_public' => 2,
            'order' => 'd',
            'type' => 'a',
            'city_id' => $city_search
        );
    }
    $image_array = mediaSearch($srch_options);
    foreach($image_array as $list_item){
        $image_title = htmlEntityDecode($list_item['title']);
        if ($list_item['image_video'] == 'i') {
            $page_link = ReturnPhotoUri($list_item);
        } else {
            $page_link = ReturnVideoUriHashed($list_item);
        }
        $image_pic = photoReturnSrcSmall($list_item);        
        $slider_container2[] = array("image_title"=>$image_title, "image_link"=>$image_pic , "page_link"=>$page_link);
    }*/
}
$data['small_image_slider'] = $slider_container2;
$data['city_search'] = $city_search;
$data['txt_code'] = $txt_code;


tt_global_set('includes', array('css/things_to_do.css','js/topThingsToDo.js','js/thingsToDo.js','js/jssor.slider.js'));
include($path . "twig_parts/_head.php");

include($path . "twig_parts/_foot.php");
echo $template->render($data);
