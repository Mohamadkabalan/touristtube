<?php

$expath = "../";
header('Content-type: application/json');
include($expath."heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$user_ID = mobileIsLogged($submit_post_get['S']);

$id = intval($submit_post_get['share_id']);
$entity_row = socialShareGet($id);
if (socialEntityOwner(SOCIAL_ENTITY_SHARE, $id) == $user_ID) {
    if (socialShareDelete($id)) {
        $Result['status'] = 'ok';
    } else {
        $Result['done'] = 'error';
    }
} else {
    $Result['done'] = 'error';
}
echo json_encode($Result);
