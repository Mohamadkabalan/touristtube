<?php
$path = "../";
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" =>0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/functions/bag.php" );
include_once ( $path . "inc/twigFct.php" );

//$link = "http://para-tube/ttback/uploads/";
$link = ReturnLink('media/discover').'/';

$theLink = $CONFIG ['server']['root'];
//require_once $theLink.'vendor/autoload.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($theLink.'twig_templates/');
$twig = new Twig_Environment($loader, array(
    'cache' => $theLink.'twig_cache/','debug' => false,
));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('hotel-search-ajax.twig');
$user_id = userGetID();

$user_is_logged=0;
if(userIsLogged()){
    $user_is_logged=1;
}

function getRestaurant($longitude_search0,$longitude_search1,$latitude_search0,$latitude_search1,$page,$orderBy,$direction){
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
	global $dbConn;
	$params = array(); 
    if($orderBy =='rate') $orderBy='id';
    if($orderBy == 'review'){
//        $query_restaurants = "SELECT h.*, i.id as i_id , i.filename as img, count(v.restaurant_id) as review FROM `discover_restaurants` as h INNER JOIN discover_restaurants_images as i on i.restaurant_id=h.id left JOIN discover_restaurants_reviews as v on v.restaurant_id=h.id AND v.published=1  WHERE h.longitude BETWEEN $longitude_search0 AND $longitude_search1  AND h.latitude BETWEEN $latitude_search0 AND $latitude_search1 GROUP BY h.id order by $orderBy $direction LIMIT $page, 9";
        $query_restaurants = "SELECT h.*, i.id as i_id , i.filename as img, count(v.restaurant_id) as review FROM `discover_restaurants` as h INNER JOIN discover_restaurants_images as i on i.restaurant_id=h.id left JOIN discover_restaurants_reviews as v on v.restaurant_id=h.id AND v.published=1  WHERE h.longitude BETWEEN :Longitude_search0 AND Longitude_search1  AND h.latitude BETWEEN :Latitude_search0 AND :Latitude_search1 GROUP BY h.id order by $orderBy $direction LIMIT :Page, 9";
    }else{
//        $query_restaurants = "SELECT h.*, i.id as i_id , i.filename as img FROM `discover_restaurants` as h INNER JOIN discover_restaurants_images as i on i.restaurant_id=h.id WHERE h.longitude BETWEEN $longitude_search0 AND $longitude_search1  AND h.latitude BETWEEN $latitude_search0 AND $latitude_search1 GROUP BY h.id order by $orderBy $direction LIMIT $page, 9";
        $query_restaurants = "SELECT h.*, i.id as i_id , i.filename as img FROM `discover_restaurants` as h INNER JOIN discover_restaurants_images as i on i.restaurant_id=h.id WHERE h.longitude BETWEEN :Longitude_search0 AND Longitude_search1  AND h.latitude BETWEEN :Latitude_search0 AND :Latitude_search1 GROUP BY h.id order by $orderBy $direction LIMIT :Page, 9";
    }
    $params[] = array(  "key" => ":Longitude_search0",
                        "value" =>$longitude_search0);
    $params[] = array(  "key" => ":Longitude_search1",
                        "value" =>$longitude_search1);
    $params[] = array(  "key" => ":Latitude_search0",
                        "value" =>$latitude_search0);
    $params[] = array(  "key" => ":Latitude_search1",
                        "value" =>$latitude_search1);
    $params[] = array(  "key" => ":Page",
                        "value" =>$page,
                        "type" =>"::PARAM_INT");

    //$query_restaurants = "SELECT h.*, i.id as i_id , i.filename as img FROM `discover_restaurants` as h INNER JOIN discover_restaurants_images as i on i.restaurant_id=h.id WHERE h.longitude BETWEEN $longitude_search0 AND $longitude_search1  AND h.latitude BETWEEN $latitude_search0 AND $latitude_search1 GROUP BY h.id order by $orderBy $direction LIMIT $page, 9";
    $restaurants = array();
//    $ret_restaurants = db_query($query_restaurants);
    $select = $dbConn->prepare($query_restaurants);
    PDO_BIND_PARAM($select,$params);
    $ret_restaurants    = $select->execute();
    $row = $select->fetchAll();
//    while($row = db_fetch_array($ret_restaurants)){
    foreach($row as $row_item){
        $restaurants[] = $row_item;
    }
    return $restaurants;
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
}

