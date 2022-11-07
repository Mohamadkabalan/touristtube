<?php

namespace TTBundle\Repository;

use TTBundle\Model\ElasticSearchSC;
use TTBundle\Repository\Common\ElasticSearchCommon;

class ElasticPoiRepository extends ElasticSearchCommon
{
    /**
     * Generate the elastic query for poi and return query of type string
     * @param array (criteria)
     * @return String $results
     */
    private function prepareDDSearchQuery(ElasticSearchSC $elasticSearchSC)
    {
        $query = null;
        $sizeOfRecords = '';
        $fromRecords = ''; 
        $sortByQuery = '';
        $queryCityId='';
        $queryCountyCode='';
        $queryCityName='';
        $queryCountyName='';
        $subQuery='';
        $queryImageExists= '';
        $subQuery = '';
        $termQuery = '';
        $aggsQuery = '';
        if($elasticSearchSC != null && ($elasticSearchSC->getTerm() != '' || $elasticSearchSC->getCriteria() != ''))
        {
            $criteria = $elasticSearchSC->getCriteria();
            $term = $elasticSearchSC->getTerm();
            //
            //
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
                    }else{
                    $sortByQuery = '
                        
                        "sort": [
                          {
                            "'.$sortBy.'": "desc",
                            "stars": "desc",
                            "_score": "desc"
                          }
                        ]';
                    }
                }else{
                    $sortByQuery = '
                        
                        "sort": [
                          {
                            "popularity": "desc",
                            "stars": "desc",
                            "_score": "desc"
                          }
                        ]';
                }
            }
            if(isset($criteria['cityId'])){
                $cityId = $criteria['cityId'];
                $queryCityId = '
                                {
                                  "match": {
                                    "location.city.id": "'.$cityId.'"
                                  }
                                }';
            }
            if(isset($criteria['cityName'])){
                $cityName = $criteria['cityName'];
                $queryCityName = '
                                {
                                  "match": {
                                    "location.city.name": "'.$cityName.'"
                                  }
                                }';
            }
            if(isset($criteria['countyCode'])){
                $countyCode = $criteria['countyCode'];
                $queryCountyCode = '
                                {
                                  "match": {
                                    "location.country.code": "'.$countyCode.'"
                                  }
                                }';
            }
            if(isset($criteria['countyName'])){
                $countyName = $criteria['countyName'];
                $queryCountyName = '
                                {
                                  "match": {
                                    "location.country.name": "'.$countyName.'"
                                  }
                                }';
            }
//            if(isset($criteria['imageExists'])){
//                $imageExists = $criteria['imageExists'];
//                $queryImageExists = '
//                                {
//                                  "exists" : { "field" : "images.id" }
//                                }';
//            }
            if($term){
                $termQuery = '
                              "query": {
                                "dis_max": {
                                  "queries": [
                                    {
                                      "bool": {
                                        "should": [
                                            {
                                              "match": {
                                                "name": {
                                                  "query": "'.$term.'",
                                                  "operator": "and", 
                                                  "fuzziness": 2,
                                                  "max_expansions":2,
                                                  "boost": 2
                                                }
                                              }
                                            },
                                            {
                                              "match": {
                                                "name_key": {
                                                  "query": "'.$term.'",
                                                  "operator": "and", 
                                                  "fuzziness": 2,
                                                  "max_expansions":2,
                                                  "boost": 3
                                                }
                                              }
                                            },
                                            {
                                              "match": {
                                                "location.country.name": {
                                                  "query": "'.$term.'",
                                                  "operator": "and", 
                                                  "fuzziness": 1,
                                                  "boost": 1
                                                }
                                              }
                                            },
                                            {
                                              "match": {
                                                "location.city.name": {
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
                                    ]
                                  }
                                }';
            }
            if(isset($criteria['oldQuery']) && $criteria['oldQuery'] ==1){
                $termQuery='"query": {
                                "dis_max": {
                                  "queries": [
                                    {
                                      "bool": {
                                        "must": [
                                          ';
                
                if($queryCityId){
                    $subQuery .= $queryCityId;
                }
                if($queryCountyCode){
                    if($subQuery != ''){ $subQuery .= ','; }
                    $subQuery .= $queryCountyCode;
                }
                if($queryCityName){
                    if($subQuery != ''){ $subQuery .= ','; }
                    $subQuery .= $queryCityName;
                }
                if($queryCountyName){
                    if($subQuery != ''){ $subQuery .= ','; }
                    $subQuery .= $queryCountyName;
                }
                if($queryImageExists){
                    if($subQuery != ''){ $subQuery .= ','; }
                    $subQuery .= $queryImageExists;
                }
                
                $termQuery .= $subQuery;
                $termQuery .=  '
                                        ]
                                      }
                                    }
                                  ]
                                }
                              }';
            }
            
            if(isset($criteria['aggs'])){
                $aggs = $criteria['aggs'];
                $aggsQuery = ' "aggs" : {
                                  "'.$aggs.'" : {
                                    "terms" : {
                                        "field" : "'.$aggs.'",
                                        "size": 10
                                    }
                                  }
                                }';
            }
            $subQuery = '';
            if($fromRecords){
                $subQuery .= $fromRecords;
            }
            if($sizeOfRecords){
                if($subQuery != ''){ $subQuery .= ','; }
                $subQuery .= $sizeOfRecords;
            }
            if($termQuery){
                if($subQuery != ''){ $subQuery .= ','; }
                $subQuery .= $termQuery;
            }
            if($sortByQuery){
                if($subQuery != ''){ $subQuery .= ','; }
                $subQuery .= $sortByQuery;
            }
            if($aggsQuery){
                if($subQuery != ''){ $subQuery .= ','; }
                $subQuery .= $aggsQuery;
            }
            
            $query = '{';
            $query .= $subQuery;
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
