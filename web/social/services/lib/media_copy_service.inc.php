<?php 
//Commented By para-soft12 Anthony Malak 03-27-2015 cleaning code needs to be remove no meaning 
//<start>
//	define('MEDIA_MAX_COPY',2);
//
//	$tt_media_servers = array(
//		
//	);
//	
//	//server templates is
//	/**
//	 * host,start,username,paswd 
//	 */
//	
//	/**
//	 * logs
//	 * @param string $logline 
//	 */
//	
//	
//	/**
//	 * find a random server to copy to.
//	 * @global array $tt_media_servers
//	 * @return intgere which server to copy to 
//	 */
////	function MediaCopyGenerateServer(){
////		global $tt_media_servers;
////		return rand(0, count($tt_media_servers) - 1 );
////	}
////	
//	/**
//	 * gets the server name given its id
//	 * @global array $tt_media_servers
//	 * @return false|string which server to get or false if invalid
//	 */
////	function MediaCopyGetServer($which){
////		global $tt_media_servers;
////		if( count($tt_media_servers) == 0 ) return false;
////		if( $which > count($tt_media_servers) ) return false;
////		return $tt_media_servers[$which]['host'];
////	}
//	
//	/**
//	 * copies a media file to a remote load balancing server
//	 * @global array $tt_media_servers
//	 * @param integer $which the server id
//	 * @return true|false if success|fail
//	 */
//	function MediaCopyToServer($files, $which){
//		global $tt_media_servers;
//		global $CONFIG;
//		
//		$server = $tt_media_servers[$which];
//		$host = $server['host'];
//		$start = $server['start'];
//		$username = $server['username'];
//		$paswd = $server['username'];
//		
//		$conn_id = ftp_connect($host); 
//		$login_result = ftp_login($conn_id, $username, $paswd);
//		
//		if(!$login_result){
//			MediaCopyLog('COULDNT CONNECT TO ' . $host);
//			return false;
//		}
//
//		ftp_chdir($conn_id, $start);
//
//		foreach($files as $file){
//			if(strlen($file) == 0) continue;
//			if( ftp_put($conn_id, $file, $CONFIG['server']['root'] . $file, FTP_BINARY) ){
//				MediaCopyLog('COPIED ' . $file);
//			}else{
//				MediaCopyLog('COULDNT COPY ' . $file);
//				ftp_close($conn_id);
//				return false;
//			}
//		}
//
//		ftp_close($conn_id);
//		return true;
//	}
//		</end>