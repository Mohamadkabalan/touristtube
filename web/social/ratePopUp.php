<?php

$path = "";
if (!isset($bootOptions)) {
    $bootOptions = array("loadDb" => 1, "loadLocation" => 1, "requireLogin" => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );

    include_once ( $path . "inc/functions/videos.php" );
    include_once ( $path . "inc/functions/users.php" );
}
include_once ( $path . "inc/twigFct.php" );

$theLink = $CONFIG ['server']['root'];
//require_once $CONFIG ['server']['root'].'vendor/autoload.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($CONFIG ['server']['root'] . 'twig_templates/');
$twig2 = new Twig_Environment($loader, array(
    'cache' => $CONFIG ['server']['root'] . 'twig_cache/', 'debug' => false,
        ));
$twig2->addExtension(new Twig_Extension_twigTT());
$template2 = $twig2->loadTemplate('parts/ratePopUp.twig');
include($path.'parts/_ratePopUp.php');
echo $template2->render($data);
