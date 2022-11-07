<?php
/*! \file
 * 
 * \brief This api gets the related videos
 * 
 * 
 * @param S session id
 * @param vid video id
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * 
 * @return JSON list with the following keys:
 * @return <pre> 
 * @return       <b>order</b> always equal to zero
 * @return       <b>id</b> video id
 * @return       <b>title</b> video title
 * @return       <b>description</b> video description
 * @return       <b>user</b> video user
 * @return       <b>n_views</b> video number of views
 * @return       <b>duration</b> video duration
 * @return       <b>rating</b> video average rating
 * @return       <b>nb_rating</b> video number of rating
 * @return       <b>nb_comments</b> video number of comments
 * @return       <b>up_vote</b> video number of likes
 * @return       <b>pdate</b> video upload date
 * @return       <b>videolink</b> video link   
 * @return       <b>thumblink</b> video thumb link   
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */

	$expath = "../";
	//header("content-type: application/xml; charset=utf-8");  
	header('Content-type: application/json');
	require_once($expath."heart.php");
        $submit_post_get = array_merge($request->query->all(),$request->request->all());

	$res = array();
//        mobileIsLogged($_REQUEST['S']);
        $user_id = mobileIsLogged($submit_post_get['S']);
        
        $limit_get = $request->query->get('limit','');
        $page_get = $request->query->get('page','');
        $vid_get = $request->query->get('vid','');
//	if (isset($_GET['limit']) && isset($_GET['page']) && isset($_GET['vid']))
	if (isset($limit_get) && isset($page_get) && isset($vid_get))
	{
//          $datas = getRelatedVideos($_GET['page'],$_GET['limit'],$_GET['vid']);
            $datas = getRelatedVideos($page_get,$limit_get,$vid_get,$user_id);
            //shuffle($datas);
            //$res = "<videos order='related'>";
            $res = array();
            foreach ( $datas['media'] as $data )
            {
                $userinfo = getUserInfo($data['userid']);
                $stats = getVideoStats($data['id']);
                $allLikesnum = socialLikesGet(array(
                    'entity_id' => $data['id'],
                    'entity_type' => SOCIAL_ENTITY_MEDIA,
                    'like_value' => 1,
                    'n_results' => true
                ));
                $res[] = array(
                                'order'=>"poi",
                                'id'=> $data['id'],
                                'title'=> $data['title'],				
                                'description'=> str_replace('"', "'",$data['description']),
                                'user'=> $userinfo['YourUserName'],
                                'n_views' => $data['nb_views'],
                                'duration' => $data['duration'],
                                'rating'=> $stats['rating'],				
                                'nb_rating' => $stats['nb_ratings'],	
                                'nb_comments' =>$stats['nb_comments'],
                                'up_vote' => intval($allLikesnum),
                                //'pdate' => str_replace('Z',' ',str_replace('T',' ',$data['pdate'])),
                                'pdate' => returnSocialTimeFormat($data['pdate']),
                                //$res .= '<rating>'.$data['rating'].'</rating>';				
                                //$res .= '<comments>'.$data['comments'].'</comments>';
                                'videolink' => $data['fullpath'].$data['name'],
                                'thumblink' => substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path))
                                //$res .= '&lt;item&gt;http://172.16.124.254:8181/touristtube/'.substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path)).'&lt;&frasl;item&gt;<br>';

                                //echo $data['id']." ".$data['fullpath'];
                                //var_dump(getVideoThumbnail($data['id'],$data['fullpath'],0));

               );
            }
	}
	echo json_encode($res);