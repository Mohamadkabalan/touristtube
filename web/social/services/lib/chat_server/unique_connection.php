<?php
/**
 * hold the chat server related functionality
 * @package chat_server
 */

/**
 * a unique connection
 */
class UniqueConnection{

	function __construct($conn,$conn_type,$in_uid,$in_user_id) {
		$this->connection = $conn;
		$this->connection_type = $conn_type;
		$this->uid = $in_uid;
		$this->user_id = $in_user_id;
		$this->status = 1;
		$this->disconnect_ts = null;
	}
	
	/**
	 * the connection
	 * @var IWebSocketConnection
	 */
	public $connection;

	/**
	 * the connection's unique id
	 * @var string
	 */
	public $uid;

	/**
	 * the connection's type
	 * @var integer
	 */
	public $connection_type;

	/**
	 * the connection's user_id
	 * @var integer
	 */
	public $user_id;

	/**
	 * the status
	 * @var integer
	 */
	public $status;
	
	/**
	 * the time at which the disconnection occurred
	 * @var type 
	 */
	private $disconnect_ts;

	/**
	 * this connection got disconnected
	 */
	public function disconnected(){
		$this->status = 0;
		$this->disconnect_ts = time();
	
	}

	/**
	 * this connection got reconnected
	 */
	public function reconnected(){
		$this->status = 1;
	}

	/**
	 * logging out
	 */
	public function logout(){
		$this->status = -1;
	}

	/**
	 * logging out
	 */
	public function isTerminated(){
		$webTerminated = ($this->status == 0) && ($this->connection_type == CLIENT_WEB) && ($this->disconnect_ts + MAX_WEB_DC_TIME < time() );
		$iosTerminated = false;
		$androidTerminated = false;
		return ($webTerminated || $iosTerminated || $androidTerminated);
	}

	/**
	 * @param WebSocketMessage $message the message to be sent
	 * send the message
	 */
	public function sendMessage($message, $chatMsgType = ChatMsgType::NO_NOTIFY){
		
		appendChatLog("status = " . $this->status);
		
		if( $this->status == 1){
			//if online
			return $this->connection->sendMessage($message);
		}else if($this->status == -1){
			//user still online but logging out
			return false;
		}else if($this->status == 0){
			//disconnected
			if( $chatMsgType == ChatMsgType::NOTIFY){
				if($this->connection_type == CLIENT_WEB){

				}else if($this->connection_type == CLIENT_ANDROID){
					iosPush($this->uid,"You have a new chat message");
				}else if($this->connection_type == CLIENT_IOS){
					iosPush($this->uid,"You have a new chat message");
				}
			}
			
			return false;
		}else{
			//shouldnt happen
			return false;
		}
		
	}
}