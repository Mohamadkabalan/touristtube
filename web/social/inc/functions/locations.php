<?php
	/**
	 * the functionality that deals with the cms_locations tables
	 * @package location
	 */

	/**
	 * Calculates the difference between 2 locations in meters
	 * @param double $lat1 first location latitude
	 * @param double $lon1 first location longitude
	 * @param double $lat2 second location latitude
	 * @param double $lon2 second location longitude
	 * @return integer the distance in meters
	 */
	function LocationDiff($lat1,$lon1,$lat2,$lon2){
		$R = 6371; // km
		$dLat = deg2rad($lat2-$lat1);
		$dLon = deg2rad($lon2-$lon1);
		$lat1_rad = deg2rad($lat1);
		$lat2_rad = deg2rad($lat2);

		$a = sin($dLat/2) * sin($dLat/2) + sin($dLon/2) * sin($dLon/2) * cos($lat1) * cos($lat2);
		$c = 2 * atan2(sqrt($a), sqrt(1-$a)); 
		$d = $R * $c;
		$dm = intval($d * 1000);
		return $dm;
	}
	
	/**
	 * primitive function to clean the tubers table 
	 */
	function tubersTableClean(){
            global $dbConn;
            $params = array();  
            $max_minutes = 5;
            $query = "DELETE FROM cms_tubers WHERE TIME_TO_SEC((TIMEDIFF(NOW(),log_ts)) > :Max_minutes ";
            $params[] = array(  "key" => ":Max_minutes",
                                "value" =>$max_minutes*60);

            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res = $select->execute();
            return $res;
	}

        /**
	 * gets all tubers within a radius of a location
	 * @param double $latitude the latitude of the location
	 * @param double $longitude the longitude of the location
	 * @param integer $radius the radius to search in
	 * return array the array of tubers within the specified space
	 */
	function tubersGetByLocation($latitude, $longitude, $radius){//need to be changed
            


        global $dbConn;
        $params = array(); 
//            $query = "SELECT * FROM cms_tubers WHERE LocationDiff(latitude,longitude,$latitude,$longitude) < $radius ";
//            $ret = db_query($query);
//            if( !$ret || db_num_rows($ret) == 0 ) return array();
//
//            $ret_arr = array();
//            while($row = db_fetch_array($ret)){
//                    $ret_arr[] = $row;
//            }
//            return $ret_arr;
            $query = "SELECT * FROM cms_tubers WHERE LocationDiff(latitude,longitude,:Latitude,:Longitude) < :Radius ";
            $params[] = array(  "key" => ":Latitude",
                                "value" =>$latitude);
            $params[] = array(  "key" => ":Longitude",
                                "value" =>$longitude);
            $params[] = array(  "key" => ":Radius",
                                "value" =>$radius);
            
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $select->execute();

            $ret    = $select->rowCount();
            
            
            if( !$select || $ret == 0 ) return array();

            $ret_arr = $select->fetchAll(PDO::FETCH_ASSOC);
            return $ret_arr;


	}
	
	/**
	 * searches for tubers based on certain criteria. options include:<br/>
	 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
	 * <b>page</b>: the number of pages to skip. default 0<br/>
	 * <b>latitude</b> latitude of location for the spatial search. default null<br/>
	 * <b>longitude</b> longitude of location for the spatial search. default null<br/>
	 * <b>radius</b> radius of the spatial search in meters. default 1000<br/>
	 * <b>country</b> 2 letter country code to search in. default null<br/>
	 * <b>gender</b> (M)ale, (F)emale. default null<br/>
	 * <b>age</b> integer to specify age. default null<br/>
	 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id'<br/>
	 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
	 * @param array $srch_options the array of options
	 * @return array a list of cms_users records
	 */
	function tuberSearch($srch_options){
//  Changed by Anthony Malak 11-05-2015 to PDO database
//  <start>
            global $dbConn;
            $params  = array(); 
            $params2 = array();
		
		$default_opts = array(
			'limit' => 6,
			'page' => 0,
			'orderby' => 'id',
			'order' => 'a',
			'latitude' => null,
			'longitude' => null,
			'radius' => 1000,
			'country' => null,
			'gender' => null,
			'age' => null
		);

		$options = array_merge($default_opts, $srch_options);
		
		$latitude = doubleval($options['latitude']);
		$longitude = doubleval($options['longitude']);
		$radius = doubleval($options['radius']);
		
		$nlimit = intval($options['limit']);
		$skip = intval($options['page']) * $nlimit;
		
		$where = '';
		
		if( !is_null($options['country']) ){
			if($where != '') $where .= " AND ";
//			$where = " U.YourCountry='{$options['country']}' ";
			$where = " U.YourCountry=:Country ";
                        $params[] = array(  "key" => ":Country",
                                            "value" =>$options['country']);
		}
		if( !is_null($options['gender']) ){
			if($where != '') $where .= " AND ";
//			$where = " U.gender='{$options['gender']}' ";
			$where = " U.gender=:Gender ";
                        $params[] = array(  "key" => ":Gender",
                                            "value" =>$options['gender']);
		}
		if( !is_null($options['age']) ){
			if($where != '') $where .= " AND ";
//			$where = " CAST( DATEDIFF(NOW(),U.YourBday)/365.25 AS SIGNED ) IN ( {$options['age']} ) ";
			$where = " CAST( DATEDIFF(NOW(),U.YourBday)/365.25 AS SIGNED ) IN ( :Age ) ";
                        $params[] = array(  "key" => ":Age",
                                            "value" =>$options['age']);
		}
		if( !is_null($options['latitude']) && !is_null($options['longitude']) && !is_null($options['radius']) ){
			if($where != '') $where .= " AND ";
//			$where .= " LocationDiff(latitude,longitude,$latitude,$longitude) < $radius ";
			$where .= " LocationDiff(latitude,longitude,:Latitude,:Longitude) < :Radius ";
                        $params[] = array(  "key" => ":Latitude",
                                            "value" =>$latitude);
                        $params[] = array(  "key" => ":Longitude",
                                            "value" =>$longitude);
                        $params[] = array(  "key" => ":Radius",
                                            "value" =>$radius);
		}
                if( userIsLogged() ) {            
                    $searcher_id = userGetID();
                    $friends = userGetFreindList($searcher_id);

                    $friends_ids = array($searcher_id);
                    foreach($friends as $freind){
                        $friends_ids[] = $freind['id'];
                    }

                    $extended_friends_list = userGetExtendedFriendList($friends_ids);
                    $extended_friends = array();
                    foreach($extended_friends_list as $entended_friend_row){
                        $extended_friends[] = $entended_friend_row['id'];
                    }
                    foreach($friends_ids as $freind){
                        if( !in_array($freind, $extended_friends) ){
                            $extended_friends[] = $freind;
                        }
                    }
                    if(count($friends_ids)!=0){
                        if($where != '') $where .= " AND ";
                        $public = USER_PRIVACY_PUBLIC;
                        $private = USER_PRIVACY_PRIVATE;
                        $selected = USER_PRIVACY_SELECTED;
                        $community = USER_PRIVACY_COMMUNITY;
                        $privacy_where = '';

                        $where .= "CASE";
                        $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=".SOCIAL_ENTITY_USER." AND PR.published=1 LIMIT 1 ) THEN 1";
//                        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=".SOCIAL_ENTITY_USER." AND PR.published=1 AND PR.kind_type='$public' LIMIT 1 ) THEN 1";
                        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=".SOCIAL_ENTITY_USER." AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) THEN 1";
//                        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=".SOCIAL_ENTITY_USER." AND PR.published=1 AND PR.user_id = '$searcher_id' LIMIT 1 ) THEN 1";
                        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=".SOCIAL_ENTITY_USER." AND PR.published=1 AND PR.user_id =:Searcher_id LIMIT 1 ) THEN 1";
                        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=".SOCIAL_ENTITY_USER." AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) THEN 1";
                        
                        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=".SOCIAL_ENTITY_USER." AND PR.published=1 AND PR.kind_type=:Private AND PR.user_id=:Searcher_id LIMIT 1 ) THEN 1";
                        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=".SOCIAL_ENTITY_USER." AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$searcher_id' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) THEN 1";
                        
                        $where .= " ELSE 0 END ";
                        $params[] = array(  "key" => ":Public", "value" =>$public);
                        $params[] = array(  "key" => ":Searcher_id", "value" =>$searcher_id);
                        $params[] = array(  "key" => ":Private", "value" =>$private);
                    }            
                }else{
                    $public = USER_PRIVACY_PUBLIC;
                    if($where != '') $where .= ' AND ';
                    $where .= "CASE";
//                    $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=".SOCIAL_ENTITY_USER." AND PR.published=1 AND PR.kind_type='$public' LIMIT 1 ) THEN 1";
                    $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=".SOCIAL_ENTITY_USER." AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) THEN 1";
                    $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=".SOCIAL_ENTITY_USER." AND PR.published=1  LIMIT 1 ) THEN 1";
                    $where .= " ELSE 0 END ";
                    $params[] = array(  "key" => ":Public",
                                        "value" =>$public);
                }
		
		if($where != '') $where = " WHERE $where ";
		
		$orderby = $options['orderby'];
		$order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
	
