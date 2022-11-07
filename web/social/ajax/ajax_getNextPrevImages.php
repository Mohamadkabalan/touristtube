<?php
	$path = "../";
	
	
	$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
	include_once ( $path . "inc/common.php" );
	include_once ( $path . "inc/bootstrap.php" );

	include_once ( $path . "inc/functions/videos.php" );
	include_once ( $path . "inc/functions/users.php" );
	
	
	
//    $id=intval($_POST['id']);
//    $page=intval($_POST['page']);
//    $data_id=intval($_POST['data_id']);
//    $favorite_data_id=intval($_POST['favorite_data_id']);
//    $is_album=intval($_POST['is_album']);
    $id=intval($request->request->get('id', 0));
    $page=intval($request->request->get('page', 0));
    $data_id=intval($request->request->get('data_id', 0));
    $favorite_data_id=intval($request->request->get('favorite_data_id', 0));
    $is_album=intval($request->request->get('is_album', 0));
    
    $loggedUser = userGetID();
    $favorite = 0;
    if (userFavoriteAdded($loggedUser, $favorite_data_id)) {
        $favorite = 1;
    }
    $VideoInfo_fav = getVideoInfo($favorite_data_id);
    $sh_title = htmlEntityDecode($VideoInfo_fav['title']);
    $description_db = htmlEntityDecode($VideoInfo_fav['description'],0);
    $sh_description = str_replace("\n", "<br/>", $description_db);
    $uricurpage = currentServerURL();
    
    $Result = array();
    if($is_album==0){
        if( $VideoInfo_fav['image_type']=='v' ){
            $sh_link = $uricurpage . ReturnVideoUriHashed($VideoInfo_fav);
        }else{
            $sh_link = $uricurpage . ReturnPhotoUriHashed($VideoInfo_fav);
        }
        $VideoInfo = getVideoInfo($id);
        $photos1 = videosGetRelatedSolr($VideoInfo,null,1,$page);
        if( intval($photos1['total']>0) ){
                $array_next = $photos1['media'][0];
                $Result['photo_next_big'] = photoReturnSrc($array_next);
                $Result['next_title'] = htmlEntityDecode($array_next['title']);
                $Result['next_id'] = $array_next['id'];
        }else{
                $array_next = '';	
                $Result['photo_next_big'] = '';
                $Result['next_title'] ='';
                $Result['next_id'] = '';
        }
        $photos2 = null;
        if($page>1){
            $photos2 = videosGetRelatedSolr($VideoInfo,null,1,($page-2)); 
            if( intval($photos2['total']==0) ) $photos2=false;
            $photos3 = videosGetRelatedSolr($VideoInfo,null,1, ($page-1) );
            $array_view = $photos3['media'][0];
            //VideoIncViews($array_view['id']); 
            $Result['inc_id'] = $array_view['id'];
        }else if($page==1){
            $photos2['media'] = array();
            $photos2['media'][] = $VideoInfo;
            $photos3 = videosGetRelatedSolr($VideoInfo,null,1, ($page-1) );
            $array_view = $photos3['media'][0];
            //VideoIncViews($array_view['id']);
            $Result['inc_id'] = $array_view['id'];
        }else{
            //VideoIncViews($id);
            $Result['inc_id'] = $id;
        }
        if( $photos2 ){
                $array_prev = $photos2['media'][0];
                $Result['photo_prev_big'] = photoReturnSrc($array_prev);
                $Result['prev_title'] = htmlEntityDecode($array_prev['title']);
                $Result['prev_id'] = $array_prev['id'];
        }else{
                $array_prev = '';	
                $Result['photo_prev_big'] = '';
                $Result['prev_title'] ='';
                $Result['prev_id'] = '';
        }
    }else{
        if( $VideoInfo_fav['image_type']=='v' ){
            $sh_link = $uricurpage . ReturnVideoAlbumUri($VideoInfo_fav);
        }else{
            $sh_link = $uricurpage . ReturnPhotoAlbumUri($VideoInfo_fav);
        }
        $AlbumInfo = userCatalogGet($id);
        $real_channel_id = NULL;
        $channelid = intval($AlbumInfo['channelid']);
        if ($channelid != 0) {
            $real_channel_id = $channelid;
        }
        $loggedUser = userGetID();
        $userId = $AlbumInfo['user_id'];

        if($loggedUser !=intval($userId)){
                $is_owner=0;
        }else{
                $is_owner=1;
        }

        $media_catalog_list_id_array = mediaCatalogListId(array('catalog_id' => $id, 'channelid' => $real_channel_id, 'type' => 'i', 'orderby' => 'id', 'order' => 'd', 'is_owner' => $is_owner));
        $position = intval(array_search($data_id, $media_catalog_list_id_array)) + 1;
        $position = (intval($position) != 0) ? intval($position) : 1;
        $position = $position + $page;
        
        $options_prev = array(
            'limit' => 1,
            'page' => ($position - 2),
            'userid' => $userId,
            'catalog_id' => $id,
            'is_owner' => $is_owner,
            'channel_id' => $real_channel_id,
            'orderby' => 'id',
            'order' => 'd',
            'type' => 'i'
        );
        $options_next = array(
            'limit' => 1,
            'page' => $position,
            'userid' => $userId,
            'catalog_id' => $id,
            'is_owner' => $is_owner,
            'channel_id' => $real_channel_id,
            'orderby' => 'id',
            'order' => 'd',
            'type' => 'i'
        );
        $privacy = 0;
        if (userIsLogged() && $is_owner==1 ) {
            $privacy = 0;
        } else if ( userIsLogged() && $is_owner==0 ) {
            if (userIsFriend($userId, $loggedUser)) {
                $privacy = 1;
            } else {
                $privacy = 2;
            }
        } else {
            $privacy = 2;
        }
        $options_next['public'] = $privacy;
        $options_prev['public'] = $privacy;
        $Result['inc_id'] = $id;

        $array_next = mediaSearch($options_next);
        if ($array_next) {
            $array_next = $array_next[0];           
            $Result['photo_next_big'] = photoReturnSrc($array_next);
            $Result['next_title'] = htmlEntityDecode($array_next['title']);
            $Result['next_id'] = $array_next['id'];        
        } else {
            $array_next = '';	
            $Result['photo_next_big'] = '';
            $Result['next_title'] ='';
            $Result['next_id'] = '';
        }
        $array_prev = mediaSearch($options_prev);

        if ($array_prev) {
            $array_prev = $array_prev[0];
            $Result['photo_prev_big'] = photoReturnSrc($array_prev);
            $Result['prev_title'] = htmlEntityDecode($array_prev['title']);
            $Result['prev_id'] = $array_prev['id'];
        } else {
            $array_prev = '';
            $Result['photo_prev_big'] = '';
            $Result['prev_title'] ='';
            $Result['prev_id'] = '';
        }
    }
    $Result['favorite']=$favorite;
    $Result['sh_link']=$sh_link;
    $Result['sh_title']=$sh_title;
    $Result['sh_description']=$sh_description;
    
    echo json_encode( $Result );