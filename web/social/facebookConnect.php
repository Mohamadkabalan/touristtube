<?php
//echo "Before inc  <br>\n";
$path = "";
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" =>1);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );
ini_set('display_errors', 'On');
use Facebook\FacebookRedirectLoginHelper;
//echo "page start <br>\n";

$helper = new FacebookRedirectLoginHelper('http://89.249.212.8/en/account/sharing');
//var_dump($helper);
echo '<a href="' . $helper->getLoginUrl() . '">'. _('Login with Facebook').'</a>';
try {
  $session = $helper->getSessionFromRedirect();
} catch(FacebookRequestException $ex) {
  // When Facebook returns an error
   echo _('Facebook returns an error');
  //   /   When Facebook returns an error
} catch(\Exception $ex) {
  // When validation fails or other local issues
   echo _('When validation fails or other local issues');
  //    /   When validation fails or other local issues
}
if ($session) {
  // Logged in
   echo _('Logged In');
  //   /   Logged in
}