<?php
set_time_limit(0);
$path = "";
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/users.php" );
if (!userIsLogged()) {
    exit('Not allowed');
}
$file_id = xss_sanitize(UriGetArg(0));
$userid = userGetID();
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
ob_start();
header('Content-Description: File Transfer');
header("Content-Type: application/octet-stream; charset=utf-8");
header("Content-Length: " . filesize($original_filename));
header('Content-Disposition: attachment; filename="' . $new_filename . '"');
header('Content-Transfer-Encoding: binary');
header('Connection: Keep-Alive');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
echo file_get_contents($original_filename);