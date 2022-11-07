<?php
$data['global_get_arra'] = $_GET;
if ($VideoInfo['image_video'] == "v") {
    $page_uri = ReturnVideoUriHashed($VideoInfo);
    $data['RightInsideContainerClass'] = "video_page_mobile";
} else {
    $page_uri = ReturnPhotoUri($VideoInfo);
    $data['RightInsideContainerClass'] = "photo_page_mobile";
}
$link_class = '';
if (!userIsLogged() && $channelid == 0 && !userIsChannel()) {
    $link_class = ' social_link_a_inactive';
}
$txt_edit_init = intval(UriGetArg('edit'));
$txt_like_init = intval(UriGetArg('like'));
$txt_comment_init = intval(UriGetArg('comment'));
$txt_share_init = intval(UriGetArg('share'));
$txt_rate_init = intval(UriGetArg('rate'));
$txt_report_init = intval(UriGetArg('report'));
$real_channel_id = NULL;
$real_channel_id_random = NULL;
$displayVideoClass = true;       
$displayImageClass = true;
$displayNextImage = false;
$displayPrevImage = false;
$displayNextVideo = false;
$displayPrevVideo = false;
$pageI=0;
$pageV=0;
$album_data_val=0;
$photos1_count=0;
$photos2_count=0;
if ($channelid != 0) {
    $real_channel_id = $channelid;
    $real_channel_id_random = -2;
}
$cat_id = NULL;
if ($album_page) {
    $album_str = 'album:';
    $album_name = htmlEntityDecode($AlbumInfo['catalog_name']);
    $album_uri = ReturnAlbumUri($AlbumInfo);
    $options2 = array('catalog_id' => $AlbumInfo['id'], 'channel_id' => $real_channel_id, 'type' => 'a', 'is_owner' => $is_owner, 'n_results' => true);
    $album_count = mediaSearch($options2);    
    $album_data_val = $album_count;
    $seo_bread_crumbs = seoBreadCrumbs(array('entity_id' => $AlbumInfo['id'], 'entity_type' => SOCIAL_ENTITY_ALBUM));
} else {
    $album = mediaGetCatalog($VideoInfo['id']);
    $album_str = '';
    $album_name = '';
    $album_uri = '';
    if ($album) {
        $AlbumInfo = $album;
        $alb_per = userPermittedForMedia(userGetID(), $album, SOCIAL_ENTITY_ALBUM);
        if ($alb_per) {
            $album_str = 'from album:';
            $album_uri = ReturnAlbumUri($album);
            $options2 = array('catalog_id' => $album['id'], 'channel_id' => $real_channel_id, 'type' => 'a', 'is_owner' => $is_owner, 'n_results' => true);
            $album_count = mediaSearch($options2);
            $album_name = htmlEntityDecode($album['catalog_name']);
        }
        $media_catalog_list_id_array = mediaCatalogListId(array('catalog_id' => $album['id'], 'channelid' => $real_channel_id, 'type' => 'a', 'orderby' => 'id', 'order' => 'd', 'is_owner' => $is_owner));
        
        $position = intval(array_search($VideoInfo['id'], $media_catalog_list_id_array)) + 1;        
        $position = (intval($position) != 0) ? intval($position) : 1;
        $album_data_val = $album_count;
        if ($media_album_page) {
            $cat_id = $album['id'];
        }
        $media_catalog_list_id_type = mediaCatalogListId(array('catalog_id' => $album['id'], 'channelid' => $real_channel_id, 'type' => $VideoInfo['image_video'], 'orderby' => 'id', 'order' => 'd', 'is_owner' => $is_owner));
        $positionVI = intval(array_search($VideoInfo['id'], $media_catalog_list_id_type)) + 1;
        $positionVI = (intval($positionVI) != 0) ? intval($positionVI) : 1;
    }
    $array_prev = '';
    $array_next = '';
    $seo_bread_crumbs = seoBreadCrumbs(array('entity_id' => $vid, 'entity_type' => SOCIAL_ENTITY_MEDIA));
    
    if ($media_album_page) {
        $srch_options = array(
            'userid' => $userInfo['id'],
            'channel_id' => $real_channel_id,
            'catalog_id' => $cat_id,
            'is_owner' => $is_owner,
            'type' => 'i',
            'n_results' => true
        );
        $photos1_count = mediaSearch($srch_options);
        $srch_options['type'] = 'v';
        $photos2_count = mediaSearch($srch_options);

        $imLimit = 10;
        $vidLimit = 10;       
        if ($photos2_count == 0) {
           $displayVideoClass = false;
        }
        if ($photos1_count == 0) {
            $displayImageClass = false;
        }
        
        $srch_options = array(
            'limit' => $imLimit,
            'page' => 0,
            'userid' => $userInfo['id'],
            'channel_id' => $real_channel_id,
            'catalog_id' => $cat_id,
            'is_owner' => $is_owner,
            'orderby' => 'id',
            'order' => 'd',
            'type' => 'i'
        );        
        if($VideoInfo['image_video']=='i'){
            $pageI = ceil($positionVI/$imLimit) - 1;
        }
        $srch_options['page'] = $pageI;
        $photos1 = mediaSearch($srch_options);
        
        if($VideoInfo['image_video']=='v'){
            $pageV = ceil($positionVI/$vidLimit) - 1;
        }
        $srch_options['page'] = $pageV;
        $srch_options['limit'] = $vidLimit;
        $srch_options['type'] = 'v';
        $photos2 = mediaSearch($srch_options);
        
        $pagcount1 = ceil($photos1_count / $imLimit);
        if ( $photos1_count > 10 && ($pageI + 1) < $pagcount1) {
            $displayNextImage = true;
        }
        if ( $photos1_count > 10 && $pageI>0) {
            $displayPrevImage = true;
        }
        $pagcount2 = ceil($photos2_count / $vidLimit);
        if ($photos2_count > 10 && ($pageV + 1) < $pagcount2) {
            $displayNextVideo = true;
        }
        if ( $photos2_count > 10 && $pageV>0) {
            $displayPrevVideo = true;
        }

        $options_prev = array(
            'limit' => 1,
            'page' => ($position - 2),
            'userid' => $userInfo['id'],
            'catalog_id' => $cat_id,
            'is_owner' => $is_owner,
            'channel_id' => $real_channel_id,
            'orderby' => 'id',
            'order' => 'd',
            'type' => 'a'
        );
        $options_next = array(
            'limit' => 1,
            'page' => $position,
            'userid' => $userInfo['id'],
            'catalog_id' => $cat_id,
            'is_owner' => $is_owner,
            'channel_id' => $real_channel_id,
            'orderby' => 'id',
            'order' => 'd',
            'type' => 'a'
        );
        $privacy = 0;
        if (userIsLogged() && ($VideoInfo['userid'] == $loggedUser)) {
            $privacy = 0;
        } else if (userIsLogged() && ($VideoInfo['userid'] != $loggedUser)) {
            if (userIsFriend($VideoInfo['userid'], $loggedUser)) {
                $privacy = 1;
            } else {
                $privacy = 2;
            }
        } else {
            $privacy = 2;
        }
        $options_next['public'] = $privacy;
        $options_prev['public'] = $privacy;
        
        $array_next = mediaSearch($options_next);
        if ($array_next) {
            $array_next = $array_next[0];
        } else {
            $array_next = '';
        }
        $array_prev = mediaSearch($options_prev);

        if ($array_prev) {
            $array_prev = $array_prev[0];
        } else {
            $array_prev = '';
        }
    }
}
$uricurpage = UriCurrentPageURL();
$uricurpage1 = urlencode($uricurpage);
$uricurserver = currentServerURL();
$videos_counter=array();
$videos_counter['total']=0;
$photos_counter=array();
$photos_counter['total']=0;
if (!$album_page) {
    if($VideoInfo['image_video']== 'i'){
        $uricurpage1 = $uricurserver.ReturnPhotoUriHashed($VideoInfo,1);
    }else{
        $uricurpage1 = $uricurserver.ReturnVideoUriHashed($VideoInfo,1);
    }
    $photos = videosGetRelatedSolr($VideoInfo, 'i', 5, 0, 1);

    $videos = videosGetRelatedSolr($VideoInfo, 'v', 5, 0, 1);
    $photos_counter = $photos;
    $videos_counter = $videos;
}
if ($VideoInfo['image_video'] == "v") {
    $fb_img = videoReturnSrcSmall($VideoInfo);
} else {
    $fb_img = photoReturnSrcMed($VideoInfo);
}
$data_album = ($album_page || $media_album_page) ? 1 : 0;
$data['fbimg'] = $fb_img;
$data['photopage'] = $photo_page;
$data['dataalbum'] = $data_album;
$data['vindeoInfoId'] = $VideoInfo['id'];
$data['avLink'] = $creator_avatar_link;
$data['linkClass'] = $link_class;
$data['avName'] = $creator_avatar_name;
$data['touristSinceDt'] = $tourist_since_date;
$data['creatorImg'] = $creator_img;
$data['usfollow'] = $usfollow;
$data['usisfriend'] = $usisfriend;
$req_text=_('add as friend');
$req_status=1;
$requestSent = sentRequestOccur($loggedUser,$userId);
if (!$usisfriend && !$requestSent) {
    $acceptRequestSent = userFreindRequestMade($userId, $loggedUser);
    if($acceptRequestSent!=false){
        $req_text=_('accept friendship request');
        $req_status=2;
    }
}
$data['req_status'] = $req_status;
$data['req_text'] = $req_text;
$data['requestSent'] = $requestSent;
$data['channelid'] = $channelid;
$data['addFriendTxt'] = _('add as friend');
$data['userInfName'] = returnUserDisplayName($userInfo);
$data['userInfId'] = $userInfo['id'];
$data['followTxt'] = _('follow');
$data['breadcrumbs'] = $seo_bread_crumbs;
include('parts/social-media-buttons1.php');
$data['albumpage'] = $album_page;
$data['mediaalbumpage'] = $media_album_page;
$data['videosTotal'] = $videos_counter['total'];
$data['photosTotal'] = $photos_counter['total'];

