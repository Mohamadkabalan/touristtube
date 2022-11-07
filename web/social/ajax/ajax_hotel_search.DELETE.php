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
function getHotel($longitude_search0,$longitude_search1,$latitude_search0,$latitude_search1,$page,$orderBy,$direction){
    if($orderBy =='rate') $orderBy='id';
//  Changed by Anthony Malak 14-05-2015 to PDO database
//  <start>
    global $dbConn;
    $params = array();  
    if($orderBy == 'review'){
//        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img , count(v.hotel_id) as review FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id left JOIN discover_hotels_reviews as v on v.hotel_id=h.id AND v.published=1 WHERE h.longitude BETWEEN $longitude_search0 AND $longitude_search1  AND h.latitude BETWEEN $latitude_search0 AND $latitude_search1 GROUP BY h.id order by $orderBy v LIMIT $page, 9";
        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img , count(v.hotel_id) as review FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id left JOIN discover_hotels_reviews as v on v.hotel_id=h.id AND v.published=1 WHERE h.longitude BETWEEN :Longitude_search0 AND :Longitude_search1  AND h.latitude BETWEEN :Latitude_search0 AND :Latitude_search1 GROUP BY h.id order by $orderBy v LIMIT :Page, 9";
    }else{
//        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id WHERE h.longitude BETWEEN $longitude_search0 AND $longitude_search1  AND h.latitude BETWEEN $latitude_search0 AND $latitude_search1 GROUP BY h.id order by $orderBy $direction LIMIT $page, 9";
        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id WHERE h.longitude BETWEEN :Longitude_search0 AND :Longitude_search1  AND h.latitude BETWEEN :Latitude_search0 AND :Latitude_search1 GROUP BY h.id order by $orderBy $direction LIMIT :Page, 9";
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
                        "value" =>$page);
    $hotels = array();//return $query_hotels;
//    $ret_hotels = db_query($query_hotels);
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params);
    $ret_hotels    = $select->execute();
//    while($row = db_fetch_array($ret_hotels)){
//        $hotels[] = $row;
//    }
    $hotels = $select->fetchAll();
    return $hotels;
}
function getHotelByCountryCode($code,$page,$orderBy,$direction){
    if($orderBy =='rate') $orderBy='id';
    global $dbConn;
    $params2 = array();  
    if($orderBy == 'review'){
//        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img , count(v.hotel_id) as review FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id left JOIN discover_hotels_reviews as v on v.hotel_id=h.id AND v.published=1 WHERE h.countryCode = '$code' GROUP BY h.id order by $orderBy $direction LIMIT $page, 9";
        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img , count(v.hotel_id) as review FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id left JOIN discover_hotels_reviews as v on v.hotel_id=h.id AND v.published=1 WHERE h.countryCode = :Code GROUP BY h.id order by $orderBy $direction LIMIT :Page, 9";
    }else{
//        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id WHERE h.countryCode = '$code'  GROUP BY h.id order by $orderBy $direction LIMIT $page, 9";
        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id WHERE h.countryCode = :Code  GROUP BY h.id order by $orderBy $direction LIMIT :Page, 9";
    }
    $params2[] = array(  "key" => ":Code",
                         "value" =>$code);
    $params2[] = array(  "key" => ":Page",
                         "value" =>$page);
    $hotels = array();//return $query_hotels;
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params2);
    $ret_hotels    = $select->execute();
//    $ret_hotels = db_query($query_hotels);
//    while($row = db_fetch_array($ret_hotels)){
//        $hotels[] = $row;
//    }
    $hotels = $select->fetchAll();
    return $hotels;
}
function getHotelByStateName($state,$page,$orderBy,$direction){
    if($orderBy =='rate') $orderBy='id';
    global $dbConn;
    $params3 = array();  
    if($orderBy == 'review'){
//        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img , count(v.hotel_id) as review FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id left JOIN discover_hotels_reviews as v on v.hotel_id=h.id AND v.published=1 WHERE h.stateName = '$state' GROUP BY h.id order by $orderBy $direction LIMIT $page, 9";
        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img , count(v.hotel_id) as review FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id left JOIN discover_hotels_reviews as v on v.hotel_id=h.id AND v.published=1 WHERE h.stateName = :State GROUP BY h.id order by $orderBy $direction LIMIT :Page, 9";
    }else{
//        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id WHERE h.stateName = '$state'  GROUP BY h.id order by $orderBy $direction LIMIT $page, 9";
        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id WHERE h.stateName = :State  GROUP BY h.id order by $orderBy $direction LIMIT :Page, 9";
    }
    $params3[] = array(  "key" => ":State",
                         "value" =>$state);
    $params3[] = array(  "key" => ":Page",
                         "value" =>$page);
    $hotels = array();//return $query_hotels;
//    $ret_hotels = db_query($query_hotels);
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params3);
    $ret_hotels    = $select->execute();
