<?php
	header("content-type: application/xml; charset=utf-8");  
	$expath = "../";
	include("../heart.php");
	echo '<?xml version="1.0" encoding="utf-8"?>';
	echo getCities($_GET['ccode']);