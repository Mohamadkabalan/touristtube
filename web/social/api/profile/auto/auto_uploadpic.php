<?php
	$expath = "../../";
	require_once ($expath."heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
	
//	$user_id = $_REQUEST['uid'];
	$user_id = $submit_post_get['uid'];
	
	
	//file_put_contents("log.txt",$_POST);
	
	/*
	$uploaddir = $mypath.'media/tubers/';
	$uploadfile = $_FILES['userfile']['name'];
	$profile_pic_name = 'Profile_' . time() . '_' . $user_id . ".jpg";
	
	$ret = array();
	
	if( !move_uploaded_file( $_FILES['userfile']['tmp_name'], $uploaddir . $uploadfile ) )
	{
		$ret['status'] = 'error';
		$ret['msg'] = "Couldn't move file.";
		echo 'error1';
	}
	else
	{
		
		if ( userSetProfilePic($user_id, $uploaddir . $uploadfile, $uploaddir . $profile_pic_name) ) 
		{
			$ret['path'] = ReturnLink('media/tubers/' . $profile_pic_name);
			echo 'ok';
		} 
		else 
		{
			echo 'error2';
			$ret['msg'] = "Couldn't set profile pic";
		}
		userCropPhoto($profile_pic_name);
		@unlink($uploaddir . $uploadfile);
	}*/
	
	$uploaddir = $CONFIG['server']['root'] . 'media/tubers/';
	$uploadfile = $_FILES['uploadfile']['name'];
	$profile_pic_name = 'Profile_' . time() . '_' . $user_id . ".jpg";
	
	if( !move_uploaded_file( $_FILES['uploadfile']['tmp_name'], $uploaddir . $uploadfile ) ){
		echo 'error 1';
	}else{
		
		//$newDims = photoShrinkDimensions($uploaddir . $uploadfile, 164 );
		//photoThumbnailCreate($uploaddir . $uploadfile, $uploaddir . 'cropable_' . $profile_pic_name , $newDims['width'], $newDims['height']);
		photoThumbnailCreate($uploaddir . $uploadfile, $uploaddir . $profile_pic_name , 164, 164);
		userCropPhoto($profile_pic_name);
		
		if ( userEdit(array('id' => $user_id, 'profile_Pic' => $profile_pic_name)) ){
			echo 'ok';
			
			@unlink($uploaddir . $uploadfile);
			
		} else {
			echo 'error 2';
		}
	}