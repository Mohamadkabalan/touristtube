<?php
	//header("content-type: application/xml; charset=utf-8");  
	$expath = "../";
		
	

	include_once("../heart.php");
	include_once("../resizepicture.php");
        
        $userID_get = $request->query->get('userID','');
//	if(isset($_GET['userID']))
	if($userID_get)
	{
//		$profilePic = getUserInfo($_GET['userID']);	
		$profilePic = getUserInfo($userID_get);	
		
		$profile = $profilePic['profile_Pic']; 
		
		cropProfileAndCreateChatThumb($profile,"../../media/tubers");
		
	}
	
	function cropProfileAndCreateChatThumb($pic,$path)
	{
		

		exec('convert -size 105x105 xc:none -fill '.$path."/".$pic.' -draw "circle 50,50 50,3" '.$path.'/chatthumbs/'.substr($pic,0,strlen($pic)-4).'.png');
		exec("convert -size 105x105  ".$path."/chatthumbs/".substr($pic,0,strlen($pic)-4).".png thumbStroke.png  -composite  ".$path."/chatthumbs/".substr($pic,0,strlen($pic)-4).".png");
		
		//echo "<img src='".$path."/chatthumbs/".substr($pic,0,strlen($pic)-4).".png'>";
		
		
		$ctype="image/png";
		header("Content-Type: $ctype"); 
		
		readfile($path."/chatthumbs/".substr($pic,0,strlen($pic)-4).".png");	
	}
