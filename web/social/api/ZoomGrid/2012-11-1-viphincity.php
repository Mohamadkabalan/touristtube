<?php
	header("content-type: application/xml; charset=utf-8");  
	$expath = "../";
	include("../heart.php");
	echo '<?xml version="1.0" encoding="utf-8"?>';
	
	if (isset($_GET['limit']) && isset($_GET['page']) && isset($_GET['cityid']))
	{
		if (is_numeric($_GET['limit']) && is_numeric($_GET['page']))
		{
			$datas = getVideosInCity($_GET['cityid'],$_GET['limit'],$_GET['page']);
			
			$res = "<videos  order=\"videosincity\">";
			foreach ( $datas as $data )
			{
				$res .= '<video>';
					$res .= '<id>'.$data['id'].'</id>';
					$res .= '<title>'.$data['title'].'</title>';				
					$res .= '<description>'.$data['description'].'</description>';
				
					$userinfo = getUserInfo($data['userid']);
					$res .= '<user>'.$userinfo['YourUserName'].'</user>';
					$res .= '<n_views>'.$data['nb_views'].'</n_views>';
					$res .= '<duration>'.$data['duration'].'</duration>';
					
					
					$res .= '<up_vote>'.$data['up_votes'].'</up_vote>';
					$res .= '<down_vote>'.$data['down_votes'].'</down_vote>';
					//$res .= '<rating>'.$data['rating'].'</rating>';				
					//$res .= '<comments>'.$data['comments'].'</comments>';
					$res .= '<videolink>'.''.$data['fullpath'].$data['name'].'</videolink>';
					$res .= '<thumblink>'.''.substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path)).'</thumblink>';
					/////$res .= '&lt;item&gt;http://172.16.124.254:8181/touristtube/'.substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path)).'&lt;&frasl;item&gt;<br>';
				
					//echo $data['id']." ".$data['fullpath'];
					//var_dump(getVideoThumbnail($data['id'],$data['fullpath'],0));
				
				$res .= '</video>';		
			}
			
			$res .= "</videos>";
		}
		
	}
	
	echo $res;
	
?>