<?php
	header("content-type: application/xml; charset=utf-8");  
//	session_id($_REQUEST['S']);
$submit_post_get = array_merge($request->query->all(),$request->request->all());
	session_id($submit_post_get['S']);
	$expath    = '../';
	
	include_once("../heart.php");
	
	$userId = userGetID();
	$userInfo   = getUserInfo ( $userId );
	
//	$page = isset($_GET['page']) ? intval($_GET['page']) - 1 : 0;
//	$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
	$page = intval($request->query->get('page',0));
	$limit = intval($request->query->get('limit',10));
	
	$options = array ( 'limit' => $limit, 'page' => $page , 'userid' => $userId , 'order' => 'd' );
	
	$userFeeds = newsfeedSearch( $options );
	
	$res = "<feeds>";
	if ( $userFeeds )
	{
		foreach ($userFeeds as $userFeed)
		{
			$videoRecord = $userFeed['media_row'];
			$commentRecord = @$userFeed['comment_row'];
			$videoLikeRecord = @$userFeed['media_like_row'];
			
			$url = ( strstr($videoRecord['type'],'image') == null ) ? ReturnVideoUri($videoRecord) : ReturnPhotoUri($videoRecord);
			
			//TODO move these to the search function to remove redundency
			//Tell mobile Departement when you do so, because we have it in api/profile/newsfeed.php
			$video_user_info = getUserInfo($userFeed['user_id']);
			
			$username = $video_user_info['YourUserName'];
			
			$video_photo = ( strstr($videoRecord['type'],'image') == null ) ? 'video' : 'photo';
			
			$date = date ( 'Y-m-d',  strtotime( $userFeed['feed_ts'] ));
			$title = "";
			$desc = "";
			switch($userFeed['feed_type']){
				case 'upload_media':
					$title = $username . " uploaded a new " . $video_photo;
					//$desc = '' . $videoRecord['title'] . ' ' . $videoRecord['description'] . '';
					$desc = $videoRecord['title'] . ' ' . $videoRecord['description'];
					//$date = date ( 'd-m-Y',  strtotime( $videoRecord['pdate'] ));
					break;
				case 'like_media':
					$title = $username . " likes a " . $video_photo;
					$desc = $username . " likes the " . $video_photo . ' ' . $videoRecord['title'] . '';
					//$date = date ( 'd-m-Y',  strtotime( $videoLikeRecord['like_date'] ));
					break;
				case 'like_comment':
					$title = $username . ' likes a comment';
					$desc = $username . ' likes the comment on the ' . $video_photo . ' ' . $videoRecord['title'] . ' : ';
					$desc .= '' . $commentRecord['comment_text'] . '';
					//$date = date ( 'd-m-Y',  strtotime( $commentRecord['comment_date'] ));
					break;
				case 'comment_media':
					$title = $username . ' posted a comment';
					$desc =  $username . ' posted a comment on the ' . $video_photo . ' ' . $videoRecord['title'] . ' : ';
					$desc .= '' . $commentRecord['comment_text'] . '';
					//$date = date ( 'd-m-Y',  strtotime( $commentRecord['comment_date'] ));
					break;
				case 'like_webcam':
					$title = $username . " likes a live feed";
					$desc = $username . " likes the live feed " . ' ' . $videoRecord['name'] . ' ';
					break;
				case 'comment_webcam':
					$title = $username . ' posted a comment';
					$desc = $username . ' posted a comment on the live feed' . ' ' . $videoRecord['name'] . ' : ';
					$desc .= '' . $commentRecord['comment_text'] . ' ';
					break;
				case 'like_comment_webcam':
					$title = $username . ' likes a comment';
					$desc = $username . ' likes the comment on the live feed' . ' ' . $videoRecord['name'] . ' : ';
					$desc .= '' . $commentRecord['comment_text'] . '';
					break;
				default:
					break;
			}
			
			if( in_array($userFeed['feed_type'],array('like_comment','comment','upload','like_media')) ){
				$thumb = ReturnLink('get_media.php?id=' . $videoRecord['id'] . '&t=i');
				$likes = $videoRecord['up_votes'];
				$views = $videoRecord['nb_views'];
				$rating = ceil($videoRecord['rating']);
				$nb_rating = $videoRecord['nb_ratings'];
				$nb_comments = $videoRecord['nb_comments'];
				$id = $videoRecord['id'];
			}else if( in_array($userFeed['feed_type'],array('comment_webcam','like_comment_webcam','like_webcam')) ){
				$url = ReturnWebcamUri($videoRecord);
				$thumb = ReturnLink($CONFIG['cam']['uploadPath']. $videoRecord['url'] . '.jpg');
				$likes = $videoRecord['up_votes'];
				$views = $videoRecord['n_views'];
				$rating = ceil($videoRecord['rating_value']);
				$nb_rating = $videoRecord['n_rating'];
				$nb_comments = $videoRecord['n_comments'];
				$date = date('d-m-Y');
				$id = $videoRecord['id'];
			}
			$res .= "<feed feed_type='".$userFeed['feed_type']."' media_type='".$video_photo."'>";
			$res .= "<title>".$title."</title>";
			$res .= "<description>".$desc."</description>";
			$res .= "<url>".urlencode($url)."</url>";
			$res .= "<thumb>".urlencode($thumb)."</thumb>";
			$res .= "<likes>".$likes."</likes>";
			$res .= "<views>".$views."</views>";
			$res .= "<rating>".$rating."</rating>";
			$res .= "<nb_rating>".$nb_rating."</nb_rating>";
			$res .= "<nb_comments>".$nb_comments."</nb_comments>";
			$res .= "<date>".$date."</date>";
			$res .= "<feed_id>".$id."</feed_id>";
			$res .= "<obj_id>".$videoRecord['id']."</obj_id>";
			$res .= "</feed>";
		}
	}
	$res .= "</feeds>";
	echo $res;