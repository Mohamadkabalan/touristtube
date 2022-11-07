<?php

namespace TTBundle\Services;

use \TTBundle\Model\ElasticSearchSC;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Repository\ElasticHotelRepository;
use TTBundle\Repository\ElasticDictionaryRepository;
use TTBundle\Repository\ElasticCitiesRepository;
use TTBundle\Repository\ElasticCountryRepository;
use TTBundle\Repository\ElasticHotelsCitiesRepository;
use TTBundle\Repository\ElasticAirportsRepository;
use TTBundle\Repository\ElasticDealsRepository;
use TTBundle\Repository\ElasticPoiRepository;
use TTBundle\Repository\ElasticMediaRepository;
use TTBundle\Repository\ElasticChannelsRepository;
use TTBundle\Repository\ElasticLocationRepository;
use TTBundle\Repository\ElasticThingsToDoRepository;
use TTBundle\Repository\ElasticDiscoverHotelRepository;
use TTBundle\Utils\Utils;
use Symfony\Bridge\Monolog\Logger;

class ElasticServices {
    
    protected $utils;
    protected $logger;
    
    public function __construct(ContainerInterface $container, Utils $utils, Logger $logger)
    {
        $this->container = $container;
        $this->utils         = $utils;
        $this->logger        = $logger;
    }
    
    
    public function fetch_aggregations(&$elastic_result, $aggregated_fields)
    {
        if (!$aggregated_fields)
            return;
            
            if (!is_array($aggregated_fields))
                $aggregated_fields = array($aggregated_fields);
                
                $aggregations = array();
                
                foreach ($aggregated_fields as $aggregated_field) {
                    $aggregations[$aggregated_field] = array();
                    $elastic_result['aggregations'][$aggregated_field]['buckets'] = array();
                }
                
                foreach ($elastic_result['hits']['hits'] as $hit) {
                    foreach ($aggregated_fields as $aggregated_field) {
                        $tmp_value = $hit['_source'];
                        $aggregated_field_parts = explode('.', $aggregated_field);
                        foreach ($aggregated_field_parts as $aggregated_field_part) {
                            if (is_array($tmp_value)) {
                                if (array_key_exists($aggregated_field_part, $tmp_value)) {
                                    $tmp_value = $tmp_value[$aggregated_field_part];
                                } else {
                                    foreach ($tmp_value as $tmp_sub_value) {
                                        if (array_key_exists($aggregated_field_part, $tmp_sub_value)) {
                                            if (array_key_exists($tmp_sub_value[$aggregated_field_part], $aggregations[$aggregated_field])) {
                                                $aggregations[$aggregated_field][$tmp_sub_value[$aggregated_field_part]] ++;
                                            } else {
                                                $aggregations[$aggregated_field][$tmp_sub_value[$aggregated_field_part]] = 1;
                                            }
                                        }
                                    }
                                    $tmp_value = null;
                                }
                            } else {
                                if (array_key_exists($aggregated_field, $hit['_source']))
                                    $aggregations[$aggregated_field][$hit['_source'][$aggregated_field]] ++;
                                    
                                    $tmp_value = null;
                            }
                            
                            if ($tmp_value && is_array($tmp_value) && array_key_exists($aggregated_field_part, $tmp_value)) {
                                $tmp_value = $tmp_value[$aggregated_field_part];
                            }
                        }
                        
                        if ($tmp_value) {
                            if (array_key_exists($tmp_value, $aggregations[$aggregated_field]))
                                $aggregations[$aggregated_field][$tmp_value] ++;
                                else
                                    $aggregations[$aggregated_field][$tmp_value] = 1;
                        }
                    }
                }
                
                foreach ($aggregated_fields as $aggregated_field) {
                    arsort($aggregations[$aggregated_field]);
                }
                
                foreach ($aggregated_fields as $aggregated_field) {
                    foreach ($aggregations as $aggregated_field => $aggregation_details) {
                        foreach ($aggregation_details as $key => $doc_count) {
                            $elastic_result['aggregations'][$aggregated_field]['buckets'][] = array('key' => $key, 'doc_count' => $doc_count);
                        }
                    }
                }
    }
    
    public function hotelSearch(ElasticSearchSC $elasticSearchSC,$language='en')
    {
        $criteria = array('size' => 10,'lang'=>$language);
        if(!$elasticSearchSC->getCriteria()) $elasticSearchSC->setCriteria($criteria);
        $criteriaArray = $elasticSearchSC->getCriteria();
        if(!isset($criteriaArray['page_src']) || $criteriaArray['page_src'] != $this->container->getParameter('hotels')['page_src']['hotels']){
            $elasticSearchSC = $this->getElasticInfo("hrshotels", $elasticSearchSC);
        }else{
            $elasticSearchSC = $this->getElasticInfo("hotels", $elasticSearchSC);
        }
        //
        if(!$elasticSearchSC->getTerm() && !$elasticSearchSC->getCriteria())
            return null;
            //
            $ElasticHotelRepository = new ElasticHotelRepository($this->logger);
            $result = $ElasticHotelRepository->searchDD($elasticSearchSC);
            
            return $result;
    }
    
    public function hotelStaticSearch(ElasticSearchSC $elasticSearchSC)
    {
        $criteria = array('size' => 10);
        if(!$elasticSearchSC->getCriteria()) $elasticSearchSC->setCriteria($criteria);
        //
        $elasticSearchSC = $this->getElasticInfo("dictionary", $elasticSearchSC);
        //
        if(!$elasticSearchSC->getTerm() && !$elasticSearchSC->getCriteria())
            return null;
            //
            $ElasticDictionaryRepository = new ElasticDictionaryRepository($this->logger);
            $result = $ElasticDictionaryRepository->searchDD($elasticSearchSC);
            return $result;
    }
    
    public function countrySearch(ElasticSearchSC $elasticSearchSC)
    {
        $criteria = array('size' => 10);
        if(!$elasticSearchSC->getCriteria()) $elasticSearchSC->setCriteria($criteria);
        //
        $elasticSearchSC = $this->getElasticInfo("cities", $elasticSearchSC);
        //
        if(!$elasticSearchSC->getTerm() && !$elasticSearchSC->getCriteria())
            return null;
            //
            $ElasticCountryRepository = new ElasticCountryRepository($this->logger);
            $result = $ElasticCountryRepository->searchDD($elasticSearchSC);
            return $result;
    }
    
    public function citiesSearch(ElasticSearchSC $elasticSearchSC)
    {
        $criteria = array('size' => 10);
        //
        if(!$elasticSearchSC->getCriteria()) $elasticSearchSC->setCriteria($criteria);
        $elasticSearchSC = $this->getElasticInfo("cities", $elasticSearchSC);
        //
        if(!$elasticSearchSC->getTerm() && !$elasticSearchSC->getCriteria())
            return null;
            //
            $ElasticCitiesRepository = new ElasticCitiesRepository($this->logger);
            $result = $ElasticCitiesRepository->searchDD($elasticSearchSC);
            return $result;
    }
    
