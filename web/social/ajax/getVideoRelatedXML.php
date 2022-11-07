<?php
	$path = "../";
	$limit=12;
	
	
	$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
	include_once ( $path . "inc/common.php" );
	include_once ( $path . "inc/bootstrap.php" );

	include_once ( $path . "inc/functions/users.php" );
	include_once ( $path . "inc/functions/videos.php" );
        
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
//	$id  = intval($_REQUEST['id']);
	$id  = intval($submit_post_get['id']);
        $VideoInfo = getVideoInfo($id);
        
        $videos = videosGetRelatedSolr($VideoInfo, 'v', $limit , 0, 1);
	
        function safeXML($text) {
            $res = str_replace("&", "&amp;", $text);
            $res = strip_tags($res);
            $res = trim($res);
            return $res;
        }

	$res = "<rss version=\"2.0\" xmlns:media=\"http://search.yahoo.com/mrss/\">\r\n"
                . "<channel>\r\n";
	foreach ( $videos['media'] as $data )	{
            $title = safeXML($data['title']);
            $title_text = (strlen($title) > 20) ? substr($title, 0, 17) . '...' : $title;
            $res .= '<item>';
                $res .= '<title>'.$title_text.'</title>';
                $res .= '<link>/video/'.videoToURLHashed($data).'</link>';
                $res .= '<media:thumbnail url="'.videoReturnThumbSrc($data).'"/>';
                $res .= '<media:content url="" type="video/mp4" />';
            $res .= '</item>'. "\r\n";
	}
	$res .= "</channel>";
        $res .= "\r\n";
	$res .= "</rss>";
	
	echo $res;