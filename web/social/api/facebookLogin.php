<?php
require_once("heart.php");
//include_once ("../src/facebook.php");
header('Content-type: application/json');
$submit_post_get = array_merge($request->query->all(),$request->request->all());

$client_type = isset($submit_post_get['client_type']) ? $submit_post_get['client_type'] : CLIENT_ANDROID;
$access_token = $submit_post_get['access_token'];

//$access_token = 'CAAO2jF5lXksBACi5NlLRn6xZC8hawyfq6fIZAeeTsnP27g8iDl3co8YTAnJJRWeVjhfd1GmLxcWBz8uJo7XydeYeA5nf59J3rBYLetwWvp9M14Gtg0oR6fDhx7FEKsqlXGrQMWE9RIjZBK2A71IWH9MIxGQBTNmzoQMXBpnI92JkLdUSeU2RAlOmrvClD22VkVBAKVhsG7zjUvIbZA8h3qEplOsmZB10ZD';

$config_use = array(
    'app_id' => $CONFIG['facebook_app_id'],
    'app_secret' => $CONFIG['facebook_app_secret'],
    'default_graph_version' => $CONFIG['facebook_default_graph_version'],
//    'default_access_token' => $access_token
);

// Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
//   $helper = $fb->getRedirectLoginHelper();
//   $helper = $fb->getJavaScriptHelper();
//   $helper = $fb->getCanvasHelper();
//   $helper = $fb->getPageTabHelper();

try {
//    echo 'test1';
//    Facebook\FacebookSession::enableAppSecretProof(false);
//    echo 'test2';
  $fb  = new Facebook\Facebook($config_use);
  $fb->setDefaultAccessToken($access_token);
  // Get the Facebook\GraphNodes\GraphUser object for the current user.
  // If you provided a 'default_access_token', the '{access-token}' is optional.
  $response = $fb->get('/me?fields=id,name,email,first_name,last_name');

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
catch(Exception $e){
    print_r($e->getMessage());
    exit;
}
//echo 'test';
$user_profile = $response->getGraphUser();
if( !isset($user_profile['email']) || is_null($user_profile['email']) || $user_profile['email']==''){
    $ret['status'] = 'error';
    $ret['message'] = 'Facebook SDK returned an error: Invalid Facebook account';
    echo json_encode($ret);
  exit;
}
$user_email = $user_profile['email'];
$check_email  = EmailIsUnique($user_email);
if(count($check_email) > 0 ){
    $res = userUpdateFbUser($check_email['id'], $user_profile['id']);
    $keep_me_logged = 1;
    if (($userRec = userLoginFacebook($user_profile['id'], $access_token, $client_type, 1))) {
        $invalid = false;
        if ($longitude != 0 && $latitude != 0) {
            userProfilePosition($userRec['row']['id'], $longitude, $latitude);
        }
//        userSetSession($userRec['row']);
    //    user_login_track($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR'], $_SERVER['HTTP_USER_AGENT'], $userRec['row']['id']);
        if(isset($submit_post_get['model_number'])){
            $REMOTE_ADDR_server = $request->server->get('REMOTE_ADDR', '');
            $HTTP_X_FORWARDED_FOR_server = $request->server->get('HTTP_X_FORWARDED_FOR', '');
    //                            user_login_track($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR'], $submit_post_get['model_number'], $userRec['row']['id']);
            user_login_track($REMOTE_ADDR_server, $HTTP_X_FORWARDED_FOR_server, $submit_post_get['model_number'], $userRec['row']['id']);
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
}else{
    $birthday_str = null;
    $res = userFacebookRegister($user_profile['name'], $user_profile['email'], $user_profile['first_name'], $user_profile['last_name'], 'O', $birthday_str ,$access_token, $user_profile['id']);
    if($res){
        $keep_me_logged = 1;
        if (($userRec = userLoginFacebook($user_profile['id'], $access_token, $client_type, 1))) {
            $invalid = false;
            if ($longitude != 0 && $latitude != 0) {
                userProfilePosition($userRec['row']['id'], $longitude, $latitude);
            }
//            userSetSession($userRec['row']);
            if(isset($submit_post_get['model_number'])){
                $REMOTE_ADDR_server = $request->server->get('REMOTE_ADDR', '');
                $HTTP_X_FORWARDED_FOR_server = $request->server->get('HTTP_X_FORWARDED_FOR', '');
        //                            user_login_track($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR'], $submit_post_get['model_number'], $userRec['row']['id']);
                user_login_track($REMOTE_ADDR_server, $HTTP_X_FORWARDED_FOR_server, $submit_post_get['model_number'], $userRec['row']['id']);
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
    }else{
        $ret['relog'] = '1';
            echo json_encode($ret);
    }
}
if($ret['status'] == 'ok'){
    $result = array();
    $result['status'] = 'success';
    $result['ssid']=$userRec['token'];
    $result['username']=$userRec['row']['YourUserName'];
    $result['fname']=$userRec['row']['fname'];
    $result['lname']=$userRec['row']['lname'];
    $userDetail = getUserInfo($userRec['row']['id']);
    $result['fullname']=returnUserDisplayName($userDetail);
    $result['email'] = $userDetail['YourEmail'];
    $result['userid']=$userRec['row']['id'];
    echo json_encode($result);
}
else{
    echo json_encode($ret);
}
//echo json_encode(print_r($me));exit;
//echo 'Logged in as ' . $me->getName();