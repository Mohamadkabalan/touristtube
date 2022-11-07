<?php
$limit = 18;
$currentpage = 0;

if (!isset($bootOptions)) {
    $path = "";
    $bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
    include_once ( $path . "inc/functions/users.php" );
    include_once ( $path . "inc/functions/videos.php" );
}
include_once ( $path . "inc/twigFct.php" );

$theLink = $CONFIG ['server']['root'];
//require_once $theLink . 'vendor/autoload.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($theLink . 'twig_templates/');
$twig = new Twig_Environment($loader, array(
    'cache' => $theLink . 'twig_cache/', 'debug' => true,
        ));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('channel_connections.twig');

$showconnectionsmenu = 1;

$channel = db_sanitize(UriGetArg(0));
$channel = 'test1';

if (UriArgIsset('page')):
    $currentpage = intval(UriGetArg('page'));
else:
    $currentpage = 0;
endif;

$currentchannelname = $channel;

$channelInfo = channelFromURL($channel);

if ($channelInfo === false)
    header("location:".ReturnLink('channels/'));

$hidesearch = 1;
$pagetab = 1;
$searchString = 'find TTubers';

$userid = userGetID();
$is_owner = 1;
$is_visible = -1;
if ($userid != intval($channelInfo['owner_id'])) {
    $is_owner = 0;
    $is_visible = 1;
    if ($channelInfo['published'] != 1)
        header('Location:' . ReturnLink('channels/'));
}else {
    userSwitchChannel($channelInfo['id']);
}

$user_is_logged = 0;
if (userIsLogged()) {
    $user_is_logged = 1;
}

$userIschannel = userIsChannel();
$userIschannel = ($userIschannel) ? 1 : 0;

channelGlobalSet($channelInfo);

$fromid_check = $userid;
$is_channel_check = 0;
if ($is_owner == 0) {
    $defaultchannelarray = userCurrentChannelGet();
    if ($defaultchannelarray && sizeof($defaultchannelarray) != 0) {
        $fromid_check = $defaultchannelarray['id'];
        $is_channel_check = 1;
    }
    if (!checkChannelPrivacyExtand($channelInfo['id'], PRIVACY_EXTAND_TYPE_CONNECTIONS, $fromid_check, $is_channel_check)):
        header('Location:' . ReturnLink('/'));
    endif;
}

$userChannelInfo = getUserInfo($channelInfo['owner_id']);

$logo_src = ($channelInfo['logo']) ? photoReturnchannelLogo($channelInfo) : ReturnLink('/media/tubers/tuber.jpg');

$header_src = ($channelInfo['header']) ? photoReturnchannelHeader($channelInfo) : ReturnLink('/images/channel/coverphoto.jpg');

$bg_src = ($channelInfo['bg']) ? 'url(' . photoReturnchannelBG($channelInfo) . ') no-repeat top' : '';

$bg_src .= ($channelInfo['bgcolor']) ? ' #' . $channelInfo['bgcolor'] : '';

$channel_name = htmlEntityDecode($channelInfo['channel_name']);
$channel_desc = htmlEntityDecode($channelInfo['small_description'],0);
$channel_slogan = htmlEntityDecode($channelInfo['slogan']);

$which_section = UriGetArg(1);

$arg = UriGetArg(2);

$includes = array('css/channel-connections.css', 'assets/channel/js/channel-connections.js', 'assets/channel/js/channel-header.js', 'css/channel-header.css',
'media'=>'css_media_query/media_style.css?v='.MQ_MEDIA_STYLE_CSS_V, 
'media1'=>'css_media_query/channel_media.css?v='.MQ_CHANNEL_MEDIA_CSS_V);

tt_global_set('includes', $includes);
include($path . "twig_parts/_headChannel.php");

$arraychannellinks = GetChannelExternalLinks($channelInfo['id']);

$TubersConnected = channelConnectedTubersSearch(array('limit' => $limit, 'page' => $currentpage, 'is_visible' => $is_visible, 'channelid' => $channelInfo['id']));

$TubersNumConnected0 = channelConnectedTubersSearch(array('channelid' => $channelInfo['id'], 'is_visible' => $is_visible, 'n_results' => true));

