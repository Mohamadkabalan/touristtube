<?php


$submit_post_get = array_merge($request->query->all(),$request->request->all());
session_id($submit_post_get['S']);
$expath = '../';
include_once("../heart.php");


$userId = userGetID();
$userInfo = getUserInfo($userId);

$userscount = 0;
$userscounthide = 'false';

//$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
//$page = isset($_GET['page']) ? intval($_GET['page']) - 1 : 0;
$limit = intval($request->query->get('vid',10));
$page = intval($request->query->get('page',0));

$options = array('limit' => $limit, 'page' => $page, 'userid' => $userId, 'order' => 'd');
$search_string = $request->request->get('search_string', '');
//if (isset($_POST['search_string'])) {
if ($search_string) {
//    $options['search_string'] = db_sanitize($_POST['search_string']);
    $options['search_string'] = $search_string;
}

$userVideos = newsfeedSearch($options);
//print_r($userVideos);
$res = "<feeds>";

if ($userVideos) {

    $pcount = count($userVideos);

    if (($pcount % $limit) != 0) {
        $pagcount++;
    }

    if ($pagcount == ($page + 1)) {
        $userscounthide = 'true';
    }

    //echo '<div class="userscounthide" data-value="'.$userscounthide.'"></div>';	

    for ($i = 0; $i < count($userVideos); $i++) {
        $videoRecord = $userVideos[$i]['media_row'];
        $commentRecord = @$userVideos[$i]['comment_row'];
        $videoLikeRecord = @$userVideos[$i]['like_row'];
        $shareRecord = @$userVideos[$i]['share_row'];

        $username = returnUserDisplayName($userVideos[$i]);
        $pic = $userVideos[$i]['profile_Pic'];

        $video_photo = ( strstr($videoRecord['type'], 'image') == null ) ? 'video' : 'photo';

        $date = date('Y-m-d', strtotime($userVideos[$i]['feed_ts']));
        $valid = true;
        $feed_ts = strtotime($userVideos[$i]['feed_ts']);
        $feed_user_id = $userVideos[$i]['user_id'];

        switch ($userVideos[$i]['feed_type']) {
            case SOCIAL_UPLOAD_MEDIA:
                $action = " uploaded a new " . $video_photo;
                //$desc = '<span class="NewsFeedCommentText">' . $videoRecord['title'] . '<br/>' . $videoRecord['description'] . '</span>';
                $title = $videoRecord['title'];
                $description = $videoRecord['description'];
                //$date = date ( 'd-m-Y',  strtotime( $videoRecord['pdate'] ));
                break;
            case SOCIAL_LIKE_MEDIA:
                $action = " likes a " . $video_photo;
                $title = $videoRecord['title'];
                $description = $videoRecord['description'];
                break;
            case SOCIAL_LIKE_COMMENT_MEDIA:
                $action = ' likes a comment';
                $title = $videoRecord['title'];
                $description = $videoRecord['description'];
                break;
            case SOCIAL_COMMENT_MEDIA:
                $action = ' posted a comment';
                $title = $videoRecord['title'];
                $description = $videoRecord['description'];
                break;
            case SOCIAL_LIKE_ALBUM:
                $action = ' likes an album';
                $desc = $username . ' likes the album <span style="font-style:italic">' . htmlEntityDecode($videoRecord['title']) . '</span><br/>';
                break;
            case SOCIAL_LIKE_COMMENT_ALBUM:
                $action = ' likes a comment';
                $title = $videoRecord['title'];
                $description = $videoRecord['description'];
                break;
            case SOCIAL_COMMENT_ALBUM:
                $action = ' posted a comment';
                $desc = $username . ' posted a comment on the album <span style="font-style:italic">' . htmlEntityDecode($videoRecord['title']) . '</span>:<br/>';
                $val_db = htmlEntityDecode($commentRecord['comment_text']);
                $desc .= '<span class="NewsFeedCommentText">' . cut_sentence_length($val_db, 100) . '</span>';
                //$date = date ( 'd-m-Y',  strtotime( $commentRecord['comment_date'] ));
                break;
            case SOCIAL_LIKE_WEBCAM:
                $action = " likes a live feed";
                $title = $videoRecord['title'];
                $description = $videoRecord['description'];
                break;
            case SOCIAL_COMMENT_WEBCAM:
                $action = ' posted a comment';
                $title = $videoRecord['title'];
                $description = $videoRecord['description'];
                break;
            case SOCIAL_LIKE_COMMENT_WEBCAM:
                $action = ' likes a comment';
                $title = $videoRecord['title'];
                $description = $videoRecord['description'];
                break;
            case SOCIAL_SHARE_MEDIA:
                $who = getUserInfo($shareRecord['from_user']);
                $action = returnUserDisplayName($who) . " shared a $video_photo";
                $title = $videoRecord['title'];
                $description = $videoRecord['description'];
                //if( strlen( $shareRecord['msg'] ) != 0 ) $desc = $shareRecord['msg'] . '<br/>' . $desc;
                break;
            case SOCIAL_SHARE_ALBUM:
                $who = getUserInfo($shareRecord['from_user']);
                $action = returnUserDisplayName($who) . " shared an album";
                $title = $videoRecord['title'];
                $description = $videoRecord['description'];
                //if( strlen( $shareRecord['msg'] ) != 0 ) $desc = $shareRecord['msg'] . '<br/>' . $desc;
                break;
            case SOCIAL_SHARE_WEBCAM:
                $who = getUserInfo($shareRecord['from_user']);
                $action = returnUserDisplayName($who) . " shared a live cam";
                $title = $videoRecord['title'];
                $description = $videoRecord['description'];
                //if( strlen( $shareRecord['msg'] ) != 0 ) $desc = $shareRecord['msg'] . '<br/>' . $desc;
                break;
            case SOCIAL_SHARED:
                if ($shareRecord['share_type'] == SOCIAL_SHARE_ALBUM) {
                    $action = "shared an album";
                    $title = $videoRecord['title'];
                    $description = $videoRecord['description'];
                    //$desc = " shared an album with {$shareRecord['all_users']}";
                } else if ($shareRecord['share_type'] == SOCIAL_SHARE_MEDIA) {
                    $action = "shared an $video_photo";
                    $title = $videoRecord['title'];
                    $description = $videoRecord['description'];
                    //$desc = "shared an $video_photo with {$shareRecord['all_users']}";
                } else if ($shareRecord['share_type'] == SOCIAL_SHARE_WEBCAM) {
                    $action = "shared a live cam";
                    $title = $videoRecord['title'];
                    $description = $videoRecord['description'];
                    //$desc = "shared a live cam with {$shareRecord['all_users']}";
                }
                break;
            default:
                $valid = false;
                break;
        }

        if (!$valid)
            continue;
        $description = htmlEntityDecode($description);
        //Deatails
        $likes = $videoRecord['like_value'];
        $views = $videoRecord['nb_views'];
        $rating = ceil($videoRecord['rating']);
        $nb_rating = $videoRecord['nb_ratings'];
        $nb_comments = $videoRecord['nb_comments'];
        $nb_shares = $videoRecord['nb_shares'];
        $id = $videoRecord['id'];

        //GET_THUMB_AND_URL
        if (in_array($userVideos[$i]['feed_type'], array(SOCIAL_LIKE_COMMENT_MEDIA, SOCIAL_COMMENT_MEDIA, SOCIAL_UPLOAD_MEDIA, SOCIAL_LIKE_MEDIA))) {
            $url = ( strstr($videoRecord['type'], 'image') == null ) ? ReturnVideoUriHashed($videoRecord) : ReturnPhotoUri($videoRecord);
            $thumb = ( $videoRecord['image_video'] == 'v' ) ? videoReturnSrcSmall($videoRecord) : photoReturnSrcSmall($videoRecord);
            $views_plays = ($videoRecord['image_video'] == 'i') ? _('Views') : _('Plays');
            $view_icon = $videoRecord['image_video'];
        } else if (in_array($userVideos[$i]['feed_type'], array(SOCIAL_COMMENT_ALBUM, SOCIAL_LIKE_COMMENT_ALBUM))) {
            $url = ReturnLink('album-view/' . $id);
            $thumb = ( $videoRecord['image_video'] == 'v' ) ? videoReturnSrcSmall($videoRecord) : photoReturnSrcSmall($videoRecord);
            $views_plays = _('Views');
            $view_icon = 'v';
        } else if (in_array($userVideos[$i]['feed_type'], array(SOCIAL_COMMENT_WEBCAM, SOCIAL_LIKE_COMMENT_WEBCAM, SOCIAL_LIKE_WEBCAM))) {
            $url = ReturnWebcamUri($videoRecord);
            $thumb = ReturnLink($CONFIG['cam']['uploadPath'] . $videoRecord['url'] . '.jpg');
            $views_plays = _('Views');
        } else if (in_array($userVideos[$i]['feed_type'], array(SOCIAL_SHARED, SOCIAL_SHARE_MEDIA, SOCIAL_SHARE_ALBUM, SOCIAL_SHARE_WEBCAM))) {
            if ($shareRecord['share_type'] == SOCIAL_SHARE_MEDIA) {
                $url = ( strstr($videoRecord['type'], 'image') == null ) ? ReturnVideoUriHashed($videoRecord) : ReturnPhotoUri($videoRecord);
                $thumb = ( $videoRecord['image_video'] == 'v' ) ? videoReturnSrcSmall($videoRecord) : photoReturnSrcSmall($videoRecord);
                $views_plays = ($videoRecord['image_video'] == 'i') ? _('Views') : _('Plays');

                $view_icon = $videoRecord['image_video'];
            } else if ($shareRecord['share_type'] == SOCIAL_SHARE_ALBUM) {
                $url = ReturnLink('album-view/' . $id);
                $thumb = ( $videoRecord['image_video'] == 'v' ) ? videoReturnSrcSmall($videoRecord) : photoReturnSrcSmall($videoRecord);
                $views_plays = _('Views');
                $view_icon = $videoRecord['image_video'];
            } else if ($shareRecord['share_type'] == SOCIAL_SHARE_WEBCAM) {
                $url = ReturnWebcamUri($videoRecord);
                $thumb = ReturnLink($CONFIG['cam']['uploadPath'] . $videoRecord['url'] . '.jpg');
                $views_plays = _('Views');
                $view_icon = 'v';
            }
        }

        $view_icon_bg = ($view_icon == 'i') ? 'profilePhotoViewIconLarge' : 'profileVideoViewIconLarge';
        $title = htmlEntityDecode($title);
        $res .= "<feed feed_type='" . $userVideos[$i]['feed_type'] . "' media_type='" . $video_photo . "' feed_uid='" . $userVideos[$i]['user_id'] . "' feed_id='" . $userVideos[$i]['id'] . "' feed_fk='" . $userVideos[$i]['feed_fk'] . "'>";
        if ($feed_user_id == userGetID()) {
            $res .= "<feed_is_mine>true</feed_is_mine>";
            //delete
        } else {
            $res .= "<feed_is_mine>false</feed_is_mine>";
            //follow or report
        }
        $res .= "<user_profile_pic>" . 'media/images/tubers/' . $pic . "</user_profile_pic>";
        $res .= "<username>" . $username . "</username>";
        $res .= "<action>" . safeXML($action) . "</action>";
        $res .= "<NewsFeedProfileUserDate>" . formatPostedDate(strtotime($userVideos[$i]['feed_ts'])) . "</NewsFeedProfileUserDate>";
        $res .= "<feed_id>" . $id . "</feed_id>";
        $res .= "<feed_ts>" . strtotime($userVideos[$i]['feed_ts']) . "</feed_ts>";

        $res .= "<nveiws>" . tt_number_format($views) . "</nveiws>";
        $res .= "<nlikes>" . tt_number_format($likes) . "</nlikes>";
        $res .= "<ncomments>" . tt_number_format($nb_comments) . "</ncomments>";
        $res .= "<nshares>" . tt_number_format($nb_shares) . "</nshares>";
        $res .= "<nb_rating>" . tt_number_format($nb_rating) . "</nb_rating>";

        $res .= "<view_icon_bg>" . $view_icon_bg . "</view_icon_bg>";

        $res .= "<title>" . safeXML(cut_sentence_length($title, 50)) . "</title>";
        $res .= "<description>" . safeXML($desc) . "</description>";
        $res .= "<url>" . urlencode($url) . "</url>";
        $res .= "<thumb>" . urlencode($thumb) . "</thumb>";
        $res .= "<rating>" . $rating . "</rating>";
        $res .= "<date>" . $date . "</date>";
//			$res .= "<obj_id>".$videoRecord['id']."</obj_id>";
        $res .= "</feed>";
    } // end for  
    $res .= "</feeds>";
} // end if 

header("content-type: application/json; charset=utf-8");
//echo $res;
$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $res);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string wit