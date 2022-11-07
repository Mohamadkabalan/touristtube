<?php
/*! \file
 * 
 * \brief This api update user info
 * 
 * 
 * @param S session id
 * @param YourEmail user email
 * @param YourUserName user name
 * @param fname user first name
 * @param lname user last name
 * @param gender user gender
 * @param website_url user website url
 * @param small_description user small description
 * @param YourCountry user Country
 * @param hometown user hometown
 * @param city user city
 * @param YourBday user birthday
 * @param display_age user display age on profile
 * @param display_yearage user display year age on profile
 * @param display_gender user display gender on profile
 * @param display_username user display username on profile
 * @param employment user employment
 * @param education user education
 * @param intrested_in user intrested in
 * @param display_interest user display interest on profile
 * @param notify_comment user notify comment
 * @param notify_friend user notify friend
 * @param notify_subscribe user notify subscribe
 * @param notify_replies user notify replies
 * 
 * @return <b>ret</b> List of user info(array)
 * @return <pre> 
 * @return         <b>status</b> status of the updates
 * @return         <b>email_valid</b> user email valid or not
 * @return         <b>email_unique</b> user email unique or not
 * @return         <b>username_unique</b> user name unique or not
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */
 
//        session_start();
//$bootOptions = array ( "loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);

$expath = '../../';
include_once("../../heart.php");

//$id = $_SESSION['id'];
//$id = mobileIsLogged($_REQUEST['S']);
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$id = mobileIsLogged($submit_post_get['S']);
if( !$id ) { die(); }
$userInfo = getUserInfo($id);
//$YourEmail = xss_sanitize($_REQUEST['YourEmail']);
//$new_username = xss_sanitize($_REQUEST['YourUserName']);
$YourEmail = $submit_post_get['YourEmail'];
$new_username = $submit_post_get['YourUserName'];
$ret = array('status' => 'done', 'username_unique' => true, 'email_unique' => true, 'email_valid' => true);
if(!userNameisUnique($id, $new_username)){
    $ret['status'] = 'error';
    $ret['username_unique'] = false;
}
/* code added by sushma mishra on 29 september 2015 to check email id starts from here*/
if(trim($YourEmail)!='' && trim($YourEmail)!=NULL){
	if(trim($userInfo['YourEmail']) != trim($YourEmail)){		
		if(check_email_address($YourEmail)){
			if(!userEmailisUnique($id, $YourEmail)){
				$ret['status'] = 'error';
				$ret['email_unique'] = false;
			}
		}else{
			$ret['status'] = 'error';
			$ret['email_valid'] = false;
		}
	}
}else{	
    $ret['status'] = 'error';
    $ret['email_valid'] = false; 
}
if($ret['status'] == 'error'){
    echo json_encode($ret);
    exit;
}
/*if(trim($userInfo['YourEmail']) != trim($YourEmail)){
    if(check_email_address($YourEmail)){
        if(!userEmailisUnique($id, $YourEmail)){
            $ret['status'] = 'error';
            $ret['email_unique'] = false;
        }
    }
}
else{
    $ret['status'] = 'error';
    $ret['email_valid'] = false;
}*/
/* code added by sushma mishra on 29 september 2015 ends here*/
$res = $id . "<<< ";
//foreach ($_REQUEST as $k => $v) {
foreach ($submit_post_get as $k => $v) {
    $res .= $k . "=" . $v . " ||| ";
}
file_put_contents("editprofile" . $id . ".test.txt", $res);
$user_id = $id;
if ($user_id != $id) {
    die('hard');
}

//$FullName = $_REQUEST['FullName'];
//$fname = xss_sanitize($_REQUEST['fname']);
//$lname = xss_sanitize($_REQUEST['lname']);
//$gender = xss_sanitize($_REQUEST['gender']);
//
//$website_url = xss_sanitize($_REQUEST['website_url']);
//$small_description = xss_sanitize($_REQUEST['small_description']);
//$YourCountry = xss_sanitize($_REQUEST['YourCountry']);
//$hometown = xss_sanitize($_REQUEST['hometown']);
//
//$city = xss_sanitize($_REQUEST['city']);
$fname = $submit_post_get['fname'];
$lname = $submit_post_get['lname'];
$gender = $submit_post_get['gender'];

