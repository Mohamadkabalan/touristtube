<?php

namespace TTBundle\Repository;

use TTBundle\Model\ElasticSearchSC;
use TTBundle\Repository\Common\ElasticSearchCommon;

class ElasticThingsToDoRepository extends ElasticSearchCommon
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
        $termQuery = '';
        $subQuery='';
        $sortBy='';
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
            if(isset($criteria['from'])){
                $from = $criteria['from'];    
                if($from){
                    $fromRecords = ' "from": '.$from.', ';
                }
            } 
            if(isset($criteria['sortBy'])){
                $sortBy = '
                    "sort": [
                      {
                        "'.$sortBy.'":  "desc",
                        "_score": "desc"
                      }
                    ]';
            }else{
                $sortBy = '
                    "sort": [
                      {
                        "_score": "desc"
                      }
                    ]';
            }
            if($term){
                $termQuery = '{
                                "match": {
                                  "name": {
                                    "query": "'.$term.'",
                                    "operator": "and", 
                                    "fuzziness": 3,
                                    "max_expansions":2,
                                    "prefix_length": 1,
                                    "boost": 3   
                                  }
                                }
                              },
                              {
                                "match": {
                                  "description": {
                                    "query": "'.$term.'",
                                    "operator": "and", 
                                    "prefix_length": 1,
                                    "boost": 1   
                                  }
                                }
                              },
                              {
                                "query_string" : {
                                  "default_field" : "name",
                                  "query" : "*'.$term.'*",
                                  "boost": 10    
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
                                  "should": [';

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
                          },
                        "suggest":{
                           "text":"'.$term.'",
                           "simple_phrase":{
                              "phrase":{
                                 "field":"name",
                                 "size":2,
                                 "gram_size":3,
                                 "direct_generator":[
                                    {
                                       "field":"name",
                                       "suggest_mode":"always"
                                    }
                                 ],
                                 "highlight":{
                                    "pre_tag":"<em>",
                                    "post_tag":"</em>"
                                 }
                              }
                           }
                        }';
            if($sortBy){
                $query .= ",".$sortBy;
            }
            $query .= '  }
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
