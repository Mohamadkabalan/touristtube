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
$link = ReturnLink('media/discover') . '/';
//twig connection Part
$theLink = $CONFIG ['server']['root'];
//require_once 'vendor/autoload.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($theLink . 'twig_templates/');
$twig = new Twig_Environment($loader, array(
    'cache' => $theLink . 'twig_cache/', 'debug' => false,
        ));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('trestaurants.twig');

$user_id = userGetID();
$includes = array('css/thotels.css', "js/jscal2.js", "js/jscal2.en.js", 'js/trestaurants.js');
$includes[] = 'css/jscal2.css';
$includes[] = 'js/language_bar.js'; //the js of the language bar
$includes[] = 'css/language_bar.css'; //the css of the language bar
$includesIE8 = array('css/trestaurantsIE8.css',);
tt_global_set('includes', $includes);
tt_global_set('includesIE8', $includesIE8);
//----
require_once $theLink . 'twig_parts/_head.php';
require_once $theLink . 'twig_parts/_language_bar.php'; //remember to include the css and js for the language bar

$symbol = '$ ';
$rate = '1';
if (isset($_SESSION['currencyRate'])) {
    $rate = $_SESSION['currencyRate'];
}
if (isset($_SESSION['currencySymbol'])) {
    $symbol = $_SESSION['currencySymbol'];
}

function getSuggestedRestaurants($limit) {
	global $dbConn;
	$params = array();  
//    $query_Restaurants = "SELECT dh.id, dh.name, dh.city, IFNULL(dhi.filename, '') AS filename, count(dhr.id) AS nbr_reviews 
//                     FROM discover_restaurants as dh 
//                     INNER JOIN discover_restaurants_images as dhi on dh.id = dhi.restaurant_id 
//                     LEFT JOIN discover_restaurants_reviews as dhr on dh.id = dhr.restaurant_id
//                     GROUP BY dh.id
//                     ORDER BY nbr_reviews DESC, dh.name 
//                     LIMIT $limit";
    $query_Restaurants = "SELECT dh.id, dh.name, dh.city, IFNULL(dhi.filename, '') AS filename, count(dhr.id) AS nbr_reviews 
                     FROM discover_restaurants as dh 
                     INNER JOIN discover_restaurants_images as dhi on dh.id = dhi.restaurant_id 
                     LEFT JOIN discover_restaurants_reviews as dhr on dh.id = dhr.restaurant_id
                     GROUP BY dh.id
                     ORDER BY nbr_reviews DESC, dh.name 
                     LIMIT :Limit";
    $params[] = array(  "key" => ":Limit",
                        "value" =>$limit,
                        "type" =>"::PARAM_INT");
    $select = $dbConn->prepare($query_Restaurants);
    PDO_BIND_PARAM($select,$params);
    $restaurants_arr    = $select->execute();
//    $ret = db_query($query_Restaurants);
    $restaurants_arr = array();
    $row = $select->fetchAll();
//    while ($row = db_fetch_array($ret)) {
    foreach($row as $row_item){
        $restaurants_arr[] = $row_item;
    }
    return $restaurants_arr;
}

function getRestaurantsByCity($cityName, $limit) {
	global $dbConn;
	$params = array(); 
//    $query_Restaurants = "SELECT dh.id, dh.name, dh.city, IFNULL(dhi.filename, '') AS filename, count(dhr.id) AS nbr_reviews 
//                     FROM discover_restaurants as dh 
//                     INNER JOIN discover_restaurants_images as dhi on dh.id = dhi.restaurant_id 
//                     LEFT JOIN discover_restaurants_reviews as dhr on dh.id = dhr.restaurant_id
//                     WHERE LOWER(dh.city) = LOWER('$cityName')
//                     GROUP BY dh.id
//                     ORDER BY nbr_reviews DESC, dh.name 
//                     LIMIT $limit";
    $query_Restaurants = "SELECT dh.id, dh.name, dh.city, IFNULL(dhi.filename, '') AS filename, count(dhr.id) AS nbr_reviews 
                     FROM discover_restaurants as dh 
                     INNER JOIN discover_restaurants_images as dhi on dh.id = dhi.restaurant_id 
                     LEFT JOIN discover_restaurants_reviews as dhr on dh.id = dhr.restaurant_id
                     WHERE LOWER(dh.city) = LOWER(:CityName)
                     GROUP BY dh.id
                     ORDER BY nbr_reviews DESC, dh.name 
                     LIMIT :Limit";
    $params[] = array(  "key" => ":CityName",
                        "value" =>$cityName);
    $params[] = array(  "key" => ":Limit",
                        "value" =>$limit,
                        "type" =>"::PARAM_INT");
    $select = $dbConn->prepare($query_Restaurants);
    PDO_BIND_PARAM($select,$params);
    $ret    = $select->execute();
//    $ret = db_query($query_Restaurants);
    $restaurants_arr = array();
//    while ($row = db_fetch_array($ret)) {
    foreach($row as $row_item){
        $restaurants_arr[] = $row_item;
    }
    return $restaurants_arr;
}