//$TubersNumConnected1 = channelConnectedTubersSearch(array('channelid' => $channelInfo['id'],'n_results' => true,'ischannel'=>1));
$TubersNumConnected1 = socialSharesGet(array(
    'orderby' => 'share_ts',
    'order' => 'd',
    'is_visible' => $is_visible,
    'entity_id' => $channelInfo['id'],
    'entity_type' => SOCIAL_ENTITY_CHANNEL,
    'share_type' => SOCIAL_SHARE_TYPE_SPONSOR,
    'n_results' => true
        ));
$TubersNumConnected = $TubersNumConnected0;

$TubersConnectedCount = $TubersNumConnected0;

$channelimageInfoCount = mediaSearch(array(
    'channel_id' => $channelInfo['id'],
    'type' => 'i',
    'catalog_status' => 0,
    'n_results' => true
        ));
$channelvideoInfoCount = mediaSearch(array(
    'channel_id' => $channelInfo['id'],
    'type' => 'v',
    'catalog_status' => 0,
    'n_results' => true
        ));

if (!$channelvideoInfoCount)
    $channelvideoInfoCount = 0;
if (!$channelimageInfoCount)
    $channelimageInfoCount = 0;

$optionsC = array ( 'channelid' => $channelInfo['id'] ,'is_owner' => $is_owner, 'user_id' => $channelInfo['owner_id'] , 'n_results' => true );
$channelalbumInfoCount = userCatalogSearch( $optionsC );

$channelbrochuresInfoCount = channelGetBorchureInfo($channelInfo['id'], 0, 0);

$ChanelNumAlbumC = $channelalbumInfoCount;

$ChanelNumBrochures = ($channelbrochuresInfoCount) ? count($channelbrochuresInfoCount) : 0;

include("parts/channelHeader1.php");
include("parts/myChannelRight1.php");

$data['bg_src'] = $bg_src;
$data['TubersNumConnected0'] = $TubersNumConnected0;
$data['TubersNumConnected1'] = $TubersNumConnected1;
$data['searchString'] = $searchString;

include("TopChannel.php");

