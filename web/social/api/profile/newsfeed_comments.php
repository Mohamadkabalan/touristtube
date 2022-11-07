<?php
/*! \file
 * 
 * \brief This api returns comment 
 * 
 * 
 * @param S session id
 * @param entity_id entity id
 * @param entity_type entity type
 * @param pagename page name
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * @param globchannelid  channel id
 * 
 * @return <b>res</b> JSON list with the following keys:
 * @return <pre> 
 * @return       <b>owner_id</b> owner id
 * @return       <b>owner_name</b>  owner name
 * @return       <b>profile_pic</b>  owner profile picture path
 * @return       <b>date</b>  comment date added
 * @return       <b>comment</b>  comment added
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */
    $expath = "../";
    header('Content-type: application/json');
    include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//    $user_id = mobileIsLogged($_REQUEST['S']);
//    
//    $media = intval($_REQUEST['entity_type']);
//    $mediaid = intval($_REQUEST['entity_id']);
//    $pagename = xss_sanitize($_REQUEST['pagename']);
//    $page = intval($_REQUEST['page']);
//    $limit = intval($_REQUEST['limit']);
//    $channelid = ( isset( $_REQUEST['globchannelid'] ) )? intval($_REQUEST['globchannelid']) : 0;
    $user_id = mobileIsLogged($submit_post_get['S']);
    
    $media = intval($submit_post_get['entity_type']);
    $mediaid = intval($submit_post_get['entity_id']);
    $pagename = $submit_post_get['pagename'];
    $page = intval($submit_post_get['page']);
    $limit = intval($submit_post_get['limit']);
    if(isset($submit_post_get['timezone'])){
        global $mobile_timezone;
        $mobile_timezone = $submit_post_get['timezone'];
    }
    $channelid = ( isset( $submit_post_get['globchannelid'] ) )? intval($submit_post_get['globchannelid']) : 0;

//    $userIschannel=userIsChannel();
//    $userIschannel = ($userIschannel) ? 1 : 0;
    $userIschannel = 0;
//    $current_channel=userCurrentChannelGet();
    $current_channel = false;
    $user_is_logged=0;    
    if($user_id){
        $user_is_logged=1;
        $entity_owner_id = socialEntityOwner($media,$mediaid);
        if( $user_id==$entity_owner_id ){		
            $is_owner=1;
        }else{
            $is_owner=0;
        }
    }else{
        $is_owner=0;	
    }
    if( $current_channel && sizeof($current_channel) != 0 && $is_owner==0){
        $userIschannel = 1;
    }
    if($channelid >0){
            $channelInfo=channelGetInfo($channelid);
    }else{
        $entityInfo=socialEntityInfo($media,$mediaid );
        if( isset( $entityInfo['channelid'] ) && intval($entityInfo['channelid']) >0 ){
                $channelid = $entityInfo['channelid'];
                $channelInfo=channelGetInfo($channelid);
        }else if( isset( $entityInfo['channel_id'] ) && intval($entityInfo['channel_id']) >0 ){
                $channelid = $entityInfo['channel_id'];
                $channelInfo=channelGetInfo($channelid);
        }else if( isset( $entityInfo['owner_id'] ) && intval($entityInfo['owner_id']) >0 ){
                $channelid = $entityInfo['id'];
                $channelInfo=channelGetInfo($channelid);
        }
    }

    $connectedTubers = getConnectedtubers($channelid);

    $logo_src = (isset( $channelInfo ) && $channelInfo['logo']) ? photoReturnchannelLogo($channelInfo) : ReturnLink('/media/tubers/tuber.jpg');

    
    $entity_owner = socialEntityOwner( $media , $mediaid );
    $is_entity_owner =0;
    $is_visible=-1;
    if($user_id == $entity_owner){                    
        $is_entity_owner =1;
    }else{        		
        $is_visible=1;
    }
    
    $is_entity_ownerSub =1;
    if($channelid >0){
        $entity_info_val = socialEntityInfo( $media , $mediaid );
        if( intval($entity_info_val['channelid']) >0 ){
            $channel_entity_id = $entity_info_val['channelid'];
        }else if( intval($entity_info_val['channel_id']) >0 ){
            $channel_entity_id = $entity_info_val['channel_id'];
        }else if( intval($entity_info_val['owner_id']) >0 ){
            $channel_entity_id = $entity_info_val['id'];
        }
        if( $channel_entity_id != $channelid ){
            $is_entity_ownerSub =0;
        }
    }
    
    $is_owner=1;
    $comment_published=1;
    $channelInfo = ( isset( $channelInfo ) ) ? $channelInfo : $channelInfo['owner_id']=-1;
    if($user_id!=intval($channelInfo['owner_id'])){
        $is_owner=0;
    }
    $user_is_logged=0;
    if($user_id){
            $user_is_logged=1;	
    }else{
            $is_visible=1;
            $is_owner=0;	
    }
    if($channelid==0 && $user_is_logged==1){
        $entity_info = socialEntityInfo($media,$mediaid);
        if( $user_id==intval($entity_info['user_id']) || $user_id==intval($entity_info['userid']) ){		
            $is_owner=1;
            $comment_published=1;
        }else{
            $is_owner=0;
        }
    }

    $Result = array();
	
