<?php

$base_dir = __DIR__;

$include_dir = $base_dir.'/inc';

ob_start();

include_once($include_dir."/config.php");

if (!isset($CONFIG['db']['db_checker_user']) || !isset($CONFIG['db']['db_checker_password']))
	exit(-1);

function dbConnect1($dbConfig, $options = array())
{
	$success = 1;
	$message = 'success';
	$db_handle = null;
	
	$user = $dbConfig['user'];
	$password = $dbConfig['pwd'];
	
	if ($options && isset($options['tt_auth_source']) && isset($options['tt_auth_source']['user']) && isset($options['tt_auth_source']['password']))
	{
		$user = $dbConfig[$options['tt_auth_source']['user']];
		$password = $dbConfig[$options['tt_auth_source']['password']];
	}
	
	try 
	{
		$connection_string = 'mysql:host='.$dbConfig['host'].';dbname='.$dbConfig['name'];
		$db_handle = new PDO($connection_string, $user, $password);
		
		// $db_handle->exec("set names utf8"); // no need for this here
	}
	catch(PDOException $e)
	{
		$success = 0;
		$message = $e->getMessage();
	}
	
	return array('status' => $success, 'db_handle' => $db_handle, 'message' => $message);
}

$attempt = dbConnect1($CONFIG['db'], array('tt_auth_source' => array('user' => 'db_checker_user', 'password' => 'db_checker_password')));

if ($attempt['message'] == 'success')
{
	$attempt['db_handle'] = null; // immediately close the connection
}

// ensure nothing is being sent before the header(s)
ob_end_clean();

if ($attempt['message'] == 'success')
{
	header($_SERVER['SERVER_PROTOCOL'].' 200 OK', true, 200);
	
	echo 1;
}
else
{
	header($_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error', true, 500);
	
	echo 0;
}

exit(0);

?>