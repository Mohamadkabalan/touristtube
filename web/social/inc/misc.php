<?php
/**
 * miscellaneous functions that don't have a package on there own or are waiting to be moved to a seperate package
 * @package misc
 */
//////////////////////////
//debugging

/**
 * debug social events
 */
define('DEBUG_TYPE_SOCIAL', 'debug_social');
/**
 * debug media events
 */
define('DEBUG_TYPE_MEDIA', 'debug_media');
/**
 * debug user events
 */
define('DEBUG_TYPE_USER', 'debug_user');
/**
 * debug upload events
 */
define('DEBUG_TYPE_UPLOAD', 'debug_upload');
/**
 * debug search events
 */
define('DEBUG_TYPE_SEARCH', 'debug_search');
/**
 * debug database events
 */
define('DEBUG_TYPE_QUERY', 'debug_query');

/**
 * information log level
 */
define('DEBUG_LVL_INFO', 1);
/**
 * warning log level
 */
define('DEBUG_LVL_WARN', 2);
/**
 * error log level
 */
define('DEBUG_LVL_ERROR', 4);
/**
 * error log level
 */
define('DEBUG_LVL_QUERY', 8);

/**
 * logs a debug_message
 * @global array $CONFIG
 * @param constant $type DEBUG_TYPE_SEARCH,DEBUG_TYPE_QUERY, ...
 * @param constant $lvl DEBUG_LVL_INFO,DEBUG_LVL_WARN,DEBUG_LVL_ERROR
 * @param type $msg 
 */
function TTDebug($type, $lvl, $msg)
{
    global $CONFIG;
    global $request;
//    $file = $_SERVER["SCRIPT_FILENAME"];
    $file = $request->server->get('SCRIPT_FILENAME', '');

    if ($lvl == DEBUG_LVL_INFO) $debug_lvl_string = 'info';
    else if ($lvl == DEBUG_LVL_WARN) $debug_lvl_string = 'warn';
    else if ($lvl == DEBUG_LVL_ERROR) $debug_lvl_string = 'error';
    else if ($lvl == DEBUG_LVL_QUERY) $debug_lvl_string = 'query';
    else $debug_lvl_string = 'unknown';

    if ($lvl & $CONFIG['debug_lvl']) {
        $_msg = str_replace(array("\n", "\r", "\t"), array(' ', ' ', ' '), $msg);
//        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'cli';
        $ip   = $request->server->get('REMOTE_ADDR', 'cli');
        //file_put_contents($CONFIG['server']['root'] . '/log/debug.log', date('Y-m-d H:i:s') . ' - ' . $ip . ' - ' . $file . ' - ' . $type . ' - ' . $debug_lvl_string . ' - '. $_msg . PHP_EOL, FILE_APPEND);
        //file_put_contents($CONFIG['server']['root'] . '/debug.log', date('Y-m-d H:i:s') . ' - ' . $ip . ' - ' . $file . ' - ' . $type . ' - ' . $debug_lvl_string . ' - '. $_msg . PHP_EOL, FILE_APPEND);
    }
}
//////////////////////////////////
//deletion

/**
 * just flag the record to be deleted
 */
define('TT_DEL_MODE_FLAG', -2);
/**
 * a purge delete that actually removes all traces
 */
define('TT_DEL_MODE_PURGE', -3);

/**
 * the current global delete mode
 * @ignore
 */
$_tt_global_delete_mode = TT_DEL_MODE_FLAG;

/**
 * gets or sets the deletemode to be used by the delete function
 * @param constant $del_mode {TT_DEL_MODE_FLAG|TT_DEL_MODE_PURGE}
 * @return the delete mode
 */
function deleteMode($del_mode = null)
{
    global $_tt_global_delete_mode;
    if (is_null($del_mode)) {
        //get
        return $_tt_global_delete_mode;
    } else {
        //set
        $_tt_global_delete_mode = $del_mode;
    }
}

function htmlEntityDecode($val, $stripslashe = 1)
{
    if ($stripslashe == 1) {
        $val = stripslashes($val);
    }
    $val = html_entity_decode($val);
    if ($stripslashe == 0) {
        $val = preg_replace('#(\\\r|\\\r\\\n|\\\n)#', '<br/>', $val);
        $val = stripslashes($val);
    }
    return $val;
}
/////////////////////////////
//sanitization

require_once 'functions/htmlpurifier/HTMLPurifier.standalone.php';

/**
 * cleans an input strign against xss attacks   By KHADRA
 * @param string $data input string to clean
 * @return string sanitized string
 * */
function xss_clean($input_str)
{
//    $return_str = str_replace( array('<','>',"'",'"',')','('), array('&lt;','&gt;','&apos;','&#x22;','&#x29;','&#x28;'), $input_str );
//    $return_str = str_ireplace( '%3Cscript', '', $return_str );
//    return $return_str;
//    return htmlentities($data);
//    	$config = HTMLPurifier_Config::createDefault();
//        $config->set('Core', 'Encoding', 'UTF-8');
//        $config->set('HTML', 'Doctype', 'HTML 4.01 Transitional');
//	$config->set('HTML.Allowed', array("p", "div","span"));
//	$sanitiser = new HTMLPurifier($config);
//        $clean_html = $sanitiser->purify($input_str);
    /* require_once('../vendor/autoload.php'); */

    if (trim($input_str) == '') return $input_str;
    $config     = HTMLPurifier_Config::createDefault();
    $config->set('HTML.AllowedElements', array("p", "span", "b"));
    $config->set('HTML.AllowedAttributes', array('data-id'));
    $def        = $config->getHTMLDefinition(true);
    $def->addAttribute('span', 'data-id', 'Text');
    $purifier   = new HTMLPurifier($config);
    $clean_html = $purifier->purify($input_str);
    return $clean_html;

    //return $input_str;
}
/**
 * cleans an input strign against xss attacks   By KHADRA then Joseph
 * @param string $data input string to clean
 * @return string sanitized string
 * */
//CODE NOT USED - COMMENTS BY KHADRA 
//function xss_clean_flash($input_str) {
//
//
//    $config = HTMLPurifier_Config::createDefault();
//    $config->set('HTML.AllowedElements', array("p", "span", "b"));
//    $def = $config->getHTMLDefinition(true);
//    $def->addAttribute('span', 'data-id', 'Text');
//    $purifier = new HTMLPurifier($config);
//    $clean_html = $purifier->purify($input_str);
//    return $clean_html;
//
//    //return $input_str;
//}

/**
 * cleans an input strign against xss attacks
 * @param string $data input string to clean
 * @return string sanitized string
 * */
//CODE NOT USED - COMMENTS BY KHADRA
//function xss_clean_old2($data) {
//    $config = HTMLPurifier_Config::createDefault();
//    //$config->set('HTML.AllowedAttributes', array("*.class","*.style","*.data-id","span.data-id","data-id"));
//    $config->set('HTML.AllowedElements', array("p", "div", "span"));
//
//    $purifier = new HTMLPurifier($config);
//
//    $def = $config->getHTMLDefinition(true);
//    $def->addAttribute('span', 'data-id', 'Text');
//
//    $clean_html = $purifier->purify($data);
//    return $clean_html;
//}
//CODE NOT USED - COMMENTS BY KHADRA 
//function xss_clean_old($data) {
//// Fix &entity\n;
//    $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
//    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
//    $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
//    $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
//
//// Remove any attribute starting with "on" or xmlns
//    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
//
//// Remove javascript: and vbscript: protocols
//    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
//    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
//    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
//
//// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
//    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
//    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
//    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
//
//// Remove namespaced elements (we do not need them)
//    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
//
//    do {
//// Remove really unwanted tags
//        $old_data = $data;
//        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
//    } while ($old_data !== $data);
//
//// we are done...
//    return $data;
//}

/**
 * sanitizes and escapes an input string againts xss and sql injection
 * @param string $data input string to be sanitized and escaped
 * @return string sanitized and escaped string 
 */
function xss_sanitize($data)
{
    return db_sanitize(xss_clean($data));
}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function string_sanitize($s) {
//    $result = preg_replace("/[^ .?!,\-a-zA-Z0-9]+/", "", html_entity_decode($s, ENT_QUOTES));
//    return $result;
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>
//////////////////////////////////////
//continent - country - city

/**
 * gets the continent info given the code
 * @param char(2) $continent_code
 * @return array|false the cms_continents record or false if none found
 */
function continentGetInfo($continent_code)
{
    global $dbConn;
    $params = array();

    $query    = "SELECT * FROM `cms_continents` WHERE code=:Continent_code";
    $params[] = array("key" => ":Continent_code",
        "value" => $continent_code);
    $select   = $dbConn->prepare($query);
    PDO_BIND_PARAM($select, $params);
    $res      = $select->execute();

    $ret = $select->rowCount();
    if ($res && $ret == 1) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        return $row;
    } else {
        return false;
    }
}

/**
 * gets the cms_countries record given the 2 letter code
 * @param char(2) $country_code 2 letter country code
 * @return array|false the cms_countries record or false if none found
 */
