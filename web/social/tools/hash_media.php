<?php
$path = "../";
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );

$all_media_query = "SELECT id FROM cms_videos";
$all_media_res = db_query($all_media_query);
$all_media = array();
while($row = db_fetch_array($all_media_res)){
    $all_media[] = $row;
}

foreach($all_media as $media){
    $id = $media['id'];
    $hashids = tt_global_get('hashids');
    $hashed_id = $hashids->encode($id);
    $hash_query = "UPDATE cms_videos SET hash_id = '$hashed_id' WHERE id = $id";
    db_query($hash_query);
}
