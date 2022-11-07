<?php

/**
 * functionality that deals with cms_videos tables
 * @package videos
 */
/**
 * the width of the media on the profile page
 */
define('MEDIA_PROFILE_WIDTH', 189);
/**
 * the height of the media on the profile page
 */
define('MEDIA_PROFILE_HEIGHT', 106);

/**
 * the width of the media on the newsfeed page
 */
define('MEDIA_NEWSFEED_WIDTH', 355);
/**
 * the height of the media on the newsfeed page
 */
define('MEDIA_NEWSFEED_HEIGHT', 197);

/**
 * how many thumbs to view in list mode 
 */
define('MEDIA_LIST_MODE_RESULTS', 15);
/**
 * how many thumbs to view in list mode 
 */
define('MEDIA_THUMB_MODE_RESULTS', 45);

/**
 * the media was just uploaded 
 */
define('MEDIA_UPLOADED', 0);
/**
 * the media file was is being processed
 */
define('MEDIA_PROCESSING', 2);
/**
 * the media file was processed and ready to be propagated throughout the cluster 
 */
define('MEDIA_PROCESSED', 3);
/**
 * the media is ready to be viewed 
 */
define('MEDIA_READY', 1);
/**
 * the media has been disabled ususally by a user deactivating his account
 */
define('MEDIA_DISABLED', -1);
/**
 * the media is to be deleted by the delete service
 */
define('MEDIA_DELETE', -2);

/**
 * tries to detect the mime type of a file
 * @param string $file the filename
 * @return string
 */
function media_mime_type($file) {
    $ob = ob_get_clean();
    $mime = system("file --mime-type -b $file");
    ob_clean();
    echo $ob;
    if ($mime == 'application/octet-stream')
        $mime = mime_content_type($file);
    if ($mime == 'application/octet-stream')
        $mime = mime_by_extension($file);
    return $mime;
}
/*
 * get the image dimesions that fits in a box given width and height
 * $image is the full image path
 * $max_width is the maximum box width
 * $max_height is the maximum box height
 * return array of width and height of the image that must be resized
 */

function GetImageDimensions($image, $max_width, $max_height,$ww=10, $hh=10) {

    $size = getimagesize($image);
    $width = $size[0];
    $height = $size[1];

    $finalSize = array();

    // get the ratio needed
    $x_ratio = $max_width / $width;
    $y_ratio = $max_height / $height;

    // if image already meets criteria, load current values in
    // if not, use ratios to load new size info
    if (($width <= $max_width) && ($height <= $max_height)) {
        $tn_width = $width;
        $tn_height = $height;
    } else if ( $x_ratio < $y_ratio) {
            $tn_height = ceil($x_ratio * $height);
            $tn_width = ceil($x_ratio * $width);
    } else {
            $tn_height = ceil($y_ratio * $height);
            $tn_width = ceil($y_ratio * $width);
    }
    if ( $tn_width < $ww) {
        $scl= $ww/$tn_width;
        $tn_height = ceil($scl * $tn_height);
        $tn_width = ceil($scl * $tn_width);
    } 
    if ( $tn_height < $hh) {
        $scl= $hh/$tn_height;
        $tn_height = ceil($scl * $tn_height);
        $tn_width = ceil($scl * $tn_width);
    }
    
    $finalSize['width'] = $tn_width;
    $finalSize['height'] = $tn_height;

    return $finalSize;
}

// upload video
function uploadVideo($FILE, $uploadDir, $fileInput = 'uploadfile') {
    $fileInfo = array();
    $fileName = time() . '-' . str_replace(array(' '), array('-'), $FILE[$fileInput]['name']);
    $fileName = preg_replace('/[^a-z0-9A-Z\.]/', '-', $fileName);

    $fileSize = round($FILE[$fileInput]['size'] / 1024);
    $file = $uploadDir . basename($fileName);

    $dir = dirname($file);
    if (!file_exists($dir)) {
        mkdir($dir, 0777, $recursive = true);
    }

    if (@move_uploaded_file($FILE[$fileInput]['tmp_name'], $file)) {
        
        $fileInfo ['name'] = $fileName;
        $fileInfo ['size'] = $fileSize;

        //first try to find the file's type using magic
        $fileInfo['type'] = media_mime_type($file);
        if( strstr($fileInfo['type'],'image') != null ){
            mediaRotateImage($file);
        }
        //$finfo = finfo_open(FILEINFO_MIME_TYPE);
        //$fileInfo [ 'type' ]  = finfo_file( $finfo, $file );
        //echo 'Success ! <span class="FileNameDiv">' . $fileName . '</span> ' . $fileSize . ' KB'; 
        return $fileInfo;
    } else {
        //echo "Error! " . $FILE[$fileInput]['error'] . " --- " . $FILE[$fileInput]['tmp_name'] ." %%% " . $file . "(" . $fileSize . " KB)";
        return false;
    }
}

function check_actual_filetype_with_tmp_path($filepath){
    $type = exif_imagetype($filepath);
    switch($type){
        case '1':
            $type_value = 'gif';
            break;
        case '2':
            $type_value = 'jpeg';
            break;
        case '3':
            $type_value = 'png';
            break;
        case '6':
            $type_value = 'bmp';
            break;
		case '17':
			$type_value = 'ico';
			break;
		default:
			$type_value = 'wrong_type';
			break;
    }
    return $type_value;
}

function MOB_uploadVideo($FILE, $uploadDir, $fileInput = 'uploadfile') {
    $temp_dir =$uploadDir . md5(time()). "/";
    mkdir($temp_dir, 0777, true);
    
    //foreach($FILE as $temp_file){
    foreach($_FILES['uploadfile']['tmp_name'] as $key => $tmp_name ){ 
        $fileInfo = array();
//        $fileName = time() . $FILE[$fileInput]['name'];
//        $fileSize = round($FILE[$fileInput]['size'] / 1024);
        $file_name = $_FILES['uploadfile']['name'][$key];
        $file_size = $_FILES['uploadfile']['size'][$key];
        $file_tmp = $_FILES['uploadfile']['tmp_name'][$key];
        $file_type= $_FILES['uploadfile']['type'][$key];
        
        $fileName = time() . '-' . str_replace(array(' '), array('-'), $file_name);
        $fileName = preg_replace('/[^a-z0-9A-Z\.]/', '-', $fileName);
        
//        $fileName = time() . $file_name;
        $file = $temp_dir . basename($fileName);
        //$file = $uploadDir . basename($fileName);
  
        $dir = dirname($file);
        if (!file_exists($dir)) {
            mkdir($dir, 0777, $recursive = true);
        }

        if (@move_uploaded_file($file_tmp, $file)) {
            $fileInfo ['name'] = $fileName;
            $fileInfo ['size'] = $file_size;
            $fileInfo ['type'] = mime_content_type($file);
            if( strstr($fileInfo['type'],'image') != null ){
                mediaRotateImage($file);
            }
            //$finfo = finfo_open(FILEINFO_MIME_TYPE);
            //$fileInfo [ 'type' ]  = finfo_file( $finfo, $file );
            //echo 'Success ! <span class="FileNameDiv">' . $fileName . '</span> ' . $fileSize . ' KB'; 
        } else { 
            //echo "Error! " . $FILE[$fileInput]['error'] . " --- " . $FILE[$fileInput]['tmp_name'] ." %%% " . $file . "(" . $fileSize . " KB)";
        }
    }
    return $temp_dir;
    //return $fileInfo;
}

function saveVideo( $videoInfo ) {
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <start>
    global $dbConn;
	$params  = array();  
	$params2 = array();  
	$params3 = array();  

    $image_video = (strstr($videoInfo['type'], 'image') == null) ? 'v' : 'i';
    $published = ($image_video == 'i') ? MEDIA_PROCESSED : MEDIA_UPLOADED;
    $code = md5($videoInfo['name']);

    $city_id = intval($videoInfo['cityid']);
    $trip_id = ( isset($videoInfo['trip_id']) ) ? intval($videoInfo['trip_id']): 0;
    $location_id = ( isset($videoInfo['location_id']) ) ? intval($videoInfo['location_id']):0;
    $country_code = is_null($videoInfo['country']) ? '' : "{$videoInfo['country']}";
    $country_code = ($country_code != 'ZZ') ? $country_code : ''; //in case of private video country is not set
    $category_id = intval($videoInfo['category']);

    $videoInfo['title'] = xss_sanitize($videoInfo['title']);
    $videoInfo['description'] = ($videoInfo['description']!='')? xss_sanitize($videoInfo['description']):'';

    $longitude = ( isset($videoInfo['longitude']) ) ? doubleval($videoInfo['longitude']):0;
    $latitude = ( isset($videoInfo['lattitude']) ) ? doubleval($videoInfo['lattitude']):0;

    $desc_linked = db_sanitize(seoHyperlinkText(stripslashes($videoInfo['description'])));
    $q_add_video = "INSERT INTO `cms_videos` ( `name` ,`fullpath`, `code`, `relativepath` ,`type` ,`country`, `pdate`, `userid`, `dimension`, `duration`, `title`, `description`, `category`, `placetakenat`, `keywords` , `is_public`, lattitude,longitude,`cityid`,`cityname`,`location_id`,`trip_id`, `published`,`image_video`,`channelid`,`link_ts`,`description_linked` ) VALUES ( :Name,:Fullpath, :Code, :Relativepath, :Type, :Country_code, NOW(), :Userid, :Dimension,:Duration, :Title, :Description,:Category_id ,:Placetakenat, :Keywords,:Is_public, :Latitude ,:Longitude,:Cty_id, :Cityname, :Location_id ,:Trip_id, :Published, :Image_video,:Channelid,NOW(),:Dsc_linked )";						
    
    $params[] = array( "key" => ":Name", "value" =>$videoInfo['name']);
    $params[] = array( "key" => ":Fullpath", "value" =>$videoInfo['fullpath']);
    $params[] = array( "key" => ":Code", "value" =>$code);
    $params[] = array( "key" => ":Relativepath", "value" =>$videoInfo['relativepath']);
    $params[] = array( "key" => ":Type", "value" =>$videoInfo['type']);
    $params[] = array( "key" => ":Country_code", "value" =>$country_code);
    $params[] = array( "key" => ":Userid", "value" =>$videoInfo['userid']);
    $params[] = array( "key" => ":Dimension", "value" =>$videoInfo['dimension']);
    $params[] = array( "key" => ":Duration", "value" =>$videoInfo['duration']);
    $params[] = array( "key" => ":Title", "value" =>$videoInfo['title']);
    $params[] = array( "key" => ":Description", "value" =>$videoInfo['description']);
    $params[] = array( "key" => ":Category_id", "value" =>$category_id);
    $params[] = array( "key" => ":Placetakenat", "value" =>$videoInfo['placetakenat']);
    $params[] = array( "key" => ":Keywords", "value" =>$videoInfo['keywords'] );
    $params[] = array( "key" => ":Is_public", "value" =>$videoInfo['is_public']);
    $params[] = array( "key" => ":Latitude", "value" =>$latitude);
    $params[] = array( "key" => ":Longitude", "value" =>$longitude);
    $params[] = array( "key" => ":Cty_id", "value" =>$city_id);
    $cityname = ( isset($videoInfo['cityname']) ) ? $videoInfo['cityname']:'';
    $params[] = array( "key" => ":Cityname", "value" =>$cityname);
    $params[] = array( "key" => ":Location_id", "value" =>$location_id);
    $params[] = array( "key" => ":Trip_id", "value" =>$trip_id);
    $params[] = array( "key" => ":Published", "value" =>$published);
    $params[] = array( "key" => ":Image_video", "value" =>$image_video);
    $channelid = ( isset($videoInfo['channelid']) ) ? $videoInfo['channelid']:0;
    $params[] = array( "key" => ":Channelid", "value" =>$channelid);
    $params[] = array( "key" => ":Dsc_linked", "value" =>$desc_linked);

    queryAdd($videoInfo['cityname']);

    $userId = userGetID();
    
    $insert = $dbConn->prepare($q_add_video);
    PDO_BIND_PARAM($insert,$params);
    $r_add_video = $insert->execute();
    if (!$r_add_video) return false;
    $vid =$dbConn->lastInsertId();
    
    $hashids = tt_global_get('hashids');
    $hashed_id = $hashids->encode($vid);
    $hash_query = "UPDATE cms_videos SET hash_id = :Hashed_id WHERE id = :Vid";
	$params2[] = array( "key" => ":Hashed_id",
                             "value" =>$hashed_id);
	$params2[] = array( "key" => ":Vid",
                             "value" =>$vid);
    $update = $dbConn->prepare($hash_query);
    PDO_BIND_PARAM($update,$params2);
    $update->execute();

    $words = $videoInfo['title'] . ' ' . $videoInfo['description'] . ' ' . $videoInfo['keywords'] . ' ' . $videoInfo['placetakenat'];

    queryAddRegular($words);

    $videoInfo['id'] = $vid;
    $video_url = videoToURL($videoInfo);
    $query   = "UPDATE cms_videos SET video_url=:Video_url WHERE id=:Vid";
	$params3[] = array( "key" => ":Video_url",
                             "value" =>$video_url);
	$params3[] = array( "key" => ":Vid",
                             "value" =>$vid);
    $update2 = $dbConn->prepare($query);
	PDO_BIND_PARAM($update2,$params3);
    $update2->execute();

    //add the upload to the newsfeed
    $timequery=$videoInfo['timequery'];
    newsfeedAdd($videoInfo['userid'], $vid, SOCIAL_ACTION_UPLOAD, $timequery, SOCIAL_ENTITY_MEDIA, USER_PRIVACY_PUBLIC, $videoInfo['channelid']);

    if ($videoInfo['catalog_id'] != 0) {

        userCatalogAddMedia($videoInfo['userid'], $vid, $videoInfo['catalog_id']);

        if ($videoInfo['default_catalog_icon'] == 1) {
            userCatalogDefaultMediaSet($videoInfo['catalog_id'], $vid);
        }
    }

    return $vid;
}

function videoDetails($videoConverter, $videoFile) {

    //$videoFile = str_replace( '/', '\\', $videoFile );
    $ob = ob_get_contents();
    ob_clean();
    $minfo = mediaFileInfo($videoFile);
    $width = mediaFileWidth($minfo);
    $height = mediaFileHeight($minfo);
    list($totalDuration, $durationString) = mediaFileDuration($minfo);

    echo $ob;

    return $durationString . '|' . $width . '|' . $height;
}

/**
 * gets the rotastion of a video file
 * @param string $videoFile the path to the videofile
 * @return float the rotation of the video file
 */
// CODE NOT USED - commented by KHADRA
//function videoGetRotation($videoFile) {
//    global $CONFIG;
//    $mediaInfo = dirname($CONFIG['video'] ['videoCoverter']) . '/MediaInfo.exe';
//    $ob = ob_get_contents();
//    ob_clean();
//    system("$mediaInfo -f \"$videoFile\" 2>&1", $o);
//    ob_clean();
//    echo $ob;
//    $output = preg_replace('/\\s+/', ' ', $output);
//    preg_match('/Rotation : \d+(?:\.\d+)?/', $output, $rotation);
//    $vals = explode(' ', $rotation[0]);
//    $rot = $vals[count($vals) - 1];
//    return floatval($rot);
//}

/**
 * gets the resultion closest to the standar resolutions
 * @param integer $width input width
 * @param integer $height input height
 * @return array contains <b>width</b> <b>height</b>
 */
function getClosestResolution($width, $height) {
    $widthArray = array(430, 640, 860, 1280, 1920);
    $heightArray = array(240, 360, 480, 720, 1080);

    $i = count($widthArray) - 1;

    if (($width > $widthArray[$i]) && ($height > $heightArray[$i])) {
        return array($widthArray[$i], $heightArray[$i]);
    }

    while ($i >= 0) {
        if (($width <= $widthArray[$i]) && ($height <= $heightArray[$i])) {
            return array($widthArray[$i], $heightArray[$i]);
        }
        $i--;
    }
    return array($widthArray[0], $heightArray[0]);
}

/**
 * copmutes the ffmpeg crop variable need to resize an input image to a thumbnail
 * @param integer $width the original width
 * @param integer $height the original height
 * @param integer $thumbWidth the thumbs's width
 * @param integer $thumbHeight the thumbs's height
 * @return string the ffpmeg crop variable 
 */
function cropCompute($width, $height, $thumbWidth, $thumbHeight) {

    $ar_in = floatval($width) / floatval($height);
    $ar_thumb = floatval($thumbWidth) / floatval($thumbHeight);

    if ($ar_in < $ar_thumb) {
        $new_width = $thumbWidth;
        $new_height = floor($height * ( $thumbWidth / $width ));

        $hpad = 0;
        $vpad = intval($new_height - $thumbHeight);
        $vpad = intval($vpad * ( $width / $thumbWidth ));
    } else {
        $new_height = $thumbHeight;
        $new_width = floor($width * ( $thumbHeight / $height ));

        $vpad = 0;
        $hpad = intval($new_width - $thumbWidth);
        $hpad = intval($hpad * ( $height / $thumbHeight ));
    }

    $vpad = abs($vpad);
    $hpad = abs($hpad);

    $crop = ' -vf crop=';
    if ($hpad == 0) {
        $crop .= 'in_w:';
    } else {
        $crop .= "in_w-$hpad:";
    }
    if ($vpad == 0) {
        $crop .= 'in_h';
    } else {
        $crop .= "in_h-$vpad";
    }

    return $crop;
}

/**
 * gets the info of a media file as an array
 * @global array $CONFIG
 * @param string $filename the media file to detect
 * @return array
 */
function mediaFileInfo($filename) {
    global $CONFIG;

    $in_path = $filename;
    if (!file_exists($in_path)) {
        //try absolute
        $in_path = $CONFIG['server']['root'] . $in_path;
    }

    $ffprobe = $CONFIG ['video'] ['videoCoverterFolder'] . 'ffprobe';
    $cmd = "$ffprobe -loglevel quiet -show_format -show_streams -print_format json " . escapeshellarg($in_path);
    $out = shell_exec($cmd);
    $ret = json_decode($out, true);

    //TTDebug(DEBUG_TYPE_MEDIA, DEBUG_LVL_INFO, "Command: " . $cmd );
    //TTDebug(DEBUG_TYPE_MEDIA, DEBUG_LVL_INFO, "Detected: " . $filename . $out . print_r($ret,true));

    return $ret;
}

/**
 * gets the duration of the media file from its info array
 * @param array $mediFileInfo the media's Info returned from a call to mediaFileInfo
 * @return array
 */
function mediaFileDuration($mediFileInfo) {
    //mail('charbel@paravision.org','test', print_r($mediFileInfo, 1));
    //$duration = floor($mediFileInfo['streams'][0]['duration']);
    $duration = floor($mediFileInfo['format']['duration']);

    $duration_string = gmdate('H:i:s', $duration);

    return array($duration, $duration_string);
}

/**
 * gets the dimensions of the media file from its info array
 * @param array $mediFileInfo the media's Info returned from a call to mediaFileInfo
 * @return array
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function mediaFileDimensions($mediFileInfo) {
//    return array(intval($mediFileInfo['streams'][0]['width']), intval($mediFileInfo['streams'][0]['height']));
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>

/**
 * gets the width of the media file from its info array
 * @param array $mediFileInfo the media's Info returned from a call to mediaFileInfo
 * @return integer
 */
function mediaFileWidth($mediFileInfo) {
    return intval($mediFileInfo['streams'][0]['width']);
}

/**
 * gets the height of the media file from its info array
 * @param array $mediFileInfo the media's Info returned from a call to mediaFileInfo
 * @return integer
 */
function mediaFileHeight($mediFileInfo) {
    return intval($mediFileInfo['streams'][0]['height']);
}
/**
 * auto rotate image
 */
function mediaRotateImage($whereto) {
    $check = exif_read_data($whereto);
//    debug($check);
    if($check){ 
        $imageOrientation=0;
        $rotateImage=0;
        if( isset($check['Orientation']) ){
            $orientation = $check['Orientation'];
            if (6 == $orientation) {
                $rotateImage = 270;
                $imageOrientation = 1;
            } elseif (3 == $orientation) {
                $rotateImage = 180;
                $imageOrientation = 1;
            } elseif (8 == $orientation) {
                $rotateImage = 90;
                $imageOrientation = 1;
            }
        }
        if($imageOrientation==1){
            $source = imagecreatefromjpeg($whereto);
            $rotate = imagerotate($source,$rotateImage,0);
            $extension = ShowFileExtension( $whereto );
            if ($extension == "gif") {
                imagegif($rotate,$whereto);
            }else if ($extension == "png") {
                imagepng($rotate,$whereto);
            }else{
                imagejpeg($rotate,$whereto);
            }
            imagedestroy($source);
            imagedestroy($rotate);
        }
    }
}
/**
 * creates a thumbnail from a video
 * @param type $videoConverter
 * @param type $videoFile
 * @param type $videoPath
 * @param type $videoCode
 * @param type $w
 * @param type $h 
 */
function videoThumbnailCreate($videoConverter, $videoFile, $videoPath, $videoCode, $w, $h) {
    ob_start();
    global $CONFIG;

    //getting video duration
    $minfo = mediaFileInfo($videoFile);
    $width = mediaFileWidth($minfo);
    $height = mediaFileHeight($minfo);
    list($totalDuration, $durationString) = mediaFileDuration($minfo);

    $thumbWidth = $w;
    $thumbHeight = $h;

    $crop = cropCompute($width, $height, $thumbWidth, $thumbHeight);
    $thumbnailSize = "{$thumbWidth}x{$thumbHeight}";


    $biggest_size = getClosestResolution($width, $height);
    $thumbWidth2 = $biggest_size[0];
    $thumbHeight2 = $biggest_size[1];
    $crop2 = cropCompute($width, $height, $thumbWidth2, $thumbHeight2);
    $thumbnailSize2 = "{$thumbWidth2}x{$thumbHeight2}";

    $crop3 = cropCompute($width, $height, MEDIA_NEWSFEED_WIDTH, MEDIA_NEWSFEED_HEIGHT);
    $thumbnailSize3 = MEDIA_NEWSFEED_WIDTH . "x" . MEDIA_NEWSFEED_HEIGHT;
    $crop4 = cropCompute($width, $height, 136, 76);
    $thumbnailSize4 = 136 . "x" . 76;
    $crop5 = cropCompute($width, $height, 237, 134);
    $thumbnailSize5 = 237 . "x" . 134;

    $path_parts = pathinfo($videoPath . $videoFile);
    $videoname = $path_parts['filename'];

    for ($t = 1; $t <= 3; $t++) {
        /*$thumbnail = $videoPath . $videoCode . "_" . $t . "_" . $videoname . ".jpg";
        $thumbnail2 = $videoPath . 'large_' . $videoCode . "_" . $t . "_" . $videoname . ".jpg";
        $thumbnail3 = $videoPath . 'small_' . $videoCode . "_" . $t . "_" . $videoname . ".jpg";
        $thumbnail4 = $videoPath . 'xsmall_' . $videoCode . "_" . $t . "_" . $videoname . ".jpg";
        $thumbnail5 = $videoPath . 'thumb_' . $videoCode . "_" . $t . "_" . $videoname . ".jpg";*/
        
        
        $thumbnail = $videoPath ."_". $videoname . "_". $videoCode . "_" . $t . "_.jpg";
        $thumbnail2 = $videoPath . 'large__' .$videoname. "_". $videoCode . "_" . $t . "_.jpg";
        $thumbnail3 = $videoPath . 'small__' .$videoname. "_". $videoCode . "_" . $t . "_.jpg";
        $thumbnail4 = $videoPath . 'xsmall__' .$videoname. "_". $videoCode . "_" . $t . "_.jpg";
        $thumbnail5 = $videoPath . 'thumb__' .$videoname. "_". $videoCode . "_" . $t . "_.jpg";


        //start taking screenshots after 2 seconds incase video begins with black
        $offset = 2;
        if ($t == 1) {
            $start = $offset;
            $end = intval($totalDuration / 3);
        } else if ($t == 2) {
            $start = intval($totalDuration / 3);
            $end = intval(2 * $totalDuration / 3);
        } else {
            $start = intval(2 * $totalDuration / 3);
            $end = intval($totalDuration);
        }
        $interval = rand($start, $end);

        $cmd = "$videoConverter -ss $interval -i \"$videoFile\" -vframes 1 $crop -s $thumbnailSize -y \"$thumbnail\"";
        TTDebug(DEBUG_TYPE_MEDIA, DEBUG_LVL_INFO, $cmd);
        system($cmd, $o);

        $cmd = "$videoConverter -ss $interval -i \"$videoFile\" -vframes 1 $crop2 -s $thumbnailSize2 -y \"$thumbnail2\"";
        TTDebug(DEBUG_TYPE_MEDIA, DEBUG_LVL_INFO, $cmd);
        system($cmd, $o);

        $cmd = "$videoConverter -ss $interval -i \"$videoFile\" -vframes 1 $crop3 -s $thumbnailSize3 -y \"$thumbnail3\"";
        TTDebug(DEBUG_TYPE_MEDIA, DEBUG_LVL_INFO, $cmd);
        system($cmd, $o);

        $cmd = "$videoConverter -ss $interval -i \"$videoFile\" -vframes 1 $crop4 -s $thumbnailSize4 -y \"$thumbnail4\"";
        TTDebug(DEBUG_TYPE_MEDIA, DEBUG_LVL_INFO, $cmd);
        system($cmd, $o);
        $cmd = "$videoConverter -ss $interval -i \"$videoFile\" -vframes 1 $crop5 -s $thumbnailSize5 -y \"$thumbnail5\"";
        TTDebug(DEBUG_TYPE_MEDIA, DEBUG_LVL_INFO, $cmd);
        system($cmd, $o);
    }

    ob_clean();
    return ($o == 0);
}
function videoThumbnailCreatePost($videoConverter, $videoFile, $videoPath, $videoCode, $w, $h) {
    ob_start();
    global $CONFIG;

    //getting video duration
    $minfo = mediaFileInfo($videoFile);
    $width = mediaFileWidth($minfo);
    $height = mediaFileHeight($minfo);
    list($totalDuration, $durationString) = mediaFileDuration($minfo);

    $thumbWidth = $w;
    $thumbHeight = $h;

    $crop = cropCompute($width, $height, $thumbWidth, $thumbHeight);
    $thumbnailSize = "{$thumbWidth}x{$thumbHeight}";


    $biggest_size = getClosestResolution($width, $height);
    $thumbWidth2 = $biggest_size[0];
    $thumbHeight2 = $biggest_size[1];
    $crop2 = cropCompute($width, $height, $thumbWidth2, $thumbHeight2);
    $thumbnailSize2 = "{$thumbWidth2}x{$thumbHeight2}";

    $crop3 = cropCompute($width, $height, MEDIA_NEWSFEED_WIDTH, MEDIA_NEWSFEED_HEIGHT);
    $thumbnailSize3 = MEDIA_NEWSFEED_WIDTH . "x" . MEDIA_NEWSFEED_HEIGHT;
    $crop4 = cropCompute($width, $height, 136, 76);
    $thumbnailSize4 = 136 . "x" . 76;
    $crop5 = cropCompute($width, $height, 237, 134);
    $thumbnailSize5 = 237 . "x" . 134;

    $path_parts = pathinfo($videoPath . $videoFile);
    $videoname = $path_parts['filename'];

    for ($t = 1; $t <= 3; $t++) {
        $thumbnail = $videoPath . $videoCode . "_" . $t . "_" . $videoname . ".jpg";
        $thumbnail2 = $videoPath . 'large_' . $videoCode . "_" . $t . "_" . $videoname . ".jpg";
        $thumbnail3 = $videoPath . 'small_' . $videoCode . "_" . $t . "_" . $videoname . ".jpg";
        $thumbnail4 = $videoPath . 'xsmall_' . $videoCode . "_" . $t . "_" . $videoname . ".jpg";
        $thumbnail5 = $videoPath . 'thumb_' . $videoCode . "_" . $t . "_" . $videoname . ".jpg";

        //start taking screenshots after 2 seconds incase video begins with black
        $offset = 2;
        if ($t == 1) {
            $start = $offset;
            $end = intval($totalDuration / 3);
        } else if ($t == 2) {
            $start = intval($totalDuration / 3);
            $end = intval(2 * $totalDuration / 3);
        } else {
            $start = intval(2 * $totalDuration / 3);
            $end = intval($totalDuration);
        }
        $interval = rand($start, $end);

        $cmd = "$videoConverter -ss $interval -i \"$videoFile\" -vframes 1 $crop -s $thumbnailSize -y \"$thumbnail\"";
        TTDebug(DEBUG_TYPE_MEDIA, DEBUG_LVL_INFO, $cmd);
        system($cmd, $o);

        $cmd = "$videoConverter -ss $interval -i \"$videoFile\" -vframes 1 $crop2 -s $thumbnailSize2 -y \"$thumbnail2\"";
        TTDebug(DEBUG_TYPE_MEDIA, DEBUG_LVL_INFO, $cmd);
        system($cmd, $o);

        $cmd = "$videoConverter -ss $interval -i \"$videoFile\" -vframes 1 $crop3 -s $thumbnailSize3 -y \"$thumbnail3\"";
        TTDebug(DEBUG_TYPE_MEDIA, DEBUG_LVL_INFO, $cmd);
        system($cmd, $o);

        $cmd = "$videoConverter -ss $interval -i \"$videoFile\" -vframes 1 $crop4 -s $thumbnailSize4 -y \"$thumbnail4\"";
        TTDebug(DEBUG_TYPE_MEDIA, DEBUG_LVL_INFO, $cmd);
        system($cmd, $o);
        $cmd = "$videoConverter -ss $interval -i \"$videoFile\" -vframes 1 $crop5 -s $thumbnailSize5 -y \"$thumbnail5\"";
        TTDebug(DEBUG_TYPE_MEDIA, DEBUG_LVL_INFO, $cmd);
        system($cmd, $o);
    }

    ob_clean();
    return ($o == 0);
}
/**
 * gets image dimensions
 * @global array $CONFIG
 * @param string $in_path the path to the input
 * @return mixed false if couldnt detect dimensions or hash with dimensions
 */
