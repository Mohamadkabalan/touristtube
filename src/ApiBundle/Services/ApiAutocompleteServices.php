<?php

namespace ApiBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;

class ApiAutocompleteServices extends Controller{
    
    protected $em;
    protected $container;
    protected $utils;
    
    
    public function __construct( EntityManager $em, ContainerInterface $container, Utils $utils ) {
        $this->em = $em;
        $this->container     = $container;
        $this->utils         = $utils;
        $this->translator    = $this->container->get('translator');
    }
    
    public function getReviewAutocompleteQuery( $options )
    {
        $Results = array();
        $lang = $options['lang'];
        if( $options['term'] != '' )
        {
            list( $result_list, $type ) = $this->container->get('ReviewsServices')->getReviewAutocompleteQR( $options['entity_type'], $options['term'], $options['limit'], $options['route'] );

            foreach ($result_list as $item)
            {
                $id    = $item['_source']['id'];
                $type  = strtolower($type);
                $title = $this->utils->htmlEntityDecode($item['_source']['name']);

                $address = '';
                if ( $type == 'h')
                {
                    $address = $this->container->get('ReviewsServices')->returnHRSHotelsLocation($item);
                    $link    = $this->container->get('TTRouteUtils')->returnHotelReviewsLink($lang, $title, $id);
                    if($options['route']=='web'){
                        $city = $item['_source']['vendor'][0]['city']['name'];
                        if(isset($item['_source']['location'][0]['state']['code'])){
                            $state_array = $this->container->get('CitiesServices')->worldStateInfo($item['_source']['location'][0]['country']['code'], $item['_source']['location'][0]['state']['code']);
                            $state_name = $this->utils->htmlEntityDecode($state_array[0]->getStateName());
                            $state        = $state_name;
                        }else{
                            $state        = '';
                        }
                        $country      = $item['_source']['location'][0]['country']['name'];
                    }
                }
                elseif ($type == 'p')
                {
                    $address = $this->container->get('ReviewsServices')->returnPoiLocation($item);
                    $link    = $this->container->get('TTRouteUtils')->returnThingstodoReviewLink($lang, $id, $title);
                    if($options['route']=='web'){
                        $city = $item['_source']['location']['city']['name'];
                        if(isset($item['_source']['vendor']['state']['name'])){
                            $state        = $item['_source']['vendor']['state']['name'];
                        }else{
                            $state        = '';
                        }
                        $country      = $item['_source']['location']['country']['name'];
                        if(isset($item['_source']['location']['address']) && $item['_source']['location']['address'] != ""){
                            $address      = $item['_source']['location']['address'];
                        }
                    }
                } 
                elseif ($type == 'ai')
                {
                    $address = $this->container->get('ReviewsServices')->returnAirportsLocation($item);
                    $link = $this->container->get('TTRouteUtils')->returnAirportReviewLink($lang, $id, $title);
                    if($options['route']=='web'){
                        $city = $item['_source']['location']['city']['name'];
                        if(isset($item['_source']['vendor']['state']['name'])){
                            $state        = $item['_source']['vendor']['state']['name'];
                        }else{
                            $state        = '';
                        }
                        $country      = $item['_source']['location']['country']['name'];
                    }
                }
                $item_array['id'] = $id;
                $item_array['title'] = $title;
                $item_array['address'] = $address;
                if($options['route']=='web'){
                    $item_array['link'] = $link;
                    $item_array['value_display'] = $title;
                    $item_array['city'] = $city;
                    $item_array['state'] = $state;
                    $item_array['country'] = $country;
                    $str = '';

                    if (isset($item_array['value_display']) && $item_array['value_display'] != '') $str              .= $title;
                    $str .= '<span class="search_result_description">';
                    $str .= ' '.$address.'</span>';
                    $item_array['value'] = $str;
                }
                
                $Results[] = $item_array;
            }
        }
        
        return $Results;
    }
    
