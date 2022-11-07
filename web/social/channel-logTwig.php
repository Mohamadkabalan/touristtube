<?php
$path = "";
$limit = 7;
$currentpage = 0;

$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/twigFct.php" );

$theLink = $CONFIG ['server']['root'];
//require_once $theLink . 'vendor/autoload.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($theLink . 'twig_templates/');
$twig = new Twig_Environment($loader, array(
    'cache' => $theLink . 'twig_cache/', 'debug' => true,
        ));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('channel-log.twig');

$channel = db_sanitize(UriGetArg(0));

if (UriArgIsset('page')):
    $currentpage = intval(UriGetArg('page'));
else:
    $currentpage = 0;
endif;


$post_id = intval(UriGetArg('post'));

$currentchannelname = $channel;

$showLogSearch = 1;

$channelInfo = channelFromURL($channel);
if ($channelInfo === false) {
    header("location:".ReturnLink('channels/'));
}
$fromid_check = userGetID();

$channel_id_owner = $channelInfo['id'];
$userid = userGetID();
$is_owner = 1;
$is_owner_visible = -1;
if ($userid != intval($channelInfo['owner_id'])) {
    $is_owner = 0;
    $is_owner_visible = 1;
    if ($channelInfo['published'] != 1)
        header('Location:' . ReturnLink('channels/'));
}else {
    userSwitchChannel($channel_id_owner);
}
$is_channel_check = 0;
if ($is_owner == 0) {
    $defaultchannelarray=userCurrentChannelGet();
    if ($defaultchannelarray && sizeof($defaultchannelarray) != 0) {
        $fromid_check = $defaultchannelarray['id'];
        $is_channel_check = 1;
    }
}

channelGlobalSet($channelInfo);

$channelPrivacyArray = GetChannelNotifications($channel_id_owner);
$channelPrivacyArray = $channelPrivacyArray[0];

$privacyExtandCond = (( intval($channelPrivacyArray['privacy_log']) == 1 && checkChannelPrivacyExtand($channelInfo['id'], PRIVACY_EXTAND_TYPE_LOG, $fromid_check, $is_channel_check) ) || $is_owner == 1);
if(!$privacyExtandCond) header('Location:' . ReturnLink("/channel/" . $channelInfo['channel_url']) );


// show connections menu
$showconnectionsmenu = 1;
$hidesearch = 1;

$userChannelInfo = getUserInfo($channelInfo['owner_id']);

$logo_src = ($channelInfo['logo']) ? photoReturnchannelLogo($channelInfo) : ReturnLink('/media/tubers/tuber.jpg');
$channel_url = ReturnLink("/channel/" . $channelInfo['channel_url']);
$header_src = ($channelInfo['header']) ? photoReturnchannelHeader($channelInfo) : ReturnLink('/images/channel/coverphoto.jpg');

$bg_src = ($channelInfo['bg']) ? 'url(' . photoReturnchannelBG($channelInfo) . ') no-repeat top' : '';

$bg_src .= ($channelInfo['bgcolor']) ? ' #' . $channelInfo['bgcolor'] : '';

$channel_name = htmlEntityDecode($channelInfo['channel_name']);
$channel_desc = htmlEntityDecode($channelInfo['small_description'],0);
$channel_slogan = htmlEntityDecode($channelInfo['slogan']);

$which_section = UriGetArg(1);

$arg = UriGetArg(2);


$includes = array('css/channel-log.css', 'assets/channel/js/channel-log.js', 'assets/channel/js/channel-header.js', 'css/channel-header.css',
    "js/underscore-min.js", "js/jquery.elastic.js", "js/jquery.mentionsInput.js",
'media'=>'css_media_query/media_style.css?v='.MQ_MEDIA_STYLE_CSS_V,
'media1'=>'css_media_query/channel_media.css?v='.MQ_CHANNEL_MEDIA_CSS_V);


tt_global_set('includes', $includes);

$arraychannellinks = GetChannelExternalLinks($channel_id_owner);

