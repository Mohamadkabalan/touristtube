<?php
    
$path = "";
$limit=30;
$currentpage=0;

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
$template = $twig->loadTemplate('channel-photos.twig');

$channel = db_sanitize(UriGetArg(0));

if( UriArgIsset('page') ):
        $currentpage=intval(UriGetArg('page'));
else:
        $currentpage=0;
endif;

$txt_srch_init = xss_sanitize(UriGetArg('search'));
$txt_srch = ($txt_srch_init=="") ? null : $txt_srch_init;

$txt_id_init = intval(UriGetArg('id'));
$txt_id = ($txt_id_init==0) ? null : $txt_id_init;	


$currentchannelname = $channel;

$channelInfo = channelFromURL($channel);

if( $channelInfo === false )
    header("location:".ReturnLink('channels/'));

$userid=userGetID();
$is_owner=1;
if($userid!=intval($channelInfo['owner_id'])){
        $is_owner=0;
        if($channelInfo['published']!=1) header('Location:' . ReturnLink('channels/') );
}else{
        userSwitchChannel($channelInfo['id']);	
}
$user_is_logged=0;
if(userIsLogged()){
        $user_is_logged=1;	
}
$userIschannel=userIsChannel();
$userIschannel = ($userIschannel) ? 1 : 0;

channelGlobalSet($channelInfo);


$channelPrivacyArray=GetChannelNotifications($channelInfo['id']);
$channelPrivacyArray=$channelPrivacyArray[0];

$userChannelInfo = getUserInfo($channelInfo['owner_id']);

$logo_src = ($channelInfo['logo']) ? photoReturnchannelLogo($channelInfo) : ReturnLink('/media/tubers/tuber.jpg');

$header_src = ($channelInfo['header']) ? photoReturnchannelHeader($channelInfo) : ReturnLink('/images/channel/coverphoto.jpg');

$bg_src = ($channelInfo['bg']) ? 'url('.photoReturnchannelBG($channelInfo).') no-repeat top' : '';

$bg_src .= ($channelInfo['bgcolor']) ? ' #'.$channelInfo['bgcolor'] : '';

$channel_name = htmlEntityDecode($channelInfo['channel_name']);
$channel_desc = htmlEntityDecode($channelInfo['small_description'],0);
$channel_slogan = htmlEntityDecode($channelInfo['slogan']);

$which_section = UriGetArg(1);

$arg = UriGetArg(2);

$includes = array('css/channel-photos.css','assets/channel/js/channel-photos.js','assets/channel/js/channel-header.js','css/channel-header.css',
    'media'=>'css_media_query/media_style.css?v='.MQ_MEDIA_STYLE_CSS_V, 
    'media1'=>'css_media_query/channel_media.css?v='.MQ_CHANNEL_MEDIA_CSS_V);

tt_global_set('includes', $includes);

$arraychannellinks = GetChannelExternalLinks($channelInfo['id']);

$TubersNumConnected = channelConnectedTubersSearch(array('channelid' => $channelInfo['id'],'n_results' => true ));
$TubersNumConnected0 = $TubersNumConnected;

$is_owner_visible=-1;
$is_visible =-1;
if( $is_owner ==0 ){
    $is_owner_visible=1;
    $is_visible=1;
}
$TubersNumConnected1 = socialSharesGet(array(
        'orderby' => 'share_ts',
        'order' => 'd',
        'is_visible'=>$is_owner_visible,
        'entity_id' => $channelInfo['id'],
        'entity_type' => SOCIAL_ENTITY_CHANNEL,
        'share_type' => SOCIAL_SHARE_TYPE_SPONSOR,
        'n_results' => true
));
$channelimageInfo = mediaSearch(array(
        'channel_id' => $channelInfo['id'],
        'type' => 'i',
        'page' =>$currentpage,
        'media_id'=>$txt_id,
        'limit' =>$limit,
        'search_string' =>$txt_srch,
        'search_where' => 't',
        'similarity' => 't',
        'search_strict' => 0,
        'catalog_status' => 0,
        'orderby' => 'pdate',
        'order' => 'd'
));