    public function getHotelAutocompleteQuery($options = array() )
    {
        $Results = array();
        
        $term = $options['term'];
        $termWordCount             = str_word_count($term);
        $limit = $options['limit'];
        $page_src = $options['page_src'];
        $cityId = $options['cityId'];
        $language = $options['lang'];
        if(isset($options['from_mobile'])){
            $from_mobile = $options['from_mobile'];
        }else{
            $from_mobile = 1;
        }
        
        $countHotel                 = 0;
        $countCities                = 0;
        $highlitedNameWordCount     = 0;
        $hotelFirstRecordNameCount  = 0;
        $string_hotel               = '';
        $totalHotel                 = 0;
        $totalCities                = 0;
        $criteria                   = array('term' => $term);
        if ($cityId) {
            $criteria['cityId'] = $cityId;
        }
        
        
        $url_source = 'getHotelAutocompleteQuery - getHotelStaticSearch - Mobile';
        $queryStringResult = $this->container->get('ElasticServices')->getHotelStaticSearch( $criteria, $url_source );
        $result_static['hits']['hits'] = $queryStringResult[0];
        
        if (!isset($result_static['hits']['hits'][0]))
        {
            $criteria['page_src'] = $page_src;
            
            $url_source = 'getHotelAutocompleteQuery - getHotelSearch - Mobile';
            list( $result_hotel, $totalHotel ) = $this->container->get('ElasticServices')->getHotelSearch( $criteria, $url_source );            
            
            $url_source = 'getHotelAutocompleteQuery - getHotelsCitiesSearch - Mobile';
            list( $result_cities, $totalCities ) = $this->container->get('ElasticServices')->getHotelsCitiesSearch( $criteria, $url_source );
            
            if ($totalHotel > 5 && $totalCities > 5) {
                $countHotel  = 5;
                $countCities = 5;
            } elseif ($totalHotel < 5) {
                $countCities = 10 - $totalHotel;
                $countHotel  = $totalHotel;
            } elseif ($totalCities < 5) {
                $countHotel  = 10 - $totalCities;
                $countCities = $totalHotel;
            }
            
            $all_result2      = $result_hotel;
            $all_result3      = $result_cities;
            $hotelResultCount = 0;
            $cityResultCount = 0;
            
            if (isset($all_result2[0])) {
                if(isset($all_result2[0]['highlight']['translation.name_'.$language])){
                    $highlitedNameResult = $all_result2[0]['highlight']['translation.name_'.$language];
                }elseif(isset($all_result2[0]['highlight']['name'][0])){
                    $highlitedNameResult = $all_result2[0]['highlight']['name'][0];
                }else{
                    $highlitedNameResult = $all_result2[0]['_source']['name'];
                }
                
                $highlitedNameWordCount = preg_match_all('/<em>(.*?)<\/em>/s', $highlitedNameResult, $term);
                
                $hotelFirstRecordNameCount = str_word_count($all_result2[0]['_source']['name']);
                
                $isTermMatchingHotel = ( $termWordCount >= 3 && ($highlitedNameWordCount == $termWordCount) && ($highlitedNameWordCount >= $hotelFirstRecordNameCount - 1) );
                
                if ($isTermMatchingHotel) {
                    $countHotel = 10;
                }
            }
            
            if (!$isTermMatchingHotel && $all_result3) {
                foreach ($all_result3 as $document3) {

                    if ($cityResultCount < $countCities) {
                        $cityResultCount++;
                    }
                    $title                         = $document3['_source']['name'];
                    $item_array                    = array();
                    $item_array['id']              = intval($document3['_source']['id']);
                    $item_array['locationId']      = 0;
                    $item_array['hotelId']         = 0;
                    $item_array['cityId']          = intval($document3['_source']['id']);
                    $item_array['longitude']       = $document3['_source']['coordinates']['lon'];
                    $item_array['latitude']        = $document3['_source']['coordinates']['lat'];
                    $item_array['title']           = $title;
                    $item_array['type']            = 'city';
                    $item_array['country']         = $document3['_source']['country']['code'];
                    $item_array['countryName']     = $document3['_source']['country']['name'];
                    $item_array['address']         = '';
                    $item_array['entityType']      = $this->container->getParameter('SOCIAL_ENTITY_CITY');
                    if($from_mobile == 0 ){
                        $links                = '';
                        $item_array['label']  = "<a href='".$links."' title='".$document3['_source']['name']."'><div class='search_result_container'> <div class='search_result'>"
                            ."<img class='search_result_image1' src='".$this->container->get("TTRouteUtils")->generateMediaURL('/media/images/search/searchfor_static1.png')."' alt='search result image'>";
                        if (isset($document3['highlight']['name'][0])) {
                            $highlitedName = $document3['highlight']['name'][0];
                        } else {
                            $highlitedName = $document3['_source']['name'];
                        }
                        if($page_src == $this->container->getParameter('hotels')['page_src']['hrs']){
                            if (isset($document3['_source']['location_id'])) 
                            {
                                $item_array['locationId']  = $document3['_source']['location_id'];
                            } else {
                                continue;
                            }
                        }
                        $item_array['label'] .= $highlitedName."<br>".$document3['_source']['country']['name'];
                        $item_array['label'] .= "</div></div></a>";
                        $item_array['name']   = $item_array['value'] = $document3['_source']['name'];
                    }
                    if($page_src == $this->container->getParameter('hotels')['page_src']['hrs']){
                        if (isset($document3['_source']['location_id'])) 
                        {
                            $item_array['locationId']  = $document3['_source']['location_id'];
                        } else {
                            continue;
                        }
                    }
                    $Results[]            = $item_array;
                    if($cityResultCount == $countCities){
                        break;
                    }
                }
            }
            
            foreach ($all_result2 as $document2) {
                
                if ($hotelResultCount < $countHotel) {
                    $hotelResultCount++;
                }
                
                if(!isset($document2['highlight']['translation.name_'.$language][0])){
                    $title = $document2['_source']['name'];
                }else{
                    $title = $document2['highlight']['translation.name_'.$language][0];
                }
                
                $country_code     = $document2['_source']['location'][0]['country']['code'];
                if ($country_code != '' || !empty($country_code)) 
                    $contryInfo       = $this->container->get('CmsCountriesServices')->countryGetInfo($country_code);
                $countryName      = $contryInfo->getName();
                
                $item_array                    = array();
                $item_array['id']              = intval($document2['_source']['id']);
                $item_array['locationId']      = 0;
                $item_array['hotelId']         = intval($document2['_source']['id']);
                $item_array['cityId']          = 0;
                $item_array['longitude']       = 0;
                $item_array['latitude']        = 0;
                $item_array['title']           = $title;
                $item_array['type']            = 'hotel';
                $item_array['country']         = $country_code;
                $item_array['countryName']     = $countryName;
                $item_array['address']         = $document2['_source']['location'][0]['address_line_1'].' '.$document2['_source']['location'][0]['address_line_2'].' '.$document2['_source']['location'][0]['city']['name'].' '.$countryName;
                $item_array['entityType']      = $this->container->getParameter('SOCIAL_ENTITY_HOTEL');
                
                if($from_mobile == 0 ){
                    $links            = '';
                    $item_array['label'] = "<a href='".$links."' title='".$document2['_source']['name']."'><div class='search_result_container'> <div class='search_result'>"
                    ."<img class='search_result_image1' src='".$this->container->get("TTRouteUtils")->generateMediaURL('/media/images/search/searchfor_static2.jpg')."' alt='search result image'>";
                    if(!isset($document2['highlight']['translation.name_'.$language][0])){
                        $highlitedName = $document2['_source']['name'];
                    }else{
                        $highlitedName = $document2['highlight']['translation.name_'.$language][0];
                    }
                    if(!$highlitedName){
                        $highlitedName = $document2['_source']['name'];
                    }
                    $item_array['label'] .= $highlitedName."<br>";
                    $item_array['label'] .= "<div class='search_result_description'> ";
                    $str1             = $document2['_source']['location'][0]['address_line_1'].' '.$document2['_source']['location'][0]['address_line_2'].' '.$document2['_source']['location'][0]['city']['name'].' ';                    
                    $str1             .= $countryName;
                    $item_array['label']         .= $str1."</div>";
                    $item_array['label']         .= "</div></div></a>";
                    if(isset($document2['_source']['location'][0]['city']['code'])){
                        $item_array['hotelCityCode'] = $document2['_source']['location'][0]['city']['code'];
                    }
                    $item_array['name'] = $item_array['value']  = $document2['_source']['name'];
                }
                if(isset($document2['_source']['location'][0]['city']['code'])){
                    $item_array['locationId']  = $document2['_source']['location'][0]['city']['code'];
                }
                
                $Results[] = $item_array;
                if ($hotelResultCount == $countHotel) {
                    break;
                }
            }            
        }
        else
        {
            $ret          = $result_static['hits']['hits'][0]['_source'];
            $result_array = $ret['results'];
            foreach ($result_array as $result) {
                $item_array               = array();
                $type                     = $result['entity_type'];
                $item_array['id']         = $result['entity_id'];
                $item_array['title']      = $result['name'];
                $item_array['entityType'] = $type;
                $item_array['locationId'] = 0;
                $item_array['hotelId']    = 0;
                $item_array['longitude']  = 0;
                $item_array['latitude']   = 0;
                $item_array['cityId']     = 0;
                $item_array['country']    = '';
                $item_array['countryName']= '';
                $item_array['address']    = '';
                $item_array['type']       = '';
                if ($type == $this->container->getParameter('SOCIAL_ENTITY_CITY')) 
                {
                    $HotelCityInfo    = $this->container->get("HRSServices")->getCmsHotelCityInfo($result['entity_id']);
                    if ($HotelCityInfo) $item_array['id'] = $item_array['locationId'] = $HotelCityInfo['hc_locationId'];
                    $item_array['cityId'] = $result['entity_id'];
                    $item_array['type']   = 'city';
                    if($from_mobile == 0){
                        $links                = '';
                        $popular              = intval($result['entity_popularity']);
                        $popularclass         = "";
                        if ($popular == 1) $popularclass         = " popular";
                        $item_array['locationId']    = $HotelCityInfo['hc_locationId'];
                        $item_array['name']   = $item_array['value'] = $result['name'];
                        $item_array['label']  = "<a href='".$links."' title='".$result['name']."'><div class='search_result_container$popularclass'> <div class='search_result'>"
                        ."<img class='search_result_image1' src='".$this->container->get("TTRouteUtils")->generateMediaURL('/media/images/search/searchfor_static1.png')."' alt='search result image'>";
                        $item_array['label']  .= $this->utils->highlightSearchStr($result['name'], $term)."<br>";
                        $item_array['label']  .= "<div class='search_result_description'> ";
                        $item_array['label']  .= "</div></div></div></a>";
                    }
                } elseif ($type == $this->container->getParameter('SOCIAL_ENTITY_DOWNTOWN')) {
                    $item_array['longitude'] = $result['coordinates']['lon'];
                    $item_array['latitude']  = $result['coordinates']['lat'];
                    $item_array['type']      = 'downtown';
                    if($from_mobile == 0){
                        $links               = '';
                        $popular             = intval($result['entity_popularity']);
                        $popularclass        = "";
                        if ($popular == 1) $popularclass         = " popular";
                        $item_array['locationId']   = 0;
                        $item_array['name']  = $item_array['value'] = $result['name'];
                        $item_array['label'] = "<a href='".$links."' title='".$result['name']."'><div class='search_result_container$popularclass'> <div class='search_result'>"
                        ."<img class='search_result_image1' src='".$this->container->get("TTRouteUtils")->generateMediaURL('/media/images/search/searchfor_static1.png')."' alt='search result image'>";
                        $item_array['label'] .= $this->utils->highlightSearchStr($result['name'], $term)."<br>";
                        $item_array['label'] .= "<div class='search_result_description'> ";
                        $item_array['label'] .= "</div></div></div></a>";
                    }
                } elseif ($type == $this->container->getParameter('SOCIAL_ENTITY_COUNTRY')) {
                    $item_array['countryName']= $result['name'];
                    $item_array['country']    = $result['country_code'];
                    $item_array['type']       = 'country';
                    if($from_mobile == 0){
                        $links                = '';
                        $popular              = intval($result['entity_popularity']);
                        $popularclass         = "";
                        if ($popular == 1) $popularclass         = " popular";
                        $item_array['locationId']    = 0;
                        $item_array['name']   = $item_array['value'] = $result['name'];
                        $item_array['label']  = "<a href='".$links."' title='".$result['name']."'><div class='search_result_container$popularclass'> <div class='search_result'>"
                        ."<img class='search_result_image1' src='".$this->container->get("TTRouteUtils")->generateMediaURL('/media/images/search/searchfor_static1.png')."' alt='search result image'>";
                        $item_array['label']  .= $this->utils->highlightSearchStr($result['name'], $term)."<br>";
                        $item_array['label']  .= "<div class='search_result_description'> ";
                        $item_array['label']  .= "</div></div></div></a>";

                    }
                } elseif ($type == $this->container->getParameter('SOCIAL_ENTITY_HOTEL')) {
                    $item_array['hotelId']    = intval($result['entity_id']);
                    $item_array['type']       = 'hotel';
                    if($from_mobile == 0){
                        $links                = '';
                        $popular              = intval($result['entity_popularity']);
                        $popularclass         = "";
                        if ($popular == 1) $popularclass         = " popular";
                        $item_array['locationId']    = 0;
                        $item_array['name']   = $item_array['value'] = $result['name'];
                        $item_array['label']  = "<a href='".$links."' title='".$result['name']."'><div class='search_result_container$popularclass'> <div class='search_result'>"
                        ."<img class='search_result_image1' src='".$this->container->get("TTRouteUtils")->generateMediaURL('/media/images/search/searchfor_static2.png')."' alt='search result image'>";
                        $item_array['label']  .= $this->utils->highlightSearchStr($result['name'], $term)."<br>";
                        $item_array['label']  .= "<div class='search_result_description'> ";
                        $item_array['label']  .= "</div></div></div></a>";
                    }
                } elseif ($type == $this->container->getParameter('SOCIAL_ENTITY_LANDMARK')) {
                    $item_array['longitude']  = $result['coordinates']['lon'];
                    $item_array['latitude']   = $result['coordinates']['lat'];
                    $item_array['type']       = 'landmark';
                    if($from_mobile == 0){
                        $links                = '';
                        $popular              = intval($result['entity_popularity']);
                        $popularclass         = "";
                        if ($popular == 1) $popularclass         = " popular";
                        $item_array['locationId']    = 0;
                        $item_array['name']   = $item_array['value'] = $result['name'];
                        $item_array['label']  = "<a href='".$links."' title='".$result['name']."'><div class='search_result_container$popularclass'> <div class='search_result'>"
                        ."<img class='search_result_image1' src='".$this->container->get("TTRouteUtils")->generateMediaURL('/media/images/search/searchfor_static4.png')."' alt='search result image'>";
                        $item_array['label']  .= $this->utils->highlightSearchStr($result['name'], $term)."<br>";
                        $item_array['label']  .= "<div class='search_result_description'> ";
                        $item_array['label']  .= "</div></div></div></a>";
                    }
                } elseif ($type == $this->container->getParameter('SOCIAL_ENTITY_AIRPORT')) {
                    $item_array['longitude'] = $result['coordinates']['lon'];
                    $item_array['latitude']  = $result['coordinates']['lat'];
                    $item_array['type']      = 'airport';
                    if($from_mobile == 0){
                        $links               = '';
                        $popular             = intval($result['entity_popularity']);
                        $popularclass        = "";
                        if ($popular == 1) $popularclass         = " popular";
                        $item_array['locationId']   = 0;
                        $item_array['name']  = $item_array['value'] = $result['name'];
                        $item_array['label'] = "<a href='".$links."' title='".$result['name']."'><div class='search_result_container$popularclass'> <div class='search_result'>
                                        <img class='search_result_image1' src='".$this->container->get("TTRouteUtils")->generateMediaURL('/media/images/search/searchfor_static7.png')."' alt='search result image'>";
                        $item_array['label'] .= $this->utils->highlightSearchStr($result['name'], $term)."<br>";
                        $item_array['label'] .= "<div class='search_result_description'> ";
                        $item_array['label'] .= "</div></div></div></a>";
                    }
                } elseif ($type == $this->container->getParameter('SOCIAL_ENTITY_REGION')) {
                    $item_array['country']    = $result['country_code'];
                    $item_array['countryName']= $result['name'];
                    $item_array['type']       = 'region';
                    if($from_mobile == 0){
                        $links                = '';
                        $popular              = intval($result['entity_popularity']);
                        $popularclass         = "";
                        if ($popular == 1) $popularclass         = " popular";
                        $item_array['locationId']    = 0;
                        $item_array['name']   = $item_array['value'] = $result['name'];
                        $item_array['label']  = "<a href='".$links."' title='".$result['name']."'><div class='search_result_container$popularclass'> <div class='search_result'>"
                        ."<img class='search_result_image1' src='".$this->container->get("TTRouteUtils")->generateMediaURL('/media/images/search/searchfor_static1.png')."' alt='search result image'>";
                        $item_array['label']  .= $this->utils->highlightSearchStr($result['name'], $term)."<br>";
                        $item_array['label']  .= "<div class='search_result_description'> ";
                        $item_array['label']  .= "</div></div></div></a>";
                    }
                }
                $Results[]            = $item_array;
            }
        }
        return $Results;
    }
    
