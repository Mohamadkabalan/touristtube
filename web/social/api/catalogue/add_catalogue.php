<?php

//	session_id($_REQUEST['S']);  
//        session_start();
$expath = "../";
include($expath."heart.php");

$submit_post_get = array_merge($request->query->all(), $request->request->all());
	
$uid = 0;
if (isset($submit_post_get['S']))
$uid = $submit_post_get['S'];

$userID = mobileIsLogged($uid);

$success = false;

if ($userID && isset($submit_post_get['name']))
{
	if ($submit_post_get['name'])
	{
		$name = filter_var($submit_post_get['name'], FILTER_SANITIZE_STRING);
		
		if (userCatalogAdd($userID, $name))
		{
			$success = true;
		}
	}
}

if ($success)
{
	echo 'done';
}
else
{
	echo 'error';
}