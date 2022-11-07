<?php
	//header("content-type: application/xml; charset=utf-8");  
	require_once("heart.php");
	$datas = getVideos(100);
	
	$res = "<videos order=\"latest\">";
	foreach ( $datas as $data )
	{
		/*$res .= '<video >';
			$res .= '<id>'.$data['id'].'</id>';
			$res .= '<title>'.safeXML($data['title']).'</title>';				
			$res .= '<description>'.safeXML($data['description']).'</description>';
			$userinfo = getUserInfo($data['userid']);
			$res .= '<user>'.$userinfo['YourUserName'].'</user>';
			$res .= '<n_views>'.$data['nb_views'].'</n_views>';
			$res .= '<duration>'.$data['duration'].'</duration>';
			
			
			$res .= '<up_vote>'.$data['up_votes'].'</up_vote>';
			$res .= '<down_vote>'.$data['down_votes'].'</down_vote>';
			$res .= '<rating>'.$data['rating'].'</rating>';				
			//$res .= '<comments>'.$data['comments'].'</comments>';
			$res .= '<videolink>'.''.$data['fullpath'].$data['name'].'</videolink>';
				$res .= '<thumblink>'.''.substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path)).'</thumblink>';
		$res .= '</video>';*/		
		$res .= '"https://www.touristtube.com/api/resizepicture.php?l='.substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path)).'&w=506&h=209",';
	}
	$res .= "</videos>";
	echo $res;