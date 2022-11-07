<?php



	define('CHAT_STATUS_OFFLINE',0);
	define('CHAT_STATUS_ONLINE',1);
	define('CHAT_STATUS_APPEAROFFLINE',100);
	define('CHAT_STATUS_AWAY',2);
	define('CHAT_STATUS_BUSY',3);
	
	
	/**
	 * check if the status is valid
	 * @param integer $status the status to check
	 * @return boolean valid or not
	 */
	function chatStatusValid($status){
		$all_status = array(CHAT_STATUS_OFFLINE, CHAT_STATUS_ONLINE, CHAT_STATUS_APPEAROFFLINE, CHAT_STATUS_AWAY, CHAT_STATUS_BUSY);
		return in_array($status,$all_status);
	}

	/**
	 * logs a chat message
	 * @param integer $from from user id
	 * @param integer $to to user id
	 * @param string $msg the message
	 * @param integer $from_tz the sender's timezone in minutes offset
	 * @return integer|false the chat log id or false if fail
	 */
	function chatLogMsg($from,$to,$msg,$delivered,$from_tz,$client_ts,$client_uid){
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
            global $dbConn;
            $params  = array(); 
            $params2 = array(); 
		if($delivered == 0){
			$_delivered = 'NULL';
		}else{
			$_delivered = 'NOW()';
		}
//		$query = "INSERT INTO cms_chat_log (from_user,to_user,msg_txt,msg_ts,delivered_ts,from_tz,client_ts,client_uid) VALUES ($from,$to,'$msg',NOW(),$_delivered,$from_tz,'$client_ts','$client_uid')";
		$query = "INSERT INTO cms_chat_log (from_user,to_user,msg_txt,msg_ts,delivered_ts,from_tz,client_ts,client_uid) VALUES (:From,:To,:Msg,NOW(),:Delivered,:From_tz,:Client_ts,:Client_uid)";
//		$ret = db_query($query);
                $params[] = array(  "key" => ":From",
                                    "value" =>$from);
                $params[] = array(  "key" => ":To",
                                    "value" =>$to);
                $params[] = array(  "key" => ":Msg",
                                    "value" =>$msg);
                $params[] = array(  "key" => ":Delivered",
                                    "value" =>$_delivered);
                $params[] = array(  "key" => ":From_tz",
                                    "value" =>$from_tz);
                $params[] = array(  "key" => ":Client_ts",
                                    "value" =>$client_ts);
                $params[] = array(  "key" => ":Client_uid",
                                    "value" =>$client_uid);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $ret    = $select->execute();
		
		if($ret){
//			$id = db_insert_id();
			$id = $dbConn->lastInsertId();
//			$query = "UPDATE cms_chat_status SET last_action=NOW() WHERE user_id='$from'";
			$query = "UPDATE cms_chat_status SET last_action=NOW() WHERE user_id=:From";
//			$ret2 = db_query($query);
                        $params2[] = array(  "key" => ":From",
                                             "value" =>$from);
                        $select = $dbConn->prepare($query);
                        PDO_BIND_PARAM($select,$params2);
                        $ret2   = $select->execute();
			return $id;
		}else{
			return false;
		}
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
	}
	
	/**
	 * a user disconnects from the chat
	 * @param integer $user_id the user's id
	 * @return boolean true|false if success|fail 
	 */
	function chatDisconnect($user_id){
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
            global $dbConn;
            $params = array();  
//		$query = "DELETE FROM cms_chat_status WHERE user_id='$user_id'";
//		return db_query($query);
		$query = "DELETE FROM cms_chat_status WHERE user_id=:User_id";
                $params[] = array(  "key" => ":User_id",
                                    "value" =>$user_id);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res    = $select->execute();
		return $res;
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
	}
	
	/**
	 * a users connects to the chat
	 * @param integer $user_id  the user's id
	 * @return boolean true|false if success|fail 
	 */
	function chatConnect($user_id){
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
	global $dbConn;
	$params = array(); 
		chatDisconnect($user_id);
//		$query = "INSERT INTO cms_chat_status (user_id,status,last_action) VALUES ('$user_id','1',NOW())";
//		return db_query($query);
		$query = "INSERT INTO cms_chat_status (user_id,status,last_action) VALUES (:User_id,'1',NOW())";
                $params[] = array(  "key" => ":User_id",
                                    "value" =>$user_id);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res    = $select->execute();
		return $res;
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
	}
	
	/**
	 * gets the chat status of a user
	 * @param integer $user_id the user's id
	 * @return integer 0 => offline, 1=> online , 2=> away
	 */
	function chatGetUserStatus($user_id){
		
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
	global $dbConn;
	$params = array(); 
//		$query = "SELECT * FROM cms_chat_status WHERE user_id='$user_id'";
		$query = "SELECT * FROM cms_chat_status WHERE user_id=:User_id";
//		$ret = db_query($query);
                $params[] = array(  "key" => ":User_id",
                                    "value" =>$user_id);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res    = $select->execute();

                $ret    = $select->rowCount();
//		if( !$ret || (db_num_rows($ret)==0) ){
		if( !$res || ($ret==0) ){
			return 0;
		}else{
//			$row = db_fetch_array($ret);
                        $row = $select->fetch();
			$status = $row['status'];
			$last_action = $row['last_action'];
			return $status;
		}
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
	}
	
	/**
	 * sets the users status
	 * @param integer $user_id
	 * @param integer $status
	 * @return boolen success|fail 
	 */
	function chatSetUserStatus($user_id,$status){
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
            global $dbConn;
            $params = array(); 
//		$query = "UPDATE cms_chat_status SET status='$status' WHERE user_id='$user_id'";
//		return db_query($query);
		$query = "UPDATE cms_chat_status SET status=:Status WHERE user_id=:User_id";
                $params[] = array(  "key" => ":Status",
                                    "value" =>$status);
                $params[] = array(  "key" => ":User_id",
                                    "value" =>$user_id);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res    = $select->execute();
		return $res;
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
	}
	
	/**
	 * gets undelivered message because a user was offline
	 * @param integer $user_id the user who was offline and has messages
	 * @return false | array the chat_log record of and undelivered message
	 */
	function chatGetOfflineMsg($user_id){
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
            global $dbConn;
            $params = array(); 
//		$query = "SELECT * FROM cms_chat_log WHERE to_user='$user_id' AND (delivered_ts IS NULL OR read_ts IS NULL) ORDER BY msg_ts ASC LIMIT 1";
		$query = "SELECT * FROM cms_chat_log WHERE to_user=:User_id AND (delivered_ts IS NULL OR read_ts IS NULL) ORDER BY msg_ts ASC LIMIT 1";
//		$ret = db_query($query);
                $params[] = array(  "key" => ":User_id",
                                    "value" =>$user_id);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $ret    = $select->rowCount();
                $res    = $select->execute();
		if( !$res || ($ret == 0) ){
			return false;
		}else{
//			$row = db_fetch_array($ret);
                        $row = $select->fetch();
			return $row;
		}
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
	}
	
	/**
	 * specifies that an offline message has been delivered
	 * @param array $row the chat_log record
	 * @return boolean true|false if success|fail 
	 */
	function chatOfflineMsgDelivered($id){
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
            global $dbConn;
            $params = array();  
//		$query = "UPDATE cms_chat_log SET delivered_ts=NOW(),read_ts=NOW() WHERE id='$id'";
//		return db_query($query);
		$query = "UPDATE cms_chat_log SET delivered_ts=NOW(),read_ts=NOW() WHERE id=:Id";
                $params[] = array(  "key" => ":Id",
                                    "value" =>$id);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res    = $select->execute();
		return $res;
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
	}

	/**
	 * specifies that an offline message has been delivered
	 * @param array $row the chat_log record
	 * @return boolean true|false if success|fail 
	 */
	function chatOfflineMsgRead($id){
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
	global $dbConn;
	$params = array();  
//		$query = "UPDATE cms_chat_log SET read_ts=NOW() WHERE id='$id'";
//		return db_query($query);
		$query = "UPDATE cms_chat_log SET read_ts=NOW() WHERE id=:Id";
                $params[] = array(  "key" => ":Id",
                                    "value" =>$id);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res    = $select->execute();
		return $res;
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
	}

	/**
	 * gets the chat log row
	 * @param integer $id the cms_chat_log record id
	 * @return false|array the chat log_record or false if not found
	 */
	function chatGetMsg($id){
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
	global $dbConn;
	$params = array();  
//		$query = "SELECT * FROM cms_chat_log SET WHERE id='$id'";
		$query = "SELECT * FROM cms_chat_log SET WHERE id=:Id";
                $params[] = array(  "key" => ":Id",
                                    "value" =>$id);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res    = $select->execute();
                $ret    = $select->rowCount();
//		$ret = db_query($query);
//		if( !$ret || db_num_rows($ret) == 0 ) return false;
		if( !$res || $ret == 0 ) return false;
//		return db_fetch_assoc($ret);
                $row = $select->fetch(PDO::FETCH_ASSOC);
		return $row;
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
	}
	
	/**
	 * resets the chat status table
	 * @return boolean true|false if success|fail 
	 */
	function chatStatusReset(){
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
            global $dbConn;
//		$query = "DELETE FROM cms_chat_status";
//		return db_query($query);
		$query = "DELETE FROM cms_chat_status";
                $select = $dbConn->prepare($query);
                $res    = $select->execute();
		return $res;
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
	}
	
	/**
	 * gets the chat log between 2 users between 2 times
	 * @param integer $user_id1
	 * @param integer $user_id2
	 * @param array $srch_options. options include<br/>
	 * <b>from_ts</b> the beginging timestamp. default null.<br/>
	 * <b>to_ts</b> the end timestamp.default null.<br/>
	 * <b>limit</b> limit of returned log. default 10<br/>
	 * <b>page</b> number of log page to skip. default 0<br/>
	 * <b>order</b> the order (a)scending (d)escending. default (a)<br/>
	 * <b>orderby</b> the cms_chat_log field to order by. default 'id'<br/>
	 */
	function chatGetLog($user_id1,$user_id2,$srch_options){
		
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <start>
            global $dbConn;
            $params = array();  
		$default_opts = array(
			'limit' => 10,
			'page' => 0,
			'orderby' => 'id',
			'order' => 'a',
			'from_ts' => null,
			'to_ts' => null
		);

		$options = array_merge($default_opts, $srch_options);
		
		$nlimit = intval($options['limit']);
		$skip = intval($options['page']) * $nlimit;
		
		$orderby = $options['orderby'];
		$order = ($options['order'] == 'a') ? 'ASC' : 'DESC';

		$where = '';
		
		if( !is_null($options['from_ts']) ){
			$from_mysql = date('Y-m-d', $options['from_ts'] );
			if($where != '') $where .= ' AND ';
//			$where .= " msg_ts >= '$from_mysql' ";
			$where .= " msg_ts >= :From_mysql ";
                        $params[] = array(  "key" => ":From_mysql",
                                            "value" =>$from_mysql);
		}
		
		if( !is_null($options['to_ts']) ){
			$to_mysql = date('Y-m-d', $options['to_ts'] );
			if($where != '') $where .= ' AND ';
//			$where .= " msg_ts >= '$to_mysql' ";
			$where .= " msg_ts >= :To_mysql ";
                        $params[] = array(  "key" => ":To_mysql",
                                            "value" =>$to_mysql);
		}
		
//		$query = "SELECT * FROM cms_chat_log WHERE ( (from_user=$user_id1 AND to_user=$user_id2) OR (from_user=$user_id2 AND to_user=$user_id1) ) $where ORDER BY $orderby $order LIMIT $skip, $nlimit";
		$query = "SELECT * FROM cms_chat_log WHERE ( (from_user=:User_id1 AND to_user=:User_id2) OR (from_user=:User_id2 AND to_user=:User_id1) ) $where ORDER BY $orderby $order LIMIT :Skip, :Nlimit";
                $params[] = array(  "key" => ":User_id1",
                                    "value" =>$user_id1);
                $params[] = array(  "key" => ":User_id2",
                                    "value" =>$user_id2);
                $params[] = array(  "key" => ":Skip",
                                    "value" =>$skip,
                                    "type" =>"::PARAM_INT");
                $params[] = array(  "key" => ":Nlimit",
                                    "value" =>$nlimit,
                                    "type" =>"::PARAM_INT");

//		$ret = db_query($query);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $ret    = $select->execute();
		$chat_log = array();

                $chat_log = $select->fetchAll();
//		while($row = db_fetch_array($ret))
//			$chat_log[] = $row_item;	
//                }
		return $chat_log;
//  Changed by Anthony Malak 18-05-2015 to PDO database
//  <end>
	}