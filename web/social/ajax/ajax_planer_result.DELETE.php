<?php

$path = "../";
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );

include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/functions/bag.php" );

//$link = "http://para-tube/ttback/uploads/";
$link = ReturnLink('media/discover') . '/';

$user_id = userGetID();

$user_is_logged = 0;
if (userIsLogged()) {
    $user_is_logged = 1;
}

//$string_search_value = xss_sanitize(@$_POST['search_name']);
//$data_state = xss_sanitize(@$_POST['data_state']);
//$data_country = xss_sanitize(@$_POST['data_country']);
$string_search_value = $request->request->get('search_name', '');
$data_state = $request->request->get('data_state', '');
$data_country = $request->request->get('data_country', '');
//$currency=xss_sanitize(@$_POST['currency']);
//$currency = @$_POST['currency'];
//$budget = intval(@$_POST['budget']);
//$displayDay = intval(@$_POST['displayDay']);
//$departdate = xss_sanitize(@$_POST['departdate']);
//$nb_days = intval(@$_POST['nb_days']);
//$themes = xss_sanitize(@$_POST['themes']);
//$stars = intval(@$_POST['stars']);
$currency = $request->request->get('currency', '');
$budget = intval($request->request->get('budget', 0));
$displayDay = intval($request->request->get('displayDay', 0));
$departdate = $request->request->get('departdate', '');
$nb_days = intval($request->request->get('nb_days', 0));
$themes = $request->request->get('themes', '');
$stars = intval($request->request->get('stars', 0));
//    $stars = $_POST['stars'];
//$rest_type = xss_sanitize(@$_POST['rest_type']);
$rest_type = $request->request->get('rest_type', '');

$averageperday = round($budget / $nb_days);
$third_price = round($averageperday / 3);
$check_price = $averageperday - $third_price;
if ($averageperday <= 350) {
    $stars = 1;
}

$departdate_arr = explode('-', $departdate);
$departdate_str = implode('', $departdate_arr);

if ($stars == 0)
    $stars = 3;

$flightPlanCoordinates = array();

$diff_angle = 0.1;

$data_array = array();

$data_array = countryNameInfo($string_search_value);
$data_array = (!$data_array) ? array() : $data_array;

if (count($data_array) > 0) {
    $string_search_value = strtolower($data_array['name']);
    $data_country = $data_array['code'];
    $longitude = $data_array['longitude'];
    $latitude = $data_array['latitude'];
    $city_id = '';
} else {
    //$data_array = cityFind($string_search_value, true);
    if ($data_country == "") {
        $data_array = cityFind($string_search_value, true);
    } else {
        $data_array = getCityInfoRow($string_search_value, $data_country, $data_state);
    }
    $data_array = (!$data_array) ? array() : $data_array;
    if (count($data_array) > 0) {
        $string_search_value = $data_array['name'];
        $data_country = $data_array['country_code'];
        $data_state = $data_array['state_code'];
        $longitude = $data_array['longitude'];
        $latitude = $data_array['latitude'];
        $city_id = $data_array['id'];
    }
}
if ($data_state == '' && $city_id != '') {
    $data_array = worldcitiespopInfo($city_id);
    $string_search_value = $data_array['name'];
    $data_country = $data_array['country_code'];
    $data_state = $data_array['state_code'];
    $longitude = $data_array['longitude'];
    $latitude = $data_array['latitude'];
    $city_id = $data_array['id'];
}

$longitude_search0 = $longitude - $diff_angle;
$longitude_search1 = $longitude + $diff_angle;
$latitude_search0 = $latitude - $diff_angle;
$latitude_search1 = $latitude + $diff_angle;

//  Changed by Anthony Malak 14-05-2015 to PDO database
//  <start>
	global $dbConn;
	$params  = array();  
	$params2 = array();  
	$params3 = array();  
	$params4 = array();  
	$params5 = array();  
	$params6 = array();  
if ($stars >= 3 && $averageperday > 350) {
//    $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id WHERE h.longitude BETWEEN $longitude_search0 AND $longitude_search1  AND h.latitude BETWEEN $latitude_search0 AND $latitude_search1 AND stars>=3 AND h.price<= $check_price GROUP BY h.id ORDER BY h.stars DESC LIMIT 0,4";
    $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id WHERE h.longitude BETWEEN :Longitude_search0 AND :Longitude_search1  AND h.latitude BETWEEN :Latitude_search0 AND :Latitude_search1 AND stars>=3 AND h.price<= :Check_price GROUP BY h.id ORDER BY h.stars DESC LIMIT 0,4";
} else {
//    $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id WHERE h.longitude BETWEEN $longitude_search0 AND $longitude_search1  AND h.latitude BETWEEN $latitude_search0 AND $latitude_search1 AND stars<=3 AND h.price<= $check_price GROUP BY h.id ORDER BY h.stars DESC LIMIT 0,4";
    $query_hotels = "SELECT h.*, i.id as i_id , i.filename as img FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id WHERE h.longitude BETWEEN :Longitude_search0 AND :Longitude_search1  AND h.latitude BETWEEN :Latitude_search0 AND :Latitude_search1 AND stars<=3 AND h.price<= :Check_price GROUP BY h.id ORDER BY h.stars DESC LIMIT 0,4";
}
$params[] = array(  "key" => ":Longitude_search0",
                    "value" =>$longitude_search0);
$params[] = array(  "key" => ":Longitude_search1",
                    "value" =>$longitude_search1);
$params[] = array(  "key" => ":Latitude_search0",
                    "value" =>$latitude_search0);
$params[] = array(  "key" => ":Latitude_search1",
                    "value" =>$latitude_search1);
$params[] = array(  "key" => ":Check_price",
                    "value" =>$check_price);

