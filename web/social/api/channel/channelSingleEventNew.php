<?php

/*
 * Returns an event's details. If a user id is provided, also returns information about the user's interactions with the event.
 * id: The event id
 * [S]: Optional, the session id.
 */

//if (isset($_REQUEST['S']))
//    session_id($_REQUEST['S']);
$submit_post_get = array_merge($request->query->all(),$request->request->all());
if (isset($submit_post_get['S']))
    session_id($submit_post_get['S']);

$expath = "../";
header("content-type: application/json; charset=utf-8");
include("../heart.php");

//$id = intval($_REQUEST['id']);
//if (isset($_REQUEST['S']))
$id = intval($submit_post_get['id']);
if (isset($submit_post_get['S']))
    $uid = $_SESSION['id'];
else
    $uid = 0;

$options = array(
    'id' => $id
);

// Get the event details.
$channeleventsInfo = channeleventSearch($options);
$event = $channeleventsInfo[0];

// Get the number of joining guests.
$joinEventArrayCount = joinEventSearch(array(
    'event_id' => $id,
    'n_results' => true
        ));

// Get the number of invited guests.
$socialInvitedCount = socialInvitedEventsGet(array(
    'entity_id' => $id,
    'entity_type' => SOCIAL_ENTITY_EVENTS,
    'share_type' => SOCIAL_SHARE_TYPE_INVITE,
    'n_results' => true
        ));

// Get the number of likes.
$options = array(
    'entity_id' => $id,
    'entity_type' => SOCIAL_ENTITY_EVENTS,
    'like_value' => 1,
    'n_results' => true
);
$allLikesnum = socialLikesGet($options);

// If a user id is provided, get the joining-user details.
$attending = '';
$guests = '';
$is_liked = '';
if ($uid != 0) {
    // Get the user details if he's joining the event.
    $options = array(
        'user_id' => $uid,
        'event_id' => $id,
    );
    $event_join_details = joinEventSearch($options);

    // If this is null, the user has not responded if he's attending or not.
    if (isset($event_join_details[0])) {
        // If the user is attending or not.
        if ($event_join_details[0]['published'] == 1) {
            $attending = 1;
            $guests = $event_join_details[0]['guests'];
        }
    }

    // Get if the user liked the event.
    if (socialLiked($uid, $id, SOCIAL_ENTITY_EVENTS))
        $is_liked = 1;
    else
        $is_liked = 0;
}



$thumbnail = ($event['photo']) ? photoReturneventImage($event) : ReturnLink('media/images/channel/eventthemephoto.jpg');

$output[] = array(
    'event' => array(
        'id' => $event['id'],
        'event_name' => htmlEntityDecode($event['name']),
        'event_thumbnail' => $thumbnail,
        'event_from_date' => date('m/d/Y', strtotime($event['fromdate'])),
        'event_from_time' => $event['fromtime'],
        'event_to_date' => date('m/d/Y', strtotime($event['todate'])),
        'event_to_time' => $event['totime'],
        'location' => htmlEntityDecode($event['location']),
        'description' => htmlEntityDecode($event['description']),
        'invited_guests' => $socialInvitedCount,
        'joining_guests' => $joinEventArrayCount,
        'likes' => $allLikesnum,
        'joining_user' => array(
            'is_attending' => $attending,
            'guest_count' => $guests,
            'is_liked' => $is_liked,
        )));

echo json_encode($output);
