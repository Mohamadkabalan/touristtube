<?php

namespace TTBundle\Repository;

use TTBundle\Model\ElasticSearchSC;
use TTBundle\Repository\Common\ElasticSearchCommon;
use Symfony\Bridge\Monolog\Logger;

class ElasticDiscoverHotelRepository extends ElasticSearchCommon
{
	public function __construct(Logger $logger) {
        $this->logger        = $logger;
    }

    /**
     * Generate the elastic query for discover hotels and return query of type string
     * @param array (criteria)
     * @return String $results
     */
    private function prepareDDSearchQuery(ElasticSearchSC $elasticSearchSC)
    {
        $query = null;
        $sizeOfRecords = '';
        $fromRecords = ''; 
        $sortByQuery = '';
        $queryCityId = '';
        $queryCityName = '';
        $queryCountryCode = '';
        $queryCountryName = '';
        $queryImageExists = '';
        $propertyTypeQuery = '';
        $prefrencesQuery = '';
        $queryStateCode = '';
        $starsQuery = '';
        $subQuery = '';
        $allQuery = '';
        $termQuery = '';
        $aggsQuery = '';
        $lang = 'en';
        
        if($elasticSearchSC != null && ($elasticSearchSC->getTerm() != '' || $elasticSearchSC->getCriteria() != ''))
        {
            $criteria = $elasticSearchSC->getCriteria();
            $term = $elasticSearchSC->getTerm();
            //
            // 
            if(isset($criteria['lang'])){
                $lang = $criteria['lang'];
            }
            if(isset($criteria['size'])){
                $size = $criteria['size'];    
                if($size){
                    $sizeOfRecords = ' "size": '.$size;
                }
            }
            if(isset($criteria['from'])){
                $from = $criteria['from'];    
                if($from){
                    $fromRecords = ' "from": '.$from;
                }
            } 
            if(isset($criteria['sortBy'])){
                $sortBy = $criteria['sortBy'];  
                if(isset($criteria['stars'])){
                    $stars = $criteria['stars'];
                }
                if(isset($criteria['price'])){
                    $price = $criteria['price']; 
                } 
                if($sortBy){
                    if(isset($criteria['sortGeolocation'])){
                        $lon = $criteria['sortByLon'];
                        $lat = $criteria['sortByLat'];
                        $sortByQuery = '
                                "sort": [
                                  {
                                    "_geo_distance": {
                                      "'.$sortBy.'": {
                                        "lat":  '.$lat.',
                                        "lon":  '.$lon.'
                                      },
                                      "order":         "asc",
                                      "unit":          "km",
                                      "distance_type": "arc"
                                    }
                                  }
                                ]';  
                    }
                    if($sortBy &&  !isset($criteria['sortGeolocation'])){
                        $sortByQuery = '
                            "sort": [
                              {
                                "stars": "desc", 
                                "'.$sortBy.'": "desc",
                                "_score": "desc"
                              }
                            ]';
                    }
                    if(isset($stars)){
                        $sortByQuery = '
                            "sort": [
                              {
                                "stars": "'.$stars.'",
                                "_score": "desc"
                              }
                            ]';
                    }
                    if(isset($price)){
                        $sortByQuery = '
                            "sort": [
                              {
                                "price": "'.$price.'",
                                "_score": "desc"
                              }
                            ]';
                    }
                }
            }else{
                $sortByQuery = '
                    "sort": [
                      {
                        "stars": "desc", 
                        "media.images.id": "desc", 
                        "popularity": "desc",
                        "_score": "desc"
                      }
                    ]';
            }
            if(isset($criteria['greaterOrEqualThan']) && isset($criteria['lessOrEqualThan'])){
                $greaterOrEqualThan = $criteria['greaterOrEqualThan'];
                $lessOrEqualThan = $criteria['lessOrEqualThan'];
                $starsQuery ='
                            {
                              "range": {
                                "stars": {
                                  "gte": '.$greaterOrEqualThan.',
                                  "lte": '.$lessOrEqualThan.'
                                }
                              }
                            }
                             ';
            }
            if(isset($criteria['hotelPrefrences']) && !empty($criteria['hotelPrefrences'])){
                $hotelPrefrences = $criteria['hotelPrefrences'];
                $prefrencesQuery = '
                                              {
                                                "bool": {
                                                  "must": [';
                $i                = 0;
                foreach ($hotelPrefrences as $prefrence) {
                    $prefrencesQuery .= '
                                                    {
                                                      "match": {
                                                        "features.title_key": "'.$prefrence.'"
                                                      }
                                                    }';
                    if ($i < sizeof($hotelPrefrences) - 1) {
                        $prefrencesQuery .= ',';
                    }
                    $i++;
                }
                $prefrencesQuery .= ']
                                                }
                                              }';
            }
            if(isset($criteria['hotelPropertyType']) && !empty($criteria['hotelPropertyType'])){
                $hotelPropertyType = $criteria['hotelPropertyType'];
                $propertyTypeQuery = '
                                              {
                                                "bool": {
                                                  "must": [';
                $i                = 0;
                foreach ($hotelPropertyType as $propertyType) {
                    $propertyTypeQuery .= '
                                                    {
                                                      "match": {
                                                        "propertyName_key": "'.$propertyType.'"
                                                      }
                                                    }';
                    if ($i < sizeof($hotelPropertyType) - 1) {
                        $propertyTypeQuery .= ',';
                    }
                    $i++;
                }
                $propertyTypeQuery .= ']
                                                }
                                              }';
            }
            if(isset($criteria['cityId']) && $criteria['cityId'] != ''){
                $cityId = $criteria['cityId'];
                $queryCityId = '{
                                  "match": {
                                    "location.city.id": "'.$cityId.'"
                                  }
                                }';
            }
            if(isset($criteria['cityName'])){
                $cityName = $criteria['cityName'];
                $queryCityName = '{
                                  "match": {
                                    "location.city.name": "'.$cityName.'"
                                  }
                                }';
            }
            if(isset($criteria['countryCode'])){
                $countryCode = $criteria['countryCode'];
                $queryCountryCode = '{
                                        "match": {
                                          "location.country.code": "'.$countryCode.'"
                                        }
                                      }';
            }
            if(isset($criteria['stateCode'])){
                $stateCode = $criteria['stateCode'];
                $queryStateCode = '{
                                        "match": {
                                          "location.state.code": "'.$stateCode.'"
                                        }
                                      }';
            }
            if(isset($criteria['countryName'])){
                $countryName = $criteria['countryName'];
                $queryCountryName = '{
                                        "match": {
                                          "location.country.name": "'.$countryName.'"
                                        }
                                      }';
            }
            if(isset($criteria['imageExists'])){
                $queryImageExists = ' {
                                        "exists" : { "field" : "media.id" }
                                      }';
            }
            if($term){
                $termQuery = '{
                                "bool": {
                                  "should": [
                                    {
                                     "match":{
                                       "name":{
                                         "query": "'.$term.'",
                                         "operator": "and", 
                                         "fuzziness": 2, 
                                         "max_expansions":2,
                                         "boost": 2
                                       }
                                     }
                                    },
                                    {
                                     "match":{
                                       "name_key":{
                                         "query": "'.$term.'", 
                                         "operator": "and", 
                                         "boost": 5
                                       }
                                     }
                                    },
                                    {
                                     "match":{
                                       "location.country.name":{
                                         "query": "'.$term.'", 
                                         "operator": "and", 
                                         "fuzziness": 1, 
                                         "boost": 1
                                       }
                                     }
                                    },
                                    {
                                      "match":{
                                        "location.city.name":{
                                          "query": "'.$term.'", 
                                          "operator": "and", 
                                          "fuzziness": 1,
                                          "boost": 2
                                        }
                                      }
                                    }  
                                  ]
                                }  
                              }
                           ';
            }
            if(isset($criteria['oldQuery']) && $criteria['oldQuery'] == 1){
                $allQuery = '"query" : {
                                "dis_max": {
                                  "queries": [
                                    {
                                      "bool": {
                                        "must": [
                                          ';
                if($queryCityId){
                    $subQuery .= $queryCityId;
                }
                if($queryCountryCode){
                    if($subQuery != ''){ $subQuery .= ','; }
                    $subQuery .= $queryCountryCode;
                }
                if($queryStateCode){
                    if($subQuery != ''){ $subQuery .= ','; }
                    $subQuery .= $queryStateCode;
                }
                if($queryCityName){
                    if($subQuery != ''){ $subQuery .= ','; }
                    $subQuery .= $queryCityName;
                }
                if($queryCountryName){
                    if($subQuery != ''){ $subQuery .= ','; }
                    $subQuery .= $queryCountryName;
                }
                if($starsQuery){
                    if($subQuery != ''){ $subQuery .= ','; }
                    $subQuery .= $starsQuery;
                }
                if($prefrencesQuery){
                    if($subQuery != ''){ $subQuery .= ','; }
                    $subQuery .= $prefrencesQuery;
                }
                if($propertyTypeQuery){
                    if($subQuery != ''){ $subQuery .= ','; }
                    $subQuery .= $propertyTypeQuery;
                }
                if($termQuery){
                    if($subQuery != ''){ $subQuery .= ','; }
                    $subQuery .= $termQuery;
                }
//                if($queryImageExists){
//                    if($subQuery != ''){ $subQuery .= ','; }
//                    $subQuery .= $queryImageExists;
//                }
                if($subQuery == ''){
                   $subQuery = '{
                                  "match_all": {}
                                }'; 
                }
                
                $allQuery .= $subQuery;
                $allQuery .=  '
                                        ]
                                      }
                                    }
                                  ]
                                }
                              }';
                
            }
            
            if(isset($criteria['aggs'])){
                $aggs = $criteria['aggs'];
                $aggsQuery = '  "aggs" : {
                                  "'.$aggs.'" : {
                                    "terms" : {
                                        "field" : "'.$aggs.'",
                                        "size": 20
                                    }
                                  }
                                }';
            }
            $query = '{';
            if($fromRecords != ''){
                $query .= $fromRecords.' , ';
            }
            if($sizeOfRecords != ''){
                $query .= $sizeOfRecords.' , ';
            }
            if($allQuery != ''){
                $query .= $allQuery.' , ';
            }
            if($sortByQuery != ''){
                $query .= $sortByQuery;
            }
            if($aggsQuery != ''){
                $query .= ' , '.$aggsQuery;
            }
            $query .= '}';

         }
         return $query;
    }

    /**
     * Method to prepare and execute dropdown search on elastic
     * 
     * @param ElasticSearchSC $elasticSearchSC
     * 
     * @return json
     */
    public function searchDD(ElasticSearchSC $elasticSearchSC)
    {
        if(!$elasticSearchSC->getQuery())
            $elasticSearchSC->setQuery( $this->prepareDDSearchQuery($elasticSearchSC) );
        //
        $queryResult = $this->search($elasticSearchSC);
        //
        return $queryResult;
    }

}