//$query_hotels = "SELECT h.*, i.id as i_id , i.filename as img FROM `discover_hotels` as h INNER JOIN discover_hotels_images as i on i.hotel_id=h.id GROUP BY h.id LIMIT 8";

//$ret_hotels = db_query($query_hotels);
$media_hotels = array();
$select = $dbConn->prepare($query_hotels);
PDO_BIND_PARAM($select,$params);
$ret_hotels    = $select->execute();

//while ($row = db_fetch_array($ret_hotels)) {
//    $media_hotels[] = $row;
//}
$media_hotels = $select->fetchAll();
$image_pic = $link . 'thumb/' . $media_hotels[0]['img'];
$stars = intval($media_hotels[0]['stars']);
if ($stars == 0) {
    $stars = 0;
}
$page_link = ReturnLink('thotel/id/' . $media_hotels[0]['id']);

$log_val = $media_hotels[0]['longitude'] . '/*/' . $media_hotels[0]['latitude'] . '/*/' . addslashes($media_hotels[0]['hotelName']) . '/*/' . SOCIAL_ENTITY_HOTEL . '/*/' . $page_link . '/*/' . $image_pic . '/*/' . $stars;
array_push($flightPlanCoordinates, $log_val);

$query_restaurants = "";
$max = 5;
$cnt = 0;
if (!empty($rest_type)) {
    $cuisines = explode(",", $rest_type);
    $cuisines_cnt = count($cuisines);
    foreach ($cuisines as $cuisine_id) {
//        $query_restaurants .= "(SELECT r.*, i.id as i_id , i.filename as img FROM `discover_restaurants` as r LEFT JOIN discover_restaurants_cuisine c on c.restaurant_id = r.id INNER JOIN discover_restaurants_images as i on i.restaurant_id=r.id WHERE c.cuisine_id = $cuisine_id AND r.longitude BETWEEN $longitude_search0 AND $longitude_search1  AND r.latitude BETWEEN $latitude_search0 AND $latitude_search1 GROUP BY r.id ORDER BY rand() LIMIT 1)";
        $query_restaurants .= "(SELECT r.*, i.id as i_id , i.filename as img FROM `discover_restaurants` as r LEFT JOIN discover_restaurants_cuisine c on c.restaurant_id = r.id INNER JOIN discover_restaurants_images as i on i.restaurant_id=r.id WHERE c.cuisine_id = :Cuisine_id".$cnt." AND r.longitude BETWEEN :Longitude_search0".$cnt." AND :Longitude_search1".$cnt."  AND r.latitude BETWEEN :Latitude_search0".$cnt." AND :Latitude_search1".$cnt." GROUP BY r.id ORDER BY rand() LIMIT 1)";
	$params2[] = array(  "key" => ":Cuisine_id".$cnt,
                            "value" =>$cuisine_id);
	$params2[] = array(  "key" => ":Longitude_search0".$cnt,
                            "value" =>$longitude_search0);
	$params2[] = array(  "key" => ":Longitude_search1".$cnt,
                            "value" =>$longitude_search1);
	$params2[] = array(  "key" => ":Latitude_search0".$cnt,
                            "value" =>$latitude_search0);
	$params2[] = array(  "key" => ":Latitude_search1".$cnt,
                            "value" =>$latitude_search1);
        $cnt++;
        if ($cnt == $max)
            break;
        else {
            $query_restaurants .= " UNION ALL ";
        }
    }
}
$remaining = $max - $cnt;
if ($remaining != 0) {
    $rest_where = !empty($rest_type) ? "c.cuisine_id in ($rest_type) AND" : "";
    $rand = ($displayDay - 1) * 2 . ',';
//    $query_restaurants .= "(SELECT r.*, i.id as i_id , i.filename as img FROM `discover_restaurants` as r LEFT JOIN discover_restaurants_cuisine c on c.restaurant_id = r.id INNER JOIN discover_restaurants_images as i on i.restaurant_id=r.id WHERE $rest_where r.longitude BETWEEN $longitude_search0 AND $longitude_search1  AND r.latitude BETWEEN $latitude_search0 AND $latitude_search1 GROUP BY r.id ORDER BY rand() LIMIT $rand $remaining)";
    $query_restaurants .= "(SELECT r.*, i.id as i_id , i.filename as img FROM `discover_restaurants` as r LEFT JOIN discover_restaurants_cuisine c on c.restaurant_id = r.id INNER JOIN discover_restaurants_images as i on i.restaurant_id=r.id WHERE $rest_where r.longitude BETWEEN :Longitude_search0a AND :Longitude_search1a  AND r.latitude BETWEEN :Latitude_search0a AND :Latitude_search1a GROUP BY r.id ORDER BY rand() LIMIT :Rand :Remaining)";

    $params2[] = array(  "key" => ":Longitude_search0a",
                         "value" =>$longitude_search0);
    $params2[] = array(  "key" => ":Longitude_search1a",
                         "value" =>$longitude_search1);
    $params2[] = array(  "key" => ":Latitude_search0a",
                         "value" =>$latitude_search0);
    $params2[] = array(  "key" => ":Latitude_search1a",
                         "value" =>$latitude_search1);
    $params2[] = array(  "key" => ":Rand",
                         "value" =>$rand,
                         "type" =>"::PARAM_INT");
    $params2[] = array(  "key" => ":Remaining",
                         "value" =>$remaining,
                         "type" =>"::PARAM_INT");
}
//    $query_restaurants = "SELECT r.*, i.id as i_id , i.filename as img FROM `discover_restaurants` as r INNER JOIN discover_restaurants_images as i on i.restaurant_id=r.id WHERE r.longitude BETWEEN $longitude_search0 AND $longitude_search1  AND r.latitude BETWEEN $latitude_search0 AND $latitude_search1 GROUP BY r.id ORDER BY rand() LIMIT $rand 5";
//$query_restaurants = "SELECT r.*, i.id as i_id , i.filename as img FROM `discover_restaurants` as r INNER JOIN discover_restaurants_images as i on i.restaurant_id=r.id GROUP BY r.id LIMIT $rand 5";

