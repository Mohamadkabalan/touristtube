<?php

/**
 * Functions that manipulate the cms_channel tables
 * @package channel
 */
define('CHANNEL_LOGO_WIDTH', 960);
define('CHANNEL_LOGO_HEIGHT', 100);
define('CHANNEL_ICON_WIDTH', 75);
define('CHANNEL_ICON_HEIGHT', 75);

/**
 * channel request parent
 */
define('CHANNEL_RELATION_TYPE_PARENT', 1);
/**
 * channel request sub
 */
define('CHANNEL_RELATION_TYPE_SUB', 2);

/**
 * channel default type
 */
define('CHANNEL_TYPE_DEFAULT', 1);
/**
 * channel diaspora type
 */
define('CHANNEL_TYPE_DIASPORA', 2);
/**
 * channel pace luce type
 */
define('CHANNEL_TYPE_PACELUCE', 3);

function uniord($u) {
    // i just copied this function fron the php.net comments, but it should work fine!
    $k = mb_convert_encoding($u, 'UCS-2LE', 'UTF-8');
    $k1 = ord(substr($k, 0, 1));
    $k2 = ord(substr($k, 1, 1));
    return $k2 * 256 + $k1;
}

function is_arabic($str) {
    if (mb_detect_encoding($str) !== 'UTF-8') {
        $str = mb_convert_encoding($str, mb_detect_encoding($str), 'UTF-8');
    }

    preg_match_all('/.|\n/u', $str, $matches);
    $chars = $matches[0];
    $arabic_count = 0;
    $latin_count = 0;
    $total_count = 0;
    foreach ($chars as $char) {
        //$pos = ord($char); we cant use that, its not binary safe 
        $pos = uniord($char);
        //echo $char ." --> ".$pos.PHP_EOL;

        if ($pos >= 1536 && $pos <= 1791) {
            $arabic_count++;
        } else if ($pos > 123 && $pos < 123) {
            $latin_count++;
        }
        $total_count++;
    }
    if (($arabic_count / $total_count) > 0.6) {
        // 60% arabic chars, its probably arabic
        return true;
    }
    return false;
}

function notfound() {
    return false;
}

/**
 * return the channel id from MD5
 * @param $md5_name the encrypted channel url
 * @param $md5_id the encrypted channel id
 * @return initeger the channel id  
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function MD5toNormalChannelID($md5_id, $md5_name) {
//    $query = "select id from cms_channel where MD5(id) = '$md5_id' AND MD5(channel_url) = '$md5_name'";
//    $result = mysql_query($query);
//    if ($res = mysql_fetch_array($result)) {
//        return $res[0];
//    } else {
//        return false;
//    }
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>

/**
 * return the channel record from MD5
 * @param $md5_name the encrypted channel url + channel id
 * @return array | false the cms_channel record or null if not found
 */
function MD5ChannelInfo($md5_name) {
    global $dbConn;
    $params = array();
    $query = "select * from cms_channel where MD5( concat(id,channel_url) ) = :Md5_name";
    $params[] = array(  "key" => ":Md5_name", "value" =>$md5_name);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res  = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        return $row;
    } else {
        return false;
    }
}

/**
 * gets the channel thumb for channles page/search given the channel info
 * @param array $channelInfo
 * @param start x position $coord_x
 * @param start y position $coord_y
 * @param image width $coord_w
 * @param image height $coord_h
 * @return initeger the image link  
 */
function createchannelThumb($channelInfo, $coord_x, $coord_y, $coord_w, $coord_h, $path = '') {
    /* $coord_x = 0;
      $coord_y = 0;
      $coord_w = 136;
      $coord_h = 76; */
    $filename = $channelInfo['header'];
    $savedfilename = "preview_" . $filename;
    $thumbpath = "media/channel/" . $channelInfo['id'] . "/thumb/";
    $thumbpathOriginal = "media/channel/" . $channelInfo['id'] . '/';
    $filePath = $path . '' . $thumbpath . $filename;
    $savedThumbPath = $path . '' . $thumbpath . $savedfilename;

    //unlink($savedThumbPath);

    if (!file_exists($savedThumbPath)) {

        // read image
        $ext = strtolower(substr(strrchr($filePath, '.'), 1)); // get the file extension
        switch ($ext) {
            case 'jpg':     // jpg
                $image_rs = imagecreatefromjpeg($filePath) or notfound();
                break;
            case 'png':     // png
                $image_rs = imagecreatefrompng($filePath) or notfound();
                break;
            case 'gif':     // gif
                $image_rs = imagecreatefromgif($filePath) or notfound();
                break;
            case 'JPG':     // JPG
                $image_rs = imagecreatefromjpeg($filePath) or notfound();
                break;
            case 'jpeg':     // jpeg
                $image_rs = imagecreatefromjpeg($filePath) or notfound();
                break;
            default:     // jpeg
                $image_rs = imagecreatefromjpeg($filePath) or notfound();
        }

        $new_rs = @imagecreatetruecolor($coord_w, $coord_h);

        // copy resized image to new canvas
        imagecopyresampled($new_rs, $image_rs, 0, 0, 160, 0, 238, 76, 800, 256);

        imagejpeg($new_rs, $savedThumbPath);
    }

    return ReturnLink($savedThumbPath,null,1);
}

/**
 * gets the album id from the table. options include:<br/>
 * <b>catalog_id</b>: the catalogue id<br/>
 * <b>video_id</b>: the video id<br/>
 * @param array $vInfo 
 * @return array | false an array of 'cms_videos_catalogs' records or false if none found.
 */
function getAlbumidFromTable($catalog_id, $video_id) {
//  Changed by Anthony Malak 28-04-2015 to PDO database

    global $dbConn;
    $params = array(); 
//    $query = "SELECT * FROM cms_videos_catalogs WHERE catalog_id = '" . $catalog_id . "' AND video_id = '" . $video_id . "'";
//    $ret = db_query($query);
//    //return $query;
//    if (!$ret || (db_num_rows($ret) == 0)) {
//        return false;
//    } else {
//        $ret_arr = array();
//        $row = db_fetch_array($ret);
//        return $row;
//    }
    $query = "SELECT * FROM cms_videos_catalogs WHERE catalog_id = :Catalog_id AND video_id = :Video_id";
    $params[] = array(  "key" => ":Catalog_id",
                        "value" =>$catalog_id);
    $params[] = array(  "key" => ":Video_id",
                        "value" =>$video_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    //return $query;
    if (!$res || ($ret == 0)) {
        return false;
    } else {
        $row = $select->fetch();
        return $row;
    }
//  Changed by Anthony Malak 28-04-2015 to PDO database

}

/**
 * gets the next media depending on criteria for albums. options include:<br/>
 * <b>id</b>: the id of the record in the table info array<br/>
 * <b>catalog_id</b>: the catalogue id<br/>
 * @param array $vInfo 
 * @return array | false an array of 'cms_videos_catalogs' records or false if none found.
 */
function getNextMediaAlbum($id, $catalog_id) {
//  Changed by Anthony Malak 28-04-2015 to PDO database

    global $dbConn;
    $params = array(); 
//    $query = "SELECT * FROM cms_videos_catalogs WHERE id = (select min(id) FROM cms_videos_catalogs WHERE id > " . $id . " AND catalog_id = '" . $catalog_id . "') ORDER BY id DESC LIMIT 0,1";
//    $ret = db_query($query);
//    //return $query;
//    if (!$ret || (db_num_rows($ret) == 0)) {
//        return false;
//    } else {
//        $ret_arr = array();
//        $row = db_fetch_array($ret);
//        $vinfo = getVideoInfo($row['video_id']);
//        return $vinfo;
//    }
    $query = "SELECT * FROM cms_videos_catalogs WHERE id = (select min(id) FROM cms_videos_catalogs WHERE id > :Id AND catalog_id = :Catalog_id ) ORDER BY id DESC LIMIT 0,1";
    $params[] = array(  "key" => ":Id", "value" =>$id);
    $params[] = array(  "key" => ":Catalog_id", "value" =>$catalog_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0)) {
        return false;
    } else {
        $ret_arr = array();
        $row = $select->fetch();
        $vinfo = getVideoInfo($row['video_id']);
        return $vinfo;
    }
//  Changed by Anthony Malak 28-04-2015 to PDO database

}

/**
 * gets the previous media depending on criteria. options include:<br/>
 * <b>id</b>: the id of the record in the table info array<br/>
 * <b>catalog_id</b>: the catalogue id<br/>
 * @param array $vInfo 
 * @return array | false an array of 'cms_videos' records or false if none found.
 */
function getPreviousMediaAlbum($id, $catalog_id) {
//  Changed by Anthony Malak 28-04-2015 to PDO database

    global $dbConn;
    $params = array();
//    $query = "SELECT * FROM cms_videos_catalogs WHERE id = (select max(id) FROM cms_videos_catalogs WHERE id < " . $id . " AND catalog_id = '" . $catalog_id . "') ORDER BY id DESC LIMIT 0,1";
//    $ret = db_query($query);
//    //return $query;
//    if (!$ret || (db_num_rows($ret) == 0)) {
//        return false;
//    } else {
//        $ret_arr = array();
//        $row = db_fetch_array($ret);
//        $vinfo = getVideoInfo($row['video_id']);
//        return $vinfo;
//    }
    $query = "SELECT * FROM cms_videos_catalogs WHERE id = (select max(id) FROM cms_videos_catalogs WHERE id < :Id AND catalog_id = :Catalog_id ) ORDER BY id DESC LIMIT 0,1";
    $params[] = array(  "key" => ":Id",
                        "value" =>$id);
    $params[] = array(  "key" => ":Catalog_id",
                        "value" =>$catalog_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0)) {
        return false;
    } else {
        $ret_arr = array();
        $row = $select->fetch();
        $vinfo = getVideoInfo($row['video_id']);
        return $vinfo;
    }
//  Changed by Anthony Malak 28-04-2015 to PDO database

}

/**
 * gets the next media depending on criteria. options include:<br/>
 * <b>vInfo</b>: the media info array<br/>
 * @param array $vInfo 
 * @return array | false an array of 'cms_videos' records or false if none found.
 */
function getNextMedia($vInfo, $_type) {
//  Changed by Anthony Malak 28-04-2015 to PDO database

    global $dbConn;
    $params = array(); 
//    if ($_type == 'i' || $_type == 'v') {
//        $query = "SELECT * FROM cms_videos WHERE id = (select min(id) FROM cms_videos WHERE id > " . $vInfo['id'] . " AND  published=1 AND image_video = '" . $_type . "' AND channelid = '" . $vInfo['channelid'] . "' AND userid = " . $vInfo['userid'] . " AND NOT EXISTS (SELECT video_id FROM cms_videos_catalogs WHERE video_id=id) ) ORDER BY pdate DESC LIMIT 0,1";
//    } else {
//        $query = "SELECT * FROM cms_videos WHERE id = (select min(id) FROM cms_videos WHERE id > " . $vInfo['id'] . " AND  published=1 AND channelid = '" . $vInfo['channelid'] . "' AND userid = " . $vInfo['userid'] . " AND NOT EXISTS (SELECT video_id FROM cms_videos_catalogs WHERE video_id=id) ) ORDER BY pdate DESC LIMIT 0,1";
//    }
//    $ret = db_query($query);
//    if (!$ret || (db_num_rows($ret) == 0)) {
//        return false;
//    } else {
//        $ret_arr = array();
//        $row = db_fetch_array($ret);
//        return $row;
//    } 
    if ($_type == 'i' || $_type == 'v') {
        $query = "SELECT * FROM cms_videos WHERE id = (select min(id) FROM cms_videos WHERE id > :Id AND  published=1 AND image_video = :Type AND channelid = :Channelid AND userid = :Userid AND NOT EXISTS (SELECT video_id FROM cms_videos_catalogs WHERE video_id=id) ) ORDER BY pdate DESC LIMIT 0,1";
	$params[] = array(  "key" => ":Id",
                            "value" =>$vInfo['id']);
	$params[] = array(  "key" => ":Type",
                            "value" =>$_type);
	$params[] = array(  "key" => ":Channelid",
                            "value" =>$vInfo['channelid']);
	$params[] = array(  "key" => ":Userid",
                            "value" =>$vInfo['userid']);
    } else {
        $query = "SELECT * FROM cms_videos WHERE id = (select min(id) FROM cms_videos WHERE id > :Id AND  published=1 AND channelid = :Channelid AND userid = :Userid AND NOT EXISTS (SELECT video_id FROM cms_videos_catalogs WHERE video_id=id) ) ORDER BY pdate DESC LIMIT 0,1";
	$params[] = array(  "key" => ":Id",
                            "value" =>$vInfo['id']);
	$params[] = array(  "key" => ":Channelid",
                            "value" =>$vInfo['channelid']);
	$params[] = array(  "key" => ":Userid",
                            "value" =>$vInfo['userid']);
    }
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0)) {
        return false;
    } else {
        $row = $select->fetch();
        return $row;
    }
//  Changed by Anthony Malak 28-04-2015 to PDO database

}

/**
 * gets the previous media depending on criteria. options include:<br/>
 * <b>vInfo</b>: the media info array<br/>
 * @param array $vInfo 
 * @return array | false an array of 'cms_videos' records or false if none found.
 */
function getPreviousMedia($vInfo, $_type) {
//  Changed by Anthony Malak 28-04-2015 to PDO database

    global $dbConn;
    $params = array();
//    if ($_type == 'i' || $_type == 'v') {
//        $query = "SELECT * FROM cms_videos WHERE id = (select max(id) FROM cms_videos WHERE id < " . $vInfo['id'] . " AND published=1 AND image_video = '" . $_type . "' AND channelid = '" . $vInfo['channelid'] . "' AND userid = " . $vInfo['userid'] . " AND NOT EXISTS (SELECT video_id FROM cms_videos_catalogs WHERE video_id=id) ) ORDER BY pdate DESC LIMIT 0,1";
//    } else {
//        $query = "SELECT * FROM cms_videos WHERE id = (select max(id) FROM cms_videos WHERE id < " . $vInfo['id'] . " AND published=1 AND channelid = '" . $vInfo['channelid'] . "' AND userid = " . $vInfo['userid'] . " AND NOT EXISTS (SELECT video_id FROM cms_videos_catalogs WHERE video_id=id) ) ORDER BY pdate DESC LIMIT 0,1";
//    }
//    $ret = db_query($query);
//    if (!$ret || (db_num_rows($ret) == 0)) {
//        return false;
//    } else {
//        $ret_arr = array();
//        $row = db_fetch_array($ret);
//        return $row;
//    }
    if ($_type == 'i' || $_type == 'v') {
        $query = "SELECT * FROM cms_videos WHERE id = (select max(id) FROM cms_videos WHERE id < :Id AND published=1 AND image_video = :Type AND channelid = :Channelid AND userid = :Userid AND NOT EXISTS (SELECT video_id FROM cms_videos_catalogs WHERE video_id=id) ) ORDER BY pdate DESC LIMIT 0,1";
        $params[] = array(  "key" => ":Id",
                            "value" =>$vInfo['id']);
	$params[] = array(  "key" => ":Type",
                            "value" =>$_type);
	$params[] = array(  "key" => ":Channelid",
                            "value" =>$vInfo['channelid']);
	$params[] = array(  "key" => ":Userid",
                            "value" =>$vInfo['userid']);
    } else {
        $query = "SELECT * FROM cms_videos WHERE id = (select max(id) FROM cms_videos WHERE id < :Id AND published=1 AND channelid = :Channelid'] AND userid = :Userid AND NOT EXISTS (SELECT video_id FROM cms_videos_catalogs WHERE video_id=id) ) ORDER BY pdate DESC LIMIT 0,1";
        $params[] = array(  "key" => ":Id",
                            "value" =>$vInfo['id']);
	$params[] = array(  "key" => ":Channelid",
                            "value" =>$vInfo['channelid']);
	$params[] = array(  "key" => ":Userid",
                            "value" =>$vInfo['userid']);
    }
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0)) {
        return false;
    } else {
        $row = $select->fetch();
        return $row;
    }
//  Changed by Anthony Malak 28-04-2015 to PDO database

}

/**
 * gets the channel info depending on search criteria. options include:<br/>
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>public</b>: wheather the channel is public or not. default 1<br/>
 * <b>owner_id</b>: the channel's owner's id. default null<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>name</b>: the channel's name default null<br/>
 * <b>url</b>: the channel's url default null<br/>
 * <b>id</b>: the channel's id default null<br/>
 * <b>strict_search</b>: search for the channel's name exactly default 1. 2 for start match expression<br/>
 * <b>channel_visible</b>: check if the channel is visible (1,true) or not (0, false). default doesnt matter (null).<br/>
 * <b>n_results</b>: returns the results or the number of results. default false
 * @param array $srch_options 
 * @return array | false an array of 'cms_channel' records or false if none found.
 */
