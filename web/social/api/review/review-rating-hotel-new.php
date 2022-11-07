<?php
/*! \file
 * 
 * \brief This api returns rating of hotel new
 * 
 *  */
$expath = "../";
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());

$user_id = mobileIsLogged($submit_post_get['S']);

$entity_id = intval($submit_post_get['entity_id']);
$entity_type = intval($submit_post_get['entity_type']);


/* Hotel Mix  Rating*/
$result = array();
$toRateArr = array();
if($user_id){
    $irating = socialRated($user_id,$entity_id, SOCIAL_ENTITY_HOTEL, SOCIAL_HOTEL_RATE_TYPE_CLEANLINESS);
    if (!$irating) $irating = 0;
    $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_HOTEL, 'rate_type' => SOCIAL_HOTEL_RATE_TYPE_CLEANLINESS, 'val' => $irating, 'name' =>_('Cleanliness'));

    $irating = socialRated($user_id,$entity_id, SOCIAL_ENTITY_HOTEL, SOCIAL_HOTEL_RATE_TYPE_CONFORT);
    if (!$irating) $irating = 0;
    $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_HOTEL, 'rate_type' => SOCIAL_HOTEL_RATE_TYPE_CONFORT, 'val' => $irating, 'name' =>_('Comfort'));

    $irating = socialRated($user_id,$entity_id, SOCIAL_ENTITY_HOTEL, SOCIAL_HOTEL_RATE_TYPE_LOCATION);
    if (!$irating) $irating = 0;
    $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_HOTEL, 'rate_type' => SOCIAL_HOTEL_RATE_TYPE_LOCATION, 'val' => $irating, 'name' =>_('Location'));

    $irating = socialRated($user_id,$entity_id, SOCIAL_ENTITY_HOTEL, SOCIAL_HOTEL_RATE_TYPE_FACILITIES);
    if (!$irating) $irating = 0;
    $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_HOTEL, 'rate_type' => SOCIAL_HOTEL_RATE_TYPE_FACILITIES, 'val' => $irating, 'name' =>_('Facilities'));

    $irating = socialRated($user_id,$entity_id, SOCIAL_ENTITY_HOTEL, SOCIAL_HOTEL_RATE_TYPE_STAFF);
    if (!$irating) $irating = 0;
    $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_HOTEL, 'rate_type' => SOCIAL_HOTEL_RATE_TYPE_STAFF, 'val' => $irating, 'name' =>_('Staff'));

    $irating = socialRated($user_id,$entity_id, SOCIAL_ENTITY_HOTEL, SOCIAL_HOTEL_RATE_TYPE_MONEY);
    if (!$irating) $irating = 0;
    $toRateArr[] = array('entity_type'=>SOCIAL_ENTITY_HOTEL, 'rate_type' => SOCIAL_HOTEL_RATE_TYPE_MONEY, 'val' => $irating, 'name' =>_('Value for money'));
}
/*All Mix Rating*/
foreach ( $toRateArr as $toRate ) {              
    $result[] =array(
	'entity_type'=>$toRate['entity_type'],
	'rate_type'=>$toRate['rate_type'],
	'rating_value'=>$toRate['val'] ? $toRate['val'] : 0,
	'name'=>$toRate['name'],
    );
}
echo json_encode($result);