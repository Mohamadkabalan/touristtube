<?php

$max_width = 1000;
$max_height = 800;

//$submit_post_get = array_merge($request->query->all(),$request->request->all());
$image = $_GET["imgfile"];
$ww = intval($_GET["ww"]);
$hh = intval($_GET["hh"]);
//$image = $submit_post_get["imgfile"];

	if (isset($_REQUEST["max_width"])) { if($_REQUEST["max_width"] < $max_width) $max_width = $_REQUEST["max_width"]; }
	
	if (isset($_REQUEST["max_height"])) { if($_REQUEST["max_height"] < $max_height) $max_height = $_REQUEST["max_height"]; }
//	if (isset($submit_post_get["max_width"])) { if($submit_post_get["max_width"] < $max_width) $max_width = $submit_post_get["max_width"]; }
//	
//	if (isset($submit_post_get["max_height"])) { if($submit_post_get["max_height"] < $max_height) $max_height = $submit_post_get["max_height"]; }
	
	if (strrchr($image, '/')) {
		$filename = substr(strrchr($image, '/'), 1); // remove folder references
	} else {
		$filename = $image;
	}
	
	$size = getimagesize($image);
	$width = $size[0];
	$height = $size[1];
        if($width<$max_width && $height<$max_height){
            $max_width = $width;
            $max_height = $height;
        }
	// get the ratio needed
	$x_ratio = $max_width / $width;
	$y_ratio = $max_height / $height;
	
	// if image already meets criteria, load current values in
	// if not, use ratios to load new size info
	if (($width <= $max_width) && ($height <= $max_height) ) {
		$tn_width = $width;
		$tn_height = $height;
	} else if ( $x_ratio < $y_ratio) {
		$tn_height = ceil($x_ratio * $height);
		$tn_width = ceil($x_ratio * $width);
	} else {
		$tn_height = ceil($y_ratio * $height);
		$tn_width = ceil($y_ratio * $width);
	} /*else if (($x_ratio * $height) < $max_height) {
		$tn_height = ceil($x_ratio * $height);
		$tn_width = $max_width;
	} else {
		$tn_width = ceil($y_ratio * $width);
		$tn_height = $max_height;
	}*/
        
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
	$resized = 'cache/'.$filename;
	$imageModified = @filemtime($image);
	$thumbModified = @filemtime($resized);
	
	header("Content-type: image/jpeg");
	
	// if thumbnail is newer than image then output cached thumbnail and exit
	if($imageModified<$thumbModified) {
		header("Last-Modified: ".gmdate("D, d M Y H:i:s",$thumbModified)." GMT");
		readfile($resized);
		exit;
	}
	
	// read image
	$ext = strtolower(substr(strrchr($image, '.'), 1)); // get the file extension
	switch ($ext) { 
		case 'jpg':     // jpg
			$src = imagecreatefromjpeg($image) or notfound();
			break;
		case 'png':     // png
			$src = imagecreatefrompng($image) or notfound();
			break;
		case 'gif':     // gif
			$src = imagecreatefromgif($image) or notfound();
			break;
		case 'JPG':     // JPG
			$src = imagecreatefromjpeg($image) or notfound();
			break;
		case 'jpeg':     // jpeg
			$src = imagecreatefromjpeg($image) or notfound();
			break;
		default:
			notfound();
	}
	
	// set up canvas
	$dst = imagecreatetruecolor($tn_width,$tn_height);
	
	//imageantialias ($dst, true);
	
	// copy resized image to new canvas
	imagecopyresampled ($dst, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
	
	/* Sharpening adddition by Mike Harding */
	// sharpen the image (only available in PHP5.1)
	if (function_exists("imageconvolution")) {
            $matrix = array(array( -1, -1, -1 ),array( -1, 32, -1 ),array( -1, -1, -1 ));
            $divisor = 24;
            $offset = 0;	
            imageconvolution($dst, $matrix, $divisor, $offset);
	}
	
	// send the header and new image
	imagejpeg($dst, null, 80);
	imagejpeg($dst, $resized, 80); // write the thumbnail to cache as well...
	
	// clear out the resources
	imagedestroy($src);
	imagedestroy($dst);
	
	//unlink($image);
	
?>