//    $options1 = array(
//        'entity_id' => $mediaid,
//        'entity_type' => $media,
//        'published' => $comment_published,
//        'is_visible' => $is_visible,
//        'n_results' => true
//    );
//					
//    $allCommentsnum = socialCommentsGet($options1);

    $options = array(
        'limit' => 5,
        'page' => $page,
        'orderby' => 'comment_date',
        'order' => 'd',
        'entity_id' => $mediaid,
        'published' => $comment_published,
        'is_visible' => $is_visible,
        'entity_type' => $media
    );
	
    $allComments = socialCommentsGet($options);
    $channel_name = htmlEntityDecode($channelInfo['channel_name']);
    $res = array();
    foreach($allComments as $comment){			
        $t_date = $comment['comment_date'];
        $commentsDate = returnSocialTimeFormat($t_date,1);//to use
        //$commentsDate = formatDateAsString($t_date);
        $is_visible=$comment['is_visible'];


        $comment_owner = $comment['user_id'];
        $channel_comment_id = intval($comment['channel_id']);
        if($entity_owner==$comment_owner && $channel_comment_id>0){ 
             $link_class='';
             $channelInfo_comment=channelGetInfo($channel_comment_id);
             $channel_name_comment = htmlEntityDecode($channelInfo_comment['channel_name']);
            if(strlen($channel_name_comment) > 25){
                $channel_name_comment = substr($channel_name_comment,0,25).' ...';
            }
            $comment_name=$channel_name_comment;
            $pic_diplay = $channelInfo_comment['logo'] ? 'media/channel/' . $channelInfo_comment['id'] . '/thumb/' . $channelInfo_comment['logo'] : '/media/tubers/tuber.jpg';
//                    $pic_diplay = ($channelInfo['logo']) ? photoReturnchannelLogo($channelInfo_comment) : ReturnLink('/media/tubers/tuber.jpg');
            $owner_id = $channel_comment_id;
            $owner_type = 'channel';
        }else{
            $comment_name = returnUserDisplayName($comment);
            $pic_diplay = 'media/tubers/'.$comment['profile_Pic'] ;
//                    $pic_diplay = ReturnLink('media/tubers/small_'.$comment['profile_Pic'] );
            $owner_id = $comment_owner;
            $owner_type = 'user';
        }
        $overhead_text= strip_tags($comment['comment_text']);
        $likes_options = array(
            'entity_id' => $comment['id'],
            'entity_type' => SOCIAL_ENTITY_COMMENT,
            'like_value' => 1,
            'n_results' => true
        );
        $likes = socialLikesGet($likes_options);
        $isLiked = socialLiked($user_id, $comment['id'], SOCIAL_ENTITY_COMMENT);
        $res[] = array(
            'id' => $comment['id'],
            'owner_id' => $owner_id,
            'owner_name' => $comment_name,
            'owner_type' => $owner_type,
            'profile_pic' => $pic_diplay,
            'date' => $commentsDate,
            'comment' => $overhead_text,
            'likes' => $likes,
            'is_liked' => $isLiked ? "$isLiked" : "0"
        );
    }

    echo json_encode( $res );