function channelSearch($srch_options) {
    global $dbConn;
    $params = array();
    $default_opts = array(
        'limit' => 10,
        'page' => 0,
        'field' => 'channel_name',
        'owner_id' => null,
        'dont_show' => 0,
        'privacy_subchannel' => 0,
        'category' => null,
        'city' => null,
        'city_id' => null,
        'country' => null,
        'orderby' => 'id',
        'order' => 'a',
        'name' => null,
        'url' => null,
        'published' => 1,
        'categoriesfilter' => 0,
        'id' => null,
        'strict_search' => 1,
        'channel_visible' => null,
        'n_results' => false
    );
    $options = array_merge($default_opts, $srch_options);
    $where = '';
    if (!is_null($options['id'])) {
        if ($where != '') $where .= ' AND ';
        $where .= " C.id=:Id ";
	$params[] = array(  "key" => ":Id", "value" =>$options['id']);
    }
    if (!is_null($options['category'])) {
        if ($where != '') $where .= ' AND ';
        $where .= " C.category=:Category ";
	$params[] = array(  "key" => ":Category", "value" =>$options['category']);
    }
    if (!is_null($options['published'])) {
        if ($where != '') $where .= ' AND ';
        $where .= " C.published=:Published ";
	$params[] = array(  "key" => ":Published", "value" =>$options['published']);
    }
    if (!is_null($options['owner_id'])) {
        if ($where != '') $where .= ' AND ';
        $where .= " C.owner_id=:Owner_id ";
	$params[] = array(  "key" => ":Owner_id", "value" =>$options['owner_id']);
    }
    if (!is_null($options['city_id'])) {
        if ($where != '') $where .= ' AND ';
        $where .= " C.city_id=:City_id ";
	$params[] = array(  "key" => ":City_id", "value" =>$options['city_id']);
    }
    if (!is_null($options['city'])) {
        if ($where != '') $where .= ' AND ';
        $where .= " C.city=:City ";
	$params[] = array(  "key" => ":City", "value" =>$options['city']);
    }
    if (!is_null($options['country'])) {
        if ($where != '') $where .= ' AND ';
        $where .= " C.country=:Country ";
	$params[] = array(  "key" => ":Country", "value" =>$options['country']);
    }
    if (!is_null($options['url'])) {
        if ($where != '') $where .= ' AND ';
        $where .= " LOWER(C.channel_url)=LOWER(:Url) ";
	$params[] = array(  "key" => ":Url", "value" =>$options['url']);
    }
    if ($options["dont_show"] != 0) {
        if ($where != '') $where .= " AND ";
        $where .= " NOT find_in_set(cast(C.id as char), :Dont_show) ";
	$params[] = array(  "key" => ":Dont_show", "value" =>$options['dont_show']);
    }
    if (!is_null($options['name'])) {
        if ($where != '') $where .= ' AND ';
        if ($options['strict_search'] == 1) {
            $where .= " LOWER(C." . $options['field'] . " ) = LOWER(:Name1) ";           
            $params[] = array(  "key" => ":Name1", "value" =>$options['name']);
        } else if ($options['strict_search'] == 2) {
            $where .= " LOWER(C." . $options['field'] . ") LIKE LOWER(:Name2) ";
            $params[] = array(  "key" => ":Name2", "value" =>$options['name'].'%');
        } else {
            $where .= " LOWER(C." . $options['field'] . ") LIKE LOWER(:Name3) ";
            $params[] = array(  "key" => ":Name3", "value" =>'%'.$options['name'].'%');
        }
    }
    if (!is_null($options['channel_visible'])) {
        if ($where != '') $where .= ' AND ';
        $where .= " C.channel_visible=:Channel_visible ";
        $params[] = array(  "key" => ":Channel_visible", "value" =>$options['channel_visible']);
    }
    if ($where != '') {
        $where = "WHERE $where";
    }

    $orderby = $options['orderby'];
    $order='';
    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $orderby = 'C.'.$options['orderby'];
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }

    $nlimit = '';
    if (!is_null($options['limit'])) {
        $nlimit = intval($options['limit']);
        $skip = intval($options['page']) * $nlimit;
    }
    
    if ($options['n_results'] == false) {
        if($options['categoriesfilter']==1){
            $query = "SELECT A.*, count(A.id) AS cnt FROM cms_channel_category AS A INNER JOIN cms_channel AS C ON A.id=C.category AND A.published=1 $where GROUP BY A.id ORDER BY A.title ASC";
        }else if($options['privacy_subchannel']==2){
            $query = "SELECT C.* FROM cms_channel AS C INNER JOIN cms_channel_privacy AS P ON P.channelid=C.id AND P.privacy_beparentchannel=1 $where ORDER BY $orderby $order";
        }else if($options['privacy_subchannel']==1){
            $query = "SELECT C.* FROM cms_channel AS C INNER JOIN cms_channel_privacy AS P ON P.channelid=C.id AND P.privacy_besubchannel=1 $where ORDER BY $orderby $order";
        }else{
           $query = "SELECT C.* FROM cms_channel AS C $where ORDER BY $orderby $order";            
        }
        if (!is_null($options['limit'])) {
            $query .= " LIMIT :Skip, :Nlimit";
            $params[] = array(  "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
            $params[] = array(  "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
        }
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
        if (!$res || ($ret == 0)) {
            return false;
        } else {
            $ret_arr = $select->fetchAll(PDO::FETCH_ASSOC);
            return $ret_arr;
        }
    } else {
        if($options['privacy_subchannel']==2){
            $query = "SELECT COUNT(C.id) FROM cms_channel AS C INNER JOIN cms_channel_privacy AS P ON P.channelid=C.id AND P.privacy_beparentchannel=1 $where";
        }else if($options['privacy_subchannel']==1){
            $query = "SELECT COUNT(C.id) FROM cms_channel AS C INNER JOIN cms_channel_privacy AS P ON P.channelid=C.id AND P.privacy_besubchannel=1 $where";
        }else{
            $query = "SELECT COUNT(C.id) FROM cms_channel AS C $where";
        }
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$select->execute();
	$row = $select->fetch();
        return $row[0];
    }
}

/**
 * gets the category list info depending on search criteria.
 * @param string $term the search criteria default
 * @return array | false an array of 'cms_channel' records or false if none found.
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function channelCategorySearch($term) {
//
//    $query = "SELECT * FROM cms_channel WHERE channel_name LIKE '" . $term . "%' OR channel_name LIKE '%" . $term . "%' OR small_description LIKE '%" . $term . "%' ";
//
//    $ret = db_query($query);
//    if (!$ret || (db_num_rows($ret) == 0)) {
//        return false;
//    } else {
//        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
//        return $ret_arr;
//    }
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<end>

/**
 * search for channel. options include:<br/>
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>name</b>: the search criteria default null<br/>
 * <b>channel_name</b>: the column in cms_channel default 0<br/>
 * <b>small_description</b>: the column in cms_channel default 0<br/>
 * <b>category</b>: the channel category default null<br/>
 * @param array $srch_options 
 * @return array | false an array of 'cms_channel' records or false if none found.
 */
//function channelMainSearch($srch_options) {
//    $default_opts = array(
//        'limit' => 32,
//        'page' => 0,
//        'orderby' => 'id',
//        'order' => 'a',
//        'channel_name' => null,
//        'small_description' => null,
//        'category' => null,
//        'name' => null
//    );
//
//    $options = array_merge($default_opts, $srch_options);
//
//    $where = '';
//
//    if (!is_null($options['category'])) {
//        $where .= " category = " . $options['category'] . " AND (";
//    }
//
//    if (!is_null($options['name'])) {
//
//        if (!is_null($options['channel_name'])) {
//            $where .= " LOWER(channel_name) LIKE LOWER('" . $options['name'] . "%') ";
//        }
//        if (!is_null($options['channel_name'])) {
//            if ($where != '')
//                $where .= ' OR ';
//            $where .= " LOWER(channel_name) LIKE LOWER('%" . $options['name'] . "%') ";
//        }
//        if (!is_null($options['small_description'])) {
//            if ($where != '')
//                $where .= ' OR ';
//            $where .= " LOWER(small_description) LIKE LOWER('%" . $options['name'] . "%') ";
//        }
//    }
//
//    if (!is_null($options['category'])) {
//        $where .= ")";
//    }
//
//    if ($where != '') {
//        $where = "WHERE $where";
//    }
//
//    $orderby = $options['orderby'];
//    $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
//
//    $nlimit = intval($options['limit']);
//    $skip = intval($options['page']) * $nlimit;
//
//    $query = "SELECT * FROM cms_channel $where ORDER BY $orderby $order LIMIT $skip, $nlimit";
//
//    $ret = db_query($query);
//    if (!$ret || (db_num_rows($ret) == 0)) {
//        return false;
//    } else {
//        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
//        return $ret_arr;
//    }
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>

function channelSolrSearch($srch_options) {
    global $CONFIG;
    //ini_set("error_reporting",E_ALL);
    //ini_set("display_errors",1);
    $default_opts = array(
        'limit' => 32,
        'page' => 1,
        'orderby' => 'id',
        'order' => 'a',
        'channel_name' => null,
        'small_description' => null,
        'c' => null,
        'name' => null
    );

    $options = array_merge($default_opts, $srch_options);

    require($CONFIG ['server']['root'] . 'vendor/autoload.php');
    $ret_arr = $ct = array();
    global $CONFIG;
    $config = $CONFIG['solr_config'];
    $client = new Solarium\Client($config);
    $client->setAdapter('Solarium\Core\Client\Adapter\Http');
    $category_id = channelCategoryGetID(urldecode($options['c']));
    $query = $client->createSelect();

    $searchString = "type:c ";
    $qq = $options['t'];
    if ($qq <> '')
        $searchString .= " AND ( title_t1:'$qq' OR description_t1:'$qq' OR city_name_accent:'$qq' )";
    if (isset($options['co']) && $options['co'] <> '')
        $searchString .= " AND country:" . $options['co'];

    
    $edismax = $query->getEDisMax();
    $edismax->setQueryFields('title_t1^40 description_t1^30 city_name_accent^2');
    $query->setQuery($searchString);
    $facetSet = $query->getFacetSet();
    $facetSet->createFacetField('category')->setField('category_t');
    $facetSet->setMinCount(1);
    $resultset = $client->select($query);
    $facet = $resultset->getFacetSet()->getFacet('category');
    $ret_arr['cats']=array();
    foreach ($facet as $value => $count){
        $ret_arr['cats'][$value] = $count;
    }


    $client->setAdapter('Solarium\Core\Client\Adapter\Http');
    $query = $client->createSelect();
    if (isset($options['c']) && $options['c'] <> ''){
        $searchString .= " AND cat_id:" . $category_id;
    }
//    if (isset($options['c']) && $options['c'] <> ''){
//        $reg_filter = array('and', 'or', 'not', ':');
//        $pattern = '/\b(' . implode("|", $reg_filter) . ')\b/i';
//        $cat = preg_replace($pattern, ' ', $options['c']);
//        $searchString .= " +category_tt:'" . $cat . "'";
//    }
//switch ( $srch_options['orderby']) {
//        case 'Date': $query->addSort('last_modified', Solarium_Query_Select::SORT_ASC); break;
//        case 'Title': $query->addSort('title_t', Solarium_Query_Select::SORT_ASC); break;
//}
    
    $query->setStart(($options['page'] - 1) * $options['limit']);
    $query->setRows($options['limit']);
    $edismax = $query->getEDisMax();
    $edismax->setQueryFields('title_t1^40 description_t1^30 city_name_accent^2');
    $query->setQuery($searchString);
    $resultset = $client->select($query);
    $userVideos['numFound'] = $resultset->getNumFound();
    $ret_arr['numFound'] = $userVideos['numFound'];
    $ret_arr['totalPages'] = ceil($userVideos['numFound'] / $srch_options['limit']);
//debug($resultset);
    $k = 0;
    $ret_arr['channel']=array();
    foreach ($resultset as $document) {
        $ret_arr['channel'][$k]['id'] = $document->id;
        $ret_arr['channel'][$k]['title'] = $document->title_t1;
        $ret_arr['channel'][$k]['url'] = $document->url;
        $ret_arr['channel'][$k]['like_value'] = $document->up_votes;
        $ret_arr['channel'][$k]['nb_comments'] = $document->nb_comments;
        $ret_arr['channel'][$k]['description'] = $document->description_t1;
        $ret_arr['channel'][$k]['cat_id'] = $document->cat_id;
        $ret_arr['channel'][$k]['pdate'] = $document->pdate;
        $ret_arr['channel'][$k]['header'] = $document->ch_header;
        $ret_arr['channel'][$k]['city_name_accent'] = $document->city_name_accent;
        $k++;
    }
    return $ret_arr;
}

/**
 * gets the channel category id given its name
 * @param string $cat_name
 * @return initeger the category id  
 */
function channelCategoryGetID($cat_name) {
    global $dbConn;
    $params = array();  
    $cat_name = str_replace("-", " ", $cat_name);
    $query = "Select c.id from cms_channel_category as c left join ml_channel_category as ml on ml.entity_id = c.id  where (c.title=:Cat_name or ml.title=:Cat_name) AND c.published = 1";    
    $params[] = array(  "key" => ":Cat_name", "value" =>$cat_name);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    if (!$res || ($ret == 0)) {
        return false;
    } else {
        $row = $select->fetch();
        return $row[0];
    }
}

/**
 * gets a list of tubers connected to a channel
 * @param integer $channel_id the channel record
 * @return array the connected tuber to the channel
 */
function getConnectedtubers($channel_id, $is_owner=0) {
//  Changed by Anthony Malak 28-04-2015 to PDO database

    global $dbConn;
    $params = array();  
    $is_visible = $is_owner ? "" : "AND is_visible = 1";
//    $query = "SELECT userid FROM cms_channel_connections where channelid = '$channel_id' AND published=1 $is_visible";
//    $ret = db_query($query);
//    if (!$ret || (db_num_rows($ret) == 0)) {
//        return false;
//    } else {
//        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row[0];
//        }
//        return $ret_arr;
//    }
    $query = "SELECT userid FROM cms_channel_connections where channelid = :Channel_id AND published=1 $is_visible";
    $params[] = array(  "key" => ":Channel_id", "value" =>$channel_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    
    if (!$res || ($ret == 0)) {
        return false;
    } else {
        $ret_arr = array();
	$row = $select->fetchAll();
        foreach($row as $row_item){
            $ret_arr[] = $row_item[0];
        }
        return $ret_arr;
    }
//  Changed by Anthony Malak 28-04-2015 to PDO database

}
/**
 * gets a list of channels connected to a tuber
 * @param integer $user_id the user id
 * @return array tuber connected channels
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  29/04/2015
//<start>
//function getTuberConnectedChannels($user_id) {
//
//    $query = "SELECT channelid FROM cms_channel_connections where userid = '$user_id' AND published=1";
//    $ret = db_query($query);
//    if (!$ret || (db_num_rows($ret) == 0)) {
//        return false;
//    } else {
//        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row[0];
//        }
//        return $ret_arr;
//    }
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<end>
/**
 * gets a list of channels related to tuber
 * @param integer $owner_id the user id
 * @return array the list of channels
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<start>
//function getChannelsRelatedOwner($owner_id) {
//
//    $query = "SELECT id FROM cms_channel where owner_id = '$owner_id' AND published=1";
//    $ret = db_query($query);
//    if (!$ret || (db_num_rows($ret) == 0)) {
//        return false;
//    } else {
//        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row[0];
//        }
//        return $ret_arr;
//    }
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  29/04/2015
//<end>

/**
 * check if tubers connected to a channel
 * @param integer $channel_id the channel record
 * @param integer $user_id the tuber id
 * @return true or false
 */
function checkChannelConnectedTuber($channel_id, $user_id) {


    global $dbConn;
    $params = array(); 

//    $query = "SELECT userid FROM cms_channel_connections where channelid = '$channel_id' AND userid = '$user_id' AND published=1";
//    $ret = db_query($query);
//    if (!$ret || (db_num_rows($ret) == 0)) {
//        return false;
//    } else {
//        return true;
//    }
    $query = "SELECT userid FROM cms_channel_connections where channelid = :Channel_id AND userid = :User_id AND published=1";
    $params[] = array(  "key" => ":Channel_id",
                        "value" =>$channel_id);
    $params[] = array(  "key" => ":User_id",
                        "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0)) {
        return false;
    } else {
        return true;
    }


}

/**
 * gets a count tubers connected to a channel
 * @param integer $channel_id the channel record
 * @return number of connected tuber to the channel
 */
function countConnectedtubers($channel_id) {


    global $dbConn;
    $params = array();
//    $query = "SELECT userid FROM cms_channel_connections where channelid = '$channel_id' AND published=1";
//    $ret = db_query($query);
//    if (!$ret || (db_num_rows($ret) == 0)) {
//        return 0;
//    } else {
//        return db_num_rows($ret);
//    }
    $query = "SELECT userid FROM cms_channel_connections where channelid = :Channel_id AND published=1";
    $params[] = array(  "key" => ":Channel_id",
                        "value" =>$channel_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0)) {
        return 0;
    } else {
        
        return $ret;
    }


}

/**
 * Stops a channel from receiving notifications for a specific tuber.
 * @param integer $channel_id the channel id.
 * @param integer $user_id the user id.
 * @return boolean true on success, false on error.
 */
function stopNotifications($channel_id, $user_id) {
    global $dbConn;
    $params2 = array();
    $params = array();
    $query = "SELECT id FROM cms_channel_connections WHERE (channelid = :Channel_id AND userid = :User_id AND published IN (1, -5) ) ORDER BY create_ts DESC LIMIT 1";
    $params2[] = array(  "key" => ":Channel_id", "value" =>$channel_id);
    $params2[] = array(  "key" => ":User_id", "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params2);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret > 0) {
        $row = $select->fetch();
        $query = "UPDATE cms_channel_connections SET notify = 0 WHERE id = :Id ";
        $params[] = array(  "key" => ":Id", "value" =>$row['id']);
    }
    // If the user is not a connection, add him as published -5 connection and turn the notifications off.
    else {
        $query = "INSERT INTO cms_channel_connections
			 			(channelid, userid, create_ts, notify, published)
			 			VALUES(:Channel_id , :User_id , NOW(), 0, '-5')";
        $params[] = array(  "key" => ":Channel_id", "value" =>$channel_id);
        $params[] = array(  "key" => ":User_id", "value" =>$user_id);
    }

    $update_insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($update_insert,$params);
    $res  = $update_insert->execute();
    if ($res)
        return true;
    else
        return false;
}

/**
 * Allows a channel to receive notifications for a specific tuber.
 * @param integer $channel_id the channel id.
 * @param integer $user_id the user id.
 * @return boolean true on success, false on error.
 */
function getNotifications($channel_id, $user_id) {
    global $dbConn;
    $params = array();
    $query = "UPDATE cms_channel_connections
              SET notify = 1
              WHERE channelid = :Channel_id AND userid = :User_id AND notify = 0 AND published IN (1, -5) ";

    $params[] = array(  "key" => ":Channel_id", "value" =>$channel_id);
    $params[] = array(  "key" => ":User_id", "value" =>$user_id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $ret    = $update->execute();
    if ($ret)
        return true;
    else
        return false;
}

/**
 * Gets a list of user ids the channel has blocked from notifications (from which the channel is NOT to get notifications).
 * @param integer $channel_id the channel id.
 * @return array of user ids on success, false on error.
 */
function getChannelBlockedConnections($channel_id) {


    global $dbConn;
    $params = array();  
//    $query = "SELECT userid FROM cms_channel_connections
//					WHERE (channelid = " . $channel_id . " AND notify = 0 AND published IN (1, -5) )";
//
//    $ret = db_query($query);
//    if ($ret) {
//        $users_arr = array();
//        while ($row = db_fetch_array($ret))
//            array_push($users_arr, $row['userid']);
//        return $users_arr;
//    } else
//        return false;
    $query = "SELECT userid FROM cms_channel_connections
					WHERE (channelid = :Channel_id AND notify = 0 AND published IN (1, -5) )";
    $params[] = array(  "key" => ":Channel_id",
                        "value" =>$channel_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res) {
        $users_arr = array();
        $row = $select->fetchAll(PDO::FETCH_ASSOC);
        foreach($row as $row_item){
            array_push($users_arr, $row_item['userid']);
        }
        return $users_arr;
    } else
        return false;


}

/**
 * Gets the channel the has the highest number of connections.
 * @return channel record , false on error.
 */
function getChannelHighestConnections($limit = 1) {


    global $dbConn;
    $params = array();
//    $query = "SELECT count( channelid ) AS cnt , channelid , CH.*
//					FROM cms_channel_connections AS CO INNER JOIN cms_channel AS CH ON CO.channelid = CH.id
//					WHERE CO.published =1 AND CH.published =1
//					GROUP BY channelid
//					ORDER BY cnt DESC
//					LIMIT $limit";
//
//    $ret = db_query($query);
//
//    if ($ret && db_num_rows($ret) > 0) {
//        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
//        return $ret_arr;
//    } else {
//        return array();
//    }
    $query = "SELECT count( channelid ) AS cnt , channelid , CH.*
					FROM cms_channel_connections AS CO INNER JOIN cms_channel AS CH ON CO.channelid = CH.id
					WHERE CO.published =1 AND CH.published =1
					GROUP BY channelid
					ORDER BY cnt DESC
					LIMIT :Limit";

    $params[] = array(  "key" => ":Limit",
                        "value" =>$limit,
                        "type" =>"::PARAM_INT");
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret > 0) {
        $ret_arr = $select->fetchAll();
        return $ret_arr;
    } else {
        return array();
    }


}

/**
 * Gets the channel the has the highest number of connections by country.
 * @return channel record , false on error.
 */
function getChannelHighestConnectionsByCountry($country, $limit = 1) {


	global $dbConn;
	$params = array();  
    if ($country != '') {
//        $query = "SELECT count( channelid ) AS cnt , channelid , CH.*
//                           FROM cms_channel_connections AS CO INNER JOIN cms_channel AS CH ON CO.channelid = CH.id
//                           WHERE CO.published =1 AND CH.published =1 AND CH.country ='$country'
//                           GROUP BY channelid
//                           ORDER BY cnt DESC
//                           LIMIT $limit";
//
//        $ret = db_query($query);
//        if ($ret && db_num_rows($ret) > 0) {
//            $ret_arr = array();
//            while ($row = db_fetch_array($ret)) {
//                $ret_arr[] = $row;
//            }
//            return $ret_arr;
        $query = "SELECT count( channelid ) AS cnt , channelid , CH.*
                           FROM cms_channel_connections AS CO INNER JOIN cms_channel AS CH ON CO.channelid = CH.id
                           WHERE CO.published =1 AND CH.published =1 AND CH.country =:Country
                           GROUP BY channelid
                           ORDER BY cnt DESC
                           LIMIT :Limit";

        $params[] = array(  "key" => ":Country",
                            "value" =>$country);
        $params[] = array(  "key" => ":Limit",
                            "value" =>$limit,
                            "type" =>"::PARAM_INT");
        $select = $dbConn->prepare($query);
        PDO_BIND_PARAM($select,$params);
        $res    = $select->execute();

        $ret    = $select->rowCount();
        if ($res && $ret > 0) {
            $ret_arr = $select->fetchAll();
            return $ret_arr;
        } else {
            return getChannelHighestConnections(28);
        }
    } else {
        return getChannelHighestConnections(28);
    }


}

/**
 * gets a list of tubers connected to a channel
 * @param integer $user_id the id of the user connecting to the channel
 * @param integer $channel_id the channel record id
 * @return array the connected tuber to the channel
 */
function connectTochannel($user_id, $channel_id) {
    global $dbConn;
    $params  = array();  
    $params2 = array();
    $query = "SELECT id,published FROM cms_channel_connections WHERE channelid = :Channel_id AND userid = :User_id AND published <> -2 LIMIT 1";
    $params[] = array(  "key" => ":Channel_id", "value" =>$channel_id);
    $params[] = array(  "key" => ":User_id", "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret > 0) {
        $row = $select->fetch();
        $query = "UPDATE `cms_channel_connections` SET published = 1, notify = 1, create_ts = NOW() WHERE id = :Id";
	$params2[] = array(  "key" => ":Id",
                            "value" =>$row['id']);
	$update = $dbConn->prepare($query);
	PDO_BIND_PARAM($update,$params2);
	$res    = $update->execute();
        $connection_id = 0;
        if ( $row['published'] == -5) {
            $connection_id = $row['id'];
        }
    }
    // Row does not exist, create it.
    else {
        $query = "INSERT INTO `cms_channel_connections` (`id`,`channelid`,`userid`,`create_ts`) VALUES (NULL , :Channel_id, :User_id,NOW())";
        $params2[] = array(  "key" => ":Channel_id",
                            "value" =>$channel_id);
        $params2[] = array(  "key" => ":User_id",
                            "value" =>$user_id);
        $select = $dbConn->prepare($query);
        PDO_BIND_PARAM($select,$params2);
        $res    = $select->execute();
        $connection_id = $dbConn->lastInsertId();
    }
    if ($res) {
        if ($connection_id != 0) {
            $sponsor_connect = SOCIAL_ACTION_CONNECT;
            newsfeedAdd($user_id, $connection_id, $sponsor_connect, $channel_id, SOCIAL_ENTITY_CHANNEL, USER_PRIVACY_PUBLIC, $channel_id);
            newsfeedAdd($user_id, $connection_id, $sponsor_connect, $channel_id, SOCIAL_ENTITY_CHANNEL, USER_PRIVACY_PRIVATE, null);
            $channel_info = channelGetInfo($channel_id);
            $channel_owner = $channel_info['owner_id'];
            addPushNotification(SOCIAL_ACTION_CONNECT, $channel_owner, $user_id, 0, $channel_id);
        }
        return true;
    } else {
        return false;
    }
}

/**
 * gets the channel connections info depending on search criteria. options include:<br/>
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>userid</b>: the user id connected to the channel . default null<br/>
 * <b>channelid</b>: the channel id  . default null<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>id</b>: the channel connections id default null<br/>
 * <b>is_visible</b>: check if the channel connection is visible (1,true) or not (0, false). default doesnt matter (null).<br/>
 * <b>n_results</b>: returns the results or the number of results. default false
 * @param array $srch_options 
 * @return array | false an array of 'cms_channel_connections' records or false if none found.
 */

function channelConnectionsSearch($srch_options) {


    global $dbConn;
    $params = array(); 
    $default_opts = array(
        'limit' => 10,
        'page' => 0,
        'orderby' => 'CO.id',
        'order' => 'a',
        'userid' => null,
        'channelid' => null,
        'channel_visible' => null,
        'from_ts' => null,
        'to_ts' => null,
        'id' => null,
        'distinct_user' => 0,
        'escape_user' => null,
        'is_visible' => null,
        'n_results' => false
    );

    $options = array_merge($default_opts, $srch_options);

    $where = '';

    if (!is_null($options['id'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " CO.id='{$options['id']}' ";
        $where .= " CO.id=:Id ";
	$params[] = array(  "key" => ":Id",
                            "value" =>$options['id']);
    }
    if (!is_null($options['userid'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " CO.userid='{$options['userid']}' ";
        $where .= " CO.userid=:Userid ";
	$params[] = array(  "key" => ":Userid",
                            "value" =>$options['userid']);
    }
    if (!is_null($options['channelid'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " CO.channelid={$options['channelid']} ";
        $where .= " CO.channelid=:Channelid ";
	$params[] = array(  "key" => ":Channelid",
                            "value" =>$options['channelid']);
    }
    if ( !is_null($options['channel_visible']) ) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " CO.is_visible='{$options['is_visible']}' ";
        $where .= " CO.is_visible=:Is_visible ";
	$params[] = array(  "key" => ":Is_visible",
                            "value" =>$options['is_visible']);
    }
    if (!is_null($options['from_ts'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " DATE(CO.create_ts) >= '{$options['from_ts']}' ";
        $where .= " DATE(CO.create_ts) >= :From_ts ";
	$params[] = array(  "key" => ":From_ts",
                            "value" =>$options['from_ts']);
    }
    if (!is_null($options['to_ts'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " DATE(CO.create_ts) <= '{$options['to_ts']}' ";
        $where .= " DATE(CO.create_ts) <= :To_ts ";
	$params[] = array(  "key" => ":To_ts",
                            "value" =>$options['to_ts']);
    }
    if(!is_null($options['escape_user'])){
        if( $where != '') $where .= " AND ";
//        $where .= " CO.userid NOT IN({$options['escape_user']}) ";
	$where .= " NOT find_in_set(cast(CO.userid as char), :Escape_user) ";
	$params[] = array(  "key" => ":Escape_user", "value" =>$options['escape_user']);
    }
    if ($where != '') {
        $where = "WHERE $where";
    }

    $orderby = $options['orderby'];
    $order='';
    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }

    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit;

    if ($options['n_results'] == false) {
        if( $options['distinct_user']==1 ){
//            $query = "SELECT CO.id AS co_id , CO.is_visible AS co_visible , CO.channelid AS co_channelid , CO.userid AS co_userid , CO.create_ts AS co_create_ts , CO.notify AS co_notify , C.* FROM cms_channel_connections AS CO INNER JOIN cms_channel AS C ON CO.channelid=C.id $where AND CO.published=1 AND C.published=1 GROUP BY CO.userid ORDER BY $orderby $order LIMIT $skip, $nlimit";
            $query = "SELECT CO.id AS co_id , CO.is_visible AS co_visible , CO.channelid AS co_channelid , CO.userid AS co_userid , CO.create_ts AS co_create_ts , CO.notify AS co_notify , C.* FROM cms_channel_connections AS CO INNER JOIN cms_channel AS C ON CO.channelid=C.id $where AND CO.published=1 AND C.published=1 GROUP BY CO.userid ORDER BY $orderby $order LIMIT :Skip, :Nlimit";
            $params[] = array(  "key" => ":Skip",
                                "value" =>$skip,
                                "type" =>"::PARAM_INT");
            $params[] = array(  "key" => ":Nlimit",
                                "value" =>$nlimit,
                                "type" =>"::PARAM_INT");
        }else{
            $query = "SELECT CO.id AS co_id , CO.is_visible AS co_visible , CO.channelid AS co_channelid , CO.userid AS co_userid , CO.create_ts AS co_create_ts , CO.notify AS co_notify , C.* FROM cms_channel_connections AS CO INNER JOIN cms_channel AS C ON CO.channelid=C.id $where AND CO.published=1 AND C.published=1 ORDER BY $orderby $order LIMIT :Skip2, :Nlimit2";
            $params[] = array(  "key" => ":Skip2",
                                "value" =>$skip,
                                "type" =>"::PARAM_INT");
            $params[] = array(  "key" => ":Nlimit2",
                                "value" =>$nlimit,
                                "type" =>"::PARAM_INT");
        }
                
//        $ret = db_query($query);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
//        if (!$ret || (db_num_rows($ret) == 0)) {
        if (!$res || ($ret == 0)) {
            return false;
        } else {
            $ret_arr = $select->fetchAll();
            return $ret_arr;
        }
    } else {        
        if( $options['distinct_user']==1 ){
            $query = "SELECT COUNT( DISTINCT CO.userid ) FROM cms_channel_connections AS CO INNER JOIN cms_channel AS C ON CO.channelid=C.id $where AND CO.published=1 AND C.published=1";
        }else{
            $query = "SELECT COUNT(CO.id) FROM cms_channel_connections AS CO INNER JOIN cms_channel AS C ON CO.channelid=C.id $where AND CO.published=1 AND C.published=1";
        }
//        $ret = db_query($query);
//        $row = db_fetch_row($ret);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
	$row = $select->fetch();

        return $row[0];
    }


}

/**
 * checks if a user is connected to a channel
 * @param integer $user_id the id of the user to check
 * @param integer $channel_id the channel record id
 * @return boolean true|false if connected or not
 */
function connectedToChannel($user_id, $channel_id) {


    global $dbConn;
    $params = array();  
//    $query = "SELECT * FROM cms_channel_connections where userid='$user_id' AND channelid='$channel_id' AND published=1";
//    $res = db_query($query);
//    if (!$res || (db_num_rows($res) == 0))
//        return false;
//    else
//        return true;
    $query = "SELECT * FROM cms_channel_connections where userid=:User_id AND channelid=:Channel_id AND published=1";
    $params[] = array(  "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array(  "key" => ":Channel_id",
                        "value" =>$channel_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0))
        return false;
    else
        return true;


}

/**
 * returns the CONNECT button given the type of user that is logged in
 * @param array $in_options options for printing the connect button. options include: <br/>
 * <b>text1</b> string. the 'connect' button text<br/>
 * <b>text2</b> string. the 'sponsor' button text<br/>
 * <b>text3</b> string. the 'disconnect' button text<br/>
 * <b>text4</b> string. the 'unsponsor' button text<br/>
 * <b>class</b> string. the extra class to append to the button. default null.<br/>
 * <b>channel_id</b> integer. required. the channel to connect to.<br/>
 * <b>main_button</b> boolean. true => the button is on the channel page. false => the button is on some other page<br/>
 * @return string the CONNECT button html or false if error.
 */
function ReturnConnectButton($in_options) {

    $default_options = array(
        'text1' => _('connect'),
        'text2' => _('sponsor'),
        'text3' => _('disconnect'),
        'text4' => _('unsponsor'),
        'channel_id' => null,
        'class' => null,
        'main_button' => false,
    );

    $options = array_merge($default_options, $in_options);

    if (is_null($options['channel_id']))
        return false;

    $channel_id = $options['channel_id'];

    $class = is_null($options['class']) ? '' : $options['class'];

    $cinfo = channelFromID($channel_id);

    $current_channel = userCurrentChannelGet();

    //if the user is a channel user or the user has selected a channel
    $channel_setting = ( userIsChannel() || ($current_channel != false) );

    //user or channel id
    $user_channel_id = $channel_setting ? $current_channel['id'] : userGetID();

    //is the (user or channel) connected to the channel
    $connected = connectedToChannel($user_channel_id, $channel_id, $channel_setting);

    if (!userIsLogged()) {
        $case = 0;
        $text = $options['text1'];
    } else if ($channel_setting) {

        if ($connected) {
            $case = 1;
            $text = $options['text4'];
        } else {
            $case = 2;
            $text = $options['text2'];
        }
    } else {
        if ($connected) {
            $case = 3;
            $text = $options['text3'];
        } else {
            $case = 4;
            $text = $options['text1'];
        }
    }

    //if its not the main page and the channel user is connected to the channel then dont display link
    if (!$options['main_button'] && $channel_setting && $connected)
        return '';

    $channel_url = !$options['main_button'] ? $cinfo['channel_url'] : '';
    return sprintf('<a class="connectBTN %s" data-url="%s" data-case="%s" data-cid="%s">%s</a>', $class, $channel_url, $case, $channel_id, $text);
}

/**
 * disconnect from channel
 * @param integer $user_id user wanting to unscubscribe
 * @param integer $channel_id the channel from which the user is unsubscribing
 * @return boolean true|false depending on the success of the operation
 */
function disconnectedTubers($user_id, $channel_id, $create_ts = '') {


    global $dbConn;
    $params  = array();  
    $params2 = array();
    if (deleteMode() == TT_DEL_MODE_PURGE) {
//        $query = "DELETE FROM cms_channel_connections where userid='$user_id' AND channelid='$channel_id' AND published=1";
        $query = "DELETE FROM cms_channel_connections where userid=:User_id AND channelid=:Channel_id AND published=1";
	$params[] = array(  "key" => ":User_id",
                            "value" =>$user_id);
	$params[] = array(  "key" => ":Channel_id",
                            "value" =>$channel_id);
    } else if (deleteMode() == TT_DEL_MODE_FLAG) {
        if ($create_ts != '') {
//            $query = "UPDATE cms_channel_connections SET published=" . TT_DEL_MODE_FLAG . ", create_ts='" . $create_ts . "' WHERE userid='$user_id' AND channelid='$channel_id' AND published=1";
            $query = "UPDATE cms_channel_connections SET published=" . TT_DEL_MODE_FLAG . ", create_ts=:Create_ts WHERE userid=:User_id AND channelid=:Channel_id AND published=1";
            $params[] = array(  "key" => ":Create_ts",
                                "value" =>$create_ts);
            $params[] = array(  "key" => ":User_id",
                                "value" =>$user_id);
            $params[] = array(  "key" => ":Channel_id",
                                "value" =>$channel_id);
        } else {
//            $query = "UPDATE cms_channel_connections SET published=" . TT_DEL_MODE_FLAG . " WHERE userid='$user_id' AND channelid='$channel_id' AND published=1";
            $query = "UPDATE cms_channel_connections SET published=" . TT_DEL_MODE_FLAG . " WHERE userid=:User_id AND channelid=:Channel_id AND published=1";
            $params[] = array(  "key" => ":User_id",
                                "value" =>$user_id);
            $params[] = array(  "key" => ":Channel_id",
                                "value" =>$channel_id);
        }
    }

//    $query_feed = "SELECT id FROM cms_channel_connections where userid='$user_id' AND channelid='$channel_id' AND published=1";
//    $ret_feed = db_query($query_feed);
    $query_feed = "SELECT id FROM cms_channel_connections where userid=:User_id AND channelid=:Channel_id AND published=1";
    $params2[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $params2[] = array( "key" => ":Channel_id",
                        "value" =>$channel_id);
    $select = $dbConn->prepare($query_feed);
    PDO_BIND_PARAM($select,$params2);
    $ret_feed    = $select->execute();

    $ret    = $select->rowCount();
    if ($ret_feed && ($ret > 0)) {
	$row = $select->fetchAll();
        foreach($row as $row_item){
            newsfeedDeleteJoinByAction($row_item['id'], SOCIAL_ACTION_CONNECT, SOCIAL_ENTITY_CHANNEL);
        }
    }
    
    
    $delete_update = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete_update,$params);
    $res    = $delete_update->execute();
    return $res;


}

/**
 * gets a list of the categories
 * @param array $srch_options the search options. options include<br/>
 * <b>orderby</b>: the column by which the results will be sorted. title 'title'<br/>
 * <b>order</b>: the order (a)scending or (d)escending
 * @return array the category rows as a hashof id => title
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  29/04/2015
//<start>
//	function channelcategoryGetHash( $srch_options = array() ){
//		
//		$default_opts = array(
//			'orderby' => 'title',
//			'order' => 'a',
//		);
//		
//		$options = array_merge($default_opts, $srch_options);
//		
//		$order_by = $options['orderby'];
//		$order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
//		
//		$VideoCategryListSQL = "Select * from cms_channel_category where published = 1 order by $order_by $order";
//		$VideoCategoryListResult = db_query($VideoCategryListSQL);
//		$ret = array();
//		while ($VideoCategoryListRes = db_fetch_array($VideoCategoryListResult)){
//			$ret[$VideoCategoryListRes['id']] = htmlEntityDecode($VideoCategoryListRes['title']);	
//		}
//		return $ret;
//		
//	}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  29/04/2015
//<end>
function channelcategoryGetHash($srch_options = array()) {


    global $dbConn;
    $params = array();  

    $default_opts = array(
        'orderby' => 'title',
        'order' => 'a',
    );
    $lang_code = LanguageGet();
    $languageSel = '';
    $languageJoin = '';
    $languageAnd = '';
    $options = array_merge($default_opts, $srch_options);

    $order_by = 'c.' . $options['orderby'];
    $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    if ($lang_code != 'en') {
        $languageSel = ',ml.title as ml_title';
        $languageJoin = ' INNER JOIN ml_channel_category ml on c.id = ml.entity_id ';
//        $languageAnd = " and ml.lang_code='$lang_code'";
        $languageAnd = " and ml.lang_code=:Lang_code";
	$params[] = array(  "key" => ":Lang_code",
                            "value" =>$lang_code);
    }
    $VideoCategryListSQL = "Select c.*$languageSel from cms_channel_category c $languageJoin where c.published = 1$languageAnd order by $order_by $order";
//    $VideoCategoryListResult = db_query($VideoCategryListSQL);
    $select = $dbConn->prepare($VideoCategryListSQL);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret2    = $select->rowCount();
    $ret = array();
    $VideoCategoryListRes = $select->fetchAll(PDO::FETCH_ASSOC);
    foreach($VideoCategoryListRes as $row_item){
//    while ($VideoCategoryListRes = db_fetch_array($VideoCategoryListResult)) {
        if ($lang_code == 'en') {
            $ret[$row_item['id']] = htmlEntityDecode($row_item['title']);
        } else {
            $ret[$row_item['id']] = htmlEntityDecode($row_item['ml_title']);
        }
    }
    return $ret;


}

/**
 * gets a list of the categories
 * @param array $arrayID. an array of channel category idss
 * @return array the category rows as a hashof ids
 */
function channelcategoryGetHashFromIDS($arrayID) {


    global $dbConn;
    $params = array();

    $where = '';
    $lang_code = LanguageGet();
    $languageSel = '';
    $languageJoin = '';
    $languageAnd = '';
     if ($lang_code != 'en') {
        $languageSel = ',ml.title as ml_title';
        $languageJoin = ' INNER JOIN ml_channel_category ml on c.id = ml.entity_id ';
//        $languageAnd = " and ml.lang_code='$lang_code'";
        $languageAnd = " and ml.lang_code=:Lang_code";
	$params[] = array(  "key" => ":Lang_code",
                            "value" =>$lang_code);
    }
    foreach ($arrayID as $ID) {
        $where .= " c.id = " . $ID . " OR";
    }
    $where = substr($where, 0, -3);

    $VideoCategryListSQL = "Select c.*$languageSel from cms_channel_category as c$languageJoin where ($where) AND published = 1$languageAnd order by id ASC";
//    debug($VideoCategryListSQL);
//    $VideoCategoryListResult = db_query($VideoCategryListSQL);
    $select = $dbConn->prepare($VideoCategryListSQL);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    $ret = array();
    $ret[0] = 'All';
    $VideoCategoryListRes = $select->fetchAll(PDO::FETCH_ASSOC);
    foreach($VideoCategoryListRes as $row_item){
//    while ($VideoCategoryListRes = db_fetch_array($VideoCategoryListResult)) {
        if ($lang_code == 'en') {
            $ret[$row_item['id']] = htmlEntityDecode($row_item['title']);
        } else {
            $ret[$row_item['id']] = htmlEntityDecode($row_item['ml_title']);
        }
    }
    return $ret;


}

/**
 * returns the link channel background
 * @param array $channelInfo the cms_channel record
 * @return string
 */
function photoReturnchannelBG($channelInfo) {
    return ReturnLink('media/channel/' . $channelInfo['id'] . '/thumb/' . $channelInfo['bg']);
}

/**
 * returns the link to the channel header
 * @param array $channelInfo the cms_channel record
 * @return string
 */
function photoReturnchannelHeader($channelInfo) {
    if ($channelInfo['header'] == '') {
        return ReturnLink('/media/images/channel/coverphoto.jpg');
    } else {
        return ReturnLink('media/channel/' . $channelInfo['id'] . '/thumb/' . $channelInfo['header']);
    }
}
function photoReturnchannelHeaderBig($channelInfo) {
    if ($channelInfo['header'] == '') {
        return '';
    } else {
        return ReturnLink('media/channel/' . $channelInfo['id'] . '/' . $channelInfo['header']);
    }
}

/**
 * returns the link to the channel logo
 * @param array $channelInfo the cms_channel record
 * @return string
 */
function photoReturnchannelLogo($channelInfo) {
    if ($channelInfo['logo'] == '') {
        return ReturnLink('media/tubers/tuber.jpg');
    } else {
        return ReturnLink('media/channel/' . $channelInfo['id'] . '/thumb/' . $channelInfo['logo']);
    }
}
function photoReturnchannelLogoBig($channelInfo) {
    if ($channelInfo['logo'] == '') {
        return '';
    } else {
        return ReturnLink('media/channel/' . $channelInfo['id'] . '/' . $channelInfo['logo']);
    }
}

/**
 * returns a channel event image link
 * @param array $eventInfo the cms_channel_event record
 * @return string
 */
function photoReturneventImage($eventInfo,$pathlk="") {
    if ($eventInfo['photo'] == '') {
        return ReturnLink('media/images/channel/eventthemephoto.jpg');
    } else {
        return $pathlk.ReturnLink('media/channel/' . $eventInfo['channelid'] . '/event/thumb/' . $eventInfo['photo']);
    }
}

/**
 * returns the relative path to a channels post directory
 * @param array $vinfo the cms_social_posts record
 * @return string
 */
function relativevideoGetPostPath($vinfo) {
    if (intval($vinfo['channel_id']) == 0 && intval($vinfo['from_id']) != 0) {
        global $CONFIG;
        $videoPath = $CONFIG['video']['uploadPath'];
        $rpath = $vinfo['relativepath'];
        return $videoPath . 'posts/' . $rpath;
    } else {
        return 'media/channel/' . $vinfo['channel_id'] . '/posts/';
    }
}

function relativevideoReturnPostPath($vinfo) {
    if (intval($vinfo['channel_id']) == 0 && intval($vinfo['from_id']) != 0) {
        global $CONFIG;
        $videoPath = $CONFIG['video']['uploadPath'];
        $rpath = $vinfo['relativepath'];
        return ReturnLink($videoPath . 'posts/' . $rpath);
    } else {
        return ReturnLink('media/channel/' . $vinfo['channel_id'] . '/posts/');
    }
}

function getPostThumbPath($vinfo) {
    $repath ="";
    if ($vinfo['media_file'] != "") {
        if ($vinfo['is_video'] == 0) {
            $repath = relativevideoReturnPostPath($vinfo);
            $repath .='/thumb/' . $vinfo['media_file'];
        }else{
            $cod1 = explode('.', $vinfo['media_file']);
            $cod2 = explode('_', $cod1[0]);
            $videoCode = $cod2[1];
            $repath = relativevideoGetPostPath($vinfo);
            $videoCode = 'small_postThumb' . $videoCode;

            $picthumb_img = getVideoThumbnail_Posts($videoCode, $repath, 0);
            if ($picthumb_img == '') {
                $picthumb_img = getVideoThumbnail_Posts($videoCode, '../' . $repath, 0);
                $picthumb_array = explode('../', $picthumb_img);
                $picthumb_img = $picthumb_array[1];
            }

            $repath = ReturnLink($picthumb_img);
        }
    }else if (intval($vinfo['post_type']) == 2) {
        $repath = relativevideoReturnPostPath($vinfo);
        $repath .='/thumb/' . $vinfo['post_text'];
    } else if (intval($vinfo['post_type']) == 3) {
        $cod1 = explode('.', $vinfo['post_text']);
        $cod2 = explode('_', $cod1[0]);
        $videoCode = $cod2[1];
        $repath = relativevideoGetPostPath($vinfo);
        $videoCode = 'small_postThumb' . $videoCode;

        $picthumb_img = getVideoThumbnail_Posts($videoCode, $repath, 0);
        if ($picthumb_img == '') {
            $picthumb_img = getVideoThumbnail_Posts($videoCode, '../' . $repath, 0);
            $picthumb_array = explode('../', $picthumb_img);
            $picthumb_img = $picthumb_array[1];
        }
        $repath = ReturnLink($picthumb_img);
    }
    return $repath;
}

//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  29/04/2015
//<start>
//function getOriginalPostThumbPath($vinfo) {
//    if (intval($vinfo['post_type']) == 2) {
//        $repath = relativevideoReturnPostPath($vinfo);
//        $repath .='/' . $vinfo['post_text'];
//        return $repath;
//    }
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  29/04/2015
//<end>

/**
 * returns a link to the brochure's thumb
 * @param array $brochureInfo the cms_channel_brochure record
 * @return array
 */
function photoReturnbrochureThumb($brochureInfo) {
    if ($brochureInfo['photo'] == '') {
        return ReturnLink('media/images/channel/brochure-cover-phot.jpg');
    } else {
        return ReturnLink('media/channel/' . $brochureInfo['channelid'] . '/brochure/thumb/' . $brochureInfo['photo']);
    }
}

/**
 * returns a link the the channel brochure's pdf 
 * @param array $brochureInfo the cms_channel_brochure record
 * @return string
 */
function pdfReturnbrochure($brochureInfo) {
    if ($brochureInfo['pdf'] == '') {
        return '';
    } else {
        return ReturnLink('media/channel/' . $brochureInfo['channelid'] . '/brochure/' . $brochureInfo['pdf']);
    }
}

/**
 * gets the link to a channel
 * @param array $channelInfo the cms_channel record
 * @return string 
 */
function channelMainLink($channelInfo) {
    return ReturnLink('channel/' . $channelInfo['channel_url']);
}

/**
 * gets the owner location of a channel
 * @param array $channelInfo the cms_channel record
 * @return array | false the cms_countries record or null if not found
 */
function channelOwnerLocation($channelInfo) {


    global $dbConn;
    $params = array();  
    $location = '';

    $city = cityGetInfo($channelInfo['city']);
    $code = $channelInfo['country'];
//    $query = "SELECT * FROM cms_countries WHERE code='$code'";
//    $ret = db_query($query);
    $query = "SELECT * FROM cms_countries WHERE code=:Code";
    $params[] = array(  "key" => ":Code",
                        "value" =>$code);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
//    if ($ret && db_num_rows($ret) != 0) {
//        $row = db_fetch_array($ret);
    if ($res && $ret != 0) {
        $row = $select->fetch();
        $country = $row['name'];
    } else {
        $country = '';
    }

    if ( isset($city['name']) && $city['name'] && ($channelInfo['country'] != 'ZZ')) {
        $location .= $city['name'] . ' - ' . $country;
    } else {
        $location = $country;
    }

    return $location;


}

/**
 * gets the owner location of a channel (secod version)
 * @param array $channelInfo the cms_channel record
 * @return array | false the cms_countries record or null if not found
 */
function channelOwnerLocationSmall($channelInfo) {


    global $dbConn;
    $params = array(); 

    $location = '';

    $city = cityGetInfo($channelInfo['city_id']);
    $code = $channelInfo['country'];

//    $query = "SELECT * FROM cms_countries WHERE code='$code'";
//    $ret = db_query($query);
    $query = "SELECT * FROM cms_countries WHERE code=:Code";
    $params[] = array(  "key" => ":Code",
                        "value" =>$code);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();

//    if ($ret && db_num_rows($ret) != 0) {
//        $row = db_fetch_array($ret);
    if ($res && $ret != 0) {
        $row = $select->fetch();
        $country = $row['name'];
    } else {
        $country = '';
    }

    if (($city['name']) && ($channelInfo['country'] != 'ZZ')) {
        $location .= $city['name'] . ' - ' . $country;
    } else {
        $location = $country;
    }

    if ($channelInfo['street'])
        $location .= "<br />" . htmlEntityDecode($channelInfo['street']);
    if ($channelInfo['phone'])
        $location .= "<br />phone: " . $channelInfo['phone'];
    if ($channelInfo['zip_code'])
        $location .= "<br />zip code: " . $channelInfo['zip_code'];
    return $location;
}
/**
 * gets a list of poular tubers for the landing page
 * @param integer $channel_id the cms_channel id
 * @param integer $limit number of results
 * @param integer $skip the number of results to skip
 * @return array a list of cms_users records 
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  29/04/2015
//<start>
//function getChannelPopularTubers($channel_id, $limit, $skip) {
//    $userid = intval(userGetID());
//    $options = array(
//        'limit' => $limit,
//        'page' => 0,
//        'skip' => $skip,
//        'orderby' => 'profile_views',
//        'order' => 'desc',
//        'profile_pic' => false,
//        'user_id' => $userid,
//        'channel_id' => $channel_id
//    );
//    return userNotSubscribed($options);
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  29/04/2015
//<end>

/**
 * gets all the channels category
 * @param integer $id the category's id if available
 * @return array | false the cms_channel_category record or null if not found
 */
function getchannelCount($id) {


    global $dbConn;
    $params = array();
//    $query = "SELECT * FROM cms_channel WHERE published=1";
//    if ($id) {
//        $query .= " AND category = " . $id;
//    }
//    $query .= " ORDER BY id ASC";
//    $ret = db_query($query);
//    return db_num_rows($ret);
    $query = "SELECT * FROM cms_channel WHERE published=1";
    if ($id) {
        $query .= " AND category = :Id";
	$params[] = array(  "key" => ":Id",
                            "value" =>$id);
    }
    $query .= " ORDER BY id ASC";
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    return $ret;


}

/**
 * gets a set of random channels
 * @param integer $limit the number of channels to get
 * @return array | false the cms_channel_category record or null if not found
 */
function getrandomcatchannel($limit) {


    global $dbConn;
    $params = array();  
    $lang_code = LanguageGet();
    $languageSel = '';
    $languageJoin = '';
    $languageWhere = '';
    if ($lang_code != 'en') {
        $languageSel = ',ml.title as ml_title';
        $languageJoin = ' INNER JOIN ml_channel_category ml on c.id = ml.entity_id ';
//        $languageWhere = " where ml.lang_code='$lang_code'";
        $languageWhere = " where ml.lang_code=:Lang_code";
	$params[] = array(  "key" => ":Lang_code",
                            "value" =>$lang_code);
    }
//    $query = "SELECT c.*$languageSel FROM cms_channel_category as c$languageJoin $languageWhere ORDER by RAND() LIMIT $limit";
//    $ret = db_query($query);
    $query = "SELECT c.*$languageSel FROM cms_channel_category as c$languageJoin $languageWhere ORDER by RAND() LIMIT :Limit";
    $params[] = array(  "key" => ":Limit",
                        "value" =>$limit,
                        "type" =>"::PARAM_INT");
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
//    if ($ret && db_num_rows($ret) != 0) {
    if ($res && $ret != 0) {
        $ret_arr = array();
	$row = $select->fetchAll();
//        while ($row = db_fetch_array($ret)) {
        foreach($row as $row_item){
            if ($lang_code == 'en') {
                $ret_arr[] = $row_item;
            } else {
                $cat1['id'] = $row_item['id'];
                $cat1['published'] = $row_item['published'];
                $cat1['image'] = $row_item['image'];
                $cat1['title'] = $row_item['ml_title'];
                $ret_arr[] = $cat1;
            }
            
        }
        return $ret_arr;
    } else {
        return false;
    }


}

/**
 * gets most active category channels
 * @param integer $limit the number of channels to get
 * @return array | false the cms_channel_category record or null if not found
 */
function getMostActiveCatchannel($limit) {


	global $dbConn;
	$params = array(); 
    $lang_code = LanguageGet();
    $languageSel = '';
    $languageJoin = '';
    $languageWhere = '';
    if ($lang_code != 'en') {
        $languageSel = ',ml.title as ml_title';
        $languageJoin = ' INNER JOIN ml_channel_category ml on CA.id = ml.entity_id ';
//        $languageWhere = " where ml.lang_code='$lang_code'";
        $languageWhere = " where ml.lang_code=:Lang_code";
	$params[] = array(  "key" => ":Lang_code",
                            "value" =>$lang_code);
        
    }
//    $query = "SELECT CA.*, COUNT(CH.id) AS CNT$languageAnd FROM cms_channel_category AS CA INNER JOIN cms_channel AS CH ON CH.category=CA.id $languageJoin $languageWhere GROUP BY CA.id ORDER BY CNT DESC LIMIT $limit";
    $query = "SELECT CA.*, COUNT(CH.id) AS CNT$languageSel FROM cms_channel_category AS CA INNER JOIN cms_channel AS CH ON CH.category=CA.id $languageJoin $languageWhere GROUP BY CA.id ORDER BY CNT DESC LIMIT :Limit";
//    $ret = db_query($query);
    $params[] = array(  "key" => ":Limit",
                        "value" =>$limit,
                        "type" =>"::PARAM_INT");
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $ret_arr = array();
	$row = $select->fetchAll();
//        while ($row = db_fetch_array($ret)) {
        foreach($row as $row_item){
            if ($lang_code == 'en') {
                $ret_arr[] = $row_item;
            } else {
                $cat1['id'] = $row_item['id'];
                $cat1['published'] = $row_item['published'];
                $cat1['image'] = $row_item['image'];
                $cat1['title'] = $row_item['ml_title'];
                $cat1['CNT'] = $row_item['CNT'];
                $ret_arr[] = $cat1;
            }
        }
        return $ret_arr;
    } else {
        return false;
    }


}

/**
 * gets all the channels category
 * @param integer $id the category's id if available
 * @param integer $start skip this many records
 * @param integer $limit the number of records to get
 * @return array | false the cms_channel_category record or null if not found
 */
function allchannelGetCategory($id=0, $start = 0, $limit = 0) {


    global $dbConn;
    $params = array(); 
    $lang_code = LanguageGet();
    $languageSel = '';
    $languageJoin = '';
    $languageAnd = ' AND ';
//    $languageWhere = '';
    if ($lang_code != 'en') {
        $languageSel = ',ml.title as ml_title';
        $languageJoin = ' LEFT JOIN ml_channel_category ml on c.id = ml.entity_id AND ml.lang_code=:Lang_code ';
//        $languageAnd = " and ml.lang_code='$lang_code'";
//        $languageWhere = " where ml.lang_code='$lang_code'";
//        $languageWhere = " ml.lang_code=:Lang_code";
	$params[] = array(  "key" => ":Lang_code", "value" =>$lang_code);
    }
    $query = "SELECT c.*$languageSel FROM cms_channel_category c$languageJoin";
    if (intval($id) != 0) {
//        $query .= " where c.id = " . $id .$languageAnd;
        $query .= " WHERE c.id = :Id";
//        $query .= " WHERE c.id = :Id ". (!empty($languageWhere) ? $languageAnd.$languageWhere : '');
	$params[] = array(  "key" => ":Id", "value" =>$id);
    }
//    }elseif($languageWhere != ''){
//        $query .= ' WHERE'.$languageWhere;
//    }
    $query .= " ORDER BY c.id ASC";
    if ($start != 0 || $limit != 0) {
//        $query .= " LIMIT " . $start . ", " . $limit;
        $query .= " LIMIT :Start , :Limit";
	$params[] = array(  "key" => ":Start", "value" =>$start, "type" =>"::PARAM_INT");
	$params[] = array(  "key" => ":Limit", "value" =>$limit, "type" =>"::PARAM_INT");
    }
//    debug($query);
//    $ret = db_query($query);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
//    if ($ret && db_num_rows($ret) != 0) {
    if ($res && $ret!= 0) {
        $ret_arr = array();
        $row = $select->fetchAll();
        foreach($row as $row_item){
            if ($lang_code == 'en') {
                $ret_arr[] = $row_item;
            } else {
                $cat1['id'] = $row_item['id'];
                $cat1['published'] = $row_item['published'];
                $cat1['image'] = $row_item['image'];
                $cat1['title'] = (isset($row_item['ml_title']) && !empty($row_item['ml_title'])) ? $row_item['ml_title'] : $row_item['title'];
                $ret_arr[] = $cat1;
            }
        }
        return $ret_arr;        
    } else {
        return false;
    }


}

/**
 * gets all the channels category
 * @param array $in_options the list of options to search for. options include:<br/>
 * <b>id</b> integer. the channels which belong to this category_id.<br/>
 * <b>limit</b> integer. limit the number of results in the retunred list. default 8.<br/>
 * <b>start_id</b> integer. get channel results only after this cms_channel id.<br/>
 * <b>start</b> integer. the number of channels to skip. default 0.<br/>
 * <b>has_data</b> boolean. the channel should have data. default null => channel doesnt need to have data<br/>
 * <b>n_results</b> boolean. gets number of results or list. default false => list.<br/>
 * @return array | false the cms_channel_category record or null if not found
 */
function channelGetCategoryList($in_options) {


    global $dbConn;
    $params = array(); 

    $default_options = array(
        'id' => 0,
        'limit' => 8,
        'start_id' => 0,
        'start' => 0,
        'orderby' => 'CC.id',
        'order' => 'a',
        'start' => 0,
        'has_data' => null,
        'n_results' => false
    );

    $lang_code = LanguageGet();
    $languageSel = '';
    $languageJoin = '';
    $languageAnd = '';
    
    $options = array_merge($default_options, $in_options);

    $id = $options['id'];
    $limit = $options['limit'];
    $start = $options['start'];
    $start_id = $options['start_id'];

    $where = "";
    if ($id != 0) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " CC.id = " . $id . "";
        $where .= " CC.id = :Id ";
	$params[] = array(  "key" => ":Id",
                            "value" =>$id);
    }
    if ($start_id != 0) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " CC.id >= " . $start_id . "";
        $where .= " CC.id >= :Start_id ";
	$params[] = array(  "key" => ":Start_id",
                            "value" =>$start_id);
    }

    if (!is_null($options['has_data'])) {
        if ($where != '')
            $where .= " AND ";
        $where .= " (SELECT COUNT(C.id) FROM cms_channel AS C WHERE C.published=1 AND C.category=CC.id) ";
        if ($options['has_data']) {
            $where .= " > 0 ";
        } else {
            $where .= " = 0 ";
        }
    }

    if ($where != '')
        $where = " WHERE $where ";
    $orderby = $options['orderby'];
    $order='';
    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }
    if ($lang_code != 'en') {
        $languageSel = ',ml.title as ml_title';
        $languageJoin = ' INNER JOIN ml_channel_category ml on CC.id = ml.entity_id ';
        if ($where != ''){
//            $languageAnd = " and ml.lang_code='$lang_code'";
            $languageAnd = " and ml.lang_code=:Lang_code";
            $params[] = array(  "key" => ":Lang_code",
                                "value" =>$lang_code);
        }else{
//            $languageAnd = " where ml.lang_code='$lang_code'";
            $languageAnd = " where ml.lang_code=:Lang_code2";
            $params[] = array(  "key" => ":Lang_code2",
                                "value" =>$lang_code);
        }
    }
    if ($options['n_results'] == false) {
//        $query = "SELECT CC.*$languageSel FROM cms_channel_category AS CC$languageJoin $where $languageAnd ORDER BY $orderby $order LIMIT $start, $limit";
        $query = "SELECT CC.*$languageSel FROM cms_channel_category AS CC$languageJoin $where $languageAnd ORDER BY $orderby $order LIMIT :Start, :Limit";
	$params[] = array(  "key" => ":Start", "value" =>$start, "type" =>"::PARAM_INT");
	$params[] = array(  "key" => ":Limit", "value" =>$limit, "type" =>"::PARAM_INT");
        
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
        
//        if ($ret && $ret != 0) {
        if ($res && $ret != 0) {
            $ret_arr = array();
            $row = $select->fetchAll();
            foreach($row as $row_item){
                if ($lang_code == 'en') {
                    $ret_arr[] = $row_item;
                } else {
                    $cat1['id'] = $row_item['id'];
                    $cat1['published'] = $row_item['published'];
                    $cat1['image'] = $row_item['image'];
                    $cat1['title'] = $row_item['ml_title'];
                    $ret_arr[] = $cat1;
                }
            }
            return $ret_arr;            
        } else {
            return false;
        }
    } else {
        $query = "SELECT COUNT(CC.id) FROM cms_channel_category AS CC $where";
//        $ret = db_query($query);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
//        if ($ret && db_num_rows($ret) != 0) {
        if ($res && $ret != 0) {
//            $row = db_fetch_array($ret);
            $row = $select->fetch();
            return $row[0];
        } else {
            return false;
        }
    }


}

/**
 * gets all the channels info
 * @param integer $id the cms_channel's id
 * @return array | false the cms_channel record or null if not found
 */
function allchannelGetInfo($start, $limit, $category = 0) {


    global $dbConn;
    $params = array();
    $query = "SELECT * FROM cms_channel WHERE published=1";

    if ($category != 0){
//        $query .= " AND category = '$category'";
        $query .= " AND category = :Category";
	$params[] = array(  "key" => ":Category",
                            "value" =>$category);
    }
    $query .= " ORDER BY create_ts DESC"; // ASC";

    if ($start == 0 && $limit == 0) {
        $query = $query;
    } else {
//        $query .= " LIMIT " . $start . ", " . $limit;
        $query .= " LIMIT :Start , :Limit";
	$params[] = array(  "key" => ":Start",
                            "value" =>$start,
                            "type" =>"::PARAM_INT");
	$params[] = array(  "key" => ":Limit",
                            "value" =>$limit,
                            "type" =>"::PARAM_INT");
    }

//    $ret = db_query($query);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
//    if ($ret && db_num_rows($ret) != 0) {
    if ($res && $ret != 0) {
        $ret_arr = $select->fetchAll();
        return $ret_arr;
    } else {
        return false;
    }


}

/**
 * gets all the channels info
 * @param integer $id the cms_channel's id
 * @return array | false the cms_channel record or null if not found
 */
function channelGetInfos($category) {


    global $dbConn;
    $params = array();
    $query = "SELECT * FROM cms_channel_category where id = :Category";

	$params[] = array(  "key" => ":Category",
                            "value" =>$category);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $ret_arr = $select->fetch();
        return $ret_arr;
    } else {
        return false;
    }
}

/**
 * gets the recently added channels
 * @param integer $start number of record to skip
 * @param integer $limit maximum number of records to return
 * @param integer $userid the channel owner id.
 * @return array | false the cms_channel record or null if not found
 */
function recently_added_channels($start, $limit, $userid = 0) {


    global $dbConn;
    $params = array(); 
    $userid = intval($userid);
//    $query = "SELECT ch.* FROM cms_channel AS ch WHERE NOT EXISTS (SELECT co.id FROM cms_channel_connections AS co WHERE co.channelid=ch.id AND co.userid = $userid AND co.published=1) AND ch.published=1";
//    $query .= " AND ch.owner_id<>$userid";
    $query = "SELECT ch.* FROM cms_channel AS ch WHERE NOT EXISTS (SELECT co.id FROM cms_channel_connections AS co WHERE co.channelid=ch.id AND co.userid = :Userid AND co.published=1) AND ch.published=1";
    $query .= " AND ch.owner_id<>:Userid";

    $query .= " ORDER BY ch.id DESC"; // ASC";
    $params[] = array(  "key" => ":Userid",
                        "value" =>$userid);

    if ($start == 0 && $limit == 0) {
        $query = $query;
    } else {
//        $query .= " LIMIT " . $start . ", " . $limit;
        $query .= " LIMIT :Start , :Limit";
	$params[] = array(  "key" => ":Start",
                            "value" =>$start,
                            "type" =>"::PARAM_INT");
	$params[] = array(  "key" => ":Limit",
                            "value" =>$limit,
                            "type" =>"::PARAM_INT");
    }

//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
        $ret_arr = $select->fetchAll();
        return $ret_arr;
    } else {
        return false;
    }


}


/**
 * gets the recently added channels
 * @param integer $start number of record to skip
 * @param integer $limit maximum number of records to return
 * @param integer $userid the channel owner id.
 * @return array | false the cms_channel record or null if not found
 */
function recently_added_channelspage($start, $limit,$cat_id=0) {


    global $dbConn;
    $params = array(); 
    $query = "SELECT ch.* FROM cms_channel AS ch WHERE ch.published=1";
    if ($cat_id != 0){
        $query .= " AND category = :Category";
        $params[] = array(  "key" => ":Category",
                            "value" =>$category);
    }
    $query .= " ORDER BY ch.id DESC"; // ASC";

    if ($start == 0 && $limit == 0) {
        $query = $query;
    } else {
//        $query .= " LIMIT " . $start . ", " . $limit;
        $query .= " LIMIT :Start , :Limit";
	$params[] = array(  "key" => ":Start",
                            "value" =>$start,
                            "type" =>"::PARAM_INT");
	$params[] = array(  "key" => ":Limit",
                            "value" =>$limit,
                            "type" =>"::PARAM_INT");
    }

//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
        $ret_arr = $select->fetchAll();
        return $ret_arr;
    } else {
        return false;
    }


}

/**
 * gets the recent sponsors of a channel
 * @param integer $start number of record to skip
 * @param integer $limit maximum number of records to return
 * @param integer $userid the channel owner id.
 * @return array | false the cms_channel record or null if not found
 */
function recently_added_channels_sponsor($start, $limit, $userid = 0) {


    global $dbConn;
    $params = array();  
    $userid = intval($userid);
    $channelInfo = channelGetInfo($userid);
    $owner_id =$channelInfo['owner_id'];

//    $query = "SELECT ch.* FROM cms_channel AS ch WHERE NOT EXISTS (SELECT so.from_user FROM cms_social_shares AS so WHERE so.channel_id=ch.id AND so.from_user = $userid AND so.share_type=3 AND so.published=1) AND ch.published=1";
//    $query .= " AND ch.owner_id<>$owner_id";
    $query = "SELECT ch.* FROM cms_channel AS ch WHERE NOT EXISTS (SELECT so.from_user FROM cms_social_shares AS so WHERE so.channel_id=ch.id AND so.from_user = :Userid AND so.share_type=3 AND so.published=1) AND ch.published=1";
    $query .= " AND ch.owner_id<>:Owner_id";

    $query .= " ORDER BY ch.create_ts DESC"; // ASC";
    $params[] = array(  "key" => ":Userid",
                        "value" =>$userid);
    $params[] = array(  "key" => ":Owner_id",
                        "value" =>$owner_id);

    if ($start == 0 && $limit == 0) {
        $query = $query;
    } else {
//        $query .= " LIMIT " . $start . ", " . $limit;
        $query .= " LIMIT :Start , :Limit";
	$params[] = array(  "key" => ":Start",
                            "value" =>$start,
                            "type" =>"::PARAM_INT");
	$params[] = array(  "key" => ":Limit",
                            "value" =>$limit,
                            "type" =>"::PARAM_INT");
    }

//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
//    if ($ret && db_num_rows($ret) != 0) {
    if ($res && $ret != 0) {
        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
        $ret_arr = $select->fetchAll();
        return $ret_arr;
    } else {
        return false;
    }


}

/**
 * gets the most activbe channels
 * @param integer $start number of record to skip
 * @param integer $limit maximum number of records to return
 * @param integer $userid the channel owner id.
 * @return array | false the cms_channel record or null if not found
 */
function most_active_channel($start, $limit, $userid = 0) {


    global $dbConn;
    $params = array(); 
    $userid = intval($userid);
    
//    $query = "SELECT count( V.channelid ) AS cnt , V.channelid , CH.* FROM cms_videos AS V INNER JOIN cms_channel AS CH ON V.channelid = CH.id WHERE V.published =1 AND CH.published =1 AND V.channelid <>0 AND NOT EXISTS (SELECT co.id FROM cms_channel_connections AS co WHERE co.channelid=CH.id AND co.userid = $userid AND co.published=1) ";    
//    $query .= " AND CH.owner_id<>$userid";
    $query = "SELECT count( V.channelid ) AS cnt , V.channelid , CH.* FROM cms_videos AS V INNER JOIN cms_channel AS CH ON V.channelid = CH.id WHERE V.published =1 AND CH.published =1 AND V.channelid <>0 AND NOT EXISTS (SELECT co.id FROM cms_channel_connections AS co WHERE co.channelid=CH.id AND co.userid = :Userid AND co.published=1) ";    
    $query .= " AND CH.owner_id<>:Userid";
    
    $params[] = array(  "key" => ":Userid",
                        "value" =>$userid);
    $query .= " GROUP BY V.channelid ORDER BY cnt DESC"; // ASC";

    if ($start == 0 && $limit == 0) {
        $query = $query;
    } else {
//        $query .= " LIMIT " . $start . ", " . $limit;
        $query .= " LIMIT :Start , :Limit";
	$params[] = array(  "key" => ":Start",
                            "value" =>$start,
                            "type" =>"::PARAM_INT");
	$params[] = array(  "key" => ":Limit",
                            "value" =>$limit,
                            "type" =>"::PARAM_INT");
    }

//    $ret = db_query($query);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
//    if ($ret && db_num_rows($ret) != 0) {
    if ($res && $ret != 0) {
        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
        $ret_arr = $select->fetchAll();
        return $ret_arr;
    } else {
        return false;
    }


}

/**
 * gets the most active sponsors of a channel
 * @param integer $start number of record to skip
 * @param integer $limit maximum number of records to return
 * @param integer $userid the channel owner id.
 * @return array | false the cms_channel record or null if not found
 */
function most_active_channel_sponsor($start, $limit, $userid = 0) {


    global $dbConn;
    $params = array(); 
    
    $userid = intval($userid);
    $channelInfo = channelGetInfo($userid);
    $owner_id = $channelInfo['owner_id'];
    
//    $query = "SELECT count( V.channelid ) AS cnt , V.channelid , CH.* FROM cms_videos AS V INNER JOIN cms_channel AS CH ON V.channelid = CH.id WHERE V.published =1 AND CH.published =1 AND V.channelid <>0 AND NOT EXISTS (SELECT so.from_user FROM cms_social_shares AS so WHERE so.channel_id=CH.id AND so.from_user = $userid AND so.share_type=3 AND so.published=1) ";
    $query = "SELECT count( V.channelid ) AS cnt , V.channelid , CH.* FROM cms_videos AS V INNER JOIN cms_channel AS CH ON V.channelid = CH.id WHERE V.published =1 AND CH.published =1 AND V.channelid <>0 AND NOT EXISTS (SELECT so.from_user FROM cms_social_shares AS so WHERE so.channel_id=CH.id AND so.from_user = :Userid AND so.share_type=3 AND so.published=1) ";
    
    //$query = "SELECT ch.* FROM cms_channel AS ch WHERE NOT EXISTS (SELECT so.from_user FROM cms_social_shares AS so WHERE so.channel_id=ch.id AND so.from_user = $userid AND so.share_type=3 AND so.published=1) AND ch.published=1";
//    $query .= " AND CH.owner_id<>$owner_id";
    $query .= " AND CH.owner_id<>:Owner_id";

    $query .= " GROUP BY V.channelid ORDER BY cnt DESC"; // ASC";
    $params[] = array(  "key" => ":Userid",
                        "value" =>$userid);
    $params[] = array(  "key" => ":Owner_id",
                        "value" =>$owner_id);

    if ($start == 0 && $limit == 0) {
        $query = $query;
    } else {
//        $query .= " LIMIT " . $start . ", " . $limit;
        $query .= " LIMIT :Start , :Limit";
	$params[] = array(  "key" => ":Start",
                            "value" =>$start,
                            "type" =>"::PARAM_INT");
	$params[] = array(  "key" => ":Limit",
                            "value" =>$limit,
                            "type" =>"::PARAM_INT");
    }

//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
        $row = $select->fetchAll();
        return $ret_arr;
    } else {
        return false;
    }
}

