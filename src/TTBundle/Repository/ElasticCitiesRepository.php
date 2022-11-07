<?php

namespace TTBundle\Repository;

use TTBundle\Model\ElasticSearchSC;
use TTBundle\Repository\Common\ElasticSearchCommon;
use Symfony\Bridge\Monolog\Logger;

class ElasticCitiesRepository extends ElasticSearchCommon
{
	public function __construct(Logger $logger) {
        $this->logger        = $logger;
    }

    /**
     * Generate the elastic query for cities and return query of type string
     * @param array (criteria)
     * @return String $results
     */
    private function prepareDDSearchQuery(ElasticSearchSC $elasticSearchSC)
    {
        $query = null;
        $sizeOfRecords = '';
        $fromRecords = '';
        if(($elasticSearchSC != null && $elasticSearchSC->getTerm() != '') || $elasticSearchSC->getCriteria())
        {
            $criteria = $elasticSearchSC->getCriteria();
            $term = $elasticSearchSC->getTerm();
            //
            //
            if(isset($criteria['from'])){
                $from = $criteria['from'];    
                if($from){
                    $fromRecords = ' "from": '.$from.' , ';
                }
            } 
            if(isset($criteria['size'])){
                $criteria = $elasticSearchSC->getCriteria();
                $size = $criteria['size'];    
                if($size){
                    $sizeOfRecords = ' "size": '.$size.', ';
                }
            }
            $query = '{'.$fromRecords.$sizeOfRecords.'
                        "query" : {
                           "dis_max": {
                              "queries": [
                                {
                                  "match": {
                                    "name": {
                                      "query": "'.$term.'",
                                      "operator": "and",
                                      "fuzziness": 2,
                                      "max_expansions":2,
                                      "prefix_length": 3,
                                      "boost": 1
                                    }
                                  }
                                },
                                {
                                  "match": {
                                    "name": {
                                      "query": "'.$term.'",
                                      "operator": "and",
                                      "fuzziness": 0,
                                      "max_expansions":2,
                                      "boost": 1
                                    }
                                  }
                                },
                                {
                                  "match": {
                                    "tagss": {
                                      "query": "'.$term.'",
                                      "operator": "and",
                                      "fuzziness": 0,
                                      "max_expansions":2,
                                      "boost": 2
                                    }
                                  }
                                }
                              ]
                           }
                        },
                         "suggest": {
                           "text": "'.$term.'",
                           "simple_phrase": {
                             "phrase": {
                               "field": "name",
                               "size": 2,
                               "gram_size": 3,
                               "direct_generator": [ {
                                 "field": "name",
                                 "suggest_mode": "always"
                               } ],
                               "highlight": {
                                 "pre_tag": "<em>",
                                 "post_tag": "</em>"
                               }
                             }
                           }
                         },

                        "highlight" : {
                           "fields" : {
                             "name" : {
                             }
                           },
                           "pre_tags": ["<em>"],
                           "post_tags": ["</em>"]
                        },
                        "sort": [
                          {
                            "popularity": "desc",
                            "_score": "desc"
                          }
                        ]
                     }';
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