function imageGetDimensions($in_path) {
    global $CONFIG;

    $op = ob_get_contents();
    ob_clean();

    $videoConverter = $CONFIG ['video']['videoCoverter'];
    //getting video duration
    //$in_path = $CONFIG['server']['root'] . '/' . $in_path;
    $cmd = "$videoConverter -i \"$in_path\" 2>&1";
//    echo $cmd;exit();
    system($cmd, $o);
    $originalSize = ob_get_contents();
    ob_clean();

    //retrive the video dimensions
    preg_match('/ (\d{2,4})x(\d{2,4})/', $originalSize, $matches);
//    print_r($originalSize);echo '<br>';
//    print_r($matches);exit();
    if (count($matches) < 3) {
        return false;
    } else {
        $ret = array();
        $ret['width'] = intval($matches[1]);
        $ret['height'] = intval($matches[2]);
        return $ret;
    }
}

/**
 * converts an image from one format to another
 * @global array $CONFIG
 * @param type $file_in
 * @param type $file_out 
 */
function convertImage($file_in, $file_out) {
    global $CONFIG;

    $op = ob_get_contents();
    ob_clean();

    $videoConverter = $CONFIG ['video']['videoCoverter'];
    $cmd = "$videoConverter -i \"$file_in\" -q 1 -y \"$file_out\" 2>&1";
    system($cmd, $o);
    ob_clean();

    echo $op;
}

/**
 * creates a thumbnail from a photo. DEPRECATED
 * @param string $in_path
 * @param string $out_path
 * @param integer $w
 * @param integer $h 
 * @param integer $x
 * @param integer $y 
 * @todo repalce all calls of this function with <b>mediaSubsample</b>
 */
function photoThumbnailCreate($in_path, $out_path, $w, $h, $x = null, $y = null) {
    return mediaSubsample(array('in_path' => $in_path, 'out_path' => $out_path, 'w' => $w, 'h' => $h, 'x' => $x, 'y' => $y));
}

function createBagItemThumbs($filename,$thumbpathOriginal, $coord_x, $coord_y, $coord_w, $coord_h,$namepic='bagthumbs',$newpath='',$returnpath='') {
    global $CONFIG;
    if($newpath=='') $newpath=$thumbpathOriginal;
    if($returnpath=='') $returnpath=$newpath;
    $path = $CONFIG['server']['root'];
    if($namepic!=''){
        $savedfilename = $namepic."_" . $filename;
    }else{
        $savedfilename = $filename;
    }
    $filePath = $path . '' . $thumbpathOriginal . $filename;
    $savedThumbPath = $path . '' . $newpath . $savedfilename;            
    if (!file_exists($savedThumbPath) || $namepic=='') {
        mediaSubsample(array('in_path' => $filePath, 'out_path' => $savedThumbPath, 'w' => $coord_w, 'h' => $coord_h, 'x' => $coord_x, 'y' => $coord_y));
    }
    return '/'.$returnpath . $savedfilename;
}

/**
 * creates a subsampled image of an inut media file
 * @param array $sampling_options the sampling options<br/>
 * options include:<br/>
 * <b>in_path</b>: string. the input media file<br/>
 * <b>out_path</b>: string. the output media file<br/>
 * <b>w</b>: integer. the width of the outpu. null means autodetectt. default null<br/>
 * <b>h</b>: integer. the height of the output. null means autodetectt. default null<br/>
 * <b>x</b>: integer. the horizontal position of the input to start sampling at. default null.<br/>
 * <b>y</b>: integer. the vertical position of the input to start sampling at. default null.<br/>
 * <b>keep_ratio</b> boolean. specified if the original ratio is to be preserved in which case the result will fit inside <b>w</b> OR <b>h</b> .default false.<br/>
 * <b>quality</b>. integer. quality of output 1-100. default 100.
 */
function mediaSubsample($sampling_options) {
    global $CONFIG;

    $default_options = array(
        'in_path' => null,
        'out_path' => null,
        'w' => null,
        'h' => null,
        'x' => null,
        'y' => null,
        'keep_ratio' => false,
        'quality' => 100
    );

    $options = array_merge($default_options, $sampling_options);

    $in_path = $options['in_path'];
    $out_path = $options['out_path'];

    if (!$in_path || !$out_path){
            return false;
    }
    ob_start();
    //$op = ob_get_contents();
    //ob_clean();
    $videoConverter = $CONFIG ['video']['videoCoverter'];

    //getting image dimensions duration
    $minfo = mediaFileInfo($in_path);
    $width = mediaFileWidth($minfo);
    $height = mediaFileHeight($minfo);

    $w = $options['w'];
    $h = $options['h'];
    $x = $options['x'];
    $y = $options['y'];
    $keep_ratio = $options['keep_ratio'];

    if (!$w && !$h)
        return false;

    if ($keep_ratio) {

        if (is_null($h)) {
            $thumbWidth = $w;
            $thumbHeight = intval($height * $w / $width);
        } else if (is_null($w)) {
            $thumbHeight = $h;
            $thumbWidth = intval($width * $h / $height);
        } else {

            $thumbWidth = $w;
            $thumbHeight = intval($height * $w / $width);

            if ($thumbHeight > $h) {
                $thumbHeight = $h;
                $thumbWidth = intval($width * $h / $height);
            }
        }
    } else if (!is_null($h) && !is_null($w)) {
        $thumbWidth = $w;
        $thumbHeight = $h;
    } else {
        if (is_null($h)) {
            if ($width > $w) {
                $thumbWidth = $w;
                $thumbHeight = intval($height * $w / $width);
            } else {
                $thumbWidth = $width;
                $thumbHeight = intval($height * $width / $width); //1
            }
        } else {
            if ($height > $h) {
                $thumbHeight = $h;
                $thumbWidth = intval($width * $h / $height);
            } else {
                $thumbHeight = $height;
                $thumbWidth = intval($width * $height / $height); //1
            }
        }
    }

    if (is_null($x) || is_null($y)) {
        $crop = cropCompute($width, $height, $thumbWidth, $thumbHeight);
    } else {
        $crop = ' -vf crop=';
        //$crop_y = intval( abs($y) * $height/$thumbHeight );
        //$crop_x = intval( abs($x) * $width/$thumbWidth );
        $crop_y = abs($y);
        $crop_x = abs($x);

        $scale1 = $w / $width;
        $scale2 = $h / $height;
        $scale = $scale1;
        if($scale2>$scale) $scale=$scale2;
        $crop_width = $thumbWidth / $scale; //intval($w * $thumbWidth/$width);
        $crop_height = $thumbHeight / $scale; //intval($h * $thumbHeight/$height);
        $crop .= "$crop_width:$crop_height:$crop_x:$crop_y";
    }

    $thumbnailSize = "{$thumbWidth}x{$thumbHeight}";

    $qscale = 1 + (100 - $options['quality']) / 4;

    $cmd = "$videoConverter -i \"$in_path\" -vframes 1 $crop -s $thumbnailSize -crf $qscale -y \"$out_path\" 2>&1"; // removed by khadra (-q:v )  
    
    //mail('rudy.sleiman@gmail.com', 'img conv', $cmd);
//    echo $cmd;exit();
    //file_put_contents("services/converter_service.log", date('r') . ' - ' . $cmd . PHP_EOL, FILE_APPEND);
    @system($cmd, $o);
    $minfo = mediaFileInfo($out_path);
    $width = mediaFileWidth($minfo);
    $height = mediaFileHeight($minfo);
    smart_resize_image( $out_path, "", $width, $height, false, $out_path , false, false, 85 );
    ob_clean();
    return ($o == 0);
}

/**
 * creates a subsampled image of an inut media file
 * @param array $sampling_options the sampling options<br/>
 * options include:<br/>
 * <b>in_path</b>: string. the input media file<br/>
 * <b>out_path</b>: string. the output media file<br/>
 * <b>w</b>: integer. the width of the outpu. null means autodetectt. default null<br/>
 * <b>h</b>: integer. the height of the output. null means autodetectt. default null<br/>
 * <b>x</b>: integer. the horizontal position of the input to start sampling at. default null.<br/>
 * <b>y</b>: integer. the vertical position of the input to start sampling at. default null.<br/>
 * <b>keep_ratio</b> boolean. specified if the original ratio is to be preserved in which case the result will fit inside <b>w</b> OR <b>h</b> .default false.<br/>
 * <b>quality</b>. integer. quality of output 1-100. default 100.
 * return out_path if done or false if not
 */
function createMediaSubsample($sampling_options) {

    global $CONFIG;

    $default_options = array(
        'in_path' => null,
        'out_path' => null,
        'w' => null,
        'h' => null,
        'x' => null,
        'y' => null,
        'keep_ratio' => false,
        'quality' => 100
    );

    $options = array_merge($default_options, $sampling_options);

    $in_path = $options['in_path'];
    $out_path = $options['out_path'];
    if (!$in_path || !$out_path)
        return false;

//	$op = ob_get_contents();
    //ob_clean();
    $videoConverter = $CONFIG ['video']['videoCoverter'];

    //getting image dimensions duration
    $minfo = mediaFileInfo($in_path);
    $width = mediaFileWidth($minfo);
    $height = mediaFileHeight($minfo);

    $w = $options['w'];
    $h = $options['h'];
    $x = $options['x'];
    $y = $options['y'];
    $keep_ratio = $options['keep_ratio'];

    if (!$w && !$h)
        return false;

    if ($keep_ratio) {

        if (is_null($h)) {
            $thumbWidth = $w;
            $thumbHeight = intval($height * $w / $width);
        } else if (is_null($w)) {
            $thumbHeight = $h;
            $thumbWidth = intval($width * $h / $height);
        } else {

            $thumbWidth = $w;
            $thumbHeight = intval($height * $w / $width);

            if ($thumbHeight > $h) {
                $thumbHeight = $h;
                $thumbWidth = intval($width * $h / $height);
            }
        }
    } else if (!is_null($h) && !is_null($w)) {
        $thumbWidth = $w;
        $thumbHeight = $h;
    } else {
        if (is_null($h)) {
            if ($width > $w) {
                $thumbWidth = $w;
                $thumbHeight = intval($height * $w / $width);
            } else {
                $thumbWidth = $width;
                $thumbHeight = intval($height * $width / $width); //1
            }
        } else {
            if ($height > $h) {
                $thumbHeight = $h;
                $thumbWidth = intval($width * $h / $height);
            } else {
                $thumbHeight = $height;
                $thumbWidth = intval($width * $height / $height); //1
            }
        }
    }

    if (is_null($x) || is_null($y)) {
        $crop = cropCompute($width, $height, $thumbWidth, $thumbHeight);
    } else {
        $crop = ' -vf crop=';
        $crop_y = intval(abs($y) * $height / $thumbHeight);
        $crop_x = intval(abs($x) * $width / $thumbWidth);

        $scale = $w / $width;
        $crop_width = $thumbWidth / $scale; //intval($w * $thumbWidth/$width);
        $crop_height = $thumbHeight / $scale; //intval($h * $thumbHeight/$height);
        $crop .= "$crop_width:$crop_height:$crop_x:$crop_y";
    }

    $thumbnailSize = "{$thumbWidth}x{$thumbHeight}";

    $qscale = 1 + (100 - $options['quality']) / 4;

    $cmd = "$videoConverter -i \"$in_path\" -vframes 1 $crop -s $thumbnailSize -crf $qscale -y \"$out_path\" 2>&1"; // removed by khadra (-q:v )
    //mail('charbel@paravision.org', 'img conv', $cmd);
    exec($cmd, $rt, $o);
    //ob_clean();
    //TTDebug(DEBUG_TYPE_MEDIA, DEBUG_LVL_INFO, $cmd);
//	echo $op;
//        return '--------------->'.print_r($rt,true).']-------------------------';
    if ($o == 0) {
        return $out_path;
    } else {
        return false;
    }
}

function createThumbnailPost($videoConverter, $videoFile, $videoPath, $videoCode) {
    $thumbWidth = 310;
    $thumbHeight = 172;
    videoThumbnailCreatePost($videoConverter, $videoFile, $videoPath, $videoCode, $thumbWidth, $thumbHeight);
}
function createThumbnail($videoConverter, $videoFile, $videoPath, $videoCode) {

    $thumbWidth = 310;
    $thumbHeight = 172;
    videoThumbnailCreate($videoConverter, $videoFile, $videoPath, $videoCode, $thumbWidth, $thumbHeight);
}

function createMobileThumbnail($videoConverter, $videoFile, $videoPath, $videoCode) {

    $thumbWidth = 310;
    $thumbHeight = 172;
    videoThumbnailCreate($videoConverter, $videoFile, $videoPath, $videoCode, $thumbWidth, $thumbHeight);
}

/**
 * resize the uploaded image to the viewer size
 * @param type $imagePath
 */
function resizeUploadedImage($imagePath, $topath) {
    $width = 1000;
    //$height = 375;
    $height = null;
    photoThumbnailCreate($imagePath, $topath, $width, $height);
}

/**
 * resize the uploaded image to the viewer size
 * @param type $imagePath
 */
function resizeUploadedImage2($imagePath, $topath) {
    $width = 700;
    $height = 375;
    //$height = null;
    photoThumbnailCreate($imagePath, $topath, $width, $height);
}

/**
 * resize the uploaded image to the viewer size
 * @param type $imagePath
 */
function resizeUploadedImage3($imagePath, $topath) {
    /* $width = null;
      $height = 230; */
    $width = MEDIA_NEWSFEED_WIDTH;
    $height = MEDIA_NEWSFEED_HEIGHT;
    photoThumbnailCreate($imagePath, $topath, $width, $height);
}

/**
 * resize the uploaded image to the viewer size
 * @param type $imagePath
 */
function resizeUploadedImage4($imagePath, $topath) {
    $width = 136;
    $height = 76;
    //$height = null;
    photoThumbnailCreate($imagePath, $topath, $width, $height);
}

/**
 * resize the uploaded image to the viewer size
 * @param type $imagePath
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function resizeUploadedImage5($imagePath, $topath) {
//    $width = 237;
//    $height = 134;
//    //$height = null;
//    photoThumbnailCreate($imagePath, $topath, $width, $height);
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>

/**
 * creates a thumbnail
 * @param integer $imagePath source image
 * @param integer $thumbPath destination image
 * @return boolean 
 */
function createThumbnailFromImage($imagePath, $thumbPath, $x = null, $y = null, $thumbWidth = 237, $thumbHeight = 134) {

    /* $thumbWidth = 160;
      $thumbHeight = 89; */
    photoThumbnailCreate($imagePath, $thumbPath, $thumbWidth, $thumbHeight, $x, $y);
    /*
      if (stristr($imagePath, '.bmp') != null) {
      $img = @imagecreatefromwbmp($imagePath);
      } else if (stristr($imagePath, '.gif') != null) {
      $img = @imagecreatefromgif($imagePath);
      } else if (stristr($imagePath, '.png') != null) {
      $img = @imagecreatefrompng($imagePath);
      } else if (stristr($imagePath, '.jpg') != null || stristr($imagePath, '.jpeg') != null) {
      $img = @imagecreatefromjpeg($imagePath);
      } else {
      return false;
      }

      if( !$img ) return false;

      $width = imagesx( $img );
      $height = imagesy( $img );

      // calculate thumbnail size
      $new_width = $thumbWidth;
      $new_height = floor( $height * ( $thumbWidth / $width ) );

      if($new_height > $thumbHeight){
      $new_height = $thumbHeight;;
      $new_width = floor( $width * ( $thumbHeight / $height ) );
      }

      $tmp_img = imagecreatetruecolor( $thumbWidth, $thumbHeight );

      $diff_x = ($thumbWidth - $new_width)/2;
      $diff_y = ($thumbHeight - $new_height)/2;
      imagecopyresized( $tmp_img, $img, $diff_x , $diff_y, 0, 0, $new_width, $new_height, $width, $height );

      @unlink($thumbPath);

      // save thumbnail into a file
      imagejpeg( $tmp_img, $thumbPath );

      return true;
     */
}

function createThumbnailFromImageDynamic($imagePath, $thumbPath, $thumbWidth = 160, $thumbHeight = 89) {

    return photoThumbnailCreate($imagePath, $thumbPath, $thumbWidth, $thumbHeight);
    /*
      if (stristr($imagePath, '.bmp') != null) {
      $img = @imagecreatefromwbmp($imagePath);
      } else if (stristr($imagePath, '.gif') != null) {
      $img = @imagecreatefromgif($imagePath);
      } else if (stristr($imagePath, '.png') != null) {
      $img = @imagecreatefrompng($imagePath);
      } else if (stristr($imagePath, '.jpg') != null || stristr($imagePath, '.jpeg') != null) {
      $img = @imagecreatefromjpeg($imagePath);
      } else {
      return false;
      }

      if( !$img ) return false;

      $width = imagesx( $img );
      $height = imagesy( $img );

      // calculate thumbnail size
      $new_width = $thumbWidth;
      $new_height = floor( $height * ( $thumbWidth / $width ) );

      if($new_height > $thumbHeight){
      $new_height = $thumbHeight;;
      $new_width = floor( $width * ( $thumbHeight / $height ) );
      }

      $tmp_img = imagecreatetruecolor( $thumbWidth, $thumbHeight );

      $diff_x = ($thumbWidth - $new_width)/2;
      $diff_y = ($thumbHeight - $new_height)/2;
      imagecopyresized( $tmp_img, $img, $diff_x , $diff_y, 0, 0, $new_width, $new_height, $width, $height );

      @unlink($thumbPath);

      // save thumbnail into a file
      imagejpeg( $tmp_img, $thumbPath );

      return true; */
}

function videoGetFakePath($vinfo, $dim = '') {
    global $CONFIG;
    $videoPath = $CONFIG['video']['uploadPath'];
    $rpath = $vinfo['relativepath'];
    $fullpath = $vinfo['fullpath'];
    $name = $vinfo['name'];

    if ((($pos = strrpos($name, '.')) != false) && ($dim != '')) {
        $name = substr($name, 0, $pos);
        //$name = $name . '.flv';
        $name = $name . '.mp4';
    }
    $tpath = $videoPath . $rpath . $dim . $name;
    $fakerpath = str_replace('/', '', $rpath);
    $fakepath = $fullpath . $dim . $name;
    $tpath = $videoPath . $rpath . $dim . $name;
    return array('real' => $tpath, 'fake' => $fakepath);
}

function videoGetPath($vinfo, $dim = '') {
    global $CONFIG;
    $videoPath = $CONFIG['video']['uploadPath'];
    $rpath = $vinfo['relativepath'];
    $name = $vinfo['name'];
    $code = $vinfo['code'];

    if ((($pos = strrpos($name, '.')) != false) && ($dim != '')) {
        $name = substr($name, 0, $pos);
        //$name = $name . '.flv';
        $name = $name . '.mp4';
    }

    return $tpath = $videoPath . $rpath . $dim . $name;
}

function videoGetPostPath($vinfo, $dim = '') {
    global $CONFIG;

    $rpath = relativevideoGetPostPath($vinfo);
    $name = $vinfo['media_file'];
    
    if (( ($pos = strrpos($name, '.')) != false) && ($dim != '')) {
        $name = substr($name, 0, $pos);
        //$name = $name . '.flv';
        $name = $name . '.mp4';
    }

    return $tpath = $rpath . $dim . $name;
}

function videoGetInstantThumbPath($vid, $uid) {

    return 'tmp/' . $vid . $uid . '.jpg';
}

//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function postVideoGetInstantThumbPath2($vinfo) {
//    //return getPostThumbPath($vinfo);
//    $videoFile = relativevideoReturnPostPath($vinfo);
//    return $videoFile . '/thumb_' . $vinfo['id'];
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>

function videoGetInstantThumbPath2($vinfo) {
    //return videoReturnSrcLink($vinfo, 'thumb');
    $videoFile = videoGetPath($vinfo);
    return dirname($videoFile) . '/thumb_' . $vinfo['id'];
}

function videoGetInstantThumbPathPost($vinfo) {
    $videoFile = videoGetPostPath($vinfo);
    return dirname($videoFile) . '/thumb_' . $vinfo['id'];
}

function createInstantThumbnail($vid, $to, $Seconds) {

    //$videoFile = str_replace( '/', '\\', $videoFile );
    ob_start();

    global $CONFIG;
    $videoConverter = $CONFIG['video']['videoCoverter'];
    $root = $CONFIG ['server']['root'];
    $vinfo = getVideoInfo($vid);

    $videoFile = $root . videoGetPath($vinfo);
    $thumbFile = $root . $to;

    system("$videoConverter -i \"$in_path\" 2>&1", $o);
    $originalSize = ob_get_contents();
    //retrive the video dimensions
    preg_match('/ (\d{2,4})x(\d{2,4})/', $originalSize, $matches);
    $width = intval($matches[1]);
    $height = intval($matches[2]);

    $tw = 76;
    $th = 46;

    $thumbnailSize = "{$tw}x{$th}";

    $crop = cropCompute($width, $height, $tw, $th);

    $interval = $Seconds;

    $cmd = "$videoConverter -ss $interval -i \"$videoFile\" -vframes 1 $crop -s $thumbnailSize -y \"$thumbFile\"";
    //$cmd = "$videoConverter -i \"$videoFile\" -ss 00:00:$interval -f singlejpeg -t 00:00:01 -r 1 -s $thumbnailSize \"$thumbFile\"";
    system($cmd, $o);

    ob_clean();
}

function videoGetInfo($path) {
    global $CONFIG;
    $videoConverter = $CONFIG['video']['videoCoverter'];
    $root = $CONFIG ['server']['root'];
    system("$videoConverter -i \"$root/$path\" 2>&1", $o);
    $out = ob_get_contents();
    ob_clean();
    return $out;
}

/**
 * uses imagemagik converts an image to RGB pixel format. useful for converting from CMYK
 * @param string $in_image path to input (only useful if CMYK) image
 * @param string $out_image path to output RGB image
 */
function convertImageToRGB($in_image, $out_image) {
    ob_start();
    $cmd = "convert -colorspace RGB $in_image $out_image";
    system($cmd);
    ob_clean();
}

/**
 * processes a video into the required video format/resolutions
 * @global array $CONFIG
 * @param integer $vid the cms_videos id of the video that needs processing
 */