/**
 * gets the most active connected tubers
 * @param integer $start number of record to skip
 * @param integer $limit maximum number of records to return
 * @param integer $userid the channel owner id.
 * @return array | false the record or null if not found
 */
function most_active_tubers( $channel_id , $start , $limit ) {


	global $dbConn;
	$params = array();  
//    $query = "SELECT count( N.user_id ) AS cnt , N.user_id , U.* FROM cms_social_newsfeed AS N INNER JOIN cms_users AS U ON N.user_id = U.id WHERE N.published =1 AND U.published =1 AND N.feed_privacy=2 AND N.channel_id=$channel_id AND N.user_id<>N.owner_id AND EXISTS (SELECT co.id FROM cms_channel_connections AS co WHERE co.channelid=$channel_id AND co.userid = N.user_id AND co.published=1)";
    $query = "SELECT count( N.user_id ) AS cnt , N.user_id , U.* FROM cms_social_newsfeed AS N INNER JOIN cms_users AS U ON N.user_id = U.id WHERE N.published =1 AND U.published =1 AND N.feed_privacy=2 AND N.channel_id=:Channel_id AND N.user_id<>N.owner_id AND EXISTS (SELECT co.id FROM cms_channel_connections AS co WHERE co.channelid=:Channel_id AND co.userid = N.user_id AND co.published=1)";
    $query .= " GROUP BY N.user_id ORDER BY cnt DESC";
    $params[] = array(  "key" => ":Channel_id",
                        "value" =>$channel_id);
    
    if ($start == 0 && $limit == 0) {
        $query = $query;
    } else {
//        $query .= " LIMIT " . $start . ", " . $limit;
        $query .= " LIMIT :Start , :Limit";
	$params[] = array(  "key" => ":Start",
                            "value" =>$start,
                            "type" =>"::PARAM_INT");
	$params[] = array(  "key" => ":Limit",
                            "value" =>$limit,
                            "type" =>"::PARAM_INT");
    }
    
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {     
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount(); 
    if ($res && $ret != 0) {     
        $ret_arr = array();
	$row = $select->fetchAll();
//        while ($row = db_fetch_array($ret)) {        
        foreach($row as $row_item){
            if( $row_item['profile_Pic'] == ''){
                $row_item['profile_Pic'] = 'he.jpg';
                if ( $row_item['gender'] == 'F') {
                    $row_item['profile_Pic'] = 'she.jpg';
                }
            }
            $ret_arr[] = $row_item;
        }
        return $ret_arr;
    } else {
        return false;
    }


}
/**
 * gets the channel info givven its id
 * @param integer $id the channel's id
 * @return array | false the cms_channel record or null if not found
 */
function channelGetInfo($id) {


    global $dbConn;
    $channelGetInfo = tt_global_get('channelGetInfo');   //Added by Devendra on 25th may 2015
    $params = array();
    
     if(isset($channelGetInfo[$id]) && $channelGetInfo[$id]!=''){
        return $channelGetInfo[$id];
    }
    //$query = "SELECT * FROM cms_channel WHERE id=:Id"; commented by devendra on 22 may 2015 as told by rishav for query optimization.
    $query = "SELECT `id`, `channel_name`, `logo`, `create_ts`, `owner_id`, `published`, `small_description`, `channel_url`, `header`, `bg`, `default_link`, `slogan`, `country`, `city_id`, `city`, `street`, `zip_code`, `phone`, `category`, `keywords`, `deactivated_ts`, `bgcolor`, `coverlink`, `cover_id`, `profile_id`, `slogan_id`, `info_id`, `hidecreatedon`, `hidecreatedby`, `hidelocation`, `channel_visible`, `like_value`, `nb_shares`, `nb_comments`, `notification_email`, `channel_type`, `last_modified` FROM `cms_channel` WHERE id=:Id";
    $params[] = array(  "key" => ":Id", "value" =>$id);

    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
            $channelGetInfo[$id]  =   $row;
        return $row;
    } else {
        $channelGetInfo[$id]  =   false;
        return false;
    }


}

/**
 * gets the channel info givven its url
 * @param string $channel_url the channel's url
 * @return array | false the cms_channel record or null if not found
 */
function channelURLGetInfo($channel_url) {


    global $dbConn;
    $params = array(); 
//    $query = "SELECT * FROM cms_channel WHERE channel_url='$channel_url'";
//
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
//        $row = db_fetch_assoc($ret);
//        return $row;
//    } else {
//        return false;
//    } 
    $query = "SELECT * FROM cms_channel WHERE channel_url=:Channel_url";
    $params[] = array(  "key" => ":Channel_url",
                        "value" =>$channel_url);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        return $row;
    } else {
        return false;
    }


}

/**
 * check the channel owner
 * @param integer $id the channel's id
 * @param integer $userid the user's id
 * @return array | false the cms_channel record or null if not found
 */
function checkChannelOwner($id, $userid) {


    global $dbConn;
    $params = array(); 
//    $query = "SELECT * FROM `cms_channel` WHERE MD5(id)='$id' AND `owner_id` = '$userid'";
//
//    $ret = db_query($query);
//
//    if ($ret && db_num_rows($ret) != 0) {
//        $row = db_fetch_assoc($ret);
//        return $row;
//    } else {
//        return false;
//    }
    $query = "SELECT * FROM `cms_channel` WHERE MD5(id)=:Id AND `owner_id` = :Userid";
    $params[] = array(  "key" => ":Id",
                        "value" =>$id);
    $params[] = array(  "key" => ":Userid",
                        "value" =>$userid);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        return $row;
    } else {
        return false;
    }


}

/**
 * check the channel owner
 * @param MD5 $id the channel's id + owner 
 * @return array | false the cms_channel record or null if not found
 */
function checkChannelOwnerMD5($id) {


    global $dbConn;
    $params = array();  
//    $query = "SELECT * FROM `cms_channel` WHERE MD5( concat(id,owner_id) )='$id'";
//
//    $ret = db_query($query);
//
//    if ($ret && db_num_rows($ret) != 0) {
//        $row = db_fetch_assoc($ret);
//        return $row;
//    } else {
//        return false;
//    }  
    $query = "SELECT * FROM `cms_channel` WHERE MD5( concat(id,owner_id) )=:Id";
    $params[] = array(  "key" => ":Id",
                        "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        return $row;
    } else {
        return false;
    }


}

/**
 * delete channel
 * @param MD5 $id the channel's id + owner 
 * @return boolean true|false depending on the success of the operation
 */
function deleteChannelOwnerMD5($id) {


    global $dbConn;
    $params = array();
    if (deleteMode() == TT_DEL_MODE_PURGE) {
//        $query = "DELETE FROM cms_channel where MD5( concat(id,owner_id) )='$id'";
        $query = "DELETE FROM cms_channel where MD5( concat(id,owner_id) )=:Id";
	$params[] = array(  "key" => ":Id",
                            "value" =>$id);
    } else if (deleteMode() == TT_DEL_MODE_FLAG) {
//        $query = "UPDATE cms_channel SET published=" . TT_DEL_MODE_FLAG . ", deactivated_ts=" . date('Y-m-d') . " WHERE MD5( concat(id,owner_id) )='$id'";
        $query = "UPDATE cms_channel SET published=" . TT_DEL_MODE_FLAG . ", deactivated_ts=:Date WHERE MD5( concat(id,owner_id) )=:Id";
	$params[] = array(  "key" => ":Id",
                            "value" =>$id);
	$params[] = array(  "key" => ":Date",
                            "value" =>date('Y-m-d'));
    }

    
//    return db_query($query);

    $delete_update = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete_update,$params);
    $res    = $delete_update->execute();
    return $res;


}

/**
 * gets the brochures count from channel
 * @param integer $channelid the cms_channel id
 * @param integer $start number of records to skip
 * @param integer $limit maximum number of records to return
 * @return integer
 */
function channelCountBorchureInfo($channelid, $start, $limit) {


    global $dbConn;
    $params = array();  
//    $query = "SELECT * FROM cms_channel_brochure WHERE channelid='$channelid' AND published=1";
//    if ($start == 0 && $limit == 0) {
//        $query = $query;
//    } else {
//        $query .= " LIMIT " . $start . ", " . $limit;
//    }
//    $ret = db_query($query);
//    return db_num_rows($ret);
    $query = "SELECT * FROM cms_channel_brochure WHERE channelid=:Channelid AND published=1";
    $params[] = array(  "key" => ":Channelid",
                        "value" =>$channelid);
    if ($start == 0 && $limit == 0) {
        $query = $query;
    } else {
        $query .= " LIMIT :Start , :Limit";
	$params[] = array(  "key" => ":Start",
                            "value" =>$start,
                            "type" =>"::PARAM_INT");
	$params[] = array(  "key" => ":Limit",
                            "value" =>$limit,
                            "type" =>"::PARAM_INT");
    }
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    return $ret;


}

/**
 * gets a list of cms_channel_brochures records belonging to a channel
 * @param integer $channelid the cms_channel id
 * @param integer $start number of records to skip
 * @param integer $limit maximum number of records to return
 * @return array | false the cms_channel_brochure record or null if not found
 */
function channelGetBorchureInfo($channelid, $start, $limit) {


    global $dbConn;
    $params = array();  
//    $query = "SELECT * FROM cms_channel_brochure WHERE channelid='$channelid' AND published=1";
//    if ($start == 0 && $limit == 0) {
//        $query = $query;
//    } else {
//        $query .= " LIMIT " . $start . ", " . $limit;
//    }
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
//        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
//        return $ret_arr;
//    } else {
//        return false;
//    }
    $query = "SELECT * FROM cms_channel_brochure WHERE channelid=:Channelid AND published=1";
	$params[] = array(  "key" => ":Channelid",
                            "value" =>$channelid);
    if ($start == 0 && $limit == 0) {
        $query = $query;
    } else {
        $query .= " LIMIT :Start , :Limit";
	$params[] = array(  "key" => ":Start",
                            "value" =>$start,
                            "type" =>"::PARAM_INT");
	$params[] = array(  "key" => ":Limit",
                            "value" =>$limit,
                            "type" =>"::PARAM_INT");
    }
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $ret_arr = $select->fetchAll();
        return $ret_arr;
    } else {
        return false;
    }


}

/**
 * gets the cms_videos records that belong to a channel
 * @param integer $channelid the cms_channel id
 * @param char $flag (i)mage or (v)ideo
 * @param integer $start number of records to skip
 * @param integer $limit maximum number of records to return
 * @param date $fromdate videos uploaded after this date.
 * @param date $todate videos uploaded before this date.
 * @return array | false the cms_videos records or false if not found
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  30/04/2015
//<start>
//function channelGetMediaInfo($channelid, $flag, $start, $limit, $fromdate = null, $todate = null) {
//    $query = "SELECT * FROM cms_videos WHERE channelid='$channelid' AND image_video = '$flag' ";
//    if (!is_null($fromdate)) {
//        $query .= " AND DATE(pdate) >= '$fromdate' ";
//    }
//    if (!is_null($todate)) {
//        $query .= " AND DATE(pdate) <= '$todate' ";
//    }
//    $query .= " ORDER BY pdate DESC";
//    if ($start == 0 && $limit == 0) {
//        $query = $query;
//    } else {
//        $query .= " LIMIT " . $start . ", " . $limit;
//    }
//
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
//        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
//        return $ret_arr;
//    } else {
//        return false;
//    }
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  30/04/2015
//<end>

/**
 * gets the count of cms_video info that belong to a channel
 * @param integer $channelid the cms_channel id
 * @param char $flag (i)mage or (v)ideo
 * @param integer $start number of records to skip
 * @param integer $limit maximum number of records to return
 * @param date $fromdate videos uploaded after this date.
 * @param date $todate videos uploaded before this date.
 * @return integer
 */
function channelCountMediaInfo($channelid, $flag, $start, $limit, $fromdate = null, $todate = null) {


    global $dbConn;
    $params = array(); 
//    $query = "SELECT * FROM cms_videos WHERE channelid='$channelid' AND image_video = '$flag'";
    $query = "SELECT * FROM cms_videos WHERE channelid=:Channelid AND image_video = :Flag";
    $params[] = array(  "key" => ":Channelid",
                        "value" =>$channelid);
    $params[] = array(  "key" => ":Flag",
                        "value" =>$flag);
    if (!is_null($fromdate)) {
//        $query .= " AND DATE(pdate) >= '$fromdate' ";
        $query .= " AND DATE(pdate) >= :Fromdate ";
        $params[] = array(  "key" => ":Fromdate",
                            "value" =>$fromdate);
    }
    if (!is_null($todate)) {
//        $query .= " AND DATE(pdate) <= '$todate' ";
        $query .= " AND DATE(pdate) <= :Todate ";
        $params[] = array(  "key" => ":Todate",
                            "value" =>$todate);
    }
//    $ret = db_query($query);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        return $ret;
    } else {
        return 0;
    }


}

