<?php
set_time_limit(0);
ob_start();
$expath = "../";
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$userid = mobileIsLogged($submit_post_get['S']);
if( !$userid ) { die("No userid..."); }
$file_id = $submit_post_get['file_id'];
$thumb = intval($submit_post_get['thumb']);
global $dbConn;
$params = array(); 
//$query = "SELECT senderId, receiverId, originalName, newName 
//          FROM cms_chat_file
//          WHERE (senderId = $userid OR receiverid = $userid) AND file_id = $file_id
//         ";
$query = "SELECT senderId, receiverId, originalName, newName 
          FROM cms_chat_file
          WHERE (senderId = :Userid OR receiverid = :Userid) AND file_id = :File_id
         ";
$params[] = array(  "key" => ":Userid",
                    "value" =>$userid);
$params[] = array(  "key" => ":File_id",
                    "value" =>$file_id);
$select = $dbConn->prepare($query);
PDO_BIND_PARAM($select,$params);
$ret    = $select->execute();
//$ret = db_query($query);
//$row = db_fetch_array($ret);
$row = $select->fetch();
//$original_filename = "/mnt/uploads/media/fileshare/".$row['newName'];

$original_filename = "/home/tt/www/uploads/media/fileshare/".$row['newName'];
$new_filename = $row['originalName'];
ob_end_clean();
header('Content-Description: File Transfer');

$file_extension = pathinfo($original_filename, PATHINFO_EXTENSION);
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $original_filename);
if($thumb == 1){
    if(mimeIsVideo($mime)){
        $original_filename = str_replace(".".$file_extension, ".png", $original_filename);
        $new_filename = str_replace(".".$file_extension, ".png", $new_filename);
        $file_extension = "png";
    }
    else{
        $original_filename = str_replace(".".$file_extension, ".jpg", $original_filename);
        $file_name = "thumb_".pathinfo($original_filename, PATHINFO_FILENAME);
        $directory = pathinfo($original_filename, PATHINFO_DIRNAME);
        $original_filename = $directory."/".$file_name.".jpg";
        $new_filename = str_replace(".".$file_extension, ".jpg", $new_filename);
        $file_extension = "jpg";
    }
}
$content_type_default = "application/octet-stream";
$content_types = array(
		"exe" => "application/octet-stream",
		"zip" => "application/zip",
		"mp3" => "audio/mpeg",
		"mpg" => "video/mpeg",
		"avi" => "video/x-msvideo",
);
$content_type = (isset($content_types[$file_extension])?$content_types[$file_extension]:$content_type_default);
header("Content-Type: ".$content_type);


header("Content-Length: " . filesize($original_filename));
header('Content-Disposition: attachment; filename="' . $new_filename . '"');
// header('Content-Transfer-Encoding: binary');
header('Connection: Keep-Alive');
header('Expires: -1');
header('Cache-Control: no-cache'); // public, must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
// echo file_get_contents($original_filename);
readfile($original_filename);
exit;