function convertVideo($vid) {

    //$videoFile = str_replace( '/', '\\', $videoFile );

    ob_start();

    global $CONFIG;
    $videoConverter = $CONFIG['video']['videoCoverter'];
    $root = $CONFIG ['server']['root'];
    $vinfo = getVideoInfo($vid);

    $videoFile = $root . videoGetPath($vinfo);
    $title = mysql_escape_string($vinfo['title']);
    $code = $vinfo['code'];

    $minfo = mediaFileInfo($videoFile);
    $width = mediaFileWidth($minfo);
    $height = mediaFileHeight($minfo);
    list($total_duration, $duration_string) = mediaFileDuration($minfo);

    $movieHeight = intval($height);
    $movieWidth = intval($width);

    $widthArray = array(430, 640, 860, 1280, 1920);
    $heightArray = array(240, 360, 480, 720, 1080);
    //$comp = array( 300 ,500, 900, 1000, 1500);
    //$comp = array( 300 ,500, 900, 2000, 3000);
    $comp = array(300, 500, 1000, 1500, 4000);

    $moov_size = intval(1.2 * 1024 * $total_duration); //1kbyte per sec

    $threads = 4;

    $i = 0;
    foreach ($heightArray as $h) {
        if (( $h <= $movieHeight ) || ($i == 0)) {
            $dimesions = $widthArray[$i] . 'x' . $h;
            $video = $root . videoGetPath($vinfo, $dimesions);

            $crop = cropCompute($width, $height, $widthArray[$i], $h);
            //-flags +4mv+trell
            //$cmd = "$videoConverter -i \"$videoFile\" -b {$comp[$i]}k -acodec libvo_aacenc -vcodec libx264 -mbd 1 -flags +mv4+aic -trellis 1 $crop -s $dimesions -metadata title=\"$title\" -y \"$video\"";
            //$cmd = "$videoConverter -i \"$videoFile\" -b {$comp[$i]}k -acodec libvo_aacenc -vcodec libx264 -mbd 1 -flags +mv4+aic -trellis 0 -coder 0 -bf 0 -subq 6 -refs 5 $crop -s $dimesions -metadata title=\"$title\" -y \"$video\"";			
            //$cmd = "$videoConverter -i \"$videoFile\" -b {$comp[$i]}k -acodec libvo_aacenc -vcodec libx264 -mbd 1 -flags +mv4+aic -trellis 0 -coder 0 -bf 0 -subq 6 -refs 5 -partitions +parti4x4+parti8x8+partp4x4+partp8x8 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -qmin 10 -qmax 51 -qdiff 4 -ac 1 -ar 16000 -r 13 -ab 32000 $crop -s $dimesions -metadata title=\"$title\" -y \"$video\"";
            //  last active one 
            // KHADRA: -moov_size $moov_size replaced by
            $cmd = "$videoConverter -i \"$videoFile\"  -pix_fmt yuv420p  -b:v {$comp[$i]}k -s $dimesions  -movflags +faststart -y \"$video\"";
            
            //$cmd = "$videoConverter -i \"$videoFile\" -b:v {$comp[$i]}k -acodec libvo_aacenc -vcodec libx264 -movflags +faststart  -vprofile high444 $crop -s $dimesions  -threads $threads -metadata title=\"$title\" -t $duration_string -ac 2 -y \"$video\"";
            //$cmd = "$videoConverter -i \"$videoFile\" -b:v {$comp[$i]}k -acodec libvo_aacenc -vcodec libx264  +faststart  -vprofile high10 $crop -s $dimesions  -threads $threads  -t $duration_string -ac 2 -y \"$video\"";
//-movflags empty_moov
            
            $logline = "cmd = $cmd";
            file_put_contents("converter_service.log", date('r') . ' - ' . $logline . PHP_EOL, FILE_APPEND);

            system($cmd, $o);
            //system("$videoConverter -i \"$videoFile\" -acodec aac -ab 32kb -vcodec mpeg4 -b 1200kb -mbd 1 -flags +4mv+trell -aic 1 -cmp 1 -subcmp 1 -s $dimesions -title \"$title\" \"$video\"", $o);
            //$ob = ob_get_contents();
            /*
              if( strstr($ob, 'reserved_moov_size is too small') != null){
              $moov_size = intval($moov_size * 1.1);
              $cmd = "$videoConverter -i \"$videoFile\" -b:v {$comp[$i]}k -acodec libvo_aacenc -vcodec libx264 -moov_size $moov_size -vprofile baseline $crop -s $dimesions  -threads $threads -metadata title=\"$title\" -t $duration_string -y \"$video\"";
              file_put_contents("converter_service.log", date('r') . ' - FAILED: NEW MOV - ' . $logline . PHP_EOL, FILE_APPEND);
              system($cmd, $o);
              }
             */

            if ($i == 0) {
                $screen_per_sec = '1';
                $screen_per_5sec = '1/5';
                $screen_per_min = '1/60';
                $screens = $screen_per_sec;
                $dest_dir = $root . '' . videoGetInstantThumbPath2($vinfo);
               
                rmdir_recursive($dest_dir);
                //system("rm -f " . $dest_dir);
                system("mkdir " . $dest_dir);
                //@mkdir($dest_dir);
                //@chmod($dest_dir, '777');
                $tw = 76;
                $th = 46;
                $thumbnailSize = "{$tw}x{$th}";
                $crop = cropCompute($widthArray[$i], $h, $tw, $th);
                $cmd = "$videoConverter -i \"$video\" -r $screens $crop -s $thumbnailSize -threads $threads -y $dest_dir/out%d.jpg";
                $logline = "cmd = $cmd";
                //file_put_contents("converter_service.log", $logline . "\n", FILE_APPEND);

                system($cmd, $o);
                $tw2 = 89;
                $th2 = 54;
                $thumbnailSize2 = "{$tw2}x{$th2}";
                $crop2 = cropCompute($widthArray[$i], $h, $tw2, $th2);
                $cmd2 = "$videoConverter -i \"$video\" -r $screens $crop2 -s $thumbnailSize2 -threads $threads -y $dest_dir/frame%d.jpg";
                $logline2 = "cmd = $cmd2";
                //file_put_contents("converter_service.log", $logline2 . "\n", FILE_APPEND);

                system($cmd2, $o);
                spriteImage($dest_dir . "/");
            }
        } else {
            break;
        }

        unset($dimesions);
        unset($video);

        $i++;
    } // end foreach

    ob_clean();
}
function rmdir_recursive($dir) {
    foreach(scandir($dir) as $file) {
        if ('.' === $file || '..' === $file) continue;
        if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
        else unlink("$dir/$file");
    }
    rmdir($dir);
}
/**
 * display the thumb for video
 * @param string $folder the path of the video's thumbnails
 * return a string to display all the thumbs of a video
 */
function displaySpriteImage($folder) {
    $toreturn = '';
    $nbImg = 60;
    $inImg = 0;
    $width = 89;
    $nbSmallImg = count(glob($folder . 'out*.jpg'));
    $nbBigImg = count(glob($folder . 'big*.jpg'));
    $reste = $nbSmallImg % $nbImg;
    for ($bigImg = 1; $bigImg <= $nbBigImg; $bigImg++) {
        $index = 0;
        if ($bigImg == $nbBigImg && $reste != '') {
            for ($i = $inImg + 1; $i <= $inImg + $reste; $i++) {
                $toreturn .='<div class="imageitem">
                <div class="thumb-pic-img" style="background:url(\'' . $folder . 'big' . $bigImg . '.jpg?no_cach='.rand(). '\') no-repeat ' . $index . 'px top;" rel="' . $i . '"></div>
                <div class="yellowborder"></div>
                </div>';
                $index -= $width;
            }
            $inImg = $inImg + $reste;
        } else {
            for ($i = $inImg + 1; $i <= $inImg + $nbImg; $i++) {
                $toreturn .='<div class="imageitem">
                <div class="thumb-pic-img" style="background:url(\'' . $folder . 'big' . $bigImg . '.jpg?no_cach='.rand(). '\') no-repeat ' . $index . 'px top;" rel="' . $i . '"></div>
                <div class="yellowborder"></div>
                </div>';
                $index -= $width;
            }
            $inImg = $inImg + $nbImg;
        }
    }
    return $toreturn;
}

/**
 * merge small images into big images
 * @param string $folder the path of the video's thumbnails
 */
function spriteImage($folder) {
    $nbOldBig = count(glob($folder . 'big*.jpg'));
    for ($t = 1; $t <= $nbOldBig; $t++) {
        unlink($folder . 'big' . $t . '.jpg');
    }
    $nbImg = 60;
    $width = 89;
    $height = 54;
    $totalwidth = $width * $nbImg;
    $lastBigImgwidth = $totalwidth;
    $totalheight = $height;
    $inImg = 0;
    $nbAllImg = count(glob($folder . 'frame*.jpg'));

    if ($nbAllImg > 0) {
        $nbOfBigImg = ceil($nbAllImg / $nbImg);
        $reste = $nbAllImg % $nbImg;
        if ($reste != 0) {
            $lastBigImgwidth = $reste * $width;
        }
        for ($bigImg = 1; $bigImg <= $nbOfBigImg; $bigImg++) {
            $index = 0;
            if ($bigImg == $nbOfBigImg && $reste != '') {
                $image = imagecreatetruecolor($lastBigImgwidth, $totalheight);
                for ($i = $inImg + 1; $i <= $inImg + $reste; $i++) {
                    $monster = imagecreatefromjpeg("$folder/frame$i.jpg");
                    imagecopymerge($image, $monster, $index, 0, 0, 0, $width, $height, 100);
                    imagedestroy($monster);
                    $index += $width;
                }
                $inImg = $inImg + $reste;
            } else {
                $image = imagecreatetruecolor($totalwidth, $totalheight);
                for ($i = $inImg + 1; $i <= $inImg + $nbImg; $i++) {
                    $monster = imagecreatefromjpeg("$folder/frame$i.jpg");
                    imagecopymerge($image, $monster, $index, 0, 0, 0, $width, $height, 100);
                    imagedestroy($monster);
                    $index += $width;
                }
                $inImg = $inImg + $nbImg;
            }
            imagejpeg($image, $folder . "big" . $bigImg . ".jpg");
            imagedestroy($image);
        }
	for ($t = 1; $t <= $nbAllImg; $t++) {
	    unlink($folder . 'frame' . $t . '.jpg');
	}
    }
}

/**
 * processes a video into the required video format/resolutions
 * @global array $CONFIG
 * @param integer $vid the cms_sosial_posts id of the video that needs processing
 */
function convert_video_posts($vid) {

    ob_start();

    global $CONFIG;
    $videoConverter = $CONFIG['video']['videoCoverter'];
    $root = $CONFIG ['server']['root'];
    $vinfo = socialPostsInfo($vid);

    $videoFile = $root . videoGetPostPath($vinfo);
    //$pos = strrpos($vinfo["post_text"],'.');
    //$code =substr($vinfo["post_text"], 6, $pos-6 );


    $minfo = mediaFileInfo($videoFile);
    $width = mediaFileWidth($minfo);
    $height = mediaFileHeight($minfo);
    list($total_duration, $duration_string) = mediaFileDuration($minfo);

    $movieHeight = intval($height);
    $movieWidth = intval($width);

    $widthArray = array(430, 640, 860, 1280, 1920);
    $heightArray = array(240, 360, 480, 720, 1080);
    //$comp = array( 300 ,500, 900, 1000, 1500);
    //$comp = array( 300 ,500, 900, 2000, 3000);
    $comp = array(300, 500, 1000, 1500, 4000);

    $moov_size = intval(1.2 * 1024 * $total_duration); //1kbyte per sec

    $threads = 4;

    $i = 0;
    foreach ($heightArray as $h) {
        if (( $h <= $movieHeight ) || ($i == 0)) {
            $dimesions = $widthArray[$i] . 'x' . $h;
            $video = $root . videoGetPostPath($vinfo, $dimesions);

            $crop = cropCompute($width, $height, $widthArray[$i], $h);
            //-flags +4mv+trell
            //$cmd = "$videoConverter -i \"$videoFile\" -b {$comp[$i]}k -acodec libvo_aacenc -vcodec libx264 -mbd 1 -flags +mv4+aic -trellis 1 $crop -s $dimesions -metadata title=\"$title\" -y \"$video\"";
            //$cmd = "$videoConverter -i \"$videoFile\" -b {$comp[$i]}k -acodec libvo_aacenc -vcodec libx264 -mbd 1 -flags +mv4+aic -trellis 0 -coder 0 -bf 0 -subq 6 -refs 5 $crop -s $dimesions -metadata title=\"$title\" -y \"$video\"";			
            //$cmd = "$videoConverter -i \"$videoFile\" -b {$comp[$i]}k -acodec libvo_aacenc -vcodec libx264 -mbd 1 -flags +mv4+aic -trellis 0 -coder 0 -bf 0 -subq 6 -refs 5 -partitions +parti4x4+parti8x8+partp4x4+partp8x8 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -qmin 10 -qmax 51 -qdiff 4 -ac 1 -ar 16000 -r 13 -ab 32000 $crop -s $dimesions -metadata title=\"$title\" -y \"$video\"";
            // KHADRA: -moov_size $moov_size replaced by -movflags +faststart
            //$cmd = "$videoConverter -i \"$videoFile\" -b:v {$comp[$i]}k -acodec libvo_aacenc -vcodec libx264 -movflags +faststart -vprofile baseline $crop -s $dimesions  -threads $threads -metadata title=\"$title\" -t $duration_string -ac 2 -y \"$video\"";
//-movflags empty_moov
            $cmd = "$videoConverter -i \"$videoFile\"  -pix_fmt yuv420p  -b:v {$comp[$i]}k -s $dimesions  -movflags +faststart  -y \"$video\"";
            $logline = "cmd(POST) = $cmd";
            file_put_contents("converter_service.log", date('r') . ' - ' . $logline . PHP_EOL, FILE_APPEND);

            system($cmd, $o);
            //system("$videoConverter -i \"$videoFile\" -acodec aac -ab 32kb -vcodec mpeg4 -b 1200kb -mbd 1 -flags +4mv+trell -aic 1 -cmp 1 -subcmp 1 -s $dimesions -title \"$title\" \"$video\"", $o);
            //$ob = ob_get_contents();
            
            /*
              if( strstr($ob, 'reserved_moov_size is too small') != null){
              $moov_size = intval($moov_size * 1.1);
              $cmd = "$videoConverter -i \"$videoFile\" -b:v {$comp[$i]}k -acodec libvo_aacenc -vcodec libx264 -moov_size $moov_size -vprofile baseline $crop -s $dimesions  -threads $threads -metadata title=\"$title\" -t $duration_string -y \"$video\"";
              file_put_contents("converter_service.log", date('r') . ' - FAILED: NEW MOV - ' . $logline . PHP_EOL, FILE_APPEND);
              system($cmd, $o);
              }
             */

            if ($i == 0) {
                $screen_per_sec = '1';
                $screen_per_5sec = '1/5';
                $screen_per_min = '1/60';
                $screens = $screen_per_sec;
                $dest_dir = $root . '/' . videoGetInstantThumbPathPost($vinfo);
                
                rmdir_recursive($dest_dir);
                //system("rm -f " . $dest_dir);
                system("mkdir " . $dest_dir);
                $tw = 76;
                $th = 46;
                $thumbnailSize = "{$tw}x{$th}";
                $crop = cropCompute($widthArray[$i], $h, $tw, $th);

                $cmd = "$videoConverter -i \"$video\" -r $screens $crop -s $thumbnailSize -threads $threads -y $dest_dir/out%d.jpg";
                $logline = "cmd = $cmd";
                //file_put_contents("post_converter.log", $logline . "\n", FILE_APPEND);				
                system($cmd, $o);
                
                $tw2 = 89;
                $th2 = 54;
                $thumbnailSize2 = "{$tw2}x{$th2}";
                $crop2 = cropCompute($widthArray[$i], $h, $tw2, $th2);
                $cmd2 = "$videoConverter -i \"$video\" -r $screens $crop2 -s $thumbnailSize2 -threads $threads -y $dest_dir/frame%d.jpg";
                $logline2 = "cmd = $cmd2";

                system($cmd2, $o);
                spriteImage($dest_dir . "/");
            }
        } else {
            break;
        }

        unset($dimesions);
        unset($video);

        $i++;
    } // end foreach

    ob_clean();
}

/**
 * gets the resolutions of a posted video
 * @param integer $vid
 * @param string $path
 * @return array
 */
function getPostVideoResolutions($vid, $path = '') {

    $all = getPostVideoResolutionsComplete($vid, $path);

    $ret = array();
    foreach ($all as $row) {
        $ret[] = $row[0];
    }

    return $ret;
}

/**
 * gets the resolutions of a posted video with the dimensions
 * @param integer $videoFile
 * @param string $videoPath
 * @return array
 */
function getPostVideoResolutionsComplete($vid, $path = '') {

    $vinfo = socialPostsInfo($vid);
    return getPostVideoResolutionsFromInfo($vinfo, $path);
}

/**
 * gets the resolutions of a posted video with the dimensions
 * @param integer $videoFile
 * @param string $videoPath
 * @return array
 */
function getPostVideoResolutionsFromInfo($vinfo, $path = '') {

    //also in convertVideo function
    $widthArray = array(430, 640, 860, 1280, 1920);
    $heightArray = array(240, 360, 480, 720, 1080);

    $i = 0;
    $ret = array();
    $j = 0;
    foreach ($heightArray as $h) {
        $dimesions = $widthArray[$i] . 'x' . $h;

        $videopath = videoGetPostPath($vinfo, $dimesions);

        if (file_exists($path . $videopath)) {
            $ret[$j][0] = $videopath;
            $ret[$j]['w'] = $widthArray[$i];
            $ret[$j]['h'] = $h;

            $j++;
        }

        $i++;
    }

    return $ret;
}

/**
 * gets the resolutions of a video
 * @param integer $vid
 * @param string $path
 * @return array
 */
function getVideoResolutions($vid, $path = '') {

    $all = getVideoResolutionsComplete($vid, $path);

    $ret = array();
    foreach ($all as $row) {
        $ret[] = $row[0];
    }

    return $ret;
}

/**
 * gets the resolutions of a video with the dimensions
 * @param integer $videoFile
 * @param string $videoPath
 * @return array
 */
function getVideoResolutionsComplete($vid, $path = '') {

    $vinfo = getVideoInfo($vid);

    return getVideoResolutionsFromInfo($vinfo, $path);
}

/**
 * gets the resolutions of a video with the dimensions
 * @param integer $videoFile
 * @param string $videoPath
 * @return array
 */
function getVideoResolutionsFromInfo($vinfo, $path = '') {

    //also in convertVideo function
    $widthArray = array(430, 640, 860, 1280, 1920);
    $heightArray = array(240, 360, 480, 720, 1080);

    $i = 0;
    $ret = array();
    $j = 0;
    foreach ($heightArray as $h) {
        $dimensions = $widthArray[$i] . 'x' . $h;

        $videopath = videoGetFakePath($vinfo, $dimensions);
        if (file_exists($path . $videopath['real'])) {
            $ret[$j][0] = $videopath['fake'];
            $ret[$j]['w'] = $widthArray[$i];
            $ret[$j]['h'] = $h;

            $j++;
        }
//		$videopath = videoGetPath($vinfo, $dimensions);
//		if (file_exists($path . $videopath)){
//			$ret[$j][0] = $videopath;
//			$ret[$j]['w'] = $widthArray[$i];
//			$ret[$j]['h'] = $h;
//			
//			$j++;
//		}

        $i++;
    }

    return $ret;
}

function getVideoRating($videoId) {
	global $dbConn;
	$params = array();

//    $q_video_rating = "SELECT nb_ratings AS totalRatings, rating AS avgRating from `cms_videos` WHERE id = '$videoId'";
//    $r_video_rating = db_query($q_video_rating);
    $q_video_rating = "SELECT nb_ratings AS totalRatings, rating AS avgRating from `cms_videos` WHERE id = :VideoId";
	$params[] = array( "key" => ":VideoId",
                            "value" =>$videoId);
    $select = $dbConn->prepare($q_video_rating);
    PDO_BIND_PARAM($select,$params);
    $r_video_rating = $select->execute();
    
    $ret    = $select->rowCount();

    if (!$r_video_rating || $ret==0) {
        $row_video_rating = array('avgRating' => 0, 'totalRatings' => 0);
    } else {
//        $row_video_rating = db_fetch_assoc($r_video_rating);
        $row_video_rating = $select->fetch(PDO::FETCH_ASSOC);
    }
    return $row_video_rating;
}

function getVideoStats($videoId) {
    $videoRating = getVideoRating($videoId);
    $videoComments = videoGetComments($videoId);

    $videoStats = array();

    $videoStats['rating'] = doubleval($videoRating['avgRating']);
    $videoStats['nb_ratings'] = intval($videoRating['totalRatings']);
    $videoStats['nb_comments'] = count($videoComments);

    return $videoStats;
}

/**
 * gets the name of the data chace variable for a video
 * @param integer $vid the cms_videos record
 * @return string 
 */
function videoCacheName($vid) {
    $cache_name = 'media_' . $vid;
    return $cache_name;
}

/**
 * gets the name of the data chace variable for a video
 * @param string $name the cms_videos record
 * @return string 
 */
function videoCacheName2($name) {
    $cache_name = 'media_' . $name;
    return $cache_name;
}

/**
 * purges a video cache
 * @param integer $vid the video id
 */
function videoCachePurge($vid) {
//    cacheUnset(videoCacheName($vid));
}

function getVideoInfo($videoId) {
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <start>
    global $dbConn;
    $getVideoInfo = tt_global_get('getVideoInfo');
    $params = array();  
// Added by Devendra      
//        $user_id    = userGetID(); 
        if(isset($getVideoInfo[$videoId]) && $getVideoInfo[$videoId])
             return $getVideoInfo[$videoId];

//    $q_video_info = "SELECT * from `cms_videos` WHERE id = :VideoId";  //Hide by Devendra
    $q_video_info = "SELECT `id`, `last_modified`, `code`, `name`, `fullpath`, `relativepath`, `type`, `title`, `description`, `category`, `placetakenat`, `keywords`, `country`, `location`, `pdate`, `dimension`, `duration`, `userid`, `published`, `nb_views`, `nb_comments`, `nb_ratings`, `rating`, `nb_shares`, `like_value`, `lattitude`, `longitude`, `cityid`, `cityname`, `is_public`, `trip_id`, `image_video`, `location_id`, `video_url`, `media_servers`, `thumb_top`, `thumb_left`, `channelid`, `can_comment`, `can_share`, `can_rate`, `can_like`, `link_ts`, `description_linked`, `hash_id`, `old` FROM `cms_videos` WHERE id = :VideoId";   // Added by Devendra
    $params[] = array( "key" => ":VideoId", "value" =>$videoId);

    $select = $dbConn->prepare($q_video_info);
    PDO_BIND_PARAM($select,$params);
    $select->execute();

    $ret    = $select->rowCount();
//    if (db_num_rows($r_video_info) == 0)
    if ($ret == 0){ 
            $getVideoInfo[$videoId] =   false; // what does this do ?
            return false;
    }

//    $row = db_fetch_assoc($r_video_info);
    $row = $select->fetch(PDO::FETCH_ASSOC);
    
    $getVideoInfo[$videoId] =   $row;
    return $row;
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <end>
}

function getVideoCode($videoId)
{
	$getVideoInfo = tt_global_get('getVideoInfo');
	
	if(isset($getVideoInfo[$videoId]) && $getVideoInfo[$videoId])
		return $getVideoInfo[$videoId]['code'];
	
	global $dbConn;
	
	$params = array();
	
	$q_video_code = 'SELECT code FROM cms_videos WHERE id = :VideoId';
	$params[] = array('key' => ':VideoId', 'value' => $videoId);
	
	$select = $dbConn->prepare($q_video_code);
	PDO_BIND_PARAM($select, $params);
	$select->execute();
	
	$ret = $select->rowCount();
	
    if (!$ret)
		return false;
	
    $row = $select->fetch(PDO::FETCH_ASSOC);
    
    return $row['code'];
}

function getVideoOwner($videoId)
{
	$getVideoInfo = tt_global_get('getVideoInfo');
	
	if(isset($getVideoInfo[$videoId]) && $getVideoInfo[$videoId])
		return $getVideoInfo[$videoId]['code'];
	
	global $dbConn;
	
	$params = array();
	
	$q_video_owner = 'SELECT userid FROM cms_videos WHERE id = :VideoId';
	$params[] = array('key' => ':VideoId', 'value' => $videoId);
	
	$select = $dbConn->prepare($q_video_owner);
	PDO_BIND_PARAM($select, $params);
	$select->execute();
	
	$ret = $select->rowCount();
	
    if (!$ret)
		return false;
	
    $row = $select->fetch(PDO::FETCH_ASSOC);
    
    return $row['userid'];
}

function getVideoInfoEncoded($encoded) {
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <start>
    global $dbConn;

//    $q_video_info = "SELECT * from `cms_videos` WHERE MD5(concat(id,video_url))='$encoded'";
    $q_video_info = "SELECT * from `cms_videos` WHERE MD5(concat(id,video_url))=:Encoded";

	$params[] = array( "key" => ":Encoded",
                            "value" =>$encoded);
    $select = $dbConn->prepare($q_video_info);
    PDO_BIND_PARAM($select,$params);
    $select->execute();

    $ret    = $select->rowCount();
//    if (db_num_rows($r_video_info) == 0)
    if ($ret == 0)
        return false;

//    $row = db_fetch_assoc($r_video_info);
    $row = $select->fetch(PDO::FETCH_ASSOC);

    return $row;
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <end> 
}

/**
 * gets the media info given its name
 * @param string $name the name of the file
 * @return boolean|array
 */
function getVideoInfoByName($name) {
//  Changed by Anthony Malak 24-04-2015 to PDO database
//  <start>
    global $dbConn;
	$params = array(); 
        

    $q_video_info = "SELECT * from `cms_videos` WHERE name = :Name";
	$params[] = array( "key" => ":Name",
                            "value" =>$name);
    $select = $dbConn->prepare($q_video_info);
    PDO_BIND_PARAM($select,$params);
    $r_video_info = $select->execute();

    $ret    = $select->rowCount();
    if (!$r_video_info || $ret == 0)
        return false;
    $row = $select->fetch(PDO::FETCH_ASSOC);
    return $row;
//  Changed by Anthony Malak 24-04-2015 to PDO database
//  <end>
}

function getVideos($nlimit = 15, $page = 0, $category_id = -1) {
    
    $options = array('limit' => $nlimit, 'page' => $page, 'type' => 'v', 'orderby' => 'pdate', 'order' => 'd');
    //$options = array('limit' => $nlimit, 'page' => $page, 'type' => 'v', 'orderby' => 'rand');
    if ($category_id != -1)
        $options['cat_id'] = $category_id;
    $vids = mediaSearch($options);
    $ret_vids = count($vids);
    if ($ret_vids < 9) {
        $options['cat_id'] = null;
        $options['max_id'] = $vids[$ret_vids - 1]['id'];
        $options['limit'] = $nlimit - $ret_vids;
        $vids2 = mediaSearch($options);
        $i = 0;
        while ($i < count($vids2)) {
            $vids[] = $vids2[$i];
            $i++;
        }
    }
    return $vids;
}

function getRandomVideos($nlimit = 15, $page = 0) {
    $options = array('limit' => $nlimit, 'page' => $page, 'type' => 'v', 'orderby' => 'rand');
    return mediaSearch($options);
}

function getVideosMostView($nlimit = 6, $page = 0) {
    $options = array('limit' => $nlimit, 'page' => $page, 'type' => 'v', 'orderby' => 'nb_views', 'order' => 'd');
    return mediaSearch($options);
}

//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function getPhotos($nlimit = 15) {
//    $options = array('limit' => $nlimit, 'type' => 'i', 'orderby' => 'id', 'order' => 'd');
//    return mediaSearch($options);
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>

function getPhotosMostView($nlimit = 6, $page = 0) {
    $options = array('limit' => $nlimit, 'page' => $page, 'type' => 'i', 'orderby' => 'nb_views', 'order' => 'd');
    return mediaSearch($options);
}

function getVideoThumbnail($videoId, $mediaPath, $returnAllPaths = 0)
{
	$videoCode = getVideoCode($videoId);
	
	$thumbs = glob($mediaPath . "_*_" . $videoCode . "*.jpg");
	// $thumbs = glob($mediaPath . $videoCode . "*.jpg");
	
	if (!$thumbs) return '';
	
	if ($returnAllPaths)
		return $thumbs;
	else
		return $thumbs[0];
}

function getVideoThumbnailByCode($videoCode, $mediaPath, $returnAllPaths = 0)
{
	$thumbs = glob($mediaPath . "_*_" . $videoCode . "*.jpg");
	// $thumbs = glob($mediaPath . $videoCode . "*.jpg");
	
	if (!$thumbs) return '';
	
	if ($returnAllPaths)
		return $thumbs;
	else
		return $thumbs[0];
}

function getVideoThumbnail_Posts($videoCode, $mediaPath, $returnAllPaths = 0)
{
	// $thumbs = glob($mediaPath . "_*_" . $videoCode . "*.jpg");
	$thumbs = glob($mediaPath .  $videoCode . "*.jpg");
	
	if (!$thumbs) return '';
	
	if ($returnAllPaths)
		return $thumbs;
	else
		return $thumbs[0];
}

//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function getVideoThumbLarge($vinfo) {
//    $videoCode = $vinfo['code'];
//    $videoPath = $vinfo['fullpath'];
//    $thumbs = glob($videoPath . 'large_*_' . $videoCode . "*.jpg");
//    //$thumbs = glob($videoPath . 'large_' . $videoCode . "*.jpg");
//    if (count($thumbs) == 0)
//        return false;
//    return $thumbs[0];
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>

function getVideoThumbSmall($vinfo) {
    $videoCode = $vinfo['code'];
    $videoPath = $vinfo['fullpath'];
    $thumbs = glob($videoPath . 'small_*_' . $videoCode . "*.jpg");
    //$thumbs = glob($videoPath . 'small_' . $videoCode . "*.jpg");
    if (count($thumbs) == 0)
        return false;
    return $thumbs[0];
}

function getImageThumbnail($videoInfo, $mediaPath) {
    $parts = pathinfo($videoInfo['name']);
    return $mediaPath . $videoInfo['relativepath'] . 'thumb_' . $parts['filename'] . '.jpg';
}
/**
 * flags a video to be deleted
 * @param integer $vid the video to be deleted
 * @return boolean
 */
function videoDeleteFlag($vid) {
    global $dbConn;
	$params = array();  
    $del_flag = MEDIA_DELETE;
//    cacheUnset(videoCacheName($vid));
    $query = "UPDATE cms_videos SET published=:Del_flag WHERE id=:Vid";
	$params[] = array( "key" => ":Del_flag",
                            "value" =>$del_flag);
	$params[] = array( "key" => ":Vid",
                            "value" =>$vid);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res = $update->execute();
    return $res;
}

