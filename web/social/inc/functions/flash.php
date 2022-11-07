<?php

/**
 * the flash functionality that deals with the cms_flash tables
 * @package flash
 */
/**
 * the maximum length of the flash
 */
define('FLASH_MAX_LENGTH', 140);

/**
 * an actual echo text
 */
define('ECHO_TYPE_TEXT',1);
/**
 * an actual echo link
 */
define('ECHO_TYPE_LINK',2);
/**
 * an actual echo location
 */
define('ECHO_TYPE_LOCATION',3);
/**
 * inserts a new flash record
 * @param integer $user_id the user performing the flash
 * @param string $flash_text
 * @param string $pic_file the filename of the flash
 * @return type
 */
function flashAdd($user_id, $flash_text,$flash_link,$flash_location,$pic_file, $vpath, $_longitude, $_latitude, $_reply_to, $_city_id, $_location_id, $location_name) {
    global $dbConn;
    $params  = array(); 
    $params2 = array(); 
    $params3 = array(); 
    $longitude = $_longitude;
    $latitude = $_latitude;
    $reply_to = $_reply_to;
    $city_id = intval($_city_id);
    $location_id = $_location_id;
    if (is_null($longitude))
        $longitude = 'NULL';
    if (is_null($latitude))
        $latitude = 'NULL';
    if (is_null($reply_to))
        $reply_to = 'NULL';
    if ($city_id == 0)
        $city_id = 'NULL';
    if (is_null($location_id))
        $location_id = 'NULL';


//        $query = "UPDATE cms_users SET n_flashes=n_flashes+1 WHERE id='$user_id'";
    $query = "UPDATE cms_users SET n_flashes=n_flashes+1 WHERE id=:User_id";
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
//    db_query($query);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $update->execute();

    $ret    = $update->rowCount();

    if ($reply_to != 'NULL') {
//        $query = "UPDATE cms_flash SET n_replies=n_replies+1 WHERE id='$reply_to' AND published=1";
//        db_query($query);
        $query = "UPDATE cms_flash SET n_replies=n_replies+1 WHERE id=:Reply_to AND published=1";
        $params2[] = array( "key" => ":Reply_to", "value" =>$reply_to);
	$update = $dbConn->prepare($query);
	PDO_BIND_PARAM($update,$params2);
	$update->execute();
    }
    $query = "INSERT INTO cms_flash (user_id,flash_text,flash_link,flash_location,pic_file,vpath,longitude,latitude,reply_to,city_id,location_id,location_name) VALUES (:User_id,:Flash_text,:Flash_link,:Flash_location,:Pic_file,:Vpath,:Longitude,:Latitude,:Reply_to,:City_id,:Location_id,:Location_name)";
    $params3[] = array( "key" => ":User_id", "value" =>$user_id);
    $params3[] = array( "key" => ":Flash_text", "value" =>$flash_text);
    $params3[] = array( "key" => ":Flash_link", "value" =>$flash_link);
    $params3[] = array( "key" => ":Flash_location", "value" =>$flash_location);
    $params3[] = array( "key" => ":Pic_file", "value" =>$pic_file);
    $params3[] = array( "key" => ":Vpath", "value" =>$vpath);
    $params3[] = array( "key" => ":Longitude", "value" =>$longitude);
    $params3[] = array( "key" => ":Latitude", "value" =>$latitude);
    $params3[] = array( "key" => ":Reply_to", "value" =>$reply_to);
    $params3[] = array( "key" => ":City_id", "value" =>$city_id);
    $params3[] = array( "key" => ":Location_id", "value" =>$location_id);
    $params3[] = array( "key" => ":Location_name", "value" =>$location_name);
    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params3);
    $res = $insert->execute();
    if(!$res) return false;
    $echoe_id = $dbConn-> lastInsertId();
    newsfeedAdd($user_id, $echoe_id, SOCIAL_ACTION_UPLOAD, $echoe_id, SOCIAL_ENTITY_FLASH , USER_PRIVACY_PUBLIC , null);
    return $echoe_id;
}
/**
 * returns the cms_flash record + the userinfo from cms_users for the flash record
 * @param integer $flash_id the cms_flash record id
 * @return null|array null if no flash found or the cms_flash record
 */