/**
 * gets all user catalogs given the search options, options include:<br/>
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>search_string</b>: the string to search for. could be space separated. no default<br/>
 * <b>from_ts</b>: search for catalogs created after this date. default null.<br/>
 * <b>to_ts</b>: search for catalogs created before this date. default null.<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id' or similarity<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>n_results</b>: gets the number of results rather than the rows. default false.
 * @param array $srch_options. the search options
 * @return array a list of cms_users_catalogs records or the number of records
 */
function userCatalogchannelSearch($srch_options) {


    global $dbConn;
    $params = array(); 

    $default_opts = array(
        'limit' => 100,
        'page' => 0,
        'id' => null,
        'user_id' => null,
        'search_string' => null,
        'orderby' => 'id',
        'order' => 'a',
        'n_results' => false,
        'channelid' => null,
        'from_ts' => null,
        'to_ts' => null
    );

    $options = array_merge($default_opts, $srch_options);

    if (is_null($options['search_string']) && strlen($options['search_string']) == 0) {
        $options['search_string'] = null;
    }

    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit;

    $where = '';

    if (is_null($options['channelid'])) {
        return false;
    } else {
        if ($where != '')
            $where .= " AND ";
//        $where .= " channelid='{$options['channelid']}' ";
        $where .= " channelid=:Channelid ";
	$params[] = array(  "key" => ":Channelid",
                            "value" =>$options['channelid']);
    }

    if (!is_null($options['from_ts'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " DATE(catalog_ts) >= '{$options['from_ts']}' ";
        $where .= " DATE(catalog_ts) >= :From_ts ";
	$params[] = array(  "key" => ":From_ts",
                            "value" =>$options['from_ts']);
    }
    if (!is_null($options['id'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " id = '{$options['id']}' ";
        $where .= " id = :Id ";
	$params[] = array(  "key" => ":Id",
                            "value" =>$options['id']);
    }

    if (!is_null($options['to_ts'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " DATE(catalog_ts) <= '{$options['to_ts']}' ";
        $where .= " DATE(catalog_ts) <= :To_ts ";
	$params[] = array(  "key" => ":To_ts",
                            "value" =>$options['to_ts']);
    }
    if ($where != '')
        $where .= " AND ";
    $where .= " published=1 ";
    
    $i = 0;
    if (!is_null($options['search_string'])) {
        $search_strings = explode(' ', $options['search_string']);

        foreach ($search_strings as $in_search_string) {

            $search_string = trim(strtolower($in_search_string));
            $search_string = preg_replace('/[^a-z0-9A-Z]/', '', $search_string);

            if (in_array($search_string, $searched))
                continue;

            $searched[] = " LOWER(catalog_name) LIKE :Searched$i ";
            $params[] = array(  "key" => ":Searched$i", "value" =>'%'.$search_string.'%');
            $i++;
        }

        if ($where != '') $where .= " AND ";
        $where .= '(' . implode(' OR ', $searched) . ')';
    }

    $orderby = $options['orderby'];
    $order='';
    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }

    if ($options['n_results'] == false) {
//        $query = "SELECT * FROM cms_users_catalogs WHERE $where ORDER BY $orderby $order LIMIT $skip, $nlimit";
        $query = "SELECT * FROM cms_users_catalogs WHERE $where ORDER BY $orderby $order LIMIT :Skip, :Nlimit";
	$params[] = array(  "key" => ":Skip",
                            "value" =>$skip,
                            "type" =>"::PARAM_INT");
	$params[] = array(  "key" => ":Nlimit",
                            "value" =>$nlimit,
                            "type" =>"::PARAM_INT");

//        $res = db_query($query);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret1   = $select->rowCount();
        $ret = array();
//        if ($res && db_num_rows($res) != 0) {
        if ($res && $ret1 != 0) {
//            while ($row = db_fetch_assoc($res)) {
//                $ret[] = $row;
//            }
            $ret = $select->fetchAll(PDO::FETCH_ASSOC);
        }
        return $ret;
    } else {
        $query = "SELECT COUNT(id) FROM `cms_users_catalogs` WHERE $where";
//        $ret = db_query($query);
//        $row = db_fetch_array($ret);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
        $row    = $select->fetch();
        
        $n_results = $row[0];
        return $n_results;
    }


}

/**
 * gets the cms_users_catalogs that belong to a channel
 * @param integer $channelid the channel's id
 * @return array | false the cms_users_catalogs record or null if not found
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<start>
//function channelGetAlbumInfo($channelid) {
//    $query = "SELECT * FROM cms_users_catalogs WHERE channelid='$channelid'";
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
//        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
//        return $ret_arr;
//    } else {
//        return false;
//    }
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<end>

/**
 * gets the link for a given channel 
 * @param integer $channelid the channel id
 * @param integer $is_social 0 for the channel links customized and 1 for social links and -1 for all links
 * @return array | false the cms_channel_links record or false if not found
 */
function GetChannelExternalLinks($channelid, $is_social = -1) {


    global $dbConn;
    $params = array();  
    if ($is_social == -1) {
//        $query = "SELECT * FROM cms_channel_links WHERE channelid='$channelid' AND published=1";
        $query = "SELECT * FROM cms_channel_links WHERE channelid=:Channelid AND published=1";
	$params[] = array(  "key" => ":Channelid",
                            "value" =>$channelid);
    } else {
//        $query = "SELECT * FROM cms_channel_links WHERE channelid='$channelid' AND published=1 And is_social='$is_social'";
        $query = "SELECT * FROM cms_channel_links WHERE channelid=:Channelid AND published=1 And is_social=:Is_social";
	$params[] = array(  "key" => ":Channelid",
                            "value" =>$channelid);
	$params[] = array(  "key" => ":Is_social",
                            "value" =>$is_social);
    }
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
        $ret_arr = $select->fetchAll();
        return $ret_arr;
    } else {
        return array();
    }


}

/**
 * add the link for a given channel 
 * @param integer $channelid the channel id
 * @param string $channellink the channel links
 * @param integer $is_social 0 for the channel links customized and 1 for social links
 * @return integer | false the newly inserted cms_channel_links id or false if not inserted
 */
function AddChannelExternalLinks($channelid, $channellink, $is_social) {
    global $dbConn;
    $params = array();  
    // Condition to skip the insert if the url is empty.
    if ($channellink == '' || $channellink == 'http://' || $channellink == 'https://') {
        return false;
    } else {
        $query = "INSERT INTO cms_channel_links (channelid, link,published,is_social) VALUES (:Channelid,:Channellink,1,:Is_social)";
	$params[] = array(  "key" => ":Channelid", "value" =>$channelid);
	$params[] = array(  "key" => ":Channellink", "value" =>$channellink);
	$params[] = array(  "key" => ":Is_social", "value" =>$is_social);
	$insert = $dbConn->prepare($query);
	PDO_BIND_PARAM($insert,$params);
	$res    = $insert->execute();
        $ret    = $dbConn->lastInsertId();
        return ( $res ) ? $ret : false;
    }
}

/**
 * delete All the link for a given channel 
 * @param integer $channelid the channel id
 * @param integer $is_social 0 for the channel links customized and 1 for social links
 * @return boolean true|false depending on the success of the operation
 */
function DeleteChannelExternalLinks($channelid, $is_social) {


    global $dbConn;
    $params = array();
//    $query = "DELETE FROM cms_channel_links where channelid='$channelid' AND is_social='$is_social' AND published=1";
//    
//    return db_query($query);
    $query = "DELETE FROM cms_channel_links where channelid=:Channelid AND is_social=:Is_social AND published=1";    
    $params[] = array(  "key" => ":Channelid", "value" =>$channelid);
    $params[] = array(  "key" => ":Is_social", "value" =>$is_social);
    
    $delete = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete,$params);
    $res    = $delete->execute();
    return $res;


}

/**
 * delete the link for a given channel 
 * @param integer $channelid the channel id
 * @param integer $id link id
 * @return boolean true|false depending on the success of the operation
 */
function unitDeleteChannelExternalLinks($channelid, $id) {
    global $dbConn;
    $params = array();  
    if (deleteMode() == TT_DEL_MODE_PURGE) {
        $query = "DELETE FROM cms_channel_links where channelid=:Channelid AND id=:Id AND published=1";
    } else if (deleteMode() == TT_DEL_MODE_FLAG) {
        $query = "UPDATE cms_channel_links SET published=" . TT_DEL_MODE_FLAG . " WHERE channelid=:Channelid AND id=:Id AND published=1";
    }
    $params[] = array(  "key" => ":Channelid", "value" =>$channelid);
    $params[] = array(  "key" => ":Id", "value" =>$id);    
    $delete_update = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete_update,$params);
    $res    = $delete_update->execute();
    return $res;
}
/**
 * gets the news info of a channel depending on search criteria. options include:<br/>
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>id</b>: the news id. default null.<br/>
 * <b>channelid</b>: the channel's id. default null<br/>
 * <b>description</b>: the description of the channel news to search for. default null.<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>n_results</b>: true => return a count, false => return a list. default false<br/>
 * <b>is_visible</b>: is visible or not. default = -1 => doenst matter.<br/>
 * <b>from_ts</b>: get news after this date. default null<br/>
 * <b>to_ts</b>: get news before this date. default null<br/>
 * <b>search_string</b>: the search string. default null<br/>
 * @param array $srch_options 
 * @return array | false an array of 'cms_channel_news' records or false if none found.
 */
function channelnewsSearch($srch_options) {
    global $dbConn;
    $params = array(); 
    $default_opts = array(
        'limit' => 100,
        'page' => 0,
        'id' => null,
        'channelid' => null,
        'description' => null,
        'search_string' => null,
        'orderby' => 'id',
        'is_visible' => -1,
        'n_results' => false,
        'from_ts' => null,
        'to_ts' => null,
        'order' => 'a'
    );

    $options = array_merge($default_opts, $srch_options);

    $where = '';

    if (!is_null($options['id'])) {
        if ($where != '')
            $where .= ' AND ';
        $where .= " id=:Id ";
        $params[] = array(  "key" => ":Id",
                            "value" =>$options['id']);
    }
    if (!is_null($options['channelid'])) {
        if ($where != '')
            $where .= ' AND ';
	$where .= " find_in_set(cast(channelid as char), :Channelid) ";
        $params[] = array(  "key" => ":Channelid", "value" =>$options['channelid']);
    }
    if (!is_null($options['description'])) {
        if ($where != '')
            $where .= ' AND ';
        $where .= " description=:Description ";
        $params[] = array(  "key" => ":Description",
                            "value" =>$options['description']);
    }
    if (!is_null($options['from_ts'])) {
        if ($where != '')
            $where .= " AND ";
        $where .= " DATE(create_ts) >= :From_ts ";
        $params[] = array(  "key" => ":From_ts",
                            "value" =>$options['from_ts']);
    }

    if (!is_null($options['to_ts'])) {
        if ($where != '')
            $where .= " AND ";
        $where .= " DATE(create_ts) <= :To_ts ";
        $params[] = array(  "key" => ":To_ts",
                            "value" =>$options['to_ts']);
    }
    if ($options['is_visible'] != -1) {
        if ($where != '')
            $where .= " AND ";
        $where .= " is_visible=:Is_visible ";
        $params[] = array(  "key" => ":Is_visible",
                            "value" =>$options['is_visible']);
    }
    if (!is_null($options['search_string'])) {
        $options['search_string'] = strtolower($options['search_string']);
        if ($where != '')
            $where = " ( $where ) AND ";
        $search_strings = explode(' ', $options['search_string']);
        $wheres = array();
        $i=0;
        foreach ($search_strings as $search_string_loop) {
            $wheres[] = "(				
                LOWER(description) LIKE :Search$i
            )";
            $params[] = array(  "key" => ":Search$i", "value" => '%'.$search_string_loop.'%' );
            $i++;
        }
        $where .= "( " . implode(' AND ', $wheres) . ")";
    }

    if ($where != '')
        $where .= ' AND ';
    $where .= " published=1 ";

    if ($where != '') {
        $where = "WHERE $where";
    }

    $orderby = $options['orderby'];
    $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';

    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit;

    if ($options['n_results'] == false) {
        $query = "SELECT * FROM cms_channel_news $where ORDER BY $orderby $order LIMIT :Skip, :Nlimit";
	$params[] = array(  "key" => ":Skip",
                            "value" =>$skip,
                            "type" =>"::PARAM_INT");
	$params[] = array(  "key" => ":Nlimit",
                            "value" =>$nlimit,
                            "type" =>"::PARAM_INT");
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
        if (!$res || ($ret == 0)) {
            return false;
        } else {
            $ret_arr = array();
            $ret_arr = $select->fetchAll(PDO::FETCH_ASSOC);
            return $ret_arr;
        }
    } else {
        $query = "SELECT COUNT(id) FROM cms_channel_news $where";
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
	$ret    = $select->rowCount();
        if ($res && $ret != 0) {
            $row = $select->fetch();
            return $row[0];
        } else {
            return false;
        }
    }
}

/**
 * edits a news info
 * @param array $news_info the new cms_channel_news info
 * @return boolean true|false if success|fail
 */
function channelNewsEdit($news_info) {


    global $dbConn;
    $params = array(); 
    $query = "UPDATE cms_channel_news SET ";
    $i = 0;
    foreach ($news_info as $key => $val) {
        if ($key != 'id' && $key != 'channelid') {
//            $query .= " $key = '$val',";
            $query .= " $key = :Val".$i.",";
            $params[] = array(  "key" => ":Val".$i,
                                "value" =>$val);
            $i++;
        }
    }
    $query = trim($query, ',');
//    $query .= " WHERE id='{$news_info['id']}' AND channelid='{$news_info['channelid']}'";
    $query .= " WHERE id=:Id AND channelid=:Channelid";
    $params[] = array(  "key" => ":Id",
                        "value" =>$news_info['id']);
    $params[] = array(  "key" => ":Channelid",
                        "value" =>$news_info['channelid']);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $ret    = $update->execute();
//    $ret = db_query($query);
    return ( $ret ) ? true : false;


}

/**
 * gets news for a given channel 
 * @param integer $channelid the channel id
 * @return array | false the cms_channel_news record or false if not found
 */
function GetChannelNews($channelid) {


    global $dbConn;
    $params = array();  
//    $query = "SELECT * FROM cms_channel_news WHERE channelid='$channelid' AND published=1";
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
//        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
//        return $ret_arr;
//    } else {
//        return false;
//    } 
    $query = "SELECT * FROM cms_channel_news WHERE channelid=:Channelid AND published=1";
    $params[] = array(  "key" => ":Channelid",
                        "value" =>$channelid);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
        $ret_arr = $select->fetchAll();
        return $ret_arr;
    } else {
        return false;
    }


}

/**
 * add news for a given channel 
 * @param integer $channelid the channel id
 * @param string $channelnews the channel news
 * @param string $create_ts the creation timestamp
 * @return integer | false the newly inserted cms_channel_news id or false if not inserted
 */
function AddChannelNews($channelid, $channelnews, $create_ts) {
    global $dbConn;
    $params = array();
//    $query = "INSERT INTO cms_channel_news (channelid, description,create_ts,published)
//    		VALUES (:Channelid,:Channelnews,:Create_ts,1)";
    $query = "INSERT INTO cms_channel_news (channelid, description,published)
    		VALUES (:Channelid,:Channelnews,1)";
	$params[] = array(  "key" => ":Channelid", "value" =>$channelid);
	$params[] = array(  "key" => ":Channelnews", "value" =>$channelnews);
	//$params[] = array(  "key" => ":Create_ts", "value" =>$create_ts);
	$insert = $dbConn->prepare($query);
	PDO_BIND_PARAM($insert,$params);
	$res    = $insert->execute();
        $news_id = $dbConn->lastInsertId();
    if ($news_id) {
        newsfeedAdd(userGetID(), $news_id, SOCIAL_ACTION_UPLOAD, $news_id, SOCIAL_ENTITY_NEWS, USER_PRIVACY_PUBLIC, $channelid);
        return $news_id;
    } else {
        return false;
    }
}

/**
 * delete All the news for a given channel 
 * @param integer $channelid the channel id
 * @return boolean true|false depending on the success of the operation
 */
function DeleteChannelNews($channelid) {


    global $dbConn;
    $params = array();  
//    $query = "DELETE FROM cms_channel_news where channelid='$channelid'";
//    
//    return db_query($query);
    $query = "DELETE FROM cms_channel_news where channelid=:Channelid";
    
    $params[] = array(  "key" => ":Channelid",
                        "value" =>$channelid);
    $delete = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete,$params);
    $res    = $delete->execute();

    return $res;


}

/**
 * delete the news for a given channel 
 * @param integer $channelid the channel id
 * @param integer $id link id
 * @return boolean true|false depending on the success of the operation
 */
function unitDeleteChannelnews($channelid, $id) {


    global $dbConn;
    $params = array(); 

    if (deleteMode() == TT_DEL_MODE_PURGE) {
//        $query = "DELETE FROM cms_channel_news where channelid='$channelid' AND id='$id' AND published=1";
        $query = "DELETE FROM cms_channel_news where channelid=:Channelid AND id=:Id AND published=1";
    } else if (deleteMode() == TT_DEL_MODE_FLAG) {
//        $query = "UPDATE cms_channel_news SET published=" . TT_DEL_MODE_FLAG . " WHERE channelid='$channelid' AND id='$id' AND published=1";
        $query = "UPDATE cms_channel_news SET published=" . TT_DEL_MODE_FLAG . " WHERE channelid=:Channelid AND id=:Id AND published=1";
    }
    $params[] = array(  "key" => ":Channelid",
                        "value" =>$channelid);
    $params[] = array(  "key" => ":Id",
                        "value" =>$id);
    
    newsfeedDeleteAll($id, SOCIAL_ENTITY_NEWS);

    //delete comments
    socialCommentsDelete($id, SOCIAL_ENTITY_NEWS);

    //delete likes
    socialLikesDelete($id, SOCIAL_ENTITY_NEWS);

    //delete shares
    socialSharesDelete($id, SOCIAL_ENTITY_NEWS);

    //delete ratings
    socialRatesDelete($id, SOCIAL_ENTITY_NEWS);

    
    $delete_update = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete_update,$params);
    $res    = $delete_update->execute();
//    return db_query($query);
    return $res;


}

/**
 * gets the other TT links for a given channel 
 * @param integer $channelid the channel id
 * @return array | false the cms_channel_otherTT record or false if not found
 */
function GetChannelOtherTT($channelid) {


    global $dbConn;
    $params = array();
//    $query = "SELECT * FROM cms_channel_otherTT WHERE channelid='$channelid' AND published=1";
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
//        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
//        return $ret_arr;
//    } else {
//        return false;
//    }
    $query = "SELECT * FROM cms_channel_otherTT WHERE channelid=:Channelid AND published=1";
    $params[] = array(  "key" => ":Channelid",
                        "value" =>$channelid);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $ret_arr = $select->fetchAll();
        return $ret_arr;
    } else {
        return false;
    }


}

/**
 * add news for a given channel 
 * @param integer $channelid the channel id
 * @param string $TTname the other TT name
 * @param string $TTlink the other TT url
 * @return integer | false the newly inserted cms_channel_otherTT id or false if not inserted
 */
function AddChannelOtherTT($channelid, $TTname, $TTlink) {
    global $dbConn;
    $params = array();
    $query = "  INSERT INTO cms_channel_otherTT (channelid, name,link,published)
                VALUES (:Channelid,:TTname,:TTlink,1)";
    $params[] = array(  "key" => ":Channelid", "value" =>$channelid);
    $params[] = array(  "key" => ":TTname", "value" =>$TTname);
    $params[] = array(  "key" => ":TTlink", "value" =>$TTlink);
    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params);
    $res    = $insert->execute();
    $ret    = $dbConn->lastInsertId();
    return ( $res ) ? $ret : false;
}

/**
 * delete All other TT Channel for a given channel 
 * @param integer $channelid the channel id
 * @return boolean true|false depending on the success of the operation
 */
function DeleteChannelOtherTT($channelid) {


    global $dbConn;
    $params = array();  
//    $query = "DELETE FROM cms_channel_otherTT where channelid='$channelid'";
//    
//    return db_query($query);
    $query = "DELETE FROM cms_channel_otherTT where channelid=:Channelid";
    
    $params[] = array(  "key" => ":Channelid",
                        "value" =>$channelid);
    $delete = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete,$params);
    $res    = $delete->execute();
    return $res;


}

/**
 * delete the other TT Channel for a given channel 
 * @param integer $channelid the channel id
 * @param integer $id other TT link id
 * @return boolean true|false depending on the success of the operation
 */
function unitDeleteChannelOtherTT($channelid, $id) {


    global $dbConn;
    $params = array();

    if (deleteMode() == TT_DEL_MODE_PURGE) {
//        $query = "DELETE FROM cms_channel_otherTT where channelid='$channelid' AND id='$id' AND published=1";
        $query = "DELETE FROM cms_channel_otherTT where channelid=:Channelid AND id=:Id AND published=1";
    } else if (deleteMode() == TT_DEL_MODE_FLAG) {
//        $query = "UPDATE cms_channel_otherTT SET published=" . TT_DEL_MODE_FLAG . " WHERE channelid='$channelid' AND id='$id' AND published=1";
        $query = "UPDATE cms_channel_otherTT SET published=" . TT_DEL_MODE_FLAG . " WHERE channelid=:Channelid AND id=:Id AND published=1";
    }
    
    $params[] = array(  "key" => ":Channelid",
                        "value" =>$channelid);
    $params[] = array(  "key" => ":Id",
                        "value" =>$id);
    $delete_update = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete_update,$params);
    $res    = $delete_update->execute();
//    return db_query($query);
    return $res;


}

/**
 * delete the event for a given channel 
 * @param integer $channelid the channel id
 * @param integer $id event id to be deletd
 * @return boolean true|false depending on the success of the operation
 */
function unitDeleteChannelEvent($channelid, $id) {


    global $dbConn;
    $params = array();  
    
    if (deleteMode() == TT_DEL_MODE_PURGE) {
//        $query = "DELETE FROM cms_channel_event where channelid='$channelid' AND id='$id' AND published=1";
        $query = "DELETE FROM cms_channel_event where channelid=:Channelid AND id=:Id AND published=1";
    } else if (deleteMode() == TT_DEL_MODE_FLAG) {
//        $query = "UPDATE cms_channel_event SET published=" . TT_DEL_MODE_FLAG . " WHERE channelid='$channelid' AND id='$id' AND published=1";
        $query = "UPDATE cms_channel_event SET published=" . TT_DEL_MODE_FLAG . " WHERE channelid=:Channelid AND id=:Id AND published=1";
    }

    newsfeedDeleteAll($id, SOCIAL_ENTITY_EVENTS);

    $channelInfo = channelGetInfo($channelid);

    newsfeedAdd($channelInfo['owner_id'], $id, SOCIAL_ACTION_EVENT_CANCEL, $id, SOCIAL_ENTITY_EVENTS, USER_PRIVACY_PUBLIC, $channelid);

    // send news feed for the list of users joined this event

//    $query_join = "SELECT * FROM cms_channel_event_join WHERE event_id='$id' AND published=1";
    $query_join = "SELECT * FROM cms_channel_event_join WHERE event_id=:Id AND published=1";

//    $res_join = db_query($query_join);
    
    $params[] = array(  "key" => ":Id", "value" =>$id);
    $select = $dbConn->prepare($query_join);
    PDO_BIND_PARAM($select,$params);
    $res_join = $select->execute();

    $ret_join = $select->rowCount();
//    if ($res_join && db_num_rows($res_join) != 0) {
    if ($res_join && $ret_join != 0) {
//        while ($row = db_fetch_array($res_join)) {
	$row = $select->fetchAll(PDO::FETCH_ASSOC);
        foreach($row as $row_item){
            $j_id = $row_item['id'];
            joinEventDelete($j_id);
            newsfeedAdd($row_item['user_id'], $j_id, SOCIAL_ACTION_EVENT_CANCEL, $id, SOCIAL_ENTITY_EVENTS, USER_PRIVACY_PRIVATE, null);
            addPushNotification(SOCIAL_ACTION_EVENT_CANCEL, $row_item['user_id'], $channelid, 1, $id, SOCIAL_ENTITY_EVENTS);
            sendChannelEmailNotification_Cancel_Event_Join($row_item['user_id'], $id, SOCIAL_ACTION_EVENT_CANCEL);
        }
    }
    
    // remove news feed for the list of user invited to this event
    $invites = socialSharesGet($options = array(
        'entity_id' => $id,
        'entity_type' => SOCIAL_ENTITY_EVENTS,
        'share_type' => SOCIAL_SHARE_TYPE_INVITE,
        'limit' => null
    ));

    foreach ($invites as $invitesInfo) {
        $ids = $invitesInfo['id'];
        newsfeedDeleteJoinByAction($ids, SOCIAL_ACTION_INVITE, SOCIAL_ENTITY_EVENTS);
    }

    // send news feed for the list of channels sponsored this event
    $sponsors = socialSharesGet($options = array(
        'entity_id' => $id,
        'entity_type' => SOCIAL_ENTITY_EVENTS,
        'share_type' => SOCIAL_SHARE_TYPE_SPONSOR,
        'limit' => null
    ));

    foreach ($sponsors as $sponsorsInfo) {
        $c_id = $sponsorsInfo['c_id'];
        $sp_id = $sponsorsInfo['sp_id'];
        newsfeedAdd($sponsorsInfo['owner_id'], $sp_id, SOCIAL_ACTION_EVENT_CANCEL, $id, SOCIAL_ENTITY_EVENTS, USER_PRIVACY_PRIVATE, $c_id);
        sendChannelEmailNotification_Cancel_Event_Sponsor($c_id, $id, SOCIAL_ACTION_EVENT_CANCEL);
    }


    //delete comments
    socialCommentsDelete($id, SOCIAL_ENTITY_EVENTS);

    //delete likes
    socialLikesDelete($id, SOCIAL_ENTITY_EVENTS);

    //delete shares and sponsors.
    socialSharesDelete($id, SOCIAL_ENTITY_EVENTS);

    //delete ratings
    socialRatesDelete($id, SOCIAL_ENTITY_EVENTS);


    
//    return db_query($query);
    
    $params = array(); 
    $params[] = array(  "key" => ":Channelid", "value" =>$channelid);
    $params[] = array(  "key" => ":Id", "value" =>$id);
    
    $delete_update = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete_update,$params);
    $res    = $delete_update->execute();
    return $res;


}

/**
 * gets the events for a given channel 
 * @param integer $channelid the channel id
 * @return array | false the cms_channel_event record or false if not found
 */
function unitGetChannelEvent($channelid, $id) {


    global $dbConn;
    $params = array();
//    $query = "SELECT * FROM cms_channel_event WHERE channelid='$channelid' AND id='$id' AND published=1";
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
//        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
//        return $ret_arr;
//    } else {
//        return false;
//    }
    $query = "SELECT * FROM cms_channel_event WHERE channelid=:Channelid AND id=:Id AND published=1";
    $params[] = array(  "key" => ":Channelid",
                        "value" =>$channelid);
    $params[] = array(  "key" => ":Id",
                        "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $ret_arr = $select->fetchAll();
        return $ret_arr;
    } else {
        return false;
    }


}

/**
 * gets the events for a given channel 
 * @param integer $channelid the channel id
 * @return array | false the cms_channel_event record or false if not found
 */
function GetChannelEvents($channelid) {


    global $dbConn;
    $params = array();  
//    $query = "SELECT * FROM cms_channel_event WHERE channelid='$channelid' AND published=1";
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
//        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
//        return $ret_arr;
//    } else {
//        return false;
//    }
    $query = "SELECT * FROM cms_channel_event WHERE channelid=:Channelid AND published=1";
    $params[] = array(  "key" => ":Channelid",
                        "value" =>$channelid);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $ret_arr = $select->fetchAll();
        return $ret_arr;
    } else {
        return false;
    }


}

/**
 * gets the events for a given id 
 * @param integer $id the event id
 * @return array | false the cms_channel_event record or false if not found
 */
function channelEventInfo($id, $published = 1) {


    global $dbConn;
    $channelEventInfo = tt_global_get('channelEventInfo');      //Added By Devendra    
    $params = array(); 
    if(isset($channelEventInfo[$id][$published]) && $channelEventInfo[$id]!=''){
        return $channelEventInfo[$id][$published];
    }
    if ($published != -1) {
//        $query = "SELECT * FROM cms_channel_event WHERE id='$id' AND published='$published'";
//        $query = "SELECT * FROM cms_channel_event WHERE id=:Id AND published=:Published";  //hide by Devendra
        $query = "SELECT `id`, `channelid`, `name`, `photo`, `description`, `location`, `country`, `location_detailed`, `longitude`, `lattitude`, `fromdate`, `fromtime`, `todate`, `totime`, `whojoin`, `limitnumber`, `caninvite`, `hideguests`, `showsponsors`, `allowsponsoring`, `enable_share_comment`, `published`, `like_value`, `nb_shares`, `nb_comments`, `is_visible` FROM `cms_channel_event` WHERE id=:Id AND published=:Published";  // Added By Devendra
	$params[] = array(  "key" => ":Id", "value" =>$id);
	$params[] = array(  "key" => ":Published", "value" =>$published);
    } else {
//        $query = "SELECT * FROM cms_channel_event WHERE id='$id'";
//        $query = "SELECT * FROM cms_channel_event WHERE id=:Id";   // Hide by Devendra
        $query = "SELECT `id`, `channelid`, `name`, `photo`, `description`, `location`, `country`, `location_detailed`, `longitude`, `lattitude`, `fromdate`, `fromtime`, `todate`, `totime`, `whojoin`, `limitnumber`, `caninvite`, `hideguests`, `showsponsors`, `allowsponsoring`, `enable_share_comment`, `published`, `like_value`, `nb_shares`, `nb_comments`, `is_visible` FROM `cms_channel_event` WHERE id=:Id";   // Added By Devendra
	$params[] = array(  "key" => ":Id", "value" =>$id);
    }
//    $ret = db_query($query);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
//    if ($ret && db_num_rows($ret) != 0) {
    if ($res && $ret != 0) {
//        $row = db_fetch_assoc($ret);
        $row = $select->fetch(PDO::FETCH_ASSOC);
        $channelEventInfo[$id][$published] =   $row;
        return $row;
    } else {
        $channelEventInfo[$id][$published] =   false;
        return false;
    }


}

/**
 * gets the news for a given id 
 * @param integer $id the news id
 * @return array | false the cms_channel_news record or false if not found
 */
function channelNewsInfo($id) {


    global $dbConn;
    $channelNewsInfo = tt_global_get('channelNewsInfo');      //Added By Devendra
    $params = array();
    if(isset($channelNewsInfo[$id]) && $channelNewsInfo[$id]!=''){
        return $channelNewsInfo[$id];
    }

//    $query = "SELECT * FROM cms_channel_news WHERE id=:Id AND published=1";  Hide by Devendra
    $query = "SELECT `id`, `channelid`, `description`, `create_ts`, `published`, `like_value`, `nb_shares`, `nb_comments`, `enable_share_comment`, `is_visible` FROM `cms_channel_news` WHERE id=:Id AND published=1";  //Added By Devendra
    $params[] = array(  "key" => ":Id", "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
            $channelNewsInfo[$id] =   $row;
            return $row;
    } else {
            $channelNewsInfo[$id] =   false;
            return false;
    }


}

/**
 * gets the suggested events
 * @param integer $channelid the channel id
 * @return array | false the cms_channel_event record or false if not found
 */
function getSuggestedEvents($channelid, $start, $limit) {


    global $dbConn;
    $params = array();  
    $cur_date = date('Y-m-d');
    $cur_time = date('H:i:s');

//    $query = "SELECT entity_id FROM cms_social_shares WHERE entity_type=" . SOCIAL_ENTITY_EVENTS . " AND share_type=" . SOCIAL_SHARE_TYPE_SPONSOR . " AND from_user='$channelid' AND published=1";
    $query = "SELECT GROUP_CONCAT( DISTINCT entity_id SEPARATOR ',' ) AS entity_id FROM cms_social_shares WHERE entity_type=" . SOCIAL_ENTITY_EVENTS . " AND share_type=" . SOCIAL_SHARE_TYPE_SPONSOR . " AND from_user=:Channelid AND published=1";//to be fixed the predefined arguments
//    $ret = db_query($query);
    $params[] = array(  "key" => ":Channelid", "value" =>$channelid);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    $ret_arr = array();
//    while ($row = db_fetch_array($ret)) {
    $row = $select->fetch();
    
    $event_id_arr = $row['entity_id'];
    if($event_id_arr=='') $event_id_arr='0';

    $query2 = "SELECT *, RAND() AS random FROM cms_channel_event AS E WHERE E.channelid<>:Channelid AND E.id NOT IN (".$event_id_arr.") AND E.published=1";
    $params = array(); 
    $params[] = array(  "key" => ":Channelid", "value" =>$channelid);
//    $query .= " AND ( ( (E.fromdate < '$cur_date') OR (E.fromdate = '$cur_date' AND E.fromtime<'$cur_time') ) ";
    $query2 .= " AND ( ( (E.fromdate < :Cur_date) OR (E.fromdate = :Cur_date AND E.fromtime<:Cur_time) ) ";
    $query2 .= " AND ";
//    $query .= " ( (E.todate > '$cur_date') OR (E.todate = '$cur_date' AND E.totime>'$cur_time') ) ";
    $query2 .= " ( (E.todate > :Cur_date) OR (E.todate = :Cur_date AND E.totime>:Cur_time) ) ";
    $query2 .= " OR ";
//    $query .= " ( (E.fromdate > '$cur_date') OR (E.fromdate = '$cur_date' AND E.fromtime>'$cur_time') ) ) ";
    $query2 .= " ( (E.fromdate > :Cur_date) OR (E.fromdate = :Cur_date AND E.fromtime>:Cur_time) ) ) ";
    $query2 .= " ORDER BY random ASC";
    if ($start == 0 && $limit == 0) {
        $query2 = $query2;
    } else {
//        $query .= " LIMIT " . $start . ", " . $limit;
        $query2 .= " LIMIT :Start, :Limit";
	$params[] = array(  "key" => ":Start", "value" =>$start, "type" => "::PARAM_INT");
	$params[] = array(  "key" => ":Limit", "value" =>$limit, "type" => "::PARAM_INT");
    }
//    $ret = db_query($query);
    $select = $dbConn->prepare($query2);
    $params[] = array(  "key" => ":Cur_date", "value" =>$cur_date);
    $params[] = array(  "key" => ":Cur_time", "value" =>$cur_time);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
//    if ($ret && db_num_rows($ret) != 0) {
    if ($res && $ret != 0) {
        $ret_arr = $select->fetchAll();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
        return $ret_arr;
    } else {
        return false;
    }


}

