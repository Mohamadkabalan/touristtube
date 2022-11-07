<?php
$expath = "../";
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//$user_id = mobileIsLogged($_REQUEST['S']);
$user_id = mobileIsLogged($submit_post_get['S']);

if( !$user_id ) die();
$mypath = "../../";
$uploadPath = $mypath.$CONFIG['video']['uploadPath'];

$uploaddir = $uploadPath . 'posts/'.date('Y').'/'.date('W').'/';

$filename = $_FILES['filename']['name']; 
$vpath = $uploaddir;

//$post_text = isset($_REQUEST['post_text']) ? xss_sanitize($_REQUEST['post_text']) : '';
//$locationPost = isset($_REQUEST['locationPost']) ? xss_sanitize($_REQUEST['locationPost']) : '';
//$linkPost = isset($_REQUEST['linkPost']) ? xss_sanitize($_REQUEST['linkPost']) : '';
////$filename = isset($_REQUEST['filename']) ? xss_sanitize($_REQUEST['filename']) : '';
//$channel_id = isset($_REQUEST['channel_id']) ? intval($_REQUEST['channel_id']) : 0;
//$data_isvideo = isset($_REQUEST['data_isvideo']) ? intval($_REQUEST['data_isvideo']) : 0;
//$longitude = isset($_REQUEST['longitude']) ? doubleval($_REQUEST['longitude']) : 0;
//$lattitude = isset($_REQUEST['lattitude']) ? doubleval($_REQUEST['lattitude']) : 0;
//$data_profile = isset($_REQUEST['data_profile']) ? intval($_REQUEST['data_profile']) : 0;
//$data_profile = $user_id;
//$privacyValue = isset( $_REQUEST['privacyValue'] )? intval($_REQUEST['privacyValue']):2;
//$privacyArray = ( isset($_REQUEST['privacyArray']) ) ? $_REQUEST['privacyArray'] : array();
$post_text = isset($submit_post_get['post_text']) ? $submit_post_get['post_text'] : '';
$locationPost = isset($submit_post_get['locationPost']) ? $submit_post_get['locationPost'] : '';
$linkPost = isset($submit_post_get['linkPost']) ? $submit_post_get['linkPost'] : '';
//$filename = isset($_REQUEST['filename']) ? xss_sanitize($_REQUEST['filename']) : '';
$channel_id = isset($submit_post_get['channel_id']) ? intval($submit_post_get['channel_id']) : 0;
$data_isvideo = isset($submit_post_get['data_isvideo']) ? intval($submit_post_get['data_isvideo']) : 0;
$longitude = isset($submit_post_get['longitude']) ? doubleval($submit_post_get['longitude']) : 0;
$lattitude = isset($submit_post_get['latitude']) ? doubleval($submit_post_get['latitude']) : 0;
$data_profile = isset($submit_post_get['data_profile']) ? intval($submit_post_get['data_profile']) : 0;
//$data_profile = $user_id;
$privacyValue = isset( $submit_post_get['privacyValue'] )? intval($submit_post_get['privacyValue']):2;
$privacyArray = ( isset($submit_post_get['privacyArray']) ) ? $submit_post_get['privacyArray'] : array();


/*code added by sushma mishra on 30th september to assign values of userid and fromid as per data profile value starts from here*/
//$from_id = $user_id;
if($data_profile!=0){		
        $from_id = $user_id;
        $user_id = $data_profile;
}else{
        $from_id = $user_id;
}
/*code added by sushma mishra on 30th september ends here*/
$filename_new = '';
if(isset($_FILES['filename']) && isset($_FILES['filename']['name']) && !empty($_FILES['filename']['name'])){
    $path_parts = pathinfo($filename);
    $extension = pathinfo($filename,PATHINFO_EXTENSION);//$path_parts['extension'];
    $pic_name_time = time();
    $filename_new= 'posts'.'_'.$pic_name_time.'.' .$extension;
    $whereto=$uploaddir. '' . $filename_new;
    $fileInfotype = media_mime_type($whereto);
    $wheretoThumb = $uploaddir  . 'thumb/' .   $filename_new;
    $uploadPathInfoThumb = pathinfo($wheretoThumb);
    $uploadPathInfo = pathinfo($whereto);
    if(!file_exists($uploadPathInfo['dirname'])) mkdir($uploadPathInfo['dirname'], 0777, true);
    if(!file_exists($uploadPathInfoThumb['dirname'])) mkdir($uploadPathInfoThumb['dirname'], 0777, true);
    if(move_uploaded_file( $_FILES['filename']['tmp_name'], $whereto ) ){  
        if( $data_isvideo ){
//            echo $vpath . $filename_new.'<br>'.$vpath;exit;
            createThumbnailPost ( $CONFIG [ 'video' ] [ 'videoCoverter' ], $vpath . $filename_new, $vpath , "postThumb".$pic_name_time );
            $ret['upload_status']= 'sucess';  
    //        $first_thumb = getVideoThumbnail_Posts("postThumb".$pic_name_time,'../' . $uploaddir, 0 );
        }
        else{
            resizeUploadedImage($whereto, $whereto);
            if(photoThumbnailCreate($whereto, $uploaddir . 'thumb/' . $filename_new , 355, 197)){
                $ret['upload_status']= 'sucess';  
            }  
        }
    }
}
if($post_id = socialPostsAdd($user_id,$channel_id,$from_id,$post_text,1,$longitude,$lattitude,$linkPost,$locationPost,$filename_new,$data_isvideo) ){
    $users_ids_str='';
    $users_ids = array();
    $privacy_kind = array();
    if ($privacyValue == USER_PRIVACY_SELECTED) {
        foreach ($privacyArray as $privacy_with) {

            if (isset($privacy_with['friends'])) {
                $privacy_kind[] = USER_PRIVACY_COMMUNITY;
            } else if (isset($privacy_with['followers'])) {
                $privacy_kind[] = USER_PRIVACY_FOLLOWERS;
            } else if (isset($privacy_with['id'])) {
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

    $ret['status'] = 'ok';
    $ret['id'] = $post_id;
}else{
        $ret['status'] = 'error';
        $ret['msg'] = _('Couldn\'t save the information. Please try again later.');
}

echo json_encode($ret);
