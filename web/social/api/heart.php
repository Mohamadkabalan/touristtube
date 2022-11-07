<?php
/**
  * the beating heart of the api.
  *
  * all functions used by the api are listed here
  *  description description description.
  *
  */
if (!isset($expath)) {
    $expath = "";
}
global $path;
global $mobile_timezone;
$path = "../" . $expath;

//define('ENVIRONMENT', getenv('ENVIRONMENT'));
include_once ( $path . 'inc/config.php' );

$bootOptions = array("loadDb" => 1 );
require_once( $path . "inc/common.php" );
require_once( $path . "inc/bootstrap.php" );
//require_once($path . "connection.php");
require_once($path . "inc/functions/users.php");
require_once($path . "inc/functions/channel.php");
require_once($path . "inc/functions/discover.php");
require_once($path . "inc/misc.php");
require_once($path . "inc/functions/bag.php");
require_once($path . "inc/functions/videos.php");
require_once($path . "inc/functions/comment_media.php");
require_once($path . "inc/functions/webcams.php" );
require_once($path . "inc/functions/locations.php");
require_once($path . "inc/functions/db_mysql.php");
require_once($path . "inc/UriManager.php");
require_once($path . "inc/functions/accent.inc.php");

global $mediaSizes;
$mediaSizes = array(

    'xxx'   => array(   '1920', '1080'),
    'xx'    => array(   '1080', '607'),
    'x'     => array(   '720',  '404'),
    'h'     => array(   '560',  '315'),
    'm'     => array(   '370',  '208'),
    's'     => array(   '290',  '163'),
    'xs'    => array(   '192',  '108'),
    's_xx'  => array(   '270',  '270'),
    's_x'   => array(   '180',  '180'),
    's_h'   => array(   '140',  '140'),
    's_m'   => array(   '92',   '92')

);

$language_post_get = array_merge($request->query->all(),$request->request->all());
$api_language = isset($language_post_get['lang']) && $language_post_get['lang'] ? $language_post_get['lang'] : 'en';
if($api_language == 'hi'){
    $api_language = 'in';
}
setLangGetText($api_language);
$GLOBAL_LANG = $api_language;

if(isset($language_post_get['timezone'])){
    global $mobile_timezone;
    $mobile_timezone = $language_post_get['timezone'];
}

function socialCommentsList($limit, $page, $entity_id, $entity_type, $published, $is_visible,$entity_owner, $user_id){
    
    $options = array(
        'limit' => $limit,
        'page' => $page,
        'orderby' => 'comment_date',
        'order' => 'd',
        'entity_id' => $entity_id,
        'published' => $published,
        'is_visible' => $is_visible,
        'entity_type' => $entity_type
    );
	
    $allComments = socialCommentsGet($options);
    $res = array();
    foreach($allComments as $comment){			
        $t_date = $comment['comment_date'];
        $commentsDate = returnSocialTimeFormat($t_date,1);//to use
        $comment_owner = $comment['user_id'];
        $channel_comment_id = intval($comment['channel_id']);
        if($entity_owner==$comment_owner && $channel_comment_id>0){
             $channelInfo_comment=channelGetInfo($channel_comment_id);
             $channel_name_comment = htmlEntityDecode($channelInfo_comment['channel_name']);
            if(strlen($channel_name_comment) > 25){
                $channel_name_comment = substr($channel_name_comment,0,25).' ...';
            }
            $comment_name=$channel_name_comment;
            $pic_diplay = $channelInfo_comment['logo'] ? 'media/channel/' . $channelInfo_comment['id'] . '/thumb/' . $channelInfo_comment['logo'] : '/media/tubers/tuber.jpg';
//                    $pic_diplay = ($channelInfo['logo']) ? photoReturnchannelLogo($channelInfo_comment) : ReturnLink('/media/tubers/tuber.jpg');
            $owner_id = $channel_comment_id;
            $owner_type = 'channel';
        }else{
            $comment_name = returnUserDisplayName($comment);
            $pic_diplay = 'media/tubers/'.$comment['profile_Pic'] ;
//                    $pic_diplay = ReturnLink('media/tubers/small_'.$comment['profile_Pic'] );
            $owner_id = $comment_owner;
            $owner_type = 'user';
        }
        $overhead_text= strip_tags($comment['comment_text']);
        $likes_options = array(
            'entity_id' => $comment['id'],
            'entity_type' => SOCIAL_ENTITY_COMMENT,
            'like_value' => 1,
            'n_results' => true
        );
        $likes = socialLikesGet($likes_options);
        $isLiked = socialLiked($user_id, $comment['id'], SOCIAL_ENTITY_COMMENT);
        $res[] = array(
            'id' => $comment['id'],
            'owner_id' => $owner_id,
            'owner_name' => $comment_name,
            'owner_type' => $owner_type,
            'profile_pic' => $pic_diplay,
            'date' => $commentsDate,
            'comment' => $overhead_text,
            'likes' => $likes,
            'is_liked' => $isLiked ? "$isLiked" : "0"
        );
    }
    return $res;
}

/**
  * A summary informing of the function.
  *
  * A *description*, description description description
  *  description description description.
  *
  * @param1 string link 
  * @param2 string size
  * @param3 string noCache
  * @param4 string keepRatio 
  *
  */
function resizepic($link, $size, $noCache, $keepRatio, $w='', $h=''){
    global $path;
    global $CONFIG;
    $theFileLink = '';
    global $mediaSizes;
    if($size != '' && in_array($size, array_keys($mediaSizes))){
        $t_width = $mediaSizes[$size][0];
        $t_height = $mediaSizes[$size][1];
    }
    else{
        $t_width = $w;
        $t_height = $h;
    }
    //$keep_ratio = isset($noCache) ? ($noCache == 1) : false;
    $keep_ratio = isset($noCache) ? ($noCache == 1) : true;
//    return ('$link: '.$link.' $size: '. $size. ' $path: _'. $path. '_ $t_width: '.$t_width.' $t_height: '.$t_height );

    $thumbcache = "cache/thumbs/";
    $md5_reflink = md5($link);
    $filename = $md5_reflink . "_" . $t_width . "_" . $t_height . ".jpg";
    
    //if the cache doesn't exist or cachebuster is enabled
    if (!file_exists($path . $thumbcache . $filename ) || $noCache) {
        $quality = 70;
        if( $keep_ratio ) $quality = 100;

        $options = array(
            'in_path' => $CONFIG['server']['root'] . $link,
            'out_path' => $CONFIG['server']['root'] . $thumbcache . $filename,
            'w' => $t_width,
            'h' => $t_height,
            'keep_ratio' => $keep_ratio,
            'quality' => $quality
        );
//        return createMediaSubsample($options);
        if ( createMediaSubsample($options) ) {
            $theFileLink = $thumbcache . $filename;
        } else {
            $theFileLink = '';
        }
    }else{
        $theFileLink = $thumbcache . $filename;
    }
    return $theFileLink;
}


$myConn;

function apiOauth2($opt = array()){
    return true;
}

$private_key = "HHqx2Z6919W8N3R";
$pt_expiry_days = 7;
$s_expiry_days = 7;

