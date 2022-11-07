<?php
/*! \file
 * 
 * \brief This api shows the blocked list of a user
 * 
 * 
 * @param S  session id
 * @param str  search string
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * 
 * @return <b>xml_output</b> List of blocked user information (array):   
 * @return <pre> 
 * @return       <b>id</b> the id of the user that were blocked
 * @return       <b>fullname</b> the full name of the user that were blocked
 * @return       <b>username</b> the user name of the user that were blocked
 * @return       <b>chatprofilepic</b> user profile picture path
 * @return       <b>prof_pic</b> the profile picture path of the user that were blocked
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */

//	session_id($_REQUEST['S']);
//	session_start();

        
	$expath = "../";
	
	include($expath."heart.php");
	include($path."services/lib/chat.inc.php");
	$submit_post_get = array_merge($request->query->all(),$request->request->all());
//	$userID = $_SESSION['id'];
//        $userID = mobileIsLogged($_REQUEST['S']);
        $userID = mobileIsLogged($submit_post_get['S']);
        if( !$userID ) die();
	$str = null;
//	if(isset($_REQUEST['str']))
//	{
//            $str = $_REQUEST['str'];
	if(isset($submit_post_get['str']))
	{
            $str = $submit_post_get['str'];
	}
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
	$default_opts = array(
		'limit' => $limit,
		'page' => $page,
		'type' => array(4),
		'userid' => $userID,
		'orderby' => 'request_ts',
		'search_string' => $str,
		'order' => 'a'
	);
	
	$datas = userFriendSearch($default_opts);
	
	
	//var_dump($datas);
	
	//$xml_output = "<friendlist order='blockedlist'>";
        $xml_output = array();
	foreach($datas as $data)
	{
            $userInfo = getUserInfo( $data['id'] );
            if ($userInfo['profile_Pic'] != "")
            {
                $chatprofilepic="media/tubers/crop_".substr($userInfo['profile_Pic'],0,strlen($userInfo['profile_Pic'])-3)."png";
            }else
            {
                $chatprofilepic="media/tubers/na.png";
            }
            $xml_output[]= array(
                            'id'=>$data['id'],
							/*code changed by sushma mishra on 30-sep-2015 to get fullname using returnUserDisplayName function starts from here*/
                            //'fullname'=>safeXML($data['FullName']),
							'fullname'=>returnUserDisplayName($userInfo),
							/*code changed by sushma mishra on 30-sep-2015 ends here*/
                            'username'=>safeXML($data['YourUserName']),
                            'chatprofilepic'=>$chatprofilepic,
                            'prof_pic'=>"media/tubers/" .$data['profile_Pic']
                        );
		
	}
	header('Content-type: application/json');	
        echo json_encode($xml_output);