//$ret_restaurants = db_query($query_restaurants);
$select = $dbConn->prepare($query_restaurants);
PDO_BIND_PARAM($select,$params2);
$ret_restaurants    = $select->execute();
$media_restaurants = array();

//while ($row = db_fetch_array($ret_restaurants)) {
//    $media_restaurants[] = $row;
//}
$media_restaurants = $select->fetchAll();

$theme_mapper = array(
    "1" => 3,
    "2" => 1,
    "3" => 8,
    "4" => 4,
    "5" => 5,
    "6" => 24,
    "7" => 7,
    "8" => 6
);
$query_landmarks = "";
$max = 5;
$cnt = 0;
if (!empty($themes)) {
    $values = explode("/*/", $themes);
    $themes_cnt = count($values);
    foreach ($values as $theme_value) {
        $theme_id = $theme_mapper[$theme_value];
//        $query_landmarks .= "(SELECT p.*, i.id as i_id , i.filename as img "
//                . "FROM `discover_poi` as p INNER JOIN discover_poi_images as i on i.poi_id=p.id LEFT JOIN discover_poi_categ pc on p.id = pc.poi_id "
//                . "WHERE pc.categ_id = $theme_id  p.longitude BETWEEN $longitude_search0 AND $longitude_search1  AND p.latitude BETWEEN $latitude_search0 AND $latitude_search1 "
//                . " ORDER BY i.default_pic DESC LIMIT 1)";
        $query_landmarks .= "(SELECT p.*, i.id as i_id , i.filename as img "
                . "FROM `discover_poi` as p INNER JOIN discover_poi_images as i on i.poi_id=p.id LEFT JOIN discover_poi_categ pc on p.id = pc.poi_id "
                . "WHERE pc.categ_id = :Theme_id".$cnt."  p.longitude BETWEEN :Longitude_search0".$cnt." AND :Longitude_search1".$cnt."  AND p.latitude BETWEEN :Latitude_search0".$cnt." AND :Latitude_search1".$cnt." "
                . " ORDER BY i.default_pic DESC LIMIT 1)";
	$params3[] = array(  "key" => ":Theme_id".$cnt,
                             "value" =>$theme_id);
	$params3[] = array(  "key" => ":Longitude_search0".$cnt,
                             "value" =>$longitude_search0);
	$params3[] = array(  "key" => ":Longitude_search1".$cnt,
                             "value" =>$longitude_search1);
	$params3[] = array(  "key" => ":Latitude_search0".$cnt,
                             "value" =>$latitude_search0);
	$params3[] = array(  "key" => ":Latitude_search1".$cnt,
                             "value" =>$latitude_search1);
        $cnt++;
        if ($cnt == $max)
            break;
        else {
            $query_landmarks .= " UNION ALL ";
        }
    }
}
$remaining = $max - $cnt;
$rand = ($displayDay - 1) * 4 . ',';
if ($remaining != 0) {
    $landmark_categ = implode(",", explode("/*/", $themes));
    $landmark_where = !empty($landmark_categ) ? "pc.categ_id in ($landmark_categ) AND" : "";
//    $query_landmarks = "(SELECT p.*, i.id as i_id , i.filename as img "
//            . "FROM `discover_poi` as p LEFT JOIN discover_poi_categ pc on p.id = pc.poi_id INNER JOIN discover_poi_images as i on i.poi_id=p.id "
//            . "WHERE $landmark_where p.longitude BETWEEN $longitude_search0 AND $longitude_search1  AND p.latitude BETWEEN $latitude_search0 AND $latitude_search1 "
//            . " GROUP BY p.id ORDER BY i.default_pic DESC LIMIT $rand $remaining)";
    $query_landmarks = "(SELECT p.*, i.id as i_id , i.filename as img "
            . "FROM `discover_poi` as p LEFT JOIN discover_poi_categ pc on p.id = pc.poi_id INNER JOIN discover_poi_images as i on i.poi_id=p.id "
            . "WHERE $landmark_where p.longitude BETWEEN :Longitude_search0a AND :Longitude_search1a  AND p.latitude BETWEEN :Latitude_search0a AND :Latitude_search1a "
            . " GROUP BY p.id ORDER BY i.default_pic DESC LIMIT :Rand :Remaining)";
    $params3[] = array(  "key" => ":Longitude_search0a",
                         "value" =>$longitude_search0);
    $params3[] = array(  "key" => ":Longitude_search1a",
                         "value" =>$longitude_search1);
    $params3[] = array(  "key" => ":Latitude_search0a",
                         "value" =>$latitude_search0);
    $params3[] = array(  "key" => ":Latitude_search1a",
                         "value" =>$latitude_search1);
    $params3[] = array(  "key" => ":Rand",
                         "value" =>$rand,
                         "type" =>"::PARAM_INT");
    $params3[] = array(  "key" => ":Remaining",
                         "value" =>$remaining,
                         "type" =>"::PARAM_INT");
}
//$query_landmarks = "SELECT p.*, i.id as i_id , i.filename as img FROM `discover_poi` as p INNER JOIN discover_poi_images as i on i.poi_id=p.id GROUP BY p.id $rand 5";

$select = $dbConn->prepare($query_landmarks);
PDO_BIND_PARAM($select,$params3);
$ret_landmarks    = $select->execute();
//$ret_landmarks = db_query($query_landmarks);
$media_landmarks = array();