$TubersNumConnected = channelConnectedTubersSearch(array('channelid' => $channel_id_owner, 'n_results' => true));
$TubersNumConnected0 = $TubersNumConnected;
$TubersNumConnected01 = $TubersNumConnected;

//$TubersNumConnected1 = channelConnectedTubersSearch(array('channelid' => $channel_id_owner,'n_results' => true,'ischannel'=>1));
$TubersNumConnected1 = socialSharesGet(array(
    'orderby' => 'share_ts',
    'order' => 'd',
    'entity_id' => $channel_id_owner,
    'is_visible' => $is_owner_visible,
    'entity_type' => SOCIAL_ENTITY_CHANNEL,
    'share_type' => SOCIAL_SHARE_TYPE_SPONSOR,
    'n_results' => true
        ));
//$TubersNumConnected=$TubersNumConnected+$TubersNumConnected1;


$channelimageInfoCount = mediaSearch(array(
    'channel_id' => $channel_id_owner,
    'type' => 'i',
    'catalog_status' => 0,
    'n_results' => true
        ));
$channelvideoInfoCount = mediaSearch(array(
    'channel_id' => $channel_id_owner,
    'type' => 'v',
    'catalog_status' => 0,
    'n_results' => true
        ));

$optionsC = array('channelid' => $channelInfo['id'],'is_owner' => $is_owner, 'user_id' => $channelInfo['owner_id'], 'n_results' => true);
$channelalbumInfoCount = userCatalogSearch($optionsC);

$ChanelNumAlbumC = $channelalbumInfoCount;


$user_is_logged = 0;
if (userIsLogged()) {
    $user_is_logged = 1;
}
$userIschannel = userIsChannel();
$userIschannel = ($userIschannel) ? 1 : 0;

$defaultchannelarray = userCurrentChannelGet();
if ($defaultchannelarray && sizeof($defaultchannelarray) != 0 && $is_owner == 0) {
    $userIschannel = 1;
    $defaultchannelid = $defaultchannelarray['id'];
}

$from_date = date('Y') . '-' . date('n') . '-1';
$to_date = date('Y-m-t', strtotime($from_date));

//post_id
// Get the news feed count.
$limitlog = 10;
$pagelog = 0;
if ($post_id != 0) {
    $news_feed_count = newsfeedGroupingLogSearch(array(
        'entity_type' => SOCIAL_ENTITY_POST,
        'entity_id' => $post_id,
        'is_visible' => $is_owner_visible,
        'channel_id' => $channel_id_owner,
        'n_results' => true
    ));
    // Get the news feed.
    $news_feed = newsfeedGroupingLogSearch(array(
        'limit' => $limitlog,
        'page' => $pagelog,
        'orderby' => 'id',
        'entity_type' => SOCIAL_ENTITY_POST,
        'entity_id' => $post_id,
        'is_visible' => $is_owner_visible,
        'order' => 'd',
        'channel_id' => $channel_id_owner
    ));
} else {
    $news_feed_count = newsfeedGroupingLogSearch(array(
        //'from_ts' => $from_date,
        'to_ts' => $to_date,
        'is_visible' => $is_owner_visible,
        'channel_id' => $channel_id_owner,
        'n_results' => true
    ));
    if($news_feed_count==0){
        $options_log = array(
            'limit' => $limitlog,
            'page' => $pagelog,
            'orderby' => 'id',
            'is_visible' => $is_owner_visible,
            'order' => 'd',
            'channel_id' => $channel_id_owner
        );
    }else{
       $options_log = array(
            'limit' => $limitlog,
            'page' => $pagelog,
            'orderby' => 'id',
            'is_visible' => $is_owner_visible,
            'order' => 'd',
            //'from_ts' => $from_date,
            'to_ts' => $to_date,
            'channel_id' => $channel_id_owner
        );
    }
    $news_feed = newsfeedGroupingLogSearch($options_log);
}
include($path . "twig_parts/_headChannel.php");