$album_counter=0;
if ($album_page || $media_album_page):
    $srch_options = array(
        'user_id' => $userId,
        'is_owner' => $is_owner,
        'n_results' => true
    );
    $Videos_more = userCatalogRelatedSolr($AlbumInfo, 5, $page);
    $album_counter = count($Videos_more['media']);
    $data['videosmore'] = $Videos_more;
endif;

$data['albumcounter'] = $album_counter;

$chlink = (isset($channelInfo)) ? '&ch_id=' . $channelInfo['id'] : '';
$data['chlink'] = $chlink;
$data['moreVid'] = ReturnLink('parts/morevideoinside.php?page=0&userID=' . $userInfo['id'] . '&oid=' . $VideoInfo['id'] . $chlink);
$data['morePhoto'] = ReturnLink('parts/morephotoinside.php?page=0&userID=' . $userInfo['id'] . '&oid=' . $VideoInfo['id'] . $chlink);

if ($album_page || $media_album_page){
    include('parts/morealbuminside.php');
}else if ($photo_page){
    include('parts/morephotoinside.php');
}else{
    include('parts/morevideoinside.php');
}
if (!$album_page && !$media_album_page && ($videos_counter['total'] > 0 || $photos_counter['total'] > 0)) {
    $data['moreVidLink'] = ReturnLink('parts/morevideoinside.php?page=0&userID=' . $userInfo['id'].'&oid='.$VideoInfo['id'].$chlink);
    $data['morePhLink'] = ReturnLink('parts/morephotoinside.php?page=0&userID=' . $userInfo['id'].'&oid='.$VideoInfo['id']).$chlink;
} else if (($album_page || $media_album_page) && $album_counter > 0) {
    $data['moreAlLink'] = ReturnLink('parts/morealbuminside.php?page=0&oid=' . $AlbumInfo['id']);
}
$data['albumname'] = $album_name;
$data['albumstr'] = $album_str;
$data['albumuri'] = $album_uri;
$data['albumdataval'] = $album_data_val;
$data['vtitle'] = $v_title;
$data['categorytitle'] = $category_title;
$data['categorylink'] = $category_link;
$data['nbviews'] = $nb_views;
$data['likevalue'] = $like_value;
$data['nbcomments'] = $nb_comments;
$data['nbshares'] = $nb_shares;
$data['nbrating'] = $nb_rating;
$data['ratingdata'] = $rating_data;

