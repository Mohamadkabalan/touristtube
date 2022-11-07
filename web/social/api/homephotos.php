<?php
	//header("content-type: application/xml; charset=utf-8");  
        header('Content-type: application/json');
	require_once("heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
	

//	if (isset($_REQUEST['limit']) && isset($_REQUEST['page']))
//	{
//		if (is_numeric($_REQUEST['limit']) && is_numeric($_REQUEST['page']))
//		{
//			$datas = getPhotosMostView($_REQUEST['limit'],$_REQUEST['page']);
	if (isset($submit_post_get['limit']) && isset($submit_post_get['page']))
	{
		if (is_numeric($submit_post_get['limit']) && is_numeric($submit_post_get['page']))
		{
			$datas = getPhotosMostView($submit_post_get['limit'],$submit_post_get['page']);
			//$res = "<photos order='home'>";
                        $res = array();
			foreach ( $datas as $data )
			{
                            $userinfo = getUserInfo($data['userid']);	
                            $res[]=array(

                                    'id'=>$data['id'],
                                    'title'=>$data['title'],
                                    'description'=>$data['description'],

                                    'user'=>$userinfo['YourUserName'],
                                    'n_views'=>$data['nb_views'],
                                    'up_vote'=>$data['like_value'],
                                    'down_vote'=>$data['down_votes'],
                                    'rating'=>$stats['rating'],			
                                    'nb_rating'=>$stats['nb_ratings'],
                                    'nb_comments'=>$stats['nb_comments'],

                                    'fulllink'=>$data['fullpath'].$data['name'],
                                    'thumblink'=>substr(getImageThumbnail(getVideoInfo($data['id']),$path.$data['fullpath']),strlen($path))
				);	
			}
			
			
			echo json_encode($res);
			
		}
	}