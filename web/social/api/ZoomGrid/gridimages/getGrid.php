<?php
	//header("content-type: application/xml; charset=utf-8");  
	$expath = "../../";
		
	

	include_once("../../heart.php");
	include_once("../../resizepicture.php");
	include_once("createGridView.php");

	$requestContinentCode = "";
	$requestCountryCode = "";
	$requestCityID = "";

//	$requestContinentCode = @$_REQUEST['continentCode'];
//	$requestCountryCode = @$_REQUEST['countryCode'];
//	$requestCityID = @$_REQUEST['cityID'];	
//
//	$widthOfGrid = @$_REQUEST['width'];
//	$heightOfGrid = @$_REQUEST['height'];

        $submit_post_get = array_merge($request->query->all(),$request->request->all());
	$requestContinentCode = $submit_post_get['continentCode'];
	$requestCountryCode = $submit_post_get['countryCode'];
	$requestCityID = $submit_post_get['cityID'];	

	$widthOfGrid = $submit_post_get['width'];
	$heightOfGrid = $submit_post_get['height'];
	
	$widthOfTh = floor($widthOfGrid/3);
	$heightOfTh = floor($heightOfGrid/3);
	
	
	
	$imagesArray = getVideosGridBy($requestContinentCode,$requestCountryCode,$requestCityID,$widthOfTh,$heightOfTh);
	//var_dump($imagesArray);
	/*foreach($imagesArray as $oneImage)
	{
		$imagesArray[] = "../../../resizepicture.php?l=".urlencode(substr($oneImage,9,strlen($oneImage)))."&w=".$widthOfTh."&h=".$heightOfTh;	
	}*/
	$filename = base64_encode($requestContinentCode.$requestCountryCode.$requestCityID.$widthOfGrid.$heightOfGrid);
	createGridView($widthOfGrid,$heightOfGrid,$imagesArray,"",$filename);
?>