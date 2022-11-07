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


//twig connection Part
$theLink = $CONFIG ['server']['root'];
//require_once 'vendor/autoload.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($theLink.'twig_templates/');
$twig = new Twig_Environment($loader, array(
    'cache' => $theLink.'twig_cache/','debug' => false,
));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('restaurant-search.twig');

$user_id = userGetID();
$includes = array($path .'css/hotel-search.css',"js/jscal2.js","js/jscal2.en.js",$path .'js/restaurant-search.js');
$includes[] = 'css/jscal2.css';
$includes[] = 'js/language_bar.js';//the js of the language bar
$includes[] = 'css/language_bar.css';//the css of the language bar
tt_global_set('includes', $includes);
//----

$symbol = '$ ';
$rate = '1';
if(isset($_SESSION['currencyRate'])){
    $rate = $_SESSION['currencyRate'];
}
if(isset($_SESSION['currencySymbol'])){
    $symbol = $_SESSION['currencySymbol'];
}

$fr_txt = "";
$time = "";
$country_code ="";
$state_code ="";
$from_date = "dd / mm / yyyy";
$time = "hh / mm";
$string_search = xss_sanitize(UriGetArg(0));
$string_search_value = isset($string_search) ? $string_search : '';
$count_day = $tpopular;
if($string_search_value!=''){
    $string_search_arr = explode('_',$string_search_value);
    $string_search_value = $string_search_arr[0];
}else{
    $string_search_value = _('Where to go?');
}
$country_code = xss_sanitize(UriGetArg('CO'));
$state_code = xss_sanitize(UriGetArg('ST'));
$city_code = xss_sanitize(UriGetArg('C'));
$fr_txt = xss_sanitize(UriGetArg('d'));
$fr_txt = isset($fr_txt) ? $fr_txt : '';
$time = xss_sanitize(UriGetArg('t'));
$time = isset($time) ? $time : '';

if($fr_txt!=''){
    $from_date = date( 'd/m/Y', strtotime($fr_txt) );
}
$foodtype = xss_sanitize(UriGetArg('foodtype'));
$budgetVal = xss_sanitize(UriGetArg('budget'));
$personsNb = xss_sanitize(UriGetArg('persons'));

$data['personsNb'] = $personsNb;
$data['foodtype'] = $foodtype;
$data['priceRange'] = $budgetVal;
$data['destVal'] = $string_search_value;
$data['country_code'] = $country_code;
$data['city_code'] = $city_code;
$data['state_code'] = $state_code;
$data['fromVal'] = $fr_txt;
$data['fromDisp'] = $from_date;

$data['timeVal'] = $time;
//return images for a specific hotel
function getHotelMedia($txt_id){
    global $dbConn;
    $params = array();  
//    $query_hotels = "SELECT id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = $txt_id ORDER BY default_pic DESC LIMIT 29";
    $query_hotels = "SELECT id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = :Txt_id ORDER BY default_pic DESC LIMIT 29";
    $params[] = array(  "key" => ":Txt_id",
                        "value" =>$txt_id);
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params);
    $ret_hotels    = $select->execute();
    $media_hotels = array();
//    $ret_hotels = db_query($query_hotels);
    $media_hotels = $select->fetchAll();
//    while($row = db_fetch_array($ret_hotels)){
//        $media_hotels[] = $row_item;
//    }
    return $media_hotels;
}
//return destination description
function destinationDesc($dest){
    $destinationDesc = $dest._(' is a top choice with fellow travelers on your selected dates<br><strong>Tip:</strong> Prices might be higher than normal, so try searching with different dates if possible. ');
    return $destinationDesc;
}
$destinationDesc = destinationDesc('Paris');
//return destination description
function availabilityDesc($dest){
    $availabilityDesc = '';
    return $availabilityDesc;
}
$availabilityDesc = availabilityDesc('Paris');

function percentReserved($dest){
    $percentReserved = '68';
    return $percentReserved;
}
$percentReserved = percentReserved('Paris')." %";

$data['destinationDesc'] = $destinationDesc;
$data['availabilityDesc'] = $availabilityDesc;
$data['percentReserved'] = $percentReserved;

$data['searchResults'] = _('search results');
$data['filterResults'] = _('Filter results');
$data['destHotelName'] = _('Destination');
$data['place2Go'] = _('Where are you going ?');
$data['bookingDates'] = _('Booking dates');
$data['from'] = _('from');
$data['to'] = _('to');
$data['hotelClass'] = _('change your hotel class selection ?');
$data['hotel'] = _('Your hotel');