function getRestaurantByCountryCode($code,$page,$orderBy,$direction){
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
	global $dbConn;
	$params = array(); 
    if($orderBy =='rate') $orderBy='id';
    if($orderBy == 'review'){
//        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img , count(v.restaurant_id) as review FROM `discover_restaurants` as h INNER JOIN discover_restaurants_images as i on i.restaurant_id=h.id left JOIN discover_restaurants_reviews as v on v.restaurant_id=h.id AND v.published=1 WHERE h.country = '$code' GROUP BY h.id order by $orderBy $direction LIMIT $page, 9";
        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img , count(v.restaurant_id) as review FROM `discover_restaurants` as h INNER JOIN discover_restaurants_images as i on i.restaurant_id=h.id left JOIN discover_restaurants_reviews as v on v.restaurant_id=h.id AND v.published=1 WHERE h.country = :Code GROUP BY h.id order by $orderBy $direction LIMIT :Page, 9";
    }else{
//        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img FROM `discover_restaurants` as h INNER JOIN discover_restaurants_images as i on i.restaurant_id=h.id WHERE h.countryCode = '$code'  GROUP BY h.id order by $orderBy $direction LIMIT $page, 9";
        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img FROM `discover_restaurants` as h INNER JOIN discover_restaurants_images as i on i.restaurant_id=h.id WHERE h.countryCode = :Code  GROUP BY h.id order by $orderBy $direction LIMIT :Page, 9";
    }
    $params[] = array(  "key" => ":Code",
                        "value" =>$code);
    $params[] = array(  "key" => ":Page",
                        "value" =>$page,
                        "type" =>"::PARAM_INT");

    $hotels = array();//return $query_hotels;
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params);
    $ret_hotels    = $select->execute();
//    $ret_hotels = db_query($query_hotels);
    $hotels = $select->fetchAll();
//    while($row = db_fetch_array($ret_hotels)){
//        $hotels[] = $row;
//    }
    return $hotels;
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
}
function getRestaurantBySearchValue($search,$page,$orderBy,$direction){
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
	global $dbConn;
	$params = array(); 
    if($orderBy =='rate') $orderBy='id';
    if($orderBy == 'review'){
//        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img , count(v.restaurant_id) as review FROM `discover_restaurants` as h INNER JOIN discover_restaurants_images as i on i.restaurant_id=h.id left JOIN discover_restaurants_reviews as v on v.restaurant_id=h.id AND v.published=1 WHERE h.name like '%$search%' or h.city like '%$search%' GROUP BY h.id order by $orderBy $direction LIMIT $page, 9";
        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img , count(v.restaurant_id) as review FROM `discover_restaurants` as h INNER JOIN discover_restaurants_images as i on i.restaurant_id=h.id left JOIN discover_restaurants_reviews as v on v.restaurant_id=h.id AND v.published=1 WHERE h.name like :Search or h.city like :Search GROUP BY h.id order by $orderBy $direction LIMIT :Page, 9";
    }else{
//        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img FROM `discover_restaurants` as h INNER JOIN discover_restaurants_images as i on i.restaurant_id=h.id WHERE h.name like '%$search%' or h.city like '%$search%' GROUP BY h.id order by $orderBy $direction LIMIT $page, 9";
        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img FROM `discover_restaurants` as h INNER JOIN discover_restaurants_images as i on i.restaurant_id=h.id WHERE h.name like :Search or h.city like :Search GROUP BY h.id order by $orderBy $direction LIMIT :Page, 9";
    }
    $params[] = array(  "key" => ":Search",
                        "value" =>"%".$search."%");
    $params[] = array(  "key" => ":Page",
                        "value" =>$page,
                        "type" =>"::PARAM_INT");
    $hotels = array();//return $query_hotels;
//    $ret_hotels = db_query($query_hotels);
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params);
    $ret_hotels    = $select->execute();
    $hotels = $select->fetchAll();
