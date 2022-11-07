<?php

require_once('common.config.php');
require_once('websocket.admin.php');
require_once('chat_server_config.inc.php');

/**
 * Manages the chat service
 * @author Halim
 */
class ChatServiceManager {
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<start>

	/**
	 * Starts the chat service  
	 */
//	public static function Start() {
//		if (PHP_OS == 'WINNT') {
//			//windows
//			$cwd = getcwd();
//			$cmd = PHP_PATH . ' -c ' . PHP_INI_PATH . " $cwd/chat_server.php";
//			echo $cmd;
//			$WshShell = new COM("WScript.Shell");
//			$WshShell->CurrentDirectory = $cwd;
//			$oExec = $WshShell->Run("$cmd", 0, false);
//			file_put_contents("service.pid", serialize($WshShell) );
//		} else {
//			//linux
//			$PID = shell_exec("nohup php $path 2> /dev/null & echo $!");
//			file_put_contents("service.pid", $PID);
//		}
//	}

	/**
	 * checks if the service is running
	 * @return boolean 
	 */
//	public static function isRunning() {
//
//		return file_exists('pid/service.pid');
//	}

	/**
	 * Stops the service 
	 */
//	public static function Stop() {
//		$data = array();
//		$data['op'] = 'stop';
//		$msg = WebSocketAdminMessage::create( json_encode($data) );
//		$client = new WebSocketAdminClient("ws://" . ServerConfig::getListenIP() . ':' . ServerConfig::getListenPort() . '/chat', ServerConfig::getAdminKey());
//		$client->open();
//		$client->sendMessage($msg);
//		@unlink('pid/service.pid');
//	}
	
//	public static function Query($data){
//		$_data['op'] = 'query';
//		$_data['data'] = $data;
//		$msg = WebSocketAdminMessage::create( json_encode($_data) );
//		$client = new WebSocketAdminClient("ws://" . ServerConfig::getListenIP() . ':' . ServerConfig::getListenPort() . '/chat', ServerConfig::getAdminKey());
//		$client->open();
//		$client->sendMessage($msg);
//	}

//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<end>
}