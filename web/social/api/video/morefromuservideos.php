<?php
/*! \file
 * 
 * \brief This api shows the user unloaded videos
 * 
 * 
 * @param vid user video id
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * 
 * @return JSON list with the following keys:
 * @return <pre> 
 * @return       <b>id</b> video id
 * @return       <b>title</b> video title
 * @return       <b>description</b> video description
 * @return       <b>user</b> video user
 * @return       <b>n_views</b> video number of views
 * @return       <b>rating</b> video average rating
 * @return       <b>nb_rating</b> video number of rating
 * @return       <b>nb_comments</b> video number of comments
 * @return       <b>up_vote</b> video number of likes
 * @return       <b>pdate</b> video upload date
 * @return       <b>fulllink</b> video full link   
 * @return       <b>thumblink</b> video thumb link  
 * @return </pre> 
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */
//        session_start();
	$expath = "../";
	//header("content-type: application/xml; charset=utf-8");  
	header('Content-type: application/json');
	require_once($expath."heart.php");

	$res = "please specify a limit and a page";

        $limit_get = $request->query->get('limit', 20);
        $page_get = $request->query->get('page', 0);
        $vid_get = $request->query->get('vid', 0);
//	if (isset($_GET['limit']) && isset($_GET['page']) && isset($_GET['vid']))
	if (isset($limit_get) && isset($page_get) && $vid_get)
	{
//		if (is_numeric($_GET['limit']) && is_numeric($_GET['page']) && is_numeric($_GET['vid']))
		if (is_numeric($limit_get) && is_numeric($page_get) && is_numeric($vid_get))
		{
//			$vinfo = getVideoInfo($_GET['vid']);
			$vinfo = getVideoInfo($vid_get);

//			$datas = GetUserVideosLimit($vinfo['userid'],$_GET['limit'],$_GET['page']);
			$datas = GetUserVideosLimit($vinfo['userid'],$limit_get,$page_get);
			shuffle($datas);
			//$res = "<videos order='fromuser'>";
                        $res = array();
			foreach ( $datas as $data )
			{
                            $userinfo = getUserInfo($data['userid']);
                            $stats = getVideoStats($data['id']);
                            $res[] = array(
                                        'id' => $data['id'],
                                        'title'=> str_replace('"', "'",$data['title']),				
                                        'description' => str_replace('"', "'",$data['description']),
                                        'user' => $userinfo['YourUserName'],
                                        'n_views'=> $data['nb_views'],
                                        'duration' => $data['duration'],
                                        'rating'=> $stats['rating'],				
                                        'nb_rating'=>$stats['nb_ratings'],	
                                        'nb_comments'=>$stats['nb_comments'],
                                        'up_vote' => $data['like_value'],
                                        'pdate' => $data['pdate'],
                                        //$res .= '<rating>'.$data['rating'].'</rating>';				
                                        //$res .= '<comments>'.$data['comments'].'</comments>';
                                        'videolink'=>$data['fullpath'].$data['name'],
                                        'thumblink'=>substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path)),
                                        /////$res .= '&lt;item&gt;http://172.16.124.254:8181/touristtube/'.substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path)).'&lt;&frasl;item&gt;<br>';

                                        //echo $data['id']." ".$data['fullpath'];
                                        //var_dump(getVideoThumbnail($data['id'],$data['fullpath'],0));
				
                                );	
			}
			
			//$res .= "</videos>";
		}
	}
	echo json_encode($res);