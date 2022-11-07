<?php
/*! \file
 * 
 * \brief This api returns friend list
 * 
 * 
 * @param S session id
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
 * @return       <b>prof_pic</b> friend profile picture path
 * @return       <b>gender</b> friend gender
 * @return </pre>
 *
 *  */
//	session_id($_REQUEST['S']);
//	session_start();
//        ini_set('display_errors', 1);
	$expath = "../";
	include($expath."heart.php");
//	include($path."services/lib/chat.inc.php");
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
	
//	$userID = $_SESSION['id'];
//        $userID = mobileIsLogged($_REQUEST['S']);
//        if( !$userID ) die();
//        if (isset($_REQUEST['limit']))
//            $limit = intval($_REQUEST['limit']);
        $userID = mobileIsLogged($submit_post_get['S']);
        if( !$userID ) die();
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
	if($userID == '')  $xml_output = "No session";
        else{

//            $datas = userGetFreindList($userID);
            
            $options = array(
                'limit' => $limit,
                'page' => $page,
                'type' => array(1),
                'is_visible' => -1,
                'userid' => $userID,
                'orderby' => 'U.FullName',
                'order' => 'a'
            ); //debug($options);
            if(isset($submit_post_get['str']) && !empty($submit_post_get['str'])){
                $options['search_string'] = $submit_post_get['str'];
            }
            $datas = userFriendSearch($options);

            //var_dump($datas);

            //$xml_output = "<friendlist order='friendlist'>";
           
            
            $xml_output=array();
            foreach($datas as $data)
            {
				$userInfo = getUserInfo( $data['id'] );
                if (userIsProfileBlocked($userID, $data['id']))
                {	
                    continue;
                }
                
                
//                if(userIsFriend($userID,$data['id']) || userFreindRequestMade($userID,$data['id']))
//                {
//                    $isfriend='YES';
//                }else{
//                    $isfriend='NO';
//                }
                $isfriend = friendRequestOccur($userID, $data['id']);
                
                if(userSubscribed($userID,$data['id']))
                {
                    $isfollowed='YES';
                }else{
                    $isfollowed='NO';
                }

                if ($userInfo['profile_Pic'] != "")
                {
                    $chatprofilepic= "/media/tubers/crop_".substr($userInfo['profile_Pic'],0,strlen($userInfo['profile_Pic'])-3)."png";
                }else {
                    $chatprofilepic="media/tubers/na.png";
                }

                $xml_output[] = array(
                                'id'=>$data['id'],
                                /*code changed by sushma mishra on 30-sep-2015 to get fullname using returnUserDisplayName function starts from here*/
                                //'fullname'=>str_replace('"', "'",$data['FullName']),
                                'fullname'=>str_replace('"', "'",returnUserDisplayName($userInfo)),
                                /*code changed by sushma mishra on 30-sep-2015 ends here*/
                                'username'=>str_replace('"', "'",$data['YourUserName']),
                                'isfriend'=>$isfriend,
                                'isfollowed'=>$isfollowed,
//                                'status'=>chatGetUserStatus($data['id']),
                                'chatprofilepic'=>$chatprofilepic,
                                'prof_pic'=>"media/tubers/" .$data['profile_Pic'],
                                'gender'=>$data['gender']
                            );
            }
            //$xml_output .= "</friendlist>";
       }
	//header("content-type: application/xml; charset=utf-8");  
        header('Content-type: application/json');
        echo json_encode($xml_output);
	