function countryGetInfo($country_code)
{
    global $dbConn;
    $params = array();

    $countryGetInfo = tt_global_get('countryGetInfo');
    if (isset($countryGetInfo[$country_code]) && $countryGetInfo[$country_code] != '')
            return $countryGetInfo[$country_code];

    $query    = "SELECT * FROM `cms_countries` WHERE code=:Country_code LIMIT 1";
    $params[] = array("key" => ":Country_code",
        "value" => $country_code);
    $select   = $dbConn->prepare($query);
    PDO_BIND_PARAM($select, $params);
    $res      = $select->execute();

    $ret = $select->rowCount();
    if ($res && $ret == 1) {
        $row                           = $select->fetch(PDO::FETCH_ASSOC);
        $countryGetInfo[$country_code] = $row;
        return $row;
    } else {
        $countryGetInfo[$country_code] = false;
        return false;
    }
}

/**
 * gets the cms_countries record given the name
 * @param string $name the name of the country
 * @return array|false the cms_countries record or false if none found
 */
function countryNameInfo($name)
{
    global $dbConn;
    $params     = array();
    $lower_name = strtolower($name);
    $_name      = ucfirst($lower_name);
    $query      = "SELECT * FROM `cms_countries` WHERE name=:Name LIMIT 1";
    $params[]   = array("key" => ":Name",
        "value" => $_name);
    $select     = $dbConn->prepare($query);
    PDO_BIND_PARAM($select, $params);
    $res        = $select->execute();

    $ret = $select->rowCount();
    if ($res && $ret == 1) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        return $row;
    } else {
        return false;
    }
}

/**
 * gets the 2 letter code of a country given its name
 * @param string $name the name of the country
 * @return false | string the 2 letter country code if found or false if not found
 */
function countryGetCode($name)
{
    global $dbConn;
    $params     = array();
    $lower_name = strtolower($name);
    $_name      = ucfirst($lower_name);

    $name_md5 = md5($lower_name);

    $query    = "SELECT code FROM `cms_countries` WHERE name LIKE :Name";
    $params[] = array("key" => ":Name",
        "value" => $_name."%");
    $select   = $dbConn->prepare($query);
    PDO_BIND_PARAM($select, $params);
    $res      = $select->execute();

    $ret = $select->rowCount();
    if ($res && $ret == 1) {
        $row = $select->fetch();
        return $row[0];
    } else {
        return false;
    }
}

/**
 * gets the 2 letter code of a country given its name
 * @param string $name the name of the country
 * @return false | string the 2 letter country code if found or false if not found
 */
function countryGetName($code)
{
    global $dbConn;
    $countryGetName = tt_global_get('countryGetName');  // Added by Devendra on 25th may 2015
    $params         = array();

    if (isset($countryGetName[$code]) && $countryGetName[$code] != '')
            return $countryGetName[$code];

    $_code = strtoupper($code);

    $query    = "SELECT name FROM `cms_countries` WHERE code=:Code";
    $params[] = array("key" => ":Code", "value" => $_code);
    $select   = $dbConn->prepare($query);
    PDO_BIND_PARAM($select, $params);
    $res      = $select->execute();

    $ret = $select->rowCount();
    if ($res && $ret == 1) {
        $row                   = $select->fetch();
        $countryGetName[$code] = $row[0];
        return $row[0];
    } else {
        $countryGetName[$code] = false;
        return false;
    }
}

/**
 * gets list of countries
 * @return false | list of countries
 */
function countryGetList()
{
    global $dbConn;
    $params = array();
    $query  = "SELECT * FROM `cms_countries` ORDER BY name ASC";
    $select = $dbConn->prepare($query);

    $res = $select->execute();

    $ret = $select->rowCount();
    if ($res && $ret > 0) {
        $ret = $select->fetchAll();
        return $ret;
    } else {
        return false;
    }
}

/**
 * gets the 2 letter code of a country given its name
 * @param string $name the name of the country
 * @return false | string the 2 letter country code if found or false if not found
 */
function continentGetCode($name)
{
    global $dbConn;
    $params = array();
    $_name  = strtolower($name);

    $query    = "SELECT code FROM `cms_continents` WHERE LOWER(name)=:Name";
    $params[] = array("key" => ":Name",
        "value" => $_name);
    $select   = $dbConn->prepare($query);
    PDO_BIND_PARAM($select, $params);
    $res      = $select->execute();

    $ret = $select->rowCount();
    if ($res && $ret == 1) {
        $row = $select->fetch();
        return $row[0];
    } else {
        return false;
    }
}

/**
 * returns gets the country/city record most likelt to reflect a location
 * @param double $lat
 * @param double $long
 */
function locationDecompose($lat, $long, $in_radius = 1000)
{
    global $dbConn;
    $params = array();
    $lat    = doubleval($lat);
    $long   = doubleval($long);

    //same code in inc/functions/videos.php -> mediaSearch
    $long_rad  = deg2rad($long);
    $c         = 40075;
    $lat_conv  = doubleval(110000.0);
    $long_conv = 1000 * ($c * cos($long_rad)) / 360;

    $radius = $in_radius;

    $diff_lat  = abs($radius / $lat_conv);
    $diff_long = abs($radius / $long_conv);

    $query = "SELECT country_code as Country, name as City, accent as AccentCity, Latitude, Longitude FROM webgeocities WHERE Latitude < :Lat + :Diff_lat AND Latitude > :Lat - :Diff_lat AND Longitude < :Long + :Diff_long AND Longitude > :Long - :Diff_long  ";

    $params[] = array("key" => ":Lat",
        "value" => $lat);
    $params[] = array("key" => ":Diff_lat",
        "value" => $diff_lat);
    $params[] = array("key" => ":Long",
        "value" => $long);
    $params[] = array("key" => ":Diff_long",
        "value" => $diff_long);
    $select   = $dbConn->prepare($query);
    PDO_BIND_PARAM($select, $params);
    $res      = $select->execute();

    $ret = $select->rowCount();

    if ($res && ($ret != 0)) {
        $min_row  = false;
        $min_dist = 1000000;

        $row = $select->fetchAll(PDO::FETCH_ASSOC);
        foreach ($row as $row_item) {
            $dist = LocationDiff($lat, $long, $row_item['Latitude'],
                $row_item['Longitude']);
            if ($dist < $min_dist) {
                $min_row  = $row_item;
                $min_dist = $dist;
            }
        }
        return $min_row;
    } else {
        return false;
    }
}

/**
 * gets the city record
 * @param integer $id the world cities pop's id
 * @return array | false the webgeocities record or null if not found
 */
function worldcitiespopInfo($id)
{
    global $dbConn;
    $params   = array();
    if (isset($worldcitiespopInfo[$id])) return $worldcitiespopInfo[$id];
    $query    = "SELECT `id`, `country_code`, `state_code`, `accent`, `name`, `latitude`, `longitude`, `timezoneid`, `order_display` FROM `webgeocities` WHERE id=:Id";  //Added by Devendra
    $params[] = array("key" => ":Id", "value" => $id);
    $select   = $dbConn->prepare($query);
    PDO_BIND_PARAM($select, $params);
    $res      = $select->execute();

    $ret = $select->rowCount();
    if ($res && $ret > 0) {
        $row                     = $select->fetch(PDO::FETCH_ASSOC);
        $worldcitiespopInfo[$id] = $row;
        return $row;
    } else {
        $worldcitiespopInfo[$id] = false;
        return false;
    }
}

/**
 * gets the list of discover worldcities available
 * @return array | false the webgeocities record or null if not found
 */
function discoverWorldcitiesAvailable()
{
    global $dbConn;
    $params = array();
    $query  = "SELECT C.id as cityid,C.country_code, C.state_code, C.name, C.latitude, C.longitude, CO.name AS country_name, ST.state_name FROM webgeocities AS C INNER JOIN cms_countries AS CO ON C.country_code=CO.code LEFT JOIN states AS ST ON C.country_code=ST.country_code AND C.state_code=ST.state_code WHERE C.order_display>=2 ORDER BY C.name ASC";

    $select = $dbConn->prepare($query);

    $res = $select->execute();

    $ret = $select->rowCount();
    if (!$res || ($ret == 0)) {
        return false;
    } else {
        $ret_arr = $select->fetchAll();

        return $ret_arr;
    }
}

/**
 * gets the state record
 * @param $country_code 
 * @param $state_code 
 * @return array | false the webgeocities record or null if not found
 */
function worldStateInfo($country_code, $state_code)
{
    global $dbConn;
    $worldStateInfo = tt_global_get('worldStateInfo');  //Added by Devendra on 25th may 2015
    $params         = array();
    if (isset($worldStateInfo[$country_code][$state_code]) && $worldStateInfo[$country_code]
        != '') return $worldStateInfo[$country_code][$state_code];


    $query    = "SELECT `country_code`, `state_code`, `state_name` FROM `states` WHERE country_code=:Country_code AND state_code=:State_code";
    $params[] = array("key" => ":Country_code", "value" => $country_code);
    $params[] = array("key" => ":State_code", "value" => $state_code);
    $select   = $dbConn->prepare($query);
    PDO_BIND_PARAM($select, $params);
    $res      = $select->execute();

    $ret = $select->rowCount();
    if ($res && $ret != 0) {
        $row                                        = $select->fetch(PDO::FETCH_ASSOC);
        $worldStateInfo[$country_code][$state_code] = $row;
        return $row;
    } else {
        $worldStateInfo[$country_code][$state_code] = false;
        return false;
    }
}

