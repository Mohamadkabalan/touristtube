<?php

$path = "../";
include_once ( $path . "inc/service_bootstrap.php" );
//A parameter called t should be passed to this service. Possible values: h (5 hours) and w (weekly)');
if(isset($argv[1])&&($argv[1]=='h' || $argv[1]=='w' ) ) {
   $timeFrame = $argv[1];
    if($timeFrame == 'h'){
        sendEmailNotificationEach5h();
        sendChannelEmailNotificationEach5h();
    }
    else if($timeFrame == 'w'){
        sendEmailNotification_Weekly();
        sendChannelEmailNotification_Weekly();
    }
}