<?php
	
	
//	session_id($_REQUEST['S']); 

//	session_id($submit_post_get['S']); 
//        session_start();
	$expath = '../';
	include_once("../heart.php");
	$submit_post_get = array_merge($request->query->all(),$request->request->all());
        header('Content-type: application/json');
        
	//$userid = $_SESSION['id'];
//	$ssid = $_REQUEST['S'];
//	$token = $_REQUEST['tokenid'];
//	$platform = $_REQUEST['platform'];
//        $app_type = $_REQUEST['apptype'];
	$ssid = $submit_post_get['S'];
        if($ssid == '')
	{
            echo json_encode(array('status' => 'fail'));
            exit();
	}
        $user_id = mobileIsLogged($ssid);
        if(!$user_id){
            echo json_encode(array('status' => 'fail'));
            exit();
        }
	$token = $submit_post_get['tokenid'];
	$platform = $submit_post_get['platform'];
	
	$result = setToken($ssid, $token, $platform, $user_id);
        
        $query = "SELECT from_user, to_user, msg_txt, location_share, voice_message, file_share FROM cms_chat_log WHERE to_user = :User_id AND status = 1";
        $params[] = array( "key" => ":User_id",
                            "value" =>$user_id);
        $select = $dbConn->prepare($query);
        PDO_BIND_PARAM($select,$params);
        $res    = $select->execute();
        $ret_arr    = $select->fetchAll(PDO::FETCH_ASSOC);
        foreach($ret_arr as $ret_item){
            //chat message 21
            //location share 23
            //voice message 24
            //file share 25
            $action_type = 21;
            $notification_msg = $ret_item['msg_txt'];
            if($ret_item['location_share']){
                $action_type = 23;
                $notification_msg = "%s shared a location with you";
            }
            else if($ret_item['voice_message']){
                $action_type = 24;
                $notification_msg = "%s sent you a voice message";
            }
            else if($ret_item['file_share']){
                $action_type = 25;
                $notification_msg = "%s shared a file with you";
            }
            addPushNotification($action_type, $ret_item['to_user'], $ret_item['from_user'], 0, 0, 0, $notification_msg);
	    
        }
	
	echo json_encode(array('status' => $result));