function mobileIsLogged($login_token)
{      
    if(!isset($login_token) || empty($login_token) || !$login_token) {
        return 0;
    }
	
	global $dbConn;
    $params = array();
	
    $token = $login_token;
//  $query = "SELECT * FROM cms_tubers WHERE uid = '$token'";
    $query = "SELECT * FROM cms_tubers WHERE uid = :Token";
//    $ret = db_query($query);
    $params[] = array(  "key" => ":Token",
                        "value" =>$token);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res  = $select->execute();

    $ret    = $select->rowCount();
//    if ($ret && db_num_rows($ret) != 0) {
    if ($res && $ret != 0) {
//        $row = db_fetch_array($ret);
        $row = $select->fetch();
        userSetSession(NULL, $row['user_id']);
        if($row['keep_me_logged'] == "1"){
            $query2 = "UPDATE cms_tubers SET expiry_date = DATE_ADD(NOW(), INTERVAL 7 DAY) WHERE id=:Id";
            $params2[] = array( "key" => ":Id",
                        "value" =>$row['id']);
            $update = $dbConn->prepare($query2);
            PDO_BIND_PARAM($update, $params2);
            $update->execute();
        }
        tt_global_set('isLogged', true);
        return $row['user_id'];
    } else {
        return 0;
    }
}

function createSession($deviceToken, $userId){
    global $s_expiry_days;
    global $dbConn;
    $params = array();  
//    $query = "INSERT INTO cms_mobile_session (userid, tokenid, expiry_date) VALUES ($userId, '$deviceToken', DATE_ADD( utc_timestamp( ) , INTERVAL $s_expiry_days DAY))";
    $query = "INSERT INTO cms_mobile_session (userid, tokenid, expiry_date) VALUES (:UserId, :DeviceToken, DATE_ADD( utc_timestamp( ) , INTERVAL :S_expiry_days DAY))";
    $params[] = array(  "key" => ":UserId",
                        "value" =>$userId);
    $params[] = array(  "key" => ":DeviceToken",
                        "value" =>$deviceToken);
    $params[] = array(  "key" => ":S_expiry_days",
                        "value" =>$s_expiry_days);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $spq    = $select->execute();
//    $spq = db_query($query);
    if($spq){
        return 'success';
    }
    else{
        return 'fail';
    }
}

function validateSession($deviceToken, $userId){
    global $s_expiry_days;
    global $dbConn;
    $params = array(); 
//    $query = "SELECT COUNT(tokenid) FROM cms_mobile_session WHERE tokenid = '$deviceToken' AND userid = $userId AND TIMESTAMPDIFF(DAY, expiry_date, NOW()) <= $s_expiry_days";
    $query = "SELECT COUNT(tokenid) FROM cms_mobile_session WHERE tokenid = :DeviceToken AND userid = :UserId AND TIMESTAMPDIFF(DAY, expiry_date, NOW()) <= :S_expiry_days";
    $params[] = array(  "key" => ":UserId",
                        "value" =>$userId);
    $params[] = array(  "key" => ":DeviceToken",
                        "value" =>$deviceToken);
    $params[] = array(  "key" => ":S_expiry_days",
                        "value" =>$s_expiry_days);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $ret    = $select->execute();
    //echo $query;
//    $ret = db_query($query);
//    $row = db_fetch_array($ret);
    $row = $select->fetch();
    $n_results = $row[0];
    return $n_results;    
}

function createHandshake($deviceToken, $platform){
    global $private_key;
    global $pt_expiry_days;
    global $dbConn;
    $params  = array(); 
    $params2 = array(); 
    $publicKey = generatePublicKey();
    $publicToken = md5($deviceToken.$publicKey.$private_key);
//    db_query("DELETE FROM cms_mobile_token WHERE tokenid = '$deviceToken'");
    $del_quer= "DELETE FROM cms_mobile_token WHERE tokenid = :DeviceToken";
    $params[] = array(  "key" => ":DeviceToken",
                        "value" =>$deviceToken);
    $select = $dbConn->prepare($del_quer);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
//    $query = "INSERT INTO cms_mobile_token (tokenid, platform, public_token, expiry_date) VALUES ('$deviceToken', $platform, '$publicToken', DATE_ADD( utc_timestamp( ) , INTERVAL $pt_expiry_days DAY))";
    $query = "INSERT INTO cms_mobile_token (tokenid, platform, public_token, expiry_date) VALUES (:DeviceToken, :Platform, :PublicToken, DATE_ADD( utc_timestamp( ) , INTERVAL :Pt_expiry_days DAY))";
    $params2[] = array(  "key" => ":DeviceToken",
                         "value" =>$deviceToken);
    $params2[] = array(  "key" => ":Platform",
                         "value" =>$platform);
    $params2[] = array(  "key" => ":PublicToken",
                         "value" =>$publicToken);
    $params2[] = array(  "key" => ":Pt_expiry_days",
                         "value" =>$pt_expiry_days);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params2);
    $spq    = $select->execute();
//    $spq = db_query($query);
    if($spq){
        return $publicKey;
    }
    else{
        return 'fail';
    }
}
// CODE NOT USED - commented by KHADRA
//function validateHandshake($publicToken, $deviceToken){
//    global $pt_expiry_days;
//    $query = "SELECT * FROM cms_mobile_token WHERE tokenid = '$deviceToken' AND public_token = '$publicToken' AND TIMESTAMPDIFF(DAY, expiry_date, NOW()) <= $pt_expiry_days";
//    $spq = mysql_query($query);
//    if($spq && db_num_rows($spq)){
//        db_query("DELETE FROM cms_mobile_token WHERE tokenid = '$deviceToken'");
//        return false;
//    }
//    return true;
//}

function generatePublicKey(){
    return uniqid(time());
}

function setToken($ssid, $token, $platform, $userid) {
    /*
      $platform = 0 for iOS
      $platform = 1 for Android
     */
    global $dbConn;
    $params  = array();
    $params2 = array();
    $params3 = array();
    $params4 = array();
    $params5 = array();
    if ($ssid == '0') {
//        $selec = db_query("SELECT * FROM cms_mobile_token WHERE tokenid='$token' AND platform='$platform'");
        $sel_query ="SELECT * FROM cms_mobile_token WHERE tokenid=:Token AND platform=:Platform";
	$params[] = array(  "key" => ":Token",
                            "value" =>$token);
	$params[] = array(  "key" => ":Platform",
                            "value" =>$platform);
	$select = $dbConn->prepare($sel_query);
	PDO_BIND_PARAM($select,$params);
	$selec    = $select->execute();

	$ret    = $select->rowCount();
        if ($ret == 0) {
//            if (db_query("INSERT INTO cms_mobile_token (ssid, tokenid, platform, ap_type) VALUES ('$ssid', '$token', '$platform', '$app_type')")) {
            $ins_query = "INSERT INTO cms_mobile_token (ssid, tokenid, platform,userid) VALUES (:Ssid, :Token, :Platform, :Userid)";
            $params2[] = array(  "key" => ":Platform",
                                 "value" =>$platform);
            $params2[] = array(  "key" => ":Token",
                                 "value" =>$token);
            $params2[] = array(  "key" => ":Ssid",
                                 "value" =>$ssid);
            $params2[] = array(  "key" => ":Userid",
                                 "value" => $userid);
            $select = $dbConn->prepare($ins_query);
            PDO_BIND_PARAM($select,$params2);
            $res    = $select->execute();
            if ($res) {
                return "success";
            } else {
                return "fail";
            }
        }
    } else {
//        $sel_query2 = "SELECT * FROM cms_mobile_token WHERE tokenid='$token'";
        $sel_query2 = "SELECT * FROM cms_mobile_token WHERE tokenid=:Token";
	$params3[] = array(  "key" => ":Token",
                             "value" =>$token);
	$select = $dbConn->prepare($sel_query2);
	PDO_BIND_PARAM($select,$params3);
	$res    = $select->execute();

	$ret    = $select->rowCount();
//        if (db_num_rows($sel) == 0) {
        if ($ret == 0) {
            //$query = "INSERT INTO cms_mobile_token (userid, tokenid, platform) VALUES ('".$userid."', '".$token."', '0')";
//            $ins_query = "INSERT INTO cms_mobile_token (ssid, tokenid, platform, ap_type) VALUES ('$ssid', '$token', '$platform', '$app_type')";
            $ins_query2 = "INSERT INTO cms_mobile_token (ssid, tokenid, platform, userid) VALUES (:Ssid, :Token, :Platform, :Userid)";
            $params4[] = array(  "key" => ":Platform",
                                 "value" =>$platform);
            $params4[] = array(  "key" => ":Token",
                                 "value" =>$token);
            $params4[] = array(  "key" => ":Ssid",
                                 "value" => $ssid);
            $params4[] = array(  "key" => ":Userid",
                                 "value" => $userid);
            $select = $dbConn->prepare($ins_query2);
            PDO_BIND_PARAM($select,$params4);
            $res    = $select->execute();
            if ($res) {
                return "success";
            } else {
                return "insert";
            }
        } else {
//            $upd_query = "UPDATE cms_mobile_token SET ssid='$ssid', platform='$platform', ap_type='$app_type' WHERE tokenid='$token'";
            $upd_query = "UPDATE cms_mobile_token SET ssid=:Ssid, platform=:Platform, userid=:Userid WHERE tokenid=:Token";
            $params5[] = array(  "key" => ":Platform",
                                 "value" =>$platform);
            $params5[] = array(  "key" => ":Token",
                                 "value" =>$token);
            $params5[] = array(  "key" => ":Ssid",
                                 "value" =>$ssid);
            $params5[] = array(  "key" => ":Userid",
                                 "value" => $userid);
            $select = $dbConn->prepare($upd_query);
            PDO_BIND_PARAM($select,$params5);
            $res    = $select->execute();
            if ($res) {//(ssid, tokenid, platform) VALUES ('$ssid', '$token', '$platform')"))
                return "success";
            } else {
                return "fail";
            }
        }
    }
    return "fail";
}