//while ($row = db_fetch_array($ret_landmarks)) {
//    $media_landmarks[] = $row;
//}
$media_hotelss = $select->fetchAll();
$all_bag_count = 0;


$Result = array();

$str = '';

$str .='<div class="plannerManageContainer">
                <div class="plannerManageTitle">'._("TRIP NAME").'</div>
                <input type="text" class="plannerManageValue" value=""/>
                <div class="plannerManagePrintBtn">'._("PRINT").'</div>
                <a href="#sharePopUp" class="shareMe"><div class="plannerManageShareBtn">'._("SHARE").'</div></a>
                <div class="plannerManageSaveBtn">'._("PUSH TO MOBILE").'</div>
                <div class="plannerManageSaveBtn">'._("SAVE").'</div>
            </div>';

$str .='<div class="plannerMapContainer">
                <div class="plannerMapLeft">
                    <div class="mapPartSchedule" index="1">'._("Schedule").'
                        <select class="scheduleSelectDay" onchange="changeDay(this);">';
for ($i = 1; $i <= $nb_days; $i++) {
    if ($displayDay == $i) {
        $str .='<option value="' . $i . '" selected="selected">'._("DAY").' ' . $i . '</option>';
    } else {
        $str .='<option value="' . $i . '">'._("DAY").' ' . $i . '</option>';
    }
}

$str .='</select>
                    </div>
                    <div class="mapParttripDates mapPart_buttons" index="2">'._("Trip dates").'</div>
                    <div class="mapParthotels mapPart_buttons" index="3">'._("Hotels").'</div>
                    <div class="mapPartrestaurants mapPart_buttons" index="4">'._("Restaurants").'</div>
                    <div class="mapPartattractions mapPart_buttons" index="5">'._("Attractions").'</div>
                    <div class="mapPartmyPlanner mapPart_buttons" index="6">'._("My planner").'</div>';
if ($user_is_logged == 1) {
    $str .='<div class="mapPartttBag mapPart_buttons" index="7">'._("TT bag").'</div>';
    $str .='<div class="mapPartTuber mapPart_buttons" index="8">'._("Tuber").'</div>';
}
$str .='<div class="ContainermapPartSchedule scrollpane" index="1">';
$valduration = 0;
$i = 0;
foreach ($media_landmarks as $media_item) {
    if ($i >= 2)
        break;
    $i++;

    $label1 = 8 + $valduration;
    $valduration ++;
    $label2 = 9 + ($valduration);
    $label1 = ($label1 < 10) ? ('0' . $label1) : $label1;
    $label2 = ($label2 < 10) ? ('0' . $label2) : $label2;
    $valduration ++;

    $image_pic = $link . 'thumb/' . $media_item['img'];
    $stars = intval($media_item['stars']);
    if ($stars == 0) {
        $stars = 0;
    }

    $page_link = ReturnLink('things2do/id/' . $media_item['id']);

    $log_val = $media_item['longitude'] . '/*/' . $media_item['latitude'] . '/*/' . addslashes($media_item['name']) . '/*/' . SOCIAL_ENTITY_LANDMARK . '/*/' . $page_link . '/*/' . $image_pic . '/*/' . $stars;
    array_push($flightPlanCoordinates, $log_val);
    $is_bag = checkUserBagItem($user_id, SOCIAL_ENTITY_LANDMARK, $media_item['id']);
    $str .='<div class="stationContainer" data-type="' . SOCIAL_ENTITY_LANDMARK . '" data-id="' . $media_item['id'] . '">
            <div class="stationImgCon">
                <a href="' . $page_link . '" target="_blank"><img src="' . $image_pic . '" alt="" class="stationImg" /></a>
            </div>
            <div class="stationDateCon">
                <div class="stationDate">' . $label1 . ':00-' . $label2 . ':00</div>
                <div class="stationChangeDuration">'._("Change Duration").'</div>
            </div>
            <a href="' . $page_link . '" target="_blank"><div class="stationTitle">' . $media_item['name'] . '</div></a>';
    //$str .='<div class="stationStars"><img src="'.ReturnLink('images/album-rating'.$stars.'.png').'" alt="" class="hotelBigImgstars" /></div>';
    if ($user_is_logged == 1) {
        if ($is_bag) {
            $str .='<div class="stationBag"></div>';
        } else {
            $str .='<div class="hotelsplus"></div>';
        }
    }
    $str .='<div class="stationSep"></div>
        </div>';
}
$i = 0;
foreach ($media_restaurants as $media_item) {
    if ($i >= 1)
        break;
    $i++;
    $label1 = 8 + $valduration;
    $label2 = 9 + $valduration;
    $label1 = ($label1 < 10) ? ('0' . $label1) : $label1;
    $label2 = ($label2 < 10) ? ('0' . $label2) : $label2;
    $valduration++;
    $image_pic = $link . 'thumb/' . $media_item['img'];
    $stars = intval($media_item['stars']);
    if ($stars == 0) {
        $stars = 0;
    }
    $page_link = ReturnLink('trestaurant/id/' . $media_item['id']);

    $log_val = $media_item['longitude'] . '/*/' . $media_item['latitude'] . '/*/' . addslashes($media_item['name']) . '/*/' . SOCIAL_ENTITY_RESTAURANT . '/*/' . $page_link . '/*/' . $image_pic . '/*/' . $stars;
    array_push($flightPlanCoordinates, $log_val);

    $is_bag = checkUserBagItem($user_id, SOCIAL_ENTITY_RESTAURANT, $media_item['id']);
    $str .='<div class="stationContainer" data-type="' . SOCIAL_ENTITY_RESTAURANT . '" data-id="' . $media_item['id'] . '">
            <a href="' . $page_link . '" target="_blank">
                    <div class="stationImgCon">
                            <img src="' . $image_pic . '" alt="" class="stationImg" />
                    </div>
            </a>
            <div class="stationDateCon">
                <div class="stationDate">' . $label1 . ':00-' . $label2 . ':00</div>
                <div class="stationChangeDuration">'._("Change Duration").'</div>
            </div>
            <a href="' . $page_link . '" target="_blank"><div class="stationTitle">' . $media_item['name'] . '</div></a>';
    //$str .='<div class="stationStars"><img src="'.ReturnLink('images/album-rating'.$stars.'.png').'" alt="" class="hotelBigImgstars" /></div>';
    $str .='<div class="stationKeywords">'._("Average price:")." " . rand(30 * $stars, 50 + (30 * ($stars - 1))) . ' ' . $currency . '</div>';
    if ($user_is_logged == 1) {
        if ($is_bag) {
            $str .='<div class="stationBag"></div>';
        } else {
            $str .='<div class="hotelsplus"></div>';
        }
    }
    $str .='<div class="stationSep"></div>
        </div>';
}

