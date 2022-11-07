<?php

$path = "";

$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/twigFct.php" );

$link = ReturnLink('media/discover') . '/';
$theLink = $CONFIG ['server']['root'];
//require_once $theLink . 'vendor/autoload.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($theLink . 'twig_templates/');
$twig = new Twig_Environment($loader, array(
    'cache' => $theLink . 'twig_cache/', 'debug' => false,
        ));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('review.twig');
/*
tt_global_set('includes', array(
    'css/review.css',
    'js/review.js')
        );
*/
tt_global_set('includes', 
        array('css/review.css',
    'media'=>'css_media_query/media_style_static_page.css?v='.MQ_MEDIA_STYLE_CSS_V,
    'media1'=>'css_media_query/review.css?v='.MQ_REVIEW_CSS_V,'js/review.js'));


if (userIsLogged() && userIsChannel()) {
    include($theLink . "twig_parts/_headChannel.php");
} else {
    include($theLink . "twig_parts/_head.php");
}

$data['userIsLogged'] = userIsLogged();
$data['userIsChannel'] = userIsChannel();
$data['SOCIAL_ENTITY_HOTEL'] = SOCIAL_ENTITY_HOTEL;
$data['SOCIAL_ENTITY_RESTAURANT'] = SOCIAL_ENTITY_RESTAURANT;
$data['SOCIAL_ENTITY_LANDMARK'] = SOCIAL_ENTITY_LANDMARK;
$data['SOCIAL_ENTITY_AIRPORT'] = SOCIAL_ENTITY_AIRPORT;


include($theLink . "twig_parts/_foot.php");
echo $template->render($data);
