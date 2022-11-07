<?php
//ini_set('display_errors', 1);
$expath = "../";
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());

$limit = $submit_post_get['limit'] ? intval($submit_post_get['limit']) : 200;
$hotel_id = intval($submit_post_get['hotel_id']);
$search_id = intval($submit_post_get['search_id']);
$lang ='en';
if (isset($submit_post_get['lang'])) {
    $lang = setLangGetText($submit_post_get['lang'], true) ? $submit_post_get['lang'] : 'en';
}
if($limit > 300){
    $limit = 300;
}

$restaurants_arr = array();
$hotels_arr = array();
$pois_arr = array();
$remaining = 0;

$row = getHotelHRSInfo($hotel_id,$lang);
if( $row && sizeof($row)>0 ){
    $row['img'] = '/media/images/hotel-icon-image2.jpg';
    $def_array = getHotelImagesP(intval($hotel_id));
    if($def_array && sizeof($def_array)>0){
        if ( !empty($def_array['hotel_id'])) {
            $row['img'] = '/media/hotels/' . $def_array['hotel_id'] . '/' . $def_array['location'] . '/' . $def_array['filename'];
        }
    }
    $name = $row["name"];
    if( isset($row["ml_name"]) && $row["ml_name"]!=''){
        $name = $row["ml_name"];
    }
    $hotels_arr[] = array(
        "id" => $hotel_id,
        "name" => $name,
        "type" => "hotel",
        "stars" => $row["stars"],
        "latitude" => $row["latitude"],
        "longitude" => $row["longitude"],
        "zipcode" => isset($row["zip_code"]) ? $row["zip_code"] : "",
        "address" => $row['address'],
        "street" => $row['street'],
        "phone" => "",
        "price" => "",
        "image" => $row['img']
    );
}
if($search_id && $search_id!=0){
    $hotel_search_request_list = getHotelSearchRequestById($search_id,$limit);
    foreach ($hotel_search_request_list as $items){
        if($items['hotel_id']!=intval($hotel_id)){
            $row = getHotelHRSInfo($items['hotel_id'],$lang);
            $row['img'] = '/media/images/hotel-icon-image2.jpg';
            $def_array = getHotelImagesP($items['hotel_id']);
            if($def_array && sizeof($def_array)>0){
                if ( !empty($def_array['hotel_id'])) {
                    $row['img'] = '/media/hotels/' . $def_array['hotel_id'] . '/' . $def_array['location'] . '/' . $def_array['filename'];
                }
            }
            $name = $row["name"];
            if( isset($row["ml_name"]) && $row["ml_name"]!=''){
                $name = $row["ml_name"];
            }
            $hotels_arr[] = array(
                "id" => $items['hotel_id'],
                "name" => $name,
                "type" => "hotel",
                "stars" => $row["stars"],
                "latitude" => $row["latitude"],
                "longitude" => $row["longitude"],
                "zipcode" => isset($row["zip_code"]) ? $row["zip_code"] : "",
                "address" => $row['address'],
                "street" => $row['street'],
                "phone" => "",
                "image" => $row['img'],
                "price" => $items['price'] .' '.$items['iso_currency']
            );
        }else{
            $hotels_arr[0]['price'] = $items['price'] .' '.$items['iso_currency'];
        }
    }
}
echo json_encode(array('hotels' => $hotels_arr));