/* ----------- Food Type Array ----------- */
//$qFoodType = "SELECT id, title FROM discover_cuisine order by title";
//$retFoodType = db_query($qFoodType);
//$cuisines_arr = array();
//while($rowFoodType = db_fetch_array($retFoodType)){
//    $cuisines_arr[] = $rowFoodType;
//}
$cuisines_arr = getCuisine();
/* ----------- Food Type Array ----------- */

$cities = array();
$suggestedRestaurants = array();
$evalRestaurants = array();
$city_names = array('Paris', 'Beirut', 'Rome', 'London', 'Abu Dabi', 'San Francisco', 'Cannes', 'Dubai', 'Monaco', 'Denise', 'Barcelona', 'Hamburg', 'Berlin');
foreach ($city_names as $city_name) {
    $restaurants = getRestaurantsByCity($city_name, 4);
    if (count($restaurants) == 0)
        continue;
    $restaurants_res = array();
    foreach ($restaurants as $restaurant) {
        $item = array();
        $item['id'] = $restaurant['id'];
        $item['hotelName'] = htmlEntityDecode($restaurant['name']);
        $item['filename'] = $link . 'thumb/' . $restaurant['filename'];
        $item['nbr_reviews'] = $restaurant['nbr_reviews'];
        $item['link'] = ReturnLink('trestaurant/id/' . $restaurant['id']);
        $restaurants_res[] = $item;
    }
    $city = array();
    $city['name'] = $city_name;
    $city['hotels'] = $restaurants_res;
    $city['link'] = ReturnLink('restaurant-search/' . strtolower($city_name));
    $cities[] = $city;
}

$sugg_restaurants = getSuggestedRestaurants(6);
foreach ($sugg_restaurants as $sugg_hotel) {
    $item = array();
    $item['id'] = $sugg_hotel['id'];
    $restaurantName = htmlEntityDecode($sugg_hotel['name']);
    if (strlen($restaurantName) > 22) {
        $restaurantName = substr($restaurantName, 0, 19) . '...';
    }
    $item['hotelName'] = $restaurantName;
    $item['filename'] = $link . 'thumb/' . $sugg_hotel['filename'];
    $item['nbr_reviews'] = $sugg_hotel['nbr_reviews'];
    $item['cityName'] = $sugg_hotel['city'];
    $item['link'] = ReturnLink('trestaurant/id/' . $sugg_hotel['id']);
    $suggestedRestaurants[] = $item;
}

$eval_restaurants = getSuggestedRestaurants(18);
foreach ($eval_restaurants as $eval_restaurant) {
    $item = array();
    $item['id'] = $eval_restaurant['id'];
    $item['hotelName'] = htmlEntityDecode($eval_restaurant['name']);
    $item['filename'] = $link . 'thumb/' . $eval_restaurant['filename'];
    $item['nbr_reviews'] = $eval_restaurant['nbr_reviews'];
    $item['cityName'] = $eval_restaurant['city'];
    $item['link'] = ReturnLink('trestaurant/id/' . $eval_restaurant['id']);
    $evalRestaurants[] = $item;
}
$rate = (isset($_SESSION['currencyRate'])) ? $_SESSION['currencyRate'] : 1;
$data["cities"] = $cities;
$data["suggestedHotels"] = $suggestedRestaurants;
$data["evalHotels"] = $evalRestaurants;
$data["starSrc"] = ReturnLink('images/hotels/star.png', null);
$data["starWhiteSrc"] = ReturnLink('images/hotels/starWhite.png', null);
$data['lowRange'] = '(0-' . ceil(200 / $rate) . ')';
$data['midRange'] = '(' . ceil(200 / $rate) . '-' . ceil(500 / $rate) . ')';
$data['highRange'] = '(' . ceil(500 / $rate) . '-' . ceil(1000 / $rate) . ')';
$data['foodType'] = $cuisines_arr;
/* $dump = print_r($data['cities'], true);
  $data['dump'] = $dump; */
include($theLink . "twig_parts/_foot.php");
echo $template->render($data);
