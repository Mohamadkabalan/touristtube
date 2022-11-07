<?php

	$path = "../";

    $bootOptions = array("loadDb" => 1, "requireLogin" => 0, "loadLocation" => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
	include_once ( $path . "inc/functions/videos.php" );
	
	//if the converter service was shutdown while converting this is needed to reset those that were being converted
	global $dbConn;
	$query = "UPDATE cms_videos SET published=" . MEDIA_UPLOADED . " WHERE published=".MEDIA_PROCESSING;
	$select = $dbConn->prepare($query);
	$res    = $select->execute();
//	db_query($query);
	
?>
