<?php
	
	$expath = "../";			
	header("content-type: application/xml; charset=utf-8");  
	include("../heart.php");
	
//	$id = intval( $_REQUEST['id'] );
//	$user_id = db_sanitize( $_REQUEST['user_id'] );
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
	$id = intval( $submit_post_get['id'] );
	$user_id = db_sanitize( $submit_post_get['user_id'] );
	
	
	
	echo $output;
