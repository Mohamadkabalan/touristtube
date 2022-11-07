<?php

// fixing session issue


$expath = "../";
require_once($expath . "heart.php");

$cats = categoryGetHash();

$rs = "<categories>";
foreach ($cats as $key => $cat) {
    $rs .= "<category id='" . $key . "'>" . safeXML($cat) . "</category>";
}
$rs .= "</categories>";
header("content-type: application/json; charset=utf-8");
//echo $rs;
$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $rs);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string wit