<?php

/*
 * Returns the upcoming events for a specific channel.
 * Param id: The channel id
 * Param [limit]: Optional, the max rows to get, default 100.
 * Param [page]: Optional, the current page.
 */

$expath = "../";
header("content-type: application/json; charset=utf-8");
include("../heart.php");

$submit_post_get = array_merge($request->query->all(),$request->request->all());
//$id = intval($_REQUEST['id']);
//if (isset($_REQUEST['limit']))
//    $limit = intval($_REQUEST['limit']);
//else
//    $limit = 100;
//if (isset($_REQUEST['page']))
//    $page = intval($_REQUEST['page']);
$id = intval($submit_post_get['id']);
if (isset($submit_post_get['limit']))
    $limit = intval($submit_post_get['limit']);
else
    $limit = 100;
if (isset($submit_post_get['page']))
    $page = intval($submit_post_get['page']);
else
    $page = 0;


// Get the upcoming section (upcoming and current).
$options = array(
    'channelid' => $id,
    'orderby' => 'todate',
    'order' => 'd',
    'status' => 3, // Status: 0->Past events, 1->Upcoming, 2->Current, 3->current+upcoming
    'is_visible' => 1,
    'page' => $page,
    'limit' => $limit
);
$upcoming_events = channeleventSearch($options);
if ($upcoming_events)
    $upcoming_events_count = count($upcoming_events);
else
    $upcoming_events_count = 0;


if ($upcoming_events_count > 0):

    // Start the XML section.
    $output .= "
			<upcoming_events>
				<count>" . $upcoming_events_count . "</count>
				<events_details>";

    // Fill in the details for every comment.
    foreach ($upcoming_events as $event):
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

        $output .= "<event>
							<id>" . $event['id'] . "</id>
							<event_name>" . htmlEntityDecode($event['name']) . "</event_name>
							<event_thumbnail>" . $thumbnail . "</event_thumbnail>
							<description>" . htmlEntityDecode($event['description']) . "</description>
							<location>" . htmlEntityDecode($event['location']) . "</location>
							<location_detailed>" . htmlEntityDecode($event['location_detailed']) . "</location_detailed>
							<longitude>" . $event['longitude'] . "</longitude>
							<lattitude>" . $event['lattitude'] . "</lattitude>
							<event_from_date>" . date('m/d/Y', strtotime($event['fromdate'])) . "</event_from_date>
							<event_from_time>" . $event['fromtime'] . "</event_from_time>
							<event_to_date>" . date('m/d/Y', strtotime($event['todate'])) . "</event_to_date>
							<event_to_time>" . $event['totime'] . "</event_to_time>
							<joining_guests>" . $event['whojoin'] . "</joining_guests>
							<limitnumber>" . $event['limitnumber'] . "</limitnumber>
							<caninvite>" . $event['caninvite'] . "</caninvite>
							<hideguests>" . $event['hideguests'] . "</hideguests>
							<showsponsors>" . $event['showsponsors'] . "</showsponsors>
							<allowsponsoring>" . $event['allowsponsoring'] . "</allowsponsoring>
							<enable_share_comment>" . $event['enable_share_comment'] . "</enable_share_comment>
							<invited_guests>" . $invited_guests_count . "</invited_guests>
							<likes>" . $likes_count . "</likes>
						</event>";
    endforeach;

    // Close the XML section.
    $output .= "</events_details>
			</upcoming_events>";

endif;

echo json_encode($output);


//$xml_cnt = file_get_contents('channelSingleBrochure.xml');    // gets XML content from file
//$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $output);    // removes newlines, returns and tabs
//print_r($xml_cnt);exit;
//// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
//$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
//$simpleXml = simplexml_load_string($xml_cnt);
//
//echo json_encode($simpleXml);    // returns a string with JSON object