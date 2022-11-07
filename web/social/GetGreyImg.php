<?php
$paths_array = array();
//path to directory to scan. i have included a wildcard for a subdirectory
$directory = "/media/external/hrs/*/*/";
 
//get all image files with a .jpg extension.
$images = glob("" . $directory . "*.jpg");
 
// $imgs = array();
// create array
// foreach($images as $image){ $imgs[] = "$image"; }
 
//shuffle array  
// shuffle($imgs);
 
//select first 20 images in randomized array
//$imgs = array_slice($imgs, 0, 900);
 
$imgs = $images;

//display images
// $i = 0;
foreach ($imgs as $img) {
//	echo"<br>";
  //  echo "<img id='i' class='imagetest' src='$img' /> ";
//	echo"<br>";
//	echo $img;
$im = imagecreatefromjpeg($img);
$hexa = average($im);
if($hexa == "#eeeeee")
{
 echo $img;
 $imgpath = $img;
 $myfile = file_put_contents('listofgrey.txt', $imgpath.PHP_EOL , FILE_APPEND | LOCK_EX);	
}

echo"<br>";
echo"Color is ";
echo $hexa;
// $i++;

}


function average($img) {
    $w = imagesx($img);
    $h = imagesy($img);
    $r = $g = $b = 0;
    for($y = 0; $y < $h; $y++) {
        for($x = 0; $x < $w; $x++) {
            $rgb = imagecolorat($img, $x, $y);
            $r += $rgb >> 16;
            $g += $rgb >> 8 & 255;
            $b += $rgb & 255;
        }
    }
    $pxls = $w * $h;
    $r = dechex(round($r / $pxls));
    $g = dechex(round($g / $pxls));
    $b = dechex(round($b / $pxls));
    if(strlen($r) < 2) {
        $r = 0 . $r;
    }
    if(strlen($g) < 2) {
        $g = 0 . $g;
    }
    if(strlen($b) < 2) {
        $b = 0 . $b;
    }
    return "#" . $r . $g . $b;
}
//$string_data = serialize($paths_array);
//file_put_contents('listofgrey.txt', $string_data);	
echo"<br>";
// echo "count: " . $i;
?>