<?php

	if(is_null($user_id)){
		$ret_arr['status'] = 'error';
		$ret_arr['error_msg'] = 'Not logged in';
	}else{
//		$album_name = db_sanitize($_POST['album_name']);
		$album_name = $request->request->get('album_name', '');
		$album_id = userCatalogAdd($user_id, $album_name);
		if( !$album_id ){
			$ret_arr['status'] = 'error';
			$ret_arr['error_msg'] = 'Couldnt create album.';
		}else{
			$ret_arr['status'] = 'ok';
			$ret_arr['album_id'] = $album_id;
		}
	}
	
?>
