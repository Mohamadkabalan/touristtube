<?php

/**
 * search for videos given certain options. options include:<br/>
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>public</b>: wheather the media file is public or not. default 1<br/>
 * <b>userid</b>: the media file's owner's id. default null<br/>
 * <b>type</b>: what type of media file (v)ideo or (i)mage or (a)ll or (u)ser. default 'v'<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>latitude</b>: the latitude of the location to search within<br/>
 * <b>longitude</b>: the logitude of the location to search within<br/>
 * <b>radius</b>: the radius to search within (in meters)<br/>
 * <b>dist_alg</b>: the distance algorithm to use (s)quare [faster], or (c)ircular [slower]. default is 's'<br/>
 * <b>search_string</b>: the string to search for. could be space separated. no default<br/>
 * <b>search_where</b>: where to search for the string (t)itle, (d)escription, (k)eywords, (a)ll, or a comma separated combination. default is 'a'<br/>
 * <b>max_id<b/>: get records less than this one. (implied orderby 'id' and order 'd'),
 * <b>min_id<b/>: get records greater than this one. (implied orderby 'id' and order 'a'),
 * @param array $srch_options. the search options
 * @return array a number of media records
 */
$submit_post_get = array_merge($request->query->all(),$request->request->all());

session_id($submit_post_get['S']);
$expath = "../";
include_once($expath . "heart.php");

//"limit"
// > 0
$limit = 50;
//if (isset($_REQUEST['limit'])) {
//    if (intval($_REQUEST['limit']) > 0) {
//        $limit = $_REQUEST['limit'];
if (isset($submit_post_get['limit'])) {
    if (intval($submit_post_get['limit']) > 0) {
        $limit = $submit_post_get['limit'];
    }
}

//"page"
// > 0

$page = 0;
//if (isset($_REQUEST['page'])) {
//    if (intval($_REQUEST['page']) > 0) {
//        $page = $_REQUEST['page'];
if (isset($submit_post_get['page'])) {
    if (intval($submit_post_get['page']) > 0) {
        $page = $submit_post_get['page'];
    }
}

//"uid"
// userid
/* $uid = null;
  if (isset($_REQUEST['uid']))
  {
  if (intval($_REQUEST['uid'])>0)
  {
  $uid = $_REQUEST['uid'];
  }
  } */
$uid = $_SESSION['id'];

//"type"
//0 : both
//1 : Videos
//2 : Images
//3 : User
define("TYPE_IMAGE", 'i');
define("TYPE_VIDEO", 'v');
define("TYPE_USER", 'u');
define("TYPE_BOTH", 'a');

$type = 'a';
//if (isset($_REQUEST['type'])) {
//    switch (intval($_REQUEST['type'])) {
if (isset($submit_post_get['type'])) {
    switch (intval($submit_post_get['type'])) {
        case 0 : $type = constant("TYPE_BOTH");
            break;
        case 1 : $type = constant("TYPE_VIDEO");
            break;
        case 2 : $type = constant("TYPE_IMAGE");
            break;
        case 3 : $type = constant("TYPE_USER");
            break;
    }
}
//"orderby"
//1 : latest
//2 : duration
//3 : rating
//4 : Like Value (likers - dislikers)
//5 : Likes
//6 : dislikes
$orderby = "id";
//if (isset($_REQUEST['orderby'])) {
//    switch (intval($_REQUEST['orderby'])) {
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
        case 6 : $orderby = "down_votes";
            break;
    }
}

//"order"
//0 for desc
//1 for asc 
$order = "d";
//if (isset($_REQUEST['order'])) {
//    if (intval($_REQUEST['order']) == 1) {
if (isset($submit_post_get['order'])) {
    if (intval($submit_post_get['order']) == 1) {
        $order = 'a';
    }
}

$long = null;
$lat = null;
$radius = 1000;
//if ((isset($_REQUEST['longitude'])) && (isset($_REQUEST['latitude']))) {
//    $long = doubleval($_REQUEST['longitude']);
//    $lat = doubleval($_REQUEST['latitude']);
//    $radius = intval($_REQUEST['radius']);
if ((isset($submit_post_get['longitude'])) && (isset($submit_post_get['latitude']))) {
    $long = doubleval($submit_post_get['longitude']);
    $lat = doubleval($submit_post_get['latitude']);
    $radius = intval($submit_post_get['radius']);
}