$data['nbviews_str'] = $nb_views_str;
$data['likevalue_str'] = $like_value_str;
$data['nbcomments_str'] = $nb_comments_str;
$data['nbshares_str'] = $nb_shares_str;
$data['nbrating_str'] = $nb_rating_str;

if ($album_page):
    $data['vid'] = $vid;
    $photoRecord = $VideoInfo;
    if ($photoRecord['image_video'] == "v") {
        $image_src = photoReturnThumbSrc($photoRecord).'?no_cach='.rand();
        $dims = imageGetDimensions($CONFIG['server']['root'] . $photoRecord['fullpath'] . 'thumb_' . $photoRecord['name']);
    } else {
        //$image_src = photoReturnSrcMed($photoRecord);
        $image_src = photoReturnSrcLink( $photoRecord, '').'?no_cach='.rand();
        $dims = imageGetDimensions($CONFIG['server']['root'] . $photoRecord['fullpath'] . '' . $photoRecord['name']);
    }
    if (!$dims) {
        $width = 310;
        $height = 172;
    } else {
        $width = $dims['width'];
        $height = $dims['height'];
    }
    $srch_options = array(
        'userid' => $userInfo['id'],
        'channel_id' => $real_channel_id,
        'catalog_id' => $data_action_id,
        'is_owner' => $is_owner,
        'type' => 'i',
        'n_results' => true
    );
    $photos1_count = mediaSearch($srch_options);
    $srch_options['type'] = 'v';
    $photos2_count = mediaSearch($srch_options);

    $imLimit = 10;
    $vidLimit = 10;       
    if ($photos2_count == 0) {
       $displayVideoClass = false;
    }
    if ($photos1_count == 0) {
        $displayImageClass = false;
    }
    
    if ($photos1_count > 10) {
        $displayNextImage = true;
    }
    if ($photos2_count > 10) {
        $displayNextVideo = true;
    }
    
    $srch_options = array(
        'limit' => $imLimit,
        'page' => 0,
        'userid' => $userInfo['id'],
        'channel_id' => $real_channel_id,
        'catalog_id' => $data_action_id,
        'is_owner' => $is_owner,
        'orderby' => 'id',
        'order' => 'd',
        'type' => 'i'
    );
    
    $photos1 = mediaSearch($srch_options);
    
    $srch_options['limit'] = $vidLimit;
    $srch_options['type'] = 'v';
    $photos2 = mediaSearch($srch_options);



    $new_height = 435;
    $scaleWidth = 696 / $width;
    $scaleHeight = 435 / $height;
    if ($scaleWidth < $scaleHeight) {
        $new_width = $width * $scaleWidth;
        $new_height = $height * $scaleWidth;
    } else {
        $new_width = $width * $scaleHeight;
        $new_height = $height * $scaleHeight;
    }

    $new_width = round($new_width);
    $new_height = round($new_height);
    $style = "width: {$new_width}px; height: {$new_height}px;";
    if (intval($photoRecord['id']) === intval($VideoInfo['id'])) {
        $which_is_default = $i;
        $default_id = $photoRecord['id'];
        $left = (696 - $new_width) / 2;
        $top = (435 - $new_height) / 2;
        $top = round($top);
        $left = round($left);
        $style .= "display:none; border: none; position: absolute; top: {$top}px; left: {$left}px";
        $src = 'src';
    } else {
        $src = 'data-src';
        $style .= "display:none; border: none;";
    }

    $data['theImg1'] = sprintf('<img data-rel="%s" %s="%s" data-title="%s" alt="%s" style="%s"/>', $photoRecord['id'], $src, $image_src, htmlEntityDecode($photoRecord['title']), htmlEntityDecode($photoRecord['title']), $style);

    
    $data['dataactionid'] = $data_action_id;
    $photo1Arr = array();
    foreach ($photos1 as $photoRecord) {
        $aPhoto1Arr = array();
        if ($photoRecord['image_video'] == "v") {
            $media_uri = ReturnVideoAlbumUri($photoRecord);
            $enlarge_class = 'enlarge_video';
        } else {
            $media_uri = ReturnPhotoAlbumUri($photoRecord);
            $enlarge_class = 'enlarge_photo';
        }
        $aPhoto1Arr['mediauri'] = $media_uri;
        $aPhoto1Arr['id'] = $photoRecord['id'];
        $aPhoto1Arr['class'] = $enlarge_class;
        $aPhoto1Arr['xsmall'] = photoReturnSrcXSmall($photoRecord);
        $aPhoto1Arr['comment'] = ceil($photoRecord['nb_comments'] / VideoGetCommentsPerPage());
        $aPhoto1Arr['title'] = addslashes(htmlEntityDecode($photoRecord['title']));
        $aPhoto1Arr['url'] = addslashes(ReturnLink('photo/' . $photoRecord['video_url']));

        $photo1Arr[] = $aPhoto1Arr;
    }
    $data['photo1Arr'] = $photo1Arr;