//    while($row = db_fetch_array($ret_hotels)){
//        $hotels[] = $row;
//    }
    $hotels = $select->fetchAll();
//    exit($query_hotels);
    return $hotels;
}
function getHotelBySearchValue($search,$page,$orderBy,$direction){
    if($orderBy =='rate') $orderBy='id';
    global $dbConn;
    $params4 = array();  
    if($orderBy == 'review'){
//        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img , count(v.hotel_id) as review FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id left JOIN discover_hotels_reviews as v on v.hotel_id=h.id AND v.published=1 WHERE h.hotelName like '%$search%' or h.cityName like '%$search%' or h.stateName like '%$search%' GROUP BY h.id order by $orderBy $direction LIMIT $page, 9";
        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img , count(v.hotel_id) as review FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id left JOIN discover_hotels_reviews as v on v.hotel_id=h.id AND v.published=1 WHERE h.hotelName like :Search or h.cityName like :Search or h.stateName like :Search GROUP BY h.id order by $orderBy $direction LIMIT :Page, 9";
    }else{
//        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id WHERE h.hotelName like '%$search%' or h.cityName like '%$search%' or h.stateName like '%$search%' GROUP BY h.id order by $orderBy $direction LIMIT $page, 9";
        $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id WHERE h.hotelName like :Search or h.cityName like :Search or h.stateName like :Search GROUP BY h.id order by $orderBy $direction LIMIT :Page, 9";
    }
    $params4[] = array(  "key" => ":Search",
                         "value" =>"%".$search."%");
    $params4[] = array(  "key" => ":Page",
                         "value" =>$page);
    $hotels = array();//return $query_hotels;
//    $ret_hotels = db_query($query_hotels);
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params4);
    $hotels    = $select->execute();
//    while($row = db_fetch_array($ret_hotels)){
//        $hotels[] = $row;
//    }
    $hotels = $select->fetchAll();
    return $hotels;
}
function getHotelNb($longitude_search0,$longitude_search1,$latitude_search0,$latitude_search1){
    global $dbConn;
    $params5 = array();  
//    $query_hotels = "SELECT count(DISTINCT(h.id)) FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id WHERE h.longitude BETWEEN $longitude_search0 AND $longitude_search1  AND h.latitude BETWEEN $latitude_search0 AND $latitude_search1";
    $query_hotels = "SELECT count(DISTINCT(h.id)) FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id WHERE h.longitude BETWEEN :Longitude_search0 AND :Longitude_search1  AND h.latitude BETWEEN :Latitude_search0 AND :Latitude_search1";
    $params5[] = array(  "key" => ":Longitude_search0",
                         "value" =>$longitude_search0);
    $params5[] = array(  "key" => ":Longitude_search1",
                         "value" =>$longitude_search1);
    $params5[] = array(  "key" => ":Latitude_search0",
                         "value" =>$latitude_search0);
    $params5[] = array(  "key" => ":Latitude_search1",
                         "value" =>$latitude_search1);
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params5);
    $ret_hotels    = $select->execute();
//    $ret_hotels = db_query($query_hotels);
//    $row = db_fetch_array($ret_hotels);
    $row = $select->fetch();
    $count = $row[0];
    return $count;
}
function getHotelByStateNameNb($state){
    global $dbConn;
    $params6 = array(); 
//    $query_hotels = "SELECT count(DISTINCT(h.id)) FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id WHERE h.stateName = '$state'";
    $query_hotels = "SELECT count(DISTINCT(h.id)) FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id WHERE h.stateName = :State";
    $params6[] = array(  "key" => ":State",
                         "value" =>$state);
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params6);
    $ret_hotels    = $select->execute();
//    $ret_hotels = db_query($query_hotels);
//    $row = db_fetch_array($ret_hotels);
    $row = $select->fetch();
    $count = $row[0];
    return $count;
}
function getHotelByCountryCodeNb($code){
    global $dbConn;
    $params7 = array(); 
//    $query_hotels = "SELECT count(DISTINCT(h.id)) FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id WHERE h.countryCode = '$code'";
    $query_hotels = "SELECT count(DISTINCT(h.id)) FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id WHERE h.countryCode = :Code";
    $params7[] = array(  "key" => ":Code",
                         "value" =>$code);
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params7);
    $ret_hotels    = $select->execute();
