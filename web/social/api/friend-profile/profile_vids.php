<?php

$submit_post_get = array_merge($request->query->all(),$request->request->all());
session_id($_REQUEST['S']);
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
	//$xml_output = "<videos order=\"friendprofilevids\">";
        $xml_output = array();
	foreach($userVideos as $data)
	{
            $userinfo = getUserInfo($data['userid']);
            $xml_output[]= array(
                            'id'=>$data['id'],
                            'title'=>str_replace('"', "'", $data['title']),				
                            'description'=>str_replace('"', "'", $data['description']),
                            'user'=>$userinfo['YourUserName'],
                            'n_views'=>$data['nb_views'],
                            'duration'=>$data['duration'],
                            'up_vote'=>$data['like_value'],
                            'down_vote'=>$data['down_votes'],
                            'country'=>$data['country'],
                            'cityname'=>$data['cityname'],
                            'rating'=>$data['rating'],
                            'nb_rating'=>$stats['nb_ratings'],
                            'is_public'=>$data['is_public'],
                            'category'=>$data['category'],
                            'keywords'=>$data['keywords'],
                            'placetakenat'=>$data['placetakenat'],
                            'comments'=>$data['comments'],
                            'videolink'=>$data['fullpath'].$data['name'],
                            'thumblink'=>substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path)),
			);
	} // end for  
	header('Content-type: application/json');
	echo json_encode($xml_output);
}else
{
        $xml_output[] = array();
        echo json_encode($xml_output);
}// end if