    public function hotelsCitiesSearch(ElasticSearchSC $elasticSearchSC, $language='en')
    {
        $criteria = array('size' => 10,'lang'=>$language);
        $criteriaArray = $elasticSearchSC->getCriteria();
        //
        if(!$elasticSearchSC->getCriteria()) $elasticSearchSC->setCriteria($criteria);
        //
        if(!isset($criteriaArray['page_src']) || $criteriaArray['page_src'] != $this->container->getParameter('hotels')['page_src']['hrs']){
            $elasticSearchSC = $this->getElasticInfo("hotelsCities", $elasticSearchSC);
        }else{
            $elasticSearchSC = $this->getElasticInfo("hrshotelscities", $elasticSearchSC);
        }
        //
        if(!$elasticSearchSC->getTerm() && !$elasticSearchSC->getCriteria())
            return null;
            //
            $ElasticHotelsCitiesRepository = new ElasticHotelsCitiesRepository($this->logger);
            $result = $ElasticHotelsCitiesRepository->searchDD($elasticSearchSC);
            
            return $result;
    }
    
    public function airportsSearch(ElasticSearchSC $elasticSearchSC)
    {
        $criteria = array('size' => 10);
        if(!$elasticSearchSC->getCriteria()) $elasticSearchSC->setCriteria($criteria);
        $elasticSearchSC = $this->getElasticInfo("airports", $elasticSearchSC);
        //
        //
        if(!$elasticSearchSC->getTerm() && !$elasticSearchSC->getCriteria())
            return null;
            //
            $ElasticAirportsRepository = new ElasticAirportsRepository($this->logger);
            $result = $ElasticAirportsRepository->searchDD($elasticSearchSC);
            return $result;
    }
    
    public function dealsSearch(ElasticSearchSC $elasticSearchSC,$language='en')
    {
        $criteria = array('size' => 10,'lang'=>$language);
        if(!$elasticSearchSC->getCriteria()) $elasticSearchSC->setCriteria($criteria);
        $elasticSearchSC = $this->getElasticInfo("deals", $elasticSearchSC);
        //
        //
        if(!$elasticSearchSC->getTerm() && !$elasticSearchSC->getCriteria())
            return null;
            //
            $ElasticDealsRepository = new ElasticDealsRepository($this->logger);
            $result = $ElasticDealsRepository->searchDD($elasticSearchSC);
            return $result;
    }
    
    public function dealsCitySearch(ElasticSearchSC $elasticSearchSC,$language='en')
    {
        $criteria = array('size' => 10,'lang'=>$language);
        if(!$elasticSearchSC->getCriteria()) $elasticSearchSC->setCriteria($criteria);
        $elasticSearchSC = $this->getElasticInfo("deals", $elasticSearchSC);
        //
        //
        if(!$elasticSearchSC->getTerm() && !$elasticSearchSC->getCriteria())
            return null;
            //
            $ElasticDealsRepository = new ElasticDealsRepository($this->logger);
            $result = $ElasticDealsRepository->searchDealCity($elasticSearchSC);
            return $result;
    }
    
    public function poiSearch(ElasticSearchSC $elasticSearchSC,$language='en')
    {
        $elasticSearchSC = $this->getElasticInfo("poi", $elasticSearchSC);
        //
        if(!$elasticSearchSC->getTerm() && !$elasticSearchSC->getCriteria())
            return null;
            //
            $ElasticPoiRepository = new ElasticPoiRepository($this->logger);
            $result = $ElasticPoiRepository->searchDD($elasticSearchSC);
            return $result;
    }
    
    public function mediaSearch(ElasticSearchSC $elasticSearchSC,$language='en')
    {
        $elasticSearchSC = $this->getElasticInfo("media", $elasticSearchSC);
        
        //
        if(!$elasticSearchSC->getTerm() && !$elasticSearchSC->getCriteria())
            return null;
            //
            $ElasticMediaRepository = new ElasticMediaRepository($this->logger);
            $result = $ElasticMediaRepository->searchDD($elasticSearchSC);
            return $result;
    }
    
    public function channelSearch(ElasticSearchSC $elasticSearchSC,$language='en')
    {
        $elasticSearchSC = $this->getElasticInfo("channels", $elasticSearchSC);
        
        //
        if(!$elasticSearchSC->getTerm() && !$elasticSearchSC->getCriteria())
            return null;
            //
            $ElasticChannelsRepository = new ElasticChannelsRepository($this->logger);
            $result = $ElasticChannelsRepository->searchDD($elasticSearchSC);
            return $result;
    }
    
    public function locationSearch(ElasticSearchSC $elasticSearchSC,$language='en')
    {
        $elasticSearchSC = $this->getElasticInfo("location", $elasticSearchSC);
        
        //
        if(!$elasticSearchSC->getTerm() && !$elasticSearchSC->getCriteria())
            return null;
            //
            $ElasticLocationRepository = new ElasticLocationRepository($this->logger);
            $result = $ElasticLocationRepository->searchDD($elasticSearchSC);
            return $result;
    }
    
    public function thingsToDoSearch(ElasticSearchSC $elasticSearchSC,$language='en')
    {
        $elasticSearchSC = $this->getElasticInfo("thingstodo", $elasticSearchSC);
        
        //
        if(!$elasticSearchSC->getTerm() && !$elasticSearchSC->getCriteria())
            return null;
            //
            $ElasticThingsToDoRepository = new ElasticThingsToDoRepository($this->logger);
            $result = $ElasticThingsToDoRepository->searchDD($elasticSearchSC);
            return $result;
    }
    
    public function discoverHotelSearch(ElasticSearchSC $elasticSearchSC,$language='en')
    {
        $criteria = array('size' => 10,'lang'=>$language);
        if(!$elasticSearchSC->getCriteria()) $elasticSearchSC->setCriteria($criteria);
        $elasticSearchSC = $this->getElasticInfo("dischotels", $elasticSearchSC);
        
        //
        if(!$elasticSearchSC->getTerm() && !$elasticSearchSC->getCriteria())
            return null;
            //
            $ElasticDiscoverHotelRepository = new ElasticDiscoverHotelRepository($this->logger);
            $result = $ElasticDiscoverHotelRepository->searchDD($elasticSearchSC);
            return $result;
    }
    
    public function getElasticInfo($indexName, ElasticSearchSC $elasticSearchSC) 
    {
        $retElastic = array();
        if($this->container->hasParameter('ENVIRONMENT') && $this->container->getParameter('ENVIRONMENT') == 'production'){
            $elasticInfo = $this->container->getParameter('elastic')['prod'];
        }else{
            $elasticInfo = $this->container->getParameter('elastic')['dev'];
        }
        $host = $elasticInfo['indexes'][$indexName]['host'];
        if(!$host){
            $host = $elasticInfo['host'];
        }
        $port = $elasticInfo['indexes'][$indexName]['port'];
        if(!$port){
            $port = $elasticInfo['port'];
        }
        $index = $elasticInfo['indexes'][$indexName]['index'];
        $docType = $elasticInfo['indexes'][$indexName]['docType'];
        $hosts = $elasticInfo['hosts'];
        
        $retElastic = array("host" => $host, "port" => $port, "index"=>$index, "docType"=>$docType, "hosts"=>$hosts);
        //
        if(!$elasticSearchSC->getHost()) $elasticSearchSC->setHost($retElastic['host']);
        if(!$elasticSearchSC->getPort()) $elasticSearchSC->setPort($retElastic['port']);
        if(!$elasticSearchSC->getIndex()) $elasticSearchSC->setIndex($retElastic['index']);
        if(!$elasticSearchSC->getDocType()) $elasticSearchSC->setDocType($retElastic['docType']);
        if(!$elasticSearchSC->getHosts()) $elasticSearchSC->setHosts($retElastic['hosts']);
        //
        $criteria = array('size' => 10);
        if(!$elasticSearchSC->getCriteria()) $elasticSearchSC->setCriteria($criteria);
        //
        return $elasticSearchSC;
    }
    
