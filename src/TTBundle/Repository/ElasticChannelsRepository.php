<?php

namespace TTBundle\Repository;

use TTBundle\Model\ElasticSearchSC;
use TTBundle\Repository\Common\ElasticSearchCommon;

class ElasticChannelsRepository extends ElasticSearchCommon
{
    /**
     * Generate the elastic query for Channels and return query of type string
     * @param array (criteria)
     * @return String $results
     */
    private function prepareDDSearchQuery(ElasticSearchSC $elasticSearchSC)
    {
        $query = null;
        $sizeOfRecords = '';
        $fromRecords = '';
        $queryOwnerId = '';
        $queryCityId = '';
        $queryCountryCode = '';
        $queryCategory = '';
        $queryCityName = '';
        $queryChannelName = '';
        $subQuery = '';
        $termQuery = '';
        $lang = 'en';
        if($elasticSearchSC != null && ($elasticSearchSC->getTerm() != '' || $elasticSearchSC->getCriteria() != ''))
        {
            $criteria = $elasticSearchSC->getCriteria();
            $term = $elasticSearchSC->getTerm();
            //
            //
            if(isset($criteria['size'])){
                $size = $criteria['size'];    
                if($size){
                    $sizeOfRecords = ' "size": '.$size.', ';
                }
            } 
            if(isset($criteria['from'])){
                $from = $criteria['from'];    
                if($from){
                    $fromRecords = ' "from": '.$from.', ';
                }
            } 
            if(isset($criteria['cityId'])){
                $cityId = $criteria['cityId'];    
                if($cityId){
                    $queryCityId = ' {
                                        "match": {
                                          "location.city.id": "'.$cityId.'"
                                        }
                                      }';
                }
            } 
            if(isset($criteria['ownerId'])){
                $ownerId = $criteria['ownerId'];
                if($ownerId){
                    $queryOwnerId = ' {
                                        "match": {
                                          "owner.id": "'.$ownerId.'"
                                        }
                                      }';
                }
            }
            if(isset($criteria['cityName'])){
                $cityName = $criteria['cityName'];    
                if($cityName){
                    $queryCityName = ' {
                                            "match": {
                                              "location.city.name": "'.$cityName.'"
                                            }
                                        }';
                }
            } 
            if(isset($criteria['countryCode'])){
                $countryCode = $criteria['countryCode'];    
                if($countryCode){
                    $queryCountryCode = '{ 
                                            "match": {
                                              "location.country.code": "'.$countryCode.'"
                                            }
                                        }';
                }
            } 
            if(isset($criteria['category'])){
                $category = $criteria['category'];    
                if($category){
                    $queryCategory = ' {
                                            "match": {
                                              "category.id": "'.$category.'"
                                            }
                                        }';
                }
            } 
            if(isset($criteria['channelName'])){
                $channelName = $criteria['channelName'];    
                if($channelName){
                    $queryChannelName = ' 
                                        {
                                          "match": {
                                            "name": {
                                              "query": "'.$channelName.'",
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
                                              "query": "'.$channelName.'",
                                              "operator": "and",
                                              "fuzziness": 2,
                                              "max_expansions":2,
                                              "boost": 3
                                            }
                                          }
                                        } ';
                }
            } 
            if($term){
                $termQuery = '{
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
                                          "location.country.name": {
                                            "query": "'.$term.'",
                                            "operator": "and",
                                            "fuzziness": 1,
                                            "max_expansions":2,
                                            "prefix_length": 3,
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
                                            "max_expansions":2,
                                            "prefix_length": 3,
                                            "boost": 2
                                          }
                                        }
                                      }
                                  ] 
                                }
                              } 
                            ';
                
            }
            $query = '{
                        '.$fromRecords.$sizeOfRecords.'
                         "query": {
                            "dis_max": {
                              "queries": [
                               {
                                "bool": {
                                  "must": [';
            if($queryCityId){
                $subQuery .= $queryCityId;
            }
            if($queryOwnerId){
                $subQuery .= $queryOwnerId;
            }
            if($queryCountryCode){
                if($subQuery != ''){ $subQuery .= ','; }
                $subQuery .= $queryCountryCode;
            }
            if($queryCategory){
                if($subQuery != ''){ $subQuery .= ','; }
                $subQuery .= $queryCategory;
            }
            if($queryCityName){
                if($subQuery != ''){ $subQuery .= ','; }
                $subQuery .= $queryCityName;
            }
            if($queryChannelName){
                if($subQuery != ''){ $subQuery .= ','; }
                $subQuery .= $queryChannelName;
            }
            if($termQuery){
                if($subQuery != ''){ $subQuery .= ','; }
                $subQuery .= $termQuery;
            }
            if($subQuery != ''){ $query .= $subQuery; }
            if($subQuery == ''){
                $query .='{
                             "match_all": {}
                          }';
            }
            $query .='       
                                  ]
                                }
                              }    
                              ]
                            }
                          }
                      }
                    ';  
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
