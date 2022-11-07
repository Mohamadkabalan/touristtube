<?php

require_once('common.config.php');

define( 'STOP_FILE' , 'converter.stop');

/**
 * Manages the chat service
 * @author Halim
 */
class ConverterServiceManager {

	/**
	 * Starts the chat service  
	 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<start>
//	public static function Start() {
//		@unlink( STOP_FILE );
//		if (PHP_OS == 'WINNT') {
//			//windows
//			$cwd = getcwd();
//			$cmd = PHP_PATH . ' -c ' . PHP_INI_PATH . " $cwd/converter_service.php";
//			echo $cmd;
//			$WshShell = new COM("WScript.Shell");
//			$WshShell->CurrentDirectory = $cwd;
//			$oExec = $WshShell->Run("$cmd", 0, false);
//			file_put_contents("converter_service.pid", 1);
//		} else {
//			//linux
//			$PID = shell_exec("nohup php $path 2> /dev/null & echo $!");
//			file_put_contents("pid/converter_service.pid", $PID);
//		}
//	}

	/**
	 * checks if the service is running
	 * @return boolean 
	 */
//	public static function isRunning() {
//
//		return file_exists('pid/converter_service.pid');
//	}
	
	/**
	 * Stops the service 
	 */
//	public static function Stop(){
//		touch( STOP_FILE );
//	}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<end>
	
	/**
	 * checks if stop requested
	 * @return bool 
	 */
	public static function StopRequested(){
		return file_exists( STOP_FILE );
	}
	
	public static function Log($logline){
		//file_put_contents("log/converter_service.log", date('r') . ' - ' .$logline . PHP_EOL, FILE_APPEND);
	}
	
	public static function Cleanup(){
		@unlink('pid/converter_service.pid');
	}

}

?>