//    exit(var_dump($photo1Arr));
    $photo2Arr = array();
    foreach ($photos2 as $photoRecord) {
        $aPhoto2Arr = array();
        if ($photoRecord['image_video'] == "v") {
            $media_uri = ReturnVideoAlbumUri($photoRecord);
            $enlarge_class = 'enlarge_video';
        } else {
            $media_uri = ReturnPhotoAlbumUri($photoRecord);
            $enlarge_class = 'enlarge_photo';
        }
        $aPhoto2Arr['mediauri'] = $media_uri;
        $aPhoto2Arr['id'] = $photoRecord['id'];
        $aPhoto2Arr['class'] = $enlarge_class;
        $aPhoto2Arr['xsmall'] = photoReturnSrcXSmall($photoRecord);
        $aPhoto2Arr['comment'] = ceil($photoRecord['nb_comments'] / VideoGetCommentsPerPage());
        $aPhoto2Arr['title'] = addslashes(htmlEntityDecode($photoRecord['title']));
        $aPhoto2Arr['url'] = addslashes(ReturnLink('photo/' . $photoRecord['video_url']));
        $photo2Arr[] = $aPhoto2Arr;
    }
    $data['photo2Arr'] = $photo2Arr; 
    
elseif ($photo_page):

    $limit = 10;
    $srch_options = array(
        'page' => $page,
        'limit' => $limit,
        'type' => 'i',
        'vid' => $oid
    );
