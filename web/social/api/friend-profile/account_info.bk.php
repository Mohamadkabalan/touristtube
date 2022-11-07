<?php

 
$submit_post_get = array_merge($request->query->all(),$request->request->all());
session_id($submit_post_get['S']); 
        session_start();

$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);

$expath = '../';
include_once("../heart.php");

header("content-type: application/xml; charset=utf-8");


//$id = $_REQUEST['uid'];
$id = $submit_post_get['uid'];
$myID = $_SESSION['id'];

/* if( !userIsLogged() ) die(); */

$userInfo = getUserInfo($id);

$uname = $userInfo['YourUserName'];
$website = htmlEntityDecode($userInfo['website_url']);
$description = htmlEntityDecode($userInfo['small_description']);
$fname = htmlEntityDecode($userInfo['fname']);
$lname = htmlEntityDecode($userInfo['lname']);
$dob = $userInfo['YourBday'];
$hometown = htmlEntityDecode($userInfo['hometown']);
$city_id = $userInfo['city_id'];
$city = getCityName($city_id);
$country = $userInfo['YourCountry'];
$display_age = $userInfo['display_age'];
$gender = $userInfo['gender'];
if ($dob != '0000-00-00') {
    $dob = date('m/d/Y', strtotime($dob));
}else{
    $dob = '';
}
$profile_pic = "media/tubers/" . $userInfo['profile_Pic'];
if ($fname == '')
    list($fname, $lname) = explode(' ', htmlEntityDecode($userInfo['FullName']), 2);

/* $xml_output = "<?xml version=\"1.0\"?>\n"; */
$xml_output = '<users order="friend-profile">';
$xml_output .= '<user>';
$xml_output .= '<uname>' . safeXML($uname) . '</uname>';
$xml_output .= '<website>' . $website . '</website>';
$xml_output .= '<small_dsc>' . safeXML($description) . '</small_dsc>';
$xml_output .= '<fname>' . safeXML($fname) . '</fname>';
$xml_output .= '<lname>' . safeXML($uname) . '</lname>';
$xml_output .= '<dob>' . $dob . '</dob>';
$xml_output .= '<hometown>' . $hometown . '</hometown>';
$xml_output .= '<city>' . $uname . '</city>';
$xml_output .= '<country>' . $coutnry . '</country>';
$xml_output .= '<display_age>' . $display_age . '</display_age>';
$xml_output .= '<gender>' . $gender . '</gender>';
$xml_output .= '<prof_pic>' . $profile_pic . '</prof_pic>';
if (userIsFriend($myID, $id)) {
    $xml_output .= '<is_friend>yes</is_friend>';
} else {
    $xml_output .= '<is_friend>no</is_friend>';
}

if (userSubscribed($myID, $id)) {
    $xml_output .= "<is_followed>yes</is_followed>";
} else {
    $xml_output .= "<is_followed>no</is_followed>";
}


$xml_output .= '</user>';
$xml_output .= '</users>';


echo $xml_output;

