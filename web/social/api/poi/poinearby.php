<?php
	header("content-type: application/xml; charset=utf-8");  
//	session_id($_REQUEST['S']);
	
	$expath = "../";
	
	
	include($expath."heart.php");

//	$long = $_GET['long'];
//	$lat = $_GET['lat'];
	$long = $request->query->get('long','');
	$lat = $request->query->get('lat','');
	//$keyword = $_GET['key'];
	
	$default_opts = array(
			'limit' => 30,
			'latitude' => $long,
			'longitude' => $lat,
			'radius' => 1000,
			//'search_string' => $keyword,
	);
	
	$data = locationSearch($default_opts);
	
	if (sizeof($data))
	{	
		$res = "<pois>";
		foreach ($data as $poi)
		{
			$res .= "<poi>";
				$res .= "<id>".$poi['id']."</id>";
				$res .= "<name>".safeXML($poi['name'])."</name>";
				$res .= "<longitude>".$poi['longitude']."</longitude>";
				$res .= "<latitude>".$poi['latitude']."</latitude>";
				$res .= "<rating>".$poi['rating']."</rating>";
				$res .= "<category_id>".$poi['category_id']."</category_id>";
			$res .= "</poi>";
		}
		$res .= "</pois>";	
		echo $res;
	}else
	{
		echo "<pois></pois>";
	}