<?php
$expath = '../';
include_once("../heart.php");	

$submit_post_get = array_merge($request->query->all(),$request->request->all());

$uid = 0;
if (isset($submit_post_get['S']))
	$uid = $submit_post_get['S'];

$user_id = mobileIsLogged($uid);

if($user_id === false || !$user_id)
{
	echo json_encode(array('status' => 'fail'));
	exit();
}

$token = $submit_post_get['tokenid'];

$result = delToken($token, $user_id); // defined in web/social/api/heart.php

userEndSession($uid); // defined in web/social/inc/functions/users.php

echo json_encode(array('status' => $result));