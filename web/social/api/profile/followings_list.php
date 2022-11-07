<?php
/*! \file
 * 
 * \brief This api to see the following of the user
 * 
 * 
 * @param S  session id
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * 
 * @return <b>xml_output</b> List of following information (array):
 * @return <pre> 
 * @return         <b>id</b> following id
 * @return         <b>user_id</b> user id
 * @return         <b>FullName</b> following name
 * @return         <b>fname</b> following first name
 * @return         <b>lname</b> following last name
 * @return         <b>gender</b> following gender
 * @return         <b>YourEmail</b> following Email
 * @return         <b>website_url</b> following website url
 * @return         <b>small_description</b> following small description
 * @return         <b>YourCountry</b> following Country
 * @return         <b>hometown</b> following hometown
 * @return         <b>YourBday</b> following birth day
 * @return         <b>YourUserName</b> following user name
 * @return         <b>profile_Pic</b> following profile Picture path
 * @return         <b>profile_id</b> following profile id
 * @return         <b>display_age</b> following display age on profile
 * @return         <b>display_yearage</b> following display year age on profile
 * @return         <b>display_gender</b> following display gender on profile
 * @return         <b>display_username</b> following display user name on profile
 * @return         <b>isfriend</b> following is friend yes or no
 * @return         <b>isfollowed</b> following is followed yes or no
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */

/*
 * Returns the events sponsored by a given channel.
 * Param S: The session id.
 * Param [limit]: Optional, the max rows to get, default 100.
 * Param [page]: Optional, the current page.
 */

 
//        session_start();

$expath = "../";
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());

//$user_id = $_SESSION['id'];
//$user_id = mobileIsLogged($_REQUEST['S']);
$user_id = mobileIsLogged($submit_post_get['S']);
if( !$user_id ) die();
//if (isset($_REQUEST['limit']))
//    $limit = intval($_REQUEST['limit']);
if (isset($submit_post_get['limit']))
    $limit = intval($submit_post_get['limit']);
else
    $limit = 10;
//if (isset($_REQUEST['page']))
//    $page = intval($_REQUEST['page']);
if (isset($submit_post_get['page']))
    $page = intval($submit_post_get['page']);
else
    $page = 0;

// Get the followers.
$options = array(
    'reverse' => true,
    'userid' => $user_id,
    'limit' => $limit,
    'page' => $page,
    'order' => 'd'
);
//if(isset($_REQUEST['str']) && !empty($_REQUEST['str'])){
//    $options['search_string'] = $_REQUEST['str'];
if(isset($submit_post_get['str']) && !empty($submit_post_get['str'])){
    $options['search_string'] = $submit_post_get['str'];
}
$followers = userSubscriberSearch($options);
// Get the followers count.
$options = array(
    'reverse' => true,
    'userid' => $user_id,
    'n_results' => true,
);
//if(isset($_REQUEST['str']) && !empty($_REQUEST['str'])){
//    $options['search_string'] = $_REQUEST['str'];
if(isset($submit_post_get['str']) && !empty($submit_post_get['str'])){
    $options['search_string'] = $submit_post_get['str'];
}
$followers_count = userSubscriberSearch($options);
$xml = array();
// Display the follower-specific data.
foreach ($followers as $follower):
    $dobVal = '';
    if ($follower['YourBday'] == '0000-00-00' || is_null($follower['YourBday'])) {
        $dobVal = '';
    } else {
        $dobVal = date('m/d/Y', strtotime($follower['YourBday']));
    }
//    if(userIsFriend($user_id,$follower['id']) || userFreindRequestMade($user_id,$follower['id']))
//    {
//        $isfriend='YES';
//    }else{
//        $isfriend='NO';
//    }
    $isfriend = friendRequestOccur($user_id, $follower['id']);
    if(userSubscribed($user_id,$follower['id']))
    {
        $isfollowed='YES';
    }else{
        $isfollowed='NO';
    }
	/*code changed by sushma mishra on 30-sep-2015 to get fullname using returnUserDisplayName function starts from here*/
	$userDetail = getUserInfo($follower['id']);
    $xml[]= array(
        'id'=>$follower['id'],
        'user_id'=>$follower['user_id'],
        //'FullName'=> htmlEntityDecode($follower['FullName']),
		'FullName'=> returnUserDisplayName($userDetail),
        'fname'=>htmlEntityDecode($follower['fname']),
        'lname'=>htmlEntityDecode($follower['lname']),
        'gender'=>$follower['gender'],
        'YourEmail'=>$follower['YourEmail'],
        'website_url'=>htmlEntityDecode($follower['website_url']),
        'small_description'=>htmlEntityDecode($follower['small_description']),
        'YourCountry'=>$follower['YourCountry'],
        'hometown'=>htmlEntityDecode($follower['hometown']),
        'YourBday'=>$dobVal,
        'YourUserName'=>$follower['YourUserName'],
        'profile_Pic'=> "media/tubers/".$follower['profile_Pic'],
        'profile_id'=>$follower['profile_id'],
        'display_age'=>$follower['display_age'],
        'display_yearage'=>$follower['display_yearage'],
        'display_gender'=>$follower['display_gender'],
        'display_username'=>$follower['display_fullname'] == 0 ? "1" : "0",
        'isfriend'=>$isfriend,
        'isfollowed'=>$isfollowed
    );
	/*code changed by sushma mishra on 30-sep-2015 ends here*/
endforeach;
    $result =array(
        'count'=>$followers_count,
        'followings'=>$xml,
    );
    
    echo json_encode($result);
