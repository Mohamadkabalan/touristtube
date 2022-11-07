<?php

	$path = "../";

    $bootOptions = array("loadDb" => 1, "requireLogin" => 0, "loadLocation" => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
    include_once ( $path . "inc/functions/videos.php" );
	
	if( file_exists('pid/cam_job.pid') ){
		//file_put_contents('log/cam_job.log', 'Couldnt start Cam Job: ' . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
		exit(0);
	}	
	global $dbConn;
	//file_put_contents('log/cam_job.log', 'Started Cam Job: ' . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
	$query = "SELECT * FROM cms_webcams WHERE state<>0";
	$select = $dbConn->prepare($query);
	$res    = $select->execute();
        
	$n = 0;
	$invalid = 0;
	$came_back = 0;
	file_put_contents('pid/cam_job.pid', 'working');
	$row = $select->fetchAll();
//	while($row = db_fetch_array($res)){
        foreach($row as $row_item){

		$resolution = '320x240';
		$stil_url = $row_item['still_url'];
		$state = $row_item['state'];
		$filename = $CONFIG ['server']['root'] . $CONFIG [ 'cam' ] [ 'uploadPath' ] . $row_item['url'] . '.jpg';
		$thumb_filename = $CONFIG ['server']['root'] . $CONFIG [ 'cam' ] [ 'uploadPath' ] . 'thumb_' . $row_item['url'] . '.jpg';
		$id = $row_item['id'];
		
		//try to get a snapshot
		$cmd = "wget -t 1 -O '$filename' -T 3 $stil_url?resolution=$resolution";
		//file_put_contents('log/cam_job.log', 'Download: ' . date('Y-m-d H:i:s') . ' - ' . $cmd . "\n", FILE_APPEND);
		system($cmd,$o);
		
		if($o != 0){
                        $params = array(); 
			$invalid++;
//			$query = "UPDATE cms_webcams SET state=2 WHERE id='$id'";
			$query = "UPDATE cms_webcams SET state=2 WHERE id=:Id";
                        $params[] = array(  "key" => ":Id",
                                            "value" =>$id);
                        $select = $dbConn->prepare($query);
                        PDO_BIND_PARAM($select,$params);
                        $res    = $select->execute();
//			db_query($query);
		}else if($state != '1'){
                        $params = array(); 
			$came_back++;
//			$query = "UPDATE cms_webcams SET state=1 WHERE id='$id'";
			$query = "UPDATE cms_webcams SET state=1 WHERE id='$id'";
                        $params[] = array(  "key" => ":Id",
                                            "value" =>$id);
                        $select = $dbConn->prepare($query);
                        PDO_BIND_PARAM($select,$params);
                        $res    = $select->execute();
//			db_query($query);
		}
		
		if($o == 0){
			//create the thumb if successful
			//file_put_contents('log/cam_job.log', 'Download Done - Creating Thumb - ' . date('Y-m-d H:i:s') .  "\n", FILE_APPEND);
			createThumbnailFromImage($filename, $thumb_filename);
		}else{
			//file_put_contents('log/cam_job.log', 'Download Failed - ' . date('Y-m-d H:i:s') .  "\n", FILE_APPEND);
			createThumbnailFromImage($filename, $thumb_filename);
		}		
		$n++;		
		//if($n == 5) break;
	}
	//file_put_contents('log/cam_job.log', 'Finished Cam Job: ' . date('Y-m-d H:i:s') . ' - ' . $n . ' Cams ' . $invalid . ' Invalid ' . $came_back . ' Cameback ' . "\n" , FILE_APPEND);
	unlink('pid/cam_job.pid');
