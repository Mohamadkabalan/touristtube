<?php
/*
* Returns the comments for a specific event.
* Param id: The entity id
* Param entity: The entity ID.
* Param [limit]: Optional, the max rows to get, default 100.
* Param [page]: Optional, the current page.
*/
	
	$expath = "../";			
	header("content-type: application/xml; charset=utf-8");  
	include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
	
//	$id = intval( $_REQUEST['id'] );
//	$entity_type = xss_sanitize( $_REQUEST['entity'] );
//	if(isset($_REQUEST['limit']))
//		$limit = intval( $_REQUEST['limit'] );
	$id = intval( $submit_post_get['id'] );
	$entity_type = $submit_post_get['entity'] ;
	if(isset($submit_post_get['limit']))
		$limit = intval( $submit_post_get['limit'] );
	else
		$limit = 100;
//	if(isset($_REQUEST['page']))
//		$page = intval( $_REQUEST['page'] );
	if(isset($submit_post_get['page']))
		$page = intval( $submit_post_get['page'] );
	else
		$page = 0;
	
	
	$options = array(
					'page' => $page,
					'limit' => $limit,
					'orderby' => 'comment_date',
					'order' => 'd',
					'entity_id' => $id,
					'published' => 1,
					'is_visible' => 1,
					'entity_type' => $entity_type
					);
	$allComments = socialCommentsGet($options);
	if($allComments)
		$comments_count = count($allComments);
	else
		$comments_count = 0;
	
	if($comments_count > 0):
	
		// Start the XML section.
		$output .= "
			<event_comments>
				<count>".$comments_count."</count>
				<comments_details>";
				
		// Fill in the details for every comment.
		foreach($allComments as $comment):
		/*code changed by sushma mishra on 30-sep-2015 to get fullname using returnUserDisplayName function starts from here*/
			$userDetail = getUserInfo($comment['user_id']);
			$output .= "<comment>
							<id>" . $comment['id'] . "</id>
							<user_id>" . $comment['user_id'] . "</user_id>
							<username>" . $comment['YourUserName'] . "</username>
							<user>" . returnUserDisplayName($userDetail) . "</user>
							<display_fullname>" . $comment['display_fullname'] . "</display_fullname>
							<userprofilepic>media/tubers/" . $comment['profile_Pic'] . "</userprofilepic>
							<text>" . htmlEntityDecode($comment['comment_text']) . "</text>
							<date>" . $comment['comment_date'] . "</date>
							<published>" . $comment['published'] . "</published>
						</comment>";
		/*code changed by sushma mishra on 30-sep-2015 ends here*/
		endforeach;
		
		// Close the XML section.
		$output .= "</comments_details>
			</event_comments>";
	
	endif;
	
	echo $output;