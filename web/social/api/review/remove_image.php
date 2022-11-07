<?php
/*! \file
 * 
 * \brief This api returns status of removing an image from an entity ()either hotel,restaurant,landmark, or airports)
 * 
 * 
 * @param S session id
 * @param id entity id
 * @param entity_type entity type
 * 
 * @return <b>ret_arr</b> JSON list with the following keys:
 * @return <pre> 
 * @return       <b>status</b> status if succeded or not
 * @return       <b>msg</b>  error message
 * @return       <b>return_url</b>  <b>only if user not logged in</b> url to go to
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */

$expath = "../";			
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
 

//$user_id = mobileIsLogged($_REQUEST['S']);
$user_id = mobileIsLogged($submit_post_get['S']);
if( !$user_id ) die();

//$id = intval($_REQUEST['id']);
//$entity_type = intval($_REQUEST['entity_type']); 
$id = intval($submit_post_get['id']);
$entity_type = intval($submit_post_get['entity_type']); 
$hotel_new = (isset($submit_post_get['hotel_new']))?intval($submit_post_get['hotel_new']):0;
if( $entity_type != SOCIAL_ENTITY_HOTEL ) $hotel_new =0;

if ( !$user_id ) { 
    $ret_arr['status'] = 'error';
    $ret_arr['msg'] = _('You need to have a TT account in order to remove this image');
    $ret_arr['return_url']=ReturnLink('/register');
}else{
    if( $hotel_new==1 ){ 
	if( userCmsHotelImagesDelete( $id , $user_id ) ){ 
	    $ret_arr['status'] = 'ok';
	}else{
	   $ret_arr['status'] = 'error';
	   $ret_arr['msg'] = _('Couldn\'t save the information. Please try again later');
	}
    }else if( userDiscoverImagesDelete( $user_id , $entity_type, $id ) ){ 
	$ret_arr['status'] = 'ok';
    }else{
       $ret_arr['status'] = 'error';
       $ret_arr['msg'] = _('Couldn\'t save the information. Please try again later');
    }
}

echo json_encode($ret_arr);

