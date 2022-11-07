<?php
/*! \file
 * 
 * \brief This api returns uploaded video or thumb
 * 
 *\todo <b><i>Change from string to Json object</i></b>
 * 
 * @param S session id
 * @param catalog_id catalog id
 * @param cityid city id 
 * @param vName video name 
 * @param vPath video path 
 * @param vSize video size 
 * @param title video title 
 * @param description video description 
 * @param category video category 
 * @param placetakenat video place taken at 
 * @param keywords video keywords
 * @param country video country
 * @param is_public video is public
 * 
 * @return either thumb path or id of the video:
 * @return <b>videoId</b>  video id
 * @return <b>thumb</b>  thumb path
 * @author Anthony Malak <anthony@touristtube.com>

 * 
 *  */
$tofile = "";
// fixing session issue



$myPath    = "../../";
$expath = '../';
include_once("../heart.php");

//$bootOptions = array ( "loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
//require_once ( $path . "inc/common.php" );
//require_once ( $path . "inc/bootstrap.php" ); 
//include_once ( "../heart.php");

//$userId = mobileIsLogged($_REQUEST['S']);
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$userId = mobileIsLogged($submit_post_get['S']);
if( !$userId ) {
    echo 'notSignedIn';
    exit();
}

//$catid = isset($_POST['catalog_id']) ? intval($_POST['catalog_id']) : 0;
$catid = intval($request->request->get('catalog_id', 0));

if( $catid!=0){
    $curr_date = date('Y-m-d');    
    $timequery = str_replace('-','',$curr_date);
    $timequery_image='i'.$timequery.'_'.$catid.'_'.$userId;
    $timequery_video='v'.$timequery.'_'.$catid.'_'.$userId;
}else{
    $timequery = time();
    $timequery_image='i'.$timequery.'_'.$userId;
    $timequery_video='v'.$timequery.'_'.$userId; 
}

//session_write_close(); 

// Fetch the city id.
//$city_id = db_sanitize ( $_POST ['cityid'] );
//$city_id = $request->request->get('cityid', '0');
$city_id = intval($submit_post_get['cityid']);

$tofile .= $userId."---";
//foreach($_POST as $k=>$v)
foreach($submit_post_get as $k=>$v)
{
	$tofile .= $k."=>".$v." |||||| ";	
}
file_put_contents("test.uppsave.txt",$tofile);
include_once ( $myPath . "inc/functions/videos.php" );


//$videoFile [ 'name' ] = db_sanitize($_POST['vName']); 
//$videoFile [ 'name' ] = $request->request->get('vName', ''); /*comment by mukesh*/
//$vPath = $request->request->get('vPath', '');                /*comment by mukesh*/
//$uploadPath = $myPath . $CONFIG [ 'video' ] [ 'uploadPath' ] . $_POST ['vPath'];
$dirname = $request->request->get('dirname');
$path = dirname($dirname);
$vPath = str_replace ( $myPath . $CONFIG [ 'video' ] [ 'uploadPath' ], '', $path );
$uploadPath = $myPath . $CONFIG [ 'video' ] [ 'uploadPath' ] . $vPath . '/';
//echo $vpath;die;

$reverse_path = dirname($dirname) ;

