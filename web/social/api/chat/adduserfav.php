<?php

 

$expath = "../";			

include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//$id = mobileIsLogged($_REQUEST['S']);
$id = mobileIsLogged($submit_post_get['S']);
if( !$id ) die();
//$uid = xss_sanitize($_REQUEST['uid']);
$uid = $submit_post_get['uid'];
if (userFavoriteUserAdd($id, $uid))
{
        echo 'added';	
}else
{
        echo 'error';	
}
