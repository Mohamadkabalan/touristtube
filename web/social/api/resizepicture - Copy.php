<?php
$path = "../";

ob_start();

require_once($path."inc/config.php");
require_once($path."inc/functions/videos.php");

$thumbcache = "cache/thumbs/";


//$referallink = urldecode($_GET['l']);
$referallink = urldecode($request->query->get('l',''));

//$t_width = $_GET['w'];
//$t_height = $_GET['h'];
$t_width = $request->query->get('w','');
$t_height = $request->query->get('h','');

$md5_reflink = md5($referallink);

$filename = $md5_reflink."_".$t_width."_".$t_height.".".get_file_extension($referallink);

$chache_thumb_path = $path.$thumbcache.$filename;

function doMobileResize($w,$h,$link)
{
	if (file_exists($chache_thumb_path))
	{
		gogogo($chache_thumb_path);
		//header("Location: $linktoimage");
	}else
	{
		if ( $ret = createThumbnailFromImageDynamic($path.$referallink, $chache_thumb_path,$t_width,$t_height)){
			//echo $linktoimage;
			gogogo($chache_thumb_path);
		}
	}
}
 


function get_file_extension($file_name) {
	return end(explode('.',$file_name));
}

function gogogo($link)
{
	ob_clean();
	
	switch (get_file_extension($link)) { 
		case "pdf": $ctype="application/pdf"; break; 
		case "exe": $ctype="application/octet-stream"; break; 
		case "zip": $ctype="application/zip"; break; 
		case "doc": $ctype="application/msword"; break; 
		case "xls": $ctype="application/vnd.ms-excel"; break; 
		case "ppt": $ctype="application/vnd.ms-powerpoint"; break; 
		case "gif": $ctype="image/gif"; break; 
		case "png": $ctype="image/png"; break; 
		case "jpeg": 
		case "jpg": $ctype="image/jpg"; break; 
		default: $ctype="application/force-download"; break; 
	} 

    /*header("Pragma: public"); // required 
    header("Expires: 0"); 
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
    header("Cache-Control: private",false); // required for certain browsers */
    header("Content-Type: $ctype"); 
/*    header("Content-Disposition: attachment; filename=\"".basename($fullPath)."\";" ); 
    header("Content-Transfer-Encoding: binary"); 
    header("Content-Length: ".$fsize);  */

    readfile( $link );

}