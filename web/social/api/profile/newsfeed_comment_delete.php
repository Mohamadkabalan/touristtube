<?php
$expath = "../";
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$userID = mobileIsLogged($submit_post_get['S']);
if( !$userID ) { die(); }

$Result = array();
$Result['status'] = 'ok';

$comment_id = intval($submit_post_get['id']);

$cr = socialCommentRow($comment_id);

$review_owner_id = socialEntityOwner($cr['entity_type'], $cr['entity_id']);

if(intval($cr['user_id']) != $userID && intval($review_owner_id) != $userID)
{
    $Result['status'] = 'error';
}
else{
    socialCommentDelete($comment_id,true);
}

echo json_encode( $Result );