/**
 * gets the event info of a channel depending on search criteria. options include:<br/>
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>id</b>: event id<br/>
 * <b>channelid</b>: the channel's id. default null<br/>
 * <b>owner_id</b>: the channel's owner id. default null<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>name</b>: the channel's name default null<br/>
 * <b>location</b>: the event location default null<br/>
 * <b>from_ts</b>: start date default null<br/>
 * <b>current_date</b>: specific date default null<br/>
 * <b>longitude_min</b>: minumum longitude for search default null<br/>
 * <b>longitude_max</b>: maximum longitude for search default null<br/>
 * <b>latitude_min</b>: minumum latitude for search default null<br/>
 * <b>latitude_max</b>: maximum latitude for search default null<br/>
 * <b>search_string</b>: the search string. default null<br/>
 * <b>is_visible</b>: is visible or not. default = -1 => doenst matter.<br/>
 * <b>to_ts</b>: end date default null<br/>
 * <b>whojoin</b>: search for the channel's name exactly default 1. 2 for start match expression<br/>
 * <b>status<b/> integer. current => 2. upcoming => 1. past => 0. current+upcoming=>3 overwrites date_from and date_to. default null (doesnt matter)<br/>
 * @param array $srch_options 
 * @return array | false an array of 'cms_channel_event' records or false if none found.
 */
