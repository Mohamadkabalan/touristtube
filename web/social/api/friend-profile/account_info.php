<?php
/*! \file
 * 
 * \brief This api to get the user information
 * 
 * 
 * @param S session id
 * @param uid user id
 * 
 * @return <b>xml_output</b> List of user information (array):
 * @return <pre> 
 * @return         <b>uname</b> user name
 * @return         <b>website</b> user website
 * @return         <b>small_dsc</b> user small description
 * @return         <b>fname</b> user first name
 * @return         <b>lname</b> user last name
 * @return         <b>dob</b> user date of birth
 * @return         <b>hometown</b> user hometown
 * @return         <b>city</b> user city
 * @return         <b>country</b> user country
 * @return         <b>display_age</b> user display age on profile
 * @return         <b>display_yearage</b> user display year age on profile
 * @return         <b>gender</b> user gender
 * @return         <b>display_gender</b> user display gender on profile
 * @return         <b>display_interest</b> user display interest on profile
 * @return         <b>display_username</b> user display name on profile
 * @return         <b>employment</b> user employment
 * @return         <b>interestedin</b> user interested in
 * @return         <b>prof_pic</b> user profile pic
 * @return         <b>prof_pic_big</b> user big profile picture
 * @return         <b>is_friend</b> user is friend with whom
 * @return         <b>is_followed</b> user is followed by whom
 * @return         <b>nb_Friends</b> user number of Friends
 * @return         <b>nb_Videos</b> user number of Videos
 * @return         <b>nb_Photos</b> user number of Photos
 * @return         <b>nb_Fav</b> user number of Favourites
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */
//	session_id($_REQUEST['S']);
//        session_start();

	$bootOptions = array ( "loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);

	$expath = '../';
	include_once("../heart.php");
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
	//header("content-type: application/xml; charset=utf-8");
        header('Content-type: application/json');	

	
//        $myID = mobileIsLogged($_REQUEST['S']);
        $myID = mobileIsLogged($submit_post_get['S']);
        if( !$myID ) $myID = 0;
//	$id = $_REQUEST['uid'];
	$id = $submit_post_get['uid'];
	/*if( !userIsLogged() ) die();*/

	$userInfo = getUserInfo( $id );
        
        //echo "<pre>";print_r($userInfo);
        
	$uname = $userInfo['YourUserName'];
	$website = htmlEntityDecode($userInfo['website_url']);
        $description = htmlEntityDecode($userInfo['small_description']);
	$fname = htmlEntityDecode($userInfo['fname']);
	//$lname = htmlEntityDecode($userInfo['lname']);
	$dob = $userInfo['YourBday'];
	$hometown = htmlEntityDecode($userInfo['hometown']);
        $city_id = $userInfo['city_id'];
//        $city = getCityName($city_id);	
        $city = htmlEntityDecode(getCityName($city_id));
	//$country = $userInfo['YourCountry'];
	$display_age = $userInfo['display_age'];
        $display_yearage = $userInfo['display_yearage'];
	$gender = $userInfo['gender'];
	$employment = htmlEntityDecode($userInfo['employment']);
//        $dob = returnSocialTimeFormat( $dob ,3); 
	$profile_pic = "media/tubers/" . $userInfo['profile_Pic'];
        $prof_pic_big = "media/tubers/" . getOriginalPP($userInfo['profile_Pic']);
		
    /*code added by sushma mishra on 30-sep-2015 to get fullname using returnUserDisplayName function starts from here*/    
	//if($fname == '') list($fname,$lname) = explode(' ',htmlEntityDecode($userInfo['FullName']), 2);
	if($fname == '') list($fname,$lname) = explode(' ',returnUserDisplayName($userInfo), 2);
	/*code added by sushma mishra on 30-sep-2015 ends here*/
	
//        if ( userIsFriend($myID,$id) || userFreindRequestMade($myId,$id)){
//            $is_friend = 'yes';
//	}else{
//            $is_friend = 'no';
//	}
        $lname = (($userInfo['lname']!= '') ? htmlEntityDecode($userInfo['lname']) : "");
        $isfriend = friendRequestOccur($myID, $id);
        
	if(userSubscribed($myID,$id)){
            $is_followed = 'yes';
	}else{
            $is_followed = 'no';
	}
        $nb_Friends = userFriendNumber( $id );
        $user_Stats = userGetStatistics( $id );
        $display_gender=$userInfo['display_gender'];
	$display_interest=$userInfo['display_interest'];
        $blocked = userIsProfileBlocked($myID, $id);
        $res = array(
            'uname'=>$uname,
            'website'=>$website,
            'small_dsc'=>str_replace('"', "'", $description),
            'fname'=>$fname,
            'lname'=>$lname,
            'dob'=>$dob,
            'hometown'=>$hometown,
            'city'=>$city,
            'country'=>$userInfo['YourCountry'],
            'display_age'=>$display_age,
            'display_yearage'=>$display_yearage,
            'gender'=>$gender,
            'display_gender'=>$display_gender,
            'display_interest'=>$display_interest,
            'education'=>$userInfo['high_education'],
            'employment'=>$employment,
            'interestedin'=>$userInfo['intrested_in'],        
            'prof_pic'=>$profile_pic,
            'prof_pic_big'=>$prof_pic_big,
            'is_friend'=>$isfriend,
            'is_followed'=>$is_followed,
            'nb_Friends'=>$nb_Friends,
            'nb_Videos'=>$user_Stats['nVideos'],
            'nb_Photos'=>$user_Stats['nImages'],
            'nb_Fav'=>$user_Stats['nFavorites'],
            'nb_Catalogs'=>$user_Stats['nCatalogs'],
            'blocked' => $blocked ? "1" : "0",
            'display_fullname' => $userInfo['display_fullname'] == 1 ? "1" : "0"
        );
		/*code added by sushma mishra on 30-sep-2015 to get fullname using returnUserDisplayName function starts from here*/
            //$res['fullname'] = $userInfo['FullName'];
        $fullname_res = returnUserDisplayName($userInfo);
        $res['fullname'] = trim($fullname_res);
		/*code added by sushma mishra on 30-sep-2015 ends here*/
        foreach ($res as $key => $value) {
            if (is_null($value)) {
                 $res[$key] = "";
            }
        }
        $xml_output[] = $res;

	echo json_encode($xml_output);