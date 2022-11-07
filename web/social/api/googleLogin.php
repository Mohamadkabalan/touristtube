<?php
ini_set('display_errors', 1);
require_once("heart.php");
//include_once ("../src/facebook.php");
header('Content-type: application/json');
$submit_post_get = array_merge($request->query->all(),$request->request->all());

$client_type = isset($submit_post_get['client_type']) ? $submit_post_get['client_type'] : CLIENT_ANDROID;
$access_token = $submit_post_get['access_token'];

$access_token = '{"access_token":"ya29.DwJuUqMP5eSI_L-VQitIq6mrWmrApA7sUE3qvTNXRg5oS0gtdL5dPxAEvfbU_8ZBww2y","token_type":"Bearer","expires_in":3598,"id_token":"eyJhbGciOiJSUzI1NiIsImtpZCI6IjczZDdhZmZjODUzNDFjYTUwMzlhMTkzMDI2YzljOTY4OTdhOTk0MTQifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiYXRfaGFzaCI6IjdmYlJWTFh2VzdLNWU1MUhxNG9QUkEiLCJhdWQiOiIyOTgyMTEzNTM0NTctajNmOWRqaWxyc2JiamR2c3BiODQwYmFya3F0aGNnN3UuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMTE4ODE0NjgxNDgyMDA2NTQwMDIiLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwiYXpwIjoiMjk4MjExMzUzNDU3LWozZjlkamlscnNiYmpkdnNwYjg0MGJhcmtxdGhjZzd1LmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiZW1haWwiOiJlbGllLmJvdS56ZWlkQGdtYWlsLmNvbSIsImlhdCI6MTQ0NTA4NjkzMCwiZXhwIjoxNDQ1MDkwNTMwfQ.nUM4zazTUDyeiiKO4oTxyYDosvfOzKlBOp7aSPnWmXDawcVKrjrWYwLjNRhXG8pFyFSxOgZZ12nRjhjBcPxdHcwvnMIVzHDvfOZAa1s0egtDzOo5QEo5J2rK7QJMyDKDkcQjFcswR2J_0o9vP6oSd1z3s9RL9Xl7SYwRAxPONXCE-HNu-xN1IRot3cgeE4xEJ6K2Icu1SDXly8hU92Nbok9N80fVFz1iL0WEMmW1KhZ41ByUfGgPIXco99J52CTaZNNS1DBqpTvJWehefti7_o_xalRejJBE0R3SFssxmQbfauEs0-yJNY5gnKMNv-48--FUKzeDmcA5iVsfjRhDLA","created":1445086932}';
//$access_token = "";
$client_id = "298211353457-j3f9djilrsbbjdvspb840barkqthcg7u.apps.googleusercontent.com";
$client_secret = "uXKCEp-o_E2eY4JjX_YEuE5J";

//$redirect_uri = "http://tt.com/social/api/googleLogin.php";

$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->addScope("email");
$client->addScope("profile");
//$authUrl = $client->createAuthUrl();
//echo $authUrl;exit;
//try{
//    $token_data = $client->verifyIdToken($access_token)->getAttributes();
//    print_r($token_data);exit;
//}
//catch(Exception $ex){
//    echo $ex->getMessage();
//    exit;
//}

try{
$client->setAccessToken($access_token);
}
catch(Exception $ex){
    echo $ex->getMessage();
    exit;
}

//print_r($client);
//echo "test";

try{
$service = new Google_Service_Oauth2($client);

$user = $service->userinfo->get();
}
catch(Exception $ex){
    echo $ex->getMessage();
    exit;
}
echo json_encode($user);exit;

$id = $user->id;
$name = $user->name;
$email = $user->email;
$link = $user->link;
$picture = $user->picture;