<?php
/*! \file
 * 
 * \brief This api returns all information of a photo
 * 
 * 
 * @param S session id
 * @param size size of the photo
 * @param noCache true/false determines if image should be retrieved from cache
 * @param KeepRatio 1/0 not used
 * @param lang language used
 * @param id photo id
 * @param w image width of the photo
 * 
 * @return list with the following keys:
 * @return <b>returnArr</b> List of photo information (array)
 * @return <pre> 
 * @return       <b>id</b> photo id
 * @return       <b>title</b> photo title
 * @return       <b>description</b> photo description
 * @return       <b>country</b> photo country
 * @return       <b>cityname</b> photo cityname
 * @return       <b>category</b> photo category
 * @return       <b>isPublic</b> photo is Public or not
 * @return       <b>placetakenat</b> photo place taken at
 * @return       <b>keywords</b> photo keywords
 * @return       <b>userId</b> photo user id
 * @return       <b>user</b> photo user name
 * @return       <b>userProfilePic</b> photo user Profile Picture path
 * @return       <b>pdate</b> photo uploaded date 
 * @return       <b>nViews</b> photo number of Views
 * @return       <b>isFav</b>user is favourite yes or no
 * @return       <b>isFriend</b>user is Friend yes or no
 * @return       <b>isFollowed</b>user is Followed yes or no
 * @return       <b>isLiked</b>photo is Liked yes or no
 * @return       <b>myRating</b> user photo rating
 * @return       <b>rating</b> photo average rating
 * @return       <b>nbRating</b> photo number of rating
 * @return       <b>nbComments</b> photo number of Comments
 * @return       <b>upVote</b> photo number of likes
 * @return       <b>photoLink</b> photo link
 * @return       <b>thumbLink</b> photo thumb link
 * @return       <b>photoUrl</b> photo url
 * @return       <b>shareLink</b> photo share Link
 * @return       <b>res</b> result 1 or -1
 * @return       <b>msg</b> error message
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>

 * 
 *  */

