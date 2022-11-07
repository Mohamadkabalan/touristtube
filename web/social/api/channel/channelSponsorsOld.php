<?php
	//getConnectedtubers($channel_id);
//	session_id($_REQUEST['S']);
//	session_start();
        
	$expath = "../";			
	//header("content-type: application/xml; charset=utf-8");  
        header('Content-type: application/json');
	include("../heart.php");
	
//	$userID = $_SESSION['id'];
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
//	$userID = mobileIsLogged($_REQUEST['S']);
	$userID = mobileIsLogged($submit_post_get['S']);
        if( !$userID ) die();
	$xml_output = array();
	//$xml_output .= "<friendlist order='channel_connections'>";
	
	$cid = null;
//	if (isset($_REQUEST['cid']))
//	{
//		$cid = $_REQUEST['cid'];
	if (isset($submit_post_get['cid']))
	{
		$cid = $submit_post_get['cid'];
	} 
	//$connetedUsers = getConnectedtubers($cid, "true");
	$connetedUsers=getSponsoredChannel($cid);
	foreach ($connetedUsers as $connectedUser)
	{
		$data = getUserInfo($connectedUser);
                if($data['FullName'] != ''){
                    $fullname=$data['FullName'];
                }
                else{ 
                    $fullname=$data['YourUserName'];
                }
                
                if($userID != $data['id'])
		{
			if(userIsFriend($userID,$data['id']))
			{
                            $isfriend = 'YES';
			}else
			{
                            $isfriend = 'NO';
			}
			
			if(userSubscribed($userID,$data['id']))
			{
                            $isfollowed='YES';				
			}else
			{
                            $isfollowed='NO';							
			}
			
			// $userInfo = getUserInfo( $data['id'] );
			if ($data['profile_Pic'] != "")
			{
                            $chatprofilepic = "/media/tubers/crop_".substr($data['profile_Pic'],0,strlen($data['profile_Pic'])-3)."png";
			}else
			{
                            $chatprofilepic = "media/tubers/na.png";
			}
			$prof_pic="media/tubers/" .$data['profile_Pic'];
			
		}
                
		//var_dump($data);
		$xml_output[] = array(
                                'id'=>$data['id'],
                                'fullname'=>$fullname,
                                'username'=>$data['YourUserName'],
                                'isfriend'=>$isfriend,
                                'isfollowed'=>$isfollowed,
                                'chatprofilepic'=>$chatprofilepic,
                                'prof_pic'=>$prof_pic,
                    );
		
            }
	echo json_encode($xml_output);