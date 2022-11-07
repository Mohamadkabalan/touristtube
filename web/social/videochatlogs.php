<?php

	$path = "";
    $bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
    include_once ( $path . "inc/functions/users.php" );
$submit_post_get = array_merge($request->query->all(),$request->request->all());
	
	// update the logs here
	global $dbConn;
	$params = array();  
//    $logsupdate = "INSERT INTO `cms_chat_log` (`from_user`, `to_user`, `msg_txt`, `client_ts`,  `client_uid`, `from_tz`,`viewed`) VALUES ( '".$_REQUEST['senderid']."', '".$_REQUEST['receiverid']."', '".$_REQUEST['msg']."', '".$_REQUEST['client_ts']."', '".$_REQUEST['senderid']."', '000','".$_REQUEST['viewed']."')";
    $logsupdate = "INSERT INTO `cms_chat_log` (`from_user`, `to_user`, `msg_txt`, `client_ts`,  `client_uid`, `from_tz`,`viewed`) VALUES ( :Senderid,:Receiverid,:Msg,:Client_ts,:Senderid2, '000',:Viewed)";
    $params[] = array(  "key" => ":Senderid",
//                        "value" =>$_REQUEST['senderid']);
                        "value" =>$submit_post_get['senderid']);
    $params[] = array(  "key" => ":Receiverid",
//                        "value" =>$_REQUEST['receiverid']);
                        "value" =>$submit_post_get['receiverid']);
    $params[] = array(  "key" => ":Msg",
//                        "value" =>$_REQUEST['msg']);
                        "value" =>$submit_post_get['msg']);
    $params[] = array(  "key" => ":Client_ts",
//                        "value" =>$_REQUEST['client_ts']);
                        "value" =>$submit_post_get['client_ts']);
    $params[] = array(  "key" => ":Senderid2",
//                        "value" =>$_REQUEST['senderid']);
                        "value" =>$submit_post_get['senderid']);
    $params[] = array(  "key" => ":Viewed",
//                        "value" =>$_REQUEST['viewed']);
                        "value" =>$submit_post_get['viewed']);
    $select = $dbConn->prepare($logsupdate);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

//	if(db_query($logsupdate)) // test
	if($res)
	    echo true;
    else
	    echo false;
	exit;
?>
