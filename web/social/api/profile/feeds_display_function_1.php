<?php
/*
 * Function to get information file exist inside directry
 */
function checkfile_exist($in_path) {
    global $CONFIG;
    $path = $CONFIG['server']['root'] . $in_path;
    if (file_exists($path)) {
        $fullPath = $in_path;
    } else {
        $fullPath = 'media/images/unavailable-preview.gif';
        
    }
    return $fullPath; 
}

function sprintfn ($format, array $args = array()) {
    // map of argument names to their corresponding sprintf numeric argument value
    $arg_nums = array_slice(array_flip(array_keys(array(0 => 0) + $args)), 1);

    // find the next named argument. each search starts at the end of the previous replacement.
    for ($pos = 0; preg_match('/(?<=%)([a-zA-Z_]\w*)(?=\$)/', $format, $match, PREG_OFFSET_CAPTURE, $pos);) {
        $arg_pos = $match[0][1];
        $arg_len = strlen($match[0][0]);
        $arg_key = $match[1][0];

        // programmer did not supply a value for the named argument found in the format string
        if (! array_key_exists($arg_key, $arg_nums)) {
            user_error("sprintfn(): Missing argument '${arg_key}'", E_USER_WARNING);
            return false;
        }

        // replace the named argument with the corresponding numeric one
        $format = substr_replace($format, $replace = $arg_nums[$arg_key], $arg_pos, $arg_len);
        $pos = $arg_pos + strlen($replace); // skip to end of replacement for next iteration
    }

    return vsprintf($format, array_values($args));
}
function feeds_display($news_feed){
    $uricurserver = currentServerURL();
    global $user_id;
    $userPrivacyArray= getUserNotifications($user_id);
    $userPrivacyArray=$userPrivacyArray[0];
    $ret_arr = array();
    $ret_arr['feeds'] = array();
    foreach ( $news_feed as $vfeeditem ){    //echo "<pre>";print_r($vfeeditem['media_row']);die;    //echo $vfeeditem['entity_type'].'_'.$vfeeditem['action_row']['entity_type'];die; 
        if($vfeeditem['entity_type'] == SOCIAL_ENTITY_WEBCAM || $vfeeditem['entity_type'] == SOCIAL_ENTITY_JOURNAL || $vfeeditem['entity_type'] == SOCIAL_ENTITY_BAG || $vfeeditem['entity_id'] == 0){
            continue;
        }
        if(isset($exept_array)){
        
            $data_action_id = $vfeeditem['action_row']['entity_id'];
            $action_row_count = $vfeeditem['action_row_count'];
            $action_row_other = $vfeeditem['action_row_other'];
            $media_row = $vfeeditem['media_row'];
            $tuber_uslnk_action = $tuber_uslnk;
            if (!is_array($vfeeditem['action_row']) || !is_array($media_row)) {
                continue;
            }
            if (in_array($vfeeditem['action_row']['entity_type'], $exept_array) || in_array($vfeeditem['entity_type'], $exept_array)) {
                continue;
            }
            if (($vfeeditem['action_row']['entity_type'] == SOCIAL_ENTITY_BAG || $vfeeditem['entity_type'] == SOCIAL_ENTITY_BAG) && $is_owner == 0) {
                continue;
            }
            $hide_all_social = 0;
            if ($vfeeditem['entity_type'] == SOCIAL_ENTITY_USER && ( $vfeeditem['action_type'] == SOCIAL_ACTION_FOLLOW || $vfeeditem['action_type'] == SOCIAL_ACTION_FRIEND )) {
                $hide_all_social = 1;
            } else if ($vfeeditem['entity_type'] == SOCIAL_ENTITY_VISITED_PLACES) {
                $hide_all_social = 1;
            }
        }
        $channelInfo = null;
        $channel_header='';
        $channel_logo = '';
        $echo_text = '';
        $fullLink = '';
        $target_name = '';
        $target_id = '';
        $other = '';
        $otherId = '';
        $loop_ret_row = array();
        $loop_ret_row['shareLink'] = '';
        $loop_ret_row['share_image'] = '';
        $overhead_text = '';
        $address='';
        $register_date='';
        $nb_followers='';
        $nb_friends='';
        $post_video_link='';
        $videoRecord = $vfeeditem['media_row'];       //echo "<pre>";print_r($videoRecord);die;
        $actionRecord = $vfeeditem['action_row'];
        $action_row = $actionRecord;     
        $action_row_count = $vfeeditem['action_row_count'];
        $action_row_other = $vfeeditem['action_row_other'];
        $other_img = array();
        $action_id = $vfeeditem['action_id'];
        $is_visible = $vfeeditem['is_visible'];
        $entity_owner = $vfeeditem['owner_id'];
        $channelId = $vfeeditem['channel_id'];
        $channel_id=0;
        $entity_type = $vfeeditem['entity_type'];
        /*hotel info*/
        switch($entity_type){   
            case SOCIAL_ENTITY_HOTEL_REVIEWS: 
                $hotel_id= $videoRecord['hotel_id'];
                $info_data = getHotelInfo($hotel_id);
                $target_id = $hotel_id;
                $target_name=$info_data['hotelName'];
            break;    
            case SOCIAL_ENTITY_RESTAURANT_REVIEWS:
                $rest_id= $videoRecord['restaurant_id'];
                $info_data = getRestaurantInfo($rest_id);
                $target_id = $rest_id;
                $target_name = $info_data['name'];
            break; 
            case SOCIAL_ENTITY_LANDMARK_REVIEWS:
                $poi_id= $videoRecord['poi_id'];
                $info_data = getPoiInfo($poi_id);
                $target_id = $poi_id;
                $target_name = $info_data['name'];
            break;
            case SOCIAL_ENTITY_AIRPORT_REVIEWS:
                $airport_id= $videoRecord['airport_id'];
                $info_data = getAirportInfo($airport_id);
                $target_id = $airport_id;
                $target_name = $info_data['name'];
            break; 
        }
        
        /**/
        /*post vedio path link*/
        if($entity_type == SOCIAL_ENTITY_POST && $videoRecord['media_file'] != ''){
            $post_video_link = videoGetPostPath($videoRecord, '');
        }
     //echo "<pre>";print_r($post_video_link);die;  
        /**/
        if (intval($videoRecord['channelid']) > 0) {
            $channel_id = intval($videoRecord['channelid']);
        }else if (intval($videoRecord['channel_id']) > 0) {
            $channel_id = intval($videoRecord['channel_id']);
        }
        $owner_type = $channel_id > 0 ? 'channel' : 'user';
        $owner_id = '';
        $owner_name = '';

        $gender = 'his';
        if($vfeeditem['gender']=='F'){
                $gender = 'her';
        }
        $userId = $vfeeditem['user_id'];
        $action_user_profile = getUserInfo($userId);
        $action_profile = $action_user_profile;
        $username = returnUserDisplayName($action_user_profile);

        $video_photo = ( strstr($videoRecord['type'],'image') == null ) ? 'video' : 'photo';

        $valid = true;
        $feed_ts = strtotime($vfeeditem['feed_ts']);
        $loop_ret_row['feed_date'] = formatDateAsString($feed_ts);
        $feed_user_id = $vfeeditem['user_id'];
        $user_id_feed = $vfeeditem['user_id'];

        //$entity_id = $videoRecord['id'];
        $entity_id = $vfeeditem['entity_id']; //the entity_id  for the album is not the id of the media record
        
        $album = null;
        if ($entity_type == SOCIAL_ENTITY_ALBUM) { 
            $album = userCatalogGet($entity_id);
        } else if($entity_type == SOCIAL_ENTITY_MEDIA) {
            $album = mediaGetCatalog($entity_id);
        }
    //echo "<pre>";print_r($vfeeditem['media_row']);
        $action_type = $vfeeditem['action_type'];

        $uploaded_on = $entity_type == '1' ? $videoRecord['pdate'] : '';

        // Variable to check if the "enable shares and comments" is enabled.
        $enable_shares_comments = array(
                'data_status' => 1,
                'class' => ''
        );

        if($entity_owner!=$userId){
            $userPrivacyArray_check= getUserNotifications($entity_owner);
            $userPrivacyArray_check=$userPrivacyArray_check[0];
        }else{
            $userPrivacyArray_check=$userPrivacyArray; 
        }
 
        $social_can_share=true;
        $social_can_comment=true;
        $social_can_like=true;
        $social_can_rate=true;
        $user_can_join_check=true;
        if( $entity_owner == $userId ) {
                $social_can_like=true;
                $social_can_share=true;
                $social_can_comment=true;
                $social_can_rate=true;
                $user_can_join_check=true;
        }else{ 
            if($channel_id>0){
                $userPrivacyArray_check = GetChannelNotifications($channel_id);
                $userPrivacyArray_check = $userPrivacyArray_check[0];
                if (( $actionRecord['entity_type'] == SOCIAL_ENTITY_CHANNEL || $actionRecord['entity_type'] == SOCIAL_ENTITY_CHANNEL_INFO || $actionRecord['entity_type'] == SOCIAL_ENTITY_CHANNEL_PROFILE || $actionRecord['entity_type'] == SOCIAL_ENTITY_CHANNEL_SLOGAN || $actionRecord['entity_type'] == SOCIAL_ENTITY_CHANNEL_COVER ) && $userPrivacyArray_check['privacy_sharing'] == 0) {
                    $social_can_share = false;
                }else if( ( $actionRecord['entity_type'] == SOCIAL_ENTITY_EVENTS || $actionRecord['entity_type'] == SOCIAL_ENTITY_EVENTS_LOCATION || $actionRecord['entity_type'] == SOCIAL_ENTITY_EVENTS_DATE || $actionRecord['entity_type'] == SOCIAL_ENTITY_EVENTS_TIME ) && $userPrivacyArray_check['privacy_sharescomments'] == 0 ){
                    $social_can_share=false;
                    $social_can_comment=false;
                } else if( $userPrivacyArray_check['privacy_social'] == 0 ){                     
                    $social_can_share=false;
                    $social_can_comment=false;                    
                }
            }else{
                if( ( $actionRecord['entity_type'] == SOCIAL_ENTITY_USER_EVENTS || $actionRecord['entity_type'] == SOCIAL_ENTITY_EVENTS_LOCATION || $actionRecord['entity_type'] == SOCIAL_ENTITY_EVENTS_DATE || $actionRecord['entity_type'] == SOCIAL_ENTITY_EVENTS_TIME ) && $userPrivacyArray_check['privacy_eventsharescomments'] == 0 ){
                        $social_can_share=false;
                        $social_can_comment=false;
                } else {
                    if( $userPrivacyArray_check['privacy_sharescomments'] == 0 ) {
                        $social_can_share=false;
                        $social_can_comment=false;
                    }
                    if( $userPrivacyArray_check['privacy_likesrates'] == 0 ) {
                        $social_can_like=false;
                        $social_can_rate=false;
                    }
                } 
            }                                   
        }

        if( isset($videoRecord['enable_share_comment']) && $videoRecord['enable_share_comment'] == 0 ){
                $enable_shares_comments['data_status'] = 0;
                $enable_shares_comments['class'] = ' inactive';
        // Case photo/video.
        }else if( (isset($videoRecord['can_share']) || isset($videoRecord['can_comment'])) && ($videoRecord['can_share'] == 0 || $videoRecord['can_comment'] == 0)){
                $enable_shares_comments['data_status'] = 0;
                $enable_shares_comments['class'] = ' inactive';
        }
        if( $enable_shares_comments['data_status'] == 0 &&  $entity_owner != $userId ){
                $social_can_share = false;
                $social_can_comment = false;
        }

        $action = null;
        $action_array = array();
        switch($vfeeditem['action_type']){
                case SOCIAL_ACTION_UPLOAD:
                        $staticaction = "[ActionOwner] uploaded a";
                        if($entity_type==SOCIAL_ENTITY_MEDIA){
                            $target_name = stripslashes($videoRecord['title']);
                            if($action_row_count>1){                                
                                //$staticaction = "[ActionOwner] uploaded $action_row_count";//todo
                                $staticaction = '[ActionOwner] uploaded %count$s';
                                array_unshift($action_array, $action_row_count);    
                                $item_index = 0;
                                foreach ($action_row_other as $item){
                                    if($item['media_row']['id'] != $vfeeditem['entity_id'] ){
                                        if ($item['media_row']['image_video'] == "v") {
                                            $thumb_src_link = substr(getVideoThumbnail($item['media_row']['id'], '../../'.$path . $item['media_row']['fullpath'], 0), strlen($path));
                                            $thumb_src = explode('../../', $thumb_src_link);
                                            $thumb_src =$thumb_src[1];
                                            $type = 'video';
                                        } else {
                                            $thumb_src = $item['media_row']['fullpath'] . $item['media_row']['name'];
                                            $type = 'image';
                                        }
                                        $other_img[] = array(
                                            'id'=> $item['media_row']['id'], 
                                            //'path' => photoReturnThumbSrc($item['media_row']),
                                            'path' => $thumb_src,
                                            //'type' => $item['media_row']['image_video'] == "v" ? 'video' : 'photo'
                                            'type'=>$type,
                                        );
                                    }
                                }
                            }
                        }

                        if($action_row_count>1){  
                            $action = "$staticaction new ".$video_photo."s [TargetName]";
                        }
                        else{
                            $action = "$staticaction new $video_photo [TargetName]";
                        }
                        $title = $videoRecord['title'];
                        $description = $videoRecord['description'];
                        $description = htmlEntityDecode($description);
                        //$target_name = $title;
                        if($entity_type != SOCIAL_ENTITY_HOTEL_REVIEWS && $entity_type != SOCIAL_ENTITY_RESTAURANT_REVIEWS && $entity_type != SOCIAL_ENTITY_LANDMARK_REVIEWS && $entity_type != SOCIAL_ENTITY_AIRPORT_REVIEWS){
                            $target_id = $videoRecord['id'];
                        }
                        //$fullLink = photoReturnSrcSmall($videoRecord);
                         if ($videoRecord['image_video'] == "v") {
                            $thumb_src_link = substr(getVideoThumbnail($videoRecord['id'], '../../'.$path . $videoRecord['fullpath'], 0), strlen($path));
                            $thumb_src = explode('../../', $thumb_src_link);
                            $thumb_src =$thumb_src[1];
                            if(!$thumb_src) { $thumb_src = ''; }
                            $type = 'video';
                        } else {
                            $thumb_src = $videoRecord['fullpath'] . $videoRecord['name'];
                            $type = 'image';
                        }
                        $fullLink=checkfile_exist($thumb_src);
                        if($entity_type==SOCIAL_ENTITY_MEDIA && $album){
                            if($action_row_count>1){
                                $action = "$staticaction new ".$video_photo."s to the album [TargetName]";
                            }
                            else{
                                $action = "$staticaction new $video_photo to the album [TargetName]";
                            }
                            $target_id = $album['id'];
                            $target_name = $album['catalog_name'];
                        }
                        else if($entity_type == SOCIAL_ENTITY_ALBUM && $album){
                            $action = "[ActionOwner] added a new album [TargetName]";
                            $album_media_options = array(
                                'limit' => 3,
                                'catalog_id' => $album['id'],
                                'orderby' => 'rand',
                                'type' => 'a',
                                'order' => 'd'
                            );
                            $album_items = mediaSearch($album_media_options);
                        }
                        switch($entity_type){
                                case SOCIAL_ENTITY_USER_EVENTS:
                                        $action = "[ActionOwner] created a new event [TargetName]";	
                                        $target_name = $videoRecord['name'];
                                        $description = htmlEntityDecode($videoRecord['description']);
                                        $title = $videoRecord['name']; 
                                        break;
                                case SOCIAL_ENTITY_NEWS:
                                        $action = "[ActionOwner] added a news";	/*added a*/	
                                        $feed_user_id = $videoRecord['channelid'];
                                        break;
                                case SOCIAL_ENTITY_BROCHURE:
                                        $action = "[ActionOwner] added a new brochure [TargetName]";
                                        if ($videoRecord['photo'] == '') {
                                            $fullLink = 'media/images/channel/brochure-cover-phot.jpg';
                                        }else{
                                            $fullLink = 'media/channel/' . $videoRecord['channelid'] . '/brochure/thumb/' . $videoRecord['photo'];
                                        }
                                        $target_name = $videoRecord['name'];
                                        $feed_user_id = $videoRecord['channelid'];
                                        break;
                                    
                                case SOCIAL_ENTITY_EVENTS:
                                        $action = '[ActionOwner] created a new event [TargetName]';	
                                        $target_name = $videoRecord['name'];
                                        break;
                             /* case SOCIAL_ENTITY_JOURNAL:
                                        $action = "[ActionOwner] added a new journal [TargetName]";					
                                        break;
                             */
                                case SOCIAL_ENTITY_HOTEL_REVIEWS:
                                case SOCIAL_ENTITY_RESTAURANT_REVIEWS:
                                case SOCIAL_ENTITY_LANDMARK_REVIEWS:
                                case SOCIAL_ENTITY_AIRPORT_REVIEWS:
                                    $action= "[ActionOwner] added the following review";
                                break;
                                case SOCIAL_ENTITY_POST:
                                    //$fullLink = getPostThumbPath($vfeeditem['media_row']);
                                    if ($videoRecord != "") { 
                                        if($videoRecord['media_file'] != ''){
                                            if ($videoRecord['is_video'] == 0) {
                                               // $repath = relativevideoReturnPostPath($videoRecord);
                                                if (intval($videoRecord['channel_id']) == 0 && intval($videoRecord['from_id']) != 0) {
                                                    global $CONFIG;
                                                    $videoPath = $CONFIG['video']['uploadPath'];
                                                    $rpath = $videoRecord['relativepath'];
                                                    $repath= $videoPath . 'posts/' . $rpath;
                                                } else {
                                                    $repath='media/channel/' . $vinfo['channel_id'] . '/posts/';
                                                }
                                                $repath .=  $videoRecord['media_file'];

                                            }else{  
                                                $cod1 = explode('.',  $videoRecord['media_file']);
                                                $cod2 = explode('_', $cod1[0]);
                                                $videoCode = $cod2[1];
                                                $repath = relativevideoGetPostPath($videoRecord);
                                                $videoCode = 'small_postThumb' . $videoCode;
                                                $picthumb_img = getVideoThumbnail_Posts($videoCode, '../../'.$repath, 0);
                                                $picthumb_img = explode('../../', $picthumb_img);
                                                $repath =$picthumb_img[1];
                                            }
                                        }else{
                                            $repath='';
                                        }
                                    }
                                    $fullLink=checkfile_exist($repath);  
                                    $action= "[ActionOwner] posted the following on ".$gender." TT page:";                                                
                                    if( intval($videoRecord['channel_id']) !=0 ){
                                        $action = "[ActionOwner] posted the following:";
                                    }
                                    $from_id = intval($videoRecord['user_id']);
                                    $realvalue=0;
                                    if($from_id != $action_profile['id'] ){	
                                        $action='[ActionOwner] posted the following on your TT page:';
                                        $userInfo_action = getUserInfo($action_profile['id']);
                                        $userInfo_action_who = getUserInfo($from_id);
                                        $action_profile = $userInfo_action;							
                                        $uslnk= userProfileLink($userInfo_action_who);
                                        if( $userInfo_action_who['id'] != $userId ){
                                            $action_array=array();
                                            $realvalue=1;
                                            $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action_who).'</span></a>';
                                            //$owner_name = returnUserDisplayName($userInfo_action);
                                            $owner_name = returnUserDisplayName($userInfo_action_who);
                                            $owner_id = $from_id;
                                            $action="[ActionOwner] posted the following on [Owner]'s TT page:";								
                                        }
                                    }
                                    break;
                                case SOCIAL_ENTITY_FLASH:
                                    $action= "[ActionOwner] echoed the following";                             
                                    $echo_text = $vfeeditem['media_row']['flash_text'];
                                    $fullLink=($videoRecord['vpath']) ? $videoRecord['vpath'] . $videoRecord['pic_file'] : '';
                                break;
                                case SOCIAL_ENTITY_ALBUM:
                                        $title = $videoRecord['title'];
                                        $target_name = $title;
                                        /*to show full link of album*/
                                        //$fullLink = photoReturnSrcSmall($videoRecord);
                                         if ($videoRecord['image_video'] == "v") {
                                            $thumb_src_link = substr(getVideoThumbnail($videoRecord['id'], '../../'.$path . $videoRecord['fullpath'], 0), strlen($path));
                                            $thumb_src = explode('../../', $thumb_src_link);
                                            $thumb_src =$thumb_src[1];
                                            if(!$thumb_src) { $thumb_src = ''; }
                                            $type = 'video';
                                        } else {
                                            $thumb_src = $videoRecord['fullpath'] . $videoRecord['name'];
                                            $type = 'image';
                                        }
                                        $fullLink=checkfile_exist($thumb_src);
                                        $target_id = $entity_id; /*here entity_is album_id*/
                                        $album_info = userCatalogGet($entity_id);
                                        $target_name = $album_info['catalog_name'];
                                        /*to show other images of album mukesh*/
                                         $srch_options = array(
                                            'limit' => 3,
                                            'catalog_id' => $target_id,
                                            'orderby' => 'rand',
                                            'type' => 'a',
                                            'order' => 'd'
                                        );
                                        $photos1 = mediaSearch($srch_options);
                                        $other_img = array();
                                        foreach($photos1 as $photo){
//                                            $other_img[]=array(
//                                                'id'=>$photo['id'],
//                                                'path'=>photoReturnThumbSrc($photo),
//                                                'type'=>$photo['image_video'] == "v" ? 'video' : 'image'
//                                            );
                                            if ($photo['image_video'] == "v") {
                                                $thumb_src_link = substr(getVideoThumbnail($photo['id'], '../../'.$path . $photo['fullpath'], 0), strlen($path));
                                                $thumb_src = explode('../../', $thumb_src_link);
                                                $thumb_src =$thumb_src[1];
                                                $type = 'video';
                                            } else {
                                                $thumb_src = $photo['fullpath'] . $photo['name'];
                                                $type = 'image';
                                            }
                                            $other_img[] = array(
                                                'id'=> $photo['id'], 
                                                'path' => $thumb_src,
                                                'type'=>$type,
                                            ); 
                                        }
                                        /*end*/
                                        if($action_row_count>=1){
                                            $action = "$staticaction new album [TargetName]"; //$staticaction the album [TargetName]
                                        }else{
                                            $action = "$staticaction $gender own album [TargetName]";
                                        }  
                                        if( intval($videoRecord['channelid']) !=0 ){
                                                //$action = "$staticaction their album [TargetName]";
                                                $action = "$staticaction new album [TargetName]";
                                        }
                                        $from_id = intval($videoRecord['userid']);
                                        if($from_id != $action_profile['id'] ){
                                                if( intval($videoRecord['channelid']) !=0 ){
                                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                                        $action_array[] ='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                        $action="$staticaction [Owner]'s album [TargetName]";	
                                                        $owner_name=$channel_array['channel_name'];
                                                        $owner_id=$videoRecord['channelid'];
                                                }else{
                                                        $userInfo_action = getUserInfo($from_id);	
                                                        $uslnk= userProfileLink($userInfo_action);
                                                        $action="$staticaction your album [TargetName]";
                                                        if( $userInfo_action['id'] != $userId ){
                                                                $action_array=array();
                                                                $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                $action="$staticaction [Owner]'s album [TargetName]";
                                                                $owner_name=returnUserDisplayName($userInfo_action);
                                                                $owner_id=$from_id;
//                                                                        $action="$staticaction %s 's ";
                                                        }

                                                }
                                        }
                                       
                                        break;
                        }
                        break;
                case SOCIAL_ACTION_UPDATE://todo validate data
                        $action = "[ActionOwner] updated $gender";
                        switch($entity_type){
                                case SOCIAL_ENTITY_CHANNEL_SLOGAN:
                                        $action = "[ActionOwner] updated their slogan";	
                                        $feed_user_id = $videoRecord['channelid'];
                                        break;
                                case SOCIAL_ENTITY_CHANNEL_INFO:
                                        $action = "[ActionOwner] updated their info";					
                                        break;
                                case SOCIAL_ENTITY_CHANNEL_COVER:
                                        $action = "[ActionOwner] updated their cover photo";
                                        break;
                                case SOCIAL_ENTITY_CHANNEL_PROFILE:
                                        $action = "[ActionOwner] updated their profile image";
                                        $entity_id = $videoRecord['id'];
//                                        $channelDetailArray=GetChannelDetailInfo($entity_id);
//                                        $channel_array=channelGetInfo($channelDetailArray['channelid']);
                                        $channel_array = channelGetInfo($entity_id);
                                        //$fullLink = 'media/channel/' . $channel_array['id'] . '/thumb/' . $channel_array['header'];
                                        $fullLink = 'media/channel/' . $channel_id . '/thumb/' . $videoRecord['detail_text'];
                                        break;
                                case SOCIAL_ENTITY_USER_PROFILE:
                                        $action = "[ActionOwner] updated $gender profile image";
                                        //$fullLink = 'media/tubers/' . $videoRecord['detail_text'];
                                        /*to show bigimage in fulllink*/
                                        $img = explode('Profile_', $videoRecord['detail_text']);
                                        $fullLink = 'media/tubers/' . $img[1];
                                        $userInfo_feed = getUserInfo($vfeeditem['user_id']);
                                        $user_stats = userGetStatistics($vfeeditem['user_id'],1);
                                //echo "<pre>";print_r($userInfo_feed);
                                        $city = $userInfo_feed['city'];
                                        $country = $userInfo_feed['YourCountry'];
                                        $locationval_text = '' . $city . '';
                                        $locationval = countryGetName($country);
                                        if ($locationval != '') {
                                            $locationval_text .=', ' . $locationval . '';
                                        }

                                        $ts_date = strtotime($userInfo_feed['RegisteredDate']);
                                        $print_date = date('M. d, Y', $ts_date);
                                        $strpop = t(_('tuber since %when'), array('%when' => $print_date));
                                        $address=$locationval_text;
                                        $register_date=$strpop;
                                        $nb_followers=$user_stats['nFollowers'];
                                        $nb_friends=$user_stats['nFriends'];
                                        break;
                                case SOCIAL_ENTITY_EVENTS_LOCATION:
                                    if($owner_type == 'channel'){
                                        $action = "[ActionOwner] changed their event location [TargetName]";
                                    }else{
                                        $action = "[ActionOwner] changed $gender event location [TargetName]";
                                    }
                                        $target_name = htmlEntityDecode($vfeeditem['media_row']['name']);
                                        $target_id = $videoRecord['id'];
                                        break;
                                case SOCIAL_ENTITY_EVENTS_DATE:
                                    if($owner_type == 'channel'){
                                        $action = "[ActionOwner] changed their event date [TargetName]";
                                    }else{
                                        $action = "[ActionOwner] changed $gender event date [TargetName]";
                                    }
                                        $target_name = $videoRecord['name'];
                                        $target_id = $videoRecord['id'];
                                        break;    
                                case SOCIAL_ENTITY_EVENTS_TIME:
                                    if($owner_type == 'channel'){
                                        $action = "[ActionOwner] changed their event time [TargetName]";
                                    }else{
                                        $action = "[ActionOwner] changed $gender event time [TargetName]";
                                    }
                                        $target_name = $videoRecord['name'];
                                        $target_id = $videoRecord['id'];
                                        break;
                        }
                        break;
                case SOCIAL_ACTION_EVENT_CANCEL:
                        if( intval($videoRecord['channelid']) !=0 ){
                                $media_uri = ReturnLink('channel-events-detailed/' . $videoRecord['id']);
                                $action_array[]='<a href="'.$media_uri.'" target="_blank"><span class="yellow_font">' . htmlEntityDecode($videoRecord['name']) . '</span></a>';
                                $action = '[ActionOwner] cancelled their event [TargetName]';	
                                $target_name = $videoRecord['name'];
                                $target_id = $videoRecord['id'];
                        }else{
                                $action = "[ActionOwner] cancelled $gender own event [TargetName]";
                                $target_name = $videoRecord['name'];
                                $target_id = $videoRecord['id'];
                        }
                        break;
                case SOCIAL_ACTION_CONNECT:
                        $staticaction = "[ActionOwner] is now connected";                        
                        if($action_row_count>1){
                            $staticaction = "[ActionOwner] and [Others] are now connected";
                        }else if($action_row_count==1){                            
                            $staticaction = "[ActionOwner] and [Other] are now connected";
                        }
                        $channel_url = ReturnLink('channel/'.$videoRecord['channel_url']);
                        $action = "$staticaction to [TargetName]'s channel";
                        $target_name = $videoRecord['channel_name'];
                        $target_id = $videoRecord['id'];
                        if($entity_type == SOCIAL_ENTITY_CHANNEL){
                            $title = $videoRecord['channel_name'];
                            $description=htmlEntityDecode($videoRecord['small_description']);
                        }
                        $action_array[]= '<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($videoRecord['channel_name']).'</span></a>';
                        if($action_row_count>1){
                            $stoth ='';
                            $i=0;
                            foreach($action_row_other as $action_row_otherIT){
                                $action_profiletb = getUserInfo($action_row_otherIT['user_id']);
                                $uslnkoth= userProfileLink($action_profiletb);
                                $uslnkothname= returnUserDisplayName($action_profiletb);

                                $stoth .= '<a class="tt_otherlink_a" href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>';
                                $i++;
                                if($i>=10 && $action_row_count>10){
                                    $stoth .= '<a class="tt_otherlink_a"><strong>'. vsprintf(ngettext("and %s other...", "and %s others...", ($action_row_count - $i) ) , array( $action_row_count - $i ) ) .'</strong></a>';
                                    break;
                                }
                            }
                            array_unshift($action_array, '</strong><div class="tt_otherlink_over"><div class="tt_otherlink_overin"><div class="tt_otherlink_over_arrow"></div>'.$stoth.'</div></div></span>');
                            array_unshift($action_array, '<span class="tt_otherlink_span"><strong>'.$action_row_count);
                            //array_unshift($action_array, $action_row_count);
                        }else if($action_row_count==1){
                            $action_profiletb = getUserInfo($action_row_other['user_id']);
                            $uslnkoth= userProfileLink($action_profiletb);
                            $uslnkothname= returnUserDisplayName($action_profiletb);
                            $other = $uslnkothname;
                            $otherId = $action_row_other['user_id'];
                            array_unshift($action_array, '<a href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>');
                        }
                        break;
                case SOCIAL_ACTION_FOLLOW:
                        $staticaction = "[ActionOwner] is now following";                        
                        if($action_row_count>1){
                            $staticaction = "[ActionOwner] and [Others] are now following";
                        }else if($action_row_count==1){                            
                            $staticaction = "[ActionOwner] and [Other] are now following";
                        }
                        $action_profile = getUserInfo($action_profile['id']);                                
                        $userInfo_action = getUserInfo($entity_id);
                        $uslnk= userProfileLink($userInfo_action);
                        $prfpic=ReturnLink('media/tubers/' . $userInfo_action['profile_Pic']);
                        $action_array=array();
                        $action_array[]='<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'<div class="tt_link_over"><div class="tt_link_over_bk"></div><div class="tt_link_over_arrow"></div><img class="tt_link_over_pic" src="'.$prfpic.'"/></div></span></a>';
                        $action = "$staticaction [Owner]";
                        $owner_name = returnUserDisplayName($userInfo_action);
                        $owner_id=$entity_id;
                        if($action_row_count>1){
                            $stoth ='';
                            $i=0;
                            foreach($action_row_other as $action_row_otherIT){
                                $action_profiletb = getUserInfo($action_row_otherIT['user_id']);
                                $uslnkoth= userProfileLink($action_profiletb);
                                $uslnkothname= returnUserDisplayName($action_profiletb);

                                $stoth .= '<a class="tt_otherlink_a" href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>';
                                $i++;
                                if($i>=10 && $action_row_count>10){
                                    $stoth .= '<a class="tt_otherlink_a"><strong>'. vsprintf(ngettext("and %s other...", "and %s others...", ($action_row_count - $i) ) , array( $action_row_count - $i ) ) .'</strong></a>';
                                    break;
                                }
                            }
                            array_unshift($action_array, '</strong><div class="tt_otherlink_over"><div class="tt_otherlink_overin"><div class="tt_otherlink_over_arrow"></div>'.$stoth.'</div></div></span>');
                            array_unshift($action_array, '<span class="tt_otherlink_span"><strong>'.$action_row_count);
                        }else if($action_row_count==1){
                            $action_profiletb = getUserInfo($action_row_other['user_id']);
                            $uslnkoth= userProfileLink($action_profiletb);
                            $uslnkothname= returnUserDisplayName($action_profiletb);
                            $other = $uslnkothname;
                            $otherId = $action_row_other['user_id'];
                            array_unshift($action_array, '<a href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>');
                        }
                        break;
                case SOCIAL_ACTION_FRIEND:
                        $staticaction = "[ActionOwner] is now friends";                        
                        if($action_row_count>1){
                            $staticaction = "[ActionOwner] and [Others] are now friends";
                        }else if($action_row_count==1){                            
                            $staticaction = "[ActionOwner] and [Other] are now friends";
                        }
                        $action_profile = getUserInfo($action_profile['id']);
                        $userInfo_action = getUserInfo($entity_id);
                        $uslnk= userProfileLink($userInfo_action);
                        $prfpic=ReturnLink('media/tubers/' . $userInfo_action['profile_Pic']);	
                        $action_array=array();
                        $action_array[]='<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'<div class="tt_link_over"><div class="tt_link_over_bk"></div><div class="tt_link_over_arrow"></div><img class="tt_link_over_pic" src="'.$prfpic.'"/></div></span></a>';
                        $action = "$staticaction with [Owner]";
                        $owner_info = getUserInfo($entity_id);
                        $owner_name = returnUserDisplayName($owner_info);
                        $owner_id = $entity_id;
                        if($action_row_count>1){
                            $stoth ='';
                            $i=0;
                            foreach($action_row_other as $action_row_otherIT){
                                $action_profiletb = getUserInfo($action_row_otherIT['user_id']);
                                $uslnkoth= userProfileLink($action_profiletb);
                                $uslnkothname= returnUserDisplayName($action_profiletb);

                                $stoth .= '<a class="tt_otherlink_a" href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>';
                                $i++;
                                if($i>=10 && $action_row_count>10){
                                    $stoth .= '<a class="tt_otherlink_a"><strong>'. vsprintf(ngettext("and %s other...", "and %s others...", ($action_row_count - $i) ) , array( $action_row_count - $i ) ) .'</strong></a>';
                                    break;
                                }
                            }
                            array_unshift($action_array, '</strong><div class="tt_otherlink_over"><div class="tt_otherlink_overin"><div class="tt_otherlink_over_arrow"></div>'.$stoth.'</div></div></span>');
                            array_unshift($action_array, '<span class="tt_otherlink_span"><strong>'.$action_row_count);
                        }else if($action_row_count==1){
                            $otherId = $action_row_other['user_id'];
                            $action_profiletb = getUserInfo($action_row_other['user_id']);
                            $uslnkothname= returnUserDisplayName($action_profiletb);
                            $other = $uslnkothname;
                            $uslnkoth= userProfileLink($action_profiletb);
                            array_unshift($action_array, '<a href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>');
                        }
                        break;
                case SOCIAL_ACTION_SPONSOR:
                    $staticaction = "[ActionOwner] sponsored";                        
                    if($action_row_count>1){
                        $staticaction = "[ActionOwner] and [Others] sponsored";
                    }else if($action_row_count==1){                            
                        $staticaction = "[ActionOwner] and [Other] sponsored";
                    }
                    switch($entity_type){
                        case SOCIAL_ENTITY_CHANNEL:
                            $channel_url = ReturnLink('channel/'.$videoRecord['channel_url']);
                            $action_array=array();
                            $action_array[]='<a h ref="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($videoRecord['channel_name']).'</span></a>';
                            $action = "$staticaction the channel [TargetName]";
                            $target_name = $videoRecord['channel_name'];
                            $target_id = $videoRecord['id'];
                            $description = htmlEntityDecode($videoRecord['small_description']);
                            $title = $target_name;
                            $overhead_text = htmlEntityDecode($actionRecord['msg']);
                            break;
                        case SOCIAL_ENTITY_EVENTS:
//                           $action = "$staticaction an event %s";
                            //$event_name = $videoRecord['name'];
                            $action = "$staticaction the event [TargetName]";
                            $target_name = $videoRecord['name'];
                            $target_id = $videoRecord['id'];
                            //$feed_user_id = $actionRecord['from_user'];
                            break;
                    }                    
                    if($action_row_count>1){
                            $stoth ='';
                            $i=0;
                        foreach($action_row_other as $action_row_otherIT){
                            $channel_arraygroup=channelGetInfo($action_row_otherIT['action_row']['from_user']);
                            $uslnkoth = ReturnLink('channel/'.$channel_arraygroup['channel_url']);
                            $uslnkothname= htmlEntityDecode($channel_arraygroup['channel_name']);

                            $stoth .= '<a class="tt_otherlink_a" href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>';
                            $i++;
                            if($i>=10 && $action_row_count>10){
                                $stoth .= '<a class="tt_otherlink_a"><strong>'. vsprintf(ngettext("and %s other...", "and %s others...", ($action_row_count - $i) ) , array( $action_row_count - $i ) ) .'</strong></a>';
                                break;
                            }
                        }
                        array_unshift($action_array, '</strong><div class="tt_otherlink_over"><div class="tt_otherlink_overin"><div class="tt_otherlink_over_arrow"></div>'.$stoth.'</div></div></span>');
                        array_unshift($action_array, '<span class="tt_otherlink_span"><strong>'.$action_row_count);
                    }else if($action_row_count==1){
                        $channel_arraygroup=channelGetInfo($action_row_other['action_row']['from_user']);
                        $uslnkoth = ReturnLink('channel/'.$channel_arraygroup['channel_url']);
                        $uslnkothname= htmlEntityDecode($channel_arraygroup['channel_name']);       
                        $other = $uslnkothname;
                        $otherId = $action_row_other['user_id'];
                        array_unshift($action_array, '<a href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>');
                    }  
                break;
                case SOCIAL_ACTION_RELATION_PARENT:
                case SOCIAL_ACTION_RELATION_SUB:
                    $staticaction = "[ActionOwner] has accepted";                        
                    if($action_row_count>1){
                        $staticaction = "[ActionOwner] and [Others] have accepted";
                    }else if($action_row_count==1){                            
                        $staticaction = "[ActionOwner] and [Others] have accepted";
                    }
                    $channel_array = channelGetInfo($feed_user_id); /*sub channel info*/  
                    $channel_url_r = ReturnLink('channel/'.$videoRecord['channel_url']);
                    $channel_name_r = htmlEntityDecode($videoRecord['channel_name']); /*parent channel name*/
                  
                    if(strlen($channel_name_r) > 32){
                       $channel_name_r = substr($channel_name_r,0,32).' ...';
                    }
                    $action = "$staticaction [Owner] as a sub channel";
                    $action_array[]='<a href="' . $channel_url_r . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.$channel_name_r.'</span></a>';
                    if($actionRecord['relation_type']==CHANNEL_RELATION_TYPE_SUB){
                        $staticaction = "[ActionOwner] is now";      /*[ActionOwner] in place of %s */                 
                        if($action_row_count>1){
                            $staticaction = "[ActionOwner] and [Others] are now";
                        }else if($action_row_count==1){//Need update                            
                            $staticaction = "[ActionOwner] and [Other] are now";
                        }
                        $action_array=array();
                        $action_array[]='<a href="' . $channel_url_r . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.$channel_name_r.'</span></a>';
                        $action = "$staticaction a sub channel to [Owner]"; /*[Owner] in place of %s*/
                       // $loop_ret_row['test'] = 'test';
                        $owner_id = $entity_id;  
                        $owner_name=$channel_name_r;
                        $username=$channel_array['channel_name'];
                    }

                    if($action_row_count>1){
                            $stoth ='';
                            $i=0;
                        foreach($action_row_other as $action_row_otherIT){
                            $channel_arraygroup=$action_row_otherIT['channel_row'];
                            $uslnkoth = ReturnLink('channel/'.$channel_arraygroup['channel_url']);
                            $uslnkothname= htmlEntityDecode($channel_arraygroup['channel_name']);

                            $stoth .= '<a class="tt_otherlink_a" href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>';
                            $i++;
                            if($i>=10 && $action_row_count>10){
                                $stoth .= '<a class="tt_otherlink_a"><strong>'. vsprintf(ngettext("and %s other...", "and %s others...", ($action_row_count - $i) ) , array( $action_row_count - $i ) ) .'</strong></a>';
                                break;
                            }
                        }
                        array_unshift($action_array, '</strong><div class="tt_otherlink_over"><div class="tt_otherlink_overin"><div class="tt_otherlink_over_arrow"></div>'.$stoth.'</div></div></span>');
                        array_unshift($action_array, '<span class="tt_otherlink_span"><strong>'.$action_row_count);
                    }else if($action_row_count==1){
                        $channel_arraygroup=$action_row_other['channel_row'];
                        $uslnkoth = ReturnLink('channel/'.$channel_arraygroup['channel_url']);
                        $uslnkothname= htmlEntityDecode($channel_arraygroup['channel_name']);                                                      
                        array_unshift($action_array, '<a href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>');
                    }
                break;
                case SOCIAL_ACTION_EVENT_JOIN:
                        $target_name = $videoRecord['name'];
                        $target_id = $videoRecord['id'];
                        //$staticaction = "[ActionOwner] joined";
                        $staticaction = "[ActionOwner] has joined";
                        if($action_row_count>1){
                            $staticaction = "[ActionOwner] and [Others] joined";
                        }else if($action_row_count==1){                            
                            $staticaction = "[ActionOwner] and [Other] joined";
                        }
                        if( intval($videoRecord['channelid']) !=0 ){
                                $channel_array=channelGetInfo($videoRecord['channelid']);
                                $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                $action= "$staticaction [Owner]'s event [TargetName]";	
                                $owner_name = $channel_array['channel_name'];
                        }else{
                                $action = "$staticaction [Owner]'s event [TargetName]";
                                $from_id = intval($videoRecord['user_id']);
                                $userInfo_action = getUserInfo($from_id);
                                $owner_name = returnUserDisplayName($userInfo_action);
                                $owner_id=$from_id;
                        }
                        if($action_row_count>1){
                            $stoth ='';
                            $i=0;
                            foreach($action_row_other as $action_row_otherIT){
                                $action_profiletb = getUserInfo($action_row_otherIT['user_id']);
                                $uslnkoth= userProfileLink($action_profiletb);
                                $uslnkothname= returnUserDisplayName($action_profiletb);

                                $stoth .= '<a class="tt_otherlink_a" href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>';
                                $i++;
                                if($i>=10 && $action_row_count>10){
                                    $stoth .= '<a class="tt_otherlink_a"><strong>'. vsprintf(ngettext("and %s other...", "and %s others...", ($action_row_count - $i) ) , array( $action_row_count - $i ) ) .'</strong></a>';
                                    break;
                                }
                            }
                            array_unshift($action_array, '</strong><div class="tt_otherlink_over"><div class="tt_otherlink_overin"><div class="tt_otherlink_over_arrow"></div>'.$stoth.'</div></div></span>');
                            array_unshift($action_array, '<span class="tt_otherlink_span"><strong>'.$action_row_count);
                        }else if($action_row_count==1){                             
                            $action_profiletb = getUserInfo($action_row_other['user_id']);
                            $uslnkoth= userProfileLink($action_profiletb);
                            $uslnkothname= returnUserDisplayName($action_profiletb);
                            $other = $uslnkothname;
                            $otherId = $action_row_other['user_id'];
                            array_unshift($action_array, '<a href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>');
                        }
                        break;
                case SOCIAL_ACTION_LIKE:
                        $original_entity_type = $entity_type;
                        $from_id = intval($videoRecord['userid']);
                        //in case the like was on a comment for instance
                        if( isset($vfeeditem['original_entity_type']) ){
                                $original_entity_type = $vfeeditem['original_entity_type'];
                                $original_entity_id = $vfeeditem['original_entity_id'];
                        }
                        $staticaction = "[ActionOwner] liked";            
                        if($action_row_count>1){
                            $staticaction = "[ActionOwner] and [Others] liked";
                        }else if($action_row_count==1){                            
                            $staticaction = "[ActionOwner] and [Other] liked";
                        }                            //echo "<pre>";print_r($vfeeditem);die;
                        switch($entity_type){ 
                                case SOCIAL_ENTITY_MEDIA: //echo $vfeeditem['entity_type'].'_'.$vfeeditem['action_row']['entity_type'];

                                    if($action_row['entity_type'] == SOCIAL_ENTITY_COMMENT){   
                                        $owner_id=$vfeeditem['original_media_row']['user_id'];
                                        $userInfo_action_who = getUserInfo($owner_id);
                                        $owner_name = returnUserDisplayName($userInfo_action_who);
                                        $action = "$staticaction [Owner]'s comment on a $video_photo [TargetName]";
//                                        $title = stripslashes($videoRecord['title']);
//                                        $target_name = $title;
//                                        $target_id = $videoRecord['id'];
                                        $overhead_text = htmlEntityDecode($vfeeditem['original_media_row']['comment_text']);
                                    }else{      
                                            if($action_row_count>=1){
                                                $action = "$staticaction the $video_photo [TargetName]";
                                            }else{
                                                $action = "$staticaction $gender own $video_photo [TargetName]";
                                            }  
                                            if( intval($videoRecord['channelid']) !=0 ){
                                                    $action = "$staticaction their $video_photo [TargetName]";
                                            }
                                            
                                            if($from_id != $action_profile['id'] ){
                                                    if( intval($videoRecord['channelid']) !=0 ){
                                                            $channel_array=channelGetInfo($videoRecord['channelid']);
                                                            $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                                            $action_array=array();
    //                                                      $action="$staticaction %s 's " ; 
                                                            $action="$staticaction [Owner]'s $video_photo [TargetName]";
                                                            $owner_name = $channel_array['channel_name'];
                                                            $owner_id=$videoRecord['channelid'];
                                                            $action_array[] = '<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                    }else{
                                                            $userInfo_action = getUserInfo($from_id);	
                                                            $uslnk= userProfileLink($userInfo_action);
                                                            $action="$staticaction your $video_photo [TargetName]";
                                                            if( $userInfo_action['id'] != $userId ){
                                                                    $action_array = array();
                                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                    $owner_name = returnUserDisplayName($userInfo_action);
                                                                    $owner_id=$from_id;
                                                                    $action="$staticaction [Owner]'s $video_photo [TargetName]"; 
                                                            }
                                                    }
                                            }
                                        }
                                            $title = stripslashes($videoRecord['title']);  /*Use stripslashes() because backslashes are coming in target name by mukesh 7 sept 2015*/    
                                            $description = $videoRecord['description'];
                                            $description = htmlEntityDecode($description);
                                            $from_id = intval($videoRecord['userid']);
                                            $target_name = $title;
                                            $target_id = $videoRecord['id'];
                                            //$fullLink = photoReturnSrcSmall($videoRecord);
                                            if ($videoRecord['image_video'] == "v") {
                                                $thumb_src_link = substr(getVideoThumbnail($videoRecord['id'], '../../'.$path . $videoRecord['fullpath'], 0), strlen($path));
                                                $thumb_src = explode('../../', $thumb_src_link);
                                                $thumb_src =$thumb_src[1];
                                                if(!$thumb_src) { $thumb_src = ''; }
                                                $type = 'video';
                                            } else {
                                                $thumb_src = $videoRecord['fullpath'] . $videoRecord['name'];
                                                $type = 'image';
                                            }
                                            $fullLink=checkfile_exist($thumb_src);
                                        break;
//                                case SOCIAL_ENTITY_WEBCAM:
//                                        $action = "$staticaction";
//                                        break;
                                case SOCIAL_ENTITY_ALBUM: 
                                        $title = $videoRecord['title'];
                                        $target_name = $title;
                                        $description = htmlEntityDecode($videoRecord['description']);
                                        if($action_row['entity_type'] == SOCIAL_ENTITY_COMMENT){
                                            /*To show fulllink*/
                                            $media_row = userCatalogDefaultMediaGet($vfeeditem['original_media_row']['entity_id']); /*$videoRecord['entity_id']-> not coming inside this for this condition. $action_row['entity_id']->coming wrong entity_id*/
                                            //echo "<pre>";print_r($media_row);die;
    //                                        $channel_id_post = '';
    //                                        if (intval($media_row['channelid']) != 0) {
    //                                            $channel_id_post = intval($media_row['channelid']);
    //                                        }
                                            //$impathss = ($media_row['image_video'] == 'i') ? photoReturnSrcSmall($media_row) : videoReturnSrcSmall($media_row);
                                            $videoRecord['id'] = $media_row['id']; /*To overwrite the id of $videoRecord in comment condition*/
                                            if ($media_row['image_video'] == "v") {
                                                $thumb_src_link = substr(getVideoThumbnail($media_row['id'], '../../'.$path . $media_row['fullpath'], 0), strlen($path));
                                                $thumb_src = explode('../../', $thumb_src_link);
                                                $thumb_src =$thumb_src[1];
                                                if(!$thumb_src) { $thumb_src = ''; }
                                                $type = 'video';
                                            } else {
                                                $thumb_src = $media_row['fullpath'] . $media_row['name'];
                                                $type = 'image';
                                            }
                                            $fullLink=checkfile_exist($thumb_src);
                                            /*End To show fulllink*/
                                            $owner_id=$vfeeditem['original_media_row']['user_id'];
                                            $userInfo_action_who = getUserInfo($owner_id);
                                            $owner_name = returnUserDisplayName($userInfo_action_who);
                                            $action = "$staticaction [Owner]'s comment on an album [TargetName]";
                                            $overhead_text = htmlEntityDecode($vfeeditem['original_media_row']['comment_text']);
                                            $video_photo = ( strstr($media_row['type'],'image') == null ) ? 'video' : 'photo';
                                        }else{
                                        
                                            /*to show full link of album*/
                                            //$fullLink = photoReturnSrcSmall($videoRecord);
                                            if ($videoRecord['image_video'] == "v") {
                                                $thumb_src_link = substr(getVideoThumbnail($videoRecord['id'], '../../'.$path . $videoRecord['fullpath'], 0), strlen($path));
                                                $thumb_src = explode('../../', $thumb_src_link);
                                                $thumb_src =$thumb_src[1];
                                                if(!$thumb_src) { $thumb_src = ''; }
                                                $type = 'video';
                                            } else {
                                                $thumb_src = $videoRecord['fullpath'] . $videoRecord['name'];
                                                $type = 'image';
                                            }
                                            $fullLink=checkfile_exist($thumb_src);

                                            if($action_row_count>=1){
                                                $action = "$staticaction the album [TargetName]";
                                            }else{
                                                $action = "$staticaction $gender own album [TargetName]";
                                            }  
                                            if( intval($videoRecord['channelid']) !=0 ){
                                                    $action = "$staticaction their album [TargetName]";
                                            }
                                            $from_id = intval($videoRecord['userid']);
                                            if($from_id != $action_profile['id'] ){
                                                    if( intval($videoRecord['channelid']) !=0 ){
                                                            $channel_array=channelGetInfo($videoRecord['channelid']);
                                                            $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                                            $action_array[] ='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                            $action="$staticaction [Owner]'s album [TargetName]";	
                                                            $owner_name=$channel_array['channel_name'];
                                                            $owner_id=$videoRecord['channelid'];
                                                    }else{
                                                            $userInfo_action = getUserInfo($from_id);	
                                                            $uslnk= userProfileLink($userInfo_action);
                                                            $action="$staticaction your album [TargetName]";
                                                            if( $userInfo_action['id'] != $userId ){
                                                                    $action_array=array();
                                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                    $action="$staticaction [Owner]'s album [TargetName]";
                                                                    $owner_name=returnUserDisplayName($userInfo_action);
                                                                    $owner_id=$from_id;
    //                                                                        $action="$staticaction %s 's ";
                                                            }

                                                    }
                                            }
                                        } 
                                        
                                        $target_id = $entity_id; /*here entity_is album_id*/
                                        $album_info = userCatalogGet($entity_id);
                                        $target_name = $album_info['catalog_name'];
                                        /*to show other images of album mukesh*/
                                        $srch_options = array(
                                           'limit' => 3,
                                           'catalog_id' => $target_id,
                                           'orderby' => 'rand',
                                           'type' => 'a',
                                           'order' => 'd'
                                       );
                                       $photos1 = mediaSearch($srch_options);
                                       $other_img = array();
                                       foreach($photos1 as $photo){
//                                            $other_img[]=array(
//                                                'id'=>$photo['id'],
//                                                'path'=>photoReturnThumbSrc($photo),
//                                                'type'=>$photo['image_video'] == "v" ? 'video' : 'image'
//                                            );
                                           if ($photo['image_video'] == "v") {
                                               $thumb_src_link = substr(getVideoThumbnail($photo['id'], '../../'.$path . $photo['fullpath'], 0), strlen($path));
                                               $thumb_src = explode('../../', $thumb_src_link);
                                               $thumb_src =$thumb_src[1];
                                               $type = 'video';
                                           } else {
                                               $thumb_src = $photo['fullpath'] . $photo['name'];
                                               $type = 'image';
                                           }
                                           $other_img[] = array(
                                               'id'=> $photo['id'], 
                                               'path' => $thumb_src,
                                               'type'=>$type,
                                           ); 
                                       }
                                       /*end*/
                                        break;
                                case SOCIAL_ENTITY_CHANNEL:
                                        if($action_row['entity_type'] == SOCIAL_ENTITY_COMMENT){   
                                            $owner_id=$vfeeditem['original_media_row']['user_id'];
                                            $userInfo_action_who = getUserInfo($owner_id);
                                            $owner_name = returnUserDisplayName($userInfo_action_who);
                                            $action = "$staticaction [Owner]'s comment on the channel [TargetName]";
                                            $overhead_text = htmlEntityDecode($vfeeditem['original_media_row']['comment_text']);
                                        }else{
                                            $channel_url = ReturnLink('channel/'.$videoRecord['channel_url']);
                                            $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($videoRecord['channel_name']).'</span></a>';
                                            $action="$staticaction [TargetName]'s channel";
                                        }
                                            $target_name = $videoRecord['channel_name'];
                                            $target_id = $videoRecord['id'];
                                            $fullLink = 'media/channel/' . $videoRecord['id'] . '/thumb/' . $videoRecord['header'];
                                        break;
                                case SOCIAL_ENTITY_NEWS:
                                    if($action_row['entity_type'] == SOCIAL_ENTITY_COMMENT){   
                                            $owner_id=$vfeeditem['original_media_row']['user_id'];
                                            $userInfo_action_who = getUserInfo($owner_id);
                                            $owner_name = returnUserDisplayName($userInfo_action_who);
                                            $action = "$staticaction [Owner]'s comment on news";
                                            $overhead_text = htmlEntityDecode($vfeeditem['original_media_row']['comment_text']);
                                        }else{
                                            $channel_array=channelGetInfo($videoRecord['channelid']);
                                            $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                            $action_array=array();
                                            $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                            $action="$staticaction [Owner]'s news";
                                            $owner_name = $channel_array['channel_name'];
                                            $owner_id = $videoRecord['channelid'];
                                        }
                                        break;
                                case SOCIAL_ENTITY_BROCHURE:
                                        if($action_row['entity_type'] == SOCIAL_ENTITY_COMMENT){   
                                            $owner_id=$vfeeditem['original_media_row']['user_id'];
                                            $userInfo_action_who = getUserInfo($owner_id);
                                            $owner_name = returnUserDisplayName($userInfo_action_who);
                                            $action = "$staticaction [Owner]'s comment on brochure [TargetName]";
                                            $overhead_text = htmlEntityDecode($vfeeditem['original_media_row']['comment_text']);
                                        }else{
                                            $options = array(
                                                         'id' => $videoRecord['entity_id'],
                                                        );

                                            $channelbrochuresInfo = channelbrochureSearch($options);
                                            $brochure = $channelbrochuresInfo[0];
                                            //$fullLink=($brochure['photo']) ? photoReturnbrochureThumb($brochure) : ReturnLink('media/images/channel/brochure-cover-phot.jpg');
                                            $channel_array=channelGetInfo($videoRecord['channelid']);
                                            $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                            $action_array=array();
                                            $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                            $action="$staticaction [Owner]'s brochure [TargetName]";
                                            $owner_name = $channel_array['channel_name'];
                                            $owner_id = $videoRecord['channelid'];
                                        }
                                            $fullLink = ($videoRecord['photo']) ? 'media/channel/' . $videoRecord['channelid'] . '/brochure/thumb/' . $videoRecord['photo'] : 'media/images/channel/brochure-cover-phot.jpg';
                                            $target_name = $videoRecord['name'];
                                            $target_id = $videoRecord['id'];
                                        break;
                                case SOCIAL_ENTITY_EVENTS:
                                        $target_name = $videoRecord['name'];
                                        $target_id = $videoRecord['id'];
                                        $description = htmlEntityDecode($videoRecord['description']);
                                        $title = $videoRecord['name'];
                                        if($action_row['entity_type'] == SOCIAL_ENTITY_COMMENT){   
                                            $owner_id=$vfeeditem['original_media_row']['user_id'];
                                            $userInfo_action_who = getUserInfo($owner_id);
                                            $owner_name = returnUserDisplayName($userInfo_action_who);
                                            $action = "$staticaction [Owner]'s comment on an event [TargetName]";
                                            $overhead_text = htmlEntityDecode($vfeeditem['original_media_row']['comment_text']);
                                        }else{
                                            $channel_array=channelGetInfo($videoRecord['channelid']);
                                            $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                            $action="$staticaction [Owner]'s event [TargetName]";
                                            $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                            $owner_name = $channel_array['channel_name'];
                                            $owner_id=$videoRecord['channelid'];
                                        }
                                        
                                        break;
                                case SOCIAL_ENTITY_CHANNEL_SLOGAN:
                                        if($action_row['entity_type'] == SOCIAL_ENTITY_COMMENT){   
                                            $owner_id=$vfeeditem['original_media_row']['user_id'];
                                            $userInfo_action_who = getUserInfo($owner_id);
                                            $owner_name = returnUserDisplayName($userInfo_action_who);
                                            $action = "$staticaction [Owner]'s comment on slogan";
                                            $overhead_text = htmlEntityDecode($vfeeditem['original_media_row']['comment_text']);
                                        }else{
                                            $action="$staticaction their slogan";	
                                            $channelDetailArray=GetChannelDetailInfo($entity_id);
                                            $channel_array=channelGetInfo($channelDetailArray['channelid']);
                                            $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                            $from_id = intval($channel_array['owner_id']);						
                                            if($from_id != $action_profile['id'] ){
                                                    $userInfo_action = getUserInfo($from_id);	
                                                    $action_array=array();
                                                    $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                    $action="$staticaction [Owner]'s slogan"; /*%s is replaced by [Owner]'s by mukesh*/	
                                                    $owner_name = $channel_array['channel_name'];
                                                    $owner_id=$videoRecord['channelid'];
                                            }
                                        }
                                        break;
                                case SOCIAL_ENTITY_CHANNEL_INFO:
                                        if($action_row['entity_type'] == SOCIAL_ENTITY_COMMENT){   
                                            $owner_id=$vfeeditem['original_media_row']['user_id'];
                                            $userInfo_action_who = getUserInfo($owner_id);
                                            $owner_name = returnUserDisplayName($userInfo_action_who);
                                            $action = "$staticaction [Owner]'s comment on info";
                                            $overhead_text = htmlEntityDecode($vfeeditem['original_media_row']['comment_text']);
                                        }else{
                                            $action="$staticaction their info";	
                                            $channelDetailArray=GetChannelDetailInfo($entity_id);
                                            $channel_array=channelGetInfo($channelDetailArray['channelid']);
                                            $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                            $from_id = intval($channel_array['owner_id']);						
                                            if($from_id != $action_profile['id'] ){
                                                    $userInfo_action = getUserInfo($from_id);
                                                    $action_array=array();
                                                    $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                    $action="$staticaction [Owner]'s info";	/*%s is replaced by [Owner]'s by mukesh*/
                                                    $owner_name = $channel_array['channel_name'];
                                                    $owner_id=$videoRecord['channelid'];
                                            }
                                        }
                                        break;
                                case SOCIAL_ENTITY_CHANNEL_COVER:
                                        if($action_row['entity_type'] == SOCIAL_ENTITY_COMMENT){   
                                            $owner_id=$vfeeditem['original_media_row']['user_id'];
                                            $userInfo_action_who = getUserInfo($owner_id);
                                            $owner_name = returnUserDisplayName($userInfo_action_who);
                                            $action = "$staticaction [Owner]'s comment on a cover photo";
                                            $overhead_text = htmlEntityDecode($vfeeditem['original_media_row']['comment_text']);
                                        }else{
                                            $action="$staticaction their cover photo";	
                                            $channelDetailArray=GetChannelDetailInfo($entity_id);
                                            $channel_array=channelGetInfo($channelDetailArray['channelid']);
                                            $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                            $from_id = intval($channel_array['owner_id']);	
                                            if($from_id != $action_profile['id'] ){
                                                    $userInfo_action = getUserInfo($from_id);		
                                                    $action_array=array();
                                                    $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'â€™s</span></a>';
                                                    $action="$staticaction [Owner]'s cover photo";		
                                                    $owner_name = $channel_array['channel_name'];
                                                    $owner_id=$videoRecord['channelid'];
                                            }
                                        }
                                        break;
                                case SOCIAL_ENTITY_CHANNEL_PROFILE:
                                        if($action_row['entity_type'] == SOCIAL_ENTITY_COMMENT){   
                                            $owner_id=$vfeeditem['original_media_row']['user_id'];
                                            $userInfo_action_who = getUserInfo($owner_id);
                                            $owner_name = returnUserDisplayName($userInfo_action_who);
                                            $action = "$staticaction [Owner]'s comment on a profile image";
                                            $overhead_text = htmlEntityDecode($vfeeditem['original_media_row']['comment_text']);
                                        }else{
                                            $action="$staticaction their profile image";	
                                            $channelDetailArray=GetChannelDetailInfo($entity_id);
                                            $channel_array=channelGetInfo($channelDetailArray['channelid']);
                                            $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                            $from_id = intval($channel_array['owner_id']);
                                            if($from_id != $action_profile['id'] ){
                                                    $userInfo_action = getUserInfo($from_id);
                                                    $action_array=array();
                                                    $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                    $action="$staticaction [Owner]'s profile image";	/*%s is replaced by [Owner]'s by mukesh*/
                                                    $owner_name=$channel_array['channel_name'];
                                                    $owner_id=$from_id;
                                                    //$fullLink = 'media/channel/' . $channel_id . '/thumb/' . $videoRecord['detail_text'];// $channel_array['logo'];
                                                    $fullLink = 'media/channel/' . $channel_id . '/thumb/' . $channel_array['logo'];
                                            }
                                        }
                                        break;
                                case SOCIAL_ENTITY_LOCATION:
                                        break;
                                case SOCIAL_ENTITY_COMMENT:
                                        $action = "$staticaction the comment";
                                        $title = $videoRecord['title'];
                                        $overhead_text = htmlEntityDecode($vfeeditem['original_media_row']['comment_text']);
                                        break;
                                case SOCIAL_ENTITY_USER_PROFILE:
                                        if($action_row_count>=1){
                                            $action="$staticaction the profile image";
                                        }else{
                                            $action="$staticaction $gender own profile image";
                                        }  
                                        $userDetailArray=getUserDetail($entity_id);
                                        /*to show bigimage in fulllink*/
                                        $img = explode('Profile_', $videoRecord['detail_text']);
                                        $fullLink = 'media/tubers/' . $img[1];
                                        //$fullLink = 'media/tubers/' . $videoRecord['detail_text'];
                                        $from_id = intval($userDetailArray['user_id']);	
                                         /*update user pic info was not showing*/    
                                        $userInfo_feed = getUserInfo($from_id);
                                        $user_stats = userGetStatistics($from_id,1);
                                        $city = $userInfo_feed['city'];
                                        $country = $userInfo_feed['YourCountry'];
                                        $locationval_text = '' . $city . '';
                                        $locationval = countryGetName($country);
                                        if ($locationval != '') {
                                            $locationval_text .=', ' . $locationval . '';
                                        }
                                        $ts_date = strtotime($userInfo_feed['RegisteredDate']);
                                        $print_date = date('M. d, Y', $ts_date);
                                        $strpop = t(_('tuber since %when'), array('%when' => $print_date));
                                        $address=$locationval_text;
                                        $register_date=$strpop;
                                        $nb_followers=$user_stats['nFollowers'];
                                        $nb_friends=$user_stats['nFriends'];
                                        if($action_row['entity_type'] == SOCIAL_ENTITY_COMMENT){   
                                            $owner_id=$vfeeditem['original_media_row']['user_id'];
                                            $userInfo_action_who = getUserInfo($owner_id);
                                            $owner_name = returnUserDisplayName($userInfo_action_who);
                                            $action = "$staticaction [Owner]'s comment on a profile image";
                                            $overhead_text = htmlEntityDecode($vfeeditem['original_media_row']['comment_text']);
                                        }else{
                                            if($from_id != $action_profile['id'] ){
                                                    $userInfo_action = getUserInfo($from_id);
                                                    $uslnk= userProfileLink($userInfo_action);
                                                    $action="$staticaction your profile image";
                                                    if( $userInfo_action['id'] != $userId ){
                                                            $action="$staticaction [Owner]'s profile image";
                                                            $action_array = array();
                                                            $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'â€™s</span></a>';
                                                            $owner_name = returnUserDisplayName($userInfo_action);
                                                            $owner_id=$from_id;
                                                    }
                                                    $img = explode('Profile_', $videoRecord['detail_text']);
                                                    $fullLink = 'media/tubers/' . $img[1]; //$userInfo_action['profile_Pic'];
                                            }
                                        }
                                        break;
                                case SOCIAL_ENTITY_HOTEL_REVIEWS:
                                case SOCIAL_ENTITY_RESTAURANT_REVIEWS:
                                case SOCIAL_ENTITY_LANDMARK_REVIEWS:
                                case SOCIAL_ENTITY_AIRPORT_REVIEWS:
                                    if($action_row['entity_type'] == SOCIAL_ENTITY_COMMENT){   
                                            $owner_id=$vfeeditem['original_media_row']['user_id'];
                                            $userInfo_action_who = getUserInfo($owner_id);
                                            $owner_name = returnUserDisplayName($userInfo_action_who);
                                            $action = "$staticaction [Owner]'s comment on review";
                                            $overhead_text = htmlEntityDecode($vfeeditem['original_media_row']['comment_text']);
                                        }else{
                                            if($action_row_count>=1){
                                                $action= "$staticaction the review";
                                            }else{
                                                $action= "$staticaction $gender own review";
                                            }  
                                            $from_id = intval($videoRecord['user_id']);
                                            if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);
                                                $uslnk= userProfileLink($userInfo_action);
                                                $action="$staticaction your review";
                                                if( $userInfo_action['id'] != $userId ){
                                                    $action_array=array();
                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                    $action="$staticaction [Owner]'s review";
                                                    $owner_name= returnUserDisplayName($userInfo_action);
                                                    $owner_id=$from_id;
                                                }
                                            }
                                        }
                                break;
                                case SOCIAL_ENTITY_POST: // echo 'hi';die;
                                    //$fullLink = getPostThumbPath($vfeeditem['media_row']);
                                    if($action_row['entity_type'] == SOCIAL_ENTITY_COMMENT){   
                                        $owner_id=$vfeeditem['original_media_row']['user_id'];
                                        $userInfo_action_who = getUserInfo($owner_id);
                                        $owner_name = returnUserDisplayName($userInfo_action_who);
                                        $action = "$staticaction [Owner]'s comment on a post";
                                        $overhead_text = htmlEntityDecode($vfeeditem['original_media_row']['comment_text']);
                                    }else{ 
                                        if($action_row_count>=1){
                                            $action="$staticaction the post";
                                        }else{
                                            $action="$staticaction $gender own post";
                                        }  
                                        if( intval($videoRecord['channel_id']) !=0 ){
                                            $action = "$staticaction their post";
                                        }
                                        $from_id = intval($videoRecord['from_id']);
                                        if($from_id==0) $from_id = intval($videoRecord['user_id']);
                                        if($from_id != $action_profile['id'] ){
                                            if( intval($videoRecord['channel_id']) !=0 ){
                                                $channel_array=channelGetInfo($videoRecord['channel_id']);
                                                $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                                $from_id = intval($channel_array['owner_id']);
                                                $userInfo_action = getUserInfo($from_id);
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';                                                                       
                                                $action="$staticaction [Owner]'s post";	
                                                $owner_name = $channel_array['channel_name'];
                                                $owner_id = $videoRecord['channelid'];
                                            }else{
                                                $userInfo_action = getUserInfo($from_id);
                                                $uslnk= userProfileLink($userInfo_action);
                                                $action="$staticaction your post";	
                                                if( $userInfo_action['id'] != $userId ){
                                                    $action_array=array();	
                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                    $action="$staticaction [Owner]'s post";	
                                                    $owner_name = returnUserDisplayName($userInfo_action);
                                                    $owner_id = $from_id;
                                                }
                                            }
                                        }   
                                    }
                                        if ($videoRecord != "") { 
                                            if($videoRecord['media_file'] != ''){
                                                if ($videoRecord['is_video'] == 0) {// echo 'if';die;
                                                    //$repath = relativevideoReturnPostPath($videoRecord);
                                                    if (intval($videoRecord['channel_id']) == 0 && intval($videoRecord['from_id']) != 0) {
                                                        global $CONFIG;
                                                        $videoPath = $CONFIG['video']['uploadPath'];
                                                        $rpath = $videoRecord['relativepath'];
                                                        $repath= $videoPath . 'posts/' . $rpath;
                                                    } else {
                                                        $repath='media/channel/' . $vinfo['channel_id'] . '/posts/';
                                                    }
                                                    $repath .=  $videoRecord['media_file'];

                                                }else{  
                                                    $cod1 = explode('.',  $videoRecord['media_file']);
                                                    $cod2 = explode('_', $cod1[0]);
                                                    $videoCode = $cod2[1];
                                                    $repath = relativevideoGetPostPath($videoRecord);
                                                    $videoCode = 'small_postThumb' . $videoCode;
                                                    $picthumb_img = getVideoThumbnail_Posts($videoCode, '../../'.$repath, 0);
                                                    $picthumb_img = explode('../../', $picthumb_img);
                                                    $repath =$picthumb_img[1];
                                                }
                                            }else{
                                                $repath='';
                                            }
                                        }
                                        $fullLink=checkfile_exist($repath); 
                                break;
                                case SOCIAL_ENTITY_FLASH:  /*need to change*/
                                    $echo_text = $vfeeditem['media_row']['flash_text'];
                                    $fullLink=($videoRecord['vpath']) ? $videoRecord['vpath'] . $videoRecord['pic_file'] : '';
                                    if($action_row['entity_type'] == SOCIAL_ENTITY_COMMENT){   
                                        $owner_id=$vfeeditem['original_media_row']['user_id'];
                                        $userInfo_action_who = getUserInfo($owner_id);
                                        $owner_name = returnUserDisplayName($userInfo_action_who);
                                        $action = "$staticaction [Owner]'s comment on echo";
                                        $overhead_text = htmlEntityDecode($vfeeditem['original_media_row']['comment_text']);
                                    }else{ 
                                        if($action_row_count>=1){
                                            //$action="$staticaction the %s echo %s";
                                            $action="$staticaction the echo";
                                        }else{
                                            $action="$staticaction $gender own echo";
                                        }  
                                        if( intval($videoRecord['channel_id']) !=0 ){
                                                $action = "$staticaction their echo";
                                        }
                                        $from_id = intval($videoRecord['user_id']);
                                        
                                        if($from_id != $action_profile['id'] ){
                                                if( intval($videoRecord['channel_id']) !=0 ){
                                                        $channel_array=channelGetInfo($videoRecord['channel_id']);
                                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                                        $from_id = intval($channel_array['owner_id']);
                                                        $userInfo_action = getUserInfo($from_id);
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';                                                                       
                                                        $action="$staticaction [Owner]'s echo";	
                                                        $owner_name = $channel_array['channel_name'];
                                                        $owner_id=$videoRecord['channelid'];
                                                    }else{
                                                        $userInfo_action = getUserInfo($from_id);
                                                        $uslnk= userProfileLink($userInfo_action);
                                                        $action="$staticaction your echo";	
                                                        if( $userInfo_action['id'] != $userId  && $from_id != 0){
                                                            $action_array=array();	
                                                            $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                            $action="$staticaction [Owner]'s echo";	
                                                            $owner_id = $from_id;
                                                            $owner_name = returnUserDisplayName($userInfo_action);
                                                        }
                                                }
                                        } 
                                    }
                                break;
                                case SOCIAL_ENTITY_USER_EVENTS:
                                        $target_name = $videoRecord['name'];
                                        $target_id = $videoRecord['id'];
                                        $description = htmlEntityDecode($videoRecord['description']);
                                        $title = $videoRecord['name']; 
                                        if($action_row['entity_type'] == SOCIAL_ENTITY_COMMENT){   
                                            $owner_id=$vfeeditem['original_media_row']['user_id'];
                                            $userInfo_action_who = getUserInfo($owner_id);
                                            $owner_name = returnUserDisplayName($userInfo_action_who);
                                            $action = "$staticaction [Owner]'s comment on an event [TargetName]";
                                            $overhead_text = htmlEntityDecode($vfeeditem['original_media_row']['comment_text']);
                                        }else{
                                            if($action_row_count>=1){
                                                $action="$staticaction the event [TargetName]";
                                            }else{
                                                $action="$staticaction $gender own event [TargetName]";
                                            }  
                                            $from_id = intval($videoRecord['user_id']);						
                                            if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);
                                                $uslnk= userProfileLink($userInfo_action);
                                                $where_action ='your';
                                                $action="$staticaction your event [TargetName]";
                                                if( $userInfo_action['id'] != $userId ){
                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                    $action="$staticaction [Owner]'s event [TargetName]";
                                                    $owner_name = returnUserDisplayName($userInfo_action);
                                                    $owner_id=$from_id;
                                                }													
                                            }	
                                        }
                                        break;
                            /*  case SOCIAL_ENTITY_JOURNAL:
                                        if($action_row_count>=1){
                                            $action="$staticaction the journal [TargetName]";
                                        }else{
                                            $action="$staticaction $gender own journal [TargetName]";
                                        }  
                                        $from_id = intval($videoRecord['user_id']);
                                        $target_name = $videoRecord['name'];
                                        $target_id = $videoRecord['id'];
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);
                                                $uslnk= userProfileLink($userInfo_action);
                                                $where_action ='your';
                                                $action="$staticaction your journal [TargetName]";
                                                if( $userInfo_action['id'] != $userId ){
                                                    $action_array=array();	
                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                    $action="$staticaction [Owner]'s journal [TargetName]"; 
                                                    $owner_id=$from_id;
                                                    $owner_name= returnUserDisplayName($userInfo_action);
                                                }
                                        }
                                        break;
                                    */    
                                case SOCIAL_ENTITY_SHARE:
                                        break;
                                case SOCIAL_ENTITY_USER:
                                        break;
                        }
                        if($action_row_count>1){
                            $stoth ='';
                            $i=0;
                            foreach($action_row_other as $action_row_otherIT){
                                $action_profiletb = getUserInfo($action_row_otherIT['user_id']);
                                $uslnkoth= userProfileLink($action_profiletb);
                                $uslnkothname= returnUserDisplayName($action_profiletb);

                                $stoth .= '<a class="tt_otherlink_a" href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>';
                                $i++;
                                if($i>=10 && $action_row_count>10){
                                    $stoth .= '<a class="tt_otherlink_a"><strong>'. vsprintf(ngettext("and %s other...", "and %s others...", ($action_row_count - $i) ) , array( $action_row_count - $i ) ) .'</strong></a>';
                                    break;
                                }
                            }
                            array_unshift($action_array, '</strong><div class="tt_otherlink_over"><div class="tt_otherlink_overin"><div class="tt_otherlink_over_arrow"></div>'.$stoth.'</div></div></span>');
                            array_unshift($action_array, '<span class="tt_otherlink_span"><strong>'.$action_row_count);
                        }else if($action_row_count==1){                            
                            $action_profiletb = getUserInfo($action_row_other['user_id']);
                            $uslnkoth= userProfileLink($action_profiletb);
                            $uslnkothname= returnUserDisplayName($action_profiletb);  
                            $other = $uslnkothname;
                            $otherId = $action_row_other['user_id'];
                            array_unshift($action_array, '<a href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>');
                        }
                        break;
                case SOCIAL_ACTION_RATE:
                        $title = $videoRecord['title'];
                        $description = $videoRecord['description'];
                        $description = htmlEntityDecode($description);
                        $staticaction = "[ActionOwner] rated";                        
                        if($action_row_count>1){
                            $staticaction = "[ActionOwner] and [Others] rated";
                        }else if($action_row_count==1){                            
                            $staticaction = "[ActionOwner] and [Other] rated";
                        }
                        $action = "$staticaction a ";
                        switch($entity_type){
                                case SOCIAL_ENTITY_MEDIA:
                                        if($action_row_count>=1){
                                            $action = "$staticaction the $video_photo [TargetName]";
                                        }else{
                                            $action = "$staticaction $gender own $video_photo [TargetName]";
                                        }  
                                        if( intval($videoRecord['channelid']) !=0 ){
                                                $action = "$staticaction their $video_photo [TargetName]";
                                        }
                                        $title = $videoRecord['title'];
                                        $description = $videoRecord['description'];
                                        $description = htmlEntityDecode($description);
                                        $from_id = intval($videoRecord['userid']);
                                        $target_name = stripslashes($videoRecord['title']);
                                        $target_id = $videoRecord['id'];
                                        //$fullLink = $videoRecord['fullpath'] . $videoRecord['name'];
                                        //$fullLink = photoReturnSrcSmall($videoRecord);
                                         if ($videoRecord['image_video'] == "v") {
                                            $thumb_src_link = substr(getVideoThumbnail($videoRecord['id'], '../../'.$path . $videoRecord['fullpath'], 0), strlen($path));
                                            $thumb_src = explode('../../', $thumb_src_link);
                                            $thumb_src =$thumb_src[1];
                                            if(!$thumb_src) { $thumb_src = ''; }
                                            $type = 'video';
                                        } else {
                                            $thumb_src = $videoRecord['fullpath'] . $videoRecord['name'];
                                            $type = 'image';
                                        }
                                        $fullLink=checkfile_exist($thumb_src);
                                        if($from_id != $action_profile['id'] ){
                                                if( intval($videoRecord['channelid']) !=0 ){
                                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                                        $action_array=array();
                                                        $action="$staticaction [Owner]'s $video_photo [TargetName]";
                                                        $owner_name = $channel_array['channel_name'];
                                                        $owner_id=$videoRecord['channelid'];
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
//                                                                $action="$staticaction %s 's ";	
                                                }else{
                                                        $userInfo_action = getUserInfo($from_id);	
                                                        $uslnk= userProfileLink($userInfo_action);
//                                                                $action="$staticaction your ";
                                                        $action="$staticaction your $video_photo [TargetName]";
                                                        if( $userInfo_action['id'] != $userId ){
                                                            $action_array=array();
                                                            $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
//                                                                    $action="$staticaction %s 's ";
                                                            $owner_name = returnUserDisplayName($userInfo_action);
                                                            $owner_id = $from_id;
                                                            $action="$staticaction [Owner]'s $video_photo [TargetName]";
                                                        }
                                                }
                                        }						
                                        break;
//                                case SOCIAL_ENTITY_WEBCAM:
//                                        $action = "$staticaction ";
//                                        break;
                                case SOCIAL_ENTITY_ALBUM:						
                                        $title = $videoRecord['title'];
                                        $description = $videoRecord['description'];
                                        $description = htmlEntityDecode($description);
                                        /*to show full link of album*/
                                        //$fullLink = photoReturnSrcSmall($videoRecord);
                                         if ($videoRecord['image_video'] == "v") {
                                            $thumb_src_link = substr(getVideoThumbnail($videoRecord['id'], '../../'.$path . $videoRecord['fullpath'], 0), strlen($path));
                                            $thumb_src = explode('../../', $thumb_src_link);
                                            $thumb_src =$thumb_src[1];
                                            if(!$thumb_src) { $thumb_src = ''; }
                                            $type = 'video';
                                        } else {
                                            $thumb_src = $videoRecord['fullpath'] . $videoRecord['name'];
                                            $type = 'image';
                                        }
                                        $fullLink=checkfile_exist($thumb_src);
                                        $target_id = $entity_id; /*here entity_is album_id*/
                                        $album_info = userCatalogGet($entity_id);
                                        $target_name = $album_info['catalog_name'];
                                        /*to show other images of album mukesh*/
                                         $srch_options = array(
                                            'limit' => 3,
                                            'catalog_id' => $target_id,
                                            'orderby' => 'rand',
                                            'type' => 'a',
                                            'order' => 'd'
                                        );
                                        $photos1 = mediaSearch($srch_options);
                                        $other_img = array();
                                        foreach($photos1 as $photo){
//                                            $other_img[]=array(
//                                                'id'=>$photo['id'],
//                                                'path'=>photoReturnThumbSrc($photo),
//                                                'type'=>$photo['image_video'] == "v" ? 'video' : 'image'
//                                            );
                                            if ($photo['image_video'] == "v") {
                                                $thumb_src_link = substr(getVideoThumbnail($photo['id'], '../../'.$path . $photo['fullpath'], 0), strlen($path));
                                                $thumb_src = explode('../../', $thumb_src_link);
                                                $thumb_src =$thumb_src[1];
                                                $type = 'video';
                                            } else {
                                                $thumb_src = $photo['fullpath'] . $photo['name'];
                                                $type = 'image';
                                            }
                                            $other_img[] = array(
                                                'id'=> $photo['id'], 
                                                'path' => $thumb_src,
                                                'type'=>$type,
                                            ); 
                                        }
                                        /*end*/
                                        if($action_row_count>=1){
                                            $action = "$staticaction the album [TargetName]";
                                        }else{
                                            $action = "$staticaction $gender own album [TargetName]";
                                        }  
                                        if( intval($videoRecord['channelid']) !=0 ){
                                            $action = "$staticaction their album [TargetName]";
                                        }
                                        $from_id = intval($videoRecord['userid']);
                                        if($from_id != $action_profile['id'] ){
                                            if( intval($videoRecord['channelid']) !=0 ){
                                                $channel_array=channelGetInfo($videoRecord['channelid']);
                                                $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                                $action_array[] = '<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                $action="$staticaction [Owner]'s album [TargetName]";	/*%s is replaced by [Owner]'s by mukesh*/
                                                $owner_name = $channel_array['channel_name'];
                                                $owner_id=$videoRecord['channelid'];
                                            }else{
                                                $userInfo_action = getUserInfo($from_id);	
                                                $uslnk= userProfileLink($userInfo_action);
                                                $action="$staticaction your album [TargetName]";
                                                if( $userInfo_action['id'] != $userId ){
                                                    $action_array=array();
                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                    $action="$staticaction [Owner]'s album [TargetName]"; /*%s is replaced by [Owner]'s by mukesh*/
                                                    $owner_name = returnUserDisplayName($userInfo_action);
                                                    $owner_id=$from_id;
                                                }
                                            }
                                        }
                                        break;                                
                                case SOCIAL_ENTITY_POST:
                                    //$fullLink = getPostThumbPath($vfeeditem['media_row']);
                                    if ($videoRecord != "") { 
                                        if($videoRecord['media_file'] != ''){
                                            if ($videoRecord['is_video'] == 0) {// echo 'if';die;
                                                //$repath = relativevideoReturnPostPath($videoRecord);
                                                if (intval($videoRecord['channel_id']) == 0 && intval($videoRecord['from_id']) != 0) {
                                                    global $CONFIG;
                                                    $videoPath = $CONFIG['video']['uploadPath'];
                                                    $rpath = $videoRecord['relativepath'];
                                                    $repath= $videoPath . 'posts/' . $rpath;
                                                } else {
                                                    $repath='media/channel/' . $vinfo['channel_id'] . '/posts/';
                                                }
                                                $repath .=  $videoRecord['media_file'];

                                            }else{  
                                                $cod1 = explode('.',  $videoRecord['media_file']);
                                                $cod2 = explode('_', $cod1[0]);
                                                $videoCode = $cod2[1];
                                                $repath = relativevideoGetPostPath($videoRecord);
                                                $videoCode = 'small_postThumb' . $videoCode;
                                                $picthumb_img = getVideoThumbnail_Posts($videoCode, '../../'.$repath, 0);
                                                $picthumb_img = explode('../../', $picthumb_img);
                                                $repath =$picthumb_img[1];
                                            }
                                        }else{
                                            $repath='';
                                        }
                                    }
                                    $fullLink=checkfile_exist($repath); 
                                    if($action_row_count>=1){
                                        $action="$staticaction the post";
                                    }else{
                                        $action="$staticaction $gender own post";
                                    }  
                                    if( intval($videoRecord['channel_id']) !=0 ){
                                            $action = "$staticaction their post";
                                    }
                                    $from_id = intval($videoRecord['from_id']);
                                    if($from_id==0) $from_id = intval($videoRecord['user_id']);

                                    if($from_id != $action_profile['id'] ){
                                        if( intval($videoRecord['channel_id']) !=0 ){
                                            $channel_array=channelGetInfo($videoRecord['channel_id']);
                                            $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                            $from_id = intval($channel_array['owner_id']);
                                            $userInfo_action = getUserInfo($from_id);
                                            $action_array=array();
                                            $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                            $action="$staticaction [Owner]'s post";	 /*%s is replaced by [Owner]'s by mukesh*/
                                            $owner_name = $channel_array['channel_name'];
                                            $owner_id = $videoRecord['channelid'];
                                        }else{
                                            $userInfo_action = getUserInfo($from_id);	
                                            $uslnk= userProfileLink($userInfo_action);
                                            $action="$staticaction your post";	
                                            if( $userInfo_action['id'] != $userId ){
                                                $action="$staticaction [Owner]'s post";	/*%s is replaced by [Owner]'s by mukesh*/
                                                $action_array=array();
                                                $action_array[]= '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                $owner_name = returnUserDisplayName($userInfo_action);
                                                $owner_id = $from_id;
                                            }
                                        }
                                    }
                                    break;
                                case SOCIAL_ENTITY_LOCATION:
                                        break;
                                case SOCIAL_ENTITY_COMMENT:
                                        break;
                                case SOCIAL_ENTITY_FLASH:
                                        break;
                                case SOCIAL_ENTITY_SHARE:
                                        break;
                                case SOCIAL_ENTITY_USER:
                                        break;
                        }
                        if($action_row_count>1){
                            $stoth ='';
                            $i=0;
                            foreach($action_row_other as $action_row_otherIT){
                                $action_profiletb = getUserInfo($action_row_otherIT['user_id']);
                                $uslnkoth= userProfileLink($action_profiletb);
                                $uslnkothname= returnUserDisplayName($action_profiletb);

                                $stoth .= '<a class="tt_otherlink_a" href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>';
                                $i++;
                                if($i>=10 && $action_row_count>10){
                                    $stoth .= '<a class="tt_otherlink_a"><strong>'. vsprintf(ngettext("and %s other...", "and %s others...", ($action_row_count - $i) ) , array( $action_row_count - $i ) ) .'</strong></a>';
                                    break;
                                }
                            }
                            array_unshift($action_array, '</strong><div class="tt_otherlink_over"><div class="tt_otherlink_overin"><div class="tt_otherlink_over_arrow"></div>'.$stoth.'</div></div></span>');
                            array_unshift($action_array, '<span class="tt_otherlink_span"><strong>'.$action_row_count);
                        }else if($action_row_count==1){                             
                            $action_profiletb = getUserInfo($action_row_other['user_id']);
                            $uslnkoth= userProfileLink($action_profiletb);
                            $uslnkothname= returnUserDisplayName($action_profiletb);
                            $other = $uslnkothname;
                            $otherId = $action_row_other['user_id'];
                            array_unshift($action_array, '<a href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>');
                        }
                        break;
                case SOCIAL_ACTION_REPLY:
                    $overhead_text = htmlEntityDecode($actionRecord['msg']);
                    $staticaction = "[ActionOwner] commented on";                        
                    if($action_row_count>1){
                        $staticaction = "[ActionOwner] and [Others] commented on";
                    }else if($action_row_count==1){                            
                        $staticaction = "[ActionOwner] and [Other] commented on";
                    }
                    if($action_row_count>=1){
                        $action="$staticaction the echo";
                    }else{
                        $action="$staticaction $gender own echo";
                    }  
                    if( intval($videoRecord['channel_id']) !=0 ){
                            $action = "$staticaction their echo";
                    }
                    $from_id = intval($videoRecord['user_id']);
                    //$echo_text = $vfeeditem['media_row']['flash_text'];

                    if($from_id != $action_profile['id'] ){
                            if( intval($videoRecord['channel_id']) !=0 ){
                                    $channel_array=channelGetInfo($videoRecord['channel_id']);
                                    $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                    $from_id = intval($channel_array['owner_id']);
                                    $userInfo_action = getUserInfo($from_id);										
                                    $action_array=array();
                                    $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                    $action="$staticaction [Owner]'s echo [TargetName]";
                                    $owner_name = $channel_array['channel_name'];
                                    $owner_id=$videoRecord['channelid'];
                            }else{
                                    $userInfo_action = getUserInfo($from_id);		
                                    $uslnk= userProfileLink($userInfo_action);
                                    $action="$staticaction your own echo";
                                    if( $userInfo_action['id'] != $userId  && $from_id != 0){
                                            $action_array=array();
                                            $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'â€™s</span></a>';
                                            $action="$staticaction [Owner]'s echo [TargetName]";
                                            $owner_name = returnUserDisplayName($userInfo_action);
                                            $owner_id = $from_id;

                                    }

                            }
                    }

                    break;
                case SOCIAL_ACTION_COMMENT:
                        $overhead_text = htmlEntityDecode($actionRecord['comment_text']);                        
                        $staticaction = "[ActionOwner] commented on";                        
                        if($action_row_count>1){
                            $staticaction = "[ActionOwner] and [Others] commented on"; /*muk*/
                        }else if($action_row_count==1){                            
                            $staticaction = "[ActionOwner] and [Other] commented on";
                        }

                        switch($entity_type){
                                case SOCIAL_ENTITY_MEDIA:
                                        if($action_row_count>=1){
                                            $action = "$staticaction the $video_photo [TargetName]";
                                        }else{
                                            $action = "$staticaction $gender own $video_photo [TargetName]";
                                        }  

                                        if( intval($videoRecord['channelid']) !=0 ){
                                                //$action = "$staticaction their $video_photo [TargetName]";
                                                $action = "$staticaction a $video_photo [TargetName]";
                                                $action_array=array();
                                        }
                                        $title = $videoRecord['title'];
                                        $description = $videoRecord['description'];
                                        $description = htmlEntityDecode($description);

                                        $from_id = intval($videoRecord['userid']);
                                        $target_name = stripslashes($videoRecord['title']);
                                        $target_id = $videoRecord['id'];
//                                      $fullLink = $videoRecord['fullpath'] . $videoRecord['name'];
                                        //$fullLink = photoReturnSrcSmall($videoRecord);
                                        if ($videoRecord['image_video'] == "v") {
                                            $thumb_src_link = substr(getVideoThumbnail($videoRecord['id'], '../../'.$path . $videoRecord['fullpath'], 0), strlen($path));
                                            $thumb_src = explode('../../', $thumb_src_link);
                                            $thumb_src =$thumb_src[1];
                                            if(!$thumb_src) { $thumb_src = ''; }
                                            $type = 'video';
                                        } else {
                                            $thumb_src = $videoRecord['fullpath'] . $videoRecord['name'];
                                            $type = 'image';
                                        }
                                        $fullLink=checkfile_exist($thumb_src);
                                        if($from_id != $action_profile['id'] ){
                                                if( intval($videoRecord['channelid']) !=0 ){
                                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
//                                                                $action="$staticaction %s 's ";	
                                                        $action="$staticaction [Owner]'s $video_photo [TargetName]";
                                                        $owner_name = $channel_array['channel_name'];
                                                        $owner_id=$videoRecord['channelid'];
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                }else{
                                                        $userInfo_action = getUserInfo($from_id);	
                                                        $uslnk= userProfileLink($userInfo_action); 
                                                        $where_action ='your';
//                                                                $action="$staticaction your ";
                                                        $action="$staticaction your $video_photo [TargetName]";
                                                        if( $userInfo_action['id'] != $userId ){
                                                            $action_array=array();
                                                            $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
//                                                                    $action="$staticaction %s 's ";
                                                            $owner_name = returnUserDisplayName($userInfo_action);
                                                            $owner_id=$from_id;
                                                            $action="$staticaction [Owner]'s $video_photo [TargetName]";
                                                           
                                                        }


                                                }
                                        }
                                break;
                                case SOCIAL_ENTITY_FLASH:
                                    $action_array=array();
                                    $echo_text = $vfeeditem['media_row']['flash_text'];
                                    if($action_row_count>=1){
                                        $action="$staticaction the echo";
                                    }else{
                                        $action="$staticaction $gender own echo";
                                    }  
                                    if( intval($videoRecord['channel_id']) !=0 ){
                                            $action = "$staticaction their echo";
                                    }
                                    $from_id = intval($videoRecord['user_id']);
                                    $fullLink=($videoRecord['vpath']) ? $videoRecord['vpath'] . $videoRecord['pic_file'] : '';  
                                    if($from_id != $action_profile['id'] ){
                                        if( intval($videoRecord['channel_id']) !=0 ){
                                            $channel_array=channelGetInfo($videoRecord['channel_id']);
                                            $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                            $from_id = intval($channel_array['owner_id']);
                                            $userInfo_action = getUserInfo($from_id);										
                                            $action_array=array();
                                            $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                            $action="$staticaction [Owner]'s echo";
                                            $owner_name = $channel_array['channel_name'];
                                            $owner_id=$videoRecord['channelid'];
                                        }else{
                                            $userInfo_action = getUserInfo($from_id);		
                                            $uslnk= userProfileLink($userInfo_action);
                                            $action="$staticaction your echo";
                                            if( $userInfo_action['id'] != $userId && $from_id != 0){
                                                $action_array=array();
                                                $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'â€™s</span></a>';
                                                $action="$staticaction [Owner]'s echo";
                                                $owner_id = $from_id;
                                                $owner_name = returnUserDisplayName($userInfo_action);
                                            }

                                        }
                                    }                                    
                                break;
//                                case SOCIAL_ENTITY_WEBCAM:
//                                        $action = "$staticaction ";
//                                        $description = $videoRecord['description'];
//                                        $description = htmlEntityDecode($description);
//                                        break;
                                case SOCIAL_ENTITY_ALBUM:
                                        if($action_row_count>=1){
                                            $action = "$staticaction the album [TargetName]";
                                        }else{
                                            $action = "$staticaction $gender own album [TargetName]";
                                        }  
                                        if( intval($videoRecord['channelid']) !=0 ){
                                                $action = "$staticaction their album [TargetName]";
                                        }
                                        $title = $videoRecord['title'];
                                        //$target_name = $title;
                                        /*to show full link of album*/
                                        //$fullLink = photoReturnSrcSmall($videoRecord);
                                         if ($videoRecord['image_video'] == "v") {
                                            $thumb_src_link = substr(getVideoThumbnail($videoRecord['id'], '../../'.$path . $videoRecord['fullpath'], 0), strlen($path));
                                            $thumb_src = explode('../../', $thumb_src_link);
                                            $thumb_src =$thumb_src[1];
                                            if(!$thumb_src) { $thumb_src = ''; }
                                            $type = 'video';
                                        } else {
                                            $thumb_src = $videoRecord['fullpath'] . $videoRecord['name'];
                                            $type = 'image';
                                        }
                                        $fullLink=checkfile_exist($thumb_src);
                                        $target_id = $entity_id; /*here entity_is album_id*/
                                        $album_info = userCatalogGet($entity_id);
                                        $target_name = $album_info['catalog_name'];
                                        /*to show other images of album mukesh*/
                                         $srch_options = array(
                                            'limit' => 3,
                                            'catalog_id' => $target_id,
                                            'orderby' => 'rand',
                                            'type' => 'a',
                                            'order' => 'd'
                                        );
                                        $photos1 = mediaSearch($srch_options);
                                        $other_img = array();
                                        foreach($photos1 as $photo){
//                                            $other_img[]=array(
//                                                'id'=>$photo['id'],
//                                                'path'=>photoReturnThumbSrc($photo),
//                                                'type'=>$photo['image_video'] == "v" ? 'video' : 'image'
//                                            );
                                            if ($photo['image_video'] == "v") {
                                                $thumb_src_link = substr(getVideoThumbnail($photo['id'], '../../'.$path . $photo['fullpath'], 0), strlen($path));
                                                $thumb_src = explode('../../', $thumb_src_link);
                                                $thumb_src =$thumb_src[1];
                                                $type = 'video';
                                            } else {
                                                $thumb_src = $photo['fullpath'] . $photo['name'];
                                                $type = 'image';
                                            }
                                            $other_img[] = array(
                                                'id'=> $photo['id'], 
                                                'path' => $thumb_src,
                                                'type'=>$type,
                                            );  
                                        }
                                        /*end*/
                                        $from_id = intval($videoRecord['userid']);
                                        if($from_id != $action_profile['id'] ){
                                                if( intval($videoRecord['channelid']) !=0 ){
                                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'â€™s</span></a>';
                                                        $action="$staticaction [Owner]'s album [TargetName]";	
                                                        $owner_name=$channel_array['channel_name'];
                                                        $owner_id=$videoRecord['channelid'];
                                                }else{
                                                        $userInfo_action = getUserInfo($from_id);	
                                                        $uslnk= userProfileLink($userInfo_action);
                                                        $action="$staticaction your album [TargetName]";
                                                        if( $userInfo_action['id'] != $userId ){
                                                                $action_array=array();
                                                                $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                $action="$staticaction [Owner]'s album [TargetName]";
                                                                $owner_name=returnUserDisplayName($userInfo_action);
                                                                $owner_id=$from_id;
                                                        }

                                                }
                                        }
                                        break;
                                case SOCIAL_ENTITY_USER_PROFILE:
                                        if($action_row_count>=1){
                                            $action="$staticaction the profile image";
                                        }else{
                                            $action="$staticaction $gender own profile image";
                                        }  
                                        $userDetailArray=getUserDetail($entity_id);
                                        //$img = explode('Profile_', $action_profile['profile_Pic']);
                                        //$fullLink = 'media/tubers/' . $img[1];
                                        $from_id = intval($userDetailArray['user_id']);	
                                      /*update user pic info was not showing*/    
                                        $userInfo_feed = getUserInfo($from_id);
                                        $user_stats = userGetStatistics($from_id,1);
                                        $city = $userInfo_feed['city'];
                                        $country = $userInfo_feed['YourCountry'];
                                        $locationval_text = '' . $city . '';
                                        $locationval = countryGetName($country);
                                        if ($locationval != '') {
                                            $locationval_text .=', ' . $locationval . '';
                                        }
                                        $ts_date = strtotime($userInfo_feed['RegisteredDate']);
                                        $print_date = date('M. d, Y', $ts_date);
                                        $strpop = t(_('tuber since %when'), array('%when' => $print_date));
                                        $address=$locationval_text;
                                        $register_date=$strpop;
                                        $nb_followers=$user_stats['nFollowers'];
                                        $nb_friends=$user_stats['nFriends'];
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);		
                                                $uslnk= userProfileLink($userInfo_action);
                                                $action="$staticaction your profile image";
                                                if( $userInfo_action['id'] != $userId ){
                                                    $action_array=array();	
                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'â€™s</span></a>';
                                                    $action="$staticaction [Owner]'s profile image";
                                                    $owner_id=$from_id;
                                                    $owner_name= returnUserDisplayName($userInfo_action);       
                                                }
                                                //$img = explode('Profile_', $userInfo_action['profile_Pic']);
                                                $img = explode('Profile_', $videoRecord['detail_text']);
                                                $fullLink = 'media/tubers/' . $img[1];
                                        }
                                        break;
                                case SOCIAL_ENTITY_CHANNEL:
                                        $action="$staticaction their channel";	
                                        $channel_array=$videoRecord;
                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                        $from_id = intval($channel_array['owner_id']);						
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                $target_name = $channel_array['channel_name'];
                                                $target_id = $channel_array['id'];
                                                $action="$staticaction [TargetName]'s channel";													
                                        }					
                                        break;
                                case SOCIAL_ENTITY_NEWS:
                                        $action="$staticaction their news";
                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                        $from_id = intval($channel_array['owner_id']);						
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                $action="$staticaction [Owner]'s news"; /*%s replaced by [Owner]'s*/
                                                $owner_name = $channel_array['channel_name'];
                                                $owner_id=$videoRecord['channelid'];
                                        }
                                        break;
                                case SOCIAL_ENTITY_BROCHURE:
                                        $action="$staticaction their brochure";
                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                        $from_id = intval($channel_array['owner_id']);		
                                        $fullLink = ($videoRecord['photo']) ? 'media/channel/' . $videoRecord['channelid'] . '/brochure/thumb/' . $videoRecord['photo'] : 'media/images/channel/brochure-cover-phot.jpg';
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                $action="$staticaction [Owner]'s brochure [TargetName]";   /*%s replaced by [Owner]'s*/
                                                $target_name = $videoRecord['name'];
                                                $target_id = $videoRecord['id'];
                                                $owner_name = $channel_array['channel_name'];
                                                $owner_id=$videoRecord['channelid'];
                                        }
                                        break;
                                case SOCIAL_ENTITY_EVENTS:
                                        $target_name = $videoRecord['name'];
                                        $target_id = $videoRecord['id'];
                                        $action="$staticaction their event [TargetName]";
                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                        $from_id = intval($channel_array['owner_id']);						
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                $action="$staticaction [Owner]'s event [TargetName]";	/*first %s replaced by [Owner]'s and second %s replaced by [TargetName] */
                                                $owner_id=$videoRecord['channelid'];
                                                $owner_name=$channel_array['channel_name'];
                                
                                        }
                                        break;
                                case SOCIAL_ENTITY_CHANNEL_SLOGAN:
                                        $action="$staticaction their slogan";	
                                        $channelDetailArray=GetChannelDetailInfo($entity_id);
                                        $channel_array=channelGetInfo($channelDetailArray['channelid']);
                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                        $from_id = intval($channel_array['owner_id']);						
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                $action="$staticaction [Owner]'s slogan";  /*%s replaced by [Owner]'s by mukesh*/		
                                                $owner_id=$from_id;
                                                $owner_name=$channel_array['channel_name'];
                                        }
                                        break;
                                case SOCIAL_ENTITY_CHANNEL_INFO:
                                        $action="$staticaction their info";	
                                        $channelDetailArray=GetChannelDetailInfo($entity_id);
                                        $channel_array=channelGetInfo($channelDetailArray['channelid']);
                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                        $from_id = intval($channel_array['owner_id']);						
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                $action="$staticaction [Owner]'s info";	/*%s replaced by [Owner]'s by mukesh*/	
                                                $owner_id=$from_id;
                                                $owner_name=$channel_array['channel_name'];
                                        }
                                        break;
                                case SOCIAL_ENTITY_CHANNEL_COVER:
                                        $action="$staticaction their cover photo";	
                                        $channelDetailArray=GetChannelDetailInfo($entity_id);
                                        $channel_array=channelGetInfo($channelDetailArray['channelid']);	
                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                        $from_id = intval($channel_array['owner_id']);	
                                        $target_name = $channel_array['channel_name'];
                                        $target_id = $channel_array['id'];
                                        $fullLink = 'media/channel/' . $channel_array['id'] . '/thumb/' . $channel_array['header'];
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                $action="$staticaction [TargetName]'s cover photo";													
                                        }
                                        break;
                                case SOCIAL_ENTITY_CHANNEL_PROFILE:
                                        $action="$staticaction their profile image";	
                                        $channelDetailArray=GetChannelDetailInfo($entity_id);
                                        $channel_array=channelGetInfo($channelDetailArray['channelid']);
                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                        $from_id = intval($channel_array['owner_id']);					
                                        if($from_id != $action_profile['id'] ){
                                            $userInfo_action = getUserInfo($from_id);
                                            $action_array=array();
                                            $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                            $action="$staticaction [Owner]'s profile image";	
                                            $owner_id=$channel_array['id'];
                                            $owner_name = $channel_array['channel_name'];
                                            $fullLink = 'media/channel/' . $channel_id . '/thumb/' . $channel_array['logo'];
                                        }
                                        break;
                                case SOCIAL_ENTITY_HOTEL_REVIEWS:  //$target_name='mukesh4';break;
                                        $item_data = getHotelInfo($vfeeditem['media_row']['hotel_id']);
                                        $target_name = $item_data['hotelName'];
                                        $target_id = $item_data['id'];
                                        $from_id = intval($videoRecord['user_id']);
                                        if($action_row_count>=1){
                                            $action="$staticaction the review";
                                        }else{ 
                                            $action="$staticaction $gender own review";
                                        }  
                                        if($from_id != $action_profile['id'] ){                                        
                                            $userInfo_action = getUserInfo($from_id);	
                                            $uslnk= userProfileLink($userInfo_action);
                                            $action="$staticaction your review";	
                                            if( $userInfo_action['id'] != $userId ){
                                                $action="$staticaction [Owner]'s review";	
                                                $action_array=array();
                                                $action_array[]= '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                $owner_name = returnUserDisplayName($userInfo_action);
                                                $owner_id = $from_id;
                                            }
                                        }
                                break;        
                                case SOCIAL_ENTITY_RESTAURANT_REVIEWS:
                                        $rest_data = getRestaurantInfo($videoRecord['restaurant_id']);
                                        $target_name = $rest_data['name'];
                                        $target_id = $rest_data['id'];
                                        if($action_row_count>=1){
                                        $action="$staticaction the review";
                                    }else{ 
                                        $action="$staticaction $gender own review";
                                    }  
                                    $from_id = intval($videoRecord['user_id']);
                                    if($from_id != $action_profile['id'] ){                                        
                                        $userInfo_action = getUserInfo($from_id);	
                                        $uslnk= userProfileLink($userInfo_action);
                                        $action="$staticaction your review";	
                                        if( $userInfo_action['id'] != $userId ){
                                            $action="$staticaction [Owner]'s review";	
                                            $action_array=array();
                                            $action_array[]= '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                            $owner_name = returnUserDisplayName($userInfo_action);
                                            $owner_id = $from_id;
                                        }
                                    }
                                break;    
                                case SOCIAL_ENTITY_LANDMARK_REVIEWS:
                                        $item_data = getPoiInfo($vfeeditem['media_row']['poi_id']);
                                        $target_name = $item_data['name'];
                                        $target_id = $item_data['id'];
                                    if($action_row_count>=1){
                                        $action="$staticaction the review";
                                    }else{ 
                                        $action="$staticaction $gender own review";
                                    }  
                                    $from_id = intval($videoRecord['user_id']);
                                    if($from_id != $action_profile['id'] ){                                        
                                        $userInfo_action = getUserInfo($from_id);	
                                        $uslnk= userProfileLink($userInfo_action);
                                        $action="$staticaction your review";	
                                        if( $userInfo_action['id'] != $userId ){
                                            $action="$staticaction [Owner]'s review";	
                                            $action_array=array();
                                            $action_array[]= '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                            $owner_name = returnUserDisplayName($userInfo_action);
                                            $owner_id = $from_id;
                                        }
                                    }
                                break;
                                case SOCIAL_ENTITY_AIRPORT_REVIEWS:
                                        $item_data = getAirportInfo($vfeeditem['media_row']['id']);
                                        $target_name = $item_data['name'];
                                        $target_id = $item_data['id'];
                                    if($action_row_count>=1){
                                        $action="$staticaction the review";
                                    }else{ 
                                        $action="$staticaction $gender own review";
                                    }  
                                    $from_id = intval($videoRecord['user_id']);
                                    if($from_id != $action_profile['id'] ){                                        
                                        $userInfo_action = getUserInfo($from_id);	
                                        $uslnk= userProfileLink($userInfo_action);
                                        $action="$staticaction your review";	
                                        if( $userInfo_action['id'] != $userId ){
                                            $action="$staticaction [Owner]'s review";	
                                            $action_array=array();
                                            $action_array[]= '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                            $owner_name = returnUserDisplayName($userInfo_action);
                                            $owner_id = $from_id;
                                        }
                                    }
                                break;    
                                case SOCIAL_ENTITY_POST:
                                    //$fullLink = getPostThumbPath($vfeeditem['media_row']);
                                    if ($videoRecord != "") { 
                                        if($videoRecord['media_file'] != ''){
                                            if ($videoRecord['is_video'] == 0) {// echo 'if';die;
                                                //$repath = relativevideoReturnPostPath($videoRecord);
                                                if (intval($videoRecord['channel_id']) == 0 && intval($videoRecord['from_id']) != 0) {
                                                    global $CONFIG;
                                                    $videoPath = $CONFIG['video']['uploadPath'];
                                                    $rpath = $videoRecord['relativepath'];
                                                    $repath= $videoPath . 'posts/' . $rpath;
                                                } else {
                                                    $repath='media/channel/' . $vinfo['channel_id'] . '/posts/';
                                                }
                                                $repath .=  $videoRecord['media_file'];

                                            }else{  
                                                $cod1 = explode('.',  $videoRecord['media_file']);
                                                $cod2 = explode('_', $cod1[0]);
                                                $videoCode = $cod2[1];
                                                $repath = relativevideoGetPostPath($videoRecord);
                                                $videoCode = 'small_postThumb' . $videoCode;
                                                $picthumb_img = getVideoThumbnail_Posts($videoCode, '../../'.$repath, 0);
                                                $picthumb_img = explode('../../', $picthumb_img);
                                                $repath =$picthumb_img[1];
                                            }
                                        }else{
                                            $repath='';
                                        }
                                    }
                                    $fullLink=checkfile_exist($repath);  
                                    if($action_row_count>=1){
                                        $action="$staticaction the post";
                                    }else{
                                        $action="$staticaction $gender own post";
                                    }  
                                    if( intval($videoRecord['channel_id']) !=0 ){
                                            $action = "$staticaction their post";
                                    }
                                    $from_id = intval($videoRecord['from_id']);
                                    if($from_id==0) $from_id = intval($videoRecord['user_id']);

                                    if($from_id != $action_profile['id'] ){
                                        if( intval($videoRecord['channel_id']) !=0 ){
                                                $channel_array=channelGetInfo($videoRecord['channel_id']);
                                                $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                                $from_id = intval($channel_array['owner_id']);
                                                $userInfo_action = getUserInfo($from_id);										
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                $action="$staticaction [Owner]'s post";	
                                                $owner_name = $channel_array['channel_name'];
                                                $owner_id=$videoRecord['channelid'];
                                        }else{
                                                $userInfo_action = getUserInfo($from_id);		
                                                $uslnk= userProfileLink($userInfo_action);
                                                $action="$staticaction your post";
                                                if( $userInfo_action['id'] != $userId ){
                                                    $action_array=array();
                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'â€™s</span></a>';
                                                    $action="$staticaction [Owner]'s post";
                                                    $owner_name = returnUserDisplayName($userInfo_action);
                                                    $owner_id=$from_id;
                                                }

                                        }
                                    }
                                break;
                                case SOCIAL_ENTITY_LOCATION:
                                        break;
                                case SOCIAL_ENTITY_USER_EVENTS:
                                        $target_name = $videoRecord['name'];
                                        $target_id = $videoRecord['id'];
                                        $description = htmlEntityDecode($videoRecord['description']);
                                        $title = $videoRecord['name'];        
                                        if($action_row_count>=1){
                                            $action="$staticaction the event [TargetName]"; /*[TargetName] in place of %s*/
                                        }else{
                                            $action="$staticaction $gender own event [TargetName]";/*[TargetName] in place of %s*/
                                        }  
                                        $from_id = intval($videoRecord['user_id']);						
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);		
                                                $uslnk= userProfileLink($userInfo_action);							
                                                $action="$staticaction your event [TargetName]";/*[TargetName] in place of %s*/
                                                if( $userInfo_action['id'] != $userId ){
                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                    //$action="$staticaction %s 's event %s";mukesh
                                                    $action="$staticaction [Owner]'s event [TargetName]";/*[TargetName] in place of %s*/
                                                    $owner_id=$from_id;
                                                    $owner_name=returnUserDisplayName($userInfo_action);
                                                }													
                                        }						
                                        break;
                                case SOCIAL_ENTITY_COMMENT:
                                        break;
                                case SOCIAL_ENTITY_FLASH:
                                        break;
                          /*    case SOCIAL_ENTITY_JOURNAL:
                                        if($action_row_count>=1){
                                            $action="$staticaction the journal [TargetName]";
                                        }else{
                                            $action="$staticaction $gender own journal [TargetName]";
                                        }  
                                        $from_id = intval($videoRecord['user_id']);
                                        $target_name = $videoRecord['name'];
                                        $target_id = $videoRecord['id'];
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);		
                                                $uslnk= userProfileLink($userInfo_action);
                                                $action="$staticaction your journal [Owner]";
                                                if( $userInfo_action['id'] != $userId ){
                                                        $action_array=array();
                                                        $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                        $action="$staticaction [Owner]'s journal [TargetName]";  
                                                        $owner_id=$from_id;
                                                        $owner_name= returnUserDisplayName($userInfo_action);
                                                }

                                        }
                                        break;
                                    */     
                                case SOCIAL_ENTITY_SHARE:
                                        break;
                                case SOCIAL_ENTITY_USER:
                                        break;
                        }                        
                        if($action_row_count>1){
                            $stoth ='';
                            $i=0;
                            foreach($action_row_other as $action_row_otherIT){
                                if( intval($action_row_otherIT['channel_id']) !=0 ){
                                    $channel_arraygroup=channelGetInfo($action_row_otherIT['channel_id']);
                                    if($channel_arraygroup['owner_id']==$action_row_otherIT['user_id']){
                                        $uslnkoth = ReturnLink('channel/'.$channel_arraygroup['channel_url']);
                                        $uslnkothname= htmlEntityDecode($channel_arraygroup['channel_name']);                                    
                                    }else{
                                        $action_profiletb = getUserInfo($action_row_otherIT['user_id']);
                                        $uslnkoth= userProfileLink($action_profiletb);
                                        $uslnkothname= returnUserDisplayName($action_profiletb);
                                    }
                                }else{
                                    $action_profiletb = getUserInfo($action_row_otherIT['user_id']);
                                    $uslnkoth= userProfileLink($action_profiletb);
                                    $uslnkothname= returnUserDisplayName($action_profiletb);
                                }

                                $stoth .= '<a class="tt_otherlink_a" href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>';
                                $i++;
                                if($i>=10 && $action_row_count>10){
                                    $stoth .= '<a class="tt_otherlink_a"><strong>'. vsprintf(ngettext("and %s other...", "and %s others...", ($action_row_count - $i) ) , array( $action_row_count - $i ) ) .'</strong></a>';
                                    break;
                                }
                            }
                            array_unshift($action_array, '</strong><div class="tt_otherlink_over"><div class="tt_otherlink_overin"><div class="tt_otherlink_over_arrow"></div>'.$stoth.'</div></div></span>');
                            array_unshift($action_array, '<span class="tt_otherlink_span"><strong>'.$action_row_count);
                        }else if($action_row_count==1){
                            if( intval($action_row_other['channel_id']) !=0 ){
                                    $channel_arraygroup=channelGetInfo($action_row_other['channel_id']);
                                    if($channel_arraygroup['owner_id']==$action_row_other['user_id']){
                                        $uslnkoth = ReturnLink('channel/'.$channel_arraygroup['channel_url']);
                                        $uslnkothname= htmlEntityDecode($channel_arraygroup['channel_name']);                                    
                                    }else{
                                        $action_profiletb = getUserInfo($action_row_other['user_id']);
                                        $uslnkoth= userProfileLink($action_profiletb);
                                        $uslnkothname= returnUserDisplayName($action_profiletb);
                                    }
                                $other = $uslnkothname;
                                $otherId = $action_row_other['user_id'];     
                            }else{
                                $action_profiletb = getUserInfo($action_row_other['user_id']);
                                $uslnkoth= userProfileLink($action_profiletb);
                                $uslnkothname= returnUserDisplayName($action_profiletb);
                                $other = $uslnkothname;
                                $otherId = $action_row_other['user_id'];
                            }                         
                            array_unshift($action_array, '<a href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>');
                        }
                        break;
                case SOCIAL_ACTION_INVITE: 
                        $userInfo_action = getUserInfo($action_row['from_user']);
                        $gender = 'his';
                        if($userInfo_action['gender']=='F'){
                            $gender = 'her';
                        }
                        $staticaction = "[ActionOwner] invited";                        
                        if($action_row_count>1){
                            $staticaction = "[ActionOwner] and [Others] invited";
                        }else if($action_row_count==1){                            
                            $staticaction = "[ActionOwner] and [Other] invited";
                        }
                        if( $entity_type == SOCIAL_ENTITY_USER_EVENTS ) { 
                                $target_name = htmlEntityDecode($vfeeditem['media_row']['name']);
                                $target_id = $vfeeditem['media_row']['id'];
                                if($action_row_count>=1){
                                    $action = "$staticaction you to join the event [TargetName]";  
                                }else{
                                    $action= "$staticaction you to join $gender own event [TargetName]"; /* add->event [TargetName]  /mukesh/*/ 
                                }  
                                $feed_user_id = intval($videoRecord['user_id']);
                                $userInfo_action = getUserInfo($feed_user_id);	
                                $uslnkothname= returnUserDisplayName($userInfo_action);
                                $username= $uslnkothname;
//                                $pic = $userInfo_action['profile_Pic'];
//                                $loop_ret_row['user_profile_pic']= 'media/tubers/' . $pic;
                        }else if( $entity_type == SOCIAL_ENTITY_EVENTS ) {
                                $target_name = htmlEntityDecode($vfeeditem['media_row']['name']);
                                $target_id = $vfeeditem['media_row']['id'];
                                $channel_array = channelGetInfo($vfeeditem['media_row']['channelid']);
                                $action = "$staticaction you to join their own event [TargetName]";							
                        }else{
                                if($action_row_count>=1){$action_text .= '';
                                    $action= "$staticaction you to connect to the channel [TargetName]"; /* [TargetName] in place of %s /mukesh/*/
                                }else{
                                    $action= "$staticaction you to connect to $gender own channel [TargetName]"; /* [TargetName] in place of %s /mukesh/*/
                                }
                                $target_name = htmlEntityDecode($vfeeditem['media_row']['channel_name']);
                                $target_id = $vfeeditem['media_row']['id'];
                        }
                        $overhead_text = htmlEntityDecode($action_row['msg']);
                        if(isset($vfeeditem['media_row']['user_id']) && $vfeeditem['media_row']['user_id'] == $user_id ){
                                $channel_array ='';
                                if( $entity_type == SOCIAL_ENTITY_USER_EVENTS ) {
                                        $action= "$staticaction people to $gender own event [TargetName]";							
                                }else if( $entity_type == SOCIAL_ENTITY_EVENTS ) {
                                        $action= "$staticaction people to their event [TargetName]";							
                                }else {
                                        $action= "$staticaction people to connect to their channel [TargetName]";
                                }
//                                $target_name = htmlEntityDecode($vfeeditem['media_row']['channel_name']);
//                                $target_id = $vfeeditem['media_row']['id'];
                        
                        }else if( $action_row['from_user'] != $videoRecord['user_id'] ){ //echo $action_row['from_user'].'_'.$user_id;die;
                                $channel_array ='';
                                $owner_id=$user_id;
                                $userInfo_action_who = getUserInfo($owner_id);					
                                $uslnk_who = userProfileLink($userInfo_action_who);
                                if( $entity_type == SOCIAL_ENTITY_USER_EVENTS ) {
                                        $action_array[] = '<a href="' . $uslnk_who . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action_who,array('max_length'=>32)).'</span></a>';
                                        $action= "$staticaction people to join [Owner]'s event [TargetName]";
                                        $owner_id=$user_id;
                                        $owner_name= returnUserDisplayName($userInfo_action_who,array('max_length'=>32));
                                        $feed_user_id = intval($videoRecord['user_id']);
                                        $userInfo_action = getUserInfo($feed_user_id);	
                                        $uslnkothname= returnUserDisplayName($userInfo_action);
                                        $username= $uslnkothname;
                                        //echo $username;die;
                                }else if( $entity_type == SOCIAL_ENTITY_EVENTS ) {
                                        $channel_array_action = channelGetInfo($vfeeditem['media_row']['channelid']);
                                        $channel_name = htmlEntityDecode($channel_array_action['channel_name']);
                                        if(strlen($channel_name) > 32){
                                           $channel_name = substr($channel_name,0,32).' ...';
                                        }
                                        $action_array[] = '<a href="' .ReturnLink('channel/' . $channel_array_action['channel_url'])  . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.$channel_name.'</span></a>';
                                        $action= "$staticaction people to join [Owner]'s event [TargetName]";
                                        $owner_id=$vfeeditem['media_row']['channelid'];
                                        $owner_name=$channel_name;
                                        $feed_user_id = $actionRecord['from_user'];
                                        $userInfo_action = getUserInfo($feed_user_id);	
                                        $uslnkothname= returnUserDisplayName($userInfo_action);
                                        $username= $uslnkothname;
                                }else {
                                        $channel_name = htmlEntityDecode($vfeeditem['media_row']['channel_name']);
                                        if(strlen($channel_name) > 32){
                                           $channel_name = substr($channel_name,0,32).' ...';
                                        }
                                        $action_array[] = '<a href="' .ReturnLink('channel/' . $media_row['channel_url'])  . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.$channel_name.'</span></a>';
                                        $action= "$staticaction people to connect to [Owner]'s channel";
                                        $owner_id=$vfeeditem['media_row']['channelid'];
                                        $owner_name=$channel_name;
                                }
                        }
                        if($action_row_count>1){
                            $stoth ='';
                            $i=0;
                            foreach($action_row_other as $action_row_otherIT){
                                if( intval($action_row_otherIT['channel_id']) !=0 ){
                                        $channel_arraygroup=channelGetInfo($action_row_otherIT['channel_id']);
                                        if($channel_arraygroup['owner_id']==$action_row_otherIT['action_row']['from_user']){
                                            $uslnkoth = ReturnLink('channel/'.$channel_arraygroup['channel_url']);
                                            $uslnkothname= htmlEntityDecode($channel_arraygroup['channel_name']);                                    
                                        }else{
                                            $action_profiletb = getUserInfo($action_row_otherIT['action_row']['from_user']);
                                            $uslnkoth= userProfileLink($action_profiletb);
                                            $uslnkothname= returnUserDisplayName($action_profiletb);
                                        }
                                }else{
                                    $action_profiletb = getUserInfo($action_row_otherIT['action_row']['from_user']);
                                    $uslnkoth= userProfileLink($action_profiletb);
                                    $uslnkothname= returnUserDisplayName($action_profiletb);
                                }

                                $stoth .= '<a class="tt_otherlink_a" href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>';
                                $i++;
                                if($i>=10 && $action_row_count>10){
                                    $stoth .= '<a class="tt_otherlink_a"><strong>'. vsprintf(ngettext("and %s other...", "and %s others...", ($action_row_count - $i) ) , array( $action_row_count - $i ) ) .'</strong></a>';
                                    break;
                                }
                            }
                            array_unshift($action_array, '</strong><div class="tt_otherlink_over"><div class="tt_otherlink_overin"><div class="tt_otherlink_over_arrow"></div>'.$stoth.'</div></div></span>');
                            array_unshift($action_array, '<span class="tt_otherlink_span" data-id="'.$id.'"><strong>'.$action_row_count);
                        }else if($action_row_count==1){
                            if( intval($action_row_other['channel_id']) !=0 ){
                                    $channel_arraygroup=channelGetInfo($action_row_other['channel_id']);
                                    if($channel_arraygroup['owner_id']==$action_row_other['action_row']['from_user']){
                                        $uslnkoth = ReturnLink('channel/'.$channel_arraygroup['channel_url']);
                                        $uslnkothname= htmlEntityDecode($channel_arraygroup['channel_name']);                                    
                                    }else{
                                        $action_profiletb = getUserInfo($action_row_other['action_row']['from_user']);
                                        $uslnkoth= userProfileLink($action_profiletb);
                                        $uslnkothname= returnUserDisplayName($action_profiletb);
                                    }
                                $other = $uslnkothname;
                                $otherId = $action_row_other['user_id'];
                            }else{
                                $action_profiletb = getUserInfo($action_row_other['action_row']['from_user']);
                                $uslnkoth= userProfileLink($action_profiletb);
                                $uslnkothname= returnUserDisplayName($action_profiletb);
                                $other = $uslnkothname;
                                $otherId = $action_row_other['user_id'];
                            }                         
                            array_unshift($action_array, '<a href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>');
                        }
                break;                
                case SOCIAL_ACTION_SHARE:
                        $who = getUserInfo($actionRecord['from_user']);						
                        $action_profile = $who;
                        $feed_user_id = $actionRecord['from_user'];
                        $username = returnUserDisplayName($action_profile); 
                        $overhead_text = htmlEntityDecode($actionRecord['msg']);
                        $overhead_text = (strlen($overhead_text) > 153) ? substr($overhead_text,0,150).'...' : $overhead_text;
                        $staticaction = "[ActionOwner] shared";                        
                        if($action_row_count>1){
                            $staticaction = "[ActionOwner] and [Others] shared";
                        }else if($action_row_count==1){                            
                            $staticaction = "[ActionOwner] and [Other] shared";
                        }                        
                        switch($entity_type){
                                case SOCIAL_ENTITY_MEDIA:
                                        if($action_row_count>=1){
                                            $action = "$staticaction the $video_photo [TargetName]";
                                        }else{
                                            $action = "$staticaction $gender own $video_photo [TargetName]"; 
                                        }  
                                        if( intval($videoRecord['channelid']) !=0 ){
                                                $action = "$staticaction their $video_photo [TargetName]";
                                        }
                                        $title = $videoRecord['title'];
                                        $description = $videoRecord['description'];
                                        $description = htmlEntityDecode($description);
                                        $from_id = intval($videoRecord['userid']);
                                        $target_name = stripslashes($videoRecord['title']);
                                        $target_id = $videoRecord['id'];
//                                      $fullLink = $videoRecord['fullpath'] . $videoRecord['name'];
                                        //$fullLink = photoReturnSrcSmall($videoRecord);
                                         if ($videoRecord['image_video'] == "v") {
                                            $thumb_src_link = substr(getVideoThumbnail($videoRecord['id'], '../../'.$path . $videoRecord['fullpath'], 0), strlen($path));
                                            $thumb_src = explode('../../', $thumb_src_link);
                                            $thumb_src =$thumb_src[1];
                                            if(!$thumb_src) { $thumb_src = ''; }
                                            $type = 'video';
                                        } else {
                                            $thumb_src = $videoRecord['fullpath'] . $videoRecord['name'];
                                            $type = 'image';
                                        }
                                        $fullLink=checkfile_exist($thumb_src);
                                        if($from_id != $action_profile['id'] ){
                                                if( intval($videoRecord['channelid']) !=0 ){
                                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
//                                                                $action="$staticaction %s 's ";
                                                        $action="$staticaction [Owner]'s $video_photo [TargetName]";
                                                        $owner_name = $channel_array['channel_name'];
                                                        $owner_id=$videoRecord['channelid'];
                                                }else{
                                                        $userInfo_action = getUserInfo($from_id);	
                                                        $uslnk= userProfileLink($userInfo_action);
                                                        $action_array=array();
//                                                                $action="$staticaction your ";
                                                        $action="$staticaction your $video_photo [TargetName]";
                                                        if( $userInfo_action['id'] != $userId ){
                                                                $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
//                                                                        $action="$staticaction %s 's ";
                                                                $owner_name = returnUserDisplayName($userInfo_action);
                                                                $action="$staticaction [Owner]'s $video_photo [TargetName]";
                                                                $owner_name = returnUserDisplayName($userInfo_action);
                                                                $owner_id = $from_id;
                                                        }
                                                }
                                        }
                                        break;
//                                case SOCIAL_ENTITY_WEBCAM:
//                                        $action = "$staticaction ";
//                                        $description = $videoRecord['description'];
//                                        $description = htmlEntityDecode($description);
//                                        break;
                                case SOCIAL_ENTITY_ALBUM:
                                        /*to show full link of album*/
                                        //$fullLink = photoReturnSrcSmall($videoRecord);
                                         if ($videoRecord['image_video'] == "v") {
                                            $thumb_src_link = substr(getVideoThumbnail($videoRecord['id'], '../../'.$path . $videoRecord['fullpath'], 0), strlen($path));
                                            $thumb_src = explode('../../', $thumb_src_link);
                                            $thumb_src =$thumb_src[1];
                                            if(!$thumb_src) { $thumb_src = ''; }
                                            $type = 'video';
                                        } else {
                                            $thumb_src = $videoRecord['fullpath'] . $videoRecord['name'];
                                            $type = 'image';
                                        }
                                        $fullLink=checkfile_exist($thumb_src);
                                        $target_id = $entity_id; /*here entity_is album_id*/
                                        $album_info = userCatalogGet($entity_id);
                                        $target_name = $album_info['catalog_name'];
                                        /*to show other images of album mukesh*/
                                         $srch_options = array(
                                            'limit' => 3,
                                            'catalog_id' => $target_id,
                                            'orderby' => 'rand',
                                            'type' => 'a',
                                            'order' => 'd'
                                        );
                                        $photos1 = mediaSearch($srch_options);
                                        $other_img = array();
                                        foreach($photos1 as $photo){
//                                            $other_img[]=array(
//                                                'id'=>$photo['id'],
//                                                'path'=>photoReturnThumbSrc($photo),
//                                                'type'=>$photo['image_video'] == "v" ? 'video' : 'image'
//                                            );
                                            if ($photo['image_video'] == "v") {
                                                $thumb_src_link = substr(getVideoThumbnail($photo['id'], '../../'.$path . $photo['fullpath'], 0), strlen($path));
                                                $thumb_src = explode('../../', $thumb_src_link);
                                                $thumb_src =$thumb_src[1];
                                                $type = 'video';
                                            } else {
                                                $thumb_src = $photo['fullpath'] . $photo['name'];
                                                $type = 'image';
                                            }
                                            $other_img[] = array(
                                                'id'=> $photo['id'], 
                                                'path' => $thumb_src,
                                                'type'=>$type,
                                            ); 
                                        }
                                        /*end*/
                                        $title = $videoRecord['title'];
                                        $description = $videoRecord['description'];
                                        $description = htmlEntityDecode($description);
                                        if($action_row_count>=1){
                                            $action = "$staticaction the album [TargetName]";
                                        }else{
                                            $action = "$staticaction $gender own album [TargetName]";
                                        }  
                                        if( intval($videoRecord['channelid']) !=0 ){
                                            $action = "$staticaction their album [TargetName]";
                                        }
                                        
                                        $from_id = intval($videoRecord['userid']);
                                        if($from_id != $action_profile['id'] ){
                                                if( intval($videoRecord['channelid']) !=0 ){
                                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                        $action="$staticaction [Owner]'s album [TargetName]";
                                                        $owner_name=$channel_array['channel_name'];
                                                        $owner_id=$videoRecord['channelid'];
                                                }else{
                                                        $userInfo_action = getUserInfo($from_id);	
                                                        $uslnk= userProfileLink($userInfo_action);
                                                        $action="$staticaction your album [TargetName]";
                                                        if( $userInfo_action['id'] != $userId ){
                                                            $action_array=array();	
                                                            $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                            $action="$staticaction [Owner]'s album [TargetName]";
                                                            $owner_name= returnUserDisplayName($userInfo_action);
                                                            $owner_id = $from_id;
                                                        }
                                                }
                                        }
                                        break;
                                case SOCIAL_ENTITY_HOTEL_REVIEWS: 
                                case SOCIAL_ENTITY_RESTAURANT_REVIEWS:
                                case SOCIAL_ENTITY_LANDMARK_REVIEWS:
                                case SOCIAL_ENTITY_AIRPORT_REVIEWS:  
                                    if($action_row_count>=1){
                                        $action="$staticaction the review";
                                    }else{
                                        $action="$staticaction $gender own review";
                                    }  
                                    $from_id = intval($videoRecord['user_id']);
                                    if($from_id != $action_profile['id'] ){                                        
                                        $userInfo_action = getUserInfo($from_id);	
                                        $uslnk= userProfileLink($userInfo_action);
                                        $action="$staticaction your review";	
                                        if( $userInfo_action['id'] != $userId ){
                                            $action="$staticaction [Owner]'s review";	
                                            $action_array=array();
                                            $action_array[]= '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                            $owner_name= returnUserDisplayName($userInfo_action);
                                            $owner_id=$from_id;
                                        }
                                    }
                                break;
                                case SOCIAL_ENTITY_POST:
                                    //$fullLink = getPostThumbPath($vfeeditem['media_row']);
                                    if ($videoRecord != "") { 
                                        if($videoRecord['media_file'] != ''){
                                            if ($videoRecord['is_video'] == 0) {// echo 'if';die;
                                                //$repath = relativevideoReturnPostPath($videoRecord);
                                                if (intval($videoRecord['channel_id']) == 0 && intval($videoRecord['from_id']) != 0) {
                                                    global $CONFIG;
                                                    $videoPath = $CONFIG['video']['uploadPath'];
                                                    $rpath = $videoRecord['relativepath'];
                                                    $repath= $videoPath . 'posts/' . $rpath;
                                                } else {
                                                    $repath='media/channel/' . $vinfo['channel_id'] . '/posts/';
                                                }
                                                $repath .=  $videoRecord['media_file'];

                                            }else{  
                                                $cod1 = explode('.',  $videoRecord['media_file']);
                                                $cod2 = explode('_', $cod1[0]);
                                                $videoCode = $cod2[1];
                                                $repath = relativevideoGetPostPath($videoRecord);
                                                $videoCode = 'small_postThumb' . $videoCode;
                                                $picthumb_img = getVideoThumbnail_Posts($videoCode, '../../'.$repath, 0);
                                                $picthumb_img = explode('../../', $picthumb_img);
                                                $repath =$picthumb_img[1];
                                            }
                                        }else{
                                            $repath='';
                                        }
                                    }
                                    $fullLink=checkfile_exist($repath);  
                                    if($action_row_count>=1){
                                        $action="$staticaction the post";
                                    }else{
                                        $action="$staticaction $gender own post";
                                    }  
                                    if( intval($videoRecord['channel_id']) !=0 ){
                                            $action = "$staticaction their post";
                                    }
                                    $from_id = intval($videoRecord['from_id']);
                                    if($from_id==0) $from_id = intval($videoRecord['user_id']);

                                    if($from_id != $action_profile['id'] ){
                                            if( intval($videoRecord['channel_id']) !=0 ){
                                                    $channel_array=channelGetInfo($videoRecord['channel_id']);
                                                    $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                                    $from_id = intval($channel_array['owner_id']);
                                                    $userInfo_action = getUserInfo($from_id);
                                                    $action_array=array();
                                                    $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                    $action="$staticaction [Owner]'s post"; 	/*%s replaced by [Owner] by mukesh*/
                                                    $owner_name=$channel_array['channel_name'];
                                                    $owner_id = $videoRecord['channelid'];
                                            }else{
                                                    $userInfo_action = getUserInfo($from_id);	
                                                    $uslnk= userProfileLink($userInfo_action);
                                                    $action="$staticaction your post";
                                                    if( $userInfo_action['id'] != $userId ){
                                                        $action_array=array();	
                                                        $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                        $action="$staticaction [Owner]'s post"; /*%s replaced by [Owner] by mukesh*/
                                                        $owner_name=returnUserDisplayName($userInfo_action);
                                                        $owner_id=$from_id;
                                                    }

                                            }
                                    }
                                    break;
                                case SOCIAL_ENTITY_VISITED_PLACES:
                                        if($action_row_count>=1){
                                            $action="$staticaction the visited location [TargetName]"; /*%s replaced by [TargetName] by mukesh*/
                                        }else{
                                            $action="$staticaction $gender own visited location [TargetName]"; /*%s replaced by [TargetName] by mukesh*/
                                        }  
                                        if($entity_owner != $user_id ){                                                    
                                            $userInfo_action = getUserInfo($entity_owner);		
                                            $uslnk= userProfileLink($userInfo_action);                                                    
                                            $action_array = array();
                                            $action="$staticaction your visited location [TargetName]"; /*%s is replaced by [TargetName] by mukesh*/
                                            if( $userInfo_action['id'] != $userId ){
                                                $action_array =array();
                                                $action_array[]='<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                $action="$staticaction [Owner]'s visited location [TargetName]";  /*first %s replaced by [Owner] and second %s replaced [TargetName] by mukesh*/
                                                $owner_name=returnUserDisplayName($userInfo_action);
                                                $owner_id=$from_id;
                                            }													
                                        }						
                                        break;
                                case SOCIAL_ENTITY_USER_EVENTS:
                                        $target_name = $videoRecord['name'];
                                        $target_id = $videoRecord['id'];
                                        if($action_row_count>=1){
                                            $action="$staticaction the event [TargetName]"; /*%s replaced by [TargetName] by mukesh*/
                                        }else{
                                            $action="$staticaction $gender own event [TargetName]"; /*%s replaced by [TargetName] by mukesh*/
                                        }  
                                        $from_id = intval($videoRecord['user_id']);						
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);		
                                                $uslnk= userProfileLink($userInfo_action);
                                                $action="$staticaction your event [TargetName]";  /*%s replaced by [TargetName] by mukesh*/
                                                if( $userInfo_action['id'] != $userId ){
                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                    $action="$staticaction [Owner]'s event [TargetName]"; /*first %s replaced by [Owner] and second %s replaced [TargetName] by mukesh*/
                                                    $owner_name=returnUserDisplayName($userInfo_action);
                                                    $owner_id=$from_id;
                                                }													
                                        }						
                                        break;
                                case SOCIAL_ENTITY_CHANNEL:
                                        $action="$staticaction their channel";	
                                        $channel_array=$videoRecord;
                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                        $from_id = intval($channel_array['owner_id']);						
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                $action="$staticaction [TargetName]'s channel";	 /*%s replaced by [TargetName] by mukesh*/
                                                $target_name = $channel_array['channel_name'];
                                                $target_id = $channel_array['id'];
                                        }					
                                        break;
                                case SOCIAL_ENTITY_NEWS:
                                        $action="$staticaction their news";
                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                        $from_id = intval($channel_array['owner_id']);						
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                $action="$staticaction [Owner]'s news";  /*%s replaced by [Owner]'s*/
                                                $owner_name = $channel_array['channel_name'];
                                                $owner_id=$from_id;
                                        }
                                        break;
                                case SOCIAL_ENTITY_BROCHURE:
                                        $action="$staticaction their brochure";
                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                        $from_id = intval($channel_array['owner_id']);	
                                        $fullLink = ($videoRecord['photo']) ? 'media/channel/' . $videoRecord['channelid'] . '/brochure/thumb/' . $videoRecord['photo'] : 'media/images/channel/brochure-cover-phot.jpg';
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                $action="$staticaction [Owner]'s brochure [TargetName]";	/*%s replaced by [Owner]'s*/
                                                $target_name = $videoRecord['name'];
                                                $target_id = $videoRecord['id'];
                                                $owner_name = $channel_array['channel_name'];
                                                $owner_id = $videoRecord['channelid'];
                                        }
                                        break;
                                case SOCIAL_ENTITY_EVENTS:
                                        $target_name = $videoRecord['name'];
                                        $target_id = $videoRecord['id'];
                                        $action="$staticaction their event [TargetName]";
                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                        $from_id = intval($channel_array['owner_id']);						
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                $action="$staticaction [Owner]'s event [TargetName]";	
                                                $owner_name = $channel_array['channel_name'];
                                                $owner_id = $videoRecord['channelid'];
                                        }
                                        break;
                                case SOCIAL_ENTITY_LOCATION:
                                        break;
                                case SOCIAL_ENTITY_COMMENT:
                                        break;
                            /*  case SOCIAL_ENTITY_JOURNAL:
                                        if($action_row_count>=1){
                                            $action="$staticaction the journal [TargetName]"; 
                                        }else{
                                            $action="$staticaction $gender own journal [TargetName]"; 
                                        }  
                                        $from_id = intval($videoRecord['user_id']);
                                        $target_name = $videoRecord['name'];
                                        $target_id = $videoRecord['id'];
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);	
                                                $uslnk= userProfileLink($userInfo_action);
                                                $action="$staticaction your journal [Owner]";
                                                if( $userInfo_action['id'] != $userId ){
                                                    $action_array=array();	
                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                    $action="$staticaction [Owner]'s journal [TargetName]";  
                                                    $owner_id=$from_id;
                                                    $owner_name= returnUserDisplayName($userInfo_action);
                                                }

                                        }
                                        break;
                                    */    
                                case SOCIAL_ENTITY_SHARE:
                                        break;
                                case SOCIAL_ENTITY_USER:
                                        break;
                        }
                        if($action_row_count>1){
                            $stoth ='';
                            $i=0;
                            foreach($action_row_other as $action_row_otherIT){
                                if( intval($action_row_otherIT['channel_id']) !=0 ){
                                        $channel_arraygroup=channelGetInfo($action_row_otherIT['channel_id']);
                                        if($channel_arraygroup['owner_id']==$action_row_otherIT['action_row']['from_user']){
                                            $uslnkoth = ReturnLink('channel/'.$channel_arraygroup['channel_url']);
                                            $uslnkothname= htmlEntityDecode($channel_arraygroup['channel_name']);                                    
                                        }else{
                                            $action_profiletb = getUserInfo($action_row_otherIT['action_row']['from_user']);
                                            $uslnkoth= userProfileLink($action_profiletb);
                                            $uslnkothname= returnUserDisplayName($action_profiletb);
                                        }
                                }else{
                                    $action_profiletb = getUserInfo($action_row_otherIT['action_row']['from_user']);
                                    $uslnkoth= userProfileLink($action_profiletb);
                                    $uslnkothname= returnUserDisplayName($action_profiletb);
                                } 
                        
                                $stoth .= '<a class="tt_otherlink_a" href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>';
                                $i++;
                                if($i>=10 && $action_row_count>10){
                                    $stoth .= '<a class="tt_otherlink_a"><strong>'. vsprintf(ngettext("and %s other...", "and %s others...", ($action_row_count - $i) ) , array( $action_row_count - $i ) ) .'</strong></a>';
                                    break;
                                }
                            }
                            array_unshift($action_array, '</strong><div class="tt_otherlink_over"><div class="tt_otherlink_overin"><div class="tt_otherlink_over_arrow"></div>'.$stoth.'</div></div></span>');
                            array_unshift($action_array, '<span class="tt_otherlink_span"><strong>'.$action_row_count);
                        }else if($action_row_count==1){
                            if( intval($action_row_other['channel_id']) !=0 ){
                                    $channel_arraygroup=channelGetInfo($action_row_other['channel_id']);
                                    if($channel_arraygroup['owner_id']==$action_row_other['action_row']['from_user']){
                                        $uslnkoth = ReturnLink('channel/'.$channel_arraygroup['channel_url']);
                                        $uslnkothname= htmlEntityDecode($channel_arraygroup['channel_name']);                                    
                                    }else{
                                        $action_profiletb = getUserInfo($action_row_other['action_row']['from_user']);
                                        $uslnkoth= userProfileLink($action_profiletb);
                                        $uslnkothname= returnUserDisplayName($action_profiletb);
                                    }
                                    
                                    $other = $uslnkothname;
                                    $otherId = $action_row_other['user_id'];     
                            }else{
                                $action_profiletb = getUserInfo($action_row_other['action_row']['from_user']);
                                $uslnkoth= userProfileLink($action_profiletb);
                                $uslnkothname= returnUserDisplayName($action_profiletb);
                                $other = $uslnkothname;
                                $otherId = $action_row_other['user_id'];     
                            }                         
                            array_unshift($action_array, '<a href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>');
                        }
                        break;
                case SOCIAL_ACTION_REECHOE:  
                    $staticaction = "[ActionOwner] reechoed";                        
                            if($action_row_count>1){
                                $staticaction = "[ActionOwner] and [Others] reechoed";
                            }else if($action_row_count==1){                            
                                $staticaction = "[ActionOwner] and [Other] reechoed";
                            }
                    switch($entity_type){
                        case SOCIAL_ENTITY_FLASH:
                            $action= "[ActionOwner] reechoed your echo";                             
                            $echo_text = $vfeeditem['media_row']['flash_text'];
                            $$fullLink=($videoRecord['vpath']) ? $videoRecord['vpath'] . $videoRecord['pic_file'] : '';
                            break;
                    }
                    break;
                default:
                        $valid = false;
                        break;
        }
        //if(!$valid) continue;
        if( is_null($action) ){
                //echo $vfeeditem['id'] . ' - ' . $vfeeditem['action_type'] . ' - ' . $entity_type;
                //exit;
                //continue;
        }
        if( $vfeeditem['action_type'] == SOCIAL_ACTION_SHARE || $vfeeditem['action_type'] == SOCIAL_ACTION_INVITE || $vfeeditem['action_type'] == SOCIAL_ACTION_SPONSOR ){
//                $action_owner_id = socialActionOwner( $vfeeditem['action_type'] , $action_id , $entity_type );
//                if($action_owner_id == $userId) continue;
        }else if($vfeeditem['action_type'] == SOCIAL_ACTION_RELATION_SUB || $vfeeditem['action_type'] == SOCIAL_ACTION_RELATION_PARENT){
            $current_row = channelGetInfo($feed_user_id);
            if($current_row['owner_id'] == $userId) continue;
        }
        if( $entity_type == SOCIAL_ENTITY_FLASH ) {
                $social_can_share=false;
                $social_can_rate=false;
                $user_can_join_check=false;
        }
        $media_row = $vfeeditem['media_row'];

