<?php
/*! \file
 * 
 * \brief This api returns channel event list
 * 
 * 
 * @param S session id
 * @param id channel id
 * @param status event status (either 0,1,2,or3)
 * @param from from date
 * @param to to date
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
 * @return       <b>desc</b> event description
 * @return       <b>is_liked</b> event is liked  or not
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */
	
	$expath = "../";			
	//header("content-type: application/xml; charset=utf-8");  
        header('Content-type: application/json');
	include("../heart.php");
        
//        $user_id = mobileIsLogged($_REQUEST['S']);
//	
//	$id = intval( $_REQUEST['id'] );
//	if(isset($_REQUEST['status']))
//		$status = intval( $_REQUEST['status'] );
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
        
        $user_id = mobileIsLogged($submit_post_get['S']);
        $limit = isset($submit_post_get['limit']) ? intval($submit_post_get['limit']) : 10;
        $page = isset($submit_post_get['page']) ? intval($submit_post_get['page']) : 0;
	
	$id = intval( $submit_post_get['id'] );
	if(isset($submit_post_get['status']))
		$status = intval( $submit_post_get['status'] );
	else
		$status = null;
	
	
	
	$options = array(
		'channelid' => $id,
		'status' => $status, // Status: 0->Past events, 1->Upcoming, 2->Current, 3->current+upcoming
		'limit' => $limit,
                'page' => $page
	);
	
	
//	if(isset($_REQUEST['from']) && isset($_REQUEST['to']))
//	{
//		$from = $_REQUEST['from'];
//		$to = $_REQUEST['to'];	
	if(isset($submit_post_get['from']) && isset($submit_post_get['to']))
	{
		$from = $submit_post_get['from'];
		$to = $submit_post_get['to'];
		$options = array_merge($options,array('from_ts' => $from, 'to_ts' => $to));
	}
		
	$channeleventsInfo = channeleventSearch($options);
	
	$output = array();
	
	if($channeleventsInfo){
		
		//$output .= "<channel_events>\n";
		
		foreach($channeleventsInfo as $event){
			$thumbnail = ($event['photo']) ? photoReturneventImage($event) : ReturnLink('media/images/channel/eventthemephoto.jpg');
                        
                        $is_liked = '';
                        if($user_id){
                            // Get if the user liked the event.
                            if(socialLiked($user_id, $event['id'], SOCIAL_ENTITY_EVENTS))
                                $is_liked = 1;
                            else
                                $is_liked = 0;
                        }
                        
			$output[] = array(
                            'id'=>$event['id'],
                            'event_name'=>htmlEntityDecode($event['name']),
                            'event_thumbnail'=>$thumbnail,
                            'event_from_date'=>date('m/d/Y', strtotime($event['fromdate'])),
                            'event_from_time'=>$event['fromtime'],
                            'event_to_date'=>date('m/d/Y', strtotime($event['todate'])),
                            'event_to_time'=>$event['totime'],
                            'location'=>htmlEntityDecode($event['location']),
                            'desc'=>htmlEntityDecode($event['description']),
                            'is_liked'=>$is_liked
			);
		}
	}

	echo json_encode($output);
