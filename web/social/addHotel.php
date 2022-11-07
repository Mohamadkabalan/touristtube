<?php
$path = "";
$bootOptions = array("loadDb" => 1, "loadLocation" => 1, "requireLogin" => 0);
//exit('exiting');
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/twigFct.php" );

include_once($path . "inc/functions/formkey.class.php");
$formKey = new formKey();
if( strtolower($_SERVER['REQUEST_METHOD']) == 'post'){
    if(!isset($_POST['form_key']) || !$formKey->validate($_POST['form_key'])){
        header('Location:' . ReturnLink('/') );
        exit;
    }
}
$theLink = $CONFIG ['server']['root'];
//require_once $theLink . 'vendor/autoload.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($theLink . 'twig_templates/');
$twig = new Twig_Environment($loader, array(
    'cache' => $theLink . 'twig_cache/','debug' => true,
));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('addHotel.twig');


$includes = array('css/addHotel.css','js/addHotel.js',
    'media'=>'css_media_query/media_style.css?v='.MQ_MEDIA_STYLE_CSS_V,
    'media1'=>'css_media_query/addHotel.css?v='.MQ_ADDHOTEL_CSS_V);
	
function getCountryList(){
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <start>
    global $dbConn;
    $rs='';
    $Query          = "select `code`,`name`,`full_name` from cms_countries";
    $select = $dbConn->prepare($Query);
    $Result    = $select->execute();
//    $Result  = db_query($Query);
    $row = $select->fetchAll();
//    while($row = db_fetch_array($Result)){
    foreach($row as $row_item){
        $rs[]  =   array('code'=> $row_item['code'],'name'=>$row_item['name'],'full_name'=>$row_item['full_name']);
    }
    return $rs;
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <end>
}
function getPropertyList(){
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <start>
    global $dbConn;
    $rs='';
    $Query  = "select id property_id,title property_name from discover_hotels_type";
    $select = $dbConn->prepare($Query);
    $Result = $select->execute();
//    $Result = db_query($Query);
    $row = $select->fetchAll();
//    while($row = db_fetch_array($Result)){
    foreach($row as $row_item){
        $rs[] = array('property_id'=>$row_item['property_id'],'property_name'=>$row_item['property_name']);
    }
    return $rs;
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <end>
}
function getFeaturesType(){
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <start>
    global $dbConn;
    $rs='';
    $Query = "Select `id`,`title` from `discover_hotels_feature_type`";
    $select = $dbConn->prepare($Query);
    $Result = $select->execute();
//    $Result = db_query($Query);
    $row = $select->fetchAll();
//    while($row = db_fetch_array($Result)){
    foreach($row as $row_item){
        $rs[$row_item['id']] = array('type_id'=>$row_item['id'],'type_name'=>$row_item['title']);
    }
    return $rs;
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <end>
}