/**
 * deletes a video
 * @param integer $video_id the id of the video to be deleted
 * @return boolean
 */
function videoDelete($video_id) {
//  Changed by Anthony Malak 24-04-2015 to PDO database
//  <start>
    global $dbConn;
    $params  = array();  
    $params2 = array();  
    $params3 = array();  
    $params4 = array();  
    $params5 = array();  
    global $CONFIG;
    $root = $CONFIG['server']['root'];

//    $query = "SELECT * FROM cms_videos WHERE id='$video_id'";
    $query1 = "SELECT * FROM cms_videos WHERE id=:Video_id";
    $params[] = array( "key" => ":Video_id", "value" =>$video_id);
    
    $select1 = $dbConn->prepare($query1);
    PDO_BIND_PARAM($select1,$params);
    $res    = $select1->execute();
    $ret    = $select1->rowCount();
    
//    if (!$res || (db_num_rows($res) == 0)) {
    if (!$res || ($ret == 0)) {
        return false;
    }
//    $vinfo = db_fetch_assoc($res);
    $vinfo = $select1->fetch(PDO::FETCH_ASSOC);
    
    //decrement the catalogs media number that this deleted video belongs to
//    $query = "SELECT catalog_id FROM cms_videos_catalogs WHERE video_id='$video_id'";
    $query2 = "SELECT catalog_id FROM cms_videos_catalogs WHERE video_id=:Video_id";

    $params2[] = array( "key" => ":Video_id", "value" =>$video_id);
    $select2 = $dbConn->prepare($query2);
    PDO_BIND_PARAM($select2,$params2);
    $res    = $select2->execute();

    $ret    = $select2->rowCount();
    
    if ($res && ($ret != 0)) {
        $row = $select2->fetchAll();
        foreach($row as $row_item){
            $catalog_id = $row_item[0];
            $params3=array();
            $query3 = "UPDATE cms_users_catalogs SET n_media=(SELECT COUNT(catalog_id) FROM cms_videos_catalogs WHERE catalog_id=:Catalog_id) WHERE id=:Catalog_id";
            $params3[] = array( "key" => ":Catalog_id", "value" =>$catalog_id);

            $update = $dbConn->prepare($query3);
            PDO_BIND_PARAM($update,$params3);
            $update->execute();
        }
    }
    $fileinfo = pathinfo($vinfo['name']);

    
    newsfeedDelete($vinfo['userid'], $video_id, SOCIAL_ACTION_UPLOAD);
    newsfeedDeleteAll($video_id, SOCIAL_ENTITY_MEDIA);
    socialCommentsDelete($video_id, SOCIAL_ENTITY_MEDIA);
    socialLikesDelete($video_id, SOCIAL_ENTITY_MEDIA);
    socialRatesDelete($video_id, SOCIAL_ENTITY_MEDIA);
    socialFavoritesDelete($video_id, SOCIAL_ENTITY_MEDIA);

//    $query = "DELETE FROM `cms_videos_catalogs` WHERE video_id='$video_id'";
//    db_query($query);
    
    $query4 = "DELETE FROM `cms_videos_catalogs` WHERE video_id=:Video_id";
    $params4[] = array( "key" => ":Video_id", "value" =>$video_id);
        
    $select3 = $dbConn->prepare($query4);
    PDO_BIND_PARAM($select3,$params4);
    $select3->execute();

    $mask = glob($root . $vinfo['fullpath'] ."*". $vinfo['name']);
    foreach ($mask as $thumb) {
        @unlink($thumb);
    }
//    array_map( "unlink", glob( $mask ) );
//    @unlink($root . $vinfo['fullpath'] . $vinfo['name']);
//    @unlink($root . $vinfo['fullpath'] . 'small_' . $vinfo['name']);
//    @unlink($root . $vinfo['fullpath'] . 'med_' . $vinfo['name']);
//    @unlink($root . $vinfo['fullpath'] . 'org_' . $vinfo['name']);
//    @unlink($root . $vinfo['fullpath'] . 'thumb_' . $fileinfo['filename'] . '.jpg');
    $resolutions = getVideoResolutionsFromInfo($vinfo, $root);
    foreach ($resolutions as $vid) {
        @unlink($root . $vinfo['fullpath'] . $vid[0]);
    }
    $thumbs = glob($root . $vinfo['fullpath'] .'_*_'. $vinfo['code'] . "*.jpg");
    foreach ($thumbs as $thumb) {
        @unlink($thumb);
    }

    if (deleteMode() == TT_DEL_MODE_PURGE) {        
        $query5 = "DELETE FROM `cms_videos` WHERE `id`=:Video_id";
    } else if (deleteMode() == TT_DEL_MODE_FLAG) {
        $query5 = "UPDATE cms_videos SET published=" . TT_DEL_MODE_FLAG . " WHERE `id`=:Video_id";
    }

    $params5[] = array( "key" => ":Video_id", "value" =>$video_id);
    $delete = $dbConn->prepare($query5);
    PDO_BIND_PARAM($delete,$params5);
    $res =$delete->execute();
    return $res;
}

/**
 * gets a users vote of a video
 * @param integer $vid the webcam id
 * @param integer $user_id the user id
 * @return integer|fasle the users like/dislike or fasle if not votes
 */
function videoVoted($vid, $user_id) {
//    cacheUnset(videoCacheName($vid));
    return socialLiked($user_id, $vid, SOCIAL_ENTITY_MEDIA);
}

/**
 * Vote a video up or down
 * @param type $vid the video being voted
 * @param type $user_id the user voting
 * @param type $up_down like (1) or dislike (-1)
 */
function videoVote($vid, $user_id, $up_down) {
//    cacheUnset(videoCacheName($vid));
    //user can only have one vote
    if (socialLiked($user_id, $vid, SOCIAL_ENTITY_MEDIA)) {
        $ret = socialLikeEdit($user_id, $vid, SOCIAL_ENTITY_MEDIA, $up_down);
    } else {
        $ret = socialLikeAdd($user_id, $vid, SOCIAL_ENTITY_MEDIA, $up_down, null);
    }

    if (!$ret)
        return false;


    return true;
}

/**
 * returns the encrypted url for a video
 * @param array $vidInfo the video info record
 * @return string the video's uri 
 */
function ReturnVideoUriHashed($vidInfo,$skip_title=0) {
    if($vidInfo['image_video']=="i"){
        return ReturnLink('best-travel-images/' . videoToURLHashed($vidInfo,$skip_title),null,0,'media');
    }else{
        return ReturnLink('best-travel-videos/' . videoToURLHashed($vidInfo,$skip_title),null,0,'media');
    }    
}

/**
 * returns the video url for a video
 * @param array $vidInfo the video info record
 * @return string the video's uri 
 */
function ReturnVideoUri($vidInfo) {
    return ReturnLink('best-travel-videos/' . $vidInfo['video_url'],null,0,'media');
    if (($channel = channelGlobalGet())) {
        return ReturnLink($channel['channel_url'] . '/video/' . $vidInfo['video_url']);
    } else {
        return ReturnLink('best-travel-videos/' . $vidInfo['video_url'],null,0,'media');
    }
}

/**
 * returns the video url for a video inside album
 * @param array $vidInfo the video info record
 * @return string the video's uri 
 */
function ReturnVideoAlbumUri($vidInfo,$skip_title=0) {
    return ReturnLink('best-travel-videos-album/' . videoToURLHashed($vidInfo,$skip_title),null,0,'media');
}

/**
 * returns the pohto url for a photo inside album
 * @param array $photoInfo the photo info record
 * @return string the photo's uri 
 */
function ReturnPhotoAlbumUri($photoInfo,$skip_title=0) {
    return ReturnLink('best-travel-images-album/' . videoToURLHashed($photoInfo,$skip_title),null,0,'media');
}

/**
 * echos an encrypted video uri
 * @param array $vidInfo the video info record
 */
function GetVideoUriHashed($vidInfo,$skip_title=0) {
    echo ReturnLink('best-travel-videos/' . videoToURLHashed($vidInfo,$skip_title),null,0,'media');
}

/**
 * echos the uri generated by ReturnVideoUri
 * @param array $vidInfo the video info record
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<start>
//function GetVideoUri($vidInfo) {
//    echo ReturnVideoUri($vidInfo);
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<end>

/**
 * returns the encrypted url for a photo
 * @param array $photoInfo the photo info record
 * @return string the photo's uri 
 */
function ReturnPhotoUriHashed($photoInfo,$skip_title=0) {
    return ReturnLink('best-travel-images/' . videoToURLHashed($photoInfo,$skip_title),null,0,'media');
}

/**
 * returns the pohto url for a photo
 * @param array $photoInfo the photo info record
 * @return string the photo's uri 
 */
function ReturnPhotoUri($photoInfo,$skip_title=0) {
    return ReturnLink('best-travel-images/' . videoToURLHashed($photoInfo,$skip_title),null,0,'media');
}

/**
 * returns the album url for an album
 * @param array $albumInfo the album info record
 * @return string the album's uri 
 */
function ReturnAlbumUri($albumInfo) {
    return ReturnLink('album/' . $albumInfo['album_url'],null,0,'media');
}

/**
 * echos the uri generated by ReturnPhotoUri
 * @param array $photoInfo the photo info record
 */
function GetPhotoUri($photoInfo) {
    echo ReturnPhotoUriHashed($photoInfo);
//    echo ReturnPhotoUri($photoInfo);
}

/**
 * returns a video's comments
 * @param integer $vid the video ID
 * @param integer $nlimit the maximum  umber of comment record to return
 * @param integer $page number of pages of comment records to skip
 * @param string $sortby column name to sort by
 * @param string $sort ascending or descending sort 'ASC' | 'DESC'
 * @return mixed array of the video's comment records or false if none found
 */
function videoGetComments($vid, $nlimit = 5, $page = 0, $sortby = 'comment_date', $sort = 'DESC') {
    $order = ($sort == 'DESC') ? 'd' : 'a';
    return socialCommentsGet(array('limit' => $nlimit, 'page' => $page, 'entity_id' => $vid, 'entity_type' => SOCIAL_ENTITY_MEDIA, 'orderby' => $sortby, 'order' => $order));
}

/**
 * gets the number of comments associated with a video
 * @param integer $vid the video to be queried for number of comments
 * @return integer the number of comments
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<start>
//function VideoGetCommentNumber($vid) {
//    return socialCommentsGet(array('entity_id' => $vid, 'entity_type' => SOCIAL_ENTITY_MEDIA, 'n_results' => true));
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<end>

/**
 * Return the number of coments per page on the video page
 * @return int the number of comments per page
 */
function VideoGetCommentsPerPage() {
    return 5;
}

/**
 * Increent a videos number of views
 * @param integer $vid the video being viewed
 */
function VideoIncViews($vid) {
    global $dbConn;
	$params = array(); 
    $query = "UPDATE cms_videos SET nb_views = nb_views +1 WHERE id=:Vid";
	$params[] = array( "key" => ":Vid",
                            "value" =>$vid);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $update->execute();
}

/**
 * return a set of user video records 
 * @param integer $userId which user
 * @param integer $nlimit how many records to return
 * @param integer $page which page of records
 * @return array an array of video records  
 */
function GetUserVideosLimit($userId, $nlimit, $page) {
    
    $options = array('userid' => $userId, 'limit' => $nlimit, 'page' => $page, 'type' => 'v');
    return mediaSearch($options);
}

/**
 * return a set of user photo records 
 * @param integer $userId which user
 * @param integer $nlimit how many records to return
 * @param integer $page which page of records
 * @return array an array of photo records  
 */
function GetUserPhotosLimit($userId, $nlimit, $page) {

    $options = array('userid' => $userId, 'limit' => $nlimit, 'page' => $page, 'type' => 'i');
    return mediaSearch($options);
}

/**
 * checks if the video already exists.
 * @param integer $name the name of the video
 * @param integer $user_id the user id of the uploader
 * return integer | false the id of the video or false if the video doesnt exist
 */
function videoExists2($name, $user_id) {
//  Changed by Anthony Malak 24-04-2015 to PDO database
//  <start>
    global $dbConn;
	$params = array();  
    $timeout = 10; //10 min
    $timeout *= 60; //seconds
//    $query = "SELECT id FROM cms_videos WHERE name='$name' AND userid='$user_id' AND TIME_TO_SEC(TIMEDIFF(NOW(), pdate)) < $timeout";
//    $ret = db_query($query);
//    if ($ret && (db_num_rows($ret) != 0 )) {
//        $row = db_fetch_array($ret);
//        return $row[0];
//    } else {
//        return false;
//    }
    $query = "SELECT id FROM cms_videos WHERE name=:Name AND userid=:User_id AND TIME_TO_SEC(TIMEDIFF(NOW(), pdate)) < :Timeout";
	$params[] = array( "key" => ":Name",
                            "value" =>$name);
	$params[] = array( "key" => ":User_id",
                            "value" =>$user_id);
	$params[] = array( "key" => ":Timeout",
                            "value" =>$timeout);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();
    if ($res && ($ret != 0 )) {
        $row = $select->fetch();
        return $row[0];
    } else {
        return false;
    }
//  Changed by Anthony Malak 24-04-2015 to PDO database
//  <end>
}

/**
 * gets the video if it exists and is owned by the right user
 * @param integer $vid the cms_videos record id
 * @param integer $user_id the owner of he media file
 * @return null| cms_videos record 
 */
function videoGetIfExists($vid, $user_id) {
//  Changed by Anthony Malak 24-04-2015 to PDO database
//  <start>
    global $dbConn;
	$params = array();  
//    $query = "SELECT * FROM cms_videos WHERE id='$vid' AND userid='$user_id'";
//    $ret = db_query($query);
//    if ($ret && (db_num_rows($ret) != 0 )) {
//        $row = db_fetch_array($ret);
//        return $row;
//    } else {
//        return null;
//    }
    $query = "SELECT * FROM cms_videos WHERE id=:Vid AND userid=:User_id";
	$params[] = array( "key" => ":Vid",
                            "value" =>$vid);
	$params[] = array( "key" => ":User_id",
                            "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();
    if ($res && ($ret != 0 )) {
        $row = $select->fetch();
        return $row;
    } else {
        return null;
    }
//  Changed by Anthony Malak 24-04-2015 to PDO database
//  <end>
}

/**
 * updates a video
 * @param integer $vid the id of the video to be updated
 * @param array $vinfo the video info array
 */
function updateVideo2($vid, $vinfo) {
    global $dbConn;
	$params = array();
    $city_id = intval($vinfo['cityid']);
    $trip_id = intval($vinfo['trip_id']);
    $location_id = intval($vinfo['location_id']);
    $country_code = is_null($vinfo['country']) ? '' : "'{$vinfo['country']}'";
    $category_id = intval($vinfo['category']);
    
    $longitude = doubleval($vinfo['longitude']);
    $latitude = doubleval($vinfo['lattitude']);

    $code = md5($vinfo['name']);
    $query = "UPDATE
                    `cms_videos`
            SET
                    `name`=:Name,
                    `fullpath`=:Fullpath,
                    `code`=:Code,
                    `relativepath`=:Relativepath,
                    `type`=:Type,
                    `country`=:Country_code,
                    `userid`=:Userid,
                    `dimension`=:Dimension,
                    `duration`=:Duration,
                    `title`=:Title,
                    `description`=:Description,
                    `category`=:Category_id,
                    `placetakenat`=:Placetakenat,
                    `keywords`=:Keywords,
                    `is_public`=:Is_public,
                    `lattitude`=:Latitude,
                    `longitude`=:Longitude,
                    `cityid`=:City_id,
                    `cityname`=:Cityname,
                    `location_id`=:Location_id,
                    `trip_id`=:Trip_id
            WHERE
                    id=:VideoId";
    
    $params[] = array( "key" => ":Name", "value" =>$vinfo['name']);
    $params[] = array( "key" => ":Fullpath", "value" =>$vinfo['fullpath']);
    $params[] = array( "key" => ":Relativepath", "value" =>$vinfo['relativepath']);
    $params[] = array( "key" => ":Type", "value" =>$vinfo['type']);
    $params[] = array( "key" => ":Country_code", "value" =>$country_code);
    $params[] = array( "key" => ":Userid", "value" =>$vinfo['userid']);
    $params[] = array( "key" => ":Dimension", "value" =>$vinfo['dimension']);
    $params[] = array( "key" => ":Duration", "value" =>$vinfo['duration']);
    $params[] = array( "key" => ":Title", "value" =>$vinfo['title']);
    $params[] = array( "key" => ":Description", "value" =>$vinfo['description']);
    $params[] = array( "key" => ":Category_id", "value" =>$category_id);
    $params[] = array( "key" => ":Placetakenat", "value" =>$vinfo['placetakenat']);
    $params[] = array( "key" => ":Keywords", "value" =>$vinfo['keywords'] );
    $params[] = array( "key" => ":Is_public", "value" =>$vinfo['is_public']);
    $params[] = array( "key" => ":Latitude", "value" =>$latitude);
    $params[] = array( "key" => ":Longitude", "value" =>$longitude);
    $params[] = array( "key" => ":City_id", "value" =>$city_id);
    $params[] = array( "key" => ":Cityname", "value" =>$vinfo['cityname'] );
    $params[] = array( "key" => ":Location_id", "value" =>$location_id);
    $params[] = array( "key" => ":Trip_id", "value" =>$trip_id);
    $params[] = array( "key" => ":VideoId", "value" =>$vid);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    =$select->execute();
                    
    queryAdd($vinfo['cityname']);

    $words = $vinfo['title'] . ' ' . $vinfo['description'] . ' ' . $vinfo['keywords'] . ' ' . $vinfo['placetakenat'];
    queryAddRegular($words);
    return $res;
}

/**
 * gets a category name given the id
 * @param integer $cat_id 
 * @return string | false category name if found or false if nothing found
 */
function categoryGetName($cat_id) {
//  Changed by Anthony Malak 24-04-2015 to PDO database
//  <start>
    global $dbConn;
    $lang_code = LanguageGet();
    $params = array();
    
    $languageSel = '';
    $languageJoin = '';
    $languageAnd = '';
    if ($lang_code != 'en') {
        $languageSel = ',ml.title as ml_title';
        $languageJoin = ' INNER JOIN ml_allcategories ml on c.id = ml.entity_id ';
//        $languageAnd = " and ml.lang_code='$lang_code'";
        $languageAnd = " and ml.lang_code=:Lang_code";
        $params[] = array( "key" => ":Lang_code",
                            "value" =>$lang_code);  
    }
//    $query = "SELECT c.title as title $languageSel FROM cms_allcategories c$languageJoin WHERE c.id='$cat_id' AND c.published='1' $languageAnd";
    $query = "SELECT c.title as title $languageSel FROM cms_allcategories c$languageJoin WHERE c.id=:Cat_id AND c.published='1' $languageAnd";
    $params[] = array( "key" => ":Cat_id",
                        "value" =>$cat_id);    
//        debug($query);
//    $ret = db_query($query);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    
//    if ($ret && (db_num_rows($ret) != 0 )) {
    if ($res && ($ret != 0 )) {
//        $row = db_fetch_array($ret);
        $row = $select->fetch();
        if ($lang_code == 'en') {
            return $row['title'];
        } else {
            return $row['ml_title'];
        }
    } else {
        return false;
    }
//  Changed by Anthony Malak 24-04-2015 to PDO database
//  <end>
}

/**
 * finds a video knowing its filename and directory in cazse of immediate delkete
 * @param string $dir
 * @param string $fname
 * @param integer $user_id the id of the user requesting the delete
 * @return boolean true if deleted false if not
 */
function videoDeleteFind($dir, $fname, $user_id) {
//  Changed by Anthony Malak 24-04-2015 to PDO database
//  <start>
	global $dbConn;
        
	$params = array();
    $timeout = 10; //10 min
    $timeout *= 60; //seconds
//    $query = "SELECT id FROM cms_videos WHERE name='$fname' AND relativepath='$dir' AND userid='$user_id' AND TIME_TO_SEC(TIMEDIFF(NOW(), pdate)) < $timeout";
//    $ret = db_query($query);
//    if ($ret && (db_num_rows($ret) != 0 )) {
//        $row = db_fetch_array($ret);
//        return $row[0];
//    } else {
//        return false;
//    }
    $query = "SELECT id FROM cms_videos WHERE name=:Fname AND relativepath=:Dir AND userid=:User_id AND TIME_TO_SEC(TIMEDIFF(NOW(), pdate)) < :Timeout";
    $params[] = array( "key" => ":Fname", "value" =>$fname);  
    $params[] = array( "key" => ":Dir", "value" =>$dir);  
    $params[] = array( "key" => ":User_id", "value" =>$user_id);  
    $params[] = array( "key" => ":Timeout", "value" =>$timeout);  
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && ($ret != 0 )) {
        $row = $select->fetch();
        return $row[0];
    } else {
        return false;
    }
//  Changed by Anthony Malak 24-04-2015 to PDO database
//  <end>
}

function _searchStringDontSearch($string) {
    //TODO: get all prepositions conjuctions
    $dont_search = array('and', 'or', 'the');
    return in_array($string, $dont_search);
}

/**
 * search query helper for the city
 * @param string $seacrh_for
 * @param string $search_where
 * @return string
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<start>
//function _searchQueryHelper2($seacrh_for, $search_where) {
//    $search_strings = explode(' ', $seacrh_for);
//    $similarity_arr = explode(',', $search_where);
//
//    $string_search = '';
//
//    $searched = array();
//
//    foreach ($search_strings as $in_search_string) {
//
//        $search_string = trim(strtolower($in_search_string));
//        $search_string = preg_replace('/[^a-z0-9A-Z]/', '', $search_string);
//
//        //add more "rules" here
//        //TTDebug(DEBUG_TYPE_SEARCH,DEBUG_LVL_INFO, "SSSS2 $search_string");
//
//        if (strlen($search_string) <= 1)
//            continue;
//
//        if (in_array($search_string, $searched))
//            continue;
//
//        if (_searchStringDontSearch($search_string))
//            continue;
//
//        $searched[] = $search_string;
//    }
//
//    $string_search = " EXISTS (SELECT vid FROM cms_videos_words WHERE vid=V.id AND word IN ('" . implode("','", $searched) . "') )"; //AND col_type='c'
//
//    return $string_search;
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<end>

/**
 * return the cache name of recrods similar to a viddeo given by id
 * @param integer $id the cms_videos id
 */
function videoSimCacheName($id) {
    return 'media_sim_' . $id;
}

/**
 * search for videos given certain options. options include:<br/>
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>skip</b>: specific number of records skip. default 0<br/>
 * <b>public</b>: wheather the media file is public or not. 0 => private, 1=> community, 2=> public. default 2<br/>
 * <b>userid</b>: the media file's owner's id. default null<br/>
 * <b>favorite</b>: dont get the user's media - get his favorite media. default false.<br/>
 * <b>type</b>: what type of media file (v)ideo or (i)mage or (a)ll or (u)ser. default 'v'<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id' or similarity<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>latitude</b>: the latitude of the location to search within<br/>
 * <b>longitude</b>: the logitude of the location to search within<br/>
 * <b>radius</b>: the radius to search within (in meters)<br/>
 * <b>dist_alg</b>: the distance algorithm to use (s)quare [faster], or (c)ircular [slower]. default is 's'<br/>
 * <b>search_string</b>: the string to search for. could be space separated. no default<br/>
 * <b>search_where</b>: where to search for the string (t)itle, (d)escription, (k)eywords, (a)ll, or a comma separated combination. default is 'a'<br/>
 * <b>search_strict</b>: if the search is strict or not default 1. if 0 similarity is not available.<br/>
 * <b>date_from</b>: get records newer than this one's date. default null<br/>
 * <b>date_to</b>: get records older than this one's date. default null<br/>
 * <b>max_id</b>: get records less than this one. (implied orderby 'id' and order 'd').<br/>
 * <b>min_id</b>: get records greater than this one. (implied orderby 'id' and order 'a').<br/>
 * <b>vid</b>: get records similar to the record given by this id.<br/>
 * <b>similarity</b>: in what way similar? (l)ocation, (k)eywords, (t)itle, (a)ny similarity, or a comma separated combination. default is 'a'. no effect if search_strict is 0.<br/>
 * <b>cat_id</b>: the category id to search for.<br/>
 * <b>catalog_id</b>: the catalog_id to search for. default null.<br/>
 * <b>channel_id</b>: the channel_id to search for. default null. -1 => disregard channel_id, -2 => any channel<br/>
 * <b>location_id</b>: the location_id to search for.<br/>
 * <b>city_id</b>: the city_id to search for. default null.<br/>
 * <b>country</b>: the country to search for. default null.<br/>
 * <b>continent</b>: the continent to search for. default null.<br/>
 * <b>multi_order</b>: array. use in case of multiple orderbys in format array(orderby=>col,orderby=>order). overrides the <b>orderby</b> option. default null.<br/>
 * <b>catalog_status</b>: integer. -1 => dont care, 0 => is not part of a catalog, 1=>is part of a catalog. default -1.<br/>
 * <b>is_owner</b>: integer. 0 => not owner (get all media for this user related to the privacy ) , 1 => owner(it does check privacy for the user). default 0.<br/>
 * <b>n_results</b>: gets the number of results rather than the rows. default false.
 * @param array $srch_options. the search options
 * @return array a number of media records
 */
/*
 * function mediaSearchNoSQL($srch_options){
  ini_set("display_errors",1);
  ini_set("error_reporting",E_ALL);

  $solr = SolConnect();
  $default_opts = array(
  'limit' => 10,
  'page' => 0,
  'skip' => 0,
  'public' => 2,
  'userid' => null,
  'favorite' => false,
  'type' => 'v',
  'orderby' => 'id',
  'order' => 'a',
  'is_owner' => 0,
  'latitude' => null,
  'longitude' => null,
  'radius' => 1000,
  'dist_alg' => 's',
  'search_string' => null,
  'search_where' => 'a',
  'search_strict' => 1,
  'max_id' => null,
  'min_id' => null,
  'vid' => null,
  'media_id' => null,
  'similarity' => 't,k',
  'cat_id' => null,
  'catalog_id' => null,
  'category_id' => null,
  'location_id' => null,
  'city_id' => null,
  'continent' => null,
  'country' => null,
  'n_results' => false,
  'min_similarity' => 0,
  'date_from' => null,
  'date_to' => null,
  'multi_order' => null,
  'channel_id' => null,
  'catalog_status' => -1,
  );

  $options = array_merge($default_opts, $srch_options);
  $search_string=$options['search_string'];
  $type=$options['type'];
  $page=$options['page'];
  $limit=$options['limit'];
  $category_id = $options['category_id'];
  $debugQuery=$options['debug'];

  $query= 'media_title:'.$search_string <> '' ? $search_string: '*:*';


  $userVideos=array();
  $params_def=array(
  //'fl'=>'*,score',
  //'facet'=>'true',
  //'facet.field'=>'media_image_video',
  'spellcheck'=>'true',
  'spellcheck.collate'=>'true',
  //'edismax'=>true,
  //'qf'=>'media_title^6.5 media_description^4.0 media_avg_day^3.0 media_cityname^10.0',
  'sort'=>'score desc , media_avg_day desc',
  //'sort'=>'random_'.rand(0,99).' desc',
  //'fq'=>array('media_published:1'),
  //'defType'=>'edismax',
  //'qf'=>'media_cityname^2 media_title'

  );
  if($type<>'a'){
  $params_def['fq'][]="media_image_video:\"$type\"";
  //$query.=$andType;
  }
  $params = array_merge($params_def , $srch_options['params']);

  //if($category_id <> '') $params['fq']="media_category:$category_id";
  if($category_id <> '') $query.=" +media_category:$category_id";

  $response = $solr->search($query, $page, $limit ,$params);

  foreach ( $response->response->docs as $k=>$doc ){
  foreach($doc as $kk=>$v){
  $rep=str_replace('media_','',$kk);
  $userVideos['media'][$k][$rep]=$v;
  }
  }

  if ( $response->getHttpStatus() == 200 ) {
  if ( $response->response->numFound > 0 ){
  $n_videos = $response->facet_counts->facet_fields->media_image_video->v;
  $n_images = $response->facet_counts->facet_fields->media_image_video->i;
  $userVideos['n_videos']=$n_videos;
  $userVideos['n_images']=$n_images;
  }
  }
  $userVideos['numFound']= $response->response->numFound;
  $userVideos['spell'] = $response->spellcheck->suggestions->collation;
  $userVideos['debug_response_docs'] = $response->response->docs;
  $userVideos['debug_spell'] = $response->spellcheck;
  $userVideos['debug'] = $response;
  return $userVideos;
  }
 */
