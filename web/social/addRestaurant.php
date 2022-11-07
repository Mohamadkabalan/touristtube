<?php
$path = "";

$bootOptions = array("loadDb" => 1, "loadLocation" => 1, "requireLogin" => 0);
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
    'cache' => $theLink . 'twig_cache/', 'debug' => false,
));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('addRestaurant.twig');

$includes = array('css/addRestaurant.css','js/addRestaurant.js',
    'media'=>'css_media_query/media_style.css?v='.MQ_MEDIA_STYLE_CSS_V,
    'media1'=>'css_media_query/addRestaurant.css?v='.MQ_ADDRESTAURANT_CSS_V);

function getCountryList(){
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <start>
	global $dbConn;
    $rs='';
    $Query          = "select `code`,`name`,`full_name` from cms_countries";
//    $Result  = db_query($Query);
    $select = $dbConn->prepare($Query);
    $res    = $select->execute();
    $row = $select->fetchAll();
//    while($row = db_fetch_array($Result)){
    foreach($row as $row_item){
        $rs[]  =   array('code'=> $row_item['code'],'name'=>$row_item['name'],'full_name'=>$row_item['full_name']);
    }
    return $rs;
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <end>
}
function getCuisineList(){
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <start>
	global $dbConn;
    $rs='';
    $Query  = "select id,title from discover_cuisine";
//    $Result = db_query($Query);
    $select = $dbConn->prepare($Query);
    $res    = $select->execute();
    $row = $select->fetchAll();
//    while($row = db_fetch_array($Result)){
    foreach($row as $row_item){
        $rs[] = array('id'=>$row_item['id'],'title'=>$row_item['title']);
    }
    return $rs;
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <end>
}

$CountryList    =   getCountryList();
$cuisineList   =   getCuisineList();
$StarList       =   array('1 Star','2 Star','3 Star','4 Star','5 Star');