function getFeatures($FeatureType){
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <start>
    global $dbConn;
    $rs='';
    $Query = "Select `id`,`title`,`feature_type` from `discover_hotels_feature`";
    $select = $dbConn->prepare($Query);
    $Result = $select->execute();

    $ret    = $select->rowCount();
//    $Result = db_query($Query);
    $row = $select->fetchAll();
//    while($row=db_fetch_array($Result)){
    foreach($row as $row_item){
        $featureName = $FeatureType[$row_item['feature_type']]['type_name'];
        $featureId = $FeatureType[$row_item['feature_type']]['type_id'];
        if($featureId==$row_item['feature_type']){
            $rs[$featureName][] = array('feature_title'=>$row_item['title'],'feature_id'=>$row_item['id']);
        }
    }
    return $rs;
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <end>
}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function fileExists($file){
//
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>

function uploadRoomImages($imageArray,$title){

    $roomImageArr   =   $imageArray;
    $roomCount =   count($imageArray['name']);
    $count=0;

    foreach($imageArray['name'] as $key=>$val){
        if(!empty($val))
            $count++;
    }
    $roomCount = $count;

    $cleanTitle     =   cleanTitle($title);
    $type           =   'hotel';
    $time           =   str_replace('.','',microtime(true));

    $newImageName   =   array();
    $targetDirectory = 'media/discover';
    $status = 1;

    for($i=0;$i<$roomCount;$i++){
        $picOriginalName = $targetDirectory.'/'.basename($roomImageArr['name'][$i]);

        $imageFileType = pathinfo($picOriginalName,PATHINFO_EXTENSION);
        $newName = $cleanTitle.'_'.$type.'_'.$time.'.'.$imageFileType;
        $pic = $targetDirectory.'/'.$newName;

        // Check file size
        if ($roomImageArr["size"][$i] > 1000000) {
            $status = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            $status = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($status == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($roomImageArr["tmp_name"][$i], $pic)) {
                echo "The file ". basename( $roomImageArr['name'][$i]). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
        $time++;
        $newImageName[] =   $newName;
    }
    return $newImageName;
}

$FeatureType    =   getFeaturesType();
$Features       =   getFeatures($FeatureType);
$CountryList    =   getCountryList();
$propertyList   =   getPropertyList();
$StarList       =   array('1 Star','2 Star','3 Star','4 Star','5 Star');
$successMessage =   '';

//if(isset($_POST['submit'])){
$submit_get = $request->request->get('submit', '');
if($submit_get){
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <start>
    global $dbConn;
    $params  = array();  
    $params2 = array();  
    $params3 = array(); 

    $hotelName          =   $request->request->get('names', '');
    $hotel_slug         =   cleanTitle($hotelName);
    $propertyType       =   $request->request->get('property', '');
    $country            =   $request->request->get('country', '');
    $city               =   $request->request->get('city', '');
    $city_id               =   $request->request->get('cityid', '');
    $stars              =   $request->request->get('stars', '');
    $address            =   $request->request->get('address', '');
    $price              =   $request->request->get('fname', '');
    $check_in           =   $request->request->get('check', '');
    $checkout           =   $request->request->get('checkout', '');
    $email              =   $request->request->get('email', '');
    $url                =   $request->request->get('url', '');
    $phone              =   intval($request->request->get('phone', ''));
    $hotelDescription   =   $request->request->get('description', '');
    $location           =   $request->request->get('input', '');
    $latitude           =   $request->request->get('latitude', '');
    $longitude          =   $request->request->get('longitude', '');
    $features           =   $request->request->get('features', '');
    $room_type          =   $request->request->get('rm_type', '');
    $room_desc          =   $request->request->get('rm_description', '');
    $room_nop           =   $request->request->get('rm_nop', '');
    $room_price         =   $request->request->get('rm_price', '');
    
    $roomPic1Arr        =   $_FILES['picture1'];
    $roomPic2Arr        =   $_FILES['picture2'];
    $roomPic3Arr        =   $_FILES['picture3'];
    
//    $Query  =   "INSERT INTO `discover_hotels` (`id`, `title`, `hotelName`, `slug`, `country_sub_id`, `address`, `address_short`, `location`, `email`, `url`, `phone`, `fax`, `longitude`, `latitude`, `stars`, `star_self_rated`, `rooms`, `local_currency_code`, `price`, `price_from`, `check_in`, `check_out`, `propertyType`, `chain_id`, `rating`, `rating_overall_text`, `rating_cleanliness`, `rating_dining`, `rating_facilities`, `rating_location`, `rating_rooms`, `rating_service`, `rating_points`, `reviews_count`, `reviews_summary_positive`, `reviews_summary_negative`, `description`, `FAQ`, `images_count`, `last_modified`, `about`, `general_facilities`, `services`, `zoom_order`, `city_id`, `cityName`, `countryCode`, `stateName`, `map_image`,`published`) ";
//    $Query  .=   "VALUES (NULL, '$hotelName', '$hotelName', '$hotel_slug', '$country', '$address', '$address', '$location', '$email', '$url', '$phone','NA','$longitude','$latitude', '$stars', '0', '1', '$', '$price', '0', '$check_in', '$checkout', '$propertyType', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 'NA', 'NA', 'NA', '$hotelDescription', 'NA', '3', CURRENT_TIMESTAMP, 'NA', 'NA', 'NA', '0', '$city_id', '$city', '$country', '$city', 'NA','-2')";

    $Query  =   "INSERT INTO `discover_hotels` (`title`, `hotelName`, `slug`, `country_sub_id`, `address`, `address_short`, `location`, `email`, `url`, `phone`, `fax`, `longitude`, `latitude`, `stars`, `star_self_rated`, `rooms`, `local_currency_code`, `price`, `price_from`, `check_in`, `check_out`, `propertyType`, `chain_id`, `rating`, `rating_overall_text`, `rating_cleanliness`, `rating_dining`, `rating_facilities`, `rating_location`, `rating_rooms`, `rating_service`, `rating_points`, `reviews_count`, `reviews_summary_positive`, `reviews_summary_negative`, `description`, `FAQ`, `images_count`, `last_modified`, `about`, `general_facilities`, `services`, `zoom_order`, `city_id`, `cityName`, `countryCode`, `stateName`, `map_image`,`published`) ";
    $Query  .=   "VALUES ( :HotelName, :HotelName2, :Hotel_slug, :Country, :Address, :Address2, :Location, :Email, :Url, :Phone,'',:Longitude,:Latitude, :Stars, '0', '1', '$', :Price, '0', :Check_in, :Checkout, :PropertyType, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '', '', '', :HotelDescription, '', '3', CURRENT_TIMESTAMP, '', '', '', '0', :City_id, :City, :Country, '', '','-1')";

    $params[] = array(  "key" => ":HotelName",
                        "value" =>$hotelName);
    $params[] = array(  "key" => ":HotelName2",
                        "value" =>$hotelName);
    $params[] = array(  "key" => ":Hotel_slug",
                        "value" =>$hotel_slug);
    $params[] = array(  "key" => ":Country",
                        "value" =>$country);
    $params[] = array(  "key" => ":Address",
                        "value" =>$address);
    $params[] = array(  "key" => ":Address2",
                        "value" =>$address);
    $params[] = array(  "key" => ":Location",
                        "value" =>$location);
    $params[] = array(  "key" => ":Email",
                        "value" =>$email);
    $params[] = array(  "key" => ":Url",
                        "value" =>$url);
    $params[] = array(  "key" => ":Phone",
                        "value" =>$phone);
    $params[] = array(  "key" => ":Longitude",
                        "value" =>$longitude);
    $params[] = array(  "key" => ":Latitude",
                        "value" =>$latitude);
    $params[] = array(  "key" => ":Stars",
                        "value" =>$stars);
    $params[] = array(  "key" => ":Price",
                        "value" =>$price);
    $params[] = array(  "key" => ":Check_in",
                        "value" =>$check_in);
    $params[] = array(  "key" => ":Checkout",
                        "value" =>$checkout);
    $params[] = array(  "key" => ":PropertyType",
                        "value" =>$propertyType);
    $params[] = array(  "key" => ":HotelDescription",
                        "value" =>$hotelDescription);
    $params[] = array(  "key" => ":City_id",
                        "value" =>$city_id);
    $params[] = array(  "key" => ":City",
                        "value" =>$city);
    $params[] = array(  "key" => ":Country",
                        "value" =>$country);
    /* ....$status is set to 1 if db query is success else set to 0... */
    $select = $dbConn->prepare($Query);
    PDO_BIND_PARAM($select,$params);
    $rs    = $select->execute();
//    if($rs =db_query($Query)){
    if($rs){
//        $lastInsertedHotelId = db_insert_id($rs);
        $lastInsertedHotelId = $dbConn->lastInsertId();
        if(isset($_POST['features'])){
            $Query  =   "INSERT INTO `discover_hotels_feature_to_hotel` (`id` ,`hotel_id` ,`hotel_feature_id`) VALUES ";
            foreach($features as $key=>$feature_id){
                $Query.=   "(NULL, :LastInsertedHotelId".$key.", :Feature_id".$key.")";
                $params2[] = array(  "key" => ":LastInsertedHotelId".$key,
                                     "value" =>$lastInsertedHotelId);
                $params2[] = array(  "key" => ":Feature_id".$key,
                                     "value" =>$feature_id);
                if($key+1<count($features)):
                    $Query.=' , ';
                endif;
            }
            $insert = $dbConn->prepare($Query);
            PDO_BIND_PARAM($insert,$params2);
            $rs    = $insert->execute();
        }

        /* uploading images to the server if any and taking the modified image name array in return */
        $newPicName1Arr     =   uploadRoomImages($roomPic1Arr,$hotelName); // uploading room images1
        $newPicName2Arr     =   uploadRoomImages($roomPic2Arr,$hotelName); // uploading room images2
        $newPicName3Arr     =   uploadRoomImages($roomPic3Arr,$hotelName); // uploading room images3
        /* ..uploading images till here...*/


        /* inserting room details in discover_hotels_rooms..*/
        $Query  =   "INSERT INTO `discover_hotels_rooms` (`id` ,`hotel_id` ,`title` ,`description` ,`num_person` ,`price` ,`pic1` ,`pic2` ,`pic3`) values ";
        foreach($room_type as $key=>$value){
            $pic1 = isset($newPicName1Arr[$key])?$newPicName1Arr[$key]:'';
            $pic2 = isset($newPicName2Arr[$key])?$newPicName2Arr[$key]:'';
            $pic3 = isset($newPicName3Arr[$key])?$newPicName3Arr[$key]:'';
//            $Query.=    "(NULL , '$lastInsertedHotelId', '$value', '$room_desc[$key]', '$room_nop[$key]', '$room_price[$key]', '$pic1', '$pic2', '$pic3')";
            $Query.=    "(NULL , :LastInsertedHotelId".$key.", :Value".$key.", :Room_desc".$key.", :Room_nop".$key.", :Room_price".$key.", :Pic1".$key.", :Pic2".$key.", :Pic3".$key.")";
            $params3[] = array(  "key" => ":LastInsertedHotelId".$key,
                                 "value" =>$lastInsertedHotelId);
            $params3[] = array(  "key" => ":Value".$key,
                                 "value" =>$value);
            $params3[] = array(  "key" => ":Room_desc".$key,
                                 "value" =>$room_desc[$key]);
            $params3[] = array(  "key" => ":Room_nop".$key,
                                 "value" =>$room_nop[$key]);
            $params3[] = array(  "key" => ":Room_price".$key,
                                 "value" =>$room_price[$key]);
            $params3[] = array(  "key" => ":Pic1".$key,
                                 "value" =>$pic1);
            $params3[] = array(  "key" => ":Pic2".$key,
                                 "value" =>$pic2);
            $params3[] = array(  "key" => ":Pic3".$key,
                                 "value" =>$pic3);
            if($key+1<count($room_type)):
                $Query.=',';
            endif;
        }
        $insert2 = $dbConn->prepare($Query);
        PDO_BIND_PARAM($insert2,$params3);
        $rs     = $insert2->execute();
//        $rs = db_query($Query);
        $status =   1;
    }
    else{
        $status=0;
    }
    if($status==1)
        $successMessage = 'We have added Your desired Hotel, we will update you once reviewed!';
    else
        $successMessage = 'There is a problem in adding your hotel, please try again later.!';
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <end>

}
$data['successMessage'] = $successMessage;
$data['userIsLogged']   = userIsLogged();
$data['userIsChannel']  = userIsChannel();
$data['hide_view_all']  = '0';

$data['Features']       =   $Features;
$data['CountryList']    =   $CountryList;
$data['StarList']       =   $StarList;
$data['PropertyList']   =   $propertyList;
$data['outputKey']   =   $formKey->outputKey();


if (userIsLogged() && userIsChannel()) {
    array_unshift($includes, 'css/channel-header.css');
    tt_global_set('includes', $includes);
    include($theLink . "twig_parts/_headChannel.php");
}else {
    tt_global_set('includes', $includes);
    include($theLink . "twig_parts/_head.php");
}
include($theLink . "twig_parts/_foot.php");
echo $template->render($data);