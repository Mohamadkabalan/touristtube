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
$template = $twig->loadTemplate('thingsToDo.twig');

$user_is_logged = 0;
if (userIsLogged()) {
    $user_is_logged = 1;
}
$user_id = userGetID();
ob_clean();
tt_global_set('includes', array('css/things_to_do.css','js/topThingsToDo.js','media'=>'css_media_query/media_style_static_page.css', 'media1'=>'css_media_query/things_to_do_media.css'));
include($path . "twig_parts/_head.php");


$userBagItemsCount=0;
if($user_is_logged==1) $userBagItemsCount = userAllBagItemsCount($user_id);
if($userBagItemsCount>99) $userBagItemsCount='99+';
$action_array='<a class="bagcontainer_a bagcontainer_a'.$user_is_logged.'" href="'. ReturnLink('bag').'" title="'._('bag').'"><div class="bagcontainer"><div class="bag_count">'.$userBagItemsCount.'</div></div></a>';

$data['action_text'] = $action_array;


$cityData = array();
$thingstodoList = getThingstodoList(LanguageGet());
foreach($thingstodoList as $item_data){
    $cityData[] = array( "img_link"  => ReturnLink("media/videos/uploads/thingstodo/".$item_data['image']), "page_link"  => returnTopThingstodoLink($item_data['alias_id']), "img_title" => htmlEntityDecode($item_data['title']), "img_descr" => htmlEntityDecode(nl2br($item_data['description'])));
}
$data['city_info'] = $cityData;

include($path . "twig_parts/_foot.php");
echo $template->render($data);
