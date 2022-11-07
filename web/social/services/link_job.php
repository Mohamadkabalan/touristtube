<?php

	$path = "../";

    $bootOptions = array("loadDb" => 1, "requireLogin" => 0, "loadLocation" => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
	include_once ( $path . "inc/functions/videos.php" );
	
	function sitemapLog($str){
		//file_put_contents('log/link.log', date('Y-m-d H:i:s') . ' ' . $str . PHP_EOL , FILE_APPEND );
	}
	
	if( file_exists('pid/link_job.pid') ){
		sitemapLog('Couldnt start link job: ' . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
		exit(0);
	}
	
	file_put_contents('pid/link_job.pid', 'working');
	
	$limit = 1000;
	$page = 0;
	$skip = $limit*$page;
	
	global $dbConn;
	$params  = array();  
	$params2 = array();  
	$params3 = array();  
	//get 1000 records at time
//	$query = "SELECT id,description FROM cms_videos WHERE link_ts < (NOW() - INTERVAL 24 HOUR) LIMIT $limit";
	$query = "SELECT id,description FROM cms_videos WHERE link_ts < (NOW() - INTERVAL 24 HOUR) LIMIT :Limit";
	$params[] = array(  "key" => ":Limit",
                            "value" =>$limit,
                            "type" =>"::PARAM_INT");
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
//	$res = db_query($query);
//	$done = !$res || (db_num_rows($res) == 0);
        
	$done = !$res || ($ret == 0);
	
	$updated = 0;
	while(!$done){  
            $params3 = array();
                $row = $select->fetchAll();
//		while($row = db_fetch_row($res)){
                foreach($row as $row_item){ 
                        $params2 = array();
			$id = $row_item[0];
			$desc = $row_item[1];
			$linked_desc = db_sanitize( seoHyperlinkText($desc) );
//			$query = "UPDATE cms_videos SET link_ts=NOW(), description_linked='$linked_desc' WHERE id='$id'";
                        $query = "UPDATE cms_videos SET link_ts=NOW(), description_linked=:Linked_desc WHERE id=:Id";
                        $params2[] = array(  "key" => ":Linked_desc",
                                            "value" =>$linked_desc);
                        $params2[] = array(  "key" => ":Id",
                                            "value" =>$id);
                        $select2 = $dbConn->prepare($query);
                        PDO_BIND_PARAM($select2,$params2);
                        $res2    = $select2->execute();
//			db_query($query);
			$updated++;
		}
		$page++;
		$skip = $limit*$page;
//		$query = "SELECT id,description FROM cms_videos WHERE link_ts < (NOW() - INTERVAL 24 HOUR) LIMIT $limit";
		$query = "SELECT id,description FROM cms_videos WHERE link_ts < (NOW() - INTERVAL 24 HOUR) LIMIT :Limit";
                $params3[] = array(  "key" => ":Limit",
                                     "value" =>$limit,
                                     "type" =>"::PARAM_INT");
                $select3 = $dbConn->prepare($query);
                PDO_BIND_PARAM($select3,$params3);
                $res3    = $select3->execute();

                $ret3    = $select3->rowCount();

//		$res = db_query($query);
		$done = !$res3 || ($ret3 == 0);
	}
	
	sitemapLog("Updated $updated records");
	unlink('pid/link_job.pid');