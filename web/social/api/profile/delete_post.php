<?php
$expath = "../";
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$userID = mobileIsLogged($submit_post_get['S']);
if( !$userID ) { die(); }
$post_id = $submit_post_get['post_id'];

$Result = array();
$Result['status'] = 'ok';

$ppinfo = socialPostsInfo($post_id);
if($ppinfo['user_id']==$userID || $ppinfo['from_id']==$userID){
    socialPostsDelete($post_id);
}else{
    $Result['status'] = 'error';
}
echo json_encode( $Result );