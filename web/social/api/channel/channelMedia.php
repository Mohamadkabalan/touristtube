<?php
/*! \file
 * 
 * \brief This api returns media channel information
 * 
 * 
 * @param id channel id
 * @param t channel type
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * @param lang language used
 * @param size channel size
 * @param noCache true/false determins if image should be retrieved from cach
 * @param KeepRatio 1/0 not used
 * @param ss search string
 * 
 * @return <b>returnArr</b> JSON list with the following keys:
 * @return <pre> 
 * @return       <b>media</b> list with the following keys:
 * @return       		<b>id</b> media id
 * @return       		<b>title</b> media title
 * @return       		<b>nViews</b> media number of Views
 * @return       		<b>upVote</b> media number of likes
 * @return       		<b>country</b> media country
 * @return       		<b>cityname</b> media cityname
 * @return       		<b>category</b> media category
 * @return       		<b>isPublic</b> media is Public or not
 * @return       		<b>comments</b> media comments
 * @return       		<b>rating</b> media average rating
 * @return       		<b>nbRating</b> media number of rating
 * @return       		<b>nbComments</b> media number of Comments
 * @return       		<b>user</b> media user
 * @return       		<b>duration</b> <b>only for videos</b>video duration
 * @return       		<b>fullpath</b> <b>anything else then video</b>media full path
 * @return       		<b>fulllink</b> media full link
 * @return       		<b>thumbLink</b> media thumb link
 * @return       <b>res</b> search result
 * @return       <b>msg</b> error message
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */

$expath = "../";
include("../heart.php");
$returnArr = array();

if (apiOauth2(array())) {
    $media = 'a';
    $limit = 100;
    $page = 0;
    $lang = 'en';
    $size = 'm';
    $noCache = false;
    $keepRatio = '1';
$submit_post_get = array_merge($request->query->all(),$request->request->all());

//    $id = intval($_REQUEST['id']);
//    $media = $_REQUEST['t'];
//    $page = ($_REQUEST['page']) ? intval($_REQUEST['page']) : 0;
//    $limit = ($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 100;
//    if (isset($_REQUEST['lang'])) {
//        $lang = setLangGetText($_REQUEST['lang'], true) ? $_REQUEST['lang'] : 'en';
//    }
//    if (isset($_REQUEST['size'])) {
//        $size = $_REQUEST['size'];
//    }
//    if (isset($_REQUEST['noCache'])) {
//        $noCache = $_REQUEST['noCache'];
//    }
//    if (isset($_REQUEST['keepRatio'])) {
//        $keepRatio = intval($_REQUEST['keepRatio']) ? $_REQUEST['keepRatio'] : '1';
//    }
//    if( isset($_REQUEST['ss']) && ($_REQUEST['ss']!='') ){ 
//        $ss = $_REQUEST['ss'];
    $id = intval($submit_post_get['id']);
    $media = $submit_post_get['t'];
    $page = ($submit_post_get['page']) ? intval($submit_post_get['page']) : 0;
    $limit = ($submit_post_get['limit']) ? intval($submit_post_get['limit']) : 100;
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
    if( isset($submit_post_get['ss']) && ($submit_post_get['ss']!='') ){ 
        $ss = $submit_post_get['ss'];
    }
    else{
        $ss = null;
    }
    $channelInfo = channelGetInfo($id);

    $channelimageInfo = mediaSearch(array(
        'channel_id' => $channelInfo['id'],
        'type' => $media,
        'limit' => $limit,
        'page' => $page,
        'search_string'=>$ss,
        'search_where' => 't',
        'similarity' => 't',
        'search_strict' => 0,
        'catalog_status' => 0,
        'orderby' => 'pdate',
        'order' => 'd'
    ));

    $output = "";

    $dataRequired = array();
    $thumbLink = '';
    foreach ($channelimageInfo as $data) {
        $adataRequired = array();
        $stats = getVideoStats($data['id']);
        $adataRequired['id'] = $data['id'];
        $adataRequired['title'] = safeXML($data['title']);

        $userinfo = getUserInfo($data['userid']);

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
        $adataRequired['user'] = $userinfo['YourUserName'];
        if ($data['image_video'] == 'v') {
            $adataRequired['duration'] = $data['duration'];
            $thumbLink = substr(getVideoThumbnail($data['id'], $path . $data['fullpath'], 0), strlen($path));
        } else {
            $thumbLink = $data['fullpath'] . $data['name'];
        }
        $adataRequired['fulllink'] = $thumbLink;
        $adataRequired['thumbLink'] = resizepic($thumbLink, $size, $noCache, $keepRatio);

        $dataRequired[] = $adataRequired;
    }
    $returnArr['media'] = $dataRequired;
    $returnArr['res'] = '1';
    $returnArr['msg'] = apiTranslate($lang, 'done');
} else {
    $returnArr['res'] = '-1';
    $returnArr['msg'] = apiTranslate('en', 'Not Authorized');
}
echo json_encode($returnArr);