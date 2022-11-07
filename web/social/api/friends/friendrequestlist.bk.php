<?php
//	session_id($_REQUEST['S']); 
$submit_post_get = array_merge($request->query->all(),$request->request->all());
	session_id($submit_post_get['S']); 
        session_start();
	
	$expath = "../";
	
	include($expath."heart.php");
	include($path."services/lib/chat.inc.php");
	
	$userID = $_SESSION['id'];

	$str = null;
	
        $str_get = $request->query->get('str','');
//	if(isset($_GET['str']))
	if($str_get)
	{
//		$str = $_GET['str'];
		$str = $str_get;
	}
	
	$default_opts = array(
		'limit' => 15000,
		'page' => 0,
		'type' => 0,
		'userid' => $userID,
		'orderby' => 'request_ts',
		'order' => 'a',
		'search_string' => $str
	);
	
	$datas = userFriendSearch($default_opts);
	
	
	//var_dump($datas);
	
	$xml_output = "<friendlist order='friendrequestlist'>";
	foreach($datas as $data)
	{
		$xml_output .= "<friend>";
		$xml_output .= "<id>".$data['id']."</id>";
		$xml_output .= "<request_msg>".safeXML($data['request_msg'])."</request_msg>";
		$xml_output .= "<fullname>".safeXML($data['FullName'])."</fullname>";
		$xml_output .= "<username>".$data['YourUserName']."</username>";
				
		$userInfo = getUserInfo( $data['id'] );
		if ($userInfo['profile_Pic'] != "")
		{
			$xml_output .= "<chatprofilepic>"."/media/tubers/crop_".substr($userInfo['profile_Pic'],0,strlen($userInfo['profile_Pic'])-3)."png"."</chatprofilepic>";
		}else
		{
			$xml_output .= "<chatprofilepic>"."media/tubers/na.png"."</chatprofilepic>";
		}
		
		$xml_output .= "<prof_pic>"."media/tubers/" .$data['profile_Pic'] . "</prof_pic>";
		$xml_output .= "</friend>";
		
	}
	$xml_output .= "</friendlist>";
	header("content-type: application/xml; charset=utf-8");  
	echo $xml_output;