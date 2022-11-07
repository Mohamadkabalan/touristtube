<?php
$c_get = $request->query->get('c','');
//if (isset($_GET['c']))
if ($c_get)
{
	//if (is_numeric($_GET['id']))
	//{
		include("heart.php");
//		$raw = getFastCityWithCoutnry($_GET['ccode'],$_GET['c']);
                $ccode_get =$request->query->get('ccode','');
		$raw = getFastCityWithCoutnry($ccode_get,$c_get);	
		header("content-type: application/xml; charset=utf-8");  
		echo '<?xml version="1.0" encoding="utf-8"?>';
		echo $raw;
	//}
}