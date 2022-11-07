<?php

$ret_arr = array();
$ret_arr['feeds'] = array();
foreach ( $news_feed as $vfeeditem ){
    
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
        $echo_text = '';
        $fullLink = '';
        $target_name = '';
        $target_id = '';
        $other = '';
        $otherId = '';
        $loop_ret_row = array();
        $overhead_text = '';
        $videoRecord = $vfeeditem['media_row'];
        $actionRecord = $vfeeditem['action_row'];
        $action_row_count = $vfeeditem['action_row_count'];
        $action_row_other = $vfeeditem['action_row_other'];
        $action_id = $vfeeditem['action_id'];
        $is_visible = $vfeeditem['is_visible'];
        $entity_owner = $vfeeditem['owner_id'];
        $channelId = $vfeeditem['channel_id'];
        $channel_id=0;
        if (intval($videoRecord['channelid']) > 0) {
            $channel_id = intval($videoRecord['channelid']);
        }else if (intval($videoRecord['channel_id']) > 0) {
            $channel_id = intval($videoRecord['channel_id']);
        }
        $owner_type = $channel_id > 0 ? 'channel' : 'user';
        
        $owner_name = '';
        $action_profile = $vfeeditem;

        $gender = 'his';
        if($action_profile['gender']=='F'){
                $gender = 'her';
        }
        $userId = $action_profile['user_id'];
        $action_user_profile = getUserInfo($userId);
        $action_profile = $action_user_profile;
        $username = returnUserDisplayName($action_user_profile);

        $video_photo = ( strstr($videoRecord['type'],'image') == null ) ? 'video' : 'photo';

        $valid = true;
        $feed_ts = strtotime($vfeeditem['feed_ts']);
        $feed_user_id = $vfeeditem['user_id'];

        //$entity_id = $videoRecord['id'];
        $entity_id = $vfeeditem['entity_id']; //the entity_id  for the album is not the id of the media record
        $entity_type = $vfeeditem['entity_type'];
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
                            if($action_row_count>1){                                
                                $staticaction = "[ActionOwner] uploaded $action_row_count";//todo
                                array_unshift($action_array, $action_row_count);                           
                            }
                        }

                        $action = "$staticaction new $video_photo";
                        $title = $videoRecord['title'];
                        $description = $videoRecord['description'];
                        $description = htmlEntityDecode($description);
                        $target_name = $title;
                        $target_id = $videoRecord['id'];
                        switch($entity_type){
                                case SOCIAL_ENTITY_USER_EVENTS:
                                        $action = "[ActionOwner] created a new event [TargetName]";					
                                        break;
                                case SOCIAL_ENTITY_NEWS:
                                        $action = "[ActionOwner] added news";					
                                        break;
                                case SOCIAL_ENTITY_BROCHURE:
                                        $action = "[ActionOwner] added a new brochure";					
                                        break;
                                case SOCIAL_ENTITY_EVENTS:
                                        $action = '[ActionOwner] created a new event [TargetName]';					
                                        break;
                                case SOCIAL_ENTITY_JOURNAL:
                                        $action = "[ActionOwner] added a new journal [TargetName]";					
                                        break;
                                case SOCIAL_ENTITY_HOTEL_REVIEWS:
//                                case SOCIAL_ENTITY_RESTAURANT_REVIEWS:
                                case SOCIAL_ENTITY_LANDMARK_REVIEWS:
                                    $action= "[ActionOwner] added the following review";
                                break;
                                case SOCIAL_ENTITY_POST:
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
                                                $owner_name = returnUserDisplayName($userInfo_action);
                                                $action="[ActionOwner] posted the following on [Owner] 's TT page:";								
                                            }
                                        }
                                        if( $videoRecord['post_type'] == SOCIAL_POST_TYPE_LINK ){
                                                $action="[ActionOwner] posted a link on $gender TT page";
                                                if( intval($videoRecord['channel_id']) !=0 ){
                                                        $action = "[ActionOwner] posted a link";
                                                }
                                                if($from_id != $action_profile['id'] ){
                                                    if($realvalue==0){
                                                        $action='[ActionOwner] posted a link on your TT page';
                                                    }else{
                                                        $action="[ActionOwner] posted a link on [Owner] 's TT page";
                                                        $userInfo_action = getUserInfo($action_profile['id']);
                                                        $owner_name = returnUserDisplayName($userInfo_action);
                                                    }								
                                                }
                                        }else if( $videoRecord['post_type'] == SOCIAL_POST_TYPE_PHOTO ){
                                                $action="[ActionOwner] posted a photo on $gender TT page";
                                                if( intval($videoRecord['channel_id']) !=0 ){
                                                        $action = "[ActionOwner] posted a new photo";
                                                }
                                                if($from_id != $action_profile['id'] ){
                                                    if($realvalue==0){
                                                        $action='[ActionOwner] posted a photo on your TT page';
                                                    }else{
                                                        $action="[ActionOwner] posted a photo on [Owner] 's TT page";
                                                        $userInfo_action = getUserInfo($action_profile['id']);
                                                        $owner_name = returnUserDisplayName($userInfo_action);
                                                    }								
                                                }							
                                        }else if( $videoRecord['post_type'] == SOCIAL_POST_TYPE_VIDEO ){
                                                $action="[ActionOwner] posted a video on $gender TT page";
                                                if( intval($videoRecord['channel_id']) !=0 ){
                                                        $action = "[ActionOwner] posted a new video";
                                                }
                                                if($from_id != $action_profile['id'] ){
                                                    if($realvalue==0){
                                                        $action='[ActionOwner] posted a video on your TT page';
                                                    }else{
                                                        $action="[ActionOwner] posted a video on [Owner] 's TT page";
                                                        $userInfo_action = getUserInfo($action_profile['id']);
                                                        $owner_name = returnUserDisplayName($userInfo_action);
                                                    }						
                                                }							
                                        }
                                        break;
                                case SOCIAL_ENTITY_FLASH:
                                    $action= "[ActionOwner] echoed the following";                             
                                    $echo_text = $vfeeditem['media_row']['flash_text'];
                                break;
                        }
                        break;
                case SOCIAL_ACTION_UPDATE://todo validate data
                        $action = "[ActionOwner] updated $gender";
                        switch($entity_type){
                                case SOCIAL_ENTITY_CHANNEL_SLOGAN:
                                        $action = "[ActionOwner] updated their slogan";					
                                        break;
                                case SOCIAL_ENTITY_CHANNEL_INFO:
                                        $action = "[ActionOwner] updated their info";					
                                        break;
                                case SOCIAL_ENTITY_CHANNEL_COVER:
                                        $action = "[ActionOwner] updated their cover photo";					
                                        break;
                                case SOCIAL_ENTITY_CHANNEL_PROFILE:
                                        $action = "[ActionOwner] updated their profile image";					
                                        break;
                                case SOCIAL_ENTITY_USER_PROFILE:
                                        $action = "[ActionOwner] updated $gender profile image";					
                                        break;
                                case SOCIAL_ENTITY_EVENTS_LOCATION:
                                        $action = "[ActionOwner] updated $gender event location [TargetName]";
                                        $target_name = htmlEntityDecode($media_row['name']);
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
                        $action = "$staticaction to [TargetName] 's channel";
                        $target_name = $videoRecord['channel_name'];
                        $target_id = $videoRecord['id'];
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
                            $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($videoRecord['channel_name']).'</span></a>';
                            $action = "$staticaction [TargetName] 's channel";
                            $target_name = $videoRecord['channel_name'];
                            $target_id = $videoRecord['id'];
                            break;
                        case SOCIAL_ENTITY_EVENTS:
//                                    $action = "$staticaction an event %s";
                            $event_name = $videoRecord['name'];
                            $action = "$staticaction an event $event_name";
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
                    $staticaction = "%s has accepted";                        
                    if($action_row_count>1){
                        $staticaction = "%s and %s others %s have accepted";
                    }else if($action_row_count==1){                            
                        $staticaction = "%s and %s have accepted";
                    }
                    $channel_array = channelGetInfo($feed_user_id);
                    $channel_url_r = ReturnLink('channel/'.$videoRecord['channel_url']);
                    $channel_name_r = htmlEntityDecode($videoRecord['channel_name']);
                    if(strlen($channel_name_r) > 32){
                       $channel_name_r = substr($channel_name_r,0,32).' ...';
                    }
                    $action = "$staticaction %s as a sub channel";
                    $action_array[]='<a href="' . $channel_url_r . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.$channel_name_r.'</span></a>';
                    if($actionRecord['relation_type']==CHANNEL_RELATION_TYPE_SUB){
                        $staticaction = "%s is now";                        
                        if($action_row_count>1){
                            $staticaction = "%s and %s others %s are now";
                        }else if($action_row_count==1){                            
                            $staticaction = "%s and %s are now";
                        }
                        $action_array=array();
                        $action_array[]='<a href="' . $channel_url_r . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.$channel_name_r.'</span></a>';
                        $action = "$staticaction a sub channel to %s";
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
                        $staticaction = "[ActionOwner] joined";                        
                        if($action_row_count>1){
                            $staticaction = "[ActionOwner] and [Others] joined";
                        }else if($action_row_count==1){                            
                            $staticaction = "[ActionOwner] and [Other] joined";
                        }
                        if( intval($videoRecord['channelid']) !=0 ){
                                $channel_array=channelGetInfo($videoRecord['channelid']);
                                $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                $action= "$staticaction [Owner] 's event [TargetName]";	
                                $owner_name = $channel_array['channel_name'];
                        }else{
                                $action = "$staticaction the event [TargetName]";
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
                        }
                        switch($original_entity_type){
                                case SOCIAL_ENTITY_MEDIA:
                                        if($action_row_count>=1){
                                            $action = "$staticaction the ";
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
                                        $target_name = $title;
                                        $target_id = $videoRecord['id'];
//                                                $fullLink = $videoRecord['fullpath'] . $videoRecord['name'];
                                        $fullLink = photoReturnSrcSmall($videoRecord);
//                                                $thumbPath = resizepic($thumbLink, 'm', false, '1');
                                        if($from_id != $action_profile['id'] ){
                                                if( intval($videoRecord['channelid']) !=0 ){
                                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                                        $action_array=array();
//                                                                $action="$staticaction %s 's " ; 
                                                        $action="$staticaction [Owner] 's $video_photo [TargetName]";
                                                        $owner_name = $channel_array['channel_name'];
                                                        $action_array[] = '<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                }else{
                                                        $userInfo_action = getUserInfo($from_id);	
                                                        $uslnk= userProfileLink($userInfo_action);
                                                        $action="$staticaction your $video_photo [TargetName]";
                                                        if( $userInfo_action['id'] != $userId ){
                                                                $action_array = array();
                                                                $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                $owner_name = returnUserDisplayName($userInfo_action);
                                                                $action="$staticaction [Owner] 's $video_photo [TargetName]";
                                                        }
                                                }
                                        }
                                        break;
                                case SOCIAL_ENTITY_WEBCAM:
                                        $action = "$staticaction ";
                                        break;
                                case SOCIAL_ENTITY_ALBUM:
                                        $title = $videoRecord['title'];
                                        $target_name = $title;
                                        if($action_row_count>=1){
                                            $action = "$staticaction the ";
                                        }else{
                                            $action = "$staticaction $gender own ";
                                        }  
                                        if( intval($videoRecord['channelid']) !=0 ){
                                                $action = "$staticaction their ";
                                        }
                                        $from_id = intval($videoRecord['userid']);
                                        if($from_id != $action_profile['id'] ){
                                                if( intval($videoRecord['channelid']) !=0 ){
                                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                                        $action_array[] ='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                        $action="$staticaction %s 's ";	
                                                }else{
                                                        $userInfo_action = getUserInfo($from_id);	
                                                        $uslnk= userProfileLink($userInfo_action);
                                                        $action="$staticaction your ";
                                                        if( $userInfo_action['id'] != $userId ){
                                                                $action_array=array();
                                                                $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                $action="$staticaction [Owner] 's album [TargetName]";
//                                                                        $action="$staticaction %s 's ";
                                                        }

                                                }
                                        }
                                        break;
                                case SOCIAL_ENTITY_CHANNEL:
                                        $channel_url = ReturnLink('channel/'.$videoRecord['channel_url']);
                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($videoRecord['channel_name']).'</span></a>';
                                        $action="$staticaction [TargetName] 's channel";
                                        $target_name = $videoRecord['channel_name'];
                                        $target_id = $videoRecord['id'];
                                        $fullLink = '/media/channel/' . $videoRecord['id'] . '/thumb/' . $videoRecord['header'];
                                        break;
                                case SOCIAL_ENTITY_NEWS:
                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                        $action_array=array();
                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                        $action="$staticaction [Owner] 's news";
                                        $owner_name = $channel_array['channel_name'];
                                        break;
                                case SOCIAL_ENTITY_BROCHURE:
                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                        $action_array=array();
                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                        $action="$staticaction [Owner] 's brochure";
                                        $owner_name = $channel_array['channel_name'];
                                        break;
                                case SOCIAL_ENTITY_EVENTS:
                                        $target_name = $videoRecord['name'];
                                        $target_id = $videoRecord['id'];
                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                        $action="$staticaction [Owner] 's event [TargetName]";
                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                        $owner_name = $channel_array['channel_name'];
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
                                                $action="$staticaction %s 's slogan";													
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
                                                $action="$staticaction %s 's info";												
                                        }
                                        break;
                                case SOCIAL_ENTITY_CHANNEL_COVER:
                                        $action="$staticaction their cover photo";	
                                        $channelDetailArray=GetChannelDetailInfo($entity_id);
                                        $channel_array=channelGetInfo($channelDetailArray['channelid']);
                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                        $from_id = intval($channel_array['owner_id']);	
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);		
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'â€™s</span></a>';
                                                $action="$staticaction [Owner] 's cover photo";		
                                                $owner_name = $channel_array['channel_name'];
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
                                                $action="$staticaction %s 's profile image";													
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
                                        $from_id = intval($userDetailArray['user_id']);						
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);
                                                $uslnk= userProfileLink($userInfo_action);
                                                $action="$staticaction your profile image";
                                                if( $userInfo_action['id'] != $userId ){
                                                        $action="$staticaction %s profile image";
                                                        $action_array = array();
                                                        $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'â€™s</span></a>';
                                                }

                                        }
                                        break;
                                case SOCIAL_ENTITY_HOTEL_REVIEWS:
