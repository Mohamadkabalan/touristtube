<?php
	$path = "../";

    $bootOptions = array("loadDb" => 1, "requireLogin" => 0, "loadLocation" => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
//    include_once ( "lib/media_copy_service.inc.php" );
    include_once ( $path . "inc/functions/videos.php" );
	
    function MediaCopyLog($logline){
		//file_put_contents("log/media_copy_service.log", date('r') . ' - ' .$logline . PHP_EOL, FILE_APPEND);
	}
	while(true){
		
		if( !db_reconnect() ){
			MediaCopyLog("COULDNT CONNECT TO DB");
			return;
		}
		
                global $dbConn;
		$query = "SELECT * FROM cms_videos WHERE published=" . MEDIA_PROCESSED . " LIMIT 1";
                $select = $dbConn->prepare($query);
                $res    = $select->execute();
//		$ret = db_query($query);

                $ret    = $select->rowCount();
		
//		if( !$ret || (db_num_rows($ret) ==0 ) ){
		if( !$res || ($ret ==0 ) ){
			sleep(5);
			MediaCopyLog('No Records To Process - sleeping...');
			continue;
		}
		
//		$MediaInfo = db_fetch_assoc($ret);
                $MediaInfo = $select->fetch(PDO::FETCH_ASSOC);
		
		$servers = array();
		$files = array();
                
//		Commented By para-soft12 Anthony Malak 03-27-2015 cleaning code needs to be remove no meaning 
//		<start>
//		if($MediaInfo['image_video'] == 'i'){
//			//image file
//		}else{
//			//video file
//		}
//		
//                
//		$copies = MEDIA_MAX_COPY;
//		for($i=0;$i < $copies;$i++){
//			
//			$which_server = MediaCopyGenerateServer();
//			$server_name = MediaCopyGetServer($which_server);
//			
//			if($server_name == false) continue;
//			
//			if( !MediaCopyToServer($files,$which_server) ){
//				MediaCopyLog('COULDNT COPY ' . implode('|',$files) . ' to ' . $server_name );
//				continue;
//			}
//			
//			$servers[] = $server_name;
//		}
//		</end>
		$servers[] = 'www.touristtube.com';
		
		if( !mediaCopiedToServers($MediaInfo['id'],$servers)){
			MediaCopyLog('COULDNT UPDATE DB for ' . $MediaInfo['id'] );
			sleep(5);
		}
		else{
			//para-soft7 added 18-dec-2013 
			$solr_posd_ids[]=$MediaInfo['id'];
		}		
                socialMediaAutoSharing($MediaInfo);
		MediaCopyLog('FINISHED ' . $MediaInfo['id'] );
	}
        /////COMENT