//if (isset($_REQUEST['S'])) {
//    session_id($_REQUEST['S']); 
//        session_start();
//}
header('Content-type: application/json');
$expath = "../";
include($expath . "heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//if (isset($_REQUEST['S'])) {
//    $myID = mobileIsLogged($_REQUEST['S']);
if (isset($submit_post_get['S'])) {
    $myID = mobileIsLogged($submit_post_get['S']);
}
$uricurserver = currentServerURL();
$returnArr = array();
if (apiOauth2(array())) {

    $lang = 'en';
    $size = 'm';
    $noCache = false;
    $keepRatio = '1';

//    if (isset($_REQUEST['size'])) {
//        $size = $_REQUEST['size'];
//    }
//    if (isset($_REQUEST['noCache'])) {
//        $noCache = $_REQUEST['noCache'];
//    }
//    if (isset($_REQUEST['keepRatio'])) {
//        $keepRatio = intval($_REQUEST['keepRatio']) ? xss_sanitize($_REQUEST['keepRatio']) : '1';
//    }
//    if (isset($_REQUEST['lang'])) {
//        $lang = setLangGetText($_REQUEST['lang'], true) ? xss_sanitize($_REQUEST['lang']) : 'en';
//    }
    if (isset($submit_post_get['size'])) {
        $size = $submit_post_get['size'];
    }
    if (isset($submit_post_get['noCache'])) {
        $noCache = $submit_post_get['noCache'];
    }
    if (isset($submit_post_get['keepRatio'])) {
        $keepRatio = intval($submit_post_get['keepRatio']) ? $submit_post_get['keepRatio'] : '1';
    }
    if (isset($submit_post_get['lang'])) {
        $lang = setLangGetText($submit_post_get['lang'], true) ? $submit_post_get['lang'] : 'en';
    }

//    if (isset($_REQUEST['id'])) {
//        if (is_numeric($_REQUEST['id'])) {

    if (isset($submit_post_get['id'])) {
        if (is_numeric($submit_post_get['id'])) {
            $id = $submit_post_get['id'];
        }

        $data = getVideoInfo($id);
        $stats = getVideoStats($id);

        VideoIncViews($id);
//        if (isset($_REQUEST['S'])) {
//
//            $vid = $_REQUEST['id'];
        if (isset($submit_post_get['S'])) {

            $vid = $submit_post_get['id'];
            $isfav = "";
            if (userFavoriteAdded($myID, $vid)) {
                $isfav = 'YES';
            } else {
                $isfav = 'NO';
            }
        }
        $returnArr['id'] = $data['id'];
        $returnArr['title'] = safeXML($data['title']);
        $returnArr['description'] = safeXML($data['description']);
        
        $returnArr['country'] = !isset($data['country']) ? '' : $data['country'];
        $returnArr['cityname'] = $data['cityname'];
        $returnArr['category'] = $data['category'];
        $returnArr['isPublic'] = $data['is_public'];
        $returnArr['placetakenat'] = safeXML($data['placetakenat']);
        $returnArr['keywords'] = safeXML($data['keywords']);
        $returnArr['pdate'] = returnSocialTimeFormat($data['pdate']);
        $returnArr['nViews'] = $data['nb_views'];
        $returnArr['cityid'] = $data['cityid'];
    if($data['channelid']=='0'){
        $userinfo = getUserInfo($data['userid']);
        $returnArr['userId'] = $data['userid'];
        $returnArr['user'] = returnUserDisplayName($userinfo);

        if ($userinfo['profile_Pic'] != "") {
            $returnArr['userProfilePic'] = "media/tubers/" . $userinfo['profile_Pic'];
        } else {
            $returnArr['userProfilePic'] = "media/tubers/na.png";
        }
        $returnArr['is_channel']='0';
    }else{
        
        $channelInfo = channelGetInfo($data['channelid']);
  //echo "<pre>";print_r($channelInfo);    
        $returnArr['userId'] = $data['channelid'];
        $returnArr['user'] = $channelInfo['channel_name'];
        if ($channelInfo['logo'] == '') {
                $channel_logo = 'media/tubers/tuber.jpg';
            } else {
                $channel_logo = 'media/channel/' . $channelInfo['id'] . '/thumb/' . $channelInfo['logo'];
            }
        $returnArr['userProfilePic'] = $channel_logo;
        $returnArr['create_ts'] = $channelInfo['create_ts'];
        $returnArr['category'] = $channelInfo['category'];
        $returnArr['country'] = !isset($channelInfo['country']) ? '' : $channelInfo['country'];
        $returnArr['city'] = $channelInfo['city'];
        $returnArr['channel_url'] = ReturnLink('channel/'.$channelInfo['channel_url']);
        $connected_tubers = getConnectedtubers($channelInfo['id']);
        if (( $myID != $channelInfo['owner_id'] ) && ( in_array($myID, $connected_tubers) )) {
            $is_connected = '1';
        }else{
            $is_connected = '0';
        } 
        $returnArr['is_connected']=$is_connected;
        $returnArr['is_channel']='1';
        $is_owner = intval($myID) == intval($channelInfo['owner_id']);
        $returnArr['is_owner'] = $is_owner ? '1' : '0';
    }
    
//        if (isset($_REQUEST['S'])) {
        if (isset($submit_post_get['S'])) {
            $returnArr['isFav'] = $isfav;
            /*
            if (userIsFriend($myID, $data['userid']) || userFreindRequestMade($myID,$data['userid'])) {
                $returnArr['isFriend'] = 'YES';
            } else {
                $returnArr['isFriend'] = 'NO';
            }
            */
            $isfriend = friendRequestOccur($myID, $data['userid']);
            $returnArr['isfriend'] = $isfriend;

            if (userSubscribed($myID, $data['userid'])) {
                $returnArr['isFollowed'] = 'YES';
            } else {
                $returnArr['isFollowed'] = 'NO';
            }
//            $liked = videoVoted($_REQUEST['id'], $myID);
            $liked = videoVoted($submit_post_get['id'], $myID);

            if (!$liked) {
                $returnArr['isLiked'] = 'NO';
            } else {
                if ($liked == 1)
                    $returnArr['isLiked'] = 'YES';
                else if ($liked == -1)
                    $returnArr['isLiked'] = 'NO';
            }

//            $row = videoUserRatingGet(intval($_REQUEST['id']), $myID);
            $row = videoUserRatingGet(intval($submit_post_get['id']), $myID);
            $returnArr['isRated'] = $row ? "1" : "0";
            $returnArr['myRating'] = intval($row['rating_value']);
        }

        $returnArr['rating'] = $stats['rating'];
        $returnArr['nbRating'] = $stats['nb_ratings'];
        $returnArr['nbComments'] = $stats['nb_comments'];
        
        $options1 = array(
            'entity_id' => $data['id'],
            'entity_type' => SOCIAL_ENTITY_MEDIA,
            'like_value' => 1,
            'n_results' => true
        );
	$allLikesnum = socialLikesGet($options1);
        
        $returnArr['upVote'] = intval($allLikesnum);
        $thumbLink = $data['fullpath'] . $data['name'];
        $returnArr['thumbLink'] = resizepic($thumbLink, $size, $noCache, $keepRatio);
        $returnArr['shareLink'] = ReturnVideoUriHashed($data);
        $returnArr['share_image'] = $uricurserver.'/'.$thumbLink;
    }
    
    
    $returnArr['res'] = '1';
    $returnArr['msg'] = _('done');
} else {
    $returnArr['res'] = '-1';
    $returnArr['msg'] = _('Not Authorized');
}
echo json_encode($returnArr);