$filesArr = scandir($dirname);
$thumbs_res = array();
foreach ($filesArr as $key=>$fileinfo) {
    $videoFile = array();
    if(!($fileinfo=='.' || $fileinfo=='..')){
            //echo $fileinfo."<br>"; 
            //echo $dirname.$fileinfo;
            $temp_path = $dirname.$fileinfo;
            $new_path = $reverse_path.'/'.$fileinfo; 
//            copy($temp_path, $new_path);
            
        $pathinfo = pathinfo($fileinfo);
            $ext = $pathinfo['extension'];
//            $title = $_POST['title'];
            $title = $request->request->get('title', '');
            $title = remove_accents($title);
            $title = str_replace(' ', '-', $title);
            $title = str_replace(' ', '-', $title);
            $title = preg_replace('/[^a-z0-9A-Z\-]/', '', $title);
            $title = str_replace('--', '-', $title);

            $ts = time() . rand(100, 999);
            $new_filename = $title . '-' . $ts . '.' . $ext;
            //$new_filename = $ts . '-' . $title . '.' . $ext;
//            rename($new_path, $reverse_path. '/' . $new_filename);
            copy($temp_path, $reverse_path. '/' . $new_filename);
            $file = $reverse_path. '/' . $new_filename;
//            $videoFile['name'] = $new_filename;
            
        //echo $fileinfo;die;
        $videoFile['name'] = $new_filename;
        $videoFile['type'] = media_mime_type($file);
        //$file = $uploadPath . $videoFile [ 'name' ]; /*comment by mukesh*/
        //$file = $dirname . $videoFile ['name'];
      //echo $file;die;  
        $videoDetails = videoDetails (  $CONFIG [ 'video' ] [ 'videoCoverter' ], $uploadPath . $videoFile ['name'] );

        $VideoDetails = explode('|', $videoDetails);
        $videoDuration = $VideoDetails[0];
        $videoWidth = $VideoDetails[1];
        $videoHeight = $VideoDetails[2];

        //$videoFile [ 'size' ] = $_POST ['vSize'];  
        //$videoFile [ 'size' ] = $request->request->get('vSize', '');   /*comment by mukesh*/
//        $videoFile [ 'type' ] = media_mime_type( $file );	

        //$videoFile ['relativepath'] = db_sanitize($_POST ['vPath']);
        $videoFile ['relativepath']      = $vPath.'/';
        //$videoFile ['fullpath'] 	= $CONFIG [ 'video' ] [ 'uploadPath' ] . db_sanitize($_POST ['vPath']);
        $videoFile ['fullpath'] 	= $CONFIG [ 'video' ] [ 'uploadPath' ] . $vPath.'/';

        $videoFile ['userid'] 		= $userId;

        //$videoFile ['title']		= db_sanitize ( $_POST ['title'] );
        //$videoFile ['description']  = db_sanitize ( $_POST ['description'] );
        //$videoFile ['category']		= db_sanitize ( $_POST ['category'] );
        //$videoFile ['placetakenat'] = db_sanitize ( $_POST ['placetakenat'] );
        //$videoFile ['keywords']		= db_sanitize ( $_POST ['keywords'] );
        $videoFile ['title']		= $request->request->get('title', '');
        $videoFile ['description']      = $request->request->get('description', '');
        $videoFile ['category']		= $request->request->get('category', '0');
        $videoFile ['placetakenat']     = '';
        $videoFile ['keywords']		= '';
        if( ($cat_name = categoryGetName($videoFile['category'])) != false){
                $videoFile['keywords'] .= $cat_name;
        }
        //$videoFile ['country']		= db_sanitize ( $_POST ['country'] );
        $videoFile ['country']		= $request->request->get('country', '');
        $videoFile ['duration']		= db_sanitize ( $videoDuration );
        $videoFile ['dimension']	= db_sanitize ( $videoWidth.' X '.$videoHeight );
        //$videoFile ['lattitude']	= doubleval ( $_POST ['lattitude'] );
        //$videoFile ['longitude']	= doubleval ( $_POST ['longitude'] );
        //$videoFile ['is_public']	= intval($_POST ['is_public']);
//        $videoFile ['is_public']	= intval($request->request->get('is_public', ''));
//        $privacy_kind_str = $videoFile ['is_public'];
        $privacyValue = intval($submit_post_get['is_public']);
        $privacyArray = '';
	$privacy_kind_media=USER_PRIVACY_PUBLIC;
        
        // user media privacy
	if( $privacyValue !=-1 ){ 
		
		$users_ids = array();
		$privacy_kind = array();
		if($privacyValue==USER_PRIVACY_SELECTED){
			foreach($privacyArray as $privacy_with){
				
				if( isset($privacy_with['friends']) ){
					$privacy_kind[] = USER_PRIVACY_COMMUNITY;
				}else if( isset($privacy_with['followers']) ){
					$privacy_kind[] = USER_PRIVACY_FOLLOWERS;			
				}else if( isset($privacy_with['id']) ){			
					$users_id = intval( $privacy_with['id'] );
					if (!in_array($users_id, $users_ids)) {
						$users_ids[] = $users_id;
					}	
					
				}
				
			}
		}else{
			$privacy_kind[] = $privacyValue;
		}
		if(sizeof($privacy_kind)>=2){
			$users_ids = array();	
		}
		$users_ids_str=join(",",$users_ids);
		$privacy_kind_str=join(",",$privacy_kind);
		
		if($privacyValue!=-1){
			if($privacy_kind_str=='' && sizeof($privacy_kind)>1){
				$privacy_kind_str=USER_PRIVACY_SELECTED;
			}
			if(sizeof($users_ids)>0 && $privacy_kind_str==''){
				$privacy_kind_str=USER_PRIVACY_SELECTED;	
			}
			$privacy_kind_media = $privacy_kind_str;
			if(sizeof($users_ids)>0){
				$privacy_kind_media=USER_PRIVACY_SELECTED;	
			}
			if(sizeof($privacy_kind)>1){
				$privacy_kind_media = USER_PRIVACY_SELECTED;
			}
		}
	} 


        $videoFile['is_public'] = $privacy_kind_media;

//        if( !in_array($videoFile ['is_public'],array(0,1,2) ) ) $videoFile ['is_public'] = 0;

        //$videoFile['location_id']	= intval($_POST['location']);
        //$videoFile['cityname']	= db_sanitize($_POST ['cityname']);

        //$videoFile['cityid']	= getCityId( $videoFile['cityname'] , '' , $videoFile ['country'] );
        $videoFile['cityid'] = $city_id;
        $videoFile['cityname'] = getCityName($city_id);

        if( $videoFile['cityid'] === false){
                file_put_contents("cityiddie.txt","died");
                die('cityid');
        }


        $videoFile['channelid'] = 0;
        $videoFile['catalog_id'] = $catid;

        $id = videoExists2($videoFile['name'],$userId);
        if( !$id ){
            if (strstr($videoFile['type'], 'image/') == null) {
                $videoFile['timequery']=$timequery_video;
            }else{
                $videoFile['timequery']=$timequery_image;
            }
            $videoId = saveVideo ( $videoFile, $dbConn);
        }else{
            $videoId = $id;
            updateVideo2 ($id, $videoFile );
        }

        $vInfo   = getVideoInfo ( $videoId );
        userPrivacyExtandEdit(array('user_id' => $userId, 'entity_type' => SOCIAL_ENTITY_MEDIA, 'entity_id' => $videoId, 'kind_type' => $privacy_kind_str, 'users' => ''));

        //if video => output id so use can select from list of thumbs
        //if image just output thumb
        if( strstr($videoFile[ 'type' ], 'image/') == null ){
               createThumbnail ( $CONFIG [ 'video' ] [ 'videoCoverter' ], $uploadPath . $videoFile ['name'], $uploadPath , $vInfo['code'] );
               $thumbs_res[] =$videoId;
        }else{
            /*$path_parts = pathinfo($uploadPath . $videoFile ['name']);
            $thumb = $path_parts['dirname'] . '/thumb_' . $path_parts['filename'] . '.jpg';

                copy($uploadPath . $videoFile ['name'], $uploadPath . 'org_' . $videoFile ['name'] );
                //resize to fit in photoviewer
                resizeUploadedImage($uploadPath . $videoFile ['name'],$uploadPath . $videoFile ['name']);
                //resize to fit in small photoviewer
                resizeUploadedImage2($uploadPath . $videoFile ['name'],$uploadPath . 'med_' . $videoFile ['name']);
                //resize to fit in profile
                resizeUploadedImage3($uploadPath . $videoFile ['name'],$uploadPath . 'small_' . $videoFile ['name']);

            createThumbnailFromImage($uploadPath . $videoFile ['name'], $thumb);*/
//echo 'test ';
//echo $uploadPath.$videoFile ['name'].' ';
            $path_parts = pathinfo($uploadPath . $videoFile ['name']);
            $thumb = $path_parts['dirname'] . '/thumb_' . $videoFile ['name'];
            //make a copy of original
            copy($uploadPath . $videoFile ['name'], $uploadPath . 'org_' . $videoFile ['name'] );
            //resize to fit in photoviewer
            resizeUploadedImage($uploadPath . $videoFile ['name'],$uploadPath . $videoFile ['name']);
            //resize to fit in small photoviewer
            resizeUploadedImage2($uploadPath . $videoFile ['name'],$uploadPath . 'med_' . $videoFile ['name']);
            //resize to fit in profile
            resizeUploadedImage3($uploadPath . $videoFile ['name'],$uploadPath . 'small_' . $videoFile ['name']);
            resizeUploadedImage4($uploadPath . $videoFile ['name'],$uploadPath . 'xsmall_' . $videoFile ['name']);
            //create thumbnail
            createThumbnailFromImage($uploadPath . $videoFile ['name'], $thumb);
            $thumbs_res[] = $thumb;
        }
        
    }
}
header('Content-type: application/json');
echo json_encode($thumbs_res);
foreach(glob($dirname.'*.*') as $v){
    unlink($v);
}
rmdir($dirname);
//unlink($dirname);



