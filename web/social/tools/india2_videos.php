<?php
$path = "../";
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);

ini_set("error_reporting", E_ALL);
ini_set("display_errors", 1);
//mysql_connect('localhost', 'root', 'mysql_root') or die("Database error");
mysql_connect('localhost', 'tourist', 'DyHgu2ejoapztca') or die("Database error");
mysql_select_db('touristtube');




$india_videos_query = "SELECT id, title, description, placetakenat, keywords FROM cms_videos WHERE userid = 485";
$india_videos_res = mysql_query($india_videos_query);
$india_videos = array();
while($row = mysql_fetch_array($india_videos_res)){
    $india_videos[] = $row;
}
foreach($india_videos as $video){
    $id = $video['id'];
    $title = $video['title'];
    $description = $video['description'];
    $placetakenat = $video['placetakenat'];
    $keywords = $video['keywords'];
    $insert_query = "INSERT INTO ml_videos (video_id, lang_code, title, description, placetakenat, keywords) VALUES ($id, 'en', '$title', '$description', '$placetakenat', '$keywords')";
//    echo $insert_query.'\n';
    mysql_query($insert_query);
//    echo $title.' '.$description.' '.$placetakenat.' '.$keywords;
}
echo "done";