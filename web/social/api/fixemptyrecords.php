<?php
	$CONFIG [ 'db' ] [ 'host' ] = "localhost";//"192.168.2.7";
	$CONFIG [ 'db' ] [ 'name' ] = "touristtube";
	$CONFIG [ 'db' ] [ 'user' ] = "tourist";
	$CONFIG [ 'db' ] [ 'pwd' ]  = "touristMysqlP@ssw0rd";

	$myConn = db_connect($CONFIG [ 'db' ] [ 'host' ], $CONFIG [ 'db' ] [ 'user' ], $CONFIG [ 'db' ] [ 'pwd' ]) or die("Unable to connect to server"); 
	db_select_db($myConn,$CONFIG [ 'db' ] [ 'name' ]) or die("Unable to select database");
	db_query("SET NAMES 'UTF8'");
	
	$query = "SELECT `id`,`name`,`fullpath`,`title`,`image_video`,`is_public` FROM `cms_videos` ;";
	$do = db_query($query);
	
	while($as = db_fetch_array($do))
	{
		if(!file_exists("../".$as['fullpath'].$as['name']))
		{
			//echo $as['id']." ".$as['name']." ".$as['fullpath']." ".$as['title']." ".$as['image_video']." ".$as['is_public']."<br>";
			echo $as['id']."<br>";
		}else
		{
		
		}
	}