//        if (!($entity_type == SOCIAL_ENTITY_MEDIA || $entity_type == SOCIAL_ENTITY_ALBUM || $media_row['post_type'] == SOCIAL_POST_TYPE_PHOTO || $media_row['post_type'] == SOCIAL_POST_TYPE_VIDEO)) {
//            $social_can_rate = false;
//        }
//        if (!($entity_type != SOCIAL_ENTITY_CHANNEL_INFO && $media_row['post_type'] != SOCIAL_POST_TYPE_LOCATION && $entity_type != SOCIAL_ENTITY_CHANNEL_SLOGAN && $entity_type != SOCIAL_ENTITY_CHANNEL_PROFILE && $entity_type != SOCIAL_ENTITY_USER_PROFILE && $entity_type != SOCIAL_ENTITY_CHANNEL_COVER)) {
//            $social_can_share = false;
//        }
        if ($action_type == SOCIAL_ACTION_EVENT_CANCEL) {
            $social_can_like = false;
            $social_can_comment = false;
        }
        if($action_type == SOCIAL_ACTION_FRIEND || $action_type == SOCIAL_ACTION_FOLLOW){
            $social_can_rate = false;
            $social_can_share = false;
            $social_can_like = false;
            $social_can_comment = false;
        }
        $title = htmlEntityDecode($title);
        
        //$username = returnUserDisplayName($action_profile);
        $pic = $action_profile['profile_Pic'];
        $gender = $action_profile['gender'];

        $overhead_text = htmlEntityDecode($overhead_text);
        if(!isset($owner_id)){
            $owner_id = $entity_owner;//socialEntityOwner($entity_type, $entity_id );
        }
        
        
        if ($entity_type == SOCIAL_ENTITY_ALBUM) { 
            $album = userCatalogGet($entity_id);
            /*To overwrite the value of comment,rating in case of album*/
            $loop_ret_row['nb_comments']=$album['nb_comments']; 
            $loop_ret_row['nb_ratings']=$album['nb_ratings'];
        }
      
        $loop_ret_row['feed_id'] = $vfeeditem['id'];
        $loop_ret_row['feed_ts'] = $feed_ts;
        
        $loop_ret_row['event_location'] = '';
        $loop_ret_row['event_date'] = '';
        $loop_ret_row['event_time'] = '';
        $loop_ret_row['event_photo'] = '';
        // print_r($videoRecord);die;    
        //to show event information mukesh
        if($entity_type== SOCIAL_ENTITY_EVENTS || $entity_type== SOCIAL_ENTITY_USER_EVENTS || $entity_type == SOCIAL_ENTITY_EVENTS_DATE || $entity_type == SOCIAL_ENTITY_EVENTS_LOCATION || $entity_type == SOCIAL_ENTITY_EVENTS_TIME){
            
            if ($channel_id > 0) {  /*for channel event*/
                $arrEventDetails   = channelEventInfo($videoRecord['id'], -1); /*$videoRecord['id'] returns entity_id/event_id */
                if ($arrEventDetails['photo'] == '') {
                    $photoReturneventImage = 'media/images/channel/eventthemephoto.jpg';
                } else {
                    $photoReturneventImage = 'media/channel/' . $arrEventDetails['channelid'] . '/event/thumb/' . $arrEventDetails['photo'];
                }
            }else{           /*for user event*/
                $arruserEventDetails   = userEventInfo($videoRecord['id'], -1); 
                if ($arruserEventDetails['photo'] == '') {
                    $photoReturneventImage = 'media/images/' . LanguageGet() . '/eventsdetailed/eventthemephoto.jpg';
                } else {
                    $photoReturneventImage = 'media/videos/uploads/events/'. $arruserEventDetails['relativepath'].'thumb/' . $arruserEventDetails['photo'];
                }
            }
            //$eventphoto = ($arrEventDetails['photo']) ? photoReturneventImage($arrEventDetails) : 'media/images/channel/eventthemephoto.jpg';
            $eventphoto = $photoReturneventImage;
            $loop_ret_row['event_location'] = $videoRecord['location'];
            $loop_ret_row['event_date'] = date('d/m/Y', strtotime($videoRecord['fromdate']));
            $loop_ret_row['event_time'] = date('g:i a', strtotime('2000-1-1 ' . $videoRecord['fromtime']));
            $loop_ret_row['event_photo'] = $eventphoto;
            if($entity_type != SOCIAL_ENTITY_USER_EVENTS && $action_type != SOCIAL_ACTION_SHARE && $action_type != SOCIAL_ACTION_LIKE && $action_type != SOCIAL_ACTION_COMMENT && $action_type != SOCIAL_ACTION_INVITE && $action_type != SOCIAL_ACTION_EVENT_JOIN){
                $feed_user_id = $videoRecord['channelid'];       /*to overwrite user_id in some condition*/
            }
            if($entity_type == SOCIAL_ENTITY_EVENTS_LOCATION || $entity_type == SOCIAL_ENTITY_EVENTS_DATE || $entity_type == SOCIAL_ENTITY_EVENTS_TIME){
                $feed_user_id=$videoRecord['user_id'];
                $description = htmlEntityDecode($videoRecord['description']);
                $title = $videoRecord['name'];
            }
        }
        
        //to show channel_id as a user_id in case of owner_type is channel with some entities
        if($owner_type == 'channel'){
            if($entity_type == SOCIAL_ENTITY_CHANNEL_INFO || $entity_type == SOCIAL_ENTITY_EVENTS_LOCATION || $entity_type == SOCIAL_ENTITY_EVENTS_DATE || $entity_type == SOCIAL_ENTITY_EVENTS_TIME || $entity_type == SOCIAL_ACTION_INVITE) //$entity_type == SOCIAL_ENTITY_CHANNEL_PROFILE//SOCIAL_ENTITY_CHANNEL_COVER
            {
                $feed_user_id = $videoRecord['channelid'];
            }
            if($entity_type == SOCIAL_ENTITY_CHANNEL_COVER){
                $feed_user_id = $actionRecord['user_id'];
            }
        }
        
        //Override owner_type is case of entity_type 9 only 
        if($action_row['entity_type'] == SOCIAL_ENTITY_COMMENT){   
            $owner_type = 'user';
        }
        
        //to show update profile pic information mukesh
        $loop_ret_row['address']=$address;
        $loop_ret_row['register_date']=$register_date;
        $loop_ret_row['nb_followers']=$nb_followers;
        $loop_ret_row['nb_friends']=$nb_friends;
                 
        //Elie
        $loop_ret_row['target_name'] = trim($target_name);
        $loop_ret_row['target_id'] = $target_id;
        $loop_ret_row['other_name'] = trim($other);
        $loop_ret_row['logged_in_user'] = $user_id;
        $like_value = socialLiked ($user_id, $entity_id, $entity_type);
        
        $rate_value = socialRateGet($user_id, $entity_id, $entity_type);

        $loop_ret_row['like_value'] = !$like_value ? 0 : $like_value;
        $loop_ret_row['rate_value'] = $rate_value ? $rate_value : 0;

        $loop_ret_row['other_id'] = $otherId;
        $loop_ret_row['full_link'] = $fullLink ? $fullLink : "";

        //To show other images pf album mukesh
        $loop_ret_row['other_media'] =$other_img;

        $loop_ret_row['user_id'] = $feed_user_id;
        $loop_ret_row['overhead_text'] = strip_tags($overhead_text);
        $loop_ret_row['echo_text'] = $echo_text;
        $loop_ret_row['enable_shares_comments'] = $enable_shares_comments;
        $loop_ret_row['entity_id'] = $entity_id;
        $loop_ret_row['entity_type'] = $entity_type;
        $loop_ret_row['action_type'] = $vfeeditem['action_type'];
        
        if($videoRecord){
            foreach ($videoRecord as $key => $value) {
                if (is_int($key)) {
                    unset($videoRecord[$key]);
                }
            }
        }

        $loop_ret_row['entity_record'] = !$videoRecord ? "" : $videoRecord;
        $loop_ret_row['action_row'] = $actionRecord;
        $loop_ret_row['action_row_count'] = $vfeeditem['action_row_count'];

        $loop_ret_row['social_can_share'] = $social_can_share;
        $loop_ret_row['social_can_like'] = $social_can_like;
        $loop_ret_row['social_can_comment'] = $social_can_comment;
        if (!($vfeeditem['action_row']['entity_type'] == SOCIAL_ENTITY_MEDIA || $vfeeditem['action_row']['entity_type'] == SOCIAL_ENTITY_ALBUM) ){
            $social_can_rate = false;
        }
        $loop_ret_row['social_can_rate'] = $social_can_rate;
        $loop_ret_row['user_can_join_check'] = $user_can_join_check;
        
        if($action_type == SOCIAL_ACTION_UPLOAD && $entity_type == SOCIAL_ENTITY_MEDIA){
            $loop_ret_row['action'] = sprintfn($action, array('count' =>$action_row_count));
        }
        else{
            $loop_ret_row['action'] = $action;
        }
