<?php
//	$post_type = intval($_POST['post_type']);
//	$post_text = isset($_POST['post_text']) ? xss_sanitize($_POST['post_text']) : '';
//	$locationPost = isset($_POST['locationPost']) ? xss_sanitize($_POST['locationPost']) : '';
//	$linkPost = isset($_POST['linkPost']) ? xss_sanitize($_POST['linkPost']) : '';
//	$filename = isset($_POST['filename']) ? xss_sanitize($_POST['filename']) : '';
//	$channel_id = isset($_POST['channel_id']) ? intval($_POST['channel_id']) : 0;
//	$data_isvideo = isset($_POST['data_isvideo']) ? intval($_POST['data_isvideo']) : 0;
//	$longitude = isset($_POST['longitude']) ? doubleval($_POST['longitude']) : 0;
//	$lattitude = isset($_POST['lattitude']) ? doubleval($_POST['lattitude']) : 0;
//	$data_profile = isset($_POST['data_profile']) ? intval($_POST['data_profile']) : 0;
//        $privacyValue = isset( $_POST['privacyValue'] )? intval($_POST['privacyValue']):2;
//        $privacyArray = ( isset($_POST['privacyArray']) ) ? $_POST['privacyArray'] : array();
	$post_type = intval($request->request->get('post_type', 0));
	$post_text = $request->request->get('post_text', '');
	$locationPost = $request->request->get('locationPost', '');
	$linkPost = $request->request->get('linkPost', '');
	$filename = $request->request->get('filename', '');
	$channel_id = intval($request->request->get('channel_id', 0));
	$data_isvideo = intval($request->request->get('data_isvideo', 0));
	$longitude = doubleval($request->request->get('longitude', 0));
	$lattitude = doubleval($request->request->get('lattitude', 0));
	$data_profile = intval($request->request->get('data_profile', 0));
        $privacyValue = intval($request->request->get('privacyValue', 2));
        $privacyArray = $request->request->get('privacyArray', array());
	$from_id = 0;
	if($data_profile!=0){
		$user_id = $data_profile;
		$from_id = userGetID();
	}else{
		$user_id = userGetID();
	}
	
	if($post_id = socialPostsAdd($user_id,$channel_id,$from_id,$post_text,$post_type,$longitude,$lattitude,$linkPost,$locationPost,$filename,$data_isvideo) ){
            $users_ids_str='';
            $users_ids = array();
            $privacy_kind = array();
            if ($privacyValue == USER_PRIVACY_SELECTED) {
                foreach ($privacyArray as $privacy_with) {
                    if (isset($privacy_with['id'])) {
                        $users_id = intval($privacy_with['id']);
                        if (!in_array($users_id, $users_ids)) {
                            $users_ids[] = $users_id;
                        }
                    }
                }
            } else {
                $privacy_kind[] = $privacyValue;
            }
            if (sizeof($privacy_kind) >= 2) {
                $users_ids = array();
            }
            $users_ids_str = join(",", $users_ids);
            $privacy_kind_str = join(",", $privacy_kind);
            if ($privacyValue != -1) {
                if ($privacy_kind_str == '' && sizeof($privacy_kind) > 1) {
                    $privacy_kind_str = USER_PRIVACY_SELECTED;
                }
                if (sizeof($users_ids) > 0 && $privacy_kind_str == '') {
                    $privacy_kind_str = USER_PRIVACY_SELECTED;
                }
                $privacy_kind_media = $privacy_kind_str;
                if (sizeof($users_ids) > 0) {
                    $privacy_kind_media = USER_PRIVACY_SELECTED;
                }
                if (sizeof($privacy_kind) > 1) {
                    $privacy_kind_media = USER_PRIVACY_SELECTED;
                }
            }
            if($data_profile!=0 && $user_id!=$user_id){
                $arrayPrivacyExtand = GetUserPrivacyExtand($user_id, 0, SOCIAL_ENTITY_POST);
                if(!$arrayPrivacyExtand){
                    $privacy_kind_str = USER_PRIVACY_PUBLIC;
                    $users_ids_str = '';
                }else{
                    $privacy_kind_str = $arrayPrivacyExtand['kind_type'];
                    $users_ids_str = $arrayPrivacyExtand['users'];
                }                
            }
            userPrivacyExtandEdit(array('user_id' => $user_id, 'entity_type' => SOCIAL_ENTITY_POST, 'entity_id' => $post_id, 'kind_type' => $privacy_kind_str, 'users' => $users_ids_str));
		
            $ret_arr['status'] = 'ok';
            $ret_arr['id'] = $post_id;
	}else{
		$ret_arr['status'] = 'error';
		$ret_arr['msg'] = _('Couldn\'t save the information. Please try again later.');
	}
	
?>
