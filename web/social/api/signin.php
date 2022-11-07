<?php
/*! \file
 * 
 * \brief This api returns string of user
 * 
 *\todo <b><i>Change from comma separated string to Json object</i></b>
 * 
 * @param username  user name
 * @param password password
 * @param client client name
 * @param model_number device model number
 * 
 * @return string either a string 'error' for not logged in user else comma seprated string:
 * @return <pre> 
 * @return       <b>token</b>  token
 * @return       <b>YourUserName</b> user name
 * @return       <b>FullName</b> user full name
 * @return       <b>id</b> user id
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */
require_once("heart.php");
header('Content-type: application/json');
$submit_post_get = array_merge($request->query->all(),$request->request->all());

//if (isset($_REQUEST['username']) && isset($_REQUEST['password']) && isset($_REQUEST['client']))
//{
//        if ( ($_REQUEST['username']!="") && ($_REQUEST['password']!="") && ($_REQUEST['client']!=""))
//        {
//                $username = db_sanitize($_REQUEST['username']);
//                $pswd = db_sanitize($_REQUEST['password']);
//                $client = db_sanitize($_REQUEST['client']);
if (isset($submit_post_get['username']) && isset($submit_post_get['password']) && isset($submit_post_get['client']))
{
        if ( ($submit_post_get['username']!="") && ($submit_post_get['password']!="") && ($submit_post_get['client']!=""))
        {
                $username = $submit_post_get['username'];
                $pswd = $submit_post_get['password'];
                $client = $submit_post_get['client'];

                $clientok = 0;
                switch($client)
                {
                        case "ANDROID": $clientok = CLIENT_ANDROID; break; 
                        case "IOS": $clientok = CLIENT_IOS; break;
                        case "WINDOWS": $clientok = CLIENT_WINDOWS ; break;
                        case "BLACKBERRY":  $clientok = CLIENT_BLACKBERRY; break;
                        case "NOKIA":  $clientok = CLIENT_NOKIA; break;
                }
                if( ($userRec = userLogin($username, $pswd, $clientok, 1)) ){
                        $invalid = false;
//                        if(isset($_REQUEST['model_number'])){
//                            user_login_track($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR'], $_REQUEST['model_number'], $userRec['row']['id']);
                        if(isset($submit_post_get['model_number'])){
                            $REMOTE_ADDR_server = $request->server->get('REMOTE_ADDR', '');
                            $HTTP_X_FORWARDED_FOR_server = $request->server->get('HTTP_X_FORWARDED_FOR', '');
//                            user_login_track($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR'], $submit_post_get['model_number'], $userRec['row']['id']);
                            user_login_track($REMOTE_ADDR_server, $HTTP_X_FORWARDED_FOR_server, $submit_post_get['model_number'], $userRec['row']['id']);
                        }
//				userSetSession($userRec);

                }else{
                        $invalid = true;
                }
                
                $result = array();
                if($invalid) {
                    echo json_encode(array('status' => 'error'));
                }
                else {
                    $result['status'] = 'success';
                    $result['ssid']=$userRec['token'];
                    $result['username']=$userRec['row']['YourUserName'];
                    $result['fname']=$userRec['row']['fname'];
                    $result['lname']=$userRec['row']['lname'];
                    /*code changed by sushma mishra on 30-sep-2015 to get fullname using returnUserDisplayName function starts from here*/
                    $userDetail = getUserInfo($userRec['row']['id']);
//$result['fullname']=htmlEntityDecode($userRec['row']['FullName']);
                    $result['fullname']=returnUserDisplayName($userDetail);
                    /*code changed by sushma mishra on 30-sep-2015 ends here*/
                    $result['email'] = $userDetail['YourEmail'];
                    $result['userid']=$userRec['row']['id'];
                    
                    echo json_encode($result);   
                }
                    //echo $userRec['token'].','.$userRec['row']['YourUserName'].','.htmlEntityDecode($userRec['row']['FullName']).','.$userRec['row']['id'];
                    //else echo $_SESSION['ssid'].','.$userRec['YourUserName'].','.htmlEntityDecode($userRec['FullName']).','.$userRec['id'];
        }
}