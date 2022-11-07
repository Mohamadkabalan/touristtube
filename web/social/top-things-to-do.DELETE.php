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
$template = $twig->loadTemplate('topThingsToDo.twig');

$txt_id_init = intval(UriGetArg('id'));
$txt_id = ($txt_id_init == 0) ? null : $txt_id_init;
$thingstodoInfo = getThingstodoInfo($txt_id);
if($thingstodoInfo['language'] != LanguageGet()){
    $GLOBAL_LANG = $thingstodoInfo['language'];
    $ur_array = UriCurrentPageURLForLanguage();
    $link = $ur_array[0].$GLOBAL_LANG.'.'.$ur_array[1];
    header("location:" . $link);
}
if ($txt_id_init == 0) {
    header("location:" . ReturnLink('review'));
}
$user_is_logged = 0;
if (userIsLogged()) {
    $user_is_logged = 1;
}
$user_id = userGetID();
ob_clean();
$userBagItemsCount=0;
if($user_is_logged==1) $userBagItemsCnt = userAllBagItemsCount($user_id);
if($userBagItemsCnt>99) $userBagItemsCount='99+';
else $userBagItemsCount=$userBagItemsCnt;
$action_array='<a class="bagcontainer_a bagcontainer_a'.$user_is_logged.'" href="'. ReturnLink('bag').'" title="'._('bag').'"><div class="bagcontainer"><div class="bag_count" data-count="'.$userBagItemsCnt.'">'.$userBagItemsCount.'</div></div></a>';

$data['action_text'] = $action_array;

$best_of_city = array();
$thingstodoSearch = thingstodoSearch(array(
    'escape_id'=>$txt_id,
    'orderby'=>'rand',
    'limit'=>6,
    'lang'=>LanguageGet()
));
foreach($thingstodoSearch as $item_data){
    $title = htmlEntityDecode($item_data['title']);
    $page_link = returnTopThingstodoLink($item_data['alias_id']);
    $best_of_city[] = array("city_link"  => $page_link, "city_descr" => $title);
    $i++;
}
$data['best_of_city'] = $best_of_city;
$thingstodoInfo['h2'] =nl2br($thingstodoInfo['h2']);
$data['thingstodoInfo'] = $thingstodoInfo;
$topThingsToDo_all = array();
$topthingstodoList = getThingstodoDetailList(array(
    'parent_id'=>$txt_id,
    'orderby'=>'order_display',
    'order'=>'d'
));
$i=1;
foreach($topthingstodoList as $item_data){
    $page_link="";
    $title = htmlEntityDecode($item_data['title']);
    $city_id = $item_data['city_id'];
    $country = $item_data['country'];
    $entity_type = $item_data['entity_type'];
    if($item_data['entity_id']>0){
        if( $entity_type == SOCIAL_ENTITY_HOTEL ){
            $dis_data = getHotelInfo($item_data['entity_id']);
            $linkpoi_name = htmlEntityDecode($dis_data['hotelName']);
            $page_link = returnHotelReviewLink($dis_data['id'],$linkpoi_name);        
        }else if( $entity_type == SOCIAL_ENTITY_RESTAURANT ){
            $dis_data = getRestaurantInfo($item_data['entity_id']);
            $linkpoi_name = htmlEntityDecode($dis_data['name']);
            $page_link = returnRestaurantReviewLink($dis_data['id'],$linkpoi_name);        
        }else if( $entity_type == SOCIAL_ENTITY_AIRPORT ){
            $dis_data = getAirportInfo($item_data['entity_id']);
            $linkpoi_name = htmlEntityDecode($dis_data['name']);
            $page_link = returnAirportReviewLink($dis_data['id'],$linkpoi_name);        
        }else {
            $dis_data = getPoiInfo($item_data['entity_id']);
            $linkpoi_name = htmlEntityDecode($dis_data['name']);
            $page_link = returnThingstodoReviewLink($dis_data['id'],$linkpoi_name);        
        }
    }
    $description = htmlEntityDecode($item_data['description']);
    $title = preg_quote($title, '/');
    if($page_link!=''){
        $description = preg_replace(array('/'.$title.'/i'), '<a class="topThingToDo_cityname" href="'.$page_link.'" title="'.$title.'">'.$title.'</a>' , nl2br($description) );
    }else{
        $description = preg_replace(array('/'.$title.'/i'), '<span class="topThingToDo_cityname">'.$title.'</span>' , nl2br($description) );
    }
    $description = stripslashes($description);
    $title = stripslashes($title);
    $image_link="";
    if($item_data['image']!=""){
        $image_link = ReturnLink("media/videos/uploads/thingstodo/".$item_data['image']);
    }
    $is_bag = checkUserBagItem($user_id, $entity_type , $item_data['entity_id'] );
    $is_bagclass='';
    if($is_bag){
        $is_bagclass=' stationBagAct';
    }
    if($item_data['entity_id']==0) $city_id=0;
    if($item_data['entity_id']==0) $country='';
    $topThingsToDo_all[] = array( "things_img"  => $image_link , "page_link"  => $page_link, "things_descr" => $description,'city_id'=>$city_id,'country'=>$country, 'entity_type'=>$entity_type,'entity_id'=>$item_data['entity_id'], 'is_bagclass'=>$is_bagclass, 'title' => $title);
    $i++;
}
$data['topThingsToDo_all'] = $topThingsToDo_all;
$data['inactiveplusclass'] = '';
if($user_is_logged==0) $data['inactiveplusclass'] = ' inactive';


tt_global_set('includes', array('css/things_to_do.css','js/topThingsToDo.js','media'=>'css_media_query/media_style_static_page.css', 'media1'=>'css_media_query/things_to_do_cities.css'));
include($path . "twig_parts/_head.php");
include($path . "twig_parts/_foot.php");
echo $template->render($data);
