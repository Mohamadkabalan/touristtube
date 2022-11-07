<?php

	$limit = MEDIA_LIST_MODE_RESULTS;
//	$page = isset($_POST['page']) ? intval($_POST['page']) : 0;
//	$channel_id = isset($_POST['channel_id']) ? intval($_POST['channel_id']) : 0;
	$page = intval($request->request->get('page', 0));
	$channel_id =  intval($request->request->get('channel_id', 0));

	$options = array ( 'limit' => $limit, 'page' => $page , 'channel_id' => $channel_id , 'order' => 'd' );

//	if( isset($_POST['search_string']) ){
//		$options['search_string'] = db_sanitize($_POST['search_string']);
        $search_string_post = $request->request->get('search_string', '');
	if( $search_string_post!='' ){
		$options['search_string'] = $search_string_post;
	}

	$userVideos = newsfeedSearch( $options );
	
	$ret_arr = array();
	
	$ret_arr['status'] = 'ok';
	
	for ( $i=0; $i< count ( $userVideos ) ; $i++ ) 
	{
		$loop_ret_row = array();
		
		$videoRecord = $userVideos[$i]['media_row'];
		$actionRecord = @$userVideos[$i]['action_row'];
		
		$action_profile = $userVideos[$i];
		$username = returnUserDisplayName($action_profile);
		
		$video_photo = ( strstr($videoRecord['type'],'image') == null ) ? 'video' : 'photo';
		
		$valid = true;
		$feed_ts = strtotime($userVideos[$i]['feed_ts']);
		$feed_user_id = $userVideos[$i]['user_id'];
		
		//$entity_id = $videoRecord['id'];
		$entity_id = $userVideos[$i]['entity_id']; //the entity_id  for the album is not the id of the media record
		$entity_type = $userVideos[$i]['entity_type'];
		
		$action = null;
		
		switch($userVideos[$i]['action_type']){
			case SOCIAL_ACTION_UPLOAD:
				$action = " uploaded a new " . $video_photo;
				$title = $videoRecord['title'];
				$description = $videoRecord['description'];
                                $description = htmlEntityDecode($description);
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
						$action = " likes a " . $video_photo;
						$title = $videoRecord['title'];
						$description = $videoRecord['description'];
                                                $description = htmlEntityDecode($description);
						break;
					case SOCIAL_ENTITY_WEBCAM:
						$action = " likes a live feed";
						$title = $videoRecord['title'];
						$description = $videoRecord['description'];
                                                $description = htmlEntityDecode($description);
						break;
					case SOCIAL_ENTITY_ALBUM:
						$action = ' likes an album';
						$desc =  $username . ' likes the album <span style="font-style:italic">' . htmlEntityDecode($videoRecord['title']) . '</span><br/>';
						break;
						break;
					case SOCIAL_ENTITY_LOCATION:
						break;
					case SOCIAL_ENTITY_COMMENT:
						$action = ' likes a comment';
						$title = $videoRecord['title'];
						$description = $videoRecord['description'];
                                                $description = htmlEntityDecode($description);
						break;
					case SOCIAL_ENTITY_FLASH:
						break;
					case SOCIAL_ENTITY_JOURNAL:
						break;
					case SOCIAL_ENTITY_JOURNAL_ITEM:
						break;
					case SOCIAL_ENTITY_SHARE:
						break;
					case SOCIAL_ENTITY_USER:
						break;
				}
				break;
			case SOCIAL_ACTION_RATE:
				switch($entity_type){
					case SOCIAL_ENTITY_MEDIA:
						
						break;
					case SOCIAL_ENTITY_WEBCAM:
						$action = " rated a live feed";
						$title = $videoRecord['title'];
						$description = $videoRecord['description'];
                                                $description = htmlEntityDecode($description);
						break;
					case SOCIAL_ENTITY_ALBUM:
						$action = " rated a album";
						$title = $videoRecord['title'];
						$description = $videoRecord['description'];
                                                $description = htmlEntityDecode($description);
						break;
					case SOCIAL_ENTITY_LOCATION:
						break;
					case SOCIAL_ENTITY_COMMENT:
						break;
					case SOCIAL_ENTITY_FLASH:
						break;
					case SOCIAL_ENTITY_JOURNAL:
						break;
					case SOCIAL_ENTITY_JOURNAL_ITEM:
						break;
					case SOCIAL_ENTITY_SHARE:
						break;
					case SOCIAL_ENTITY_USER:
						break;
				}
				$action = " rated a " . $video_photo;
				$title = $videoRecord['title'];
				$description = $videoRecord['description'];
                                $description = htmlEntityDecode($description);
				break;
			case SOCIAL_ACTION_COMMENT:
				switch($entity_type){
					case SOCIAL_ENTITY_MEDIA:
						$action = ' posted a comment';
						$title = $videoRecord['title'];
						$description = $videoRecord['description'];
                                                $description = htmlEntityDecode($description);
						break;
					case SOCIAL_ENTITY_WEBCAM:
						$action = ' posted a comment';
						$title = $videoRecord['title'];
						$description = $videoRecord['description'];
                                                $description = htmlEntityDecode($description);
						break;
					case SOCIAL_ENTITY_ALBUM:
						$action = ' posted a comment';
						$desc =  $username . ' posted a comment on the album <span style="font-style:italic">' . htmlEntityDecode($videoRecord['title']) . '</span>:<br/>';
                                                $val_db = htmlEntityDecode($actionRecord['comment_text']);
						$desc .= '<span class="NewsFeedCommentText">' . cut_sentence_length($val_db,100) . '</span>';
						break;
					case SOCIAL_ENTITY_LOCATION:
						break;
					case SOCIAL_ENTITY_COMMENT:
						break;
					case SOCIAL_ENTITY_FLASH:
						break;
					case SOCIAL_ENTITY_JOURNAL:
						break;
					case SOCIAL_ENTITY_JOURNAL_ITEM:
						break;
					case SOCIAL_ENTITY_SHARE:
						break;
					case SOCIAL_ENTITY_USER:
						break;
				}
				break;
			case SOCIAL_ACTION_SHARE:
				$feed_user_id = $actionRecord['from_user'];
				switch($entity_type){
					case SOCIAL_ENTITY_MEDIA:
						$who = getUserInfo($actionRecord['from_user']);
						$action = " shared a $video_photo";
						if( $actionRecord['from_user'] != $userId){
							$action .= " with you";
						}
						$action_profile = $who;
						$title = $videoRecord['title'];
						$description = $videoRecord['description'];
                                                $description = htmlEntityDecode($description);
						break;
					case SOCIAL_ENTITY_WEBCAM:
						$who = getUserInfo($actionRecord['from_user']);
						$action = " shared a live cam";
						if( $actionRecord['from_user'] != $userId){
							$action .= " with you";
						}
						$action_profile = $who;
						$title = $videoRecord['title'];
						$description = $videoRecord['description'];
                                                $description = htmlEntityDecode($description);
						break;
					case SOCIAL_ENTITY_ALBUM:
						$who = getUserInfo($actionRecord['from_user']);
						$action = " shared an album";
						if( $actionRecord['from_user'] != $userId){
							$action .= " with you";
						}
						$action_profile = $who;
						$title = $videoRecord['title'];
						$description = $videoRecord['description'];
                                                $description = htmlEntityDecode($description);
						break;
					case SOCIAL_ENTITY_LOCATION:
						break;
					case SOCIAL_ENTITY_COMMENT:
						break;
					case SOCIAL_ENTITY_FLASH:
						break;
					case SOCIAL_ENTITY_JOURNAL:
						break;
					case SOCIAL_ENTITY_JOURNAL_ITEM:
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
		
		if(!$valid) continue;
		if( is_null($action) ){
			//echo $userVideos[$i]['id'] . ' - ' . $userVideos[$i]['action_type'] . ' - ' . $entity_type;
			//exit;
			continue;
		}
		
		$title = htmlEntityDecode($title);
		$username = returnUserDisplayName($action_profile);
		$pic = $action_profile['profile_Pic'];
		$gender = $action_profile['gender'];
		
		$owner_id = socialEntityOwner($entity_type, $entity_id );
		
		$loop_ret_row['feed_id'] = $userVideos[$i]['id'];
		
		$loop_ret_row['feed_id'] = $userVideos[$i]['id'];
		$loop_ret_row['feed_ts'] = $feed_ts;
		
		$loop_ret_row['user_id'] = $feed_user_id;
		$loop_ret_row['entity_id'] = $entity_id;
		$loop_ret_row['entity_type'] = $entity_type;
		
		$loop_ret_row['entity_record'] = $videoRecord;
		
		$loop_ret_row['action'] = $action;
		$loop_ret_row['title'] = $title;
		$loop_ret_row['description'] = $description;
		
		$loop_ret_row['user_name'] = $username;
		$loop_ret_row['user_profile_link'] = userProfileLink($action_profile);
		if($pic){
			$loop_ret_row['user_profile_pic'] = ReturnLink('media/tubers/small_' . $pic);
		}else{
			if($gender == 'M') $loop_ret_row['user_profile_pic'] = ReturnLink('media/tubers/he.jpg');
			else $loop_ret_row['user_profile_pic'] = ReturnLink('media/tubers/she.jpg');
		}
		
		$loop_ret_row['owner_id'] = $owner_id;
		
		$ret_arr['feeds'][] = $loop_ret_row;
	}
	
	
?>
