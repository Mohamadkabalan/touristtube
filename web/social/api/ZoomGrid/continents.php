<?php
	$expath = "../";
	header("content-type: application/xml; charset=utf-8");  
	include("../heart.php");
	echo '<?xml version="1.0" encoding="utf-8"?>';
	echo getContinents();
	/*echo '<continents>
			<continent numberOfVideo="0" thumb="api/ZoomGrid/continents/photos/africa.jpg" code="AF">Africa</continent>
			<continent numberOfVideo="0" thumb="api/ZoomGrid/continents/photos/Asia.jpg" code="AS">Asia</continent>
			<continent numberOfVideo="0" thumb="api/ZoomGrid/continents/photos/europe.jpg" code="EU">Europe</continent>
			<continent numberOfVideo="0" thumb="api/ZoomGrid/continents/photos/north-america.jpg" code="NA">North America</continent>
			<continent numberOfVideo="0" thumb="api/ZoomGrid/continents/photos/south-america.jpg" code="SA">South America</continent>
			<continent numberOfVideo="0" thumb="api/ZoomGrid/continents/photos/Australia.jpg" code="OC">Australia</continent>
			<continent numberOfVideo="0" thumb="api/ZoomGrid/continents/photos/antartica.jpg" code="AN">Antartica</continent>
	</continents>';*/