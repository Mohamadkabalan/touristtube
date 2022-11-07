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
    'cache' => $theLink . 'twig_cache/', 'debug' => false,
        ));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('discover.twig');

$user_is_logged = 0;
if (userIsLogged()) {
    $user_is_logged = 1;
}
$user_id = userGetID();
ob_clean();
tt_global_set('includes', array('css/discover_first.css','js/discover_first.js','css/jquery.selectbox.map.css', 'js/jquery.selectbox-0.6.1.js'));
include($path . "twig_parts/_head.php");


$discoverAvailable = discoverWorldcitiesAvailable();
$index = 0;
$media_pinArray =   array();
foreach ($discoverAvailable as $item_val) {
    $name = '<span class="mtooltip_title_white">'.$item_val['name'].'</span>';
    $str = $item_val['name'];
    if($item_val['state_name']!=''){
       $name .= '<br/>'.$item_val['state_name'].''; 
    }
    if($item_val['country_name']!=''){
       $name .= '<br/>'.$item_val['country_name'].'';
       $str .= '/'.$item_val['country_code'];
       if( $item_val['state_name']!='' ){
            $str .= '/'.$item_val['state_code'];
       }
    }
    $name = addslashes($name);
    $lat = $item_val['latitude'];
    $log = $item_val['longitude'];
//    $cityid = $item_val['cityid'];
    $page_link = addslashes(ReturnLink('map/'.$str));
    
    $media_pinArray['name'] = $name;
    $media_pinArray['lat'] = $lat;
    $media_pinArray['log'] = $log;
//    $media_pinArray['cityid'] = $cityid;
    $media_pinArray['page_link'] = $page_link;
    
    $aMedia_pinArray[]   =   $media_pinArray;
}

$data['media_pinArray']  =   $aMedia_pinArray;
$data['image_link'] = ReturnLink('images/discover/mapcollapsemenu.png');



$image_array = array('4.png','3.png','5.png','11.png','9.png','14.png','10.png','7.png','13.png','1.png','6.png','12.png','8.png');
$i=0;
$country_info =   array();
foreach($discoverAvailable as $item_val){
    $titre = $item_val['name'];
    $str = $item_val['name'];
    if($item_val['country_name']!=''){
       $str .= '/'.$item_val['country_code'];
       if( $item_val['state_name']!='' ){
            $str .= '/'.$item_val['state_code'];
       }
    }
    $page_link = ReturnLink('map/'.$str);
    $imglink=ReturnLink('images/discover/discoverfirst/'.$image_array[$i]);
    $i++;
    $country_info['titre'] = $titre;
    $country_info['page_link'] = $page_link;
    $country_info['imglink'] = $imglink;
    
    
    $acountr_info[] = $country_info;        
}
$data['contry_info']  =   $acountr_info; 


$userBagItemsCount=0;
if($user_is_logged==1) $userBagItemsCount = userAllBagItemsCount($user_id);
if($userBagItemsCount>99) $userBagItemsCount='99+';
$action_array = array();
$action_text1 =  'Start packing your %s TT bag %s for your next trip';
$action_array[]='<a class="bagcontainer_a" href="'. ReturnLink('bag').'" title="'._('bag').'">';
$action_array[]='<div class="bagcontainer"><div class="bag_count">'.$userBagItemsCount.'</div></div></a>';

$action_text = vsprintf(_($action_text1), $action_array);

$data['action_text'] = $action_text;

include($path . "twig_parts/_foot.php");
echo $template->render($data);