    public function prepareElasticQueryString($term)
    {
        $query = trim($term);
        if($query != ''){
            $criteria = "(*" . str_replace(" ", "* AND *", $query) . "*)";
            $criteria .= " OR (" . str_replace(" ", "~ AND ", $query) . "~)";
        }
        return $criteria;
    }
    
    public function checkElasticErrorLog($queryResults)
    {
        if( isset($queryResults['error_encountered']) && $queryResults['error_encountered'] ){
            $guid = str_replace('-', '', $this->utils->GUID());
            if(isset($queryResults['criteria'])){
                $queryResults['criteria'] = array();
            }
            $criteria = $this->utils->flatten_array($queryResults['criteria']);
            $tt_exceptions = $queryResults['tt_exceptions'];
            $url_source = $queryResults['url_source'];
            
            $this->addErrorLog("$url_source [$guid] ($criteria):: Encountered ".count($tt_exceptions).' exception'.(count($tt_exceptions)
                == 1 ? '' : 's'));
            
            foreach ($tt_exceptions as $indx => $tt_exception) {
                $traceURL = $tt_exception['traceURL'];
                $this->addErrorLog("$traceURL [$guid]:: Exception {indx} type:: {ex_type} message:: {ex_messsage}", array('indx' => $indx, 'ex_type' => $tt_exception['type'], 'ex_message' => $tt_exception['exception']));
            }
            
        }
    }
    
    /**
     * Log message with params, optionally cleaning the params (currently, masking credit card info, if they exist).
     * @param String $message
     * @param array $params Expected key/value pairs, with keys occurring in message, as {key}, {key} will be replaced with its associated value in $params in the logged string.
     * @param boolean $cleanParams
     */
    public function addErrorLog($message, $params = array(), $cleanParams = false)
    {
        $this->prepareLogParameters($params, $cleanParams);
        
        $logger = $this->get('logger');
        $logger->error("\nUser {userId} - ".$message, $params);
    }
    
    public function GetAllAirportCityElastic($city,$url_source=NULL)
    {
        $ElasticSearchSC   = new ElasticSearchSC();
        $criteria          = array(
            'cityId' => $city
        );
        $ElasticSearchSC->setCriteria($criteria);
        $ElasticSearchSC->setUrlSource($url_source);
        $queryStringResult = json_decode($this->airportsSearch($ElasticSearchSC), true);
        $this->checkElasticErrorLog($queryStringResult);
        $retDoc            = $queryStringResult;
        if(!$retDoc || empty($retDoc)){
            $retDoc['hits']['hits'] = array();
            $retDoc['hits']['total'] = 0;
        }
        return $retDoc;
    }
    
    public function getRelatedImages( $imageInfo, $limit = 12, $types = "a", $check_user_id = true,$url_source=NULL, $isUserLoggedIn =false )
    {
        $imageTitle   = str_replace('-', ' ', $this->utils->cleanTitle( $imageInfo['v_title'] ));
        $userImage    = $imageInfo['v_userid'];
        $ImageCityId  = $imageInfo['v_cityid'];
        $ElasticSearchSC = new ElasticSearchSC();
        $criteria = array(
            'size' => $limit,
            'type' => $types,
            'cityId' => $ImageCityId,
            'imageTitle' => $imageTitle,
            'oldQuery' => 1
        );
        
        if ( $isUserLoggedIn && $check_user_id) 
        {
            $criteria['userId'] = $userImage;
        }
        
        $ElasticSearchSC->setCriteria($criteria);
        $ElasticSearchSC->setUrlSource($url_source);
        $queryStringResult = json_decode($this->mediaSearch($ElasticSearchSC), true);
        $this->checkElasticErrorLog($queryStringResult);
        $retDoc   = $queryStringResult;
        if(!$retDoc || empty($retDoc)){
            $retDoc['hits']['hits'] = array();
            $retDoc['hits']['total'] = 0;
        }
        if ($retDoc['hits']['total'] == 0 && $check_user_id) return $this->getRelatedImages($imageInfo, $limit, $types, false,$url_source, $isUserLoggedIn);
        return $retDoc['hits']['hits'];
    }
    
    public function getCityChannelListElastic($srch_options,$url_source=NULL)
    {
        $default_opts = array(
            'limit' => 10,
            'page' => 0,
            'from' => 0,
            'channel_name' => '',
            'city_id' => 0,
            'owner_id' => 0,
            'cityName' => '',
            'country' => '',
            'state_name' => '',
            'category' => '',
            'orderby' => 'id',
            'order' => 'a'
        );
        $options      = array_merge($default_opts, $srch_options);
        
        $limit = intval($options['limit']);
        if ($options['from'] == 0) {
            $start = intval($options['page']) * $limit;
        } else {
            $start = intval($options['from']);
        }
        $channel_name = $options['channel_name'];
        $city_id      = intval($options['city_id']);
        $owner_id      = intval($options['owner_id']);
        $country_code = $options['country'];
        $cityName = $options['cityName'];
        $statename    = $options['state_name'];
        $category     = $options['category'];
        $orderby      = $options['orderby'];
        $order        = $options['order'];
        $where        = '';
        $statename    = str_replace('"', '', $statename);
        
        $ElasticSearchSC = new ElasticSearchSC();
        $criteria = array(
            'from' => $start,
            'size' => $limit,
            'cityId' => $city_id,
            'ownerId' => $owner_id,
            'countryCode' => $country_code,
            'cityName' => $cityName,
            'category' => $category,
            'oldQuery' => 1
        );
        if($channel_name){
            $ElasticSearchSC->setTerm($channel_name);
        }
        $ElasticSearchSC->setCriteria($criteria);
        $ElasticSearchSC->setUrlSource($url_source);
        $queryStringResult = json_decode($this->channelSearch($ElasticSearchSC), true);
        $this->checkElasticErrorLog($queryStringResult);
        $retDoc   = $queryStringResult;
        if(!$retDoc || empty($retDoc)){
            $retDoc['hits']['hits'] = array();
            $retDoc['hits']['total'] = 0;
        }
        $res      = $retDoc['hits']['hits'];
        return [$res, $retDoc['hits']['total']];
    }
    
