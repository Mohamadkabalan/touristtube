<?php

$submit_post_get = array_merge($request->query->all(),$request->request->all());
session_id($submit_post_get['S']);
session_start();
//var_dump($_SESSION); exit;

$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);

$expath = '../';
include_once("../heart.php");

header("content-type: application/xml; charset=utf-8");

$id = $_SESSION['id'];
if (!userIsLogged())
    die();

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
$employment = htmlEntityDecode($userInfo['employment']);
$education = htmlEntityDecode($userInfo['education']);

$interestedIn = getIntresedIn(array('id' => $userInfo['intrested_in']));
$nb_Friends = userFriendNumber($id);

$user_Stats[] = userGetStatistics($id);
if ($dob == '' || $dob == '0000-00-00') {
    $dob = '';
} else {
    $dob = date('d/m/Y', strtotime($dob));
}
$profile_pic = "media/tubers/" . $userInfo['profile_Pic'];
if ($fname == '')
    list($fname, $lname) = explode(' ', htmlEntityDecode($userInfo['FullName']), 2);

/* $xml_output = "<?xml version=\"1.0\"?>\n"; */
$xml_output = '<users order="my-profile">';
$xml_output .= '<user>';
$xml_output .= '<uname>' . $uname . '</uname>';
$xml_output .= '<website>' . $website . '</website>';
$xml_output .= '<small_dsc>' . $description . '</small_dsc>';
$xml_output .= '<fname>' . $fname . '</fname>';
$xml_output .= '<lname>' . $lname . '</lname>';
$xml_output .= '<dob>' . $dob . '</dob>';
$xml_output .= '<hometown>' . $hometown . '</hometown>';
$xml_output .= '<city>' . $city . '</city>';
$xml_output .= '<country>' . $country . '</country>';
$xml_output .= '<display_age>' . $display_age . '</display_age>';
$xml_output .= '<gender>' . $gender . '</gender>';
$xml_output .= '<prof_pic>' . $profile_pic . '</prof_pic>';
$xml_output .= '<work>' . $employment . '</work>';
$xml_output .= '<education>' . $education . '</education>';
$xml_output .= '<interestedin>' . htmlEntityDecode($interestedIn['title']) . '</interestedin>';
$xml_output .= '<nb_Friends>' . $nb_Friends . '</nb_Friends>';
$xml_output .= '<nb_Videos>' . $user_Stats['nVideos'] . '</nb_Videos>';
$xml_output .= '<nb_Photos>' . $user_Stats['nImages'] . '</nb_Photos>';
$xml_output .= '<nb_Fav>' . $user_Stats['nFavorites'] . '</nb_Fav>';
$xml_output .= '</user>';
$xml_output .= '</users>';
echo $xml_output;