$request_type = $request->getMethod();
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//if(isset($_REQUEST['submit'])){
if($submit_post_get['submit']){
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <start>
	global $dbConn;
	$params  = array(); 
	$params2 = array(); 

//    $restaurantName         =   $_POST['names_rest'];
    $restaurantName         =   $request->request->get('names_rest', '');
//    $cuisineArray           =   $_POST['cuisine'];
    $cuisineArray           =   $request->request->get('cuisine', '');
//    $country                =   $_POST['country'];
    $country                =   $request->request->get('country', '');
    $city                   =   $request->request->get('city', '');
    $city_id               =   $request->request->get('cityid', '');
//    $stars                  =   $_POST['stars'];
    $stars                  =   $request->request->get('stars', '');
//    $restaurantAddress      =   $_POST['address_rest'];
    $restaurantAddress      =   $request->request->get('address_rest', '');
//    $price                  =   $_POST['price_rest'];
    $price                  =   $request->request->get('price_rest', '');
//    $ophr                   =   $_POST['op-hr'];
    $ophr                   =   $request->request->get('op-hr', '');
//    $clhr                   =   $_POST['cl-hr'];
    $clhr                   =   $request->request->get('cl-hr', '');

    $hours                  =   $ophr.'-'.$clhr;
//    $email                  =   $_POST['email_rest'];
    $email                  =   $request->request->get('email_rest', '');
//    $restaurantUrl          =   $_POST['url_rest'];
    $restaurantUrl          =   $request->request->get('url_rest', '');
//    $phone                  =   intval($_POST['phone_rest']);
    $phone                  =   intval($request->request->get('phone_rest', ''));
//    $restaurantDescription  =   $_POST['description_rest'];
    $restaurantDescription  =   $request->request->get('description_rest', '');
//    $restaurantFacility     =   $_POST['facilities_rest'];
    $restaurantFacility     =   $request->request->get('facilities_rest', '');

//    $latitude               =   $_POST['latitude'];
    $latitude               =   $request->request->get('latitude', '');
//    $longitude              =   $_POST['longitude'];
    $longitude              =   $request->request->get('longitude', '');


    $Query = "INSERT INTO `global_restaurants` (`name`          ,`longitude` ,`latitude` ,`country` ,`locality` ,`address`           ,`zoom_order` ,`city_id` ,`tel` ,`fax` ,`email` ,`website`      ,`published`,`avg_price`,`from_source`,`factual_id`)";
                             $Query .=" VALUES (:RestaurantName ,:Longitude  ,:Latitude  ,:Country  ,:City      ,:RestaurantAddress  , '0'         ,:City_id  ,:Phone, ''   ,:Email  ,:RestaurantUrl ,-1         , :Avg_price,'new'        ,'0')";

    $params[] = array(  "key" => ":RestaurantName",
                        "value" =>$restaurantName);
    $params[] = array(  "key" => ":Longitude",
                        "value" =>$longitude);
    $params[] = array(  "key" => ":Latitude",
                        "value" =>$latitude);
//    $params[] = array(  "key" => ":Stars",
//                        "value" =>$stars);
    $params[] = array(  "key" => ":Country",
                        "value" =>$country);
    $params[] = array(  "key" => ":City",
                        "value" =>$city);
    $params[] = array(  "key" => ":RestaurantAddress",
                        "value" =>$restaurantAddress);
//    $params[] = array(  "key" => ":RestaurantDescription",
//                        "value" =>$restaurantDescription);
//    $params[] = array(  "key" => ":RestaurantFacility",
//                        "value" =>$restaurantFacility);
    $params[] = array(  "key" => ":City_id",
                        "value" =>$city_id);
    $params[] = array(  "key" => ":Phone",
                        "value" =>$phone);
    $params[] = array(  "key" => ":Email",
                        "value" =>$email);
    $params[] = array(  "key" => ":RestaurantUrl",
                        "value" =>$restaurantUrl);
    $params[] = array(  "key" => ":Avg_price",
                        "value" =>$price);
//    $params[] = array(  "key" => ":Hours",
//                        "value" =>$hours);
    
    $insert = $dbConn->prepare($Query);
    PDO_BIND_PARAM($insert,$params);
    $rs    = $insert->execute();
//    if($rs = db_query($Query)){
    if($rs){
        $lastInsertedId = $dbConn->lastInsertId();
//        $lastInsertedId = db_insert_id($rs);
        if(!empty($cuisineArray)){
            $Query  = "INSERT INTO `discover_restaurants_cuisine` (`cuisine_id` ,`restaurant_id`)VALUES";
            foreach($cuisineArray as $key=>$cuisineId){
//                $Query.=   "('$cuisineId','$lastInsertedId')";
                $Query.=   "( :CuisineId".$key.",:LastInsertedId".$key.")";
                $params2[] = array(  "key" => ":CuisineId".$key,
                                     "value" =>$cuisineId);
                $params2[] = array(  "key" => ":LastInsertedId".$key,
                                     "value" =>$lastInsertedId);
                if($key+1<count($cuisineArray)):
                    $Query.=' , ';
                endif;
            }
//            $rs = db_query($Query);
            $insert2 = $dbConn->prepare($Query);
            PDO_BIND_PARAM($insert2,$params2);
            $rs    = $insert2->execute();
        }
        $status =1;
    }
    else
        $status=0;

    if($status==1){
        $successMessage='Thank you for adding your desired restaurant,We will update it once reviewed';
    }else
        $successMessage='There is some problem in adding your Point of interest, please try again later.';
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <end>
}


$data['successMessage'] = $successMessage;
$data['userIsLogged']   = userIsLogged();
$data['userIsChannel']  = userIsChannel();
$data['hide_view_all']  = '0';

//$data['Features']       =   $Features;
$data['CountryList']    =   $CountryList;
$data['StarList']       =   $StarList;
$data['CuisineList']   =   $cuisineList;
$data['outputKey']   =   $formKey->outputKey();

if (userIsLogged() && userIsChannel()) {
     array_unshift($includes, 'css/channel-header.css');
    tt_global_set('includes', $includes);
    include($theLink . "twig_parts/_headChannel.php");
} else {
    tt_global_set('includes', $includes);
    include($theLink . "twig_parts/_head.php");
}
include($theLink . "twig_parts/_foot.php");
echo $template->render($data);