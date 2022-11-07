<?php
/**
 * a list of UniqueConnection
 * @package chat_server
 */

/**
 * manages the connections 
 */
class ConnectionList{
	
	/**
	 * the list of connected users
	 * @var array(array(UniqueConnection))
	 */
	static $connected_users = array();
	
	/**
	 * the list of connected users statuses
	 * @var array(integer) 
	 */
	static $status = array();
	
	/**
	 * gets the connections user structure given the user_id
	 * @param integer $user_id the client side user_id
	 * @return array(UniqueConnection) the user as used by the socket server 
	 */
	public static function getUser($user_id){
            echo $user_id;
		return isset(self::$connected_users[$user_id]) ? self::$connected_users[$user_id] : null;
	}
	
	/**
	 * adds a user to the connection list
	 * @param integer $user_id the client side user_id
	 * @param IWebSocketConnection $user the socket server data structute
	 * @param integer $client_type what kind of client
	 */
	public static function addUser($user_id, $uid, $user, $client_type){
		
		if( self::findUserID($user) != false ){
			//the connection is already associated with a user_id
			//actually should never happen
			appendChatLog( 'SOCKET: ' . $user->getId() . ' UID: ' . $uid . ' user_id ' . $user_id . ' ALREADY THERE' );
			return;
		}else if( !self::userIsConnected($user_id) ){
			//this is a new connection for the user
			$new_connection = new UniqueConnection($user,$client_type,$uid,$user_id);
			self::$connected_users[$user_id] = array($new_connection);
			appendChatLog( 'SOCKET: ' . $user->getId() . ' UID: ' . $uid . ' user_id ' . $user_id . ' NEW CONNECTION');
		}else{
			//the user is connecting using a new device or the uid is reconnecting on a different socket
			$new_connection = new UniqueConnection($user,$client_type,$uid,$user_id);
			
			appendChatLog( 'SOCKET: ' . $user->getId() . ' UID: ' . $uid . ' user_id ' . $user_id . ' RE-CONNECTION');
			
			//overwriting the connection doesnt work in case same session of same browser to delete it after a timeout
			//for the mobile it can just be overwritten since the same session can be used twice
			if( in_array($client_type,array( CLIENT_ANDROID , CLIENT_WINDOWS, CLIENT_IOS ) ) ){
				$found = false;
				foreach(self::$connected_users as $user_id_loop => &$user_loops){
					$i = 0;
					while($i < count($user_loops)){
						//session_id found
						if( $user_loops[$i]->uid == $uid ){
							$user_loops[$i] = $new_connection;
							$found = true;
							appendChatLog( "OVERWRITE A CONNECTION FOR USER: " . $user_id . ' SOCKET: ' . $user->getId() . ' UID: ' . $uid . ' NSOCKETS: ' . count(self::$connected_users[$user_id]) );
							break;
						}
						$i++;
					}
					if($found) break;
				}
				//session_id not found so append to list
				if(!$found){
					appendChatLog( "NEW CONNECTION FOR USER: " . $user_id . ' SOCKET: ' . $user->getId() . ' UID: ' . $uid . ' NSOCKETS: ' . count(self::$connected_users[$user_id]) );
					self::$connected_users[$user_id][] = $new_connection;
				}
			}else if($client_type == CLIENT_WEB){
				self::$connected_users[$user_id][] = $new_connection;
				appendChatLog("ADDING A CONNECTION FOR USER: " . $user_id . ' SOCKET: ' . $user->getId() . ' UID: ' . $uid . ' NSOCKETS: ' . count(self::$connected_users[$user_id]) );
			}
			
		}
	}
	
	/**
	 * remove a user from the list of connections
	 * @param integer $user_id the client side user id to remove
	 */
	public static function removeUser($user_id, $user){
		if( !self::userIsConnected($user_id) ) return;
		
		//remove the connection
		$i = 0;
		$keys = array_keys(self::$connected_users[$user_id]);
		while( $i < count($keys) ){
			if( self::$connected_users[$user_id][ $keys[$i] ]->connection->getId() == $user->getId() ){
				if( self::$connected_users[$user_id][ $keys[$i] ]->isTerminated() ){
					unset( self::$connected_users[$user_id][ $keys[$i] ] );
				}else{
					self::$connected_users[$user_id][ $keys[$i] ]->disconnected();
				}
				
				break;
			}
			$i++;
		}
		
		//if no more connections then the user is really disconnected
		if( count(self::$connected_users[$user_id]) == 0 ){
			unset( self::$connected_users[$user_id] );
		}
	}
	
	/**
	 * finds the client side id given the server side srtucture
	 * @param IWebSocketConnection $user thew server side data stucture
	 * @return integer the client_side id or false if not found
	 */
	public static function findUserID($user){
		//file_put_contents("log/chat.log", print_r(self::$connected_users, true) . "\r\n", FILE_APPEND);
		foreach(self::$connected_users as $user_id => $user_loops){
			foreach($user_loops as $user_loop){
				if( $user_loop->connection->getId() == $user->getId() ){
					return $user_id;
				}
			}
		}
		return false;
	}

	/**
	 * finds the client side id given the server side srtucture
	 * @param IWebSocketConnection $user thew server side data stucture
	 * @return integer the client_side id or false if not found
	 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<start>
//	public static function findUniqueConnection($user){
//		foreach(self::$connected_users as $user_id => $user_loops){
//			foreach($user_loops as $user_loop){
//				if( $user_loop->connection->getId() == $user->getId() ){
//					return $user_loop;
//				}
//			}
//		}
//		return false;
//	}
	
	/**
	 * checks to see if a user is connected
	 * @param integer $user_id the client side user id
	 * @return boolean true|false if the user is connected or not. 
	 */
	public static function userIsConnected($user_id){
		return isset(self::$connected_users[$user_id]);
	}


	/**
	 * checks to see if a user is connected
	 * @param string $user_id the client side user id
	 * @return boolean true|false if the user is connected or not. 
	 */
//	public static function uidIsConnected($uid){
//		foreach(self::$connected_users as $cuid => $user_loops){
//			foreach($user_loops as $user_loop){
//				if( $user_loop->uid == $uid ){
//					return $user_loop;
//				}
//			}
//		}
//		return false;
//	}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<end>
	
	
	/**
	 * checks to see if a user is connected
	 * @param integer $user_id the client side user id
	 * @return array status_code and status_string
	 */
	public static function userGetStatus($user_id){
		if ( isset(self::$connected_users[$user_id]) ){
			
			if( self::$status[$user_id] == CHAT_STATUS_APPEAROFFLINE )
				return 0;
			else
				return self::$status[$user_id];
			
		}else{
			return 0;
		}
	}
	
	/**
	 * sets a user's status
	 * @param integer $user_id the user's id
	 * @param integer $status the new status
	 */
	public static function userSetStatus($user_id, $status){
		self::$status[$user_id] = $status;
	}
	
}