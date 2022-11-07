<?php

	$limit = 10;
//	$page = isset($_POST['page']) ? intval($_POST['page']) : 0;
//	$userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
//	$from_date = isset($_POST['from_date']) ? xss_sanitize($_POST['from_date']) : null;
//	$to_date = isset($_POST['to_date']) ? xss_sanitize($_POST['to_date']) : null;
//	$is_owner_visible = isset($_POST['is_owner_visible']) ? intval($_POST['is_owner_visible']) : 1;
	$page = intval($request->request->get('page', 0));
	$userId = intval($request->request->get('user_id', 0));
	$from_date = $request->request->get('from_date', '');
	$to_date = $request->request->get('to_date', '');
	$is_owner_visible = intval($request->request->get('is_owner_visible', 1));
	if($from_date=='') $from_date=null;
	if($to_date=='') $to_date=null;
        $userPrivacyArray= getUserNotifications($userId);
        $userPrivacyArray=$userPrivacyArray[0];
        //$page=0;        
	$options = array ( 
            'limit' => $limit, 
            'page' => $page , 
            'userid' => $userId , 
            'order' => 'id' , 
            'channel_id' => null ,
            'from_ts' => $from_date, 
            'to_ts' => $to_date, 
            'is_visible' => $is_owner_visible 
        );
	$search_string_post = $request->request->get('search_string', '');
