<?php



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

$dob = date('m/d/Y', strtotime($datas['YourBday']) );
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

    'YourBday'=>$dob,
    'YourUserName'=>$datas['YourUserName'],
    'profile_Pic'=>$profile_pic,
    'display_age'=>$datas['display_age'],
    'display_yearage'=>$datas['display_yearage'],
    'display_gender'=>$datas['display_gender'],

    'occupation'=>$datas['occupation'],
    'employment'=>$datas['employment'],
    'high_education'=>$datas['high_education'],
    'uni_education'=>$datas['uni_education'],
    'display_interest'=>$datas['display_interest'],
    'intrested_in'=>$datas['intrested_in'],
    'display_fullname'=>$datas['display_fullname'],
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