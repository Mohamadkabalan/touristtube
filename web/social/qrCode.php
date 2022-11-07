<?php
$path = "";
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );

include_once ( $path . "inc/functions/users.php" );

header('Content-Type: image/png');

if (userIsLogged()) {
    $user_id = userGetID();
    if( $user_id == 1744 ){
        $size = 185;
        $padding = 10;
        $qr = new Endroid\QrCode\QrCode();

        $qr->setText($request->query->get('url', 'tt.com'));
        $qr->setSize($size);
        $qr->setPadding($padding);
        $qr->render();
    }
}