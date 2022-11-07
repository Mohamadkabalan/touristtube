<?php 
/*! \file
 * 
 * \brief This api returns friend request list
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
 * @return       <b>request_msg</b> friend request message
 * @return       <b>fullname</b> friend full name
 * @return       <b>username</b> user name
 * @return       <b>chatprofilepic</b> user chat profile picture path
 * @return       <b>chatprofilepic</b> friend chat profile picture path
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */
//	session_id($_REQUEST['S']);
//	session_start();
        
        $expath = "../";
        include($expath . "heart.php");
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
        
	include($path."services/lib/chat.inc.php");
	
//	$userID = $_SESSION['id'];
//        $userID = mobileIsLogged($_REQUEST['S']);
        $userID = mobileIsLogged($submit_post_get['S']);
   
        if( !$userID ) die();
	$str = null;
//	if (isset($_REQUEST['limit']))
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
//	if(isset($_REQUEST['str']))
//	{
//		$str = $_REQUEST['str'];
	if(isset($submit_post_get['str']))
	{
		$str = $submit_post_get['str'];
	}
	
	$default_opts = array(
		'limit' => $limit,
		'page' => $page,
		'type' => array(0),
		'userid' => $userID,
		'orderby' => 'request_ts',
		'order' => 'd',
		'search_string' => $str
	);
	
	$datas = userFriendSearch($default_opts);

	
	//$xml_output = "<friendlist order='friendrequestlist'>";
        $xml_output = array();
	foreach($datas as $data)
	{  
            $userInfo = getUserInfo( $data['id'] );
            if ($userInfo['profile_Pic'] != "")
            {
                $chatprofilepic="/media/tubers/crop_".substr($userInfo['profile_Pic'],0,strlen($userInfo['profile_Pic'])-3)."png";
            }else
            {
                $chatprofilepic="media/tubers/na.png";
            }
			/*code changed by sushma mishra on 30-sep-2015 to get fullname using returnUserDisplayName function starts from here*/
            $xml_output[]= array(
                            'id'=>$data['id'],
                            'request_msg'=>str_replace('"', "'",$data['request_msg']),							
                            //'fullname'=>str_replace('"', "'",$data['FullName']),
                            'fullname'=>str_replace('"', "'",returnUserDisplayName($userInfo)),							
                            'username'=>$data['YourUserName'],
                            'chatprofilepic'=>$chatprofilepic,
                            'prof_pic'=>"media/tubers/" .$data['profile_Pic'],
                        );    
			/*code changed by sushma mishra on 30-sep-2015 ends here*/
	}
	header('Content-type: application/json');	
        echo json_encode($xml_output);