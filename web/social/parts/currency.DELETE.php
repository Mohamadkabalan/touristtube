<?php
//call the functions and script
$path = "../";
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/twigFct.php" );

//twig connection Part
$theLink = $CONFIG ['server']['root'];
//require_once $theLink.'vendor/autoload.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($theLink.'twig_templates/');
$twig = new Twig_Environment($loader, array(
    'cache' => $theLink.'twig_cache/','debug' => false,
));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('currency.twig');

$user_id = userGetID();
//----
$data = array();

$topArray = array();
$topArray[0]["val"] = '1';
$topArray[0]["symb"] = '$ ';
$topArray[0]["name"] = 'U.S. Dollar';

$topArray[1]["val"] = '1.2';
$topArray[1]["symb"] = '€ ';
$topArray[1]["name"] = 'Euro';

$topArray[2]["val"] = '0.97';
$topArray[2]["symb"] = 'CAD ';
$topArray[2]["name"] = 'Canadian Dollar';

$allArray = array();
$allArray[0]["val"] = '0.5';
$allArray[0]["symb"] = 'AR$ ';
$allArray[0]["name"] = 'Argentine Peso';

$allArray[1]["val"] = '1.2';
$allArray[1]["symb"] = '€ ';
$allArray[1]["name"] = 'Euro';

$allArray[2]["val"] = '1';
$allArray[2]["symb"] = '$ ';
$allArray[2]["name"] = 'U.S. Dollar';

$allArray[3]["val"] = '0.97';
$allArray[3]["symb"] = 'CAD ';
$allArray[3]["name"] = 'Canadian Dollar';

$data['topCurr'] = _('Top currencies');
$data['allCurr'] = _('All currencies');
$data['topArray'] = $topArray;
$data['allArray'] = $allArray;
$data['jqueryScript'] = ReturnLink("js/jquery-1.9.1.min.js");
$data['ajaxLink'] = ReturnLink('ajax/ajax_change_currency.php');

echo $template->render($data);