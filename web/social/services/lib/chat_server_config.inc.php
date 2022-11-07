<?php


/**
 * Holds the config variables of the server
 * @author Halim 
 */
class ServerConfig {

	/**
	 * IP to listen on
	 * @var string
	 */
	
	/**
	 * The admin key required by the socket servre
	 * @var string 
	 */
	private static $admin_key = '14dmg8dndjs';

	/**
	 * gets the IP to listen on
	 * @return string IP in string format
	 */
	public static function getListenIP() {
		$file = file('lib/chat_servers_list.conf');
		$server = $file[rand(0, count($file) - 1)];
		list($ip,$port) = explode(':',$server);
		return $ip;
	}

	/**
	 * gets the Port to listen on
	 * @return string Port to listen on in string format 
	 */
	public static function getListenPort() {
		$file = file('lib/chat_servers_list.conf');
		$server = $file[rand(0, count($file) - 1)];
		list($ip,$port) = explode(':',$server);
		return $port;
	}
	
	/**
	 * return a chat server to connect to
	 * @return type
	 */
	public static function getChatServer(){
		if( PHP_SAPI != 'cli' ) $file = file('services/lib/chat_servers_list.conf');
		else $file = file('lib/chat_servers_list.conf');
		$lines = array();
		foreach($file as $line){
			//ignore invalid lines
			if($line[0]=='#') continue;
			if( strlen($line) < 15) continue;
			
			//only take actual servers
			$lines[] = trim($line, "\r\n");
		}
		$server = $lines[rand(0, count($lines) - 1)];
		//$server = $lines[0];
		return $server;
	}
	

	/**
	 * gets the admin key
	 * @return string the admin key of the server 
	 */
	public static function getAdminKey() {
		return self::$admin_key;
	}

}

?>