/**
 * gets the city record from webgeocities given the city record from webgeocities
 * if the record doesnt exist it is created and returned
 * @param array $record the city record
 * @return array the webgeocities record
 */
function cityGetRecord($record)
{

    global $dbConn;
    $params = array();
    $lat    = doubleval($record['Latitude']);
    $long   = doubleval($record['Longitude']);

    $query    = "SELECT * FROM webgeocities WHERE latitude=:Lat AND longitude=:Long ";
    $params[] = array("key" => ":Lat",
        "value" => $lat);
    $params[] = array("key" => ":Long",
        "value" => $long);
    $select   = $dbConn->prepare($query);
    PDO_BIND_PARAM($select, $params);
    $res      = $select->execute();

    $ret = $select->rowCount();
    if (!$res || $ret == 0) {
        return false;
    } else {
        $row = $select->fetch();
        return $row;
    }
}

/**
 * gets the location of a city in webgeocities (the record should exist)
 * @param integer $id the webgeocities record id
 * @return array the latitude and longitude
 */
function cityGetLocation($id)
{
    global $dbConn;
    $params = array();

    $query    = "SELECT * FROM webgeocities WHERE id=:Id";
    $params[] = array("key" => ":Id",
        "value" => $id);
    $select   = $dbConn->prepare($query);
    PDO_BIND_PARAM($select, $params);
    $res      = $select->execute();
    $row      = $select->fetch();
    return array($row['latitude'], $row['longitude']);
}

/**
 * gets the webgeocities record
 * @param integer $id the webgeocities id
 * @return false|array false or webgeocities record
 */
function cityGetInfo($id)
{

    global $dbConn;
    $params   = array();
    $query    = "SELECT * FROM webgeocities WHERE id=:Id";
    $params[] = array("key" => ":Id",
        "value" => $id);
    $select   = $dbConn->prepare($query);
    PDO_BIND_PARAM($select, $params);
    $res      = $select->execute();

    $ret = $select->rowCount();
    if (!$res || ($ret == 0)) return false;
    $row = $select->fetch();
    return $row;
}

/**
 * checks to see if a query is valid to be inserted into the database
 * @param string $string the string to check for validity
 * @return boolean if the string is valid or not
 */
function queryValid($string)
{
    if (strlen($string) <= 2) return false;

    return true;
}

/**
 * adds a query to the query tables for trends analysis
 * @param string $searchString space separated list of search terms
 */
function queryAddRegular($searchString)
{

    global $dbConn;
    $_searchString       = $searchString;
    $_searchString       = remove_accents($_searchString);
    $_searchString       = preg_replace("/[^A-Za-z]/", " ", $_searchString);
    $_searchString       = strtolower($_searchString);
    $singleSearchStrings = explode(' ', $_searchString);

    foreach ($singleSearchStrings as $string) {

        $mod_string = $string;

        if (queryValid($string)) {
            $params   = array();
            $query    = "INSERT INTO cms_queries_log (query_string) VALUES (:Mod_string)";
            $params[] = array("key" => ":Mod_string",
                "value" => $mod_string);
            $insert   = $dbConn->prepare($query);
            PDO_BIND_PARAM($insert, $params);
            $res      = $insert->execute();

            $params = array();
            $query2 = "INSERT INTO cms_queries (query_string,n_occurrence,trend_candidate) VALUES (:Mod_string,1,0) ON DUPLICATE KEY UPDATE n_occurrence=n_occurrence+1;";

            $params[] = array("key" => ":Mod_string",
                "value" => $mod_string);
            $insert2  = $dbConn->prepare($query2);
            PDO_BIND_PARAM($insert2, $params);
            $res      = $insert2->execute();
        }
    }
}

/**
 * adds a query to the query tables for trends analysis
 * @param string $searchString space separated list of search terms
 */
function queryAdd($searchString)
{

    global $dbConn;
    $params = array();

    $new_string = strtolower($searchString);

    $query    = "INSERT INTO cms_queries_log (query_string) VALUES (:New_string)";
    $params[] = array("key" => ":New_string",
        "value" => $new_string);
    $insert   = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert, $params);
    $res      = $insert->execute();

    $query    = "INSERT INTO cms_queries (query_string,n_occurrence,trend_candidate) VALUES (:New_string,1,1) ON DUPLICATE KEY UPDATE n_occurrence=n_occurrence+1;";
    $params   = array();
    $params[] = array("key" => ":New_string", "value" => $new_string);
    $insert   = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert, $params);
    $res      = $insert->execute();
}

/**
 * update trends table
 */
function updateTrendsTable()
{
    global $dbConn;
    $params = array();
    $query  = "SELECT wc.id , wc.name, count(v.id) as cnt FROM  webgeocities wc
                INNER JOIN cms_videos v on v.cityid = wc.id  
                where v.is_public = 2
                group by wc.id, wc.name
                order by cnt desc
                limit 20";

    $select = $dbConn->prepare($query);
    $res    = $select->execute();
    $ret    = $select->rowCount();

    if (!$res || $ret == 0) return array();

    $query  = "DELETE FROM `cms_trends` WHERE 1";
    $delete = $dbConn->prepare($query);
    $delete->execute();

    $row = $select->fetchAll();
    foreach ($row as $row_item) {
        $query  = "INSERT INTO `cms_trends`(`city_id`, `city_name`) VALUES (".$row_item['id'].",'".$row_item['name']."')";
        $insert = $dbConn->prepare($query);
        $insert->execute();
    }
}

/**
 * returns the current search trends
 * @return array an array of popular search strings
 */
function queryGetTrends($limit = 20, $page = 0)
{
    global $dbConn;
    $params = array();
    $skip   = $page * $limit;
    $query  = "SELECT * FROM `cms_trends` WHERE 1 limit 20";

    $select = $dbConn->prepare($query);

    $res = $select->execute();

    $ret = $select->rowCount();

    if (!$res || ($ret == 0)) return array();

    $ret_arr = array();
    $font    = 16;
    $margin  = 1;
    $count   = 0;
    $row     = $select->fetchAll();

    foreach ($row as $row_item) {
        if ($count == 5) {
            $count  = 0;
            $font   -= 2;
            $margin += 1;
        }
        if ($margin == 4) $margin    = 3;
        $ret_arr[] = array('name' => $row_item['city_name'], 'font' => $font, 'margin' => $margin,
            'class' => 'rand-'.rand(1, 4));
        $count++;
    }
    shuffle($ret_arr);
    return $ret_arr;
}

/**
 * gets a list of the categories
 * @return array the category rows as a hashof id => title
 */
function categoryGetHash($srch_options = array())
{
    global $dbConn;
    $params = array();

    $default_opts = array(
        'hide_all' => false,
        'orderby' => 'title',
        'order' => 'a',
        'in' => ''
    );
    $lang_code    = LanguageGet();
    $languageSel  = '';
    $languageJoin = '';
    $languageAnd  = '';
    $options      = array_merge($default_opts, $srch_options);
    $order_by     = 'c.'.$options['orderby'];
    $order        = ($options['order'] == 'a') ? 'ASC' : 'DESC';

//  $in = !empty($options['in']) ? 'AND c.id IN :In'  : '';
    if (!empty($options['in'])) {
        $in       = " AND c.id IN :In";
        $params[] = array("key" => ":In",
            "value" => $options['in']);
    } else {
        $in = "";
    }

    if ($lang_code != 'en') {
        $languageSel  = ', ml.title as ml_title';
        $languageJoin = ' INNER JOIN ml_allcategories ml on c.id = ml.entity_id ';

        $languageAnd = " and ml.lang_code=:Lang_code";
        $params[]    = array("key" => ":Lang_code",
            "value" => $lang_code);
    }
    //echo $VideoCategryListSQL = "Select c.id, c.title, c.published,  c.item_order $languageSel from cms_allcategories c INNER JOIN cms_videos v on c.id = v.category$languageJoin where c.published = 1 $in $languageAnd order by $order_by $order";
    $VideoCategryListSQL = "Select distinct c.id, c.title, c.published,  c.item_order $languageSel from cms_allcategories c INNER JOIN cms_videos v on c.id = v.category $languageJoin where c.published = 1 $in $languageAnd order by $order_by $order";

    $select = $dbConn->prepare($VideoCategryListSQL);

    PDO_BIND_PARAM($select, $params);
    $VideoCategoryListResult = $select->execute();


    $ret = array();
    if (!$options['hide_all']) {
        $ret[0] = 'All';
    }

    //$ret1 = $select->rowCount();
    /*
     * TODO TO CLEAN
     */
    $VideoCategoryListRes = $select->fetchAll();
    foreach ($VideoCategoryListRes as $catarray) {
        if ($lang_code == 'en') {
            $ret[$catarray['id']] = htmlEntityDecode($catarray['title']);
        } else {
            $ret[$catarray['id']] = htmlEntityDecode($catarray['ml_title']);
        }
    }
    return $ret;
}