$channelimageInfoCount = mediaSearch(array(
        'channel_id' => $channelInfo['id'],
        'type' => 'i',
        'media_id'=>$txt_id,
        'search_string' =>$txt_srch,
        'search_where' => 't',
        'similarity' => 't',
        'search_strict' => 0,
        'catalog_status' => 0,
        'n_results' => true
));
$channelvideoInfoCount = mediaSearch(array(
        'channel_id' => $channelInfo['id'],
        'type' => 'v',
        'catalog_status' => 0,
        'n_results' => true
));


$optionsC = array ( 'channelid' => $channelInfo['id'] , 'user_id' => $channelInfo['owner_id'],'is_owner' => $is_owner , 'n_results' => true );

$channelalbumInfoCount = userCatalogSearch( $optionsC );

$channelbrochuresInfoCount = channelGetBorchureInfo($channelInfo['id'],0,0);

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

$data['channelimageInfoCount'] = $channelimageInfoCount;
$data['channelimageInfo'] = $channelimageInfo;

$data['formatChannelvideoInfoCount'] = tt_number_format($channelimageInfoCount);
$data['countChannelimageInfo'] = count($channelimageInfo);

$photoInfoArr = array();
$i=0;
foreach($channelimageInfo as $VidInfo){
    $i++;
    $vid_id = $VidInfo['id'];
    $vid_code = $VidInfo['code'];
    $pdate = strtotime($VidInfo['pdate']);
    $Title_pic = htmlEntityDecode($VidInfo['title']);
    $Title = cut_sentence_length($Title_pic, 48, 25);
    $description_db = htmlEntityDecode($VidInfo['description']);
    $Description=str_replace("\n","<br/>",$description_db);
    if($Description!='') $Description = substr($Description,0,130).'...';

    $likes = $VidInfo['like_value'];
    $rating = ceil($VidInfo['rating']);
    $duration = $VidInfo['duration'];
    $views = tt_number_format($VidInfo['nb_views']);
    $mediauri = ReturnPhotoUri($VidInfo);
    
    $aphotoInfoArr['vid_id'] = $vid_id;
    $aphotoInfoArr['mediauri'] = $mediauri;
    $aphotoInfoArr['Title'] = $Title;
    $aphotoInfoArr['photoXSmall'] = photoReturnSrcXSmall($VidInfo);;
    $aphotoInfoArr['views'] = $views;
    $aphotoInfoArr['displayViewsCount'] = displayViewsCount($VidInfo['nb_views'],0);;
    $aphotoInfoArr['formatDate'] = formatDateAsString($pdate);;
    $aphotoInfoArr['Description'] = $Description;
    $aphotoInfoArr['Videocan_comment'] = $VidInfo['can_comment'];
    $aphotoInfoArr['Videocan_share'] = $VidInfo['can_share'];
    $aphotoInfoArr['privacy_social'] = intval($channelPrivacyArray['privacy_social']);
    $aphotoInfoArr['user_is_logged'] = $user_is_logged;
    
    
    $photoInfoArr[] = $aphotoInfoArr;
}
$data['photoInfoArr'] = $photoInfoArr;


$pagcount= floor($channelimageInfoCount/$limit);
if(($channelimageInfoCount%$limit)!=0){
        $pagcount++;
}

$data['pagcount'] = $pagcount;
$data['currentpage'] = $currentpage;
$data['loadMoreLink'] = ReturnLink('channel-photos/' . $currentchannelname . '/page/' . ($currentpage + 1));
$data['pageArg'] = UriArgIsset('page');
$data['loadPreviousLink'] = ReturnLink('channel-photos/' . $currentchannelname . '/page/' . ($currentpage - 1));

$data['channel_type'] = $channel_type;
$data['CHANNEL_TYPE_DIASPORA'] = CHANNEL_TYPE_DIASPORA;


include("parts/channelNewsTicker1.php");
include($theLink . "twig_parts/_foot.php");
echo $template->render($data);