    public function getFlightAutocompleteQuery($options)
    {
        $Results = array();
        $term    = strtolower($options['term']);
        $term    = ltrim($term);
        $term    = rtrim($term);
        
        $srch_options = array(
            'limit' => $options['limit'],
            'from' => 0,
            'term' => $term
        );
        if(isset($options['from_mobile'])){
            $from_mobile = $options['from_mobile'];
        }else{
            $from_mobile = 1;
        }
        
        $url_source = ' getFlightAutocompleteQuery- getAirportsSearch - Mobile';
        $queryStringResult = $this->container->get('ElasticServices')->getAirportsSearch( $srch_options, $url_source );
        $Result = $queryStringResult[0];
        $retDoc_airport['aggregations'] = $queryStringResult[2];
        $res             = array();
        $grouping_arr    = array();
        $aggregation     = $retDoc_airport['aggregations']['cityId']['buckets'];
        foreach ($aggregation as $agg) {
            if ($agg['doc_count'] > 0) {
                $res[] = $agg['key'];
            }
        }
        foreach ($Result as $document) {
            $city_id = $document['_source']['location']['city']['id'];
            $grouping_arr['c'.$city_id][] = $document['_source'];
        }
        $count    = 0;
        $item_array2   = array();
        if (sizeof($grouping_arr) > 0) {
            $cityIdList = array();
            foreach ($grouping_arr as $key => $value) {
                $city_id       = str_replace('c', '', $key);
                $cityInfo      = $this->get('CitiesServices')->worldcitiespopInfo($city_id);
                if (!$cityInfo) continue;
                $countrycode   = $cityInfo[0]->getCountryCode();
                $statecode     = $cityInfo[0]->getStateCode();
                $state_array   = $this->container->get('CitiesServices')->worldStateInfo($countrycode, $statecode);
                $country_array = $this->container->get('CmsCountriesServices')->countryGetInfo($countrycode);
                $cityName      = $this->utils->htmlEntityDecode($cityInfo[0]->getName());
                if(isset($state_array[0])){
                    $stateName     = $state_array[0]->getStateName();
                }
                $countryName   = $this->utils->htmlEntityDecode($country_array->getName());
                $mainGroup     = "<div class='search_result_container'> <div class='search_result'>".$cityName.", ".$stateName.", ".$countryName."</div></div>";
                $Groupes       = $this->container->get('ElasticServices')->GetAllAirportCityElastic($city_id,$url_source);
                $values        = $Groupes['hits']['hits'];
                
                foreach ($values as $group) {
                    if ($city_id == $group['_source']['location']['city']['id']) {
                        if($from_mobile == 0){
                            $label = '';
                            if ($count == 0) {
                                $label = $mainGroup;
                                $count = 2;
                            }
                            $links = "";
                            
                            $label .= "<a class='astyle' href='".$links."' title='".$group['_source']['name']."'><div class='subcategairports'><span class=''><img class='flecheenter' src='".$this->container->get("TTRouteUtils")->generateMediaURL('/media/images/autocomparrow.png')."' alt='arrowblue'/></span>".$group['_source']['code']." , ".$this->utils->highlightSearchStr($group['_source']['name'], $term)."</div></a>";
                            
                            $item_array2 = array(
                                'name' => $group['_source']['name'],
                                'value' => $group['_source']['code'],
                                'label' => $label,
                                'value' => $cityName." (".$group['_source']['code'].")",
                                'cityId' => $city_id,
                                'cityName' => $cityName,
                                'stateName' => $stateName,
                                'countryName' => $countryName,
                                'airport_code' =>$group['_source']['code']
                            );  
                            $Results[] = $item_array2; 
                            $mainGroup = '';
                            $item_array2   = array();
                            $count     = 0;
                        }else{
                            $item_array2[] = array(
                                'name' => $group['_source']['name'],
                                'airport_code' => $group['_source']['code']
                            );
                        }
                    }
                }
                if($from_mobile == 1){
                    $Results[] = array('type' => 'city', 'id' => $city_id, 'name' => $cityName.", ".$stateName.", ".$countryName,'airports' => $item_array2);
                    if(!in_array($city_id, $cityIdList)){
                        $item_array2    = array();
                        $cityIdList[] = $city_id;
                    }
                }
            }
        }
        return $Results;
    }
    