//    while($row = db_fetch_array($ret_hotels)){
//        $hotels[] = $row_item;
//    }
    return $hotels;
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
}

function getRestaurantNb($longitude_search0,$longitude_search1,$latitude_search0,$latitude_search1){
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
    global $dbConn;
    $params = array(); 
//    $query_restaurants = "SELECT count(DISTINCT(h.id)) FROM `discover_restaurants` as h INNER JOIN discover_restaurants_images as i on i.restaurant_id=h.id WHERE h.longitude BETWEEN $longitude_search0 AND $longitude_search1  AND h.latitude BETWEEN $latitude_search0 AND $latitude_search1";
    $query_restaurants = "SELECT count(DISTINCT(h.id)) FROM `discover_restaurants` as h INNER JOIN discover_restaurants_images as i on i.restaurant_id=h.id WHERE h.longitude BETWEEN :Longitude_search0 AND :Longitude_search1  AND h.latitude BETWEEN :Latitude_search0 AND :Latitude_search1";
    $params[] = array(  "key" => ":Longitude_search0",
                        "value" =>$longitude_search0);
    $params[] = array(  "key" => ":Longitude_search1",
                        "value" =>$longitude_search1);
    $params[] = array(  "key" => ":Latitude_search0",
                        "value" =>$latitude_search0);
    $params[] = array(  "key" => ":Latitude_search1",
                        "value" =>$latitude_search1);
//    $ret_restaurants = db_query($query_restaurants);
    $select = $dbConn->prepare($query_restaurants);
    PDO_BIND_PARAM($select,$params);
    $ret_restaurants    = $select->execute();
//    $row = db_fetch_array($ret_restaurants);
    $row = $select->fetch();
    $count = $row[0];
    return $count;
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
}
function getRestaurantByCountryCodeNb($code){
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
    global $dbConn;
    $params = array();
//    $query_hotels = "SELECT count(DISTINCT(h.id)) FROM `discover_restaurants` as h INNER JOIN discover_restaurants_images as i on i.restaurant_id=h.id WHERE h.country = '$code'";
    $query_hotels = "SELECT count(DISTINCT(h.id)) FROM `discover_restaurants` as h INNER JOIN discover_restaurants_images as i on i.restaurant_id=h.id WHERE h.country = :Code";
    $params[] = array(  "key" => ":Code",
                        "value" =>$code);
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params);
    $ret_hotels    = $select->execute();
//    $ret_hotels = db_query($query_hotels);
//    $row = db_fetch_array($ret_hotels);
    $row = $select->fetch();
    $count = $row[0];
    return $count;
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
}
function getRestaurantBySearchValueNb($search){
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
    global $dbConn;
    $params = array();
//    $query_hotels = "SELECT count(DISTINCT(h.id)) FROM `discover_restaurants` as h INNER JOIN discover_restaurants_images as i on i.restaurant_id=h.id WHERE h.name like '%$search%' or h.city like '%$search%' ";
    $query_hotels = "SELECT count(DISTINCT(h.id)) FROM `discover_restaurants` as h INNER JOIN discover_restaurants_images as i on i.restaurant_id=h.id WHERE h.name like :Search or h.city like :Search ";
    $params[] = array(  "key" => ":Search",
                        "value" =>"%".$search."%");
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params);
    $ret_hotels    = $select->execute();
//    $ret_hotels = db_query($query_hotels);
//    $row = db_fetch_array($ret_hotels);
    $row = $select->fetch();
    $count = $row[0];
    return $count;
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
}

