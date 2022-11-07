<?php
$expath = "../";			
//header("content-type: application/xml; charset=utf-8");  
header('Content-type: application/json');
include("../heart.php");

$news_feed = newsfeedGroupingLogSearch(array(
    'limit' => 10,
    'page' => 0,
    'orderby' => 'feed_ts',
    'entity_type' => SOCIAL_ENTITY_POST,
    'entity_id' => $post_id,
    'is_visible' => $is_owner_visible,
    'order' => 'd',
    'channel_id' => $channel_id_owner
));

foreach ($news_feed as $news) {

//    debug($news);
//    exit;
    if ($news['action_type'] == SOCIAL_ACTION_RATE) {
        continue;
    }
    $commentsNumber = 0;
    $likeNumber = 0;
    $sharesNumber = 0;
    $owner_entity = $news['owner_id'];
    $channel_entity_id = $news['channel_id'];

    $is_owner_join = 0;
    if ($owner_entity == $userid) {
        $is_owner_join = 1;
    }
    $entity_chid = 0;
    if (intval($news['media_row']['channelid']) > 0) {
        $entity_chid = $news['media_row']['channelid'];
    } else if (intval($news['media_row']['channel_id']) > 0) {
        $entity_chid = $news['media_row']['channel_id'];
    }
    $action_row_count = $news['action_row_count'];
    $action_row_other = $news['action_row_other'];

    if ($entity_chid > 0) {
        $entity_charray = channelGetInfo($entity_chid);
        if ($entity_charray['owner_id'] == $userid) {
            $entity_ch_name_log = htmlEntityDecode($entity_charray['channel_name']);
            $entity_chlogo = ($entity_charray['logo']) ? photoReturnchannelLogo($entity_charray) : ReturnLink('/media/tubers/tuber.jpg');
            $entity_churl = ReturnLink("/channel/" . $entity_charray['channel_url']);
        } else {
            $entity_chid = 0;
        }
    }
    $action_array = array();
    $logo_src_log = $logo_src;
    $channel_url_log = $channel_url;
    $channel_name_log = $channel_name;
    if ($channel_entity_id != $channel_id_owner && $news['action_type'] != SOCIAL_ACTION_SPONSOR && $news['action_type'] != SOCIAL_ACTION_RELATION_SUB && $news['action_type'] != SOCIAL_ACTION_RELATION_PARENT) {
        $is_owner_join = 0;
        $channel_entity_array = channelGetInfo($channel_entity_id);
        $channel_name_log = htmlEntityDecode($channel_entity_array['channel_name']);
        $logo_src_log = ($channel_entity_array['logo']) ? photoReturnchannelLogo($channel_entity_array) : ReturnLink('/media/tubers/tuber.jpg');
        $channel_url_log = ReturnLink("/channel/" . $channel_entity_array['channel_url']);
    } else if ($news['action_type'] == SOCIAL_ACTION_SPONSOR || $news['action_type'] == SOCIAL_ACTION_RELATION_SUB || $news['action_type'] == SOCIAL_ACTION_RELATION_PARENT) {
        $is_owner_join = 0;
        $channel_entity_array = channelGetInfo($news['user_id']);
        $channel_name_log = htmlEntityDecode($channel_entity_array['channel_name']);
        $logo_src_log = ($channel_entity_array['logo']) ? photoReturnchannelLogo($channel_entity_array) : ReturnLink('/media/tubers/tuber.jpg');
        $channel_url_log = ReturnLink("/channel/" . $channel_entity_array['channel_url']);
    }

    $report_buts = '';
    $enable_button_name = _('enable shares & comments');
    switch ($news['action_row']['entity_type']) {
        case SOCIAL_ENTITY_EVENTS_LOCATION:
        case SOCIAL_ENTITY_EVENTS_DATE:
        case SOCIAL_ENTITY_EVENTS_TIME:
        case SOCIAL_ENTITY_EVENTS:
            $report_buts = '_event';
            if ($is_owner == 1) {
                $user_can_join_check = 1;
            } else if (checkChannelPrivacyExtand($news['media_row']['channelid'], PRIVACY_EXTAND_TYPE_EVENTJOIN, $userid, 0)) {
                $user_can_join_check = 1;
            } else {
                $user_can_join_check = 0;
            }
            if ($news['action_type'] == SOCIAL_ACTION_EVENT_CANCEL) {
                $enable_button_name = _('enable shares');
            }
            if ($news['action_type'] == SOCIAL_ACTION_SPONSOR) {
                $channelPrivacyArray = GetChannelNotifications($news['media_row']['channelid']);
                $channelPrivacyArray = $channelPrivacyArray[0];
            }
            break;

        case SOCIAL_ENTITY_BROCHURE:
            $report_buts = '_brochure';
            break;

        case SOCIAL_ENTITY_NEWS:
            $report_buts = '_news';
            break;

        case SOCIAL_ENTITY_CHANNEL:
            $report_buts = '_channel';
            if ($news['action_type'] == SOCIAL_ACTION_SPONSOR) {
                $channelPrivacyArray = GetChannelNotifications($news['media_row']['channelid']);
                $channelPrivacyArray = $channelPrivacyArray[0];
            }
            break;

        case SOCIAL_ENTITY_CHANNEL_INFO:
        case SOCIAL_ENTITY_CHANNEL_PROFILE:
        case SOCIAL_ENTITY_CHANNEL_SLOGAN:
        case SOCIAL_ENTITY_CHANNEL_COVER:
            $report_buts = '_channel';
            $enable_button_name = _('enable comments');
            break;

        case SOCIAL_ENTITY_MEDIA:
            if ($news['media_row']['image_video'] == "v") {
                $report_buts = '_video';
            } else {
                $report_buts = '_photo';
            }
            break;

        case SOCIAL_ENTITY_POST:
            if ($news['media_row']['post_type'] == SOCIAL_POST_TYPE_VIDEO) {
                $report_buts = '_postvideo';
            } else if ($news['media_row']['post_type'] == SOCIAL_POST_TYPE_PHOTO) {
                $report_buts = '_postphoto';
            } else {
                $report_buts = '_content';
            }
            break;

        case SOCIAL_ENTITY_ALBUM:
            $report_buts = '_album';
            break;

        default:
            $report_buts = '_content';
            break;
    }

    $social_can_share = true;
    $social_can_comment = true;
    $social_can_rate = true;
    if ($is_owner == 1) {
        $social_can_share = true;
        $social_can_comment = true;
        $social_can_rate = true;
    } else if ($channelPrivacyArray['privacy_sharescomments_content'] == 0) {
        $social_can_share = false;
        $social_can_comment = false;
    } else if (( $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_LOCATION || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_DATE || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_TIME ) && $channelPrivacyArray['privacy_sharescomments'] == 0) {
        $social_can_share = false;
        $social_can_comment = false;
    } else if ($news['action_row']['entity_type'] != SOCIAL_ENTITY_CHANNEL && $channelPrivacyArray['privacy_social'] == 0) {
        $social_can_share = false;
        $social_can_comment = false;
        $social_can_rate = false;
    } else if (( $news['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL || $news['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_INFO || $news['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_PROFILE || $news['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_SLOGAN || $news['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_COVER ) && $channelPrivacyArray['privacy_sharing'] == 0) {
        $social_can_share = false;
    }

    $data_action_id = $news['action_row']['entity_id'];
    $data_action_type = $news['action_row']['entity_type'];
    if ($news['action_type'] == SOCIAL_ACTION_UPDATE) {
        $data_action_id = $news['action_id'];
        if ($news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_LOCATION || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_DATE || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_TIME) {
            $data_action_type = SOCIAL_ENTITY_EVENTS;
        }
    }
    if (strlen($channel_name_log) > 32) {
        $channel_name_log = substr($channel_name_log, 0, 32) . ' ...';
    }
    // Variable to show the hidden-state-overlay div if the item is hidden onload.
    $is_hidden_state = '';
    $is_hidden_social = '';

    if ($is_owner == 1) {
        if (checkNewsfeedHided($news['id'], $channelInfo['owner_id'])) {
            $is_hidden_state = 'display:block;';
            $is_hidden_social = 'display:none;';
        }
    }
    // Variable to check if the "enable shares and comments" is enabled.
    $enable_shares_comments = array(
        'data_status' => 1,
        'class' => ''
    );
    // Case: Events, brochures, news.
    if (isset($news['media_row']['enable_share_comment']) && $news['media_row']['enable_share_comment'] == 0) {
        $enable_shares_comments['data_status'] = 0;
        $enable_shares_comments['class'] = ' inactive';
        // Case photo/video.
    } else if ((isset($news['media_row']['can_share']) || isset($news['media_row']['can_comment'])) && ($news['media_row']['can_share'] == 0 || $news['media_row']['can_comment'] == 0)) {
        $enable_shares_comments['data_status'] = 0;
        $enable_shares_comments['class'] = ' inactive';
    }

    $div_val .= '<div class="log_item_list">';

    $div_val .= '<div class="line1"></div><div class="line2"></div>';
    $div_val .= '<div data-id="' . $news['id'] . '" class="log_top_arrow"></div>';
    $div_val .= '
                                                <div class="log_top_buttons_container">
                                                    <div data-id="' . $news['id'] . '" data-entity-id="' . $news['media_row']['id'] . '" data-entity-type="' . $news['entity_type'] . '" id="log_hidden_buttons_' . $news['id'] . '" class="log_hidden_buttons">';
    if ($is_owner == 1) {
        if ($is_owner_join == 1) {
            $div_val .= '<div class="overdatabutenable_log log_top_button" data-status="' . $enable_shares_comments['data_status'] . '">
                                                                <div class="overdatabutntficon' . $enable_shares_comments['class'] . '"></div> 
                                                                <div class="overdatabutntftxt marginleft12">' . $enable_button_name . '</div>
                                                            </div>

                                                            <div class="seperator_button"></div>
                                                            <div class="log_top_button" id="remove_button_log">' . _('remove') . '</div>
                                                            <div class="seperator_button"></div>';
        }
        $div_val .= '<div class="log_top_button" id="hide_button_log">' . _('hide') . '</div>';
    } else {
        if (userIsLogged() && $userIschannel == 0):
            $div_val .= '<a href="#sharepopup" rel="nofollow" id="report_button_log_viewer' . $report_buts . '" class="log_top_button report_button_log_reason">' . _('report') . '</a>
                                                               <div class="seperator_button"></div>';
        endif;
        $div_val .= '<div class="log_top_button hide_button_log_viewer">' . _('hide') . '</div>';
    }
    $div_val .= '</div>
                                                </div>';

    // Commented on an image or video .
    if (($news['action_type'] == SOCIAL_ACTION_COMMENT || $news['action_type'] == SOCIAL_ACTION_SHARE || $news['action_type'] == SOCIAL_ACTION_UPLOAD) && $news['action_row']['entity_type'] == SOCIAL_ENTITY_MEDIA) {
        $footer="";
        // Prepare the overhead text.
        $overhead_text = '';
        $impathss = photoReturnSrcSmall($news['media_row']);
        $media_video_type = 'photo';
        if ($news['media_row']['image_video'] == "v") {
            $media_video_type = 'video';
            $enlarge_class = 'enlarge_video';
            $media_uri = ReturnVideoUriHashed($news['media_row']);
        } else {
            $media_uri = ReturnPhotoUri($news['media_row']);
            $enlarge_class = 'enlarge_photo';
        }
        if ($news['action_type'] == SOCIAL_ACTION_UPLOAD) {
            $staticaction = "%s added a";
            if($action_row_count>1){                                
                $staticaction = "%s added %s";
                array_unshift($action_array, $action_row_count);                           
            }            
            $action_text = "$staticaction new ". $media_video_type;
            $album = mediaGetCatalog($news['media_row']['id']);
            if (!$album)
                $album = array();
            if (sizeof($album) > 0) {
                $media_uri_album = ReturnAlbumUri($album);
                $media_uri_str_album = '<a href="' . $media_uri_album . '" target="_blank">';
                $action_array[] = $media_uri_str_album . '<span class="yellow_font">(' . htmlEntityDecode($album['catalog_name']) . ')</span></a>';
                $action_text .= ' to the album %s';
            } else {
                $media_uri_str = '<a href="' . $media_uri . '" target="_blank">';
                $action_array[] = $media_uri_str . '<span class="yellow_font">(' . htmlEntityDecode($news['media_row']['title']) . ')</span></a>';
                $action_text .= ' %s';
            }
        } else if ($news['action_type'] == SOCIAL_ACTION_SHARE) {
            $action_text = "%s shared a " . $media_video_type;
            $overhead_text = htmlEntityDecode($news['action_row']['msg']);
            $overhead_text = (strlen($overhead_text) > 153) ? substr($overhead_text, 0, 150) . '...' : $overhead_text;
            $media_uri_str = '<a href="' . $media_uri . '" target="_blank">';
            $action_array[] = $media_uri_str . '<span class="yellow_font">(' . htmlEntityDecode($news['media_row']['title']) . ')</span></a>';
            $action_text .= ' %s';
        } else if ($news['action_type'] == SOCIAL_ACTION_COMMENT) {
            $action_text = '%s commented on a ' . $media_video_type;
            $overhead_text = htmlEntityDecode($news['action_row']['comment_text']);
            $media_uri_str = '<a href="' . $media_uri . '" target="_blank">';
            $action_array[] = $media_uri_str . '<span class="yellow_font">(' . htmlEntityDecode($news['media_row']['title']) . ')</span></a>';
            $action_text .= ' %s';
        }
        array_unshift($action_array, '<a href="' . $channel_url_log . '" class="social_link_a"><strong>' . $channel_name_log . '</strong></a>');
        $action_text_display = vsprintf(_($action_text), $action_array);
        $i = 0;
            $footer = '<br /><div class="log_media_footer">';
            // Run through the items and display them according to their type (image / video).
            foreach ($action_row_other as $item){
                if($item['media_row']['id'] != $news['entity_id'] ){
                    $thumbnail = photoReturnThumbSrc($item['media_row']);
                    if ($item['media_row']['image_video'] == "v") {
                        $enlarge_classother = 'enlarge_video';
                        $media_uriother = ReturnVideoUriHashed($item['media_row']);
                    } else {
                        $enlarge_classother = 'enlarge_photo';
                        $media_uriother = ReturnPhotoUri($item['media_row']);
                    }
                    if ($i == 0){
                        $footer .= '<a class="footer_imagea" href="' . $media_uriother . '" target="_blank">';
                        $footer .= '<img class="footer_image" src="' . $thumbnail . '" />';
                    }else{
                        $footer .= '<a class="footer_imagea" style="margin-left: 2px;" href="' . $media_uriother . '" target="_blank">';
                        $footer .= '<img class="footer_image" src="' . $thumbnail . '" />';
                    }
                    $footer .= '<div class="enlarge footer_enlarge ' . $enlarge_classother . '"></div></a>';

                    $i++;
                }
            }
            $footer .= '</div>';
            
        $div_val .= '                                                
                                                <div class="log_channel_header">
                                                    <div class="arrow"></div>
                                                    <a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
                                                    <div class="log_header_text">' . $action_text_display . '</div><br />
                                                    <div class="log_header_time">' . formatDateAsString(strtotime($news['feed_ts'])) . '</div>
                                                    <div id="hidden_header_' . $news['id'] . '" class="hidden_header" style="' . $is_hidden_state . '"></div>
                                                </div>';
        if ($overhead_text != '') {
            $div_val .= '<div class="log_overhead_text">"' . $overhead_text . '"</div>';
        }
        $div_val .= '<div class="log_media_container">';
        $div_val .= '<a href="' . $media_uri . '" title="' . htmlEntityDecode($news['media_row']['title']) . '" target="_blank"><img class="picture" src="' . $impathss . '" />';

        $div_val .= '<div class="enlarge ' . $enlarge_class . '"></div></a>
                                                    <div class="log_media_right">
                                                        <div class="text">
                                                            <div class="news_count news_count_v">' . displayViewsCount($news['media_row']['nb_views']) . '</div>
                                                            <div class="news_count news_count_l">' . displayLikesCount($news['media_row']['like_value']) . '</div>
                                                            <div class="news_count news_count_c">' . displayCommentsCount($news['media_row']['nb_comments']) . '</div>
                                                            <div class="news_count news_count_s">' . displaySharesCount($news['media_row']['nb_shares']) . '</div>
                                                            <div class="news_count news_count_r">' . displayRatingsCount($news['media_row']['nb_ratings']) . '</div>
                                                            <div id="popup_view_rate' . round($news['media_row']['rating']) . '" class="log_ratings"></div>
                                                        </div>
                                                    </div>
                                                    <div data-id="' . $news['id'] . '" id="hidden_body_' . $news['id'] . '" class="hidden_body" style="' . $is_hidden_state . '">
                                                        <div class="unhide_log">' . _("Click to unhide") . '</div>
                                                    </div>'.$footer.'
                                                </div>';
    }// Commented on an album.
    else if (($news['action_type'] == SOCIAL_ACTION_COMMENT || $news['action_type'] == SOCIAL_ACTION_SHARE || $news['action_type'] == SOCIAL_ACTION_UPLOAD) && $news['action_row']['entity_type'] == SOCIAL_ENTITY_ALBUM) {
        $impathss = ($news['media_row']['image_video'] == 'i') ? photoReturnSrcSmall($news['media_row']) : videoReturnSrcSmall($news['media_row']);
        $catInfo = userCatalogGet($news['entity_id']);
        $media_uri = ReturnAlbumUri($catInfo);
        $overhead_text = '';
        if ($news['action_type'] == SOCIAL_ACTION_UPLOAD) {
            $action_text = '%s added new album ';
        } else if ($news['action_type'] == SOCIAL_ACTION_SHARE) {
            $action_text = "%s shared an album ";
            $overhead_text = htmlEntityDecode($news['action_row']['msg']);
            $overhead_text = (strlen($overhead_text) > 153) ? substr($overhead_text, 0, 150) . '...' : $overhead_text;
        } else if ($news['action_type'] == SOCIAL_ACTION_COMMENT) {
            $action_text = '%s commented on an album ';
            $overhead_text = htmlEntityDecode($news['action_row']['comment_text']);
        }

        $media_uri_str = '<a href="' . $media_uri . '" target="_blank">';
        $action_array[] = $media_uri_str . '<span class="yellow_font">(' . htmlEntityDecode($catInfo['catalog_name']) . ')</span></a>';
        $action_text .= ' %s';

        array_unshift($action_array, '<a href="' . $channel_url_log . '" class="social_link_a"><strong>' . $channel_name_log . '</strong></a>');
        $action_text_display = vsprintf(_($action_text), $action_array);

        $div_val .= '
                                                <div class="log_channel_header">
                                                    <div class="arrow"></div>
                                                    <a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
                                                    <div class="log_header_text">' . $action_text_display . '</div><br />
                                                    <div class="log_header_time">' . formatDateAsString(strtotime($news['feed_ts'])) . '</div>
                                                    <div id="hidden_header_' . $news['id'] . '" class="hidden_header" style="' . $is_hidden_state . '"></div>
                                                </div>';
        if ($overhead_text != '') {
            $div_val .= '<div class="log_overhead_text">"' . $overhead_text . '"</div>';
        }
        $div_val .= '<div class="log_media_container">
                                                        <div class="log_media_container_inside">
                                                                <a href="' . $media_uri . '" title="' . htmlEntityDecode($catInfo['catalog_name']) . '" target="_blank"><img class="picture" src="' . $impathss . '" />
                                                                <div class="enlarge enlarge_photo"></div></a>
                                                                <div class="log_media_right">
                                                                        <div class="text">
                                                                                <div class="news_count news_count_v">' . displayViewsCount($news['media_row']['nb_views']) . '</div>
                                                                                <div class="news_count news_count_l">' . displayLikesCount($news['media_row']['like_value']) . '</div>
                                                                                <div class="news_count news_count_c">' . displayCommentsCount($news['media_row']['nb_comments']) . '</div>
                                                                                <div class="news_count news_count_s">' . displaySharesCount($news['media_row']['nb_shares']) . '</div>
                                                                                <div class="news_count news_count_r">' . displayRatingsCount($news['media_row']['nb_ratings']) . '</div>
                                                                                <div id="popup_view_rate' . round($news['media_row']['rating']) . '" class="log_ratings"></div>
                                                                        </div>
                                                                </div>
                                                                <div data-id="' . $news['id'] . '" id="hidden_body_' . $news['id'] . '" class="hidden_body" style="' . $is_hidden_state . '">
                                                                        <div class="unhide_log">' . _("Click to unhide") . '</div>
                                                                </div>
                                                        </div>
                                                </div>';
    }
    // Commented on channel info.
    else if (($news['action_type'] == SOCIAL_ACTION_COMMENT || $news['action_type'] == SOCIAL_ACTION_UPDATE) && $news['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_INFO) {
        $overhead_text = '';
        $channelDetailArray = $news['media_row'];
        $info = htmlEntityDecode($channelDetailArray['detail_text']);
        $info = (strlen($info) > 120) ? substr($info, 0, 117) . '...' : $info;
        if ($news['action_type'] == SOCIAL_ACTION_COMMENT) {
            $action_text = "%s commented on their info";
            $overhead_text = htmlEntityDecode($news['action_row']['comment_text']);
        } else {
            $action_text = "%s updated their info";
        }

        array_unshift($action_array, '<a href="' . $channel_url_log . '" class="social_link_a"><strong>' . $channel_name_log . '</strong></a>');
        $action_text_display = vsprintf(_($action_text), $action_array);

        $div_val .= '
                                                <div class="log_channel_header">
                                                    <div class="arrow"></div>
                                                    <a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
                                                    <div class="log_header_text">' . $action_text_display . '</div><br />
                                                    <div class="log_header_time">' . formatDateAsString(strtotime($news['feed_ts'])) . '</div>
                                                    <div id="hidden_header_' . $news['id'] . '" class="hidden_header" style="' . $is_hidden_state . '"></div>
                                                </div>';
        if ($overhead_text != '') {
            $div_val .= '<div class="log_overhead_text">"' . $overhead_text . '"</div>';
        }
        $div_val .= '<div class="log_slogan_container">
                                                    <div class="left_text">' . _("about") . '</div>
                                                    <div class="right_text">"' . $info . '"</div>
                                                    <div data-id="' . $news['id'] . '" id="hidden_body_' . $news['id'] . '" class="hidden_body" style="' . $is_hidden_state . '">
                                                        <div class="unhide_log">' . _("Click to unhide") . '</div>
                                                    </div>
                                                </div>';
    }
    // Commented on channel profile.
    else if (($news['action_type'] == SOCIAL_ACTION_COMMENT || $news['action_type'] == SOCIAL_ACTION_UPDATE) && $news['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_PROFILE) {
        $overhead_text = '';
        $channelDetailArray = $news['media_row'];
        $channel_array = channelGetInfo($channelDetailArray['channelid']);
        $channel_url_log = ReturnLink('channel/' . $channel_array['channel_url']);
        $channel_name_log = htmlEntityDecode($channel_array['channel_name']);
        if (strlen($channel_name_log) > 32) {
            $channel_name_log = substr($channel_name_log, 0, 32) . ' ...';
        }
        if ($news['action_type'] == SOCIAL_ACTION_COMMENT) {
            $action_text = "%s commented on their profile image";
            $overhead_text = htmlEntityDecode($news['action_row']['comment_text']);
        } else {
            $action_text = "%s updated their profile image";
        }
        array_unshift($action_array, '<a href="' . $channel_url_log . '" class="social_link_a"><strong>' . $channel_name_log . '</strong></a>');
        $action_text_display = vsprintf(_($action_text), $action_array);

        $logo_srcbig = ReturnLink('/media/channel/' . $channelDetailArray['channelid'] . '/' . $channelDetailArray['detail_text']);
        $div_val .= '
                                                <div class="log_channel_header">
                                                    <div class="arrow"></div>
                                                    <a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
                                                    <div class="log_header_text">' . $action_text_display . '</div><br />
                                                    <div class="log_header_time">' . formatDateAsString(strtotime($news['feed_ts'])) . '</div>
                                                    <div id="hidden_header_' . $news['id'] . '" class="hidden_header" style="' . $is_hidden_state . '"></div>
                                                </div>';
        if ($overhead_text != '') {
            $div_val .= '<div class="log_overhead_text">"' . $overhead_text . '"</div>';
        }
        $div_val .= '<div class="log_sponsor_container">
                                                    <a class="channelAvatarLink" href="' . $logo_srcbig . '"><img class="img_left_small" alt="'.$channel_name_log.'" src="' . ReturnLink('/media/channel/' . $channelDetailArray['channelid'] . '/thumb/' . $channelDetailArray['detail_text']) . '" /></a>
                                                    <div class="log_sponsor_right">
                                                        <div class="text">
                                                            <div class="news_count news_count_l">' . displayLikesCount($channelDetailArray['like_value']) . '</div>
                                                            <div class="news_count news_count_c">' . displayCommentsCount($channelDetailArray['nb_comments']) . '</div>
                                                        </div>
                                                    </div>
                                                    <div data-id="' . $news['id'] . '" id="hidden_body_' . $news['id'] . '" class="hidden_body" style="' . $is_hidden_state . '">
                                                        <div class="unhide_log">' . _("Click to unhide") . '</div>
                                                    </div>
                                                </div>';
    }
    // Commented on channel slogan.
    else if (($news['action_type'] == SOCIAL_ACTION_COMMENT || $news['action_type'] == SOCIAL_ACTION_UPDATE) && $news['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_SLOGAN) {
        $overhead_text = '';
        $channelDetailArray = $news['media_row'];
        $slogan = htmlEntityDecode($channelDetailArray['detail_text']);
        $slogan = (strlen($slogan) > 120) ? substr($slogan, 0, 117) . '...' : $slogan;
        if ($news['action_type'] == SOCIAL_ACTION_COMMENT) {
            $action_text = "%s commented on their slogan";
            $overhead_text = htmlEntityDecode($news['action_row']['comment_text']);
        } else {
            $action_text = "%s updated their slogan";
        }

        array_unshift($action_array, '<a href="' . $channel_url_log . '" class="social_link_a"><strong>' . $channel_name_log . '</strong></a>');
        $action_text_display = vsprintf(_($action_text), $action_array);

        $div_val .= '
                                                <div class="log_channel_header">
                                                    <div class="arrow"></div>
                                                    <a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
                                                    <div class="log_header_text">' . $action_text_display . '</div><br />
                                                    <div class="log_header_time">' . formatDateAsString(strtotime($news['feed_ts'])) . '</div>
                                                    <div id="hidden_header_' . $news['id'] . '" class="hidden_header" style="' . $is_hidden_state . '"></div>
                                                </div>';
        if ($overhead_text != '') {
            $div_val .= '<div class="log_overhead_text">"' . $overhead_text . '"</div>';
        }
        $div_val .= '<div class="log_slogan_container">
                                                    <div class="left_text">' . _('slogan') . '</div>
                                                    <div class="right_text">"' . $slogan . '"</div>
                                                    <div data-id="' . $news['id'] . '" id="hidden_body_' . $news['id'] . '" class="hidden_body" style="' . $is_hidden_state . '">
                                                        <div class="unhide_log">' . _('Click to unhide') . '</div>
                                                    </div>
                                                </div>';
    }
    // Commented on channel cover.
    else if (($news['action_type'] == SOCIAL_ACTION_COMMENT || $news['action_type'] == SOCIAL_ACTION_UPDATE) && $news['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_COVER) {
        $overhead_text = '';
        $channelDetailArray = $news['media_row'];
        $channel_array = channelGetInfo($channelDetailArray['channelid']);
        $channel_url_log = ReturnLink('channel/' . $channel_array['channel_url']);
        $channel_name_log = htmlEntityDecode($channel_array['channel_name']);
        if (strlen($channel_name_log) > 32) {
            $channel_name_log = substr($channel_name_log, 0, 32) . ' ...';
        }
        if ($news['action_type'] == SOCIAL_ACTION_COMMENT) {
            $action_text = "%s commented on their cover photo";
            $overhead_text = htmlEntityDecode($news['action_row']['comment_text']);
        } else {
            $action_text = "%s updated their cover photo";
        }

        array_unshift($action_array, '<a href="' . $channel_url_log . '" class="social_link_a"><strong>' . $channel_name_log . '</strong></a>');
        $action_text_display = vsprintf(_($action_text), $action_array);

        $logo_srcbig = ReturnLink('/media/channel/' . $channelDetailArray['channelid'] . '/' . $channelDetailArray['detail_text']);
        $div_val .= '
                                                <div class="log_channel_header">
                                                    <div class="arrow"></div>
                                                    <a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
                                                    <div class="log_header_text">' . $action_text_display . '</div><br />
                                                    <div class="log_header_time">' . formatDateAsString(strtotime($news['feed_ts'])) . '</div>
                                                    <div id="hidden_header_' . $news['id'] . '" class="hidden_header" style="' . $is_hidden_state . '"></div>
                                                </div>';
        if ($overhead_text != '') {
            $div_val .= '<div class="log_overhead_text">"' . $overhead_text . '"</div>';
        }
        $div_val .= '<div class="log_sponsor_container">
                                                    <a class="channelAvatarLink" href="' . $logo_srcbig . '"><img class="img_left_full" alt="'.$channel_name_log.'" src="' . ReturnLink('/media/channel/' . $channelDetailArray['channelid'] . '/thumb/' . $channelDetailArray['detail_text']) . '" /></a>
                                                    <div class="log_sponsor_right">
                                                        <div class="text">
                                                            <div class="news_count news_count_l">' . displayLikesCount($channelDetailArray['like_value']) . '</div>
                                                            <div class="news_count news_count_c">' . displayCommentsCount($channelDetailArray['nb_comments']) . '</div>
                                                        </div>
                                                    </div>
                                                    <div data-id="' . $news['id'] . '" id="hidden_body_' . $news['id'] . '" class="hidden_body" style="' . $is_hidden_state . '">
                                                        <div class="unhide_log">' . _('Click to unhide') . '</div>
                                                    </div>
                                                </div>
                                                ';
    }// Commented on a posted video or image.
    else if (($news['action_type'] == SOCIAL_ACTION_COMMENT || $news['action_type'] == SOCIAL_ACTION_SHARE || $news['action_type'] == SOCIAL_ACTION_UPLOAD) && $news['action_row']['entity_type'] == SOCIAL_ENTITY_POST && ( $news['media_row']['post_type'] == SOCIAL_POST_TYPE_PHOTO || $news['media_row']['post_type'] == SOCIAL_POST_TYPE_VIDEO)) {
        $overhead_text = '';

        $action_str = 'photo';
        if ($news['media_row']['post_type'] == SOCIAL_POST_TYPE_VIDEO) {
            $action_str = 'video';
            $enlarge_class = 'enlarge_video';
        } else {
            $enlarge_class = 'enlarge_photo';
        }
        if ($news['action_type'] == SOCIAL_ACTION_COMMENT) {
            $action_text = "%s commented on a posted " . $action_str;
            $overhead_text = htmlEntityDecode($news['action_row']['comment_text']);
        } else if ($news['action_type'] == SOCIAL_ACTION_SHARE) {
            $action_text = "%s shared a posted " . $action_str;
            $overhead_text = htmlEntityDecode($news['action_row']['msg']);
            $overhead_text = (strlen($overhead_text) > 153) ? substr($overhead_text, 0, 150) . '...' : $overhead_text;
        } else {
            $action_text = "%s posted a " . $action_str;
        }
        
        array_unshift($action_array, '<a href="' . $channel_url_log . '" class="social_link_a"><strong>' . $channel_name_log . '</strong></a>');
        $action_text_display = vsprintf(_($action_text), $action_array);

        $div_val .= '
                                                <div class="log_channel_header">
                                                    <div class="arrow"></div>
                                                    <a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
                                                    <div class="log_header_text">' . $action_text_display . '</div><br />
                                                    <div class="log_header_time">' . formatDateAsString(strtotime($news['feed_ts'])) . '</div>
                                                    <div id="hidden_header_' . $news['id'] . '" class="hidden_header" style="' . $is_hidden_state . '"></div>
                                                </div>';
        if ($overhead_text != '') {
            $div_val .= '<div class="log_overhead_text">"' . $overhead_text . '"</div>';
        }
        $div_val .= '<div class="log_media_container">
                                                    <a href="#sharepopup" rel="nofollow" data-channelid="' . $channel_id_owner . '" data-id="' . $news['media_row']['id'] . '" s_val="more"><img class="picture" src="' . getPostThumbPath($news['media_row']) . '" />
                                                    <div class="enlarge ' . $enlarge_class . '"></div></a>
                                                    <div class="log_media_right">
                                                        <div class="text">
                                                            <div class="news_count news_count_v">' . displayViewsCount($news['media_row']['nb_views']) . '</div>
                                                            <div class="news_count news_count_l">' . displayLikesCount($news['media_row']['like_value']) . '</div>
                                                            <div class="news_count news_count_c">' . displayCommentsCount($news['media_row']['nb_comments']) . '</div>
                                                            <div class="news_count news_count_s">' . displaySharesCount($news['media_row']['nb_shares']) . '</div>
                                                            <div class="news_count news_count_r">' . displayRatingsCount($news['media_row']['nb_ratings']) . '</div>
                                                            <div id="popup_view_rate' . round($news['media_row']['rating']) . '" class="log_ratings"></div>
                                                        </div>
                                                    </div>
                                                    <div data-id="' . $news['id'] . '" id="hidden_body_' . $news['id'] . '" class="hidden_body" style="' . $is_hidden_state . '">
                                                        <div class="unhide_log">' . _("Click to unhide") . '</div>
                                                    </div>
                                                </div>';
    }// Commented on a posted text.
    else if (($news['action_type'] == SOCIAL_ACTION_COMMENT || $news['action_type'] == SOCIAL_ACTION_SHARE || $news['action_type'] == SOCIAL_ACTION_UPLOAD) && $news['action_row']['entity_type'] == SOCIAL_ENTITY_POST && $news['media_row']['post_type'] == SOCIAL_POST_TYPE_TEXT) {
        $overhead_text = '';
        $action_str = 'the following:';
        if ($news['action_type'] == SOCIAL_ACTION_COMMENT) {
            $action_text = "%s commented on " . $action_str;
            $overhead_text = htmlEntityDecode($news['action_row']['comment_text']);
        } else if ($news['action_type'] == SOCIAL_ACTION_SHARE) {
            $action_text = "%s shared " . $action_str;
            $overhead_text = htmlEntityDecode($news['action_row']['msg']);
            $overhead_text = (strlen($overhead_text) > 153) ? substr($overhead_text, 0, 150) . '...' : $overhead_text;
        } else {
            $action_text = "%s posted " . $action_str;
        }

        array_unshift($action_array, '<a href="' . $channel_url_log . '" class="social_link_a"><strong>' . $channel_name_log . '</strong></a>');
        $action_text_display = vsprintf(_($action_text), $action_array);

        $div_val .= '
                                                <div class="log_channel_header">
                                                    <div class="arrow"></div>
                                                    <a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
                                                    <div class="log_header_text">' . $action_text_display . '</div><br />
                                                    <div class="log_header_time">' . formatDateAsString(strtotime($news['feed_ts'])) . '</div>
                                                    <div id="hidden_header_' . $news['id'] . '" class="hidden_header" style="' . $is_hidden_state . '"></div>
                                                </div>';
        if ($overhead_text != '') {
            $div_val .= '<div class="log_overhead_text">"' . $overhead_text . '"</div>';
        }
        $div_val .= '<div class="log_post_container">
                                                    <div class="log_post_left">
                                                        <div class="log_post_left_text">' . htmlEntityDecode($news['media_row']['post_text']) . '</div>
                                                    </div>
                                                    <div data-id="' . $news['id'] . '" id="hidden_body_' . $news['id'] . '" class="hidden_body" style="' . $is_hidden_state . '">
                                                        <div class="unhide_log">' . _("Click to unhide") . '</div>
                                                    </div>
                                                </div>';
    }
    // Commented on a posted link.
    else if (($news['action_type'] == SOCIAL_ACTION_COMMENT || $news['action_type'] == SOCIAL_ACTION_SHARE || $news['action_type'] == SOCIAL_ACTION_UPLOAD) && $news['action_row']['entity_type'] == SOCIAL_ENTITY_POST && $news['media_row']['post_type'] == SOCIAL_POST_TYPE_LINK) {
        $overhead_text = htmlEntityDecode($news['action_row']['comment_text']);

        $link = htmlEntityDecode($news['media_row']['post_text']);
        if (strlen($link) > 20) {
            $link_display = substr($link, 0, 5) . '...' . substr($link, (strlen($link) - 12));
        } else {
            $link_display = $link;
        }
        $action_str = 'a link';
        if ($news['action_type'] == SOCIAL_ACTION_COMMENT) {
            $action_text = "%s commented on " . $action_str;
            $overhead_text = htmlEntityDecode($news['action_row']['comment_text']);
        } else if ($news['action_type'] == SOCIAL_ACTION_SHARE) {
            $action_text = "%s shared " . $action_str;
            $overhead_text = htmlEntityDecode($news['action_row']['msg']);
            $overhead_text = (strlen($overhead_text) > 153) ? substr($overhead_text, 0, 150) . '...' : $overhead_text;
        } else {
            $action_text = "%s posted " . $action_str;
        }

        array_unshift($action_array, '<a href="' . $channel_url_log . '" class="social_link_a"><strong>' . $channel_name_log . '</strong></a>');
        $action_text_display = vsprintf(_($action_text), $action_array);

        $div_val .= '<div class="log_channel_header">
                                                    <div class="arrow"></div>
                                                    <a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
                                                    <div class="log_header_text">' . $action_text_display . '</div><br />
                                                    <div class="log_header_time">' . formatDateAsString(strtotime($news['feed_ts'])) . '</div>
                                                    <div id="hidden_header_' . $news['id'] . '" class="hidden_header" style="' . $is_hidden_state . '"></div>
                                                </div>';
        if ($overhead_text != '') {
            $div_val .= '<div class="log_overhead_text">"' . $overhead_text . '"</div>';
        }
        $div_val .= '<div class="log_post_container">
                                                    <div class="log_post_left">
                                                        <div class="log_post_left_text"><a href="' . $link . '">"' . $link_display . '"</a></div>
                                                    </div>
                                                    <div data-id="' . $news['id'] . '" id="hidden_body_' . $news['id'] . '" class="hidden_body" style="' . $is_hidden_state . '">
                                                        <div class="unhide_log">' . _("Click to unhide") . '</div>
                                                    </div>
                                                </div>';
    }
    // Commented on news.
    else if (($news['action_type'] == SOCIAL_ACTION_COMMENT || $news['action_type'] == SOCIAL_ACTION_SHARE || $news['action_type'] == SOCIAL_ACTION_UPLOAD) && $news['action_row']['entity_type'] == SOCIAL_ENTITY_NEWS) {
        $overhead_text = '';
        $news_body = $news['media_row']['description'];
        $news_body = htmlEntityDecode($news_body);
        $news_body = (strlen($news_body) > 173) ? substr($news_body, 0, 170) . '...' : $news_body;

        $action_str = 'news';
        if ($news['action_type'] == SOCIAL_ACTION_COMMENT) {
            $action_text = "%s commented on the " . $action_str;
            $overhead_text = htmlEntityDecode($news['action_row']['comment_text']);
        } else if ($news['action_type'] == SOCIAL_ACTION_SHARE) {
            $action_text = "%s shared the " . $action_str;
            $overhead_text = htmlEntityDecode($news['action_row']['msg']);
            $overhead_text = (strlen($overhead_text) > 153) ? substr($overhead_text, 0, 150) . '...' : $overhead_text;
        } else {
            $action_text = "%s uploaded a " . $action_str;
        }

        array_unshift($action_array, '<a href="' . $channel_url_log . '" class="social_link_a"><strong>' . $channel_name_log . '</strong></a>');
        $action_text_display = vsprintf(_($action_text), $action_array);

        $div_val .= '
                                                <div class="log_channel_header">
                                                    <div class="arrow"></div>
                                                    <a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
                                                    <div class="log_header_text">' . $action_text_display . '</div><br />
                                                    <div class="log_header_time">' . formatDateAsString(strtotime($news['feed_ts'])) . '</div>
                                                    <div id="hidden_header_' . $news['id'] . '" class="hidden_header" style="' . $is_hidden_state . '"></div>
                                                </div>';
        if ($overhead_text != '') {
            $div_val .= '<div class="log_overhead_text">"' . $overhead_text . '"</div>';
        }
        $div_val .= '<div class="log_news_container">
                                                    <div class="log_news_left">
                                                        <div class="log_news_left_text">' . $news_body . '</div>
                                                    </div>
                                                    <div class="log_news_right">
                                                        <div class="log_news_right_text">
                                                            <div class="news_count news_count_l">' . displayLikesCount($news['media_row']['like_value']) . '</div>
                                                            <div class="news_count news_count_c">' . displayCommentsCount($news['media_row']['nb_comments']) . '</div>
                                                            <div class="news_count news_count_s">' . displaySharesCount($news['media_row']['nb_shares']) . '</div>
                                                        </div>
                                                    </div>
                                                    <div data-id="' . $news['id'] . '" id="hidden_body_' . $news['id'] . '" class="hidden_body" style="' . $is_hidden_state . '">
                                                        <div class="unhide_log">' . _("Click to unhide") . '</div>
                                                    </div>
                                               </div>';
    }
    // Commented on an event.
    else if (($news['action_type'] == SOCIAL_ACTION_COMMENT || $news['action_type'] == SOCIAL_ACTION_EVENT_CANCEL || $news['action_type'] == SOCIAL_ACTION_SPONSOR || $news['action_type'] == SOCIAL_ACTION_INVITE || $news['action_type'] == SOCIAL_ACTION_SHARE || $news['action_type'] == SOCIAL_ACTION_UPLOAD) && $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS) {

        if ($news['media_row']['photo'] != '')
            $event_photo = ReturnLink('/media/channel/' . $news['media_row']['channelid'] . '/event/thumb/' . $news['media_row']['photo']);
        else
            $event_photo = ReturnLink('/media/images/channel/eventthemephoto.jpg');

        $overhead_text = '';
        // Get the number of sponsors.
        $options = array(
            'entity_id' => $news['media_row']['id'],
            'entity_type' => SOCIAL_ENTITY_EVENTS,
            'share_type' => SOCIAL_SHARE_TYPE_SPONSOR,
            'is_visible' => 1,
            'like_value' => 1,
            'n_results' => true
        );
        $sponsors_num = socialSharesGet($options);
        $joinEventArrayCount = joinEventSearch(array(
            'event_id' => $news['media_row']['id'],
            'n_results' => true
        ));

        $action_str = 'event';
        if ($news['action_type'] == SOCIAL_ACTION_COMMENT) {
            $action_text = "%s commented on the " . $action_str;
            $overhead_text = htmlEntityDecode($news['action_row']['comment_text']);
        } else if ($news['action_type'] == SOCIAL_ACTION_SHARE) {
            $action_text = "%s shared the " . $action_str;
            $overhead_text = htmlEntityDecode($news['action_row']['msg']);
            $overhead_text = (strlen($overhead_text) > 153) ? substr($overhead_text, 0, 150) . '...' : $overhead_text;
        } else if ($news['action_type'] == SOCIAL_ACTION_INVITE) {
            $action_text = "%s invited to the " . $action_str;
            $overhead_text = htmlEntityDecode($news['action_row']['msg']);
            $overhead_text = (strlen($overhead_text) > 153) ? substr($overhead_text, 0, 150) . '...' : $overhead_text;
        } else if ($news['action_type'] == SOCIAL_ACTION_SPONSOR) {
            $action_text = "%s sponsored the " . $action_str;
            $overhead_text = htmlEntityDecode($news['action_row']['msg']);
            $overhead_text = (strlen($overhead_text) > 153) ? substr($overhead_text, 0, 150) . '...' : $overhead_text;
        } else if ($news['action_type'] == SOCIAL_ACTION_EVENT_CANCEL) {
            $action_text = "%s cancelled the " . $action_str;
        } else {
            $action_text = "%s created a new " . $action_str;
        }
        $action_text .= ' %s';
        $action_array[] = '<a href="' . ReturnLink('channel-events-detailed/' . $news['media_row']['id']) . '"><span class="yellow_font">(' . htmlEntityDecode($news['media_row']['name']) . ')</span></a>';
        array_unshift($action_array, '<a href="' . $channel_url_log . '" class="social_link_a"><strong>' . $channel_name_log . '</strong></a>');
        $action_text_display = vsprintf(_($action_text), $action_array);

        $div_val .= '
                                                <div class="log_channel_header">
                                                    <div class="arrow"></div>
                                                    <a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
                                                    <div class="log_header_text">' . $action_text_display . '</div><br />
                                                    <div class="log_header_time">' . formatDateAsString(strtotime($news['feed_ts'])) . '</div>
                                                    <div id="hidden_header_' . $news['id'] . '" class="hidden_header" style="' . $is_hidden_state . '"></div>
                                                </div>';
        if ($overhead_text != '') {
            $div_val .= '<div class="log_overhead_text">"' . $overhead_text . '"</div>';
        }
        $div_val .= '<div class="log_events_container">
                                                    <div class="picture_container">
                                                        <a href="' . ReturnLink('channel-events-detailed/' . $news['media_row']['id']) . '"><img class="picture" src="' . $event_photo . '" />
                                                        <div class="enlarge enlarge_photo"></div></a>
                                                    </div>
                                                    <div class="details">
                                                        <span class="date">' . date('d/m/Y', strtotime($news['media_row']['fromdate'])) . '</span>
                                                        <img class="clock_icon" src="' . ReturnLink('media/images/channel/eventclock.png') . '" />
                                                        <span class="time">' . date('g:i a', strtotime('2000-1-1 ' . $news['media_row']['fromtime'])) . '</span><br />
                                                        <span class="location">' . _('location:') . ' ' . htmlEntityDecode($news['media_row']['location']) . '</span>
                                                    </div>
                                                    <div class="log_events_right">
                                                        <div class="text">
                                                            <div class="news_count news_count_l">' . displayLikesCount($news['media_row']['like_value']) . '</div>
                                                            <div class="news_count news_count_c">' . displayCommentsCount($news['media_row']['nb_comments']) . '</div>
                                                            <div class="news_count news_count_s">' . displaySharesCount($news['media_row']['nb_shares']) . '</div>
                                                            <div class="news_count news_count_j">' . displayJoiningCount($joinEventArrayCount) . '</div>
                                                            <div class="news_count news_count_sp">' . displaySponsorsCount($sponsors_num) . '</div>
                                                        </div>
                                                    </div>
                                                    <div data-id="' . $news['id'] . '" id="hidden_body_' . $news['id'] . '" class="hidden_body" style="' . $is_hidden_state . '">
                                                        <div class="unhide_log">' . _('Click to unhide') . '</div>
                                                    </div>
                                                </div>';
    }
    // Commented on a brochure.
    else if (($news['action_type'] == SOCIAL_ACTION_COMMENT || $news['action_type'] == SOCIAL_ACTION_SHARE || $news['action_type'] == SOCIAL_ACTION_UPLOAD) && $news['action_row']['entity_type'] == SOCIAL_ENTITY_BROCHURE) {
        $overhead_text = '';

        $action_str = 'brochure';
        if ($news['action_type'] == SOCIAL_ACTION_COMMENT) {
            $action_text = "%s commented on the " . $action_str;
            $overhead_text = htmlEntityDecode($news['action_row']['comment_text']);
        } else if ($news['action_type'] == SOCIAL_ACTION_SHARE) {
            $action_text = "%s shared the " . $action_str;
            $overhead_text = htmlEntityDecode($news['action_row']['msg']);
            $overhead_text = (strlen($overhead_text) > 153) ? substr($overhead_text, 0, 150) . '...' : $overhead_text;
        } else {
            $action_text = "%s added a " . $action_str;
        }
        $action_text .= ' %s';
        $action_array[] = '<a href="' . ReturnLink('channel-brochures/' . $channelInfo['channel_url'] . '/id/' . $news['entity_id']) . '"><span class="yellow_font">(' . htmlEntityDecode($news['media_row']['name']) . ')</span></a>';
        array_unshift($action_array, '<a href="' . $channel_url_log . '" class="social_link_a"><strong>' . $channel_name_log . '</strong></a>');
        $action_text_display = vsprintf(_($action_text), $action_array);
        $pdf = ($news['media_row']['pdf']) ? pdfReturnbrochure($news['media_row']) : '#';
        
        $photo = $media_row['photo'];
        $thumb_pic = ($photo) ? photoReturnbrochureThumb($news['media_row']) : ReturnLink('media/images/channel/brochure-cover-phot.jpg');
        
        $div_val .= '
                                                <div class="log_channel_header">
                                                    <div class="arrow"></div>
                                                    <a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
                                                    <div class="log_header_text">' . $action_text_display . '</div><br />
                                                    <div class="log_header_time">' . formatDateAsString(strtotime($news['feed_ts'])) . '</div>
                                                    <div id="hidden_header_' . $news['id'] . '" class="hidden_header" style="' . $is_hidden_state . '"></div>
                                                </div>';
        if ($overhead_text != '') {
            $div_val .= '<div class="log_overhead_text">"' . $overhead_text . '"</div>';
        }
        $div_val .= '<div class="log_brochure_container">
                                                    <div class="log_brochure_left">
                                                        <div class="picture">
                                                            <a href="' . $pdf . '" target="_blank"><img src="' . $thumb_pic . '"></a>
                                                        </div>
                                                        <a href="' . $pdf . '" target="_blank" class="text text_brochure">' . htmlEntityDecode($news['media_row']['name']) . '</a>
                                                    </div>
                                                    <div class="log_brochure_right">
                                                        <div class="text">
                                                            <div class="news_count news_count_l">' . displayLikesCount($news['media_row']['like_value']) . '</div>
                                                            <div class="news_count news_count_c">' . displayCommentsCount($news['media_row']['nb_comments']) . '</div>
                                                            <div class="news_count news_count_s">' . displaySharesCount($news['media_row']['nb_shares']) . '</div>
                                                        </div>
                                                    </div>
                                                    <div data-id="' . $news['id'] . '" id="hidden_body_' . $news['id'] . '" class="hidden_body" style="' . $is_hidden_state . '">
                                                        <div class="unhide_log">' . _('Click to unhide') . '</div>
                                                    </div>
                                                </div>';
    }
    // Sponsored a channel.
    else if ($news['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL) {
        // Prepare the overhead text.
        $action_text = '';
        $overhead_text = '';
        if ($news['action_type'] == SOCIAL_ACTION_SPONSOR) {
            $action_array[] = '<a href="' . ReturnLink('channel/' . $news['media_row']['channel_url']) . '"><span class="yellow_font">(' . htmlEntityDecode($news['media_row']['channel_name']) . ')</span></a>';
            $action_text = '%s sponsored the channel %s';
            $overhead_text = htmlEntityDecode($news['action_row']['msg']);
            $overhead_text = (strlen($overhead_text) > 153) ? substr($overhead_text, 0, 150) . '...' : $overhead_text;
        } else if ($news['action_type'] == SOCIAL_ACTION_RELATION_SUB || $news['action_type'] == SOCIAL_ACTION_RELATION_PARENT) {
            $action_text = '%s has accepted you as a sub channel';
            if ($news['action_row']['relation_type'] == CHANNEL_RELATION_TYPE_SUB) {
                $action_text = '%s is now a sub channel';
            }
            if ($channelInfo['id'] != $news['media_row']['id']) {
                $channel_url_r = ReturnLink('channel/' . $news['media_row']['channel_url']);
                $channel_name_r = htmlEntityDecode($news['media_row']['channel_name']);
                if (strlen($channel_name_r) > 32) {
                    $channel_name_r = substr($channel_name_r, 0, 32) . ' ...';
                }
                $action_array[] = '<a href="' . $channel_url_r . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">' . $channel_name_r . '</span></a>';
                $action_text = '%s has accepted %s as a sub channel';
                if ($news['action_row']['relation_type'] == CHANNEL_RELATION_TYPE_SUB) {
                    $action_array = array();
                    $action_array[] = '<a href="' . $channel_url_r . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">' . $channel_name_r . '</span></a>';
                    $action_text = '%s is now a sub channel to %s';
                }
            }
        } else if ($news['action_type'] == SOCIAL_ACTION_COMMENT) {
            $overhead_text = htmlEntityDecode($news['action_row']['comment_text']);
            $action_text = '%s commented on their channel ';
        } else if ($news['action_type'] == SOCIAL_ACTION_SHARE) {
            $action_text = '%s shared their channel ';
            $overhead_text = htmlEntityDecode($news['action_row']['msg']);
            $overhead_text = (strlen($overhead_text) > 153) ? substr($overhead_text, 0, 150) . '...' : $overhead_text;
        } else if ($news['action_type'] == SOCIAL_ACTION_INVITE) {
            $action_text = '%s invited to their channel ';
            $overhead_text = htmlEntityDecode($news['action_row']['msg']);
            $overhead_text = (strlen($overhead_text) > 153) ? substr($overhead_text, 0, 150) . '...' : $overhead_text;
        }

        $channel_src = ($news['media_row']['logo']) ? photoReturnchannelLogo($news['media_row']) : ReturnLink('/media/tubers/tuber.jpg');
        $header_src = photoReturnchannelHeader($news['media_row']);

        array_unshift($action_array, '<a href="' . $channel_url_log . '" class="social_link_a"><strong>' . $channel_name_log . '</strong></a>');
        $action_text_display = vsprintf(_($action_text), $action_array);
        $logo_srcbig = photoReturnchannelHeaderBig($news['media_row']);
        $logo_srcbig1 = ($news['media_row']['logo']) ? photoReturnchannelLogoBig($news['media_row']) : '';
        $div_val .= '
                                                <div class="log_channel_header">
                                                    <div class="arrow"></div>
                                                    <a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
                                                    <div class="log_header_text">' . $action_text_display . '</div><br />
                                                    <div class="log_header_time">' . formatDateAsString(strtotime($news['feed_ts'])) . '</div>
                                                    <div id="hidden_header_' . $news['id'] . '" class="hidden_header" style="' . $is_hidden_state . '"></div>
                                                </div>';
        if ($overhead_text != '') {
            $div_val .= '<div class="log_overhead_text">"' . $overhead_text . '"</div>';
        }
        $div_val .= '<div class="log_sponsor_container">';
                        if($logo_srcbig!=''){
                            $div_val .= '<a class="channelAvatarLink" href="' . $logo_srcbig . '"><img class="img_left" alt="'.$channel_name_log.'" src="' . $header_src . '" /></a>';
                        }else{
                            $div_val .= '<img class="img_left" alt="'.$channel_name_log.'" src="' . $header_src . '" />';
                        }
                        if($logo_srcbig1!=''){
                            $div_val .= '<a class="channelAvatarLink" href="' . $logo_srcbig1 . '"><img class="img_right" alt="'.$channel_name_log.'" src="' . $channel_src . '" /></a>';
                        }else{
                            $div_val .= '<img class="img_right" alt="'.$channel_name_log.'" src="' . $channel_src . '" />';
                        }
                                                    $div_val .= '<div class="log_sponsor_right">
                                                        <div class="text">
                                                            <div class="news_count news_count_l">' . displayLikesCount($news['media_row']['like_value']) . '</div>
                                                            <div class="news_count news_count_c">' . displayCommentsCount($news['media_row']['nb_comments']) . '</div>
                                                            <div class="news_count news_count_s">' . displaySharesCount($news['media_row']['nb_shares']) . '</div>
                                                        </div>
                                                    </div>
                                                    <div data-id="' . $news['id'] . '" id="hidden_body_' . $news['id'] . '" class="hidden_body" style="' . $is_hidden_state . '">
                                                        <div class="unhide_log">' . _('Click to unhide') . '</div>
                                                    </div>
                                                </div>';
    }
    // Changed event location.
    else if ($news['action_type'] == SOCIAL_ACTION_UPDATE && $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_LOCATION) {
        // Prepare the event photo.
        if ($news['media_row']['photo'] != '')
            $event_photo = ReturnLink('/media/channel/' . $news['media_row']['channelid'] . '/event/thumb/' . $news['media_row']['photo']);
        else
            $event_photo = ReturnLink('/media/images/channel/eventthemephoto.jpg');

        // Get the number of sponsors.                                           
        $options = array(
            'entity_id' => $news['media_row']['id'],
            'entity_type' => SOCIAL_ENTITY_EVENTS,
            'share_type' => SOCIAL_SHARE_TYPE_SPONSOR,
            'is_visible' => 1,
            'like_value' => 1,
            'n_results' => true
        );
        $sponsors_num = socialSharesGet($options);
        $joinEventArrayCount = joinEventSearch(array(
            'event_id' => $news['media_row']['id'],
            'n_results' => true
        ));

        $action_text = "%s changed their event location %s";

        $action_array[] = '<a href="' . ReturnLink('channel-events-detailed/' . $news['media_row']['id']) . '"><span class="yellow_font">(' . htmlEntityDecode($news['media_row']['name']) . ')</span></a>';
        array_unshift($action_array, '<a href="' . $channel_url_log . '" class="social_link_a"><strong>' . $channel_name_log . '</strong></a>');
        $action_text_display = vsprintf(_($action_text), $action_array);

        $div_val .= '
                                                <div class="log_channel_header">
                                                    <div class="arrow"></div>
                                                    <a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
                                                    <div class="log_header_text">' . $action_text_display . '</div><br />
                                                    <div class="log_header_time">' . formatDateAsString(strtotime($news['feed_ts'])) . '</div>
                                                    <div id="hidden_header_' . $news['id'] . '" class="hidden_header" style="' . $is_hidden_state . '"></div>
                                                </div>
                                                
                                                
                                                <div class="log_events_container">
                                                    <div class="picture_container">
                                                        <a href="' . ReturnLink('channel-events-detailed/' . $news['media_row']['id']) . '"><img class="picture" src="' . $event_photo . '" />
                                                        <div class="enlarge enlarge_photo"></div></a>
                                                    </div>
                                                    <div class="details">
                                                        <span class="date">' . date('d/m/Y', strtotime($news['media_row']['fromdate'])) . '</span>
                                                        <img class="clock_icon" src="' . ReturnLink('media/images/channel/eventclock.png') . '" />
                                                        <span class="time">' . date('g:i a', strtotime('2000-1-1 ' . $news['media_row']['fromtime'])) . '</span><br />
                                                        <span class="location">' . _('location:') . ' ' . htmlEntityDecode($news['media_row']['location']) . '</span>
                                                    </div>
                                                    <div class="log_events_right">
                                                        <div class="text">
                                                            <div class="news_count news_count_l">' . displayLikesCount($news['media_row']['like_value']) . '</div>
                                                            <div class="news_count news_count_c">' . displayCommentsCount($news['media_row']['nb_comments']) . '</div>
                                                            <div class="news_count news_count_s">' . displaySharesCount($news['media_row']['nb_shares']) . '</div>
                                                            <div class="news_count news_count_j">' . displayJoiningCount($joinEventArrayCount) . '</div>
                                                            <div class="news_count news_count_sp">' . displaySponsorsCount($sponsors_num) . '</div>
                                                        </div>
                                                    </div>
                                                    <div data-id="' . $news['id'] . '" id="hidden_body_' . $news['id'] . '" class="hidden_body" style="' . $is_hidden_state . '">
                                                        <div class="unhide_log">' . _('Click to unhide') . '</div>
                                                    </div>
                                                </div>
                                                ';
    }
    // Changed event date.
    else if ($news['action_type'] == SOCIAL_ACTION_UPDATE && $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_DATE) {
        // Prepare the event photo.
        if ($news['media_row']['photo'] != '')
            $event_photo = ReturnLink('/media/channel/' . $news['media_row']['channelid'] . '/event/thumb/' . $news['media_row']['photo']);
        else
            $event_photo = ReturnLink('/media/images/channel/eventthemephoto.jpg');


        // Get the number of sponsors.
        $options = array(
            'entity_id' => $news['media_row']['id'],
            'entity_type' => SOCIAL_ENTITY_EVENTS,
            'share_type' => SOCIAL_SHARE_TYPE_SPONSOR,
            'is_visible' => 1,
            'like_value' => 1,
            'n_results' => true
        );
        $sponsors_num = socialSharesGet($options);
        $joinEventArrayCount = joinEventSearch(array(
            'event_id' => $news['media_row']['id'],
            'n_results' => true
        ));

        $action_text = "%s changed their event date %s";

        $action_array[] = '<a href="' . ReturnLink('channel-events-detailed/' . $news['media_row']['id']) . '"><span class="yellow_font">(' . htmlEntityDecode($news['media_row']['name']) . ')</span></a>';
        array_unshift($action_array, '<a href="' . $channel_url_log . '" class="social_link_a"><strong>' . $channel_name_log . '</strong></a>');
        $action_text_display = vsprintf(_($action_text), $action_array);

        $div_val .= '
                                                <div class="log_channel_header">
                                                    <div class="arrow"></div>
                                                    <a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
                                                    <div class="log_header_text">' . $action_text_display . '</div><br />
                                                    <div class="log_header_time">' . formatDateAsString(strtotime($news['feed_ts'])) . '</div>
                                                    <div id="hidden_header_' . $news['id'] . '" class="hidden_header" style="' . $is_hidden_state . '"></div>
                                                </div>
                                                
                                                
                                                <div class="log_events_container">
                                                    <div class="picture_container">
                                                        <a href="' . ReturnLink('channel-events-detailed/' . $news['media_row']['id']) . '"><img class="picture" src="' . $event_photo . '" />
                                                        <div class="enlarge enlarge_photo"></div></a>
                                                    </div>
                                                    <div class="details">
                                                        <span class="date">' . date('d/m/Y', strtotime($news['media_row']['fromdate'])) . '</span>
                                                        <img class="clock_icon" src="' . ReturnLink('media/images/channel/eventclock.png') . '" />
                                                        <span class="time">' . date('g:i a', strtotime('2000-1-1 ' . $news['media_row']['fromtime'])) . '</span><br />
                                                        <span class="location">' . _('location:') . ' ' . htmlEntityDecode($news['media_row']['location']) . '</span>
                                                    </div>
                                                    <div class="log_events_right">
                                                        <div class="text">
                                                            <div class="news_count news_count_l">' . displayLikesCount($news['media_row']['like_value']) . '</div>
                                                            <div class="news_count news_count_c">' . displayCommentsCount($news['media_row']['nb_comments']) . '</div>
                                                            <div class="news_count news_count_s">' . displaySharesCount($news['media_row']['nb_shares']) . '</div>
                                                            <div class="news_count news_count_j">' . displayJoiningCount($joinEventArrayCount) . '</div>
                                                            <div class="news_count news_count_sp">' . displaySponsorsCount($sponsors_num) . '</div>
                                                        </div>
                                                    </div>
                                                    <div data-id="' . $news['id'] . '" id="hidden_body_' . $news['id'] . '" class="hidden_body" style="' . $is_hidden_state . '">
                                                        <div class="unhide_log">' . _('Click to unhide') . '</div>
                                                    </div>
                                                </div>
                                                ';
    }
    // Changed event time.
    else if ($news['action_type'] == SOCIAL_ACTION_UPDATE && $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_TIME) {
        // Prepare the event photo.
        if ($news['media_row']['photo'] != '')
            $event_photo = ReturnLink('/media/channel/' . $news['media_row']['channelid'] . '/event/thumb/' . $news['media_row']['photo']);
        else
            $event_photo = ReturnLink('/media/images/channel/eventthemephoto.jpg');


        // Get the number of sponsors.
        $options = array(
            'entity_id' => $news['media_row']['id'],
            'entity_type' => SOCIAL_ENTITY_EVENTS,
            'share_type' => SOCIAL_SHARE_TYPE_SPONSOR,
            'is_visible' => 1,
            'like_value' => 1,
            'n_results' => true
        );
        $sponsors_num = socialSharesGet($options);
        $joinEventArrayCount = joinEventSearch(array(
            'event_id' => $news['media_row']['id'],
            'n_results' => true
        ));

        $action_text = "%s changed their event time %s";

        $action_array[] = '<a href="' . ReturnLink('channel-events-detailed/' . $news['media_row']['id']) . '"><span class="yellow_font">(' . htmlEntityDecode($news['media_row']['name']) . ')</span></a>';
        array_unshift($action_array, '<a href="' . $channel_url_log . '" class="social_link_a"><strong>' . $channel_name_log . '</strong></a>');
        $action_text_display = vsprintf(_($action_text), $action_array);

        $div_val .= '
                                                <div class="log_channel_header">
                                                    <div class="arrow"></div>
                                                    <a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
                                                    <div class="log_header_text">' . $action_text_display . '</div><br />
                                                    <div class="log_header_time">' . formatDateAsString(strtotime($news['feed_ts'])) . '</div>
                                                    <div id="hidden_header_' . $news['id'] . '" class="hidden_header" style="' . $is_hidden_state . '"></div>
                                                </div>
                                                
                                                
                                                <div class="log_events_container">
                                                    <div class="picture_container">
                                                        <a href="' . ReturnLink('channel-events-detailed/' . $news['media_row']['id']) . '"><img class="picture" src="' . $event_photo . '" />
                                                        <div class="enlarge enlarge_photo"></div></a>
                                                    </div>
                                                    <div class="details">
                                                        <span class="date">' . date('d/m/Y', strtotime($news['media_row']['fromdate'])) . '</span>
                                                        <img class="clock_icon" src="' . ReturnLink('media/images/channel/eventclock.png') . '" />
                                                        <span class="time">' . date('g:i a', strtotime('2000-1-1 ' . $news['media_row']['fromtime'])) . '</span><br />
                                                        <span class="location">' . _('location:') . ' ' . htmlEntityDecode($news['media_row']['location']) . '</span>
                                                    </div>
                                                    <div class="log_events_right">
                                                        <div class="text">
                                                            <div class="news_count news_count_l">' . displayLikesCount($news['media_row']['like_value']) . '</div>
                                                            <div class="news_count news_count_c">' . displayCommentsCount($news['media_row']['nb_comments']) . '</div>
                                                            <div class="news_count news_count_s">' . displaySharesCount($news['media_row']['nb_shares']) . '</div>
                                                            <div class="news_count news_count_j">' . displayJoiningCount($joinEventArrayCount) . '</div>
                                                            <div class="news_count news_count_sp">' . displaySponsorsCount($sponsors_num) . '</div>
                                                        </div>
                                                    </div>
                                                    <div data-id="' . $news['id'] . '" id="hidden_body_' . $news['id'] . '" class="hidden_body" style="' . $is_hidden_state . '">
                                                        <div class="unhide_log">' . _('Click to unhide') . '</div>
                                                    </div>
                                                </div>
                                                ';
    }// end
    else {
        //debug($news);
    }
    $div_val .= '<div class="commentDivClass social_data_all" style="' . $is_hidden_social . '" data-category="" data-enable="1" data-id="' . $data_action_id . '" data-type="' . $data_action_type . '">';
    $div_val .= '<div class="buttons">';
    if ($news['action_type'] != SOCIAL_ACTION_EVENT_CANCEL && ( $news['action_row']['entity_type'] == SOCIAL_ENTITY_MEDIA || $news['action_row']['entity_type'] == SOCIAL_ENTITY_ALBUM || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS || $news['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_LOCATION || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_DATE || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_TIME )) {
        $div_val .= '<div class="btn"><div class="description"></div></div>';
        $div_val .= '<div class="btn_txt opacitynone"></div>';
    }

    if (intval($channelPrivacyArray['privacy_social']) == 1 || $is_owner == 1) {
        if ($news['action_type'] != SOCIAL_ACTION_EVENT_CANCEL) {
            $div_val .= '<div class="btn"><div class="likes"></div></div>';
            $div_val .= '<div class="btn_txt opacitynone"></div>';
            if ($social_can_comment) {
                $div_val .= '<div class="btn btn_comments btn_enabled"><div class="comments"></div></div>';
                $div_val .= '<div class="btn_txt opacitynone btn_txt_comments btn_enabled"></div>';
            }
        }
        if ($news['action_row']['entity_type'] != SOCIAL_ENTITY_CHANNEL_INFO && $news['action_row']['entity_type'] != SOCIAL_ENTITY_CHANNEL_SLOGAN && $news['action_row']['entity_type'] != SOCIAL_ENTITY_CHANNEL_PROFILE && $news['action_row']['entity_type'] != SOCIAL_ENTITY_CHANNEL_COVER) {
            if ($social_can_share) {

                $div_val .= '<div class="btn btn_shares btn_enabled"><div class="shares"></div></div>';
                $div_val .= '<div class="btn_txt opacitynone btn_txt_shares btn_enabled"></div>';
            }
        }
        if ($news['action_row']['entity_type'] == SOCIAL_ENTITY_MEDIA || $news['action_row']['entity_type'] == SOCIAL_ENTITY_ALBUM || $news['media_row']['post_type'] == SOCIAL_POST_TYPE_PHOTO || $news['media_row']['post_type'] == SOCIAL_POST_TYPE_VIDEO) {
            if ($social_can_rate) {
                $div_val .= '<div class="btn btn_rates"><div class="rates"></div></div>';
                $div_val .= '<div class="btn_txt opacitynone"></div>';
            }
        }
    }

    if ($news['action_type'] != SOCIAL_ACTION_EVENT_CANCEL && ( $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_LOCATION || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_DATE || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_TIME )) {
        if ($user_can_join_check) {
            $div_val .= '<div class="btn btn_joins"><div class="joins"></div></div>';
            $div_val .= '<div class="btn_txt btn_txt_joins opacitynone"></div>';
        }

        if (( $news['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL && $channelPrivacyArray['privacy_sponsoring'] == 1 ) || ( ( $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_LOCATION || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_DATE || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_TIME ) && $channelPrivacyArray['privacy_sponsoring_event'] == 1 )) {
            $div_val .= '<div class="btn btn_sponsors"><div class="sponsors"></div></div>';
            $div_val .= '<div class="btn_txt btn_txt_sponsors opacitynone"></div>';
        }
    }

    $div_val .= '</div>';

    //-- Description DIV--
    if ($news['action_type'] != SOCIAL_ACTION_EVENT_CANCEL && ( $news['action_row']['entity_type'] == SOCIAL_ENTITY_MEDIA || $news['action_row']['entity_type'] == SOCIAL_ENTITY_ALBUM || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS || $news['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_LOCATION || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_DATE || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_TIME )) {
        $div_val .= '<div class="descriptionDiv socialInitDiv hide shadow" style="overflow:hidden;">
														<div class="closeDiv"></div>';

        if ($news['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL) {
            $val_db = htmlEntityDecode($news['channel_row']['channel_name']);
            $title = str_replace("\n", "<br/>", $val_db);
            if (strlen($title) > 32) {
                $title = substr($title, 0, 32) . ' ...';
            }
            $description_db = htmlEntityDecode($news['channel_row']['small_description'],0);
            $description = str_replace("\n", "<br/>", $description_db);
        } else {
            $val_db = htmlEntityDecode($news['media_row']['title']);
            $title = str_replace("\n", "<br/>", $val_db);
            if (strlen($title) > 32) {
                $title = substr($title, 0, 32) . ' ...';
            }
            $description_db = htmlEntityDecode($news['media_row']['description'],0);
            $description = str_replace("\n", "<br/>", $description_db);
        }
        $description_str = cut_sentence_length($description, 150, 10);
        $div_val .= '<div class="containerDiv" style="height:auto; margin-top:6px;">
															<div class="yellow_font11">' . $title . '</div>
															<div class="commentsAll scrollpane_description" style="max-height:72px;">
																<div class="albumdesc">' . seoHyperlinkText($description_str) . '</div>
															</div>
															<div class="yellow_font11"></div>
														</div>
													</div>';
    }
    $entity_owner = socialEntityOwner($data_action_type, $data_action_id);
    $is_entity_owner = 0;
    $real_entity_owner = 0;
    if ($userid == $entity_owner) {
        $is_entity_owner = 1;
        $real_entity_owner = 1;
    }
    if ($channel_entity_id != $channel_id_owner && $news['action_type'] != SOCIAL_ACTION_SPONSOR) {
        $is_entity_owner = 0;
    } else if ($news['user_id'] != $channel_id_owner && $news['action_type'] == SOCIAL_ACTION_SPONSOR) {
        $is_entity_owner = 0;
    }
    if ($entity_chid > 0) {
        $channel_name_log = $entity_ch_name_log;
        $channel_url_log = $entity_churl;
        $logo_src_log = $entity_chlogo;
    } else {
        $logo_src_log = $logo_src;
        $channel_url_log = $channel_url;
        $channel_name_log = $channel_name;
    }

    if (intval($channelPrivacyArray['privacy_social']) == 1 || $is_owner == 1) {

        if ($news['action_type'] != SOCIAL_ACTION_EVENT_CANCEL) {
            // --Like DIV--
            $div_val .= '<div class="likesDiv socialInitDiv hide shadow">
														<div class="closeDiv"></div>
														<div class="likeTTL">' . _("LIKES") . ' (<span class="likesNumber">' . $likeNumber . '</span>)</div>';
            if (( userIsLogged() && $userIschannel == 0 ) || $is_owner == 1) {
                $div_val .= '<div class="meDiv">';
                if ($is_owner == 0) {
                    if ($real_entity_owner == 0) {
                        $div_val .= '<a href="' . $s_user_link . '"><img src="' . ReturnLink('media/tubers/small_' . $profilePic) . '" alt="' . $fullName . '" width="45" height="45"/></a>
                                                                                                                                <a class="mynameDiv social_link_a" href="' . $s_user_link . '">' . $fullName . '</a>
                                                                                                                                <div class="likeDiv" data-action="" data-media=""></div>';
                    } else {
                        if (strlen($channel_name_log) > 32) {
                            $channel_name_log = substr($channel_name_log, 0, 32) . ' ...';
                        }
                        $div_val .= '<a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
                                                                                                                                <a class="mynameDiv social_link_a" href="' . $channel_url_log . '">' . $channel_name_log . '</a>';
                    }
                } else {
                    if (strlen($channel_name_log) > 32) {
                        $channel_name_log = substr($channel_name_log, 0, 32) . ' ...';
                    }
                    $div_val .= '<a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
                                                                                                                            <a class="mynameDiv social_link_a" href="' . $channel_url_log . '">' . $channel_name_log . '</a>';
                }
                $div_val .= '</div>';
            } else {
                $div_val .= '<div class="toplikes">
																	<div class="likepop"></div>
																	<div class="meStanDiv displaynone">
<div class="meStanDivText">' . _("you need to have a") . ' <a class="black_link" href="' . ReturnLink('/register') . '">' . _("TT account") . '</a><br />' . _("in order to like") . '</div>
																	</div>
																</div>';
            }
            $div_val .= '<div class="containerDiv" style="height:auto; overflow:hidden;">
															<div class="commentsAll" style="max-height:446px;"><div class="social_not_refreshed"></div></div>
														</div>
														<div class="showMore_like showMore">' . _('show more') . '</div>
													</div>';

            if ($social_can_comment) {
                //-- Comment DIV--
                $div_val .= '<div class="commentsDiv socialInitDiv hide shadow">
															<div class="closeDiv"></div>
															<div class="likeTTL">' . _("COMMENTS") . ' (<span class="commentsNumber">' . $commentsNumber . '</span>)</div>';
                $comlang = _('write a comment / @T Tuber to reply');

                if (userIsLogged() && ( ($userIschannel == 0 && $is_owner == 0) || ($is_owner == 1 && $is_entity_owner == 1) )) {
                    if ($real_entity_owner == 0 || $is_owner == 1) {
                        $div_val .= '<div class="meDiv">';
                        if ($is_owner == 0) {
                            if ($real_entity_owner == 0) {
                                $div_val .= '<a href="' . $s_user_link . '"><img src="' . ReturnLink('media/tubers/small_' . $profilePic) . '" alt="' . $fullName . '" width="45" height="45"/></a>
																	<a class="mynameDiv social_link_a" href="' . $s_user_link . '">' . $fullName . '</a>';
                            }
                        } else {
                            if (strlen($channel_name_log) > 32) {
                                $channel_name_log = substr($channel_name_log, 0, 32) . ' ...';
                            }
                            $div_val .= '<a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
																	 <a class="mynameDiv social_link_a" href="' . $channel_url_log . '">' . $channel_name_log . '</a>';
                        }

                        $div_val .= '<div class="writecommentDiv writecommentDivAuto">
                                                                                                                                        <div class="examples">
                                                                                                                                          <textarea class="mention textareaclass" placeholder="' . $comlang . '" style="height: 28px; overflow: hidden;"></textarea>
                                                                                                                                        </div>
                                                                                                                                    </div>';
                        $div_val .= '</div>';
                    }
                } else if (($userIschannel == 1 && $is_owner == 0) || ($is_owner == 1 && $is_entity_owner == 1)) {
                    $div_val .= '<div class="meStanDiv_comment displaynone disabledmessage">
																	' . _("you need to have a") . ' <a class="black_link" href="' . ReturnLink('/register') . '">' . _("TT account") . '</a><br />' . _("in order to write a comment") . '
																</div>
																<div class="meDiv">
																	<div class="writecommentDiv"><input type="text" name="commentText" value="' . $comlang . '" onblur="addValueInput(this)" onFocus="removeValueInput(this)" data-value="' . $comlang . '" class="notsigned" data-mode="photo"/></div>
																	
																</div>';
                }
                $div_val .= '<div class="containerDiv">
																<div class="commentsAll" style="max-height:412px;"></div>
															</div>
															<div class="showMore_comments showMore">' . _('show more') . '</div>
														   
														</div>';
            }
        }
        if ($social_can_share) {
            if ($news['action_row']['entity_type'] != SOCIAL_ENTITY_CHANNEL_INFO && $news['action_row']['entity_type'] != SOCIAL_ENTITY_CHANNEL_SLOGAN && $news['action_row']['entity_type'] != SOCIAL_ENTITY_CHANNEL_PROFILE && $news['action_row']['entity_type'] != SOCIAL_ENTITY_CHANNEL_COVER) {
                //-- Share DIV--
                $div_val .= '<div class="sharesDiv socialInitDiv hide shadow">
															<div class="closeDiv"></div>
															<div class="likeTTL">' . _("SHARES") . ' (<span class="sharesNumber">' . $sharesNumber . '</span>)</div>';

                if (userIsLogged() && ( ($userIschannel == 0 && $is_owner == 0) || ($is_owner == 1 && $is_entity_owner == 1) )) {
                    if ($real_entity_owner == 0 || $is_owner == 1) {
                        $div_val .= '<div class="meDiv" style="width:243px;">';
                        if ($is_owner == 0) {
                            $div_val .= '<a href="' . $s_user_link . '"><img src="' . ReturnLink('media/tubers/small_' . $profilePic) . '" alt="' . $fullName . '" width="45" height="45"/></a>
																		<a class="mynameDiv social_link_a" style="width:195px;" href="' . $s_user_link . '">' . $fullName . '</a>';
                        } else {
                            if (strlen($channel_name_log) > 32) {
                                $channel_name_log = substr($channel_name_log, 0, 32) . ' ...';
                            }

                            $div_val .= '<a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
																		 <a class="mynameDiv social_link_a" style="width:195px;" href="' . $channel_url_log . '">' . $channel_name_log . '</a>';
                        }
                        $div_val .= '</div>';


                        $div_val .= '<div class="formttl13 formContainer100 margintop26" style="margin-top:6px;">' . _("write something") . '</div>
																<textarea id="invitetext" class="ChaFocus margintop5" onBlur="addValue2(this)" onFocus="removeValue2(this)" data-value="' . _("write something...") . '" style="font-family:Arial, Helvetica, sans-serif; width:249px; height:38px; background-color:#ebebeb;" type="text" name="invitetext">' . _("write something...") . '</textarea>';

                        $div_val .= '<div>
																	<select id="share_select" style="margin-top:14px;">
																		<option value="0" selected="selected" disabled="disabled">' . _('share this entry with...') . '</option>';
                        if ($is_owner == 1 || $userIschannel == 1) {
                            $div_val .= '<option value="6" data-text="connections">' . _('connections') . '</option>';
                        } else {
                            $div_val .= '<option value="2" data-text="friends and followers">' . _('friends and followers') . '</option>
                                    <option value="1" data-text="friends">' . _('friends') . '</option>
																			<option value="3" data-text="followers">' . _('followers') . '</option>';
                        }
                        $div_val .= '<option value="4" data-text="custom">' . _('custom') . '</option>
																		<option value="5" data-text="by mail">' . _('by mail') . '</option>
																	</select>
																</div>';

                        $div_val .= '<div class="formttl13 formContainer100 margintop15" style="margin-top:12px;">' . _('add people (T tubers, emails)') . '</div>
																<div class="peoplecontainer peoplecontainer_boxed formContainer100 margintop2" style="width:251px; background:none">
																	<div class="emailcontainer_boxed emailcontainer_boxed_share" style="width:245px;">
																		<div class="addmore"><input name="addmoretext_brochure" id="addmoretext_brochure" type="text" class="addmoretext_css" value="' . _('add more') . '" data-value="' . _('add more') . '" onFocus="removeValue2(this)" onBlur="addValue2(this)" data-id=""/></div>
																	</div>
																	<div id="share_boxed_send" class="sharepopup_but2 sharepopup_buts">' . _('send') . '</div>
																</div>';
                    }
                } else if (($userIschannel == 1 && $is_owner == 0) || ($is_owner == 1 && $is_entity_owner == 1)) {
                    $div_val .= '<select id="share_select1" onChange="share_selectDisabled(this)">
																	<option value="0" selected="selected" disabled="disabled">' . _('share this entry with...') . '</option>
                            <option value="2">' . _('friends and followers') . '</option>
																	<option value="1">' . _('friends') . '</option>
																	
																	<option value="3">' . _('followers') . '</option>
																	<option value="4">' . _('custom') . '</option>
																	<option value="5">' . _('by mail') . '</option>
																</select>';
                    $div_val .= '<div class="meStanDiv_comment meStanDiv_share displaynone disabledmessage">
																	' . _('you need to have a') . ' <a class="black_link" href="' . ReturnLink('/register') . '">' . _('TT account') . '</a><br />' . _('in order to share this entry.') . '
																</div>';
                }
                $div_val .= '<div class="containerDiv">
																<div class="commentsAll" style="max-height:446px;"></div>
															</div>
															<div class="showMore_shares showMore">' . _('show more') . '</div>
														</div>';
            }
        }
        if ($social_can_rate) {
            if ($news['action_row']['entity_type'] == SOCIAL_ENTITY_MEDIA || $news['action_row']['entity_type'] == SOCIAL_ENTITY_ALBUM || $news['media_row']['post_type'] == SOCIAL_POST_TYPE_PHOTO || $news['media_row']['post_type'] == SOCIAL_POST_TYPE_VIDEO) {
                //-- Rate DIV--
                $ratesNumber = 0;
                $div_val .= '<div class="ratesDiv socialInitDiv hide shadow">
															<div class="closeDiv"></div>
															<div class="likeTTL" style="width:auto;">' . _("RATING") . ' (<span class="ratesNumber">' . $ratesNumber . '</span>)</div>
															<div class="popup_view_rate"></div>';
                if (userIsLogged() && ( ($userIschannel == 0 && $is_owner == 0) || ($is_owner == 1 && $is_entity_owner == 1) )) {
                    if ($real_entity_owner == 0 || $is_owner == 1) {
                        $div_val .= '<div class="meDiv">';
                        if ($is_owner == 0) {
                            $div_val .= '<a href="' . $s_user_link . '"><img src="' . ReturnLink('media/tubers/small_' . $profilePic) . '" alt="' . $fullName . '" width="45" height="45"/></a>
                                                                                                                                            <a class="mynameDiv social_link_a" href="' . $s_user_link . '">' . $fullName . '</a>

                                                                                                                                            <div class="NewsFeed_ModalRating" id="myrating">';
                            $irating = socialRated($userid, $data_action_id, $news['action_row']['entity_type']);
                            if(!$irating) $irating=0;
                            $i = 0;
                            while ($i < $irating) {
                                $div_val .= '<img src="' . ReturnLink('media/images/ratingbig_1.png') . '" width="9" height="9" style="margin:0; padding:0;" class="rating_star"/>';
                                $i++;
                            }
                            while ($i < 5) {
                                $div_val .= '<img src="' . ReturnLink('media/images/ratingbig_0.png') . '" width="9" height="9" style="margin:0; padding:0;" class="rating_star"/>';
                                $i++;
                            }

                            $div_val .= '</div>
                                                                                                                                            <input type="hidden" id="myrating_score" value="' . $irating . '"/>';
                        } else {
                            if (strlen($channel_name_log) > 32) {
                                $channel_name_log = substr($channel_name_log, 0, 32) . ' ...';
                            }


                            $div_val .= '<a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
                                                                                                                                             <a class="mynameDiv social_link_a" href="' . $channel_url_log . '">' . $channel_name_log . '</a>';
                        }
                        $div_val .= '</div>';
                    }
                } else if (($userIschannel == 1 && $is_owner == 0) || ($is_owner == 1 && $is_entity_owner == 1)) {
                    $div_val .= '<div class="toplikes">
																	<div class="ratepop"></div>
																	<div class="meStanDiv displaynone">
																		<div class="meStanDivText">' . _('you need to have a') . ' <a class="black_link" href="' . ReturnLink('register') . '">' . _('TT account') . '</a><br />' . _('in order to rate this entry') . '</div>
																	</div>
																</div>';
                }
                $div_val .= '<div class="containerDiv" style="height:auto; overflow:hidden;">
																<div class="commentsAll" style="max-height:446px;">
																</div>
															</div>
															<div class="showMore_rates showMore">' . _('show more') . '</div>
														</div>'; //---- rateDiv
            }
        }
    }// end privacy

    if ($news['action_type'] != SOCIAL_ACTION_EVENT_CANCEL && ( $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_LOCATION || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_DATE || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_TIME )) {
        if ($user_can_join_check) {
            //-- Join DIV-->
            $div_val .= '<div class="joinsDiv socialInitDiv hide shadow">
														<div class="closeDiv"></div>
														<div class="likeTTL">' . _('JOINING PEOPLE ') . '(<span class="joinsNumber"></span>)</div>';

            // Show the profile header if a user (not a channel).
            if (userIsLogged() && ( ($userIschannel == 0 && $is_owner == 0) || ($is_owner == 1 && $is_entity_owner == 1) )) {
                if ($real_entity_owner == 0 || $is_owner == 1) {
                    if ($is_owner == 0) {
                        $div_val .= '<div class="meDiv">';
                        $div_val .= '<a href="' . $s_user_link . '"><img src="' . ReturnLink('media/tubers/small_' . $profilePic) . '" alt="' . $fullName . '" width="45" height="45"/></a>
																	<a class="mynameDiv social_link_a" href="' . $s_user_link . '">' . $fullName . '</a>';
                        $div_val .= '</div>';
                        $div_val .= '<div id="side_panel_join_text" class="side_panel_join_text"></div>
																	<div style="margin-top:14px; position: relative; float: left;">
																		<div class="join_event_yesnobox">
																			<div class="option_container"><div><input id="event_join_yes" type="radio" name="event_join" value="yes" /><div class="join_event_text">' . _('YES') . '</div></div></div>
																			<div class="option_container"><div><input id="event_join_no" type="radio" name="event_join" value="no" /><div class="join_event_text">' . _('NO') . '</div></div></div>
																		</div>
																		<div id="join_guests" class="join_guests">
																			<div class="join_guests_text">' . _('going with me') . '</div>
																			<select id="join_guests_number">
																				<option value="0">' . _('going alone') . '</option>
                                                                                                                                                                <option value="1">' . _('1 guest') . '</option>
                                                                                                                                                                <option value="2">' . _('2 guests') . '</option>
                                                                                                                                                                <option value="3">' . _('3 guests') . '</option>
                                                                                                                                                                <option value="4">' . _('4 guests') . '</option>
                                                                                                                                                                <option value="5">' . _('5 guests') . '</option>
                                                                                                                                                                <option value="6">' . _('6 guests') . '</option>
                                                                                                                                                                <option value="7">' . _('7 guests') . '</option>
                                                                                                                                                                <option value="8">' . _('8 guests') . '</option>
                                                                                                                                                                <option value="9">' . _('9 guests') . '</option>
                                                                                                                                                                <option value="10">' . _('10 guests') . '</option>
																			</select>
																		</div>
																	</div>';
                    } else {
                        if (strlen($channel_name_log) > 32) {
                            $channel_name_log = substr($channel_name_log, 0, 32) . ' ...';
                        }
                        $div_val .= '<div class="meDiv">';
                        $div_val .= '<a href="' . $channel_url_log . '"><img class="log_channel_logo" alt="'.$channel_name_log.'" src="' . $logo_src_log . '" width="40" height="40" /></a>
																	 <a class="mynameDiv social_link_a" href="' . $channel_url_log . '">' . $channel_name_log . '</a>';
                        $div_val .= '</div>';
                    }
                }
            } else if (($userIschannel == 1 && $is_owner == 0) || ($is_owner == 1 && $is_entity_owner == 1)) {
                $div_val .= '<div id="side_panel_join_text" class="side_panel_join_text"></div>
																<div style="margin-top:14px;">
																	<div class="join_event_yesnobox">
																		<div class="option_container"><div><input class="joins_disabled_radio" type="radio" name="event_join_disabled" value="yes" /><div class="join_event_text">' . _('YES') . '</div></div></div>
																		<div class="option_container"><div><input class="joins_disabled_radio" type="radio" name="event_join_disabled" value="no" /><div class="join_event_text">' . _('NO') . '</div></div></div>
																	</div>
																	<div id="joins_login_error_msg" class="meStanDiv displaynone" style="">
																		<div class="meStanDivText">' . _('you need to have a') . ' <a class="black_link" href="' . ReturnLink('/register') . '">' . _('TT account') . '</a><br />' . _('in order to join this event') . '</div>
																	</div>
																</div>';
            }


            $div_val .= '<div class="containerDiv" style="height:auto; overflow:hidden;">
															<div class="commentsAll" style="max-height:446px;">
															</div>
														</div>
														<div class="showMore_joins showMore">' . _('show more') . '</div>
													</div>'; //-- joinsDiv --
        }

        if (( $news['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL && $channelPrivacyArray['privacy_sponsoring'] == 1 ) || ( ( $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_LOCATION || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_DATE || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_TIME ) && $channelPrivacyArray['privacy_sponsoring_event'] == 1 )) {
            //-- Sponsor DIV-->                    
            $div_val .= '<div class="sponsorsDiv socialInitDiv hide shadow">
														<div class="closeDiv"></div>
														<div class="likeTTL">' . _('SPONSORS') . ' (<span class="sponsorsNumber"></span>)</div>';


            // Allow sponsoring only if the user is a channel but not the owner of this channel.
            if (userIsLogged() && $userIschannel == 1 && $is_owner == 0) {
                if ($real_entity_owner == 0) {

                    $div_val .= '<div id="sponsor_boxed" class="sponsor_boxed">
																<div class="channel_description">';

                    $default_channel_pic = ($defaultchannelarray['logo']) ? photoReturnchannelLogo($defaultchannelarray) : ReturnLink('/media/tubers/tuber.jpg');
                    $default_channel_name = htmlEntityDecode($defaultchannelarray['channel_name']);
                    $div_val .= '<img class="channel_logo" src="' . $default_channel_pic . '" />
																	<div class="channel_name">' . $default_channel_name . '</div>
																</div>';


                    $div_val .= '<div class="sponsorpopup_boxed_container">
																	<div class="formttl13 formContainer100" style="margin-top:6px;">' . _('write something') . '</div>
																	<textarea id="invitetext" class="ChaFocus margintop5" onblur="addValue2(this)" onfocus="removeValue2(this)" data-value="' . _('write something...') . '" style="font-family:Arial, Helvetica, sans-serif; width:245px; height:38px; background-color:#ebebeb;" type="text" name="invitetext">' . _('write something...') . '</textarea>
																	
																	
																	
																	<div class="formttl13 formContainer100 margintop15" style="margin-top:12px;">' . _('add people (emails)') . '</div>
																	<div class="peoplecontainer peoplecontainer_boxed formContainer100 margintop2" style="width:251px; background:none">
																		<div class="emailcontainer_boxed emailcontainer_boxed_share" style="width:245px;">
																			<div class="addmore"><input name="addmoretext_sponsors" id="addmoretext_sponsors" type="text" class="addmoretext_css" value="' . _('add more') . '" data-value="' . _('add more') . '" onfocus="removeValue2(this)" onblur="addValue2(this)" data-id=""/></div>
																		</div>
																		<div id="sponsor_boxed_send" class="sharepopup_but2 sharepopup_buts" data-defaultcid="' . $defaultchannelid . '">' . _('send') . '</div>
																	</div>
																</div>
															</div>';
                }
            } else { // User is not a channel / is not logged in.
                if ($is_owner == 0 && $real_entity_owner == 0) {

                    $div_val .= '<div id="sponsor_disabled_click_handler">
                                                                                                                                    <select id="share_select" style="margin-top:14px;" disabled="disabled">
                                                                                                                                            <option value="0" selected="selected" disabled="disabled">' . _('sponsor this event by selecting...') . '</option>
                                                                                                                                    </select>
                                                                                                                                    <div style="position:absolute; left:0; right:0; top:15px; bottom:0;"></div>
                                                                                                                            </div>
                                                                                                                            <div id="sponsors_require_channel_error_msg" class="meStanDiv displaynone" style="position:relative">
                                                                                                                                    <div class="meStanDivText">' . _('you need to have a ') . '<a class="black_link" href="' . ReturnLink('/CreateChannelForm') . '">' . _('channel page') . '</a><br />' . _('in order to sponsor this event') . '</div>
                                                                                                                            </div>';
                }
            } // Is channel and not user.


            $div_val .= '<div class="containerDiv" style="height:auto; overflow:hidden;">
															<div class="commentsAll" style="max-height:446px;">
															</div>
														</div>
														<div class="showMore_sponsors showMore">' . _('show more') . '</div>
													</div>'; //-- sponsorsDiv --	
        }
    }

    $div_val .= '</div>'; //-- CommentDiv. --

    $div_val .= '<div class="log_bottom_spacer"></div>';
    // end; log_item_list
    $div_val .= '</div>';
} //endforeach;