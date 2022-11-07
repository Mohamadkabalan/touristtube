<?php
/*! \file
 * 
 * \brief This api returns the id of added comment
 * 
 * \todo <b><i>Change from Integer to Json object</i></b>
 * 
 * @param S session id
 * @param fk post id
 * @param cid channel id
 * @param comment event comment added 
 * 
 * @return integer id of added comment
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */
/*
* Returns the events sponsored by a given channel.
* param S: The session id.
* param fk: The foreign key (Event id).
* param cid: The channel id.
* param comment: The comment text.
*
* Returns the new comment's ID on success, 0 on failure.
*/


//	session_id($_REQUEST['S']); 
//        session_start();
	
	$expath = "../";			
	header('Content-type: application/json');
	include("../heart.php");
	
//	$user_id = $_SESSION['id'];
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//	$user_id = mobileIsLogged($_REQUEST['S']);
	$user_id = mobileIsLogged($submit_post_get['S']);
        if( !$user_id ) die();
	
//	$user_id = intval( $_REQUEST['uid'] );
//	$fk = intval( $_REQUEST['fk'] );
//	$channel_id = intval( $_REQUEST['cid'] );
//	$comment =  $_REQUEST['comment'] ;
	$fk = intval( $submit_post_get['fk'] );
	$channel_id = intval( $submit_post_get['cid'] );
	$comment =  $submit_post_get['comment'] ;
	
	$comment_id = socialCommentAdd($user_id, $fk, SOCIAL_ENTITY_EVENTS, $comment, $channel_id);
	if($comment_id){
            //$status = $comment_id;
            $status = array('status' => 'success');
        }
	else{
            //$status = 0;
            $status = array('status' => 'error');
        }
	// Start the XML section.
//	$output = $status;
//	echo $output;
        echo json_encode($status);