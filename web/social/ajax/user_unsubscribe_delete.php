<?php
    $path = "../";

    $bootOptions = array("loadDb" => 1 , 'requireLogin' => false);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );

    include_once ( $path . "inc/functions/videos.php" );
    include_once ( $path . "inc/functions/users.php" );
	
//    $code = xss_sanitize(@$_POST['code']);
    $code = $request->request->get('code', '');

    $message = array();
    $message['error'] = '';
    $message['msg'] = '';


    if(deleteUserEmailMD5($code)){		
            $message['msg'] = '';
    }else{
            $message['error'] = 'error';
            $message['msg'] = 'Couldn\'t process. Please try again later.';
    }
    echo json_encode($message);
