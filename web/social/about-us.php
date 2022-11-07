<?php
$path = "";

$bootOptions = array("loadDb" => 1, "loadLocation" => 1, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );

//echo vsprintf(_("%1\$s - %2\$s: reviews with %3\$s, top photos and things to do in %2\$s, see ratings at TouristTube"),
//        array('title', 'paris', 'elie bou zeid') );
//echo  array_shift((explode(".",$_SERVER['HTTP_HOST'])));;
//exit();


//sendUserEmail_Add_Account( 42 , "rudy.sleiman@gmail.com" , "t3aj ya ruda" );exit;

//sendEmailNotification_Weekly();exit;
////////////////////////////    Testing start
$globArray['ownerName'] = 'roudy zzzzzzTT';

/*$globArray['posts'][0]['case'] = '1';
$globArray['posts'][0]['mediaTitle'] = 'POSTS';
$globArray['posts'][0]['mediaType'] = 'echo';
$globArray['posts'][0]['friends'] = array("0"=>array("friendImg.jpg", "fiend name","1250","155"));


$globArray['posts'][0]['partTitle'] = '<font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">{Name of the tuber}</font>
<font face="Arial, Helvetica, sans-serif" size="2" color="#000000"> 
ommented on the following:</font>';
$globArray['posts'][0]['friendComment'] = " it's a very interesting channel, where you can find everything about travel, "
        . "and special offers as well... ";

$globArray['posts'][0]['text'] = 'othe news is displayed here, its about 1 to 2 lines as maximum......................';
$globArray['posts'][0]['media'] = 'Echo';*/

$my_lnk_tmp = currentServerURL() ;


$globArray['posts'][0]['case'] = '2';
$globArray['posts'][0]['mediaTitle'] = 'POSTS';
$globArray['posts'][0]['mediaType'] = 'review';
$globArray['posts'][0]['friends'] = array("0"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "1"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "2"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "3"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "4"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "5"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "6"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "7"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "8"=>array("friendImg.jpg", "fiend name","1250","155"));
$globArray['posts'][0]['partTitle'] = '<font face="Arial, Helvetica, sans-serif" size="2" color="#000000">
    Some people commented on the following:</font>';
$globArray['posts'][0]['friendComment'] = '';

$globArray['posts'][0]['reviewed_item_title'] = 'Eurostars Hotel Saint John';
$globArray['posts'][0]['reviewed_item_type'] = 'Hotel';
$globArray['posts'][0]['reviewed_item_address'] = 'Via Matteo Boiardo 30, Station Termini, 00185 Rome, Italy';
$globArray['posts'][0]['review_marker'] = $my_lnk_tmp.'/images/review_marker.png';
$globArray['posts'][0]['rating_stars'] = $my_lnk_tmp.'/images/hotels/stars.png';
$globArray['posts'][0]['review_placeholder'] = $my_lnk_tmp.'/images/twig_review_placeholder.jpg';
$globArray['posts'][0]['review_text'] = 'reviewed text goes here reviewed text goes here reviewed text goes here';




