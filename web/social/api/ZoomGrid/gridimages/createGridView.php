<?php
function createGridView($bigWitdth,$bigHeight,$imgAry,$path,$GDImgName="")
{
	if(isset($GDImgName))
	{
		if($GDImgName != "")
		{
			if (file_exists('cache/'.$GDImgName.'.jpg'))
			{
				if (filemtime('cache/'.$GDImgName.'.jpg') < strtotime("1000 minutes ago"))
				{
					readAndOutput(realCreateGridViewImage($bigWitdth,$bigHeight,$imgAry,$path,$GDImgName));	
				}else
				{
						readAndOutput($GDImgName);				
				}
			}else
			{
				readAndOutput(realCreateGridViewImage($bigWitdth,$bigHeight,$imgAry,$path,$GDImgName));		
			}
		}else
		{
			readAndOutput(realCreateGridViewImage($bigWitdth,$bigHeight,$imgAry,$path,$GDImgName));	
		}
	}else
	{
		readAndOutput(realCreateGridViewImage($bigWitdth,$bigHeight,$imgAry,$path,$GDImgName));
	}
		
	//realCreateGridViewImage($bigWitdth,$bigHeight,$imgAry,$path,$GDImgName);
	
	//readAndOutput($GDImgName);
		
}
function readAndOutput($imgNamePath)
{
	$ctype="image/jpg";
	header("Content-Type: $ctype"); 
	
	readfile('cache/'.$imgNamePath.'.jpg');
	//readfile($imgNamePath);
}
function realCreateGridViewImage($bigWitdth,$bigHeight,$imgAry,$path,$GDImgName)
{
	$im = imagecreatetruecolor($bigWitdth, $bigHeight);
	$white = imagecolorallocate($im, 255, 255, 255);
	imagefilledrectangle($im, 0, 0, $bigWitdth, $bigHeight, "");
	imagejpeg($im, 'back.jpg');	
	imagedestroy($im);
	
	$backgroundSource = "back.jpg";
	
	$outputImage = imagecreatefromjpeg($backgroundSource);
	$imageSource = array();
	$imageStats = array();
	
	$counter = 0;
	$squareSize = floor(sqrt(sizeof($imgAry)));
	
	$thumbsMAX = $squareSize*$squareSize;
	
	
	$fixedThumbWidth = ceil($bigWitdth/$squareSize);
	$fixedThumbHeight = ceil($bigHeight/$squareSize);
	
	foreach($imgAry as $oneImage)
	{
		
		$colNum = ceil($counter/$squareSize);
		
		if($counter % $squareSize == 0) $currentLeft = 0;
		if($counter % $squareSize-2 == 0) $currentLeft = $fixedThumbWidth*2;
		if($counter % $squareSize-1 == 0) $currentLeft = $fixedThumbWidth*1;
	
	//	else if($counter < $squareSize)  $currentLeft = abs(($counter-$squareSize)*$fixedThumbWidth);
	//	else $currentLeft = abs((round($counter/$squareSize)-1)*$fixedThumbWidth);
		
		//echo $oneImage."<br>";
		
		
		$ThumbIndex = floor(($counter)/$squareSize);
		
		$currentTop = $ThumbIndex*$fixedThumbHeight;
		
		$imageSource[$counter] = $oneImage;
		/////doMobileResize($w,$h,$path,$referallink,$link=0)
		//if(file_exists()) echo "1000000000000000";
		
		
		$imageStats[$counter] = imagecreatefromjpeg($imageSource[$counter]);
		
		imagecopymerge($outputImage,$imageStats[$counter],$currentLeft,$currentTop,0,0,$fixedThumbWidth-1,$fixedThumbHeight-1,100);
		
		$counter++;
		if($counter == $thumbsMAX ) break;
	}	
	
	$imgName = time().md5(time());
	if(isset($GDImgName))
	{
		if($GDImgName != "")
		{
			$imgName = 	$GDImgName;
		}
	}
	
	imagejpeg($outputImage,'cache/'.$imgName.'.jpg');

	imagedestroy($outputImage);	
	return $imgName;
}


