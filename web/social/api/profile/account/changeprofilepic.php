<?php
/*! \file
 * 
 * \brief This api to change profile picture
 * 
 * \todo <b><i>Change from string to Json object</i></b>
 * 
 * @param S session id
 * @param userfile contains all info of the file
 * 
 * @return string of result if changed or not
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */

//	session_id($_REQUEST['S']);
//	session_start();
	$expath = "../../";
	include($expath."heart.php");
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
	 $mypath = "../../../";
//	$user_id = $_SESSION['id'];
//        $user_id = mobileIsLogged($_REQUEST['S']);
        $user_id = mobileIsLogged($submit_post_get['S']);
        if( !$user_id ) die();
	$uploaddir = $CONFIG['server']['root'] . 'media/tubers/';
	$uploadfile = $_FILES['userfile']['name'];
        $extension = pathinfo($uploadfile,PATHINFO_EXTENSION);
        $filename = time() . '_' . $user_id . '.' . $extension;
	$profile_pic_name = 'Profile_' . $filename;
//        echo $uploaddir . $filename . '<br>';
//        echo file_exists($_FILES['userfile']['tmp_name']);exit();
//        print_r( $_FILES).'<br>';exit();
        $result = array();
	if( !move_uploaded_file( $_FILES['userfile']['tmp_name'], $uploaddir . $filename ) ){
                $result['status'] = 'error';
		//echo 'error';
	}else{
            photoThumbnailCreate($uploaddir. $filename , $uploaddir . 'cropable_' . $profile_pic_name , 130, 130);     
            photoThumbnailCreate($uploaddir. $filename , $uploaddir . $profile_pic_name , 130, 130);
            $ret = userSetProfilePic($user_id, $uploaddir . $profile_pic_name, $uploaddir . $profile_pic_name);           
            if( $ret ){
                userCropPhoto($profile_pic_name);
//                @unlink($uploaddir . $uploadfile);
                //echo 'ok';
                $result['status'] = 'ok';
            }else{
                //echo 'error';
                $result['status'] = 'error';
            }
	}
        echo json_encode($result);
//	ob_flush();