    public function getThingsToDoAutocompleteQuery( $options )
    {
        $Results = array(); 
        
        $url_source = 'searchThingsToDo - getThingsToDoSearch - URL: '.$options['route'];
        $queryStringResult = $this->container->get('ElasticServices')->getThingsToDoSearch( $options, $url_source );
        $all_results = $queryStringResult[0];
        $suggest_res['suggest'] = $queryStringResult[3];
        
        
        if(isset($suggest_res['suggest']['simple_phrase'][0]['options'][0])){
            $all_suggest = $suggest_res['suggest']['simple_phrase'][0]['options'];
        }else{
            $all_suggest = array();
        }
        if(empty($all_results) && empty($all_suggest)){
            $item_array['label']  = "<div class='search_result_container'> <div class='search_result'>".$this->translator->trans('No Results')."<br></div></div>";
        }else{
            if(!empty($all_results)){
                foreach ($all_results as $document) {
                    $name          = $this->utils->htmlEntityDecode($document['_source']['name']);
                    $links         = $this->utils->generateLangURL($options['lang'],'/'.$document['_source']['alias']);
                    $item_array['id']     = intval($document['_source']['id']);
                    
                    $item_array['name']   = $name;
                    $item_array['value']  = $name;
                    if($options['route'] == 'web'){
                        $item_array['label']  = "<a href='".$links."' title='".$name."'><div class='search_result_container'> <div class='search_result'>";
                        $item_array['label']  .= $name."<br>";
                        $item_array['label']  .= "</div></div></a>";
                    
                        $item_array['links'] = $links;
                    }
                    $Results[] = $item_array;
                }
            }else{
                $item_array['label']  = "<div class='search_result_container'><div class='search_result'>".$this->translator->trans('Did you mean ?')." </div></div>";
                $Results[] = $item_array;
                foreach ($all_suggest as $suggest) {
                    $name          = $this->utils->htmlEntityDecode($suggest['text']);
                    $suggestName = $suggest['highlighted'];
                    $item_array['name']   = $name;
                    $item_array['value']  = $name;
                    $item_array['isSuggestion']  = true;
                    if($options['route'] == 'web'){
                        $item_array['label']  = "<a title='".$name."'><div class='search_result_container'> <div class='search_result'>";
                        $item_array['label']  .= $suggestName."<br>";
                        $item_array['label']  .= "</div></div></a>";
                    }
                    $Results[] = $item_array;
                }
            }
        }
        return $Results;
    }
    
