<?php

$expath = "../";
header("content-type: application/json; charset=utf-8");
include("../heart.php");

//$id = intval($_REQUEST['id']);
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$id = intval($submit_post_get['id']);

$channelInfo = channelGetInfo($id);
$userChannelInfo = getUserInfo($channelInfo['owner_id']);
$catarr = allchannelGetCategory($channelInfo['category']);

$channel_id = $channelInfo['id'];
$channel_name = $channelInfo['channel_name'];
$channel_slogan = htmlEntityDecode($channelInfo['slogan']);
$channel_category = htmlEntityDecode($catarr[0]['title']);
$channel_location = channelOwnerLocation($channelInfo);
$channel_phone = $channelInfo['phone'];
$channel_logo = ($channelInfo['logo']) ? photoReturnchannelLogo($channelInfo) : ReturnLink('/media/images/tubers/tuber.jpg');
$channel_header = photoReturnchannelHeader($channelInfo);
$channel_desc = htmlEntityDecode($channelInfo['small_description']);
$channel_default_link = $channelInfo['default_link'];
$channel_created_by = ($channelInfo['hidecreatedby'] == 0) ? returnUserDisplayName($userChannelInfo) : '';
$channel_created_date = ($channelInfo['hidecreatedon'] == 0) ? date('d/m/Y', strtotime($channelInfo['create_ts'])) : '';

$arraychannellinks = GetChannelExternalLinks($channelInfo['id']);

if ($arraychannellinks) {
    $socialLinks = "<sociallink>" . "\n";
    foreach ($arraychannellinks as $arrlinks) {
        $socialLinks .= "<link>" . htmlEntityDecode($arrlinks['link']) . "</link>" . "\n";
    }
    $socialLinks .= "</sociallink>";
} else {
    $socialLinks = "";
}

$channel_connected_tubers = channelConnectedTubersSearch(array('channelid' => $channelInfo['id'], 'n_results' => true));
$channel_sponsors = socialSharesGet(array(
    'orderby' => 'share_ts',
    'order' => 'd',
    'entity_id' => $channelInfo['id'],
    'entity_type' => SOCIAL_ENTITY_CHANNEL,
    'share_type' => SOCIAL_SHARE_TYPE_SPONSOR,
    'n_results' => true
        ));

$channel_photos = mediaSearch(array(
    'channel_id' => $channelInfo['id'],
    'type' => 'i',
    'catalog_status' => 0,
    'n_results' => true
        ));
$channel_video = mediaSearch(array(
    'channel_id' => $channelInfo['id'],
    'type' => 'v',
    'catalog_status' => 0,
    'n_results' => true
        ));

$options = array('channelid' => $channelInfo['id']);
$channelalbumInfoCount = userCatalogchannelSearch($options);
$channel_album = ($channelalbumInfoCount) ? count($channelalbumInfoCount) : 0;

$options = array('channelid' => $channelInfo['id'], 'order' => 'd');
$channelbrochuresInfoCount = channelbrochureSearch($options);
$channel_brochure = ($channelbrochuresInfoCount) ? count($channelbrochuresInfoCount) : 0;

$options = array('channelid' => $channelInfo['id'], 'order' => 'd');
$channeleventsInfoCount = channeleventSearch($options);
$channel_events = ($channeleventsInfoCount) ? count($channeleventsInfoCount) : 0;

$options = array('channelid' => $channelInfo['id'], 'n_results' => true);
$channel_news = channelnewsSearch($options);

$bg_src = ($channelInfo['bg']) ? photoReturnchannelBG($channelInfo) : '';
$bg_color = ($channelInfo['bgcolor']) ? ' #' . $channelInfo['bgcolor'] : '';

$output = '';

$output .= "
	<channel>
	<id>" . $channel_id . "</id>
	<name>" . safeXML($channel_name) . "</name>
	<slogan>" . safeXML($channel_slogan) . "</slogan>
	<about>" . safeXML($channel_desc) . "</about>
	<logo>" . $channel_logo . "</logo>
	<header>" . $channel_header . "</header>
	<bgimage>" . $bg_src . "</bgimage>
	<bgcolor>" . $bg_color . "</bgcolor>
	<hidecreatedon>" . $channelInfo['hidecreatedon'] . "</hidecreatedon>
	<created_on>" . $channel_created_date . "</created_on>
	<hidecreatedby>" . $channelInfo['hidecreatedby'] . "</hidecreatedby>
	<created_by>" . $channel_created_by . "</created_by>
	<phone>" . $channel_phone . "</phone>
	<hidelocation>" . $channelInfo['hidelocation'] . "</hidelocation>
	<location>" . $channel_location . "</location>
	<url>" . $channel_default_link . "</url>
	<statistic>
		<connected_tubers>" . $channel_connected_tubers . "</connected_tubers>
		<sponsors>" . $channel_sponsors . "</sponsors>
		<videos>" . $channel_video . "</videos>
		<photos>" . $channel_photos . "</photos>
		<albums>" . $channel_album . "</albums>
		<events>" . $channel_events . "</events>
		<brochures>" . $channel_album . "</brochures>
		<news>" . $channel_news . "</news>
	</statistic>";
$output .= "
	" . $socialLinks . "
	</channel>";
//echo $output;

$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $output);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string with JSON object