//                                case SOCIAL_ENTITY_RESTAURANT_REVIEWS:
                                case SOCIAL_ENTITY_LANDMARK_REVIEWS:
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
                                break;
                                case SOCIAL_ENTITY_POST:
                                        if( $videoRecord['post_type'] == SOCIAL_POST_TYPE_LOCATION ){
                                                if($action_row_count>=1){
                                                    $action="$staticaction the location";
                                                }else{
                                                    $action="$staticaction $gender location";
                                                }  
                                                $from_id = intval($videoRecord['user_id']);

                                                if($from_id != $action_profile['id'] ){
                                                        $userInfo_action = getUserInfo($from_id);
                                                        $uslnk= userProfileLink($userInfo_action);
                                                        $action="$staticaction your location status";
                                                        if( $userInfo_action['id'] != $userId ){
                                                                $action_array=array();
                                                                $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                $action="$staticaction [Owner] 's location status";
                                                                $owner_name = returnUserDisplayName($userInfo_action);
                                                                $owner_id = $from_id;
                                                        }										

                                                }
                                        }else {
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
                                                                $action="$staticaction [Owner] 's post";
                                                                $owner_name = $channel_array['channel_name'];
                                                        }else{
                                                                $userInfo_action = getUserInfo($from_id);
                                                                $uslnk= userProfileLink($userInfo_action);
                                                                        $action="$staticaction your post";	
                                                                if( $userInfo_action['id'] != $userId ){
                                                                    $action_array=array();	
                                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                    $action="$staticaction [Owner] 's post";	
                                                                    $owner_name = returnUserDisplayName($userInfo_action);
                                                                    $owner_id = $from_id;
                                                                }
                                                        }
                                                }
                                        }
                                        break;
                                case SOCIAL_ENTITY_FLASH:
                                    if($action_row_count>=1){
                                        $action="$staticaction the %s echo %s";
                                    }else{
                                        $action="$staticaction $gender own %s echo %s";
                                    }  
                                    if( intval($videoRecord['channel_id']) !=0 ){
                                            $action = "$staticaction their %s echo %s";
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
                                                    $action="$staticaction %s 's %s echo %s";	
                                            }else{
                                                    $userInfo_action = getUserInfo($from_id);
                                                    $uslnk= userProfileLink($userInfo_action);
                                                    $action="$staticaction your %s echo %s";	
                                                    if( $userInfo_action['id'] != $userId ){
                                                        $action_array=array();	
                                                        $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                        $action="$staticaction %s 's %s echo %s";	
                                                    }
                                            }
                                    }                                        
                                break;
                                case SOCIAL_ENTITY_USER_EVENTS:
                                        $target_name = $videoRecord['name'];
                                        $target_id = $videoRecord['id'];
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
                                                $action="$staticaction [Owner] 's event [TargetName]";
                                                $owner_name = returnUserDisplayName($userInfo_action);
                                            }													
                                        }						
                                        break;
                                case SOCIAL_ENTITY_JOURNAL:
                                        if($action_row_count>=1){
                                            $action="$staticaction the journal %s";
                                        }else{
                                            $action="$staticaction $gender own journal %s";
                                        }  
                                        $from_id = intval($videoRecord['user_id']);						
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);
                                                $uslnk= userProfileLink($userInfo_action);
                                                $where_action ='your';
                                                $action="$staticaction your journal %s";
                                                if( $userInfo_action['id'] != $userId ){
                                                    $action_array=array();	
                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                    $action="$staticaction %s 's journal %s";
                                                }
                                        }
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
                                        $target_name = $title;
                                        $target_id = $videoRecord['id'];
