<?php
/**
 * returns a url that is nice for the user and accpeted by the browser
 * the input string should not contain the replace values such as _
 * @param string $in_string the input to be encoded
 * @return string the encoded string
 */
function seoEncodeURL($in_string) {
    $replace = array(
        ' ' => '-'
    );
    $search_array = array_keys($replace);
    $replace_array = array_values($replace);
    $out = str_replace($search_array, $replace_array, $in_string);
    return $out;
}

/**
 * returns the value encoded by seoEncodeUrl
 * @param string $in_string the input to be encoded
 * @return string the decoded string
 */
function seoDecodeURL($in_string) {
    $replace = array(
        '-' => ' '
    );
    $search_array = array_keys($replace);
    $replace_array = array_values($replace);
    $out = str_replace($search_array, $replace_array, $in_string);
    return $out;
}

/**
 * primitive function to get the executing file
 * @return string the executing filename
 */
function _seoExecutingFile() {
    global $request;
//    $file = $_SERVER["SCRIPT_FILENAME"];
    $file = $request->server->get('SCRIPT_FILENAME', '');
    return basename($file);
}

/**
 * gets the seo text for a page
 * @param type $which SEO_TITLE, SEO_DESCRIPTION, SEO_KEYWORDS
 * @return string
 */
function seoTextGet() {
    $pagecurname = _seoExecutingFile();
    $title = "";
    $keywords = "";
    $action_array = array();
    $desc = _("Things to do in Paris; Discover Barcelona, Los Angeles; Ancient Rome; Best of Istanbul; Hotels and Restaurants Reviews; Points of Interest; Plan Trip");    
    switch ($pagecurname) {
        case 'embed.php':
            global $VideoInfo;
            
            $category = categoryGetInfo( $VideoInfo['category'] );
            $user = getUserInfo($VideoInfo['userid']);
            
            $action_text = '%s embeded-tourist tube';  
            $sttretitle = htmlEntityDecode($VideoInfo['title']);
            $sttretitle = substr($sttretitle, 0, 28).' '.$VideoInfo['hash_id'];
            $action_array[]=htmlEntityDecode($sttretitle);            
            $title = vsprintf($action_text, $action_array);
            
            $keywords = '';
            $desc = vsprintf(_("enjoy the best collection of %s embeded and travel pictures and best videos of the web, don't miss visiting us on touristtube website - %s"),array(htmlEntityDecode($VideoInfo['title']).' '.$VideoInfo['hash_id'], $VideoInfo['hash_id']) );            
            break;
        case 'tubers.php':
            global $search_string;
            $action_text = 'Tubers';
            if( $search_string!='' ){
                $action_text .= ' - %s';  
                $action_array[]=htmlEntityDecode($search_string); 
            }            
            $title = vsprintf(_TL($action_text), $action_array); 
            $keywords_string =_("things to do")." , "._("attraction")." , "._("landmarks")." , "._("museums")." , "._("activity")." , "._("advice")." , "._("attractions")." , "._("vacation")." , "._("reviews")." , "._("travel")." , "._("World")." , "._("attractions in World")." , "._("activities in World")." , "._("things to do in World");
            break;
        case 'search-location.php':
//            $title = _TL("Search for Hotels,Restaurants");
            $title = _("Search for Hotels,Restaurants");
            break;
    }
    if ($title == '') $title = _('TouristTube | Reviews, Connect, Travel and Archive your Trip'); 
    $title = str_replace('"', '', $title);
    return array($title, $desc, $keywords);
}


function _specialExplode($in_text) {
    return preg_split("/[\s,\(\)\:]+/", $in_text);
}

/**
 * primitive function used by <b>seoHyperlinkText</b> to explode the text into words
 * @ignore
 * @param array $words list of words
 * @param integer $length the amount of words in the mix array
 * @return array the set of exploded terms
 */
