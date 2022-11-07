<?php

 
$submit_post_get = array_merge($request->query->all(),$request->request->all());
session_id($submit_post_get['S']); 
        session_start();
//$bootOptions = array ( "loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);

$expath = '../../';
include_once("../../heart.php");

//header("content-type: application/xml; charset=utf-8");


$id = $_SESSION['id'];
$res = "<user_info>";

$datas = getUserInfo($id);

//var_dump($datas);
/* foreach($datas as $k=>$v)
  {
  echo htmlentities('$res .= "<'.$k.'>".safeXML($datas[\''.$k.'\'])."</'.$k.'>";')."<br>";
  } */
$dobVal = '';
if ($datas['YourBday'] == '0000-00-00') {
    $dobVal = '';
} else {
    $dobVal = $datas['YourBday'];
}
$res .= "<user>";
$res .= "<id>" . $datas['id'] . "</id>";
$res .= "<FullName>" . safeXML($datas['FullName']) . "</FullName>";
$res .= "<fname>" . safeXML($datas['fname']) . "</fname>";
$res .= "<lname>" . safeXML($datas['lname']) . "</lname>";
$res .= "<gender>" . $datas['gender'] . "</gender>";
$res .= "<YourEmail>" . $datas['YourEmail'] . "</YourEmail>";
$res .= "<website_url>" . htmlEntityDecode($datas['website_url']) . "</website_url>";
$res .= "<small_description>" . safeXML($datas['small_description']) . "</small_description>";
$res .= "<YourCountry>" . $datas['YourCountry'] . "</YourCountry>";
$res .= "<hometown>" . htmlEntityDecode($datas['hometown']) . "</hometown>";
$res .= "<city>" . $datas['city'] . "</city>";
$res .= "<display_age>" . $datas['display_age'] . "</display_age>";
$res .= "<YourBday>" . $dobVal . "</YourBday>";
$res .= "<YourUserName>" . $datas['YourUserName'] . "</YourUserName>";
$res .= "<profile_Pic>" . $datas['profile_Pic'] . "</profile_Pic>";
$res .= "<display_age>" . $datas['display_age'] . "</display_age>";
$res .= "<profile_views>" . $datas['profile_views'] . "</profile_views>";

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

//echo $res;