function mediaSearchNoSQL($srch_options) {
    global $path;
    global $CONFIG;
    require($path . 'vendor/autoload.php');
    $userVideos = $ct = array();
    $config = $CONFIG['solr_config'];
//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
    $lang = LanguageGet();
    $langstring = "(+(lang:$lang lang:xx) -(+lang:xx +$lang:1)) AND ";
//$langstring = "";
    $client = new Solarium\Client($config);
    $client->setAdapter('Solarium\Core\Client\Adapter\Http');
    $query = $client->createSelect();
    $helper = $query->getHelper();
    $privacyString = "$langstring is_public:2";
    if (userIsLogged()) {
        $userId = userGetID();
        $privacyString = "$langstring (is_public:2 OR allowed_users:*|$userId|*)";
    }

    $searchString = "$privacyString AND type:m";
    $qq = $srch_options['q'];
    $qq = $helper->escapeTerm($qq);
    search_log($qq, 'W_MEDIA');
    if ($qq <> '')
        $searchString .= " AND( title_t1:'$qq' OR description_t1:'$qq' OR city_name_accent:'$qq' )";
    $catSearchString = $searchString;
    if (isset($srch_options['t']) && $srch_options['t'] <> '' && $srch_options['t'] <> 'a')
        $catSearchString .= " +mtype:" . $srch_options['t'];
    $cat_query = $client->createSelect();
    $cat_query->setQuery($catSearchString);
    $cat_facetSet = $cat_query->getFacetSet();
    $cat_facetSet->createFacetField('cat_id')->setField('cat_id');
//print_r($cat_query);
    $cat_result = $client->select($cat_query);
    $cat_facet = $cat_result->getFacetSet()->getFacet('cat_id');
    $categories = array();
    foreach ($cat_facet as $value => $count) {
        if ($count > 0) {
            $categories[] = $value;
        }
    }
    $userVideos['categories'] = $categories;
    if (isset($srch_options['c']) && $srch_options['c'] <> '' && $srch_options['c'] <> 0)
        $searchString .= " +cat_id:" . $srch_options['c'];

    try {

//$edismax = $query->getEDisMax();
//$edismax->setQueryFields('title_t1^40 description_t1^30 city_name^2');
//echo $searchString;
        $query = $client->createSelect();
        $query->setQuery($searchString);
        $facetSet = $query->getFacetSet();
        $facetSet->createFacetField('mtype')->setField('mtype');
        $resultset = $client->select($query);
        $userVideos['numFound'] = $resultset->getNumFound();
        $facet = $resultset->getFacetSet()->getFacet('mtype');
        foreach ($facet as $value => $count)
            $ct[$value] = $count;
        $userVideos['n_videos'] = $ct['v'];
        $userVideos['n_images'] = $ct['i'];

        $query = $client->createSelect();
        if (isset($srch_options['t']) && $srch_options['t'] <> '' && $srch_options['t'] <> 'a')
            $searchString .= " AND mtype:" . $srch_options['t'];

//print_r($searchString);
//mail ('charbel@paravision.org', 'searchString', $searchString);
//$edismax = $query->getEDisMax();
//$edismax->setQueryFields('title_t1^4 description_t1^3 city_name_accent^2');

        $spellcheck = $query->getSpellcheck();
        $spellcheck->setQuery($qq);
        $spellcheck->setBuild(true);
        $spellcheck->setCollate(true);
//$spellcheck->setExtendedResults(true);
//$spellcheck->setCollateExtendedResults(true);

        $query->setStart(($srch_options['page'] - 1) * $srch_options['limit']);
        $query->setRows($srch_options['limit']);
        switch ($srch_options['orderby']) {
            case 'Date': $query->addSort('pdate', $query::SORT_DESC);
                break;
//    case 'Title': $query->addSort('title_t1 asc'); break;
            case 'Title': $query->addSort('search_suggest', $query::SORT_ASC);
                break;
            default: $searchString = "{!MediaSearch}" . $searchString;
        }
        $query->setQuery($searchString);
        $resultset = $client->select($query);
        $spellcheckResult = $resultset->getSpellcheck();
        if (isset($spellcheckResult) && !$spellcheckResult->getCorrectlySpelled()) {
            $collation = $spellcheckResult->getCollation();
            $k = 1;
            if (isset($collation)) {
                //debug($collation);

                $userVideos['corrections'][0] = $collation->getQuery();
                if ($k > 0) {
                    $new_term = $userVideos['corrections'][0];
                    $searchString = "$privacyString AND type:m";
                    $searchString .= " AND ( title_t1:'$new_term' OR description_t1:'$new_term' OR city_name_accent:'$new_term' )";
                    $catSearchString = $searchString;
                    if (isset($srch_options['t']) && $srch_options['t'] <> '' && $srch_options['t'] <> 'a')
                        $catSearchString .= " AND mtype:" . $srch_options['t'];
                    $cat_query = $client->createSelect();
                    $cat_query->setQuery($catSearchString);
                    $cat_facetSet = $cat_query->getFacetSet();
                    $cat_facetSet->createFacetField('cat_id')->setField('cat_id');
                    $cat_result = $client->select($cat_query);
                    $cat_facet = $cat_result->getFacetSet()->getFacet('cat_id');
                    $categories = array();
                    foreach ($cat_facet as $value => $count) {
                        if ($count > 0) {
                            $categories[] = $value;
                        }
                    }
                    $userVideos['categories'] = $categories;
                    $query = $client->createSelect();
                    if (isset($srch_options['t']) && $srch_options['t'] <> '' && $srch_options['t'] <> 'a')
                        $searchString .= " AND mtype:" . $srch_options['t'];

//            $edismax = $query->getEDisMax();
//            $edismax->setQueryFields('title_t1^4 description_t1^3 city_name_accent^2');
                    $query->setStart(($srch_options['page'] - 1) * $srch_options['limit']);
                    $query->setRows($srch_options['limit']);
                    switch ($srch_options['orderby']) {
                        case 'Date': $query->addSort('pdate', $query::SORT_DESC);
                            break;
                        case 'Title': $query->addSort('search_suggest', $query::SORT_ASC);
                            break;
                        default: $searchString = "{!MediaSearch}" . $searchString;
                    }
                    $query->setQuery($searchString);
                    $facetSet = $query->getFacetSet();
                    $facetSet->createFacetField('mtype')->setField('mtype');
                    $resultset = $client->select($query);
                    $userVideos['numFound'] = $resultset->getNumFound();
                    $facet = $resultset->getFacetSet()->getFacet('mtype');
                    foreach ($facet as $value => $count)
                        $ct[$value] = $count;
                    $userVideos['n_videos'] = $ct['v'];
                    $userVideos['n_images'] = $ct['i'];
                }
            }
        }
        $userVideos['totalPages'] = ceil($resultset->getNumFound() / $srch_options['limit']);
        $k = 0;
        foreach ($resultset as $document) {
            $userVideos['media'][$k]['id'] = $document->id;
            $userVideos['media'][$k]['title'] = $document->title_t1;
            $userVideos['media'][$k]['image_video'] = $document->mtype;
            $userVideos['media'][$k]['location'] = $document->location_t;
            $userVideos['media'][$k]['nb_views'] = $document->nb_views;
            $userVideos['media'][$k]['pdate'] = $document->pdate;
            $userVideos['media'][$k]['like_value'] = $document->like_value;
            $userVideos['media'][$k]['nb_comments'] = $document->nb_comments;
            $userVideos['media'][$k]['nb_ratings'] = $document->nb_comments;
            $userVideos['media'][$k]['description'] = $document->description_t1;
            $userVideos['media'][$k]['video_url'] = $document->url;
            $userVideos['media'][$k]['rating'] = $document->rating;
            $userVideos['media'][$k]['name'] = $document->name_t;
            $userVideos['media'][$k]['code'] = $document->code_m;
            $userVideos['media'][$k]['fullpath'] = $document->fullpath_m;
            $userVideos['media'][$k]['relativepath'] = $document->relativepath_m;
            $k++;
        }
//mail ('charbel@paravision.org', 'userVideos loc', print_r($userVideos, 1));
        return $userVideos;
    } catch (Exception $exception) {
        echo $exception;
        return $exception;
    }
}

function search_log($term, $page) {

//  <start>
	global $dbConn;
	$params = array();
    $term = trim($term);
    if ($term !== '') {
        $date = date('Y/m/d H:i:s');
        $query = "INSERT INTO cms_search_log (search_type, search_string, search_ts) 
                VALUES (:Page, :Term, :Date)";
        
	$params[] = array( "key" => ":Page",
                            "value" =>$page);  
	$params[] = array( "key" => ":Term",
                            "value" =>$term);  
	$params[] = array( "key" => ":Date",
                            "value" =>$date);  
	$insert = $dbConn->prepare($query);
	PDO_BIND_PARAM($insert,$params);
	$insert->execute();
    }
}

/**
 * search for videos given certain options. options include:<br/>
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>skip</b>: specific number of records skip. default 0<br/>
 * <b>is_public</b>: wheather the media file is public or not. 0 => private, 1=> community, 2=> public. default 2<br/>
 * <b>userid</b>: the media file's owner's id. default null<br/>
 * <b>favorite</b>: dont get the user's media - get his favorite media. default false.<br/>
 * <b>type</b>: what type of media file (v)ideo or (i)mage or (a)ll or (u)ser. default 'v'<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id' or similarity<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>latitude</b>: the latitude of the location to search within<br/>
 * <b>longitude</b>: the logitude of the location to search within<br/>
 * <b>radius</b>: the radius to search within (in meters)<br/>
 * <b>dist_alg</b>: the distance algorithm to use (s)quare [faster], or (c)ircular [slower]. default is 's'<br/>
 * <b>search_string</b>: the string to search for. could be space separated. no default<br/>
 * <b>search_where</b>: where to search for the string (t)itle, (d)escription, (k)eywords, (a)ll, or a comma separated combination. default is 'a'<br/>
 * <b>search_strict</b>: if the search is strict or not default 1. if 0 similarity is not available.<br/>
 * <b>date_from</b>: get records newer than this one's date. default null<br/>
 * <b>date_to</b>: get records older than this one's date. default null<br/>
 * <b>max_id</b>: get records less than this one. (implied orderby 'id' and order 'd').<br/>
 * <b>min_id</b>: get records greater than this one. (implied orderby 'id' and order 'a').<br/>
 * <b>vid</b>: get records similar to the record given by this id.<br/>
 * <b>similarity</b>: in what way similar? (l)ocation, (k)eywords, (t)itle, (a)ny similarity, or a comma separated combination. default is 'a'. no effect if search_strict is 0.<br/>
 * <b>cat_id</b>: the category id to search for.<br/>
 * <b>catalog_id</b>: the catalog_id to search for. default null.<br/>
 * <b>channel_id</b>: the channel_id to search for. default null. -1 => disregard channel_id, -2 => any channel<br/>
 * <b>location_id</b>: the location_id to search for.<br/>
 * <b>city_id</b>: the city_id to search for. default null.<br/>
 * <b>country</b>: the country to search for. default null.<br/>
 * <b>continent</b>: the continent to search for. default null.<br/>
 * <b>multi_order</b>: array. use in case of multiple orderbys in format array(orderby=>col,orderby=>order). overrides the <b>orderby</b> option. default null.<br/>
 * <b>catalog_status</b>: integer. -1 => dont care, 0 => is not part of a catalog, 1=>is part of a catalog. default -1.<br/>
 * <b>is_owner</b>: integer. 0 => not owner (get all media for this user related to the privacy ) , 1 => owner(it does check privacy for the user). default 0.<br/>
 * <b>n_results</b>: gets the number of results rather than the rows. default false.
 * @param array $srch_options. the search options
 * @return array a number of media records
 */
function mediaSearch($srch_options) {

//  <start>
	global $dbConn;
	$params = array();

    $default_opts = array(
        'limit' => 6,
        'page' => 0,
        'skip' => 0,
        'is_public' => null,
        'userid' => null,
        'favorite' => false,
        'type' => 'v',
        'orderby' => 'id',
        'order' => 'a',
        'is_owner' => 0,
        'latitude' => null,
        'longitude' => null,
        'radius' => 1000,
        'dist_alg' => 's',
        'search_string' => null,
        'search_where' => 'a',
        'search_strict' => 1,
        'max_id' => null,
        'min_id' => null,
        'vid' => null,
        'media_id' => null,
        'similarity' => 't,k',
        'cat_id' => null,
        'catalog_id' => null,
        'location_id' => null,
        'city_id' => null,
        'continent' => null,
        'country' => null,
        'n_results' => false,
        'min_similarity' => 0,
        'date_from' => null,
        'date_to' => null,
        'multi_order' => null,
        'channel_id' => null,
        'catalog_status' => -1,
        'featured' => 0,
        'exclude_id' => 0
    );

    $options = array_merge($default_opts, $srch_options);
    //TTDebug( DEBUG_TYPE_SEARCH, DEBUG_LVL_INFO, print_r($options,true));

    if (!is_null($options['search_string']) && (strlen($options['search_string']) == 0)) {
        $options['search_string'] = null;
    }

    //if no vlaid search string make sure to remove similarity orderby
    if (is_null($options['search_string'])) {

        if (( ($options['orderby'] == 'similarity') || ($options['orderby'] == 'matches') ) && is_null($options['vid']))
            $options['orderby'] = 'id';

        if ($options['multi_order'] != null && is_null($options['vid'])) {
            unset($options['multi_order']['similarity']);
            unset($options['multi_order']['matches']);
        }
    }

    $options['max_id'] = intval($options['max_id']);
    $options['min_id'] = intval($options['min_id']);

    if (($ch = channelGlobalGet()) != null) {
        //$options['channel_id'] = $ch['id'];
        $options['userid'] = $ch['owner_id'];
    }
    $userId = $options['userid'];
    if (userIsLogged() && ($userId == userGetID())) {
        $options['privacy'] = 0;
    } else if (userIsLogged() && ($userId != userGetID())) {

        if (userIsFriend($userId, userGetID())) {
            $options['privacy'] = 1;
        } else {
            $options['privacy'] = 2;
        }
    } else {
        $options['privacy'] = 2;
    }

    /* no trends from search queries
      if($options['search_string'] !== null)
      queryAdd($options['search_string']);
     */

    $nlimit = '';
    if ( !is_null($options['limit']) ) {
        $nlimit = intval($options['limit']);
        $skip = (intval($options['page']) * $nlimit) + intval($options['skip']);
    }

    $where = '';

    if ($where != '')
        $where .= ' AND ';
    $where .= " published=" . MEDIA_READY;

    $like_string = '';

    if (!is_null($options['cat_id'])) {
        if ($where != '')
            $where .= ' AND ';

        //also search in title, descrtiption for category name
        //FUTURE NOTE: add to rules when multiple categories languages
        $cat_name = categoryGetName($options['cat_id']);

        if ($cat_name != false) {
            $cat_name = strtolower($cat_name);
            $options['search_string'] .= ' ' . $cat_name;
            
            $search_strings = explode(' ', $cat_name);
            $similarity_arr = explode(',', $options['search_where']);

            $string_search = '';

            $searched = array();

            foreach ($search_strings as $in_search_string) {

                $search_string = trim(strtolower($in_search_string));
                $search_string = preg_replace('/[^a-z0-9A-Z]/', '', $search_string);

                //TTDebug(DEBUG_TYPE_SEARCH,DEBUG_LVL_INFO, "SSSS1 $search_string");
                //remove 's' character from end of string
                $search_string = rtrim($search_string, 's');
                //add more "rules" here
                //TTDebug(DEBUG_TYPE_SEARCH,DEBUG_LVL_INFO, "SSSS2 $search_string");

                if (strlen($search_string) <= 1)
                    continue;

                if (in_array($search_string, $searched))
                    continue;

                if (_searchStringDontSearch($search_string))
                    continue;

                $searched[] = $search_string;
            }
            //$string_search = " EXISTS (SELECT vid FROM cms_videos_words WHERE vid=V.id AND find_in_set(cast(word as char), :Esearched)"; 
            //$params[] = array( "key" => ":Esearched", "value" =>implode("','", $searched));
            //$where .= " ( $string_search OR category=:Cat_id ) ";
            $where .= " ( category=:Cat_id ) ";
            $params[] = array( "key" => ":Cat_id","value" =>$options['cat_id']);  
        } else {
            $where .= " category=:Cat_id2 ";
            $params[] = array( "key" => ":Cat_id2","value" =>$options['cat_id']);  
        }
    }

    if (!is_null($options['city_id'])) {
        if ($where != '')
            $where .= ' AND ';

        //also search in title
        //$cityInfo = cityGetInfo($options['city_id']);
        //if( ($cityInfo != false) && ($options['strict_search'] == 1) ){
        //in case we seach for paris either search for cityif or inside the record itself
        /* if( ($cityInfo != false) ){
          $city_name = strtolower( $cityInfo['name'] );
          $options['search_string'] .= ' ' . $city_name;
          $tmp_srch_query = "("  . _searchQueryHelper2($city_name,$options['search_where']) . ")";
          $where .= " ( $tmp_srch_query OR cityid={$options['city_id']} ) ";
          }else{ */
//        $where .= " cityid={$options['city_id']} ";
        $where .= " cityid=:City_id ";
        $params[] = array( "key" => ":City_id","value" =>$options['city_id']);  
        //}
    }
    if (!is_null($options['exclude_id'])) {
        if ($where != '')
            $where .= ' AND ';
        $where .= " id <> :Id";
        $params[] = array( "key" => ":Id","value" => $options['exclude_id']);  
        //}
    }
    

    if ($options['featured'] != 0) {
        if ($where != '')
            $where .= ' AND ';
        
        $where .= " featured=:Featured ";
        $params[] = array( "key" => ":Featured","value" =>1);  
    }

    if (!is_null($options['country'])) {
        if ($where != '')
            $where .= ' AND ';

        //also search in title
        /* $country_name = countryGetName($options['country']);

          if( $country_name != false ){
          $country_name = strtoupper( $country_name );
          $options['search_string'] .= ' ' . $country_name;
          $tmp_srch_query = "("  . _searchQueryHelper2($country_name,$options['search_where']) . ")";
          $where .= " ( $tmp_srch_query OR country='{$options['country']}' ) ";
          }else{ */
//        $where .= " country='{$options['country']}' ";
        $where .= " country=:Country ";
        $params[] = array( "key" => ":Country","value" =>$options['country']);  
        //}
    }

    if (!is_null($options['continent'])) {
        if ($where != '')
            $where .= ' AND ';

//        $where .= " country IN (SELECT code FROM cms_countries WHERE continent_code='{$options['continent']}') ";
        $where .= " country IN (SELECT code FROM cms_countries WHERE continent_code=:Continent) ";
        $params[] = array( "key" => ":Continent","value" =>$options['continent']); 
    }

    if (!is_null($options['location_id'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " location_id={$options['location_id']} ";
        $where .= " location_id=:Location_id ";
        $params[] = array( "key" => ":Location_id","value" =>$options['location_id']); 
    }

    ////////////////
    //no longer valid. a user can own multiple channels
    if (isset($options['channel_id'])) {
        $ch_id = $options['channel_id'];
//        $tquery = "SELECT owner_id FROM cms_channel WHERE id='$ch_id'";
//        $ret = db_query($tquery);
        $params2 = array();
        $tquery = "SELECT owner_id FROM cms_channel WHERE id=:Ch_id";
	$params2[] = array( "key" => ":Ch_id","value" =>$ch_id);  
	$select = $dbConn->prepare($tquery);
	PDO_BIND_PARAM($select,$params2);
	$res = $select->execute();

	$ret    = $select->rowCount();
//        if ($ret && db_num_rows($ret)) {
        if ($res && $ret) {
            $row = $select->fetch();
            if ($where != '')
                $where .= ' AND ';
//            $where .= " userid={$row[0]} ";
            $where .= " userid=:Owner_id ";
            $params[] = array( "key" => ":Owner_id","value" =>$row[0]); 
        }
    }

    ////////////////
    //no longer valid. a user can own multiple channels
    if (!is_null($options['channel_id'])) {
        if ($options['channel_id'] == -2) {
            if ($where != '')
                $where .= ' AND ';
            $where .= " channelid<>0 ";
        }else if ($options['channel_id'] != -1) {
            if ($where != '')
                $where .= ' AND ';
//            $where .= " channelid='{$options['channel_id']}' ";
            $where .= " channelid=:Channel_id ";
            $params[] = array( "key" => ":Channel_id","value" =>$options['channel_id']); 
        }
    }else {
        if ($where != '')
            $where .= ' AND ';
        $where .= " channelid='0' ";
    }

    if ($options['type'] != 'a') {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " image_video='{$options['type']}' ";
        $where .= " image_video=:Type ";
        $params[] = array( "key" => ":Type","value" =>$options['type']); 
    }
    if (!is_null($options['media_id'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " id='{$options['media_id']}' ";
        $where .= " id=:Media_id ";
        $params[] = array( "key" => ":Media_id","value" =>$options['media_id']); 
    }

    //similar videos
    if (!is_null($options['vid']) && ($options['max_id'] === 0) && ($options['min_id'] === 0)) {

        //////////
        //check for cache
        //$cacheSimilarName = videoSimCacheName($options['vid']);

        //maximum number of pages to cache
        $max_cached_pages = 10;

//        if (cacheIsSet($cacheSimilarName)) {
//
//            $cacheSimilar = cacheGet($cacheSimilarName);
//
//            $cachePage = $cacheSimilar[0];
//            $cacheRecords = $cacheSimilar[1];
//            $page = intval($options['page']);
//
//            //cache is still valid
//            if ($page < $cachePage + $max_cached_pages) {
//                TTDebug(DEBUG_TYPE_SEARCH, DEBUG_LVL_INFO, "cache hit for video: {$options['vid']}, page: $page");
//                return array_slice($cacheRecords, $page * $nlimit, $nlimit);
//            }
//
//            TTDebug(DEBUG_TYPE_SEARCH, DEBUG_LVL_INFO, "cache miss 1 for video: {$options['vid']}, page: $page");
//            //cache is not valid the cache will be updated below
//        } else {
            $page = intval($options['page']);
        //    TTDebug(DEBUG_TYPE_SEARCH, DEBUG_LVL_INFO, "cache miss 2 for video: {$options['vid']}, page: $page");
        //}

        //cache more than needed results
        $nlimit = $nlimit * $max_cached_pages;

        //no cache or not valid
        /////////////////
    }

    if (!is_null($options['vid'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " id <> {$options['vid']} ";
        $where .= " id <> :Vid ";
        $params[] = array( "key" => ":Vid","value" =>$options['vid']); 

        $tmpVinfo = getVideoInfo($options['vid']);

        if ($tmpVinfo == false) {
            TTDebug(DEBUG_TYPE_SEARCH, DEBUG_LVL_WARN, "similarity on video {$options['vid']} that doesnt exist");
            return array();
        }

        $similarity_arr = explode(',', $options['similarity']);

        //first the location
        if (in_array('a', $similarity_arr) || in_array('l', $similarity_arr)) {
            $latitude = $tmpVinfo['lattitude'];
            $longitude = $tmpVinfo['longitude'];

            if (($latitude == '0') || ($longitude == '0')) {
                //musnt add
            } else {
                $options['latitude'] = $latitude;
                $options['longitude'] = $longitude;
            }
        }

        //now the strings
        $final_search_string_arr = array();
        if (in_array('a', $similarity_arr)) {
            $options['search_where'] = 'a';
        } else {
            $options['search_where'] = implode(',', $similarity_arr);
        }

        if (in_array('a', $similarity_arr) || in_array('k', $similarity_arr)) {
            //remove category from keywords
            $keywords = $tmpVinfo['keywords'];
            $keywords = explode(',', $keywords);
            array_pop($keywords);
            $keywords = implode(',', $keywords);
            $final_search_string_arr[] = $keywords;
        }

        if (in_array('a', $similarity_arr) || in_array('t', $similarity_arr)) {
            $final_search_string_arr[] = $tmpVinfo['title'];
        }

        if (count($final_search_string_arr) != 0) {
            $options['search_string'] = implode(' ', $final_search_string_arr);
        }

        //hard code category orderby
        /* doesnt work well for video similarity
          if( !is_null($options['multi_order']) ){
          $orders_keys = array_keys($options['multi_order']);
          $order_values = array_values($options['multi_order']);
          //if the abs category order doesnt exist add it
          if( strstr($orders_keys[0],"ABS") == null ){
          array_unshift($orders_keys,"ABS(category - {$tmpVinfo['category']})");
          array_unshift($order_values,"a");
          $options['multi_order'] = array_combine($orders_keys, $order_values);
          }else{

          }
          }else{
          $options['multi_order'] = array("ABS(category - {$tmpVinfo['category']})" => 'a' , $options['orderby'] => $options['order']);
          }
         */
    }



    if (!is_null($options['userid'])) {

        if ($options['favorite'] == false) {
            if ($where != '')
                $where .= ' AND ';
//            $where .= " userid={$options['userid']} ";
            $where .= " userid=:Userid ";
            $params[] = array( "key" => ":Userid","value" =>$options['userid']);
        }else {
            if ($where != '')
                $where .= ' AND ';
//            $where .= " id IN (SELECT entity_id FROM cms_social_favorites WHERE user_id={$options['userid']} AND entity_type='" . SOCIAL_ENTITY_MEDIA . "' AND published = 1 ) ";
            $where .= " id IN (SELECT entity_id FROM cms_social_favorites WHERE user_id=:Userid2 AND entity_type='" . SOCIAL_ENTITY_MEDIA . "' AND published = 1 ) ";
            $params[] = array( "key" => ":Userid2","value" =>$options['userid']);
        }
    }

    //this used to be inside !is_null($options['userid'])
    if (!is_null($options['catalog_id'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " EXISTS (SELECT catalog_id FROM cms_videos_catalogs WHERE video_id=V.id AND catalog_id={$options['catalog_id']})";
        $where .= " EXISTS (SELECT catalog_id FROM cms_videos_catalogs WHERE video_id=V.id AND catalog_id=:Catalog_id)";
        $params[] = array( "key" => ":Catalog_id","value" =>$options['catalog_id']);
    }

    if (userIsLogged()) {
        if (intval($options['is_owner']) == 0) {
            $searcher_id = userGetID();
            $params3 = array();
            $friends = userGetFreindList($searcher_id);

            $friends_ids = array($searcher_id);
            foreach ($friends as $freind) {
                $friends_ids[] = $freind['id'];
            }
            if (count($friends_ids) != 0) {
                if ($where != '')
                    $where .= " AND ";
                $public = USER_PRIVACY_PUBLIC;
                $private = USER_PRIVACY_PRIVATE;
                $selected = USER_PRIVACY_SELECTED;
                $community = USER_PRIVACY_COMMUNITY;
                $privacy_where = '';

                $where .= "CASE";
                $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=V.id AND PR.entity_type=" . SOCIAL_ENTITY_MEDIA . " AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) THEN 1";
                $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=V.id AND PR.entity_type=" . SOCIAL_ENTITY_MEDIA . " AND PR.published=1  LIMIT 1 ) THEN 1";
                $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=V.id AND PR.entity_type=" . SOCIAL_ENTITY_MEDIA . " AND PR.published=1 AND PR.user_id = :Searcher_id LIMIT 1 ) THEN 1";
                $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=V.id AND PR.entity_type=" . SOCIAL_ENTITY_MEDIA . " AND PR.published=1 AND PR.kind_type=:Community AND PR.user_id IN (".implode(',', $friends_ids).") LIMIT 1 ) THEN 1";
                $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=V.id AND PR.entity_type=" . SOCIAL_ENTITY_MEDIA . " AND PR.published=1 AND PR.kind_type=:Private AND PR.user_id=:Searcher_id2 LIMIT 1 ) THEN 1";
                $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=V.id AND PR.entity_type=" . SOCIAL_ENTITY_MEDIA . " AND PR.published=1 AND ( ( FIND_IN_SET( :Community2 , CONCAT( PR.kind_type ) ) AND PR.user_id IN (".implode(',', $friends_ids).") ) OR ( FIND_IN_SET( :Searcher_id3 , CONCAT( PR.users ) ) )  )   LIMIT 1 ) THEN 1";
                $params[] = array( "key" => ":Public","value" =>$public);
                $params[] = array( "key" => ":Searcher_id","value" =>$searcher_id);
                $params[] = array( "key" => ":Community","value" =>$community);
                $params[] = array( "key" => ":Private","value" =>$private);
                $params[] = array( "key" => ":Searcher_id2","value" =>$searcher_id);
                $params[] = array( "key" => ":Community2","value" =>$community);
                $params[] = array( "key" => ":Searcher_id3","value" =>$searcher_id);
                $where .= " ELSE 0 END ";
            }
        }
    }else {
        $public = USER_PRIVACY_PUBLIC;
        if ($where != '')
            $where .= ' AND ';
        $where .= "CASE";
        $where .= " WHEN V.is_public='$public' THEN 1";
        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=V.id AND PR.entity_type=" . SOCIAL_ENTITY_MEDIA . " AND PR.published=1 AND PR.kind_type=:Public2 LIMIT 1 ) THEN 1";
        $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=V.id AND PR.entity_type=" . SOCIAL_ENTITY_MEDIA . " AND PR.published=1  LIMIT 1 ) THEN 1";
        $where .= " ELSE 0 END ";
        $params[] = array( "key" => ":Public2", "value" =>$public);
    }

    if (!is_null($options['date_from'])) {
        if ($where != '')
            $where .= ' AND ';
        $where .= " DATE(pdate) >= :Date_from ";
        $params[] = array( "key" => ":Date_from", "value" =>$options['date_from']);
    }
    if (!is_null($options['is_public'])) {
        if ($where != '')
            $where .= ' AND ';
        $where .= " is_public = :Is_public ";
        $params[] = array( "key" => ":Is_public", "value" =>$options['is_public']);
    }

    if (!is_null($options['date_to'])) {
        if ($where != '')
            $where .= ' AND ';
        $where .= " DATE(pdate) <= :Dte_to ";
        $params[] = array( "key" => ":Dte_to", "value" =>$options['date_to']);
    }

    //in case vid is set dont search by location
    if (is_null($options['vid']) && !is_null($options['latitude']) && !is_null($options['longitude']) && !is_null($options['radius'])) {
        $lat = doubleval($options['latitude']);
        $long = doubleval($options['longitude']);
        $radius = intval($options['radius']);

        if ($where != '')
            $where .= ' AND ';

        if ($options['dist_alg'] == 's') {
            //the 1 latitude degree ~= 110km, 1 degree longitude = 110km at equater, 78 at 45 degrees, 0 at pole
            //for longitude at [33,35] square 0.1 => 14km, 0.01 => 1.4km, 0.001 => 140m so. good approx for a degree square is approx 140km
            //use formula for longitude
            $long_rad = deg2rad($long);
            $c = 40075;

            $lat_conv = doubleval(110000.0);
            $long_conv = (1000 * $c * cos($long_rad)) / 360;

            $diff_lat = $radius / $lat_conv;
            $diff_long = $radius / $long_conv;

            $where .= " squareLocationDiff(lattitude,longitude,:Lat,:Long,:Diff_lat,:Diff_long) ";
            $params[] = array( "key" => ":Lat", "value" =>$lat);
            $params[] = array( "key" => ":Long", "value" =>$long);
            $params[] = array( "key" => ":Diff_lat", "value" =>$diff_lat);
            $params[] = array( "key" => ":Diff_long", "value" =>$diff_long);
        } else {
            //$where .= " LocationDIff(lattitude,longitude,$lat,$long) <= $radius ";
            $where .= " LocationDIff(lattitude,longitude,:Lat2,:Long2) <=:Radius) ";
            $params[] = array( "key" => ":Lat2", "value" =>$lat);
            $params[] = array( "key" => ":Long2", "value" =>$long);
            $params[] = array( "key" => ":Radius", "value" =>$radius);
        }
    }

    $searched = array();

    $sub_query = '';
    $sim_table = '';

    if (!is_null($options['search_string']) && ($options['search_strict'] == 1)) {
        //only use table if strict search is on
        $search_strings = explode(' ', $options['search_string']);

//        foreach ($search_strings as $in_search_string) {
//
//            $search_string = trim(strtolower($in_search_string));
//            $search_string = preg_replace('/[^a-z0-9A-Z]/', '', $search_string);
//
//            //if( strlen($search_string) <= 1) continue;
//
//            if (in_array($search_string, $searched))
//                continue;
//
//            if (_searchStringDontSearch($search_string))
//                continue;
//
//            $searched[] = $search_string;
//        }
//        $all_words = implode("','", $searched);

      //  $sim_table = " INNER JOIN (SELECT vid, SUM(weight) AS similarity, COUNT(weight) AS matches FROM cms_videos_words AS W WHERE W.word IN ('$all_words') GROUP BY vid) AS S ON S.vid=V.id ";
    }else if (!is_null($options['search_string']) && ($options['search_strict'] == 0)) {
        //if strict search is off use cms_videos table and LIKE
        $search_strings = explode(' ', $options['search_string']);
        $similarity_arr = explode(',', $options['similarity']);

        $string_search = '';

        foreach ($search_strings as $in_search_string) {

            $search_string = trim(strtolower($in_search_string));
            $search_string = preg_replace('/[^a-z0-9A-Z]/', '', $search_string);

            //if( strlen($search_string) <= 1) continue;

            if (in_array($search_string, $searched))
                continue;

            $searched[] = $search_string;

            if (in_array('a', $similarity_arr) || in_array('k', $similarity_arr)) {
                //remove category from keywords
                if ($string_search != '')
                    $string_search .= " OR ";
                $string_search .= " LOWER(keywords) LIKE :Search_string ";
                $params[] = array( "key" => ":Search_string", "value" =>'%'.$search_string.'%'); 
            }

            if (in_array('a', $similarity_arr) || in_array('t', $similarity_arr)) {
                if ($string_search != '')
                    $string_search .= " OR ";
                $string_search .= " LOWER(title) LIKE :Search_string2 ";
                $params[] = array( "key" => ":Search_string2", "value" =>'%'.$search_string.'%'); 
            }

            if (in_array('a', $similarity_arr) || in_array('d', $similarity_arr)) {
                if ($string_search != '')
                    $string_search .= " OR ";
                $string_search .= " LOWER(description) LIKE :Search_string3 ";
                $params[] = array( "key" => ":Search_string3", "value" =>'%'.$search_string.'%'); 
            }
        }

        if ($string_search != '') {
            if ($where != '')
                $where .= ' AND ';
            $where .= " ( $string_search ) ";
        }
    }

    //-1 we dont care.
    //0 must not be part of a catalog
    //1 must not be part of a catalog
    if ($options['catalog_status'] == 0) {
        if ($where != '')
            $where .= ' AND ';
        $where .= " NOT EXISTS (SELECT video_id FROM cms_videos_catalogs WHERE video_id=V.id) ";
    }else if ($options['catalog_status'] == 1) {
        if ($where != '')
            $where .= ' AND ';
        $where .= " EXISTS (SELECT video_id FROM cms_videos_catalogs WHERE video_id=V.id) ";
    }

    $orderby = $options['orderby'];
    $order='';
    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }

    if ($options['max_id'] !== 0) {
        //$orderby = 'id';
        //$order = ' ASC ';

        if ($where != '')
            $where .= ' AND ';
        $where .= " id < :Max_id ";
        $params[] = array( "key" => ":Max_id", "value" =>$options['max_id']); 
    }

    if ($options['min_id'] !== 0) {
        //$orderby = 'id';
        //$order = ' ASC ';

        if ($where != '')
            $where .= ' AND ';
        $where .= " id > :Min_id ";
        $params[] = array( "key" => ":Min_id", "value" =>$options['min_id']); 
    }

    //dont use similarity if not strict_search
    if (!is_null($options['search_string']) && ($options['search_strict'] == 1)) {
        $where .= ' AND ';
        $where .= " similarity >= :Min_similarity ";
        $params[] = array( "key" => ":Min_similarity" , "value" =>$options['min_similarity']); 
    }

    $all_orders = "$orderby $order";
    if (!is_null($options['multi_order'])) {
        $all_orders = '';
        foreach ($options['multi_order'] as $loop_order_by => $loop_order) {
            if ($all_orders != '')
                $all_orders .= ', ';
            $all_orders .= " $loop_order_by ";
            if ($loop_order == 'd')
                $all_orders .= ' DESC ';
            else if ($loop_order == 'a')
                $all_orders .= ' ASC ';
        }
    }

    if ($options['n_results'] == false) {

        $sim = '';
       // if (!is_null($options['search_string']) && ($options['search_strict'] == 1))
        //    $sim = ',S.similarity';
        $query = "SELECT V.* $sim FROM `cms_videos` AS V $sim_table WHERE $where ORDER BY $all_orders";

        if (!is_null($options['limit'])) {
            $query .= " LIMIT $skip, $nlimit";
        }      
        
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
        
	$select->execute();
        $media = $select->fetchAll(PDO::FETCH_ASSOC);

        //in case of video similarity cache the excess results and only return the requested number of results
        if (!is_null($options['vid']) && ($options['max_id'] === 0) && ($options['min_id'] === 0)) {
            $cacheName = videoSimCacheName($options['vid']);
            $original_request_limit = intval($options['limit']);
            $original_request_page = intval($options['page']);

            $media = array_slice($media, 0, $original_request_limit);
        }

        return $media;
    } else {
        if (is_null($options['search_string']) || ($options['search_strict'] == 0)) {
            $query = "SELECT COUNT(V.id) FROM `cms_videos` AS V WHERE $where";
        } else {
            $query = "SELECT COUNT(T.id) FROM (SELECT V.id FROM `cms_videos` AS V $sim_table WHERE $where) AS T";
        }
        //TTDebug(DEBUG_TYPE_SEARCH, DEBUG_LVL_INFO, $query);

//        $ret = db_query($query);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$select->execute();
//        $row = db_fetch_array($ret);
        $row = $select->fetch();
        $n_results = $row[0];
        return $n_results;
    }