for ($i = 2; $i < sizeof($media_landmarks); $i++) {

    if ($i >= 4)
        break;
    $media_item = $media_landmarks[$i];

    $label1 = 8 + $valduration;
    $valduration ++;
    $label2 = 9 + ($valduration);
    $label1 = ($label1 < 10) ? ('0' . $label1) : $label1;
    $label2 = ($label2 < 10) ? ('0' . $label2) : $label2;
    $valduration ++;

    $image_pic = $link . 'thumb/' . $media_item['img'];
    $stars = intval($media_item['stars']);
    if ($stars == 0) {
        $stars = 0;
    }
    $page_link = ReturnLink('things2do/id/' . $media_item['id']);

    $log_val = $media_item['longitude'] . '/*/' . $media_item['latitude'] . '/*/' . addslashes($media_item['name']) . '/*/' . SOCIAL_ENTITY_LANDMARK . '/*/' . $page_link . '/*/' . $image_pic . '/*/' . $stars;
    array_push($flightPlanCoordinates, $log_val);
    $is_bag = checkUserBagItem($user_id, SOCIAL_ENTITY_LANDMARK, $media_item['id']);
    $str .='<div class="stationContainer" data-type="' . SOCIAL_ENTITY_LANDMARK . '" data-id="' . $media_item['id'] . '">
            <div class="stationImgCon">
                <a href="' . $page_link . '" target="_blank"><img src="' . $image_pic . '" alt="" class="stationImg" /></a>
            </div>
            <div class="stationDateCon">
                <div class="stationDate">' . $label1 . ':00-' . $label2 . ':00</div>
                <div class="stationChangeDuration">'._("Change Duration").'</div>
            </div>
            <a href="' . $page_link . '" target="_blank"><div class="stationTitle">' . $media_item['name'] . '</div></a>';
    //$str .='<div class="stationStars"><img src="'.ReturnLink('images/album-rating'.$stars.'.png').'" alt="" class="hotelBigImgstars" /></div>';

    if ($user_is_logged == 1) {
        if ($is_bag) {
            $str .='<div class="stationBag"></div>';
        } else {
            $str .='<div class="hotelsplus"></div>';
        }
    }
    $str .='<div class="stationSep"></div>
        </div>';
}
for ($i = 1; $i < sizeof($media_restaurants); $i++) {
    if ($i >= 2)
        break;
    $media_item = $media_restaurants[$i];

    $label1 = 8 + $valduration;
    $label2 = 9 + $valduration;
    $label1 = ($label1 < 10) ? ('0' . $label1) : $label1;
    $label2 = ($label2 < 10) ? ('0' . $label2) : $label2;
    $valduration++;
    $image_pic = $link . 'thumb/' . $media_item['img'];
    $stars = intval($media_item['stars']);
    if ($stars == 0) {
        $stars = 0;
    }
    $page_link = ReturnLink('trestaurant/id/' . $media_item['id']);

    $log_val = $media_item['longitude'] . '/*/' . $media_item['latitude'] . '/*/' . addslashes($media_item['name']) . '/*/' . SOCIAL_ENTITY_RESTAURANT . '/*/' . $page_link . '/*/' . $image_pic . '/*/' . $stars;
    array_push($flightPlanCoordinates, $log_val);

    $is_bag = checkUserBagItem($user_id, SOCIAL_ENTITY_RESTAURANT, $media_item['id']);
    $str .='<div class="stationContainer" data-type="' . SOCIAL_ENTITY_RESTAURANT . '" data-id="' . $media_item['id'] . '">
            <a href="' . $page_link . '" target="_blank">
                    <div class="stationImgCon">
                            <img src="' . $image_pic . '" alt="" class="stationImg" />
                    </div>
            </a>
            <div class="stationDateCon">
                <div class="stationDate">' . $label1 . ':00-' . $label2 . ':00</div>
                <div class="stationChangeDuration">'._("Change Duration").'</div>
            </div>
            <a href="' . $page_link . '" target="_blank"><div class="stationTitle">' . $media_item['name'] . '</div></a>';
    //$str .='<div class="stationStars"><img src="'.ReturnLink('images/album-rating'.$stars.'.png').'" alt="" class="hotelBigImgstars" /></div>';
    $str .='<div class="stationKeywords">'._("Average price:")." " . rand(30 * $stars, 50 + (30 * ($stars - 1))) . ' ' . $currency . '</div>';
    if ($user_is_logged == 1) {
        if ($is_bag) {
            $str .='<div class="stationBag"></div>';
        } else {
            $str .='<div class="hotelsplus"></div>';
        }
    }
    $str .='<div class="stationSep"></div>
        </div>';
}
$str .='</div>

                    <div class="ContainermapParttripDates" index="2">
                        <div class="tripDatesTitle">'._("Trip days").'</div>
                        <div class="tripDatesVal">' . $nb_days . '</div>
                        <div class="tripDatesCalendarContainer" id="tripDatesCalendarContainer"></div>
                        <div class="tripDatesSmallDesc">'._("Edit your departure dates and your trip days number").'</div>
                    </div>
                    <div class="ContainermapParthotels scrollpane" index="3">
                        <div class="stationSuggestion">'._("TT Hotels Suggestion").'</div>
                        <div class="stationContainer">';
