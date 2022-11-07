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
	
//	$id = intval( $_REQUEST['id'] );
//        $limit = intval( $_REQUEST['limit'] );
//        $page = intval( $_REQUEST['page'] );
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
	$id = intval( $submit_post_get['id'] );
        $limit = intval( $submit_post_get['limit'] );
        if($limit == 0){
            $limit = 10;
        }
        $page = intval( $submit_post_get['page'] );
        
//        $options0 = array('channelid'=>$globchannelid,'n_results' => true,'is_visible'=>$is_owner_visible,'from_ts'=>$frtxt ,'to_ts'=>$totxt,'search_string' => $txt_srch);	
//	$channelNewsCount = channelnewsSearch($options0);
//	
//	$newsOptions = array('channelid'=>$globchannelid,'limit'=>$limit,'page'=>$currentpage, 'orderby'=>'id', 'order'=>'d','is_visible'=>$is_owner_visible,'from_ts'=>$frtxt ,'to_ts'=>$totxt,'search_string' => $txt_srch);	
//	$channelNewsInfo = channelnewsSearch($newsOptions);
	
	$options = array(
            'limit' => $limit,
            'page' => $page,
            'channelid' => $id,
            'order' => 'd',
            'orderby' => 'id',
            'is_visible' => 1
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
				'news_create_date'=> returnSocialTimeFormat($news['create_ts'])
                            );
		}
	}
	
	echo json_encode($output);