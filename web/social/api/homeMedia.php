<?php
/*! \file
 * 
 * \brief This api returns all information of media (resized or not)
 * 
 * 
 * @param media 'v' for video or 'i' for image
 * @param type 'latest' for new videos or 'most' for most viewed
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * @param lang language used
 * @param size size of the file  
 * @param noCache true/false determins if image should be retrieved from cach
 * @param KeepRatio 1/0 not used
 * 
 * @return list with the following keys:
 * @return <b>media</b> List of media information (array)
 * @return <pre> 
 * @return        <b>id</b> media id
 * @return        <b>title</b> media title
 * @return        <b>nViews</b> media number of views
 * @return        <b>mediaLink</b> media link
 * @return        <b>mediaType</b> media Type
 * @return        <b>duration</b> media Time
 * @return        <b>size</b> media size
 * @return        <b>fulllink</b> media full path
 * @return        <b>thumbLink</b> media resize
 * @return <b>res</b> result "1"
 * @return <b>msg</b> message "done"
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>

 * 
 *  */
$expath    = '';
require_once("heart.php");
$returnArr = array();

if (apiOauth2(array())) {
    /* ////////need/////////
     * media: i/v
     * type: string
     * limit: int
     * page: int
     * lang: string
     * size: string
     * keepRatio: 1/0
     * noCache: true/false
     */

//    All Options
    $mediaTypeArr = array('i', 'v');
    $requestTypeArr = array('latest', 'most');
$submit_post_get = array_merge($request->query->all(),$request->request->all());

//    Default Values
    $media = 'i';
    $type = 'most';
    $limit = 30;
    $page = 0;
    $lang = 'en';
    $size = 'm';
    $noCache = false;
    $keepRatio = '1';

//    if (isset($_REQUEST['media'])) {
//        $media = in_array(xss_sanitize($_REQUEST['media']), $mediaTypeArr) ? xss_sanitize($_REQUEST['media']) : 'i';
//    }
//    if (isset($_REQUEST['type'])) {
//        $type = in_array(xss_sanitize($_REQUEST['type']), $requestTypeArr) ? xss_sanitize($_REQUEST['type']) : 'most';
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
    if (isset($submit_post_get['type'])) {
        $type = in_array($submit_post_get['type'], $requestTypeArr) ? $submit_post_get['type'] : 'most';
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
    if (isset($_REQUEST['size'])) {
        $size = $submit_post_get['size'];
    }
    if (isset($_REQUEST['noCache'])) {
        $noCache = $submit_post_get['noCache'];
    }
    if (isset($_REQUEST['keepRatio'])) {
        $keepRatio = intval($submit_post_get['keepRatio']) ? $submit_post_get['keepRatio'] : '1';
    }

//    exit('$media'.$media.'$type'.$type.'$limit'.$limit.'$page'.$page.'$lang'.$lang.'$size'.$size);

    if ($media == 'v' && $type == 'latest') {
        $datas = getVideos($limit, $page);
    }
    if ($media == 'v' && $type == 'most') {
        $datas = homeVideosMostViewed($limit, $page);
    }
    if ($media == 'i' && $type == 'most') {
        $datas = getPhotosMostView($limit, $page);
    }
    $dataRequired = array();
    $thumbLink = '';
    foreach ($datas as $data) {
        $adataRequired = array();
        $adataRequired['id'] = $data['id'];
        $title = safeXML($data['title']);
        $adataRequired['title'] = $title;
        $adataRequired['nViews'] = $data['nb_views'];
        $adataRequired['mediaLink'] = $data['fullpath'] . $data['name'];
        $adataRequired['mediaType'] = $media;
        if ($media == 'v') {
            $adataRequired['duration'] = $data['duration'];
            $thumbLink = substr(getVideoThumbnail($data['id'], $path . $data['fullpath'], 0), strlen($path));
        } else if ($media == 'i') {
            $thumbLink = $data['fullpath'].$data['name'];
        }

        $adataRequired['size'] = $size;
        $adataRequired['fulllink'] = $thumbLink;
        $adataRequired['thumbLink'] = '';//resizepic($thumbLink, $size, $noCache, $keepRatio);
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