    public function getPoiNearLocation( $srch_options, $url_source=NULL )
    {
        
        $default_opts = array
        (
            'limit' => 10,
            'page' => 0,
            'from' => null,
            'countryCode' => null,
            'cityId' => 0,
            'sortByLat' => null,
            'sortByLon' => null,
            'sortBy' => null,
            'sortGeolocation' => null,
            'search_try' => 1,
            'imageExists' => 1,
            'cityName' => null,
            'oldQuery' => null,
            'aggs' => null,
            'term' => null
        );
        $options = array_merge($default_opts, $srch_options);
        
        $page = $options['page'];
        $limit = $options['limit'];
        
        if( $options['from'] !=null )
        {
            $from = $options['from'];
        } else {
            $from = $page * $limit;
        }
        
        $ElasticSearchSC = new ElasticSearchSC();
        $criteria = array
        (
            'from' => $from,
            'size' => $limit
        );
        
        if( $options['countryCode'] !=null )
        {
            $criteria['countryCode'] = $options['countryCode'];
        }
        
        if( $options['sortByLat'] !=null )
        {
            $criteria['sortByLat'] = $options['sortByLat'];
        }
        
        if( $options['sortByLon'] !=null )
        {
            $criteria['sortByLon'] = $options['sortByLon'];
        }
        
        if( $options['sortBy'] !=null )
        {
            $criteria['sortBy'] = $options['sortBy'];
        }
        
        if( $options['sortGeolocation'] !=null )
        {
            $criteria['sortGeolocation'] = $options['sortGeolocation'];
        }
        
        if( $options['cityName'] !=null )
        {
            $criteria['cityName'] = $options['cityName'];
        }
        
        if( $options['cityId'] !=null )
        {
            $criteria['cityId'] = intval($options['cityId']);
        }
        
        if( $options['oldQuery'] !=null )
        {
            $criteria['oldQuery'] = $options['oldQuery'];
        }
        
        if( $options['imageExists'] !=null )
        {
            $criteria['imageExists'] = $options['imageExists'];
        }
        
        if( $options['aggs'] !=null )
        {
            $criteria['aggs'] = $options['aggs'];
        }
        
        if( $options['term'] !=null && $options['term'] !='' )
        {
            $ElasticSearchSC->setTerm( $options['term'] );
        }
        
        $ElasticSearchSC->setCriteria($criteria);
        
        $ElasticSearchSC->setUrlSource( $url_source );
        $queryStringResult = json_decode($this->poiSearch($ElasticSearchSC), true);
        $this->checkElasticErrorLog($queryStringResult);
        $retDoc   = $queryStringResult;
        if(!$retDoc || empty($retDoc))
        {
            $retDoc['hits']['hits'] = array();
            $retDoc['aggregations'] = array();
            $retDoc['hits']['total'] = 0;
        }
        
        if( !isset($retDoc['aggregations']) )
        {
            $retDoc['aggregations'] = array();
        }
        
        $hits = $retDoc['hits']['hits'];
        $n_hits = $retDoc['hits']['total'];
        $aggregations = $retDoc['aggregations'];
        
        if ( !$hits && $options['search_try']>1 ) {
            $ElasticSearchSC = new ElasticSearchSC();
            $criteria = array
            (
                'from' => $from,
                'size' => $limit
            );
            
            $ElasticSearchSC->setCriteria($criteria);
            
            $ElasticSearchSC->setUrlSource( '(if !$hits):: '.$url_source );
            $queryStringResult = json_decode($this->poiSearch($ElasticSearchSC), true);
            $this->checkElasticErrorLog($queryStringResult);
            $retDoc   = $queryStringResult;
            if(!$retDoc || empty($retDoc))
            {
                $retDoc['hits']['hits'] = array();
                $retDoc['aggregations'] = array();
                $retDoc['hits']['total'] = 0;
            }
            
            if( !isset($retDoc['aggregations']) )
            {
                $retDoc['aggregations'] = array();
            }
            
            $hits = $retDoc['hits']['hits'];
            $n_hits = $retDoc['hits']['total'];
            $aggregations = $retDoc['aggregations'];
        }
        
        return [$hits, $n_hits, $aggregations];
    }

