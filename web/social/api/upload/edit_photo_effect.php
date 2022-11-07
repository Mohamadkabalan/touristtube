<?php

$expath = "../";			
//header("content-type: application/xml; charset=utf-8");  
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());

$userid = mobileIsLogged($submit_post_get['S']);
if(!$userid){
    $Result['status'] = 'error';
    $Result['msg'] = 'invalid_token';
    echo json_encode($Result);
    exit;
}
//if( !$user_id ) die();

$id = intval($submit_post_get['id']);
$image64 = $submit_post_get['image64'];

$video_info = getVideoInfo($id);
    //debug("aaaa".$_POST["theId"]);
    //exit();
if ($userid == $video_info['userid']) {
    $pic_full_path = $video_info['fullpath'];
    $pic_name = $video_info['name'];
    $root_link = $CONFIG ['server']['root'];
    $absolute_path = $CONFIG['server']['root'] . $pic_full_path . $pic_name;
    $absolute_directory = $CONFIG['server']['root'] . $pic_full_path;
//        $filteredData = substr($_POST["image64"], strpos($_POST["image64"], ",") + 1);
    //$filteredData = substr($image64, strpos($image64, ",") + 1);
    //$decodedData = base64_decode($filteredData);
    
    //$fp = fopen($absolute_path, 'wb')or die("can't open file");
    //fwrite($fp, $decodedData);
    //fclose($fp);
    
    //$target_dir = $absolute_directory;
    //$target_file = $absolute_path;
    //$uploadOk = 1;
    //$imageFileType = pathinfo($absolute_path,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
    
    rename($_FILES["photo"]["tmp_name"], $absolute_path);
    chmod($absolute_path, 0755);
  
    $dims = getimagesize($absolute_path);
    $dimVal = $dims[0].' X '.$dims[1];
//  Changed by Anthony Malak 14-05-2015 to PDO database
//  <start>
    global $dbConn;
    $params = array();  
//    $query = "UPDATE cms_videos SET dimension='$dimVal' WHERE id='$id'";
//    db_query($query);
    $query = "UPDATE cms_videos SET dimension=:DimVal WHERE id=:Id";
    $params[] = array(  "key" => ":Id",
                        "value" =>$id);
    $params[] = array(  "key" => ":DimVal",
                        "value" =>$dimVal);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    createThumbnailFromImage($absolute_path, $absolute_directory . 'med_' . $pic_name, 0, 0, 700, 375);
    //resize to fit in profile
    createThumbnailFromImage($absolute_path, $absolute_directory . 'small_' . $pic_name, 0, 0, 355, 197);
    createThumbnailFromImage($absolute_path, $absolute_directory . 'xsmall_' . $pic_name, 0, 0, 136, 76);
    //create thumbnail
    createThumbnailFromImage($absolute_path, $absolute_directory . 'thumb_' . $pic_name, 0, 0,237,134);

    $Result['status'] = 'success';
    $Result['msg'] = '';
} else {
    $Result['status'] = 'error';
    $Result['msg'] = _("You are not the owner of the image");
}
    
echo json_encode($Result);