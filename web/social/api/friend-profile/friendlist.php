<?php
/*! \file
 * 
 * \brief This api to see friends list of my friend
 * 
 * 
 * @param S  session id
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * 
 * @return <b>xml_output</b> List of friend information (array):
 * @return <pre> 
 * @return         <b>id</b> friend id
 * @return         <b>fullname</b> friend fullname
 * @return         <b>username</b> friend username
 * @return         <b>isfriend</b> friend is friend  yes or no
 * @return         <b>isfollowed</b> friend is followed  yes or no
 * @return         <b>chatprofilepic</b> my friend profile picture path
 * @return         <b>prof_pic</b> friend profile picture path
 * @return         <b>gender</b> friend gender
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */

//	session_id($_REQUEST['S']);
//	session_start();
        
	$expath = "../";
	header('Content-type: application/json');
	include($expath."heart.php");
	include($path."services/lib/chat.inc.php");
        $xml_output = array();
        
	$submit_post_get = array_merge($request->query->all(),$request->request->all());
//	$userID = $_REQUEST['uid'];
	$userID = $submit_post_get['uid'];
//	$myID = $_SESSION['id'];
//        $myID = mobileIsLogged($_REQUEST['S']);
        $myID = mobileIsLogged($submit_post_get['S']);
        if( !$myID ) die();
        $user_privacy = checkUserPrivacyExtandNetwork($userID, $myID, SOCIAL_ENTITY_PROFILE_FRIENDS);
        if(!$user_privacy){
            echo json_encode($xml_output);
            exit;
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
        
        $options = array(
            'limit' => $limit,
            'page' => $page,
            'type' => array(1),
            'is_visible' => -1,
            'userid' => $userID,
            'orderby' => 'U.FullName',
            'order' => 'a'
        );
        
//        if(isset($_REQUEST['str']) && !empty($_REQUEST['str'])){
//            $options['search_string'] = $_REQUEST['str'];
        if(isset($submit_post_get['str']) && !empty($submit_post_get['str'])){
            $options['search_string'] = $submit_post_get['str'];
        }
        $datas = userFriendSearch($options);
        
	
	//var_dump($datas);
	
	//$xml_output = "<friendlist order='friendfriendlist'>";
        
	foreach($datas as $data)
	{
            $userInfo = getUserInfo( $data['id'] );
            
            if(userSubscribed($myID,$userID)){
            $is_followed = 'yes';
            }else{
                $is_followed = 'no';
            }
            
//            if($myID != $data['id'])
//            {
//                if(userIsFriend($myID,$data['id']) || userFreindRequestMade($myID,$data['id'])){
//                    $isfriend = 'YES';
//                }else{
//                    $isfriend = 'NO';
//                }
            $isfriend = friendRequestOccur($myID, $data['id']);
            if(userSubscribed($myID,$data['id']))
            {
                $isfollowed='YES';
            }else{
                $isfollowed='NO';
            }

            if ($userInfo['profile_Pic'] != ""){
                $chatprofilepic = "/media/tubers/crop_".substr($userInfo['profile_Pic'],0,strlen($userInfo['profile_Pic'])-3)."png";
            }else{
                $chatprofilepic = "media/tubers/na.png";
            }
                            /*code changed by sushma mishra on 30-sep-2015 to get fullname using returnUserDisplayName function starts from here*/
                            $userDetail = getUserInfo( $data['id'] );

            $xml_output[]= array(
                'id'=>$data['id'],
                                                'fullname'=>returnUserDisplayName($userDetail),
                //'fullname'=>$data['FullName'],
                'username'=>$data['YourUserName'],
                'isfriend'=>$isfriend,
                'isfollowed'=>$isfollowed,
                'chatprofilepic'=>$chatprofilepic,
                'prof_pic'=>"media/tubers/" .$data['profile_Pic'],
                'gender'=>$data['gender']
            );   
				/*code changed by sushma mishra on 30-sep-2015 ends here*/
//            }
	}
		
        echo json_encode($xml_output);