    public function getHotelsInSearch( $srch_options )
    {
        $default_opts = array
        (
            'url_source' => '',
            'routepath' => '',
            'stateCode' => NULL,
            'cityId' => NULL,
            'countryCode' => NULL,
            'sortGeolocation' => NULL,
            'sortByLat' => NULL,
            'sortByLon' => NULL
        );
        $options = array_merge($default_opts, $srch_options);
        
        $lang = ( isset($options['lang']) )?$options['lang']:'en';
        $nearby = intval($options['nearby']);
        $url_source = $options['url_source'];
        $routepath = $options['routepath'];
        $options['cityId'] = ( $options['cityId'] && intval($options['cityId']) !=0 )? $options['cityId']:NULL;
        $options['countryCode'] = ( $options['countryCode'] && $options['countryCode'] !='' )? $options['countryCode']:NULL;
        $options['stateCode'] = ( $options['stateCode'] && $options['stateCode'] !='' )? $options['stateCode']:NULL;
        
        if ( $nearby != 0 )
        {            
            $options['stateCode'] = $options['nearby'] = $options['url_source'] = $options['imageExists'] = $options['oldQuery'] = NULL;
        }
        
        $queryStringResult = $this->getDiscoverHotelSearch( $options, $url_source );
        $count             = intval($queryStringResult[1]);
        $hotelsInResult    = $queryStringResult[0];
        
        if ( $nearby != 0 && !$hotelsInResult )
        {            
            if ( $options['countryCode'] != NULL && $options['cityId'] != NULL ) {
                $options['cityId'] = null;
                $url_source                   = 'allHotelsIn - getDiscoverHotelSearch(!$ret && $country_code) - URL: '.$routepath;
                $queryStringResult = $this->getDiscoverHotelSearch( $options, $url_source );
                $count             = intval($queryStringResult[1]);
                $hotelsInResult    = $queryStringResult[0];
            }
            
            if ( !$hotelsInResult ) {
                $options['cityId'] = null;
                $options['countryCode'] = null;
                $url_source        = 'allHotelsIn - getDiscoverHotelSearch(!$ret) - URL: '.$routepath;
                $queryStringResult = $this->getDiscoverHotelSearch( $options, $url_source );
                $count             = intval($queryStringResult[1]);
                $hotelsInResult    = $queryStringResult[0];
            }
        }
        
        $hotels_array      = array();
        $country_name      = '';
        
        foreach ($hotelsInResult as $item) {
            $item_array = array();
            $locationText = '';
            if (count($item['_source']['media']['images']) > 0) {
                $dimagepath            = 'media/discover/';
                $dimage                = $item['_source']['media']['images'][0]['filename'];
                $image_pa              = $this->container->get("TTMediaUtils")->createItemThumbs($dimage, $dimagepath, 0, 0, 291, 166, 'hotels-in-291166');
                $item_array['image']       = $image_pa;
                $item_array['image_exist'] = true;
            } else {
                $image = $this->container->get('ReviewsServices')->getHotelsDefaultPic($item['_source']['id']);
                if ($image) {
                    $dimagepath            = 'media/discover/';
                    $dimage                = $image->getFilename();
                    $image_pa              = $this->container->get("TTMediaUtils")->createItemThumbs($dimage, $dimagepath, 0, 0, 291, 166, 'hotels-in-291166');
                    $item_array['image']       = $image_pa;
                    $item_array['image_exist'] = true;
                } else {
                    $dimagepath             = 'media/images/';
                    $dimage                 = 'hotel-icon-image3.jpg';
                    $item_array['image']        = $this->container->get("TTMediaUtils")->createItemThumbs($dimage, $dimagepath, 0, 0, 291, 166, 'hotels-in-291166');
                    $item_array['image_exist']  = FALSE;
                    $item_array['image_width']  = '';
                    $item_array['image_height'] = '';
                }
            }

            $item_array['title']    = $this->utils->htmlEntityDecode($item['_source']['name']);
            $item_array['titlealt'] = $this->utils->cleanTitleDataAlt($item['_source']['name']);
            $item_array['link']     = '';
            $locationText       = $item['_source']['location']['address'];
            if (intval($item['_source']['location']['city']['id']) > 0 && $locationText == '') {
                $city_array = $this->container->get('CitiesServices')->worldcitiespopInfo(intval($item['_source']['location']['city']['id']));
                $city_array = $city_array[0];
                $city_name  = $this->utils->htmlEntityDecode($city_array->getName());
                if ($city_name) {
                    if ($locationText) $locationText .= '<br/>';
                    $locationText .= $city_name;
                }
                $state_name  = '';
                $state_array = $this->container->get('CitiesServices')->worldStateInfo($city_array->getCountryCode(), $city_array->getStateCode());
                if ($state_array && sizeof($state_array)) {
                    $state_name = $this->utils->htmlEntityDecode($state_array[0]->getStateName());
                    if ($state_name) {
                        if ($city_name == '') $locationText .= '<br/>';
                        $locationText .= ', '.$state_name;
                    }
                }
                $country_array = $this->container->get('CmsCountriesServices')->countryGetInfo($city_array->getCountryCode());
                $country_name  = $this->utils->htmlEntityDecode($country_array->getName());
                if ($country_name) {
                    if ($city_name == '' && $state_name == '') $locationText .= '<br/>';
                    $locationText .= ', '.$country_name;
                }
            }else {
                if ($locationText == '') $locationText = $item['_source']['location']['location'];
            }
            $item_array['location'] = $locationText;
            $stars              = ceil($item['_source']['stars']);
            $all_stars          = 0;
            if ($stars > 0) {
                $all_stars = $stars;
            }
            $item_array['stars']       = $all_stars;
            $price                 = $item['_source']['prices']['priceFrom'];
            $item_array['price']       = $price;
            $item_array['detail_link'] = $this->container->get('TTRouteUtils')->returnHotelReviewLink( $lang, $item['_source']['id'], $item['_source']['name']);
            $item_array['show_on_map'] = '/ajax/show-on-map?type=h&id='.$item['_source']['id'];
            $item_array['id']          = $item['_source']['id'];
            $item_array['type']        = $this->container->getParameter('SOCIAL_ENTITY_HOTEL');
            $item_array['country']     = $item['_source']['location']['country']['code'];
            $item_array['city']        = $item['_source']['location']['city']['id'];
            $hotels_array[]        = $item_array;
        }
        return [ $hotels_array, $count, $country_name ];
    }

    public function getDiscoverHotelSearch( $srch_options, $url_source=NULL )
    {
        $default_opts = array
        (
            'limit' => 10,
            'page' => 0,
            'from' => null,
            'sortBy' => null,
            'cityId' => null,
            'countryCode' => null,
            'stateCode' => null,
            'oldQuery' => null,
            'sortGeolocation' => null,
            'sortByLat' => null,
            'sortByLon' => null,
            'aggs' => null,
            'stars' => null,
            'price' => null,
            'hotelPrefrences' => null,
            'greaterOrEqualThan' => null,
            'lessOrEqualThan' => null,
            'hotelPropertyType' => null,
            'imageExists' => null
        );
        $options = array_merge($default_opts, $srch_options);
        
        $page = $options['page'];
        $limit = $options['limit'];
        
        if( $options['from'] !=null )
        {
            $from = $options['from'];
        } else {
            $from = $page * $limit;
        }
        
        $ElasticSearchSC = new ElasticSearchSC();
        $criteria = array
        (
            'from' => $from,
            'size' => $limit
        );
        
        if( $options['cityId'] !=null )
        {
            $criteria['cityId'] = $options['cityId'];
        }
        
        if( $options['imageExists'] !=null )
        {
            $criteria['imageExists'] = $options['imageExists'];
        }
        
        if( $options['countryCode'] !=null )
        {
            $criteria['countryCode'] = $options['countryCode'];
        }
        
        if( $options['stateCode'] !=null )
        {
            $criteria['stateCode'] = $options['stateCode'];
        }
        
        if( $options['sortBy'] !=null )
        {
            $criteria['sortBy'] = $options['sortBy'];
        }
        
        if( $options['oldQuery'] !=null )
        {
            $criteria['oldQuery'] = $options['oldQuery'];
        }
        
        if( $options['sortGeolocation'] !=null )
        {
            $criteria['sortGeolocation'] = $options['sortGeolocation'];
        }
        
        if( $options['sortByLat'] !=null )
        {
            $criteria['sortByLat'] = $options['sortByLat'];
        }
        
        if( $options['sortByLon'] !=null )
        {
            $criteria['sortByLon'] = $options['sortByLon'];
        }
        
        if( $options['stars'] !=null )
        {
            $criteria['stars'] = $options['stars'];
        }
        
        if( $options['price'] !=null )
        {
            $criteria['price'] = $options['price'];
        }
        
        if( $options['hotelPrefrences'] !=null )
        {
            $criteria['hotelPrefrences'] = $options['hotelPrefrences'];
        }
        
        if( $options['greaterOrEqualThan'] !=null )
        {
            $criteria['greaterOrEqualThan'] = $options['greaterOrEqualThan'];
        }
        
        if( $options['lessOrEqualThan'] !=null )
        {
            $criteria['lessOrEqualThan'] = $options['lessOrEqualThan'];
        }
        
        if( $options['hotelPropertyType'] !=null )
        {
            $criteria['hotelPropertyType'] = $options['hotelPropertyType'];
        }
        
        if( $options['aggs'] !=null )
        {
            $criteria['aggs'] = $options['aggs'];
        }
        
        $ElasticSearchSC->setCriteria($criteria);
        $ElasticSearchSC->setUrlSource( $url_source );
        $queryStringResult = json_decode($this->discoverHotelSearch($ElasticSearchSC), true);
        
        return $this->buildSearchArray( $queryStringResult );
    }
    