function delToken($token, $userid) {
    global $dbConn;
    
    $sel_query = "SELECT * FROM cms_mobile_token WHERE tokenid=:Token";
    $params[] = array(  "key" => ":Token",
                         "value" =>$token);
    $select = $dbConn->prepare($sel_query);
    PDO_BIND_PARAM($select,$params);
    $sel_res    = $select->execute();
    $sel_count = $select->rowCount();
    if($sel_res && $sel_count > 0){
        $sel_ret    = $select->fetchAll(PDO::FETCH_ASSOC);
        foreach($sel_ret as $sel_item){
            $params1 = array(); 
            $del_query = "DELETE FROM cms_tubers WHERE uid=:Uid";
            $params1[] = array(  "key" => ":Uid",
                                "value" =>$sel_item['ssid']);
            $del = $dbConn->prepare($del_query);
            PDO_BIND_PARAM($del,$params1);
            $del->execute();
        }
    }
    $params2 = array();
    $query = "DELETE FROM cms_mobile_token WHERE tokenid=:Token";
    $params2[] = array(  "key" => ":Token",
                        "value" =>$token);
    $delete = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete,$params2);
    $res    = $delete->execute();
    if ($res) {// AND ssid='$ssid'"))
        return "success";
    } else {
        return "fail";
    }
}

function getContinents() {
    global $myConn;
    $res = "";
    global $dbConn;
    //$sql = "select * from `cms_continents`";
    $sql = "SELECT * FROM `cms_videos` as v
		INNER JOIN `webgeocities` as c on v.cityid = c.id
		INNER JOIN `cms_countries` as cc
		ON cc.code = c.country_code
		INNER JOIN `cms_continents` as co
		ON cc.continent_code = co.code
		group by `continent_code`";
    $select = $dbConn->prepare($sql);
    $query    = $select->execute();
//    $query = db_query($sql);
    $res .= "<continents>\n";
    $data = $select->fetchAll();
//    while ($data = db_fetch_array($query)) {
    foreach($data as $data_item){
        $res .= "<continent numberOfVideo='0' thumb='api/ZoomGrid/gridimages/getGrid.php?continentCode=" . $data_item['code'] . "' code='" . $data_item['code'] . "'>" . $data_item['name'] . "</continent>";
    }
    //echo db_error($myConn);
    $res .= "</continents>\n";
    return $res;
}

function getAbsolutelinkFromReturnLink($linkReturned) {
    global $request;
//    $document_dir = dirname($_SERVER['SCRIPT_URL']);
    $document_dir = dirname($request->server->get('SCRIPT_URL', ''));

    if (strpos($linkReturned, $document_dir) !== FALSE) {
        return str_replace($document_dir . '/', '', $linkReturned);
    } else {
        return $linkReturned;
    }
}

function safeTag($text) {
    $res = str_replace("_", "-", $text);
    $res = str_replace(">", "", $text);
    $res = str_replace("<", "", $text);
    $res = str_replace("'", "", $text);
    $res = str_replace("\"", "", $text);
    $res = str_replace("?", "", $text);
    $res = str_replace("\\", "", $text);
    $res = str_replace("/", "", $text);
    $res = strip_tags($res);
    $res = trim($res);

    //$res = mb_convert_encoding($res, 'UTF-8');
    //$res = utf8_encode($res);
    //$res = htmlspecialchars($res);
    //$res = htmlentities($res, ENT_QUOTES, "UTF-8");
    //$text = remove_accents($text);
    return $res;
}

function safeXML($text) {
//    $res = html_entity_decode($text, ENT_QUOTES | ENT_XHTML, 'UTF-8');
    $res = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
//    $res = str_replace("&", "&amp;", $text);
//    $res = strip_tags($res);
//    $res = trim($res);
    //$res = mb_convert_encoding($res, 'UTF-8');
    //$res = utf8_encode($res);
    //$res = htmlspecialchars($res);
    //$res = htmlentities($res, ENT_QUOTES, "UTF-8");
    //$text = remove_accents($text);
    return $res;
}

function homeVideosMostViewed($limit, $page) {
    return getVideosMostView($limit, $page);
}

function getCityById($id) {
    global $dbConn;
    $params = array();  
    global $myConn;
//    $sql = "select * from `webgeocities` where `id` = " . db_sanitize($id) . " limit 1;";
    $sql = "select * from `webgeocities` where `id` = :Id limit 1;";
    $params[] = array(  "key" => ":Id",
                        "value" =>$id);
    $select = $dbConn->prepare($sql);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
//    $query = db_query($sql);
    $row = $select->fetch();
//    return db_fetch_array($query);
    return $row;
}

function getCities($Country) {
    global $myConn;
	global $dbConn;
	$params  = array(); 
	$params2 = array();
    $res = "";
//    $sql = "select * from `webgeocities` as c where UCASE(`country_code`) = UCASE('" . $Country . "') and c.id in (select `cityid` from `cms_videos`) ";
    $sql = "select * from `webgeocities` as c where UCASE(`country_code`) = UCASE(:Country) and c.id in (select `cityid` from `cms_videos`) ";
    $params[] = array(  "key" => ":Country",
                        "value" =>$Country);
    $select = $dbConn->prepare($sql);
    PDO_BIND_PARAM($select,$params);
    $query    = $select->execute();
//    $query = db_query($sql);


    //echo db_error($myConn);

    $res .= "<cities>";
    $data = $select->fetchAll();
//    while ($data = db_fetch_array($query)) {
    foreach($data as $data_item){
//        $checkquery = "SELECT COUNT(id) FROM  `cms_videos` WHERE  `cityid` = '" . $data['id'] . "' and `is_public` = '2';";
        $checkquery = "SELECT COUNT(id) FROM  `cms_videos` WHERE  `cityid` = :Data and `is_public` = '2';";
	$params2[] = array(  "key" => ":Data",
                            "value" =>$data['id']);
	$select = $dbConn->prepare($checkquery);
	PDO_BIND_PARAM($select,$params2);
	$checkdo    = $select->execute();
//        $checkdo = db_query($checkquery);

	$ret    = $select->rowCount();
        if ($ret > 0) {
//            $dato = db_fetch_array($checkdo);
            $dato = $select->fetch();
            if ($dato[0] >= 9) {
                $res .= '<city code="' . $data_item['id'] . '" thumb="api/ZoomGrid/gridimages/getGrid.php?cityID=' . $data_item['id'] . '">' . $data_item['name'] . '</city>';
            }
        }
    }
    $res .= "</cities>";
    return $res;
}