$data['cuisineTxt'] = _('Cuisine');
$data['priceAvg'] = _('PRICE (AVG. PER NIGHT)');
$data['offerTxt'] = _('Special Offers');
$data['discountRest'] = _('DISCOUNTED RESTAURANTS');
$data['hotelPref'] = _('Hotel Preferences');
$data['tuberEval'] = _('Tubers evaluation');
$data['tuberEvals'] = _('Tubers evaluations');
$data['reserved'] = _('reserved');
$data['sortBy'] = _('Sort by');
$data['price'] = _('Price');
$data['guestEval'] = _('Guest Evaluation');
$data['hotelName'] = _('Restaurant Name');
$data['starRating'] = _('Star Rating');
$data['popular'] = _('Most Popular');
$data['priceVal'] = 'price';
$data['guestEvalVal'] = 'rate';
$data['hotelNameVal'] = 'name';
$data['starRatingVal'] = 'stars';
$data['popularVal'] = 'review';
$data['more'] = _('more');
$data['less'] = _('less');

$data['rateNight'] = _('rate per night');
$data['details'] = _('SEE DETAILS');
$data['onMap'] = _('Check it on map');
$data['viewHotel'] = _('View Hotel');
$data['changeCurLink'] = returnLink("parts/currency.php");;


$cuisine = array();
$cuisine[0]["value"] = '1';
$cuisine[1]["value"] = '2';
$cuisine[2]["value"] = '3';
$cuisine[3]["value"] = '4';
$cuisine[4]["value"] = '5';
$cuisine[5]["value"] = '6';
$cuisine[6]["value"] = '7';
$cuisine[7]["value"] = '8';
$cuisine[8]["value"] = '9';
$cuisine[9]["value"] = '10';
$cuisine[10]["value"] = '11';
$cuisine[11]["value"] = '12';
$cuisine[12]["value"] = '13';
$cuisine[13]["value"] = '14';
$cuisine[14]["value"] = '15';
$cuisine[15]["value"] = '16';
$cuisine[16]["value"] = '17';
$cuisine[17]["value"] = '18';
$cuisine[18]["value"] = '19';
$cuisine[19]["value"] = '20';
$cuisine[20]["value"] = '21';

$cuisine[0]["display"] = _('French');
$cuisine[1]["display"] = _('Italian');
$cuisine[2]["display"] = _('Lebanese');
$cuisine[3]["display"] = _('Thai');
$cuisine[4]["display"] = _('Spanish');
$cuisine[5]["display"] = _('European');
$cuisine[6]["display"] = _('Indian');
$cuisine[7]["display"] = _('International');
$cuisine[8]["display"] = _('Iranian');
$cuisine[9]["display"] = _('Italian');
$cuisine[10]["display"] = _('South American');
$cuisine[11]["display"] = _('American');
$cuisine[12]["display"] = _('Arabic');
$cuisine[13]["display"] = _('Asian');
$cuisine[14]["display"] = _('Caribbean');
$cuisine[15]["display"] = _('Cuban');
$cuisine[16]["display"] = _('Korean');
$cuisine[17]["display"] = _('Mexican');
$cuisine[18]["display"] = _('Portuguese');
$cuisine[19]["display"] = _('Russian');
$cuisine[20]["display"] = _('Scandinavian');

$cuisine[0]["number"] = ' (12)';
$cuisine[6]["number"] = ' (9)';
$cuisine[9]["number"] = ' (24)';

$offers = array();
$offers[0]["value"] = '1';
$offers[1]["value"] = '2';
$offers[2]["value"] = '3';
$offers[3]["value"] = '4';
$offers[4]["value"] = '5';

$offers[0]["display"] = _('All offers');
$offers[1]["display"] = _('-50&percnt; off the menu');
$offers[2]["display"] = _('-40&percnt; off the menu');
$offers[3]["display"] = _('-40&percnt; off set menus');
$offers[4]["display"] = _('-30&percnt; off the check');

$offers[1]["number"] = ' (12)';
$offers[2]["number"] = ' (9)';
$offers[3]["number"] = ' (24)';

$menus = array();
$menus[0]["value"] = '1';
$menus[1]["value"] = '2';
$menus[2]["value"] = '3';

$menus[0]["display"] = _('SET MENUS');
$menus[1]["display"] = _('BRUNCH');
$menus[2]["display"] = _('GROUPS');

$menus[0]["number"] = ' (41)';
$menus[1]["number"] = ' (9)';
$menus[2]["number"] = ' (6)';

$data['menus'] = $menus;

$cards = array();
$cards[0]["value"] = '1';
$cards[1]["value"] = '2';
$cards[2]["value"] = '3';

