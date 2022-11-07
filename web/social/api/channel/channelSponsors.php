<?php
/*! \file
 * 
 * \brief This api returns channel sponsors
 * 
 * 
 * @param S session id
 * @param cid channel id
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * 
 * @return <b>res</b> JSON list with the following keys:
 * @return <pre> 
 * @return       <b>id</b> tuber id
 * @return       <b>channel_name</b> tuber channel name
 * @return       <b>logo</b> tuber channel logo path
 * @return       <b>cover</b> tuber channel cover path
 * @return       <b>create_ts</b> tuber channel create date and time
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */

$expath = "../";			
header('Content-type: application/json');
include("../heart.php");

$cid = null;
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//if (isset($_REQUEST['cid']))
//{
//        $cid = intval($_REQUEST['cid']);
if (isset($submit_post_get['cid']))
{
        $cid = intval($submit_post_get['cid']);
} 
else{
    die();
}
$channelInfo = channelFromID($cid);

//$userID = mobileIsLogged($_REQUEST['S']);
$userID = mobileIsLogged($submit_post_get['S']);
if( !$userID ) $userID = 0;

$is_owner = 1;
$is_visible = -1;
if ($userid != intval($channelInfo['owner_id'])) {
    $is_owner = 0;
    $is_visible = 1;
    if ($channelInfo['published'] != 1)
    {
        
    }
}


//$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 10;
//$currentpage = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 0;
$limit = isset($submit_post_get['limit']) ? intval($submit_post_get['limit']) : 10;
$currentpage = isset($submit_post_get['page']) ? intval($submit_post_get['page']) : 0;


$channel_sponsors_count = socialSharesGet(array(
    'orderby' => 'share_ts',
    'order' => 'd',
    'is_visible' => $is_visible,
    'entity_id' => $channelInfo['id'],
    'entity_type' => SOCIAL_ENTITY_CHANNEL,
    'share_type' => SOCIAL_SHARE_TYPE_SPONSOR,
    'n_results' => true
));

$TubersConnected = socialSharesGet(array(
    'orderby' => 'share_ts',
    'order' => 'd',
    'limit' => $limit,
    'page' => $currentpage,
    'is_visible' => $is_visible,
    'entity_id' => $channelInfo['id'],
    'entity_type' => SOCIAL_ENTITY_CHANNEL,
    'share_type' => SOCIAL_SHARE_TYPE_SPONSOR
));

$res = array('sponsors' => array(), 'total' => $channel_sponsors_count);
foreach ($TubersConnected as $tuberInfo) {
    $channel = array();
    $channel['id'] = $tuberInfo['id'];
    $channel['channel_name'] = $tuberInfo['channel_name'];
    if ($tuberInfo['logo'] == '') {
        $channel['logo'] = 'media/tubers/tuber.jpg';
    } else {
        $channel['logo'] = 'media/channel/' . $tuberInfo['id'] . '/thumb/' . $tuberInfo['logo'];
    }
    if ($channelInfo['header'] == '') {
        $channel['cover'] = 'media/images/channel/coverphoto.jpg';
    } else {
        $channel['cover'] = 'media/channel/' . $tuberInfo['id'] . '/thumb/' . $tuberInfo['header'];
    }
//    $channel['cover'] = photoReturnchannelHeaderBig($tuberInfo);
    $create_ts = $tuberInfo['share_ts'];
    $channel['create_ts'] = returnSocialTimeFormat($create_ts,3);
    $res['sponsors'][] = $channel;
}
		
echo json_encode($res);