//		$query = "SELECT
//						U.*,T.longitude,T.latitude
//					FROM
//						cms_users AS U
//						INNER JOIN cms_tubers AS T ON T.user_id = U.id
//					$where						
//					ORDER BY $orderby $order LIMIT $skip, $nlimit";
		$query = "SELECT
						U.*,T.longitude,T.latitude
					FROM
						cms_users AS U
						INNER JOIN cms_tubers AS T ON T.user_id = U.id
					$where						
					ORDER BY $orderby $order LIMIT :Skip, :Nlimit";
		
//		$ret = db_query($query);
                $params[] = array(  "key" => ":Skip",
                                    "value" =>$skip,
                                    "type" =>"::PARAM_INT");
                $params[] = array(  "key" => ":Nlimit",
                                    "value" =>$nlimit,
                                    "type" =>"::PARAM_INT");
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res    = $select->execute();

                $ret    = $select->rowCount();
		$tubers = array();
                $tubers = $select->fetchAll();
//		while($row = db_fetch_array($ret))
//			$tubers[] = $row;	

		return $tubers;
	}

	/**
	 * saves a user's reviews of a location
	 * @param integer $location_id the cms_locations id
	 * @param integer $user_id the sms_users id
	 * @param string $review the review text
	 * @param integer $rating the rating
	 * @return array|boolean new stats if success|false if fail
	 */
	function locationReviewSet($location_id, $user_id, $review, $rating){
		
		if( socialRateAdd($user_id, $location_id, SOCIAL_ENTITY_LOCATION, $rating, null) && socialCommentAdd($user_id, $location_id, SOCIAL_ENTITY_LOCATION, $review, 0)) return true;
		else return false;
	}
	
	/**
	 * gets the location review for a user
	 * @param integer $location_id the cms_locations id
	 * @param integer $user_id the sms_users id
	 * @return false|array the review virtual record record or false if not found
	 */
	function locationReviewGet($location_id,$user_id){
		
		$row1 = socialRateGet($user_id, $location_id, SOCIAL_ENTITY_LOCATION);
		if( !$row1 ) return false;
		
		$row2 = socialCommentsGet(array('user_id' => $user_id, 'entity_id' => $location_id , 'entity_type' => SOCIAL_ENTITY_LOCATION ));
		$row2 = $row2[0];
		
		$review = array('rating' => $row1['rating_value'] , 'review' => $row2['comment_text']  );
		return $review;
	}
	
	/**
	 * find a location review
	 * @param integer $location_id cms_locations id
	 * @param integer $which how many to skip
	 * @return false|array the review virrtual record record or false if not found
	 */
	function locationReviewFind($location_id,$which){
		$review = socialCommentsGet(array('entity_id' => $location_id , 'entity_type' => SOCIAL_ENTITY_LOCATION , 'limit' => 1 , 'page' => $which ));
		$rate = socialRateGet($review['user_id'], $location_id, SOCIAL_ENTITY_LOCATION);
		$review = array('rating' => $rate['rating_value'] , 'review' => $review['comment_text']  );
		return $review;
	}
	
	/**
	 * gets the ammount of reviews for a location
	 * @param integer $location_id cms_locations id
	 * @return false|integer the number of reviews or false if not found
	 */
	function locationReviewCount($location_id){
		return socialCommentsGet(array('entity_id' => $location_id , 'entity_type' => SOCIAL_ENTITY_LOCATION , 'n_results' => true ));
	}
	
	/**
	 * gets a location given its id
	 * @param integer $location_id the cms_locations' id
	 * @return array|false the cms_locations record or false if not found
	 */
	function locationGet($location_id){


            global $dbConn;
            $params = array(); 
//            $query = "SELECT * FROM cms_locations WHERE id='$location_id'";
//            $res = db_query($query);
//            if( !$res || (db_num_rows($res)==0) ){
//                    return false;
//            }else{
//                    $row = db_fetch_assoc($res);
//
//                    return $row;
//            }
            $query = "SELECT * FROM cms_locations WHERE id=:Location_id";
            $params[] = array(  "key" => ":Location_id",
                                "value" =>$location_id);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();

            $ret    = $select->rowCount();
            
            if( !$res || ($ret==0) ){
                    return false;
            }else{
                    $row = $select->fetch(PDO::FETCH_ASSOC);
                    return $row;
            }


	}
	
	/**
	 * gets a location's url
	 * @param array $loc_rec the cms_locations record
	 * @return string the location's url 
	 */
	function locationGetURL($loc_rec){
		$link = '';
		switch($loc_rec['category_id']){
			case '1':
				$link = 'restaurant/';
				break;
			case '2':
				$link = 'hotel/';
				break;
			default:
				$link = 'location/';
				break;
		}
		
		$link .= seoEncodeURL($loc_rec['name']);
		$link = ReturnLink($link);
		return $link;
	}
	
	/**
	 * increments the number of views for a location
	 * @param integer $location_id the cms_locations id
	 * @return boolean true|false if success|fail
	 */
	function locationIncViews($location_id){
            global $dbConn;
            $params = array();
            $query = "UPDATE cms_locations SET nb_views=nb_views+1 WHERE id=:Location_id";
            $params[] = array(  "key" => ":Location_id",
                                "value" =>$location_id);
            $update = $dbConn->prepare($query);
            PDO_BIND_PARAM($update,$params);
            $res = $update->execute();
            if( $res ){
                    return false;
            }else{
                    return true;
            }
	}
		
	/**
	 * searches for locations based on certain criteria. options include:<br/>
	 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
	 * <b>page</b>: the number of pages to skip. default 0<br/>
	 * <b>latitude</b> latitude of location for the spatial search. default null<br/>
	 * <b>longitude</b> longitude of location for the spatial search.default null<br/>
	 * <b>radius</b> radius of the spatial search in meters. default 1000<br/>
	 * <b>dist_alg</b>: the distance algorithm to use (s)quare [faster], or (c)ircular [slower]. default is 's'<br/>
	 * <b>type</b> what kinds of locations to search for (r)estaurant, (h)otel, a(c)tivity, or (a)ll or comma separated string. default (a)<br/>
	 * <b>search_string</b> the search string (searches in the <b>name</b> column). default null<br/>
	 * <b>country</b> 2 letter country code to search in. default null<br/>
	 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id'<br/>
	 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
	 * <b>n_results</b>: gets the number of results rather than the rows. default false.
	 * @param array $srch_options the array of options
	 * @return array a list of cms_locations records
	 */
	function locationSearch($srch_options){
		
            global $dbConn;
            $params = array(); 
		$default_opts = array(
			'limit' => 6,
			'page' => 0,
			'type' => 'a',
			'orderby' => 'id',
			'order' => 'a',
			'latitude' => null,
			'longitude' => null,
			'radius' => 1000,
			'dist_alg' => 's',
			'search_string' => null,
			'n_results' => false
		);

		$options = array_merge($default_opts, $srch_options);
		
		/* no trends from search queries
		if($options['search_string'] !== null)
			queryAdd($options['search_string']);
		*/
		
		$nlimit = intval($options['limit']);
		$skip = intval($options['page']) * $nlimit;
	
		$where = '';
		
		if( $options['type'] != 'a' ){
			$types = explode(',',$options['type']);
			if(!in_array('a',$types)){
				$types_or = array();
				foreach($types as $type){
					switch($type){
						case 'h':
							$types_or[] = " category_id='2' ";
							break;
						case 'r':
							$types_or[] = " category_id='1' ";
							break;
						case 'c':
							$types_or[] = " category_id='3' ";
							break;
						default:
							break;
					}
				}
				if ($where != '' ) $where .= ' AND ';
//				$where .= '(' . implode(' OR ', $types_or) . ')';
				$where .= "(:Types_or)";
                                $params[] = array(  "key" => ":Types_or",
                                                    "value" =>implode(' OR ', $types_or));
			}
		}
		
		if( !is_null($options['latitude']) && !is_null($options['longitude']) && !is_null($options['radius']) ){
			$lat = doubleval($options['latitude']);
			$long = doubleval($options['longitude']);
			$radius = intval($options['radius']);

			if($where != '') $where .= ' AND ';

			if( $options['dist_alg'] == 's'){
				//the 1 latitude degree ~= 110km, 1 degree longitude = 110km at equater, 78 at 45 degrees, 0 at pole
				//for longitude at [33,35] square 0.1 => 14km, 0.01 => 1.4km, 0.001 => 140m so. good approx for a degree square is approx 140km
				//use formula for longitude
				$long_rad = deg2rad($long);
				$c = 40075;

				$lat_conv = doubleval(110000.0);
				$long_conv = (1000 * $c * cos($long_rad))/360;

				$diff_lat = round($radius/$lat_conv, 3);
				$diff_long = round($radius/$long_conv, 3);
				
				$diff_long *= 2;

				
//				$where .= " squareLocationDiff(latitude,longitude,$lat,$long,$diff_lat,$diff_long) ";
				$where .= " squareLocationDiff(latitude,longitude,:Lat,:Long,:Diff_lat,:Diff_long) ";
                                $params[] = array(  "key" => ":Lat",
                                                    "value" =>$lat);
                                $params[] = array(  "key" => ":Long",
                                                    "value" =>$long);
                                $params[] = array(  "key" => ":Diff_lat",
                                                    "value" =>$diff_lat);
                                $params[] = array(  "key" => ":Diff_long",
                                                    "value" =>$diff_long);
			}else{
				$where .= " LocationDIff(latitude,longitude,$lat,$long) <= $radius ";
				$where .= " LocationDIff(latitude,longitude,:Lat,:Long) <= :Radius ";
                                $params[] = array(  "key" => ":Lat",
                                                    "value" =>$lat);
                                $params[] = array(  "key" => ":Long",
                                                    "value" =>$long);
                                $params[] = array(  "key" => ":Radius",
                                                    "value" =>$radius);
			}
		}
		
		if( !is_null($options['search_string']) ){
			$search_strings = explode(' ',$options['search_string']);
			
			$sub_where = array();

			foreach($search_strings as $in_search_string){

				$search_string = trim(strtolower($in_search_string));

				if($search_string == '') continue;

				$sub_where[] = " LOWER(name) LIKE '%{$search_string}%' ";
			}

			if( count($sub_where) != 0 ){
				if($where != '') $where .= ' AND ';
//				$sub_where = '(' . implode(' AND ', $sub_where) . ')';
				$sub_where = '(:Sub_where)';
                                $params[] = array(  "key" => ":Sub_where",
                                                    "value" =>implode(' AND ', $sub_where));
				$where .= $sub_where;
			}
		}

		$orderby = $options['orderby'];
		$order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
	
		if( $options['n_results'] == false ){
//			$query = "SELECT * FROM `cms_locations` WHERE $where ORDER BY $orderby $order LIMIT $skip, $nlimit";
			$query = "SELECT * FROM `cms_locations` WHERE $where ORDER BY $orderby $order LIMIT :Skip, :Nlimit";
                        $params[] = array(  "key" => ":Skip",
                                            "value" =>$skip,
                                            "type" =>"::PARAM_INT");
                        $params[] = array(  "key" => ":Nlimit",
                                            "value" =>$nlimit,
                                            "type" =>"::PARAM_INT");
//			$ret = db_query($query);
                        $select = $dbConn->prepare($query);
                        PDO_BIND_PARAM($select,$params);
                        $res    = $select->execute();

                        $ret    = $select->rowCount();
			$media = array();
//			while($row = db_fetch_array($ret))
//				$media[] = $row;	
                        $media = $select->fetchAll();
			return $media;
		}else{
			$query = "SELECT COUNT(id) FROM `cms_locations` WHERE $where";
//			$ret = db_query($query);
//			$row = db_fetch_array($ret);
                        $select = $dbConn->prepare($query);
                        PDO_BIND_PARAM($select,$params);
                        $res    = $select->execute();

                        $ret    = $select->rowCount();
                        $row = $select->fetch();
			$n_results = $row[0];
			return $n_results;
		}
		

	}
	
	/**
	 * find sthe cms_locations reecord given its name
	 * @param string $name 
	 * @return array|false the cms_locations record or false if now found
	 */
	function locationGetByName($name){
//  Changed by Anthony Malak 20-04-2015 to PDO database

//		$query = "SELECT * FROM cms_locations WHERE name='$name'";
//		$res = db_query($query);
//		if(!$res || (db_num_rows($res)==0) ){
//			return false;
//		}else{
//			$row = db_fetch_array($res);
//			return $row;
//		}
            global $dbConn;
            $params = array();  
		$query = "SELECT * FROM cms_locations WHERE name=:Name";
                $params[] = array(  "key" => ":Name",
                                    "value" =>$name);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res = $select->execute();

                $ret    = $select->rowCount();
		if(!$res || ($ret==0) ){
			return false;
		}else{
			$row = $select->fetch();
			return $row;
		}
//  Changed by Anthony Malak 20-04-2015 to PDO database

	}
	
	/**
	 * gets the locations statistics
	 * @param array $loc_arr the location record
	 * @return array all the location statistics
	 */
	function locationGetStatistics($_loc_arr){
		
		$ret = array();
		$loc_arr = $_loc_arr;
		if( !is_array($loc_arr) ){
			$loc_arr = locationGet($loc_arr);
		}
		
		$ret['nb_views'] = $loc_arr['nb_views'];
		$ret['like_value'] = $loc_arr['like_value'];
		$ret['nb_ratings'] = $loc_arr['nb_ratings'];
		$ret['rating'] = $loc_arr['rating'];
		
		return $ret;
	}
	
	/**
	* Return the number of coments per page on the video page
	* @return int the number of comments per page
	*/
	function LocationGetCommentsPerPage(){
		return 5;
	}
	
	/**
	 * gets comments.
	 * @param integer $location_id which location to get the reviews
	 * @param integer $nlimit the limit of review to get
	 * @param integer $page how many pages of reviews to skip
	 * @param string $sortby cms_locations_review field to sort by
	 * @param string $sort 'ASC', 'DESC'
	 * @return array|boolean a set of cms_locations_review records or false if none found
	 */
	function LocationGetComments($location_id, $nlimit, $page, $sortby, $sort ){
            $skip = $nlimit * $page;
            global $dbConn;
            $params = array(); 
            $query = "SELECT CR.rating_value as rating, CM.comment_text AS review, CM.comment_date AS review_ts, U.YourUserName AS username
                        FROM cms_social_comments AS CM
                            INNER JOIN cms_social_ratings AS CR ON CM.user_id=CR.user_id AND CM.entity_id=CR.entity_id AND CR.rate_type=0 
                            INNER JOIN cms_users AS U ON CM.user_id=U.id
                        WHERE CM.entity_id=:Location_id AND CM.published='1'
                        ORDER BY $sortby $sort LIMIT :Skip,:Nlimit";
		
            $params[] = array( "key" => ":Location_id", "value" =>$location_id);
            $params[] = array( "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
            $params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res = $select->execute();
            $ret = $select->rowCount();
            if ( $res && $ret>0  ){
                $res_ar = $select->fetchAll(PDO::FETCH_ASSOC);
                return $res_ar;
            }else{
                return false;
            }
	}
	
	/**
	 * returns the relative path to the qr code
	 * @param array $location the cms_locations record of which we want the qrcode
	 */
	function LocationQRCode($location){
		
		$filename = "images/qrcodes/{$location['id']}.png";
		
		if( file_exists($filename) ) return $filename;
		
		switch($location['category_id']){
			case 2:
				$cat = 'hotel';
				break;
			case 1:
				$cat = 'restaurant';
				break;
			default:
				$cat = 'location';
				break;
		}
		
		$url = "https://www.touristtube.com/$cat/" . seoEncodeURL($location['name']);
		$data = $url;
		$matrixPointSize = 4; //1 to 10
		$errorCorrectionLevel = 'L'; //array('L','M','Q','H')
		
        QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2); 
		
		return $filename;
	}
	
	/**
	 * like or dislike a location
	 * @param integer $location_id the cms_locations id
	 * @param integer $user_id the cms_users id
	 * @param integer $like_val 1, or -1
	 */
	function locationLike($location_id,$user_id,$like_val){
		
		if( socialLiked($user_id, $location_id, SOCIAL_ENTITY_LOCATION) ){
			return socialLikeEdit($user_id, $location_id, SOCIAL_ENTITY_LOCATION, $like_val);
		}else{
			return socialLikeAdd($user_id, $location_id, SOCIAL_ENTITY_LOCATION, $like_val,null);
		}
		
	}
