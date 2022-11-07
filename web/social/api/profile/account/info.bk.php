<?php

 
$submit_post_get = array_merge($request->query->all(),$request->request->all());
session_id($submit_post_get['S']); 
        session_start();
//$bootOptions = array ( "loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);

$expath = '../../';
include_once("../../heart.php");

header("content-type: application/xml; charset=utf-8");

$id = $_SESSION['id'];
$res = "<user_info>";

$datas = getUserInfo($id);

//var_dump($datas);
/* foreach($datas as $k=>$v)
  {
  //echo htmlentities('$res .= "<'.$k.'>".safeXML($datas[\''.$k.'\'])."</'.$k.'>";')."<br>";
  //echo htmlentities('$'.$k.' = $_REQUEST[\''.$k.'\'];')."<br>";
  } */
$dob = '';
if ($datas['YourBday'] == '0000-00-00') {
    $dob = '';
} else {
    $dob = date('m/d/Y', strtotime($datas['YourBday']));
}
$profile_pic = "media/tubers/" . $datas['profile_Pic'];

$res .= "<user>";
$res .= "<id>" . safeXML($datas['id']) . "</id>";
$res .= "<FullName>" . safeXML($datas['FullName']) . "</FullName>";
$res .= "<fname>" . safeXML($datas['fname']) . "</fname>";
$res .= "<lname>" . safeXML($datas['lname']) . "</lname>";
$res .= "<gender>" . safeXML($datas['gender']) . "</gender>";
$res .= "<YourEmail>" . safeXML($datas['YourEmail']) . "</YourEmail>";
$res .= "<website_url>" . safeXML($datas['website_url']) . "</website_url>";
$res .= "<small_description>" . safeXML($datas['small_description']) . "</small_description>";
$res .= "<YourCountry>" . safeXML($datas['YourCountry']) . "</YourCountry>";
$res .= "<hometown>" . safeXML($datas['hometown']) . "</hometown>";
$res .= "<city>" . safeXML($datas['city']) . "</city>";

$res .= "<YourBday>" . safeXML($dob) . "</YourBday>";
$res .= "<YourUserName>" . safeXML($datas['YourUserName']) . "</YourUserName>";
$res .= "<profile_Pic>" . safeXML($profile_pic) . "</profile_Pic>";
$res .= "<display_age>" . safeXML($datas['display_age']) . "</display_age>";
$res .= "<display_gender>" . safeXML($datas['display_gender']) . "</display_gender>";

//$res .= "<profile_views>".safeXML($datas['profile_views'])."</profile_views>";
//$res .= "<published>".safeXML($datas['published'])."</published>";
//$res .= "<notifs>".safeXML($datas['notifs'])."</notifs>";
//$res .= "<chkey>".safeXML($datas['chkey'])."</chkey>";
//$res .= "<n_flashes>".safeXML($datas['n_flashes'])."</n_flashes>";
//$res .= "<n_followers>".safeXML($datas['n_followers'])."</n_followers>";
//$res .= "<n_following>".safeXML($datas['n_following'])."</n_following>";
//$res .= "<n_friends>".safeXML($datas['n_friends'])."</n_friends>";
//$res .= "<n_catalogs>".safeXML($datas['n_catalogs'])."</n_catalogs>";
//$res .= "<n_journals>".safeXML($datas['n_journals'])."</n_journals>";
$res .= "<occupation>" . safeXML($datas['occupation']) . "</occupation>";
$res .= "<employment>" . safeXML($datas['employment']) . "</employment>";
$res .= "<high_education>" . safeXML($datas['high_education']) . "</high_education>";
$res .= "<uni_education>" . safeXML($datas['uni_education']) . "</uni_education>";
$res .= "<display_interest>" . safeXML($datas['display_interest']) . "</display_interest>";
$res .= "<intrested_in>" . safeXML($datas['intrested_in']) . "</intrested_in>";
$res .= "<display_fullname>" . safeXML($datas['display_fullname']) . "</display_fullname>";
$res .= "<contact_privacy>" . safeXML($datas['contact_privacy']) . "</contact_privacy>";
$res .= "<search_engine>" . safeXML($datas['search_engine']) . "</search_engine>";
$res .= "<feeds_privacy>" . safeXML($datas['feeds_privacy']) . "</feeds_privacy>";
$res .= "<comment_privacy>" . safeXML($datas['comment_privacy']) . "</comment_privacy>";

$datas = userGetStatistics($id);

$res .= "<nVideos>" . $datas['nVideos'] . "</nVideos>";
$res .= "<nImages>" . $datas['nImages'] . "</nImages>";
$res .= "<nViews>" . $datas['nViews'] . "</nViews>";
$res .= "<nLikes>" . $datas['nLikes'] . "</nLikes>";
$res .= "<nMediaViews>" . $datas['nMediaViews'] . "</nMediaViews>";
$res .= "<nSubscribers>" . $datas['nSubscribers'] . "</nSubscribers>";
$res .= "<nFavorites>" . $datas['nFavorites'] . "</nFavorites>";
//var_dump(getUserVideosCount($id));

$res .= "<notify_comment>" . userNotifyOnComment($id) . "</notify_comment>"; //comment
$res .= "<notify_friend>" . userNotifyOnFreind($id) . "</notify_friend>"; //friend
$res .= "<notify_subscribe>" . userNotifyOnSubscribe($id) . "</notify_subscribe>"; //subscribe
$res .= "<notify_replies>" . userNotifyOnReply($id) . "</notify_replies>"; //notify replies

$res .= "<invites_made>" . userGetInvitesNumber($id) . "</invites_made>";
$res .= "<invites_max>" . INVITES_MAX . "</invites_max>";

$res .= "</user>";
$res .= "</user_info>";

echo $res;
