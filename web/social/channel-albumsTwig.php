<?php
$path = "";
$limit = 30;
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
$template = $twig->loadTemplate('channel-albums.twig');

$channel = xss_sanitize(UriGetArg(0));

if (UriArgIsset('page')):
    $currentpage = intval(UriGetArg('page'));
else:
    $currentpage = 0;
endif;

$txt_srch_init = xss_sanitize(UriGetArg('search'));
$txt_srch = ($txt_srch_init == "") ? null : $txt_srch_init;

$txt_id_init = intval(UriGetArg('id'));
$txt_id = ($txt_id_init == 0) ? null : $txt_id_init;


$currentchannelname = $channel;

$channelInfo = channelFromURL($channel);

if ($channelInfo === false)
            header("location:".ReturnLink('/'));


$userid = userGetID();
$is_owner = 1;
if ($userid != intval($channelInfo['owner_id'])) {
    $is_owner = 0;
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

$channelPrivacyArray = GetChannelNotifications($channelInfo['id']);
$channelPrivacyArray = $channelPrivacyArray[0];

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

$includes = array('css/channel-photos.css', 'assets/channel/js/channel-photos.js', 'assets/channel/js/channel-header.js', 'css/channel-header.css',
'media'=>'css_media_query/media_style.css?v='.MQ_MEDIA_STYLE_CSS_V,
'media1'=>'css_media_query/channel_media.css?v='.MQ_CHANNEL_MEDIA_CSS_V);

tt_global_set('includes', $includes);

$arraychannellinks = GetChannelExternalLinks($channelInfo['id']);

$TubersNumConnected = channelConnectedTubersSearch(array('channelid' => $channelInfo['id'], 'n_results' => true));
$TubersNumConnected0 = $TubersNumConnected;
$is_visible = -1;
$is_owner_visible = -1;
if ($is_owner == 0) {
    $is_visible = 1;
    $is_owner_visible = 1;
}
$TubersNumConnected1 = socialSharesGet(array(
    'orderby' => 'share_ts',
    'order' => 'd',
    'is_visible' => $is_visible,
    'entity_id' => $channelInfo['id'],
    'entity_type' => SOCIAL_ENTITY_CHANNEL,
    'share_type' => SOCIAL_SHARE_TYPE_SPONSOR,
    'n_results' => true
        ));
//$TubersNumConnected=$TubersNumConnected+$TubersNumConnected1;

$options = array ( 'limit' => $limit,'is_owner' => $is_owner, 'page' => $currentpage, 'channelid' => $channelInfo['id'], 'user_id' => $channelInfo['owner_id'], 'id' =>$txt_id , 'search_string' => $txt_srch, 'order' => 'd', 'orderby' => 'id' );
$channelalbumInfo = userCatalogSearch( $options );
$optionsC = array ( 'channelid' => $channelInfo['id'], 'user_id' => $channelInfo['owner_id'],'is_owner' => $is_owner, 'id' =>$txt_id , 'search_string' => $txt_srch , 'n_results' => true );
$channelalbumInfoCount = userCatalogSearch( $optionsC );

$channelvideoInfoCount = mediaSearch(array(
    'channel_id' => $channelInfo['id'],
    'type' => 'v',
    'catalog_status' => 0,
    'n_results' => true
        ));
$channelimageInfoCount = mediaSearch(array(
    'channel_id' => $channelInfo['id'],
    'type' => 'i',
    'catalog_status' => 0,
    'n_results' => true
));

$channelbrochuresInfoCount = channelGetBorchureInfo($channelInfo['id'], 0, 0);
$ChanelNumAlbumC = $channelalbumInfoCount;

$ChanelNumBrochures = ($channelbrochuresInfoCount) ? count($channelbrochuresInfoCount) : 0;
$channel_type = $channelInfo['channel_type'];


include($path . "twig_parts/_headChannel.php");
$data['channelInfoId'] = $channelInfo['id'];
$data['txt_srch_init'] = $txt_srch_init;
$data['userIschannel'] = $userIschannel;
$data['user_is_logged'] = $user_is_logged;
$data['is_owner'] = $is_owner;

$data['bg_src'] = $bg_src;

include("parts/channelHeader1.php");
include("parts/myChannelRight1.php");

$data['ChanelNumAlbumC'] = $ChanelNumAlbumC;
$data['channelalbumInfo'] = $channelalbumInfo;

$data['formatChanelNumAlbumC'] = tt_number_format($ChanelNumAlbumC);
$data['channelimageInfoCount'] =$channelimageInfoCount;

$albumInfoArr = array();
$i = 0;
foreach ($channelalbumInfo as $VidInfo) {
    $i++;

    $vid_id = $VidInfo['id'];

    $vinfo = userCatalogDefaultMediaGet($vid_id);

    $pdate = strtotime($VidInfo['catalog_ts']);

    $Title_pic = htmlEntityDecode($VidInfo['catalog_name']);
    $Title = cut_sentence_length($Title_pic, 48, 25);
    $description_db = htmlEntityDecode($VidInfo['description'],0);
    $Description = str_replace("\n", "<br/>", $description_db);
    if ($Description != '')
        $Description = substr($Description, 0,80) . '...';
    $views = tt_number_format($VidInfo['nb_views']);

    if ($vinfo != false)
        $thumb = photoReturnSrcXSmall($vinfo);
    else
        $thumb = ReturnLink('images/empty.jpg');


    $mediauri = ReturnAlbumUri($VidInfo);

    $options2 = array('catalog_id' => $VidInfo['id'], 'channel_id' => $VidInfo['channelid'], 'type' => 'a', 'is_owner' => $is_owner, 'n_results' => true);
    $album_media_count = mediaSearch($options2);
    
    $aAlbumInfoArr['vid_id'] = $vid_id;
    $aAlbumInfoArr['album_media_count'] = $album_media_count;
    $aAlbumInfoArr['mediauri'] = $mediauri;
    $aAlbumInfoArr['Title'] = $Title;
    $aAlbumInfoArr['thumb'] = $thumb;
    $aAlbumInfoArr['views'] = $views;
    $aAlbumInfoArr['displayViewsCount'] = displayViewsCount($VidInfo['nb_views'],0);
    $aAlbumInfoArr['formatDate'] = formatDateAsString($pdate);
    $aAlbumInfoArr['Description'] = $Description;
    $aAlbumInfoArr['catalog_name'] = htmlEntityDecode($VidInfo['catalog_name']);
    $aAlbumInfoArr['can_comment'] = $VidInfo['can_comment'];
    $aAlbumInfoArr['can_share'] = $VidInfo['can_share'];
    $aAlbumInfoArr['privacy_social'] = intval($channelPrivacyArray['privacy_social']);
    $aAlbumInfoArr['user_is_logged'] = $user_is_logged;
    
    $albumInfoArr[] = $aAlbumInfoArr;
}
$data['albumInfoArr'] = $albumInfoArr;


$pagcount= floor($channelimageInfoCount/$limit);
if(($channelimageInfoCount%$limit)!=0){
        $pagcount++;
}

$data['pagcount'] = $pagcount;
$data['currentpage'] = $currentpage;
$data['loadMoreLink'] = ReturnLink('channel-albums/' . $currentchannelname . '/page/' . ($currentpage + 1));
$data['pageArg'] = UriArgIsset('page');
$data['loadPreviousLink'] = ReturnLink('channel-albums/' . $currentchannelname . '/page/' . ($currentpage - 1));

$data['channel_type'] = $channel_type;
$data['CHANNEL_TYPE_DIASPORA'] = CHANNEL_TYPE_DIASPORA;

include("parts/channelNewsTicker1.php");
include($theLink . "twig_parts/_foot.php");
echo $template->render($data);
