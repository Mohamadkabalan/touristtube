<?php
set_time_limit ( 0 );
ini_set('display_errors',1);
$database_name = "touristtube";

$main_path = '/uploads/';
$main_path1 = '/uploads/classified/noalbum/';

//$main_path = '/home/para-tube/www/';
//$main_path1 = '/var/log/albums/tubers/';

mysql_connect('localhost','tourist','DyHgu2ejoapztca');
//mysql_connect('localhost','root','mysql_root');
mysql_select_db($database_name);

//$sql_results = mysql_query("SELECT cms_users_catalogs.* FROM cms_users_catalogs,cms_users  "
//        . "WHERE cms_users_catalogs.user_id=cms_users.id AND channelid <> 0 AND  cms_users_catalogs.id >=624 ") or die( mysql_error());

//$i=0;
//while($data = mysql_fetch_array($sql_results)) {
// $dir_name = $data['id'].'-'.$data['user_id'].'-'.$data['catalog_name'];
// echo $i++.':'.$dir_name."<br>";
// mkdir ($main_path1.$dir_name);
//  $medias = mysql_query("SELECT cms_videos.fullpath, cms_videos.name FROM cms_videos, cms_videos_catalogs "
//         . " WHERE cms_videos.id = cms_videos_catalogs.video_id AND cms_videos.image_video = 'v' "
//         . "AND cms_videos_catalogs.catalog_id = ".$data['id']) or die( mysql_error());
// while($m = mysql_fetch_array($medias)) {
// echo $source = $main_path.$m['fullpath'].$m['name'];
// $dest = $main_path1.$dir_name.'/'.$m['name'];
//copy($source,$dest);
//echo '<br>';
// }
//
// $medias = mysql_query("SELECT cms_videos.fullpath, cms_videos.name FROM cms_videos, cms_videos_catalogs "
//         . " WHERE cms_videos.id = cms_videos_catalogs.video_id AND cms_videos.image_video = 'i' "
//         . "AND cms_videos_catalogs.catalog_id = ".$data['id']) or die( mysql_error());
// while($m = mysql_fetch_array($medias)) {
// echo $source = $main_path.$m['fullpath'].'org_'.$m['name'];
// $dest = $main_path1.$dir_name.'/'.$m['name'];
//copy($source,$dest);
//echo '<br>';
// }
//}

  $medias = mysql_query("SELECT cms_videos.fullpath, cms_videos.name FROM cms_videos "
         . " WHERE cms_videos.id  NOT IN ( SELECT video_id FROM cms_videos_catalogs) AND cms_videos.image_video = 'v' ") or die( mysql_error());
 while($m = mysql_fetch_array($medias)) {
 echo $source = $main_path.$m['fullpath'].$m['name'];
 $dest = $main_path1.$m['name'];
copy($source,$dest);
echo '<br>';
 }

 $medias = mysql_query("SELECT cms_videos.fullpath, cms_videos.name FROM cms_videos "
         . " WHERE cms_videos.id  NOT IN ( SELECT video_id FROM cms_videos_catalogs) AND cms_videos.image_video = 'i' ") or die( mysql_error());
 while($m = mysql_fetch_array($medias)) {
 echo $source = $main_path.$m['fullpath'].'org_'.$m['name'];
 $dest = $main_path1.$m['name'];
copy($source,$dest);
echo '<br>';
 }