//                                                $fullLink = $videoRecord['fullpath'] . $videoRecord['name'];
                                        $fullLink = photoReturnSrcSmall($videoRecord);
                                        if($from_id != $action_profile['id'] ){
                                                if( intval($videoRecord['channelid']) !=0 ){
                                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                                        $action_array=array();
                                                        $action="$staticaction [Owner] 's $video_photo [TargetName]";
                                                        $owner_name = $channel_array['channel_name'];
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
                                                            $action="$staticaction [Owner] 's $video_photo [TargetName]";
                                                        }
                                                }
                                        }						
                                        break;
                                case SOCIAL_ENTITY_WEBCAM:
                                        $action = "$staticaction ";
                                        break;
                                case SOCIAL_ENTITY_ALBUM:						
                                        $title = $videoRecord['title'];
                                        $description = $videoRecord['description'];
                                        $description = htmlEntityDecode($description);
                                        if($action_row_count>=1){
                                            $action = "$staticaction the ";
                                        }else{
                                            $action = "$staticaction $gender own ";
                                        }  
                                        if( intval($videoRecord['channelid']) !=0 ){
                                                $action = "$staticaction their ";
                                        }
                                        $from_id = intval($videoRecord['userid']);
                                        if($from_id != $action_profile['id'] ){
                                                if( intval($videoRecord['channelid']) !=0 ){
                                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                                        $action_array[] = '<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                        $action="$staticaction %s 's ";	
                                                }else{
                                                        $userInfo_action = getUserInfo($from_id);	
                                                        $uslnk= userProfileLink($userInfo_action);
                                                        $action="$staticaction your ";
                                                        if( $userInfo_action['id'] != $userId ){
                                                                $action_array=array();
                                                                $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                $action="$staticaction %s 's ";
                                                        }
                                                }
                                        }
                                        break;                                
                                case SOCIAL_ENTITY_POST:
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
                                                $action="$staticaction %s 's post";	
                                            }else{
                                                $userInfo_action = getUserInfo($from_id);	
                                                $uslnk= userProfileLink($userInfo_action);
                                                $action="$staticaction your post";	
                                                if( $userInfo_action['id'] != $userId ){
                                                    $action="$staticaction %s 's post";	
                                                    $action_array=array();
                                                    $action_array[]= '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
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
                        $action="$staticaction the %s echo %s";
                    }else{
                        $action="$staticaction $gender own %s echo %s";
                    }  
                    if( intval($videoRecord['channel_id']) !=0 ){
                            $action = "$staticaction their %s echo %s";
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
                                    $action="$staticaction %s 's %s echo %s";	
                            }else{
                                    $userInfo_action = getUserInfo($from_id);		
                                    $uslnk= userProfileLink($userInfo_action);
                                    $action="$staticaction your %s echo %s";
                                    if( $userInfo_action['id'] != $userId ){
                                            $action_array=array();
                                            $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'â€™s</span></a>';
                                            $action="$staticaction %s 's %s echo %s";

                                    }

                            }
                    }

                    break;
                case SOCIAL_ACTION_COMMENT:
                        $overhead_text = htmlEntityDecode($actionRecord['comment_text']);                        
                        $staticaction = "[ActionOwner] commented on";                        
                        if($action_row_count>1){
                            $staticaction = "[ActionOwner] and [Others] commented on";
                        }else if($action_row_count==1){                            
                            $staticaction = "[ActionOwner] and [Other] commented on";
                        }

                        switch($entity_type){
                                case SOCIAL_ENTITY_MEDIA:
                                        if($action_row_count>=1){
                                            $action = "$staticaction the ";
                                        }else{
                                            $action = "$staticaction $gender own ";
                                        }  

                                        if( intval($videoRecord['channelid']) !=0 ){
                                                $action = "$staticaction their ";
                                                $action_array=array();
                                        }
                                        $title = $videoRecord['title'];
                                        $description = $videoRecord['description'];
                                        $description = htmlEntityDecode($description);

                                        $from_id = intval($videoRecord['userid']);
                                        $target_name = $title;
                                        $target_id = $videoRecord['id'];
//                                                $fullLink = $videoRecord['fullpath'] . $videoRecord['name'];
                                        $fullLink = photoReturnSrcSmall($videoRecord);
                                        if($from_id != $action_profile['id'] ){
                                                if( intval($videoRecord['channelid']) !=0 ){
                                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
//                                                                $action="$staticaction %s 's ";	
                                                        $action="$staticaction [Owner] 's $video_photo [TargetName]";
                                                        $owner_name = $channel_array['channel_name'];
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
                                                            $action="$staticaction [Owner] 's $video_photo [TargetName]";
                                                        }


                                                }
                                        }
                                break;
                                case SOCIAL_ENTITY_FLASH:
                                    $action_array=array();
                                    if($action_row_count>=1){
                                        $action="$staticaction the %s echo %s";
                                    }else{
                                        $action="$staticaction $gender own %s echo %s";
                                    }  
                                    if( intval($videoRecord['channel_id']) !=0 ){
                                            $action = "$staticaction their %s echo %s";
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
                                            $action="$staticaction %s 's %s echo %s";	
                                        }else{
                                            $userInfo_action = getUserInfo($from_id);		
                                            $uslnk= userProfileLink($userInfo_action);
                                            $action="$staticaction your %s echo %s";
                                            if( $userInfo_action['id'] != $userId ){
                                                $action_array=array();
                                                $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'â€™s</span></a>';
                                                $action="$staticaction %s 's %s echo %s";

                                            }

                                        }
                                    }                                    
                                break;
                                case SOCIAL_ENTITY_WEBCAM:
                                        $action = "$staticaction ";
                                        $description = $videoRecord['description'];
                                        $description = htmlEntityDecode($description);
                                        break;
                                case SOCIAL_ENTITY_ALBUM:
                                        if($action_row_count>=1){
                                            $action = "$staticaction the";
                                        }else{
                                            $action = "$staticaction $gender own ";
                                        }  
                                        if( intval($videoRecord['channelid']) !=0 ){
                                                $action = "$staticaction their ";
                                        }
                                        $from_id = intval($videoRecord['userid']);
                                        if($from_id != $action_profile['id'] ){
                                                if( intval($videoRecord['channelid']) !=0 ){
                                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'â€™s</span></a>';
                                                        $action="$staticaction %s 's ";	
                                                }else{
                                                        $userInfo_action = getUserInfo($from_id);	
                                                        $uslnk= userProfileLink($userInfo_action);
                                                        $action="$staticaction your ";
                                                        if( $userInfo_action['id'] != $userId ){
                                                                $action_array=array();
                                                                $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                $action="$staticaction %s 's ";
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
                                        $from_id = intval($userDetailArray['user_id']);						
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);		
                                                $uslnk= userProfileLink($userInfo_action);
                                                $action="$staticaction your profile image";
                                                if( $userInfo_action['id'] != $userId ){
                                                    $action_array=array();	
                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'â€™s</span></a>';
                                                    $action="$staticaction %s 's profile image";
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
                                                $target_name = $channel_array['channel_name'];
                                                $target_id = $channel_array['id'];
                                                $action="$staticaction [TargetName] 's channel";													
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
                                                $action="$staticaction %s 's news";													
                                        }
                                        break;
                                case SOCIAL_ENTITY_BROCHURE:
                                        $action="$staticaction their brochure";
                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                        $from_id = intval($channel_array['owner_id']);						
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                $action="$staticaction %s 's brochure";													
                                        }
                                        break;
                                case SOCIAL_ENTITY_EVENTS:
                                        $action="$staticaction their event %s";
                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                        $from_id = intval($channel_array['owner_id']);						
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                $action="$staticaction %s 's event %s";													
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
                                                $action="$staticaction %s 's slogan";													
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
                                                $action="$staticaction %s 's info";												
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
                                        $fullLink = '/media/channel/' . $channel_array['id'] . '/thumb/' . $channel_array['header'];
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                $action="$staticaction [TargetName] 's cover photo";													
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
                                                $action="$staticaction [EntityName] 's profile image";													
                                        }
                                        break;
                                case SOCIAL_ENTITY_HOTEL_REVIEWS:
