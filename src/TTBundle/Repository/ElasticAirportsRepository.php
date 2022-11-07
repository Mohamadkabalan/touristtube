<?php

namespace TTBundle\Repository;

use TTBundle\Model\ElasticSearchSC;
use TTBundle\Repository\Common\ElasticSearchCommon;

class ElasticAirportsRepository extends ElasticSearchCommon
{
    /**
     * Generate the elastic query for airports and return query of type string
     * @param array (criteria)
     * @return String $results
     */
    private function prepareDDSearchQuery(ElasticSearchSC $elasticSearchSC)
    {
        $query = null;
        $fromRecords = '';
        $sizeOfRecords = '';
        $sortBy = '';
        $query = '';
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
                        "popularity":  "desc",
                        "_score": "desc"
                      }
                    ]';
            }
            if(isset($criteria['cityId'])){
                $criteria = $elasticSearchSC->getCriteria();
                $cityId = $criteria['cityId'];    
                $query = '"query": {
                      "bool": {
                        "must": [
                          {
                            "match": {
                              "useForBooking": "true"
                            }
                          },
                          {
                            "match": {
                              "location.city.id": "'.$cityId.'"
                            }
                          }
                        ]
                      }
                    }';
            }
            if($term){
                $query = '
                        "query": {
                          "dis_max": {
                            "queries": [
                              {
                                "bool" : {
                                "must": [{
                                    "match": {
                                      "name": {
                                        "query": "'.$term.'",
                                        "fuzziness": 2, 
                                        "max_expansions":2,
                                        "operator": "and",
                                        "prefix_length": 2, 
                                        "boost": 3
                                      }
                                    }
                                  }
                                  ,{
                                    "term": {
                                      "useForBooking": "true"
                                    }
                                  }
                                ]
                                }
                              },
                              {
                                "bool" : {
                                "must": [{
                                    "match": {
                                      "name_key": {
                                        "query": "'.$term.'",
                                        "operator": "and",
                                        "boost": 10
                                      }
                                    }
                                  }
                                  ,{
                                    "term": {
                                      "useForBooking": "true"
                                    }
                                  }
                                ]
                                }
                              },
                              {
                                "bool" : {
                                "must": [{
                                    "match": {
                                      "code": {
                                        "query": "'.$term.'",
                                        "operator": "and",
                                        "boost": 10
                                      }
                                    }
                                  }
                                  ,{
                                    "term": {
                                      "useForBooking": "true"
                                    }
                                  }
                                ]
                                }
                              },
                              {
                                "bool" : {
                                "must": [{
                                    "match": {
                                      "location.city.name": {
                                        "query": "'.$term.'",
                                        "operator": "and",
                                        "fuzziness": 2,
                                        "max_expansions":2,
                                        "prefix_length": 3, 
                                        "boost": 7
                                      }
                                    }
                                  }
                                  ,{
                                    "term": {
                                      "useForBooking": "true"
                                    }
                                  }
                                ]
                                }
                              },
                              {
                                "bool":{
                                  "must":[
                                    {
                                      "match_phrase_prefix":{
                                        "location.city.name":{
                                          "query":"'.$term.'",
                                          "boost": 7
                                        }
                                      }
                                    },
                                    {
                                      "term":{
                                        "useForBooking":"true"
                                      }
                                    }
                                  ]
                                }
                              },
                              {
                                "bool" : {
                                "must": [{
                                    "match": {
                                      "location.country.name": {
                                        "query": "'.$term.'",
                                        "operator": "and",
                                        "fuzziness": 2,
                                        "max_expansions":2,
                                        "prefix_length": 3, 
                                        "boost": 1
                                      }
                                    }
                                  }
                                  ,{
                                    "term": {
                                      "useForBooking": "true"
                                    }
                                  }
                                ]
                                }
                              }

                            ]
                          }
                        },
                        "suggest": {
                          "text": "'.$term.'",
                          "suggest_name": {
                            "phrase": {
                              "field": "name",
                              "size": 2,
                              "gram_size": 3,
                              "direct_generator": [
                                {
                                  "field": "name",
                                  "suggest_mode": "always"
                                }
                              ],
                              "highlight": {
                                "pre_tag": "",
                                "post_tag": ""
                              }
                            }
                          },
                          "suggest_city": {
                            "phrase": {
                              "field": "location.city.name",
                              "size": 2,
                              "gram_size": 3,
                              "direct_generator": [
                                {
                                  "field": "location.city.name",
                                  "suggest_mode": "always"
                                }
                              ],
                              "highlight": {
                                "pre_tag": "",
                                "post_tag": ""
                              }
                            }
                          }
                        },
                        "aggs": {
                          "cityId": {
                            "terms": {
                              "field": "location.city.id",
                              "order": {
                                "max_record": "desc"
                              }
                            },
                            "aggs": {
                              "max_record": {
                                "max": {
                                    "script": {
                                      "source": "_score * (1 + doc.popularity.value)"
                                    }
                                }
                              }
                            }
                          }
                        },
                        "highlight": {
                          "fields": {
                            "name": {},
                            "code": {},
                            "location.city.name": {},
                            "location.country.name": {}
                          },
                          "pre_tags": [
                            "<em>"
                          ],
                          "post_tags": [
                            "</em>"
                          ]
                        }';
            }
            $finalQuery = '
                        {
                            '.$fromRecords.$sizeOfRecords.$query.' , '.$sortBy.'
                        }
                     ';
            //
            

         }
         
         return $finalQuery;
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
