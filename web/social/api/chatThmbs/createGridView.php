<?php
function createChatThumb()
{
		
	$img1 = imagecreateFromjpeg("./Profile_1343975179_43.jpg");
	$x=imagesx($img1)-$width ;
	$y=imagesy($img1)-$height;
	
	
	
	
	$img2 = imagecreatetruecolor($x, $y);
	$black = imagecolorallocate($img2, NULL, NULL, NULL);
	$transparent_bg = imagecolortransparent($img2, $black);
	imagefill($img2, 0, 0, $transparent_bg);
	
	$img3 = "thumbStroke.png";
	$imageStats = imagecreatefrompng($img3);
		
	
	
	$e = imagecolorallocate($img2, NULL, NULL, NULL);
	
	$r = $x <= $y ? $x : $y;
	imagefilledellipse($img2, ($x/2), ($y/2), $r, $r, $e); 
	
	imagecolortransparent($img2, $e);
	imagecolortransparent($imageStats,$e);
	
	
	imagecopymerge($img1,$imageStats,0, 0, 0, 0, $x, $y, 10);
	imagecopymerge($img1, $img2, 0, 0, 0, 0, $x, $y, 100);
	
	imagecolortransparent($img1, $transparent_bg);
	//header("Content-type: image/png");
	imagealphablending($img1,false);
	imagesavealpha($img1,true);
	imagepng($img1,"cache/3.png",9);
	
	
	//readfile('cache/3.png');
	
	
	/*imagepng($img1,"cache/2.png");
	imagedestroy($img1); 
	header("Content-type: image/png");
	readfile('cache/2.png');
*/
	
		
}