/**
 * gets the category id given its name
 * @param string $cat_name
 * @return initeger the category id  
 */
function categoryGetID($cat_name)
{
    global $dbConn;
    $params       = array();
    $lang_code    = LanguageGet();
    $languageSel  = '';
    $languageJoin = '';
    $languageAnd  = '';
    $titleOr      = '';
    if ($lang_code != 'en') {
        $languageSel  = ',ml.title as ml_title';
        $languageJoin = ' INNER JOIN ml_allcategories ml on c.id = ml.entity_id ';

        $languageAnd = " and ml.lang_code=:Lang_code";

        $titleOr  = " or ml.title = :Cat_name";
        $params[] = array("key" => ":Lang_code",
            "value" => $lang_code);
        $params[] = array("key" => ":Cat_name",
            "value" => $cat_name);
    }
    $query    = "Select c.id from cms_allcategories c$languageJoin where ( c.title=:Cat_name2 $titleOr) AND c.published = 1$languageAnd";
    $params[] = array("key" => ":Cat_name2",
        "value" => $cat_name);
    $select   = $dbConn->prepare($query);
    PDO_BIND_PARAM($select, $params);
    $res      = $select->execute();

    $ret = $select->rowCount();

    if (!$res || ($ret == 0)) {
        return false;
    } else {

        $row = $select->fetch();
        return $row[0];
    }
}

/**
 * gets the category info
 * @param intiger $cat_id the category id
 * @return category info record   
 */
function categoryGetInfo($cat_id)
{
    global $dbConn;
    $params       = array();
    $lang_code    = LanguageGet();
    $languageSel  = '';
    $languageJoin = '';
    $languageAnd  = '';
    if ($lang_code != 'en') {
        $languageSel  = ',ml.title as ml_title';
        $languageJoin = ' INNER JOIN ml_allcategories ml on c.id = ml.entity_id ';
//        $languageAnd = " and ml.lang_code='$lang_code'";
        $languageAnd  = " AND ml.lang_code=:Lang_code";
        $params[]     = array("key" => ":Lang_code",
            "value" => $lang_code);
    }
//    $query = "Select c.*$languageSel from cms_allcategories c$languageJoin where c.id='$cat_id' AND c.published = 1";
    $query    = "Select c.*$languageSel from cms_allcategories c$languageJoin where c.id=:Cat_id AND c.published = 1 $languageAnd";
    $params[] = array("key" => ":Cat_id",
        "value" => $cat_id);
//    $res = db_query($query);
    $select   = $dbConn->prepare($query);
    PDO_BIND_PARAM($select, $params);
    $res      = $select->execute();

    $ret = $select->rowCount();
    if (!$res || ($ret == 0)) {
        return false;
    } else {
//        $row = db_fetch_array($res);
        $row = $select->fetch();
        if ($lang_code == 'en') {
            $ret_arr = $row;
        } else {
            $cat1['id']         = $row['id'];
            $cat1['published']  = $row['published'];
            $cat1['item_order'] = $row['item_order'];
            $cat1['title']      = $row['ml_title'];
            $ret_arr            = $cat1;
        }
        return $ret_arr;
    }
}

/**
 * gets the load of the server as a percentage
 * @return int between 0 and 100 
 */
function get_server_load_percentage()
{
    $processors = 0;
    exec("cat /proc/cpuinfo | grep processor | wc -l", $processors);
    $processors = intval($processors[0]);
    $sys_load   = sys_getloadavg();
    $load       = $sys_load[0];
    return intval(($load * 100) / $processors);
}

/**
 * converts a time 01:00:12 to the number of seconds
 * @param string $time the time in string format
 * @return integer the number of seconds 
 */
function time_to_seconds($time)
{
    $time_arr = explode(':', $time);
    return intval($time_arr[0]) * 3600 + intval($time_arr[1]) * 60 + intval($time_arr[0]);
}

/**
 * cuts a sentence and appends 3 dots if necessary
 * @param string $sentence the sentence to cut
 * @param integer $max_chars maximum chars
 * @param integer $line_width the number of characters in the line in case of multiple lines
 * @return string 
 */
function cut_sentence_length($sentence, $max_chars, $line_width = 100)
{
    $out        = str_replace('  ', ' ', $sentence);
    $left_chars = $max_chars;
    $orig       = $out;
    $out        = substr($orig, 0, $left_chars);
    $left_chars -= $line_width * substr_count($out, '<br/>');
    $left_chars -= $line_width * substr_count($out, '<br>');
    if ($left_chars < 0) {
        //too many new lines just take till the first new line.
        $first_new_line_pos = min(array(strpos($out, '<br/>'), strpos($out,
                '<br>')));
        $out                = substr($out, 0, $first_new_line_pos);
        $out                .= ' ...';
        return $out;
    }
    if (strlen($orig) > $left_chars) {
        $out      = substr($orig, 0, $left_chars);
        $desc_arr = explode(' ', $out);
        if (strstr($out, ' '.$desc_arr[count($desc_arr) - 1].' ') == null)
                unset($desc_arr[count($desc_arr) - 1]);
        $out      = implode(' ', $desc_arr);
        $out      .= ' ...';
    }
    return $out;
}

function truncate($text, $in_length = 100, $options = array())
{
    $length  = $in_length;
    $default = array(
        'ellipsis' => '...', 'exact' => false, 'html' => true, 'line_width' => 100
    );
    if (isset($options['ending'])) {
        $default['ellipsis'] = $options['ending'];
    } elseif (!empty($options['html']) && Configure::read('App.encoding') === 'UTF-8') {
        $default['ellipsis'] = "\xe2\x80\xa6";
    }
    $options = array_merge($default, $options);
    extract($options);

    if (!function_exists('mb_strlen')) {
        class_exists('Multibyte');
    }

    if ($html) {
        if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            return $text;
        }
        $totalLength = mb_strlen(strip_tags($ellipsis));
        $openTags    = array();
        $truncate    = '';

        preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags,
            PREG_SET_ORDER);
        foreach ($tags as $tag) {
            if ($tag[2] == 'br') {
                //if there is one br and the length is exactly equal to 1 line and the break is on the first line this doesn't work
                $length -= $line_width;
            }
            if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s',
                    $tag[2])) {
                if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
                    array_unshift($openTags, $tag[2]);
                } elseif (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
                    $pos = array_search($closeTag[1], $openTags);
                    if ($pos !== false) {
                        array_splice($openTags, $pos, 1);
                    }
                }
            }

            $truncate .= $tag[1];

            $contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i',
                    ' ', $tag[3]));
            if ($contentLength + $totalLength > $length) {
                $left           = $length - $totalLength;
                $entitiesLength = 0;
                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i',
                        $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
                    foreach ($entities[0] as $entity) {
                        if ($entity[1] + 1 - $entitiesLength <= $left) {
                            $left--;
                            $entitiesLength += mb_strlen($entity[0]);
                        } else {
                            break;
                        }
                    }
                }

                $truncate .= mb_substr($tag[3], 0, $left + $entitiesLength);
                break;
            } else {
                $truncate    .= $tag[3];
                $totalLength += $contentLength;
            }
            if ($totalLength >= $length) {
                break;
            }
        }
    } else {
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        $truncate = mb_substr($text, 0, $length - mb_strlen($ellipsis));
    }
    if (!$exact) {
        $spacepos = mb_strrpos($truncate, ' ');
        if ($html) {
            $truncateCheck = mb_substr($truncate, 0, $spacepos);
            $lastOpenTag   = mb_strrpos($truncateCheck, '<');
            $lastCloseTag  = mb_strrpos($truncateCheck, '>');
            if ($lastOpenTag > $lastCloseTag) {
                preg_match_all('/<[\w]+[^>]*>/s', $truncate, $lastTagMatches);
                $lastTag  = array_pop($lastTagMatches[0]);
                $spacepos = mb_strrpos($truncate, $lastTag) + mb_strlen($lastTag);
            }
            $bits = mb_substr($truncate, $spacepos);
            preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
            if (!empty($droppedTags)) {
                if (!empty($openTags)) {
                    foreach ($droppedTags as $closingTag) {
                        if (!in_array($closingTag[1], $openTags)) {
                            array_unshift($openTags, $closingTag[1]);
                        }
                    }
                } else {
                    foreach ($droppedTags as $closingTag) {
                        $openTags[] = $closingTag[1];
                    }
                }
            }
        }
        $truncate = mb_substr($truncate, 0, $spacepos);
    }
    $truncate .= $ellipsis;

    if ($html) {
        foreach ($openTags as $tag) {
            $truncate .= '</'.$tag.'>';
        }
    }

    return $truncate;
}
/**
 * Truncates text.
 *
 * Cuts a string to the length of $length and replaces the last characters
 * with the ending if the text is longer than length.
 *
 * @param string $text String to truncate.
 * @param integer $in_length Length of returned string, including ellipsis.
 * @param string $ending Ending to be appended to the trimmed string.
 * @param boolean $exact If false, $text will not be cut mid-word
 * @param boolean $considerHtml If true, HTML tags would be handled correctly
 * @return string Trimmed string.
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  05/05/2015
//<start>
//function substrhtml($str, $start, $total_len, $line_width = 100) {
//
//    $len = $total_len;
//    $str_clean = substr(strip_tags($str), $start, $len);
//
//    if (preg_match_all('/\<[^>]+>/is', $str, $matches, PREG_OFFSET_CAPTURE)) {
//
//        for ($i = 0; $i < count($matches[0]); $i++) {
//
//            if (strstr($matches[0][$i][0], 'br') != null) {
//                $len -= $line_width;
//            }if ($matches[0][$i][1] <= $len) {
//
//                $str_clean = substr($str_clean, 0, $matches[0][$i][1]) . $matches[0][$i][0] . substr($str_clean, $matches[0][$i][1]);
//            } else if (preg_match('/\<[^>]+>$/is', $matches[0][$i][0])) {
//
//                $str_clean = substr($str_clean, 0, $matches[0][$i][1]) . $matches[0][$i][0] . substr($str_clean, $matches[0][$i][1]);
//
//                break;
//            }
//        }
//
//        return $str_clean;
//    } else {
//
//        return substr($str, $start, $len);
//    }
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  05/05/2015
//<end>

/**
 * for mat a number so that the maximum
 * @param integer $in 
 * @return string the formatted output
 */
