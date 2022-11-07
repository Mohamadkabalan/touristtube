<?php
$limit = 18;
$currentpage = 0;
$path = "";
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
$template = $twig->loadTemplate('channel-sponsors.twig');

$showconnectionsmenu = 1;

$channel = db_sanitize(UriGetArg(0));
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
$pagetab = 2;
$searchString = 'find TChannels';

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
    if (!checkChannelPrivacyExtand($channelInfo['id'], PRIVACY_EXTAND_TYPE_SPONSORS, $fromid_check, $is_channel_check)):
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

$includes = array('css/channel-connections.css', 'assets/channel/js/channel-sponsors.js', 'assets/channel/js/channel-header.js', 'css/channel-header.css', 
'media'=>'css_media_query/media_style.css?v='.MQ_MEDIA_STYLE_CSS_V, 
'media1'=>'css_media_query/channel_media.css?v='.MQ_CHANNEL_MEDIA_CSS_V
	);
tt_global_set('includes', $includes);

$arraychannellinks = GetChannelExternalLinks($channelInfo['id']);

$TubersConnected = socialSharesGet(array(
    'orderby' => 'share_ts',
    'order' => 'd',
    'limit' => $limit,
    'page' => $currentpage,
    'is_visible' => $is_visible,
    'entity_id' => $channelInfo['id'],
    'entity_type' => SOCIAL_ENTITY_CHANNEL,
    'share_type' => SOCIAL_SHARE_TYPE_SPONSOR
        ));
//var_dump($TubersConnected);
$TubersNumConnected0 = channelConnectedTubersSearch(array('channelid' => $channelInfo['id'], 'n_results' => true));

$TubersNumConnected1 = socialSharesGet(array(
    'orderby' => 'share_ts',
    'order' => 'd',
    'is_visible' => $is_visible,
    'entity_id' => $channelInfo['id'],
    'entity_type' => SOCIAL_ENTITY_CHANNEL,
    'share_type' => SOCIAL_SHARE_TYPE_SPONSOR,
    'n_results' => true
        ));
$TubersNumConnected = $TubersNumConnected1;

$TubersConnectedCount = $TubersNumConnected1;

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

$optionsC = array ( 'channelid' => $channelInfo['id'],'is_owner' => $is_owner , 'user_id' => $channelInfo['owner_id'] , 'n_results' => true );
$channelalbumInfoCount = userCatalogSearch( $optionsC );

$channelbrochuresInfoCount = channelGetBorchureInfo($channelInfo['id'], 0, 0);
$ChanelNumAlbumC = $channelalbumInfoCount;

$ChanelNumBrochures = ($channelbrochuresInfoCount) ? count($channelbrochuresInfoCount) : 0;
include($path . "twig_parts/_headChannel.php");


$data['pagetab']= $pagetab;
$data['channelInfoId']= $channelInfo['id'];
$data['is_owner']= $is_owner;
$data['user_is_logged']= $user_is_logged;

$data['bg_src'] =$bg_src;
include("parts/channelHeader1.php");
include("parts/myChannelRight1.php");

$data['TubersNumConnected1'] = $TubersNumConnected1;

$data['searchString'] = $searchString;
$data['is_owner'] = $is_owner;

