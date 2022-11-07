<?php
/*! \file
 * 
 * \brief This api returns all information of a video
 * 
 * 
 * @param S session id
 * @param size size of the video
 * @param noCache true/false determines if image should be retrieved from cache
 * @param KeepRatio 1/0 not used
 * @param lang language used
 * @param id video id
 * @param w image width of the video
 * 
 * @return <b>returnArr</b> List of video information (array)
 * @return <pre> 
 * @return        <b>id</b> video id
 * @return        <b>title</b> video title
 * @return        <b>description</b> video description
 * @return        <b>userId</b> video user id
 * @return        <b>user</b> video user name
 * @return        <b>country</b> video country
 * @return        <b>cityname</b> video cityname
 * @return        <b>category</b> video category
 * @return        <b>isPublic</b> video is Public or not
 * @return        <b>placetakenat</b> video place taken at
 * @return        <b>keywords</b> video keywords
 * @return        <b>userProfilePic</b> video user Profile Picture
 * @return        <b>pdate</b> video uploaded date 
 * @return        <b>nViews</b> video number of Views
 * @return        <b>duration</b> video duration
 * @return        <b>isFav</b>user is favourite yes or no
 * @return        <b>isFriend</b>user is Friend yes or no
 * @return        <b>isFollowed</b>user is Followed yes or no
 * @return        <b>isLiked</b>video is Liked yes or no
 * @return        <b>myRating</b> user video rating
 * @return        <b>rating</b> video average rating
 * @return        <b>nbRating</b> video number of rating
 * @return        <b>nbComments</b> video number of Comments
 * @return        <b>upVote</b> video number of likes
 * @return        <b>videoLink</b> video link
 * @return        <b>thumbLink</b> video thumb link
 * @return        <b>videoUrl</b> video url
 * @return        <b>shareLink</b> video share Link
 * @return        <b>res</b> result 1 or -1
 * @return        <b>msg</b> error message
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>

 * 
 *  */
//if (isset($_REQUEST['S'])) {
//    session_id($_REQUEST['S']); 
//        session_start();
//}
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
//        $size = xss_sanitize($_REQUEST['size']);
//    }
//    if (isset($_REQUEST['noCache'])) {
//        $noCache = xss_sanitize($_REQUEST['noCache']);
//    }
//    if (isset($_REQUEST['keepRatio'])) {
//        $keepRatio = intval(xss_sanitize($_REQUEST['keepRatio'])) ? xss_sanitize($_REQUEST['keepRatio']) : '1';
//    }
//    if (isset($_REQUEST['lang'])) {
//        $lang = setLangGetText(xss_sanitize($_REQUEST['lang']), true) ? xss_sanitize($_REQUEST['lang']) : 'en';
//    }
//
//    if (isset($_REQUEST['id'])) {
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
    if (isset($submit_post_get['id'])) {
        $id_get = $submit_post_get['id'];
//        $id_get = $request->query->get('id','');
//        if (is_numeric($_GET['id'])) {
        if (is_numeric($id_get)) {
            $id = $id_get;
        }

        $data = getVideoInfo($id);
        VideoIncViews($id);
        $stats = getVideoStats($id);
        $videoResolutions = array();
        $dimenssions = getVideoResolutionsComplete($id, $path);
        foreach ($dimenssions as $dimens) {
            $videoResolutions[] = array(
                "dimensions" => array(
                    "width" => $dimens['w'],
                    "height" => $dimens['h']
                ),
                "videoLink" => $dimens['0']
            );
        }
        //var_dump($dimenssions);
        $videoPath = "";
        $tempW = 0;
        
//        if (isset($_GET['w'])) {
        $w_get = $submit_post_get['w'];
//        $w_get = $request->query->get('w','');
        
        if ($w_get) {
//            $wint = intval($_GET['w']);
            $wint = intval($w_get);

            $difference = array();
            $sizes = array();

            $differenceabs = array();

            foreach ($dimenssions as $dim) {
                $difference[] = $dim['w'] - $wint;
                $sizes[] = $dim['w'];
            }

            foreach ($difference as $diff) {
                if ($diff < 0) {
                    $differenceabs[] = intval(round(abs($diff / 3)));
                } else {
                    $differenceabs[] = $diff;
                }
            }
            $rightwidth = $sizes[array_search(min($differenceabs), $differenceabs)];
            foreach ($dimenssions as $dimens) {
                if ($dimens['w'] == $rightwidth) {
                    $videoPath = $dimens[0];
                }
            }
        }
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
        $returnArr['duration'] = $data['duration'];
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
            $is_connected = "1";
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
            if (userIsFriend($myID, $data['userid']) || userFreindRequestMade($myID, $data['userid'])) {
                $returnArr['isFriend'] = 'YES';
            } else {
                $returnArr['isFriend'] = 'NO';
            }*/
            
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
        $returnArr['videoLink'] = $videoPath;
        $thumbLink = substr(getVideoThumbnail($data['id'], $path . $data['fullpath'], 0), strlen($path));
        $returnArr['thumbLink'] = resizepic($thumbLink, $size, $noCache, $keepRatio);
        $returnArr['videoUrl'] = $data['video_url'];
        $returnArr['shareLink'] = ReturnVideoUriHashed($data);
        $returnArr['share_image'] = $uricurserver.'/'.$thumbLink;
        $returnArr['resolutions'] = $videoResolutions;
    }

    $returnArr['res'] = '1';
    $returnArr['msg'] = _('done');
} else {
    $returnArr['res'] = '-1';
    $returnArr['msg'] = _('Not Authorized');
}
echo json_encode($returnArr);