function tt_number_format($in)
{
    if ($in == '') return 0;
    if ($in == 0) return 0;
    $out = intval($in);
    if ($out >= 1000) {
        $out = intval($out / 100);
        $out = $out / 10;
        $out .= 'k';
    }
    return $out;
}
/**
 * this is a global variable used to store data to pass between includes 
 */
$_tt_global_variables = array();

/**
 * store a variable to pass it cleanly between includes
 * @global array $_tt_global_variables
 * @param string $var the variable to be stored
 * @param mixed $val the value
 */
function tt_global_set($var, $val)
{
    global $_tt_global_variables;
    $_tt_global_variables[$var] = $val;
}

/**
 * checks to see if a global variable has been set
 * @global array $_tt_global_variables
 * @param string $var the name of the variable
 * @return boolean true|false if set or not set 
 */
function tt_global_isset($var)
{
    global $_tt_global_variables;
    return (isset($_tt_global_variables[$var]) && $_tt_global_variables[$var] != '')
            ? true : false;
}

/**
 * gets the global variable
 * @global array $_tt_global_variables
 * @param string $var the name of the variable
 * @return mixed the variable if exists or false if not exists
 */
function tt_global_get($var)
{
    global $_tt_global_variables;
    return isset($_tt_global_variables[$var]) ? $_tt_global_variables[$var] : false;
}

/**
 * formats a timestamp into the posted format
 * @param integer $in_ts thimestamp
 * @return string the formated posted string
 */
function formatPostedDate($in_ts)
{
    /*
      $time_min = intval( (time() - $in_ts)/60 );
      $time_hours = intval($time_min/60);
      $time_days = intval($time_hours/24);
      $time_month = intval($time_days/30);
      if($time_min <= 1){
      $time = 1;
      $which_msg = 'modal_posted_minute';
      }else if($time_hours < 2){
      $time = $time_min;
      $which_msg = 'modal_posted_minutes';
      }else if($time_days < 2){
      $time = $time_hours;
      $which_msg = 'modal_posted_hours';
      }else if($time_days < 31){
      $time = $time_days;
      $which_msg = 'modal_posted_days';
      }else{
      $time = $time_month;
      $which_msg = 'modal_posted_months';
      } */

    $time_min   = intval((time() - $in_ts) / 60);
    $time_hours = intval($time_min / 60);
    $time_days  = intval($time_hours / 24);
    $time_week  = intval($time_days / 7);
    $time_month = intval($time_days / 30);
    $time_yeari = intval($time_month / 12);
    $time_year  = round($time_month / 12, 1);
    if ($time_hours < 24) {
        //$time = $time_min;
        $which_msg = 'modal_posted_today';
    } else if ($time_hours < 48) {
        //$time = $time_min;
        $which_msg = 'modal_posted_yesterday';
    } else if ($time_days < 7) {
        $time      = $time_days;
        $which_msg = 'modal_posted_days';
    } else if ($time_week == 1) {
        //$time = $time_week;
        $which_msg = 'modal_posted_week';
    } else if ($time_week < 6) {
        $time      = $time_week;
        $which_msg = 'modal_posted_weeks';
    } else if ($time_month == 1) {
        //$time = $time_week;
        $which_msg = 'modal_posted_month';
    } else if ($time_month <= 12) {
        $time      = $time_month;
        $which_msg = 'modal_posted_months';
    } else if ($time_yeari == 1) {
        //$time = $time_yeari;
        $which_msg = 'modal_posted_year';
    } else {
        $time      = $time_year;
        $which_msg = 'modal_posted_years';
    }
    return _('posted').' '.formatDateAsString($in_ts);
    //return LanguageStringReturn($which_msg, array('%time%' => $time) );
}
/**
 * formats a timestamp into the posted format
 * @param integer $in_ts thimestamp
 * @return string the formated posted string
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  05/05/2015
//<start>
//function formatUploadDate($in_ts) {
//
//    $time_min = intval((time() - $in_ts) / 60);
//    $time_hours = intval($time_min / 60);
//    $time_days = intval($time_hours / 24);
//    $time_week = intval($time_days / 7);
//    $time_month = intval($time_days / 30);
//    $time_yeari = intval($time_month / 12);
//    $time_year = round($time_month / 12, 1);
//    if ($time_hours < 24) {
//        //$time = $time_min;
//        $which_msg = 'modal_upload_today';
//    } else if ($time_hours < 48) {
//        //$time = $time_min;
//        $which_msg = 'modal_upload_yesterday';
//    } else if ($time_days < 7) {
//        $time = $time_days;
//        $which_msg = 'modal_upload_days';
//    } else if ($time_week == 1) {
//        //$time = $time_week;
//        $which_msg = 'modal_upload_week';
//    } else if ($time_week < 5) {
//        $time = $time_week;
//        $which_msg = 'modal_upload_weeks';
//    } else if ($time_month == 1) {
//        //$time = $time_week;
//        $which_msg = 'modal_upload_month';
//    } else if ($time_month <= 12) {
//        $time = $time_month;
//        $which_msg = 'modal_upload_months';
//    } else if ($time_yeari == 1) {
//        //$time = $time_yeari;
//        $which_msg = 'modal_upload_year';
//    } else {
//        $time = $time_year;
//        $which_msg = 'modal_upload_years';
//    }
//    return _('uploaded') . ' ' . formatDateAsString($in_ts);
//    //return LanguageStringReturn($which_msg, array('%time%' => $time) );
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  05/05/2015
//<end>

/**
 * formats a liked date string
 * @param integer $in_ts thimestamp
 * @return string the formated posted string
 */
function formatLikedDate($in_ts)
{

    $time_min   = intval((time() - $in_ts) / 60);
    $time_hours = intval($time_min / 60);
    $time_days  = intval($time_hours / 24);
    $time_week  = intval($time_days / 7);
    $time_month = intval($time_days / 30);
    $time_yeari = intval($time_month / 12);
    $time_year  = round($time_month / 12, 1);
    if ($time_hours < 24) {
        //$time = $time_min;
        $which_msg = 'modal_posted_today_likes';
    } else if ($time_hours < 48) {
        //$time = $time_min;
        $which_msg = 'modal_posted_yesterday_likes';
    } else if ($time_days < 7) {
        $time      = $time_days;
        $which_msg = 'modal_posted_days_likes';
    } else if ($time_week == 1) {
        //$time = $time_week;
        $which_msg = 'modal_posted_week_likes';
    } else if ($time_week < 5) {
        $time      = $time_week;
        $which_msg = 'modal_posted_weeks_likes';
    } else if ($time_month == 1) {
        //$time = $time_week;
        $which_msg = 'modal_posted_month_likes';
    } else if ($time_month <= 12) {
        $time      = $time_month;
        $which_msg = 'modal_posted_months_likes';
    } else if ($time_yeari == 1) {
        //$time = $time_yeari;
        $which_msg = 'modal_posted_year_likes';
    } else {
        $time      = $time_year;
        $which_msg = 'modal_posted_years_likes';
    }

    //return LanguageStringReturn($which_msg, array('%time%' => $time) );
    return _('posted').' '.formatDateAsString($in_ts);
}

/**
 * formats a liked date string
 * @param integer $in_ts thimestamp
 * @return string the formated posted string
 */
