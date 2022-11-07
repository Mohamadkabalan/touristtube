<?php
/*! \file
 * 
 * \brief This api  gets all user catalogs(or albums) 
 * 
 * 
 * @param S  session id
 * @param page page number (starting from 0)
 * 
 * @return <b>output</b> List of album information (array):
 * @return <pre> 
 * @return         <b>id</b> album id
 * @return         <b>nb_comments</b> album number of comments
 * @return         <b>like_value</b> album number of like
 * @return         <b>rating</b> album average rating
 * @return         <b>nb_rating</b> album number of rating
 * @return         <b>nb_views</b> album number of views
 * @return         <b>nb_shares</b> album number of shares
 * @return         <b>title</b> album title
 * @return         <b>description</b> album description
 * @return         <b>catalog_date</b> album uploaded date
 * @return         <b>thumb</b> album thumb path
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */


$expath    = '../';
header("content-type: application/json; charset=utf-8");
include_once("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());

//$user_id = mobileIsLogged($_REQUEST['S']);
$user_id = mobileIsLogged($submit_post_get['S']);
$logged_user_id = $user_id;
$uid = isset($submit_post_get['uid']) ? intval($submit_post_get['uid']) : 0;

if($uid == 0 && !$user_id) die();
$is_owner = 1;
if($uid > 0){
    if($uid != $user_id){
        $is_owner = 0;
    }
    $user_id = $uid;
}
//$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 0;
//$limit = ( $_REQUEST['limit'] ) ? intval($_REQUEST['limit']) : 10;
$page = isset($submit_post_get['page']) ? intval($submit_post_get['page']) : 0;
$limit = ( $submit_post_get['limit'] ) ? intval($submit_post_get['limit']) : 10;
if(intval($submit_post_get['all']) == 1 ){
    $limit = 0;
}

$options = array('limit' => $limit, 'page' => $page,'is_owner'=> $is_owner , 'user_id' => $user_id, 'order' => 'a', 'orderby' => 'catalog_name');

//$options2 = array('user_id' => $userId, 'search_string' => $search_string, 'is_owner'=>$is_owner , 'id' => $txt_id, 'n_results' => true, 'date_from' => $from_date, 'date_to' => $to_date);

$userVideos = userCatalogSearch($options);
//$userVideos_count = userCatalogSearch($options2);
//print_r($userVideos);

$output = array();
if ($userVideos) {
    $pcount = count($userVideos);  //print_r($pcount);

    foreach ($userVideos as $album) 
    {
        if ($logged_user_id && intval($logged_user_id) == intval($album['user_id'])) {
            $is_owner = 1;
        } else {
            $is_owner = 0;
        }
        /*big_image*/
        $VideoInfo = userCatalogDefaultMediaGet($album['id']);
        $photoRecord = $VideoInfo;
        if ($photoRecord['image_video'] == "v") {
            $image_src = substr(getVideoThumbnail($VideoInfo['id'], $path . $VideoInfo['fullpath'], 0), strlen($path));
            $bgimage_type = 'video';
            if(!$image_src) { $image_src = ''; }
//                            $image_src = photoReturnThumbSrc($photoRecord);
        } else {
            //$image_src = photoReturnSrcMed($photoRecord);
            $image_src = $VideoInfo['fullpath'] . $VideoInfo['name'];
            $bgimage_type = 'image';
//                            $image_src = photoReturnSrcLink($photoRecord, '');

        }
        /*Other Images*/
        $permitted = userPermittedForMedia($logged_user_id, $photoRecord, SOCIAL_ENTITY_MEDIA);
        $imLimit = 4;
        if(!$permitted)
            $imLimit = 5;
        $cat_id = $album['id'];
        $srch_options = array(
            'limit' => $imLimit,
            'page' => 0,
            'catalog_id' => $cat_id,
            'is_owner' => $is_owner,
            'orderby' => 'id',
            'order' => 'd',
            'type' => 'a',
            'exclude_id' => $photoRecord['id']
        );   
        $photos1 = mediaSearch($srch_options);
        $srch_options1 = array(
            'catalog_id' => $cat_id,
            'is_owner' => $is_owner,
            'type' => 'a',
            'n_results' => true
        );
        $total = mediaSearch($srch_options1);
        $other_img = array();
        foreach($photos1 as $photo){
            if ($photo['image_video'] == "v") {
                $thumb_src = substr(getVideoThumbnail($photo['id'], $path . $photo['fullpath'], 0), strlen($path));
                if(!$thumb_src) { $thumb_src = ''; }
                $type = 'video';
            } else {
                $thumb_src = $photo['fullpath'] . $photo['name'];
                $type = 'image';
            }
            $other_img[] = array(
                'id'=>$photo['id'],
                'title'=>$photo['title'],
                'path'=>$thumb_src,
                'type'=>$type
            );
        }
        $isLiked = socialLiked($logged_user_id, $album['id'], 3);
        $isLiked = $isLiked ? "$isLiked" : "0";
        $user_rating = socialRateGet($logged_user_id, $album['id'], SOCIAL_ENTITY_ALBUM);
      //print_r($photos1);
        $catalog_date = $album['catalog_ts'];
        $pdate = returnSocialTimeFormat($catalog_date,3);
        
        $output[] = array(
            'id'=>$album['id'],
            'title'=>$album['catalog_name'],
            'catalog_date'=>$pdate,
            'nb_comments'=>$album['nb_comments'],
            'like_value'=>$album['like_value'],
            'rating'=>$album['rating'],
            'nb_rating'=>$album['nb_ratings'],
            'nb_views'=>$album['nb_views'],
            'nb_shares'=>$album['nb_shares'],
            'description'=>$album['description'],
            'bgimage_id'=>$photoRecord['id'] ? $photoRecord['id'] : 0,
            'bgimage'=>$image_src,
            'bgimage_private'=> $permitted ? "0" : "1",
            'bgimage_type'=>$bgimage_type,
            'media'=>$other_img,
            'total'=>$total,
            'isLiked'=>$isLiked,
            'user_rating'=>$user_rating ? $user_rating : 0
        );

    }
}
echo json_encode($output);
