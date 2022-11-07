<?php

$expath = "../";
header("content-type: application/json; charset=utf-8");
include("../heart.php");

//$id = intval($_REQUEST['id']);
//if (isset($_REQUEST['status']))
//    $status = intval($_REQUEST['status']);
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$id = intval($submit_post_get['id']);
if (isset($submit_post_get['status']))
    $status = intval($submit_post_get['status']);
else
    $status = null;



$options = array(
    'channelid' => $id,
    'status' => $status, // Status: 0->Past events, 1->Upcoming, 2->Current, 3->current+upcoming
    'limit' => null,
);


//if (isset($_REQUEST['from']) && isset($_REQUEST['to'])) {
//    $from = $_REQUEST['from'];
//    $to = $_REQUEST['to'];
if (isset($submit_post_get['from']) && isset($submit_post_get['to'])) {
    $from = $submit_post_get['from'];
    $to = $submit_post_get['to'];
    $options = array_merge($options, array('from_ts' => $from, 'to_ts' => $to));
}

$channeleventsInfo = channeleventSearch($options);

$output = "";

if ($channeleventsInfo) {

    $output .= "<channel_events>\n";

    foreach ($channeleventsInfo as $event) {
        $thumbnail = ($event['photo']) ? photoReturneventImage($event) : ReturnLink('media/images/channel/eventthemephoto.jpg');
        $output .= "
			<event>
				<id>" . $event['id'] . "</id>
				<event_name>" . htmlEntityDecode($event['name']) . "</event_name>
				<event_thumbnail>" . $thumbnail . "</event_thumbnail>
				<event_from_date>" . date('m/d/Y', strtotime($event['fromdate'])) . "</event_from_date>
				<event_from_time>" . $event['fromtime'] . "</event_from_time>
				<event_to_date>" . date('m/d/Y', strtotime($event['todate'])) . "</event_to_date>
				<event_to_time>" . $event['totime'] . "</event_to_time>
				<location>" . htmlEntityDecode($event['location']) . "</location>
				<desc>" . htmlEntityDecode($event['description']) . "</desc>
			</event>";
    }

    $output .= "</channel_events>";
}

//echo $output;
$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $output);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string with JSON object
