<?php
/*! \file
 * 
 * \brief This api returns channel event details
 * 
 * 
 * @param S session id
 * @param id event id
 * 
 * @return <b>output</b> JSON list with the following keys:
 * @return <pre> 
 * @return       <b>id</b> event id
 * @return       <b>event_name</b> event name
 * @return       <b>event_thumbnail</b> event thumbnail path
 * @return       <b>event_from_date</b> event from date
 * @return       <b>event_from_time</b> event from time
 * @return       <b>event_to_date</b> event to date
 * @return       <b>event_to_time</b> event to time
 * @return       <b>location</b> event location
 * @return       <b>description</b> event description
 * @return       <b>invited_guests</b> List with the following keys:
 * @return       		<b>entity_id</b> event entity id
 * @return       		<b>entity_type</b> event entity type
 * @return       		<b>share_type</b> event share type
 * @return       		<b>n_results</b> event results 
 * @return       <b>likes</b> List of keys(social info)
 * @return       <b>joining_user</b> List with the following keys:
 * @return       		<b>is_attending</b> user is attending or not
 * @return       		<b>guest_count</b> user guest count
 * @return       		<b>is_liked</b> user is liked or not
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */
/*
* Returns an event's details. If a user id is provided, also returns information about the user's interactions with the event.
* id: The event id
* [S]: Optional, the session id.
*/
	
	
	
	$expath = "../";
	header('Content-type: application/json');
	include("../heart.php");
        

//	$id = intval( $_REQUEST['id'] );
//	$uid = mobileIsLogged($_REQUEST['S']);
$submit_post_get = array_merge($request->query->all(),$request->request->all());
	$id = intval( $submit_post_get['id'] );
	$uid = mobileIsLogged($submit_post_get['S']);
        
        if(isset($submit_post_get['status']))
		$status = intval( $submit_post_get['status'] );
	else
		$status = null;
	
	
	$options = array(
		'id' => $id,
            'status' => $status, 
	);
        

	// Get the event details.
	$event = userEventInfo($id,-1);
        
        
    //echo "<pre>";print_r($usereventsInfo);die;
	//$event = $channeleventsInfo[0];

    
	// Get the number of joining guests.
	$joinEventArrayCount = joinUserEventSearch(array(
			'event_id' => $id,			
			'n_results' => true
	));
	
	// Get the number of invited guests.
	$socialInvitedCount =socialInvitedEventsGet(array(
			'entity_id' => $id,
			'entity_type' => SOCIAL_ENTITY_USER_EVENTS,
			'share_type' => SOCIAL_SHARE_TYPE_INVITE,			
			'n_results' => true
	));
	
	// Get the number of likes.
	$options = array(
                    'entity_id' => $id,
                    'entity_type' => SOCIAL_ENTITY_USER_EVENTS,
                    'like_value' => 1,
                    'n_results' => true
                );
        
	$allLikesnum = socialLikesGet($options);
	
	// If a user id is provided, get the joining-user details.
	$attending = '';
	$guests = '';
	$is_liked = '';
	if($uid){
		// Get the user details if he's joining the event.
		$options = array(
			'user_id' => $uid,
			'event_id' => $id,
		);
		$event_join_details = joinUserEventSearch($options);
		
		// If this is null, the user has not responded if he's attending or not.
		if(isset($event_join_details[0])){
			// If the user is attending or not.
			if($event_join_details[0]['published'] == 1){
				$attending = 1;
				$guests = $event_join_details[0]['guests'];
			}
		}
		
		// Get if the user liked the event.
		if(socialLiked($uid, $id, SOCIAL_ENTITY_USER_EVENTS))
			$is_liked = 1;
		else
			$is_liked = 0;
	}
	
	
	$thumbnail = ($event['photo']) ? getEventThumbPath($event,"") : ReturnLink('media/images/eventsdetailed/eventthemephoto.jpg');
	
	$output[] = array(
                        'id'=>$event['id'],
                        'event_name'=>htmlEntityDecode($event['name']),
                        'event_thumbnail'=>$thumbnail,
                        'event_from_date'=>date('m/d/Y', strtotime($event['fromdate'])),
                        'event_from_time'=>$event['fromtime'],
                        'event_to_date'=>date('m/d/Y', strtotime($event['todate'])),
                        'event_to_time'=>$event['totime'],
                        'location'=>htmlEntityDecode($event['location']),
                        'description'=>htmlEntityDecode($event['description']),
                        'invited_guests'=>$socialInvitedCount,
                        'joining_guests'=> $joinEventArrayCount,
                        'likes'=>$allLikesnum,
                        'joining_user'=>array(
                                            'is_attending'=>$attending,
                                            'guest_count'=>$guests,
                                            'is_liked'=>$is_liked,
                        )
        );
//		
	echo json_encode($output);