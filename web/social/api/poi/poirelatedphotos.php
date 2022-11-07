<?php
/*! \file
 * 
 * \brief This api returns point of interest related photos
 * 
 * \todo <b><i>Change from XML to Json object and should be like/ remove like instead of upvote/downvote</i></b>
 * 
 * @param lid location id
 * @param limit number of records to return
 * @param p page number (starting from 0)
 * 
 * @return <b>res</b> xml :
 * @return <pre> 
 * @return       <b>id</b> photo id
 * @return       <b>title</b> photo title
 * @return       <b>description</b> photo description
 * @return       <b>user</b> photo user
 * @return       <b>n_views</b> photo number of views
 * @return       <b>duration</b> photo duration
 * @return       <b>rating</b> photo average rating
 * @return       <b>nb_rating</b> photo number of rating
 * @return       <b>nb_comments</b> photo number of comments
 * @return       <b>up_vote</b> photo number of likes
 * @return       <b>down_vote</b> photo number of dislikes
 * @return       <b>videolink</b> photo link
 * @return       <b>thumblink</b> photo thumb link
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */
        $lid_get = $request->query->get('lid','');
//	if (isset($_GET['lid']))
//	{
//		$location_id =  $_GET['lid'];
	if ($lid_get)
	{
		$location_id = $lid_get;
	}else
	{
		exit();	
	}
	
	header("content-type: application/xml; charset=utf-8");  
	
	
	$limit = 25;
	$page = 0;
	
	$expath = "../";
	include($expath."heart.php");
	
        $limit_get  = $request->query->get('limit','');
        $p_get      = $request->query->get('p','');
//	if (isset($_GET['limit']))
//	{
//		$limit = $_GET['limit'];
	if ($limit_get)
	{
		$limit = $limit_get;
	}
//	if (isset($_GET['p']))
//	{
//		$page = $_GET['p'];
	if ($p_get)
	{
		$page = $p_get;
	}

	$options = array('limit' => $limit, 'type' => 'i', 'orderby' => 'id', 'order' => 'a' , 'page' => $page,'location_id' => $location_id );
	$datas = mediaSearch($options);		
	
	$res = "<photos order='related to location'>";
			foreach ( $datas as $data )
			{
					$res .= '<photo order="poi">';
					$res .= '<id>'.safeXML($data['id']).'</id>';
					$res .= '<title>'.safeXML($data['title']).'</title>';				
					$res .= '<description>'.safeXML($data['description']).'</description>';		
					$userinfo = getUserInfo($data['userid']);				
					$res .= '<user>'.$userinfo['YourUserName'].'</user>';
					$res .= '<n_views>'.$data['nb_views'].'</n_views>';
					
					
					$res .= '<up_vote>'.$data['up_votes'].'</up_vote>';
					$res .= '<down_vote>'.$data['down_votes'].'</down_vote>';
					$res .= '<rating>'.$stats['rating'].'</rating>';				
					$res .= '<nb_rating>'.$stats['nb_ratings'].'</nb_rating>';	
					$res .= '<nb_comments>'.$stats['nb_comments'].'</nb_comments>';
					
					$res .= '<fulllink>'.''.$data['fullpath'].$data['name'].'</fulllink>';
					/////$res .= '&lt;item&gt;http://172.16.124.254:8181/touristtube/'.substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path)).'&lt;&frasl;item&gt;<br>';
				
					//echo $data['id']." ".$data['fullpath'];
					//var_dump(getVideoThumbnail($data['id'],$data['fullpath'],0));
				
				$res .= '</photo>';		
			}
			
			$res .= "</photos>";
			
			echo $res;