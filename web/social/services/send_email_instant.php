<?php

$path = "../";
include_once ( $path . "inc/service_bootstrap.php" );
include_once ( $path . "inc/classes/phpmailer.class.php" );

$hosts_count = 1;
$limit = 5;
while(true){
    $dir = getcwd();
    for($i = 0; $i < $hosts_count; $i++){
        $offset = $i*$limit;
        $cmd = "php -f $dir/send_email_script_instant.php".
                addslashes(" host=$i").
                addslashes(" start=$offset").
                addslashes(" limit=$limit");
        exec($cmd . " > /dev/null &");
    }
    sleep(30); // 180
} 