//                                case SOCIAL_ENTITY_RESTAURANT_REVIEWS:
                                case SOCIAL_ENTITY_LANDMARK_REVIEWS:
                                    if ($action_row['entity_type'] == SOCIAL_ENTITY_HOTEL_REVIEWS) {
                                           $item_data = getHotelInfo($media_row['hotel_id']);
                                           $target_name = $item_data['hotelName'];
                                           $target_id = $item_data['id'];
                                       } else if ($action_row['entity_type'] == SOCIAL_ENTITY_RESTAURANT_REVIEWS) {
                                           $item_data = getRestaurantInfo($media_row['restaurant_id']);
                                           $target_name = $item_data['name'];
                                           $target_id = $item_data['id'];
                                       } else {
                                           $item_data = getPoiInfo($media_row['poi_id']);
                                           $target_name = $item_data['name'];
                                           $target_id = $item_data['id'];
                                       }
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
                                            $action="$staticaction [Owner] 's review";	
                                            $action_array=array();
                                            $action_array[]= '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                            $owner_name = returnUserDisplayName($userInfo_action);
                                            $owner_id = $from_id;
                                        }
                                    }
                                break;
                                case SOCIAL_ENTITY_POST:
                                        if( $videoRecord['post_type'] == SOCIAL_POST_TYPE_LOCATION ){	
                                                if($action_row_count>=1){
                                                    $action="$staticaction the location";
                                                }else{
                                                    $action="$staticaction $gender location";
                                                }  
                                                $from_id = intval($videoRecord['from_id']);
                                                if($from_id==0) $from_id = intval($videoRecord['user_id']);				

                                                if($from_id != $action_profile['id'] ){
                                                        $userInfo_action = getUserInfo($from_id);		
                                                        $uslnk= userProfileLink($userInfo_action);
                                                        $action="$staticaction your location status";
                                                        if( $userInfo_action['id'] != $userId ){
                                                            $action_array=array();	
                                                            $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                $action="$staticaction %s 's location status";
                                                        }

                                                }
                                        }else {
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
                                                                $action="$staticaction %s 's post";	
                                                        }else{
                                                                $userInfo_action = getUserInfo($from_id);		
                                                                $uslnk= userProfileLink($userInfo_action);
                                                                $action="$staticaction your post";
                                                                if( $userInfo_action['id'] != $userId ){
                                                                        $action_array=array();
                                                                        $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'â€™s</span></a>';
                                                                        $action="$staticaction %s 's post";

                                                                }

                                                        }
                                                }
                                        }
                                        break;
                                case SOCIAL_ENTITY_LOCATION:
                                        break;
                                case SOCIAL_ENTITY_USER_EVENTS:
                                        if($action_row_count>=1){
                                            $action="$staticaction the event %s";
                                        }else{
                                            $action="$staticaction $gender own event %s";
                                        }  
                                        $from_id = intval($videoRecord['user_id']);						
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);		
                                                $uslnk= userProfileLink($userInfo_action);							
                                                $action="$staticaction your event %s";
                                                if( $userInfo_action['id'] != $userId ){
                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                    $action="$staticaction %s 's event %s";
                                                }													
                                        }						
                                        break;
                                case SOCIAL_ENTITY_COMMENT:
                                        break;
                                case SOCIAL_ENTITY_FLASH:
                                        break;
                                case SOCIAL_ENTITY_JOURNAL:
                                        if($action_row_count>=1){
                                            $action="$staticaction the journal %s";
                                        }else{
                                            $action="$staticaction $gender own journal %s";
                                        }  
                                        $from_id = intval($videoRecord['user_id']);						
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);		
                                                $uslnk= userProfileLink($userInfo_action);
                                                $action="$staticaction your journal %s";
                                                if( $userInfo_action['id'] != $userId ){
                                                        $action_array=array();
                                                        $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                        $action="$staticaction %s 's journal %s";
                                                }

                                        }
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
                case SOCIAL_ACTION_SHARE:
                        $who = getUserInfo($actionRecord['from_user']);						
                        $action_profile = $who;
                        $feed_user_id = $actionRecord['from_user'];
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
                                            $action = "$staticaction the ";
                                        }else{
                                            $action = "$staticaction $gender own "; 
                                        }  
                                        if( intval($videoRecord['channelid']) !=0 ){
                                                $action = "$staticaction their ";
                                        }
                                        $title = $videoRecord['title'];
                                        $description = $videoRecord['description'];
                                        $description = htmlEntityDecode($description);
                                        $from_id = intval($videoRecord['userid']);

                                        $target_name = $title;
                                        $target_id = $videoRecord['id'];
