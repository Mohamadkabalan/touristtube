<?php
/*! \file
 * 
 * \brief This api gets the related photos
 * 
 * 
 * @param S session id
 * @param vid photo id
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * 
 * @return JSON list with the following keys:
 * @return <pre> 
 * @return       <b>id</b> photo id
 * @return       <b>title</b> photo title
 * @return       <b>description</b> photo description
 * @return       <b>user</b> photo user
 * @return       <b>n_views</b> photo number of views
 * @return       <b>rating</b> photo average rating
 * @return       <b>nb_rating</b> photo number of rating
 * @return       <b>nb_comments</b> photo number of comments
 * @return       <b>up_vote</b> photo number of likes
 * @return       <b>pdate</b> photo upload date
 * @return       <b>fulllink</b> photo full link   
 * @return       <b>thumblink</b> photo thumb link   
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */

	$expath = "../";
        
	header('Content-type: application/json');
	require_once($expath."heart.php");
        $submit_post_get = array_merge($request->query->all(),$request->request->all());

	$res = array();
	
	$uid = 0;
	if (isset($submit_post_get['S']))
		$uid = $submit_post_get['S'];
	
	$size = 'm';
	$noCache = false;
	$keepRatio = '1';
	
	$user_id = mobileIsLogged($uid);
	$limit_get = $request->query->get('limit','');
	$page_get = $request->query->get('page','');
	$vid_get = $request->query->get('vid','');
        
//	if (isset($_GET['limit']) && isset($_GET['page']) && isset($_GET['vid']))
	if (isset($limit_get) && isset($page_get)  && isset($vid_get)) 
	{
//          $datas = getRelatedVideos($_GET['page'],$_GET['limit'],$_GET['vid']);
            $datas = getRelatedVideos($page_get,$limit_get,$vid_get,$user_id);
            //shuffle($datas);
            //$res = "<photos order='related'>";
            $res = array();
//                        echo $datas['media'][0]['pdate'];exit();
            foreach ( $datas['media'] as $data )
            {
                $userinfo = getUserInfo($data['userid']);
                $stats = getVideoStats($data['id']);
                $thumbLink = $data['fullpath'] . $data['name'];
                $allLikesnum = socialLikesGet(array(
                    'entity_id' => $data['id'],
                    'entity_type' => SOCIAL_ENTITY_MEDIA,
                    'like_value' => 1,
                    'n_results' => true
                ));
                $res_item = array(
                            'id'=>$data['id'],
                            'title'=> str_replace('"', "'",$data['title']),
                            'description'=> str_replace('"', "'",$data['description']),
                            'user'=> $userinfo['YourUserName'],
                            'n_views'=>$data['nb_views'],
                            'rating'=>$stats['rating'],				
                            'nb_rating'=>$stats['nb_ratings'],
                            'nb_comments'=>$stats['nb_comments'],
                            'up_vote' => intval($allLikesnum),
                            //'pdate' => str_replace('Z',' ',str_replace('T',' ',$data['pdate'])),
                            'pdate' => returnSocialTimeFormat($data['pdate']),
                            'type' => $data['type'],
                            //$res .= '<rating>'.$data['rating'].'</rating>';				
                            //$res .= '<comments>'.$data['comments'].'</comments>';
                            //$res .= '<thumblink>'.''.substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path)).'</thumblink>';
                            /////$res .= '&lt;item&gt;http://172.16.124.254:8181/touristtube/'.substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path)).'&lt;&frasl;item&gt;<br>';

                            //echo $data['id']." ".$data['fullpath'];
                            //var_dump(getVideoThumbnail($data['id'],$data['fullpath'],0));
                        );
                if($data['type'] == 'i'){
                    $res_item['fulllink'] = $thumbLink;
                    $res_item['thumblink'] = resizepic($thumbLink, $size, $noCache, $keepRatio);
                }
                elseif($data['type'] == 'v'){
                    $res_item['videolink'] = $data['fullpath'].$data['name'];
                    $res_item['thumblink'] = substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path));
                    $res_item['duration'] = $data['duration'];
                }
                $res[] = $res_item;
            }
			
			//$res .= "</photos>";
	}
	echo json_encode($res);