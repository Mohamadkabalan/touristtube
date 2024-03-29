<?php
$path = "";

$bootOptions = array("loadDb" => 1, "loadLocation" => 1, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/twigFct.php" );

$lang = LanguageGet();
$theLink = $CONFIG ['server']['root'];
//require_once $theLink . 'vendor/autoload.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($theLink . 'twig_templates/');
$twig = new Twig_Environment($loader, array(
    'cache' => $theLink . 'twig_cache/', 'debug' => false,
));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate($lang.'_terms-and-conditions.twig');
$includes = array( 'media'=>'css_media_query/media_style_static_page.css?v='.MQ_MEDIA_STYLE_CSS_V,
    'media1'=>'css_media_query/privacy_policy_media.css?v='.MQ_PRIVACY_POLICY_MEDIA_CSS_V);

$data['userIsLogged'] = userIsLogged();
$data['userIsChannel'] = userIsChannel();
$data['hide_view_all'] = '0';
if (userIsLogged() && userIsChannel()) {
     array_unshift($includes, 'css/channel-header.css');
    tt_global_set('includes', $includes);
    include($theLink . "twig_parts/_headChannel.php");
} else {
    tt_global_set('includes', $includes);
    include($theLink . "twig_parts/_head.php");
}


include($theLink . "twig_parts/_foot.php");
echo $template->render($data);