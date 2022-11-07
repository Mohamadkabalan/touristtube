<?php

	
	header('Content-type: application/json');
	require_once("heart.php");

	$res = "please specify a limit and a page";
$submit_post_get = array_merge($request->query->all(),$request->request->all());

//	if (isset($_REQUEST['limit']) && isset($_REQUEST['page']))
//	{
//		if (is_numeric($_REQUEST['limit']) && is_numeric($_REQUEST['page']))
//		{
//			$datas = homeVideosMostViewed($_REQUEST['limit'],$_REQUEST['page']);

	if (isset($submit_post_get['limit']) && isset($submit_post_get['page']))
	{
		if (is_numeric($submit_post_get['limit']) && is_numeric($submit_post_get['page']))
		{
			$datas = homeVideosMostViewed($submit_post_get['limit'],$submit_post_get['page']);
			
			//$res = "<videos  order=\"most\">";
                        $res = array();
			foreach ( $datas as $data )
			{
				$thumblink = substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path));
				$userinfo = getUserInfo($data['userid']);
				$res[]=array(
					'id'=>$data['id'],
					'title'=>$data['title'],
					'description'=>$data['description'],

					'user'=>$userinfo['YourUserName'],
					'n_views'=>$data['nb_views'],
					'duration'=>$data['duration'],
					'up_vote'=>$data['like_value'],
					'down_vote'=>$data['down_votes'],
					'rating'=>$data['rating'],
					//$res .= '<comments>'.$data['comments'].'</comments>';
					'videolink'=>$data['fullpath'].$data['name'],
					'thumblink'=> $thumblink ? $thumblink : "",
					/////$res .= '&lt;item&gt;http://172.16.124.254:8181/touristtube/'.substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path)).'&lt;&frasl;item&gt;<br>';
				
					//echo $data['id']." ".$data['fullpath'];
					//var_dump(getVideoThumbnail($data['id'],$data['fullpath'],0));
				
				);		
			}
			
			
		}
	}
	
	echo json_encode($res);