$image_pic = $link . 'thumb/' . $media_hotels[0]['img'];
$stars = intval($media_hotels[0]['stars']);
if ($stars == 0) {
    $stars = 1;
}
$page_link = ReturnLink('thotel/id/' . $media_hotels[0]['id']);
$str .='<a href="' . $page_link . '" target="_blank">
                            <div class="stationImgCon">
                                <img src="' . $image_pic . '" alt="" class="stationImg" />
                            </div>
                            <div class="stationTitle">' . $media_hotels[0]['hotelName'] . '</div>
                        </a>
                        <div class="stationStars"><img src="' . ReturnLink('images/album-rating' . $stars . '.png') . '" alt="" class="hotelBigImgstars" /></div>
                        <div class="stationKeywords">' . $media_hotels[0]['address'] . '</div>
                        <div class="stationKeywords">'._("Average price:")." " . $media_hotels[0]['price'] . ' ' . $currency . '</div>
                        <div class="clearboth"></div>';

$str .='<div class="stationSep_yellow"></div>';
$str .='</div>';
foreach ($media_hotels as $media_item) {
    $image_pic = $link . 'thumb/' . $media_item['img'];
    $stars = intval($media_item['stars']);
    if ($stars == 0) {
        $stars = 1;
    }
    $page_link = ReturnLink('thotel/id/' . $media_item['id']);

    $is_bag = checkUserBagItem($user_id, SOCIAL_ENTITY_HOTEL, $media_item['id']);
    $str .='<div class="stationContainer" data-type="' . SOCIAL_ENTITY_HOTEL . '" data-id="' . $media_item['id'] . '">
        <a href="' . $page_link . '" target="_blank">
                <div class="stationImgCon">
                        <img src="' . $image_pic . '" alt="" class="stationImg" />
                </div>
                <div class="stationTitle">' . $media_item['hotelName'] . '</div>
        </a>
        <div class="stationStars"><img src="' . ReturnLink('images/album-rating' . $stars . '.png') . '" alt="" class="hotelBigImgstars" /></div>
        <div class="stationKeywords">' . $media_item['address'] . '</div>
        <div class="stationKeywords">'._("Average price:")." " . $media_item['price'] . '' . $currency . '</div>';
    if ($user_is_logged == 1) {
        if ($is_bag) {
            $str .='<div class="stationBag"></div>';
        } else {
            $str .='<div class="hotelsplus"></div>';
        }
    }
    $str .='<div class="stationSep"></div>
    </div>';
}
$str .='</div>
                <div class="ContainermapPartrestaurants scrollpane" index="4">
                    <div class="stationSuggestion">'._("TT Restaurants").'</div>';
foreach ($media_restaurants as $media_item) {
    $image_pic = $link . 'thumb/' . $media_item['img'];
    $stars = intval($media_item['stars']);
    if ($stars == 0) {
        $stars = 1;
    }
    $page_link = ReturnLink('trestaurant/id/' . $media_item['id']);

    $is_bag = checkUserBagItem($user_id, SOCIAL_ENTITY_RESTAURANT, $media_item['id']);
    $str .='<div class="stationContainer" data-type="' . SOCIAL_ENTITY_RESTAURANT . '" data-id="' . $media_item['id'] . '">
                <a href="' . $page_link . '" target="_blank">
                        <div class="stationImgCon">
                                <img src="' . $image_pic . '" alt="" class="stationImg" />
                        </div>
                        <div class="stationTitle">' . $media_item['name'] . '</div>
                </a>';
    //$str .='<div class="stationStars"><img src="'.ReturnLink('images/album-rating'.$stars.'.png').'" alt="" class="hotelBigImgstars" /></div>';
    $str .='<div class="stationKeywords">'._("Average price").': ' . rand(30 * $stars, 50 + (30 * ($stars - 1))) . '' . $currency . '</div>';
    if ($user_is_logged == 1) {
        if ($is_bag) {
            $str .='<div class="stationBag"></div>';
        } else {
            $str .='<div class="hotelsplus"></div>';
        }
    }
    $str .='<div class="stationSep"></div>
        </div>';
}
$str .='</div>
                <div class="ContainermapPartattractions scrollpane" index="5">
                    <div class="stationAttrac">'._("Category").'</div>
                    <select class="attractionSelect">
                        <option>'._("Show all").'</option>
                    </select>
                    <div class="stationAttrac">'._("Sort By").'</div>
                    <select class="attractionSelect marginbottom18">
                        <option>'._("Rating").'</option>
                    </select>';
