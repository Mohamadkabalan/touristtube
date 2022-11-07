<?php

$path = "../";

$bootOptions = array("loadDb" => 1, "requireLogin" => 0, "loadLocation" => 0);
include_once ($path."inc/common.php");
include_once ($path."inc/bootstrap.php");
include_once ($path."inc/functions/users.php");
include_once ($path."inc/functions/videos.php");

global $dbConn;

while(true)
{
    try
	{
        $query = "SELECT * FROM cms_push_notification WHERE processed = 0 AND tries < 10";
		
        $select = $dbConn->prepare($query);
		
        $res = $select->execute();
        $rowCount = $select->rowCount();
        $rows = $select->fetchAll(PDO::FETCH_ASSOC);
		
        if($res && $rowCount)
		{
            foreach($rows as $row)
			{
				$sent = 0;
				
                if (mobile_push($row))
				{
					$sent = 1;
				}
				
				$update_query = "UPDATE cms_push_notification SET processed = 1, sent = :sent, tries = tries + 1 WHERE id = :id";
				
                $params = array();
                $params[] = array('key' => ':id', 'value' => $row['id']);
				$params[] = array('key' => ':sent', 'value' => $sent);
				
                $update_statement = $dbConn->prepare($update_query);
                PDO_BIND_PARAM($update_statement, $params);
                $update_statement->execute();
				
                file_put_contents('log/push_notifications.log', "\ncms_push_notification(id:: ".$row['id'].', action_user_id:: '.$row['action_user_id'].', user_id:: '.$row['user_id'].', action_type:: '.$row['action_type'].', entity_id:: '.$row['entity_id'].', entity_type:: '.$row['entity_type'].'):: msg:: '.$row['msg'], FILE_APPEND);
            }
        }
    }
	catch(Exception $ex)
	{
        file_put_contents('log/push_notifications.log', "\n".print_r($ex->getMessage(), true), FILE_APPEND);
    }
	
	sleep(5);
}

function getUserStatus($user_id)
{
    global $dbConn;
	
    $query = "SELECT status FROM cms_chat_status WHERE user_id = :user_id";
	
	$params = array();
    $params[] = array('key' => ':user_id', 'value' => $user_id);
	
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select, $params);
	
    $res = $select->execute();
    $rowCount = $select->rowCount();
    //$ret = db_query($query);
	
	$status = 0;
	
	if ($res && $rowCount)
	{
        $row = $select->fetch();
		
        $status = $row['status'];
    }
	
	return $status;
}

