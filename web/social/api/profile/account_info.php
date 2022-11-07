<?php
/*! \file
 * 
 * \brief This api returns all account information
 * 
 * 
 * @param S session id
 * 
 * @return <b>userInfo</b> List of account information (array)
 * @return <pre> 
 * @return 
 * @return         <b>uname</b> user name
 * @return         <b>website</b> user website
 * @return         <b>small_dsc</b> user small_dsc
 * @return         <b>fname</b> user first name
 * @return         <b>lname</b> user last name
 * @return         <b>dob</b> user date of birth
 * @return         <b>hometown</b> user hometown
 * @return         <b>city</b> user city
 * @return         <b>country</b> user country
 * @return         <b>display_age</b> user display age or not on profile
 * @return         <b>display_yearage</b> user display year age or not on profile
 * @return         <b>gender</b> user gender
 * @return         <b>prof_pic</b> user profile picture
 * @return         <b>work</b> user work
 * @return         <b>display_gender</b> user user display gender age or not on profile
 * @return         <b>display_interest</b> user user display interest age or not on profile
 * @return         <b>education</b> list of education with the following keys: 
 * @return                <b>high_education</b>  user high education
 * @return                <b>uni_education</b>  user uni education
 * @return         <b>interestedin</b> user interested in
 * @return         <b>nb_Friends</b> user number of Friends
 * @return         <b>nb_Videos</b> user number of Videos
 * @return         <b>nb_Photos</b> user number of Photos
 * @return         <b>nb_Fav</b> user number of Fav
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */

$bootOptions = array ( "loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);

$expath = '../';
include_once("../heart.php");

//header("content-type: application/xml; charset=utf-8");
header('Content-type: application/json');
//$id = $_SESSION['id'];
//$id = mobileIsLogged($_REQUEST['S']);
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$id = mobileIsLogged($submit_post_get['S']);
if( !$id ) die();

$userInfo = getUserInfo( $id );
//echo "<pre>";print_r($userInfo);

$uname = $userInfo['YourUserName'];
$website = htmlEntityDecode($userInfo['website_url']);
$description = htmlEntityDecode($userInfo['small_description']);
$fname = htmlEntityDecode($userInfo['fname']);
$lname = htmlEntityDecode($userInfo['lname']);
$dob = $userInfo['YourBday'];
$hometown = htmlEntityDecode($userInfo['hometown']);
$city_id = $userInfo['city_id'];
$city = htmlEntityDecode(getCityName($city_id));
$country = $userInfo['YourCountry'];
$display_age = $userInfo['display_age'];
$display_yearage = $userInfo['display_yearage'];
$gender = $userInfo['gender'];
$employment = htmlEntityDecode($userInfo['employment']);
$education = htmlEntityDecode($userInfo['education']);
$display_gender=$userInfo['display_gender'];
$display_interest= $userInfo['display_interest'];
//$interestedIn = getIntresedIn(array( 'id' => $userInfo['intrested_in']));
$nb_Friends = userFriendNumber( $id );

$user_Stats = userGetStatistics( $id );

$dob = returnSocialTimeFormat( $dob ,3);
$profile_pic = "media/tubers/" . $userInfo['profile_Pic'];
$prof_pic_big = "media/tubers/" . getOriginalPP($userInfo['profile_Pic']);

/*code changed by sushma mishra on 30-sep-2015 to get fullname using returnUserDisplayName function starts from here*/
//if($fname == '') list($fname,$lname) = explode(' ',htmlEntityDecode($userInfo['FullName']), 2);
if($fname == '') list($fname,$lname) = explode(' ',returnUserDisplayName($userInfo), 2);
$xml_output[]=array(
    'uname'=>$uname,
    //'fullname'=>$userInfo['FullName'],
	'fullname'=>returnUserDisplayName($userInfo),
    'website'=>$website,
    'small_dsc'=>$description,
    'fname'=>$fname,
    'lname'=>$lname,
    'dob'=>$dob,
    'hometown'=>$hometown,
    'city'=>$city,
    'country'=>$country,
    'display_age'=>$display_age,
    'display_yearage'=>$display_yearage,
    'gender'=>$gender,
    'prof_pic'=>$profile_pic,
    'prof_pic_big'=>$prof_pic_big,
    'work'=>$employment,
    'display_gender'=>$display_gender,
    'display_interest'=>$display_interest,
    'education'=>array(
        'high_education'=>$userInfo['high_education'],
        'uni_education'=>$userInfo['uni_education'],
    ),
    'interestedin'=>$userInfo['intrested_in'],
    'nb_Friends'=>$nb_Friends,
    'nb_Videos'=>$user_Stats['nVideos'],
    'nb_Photos'=>$user_Stats['nImages'],
    'nb_Fav'=>$user_Stats['nFavorites'],
    'nb_Catalogs'=>$user_Stats['nCatalogs']
);
/*code changed by sushma mishra on 30-sep-2015 ends here*/    
echo json_encode($xml_output);