foreach ($media_landmarks as $media_item) {
    $image_pic = $link . 'thumb/' . $media_item['img'];
    $stars = intval($media_item['stars']);
    if ($stars == 0) {
        $stars = 1;
    }
    $page_link = ReturnLink('things2do/id/' . $media_item['id']);
    $is_bag = checkUserBagItem($user_id, SOCIAL_ENTITY_LANDMARK, $media_item['id']);
    $str .='<div class="stationContainer" data-type="' . SOCIAL_ENTITY_LANDMARK . '" data-id="' . $media_item['id'] . '">
                <div class="stationImgCon">
                        <a href="' . $page_link . '" target="_blank"><img src="' . $image_pic . '" alt="" class="stationImg" /></a>
                </div>
                <a href="' . $page_link . '" target="_blank"><div class="stationTitle">' . $media_item['name'] . '</div></a>';
    //$str .='<div class="stationStars"><img src="'.ReturnLink('images/album-rating'.$stars.'.png').'" alt="" class="hotelBigImgstars" /></div>';
    if ($user_is_logged == 1) {
        if ($is_bag) {
            $str .='<div class="stationBag"></div>';
        } else {
            $str .='<div class="hotelsplus"></div>';
        }
    }
    $str .='<div class="stationSep"></div>
        </div>';
}
$str .='</div>
                <div class="ContainermapPartmyPlanner scrollpane" index="6">
                    <div class="myPlannerLabel">'._("Number of adults").'</div>
                    <select class="myPlannerSelect">
                        <option>1</option><option>2</option>
                    </select>
                    <div class="myPlannerLabel">'._("Number of rooms").'</div>
                    <select class="myPlannerSelect">
                        <option>1</option><option>2</option>
                    </select>
                    <div class="myPlannerLabel">'._("Transportation").'</div>
                    <select class="myPlannerSelect">
                        <option>'._("Walking").'</option>
                        <option>'._("Car").'</option>
                    </select>
                    <div class="myPlannerLabel">'._("Intensity").'</div>
                    <select class="myPlannerSelect">
                        <option>'._("Easy").'</option>
                        <option>'._("Hard").'</option>
                    </select>
                    <div class="interestCategTitle">'._("Edit interest categories").'</div>
                    <div class="interestCategoryOption">'._("Arts").'</div>
                    <div class="interestCategoryOption">'._("Panorama").'</div>
                    <div class="interestCategoryOption">'._("Religious sites").'</div>
                    <div class="interestCategoryOption">'._("Memorial Parks").'</div>
                    <div class="interestCategoryOption">'._("Arts").'</div>
                    <div class="interestCategoryOption">'._("Panorama").'</div>
                    <div class="interestCategoryOption">'._("Religious sites").'</div>
                    <div class="interestCategoryOption">'._("Memorial Parks").'</div>
                    <div class="interestCategoryOption">'._("Arts").'</div>
                    <div class="interestCategoryOption">'._("Panorama").'</div>
                    <div class="interestCategoryOption">'._("Religious sites").'</div>
                    <div class="interestCategoryOption">'._("Memorial Parks").'</div>
                    <div class="interestCategoryOption">'._("Arts").'</div>
                    <div class="interestCategoryOption">'._("Panorama").'</div>
                    <div class="interestCategoryOption">'._("Religious sites").'</div>
                    <div class="interestCategoryOption">'._("Memorial Parks").'</div>
                </div>';
