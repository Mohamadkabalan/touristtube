<?php
/*! \file
 * 
 * \brief This api returns news information of channel
 * 
 * 
 * @param id channel id
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * 
 * @return <b>output</b> JSON list with the following keys:
 * @return <pre> 
 * @return       <b>id</b> news id
 * @return       <b>news_description</b> news description
 * @return       <b>news_create_date</b> news create date
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */
	
	$expath = "../";			
	//header("content-type: application/xml; charset=utf-8");
        header('Content-type: application/json');
	include("../heart.php");
	
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
	$id = intval( $submit_post_get['id'] );
        $news_id = intval( $submit_post_get['news_id'] );
	
	$options = array(
            'channelid' => $id,
            'is_visible' => 1,
            'id'=>$news_id
	);
	
	$channelnewsInfo = channelnewsSearch($options);
	//print_r($channelnewsInfo);exit;
	$output = array();
	
	if($channelnewsInfo){
		
		//$output .= "<channel_news>\n";
		
		foreach($channelnewsInfo as $news){
			$output[] = array(
				'id'=>$news['id'],
				'news_description'=>htmlEntityDecode($news['description']),
				'news_create_date'=>date('d/m/Y', strtotime($news['create_ts'])),
                            );
		}
	}
	
	echo json_encode($output);