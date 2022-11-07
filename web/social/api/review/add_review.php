<?php

$expath = "../";			
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
 

//$user_id = mobileIsLogged($_REQUEST['S']);
$user_id = mobileIsLogged($submit_post_get['S']);
if( !$user_id ) die();

//$id = intval($_POST['id']);
//$entity_type = intval($_POST['data_type']);
//$date = xss_sanitize($_POST['date']);
//$near = xss_sanitize($_POST['near']);
//$themes = xss_sanitize($_POST['themes']);
//$description = xss_sanitize($_POST['txt']);
$id = intval($request->request->get('id', ''));
$entity_type = intval($request->request->get('data_type', ''));
$date = $request->request->get('date', '');
$near = $request->request->get('near', '');
$themes = $request->request->get('themes', '');
$description = $request->request->get('txt', '');
$title='';
$hotel_new = intval($request->request->get('hotel_new', 0));
if( $entity_type != SOCIAL_ENTITY_HOTEL ) $hotel_new =0;

$Result = array();
//$user_id = userGetID();
if ( !userIsLogged() ) {
    $Result['status'] = 'error';
    $Result['msg'] = _('You need to have a "TT account" in order to write a review');
}else if ( $description=='' && $hotel_new==1) {
    $Result['status'] = 'error';
    $Result['msg'] = _('Couldn\'t save empty information.');
}else{
    $Result['status'] = 'ok';
    $Result['msg'] = _('Review added successfully.');
    if($hotel_new==1){
	if( !userCmsHotelReviewsAdd( $user_id, $id, $description ) ){
	    $Result['status'] = 'error';
	    $Result['msg'] = _('Couldn\'t save the information. Please try again later.');
	}
    }else{
	if( !userReviewsExtraAdd( $user_id , $entity_type , $id , $title , $description , $date , $near , $themes ) ){
	    $Result['status'] = 'error';
	    $Result['msg'] = _('Couldn\'t save the information. Please try again later.');
	}
    }    
}

echo json_encode($Result);
