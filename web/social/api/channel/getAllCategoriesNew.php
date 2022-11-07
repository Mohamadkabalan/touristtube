<?php

		

$expath = "../";
header("content-type: application/json; charset=utf-8");
include("../heart.php");



$res = "";

$allcat = allchannelGetCategory();

if ($allcat) {

    $res .= "<channel_categories>";

    foreach ($allcat as $cat) {
        $res .= "<channel_category>";
        $res .= "<id>" . safeXML($cat['id']) . "</id>";
        $res .= "<title>" . safeXML($cat['title']) . "</title>";
        $res .= "<image>" . safeXML("media/images/channel/mob_category/" . $cat['image']) . "</image>";
        $res .= "<published>" . safeXML($cat['published']) . "</published>";
        $res .= "</channel_category>";
    }
    $res .= "</channel_categories>";
}

//echo $res;

//$xml_cnt = file_get_contents('channelSingleBrochure.xml');    // gets XML content from file
$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $res);    // removes newlines, returns and tabs

// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string with JSON object