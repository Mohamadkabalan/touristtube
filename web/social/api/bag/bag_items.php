<?php
    //ini_set("display_errors", 1);
    //ini_set("error_reporting", E_ALL);
    $expath = "../";
    $bootOptions = array ( "loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);
    include_once($expath."heart.php");
//    $user_id = mobileIsLogged($_REQUEST['S']);
    $submit_post_get = array_merge($request->query->all(),$request->request->all());
	
	$uid = 0;
	if (isset($submit_post_get['S']))
		$uid = $submit_post_get['S'];
	
    $user_id = mobileIsLogged($uid);
	
    // if( !$user_id ) die();
	if (!$user_id)
	{
		echo json_encode(array());
		
		exit;
	}
	
	
//    $user_id = $_SESSION['id'];
//    if(!isset($user_id)){ 
//        echo json_encode("No session");
//        exit();
//    }
    $link = 'media/discover/';
    $id = intval($submit_post_get['bag_id']);
//    if(isset($_REQUEST['id']))
//    {
//        $id = xss_sanitize($_REQUEST['id']);
//    }

	$items_array = array();
	
    // $Result = array();
    $bag_info = userBagInfo($user_id, $id);
	
    if($bag_info)
	{
        $datas = userBagItemsList($user_id,$bag_info['id']);
        if($datas){
            $Result['id'] = $bag_info['id'];
            $Result['name'] = $bag_info['name'];
            
            global $dbConn;
			
			if ($datas)
				foreach ($datas as $each)
				{
				   $type = $each['type'];
				   $entity_id = $each['item_id'];
				   $locationText ='';
				   $dimagepath='';
				   $reference = '';
				   $checkIndate = '';
				   $checkOutdate = '';
				   $checkIn = '';
				   $checkOut = '';
				   $bag_object=array();
				   $params = array();
				   $params[] = array(  "key" => ":Item_id", "value" =>$entity_id);
				   
				   if($type == SOCIAL_ENTITY_HOTEL)
				   {
					   $type_val='hotel';
						$query_hotels = "SELECT $each[0] as bag,id , filename as img, location , hotel_id FROM `cms_hotel_image` WHERE hotel_id = :Item_id and filename != '' ORDER BY default_pic DESC LIMIT 1";
						$select14 = $dbConn->prepare($query_hotels);
						PDO_BIND_PARAM($select14,$params);
						$ret_hotels    = $select14->execute();
						$rows_res = $select14->fetch();
						$rows_res['bag'] = $each[0];
						$media_hotels = $rows_res;

						$query = "SELECT h.*, '' as dimage FROM `cms_hotel` as h WHERE h.id = :Item_id LIMIT 1";
						$select15 = $dbConn->prepare($query);
						PDO_BIND_PARAM($select15,$params);
						$retH    = $select15->execute();
						$hotels = $select15->fetch();

						$entity_name = $hotels['name'];
						$longitude = $hotels['longitude'];
						$latitude = $hotels['latitude'];
						if ($locationText == '') $locationText = $hotels['street'];
						if ($hotels['city_id'] != '0' && $locationText == '') {
							$city_array = worldcitiespopInfo(intval($hotels['city_id']));
							$city_name = $city_array['name'];
							if ($city_name != '') {
								$locationText .= $city_name;
							}
							$state_array = worldStateInfo($city_array['country_code'], $city_array['state_code']);
							$state_name = $state_array['state_name'];
							if ($state_name != '') {
								$locationText .= ', ' . $state_name;
							}
							$country_array = countryGetInfo($city_array['country_code']);
							$country_name = $country_array['name'];
							if ($country_name != '') {
								$locationText .= ', ' . $country_name;
							}
						}
						$cityName = $locationText;          
						if( $media_hotels['img'] && $media_hotels['img']!=''){
							$dimagepath='media/hotels/' . $media_hotels['hotel_id'] . '/' . $media_hotels['location'] . '/';
							$dimage =  $media_hotels['img'];
						}else{
							$dimage = 'media/images/hotel-icon-image3.jpg';
						}
						$bag =  $media_hotels['bag'];
						$icon =  'media/images/placestostay_icon.png';
						
						$queryN = "SELECT * FROM `hotel_reservation` WHERE hotel_id = :Item_id AND user_id=:userId AND reservation_status IN ('Confirmed','Modified') ORDER BY id DESC";
						
						$paramsN = array();
						$paramsN[] = array(  "key" => ":Item_id", "value" =>$entity_id);
						$paramsN[] = array(  "key" => ":userId", "value" =>$user_id);
						$select15N = $dbConn->prepare($queryN);
						PDO_BIND_PARAM($select15N,$paramsN);
						$retHN = $select15N->execute();
						$ret = $select15N->rowCount();
						if($retHN && ($ret!=0) ){
							$hotelReservationList = $select15N->fetchAll();
							if($dimagepath!=''){
								$bag_object['img']= createBagItemThumbs($dimage,$dimagepath,0,0,700,399);
							}else{
								$bag_object['img']= $dimage;
							}
							$bag_object['id'] = $each['id'];
							$bag_object['entity_id'] = $entity_id;
							$bag_object['entity_name'] = $entity_name;
							$titled = cleanTitleData($entity_name);
							$bag_object['entity_name_clean'] =  str_replace('-', '+', $titled);
							$bag_object['entity_type'] = $type;
							$bag_object['icon']= $icon;
							$bag_object['address']= $cityName;
							
							if ($hotelReservationList)
								foreach($hotelReservationList as $reservationData)
								{
									$bag_object['checkIndate'] = date("d",strtotime($reservationData['from_date']));
									$bag_object['checkOutdate'] = date("d",strtotime($reservationData['to_date']));
									$bag_object['checkIn'] = date("M Y l",strtotime($reservationData['from_date']));
									$bag_object['checkOut'] = date("M Y l",strtotime($reservationData['to_date']));
									$bag_object['reference'] = $reservationData['reference'];
									$items_array[] = $bag_object;
								}
								
							continue;
						}
				   }
				   else if($type == SOCIAL_ENTITY_RESTAURANT)
				   {
                                       continue;
					   $type_val='restaurant';
					   $query_restaurants = "SELECT $each[0] as bag, id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = :Item_id and filename != '' ORDER BY default_pic DESC LIMIT 1";
						$select11 = $dbConn->prepare($query_restaurants);
						PDO_BIND_PARAM($select11,$params);
						$ret_restaurants    = $select11->execute();
						$rows_res = $select11->fetch();
						$rows_res['bag'] = $each[0];
						$media_resto = $rows_res;
						$query = "SELECT h.*, d.image as dimage FROM `global_restaurants` as h LEFT JOIN cms_thingstodo_details as d on d.entity_id=:Item_id AND d.entity_type=".SOCIAL_ENTITY_RESTAURANT." WHERE h.id = :Item_id LIMIT 1";
						$select12 = $dbConn->prepare($query);
						PDO_BIND_PARAM($select12,$params);
						$retR    = $select12->execute();
						$restaurants = $select12->fetch();

						$entity_name = $restaurants['name'];
						$longitude = $restaurants['longitude'];
						$latitude = $restaurants['latitude'];
						if ( intval($restaurants['city_id']) >0) {
							$city_array = worldcitiespopInfo(intval($restaurants['city_id']));
							$city_name = $city_array['name'];
							if ($city_name != '') {
								$locationText .= $city_name;
							}
							$state_array = worldStateInfo($city_array['country_code'], $city_array['state_code']);
							$state_name = $state_array['state_name'];
							if ($state_name != '') {
								$locationText .= ', ' . $state_name;
							}
							$country_array = countryGetInfo($city_array['country_code']);
							$country_name = $country_array['name'];
							if ($country_name != '') {
								$locationText .= ', ' . $country_name;
							}
						} else {
							$city_name = $restaurants['locality'];
							if ($city_name != '') {
								$locationText .= $city_name;
							}
							$region_name = $restaurants['region'];
							$admin_region_name = $restaurants['admin_region'];
							if ($region_name != '' && $region_name !=$city_name ) {
								$locationText .= ', ' . $region_name;
							}else if ($admin_region_name != '' && $admin_region_name !=$city_name) {
								$locationText .= ', ' . $admin_region_name;
							}
							$country_cd = $restaurants['country'];
							if ($country_cd != '') {
								$country_array = countryGetInfo($country_cd);
								$country_name = $country_array['name'];
								if ($country_name != '') {
									$locationText .= ', ' . $country_name;
								}
							}
						}
						if ($locationText == '') {
							$locationText = $restaurants['address'];
						}else if ($restaurants['address'] != '') {
							$locationText .= '<br>'.$restaurants['address'];
						}
						$cityName = $locationText;
						if( !is_null($restaurants['dimage']) && $restaurants['dimage'] && $restaurants['dimage']!='' ){
							$dimagepath="media/thingstodo/";
							$dimage = $restaurants['dimage'];
						}else if( $media_resto['img'] && $media_resto['img']!=''){
							$dimagepath='media/discover/';
							$dimage = $media_resto['img'];
						}else{
							$dimage = 'media/images/restaurant-icon3.jpg';
						}    
						$bag = $media_resto['bag'];
						$icon = 'media/images/food-eat-icon.png';
				   }
				   else if($type == SOCIAL_ENTITY_LANDMARK)
				   {
					   $type_val='thingstodo';
					   $query_poi = "SELECT $each[0] as bag, id , filename as img , poi_id FROM `discover_poi_images` WHERE poi_id = :Item_id ORDER BY default_pic DESC LIMIT 1";
						$select17 = $dbConn->prepare($query_poi);
						PDO_BIND_PARAM($select17,$params);
						$ret_poi    = $select17->execute();
						$rows_res = $select17->fetch();
						$rows_res['bag'] = $each[0];
						$media_poi = $rows_res;

						$query = "SELECT h.*, d.image as dimage FROM `discover_poi` as h LEFT JOIN cms_thingstodo_details as d on d.entity_id=:Item_id AND d.entity_type=".SOCIAL_ENTITY_LANDMARK." WHERE h.id = :Item_id LIMIT 1";
						$select18 = $dbConn->prepare($query);
						PDO_BIND_PARAM($select18,$params);
						$retP    = $select18->execute();
						$poi = $select18->fetch();

						$entity_name = $poi['name'];
						$longitude = $poi['longitude'];
						$latitude = $poi['latitude'];
						$poi_cityName = $poi['cityName'];
						if( !is_null($poi['dimage']) && $poi['dimage'] && $poi['dimage']!='' ){
							$dimagepath="media/thingstodo/";
							$dimage = $poi['dimage'];
						}else if( $media_poi['img'] && $media_poi['img']!=''){
							$dimagepath='media/discover/';
							$dimage =  $media_poi['img'];
						}else{
							$dimage = 'media/images/landmark-icon3.jpg';
						}

						if ($poi['city_id'] != '0') {
							$city_array = worldcitiespopInfo(intval($poi['city_id']));
							$city_name = $city_array['name'];
							if ($city_name != '') {
								$locationText .= $city_name;
							}
							$state_array = worldStateInfo($city_array['country_code'], $city_array['state_code']);
							$state_name = $state_array['state_name'];
							if ($state_name != '') {
								$locationText .= ', ' . $state_name;
							}
							$country_array = countryGetInfo($city_array['country_code']);
							$country_name = $country_array['name'];
							if ($country_name != '') {
								$locationText .= ', ' . $country_name;
							}
						} else {
							$locationText = $poi['address'];
						}
						if ($locationText == '') {
							$locationText = $poi['address'];
						}else if ($poi['address'] != '') {
							$locationText .= '<br>'.$media_poi['address'];
						}
						$cityName =  $locationText;
						$bag = $media_poi['bag'];
						$icon =  'media/images/thingstodo-icon.png';
				   }
				   else if($type == SOCIAL_ENTITY_EVENTS)
				   {
						$arrEventDetails   = channelEventInfo($entity_id,-1);
						$arrEventDetails['bag'] = $each['id'];
						$event_item = $arrEventDetails;

						$icon =  'media/images/event-icon.png';
						if($event_item['photo'] != ''){
							$dimagepath = 'media/channel/' . $event_item['channelid'].'/event/';
							$dimage = $event_item['photo'];
						}else{
							$dimage = 'media/images/event-icon3.jpg';
						}
						$entity_name = $event_item['name'];

						$cityName= $event_item['location'];
						$bag = $event_item['bag'];
						$bag_object['channel_id'] = $event_item['channelid'];
				   }
				   else
				   {
					   continue;
				   }
					
					if($dimagepath)
					{
						$bag_object['img']= createBagItemThumbs($dimage,$dimagepath,0,0,700,399);
					}
					else
					{
						$bag_object['img']= $dimage;
					}
					
					$bag_object['id'] = $each['id'];
					$bag_object['entity_id'] = $entity_id;
					$bag_object['entity_name'] = $entity_name;
					$titled = cleanTitleData($entity_name);
					$bag_object['entity_name_clean'] =  str_replace('-', '+', $titled);
					$bag_object['entity_type'] = $type;
					$bag_object['icon']= $icon;
					$bag_object['address']= $cityName;
					$bag_object['checkIn'] = $checkIn;
					$bag_object['checkOut'] = $checkOut;
					$bag_object['checkIndate'] = $checkIndate;
					$bag_object['checkOutdate'] = $checkOutdate;
					$bag_object['reference'] = $reference;
					$items_array[] = $bag_object;
				}
        }
    }
	
    echo json_encode($items_array);
