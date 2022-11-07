<?php

		

$expath = "../";
header("content-type: application/json; charset=utf-8");
include("../heart.php");



$res = "";
$res .= "<channel_search>";

/**
 * gets the channel info depending on search criteria. options include:<br/>
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>public</b>: wheather the channel is public or not. default 1<br/>
 * <b>owner_id</b>: the channel's owner's id. default null<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>name</b>: the channel's name default null<br/>
 * <b>url</b>: the channel's url default null<br/>
 * <b>id</b>: the channel's id default null<br/>
 * <b>strict_search</b>: search for the channel's name exactly default 1. 2 for start match expression<br/>
 * @param array $srch_options 
 * @return array | false an array of 'cms_channel' records or false if none found.
 */
$limit = 10;
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//if (isset($_REQUEST['limit'])) {
//    $limit = intval($_REQUEST['limit']);
if (isset($submit_post_get['limit'])) {
    $limit = intval($submit_post_get['limit']);
}

$page = 0;
//if (isset($_REQUEST['page'])) {
//    $page = intval($_REQUEST['page']);
if (isset($submit_post_get['page'])) {
    $page = intval($submit_post_get['page']);
}

$owner_id = null;
//if (isset($_REQUEST['owner_id'])) {
//    $owner_id = intval($_REQUEST['owner_id']);
if (isset($submit_post_get['owner_id'])) {
    $owner_id = intval($submit_post_get['owner_id']);
}

$category_id = null;
//if (isset($_REQUEST['category_id'])) {
//    $owner_id = intval($_REQUEST['category_id']);
if (isset($submit_post_get['category_id'])) {
    $owner_id = intval($submit_post_get['category_id']);
}

$name = null;
//if (isset($_REQUEST['name'])) {
//    $name = $_REQUEST['name'];
if (isset($submit_post_get['name'])) {
    $name = $submit_post_get['name'];
}

$cid = null;
$strict = 0;
//if (isset($_REQUEST['cid'])) {
//    $cid = $_REQUEST['cid'];
if (isset($submit_post_get['cid'])) {
    $cid = $submit_post_get['cid'];
    $strict = 1;
}

$search_opts = array(
    'limit' => $limit,
    'page' => $page,
    'field' => 'channel_name',
    'owner_id' => $owner_id,
    'category' => $category_id,
    'orderby' => 'id',
    'order' => 'a',
    'name' => $name,
    'url' => null,
    'id' => $cid,
    'strict_search' => $strict
);

$search = channelSearch($search_opts);
if ($search) {
    foreach ($search as $chan) {
        $res .= "<channel>";

        foreach ($chan as $k => $v) {
            if (is_string($k)) {
                $res .= "<" . safeTag($k) . ">" . safeXML($v) . "</" . safeTag($k) . ">";
            }
        }
        $res .= "<stats_connected_tubers>" . countConnectedtubers($chan['id']) . "</stats_connected_tubers>";
        $res .= "<stats_count_images>" . channelCountMediaInfo($chan['id'], "i", 0, 0, $fromdate = null, $todate = null) . "</stats_count_images>";
        $res .= "<stats_count_videos>" . channelCountMediaInfo($chan['id'], "v", 0, 0, $fromdate = null, $todate = null) . "</stats_count_videos>";

        $default_opts = array(
            'channelid' => $chan['id'],
            'n_results' => true,
        );

        $catalog_count = userCatalogchannelSearch($default_opts);

        $res .= "<stats_count_catalog>" . $catalog_count . "</stats_count_catalog>";

        //var_dump($chan);
        $res .= "<full_link_channel_logo>" . photoReturnchannelLogo($chan) . "</full_link_channel_logo>";
        $res .= "<full_link_channel_header>" . photoReturnchannelHeader($chan) . "</full_link_channel_header>";
        $res .= "<full_link_channel_bg>" . photoReturnchannelBG($chan) . "</full_link_channel_bg>";



        $res .= "</channel>";
    }
}
$res .= "</channel_search>";

//echo $res;
$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $res);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string wit