function flashGetInfo($flash_id) {
//  Changed by Anthony Malak 21-04-2015 to PDO database
//  <start>
	global $dbConn;
       $params = array();  
       $flashGetInfo = tt_global_get('flashGetInfo');
            if(isset($flashGetInfo[$flash_id]) && $flashGetInfo[$flash_id]!='')
                return $flashGetInfo[$flash_id];
    $query = "SELECT F.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic FROM cms_flash AS F INNER JOIN cms_users AS U ON F.user_id=U.id WHERE F.id=:Flash_id AND F.published=1";
    $params[] = array( "key" => ":Flash_id", "value" =>$flash_id);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();
    
    if ($res && ($ret != 0)) {
        $row = $select->fetch();
        $flashGetInfo[$flash_id]    =   $row;
        return $row;
    } else {
            $flashGetInfo[$flash_id]    =   false;
            return null;
    }
//  Changed by Anthony Malak 21-04-2015 to PDO database
//  <end>
}

/**
 * returns a set of cms_flash record + the userinfo from cms_users for the flash record that are replies
 * @param integer $flash_id the cms_flash record id for which replies will be found
 * @return null|array null if no flash found or the cms_flash record
 */
function flashGetReplies($flash_id) {
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <start>
    global $dbConn;
    $params = array();  
//    $query = "SELECT F.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic FROM cms_flash AS F INNER JOIN cms_users AS U ON F.user_id=U.id WHERE F.reply_to='$flash_id' AND F.published=1 ORDER BY id DESC";
//    $ret = db_query($query);
//    if ($ret && (db_num_rows($ret) != 0)) {
//        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
//        return $ret_arr;
//    } else {
//        return null;
//    }
    $query = "SELECT F.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic FROM cms_flash AS F INNER JOIN cms_users AS U ON F.user_id=U.id WHERE F.reply_to=:Flash_id AND F.published=1 ORDER BY id DESC";
    $params[] = array( "key" => ":Flash_id",
                        "value" =>$flash_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();
    
    if ($res && ($ret != 0)) {
        $ret_arr = $select->fetchAll();
        return $ret_arr;
    } else {
        return null;
    }
}
/**
 * uploads a flash picture
 * @global array $CONFIG
 * @param string $fileInput the name of the $_FILES record
 * @return array|boolean
 */
function flashUploadFile($fileInput) {
    global $CONFIG;

    //got from common.php getUploadDirTree
    $rel_path = getUploadDirTree($CONFIG ['flash'] ['uploadPath']);
    $total_path = $CONFIG ['server']['root'] . $rel_path;

    $fileInfo = array();
    $fileName = time() . '-' . str_replace(array(' '), array('-'), $_FILES[$fileInput]['name']);
    $fileName = preg_replace('/[^a-z0-9A-Z\.]/', '-', $fileName);
    $thumb = "thumb_" . $fileName;
    $fileSize = round($_FILES[$fileInput]['size'] / 1024);
    $file = $rel_path . basename($fileName);

    if (@move_uploaded_file($_FILES[$fileInput]['tmp_name'], $file)) {
        $fileInfo ['filename'] = $fileName;
        $fileInfo ['size'] = $fileSize;
        $fileInfo ['vpath'] = $rel_path;
        $fileInfo ['thumb'] = $thumb;

        $fileInfo ['type'] = mime_content_type($file);

        if (strstr($fileInfo['type'], 'image') == null) {
            @unlink($file);
            return false;
        }
        mediaRotateImage($file);

        $thumbWidth = 190;
        $thumbHeight = 105;
        photoThumbnailCreate($total_path . $fileName, $total_path . $thumb, $thumbWidth, $thumbHeight);

        return $fileInfo;
    } else {
        return false;
    }
}

/**
 * search for flash feeds given certain options. options include:<br/>
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>user_id</b>: the media file's owner's id. default null<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>latitude</b>: the latitude of the location to search within<br/>
 * <b>longitude</b>: the logitude of the location to search within<br/>
 * <b>radius</b>: the radius to search within (in meters)<br/>
 * <b>dist_alg</b>: the distance algorithm to use (s)quare [faster], or (c)ircular [slower]. default is 's'<br/>
 * <b>search_string</b>: the string to search for. could be space separated. no default<br/>
 * <b>min_time</b>: gets the flashes newer than this unix timestamp. default null.<br/>
 * <b>max_time</b>: gets the flashes older than this unix timestamp. default false.<br/>
 * <b>n_results</b>: gets the number of results rather than the rows. default false.
 * @param array $srch_options. the search options
 * @return array a number of media records
 */
function flashSearch($srch_options) {
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <start>
	global $dbConn;
	$params = array();  

    $default_opts = array(
        'limit' => 6,
        'page' => 0,
        'id' => null,
        'user_id' => null,
        'orderby' => 'id',
        'order' => 'a',
        'month_search' => null,
        'day_search' => null,
        'hash_search' => null,
        'field_search' => null,
        'from_ts' => null,
        'to_ts' => null,
        'latitude' => null,
        'longitude' => null,
        'radius' => 1000,
        'dist_alg' => 's',
        'search_string' => null,
        'n_results' => false,
        'min_time' => null,
        'max_time' => null
    );

    $options = array_merge($default_opts, $srch_options);

    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit;

    $where = '';
    $join = '';
    $and = '';
    if (isset($options['hash_search']) && !is_null($options['hash_search']) && $options['hash_search'] != '') {
        $join = 'LEFT JOIN cms_hashtag AS H ON (F.id=H.entity_id and H.entity_type=8)';
        if ($where != '')
            $where .= ' AND ';
//        $where .= " H.key = '{$options['hash_search']}'";
        $where .= " H.key = :Hash_search";
	$params[] = array( "key" => ":Hash_search", "value" =>$options['hash_search']);
    }
    if (isset($options['field_search']) && !is_null($options['field_search']) && $options['field_search'] != '') {
        $join = 'LEFT JOIN cms_hashtag AS H ON (F.id=H.entity_id and H.entity_type=8)';
        if ($where != '')
            $where .= ' AND ';
//        $where .= " H.key like '%{$options['field_search']}%'";
        $where .= " H.key like :Field_search";
	$params[] = array( "key" => ":Field_search", "value" =>"%".$options['field_search']."%");
    }
    if (!is_null($options['from_ts'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " DATE(F.flash_ts) >= '{$options['from_ts']}' ";
        $where .= " DATE(F.flash_ts) >= :From_ts ";
	$params[] = array( "key" => ":From_ts", "value" =>$options['from_ts']);
    }
    if (!is_null($options['to_ts'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " DATE(F.flash_ts) <= '{$options['to_ts']}' ";
        $where .= " DATE(F.flash_ts) <= :To_ts ";
	$params[] = array( "key" => ":To_ts", "value" =>$options['to_ts']);
    }
    if (!is_null($options['id'])) {
        if ($where != '')
            $where .= ' AND';
//        $where .= " F.id = '{$options['id']}'";
        $where .= " F.id = :Id";
	$params[] = array( "key" => ":Id", "value" =>$options['id']);
    }
    if (!is_null($options['user_id'])) {
        if ($where != '') $where .= ' AND';
//        $where .= " F.user_id IN ({$options['user_id']})";
        $where .= " find_in_set(cast(F.user_id as char), :User_id)";
	$params[] = array( "key" => ":User_id", "value" =>$options['user_id']);
    }
    if (!is_null($options['search_string'])) {
        $search_strings = explode(' ', $options['search_string']);

        foreach ($search_strings as $in_search_string) {

            $search_string = trim(strtolower($in_search_string));
            $search_string = preg_replace('/[^a-z0-9A-Z]/', '', $search_string);

            if (strlen($search_string) <= 1)
                continue;

            if (in_array($search_string, $searched))
                continue;

            $searched[] = $search_string;
        }
        $sub_where = array();
        $i=0;
        foreach ($searched as $word) {
            $sub_where[] = " LOWER(F.flash_text) LIKE :Word".$i." ";
            $params[] = array( "key" => ":Word".$i , "value" =>'%'.$word.'%');
            $i++;
        }
        if ($where != '')
            $where .= ' AND ';
        $where .= ' ( ' . implode(' AND ', $sub_where) . ' ) ';
    }


    if (isset($options['day_search']) && !is_null($options['day_search']) && $options['day_search'] != '') {
        $arr_day = explode('-', $options['day_search']);
        if ($arr_day) {
            if (count($arr_day) == 3) {
                $mysl_day_start = date('Y-m-d H:i:s', mktime(0, 0, 0, $arr_day[1], $arr_day[2], $arr_day[0]));
                $mysl_day_end = date('Y-m-d H:i:s', mktime(23, 59, 59, $arr_day[1], $arr_day[2], $arr_day[0]));
                if ($where != '')
                    $where .= ' AND ';
//                $where .= " F.flash_ts >= '$mysl_day_start' and F.flash_ts <= '$mysl_day_end'";
                $where .= " F.flash_ts >= :Mysl_day_start and F.flash_ts <= :Mysl_day_end";
                $params[] = array( "key" => ":Mysl_day_start", "value" =>$mysl_day_start);
                $params[] = array( "key" => ":Mysl_day_end", "value" =>$mysl_day_end);
            }
        }
    }
    if (isset($options['month_search']) && !is_null($options['month_search']) && $options['month_search'] != '') {
        $arr_month = explode('-', $options['month_search']);
        if ($arr_month) {
            if (count($arr_month) == 2) {
                $mysl_month_start = date('Y-m-d H:i:s', mktime(0, 0, 0, $arr_month[1], 1, $arr_month[0]));
                $mysl_month_end = date('Y-m-d H:i:s', mktime(0, 0, 0, $arr_month[1] + 1, 0, $arr_month[0]));
                if ($where != '') $where .= ' AND ';
//                $where .= " F.flash_ts >= '$mysl_month_start' and F.flash_ts <= '$mysl_month_end'";
                $where .= " F.flash_ts >= :Mysl_day_start2 and F.flash_ts <= :Mysl_day_end2";
                $params[] = array( "key" => ":Mysl_day_start2", "value" =>$mysl_month_start);
                $params[] = array( "key" => ":Mysl_day_end2", "value" =>$mysl_month_end);
            }
        }
    }
    $orderby = $options['orderby'];
    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
        $orderby = "F." . $orderby;
    }

    if ($where != ''){
        $where = "WHERE $where";
        $and = " and ";
    }else{
        $and = " where ";
    }

    if ($options['n_results'] == false) {
//        $query = "SELECT F.* FROM `cms_flash` as F $join $where $and F.published=1 ORDER BY $orderby $order LIMIT $skip, $nlimit";
        $query = "SELECT F.* FROM `cms_flash` as F $join $where $and F.published=1 ORDER BY $orderby $order LIMIT :Skip, :Nlimit";
//        $ret = db_query($query);
        $params[] = array( "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
	$params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$select->execute();
        
        $flashes = array();

//        while ($row = db_fetch_array($ret))
        $flashes = $select->fetchAll();
//        exit ($query);
        return $flashes;
    } else {
        $query = "SELECT COUNT(F.id) FROM `cms_flash` as F $join $where $and published=1";
        //return $query;
//        $ret = db_query($query);
//        $row = db_fetch_array($ret);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$select->execute();
        
	$row = $select->fetch();
        $n_results = $row[0];
        return $n_results;
    }
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <end>
}

/**
 * checks if the user already liked the flash
 * @param integer $user_id
 * @param integer $flash_id
 * @return the user's like vlaue - if any - or null if none
 */
function flashLiked($user_id, $flash_id) {
    return socialLiked($user_id, $flash_id, SOCIAL_ENTITY_FLASH);
}

/**
 * likes a flash
 * @param integer $user_id
 * @param integer $flash_id
 * @param integer $like_value
 */
function flashLike($user_id, $flash_id, $like_value) {

    if (($like_value != -1) && ($like_value != 1))
        return false;

    if (socialLiked($user_id, $flash_id, SOCIAL_ENTITY_FLASH)) {
        socialLikeEdit($user_id, $flash_id, SOCIAL_ENTITY_FLASH, $like_value);
    } else {
        socialLikeAdd($user_id, $flash_id, SOCIAL_ENTITY_FLASH, $like_value, null);
    }

    return true;
}
function flashReflash($user_id, $flash_id) {
	global $dbConn;
	$params  = array();  
	$params2 = array();  
	$params[] = array( "key" => ":Flash_id",
                            "value" =>$flash_id);
    $query = "SELECT * FROM cms_flash WHERE id=:Flash_id AND published=1";
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();

    if (!$res || $ret == 0) {
        return false;
    }
//    $row = db_fetch_array($ret);
    $row = $select->fetch();
    $flash_text = $row['flash_text'];
    $flash_location = $row['flash_location'];
    $flash_link = $row['flash_link'];
    $pic_file = $row['pic_file'];
    $vpath = $row['vpath'];
    $_longitude = $row['longitude'];
    $_latitude = $row['latitude'];

    flashAdd($user_id, $flash_text, $flash_link , $flash_location , $pic_file, $vpath, $_longitude, $_latitude, null);

    $query = "UPDATE cms_flash SET n_reflashes=n_reflashes+1 WHERE id=:Flash_id AND published=1";
	$params2[] = array( "key" => ":Flash_id",
                             "value" =>$flash_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params2);
    $res = $select->execute();

    return true;
}

/**
 * get most used of flash hash keys :<br/>
 *
 * @param int $number
 *
 * @return array or false
 */
function getFlashTags($number) {
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <start>
	global $dbConn;
	$params = array(); 
//    $query = 'SELECT *, count(H.key) as cnt FROM cms_hashtag AS H where H.entity_type=' . SOCIAL_ENTITY_FLASH . '
//GROUP BY H.key order by cnt DESC limit 0, ' . $number;
//    return getArr($query);
    $query = 'SELECT *, count(H.key) as cnt FROM cms_hashtag AS H where H.entity_type=' . SOCIAL_ENTITY_FLASH . '
GROUP BY H.key order by cnt DESC limit 0, :Number';
    $params[] = array( "key" => ":Number",
                        "value" =>$number,
                        "type" =>"::PARAM_INT");
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();
    
    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $ret_arr = $select->fetchAll();
    } else {
        return false;
    }
    
    return $ret_arr;
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <end>
}

/**
 * get All hash tags related to a certain echoe
 *
 * @param int $entity_id the echoe id
 *
 * @return array 
 */
function getEchoeTags($entity_id) {
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <start>
	global $dbConn;
	$params = array();  

//    $query = 'SELECT * FROM cms_hashtag where entity_id='.$entity_id.' And entity_type=' . SOCIAL_ENTITY_FLASH . '';
//    return getArr($query);
    $query = "SELECT * FROM cms_hashtag where entity_id=:Entity_id And entity_type=" . SOCIAL_ENTITY_FLASH . "";
    $params[] = array( "key" => ":Entity_id", "value" =>$entity_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();
    
    if ($res && $ret != 0) {
        $ret_arr = $select->fetchAll();
    } else {
        return false;
    }

    return $ret_arr;
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <end>
}
/**
 * Display top Cloud for hashTags
 *
 * @param int keyNumber the maximum number of keys to display
 * Use function displayKeyCloud()
 *
 * @return string the keys listed using classes in span (smallestCloud, smallCloud, mediumCloud, largeCloud, largestCloud)
 */
function keyCloudFlash($keyNumber) {
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <start>
	global $dbConn;
	$params = array();
    $allArr = array();
    $toRet = '';
//    $query = 'SELECT *, count(H.key) as cnt FROM cms_hashtag AS H where H.entity_type=' . SOCIAL_ENTITY_FLASH . '
//GROUP BY H.key order by cnt DESC limit 0, ' . $keyNumber;
    $query = 'SELECT *, count(H.key) as cnt FROM cms_hashtag AS H where H.entity_type=' . SOCIAL_ENTITY_FLASH . '
GROUP BY H.key order by cnt DESC limit 0, :KeyNumber';
//    $ret = db_query($query);
    $params[] = array( "key" => ":KeyNumber",
                        "value" =>$keyNumber,
                        "type" =>"::PARAM_INT");
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();
    
    $ret    = $select->rowCount();
    if ($res && $ret > 0) {
        $row = $select->fetchAll(PDO::FETCH_ASSOC);
//        while ($row = db_fetch_assoc($ret)) {
//            if ($index == 0) {
//                $allArr['searchMax'] = $row['cnt'];
//                $allArr['keyNumber'] = $keyNumber;
//                $index++;
//            }
//            $allArr['keys'][] = array('val' => $row['cnt'], 'text' => $row['key']);
//        }
        $index =0;
        foreach($row as $row_item){
            if ($index == 0) {
                $allArr['searchMax'] = $row_item['cnt'];
                $allArr['keyNumber'] = $keyNumber;
                $index++;
            }
            $allArr['keys'][] = array('val' => $row_item['cnt'], 'text' => $row_item['key']);
        }
        shuffle($allArr['keys']);
//        $toRet = var_dump($allArr);
        $toRet = displayKeyCloud($allArr);
    }
    return $toRet;
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <end>
}

function save_hashtags($string,$postId){
	global $dbConn;
	$params = array();
    $start = '<b>#';
    $end = '</b>';
    $arrayOne = explode($start, $string);
    $queryPlus = '';
    $query = "INSERT INTO `cms_hashtag`(`entity_type`, `entity_id`, `key`) VALUES ";
    if (count($arrayOne) > 0) {
        $i=0;
        foreach ($arrayOne as $anElt) {
            $aHashTag = substr($anElt, 0, strpos($anElt, $end));            
            if($aHashTag != ''){
                if($queryPlus != '') $queryPlus .=',';
                $queryPlus .= "(".SOCIAL_ENTITY_FLASH.",".$postId.",:AHashTag$i)";
                $params[] = array( "key" => ":AHashTag$i", "value" =>strtolower($aHashTag));
            }
            $i++;
        }
    }    
    if($queryPlus != ''){
        $query .= $queryPlus;
	$select = $dbConn->prepare($query);
        PDO_BIND_PARAM($select,$params);
	$res = $select->execute();
        if($res){
            return true;
        }
        else{
            return false;
        }
    }
    return true;
}
/**
 * adds a flash reechoe to the global flash reechoe table
 * @param integer $user_id the user making the reechoe
 * @param integer $fk the foriegn key
 * @param string $entity_type the type of the reechoe
 * @return integer|false false if failed else the id of the new reechoe
 */
function flashReechoeAdd($user_id,$fk,$entity_type){
    global $dbConn;
    $params  = array(); 
    $params2 = array(); 
    $params3 = array(); 
    $params4 = array(); 
    $entity_info = flashGetInfo($fk);
    if($entity_info){
        $flash_text = $entity_info['flash_text'];
        $flash_link = $entity_info['flash_link'];
        $flash_location = $entity_info['flash_location'];
        $pic_file = $entity_info['pic_file'];
        $vpath = $entity_info['vpath'];
        $longitude = ($entity_info['longitude'] !='')? $entity_info['longitude']: NULL;
        $latitude = ($entity_info['latitude'] !='')? $entity_info['latitude']: NULL;
        $city_id = ($entity_info['city_id'] !='')? $entity_info['city_id']: 0;
        $location_id = ($entity_info['location_id'] !='')? $entity_info['location_id']: 0;
        $location_name = $entity_info['location_name'];
        $query_re = "INSERT INTO cms_flash (user_id,flash_text,flash_link,flash_location,pic_file,vpath,longitude,latitude,reply_to,city_id,location_id,location_name) VALUES (:User_id,:Flash_text,:Flash_link,:Flash_location,:Pic_file,:Vpath,:Longitude,:Latitude,NULL,:City_id,:Location_id,:Location_name)";
        
	$params[] = array( "key" => ":User_id", "value" =>$user_id);
	$params[] = array( "key" => ":Flash_text", "value" =>$flash_text);
	$params[] = array( "key" => ":Flash_link", "value" =>$flash_link);
	$params[] = array( "key" => ":Flash_location", "value" =>$flash_location);
	$params[] = array( "key" => ":Pic_file", "value" =>$pic_file);
	$params[] = array( "key" => ":Vpath", "value" =>$vpath);
	$params[] = array( "key" => ":Longitude", "value" =>$longitude);
	$params[] = array( "key" => ":Latitude", "value" =>$latitude);
	$params[] = array( "key" => ":City_id", "value" =>$city_id);
	$params[] = array( "key" => ":Location_id", "value" =>$location_id);
	$params[] = array( "key" => ":Location_name", "value" =>$location_name);
       
	$insert = $dbConn->prepare($query_re);
	PDO_BIND_PARAM($insert,$params);
	$insert->execute();
	$ret    = $insert->rowCount();
        $echoe_id=$dbConn->lastInsertId();
        $query = "INSERT INTO cms_flash_reechoe (user_id,entity_id,entity_type) VALUES (:User_id,:Fk,:Entity_type)";
	$params2[] = array( "key" => ":User_id", "value" =>$user_id);
	$params2[] = array( "key" => ":Fk", "value" =>$fk);
	$params2[] = array( "key" => ":Entity_type", "value" =>$entity_type);

	$insert2 = $dbConn->prepare($query);
	PDO_BIND_PARAM($insert2,$params2);
	$res = $insert2->execute();
	if(!$res) return false;
        $reechoe_id = $dbConn->lastInsertId();
        newsfeedAdd($user_id, $reechoe_id, SOCIAL_ACTION_REECHOE, $fk, SOCIAL_ENTITY_FLASH , USER_PRIVACY_PRIVATE , null);
        
	$hash_array = getEchoeTags($fk);   
        if($hash_array){
            foreach($hash_array as $item){
                $queryhash = "INSERT INTO `cms_hashtag` (`entity_type`, `entity_id`, `key`) VALUES (".SOCIAL_ENTITY_FLASH.",:Echoe_id,:Key)"; 
                $params3[] = array( "key" => ":Echoe_id", "value" =>$echoe_id);
                $params3[] = array( "key" => ":Key", "value" =>$item['key']);
                $insert3 = $dbConn->prepare($queryhash);
                PDO_BIND_PARAM($insert3,$params3);
                $res = $insert3->execute();
                
            }
        }
	$table='';
	if( $entity_type == SOCIAL_ENTITY_FLASH ){
            $table = 'cms_flash';
            $where = " id=:Fk ";
            $params4[] = array( "key" => ":Fk", "value" =>$fk);
	}
	if($table != ''){
            $query = "UPDATE $table SET n_reflashes = n_reflashes + 1 WHERE $where";
            $update = $dbConn->prepare($query);
            PDO_BIND_PARAM($update,$params4);
            $res = $update->execute();
        }
	newsfeedAdd($user_id, $echoe_id, SOCIAL_ACTION_UPLOAD, $echoe_id, SOCIAL_ENTITY_FLASH , USER_PRIVACY_PUBLIC , null);
	return $reechoe_id;
    }else{
        return false;
    }
}
/**
 * gets the reechoes
 * @param array $options<br/>
 * <b>limit</b>: the maximum number of reechoes records returned. default 10<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table cms_users. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>entity_id</b>: which foreign key. required.<br/>
 * <b>entity_type</b>: what type of reechoes. required.<br/>
 * <b>from_ts</b>: search for reechoes after this date. default null.<br/>
 * <b>to_ts</b>: search for reechoes before this date. default null.<br/>
 * <b>published</b>: published status. 1 => active. default null => doesnt matter.<br/>
 * <b>n_results</b>: return records or number of results. default false.<br/>
 * @return integer|array a set of 'newsfeed records' could either be a comment, of an upload
 */
function socialReechoesGet($srch_options){
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <start>
	global $dbConn;
	$params = array(); 
    $default_opts = array(
        'limit' => 10,
        'page' => 0,
        'orderby' => 'id',
        'order' => 'a',
        'entity_id' => null,
        'entity_type' => null,
        'user_id' => null,
        'published' => 1,
        'from_ts' => null,
        'to_ts' => null,
        'n_results' => false
    );
    $options = array_merge($default_opts, $srch_options);
    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit;
    $orderby = $options['orderby'];
    $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';	
    $where = '';
    if( !is_null($options['user_id']) ){
        if( $where != '') $where .= ' AND ';
//        $where .= " C.user_id='{$options['user_id']}' ";
        $where .= " C.user_id=:User_id ";
	$params[] = array( "key" => ":User_id",
                            "value" =>$options['user_id']);
    }	
    if(!is_null($options['entity_type'])){
        if( $where != '') $where .= " AND ";
//        $where .= " C.entity_type = '{$options['entity_type']}' ";
        $where .= " C.entity_type = :Entity_type ";
	$params[] = array( "key" => ":Entity_type",
                            "value" =>$options['entity_type']);
    }	
    if(!is_null($options['entity_id'])){
        if( $where != '') $where .= " AND ";
//        $where .= " C.entity_id = '{$options['entity_id']}' ";
        $where .= " C.entity_id = :Entity_id ";
	$params[] = array( "key" => ":Entity_id",
                            "value" =>$options['entity_id']);
    }	
    if( !is_null($options['published']) ){
        if( $where != '') $where .= ' AND ';
//        $where .= " C.published='{$options['published']}' ";
        $where .= " C.published=:Published ";
	$params[] = array( "key" => ":Published",
                            "value" =>$options['published']);
    }
    if(!is_null($options['from_ts'])){
        if( $where != '') $where .= " AND ";
//        $where .= " DATE(C.create_ts) >= '{$options['from_ts']}' ";
        $where .= " DATE(C.create_ts) >= :From_ts ";
	$params[] = array( "key" => ":From_ts",
                            "value" =>$options['from_ts']);
    }	
    if(!is_null($options['to_ts'])){
        if( $where != '') $where .= " AND ";
//        $where .= " DATE(C.create_ts) <= '{$options['to_ts']}' ";
        $where .= " DATE(C.create_ts) <= :To_ts ";
	$params[] = array( "key" => ":To_ts",
                            "value" =>$options['to_ts']);
    }
	
    if( $options['n_results'] == false ){
//        $query = "SELECT C.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender
//                  FROM cms_flash_reechoe AS C INNER JOIN cms_users AS U ON C.user_id=U.id
//                  WHERE $where ORDER BY $orderby $order LIMIT $skip,$nlimit";
        $query = "SELECT C.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender
                  FROM cms_flash_reechoe AS C INNER JOIN cms_users AS U ON C.user_id=U.id
                  WHERE $where ORDER BY $orderby $order LIMIT :Skip,:Nlimit";
        
	$params[] = array( "key" => ":Skip",
                            "value" =>$skip,
                            "type" =>"::PARAM_INT");
	$params[] = array( "key" => ":Nlimit",
                            "value" =>$nlimit,
                            "type" =>"::PARAM_INT");
//        $ret = db_query ( $query );
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
        if ( $res && $ret>0 ){
            $ret_arr = array();
            $row = $select->fetchAll();
//            while($row = db_fetch_assoc ( $ret )){
//                if($row['profile_Pic'] == ''){ 
//                    $row['profile_Pic'] = 'he.jpg';
//                    if($row['gender']=='F'){
//                            $row['profile_Pic'] = 'she.jpg';
//                    }
//                }
//                $res[] = $row;
//            }
            
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
        }else{
            return array();
        }
    }else{
        $query = "SELECT COUNT(C.id) FROM cms_flash_reechoe AS C INNER JOIN cms_users AS U ON C.user_id=U.id WHERE $where";
//        $ret = db_query($query);
//        $row = db_fetch_row($ret);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$select->execute();
        $row = $select->fetch();
        return $row[0];
    }
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <end>
}

function socialReechoeRow($item_id) {
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <start>
    global $dbConn;   
    $socialReechoeRow = tt_global_get('socialReechoeRow');   // Added by Devendra on 25th may 2015    
    $params     = array();

    if(isset($socialReechoeRow[$item_id]) && $socialReechoeRow[$item_id]!=''){
        return $socialReechoeRow[$item_id];
    }

	//$query = "SELECT * FROM cms_flash_reechoe WHERE id=:Item_id "; commented by devendra on 22 may 2015 as told by rishav for query optimization.
    $query = "SELECT `id`, `user_id`, `entity_id`, `entity_type`, `create_ts`, `published` FROM `cms_flash_reechoe` WHERE id=:Item_id ";
	$params[] = array( "key" => ":Item_id", "value" =>$item_id);
	
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res = $select->execute();

	$ret    = $select->rowCount();
	if (!$res || ($ret == 0)){    
            $socialReechoeRow[$item_id] =   false;
            return false;
        }else{ 
            $row = $select->fetch(PDO::FETCH_ASSOC);
            $socialReechoeRow[$item_id] =   $row;
            return $row;
        }
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <end>
}

/**
 * remove flash
 * @param type $user_id the logged user
 * @param type $id the id of the flash
 * @return int 1 if done or 0 if not
 */
function removeFlash($user_id,$id){   
	global $dbConn;
	$params  = array(); 
	$params2 = array();  
	$params3 = array();  
	$params4 = array();   
    if( deleteMode() == TT_DEL_MODE_PURGE ){
        $query = "DELETE FROM cms_flash where id=:Id and user_id = :User_id AND published=1";
    }else if( deleteMode() == TT_DEL_MODE_FLAG ){
        $query = "UPDATE cms_flash SET published=".TT_DEL_MODE_FLAG." WHERE id=:Id and user_id = :User_id AND published=1";
    }
    $hash_array = getEchoeTags($id);   
    if($hash_array){
        foreach($hash_array as $item){
            $queryhash = "DELETE FROM cms_hashtag where id=:Item_id";
            $params[] = array( "key" => ":Item_id", "value" =>$item['id']);
            $delete = $dbConn->prepare($queryhash);
            PDO_BIND_PARAM($delete,$params);
            $delete->execute();
        }
    }
    newsfeedDeleteAll($id,SOCIAL_ENTITY_FLASH);
    
    $params4[] = array( "key" => ":Id", "value" =>$id);        
    $params4[] = array( "key" => ":User_id", "value" =>$user_id);        
    $delete_update3 = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete_update3,$params4);
    $ret = $delete_update3->execute();  
    
    return $ret;
}