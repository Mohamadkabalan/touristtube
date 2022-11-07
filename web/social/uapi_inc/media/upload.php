<?php

if (is_null($user_id)) {
	$ret_arr['status'] = 'error';
	$ret_arr['error_msg'] = 'Not logged in';
} else {

	if (!isset($_FILES['uploadfile'])) {
		$ret_arr['status'] = 'error';
		$ret_arr['error_msg'] = "Missing upload file called 'uploadfile'";
		break;
	}

	$uploadPath = getUploadDirTree($path . $CONFIG ['video'] ['uploadPath']);
	$videoFile = uploadVideo($_FILES, $uploadPath);
	$videoFile['relativepath'] = str_replace($CONFIG ['video'] ['uploadPath'], '', $uploadPath);

	//////////////////////////////////
	//copied from up_save.php

	$videoDetails = videoDetails($CONFIG ['video'] ['videoCoverter'], $uploadPath . $videoFile ['name']);

	$VideoDetails = explode('|', $videoDetails);
	$videoDuration = $VideoDetails[0];
	$videoWidth = intval($VideoDetails[1]);
	$videoHeight = intval($VideoDetails[2]);

	if ($videoWidth == 0 || $videoHeight == 0) {
		$ret_arr['status'] = 'error';
		$ret_arr['error_msg'] = "Couldn't get media information";
		@unlink($uploadPath . $videoFile['name']);
		break;
	}

//	$videoFile ['size'] = db_sanitize($_POST ['vSize']);
	$videoFile ['size'] = $request->request->get('vSize', '');
	$videoFile ['type'] = media_mime_type($uploadPath . $videoFile ['name']);

	if (strstr($videoFile['type'], 'image') == null && !mimeIsVideo($videoFile['type'])) {
		$ret_arr['status'] = 'error';
		$ret_arr['error_msg'] = "Upload not mediaFile";
		@unlink($uploadPath . $videoFile['name']);
		break;
	}

	//rename upload with timestamp and title
	$ts = microtime();
	$ts = str_replace(' ', '', $ts);
	$ts = str_replace('0.', '', $ts) . rand(0, 1000);
	$pathinfo = pathinfo($videoFile['name']);
	$ext = $pathinfo['extension'];
//	$title = $_POST['title'];
	$title = $request->request->get('title', '');
	$title = remove_accents($title);
	$title = str_replace(' ', '-', $title);
	$title = str_replace(' ', '-', $title);
	$title = preg_replace('/[^a-z0-9A-Z\-]/', '', $title);
	$title = str_replace('--', '-', $title);
	$new_filename = $ts . '-' . $title . '.' . $ext;
	rename($uploadPath . $videoFile ['name'], $uploadPath . $new_filename);

	$videoFile ['name'] = $new_filename;

	//convert tiff to jpeg TODO: convert all to jpeg
	if ($videoFile['type'] == 'image/tiff') {
		$path_parts = pathinfo($videoFile ['name']);
		$new_filename = $path_parts['filename'] . '.jpg';
		$renamed = 'org_' . $videoFile['name'];
		convertImage($uploadPath . $videoFile['name'], $uploadPath . $new_filename);
		rename($uploadPath . $videoFile['name'], $uploadPath . $renamed);
		$videoFile['type'] = 'image/jpeg';
		$videoFile['name'] = $new_filename;
	}

	$videoFile['type'] = mime_content_type($uploadPath . $videoFile ['name']);

	$videoFile['fullpath'] = $CONFIG ['video'] ['uploadPath'] . $videoFile ['relativepath'];

	$videoFile['userid'] = $user_id;

//	$videoFile['title'] = db_sanitize($_POST['title']);
//	$videoFile['description'] = db_sanitize($_POST['description']);
//	$videoFile['category'] = db_sanitize($_POST['category']);
//	$videoFile['placetakenat'] = db_sanitize($_POST['placetakenat']);
//	$videoFile['keywords'] = db_sanitize($_POST['keywords']);
	$videoFile['title']         = $request->request->get('title', '');
	$videoFile['description']   = $request->request->get('description', '');
	$videoFile['category']      = $request->request->get('category', '');
	$videoFile['placetakenat']  = $request->request->get('placetakenat', '');
	$videoFile['keywords']      = $request->request->get('keywords', '');
	if (($cat_name = categoryGetName($videoFile['category'])) != false) {
		$videoFile['keywords'] .= ', ' . $cat_name;
	}
//	$videoFile['country'] = db_sanitize(strtoupper(substr($_POST['country'], 0, 2)));
	$videoFile['country'] = db_sanitize(strtoupper(substr($request->request->get('country', ''), 0, 2)));
	$videoFile['duration'] = db_sanitize($videoDuration);
	$videoFile['dimension'] = db_sanitize($videoWidth . ' X ' . $videoHeight);
//	$videoFile['is_public'] = isset($_POST ['is_public']) ? intval($_POST ['is_public']) : 0;
	$videoFile['is_public'] = intval($request->request->get('is_public', 0));

	if (!in_array($videoFile['is_public'], array(0, 1, 2, 3, 4)))
		$videoFile['is_public'] = 0;

//	$videoFile['cityname'] = db_sanitize($_POST ['cityname']);
	$videoFile['cityname'] = $request->request->get('cityname', '');

	$videoFile['cityid'] = getCityId($videoFile['cityname'], '', $videoFile['country']);

	if ($videoFile['cityid'] === false) {
		$ret_arr['status'] = 'error';
		$ret_arr['error_msg'] = "Couldn't find city '{$videoFile['cityname']}' in country '{$videoFile['country']}' ";
		@unlink($uploadPath . $videoFile['name']);
		break;
	}

	$videoFile['location_id'] = 0;
	$videoFile['trip_id'] = 0;

//	$videoFile ['lattitude'] = isset($_POST ['lattitude']) ? doubleval($_POST ['lattitude']) : 0;
//	$videoFile ['longitude'] = isset($_POST ['longitude']) ? doubleval($_POST ['longitude']) : 0;
	$videoFile ['lattitude'] = doubleval($request->request->get('lattitude', 0));
	$videoFile ['longitude'] = doubleval($request->request->get('longitude', 0));
	if (($videoFile['lattitude'] == 0) || ($videoFile['longitude'] == 0)) {
		list($lat, $long) = cityGetLocation($videoFile['cityid']);
		$videoFile['lattitude'] = $lat;
		$videoFile['longitude'] = $long;
	}

//	$videoFile['catalog_id'] = isset($_POST['catalog_id']) ? intval($_POST['catalog_id']) : 0;
	$videoFile['catalog_id'] = intval($request->request->get('catalog_id', 0));

//	$videoFile['default_catalog_icon'] = isset($_POST['defaultcatalogicon']) ? intval($_POST['defaultcatalogicon']) : 0;
	$videoFile['default_catalog_icon'] = intval($request->request->get('defaultcatalogicon', 0));

	$id = saveVideo($videoFile);

	if (strstr($videoFile['type'], 'image/') == null) {
		$vinfo = videoGetInfo($id);
		createThumbnail($CONFIG ['video'] ['videoCoverter'], $uploadPath . $videoFile ['name'], $uploadPath, $vinfo['code']);
	} else {
		$path_parts = pathinfo($uploadPath . $videoFile ['name']);
		$thumb = $path_parts['dirname'] . '/thumb_' . $path_parts['filename'] . '.jpg';
		//make a copy of original
		copy($uploadPath . $videoFile ['name'], $uploadPath . 'org_' . $videoFile ['name']);
		//resize to fit in photoviewer
		resizeUploadedImage($uploadPath . $videoFile ['name'], $uploadPath . $videoFile ['name']);
		//resize to fit in small photoviewer
		resizeUploadedImage2($uploadPath . $videoFile ['name'], $uploadPath . 'med_' . $videoFile ['name']);
		//resize to fit in profile
		resizeUploadedImage3($uploadPath . $videoFile ['name'], $uploadPath . 'small_' . $videoFile ['name']);
                resizeUploadedImage4($uploadPath . $videoFile ['name'], $uploadPath . 'xsmall_' . $videoFile ['name']);
		//create thumbnail
		createThumbnailFromImage($uploadPath . $videoFile ['name'], $thumb);
	}

	//////////////////////////////////////

	$ret_arr['status'] = 'ok';
	$ret_arr['upload']['name'] = $videoFile['name'];
	$ret_arr['upload']['path'] = $videoFile ['relativepath'];
	$ret_arr['upload']['size'] = $videoFile['size'];
	$ret_arr['upload']['id'] = $id;
}

?>