function _specialWordMix($all_words, $length) {

    $ret = array();

    $i = 0;

    while ($i <= count($all_words) - $length) {

        $current_str1 = 0;
        $loop_ret = array();
        while ($current_str1 < $length) {

            $loop_ret[] = $all_words[$i + $current_str1];

            $current_str1++;
        }

        $term_text = implode(' ', $loop_ret);
        $term_text_ascii = remove_accents($term_text);
        $term_text_ascii = preg_replace('/[^a-z0-9A-Z\-\ ]/', '', $term_text_ascii);

        $loop_arr = array('real' => $term_text, 'cleaned' => $term_text_ascii);

        $ret[] = $loop_arr;

        $i++;
    }

    return $ret;
}

//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function seoGetStaticPages($meta){
//    if($meta=='keywords'){
////          Commented by Anthony Malak to fix the translation 04-03-2015
////          <Start>
//
//        $str = 
////            'tourist tube, trip, trip advisor, tourist, tube, vacation, photos, videos , things to do, travel planning, travel discover, travel, planning, hotel, hotels, motel, bed and breakfast, inn, restaurants, planner, rating, ratings, review, reviews, popular, plan, cheap, discount, map, maps, golf, ski, articles, attractions, landmarks, museums, advice, best of, the best, resorts, airports, Points of interests, POI, live cam, web cam, best places to visit, guided tour, visit, location';
//                _("tourist tube")." , "._("trip")." , "._("trip advisor")." , "._("tourist")." , "._("tube")." , "._("vacation")." , "._("photos")." , "._("videos")." , "._("things to do")." , "._("travel planning")." , "._("travel discover")." , "._("travel")." , "._("planning")." , "._("hotel")." , "._("hotels")." , "._("motel")." , "._("bed and breakfast")." , "._("inn")." , "._("restaurants")." , "._("planner")." , "._("rating")." , "._("ratings")." , "._("review")." , "._("reviews")." , "._("popular")." , "._("plan")." , "._("cheap")." , "._("discount")." , "._("map")." , "._("maps")." , "._("golf")." , "._("ski")." , "._("articles")." , "._("attractions")." , "._("landmarks")." , "._("museums")." , "._("advice")." , "._("best of")." , "._("the best")." , "._("resorts")." , "._("airports")." , "._("Points of interests")." , "._("POI")." , "._("live cam")." , "._("web cam")." , "._("best places to visit")." , "._("guided tour")." , "._("visit")." , "._("location");
//    }
//    if($meta=='description'){
////        $str = _TL('Discover China, California; Free 1 Terabyte for your Videos; Best of Paris; Hotels Ã¢â‚¬â€œ Restaurants Reviews; Rome Landmarks; Join Ã¢â‚¬â€œ Enjoy our Community');
//        $str = _("Discover China, California; Free 1 Terabyte for your Videos; Best of Paris; Hotels Ã¢â‚¬â€œ Restaurants Reviews; Rome Landmarks; Join Ã¢â‚¬â€œ Enjoy our Community");
////          Commented by Anthony Malak to fix the translation 04-03-2015
////          <end>
//
//    }
//    
//return $str;
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>

/**
 * special replace function to avoid overwriting previous replaces
 * @ignore
 * @param string $what search for this string
 * @param string $with replace the found string with this string
 * @param string $where search in this string
 */
function _specialReplace($what, $with, $where) {
    $out = "";
    $what_len = strlen($what);
    $where_len = strlen($where);
    $i = 0;

    while ($i < $where_len) {

        //the end of the string
        if ($i > $where_len - $what_len) {
            $out .= substr($where, $i);
            break;
        }

        //if the start of an <a> tag continue
        $atag = (substr($where, $i, 2) == '<a');
        if ($atag) {
            $skipto = strpos($where, '</a>', $i + 1);
            $advance = $skipto - $i + strlen('</a>');
            $loop_str = substr($where, $i, $advance);
            $out .= $loop_str;
            $i = $skipto + strlen('</a>');
            continue;
        }

        //search for term
        $loop_str = substr($where, $i, $what_len);

        //concider a word if after it is one of these
        $punc = array(' ', ',', '.', '-');

        $next_char_index = $what_len + $i;

        //make sure if it is found its a word.
        if (($loop_str == $what) && (!isset($where[$next_char_index]) || ( isset($where[$next_char_index]) && in_array($where[$next_char_index], $punc) ) )) {
            $out .= $with;
            $i+= $what_len;
        } else {
            //or just append
            $out .= $where[$i];
            $i++;
        }
    }

    return $out;
}

