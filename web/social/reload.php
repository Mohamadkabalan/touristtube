<?php
    
	$path = "";
	$bootOptions = array("loadDb" => 1, "loadLocation" => 1, "requireLogin" => 0);
    
	include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
	include_once ( $path . "inc/functions/users.php" );
	include_once ( $path . "inc/functions/videos.php" );
	
	
	function channelGetBorchureI(){
                global $dbConn;
                $params = array();  
		$query = "SELECT * FROM cms_brochures";
                $select = $dbConn->prepare($query);
                $res    = $select->execute();
                $ret    = $select->rowCount();
		
//		$ret = db_query($query);
//		if($ret && db_num_rows($ret)!=0 ){
		if($res && $ret!=0 ){
			$ret_arr = array();
                        $ret_arr = $select->fetchAll();
//			while($row = db_fetch_array($ret)){
//				$ret_arr[] = $row_item;
//			}
			return $ret_arr;
		}else{
			return false;
		}
	}
	
	function GetChannelBrochureI($name){
                global $dbConn;
                $params = array();
//		$query = "SELECT * FROM cms_channel_brochure WHERE name = '$name' limit 0,1";
		$query = "SELECT * FROM cms_channel_brochure WHERE name = :Name limit 0,1";
                $params[] = array(  "key" => ":Name",
                                    "value" =>$name);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res    = $select->execute();

                $ret    = $select->rowCount();
//		$ret = db_query($query);
//		if($ret && db_num_rows($ret)!=0 ){
		if($res && $ret!=0 ){
			$ret_arr = array();
                        $ret_arr = $select->fetchAll();
//			while($row = db_fetch_array($ret)){
//				$ret_arr[] = $row_item;
//			}
			return $ret_arr;
		}else{
			return false;
		}
	}
	
	$brochureStan = channelGetBorchureI();
	
	foreach( $brochureStan as $brochure ){
		/*
		$thumb = explode("/", $brochure['thumb']);
		$pdf = explode("/", $brochure['pdf']);
		
		$thumb = $thumb[sizeof($thumb)-1];
		$pdf = $pdf[sizeof($pdf)-1];
		
		echo $brochure['channelid'].'<br />';
		echo $brochure['title'].'<br />';
		echo $brochure['description'].'<br />';
		echo $thumb.'<br />';
		echo $pdf.'<hr />';
		
		AddChannelBrochure($brochure['channelid'],$brochure['title'],$thumb,$pdf);
		*/
		$selectedbrochure = GetChannelBrochureI($brochure['title']);
		$brID = $selectedbrochure[0]['channelid'];
		
		echo $brID.'<br />';
		echo htmlEntityDecode($selectedbrochure[0]['name']).' === '.htmlEntityDecode($brochure['title']).'<br />';
		echo $brochure['thumb'].'<br />';
		echo $brochure['pdf'].'<br />';
		
		if( !is_dir( 'media/channel/'.$brID.'/brochure' ) ) mkdir( 'media/channel/'.$brID.'/brochure'  );
		if( !is_dir( 'media/channel/'.$brID.'/brochure/thumb' ) ) mkdir( 'media/channel/'.$brID.'/brochure/thumb'  );
		
		$src = 'media/channel/'.$brochure['thumb'];
		$dest =  'media/channel/'.$brID.'/brochure/thumb/'.$selectedbrochure[0]['photo'];
		
		$src0 = 'media/channel/'.$brochure['pdf'];
		$dest0 =  'media/channel/'.$brID.'/brochure/'.$selectedbrochure[0]['pdf'];
		
		echo $src.'<br />';
		echo $dest.'<br />';
		
		echo $src0.'<br />';
		echo $dest0.'<hr />';
		
		//copy( 'media/channel/'.$brochure['thumb'], 'media/channel/'.$brID.'/brochure/thumb/'.$selectedbrochure[0]['photo'] );
		//copy( 'media/channel/'.$brochure['pdf'], 'media/channel/'.$brID.'/brochure/'.$selectedbrochure[0]['pdf'] );
		
	}
	
?>