if ($user_is_logged == 1) {
    $str .='<div class="ContainermapPartttBag" index="7">
            <div class="stationAttrac">'._("Filter By").'</div>
            <select class="attractionSelect marginbottom18">
                <option value="0">'._("All").'</option>
                <option value="1">'._("Things To Do").'</option>
                <option value="2">'._("Hotels").'</option>
                <option value="3">'._("Restaurants").'</option>
            </select>';
$count= 0;
    $bagCoordinates = array();
    foreach ($all_bag as $media_bag_item) {
        $item_id = $media_bag_item['item_id'];
        $page_link = '';

        if ($media_bag_item['type'] == SOCIAL_ENTITY_HOTEL) {
//            $query_d = "SELECT r.*, i.id as i_id , i.filename as img, r.latitude as latitude, r.longitude as longitude , r.hotelName as name , hotel_id FROM `discover_hotels` as r INNER JOIN discover_hotels_imges as i on i.hotel_id=r.id WHERE hotel_id = $item_id ORDER BY i.default_pic DESC LIMIT 1";
            $params4 = array();
            $query_d = "SELECT r.*, i.id as i_id , i.filename as img, r.latitude as latitude, r.longitude as longitude , r.hotelName as name , hotel_id FROM `discover_hotels` as r INNER JOIN discover_hotels_imges as i on i.hotel_id=r.id WHERE hotel_id = :Item_id ORDER BY i.default_pic DESC LIMIT 1";
            $params4[] = array(  "key" => ":Item_id",
                                 "value" =>$item_id);
            $select = $dbConn->prepare($query_d);
            PDO_BIND_PARAM($select,$params4);
            $ret_res    = $select->execute();
//            $ret_res = db_query($query_d);
//            $media_item = db_fetch_array($ret_res);
            $media_bag_itemlect->fetch();
            $page_link = ReturnLink('thotel/id/' . $media_item['hotel_id']);
        } else if ($media_bag_item['type'] == SOCIAL_ENTITY_RESTAURANT) {
//            $query_d = "SELECT r.*, i.id as i_id , i.filename as img, r.latitude as latitude, r.longitude as longitude , r.name as name , restaurant_id FROM `discover_restaurants` as r INNER JOIN discover_restaurants_images as i on i.restaurant_id=r.id WHERE restaurant_id = $item_id ORDER BY i.default_pic DESC LIMIT 1";     
            $params5 = array();       
            $query_d = "SELECT r.*, i.id as i_id , i.filename as img, r.latitude as latitude, r.longitude as longitude , r.name as name , restaurant_id FROM `discover_restaurants` as r INNER JOIN discover_restaurants_images as i on i.restaurant_id=r.id WHERE restaurant_id = :Item_id ORDER BY i.default_pic DESC LIMIT 1";            
            $params5[] = array(  "key" => ":Item_id",
                                 "value" =>$item_id);
            $select = $dbConn->prepare($query_d);
            PDO_BIND_PARAM($select,$params5);
            $ret_res    = $select->execute();
//            $ret_res = db_query($query_d);
//            $media_item = db_fetch_array($ret_res);
            $media_item = $select->fetch();
            $media_item['price'] = rand(30 * $stars, 50 + (30 * ($stars - 1)));
            $media_item['address'] = '';
            $page_link = ReturnLink('trestaurant/id/' . $media_item['restaurant_id']);
        } else if ($media_bag_item['type'] == SOCIAL_ENTITY_LANDMARK) {
            $params6 = array();
            $query_d = "SELECT r.*, i.id as i_id , i.filename as img, r.latitude as latitude, r.longitude as longitude, r.name as name , poi_id FROM `discover_poi` as r INNER JOIN discover_poi_images as i on i.poi_id=r.id WHERE poi_id = :Item_id ORDER BY i.default_pic DESC LIMIT 1";
            $params6[] = array(  "key" => ":Item_id",
                                 "value" =>$item_id);
            $select = $dbConn->prepare($query_d);
            PDO_BIND_PARAM($select,$params6);
            $ret_res    = $select->execute();

//            $ret_res = db_query($query_d);
//            $media_item = db_fetch_array($ret_res);
            $media_item = $select->fetch();
            $media_item['price'] = '';
            $media_item['address'] = '';
            $page_link = ReturnLink('things2do/id/' . $media_item['poi_id']);
        } else {
            continue;
        }
        $image_pic = $link . 'thumb/' . $media_item['img'];
        $stars = intval($media_item['stars']);
        if ($stars == 0 && $media_bag_item['type'] == SOCIAL_ENTITY_HOTEL) {
            $stars = 1;
        } else if ($media_bag_item['type'] != SOCIAL_ENTITY_HOTEL) {
            $stars = 0;
        }
        $lon_val = $media_item['longitude'] . '/*/' . $media_item['latitude'] . '/*/' . $media_bag_item['type'] . '/*/' . addslashes($media_item['name']) . '/*/' . $page_link . '/*/' . $image_pic . '/*/' . $stars;
        array_push($bagCoordinates, $lon_val);

        $str .='<div class="stationContainer">';
        if ($page_link != ''): $str .='<a href="' . $page_link . '" target="_blank">';
        endif;
        $str .='<div class="stationImgCon">
                        <img src="' . $image_pic . '" alt="" class="stationImg" />
                    </div>
                    <div class="stationTitle">' . $media_item['name'] . '</div>';
        if ($page_link != ''): $str .='</a>';
        endif;
        if ($media_bag_item['type'] == SOCIAL_ENTITY_HOTEL)
            $str .='<div class="stationStars"><img src="' . ReturnLink('images/album-rating' . $stars . '.png') . '" alt="" class="hotelBigImgstars" /></div>';
        if ($media_item['address'] != ''):
            $str .='<div class="stationKeywords">' . $media_item['address'] . '</div>';
        endif;
        if ($media_item['price'] != ''):
            $str .='<div class="stationKeywords">'._("Average price").': ' . $media_item['price'] . '' . $currency . '</div>';
        endif;
        $str .='<div class="stationSep"></div>
            </div>';
    }
    $str .='</div>';

    $str .='<div class="ContainermapPartTuber" index="8">';
    $options = array(
        'limit' => 8,
        'page' => 0,
        'public' => 1,
        'orderby' => 'rand',
        'order' => 'desc',
        'profile_pic' => true
    );
    $tubers = userSearch($options);
    foreach ($tubers as $tuber) {
        $options = array(
            'type' => array(1),
            'userid' => $tuber['id'],
            'n_results' => true
        );
        $friend_count = userFriendSearch($options);
        $options = array(
            'userid' => $tuber['id'],
            'n_results' => true
        );
        $followers_count = userSubscriberSearch($options);
        if (strstr($tuber['profile_Pic'], 'tuber.jpg') != null)
            continue;
        $str .='<div class="stationContainer">';
        $str .='<a href="' . userProfileLink($tuber) . '" target="_blank">';
        $str .='<div class="stationImgCon">
                            <img src="' . ReturnLink('media/tubers/' . $tuber['profile_Pic']) . '" alt="" class="stationImg" />
                    </div>
                    <div class="stationTitle">' . returnUserDisplayName($tuber) . '</div>
                    <div class="stationKeywords marginTop4">' . $friend_count . ' '._("friends").'</div>
                    <div class="stationKeywords">' . $followers_count . ' '._("followers").'</div>';
        $str .='</a>';
        $str .='<div class="stationSep"></div>
            </div>';
    }
    $str .='</div>';
}
$title = '';
$flightPlanCoordinates_str = implode('[*]', $flightPlanCoordinates);
$bagCoordinates_str = implode('[*]', $bagCoordinates);
$str .='</div>
                                <div class="plannerMapRight_label">' . $string_search_value . ' - '._("DAY").' ' . $displayDay . '</div>
                                <div class="plannerMapTrackInfo"></div>
            <div class="plannerMapRight">
                <script type="text/javascript">
                    $(document).ready(function(e) {';
$str .='setMapSearch( ' . $latitude . ' , ' . $longitude . ' , "' . $title . '" , "' . $flightPlanCoordinates_str . '" , "' . $displayDay . '" , "' . $bagCoordinates_str . '" );
                                                });
                </script>
                <div id="googlemap" class="googlemap"></div>
            </div>
        </div>';

$Result['data'] = $str;
$Result['departdate_str'] = $departdate_str;
$Result['all_bag_count'] = $all_bag_count;
$Result['data_country'] = $data_country;
$Result['data_state'] = $data_state;
$Result['city_id'] = $city_id;
$Result['query'] = $query_restaurants;
echo json_encode($Result);