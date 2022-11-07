<?php
$path = "";

$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/twigFct.php" );
$theLink = $CONFIG ['server']['root'];
//require_once $theLink . 'vendor/autoload.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($theLink . 'twig_templates/');
$twig = new Twig_Environment($loader, array(
    'cache' => $theLink . 'twig_cache/', 'debug' => true,
));
$twig->addExtension(new Twig_Extension_twigTT());
$twig->addExtension(new Twig_Extension_Debug());/* used for debug */
$template = $twig->loadTemplate('video.twig');

//arg comes from the search freindly uri
$video_url = UriGetArg(0); // $_GET['arg'];
$category_title = '';
$category_link='';
if (isset($album_page) && $album_page) {
    $album_page = true;
    $AlbumInfo = albumFromURL($video_url);

    if ($AlbumInfo === false || $AlbumInfo['published'] == 0 || !userPermittedForMedia(userGetID(), $AlbumInfo, SOCIAL_ENTITY_ALBUM)) {
        header('Location:' . ReturnLink(''));
        exit;
    }
    $VideoInfo = userCatalogDefaultMediaGet($AlbumInfo['id']);
    if (!$VideoInfo) {
        header('Location:' . ReturnLink(''));
        exit;
    }
    if (intval($AlbumInfo['category']) > 0) {
        $cats = categoryGetName($AlbumInfo['category']);
        if ($cats) {
            $category_title = _('from category:') . ' ' . $cats;
            $category_link = ReturnLink('search/c/' . $AlbumInfo['category']);
        }
    }
    //userCatalogView($AlbumInfo['id']);
    $photo_page = true;
    $vid = $VideoInfo['id'];
    $v_title = htmlEntityDecode($AlbumInfo['catalog_name']);
    $v_title_share = htmlEntityDecode($AlbumInfo['catalog_name']);
    $p_title = htmlEntityDecode($AlbumInfo['catalog_name']);
    if ($AlbumInfo['cityname'] != '') {
        $v_title = ucwords($AlbumInfo['cityname']) . ' - ' . $v_title_share;
        $p_title = $v_title_share . " - " . $AlbumInfo['cityname'];
    }

    $nb_comments = $AlbumInfo['nb_comments'];
    $nb_comments_str = displayCommentsCount($AlbumInfo['nb_comments'],0);
    $like_value = $AlbumInfo['like_value'];
    $like_value_str = displayLikesCount($AlbumInfo['like_value'],0);
    $rating_data = round($AlbumInfo['rating'], 0);
    $nb_rating = $AlbumInfo['nb_ratings'];
    $nb_rating_str = displayRatingsCount($AlbumInfo['nb_ratings'],0);
    $nb_views = $AlbumInfo['nb_views'];
    $nb_views_str = displayViewsCount($AlbumInfo['nb_views'],0);
    $nb_shares = $AlbumInfo['nb_shares'];
    $nb_shares_str = displaySharesCount($AlbumInfo['nb_shares'],0);

    // for social media
    $data_action_id = $AlbumInfo['id'];
    $current_id = $data_action_id;
    $entity_type = SOCIAL_ENTITY_ALBUM;
    $creator_ts = strtotime($AlbumInfo['catalog_ts']);
    $pic_title = htmlEntityDecode($AlbumInfo['catalog_name']);
    $description_db = htmlEntityDecode($AlbumInfo['description'],0);
    $description = str_replace("\n", "<br/>", $description_db);
    $country = $AlbumInfo['country'];
    $locationval = countryGetName($country);
    if ($AlbumInfo['cityname'] != '') {
        $locationval = $AlbumInfo['cityname'] . ' - ' . $locationval;
    }

    $catEnable = intval($AlbumInfo['can_comment']);
    if (intval($AlbumInfo['can_share'])) {
        $catEnable = intval($AlbumInfo['can_share']);
    }
    $privacy_icon_val = $AlbumInfo['is_public'];
    // end for social media
} else {
    $album_page = false;
//	$VideoInfo = videoFromURL($video_url);
    $VideoInfo = videoFromURLHashed($video_url);
    if ($VideoInfo === false) {
        header('Location:' . ReturnLink(''));
        exit;
    }
    $vid = $VideoInfo['id'];
    //VideoIncViews($vid);

    $current_id = $vid;
    if (($VideoInfo['published'] == 0) || !userPermittedForMedia(userGetID(), $VideoInfo, SOCIAL_ENTITY_MEDIA)) {
        header('Location:' . ReturnLink(''));
        exit;
    }
    if (intval($VideoInfo['category']) > 0) {
        $cats = categoryGetName($VideoInfo['category']);
        if ($cats) {
            $category_title = _('from category:') . ' ' . $cats;
            $category_link = ReturnLink('search/c/' . $VideoInfo['category']);
        }
    }
    $v_title = htmlEntityDecode($VideoInfo['title']);
    $v_title_share = $v_title;
    $p_title = $v_title;
    if ($VideoInfo['cityname'] != '') {
        $v_title = ucwords($VideoInfo['cityname']) . ' - ' . $v_title;
        $p_title = $v_title . " - " . $VideoInfo['cityname'];
    }
    $_tt_global_variables['title'] = $p_title;
    
    $nb_comments = $VideoInfo['nb_comments'];
    $nb_comments_str = displayCommentsCount($VideoInfo['nb_comments'],0);
    $like_value = $VideoInfo['like_value'];
    $like_value_str = displayLikesCount($VideoInfo['like_value'],0);
    $rating_data = round($VideoInfo['rating'], 0);
    $nb_rating = $VideoInfo['nb_ratings'];
    $nb_rating_str = displayRatingsCount($VideoInfo['nb_ratings'],0);
    $nb_views = $VideoInfo['nb_views'];
    if($VideoInfo['image_video'] == "v"){
        $nb_views_str = displayPlaysCount($VideoInfo['nb_views'],0);
    }else{
        $nb_views_str = displayViewsCount($VideoInfo['nb_views'],0);
    }    
    $nb_shares = $VideoInfo['nb_shares'];
    $nb_shares_str = displaySharesCount($VideoInfo['nb_shares'],0);

    // for social media
    $data_action_id = $vid;
    $entity_type = SOCIAL_ENTITY_MEDIA;
    $creator_ts = strtotime($VideoInfo['pdate']);
    $pic_title = htmlEntityDecode($VideoInfo['title']);
    $description_db = htmlEntityDecode($VideoInfo['description'],0);
    $description = str_replace("/\n", "<br/>", $description_db);
    $country = $VideoInfo['country'];
    $locationval = countryGetName($country);
    if ($VideoInfo['cityname'] != '') {
        $locationval = $VideoInfo['cityname'] . ' - ' . $locationval;
    }

    $catEnable = intval($VideoInfo['can_comment']);
    if (intval($VideoInfo['can_share'])) {
        $catEnable = intval($VideoInfo['can_share']);
    }
    $privacy_icon_val = $VideoInfo['is_public'];
    // end for social media
}
if (isset($photo_page)) {
    $photo_page = true;
} else {
    $photo_page = false;
}
if (isset($media_album_page)) {
    $media_album_page = true;
} else {
    $media_album_page = false;
}