//  <end>
}

/**
 * search for media id list for a given album id:<br/>
 * <b>public</b>: wheather the media file is public or not. 0 => private, 1=> community, 2=> public. default 2<br/>
 * <b>type</b>: what type of media file (v)ideo or (i)mage or (a)ll or (u)ser. default 'v'<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id' or similarity<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>catalog_id</b>: the catalog_id to search for. default null.<br/>
 * <b>channelid</b>: the channel id to search for. default 0.<br/>
 * <b>is_owner</b>: integer. 0 => not owner (get all media for this user related to the privacy ) , 1 => owner(it does check privacy for the user). default 0.<br/>
 * @return array media id list for a given album id
 */
function mediaCatalogListId($srch_options) {

//  <start>
	global $dbConn;
	$params = array();

    $default_opts = array(
        'public' => 2,
        'type' => 'v',
        'orderby' => 'id',
        'order' => 'a',
        'is_owner' => 0,
        'channelid' => 0,
        'catalog_id' => null
    );

    $options = array_merge($default_opts, $srch_options);
    $where = '';

    if ($where != '')
        $where .= ' AND ';
    $where .= " published=" . MEDIA_READY;
    
    if(is_null($options['channelid'])) $options['channelid'] = 0;
    if ($where != '') $where .= ' AND ';
    $where .= " channelid=:Channelid ";
    $params[] = array( "key" => ":Channelid", "value" =>$options['channelid']);  

    if ($options['type'] != 'a') {
        if ($where != '')
            $where .= ' AND ';
        $where .= " image_video=:Type ";
        $params[] = array( "key" => ":Type", "value" =>$options['type']); 
    }

    //this used to be inside !is_null($options['userid'])
    if (!is_null($options['catalog_id'])) {
        if ($where != '')
            $where .= ' AND ';
        $where .= " EXISTS (SELECT catalog_id FROM cms_videos_catalogs WHERE video_id=V.id AND catalog_id=:Catalog_id)";
        $params[] = array( "key" => ":Catalog_id", "value" =>$options['catalog_id']); 
    }

    if (userIsLogged()) {
        if (intval($options['is_owner']) == 0) {
            $searcher_id = userGetId();
            if ($where != '')
                $where .= ' AND ';

            //TODO: now that there is extended friend_list dont think IN should be used anymore
            $community = USER_PRIVACY_COMMUNITY;
            $private = USER_PRIVACY_PRIVATE;
            $public = USER_PRIVACY_PUBLIC;
            $selected = USER_PRIVACY_SELECTED;

            $friends_list = userGetFreindList($searcher_id);
            $friends = array();
            foreach ($friends_list as $freind_row) {
                $friends[] = $freind_row['id'];
            }
            $friends[] = $searcher_id;
            $friends_queryar = implode(',', $friends);
            $friends_query = " OR (is_public = :Community  AND find_in_set(cast(userid as char), :Friends_query) ) ";
            $params[] = array( "key" => ":Community", "value" =>$community); 
            $params[] = array( "key" => ":Friends_query", "value" =>$friends_queryar);

            $selected_query = " OR (is_public=:Selected AND EXISTS (SELECT to_user FROM cms_social_permissions WHERE from_user=id AND to_user=:Searcher_id  ) ) ";
            $params[] = array( "key" => ":Selected", "value" =>$selected); 
            $params[] = array( "key" => ":Searcher_id", "value" =>$searcher_id); 

            $where .= " (is_public = $public $friends_query $selected_query OR (is_public = :Private AND userid=:Searcher_id2) )";
            $params[] = array( "key" => ":Private", "value" =>$private); 
            $params[] = array( "key" => ":Searcher_id2", "value" =>$searcher_id); 
        }
    } else {
        if ($where != '')
            $where .= ' AND ';
        $where .= " is_public = " . USER_PRIVACY_PUBLIC;
    }

    $orderby = $options['orderby'];
    $order='';
    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }
    $all_orders = "$orderby $order";
    $query = "SELECT id FROM `cms_videos` AS V WHERE $where ORDER BY $all_orders";

//    $ret = db_query($query);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $select->execute();
    $row = $select->fetchAll();
    
    $media = array();
    foreach($row as $row_item){
       $media[] = $row_item['id'];
    }       
    return $media;

//  <end>
}

/**
 * gets all photos taken by a user around the same time as a photo
 * @param array a cms_videos record of the original photo
 * @return array set of cms_videos records
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function photosGetTime($photoInfo) {
//    $limit = 4;
//    $max = $limit * 2;
//
//    $options = array('userid' => $photoInfo['userid'], 'limit' => $limit, 'page' => 0, 'max_id' => $photoInfo['id'], 'type' => 'i');
//    $privacy = 0;
//    if (userIsLogged() && ($photoInfo['userid'] == userGetID())) {
//        $privacy = 0;
//    } else if (userIsLogged() && ($photoInfo['userid'] != userGetID())) {
//        if (userIsFriend($photoInfo['userid'], userGetID())) {
//            $privacy = 1;
//        } else {
//            $privacy = 2;
//        }
//    } else {
//        $privacy = 2;
//    }
//
//    $options['public'] = $privacy;
//
//    $array1 = mediaSearch($options);
//
//    $array1[] = $photoInfo;
//
//    //$array1 = array_merge($array1, mediaSearch($options));
//
//    if (count($array1) < $max) {
//        $diff = $max - count($array1);
//
//        $options = array('userid' => $photoInfo['userid'], 'limit' => $diff, 'page' => 0, 'min_id' => $photoInfo['id'], 'type' => 'i');
//        $options['public'] = $privacy;
//
//        $array2 = mediaSearch($options);
//
//        foreach ($array2 as $row2) {
//
//            $found = false;
//            foreach ($array1 as $row1) {
//                if ($row1['id'] == $row2['id']) {
//                    $found = true;
//                    break;
//                }
//            }
//
//            if (!$found)
//                $array1[] = $row2;
//        }
//    }
//
//    return $array1;
//}

/**
 * gets some photos related to the current photo
 * @param array a cms_videos record of the original photo
 * @return array set of cms_videos records
 */
//function photosGetRelated($photoInfo) {
//    /* $real_channel_id_random = NULL;
//      if(intval($photoInfo['channelid'])!=0){
//      $real_channel_id_random = -2;
//      }
//      $options = array('search_string' => $photoInfo['title'], 'limit' => 10,'channel_id' => $real_channel_id_random,'type' => $photoInfo['image_video'] , 'page' => 0 , 'params' =>array() );
//
//      $array1 = mediaSearchNoSQL($options);
//      return $array1; */
//
//    $limit = 4;
//    $max = $limit * 2;
//    $real_channel_id_random = NULL;
//    if (intval($photoInfo['channelid']) != 0) {
//        $real_channel_id_random = -2;
//    }
//    //$options = array('vid' => $photoInfo['id'], 'limit' => $limit, 'page' => 0, 'max_id' => $photoInfo['id'], 'type' => 'i', 'orderby' => 'similarity' , 'order' => 'd' , 'min_similarity' => 100);
//    $options = array('vid' => $photoInfo['id'], 'limit' => $limit, 'channel_id' => $real_channel_id_random, 'page' => 0, 'max_id' => $photoInfo['id'], 'type' => 'i', 'multi_order' => array('similarity' => 'd', 'id' => 'd'), 'min_similarity' => 100);
//    $privacy = 0;
//    if (userIsLogged() && ($photoInfo['userid'] == userGetID())) {
//        $privacy = 0;
//    } else if (userIsLogged() && ($photoInfo['userid'] != userGetID())) {
//        if (userIsFriend($photoInfo['userid'], userGetID())) {
//            $privacy = 1;
//        } else {
//            $privacy = 2;
//        }
//    } else {
//        $privacy = 2;
//    }
//
//    $options['public'] = $privacy;
//
//    $array1 = mediaSearch($options);
//    $array1 = array_reverse($array1);
//    $array1[] = $photoInfo;
//
//    //$array1 = array_merge($array1, mediaSearch($options));
//
//    if (count($array1) < $max) {
//        $diff = $max - count($array1);
//
//        //$options = array('vid' => $photoInfo['id'], 'limit' => $diff, 'page' => 0, 'min_id' => $photoInfo['id'], 'type' => 'i',  'orderby' => 'similarity' , 'order' => 'd' , 'min_similarity' => 100);
//        $options = array('vid' => $photoInfo['id'], 'limit' => $diff, 'page' => 0, 'channel_id' => $real_channel_id_random, 'min_id' => $photoInfo['id'], 'type' => 'i', 'multi_order' => array('similarity' => 'd', 'id' => 'a'), 'min_similarity' => 100);
//
//        $options['public'] = $privacy;
//
//        $array2 = mediaSearch($options);
//
//        foreach ($array2 as $row2) {
//
//            $found = false;
//            foreach ($array1 as $row1) {
//                if ($row1['id'] == $row2['id']) {
//                    $found = true;
//                    break;
//                }
//            }
//
//            if (!$found)
//                $array1[] = $row2;
//        }
//    }
//
//    return $array1;
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>

// CODE NOT USED - commented by KHADRA
//function videosGetRelated($photoInfo) {
//    $limit = 4;
//    $max = $limit * 2;
//
//    $real_channel_id_random = NULL;
//    if (intval($photoInfo['channelid']) != 0) {
//        $real_channel_id_random = -2;
//    }
//
//    //$options = array('vid' => $photoInfo['id'], 'limit' => $limit, 'page' => 0, 'max_id' => $photoInfo['id'], 'type' => 'v', 'orderby' => 'similarity' , 'order' => 'd' , 'min_similarity' => 100);
//    $options = array('vid' => $photoInfo['id'], 'limit' => $limit, 'page' => 0, 'channel_id' => $real_channel_id_random, 'max_id' => $photoInfo['id'], 'type' => 'v', 'multi_order' => array('similarity' => 'd', 'id' => 'd'), 'min_similarity' => 100);
//    $privacy = 0;
//    if (userIsLogged() && ($photoInfo['userid'] == userGetID())) {
//        $privacy = 0;
//    } else if (userIsLogged() && ($photoInfo['userid'] != userGetID())) {
//        if (userIsFriend($photoInfo['userid'], userGetID())) {
//            $privacy = 1;
//        } else {
//            $privacy = 2;
//        }
//    } else {
//        $privacy = 2;
//    }
//
//    $options['public'] = $privacy;
//
//    $array1 = mediaSearch($options);
//    $array1 = array_reverse($array1);
////	$array1[] = $photoInfo;
//    //$array1 = array_merge($array1, mediaSearch($options));
//
//    if (count($array1) < $max) {
//        $diff = $max - count($array1);
//
//        //$options = array('vid' => $photoInfo['id'], 'limit' => $diff, 'page' => 0, 'min_id' => $photoInfo['id'], 'type' => 'v', 'orderby' => 'similarity' , 'order' => 'd' , 'min_similarity' => 100);
//        $options = array('vid' => $photoInfo['id'], 'limit' => $diff, 'page' => 0, 'channel_id' => $real_channel_id_random, 'min_id' => $photoInfo['id'], 'type' => 'v', 'multi_order' => array('similarity' => 'd', 'id' => 'a'), 'min_similarity' => 100);
//        $options['public'] = $privacy;
//
//        $array2 = mediaSearch($options);
//
//        foreach ($array2 as $row2) {
//
//            $found = false;
//            foreach ($array1 as $row1) {
//                if ($row1['id'] == $row2['id']) {
//                    $found = true;
//                    break;
//                }
//            }
//
//            if (!$found)
//                $array1[] = $row2;
//        }
//    }
//
//    return $array1;
//}

/**
 * gets related media - based on solr indexes
 * @param array a cms_videos record of the original photo
 * @return array set of cms_videos records
 */
function videosGetRelatedSolr($photoInfo, $m_type = null, $limit = 5, $start = 0, $user_sort = 0) {
    //print_r($photoInfo);
    //ini_set("error_reporting",E_ALL);
    //ini_set("display_errors",1);
    $reg_filter = array('and', 'or', 'not', ':');
    $pattern = '/\b(' . implode("|", $reg_filter) . ')\b/i';
    $t = preg_replace($pattern, ' ', $photoInfo['title']);
    //$d = $photoInfo['description'];
    $cid = $photoInfo['category'];
    $c = categoryGetName($cid);
    $c = str_replace("Others", "", $c);
    $k = preg_replace($pattern, ' ', $photoInfo['keywords']);
    $k = str_replace("Others", "", $k);
    $type = $m_type ? $m_type : $photoInfo['image_video'];
    $id = $photoInfo['id'];
    $lang = LanguageGet();
//    $limit = 15;
    $max = $limit * 2;
    //debug($photoInfo);

    /*
     * @todo check why are we making this test on the channel id
     */
    $real_channel_id_random = NULL;
    if (intval($photoInfo['channelid']) != 0) {
        $real_channel_id_random = -2;
    }

  //  require('../../vendor/autoload.php');
    $userVideos = $ct = array();
    global $CONFIG;
    $config = $CONFIG['solr_config'];

    $client = new Solarium\Client($config);
    $client->getPlugin('postbigrequest');
    $client->setAdapter('Solarium\Core\Client\Adapter\Http');
    $query = $client->createSelect();

    $helper = $query->getHelper();
    $t = $helper->escapeTerm($t);
    $c = $helper->escapeTerm($c);
    $k = $helper->escapeTerm($k);

    $privacyString = "((+(lang:$lang lang:xx) -(+lang:xx +$lang:1)) AND type:m) AND is_public:2";
    $cat_filter = $cid != NULL && $cid != 0 ? "OR (cat_id:$cid) ": "";
    if (userIsLogged()) {
        $userId = userGetID();
        $privacyString = "((+(lang:$lang lang:xx) -(+lang:xx +$lang:1)) AND type:m) AND (is_public:2 OR allowed_users:*|$userId|*)";
    }
    $cityid = $photoInfo['cityid'];
    $city_filter = $cityid != NULL ? " +cityid:$cityid^500" : "";
    if ($user_sort == 1) {
        $edismax = $query->getEDisMax();
        $userid = $photoInfo['userid'];
        $searchString = $privacyString . " +mtype:$type";
        $searchString .= " AND (-id:$id)";
        if($photoInfo['channelid'] != 0){
            $searchString .= " AND (-channelid:0)";
        }
        else{
            $searchString .= " AND channelid:0";
        }
        
        $searchString .= " AND ( (title_t1:$t $k $c) OR (city_name_accent:$t $k $c) OR (description_t1:$t $k $c) $cat_filter )";
        $query->setQuery($searchString);
        $query_string = "";
        if($photoInfo['channelid'] != 0){
            $query_string .= "+channelid:".$photoInfo['channelid']."^2000 ";
        }
        $query_string .= "+userid:$userid^1000 +(title_t1:$t $k $c)^750 $city_filter";
        $edismax->setBoostQuery($query_string);
    } else {
        $edismax = $query->getEDisMax();
        $searchString = $privacyString . " +mtype:$type";
        $searchString .= " AND (-id:$id)";
        if($photoInfo['channelid'] != 0){
            $searchString .= " AND (-channelid:0)";
        }
        else{
            $searchString .= " AND channelid:0";
        }
        $searchString .= " AND ( (title_t1:$t $k $c) OR (city_name_accent:$t $k $c) OR (description_t1:$t $k $c) $cat_filter )"; //keywords:$t $c $k
        $query->setQuery($searchString);
        $query_string = "";
        $query_string .= "+(title_t1:$t $k $c)^750 $city_filter";
        $edismax->setBoostQuery($query_string);
    }
    $query->setStart($start * $limit)->setRows($limit);
    $resultset = $client->select($query);
    $total = $resultset->getNumFound();
    $k = 0;
    $matches = 0;
    $userVideos['media']=array();
    foreach ($resultset as $document) {
        $userVideos['media'][$k]['id'] = $document->id;
        $userVideos['media'][$k]['title'] = $document->title_t1;
        $userVideos['media'][$k]['image_video'] = $document->mtype;
        $userVideos['media'][$k]['location'] = $document->location_t;
        $userVideos['media'][$k]['nb_views'] = $document->nb_views;
        $userVideos['media'][$k]['pdate'] = $document->pdate;
        $userVideos['media'][$k]['like_value'] = $document->like_value;
        $userVideos['media'][$k]['nb_comments'] = $document->nb_comments;
        $userVideos['media'][$k]['description'] = $document->description_t1;
        $userVideos['media'][$k]['video_url'] = $document->url;
        $userVideos['media'][$k]['rating'] = $document->rating;
        $userVideos['media'][$k]['name'] = $document->name_t;
        $userVideos['media'][$k]['type'] = $document->mtype;
        $userVideos['media'][$k]['code'] = $document->code_m;
        $userVideos['media'][$k]['fullpath'] = $document->fullpath_m;
        $userVideos['media'][$k]['relativepath'] = $document->relativepath_m;
        $userVideos['media'][$k]['userid'] = $document->userid;
        $userVideos['media'][$k]['channelid'] = $document->channelid;
        $userVideos['media'][$k]['duration'] = $document->duration;
        $k++;
        $matches++;
    }

    $userVideos['total'] = $total;
    $userVideos['matches'] = $matches;
    return $userVideos;

//
//
//    $options = array('vid' => $photoInfo['id'], 'limit' => $limit, 'page' => 0,'channel_id' => $real_channel_id_random, 'max_id' => $photoInfo['id'], 'type' => 'v', 'multi_order' => array('similarity' => 'd', 'id' => 'd') , 'min_similarity' => 100);
//    $privacy = 0;
//    if( userIsLogged() &&  ($photoInfo['userid'] == userGetID()) ){
//            $privacy = 0;
//    }else if( userIsLogged() &&  ($photoInfo['userid'] != userGetID()) ){
//            if( userIsFriend($photoInfo['userid'],userGetID()) ){
//                    $privacy = 1;
//            }else{
//                    $privacy = 2;
//            }
//    }else{
//            $privacy = 2;
//    }
//
//    $options['public'] = $privacy;
//
//    $array1 = mediaSearch($options);
//    $array1 = array_reverse($array1);	
//    $array1[] = $photoInfo;
//        return $array1;
}