$search = "";
//if (isset($_REQUEST['search'])) {
//    $search = $_REQUEST['search'];
if (isset($submit_post_get['search'])) {
    $search = $submit_post_get['search'];
}


if ($search == "") {
    $search = null;
}

$options = array(
    'limit' => $limit,
    'page' => $page,
    'public' => 2,
    'userid' => $uid,
    'favorite' => true,
    'type' => $type,
    'orderby' => $orderby,
    'order' => $order,
    'latitude' => $long,
    'longitude' => $lat,
    'radius' => null,
    'dist_alg' => 's',
    'search_string' => $search,
    'search_where' => 'a',
    'max_id' => null,
    'min_id' => null
);

$datas = mediaSearch($options);

$res = "<viph order='favs'>";
foreach ($datas as $data) {
    $stats = getVideoStats($data['id']);
    if (strpos($data['type'], "image") === false) {
        $res .= '<video>';
        $res .= '<id>' . $data['id'] . '</id>';
        $res .= '<title>' . htmlEntityDecode($data['title']) . '</title>';
        $res .= '<description>' . htmlEntityDecode($data['description']) . '</description>';
        $userinfo = getUserInfo($data['userid']);
        $res .= '<user>' . $userinfo['YourUserName'] . '</user>';
        $res .= '<n_views>' . $data['nb_views'] . '</n_views>';
        $res .= '<duration>' . $data['duration'] . '</duration>';


        $res .= '<rating>' . $stats['rating'] . '</rating>';
        $res .= '<nb_rating>' . $stats['nb_ratings'] . '</nb_rating>';
        $res .= '<nb_comments>' . $stats['nb_comments'] . '</nb_comments>';

        $res .= '<up_vote>' . $data['like_value'] . '</up_vote>';
        $res .= '<down_vote>' . $data['down_votes'] . '</down_vote>';
        //$res .= '<rating>'.$data['rating'].'</rating>';				
        //$res .= '<comments>'.$data['comments'].'</comments>';
        $res .= '<videolink>' . '' . $data['fullpath'] . $data['name'] . '</videolink>';
        $res .= '<thumblink>' . '' . substr(getVideoThumbnail($data['id'], $path . $data['fullpath'], 0), strlen($path)) . '</thumblink>';
        /////$res .= '&lt;item&gt;http://172.16.124.254:8181/touristtube/'.substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path)).'&lt;&frasl;item&gt;<br>';
        //echo $data['id']." ".$data['fullpath'];
        //var_dump(getVideoThumbnail($data['id'],$data['fullpath'],0));
        $res .= '</video>';
    } else {
        $res .= '<photo>';
        $res .= '<id>' . $data['id'] . '</id>';
        $res .= '<title>' . htmlEntityDecode($data['title']) . '</title>';
        $res .= '<description>' . htmlEntityDecode($data['description']) . '</description>';
        $userinfo = getUserInfo($data['userid']);

        $res .= '<rating>' . $stats['rating'] . '</rating>';
        $res .= '<nb_rating>' . $stats['nb_ratings'] . '</nb_rating>';
        $res .= '<nb_comments>' . $stats['nb_comments'] . '</nb_comments>';

        $res .= '<up_vote>' . $data['like_value'] . '</up_vote>';
        $res .= '<down_vote>' . $data['down_votes'] . '</down_vote>';
        $res .= '<user>' . $userinfo['YourUserName'] . '</user>';
        $res .= '<n_views>' . $data['nb_views'] . '</n_views>';
        $res .= '<fulllink>' . '' . $data['fullpath'] . $data['name'] . '</fulllink>';
        //$res .= '<thumblink>'.''.substr(getImageThumbnail(getVideoInfo($data['id']),$path.$data['fullpath']),strlen($path)).'</thumblink>';
        $res .= '</photo>';
    }
}
header("content-type: application/json; charset=utf-8");
$res .= "</viph>";

//echo $res;
$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $res);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string wit