$loggedUser = userGetID();
$userInfo_loggedUser = getUserInfo($loggedUser);
$userId = $VideoInfo['userid'];

if ($loggedUser != intval($VideoInfo['userid'])) {
    $is_owner = 0;
} else {
    $is_owner = 1;
}
$user_is_logged = 0;
if (userIsLogged()) {
    $user_is_logged = 1;
}

$channelid = intval($VideoInfo['channelid']);
if ($channelid == 0 && !userIsChannel()) {
    userCurrentChannelReset();
}

$userIschannel = userIsChannel();
$userIschannel = ($userIschannel) ? 1 : 0;

$current_channel = userCurrentChannelGet();
if ($current_channel && sizeof($current_channel) != 0) {
    $userIschannel = 1;
}

$userInfo = getUserInfo($VideoInfo['userid']);

$usisfriend = true;
$usfollow = true;
if ($user_is_logged && !$is_owner) {
    $usisfriend = userIsFriend($loggedUser, $userInfo['id']);
    if (!$usisfriend) {
        $usisfriend = userFreindRequestMade($loggedUser, $userInfo['id']);
    }
    $usfollow = userSubscribed($loggedUser, $userInfo['id']);
}
$creator_avatar_link='';
if ($channelid != 0) {
    $channelInfo = channelGetInfo($channelid);
    $logo_srcbig = ($channelInfo['logo']) ? photoReturnchannelLogoBig($channelInfo) : '';
    $data['originalPP'] = $logo_srcbig;
    if ($userInfo['id'] == intval($channelInfo['owner_id'])) {
        $tourist_since_date = _('TChannel since')." " . returnSocialTimeFormat( $channelInfo['create_ts'] ,6); 
        $creator_name = htmlEntityDecode($channelInfo['channel_name']);
        $creator_avatar_name = htmlEntityDecode($channelInfo['channel_name']);
        $creator_avatar_link = ReturnLink('channel/' . $channelInfo['channel_url']);
        if (strlen($creator_name) > 32) {
            $creator_name = substr($creator_name, 0, 32) . ' ...';
        }
        $creator_img = ($channelInfo['logo']) ? photoReturnchannelLogo($channelInfo) : ReturnLink('/media/tubers/tuber.jpg');
        $creator_img1 = ($channelInfo['logo']) ? photoReturnchannelLogo($channelInfo) : ReturnLink('/media/tubers/small_tuber.jpg');
    }
} else {
    $creator_img = ReturnLink('media/tubers/' . $userInfo['profile_Pic']);
    $creator_img1 = ReturnLink('media/tubers/small_' . $userInfo['profile_Pic']);
    $originalPP = ReturnLink('media/tubers/'. getOriginalPP($userInfo['profile_Pic']));
    $data['originalPP'] = $originalPP;
    $creator_name = returnUserDisplayName($userInfo, array('max_length' => 25));
    $creator_avatar_name = returnUserDisplayName($userInfo);
    $creator_avatar_link = userProfileLink($userInfo).'/TTpage';
    $tourist_since_date = _('Tourist tuber since').' ' . returnSocialTimeFormat($userInfo['RegisteredDate'],6);
}
$userIsLogged = userIsLogged();
$userIsChannel = userIsChannel();

