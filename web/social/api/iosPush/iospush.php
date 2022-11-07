<?php

function bagPushToMobile($user_id, $bag_id, $bag_name, $newmessage){
    global $CONFIG;
    global $dbConn;
    $params = array();  
//    $query = db_query("SELECT cmt.ssid, cmt.tokenid, cmt.platform, cmt.ap_type
//                       FROM cms_mobile_token AS cmt
//                       INNER JOIN cms_tubers AS ct on ct.uid = cmt.ssid
//                       WHERE cmt.ap_type = 'bag' AND ct.user_id = $user_id
//                      ");
    $query = "SELECT cmt.ssid, cmt.tokenid, cmt.platform, cmt.ap_type
                       FROM cms_mobile_token AS cmt
                       INNER JOIN cms_tubers AS ct on ct.uid = cmt.ssid
                       WHERE cmt.ap_type = 'bag' AND ct.user_id = :User_id
                      ";
    $params[] = array(  "key" => ":User_id",
                        "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $response = '';
    $row = $select->fetchAll();
//    while($row = db_fetch_array($query))
    foreach($row as $row_item){
        file_put_contents('log/chat_iospush.log', print_r($row_item,true), FILE_APPEND);
        if($row_item['platform'] == 0)
        {
            $tokenid = $row_item['tokenid'];

            // Put your device token here (without spaces):
            $deviceToken = $tokenid;//'18024ea81893d6570169ae0ac13eaedb23333ef3603504c53d4ca6f578eb0502';

            // Put your private key's passphrase here:
            $passphrase = '123456';

            // Put your alert message here:
            $message = $newmessage;//'My first push notification!';

            ////////////////////////////////////////////////////////////////////////////////

            $ctx = stream_context_create();
            stream_context_set_option($ctx, 'ssl', 'local_cert', $CONFIG['server']['root'] . 'api/iosPush/tt.pem');
            stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

            // Open a connection to the APNS server
            $fp = stream_socket_client(
                    'ssl://gateway.sandbox.push.apple.com:2195', $err,
                    $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

            if (!$fp){
                    printf("Failed to connect: $err $errstr" . PHP_EOL);
                    return false;
            }

            echo 'Connected to APNS' . PHP_EOL;

            // Create the payload body
            $body['aps'] = array(
                    'alert' => $newmessage,
                    'sound' => 'default'
                    );

            // Encode the payload as JSON
            $payload = json_encode($body);

            // Build the binary notification
            $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

            // Send it to the server
            $result = fwrite($fp, $msg, strlen($msg));

            if (!$result)
                    echo 'Message not delivered' . PHP_EOL;
            else
                    echo 'Message successfully delivered' . PHP_EOL;

            // Close the connection to the server
            fclose($fp);
        }
        else if($row_item['platform'] == 1)
        {
            $url = 'https://android.googleapis.com/gcm/send';
            $serverApiKey = "AIzaSyDMz06eGwud6UWrMGM4IllZGsjuaaI64D0";

            $reg = $row_item['tokenid'];
            $headers = array(
                'Content-Type:application/json',
                'Authorization:key=' . $serverApiKey
            );
            $ssid = $row_item['ssid'];
            $data = array(
               'registration_ids' => array($reg),
               'data' => array(
                   'msg' => $newmessage,
                   'bag_id' => $bag_id,
                   'bag_name' => $bag_name,
                   'ssid' => $ssid,
                   'ap_type' => $row['ap_type']
               )
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            if ($headers)
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            $response = curl_exec($ch);
            curl_close($ch);
            $resp_decoded = json_decode($response, true);
            if($resp_decoded['canonical_ids'] > 0){
                $result = $resp_decoded['results'][0];
                if(isset($result['registration_id']))
                {
                    UpdateToken($user_id, $ssid, $reg);
                }
            }
        }
    }
    return $response;
}

function UpdateToken($user_id, $ssid){
    
}

function iosPush($ssid, $newmessage)
{
	global $CONFIG;
	global $dbConn;
	$params = array();  
//	$query = db_query("SELECT * FROM cms_mobile_token WHERE ssid='".$ssid."'");
	$query = "SELECT * FROM cms_mobile_token WHERE ssid=:Ssid";
	$params[] = array(  "key" => ":Ssid",
                            "value" =>$ssid);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
	$row = $select->fetchAll();
//	while($row = db_fetch_array($query))
        foreach($row as $row_item){
		file_put_contents('log/chat_iospush.log',print_r($row_item,true),FILE_APPEND);
		if($row_item['platform'] == 0)
		{
			$tokenid = $row_item['tokenid'];
			
			// Put your device token here (without spaces):
			$deviceToken = $tokenid;//'18024ea81893d6570169ae0ac13eaedb23333ef3603504c53d4ca6f578eb0502';
			
			// Put your private key's passphrase here:
			$passphrase = '123456';
			
			// Put your alert message here:
			$message = $newmessage;//'My first push notification!';
			
			////////////////////////////////////////////////////////////////////////////////
			
			$ctx = stream_context_create();
			stream_context_set_option($ctx, 'ssl', 'local_cert', $CONFIG['server']['root'] . 'api/iosPush/tt.pem');
			stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
			
			// Open a connection to the APNS server
			$fp = stream_socket_client(
				'ssl://gateway.sandbox.push.apple.com:2195', $err,
				$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
			
			if (!$fp){
				printf("Failed to connect: $err $errstr" . PHP_EOL);
				return false;
			}
			
			echo 'Connected to APNS' . PHP_EOL;
			
			// Create the payload body
			$body['aps'] = array(
				'alert' => $newmessage,
				'sound' => 'default'
				);
			
			// Encode the payload as JSON
			$payload = json_encode($body);
			
			// Build the binary notification
			$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
			
			// Send it to the server
			$result = fwrite($fp, $msg, strlen($msg));
			
			if (!$result)
				echo 'Message not delivered' . PHP_EOL;
			else
				echo 'Message successfully delivered' . PHP_EOL;
			
			// Close the connection to the server
			fclose($fp);
		}
		else if($row_item['platform'] == 1)
		{
			 $url = 'https://android.googleapis.com/gcm/send';
			 $serverApiKey = "AIzaSyDMz06eGwud6UWrMGM4IllZGsjuaaI64D0";
			 
			 $reg = $row_item['tokenid'];
			$headers = array(
			 'Content-Type:application/json',
			 'Authorization:key=' . $serverApiKey
			 );
			
			 $data = array(
			 'registration_ids' => array($reg)
			 , 'data' => array(
			 'type' => 'New'
			 , 'title' => 'GCM'
			 , 'msg' => $newmessage
			 , 'url' => 'http://androidmyway.wordpress.com'
			 )
			 );
			
			 $ch = curl_init();
			 curl_setopt($ch, CURLOPT_URL, $url);
			 if ($headers)
			 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			 curl_setopt($ch, CURLOPT_POST, true);
			 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			
			 $response = curl_exec($ch);
			file_put_contents('log/chat_iospush.log',print_r($data,true) . "r=". $response,FILE_APPEND);
			curl_close($ch);

		}
	}
	

}