//    $srch_options['max_id'] = isset($_GET['max_id']) ? intval($_GET['max_id']) : 0;
    $srch_options['max_id'] = intval($request->query->get('max_id',0));

    $favorite = '';
    $data_status_favorite = '';
    $data_is_favorite = _('add to favorites');
    if (userFavoriteAdded($loggedUser, $VideoInfo['id'])) {
        $favorite = ' active';
        $data_is_favorite = _('remove from favorites');
        $data_status_favorite = 'yellow';
    }

    $data['oid'] = $oid;

    $i = 0;
    $which_is_default = -1;
    $default_id = -1;
    $skipPrev = 0;
    $skipNext = 0;
    //foreach($photos as $photoRecord){
    $photoRecord = $VideoInfo;
    $image_link = ReturnPhotoUri($photoRecord);
    //$image_src = photoReturnSrcMed($photoRecord);
    $image_src = photoReturnSrcLink($photoRecord, '').'?no_cach='.rand();
    $image_src_org = photoReturnSrc($photoRecord).'?no_cach='.rand();

    $dims = imageGetDimensions($CONFIG['server']['root'] . $photoRecord['fullpath'] . '' . $photoRecord['name']);
    $width = $dims['width'];
    $height = $dims['height'];
    //$new_width = 697;
    //$new_height = intval($height*$new_width/$width);
    $new_height = 435;
    $scaleWidth = 696 / $width;
    $scaleHeight = 435 / $height;
    
    if ($scaleWidth < $scaleHeight) {
        $new_width = $width * $scaleWidth;
        $new_height = $height * $scaleWidth;
    } else {
        $new_width = $width * $scaleHeight;
        $new_height = $height * $scaleHeight;
    }
    $new_width = round($new_width);
    $new_height = round($new_height);
    $style = "width: {$new_width}px; height: {$new_height}px;";
    if (intval($photoRecord['id']) === intval($VideoInfo['id'])) {
        $which_is_default = $i;
        $default_id = $photoRecord['id'];
        $left = (696 - $new_width) / 2;
        $top = (435 - $new_height) / 2;
        $top = round($top);
        $left = round($left);
        $style .= "display:none; border: none; position: absolute; top: {$top}px; left: {$left}px";
        $src = 'src';
    } else {
        $src = 'data-src';
        $style .= "display:none; border: none;";
    }
    if ($array_prev != '') {
        $array_prev_org = photoReturnSrc($array_prev);
        $prev_title_org = addslashes(htmlEntityDecode($array_prev['title']));
        if ($media_album_page) {
            if ($array_prev['image_video'] == "v") {
                $media_uri = ReturnVideoAlbumUri($array_prev);
            } else {
                $media_uri = ReturnPhotoAlbumUri($array_prev);
            }
        } else {
            if ($array_prev['image_video'] == "v") {
                $media_uri = ReturnVideoUriHashed($array_prev);
            } else {
                $media_uri = ReturnPhotoUri($array_prev);
            }
        }
    }
    if ($array_next != '') {
        $array_next_org = photoReturnSrc($array_next);
        $next_title_org = addslashes(htmlEntityDecode($array_next['title']));
        if ($media_album_page) {
            if ($array_next['image_video'] == "v") {
                $media_uri1 = ReturnVideoAlbumUri($array_next);
            } else {
                $media_uri1 = ReturnPhotoAlbumUri($array_next);
            }
        } else {
            if ($array_next['image_video'] == "v") {
                $media_uri1 = ReturnVideoUriHashed($array_next);
            } else {
                $media_uri1 = ReturnPhotoUri($array_next);
            }
        }
    }
    $data['arrayprev'] = $array_prev;
    $data['arraynext'] = $array_next;
    if ($array_prev != '') {
        $data['mediauri0'] = $media_uri;
        $data['arrayprevId'] = $array_prev['id'];
        $data['arrayprevorg'] = $array_prev_org;
        $data['prevtitleorg'] = $prev_title_org;
    }
    if ($array_next != '') {
        $data['mediauri1'] = $media_uri1;
        $data['arraynextId'] = $array_next['id'];
        $data['arraynextorg'] = $array_next_org;
        $data['nexttitleorg'] = $next_title_org;
    }
    $data['imgInPhotoPage'] = sprintf('<img data-rel="%s" %s="%s" data-title="%s" alt="%s" style="%s" data-org="%s"/>', $photoRecord['id'], $src, $image_src, htmlEntityDecode($photoRecord['title']), htmlEntityDecode($photoRecord['title']), $style, $image_src_org);
    if ($which_is_default == -1)
        $skipPrev++;
    else if ($which_is_default != $i)
        $skipNext++;

    $i++;
    //}

    $data['defaultid'] = $default_id;

    $data['favorite'] = $favorite;
    $data['dataisfavorite'] = $data_is_favorite;
    $data['datastatusfavorite2'] = $data_status_favorite;
    if (!$media_album_page):
        $photoMediaAlbumArr = array();

        foreach ($photos['media'] as $key => $photoRecord) {
            $aphotoMediaAlbumArr = array();
            if ($photoRecord['image_video'] == "v") {
                $media_uri = ReturnVideoUriHashed($photoRecord);
                $enlarge_class = 'enlarge_video';
            } else {
                $media_uri = ReturnPhotoUri($photoRecord);
                $enlarge_class = 'enlarge_photo';
            }
            $aphotoMediaAlbumArr['mediauri'] = $media_uri;
            $aphotoMediaAlbumArr['enlargeclass'] = $enlarge_class;
            $aphotoMediaAlbumArr['xsmall'] = photoReturnSrcXSmall($photoRecord);
            $aphotoMediaAlbumArr['comment'] = ceil($photoRecord['nb_comments'] / VideoGetCommentsPerPage());
            $aphotoMediaAlbumArr['id'] = $photoRecord['id'];
            $aphotoMediaAlbumArr['title'] = addslashes(htmlEntityDecode($photoRecord['title']));
            $aphotoMediaAlbumArr['url'] = addslashes(ReturnLink('photo/' . $photoRecord['video_url']));
            $photoMediaAlbumArr[] = $aphotoMediaAlbumArr;
        }
        $data['photoMediaAlbumArr'] = $photoMediaAlbumArr;
        $data['photoMediaAlbumTotal'] = $photos['total'];

    else:
        $photo1MediaAlbumArr = array();
        foreach ($photos1 as $photoRecord):
            $aphoto1MediaAlbumArr = array();
            if ($photoRecord['image_video'] == "v") {
                $media_uri = ReturnVideoAlbumUri($photoRecord);
                $enlarge_class = 'enlarge_video';
            } else {
                $media_uri = ReturnPhotoAlbumUri($photoRecord);
                $enlarge_class = 'enlarge_photo';
            }
            $aphoto1MediaAlbumArr['mediauri'] = $media_uri;
            $aphoto1MediaAlbumArr['id'] = $photoRecord['id'];
            $aphoto1MediaAlbumArr['enlargeclass'] = $enlarge_class;
            $aphoto1MediaAlbumArr['xsmall'] = photoReturnSrcXSmall($photoRecord);
            $aphoto1MediaAlbumArr['comment'] = ceil($photoRecord['nb_comments'] / VideoGetCommentsPerPage());
            $aphoto1MediaAlbumArr['title'] = addslashes(htmlEntityDecode($photoRecord['title']));
            $aphoto1MediaAlbumArr['url'] = addslashes(ReturnLink('photo/' . $photoRecord['video_url']));
            $photo1MediaAlbumArr[] = $aphoto1MediaAlbumArr;
        endforeach;
        $data['photo1MediaAlbumArr'] = $photo1MediaAlbumArr;
        $photo2MediaAlbumArr = array();
        foreach ($photos2 as $photoRecord):
            $aphoto2MediaAlbumArr = array();
            if ($photoRecord['image_video'] == "v") {
                $media_uri = ReturnVideoAlbumUri($photoRecord);
                $enlarge_class = 'enlarge_video';
            } else {
                $media_uri = ReturnPhotoAlbumUri($photoRecord);
                $enlarge_class = 'enlarge_photo';
            }
            $aphoto2MediaAlbumArr['mediauri'] = $media_uri;
            $aphoto2MediaAlbumArr['id'] = $photoRecord['id'];
            $aphoto2MediaAlbumArr['enlargeclass'] = $enlarge_class;
            $aphoto2MediaAlbumArr['xsmall'] = photoReturnSrcXSmall($photoRecord);
            $aphoto2MediaAlbumArr['comment'] = ceil($photoRecord['nb_comments'] / VideoGetCommentsPerPage());
            $aphoto2MediaAlbumArr['title'] = addslashes(htmlEntityDecode($photoRecord['title']));
            $aphoto2MediaAlbumArr['url'] = addslashes(ReturnLink('photo/' . $photoRecord['video_url']));
            $photo2MediaAlbumArr[] = $aphoto2MediaAlbumArr;
        endforeach;
        $data['photo2MediaAlbumArr'] = $photo2MediaAlbumArr;
    endif;

    $data['iframeSrc1'] = $uricurserver . ReturnLink('embed/' . md5($VideoInfo['id'] . '' . $VideoInfo['video_url']));
