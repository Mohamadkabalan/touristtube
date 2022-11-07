<?php

	$path = "../";

    $bootOptions = array("loadDb" => 1, "requireLogin" => 0, "loadLocation" => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
    include_once ( $path . "inc/functions/users.php" );
    include_once ( $path . "inc/functions/videos.php" );
	
	function appendToLog($msg){
		file_put_contents("log/post_converter.log", date('Y-m-d H:i:s') . ' - ' . $msg . "\n", FILE_APPEND);
	}
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
	global $dbConn;
	$params = array();  
	
	while( true ){
		
		if( !db_reconnect() ){
			appendToLog("COULDNT CONNECT TO DB!");
			return;
		}
		
		$server_load = get_server_load_percentage();
		if( $server_load > 65 ){
			//the server is loaded so sleep
			appendToLog("Server Load $server_load - sleeping...");
			sleep(5);
		}
		
//		db_query("LOCK TABLES cms_social_posts WRITE;");
                $prepare_query = "LOCK TABLES cms_social_posts WRITE;";
                $select0 = $dbConn->prepare($prepare_query);
                $select0->execute();
                
		$query = "SELECT * FROM cms_social_posts WHERE is_video=1 AND published=0 LIMIT 1";
//		$ret = db_query($query);
                $select = $dbConn->prepare($query);
                $res    = $select->execute();

                $ret    = $select->rowCount();
		
//		if( !$ret || (db_num_rows($ret) == 0 ) ){
		if( !$res || ($ret == 0 ) ){
//			db_query("UNLOCK TABLES;");
                        $prepare_query1 = "UNLOCK TABLES;";
                        $select1 = $dbConn->prepare($prepare_query1);
                        $select1->execute();
			appendToLog('No Records To Process - sleeping...');
			sleep(5);
			continue;
		}
		
//		$row = db_fetch_array($ret);
                $row = $select->fetch();
		
		$vid = $row['id'];		
//		$query = "UPDATE cms_social_posts SET published=-1 WHERE published=0 AND id='$vid'";
		$query = "UPDATE cms_social_posts SET published=-1 WHERE published=0 AND id=:Vid";
//		db_query($query);
                $params=array();
                $params[] = array(  "key" => ":Vid",
                                    "value" =>$vid);
                $select2 = $dbConn->prepare($query);
                PDO_BIND_PARAM($select2,$params);
                $res2    = $select2->execute();
//		if( db_affected_rows() == 1){
		if( $res2 == 1){
			//continue
		}else{
			appendToLog('row ' . $vid . ' already being processed');
			sleep(1);
			continue;
		}
//		db_query("UNLOCK TABLES;");
                $prepare_query2 = "UNLOCK TABLES;";
                $select3 = $dbConn->prepare($prepare_query2);
                $res3    = $select3->execute();
		
		appendToLog('processing row ' . $vid);
		convert_video_posts($vid);
		appendToLog('processed row ' . $vid);
		
//		$query = "UPDATE cms_social_posts SET published=1 WHERE id='$vid'";
		$query = "UPDATE cms_social_posts SET published=1 WHERE id=:Vid";
                $select4 = $dbConn->prepare($query);
                PDO_BIND_PARAM($select4,$params);
                $res4    = $select4->execute();
//		db_query($query);
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
		
	}