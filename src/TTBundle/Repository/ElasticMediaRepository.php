<?php

namespace TTBundle\Repository;

use TTBundle\Model\ElasticSearchSC;
use TTBundle\Repository\Common\ElasticSearchCommon;

class ElasticMediaRepository extends ElasticSearchCommon
{
    /**
     * Generate the elastic query for media and return query of type string
     * @param array (criteria)
     * @return String $results
     */
    private function prepareDDSearchQuery(ElasticSearchSC $elasticSearchSC)
    {
        $query = null;
        $sizeOfRecords = '';
        $fromRecords = '';
        $queryUserId = '';
        $queryCatergoryId = '';
        $queryImageTitle = '';
        $queryType = '';
        $imageTitle = '';
        $queryCityId= '';
        $queryCountryName='';
        $queryCountryCode='';
        $termQuery='';
        $subQuery='';
        $queryAggregation = '';
        $lang = 'en';
        if($elasticSearchSC != null && ($elasticSearchSC->getTerm() != '' || $elasticSearchSC->getCriteria() != ''))
        {
            $criteria = $elasticSearchSC->getCriteria();
            $term = $elasticSearchSC->getTerm();
            if(isset($criteria['from'])){
                $from = $criteria['from'];    
                if($from && $from != 0){
                    $fromRecords = ' "from": '.$from.', ';
                }
            } 
            if(isset($criteria['size'])){
                $size = $criteria['size'];    
                if($size){
                    $sizeOfRecords = ' "size": '.$size.', ';
                }
            }
            if(isset($criteria['catergoryId'])){
                $catergoryId = $criteria['catergoryId'];    
                if($catergoryId){
                    $queryCatergoryId = ' {
                                            "match": {
                                              "category.id": "'.$catergoryId.'"
                                            }
                                          } ';
                  }
            } 
            if(isset($criteria['countryCode'])){
                $countryCode = $criteria['countryCode'];    
                if($countryCode){
                    $queryCountryCode = ' {
                                            "match": {
                                              "location.country.code": "'.$countryCode.'"
                                            }
                                          } ';
                  }
            }
            if(isset($criteria['countryName'])){
                $countryName = $criteria['countryName'];    
                if($countryName){
                    $queryCountryName = ' {
                                            "match": {
                                              "location.country.name": "'.$countryName.'"
                                            }
                                          } ';
                  }
            } 
            if(isset($criteria['userId'])){
                $userId = $criteria['userId'];    
                if($userId){
                    $queryUserId = ' {
                                            "match": {
                                              "user.id": "'.$userId.'"
                                            }
                                          } ';
                  }
            } 
            if(isset($criteria['cityId'])){
                $cityId = $criteria['cityId'];    
                if($cityId){
                    $queryCityId = ' {
                                            "match": {
                                              "location.city.id": "'.$cityId.'"
                                            }
                                          } ';
                  }
            } 
            if(isset($criteria['imageTitle'])){
                $imageTitle = $criteria['imageTitle'];    
                if($imageTitle){
                    $queryImageTitle = ' {
                                            "match": {
                                              "title": "'.$imageTitle.'"
                                            }
                                          } ';
                  }
            } 
            if(isset($criteria['type'])){
                $type = $criteria['type'];    
                if($type=='i' || $type=='v'){
                    $queryType = ' {
                                            "match": {
                                              "image_video": "'.$type.'"
                                            }
                                          } ';
                  }
            } 
            if(isset($criteria['lang'])){
                $lang = $criteria['lang'];
            }
            if(isset($criteria['aggs'])){
                $aggs = $criteria['aggs']; 
                $queryAggregation = ',
                                "aggs": {
                                  "'.$aggs.'": {
                                    "terms": {
                                      "field": "'.$aggs.'",
                                      "size" : 100
                                    }
                                  }
                                }';
            }
            if(isset($criteria['sortBy'])){
                $sortBy = $criteria['sortBy'];    
                if($sortBy){
                    if(isset($criteria['sortGeolocation'])){
                        $lon = $criteria['sortByLon'];
                        $lat = $criteria['sortByLat'];
                        $querySortBy = '
                                ,"sort": [
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
                        $querySortBy = '
                            ,"sort": [
                              {
                                "'.$sortBy.'": "desc",
                                "_score": "desc"
                              }
                            ]';
                    }
                }
            }else{
                $querySortBy = '
                    ,"sort": [
                      {
                        "_score": "desc"
                      }
                    ]';
            }
            
            if($term){
                $termQuery .= ' { "bool": { "should": [{
                                   "match":{
                                     "title":{
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
                                     "title_key":{
                                       "query": "'.$term.'", 
                                       "operator": "and", 
                                       "fuzziness": 2, 
                                       "max_expansions":2,
                                       "boost": 3
                                     }
                                   }
                                  },
                                  {
                                   "match":{
                                     "translation.title_'.$lang.'":{
                                       "query": "'.$term.'", 
                                       "operator": "and", 
                                       "fuzziness": 2, 
                                       "max_expansions":2,
                                       "boost": 4
                                     }
                                   }
                                  },
                                  {
                                   "match":{
                                     "location.country.name":{
                                       "query": "'.$term.'", 
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
                                  } ] }}';
            }
            
            
            $query = '{
                        '.$fromRecords.$sizeOfRecords.'
                         "query": {
                            "dis_max": {
                              "queries": [
                               {
                                "bool": {
                                  "must": [';
            if($queryCatergoryId){
                $subQuery .= $queryCatergoryId;
            }
            if($queryType){
                if($subQuery != ''){ $subQuery .= ','; }
                $subQuery .= $queryType;
            }
            if($queryUserId){
                if($subQuery != ''){ $subQuery .= ','; }
                $subQuery .= $queryUserId;
            }
            if($queryCityId){
                if($subQuery != ''){ $subQuery .= ','; }
                $subQuery .= $queryCityId;
            }
            if($queryImageTitle){
                if($subQuery != ''){ $subQuery .= ','; }
                $subQuery .= $queryImageTitle;
            }
            if($queryCountryName){
                if($subQuery != ''){ $subQuery .= ','; }
                $subQuery .= $queryCountryName;
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
                          }';
            if($querySortBy){
               $query .= $querySortBy;
            }
            if($queryAggregation){
               $query .= $queryAggregation;
            }
            $query .='}
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