function photoReturnSrc($photoInfo) {
    if ($photoInfo['image_video'] == 'i')
        return photoReturnSrcLink($photoInfo);
    else
        return videoReturnSrcLink($photoInfo);
}

function photoReturnSrcSmall($photoInfo) {
    if ($photoInfo['image_video'] == 'i')
        return photoReturnSrcLink($photoInfo, 'small');
    else
        return videoReturnSrcLink($photoInfo, 'small');
}

function photoReturnSrcXSmall($photoInfo) {
    if ($photoInfo['image_video'] == 'i')
        return photoReturnSrcLink($photoInfo, 'xsmall');
    else
        return videoReturnSrcLink($photoInfo, 'xsmall');
}

function photoReturnSrcMed($photoInfo) {
    return photoReturnSrcLink($photoInfo, 'med');
}

function photoReturnOriginalSrc($photoInfo) {
    return photoReturnSrcLink($photoInfo, 'org');
}

function photoReturnThumbSrc($photoInfo) {
    if ($photoInfo['image_video'] == 'i')
        return photoReturnSrcLink($photoInfo, 'thumb');
    else
        return videoReturnSrcLink($photoInfo, 'thumb');
}

function photoReturnSrcLink($photoInfo, $size = '', $is_media=1) {
    global $CONFIG;
    $relativepath = $photoInfo['relativepath'];
    $relativepath = str_replace('/', '', $relativepath);
    $fullPath = $photoInfo['fullpath'] . (!empty($size) ? $size . '_' : '') . $photoInfo['name'];
    $fullPath_exist = $CONFIG ['server']['root'] . '' . $photoInfo['fullpath'] . '' . (!empty($size) ? $size . '_' : '') . $photoInfo['name'];

    try{
        if (!file_exists($fullPath_exist)){
            $mediapath = $CONFIG ['server']['root'] . '' . $photoInfo['fullpath'];
            if ($photoInfo['image_video'] == 'i') rebuildImagesSizes( $photoInfo , $mediapath  );        
            $fullPath = $photoInfo['fullpath'] . (!empty($size) ? $size . '_' : '') . $photoInfo['name'];
            $fullPath_exist = $mediapath . '' . (!empty($size) ? $size . '_' : '') . $photoInfo['name'];            
        }
        if (!file_exists($fullPath_exist)){
            $fullPath = 'media/images/unavailable-preview.gif';
            $is_media=1;
        }
    } catch (Exception $e) {
        
    }
    return ReturnLink($fullPath, null, $is_media);
}
function rebuildImagesSizes($videoFile,$uploadPath){
    resizeUploadedImage2($uploadPath . $videoFile ['name'], $uploadPath . 'med_' . $videoFile ['name']);
    //resize to fit in profile
    resizeUploadedImage3($uploadPath . $videoFile ['name'], $uploadPath . 'small_' . $videoFile ['name']);
    resizeUploadedImage4($uploadPath . $videoFile ['name'], $uploadPath . 'xsmall_' . $videoFile ['name']);
    //create thumbnail
    photoThumbnailCreate($uploadPath . $videoFile ['name'], $uploadPath . 'thumb_' . $videoFile ['name'], 237, 134);
}

//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function eventReturnThumbSrc($photoName) {
//
//    return ReturnLink('pmedia/event/' . $photoName);
//    //return ReturnLink('get_media.php?t=i&id=' . $photoInfo['id']);
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>

function videoReturnThumbSrc($photoInfo) {
//    return $photoInfo['id'];
    return videoReturnSrcLink($photoInfo, 'thumb');
//    return ReturnLink('pmedia/i/' . $photoInfo['name']);
    //return ReturnLink('get_media.php?t=i&id=' . $photoInfo['id']);
}

function videoReturnSrcSmall($photoInfo) {
    return videoReturnSrcLink($photoInfo, 'small');
//	return ReturnLink('pmedia/i3/' . $photoInfo['name']);
    //return ReturnLink('get_media.php?t=i&real=3&id=' . $photoInfo['id']);
}
// CODE NOT USED - commented by KHADRA
//function videoReturnSrcXSmall($photoInfo) {
//    return videoReturnSrcLink($photoInfo, 'xsmall');
//}

function videoReturnSrcLink($photoInfo, $size = '') {
    global $CONFIG;
    $mediaPath = $CONFIG ['server']['root'] . '' . $photoInfo ['fullpath'] . '';
    $videoCode = $photoInfo['code'];
    $thumbs = glob($mediaPath ."_*_". $videoCode . "_*.jpg");
    //$thumbs = glob($mediaPath . $videoCode . "*.jpg");
    
    if ($thumbs && count($thumbs) > 0) {
        $path_parts = pathinfo($thumbs[0]);
        $filename = $path_parts['filename'];
        $relativepath = $photoInfo['relativepath'];
        $relativepath = str_replace('/', '', $relativepath);
        $fullPath = $photoInfo ['fullpath'] . (!empty($size) ? $size . '_' : '') . $filename . '.jpg';
        return ReturnLink($fullPath, null, 1);
    } else {
        return ReturnLink('media/images/unavailable-preview.gif', null, 1);
    }
}

function getCityId($cityName, $cityAccent, $countryCode) {

//  <start>
	global $dbConn;
	$params = array();
    if ($cityName == '' || $countryCode == '')
        return false;
    $cityName = trim($cityName);
    $cityAccent = trim($cityAccent);
    $countryCode = trim($countryCode);
    $lower_cc = strtolower($countryCode);
    $upper_cc = strtoupper($countryCode);
    $lower_city_name = strtolower($cityName);

//    $qu = "select `id` from `webgeocities` where `name`='$lower_city_name' and `country_code`='$upper_cc'";
    $qu = "select `id` from `webgeocities` where `name`=:Lower_city_name and `country_code`=:Upper_cc";
    $params[] = array( "key" => ":Lower_city_name",
                        "value" =>$lower_city_name);  
    $params[] = array( "key" => ":Upper_cc",
                        "value" =>$upper_cc);  
//    if ($cityAccent != '')
//        $qu .= " and `accent`='$cityAccent'";
    if ($cityAccent != ''){
        $qu .= " and `accent`=:CityAccent";
	$params[] = array( "key" => ":CityAccent",
                            "value" =>$cityAccent);  
    }

//    $send = db_query($qu);
    $select = $dbConn->prepare($qu);
    PDO_BIND_PARAM($select,$params);
    $select->execute();

    $ret    = $select->rowCount();

//    if (db_num_rows($send) == 0) {
    if ($ret == 0) {
        return false;
    } else {
//       $cityidres = db_fetch_array($send);
        $cityidres = $select->fetch();
        return $cityidres['id'];
    }

//  <end>
}

function getCityInfoRow($cityName, $countryCode, $state_code = '') {

//  <start>
	global $dbConn;
	$params = array();
    $cityName = trim($cityName);
    $countryCode = trim($countryCode);
    $lower_cc = strtolower($countryCode);
    $upper_cc = strtoupper($countryCode);
    $lower_city_name = strtolower($cityName);

//    $qu = "select * from `webgeocities` where LOWER(`name`)=LOWER('$lower_city_name') AND `country_code` = '$upper_cc'";
//    if ($state_code != '')
//        $qu .= " AND LOWER(`state_code`)=LOWER('$state_code')";
//    $qu .= " ORDER BY order_display DESC";
    $qu = "select * from `webgeocities` where LOWER(`name`)=LOWER(:Lower_city_name) AND `country_code` = :Upper_cc";
    $params[] = array( "key" => ":Lower_city_name",
                        "value" =>$lower_city_name);  
    $params[] = array( "key" => ":Upper_cc",
                        "value" =>$upper_cc);  

    if ($state_code != ''){
        $qu .= " AND LOWER(`state_code`)=LOWER(:State_code)";
        $params[] = array( "key" => ":State_code",
                            "value" =>$state_code); 
    }
    $qu .= " ORDER BY order_display DESC";
//    $send = db_query($qu);
//
//    if (db_num_rows($send) == 0) {
//        return false;
//    } else {
//        $cityidres = db_fetch_array($send);
//        return $cityidres;
//    }
    $select = $dbConn->prepare($qu);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();
    if ($ret == 0) {
        return false;
    } else {
        $cityidres = $select->fetch();
        return $cityidres;
    }

//  <end>
}

function getCityName($cityId) {
    global $dbConn;
    $params = array();
    $qu = "select * from `webgeocities` where `id`=:CityId";
    $params[] = array( "key" => ":CityId",
                        "value" =>$cityId);  

    $select = $dbConn->prepare($qu);
    PDO_BIND_PARAM($select,$params);
    $select->execute();

    $ret    = $select->rowCount();

    if ($ret == 0) {
        return false;
    } else {
        $cityidres = $select->fetch();
        return $cityidres['name'];
    }
}

/**
 * gets the city record (webgeocities not webgeocities) for a city_name
 * @param string $city_name
 * @return false|array false if no city or the webgeocities record 
 */
function cityFind($city_name, $strict = false) {

//  <start>
	global $dbConn;
	$params = array();
    $l_city_name = strtolower($city_name);
    if ($strict){
//        $query = "SELECT * FROM webgeocities WHERE name='$l_city_name'";
        $query = "SELECT * FROM webgeocities WHERE name=:L_city_name";
	$params[] = array( "key" => ":L_city_name",
                            "value" =>$l_city_name);  
    }
    else{
//        $query = "SELECT * FROM webgeocities WHERE name LIKE '%$l_city_name%'";
        $query = "SELECT * FROM webgeocities WHERE name LIKE :L_city_name";
	$params[] = array( "key" => ":L_city_name",
                            "value" => '%'.$l_city_name.'%'); 
        
    }
    $query .= " ORDER BY order_display DESC LIMIT 1";


    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0)) {
        return false;
    } else {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
}

/**
 * gets the video (media actually) rating for a user
 * @param integer $vid the cms_video record id
 * @param integer $uid the cms_user record id
 * @return false|array false if the user hasnt rated a video or the rating array (+comment)
 */
function videoUserRatingGet($vid, $uid) {
    return socialRateGet($uid, $vid, SOCIAL_ENTITY_MEDIA);
}

/**
 * sets a users's rating for a video (media actually)
 * @param integer $vid the cms_video record id
 * @param integer $uid the cms_user record id
 * @param integer $rating the user's rating
 * @param string $comment the user's comment 
 * @return boolean array|false the new rating|fail
 */
function videoUserRatingSet($vid, $uid, $user_rating) {
//    cacheUnset(videoCacheName($vid));
    if (videoUserRatingGet($vid, $uid) === false) {
        $ret = socialRateAdd($uid, $vid, SOCIAL_ENTITY_MEDIA, $user_rating, null);
    } else {
        $ret = socialRateEdit($uid, $vid, SOCIAL_ENTITY_MEDIA, $user_rating);
    }

    if ($ret == false)
        return false;

    $rate_rec = socialRateRecordGet($uid, $vid, SOCIAL_ENTITY_MEDIA);

    $rate_id = $rate_rec['id'];

    return $ret;
}

/**
 * the new video data
 * @param array $videoInfo 
 */
function videoEdit($videoInfo) {
    global $dbConn;
    global $CONFIG;
    $params = array();
    $params2 = array();
    $id = $videoInfo['id'];
    $selQ = 'SELECT * FROM `cms_videos` ';
//    $selQ .= "where `id` = $id"; //for test
//    $ret = mysql_query($selQ);
//    $before = mysql_fetch_array($ret);
    $selQ .= "where `id` = :Id";
    $params2[] = array( "key" => ":Id",
                        "value" =>$id);  
    $select = $dbConn->prepare($selQ);
    PDO_BIND_PARAM($select,$params2);
    $select->execute();
    
    $before = $select->fetch();

    $query = "UPDATE cms_videos SET ";
    $re_index_words = false;
    $i = 0;
    foreach ($videoInfo as $key => $val) {
        if ($key == 'id')
            continue;
        if (in_array($key, array('cityid', 'location_id', 'trip_id'))) {
            $real_id = intval($val);
//            if ($real_id == 0)
//                $real_id = 'NULL';
//            $query .= "$key=$real_id,";
            $query .= " $key =:Real_id".$i.",";
            $params[] = array( "key" => ":Real_id".$i,
                                "value" =>$real_id);  
            $i++;
            continue;
        }

        if (in_array($key, array('title', 'keywords', 'placetakenat', 'description'))) {
            $re_index_words = true;
        }
        $query .= "$key=:Real_id".$i.",";
        $params[] = array( "key" => ":Real_id".$i, "value" =>$val);  
        $i++;
    }
    $title = (isset($videoInfo['title']))? $videoInfo['title']:'';
    $name = $before['name'];
    if($title!=''){
        $title = remove_accents($title);
        $title = str_replace(' ', '-', $title);
        $title = str_replace(' ', '-', $title);
        $title = preg_replace('/[^a-z0-9A-Z\-]/', '', $title);
        $title = str_replace('--', '-', $title);

        $for = strrpos($name, '.');
        $ext = substr($name, $for);

        $oldVideoName = substr($name, 0, $for);
        $namewoExt = $title . '-' . time() . rand(100, 999);
        $name = $namewoExt . $ext;
//        $query .= " `old`=`name`,`name`='$name'";
        $query .= " `old`=`name`,`name`=:Name,";
        $params[] = array( "key" => ":Name", "value" =>$name);

        /**
         * if image
         */
        $fullpath = $CONFIG ['server']['root'] . $before['fullpath'];
        $old = $before['name'];
        if ($before['image_video'] == 'i') {
            if (!rename($fullpath . $old, $fullpath . $name)) {
    
    //        echo '<br>normal:' . $id;
            }
            if (!rename($fullpath . 'thumb_' . $old, $fullpath . 'thumb_' . $name)) {
    
    //        echo '<br>thumb:' . $id;
            }
            if (!rename($fullpath . 'xsmall_' . $old, $fullpath . 'xsmall_' . $name)) {

    //        echo '<br>xsmall:' . $id;
            }
            if (!rename($fullpath . 'small_' . $old, $fullpath . 'small_' . $name)) {
    //        echo '<br>small:' . $id;
            }
            if (!rename($fullpath . 'org_' . $old, $fullpath . 'org_' . $name)) {
    //        echo '<br>org:' . $id;
            }
            if (!rename($fullpath . 'med_' . $old, $fullpath . 'med_' . $name)) {
    //        echo '<br>med:' . $id;
            }
        }else{
            $renameOptions = array('ext' => $ext, 'oldVideoName' => $oldVideoName, 'namewoExt' => $namewoExt, 'id' => $id, 'path' => $fullpath, 'code' => $before['code']);
            renameVideosAndThumbs($renameOptions);
        }
    }

    if (isset($videoInfo['description'])) {
        $desc_linked = seoHyperlinkText($videoInfo['description']);
//        $query .= ",link_ts=NOW(), description_linked='$desc_linked'";
        $query .= "link_ts=NOW(), description_linked=:Desc_linked,";
        $params[] = array( "key" => ":Desc_linked", "value" =>$desc_linked);
    }

//    $query .= " WHERE id='$id'";
    $query = trim($query, ',');
    $query .= " WHERE id=:Id";
    $params[] = array( "key" => ":Id", "value" =>$id);

    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $ret = $update->execute();

    if (!$ret)
        return false;

    $words = $videoInfo['title'] . ' ' . $videoInfo['description'] . ' ' . $videoInfo['keywords'] . ' ' . $videoInfo['placetakenat'];
    videoCachePurge($id);

    return $ret;

//  <end>
}
/**
 * 
 * @param type $arr 'ext','oldVideoName','namewoExt'','path','code','id','path','code'
 */
function renameVideosAndThumbs($arr) {
    //rename thumbs
    $thumbPrefixArr = array('', 'large_', 'small_', 'xsmall_', 'thumb_');
    $vidPrefixArr = array('1920x1080', '1280x720', '860x480', '640x360', '430x240');
    for ($i = 1; $i <= 3; $i++) {
        foreach ($thumbPrefixArr as $thumbPrefix) {
//            $old = $arr['path'] . $thumbPrefix . $arr['code'] . "_" . $i . "_" . $arr['oldVideoName'] . '.jpg';
            $old = $arr['path'] . $thumbPrefix . "_" . $arr['oldVideoName'] . "_" . $arr['code'] . "_" . $i . '_.jpg';
            $new = $arr['path'] . $thumbPrefix . "_" . $arr['namewoExt'] . "_" . $arr['code'] . "_" . $i . '_.jpg';
            if (!rename($old, $new)) {
//                writeLine('(vt) ID : [ ' . $arr['id'] . " ] | OLD : [ " . $old . " ] | NEW : [ " . $new . " ]");
            }
        }
    }
    //rename video
    //original:
    $oldOrig = $arr['path'] . $arr['oldVideoName'] . $arr['ext'];
    $newOrig = $arr['path'] . $arr['namewoExt'] . $arr['ext'];
    if (!rename($oldOrig, $newOrig)) {
//        writeLine('(vo) ID : [ ' . $arr['id'] . " ] | OLD : [ " . $oldOrig . " ] | NEW : [ " . $newOrig . " ]");
    }
    //resized
    foreach ($vidPrefixArr as $vidPrefix) {
        $old = $arr['path'] . $vidPrefix . $arr['oldVideoName'] . '.mp4';
        $new = $arr['path'] . $vidPrefix . $arr['namewoExt'] . '.mp4';
        if (!rename($old, $new)) {
//            writeLine('(vc) ID : [ ' . $arr['id'] . " ] | OLD : [ " . $old . " ] | NEW : [ " . $new . " ]");
        }
    }
}

/**
 * converts a video title to encrypted url
 * @param array $vinfo a cms_videos record
 * @return string the url of the video
 */
function videoToURLHashed($vinfo,$skip_title=0) {
    $hashids = tt_global_get('hashids');
    $url = $hashids->encode($vinfo['id']);
    if($skip_title==0){
        $url = (cleanTitle($vinfo['title'])=='')?'+'.'/'.$hashids->encode($vinfo['id']):cleanTitle($vinfo['title']) .'?id='.$hashids->encode($vinfo['id']);
    }
    return $url;
}

/**
 * converts a video title to url
 * @param array $vinfo a cms_videos record
 * @return string the url of the video
 */
function videoToURL($vinfo) {

    $url = remove_accents($vinfo['title']);
    //$url = strtolower( $url );
    $url = str_replace(' ', '-', $url);
    $url = preg_replace('/[^a-z0-9A-Z\-]/', '', $url);
    $url = str_replace('--', '-', $url);

    $url = substr($url, 0, 80);

    if ($url[strlen($url) - 1] != '-')
        $url = $url . '-';

    $url = $url . $vinfo['id'];

    return $url;
}

/**
 * gets the cms_videos record given the encrypted url
 * @param string $url
 * @return mixed false if no record or the cms_videos record. 
 */
function videoFromURLHashed($url) {

//  <start>
	global $dbConn;
	$params  = array();
	$params2 = array();

     $lang = LanguageGet();


    if (strlen($url) > 0) {
        $hashids = tt_global_get('hashids');
        $id = $hashids->decode($url);
//        $query = "SELECT * FROM cms_videos WHERE id=$id[0] and published=1";
        $query = "SELECT * FROM cms_videos WHERE id=:Id and published=1";
	$params[] = array( "key" => ":Id",
                            "value" =>$id[0]);
//        $res = db_query($query);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
	
        if (!$res || ($ret == 0)) {
            return false;
        } else {
//            $row = db_fetch_array($res);
            $row = $select->fetch();
            
//            $query_ = "SELECT * FROM ml_videos WHERE video_id=" . $id[0] . " AND lang_code='" . $lang . "'";
            $query_ = "SELECT * FROM ml_videos WHERE video_id=:Id AND lang_code=:Lang ";
            $params2[] = array( "key" => ":Id",
                                "value" =>$id[0]);
            $params2[] = array( "key" => ":Lang",
                                "value" =>$lang);
//            $res_ = db_query($query_);
            $select = $dbConn->prepare($query_);
            PDO_BIND_PARAM($select,$params2);
            $res    = $select->execute();
//            $row_ = db_fetch_array($res_);
            $row_ = $select->fetch();
            if (is_array($row_)) {
                $row['title'] = $row_['title'];
                $row['description'] = $row_['description'];
                $row['placetakenat'] = $row_['placetakenat'];
                $row['keywords'] = $row_['keywords'];
            }

            return $row;
        }
    } else {
        return false;
    }
}
/**
 * gets the suggestion of a single string
 * @param string $in_string the string to be checked for suggestions
 * @return array|null the suggested string or nul if no suggestions 
 */
function suggestionGetCity($in_string) {

//  <start>
    global $dbConn;
    $params = array();
    $len = strlen($in_string);
    $city_limit = 10;
//
//    $query = "SELECT DISTINCT(C.name) AS LR FROM webgeocities AS C WHERE C.name LIKE '$in_string%' ORDER BY LR ASC LIMIT $city_limit";
//
//    $res = db_query($query);
//    if ($res && (db_num_rows($res) != 0)) {
//        $ret = array();
//        while ($row = db_fetch_array($res)) {
//            $ret[] = $row[0];
//        }
//        return (count($ret) == 0) ? null : $ret;
//    } else {
//        return null;
//    }
    $query = "SELECT DISTINCT(C.name) AS LR FROM webgeocities AS C WHERE C.name LIKE :In_string ORDER BY LR ASC LIMIT :City_limit";
    $params[] = array( "key" => ":In_string",
                        "value" => $in_string.'%');
    $params[] = array( "key" => ":City_limit",
                        "value" => $city_limit,
                        "type" =>  "::PARAM_INT");
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();
    if ($res && ($ret != 0)) {
        $res = $select->fetchAll();
        $ret_arr = array();
        foreach($row as $row_item){
            $ret_arr[] = $row_item[0];
        }
        return (count($ret_arr) == 0) ? null : $ret_arr;
    } else {
        return null;
    }

//  <end>
}

/**
 * gets suggestions for city
 * @param array $srch_options
 * <b>term</b>: the search criteria default null<br/>
 * <b>limit</b>: the maximum number of records returned. default 10<br/>
 * <b>strict</b>: strict the serach to 3 types 0 - for = '$term', 1 - for LIKE '$term%', 2 - for LIKE '%$term%', 2 - for LIKE '%$term%'<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * @return null 
 */
function searchGetCity($srch_options) {

//  <start>
    global $dbConn;
    $params = array();  

    $default_opts = array(
        'term' => $term,
        'limit' => $limit,
        'strict' => 0,
        'page' => 0
    );

    $options = array_merge($default_opts, $srch_options);

    $where = '';

    if (!is_null($options['term'])) {

        if ($options['strict'] == 0) {
//            $where .= " LOWER(cityname) = LOWER('" . $options['term'] . "') ";
            $where .= " LOWER(cityname) = LOWER(:Term) ";
            $params[] = array( "key" => ":Term",
                                "value" =>$options['term']);
        } else if ($options['strict'] == 1) {
//            $where .= " LOWER(cityname) LIKE LOWER('" . $options['term'] . "%') ";
            $where .= " LOWER(cityname) LIKE LOWER(:Term2) ";
            $params[] = array( "key" => ":Term2",
                                "value" =>$options['term']. '%');
        } else if ($options['strict'] == 2) {
//            $where .= " LOWER(cityname) LIKE LOWER('%" . $options['term'] . "%') ";
            $where .= " LOWER(cityname) LIKE LOWER(:Term3) ";
            $params[] = array( "key" => ":Term3",
                                "value" =>'%'.$options['term'].'%');
        }
    }
    /*
      if($where != ''){
      $where = "WHERE $where";
      }
     */
    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit;

//    $query = "SELECT DISTINCT(cityname) as name FROM cms_videos WHERE $where ORDER BY nb_views DESC LIMIT $skip, $nlimit";
    $query = "SELECT DISTINCT(cityname) as name FROM cms_videos WHERE $where ORDER BY nb_views DESC LIMIT :Skip, :Nlimit";
    $params[] = array( "key" => ":Skip",
                        "value" => $skip,
                        "type" => "::PARAM_INT");
    $params[] = array( "key" => ":Nlimit",
                        "value" => $nlimit,
                        "type" => "::PARAM_INT");

//    $res = db_query($query);
//
//    $ret_arr = array();
//    while ($row = db_fetch_array($res)) {
//        $ret_arr[] = $row;
//    }
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute(); 
    
    $ret_arr = $select->fetchAll();
    return $ret_arr;

//  <end>
}

/**
 * gets the suggestion of a single string
 * @param array $srch_options
 * <b>term</b>: the search criteria default null<br/>
 * <b>limit</b>: the maximum number of records returned. default 10<br/>
 * <b>strict</b>: strict the serach to 3 types 0 - for = '$term', 1 - for LIKE '$term%', 2 - for LIKE '%$term%', 2 - for LIKE '%$term%'<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>field</b>: the field to search within. default title<br/>
 * @return null 
 */
function suggestMedia($srch_options) {

//  <start>
    global $dbConn;
    $params = array();  
    $default_opts = array(
        'term' => $term,
        'limit' => $limit,
        'strict' => 0,
        'page' => 0,
        'field' => 'title'
    );

    $options = array_merge($default_opts, $srch_options);

    $where = '';

    if (!is_null($options['term'])) {

        if ($options['strict'] == 0) {
//            $where .= " LOWER(" . $options['field'] . ") = LOWER('" . $options['term'] . "') ";
            $where .= " LOWER(" . $options['field'] . ") = LOWER(:Term) ";
            $params[] = array( "key" => ":Term",
                                "value" =>$options['term']);
        } else if ($options['strict'] == 1) {
//            $where .= " LOWER(" . $options['field'] . ") LIKE LOWER('" . $options['term'] . "%') ";
            $where .= " LOWER(" . $options['field'] . ") LIKE LOWER(:Term2) ";
            $params[] = array( "key" => ":Term2",
                                "value" =>$options['term']);
        } else if ($options['strict'] == 2) {
//            $where .= " LOWER(" . $options['field'] . ") LIKE LOWER('%" . $options['term'] . "%') ";
            $where .= " LOWER(" . $options['field'] . ") LIKE LOWER(:Term3) ";
            $params[] = array( "key" => ":Term3",
                                "value" =>$options['term']);
        }
    }

    if ($where != '') {
        $where = "WHERE $where";
    }

    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit;

//    $query = "SELECT DISTINCT(" . $options['field'] . ") as title FROM cms_videos $where ORDER BY nb_views DESC LIMIT $skip, $nlimit";
    $query = "SELECT DISTINCT(" . $options['field'] . ") as title FROM cms_videos $where ORDER BY nb_views DESC LIMIT :Skip, :Nlimit";
    $params[] = array( "key" => ":Skip",
                        "value" =>$skip,
                        "type" => "::PARAM_INT");
    $params[] = array( "key" => ":Nlimit",
                        "value" =>$nlimit,
                        "type" => "::PARAM_INT");

//    $res = db_query($query);
//
//    $ret_arr = array();
//    while ($row = db_fetch_array($res)) {
//        $ret_arr[] = $row;
//    }
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute(); 

    $ret_arr = $select->fetchAll();
    return $ret_arr;

//  <end>
}

