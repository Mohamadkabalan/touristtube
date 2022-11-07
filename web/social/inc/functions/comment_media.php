<?php
/**
 * comment on media functions
 * @todo Move to <b>videos.php</b>
 * @see videos
 * @package comment_media
 */

/**
 * Add a comment to a video
 * @param integer $user_id the person making the comment
 * @param integer $video_id the video being commented on
 * @param string $text the comment text
 */
function commentAdd($user_id, $video_id, $text) {
	$comment_id = socialCommentAdd($user_id, $video_id, SOCIAL_ENTITY_MEDIA , $text, 0);
	if ($comment_id){
		
		if($text[0] == '@'){
			$i = 1;
			while( ($text[$i] != ' ' ) && ($text[$i] != ':' ) ){
				$reply_to .= $text[$i];
				$i++;
			}
			$reply_to = db_sanitize($reply_to);
			$reply_to_id = userFindByUsername($reply_to);

			if( ($reply_to_id != false) && (userNotifyOnReply($reply_to_id)) ){
				emailCommentReply($reply_to_id, $user_id);
				
				iosPush($reply_to_id, "Someone replied to your comment");
			}
		}
		
		$owner_id = videoGetOwner($video_id);
		if( $owner_id ){
			if( userNotifyOnComment($owner_id) ){
				emailVideoComment($owner_id, $user_id);
			}
		}

		return $comment_id;
	}else
		return false;
}

/**
 * edits a comment
 * @param integer $id the comment id
 * @param string $text the new comment text
 * @return boolean true|false if success or fail 
 */
function commentEdit($id, $text){
	return socialCommentEdit($id,$text);
	
}

/**
 * Delete a comment
 * @param integer $id the id of the comment to be deleted
 * @return boolean true|false if success|fail   
 */
function commentDelete($id) {
	socialCommentDelete($id);
}

/**
 * the owner of the comment
 * @param integer $id the comment id
 * return false | integer the owner user_id or flase if invalid|failed
 */
function commentOwner($id){
	return socialCommentOwner($id);
}

/**
 * the video of the comment
 * @param integer $id the comment id
 * return false | integer the owner user_id or flase if invalid|failed
 */
function commentVideo($id){
	return socialCommentEntityId($id);
}

/**
 * Unpublish a comment so it doesnt show
 * @param type $id the id of the comment to be un published
 */
function commentDisable($id){
	return socialCommentDisable($id);
}

/**
 * gets the vote of a user on a comment
 * @param integer $comment_id
 * @param integer $user_id 
 */
function commentVoted($comment_id,$user_id){
	return socialLiked($user_id,$comment_id,SOCIAL_ENTITY_COMMENT);
}

/**
 * Votes on a comment (up or down)
 * @param integer $comment_id the comment to vote on
 * @param integer $user_id the user voting
 * @param integer $up_down up (1) or down (-1)
 */
function commentVote($comment_id, $user_id, $up_down) {
	//user can only have one vote
	$old_like = commentVoted($comment_id, $user_id);
	if (!$old_like) {
		
		socialLikeAdd($user_id, $comment_id, SOCIAL_ENTITY_COMMENT, $up_down, null);
		
	}else {
		if ($old_like == $up_down)
			return false;
		
		$ret = socialLikeEdit($user_id, $comment_id, SOCIAL_ENTITY_COMMENT, $up_down);
	}
	
	return true;
}

/**
 * checks if a user already marked a comment as spam
 * @param integer $reporter_id the reporter's user id
 * @param integer $comment_id the comment's id
 * @return boolean true|false if the comment has already been reported
 */
function commentReported($reporter_id,$comment_id){
	return socialCommentReported($reporter_id,$comment_id);
}

/**
 * reports a comment as spam
 * @param type $reporter_id the user id of the reporter
 * @param type $comment_id the comment's id
 * @return boolean true|false if success|fail  
 */
function commentReportSpam($reporter_id,$comment_id){
	return socialCommentReportSpam($reporter_id,$comment_id);
}