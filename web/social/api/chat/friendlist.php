<?php
	//return integer 0 => offline, 1=> online , 2=> away
//	session_id($_REQUEST['S']); 
//        session_start();
	$expath = "../";
	include($expath."heart.php");
	include($path."services/lib/chat.inc.php");
	//$userID = $_SESSION['id'];
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//        $userID = mobileIsLogged($_REQUEST['S']);
        $userID = mobileIsLogged($submit_post_get['S']);
        if( !$userID ) die();
	if($userID == '')  $xml_output = "No session";
        else {
	$datas = userGetFreindList($userID);
	$xml_output = "<friendlist order='chatfriendlist'>";
	foreach($datas as $data)
	{
		$xml_output .= "<friend>";
			$xml_output .= "<id>".$data['id']."</id>";
			/*code added by sushma mishra on 30-sep-2015 to get fullname using returnUserDisplayName function starts from here*/
			//$xml_output .= "<fullname>".safeXML($data['FullName'])."</fullname>";
			$userInfo = getUserInfo($data['id']);
			$xml_output .= "<fullname>".returnUserDisplayName($userInfo)."</fullname>";
			/*code added by sushma mishra on 30-sep-2015 ends here*/
			$xml_output .= "<username>".safeXML($data['YourUserName'])."</username>";					
			
			if ($userInfo['profile_Pic'] != "")
			{
				$xml_output .= "<chatprofilepic>"."media/tubers/crop_".substr($userInfo['profile_Pic'],0,strlen($userInfo['profile_Pic'])-3)."png"."</chatprofilepic>";
			}else
			{
				$xml_output .= "<chatprofilepic>"."media/tubers/na.png"."</chatprofilepic>";
			}
			
			
			
			$xml_output .= "<status>".chatGetUserStatus($data['id'])."</status>";
			$xml_output .= "<prof_pic>"."media/tubers/" .$data['profile_Pic'] . "</prof_pic>";
			if (userFavoriteUserAdded($userID,$data['id']))
			{	
				$xml_output .= "<is_fav>YES</is_fav>";
			}else
			{
				$xml_output .= "<is_fav>NO</is_fav>";
			}
			
			if (userIsBlocked($userID, $data['id']))
			{	
				$xml_output .= "<is_blocked>YES</is_blocked>";
			}else
			{
				$xml_output .= "<is_blocked>NO</is_blocked>";
			}				
		$xml_output .= "</friend>";	
	}
	$xml_output .= "</friendlist>";
                }
	header("content-type: application/xml; charset=utf-8");  
	echo $xml_output;