function channeleventSearch($srch_options) {


    global $dbConn;
    $params = array();  
    $default_opts = array(
        'limit' => 100,
        'page' => 0,
        'id' => null,
        'channelid' => null,
        'owner_id' => null,
        'name' => null,
        'location' => null,
        'from_ts' => null,
        'to_ts' => null,
        'current_date' => null,
        'whojoin' => null,
        'limitnumber' => null,
        'caninvite' => null,
        'hideguests' => null,
        'search_string' => null,
        'country' => null,
        'longitude_min' => null,
        'longitude_max' => null,
        'latitude_min' => null,
        'latitude_max' => null,
        'is_visible' => -1,
        'strict_search' => 1,
        'skip' => 0,
        'orderby' => 'id',
        'published' => 1,
        'order' => 'a',
        'n_results' => false,
        'status' => null
    );

    $options = array_merge($default_opts, $srch_options);

    $is_visible = $options['is_visible'];

    $where = '';

    if (!is_null($options['id'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " E.id='{$options['id']}' ";
        $where .= " E.id=:Id ";
	$params[] = array(  "key" => ":Id",
                            "value" =>$options['id']);
    }
    if (!is_null($options['country'])) {
        if ($where != '') $where .= ' AND ';
        $where .= " E.country=:Country ";
	$params[] = array(  "key" => ":Country", "value" =>$options['country']);
    }
    if (!is_null($options['channelid'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " E.channelid='{$options['channelid']}' ";
        $where .= " E.channelid=:Channelid ";
	$params[] = array(  "key" => ":Channelid",
                            "value" =>$options['channelid']);
    }
    if (!is_null($options['longitude_min']) && !is_null($options['longitude_max'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " E.longitude BETWEEN '{$options['longitude_min']}' AND '{$options['longitude_max']}'";
        $where .= " E.longitude BETWEEN :Longitude_min AND :Longitude_max";
	$params[] = array(  "key" => ":Longitude_min",
                            "value" =>$options['longitude_min']);
	$params[] = array(  "key" => ":Longitude_max",
                            "value" =>$options['longitude_max']);
    }
    if (!is_null($options['latitude_min']) && !is_null($options['latitude_max'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " E.lattitude BETWEEN '{$options['latitude_min']}' AND '{$options['latitude_max']}'";
        $where .= " E.lattitude BETWEEN :Latitude_min AND :Latitude_max";
	$params[] = array(  "key" => ":Latitude_min",
                            "value" =>$options['latitude_min']);
	$params[] = array(  "key" => ":Latitude_max",
                            "value" =>$options['latitude_max']);
    }
    if (!is_null($options['name']) && $options['name']!='' ) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " E.name='{$options['name']}' ";
        $where .= " E.name=:Name ";
	$params[] = array(  "key" => ":Name",
                            "value" =>$options['name']);
    }
    if (!is_null($options['location']) && $options['location']!='' ) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " E.location={$options['location']} ";
        $where .= " E.location=:Location ";
	$params[] = array(  "key" => ":Location",
                            "value" =>$options['location']);
    }
    if (!is_null($options['from_ts']) && $options['from_ts']!='' ) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " DATE(E.todate) >= '{$options['from_ts']}' ";
        $where .= " DATE(E.todate) >= :From_ts ";
	$params[] = array(  "key" => ":From_ts",
                            "value" =>$options['from_ts']);
    }

    if (!is_null($options['to_ts']) && $options['to_ts']!='' ) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " DATE(E.fromdate) <= '{$options['to_ts']}' ";
        $where .= " DATE(E.fromdate) <= :To_ts ";
	$params[] = array(  "key" => ":To_ts",
                            "value" =>$options['to_ts']);
    }
    if (!is_null($options['current_date'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " DATE(E.fromdate) = '{$options['current_date']}' ";
        $where .= " DATE(E.fromdate) = :Current_date ";
	$params[] = array(  "key" => ":Current_date",
                            "value" =>$options['current_date']);
    }

    if (!is_null($options['whojoin'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " E.whojoin='{$options['whojoin']}' ";
        $where .= " E.whojoin=:Whojoin ";
	$params[] = array(  "key" => ":Whojoin",
                            "value" =>$options['whojoin']);
    }
    if (!is_null($options['limitnumber'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " E.limitnumber='{$options['limitnumber']}' ";
        $where .= " E.limitnumber=:Limitnumber ";
	$params[] = array(  "key" => ":Limitnumber",
                            "value" =>$options['limitnumber']);
        
    }
    if (!is_null($options['caninvite'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " E.caninvite='{$options['caninvite']}' ";
        $where .= " E.caninvite=:Caninvite ";
	$params[] = array(  "key" => ":Caninvite",
                            "value" =>$options['caninvite']);
    }
    if (!is_null($options['hideguests'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " E.hideguests='{$options['hideguests']}' ";
        $where .= " E.hideguests=:Hideguests ";
	$params[] = array(  "key" => ":Hideguests",
                            "value" =>$options['hideguests']);
    }
    if ($is_visible != -1) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " E.is_visible='{$options['is_visible']}' ";
        $where .= " E.is_visible=:Is_visible ";
	$params[] = array(  "key" => ":Is_visible",
                            "value" =>$options['is_visible']);
    }
    if (!is_null($options['search_string']) && $options['search_string']!='' ) {
        $options['search_string'] = strtolower($options['search_string']);
        if ($where != '')
            $where = " ( $where ) AND ";
        $search_strings = explode(' ', $options['search_string']);
        $wheres = array();
        $i=0;
        foreach ($search_strings as $search_string_loop) {
            $wheres[] = "(				
                    LOWER(E.name) LIKE :Wheres$i
            )";
            $params[] = array(  "key" => ":Wheres$i", "value" =>'%'.$search_string_loop.'%');
            $i++;
        }
        $where .= "( " . implode(' AND ', $wheres) . ")";
    }

    if ($where != '')
        $where .= ' AND ';
    $where .= " E.published=1 ";

    if (!is_null($options['status'])) {

        $cur_date = date('Y-m-d');
        $cur_time = date('H:i:s');

        if ($options['status'] == 0) {
            //past
            if ($where != '')
                $where .= " AND ";
//            $where .= " (E.todate < '$cur_date') OR (E.todate = '$cur_date' AND E.totime<'$cur_time') ";
            $where .= " (E.todate < :Cur_date) OR (E.todate = :Cur_date AND E.totime<:Cur_time) ";
            $params[] = array(  "key" => ":Cur_date",
                                "value" =>$cur_date);
            $params[] = array(  "key" => ":Cur_time",
                                "value" =>$cur_time);
        }else if ($options['status'] == 1) {
            //upcoming
            if ($where != '')
                $where .= " AND ";
//            $where .= " (E.fromdate > '$cur_date') OR (E.fromdate = '$cur_date' AND E.fromtime>'$cur_time') ";
            $where .= " (E.fromdate > :Cur_date) OR (E.fromdate = :Cur_date AND E.fromtime>:Cur_time) ";
            $params[] = array(  "key" => ":Cur_date",
                                "value" =>$cur_date);
            $params[] = array(  "key" => ":Cur_time",
                                "value" =>$cur_time);
        }else if ($options['status'] == 2) {
            //current
            if ($where != '')
                $where .= " AND ";
//            $where .= " ( (E.fromdate < '$cur_date') OR (E.fromdate = '$cur_date' AND E.fromtime<'$cur_time') ) ";
            $where .= " ( (E.fromdate < :Cur_date) OR (E.fromdate = :Cur_date AND E.fromtime<:Cur_time) ) ";
            $where .= " AND ";
//            $where .= " ( (E.todate > '$cur_date') OR (E.todate = '$cur_date' AND E.totime>'$cur_time') ) ";
            $where .= " ( (E.todate > :Cur_date) OR (E.todate = :Cur_date AND E.totime>:Cur_time) ) ";
            $params[] = array(  "key" => ":Cur_date",
                                "value" =>$cur_date);
            $params[] = array(  "key" => ":Cur_time",
                                "value" =>$cur_time);
        }else if ($options['status'] == 3) {
            //current + upcoming
            if ($where != '')
                $where .= " AND ";
//            $where .= " ( ( (E.fromdate < '$cur_date') OR (E.fromdate = '$cur_date' AND E.fromtime<'$cur_time') ) ";
            $where .= " ( ( (E.fromdate < :Cur_date) OR (E.fromdate = :Cur_date AND E.fromtime<:Cur_time) ) ";
            $where .= " AND ";
//            $where .= " ( (E.todate > '$cur_date') OR (E.todate = '$cur_date' AND E.totime>'$cur_time') ) ";
            $where .= " ( (E.todate > :Cur_date) OR (E.todate = :Cur_date AND E.totime>:Cur_time) ) ";
            $where .= " OR ";
//            $where .= " ( (E.fromdate > '$cur_date') OR (E.fromdate = '$cur_date' AND E.fromtime>'$cur_time') ) ) ";
            $where .= " ( (E.fromdate > :Cur_date) OR (E.fromdate = :Cur_date AND E.fromtime>:Cur_time) ) ) ";
            $params[] = array(  "key" => ":Cur_date",
                                "value" =>$cur_date);
            $params[] = array(  "key" => ":Cur_time",
                                "value" =>$cur_time);
        }
    }

    if ($where != '') {
        $where = "WHERE $where";
    }

    $orderby = $options['orderby'];
    $order='';
    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }


    $nlimit = '';

    if (!is_null($options['limit'])) {
        $nlimit = intval($options['limit']);
        $skip = intval($options['page']) * $nlimit + intval($options['skip']);
    }

    if ($options['n_results'] == false) {
        if (!is_null($options['owner_id'])) {
//            $query = "SELECT E.* 
//							FROM 
//								cms_channel_event AS E INNER JOIN cms_channel AS C ON E.channelid=C.id AND C.owner_id='{$options['owner_id']}' 
//							$where ORDER BY $orderby $order";
            $query = "SELECT E.* 
							FROM 
								cms_channel_event AS E INNER JOIN cms_channel AS C ON E.channelid=C.id AND C.owner_id=:Owner_id
							$where ORDER BY $orderby $order";
            $params[] = array(  "key" => ":Owner_id", "value" =>$options['owner_id']);
        } else {
            $query = "SELECT * FROM cms_channel_event AS E $where ORDER BY $orderby $order";
        }
        if (!is_null($options['limit'])) {
//            $query .= " LIMIT $skip, $nlimit";
            $query .= " LIMIT :Skip, :Nlimit";
            $params[] = array(  "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
            $params[] = array(  "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
        }

//        $ret = db_query($query);
//        if (!$ret || (db_num_rows($ret) == 0)) {
        
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
           
        if (!$select || ($ret == 0)) {
            return false;
        } else {
            $ret_arr = array();
//            while ($row = db_fetch_array($ret)) {
//                $ret_arr[] = $row;
//            }
            $ret_arr = $select->fetchAll();            
            return $ret_arr;
        }
    } else {
        if (!is_null($options['owner_id'])) {
//            $query = "SELECT COUNT(E.id) 
//							FROM 
//								cms_channel_event AS E INNER JOIN cms_channel AS C ON E.channelid=C.id AND C.owner_id='{$options['owner_id']}' 
//							$where";
            $query = "SELECT COUNT(E.id) 
							FROM 
								cms_channel_event AS E INNER JOIN cms_channel AS C ON E.channelid=C.id AND C.owner_id=:Owner_id 
							$where";
            $params[] = array(  "key" => ":Owner_id",
                                "value" =>$options['owner_id']);
        } else {
            $query = "SELECT
						COUNT(E.id)
					FROM cms_channel_event AS E $where";
        }

//        $ret = db_query($query);
//        $row = db_fetch_row($ret);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
	$row = $select->fetch();

        return $row[0];
    }


}

/**
 * delete the brochure for a given channel 
 * @param integer $channelid the channel id
 * @param integer $id brochure id to be deleted
 * @return boolean true|false depending on the success of the operation
 */
function unitDeleteChannelBrochure($channelid, $id) {


    global $dbConn;
    $params = array();  

    if (deleteMode() == TT_DEL_MODE_PURGE) {
//        $query = "DELETE FROM cms_channel_brochure where channelid='$channelid' AND id='$id' AND published=1";
        $query = "DELETE FROM cms_channel_brochure where channelid=:Channelid AND id=:Id AND published=1";
    } else if (deleteMode() == TT_DEL_MODE_FLAG) {
//        $query = "UPDATE cms_channel_brochure SET published=" . TT_DEL_MODE_FLAG . " WHERE channelid='$channelid' AND id='$id' AND published=1";
        $query = "UPDATE cms_channel_brochure SET published=" . TT_DEL_MODE_FLAG . " WHERE channelid=:Channelid AND id=:Id AND published=1";
    }
    $params[] = array(  "key" => ":Channelid", "value" =>$channelid);
    $params[] = array(  "key" => ":Id", "value" =>$id);

    newsfeedDeleteAll($id, SOCIAL_ENTITY_BROCHURE);

    //delete comments
    socialCommentsDelete($id, SOCIAL_ENTITY_BROCHURE);

    //delete likes
    socialLikesDelete($id, SOCIAL_ENTITY_BROCHURE);

    //delete shares
    socialSharesDelete($id, SOCIAL_ENTITY_BROCHURE);

    //delete ratings
    socialRatesDelete($id, SOCIAL_ENTITY_BROCHURE);

//    return db_query($query);
    $delete_update = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete_update,$params);
    $res    = $delete_update->execute();
    return $res;
}

/**
 * gets the brochure info given its id
 * @param integer $id the cms_channel_brochure record id
 * @return array | false the cms_channel_brochure record or false if not found
 */
function channelBrochureInfo($id) {


    global $dbConn;
    $channelBrochureInfo = tt_global_get('channelBrochureInfo');      //Added By Devendra
    $params = array();
    if(isset($channelBrochureInfo[$id]) && $channelBrochureInfo[$id]!=''){
        return $channelBrochureInfo[$id];
    }
    //$query = "SELECT * FROM cms_channel_brochure WHERE id=:Id AND published=1";  //Hide by Devendra
    $query = "SELECT `id`, `channelid`, `name`, `photo`, `pdf`, `create_ts`, `published`, `like_value`, `nb_shares`, `is_visible`, `enable_share_comment`, `nb_comments` FROM `cms_channel_brochure` WHERE id=:Id AND published=1";  // Added by Devendra
    $params[] = array(  "key" => ":Id", "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
            $channelBrochureInfo[$id] =   $row;
            return $row;
    } else {
            $channelBrochureInfo[$id] =   false;
            return false;
    }


}

/**
 * gets the brochure for a given channel 
 * @param integer $channelid the channel id
 * @return array | false the cms_channel_brochure record or false if not found
 */
function unitGetChannelBrochure($channelid, $id) {


    global $dbConn;
    $params = array();
//    $query = "SELECT * FROM cms_channel_brochure WHERE channelid='$channelid' AND id='$id' AND published=1";
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
//        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
//        return $ret_arr;
//    } else {
//        return false;
//    }
    $query = "SELECT * FROM cms_channel_brochure WHERE channelid=:Channelid AND id=:Id AND published=1";
    $params[] = array(  "key" => ":Channelid",
                        "value" =>$channelid);
    $params[] = array(  "key" => ":Id",
                        "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $ret_arr = $select->fetchAll();
        return $ret_arr;
    } else {
        return false;
    }


}

/**
 * gets the brochure for a given channel 
 * @param integer $channelid the channel id
 * @return array | false the cms_channel_brochure record or false if not found
 */
function GetChannelBrochure($channelid) {


    global $dbConn;
    $params = array();
//    $query = "SELECT * FROM cms_channel_brochure WHERE channelid='$channelid' AND published=1";
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
//        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
//        return $ret_arr;
//    } else {
//        return false;
//    }
    $query = "SELECT * FROM cms_channel_brochure WHERE channelid=:Channelid AND published=1";
    $params[] = array(  "key" => ":Channelid",
                        "value" =>$channelid);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $ret_arr = $select->fetchAll();
        return $ret_arr;
    } else {
        return false;
    }


}

/**
 * gets the event info of a channel depending on search criteria. options include:<br/>
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>id</b>: the brochure id. default null<br/>
 * <b>channelid</b>: the channel's id. default null<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>name</b>: the channel's name. default null.<br/>
 * <b>photo</b>: searches for this photo, default null<br/>
 * <b>pdf</b>:  searches for this pdf, default null.<br/>
 * <b>is_visible</b>: is visible or not. default = -1 => doenst matter.<br/>
 * <b>location</b>: the event location. default null.<br/>
 * <b>search_string</b>: the search string. default null<br/>
 * <b>from_ts</b>: get brochures after this date. default null<br/>
 * <b>to_ts</b>: get brochures before this date. default null<br/>
 * <b>n_results</b>: true => gets the count, false=>gets the list. default false<br/>
 * @param array $srch_options 
 * @return array | false an array of 'cms_channel_brochure' records or false if none found.
 */
function channelbrochureSearch($srch_options) {


    global $dbConn;
    $params = array();  
    $default_opts = array(
        'limit' => 100,
        'page' => 0,
        'id' => null,
        'channelid' => null,
        'name' => null,
        'photo' => null,
        'pdf' => null,
        'is_visible' => -1,
        'search_string' => null,
        'strict_search' => 1,
        'orderby' => 'id',
        'n_results' => false,
        'from_ts' => null,
        'to_ts' => null,
        'order' => 'a'
    );

    $options = array_merge($default_opts, $srch_options);

    $is_visible = $options['is_visible'];

    $where = '';

    if (!is_null($options['id'])) {
        if ($where != '')
            $where .= ' AND ';
        $where .= " id='{$options['id']}' ";
    }
    if (!is_null($options['channelid'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " channelid='{$options['channelid']}' ";
        $where .= " channelid=:Channelid ";
	$params[] = array(  "key" => ":Channelid",
                            "value" =>$options['channelid']);
    }
    if (!is_null($options['name'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " name='{$options['name']}' ";
        $where .= " name=:Name ";
	$params[] = array(  "key" => ":Name",
                            "value" =>$options['name']);
    }
    if (!is_null($options['photo'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " photo={$options['photo']} ";
        $where .= " photo=:Photo ";
	$params[] = array(  "key" => ":Photo",
                            "value" =>$options['photo']);
    }
    if (!is_null($options['pdf'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " pdf='{$options['pdf']}' ";
        $where .= " pdf=:Pdf ";
	$params[] = array(  "key" => ":Pdf",
                            "value" =>$options['pdf']);
    }
    if (!is_null($options['from_ts'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " DATE(create_ts) >= '{$options['from_ts']}' ";
        $where .= " DATE(create_ts) >= :From_ts ";
	$params[] = array(  "key" => ":From_ts",
                            "value" =>$options['from_ts']);
    }

    if (!is_null($options['to_ts'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " DATE(create_ts) <= '{$options['to_ts']}' ";
        $where .= " DATE(create_ts) <= :To_ts ";
	$params[] = array(  "key" => ":To_ts",
                            "value" =>$options['to_ts']);
    }
    if ($is_visible != -1) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " is_visible='{$options['is_visible']}' ";
        $where .= " is_visible=:Is_visible ";
	$params[] = array(  "key" => ":Is_visible",
                            "value" =>$options['is_visible']);
    }
    if (!is_null($options['search_string'])) {
        $options['search_string'] = strtolower($options['search_string']);
        if ($where != '')
            $where = " ( $where ) AND ";
        $search_strings = explode(' ', $options['search_string']);
        $wheres = array();
        $i=0;
        foreach ($search_strings as $search_string_loop) {
            $wheres[] = "(			
                    LOWER(name) LIKE :Wheres$i
            )";
            $params[] = array(  "key" => ":Wheres$i", "value" =>'%'.$search_string_loop.'%');
            $i++;
        }
        $where .= "( " . implode(' AND ', $wheres) . ")";
    }
    if ($where != '')
        $where .= " AND ";
    $where .= " published=1 ";

    if ($where != '') {
        $where = "WHERE $where";
    }

    $orderby = $options['orderby'];
    $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';

    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit;


    if ($options['n_results'] == false) {
//        $query = "SELECT * FROM cms_channel_brochure $where ORDER BY $orderby $order LIMIT $skip, $nlimit";
        $query = "SELECT * FROM cms_channel_brochure $where ORDER BY $orderby $order LIMIT :Skip, :Nlimit";
	$params[] = array(  "key" => ":Skip",
                            "value" =>$skip,
                            "type" =>"::PARAM_INT");
	$params[] = array(  "key" => ":Nlimit",
                            "value" =>$nlimit,
                            "type" =>"::PARAM_INT");
//        $ret = db_query($query);
//        if (!$ret || (db_num_rows($ret) == 0)) {
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
        if (!$res || ($ret == 0)) {
            return false;
        } else {
            $ret_arr = array();
//            while ($row = db_fetch_array($ret)) {
//                $ret_arr[] = $row;
//            }
            $ret_arr = $select->fetchAll();
            return $ret_arr;
        }
    } else {
        $query = "SELECT COUNT(id) FROM cms_channel_brochure $where";
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
        $row = $select->fetch();
        return $row[0];
    }
}

/**
 * update Channel brochure for a given channel 
 * @param integer $channelid the channel id
 * @param string $namebrochure the brochure name
 * @param string $pdf the pdf link
 * @param string $brochurephotoStanInside the brochure cover photo
 * @return integer | false the newly inserted cms_channel_brochure id or false if not updated
 */
function UpdateChannelBrochure($channelid, $namebrochure, $pdf, $brochurephotoStanInside, $id) {
    global $dbConn;
    $params = array();  
    $query = "UPDATE cms_channel_brochure SET name=:Namebrochure,photo=:BrochurephotoStanInside,pdf=:Pdf WHERE channelid=:Channelid AND id=:Id";
    $params[] = array(  "key" => ":Namebrochure", "value" =>$namebrochure);
    $params[] = array(  "key" => ":BrochurephotoStanInside", "value" =>$brochurephotoStanInside);
    $params[] = array(  "key" => ":Pdf", "value" =>$pdf);
    $params[] = array(  "key" => ":Channelid", "value" =>$channelid);
    $params[] = array(  "key" => ":Id", "value" =>$id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    $ret    = $dbConn->lastInsertId();
    return ( $res ) ? $ret : false;
}

/**
 * edits a brochures info
 * @param array $brochure_info the new cms_channel_brochure info
 * @return boolean true|false if success|fail
 */
function channelBrochurelEdit($brochure_info) {
    global $dbConn;
    $params = array();
    $i = 0;
    $query = "UPDATE cms_channel_brochure SET ";
    foreach ($brochure_info as $key => $val) {
        if ($key != 'id' && $key != 'channelid') {
            $query .= " $key = :Val".$i.",";
            $params[] = array(  "key" => ":Val".$i,
                                "value" =>$val);
            $i++;
        }
    }
    $query = trim($query, ',');
//    $query .= " WHERE id='{$brochure_info['id']}' AND channelid='{$brochure_info['channelid']}'";
//    $ret = db_query($query);
//    return ( $ret ) ? true : false;
    $query .= " WHERE id=:Id AND channelid=:Channelid ";
    $params[] = array(  "key" => ":Id",
                        "value" =>$brochure_info['id']);
    $params[] = array(  "key" => ":Channelid",
                        "value" =>$brochure_info['channelid']);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return $res;


}

/**
 * edits a  channel event info
 * @param array $new_info the new cms_channel_event info
 * @return boolean true|false if success|fail
 */
function channelEventEdit($new_info) {
    global $dbConn;
    $params = array();
    $eventArray = channelEventInfo($new_info['id']);
    $locationupdate = false;
    $dateupdate = false;
    $timeupdate = false;
    $i = 0;
    $query = "UPDATE cms_channel_event SET ";

    foreach ($new_info as $key => $val) {
        if ($key != 'id' && $key != 'channelid') {
            if (($key == 'location' && $eventArray['location'] != $val) || ($key == 'location_detailed' && $eventArray['location_detailed'] != $val) || ($key == 'longitude' && $eventArray['longitude'] != $val) || ($key == 'lattitude' && $eventArray['lattitude'] != $val)) {
                $locationupdate = true;
            } else if (( $key == 'fromdate' && date('Y-m-d', strtotime($eventArray['fromdate'])) != date('Y-m-d', strtotime($val)) ) || ( $key == 'todate' && date('Y-m-d', strtotime($eventArray['todate'])) != date('Y-m-d', strtotime($val)) )) {
                $dateupdate = true;
            } else if (( $key == 'fromtime' && strtotime($eventArray['fromtime']) != strtotime($val) ) || ( $key == 'totime' && strtotime($eventArray['totime']) != strtotime($val) )) {
                $timeupdate = true;
            }
//            $query .= " $key = '$val',";
            $query .= " $key = :Val".$i.",";
            $params[] = array(  "key" => ":Val".$i,
                                "value" =>$val);
            $i++;
        }
    }

    $query = trim($query, ',');
//    $query .= " WHERE id='{$new_info['id']}' AND  channelid='{$new_info['channelid']}'";
    $query .= " WHERE id=:Id AND  channelid=:Channelid";
	$params[] = array(  "key" => ":Id",
                            "value" =>$new_info['id']);
	$params[] = array(  "key" => ":Channelid",
                            "value" =>$new_info['channelid']);
//    $ret = db_query($query);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $ret    = $update->execute();
    if ($ret) {        
        if ($locationupdate) {
            newsfeedAdd(userGetID(), $new_info['id'], SOCIAL_ACTION_UPDATE, $new_info['id'], SOCIAL_ENTITY_EVENTS_LOCATION, USER_PRIVACY_PUBLIC, $new_info['channelid']);
            sendEventUpdateEmails($new_info['id'], SOCIAL_ENTITY_EVENTS_LOCATION);
        }
        if ($dateupdate) {
            newsfeedAdd(userGetID(), $new_info['id'], SOCIAL_ACTION_UPDATE, $new_info['id'], SOCIAL_ENTITY_EVENTS_DATE, USER_PRIVACY_PUBLIC, $new_info['channelid']);
            sendEventUpdateEmails($new_info['id'], SOCIAL_ENTITY_EVENTS_DATE);
        }
        if ($timeupdate) {
            newsfeedAdd(userGetID(), $new_info['id'], SOCIAL_ACTION_UPDATE, $new_info['id'], SOCIAL_ENTITY_EVENTS_TIME, USER_PRIVACY_PUBLIC, $new_info['channelid']);
            sendEventUpdateEmails($new_info['id'], SOCIAL_ENTITY_EVENTS_TIME);
        }
    }
    return ( $ret ) ? true : false;
}

function sendEventUpdateEmails($id, $entity_type) {


    global $dbConn;
    $params = array(); 
//    $query_join = "SELECT * FROM cms_channel_event_join WHERE event_id='$id' AND published=1";
    $query_join = "SELECT * FROM cms_channel_event_join WHERE event_id=:Id AND published=1";
    $params[] = array(  "key" => ":Id",
                        "value" =>$id);

//    $res_join = db_query($query_join);
    $select = $dbConn->prepare($query_join);
    PDO_BIND_PARAM($select,$params);
    $res_join  = $select->execute();
    
    $ret_join = $select->rowCount();
//    if ($res_join && db_num_rows($res_join) != 0) {
    if ($res_join && $ret_join != 0) {
//        while ($row = db_fetch_array($res_join)) {
	$row = $select->fetchAll();
        foreach($row as $row_item){
            $j_id = $row_item['id'];
            sendChannelEmailNotification_Cancel_Event_Join($row_item['user_id'], $id, $entity_type);
        }
    }
    $sponsors = socialSharesGet($options = array(
        'entity_id' => $id,
        'entity_type' => SOCIAL_ENTITY_EVENTS,
        'share_type' => SOCIAL_SHARE_TYPE_SPONSOR,
        'limit' => null
    ));

    foreach ($sponsors as $sponsorsInfo) {
        $c_id = $sponsorsInfo['c_id'];
        $sp_id = $sponsorsInfo['sp_id'];
        sendChannelEmailNotification_Cancel_Event_Sponsor($c_id, $id, $entity_type);
    }


}

/**
 * add the events for a given channel 
 * @param integer $channelid the channel id
 * @param string $nameevents the event name
 * @param string $descriptionevent the event description
 * @param string $data_location the event location
 * @param string $locationevent the event location detailed
 * @param double $data_lng longitude
 * @param double $data_lat latitude
 * @param date $fromdate the event from date
 * @param time $fromdatetime the event from date time
 * @param date $todate the event to date
 * @param time $todatetime the event to date time
 * @param integer $whojoin the event 1 for every one and 0 for only connections
 * @param integer $guestevent the limit number of guests
 * @param integer $caninvite the value 1 if guest can invite and 0 if not
 * @param integer $showguests the value 1 show guests list and 0 hide them
 * @param integer $showsponsors the value 1 show sponsors list and 0 hide them
 * @param integer $allowsponsoring the value 1 allow sponsoring my event and 0 if not
 * @param integer $enablesharecomments the value 1 enable shares comments and 0 if not
 * @return integer | false the newly inserted cms_channel_event id or false if not inserted
 */
function AddChannelEvents($channelid, $nameevents, $descriptionevent, $data_location, $fromdate, $fromdatetime, $todate, $todatetime, $whojoin, $themephotoStanInside, $guestevent, $caninvite, $showguests, $showsponsors, $allowsponsoring, $enablesharecomments, $data_lng, $data_lat, $locationevent, $country = '') {
    global $dbConn;
    $params = array();
    $query = " INSERT INTO cms_channel_event (channelid,name,photo,description,location,country,location_detailed,longitude,lattitude,fromdate,fromtime,todate,totime,whojoin,limitnumber,caninvite,hideguests,showsponsors,allowsponsoring,enable_share_comment,published)
                VALUES (:Channelid,:Nameevents,:ThemephotoStanInside,:Descriptionevent,:Data_location,:Country,:Locationevent,:Data_lng,:Data_lat,:Fromdate,:Fromdatetime,:Todate,:Todatetime,:Whojoin,:Guestevent,:Caninvite,:Showguests,:Showsponsors,:Allowsponsoring,:Enablesharecomments,1)";    
	$params[] = array(  "key" => ":Channelid", "value" =>$channelid);
	$params[] = array(  "key" => ":Nameevents", "value" =>$nameevents);
	$params[] = array(  "key" => ":ThemephotoStanInside", "value" =>$themephotoStanInside);
	$params[] = array(  "key" => ":Descriptionevent", "value" =>$descriptionevent);
	$params[] = array(  "key" => ":Data_location", "value" =>$data_location);
	$params[] = array(  "key" => ":Country", "value" =>$country);
	$params[] = array(  "key" => ":Locationevent", "value" =>$locationevent);
	$params[] = array(  "key" => ":Data_lng", "value" =>$data_lng);
	$params[] = array(  "key" => ":Data_lat", "value" =>$data_lat);
	$params[] = array(  "key" => ":Fromdate", "value" =>$fromdate);
	$params[] = array(  "key" => ":Fromdatetime", "value" =>$fromdatetime);
	$params[] = array(  "key" => ":Todate", "value" =>$todate);
	$params[] = array(  "key" => ":Todatetime", "value" =>$todatetime);
	$params[] = array(  "key" => ":Whojoin", "value" =>$whojoin);
	$params[] = array(  "key" => ":Guestevent", "value" =>$guestevent);
	$params[] = array(  "key" => ":Caninvite", "value" =>$caninvite);
	$params[] = array(  "key" => ":Showguests", "value" =>$showguests);
	$params[] = array(  "key" => ":Showsponsors", "value" =>$showsponsors);
	$params[] = array(  "key" => ":Allowsponsoring", "value" =>$allowsponsoring);
	$params[] = array(  "key" => ":Enablesharecomments", "value" =>$enablesharecomments);
    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params);
    $res    = $insert->execute();
    $event_id    = $dbConn->lastInsertId();
    if ($event_id) {
        newsfeedAdd(userGetID(), $event_id, SOCIAL_ACTION_UPLOAD, $event_id, SOCIAL_ENTITY_EVENTS, USER_PRIVACY_PUBLIC, $channelid);
        return $event_id;
    } else {
        return false;
    }
}

/**
 * add the brochure for a given channel 
 * @param integer $channelid the channel id
 * @param string $namebrochure the brochure name
 * @param string $brochurephotoStanInside the brochure thumb
 * @param string $pdf the brochure pdf
 * @return integer | false the newly inserted cms_channel_brochure id or false if not inserted
 */
function AddChannelBrochure($channelid, $namebrochure, $brochurephotoStanInside, $pdf) {
    global $dbConn;
    $params = array();
    $query = "  INSERT INTO cms_channel_brochure (channelid,name,photo,pdf,published)
                VALUES (:Channelid,:Namebrochure,:BrochurephotoStanInside,:Pdf,1)";
    $params[] = array(  "key" => ":Channelid", "value" =>$channelid);
    $params[] = array(  "key" => ":Namebrochure", "value" =>$namebrochure);
    $params[] = array(  "key" => ":BrochurephotoStanInside", "value" =>$brochurephotoStanInside);
    $params[] = array(  "key" => ":Pdf", "value" =>$pdf);
    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params);
    $res    = $insert->execute();
    $brochure_id    = $dbConn->lastInsertId();
    if ($brochure_id) {
        newsfeedAdd(userGetID(), $brochure_id, SOCIAL_ACTION_UPLOAD, $brochure_id, SOCIAL_ENTITY_BROCHURE, USER_PRIVACY_PUBLIC, $channelid);
        return $brochure_id;
    } else {
        return false;
    }
}

/**
 * check the channel privacy extand for a given channel and user 
 * @param integer $channelid the channel id
 * @param integer $fromid the from id
 * @param integer $is_channel  0 | 1  0 if fromid is tuber and 1 if is channel
 * @param integer $privacy_type the desired privacy type (PRIVACY_EXTAND_TYPE_CONNECTIONS, PRIVACY_EXTAND_TYPE_SPONSORS, PRIVACY_EXTAND_TYPE_LOG OR PRIVACY_EXTAND_TYPE_EVENTJOIN)
 * @return true | false 
 */
function checkChannelPrivacyExtand($channelid, $privacy_type, $fromid, $is_channel) {


    global $dbConn;
    $params = array();  
//    $query = "SELECT * FROM cms_channel_privacy_extand WHERE channelid='$channelid' AND privacy_type='$privacy_type' AND published=1";
    $query = "SELECT * FROM cms_channel_privacy_extand WHERE channelid=:Channelid AND privacy_type=:Privacy_type AND published=1";
    $params[] = array(  "key" => ":Channelid",
                        "value" =>$channelid);
    $params[] = array(  "key" => ":Privacy_type",
                        "value" =>$privacy_type);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
    if ($res && $ret != 0) {
//        $row = db_fetch_array($ret);
        $row = $select->fetch();
        $kind_type_array = explode(',', $row['kind_type']);

        // user not logged
        if (intval($fromid) == 0) {
            if (intval($row['kind_type']) == PRIVACY_EXTAND_KIND_PUBLIC) {
                return true;
            } else {
                return false;
            }
        } else if (sizeof($kind_type_array) == 1 && $row['kind_type'] != '') {
            if (intval($row['kind_type']) == PRIVACY_EXTAND_KIND_PUBLIC) {
                return true;
            } else if (intval($row['kind_type']) == PRIVACY_EXTAND_KIND_PRIVATE) {
                return false;
            } else if (intval($row['kind_type']) == PRIVACY_EXTAND_KIND_CONNECTIONS) {
                $connectedTubers = getConnectedtubers($channelid);
                if (checkExistId($fromid, $connectedTubers) && $is_channel == 0) {
                    return true;
                } else {
                    if ($is_channel == 0) {
                        // for tuber
                        $check_array = explode(',', $row['connections']);
                    } else {
                        // for channel
                        $check_array = explode(',', $row['sponsors']);
                    }
                    return checkExistId($fromid, $check_array);
                }
            } else if (intval($row['kind_type']) == PRIVACY_EXTAND_KIND_SPONSORS) {
                $connected_channel = getSponsoredChannel($channelid);
                if (checkExistId($fromid, $connected_channel) && $is_channel == 1) {
                    return true;
                } else {
                    if ($is_channel == 0) {
                        // for tuber
                        $check_array = explode(',', $row['connections']);
                    } else {
                        // for channel
                        $check_array = explode(',', $row['sponsors']);
                    }
                    return checkExistId($fromid, $check_array);
                }
            } else {
                // custom
                if ($is_channel == 0) {
                    // for tuber
                    $check_array = explode(',', $row['connections']);
                } else {
                    // for channel
                    $check_array = explode(',', $row['sponsors']);
                }
                return checkExistId($fromid, $check_array);
            }
        } else {
            // sponsors + connections
            if ($is_channel == 0) {
                // for tuber
                $check_array = getConnectedtubers($channelid);
            } else {
                // for channel
                $check_array = getSponsoredChannel($channelid);
            }
            return checkExistId($fromid, $check_array);
        }
    } else {
        return true;
    }


}

function checkExistId($id, $array) {
    if (in_array($id, $array)) {
        return true;
    } else {
        return false;
    }
}

/**
 * gets the channel privacy extand for a given channel 
 * @param integer $channelid the channel id
 * @return array | false the cms_channel_privacy_extand record or false if not found
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  04/05/2015
//<start>
//function GetChannelPrivacyExtand($channelid) {
//    $query = "SELECT * FROM cms_channel_privacy_extand WHERE channelid='$channelid' AND published=1";
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
//        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
//        return $ret_arr;
//    } else {
//        return false;
//    }
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  04/05/2015
//<end>

/**
 * gets the channel privacy extand for a given channel . options include:<br/>
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>id</b>: privacy extand id<br/>
 * <b>channelid</b>: the channel's id. default null<br/>
 * @param integer $privacy_type the desired privacy type (PRIVACY_EXTAND_TYPE_CONNECTIONS, PRIVACY_EXTAND_TYPE_SPONSORS, PRIVACY_EXTAND_TYPE_LOG OR PRIVACY_EXTAND_TYPE_EVENTJOIN)
 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * @return array | false an array of 'cms_channel_privacy_extand' records or false if none found.
 */
function channelPrivacyExtandSearch($srch_options) {


    global $dbConn;
    $params = array();  
    $default_opts = array(
        'limit' => 100,
        'page' => 0,
        'id' => null,
        'channelid' => null,
        'privacy_type' => null,
        'orderby' => 'id',
        'n_results' => false,
        'order' => 'a'
    );

    $options = array_merge($default_opts, $srch_options);

    $where = '';

    if (!is_null($options['id'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " id='{$options['id']}' ";
        $where .= " id=:Id ";
	$params[] = array(  "key" => ":Id",
                            "value" =>$options['id']);
    }
    if (!is_null($options['channelid'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " channelid='{$options['channelid']}' ";
        $where .= " channelid=:Channelid ";
	$params[] = array(  "key" => ":Channelid",
                            "value" =>$options['channelid']);
    }
    if (!is_null($options['privacy_type'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " privacy_type='{$options['privacy_type']}' ";
        $where .= " privacy_type=:Privacy_type ";
	$params[] = array(  "key" => ":Privacy_type", "value" =>$options['privacy_type']);
    }

    if ($where != '')
        $where .= " AND ";
    $where .= " published=1 ";

    if ($where != '') {
        $where = "WHERE $where";
    }

    $orderby = $options['orderby'];
    $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';

    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit;


    if ($options['n_results'] == false) {
//        $query = "  SELECT *
//                    FROM cms_channel_privacy_extand 
//                    $where ORDER BY $orderby $order LIMIT $skip, $nlimit";
        $query = "  SELECT * FROM cms_channel_privacy_extand 
                    $where ORDER BY $orderby $order LIMIT :Skip, :Nlimit";
	$params[] = array(  "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
	$params[] = array(  "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");

//        $ret = db_query($query);
//        if (!$ret || (db_num_rows($ret) == 0)) {
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();        
        if (!$select || ($ret == 0)) {
            return false;
        } else {
            $media = array();
//            while ($row = db_fetch_array($ret)) {
//                $media[] = $row;
//            }
            $media = $select->fetchAll();
            return $media;
        }
    } else {
        $query = "  SELECT
		    COUNT(id)
		    FROM cms_channel_privacy_extand $where";

//        $ret = db_query($query);
//        $row = db_fetch_row($ret);
        
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
	$row = $select->fetch();

        return $row[0];
    }


}

/**
 * edits a channel privacy extand info
 * @param array $new_info the new cms_channel_privacy_extand info
 * @return boolean true|false if success|fail
 */
function channelPrivacyExtandEdit($new_info) {
    global $dbConn;
    $params = array();
    $query = "SELECT * FROM cms_channel_privacy_extand WHERE channelid=:Channelid AND privacy_type=:Privacy_type";
    $params[] = array(  "key" => ":Channelid", "value" =>$new_info['channelid']);
    $params[] = array(  "key" => ":Privacy_type", "value" =>$new_info['privacy_type']);
//    $ret = db_query($query);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($select && $ret != 0) {
        $params = array();
        $query = "UPDATE cms_channel_privacy_extand SET ";
        $i = 0;
        foreach ($new_info as $key => $val) {
            if ($key != 'id' && $key != 'channelid' && $key != 'privacy_type') {
                    $query .= " $key = :Val".$i.",";
                    $params[] = array(  "key" => ":Val".$i, "value" =>$val);
                    $i++;
            }
        }
        $query = trim($query, ',');
        $query .= " WHERE channelid=:Channelid AND privacy_type=:Privacy_type";
	$params[] = array(  "key" => ":Channelid", "value" =>$new_info['channelid']);
	$params[] = array(  "key" => ":Privacy_type", "value" =>$new_info['privacy_type']);
        
	$update = $dbConn->prepare($query);
	PDO_BIND_PARAM($update,$params);
        
	$res    = $update->execute();
        return ( $res ) ? true : false;
    } else {
        $params = array();
        $ret = AddchannelPrivacyExtand($new_info['channelid'], $new_info['privacy_type']);
        if ($ret) {
            $query = "UPDATE cms_channel_privacy_extand SET ";
            $i = 0;
            foreach ($new_info as $key => $val) {
                if ($key != 'id' && $key != 'channelid' && $key != 'privacy_type') {
                    $query .= " $key = :Val".$i.",";
                    $params[] = array(  "key" => ":Val".$i, "value" =>$val);
                    $i++;
                }
            }
            $query = trim($query, ',');
            $query .= " WHERE channelid=:Channelid AND privacy_type=:Privacy_type";
            $params[] = array(  "key" => ":Channelid", "value" =>$new_info['channelid']);
            $params[] = array(  "key" => ":Privacy_type", "value" =>$new_info['privacy_type']);
            $update = $dbConn->prepare($query);
            PDO_BIND_PARAM($update,$params);
            $res    = $update->execute();
            return ( $res ) ? true : false;
        } else {
            return false;
        }
    }
}

/**
 * insert sub channel relation
 * @param integer $channel_id the sub channel's id
 * @param integer $parent_id the parent channel's id
 * @return 1 inseted or 2 already exists or 0 if not found 
 */
function addChannelRelation($channel_id, $parent_id, $relation_type = 1) {
    global $dbConn;
    $params = array();
    $query = "SELECT * FROM cms_channel_relations WHERE channelid=:Channel_id AND parent_id=:Parent_id";
    $params[] = array(  "key" => ":Channel_id", "value" =>$channel_id);
    $params[] = array(  "key" => ":Parent_id", "value" =>$parent_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        return 2;
    } else {
        $params = array();
        $query = "INSERT INTO cms_channel_relations (channelid,parent_id,relation_type) VALUES (:Channel_id,:Parent_id,:Relation_type)";
	$params[] = array(  "key" => ":Channel_id", "value" =>$channel_id);
	$params[] = array(  "key" => ":Parent_id", "value" =>$parent_id);
	$params[] = array(  "key" => ":Relation_type", "value" =>$relation_type);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$ret    = $select->execute();
        if($ret){
            $notificationsArray = GetChannelNotifications($friend_id);
            $notificationsArray = $notificationsArray[0];

            if ($notificationsArray['email_subchannelrequest'] == 1 || count($notificationsArray) == 0) {
                $db_insert_id = $dbConn->lastInsertId();
                $channel_info=channelGetInfo($channel_id);
                $channel_info1=channelGetInfo($parent_id);
                
                $global_link= currentServerURL().'';
                $globArray = array();
                $case_val_array = array();
                $globArray['invite'] = array();
                if($relation_type==CHANNEL_RELATION_TYPE_PARENT){
                    $uinfo = getUserInfo($channel_info1['owner_id']);
                    $FullName1 = htmlEntityDecode($channel_info['channel_name']);
                    $FullName = htmlEntityDecode($channel_info1['channel_name']);
                    $channel_url = ReturnLink('channel/' . $channel_info['channel_url']);
                    $logo_src_action = ($channel_info['logo']) ? photoReturnchannelLogo($channel_info) : ReturnLink('/media/tubers/tuber.jpg');
                    $subject = "$FullName1 wants to be a sub channel on touristtube";
                    $case_val_array['partTitle'] = '<font face="Arial, Helvetica, sans-serif" size="2" color="#000000"> wants to be a sub channel</font>';
                }else{
                    $uinfo = getUserInfo($channel_info['owner_id']);
                    $logo_src_action = ($channel_info1['logo']) ? photoReturnchannelLogo($channel_info1) : ReturnLink('/media/tubers/tuber.jpg');
                    $FullName1 = htmlEntityDecode($channel_info1['channel_name']);
                    $FullName = htmlEntityDecode($channel_info['channel_name']);
                    $channel_url = ReturnLink('channel/' . $channel_info1['channel_url']);
                    $subject = "$FullName1 wants to add you as a sub channel on touristtube";
                    $case_val_array['partTitle'] = '<font face="Arial, Helvetica, sans-serif" size="2" color="#000000"> wants to add you as a sub channel</font>';
                }
                $globArray['ownerName'] = $FullName;
                $globArray['activateLink'] = ReturnLink('TT-confirmation/TSubChannel/'.md5($db_insert_id.''.$channel_id));
                $case_val_array['friends'] = array("0"=>array( $logo_src_action, $FullName1,$channel_url) );            
                if( $finfo[''] !=''){
                    $to_email = $finfo['otherEmail'];
                }else{
                    $to_email = $finfo['YourEmail'];
                }
                $globArray['invite'][] = $case_val_array;
                
                displayEmailSubChannelRequest( $to_email , $subject , $globArray , '' , '' , '' );
            }
            return 1;
        }else{
            return 0;
        }        
    }
}
function getRelationRequestMD5($ID) {
    global $dbConn;
    $params = array();
    $query = "SELECT * FROM `cms_channel_relations` WHERE MD5(concat(id,channelid))=:Id AND published=0 ";
    $params[] = array(  "key" => ":Id", "value" =>$ID);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        return $row;
    } else {
        return false;
    }
}
/**
 * gets the channel relation info 
 * @param integer $channel_id the sub channel's id
 * @param integer $parent_id the parent channel's id
 * @return array | false the cms_channel_relations record or null if not found
 */
function channelRelationInfo($channel_id, $parent_id) {


    global $dbConn;
    $params = array();  
//    $query = "SELECT * FROM cms_channel_relations WHERE channelid='$channel_id' AND parent_id='$parent_id'";
//
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
//        $row = db_fetch_assoc($ret);
//        return $row;
//    } else {
//        return false;
//    } 
    $query = "SELECT * FROM cms_channel_relations WHERE channelid=:Channel_id AND parent_id=:Parent_id";
    $params[] = array(  "key" => ":Channel_id",
                        "value" =>$channel_id);
    $params[] = array(  "key" => ":Parent_id",
                        "value" =>$parent_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        return $row;
    } else {
        return false;
    }


}

/**
 * gets the channel relation row 
 * @param integer $id which row
 * @return array|false the row if found or false if not
 */
function channelRelationRow($id) {


    global $dbConn;
    $channelRelationRow = tt_global_get('channelRelationRow');   //Added by Devendra on 25th may 2015
    $params = array();

    if(isset($channelRelationRow[$id]) && $channelRelationRow[$id]!=''){
        return $channelRelationRow[$id];
    }
    //$query = "SELECT * FROM cms_channel_relations WHERE id=:Id"; commented by rishav chhajer on 22 may 2015
    $query = "SELECT `id`, `channelid`, `parent_id`, `relation_type`, `create_ts`, `published` FROM `cms_channel_relations` WHERE id=:Id";
    $params[] = array(  "key" => ":Id", "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($ret) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        
            $channelRelationRow[$id] =   $row;
            return $row;
    } else {
            $channelRelationRow[$id] =   false;
            return false;
    }


}

/**
 * get sub channel relation
 * @param integer limit - limit of record to return. default null
 * @param integer channel_id the sub channel's id
 * @param integer parent_id the parent channel's id
 * @param integer relation_type the relation type , 1 for parent , 2 for sub and -1 it doesnt matter
 * @return array or false if not found 
 */
function getChannelRelation($srch_options) {


    global $dbConn;
    $params = array();  
    $default_opts = array(
        'limit' => null,
        'page' => 0,
        'channel_id' => null,
        'parent_id' => null,
        'relation_type' => -1,
        'published' => -1,
        'from_ts' => null,
        'to_ts' => null,
        'distinct_user' => 0,
        'escape_user' => null,
        'order' => 'a',
        'n_results' => false,
        'orderby' => 'CR.id'
    );
    $options = array_merge($default_opts, $srch_options);
    $orderby = $options['orderby'];
    $order = '';
    $nlimit_str = '';
    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        if ($orderby != 'CR.id' && $orderby != 'CH.id')
            $orderby = "LOWER(" . $options['orderby'] . ")";
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }
    if (!is_null($options['limit'])) {
        $nlimit = intval($options['limit']);
        $skip = intval($options['page']) * $nlimit;
//        $nlimit_str = "LIMIT $skip, $nlimit";
        $nlimit_str = "LIMIT :Skip, :Nlimit";
	$params[] = array(  "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
	$params[] = array(  "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
    }

    $where = '';
    if (!is_null($options['channel_id'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " CR.channelid='{$options['channel_id']}' ";
        $where .= " CR.channelid=:Channel_id ";
	$params[] = array(  "key" => ":Channel_id", "value" =>$options['channel_id']);
    }
    if (!is_null($options['parent_id'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " CR.parent_id='{$options['parent_id']}' ";
        $where .= " CR.parent_id=:Parent_id ";
	$params[] = array(  "key" => ":Parent_id", "value" =>$options['parent_id']);
    }
    if ($options['relation_type'] != -1) {
        if ($where != '') $where .= ' AND ';;
        //$where .= " CR.relation_type='{$options['relation_type']}' ";
        $where .= " CR.relation_type=:Relation_type ";
	$params[] = array(  "key" => ":Relation_type", "value" =>$options['relation_type']);
    }
    if (!is_null($options['from_ts'])) {
        if ($where != '') $where .= " AND ";
//        $where .= " DATE(CR.create_ts) >= '{$options['from_ts']}' ";
        $where .= " DATE(CR.create_ts) >= :From_ts ";
	$params[] = array(  "key" => ":From_ts", "value" =>$options['from_ts']);
    }
    if (!is_null($options['to_ts'])) {
        if ($where != '') $where .= " AND ";
//        $where .= " DATE(CR.create_ts) <= '{$options['to_ts']}' ";
        $where .= " DATE(CR.create_ts) <= :To_ts ";
	$params[] = array(  "key" => ":To_ts", "value" =>$options['to_ts']);
    }
    if ($options['published'] != -1) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " CR.published='{$options['published']}' ";
        $where .= " CR.published=:Published ";
	$params[] = array(  "key" => ":Published", "value" =>$options['published']);
    }
    if(!is_null($options['escape_user'])){
        if( $where != '') $where .= " AND ";
        if (!is_null($options['channel_id'])) {
//           $where .= " CR.parent_id NOT IN({$options['escape_user']}) ";
            $where .= " NOT find_in_set(cast(CR.parent_id as char), :Escape_user) ";
            $params[] = array(  "key" => ":Escape_user", "value" =>$options['escape_user']);
        }else{
//            $where .= " CR.channelid NOT IN({$options['escape_user']}) ";
            $where .= " NOT find_in_set(cast(CR.channelid as char), :Escape_user) ";
            $params[] = array(  "key" => ":Escape_user", "value" =>$options['escape_user']);
        }        
    }
    if ($options['n_results'] == false) {
        $query = "SELECT *, CR.id as rid
                          FROM cms_channel_relations AS CR";
        if (!is_null($options['channel_id'])) {
            $query .= " INNER JOIN cms_channel AS CH ON CH.id=CR.parent_id ";
        } else if (!is_null($options['parent_id'])) {
            $query .= " INNER JOIN cms_channel AS CH ON CH.id=CR.channelid ";
        }        
        if( $options['distinct_user']==1 ){
            if (!is_null($options['channel_id'])) {
                $query .= "WHERE $where GROUP BY CR.parent_id ORDER BY $orderby $order $nlimit_str";
            } else if (!is_null($options['parent_id'])) {
                $query .= "WHERE $where GROUP BY CR.channelid ORDER BY $orderby $order $nlimit_str";
            }
        }else{
            $query .= "WHERE $where ORDER BY $orderby $order $nlimit_str";
        }

	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
//        if ($ret && db_num_rows($ret) != 0) {
        if ($res && $ret != 0) {
            $ret_arr = array();
//            while ($row = db_fetch_array($ret)) {
//                $ret_arr[] = $row;
//            }
            $ret_arr=$select->fetchAll();
            return $ret_arr;
        } else {
            return false;
        }
    } else {
        if( $options['distinct_user']==1 ){
            if (!is_null($options['channel_id'])) {
                $query = "SELECT COUNT( DISTINCT CR.parent_id ) FROM cms_channel_relations AS CR";
            } else if (!is_null($options['parent_id'])) {
                $query = "SELECT COUNT( DISTINCT CR.channelid ) FROM cms_channel_relations AS CR";
            }
        }else{
            $query = "SELECT COUNT(CR.id) FROM cms_channel_relations AS CR";           
        }
        if (!is_null($options['channel_id'])) {
            $query .= " INNER JOIN cms_channel AS CH ON CH.id=CR.parent_id ";
        } else if (!is_null($options['parent_id'])) {
            $query .= " INNER JOIN cms_channel AS CH ON CH.id=CR.channelid ";
        }
        $query .= "WHERE $where";
        //return $query;
//        $ret = db_query($query);
//        $row = db_fetch_row($ret);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
	$row = $select->fetch();

        return $row[0];
    }


}

/**
 * edits a channel relation info
 * @param array $news_info the new cms_channel_relations info
 * @return boolean true|false if success|fail
 */
function channelRelationEdit($news_info) {


    global $dbConn;
    $params = array();  
    $query = "UPDATE cms_channel_relations SET ";
    $i =0 ;
    foreach ($news_info as $key => $val) {
    
        if ($key != 'id' && $key != 'channelid' && $key != 'parent_id') {
//            $query .= " $key = '$val',";
            
            $query .= " $key = :Val".$i.",";
            $params[] = array(  "key" => ":Val".$i,
                                "value" =>$val);
            $i++;
        }
    }

    $query = trim($query, ',');
//    $query .= " WHERE channelid='{$news_info['channelid']}' AND parent_id='{$news_info['parent_id']}'";
//    $ret = db_query($query);
    $query .= " WHERE channelid=:Channelid AND parent_id=:Parent_id";
    $params[] = array(  "key" => ":Channelid",
                        "value" =>$news_info['channelid']);
    $params[] = array(  "key" => ":Parent_id",
                        "value" =>$news_info['parent_id']);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();

    $ret    = $update->rowCount();
    if ($ret) {
        return true;
    } else {
        return false;
    }


}

/**
 * get parent channel relation list
 * @param string $channel_id_list the list of channel's id
 * @return array or false if not found 
 */
function getParentChannelRelationList($channel_id_list, $published = '1', $channel_id_list_old = '') {


    global $dbConn;
    $params = array();
    $query = "SELECT GROUP_CONCAT( DISTINCT parent_id SEPARATOR ',' ) AS parent_id FROM cms_channel_relations WHERE channelid IN($channel_id_list) AND published IN($published) AND EXISTS ( SELECT c.id FROM cms_channel AS c WHERE c.id = parent_id AND c.published IN($published) )";
    
//    $ret = db_query($query);
    
    $select = $dbConn->prepare($query);
    $res    = $select->execute();

    $ret    = $select->rowCount();
        
    if ($res && $ret != 0) {
//        $row = db_fetch_array($ret);
        $row = $select->fetch();
        
        if ($row['parent_id'] != '') {
            $channel_id_list_new = '';
            if ($channel_id_list_old != '')
                $channel_id_list_new = $channel_id_list_old . ',';
            $channel_id_list_new .= $row['parent_id'];

            return getParentChannelRelationList($row['parent_id'], $published, $channel_id_list_new);
        }else {
            return $channel_id_list_old;
        }
    } else {
        return $channel_id_list_old;
    }


}

/**
 * get sub channel relation list
 * @param string $channel_id_list the list of channel's id
 * @return array or false if not found 
 */
function getSubChannelRelationList($channel_id_list, $published = '1', $channel_id_list_old = '') {


    global $dbConn;
    $params = array(); 
    $query = "SELECT GROUP_CONCAT( DISTINCT channelid SEPARATOR ',' ) AS channelid FROM cms_channel_relations WHERE parent_id IN($channel_id_list) AND published IN($published)";
//    $ret = db_query($query);
    
    $select = $dbConn->prepare($query);
    
    $res    = $select->execute();

    $ret    = $select->rowCount();
//    if ($ret && db_num_rows($ret) != 0) {
    if ($res && $ret != 0) {
//        $row = db_fetch_array($ret);
	$row = $select->fetch();        
        if ($row['channelid'] != '') {
            $channel_id_list_new = '';
            if ($channel_id_list_old != '')
                $channel_id_list_new = $channel_id_list_old . ',';
            $channel_id_list_new .= $row['channelid'];

            return getSubChannelRelationList($row['channelid'], $published, $channel_id_list_new);
        }else {
            return $channel_id_list_old;
        }
    } else {
        return $channel_id_list_old;
    }


}

/**
 * Remove the parent channel for a given channel 
 * @param integer $channel_id the sub channel id
 * @param integer $parent_id the parent channel id
 * @return array | false the cms_channel_relations record or false if not found
 */
function channelRelationDelete($channel_id, $parent_id,$relation_type) {


    global $dbConn;
    $params = array();  
    $channel_relation_row = channelRelationInfo($channel_id, $parent_id);
    if ($channel_relation_row) {
//        $query = "DELETE FROM cms_channel_relations where channelid='$channel_id' AND parent_id='$parent_id'";       
        $query = "DELETE FROM cms_channel_relations where channelid=:Channel_id AND parent_id=:Parent_id";  
	$params[] = array(  "key" => ":Channel_id",
                            "value" =>$channel_id);
	$params[] = array(  "key" => ":Parent_id",
                            "value" =>$parent_id);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
        newsfeedDeleteByAction($channel_relation_row['id'], SOCIAL_ACTION_RELATION_PARENT);
        newsfeedDeleteByAction($channel_relation_row['id'], SOCIAL_ACTION_RELATION_SUB);        
//        return db_query($query);
        return $res;
    } else {
        return false;
    }


}

/**
 * insert the default channel privacy extand given the channel id
 * @param integer $channelid the desired channel's id
 * @param integer $privacy_type the desired privacy type (PRIVACY_EXTAND_TYPE_CONNECTIONS, PRIVACY_EXTAND_TYPE_SPONSORS, PRIVACY_EXTAND_TYPE_LOG OR PRIVACY_EXTAND_TYPE_EVENTJOIN)
 * @return true the cms_channel privacy record or false if not found 
 */
function AddchannelPrivacyExtand($channelid, $privacy_type) {
    global $dbConn;
    $params = array();
    $query = " INSERT INTO cms_channel_privacy_extand (channelid,privacy_type)
                VALUES (:Channelid,:Privacy_type)";
    $params[] = array(  "key" => ":Channelid", "value" =>$channelid);
    $params[] = array(  "key" => ":Privacy_type", "value" =>$privacy_type);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $ret    = $select->execute();
    return ( $ret ) ? true : false;
}

/**
 * gets the channel privacy for a given channel list
 * @param integer $channel_array list of channel id
 * @return array | false the cms_channel_privacy record or false if not found
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  04/05/2015
//<start>
//function GetMultiChannelNotifications($channel_array) {
//    $list_arr = array();
//    foreach ($channel_array as $channel_id) {
//        $list_arr[$channel_id] = GetChannelNotifications($channel_id);
//    }
//    return $list_arr;
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  04/05/2015
//<end>

/**
 * gets the channel privacy for a given channel 
 * @param integer $channelid the channel id
 * @return array | false the cms_channel_privacy record or false if not found
 */
function GetChannelNotifications($channelid) {
    global $dbConn;
    $params = array();  
    $query = "SELECT * FROM cms_channel_privacy WHERE channelid=:Channelid AND published=1";
    $params[] = array(  "key" => ":Channelid",
                        "value" =>$channelid);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $ret_arr = array();
        $ret_arr = $select->fetchAll();
        return $ret_arr;
    } else {
        if (AddchannelPrivacy($channelid)) {
            $params = array();
            $query = "SELECT * FROM cms_channel_privacy WHERE channelid=:Channelid AND published=1";
            $params[] = array(  "key" => ":Channelid", "value" =>$channelid);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();
            $ret    = $select->rowCount();
            if ($res && $ret != 0) {
                $ret_arr = array();
                $ret_arr = $select->fetchAll();
                return $ret_arr;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
/**
 * edits a channel privacy info
 * @param array $new_info the new cms_channel_privacy info
 * @return boolean true|false if success|fail
 */
function channelPrivacyEdit($new_info) {
    global $dbConn;
    $params = array();
    $query = "SELECT * FROM cms_channel_privacy WHERE channelid=:Channelid";
    $params[] = array(  "key" => ":Channelid", "value" =>$new_info['channelid']);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $i = 0;
        $params = array();
        $query = "UPDATE cms_channel_privacy SET ";
        foreach ($new_info as $key => $val) {
            if ($key != 'id' && $key != 'channelid') {
                $query .= " $key = :Val".$i.",";
                $params[] = array(  "key" => ":Val".$i,
                                    "value" =>$val);
                $i++;
            }
        }
        $query = trim($query, ',');
        $query .= " WHERE channelid=:Channelid";
        $params[] = array(  "key" => ":Channelid", "value" =>$new_info['channelid']);
        $select = $dbConn->prepare($query);
        PDO_BIND_PARAM($select,$params);
        $res    = $select->execute();
        return ( $res ) ? true : false;
    } else {
        $ret = AddchannelPrivacy($new_info['channelid']);
        if ($ret) {
            $i = 0 ;
            $params = array();
            $query = "UPDATE cms_channel_privacy SET ";
            foreach ($new_info as $key => $val) {
                if ($key != 'id' && $key != 'channelid') {
                $query .= " $key = :Val".$i.",";
                $params[] = array(  "key" => ":Val".$i, "value" =>$val);
                $i++;
                }
            }
            $query = trim($query, ',');
            $query .= " WHERE channelid=:Channelid";
            $params[] = array(  "key" => ":Channelid", "value" =>$new_info['channelid']);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();
            return ( $res ) ? true : false;
        } else {
            return false;
        }
    }
}
/**
 * insert the default channel privacy given the channel id
 * @param integer $channelid the desired channel's id
 * @return true the cms_channel_privacy record or false if not found 
 */
function AddchannelPrivacy($channelid) {
    global $dbConn;
    $params = array();    
    $query = "  INSERT INTO cms_channel_privacy (channelid)
                VALUES (:Channelid)";
    $params[] = array(  "key" => ":Channelid", "value" =>$channelid);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    return ( $res ) ? true : false;
}
/**
 * searchs for tubers a tuber is subscribed to (following). options include:<br/>
 * <b>limit</b>: integer - limit of record to return. default 6<br/>
 * <b>page</b>: integer - how many pages of result to skip. default 0<br/>
 * <b>skip</b>: integer - hadcoded number of records to skip. default 0<br/>
 * <b>channelid</b>: integer - the channel to search for. required<br/>
 * <b>begins</b>: user names begin with this letter <br/>
 * <b>search_string</b>: the search string. default null<br/>
 * <b>orderby</b>: string - the column to order the results by. default request_ts<br/>
 * <b>order</b>: char - either (a)scending or (d)esceniding. default (a)<br/>
 * <b>n_results</b>: returns the results or the number of results. default false
 * @param array $srch_options search options
 * @return array of result records
 */
function channelConnectedTubersSearch($srch_options) {

    /* if( isset($srch_options['ischannel']) && ($srch_options['ischannel']==1) ){
      return _channelConnectedTubersSearch_channel($srch_options);
      }else{ */
    return _channelConnectedTubersSearch_user($srch_options);
    //}
}

/**
 * primitive function to search for users
 * searchs for tubers a tuber is subscribed to (following). options include:<br/>
 * <b>limit</b>: integer - limit of record to return. default 6<br/>
 * <b>page</b>: integer - how many pages of result to skip. default 0<br/>
 * <b>skip</b>: integer - hadcoded number of records to skip. default 0<br/>
 * <b>channelid</b>: integer - the channel to search for. required<br/>
 * <b>begins</b>: user names begin with this letter <br/>
 * <b>search_string</b>: the search string. default null<br/>
 * <b>from_time</b>: start time default null<br/>
 * <b>to_time</b>: end time default null<br/>
 * <b>orderby</b>: string - the column to order the results by. default request_ts<br/>
 * <b>order</b>: char - either (a)scending or (d)esceniding. default (a)<br/>
 * <b>n_results</b>: returns the results or the number of results. default false
 * @param array $srch_options search options
 * @ignore
 * @return array of result records
 */
function _channelConnectedTubersSearch_user($srch_options) {


    global $dbConn;
    $params = array(); 
    $default_opts = array(
        'limit' => 18,
        'page' => 0,
        'dont_show' => 0,
        'channelid' => null,
        'begins' => null,
        'from_time' => null,
        'to_time' => null,
        'notify' => null,
        'published' => 1,
        'is_visible' => -1,
        'search_string' => null,
        'orderby' => 'create_ts',
        'order' => 'd',
        'n_results' => false,
        'skip' => 0
    );

    $options = array_merge($default_opts, $srch_options);

    $nlimit = '';
    if (!is_null($options['limit'])) {
        $nlimit = intval($options['limit']);
        $skip = intval($options['page']) * $nlimit + intval($options['skip']);
    }

    $order = '';
    $orderby = $options['orderby'];
    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }
    
    $is_visible = $options['is_visible'];

    $user_id = $options['channelid'];
    if (is_null($user_id)) {
        return array();
    }

    $where = '';

    if ($where != '')
        $where .= ' AND ';


    if (!is_null($options['begins'])) {
        if ($options['begins'] == '#') {
            if ($where != '')
                $where = " ( $where ) AND ";
            $where .= "( U.display_fullname=0 AND LOWER(U.YourUserName) REGEXP  '^[1-9]' )";
            //user names cant have numbers
        }else {
            if ($where != '')
                $where = " ( $where ) AND ";
//            $where .= "( 
//                            (
//                                    U.display_fullname=0
//                                    AND
//                                    LOWER(U.YourUserName) LIKE '{$options['begins']}%'
//                            )
//                            OR
//                            (
//                                    U.display_fullname=1
//                                    AND
//                                    (
//                                            LOWER(U.fname) LIKE '{$options['begins']}%' 
//                                            OR
//                                            LOWER(U.FullName) LIKE '{$options['begins']}%'
//                                    )
//                            )
//                    )";
            $where .= "( 
                            (
                                    U.display_fullname=0
                                    AND
                                    LOWER(U.YourUserName) LIKE :Begins
                            )
                            OR
                            (
                                    U.display_fullname=1
                                    AND
                                    (
                                            LOWER(U.fname) LIKE :Begins
                                            OR
                                            LOWER(U.FullName) LIKE :Begins
                                    )
                            )
                    )";
            $params[] = array(  "key" => ":Begins",
                                "value" =>$options['begins']."%");
            /* only search in firstname
             * OR
              LOWER(U.lname) LIKE '{$options['begins']}%'

             */
        }
    }

    if (!is_null($options['search_string']) && $options['search_string']!='') {
        $options['search_string'] = strtolower($options['search_string']);
        if ($where != '')
            $where = " ( $where ) AND ";
        $search_strings = explode(' ', $options['search_string']);
        $wheres = array();
        $i=0;
        foreach ($search_strings as $search_string_loop) {
            $wheres[] = "( 
                            (
                                    U.display_fullname=0
                                    AND
                                    LOWER(U.YourUserName) LIKE :Search_strings$i
                            )
                            OR
                            (
                                    U.display_fullname=1
                                    AND
                                    (
                                            LOWER(U.fname) LIKE :Search_strings$i 
                                            OR
                                            LOWER(U.lname) LIKE :Search_strings$i 
                                            OR
                                            LOWER(U.FullName) LIKE :Search_strings$i
                                    )
                            )
                    )";
            $params[] = array(  "key" => ":Search_strings$i", "value" =>'%'.$search_string_loop.'%' );
            $i++;
        }
        $where .= "( " . implode(' AND ', $wheres) . ")";
        //$where .= "(:Wheres)";
        //$params[] = array(  "key" => ":Wheres", "value" =>implode(' AND ', $wheres));
    }

    if ($where != '')
        $where .= " AND ";
//    $where .= " F.channelid='{$options['channelid']}' ";
    $where .= " F.channelid=:Channelid ";
    $params[] = array(  "key" => ":Channelid",
                        "value" =>$options['channelid']);
    if (!is_null($options["notify"])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " F.notify='{$options['notify']}' ";
        $where .= " F.notify=:Notify ";
        $params[] = array(  "key" => ":Notify",
                            "value" =>$options['notify']);
        
    }
    if ($options["dont_show"] != 0) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " F.id NOT IN (" . $options["dont_show"] . ") ";
        $where .= " NOT find_in_set(cast(F.userid as char), :Dont_show) ";
        $params[] = array(  "key" => ":Dont_show", "value" =>$options["dont_show"]);
    }
    if ($is_visible != -1) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " F.is_visible='{$options['is_visible']}' ";
        $where .= " F.is_visible=:Is_visible ";
        $params[] = array(  "key" => ":Is_visible",
                            "value" =>$options['is_visible']);
    }
    if (!is_null($options['from_time']) || !is_null($options['to_time'])) {
        if ($where != '') {
            $where = " ( " . $where . " ) ";
        }
        if (!is_null($options['from_time'])) {
            if ($where != '')
                $where .= " AND ";
//            $where .= " (F.create_ts) >= '{$options['from_time']}' ";
            $where .= " (F.create_ts) >= :From_time ";
            $params[] = array(  "key" => ":From_time",
                                "value" =>$options['from_time']);
        }
        if (!is_null($options['to_time'])) {
            if ($where != '')
                $where .= " AND ";
//            $where .= " (F.create_ts) <= '{$options['to_time']}' ";
            $where .= " (F.create_ts) <= :To_time ";
            $params[] = array(  "key" => ":To_time",
                                "value" =>$options['to_time']);
        }
    }
    if ($options['published'] == -1) {
        if ($where != '')
            $where .= " AND ";
        $where .= " F.published IN (-5,1)";
    }else {
        if ($where != '')
            $where .= " AND ";
//        $where .= " F.published= '{$options['published']}'";
        $where .= " F.published= :Published";
        $params[] = array(  "key" => ":Published", "value" =>$options['published']);
    }

    if ($options['n_results'] == false) {
        $query = "SELECT
                            U.*,F.*, U.id as uid
                    FROM
                            cms_channel_connections AS F
                            INNER JOIN cms_users AS U ON F.userid=U.id
                    WHERE $where ORDER BY $orderby $order";
        if (!is_null($options['limit'])) {
//            $query .= " LIMIT $skip, $nlimit";
            $query .= " LIMIT :Skip, :Nlimit";
            $params[] = array(  "key" => ":Skip",
                                "value" =>$skip,
                                "type" =>"::PARAM_INT");
            $params[] = array(  "key" => ":Nlimit",
                                "value" =>$nlimit,
                                "type" =>"::PARAM_INT");
        }

//        $ret = db_query($query);
        
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
        $media = array();

//        while ($row = db_fetch_array($ret)) {
        $row = $select->fetchAll();
        foreach($row as $row_item){
            if ($row_item['profile_Pic'] == '') {                
                $row_item['profile_Pic'] = 'he.jpg';
                if ($row_item['gender'] == 'F') {
                    $row_item['profile_Pic'] = 'she.jpg';
                }
            }
            $media[] = $row_item;
        }
        return $media;
    } else {
        $query = "  SELECT
                            COUNT(F.id)
                    FROM
                            cms_channel_connections AS F
                            INNER JOIN cms_users AS U ON F.userid=U.id
                    WHERE $where";

//        $ret = db_query($query);
//        $row = db_fetch_row($ret);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
        $row = $select->fetch();

        return $row[0];
    }


}

/**
 * primitive function to search for channels
 * searchs for channels (following) another channel. options include:<br/>
 * <b>limit</b>: integer - limit of record to return. default 6<br/>
 * <b>page</b>: integer - how many pages of result to skip. default 0<br/>
 * <b>skip</b>: integer - hadcoded number of records to skip. default 0<br/>
 * <b>channelid</b>: integer - the channel to search for. required<br/>
 * <b>begins</b>: user names begin with this letter <br/>
 * <b>search_string</b>: the search string. default null<br/>
 * <b>orderby</b>: string - the column to order the results by. default request_ts<br/>
 * <b>order</b>: char - either (a)scending or (d)esceniding. default (a)<br/>
 * <b>n_results</b>: returns the results or the number of results. default false
 * @param array $srch_options search options
 * @ignore
 * @return array of result records
 */
function _channelConnectedTubersSearch_channel($srch_options) {


    global $dbConn;
    $params = array(); 
    $default_opts = array(
        'limit' => 18,
        'page' => 0,
        'channelid' => null,
        'userid' => null,
        'begins' => null,
        'is_visible' => -1,
        'search_string' => null,
        'orderby' => 'create_ts',
        'order' => 'd',
        'n_results' => false,
        'skip' => 0
    );

    $options = array_merge($default_opts, $srch_options);

    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit + intval($options['skip']);

    $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    $orderby = $options['orderby'];

    $channel_id = $options['channelid'];
    $user_id = $options['userid'];
    $is_visible = $options['is_visible'];
    /* if( is_null($channel_id) ){
      return array();
      } */

    $where = '';

    if ($where != '')
        $where .= ' AND ';


    if (!is_null($options['begins'])) {
        if ($options['begins'] == '#') {
            if ($where != '')
                $where = " ( $where ) AND ";
            $where .= "( LOWER(C.channel_name) REGEXP  '^[1-9]' )";
            //user names cant have numbers
        }else {
            if ($where != '')
                $where = " ( $where ) AND ";
//            $where .= " LOWER(C.channel_name) LIKE '{$options['begins']}%' ";
            $where .= " LOWER(C.channel_name) LIKE :Begins ";
            $params[] = array(  "key" => ":Begins",
                                "value" =>$options['begins']."%");
        }
    }

    if (!is_null($options['search_string'])) {
        $options['search_string'] = strtolower($options['search_string']);
        if ($where != '')
            $where = " ( $where ) AND ";
        $search_strings = explode(' ', $options['search_string']);
        $wheres = array();
        $i=0;
        foreach ($search_strings as $search_string_loop) {
            $wheres[] = " LOWER(C.channel_name) LIKE :Wheres$i ";
            $params[] = array(  "key" => ":Wheres$i", "value" =>'%'.$search_string_loop.'%');
            $i++;
        }
        $where .= "( " . implode(' AND ', $wheres) . ")";
    }

    if (!is_null($channel_id)) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " F.channelid='{}' ";
        $where .= " F.channelid=:Channelid ";
        $params[] = array(  "key" => ":Channelid",
                            "value" =>$options['channelid']);
    }
    if (!is_null($user_id)) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " F.userid='{$options['userid']}' ";
        $where .= " F.userid=:Userid ";
        $params[] = array(  "key" => ":Userid",
                            "value" =>$options['userid']);
    }
    if ($is_visible != -1) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " F.is_visible='{$options['is_visible']}' ";
        $where .= " F.is_visible=:Is_visible ";
        $params[] = array(  "key" => ":Is_visible",
                            "value" =>$options['is_visible']);
    }

    if ($where != '')
        $where .= " AND ";
    $where .= " F.published='1' ";

    if ($orderby == 'create_ts')
        $orderby = "F.create_ts";

    if ($options['n_results'] == false) {
        if (!is_null($user_id)) {
            $query = "SELECT C.*,C.id AS c_id, F.is_visible, F.id AS connection_id
							FROM
							cms_channel_connections AS F INNER JOIN cms_channel AS C ON F.channelid=C.id";
        } else {
            $query = "SELECT C.*,C.id AS c_id,F.*
							FROM
							cms_channel_connections AS F INNER JOIN cms_channel AS C ON F.userid=C.id";
        }
//        $query .= " WHERE $where ORDER BY $orderby $order LIMIT $skip, $nlimit";
        $query .= " WHERE $where ORDER BY $orderby $order LIMIT :Skip, :Nlimit";
	$params[] = array(  "key" => ":Skip",
                            "value" =>$skip,
                            "type" =>"::PARAM_INT");
	$params[] = array(  "key" => ":Nlimit",
                            "value" =>$nlimit,
                            "type" =>"::PARAM_INT");

//        $ret = db_query($query);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
        $media = array();

//        while ($row = db_fetch_array($ret)) {
//            $media[] = $row;
//        }
        $media = $select->fetchAll();
        return $media;
    } else {
        $query = "SELECT

						COUNT(F.id)
					FROM
						cms_channel_connections AS F INNER JOIN cms_channel AS C ON";
        if (!is_null($user_id)) {
            $query .= " F.channelid=C.id";
        } else {
            $query .= " F.userid=C.id";
        }
        $query .= " WHERE $where";

//        $ret = db_query($query);
//        $row = db_fetch_row($ret);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
	$row = $select->fetch();

        return $row[0];
    }


}

/**
 * gets the connected tubers for a given channel 
 * @param integer $channelid the channel id
 * @return array | false the cms_channel_connections record or false if not found
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  04/05/2015
//<start>
/* function GetChannelConnectedTubers($channelid,$start=0,$limit=0){
  $query = "SELECT
 * FROM cms_channel_connections WHERE channelid='$channelid' AND published=1
  ";
  if($start==0 && $limit==0){
  $query = $query;
  }else{
  $query .= " LIMIT ".$start.", ".$limit;
  }
  $ret = db_query($query);
  if($ret && db_num_rows($ret)!=0 ){
  $ret_arr = array();
  while($row = db_fetch_array($ret)){
  $ret_arr[] = $row;
  }
  return $ret_arr;
  }else{
  return false;
  }
  } */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  04/05/2015
//<end>

/**
 * Remove the connected tuber for a given channel 
 * @param integer $channelid the channel id
 * @param integer $id the connection id
 * @return array | false the cms_channel_connections record or false if not found
 */
function RemoveChannelConnectedTuber($channelid, $id) {


    global $dbConn;
    $params = array(); 

    if (deleteMode() == TT_DEL_MODE_PURGE) {
//        $query = "DELETE FROM cms_channel_connections where channelid='$channelid' AND id='$id' AND published=1";
        $query = "DELETE FROM cms_channel_connections where channelid=:Channelid AND id=:Id AND published=1";
    } else if (deleteMode() == TT_DEL_MODE_FLAG) {
//        $query = "UPDATE cms_channel_connections SET published=" . TT_DEL_MODE_FLAG . " WHERE channelid='$channelid' AND id='$id' AND published=1";
        $query = "UPDATE cms_channel_connections SET published=" . TT_DEL_MODE_FLAG . " WHERE channelid=:Channelid AND id=:Id AND published=1";
    }
    
    $params[] = array(  "key" => ":Channelid",
                        "value" =>$channelid);
    $params[] = array(  "key" => ":Id",
                        "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
//    return db_query($query);
    return $res;


}

/**
 * Remove the sponsored channel for a given channel 
 * @param integer $userid the channel sponsor id
 * @param integer $id the connection id
 * @return array | false the cms_channel_connections record or false if not found
 */
function RemoveSponsoredChannel($userid, $id) {

    return socialShareDelete($id);
}

/**
 * returns the social networking image given the social network
 * @param $link {facbook,google,yahoo,...}
 * @return string
 */
function ReturnImageFromLink($link) {
    if (strpos($link, 'facebook')) {
        return ReturnLink('media/images/channel/f_icon_player.gif');
    } else if (strpos($link, 'google')) {
        return ReturnLink('media/images/channel/g_icon_player.gif');
    } else if (strpos($link, 'twitter')) {
        return ReturnLink('media/images/channel/t_icon_player.gif');
    } else if (strpos($link, 'yahoo')) {
        return ReturnLink('media/images/channel/y_icon_player.gif');
    } else {
        return ReturnLink('media/images/channel/w_icon_player.gif');
    }
}

/**
 * gets the channel given the owner
 * @param integer $userid the owner of the channel
 * @return array | false the cms_channel record or false if not found
 */
function channelFromUser($userid) {
    $opts = array(
        'limit' => 1,
        'public' => null,
        'owner_id' => $userid
    );

    $res = channelSearch($opts);

    if (!$res)
        return false;
    return $res[0];
}

/**
 * gets the channel record given the url
 * @param string $url the desired channel's url
 * @return array | false the cms_channel record or false if not found 
 */
function channelFromURL($url) {
    global $dbConn;
    $params = array();
    $query = "SELECT C.* FROM cms_channel AS C WHERE  C.published='1'  AND  LOWER(C.channel_url) LIKE LOWER(:Url)  ORDER BY C.id ASC ";
    $params[] = array(  "key" => ":Url", "value" =>$url);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0)) {
        return false;
    } else {
        $ret_arr = $select->fetchAll();
        return $ret_arr[0];
    }
}

/**
 * gets the channel record given the id
 * @param string $id the desired channel's id
 * @return array | false the cms_channel record or false if not found 
 */
function channelFromID($id) {
    $opts = array(
        'limit' => 1,
        'public' => null,
        'id' => $id
    );

    $res = channelSearch($opts);

    if (!$res)
        return false;
    return $res[0];
}

/**
 * check channel url if exist
 * @param string $channelurl the channel url
 * @param integer $channelid the channel's id
 * @return true | false true if channel url found or false if not found
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  04/05/2015
//<start>
//function checkChannelURL($channelurl, $channelid) {
//    $query = "SELECT * FROM cms_channel WHERE channel_url='$channelurl' AND id!='$channelid'";
//    //echo $query;
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
//        return true;
//    } else {
//        return false;
//    }
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  04/05/2015
//<end>

/**
 * Check if the channel URL is unique and provide a new, unique one if the one provided is already taken.
 * @param string $url the provided URL.
 * @return the same channel URL if it's unique, a new one if it is not unique.
 */
function channelUrlRename($url) {


    global $dbConn;
    $params = array();  
    $url = strtolower($url);
    $db_urls_arr = array();
    // Get if the channel URL is used.
//    $query = "SELECT
//					LOWER(channel_url) as channel_url
//				FROM cms_channel
//				WHERE
//					(LOWER(channel_url) LIKE '" . $url . "-%')
//					OR (LOWER(channel_url) = '" . $url . "')";
    $query = "  SELECT
                        LOWER(channel_url) as channel_url
                FROM cms_channel
                WHERE
                        (LOWER(channel_url) LIKE :Url1)
                        OR (LOWER(channel_url) = :Url2 )";
//    $ret = db_query($query);
    $params[] = array(  "key" => ":Url1", "value" =>$url."-%");
    $params[] = array(  "key" => ":Url2", "value" =>$url);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
//    while ($row = db_fetch_array($ret))
    $row = $select->fetchAll();
    foreach($row as $row_item){
        array_push($db_urls_arr, $row_item['channel_url']);
    }

    // If the original url is unique, return it.
    if (!in_array($url, $db_urls_arr))
        return $url;
    // If the original url already exists, try to make a new one.
    else {
        $i = 1;
        while ($i < 1000000) {
            $new_url = $url . '-' . $i;
            if (!in_array($new_url, $db_urls_arr))
                return $new_url;
            $i++;
        }
    }


}
/**
 * adds a channel
 * @param string $name the channel's name
 * @param string $url the channel's url
 * @param string $desc the channels description
 * @param string $header the channels description
 * @param string $bg the the background image
 * @param string $default_link a link to the channel website
 * @param string $slogan slogan
 * @param char(2) $country country code
 * @param integer $city webgeocities name
 * @param integer $city_id webgeocities id
 * @param string $street street address
 * @param string $zip_code zipcode
 * @param string $phone phoner number
 * @param string $category pointer to cms_channel_category record
 * @return integer | false the newly inserted cms_channel id or false if not inserted
 */
function channelAdd($user_id, $name='', $url='', $desc='', $header='', $bg='', $default_link='', $slogan='', $country='', $city_id=0, $city='', $street='', $zip_code='', $phone='', $category=0) {
    global $dbConn;
    $params = array(); 
    $url = channelUrlRename($url);
    $query = "  INSERT INTO cms_channel (owner_id,channel_name,channel_url,bg,default_link,country,city_id,city,street,zip_code,phone,category,published)
                VALUES (:User_id,:Name,:Url,:Bg,:Default_link,:Country,:City_id,:City,:Street,:Zip_code,:Phone,:Category,0)";
    $params[] = array(  "key" => ":User_id", "value" =>$user_id);
    $params[] = array(  "key" => ":Name", "value" =>$name);
    $params[] = array(  "key" => ":Url", "value" =>$url);
    $params[] = array(  "key" => ":Bg", "value" =>$bg);
    $params[] = array(  "key" => ":Default_link", "value" =>$default_link);
    $params[] = array(  "key" => ":Country", "value" =>$country);
    $params[] = array(  "key" => ":City_id", "value" =>$city_id);
    $params[] = array(  "key" => ":City", "value" =>$city);
    $params[] = array(  "key" => ":Street", "value" =>$street);
    $params[] = array(  "key" => ":Zip_code", "value" =>$zip_code);
    $params[] = array(  "key" => ":Phone", "value" =>$phone);
    $params[] = array(  "key" => ":Category", "value" =>$category);
    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params);
    $ret    = $insert->execute();
    if ($ret) {
        $channelid = $dbConn->lastInsertId();
        AddchannelPrivacy($channelid);
        if ($header != '') {
            channelEdit(array('id' => $channelid, 'cover_id' => $header));
        }
        if ($slogan != '') {
            channelEdit(array('id' => $channelid, 'slogan_id' => $slogan));
        }
        if ($desc != '') {
            channelEdit(array('id' => $channelid, 'info_id' => $desc));
        }
        
        return $channelid;
    } else {
        return false;
    }
}
/**
 * insert the channel detail for a given channel id
 * @param integer $channelid the desired channel's id
 * @param string $detail_text the detail text
 * @param integer $detail_type the detail type (cover or profile or slogan or info)
 * @return db_insert_id
 */
function AddChannelDetail($channelid, $detail_text, $detail_type) {
    global $dbConn;
    $params = array();
    $query = "  INSERT INTO cms_channel_detail (channelid,detail_text,detail_type)
                VALUES (:Channelid,:Detail_text,:Detail_type)";    
    $params[] = array(  "key" => ":Channelid", "value" =>$channelid);
    $params[] = array(  "key" => ":Detail_text", "value" =>$detail_text);
    $params[] = array(  "key" => ":Detail_type", "value" =>$detail_type);
    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params);
    $ret    = $insert->execute();
    $res    = $dbConn->lastInsertId();
    return ( $ret ) ? $res : 0;
}
/**
 * edits a channel detail info
 * @param array $news_info the new cms_channel_detail info
 * @return boolean true|false if success|fail
 */
function channelDetailEdit($news_info) {


    global $dbConn;
    $params = array();  
    $query = "UPDATE cms_channel_detail SET ";
    foreach ($news_info as $key => $val) {
        if ($key != 'id' && $key != 'channelid') {
//            $query .= " $key = '$val',";
            $query .= " $key = :Val".$i.",";
            $params[] = array(  "key" => ":Val".$i,
                                "value" =>$val);
            $i++;
        }
    }
    $query = trim($query, ',');
    $query .= " WHERE id=:Id AND channelid=:Channelid";
    $params[] = array(  "key" => ":Id", "value" =>$news_info['id']);
    $params[] = array(  "key" => ":Channelid", "value" =>$news_info['channelid']);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $ret    = $update->execute();
    return ( $ret ) ? true : false;
}
/**
 * gets user detail for a given user id 
 * @param integer $user_id user id
 * @return array the user detail list
 */
function getChannelDetailSearch($srch_options) {
    global $dbConn;
    $params = array();
    $default_opts = array(
        'limit' => 10,
        'page' => 0,
        'skip' => 0,
        'date_from' => null,
        'date_to' => null,
        'detail_type' => -1,
        'channelid' => NULL,
        'orderby' => 'id',
        'order' => 'a',
        'n_results' => false
    );

    $options = array_merge($default_opts, $srch_options);

    $nlimit = intval($options['limit']);
    $skip = (intval($options['page']) * $nlimit) + intval($options['skip']);

    $where = '';
    if (!is_null($options['channelid'])) {
        if ($where != '') $where .= " AND";
        $where .= " channelid=:Channelid";
        $params[] = array( "key" => ":Channelid", "value" =>$options['channelid']);
    }
    if ( $options['channelid']!=-1) {
        if ($where != '') $where .= " AND";
        $where .= " detail_type=:Detail_type";
        $params[] = array( "key" => ":Detail_type", "value" =>$options['detail_type']);
    }
    if (!is_null($options['date_from'])) {
        if ($where != '') $where .= ' AND ';
        $where .= " DATE(create_ts) >= :Date_from ";
        $params[] = array( "key" => ":Date_from", "value" =>$options['date_from']);
    }
    if (!is_null($options['date_to'])) {
        if ($where != '') $where .= ' AND ';
        $where .= " DATE(create_ts) <= :Date_to ";
        $params[] = array( "key" => ":Date_to", "value" =>$options['date_to']);
    }
    if ($where != '') $where .= " AND";
    $where .=" published=1";
    if ($options['n_results'] == false) {
        $orderby = $options['orderby'];
        $order='';
        if ($orderby == 'rand') {
            $orderby = "RAND()";
        } else {
            $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
        }
        $query = "SELECT * FROM cms_channel_detail WHERE $where ORDER BY $orderby $order LIMIT :Skip, :Nlimit";
        $params[] = array( "key" => ":Skip", "value" =>$skip,"type" =>"::PARAM_INT" );
        $params[] = array( "key" => ":Nlimit", "value" =>$nlimit,"type" =>"::PARAM_INT" );
        $select = $dbConn->prepare($query);
        PDO_BIND_PARAM($select,$params);
        $res    = $select->execute();

        $ret    = $select->rowCount();
        if (!$res || ($ret == 0)) {        
            return array();
        } else {
            $ret_arr = $select->fetchAll();
            return $ret_arr;
        }
    }else{
        $query = "SELECT COUNT(id) FROM `cms_channel_detail` WHERE $where";
        $select = $dbConn->prepare($query);
        PDO_BIND_PARAM($select,$params);
        $select->execute();
        
        $row = $select->fetch();
        
        $n_results = $row[0];
        return $n_results;
    }
}
/**
 * delete the channel detail profile image
 * @param integer $channel_id the channel id
 * @param integer $id profile image id to be deleted
 * @return boolean true|false depending on the success of the operation
 */
function channelDetailDelete($channel_id, $id, $entity_type) {
    global $dbConn;
    $params  = array();  
    $params2 = array();
    if (deleteMode() == TT_DEL_MODE_PURGE) {
        $query = "DELETE FROM cms_channel_detail where channelid=:Channel_id AND id=:Id AND published=1";
    } else if (deleteMode() == TT_DEL_MODE_FLAG) {
        $query = "UPDATE cms_channel_detail SET published=" . TT_DEL_MODE_FLAG . " WHERE channelid=:Channel_id AND id=:Id AND published=1";
    }
    newsfeedDeleteJoinByAction( $id , SOCIAL_ACTION_UPDATE , $entity_type );

    $query_user = "UPDATE `cms_channel` SET `logo`='',`profile_id`='' WHERE `profile_id`=:Profile_id AND id=:Id AND published=1";
    $params2[] = array( "key" => ":Id", "value" =>$channel_id);
    $params2[] = array( "key" => ":Profile_id", "value" =>$id);
    $select = $dbConn->prepare($query_user);
    PDO_BIND_PARAM($select,$params2);
    $select->execute();

    //delete comments
    socialCommentsDelete( $id , $entity_type );

    //delete likes
    socialLikesDelete( $id , $entity_type );
    
    $params[] = array( "key" => ":Channel_id", "value" =>$channel_id);
    $params[] = array( "key" => ":Id", "value" =>$id);
    $delete_update = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete_update,$params);
    $res    = $delete_update->execute();
    return $res;
}

/**
 * gets channel detail for a given channel id 
 * @param integer $channel_id the channel record
 * @return array the channel detail
 */
function GetChannelDetail($channel_id, $id) {
    global $dbConn;
    $params = array();
    $query = "SELECT * FROM cms_channel_detail where channelid = :Channel_id AND id=:Id";
    $params[] = array(  "key" => ":Channel_id",
                        "value" =>$channel_id);
    $params[] = array(  "key" => ":Id",
                        "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0)) {
        return false;
    } else {
        $ret_arr = $select->fetchAll();
        return $ret_arr[0];
    }
}

/**
 * gets channel detail for a given id 
 * @param integer $id the channel detail record
 * @return array the channel detail
 */
function GetChannelDetailInfo($id) {
    global $dbConn;
    $GetChannelDetailInfo   =   tt_global_get('GetChannelDetailInfo'); //Added by Devendra
    $params = array();
        if(isset($GetChannelDetailInfo[$id]) && $GetChannelDetailInfo[$id]!='')
        return $GetChannelDetailInfo[$id];
       //$query = "SELECT * FROM cms_channel_detail where id=:Id"; Commented by devendra on 22 may 2015 as told by rishav for optimizing query.
    $query = "SELECT `id`, `channelid`, `detail_text`, `detail_type`, `create_ts`, `like_value`, `nb_shares`, `nb_comments`, `enable_share_comment` FROM `cms_channel_detail` WHERE id=:Id";
 
    $params[] = array(  "key" => ":Id", "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0)) {
        return false;
    } else {
        $ret_arr = $select->fetch();
        return $ret_arr;
    }
}

/**
 * gets channel detail for a given id 
 * @param integer $id the channel detail record
 * @return array the channel detail
 */
function getChannelDetailMD5($id) {
//    Added by Anthony Malak 01-06-2015 for getting channel profile  
//    <start>
    global $dbConn;
    $params = array();
    $query = "SELECT * FROM cms_channel_detail where MD5(concat(id,channelid))=:Id AND published=1";
    $params[] = array(  "key" => ":Id", "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
 if (!$res || ($ret == 0)) {
    
            $GetChannelDetailInfo[$id] =   false;
        return false;
    } else {
        $ret_arr = $select->fetch();
            $GetChannelDetailInfo[$id] =   $ret_arr;
            return $ret_arr;
    }
    
    
    

//    Added by Anthony Malak 01-06-2015  for profile channel image next, prev
//    <end>
}

/**
 * gets Channel next previous profile picture
 * @param integer $id profile pic id 
 * @param String $next_prev argument for next or previous select
 * @return array the channel profile pic
 */
function getChannelNextPrevImage($id,$channelid,$next_prev,$channelImageType) {
//  Added by Anthony Malak 01-06-2015

    global $dbConn;
    $params = array();  
    
    if($next_prev == 'next'){
         $query = "SELECT * FROM cms_channel_detail where id > :Id and channelid =:Channelid and detail_type = :Detail_type AND published=1 order by id ASC LIMIT 1";
    }else{
         $query = "SELECT * FROM cms_channel_detail where id < :Id and channelid =:Channelid and detail_type = :Detail_type AND published=1 order by id DESC LIMIT 1";
    }
    
    $params[] = array( "key" => ":Id", "value" =>$id);
    $params[] = array( "key" => ":Channelid", "value" =>$channelid);
    $params[] = array( "key" => ":Detail_type", "value" =>$channelImageType);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    
    $ret    = $select->rowCount();
    if (!$res || $ret == 0 ) {
        return array();
    } else {
        $ret_arr    = $select->fetch(PDO::FETCH_ASSOC);
        return $ret_arr;
    }
//  Added by Anthony Malak 01-06-2015 to get the next channel image


}

/**
 * edits a channel
 * @param array $channel_info the new cms_channel info
 * @return boolean true|false if success or fail
 */
function channelEdit($channel_info) {


    global $dbConn;
    $params = array();  
    $channelArray = channelGetInfo($channel_info['id']);
    $profile_id = 0;
    $cover_id = 0;
    $slogan_id = 0;
    $info_id = 0;
    $coverupdate = false;
    $profileupdate = false;
    $sloganupdate = false;
    $infoupdate = false;

    $query = "UPDATE cms_channel SET ";
    foreach ($channel_info as $key => $val) {
        if ($key != 'id' && $key != 'owner_id') {
            if ($key == 'profile_id') {
                if ($channelArray['profile_id'] != 0) {
                    $channelDetailArray = GetChannelDetail($channel_info['id'], $channelArray['profile_id']);
                    if ($channelDetailArray && $channelDetailArray['detail_text'] != $val) {
//                        $query .= " `logo` = '$val',";
                        $query .= " `logo` = :Logo,";
                        $params[] = array(  "key" => ":Logo",
                                            "value" =>$val);
                        $profileupdate = true;
                        $profile_id = AddChannelDetail($channel_info['id'], $val, CHANNEL_DETAIL_PROFILE);
                    } else {
                        $profile_id = $channelArray['profile_id'];
                    }
                } else {
                    $query .= " `logo` =  :Logo2,";
                    $params[] = array(  "key" => ":Logo2",
                                        "value" =>$val);
                    $profileupdate = true;
                    $profile_id = AddChannelDetail($channel_info['id'], $val, CHANNEL_DETAIL_PROFILE);
                }
                $val = $profile_id;
            } else if ($key == 'cover_id') {
                if ($channelArray['cover_id'] != 0) {
                    $channelDetailArray = GetChannelDetail($channel_info['id'], $channelArray['cover_id']);
                    if ($channelDetailArray && $channelDetailArray['detail_text'] != $val) {
                        $query .= " `header` = :Header,";
                        $params[] = array(  "key" => ":Header",
                                            "value" =>$val);
                        $coverupdate = true;
                        $cover_id = AddChannelDetail($channel_info['id'], $val, CHANNEL_DETAIL_COVER);
                    } else {
                        $cover_id = $channelArray['cover_id'];
                    }
                } else {
                    $query .= " `header` = :Header2,";
                    $params[] = array(  "key" => ":Header2",
                                        "value" =>$val);
                    $coverupdate = true;
                    $cover_id = AddChannelDetail($channel_info['id'], $val, CHANNEL_DETAIL_COVER);
                }

                $val = $cover_id;
            } else if ($key == 'slogan_id') {
                if ($channelArray['slogan_id'] != 0) {
                    $channelDetailArray = GetChannelDetail($channel_info['id'], $channelArray['slogan_id']);
                    if ($channelDetailArray && $channelDetailArray['detail_text'] != $val) {
                        $query .= " `slogan` = :Slogan,";
                        $params[] = array(  "key" => ":Slogan",
                                            "value" =>$val);
                        $sloganupdate = true;
                        $slogan_id = AddChannelDetail($channel_info['id'], $val, CHANNEL_DETAIL_SLOGAN);
                    } else {
                        $slogan_id = $channelArray['slogan_id'];
                    }
                } else {
                    $query .= " `slogan` = :Slogan2,";
                    $params[] = array(  "key" => ":Slogan2",
                                        "value" =>$val);
                    $sloganupdate = true;
                    $slogan_id = AddChannelDetail($channel_info['id'], $val, CHANNEL_DETAIL_SLOGAN);
                }
                $val = $slogan_id;
            } else if ($key == 'info_id') {
                if ($channelArray['info_id'] != 0) {
                    $channelDetailArray_info = GetChannelDetail($channel_info['id'], $channelArray['info_id']);
                    if ($channelDetailArray_info && $channelDetailArray_info['detail_text'] != $val) {
                        $infoupdate = true;
                        $query .= " `small_description` = :Small_description,";
                        $params[] = array(  "key" => ":Small_description",
                                            "value" =>$val);
                        $info_id = AddChannelDetail($channel_info['id'], $val, CHANNEL_DETAIL_INFO);
                    } else {
                        $info_id = $channelArray['info_id'];
                    }
                } else {
                    $query .= " `small_description` = :Small_description2,";
                    $params[] = array(  "key" => ":Small_description2",
                                        "value" =>$val);
                    $infoupdate = true;
                    $info_id = AddChannelDetail($channel_info['id'], $val, CHANNEL_DETAIL_INFO);
                }
                $val = $info_id;
            } else if ($key == 'deactivated_ts' && $val == 0) {
                $val = NULL;  
                $query .= " $key = NULL,";
                continue;
            }
            $query .= " $key = '$val',";
        }
    }
    $query = trim($query, ',');
    $query .= " WHERE id=:Id";
    $params[] = array(  "key" => ":Id",
                        "value" =>$channel_info['id']);
    if (isset($channel_info['owner_id']) && $channel_info['owner_id'] != '') {
//        $query .= " AND owner_id='{$channel_info['owner_id']}'";
        $query .= " AND owner_id=:Owner_id";
        $params[] = array(  "key" => ":Owner_id",
                            "value" =>$channel_info['owner_id']);
    }
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $ret    = $update->execute();

    if ($ret) {
        if ($profileupdate) {
            newsfeedAdd($channelArray['owner_id'], $profile_id, SOCIAL_ACTION_UPDATE, $channel_info['id'], SOCIAL_ENTITY_CHANNEL_PROFILE, USER_PRIVACY_PUBLIC, $channel_info['id']);
        }
        if ($coverupdate) {
            newsfeedAdd($channelArray['owner_id'], $cover_id, SOCIAL_ACTION_UPDATE, $channel_info['id'], SOCIAL_ENTITY_CHANNEL_COVER, USER_PRIVACY_PUBLIC, $channel_info['id']);
        }
        if ($sloganupdate) {
            newsfeedAdd($channelArray['owner_id'], $slogan_id, SOCIAL_ACTION_UPDATE, $channel_info['id'], SOCIAL_ENTITY_CHANNEL_SLOGAN, USER_PRIVACY_PUBLIC, $channel_info['id']);
        }
        if ($infoupdate) {
            $channelArrayNew = GetChannelDetail($channel_info['id'], $info_id);
            if ($channelDetailArray_info['detail_text'] != $channelArrayNew['detail_text']) {
                newsfeedAdd($channelArray['owner_id'], $info_id, SOCIAL_ACTION_UPDATE, $channel_info['id'], SOCIAL_ENTITY_CHANNEL_INFO, USER_PRIVACY_PUBLIC, $channel_info['id']);
            }
        }
    }

    return ( $ret ) ? true : false;


}

function channelUpdateProfileImages($channel_info) {
    global $dbConn;
    $params = array();  
    $channelArray = channelGetInfo($channel_info['id']);
    $profile_id = 0;
    $cover_id = 0;
    $coverupdate = false;
    $profileupdate = false;

    $query = "UPDATE cms_channel SET ";
    foreach ($channel_info as $key => $val) {
        if ($key != 'id' && $key != 'owner_id') {
            if ($key == 'profile_id') {
				$channelDetailArray = GetChannelDetail($channel_info['id'], $channel_info['profile_id']);
				if ($channelDetailArray && $channelDetailArray['detail_text'] != $channelArray['detail_text']) {
					$query .= " `logo` = :Logo,";
					$query .= " `profile_id` = :Profile_id,";
					$params[] = array(  "key" => ":Logo", "value" =>$channelDetailArray['detail_text']);
					$params[] = array(  "key" => ":Profile_id", "value" =>$channel_info['profile_id']);
					$profileupdate = true;
                                        $profile_id = $channel_info['profile_id'];
				}
            } else if ($key == 'cover_id') {
				$channelDetailArray = GetChannelDetail($channel_info['id'], $channel_info['cover_id']);
				if ($channelDetailArray && $channelDetailArray['detail_text'] != $channelArray['detail_text']) {
					$query .= " `header` = :Header,";
					$query .= " `cover_id` = :Cover_id,";
					$params[] = array(  "key" => ":Header", "value" =>$channelDetailArray['detail_text']);
					$params[] = array(  "key" => ":Cover_id", "value" =>$channel_info['cover_id']);
					$coverupdate = true;
                                        $cover_id = $channel_info['cover_id'];
				}
            }else{
				$query .= " $key = '$val',";
			}
        }
    }
    $query = trim($query, ',');
    $query .= " WHERE id=:Id";
    $params[] = array(  "key" => ":Id", "value" =>$channel_info['id']);
    if (isset($channel_info['owner_id']) && $channel_info['owner_id'] != '') {
        $query .= " AND owner_id=:Owner_id";
        $params[] = array(  "key" => ":Owner_id", "value" =>$channel_info['owner_id']);
    }
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $ret    = $update->execute();

    if ($ret) {
        if ($profileupdate) {
            newsfeedAdd($channelArray['owner_id'], $profile_id, SOCIAL_ACTION_UPDATE, $channel_info['id'], SOCIAL_ENTITY_CHANNEL_PROFILE, USER_PRIVACY_PUBLIC, $channel_info['id']);
        }
        if ($coverupdate) {
            newsfeedAdd($channelArray['owner_id'], $cover_id, SOCIAL_ACTION_UPDATE, $channel_info['id'], SOCIAL_ENTITY_CHANNEL_COVER, USER_PRIVACY_PUBLIC, $channel_info['id']);
        }
    }

    return ( $ret ) ? true : false;
}
/**
 * chnages the channels logo
 * @param integer $id the channel's id
 * @param string $logo the new logo
 * @return boolean true|false if success or fail
 */
function channelChangeLogo($id, $logo) {


    global $dbConn;
    $params = array();
//    $query = "UPDATE cms_channel SET logo='$logo' WHERE id='$id'";
    $query = "UPDATE cms_channel SET logo=:Logo WHERE id=:Id";

    newsfeedAdd(userGetID(), $id, SOCIAL_ACTION_UPDATE, $id, SOCIAL_ENTITY_CHANNEL_PROFILE, USER_PRIVACY_PUBLIC, $id);

//    $ret = db_query($query);
    
    $params[] = array(  "key" => ":Logo",
                        "value" =>$logo);
    $params[] = array(  "key" => ":Id",
                        "value" =>$id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $ret    = $update->execute();
    return ( $ret ) ? true : false;


}

/**
 * chnages the channels icon
 * @param integer $id the channel's id
 * @param string $icon the new icon
 * @return boolean true|false if success or fail
 */
function channelChangeIcon($id, $icon) {


    global $dbConn;
    $params = array(); 
//    $query = "UPDATE cms_channel SET icon='$icon' WHERE id='$id'";
//    $ret = db_query($query);
//    return ( $ret ) ? true : false;
    $query = "UPDATE cms_channel SET icon=:Icon WHERE id=:Id";
    $params[] = array(  "key" => ":Icon",
                        "value" =>$icon);
    $params[] = array(  "key" => ":Id",
                        "value" =>$id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $ret    = $update->execute();
    return ( $ret ) ? true : false;


}

$_global_search = null;
$_global_cat_search = null;

/**
 * sets the channel search criteria
 * @param string $cinfo the search criteria
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<start>
//function channelGlobalSearchSet($cinfo) {
//    global $_global_search;
//    $_global_search = $cinfo;
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<end>

/**
 * sets the channel category search criteria
 * @param string $cinfo the search criteria
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  04/05/2015
//<start>
//function channelGlobalcategorySet($cinfo) {
//    global $_global_cat_search;
//    $_global_cat_search = $cinfo;
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  04/05/2015
//<end>

/**
 * returns the global channel search criteria
 * @return integer | false the global channel search criteria record or false if none 
 */
function channelGlobalSearchGet() {
    global $_global_search;
    return is_null($_global_search) ? false : $_global_search;
}

/**
 * returns the global channel category search criteria
 * @return integer | false the global channel search criteria record or false if none 
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  04/05/2015
//<start>
//function channelGlobalcategoryGet() {
//    global $_global_cat_search;
//    return is_null($_global_cat_search) ? false : $_global_cat_search;
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  04/05/2015
//<end>

$_global_channel = null;

/**
 * sets the channel globally
 * @param array $cinfo the channel record
 */
function channelGlobalSet($cinfo) {
    global $_global_channel;
    $_global_channel = $cinfo;
}

/**
 * returns the global channed_id
 * @return array | false the global channed record or false if none 
 */
function channelGlobalGet() {
    global $_global_channel;
    return is_null($_global_channel) ? false : $_global_channel;
}

/**
 * gets the channel logo
 * @return string | false the link to the logo or false if no channel 
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<start>
//function channelGlobalGetLogo() {
//    global $_global_channel;
//    if (is_null($_global_channel))
//        return false;
//    return $logo = ReturnLink('media/images/channel/logo/' . $_global_channel['logo']);
//}

/**
 * gets the channel icon
 * @return string | false the link to the icon or false if no channel 
 */
function channelGlobalGetIcon() {
    global $_global_channel;
    if (is_null($_global_channel))
        return false;
    return $logo = ReturnLink('media/images/channel/icon/' . $_global_channel['icon']);
}

/**
 * gets the channel name
 * @return string | false the channel's name
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  04/05/2015
//<start>
//function channelGlobalGetName() {
//    global $_global_channel;
//    if (is_null($_global_channel))
//        return false;
//    return $_global_channel['channel_name'];
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  04/05/2015
//<end>

/**
 * gets various channel details
 * @param array $chinfo the cms_channel record
 * @param array $chinfo the cms_user record
 * @return array the channel details
 */
function channelGetDetails($chinfo, $userInfo) {


    global $dbConn;
    $params = array();  

    $ret = array();
    $ts = strtotime($userInfo['RegisteredDate']);
    $ret['joinedDate'] = date('F d, Y', $ts);

    $ts = strtotime($userInfo['YourBday']);
    $ts2 = time();
    $ret['age'] = intval(($ts2 - $ts) / (3600 * 24 * 365)); //leap years

//    $query = "SELECT pdate FROM cms_videos WHERE userid='{$userInfo['id']}' AND published='1'";
//    $res = db_query($query);
    $query = "SELECT pdate FROM cms_videos WHERE userid=:Id AND published='1'";
    $params[] = array(  "key" => ":Id",
                        "value" =>$userInfo['id']);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();

//    if (!$res || (db_num_rows($res) == 0)) {
    if (!$res || ($ret == 0)) {
        $ret['lastActivity'] = 'Never';
    } else {
//        $row = db_fetch_array($res);
        $row = $select->fetch();
        $ts = strtotime($row[0]);
        $ret['lastActivity'] = date('F d, Y', $ts);
    }

//    $query = "SELECT name FROM cms_countries WHERE code='{$userInfo['YourCountry']}'";
//    $res = db_query($query);
//    if (!$res || (db_num_rows($res) == 0)) {
    $params = array(); 
    $query2 = "SELECT name FROM cms_countries WHERE code=:YourCountry";
    $params[] = array(  "key" => ":YourCountry",
                        "value" =>$userInfo['YourCountry']);
    $select2 = $dbConn->prepare($query2);
    PDO_BIND_PARAM($select2,$params);
    $res     = $select2->execute();

    $ret     = $select2->rowCount();
    if (!$res || ($ret == 0)) {
        $ret['country'] = 'Unkown';
    } else {
//        $row = db_fetch_array($res);
	$row = $select2->fetch();
        $ret['country'] = $row[0];
    }

    return $ret;


}

/**
 * channel get statistics
 * @param integer $channel_id which cms_channel
 * @return array the hash containing the channel like,comment,share, rates numbers
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  04/05/2015
//<start>
//function channelGetStatistics($channel_id) {
//
//
//    if ($channelStats != null)
//        return $channelStats;
//
//    //the media stats
//    $query = "SELECT 
//					SUM(up_votes) AS up_votes, SUM(down_votes) AS down_votes, SUM(like_value) AS like_value, SUM(nb_shares) AS nb_shares, SUM(nb_comments) AS nb_comments, SUM(nb_ratings) AS nb_ratings
//				FROM
//					cms_videos
//				WHERE
//					channelid='$channel_id' AND published=1
//			";
//    $res = db_query($query);
//    $media_row = db_fetch_assoc($res);
//
//    //news stats
//    $query = "SELECT 
//					SUM(up_votes) AS up_votes, SUM(down_votes) AS down_votes, SUM(like_value) AS like_value
//				FROM
//					cms_channel_news
//				WHERE
//					channelid='$channel_id' AND published=1
//			";
//    $res = db_query($query);
//    $news_row = db_fetch_assoc($res);
//
//    //brochure stats
//    $query = "SELECT 
//					SUM(up_votes) AS up_votes, SUM(down_votes) AS down_votes, SUM(like_value) AS like_value
//				FROM
//					cms_channel_brochure
//				WHERE
//					channelid='$channel_id' AND published=1
//			";
//    $res = db_query($query);
//    $brochure_row = db_fetch_assoc($res);
//
//    //events stats
//    $query = "SELECT 
//					SUM(up_votes) AS up_votes, SUM(down_votes) AS down_votes, SUM(like_value) AS like_value
//				FROM
//					cms_channel_event
//				WHERE
//					channelid='$channel_id' AND published=1
//			";
//    $res = db_query($query);
//    $event_row = db_fetch_assoc($res);
//
//    $channelStats = array();
//    $channelStats['media'] = $media_row;
//    $channelStats['news'] = $news_row;
//    $channelStats['event'] = $event_row;
//    $channelStats['brochure'] = $brochure_row;
//
//    $total = array();
//    foreach ($channelStats as $type => &$arr) {
//        foreach ($arr as $col => &$val) {
//            if (!$val)
//                $val = 0;
//            if (!isset($total[$col]))
//                $total[$col] = $val;
//            else
//                $total[$col] += $val;
//        }
//    }
//
//    $channelStats['total'] = $total;
//
//
//    return $channelStats;
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  04/05/2015
//<end>

/**
 * sets the visibility of the channel
 * @param integer $id the cms_channel id
 * @param integer $visible visible or not
 * @return boolean true|false if success|fail
 */
function channelVisibilitySet($id, $visible) {
    global $dbConn;
    $params = array();
    $query = "UPDATE cms_channel SET channel_visible=:Visible WHERE id=:Id";
    $params[] = array(  "key" => ":Visible",
                        "value" =>$visible);
    $params[] = array(  "key" => ":Id",
                        "value" =>$id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return $res;
}

/**
 * sets the visibility of the cms_channel connections
 * @param integer $id the cms_channel_connections id
 * @param integer $visible visible or not
 * @return boolean true|false if success|fail
 */
function connectionVisibilitySet($id, $visible) {
    global $dbConn;
    $params = array();
    $query = "UPDATE cms_channel_connections SET is_visible=:Visible WHERE id=:Id";
    $params[] = array(  "key" => ":Visible",
                        "value" =>$visible);
    $params[] = array(  "key" => ":Id",
                        "value" =>$id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return $res;
}

/**
 * sets the visibility of the cms_channel connections
 * @param integer $id the cms_channel_connections id
 * @param integer $channel_id the cms_channel_connections channel id
 * @param integer $notify 1 or 0 if false
 * @return boolean true|false if success|fail
 */
function channelConnectionNotificationSet($channel_id, $id, $notify) {
    $query = "UPDATE cms_channel_connections SET notify='$notify' WHERE id='$id' AND channelid='$channel_id'";
    return db_query($query);
}
/**
 * join an event 
 * @param integer $user_id the user id
 * @param integer $event_id the event id
 * @param integer $channel_id the channel id
 * @param integer $guests the number of guests
 * @return integer | false the newly inserted cms_channel_event_join id or false if not inserted
 */
function joinEventAdd($event_id, $user_id, $guests, $channel_id) {
    global $dbConn;
    $params = array();  
    $join_event_id = '';
    $query = "  SELECT id FROM cms_channel_event_join
                WHERE (event_id = :Event_id AND user_id = :User_id AND published = -2)";
    $params[] = array(  "key" => ":Event_id", "value" =>$event_id);
    $params[] = array(  "key" => ":User_id", "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    if ($ret > 0) {
        $row = $select->fetch();
        $join_event_id = $row[0];
    $params = array();
        $query = "UPDATE cms_channel_event_join
                    SET guests = :Guests, published = 1
                    WHERE (event_id = :Event_id AND user_id = :User_id AND published = -2)";
        $params[] = array(  "key" => ":Guests", "value" =>$guests);
        $params[] = array(  "key" => ":Event_id", "value" =>$event_id);
        $params[] = array(  "key" => ":User_id", "value" =>$user_id);
	$update = $dbConn->prepare($query);
	PDO_BIND_PARAM($update,$params);
	$ret    = $update->execute();
    }
    // If it's the first time the user joins this event.
    else {
    $params = array();
        $query = "INSERT INTO cms_channel_event_join (event_id,user_id,create_ts,guests,published)
                    VALUES (:Event_id,:User_id,NOW(),:Guests,1)";
        $params[] = array(  "key" => ":Event_id", "value" =>$event_id);
        $params[] = array(  "key" => ":User_id", "value" =>$user_id);
        $params[] = array(  "key" => ":Guests", "value" =>$guests);
	$insert = $dbConn->prepare($query);
	PDO_BIND_PARAM($insert,$params);
	$ret    = $insert->execute();
    }
    if ($join_event_id == '')
        $join_event_id = $dbConn->lastInsertId();
    if ($join_event_id) {
        if (db_num_rows($ret) == 0) {
            newsfeedAdd($user_id, $join_event_id, SOCIAL_ACTION_EVENT_JOIN, $event_id, SOCIAL_ENTITY_EVENTS, USER_PRIVACY_PUBLIC, $channel_id);
            newsfeedAdd($user_id, $join_event_id, SOCIAL_ACTION_EVENT_JOIN, $event_id, SOCIAL_ENTITY_EVENTS, USER_PRIVACY_PRIVATE, null);
        }
        return $join_event_id;
    } else {
        return false;
    }
}

/**
 * gets the join an event 
 * @param integer $id the join's id
 * @return array | false the cms_channel_event_join record or null if not found
 */
function joinEventInfo($id) {
    global $dbConn;
    $joinEventInfo = tt_global_get('joinEventInfo');   // Added By Devendra on 25th may 2015
    $params = array();
    if(isset($joinEventInfo[$id]) && $joinEventInfo[$id]!=''){
     return $joinEventInfo[$id];
    }
    $query = "SELECT `id`, `event_id`, `user_id`, `create_ts`, `guests`, `published`, `is_visible` FROM `cms_channel_event_join` WHERE id=:Id";    
    $params[] = array(  "key" => ":Id", "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        $joinEventInfo[$id] =    $row;
        return $row;
    } else {
        $joinEventInfo[$id] =    false;
        return false;
    }


}

/**
 * delete the join event for a given join id 
 * @param integer $id the join's id 
 * @return boolean true|false depending on the success of the operation
 */
function joinEventDelete($id) {


    global $dbConn;
    $params = array();
    if (deleteMode() == TT_DEL_MODE_PURGE) {
//        $query = "DELETE FROM cms_channel_event_join where id='$id' AND published=1";
        $query = "DELETE FROM cms_channel_event_join where id=:Id AND published=1";
    } else if (deleteMode() == TT_DEL_MODE_FLAG) {
//        $query = "UPDATE cms_channel_event_join SET published=" . TT_DEL_MODE_FLAG . " WHERE id='$id' AND published=1";
        $query = "UPDATE cms_channel_event_join SET published=" . TT_DEL_MODE_FLAG . " WHERE id=:Id AND published=1";
    }

    newsfeedDeleteJoinByAction($id, SOCIAL_ACTION_EVENT_JOIN, SOCIAL_ENTITY_EVENTS);

    
    $params[] = array(  "key" => ":Id", "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
//    return db_query($query);
    return $res;


}

/**
 * Deletes all the Join Event associated with a key,type. typically for a foreign key record delete
 * @param integer $fk
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  04/05/2015
//<start>
//function socialJoinEventDelete($fk) {
//    $done = false;
//    $query = "SELECT id FROM cms_channel_event_join WHERE event_id='$fk' AND published=1 LIMIT 1000"; //delete 1000 records at a time
//    TTDebug(DEBUG_TYPE_SOCIAL, DEBUG_LVL_INFO, $query);
//    $res = db_query($query);
//    if (!$res || (db_num_rows($res) == 0))
//        $done = true;
//    while (!$done) {
//
//        while ($row = db_fetch_row($res)) {
//            $id = $row[0];
//            joinEventDelete($id);
//        }
//
//        $res = db_query($query);
//        if (!$res || (db_num_rows($res) == 0))
//            $done = true;
//    }
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  04/05/2015
//<end>

/**
 * gets the joined event info of an event . options include:<br/>
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>id</b>: join id<br/>
 * <b>event_id</b>: the event's id. default null<br/>
 * <b>user_id</b>: the user's id. default null<br/>
 * <b>from_ts</b>: search for joined user after this date. default null.<br/>
 * <b>to_ts</b>: search for joined user before this date. default null.<br/>
 * <b>is_visible</b>: is visible or not. default = -1 => doenst matter.<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * @return array | false an array of 'cms_channel_event_join' records or false if none found.
 */
function joinEventSearch($srch_options) {


    global $dbConn;
    $params = array();  
    $default_opts = array(
        'limit' => 100,
        'page' => 0,
        'id' => null,
        'event_id' => null,
        'is_visible' => -1,
        'distinct_user' => 0,
        'escape_user' => null,
        'user_id' => null,
        'from_ts' => null,
        'to_ts' => null,
        'orderby' => 'id',
        'n_results' => false,
        'order' => 'a'
    );

    $options = array_merge($default_opts, $srch_options);

    $where = '';

    if (!is_null($options['id'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " CJ.id='{$options['id']}' ";
        $where .= " CJ.id=:Id ";
	$params[] = array(  "key" => ":Id",
                            "value" =>$options['id']);
    }
    if (!is_null($options['from_ts'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " DATE(CJ.create_ts) >= '{$options['from_ts']}' ";
        $where .= " DATE(CJ.create_ts) >= :From_ts ";
	$params[] = array(  "key" => ":From_ts",
                            "value" =>$options['from_ts']);
    }
    if (!is_null($options['to_ts'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " DATE(CJ.create_ts) <= '{$options['to_ts']}' ";
        $where .= " DATE(CJ.create_ts) <= :To_ts ";
	$params[] = array(  "key" => ":To_ts",
                            "value" =>$options['to_ts']);
    }
    if (!is_null($options['event_id'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " CJ.event_id='{$options['event_id']}' ";
        $where .= " CJ.event_id=:Event_id ";
	$params[] = array(  "key" => ":Event_id",
                            "value" =>$options['event_id']);
    }
    if (!is_null($options['user_id'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " CJ.user_id='{$options['user_id']}' ";
        $where .= " CJ.user_id=:User_id ";
	$params[] = array(  "key" => ":User_id",
                            "value" =>$options['user_id']);
    }
    if ($options['is_visible'] != -1) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " CJ.is_visible='{$options['is_visible']}' ";
        $where .= " CJ.is_visible=:Is_visible ";
	$params[] = array(  "key" => ":Is_visible",
                            "value" =>$options['is_visible']);
    }
    if(!is_null($options['escape_user'])){
        if( $where != '') $where .= " AND ";
//        $where .= " CJ.user_id NOT IN({$options['escape_user']}) ";
	$where .= " NOT find_in_set(cast(CJ.user_id as char), :Escape_user) ";
	$params[] = array(  "key" => ":Escape_user", "value" =>$options['escape_user']);
    }
    if ($where != '')
        $where .= " AND ";
    $where .= " CJ.published=1 ";

    if ($where != '') {
        $where = "WHERE $where";
    }

    $orderby = $options['orderby'];
    $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';

    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit;


    if ($options['n_results'] == false) {        
        if( $options['distinct_user']==1 ){                    
//                $query = "SELECT CJ.is_visible AS is_visible_join , CJ.id, CJ.event_id, CJ.user_id, CJ.create_ts, CJ.guests, CJ.published,
//                                                    users.id AS user_id, users.FullName, users.fname, users.lname, users.gender, users.YourEmail, users.website_url, users.small_description, users.YourCountry, users.hometown, users.city, users.YourIP, users.YourBday, users.YourUserName, users.profile_Pic, users.display_age, users.display_gender, users.YourPassword, users.RegisteredDate, users.profile_views, users.published, users.notifs, users.chkey, users.n_flashes, users.n_journals, users.occupation, users.employment, users.high_education, users.uni_education, users.intrested_in, users.display_interest, users.display_fullname, users.contact_privacy, users.search_engine, users.feeds_privacy, users.comment_privacy, users.isChannel, users.otherEmail
//                                    FROM cms_channel_event_join AS CJ
//                                    INNER JOIN cms_users AS users ON users.id = CJ.user_id
//                                    $where GROUP BY CJ.user_id ORDER BY $orderby $order LIMIT $skip, $nlimit";
            $query = "SELECT CJ.is_visible AS is_visible_join , CJ.id, CJ.event_id, CJ.user_id, CJ.create_ts, CJ.guests, CJ.published,
                                    users.id AS user_id, users.FullName, users.fname, users.lname, users.gender, users.YourEmail, users.website_url, users.small_description, users.YourCountry, users.hometown, users.city, users.YourIP, users.YourBday, users.YourUserName, users.profile_Pic, users.display_age,users.display_yearage, users.display_gender, users.YourPassword, users.RegisteredDate, users.profile_views, users.published, users.notifs, users.chkey, users.n_flashes, users.n_journals, users.occupation, users.employment, users.high_education, users.uni_education, users.intrested_in, users.display_interest, users.display_fullname, users.contact_privacy, users.search_engine, users.feeds_privacy, users.comment_privacy, users.isChannel, users.otherEmail
                    FROM cms_channel_event_join AS CJ
                    INNER JOIN cms_users AS users ON users.id = CJ.user_id
                    $where GROUP BY CJ.user_id ORDER BY $orderby $order LIMIT :Skip, :Nlimit";

        }else{
//            $query = "SELECT CJ.is_visible AS is_visible_join , CJ.id, CJ.event_id, CJ.user_id, CJ.create_ts, CJ.guests, CJ.published,
//							users.id AS user_id, users.FullName, users.fname, users.lname, users.gender, users.YourEmail, users.website_url, users.small_description, users.YourCountry, users.hometown, users.city, users.YourIP, users.YourBday, users.YourUserName, users.profile_Pic, users.display_age, users.display_gender, users.YourPassword, users.RegisteredDate, users.profile_views, users.published, users.notifs, users.chkey, users.n_flashes, users.n_journals, users.occupation, users.employment, users.high_education, users.uni_education, users.intrested_in, users.display_interest, users.display_fullname, users.contact_privacy, users.search_engine, users.feeds_privacy, users.comment_privacy, users.isChannel, users.otherEmail
//					FROM cms_channel_event_join AS CJ
//					INNER JOIN cms_users AS users ON users.id = CJ.user_id
//					$where ORDER BY $orderby $order LIMIT $skip, $nlimit";
            $query = "SELECT CJ.is_visible AS is_visible_join , CJ.id, CJ.event_id, CJ.user_id, CJ.create_ts, CJ.guests, CJ.published,
							users.id AS user_id, users.FullName, users.fname, users.lname, users.gender, users.YourEmail, users.website_url, users.small_description, users.YourCountry, users.hometown, users.city, users.YourIP, users.YourBday, users.YourUserName, users.profile_Pic, users.display_age,users.display_yearage, users.display_gender, users.YourPassword, users.RegisteredDate, users.profile_views, users.published, users.notifs, users.chkey, users.n_flashes, users.n_journals, users.occupation, users.employment, users.high_education, users.uni_education, users.intrested_in, users.display_interest, users.display_fullname, users.contact_privacy, users.search_engine, users.feeds_privacy, users.comment_privacy, users.isChannel, users.otherEmail
					FROM cms_channel_event_join AS CJ
					INNER JOIN cms_users AS users ON users.id = CJ.user_id
					$where ORDER BY $orderby $order LIMIT :Skip, :Nlimit";            
        }
        $params[] = array(  "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
        $params[] = array(  "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");

//        $ret = db_query($query);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
//        if (!$ret || (db_num_rows($ret) == 0)) {
        if (!$res || ($ret == 0)) {
            return false;
        } else {
            $media = array();

//            while ($row = db_fetch_array($ret)) {
            $row = $select->fetchAll();
            foreach($row as $row_item){
                if ($row_item['profile_Pic'] == '') {
                    $row_item['profile_Pic'] = 'he.jpg';
                    if ($row_item['gender'] == 'F') {
                        $row_item['profile_Pic'] = 'she.jpg';
                    }
                }
                $media[] = $row_item;
            }

            return $media;
        }
    } else {        
        if( $options['distinct_user']==1 ){
            $query = "SELECT COUNT( DISTINCT CJ.user_id ) FROM cms_channel_event_join AS CJ $where";
        }else{
            $query = "SELECT COUNT(CJ.id) FROM cms_channel_event_join AS CJ $where";
        }
//        $ret = db_query($query);
//        $row = db_fetch_row($ret);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
        $row    = $select->fetch();

        return $row[0];
    }
}
/**
 * gets a list of tubers joined
 * @param integer $event_id the event id
 * @return array the joined tuber
 */
function getJoinedEventTubersList($event_id) {


    global $dbConn;
    $params = array();  
//    $query = "SELECT DISTINCT user_id FROM cms_channel_event_join WHERE event_id = '".$event_id."' AND published=1";
//    $ret = db_query($query);
//    if (!$ret || (db_num_rows($ret) == 0)) {
//        return false;
//    } else {
//        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row[0];
//        }
//        return $ret_arr;
//    }
    $query = "SELECT DISTINCT user_id FROM cms_channel_event_join WHERE event_id = :Event_id AND published=1";
    $params[] = array(  "key" => ":Event_id",
                        "value" =>$event_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0)) {
        return false;
    } else {
        $ret_arr = array();
	$row = $select->fetchAll();
        foreach($row as $row_item){
            $ret_arr[] = $row_item[0];
        }
        return $ret_arr;
    }


}
/**
 * edits a  join event info
 * @param array $new_info the new cms_channel_event_join info
 * @return boolean true|false if success|fail
 */
function joinEventEdit($new_info) {


    global $dbConn;
    $params = array(); 
    $query = "UPDATE cms_channel_event_join SET ";
    $i = 0 ;
    foreach ($new_info as $key => $val) {
        if ($key != 'id' && $key != 'event_id') {
//            $query .= " $key = '$val',";
            $query .= " $key = :Val".$i.",";
            $params[] = array(  "key" => ":Val".$i,
                                "value" =>$val);
            $i++;
        }
    }
    $query = trim($query, ',');
//    $query .= " WHERE id='{$new_info['id']}' AND  event_id='{$new_info['event_id']}'";
    $query .= " WHERE id=:Id AND  event_id=:Event_id";
//    $ret = db_query($query);
    
    $params[] = array(  "key" => ":Id", "value" =>$new_info['id']);
    $params[] = array(  "key" => ":Event_id", "value" =>$new_info['event_id']);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $ret    = $select->execute();
    return ( $ret ) ? true : false;
}



/**
 * Checks if a country exists in the zip-codes db (if it has zip codes associated to it).
 * @param string $country_code the country code
 * @return result array on success, flase on fail.
 */
function countryExists($country_code) {


    global $dbConn;
    $params = array();  
//    $query = "SELECT * FROM cms_zipcodes WHERE (LOWER(country_code) = '" . strtolower($country_code) . "') LIMIT 1";
//
//    $ret = db_query($query);
//    if ($ret) {
//        $row = db_fetch_array($ret);
//        return $row;
//    } else {
//        return false;
//    }
    $query = "SELECT * FROM cms_zipcodes WHERE (LOWER(country_code) = :Country_code) LIMIT 1";
    $params[] = array(  "key" => ":Country_code",
                        "value" =>strtolower($country_code));
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($ret) {
        $row = $select->fetch();
        return $row;
    } else {
        return false;
    }


}

/**
 * Looks up a zip code and a coutnry code and returns the first corresponding result.
 * @param string $zip_code the zip code
 * @param string $country_code the country code
 * @return result array on success, flase on fail.
 */
function zipcodeLookup($zip_code, $country_code) {


    global $dbConn;
    $params = array();  
//    $query = "SELECT * FROM cms_zipcodes WHERE (LOWER(zip_code) = '" . strtolower($zip_code) . "' AND LOWER(country_code) = '" . strtolower($country_code) . "') LIMIT 1";
//
//    $ret = db_query($query);
//    if ($ret) {
//        $row = db_fetch_array($ret);
//        return $row;
//    } else {
//        return false;
//    }
    $query = "SELECT * FROM cms_zipcodes WHERE (LOWER(zip_code) = :Zip_code AND LOWER(country_code) = :Country_code) LIMIT 1";

    $params[] = array(  "key" => ":Zip_code",
                        "value" =>strtolower($zip_code) );
    $params[] = array(  "key" => ":Country_code",
                        "value" =>strtolower($country_code));
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res) {
        $row = $select->fetch();
        return $row;
    } else {
        return false;
    }


}

/**
 * Gets all the countries that are used in all channels
 * @return result array(of country) on success, flase on fail.
 */
//Added by Anthony Malak 04-06-2015 
function channelCountryExists() {
    global $dbConn;
    $params = array();  
    $query = "SELECT DISTINCT co.* FROM `cms_countries` as co INNER JOIN cms_channel AS ch ON ch.country = co.code AND ch.published = 1 ORDER BY name ASC";

    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res) {
        $row = $select->fetchAll();
        return $row;
    } else {
        return false;
    }
}
/**
 * add the story record 
 * @param integer $entity_id the discover entity id
 * @param integer $entity_type the $entity type
 * @param integer $channel_id the channel id
 * @return integer | false the newly inserted cms_channel_reviewpage id or false if not inserted
 */
function AddDiscoverToChannel($entity_id,$entity_type,$channel_id) {
    global $dbConn;
    $params = array();
    $query = "SELECT id FROM cms_channel_reviewpage WHERE entity_type = :Entity_type AND entity_id = :Entity_id AND channel_id = :Channel_id AND published = 1 LIMIT 1";
    $params[] = array(  "key" => ":Entity_type", "value" =>$entity_type);
    $params[] = array(  "key" => ":Entity_id", "value" =>$entity_id);
    $params[] = array(  "key" => ":Channel_id", "value" =>$channel_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    if ($res && $ret > 0) {
        $row = $select->fetch();
        return $row['id'];
    }else{
        $query = "INSERT INTO `cms_channel_reviewpage`(`channel_id`, `entity_id`, `entity_type`) VALUES (:Channel_id,:Entity_id,:Entity_type)";
        $insert = $dbConn->prepare($query);
        PDO_BIND_PARAM($insert,$params);
        $res    = $insert->execute();
        $ret    = $dbConn->lastInsertId();
        return ( $res ) ? $ret : false;
    }
}
/**
 * get list of channel ids related
 * @param integer $entity_id the discover entity id
 * @param integer $entity_type the $entity type
 * @return array | false
 */
function getDiscoverToChannelid($entity_id,$entity_type) {
    global $dbConn;
    $params = array();
    $query = "SELECT channel_id FROM cms_channel_reviewpage WHERE entity_type = :Entity_type AND entity_id = :Entity_id AND published = 1";
    $params[] = array(  "key" => ":Entity_type", "value" =>$entity_type);
    $params[] = array(  "key" => ":Entity_id", "value" =>$entity_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    if ($res && $ret > 0) {
        $row = $select->fetchAll(PDO::FETCH_ASSOC);
        $channelids = array();
        foreach($row as $row_item){
            $channelids[] = $row_item['channel_id'];
        }
        return $channelids;
    }else{        
        return array();
    }
}
/**
 * get list of discover related
 * @param integer $channel_id the channel id
 * @return array
 */
function getDiscoverToChannelList($channel_id) {
    global $dbConn;
    $params = array();
    $query = "SELECT * FROM cms_channel_reviewpage WHERE channel_id = :Channel_id AND published = 1";
    $params[] = array(  "key" => ":Channel_id", "value" =>$channel_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    if ($res && $ret > 0) {
        $row = $select->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }else{        
        return array();
    }
}