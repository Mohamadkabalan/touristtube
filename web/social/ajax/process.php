<?php

$path = "../";
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/users.php" );

$username = $request->request->get('EmailField', '');
$pswd = $request->request->get('PasswordField', '');
$keep_me_logged = intval($request->request->get('keep_me_logged', 0));
$longitude = floatval($request->request->get('longitude', 0));
$latitude = floatval($request->request->get('latitude', 0));
$ret = array();
if (($userRec = userLogin($username, $pswd, CLIENT_WEB, $keep_me_logged))) {
    $invalid = false;
    if ($longitude != 0 && $latitude != 0) {
        userProfilePosition($userRec['row']['id'], $longitude, $latitude);
    }
    userSetSession($userRec['row']);
//    user_login_track($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR'], $_SERVER['HTTP_USER_AGENT'], $userRec['row']['id']);
    $REMOTE_ADDR_server = $request->server->get('REMOTE_ADDR', '');
    $HTTP_X_FORWARDED_FOR_server = $request->server->get('HTTP_X_FORWARDED_FOR', '');
    $HTTP_USER_AGENT_server = $request->server->get('HTTP_USER_AGENT', '');
    user_login_track($REMOTE_ADDR_server, $HTTP_X_FORWARDED_FOR_server, $HTTP_USER_AGENT_server, $userRec['row']['id']);
    global $CONFIG;
    $expire = time() + 365 * 24 * 3600;
    $pathcookie = '/';
    $current_channel = $userRec['row']['isChannel'] ? userDefaultChannelGet($userRec['row']['id']) : false;
    if( $current_channel !=false ) $current_channel = $current_channel['channel_url'];
    setcookie("current_channel", $current_channel , $expire, $pathcookie, $CONFIG['cookie_path']);
    
    if ($keep_me_logged == 1) {
        setcookie("lt", $userRec['token'], $expire, $pathcookie, $CONFIG['cookie_path']);
    } else {
        setcookie("lt", $userRec['token'], 0, $pathcookie, $CONFIG['cookie_path']);
    }    
    $ret['status'] = 'ok';
} else {
    $invalid = true;
    $ret['status'] = 'error';
    if (userIsDeactivated($username, $pswd)) {
        $ret['reactivate'] = '1';
    } else {
        $ret['reactivate'] = '0';
    }
}
/* if($invalid) echo 'error';
  else echo 'ok'; */
echo json_encode($ret);
