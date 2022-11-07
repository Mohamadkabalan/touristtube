<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 function getStaticMapImage($latitude, $longitude, $marker, $filename = 'test.png', $path = '' ){
    if($latitude == '' || $longitude == '')
        return;
    $marker = "color:yellow%7Clabel:$marker%7C$latitude,$longitude";
    $url = "http://maps.googleapis.com/maps/api/staticmap?key=AIzaSyBJPoaOvHnqZ8QTvSdW96y0EMnxfn5Yh80&center=$latitude,$longitude&markers=$marker&zoom=14&size=300x300&sensor=false";
    $serverApiKey = "";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
    $raw = curl_exec($ch);
    curl_close ($ch);
    if($path == '')
        $path = getcwd().'/uploads/'.$filename;
    else
        $path = $path.'/'.$filename;
    $fp = fopen($path,'x');
    fwrite($fp, $raw);
    fclose($fp);
 }