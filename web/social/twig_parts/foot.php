<?php
if (!isset($bootOptions)) {
    $path = "../";
    $bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
    include_once ( $path . "inc/functions/videos.php" );
    include_once ( $path . "inc/functions/users.php" );
}
    include_once ( $path . "inc/twigFct.php" );
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($CONFIG ['server']['root'].'twig_templates/');
$twig = new Twig_Environment($loader, array(
    'cache' => $CONFIG ['server']['root'].'twig_cache/','debug' => false,
));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('foot.html');
include('_foot.php');
echo $template->render($data);