else:
    $res = getVideoResolutions($vid, '');
    $i = count($res);
    $call = array();
    $js_call = array();

    $str = "";
    $Len = 9;
    if (count($videos['media']) < 9) {
        $Len = count($videos['media']);
    }
    $videoStr = '';

//$videoRecord['video_url'];
    for ($i = 0; $i < $Len; $i++) {
        $videoRecord = $videos['media'][$i];
        $videoStr .= '' . videoReturnThumbSrc($videoRecord) . '[*]' . videoToURLHashed($videoRecord) . '[*]' . htmlEntityDecode($videoRecord['title']) . '[*]' . $videoRecord['name'] . '[*]' . $videoRecord['fullpath'] . '[*]' . $videoRecord['id'];
        if ($i < ($Len - 1)) {
            $videoStr .= '/*/';
        }
    }
    $i = count($res);
    while ($i > 0) {
        $call[] = sprintf("%s::%s", ReturnLink($res[$i - 1], null, 1), $i);
        $js_call[] = ReturnLink($res[$i - 1], null, 1);
        $i--;
    }

    $js_call_reversed = array_reverse($js_call);
    
    $favorite = '';
    $data_status_favorite = '';
    $data_is_favorite = _('add to favorites');
    if (userFavoriteAdded($loggedUser, $vid)) {
        $favorite = ' active';
        $data_is_favorite = _('remove from favorites');
        $data_status_favorite = 'yellow';
    }
    
    $like = videoVoted($vid, $loggedUser);
    if ($like == 1)
        $like = '+1';
    if ($like == false)
        $like = 0;
    $rating = round(socialRateGet($loggedUser, $VideoInfo['id'], SOCIAL_ENTITY_MEDIA), 0);
    if ($rating == false)
        $rating = 0;
    $description_db = htmlEntityDecode($VideoInfo['description'],0);

    $data['favorite'] = $favorite;
    $data['dataisfavorite'] = $data_is_favorite;
    $data['datastatusfavorite2'] = $data_status_favorite;
    
    $favorite2 = (userFavoriteAdded($loggedUser, $vid)) ? 'yes' : 'no';
    $data['ThumbPath2'] = '';//ReturnLink(videoGetInstantThumbPath2($VideoInfo));
    $data['countcall2'] = count($call);
    $data['favorite2'] = $favorite2;
    $data['like2'] = $like;
    $data['rating2'] = $rating;    
    $data['call2'] = implode('/*/', $call);    
    $data['res_list'] = implode('/*/', $res);
    $data['title2'] = addslashes(htmlEntityDecode($VideoInfo['title']));
    $data['descriptiondb2'] = str_replace("\n", "<br/>", addslashes($description_db));
    $data['thumbSrc2'] = videoReturnThumbSrc($VideoInfo);
    
    $vid_arr = explode(":", $call[count($call) - 1]);
    $data['firstCall2'] = $vid_arr[0];

    $data['jscallreversed2'] = implode("','", $js_call_reversed);
    $data['vid2'] = $vid;
    $data['videoStr2'] = $videoStr;

    $skipPrev = 0;
    $skipNext = 0;
    $which_is_default = -1;

    foreach ($videos['media'] as $videoRecord) {
        if (intval($videoRecord['id']) === intval($VideoInfo['id'])) {
            $which_is_default = $i;
        } else {
            
        }

        if ($which_is_default == -1)
            $skipPrev++;
        else if ($which_is_default != $i)
            $skipNext++;

        $i++;
    }
    if ($array_prev != '') {
        $array_prev_org = photoReturnSrc($array_prev);
        $prev_title_org = addslashes(htmlEntityDecode($array_prev['title']));
        if ($media_album_page) {
            if ($array_prev['image_video'] == "v") {
                $media_uri = ReturnVideoAlbumUri($array_prev);
            } else {
                $media_uri = ReturnPhotoAlbumUri($array_prev);
            }
        } else {
            if ($array_prev['image_video'] == "v") {
                $media_uri = ReturnVideoUriHashed($array_prev);
            } else {
                $media_uri = ReturnPhotoUri($array_prev);
            }
        }
    }
    if ($array_next != '') {
        $array_next_org = photoReturnSrc($array_next);
        $next_title_org = addslashes(htmlEntityDecode($array_next['title']));
        if ($media_album_page) {
            if ($array_next['image_video'] == "v") {
                $media_uri1 = ReturnVideoAlbumUri($array_next);
            } else {
                $media_uri1 = ReturnPhotoAlbumUri($array_next);
            }
        } else {
            if ($array_next['image_video'] == "v") {
                $media_uri1 = ReturnVideoUriHashed($array_next);
            } else {
                $media_uri1 = ReturnPhotoUri($array_next);
            }
        }
    }
    if ($array_prev != '') {
        $data['mediauri10'] = $media_uri;
        $data['arrayprevId1'] = $array_prev['id'];
        $data['arrayprevorg1'] = $array_prev_org;
        $data['prevtitleorg1'] = $prev_title_org;
    }
    if ($array_next != '') {
        $data['mediauri11'] = $media_uri1;
        $data['arraynextId1'] = $array_next['id'];
        $data['arraynextorg1'] = $array_next_org;
        $data['nexttitleorg1'] = $next_title_org;
    }
    if (!$media_album_page):
        $medalbvideoesArr = array();
        foreach ($videos['media'] as $key => $videoRecord) {
            $amedalbvideoesArr = array();

            if ($videoRecord['image_video'] == "v") {
                $media_uri = ReturnVideoUriHashed($videoRecord);
                $enlarge_class = 'enlarge_video';
            } else {
                $media_uri = ReturnPhotoUri($videoRecord);
                $enlarge_class = 'enlarge_photo';
            }

            $amedalbvideoesArr['id'] = $videoRecord['id'];
            $amedalbvideoesArr['mediauri'] = $media_uri;
            $amedalbvideoesArr['enlargeclass'] = $enlarge_class;
            $amedalbvideoesArr['xsmall'] = photoReturnSrcXSmall($videoRecord);
            $amedalbvideoesArr['url'] = $videoRecord['video_url'];
            $amedalbvideoesArr['comment'] = ceil($videoRecord['nb_comments'] / VideoGetCommentsPerPage());
            $amedalbvideoesArr['title'] = addslashes(htmlEntityDecode($videoRecord['title']));
            $medalbvideoesArr[] = $amedalbvideoesArr;
        }
        $data['medalbvideoesArr'] = $medalbvideoesArr;
        $data['videostotal'] = $videos['total'];

    else:
        $medalbphotosArr = array();
        foreach ($photos1 as $photoRecord):
            $amedalbphotosArr = array();
            if ($photoRecord['image_video'] == "v") {
                $media_uri = ReturnVideoAlbumUri($photoRecord);
                $enlarge_class = 'enlarge_video';
            } else {
                $media_uri = ReturnPhotoAlbumUri($photoRecord);
                $enlarge_class = 'enlarge_photo';
            }
            $amedalbphotosArr['id'] = $photoRecord['id'];
            $amedalbphotosArr['mediauri'] = $media_uri;
            $amedalbphotosArr['enlargeclass'] = $enlarge_class;
            $amedalbphotosArr['xsmall'] = photoReturnSrcXSmall($photoRecord);
            $amedalbphotosArr['url'] = addslashes(ReturnLink('photo/' . $photoRecord['video_url']));
            $amedalbphotosArr['comment'] = ceil($photoRecord['nb_comments'] / VideoGetCommentsPerPage());
            $amedalbphotosArr['title'] = addslashes(htmlEntityDecode($photoRecord['title']));
            $medalbphotosArr[] = $amedalbphotosArr;
        endforeach;
        $data['medalbphotosArr'] = $medalbphotosArr;
        $medalbphotosArr2 = array();
        foreach ($photos2 as $photoRecord):
            $amedalbphotosArr2 = array();
            if ($photoRecord['image_video'] == "v") {
                $media_uri = ReturnVideoAlbumUri($photoRecord);
                $enlarge_class = 'enlarge_video';
            } else {
                $media_uri = ReturnPhotoAlbumUri($photoRecord);
                $enlarge_class = 'enlarge_photo';
            }
            $amedalbphotosArr2['id'] = $photoRecord['id'];
            $amedalbphotosArr2['mediauri'] = $media_uri;
            $amedalbphotosArr2['enlargeclass'] = $enlarge_class;
            $amedalbphotosArr2['xsmall'] = photoReturnSrcXSmall($photoRecord);
            $amedalbphotosArr2['url'] = addslashes(ReturnLink('photo/' . $photoRecord['video_url']));
            $amedalbphotosArr2['comment'] = ceil($photoRecord['nb_comments'] / VideoGetCommentsPerPage());
            $amedalbphotosArr2['title'] = addslashes(htmlEntityDecode($photoRecord['title']));
            $medalbphotosArr2[] = $amedalbphotosArr2;

        endforeach;
        $data['medalbphotosArr2'] = $medalbphotosArr2;
    endif;
    $data['iframeSrcLast'] = $uricurserver . ReturnLink('embed/' . md5($VideoInfo['id'] . '' . $VideoInfo['video_url']));
    $data['skipNextLast'] = $skipNext;
    $data['skipPrevLast'] = $skipPrev;
    $data['oidLast'] = $oid;
    $data['defaultLast'] = $which_is_default;