function formatDateAsString($in_ts)
{

    $time_min   = intval((time() - $in_ts) / 60);
    $time_hours = intval($time_min / 60);
    $time_days  = intval($time_hours / 24);
    $time_week  = round($time_days / 7);
    $time_month = round($time_days / 30);
    $time_yeari = intval($time_month / 12);
    $time_year  = round($time_month / 12, 1);

    $time  = '';
    $time2 = '';

    if ($time_min <= 1) {
        $which_msg = _('1 minute ago');
    } else if ($time_min < 60) {
        $time      = $time_min;
        $which_msg = sprintf(ngettext("%d minute ago", "%d minutes ago", $time),
            $time);
    } else if ($time_hours == 1) {
        $which_msg = sprintf(ngettext("%d hour ago", "%d hours ago", $time_hours),
            $time_hours);
    } else if ($time_hours < 24) {
        $time      = $time_hours;
        $which_msg = sprintf(ngettext("%d hour ago", "%d hours ago", $time),
            $time);
    } else if ($time_hours < 48) {
        $time3     = date('Y-m-d h:i A', $in_ts);
        $time2     = returnSocialTimeFormat($time3, 2);
        $which_msg = sprintf(_('yesterday at %s'), $time2);
    } else if ($time_days < 7) {
        $time      = $time_days;
        $time3     = date('Y-m-d h:i A', $in_ts);
        $time2     = returnSocialTimeFormat($time3, 2);
        //$time2 = date('h:i A', $in_ts); //hour without leading zero is 'g'
        $which_msg = sprintf(ngettext("%d day ago", "%d days ago", $time), $time);
    } else if ($time_week == 1) {
        $time      = $time_week;
        $which_msg = sprintf(ngettext("%d week ago", "%d weeks ago", $time),
            $time);
    } else if ($time_week < 5) {
        $time      = $time_week;
        $which_msg = sprintf(ngettext("%d week ago", "%d weeks ago", $time),
            $time);
    } else if ($time_month == 1) {
        $time      = $time_month;
        $which_msg = sprintf(ngettext("%d month ago", "%d months ago", $time),
            $time);
    } else if ($time_month <= 12) {
        $time      = $time_month;
        $which_msg = sprintf(ngettext("%d month ago", "%d months ago", $time),
            $time);
    } else if ($time_yeari == 1) {
        $time      = $time_yeari;
        $which_msg = sprintf(ngettext("%d year ago", "%d years ago", $time),
            $time);
    } else {
        $time      = $time_year;
        $which_msg = sprintf(ngettext("%d year ago", "%d years ago", $time),
            $time);
    }
    return $which_msg;
}
/*
  function formatDateAsString($in_ts){

  $time_min = intval( (time() - $in_ts)/60 );
  $time_hours = intval($time_min/60);
  $time_days = intval($time_hours/24);
  $time_week = intval($time_days/7);
  $time_month = intval($time_days/30);
  $time_yeari = intval($time_month/12);
  $time_year = round($time_month/12,1);

  $time = '';
  $time2 = '';

  if($time_min <=1){
  $which_msg = 'modal_posted_minute_final';
  }else if($time_min < 60){
  $time = $time_min;
  $which_msg = 'modal_posted_minutes_final';
  }else if($time_hours == 1){
  $which_msg = 'modal_posted_hour_final';
  }else if($time_hours < 24){
  $time = $time_hours;
  $which_msg = 'modal_posted_hours_final';
  }else if($time_hours < 48){
  $time = date('h:i A',$in_ts); //hour without leading zero is 'g'
  $which_msg = 'modal_posted_yesterday_final';
  }else if($time_days < 7){
  $time = $time_days;
  $time2 = date('h:i A',$in_ts); //hour without leading zero is 'g'
  $which_msg = 'modal_posted_days_final';
  }else if($time_week == 1){
  //$time = $time_week;
  $which_msg = 'modal_posted_week_final';
  }else if($time_week < 5){
  $time = $time_week;
  $which_msg = 'modal_posted_weeks_final';
  }else if($time_month == 1){
  //$time = $time_week;
  $which_msg = 'modal_posted_month_final';
  }else if($time_month <= 12){
  $time = $time_month;
  $which_msg = 'modal_posted_months_final';
  }else if($time_yeari == 1){
  //$time = $time_yeari;
  $which_msg = 'modal_posted_year_final';
  }else{
  $time = $time_year;
  $which_msg = 'modal_posted_years_final';
  }

  return LanguageStringReturn($which_msg, array('%time%' => $time,'%time2%' => $time2) );
  }
 */

/**
 * export php constants to javascript
 * @param mixed $_constants constant's names
 */
function export_constants($_constants)
{

    $constants = $_constants;
    if (!is_array($constants)) {
        $constants = array($constants);
    }

    foreach ($constants as $constant) {
        echo "var $constant = ".constant($constant).";\r\n";
    }
}

/**
 * return most visited cams
 * @param int limit the maximum number of cams
 * @return array
 */
function mostVisitedCams($limit = 0)
{


    global $dbConn;
    $params = array();
    $allArr = array();

    $query = "SELECT * FROM `cms_webcams` where state = 1 order by `nb_views` DESC";
    if ($limit != 0) {
        $query    .= " LIMIT 0 , :Limit";
        $params[] = array("key" => ":Limit",
            "value" => $limit,
            "type" => "::PARAM_INT");
    }
//    $ret = db_query($query);

    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select, $params);
    $res    = $select->execute();

    $ret = $select->rowCount();

//    if ($ret && db_num_rows($ret) > 0) {
    if ($res && $ret > 0) {
        $row = $select->fetchAll(PDO::FETCH_ASSOC);
//        while ($row = db_fetch_assoc($ret)) {
        foreach ($row as $row_item) {
            $allArr[] = $row_item;
        }
        return $allArr;
    } else {
        return false;
    }
}

/**
 * Display top Cloud
 * @param int keyNumber the maximum number of keys to display
 * Use function displayKeyCloud()
 * 
 * @return string the keys listed using classes in span (smallestCloud, smallCloud, mediumCloud, largeCloud, largestCloud)
 */
function keyCloudLive($keyNumber)
{


    global $dbConn;
    $params   = array();
    $index    = 0;
    $allArr   = array();
    $toRet    = '';
//    $query = "SELECT * FROM `cms_webcams` where state = 1 order by `nb_views` DESC LIMIT 0 , $keyNumber";
    $query    = "SELECT * FROM `cms_webcams` where state = 1 order by `nb_views` DESC LIMIT 0 , :Limit";
//    $ret = db_query($query);
    $params[] = array("key" => ":Limit", "value" => $keyNumber, "type" => "::PARAM_INT");

    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select, $params);
    $res    = $select->execute();

    $ret = $select->rowCount();
//    if ($ret && db_num_rows($ret) > 0) {
    if ($res && $ret > 0) {
//        while ($row = db_fetch_assoc($ret)) {
        $row = $select->fetchAll(PDO::FETCH_ASSOC);

        foreach ($row as $row_item) {
            if ($index == 0) {
                $allArr['searchMax'] = $row_item['nb_views'];
                $allArr['keyNumber'] = $keyNumber;
                $index++;
            }
            $allArr['keys'][] = array('val' => $row_item['nb_views'], 'text' => $row_item['name']);
        }
        shuffle($allArr['keys']);
//        $toRet = var_dump($allArr);
        $toRet = displayKeyCloud($allArr);
    }
    return $toRet;
}

/**
 * Display top Cloud
 * @param mixed $allArr 
 *      $allArr['searchMax'] : the maximum value of the search
 *      $allArr['keyNumber'] : the maximum number of keys to display
 *      $allArr['keys'] : all the keys
 *            $allArr['keys']['val'] : the number of search for specific key
 *            $allArr['keys']['text'] : the displayed value for a specific key
 * 
 * @return string the keys listed using classes in span (smallestCloud, smallCloud, mediumCloud, largeCloud, largestCloud)
 */
function displayKeyCloud($allArr)
{
    $toReturn = '';
    if (is_array($allArr) && !is_null($allArr)) {
        $searchMax = $allArr['searchMax'];
        $keyNumber = $allArr['keyNumber'];
        foreach ($allArr['keys'] as $key) {
            if ($keyNumber > 0) {
                $percent = floor(($key['val'] / $searchMax) * 100);
                if ($percent < 20) {
                    $class = 'smallestCloud';
                } elseif ($percent >= 20 and $percent < 40) {
                    $class = 'smallCloud';
                } elseif ($percent >= 40 and $percent < 60) {
                    $class = 'mediumCloud';
                } elseif ($percent >= 60 and $percent < 80) {
                    $class = 'largeCloud';
                } else {
                    $class = 'largestCloud';
                }
                $toReturn .= '<span class="aCloudItem '.$class.'">'.htmlEntityDecode($key['text']).' </span>'."\n";
            } else {
                break;
            }
            $keyNumber--;
        }
    }
    return $toReturn;
}
/**
 * Function used to get array from select Query
 * @param string $query : the query to run in mysql
 * return : array or false
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  05/05/2015
//<start>
//function getArr($query) {
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
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  05/05/2015
//<end>

/**
 * Function return country code or name from ip
 */
function getCountryFromIP($ip, $type = "code")
{
    switch ($type) {
        case 'code':
            global $CountryCode;
            return $CountryCode;
            break;
        default:
            global $CountryName;
            return $CountryName;
            break;
    }
}

