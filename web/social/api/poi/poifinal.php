<?php
/*! \file
 * 
 * \brief This api  returns xml of point of interest
 * 
 * \todo <b><i>Change from xml to Json object</i></b>
 * 
 * @param page page number (starting from 0)
 * @param long point of interest longitude
 * @param lat point of interest latitude
 * @param rad point of interest radius
 * @param key search string
 * 
 * @return <b>res</b> xml :
 * @return <pre> 
 * @return         <b>id</b> point of interest id
 * @return         <b>name</b> point of interest name
 * @return         <b>longitude</b> point of interest longitude
 * @return         <b>latitude</b> point of interest latitude
 * @return         <b>rating</b> point of interest average rating
 * @return         <b>category_id</b> point of interest category id
 * @return         <b>n_review</b> point of interest number of review
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */
	header("content-type: application/xml; charset=utf-8");  
//	session_id($_REQUEST['S']);
	
	$expath = "../";
	
	
	include($expath."heart.php");

//	$page = $_GET['page'];
//	
//	$long = $_GET['long'];
//	$lat = $_GET['lat'];
//	$rad = $_GET['rad'];
	$page = $request->query->get('page','');
	
	$long = $request->query->get('long','');
	$lat = $request->query->get('lat','');
	$rad = $request->query->get('rad','');
	
	
	$key_get = $request->query->get('key','');
//	if(isset($_GET['key']))
	if($key_get)
	{
//		$keyword = $_GET['key'];
		$keyword = $key_get;
		$default_opts = array(
			'limit' => 30,
			'page' => $page,
			'latitude' => $lat,
			'longitude' => $long,
			'radius' => $rad,
			'search_string' => $keyword,
		);
		$data = locationSearch($default_opts);
		if (sizeof($data))
		{	
			$res = "<pois order='search'>";
			$res .= getFastCityTT($keyword);	
			$res .= getCountryCodeTT($keyword);
			foreach ($data as $poi)
			{
				$res .= "<poi>";
				$res .= "<id>".$poi['id']."</id>";
				$res .= "<name>".safeXML($poi['name'])."</name>";
				$res .= "<longitude>".$poi['longitude']."</longitude>";
				$res .= "<latitude>".$poi['latitude']."</latitude>";
				$res .= "<rating>".$poi['rating']."</rating>";
				$res .= "<category_id>".$poi['category_id']."</category_id>";
				$res .= "<n_review>".$poi['n_review']."</n_review>";
				$res .= "</poi>";
			}
			
			$res .= "</pois>";
			echo $res;
		}
		else
		{
			//echo "<pois></pois>";
			$res = "<pois order='search'>";
			$res .= getFastCityTT($keyword);	
			$res .= getCountryCodeTT($keyword);
			$res .= "</pois>";
			echo $res;
		}
	}
	else
	{
		//echo "Hello";
		//$keyword = "";
		//$long = $_GET['long'];
		//$lat = $_GET['lat'];
		$default_opts = array(
			'limit' => 30,
			'page' => $page,
			'latitude' => $lat,
			'longitude' => $long,
			'radius' => 1000
			
		);
		$data = locationSearch($default_opts);
		
		//var_dump($data);
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
		}	
		else
		{
			echo "<pois></pois>";
		}
	}