$data['userIsLogged'] = $userIsLogged;
$data['userIsChannel'] = $userIsChannel;
$data['channelid'] = $channelid;




$includes = array('js/social-actions.js', 'css/social-actions.css?v='.SOCIAL_ACTIONS_CSS_V, 'css/media_page.css?v='.MEDIA_PAGE_CSS_V, 'js/media_page.js',
//** Add 12 March for photo swiper
 'js/swiper-photo.js',
 'css_media_query/swiper-photo.min.css?v='.MQ_SWIPER_PHOTO_MIN_CSS_V,
//** ------------------------------------------------
 'media'=>'css_media_query/media_style.css?v='.MQ_MEDIA_STYLE_CSS_V,
 'media1'=>'css_media_query/video_media.css?v='.MQ_VIDEO_MEDIA_CSS_V);


if ($photo_page || $album_page) {
    $includes[] = 'js/jquery.fullscreen-min.js';
    $includes[] = 'js/smallphoto-behavior.js';
}
if ($channelid == 0) {
    $includes[] = "assets/channel/js/channel-home-upload-behavior.js";
    
}

if ($channelid != 0) {
    array_unshift($includes, 'css/channel-header.css');
    tt_global_set('includes', $includes);
    include($theLink . "twig_parts/_headChannel.php");
} else {
    if (userIsLogged() && userIsChannel()) {
        array_unshift($includes, 'css/channel-header.css');
        tt_global_set('includes', $includes);
        include($theLink . "twig_parts/_headChannel.php");
    } else {
        tt_global_set('includes', $includes);
        include($theLink . "twig_parts/_head.php");
    }
}

$data['modalIncludeCondition'] = ( !in_array(tt_global_get('page'), array( 'photo.php' , 'photo-album.php', 'album.php' ) ) );
$data['videojsCss'] = ReturnLink('css/video-js.css');
$data['videopagebehavior'] = ReturnLink("js/video-page-behavior.js");
$data['videoJs'] = ReturnLink("js/video.js");
$data['zoomJs'] = ReturnLink("js/zoom.js");
$data['jqpluginJs'] = ReturnLink("js/jquery.jqplugin.1.0.2.js");

//don't remove
$userInfo = getUserInfo($VideoInfo['userid']);

include('parts/media_page.php');

include($theLink . "twig_parts/_foot.php");
echo $template->render($data);