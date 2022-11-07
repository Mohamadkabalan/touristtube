<?php
/*! \file
 * 
 * \brief This api returns friend request list
 * 
 * \todo <b><i>Change from xml to Json object</i></b>
 * 
 * @param id category id
 * @param channelid channel id
 * 
 * @return xml:
 * @return <pre> 
 * @return       <b>id</b> media id
 * @return       <b>type</b> media type
 * @return       <b>thumbnail</b> media thumbnailpath
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */
	
	$expath = "../";			
	header("content-type: application/xml; charset=utf-8");  
	include("../heart.php");
	
//	$catid = intval( $_REQUEST['id'] );
//	$channelid = intval( $_REQUEST['channelid'] );
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
	$catid = intval( $submit_post_get['id'] );
	$channelid = intval( $submit_post_get['channelid'] );
	
	$options = array(
		'limit' => 999,
		'page' => 0,
		'catalog_id' => $catid,
		'channel_id' => $channelid,
		'orderby' => 'pdate',
		'type' => 'a',
		'order' => 'd'
	);
	
	$media_info = mediaSearch($options);
	
	$output = "<album>\n";
	
	foreach($media_info as $media){
		$output .= "
		<media>
			<id>".$media['id']."</id>
			<type>".$media['image_video']."</type>
			<thumbnail>".photoReturnThumbSrc($media)."</thumbnail>
		</media>";
	}
	
	$output .= "</album>";
	
	echo $output;