function getVideosInCity($id, $limit = 30, $page = 0) {
    $default_opts = array(
        'limit' => $limit,
        'page' => $page,
        'type' => 'a',
        'city_id' => $id
    );
    /* $offset = $page*$limit;
      global $myConn; */
    //$sql = "select * from `cms_videos` where `cityid`='".$id."' limit ".$offset.",".$limit.";";
    //echo $sql;
    //$sql = "select * from `cms_videos; ";
    //$query = db_query($sql);

    $datas = mediaSearch($default_opts);

    /* while($row = db_fetch_array($query))
      {
      $datar[] = $row;
      } */
    return $datas;
}

/* function getAllCitiesWithVideo()
  {
  global $myConn;
  $res = "";
  $sql = "select * from `webgeocities` where `id` in (select `cityid` from `cms_videos`) group by `longitude` ,	`latitude`";
 // $query = db_query($sql);
  $res .= "<cities>";
  while ($data = db_fetch_array($query))
  {
  $res .= '<city id="'.$data['id'].'" country_code="'.$data['country_code'].'" thumb="'.generateThumbnailsForCity($data['id']).'">'.$data['name'].'</city>';
  }
  $res .= "</cities>";
  return $res;
  } */

//autocompleteCity.php
function getFastCity($text) {
    $res = array();
    $trimed_text = trim($text);
    if(!isset($text) || empty($trimed_text))
    {
        echo json_encode($res);
        exit;
    }
    global $myConn;
    global $dbConn;
    $params = array();  

    //SELECT `City`,`Country`  FROM `cities` WHERE `City` LIKE 'c%' order by `Population` desc  limit 0,5
//    $sql = "Select `id`, name as City, country_code as Country , accent as AccentCity,`latitude`,`longitude` FROM `webgeocities` WHERE `name` LIKE '%" . db_sanitize($text) . "%' order by `name` asc limit 0,5; ";
//    $sql = "Select `id`, name as City, country_code as Country , accent as AccentCity,`latitude`,`longitude` FROM `webgeocities` WHERE `name` LIKE :Text  order by `name` asc limit 0,5; ";
    $sql = "SELECT C.id , C.name as City, C.country_code as Country, C.accent as AccentCity, C.longitude, C.latitude, COALESCE(S.state_name, '') AS state_name "
            . "FROM webgeocities AS C LEFT JOIN states AS S ON C.country_code=S.country_code AND C.state_code=S.state_code "
            . "WHERE C.name LIKE :Text GROUP BY C.name,C.state_code, C.country_code ORDER BY C.name ASC LIMIT 15";
    $params[] = array(  "key" => ":Text",
                        "value" =>"%".$text."%");
    $select = $dbConn->prepare($sql);
    PDO_BIND_PARAM($select,$params);
    $query    = $select->execute();
    $data = $select->fetchAll(PDO::FETCH_ASSOC);

    foreach($data as $data_item){
        $state_name=''; 
        if($data_item['state_name']!='') $state_name= ', '.$data_item['state_name'];
        $res[]=array(
            'id'=>$data_item['id'],
            'name'=>$data_item['City'].$state_name,
            'country'=>$data_item['Country'],
            'accent'=>$data_item['AccentCity'],
            'longitude'=>$data_item['longitude'],
            'latitude'=>$data_item['latitude']
        );
        //"<city id=\"" . $data_item['id'] . "\" country='" . $data_item['Country'] . "' accent='" . $data_item['AccentCity'] . "' latitude='" . $data_item['latitude'] . "' longitude='" . $data_item['longitude'] . "' >" . $data_item['City'] . "</city>";
    }
    header('Content-type: application/json');
    echo json_encode($res);
}

function getTubersWithInfo($latitude, $longitude, $radius) {//by Gerard For Tourit Tubers
    global $myConn;
    global $dbConn;
    $params = array();  
    $data = tubersGetByLocation($latitude, $longitude, $radius);
    $res = "";
    foreach ($data as $data2) {
        $sql = "Select * FROM `cms_users` WHERE id = :Data2; ";
	$params[] = array(  "key" => ":Data2",
                            "value" =>$data2['user_id']);
	$select = $dbConn->prepare($sql);
	PDO_BIND_PARAM($select,$params);
	$query    = $select->execute();
//        $query = db_query($sql);
	$data1 = $select->fetchAll(PDO::FETCH_ASSOC);
//        while ($data1 = db_fetch_assoc($query)) {
        foreach($data1 as $data1_item){			
            $res .= "<tuber>";
            $res .= "<id>" . $data1_item['id'] . "</id>";
			
			/*code changed by sushma mishra on 30-sep-2015 to get fullname using returnUserDisplayName function starts from here*/
			$userDetail = getUserInfo($data1_item['id']);
            //$res .= "<fullname>" . htmlEntityDecode($data1_item['FullName']) . "</fullname>";
			$res .= "<fullname>" . returnUserDisplayName($userDetail) . "</fullname>";
			/*code changed by sushma mishra on 30-sep-2015 ends here*/
			
            $res .= "<longitude>" . $data2['longitude'] . "</longitude>";
            $res .="<latitude>" . $data2['latitude'] . "</latitude>";
            $res .= "<category_id>3</category_id>";
            $res .="</tuber>";
        }
    }
    return $res;
}

function getFastCityTT($text) {//by Gerard for Tourist Tubers SEarch
    global $myConn;
    global $dbConn;
    $params = array();  

    //SELECT `City`,`Country`  FROM `cities` WHERE `City` LIKE 'c%' order by `Population` desc  limit 0,5
//    $sql = "Select name as City, country_code as Country, accent as AccentCity,`latitude`,`longitude` FROM `webgeocities` WHERE `name` LIKE '%" . db_sanitize($text) . "%' order by `name` asc limit 0,15; ";
    $sql = "Select name as City, country_code as Country, accent as AccentCity,`latitude`,`longitude` FROM `webgeocities` WHERE `name` LIKE :Text order by `name` asc limit 0,15; ";
    $params[] = array(  "key" => ":Text",
                        "value" =>"%".$text."%");
    $select = $dbConn->prepare($sql);
    PDO_BIND_PARAM($select,$params);
    $query    = $select->execute();
//    $query = db_query($sql);
    $data = $select->fetchAll(PDO::FETCH_ASSOC);
    $res = "";
    foreach($data as $data_item){
//    while ($data = db_fetch_assoc($query)) {
        $res .= "<city>";
        //$res .= "<country>".$data['Country']."</country>";
        //$res .= "<accent>".$data['AccentCity']."</accent>";
        $res .= "<latitude>" . $data_item['latitude'] . "</latitude>";
        $res .= "<longitude>" . $data_item['longitude'] . "</longitude>";
        $res .= "<name>" . $data_item['City'] . "</name>";
        $res .= "<category_id>4</category_id>";
        $res .="</city>";
    }
    return $res;
}

