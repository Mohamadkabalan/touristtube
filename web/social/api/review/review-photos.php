<?php
$expath = "../";
header('Content-type: application/json');
include("../heart.php");
$uricurserver = currentServerURL();
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//echo $uricurserver;

//$user_id = mobileIsLogged($_REQUEST['S']);
$user_id = mobileIsLogged($submit_post_get['S']);

if( !$user_id ) die();

$link = 'media/discover/';
$theLink = $CONFIG ['server']['root'];
//$entity_id = intval($_REQUEST['entity_id']);
//$entity_type = intval($_REQUEST['entity_type']);
$entity_id = intval($submit_post_get['entity_id']);
$entity_type = intval($submit_post_get['entity_type']);
$media_hotelsi = array();
switch($entity_type){
    case SOCIAL_ENTITY_HOTEL:
	global $dbConn;
	$params = array(); 
//        $query_hotelsi = "SELECT id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = $entity_id AND user_id=$user_id ORDER BY id DESC";
        $query_hotelsi = "SELECT id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = :Entity_id AND user_id=:User_id ORDER BY id DESC";
	$params[] = array(  "key" => ":Entity_id",
                            "value" =>$entity_id);
	$params[] = array(  "key" => ":User_id",
                            "value" =>$user_id);
	$select = $dbConn->prepare($query_hotelsi);
	PDO_BIND_PARAM($select,$params);
	$ret_hotelsi    = $select->execute();

	$ret    = $select->rowCount();

//        $ret_hotelsi = db_query($query_hotelsi);
	$row = $select->fetchAll();
        foreach($row as $row_item){
//        while ($row = db_fetch_array($ret_hotelsi)) {
            $bigim=$row_item['img'];
            $for = strrpos($bigim, '_') + 1;
            $extbig = substr($bigim, $for);    
            if(file_exists( $theLink . 'media/discover/large/' .$extbig ) ){
                $row_item['discover_imgbig'] = $link . 'large/' . $extbig;
            }else{
                $row_item['discover_imgbig'] = $link . 'large/' . $row_item['img'];
            }
            $media_hotelsi[] = $row_item;
        }
        break;
    case SOCIAL_ENTITY_RESTAURANT:
	global $dbConn;
	$params = array();  
//        $query_hotelsi = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = $entity_id AND user_id=$user_id ORDER BY id DESC";
        $query_hotelsi = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = :Entity_id AND user_id=:User_id ORDER BY id DESC";
	$params[] = array(  "key" => ":Entity_id",
                            "value" =>$entity_id);
	$params[] = array(  "key" => ":User_id",
                            "value" =>$user_id);
	$select = $dbConn->prepare($query_hotelsi);
	PDO_BIND_PARAM($select,$params);
	$ret_hotelsi    = $select->execute();
//         $ret_hotelsi = db_query($query_hotelsi);
	$row = $select->fetchAll();
//         while ($row = db_fetch_array($ret_hotelsi)) {
        foreach($row as $row_item){
             $bigim=$row_item['img'];
             $for = strrpos($bigim, '_') + 1;
             $extbig = substr($bigim, $for);    
             if(file_exists( $theLink . 'media/discover/large/' .$extbig ) ){
                 $row_item['discover_imgbig'] = $link . 'large/' . $extbig;
             }else{
                 $row_item['discover_imgbig'] = $link . 'large/' . $row_item['img'];
             }
             $media_hotelsi[] = $row_item;
         }
        break;
    case SOCIAL_ENTITY_LANDMARK:
	global $dbConn;
	$params = array();  
//        $query_hotelsi = "SELECT id , filename as img , poi_id FROM `discover_poi_images` WHERE poi_id = $entity_id AND user_id=$user_id ORDER BY id DESC";
        $query_hotelsi = "SELECT id , filename as img , poi_id FROM `discover_poi_images` WHERE poi_id = :Entity_id AND user_id=:User_id ORDER BY id DESC";
	$params[] = array(  "key" => ":Entity_id",
                            "value" =>$entity_id);
	$params[] = array(  "key" => ":User_id",
                            "value" =>$user_id);
	$select = $dbConn->prepare($query_hotelsi);
	PDO_BIND_PARAM($select,$params);
	$ret_hotelsi    = $select->execute();
//         $ret_hotelsi = db_query($query_hotelsi);
	$row = $select->fetchAll();
//         while ($row = db_fetch_array($ret_hotelsi)) {
        foreach($row as $row_item){
             $bigim=$row_item['img'];
             $for = strrpos($bigim, '_') + 1;
             $extbig = substr($bigim, $for);    
             if(file_exists( $theLink . 'media/discover/large/' .$extbig ) ){
                 $row_item['discover_imgbig'] = $link . 'large/' . $extbig;
             }else{
                 $row_item['discover_imgbig'] = $link . 'large/' . $row_item['img'];
             }
             $media_hotelsi[] = $row_item;
         }
        break;
}
$media = array();
foreach ( $media_hotelsi as $media_hotels )    
{
    $media[] =array(
        'id'=>$media_hotels['id'],
        'img'=>$media_hotels['img'],
        'discover_imgbig'=>$media_hotels['discover_imgbig']
    );
}
echo json_encode($media);
