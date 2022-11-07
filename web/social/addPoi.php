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
$template = $twig->loadTemplate('addPoi.twig');

$includes = array('css/addPoi.css','js/addPoi.js',
    'media'=>'css_media_query/media_style.css?v='.MQ_MEDIA_STYLE_CSS_V,
    'media1'=>'css_media_query/addPoi.css?v='.MQ_ADDPOI_CSS_V);

function getCountryList(){
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <start>
    global $dbConn;
    $rs='';
    $Query          = "select `code`,`name`,`full_name` from cms_countries";
//    $Result  = db_query($Query);
    $select = $dbConn->prepare($Query);
    $Result    = $select->execute();
    $row = $select->fetchAll();
//    while($row = db_fetch_array($Result)){
    foreach($row as $row_item){
        $rs[]  =   array('code'=> $row_item['code'],'name'=>$row_item['name'],'full_name'=>$row_item['full_name']);
    }
    return $rs;
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <end>
}
function getCategoryList(){
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <start>
    global $dbConn;
    $rs='';
    $Query  = "select id,title from discover_categs ";
//    $Result = db_query($Query);
    $select = $dbConn->prepare($Query);
    $Result    = $select->execute();
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
$CategoryList   =   getCategoryList();

$successMessage        =   '';

if(isset($_POST['submit'])){
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <start>
    global $dbConn;
    $params  = array();  
    $params2 = array();  

//    $poiName                =   $_POST['names_poi'];
    $poiName                =   $request->request->get('names_poi', '');
//    $categoryArray          =   $_POST['cat'];
    $categoryArray          =   $request->request->get('cat', '');
//    $country                =   $_POST['country'];
    $country                =   $request->request->get('country', '');
//    $city                   =   $_POST['city_poi'];
    $city                   =   $request->request->get('city', '');
    $city_id               =   $request->request->get('cityid', '');
    $stars                  =   '';
//    $poiAddress             =   $_POST['address_poi'];
    $poiAddress             =   $request->request->get('address_poi', '');
//    $price                  =   $_POST['price_poi'];
    $price                  =   $request->request->get('price_poi', '');
//    $check_in               =   $_POST['op-hr'];
    $check_in               =   $request->request->get('op-hr', '');
//    $checkout               =   $_POST['cl-hr'];
    $checkout               =   $request->request->get('cl-hr', '');
//    $email                  =   $_POST['email_poi'];
    $email                  =   $request->request->get('email_poi', '');
//    $poiUrl                 =   $_POST['url_poi'];
    $poiUrl                 =   $request->request->get('url_poi', '');
//    $phone                  =   $_POST['phone_poi'];
    $phone                  =   $request->request->get('phone_poi', '');
//    $poiDescription         =   $_POST['description_poi'];
    $poiDescription         =   $request->request->get('description_poi', '');

//    $latitude               =   $_POST['latitude'];
    $latitude               =   $request->request->get('latitude', '');
//    $longitude              =   $_POST['longitude'];
    $longitude              =   $request->request->get('longitude', '');

//    $Query = "INSERT INTO `discover_poi` (`id`, `longitude`, `latitude`, `name`, `stars`, `country`, `city`, `zoom_order`, `show_on_map`, `cat`, `sub_cat`, `map_image`, `city_id`, `zipcode`, `phone`, `fax`, `email`, `website`, `price`, `description`,`published`,`last_modified`,`address`) VALUES";
//    $Query .= "(NULL, '$longitude', '$latitude', '$poiName', '$stars', '$country', '$city', '', '', '', '', '', '$city_id', '', '$phone', '', '$email', '$poiUrl', '$price','$poiDescription','-2','','$poiAddress')";
    $Query = "INSERT INTO `discover_poi` (`id`, `longitude`, `latitude`, `name`, `stars`, `country`, `city`, `zoom_order`, `show_on_map`, `cat`, `sub_cat`, `map_image`, `city_id`, `zipcode`, `phone`, `fax`, `email`, `website`, `price`, `description`,`published`,`last_modified`,`address`) VALUES";
    $Query .= "(NULL, :Longitude, :Latitude, :PoiName, :Stars, :Country, :City, '', '', '', '', '', :City_id, '', :Phone, '', :Email, :PoiUrl, :Price, :PoiDescription,'-1','',:PoiAddress)";

    $params[] = array(  "key" => ":Longitude",
                        "value" =>$longitude);
    $params[] = array(  "key" => ":Latitude",
                        "value" =>$latitude);
    $params[] = array(  "key" => ":PoiName",
                        "value" =>$poiName);
    $params[] = array(  "key" => ":Stars",
                        "value" =>$stars);
    $params[] = array(  "key" => ":Country",
                        "value" =>$country);
    $params[] = array(  "key" => ":City",
                        "value" =>$city);
    $params[] = array(  "key" => ":City_id",
                        "value" =>$city_id);
    $params[] = array(  "key" => ":Phone",
                        "value" =>$phone);
    $params[] = array(  "key" => ":Email",
                        "value" =>$email);
    $params[] = array(  "key" => ":PoiUrl",
                        "value" =>$poiUrl);
    $params[] = array(  "key" => ":Price",
                        "value" =>$price);
    $params[] = array(  "key" => ":PoiDescription",
                        "value" =>$poiDescription);
    $params[] = array(  "key" => ":PoiAddress",
                        "value" =>$poiAddress);
    
    $select = $dbConn->prepare($Query);
    PDO_BIND_PARAM($select,$params);
    $rs    = $select->execute();
//    if($rs=db_query($Query)){
    if($rs){
//        $lastInsertedId = db_insert_id($rs);
        $lastInsertedId = $dbConn->lastInsertId();
        if(!empty($categoryArray)){
            $Query  = "INSERT INTO `discover_poi_categ` (`categ_id` ,`poi_id`) VALUES ";
            foreach($categoryArray as $key=>$categId){
//                $Query.=   "('$categId','$lastInsertedId')";
                $Query.=   "(:CategId".$key.",:LastInsertedId".$key.")";
                $params2[] = array(  "key" => ":CategId".$key,
                                     "value" =>$categId);
                $params2[] = array(  "key" => ":LastInsertedId".$key,
                                     "value" =>$lastInsertedId);
                if($key+1<count($categoryArray)):
                    $Query.=' , ';
                endif;
            }
//            $rs = db_query($Query);
            $insert = $dbConn->prepare($Query);
            PDO_BIND_PARAM($insert,$params2);
            $rs    = $insert->execute();
        }
        $status =1;
    }
    else{
        $status=0;
    }

    if($status==1){
        $successMessage = "Thanks for adding your Point of interest, we will add it to our system once reviewed.!!";
    }
    else{
        $successMessage = "There is some problem in adding your point of interest, please try again later.!!";
    }
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <end>
}
$data['successMessage'] = $successMessage;
$data['userIsLogged']   = userIsLogged();
$data['userIsChannel']  = userIsChannel();
$data['hide_view_all']  = '0';


$data['CountryList']    =   $CountryList;
$data['StarList']       =   $StarList;
$data['CategoryList']   =   $CategoryList;
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