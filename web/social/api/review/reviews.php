<?php
/*! \file
 * 
 * \brief This api to get the review for either hotel,restaurant,point of interest, or airports
 * 
 * 
 * @param entity_id  entity id
 * @param entity_type entity type
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * 
 * @return <b>media_reviewsArray</b> List of review information (array):
 * @return <pre> 
 * @return         <b>rev_id</b> review id
 * @return         <b>logo_userRev_src</b> review user profile picture
 * @return         <b>description_db</b> description added or review
 * @return         <b>creator_ts</b> change the date to a string example: 1 month ago 
 * @return         <b>tuber_name_action</b> tuber name
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
//if( !$user_id ) die();

//$entity_id = intval($_REQUEST['entity_id']);
//$entity_type = intval($_REQUEST['entity_type']);
//$page = ($_REQUEST['page'])? intval($_REQUEST['page']) : 0;
//$limit = ($_REQUEST['limit'])? intval($_REQUEST['limit']) : 100;
$entity_id = intval($submit_post_get['entity_id']);
$entity_type = intval($submit_post_get['entity_type']);
$page = ($submit_post_get['page'])? intval($submit_post_get['page']) : 0;
$limit = ($submit_post_get['limit'])? intval($submit_post_get['limit']) : 10;

switch($entity_type){
    case SOCIAL_ENTITY_HOTEL:
        $media_hotels_reviews = userReviewsList($entity_id, SOCIAL_ENTITY_HOTEL, $limit, $page);    
        $review_type = SOCIAL_ENTITY_HOTEL_REVIEWS;
        break;
    
    case SOCIAL_ENTITY_RESTAURANT:
        $media_hotels_reviews = userReviewsList($entity_id, SOCIAL_ENTITY_RESTAURANT, $limit, $page);
        $review_type = SOCIAL_ENTITY_RESTAURANT_REVIEWS;
        break;
    
    case SOCIAL_ENTITY_LANDMARK:
        $media_hotels_reviews = userReviewsList($entity_id, SOCIAL_ENTITY_LANDMARK, $limit, $page);
        $review_type = SOCIAL_ENTITY_LANDMARK_REVIEWS;
        break;
    case SOCIAL_ENTITY_AIRPORT:
        $media_hotels_reviews = userReviewsList($entity_id, SOCIAL_ENTITY_AIRPORT, $limit, $page);
        $review_type = SOCIAL_ENTITY_AIRPORT_REVIEWS;
        break;
}
$media_reviewsArray = array();
foreach ($media_hotels_reviews as $rev) {
    $media_hotels_reviewsArray = array();
    $rev_id = $rev['id'];
    $user_id_rev = $rev['user_id'];
    $userRevInfo = getUserInfo($user_id_rev);
    $logo_userRev_src = ReturnLink('media/tubers/' . $userRevInfo['profile_Pic']);
    $description_db = html_entity_decode($rev['description'], 0);
    $media_hotels_reviewsArray['rev_id'] = $rev_id;
    $media_hotels_reviewsArray['logo_userRev_src'] = $logo_userRev_src;
    //$media_hotels_reviewsArray['title'] = htmlEntityDecode($rev['title']);
    $media_hotels_reviewsArray['description_db'] = trim($description_db);
    $creator_ts = strtotime($rev['create_ts']);
    $media_hotels_reviewsArray['creator_ts'] =  formatDateAsString($creator_ts);
    $user_link = userProfileLink($userRevInfo);
    //$media_hotels_reviewsArray['user_link'] = $user_link;
    $tuber_name_action = returnUserDisplayName($userRevInfo);
    $media_hotels_reviewsArray['tuber_name_action'] = $tuber_name_action;
    $is_owner = ($user_id && intval($rev['user_id']) == intval($user_id)) ? '1' : '0';
    $media_hotels_reviewsArray['is_owner'] = $is_owner;
    $media_hotels_reviewsArray['owner_id'] = $rev['user_id'];
    
//            $media_hotels_reviewsArray['disp_remove'] = false;
//            if($user_id==$user_id_rev){
//                $media_hotels_reviewsArray['disp_remove'] = true;
//            }
    
    
    $entity_owner = socialEntityOwner( $review_type , $rev_id );
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
        $entity_info = socialEntityInfo($review_type,$rev_id);
        if( $user_id==intval($entity_info['user_id']) || $user_id==intval($entity_info['userid']) ){
            $comment_published=1;
        }
    }
    
    $comments_count_options = array(
        'entity_id' => $rev_id,
        'entity_type' => $review_type,
        'published' => $comment_published,
        'is_visible' => $is_visible,
        'n_results' => true
    );			
    $comments_count = socialCommentsGet($comments_count_options);
    $shares_count_options = array(
        'entity_id' => $rev_id,
        'entity_type' => $review_type,
        'share_type' => SOCIAL_SHARE_TYPE_SHARE,
        'like_value' => 1,
        'n_results' => true
    );
    $shares_count = socialSharesGet($shares_count_options);
    
    $comments = socialCommentsList(5, 0, $rev_id, $review_type, $comment_published, $is_visible, $entity_owner,$user_id);
    $likes_options = array(
        'entity_id' => $rev_id,
        'entity_type' => $review_type,
        'like_value' => 1,
        'n_results' => true
    );
    $likes = socialLikesGet($likes_options);
    $isLiked = socialLiked($user_id, $rev_id, $review_type);
    $media_hotels_reviewsArray['is_liked'] = $isLiked ? "$isLiked" : "0";
    $media_hotels_reviewsArray['comments'] = $comments;
    $media_hotels_reviewsArray['likes'] = $likes;
    $media_hotels_reviewsArray['nb_comments'] = $comments_count;
    $media_hotels_reviewsArray['nb_shares'] = $shares_count;
    $media_reviewsArray[] = $media_hotels_reviewsArray;
}
$output['reviews'] = $media_reviewsArray;

    
echo json_encode($media_reviewsArray);