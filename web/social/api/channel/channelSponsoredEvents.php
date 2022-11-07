<?php
/*! \file
 * 
 * \brief This api returns the sponsored events of channel
 * 
 * 
 * @param id user id
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * 
 * @return <b>result</b> JSON list with the following keys:
 * @return <pre> 
 * @return       <b>sponsored_events_count</b> sponsored events number
 * @return       <b>output</b> List with the following keys (array)
 * @return              <b>id</b> event id
 * @return              <b>event_name</b> event name
 * @return              <b>all_users</b> event all users
 * @return              <b>entity_id</b> event entity id
 * @return              <b>share_ts</b> event share date
 * @return              <b>msg</b> event message
 * @return              <b>event_owner_channel_id</b> event owner channel id
 * @return              <b>event_thumbnail</b> event thumbnail path
 * @return              <b>description</b> event description
 * @return              <b>location</b> event location
 * @return              <b>location_detailed</b> event location detailed
 * @return              <b>longitude</b> event longitude
 * @return              <b>lattitude</b> event lattitude
 * @return              <b>event_from_date</b> event from date
 * @return              <b>event_from_time</b> event from time
 * @return              <b>event_to_date</b> event to date
 * @return              <b>event_to_time</b> event to time
 * @return              <b>joining_guests</b> event joining guests
 * @return              <b>limitnumber</b> event limit number of user
 * @return              <b>caninvite</b> event can invite
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
* Returns the events sponsored by a given channel.
* Param id: The channel id
* Param [limit]: Optional, the max rows to get, default 100.
* Param [page]: Optional, the current page.
*/
	
	$expath = "../";			
	header('Content-type: application/json'); 
	include("../heart.php");
	
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//	$id = intval( $_REQUEST['id'] );
//    
//	if(isset($_REQUEST['limit']))
//		$limit = intval( $_REQUEST['limit'] );
	$id = intval( $submit_post_get['id'] );
    
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

	
	// Get the upcoming section (upcoming and current).
	$options = array(
		'from_user' => $id,
		'orderby' => 'share_ts',
		'order' => 'd',
		'status' => null, // Status: 0->Past events, 1->Upcoming, 2->Current, 3->current+upcoming
		'is_visible'=> 1,
		'page' => $page,
		'limit' => $limit
	);
	$sponsored_events = sponsoredEventsGet($options);
        
	if($sponsored_events)
		$sponsored_events_count = count($sponsored_events);
	else
		$sponsored_events_count = 0;
	
	
	if($sponsored_events_count > 0):
	
		// Start the XML section.
				
		// Fill in the details for every comment.
		foreach($sponsored_events as $event):
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
                                        'all_users'=>$event['all_users'],
                                        'entity_id'=>$event['entity_id'],    
                                        'share_ts'=>$event['share_ts'],    
                                        'msg'=>htmlEntityDecode($event['msg']),
                                        'event_owner_channel_id'=>$event['event_owner_channel_id'],
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
                endforeach;
        endif;
            

         $result = array();
         $result =array(
            'sponsored_events_count'=>$sponsored_events_count,
            'output'=>$output,
             );                

        
	echo json_encode($result);
