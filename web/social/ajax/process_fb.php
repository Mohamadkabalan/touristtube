<?php
$path = "../";
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/users.php" );

$access_token = $request->request->get('access_token', '');
$longitude = floatval($request->request->get('longitude', 0));
$latitude = floatval($request->request->get('latitude', 0));

$config_use = array(
    'app_id' => $CONFIG['facebook_app_id'],
   'app_secret' => $CONFIG['facebook_app_secret'],
    'default_graph_version' => $CONFIG['facebook_default_graph_version'],
//    'default_access_token' => $access_token
);


try {

  $fb  = new Facebook\Facebook($config_use);
//  $response = $fb->get('/me?fields=id,name,email,birthday,first_name,last_name,gender', $access_token);
  $response = $fb->get('/me?fields=id,name,email,birthday,first_name,last_name', $access_token);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
    $ret['status'] = 'error';
    $ret['message'] = 'Graph returned an error: ' . $e->getMessage();
    echo json_encode($ret);
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    $ret['status'] = 'error';
    $ret['message'] = 'Facebook SDK returned an error: ' . $e->getMessage();
    echo json_encode($ret);
  exit;
}
$user_profile = $response->getGraphUser();
if( !isset($user_profile['email']) || is_null($user_profile['email']) || $user_profile['email']==''){
    $ret['status'] = 'error';
    $ret['message'] = 'Facebook SDK returned an error: Invalid Facebook account';
    echo json_encode($ret);
  exit;
}
$user_email = $user_profile['email'];
$check_email  = EmailIsUnique($user_email);
// date('Y-m-d', strtotime($user_profile['birthday']));
if(isset($user_profile['birthday'])){
    $birthday= $user_profile['birthday'];
    $birthday_str = $birthday->format('Y-m-d');
}
else{
    $birthday_str = null;
}
//if(count($check_email) > 0 ) $res = userUpdateFbUser($check_email['id'], $user_profile['id']);
//else $res = userFacebookRegister($user_profile['name'], $user_profile['email'], $user_profile['first_name'], $user_profile['last_name'], $user_profile['gender'], $birthday_str ,$access_token, $user_profile['id']);
if(count($check_email) > 0 ) $res = userUpdateFbUser($check_email['id'], $user_profile['id']);
else $res = userFacebookRegister($user_profile['name'], $user_profile['email'], $user_profile['first_name'], $user_profile['last_name'], 'O', $birthday_str ,$access_token, $user_profile['id']);
   

    $keep_me_logged = 1;
    if (($userRec = userLoginFacebook($user_profile['id'], $access_token, CLIENT_WEB, 1))) {
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
    
    setcookie("lt", $userRec['token'], $expire, $pathcookie, $CONFIG['cookie_path']);
    $ret['status'] = 'ok';      
}
echo json_encode($ret);
    
    