function mobile_push($push_data)
{
	if (in_array($push_data['action_type'], array(SOCIAL_ACTION_CHAT, SOCIAL_ACTION_LOCATION_SHARE, SOCIAL_ACTION_VOICE_MESSAGE, SOCIAL_ACTION_FILE_SHARE, SOCIAL_ACTION_CALL, SOCIAL_ACTION_DISCONNECT_CALL)))
	{
		$action_user = getUserInfo($push_data['action_user_id']);
		$action_user_name = returnUserDisplayName($action_user);
		$profile_pic = ReturnLink('media/tubers/'.$action_user['profile_Pic']);
		$chat_status = getUserStatus($push_data['action_user_id']);
		
		if($push_data['action_type'] == SOCIAL_ACTION_CHAT)
		{
			$push_data['msg'] = $action_user_name.": ".$push_data['msg'];
		}
	}
	
	$media_type = '';
	
	if($push_data['entity_type'] == SOCIAL_ENTITY_MEDIA)
	{
		$media_info = getVideoInfo($push_data['entity_id']);
		$media_type = $media_info['image_video'] == "v" ? 'video' : 'image';
	}
	
	global $dbConn;
	
	$query = "SELECT * FROM cms_mobile_token WHERE userid = :user_id";
	
	$params = array();
	$params[] = array('key' => ':user_id', 'value' => $push_data['user_id']);
	
	$mobile_token_statement = $dbConn->prepare($query);
	PDO_BIND_PARAM($mobile_token_statement, $params);
	$res = $mobile_token_statement->execute();
	$rows = $mobile_token_statement->fetchAll(PDO::FETCH_ASSOC);
	
	$tokens_ios = array();
	$tokens_android = array();
	
	foreach($rows as $row_item)
	{
		if ($row_item['platform'])
			$tokens_android[] = $row_item['tokenid'];
		else
			$tokens_ios[] = $row_item['tokenid'];
	}
	
	if($tokens_ios)
	{
		$deviceToken = $row_item['tokenid'];
		
		// $apnsCert = '/home/ffmpeg/www/services/TT-push-devCertificates.pem';
		$apnsCert = '/home/ffmpeg/www/services/apns_prod_cert.pem';
		
		$apnsHost = 'gateway.sandbox.push.apple.com';
		// $apnsHost = 'gateway.push.apple.com';
		$apnsPort = 2195;
		
		$payload_arr = array(
					'aps' => array(
						'data' => array(
							$push_data['user_id'], // target_user_id 0
							$push_data['action_user_id'], // action_user_id 1
							$push_data['action_type'], // action_type 2
							$push_data['entity_id'], // entity_id 3
							$push_data['entity_type'], // entity_type 4
							$media_type // media_type 5
						),
						'type' => 1,
						'alert' => $push_data['msg'],
						'sound' => 'default',
						'content-available' => 1
					 )
				);
		
		if (in_array($push_data['action_type'], array(SOCIAL_ACTION_CHAT, SOCIAL_ACTION_LOCATION_SHARE, SOCIAL_ACTION_VOICE_MESSAGE, SOCIAL_ACTION_FILE_SHARE, SOCIAL_ACTION_CALL, SOCIAL_ACTION_DISCONNECT_CALL)))
		{
			$payload_arr['aps']['data'][6] = $action_user_name; // action_user_name 6
			$payload_arr['aps']['data'][7] = $profile_pic; // action_user_pic 7
			$payload_arr['aps']['data'][8] = $chat_status; // action_user_status 8
			
			if (in_array($push_data['action_type'], array(SOCIAL_ACTION_LOCATION_SHARE, SOCIAL_ACTION_VOICE_MESSAGE, SOCIAL_ACTION_FILE_SHARE)))
			{
				$payload_arr['aps']['alert'] = sprintf($push_data['msg'], $action_user_name);
			}
		}
		
		$payload = json_encode($payload_arr);
		$streamContext = stream_context_create();
		stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
		// stream_context_set_option($streamContext, 'ssl', 'passphrase', 'cel11:tootle');
		$apns = stream_socket_client('ssl://'.$apnsHost.':'.$apnsPort, $error, $errorString, 30, STREAM_CLIENT_CONNECT, $streamContext);
		
		if($apns === false)
			return false;
		
		stream_set_blocking($apns, 0);
		
		$apnsMessage = chr(1)
			. pack('N', 88)
			. pack('N', time() + 2419200) // ( 28*(24*60*60)))
			. chr(0) . chr(32) // Device token
			. pack('H*', str_replace(' ', '', $deviceToken))
			. chr(0).chr(strlen($payload))
			. $payload.$payload;
		
		fwrite($apns, $apnsMessage);
		usleep(500000);
		// checkAppleResponse($apns);
		fclose($apns);
		
		return true;
	}
	
	if($tokens_android)
	{
		$url = 'https://android.googleapis.com/gcm/send';
		// $serverApiKey = "AIzaSyASUpN4IjTq-2_R2MHG3sdDCfYsEqYAVXI";
		// $serverApiKey = "AIzaSyAklNf4JWuFuAlKtpO_EWeDyMZqSnFCPMg";
		// $serverApiKey = "AIzaSyDMz06eGwud6UWrMGM4IllZGsjuaaI64D0";
		// $serverApiKey = "AIzaSyCAuWdf-AY2DswOjESOHFItX6GC22HV5Po";
		// $serverApiKey = "AIzaSyBg4fJhF2F7fP0mcDqmeoCJgFOJUPhpIp8";
		// $serverApiKey = "AIzaSyCAuWdf-AY2DswOjESOHFItX6GC22HV5Po";
		$serverApiKey = "AIzaSyB48ayo-FuXKcR3fLBVma2tIUbiLtQfJsw";
		
		// $reg = $row_item['tokenid'];
		$data = array(
		   'registration_ids' => $tokens_android,
		   'data' => array(
			   'target_user_id' => $push_data['user_id'],
			   'action_user_id' => $push_data['action_user_id'],
			   'action_type' => $push_data['action_type'],
			   'entity_id' => $push_data['entity_id'],
			   'entity_type' => $push_data['entity_type'],
			   'media_type' => $media_type,
			   'msg' => $push_data['msg']
		   )
		);
	   
		if (in_array($push_data['action_type'], array(SOCIAL_ACTION_CHAT, SOCIAL_ACTION_LOCATION_SHARE, SOCIAL_ACTION_VOICE_MESSAGE, SOCIAL_ACTION_FILE_SHARE, SOCIAL_ACTION_CALL, SOCIAL_ACTION_DISCONNECT_CALL)))
		{
			$data['data']['action_user_name'] = $action_user_name;
			$data['data']['action_user_pic'] = $profile_pic;
			$data['data']['action_user_status'] = $chat_status;
			
			if (in_array($push_data['action_type'], array(SOCIAL_ACTION_LOCATION_SHARE, SOCIAL_ACTION_VOICE_MESSAGE, SOCIAL_ACTION_FILE_SHARE)))
			{
				$data['data']['msg'] = sprintf($push_data['msg'], $action_user_name);
			}
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:key='.$serverApiKey));
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		
		$response = curl_exec($ch);
		curl_close($ch);
		$json_response = json_decode($response);
		// file_put_contents('log/push_notifications.log', print_r($json_response, true), FILE_APPEND);
		
		if($json_response->canonical_ids > 0)
		{
			$res_results = $json_response->results;
			
			foreach($res_results as $token_index => $res)
			{
				if($res->registration_id)
				{
					$token_to_delete = $tokens_android[$token_index];
					$token_delete_query = "DELETE FROM cms_mobile_token WHERE tokenid = :token_id";
					
					$params = array();
					$params[] = array('key' => ':token_id', 'value' => $token_to_delete);
					
					$token_delete_statement = $dbConn->prepare($token_delete_query);
					PDO_BIND_PARAM($token_delete_statement, $params);
					$token_delete_statement->execute();
				}
			}
		}
		
		file_put_contents('log/push_notifications.log', "\n".print_r($data, true)."r=". $response, FILE_APPEND);
	}
}