/**
 * Function return the external link
 */
function returnExternalLink($link)
{
    if (preg_match("#https?://#", $link) === 0) {
        $link = 'http://'.$link;
    }
    return $link;
}

function get_cat_name($catid)
{


    global $dbConn;
    $params    = array();
    $lang_code = LanguageGet();
    if ($lang_code != 'en') {
        $languageSel  = ',ml.title as ml_title';
        $languageJoin = ' INNER JOIN ml_allcategories ml on c.id = ml.entity_id ';
    }
//    $q = "select c.title as title$languageSel from cms_allcategories c$languageJoin where c.id=" . $catid;
    $query    = "select c.title as title$languageSel from cms_allcategories c$languageJoin where c.id=:Catid";
//    $ret = db_query($q);
    $params[] = array("key" => ":Catid",
        "value" => $catid);
    $select   = $dbConn->prepare($query);
    PDO_BIND_PARAM($select, $params);
    $res      = $select->execute();

    $ret = $select->rowCount();

//    while ($row = db_fetch_array($ret)) {
    $row = $select->fetchAll();
    foreach ($row as $row_item) {
        if ($lang_code == 'en') {
            $res = htmlEntityDecode($row_item['title']);
        } else {
            $res = htmlEntityDecode($row_item['ml_title']);
        }
    }
    return $res;
}

function ShowFileExtension($filepath)
{
    preg_match('/[^?]*/', $filepath, $matches);
    $string = $matches[0];

    $pattern = preg_split('/\./', $string, -1, PREG_SPLIT_OFFSET_CAPTURE);

    if (count($pattern) > 1) {
        $filenamepart = $pattern[count($pattern) - 1][0];
        preg_match('/[^?]*/', $filenamepart, $matches);
        return strtolower($matches[0]);
    }
}

function checkTimeZoneCookie()
{
    if (!isset($_COOKIE['timezone'])) {
        $expiret    = time() + 365 * 24 * 3600;
        $expire     = gmdate("r", $expiret);
        $pathcookie = '/';
        return '<script type="text/javascript">
            if (navigator.cookieEnabled) document.cookie = "timezone="+ (- new Date().getTimezoneOffset()) +";expires=('.$expire.');path=/";
            if (navigator.cookieEnabled) document.location.reload();
        </script>';
    } else {
        return updateCheckTimeZoneCookie();
    }
}

function updateCheckTimeZoneCookie()
{
    $expiret    = time() + 365 * 24 * 3600;
    $expire     = gmdate("r", $expiret);
    $pathcookie = '/';
    return '<script type="text/javascript">
         if (navigator.cookieEnabled) document.cookie = "timezone="+ (- new Date().getTimezoneOffset()) +";expires=('.$expire.');path=/";
    </script>';
}

function returnSocialTimeFormat($time_date, $_case = 1)
{
    global $request;
    global $mobile_timezone;
    if (isset($mobile_timezone)) {
        $timezone_cookie = $mobile_timezone;
    } else {
        $timezone_cookie = $request->cookies->get('timezone', '');
    }
    switch ($_case) {
        case 1://Feb dd, YYYY at 7:50 PM OR AM
//           if (!isset($_COOKIE['timezone'])) {
            if (!isset($timezone_cookie)) {
                $timeDate = strftime("%b %d, %Y", strtotime($time_date))._(' at ').strftime('%I:%M',
                        strtotime($time_date));
            } else {
                $ts       = new DateTime($time_date, new DateTimeZone('GMT'));
                $ts->add(DateInterval::createFromDateString($timezone_cookie.' minutes'));
                $timeDate = strftime("%b %d, %Y", $ts->getTimestamp())._(' at ').strftime('%X',
                        $ts->getTimestamp()); //%I:%M %p
//                $timeDate = $ts->format("M d, Y") ._(' at '). $ts->format("h:i A");
            }
            break;
        case 2://7:50 PM OR AM
//           if (!isset($_COOKIE['timezone'])) {
            if (!isset($timezone_cookie)) {
                $timeDate = date('h:i A', strtotime($time_date));
            } else {
                $ts       = new DateTime($time_date, new DateTimeZone('GMT'));
                $ts->add(DateInterval::createFromDateString($timezone_cookie.' minutes'));
                $timeDate = strftime('%X', $ts->getTimestamp());
//                $timeDate = $ts->format("h:i A");
            }
            break;
        case 3://Feb dd, YYYY
//           if (!isset($_COOKIE['timezone'])) {
            if (!isset($timezone_cookie)) {
                $timeDate = strftime('%b %d, %Y', strtotime($time_date));
            } else {
                $ts       = new DateTime($time_date, new DateTimeZone('GMT'));
                $ts->add(DateInterval::createFromDateString($timezone_cookie.' minutes'));
                $timeDate = strftime('%b %d, %Y', $ts->getTimestamp());
//                $timeDate = $ts->format("M d, Y") ;
            }
            break;
        case 4://YYYY-mm-dd
//           if (!isset($_COOKIE['timezone'])) {
            if (!isset($timezone_cookie)) {
                $timeDate = strftime('Y-m-d', strtotime($time_date));
            } else {
                $ts       = new DateTime($time_date, new DateTimeZone('GMT'));
                $ts->add(DateInterval::createFromDateString($timezone_cookie.' minutes'));
                $timeDate = strftime('Y-m-d', $ts->getTimestamp());
//                $timeDate = $ts->format("Y-m-d") ;
            }
            break;
        case 5://dd-mm-YYYY
//           if (!isset($_COOKIE['timezone'])) {
            if (!isset($timezone_cookie)) {
                $timeDate = strftime('d-m-Y', strtotime($time_date));
            } else {
                $ts       = new DateTime($time_date, new DateTimeZone('GMT'));
                $ts->add(DateInterval::createFromDateString($timezone_cookie.' minutes'));
                $timeDate = strftime('d-m-Y', $ts->getTimestamp());
//                $timeDate = $ts->format("d-m-Y") ;
            }
            break;
        case 6://March dd, YYYY
//           if (!isset($_COOKIE['timezone'])) {
            if (!isset($timezone_cookie)) {
                $timeDate = strftime('%B %d, %Y', strtotime($time_date));
            } else {
                $ts       = new DateTime($time_date, new DateTimeZone('GMT'));
                $ts->add(DateInterval::createFromDateString($timezone_cookie.' minutes'));
                $timeDate = strftime('%B %d, %Y', $ts->getTimestamp());
//                $timeDate = $ts->format("F d, Y") ;
            }
            break;
        default://Feb dd, YYYY at 7:50 PM OR AM
//            if (!isset($_COOKIE['timezone'])) {
            if (!isset($timezone_cookie)) {
                $timeDate = strftime("%b %d, %Y", strtotime($time_date))._(' at ').strftime('%I:%M',
                        strtotime($time_date));
            } else {
                $ts       = new DateTime($time_date, new DateTimeZone('GMT'));
                $ts->add(DateInterval::createFromDateString($timezone_cookie.' minutes'));
                $timeDate = strftime("%b %d, %Y", $ts->getTimestamp())._(' at ').strftime('%X',
                        $ts->getTimestamp());
//                $timeDate = $ts->format("M d, Y") ._(' at '). $ts->format("h:i A");
            }
            break;
    }
    return $timeDate;
}

function timeFormat($inputType)
{
    $inputDate = returnSocialTimeFormat($inputType, 1);
    return $inputDate;
}

function validate_alphanumeric_underscore($str)
{
    return preg_match('/^[a-zA-Z0-9.\-@_]+$/', $str);
}

function _TL($str)
{
    if ($str != '') $str = _($str);
    return $str;
}
/* function to log memory used by a php script
 * added by rishav chhajer
 * date 11th june 2015
 */

function logMemoryUsage()
{
    $memUsage = memory_get_usage(false) / (1024 * 1024);
    $date     = date("Y-m-d H:i:s");

    if (!(strstr($_SERVER[REQUEST_URI], '.mp3') || strstr($_SERVER[REQUEST_URI],
            '.ogg'))) {
        $message = $date." : "."http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"." : ".$memUsage."\n";
    }
    if ($memUsage > 5) {
        file_put_contents("memuse_log.txt", $message, FILE_APPEND);
    }
}
/* function to add a push notification
 * added by elie
 * date 27th june 2015
 * @params $message_type 
 * @params $target_user_id 
 * @params $action_user_id 
 * @params $user_is_channel
 * @params $entity_id
 * @params $entity_type
 */

