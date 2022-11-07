<?php

// fixing session issue

$submit_post_get = array_merge($request->query->all(),$request->request->all());
session_id($submit_post_get['S']);


$path    = "../../";

$bootOptions = array ( "loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);
require_once ( $path . "inc/common.php" );
require_once ( $path . "inc/bootstrap.php" ); 

$userId = $_SESSION['id'];
//session_write_close(); 

require_once ( $path . "inc/functions/videos.php" );
$videoFile = array();

$videoFile [ 'name' ] = $request->request->get('vName', '');
$vPath = $request->request->get('description', '');
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
$videoFile ['title']		= $request->request->get('title', '');
$videoFile ['description']  = $request->request->get('description', '');
$videoFile ['category']		= $request->request->get('category', '');
$videoFile ['placetakenat'] = $request->request->get('placetakenat', '');
$videoFile ['keywords']		= $request->request->get('keywords', '');
if( ($cat_name = categoryGetName($videoFile['category'])) != false){
	$videoFile['keywords'] .= ', ' . $cat_name;
}
//$videoFile ['country']		= db_sanitize ( $_POST ['country'] );
//$videoFile ['duration']		= db_sanitize ( $videoDuration );
//$videoFile ['dimension']	= db_sanitize ( $videoWidth.' X '.$videoHeight );
//$videoFile ['lattitude']	= doubleval ( $_POST ['lattitude'] );
//$videoFile ['longitude']	= doubleval ( $_POST ['longitude'] );
//$videoFile ['is_public']	= intval($_POST ['is_public']);
$videoFile ['country']		= $request->request->get('country', '');
$videoFile ['duration']		= db_sanitize ( $videoDuration );
$videoFile ['dimension']	= db_sanitize ( $videoWidth.' X '.$videoHeight );
$videoFile ['lattitude']	= doubleval ( $request->request->get('lattitude', ''));
$videoFile ['longitude']	= doubleval ( $request->request->get('longitude', ''));
$videoFile ['is_public']	= intval($request->request->get('is_public', ''));

//$m_city_name = db_sanitize ( $_POST ['cityname'] );
//$m_city_accent = db_sanitize ( $_POST ['cityaccent'] );
$m_city_name = $request->request->get('cityname', '');
$m_city_accent = $request->request->get('cityaccent', '');

$m_city_id = getCityId($m_city_name,$m_city_accent,$videoFile ['country']);

if( isset($m_city_id)){
	$videoFile ['cityid']	= intval($m_city_id);
	$videoFile ['cityname']	= $m_city_name;
}else{
	$ret = locationDecompose($videoFile ['lattitude'], $videoFile ['longitude']);
	if($ret != false){
		$city_rec = cityGetRecord($ret);
		$videoFile ['cityid']	= $city_rec['id'];
		$videoFile ['cityname']	= $city_rec['name'];
	}else{
		$videoFile ['cityid'] = 50; //in case nothing was found
		$videoFile ['cityname']	= '';
	}
}



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
if( strstr($videoFile[ 'type' ], 'image/') == null ){
    createThumbnail ( $CONFIG [ 'video' ] [ 'videoCoverter' ], $uploadPath . $videoFile ['name'], $uploadPath , $vInfo['code'] );
    echo $videoId;
}else{
    $path_parts = pathinfo($uploadPath . $videoFile ['name']);
    $thumb = $path_parts['dirname'] . '/thumb_' . $path_parts['filename'] . '.jpg';
    createThumbnailFromImage($uploadPath . $videoFile ['name'], $thumb);
    echo $thumb;
}

?>