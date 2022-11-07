<?php
/*! \file
 * 
 * \brief This api the upcoming events for a specific channel.
 * 
 * 
 * @param id channel id
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * 
 * @return <b>result</b> JSON list with the following keys:
 * @return <pre> 
 * @return       <b>upcoming_events_count</b> channel upcoming events count
 * @return       <b>output</b> List with the following keys (array)
 * @return              <b>id</b> event id
 * @return              <b>event_name</b> event name
 * @return              <b>event_thumbnail</b> event thumbnail
 * @return              <b>description</b> event description
 * @return              <b>location</b> event location
 * @return              <b>location_detailed</b> event location detailed
 * @return              <b>longitude</b> event longitude
 * @return              <b>lattitude</b> event latitude
 * @return              <b>event_from_date</b> event from date
 * @return              <b>event_from_time</b> event from time
 * @return              <b>event_to_date</b> event to date
 * @return              <b>event_to_time</b> event to time
 * @return              <b>joining_guests</b> event joining guests
 * @return              <b>limitnumber</b> event user limit number
 * @return              <b>caninvite</b> event can invite guests
 * @return              <b>hideguests</b> event hide guests
 * @return              <b>showsponsors</b> event show sponsors
 * @return              <b>allowsponsoring</b> event allow sponsoring
 * @return              <b>enable_share_comment</b> event enable share comment
 * @return              <b>invited_guests</b> event invited guests number
 * @return              <b>likes</b> event likes number
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */
/*
* Returns the upcoming events for a specific channel.
* Param id: The channel id
* Param [limit]: Optional, the max rows to get, default 100.
* Param [page]: Optional, the current page.
*/
	
	$expath = "../";			
	header('Content-type: application/json'); 
	include("../heart.php");
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
	
//	$id = intval( $_REQUEST['id'] );
//	if(isset($_REQUEST['limit']))
//		$limit = intval( $_REQUEST['limit'] );
//	else
//		$limit = 100;
//	if(isset($_REQUEST['page']))
//		$page = intval( $_REQUEST['page'] );
	$id = intval( $submit_post_get['id'] );
	if(isset($submit_post_get['limit']))
		$limit = intval( $submit_post_get['limit'] );
	else
		$limit = 100;
	if(isset($submit_post_get['page']))
		$page = intval( $submit_post_get['page'] );
	else
		$page = 0;
	
	
	// Get the upcoming section (upcoming and current).
	$options = array(
		'channelid' => $id,
		'orderby' => 'todate',
		'order' => 'd',
		'status' => 3, // Status: 0->Past events, 1->Upcoming, 2->Current, 3->current+upcoming
		'is_visible'=> 1,
		'page' => $page,
		'limit' => $limit
	);
	$upcoming_events = channeleventSearch($options);
	if($upcoming_events)
		$upcoming_events_count = count($upcoming_events);
	else
		$upcoming_events_count = 0;
	
	
	if($upcoming_events_count > 0):
	
		// Start the XML section.
		
				
		// Fill in the details for every comment.
		foreach($upcoming_events as $event):
			// Get the number of invited guests.
			$options = array(
						'entity_id' => $event['id'],
						'entity_type' => SOCIAL_ENTITY_EVENTS,
						'share_type' => SOCIAL_SHARE_TYPE_INVITE,
						'n_results' => true
			);
			$invited_guests_count = socialInvitedEventsGet($options);
			
			// Get the number of likes for this event.
			$options = array(
					'entity_id' => $event['id'],
					'entity_type' => SOCIAL_ENTITY_EVENTS,
					'like_value' => 1,
					'n_results' => true
				);
			$likes_count = socialLikesGet($options);
			
			// If the image does not exist, return the default image.
			$thumbnail = ($event['photo']) ? photoReturneventImage($event) : ReturnLink('media/images/channel/eventthemephoto.jpg');
			
                        $output[] = array(
                                        'id'=>$event['id'],
                                        'event_name'=>htmlEntityDecode($event['name']),
                                        'event_thumbnail'=>$thumbnail,
                                        'description'=>htmlEntityDecode($event['description']),
                                        'location'=>htmlEntityDecode($event['location']),    
                                        'location_detailed'=>htmlEntityDecode($event['location_detailed']),        
                                        'longitude'=>$event['longitude'],
                                        'lattitude'=>$event['lattitude'],
                                        'event_from_date'=>date('m/d/Y', strtotime($event['fromdate'])),    
                                        'event_from_time'=>$event['fromtime'],    
                                        'event_to_date'=>date('m/d/Y', strtotime($event['todate'])),
                                        'event_to_time'=>$event['totime'],
                                        'joining_guests'=>$event['whojoin'],
                                        'limitnumber'=>$event['limitnumber'],
                                        'caninvite'=>$event['caninvite'],
                                        'hideguests'=>$event['hideguests'],
                                        'showsponsors'=>$event['showsponsors'],
                                        'allowsponsoring'=>$event['allowsponsoring'],    
                                        'enable_share_comment'=>$event['enable_share_comment'],
                                        'invited_guests'=>$invited_guests_count,
                                        'likes'=>$likes_count, 
                            
                        );
//			
		endforeach;
		       
	endif;
        
                $result = array();
                $result =array(
                                'upcoming_events_count'=>$upcoming_events_count,
                                'output'=>$output,
                );
	
	echo json_encode($result);