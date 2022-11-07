<?php
	
	$path = '../';
	$bootOptions = array("loadDb" => 1 , 'requireLogin' => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
	include_once ( $path . "inc/functions/videos.php" );
	include_once ( $path . "inc/functions/users.php" );
    
	//kill previous calls
	if(isset($_SESSION['conn_id'])){
		db_connection_kill($_SESSION['conn_id']);
		unset($_SESSION['conn_id']);
	}
	
	$_SESSION['conn_id'] = db_connection_id();
	session_write_close();
	
//	$catgeory = isset($_GET['category']) ?  $_GET['category'] : null;
//	$terms = isset($_GET['term']) ? db_sanitize($_GET['term']) : null;
	$catgeory = $request->query->get('category',null);
	$terms = $request->query->get('term',null);
	
	if($catgeory == null) die('1');
	if($terms == null) die('2');
	$terms = strtolower(trim($terms));
	$terms = remove_accents($terms);
	
	$ret = array();
    $i = $j = 0;
	//$terms_arr = explode(' ',$terms);
	
	/**
	 * case insensitive in array
	 * @param string $string
	 * @param array $array
	 * @return boolean
	 */
	function case_in_array($string,$array){
		foreach($array as $val){
			if(strcasecmp($string, $val) == 0) return true;
		}
		return false;
	}
	
	if( in_array($catgeory,array('a','v','i','h','r') ) ){
		$total_suggestion = array();
		$changed = false;
		/* 
		 * search in country listing all the options (=, start with and LIKE)
		 */
		$limit = 10;
		
		$countryTodisplay = $cityTodisplay = $mediaToDisplay = array();
		
		$country_opts = array(
							'term' => $terms,
							'strict' => 2,
							'limit' => $limit
						);
						
		$possible_countries = searchGetCountry($country_opts);
		/*
		$possible_countries = array();
		
		foreach ($possible_countries_all as $row){
			if (!in_array($row,$possible_countries)) array_push($possible_countries,$row);
		}
		*/
		if( $possible_countries ){
			
			foreach( $possible_countries as $possible ){
				
				$countryTodisplay[$j]['fullArray'] = $possible;
				$paragraph = $possible['name'];
				$search = $terms;
				
				$countryTodisplay[$j] = array_merge( $countryTodisplay[$j], allSearchfunctions($search, $paragraph) );
				
				$dkey = '';
				$stanName = str_replace($terms, '<strong class="if_possible_countries">'.$terms.'</strong>', strtolower($possible['name']));
				$ret[$j]['name'] = $possible['name'];
				$ret[$j]['label'] = $stanName;
				$ret[$j]['url'] = ReturnLink('search/SearchCategory/'.$catgeory.'/ss/'.$possible['name']);
				$ret[$j]['wrong'] = $terms;
				$ret[$j]['right'] = $possible['name'];
				$j++;
				
			}
			
		}
		
		/*
		$ret = 
		{"fullArray":{"0":"Costa Rica","name":"Costa Rica"},"occNumber":1,"beginWith":false,"position":{"2":"costa"},"density":33.333333333333},
		{"fullArray":{"0":"Afghanistan","name":"Afghanistan"},"occNumber":1,"beginWith":false,"position":{"7":"Afghanistan"},"density":27.272727272727},
		{"fullArray":{"0":"United States of America","name":"United States of America"},"occNumber":1,"beginWith":false,"position":{"7":"states"},"density":14.285714285714},
		{"fullArray":{"0":"Holy See (Vatican City State)","name":"Holy See (Vatican City State)"},"occNumber":1,"beginWith":false,"position":{"22":"state"},"density":13.04347826087}
		
		usort($countryTodisplay, 'occurencySorting');
		usort($countryTodisplay, 'beginWithSorting');
		usort($countryTodisplay, 'densitySorting');
		*/
				
		/* 
		 * end of search country
		 */
		/* 
		 * search in city listing all the options (=, start with and LIKE)
		 */
		$limit = 10;
		$possible_cities = array();
		
		$cities_opts = array(
							'term' => $terms,
							'limit' => $limit,
							'strict' => 2
						);
		$possible_cities = searchGetCity($cities_opts);
		/*
		$possible_cities = array();
		
		foreach ($possible_cities_all as $row){
			if (!in_array($row,$possible_cities)) array_push($possible_cities,$row);
		}
		*/
		if( $possible_cities ){
			
			foreach( $possible_cities as $possible ){
				
				$cityTodisplay[$j]['fullArray'] = $possible;
				$paragraph = $possible['name'];
				$search = $terms;
				
				$cityTodisplay[$j] = array_merge( $cityTodisplay[$j], allSearchfunctions($search, $paragraph) );
				
				$dkey = '';
				$stanName = str_replace($terms, '<strong class="if_possible_cities">'.$terms.'</strong>', strtolower($possible['name']));
				$ret[$j]['name'] = $possible['name'];
				$ret[$j]['label'] = $stanName;
				$ret[$j]['url'] = ReturnLink('search/SearchCategory/'.$catgeory.'/ss/'.$possible['name']);
				$ret[$j]['wrong'] = $terms;
				$ret[$j]['right'] = $possible['name'];
				$j++;
				
			}
						
		}
		
		/*
		 *end of city search
		 */
		/*
		 * beign of media search
		 */
		$limit = 10;
				
		$media_opts = array(
							'term' => $terms,
							'limit' => $limit,
							'strict' => 2
						);
		
		$possible_media = suggestMedia($media_opts);
		/*
		$possible_media = array();
		
		foreach ($possible_media_all as $row){
			if (!in_array($row,$possible_media)) array_push($possible_media,$row);
		}
		*/
		if( $possible_media ){
			
			$i = $j+1;
			
			foreach( $possible_media as $possible ){
				
				$mediaTodisplay[$j]['fullArray'] = $possible;
				$paragraph = htmlEntityDecode($possible['title']);
				$search = $terms;
				
				$mediaTodisplay[$j] = array_merge( $mediaTodisplay[$j]['fullArray'], allSearchfunctions($search, $paragraph) );
				
				$j++;
				
			}
		}
		
		
		/*
		$ret = 
		{"fullArray":{"0":"Paris - Bateau mouche Tour","title":"Paris - Bateau mouche Tour"},"occNumber":1,"beginWith":true,"position":["paris"],"density":19.047619047619},
		{"fullArray":{"0":"Mercedes Benz at Paris Car Show 2012 in France","title":"Mercedes Benz at Paris Car Show 2012 in France"},"occNumber":1,"beginWith":false,"position":{"17":"paris"},"density":10.526315789474},
		{"fullArray":{"0":"Maserati or Ferrari- Paris- salon de l automobile 2012 France","title":"Maserati or Ferrari- Paris- salon de l automobile 2012 France"},"occNumber":1,"beginWith":false,"position":{"20":"paris"},"density":8},
		{"fullArray":{"0":"Lamborghini at Paris Car show 2012... France","title":"Lamborghini at Paris Car show 2012... France"},"occNumber":1,"beginWith":false,"position":{"15":"paris"},"density":11.428571428571},
		{"fullArray":{"0":"Salon de l'automobile - Paris \u2013 2012 -  the wonderful and trendy new smart - I like it","title":"Salon de l'automobile - Paris \u2013 2012 -  the wonderful and trendy new smart - I like it"},"occNumber":1,"beginWith":false,"position":{"22":"paris"},"density":6.25},
		{"fullArray":{"0":"Salon de l'automobile - Paris - 2012- Mercedes stand- AMG and the amazing new Mecedes concept car","title":"Salon de l'automobile - Paris - 2012- Mercedes stand- AMG and the amazing new Mecedes concept car"},"occNumber":1,"beginWith":false,"position":{"22":"paris"},"density":5.1948051948052},
		{"fullArray":{"0":"Porsche at Paris Car Show 2012","title":"Porsche at Paris Car Show 2012"},"occNumber":1,"beginWith":false,"position":{"11":"paris"},"density":16},{"fullArray":{"0":"Rolls Royce au Salon de l'automobile Paris 2012","title":"Rolls Royce au Salon de l'automobile Paris 2012"},"occNumber":1,"beginWith":false,"position":{"37":"paris"},"density":10},
		{"fullArray":{"0":"River Seine Paris... Tour en Bateaux Mouches","title":"River Seine Paris... Tour en Bateaux Mouches"},"occNumber":1,"beginWith":false,"position":{"12":"paris"},"density":11.428571428571},
		{"fullArray":{"0":"Restaurant Terrace of the Westin Hotel Paris","title":"Restaurant Terrace of the Westin Hotel Paris"},"occNumber":1,"beginWith":false,"position":{"39":"paris"},"density":10.526315789474}
		*/
		
		
		//var_dump($mediaTodisplay);
		usort($mediaTodisplay, 'densitySorting');
		//var_dump($mediaTodisplay);
		usort($mediaTodisplay, 'occurencySorting');
		//var_dump($mediaTodisplay);
		usort($mediaTodisplay, 'beginWithSorting');
		//var_dump($mediaTodisplay);
		//print_r($mediaTodisplay);
		foreach($mediaTodisplay as $possible){
			//$possible = $displayMedia["fullArray"];
			$dkey = '';
			$stanName = str_replace($terms, '<strong class="sss">'.$terms.'</strong>', strtolower(htmlEntityDecode($possible['title'])));
			$ret[$i]['name'] = htmlEntityDecode($possible['title']);
			$ret[$i]['label'] = $stanName;
			$ret[$i]['url'] = ReturnLink('search/SearchCategory/'.$catgeory.'/ss/'.htmlEntityDecode($possible['title']));
			$ret[$i]['wrong'] = $terms;
			$ret[$i]['right'] = htmlEntityDecode($possible['title']);
			$i++;
			
		}
		
		/*
		 * end of media search
		 */
		echo json_encode($ret);
                
	}else if($catgeory == 'u'){
		$possible_users = suggestionGetUser($terms);

		if( $possible_users != null ) $changed = true;
		
		//$possible_users = array_s
		function cmp($a, $b) {
			$u1 = returnUserDisplayName($a,array('max_length' => null));
			$u2 = returnUserDisplayName($b,array('max_length' => null));

			return strcasecmp($u1, $u2);
		}
		uasort($possible_users, 'cmp');

		if( $changed ){
			$i = 0;
			foreach($possible_users as $possible){
				$username = returnUserDisplayName($possible,array('max_length' => null));
				
				//$dkey = '<span style="font-weight: bold;font-size:12px;">' . $name . '</span> - <span style="font-size:11px;">'.$username.'</span>';
				$dkey = '<span style="font-size:11px;"><img src="'. ReturnLink('media/tubers/' . $possible['profile_Pic']) .'" alt="'.$username.'" class="autocompletImage"/><span class="search_suggestion_text">'.$username.'</span></span>';
				$ret[$i]['label'] = $dkey;
				$ret[$i]['wrong'] = $terms;
				$ret[$i]['right'] = $username;
				$ret[$i]['id'] = $possible['id'];
				$i++;
			}

			echo json_encode($ret);
		}else{
			die('');
		}
	}