<?php
$path = "";
$bootOptions = array("loadDb" => 1, "loadLocation" => 1, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/functions/videos.php" );

$category_name = seoDecodeURL(UriGetArg(0));
$category_id = categoryGetID($category_name);

tt_global_set('category_id', $category_id);
tt_global_set('category_name', $category_name);
tt_global_set('title', $category_name);
$page = UriGetArg(1);
if($page == null) include('index.php');
else include($page . '.php');