<?php

/*
 * Returns the events sponsored by a given channel.
 * param s: The session id.
 * param eid: The event id.
 * param [cid]: The channel_id. Optional for deleting, must be filled for adding or modifying.
 * param join_event: The status if joining or not. Value "Yes/No".
 * param [guests_count]: The number of guests. Optional when deleting, must be filled for adding or modifying.
 * 
 * Returns 1 on success, 0 on failure.
 */

$expath = "../";
header("content-type: application/json; charset=utf-8");
include("../heart.php");

//$user_id = mobileIsLogged($_REQUEST['S']);
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$user_id = mobileIsLogged($submit_post_get['S']);
if( !$user_id ) die();

//	$user_id = intval( $_REQUEST['uid'] );
//$event_id = intval($_REQUEST['eid']);
//$join_event = strtolower(xss_sanitize($_REQUEST['join_event']));
//if (isset($_REQUEST['guests_count']))
//    $guests_count = intval($_REQUEST['guests_count']);
$event_id = intval($submit_post_get['eid']);
$join_event = strtolower($submit_post_get['join_event']);
if (isset($submit_post_get['guests_count']))
    $guests_count = intval($submit_post_get['guests_count']);
else
    $guests_count = 0;
//if (isset($_REQUEST['cid']))
//    $channel_id = intval($_REQUEST['cid']);
if (isset($submit_post_get['cid']))
    $channel_id = intval($submit_post_get['cid']);
else
    $channel_id = 0;
$status = 0;


// Get details in case the user had already joined the event.
$user_join = array();
$options = array(
    'event_id' => $event_id,
    'user_id' => $user_id,
    'limit' => 1
);
$user_join = joinEventSearch($options);


// ** The db only saves the attending users.
// If the user had already joined the event.
if (isset($user_join[0])) {
    // The user only changed the guests' count.
    if ($join_event == 'yes') {
        $options = array(
            'id' => $user_join[0]['id'],
            'event_id' => $event_id,
            'guests' => $guests_count
        );
        if (joinEventEdit($options))
            $status = 1;
    }
    // The user is not going anymore.
    else {
        if (joinEventDelete($user_join[0]['id']))
            $status = 1;
    }
}
// If the user was not attending the event.
else {
    // Save the user as "attending".
    if ($join_event == 'yes' && $channel_id != 0) {
        if (joinEventAdd($event_id, $user_id, $guests_count, $channel_id))
            $status = 1;
    }
}
// Start the XML section.
$output .= "<status>" . $status . "</status>";
//echo $output;

$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $output);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string wit