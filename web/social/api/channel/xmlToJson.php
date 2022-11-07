<?php
header("content-type: application/json; charset=utf-8");  
//include 'channelSingleBrochure.xml';

// converts XML content to JSON
// receives the URL address of the XML file. Returns a string with the JSON object
  $xml_cnt = file_get_contents('channelSingleBrochure.xml');    // gets XML content from file
  $xml_cnt = str_replace(array("\n", "\r", "\t"), '', $xml_cnt);    // removes newlines, returns and tabs

  // replace double quotes with single quotes, to ensure the simple XML function can parse the XML
  $xml_cnt = trim(str_replace('"', "'", $xml_cnt));
  $simpleXml = simplexml_load_string($xml_cnt);

  echo json_encode($simpleXml);    // returns a string with JSON object
