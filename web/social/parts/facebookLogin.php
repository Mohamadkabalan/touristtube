<?php 
    
    $path = "../";
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
    include_once ( $path . "inc/functions/videos.php" );
    include_once ( $path . "inc/functions/users.php" );
    include_once ( $path . "inc/functions/flash.php" );
    include_once ( $path . "src/facebook.php");
    $url = $request->query->get('url');
    
    $config_use = array(
        'appId' => '1049176588449442',
        'secret' => '59f28856e12707b5024a13948eb07e76',
    );
    $facebook = new Facebook($config_use);
    $user_id = $facebook->getUser();
    
    if($user_id){
        try{
            $check_email = array();
//            $user_profile = $facebook->api('/me', array('fields' => 'id,name,email,birthday,first_name,last_name,gender'));
            $user_profile = $facebook->api('/me', array('fields' => 'id,name,email,birthday,first_name,last_name'));
            $acces_token  = $facebook->getAccessToken();
            $user_email   = $user_profile['email'];
            $check_email  = EmailIsUnique($user_email);
            global $CONFIG;
            
            if(count($check_email) > 0 ){
                $res = userUpdateToken($check_email['id'],$acces_token,$user_profile['id']);
                $keep_me_logged = 1;
                if (($userRec = userLoginFacebook($username, CLIENT_WEB, 1))) {
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
                    header("location:".$url);
                } else {
                    $invalid = true;
                    $ret['status'] = 'error';
                    if (userIsDeactivated($username, $pswd)) {
                        $ret['reactivate'] = '1';
                    } else {
                        $ret['reactivate'] = '0';
                    }
                    header("location:".$login_url);
                }
            }else{
//                $res = userFacebookRegister($user_profile['name'], $user_profile['email'], $user_profile['first_name'], $user_profile['last_name'], $user_profile['gender'] ,$acces_token, $user_profile['id']);
                $res = userFacebookRegister($user_profile['name'], $user_profile['email'], $user_profile['first_name'], $user_profile['last_name'], 'O' ,$acces_token, $user_profile['id']);
                if($res){
                    $keep_me_logged = 1;
                    if (($userRec = userLoginFacebook($username, CLIENT_WEB, $keep_me_logged))) {
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
                        header("location:".$url);
                    } else {
                        $invalid = true;
                        $ret['status'] = 'error';
                        if (userIsDeactivated($username, $pswd)) {
                            $ret['reactivate'] = '1';
                        } else {
                            $ret['reactivate'] = '0';
                        }
                        header("location:".$login_url);
                    }
                }else{
                    $login_url = $facebook->getLoginUrl(array('scope' => 'email'));
                    header("location:".$login_url);
                }
            }
        }catch(FacebookApiException $e){
            $login_url = $facebook->getLoginUrl(array('scope' => 'email'));
            header("location:".$login_url);
            error_log($e->getType());
            error_log($e->getMessage());
        }
    }
    else{
        $login_url = $facebook->getLoginUrl(array('scope' => 'email'));
        header("location:".$login_url);
    }