//        $loop_ret_row['action_array'] = $action_array;


        $loop_ret_row['action_id'] = $action_id;
        $loop_ret_row['is_visible'] = $is_visible;
        $loop_ret_row['title'] = $title;
        $loop_ret_row['description'] = $description? $description : "";
        $loop_ret_row['uploaded_on'] = $uploaded_on;
        $loop_ret_row['video_photo'] = $video_photo;
        if($entity_type == SOCIAL_ENTITY_POST){
            $loop_ret_row['video_photo'] = $videoRecord['is_video'] == "0" ? "photo" : "video";
        }
        $loop_ret_row['gender'] = $gender;

        $loop_ret_row['user_name'] = trim($username);
//        $loop_ret_row['user_profile_link'] = userProfileLink($action_profile);

        $loop_ret_row['user_profile_pic']= 'media/tubers/' . $pic;
//        $loop_ret_row['user_profile_pic']=ReturnLink('media/tubers/' . $pic);
        
        if($action_type == SOCIAL_ACTION_INVITE && $entity_type == SOCIAL_ENTITY_CHANNEL){
            $userInfo_action = getUserInfo($actionRecord['from_user']);
            $logo_src_action = 'media/tubers/' . $userInfo_action['profile_Pic'];
            $tuber_name_action = returnUserDisplayName($userInfo_action);
            $loop_ret_row['user_id'] =  $actionRecord['from_user'];
            $loop_ret_row['user_profile_pic'] =  $logo_src_action;
            $loop_ret_row['user_name'] =  $tuber_name_action;
        }
        else if( ($action_type == SOCIAL_ACTION_INVITE && $entity_type == SOCIAL_ENTITY_USER_EVENTS) || ($action_type == SOCIAL_ACTION_INVITE && $entity_type == SOCIAL_ENTITY_EVENTS) ){
            $pic = $userInfo_action['profile_Pic'];
            $loop_ret_row['user_profile_pic']= 'media/tubers/' . $pic;    
        }
        
        $loop_ret_row['owner_type'] = $owner_type;
        //to show invite channel info mukesh
        if(($entity_type== SOCIAL_ENTITY_CHANNEL || $entity_type== SOCIAL_ENTITY_CHANNEL_COVER || $entity_type== SOCIAL_ENTITY_CHANNEL_PROFILE) && ($owner_type == 'channel')){
            if($entity_type== SOCIAL_ENTITY_CHANNEL_COVER){
                $channel_info = channelGetInfo($videoRecord['channelid']);
                if ($channel_info['header'] == '') {
                    $channel_header = 'media/images/channel/coverphoto.jpg';
                } else {
                    $channel_header = 'media/channel/' . $channel_info['id'] . '/thumb/' . $channel_info['header'];
                }

                if ($channel_info['logo'] == '') {
                    $channel_logo = 'media/tubers/tuber.jpg';
                } else {
                    $channel_logo = 'media/channel/' . $channel_info['id'] . '/thumb/' . $channel_info['logo'];
                }
            }
            else{
                if ($videoRecord['header'] == '') {
                    $channel_header = 'media/images/channel/coverphoto.jpg';
                } else {
                    $channel_header = 'media/channel/' . $videoRecord['id'] . '/thumb/' . $videoRecord['header'];
                }

                if ($videoRecord['logo'] == '') {
                    $channel_logo = 'media/tubers/tuber.jpg';
                } else {
                    $channel_logo = 'media/channel/' . $videoRecord['id'] . '/thumb/' . $videoRecord['logo'];
                }
            }
            
//            $channel_header = photoReturnchannelHeader($videoRecord);
//            $channel_logo = photoReturnchannelLogo($videoRecord); 
        }
        $loop_ret_row['channel_logo'] = $channel_logo;
        $loop_ret_row['channel_cover'] = $channel_header;
        
        $loop_ret_row['owner_name'] = trim($owner_name);
        $loop_ret_row['owner_id'] = $owner_id;