$txtbegins = "";
if ($txtbegins == "") {
    $txtbegins = null;
} else if ($txtbegins == "sharp") {
    $txtbegins = "#";
}
include($theLink . "twig_parts/_foot.php");
$data['txtbegins'] = $txtbegins;
$alpharr = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '#', 'all');
$data['alphArray'] = $alpharr;
for ($i = 0; $i < count($alpharr); $i++) {
    $retcount = channelConnectedTubersSearch(array('channelid' => $channelInfo['id'], 'n_results' => true, 'begins' => $alpharr[$i]));
    $retcountarr[$i] =$retcount;
}
$data['retcount'] = $retcountarr;
$data['is_owner'] = $is_owner;
$data['userIschannel'] = $userIschannel;
$data['user_is_logged'] = $user_is_logged;
$data['pagetab'] = $pagetab;
if ($is_owner == 1){
    $channelPrivacyExtand = channelPrivacyExtandSearch(array(
        'channelid' => $channelInfo['id'],
        'privacy_type' => PRIVACY_EXTAND_TYPE_CONNECTIONS
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
    
    $data['privacy_icon_val '] = $privacy_icon_val ;
    $data['PRIVACY_EXTAND_KIND_PUBLIC'] = PRIVACY_EXTAND_KIND_PUBLIC;
    $data['PRIVACY_EXTAND_KIND_CONNECTIONS'] = PRIVACY_EXTAND_KIND_CONNECTIONS;
    $data['PRIVACY_EXTAND_KIND_SPONSORS'] = PRIVACY_EXTAND_KIND_SPONSORS;
    $data['PRIVACY_EXTAND_KIND_PRIVATE'] = PRIVACY_EXTAND_KIND_PRIVATE;
    $data['PRIVACY_EXTAND_KIND_CUSTOM'] = PRIVACY_EXTAND_KIND_CUSTOM;
}
$data['TubersConnected'] = $TubersConnected;
$data['countTubersConnected'] = count($TubersConnected);
$data['channelInfoId'] = $channelInfo['id'];
if ($TubersConnected) { 
$tuberInfoArray = array();
    $i = 0;
    foreach ($TubersConnected as $tuberInfo) {
        $i++;
        $connection_id = $tuberInfo['id'];
        $create_ts = $tuberInfo['create_ts'];
        $tuber_id = $tuberInfo['userid'];
        //$one_user_info =getUserInfo($tuber_id);
        $popclass = 'popUpLeft';
        $user_stats = userGetStatistics($tuber_id,1);

        $is_visibleclass = " displaynone";
        $is_visibleclassli = "";

        if (intval($tuberInfo['is_visible']) == 0) {
            $is_visibleclass = "";
            $is_visibleclassli = " noaction";
        }

        $title = returnUserDisplayName($tuberInfo);
        $prfpic = ReturnLink('media/tubers/' . $tuberInfo['profile_Pic']);
        $uslnk = userProfileLink($tuberInfo);
        $locationval = countryGetName($tuberInfo['YourCountry']);
        if ($tuberInfo['city_id'] != '' && $tuberInfo['city_id'] != 0) {
            $city = cityGetInfo($tuberInfo['city_id']);
            $locationval = $city['name'] . ', ' . $locationval;
        }

        $usfollow = userSubscribed($userid, $tuber_id);
        $usisfriend = userIsFriend($userid, $tuber_id);
        if (!$usisfriend) {
            $usisfriend = userFreindRequestMade($userid, $tuber_id);
        }

        $usisfriendstr = "add as a friend";
        $usisfriendclass = "addFriend";
        if ($usisfriend) {
            $usisfriendstr = "unfriend";
            $usisfriendclass = "removeFriend";
        }

        $usfollowstr = "follow";
        $usfollowclass = "followFriend";
        if ($usfollow) {
            $usfollowstr = "unfollow";
            $usfollowclass = "unfollowFriend";
        }
        
        $atuberInfoArray['connection_id'] = $connection_id;
        $atuberInfoArray['tuber_id'] = $tuber_id;
        $atuberInfoArray['is_visibleclassli'] = $is_visibleclassli;
        $atuberInfoArray['is_visibleclass'] = $is_visibleclass;
        $atuberInfoArray['prfpic'] = $prfpic;
        $atuberInfoArray['title'] = $title;
        
        $str = '<a class="uslnk" href="' . $uslnk . '" title="' . $title . '"><div>' . $title . '</div></a> <div class="userlocation">' . $locationval . '</div>';
        $atuberInfoArray['str'] = $str;

        $ts_date = strtotime($tuberInfo['create_ts']);
        $print_date = returnSocialTimeFormat($tuberInfo['create_ts'],3);

        $str = t(_('<div>connected since %when</div>'), array('%when' => $print_date));
        $atuberInfoArray['str2'] = $str;
        $atuberInfoArray['popclass'] = $popclass;
        $atuberInfoArray['nFriends'] = tt_number_format($user_stats['nFriends']);
        $atuberInfoArray['getnFriends']=ngettext("friend", "friends", $user_stats['nFriends']);
        $atuberInfoArray['nFollowers'] = tt_number_format($user_stats['nFollowers']);
        $atuberInfoArray['getnFollowers']=ngettext("follower", "followers", $user_stats['nFollowers']);
        $atuberInfoArray['userIschannel'] = $userIschannel;
        $atuberInfoArray['userid'] = $userid;
        $atuberInfoArray['usisfriendclass'] = $usisfriendclass;
        $atuberInfoArray['usisfriendstr'] = $usisfriendstr;
        $atuberInfoArray['usfollowclass'] = $usfollowclass;
        $atuberInfoArray['usfollowstr'] = $usfollowstr;
    }
    $data['tuberInfoArray'] = $atuberInfoArray;
    $pagcount = floor($TubersConnectedCount / $limit);
    if (($TubersConnectedCount % $limit) != 0) {
        $pagcount++;
    }
    $data['pagcount'] = $pagcount;
    $data['currentpage'] = $currentpage;
    $data['getlink'] = GetLink('channel-connections/' . $currentchannelname . '/page/' . ($currentpage + 1));
    $data['getlink2'] = GetLink('channel-connections/' . $currentchannelname . '/page/' . ($currentpage - 1));
    $data['uripage'] = UriArgIsset('page');
    
} 
include("closing-footer.php");
include($path . "twig_parts/_foot.php");