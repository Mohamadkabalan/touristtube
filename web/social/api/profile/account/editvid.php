<?php
/*! \file
 * 
 * \brief This api to edit a video 
 * 
 * \todo <b><i>Change the output(no output) </i></b>
 * 
 * @param vid video id
 * @param title video title
 * @param description video description
 * @param category video category
 * @param placetakenat video place taken at
 * @param country video country
 * @param location video location
 * @param keywords video keywords
 * @param cityname video city name
 * @param country video country
 * @param is_public video is public or not
 * 
 * @return no output yet
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */		
	//session_id( $_REQUEST['S'] );
	//$bootOptions = array ( "loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);
	header('Content-type: application/json');
	$expath = '../../';
	include_once("../../heart.php");
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
   	$user_id = mobileIsLogged($submit_post_get['S']);
	//$id = $_SESSION['id'];
      
	
//	$vid = $_REQUEST['vid'];
        $vid = $submit_post_get['vid'];
        $vinfo = getVideoInfo($vid);
        
//        if(!$user_id || intval($user_id) != intval($vid) ){
//            exit();
//        }
	
	if ($vinfo['userid'] != $user_id){
            exit();
        }
        
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

//	if (isset($_REQUEST['title'])){
//		$vinfo['title'] = $_REQUEST['title'];
//	}
//	if (isset($_REQUEST['description'])){
//		$vinfo['description'] = $_REQUEST['description'];
//	}
//	if (isset($_REQUEST['category'])){
//		$vinfo['category'] = $_REQUEST['category'];
//	}
//	if (isset($_REQUEST['placetakenat'])){
//		$vinfo['placetakenat'] = $_REQUEST['placetakenat'];
//	}
//	if (isset($_REQUEST['country'])){
//		$vinfo['country'] = $_REQUEST['country'];
//	}
//	if (isset($_REQUEST['location'])){
//		$vinfo['location'] = $_REQUEST['location'];
//	}
//	if (isset($_REQUEST['keywords'])){
//		$vinfo['keywords'] = $_REQUEST['keywords'];
//	}
//	if (isset($_REQUEST['cityname'])){
//		$vinfo['cityid'] = getCityId($_REQUEST['cityname'],'',$_REQUEST['country']);
//		$vinfo['cityname'] = $_REQUEST['cityname'];
//	}
//	if (isset($_REQUEST['is_public'])){
//		$vinfo['is_public'] = $_REQUEST['is_public'];	
//	}
        
        $videoFile = array();
        $city_id = intval($submit_post_get['cityid']);
        $videoFile['cityid'] = $city_id;
        $videoFile['cityname'] = getCityName($city_id);
	$videoFile['id'] = $vid;
	if (isset($submit_post_get['title'])){
		$videoFile['title'] = $submit_post_get['title'];
	}
	if (isset($submit_post_get['description'])){
		$videoFile['description'] = $submit_post_get['description'];
	}
	if (isset($submit_post_get['category'])){
		$videoFile['category'] = intval($submit_post_get['category']);
	}
	if (isset($submit_post_get['placetakenat'])){
		$videoFile['placetakenat'] = $submit_post_get['placetakenat'];
	}
	if (isset($submit_post_get['country'])){
		$videoFile['country'] = $submit_post_get['country'];
	}
	if (isset($submit_post_get['location'])){
		$videoFile['location'] = $submit_post_get['location'];
	}
	if (isset($submit_post_get['keywords'])){
		$videoFile['keywords'] = $submit_post_get['keywords'];
	}
//	if (isset($submit_post_get['cityname'])){
//		$videoFile['cityid'] = getCityId($submit_post_get['cityname'],'',$submit_post_get['country']);
//		$videoFile['cityname'] = $submit_post_get['cityname'];
//	}
       
        
//	if (isset($submit_post_get['is_public'])){
//		$vinfo['is_public'] = $submit_post_get['is_public'];	
//	}
	$videoFile['is_public'] = $privacy_kind_media;
//        $trip_id = 0;
//	$videoFile['trip_id'] = $trip_id;
        
        //echo "<pre>";print_r($videoFile);die;
       // videoEdit($videoFile);
	if(videoEdit($videoFile)){ 
            if( isset($privacyValue) && $privacyValue!=-1 ){
                userPrivacyExtandEdit(array('user_id'=>$user_id,'entity_type'=>SOCIAL_ENTITY_MEDIA,'entity_id'=>$vid,'kind_type'=>$privacy_kind_str,'users'=>$users_ids_str));
            }
            $ret['status'] = 'ok';
        }
        else{
            $ret['status'] = 'error';
        }
        
        echo json_encode($ret);
        