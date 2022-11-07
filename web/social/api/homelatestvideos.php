<?php
        header('Content-type: application/json');
	require_once("heart.php");
	$limit = 30;
//	if (isset($_REQUEST['limit']))
//	{
//		$limit  = $_REQUEST['limit'];
$submit_post_get = array_merge($request->query->all(),$request->request->all());
	if (isset($submit_post_get['limit']))
	{
		$limit  = $submit_post_get['limit'];
	}
        
        if (isset($submit_post_get['page']))
	{
		$page  = $submit_post_get['page'];
	}
	
	$datas = getVideos($limit, $page);	
	
        $res = array();
	foreach ( $datas as $data )
	{
            $thumblink = substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path));
            $userinfo = getUserInfo($data['userid']);
            $title = html_entity_decode($data['title'], ENT_QUOTES | ENT_XHTML, 'UTF-8');
            $res[]=array(
                'id'=>$data['id'],
                'title'=>$title,
                'description'=>$data['description'],	
                'user'=>$userinfo['YourUserName'],
                'n_views'=>$data['nb_views'],
                'duration'=>$data['duration'],
                'up_vote'=>$data['like_value'],
                'down_vote'=>$data['down_votes'],
                'rating'=>$data['rating'],
                'videolink'=>$data['fullpath'].$data['name'],
                'thumblink'=> $thumblink ? $thumblink : ""
            );
	
	}
	echo json_encode($res);