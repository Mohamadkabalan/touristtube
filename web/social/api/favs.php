<?php
/*! \file
 * 
 * \brief This api search for videos
 * 
 * 
 * @param S  session id
 * @param uid  user id
 * @param media type of media either 'i' for image or 'v' for videos
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * @param lang language used
 * @param size media size
 * @param noCache true/false determins if image should be retrieved from cach
 * @param KeepRatio 1/0 not used
 * @param orderby  order by choosen field 
 * @param order  order type 1 for asc or 0 for desc
 * @param longitude longitude of the media
 * @param search search string of the media
 * 
 * @return <b>returnArr</b> JSON List with the folowing keys:
 * @return <pre> 
 * @return         <b>media</b> List of media information with the folowing keys:
 * @return                <b>id</b> media id
 * @return                <b>title</b> media title
 * @return                <b>nViews</b> media number of Views
 * @return                <b>upVote</b> media number of likes
 * @return                <b>country</b> media country
 * @return                <b>cityname</b> media cityname
 * @return                <b>category</b> media category
 * @return                <b>isPublic</b> media is Public or not
 * @return                <b>comments</b> media comments
 * @return                <b>rating</b> media average rating
 * @return                <b>nbRating</b> media number of rating
 * @return                <b>nbComments</b> media number of Comments
 * @return                <b>user</b> media user name
 * @return                <b>duration</b> <b><u>only in case of video</u></b> video duration
 * @return                <b>fullpath</b> <b><u>any case other then video</u></b> media full path
 * @return                <b>fulllink</b> media full link
 * @return                <b>thumbLink</b> media thumb link
 * @return         <b>res</b> the result
 * @return         <b>msg</b> the result message
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */

/**
 * search for videos given certain options. options include:<br/>
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>public</b>: wheather the media file is public or not. default 1<br/>
 * <b>userid</b>: the media file's owner's id. default null<br/>
 * <b>media</b>: what media of media file (v)ideo or (i)mage or (a)ll or (u)ser. default 'v'<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>latitude</b>: the latitude of the location to search within<br/>
 * <b>longitude</b>: the longitude of the location to search within<br/>
 * <b>radius</b>: the radius to search within (in meters)<br/>
 * <b>dist_alg</b>: the distance algorithm to use (s)quare [faster], or (c)ircular [slower]. default is 's'<br/>
 * <b>search_string</b>: the string to search for. could be space separated. no default<br/>
 * <b>search_where</b>: where to search for the string (t)itle, (d)escription, (k)eywords, (a)ll, or a comma separated combination. default is 'a'<br/>
 * <b>max_id<b/>: get records less than this one. (implied orderby 'id' and order 'd'),
 * <b>min_id<b/>: get records greater than this one. (implied orderby 'id' and order 'a'),
 * @param array $srch_options. the search options
 * @return array a number of media records
 */
require_once("heart.php");
header('Content-type: application/json');
$returnArr = array();
$submit_post_get = array_merge($request->query->all(),$request->request->all());

if (apiOauth2(array())) {
//    session_id($_REQUEST['S']); 
//        session_start();
//    $userID = mobileIsLogged($_REQUEST['S']);
    $userID = mobileIsLogged($submit_post_get['S']);
    if( !$userID ){ 
        echo json_encode($returnArr);
        exit;
    }
//    $userID = mobileIsLogged($_REQUEST['S']);
//    if (isset($_REQUEST['uid'])) {
//        $userID = xss_sanitize($_REQUEST['uid']);
//    }
    if ( $userID == 0 )
            die ( 'Invalid info!' );
    $expath = "";

    $mediaTypeArr = array('i', 'v', 'a');

    $media = 'a';
    $limit = 50;
    $page = 0;
    $lang = 'en';
    $size = 'm';
    $noCache = false;
    $keepRatio = '1';
    $orderby = "id";
    $order = "d";
    $long = null;
    $lat = null;
    $radius = 1000;
    $search = "";

//    $userID
//    if(isset($_REQUEST['uid']))
//    {
//        $uid = xss_sanitize($_REQUEST['uid']);
    if(isset($submit_post_get['uid']))
    {
        $uid = $submit_post_get['uid'];
        if(!userIsFriend($userID, $uid)){ 
            echo json_encode($returnArr);
            exit;
        }
    }
    else{
        $uid = $userID;
    }

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

//"orderby"
//1 : latest
//2 : duration
//3 : rating
//4 : Like Value (likers - dislikers)
//5 : Likes
//    if (isset($_REQUEST['orderby'])) {
//        switch (intval($_REQUEST['orderby'])) {
    if (isset($submit_post_get['orderby'])) {
        switch (intval($submit_post_get['orderby'])) {
            case 1 : $orderby = "id";
                break;
            case 2 : $orderby = "duration";
                break;
            case 3 : $orderby = "rating";
                break;
            case 4 : $orderby = "like_value";
                break;
            case 5 : $orderby = "like_value";
                break;
        }
    }

//"order"
//0 for desc
//1 for asc

//    if (isset($_REQUEST['order'])) {
//        if (intval($_REQUEST['order']) == 1) {
    if (isset($submit_post_get['order'])) {
        if (intval($submit_post_get['order']) == 1) {
            $order = 'a';
        }
    }


//    if ((isset($_REQUEST['longitude'])) && (isset($_REQUEST['latitude']))) {
//        $long = doubleval($_REQUEST['longitude']);
//        $lat = doubleval($_REQUEST['latitude']);
//        $radius = intval($_REQUEST['radius']);
//    }
//
//    
//    if (isset($_REQUEST['search'])) {
//        $search = $_REQUEST['search'];
    if ((isset($submit_post_get['longitude'])) && (isset($submit_post_get['latitude']))) {
        $long = doubleval($submit_post_get['longitude']);
        $lat = doubleval($submit_post_get['latitude']);
        $radius = intval($submit_post_get['radius']);
    }

    
    if (isset($submit_post_get['search'])) {
        $search = $submit_post_get['search'];
    }


    if ($search == "") {
        $search = null;
    }

//    $options = array(
//        'limit' => $limit,
//        'page' => $page,
//        'public' => 2,
//        'userid' => $uid,
//        'favorite' => true,
//        'type' => $media,
//        'orderby' => $orderby,
//        'order' => $order,
//        'latitude' => $long,
//        'longitude' => $lat,
//        'radius' => null,
//        'dist_alg' => 's',
//        'search_string' => $search,
//        'search_where' => 'a',
//        'max_id' => null,
//        'min_id' => null
//    );
//
//    $datas = mediaSearch($options);
    
    $options = array(
        'limit' => $limit,
        'page' => $page,
        'user_id' => $uid,
        'order' => 'd',
        'orderby' => 'favorite_ts',
        'types' => array(SOCIAL_ENTITY_MEDIA)
    );
    $datas = socialFavoritesGet($options);

    $dataRequired = array();
    $thumbLink = '';
    foreach ($datas as $item) {
        $data = getVideoInfo($item['entity_id']);
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
    $returnArr['msg'] = _('done');
} else {
    $returnArr['res'] = '-1';
    $returnArr['msg'] = _('Not Authorized');
}
echo json_encode($returnArr);