$website_url = $submit_post_get['website_url'];
$small_description = $submit_post_get['small_description'];
$YourCountry = $submit_post_get['YourCountry'];
$hometown = $submit_post_get['hometown'];

$city = $submit_post_get['city'];
$city_id = getCityId($city, '', $YourCountry);
if(intval($city_id) == 0){
    $city_id = $submit_post_get['city_id'];
    $city = getCityName($city_id);
}
//if ($_REQUEST['YourBday'] != '') {
//    $YourBdayArray = explode('/', xss_sanitize($_REQUEST['YourBday'])); //m/d/yyyy
if ($submit_post_get['YourBday'] != '') {
    $YourBdayArray = explode('/', $submit_post_get['YourBday']); //m/d/yyyy
    $YourBdaySave = $YourBdayArray[2] . '-' . $YourBdayArray[0] . '-' . $YourBdayArray[1]; // (yyyy-mm-dd)
} else {
    $YourBdaySave = '1000-01-01';
}
$dob = $YourBdaySave;



//$display_age = xss_sanitize($_REQUEST['display_age']);
//$display_yearage = xss_sanitize($_REQUEST['display_yearage']);
//$display_gender = xss_sanitize($_REQUEST['display_gender']);
//$display_username = xss_sanitize($_REQUEST['display_username']);
$display_age = $submit_post_get['display_age'];
$display_yearage = $submit_post_get['display_yearage'];
$display_gender = $submit_post_get['display_gender'];
$display_username = $submit_post_get['display_username'];

$save = array();
$save['employment'] = "";
$save['high_education'] = "";
$save['uni_education'] = "";
$save['intrested_in'] = "";

//$employment = xss_sanitize($_REQUEST['employment']);
//$high_education = xss_sanitize($_REQUEST['education']);
//$intrested_in = intval($_REQUEST['intrested_in']);
//$display_interest = xss_sanitize($_REQUEST['display_interest']);
$employment = $submit_post_get['employment'];
$high_education = $submit_post_get['education'];
$intrested_in = intval($submit_post_get['intrested_in']);
$display_interest = $submit_post_get['display_interest'];

$save['id'] = $user_id;
$save['employment'] = $employment;
$save['high_education'] = $high_education;
$save['intrested_in'] = (is_numeric($intrested_in)) ? $intrested_in : "";
$save['display_interest'] = (is_numeric($display_interest)) ? $display_interest : "";
$save['display_fullname'] = $display_username == 1 ? 0 : 1;
$save['YourEmail'] = $YourEmail;

$notif_total = 0;
//$noti_comment = xss_sanitize($_REQUEST['notify_comment']);
//$noti_friends = xss_sanitize($_REQUEST['notify_friend']);
//$noti_subscrives = xss_sanitize($_REQUEST['notify_subscribe']);
//$noti_reokies = xss_sanitize($_REQUEST['notify_replies']);
$noti_comment = $submit_post_get['notify_comment'];
$noti_friends = $submit_post_get['notify_friend'];
$noti_subscrives = $submit_post_get['notify_subscribe'];
$noti_reokies = $submit_post_get['notify_replies'];

if ($noti_comment == "1") {
    $notif_total += NOTIF_COMMENT;
}
if ($noti_friends == "1") {
    $notif_total += NOTIF_FREIND;
}
if ($noti_subscrives == "1") {
    $notif_total += NOTIF_SUBSCRIBE;
}
if ($noti_reokies == "1") {
    $notif_total += NOTIF_REPLY;
}

if (userEditPersonalInfo($id, $fname, $lname, $website_url, $small_description, $gender, $dob, $display_age, $city_id, $city, $YourCountry, $hometown, $display_gender, $display_yearage)) {
    userSetNotifications($id, $notif_total);
    if (userNameisUnique($id, $new_username)) {
        userEditUsername($id, $new_username);
    }
    userEdit($save);
    echo json_encode($ret);
} else {
    $ret['status'] = 'error';
    echo json_encode($ret);
}