//        $loop_ret_row['channel_id'] = $channelId ? $channelId : "";
        $loop_ret_row['channel_id'] = $channel_id ? $channel_id : "";
        $loop_ret_row['action_row_other'] = $action_row_other;
        
//        if ($channelInfo['owner_id'] == $loggedUser) {
//            $channelInfo_disable = 1;
//            $fullName = htmlEntityDecode($channelInfo['channel_name']);
//            $profilePic = ($channelInfo['logo']) ? photoReturnchannelLogo($channelInfo) : ReturnLink('/media/tubers/small_tuber.jpg');
//            $s_user_link = ReturnLink('channel/'.$channelInfo['channel_url']);
//        }
        $loop_ret_row['action_owner_type'] = 'user';
        $related_channel = 0;
        if ($action_type == SOCIAL_ACTION_SPONSOR || $action_type == SOCIAL_ACTION_RELATION_SUB || $action_type == SOCIAL_ACTION_RELATION_PARENT) {
            $related_channel = 1;
            $channelInfo = channelGetInfo($vfeeditem['user_id']);
            $loop_ret_row['user_profile_pic'] = $channelInfo['logo'] ? 'media/channel/' . $channelInfo['id'] . '/thumb/' . $channelInfo['logo'] : '/media/tubers/tuber.jpg';
//            $loop_ret_row['user_profile_pic'] = ($channelInfo['logo']) ? photoReturnchannelLogo($channelInfo) : ReturnLink('/media/tubers/tuber.jpg');
            $loop_ret_row['user_name'] = $channelInfo['channel_name'];
            $loop_ret_row['user_id'] = $channelInfo['id'];
            $loop_ret_row['action_owner_type'] = 'channel';
        } else if (intval($media_row['channelid']) > 0 || intval($media_row['channel_id']) > 0) {
            if (intval($media_row['channel_id']) > 0) {
                $channelInfo = channelGetInfo($media_row['channel_id']);
            } else {
                $channelInfo = channelGetInfo($media_row['channelid']);
            }
            if ($channelInfo['owner_id'] == $vfeeditem['user_id']) {
                $related_channel = 1;
                $loop_ret_row['user_profile_pic'] = $channelInfo['logo'] ? 'media/channel/' . $channelInfo['id'] . '/thumb/' . $channelInfo['logo'] : '/media/tubers/tuber.jpg';
//                $loop_ret_row['user_profile_pic'] = ($channelInfo['logo']) ? photoReturnchannelLogo($channelInfo) : ReturnLink('/media/tubers/tuber.jpg');
                $loop_ret_row['user_name'] = $channelInfo['channel_name'];
                $loop_ret_row['user_id'] = $channelInfo['id'];
                $loop_ret_row['action_owner_type'] = 'channel';
//                $channel_url = ReturnLink('channel/' . $channelInfo['channel_url']);
//                $tuber_name_action = '<a href="' . $channel_url . '" target="_blank">' . htmlEntityDecode($channelInfo['channel_name']) . '</a>';
//                $pic_lnk = $channel_url;
//                $logo_src_action = ($channelInfo['logo']) ? photoReturnchannelLogo($channelInfo) : ReturnLink('/media/tubers/tuber.jpg');
            } else {
//                $tuber_name_action = '<a href="' . $user_profile_link . '" target="_blank">' . $tuber_name_action . '</a>';
//                $pic_lnk = $user_profile_link;
            }
        }
        
        $log_top_related = "Tfriends";
        if ($related_channel == 1) {
//            $hide_all_button_text = "channel";
//            $ischannel = 1;
//            $hide_all_button_id = $channelInfo['id'];
            $log_top_related = "TChannel";
        } else {
//            $hide_all_button_text = "Ttuber";
//            $hide_all_button_id = $user_id_feed;
//            $ischannel = 0;
            $usisfriend = userIsFriend($user_id, $user_id_feed);
            if (!$usisfriend) {
                $usfollow = userSubscribed($user_id, $user_id_feed);
                if ($usfollow) {
                    $log_top_related = "Tfollowings";
                }
            } else {
                $log_top_related = "Tfriends";
            }
        }
        $loop_ret_row['related_to'] = $log_top_related;
        $loop_ret_row['post_video_link']=$post_video_link;
        
        $activity_log_related_to = "tt";
        if ( isset($channelInfo) && isset($channelInfo['owner_id']) && intval($channelInfo['owner_id']) > 0 ) {
            $activity_log_related_to = "c";
        } else if ($vfeeditem['action_row']['entity_type'] == SOCIAL_ENTITY_BAG || $vfeeditem['entity_type'] == SOCIAL_ENTITY_BAG) {
            $activity_log_related_to = "h";
        }else if ($vfeeditem['action_row']['entity_type'] == SOCIAL_ENTITY_FLASH || $vfeeditem['entity_type'] == SOCIAL_ENTITY_FLASH) {
            $activity_log_related_to = "e";
        } else {
            if (intval($vfeeditem['owner_id']) == $user_id) { 
                $activity_log_related_to = "tt";
            } else {
                $activity_log_related_to = "n";
            }
        }
        $loop_ret_row['activity_log_related_to'] = $activity_log_related_to;
        $loop_ret_row['is_read'] = $vfeeditem['published'];
        $loop_ret_row['entity_record']['like_value'] = socialLikesGet(array(
            'entity_id' => $entity_id,
            'entity_type' => $entity_type,
            'like_value' => 1,
            'n_results' => true
        ));
        switch($entity_type){
            case SOCIAL_ENTITY_MEDIA:
                $thumbLink = $vfeeditem['media_row']['fullpath'] . $vfeeditem['media_row']['name'];
                $loop_ret_row['shareLink'] = ReturnVideoUriHashed($vfeeditem['media_row']);
                $loop_ret_row['share_image'] = $uricurserver.'/'.$thumbLink;
                break;
            case SOCIAL_ENTITY_CHANNEL:
                $loop_ret_row['shareLink'] = channelMainLink($vfeeditem['media_row']);
                $loop_ret_row['share_image'] = $uricurserver.'/media/channel/' . $vfeeditem['media_row']['id'] . '/thumb/' . $vfeeditem['media_row']['header'];
                break;
        }
        $ret_arr['feeds'][] = $loop_ret_row;
    } 
    foreach ($ret_arr['feeds'] as $key => $value) {
        if (is_null($value)) {
             $ret_arr['feeds'][$key] = "";
        }
    }
//echo json_encode($ret_arr['feeds']);exit(); 
    return $ret_arr['feeds'];
}