function getCountryCodeTT($text) {//by Gerard for Tourist Tubers SEarch
    global $myConn;
    global $dbConn;
    $params = array();  
//    $sql = "select * from `cms_countries` WHERE `name` LIKE '%" . db_sanitize($text) . "%' limit 0,15;";
    $sql = "select * from `cms_countries` WHERE `name` LIKE :Text limit 0,15;";
    $params[] = array(  "key" => ":Text",
                        "value" =>"%".$text."%");
    $select = $dbConn->prepare($sql);
    PDO_BIND_PARAM($select,$params);
    $query    = $select->execute();
//    $query = db_query($sql);
    $res = "";
    $data = $select->fetchAll();
//    while ($data = db_fetch_array($query)) {
    foreach($data as $data_item){
        $res .= "<country>";
        $res .= "<name>" . $data_item['full_name'] . "</name>";
        $res.= "<longitude>" . $data_item['longitude'] . "</longitude>";
        $res .= "<latitude>" . $data_item['latitude'] . "</latitude>";
        $res .= "<category_id>5</category_id>";
        $res .="</country>";
    }
    return $res;
}

function getFastCityWithCoutnry($country, $text) {
    $res = array();
    $trimed_text = trim($text);
    if(!isset($text) || empty($trimed_text))
    {
        echo json_encode($res);
        return;
    }
    global $myConn;
    global $dbConn;
    $params = array();  

    //SELECT `City`,`Country`  FROM `cities` WHERE `City` LIKE 'c%' order by `Population` desc  limit 0,5
//    $sql = "Select `id`, name as City, country_code as Country, accent as AccentCity, `longitude`,`latitude`  FROM `webgeocities` WHERE `country_code` ='" . db_sanitize(strtoupper($country)) . "' and `name` LIKE '%" .
//            db_sanitize($text) . "%' group by `longitude`,`latitude`  order by `name` asc limit 0,15; ";
    $sql = "SELECT C.id , C.name as City, C.country_code as Country, C.accent as AccentCity, C.longitude, C.latitude, COALESCE(S.state_name, '') AS state_name "
            . "FROM webgeocities AS C LEFT JOIN states AS S ON C.country_code=S.country_code AND C.state_code=S.state_code "
            . "WHERE C.country_code=:Country AND C.name LIKE :Text GROUP BY C.name,C.state_code,C.country_code ORDER BY C.name ASC LIMIT 15";
//    $sql = "Select `id`, name as City, country_code as Country, accent as AccentCity, `longitude`,`latitude`  "
//            . "FROM `webgeocities` "
//            . "WHERE `country_code` =:Country and `name` LIKE :Text group by `longitude`,`latitude`  order by `name` asc limit 0,15; ";

    $params[] = array(  "key" => ":Country",
                        "value" =>$country);
    $params[] = array(  "key" => ":Text",
                        "value" =>"%".$text."%");
    $select = $dbConn->prepare($sql);
    PDO_BIND_PARAM($select,$params);
    $query    = $select->execute();
    //$res = "<cities>";
    
    $data = $select->fetchAll(PDO::FETCH_ASSOC);

    foreach($data as $data_item){
        $state_name=''; 
        if($data_item['state_name']!='') $state_name= ', '.$data_item['state_name'];
        $res []=array(
            'id'=>$data_item['id'],
            'name'=>$data_item['City'].$state_name,
            'country'=>$data_item['Country'],
            'accent'=>$data_item['AccentCity'],
            'longitude'=>$data_item['longitude'],
            'latitude'=>$data_item['latitude']
        ); 
       //"<city id=\"" . $data_item['id'] . "\" country=\"" . $data_item['Country'] . "\" accent=\"" . $data_item['AccentCity'] . "\" longitude=\"" . $data_item['longitude'] . "\" Latitude=\"" . $data_item['latitude'] . "\" Population=\"0\" >" . $data_item['City'] . "</city>";
    }
    header('Content-type: application/json');
    echo json_encode($res);
}

function videosGetRelatedElastic($photoInfo, $limit = 5, $start = 0, $loggedInUser = 0){
    global $CONFIG;
    $imageInfo = $photoInfo;
    
    $imageTitle     = str_replace('-', ' ', cleanTitle($imageInfo['title']));
    $userImage      = $imageInfo['userid'];
    $ImageCityId    = $imageInfo['cityid'];
//    $loggedInUser   = $this->userGetID();
    $params1 = array('hosts' => array($CONFIG [ 'elastic' ] [ 'ip' ] ));
    $client = new Elasticsearch\Client($params1);
    $searchParams['index'] = $CONFIG [ 'elastic' ] [ 'index' ];
    $searchParams['type'] = 'media';
    if($loggedInUser){
        $searchParams['body'] = array (
            'from' => $start * $limit,
            'size' => $limit,
            'query' => 
            array (
              'bool' => 
              array (
                'must' => 
                array (
                  0 => 
                  array (
                    'match' => 
                    array (
                      'userid' => $userImage,
                    ),
                  ),
                  1 => 
                  array (
                    'bool' => 
                    array (
                      'should' => 
                      array (
                        0 => 
                        array (
                          'function_score' => 
                          array (
                            'query' => 
                            array (
                              'query_string' => 
                              array (
                                'default_field' => 'title',
                                'query' => $imageTitle,
                              ),
                            ),
                            'boost' => 100,
                          ),
                        ),
                        1 => 
                        array (
                          'function_score' => 
                          array (
                            'query' => 
                            array (
                              'match' => 
                              array (
                                'cityid' => $ImageCityId,
                              ),
                            ),
                            'boost' => 15,
                          ),
                        ),
                        2 => 
                        array (
                          'function_score' => 
                          array (
                            'query' => 
                            array (
                              'match' => 
                              array (
                                'userid' => $userImage,
                              ),
                            ),
                            'boost' => 1,
                          ),
                        ),
                      ),
                    ),
                  ),
                ),
              ),
            ),
          );
    }
    else{
        $searchParams['body'] = array (
            'from' => $start * $limit,
            'size' => $limit,
            'query' => 
            array (
              'bool' => 
              array (
                'should' => 
                array (
                  0 => 
                  array (
                    'function_score' => 
                    array (
                      'query' => 
                      array (
                        'query_string' => 
                        array (
                          'default_field' => 'title',
                          'query' => $imageTitle,
                        ),
                      ),
                      'boost' => 100,
                    ),
                  ),
                  1 => 
                  array (
                    'function_score' => 
                    array (
                      'query' => 
                      array (
                        'match' => 
                        array (
                          'cityid' => $ImageCityId,
                        ),
                      ),
                      'boost' => 15,
                    ),
                  ),
                ),
              ),
            ),
          );
    }
    
    try{
        $retDoc = $client->search($searchParams);
        $main_result = $retDoc['hits']['hits'];
    } catch (Exception $ex) {
        echo $ex->getMessage();
        echo '          '.json_encode($searchParams['body']);
        exit;
    }
    $k = 0;
    $matches = 0;
    $userVideos['media']=array();
    foreach ($main_result as $document) {
        $userVideos['media'][$k]['id'] = $document['_source']['id'];
        $userVideos['media'][$k]['title'] = $document['_source']['title'];
        $userVideos['media'][$k]['image_video'] = $document['_source']['image_video'];
        $userVideos['media'][$k]['location'] = $document['_source']['location'];
        $userVideos['media'][$k]['nb_views'] = $document['_source']['nb_views'];
        $userVideos['media'][$k]['pdate'] = $document['_source']['pdate'];
        $userVideos['media'][$k]['like_value'] = $document['_source']['like_value'];
        $userVideos['media'][$k]['nb_comments'] = $document['_source']['nb_comments'];
        $userVideos['media'][$k]['description'] = $document['_source']['description'];
        $userVideos['media'][$k]['video_url'] = $document['_source']['video_url'];
        $userVideos['media'][$k]['rating'] = $document['_source']['rating'];
        $userVideos['media'][$k]['name'] = $document['_source']['name'];
        $userVideos['media'][$k]['type'] = $document['_source']['image_video'];
        $userVideos['media'][$k]['code'] = $document['_source']['code'];
        $userVideos['media'][$k]['fullpath'] = $document['_source']['fullpath'];
        $userVideos['media'][$k]['relativepath'] = $document['_source']['relativepath'];
        $userVideos['media'][$k]['userid'] = $document['_source']['userid'];
        $userVideos['media'][$k]['channelid'] = $document['_source']['channelid'];
        $userVideos['media'][$k]['duration'] = $document['_source']['duration'];
        $k++;
        $matches++;
    }
    return $userVideos;
}

