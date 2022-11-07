<?php

$path = "";

$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/functions/flash.php" );

include_once ( $path . "inc/twigFct.php" );

$theLink = $CONFIG ['server']['root']; 
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($theLink . 'twig_templates/');
$twig = new Twig_Environment($loader, array(
    'cache' => $theLink . 'twig_cache/', 'debug' => false,
        ));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('live.twig');

$user_is_logged = 0;
if (userIsLogged()) {
    $user_is_logged = 1;
}
$val_code = UriGetArg('CO');
$country_code_search = isset($val_code) ? xss_sanitize($val_code) : '';
$val_code = UriGetArg('ST');
$state_code_search = isset($val_code) ? xss_sanitize($val_code) : '';
$val_code = UriGetArg('C');
$city_code_search = isset($val_code) ? intval($val_code) : '';
$val_code = UriGetArg('CN');
$continent_code_search = isset($val_code) ? xss_sanitize($val_code) : '';
$val_code = UriGetArg('ss');
$ss = isset($val_code) ? xss_sanitize($val_code) : '';
$val_code = UriGetArg('ss2');
$ss2 = isset($val_code) ? xss_sanitize($val_code) : '';

tt_global_set('includes', array('css/live.css','assets/tuber/js/live.js',
'media'=>'css_media_query/media_style.css?v='.MQ_MEDIA_STYLE_CSS_V,
'media1'=>'css_media_query/live.css?v='.MQ_LIVE_CSS_V));


include($path . "twig_parts/_head.php");

$mostVisited = mostVisitedCams();
$mostVisitedStr = '';
$index = 0;
$mostVisitedArr = array();
foreach ($mostVisited as $aCam) {
    $aMostVisitedArr = array();
    if ($index < 10) {
        $mostVisitedStr .= '<li class="aCamItem">'
                . '<div class="bulletLICams"></div>'
                . '<a href="' . ReturnWebcamUri($aCam) . '"  class="textLICams">' . $aCam['name'] . '</a>'
                . '<span>(' . $aCam['nb_views'] . ' ' . _('views') . ')</span>'
                . '</li>';
        $index++;
    }
    $aMostVisitedArr['name'] = addslashes(htmlEntityDecode($aCam['name']));
    $aMostVisitedArr['page_link'] = ReturnWebcamUri($aCam);
    $aMostVisitedArr['image_pic'] = ReturnLink($CONFIG['cam']['uploadPath'] . $aCam['url'] . '.jpg');
    $aMostVisitedArr['lat'] = $aCam['latitude'];
    $aMostVisitedArr['log'] = $aCam['longitude'];

    $mostVisitedArr[] = $aMostVisitedArr;
}
$data['mostVisitedStr'] = $mostVisitedStr;
$data['mostVisitedArr'] = $mostVisitedArr;

$data['keyCloudLive_25'] = keyCloudLive(25);
include($path .'ajax/ajax_livecam_search.php');

include($path . "twig_parts/_foot.php");
echo $template->render($data);