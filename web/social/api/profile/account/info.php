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
 * @return         <b>id</b> user id
 * @return         <b>FullName</b> user Full Name
 * @return         <b>fname</b> user first name
 * @return         <b>lname</b> user last name
 * @return         <b>gender</b> user gender
 * @return         <b>YourEmail</b> user Your Email
 * @return         <b>website_url</b> user website url
 * @return         <b>small_description</b> user small description
 * @return         <b>YourCountry</b> user Country
 * @return         <b>hometown</b> user hometown
 * @return         <b>city</b> user city
 * @return         <b>YourBday</b> user date of Birthday
 * @return         <b>YourUserName</b> user Your User Name
 * @return         <b>profile_Pic</b> user profile Picture
 * @return         <b>display_age</b> user display age on profile
 * @return         <b>display_yearage</b> user display year age on profile
 * @return         <b>display_gender</b> user display gender on profile
 * @return         <b>occupation</b> user occupation
 * @return         <b>employment</b> user employment
 * @return         <b>education</b> user education
 * @return         <b>display_interest</b> user display interest on profile
 * @return         <b>intrested_in</b> user intrested in
 * @return         <b>display_username</b> user  display display username on profile
 * @return         <b>contact_privacy</b> user  display contact privacy on profile
 * @return         <b>search_engine</b> user search engine
 * @return         <b>feeds_privacy</b> user feeds privacy
 * @return         <b>comment_privacy</b> user comment privacy
 * @return         <b>notify_comment</b> user notify comment
 * @return         <b>notify_friend</b> user notify friend
 * @return         <b>notify_subscribe</b> user notify subscribe
 * @return         <b>notify_replies</b> user notify replies
 * @return         <b>invites_max</b> user invites max
 * @return         <b>nVideos</b> user number of Videos
 * @return         <b>nImages</b> user number of Images
 * @return         <b>nCatalogs</b> user number of Catalogs
 * @return         <b>nFriends</b> user number of Friends
 * @return         <b>nFollowers</b> user number of Followers
 * @return         <b>nFollowings</b> user number of Followings
 * @return         <b>nViews</b> user number of Views
 * @return         <b>nMediaViews</b> user number of Media Views
 * @return         <b>nSubscribers</b> user number of Subscribers
 * @return         <b>nFavorites</b> user number of Favourites
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */


//$bootOptions = array ( "loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);

$expath = '../../';
include_once("../../heart.php");

header('Content-type: application/json');


//$id = $_SESSION['id'];
//$id = mobileIsLogged($_REQUEST['S']);
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$id = mobileIsLogged($submit_post_get['S']);
if( !$id ) die();
//$res = "<user_info>";

$datas = getUserInfo($id);

//print_r($datas);
/*foreach($datas as $k=>$v)
{
	//echo htmlentities('$res .= "<'.$k.'>".safeXML($datas[\''.$k.'\'])."</'.$k.'>";')."<br>";	
	//echo htmlentities('$'.$k.' = $_REQUEST[\''.$k.'\'];')."<br>";	
}*/

