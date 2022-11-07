<?php

if (!isset($argv) || !count($argv))
	exit(-1);

$base_dir = __DIR__;

$include_dir = dirname($base_dir).'/inc';

include_once($include_dir."/config.php");
include_once($include_dir."/classes/phpmailer.class.php");

$base_log_dir = $CONFIG['services']['logs']['base_dir'];

$dkim = null;

if (isset($CONFIG['services']['email']['DKIM']) && isset($CONFIG['services']['email']['DKIM']['private']) && $CONFIG['services']['email']['DKIM']['private'])
{
	$dkim = $CONFIG['services']['email']['DKIM'];
}

function appendToLog($msg)
{
	global $base_log_dir;
	
	$dt = date('Ymd');
	
    file_put_contents("${base_log_dir}/${dt}_send_email_notification.log", date('c') . ' - ' . $msg . "\n", FILE_APPEND);
}

$hosts = $CONFIG['services']['email']['providers']['socket_labs']['hosts'];

if (!isset($hosts) || !$hosts)
	exit(-2);

$host = null;
$priority_str = '> 0';

$required_args = array('host', 'start', 'limit');

$long_opts = array('priority::');

foreach ($required_args as $required_arg)
{
	$long_opts[] = "${required_arg}:";
}

$options = getopt(null, $long_opts);

if (!$options)
{
	appendToLog("No argument given, required:: ".implode(', ', $required_args));
	
	exit(-2);
}

$missing_args = array();

foreach ($required_args as $required_arg)
{
	if (!isset($options[$required_arg]))
		$missing_args[] = $required_arg;
}

if ($missing_args)
{
	appendToLog("Missing args:: ".implode(', ', $missing_args));
	
	exit(-2);
}


if (isset($options['priority']) && $options['priority'] == '0')
{
	$priority_str = '= 0'; // service highest-priority emails
}

if (isset($hosts[$options['host']]))
	$host = $hosts[$options['host']];
else
	$host = reset($hosts);

function dbConnect1($dbConfig)
{
	try 
	{
		$connection_string = 'mysql:host='.$dbConfig['host'].';dbname='.$dbConfig['name'];
		$conn = new PDO($connection_string, $dbConfig['user'], $dbConfig['pwd']);
		
		$conn->exec("set names utf8");
	}
	catch(PDOException $e)
	{
		appendToLog("Failed to get DB handle:: ".$e->getMessage());
		
		exit(-4);
	}
	
	return $conn;
}

$dbConn = dbConnect1($CONFIG['db']);

if (!$dbConn)
{
	appendToLog("Failed to get a connection to the database.");
	
	exit(-1);
}



function initMailer($host)
{
	global $dkim;
	
    $mail = new PHPMailer();
    $mail->Host = $host['host'];
    
	if ($host['is_smtp'])
	{
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = $host['security_type'];
        $mail->Port = $host['port'];
        $mail->Username = $host['username'];
        $mail->Password = $host['password'];
		
		/*
		// PHP Parse error:  syntax error, unexpected '$dkim_element' (T_VARIABLE) on line 133 --> 135
		if ($dkim != null)
		{
			foreach ($dkim as $dkim_element => $dkim_value)
			{
				$mail->DKIM_$dkim_element = $dkim_value;
			}
			
			$mail->DKIM_identity = $mail->From;
		}
		*/
    }
	
    $mail->AltBody = "To view the message, please use an HTML compatible email viewer!";
	
    return $mail;
}

$query = "SELECT * FROM cms_emails WHERE sent = 0 AND num_try < 3 AND priority $priority_str ORDER BY priority ASC, create_ts ASC LIMIT ".$options['start'].', '.$options['limit'];
$statement = $dbConn->prepare($query);
$execStatus = $statement->execute();

$rowCount = $statement->rowCount();

if (!$execStatus || !$rowCount)
	exit(-10);


$emails = $statement->fetchAll();

$mail = initMailer($host);


$update_query_sent = "UPDATE cms_emails SET sent = 1 WHERE id = :id";
$update_statement = null;

$update_num_tries_query = "UPDATE cms_emails SET num_try = num_try + 1 WHERE id = :id";
$update_num_tries_statement = null;

try
{
	$update_statement = $dbConn->prepare($update_query_sent);
	$update_num_tries_statement = $dbConn->prepare($update_num_tries_query);
}
catch (PDOException $e)
{
	exit(-1);
}

foreach($emails as $email)
{
	$mail->ClearAddresses();
	$mail->CharSet = 'UTF-8';
	$mail->SetFrom('info@touristtube.com', $email['title']); 
	$mail->Subject = (defined('ENVIRONMENT') && ENVIRONMENT == 'development'?'dev -- ':'').$email['subject'];
	$mail->MsgHTML($email['msg']);
	// $mail->AddAddress($email['to_email']);
	
	$emails = preg_split('/[,; ]/', $email['to_email']);
	
	if ($emails === false || !$emails)
		continue;
	
	$recipient_email = '';
	
	do
	{
		$recipient_email = array_shift($emails);
	}
	while (!$recipient_email);
	
	if (!$recipient_email)
		continue;
	
	$mail->AddAddress($recipient_email);
	
	if ($emails)
	{
		foreach ($emails as $recipient_email)
		{
			if (!$recipient_email)
				continue;
			
			$mail->AddCC($recipient_email);
		}
	}
	
	if ($mail->Send())
	{
		$update_statement->bindValue(':id', $email['id'], PDO::PARAM_INT);
		
		$update_statement->execute();
	}
	else
	{
		
		$update_num_tries_statement->bindValue(':id', $email['id'], PDO::PARAM_INT);
		
		$update_num_tries_statement->execute();
		
		appendToLog($mail->ErrorInfo);
	}
}

$mail->SmtpClose();

if ($update_statement != null)
{
	$update_statement->closeCursor();
	$update_statement = null;
}

if ($update_num_tries_statement != null)
{
	$update_num_tries_statement->closeCursor();
	$update_num_tries_statement = null;
}


$dbConn = null;