//    $ret_hotels = db_query($query_hotels);
//    $row = db_fetch_array($ret_hotels);
    $row = $select->fetch();
    $count = $row[0];
    return $count;
}
function getHotelBySearchValueNb($search){
    global $dbConn;
    $params8 = array(); 
//    $query_hotels = "SELECT count(DISTINCT(h.id)) FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id WHERE h.hotelName like '%$search%' or h.cityName like '%$search%' or h.stateName like '%$search%' ";
    $query_hotels = "SELECT count(DISTINCT(h.id)) FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id WHERE h.hotelName like :Search or h.cityName like :Search or h.stateName like :Search ";
    $params8[] = array(  "key" => ":Search",
                         "value" =>"%".$search."%");
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params8);
    $ret_hotels    = $select->execute();
//    $ret_hotels = db_query($query_hotels);
//    $row = db_fetch_array($ret_hotels);
    $row = $select->fetch();
    $count = $row[0];
    return $count;
}
//return images for a specific hotel
function getHotelMedia($txt_id,$spath){
    global $dbConn;
    $params9 = array(); 
    global $link;
//    $query_hotels = "SELECT id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = $txt_id ORDER BY default_pic DESC LIMIT 18";
    $query_hotels = "SELECT id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = :Txt_id ORDER BY default_pic DESC LIMIT 18";
    $params9[] = array(  "key" => ":Txt_id",
                         "value" =>$txt_id);
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params9);
    $ret_hotels    = $select->execute();
    $media_hotels = array();
//    $ret_hotels = db_query($query_hotels);
    $row = $select->fetchAll();
//    while($row = db_fetch_array($ret_hotels)){
    foreach($row as $row_item){
        $imgArr = array();
        $imgArr['img'] = $link.$row_item['img'];
        $imgArr['thumb'] = $link."thumb/".$row_item['img'];

        $dims = imageGetDimensions( $spath . 'media/discover/' .$row_item['img']);

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
        $media_hotels[] = $imgArr;
    }
    return $media_hotels;
}
function getHotelMediaNb($txt_id){
    global $dbConn;
    $params10 = array(); 
    $nb = '';
//    $query_hotels = "SELECT count(*) FROM `discover_hotels_images` WHERE hotel_id = $txt_id";
    $query_hotels = "SELECT count(*) FROM `discover_hotels_images` WHERE hotel_id = :Txt_id";
    $params10[] = array(  "key" => ":Txt_id",
                          "value" =>$txt_id);
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params10);
    $ret_hotels    = $select->execute();
//    $ret_hotels = db_query($query_hotels);
//    $row = db_fetch_array($ret_hotels);
    $row = $select->fetch();
    $nb = $row[0];
    return $nb;
}
function navigatePhoto($txt_id,$page){
    $arr = array();
    $showedPerPage = 18;
    $total = getHotelMediaNb($txt_id);
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
    global $dbConn;
    $params11 = array();  
//    $query_hotels_reviews = "SELECT count(*) FROM `discover_hotels_reviews` WHERE hotel_id = $txt_id AND published=1";
    $query_hotels_reviews = "SELECT count(*) FROM `discover_hotels_reviews` WHERE hotel_id = :Txt_id AND published=1";
    $params11[] = array(  "key" => ":Txt_id",
                          "value" =>$txt_id);
    $select = $dbConn->prepare($query_hotels_reviews);
    PDO_BIND_PARAM($select,$params11);
    $ret_hotels    = $select->execute();
//    $ret_hotels_reviews = db_query($query_hotels_reviews);
//    $row = db_fetch_array($ret_hotels_reviews);
    $row = $select->fetch();
    $toReturn = $row[0];
    return $toReturn;
}
//return destination description
function destinationDesc($dest){
$destinationDesc = $dest._(' is a top choice with fellow travelers on your selected dates<br><strong>Tip:</strong> Prices might be higher than normal, so try searching with different dates if possible. ');
return $destinationDesc;
}
//return destination description
function availabilityDesc($dest){
$availabilityDesc = 'aaa1896 properties found in Paris, 436 available.';
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
//$state_code = xss_sanitize(@$_POST['state_code']);
//$city_code = intval(@$_POST['city_code']);
//$from = xss_sanitize(@$_POST['from']);
//$to = xss_sanitize(@$_POST['to']);
//$hotelClass = xss_sanitize(@$_POST['hotelClass']);
//$priceRange = xss_sanitize(@$_POST['priceRange']);
//$room_val = xss_sanitize(@$_POST['room_val']);
//$accomType = xss_sanitize(@$_POST['accomType']);
//$hotelPref = xss_sanitize(@$_POST['hotelPref']);
//$tuberEvals = xss_sanitize(@$_POST['tuberEvals']);
//$orderBy = xss_sanitize(@$_POST['orderBy']);
//$hotel_page = intval(@$_POST['hotel_page']);
//$hotel_page = $hotel_page*9;
//$direction = xss_sanitize(@$_POST['direction']);
$destination = $request->request->get('destination', '');
$country_code = $request->request->get('country_code', '');
$state_code = $request->request->get('state_code', '');
$city_code = intval($request->request->get('city_code', 0));
$from = $request->request->get('from', '');
$to = $request->request->get('to', '');
$hotelClass = $request->request->get('hotelClass', '');
$priceRange = $request->request->get('priceRange', '');
$room_val = $request->request->get('room_val', '');
$accomType = $request->request->get('accomType', '');
$hotelPref = $request->request->get('hotelPref', '');
$tuberEvals = $request->request->get('tuberEvals', '');
$orderBy = $request->request->get('orderBy', '');
$hotel_page = intval($request->request->get('hotel_page', 0));
$hotel_page = $hotel_page*9;
$direction = $request->request->get('direction', '');
if(strtolower($direction) != 'asc'){
    $direction = 'desc';
}

$longitude_search0 = 0;
$longitude_search1 =0;
$latitude_search0 = 0;
$latitude_search1 = 0;
$hotels = array();

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
        $hotels = getHotel($longitude_search0,$longitude_search1,$latitude_search0,$latitude_search1,$hotel_page,$orderBy,$direction);
        $hotelsCount = getHotelNb($longitude_search0,$longitude_search1,$latitude_search0,$latitude_search1);
    }