/**
 * gets suggestions for country
 * @param array $srch_options
 * <b>term</b>: the search criteria default null<br/>
 * <b>limit</b>: the maximum number of records returned. default 10<br/>
 * <b>strict</b>: strict the serach to 3 types 0 - for = '$term', 1 - for LIKE '$term%', 2 - for LIKE '%$term%'<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * @return null 
 */
function searchGetCountry($srch_options) {

//  <start>
    global $dbConn;
    $params = array(); 

    $default_opts = array(
        'term' => $term,
        'limit' => $limit,
        'strict' => 0,
        'page' => 0
    );

    $options = array_merge($default_opts, $srch_options);

    $where = '';

    if (!is_null($options['term'])) {

        if ($options['strict'] == 0) {
//            $where .= " LOWER(t1.name) = LOWER('" . $options['term'] . "')";
            $where .= " LOWER(t1.name) = LOWER(:Term1)";
            $params[] = array( "key" => ":Term1",
                                "value" =>$options['term']);
        } else if ($options['strict'] == 1) {
//            $where .= " LOWER(t1.name) LIKE LOWER('" . $options['term'] . "%')";
            $where .= " LOWER(t1.name) LIKE LOWER(:Term2)";
            $params[] = array( "key" => ":Term2",
                                "value" =>$options['term'].'%');
        } else if ($options['strict'] == 2) {
//            $where .= " LOWER(t1.name) LIKE LOWER('%" . $options['term'] . "%')";
            $where .= " LOWER(t1.name) LIKE LOWER(:Term3)";
            $params[] = array( "key" => ":Term3",
                                "value" =>'%'.$options['term'].'%');
        }
    }
    /*
      if($where != ''){
      $where = "WHERE $where";
      }
     */
    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit;

    //$query = "SELECT t1.name, t1.code, t2.country FROM cms_countries as t1 INNER JOIN cms_videos as t2 WHERE $where and t2.country = t1.code ORDER BY t2.nb_views DESC LIMIT $skip, $nlimit";
    $query = "SELECT DISTINCT(t1.name) as name FROM cms_countries as t1 INNER JOIN cms_videos as t2 WHERE $where and t2.country = t1.code ORDER BY t2.nb_views DESC LIMIT :Skip, :Nlimit";
    $params[] = array( "key" => ":Skip",
                        "value" =>$skip,
                        "type" => "::PARAM_INT");
    $params[] = array( "key" => ":Nlimit",
                        "value" =>$nlimit,
                        "type" => "::PARAM_INT");

//    $res = db_query($query);
//
//    $ret_arr = array();
//    while ($row = db_fetch_array($res)) {
//        $ret_arr[] = $row;
//    }

    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute(); 
    
    $ret_arr = $select->fetchAll();
    return $ret_arr;

//  <end>
}

/**
 * gets suggestions for country
 * @param string $in_string
 * @return null 
 */
function suggestionGetCountry($in_string) {

//  <start>
    global $dbConn;
    $params = array();  
    $country_limit = 10;

    if (strlen($in_string) == 0)
        return null;

//    $query = "SELECT C.name AS LR FROM cms_countries AS C WHERE LOWER(name) LIKE '$in_string%' ORDER BY LR DESC LIMIT $country_limit";
    $query = "SELECT C.name AS LR FROM cms_countries AS C WHERE LOWER(name) LIKE :In_string ORDER BY LR DESC LIMIT :Country_limit";
//    $res = db_query($query);
    $params[] = array( "key" => ":In_string",
                        "value" =>$in_string.'%');
    $params[] = array( "key" => ":Country_limit",
                        "value" =>$country_limit,
                        "type" => "::PARAM_INT");
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret1    = $select->rowCount();
//    if ($res && (db_num_rows($res) != 0)) {
    if ($res && ($ret1 != 0)) {
        $ret = array();
//        while ($row = db_fetch_array($res)) {
        $row = $select->fetchAll();
        foreach($row as $row_item){
            $ret[] = $row_item[0];
        }
        /*
          if( count($ret) != $country_limit){
          $left = $country_limit - count($ret);
          $query = "SELECT C.name AS LR FROM webgeocities AS C WHERE C.name LIKE '%$in_string%' ORDER BY LR ASC LIMIT $left";
          $res = db_query($query);
          while( $row = db_fetch_array($res) ){
          if(!in_array($row[0],$ret)) $ret[] = $row[0];
          }
          }
         */
        return (count($ret) == 0) ? null : $ret;
    } else {
        return null;
    }

//  <end>
}

/**
 * gets the user catalog record associated with the video
 * @param integer $video_id the cms_videos record id
 * @return array the cms_users_catalogs record
 */
function mediaGetCatalog($video_id) {

//  <start>
    global $dbConn;
    $params = array();  
//    $query = "SELECT
//					UC.*
//				FROM
//					cms_users_catalogs AS UC
//					INNER JOIN cms_videos_catalogs AS VC ON UC.id=VC.catalog_id
//				WHERE
//					VC.video_id='$video_id'
//				ORDER BY
//					UC.id DESC
//				LIMIT 1";
//    $res = db_query($query);
//    if ($res && (db_num_rows($res) != 0)) {
//        return db_fetch_assoc($res);
//    } else {
//        return false;
//    } 
    $query = "SELECT
					UC.*
				FROM
					cms_users_catalogs AS UC
					INNER JOIN cms_videos_catalogs AS VC ON UC.id=VC.catalog_id
				WHERE
					VC.video_id=:Video_id
				ORDER BY
					UC.id DESC
				LIMIT 1";
    $params[] = array( "key" => ":Video_id",
                        "value" =>$video_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();
    if ($res && ($ret != 0)) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        return $row;
    } else {
        return false;
    }	

//  <end>
}

/**
 * insert a temporary video
 * @param integer $user_id the user id 
 * @param string $filename the filename of the media file
 * @param string $vpath the path to the uplaod
 * @param string $thumb the saved thumb
 * @param integer $catalog_id the catalog of the uploaded file
 * @param char $image_video 'i' => image, 'v' => video
 * @return boolean true|false if success fail
 */
function videoTemporaryInsert($user_id, $filename, $vpath, $thumb, $catalog_id, $image_video, $channel_id = 0) {
    global $dbConn;
    $params = array();  
    $_catalog_id = intval($catalog_id);
    if ($_catalog_id == 0)
        $_catalog_id = 0;
    else if (userCatalogOwner($_catalog_id) != $user_id)
        return false;

    $_channel_id = intval($channel_id);
    //$query = "INSERT INTO cms_videos_temp (user_id,filename,vpath,thumb,catalog_id,image_video,channelid) VALUES (:User_id,:Filename,:Vpath,:Thumb,:Catalog_id,:Image_video,:Channel_id)";
    $query = "INSERT INTO `cms_videos_temp`( `user_id`, `filename`, `vpath`, `thumb`, `catalog_id`, `image_video`, `channelid`, `pending_data`, `privacy_value`, `privacy_array`) VALUES (:User_id,:Filename,:Vpath,:Thumb,:Catalog_id,:Image_video,:Channel_id,'','','')";
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $params[] = array( "key" => ":Filename", "value" =>$filename);
    $params[] = array( "key" => ":Vpath", "value" =>$vpath);
    $params[] = array( "key" => ":Thumb", "value" =>$thumb);
    $params[] = array( "key" => ":Catalog_id", "value" =>$_catalog_id);
    $params[] = array( "key" => ":Image_video", "value" =>$image_video);
    $params[] = array( "key" => ":Channel_id", "value" =>$_channel_id);
    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params);
    $res  = $insert->execute();
    return $res;
}

/**
 * searches for the temporary albums.
 * @param $srch_options array of options. options include: <br/>
 * <b>limit</b> integer. limit results. default 5. <br/>
 * <b>page</b> integer. number of result pages to skip. default 0.<br/>
 * <b>user_id</b> which user id to search for. required<br/>
 * <b>channelid</b> which channel id to search for. default null<br/>
 * <b>n_results</b> boolean. false => results or true => number of result. default false
 * @return array|integer 
 */
function videoTemporaryGetAlbums($srch_options) {

//  <start>
//exit;
    global $dbConn;
    $params = array(); 

    $default_opts = array('user_id' => null,
        'limit' => 100,
        'page' => 0,
        'channelid' => null,
        'n_results' => false,
        'orderby' => 'id',
        'order' => 'd'
    );

    $options = array_merge($default_opts, $srch_options);

    $where = '';

    if (is_null($options['user_id'])) {
        return false;
    } else {
        if ($where != '')
            $where .= " AND ";
//        $where .= " T.user_id = '{$options['user_id']}' ";
        $where .= " T.user_id = :User_id ";
	$params[] = array( "key" => ":User_id",
                            "value" =>$options['user_id']);
    }

    if (is_null($options['channelid'])) {
        if ($where != '')
            $where .= " AND ";
        $where .= " (COALESCE(T.channelid ,0)=0 OR T.channelid=0) ";
    }else {
        if ($where != '')
            $where .= " AND ";
//        $where .= " T.channelid = '{$options['channelid']}' ";
        $where .= " T.channelid = :Channelid ";
	$params[] = array( "key" => ":Channelid",
                            "value" =>$options['channelid']);
    }

    $limit = intval($options['limit']);
    $page = intval($options['page']);

    $skip = $limit * $page;

    $orderby = $options['orderby'];
    $order = ($options['order'] == 'd') ? 'DESC' : 'ASC';

    if ($where != '')
        $where .= " AND ";
    $where .= " COALESCE( catalog_id ,0) AND catalog_id<>0 ";

    if ($where != '')
        $where = " WHERE $where ";

    if (!$options['n_results']) {
//        $query = "SELECT
//					DISTINCT(C.id),C.catalog_name
//				FROM
//					cms_videos_temp AS T
//					INNER JOIN cms_users_catalogs AS C ON C.id=T.catalog_id
//					$where ORDER BY $orderby $order LIMIT $skip,$limit";
         $query = "SELECT
					DISTINCT(C.id),C.catalog_name
				FROM
					cms_videos_temp AS T
					INNER JOIN cms_users_catalogs AS C ON C.id=T.catalog_id
					$where ORDER BY $orderby $order LIMIT :Skip,:Limit";
	$params[] = array( "key" => ":Skip",
                            "value" =>$skip,
                            "type" => "::PARAM_INT");
	$params[] = array( "key" => ":Limit",
                            "value" =>$limit,
                            "type" => "::PARAM_INT");
//        $res = db_query($query);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res = $select->execute();

	$ret    = $select->rowCount();
        if (!$res || ($ret == 0)) {
            return array();
        } else {
            $ret = array();
//            while ($row = db_fetch_assoc($res)) {
//                $ret[] = $row;
//            }
            $ret = $select->fetchAll(PDO::FETCH_ASSOC);
            return $ret;
        }
    } else {
        $query = "SELECT COUNT(user_id) FROM cms_videos_temp AS T $where";        
//        $res = db_query($query);
//        $row = db_fetch_row($res);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$select->execute();
	$row = $select->fetch();
        return $row[0];        
    }

//  <end>
}

/**
 * delete a temporary video
 * @param integer $user_id the user id 
 * @param string $filename the filename of the media file
 * @return boolean true|false if success fail
 */
function videoTemporaryDelete($user_id, $filename) {

//  <start>
    global $dbConn;
    $params = array();  
//    $query = "DELETE FROM cms_videos_temp WHERE user_id='$user_id' AND filename='$filename'";
//    return db_query($query);
    $query = "DELETE FROM cms_videos_temp WHERE user_id=:User_id AND filename=:Filename";
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $params[] = array( "key" => ":Filename", "value" =>$filename);
    $delete = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete,$params);
    $res = $delete->execute();
    return $res;

//  <end>
}

/**
 * check if the user is the owner of a temporary video
 * @param integer $user_id the user id 
 * @param string $filename the filename of the media file
 * @return boolean true|false if success fail
 */
function videoTemporaryOwner($user_id, $filename) {

//  <start>
    global $dbConn;
    $params = array();  
	
//    $query = "SELECT * FROM cms_videos_temp WHERE user_id='$user_id' AND filename='$filename'";
//    $res = db_query($query);
//    if (!$res || (db_num_rows($res) == 0)) {
//        return false;
//    } else {
//        return true;
//    }
    $query = "SELECT * FROM cms_videos_temp WHERE user_id=:User_id AND filename=:Filename";
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $params[] = array( "key" => ":Filename", "value" =>$filename);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0)) {
        return false;
    } else {
        return true;
    }

//  <end>
}

/**
 * return the temporary video record
 * @param $ID the temporary video id
 * @return array | false the cms_videos_temp record or null if not found
 */
function videoTemporaryInfo($id) {

//  <start>
    global $dbConn;
    $params = array();  
//    $query = "SELECT * FROM cms_videos_temp WHERE id='$id'";
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
//        $row = db_fetch_assoc($ret);
//        return $row;
//    } else {
//        return false;
//    }
    $query = "SELECT * FROM cms_videos_temp WHERE id=:Id";
    $params[] = array( "key" => ":Id",
                        "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $rss  = $select->execute();

    $ret    = $select->rowCount();
    if ($rss && $ret != 0) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        return $row;
    } else {
        return false;
    }

//  <end>
}

/**
 * the new temporary video data
 * @param array $videoInfo 
 */
function videoTemporaryEdit($videoInfo) {
    global $dbConn;
    $params = array(); 
    $user_id = $videoInfo['user_id'];
    $filename = $videoInfo['filename'];
    $query = "UPDATE cms_videos_temp SET ";
    $re_index_words = false;
    $i = 0;
    foreach ($videoInfo as $key => $val) {
        if ($key == 'user_id' || $key == 'filename')
            continue;
//        $query .= "$key='$val',";
        $query .= " $key = :Val".$i.",";
	$params[] = array( "key" => ":Val".$i, "value" =>$val);
        $i++;
    }
    $query = trim($query, ',');
    $query .= " WHERE  user_id=:User_id AND filename=:Filename";
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $params[] = array( "key" => ":Filename", "value" =>$filename);

    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $ret  = $update->execute();
    
    if (!$ret)
        return false;
    return $ret;
}

/**
 * checks if a video is owned by a user
 * @param integer $video_id
 * @param integer $owner_id 
 * @return boolean true|false
 */
function videoOwner($video_id, $owner_id) {
    return ( videoGetOwner($video_id) == $owner_id);
}

/**
 * gets a media file's owner
 * @param integer $video_id
 * @return false|integer 
 */
function videoGetOwner($video_id) {
    return getVideoOwner($video_id);
}

/**
 * gets all the temporary uploads for a user. options include:<br/>
 * <b>user_id</b> integer. the user's id. required.<br/>
 * <b>catalog_id</b> integer. to which catalog id<br/>
 * <b>limit</b> integer. limit number of results default. 150.<br/>
 * <b>$page</b> integer. pages to skip. default 0.<br/>
 * <b>image_video</b>. char 'i' => image, 'v' => video, 'a' => all<br/>
 * <b>n_results</b>. boolean. gets the number of results or the results.
 * @param $srch_options array the search options
 * @return integer|array() an array of all the users temporary uploads or the number of results
 */
function videoTemporaryGetAll($srch_options) {

//  <start>
    global $dbConn;
    $params = array();  
    $default_opts = array('user_id' => null,
        'catalog_id' => null,
        'channel_id' => null,
        'limit' => 1000,
        'page' => 0,
        'image_video' => 'a',
        'n_results' => false
    );

    $options = array_merge($default_opts, $srch_options);

    $where = '';

    if (is_null($options['user_id'])) {
        return false;
    } else {
        if ($where != '')
            $where .= " AND ";
//        $where .= " user_id = '{$options['user_id']}' ";
        $where .= " user_id = :User_id ";
	$params[] = array( "key" => ":User_id",
                            "value" =>$options['user_id']);
    }

    if (!is_null($options['catalog_id'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " catalog_id = '{$options['catalog_id']}' ";
        $where .= " catalog_id = :Catalog_id ";
	$params[] = array( "key" => ":Catalog_id",
                            "value" =>$options['catalog_id']);
    }else {
        if ($where != '')
            $where .= " AND ";
        $where .= " ( COALESCE(catalog_id ,0)=0 OR catalog_id=0) ";
    }

    if (!is_null($options['channel_id'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " channelid = '{$options['channel_id']}' ";
        $where .= " channelid = :Channel_id ";
	$params[] = array( "key" => ":Channel_id",
                            "value" =>$options['channel_id']);
    }else {
        if ($where != '')
            $where .= " AND ";
        $where .= " ( COALESCE( channelid,0)=0 OR channelid=0) ";
    }

    $limit = intval($options['limit']);
    $page = intval($options['page']);

    $skip = $limit * $page;

    if ($options['image_video'] != 'a') {
        $where .= " AND ";
//        $where .= " image_video='{}' ";
        $where .= " image_video=:Image_video ";
	$params[] = array( "key" => ":Image_video",
                            "value" =>$options['image_video']);
    }

    if ($where != '')
        $where = " WHERE $where ";

    if (!$options['n_results']) {
//        $query = "SELECT id,filename,vpath,thumb,image_video FROM cms_videos_temp $where ORDER BY id DESC LIMIT $skip,$limit ";
        $query = "SELECT id,filename,vpath,thumb,image_video FROM cms_videos_temp $where ORDER BY id DESC LIMIT :Skip,:Limit ";
	$params[] = array( "key" => ":Skip",
                            "value" =>$skip,
                            "type" =>"::PARAM_INT");
	$params[] = array( "key" => ":Limit",
                            "value" =>$limit,
                            "type" =>"::PARAM_INT");
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res = $select->execute();

	$ret    = $select->rowCount();
//        $res = db_query($query);
//        if (!$res || (db_num_rows($res) == 0)) {
        if (!$res || ($ret == 0)) {
            return array();
        } else {
            $ret = array();
//            while ($row = db_fetch_assoc($res)) {
//                $ret[] = $row;
//            }
            $ret = $select->fetchAll();
            return $ret;
        }
    } else {
        $query = "SELECT COUNT(user_id) FROM cms_videos_temp $where";
//        $res = db_query($query);
//        $row = db_fetch_row($res);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res = $select->execute();
        $row = $select->fetch();
        return $row[0];
    }

//  <end>
}

function mimeIsVideo($mime) {
    if (!$mime)
        return false;
    $video_mime_types = array(
        'application/annodex',
        'application/mp4',
        'application/ogg',
        'application/vnd.rn-realmedia',
        'application/x-matroska',
        'video/3gpp',
        'video/3gpp2',
        'video/annodex',
        'video/divx',
        'video/flv',
        'video/h264',
        'video/mp4',
        'video/mp4v-es',
        'video/mpeg',
        'video/mpeg-2',
        'video/mpeg4',
        'video/ogg',
        'video/ogm',
        'video/quicktime',
        'video/ty',
        'video/vdo',
        'video/vivo',
        'video/vnd.rn-realvideo',
        'video/vnd.vivo',
        'video/webm',
        'video/x-bin',
        'video/x-cdg',
        'video/x-divx',
        'video/x-dv',
        'video/x-flv',
        'video/x-la-asf',
        'video/x-m4v',
        'video/x-matroska',
        'video/x-motion-jpeg',
        'video/x-ms-asf',
        'video/x-ms-dvr',
        'video/x-ms-wm',
        'video/x-ms-wmv',
        'video/x-msvideo',
        'video/x-sgi-movie',
        'video/x-tivo',
        'video/avi',
        'video/x-ms-asx',
        'video/x-ms-wvx',
        'video/x-ms-wmx',
        //mts
        'video/avchd-stream',
        'video/m2ts',
        'video/mp2t',
        'video/vnd.dlna.mpeg-tts'
    );
    return in_array($mime, $video_mime_types);
}

/**
 * media file copied to server
 * @param integer $vid the cms_videos record id
 * @param array $servers an array of servernames
 * @return boolean true|false if sucess failed
 */
function mediaCopiedToServers($vid, $servers) {
    global $dbConn;
    $params = array();

    $media_servers = implode(',', $servers);
    $query = "UPDATE cms_videos SET media_servers=:Media_servers,published=" . MEDIA_READY . " WHERE id=:Vid";
    $params[] = array( "key" => ":Media_servers",
                        "value" =>$media_servers);
    $params[] = array( "key" => ":Vid",
                        "value" =>$vid);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res  = $update->execute();
    
    return $res;
}

/**
 * gets the shrink dimension for a photo
 * @param string $src 
 * @param string $dest 
 * @param integer $val 
 * @return array contains <b>width</b> and <b>height</b>
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function photoShrinkDimensions($src, $val) {
//    $dims = imageGetDimensions($src);
//    $width = $dims['width'];
//    $height = $dims['height'];
//
//    $new_width = $val;
//    $new_height = $height * $new_width / $width;
//
//    if ($new_height < $val) {
//        $new_height = $val;
//        $new_width = $width * $new_height / $height;
//    }
//    return array('width' => intval($new_width), 'height' => intval($new_height));
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>

/**
 * Gets Views number for a user' s videos
 * @param integer $user_id the desired user id
 * @param integer $channel_id the desired channel id
 * @return integer
 */
function getVideoViews($user_id, $channel_id = 0) {

//  <start>
    global $dbConn;
    $params = array(); 
//    $query = "SELECT Sum( `nb_views` )
//        FROM `cms_videos`
//        WHERE `userid` ='" . $user_id . "' AND `channelid` ='" . $channel_id . "' AND `published` = '1' AND `image_video` = 'v'";
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
//        $row = db_fetch_array($ret);
//        return $row[0];
//    } else {
//        return 0;
//    }
    $query = "SELECT Sum( `nb_views` )
        FROM `cms_videos`
        WHERE `userid` =:User_id AND `channelid` =:Channel_id AND `published` = '1' AND `image_video` = 'v'";
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":Channel_id",
                        "value" =>$channel_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res  = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch();
        return $row[0];
    } else {
        return 0;
    }

//  <end>
}

/**
 * Gets Views number for a user' s Images
 * @param integer $user_id the desired user id
 * @param integer $channel_id the desired channel id
 * @return integer
 */
function getImageViews($user_id, $channel_id = 0) {

//  <start>
    global $dbConn;
    $params = array();  
//    $query = "SELECT Sum( `nb_views` )
//        FROM `cms_videos`
//        WHERE `userid` ='" . $user_id . "' AND `channelid` ='" . $channel_id . "' AND `published` = '1' AND `image_video` = 'i'";
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
//        $row = db_fetch_array($ret);
//        return $row[0];
//    } else {
//        return 0;
//    }
    $query = "SELECT Sum( `nb_views` )
        FROM `cms_videos`
        WHERE `userid` =:User_id AND `channelid` =:Channel_id AND `published` = '1' AND `image_video` = 'i'";
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":Channel_id",
                        "value" =>$channel_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row =  $select->fetch();
        return $row[0];
    } else {
        return 0;
    }

//  <end>
}
/*
 * Function to auto post uploaded media by user to the social media they are connected to
 */

function socialMediaAutoSharing($MediaInfo) {
    global $CONFIG;
    $global_link = 'https://media.touristtube.com';
    $userId = $MediaInfo['userid'];
    try {
        $userSocialMediaConnectedList = getUserSocialInfo($userId);

        // here we are creating URL to be posted on social media's according to the type of media
        $media_link = $MediaInfo['image_video'] == 'i' ? ReturnPhotoUri($MediaInfo) : ReturnVideoUriHashed($MediaInfo);
        $url = $media_link;
        foreach ($userSocialMediaConnectedList as $key => $UserInfo):
            if ($key == 'twitter') {
                /*
                 * connect to twitter to auto post
                 * include twitteroauth.php class and config file
                 */
                require_once($CONFIG['server']['root'] . '/libs/twitteroauth/twitteroauth/twitteroauth.php');
                require_once($CONFIG['server']['root'] . '/libs/twitteroauth/config.php');
                try {
                    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $UserInfo['oauth_token'], $UserInfo['oauth_token_secret']);
                    $status = $connection->post('statuses/update', array('status' => $MediaInfo['title'] . ': ' . $url));
                    if ($status->errors) {
                        //$arrerror = $status->errors;
                        //debug($arrerror);                   
                    }
                } catch (Exception $e) {
                }
            } elseif ($key == 'fb') {
                // Below file is required for making connection to facebook
                require_once($CONFIG ['server']['root'] . 'vendor/facebook/php-sdk-v4/src/Facebook/autoload.php');
                //debug($key);exit;
                class fb1 extends Facebook\Facebook {
                    function __construct($arg) {
                        parent::__construct($arg);
                    }
                    public function setCodeManually($code) {
                        $this->code = $code;
                    }
                    public function setStateManually($state) {
                        $this->state1 = $state;
                    }
                }
                $facebook = new fb1(array(
                    'appId' => '1045138925510219',
                    'app_id' => '1045138925510219',
                    'secret' => '87378d17c481361e8f8d526da84b3e50',
                    'app_secret' => '87378d17c481361e8f8d526da84b3e50'
                ));
                $facebook->setDefaultAccessToken($UserInfo['oauth_token']);
                //$fbUser = $facebook->getUser($UserInfo['oauth_token']);
                try {
                    $response = $facebook->get('/me?fields=id,name', $UserInfo['oauth_token']);
                    $fbUser = $response->getGraphUser();
                    if ($fbUser) {
                        try {
                            $linkData = ['link' => $url,'message' => $MediaInfo['title']];
                            $facebook->post('/me/feed', $linkData, $UserInfo['oauth_token']);
                        } catch (FacebookApiException $e) {
                            //exit($e);
        //                   header('Location:' .ReturnLink('account/callback-fb') );
                        }
                    } else {
        //header('Location:' .ReturnLink('account/callback-fb') );
                    }
                } catch(Facebook\Exceptions\FacebookResponseException $e) {
//                    echo 'Graph returned an error: ' . $e->getMessage();
//                    exit;
                } catch(Facebook\Exceptions\FacebookSDKException $e) {
//                    echo 'Facebook SDK returned an error: ' . $e->getMessage();
//                    exit;
                }                
            } elseif ($key == 'gplus') {
                // google plus api here
            } elseif ($key == 'yahoo') {
                //yahoo API here
            }

        endforeach;
        return true;
    } catch (Exception $e) {
       return false;
    }
}
/*
 * Function to get user social credential according to the userId
 */

function getUserSocialInfo($userId) {
    global $dbConn;
    $params = array();  

    $query = "SELECT account_type,oauth_token,oauth_token_secret FROM `cms_users_social_tokens` where `user_id`=:UserId and `status`=1";
    $params[] = array( "key" => ":UserId",
                        "value" =>$userId);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();
    $data = array();
    if($res){
        $row = $select->fetch(PDO::FETCH_ASSOC);
        $data[$row['account_type']] = array('oauth_token' => $row['oauth_token'], 'oauth_token_secret' => $row['oauth_token_secret']);
    }
    return $data;
}