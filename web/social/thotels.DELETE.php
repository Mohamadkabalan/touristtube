<?php
//call the functions and script
$path = "";
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/functions/bag.php" );
include_once ( $path . "inc/twigFct.php" );

//$link = "/ttback/uploads/";
$link = ReturnLink('media/discover').'/';
//twig connection Part
$theLink = $CONFIG ['server']['root'];
//require_once 'vendor/autoload.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($theLink.'twig_templates/');
$twig = new Twig_Environment($loader, array(
    'cache' => $theLink.'twig_cache/','debug' => false,
));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('thotels.twig');

$user_id = userGetID();
$includes = array('css/thotels.css',"js/jscal2.js","js/jscal2.en.js",'js/thotels.js');
$includes[] = 'css/jscal2.css';
$includes[] = 'js/language_bar.js';//the js of the language bar
$includes[] = 'css/language_bar.css';//the css of the language bar
$includesIE8 = array('css/thotelsIE8.css',);
tt_global_set('includes', $includes);
tt_global_set('includesIE8', $includesIE8);
//----
require_once $theLink.'twig_parts/_head.php';
require_once $theLink.'twig_parts/_language_bar.php';//remember to include the css and js for the language bar

$symbol = '$ ';
$rate = '1';
if(isset($_SESSION['currencyRate'])){
    $rate = $_SESSION['currencyRate'];
}
if(isset($_SESSION['currencySymbol'])){
    $symbol = $_SESSION['currencySymbol'];
}
function getSuggestedHotels($limit){
	global $dbConn;
	$params = array();  
//    $query_hotels = "SELECT dh.id, dh.hotelName, dh.cityName, IFNULL(dhi.filename, '') AS filename, count(dhr.id) AS nbr_reviews 
//                     FROM discover_hotels as dh 
//                     INNER JOIN discover_hotels_images as dhi on dh.id = dhi.hotel_id 
//                     LEFT JOIN discover_hotels_reviews as dhr on dh.id = dhr.hotel_id AND dhr.published=1
//                     GROUP BY dh.id
//                     ORDER BY nbr_reviews DESC, dh.hotelName 
//                     LIMIT $limit";
    $query_hotels = "SELECT dh.id, dh.hotelName, dh.cityName, IFNULL(dhi.filename, '') AS filename, count(dhr.id) AS nbr_reviews 
                     FROM discover_hotels as dh 
                     INNER JOIN discover_hotels_images as dhi on dh.id = dhi.hotel_id 
                     LEFT JOIN discover_hotels_reviews as dhr on dh.id = dhr.hotel_id AND dhr.published=1
                     GROUP BY dh.id
                     ORDER BY nbr_reviews DESC, dh.hotelName 
                     LIMIT $limit";
    $params[] = array(  "key" => ":Limit",
                        "value" =>$limit,
                        "type" =>"::PARAM_INT");
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
//    $ret = db_query($query_hotels);
    $hotels_arr = array();
    $hotels_arr = $select->fetchAll();
//    while($row = db_fetch_array($ret)){
//        $hotels_arr[] = $row_item;
//    }
    return $hotels_arr;
}

function getHotelsByCity($cityName, $limit){
//    $query_hotels = "SELECT dh.id, dh.hotelName, dh.cityName, IFNULL(dhi.filename, '') AS filename, count(dhr.id) AS nbr_reviews 
//                     FROM discover_hotels as dh 
//                     INNER JOIN discover_hotels_images as dhi on dh.id = dhi.hotel_id 
//                     LEFT JOIN discover_hotels_reviews as dhr on dh.id = dhr.hotel_id AND dhr.published=1
//                     WHERE LOWER(dh.cityName) = LOWER('$cityName')
//                     GROUP BY dh.id
//                     ORDER BY nbr_reviews DESC, dh.hotelName 
//                     LIMIT $limit";
    $query_hotels = "SELECT dh.id, dh.hotelName, dh.cityName, IFNULL(dhi.filename, '') AS filename, count(dhr.id) AS nbr_reviews 
                     FROM discover_hotels as dh 
                     INNER JOIN discover_hotels_images as dhi on dh.id = dhi.hotel_id 
                     LEFT JOIN discover_hotels_reviews as dhr on dh.id = dhr.hotel_id AND dhr.published=1
                     WHERE LOWER(dh.cityName) = LOWER(:CityName)
                     GROUP BY dh.id
                     ORDER BY nbr_reviews 
//                   LIMIT :Limit";
    $params[] = array(  "key" => ":CityName",
                        "value" =>$cityName);
    $params[] = array(  "key" => ":Limit",
                        "value" =>$limit,
                        "type" =>"::PARAM_INT");
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
//    $ret = db_query($query_hotels);
    $hotels_arr = array();
    $hotels_arr = $select->fetchAll();
//    while($row = db_fetch_array($ret)){
//        $hotels_arr[] = $row_item;
//    }
    return $hotels_arr;
}

