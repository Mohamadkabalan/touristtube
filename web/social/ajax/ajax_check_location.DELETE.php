<?php

    $path = "../";
    $bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
    include_once ( $path . "inc/functions/users.php" );
    $longitude = floatval($request->request->get('longitude', ''));
    $latitude = floatval($request->request->get('latitude', ''));
    $user_id = userGetID();

    if( $longitude!=0 && $latitude!=0 ){
        if(userProfilePosition($user_id,$longitude,$latitude)){
        }
    }