/*$backgroundSource = "back.jpg";





$outputImage = imagecreatefromjpeg($backgroundSource);

$images = array('Desert.jpg','Hydrangeas.jpg','Jellyfish.jpg','Koala.jpg','Lighthouse.jpg','Penguins.jpg');
$imageSource = array();
$imageStats = array();

$counter = 0;
$ArraySize = sizeof($images);
foreach($images as $oneImage)
{
	$imageSource[$counter] = $oneImage;
	$imageStats[$counter] = imagecreatefromjpeg($imageSource[$counter]);
	
	imagecopymerge($outputImage,$imageStats[$counter],0,0,0,0,imagesx($imageStats[$counter]),imagesy($imageStats[$counter]),100);
	$counter++;
}	



/*$feedBurnerStatsSource = "Desert.jpg";
$feedBurnerStats = imagecreatefromjpeg($feedBurnerStatsSource);
$feedBurnerStatsX = imagesx($feedBurnerStats);
$feedBurnerStatsY = imagesy($feedBurnerStats);

$feedBurnerStatsSource2 = "Hydrangeas.jpg";
$feedBurnerStats2 = imagecreatefromjpeg($feedBurnerStatsSource2);
$feedBurnerStatsX2 = imagesx($feedBurnerStats2);
$feedBurnerStatsY2 = imagesy($feedBurnerStats2);

$feedBurnerStatsSource3 = "Jellyfish.jpg";
$feedBurnerStats3 = imagecreatefromjpeg($feedBurnerStatsSource3);
$feedBurnerStatsX3 = imagesx($feedBurnerStats3);
$feedBurnerStatsY3 = imagesy($feedBurnerStats3);

$feedBurnerStatsSource4 = "Koala.jpg";
$feedBurnerStats4 = imagecreatefromjpeg($feedBurnerStatsSource4);
$feedBurnerStatsX4 = imagesx($feedBurnerStats4);
$feedBurnerStatsY4 = imagesy($feedBurnerStats4);

$feedBurnerStatsSource5 = "Lighthouse.jpg";
$feedBurnerStats5 = imagecreatefromjpeg($feedBurnerStatsSource5);
$feedBurnerStatsX5 = imagesx($feedBurnerStats5);
$feedBurnerStatsY5 = imagesy($feedBurnerStats5);

$feedBurnerStatsSource6 = "Penguins.jpg";
$feedBurnerStats6 = imagecreatefromjpeg($feedBurnerStatsSource6);
$feedBurnerStatsX6 = imagesx($feedBurnerStats6);
$feedBurnerStatsY6 = imagesy($feedBurnerStats6);*/




/*imagecopymerge($outputImage,$feedBurnerStats,0,0,0,0,$feedBurnerStatsX,$feedBurnerStatsY,100);
imagecopymerge($outputImage,$feedBurnerStats2,200,0,0,0,$feedBurnerStatsX2,$feedBurnerStatsY2,100);
imagecopymerge($outputImage,$feedBurnerStats3,400,0,0,0,$feedBurnerStatsX3,$feedBurnerStatsY3,100);
imagecopymerge($outputImage,$feedBurnerStats4,0,150,0,0,$feedBurnerStatsX4,$feedBurnerStatsY4,100);
imagecopymerge($outputImage,$feedBurnerStats5,200,150,0,0,$feedBurnerStatsX5,$feedBurnerStatsY5,100);
imagecopymerge($outputImage,$feedBurnerStats6,400,150,0,0,$feedBurnerStatsX6,$feedBurnerStatsY6,100);



imagejpeg($outputImage,'merged.jpeg');

imagedestroy($outputImage);

echo "<img src='merged.jpeg'>";*/

?>