    public function getPhotosVideosSearchData( $srch_options )
    {
        $default_opts = array
        (
            'url_source' => '',
            'description_length' => NULL,
            'imageTitle' => NULL,
            'return_resolution' => false,
            'escape_id' => 0,
            'lang' => 'en'
        );
        $options            = array_merge($default_opts, $srch_options);
        $lang               = ( isset($options['lang']) )?$options['lang']:'en';
        $url_source         = $options['url_source'];
        $return_resolution  = $options['return_resolution'];
        $description_length = intval($options['description_length']);
        $escape_id          = intval($options['escape_id']);
        $options['imageTitle'] = ( !is_null($options['imageTitle']) )?str_replace('-', ' ', $this->utils->cleanTitle( $options['imageTitle'] )):'';
        
        list($hits, $count, $aggregations, $suggest) = $this->getPhotosVideosSearch( $options, $url_source );
        $results_list = array();
        foreach ( $hits as $item )
        {
            if ($item['_source']['id'] == $escape_id )
            {
                continue;
            }
                
            if ( $lang !='en' && isset($item['_source']['translation'][0]['title_'.$lang]) && sizeof($item['_source']['translation'][0]['title_'.$lang]) && strlen($item['_source']['translation'][0]['title_'.$lang]) > 1)
            {
                $item_array['title']       = $this->utils->htmlEntityDecode($item['_source']['translation'][0]['title_'.$lang]);
                $item_array['titlealt']    = $this->utils->cleanTitleDataAlt($item['_source']['translation'][0]['title_'.$lang]);
                $item_array['description'] = $this->utils->htmlEntityDecode($item['_source']['translation'][0]['description_'.$lang]);
            } else {
                $item_array['title']       = $this->utils->htmlEntityDecode($item['_source']['title']);
                $item_array['titlealt']    = $this->utils->cleanTitleDataAlt($item['_source']['title']);
                $item_array['description'] = $this->utils->htmlEntityDecode($item['_source']['description']);
            }
            
            if( !is_null($description_length) )
            {
                $item_array['description'] = $this->utils->getMultiByteSubstr( $item_array['description'], $description_length, NULL, $lang );
            }
            
            $realpath     = $item['_source']['media']['relativepath'];
            $relativepath = str_replace('/', '', $realpath);
            $fullPath     = $item['_source']['media']['fullpath'];
            $itemsname    = $item['_source']['media']['name'];
            
            $item_array['img1'] = $item_array['img_big'] = $item_array['img']  = $this->container->get("TTMediaUtils")->mediaReturnSrcLinkElastic($item, 'small', 284, 162);
            if ($item['_source']['image_video'] == "i")
            {
                $item_array['img_big'] = $item_array['img1'] = $this->container->get("TTMediaUtils")->mediaReturnSrcLinkElastic($item, '');
            }

            $item_array['id']   = $item['_source']['id'];
            $item_array['type'] = $item_array['mediaType'] = $item['_source']['image_video'];
            $item_array['link'] = ''.$this->container->get("TTMediaUtils")->returnMediaUriHashedElastic($item, $lang);
            $t_date             = date('Y-m-d H:i', strtotime($item['_source']['pdate']));
            $commentsDate       = $this->utils->returnSocialTimeFormat($t_date, 1);
            $item_array['time'] = $commentsDate;
            
            
            if ( $return_resolution && $item['_source']['image_video'] == 'v' )
            {
                $rpath     = $item['_source']['media']['relativepath'];
                $name      = $item['_source']['media']['name'];
                $videoResolutionArray      = array( 'full_path'=>$item['_source']['media']['fullpath'], 'relative_path'=>$rpath, 'name'=>$name );
                $res                       = $this->container->get('PhotosVideosServices')->getVideoResolutions( $videoResolutionArray, '' );
                $item_array['res_list']    = implode('/*/', $res);
                $item_array['res_video']   = ($res)?$res[0]:'';
                $item_array['res_listimg'] = $item_array['img_big'];
            }
            $results_list[]     = $item_array;
        }
        
        return [$results_list, $count, $aggregations, $suggest];
    }
    
    public function getPhotosVideosSearch( $srch_options, $url_source=NULL )
    {
        $default_opts = array
        (
            'limit' => 10,
            'page' => 0,
            'from' => null,
            'cityId' => null,
            'countryCode' => null,
            'countryName' => null,
            'type' => 'a',
            'sortBy' => null,
            'catergoryId' => null,
            'category' => null,
            'userId' => null,
            'aggs' => null,
            'imageTitle' => null,
            'oldQuery' => null,
            'term' => null
        );
        $options = array_merge($default_opts, $srch_options);
        
        $page = $options['page'];
        $limit = $options['limit'];
        
        if( $options['from'] !=null )
        {
            $from = $options['from'];
        } else {
            $from = $page * $limit;
        }
        
        $ElasticSearchSC = new ElasticSearchSC();
        $criteria = array
        (
            'from' => $from,
            'size' => $limit
        );
        
        if( $options['cityId'] !=null )
        {
            $criteria['cityId'] = $options['cityId'];
        }
        
        if( $options['countryCode'] !=null )
        {
            $criteria['countryCode'] = $options['countryCode'];
        }
        
        if( $options['countryName'] !=null )
        {
            $criteria['countryName'] = $options['countryName'];
        }
        
        if( $options['type'] !=null && $options['type'] !='a' )
        {
            $criteria['type'] = $options['type'];
        }
        
        if( $options['sortBy'] !=null )
        {
            $criteria['sortBy'] = $options['sortBy'];
        }
        
        if( $options['catergoryId'] !=null )
        {
            $criteria['catergoryId'] = $options['catergoryId'];
        }
        
        if( $options['category'] !=null )
        {
            $criteria['category'] = $options['category'];
        }
        
        if( $options['userId'] !=null )
        {
            $criteria['userId'] = $options['userId'];
        }
        
        if( $options['aggs'] !=null )
        {
            $criteria['aggs'] = $options['aggs'];
        }
        
        if( $options['imageTitle'] !=null )
        {
            $criteria['imageTitle'] = $options['imageTitle'];
        }
        
        if( $options['oldQuery'] !=null )
        {
            $criteria['oldQuery'] = $options['oldQuery'];
        }
        
        if( $options['term'] !=null && $options['term'] !='' )
        {
            $ElasticSearchSC->setTerm( $options['term'] );
        }
        
        $ElasticSearchSC->setCriteria($criteria);
        $ElasticSearchSC->setUrlSource( $url_source );
        $queryStringResult = json_decode($this->mediaSearch($ElasticSearchSC), true);
        
        return $this->buildSearchArray( $queryStringResult );
    }
    
