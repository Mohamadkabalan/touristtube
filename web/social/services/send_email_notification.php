<?php

$base_dir = __DIR__;

$include_dir = dirname($base_dir).'/inc';

include_once($include_dir."/config.php");

if (!isset($CONFIG['services']) || !isset($CONFIG['services']['email']) || !isset($CONFIG['services']['email']['base_dir']) || !isset($CONFIG['services']['email']['providers']['socket_labs']) || !isset($CONFIG['services']['email']['providers']['socket_labs']['hosts']))
	exit(-1);

// $base_dir = $CONFIG['services']['email']['base_dir'];

$hosts_count = count($CONFIG['services']['email']['providers']['socket_labs']['hosts']);
$limit = 150;

while (true)
{
    for ($i = 0;$i < $hosts_count;$i++)
	{
        $offset = $i * $limit;
		
        $cmd = "";
		
        exec("php $base_dir/send_email_script.php --priority=0 --host=$i --start=$offset --limit=$limit > /dev/null &");
		
		exec("php $base_dir/send_email_script.php --host=$i --start=$offset --limit=$limit > /dev/null &");
    }
	
    sleep(30);
	
	for ($i = 0;$i < $hosts_count;$i++)
	{
        $offset = $i * $limit;
		
        $cmd = "";
		
        exec("php $base_dir/send_email_script.php --priority=0 --host=$i --start=$offset --limit=$limit > /dev/null &");
    }
	
    sleep(30);
}