function addPushNotification($action_type, $target_user_id, $action_user_id,
                             $user_is_channel = 0, $entity_id = 0,
                             $entity_type = 0, $message = '')
{
    if ($user_is_channel) {
        $action_user      = channelGetInfo($action_user_id);
        $action_user_name = $action_user['channel_name'];
    } else {
        $action_user      = getUserInfo($action_user_id);
        $action_user_name = returnUserDisplayName($action_user);
    }
    $msg = $message;
    switch ($action_type) {
        case SOCIAL_ACTION_FOLLOW:
            $msg = "$action_user_name is now following you";
            break;
        case SOCIAL_ACTION_FRIEND:
            $msg = "$action_user_name accepted your friend request";
            break;
        case SOCIAL_ACTION_FRIEND_REQUEST:
            $msg = "$action_user_name sent you a friend request";
            break;
        case SOCIAL_ACTION_INVITE:
            switch ($entity_type) {
                case SOCIAL_ENTITY_EVENTS: SOCIAL_ENTITY_USER_EVENTS:
                    if ($entity_type == SOCIAL_ENTITY_EVENTS) {
                        $event_info = channelEventInfo($entity_id);
                        $event_name = $event_info['name'];
                        $msg        = "$action_user_name invited you to join the event $event_name";
                    } else {
                        $event_info = userEventInfo($entity_id);
                        $event_name = $event_info['name'];
                        $msg        = "$action_user_name invited you to join the event $event_name";
                    }
//                    $event_name = $event_info['name'];    
//                    $msg = "$action_user_name invited you to join the event $event_name";
                    break;
                case SOCIAL_ENTITY_CHANNEL:
                    $channel_info = channelGetInfo($entity_id);
                    $channel_name = $channel_info['channel_name'];
                    $msg          = "$action_user_name invited you to connect to the channel $channel_name";
                    break;
            }
            break;
        case SOCIAL_ACTION_EVENT_CANCEL:
            if ($entity_type == SOCIAL_ENTITY_EVENTS) {
                $event_info = channelEventInfo($entity_id);
            } else {
                $event_info = userEventInfo($entity_id);
            }
            $event_name   = $event_info['name'];
            $msg          = "$action_user_name cancelled the event $event_name";
            break;
        case SOCIAL_ACTION_CONNECT:
            $channel_info = channelGetInfo($entity_id);
            $channel_name = $channel_info['channel_name'];
            $msg          = "$action_user_name is now connected to the channel $channel_name";
            break;
        case SOCIAL_ACTION_COMMENT:
            switch ($entity_type) {
                case SOCIAL_ENTITY_USER_PROFILE:
                    $msg         = "$action_user_name commented on your profile image";
                    break;
                case SOCIAL_ENTITY_MEDIA:
                    $entity_info = getVideoInfo($entity_id);
                    $video_photo = ( strstr($entity_info['type'], 'image') == null )
                            ? 'video' : 'photo';
                    $title       = $entity_info['title'];
                    $msg         = "$action_user_name commented on your $video_photo $title";
                    break;
                case SOCIAL_ENTITY_ALBUM:
                    $album_info  = userCatalogGet($entity_id);
                    $album_name  = $album_info['catalog_name'];
                    $msg         = "$action_user_name commented on your album $album_name";
                    break;
                case SOCIAL_ENTITY_USER_EVENTS:
                    $event_info  = userEventInfo($entity_id);
                    $event_name  = $event_info['name'];
                    $msg         = "$action_user_name commented on the event $event_name";
                    break;
            }
            break;
        case SOCIAL_ACTION_LIKE:
            switch ($entity_type) {
                case SOCIAL_ENTITY_USER_PROFILE:
                    $msg         = "$action_user_name liked your profile image";
                    break;
                case SOCIAL_ENTITY_MEDIA:
                    $entity_info = getVideoInfo($entity_id);
                    $video_photo = ( strstr($entity_info['type'], 'image') == null )
                            ? 'video' : 'photo';
                    $title       = $entity_info['title'];
                    $msg         = "$action_user_name liked your $video_photo $title";
                    break;
                case SOCIAL_ENTITY_ALBUM:
                    $album_info  = userCatalogGet($entity_id);
                    $album_name  = $album_info['catalog_name'];
                    $msg         = "$action_user_name liked your album $album_name";
                    break;
                case SOCIAL_ENTITY_USER_EVENTS:
                    $event_info  = userEventInfo($entity_id);
                    $event_name  = $event_info['name'];
                    $msg         = "$action_user_name liked the event $event_name";
                    break;
            }
            break;
        case SOCIAL_ACTION_SHARE:
            switch ($entity_type) {
                case SOCIAL_ENTITY_MEDIA:
                    $entity_info   = getVideoInfo($entity_id);
                    $video_photo   = ( strstr($entity_info['type'], 'image') == null )
                            ? 'video' : 'photo';
                    $title         = $entity_info['title'];
                    $msg           = "$action_user_name shared your $video_photo $title";
                    break;
                case SOCIAL_ENTITY_ALBUM:
                    $album_info    = userCatalogGet($entity_id);
                    $album_name    = $album_info['catalog_name'];
                    $msg           = "$action_user_name shared your album $album_name";
                    break;
                case SOCIAL_ENTITY_USER_EVENTS:
                    $event_info    = userEventInfo($entity_id);
                    $event_name    = $event_info['name'];
                    $msg           = "$action_user_name shared the event $event_name";
                    break;
                case SOCIAL_ENTITY_VISITED_PLACES:
                    $city_info     = socialEntityInfo(SOCIAL_ENTITY_VISITED_PLACES,
                        $entity_id);
                    $stateinfo     = worldStateInfo($city_info['country_code'],
                        $city_info['state_code']);
                    $state_name    = (!$stateinfo) ? '' : $stateinfo['state_name'];
                    $country_name  = countryGetName($city_info['country_code']);
                    $location_name = $city_info['name'];
                    if ($state_name != '') {
                        $location_name .= ", $state_name";
                    }
                    if ($country_name != '') {
                        $location_name .= ", $country_name";
                    }
                    $msg = "$action_user_name shared your visited location $location_name";
                    break;
            }
            break;
    }

    global $dbConn;
    $params   = array();
    $query    = "INSERT INTO cms_push_notification (msg,user_id,action_user_id,action_type, entity_id, entity_type) VALUES (:Msg, :User_id,:Action_user_id,:Action_type,:Entity_id,:Entity_type)";
    $params[] = array("key" => ":Msg", "value" => $msg);
    $params[] = array("key" => ":User_id", "value" => $target_user_id);
    $params[] = array("key" => ":Action_user_id", "value" => $action_user_id);
    $params[] = array("key" => ":Action_type", "value" => $action_type);
    $params[] = array("key" => ":Entity_id", "value" => $entity_id);
    $params[] = array("key" => ":Entity_type", "value" => $entity_type);
    $insert   = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert, $params);
    $res      = $insert->execute();
    $ret      = $insert->rowCount();
}

/**
 * gets the alias info given the id
 * @param $id the alias id
 * @return array|false the alias record or false if none found
 */
function aliasContentInfo($id)
{
    global $dbConn;
    $params   = array();
    $query    = "SELECT * FROM `alias` WHERE id=:Id";
    $params[] = array("key" => ":Id", "value" => $id);
    $select   = $dbConn->prepare($query);
    PDO_BIND_PARAM($select, $params);
    $res      = $select->execute();
    $ret      = $select->rowCount();
    if ($res && $ret == 1) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        return $row;
    } else {
        return false;
    }
}
function checkChannelSubdomain(){
    global $request;
    $SERVER_HOST_server = $request->server->get('HTTP_HOST', '');
    $sub_domain1 = explode('.', $SERVER_HOST_server)[0];
    $sub_domain = explode('-', $sub_domain1)[0];
    
    if($sub_domain !='channels'){
        $REQUEST_URI_server = $request->server->get('REQUEST_URI', '');
        $Redirect_URL = generateLangURL($REQUEST_URI_server,'channels',true);
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: $Redirect_URL");
        exit();
    }
}
function generateLangURL($path, $page_type = '', $skip_lang = false)
{
    global $CONFIG;
    $lang = LanguageGet();
    $subdomain_suffix = $CONFIG['subdomain_suffix'];
    $langroute = '';
    if ($lang != 'en' && !$skip_lang ) $langroute = '/'.$lang;
    if (substr($path, 0, 1) != '/') $path = '/'.$path;
	
	if (!$page_type)
	{
		if ($path == '/channels')
			$page_type = 'channels';
		else
		{
			$channel_prefixes = array('/channel-', '/channels-', '/channel/', '/channels/', '/CreateChannelForm', '/create-channel', '/recent-channels', '/embed-channel', '/TT-confirmation/channel');
			
			usort($channel_prefixes, function ($a, $b) {
					if (strlen($a) == strlen($b))
						return 0;
					
					return (strlen($a) > strlen($b)?1:-1);
			});
			
			foreach ($channel_prefixes as $possible_prefix)
			{
				if (substr($path, 0, strlen($possible_prefix)) == $possible_prefix)
				{
					$page_type = 'channels';
					
					break;
				}
			}
		}
	}

    $currentServerURL = UriCurrentServerURL();
	
	$subdomain_root = 'www';
	
	if (in_array($page_type, array('media', 'restaurants', 'corporate', 'channels', 'deals', 'where-is', 'nearby')))
		$subdomain_root = $page_type;
	
	$langroute = $currentServerURL[0].$subdomain_root.$subdomain_suffix.'.'.$currentServerURL[1].$langroute;
	
    return $langroute.$path;
}
