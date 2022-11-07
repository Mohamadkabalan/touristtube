<?php
    $path = "../";

    $bootOptions = array("loadDb" => 1 , 'requireLogin' => 1);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );

    include_once ( $path . "inc/functions/users.php" );

    exit(1);
header('Content-Type: image/png');

use Endroid\QrCode\QrCode;

if (userIsLogged()) {
    $user_id = userGetID();
debug('sele2');
    if( $user_id == 1 || $user_id == 42 ){
        debug($request->request->get('url', 'tt.com'));
        $size = 200;
        $padding = 10;
        $qr = new Endroid\QrCode\QrCode();

        $qr->setText($request->request->get('url', 'tt.com'));
        $qr->setSize($size);
        $qr->setPadding($padding);
        $qr->render();
    }
}