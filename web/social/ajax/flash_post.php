<?php

$path = "../";

$bootOptions = array("loadDb" => 1, 'requireLogin' => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );

include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/flash.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/functions/locations.php" );

if (!userIsLogged()) {
    $ret['status'] = 'error';
    $ret['type'] = 'session';
    echo json_encode($ret);
    exit;
}

$user_id = userGetID();
//exit($flash_text);
//$filename = $_POST['filename'];
//$flash_text = (isset($_POST['FlashText'])) ? $_POST['FlashText'] : '';
//$flash_link = (isset($_POST['flash_link'])) ? $_POST['flash_link'] : '';
//$flash_location = (isset($_POST['flash_location'])) ? $_POST['flash_location'] : '';
//$vpath = $_POST['vpath'];
//$FlashLong = ($_POST['FlashLong'] != '') ? doubleval($_POST['FlashLong']) : null;
//$FlashLat = ($_POST['FlashLat'] != '') ? doubleval($_POST['FlashLat']) : null;
//$replyTo = ($_POST['ReplyTo'] != '') ? intval($_POST['ReplyTo']) : null;
//$rtype = intval($_POST['rtype']);
//$location_name = $_POST['label'];
$filename = $request->request->get('filename', '');
$flash_text = $request->request->get('FlashText', '');
$flash_link = $request->request->get('flash_link', '');
$flash_location = $request->request->get('flash_location', '');
$vpath = $request->request->get('vpath', '');
$FlashLongss = $request->request->get('FlashLong', '');
$FlashLong = ( $FlashLongss != '') ? doubleval($FlashLongss) : null;
$FlashLatss = $request->request->get('FlashLat', '');
$FlashLat = ( $FlashLatss != '') ? doubleval($FlashLatss) : null;
$ReplyToss = $request->request->get('ReplyTo', '');
$replyTo = ( $ReplyToss != '') ? intval($ReplyToss) : null;
$rtype = intval($request->request->get('rtype', 0));
$location_name = $request->request->get('label', '');


$ret = array();
if (strstr($vpath, '..') != null){
    $ret['status'] = 'error';
    $ret['type'] = 'save';
    echo json_encode($ret);
    die('');
}
if (!file_exists($CONFIG['server']['root'] . $vpath . $filename)){
    $ret['status'] = 'error';
    $ret['type'] = 'save';
    echo json_encode($ret);
    die('');
}

if ($rtype == 0) {
    //city selected
//    $city = $_POST['city'];
//    $cc = $_POST['cc'];
    $city = $request->request->get('city', '');
    $cc = $request->request->get('cc', '');
    $city_id = getCityId($city, '', $cc);
    $location_id = null;
} else if ($rtype == 1) {
    //location selected
//    $location_id = intval($_POST['loc_id']);
    $location_id = intval($request->request->get('loc_id', 0));
    $location = locationGet($location_id);
    if ($location == false){
        $ret['status'] = 'error';
        $ret['type'] = 'save';
        echo json_encode($ret);
        die('');
    }
    $city_id = $location['city_id'];
}else {
    $location_id = null;
    $city_id = null;
}

if ( $insertedFlashId = flashAdd($user_id, $flash_text,$flash_link,$flash_location, $filename, $vpath, $FlashLong, $FlashLat, $replyTo, $city_id, $location_id, $location_name)) {    
    if(save_hashtags($flash_text,$insertedFlashId)){
        $ret['status'] = 'ok';
    }else{
        $ret['status'] = 'error';
        $ret['type'] = 'save tags';
    }
} else {
    $ret['status'] = 'error';
    $ret['type'] = 'save';
    $ret['typesa'] = ' '.$flash_text;
}

echo json_encode($ret);