    public function getSearchLocalityQuery( $options )
    {
        $Results = array();
        $countryCode = $options['countryCode']; 
        $term = $options['term'];
        $limit = $options['limit']; 
        $lang = $options['lang'];
        $routepath = $options['route'];
        $onlyCity = (isset($options['onlyCity']))? intval($options['onlyCity']) :1;

        if( $term != '' )
        {
            $srch_options = array
            (
                'term' => $term,
                'onlyCity' => $onlyCity
            );
            if( isset($countryCode) && $countryCode != '' )
            {
                $srch_options['countryCode'] = $countryCode;
            }

            $queryStringResult = $this->container->get('ElasticServices')->getLocationSearch( $srch_options, $routepath );
            $result_list = $queryStringResult[0];
            foreach ($result_list as $document)
            {
                $item_array['id']          = $document['_source']['id'];
                $item_array['fullName']    = $item_array['name'] = $item_array['value'] = $document['_source']['name'];
                $item_array['stateCode']   = ( isset($document['_source']['stateCode']) && $document['_source']['stateCode']!='')?$document['_source']['stateCode']:'';
                $item_array['countryCode'] = $document['_source']['contryCode'];
                if(isset($document['_source']['type']) && $document['_source']['type'] == 'country'){
                    
                    if($routepath == 'web'){
                        $url_source = 'searchLocalityAction - getHotelSearch(country) - URL: '.$routepath;
                        $queryStringResult = $this->container->get('ElasticServices')->getHotelSearch( $srch_options, $url_source );
                        $count_hotel = $queryStringResult[1];
                        $item_array['hotellink']       = $count_hotel;
                        
                        $url_source = 'searchLocalityAction - getPhotosVideosSearch(country) - URL: '.$routepath;
                        $queryStringResultMedia = $this->container->get('ElasticServices')->getPhotosVideosSearch( $srch_options, $url_source );
                        $count_media = $queryStringResultMedia[1];
                        if ($count_media > 0) $item_array['medialink'] = $this->container->get("TTMediaUtils")->returnSearchMediaLink($lang, $item_array['name'], '', 'a', 1, 0);
                        $item_array['restaurantslink'] = 0;
                        
                        $item_array['label'] = "<div class='search_result_container'> <div class='search_result'>"
                        ."<img class='search_result_image' src='".$this->container->get("TTRouteUtils")->generateMediaURL('/media/images/search/location_search.png')."' alt='search result image'>";
                        $item_array['label'] .= $this->utils->highlightSearchStr($document['_source']['name'], $term)."<br>";
                        $item_array['label']     .= "</div></div>";
                        $item_array['city']      = $document['_source']['name'];
                        $item_array['contryC']   = $document['_source']['contryCode'];
                        $item_array['type']      = 3;
                        $item_array['pid']       = 'CO_'.$document['_source']['contryCode'];
                        $channelcount            = $this->get('ChannelServices')->getCityChannelCount($document['_source']['contryCode'], '', 0);
                        $item_array['chlink']    = ($channelcount > 0) ? 'co/'.$document['_source']['contryCode'] : '';
                        $todoLink                = $this->container->get('ThingsToDoServices')->getThingstodoCountryAliasLink($document['_source']['contryCode'], '');
                        if ($todoLink && $todoLink[0]) {
                            $retarr['thlink'] = $this->utils->generateLangURL($todoLink[0]->getAlias());
                        }
                    }
                }
                else if (isset($document['_source']['type']) && $document['_source']['type'] == 'state')
                {
                    $country_array = $this->container->get('CmsCountriesServices')->countryGetInfo( $item_array['countryCode'] );
                    if( $country_array )
                    {
                        $countryName            = $this->utils->htmlEntityDecode( $country_array->getName() );
                        $item_array['fullName'] = $item_array['fullName'] .', '.$countryName;
                    }
                    if($routepath == 'web'){
                        $url_source = 'searchLocalityAction - getHotelSearch(state) - URL: '.$routepath;
                        $queryStringResult = $this->container->get('ElasticServices')->getHotelSearch( $srch_options, $url_source );
                        $count_hotel = $queryStringResult[1];
                        $item_array['hotellink'] = $count_hotel;
                        $item_array['restaurantslink'] = 0;

                        $srch_options = array
                        (
                            'countryCode' => $document['_source']['contryCode'],
                            'term' => $term
                        );

                        $url_source = 'searchLocalityAction - getPhotosVideosSearch(state) - URL: '.$routepath;
                        $queryStringResultMedia = $this->container->get('ElasticServices')->getPhotosVideosSearch( $srch_options, $url_source );
                        $count_media = $queryStringResultMedia[1];

                        $contryInfo      = $this->container->get('CmsCountriesServices')->countryGetInfo($document['_source']['contryCode']);
                        $countryName     = $contryInfo->getName();
                        $item_array['label'] = "<div class='search_result_container'> <div class='search_result'>"
                        ."<img class='search_result_image' src='".$this->container->get("TTRouteUtils")->generateMediaURL('/media/images/search/location_search.png')."' alt='search result image'>";
                        $item_array['label'] .= $this->utils->highlightSearchStr($document['_source']['name'], $term)."<br>";
                        $item_array['label'] .= "<div class='search_result_description'>".$countryName."</div>";
                        $item_array['label'] .= "</div></div>";
                        $item_array['value'] = $document['_source']['name'].', '.$countryName;
                        $retarr['name']      = $document['_source']['name'];
                        if ($count_media > 0) $retarr['medialink'] = $this->container->get("TTMediaUtils")->returnSearchMediaLink($lang, $retarr['name'], '', 'a', 1, 0);
                        $item_array['city']      = $document['_source']['name'];
                        $item_array['state']     = $document['_source']['stateCode'];
                        $item_array['contryC']   = $document['_source']['contryCode'];
                        $item_array['type']      = 2;
                        $item_array['pid']       = 'S_'.$retarr['stateCode'].'_'.$document['_source']['contryCode'];
                        $channelcount        = $this->container->get('ChannelServices')->getCityChannelCount($document['_source']['contryCode'], '', 0);
                        $item_array['chlink']    = ($channelcount > 0) ? 'co/'.$document['_source']['contryCode'] : '';
                        $todoLink            = $this->container->get('ThingsToDoServices')->getThingstodoCountryAliasLink($document['_source']['contryCode'], $document['_source']['stateCode']);
                        if ($todoLink && $todoLink[0]) {
                            $item_array['thlink'] = $this->utils->generateLangURL($todoLink[0]->getAlias());
                        }
                    }
                }
                else if (isset($document['_source']['type']) && $document['_source']['type'] == 'city')
                {
                    $state_array   = $this->container->get('CitiesServices')->worldStateInfo( $item_array['countryCode'], $item_array['stateCode'] );
                    if ($state_array && sizeof($state_array))
                    {
                        $countryName = '';
                        if( isset($state_array[1]) && $state_array[1] )
                        {
                            $country_array = $state_array[1];
                            $countryName = ' '.$this->utils->htmlEntityDecode($country_array->getName());
                        }
                        $stateName              = $this->utils->htmlEntityDecode($state_array[0]->getStateName());
                        $item_array['fullName'] = $item_array['fullName'] .', '.$stateName.$countryName;
                    }
                    else
                    {
                        $country_array = $this->container->get('CmsCountriesServices')->countryGetInfo( $item_array['countryCode'] );
                        if( $country_array )
                        {
                            $countryName            = $this->utils->htmlEntityDecode( $country_array->getName() );
                            $item_array['fullName'] = $item_array['fullName'] .', '.$countryName;
                        }
                    }
                    
                    if($routepath == 'web'){
                        $srch_options = array
                        (
                            'cityId' => $document['_source']['id'],
                            'term' => $term
                        );

                        $url_source = 'searchLocalityAction - getHotelSearch(city) - URL: '.$routepath;
                        $queryStringResult = $this->container->get('ElasticServices')->getHotelSearch( $srch_options, $url_source );
                        $count_hotel = $queryStringResult[1];

                        $item_array['hotellink'] = $count_hotel;
                        $item_array['restaurantslink'] = 0;
                
                        $url_source = 'searchLocalityAction - getPhotosVideosSearch(city) - URL: '.$routepath;
                        $queryStringResultMedia = $this->container->get('ElasticServices')->getPhotosVideosSearch( $srch_options, $url_source );
                        $count_media = $queryStringResultMedia[1];
                        
                        $item_array['label'] = "<div class='search_result_container'> <div class='search_result'>"
                        ."<img class='search_result_image' src='".$this->container->get("TTRouteUtils")->generateMediaURL('/media/images/search/location_search.png')."' alt='search result image'>";
                        $item_array['label'] .= $this->utils->highlightSearchStr($document['_source']['name'], $term)."<br>";
                        $item_array['label']       .= "<div class='search_result_description'>".$stateName.$countryName."</div>"
                        ."</div></div>";
                        $item_array['value']       = $document['_source']['name'].', '.$stateName.$countryName;
                        $item_array['name']        = $document['_source']['name'];
                        if ($count_media > 0) $item_array['medialink']   = $this->container->get("TTMediaUtils")->returnSearchMediaLink($this->data['LanguageGet'], $item_array['name'], '', 'a', 1, 0);
                        $item_array['city']        = $document['_source']['name'];
                        $item_array['type']        = 1;
                        $item_array['countryId']   = $document['_source']['stateCode'];
                        $item_array['countryCode'] = $document['_source']['contryCode'];
                        $item_array['id']          = $document['_source']['id'];
                        $item_array['pid']         = 'C_'.$item_array['id'];
                        $channelcount              = $this->container->get('ChannelServices')->getCityChannelCount('', '', $item_array['id']);
                        $item_array['chlink']      = ($channelcount > 0) ? 'ci/'.$item_array['id'] : '';

                    }
                    
                }
                $lkname           = $item_array['name'];
                $lkname           = str_replace('-', '+', $lkname);
                $item_array['lkname'] = $lkname;
                $item_array['type'] = $document['_source']['type'];
                
                $Results[] = $item_array;
            }
        }
        
        return $Results;
    }
    
