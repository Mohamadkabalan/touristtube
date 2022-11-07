<?php

	$path = "../";

    $bootOptions = array("loadDb" => 1 , 'requireLogin' => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
    include_once ( $path . "inc/functions/users.php" );
	include_once ( $path . "inc/functions/videos.php" );
	
	
	global $dbConn;
	$params  = array();  
	$params2 = array(); 
        if(!userIsLogged()) { exit(); }
	$userID = userGetID();
//        $status = $_POST['status'];
        $status = $request->request->get('status', '');
        echo $userID;
//        $del_query = "DELETE FROM cms_chat_status WHERE user_id=$userID";
        $del_query = "DELETE FROM cms_chat_status WHERE user_id=:UserID";
	$params[] = array(  "key" => ":UserID",
                            "value" =>$userID);
	$delete = $dbConn->prepare($del_query);
	PDO_BIND_PARAM($delete,$params);
	$res    = $delete->execute();
//	db_query($del_query);
//        $query = "INSERT INTO cms_chat_status (user_id,status,last_action) VALUES ($userID,$status,NOW())";
        $query = "INSERT INTO cms_chat_status (user_id,status,last_action) VALUES (:UserID,:Status,NOW())";
	$params2[] = array(  "key" => ":UserID",
                             "value" =>$userID);
	$params2[] = array(  "key" => ":Status",
                             "value" =>$status);
	$insert = $dbConn->prepare($query);
	PDO_BIND_PARAM($insert,$params2);
	$res2   = $insert->execute();
//	$ret = db_query($query);
        //echo json_encode($ret); 
        
        