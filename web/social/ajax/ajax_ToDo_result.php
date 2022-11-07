<?php
    $path = "../";

	$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" =>0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );

    include_once ( $path . "inc/functions/videos.php" );
    include_once ( $path . "inc/functions/users.php" );
	include_once ( $path . "inc/functions/bag.php" );
	
	//$link = "http://para-tube/ttback/uploads/";
	$link = ReturnLink('media/discover').'/';

	$user_id = userGetID();
	
        global $dbConn;
	$user_is_logged=0;
	if(userIsLogged()){
		$user_is_logged=1;	
	}
//	$latitude= (double)@$_POST['data_lat'];
//	$longitude= (double)@$_POST['data_log'];
//	$diff_angle= (double)@$_POST['data_angle'];
//	$entity_type= intval(@$_POST['entity_type']);
	$latitude= (double)$request->request->get('data_lat', '');
	$longitude= (double)$request->request->get('data_log', '');
	$diff_angle= (double)$request->request->get('data_angle', '');
	$entity_type= intval($request->request->get('entity_type', ''));
	
	
//	$frtxt=xss_sanitize(@$_POST['frtxt']);
	$frtxt=$request->request->get('frtxt', '');
	if($frtxt==""){
		$frtxt=null;	
	}
	
//	$totxt=xss_sanitize(@$_POST['totxt']);
	$totxt=$request->request->get('totxt', '');
	if($totxt==""){
		$totxt=null;	
	}
	
	$Result = array();
	
	$str_data ="";
	
	$longitude_search0 =$longitude - $diff_angle;
	$longitude_search1 =$longitude + $diff_angle;
	$latitude_search0 = $latitude - $diff_angle;
	$latitude_search1 = $latitude + $diff_angle;
	
	$media_landmarks = array();
	
	if( $entity_type == SOCIAL_ENTITY_EVENTS ||  $entity_type == 0){
		$options = array(
			'orderby' => 'todate',
			'order' => 'd',
			'status' => 3,
			'page' => 0,	
			'from_ts'=>$frtxt ,
			'to_ts'=>$totxt,
			'longitude_min'=>$longitude_search0,
			'longitude_max'=>$longitude_search1,
			'latitude_min'=>$latitude_search0,
			'latitude_max'=>$latitude_search1,
			'is_visible'=>1,
			'limit' => 7
		);
		$upcoming_events = channeleventSearch($options);
	}
	if( $entity_type == SOCIAL_ENTITY_LANDMARK ||  $entity_type == 0){	
//  Changed by Anthony Malak 14-05-2015 to PDO database
//  <start>
                $params = array();  	
//		$query_landmarks = "SELECT p.*, i.id as i_id , i.filename as img FROM `discover_poi` as p INNER JOIN discover_poi_images as i on i.poi_id=p.id WHERE p.longitude BETWEEN $longitude_search0 AND $longitude_search1  AND p.latitude BETWEEN $latitude_search0 AND $latitude_search1 GROUP BY p.id ORDER BY i.default_pic DESC LIMIT 15";
		$query_landmarks = "SELECT p.*, i.id as i_id , i.filename as img FROM `discover_poi` as p INNER JOIN discover_poi_images as i on i.poi_id=p.id WHERE p.longitude BETWEEN :Longitude_search0 AND :Longitude_search1  AND p.latitude BETWEEN :Latitude_search0 AND :Latitude_search1 GROUP BY p.id ORDER BY i.default_pic DESC LIMIT 15";
		
                $params[] = array(  "key" => ":Longitude_search0",
                                    "value" =>$longitude_search0);
                $params[] = array(  "key" => ":Longitude_search1",
                                    "value" =>$longitude_search1);
                $params[] = array(  "key" => ":Latitude_search0",
                                    "value" =>$latitude_search0);
                $params[] = array(  "key" => ":Latitude_search1",
                                    "value" =>$latitude_search1);
//		$ret_landmarks = db_query($query_landmarks);
                $select = $dbConn->prepare($query_landmarks);
                PDO_BIND_PARAM($select,$params);
                $ret_landmarks    = $select->execute();
		
//		while($row = db_fetch_array($ret_landmarks)){
//			$media_landmarks[] = $row;
//		}
                $row = $select->fetchAll();
                $media_landmarks=$row;
                
	}
	$upcoming_events = (!$upcoming_events)? array() : $upcoming_events;
	
	$count1 = count($upcoming_events);
	if($count1 > 3 ) $count1 = 3;

	$count = count($media_landmarks);
	if($count > 3 ) $count = 3;

	if($count < 3 && $count1 == 3){
		$count1 = count($upcoming_events);
		if($count1 > (6-$count) ) $count1 = 6-$count;
	}else if($count1 < 3 && $count == 3){
		$count = count($media_landmarks);
		if($count > (6-$count1) ) $count = 6-$count1;
	}
	for($k=0; $k<$count1; $k++){
        $upcoming_events_item = $upcoming_events[$k];
		$from_date = date( 'd/m/Y', strtotime($upcoming_events_item['fromdate']) );
		$to_date = date( 'd/m/Y', strtotime($upcoming_events_item['todate']) );
		$event_date='<span class="yellow">'._("from")." ".$from_date."</br>"._("to")." ".$to_date.'</span>';
		if($from_date==$to_date){
			   $event_date='<span class="yellow">'.$from_date.'</span>';
		}
		$page_link = ReturnLink('channel-events-detailed/'.$upcoming_events_item['id']);
		 $image_pic = ($upcoming_events_item['photo']) ? photoReturneventImage($upcoming_events_item) : ReturnLink('media/images/channel/eventthemephoto.jpg');
		 $str_data .= '<div class="hotels_cont_inside" data-id="'.$upcoming_events_item['id'].'" data-type="'.SOCIAL_ENTITY_EVENTS.'">
				<div class="restoreviews">
					<div class="hotelreview_txt">'.$event_date .'</br>'. htmlEntityDecode($upcoming_events_item['name']).'</div>';

		$str_data .= '</div>
		<div class="hotelsreviews_pic"><a href="'.$page_link.'" target="_blank"><img src="'.$image_pic.'" width="175" height="109"></a></div>';
		if($user_is_logged==1){
				$str_data .= '<div class="hotelsplus"></div>';
		}
		$str_data .= '</div>';
	}
	for($k=0; $k<$count; $k++){
		$media_landmarks_item = $media_landmarks[$k];
		$id = $media_landmarks_item['id'];
//  Changed by Anthony Malak 14-05-2015 to PDO database
//  <start>
                $params = array();
//		$query_landmarks_reviews = "SELECT COUNT(id) FROM `discover_poi_reviews` WHERE poi_id = $id AND published=1";
		$query_landmarks_reviews = "SELECT COUNT(id) FROM `discover_poi_reviews` WHERE poi_id = :Id AND published=1";
                $params[] = array(  "key" => ":Id",
                                    "value" =>$id);
                $select = $dbConn->prepare($query_landmarks_reviews);
                PDO_BIND_PARAM($select,$params);
                $retquery_landmarks_reviews_count    = $select->execute();
//		$retquery_landmarks_reviews_count = db_query($query_landmarks_reviews);
//		$row_reviews_count = db_fetch_array($retquery_landmarks_reviews_count);
                $row_reviews_count = $select->fetch();
//  Changed by Anthony Malak 14-05-2015 to PDO database
//  <end>
		$n_results = $row_reviews_count[0];

		$image_pic = $link.'thumb/'.$media_landmarks_item['img'];
		$page_link = ReturnLink('things2do/id/'.$id);
		$str_data .= '<div class="hotels_cont_inside" data-id="'.$media_landmarks_item['id'].'" data-type="'.SOCIAL_ENTITY_LANDMARK.'">
							<div class="restoreviews">
									<a href="'.$page_link.'" target="_blank"><div class="hotelreview_txt">'.htmlEntityDecode($media_landmarks_item['name']).'</div></a>';
		if($n_results>0){
			$str_data .= '<div class="hotelreview_txt1"><u>'. $n_results .' REVIEWS</u></div>';
		}
		$str_data .= '</div>
		<div class="hotelsreviews_pic"><a href="'.$page_link.'" target="_blank"><img src="'.$image_pic.'" width="175" height="109"></div></a>';
		if($user_is_logged==1){
			$str_data .= '<div class="hotelsplus"></div>';
		}
		$str_data .= '</div>';

	}
	
	$Result['data'] = $str_data;
	echo json_encode( $Result );