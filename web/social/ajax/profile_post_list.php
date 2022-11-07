<?php
$path = "../";

$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );

//$userId = intval(@$_POST['user_id']);
//$page = intval(@$_POST['page']);
//$oneobject = intval(@$_POST['oneobject']);
$userId = intval($request->request->get('user_id', 0));
$page = intval($request->request->get('page', 0));
$oneobject = intval($request->request->get('oneobject', 0));
$loggedUser = userGetID();

$limit =10;
$reallimit=$limit;
$realpage = $page;
if($oneobject==1){
    $reallimit =1;
    $realpage = (($page+1)*$limit)-1;
}
//$frtxt = xss_sanitize(@$_POST['frtxt']);
$frtxt =$request->request->get('frtxt', '');
if ($frtxt == "") {
    $frtxt = null;
}

//$totxt = xss_sanitize(@$_POST['totxt']);
$totxt = $request->request->get('totxt', '');
if ($totxt == "") {
    $totxt = null;
}

include($path.'parts/profile_post_list.php');