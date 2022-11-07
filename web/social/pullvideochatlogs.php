<?php
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	$path = "";
    $bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
    include_once ( $path . "inc/functions/users.php" );
	
	$item_per_page = 5;
	
	
	// update the view status when user is on-line starts here
	global $dbConn;
	$params = array();  
	$user_ID = userGetID();
//	$chatviewlogstatus = "UPDATE `cms_chat_log` SET `viewed` = '1' WHERE `to_user` ='$user_ID' AND `from_user`= '".$_POST['sender']."';";
	$chatviewlogstatus = "UPDATE `cms_chat_log` SET `viewed` = '1' WHERE `to_user` =:User_ID AND `from_user`= :Sender;";
//	$result = db_query($chatviewlogstatus);
	$params[] = array(  "key" => ":User_ID",
                            "value" =>$user_ID);
	$params[] = array(  "key" => ":Sender",
                            "value" =>$request->request->get('sender', ''));
	$select = $dbConn->prepare($chatviewlogstatus);
	PDO_BIND_PARAM($select,$params);
	$result    = $select->execute();

	
	
	/**
	* gets the total count of the chat logs of a sender and receiver
	* @param integer $user_id the user's id
	* */
	
	
	function chatGetUserLogsCount($userid=0,$from_userid=0){
                global $dbConn;
                $params = array();  

//		$query = "SELECT COUNT(id) as cnt FROM `cms_chat_log` where `to_user` IN ('$userid','$from_userid') AND `from_user` IN ('$userid','$from_userid') AND `viewed` IN ('0','1')";
		$query = "SELECT COUNT(id) as cnt FROM `cms_chat_log` where `to_user` IN (:Userid,:From_userid) AND `from_user` IN (:Userid,:From_userid) AND from_user <> to_user AND from_mobile = 0 AND location_share = 0";
                $params[] = array(  "key" => ":Userid",
                                    "value" =>$userid);
                $params[] = array(  "key" => ":From_userid",
                                    "value" =>$from_userid);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $ret    = $select->execute();
//		$ret = db_query($query);
//		$count = mysql_fetch_array($ret); //total records
                $count = $select->fetch();
		return $count['cnt'];
	}	
	

	
	/**
	* gets the chat logs of a user
	* @param integer $user_id the user's id
	* */
	
	function chatGetUserLogs($userid=0,$from_userid=0,$page_number=0, $item_per_page){
   
		//get current starting point of records
		//$position = ($page_number * $item_per_page);

                global $dbConn;
                $params = array(); 
//		$query = "(SELECT `id` , `from_user` , `to_user` , `msg_txt` ,`msg_ts`,`viewed` FROM `cms_chat_log` where `to_user` IN ('$userid','$from_userid') AND `from_user` IN ('$userid','$from_userid') AND `viewed` IN ('0','1') ORDER BY `id` DESC LIMIT $page_number, $item_per_page) ORDER BY id ASC";
		$query = "(SELECT `id` , `from_user` , `to_user` , `msg_txt` ,`msg_ts`,`viewed` FROM `cms_chat_log` where `to_user` IN (:Userid,:From_userid) AND `from_user` IN (:Userid,:From_userid) AND from_user <> to_user AND from_mobile = 0 AND location_share = 0 ORDER BY `id` DESC LIMIT $page_number, $item_per_page) ORDER BY id ASC";
                $params[] = array(  "key" => ":Userid",
                                    "value" =>$userid);
                $params[] = array(  "key" => ":From_userid",
                                    "value" =>$from_userid);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res    = $select->execute();
		$ret_arr = array();
//		$ret = db_query($query);

                $ret    = $select->rowCount();
//		if ( !$ret || db_num_rows( $ret ) == 0 ) return array();
		if ( !$res || $ret == 0 ) return array();
                $row = $select->fetchAll();
//		while($row = db_fetch_array($ret)){
                foreach($row as $row_item){
			$new_row = $row_item;
			$ret_arr[] = $new_row;
		}
		return $ret_arr;
	}	
	
	
	//sanitize post value
        $page_post = $request->request->get('page', '');
//	$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	$page_number = filter_var($page_post, FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	
        $receiver_post = $request->request->get('receiver', '');
        $sender_post = $request->request->get('sender', '');
//	$logs = chatGetUserLogs($_POST['receiver'],$_POST['sender'],$page_number,5);
	$logs = chatGetUserLogs($receiver_post,$sender_post,$page_number,5);
	
	//get logs here
//	$totallogs = chatGetUserLogsCount($_POST['receiver'],$_POST['sender']);
	$totallogs = chatGetUserLogsCount($receiver_post,$sender_post);
	
	//break total records into pages
	$total_pages = ceil($totallogs/$item_per_page); 
        $i = 0;
        foreach($logs as $log){
            $userInfo= getUserInfo($log['from_user']);
            $userName= returnUserDisplayName( $userInfo , array('max_length' => 12) );
//            $UTC = new DateTimeZone("UTC");
//            $date = new DateTime( $log['msg_ts'], $UTC );
            //$logs[$i]['utc_time'] = $date->format('Y-m-d H:i:s');
            //$logs[$i]['utc_time'] = gmdate('r', strtotime($log['msg_ts']));
            $logs[$i]['utc_time'] = gmdate("r", strtotime($log['msg_ts']) );
            $logs[$i]['userName'] = $userName;
            $i++;
        }
        
        echo json_encode(array('logs' => $logs, 'total_count' => $totallogs, 'total_pages' => $total_pages));
        exit();
	if(count($logs) >0){
		echo '<input type="hidden" value="'.$total_pages.'" id="total_pages"><input type="hidden" value="'.$totallogs.'" id="total_count"> ';
		foreach ($logs as $log){
		$userInfo= getUserInfo($log['from_user']);
		$userName= returnUserDisplayName( $userInfo , array('max_length' => 12) );
		
	?>
		<div class="row_outer"> <div class="row"><div><div class="user fl"><div class="user_inner"><?php echo $userName;?></div></div><div class="time fr"><span class="bg fl"></span><div class="time_txt fl"> <?php echo date('H:i:s',strtotime($log['msg_ts']));?> </div><div class="clearfix"></div></div><div class="clearfix"></div></div><div class="text"><div class="text_inner"><?php echo $log['msg_txt'];?></div></div></div><div class="row_splitter"></div></div>

	<?php } }
	?>
<?php exit; ?>