//	if( isset($_POST['search_string']) ){
//		$options['search_string'] = db_sanitize($_POST['search_string']);
	if( $search_string_post ){
		$options['search_string'] = $search_string_post;
	} 
        $userVideos = newsfeedPageSearch( $options ); 
	$ret_arr = array();
	
	$ret_arr['status'] = 'ok';
	
	for ( $i=0; $i< count ( $userVideos ) ; $i++ ) 
	{
		$loop_ret_row = array();
		$overhead_text = '';
		$videoRecord = $userVideos[$i]['media_row'];
		$actionRecord = @$userVideos[$i]['action_row'];
		$action_id = $userVideos[$i]['action_id'];
		$is_visible = $userVideos[$i]['is_visible'];
		$entity_owner = $userVideos[$i]['owner_id'];

                
		$action_profile = $userVideos[$i];
		$gender = $action_profile['gender'];
		$gender = 'his';
		if($news['gender']=='F'){
			$gender = 'her';
		}
		$user_id = $action_profile['user_id'];
                $action_user_profile = getUserInfo($user_id);
		$action_profile = $action_user_profile;
		$username = returnUserDisplayName($action_user_profile);
		
		$video_photo = ( strstr($videoRecord['type'],'image') == null ) ? 'video' : 'photo';
		
		$valid = true;
		$feed_ts = strtotime($userVideos[$i]['feed_ts']);
		$feed_user_id = $userVideos[$i]['user_id'];
		
		//$entity_id = $videoRecord['id'];
		$entity_id = $userVideos[$i]['entity_id']; //the entity_id  for the album is not the id of the media record
		$entity_type = $userVideos[$i]['entity_type'];
		
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
                    if( ( $news['action_row']['entity_type'] == SOCIAL_ENTITY_USER_EVENTS || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_LOCATION || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_DATE || $news['action_row']['entity_type'] == SOCIAL_ENTITY_EVENTS_TIME ) && $userPrivacyArray_check['privacy_eventsharescomments'] == 0 ){
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
		switch($userVideos[$i]['action_type']){
			case SOCIAL_ACTION_UPLOAD:
				$action = "%s uploaded a new ";
                                
				$title = $videoRecord['title'];
				$description = $videoRecord['description'];
                                $description = htmlEntityDecode($description);
				switch($entity_type){
					case SOCIAL_ENTITY_USER_EVENTS:
						$action = "%s created a new event %s";					
						break;
					case SOCIAL_ENTITY_NEWS:
						$action = "%s added news";					
						break;
					case SOCIAL_ENTITY_BROCHURE:
						$action = "%s added a new brochure";					
						break;
					case SOCIAL_ENTITY_EVENTS:
						$action = '%s created a new event %s';					
						break;
					case SOCIAL_ENTITY_JOURNAL:
						$action = "%s added a new journal %s";					
						break;
					case SOCIAL_ENTITY_POST:
						$action= "%s posted the following on ".$gender." TT page:";                                                
						if( intval($videoRecord['channel_id']) !=0 ){
							$action = "%s posted the following:";
						}
						$from_id = intval($videoRecord['user_id']);
                                                $realvalue=0;
						if($from_id != $action_profile['id'] ){	
                                                    $action='%s posted the following on your TT page:';
                                                    $userInfo_action = getUserInfo($action_profile['id']);
                                                    $userInfo_action_who = getUserInfo($from_id);
                                                    $action_profile = $userInfo_action;							
                                                    $uslnk= userProfileLink($userInfo_action_who);
                                                    if( $userInfo_action_who['id'] != $userId ){
                                                        $action_array=array();
                                                        $realvalue=1;
                                                        $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action_who).'</span></a>';
                                                        $action="%s posted the following on %s 's TT page:";								
                                                    }
						}
						if( $videoRecord['post_type'] == SOCIAL_POST_TYPE_LINK ){
							$action="%s posted a link on $gender TT page";
							if( intval($videoRecord['channel_id']) !=0 ){
								$action = "%s posted a link";
							}
							if($from_id != $action_profile['id'] ){
                                                            if($realvalue==0){
                                                                $action='%s posted a link on your TT page';
                                                            }else{
                                                                $action="%s posted a link on %s 's TT page";
                                                            }								
							}
						}else if( $videoRecord['post_type'] == SOCIAL_POST_TYPE_PHOTO ){
							$action="%s posted a photo on $gender TT page";
							if( intval($videoRecord['channel_id']) !=0 ){
								$action = "%s posted a new photo";
							}
							if($from_id != $action_profile['id'] ){
                                                            if($realvalue==0){
                                                                $action='%s posted a photo on your TT page';
                                                            }else{
                                                                $action="%s posted a photo on %s 's TT page";
                                                            }								
							}							
						}else if( $videoRecord['post_type'] == SOCIAL_POST_TYPE_VIDEO ){
							$action="%s posted a video on $gender TT page";
							if( intval($videoRecord['channel_id']) !=0 ){
								$action = "%s posted a new video";
							}
							if($from_id != $action_profile['id'] ){
                                                            if($realvalue==0){
                                                                $action='%s posted a video on your TT page';
                                                            }else{
                                                                $action="%s posted a video on %s 's TT page";
                                                            }						
							}							
						}
						break;
				}
				break;
			case SOCIAL_ACTION_UPDATE:
				$action = "%s updated $gender";
				switch($entity_type){
					case SOCIAL_ENTITY_CHANNEL_SLOGAN:
						$action = "%s updated their slogan";					
						break;
					case SOCIAL_ENTITY_CHANNEL_INFO:
						$action = "%s updated their info";					
						break;
					case SOCIAL_ENTITY_CHANNEL_COVER:
						$action = "%s updated their cover photo";					
						break;
					case SOCIAL_ENTITY_CHANNEL_PROFILE:
						$action = "%s updated their profile image";					
						break;
				}
				break;
			case SOCIAL_ACTION_EVENT_CANCEL:
				if( intval($videoRecord['channelid']) !=0 ){
					$media_uri = ReturnLink('channel-events-detailed/' . $videoRecord['id']);
                                        $action_array[]='<a href="'.$media_uri.'" target="_blank"><span class="yellow_font">' . htmlEntityDecode($videoRecord['name']) . '</span></a>';
					$action = '%s cancelled their event %s';	
				}else{
					$action = "%s cancelled $gender own event";
				}
				break;
			case SOCIAL_ACTION_CONNECT:
				$channel_url = ReturnLink('channel/'.$videoRecord['channel_url']);
				$action = "%s is now connected to %s 's channel";
                                $action_array[]= '<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($videoRecord['channel_name']).'</span></a>';
				break;
			case SOCIAL_ACTION_FOLLOW:
                                $action_profile = getUserInfo($action_profile['id']);                                
				$userInfo_action = getUserInfo($entity_id);
				$uslnk= userProfileLink($userInfo_action);
				$prfpic=ReturnLink('media/tubers/' . $userInfo_action['profile_Pic']);
                                $action_array=array();
                                $action_array[]='<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'<div class="tt_link_over"><div class="tt_link_over_bk"></div><div class="tt_link_over_arrow"></div><img class="tt_link_over_pic" src="'.$prfpic.'"/></div></span></a>';
				$action = "%s is now following %s";
				break;
			case SOCIAL_ACTION_FRIEND:
                                $action_profile = getUserInfo($action_profile['id']);
				$userInfo_action = getUserInfo($entity_id);
				$uslnk= userProfileLink($userInfo_action);
				$prfpic=ReturnLink('media/tubers/' . $userInfo_action['profile_Pic']);	
                                $action_array=array();
                                $action_array[]='<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'<div class="tt_link_over"><div class="tt_link_over_bk"></div><div class="tt_link_over_arrow"></div><img class="tt_link_over_pic" src="'.$prfpic.'"/></div></span></a>';
				$action = '%s is now friends with %s';
				break;
			case SOCIAL_ACTION_SPONSOR:				
				switch($entity_type){
					case SOCIAL_ENTITY_CHANNEL:
						$channel_url = ReturnLink('channel/'.$videoRecord['channel_url']);
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($videoRecord['channel_name']).'</span></a>';
						$action = "%s sponsored %s 's channel";
						break;
					case SOCIAL_ENTITY_EVENTS:
						$action = '%s sponsored an event %s';
						break;
				}
				break;
                        case SOCIAL_ACTION_RELATION_PARENT:
                        case SOCIAL_ACTION_RELATION_SUB:
                            $channel_array = channelGetInfo($feed_user_id);
                            $channel_url_r = ReturnLink('channel/'.$videoRecord['channel_url']);
                            $channel_name_r = htmlEntityDecode($videoRecord['channel_name']);
                            if(strlen($channel_name_r) > 32){
                               $channel_name_r = substr($channel_name_r,0,32).' ...';
                            }
                            $action = '%s has accepted %s as a sub channel';
                            $action_array[]='<a href="' . $channel_url_r . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.$channel_name_r.'</span></a>';
                            if($actionRecord['relation_type']==CHANNEL_RELATION_TYPE_SUB){
                                $action_array=array();
                                $action_array[]='<a href="' . $channel_url_r . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.$channel_name_r.'</span></a>';
                                $action = '%s is now a sub channel to %s';
                            }
                        break;
			case SOCIAL_ACTION_EVENT_JOIN:
				if( intval($videoRecord['channelid']) !=0 ){
					$channel_array=channelGetInfo($videoRecord['channelid']);
					$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
					$action= "%s has joined %s 's event %s";	
				}else{
					$action = "%s joined the event %s";
				}
				break;
			case SOCIAL_ACTION_LIKE:
				$original_entity_type = $entity_type;
				//in case the like was on a comment for instance
				if( isset($userVideos[$i]['original_entity_type']) ){
					$original_entity_type = $userVideos[$i]['original_entity_type'];
					$original_entity_id = $userVideos[$i]['original_entity_id'];
				}
				switch($original_entity_type){
					case SOCIAL_ENTITY_MEDIA:
						$action = "%s liked $gender own ";
						if( intval($videoRecord['channelid']) !=0 ){
							$action = "%s liked their ";
						}
						$title = $videoRecord['title'];
						$description = $videoRecord['description'];
                                                $description = htmlEntityDecode($description);
						$from_id = intval($videoRecord['userid']);
						if($from_id != $action_profile['id'] ){
							if( intval($videoRecord['channelid']) !=0 ){
								$channel_array=channelGetInfo($videoRecord['channelid']);
								$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
								$action_array=array();
                                                                $action="%s liked %s 's " ; 
                                                                $action_array[] = '<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
							}else{
								$userInfo_action = getUserInfo($from_id);	
								$uslnk= userProfileLink($userInfo_action);
								$action='%s liked your ';
								if( $userInfo_action['id'] != $userId ){
                                                                        $action_array = array();
									$action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                        $action="%s liked %s 's ";
								}
							}
						}
						break;
					case SOCIAL_ENTITY_WEBCAM:
						$action = "%s liked ";
						break;
					case SOCIAL_ENTITY_ALBUM:
						$action = '%s liked $gender own ';
						if( intval($videoRecord['channelid']) !=0 ){
							$action = "%s liked their ";
						}
						$from_id = intval($videoRecord['userid']);
						if($from_id != $action_profile['id'] ){
							if( intval($videoRecord['channelid']) !=0 ){
								$channel_array=channelGetInfo($videoRecord['channelid']);
								$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                                                $action_array[] ='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
								$action="%s liked %s 's ";	
							}else{
								$userInfo_action = getUserInfo($from_id);	
								$uslnk= userProfileLink($userInfo_action);
								$action='%s liked your ';
								if( $userInfo_action['id'] != $userId ){
                                                                        $action_array=array();
									$action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                        $action="%s liked %s 's ";
								}
								
							}
						}
						break;
					case SOCIAL_ENTITY_CHANNEL:
						$channel_url = ReturnLink('channel/'.$videoRecord['channel_url']);
						$action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($videoRecord['channel_name']).'</span></a>';
                                                $action="%s liked %s 's channel";
                                                
						break;
					case SOCIAL_ENTITY_NEWS:
						$channel_array=channelGetInfo($videoRecord['channelid']);
						$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
						$action="%s liked %s 's news";
						break;
					case SOCIAL_ENTITY_BROCHURE:
						$channel_array=channelGetInfo($videoRecord['channelid']);
						$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                                $action_array=array();
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
						$action="%s liked %s 's brochure";
						break;
					case SOCIAL_ENTITY_EVENTS:
						$channel_array=channelGetInfo($videoRecord['channelid']);
						$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
						$action="%s liked %s 's event %s";
                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
						break;
					case SOCIAL_ENTITY_CHANNEL_SLOGAN:
						$action="%s liked their slogan";	
						$channelDetailArray=GetChannelDetailInfo($entity_id);
						$channel_array=channelGetInfo($channelDetailArray['channelid']);
						$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
						$from_id = intval($channel_array['owner_id']);						
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);	
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
							$action="%s liked %s 's slogan";													
						}
						break;
					case SOCIAL_ENTITY_CHANNEL_INFO:
						$action="%s liked their info";	
						$channelDetailArray=GetChannelDetailInfo($entity_id);
						$channel_array=channelGetInfo($channelDetailArray['channelid']);
						$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
						$from_id = intval($channel_array['owner_id']);						
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
							$action="%s liked %s 's info";												
						}
						break;
					case SOCIAL_ENTITY_CHANNEL_COVER:
						$action="%s liked their cover photo";	
						$channelDetailArray=GetChannelDetailInfo($entity_id);
						$channel_array=channelGetInfo($channelDetailArray['channelid']);
						$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
						$from_id = intval($channel_array['owner_id']);						
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);		
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'’s</span></a>';
							$action="%s liked %s 's cover photo";													
						}
						break;
					case SOCIAL_ENTITY_CHANNEL_PROFILE:
						$action="%s liked their profile image";	
						$channelDetailArray=GetChannelDetailInfo($entity_id);
						$channel_array=channelGetInfo($channelDetailArray['channelid']);
						$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
						$from_id = intval($channel_array['owner_id']);					
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
							$action="%s liked %s 's profile image";													
						}
						break;
					case SOCIAL_ENTITY_LOCATION:
						break;
					case SOCIAL_ENTITY_COMMENT:
						$action = '%s liked the comment';
						$title = $videoRecord['title'];
                                                $overhead_text = htmlEntityDecode($userVideos[$i]['original_media_row']['comment_text']);
						break;
					case SOCIAL_ENTITY_USER_PROFILE:
						$action="%s liked $gender own profile image";
						$userDetailArray=getUserDetail($entity_id);
						$from_id = intval($userDetailArray['user_id']);						
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);
							$uslnk= userProfileLink($userInfo_action);
                                                        $action="%s liked your profile image";
							if( $userInfo_action['id'] != $userId ){
                                                                $action="%s liked %s profile image";
                                                                $action_array = array();
								$action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'’s</span></a>';
							}
																				
						}
						break;
					case SOCIAL_ENTITY_POST:
						if( $videoRecord['post_type'] == SOCIAL_POST_TYPE_LOCATION ){
							$action="%s liked $gender location";
							$from_id = intval($videoRecord['user_id']);
							
							if($from_id != $action_profile['id'] ){
								$userInfo_action = getUserInfo($from_id);
								$uslnk= userProfileLink($userInfo_action);
                                                                $action="%s liked your location status";
								if( $userInfo_action['id'] != $userId ){
                                                                        $action_array=array();
									$action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                        $action="%s liked %s 's location status";
								}										
																
							}
						}else {
							$action="%s liked $gender own post";
							if( intval($videoRecord['channel_id']) !=0 ){
								$action = "%s liked their post";
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
									$action="%s liked %s 's post";	
								}else{
									$userInfo_action = getUserInfo($from_id);
									$uslnk= userProfileLink($userInfo_action);
                                                                                $action="%s liked your post";	
									if( $userInfo_action['id'] != $userId ){
                                                                            $action_array=array();	
                                                                            $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                            $action="%s liked %s 's post";	
									}
								}
							}
						}
						break;
					case SOCIAL_ENTITY_FLASH:
						break;
					case SOCIAL_ENTITY_USER_EVENTS:
						$action="%s liked $gender own event %s";
						$from_id = intval($videoRecord['user_id']);						
						if($from_id != $action_profile['id'] ){
                                                    $userInfo_action = getUserInfo($from_id);
                                                    $uslnk= userProfileLink($userInfo_action);
                                                    $where_action ='your';
                                                    $action='%s liked your event %s';
                                                    if( $userInfo_action['id'] != $userId ){
                                                        $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                        $action="%s liked %s 's event %s";
                                                    }													
						}						
						break;
					case SOCIAL_ENTITY_JOURNAL:
						$action="%s liked $gender own journal %s";
						$from_id = intval($videoRecord['user_id']);						
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);
							$uslnk= userProfileLink($userInfo_action);
							$where_action ='your';
                                                        $action="%s liked your journal %s";
							if( $userInfo_action['id'] != $userId ){
                                                            $action_array=array();	
                                                            $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                            $action="%s liked %s 's journal %s";
							}
						}
						break;
					case SOCIAL_ENTITY_SHARE:
						break;
					case SOCIAL_ENTITY_USER:
						break;
				}
				break;
			case SOCIAL_ACTION_RATE:
				$action = "%s rated a ";
				$title = $videoRecord['title'];
				$description = $videoRecord['description'];
                                $description = htmlEntityDecode($description);
				switch($entity_type){
					case SOCIAL_ENTITY_MEDIA:
						$action = "%s rated $gender own ";
						if( intval($videoRecord['channelid']) !=0 ){
							$action = "%s rated their ";
						}
						$title = $videoRecord['title'];
						$description = $videoRecord['description'];
                                                $description = htmlEntityDecode($description);
						$from_id = intval($videoRecord['userid']);
						if($from_id != $action_profile['id'] ){
							if( intval($videoRecord['channelid']) !=0 ){
								$channel_array=channelGetInfo($videoRecord['channelid']);
								$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                                                $action_array=array();
                                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
								$action="%s rated %s 's ";	
							}else{
								$userInfo_action = getUserInfo($from_id);	
								$uslnk= userProfileLink($userInfo_action);
                                                                $action="%s rated your ";
								if( $userInfo_action['id'] != $userId ){
                                                                    $action_array=array();
                                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                    $action="%s rated %s 's ";
								}
							}
						}						
						break;
					case SOCIAL_ENTITY_WEBCAM:
						$action = "%s rated ";
						break;
					case SOCIAL_ENTITY_ALBUM:						
						$title = $videoRecord['title'];
						$description = $videoRecord['description'];
                                                $description = htmlEntityDecode($description);
						$action = "%s rated $gender own ";
						if( intval($videoRecord['channelid']) !=0 ){
							$action = "%s rated their ";
						}
						$from_id = intval($videoRecord['userid']);
						if($from_id != $action_profile['id'] ){
							if( intval($videoRecord['channelid']) !=0 ){
								$channel_array=channelGetInfo($videoRecord['channelid']);
								$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
								$action_array[] = '<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                                $action="%s rated %s 's ";	
							}else{
								$userInfo_action = getUserInfo($from_id);	
								$uslnk= userProfileLink($userInfo_action);
								$action="%s rated your ";
								if( $userInfo_action['id'] != $userId ){
									$action_array=array();
                                                                        $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                        $action="%s rated %s 's ";
								}
							}
						}
						break;
					case SOCIAL_ENTITY_POST:
						$action="%s rated $gender own post";
						if( intval($videoRecord['channel_id']) !=0 ){
							$action = "%s rated their post";
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
                                                        $action="%s rated %s 's post";	
                                                    }else{
                                                        $userInfo_action = getUserInfo($from_id);	
                                                        $uslnk= userProfileLink($userInfo_action);
                                                        $action='%s rated your post';	
                                                        if( $userInfo_action['id'] != $userId ){
                                                            $action="%s rated %s 's post";	
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
				break;
			case SOCIAL_ACTION_COMMENT:
				$overhead_text = htmlEntityDecode($actionRecord['comment_text']);	
				switch($entity_type){
					case SOCIAL_ENTITY_MEDIA:
						$action = "%s commented on $gender own ";
                                                
						if( intval($videoRecord['channelid']) !=0 ){
							$action = "%s commented on their ";
                                                        $action_array=array();
						}
						$title = $videoRecord['title'];
						$description = $videoRecord['description'];
                                                $description = htmlEntityDecode($description);
						
						$from_id = intval($videoRecord['userid']);
						if($from_id != $action_profile['id'] ){
							if( intval($videoRecord['channelid']) !=0 ){
								$channel_array=channelGetInfo($videoRecord['channelid']);
								$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
								$action="%s commented on %s 's ";	
                                                                $action_array=array();
                                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
							}else{
								$userInfo_action = getUserInfo($from_id);	
								$uslnk= userProfileLink($userInfo_action);
								$where_action ='your';
                                                                $action="%s commented on your ";
								if( $userInfo_action['id'] != $userId ){
                                                                    $action_array=array();
                                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                    $action="%s commented on %s 's ";
								}
								
                                                                
							}
						}
						break;
					case SOCIAL_ENTITY_WEBCAM:
						$action = '%s commented on ';
						$description = $videoRecord['description'];
                                                $description = htmlEntityDecode($description);
						break;
					case SOCIAL_ENTITY_ALBUM:						
						$action = "%s commented on $gender own ";
						if( intval($videoRecord['channelid']) !=0 ){
							$action = "%s commented on their ";
						}
						$from_id = intval($videoRecord['userid']);
						if($from_id != $action_profile['id'] ){
							if( intval($videoRecord['channelid']) !=0 ){
								$channel_array=channelGetInfo($videoRecord['channelid']);
								$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                                                $action_array=array();
                                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'’s</span></a>';
								$action="%s commented on %s 's ";	
							}else{
								$userInfo_action = getUserInfo($from_id);	
								$uslnk= userProfileLink($userInfo_action);
                                                                $action="%s commented on your ";
								if( $userInfo_action['id'] != $userId ){
                                                                        $action_array=array();
									$action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                        $action="%s commented on %s 's ";
								}
									
							}
						}
						break;
					case SOCIAL_ENTITY_USER_PROFILE:
						$action="%s commented on $gender own profile image";
						$userDetailArray=getUserDetail($entity_id);
						$from_id = intval($userDetailArray['user_id']);						
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);		
							$uslnk= userProfileLink($userInfo_action);
                                                        $action="%s commented on your profile image";
							if( $userInfo_action['id'] != $userId ){
                                                            $action_array=array();	
                                                            $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'’s</span></a>';
                                                            $action="%s commented on %s 's profile image";
							}
																				
						}
						break;
					case SOCIAL_ENTITY_CHANNEL:
						$action="%s commented on their channel";	
						$channel_array=$videoRecord;
						$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
						$from_id = intval($channel_array['owner_id']);						
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
							$action="%s commented on %s 's channel";													
						}					
						break;
					case SOCIAL_ENTITY_NEWS:
						$action="%s commented on their news";
						$channel_array=channelGetInfo($videoRecord['channelid']);
						$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
						$from_id = intval($channel_array['owner_id']);						
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
							$action="%s commented on %s 's news";													
						}
						break;
					case SOCIAL_ENTITY_BROCHURE:
						$action="%s commented on their brochure";
						$channel_array=channelGetInfo($videoRecord['channelid']);
						$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
						$from_id = intval($channel_array['owner_id']);						
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
							$action="%s commented on %s 's brochure";													
						}
						break;
					case SOCIAL_ENTITY_EVENTS:
						$action="%s commented on their event %s";
						$channel_array=channelGetInfo($videoRecord['channelid']);
						$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
						$from_id = intval($channel_array['owner_id']);						
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
							$action="%s commented on %s 's event %s";													
						}
						break;
					case SOCIAL_ENTITY_CHANNEL_SLOGAN:
						$action="%s commented on their slogan";	
						$channelDetailArray=GetChannelDetailInfo($entity_id);
						$channel_array=channelGetInfo($channelDetailArray['channelid']);
						$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
						$from_id = intval($channel_array['owner_id']);						
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
							$action="%s commented on %s 's slogan";													
						}
						break;
					case SOCIAL_ENTITY_CHANNEL_INFO:
						$action="%s commented on their info";	
						$channelDetailArray=GetChannelDetailInfo($entity_id);
						$channel_array=channelGetInfo($channelDetailArray['channelid']);
						$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
						$from_id = intval($channel_array['owner_id']);						
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
							$action="%s commented on %s 's info";												
						}
						break;
					case SOCIAL_ENTITY_CHANNEL_COVER:
						$action="%s commented on their cover photo";	
						$channelDetailArray=GetChannelDetailInfo($entity_id);
						$channel_array=channelGetInfo($channelDetailArray['channelid']);	
						$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
						$from_id = intval($channel_array['owner_id']);						
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
							$action="%s commented on %s 's cover photo";													
						}
						break;
					case SOCIAL_ENTITY_CHANNEL_PROFILE:
						$action="%s commented on their profile image";	
						$channelDetailArray=GetChannelDetailInfo($entity_id);
						$channel_array=channelGetInfo($channelDetailArray['channelid']);
						$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
						$from_id = intval($channel_array['owner_id']);					
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
							$action="%s commented on %s 's profile image";													
						}
						break;
					case SOCIAL_ENTITY_POST:
						if( $videoRecord['post_type'] == SOCIAL_POST_TYPE_LOCATION ){						
							$action="%s commented on $gender location";
							$from_id = intval($videoRecord['from_id']);
							if($from_id==0) $from_id = intval($videoRecord['user_id']);				
							
							if($from_id != $action_profile['id'] ){
								$userInfo_action = getUserInfo($from_id);		
								$uslnk= userProfileLink($userInfo_action);
                                                                $action="%s commented on your location status";
								if( $userInfo_action['id'] != $userId ){
                                                                    $action_array=array();	
                                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                        $action="%s commented on %s 's location status";
								}
																
							}
						}else {
							$action="%s commented on $gender own post";
							if( intval($videoRecord['channel_id']) !=0 ){
								$action = "%s commented on their post";
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
                                                                        $action="%s commented on %s 's post";	
								}else{
									$userInfo_action = getUserInfo($from_id);		
									$uslnk= userProfileLink($userInfo_action);
									$action='%s commented on your post';
									if( $userInfo_action['id'] != $userId ){
                                                                                $action_array=array();
										$action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'’s</span></a>';
                                                                                $action="%s commented on %s 's post";

									}
									
								}
							}
						}
						break;
					case SOCIAL_ENTITY_LOCATION:
						break;
					case SOCIAL_ENTITY_USER_EVENTS:
						$action="%s commented on $gender own event %s";
						$from_id = intval($videoRecord['user_id']);						
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);		
							$uslnk= userProfileLink($userInfo_action);							
                                                        $action="%s commented on your event %s";
							if( $userInfo_action['id'] != $userId ){
                                                            $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                            $action="%s commented on %s 's event %s";
							}													
						}						
						break;
					case SOCIAL_ENTITY_COMMENT:
						break;
					case SOCIAL_ENTITY_FLASH:
						break;
					case SOCIAL_ENTITY_JOURNAL:
						$action="%s commented on $gender own journal %s";
						$from_id = intval($videoRecord['user_id']);						
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);		
							$uslnk= userProfileLink($userInfo_action);
							$action="%s commented on your journal %s";
							if( $userInfo_action['id'] != $userId ){
								$action_array=array();
                                                                $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                $action="%s commented on %s 's journal %s";
							}
																				
						}
						break;
					case SOCIAL_ENTITY_SHARE:
						break;
					case SOCIAL_ENTITY_USER:
						break;
				}
				break;
			case SOCIAL_ACTION_SHARE:
				$feed_user_id = $actionRecord['from_user'];
				$overhead_text = htmlEntityDecode($actionRecord['msg']);
				$overhead_text = (strlen($overhead_text) > 153) ? substr($overhead_text,0,150).'...' : $overhead_text;
				switch($entity_type){
					case SOCIAL_ENTITY_MEDIA:
						$who = getUserInfo($actionRecord['from_user']);						
						$action_profile = $who;
						$action = "%s shared $gender own ";
						if( intval($videoRecord['channelid']) !=0 ){
							$action = "%s shared their ";
						}
						$title = $videoRecord['title'];
						$description = $videoRecord['description'];
                                                $description = htmlEntityDecode($description);
						$from_id = intval($videoRecord['userid']);
						if($from_id != $action_profile['id'] ){
							if( intval($videoRecord['channelid']) !=0 ){
								$channel_array=channelGetInfo($videoRecord['channelid']);
								$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
                                                                $action_array=array();
                                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
								$action="%s shared %s 's ";
							}else{
								$userInfo_action = getUserInfo($from_id);	
								$uslnk= userProfileLink($userInfo_action);
								$action_array=array();
                                                                $action='%s shared your ';
								if( $userInfo_action['id'] != $userId ){
									$action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                        $action="%s shared %s 's ";
								}
							}
						}
						break;
					case SOCIAL_ENTITY_WEBCAM:
						$who = getUserInfo($actionRecord['from_user']);
						$action = "%s shared ";						
						$action_profile = $who;
						$description = $videoRecord['description'];
                                                $description = htmlEntityDecode($description);
						break;
					case SOCIAL_ENTITY_ALBUM:
						$who = getUserInfo($actionRecord['from_user']);
						
						$action_profile = $who;
						$title = $videoRecord['title'];
						$description = $videoRecord['description'];
                                                $description = htmlEntityDecode($description);
						$action = "%s shared $gender own ";
						if( intval($videoRecord['channelid']) !=0 ){
							$action = "%s shared their ";
						}
						$from_id = intval($videoRecord['userid']);
						if($from_id != $action_profile['id'] ){
							if( intval($videoRecord['channelid']) !=0 ){
								$channel_array=channelGetInfo($videoRecord['channelid']);
								$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);
								$action_array=array();
                                                                $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
                                                                $action="%s shared %s 's ";	
							}else{
								$userInfo_action = getUserInfo($from_id);	
								$uslnk= userProfileLink($userInfo_action);
                                                                $action="%s shared your ";
								if( $userInfo_action['id'] != $userId ){
                                                                    $action_array=array();	
                                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                    $action="%s shared %s 's ";
								}
							}
						}
						break;
					case SOCIAL_ENTITY_POST:
						$action="%s shared $gender own post";
						if( intval($videoRecord['channel_id']) !=0 ){
							$action = "%s shared their post";
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
								$action="%s shared %s 's post";	
							}else{
								$userInfo_action = getUserInfo($from_id);	
								$uslnk= userProfileLink($userInfo_action);
                                                                $action="%s shared your post";
								if( $userInfo_action['id'] != $userId ){
                                                                    $action_array=array();	
                                                                    $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                                    $action="%s shared %s 's post";
                                                                }
								
							}
						}
						break;
					case SOCIAL_ENTITY_VISITED_PLACES:
						$action="%s shared $gender own visited location %s";
                                                if($entity_owner != $user_id ){                                                    
                                                    $userInfo_action = getUserInfo($entity_owner);		
                                                    $uslnk= userProfileLink($userInfo_action);                                                    
                                                    $action_array = array();
                                                    $action="%s shared your visited location %s";
                                                    if( $userInfo_action['id'] != $userId ){
                                                        $action_array =array();
                                                        $action_array[]='<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                        $action="%s shared %s 's visited location %s";
                                                    }													
						}						
						break;
					case SOCIAL_ENTITY_USER_EVENTS:
						$action="%s shared $gender own event %s";
						$from_id = intval($videoRecord['user_id']);						
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);		
							$uslnk= userProfileLink($userInfo_action);
							$action="%s shared your event %s";
							if( $userInfo_action['id'] != $userId ){
                                                            $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                            $action="%s shared %s 's event %s";
							}													
						}						
						break;
					case SOCIAL_ENTITY_CHANNEL:
						$action="%s shared their channel";	
						$channel_array=$videoRecord;
						$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
						$from_id = intval($channel_array['owner_id']);						
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
							$action="%s shared %s 's channel";													
						}					
						break;
					case SOCIAL_ENTITY_NEWS:
						$action="%s shared their news";
						$channel_array=channelGetInfo($videoRecord['channelid']);
						$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
						$from_id = intval($channel_array['owner_id']);						
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
							$action="%s shared %s 's news";													
						}
						break;
					case SOCIAL_ENTITY_BROCHURE:
						$action="%s shared their brochure";
						$channel_array=channelGetInfo($videoRecord['channelid']);
						$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
						$from_id = intval($channel_array['owner_id']);						
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
							$action="%s shared %s 's brochure";													
						}
						break;
					case SOCIAL_ENTITY_EVENTS:
						$action="%s shared their event %s";
						$channel_array=channelGetInfo($videoRecord['channelid']);
						$channel_url = ReturnLink('channel/'.$channel_array['channel_url']);	
						$from_id = intval($channel_array['owner_id']);						
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);
                                                        $action_array=array();
                                                        $action_array[]='<a href="' . $channel_url . '" target="_blank"><span class="yellow_font">'.htmlEntityDecode($channel_array['channel_name']).'</span></a>';
							$action="%s shared %s 's event %s";													
						}
						break;
					case SOCIAL_ENTITY_LOCATION:
						break;
					case SOCIAL_ENTITY_COMMENT:
						break;
					case SOCIAL_ENTITY_FLASH:
						break;
					case SOCIAL_ENTITY_JOURNAL:
						$action="%s shared $gender own journal %s";
						$from_id = intval($videoRecord['user_id']);						
						if($from_id != $action_profile['id'] ){
							$userInfo_action = getUserInfo($from_id);	
							$uslnk= userProfileLink($userInfo_action);
                                                        $action="%s shared your journal %s";
							if( $userInfo_action['id'] != $userId ){
                                                            $action_array=array();	
                                                            $action_array[] = '<a href="' . $uslnk . '" target="_blank" class="tt_link_a"><span class="yellow_font tt_link_span">'.returnUserDisplayName($userInfo_action).'</span></a>';
                                                            $action="%s shared %s 's journal %s";
							}
																				
						}
						break;
					case SOCIAL_ENTITY_SHARE:
						break;
					case SOCIAL_ENTITY_USER:
						break;
				}
				break;
			default:
				$valid = false;
				break;
		}
		
		//if(!$valid) continue;
		if( is_null($action) ){
			//echo $userVideos[$i]['id'] . ' - ' . $userVideos[$i]['action_type'] . ' - ' . $entity_type;
			//exit;
			//continue;
		}
		if( $userVideos[$i]['action_type'] == SOCIAL_ACTION_SHARE || $userVideos[$i]['action_type'] == SOCIAL_ACTION_INVITE || $userVideos[$i]['action_type'] == SOCIAL_ACTION_SPONSOR ){
			$action_owner_id = socialActionOwner( $userVideos[$i]['action_type'] , $action_id , $entity_type );
			if($action_owner_id == $userId) continue;
		}else if($userVideos[$i]['action_type'] == SOCIAL_ACTION_RELATION_SUB || $userVideos[$i]['action_type'] == SOCIAL_ACTION_RELATION_PARENT){
                    $current_row = channelGetInfo($feed_user_id);
                    if($current_row['owner_id'] == $userId) continue;
                }
		
		$title = htmlEntityDecode($title);
		$username = returnUserDisplayName($action_profile);
		$pic = $action_profile['profile_Pic'];
		$gender = $action_profile['gender'];
                
                $overhead_text = htmlEntityDecode($overhead_text);
		
		$owner_id = socialEntityOwner($entity_type, $entity_id );
		
		$loop_ret_row['feed_id'] = $userVideos[$i]['id'];
		$loop_ret_row['feed_ts'] = $feed_ts;
		
		$loop_ret_row['user_id'] = $feed_user_id;
		$loop_ret_row['overhead_text'] = $overhead_text;
		$loop_ret_row['enable_shares_comments'] = $enable_shares_comments;
		$loop_ret_row['entity_id'] = $entity_id;
		$loop_ret_row['entity_type'] = $entity_type;
		$loop_ret_row['action_type'] = $userVideos[$i]['action_type'];
		
		$loop_ret_row['entity_record'] = $videoRecord;
		$loop_ret_row['action_row'] = $actionRecord;
		
		$loop_ret_row['social_can_share'] = $social_can_share;
                $loop_ret_row['social_can_like'] = $social_can_like;
		$loop_ret_row['social_can_comment'] = $social_can_comment;
		$loop_ret_row['social_can_rate'] = $social_can_rate;
		$loop_ret_row['user_can_join_check'] = $user_can_join_check;
		
		$loop_ret_row['action'] = $action;
		$loop_ret_row['action_array'] = $action_array;
                
                
		$loop_ret_row['action_id'] = $action_id;
		$loop_ret_row['is_visible'] = $is_visible;
		$loop_ret_row['title'] = $title;
		$loop_ret_row['description'] = $description;
		$loop_ret_row['video_photo'] = $video_photo;
                $loop_ret_row['gender'] = $gender;
		
		$loop_ret_row['user_name'] = $username;
		$loop_ret_row['user_profile_link'] = userProfileLink($action_profile);
		
		$loop_ret_row['user_profile_pic']=ReturnLink('media/tubers/' . $pic);
		
		$loop_ret_row['owner_id'] = $owner_id;
		
		$ret_arr['feeds'][] = $loop_ret_row;
	}
	
	
?>