/*	
$res .= "<user>";
$res .= "<id>".safeXML($datas['id'])."</id>";
$res .= "<FullName>".safeXML($datas['FullName'])."</FullName>";
$res .= "<fname>".safeXML($datas['fname'])."</fname>";
$res .= "<lname>".safeXML($datas['lname'])."</lname>";
$res .= "<gender>".safeXML($datas['gender'])."</gender>";
$res .= "<YourEmail>".safeXML($datas['YourEmail'])."</YourEmail>";
$res .= "<website_url>".safeXML($datas['website_url'])."</website_url>";
$res .= "<small_description>".safeXML($datas['small_description'])."</small_description>";
$res .= "<YourCountry>".safeXML($datas['YourCountry'])."</YourCountry>";
$res .= "<hometown>".safeXML($datas['hometown'])."</hometown>";
$res .= "<city>".safeXML($datas['city'])."</city>";

$res .= "<YourBday>".safeXML($dob)."</YourBday>";
$res .= "<YourUserName>".safeXML($datas['YourUserName'])."</YourUserName>";
$res .= "<profile_Pic>".safeXML($profile_pic)."</profile_Pic>";
$res .= "<display_age>".safeXML($datas['display_age'])."</display_age>";
$res .= "<display_gender>".safeXML($datas['display_gender'])."</display_gender>";

//$res .= "<profile_views>".safeXML($datas['profile_views'])."</profile_views>";
//$res .= "<published>".safeXML($datas['published'])."</published>";

//$res .= "<notifs>".safeXML($datas['notifs'])."</notifs>";
//$res .= "<chkey>".safeXML($datas['chkey'])."</chkey>";
//$res .= "<n_flashes>".safeXML($datas['n_flashes'])."</n_flashes>";
//$res .= "<n_journals>".safeXML($datas['n_journals'])."</n_journals>";
$res .= "<occupation>".safeXML($datas['occupation'])."</occupation>";
$res .= "<employment>".safeXML($datas['employment'])."</employment>";
$res .= "<high_education>".safeXML($datas['high_education'])."</high_education>";
$res .= "<uni_education>".safeXML($datas['uni_education'])."</uni_education>";
$res .= "<display_interest>".safeXML($datas['display_interest'])."</display_interest>";
$res .= "<intrested_in>".safeXML($datas['intrested_in'])."</intrested_in>";
$res .= "<display_fullname>".safeXML($datas['display_fullname'])."</display_fullname>";
$res .= "<contact_privacy>".safeXML($datas['contact_privacy'])."</contact_privacy>";
$res .= "<search_engine>".safeXML($datas['search_engine'])."</search_engine>";
$res .= "<feeds_privacy>".safeXML($datas['feeds_privacy'])."</feeds_privacy>";
$res .= "<comment_privacy>".safeXML($datas['comment_privacy'])."</comment_privacy>";

$datas = userGetStatistics($id);

$res .= "<nVideos>".$datas['nVideos']."</nVideos>";
$res .= "<nImages>".$datas['nImages']."</nImages>";
$res .= "<nViews>".$datas['nViews']."</nViews>";
$res .= "<nLikes>".$datas['nLikes']."</nLikes>";
$res .= "<nMediaViews>".$datas['nMediaViews']."</nMediaViews>";
$res .= "<nSubscribers>".$datas['nSubscribers']."</nSubscribers>";
$res .= "<nFavorites>".$datas['nFavorites']."</nFavorites>";


//var_dump(getUserVideosCount($id));

$res .= "<notify_comment>".userNotifyOnComment($id)."</notify_comment>"; //comment
$res .= "<notify_friend>".userNotifyOnFreind($id)."</notify_friend>"; //friend
$res .= "<notify_subscribe>".userNotifyOnSubscribe($id)."</notify_subscribe>"; //subscribe
$res .= "<notify_replies>".userNotifyOnReply($id)."</notify_replies>"; //notify replies

$res .= "<invites_made>".userGetInvitesNumber($id)."</invites_made>"; 
$res .= "<invites_max>".$datas['invites_max']."</invites_max>"; 

$res .= "</user>";
$res .= "</user_info>";

echo $res;
*/
if( ($datas['YourBday'])=='0000-00-00' || is_null($datas['YourBday'])){
    $dob="";
}else{
    $dob = date('m/d/Y', strtotime($datas['YourBday']) );
}
$profile_pic = "media/tubers/" . $datas['profile_Pic'];
$notify_comment = (htmlEntityDecode(userNotifyOnComment($id))== "") ? "0" : "1";
$notify_friend = (htmlEntityDecode(userNotifyOnFreind($id)) == "") ? "0" : "1";
$notify_subscribe = (htmlEntityDecode(userNotifyOnSubscribe($id)) == "") ? "0" : "1";
$notify_reply = (htmlEntityDecode(userNotifyOnReply($id)) == "") ? "0" : "1";
//var_dump(getUserVideosCount($id));

/*code changed by sushma mishra on 30-sep-2015 to get fullname using returnUserDisplayName function starts from here*/
$res[] = array(
    'id'=>$datas['id'],
    //'FullName'=>$datas['FullName'],
	'FullName'=>returnUserDisplayName($datas),
    'fname'=>$datas['fname'],
    'lname'=>$datas['lname'],
    'gender'=>$datas['gender'],
    'YourEmail'=>$datas['YourEmail'],
    'website_url'=>$datas['website_url'],
    'small_description'=>str_replace('"', "'", $datas['small_description']),
    'YourCountry'=>$datas['YourCountry'],
    'hometown'=>$datas['hometown'],
    'city'=>$datas['city'],
    'city_id'=>$datas['city_id'],
    'YourBday'=>$dob,
    'YourUserName'=>$datas['YourUserName'],
    'prof_pic'=>$profile_pic,
    'display_age'=>$datas['display_age'],
    'display_yearage'=>$datas['display_yearage'],
    'display_gender'=>$datas['display_gender'],

    'occupation'=>$datas['occupation'],
    'employment'=>$datas['employment'],
    'education'=>$datas['high_education'],
    'display_interest'=>$datas['display_interest'],
    'intrested_in'=>$datas['intrested_in'],
    'display_username'=> $datas['display_fullname'] == 0 ? "1" : "0",
    'contact_privacy'=>$datas['contact_privacy'],
    'search_engine'=>$datas['search_engine'],
    'feeds_privacy'=>$datas['feeds_privacy'],
    'comment_privacy'=>$datas['comment_privacy'],

    'notify_comment'=>$notify_comment, //comment
    'notify_friend'=>$notify_friend, //friend
    'notify_subscribe'=>$notify_subscribe, //subscribe
    'notify_replies'=>$notify_reply, //notify replies

    'invites_max'=>$datas['invites_max'],
);
/*code changed by sushma mishra on 30-sep-2015 ends here*/ 

$datas = userGetStatistics($id,2);
$res[0]['nVideos'] =$datas['nVideos'];
$res[0]['nImages']=$datas['nImages'];
$res[0]['nCatalogs']=$datas['nCatalogs'];
$res[0]['nFriends']=$datas['nFriends'];
$res[0]['nFollowers']=$datas['nFollowers'];
$res[0]['nFollowings']=$datas['nFollowings'];
$res[0]['nViews']=$datas['nViews'];
$res[0]['nMediaViews']=$datas['nMediaViews'];
$res[0]['nSubscribers']=$datas['nSubscribers'];
$res[0]['nFavorites']=$datas['nFavorites'];
echo json_encode($res);