//                                                $fullLink = $videoRecord['fullpath'] . $videoRecord['name'];
                                        $fullLink = photoReturnSrcSmall($videoRecord);
                                        if($from_id != $action_profile['id'] ){
                                                if( intval($videoRecord['channelid']) !=0 ){
                                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
//                                                                $action="$staticaction %s 's ";
                                                        $action="$staticaction [Owner] 's $video_photo [TargetName]";
                                                        $owner_name = $channel_array['channel_name'];
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
                                                                $action="$staticaction [Owner] 's $video_photo [TargetName]";
                                                        }
                                                }
                                        }
                                        break;
                                case SOCIAL_ENTITY_WEBCAM:
                                        $action = "$staticaction ";
                                        $description = $videoRecord['description'];
                                        $description = htmlEntityDecode($description);
                                        break;
                                case SOCIAL_ENTITY_ALBUM:
                                        $title = $videoRecord['title'];
                                        $description = $videoRecord['description'];
                                        $description = htmlEntityDecode($description);
                                        if($action_row_count>=1){
                                            $action = "$staticaction the ";
                                        }else{
                                            $action = "$staticaction $gender own ";
                                        }  
                                        if( intval($videoRecord['channelid']) !=0 ){
                                                $action = "$staticaction their ";
                                        }
                                        $from_id = intval($videoRecord['userid']);
                                        if($from_id != $action_profile['id'] ){
                                                if( intval($videoRecord['channelid']) !=0 ){
                                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                        $action="$staticaction %s 's ";	
                                                }else{
                                                        $userInfo_action = getUserInfo($from_id);	
                                                        $uslnk= userProfileLink($userInfo_action);
                                                        $action="$staticaction your ";
                                                        if( $userInfo_action['id'] != $userId ){
                                                            $action_array=array();	
                                                            $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                            $action="$staticaction %s 's ";
                                                        }
                                                }
                                        }
                                        break;
                                case SOCIAL_ENTITY_HOTEL_REVIEWS:
//                                case SOCIAL_ENTITY_RESTAURANT_REVIEWS:
                                case SOCIAL_ENTITY_LANDMARK_REVIEWS:
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
                                                        $action="$staticaction %s 's post";	
                                                }else{
                                                        $userInfo_action = getUserInfo($from_id);	
                                                        $uslnk= userProfileLink($userInfo_action);
                                                        $action="$staticaction your post";
                                                        if( $userInfo_action['id'] != $userId ){
                                                            $action_array=array();	
                                                            $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                            $action="$staticaction %s 's post";
                                                        }

                                                }
                                        }
                                        break;
                                case SOCIAL_ENTITY_VISITED_PLACES:
                                        if($action_row_count>=1){
                                            $action="$staticaction the visited location %s";
                                        }else{
                                            $action="$staticaction $gender own visited location %s";
                                        }  
                                        if($entity_owner != $user_id ){                                                    
                                            $userInfo_action = getUserInfo($entity_owner);		
                                            $uslnk= userProfileLink($userInfo_action);                                                    
                                            $action_array = array();
                                            $action="$staticaction your visited location %s";
                                            if( $userInfo_action['id'] != $userId ){
                                                $action_array =array();
                                                $action_array[]='<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                $action="$staticaction %s 's visited location %s";
                                            }													
                                        }						
                                        break;
                                case SOCIAL_ENTITY_USER_EVENTS:
                                        if($action_row_count>=1){
                                            $action="$staticaction the event %s";
                                        }else{
                                            $action="$staticaction $gender own event %s";
                                        }  
                                        $from_id = intval($videoRecord['user_id']);						
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);		
                                                $uslnk= userProfileLink($userInfo_action);
                                                $action="$staticaction your event %s";
                                                if( $userInfo_action['id'] != $userId ){
                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                    $action="$staticaction %s 's event %s";
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
                                                $action="$staticaction %s 's channel";													
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
                                                $action="$staticaction %s 's news";													
                                        }
                                        break;
                                case SOCIAL_ENTITY_BROCHURE:
                                        $action="$staticaction their brochure";
                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                        $from_id = intval($channel_array['owner_id']);						
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                $action="$staticaction %s 's brochure";													
                                        }
                                        break;
                                case SOCIAL_ENTITY_EVENTS:
                                        $action="$staticaction their event %s";
                                        $channel_array=channelGetInfo($videoRecord['channelid']);
                                        $channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
                                        $from_id = intval($channel_array['owner_id']);						
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                $action="$staticaction %s 's event %s";													
                                        }
                                        break;
                                case SOCIAL_ENTITY_LOCATION:
                                        break;
                                case SOCIAL_ENTITY_COMMENT:
                                        break;
                                case SOCIAL_ENTITY_JOURNAL:
                                        if($action_row_count>=1){
                                            $action="$staticaction the journal %s";
                                        }else{
                                            $action="$staticaction $gender own journal %s";
                                        }  
                                        $from_id = intval($videoRecord['user_id']);						
                                        if($from_id != $action_profile['id'] ){
                                                $userInfo_action = getUserInfo($from_id);	
                                                $uslnk= userProfileLink($userInfo_action);
                                                $action="$staticaction your journal %s";
                                                if( $userInfo_action['id'] != $userId ){
                                                    $action_array=array();	
                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                    $action="$staticaction %s 's journal %s";
                                                }

                                        }
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
                            }else{
                                $action_profiletb = getUserInfo($action_row_other['action_row']['from_user']);
                                $uslnkoth= userProfileLink($action_profiletb);
                                $uslnkothname= returnUserDisplayName($action_profiletb);
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
                    $staticaction = "%s invited";                        
                    if($action_row_count>1){
                        $staticaction = "%s and %s others %s invited";
                    }else if($action_row_count==1){                            
                        $staticaction = "%s and %s invited";
                    }
                    if( $entity_type == SOCIAL_ENTITY_USER_EVENTS ) {
                            if($action_row_count>=1){
                                $action_text = "$staticaction you to join the ";		
                            }else{
                                $action_text = "$staticaction you to join ".$gender." own ";		
                            }   					
                    }else if( $entity_type == SOCIAL_ENTITY_EVENTS ) {
                            $channel_array = channelGetInfo($media_row['channelid']);
                            $action_text = "$staticaction you to join their own ";							
                    }else{
                            if($action_row_count>=1){$action_text .= '';
                                $action_text = "$staticaction you to connect to the channel %s";
                            }else{
                                $action_text = "$staticaction you to connect to ".$gender." own channel %s";
                            }   
                    }
                    $overhead_text = htmlEntityDecode($action_row['msg']);
                    if( $media_row['user_id'] == $userId ){
                            $channel_array ='';
                            if( $entity_type == SOCIAL_ENTITY_USER_EVENTS ) {
                                    $action_text = "$staticaction people to join your ";							
                            }else if( $entity_type == SOCIAL_ENTITY_EVENTS ) {
                                    $action_text = "$staticaction people to join their ";							
                            }else {
                                    $action_text = "$staticaction people to connect to their channel %s";
                            }							
                    }else if( $action_row['from_user'] != $owner_id ){
                            $channel_array ='';
                            $userInfo_action_who = getUserInfo($owner_id);					
                            $uslnk_who = userProfileLink($userInfo_action_who);
                            if( $entity_type == SOCIAL_ENTITY_USER_EVENTS ) {
                                    $action_array[] = '<a href="' . $uslnk_who . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action_who,array('max_length'=>32)).'</span></a>';
                                    $action_text = "$staticaction you to join %s 's ";
                            }else if( $entity_type == SOCIAL_ENTITY_EVENTS ) {
                                    $channel_array_action = channelGetInfo($media_row['channelid']);
                                    $channel_name = htmlEntityDecode($channel_array_action['channel_name']);
                                    if(strlen($channel_name) > 32){
                                       $channel_name = substr($channel_name,0,32).' ...';
                                    }
                                    $action_array[] = '<a href="' .ReturnLink('channel/' . $channel_array_action['channel_url'])  . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.$channel_name.'</span></a>';
                                    $action_text = "$staticaction you to join %s 's ";
                            }else {
                                    $channel_name = htmlEntityDecode($media_row['channel_name']);
                                    if(strlen($channel_name) > 32){
                                       $channel_name = substr($channel_name,0,32).' ...';
                                    }
                                    $action_array[] = '<a href="' .ReturnLink('channel/' . $media_row['channel_url'])  . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.$channel_name.'</span></a>';
                                    $action_text = "$staticaction you to connect to %s 's channel";
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
                        }else{
                            $action_profiletb = getUserInfo($action_row_other['action_row']['from_user']);
                            $uslnkoth= userProfileLink($action_profiletb);
                            $uslnkothname= returnUserDisplayName($action_profiletb);
                        }                         
                        array_unshift($action_array, '<a href="' . $uslnkoth . '" target="_blank" ><strong>'.$uslnkothname.'</strong></a>');
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
                $action_owner_id = socialActionOwner( $vfeeditem['action_type'] , $action_id , $entity_type );
                if($action_owner_id == $userId) continue;
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

        if (!($entity_type == SOCIAL_ENTITY_MEDIA || $entity_type == SOCIAL_ENTITY_ALBUM || $media_row['post_type'] == SOCIAL_POST_TYPE_PHOTO || $media_row['post_type'] == SOCIAL_POST_TYPE_VIDEO)) {
            $social_can_rate = false;
        }
        if (!($entity_type != SOCIAL_ENTITY_CHANNEL_INFO && $media_row['post_type'] != SOCIAL_POST_TYPE_LOCATION && $entity_type != SOCIAL_ENTITY_CHANNEL_SLOGAN && $entity_type != SOCIAL_ENTITY_CHANNEL_PROFILE && $entity_type != SOCIAL_ENTITY_USER_PROFILE && $entity_type != SOCIAL_ENTITY_CHANNEL_COVER)) {
            $social_can_share = false;
        }
        if ($action_type == SOCIAL_ACTION_EVENT_CANCEL) {
            $social_can_like = false;
        }
        if($action_type == SOCIAL_ACTION_FRIEND || SOCIAL_ACTION_FOLLOW){
            $social_can_rate = false;
            $social_can_share = false;
            $social_can_like = false;
            $social_can_comment = false;
        }
        $title = htmlEntityDecode($title);
        $username = returnUserDisplayName($action_profile);
        $pic = $action_profile['profile_Pic'];
        $gender = $action_profile['gender'];

        $overhead_text = htmlEntityDecode($overhead_text);

        $owner_id = $entity_owner;//socialEntityOwner($entity_type, $entity_id );

        $loop_ret_row['feed_id'] = $vfeeditem['id'];
//        $loop_ret_row['feed_ts'] = $feed_ts;
        
        //Elie
        $loop_ret_row['feed_date'] = formatDateAsString($feed_ts);
        $loop_ret_row['target_name'] = $target_name;
        $loop_ret_row['target_id'] = $target_id;
        $loop_ret_row['other_name'] = $other;
        $like_value = socialLiked ($user_id, $entity_id, $entity_type);
        $rate_value = socialRateGet($user_id, $entity_id, $entity_type);

        $loop_ret_row['like_value'] = !$like_value ? 0 : $like_value;
        $loop_ret_row['rate_value'] = $rate_value;

        $loop_ret_row['other_id'] = $otherId;
        $loop_ret_row['full_link'] = $fullLink;

        $loop_ret_row['user_id'] = $feed_user_id;
        $loop_ret_row['overhead_text'] = $overhead_text;
        $loop_ret_row['echo_text'] = $echo_text;
        $loop_ret_row['enable_shares_comments'] = $enable_shares_comments;
        $loop_ret_row['entity_id'] = $entity_id;
        $loop_ret_row['entity_type'] = $entity_type;
        $loop_ret_row['action_type'] = $vfeeditem['action_type'];

        $loop_ret_row['entity_record'] = $videoRecord;
        $loop_ret_row['cover_photo'] = $original_entity_type == SOCIAL_ENTITY_CHANNEL_COVER ?  photoReturnchannelHeader(array('header' => $videoRecord['detail_text'])) : '';
        $loop_ret_row['action_row'] = $actionRecord;
        $loop_ret_row['action_row_count'] = $vfeeditem['action_row_count'];

        $loop_ret_row['social_can_share'] = $social_can_share;
        $loop_ret_row['social_can_like'] = $social_can_like;
        $loop_ret_row['social_can_comment'] = $social_can_comment;
        $loop_ret_row['social_can_rate'] = $social_can_rate;
        $loop_ret_row['user_can_join_check'] = $user_can_join_check;

        $loop_ret_row['action'] = $action;
//        $loop_ret_row['action_array'] = $action_array;


        $loop_ret_row['action_id'] = $action_id;
        $loop_ret_row['is_visible'] = $is_visible;
        $loop_ret_row['title'] = $title;
        $loop_ret_row['description'] = $description;
        $loop_ret_row['uploaded_on'] = $uploaded_on;
        $loop_ret_row['video_photo'] = $video_photo;
        $loop_ret_row['gender'] = $gender;

        $loop_ret_row['user_name'] = $username;
        $loop_ret_row['user_profile_link'] = userProfileLink($action_profile);

        $loop_ret_row['user_profile_pic']=ReturnLink('media/tubers/' . $pic);
        
        $loop_ret_row['owner_type'] = $owner_type;
        if($owner_type == 'channel'){
            $channelInfo = channelGetInfo($owner_id);
            if($channelInfo['header'] == ''){
                $channel_header = 'media/images/channel/coverphoto.jpg';
            }
            else{
                $channel_header = 'media/channel/' . $channelInfo['id'] . '/thumb/' . $channelInfo['header'];
            }
            if($channelInfo['logo'] == ''){
                $channel_logo = 'media/tubers/tuber.jpg';
            }else{
                $channel_logo = 'media/channel/' . $channelInfo['id'] . '/thumb/' . $channelInfo['logo'];
            }
            $loop_ret_row['channel_logo'] = $channel_logo;
            $loop_ret_row['channel_cover'] = $channel_header;
        }
        
        $loop_ret_row['owner_name'] = $owner_name;
        $loop_ret_row['owner_id'] = $owner_id;
        $loop_ret_row['channel_id'] = $channelId;
        $loop_ret_row['action_row_other'] = $action_row_other;

        $ret_arr['feeds'][] = $loop_ret_row;
}
//echo json_encode($ret_arr['feeds']);exit(); 
echo json_encode($ret_arr['feeds']);