$cards[0]["display"] = _('Visa ');
$cards[1]["display"] = _('MASTERCARD');
$cards[2]["display"] = _('CREDIT CARD');

$cards[0]["number"] = ' (41)';
$cards[1]["number"] = ' (9)';
$cards[2]["number"] = ' (6)';

$data['cards'] = $cards;

$with = array();
$with[0]["value"] = '1';
$with[1]["value"] = '2';
$with[2]["value"] = '3';
$with[3]["value"] = '4';

$with[0]["display"] = _('AMONGSR FRIENDS');
$with[1]["display"] = _('AS A FAMILY');
$with[2]["display"] = _('BUSINESS LUNCH');
$with[3]["display"] = _('ROMANTIC');

$with[0]["number"] = ' (41)';
$with[1]["number"] = ' (19)';
$with[2]["number"] = ' (60)';
$with[3]["number"] = ' (36)';

$data['with'] = $with;

$restriction = array();
$restriction[0]["value"] = '1';
$restriction[1]["value"] = '2';
$restriction[2]["value"] = '3';
$restriction[3]["value"] = '4';
$restriction[4]["value"] = '5';

$restriction[0]["display"] = _('GLUTEN FREE');
$restriction[1]["display"] = _('HALAL');
$restriction[2]["display"] = _('KOSHER');
$restriction[3]["display"] = _('ORGANIC');
$restriction[4]["display"] = _('VEGETARIAN DISHES');

$restriction[0]["number"] = ' (41)';
$restriction[1]["number"] = ' (19)';
$restriction[2]["number"] = ' (60)';
$restriction[3]["number"] = ' (36)';
$restriction[4]["number"] = ' (26)';

$data['restriction'] = $restriction;

$meals = array();
$meals[0]["value"] = '1';
$meals[1]["value"] = '2';
$meals[2]["value"] = '3';

$meals[0]["display"] = _('SEAFOOD PLATTER');
$meals[1]["display"] = _('ALL YOU CAN EAT BUFFET');
$meals[2]["display"] = _('BURGER');

$meals[0]["number"] = ' (15)';
$meals[1]["number"] = ' (19)';
$meals[2]["number"] = ' (26)';

$data['meals'] = $meals;

$service = array();
$service[0]["value"] = '1';
$service[1]["value"] = '2';
$service[2]["value"] = '3';
$service[3]["value"] = '4';
$service[4]["value"] = '5';

$service[0]["display"] = _('ENGLISH SPOKEN');
$service[1]["display"] = _('CAN BE PRIVATIZED');
$service[2]["display"] = _('WINE BY THE GLASSS');
$service[3]["display"] = _('FAMILY FRIENDLY');
$service[4]["display"] = _('AIR CONDITIONED');

$service[0]["number"] = ' (72)';
$service[1]["number"] = ' (12)';
$service[2]["number"] = ' (29)';
$service[3]["number"] = ' (24)';
$service[4]["number"] = ' (76)';

$data['services'] = $service;

$occasions = array();
$occasions[0]["value"] = '1';
$occasions[1]["value"] = '2';
$occasions[2]["value"] = '3';
$occasions[3]["value"] = '4';

$occasions[0]["display"] = _('BIRTHDAY');
$occasions[1]["display"] = _('SEMINAR');
$occasions[2]["display"] = _('CHRISTENING');
$occasions[3]["display"] = _('COMMUNION');

$occasions[0]["number"] = ' (41)';
$occasions[1]["number"] = ' (19)';
$occasions[2]["number"] = ' (60)';
$occasions[3]["number"] = ' (36)';

$data['occasions'] = $occasions;
$data['timeTxt'] = _('Time');
$data['dateTxt'] = _('Date');
$data['hotelPrefOpt'] = $hotelPrefOpt;
$data['cuisine'] = $cuisine;
$data['offers'] = $offers;
$data['menusTxt'] = _('Menus');
$data['serviceTxt'] = _('Service');

$data['cardsTxt'] = _('Accepted credit cards');
$data['withTxt'] = _('With whom?');
$data['restrictionTxt'] = _('Menu restrictions');
$data['mealTxt'] = _('Meal');
$data['occasionTxt'] = _('Special occasion');

$tuberEvalsOpt = array('30','16','35','77','99');
$data['tuberEvalsOpt'] = $tuberEvalsOpt;
$data['tuberEvalsTotal'] = '350';

require_once $theLink.'twig_parts/_head.php';
require_once $theLink.'twig_parts/_language_bar.php';//remember to include the css and js for the language bar
/*$dump = print_r($data, true);
$data['dump'] = $dump;*/
include($theLink . "twig_parts/_foot.php");
echo $template->render($data);