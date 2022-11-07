<?php
/**
 * upload.php
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 */
 
function generateRandomAlphaNum($long)
{
	$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
	$length = $long;
	$res = '';
	for($i = 0; $i < $length; $i++)
	{
		$res .= $chars[mt_rand(0, 36)];
	}
	return $res;
}

// HTTP headers for no cache etc
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


// Settings
//$targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
$targetDir = 'media/chat_media';

$db_insert_id = 0;

$cleanupTargetDir = true; // Remove old files
$maxFileAge = 5 * 3600; // Temp file age in seconds

// 5 minutes execution time
@set_time_limit(5 * 60);

// Uncomment this one to fake upload time
// usleep(5000);

// Get parameters
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
//$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
$chunk = isset($submit_post_get["chunk"]) ? intval($submit_post_get["chunk"]) : 0;
$chunks = isset($submit_post_get["chunks"]) ? intval($submit_post_get["chunks"]) : 0;
//$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

//$originalFileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';
$originalFileName = isset($submit_post_get["name"]) ? $submit_post_get["name"] : '';

$a_ext = strrpos($originalFileName, '.');
$fileName="";
if ($a_ext!==false)
{
	$a_fileName_b = substr($originalFileName, $a_ext);
	$fileName = time().md5($originalFileName)."_".generateRandomAlphaNum(5)."a".$a_fileName_b;
}else
{
	$fileName = time().md5($originalFileName)."_".generateRandomAlphaNum(5);
}



// Clean the fileName for security reasons
$fileName = preg_replace('/[^a-z0-9A-Z\.]/', '_', $fileName);

// Make sure the fileName is unique but only if chunking is disabled
if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
	
	
	
	$imgsArray = array(".jpg",".gif",".png");
	$vidssArray = array(".mp4",".wmv",".flv",".f4v");
	
	
	
	$ext = strrpos($fileName, '.');
	
	
	$fileName_a = substr($fileName, 0, $ext);
	$fileName_b = substr($fileName, $ext);
	
	if(in_array($fileName_b,$imgsArray))
	{
		$mime = "image";
	}else{
		$mime = "video";	
	}

	$count = 1;
	while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
		$count++;

	$rowName = $fileName_a . '_' . $count;

	$fileName = $fileName_a . '_' . $count . $fileName_b;
	
	//db code
	
}

$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;



// Create target dir
if (!file_exists($targetDir))
	@mkdir($targetDir);

// Remove old temp files	
if ($cleanupTargetDir && is_dir($targetDir) && ($dir = opendir($targetDir))) {
	while (($file = readdir($dir)) !== false) {
		$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

		// Remove temp file if it is older than the max age and is not the current file
		if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
			@unlink($tmpfilePath);
		}
	}

	closedir($dir);
} else
	die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "'._('Failed to open temp directory.').'"}, "id" : "id"}');
	

// Look for the content type header
$HTTP_CONTENT_TYPE_server = $request->server->get('HTTP_CONTENT_TYPE', '');
$CONTENT_TYPE_server = $request->server->get('CONTENT_TYPE', '');
//if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
//	$contentType = $_SERVER["HTTP_CONTENT_TYPE"];
if (isset($HTTP_CONTENT_TYPE_server))
	$contentType = $HTTP_CONTENT_TYPE_server;

//if (isset($_SERVER["CONTENT_TYPE"]))
//	$contentType = $_SERVER["CONTENT_TYPE"];
if (isset($CONTENT_TYPE_server))
	$contentType = $CONTENT_TYPE_server;

// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
if (strpos($contentType, "multipart") !== false) {
	if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
		// Open temp file
		$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
		if ($out) {
			// Read binary input stream and append it to temp file
			$in = fopen($_FILES['file']['tmp_name'], "rb");

			if ($in) {
				while ($buff = fread($in, 4096))
					fwrite($out, $buff);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "'._('Failed to open input stream.').'"}, "id" : "id"}');
			fclose($in);
			fclose($out);
			@unlink($_FILES['file']['tmp_name']);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "'._('Failed to open output stream.').'"}, "id" : "id"}');
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "'._('Failed to move uploaded file.').'"}, "id" : "id"}');
} else {
	// Open temp file
	
	$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
	$thmb_out = fopen("{$filePath_thmb }.part", $chunk == 0 ? "wb" : "ab");
	
	if ($out) {
		// Read binary input stream and append it to temp file
		$in = fopen("php://input", "rb");
		
		$thumb_in = fopen("php://input", "rb");
		
		if($thumb_in)
		{
			while ($buff = fread($thumb_in, 4096))
				fwrite($thmb_out, $buff);	
		}
		
		if ($in) {
			while ($buff = fread($in, 4096))
				fwrite($out, $buff);
				fwrite($thmb_out, $buff);
				
				
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "'._('Failed to open input stream.').'"}, "id" : "id"}');

		fclose($in);
		fclose($out);
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "'._('Failed to open output stream.').'"}, "id" : "id"}');
}

// Check if file has been uploaded
if (!$chunks || $chunk == $chunks - 1) {
	// Strip the temp .part suffix off 
	rename("{$filePath}.part", $filePath);
}


$ret = array('filename' => $fileName);

echo json_encode($ret);

?>