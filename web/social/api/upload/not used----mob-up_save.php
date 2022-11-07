<?php

// fixing session issue
//session_id($_POST['S']);


$path    = "../../";

$bootOptions = array ( "loadDb" => 1, "loadLocation" => 1, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" ); 


$userId = $_SESSION['id'];
//session_write_close(); 

include_once ( $path . "touristtubeAPI/function/videos.php" );
$videoFile = array();

//$videoFile [ 'name' ] = $_POST ['vName']; 
$videoFile [ 'name' ] = $request->request->get('vName', ''); 
$vPath = $request->request->get('vName', '');

$uploadPath = $path . $CONFIG [ 'video' ] [ 'uploadPath' ] . $vPath;
$file = $uploadPath . $videoFile [ 'name' ];

$videoDetails = videoDetails (  $CONFIG [ 'video' ] [ 'videoCoverter' ], $uploadPath . $videoFile ['name'] );

$VideoDetails = explode('|', $videoDetails);
$videoDuration = $VideoDetails[0];
$videoWidth = $VideoDetails[1];
$videoHeight = $VideoDetails[2];

$videoFile [ 'size' ] = $request->request->get('vSize', '');
$videoFile [ 'type' ] = mime_content_type( $file );	

$videoFile ['relativepath'] = $vPath;
$videoFile ['fullpath'] 	= $CONFIG [ 'video' ] [ 'uploadPath' ] . $vPath;

$videoFile ['userid'] 		= $userId;

//$videoFile ['title']		= db_sanitize ( $_POST ['title'] );
//$videoFile ['description']  = db_sanitize ( $_POST ['description'] );
//$videoFile ['category']		= db_sanitize ( $_POST ['category'] );
//$videoFile ['placetakenat'] = db_sanitize ( $_POST ['placetakenat'] );
//$videoFile ['keywords']		= db_sanitize ( $_POST ['keywords'] );
//$videoFile ['country']		= db_sanitize ( $_POST ['country'] );
$videoFile ['title']		= $request->request->get('title', '');
$videoFile ['description']      = $request->request->get('description', '');
$videoFile ['category']		= $request->request->get('category', '');
$videoFile ['placetakenat']     = $request->request->get('placetakenat', '');
$videoFile ['keywords']		= $request->request->get('keywords', '');
$videoFile ['country']		= $request->request->get('country', '');
$videoFile ['duration']		= db_sanitize ( $videoDuration );
$videoFile ['dimension']	= db_sanitize ( $videoWidth.' X '.$videoHeight );
//$videoFile ['lattitude']	= db_sanitize ( $_POST ['lattitude'] );
//$videoFile ['longitude']	= db_sanitize ( $_POST ['longitude'] );
$videoFile ['lattitude']	= $request->request->get('lattitude', '');
$videoFile ['longitude']	= $request->request->get('longitude', '');

$id = videoExists2($videoFile['name'],$userId);
if( !$id ){
	$videoId = saveVideo ( $videoFile, $dbConn );
}else{
	$videoId = $id;
	updateVideo2 ($id, $videoFile );
}

$vInfo   = getVideoInfo ( $videoId );

//if video => output id so use can select from list of thumbs
//if image just output thumb

//$videoId = saveVideo ( $videoFile, $dbConn );
//$vInfo   = getVideoInfo ( $videoId );

if( strstr($videoFile[ 'type' ], 'image/') == null ){
    createThumbnail ( $CONFIG [ 'video' ] [ 'videoCoverter' ], $uploadPath . $videoFile ['name'], $uploadPath , $vInfo['code'] );
    echo $videoId;
}else{
    $path_parts = pathinfo($uploadPath . $videoFile ['name']);
    $thumb = $path_parts['dirname'] . '/thumb_' . $path_parts['filename'] . '.jpg';
    createThumbnailFromImage($uploadPath . $videoFile ['name'], $thumb);
    //echo $thumb;
}

//alphonse start
//$videoFile['cityname'] =  db_sanitize ( $_POST ['cityname'] );
//$videoFile['cityaccent'] =  db_sanitize ( $_POST ['cityaccent'] );
$videoFile['cityname'] =  $request->request->get('cityname', '');
$videoFile['cityaccent'] =  $request->request->get('cityaccent', '');

//createThumbnail ( $CONFIG [ 'video' ] [ 'videoCoverter' ], $uploadPath . $videoFile ['name'], $uploadPath , $vInfo['code'] );
createMobileThumbnail( $CONFIG [ 'video' ] [ 'videoCoverter' ], $uploadPath . $videoFile ['name'], $uploadPath , $vInfo['code'] );

echo $videoId;
//echo  $CONFIG [ 'video' ] [ 'videoCoverter' ]. $uploadPath . $videoFile ['name']. $uploadPath. $vInfo['code']; 




