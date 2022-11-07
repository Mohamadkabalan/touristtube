<?php
$a_get = $request->query->get('a','');
//if (isset($_GET['a']))
if ($a_get)
{
	
	header("content-type: application/xml; charset=utf-8");  
	$expath = "../";
	include("../heart.php");
	
//	$datas = getAllSearch($_GET['a']);
	$datas = getAllSearch($a_get);
}