if($TubersNumConnected1 > 0 ){
    $txtbegins = "";
    if ($txtbegins == "") {
        $txtbegins = null;
    } else if ($txtbegins == "sharp") {
        $txtbegins = "#";
    }
    
    $alpharr = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '#', 'all');
    for ($i = 0; $i < count($alpharr); $i++) {
        $retcount = socialSharesGet(array(
            'orderby' => 'share_ts',
            'order' => 'd',
            'begins' => $alpharr[$i],
            'entity_id' => $channelInfo['id'],
            'entity_type' => SOCIAL_ENTITY_CHANNEL,
            'share_type' => SOCIAL_SHARE_TYPE_SPONSOR,
            'n_results' => true
        ));
        $retcountarr[$i] =$retcount;
    }
    if ($is_owner == 1){
        $channelPrivacyExtand = channelPrivacyExtandSearch(array(
            'channelid' => $channelInfo['id'],
            'privacy_type' => PRIVACY_EXTAND_TYPE_SPONSORS
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
}
$data['privacy_icon_val'] = $privacy_icon_val;
$data['privacy_icon_str'] = $privacy_icon_str;
$data['PRIVACY_EXTAND_KIND_PUBLIC'] = PRIVACY_EXTAND_KIND_PUBLIC;
$data['PRIVACY_EXTAND_KIND_CONNECTIONS'] = PRIVACY_EXTAND_KIND_CONNECTIONS;
$data['PRIVACY_EXTAND_KIND_SPONSORS'] = PRIVACY_EXTAND_KIND_SPONSORS;
$data['PRIVACY_EXTAND_KIND_PRIVATE'] = PRIVACY_EXTAND_KIND_PRIVATE;
$data['PRIVACY_EXTAND_KIND_CUSTOM'] = PRIVACY_EXTAND_KIND_CUSTOM;
$data['retcount'] = $retcountarr;
$data['txtbegins'] = $txtbegins;
$data['alphArray'] = $alpharr;
$data['TubersNumConnected0'] = $TubersNumConnected0;
$data['TubersNumConnected1'] = $TubersNumConnected1;
$data['TubersConnected'] = $TubersConnected;
$data['userid'] = $userid;

$i = 0;
foreach ($TubersConnected as $tuberInfo) {
    $i++;

    $is_visibleclass = " displaynone";
    $is_visibleclassli = "";
    if (intval($tuberInfo['is_visible']) == 0) {
        $is_visibleclass = "";
        $is_visibleclassli = " noaction";
    }

    $sponsor_id = $tuberInfo['sp_id'];
    $sponsor_owner_id = $tuberInfo['owner_id'];
    $create_ts = $tuberInfo['share_ts'];
    $channel_id = $tuberInfo['from_user'];
    $popclass = 'popUpLeft';

    $title = htmlEntityDecode($tuberInfo['channel_name']);
    $currentchannelname = $tuberInfo['channel_url'];
    if ($tuberInfo['logo'] == '') {
        $prfpic = ReturnLink('/media/tubers/tuber.jpg');
    } else {
        $prfpic = ReturnLink('media/channel/' . $channel_id . '/thumb/' . $tuberInfo['logo']);
    }
    $uslnk = ReturnLink('/channel/' . $currentchannelname);

    $locationval = countryGetName($tuberInfo['country']);
    if ($tuberInfo['city_id'] != '' && $tuberInfo['city_id'] != 0) {
        $city = cityGetInfo($tuberInfo['city_id']);
        $locationval = $city['name'] . ', ' . $locationval;
    }
    $atubersSponsorsInfo['sponsor_id'] = $sponsor_id; 
    $atubersSponsorsInfo['channel_id'] = $channel_id; 
    $atubersSponsorsInfo['is_visibleclassli'] = $is_visibleclassli; 
    $atubersSponsorsInfo['is_visibleclass'] = $is_visibleclass; 
    $atubersSponsorsInfo['title'] = $title; 
    $atubersSponsorsInfo['is_visibleclass'] = $is_visibleclass; 
    $atubersSponsorsInfo['sponsor_owner_id'] = $sponsor_owner_id;
    $atubersSponsorsInfo['prfpic'] = $prfpic;
    
    $str = '<a class="uslnk" href="' . $uslnk . '" title="' . $title . '"><div>' . $title . '</div></a> <div class="userlocation">' . $locationval . '</div>';
    $atubersSponsorsInfo['str'] = $str; 
    $ts_date = strtotime($create_ts);
    $print_date = returnSocialTimeFormat($create_ts,3);
    
    $str = t(_('<div>sponsor since %when</div>'), array('%when' => $print_date));
    $atubersSponsorsInfo['str2'] = $str; 
    
    $atubersSponsorsInfo['popclass'] = $popclass;
    
    $TubersNumConn0 = channelConnectedTubersSearch(array('channelid' => $channel_id, 'n_results' => true));

    $TubersNumConn1 = socialSharesGet(array(
        'orderby' => 'share_ts',
        'order' => 'd',
        'entity_id' => $channel_id,
        'entity_type' => SOCIAL_ENTITY_CHANNEL,
        'share_type' => SOCIAL_SHARE_TYPE_SPONSOR,
        'n_results' => true
    ));
    $TubersNumConn = $TubersNumConn0;
    $atubersSponsorsInfo['TubersNumConn'] = tt_number_format($TubersNumConn);
    $atubersSponsorsInfo['getTubersNumConn'] = ngettext("connection", "connections", $TubersNumConn);
    $atubersSponsorsInfo['uslnk'] = $uslnk;
    if ($is_owner == 1){
        
    } else if ($userid == $sponsor_owner_id) {
                                                                
    } else if ($userIschannel == 0) {
        $connectedTubers = getConnectedtubers($channel_id);
        $data['checkconnectedTubers'] = in_array($userid, $connectedTubers);
        
    } else {
        $defaultchannelarray = userCurrentChannelGet();
        $defaultchannelid = $defaultchannelarray["id"];

        $connected_channel = getSponsoredChannel($channel_id);
        $data['checkconnected_channel'] = in_array($userid, $connectedTubers);
    }
    $tubersSponsorsInfo[] = $atubersSponsorsInfo;
}

$data['tubersSponsorsInfoArray'] = $tubersSponsorsInfo;

$pagcount = floor($TubersConnectedCount / $limit);
if (($TubersConnectedCount % $limit) != 0) {
    $pagcount++;
}
$data['currentpage'] = $currentpage;
$data['pagcount'] = $pagcount;
$data['pagelink'] = ReturnLink('channel-sponsors/' . $currentchannelname . '/page/' . ($currentpage + 1)); 
$data['page2'] = UriArgIsset('page');
$data['pagelink2'] = ReturnLink('channel-sponsors/' . $currentchannelname . '/page/' . ($currentpage - 1));

include("parts/channelNewsTicker1.php");
include($theLink . "twig_parts/_foot.php");
echo $template->render($data);