function getRelatedVideos($page, $limit, $vid, $loggedUserId) {
    $id = $_SESSION['ssid'];
    $_SESSION['ssid'] = "";

    $videoInfo = getVideoInfo($vid);
    $_SESSION['ssid'] = $id;
    $videotype = $videoInfo['type'];
    $type = "a";
    if (strpos($videotype, "image") === false) {
        $type = "v";
    } else {
        $type = "i";
    }

    $options = array('limit' => $limit, 'type' => 'v', 'orderby' => 'similarity', 'order' => 'd', 'page' => $page, 'vid' => $vid, 'type' => $type);
    return videosGetRelatedElastic($videoInfo, $limit, $page, $loggedUserId);
//    return videosGetRelatedSolr($videoInfo,null,$limit,$page,1);
    //return mediaSearch($options);
}

//allcountries.php
function getAllCountries() {
    global $myConn;
    global $dbConn;
//    $params  = array(); 
    $params2 = array();   
    $res = "";
    $sql = "select * from `cms_countries`"; // where UCASE(`code`) in (select UCASE(`country_code`) from `webgeocities`)";
    //$sql = "select * from `cms_countries` where UCASE(`code`) in (select UCASE(`country_code`) from `cms_mobile_countryXY`)";
//    $query = db_query($sql);
    $select = $dbConn->prepare($sql);
    $query    = $select->execute();
    $res .= "<countries>\n";
    $data = $select->fetchAll();
//    while ($data = db_fetch_array($query)) {
    foreach($data as $data_item){
        if (checkCountryVideos($data['code']) > 0) {
            $thumb = "api/ZoomGrid/gridimages/getGrid.php?countryCode=" . $data_item['code'];
        } else {
            $thumb = "";
        }

        $res .= '<country country_code="' . $data_item['code'] . '" name="' . str_replace("&", "and", $data_item['name']) .
                '" continent_code="' . $data_item['continent_code'] . '" country_thumb="' . $thumb . '" >' . "\n";

//        $coordssql = " SELECT * FROM `cms_mobile_countryXY` WHERE `country_code` = '" . $data_item['code'] . "' ; ";
        $coordssql = " SELECT * FROM `cms_mobile_countryXY` WHERE `country_code` = :Code ; ";
	$params2[] = array(  "key" => ":Code",
                             "value" =>$data_item['code']);
	$select = $dbConn->prepare($coordssql);
	PDO_BIND_PARAM($select,$params2);
	$send_coordssql    = $select->execute();
//        $send_coordssql = db_query($coordssql);
	$coordsData = $select->fetchAll();
//        while ($coordsData = db_fetch_array($send_coordssql)) {
        foreach($coordsData as $coordsData_item){
            if ($coordsData['iscenter'] == "0") {
                $res .= '<coords>' . $coordsData_item['x'] . ' ' . $coordsData_item['y'] . '</coords>' . "\n";
                $res .= '<coordsx>' . $coordsData_item['x'] . '</coordsx>' . "\n";
                $res .= '<coordsy>' . $coordsData_item['y'] . '</coordsy>' . "\n";
            } else {
                $res .= '<center>' . $coordsData_item['x'] . ' ' . $coordsData_item['y'] . '</center>' . "\n";
            }
        }


        $res .= '</country>' . "\n";
    }
    $res .= "</countries>";
    str_replace("&", "&amp;", $res);
    //echo db_error($myConn);
    return $res;
}

function checkCountryVideos($countryCode) {
    global $myConn;
    global $path;
    global $dbConn;
    $params = array();  

//    $sql = "SELECT v.* FROM `cms_videos` as v
//		INNER JOIN `webgeocities` as c on v.cityid = c.id
//		INNER JOIN `cms_countries` as cc
//		ON cc.code = UCASE(c.country_code)
//		where UCASE(`country_code`) = UCASE('" . $countryCode . "')
//		order by rand() limit 0,20";
    $sql = "SELECT v.* FROM `cms_videos` as v
		INNER JOIN `webgeocities` as c on v.cityid = c.id
		INNER JOIN `cms_countries` as cc
		ON cc.code = UCASE(c.country_code)
		where UCASE(`country_code`) = UCASE(:CountryCode)
		order by rand() limit 0,20";
    $params[] = array(  "key" => ":CountryCode",
                        "value" =>$countryCode);
    $select = $dbConn->prepare($sql);
    PDO_BIND_PARAM($select,$params);
    $query    = $select->execute();
//    $query = db_query($sql);

	$numOfimages    = $select->rowCount();

//    $numOfimages = db_num_rows($query);
    return $numOfimages;
}

function generateThumbnailsForCountry($countryCode) {
    global $myConn;
    global $path;
    global $dbConn;
    $params = array();  

//    $sql = "SELECT v.* FROM `cms_videos` as v
//		INNER JOIN `webgeocities` as c on v.cityid = c.id
//		INNER JOIN `cms_countries` as cc
//		ON cc.code = UCASE(c.country_code)
//		where UCASE(`country_code`) = UCASE('" . $countryCode . "')
//		order by rand() limit 0,20";

    $sql = "SELECT v.* FROM `cms_videos` as v
		INNER JOIN `webgeocities` as c on v.cityid = c.id
		INNER JOIN `cms_countries` as cc
		ON cc.code = UCASE(c.country_code)
		where UCASE(`country_code`) = UCASE(:CountryCode)
		order by rand() limit 0,20";
    $params[] = array(  "key" => ":CountryCode",
                        "value" =>$countryCode);
    $select = $dbConn->prepare($sql);
    PDO_BIND_PARAM($select,$params);
    $query    = $select->execute();

//    $query = db_query($sql);

    $numOfimages    = $select->rowCount();
//    $numOfimages = db_num_rows($query);

    $tempNumRows = 0;
    $tempNumImages = 0;
    /*
      do
      {
      $tempNumImages = $tempNumRows * $tempNumRows;
      $tempNumRows++;
      }while ($numOfimages >= $tempNumImages);

      $thumbsArray = array();

      //		for($i=0;$i<$tempNumImages;$i++)
      for($i=0;$i<$numOfimages;$i++)
      {
      $data = db_fetch_array($query);
      //echo $data['id']." ".$data['fullpath'];
      //$thumbsArray[] = getVideoThumbnail($data['id'],"../".$data['fullpath'],0);
      $thumbsArray[] = substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path));
      //echo substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path));
      }

      //var_dump($thumbsArray);

      for ($i=0;$i<count($thumbsArray);$i++)
      {

      if (($thumbsArray[$i]!= "") && ($thumbsArray[$i]!= null))
      {
      $fullThumbsArray[] = $thumbsArray[$i];
      }
      } */
    //echo $countryCode;
    //var_dump($fullThumbsArray);
    //$destfile = $continent."_mixed.jpg";
    //if (count($fullThumbsArray>0))
    //{
    $fullThumbsArray = array();
    $data = $select->fetchAll();
//    while ($data = db_fetch_array($query)) {
    foreach($data as $data_item){
        //if ($countryCode == "AR"){ echo $data['id']." ".$path.$data['fullpath']; echo $sql;}
        $fullThumbsArray[] = substr(getVideoThumbnail($data['id'], $path . $data_item['fullpath'], 0), strlen($path));
//			echo substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path));
    }
    if (count($fullThumbsArray) == 0)
        return "";
    $srcFile = $fullThumbsArray[rand(0, count($fullThumbsArray) - 1)];
    return $srcFile;
    //}else
    //{
    //return "";
    //}
}

