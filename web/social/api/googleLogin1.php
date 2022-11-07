<?php
ini_set('display_errors', 1);
require_once("heart.php");
//include_once ("../src/facebook.php");
//header('Content-type: application/json');
########## Google Settings.Client ID, Client Secret from https://console.developers.google.com #############
$client_id = '298211353457-j3f9djilrsbbjdvspb840barkqthcg7u.apps.googleusercontent.com'; 
$client_secret = 'uXKCEp-o_E2eY4JjX_YEuE5J';
$redirect_uri = 'http://tt.com/social/api/googleLogin1.php';

$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->addScope("email");
$client->addScope("profile");

$service = new Google_Service_Oauth2($client);

//If code is empty, redirect user to google authentication page for code.
//Code is required to aquire Access Token from google
//Once we have access token, assign token to session variable
//and we can redirect user back to page and login.
if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
  exit;
}

//if we have access_token continue, or else get login URL for user
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
} else {
  $authUrl = $client->createAuthUrl();
}

//Display user info or display login url as per the info we have.
echo '<div style="margin:20px">';
if (isset($authUrl)){ 
    //show login url
    echo '<div align="center">';
    echo '<h3>Login with Google -- Demo</h3>';
    echo '<div>Please click login button to connect to Google.</div>';
    echo '<a class="login" href="' . $authUrl . '"><img src="media/images/google-login-button.png" /></a>';
    echo '</div>';
    
} else {
    
    $user = $service->userinfo->get(); //get user info 
    
    
    //show user picture
    echo '<img src="'.$user->picture.'" style="float: right;margin-top: 33px;" />';
    
    //print user details
    echo '<pre>';
    print_r($user);
    echo '</pre>';
    print_r( $_SESSION['access_token']);
}
echo '</div>';