/**
 * primitive function that tries to guess if a word is a location
 * @param string $word
 * @return array|false the cms_locations record or false if not found
 */
function _guessSearchLocation($word) {
	global $dbConn;
	$params = array();  
//    $l_word = strtolower($word);
//    $query = "SELECT * FROM cms_locations AS L WHERE LOWER(name)='$l_word' LIMIT 1";
//    $res = db_query($query);
//    if ($res && (db_num_rows($res) != 0)) {
//        return db_fetch_assoc($res);
//    }
    $l_word = strtolower($word);
    $query = "SELECT * FROM cms_locations AS L WHERE LOWER(name)=:L_word LIMIT 1";
	$params[] = array(  "key" => ":L_word",
                            "value" =>$l_word);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();
    if ($res && ($ret != 0)) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
}

/**
 * primitive function that tries to guess the city in which a media query might exist in
 * @param string $word the word that could either be a city or a
 * @return array a cms_videos record with the best buess or false
 */
function _guessSearchCityMedia($word) {
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <start>
	global $dbConn;
    $params = array();  

    $l_word = strtolower($word);
    $l_words = explode(' ', $l_word);
    $search_like_query_arr = array();
    foreach ($l_words as $l_word_loop) {
        $search_like_query_arr[] = " ( LOWER(title) LIKE '%$l_word_loop%' OR LOWER(placetakenat) LIKE '%$l_word_loop%' ) ";
    }
    $search_like_query = implode(' OR ', $search_like_query_arr);
//    $query = "SELECT cityid FROM cms_videos AS V WHERE $search_like_query ORDER BY RAND() LIMIT 20";
    $query = "SELECT cityid FROM cms_videos AS V WHERE :Search_like_query ORDER BY RAND() LIMIT 20";
    $params[] = array(  "key" => ":Search_like_query",
                        "value" =>$search_like_query);
//    $res = db_query($query);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();

    if (!$res || ($ret == 0))
        return false;

    $potentials = array();
//    while ($row = db_fetch_row($res)) {
//        $city_id = $row[0];
//
//        if (isset($potentials[$city_id]))
//            $potentials[$city_id] ++;
//        else
//            $potentials[$city_id] = 1;
//    }
    $row = $select->fetchAll();
    foreach($row as $row_item){
        $city_id = $row_item[0];
        if (isset($potentials[$city_id]))
            $potentials[$city_id] ++;
        else
            $potentials[$city_id] = 1;
    }

    $max_city_count = 0;
    $likely_city_id = null;
    foreach ($potentials as $city_id => $count) {
        if ($count > $max_city_count) {
            $max_city_count = $count;
            $likely_city_id = $city_id;
        }
    }

    return cityGetInfo($likely_city_id);
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <end>
}

/**
 * creates hyperlinks inside texts that have links to content on the site
 * the links have the class 'seoLink'
 * @ignore
 * @param string $in_text
 * @todo this function is being called in the display. it can be slow so it should be moved to a table col/background service
 * @return string the "linkified" text
 */