function generateThumbnailsForCity($cityCode) {
    global $myConn;
    global $path;
    global $dbConn;
    $params = array(); 


//    $sql = "SELECT v.* FROM `cms_videos` as v
//		INNER JOIN `webgeocities` as c on v.cityid = c.id
//		INNER JOIN `cms_countries` as cc
//		ON cc.code =  UCASE(c.country_code)
//		where UCASE(`cityid`) = UCASE('" . $cityCode . "')
//		order by rand() limit 0,20";
    $sql = "SELECT v.* FROM `cms_videos` as v
		INNER JOIN `webgeocities` as c on v.cityid = c.id
		INNER JOIN `cms_countries` as cc
		ON cc.code =  UCASE(c.country_code)
		where UCASE(`cityid`) = UCASE(:CityCode)
		order by rand() limit 0,20";
    $params[] = array(  "key" => ":CityCode",
                        "value" =>$cityCode);
    $select = $dbConn->prepare($sql);
    PDO_BIND_PARAM($select,$params);
    $query    = $select->execute();
//    $query = db_query($sql);

//    $numOfimages = db_num_rows($query);

    $numOfimages    = $select->rowCount();
//		echo $cityCode."   ".$numOfimages;
    /* $tempNumRows = 0;
      $tempNumImages = 0;

      do
      {
      $tempNumImages = $tempNumRows * $tempNumRows;
      $tempNumRows++;
      }while ($numOfimages >= $tempNumImages);

      $thumbsArray = array();

      for($i=0;$i<$tempNumImages;$i++)
      {
      $data = db_fetch_array($query);
      //echo $data['id']." ".$data['fullpath'];
      //$thumbsArray[] = getVideoThumbnail($data['id'],"../".$data['fullpath'],0);
      $thumbsArray[] = substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path));
      //echo substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path));
      }

      //var_dump($thumbsArray);
      $fullThumbsArray = array();
      for ($i=0;$i<count($thumbsArray);$i++)
      {

      if (($thumbsArray[$i]!= "") && ($thumbsArray[$i]!= null))
      {
      $fullThumbsArray[] = $thumbsArray[$i];
      }
      } */
    //var_dump($fullThumbsArray);
    //$destfile = $continent."_mixed.jpg";
    //if (count($fullThumbsArray>0))
    //{
    $fullThumbsArray = array();
    $data = $select->fetchAll();
//    while ($data = db_fetch_array($query)) {
    foreach($data as $data_item){
        //if ($countryCode == "AR"){ echo $data['id']." ".$path.$data['fullpath']; echo $sql;}
        $fullThumbsArray[] = substr(getVideoThumbnail($data_item['id'], $path . $data_item['fullpath'], 0), strlen($path));
    }
    $srcFile = $fullThumbsArray[rand(0, count($fullThumbsArray) - 1)];
    return $srcFile;
    //}else
    //{
    //return "";
    //}
}

function getVideosGridBy($continent, $country, $city, $width, $height) {

    global $path;
    global $CONFIG;
    global $dbConn;
    $params = array(); 

    $orderAttr = "nb_views";
    if (isset($continent) && $continent != "") {
//        $getVideosBy = "SELECT v.* FROM `cms_videos` as v
//							  INNER JOIN `webgeocities` as c on v.cityid = c.id
//							  INNER JOIN `cms_countries` as cc
//							  ON UCASE(`cc`.`code`) = UCASE(`c`.`country_code`)
//							  where cc.`continent_code` = '" . $continent . "' and v.`is_public`='2'";
        $getVideosBy = "SELECT v.* FROM `cms_videos` as v
							  INNER JOIN `webgeocities` as c on v.cityid = c.id
							  INNER JOIN `cms_countries` as cc
							  ON UCASE(`cc`.`code`) = UCASE(`c`.`country_code`)
							  where cc.`continent_code` = :Continent and v.`is_public`='2'";
	$params[] = array(  "key" => ":Continent",
                            "value" =>$continent);
    } else if (isset($country) && $country != "") {
//        $getVideosBy = "SELECT v.* FROM `cms_videos` as v
//							  INNER JOIN `webgeocities` as c on v.cityid = c.id
//							  INNER JOIN `cms_countries` as cc
//							  ON UCASE(`cc`.`code`) = UCASE(`c`.`country_code`)
//							  where UCASE(`country_code`) = UCASE('" . $country . "') and v.`is_public`='2'";
        $getVideosBy = "SELECT v.* FROM `cms_videos` as v
							  INNER JOIN `webgeocities` as c on v.cityid = c.id
							  INNER JOIN `cms_countries` as cc
							  ON UCASE(`cc`.`code`) = UCASE(`c`.`country_code`)
							  where UCASE(`country_code`) = UCASE(:Country) and v.`is_public`='2'";
	$params[] = array(  "key" => ":Country",
                            "value" =>$country);
    } else if (isset($city) && $city != "") {
//        $getVideosBy = "SELECT v.* FROM `cms_videos` as v
//							  INNER JOIN `webgeocities` as c on v.cityid = c.id
//							  INNER JOIN `cms_countries` as cc
//							  ON UCASE(`cc`.`code`) = UCASE(`c`.`country_code`)
//							  where UCASE(`cityid`) = UCASE('" . $city . "') and v.`is_public`='2'";
        $getVideosBy = "SELECT v.* FROM `cms_videos` as v
							  INNER JOIN `webgeocities` as c on v.cityid = c.id
							  INNER JOIN `cms_countries` as cc
							  ON UCASE(`cc`.`code`) = UCASE(`c`.`country_code`)
							  where UCASE(`cityid`) = UCASE(:City) and v.`is_public`='2'";
	$params[] = array(  "key" => ":City",
                            "value" =>$city);
    }

    $getVideosBy .= " ORDER BY `" . $orderAttr . "`,rand() DESC LIMIT 0,9 ";

//    $sendQuery = db_query($getVideosBy);
    $select = $dbConn->prepare($getVideosBy);
    PDO_BIND_PARAM($select,$params);
    $sendQuery    = $select->execute();
    $thumbArray = array();
    $thumbArrayVarlast = array();
    $dataQuery = $select->fetchAll();
//    while ($dataQuery = db_fetch_array($sendQuery)) {
    foreach($dataQuery as $dataQuery_item){
        //$thumbArray[] = getVideoThumbnail($dataQuery['id'],$path.$dataQuery['fullpath'],0);
        if ((strpos($dataQuery_item['type'], "image") === false)) {
            $temps1 = substr(getVideoThumbnail($dataQuery_item['id'], $path . $dataQuery_item['fullpath'], 0), strlen($path));
            $thumbArrayVar = $temps1;
            //echo $temps1."\n";
        } else {
            $temps2 = substr(getImageThumbnail(getVideoInfo($dataQuery_item['id']), $path . $CONFIG ['video'] ['uploadPath']), strlen($path));
            $thumbArrayVar = $temps2;
            //echo "image $dataQuery[id]  :".$temps2."\n";
        }

        /////////////////////////////////
        //this codes needs testing on the tablet
        $thumbcache = "cache/thumbs/";
        $md5_reflink = md5($thumbArrayVar);
        $filename = $md5_reflink . "_" . $width . "_" . $height . ".jpg";
        if (!file_exists($path . $thumbcache . $filename)) {
            $options = array(
                'in_path' => $CONFIG['server']['root'] . $thumbArrayVar,
                'out_path' => $CONFIG['server']['root'] . $thumbcache . $filename,
                'w' => $width,
                'h' => $height,
                'keep_ratio' => false,
                'quality' => 100
            );

            mediaSubsample($options);
        }

        if (sizeof($thumbArrayVar) < 9) {
            $thumbArrayVarlast[] = ReturnLink($thumbcache . $filename);
        } else {
            $thumbArray[] = ReturnLink($thumbcache . $filename);
        }
        ////////////////////////////////
    }

    return array_merge($thumbArray, $thumbArrayVarlast);
}
/**
 * checks if a users sent a friend request for other and different different cases of a friend request
 * @param integer $user_id the current user 
 * @param integer $friend_id the second user
 * @return value  [1-> not a friend(you can send a request), 2-> already friend, 3-> request sent, 4-> request received]
 */
