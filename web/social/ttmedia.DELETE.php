<?php

$path = "";
//exit(phpinfo());
$bootOptions = array("loadDb" => 1, "loadLocation" => 1, "requireLogin" => 0);
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
    'cache' => $theLink . 'twig_cache/', 'debug' => false,
        ));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('ttmedia.twig');

$includes = array( 'js/index-behavior.js?v='.INDEXBEHAVIOR_JS_V,
    'js/jquery.mscroll.js',
    'js/swiper.js',
	'js/media_category_dd.js',
	'css_media_query/swiper_slider.css?v='.MQ_SWIPER_SLIDER_CSS_V,
	'media'=>'css_media_query/media_style.css?v='.MQ_MEDIA_STYLE_CSS_V,
    'media1'=>'css_media_query/index_media.css?v='.MQ_INDEX_MEDIA_CSS_V );

$userIschannel=userIsChannel();
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
$data['CountryCode'] = $CountryCode;

if (isset($category_id) && $category_id <> '') {
    $thumbVideosData = getVideos(15, 0, $category_id);
} else {
    $thumbVideosData = getVideos();
}
$n_thumbs = count($thumbVideosData);
$item_width = 239;
$items_width = $n_thumbs * $item_width;
$data['thumbVideosData'] = $thumbVideosData;
$data['n_thumbs'] = $n_thumbs;
$data['items_width'] = $items_width;
$thumbVArrAll = array();
foreach ($thumbVideosData as $oneThumVideo) {
    $thumbVArr = array();
    $linktogogle = currentServerURL().ReturnVideoUriHashed($oneThumVideo);
    $vid_code = $oneThumVideo['code'];
    $pdate = $oneThumVideo['pdate'];
    $duration = $oneThumVideo['duration'];
    $imgdir = "thumbnails";
    $max_title = 26;
    $description = htmlEntityDecode($oneThumVideo['description']);
    $val_db = htmlEntityDecode($oneThumVideo['title']);
    $title = cut_sentence_length($val_db, $max_title);
    $description = ($description=='')? $title : $description;
    $thumbVArr['vid_code'] = $vid_code;
    $thumbVArr['imgdir'] = $imgdir;
    $thumbVArr['val_db'] = $val_db;
    $thumbVArr['title'] = $title;
    $thumbVArr['description'] = $description;
    $thumbVArr['iconinslider'] = ReturnVideoUriHashed($oneThumVideo);
    $thumbVArr['linktogogle'] = $linktogogle;
    $thumbVArr['pdate'] = $pdate;
    $thumbVArr['duration'] = $duration;
    $thumbVArr['imgThum'] = videoReturnThumbSrc($oneThumVideo);
    $thumbVArrAll[] = $thumbVArr;
}
$data['thumbVArrAll'] = $thumbVArrAll;
$data['recentVideoTxt'] = _('recent videos');
$data['TRENDSTxt'] = _('TRENDS');

$trends = queryGetTrends();

$trendsAll = array();
foreach ($trends as $trend) {
    $name = $trend['name'];
    $font = $trend['font'];
    $margin = $trend['margin'];
    $class = $trend['class'];
    
    //$trend_link = ReturnLink('search?qr=' . $name);
    $trend_link = ReturnLink($name.'--Sa_1_');
    $link = sprintf('<a href="%s" target="_blank" title="%s" class="IndexTrendLink">%s</a>', $trend_link, $name, $name);

    $trendsAll[] = sprintf('<li class="%s" style="margin-top: %spx;" >%s</li>', $class, $margin, $link);
}
$data['trendsAll'] = $trendsAll;
$data['Categories'] = _('Categories');

$categories = categoryGetHash(array('orderby' => 'item_order'));
$category_id = null;
$i = 0;
$category_name = tt_global_get('category_name');
$category_id = tt_global_get('category_id');

$data['category_id'] = $category_id;
$data['category_name'] = $category_name;
$data['categories'] = $categories;
$catArray = array();
foreach ($categories as $cat_id => $name) {
    $url_replace = str_replace(" ", "+", $name);
    //$catArray[] = array('catId' => $cat_id, 'name' => $name, 'link' => ReturnLink('search?c=' . $cat_id));
    $catArray[] = array('catId' => $cat_id, 'name' => $name, 'link' => ReturnLink($url_replace));
}
$data['catArray'] = $catArray;
$moreVideoLink = ReturnLink('parts/morevideo.php?page=0');
$morePhotoLink = ReturnLink('parts/morephoto.php?page=0');

if (($ch = channelGlobalGet()) != false) {
    $moreVideoLink .= '&ch=' . $ch['id'];
    $morePhotoLink .= '&ch=' . $ch['id'];
}
if ($category_id != null) {
    $moreVideoLink .= '&cat_id=' . $category_id;
    $morePhotoLink .= '&cat_id=' . $category_id;
    $_GET['cat_id'] = $request->query->set('cat_id',$category_id);
}

$data['moreVideoLink'] = $moreVideoLink;
$data['morePhotoLink'] = $morePhotoLink;

$data['MostViewed'] = _('Most Viewed');
$data['Videos'] = _('Videos');
$data['Photos'] = _('Photos');
$data['Morevideos'] = _('More videos');
$data['Morephotos'] = _('More photos');
$data['LoadMore'] = _('Load More...');
$data['MostActiveTT'] = _('Most Active<br/>Tourist Tubers');
include($theLink . 'parts/_morevideo1.php');
include($theLink . 'parts/_morephoto1.php');

$tubers = getPopularTubers();
$tubersArr = array();
foreach ($tubers as $tuber) {
    $location = userGetLocation($tuber);
    if (strstr($tuber['profile_Pic'], 'tuber.jpg') != null)
        continue;
    $tubersArr[] = array(
        'location' => $location,
        'ProfileLink' => userProfileLink($tuber),
        'thumb' => ReturnLink('media/tubers/thumb_' . $tuber['profile_Pic']),
        'xsmall' => ReturnLink('media/tubers/xsmall_' . $tuber['profile_Pic']),
        'small' => ReturnLink('media/tubers/small_' . $tuber['profile_Pic']),
        'name' => returnUserDisplayName($tuber)
    );
}
$data['tubersArr'] = $tubersArr;
$data['searchTubers'] = _('Search For Tubers');
$data['entername'] = _('enter tuber name');

if (userIsLogged()):
    $user_infos = getUserInfo(userGetID());
    $invites = userGetInvitesNumber(userGetID());
    $invites_left = $user_infos['invites_max'] - $invites;
    $data['invitesLeftNB'] = $invites_left;
    $data['InvitationsTxt'] = _('Invitations');
    $data['veGotTxt'] = sprintf(_("you've got %s invitations to send"), '<span id="invites_left">' . $invites_left . '</span>');
    $data['submitTxt'] = _('submit');
endif;
$data['ttOnMove'] = _('Tourist Tube on the move');
$data['ttAppDownload'] = _('Download tourist tube App on your device');

include($theLink . "twig_parts/_foot.php");
echo $template->render($data);