function seoHyperlinkText($in_text) {
    global $CONFIG;
    require($CONFIG ['server']['root'].'vendor/autoload.php');
    $config = $CONFIG['solr_config'];
    $client = new Solarium\Client($config);
    $client->setAdapter('Solarium\Core\Client\Adapter\Http');
    $typeSearch = '(type:co OR type:ct OR type:h OR type:p) AND ';
    $length = 4;

    //get all the words in the string
    $out_text = trim($in_text);

    while ($length >= 1) {
        $all_words = _specialExplode($out_text);

        $terms = _specialWordMix($all_words, $length);

        //replace the terms in the output linkified text
        $i = 0;
        while ($i < count($terms)) {
            $term_arr = $terms[$i];

            $real_term = $term_arr['real'];
            $cleaned_term = $term_arr['cleaned'];
            $lterm = strtolower($cleaned_term);

            $found_link = null;

            //ignore in, a, and, etc ...
            if (is_null($found_link) && strlen($real_term) <= 3) {
                $i++;
                continue;
            }

            $searchString = $typeSearch . '+title_t2:"' . $lterm . '"';
//                                mail('elie@paravision.org', 'test', print_r($searchString, 1));
            try{
                $query = $client->createSelect();
                $query->setQuery($searchString);
                $resultset = $client->select($query);
                $count = $resultset->getNumFound();
                if (is_null($found_link)) {
                    if ($count > 0) {
                        //$found_link = 'search?qr=' . $real_term;
                        $found_link = 'search/qr/' . $real_term;
                    }
                }
            } catch (Exception $ex) {

            }

            if (!is_null($found_link)) {
                $link = sprintf('<a href="%s" class="seoLink" target="_blank" title="%s">%s</a>', ReturnLink($found_link), $real_term, $real_term);
                $out_text = _specialReplace($real_term, $link, $out_text);

                $i += $length - 1;
            }

            $i++;
        }

        $length--;
    }

    return $out_text;
}

/**
 * creates hyperlinks inside texts that have links to content on the site
 * the links have the class 'seoLink'
 * @ignore
 * @param string $in_text
 * @todo this function is being called in the display. it can be slow so it should be moved to a table col/background service
 * @return string the "linkified" text
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function seoHyperlinkTextOld($in_text) {
//
//    $length = 3;
//
//    //get all the words in the string
//    $out_text = trim($in_text);
//    //$all_words = _specialExplode($out_text);
//    //return $all_words;
//    //try multiple words at a time
//    while ($length >= 1) {
//
//        $all_words = _specialExplode($out_text);
//
//        $terms = _specialWordMix($all_words, $length);
//
//        //replace the terms in the output linkified text
//        $i = 0;
//        while ($i < count($terms)) {
//
//            $term_arr = $terms[$i];
//
//            $real_term = $term_arr['real'];
//            $cleaned_term = $term_arr['cleaned'];
//            $lterm = strtolower($cleaned_term);
//
//            $found_link = null;
//
//
//            //ignore in, a, and, etc ...
//            if (is_null($found_link) && strlen($real_term) <= 3) {
//                $i++;
//                continue;
//            }
//
//            //check if the term is a country
//            if (is_null($found_link)) {
//                $query = "SELECT code FROM cms_countries WHERE LOWER(name)='$lterm'";
//                $res = db_query($query);
//                if ($res && (db_num_rows($res) != 0)) {
//                    $found_link = 'search?qr=' . $real_term;
//                }
//            }
//            //check if the term is a country
//            if (is_null($found_link)) {
//                $query = "SELECT state_code FROM states WHERE LOWER(state_name)='$lterm'";
//                $res = db_query($query);
//                if ($res && (db_num_rows($res) != 0)) {
//                    $found_link = 'search?qr=' . $real_term;
//                }
//            }
//            //check if the term is a city
//            if (is_null($found_link)) {
//                $query = "SELECT name FROM webgeocities AS C WHERE name='$lterm' and order_display>=1"; //has data
//                $res = db_query($query);
//                if ($res && (db_num_rows($res) != 0)) {
//                    $found_link = 'search?qr=' . $real_term;
//                }
//            }
//
//            if (is_null($found_link)) {
//                $query = "SELECT p.name FROM discover_poi as p INNER JOIN discover_poi_images as i on i.poi_id = p.id WHERE name='$lterm' ORDER BY i.default_pic DESC";
//                $res = db_query($query);
//                if ($res && (db_num_rows($res) != 0)) {
//                    $found_link = 'search?qr=' . $real_term;
//                }
//            }
//
//            //check if the term is a location
////				if( is_null($found_link) ){
////					$location_rec = _guessSearchLocation($real_term);
////					if( $location_rec != false ){
////						$category_id = $location_rec['category_id'];
////						if($category_id == 1){
////							//$found_link = 'search-location/type/restaurant/search-string/' . $real_term;
////							$found_link = 'search?q=' . $real_term;
////						}else if($category_id == 2){
////							//$found_link = 'search-location/type/hotel/search-string/' . $real_term;
////							$found_link = 'search?q=' . $real_term;
////						}else{
////							$found_link = 'search?q=' . $real_term;
////						}
////					}
////				}
//
//            if (!is_null($found_link)) {
//                $link = sprintf('<a href="%s" class="seoLink" target="_blank" title="%s">%s</a>', ReturnLink($found_link), $real_term, $real_term);
//                $out_text = _specialReplace($real_term, $link, $out_text);
//
//                $i += $length - 1;
//            }
//
//            $i++;
//        }
//
//        $length--;
//    }
//
//    return $out_text;
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>

/**
 * prints the breadcrumbs with class 'seoCrumb'.
 * @param array $in_options the input word.<br/>
 * options include:<br/>
 * <b>word</b>: string - the word for which the crumbs are being generated. default null.<br/>
 * <b>entity_id</b>: integer - the input entity. default null.<br/>
 * <b>entity_type</b>: {SOCIAL_ENTITY_*}. default null.<br/>
 * @return string the breadcrumb set of links
 */
