<?php
    if( !isset($bootOptions) ){	
        $path = "../";
        $bootOptions = array("loadDb" => 1 , 'requireLogin' => false);
        include_once ( $path . "inc/common.php" );
        include_once ( $path . "inc/bootstrap.php" );

        include_once ( $path . "inc/functions/videos.php" );
        include_once ( $path . "inc/functions/webcams.php" );
        include_once ( $path . "inc/functions/users.php" );
    }
    $type = $request->query->get('type','m');
    if( !in_array($type,array('m','w')) ) {
    header("location:".ReturnLink(''));
}	
    
	
    //comment type. either media or webcam
    if($type == 'm') include_once ( $path . "inc/functions/comment_media.php" );
    else if($type == 'w') include_once ( $path . "inc/functions/comment_webcam.php" );
    
    $user_id = userGetID();
//    if( isset($_POST['current_id']) ) $current_id = intval($_POST['current_id']);
//    if( isset($_POST['entity_type']) ) $entity_type = intval($_POST['entity_type']);
    if( !isset($current_id) ) $current_id = intval($request->request->get('current_id', 0));
    if( !isset($entity_type) ) $entity_type = intval($request->request->get('entity_type', 0));
    
//    $sortby = isset($_POST['sortby']) ? db_sanitize($_POST['sortby']) : 'comment_date';
//    $sort = isset($_POST['sort']) ? db_sanitize($_POST['sort']) : 'DESC';
//    $page = isset($_POST['page']) ? db_sanitize($_POST['page']) : 0;
    $sortby = $request->request->get('sortby', 'comment_date');
    $sort = $request->request->get('sort', 'DESC');
    $page = intval($request->request->get('page', 0));
    
    if($type == 'm'){
        $vinfo = getVideoInfo($vid);
        $owner_id = $vinfo['userid'];
        $comments = socialMediaCommentsSpecialGet(array('media_id' => $current_id, 'limit' => 5 ,'page' => $page ,'order' => strtolower($sort[0]) ,'orderby' => $sortby, 'entity_type'=>$entity_type ));// videoGetComments($vid, $nlimit, $page, $sortby, $sort );
    }else if($type == 'w'){
        $owner_id = -1;
        //$comments = webcamGetComments($current_id, 5, $page, $sortby, $sort );
        $comments = socialMediaCommentsSpecialGet(array('media_id' => $current_id, 'limit' => 5 ,'page' => 0 ,'order' => 'DESC' ,'orderby' => 'id', 'entity_type'=>$entity_type ));
    }

    $commentGlobArr = array();
    foreach($comments as $comment): 
        $aCommentGlobArr = array();
        $comment_ch = intval($comment['channel_id']);
        if($comment_ch>0){
            $comment_ch_array=channelGetInfo($comment_ch);
            if($comment_ch_array['owner_id']==$comment['user_id']){
                $how_cmt = htmlEntityDecode($comment_ch_array['channel_name']);
                if(strlen($how_cmt) > 70){
                    $how_cmt = substr($how_cmt,0,67).' ...';
                 }
            }else{
                $how_cmt = returnUserDisplayName($comment,array('max_length' => 70));
            }
        }else{
            $how_cmt = returnUserDisplayName($comment,array('max_length' => 70));
        }
        $uscomment = array('id'=>$comment['user_id'],'YourUserName'=>$comment['YourUserName']);
        
        $cuser_link = userProfileLink($uscomment);        
        $aCommentGlobArr['howcmt'] = $how_cmt;
        $aCommentGlobArr['cuser_link'] = $cuser_link;
        $comment_date = returnSocialTimeFormat($comment['comment_date'],3);
        $aCommentGlobArr['date'] = $comment_date;
        $aCommentGlobArr['text'] = str_replace("\n","<br/>",htmlEntityDecode($comment['comment_text']) );
        $commentGlobArr[] = $aCommentGlobArr;
		
endforeach;
$data['commentGlobArr'] = $commentGlobArr;