$data['channel_id_owner'] = $channel_id_owner;
$data['is_owner'] = $is_owner;
$data['userIschannel'] = $userIschannel;
$data['user_is_logged'] = $user_is_logged;

$data['bg_src'] = $bg_src;

$data['channelCondition'] = false;
$data['pagecurname'] = 'channel-log.php';



if ($is_owner == 1){
    $channelPrivacyExtand = channelPrivacyExtandSearch(array(
        'channelid' => $channel_id_owner,
        'privacy_type' => PRIVACY_EXTAND_TYPE_LOG
    ));
    $privacy_icon_val = PRIVACY_EXTAND_KIND_PUBLIC; 

    if ($channelPrivacyExtand) {
        $channelPrivacyExtand = $channelPrivacyExtand[0];
        $kind_type_array = explode(',', $channelPrivacyExtand['kind_type']);
        if (sizeof($kind_type_array) > 1 || $channelPrivacyExtand['connections'] != '' || $channelPrivacyExtand['sponsors'] != '') {
            $privacy_icon_val = PRIVACY_EXTAND_KIND_CUSTOM;
        } else {
            $privacy_icon_val = $kind_type_array[0];
        }
    }
    $privacy_icon_str = _('public');
    if ($privacy_icon_val == PRIVACY_EXTAND_KIND_PRIVATE) {
        $privacy_icon_str = _('private');
    } else if ($privacy_icon_val == PRIVACY_EXTAND_KIND_CONNECTIONS) {
        $privacy_icon_str = _('connections');
    } else if ($privacy_icon_val == PRIVACY_EXTAND_KIND_SPONSORS) {
        $privacy_icon_str = _('sponsors');
    } else if ($privacy_icon_val == PRIVACY_EXTAND_KIND_CUSTOM) {
        $privacy_icon_str = _('custom');
    }
}
$data['privacy_icon_val']=$privacy_icon_val;
$data['privacy_icon_str']=$privacy_icon_str;

include("parts/channelHeader1.php");

if($is_owner == 1){
    include("parts/myChannelRightLog.php");
}else{
    include("parts/myChannelRight1.php");
}
$data['PRIVACY_EXTAND_KIND_PUBLIC'] = PRIVACY_EXTAND_KIND_PUBLIC;
$data['PRIVACY_EXTAND_KIND_CONNECTIONS'] = PRIVACY_EXTAND_KIND_CONNECTIONS;
$data['PRIVACY_EXTAND_KIND_SPONSORS'] = PRIVACY_EXTAND_KIND_SPONSORS;
$data['PRIVACY_EXTAND_KIND_PRIVATE'] = PRIVACY_EXTAND_KIND_PRIVATE;
$data['PRIVACY_EXTAND_KIND_CUSTOM'] = PRIVACY_EXTAND_KIND_CUSTOM;

$data['TubersNumConnected01'] = $TubersNumConnected01;
$data['TubersNumConnected1'] = $TubersNumConnected1;

$data['SOCIAL_POST_TYPE_TEXT'] = SOCIAL_POST_TYPE_TEXT;
$data['SOCIAL_POST_TYPE_LINK'] = SOCIAL_POST_TYPE_LINK;
$data['SOCIAL_POST_TYPE_LOCATION'] = SOCIAL_POST_TYPE_LOCATION;

$data['news_feed_count'] = $news_feed_count;

$data['year_arrow_left_link'] = ReturnLink('images/channel/calpicker/year_arrow_left.png');
$data['dateY'] = date('Y');
$data['year_arrow_right_link'] = ReturnLink('images/channel/calpicker/year_arrow_right.png');
$data['months_bar_link'] = ReturnLink('images/channel/calpicker/months_bar.png');

if( $news_feed_count >0 ) 
    $data['dateN'] = date('n');

include("parts/channel-log_loopTwig.php");

include("parts/channelNewsTicker1.php");
include($theLink . "twig_parts/_foot.php");
echo $template->render($data);
?>
