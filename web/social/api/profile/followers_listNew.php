<?php

/*
 * Returns the events sponsored by a given channel.
 * Param S: The session id.
 * Param [limit]: Optional, the max rows to get, default 100.
 * Param [page]: Optional, the current page.
 */

$submit_post_get = array_merge($request->query->all(),$request->request->all());

session_id($_REQUEST['S']);

$expath = "../";
header("content-type: application/json; charset=utf-8");
include("../heart.php");

$user_id = $_SESSION['id'];
//if (isset($_REQUEST['limit']))
//    $limit = intval($_REQUEST['limit']);
if (isset($submit_post_get['limit']))
    $limit = intval($submit_post_get['limit']);
else
    $limit = 100;
//if (isset($_REQUEST['page']))
//    $page = intval($_REQUEST['page']);
if (isset($submit_post_get['page']))
    $page = intval($submit_post_get['page']);
else
    $page = 0;

// Get the followers.
$options = array(
    'reverse' => false,
    'userid' => $user_id,
    'limit' => $limit,
    'page' => $page
);
$followers = userSubscriberSearch($options);
// Get the followers count.
$options = array(
    'reverse' => false,
    'userid' => $user_id,
    'n_results' => true,
);
$followers_count = userSubscriberSearch($options);

// Display the general data.
$xml = "<followers>
			<count>" . $followers_count . "</count>
			<followers_details>";

// Display the follower-specific data.
foreach ($followers as $follower):

    $xml .= "<follower>
					<id>" . $follower['id'] . "</id>
					<user_id>" . $follower['user_id'] . "</user_id>
					<FullName>" . htmlEntityDecode($follower['FullName']) . "</FullName>
					<fname>" . htmlEntityDecode($follower['fname']) . "</fname>
					<lname>" . htmlEntityDecode($follower['lname']) . "</lname>
					<gender>" . $follower['gender'] . "</gender>
					<YourEmail>" . $follower['YourEmail'] . "</YourEmail>
					<website_url>" . htmlEntityDecode($follower['website_url']) . "</website_url>
					<small_description>" . htmlEntityDecode($follower['small_description']) . "</small_description>
					<YourCountry>" . $follower['YourCountry'] . "</YourCountry>
					<hometown>" . htmlEntityDecode($follower['hometown']) . "</hometown>
					<YourBday>" . date('m/d/Y', strtotime($follower['YourBday'])) . "</YourBday>
					<YourUserName>" . $follower['YourUserName'] . "</YourUserName>
					<profile_Pic>" . $follower['profile_Pic'] . "</profile_Pic>
					<profile_id>" . $follower['profile_id'] . "</profile_id>
					<display_age>" . $follower['display_age'] . "</display_age>
					<display_gender>" . $follower['display_gender'] . "</display_gender>
					<display_fullname>" . $follower['display_fullname'] . "</display_fullname>
				</follower>";

endforeach;
// Close the xml tags.
$xml .= "</followers_details></followers>";
//echo $xml;
$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $xml);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string wit