/*   /  If city Code is set */
}else if ($country_code != ""){
    $hotels = getHotelByCountryCode($country_code,$hotel_page,$orderBy,$direction);
    $hotelsCount = getHotelByCountryCodeNb($country_code);
}else if($state_code != ""){
    $state_code_arr = explode("-",$state_code);
    if(count($state_code_arr) == 2){
        $data_array = worldStateInfo($state_code_arr[0],$state_code_arr[1]);
        if( count($data_array)>0 ){
            $state_name = $data_array['state_name'];
            $hotels = getHotelByStateName($state_name,$hotel_page,$orderBy,$direction);
            $hotelsCount = getHotelByStateNameNb($state_name);
        }
    }
}else{
    $hotels = getHotelBySearchValue($destination,$hotel_page,$orderBy,$direction);
    $hotelsCount = getHotelBySearchValueNb($destination);
}

$HotelFullData = array();
foreach($hotels as $h){
    $htData = array();
    $htData["hotelName"] = $h["hotelName"];
    $htData["price"] = ceil($h["price"] * $rate);
    $htData["address"] = $h["address"];
    $htData["location"] = $h["location"];
    $htData["hotelName"] = $h["hotelName"];
    $htData["map"] = ReturnLink('parts/show-on-map.php?type=h&id='.$h["id"]);
    $htData["link"] = ReturnLink('thotel/id/'.$h["id"]);
    if($from != ''){
        $htData["link"] .= '/from/'.$from;
    }
    if($to != ''){
        $htData["link"] .= '/to/'.$to;
    }
    if($room_val != ''){
        $htData["link"] .= '/room/'.$room_val;
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
    if($state_code != ''){
        $htData["link"] .= '/ST/'.$state_code;
    }
    $htData["stars"] = returnLink("images/album-rating".$h["stars"].".png");
    $htData["mediaNb"] = getHotelMediaNb($h["id"]);
    $firstArray = getHotelMedia($h["id"],$theLink);
    $htData["media"] = $firstArray;
    $first = $firstArray[0];
    $htData["firstSrc"] = $first['img'];
    $htData["firstStyle"] = $first['style'];
    $htData["mediumStyle"] = $first['mediumStyle'];
    $htData["reviewNb"] = getReviewNb($h["id"]);
    $mediaNav = navigatePhoto($h["id"],1);
    $htData["mediaPrev"] = $mediaNav[0];
    $htData["mediaNext"] = $mediaNav[1];
    $htData["hotelId"] = $h["id"];
    $HotelFullData[] = $htData;
}
$destinationDesc = destinationDesc($destination);
$availabilityDesc = availabilityDesc($destination);
$data["hotels"] = $HotelFullData;
$data['rateNight'] = _('rate per night');
$data['details'] = _('SEE DETAILS');
$data['onMap'] = _('Check it on map');
$data['viewHotel'] = _('View Hotel');
$data['tuberEval'] = _('Tubers evaluation');
$data['symbol'] = $symbol;

/*$dump = print_r($data, true);
$data['dump'] = $dump;*/

$Result['hotels'] = $template->render($data);
$Result['destinationDesc'] = $destinationDesc;
$Result['percent'] = $percentReserved;
$Result['longitude'] = $longitude;
$Result['latitude'] = $latitude;
$Result['hotelsCount'] = $hotelsCount;
$Result['hotelSearchHeadRightDesc'] = $hotelsCount. ' properties found';
$Result['hotels_ss'] = $hotels;

echo json_encode( $Result );