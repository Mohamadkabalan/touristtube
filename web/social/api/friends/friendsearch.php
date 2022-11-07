<?php
/*! \file
 * 
 * \brief This api returns friend search
 * 
 * 
 * @param S session id
 * @param order order asc or desc
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * @param str search string
 * 
 * @return JSON list with the following keys:
 * @return <pre> 
 * @return       <b>id</b> friend id
 * @return       <b>fullname</b> friend full name
 * @return       <b>username</b> user name
 * @return       <b>isfriend</b> user is friend [1-> not a friend(you can send a request), 2-> already friend, 3-> request sent, 4-> request received]
 * @return       <b>isfollowed</b> user is followed
 * @return       <b>status</b> user chat status
 * @return       <b>chatprofilepic</b> user chat profile picture path
 * @return       <b>n_friends</b> friend number of friends
 * @return       <b>n_following</b> friend number of following
 * @return       <b>prof_pic</b> friend profile picture path
 * @return       <b>gender</b> friend gender
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */
//	session_id($_REQUEST['S']); 
//        session_start();
        $expath = "../";
	include($expath."heart.php");
	header('Content-type: application/json');
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//        if(empty($_REQUEST['str'])){
        if(empty($submit_post_get['str'])){
            echo json_encode(array());
            exit();
        }
//	$userID = $_SESSION['id'];
//        $userID = mobileIsLogged($_REQUEST['S']);
        $userID = mobileIsLogged($submit_post_get['S']);
        if( !$userID ) { $userID = 0; }
//        $order  =   isset($_REQUEST['order'])? $_REQUEST['order']:'0';
        $order  =   isset($submit_post_get['order'])? $submit_post_get['order']:'0';
        if($order == 1){ 
            $order = 'a';
        }
        else{
            $order = 'd';
        }
//        if (isset($_REQUEST['limit']))
//            $limit = intval($_REQUEST['limit']);
        if (isset($submit_post_get['limit']))
            $limit = intval($submit_post_get['limit']);
        else
            $limit = 10;
//        if (isset($_REQUEST['page']))
//            $page = intval($_REQUEST['page']);
        if (isset($submit_post_get['page']))
            $page = intval($submit_post_get['page']);
        else
            $page = 0;
	$default_opts = array(
		'limit' => $limit,
		'page' => $page,
		'public' => 1,
		'orderby' => 'id',
		//'order' => 'a',
                'order' => $order,
//		'search_string' => $_REQUEST['str'],
		'search_string' => $submit_post_get['str'],
		'search_strict' => 0,
		'search_where' => 'a'
	);
	
	$datas = userSearch($default_opts);
	//echo "<pre>";print_r($datas);
	
	//$xml_output = "<friendlist order='userSearch'>";
        $xml_output = array();
        
	foreach($datas as $data)
	{
		if($userID != $data['id'])
		{
			
                    $userInfo = getUserInfo( $data['id'] );
                    /*code changed by sushma mishra on 30-sep-2015 to get fullname using returnUserDisplayName function starts from here*/
                        /*if($data['FullName'] != ''){
                            $fullname =$data['FullName'];
                        }else{
                            $fullname=$data['YourUserName'];
                        }*/
                        $fullname=returnUserDisplayName($userInfo);
                        /*code changed by sushma mishra on 30-sep-2015 ends here*/
                        
//                      if(userIsFriend($userID,$data['id']) || userFreindRequestMade($userID,$data['id']))
//			{
//				$isfriend='YES';
//			}else
//			{
//				$isfriend='NO';
//			}
                        
                        //(1-> not a friend, 2-> yes a friend, 3-> request sent, 4-> request received)
                        $isfriend = friendRequestOccur($userID, $data['id']);
                        
                        if(userSubscribed($userID,$data['id']))
			{
				$isfollowed='YES';
			}else
			{
				$isfollowed='NO';							
			}
                        
            if ($userInfo['profile_Pic'] != "")
			{
				$chatprofilepic="/media/tubers/crop_".substr($userInfo['profile_Pic'],0,strlen($userInfo['profile_Pic'])-3)."png";
			}else
			{
				$chatprofilepic= "media/tubers/na.png";
			}
                        
                        $statis = userGetStatistics($userInfo['id'],1);
                        $location = userGetLocation($userInfo);
                        if(!$location){
                            $location = '';
                        }
			$xml_output[]=array(
                                'id'=>$data['id'],
                                'fullname'=>$fullname,
                                'username'=>$data['YourUserName'],
                                'isfriend'=>$isfriend,
                                'isfollowed'=>$isfollowed,
                                'chatprofilepic'=>$chatprofilepic,
                                'n_friends'=>$statis['nFriends'],
                                'n_following'=>$statis['nFollowings'],
                                'prof_pic'=>"media/tubers/" .$data['profile_Pic'],
                                'gender'=>$data['gender'],
                                'location'=>$location
			);
		}
		
	}
		
        echo json_encode($xml_output);