endif;
$data['displayNextImage'] = $displayNextImage;
$data['displayPrevImage'] = $displayPrevImage;
$data['displayNextVideo'] = $displayNextVideo;
$data['displayPrevVideo'] = $displayPrevVideo;
$singleMediaTypeClass = '';
if ($photos1_count == 0 && $photos2_count > 0) {
    $singleMediaTypeClass = ' onlyVideo';
} else if ($photos2_count == 0 && $photos1_count > 0) {
    $singleMediaTypeClass = ' onlyImage';
}
$data['uricurpage'] = xss_sanitize($uricurpage);
$data['uricurpage1'] = $uricurpage1;
$data['vtitleshare'] = $v_title_share;
$data['vtitleshareUrl'] = urlencode($v_title_share);
$data['description'] = $description;

$data['singleMediaTypeClass'] = $singleMediaTypeClass;
$data['displayVideoClass'] = $displayVideoClass;
$data['displayImageClass'] = $displayImageClass;
$data['pageI'] = $pageI;
$data['pageV'] = $pageV;
$data['catid'] = $cat_id;
$data['user_is_logged'] = $user_is_logged;
$data['profile_empty_pic'] = $userInfo['profile_empty_pic'];
$srch_optionsnew = array(
    'user_id' => $userInfo['id'],
    'n_results' => true
);
$detail_count = getUserDetailSearch($srch_optionsnew);
if($detail_count>0){
    $data['viewAllPILink'] = userProfileLink($userInfo) . '/profile-images';
}else{
    $data['viewAllPILink'] = '';
}
$data['md5_userid'] =  md5($userInfo['profile_id'].$userInfo['id']);
$data['social_type'] =  SOCIAL_ENTITY_USER_PROFILE;
include('parts/commentVideoSection.php');