    public function getLocationSearch( $srch_options, $url_source=NULL )
    {
        $default_opts = array
        (
            'limit' => 10,
            'page' => 0,
            'from' => null,
            'onlyCity' => 1,
            'countryCode' => null,
            'term' => null
        );
        $options = array_merge($default_opts, $srch_options);
        
        $page = $options['page'];
        $limit = $options['limit'];
        
        if( $options['from'] !=null )
        {
            $from = $options['from'];
        } else {
            $from = $page * $limit;
        }
        
        $ElasticSearchSC = new ElasticSearchSC();
        $criteria = array
        (
            'from' => $from,
            'size' => $limit
        );
        
        if( $options['countryCode'] !=null )
        {
            $criteria['countryCode'] = $options['countryCode'];
        }
        
        if( $options['onlyCity'] !=null )
        {
            $criteria['onlyCity'] = $options['onlyCity'];
        }
        if( $options['term'] !=null && $options['term'] !='' )
        {
            $ElasticSearchSC->setTerm( $options['term'] );
        }
        
        
        $ElasticSearchSC->setCriteria($criteria);
        $ElasticSearchSC->setUrlSource( $url_source );
        $queryStringResult = json_decode($this->locationSearch($ElasticSearchSC), true);
        
        return $this->buildSearchArray( $queryStringResult );
    }
//    
    public function getHotelSearchData( $srch_options )
    {
        $default_opts = array
        (
            'url_source' => '',
            'lang' => 'en',
            'cityId' => 0
        );
        $options = array_merge($default_opts, $srch_options);
        $cityid = intval($options['cityId']);
        if ($cityid != 0) {
            $HotelCityInfo = $this->container->get('HRSServices')->getCmsHotelCityInfo($cityid);
            if ($HotelCityInfo) $options['locationId']    = $HotelCityInfo['hc_locationId'];
        }
        $lang = ( isset($options['lang']) )?$options['lang']:'en';
        $url_source = $options['url_source'];
        
        list($hits, $count, $aggregations, $suggest) = $this->getHotelSearch( $options, $url_source );
        $results_list = array();
        
        
        foreach ($hits as $item) {
            $item_array              = array();
            $stars                   = ceil($item['_source']['stars']);
            if ($stars > 5) $stars             = 5;
            $item_array['stars']     = $stars;
            $item_array['longitude'] = $item['_source']['vendor'][0]['coordinates']['lon'];
            $item_array['latitude']  = $item['_source']['vendor'][0]['coordinates']['lat'];
            $item_array['type']      = $this->container->getParameter('SOCIAL_ENTITY_HOTEL');
            $item_array['id']        = intval($item['_source']['id']);
            $item_array['name']      = $this->utils->htmlEntityDecode($item['_source']['name']);
            $item_array['namealt']   = $this->utils->cleanTitleDataAlt($item['_source']['name']);
            $item_array['title']     = addslashes($item_array['name']);
            $item_array['link']      = $this->container->get('TTRouteUtils')->returnHotelDetailedLink($lang, $item['_source']['name'], $item['_source']['id']);
            $item_array['img']       = $this->container->get('HRSServices')->getHotelMainImage($item['_source']['id'],1,1);
//            $imgarr                  = array();
//            if (sizeof($def_array)) {
//                $imgarr = $def_array;
//            }
//            $item_array['img'] = $this->container->get('HRSServices')->createImageSource($imgarr, 1, 1);

            $address = array();

            if (isset($item['_source']['location'][0]['district'])) {
                $address[] = $item['_source']['location'][0]['district'];
            }

            if (isset($item['_source']['location'][0]['zip_code'])) {
                $address[] = $item['_source']['location'][0]['zip_code'];
            }

            if (isset($item['_source']['location'][0]['city']['name'])) {
                $address[] = $item['_source']['location'][0]['city']['name'];
            }

            if (isset($item['_source']['location'][0]['country']['name'])) {
                $address[] = $item['_source']['location'][0]['country']['name'];
            }

            $item_array['address'] = implode(", ", array_filter($address));
            $results_list[]  = $item_array;
        }
        return [$results_list, $count, $aggregations, $suggest];
    }
    
    public function getHotelSearch( $srch_options, $url_source=NULL )
    {
        $default_opts = array
        (
            'limit' => 10,
            'page' => 0,
            'from' => null,
            'cityId' => null,
            'cityName' => null,
            'countryCode' => null,
            'locationId' => null,
            'sortBy' => null,
            'sortbyOrder' => null,
            'imageExists' => null,
            'page_src' => null,
            'oldQuery' => null,
            'term' => null
        );
        $options = array_merge($default_opts, $srch_options);
        
        
        $page = $options['page'];
        $limit = $options['limit'];
        
        if( $options['from'] !=null )
        {
            $from = $options['from'];
        } else {
            $from = $page * $limit;
        }
        
        $ElasticSearchSC = new ElasticSearchSC();
        $criteria = array
        (
            'from' => $from,
            'size' => $limit
        );
        
        if( $options['cityId'] !=null )
        {
            $criteria['cityId'] = $options['cityId'];
        }
        
        if( $options['cityName'] !=null )
        {
            $criteria['cityName'] = $options['cityName'];
        }
        
        if( $options['countryCode'] !=null )
        {
            $criteria['countryCode'] = $options['countryCode'];
        }
        
        if( $options['locationId'] !=null )
        {
            $criteria['locationId'] = $options['locationId'];
        }
        
        if( $options['sortBy'] !=null )
        {
            $criteria['sortBy'] = $options['sortBy'];
        }
        
        if( $options['sortbyOrder'] !=null )
        {
            $criteria['sortbyOrder'] = $options['sortbyOrder'];
        }
        
        if( $options['imageExists'] !=null )
        {
            $criteria['imageExists'] = $options['imageExists'];
        }
        
        if( $options['page_src'] !=null )
        {
            $criteria['page_src'] = $options['page_src'];
        }
        
        if( $options['oldQuery'] !=null )
        {
            $criteria['oldQuery'] = $options['oldQuery'];
        }
        
        if( $options['term'] !=null && $options['term'] !='' )
        {
            $ElasticSearchSC->setTerm( $options['term'] );
        }
        
        $ElasticSearchSC->setCriteria($criteria);
        $ElasticSearchSC->setUrlSource( $url_source );
        $queryStringResult = json_decode($this->hotelSearch($ElasticSearchSC), true);
        
        return $this->buildSearchArray( $queryStringResult );
    }
    
    public function getCitiesSearch( $srch_options, $url_source=NULL )
    {
        $default_opts = array
        (
            'limit' => 10,
            'page' => 0,
            'from' => null,
            'term' => null
        );
        $options = array_merge($default_opts, $srch_options);
        
        $page = $options['page'];
        $limit = $options['limit'];
        
        if( $options['from'] !=null )
        {
            $from = $options['from'];
        } else {
            $from = $page * $limit;
        }
        
        $ElasticSearchSC = new ElasticSearchSC();
        $criteria = array
        (
            'from' => $from,
            'size' => $limit
        );
        
        if( $options['term'] !=null && $options['term'] !='' )
        {
            $ElasticSearchSC->setTerm( $options['term'] );
        }
        
        $ElasticSearchSC->setCriteria($criteria);
        $ElasticSearchSC->setUrlSource( $url_source );
        $queryStringResult = json_decode($this->citiesSearch($ElasticSearchSC), true);
        
        return $this->buildSearchArray( $queryStringResult );
    }
    
