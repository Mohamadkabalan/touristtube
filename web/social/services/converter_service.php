<?php

	$path = "../";

    $bootOptions = array("loadDb" => 1, "requireLogin" => 0, "loadLocation" => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );

    include_once ( $path . "inc/functions/users.php" );
    include_once ( $path . "inc/functions/videos.php" );
	
	include('lib/converter_service.inc.php');
	
	while( !ConverterServiceManager::StopRequested() ){
		
		if( !db_reconnect() ){
			ConverterServiceManager::Log("COULDNT CONNECT TO DB!");
			return;
		}
		
		$server_load = get_server_load_percentage();
		if( $server_load > 65 ){
			//the server is loaded so sleep
			ConverterServiceManager::Log("Server Load $server_load - sleeping...");
			sleep(5);
		}
		
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
	global $dbConn;
	$params = array(); 
//		db_query("LOCK TABLES cms_videos WRITE;");
		$prepared_query = "LOCK TABLES cms_videos WRITE;";
                $select0 = $dbConn->prepare($prepared_query);
                $res    = $select0->execute();
		
                $query = "SELECT * FROM cms_videos WHERE image_video='v' AND published=" . MEDIA_UPLOADED . " LIMIT 20";

                $select = $dbConn->prepare($query);
                $res    = $select->execute();

                $ret    = $select->rowCount();		
//		if( !$ret || (db_num_rows($ret) == 0 ) ){
		if( !$res || ($ret == 0 ) ){
//			db_query("UNLOCK TABLES;");
                        $prepared_query1 = "UNLOCK TABLES;";
                        $select1 = $dbConn->prepare($prepared_query1);
                        $select1->execute();
			ConverterServiceManager::Log('No Records To Process - sleeping...');
			sleep(5);
			continue;
		}		
		//find the shortest duration ad process it
		$shortest_id = -1;
		$shortest_time = 100000;
                $row = $select->fetchAll();
//		while($row = db_fetch_array($ret)){
                foreach($row as $row_item){
			$duration_sec = time_to_seconds($row_item['duration']);
			if( $duration_sec < $shortest_time ){
				$shortest_time = $duration_sec;
				$shortest_id = $row_item['id'];
			}
		}
		
		$vid = $shortest_id;
		
//		$query = "UPDATE cms_videos SET published=" . MEDIA_PROCESSING . " WHERE published=".MEDIA_UPLOADED." AND id='$vid'";
		$query = "UPDATE cms_videos SET published=" . MEDIA_PROCESSING . " WHERE published=".MEDIA_UPLOADED." AND id=:Vid";
                $params=array();
                $params[] = array(  "key" => ":Vid",
                                    "value" =>$vid);
                $select2 = $dbConn->prepare($query);
                PDO_BIND_PARAM($select2,$params);
                $res2    = $select2->execute();
//		db_query($query);
//		if( db_affected_rows() == 1){
		if( $res2 == 1){
			//continue
		}else{
			ConverterServiceManager::Log('row ' . $vid . ' already being processed');
			sleep(1);
			continue;
		}
//		db_query("UNLOCK TABLES;");
                $prepared_query3 = "UNLOCK TABLES;";
                $select3 = $dbConn->prepare($prepared_query3);
                $res3    = $select3->execute();
		
		ConverterServiceManager::Log('processing row ' . $vid);
		convertVideo($vid);
		ConverterServiceManager::Log('processed row ' . $vid);
		
//		$query = "UPDATE cms_videos SET published=".MEDIA_PROCESSED." WHERE id='$vid'";
		$query = "UPDATE cms_videos SET published=".MEDIA_PROCESSED." WHERE id=:Vid";
                $select4 = $dbConn->prepare($query);
                PDO_BIND_PARAM($select4,$params);
                $res4    = $select4->execute();
		
		
		
//		db_query($query);
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
		
	}
	ConverterServiceManager::Cleanup();