$cities = array();
$suggestedHotels = array();
$evalHotels = array();
$city_names = array('Paris', 'Beirut', 'Rome', 'London', 'Abu Dabi', 'San Francisco', 'Cannes', 'Dubai', 'Monaco', 'Denise', 'Barcelona', 'Hamburg', 'Berlin');
foreach($city_names as $city_name){
    $hotels = getHotelsByCity($city_name, 4);
    if(count($hotels) == 0)
        continue;
    $hotels_res = array();
    foreach($hotels as $hotel){
        $item = array();
        $item['id'] = $hotel['id'];
        $item['hotelName'] = $hotel['hotelName'];
        $item['filename'] = $link.'thumb/'.$hotel['filename'];
        $item['nbr_reviews'] = $hotel['nbr_reviews'];
        $item['link'] = ReturnLink('thotel/id/'.$hotel['id']);
        $hotels_res[] = $item;
    }
    $city = array();
    $city['name'] = $city_name;
    $city['hotels'] = $hotels_res;
    $city['link'] = ReturnLink('hotel-search/' . strtolower($city_name) );
    $cities[] = $city;
}

$sugg_hotels = getSuggestedHotels(6);
foreach($sugg_hotels as $sugg_hotel){
    $item = array();
    $item['id'] = $sugg_hotel['id'];
    $hotelName = $sugg_hotel['hotelName'];
    if(strlen($hotelName) > 22){
        $hotelName = substr($hotelName, 0, 19).'...';
    }
    $item['hotelName'] = $hotelName;
    $item['filename'] = $link.'thumb/'.$sugg_hotel['filename'];
    $item['nbr_reviews'] = $sugg_hotel['nbr_reviews'];
    $item['cityName'] = $sugg_hotel['cityName'];
    $item['link'] = ReturnLink('thotel/id/'.$sugg_hotel['id']);
    $suggestedHotels[] = $item;
}

$eval_hotels = getSuggestedHotels(18);
foreach($eval_hotels as $eval_hotel){
    $item = array();
    $item['id'] = $eval_hotel['id'];
    $item['hotelName'] = $eval_hotel['hotelName'];
    $item['filename'] = $link.'thumb/'.$eval_hotel['filename'];
    $item['nbr_reviews'] = $eval_hotel['nbr_reviews'];
    $item['cityName'] = $eval_hotel['cityName'];
    $item['link'] = ReturnLink('thotel/id/'.$eval_hotel['id']);
    $evalHotels[] = $item;
}
$rate = (isset($_SESSION['currencyRate']))?$_SESSION['currencyRate']:1;
$data["cities"] = $cities;
$data["suggestedHotels"] = $suggestedHotels;
$data["evalHotels"] = $evalHotels;
$data["starSrc"] = ReturnLink('images/hotels/star.png', null);
$data["starWhiteSrc"] = ReturnLink('images/hotels/starWhite.png', null);
$data['lowRange'] = '(0-'.ceil(200 /$rate).')';
$data['midRange'] = '('.ceil(200 /$rate).'-'.ceil(500 /$rate).')';
$data['highRange'] = '('.ceil(500 /$rate).'-'.ceil(1000 /$rate).')';

/*$dump = print_r($data['cities'], true);
$data['dump'] = $dump;*/
include($theLink . "twig_parts/_foot.php");
echo $template->render($data);