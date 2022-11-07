<?php


$expath    = '';

include_once("heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$returnArr = array();

//$userId = 0;

//$userId = mobileIsLogged($_REQUEST['S']);
//if (isset($_REQUEST['uid'])) {
//    $userId = xss_sanitize($_REQUEST['uid']);
$userId = mobileIsLogged($submit_post_get['S']);
$logged_user_id = $userId;
if (isset($submit_post_get['uid'])) {
    $userId = $submit_post_get['uid'];
}

if ( $userId == 0 )
	die ( 'Invalid info!' );
if (apiOauth2(array())) {

    $userInfo = getUserInfo($userId);

//    All Options
    $mediaTypeArr = array('i', 'v');

//    Default Values
    $media = 'i';
    $limit = 15;
    $page = 0;
    $lang = 'en';
    $size = 'm';
    $noCache = false;
    $keepRatio = '1';

//    if (isset($_REQUEST['media'])) {
//        $media = in_array(xss_sanitize($_REQUEST['media']), $mediaTypeArr) ? xss_sanitize($_REQUEST['media']) : 'i';
//    }
//    if (isset($_REQUEST['limit'])) {
//        $limit = intval($_REQUEST['limit']);
//    }
//    if (isset($_REQUEST['page'])) {
//        $page = intval($_REQUEST['page']);
//    }
//    if (isset($_REQUEST['lang'])) {
//        $lang = setLangGetText(xss_sanitize($_REQUEST['lang']), true) ? xss_sanitize($_REQUEST['lang']) : 'en';
//    }
//    if (isset($_REQUEST['size'])) {
//        $size = xss_sanitize($_REQUEST['size']);
//    }
//    if (isset($_REQUEST['noCache'])) {
//        $noCache = xss_sanitize($_REQUEST['noCache']);
//    }
//    if (isset($_REQUEST['keepRatio'])) {
//        $keepRatio = intval(xss_sanitize($_REQUEST['keepRatio'])) ? xss_sanitize($_REQUEST['keepRatio']) : '1';
//    }
    if (isset($submit_post_get['media'])) {
        $media = in_array($submit_post_get['media'], $mediaTypeArr) ? $submit_post_get['media'] : 'i';
    }
    if (isset($submit_post_get['limit'])) {
        $limit = intval($submit_post_get['limit']);
    }
    if (isset($submit_post_get['page'])) {
        $page = intval($submit_post_get['page']);
    }
    if (isset($submit_post_get['lang'])) {
        $lang = setLangGetText($submit_post_get['lang'], true) ? $submit_post_get['lang'] : 'en';
    }
    if (isset($submit_post_get['size'])) {
        $size = $submit_post_get['size'];
    }
    if (isset($submit_post_get['noCache'])) {
        $noCache = $submit_post_get['noCache'];
    }
    if (isset($submit_post_get['keepRatio'])) {
        $keepRatio = intval($submit_post_get['keepRatio']) ? $submit_post_get['keepRatio'] : '1';
    }

    $is_owner=0;
    if($logged_user_id==$userId) $is_owner=1;
    
    $options = array(
        'limit' => $limit,
        'page' => $page,
        'userid' => $userId,
        'type' => $media,
        'orderby' => 'id',
        'order' => 'd',
        'search_strict' => 0, 
        'similarity' => 't,d',
        'is_owner' => $is_owner,
        'catalog_status' => 0
    );

//    if (isset($_REQUEST['ss']) && ($_REQUEST['ss'] != ''))
//        $options['search_string'] = xss_sanitize($_REQUEST['ss']);
    if (isset($submit_post_get['ss']) && ($submit_post_get['ss'] != ''))
        $options['search_string'] = $submit_post_get['ss'];
    $userVideos = mediaSearch($options);

    $dataRequired = array();
    $thumbLink = '';

    if ($userVideos && sizeof($userVideos) > 0) {
        foreach ($userVideos as $data) {
            $adataRequired = array();

            $adataRequired['id'] = $data['id'];
            $adataRequired['title'] = safeXML($data['title']);
            $adataRequired['description'] = safeXML($data['description']);
            $adataRequired['placetakenat'] = safeXML($data['placetakenat']);
            $adataRequired['keywords'] = htmlEntityDecode($data['keywords']);
            $userinfo = getUserInfo($data['userid']);
            $adataRequired['user'] = $userinfo['YourUserName'];
            $adataRequired['nViews'] = $data['nb_views'];
            $adataRequired['upVote'] = $data['like_value'];
            $adataRequired['country'] = $data['country'];
            $adataRequired['cityname'] = $data['cityname'];
            $adataRequired['category'] = $data['category'];
            $adataRequired['isPublic'] = $data['is_public'];
            $adataRequired['comments'] = $data['comments'];
            $adataRequired['rating'] = $stats['rating'];
            $adataRequired['nbRating'] = $stats['nb_ratings'];
            $adataRequired['nbComments'] = $stats['nb_comments'];
            if ($media == 'v') {
                $adataRequired['duration'] = $data['duration'];
                $thumbLink = substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path));
            }else{
                $thumbLink = $data['fullpath'] . $data['name'];
            }
            $adataRequired['fulllink'] = $thumbLink;
            $adataRequired['thumbLink'] = resizepic($thumbLink, $size, $noCache, $keepRatio);
            

            $dataRequired[] = $adataRequired;
        }
    }
    $returnArr['media'] = $dataRequired;
    $returnArr['res'] = '1';
    $returnArr['msg'] = _('done');
} else {
    $returnArr['res'] = '-1';
    $returnArr['msg'] = _('Not Authorized');
}
echo json_encode($returnArr);