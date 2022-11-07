<?php 
/*! \file
 * 
 * \brief This api returns a video's comments
 * 
 * 
 * @param vid user video id
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * 
 * @return JSON list with the following keys:
 * @return <pre> 
 * @return       <b>id</b> comment id
 * @return       <b>user</b> video user name
 * @return       <b>userprofilepic</b> user profile picture path
 * @return       <b>video_id</b> video id   
 * @return       <b>text</b> comment text   
 * @return       <b>date</b>  comment date 
 * @return       <b>likevalue</b> comment likes  
 * @return       <b>upvotes</b> not used
 * @return       <b>downvotes</b> not used
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */
	$expath = "../";
	header('Content-type: application/json');
	include("../heart.php");
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
	/*echo '<?xml version="1.0" encoding="utf-8"?>';
	
	/**
	 * returns a video's comments
	 * @param integer $vid the video ID
	 * @param integer $nlimit the maximum  umber of comment record to return
	 * @param integer $page number of pages of comment records to skip
	 * @param string $sortby column name to sort by
	 * @param string $sort ascending or descending sort 'ASC' | 'DESC'
	 * @return mixed array of the video's comment records or false if none found
	 */
//	if (isset($_REQUEST['vid']))
//	{
//		$vid = $_REQUEST['vid'];
//	}
//	 if (isset($_REQUEST['page']))
//	 {
//		 $page = $_REQUEST['page'];
//	 }
//	  if (isset($_REQUEST['limit']))
//	 {
//		 $limit = $_REQUEST['limit'];
//	 }
        if(isset($submit_post_get['timezone'])){
            global $mobile_timezone;
            $mobile_timezone = $submit_post_get['timezone'];
        }
	if (isset($submit_post_get['vid']))
	{
		$vid = $submit_post_get['vid'];
	}
	 if (isset($submit_post_get['page']))
	 {
		 $page = $submit_post_get['page'];
	 }
	  if (isset($submit_post_get['limit']))
	 {
		 $limit = $submit_post_get['limit'];
	 }

	if(is_numeric($page) && is_numeric ($limit) && is_numeric($vid))
	{	
		//$res = "<comments>";
                $res = array();
		$datas = videoGetComments($vid, $limit, $page);		
		foreach ( $datas as $data )
		{
                    $t_date = $data['comment_date'];
                    $commentsDate = returnSocialTimeFormat($t_date,1);//to use
                    $userinfo = getUserInfo($data['user_id']);
                    if ($userinfo['profile_Pic'] != ""){
                        $userprofilepic = "media/tubers/".$userinfo['profile_Pic'];
                    }else {
                        $userprofilepic = "media/tubers/na.png";
                    }
					/*code changed by sushma mishra on 30-sep-2015 to get fullname using returnUserDisplayName function starts from here*/
                    $res[] = array(
                            'id'=>$data['id'],
                            'user'=>$userinfo['YourUserName'],							
                           //'fullname'=>htmlEntityDecode($userinfo['FullName']),
                            'fullname'=>returnUserDisplayName($userinfo),
                            'user_id'=>$userinfo['id'],
                            'userprofilepic'=>$userprofilepic,
                            'video_id'=> $data['video_id'],
                            'text'=> $data['comment_text'],
                            'date'=> $commentsDate,
                            'likevalue'=> $data['like_value'],
                            'upvotes'=> $data['down_votes'],
                            'downvotes'=> $data['like_value']
                    );
					/*code changed by sushma mishra on 30-sep-2015 ends here*/
                        
		}
		
		echo json_encode($res);
	}