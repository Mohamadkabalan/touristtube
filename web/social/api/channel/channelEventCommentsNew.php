<?php

/*
 * Returns the comments for a specific event.
 * Param id: The event id
 * Param [limit]: Optional, the max rows to get, default 100.
 * Param [page]: Optional, the current page.
 */

$expath = "../";
header("content-type: application/json; charset=utf-8");
include("../heart.php");

//$id = intval($_REQUEST['id']);
//if (isset($_REQUEST['limit']))
//    $limit = intval($_REQUEST['limit']);
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$id = intval($submit_post_get['id']);
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


$options = array(
    'page' => $page,
    'limit' => $limit,
    'orderby' => 'comment_date',
    'order' => 'd',
    'entity_id' => $id,
    'published' => 1,
    'is_visible' => 1,
    'entity_type' => SOCIAL_ENTITY_EVENTS
);
$allComments = socialCommentsGet($options);
if ($allComments)
    $comments_count = count($allComments);
else
    $comments_count = 0;

if ($comments_count > 0):

    // Start the XML section.
    $output .= "
			<event_comments>
				<count>" . $comments_count . "</count>
				<comments_details>";

    // Fill in the details for every comment.
    foreach ($allComments as $comment):
        $output .= "<comment>
							<id>" . $comment['id'] . "</id>
							<user_id>" . $comment['user_id'] . "</user_id>
							<username>" . $comment['YourUserName'] . "</username>
							<user>" . htmlEntityDecode($comment['FullName']) . "</user>
							<display_fullname>" . $comment['display_fullname'] . "</display_fullname>
							<userprofilepic>media/images/tubers/" . $comment['profile_Pic'] . "</userprofilepic>
							<text>" . htmlEntityDecode($comment['comment_text']) . "</text>
							<date>" . $comment['comment_date'] . "</date>
							<published>" . $comment['published'] . "</published>
						</comment>";
    endforeach;

    // Close the XML section.
    $output .= "</comments_details>
			</event_comments>";

endif;

//echo $output;
$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $output);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string with JSON object