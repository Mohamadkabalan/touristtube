<?php
/**
 * @file
 * Take the user when they return from Twitter. Get access tokens.
 * Verify credentials and redirect to based on response from Twitter.
 */

/* Start session and load lib */
session_start();
echo 1;
require_once($CONFIG ['server']['root'].'/libs/twitteroauth/twitteroauth/twitteroauth.php');
require_once($CONFIG ['server']['root'].'/libs/twitteroauth/config.php');

echo 2;
exit;

//require_once('twitteroauth/twitteroauth.php');
//require_once('config.php');

/* If the oauth_token is old redirect to the connect page. */
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//if (isset($submit_post_get['oauth_token']) && $submit_post_get['oauth_token'] !== $submit_post_get['oauth_token']) {
//  $_SESSION['oauth_status'] = 'oldtoken';
// // header('Location: ./clearsessions.php');
//}

/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_COOKIE['oauth_token'], $_COOKIE['oauth_token_secret']);

/* Request access tokens from twitter */
//$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
$access_token = $connection->getAccessToken($submit_post_get['oauth_verifier']);

/* Save the access tokens. Normally these would be saved in a database for future use. */
//$_SESSION['access_token'] = $access_token;

/* Remove no longer needed request tokens */
$expire = time() + 365 * 24 * 3600;
$pathcookie = '/';
setcookie("oauth_token", '' , $expire, $pathcookie, $CONFIG['cookie_path']);
setcookie("oauth_token_secret", '' , $expire, $pathcookie, $CONFIG['cookie_path']);

/* If HTTP response is 200 continue otherwise send to connect page to retry */
if (200 == $connection->http_code) {
  /* The user has been verified and the access tokens can be saved for future use */
  $_SESSION['status'] = 'verified';
  header('Location: http://192.168.1.200/en/account/sharing');
} else {
    exit('not authorized');
  /* Save HTTP status for error dialog on connnect page.*/
  //header('Location: ./clearsessions.php');
}
