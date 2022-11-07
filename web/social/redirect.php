<?php

/* Start session and load library. */
session_start();

require_once($CONFIG ['server']['root'].'/libs/twitteroauth/twitteroauth/twitteroauth.php');
require_once($CONFIG ['server']['root'].'/libs/twitteroauth/config.php');

//require_once('twitteroauth/twitteroauth.php');
//require_once('config.php');

/* Build TwitterOAuth object with client credentials. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
 
/* Get temporary credentials. */
$request_token = $connection->getRequestToken(OAUTH_CALLBACK);

/* Save temporary credentials to session. */

//print_r($request_token); exit;

$expire = time() + 365 * 24 * 3600;
$pathcookie = '/';
$token = $request_token['oauth_token'];
setcookie("oauth_token", $token , $expire, $pathcookie, $CONFIG['cookie_path']);
setcookie("oauth_token_secret", $request_token['oauth_token_secret'] , $expire, $pathcookie, $CONFIG['cookie_path']);
 
/* If last connection failed don't display authorization link. */
switch ($connection->http_code) {
  case 200:
    /* Build authorize URL and redirect user to Twitter. */
    $url = $connection->getAuthorizeURL($token);
    header('Location: ' . $url); 
    break;
  default:
    /* Show notification if something went wrong. */
    echo 'Could not connect to Twitter. Refresh the page or try again later.';
}