function friendRequestOccur($user_id, $friend_id) {

    $requestSent = sentRequestOccur($user_id, $friend_id);
    $usisfriend = userIsFriend($user_id, $friend_id);
    $requestReceived = sentRequestOccur($friend_id, $user_id);//4
    $acceptRequestReceived = userFreindRequestMade($friend_id, $user_id);
//    if($acceptRequestReceived == 2){
//        return true;
//    }
    $isfriend="1";
    if (!$usisfriend && !$requestSent && !$acceptRequestReceived) {
//        $acceptRequestSent = userFreindRequestMade($friend_id, $user_id);
//        if($acceptRequestSent!=false){ 
//            $isfriend="4";
//        }
        
        $isfriend="1";
    }
    else if($usisfriend && !$requestSent){
        $isfriend="2";

    } 
    else if($requestSent){
        $isfriend="3";

    }
    else if($requestReceived){
        $isfriend = "4";
    }
    
    return $isfriend;
}

function format_address($entity_info, $type = '', $country_name = ''){
    if(isset($entity_info['_source'])){
        $entity = $entity_info['_source'];
    }else{
        $entity = $entity_info;
        $entity['countryName'] = $country_name;
    }
    if($type != ''){
        $entity_info['_type'] = $type;
    }
    
    $cityName = "";
    $countryName = "";
    $stateName = "";
    $locationText = "";
    if($entity['city_id'] > 0){
        switch($entity_info['_type']){
            case "hotel":
                $cityName = trim($entity['city']);
                $countryName = trim($entity['country']);
                $stateName = trim($entity['stateName']);
                break;
            case "airport":
                $cityName = trim($entity['city']);
                $countryName = trim($entity['countryName']);
                $stateName = trim($entity['state_name']);
                break;
            case "poi":
                $cityName = trim($entity['city']);
                $countryName = trim($entity['country_name']);
                $stateName = trim($entity['state_name']);
                break;
        }
        $locationText = $cityName;
        if($stateName != ''){
            if($locationText != ''){
                $locationText .= ', ';
            }
            $locationText .= $stateName;
        }
        if($countryName != ''){
            if($locationText != ''){
                $locationText .= ', ';
            }
            $locationText .= $countryName;
        }
    }
    else{
        switch($entity_info['_type']){
            case "hotel":
                $locationText = $entity['location'];
                break;
            case "airport":
                $cityName = trim($entity['city']);
                $countryName = trim($entity['countryName']);
                $stateName = trim($entity['state_name']);
                $locationText = $cityName;
                if($stateName != ''){
                    if($locationText != ''){
                        $locationText .= ', ';
                    }
                    $locationText .= $stateName;
                }
                if($countryName != ''){
                    if($locationText != ''){
                        $locationText .= ', ';
                    }
                    $locationText .= $countryName;
                }
                break;
            case "poi":
                $locationText = $entity['address'];
                break;
        }
    }
    if($locationText == ''){
        switch($entity_info['_type']){
            case "hotel":
                $locationText = $entity['address'];
                break;
//            case "restaurant":
//                break;
            case "poi":
                $locationText = $entity['address'];
                break;
        }
    }
    if($entity_info['_type'] == 'restaurant'){
        switch($entity['country']){
            case 'us':
            case 'gb':
                $locationText = $entity['address'].', '.$entity['locality'].', '.$entity['region'].' '.$entity['postcode'].', '.$entity['countryName'];
                break;
            case 'fr':
                $locationText = $entity['address'].', '.$entity['postcode'].' '.$entity['locality'].', '.$entity['countryName'];
                break;
            case 'ar':
                $locationText = $entity['address'].', '.$entity['region'].', '.$entity['countryName'];
                break;
            default:
                
                $city           = $entity['locality'] ;
                $state          = '';
                $country        = $entity['countryName'];
                $address        = $entity['address'];
                $locality       = $entity['locality'];
                $region         = $entity['region'];
                $admin_region   = $entity['admin_region'];
                
                $str1='';
                if ( $entity['address'] != ''){
                    if ($str1 != '') $str1 .=', ';
                    $str1 .= $entity['address'];
                }
                if ( $entity['from_source'] == 'factual' ) {
                    if (isset($entity['city']) && $entity['city'] != '') {
                        if ($str1 != '') $str1 .=', ';
                        $str1 .= $entity['city'];
                    }else if (isset($entity['locality']) && $entity['locality'] != '') {
                        if ($str1 != '') $str1 .=', ';
                        $str1 .=$entity['locality'];
                    }
                    if (isset($entity['region']) && $entity['region'] != '' && $entity['region'] != $entity['locality'] ) {
                        if ($str1 != '') $str1 .=', ';
                        $str1 .= $entity['region'];
                    }else if (isset($entity['admin_region']) && $entity['admin_region'] != '' && $entity['admin_region'] != $entity['locality']  && $entity['admin_region'] != $entity['region']) {
                        if ($str1 != '') $str1 .=', ';
                        $str1 .=$entity['admin_region'];
                    }  
                    if (isset($entity['countryName']) && $entity['countryName'] != '') {
                        if ($str1 != '') $str1 .=', ';
                        $str1 .= $entity['countryName'];
                    }
                }else if (isset($entity['countryName']) && $entity['countryName'] != '') {
                    if ($str1 != '') $str1 .=', ';
                    $str1 .= $entity['countryName'];
                }
//                if (isset($city) && $city != '') {
//                    if ($title != '') $str1 .=', ';
//                    $str1 .= $city;
//                }
//                if (isset($locality) && $locality != '') {
//                    if ($title != '') $str1 .=', ';
//                    $str1 .=$locality;
//                    if ( $address != '' ){
//                        if ($str1 != '') $str1 .=', ';
//                        $str1 .= $address;
//                    }
//                }
//                if (isset($state) && $state != '') {
//                    if ($title != '') $str1 .=', ';
//                    $str1 .=$state;
//                }
//                if (isset($region) && $region != '' && $region != $region ) {
//                    if ($title != '') $str1 .=', ';
//                    $str1 .= $region;
//                    if ($locality == '' && $address != ''){
//                        if ($str1 != '') $str1 .=', ';
//                        $str1 .= $address;
//                    }
//                }else if (isset($admin_region) && $admin_region != '' && $admin_region != $locality) {
//                    if ($title != '') $str1 .=', ';
//                    $str1 .=$region;
//                    if ($locality == '' && $region == '' && $address != ''){
//                        if ($str1 != '') $str1 .=', ';
//                        $str1 .= $address;
//                    }
//                }
//                if ($locality == '' && $region == '' && $admin_region == '' && $address != ''){
//                    if ($title != '') $str1 .=', ';
//                    $str1 .= $address;
//                }
//                if (isset($country) && $country != '') {
//                    if ($title != '') $str1 .=', ';
//                    $str1 .= $country;
//                }
                $locationText = $str1;
//                $locationText = $entity['address'].', '.$entity['locality'].', '.$entity['countryName'];
                break;
        }
    }
    return $locationText;
}