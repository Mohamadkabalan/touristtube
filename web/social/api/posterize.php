<?php
if (!isset($expath))
	{
		$expath = "";	
	}
	$path = "../".$expath;

ob_start();

if(file_exists($path."inc/dev.config.php"))
	require_once($path."inc/dev.config.php");
else
	require_once($path."inc/config.php");
require_once($path."inc/functions/videos.php");

$l_get = $request->query->get('l','');
$w_get = $request->query->get('w','');
$h_get = $request->query->get('h','');
//if ((isset($_GET['l'])) && (isset($_GET['w'])) &&(isset($_GET['h'])))
if (($l_get) && ($w_get) &&($h_get))
{
	$reflink = urldecode($l_get);
	$t_width = $w_get;
	$t_height = $h_get;
	doMobilePoster($t_width,$t_height,$path,$reflink);
}
function doMobilePoster($w,$h,$path,$referallink,$linkss=0)
{
	global $CONFIG;
	
	
}