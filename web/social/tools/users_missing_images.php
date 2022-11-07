<?php
$path = "../";
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/videos.php");

$all_users_query = "SELECT id, profile_Pic FROM cms_users WHERE profile_Pic <> ''";
$all_users_res = db_query($all_users_query);
$all_users = array();
while($row = db_fetch_array($all_users_res)){
    $all_users[] = $row;
}
$missing_images_ids = array();
foreach($all_users as $user){
    $image = '../media/tubers/small_'.$user['profile_Pic'];
    if(!file_exists($image)){
        $missing_images_ids[] = '['.$user['id'].', '.$user['profile_Pic'].']';
        $src = '../media/tubers/'.$user['profile_Pic'];
        if(file_exists($src)){
            photoThumbnailCreate($src, $image, 45, 45);
        }
    }
}
$result = implode(' -- ', $missing_images_ids);
print_r($result);