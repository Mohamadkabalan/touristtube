<?php
/*! \file
 * 
 * \brief This api returns the connected tuber
 * 
 * 
 * @param S session id
 * @param cid channel id
 * 
 * @return <b>output</b> JSON list with the following keys:
 * @return <pre> 
 * @return       <b>id</b> tuber id
 * @return       <b>fullname</b> tuber full name
 * @return       <b>username</b> user name
 * @return       <b>isfriend</b> tuber is friend
 * @return       <b>isfollowed</b> tuber is followed
 * @return       <b>chatprofilepic</b> tuber chat profile picture path
 * @return       <b>prof_pic</b> user profile picture path
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */
$expath = "../";
header('Content-type: application/json');
include("../heart.php");

$submit_post_get = array_merge($request->query->all(), $request->request->all());

$uid = 0;
if (isset($submit_post_get['S']))
	$uid = $submit_post_get['S'];

$userID = mobileIsLogged($uid);

$res = array();

$cid = 0;

if (isset($submit_post_get['cid']))
{
	$cid = $submit_post_get['cid'];
}

if (!$cid)
{
	echo json_encode(array());
	
	exit;
}

$page = intval($submit_post_get['page']);
$limit = intval($submit_post_get['limit']);
if($limit == 0)
	$limit = 10;


$channelInfo = channelFromID($cid);

$is_owner = false;
$is_visible = -1;

if ($userID != intval($channelInfo['owner_id'])) 
{
	$is_visible = 1;
	$is_owner = true;
}

$connectedUsers = channelConnectedTubersSearch(array('limit' => $limit, 'page' => $page, 'is_visible' => $is_visible, 'channelid' => $channelInfo['id']));
$connectedUsersCount = channelConnectedTubersSearch(array('is_visible' => $is_visible, 'channelid' => $channelInfo['id'], 'n_results' => true));

// echo $connectedUsersCount;
// print_r($connectedUsers); die;

$output = array();

if ($connectedUsers)
	foreach ($connectedUsers as $connectedUser)
	{
		$data = getUserInfo($connectedUser['uid']);
		
		/* code added by sushma mishra on 30-sep-2015 to get fullname using returnUserDisplayName function starts from here */
		/*
		if($connectedUser['FullName'] != '')
		{
			$fullname = $connectedUser['FullName'];
		}
		else
		{
			$fullname = $connectedUser['YourUserName'];
		}
		*/
			
		$fullname = returnUserDisplayName($data);
		/* code added by sushma mishra on 30-sep-2015 ends here */
		// $isfriend = ((userIsFriend($userID, $connectedUser['uid']) || userFreindRequestMade($userID, $connectedUser['uid']) == FRND_STAT_PENDING)?'YES':'NO');
		
		$isfriend = friendRequestOccur($userID, $connectedUser['uid']);
		
		$isfollowed = ((userSubscribed($userID, $connectedUser['uid']))?'YES':'NO');
		
		if ($connectedUser['profile_Pic'] != "")
		{
			$prof_pic = "media/tubers/".$connectedUser['profile_Pic'];
			$chatprofilepic = "/media/tubers/crop_".substr($connectedUser['profile_Pic'], 0, strlen($connectedUser['profile_Pic']) - 3)."png";
		}
		else
		{
			$chatprofilepic = "media/tubers/na.png";
			
			$prof_pic = 'media/tubers/'.($connectedUser['gender'] == 'F'?'s':'').'he.jpg';
		}
		
		$res = array('total' => $connectedUsersCount, 'connections' => array());
		$is_owner = ($userID && intval($connectedUser['uid']) == intval($userID)) ? '1' : '0';
		
		$output[] = array(
				'id' => $connectedUser['uid'],
				'fullname' => $fullname,
				'username' => $connectedUser['YourUserName'],
				'isfriend' => $isfriend,
				'isfollowed' => $isfollowed,
				'chatprofilepic' => $chatprofilepic,
				'prof_pic' => $prof_pic,
				'is_owner' => $is_owner
			);
			
			$res['connections'] = $output;
	}

// $keys = array_keys(array_column($res['connections'], 'id'), $userID);
// $key_value = $keys[0];

$key_value = '';

foreach($res['connections'] as $arr => $key)
{
	foreach($key as $arr1 => $key1)
	{
		if ($key1 == $userID)
		{
			$key_value = $arr;
		}
	}
}

// print_r($res['connections'][$key_value]['id']); die;

if($res['connections'][$key_value]['id'] == $userID)
{
	$temp = $res['connections'][0];
	$res['connections'][0] = $res['connections'][$key_value];
	$res['connections'][$key_value] = $temp;
}
	
//echo "<pre>";print_r($res);

echo json_encode($res);