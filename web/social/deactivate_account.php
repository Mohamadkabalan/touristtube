<?php

	$path = "";
	
	$bootOptions = array ( "loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1 );
	include_once ( $path . "inc/common.php" );
	include_once ( $path . "inc/bootstrap.php" ); 
	include_once ( $path . "inc/functions/users.php" ); 
	
	$user_id = userGetID();
	
	//userDeactivate($user_id);
	userDisable($user_id);
	
	userLogout();
	
	header('Location: ' . ReturnLink('') );

?>