/*
$globArray['posts'][2]['case'] = '1';
$globArray['posts'][2]['mediaTitle'] = 'POSTS';
$globArray['posts'][2]['mediaType'] = 'post';
$globArray['posts'][2]['friends'] = array("0"=>array("friendImg.jpg", "fiend name","1250","155"));
$globArray['posts'][2]['partTitle'] = '<font face="Arial, Helvetica, sans-serif" size="2" color="#000000"> Some people commented on your {media Type} published on your channel  </font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">{channel Name}</font>';
$globArray['posts'][2]['friendComment'] = '"it\'s a very interesting channel, where you can find everything about travel, and special offers as well..."';
$globArray['posts'][2]['text'] = '';
$globArray['posts'][2]['media'] = 'mediaImg.jpg';

$globArray['posts'][3]['case'] = '2';
$globArray['posts'][3]['mediaTitle'] = 'POSTS';
$globArray['posts'][3]['mediaType'] = 'post';
$globArray['posts'][3]['friends'] = array("0"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "1"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "2"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "3"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "4"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "5"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "6"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "7"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "8"=>array("friendImg.jpg", "fiend name","1250","155"));
$globArray['posts'][3]['partTitle'] = '<font face="Arial, Helvetica, sans-serif" size="2" color="#000000"> Some people commented on your {media Type} published on your channel  </font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">{channel Name}</font>';
$globArray['posts'][3]['friendComment'] = '';
$globArray['posts'][3]['text'] = '';
$globArray['posts'][3]['media'] = 'mediaImg.jpg';






$globArray['channel'][0]['case'] = '1';
$globArray['channel'][0]['mediaTitle'] = 'CHANNEL\'S RELATED';
$globArray['channel'][0]['mediaType'] = '';
$globArray['channel'][0]['friends'] = array("0"=>array("friendImg.jpg", "fiend name","1250","155"));
$globArray['channel'][0]['partTitle'] = '<font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">{Name of user}</font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#000000"> commented on the </font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b"> profile photo </font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#000000">of your channel  </font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">{channel Name}</font>';
$globArray['channel'][0]['friendComment'] = '"it\'s a very interesting channel, where you can find everything about travel, and special offers as well..."';
$globArray['channel'][0]['text'] = '';
$globArray['channel'][0]['media'] = '';

$globArray['channel'][1]['case'] = '2';
$globArray['channel'][1]['mediaTitle'] = 'CHANNEL\'S RELATED';
$globArray['channel'][1]['mediaType'] = '';
$globArray['channel'][1]['friends'] = array("0"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "1"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "2"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "3"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "4"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "5"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "6"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "7"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "8"=>array("friendImg.jpg", "fiend name","1250","155"));
$globArray['channel'][1]['partTitle'] = '<font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Some people commented on the </font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b"> profile photo </font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#000000">of your channel  </font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">{channel Name}</font>';
$globArray['channel'][1]['friendComment'] = '';
$globArray['channel'][1]['text'] = '';
$globArray['channel'][1]['media'] = '';

$globArray['channel'][2]['case'] = '1';
$globArray['channel'][2]['mediaTitle'] = 'CHANNEL\'S RELATED';
$globArray['channel'][2]['mediaType'] = '';
$globArray['channel'][2]['friends'] = array("0"=>array("friendImg.jpg", "fiend name","1250","155"));
$globArray['channel'][2]['partTitle'] = '<font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">{Name of user}</font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#000000"> commented on the </font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b"> cover photo </font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#000000">of your channel  </font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">{channel Name}</font>';
$globArray['channel'][2]['friendComment'] = '"it\'s a very interesting channel, where you can find everything about travel, and special offers as well..."';
$globArray['channel'][2]['text'] = '';
$globArray['channel'][2]['media'] = '';

$globArray['channel'][3]['case'] = '2';
$globArray['channel'][3]['mediaTitle'] = 'CHANNEL\'S RELATED';
$globArray['channel'][3]['mediaType'] = '';
$globArray['channel'][3]['friends'] = array("0"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "1"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "2"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "3"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "4"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "5"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "6"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "7"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "8"=>array("friendImg.jpg", "fiend name","1250","155"));
$globArray['channel'][3]['partTitle'] = '<font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Some people commented on the </font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b"> cover photo </font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#000000">of your channel  </font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">{channel Name}</font>';
$globArray['channel'][3]['friendComment'] = '';
$globArray['channel'][3]['text'] = '';
$globArray['channel'][3]['media'] = '';

$globArray['channel'][4]['case'] = '1';
$globArray['channel'][4]['mediaTitle'] = 'CHANNEL\'S RELATED';
$globArray['channel'][4]['mediaType'] = 'slogan';
$globArray['channel'][4]['friends'] = array("0"=>array("friendImg.jpg", "fiend name","1250","155"));
$globArray['channel'][4]['partTitle'] = '<font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">{Name of user}</font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#000000"> commented on the  slogan you published on your channel </font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">{channel Name}</font>';
$globArray['channel'][4]['friendComment'] = '"it\'s a very interesting channel, where you can find everything about travel, and special offers as well..."';
$globArray['channel'][4]['text'] = 'â€œthe news is displaye here, itâ€™s about 1 to 2 lines as maximum...................â€�';
$globArray['channel'][4]['media'] = '';

$globArray['channel'][5]['case'] = '2';
$globArray['channel'][5]['mediaTitle'] = 'CHANNEL\'S RELATED';
$globArray['channel'][5]['mediaType'] = 'slogan';
$globArray['channel'][5]['friends'] = array("0"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "1"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "2"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "3"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "4"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "5"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "6"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "7"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "8"=>array("friendImg.jpg", "fiend name","1250","155"));
$globArray['channel'][5]['partTitle'] = '<font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Some people commented on the slogan  you published on your channel </font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">{channel Name}</font>';
$globArray['channel'][5]['friendComment'] = '';
$globArray['channel'][5]['text'] = 'â€œthe news is displaye here, itâ€™s about 1 to 2 lines as maximum...................â€�';
$globArray['channel'][5]['media'] = '';

$globArray['channel'][6]['case'] = '1';
$globArray['channel'][6]['mediaTitle'] = 'CHANNEL\'S RELATED';
$globArray['channel'][6]['mediaType'] = 'info';
$globArray['channel'][6]['friends'] = array("0"=>array("friendImg.jpg", "fiend name","1250","155"));
$globArray['channel'][6]['partTitle'] = '<font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">{Name of user}</font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#000000"> commented on  your info on your channel </font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">{channel Name}</font>';
$globArray['channel'][6]['friendComment'] = '"it\'s a very interesting channel, where you can find everything about travel, and special offers as well..."';
$globArray['channel'][6]['text'] = 'â€œthe news is displaye here, itâ€™s about 1 to 2 lines as maximum...................â€�';
$globArray['channel'][6]['media'] = '';

$globArray['channel'][7]['case'] = '2';
$globArray['channel'][7]['mediaTitle'] = 'CHANNEL\'S RELATED';
$globArray['channel'][7]['mediaType'] = 'info';
$globArray['channel'][7]['friends'] = array("0"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "1"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "2"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "3"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "4"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "5"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "6"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "7"=>array("friendImg.jpg", "fiend name","1250","155"),
                        "8"=>array("friendImg.jpg", "fiend name","1250","155"));
$globArray['channel'][7]['partTitle'] = '<font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Some people commented on the your info  on your channel </font>
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">{channel Name}</font>';
$globArray['channel'][7]['friendComment'] = '';
$globArray['channel'][7]['text'] = 'â€œthe news is displaye here, itâ€™s about 1 to 2 lines as maximum...................â€�';
$globArray['channel'][7]['media'] = '';
*/

$to_email='';

//displayEmailReview($to_email, $globArray, 0, '', '');
//exit;

////////////////////////////    Testing end

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
$template = $twig->loadTemplate($lang.'_about-us.twig');

$includes = array('media' => 'css_media_query/media_style.css', 'media1' => 'css_media_query/about-us.css?v='.MQ_ABOUT_US_CSS_V);

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