    public function getThingsToDoSearch( $srch_options, $url_source=NULL )
    {
        $default_opts = array
        (
            'limit' => 10,
            'page' => 0,
            'from' => null,
            'term' => null
        );
        $options = array_merge($default_opts, $srch_options);
        
        $page = $options['page'];
        $limit = $options['limit'];
        
        if( $options['from'] !=null )
        {
            $from = $options['from'];
        } else {
            $from = $page * $limit;
        }
        
        $ElasticSearchSC = new ElasticSearchSC();
        $criteria = array
        (
            'from' => $from,
            'size' => $limit
        );
        
        if( $options['term'] !=null && $options['term'] !='' )
        {
            $ElasticSearchSC->setTerm( $options['term'] );
        }
        
        $ElasticSearchSC->setCriteria($criteria);
        $ElasticSearchSC->setUrlSource( $url_source );
        $queryStringResult = json_decode($this->thingsToDoSearch($ElasticSearchSC), true);
        
        return $this->buildSearchArray( $queryStringResult );
    }
    
    public function getAirportsSearch( $srch_options, $url_source=NULL )
    {
        $default_opts = array
        (
            'limit' => 10,
            'page' => 0,
            'from' => null,
            'cityId' => null,
            'term' => null
        );
        $options = array_merge($default_opts, $srch_options);
        
        $page = $options['page'];
        $limit = $options['limit'];
        
        if( $options['from'] !=null )
        {
            $from = $options['from'];
        } else {
            $from = $page * $limit;
        }
        
        $ElasticSearchSC = new ElasticSearchSC();
        $criteria = array
        (
            'from' => $from,
            'size' => $limit
        );
        
        if( $options['cityId'] !=null )
        {
            $criteria['cityId'] = $options['cityId'];
        }
        
        if( $options['term'] !=null && $options['term'] !='' )
        {
            $ElasticSearchSC->setTerm( $options['term'] );
        }
        
        $ElasticSearchSC->setCriteria($criteria);
        $ElasticSearchSC->setUrlSource( $url_source );
        $queryStringResult = json_decode($this->airportsSearch($ElasticSearchSC), true);
        
        return $this->buildSearchArray( $queryStringResult );
    }
    
    public function getHotelStaticSearch( $srch_options, $url_source=NULL )
    {
        $default_opts = array
        (
            'limit' => 10,
            'page' => 0,
            'from' => null,
            'term' => null
        );
        $options = array_merge($default_opts, $srch_options);
        
        $page = $options['page'];
        $limit = $options['limit'];
        
        if( $options['from'] !=null )
        {
            $from = $options['from'];
        } else {
            $from = $page * $limit;
        }
        
        $ElasticSearchSC = new ElasticSearchSC();
        $criteria = array
        (
            'from' => $from,
            'size' => $limit
        );
        
        if( $options['term'] !=null && $options['term'] !='' )
        {
            $ElasticSearchSC->setTerm( $options['term'] );
        }
        
        $ElasticSearchSC->setCriteria($criteria);
        $ElasticSearchSC->setUrlSource( $url_source );
        $queryStringResult = json_decode($this->hotelStaticSearch($ElasticSearchSC), true);
        
        return $this->buildSearchArray( $queryStringResult );
    }
    
    public function getHotelsCitiesSearch( $srch_options, $url_source=NULL )
    {
        $default_opts = array
        (
            'limit' => 10,
            'page' => 0,
            'from' => null,
            'cityId' => null,
            'term' => null
        );
        $options = array_merge($default_opts, $srch_options);
        
        $page = $options['page'];
        $limit = $options['limit'];
        
        if( $options['from'] !=null )
        {
            $from = $options['from'];
        } else {
            $from = $page * $limit;
        }
        
        $ElasticSearchSC = new ElasticSearchSC();
        $criteria = array
        (
            'from' => $from,
            'size' => $limit
        );
        
        if( isset($options['page_src']) ) $criteria['page_src'] = $options['page_src'];
        
        if( $options['cityId'] !=null )
        {
            $criteria['cityId'] = $options['cityId'];
        }
        
        if( $options['term'] !=null && $options['term'] !='' )
        {
            $ElasticSearchSC->setTerm( $options['term'] );
        }
        
        $ElasticSearchSC->setCriteria($criteria);
        $ElasticSearchSC->setUrlSource( $url_source );
        $queryStringResult = json_decode($this->hotelsCitiesSearch($ElasticSearchSC), true);
        
        return $this->buildSearchArray( $queryStringResult );
    }
    
    public function getDealsSearch( $srch_options, $url_source=NULL )
    {
        $default_opts = array
        (
            'limit' => 10,
            'page' => 0,
            'from' => null,
            'term' => null
        );
        $options = array_merge($default_opts, $srch_options);
        
        $page = $options['page'];
        $limit = $options['limit'];
        
        if( $options['from'] !=null )
        {
            $from = $options['from'];
        } else {
            $from = $page * $limit;
        }
        
        $ElasticSearchSC = new ElasticSearchSC();
        $criteria = array
        (
            'from' => $from,
            'size' => $limit
        );
        
        if( $options['term'] !=null && $options['term'] !='' )
        {
            $ElasticSearchSC->setTerm( $options['term'] );
        }
        
        $ElasticSearchSC->setCriteria($criteria);
        $ElasticSearchSC->setUrlSource( $url_source );
        $queryStringResult = json_decode($this->dealsSearch($ElasticSearchSC), true);
        
        return $this->buildSearchArray( $queryStringResult );
    }
    
    public function getDealsCitySearch( $srch_options, $url_source=NULL )
    {
        $default_opts = array
        (
            'limit' => 10,
            'page' => 0,
            'from' => null,
            'term' => null
        );
        $options = array_merge($default_opts, $srch_options);
        
        $page = $options['page'];
        $limit = $options['limit'];
        
        if( $options['from'] !=null )
        {
            $from = $options['from'];
        } else {
            $from = $page * $limit;
        }
        
        $ElasticSearchSC = new ElasticSearchSC();
        $criteria = array
        (
            'from' => $from,
            'size' => $limit
        );
        
        if( $options['term'] !=null && $options['term'] !='' )
        {
            $ElasticSearchSC->setTerm( $options['term'] );
        }
        
        $ElasticSearchSC->setCriteria($criteria);
        $ElasticSearchSC->setUrlSource( $url_source );
        $queryStringResult = json_decode($this->dealsCitySearch($ElasticSearchSC), true);
        
        return $this->buildSearchArray( $queryStringResult );
    }
    
    public function buildSearchArray( $queryStringResult )
    {
        $this->checkElasticErrorLog($queryStringResult);
        $retDoc   = $queryStringResult;
        if(!$retDoc || empty($retDoc))
        {
            $retDoc['hits']['hits'] = array();
            $retDoc['aggregations'] = array();
            $retDoc['suggest'] = array();
            $retDoc['hits']['total'] = 0;
        }
        
        if( !isset($retDoc['aggregations']) )
        {
            $retDoc['aggregations'] = array();
        }
        
        if( !isset($retDoc['suggest']) )
        {
            $retDoc['suggest'] = array();
        }
        
        $hits = $retDoc['hits']['hits'];
        $n_hits = $retDoc['hits']['total'];
        $aggregations = $retDoc['aggregations'];
        $suggest = $retDoc['suggest'];
        
        return [$hits, $n_hits, $aggregations, $suggest];
    }
}