//return images for a specific restaurant
function getRestaurantMedia($txt_id,$spath){
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
    global $dbConn;
    $params = array();
    global $link;
//    $query_restaurants = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = $txt_id ORDER BY default_pic DESC LIMIT 18";
    $query_restaurants = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = :Txt_id ORDER BY default_pic DESC LIMIT 18";
    $params[] = array(  "key" => ":Txt_id",
                        "value" =>$txt_id);
    $media_restaurants = array();
    $select = $dbConn->prepare($query_restaurants);
    PDO_BIND_PARAM($select,$params);
    $ret_restaurants    = $select->execute();
//    $ret_restaurants = db_query($query_restaurants);
    $row = $select->fetchAll();
//    while($row = db_fetch_array($ret_restaurants)){
    foreach($row as $row_item){
        $imgArr = array();
        $imgArr['img'] = $link.'thumb/'.$row_item['img'];
        $imgArr['thumb'] = $link."thumb/".$row_item['img'];
        $dims = imageGetDimensions( $spath . 'media/discover/thumb/' . $row_item['img']);
        //$dims = imageGetDimensions( $link. $row['img']);
        $width = $dims['width'];
        $height = $dims['height'];
        //for large size
        $new_height = 256;
        $scaleWidth= 476/$width;
        $scaleHeight= 256/$height;
        if($scaleWidth>$scaleHeight){
            $new_width = $width*$scaleWidth;
            $new_height = $height*$scaleWidth;
        }else{
            $new_width = $width*$scaleHeight;
            $new_height = $height*$scaleHeight;
        }
        $dataw=$new_width;
        $datah = $new_height;
        //for medium size
        $new_height2 = 112;
        $scaleWidth2= 219/$width;
        $scaleHeight2= 112/$height;
        if($scaleWidth2>$scaleHeight2){
            $new_width2 = $width*$scaleWidth2;
            $new_height2 = $height*$scaleWidth2;
        }else{
            $new_width2 = $width*$scaleHeight2;
            $new_height2 = $height*$scaleHeight2;
        }

        $imgArr['imgWidth'] = $dataw;
        $imgArr['imgHeight'] = $datah;
        $imgArr['style'] = 'style="width:'.round($dataw).'px;height:'.round($datah).'px"';
        $imgArr['mediumStyle'] = 'style="width:'.round($new_width2).'px;height:'.round($new_height2).'px"';
        $media_restaurants[] = $imgArr;
    }
    return $media_restaurants;
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
}
function getRestaurantMediaNb($txt_id){
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
    global $dbConn;
    $params = array();
    $nb = '';
//    $query_restaurants = "SELECT count(*) FROM `discover_restaurants_images` WHERE restaurant_id = $txt_id ORDER BY default_pic DESC";
    $query_restaurants = "SELECT count(*) FROM `discover_restaurants_images` WHERE restaurant_id = :Txt_id ORDER BY default_pic DESC";
    $params[] = array(  "key" => ":Txt_id",
                        "value" =>$txt_id);
//    $ret_restaurants = db_query($query_restaurants);
    $select = $dbConn->prepare($query_restaurants);
    PDO_BIND_PARAM($select,$params);
    $ret_restaurants    = $select->execute();
//    $row = db_fetch_array($ret_restaurants);
    $row = $select->fetch();
    $nb = $row[0];
    return $nb;
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
}
function navigatePhoto($txt_id,$page){
    $arr = array();
    $showedPerPage = 18;
    $total = getRestaurantMediaNb($txt_id);
    $totalPage = ceil($total / $showedPerPage);
    //next 
    if($totalPage == $page){
        $arr[1] = '';
    }else if($totalPage >= ($page+1)){
        $arr[1] = ($page+1);
    }
    //prev
    if($page == 1){
        $arr[0] = '';
    }else{
        $arr[0] = $page-1;
    }
    return $arr;
}