function seoBreadCrumbs($in_options) {

    $default_opts = array(
        'word' => null,
        'entity_id' => null,
        'entity_type' => null,
        'location_id' => null,
        'city_id' => null
    );

    $options = array_merge($default_opts, $in_options);

    $crumbs_arr = array();

    $cityInfo = null;
    $stateInfo = null;
    $countryInfo = null;
    $continentInfo = null;
    $crumbs_lvl = 0;
    if (!is_null($options['entity_id']) && !is_null($options['entity_type'])) {



        $entity_info = socialEntityInfo($options['entity_type'], $options['entity_id']);

        if ($options['entity_type'] == SOCIAL_ENTITY_MEDIA) {

            $cityInfo = cityGetInfo($entity_info['cityid']);
            $countryInfo = countryGetInfo($cityInfo['country_code']);
            $stateInfo = worldStateInfo($cityInfo['country_code'], $cityInfo['state_code']);
            $continentInfo = continentGetInfo($countryInfo['continent_code']);
            $crumbs_lvl = 4;

            $options['word'] = htmlEntityDecode($entity_info['title']);
        } else if ($options['entity_type'] == SOCIAL_ENTITY_ALBUM) {

            $cityInfo = cityGetInfo($entity_info['cityid']);
            $countryInfo = countryGetInfo($cityInfo['country_code']);
            $stateInfo = worldStateInfo($cityInfo['country_code'], $cityInfo['state_code']);
            $continentInfo = continentGetInfo($countryInfo['continent_code']);
            $crumbs_lvl = 4;
            $entity_info['title'] = htmlEntityDecode($entity_info['catalog_name']);
            $options['word'] = htmlEntityDecode($entity_info['title']);
        } else if ($options['entity_type'] == SOCIAL_ENTITY_WEBCAM) {

            $cityInfo = cityGetInfo($entity_info['city_id']);
            $countryInfo = countryGetInfo($cityInfo['country_code']);
            $stateInfo = worldStateInfo($cityInfo['country_code'], $cityInfo['state_code']);
            $continentInfo = continentGetInfo($countryInfo['continent_code']);
            $crumbs_lvl = 4;
            $entity_info['title'] = htmlEntityDecode($entity_info['name']);
            $options['word'] = htmlEntityDecode($entity_info['title']);
        }

        if (isset($entity_info['title'])) {
            $options['word'] = htmlEntityDecode($entity_info['title']);
        } else if (isset($entity_info['name'])) {
            $options['word'] = htmlEntityDecode($entity_info['name']);
        }
    } else if (!is_null($options['city_id'])) {
        $cityInfo = cityGetInfo($options['city_id']);
        $countryInfo = countryGetInfo($cityInfo['country_code']);
        $stateInfo = worldStateInfo($cityInfo['country_code'], $cityInfo['state_code']);
        $continentInfo = continentGetInfo($countryInfo['continent_code']);
        if (strtolower($options['word']) == strtolower($cityInfo['name'])) {
            $crumbs_lvl = 3;
        } else {
            $crumbs_lvl = 4;
        }
    } else if (!is_null($options['word'])) {

        if (strlen($options['word']) == 0)
            return '';

        //check if the word is a city
        if ($continentInfo == null) {
            $cityInfo = cityFind($options['word']);
            if ($cityInfo != false) {
                $countryInfo = countryGetInfo($cityInfo['country_code']);
                $stateInfo = worldStateInfo($cityInfo['country_code'], $cityInfo['state_code']);
                $continentInfo = continentGetInfo($countryInfo['continent_code']);
                $crumbs_lvl = 3;
            }
        }

        //check if the word is a continent
        if ($continentInfo == null) {
            $cc = continentGetCode($options['word']);
            if ($cc != false) {
                $continentInfo = continentGetInfo($cc);
                $crumbs_lvl = 1;
            }
        }


        //check if the word is a country
        if ($continentInfo == null) {
            $cc = countryGetCode($options['word']);
            if ($cc != false) {
                $countryInfo = countryGetInfo($cc);
                $continentInfo = continentGetInfo($countryInfo['continent_code']);
                $crumbs_lvl = 2;
            }
        }

        //check if the word is a location
        if ($continentInfo == null) {
            $location_rec = _guessSearchLocation($options['word']);

            if ($location_rec != false) {
                $city_id = $location_rec['city_id'];
                $cityInfo = cityGetInfo($city_id);
                $countryInfo = countryGetInfo($cityInfo['country_code']);
                $stateInfo = worldStateInfo($cityInfo['country_code'], $cityInfo['state_code']);
                $continentInfo = continentGetInfo($countryInfo['continent_code']);
                $crumbs_lvl = 4;
            }
        }

        //check try to guess the city based on the media records
        if ($continentInfo == null) {
            $cityInfo = _guessSearchCityMedia($options['word']);
            if ($cityInfo != false) {
                $countryInfo = countryGetInfo($cityInfo['country_code']);
                $stateInfo = worldStateInfo($cityInfo['country_code'], $cityInfo['state_code']);
                $continentInfo = continentGetInfo($countryInfo['continent_code']);
                $crumbs_lvl = 4;
            }
        }
    }

    ////////////////////////////
    //by here we have all available info

    if (is_null($continentInfo)) {
        return '';
    }

    if (!is_null($continentInfo) && sizeof($continentInfo) > 0 && $continentInfo['name'] != '') {
        //$crumb_link = ReturnLink('search/SearchCategory/a/ss/'.$continentInfo['name']);
        //$crumb_link = ($options['entity_type'] == SOCIAL_ENTITY_WEBCAM)?ReturnLink('live/CN/' . $continentInfo['code']) :ReturnLink('search?qr=' . $continentInfo['name']);
        $crumb_link = ($options['entity_type'] == SOCIAL_ENTITY_WEBCAM)?ReturnLink('live/CN/' . $continentInfo['code']) :ReturnLink('search/qr/' . $continentInfo['name']);

        $crumb_text = $continentInfo['name'];
        $crumbs_arr[] = array('link' => $crumb_link, 'text' => $crumb_text);
    }

    if (!is_null($countryInfo) && sizeof($countryInfo) > 0 && $countryInfo['name'] != '') {
        //$crumb_link = ReturnLink('search/SearchCategory/a/ss/'.$countryInfo['name']);
        //$crumb_link = ($options['entity_type'] == SOCIAL_ENTITY_WEBCAM)?ReturnLink('live/CO/' . $countryInfo['code']) :ReturnLink('search?qr=' . $countryInfo['name']);
        $crumb_link = ($options['entity_type'] == SOCIAL_ENTITY_WEBCAM)?ReturnLink('live/CO/' . $countryInfo['code']) :ReturnLink('search/qr/' . $countryInfo['name']);
        $crumb_text = $countryInfo['name'];
        $crumbs_arr[] = array('link' => $crumb_link, 'text' => $crumb_text);
    }

    if (!is_null($stateInfo) && sizeof($stateInfo) > 0 && $stateInfo['state_name'] != '') {
        //$crumb_link = ReturnLink('search/SearchCategory/a/ss/'.$countryInfo['name']);
        //$crumb_link = ($options['entity_type'] == SOCIAL_ENTITY_WEBCAM)?ReturnLink('live/ST/' . $stateInfo['country_code'].'-'.$stateInfo['state_code']) :ReturnLink('search?qr=' . $stateInfo['state_name']);
        $crumb_link = ($options['entity_type'] == SOCIAL_ENTITY_WEBCAM)?ReturnLink('live/ST/' . $stateInfo['country_code'].'-'.$stateInfo['state_code']) :ReturnLink('search/qr/' . $stateInfo['state_name']);
        $crumb_text = $stateInfo['state_name'];
        $crumbs_arr[] = array('link' => $crumb_link, 'text' => $crumb_text);
    }

    if (!is_null($cityInfo) && sizeof($cityInfo) > 0 && $cityInfo['name'] != '') {
        //$crumb_link = ReturnLink('search/SearchCategory/a/ss/'.$cityInfo['name']);
        //$crumb_link = ($options['entity_type'] == SOCIAL_ENTITY_WEBCAM)?ReturnLink('live/C/'.$cityInfo['id']) :ReturnLink('search?qr=' . $cityInfo['name']);
        $crumb_link = ($options['entity_type'] == SOCIAL_ENTITY_WEBCAM)?ReturnLink('live/C/'.$cityInfo['id']) :ReturnLink('search/qr/' . $cityInfo['name']);
        $crumb_text = ucwords($cityInfo['name']);
        $crumbs_arr[] = array('link' => $crumb_link, 'text' => $crumb_text);
    }

//		$crumbs = array();
    $crumbs = '<ul class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';
    $ix=1;
    foreach ($crumbs_arr as $crumb_arr) {
        $crumb_link = $crumb_arr['link'];
        $crumb_text = $crumb_arr['text'];
//			$crumbs[] = sprintf('<a href="%s" class="seoCrumbLink">%s</a>', $crumb_link,$crumb_text );
        $crumbs .= sprintf('<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="%s" class="seoCrumbLink" title="%s" itemprop="item"><span itemprop="name">%s</span></a><span class="seoCrumbLinkSep">&gt;</span><meta itemprop="position" content="%s"></li>', $crumb_link,$crumb_text, $crumb_text,$ix);
        $ix++;
    }


//		if( $crumbs_lvl == 4){
////			$crumbs[] = sprintf('%s',$options['word']);
//                    $crumbs .= sprintf('<li>%s</li>',$options['word']);
//		}
    $crumbs .= '</ul>';

//		$all_crumbs_link = implode('<span class="seoCrumbLinkSep">&gt;</span>' , $crumbs);
    $all_crumbs_link = $crumbs;


    return $all_crumbs_link;
}

////////////////////////////////////////////////