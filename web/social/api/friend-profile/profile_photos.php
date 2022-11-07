<?php

$submit_post_get = array_merge($request->query->all(),$request->request->all());
session_id($submit_post_get['S']);
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

$options = array ( 'limit' => $limit, 'page' => $page , 'userid' => $userId , 'type' => 'i', 'orderby' => 'id' , 'order' => 'd' );

//if( isset($_REQUEST['ss']) && ($_REQUEST['ss']!='') ) $options['search_string'] = db_sanitize($_REQUEST['ss']);
if( isset($submit_post_get['ss']) && ($submit_post_get['ss']!='') ) $options['search_string'] = $submit_post_get['ss'];

$userVideos = mediaSearch( $options );


if ( $userVideos )
{
	//$xml_output = "<photos order=\"friendprofilephotos\">";
        $xml_output = array();
	foreach($userVideos as $data ) 
	{
                $userinfo = getUserInfo($data['userid']);
		$xml_output[] = array(
                                    'id'=>$data['id'],
                                    'title'=>str_replace('"', "'", $data['title']),		
                                    'description'=>str_replace('"', "'", $data['description']),
                                    'keywords'=>str_replace('"', "'",$data['keywords']),				
                                    'user'=>$userinfo['YourUserName'],
                                    'n_views'=>$data['nb_views'],
                                    'up_vote'=>$data['like_value'],
                                    'down_vote'=>$data['down_votes'],
                                    'country'=>$data['country'],
                                    'cityname'=>$data['cityname'],
                                    'rating'=>$stats['rating'],			
                                    'nb_rating'=>$stats['nb_ratings'],
                                    'nb_comments'=>$stats['nb_comments'],
                                    'fulllink'=>$data['fullpath'].$data['name'],
                                    'category'=>$data['category'],
                                    'placetakenat'=>$data['placetakenat'],
                                    'is_public'=>$data['is_public'],
                                );
	} // end for  
	header('Content-type: application/json');
	echo json_encode($xml_output);
    }else
    {
            //$xml_output .= "<photos order=\"profilephotos\"></photos>";
            $xml_output[] = array();
            echo json_encode($xml_output);
    }