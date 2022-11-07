<?php

$submit_post_get = array_merge($request->query->all(),$request->request->all()); 
session_id($submit_post_get['S']); 
        session_start();
$expath    = '../';

include_once("../heart.php");


$userId = 0;
//$userId = $_REQUEST['uid'];
$userId = $submit_post_get['uid'];

	$userId = ( int ) $userId;
	
if ( $userId == 0 )
	die ( 'Invalid info!' );

$userInfo   = getUserInfo ( $userId );

//$limit = intval($_REQUEST['limit']);//$CONFIG [ 'limit' ] [ 'videos' ];
$limit = intval($submit_post_get['limit']);//$CONFIG [ 'limit' ] [ 'videos' ];

$page = 0;
//if ( isset ($_REQUEST['page']) ) $page = intval($_REQUEST['page']);
if ( isset ($submit_post_get['page']) ) $page = intval($submit_post_get['page']);

$options = array ( 'limit' => $limit, 'page' => $page , 'userid' => $userId , 'type' => 'v', 'orderby' => 'id' , 'order' => 'd' );

//if( isset($_REQUEST['ss']) && ($_REQUEST['ss']!='') ) $options['search_string'] = db_sanitize($_REQUEST['ss']);
if( isset($submit_post_get['ss']) && ($submit_post_get['ss']!='') ) $options['search_string'] = $submit_post_get['ss'];

$userVideos = mediaSearch( $options );


if ( sizeof($userVideos)>0 )
{
	$xml_output = "<videos order=\"friendprofilevids\">";
	foreach($userVideos as $data)
	{
		$xml_output .= '<video>';
			$xml_output .= '<id>'.$data['id'].'</id>';
			$xml_output .= '<title>'.safeXML($data['title']).'</title>';				
			$xml_output .= '<description>'.safeXML($data['description']).'</description>';
			$userinfo = getUserInfo($data['userid']);
			$xml_output .= '<user>'.safeXML($userinfo['YourUserName']).'</user>';
			$xml_output .= '<n_views>'.$data['nb_views'].'</n_views>';
			$xml_output .= '<duration>'.$data['duration'].'</duration>';
			$xml_output .= '<up_vote>'.$data['up_votes'].'</up_vote>';
			$xml_output .= '<down_vote>'.$data['down_votes'].'</down_vote>';
			$xml_output .= '<country>'.safeXML($data['country']).'</country>';
			$xml_output .= '<cityname>'.safeXML($data['cityname']).'</cityname>';
			$xml_output .= '<rating>'.$data['rating'].'</rating>';
			$xml_output .= '<nb_rating>'.$stats['nb_ratings'].'</nb_rating>';	
			$xml_output .= '<is_public>'.$data['is_public'].'</is_public>';
			$xml_output .= '<category>'.safeXML($data['category']).'</category>';
			$xml_output .= '<keywords>'.safeXML($data['keywords']).'</keywords>';
			$xml_output .= '<placetakenat>'.safeXML($data['placetakenat']).'</placetakenat>';
			$xml_output .= '<comments>'.safeXML($data['comments']).'</comments>';
			$xml_output .= '<videolink>'.''.safeXML($data['fullpath'].$data['name']).'</videolink>';
			$xml_output .= '<thumblink>'.''.safeXML(substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path))).'</thumblink>';
			
		$xml_output .= '</video>';
	} // end for  
	$xml_output .= "</videos>";
	header("content-type: application/xml; charset=utf-8");
	echo $xml_output;
}else
{
	$xml_output = "<videos order=\"friendprofilevids\"></videos>";
	echo $xml_output;
}// end if