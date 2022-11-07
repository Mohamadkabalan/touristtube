<?php
$expath = "../";
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());

//$user_id = mobileIsLogged($_REQUEST['S']);
$user_id = mobileIsLogged($submit_post_get['S']);

if( !$user_id ) die();

//    $id = intval($_POST['id']);
//    $entity_type = intval($_POST['entity_type']);

    $id = intval($request->request->get('id', ''));
    $entity_type = intval($request->request->get('entity_type', ''));
    
    $Result = array();
    if(userReviewsExtraDelete($user_id, $entity_type ,$id)){
        $Result['result'] = 'ok';        
    }else{
        $Result['result'] = 'error';
        $Result['error'] = _('invalid data');
    }    
    echo json_encode( $Result );