function getReviewNb($txt_id){
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
    global $dbConn;
    $params = array();
//    $query_restaurants_reviews = "SELECT count(*) FROM `discover_restaurants_reviews` WHERE restaurant_id = $txt_id AND published=1";
    $query_restaurants_reviews = "SELECT count(*) FROM `discover_restaurants_reviews` WHERE restaurant_id = :Txt_id AND published=1";
    $params[] = array(  "key" => ":Txt_id",
                        "value" =>$txt_id);
//    $ret_restaurants_reviews = db_query($query_restaurants_reviews);
    $select = $dbConn->prepare($query_restaurants_reviews);
    PDO_BIND_PARAM($select,$params);
    $ret_restaurants_reviews    = $select->execute();
//    $row = db_fetch_array($ret_restaurants_reviews);
    $row = $select->fetch();
    $toReturn = $row[0];
    return $toReturn;
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
}
//return destination description
function destinationDesc($dest){
$destinationDesc = $dest._(' is a top choice with fellow travelers on your selected dates<br><strong>Tip:</strong> Prices might be higher than normal, so try searching with different dates if possible. ');
return $destinationDesc;
}
//return destination description
function availabilityDesc($dest){
$availabilityDesc = _('1896 properties found in Paris, 436 available.');
return $availabilityDesc;
}
function percentReserved($dest){
    $percentReserved = '68';
    return $percentReserved;
}
$symbol = '$ ';
$rate = '1';
if(isset($_SESSION['currencyRate'])){
    $rate = $_SESSION['currencyRate'];
}
if(isset($_SESSION['currencySymbol'])){
    $symbol = $_SESSION['currencySymbol'];
}
//$destination = xss_sanitize(@$_POST['destination']);
//$country_code = xss_sanitize(@$_POST['country_code']);
//$city_code = intval(@$_POST['city_code']);
$destination = $request->request->get('destination', '');
$country_code = $request->request->get('country_code', '');
$city_code = intval($request->request->get('city_code', 0));
//$state_code = xss_sanitize(@$_POST['state_code']);
//$d = xss_sanitize(@$_POST['d']);
//$t = xss_sanitize(@$_POST['t']);
//$persons_val = xss_sanitize(@$_POST['persons']);
$d = $request->request->get('d', '');
$t = $request->request->get('t', '');
$persons_val = $request->request->get('persons', '');
/*$from = xss_sanitize(@$_POST['from']);
$to = xss_sanitize(@$_POST['to']);
$restaurantClass = xss_sanitize(@$_POST['restaurantClass']);
$priceRange = xss_sanitize(@$_POST['priceRange']);
$accomType = xss_sanitize(@$_POST['accomType']);
$restaurantPref = xss_sanitize(@$_POST['restaurantPref']);*/
//$tuberEvals = xss_sanitize(@$_POST['tuberEvals']);
//$orderBy = xss_sanitize(@$_POST['orderBy']);
//$direction = xss_sanitize(@$_POST['direction']);
$tuberEvals = $request->request->get('tuberEvals', '');
$orderBy = $request->request->get('orderBy', '');
$direction = $request->request->get('direction', '');
if(strtolower($direction) != 'asc'){
    $direction = 'desc';
}
//$restaurant_page = intval(@$_POST['restaurant_page']);
$restaurant_page = intval($request->request->get('restaurant_page', 0));
$restaurant_page = $restaurant_page*9;
//return longitude and latidude for a city
$diff_angle =0.2;
$percentReserved = percentReserved($destination)." %";
$data_array = array();
/*   If city Code is set */
if( isset($city_code) && $city_code > 0){
    $data_array = worldcitiespopInfo(intval($city_code));
    if( count($data_array)>0 ){
//        $destination = $data_array['name'];
//        $country_val = $data_array['country_code'];
        $longitude = $data_array['longitude'];
        $latitude = $data_array['latitude'];
//        $city_id = $data_array['id'];
        $longitude_search0 =$longitude - $diff_angle;
        $longitude_search1 =$longitude + $diff_angle;
        $latitude_search0 = $latitude - $diff_angle;
        $latitude_search1 = $latitude + $diff_angle;
        $restaurants = getRestaurant($longitude_search0,$longitude_search1,$latitude_search0,$latitude_search1,$restaurant_page,$orderBy,$direction);
        $restaurantsCount = getRestaurantNb($longitude_search0,$longitude_search1,$latitude_search0,$latitude_search1);
    }
}
/*   /  If city Code is set */
else if ($country_code != ""){
    $restaurants = getRestaurantByCountryCode($country_code,$restaurant_page,$orderBy,$direction);
    $restaurantsCount = getRestaurantByCountryCodeNb($country_code);
}
else{
    $restaurants = getRestaurantBySearchValue($destination,$restaurant_page,$orderBy,$direction);
    $restaurantsCount = getRestaurantBySearchValueNb($destination);
}
$RestaurantFullData = array();
foreach($restaurants as $h){
    $htData = array();
    $htData["hotelName"] = $h["name"];
    $htData["price"] = ceil($h["price"] * $rate);
    $htData["address"] = $h["address"];
    $htData["location"] = $h["location"];
    $htData["map"] = ReturnLink('parts/show-on-map.php?type=r&id='.$h["id"]);
    $htData["link"] = ReturnLink('trestaurant/id/'.$h["id"]);
    if($d != ''){
        $htData["link"] .= '/d/'.$d;
    }
    if($t != ''){
        $htData["link"] .= '/t/'.$t;
    }
    if($persons_val != ''){
        $htData["link"] .= '/persons/'.$persons_val;
    }
    if($destination != ''){
        $htData["link"] .= '/s/'.$destination;
    }
    if($country_code != ''){
        $htData["link"] .= '/CO/'.$country_code;
    }
    if($city_code != ''){
        $htData["link"] .= '/C/'.$city_code;
    }
//    if($state_code != ''){
//        $htData["link"] .= '/ST/'.$state_code;
//    }
    $htData["stars"] = returnLink("images/album-rating".$h["stars"].".png");
    $htData["mediaNb"] = getRestaurantMediaNb($h["id"]);
    $htData["media"] = getRestaurantMedia($h["id"],$theLink);
    $first = $htData["media"][0];
    $htData["firstSrc"] = $first['img'];
    $htData["firstStyle"] = $first['style'];
    $htData["mediumStyle"] = $first['mediumStyle'];
    $htData["reviewNb"] = getReviewNb($h["id"]);
    $mediaNav = navigatePhoto($h["id"],1);
    $htData["mediaPrev"] = $mediaNav[0];
    $htData["mediaNext"] = $mediaNav[1];
    $htData["hotelId"] = $h["id"];
    $RestaurantFullData[] = $htData;
}
$destinationDesc = destinationDesc($destination);
$availabilityDesc = availabilityDesc($destination);
$data["hotels"] = $RestaurantFullData;
$data['rateNight'] = _('average per person');
$data['details'] = _('SEE DETAILS');
$data['onMap'] = _('Check it on map');
$data['viewHotel'] = _('View Restaurant');
$data['tuberEval'] = _('Tubers evaluation');
$data['symbol'] = $symbol;

/*$dump = print_r($data, true);
$data['dump'] = $dump;*/

//$Result['restaurants'] = var_dump($restaurants);
$Result['restaurants'] = $template->render($data);
$Result['destinationDesc'] = $destinationDesc;
$Result['percent'] = $percentReserved;
$Result['longitude'] = $longitude;
$Result['latitude'] = $latitude;
$Result['restaurantsCount'] = $restaurantsCount;
$Result['restaurantSearchHeadRightDesc'] = $restaurantsCount. ' properties found';
echo json_encode( $Result);