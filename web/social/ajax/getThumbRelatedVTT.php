<?php
	header("Content-Type:text/vtt;charset=utf-8");
	$path = "../";
	$limit=12;
	
	$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
	include_once ( $path . "inc/common.php" );
	include_once ( $path . "inc/bootstrap.php" );
        include_once ( $path . "inc/functions/users.php" );
	include_once ( $path . "inc/functions/videos.php" );
        
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
//	$id  = intval($_REQUEST['id']);
//	$is_post  = intval($_REQUEST['is_post']);
	$id  = intval($submit_post_get['id']);
	$is_post  = intval($submit_post_get['is_post']);
        
        function format_time($t,$f=':') {
          return sprintf("%02d%s%02d%s%02d", floor($t/3600), $f, ($t/60)%60, $f, $t%60);
        }
        if($is_post==0){
            $VideoInfo = getVideoInfo($id);
            $thumbFolder = $path . videoGetInstantThumbPath2($VideoInfo) . "/";
            $folder = currentServerURL().'/'.videoGetInstantThumbPath2($VideoInfo) . "/";

            $nbBigImg = count(glob($thumbFolder . 'out*.jpg'));
        }else{
            $VideoInfo = socialPostsInfo($id);
            $thumbFolder = $path . videoGetInstantThumbPathPost($VideoInfo) . "/";
            $folder = currentServerURL().'/'.videoGetInstantThumbPathPost($VideoInfo) . "/";
            $nbBigImg = count(glob($thumbFolder . 'out*.jpg'));
        }
	$res = "WEBVTT\r\n\r\n";
        $oldval=0;
	for ($i = 1; $i <= $nbBigImg; $i++) {
            $dip0= format_time($oldval);            
            $oldval++;            
            $dip1= format_time($oldval);
            
            $res .= $dip0.'.000'.' --> '.$dip1.'.000'. "\r\n";            
            $res .= ''.$folder . 'out' . $i . '.jpg?no_cach='.rand(). "\r\n\r\n";
	}
	echo $res;