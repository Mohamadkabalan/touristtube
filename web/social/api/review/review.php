<?php
$expath = "../";
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());

$user_id = mobileIsLogged($submit_post_get['S']);
$entity_id = intval($submit_post_get['entity_id']);
$entity_type = intval($submit_post_get['entity_type']);
$review_info = userReviewsInfo($entity_id, $entity_type);

$rev_id = $review_info['id'];
$review_text = $review_info['description'];
$user_id_rev = $review_info['user_id'];
$userRevInfo = getUserInfo($user_id_rev);
$logo_userRev_src = ReturnLink('media/tubers/' . $userRevInfo['profile_Pic']);
$tuber_name_action = returnUserDisplayName($userRevInfo);
$isLiked = socialLiked($user_id, $rev_id, $entity_type);

$entity_owner = socialEntityOwner( $entity_type , $rev_id );
$is_entity_owner =0;
$is_visible=-1;
if($user_id == $entity_owner){                    
    $is_entity_owner =1;
}else{        		
    $is_visible=1;
}
$comment_published=1;
$user_is_logged=0;
if($user_id){
    $user_is_logged=1;	
}else{
    $is_visible=1;	
}
if($user_is_logged==1){
    $entity_info = socialEntityInfo($entity_type,$rev_id);
    if( $user_id==intval($entity_info['user_id']) || $user_id==intval($entity_info['userid']) ){
        $comment_published=1;
    }
}

$comments = socialCommentsList(5, 0, $rev_id, $entity_type, $comment_published, $is_visible, $entity_owner,$user_id);

$result = array(
    "owner" => array(
        'id' => $user_id_rev,
        'name' => $tuber_name_action,
        'profile_pic' => $logo_userRev_src,
        'review' => $review_text,
        'is_liked' => $isLiked ? "$isLiked" : "0"
    ),
    "comments" => $comments
);

echo json_encode($result);