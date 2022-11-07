<?php

$expath = "../";
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//$user_id = mobileIsLogged($_REQUEST['S']);
$user_id = mobileIsLogged($submit_post_get['S']);
if( !$user_id ) die();

$comment = $submit_post_get['comment'];
$id = intval($submit_post_get['id']);
//$comments_user_array = $submit_post_get['comments_user_array'];



if( socialEntityOwner(SOCIAL_ENTITY_COMMENT, $id ) == $user_id ){
    $social_array=socialCommentEdit($id,$comment);
    if($social_array){
        $result = array('status' => 'success');
    }
    else{
        $result = array('status' => 'error');
    }
}
else{
    $result = array('status'=>'error', 'msg' => 'not_owner');
}
echo json_encode($result);