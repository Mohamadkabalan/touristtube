<?php
$path = "";
$tpopular = 5;
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );

include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/functions/bag.php" );
include_once ( $path . "inc/twigFct.php" );


$theLink = $CONFIG ['server']['root'];
//require_once $theLink . 'vendor/autoload.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($theLink . 'twig_templates/');
$twig = new Twig_Environment($loader, array(
    'cache' => $theLink . 'twig_cache/', 'debug' => false,
));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('notfound.twig');

$includes = array('css/pagenotfound.css','media'=>'css_media_query/media_style.css?v='.MQ_MEDIA_STYLE_CSS_V);

tt_global_set('includes', $includes);
include($theLink . "twig_parts/_head.php");
include($theLink . "twig_parts/_foot.php");
echo $template->render($data);