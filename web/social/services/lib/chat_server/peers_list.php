<?php
/**
 * holds the chat server peer list
 * @package chat_server
 */


/**
 * static class that holds the list of chat server peers
 */
class ChatServerPeersList{
	
	/**
	 * an array of chat peers
	 * @var array(string)
	 */
	private static $peers = array();
	
	/**
	 * a set of sockets
	 * @var WebSocketAdminClient[]
	 */
	private static $peerSockets = array();
	
	/**
	 * adds a peers to the 
	 * @param type $peer
	 */
	public static function addPeer($peer){
		if( in_array($peer, self::$peers) ) return;
		
		//add to peer strings
		self::$peers[] = $peer;
		
		$peerSockets[] = null;
	}
	
	/**
	 * gets this list of chat server peers
	 * @return array the list of peers
	 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<start>
//	public static function getPeers(){
//		return self::$peers;
//	}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<end>
	
	/**
	 * a set of sockets
	 * @return WebSocketAdminClient[]
	 */
	public static function getPeerSockets(){
		
		$i = 0;
		foreach(self::$peers as $peer){
			//open socket and add to peer sockets
			
			//open the socket if not previously opened
			if( !isset(self::$peerSockets[$i]) || is_null(self::$peerSockets[$i]) ){
				try{
					//try to pen it
					$newPeerSocket = new WebSocketAdminClient("ws://" . $peer . '/chat', ServerConfig::getAdminKey());
					$newPeerSocket->open();
					self::$peerSockets[$i] = $newPeerSocket;
				}  catch (Exception $ex){
					self::$peerSockets[$i] = null;
				}
			}
			
			$i++;
		}
		
		return self::$peerSockets;
	}
}