    public function getChannelAutocompleteQuery( $options )
    {
        $Results = array();
        $optionsCh = array('orderby' => 'id', 'order' => 'd', 'channel_name'=>$options['term'] );
        if ($options['type'] == 1) {
            $optionsCh['city_id'] = $options['cityId'] ;
        } elseif ($options['type'] == 2) {
            $optionsCh['country'] = $options['contryC'];
        } elseif ($options['type'] == 3) {
            $optionsCh['country'] = $options['contryC'];
        }
        
        $url_source = 'searchForChannelAction - getCityChannelListElastic - URL: '.$options['route'];
        $queryStringResultChannel = $this->get('ElasticServices')->getCityChannelListElastic($optionsCh,$url_source);
        $ret_channels   = $queryStringResultChannel[0];
        
        $ret = array();
        foreach ($ret_channels as $document) {
            $item_array['value'] = $document['_source']['name'];
            if($options['route'] == 'web'){
                $links           = $this->utils->generateLangURL('/channel/'.$document['_source']['url'], 'channels');
                $item_array['label'] = "<a href='".$links."' title='".$document['_source']['name']."'><div class='search_result_container'> <div class='search_result'>";
                if ($document['_source']['media']['images']['logo']) {
                    $thumb_lnk = $this->container->get("TTRouteUtils")->generateMediaURL("/media/channel/".$document['_source']['id']."/thumb/".$document['_source']['media']['images']['logo']);
                    $item_array['label'] .= "<img class='search_result_image1' src='$thumb_lnk' alt='search result image'>";
                } else {
                    $item_array['label'] .= "<img class='search_result_image1' src='".$this->container->get("TTRouteUtils")->generateMediaURL('/media/tubers/xsmall_tuber.jpg')."' alt='search result image'>";
                }
                $item_array['label'] .= $this->utils->highlightSearchStr($document['_source']['name'], $options['term'])."<br>";
                $item_array['label'] .= "</div></div></a>";
                $item_array['links'] = $links;
            }
            $Results[]           = $item_array;
        }
        return $Results;
    }
}
