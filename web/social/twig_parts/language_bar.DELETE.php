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
//require_once $CONFIG ['server']['root'].'vendor/autoload.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($CONFIG ['server']['root'].'twig_templates/');
$twig = new Twig_Environment($loader, array(
    'cache' => $CONFIG ['server']['root'].'twig_cache/','debug' => false,
));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('parts/language_bar.twig');
/* Array to get Details of the languages  */
$langLogo = array(
    'en'=>array('icon'=>'en.jpg','title'=>'English','shortTitle'=>'en'),
    'fr'=>array('icon'=>'fr.jpg','title'=>'Français','shortTitle'=>'fr')
);
/* -end-  Array to get Details of the languages  */
$data["recentlyViewed"] = _('Recently viewed');
$data["language"] = _('Language');
$data["currentCurrency"] = isset($_SESSION['currencyName'])?$_SESSION['currencyName']:_('U.S. Dollar');
$data["langIco"] = ReturnLink('images/en.jpg');
$data['changeCurLink'] = ReturnLink("parts/currency.php");;
echo $template->render($data);