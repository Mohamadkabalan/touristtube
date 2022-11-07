<?php
	header("content-type: application/xml; charset=utf-8");  
//	session_id($_REQUEST['S']);
	
	$expath = "../";
	
	
	include($expath."heart.php");

//	$longitude = $_GET['long'];
//	$latitude = $_GET['lat'];
//	$radius = $_GET['rad'];
        $longitude = $request->query->get('long','');
        $latitude = $request->query->get('lat','');
        $radius = $request->query->get('rad','');
//	
	$data = getTubersWithInfo($latitude, $longitude, $radius);
	if (sizeof($data))
	{	
		$res = "<tubers>";
		$res .= $data;
		$res .= "</tubers>";
		echo $res;
	}
	else
	{
		echo "<tubers></tubers>";
	}