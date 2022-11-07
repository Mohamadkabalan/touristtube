<?php
$expath = "../";			
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());

//$user_id = mobileIsLogged($_REQUEST['S']);
//$album_id = intval( $_REQUEST['id'] );
//
//$page =  isset($_REQUEST['page'])  ? intval($_REQUEST['page']) : 0;
$user_id = mobileIsLogged($submit_post_get['S']);
$album_id = intval( $submit_post_get['id'] );

$page =  isset($submit_post_get['page'])  ? intval($submit_post_get['page']) : 0;

/*big_image*/
$VideoInfo = userCatalogDefaultMediaGet($album_id);

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
$output = array();
$AlbumInfo = userCatalogGet($album_id);
//print_r($AlbumInfo);exit();
//echo $user_id.', '.$AlbumInfo['user_id'];exit;
if ($user_id && intval($user_id) == intval($AlbumInfo['user_id'])) {
    $is_owner = 1;
} else {
    $is_owner = 0;
}
//$is_owner = 1;
/*Other Images*/
$imLimit = 5;
$imLimit =  isset($submit_post_get['limit'])  ? intval($submit_post_get['limit']) : 5;
$permitted = userPermittedForMedia($user_id, $VideoInfo, SOCIAL_ENTITY_MEDIA);
if($page == 0 && $permitted){
    $imLimit = $imLimit - 1;
    $output[] = array(
        'id'=>$photoRecord['id'],
        'title'=>$photoRecord['title'],
        'path'=>$image_src,
        'type'=>$bgimage_type
    );
}else{
    if($permitted)
        $skip = (($imLimit - 1) * $page) + ($page - 1);
    else{
        $skip = $imLimit * $page; 
    }
}
//echo $skip;exit;
$srch_options = array(
    'limit' => $imLimit,
    'page' => $page,
    'catalog_id' => $album_id,
    'orderby' => 'id',
    'order' => 'd',
    'is_owner' => $is_owner,
    'type' => 'a',
    'exclude_id' => $photoRecord['id']
);
if($page > 0){
    $srch_options['page'] = 0;
    $srch_options['skip'] = $skip;
}

$photos1 = mediaSearch($srch_options);
//print_r($photos1);exit();
foreach($photos1 as $photo){
    if ($photo['image_video'] == "v") {
        $thumb_src = substr(getVideoThumbnail($photo['id'], $path . $photo['fullpath'], 0), strlen($path));
        if(!$thumb_src) { $thumb_src = ''; }
        $type = 'video';
    } else {
        $thumb_src = $photo['fullpath'] . $photo['name'];
        $type = 'image';
    }
    $output[] = array(
        'id'=>$photo['id'],
        'title'=>$photo['title'],
        'path'=>$thumb_src,
        'type'=>$type
    );
}

echo json_encode($output);