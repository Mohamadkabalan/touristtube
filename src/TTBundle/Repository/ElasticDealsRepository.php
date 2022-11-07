<?php

namespace TTBundle\Repository;

use TTBundle\Model\ElasticSearchSC;
use TTBundle\Repository\Common\ElasticSearchCommon;
use Symfony\Bridge\Monolog\Logger;

class ElasticDealsRepository extends ElasticSearchCommon
{

	public function __construct(Logger $logger) {
        $this->logger        = $logger;
    }

    /**
     * Generate the elastic query for deals and return query of type string
     * @param array (criteria)
     * @return String $results
     */
    private function prepareDDSearchQuery(ElasticSearchSC $elasticSearchSC)
    {
        $query = null;
        $sizeOfRecords = '';
        $lang = 'en';
        if($elasticSearchSC != null && ($elasticSearchSC->getTerm() != '' || $elasticSearchSC->getCriteria() != ''))
        {
            $criteria = $elasticSearchSC->getCriteria();
            $term = $elasticSearchSC->getTerm();
            //
            //
            $criteria = $elasticSearchSC->getCriteria();
            if(isset($criteria['size'])){
                $size = $criteria['size'];    
                if($size){
                    $sizeOfRecords = ' "size": '.$size.', ';
                }
            } 
            if(isset($criteria['lang'])){
                $lang = $criteria['lang'];
            }
            $query = '{'.$sizeOfRecords.'
                        "query": {
                          "dis_max": {
                            "queries": [
                              {
                                "match": {
                                  "name": {
                                    "query": "'.$term.'",
                                    "operator": "and",
                                    "max_expansions":2,
                                    "fuzziness": 2,
                                    "boost": 2
                                  }
                                }
                              },
                              {
                                "match": {
                                  "translation.name_en": {
                                    "query": "'.$term.'",
                                    "operator": "and",
                                    "fuzziness": 2,
                                    "max_expansions":2,
                                    "boost": 4
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
                                    "boost": 2
                                  }
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
                                "pre_tag": "<em>",
                                "post_tag": "</em>"
                              }
                            }
                          }
                        },
                        "highlight": {
                          "fields": {
                            "name": {},
                            "translation.name_'.$lang.'": {},
                            "location.city.name": {},
                            "location.country.name": {}
                          },
                          "pre_tags": [
                            "<em>"
                          ],
                          "post_tags": [
                            "</em>"
                          ]
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
    
    /**
     * Generate the elastic query for hotels and return query of type string
     * @param array (criteria)
     * @return String $results
     */
    private function prepareDealCitySearchQuery(ElasticSearchSC $elasticSearchSC)
    {
        $query = null;
        $sizeOfRecords = '';
        $lang = 'en';
        if($elasticSearchSC != null && $elasticSearchSC->getTerm() != '')
        {
            $criteria = $elasticSearchSC->getCriteria();
            $term = $elasticSearchSC->getTerm();
            //
            //
            if($elasticSearchSC->getCriteria('size')){
                $criteria = $elasticSearchSC->getCriteria();
                $size = $criteria['size'];    
                if($size){
                    $sizeOfRecords = ' "size": '.$size.', ';
                }
            } 
            if($elasticSearchSC->getCriteria('lang')){
                $lang = $criteria['lang'];
            }
            //
            $query = '{'.$sizeOfRecords.'
                          "query": {
                            "dis_max": {
                              "queries": [
                                {
                                  "match": {
                                    "location.city.name": {
                                      "query": "'.$term.'",
                                      "fuzziness": 2,
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
                                      "fuzziness": 0,
                                      "max_expansions":2,
                                      "boost": 2
                                    }
                                  }
                                },
                                {
                                  "match": {
                                    "location.city.name_key": {
                                      "query": "'.$term.'",
                                      "boost": 4
                                    }
                                  }
                                },
                                {
                                  "match": {
                                    "tags": {
                                      "query": "'.$term.'",
                                      "fuzziness": 0,
                                      "max_expansions":2,
                                      "boost": 2
                                    }
                                  }
                                }
                              ]
                            }
                          },
                          "aggs": {
                            "cityName": {
                              "terms": {
                                "field": "location.city.name_key",
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
                                },
                                "top_sales_hits": {
                                  "top_hits":{
                                    "_source":{
                                      "includes": [ "location.city.id" ]
                                    }
                                  }
                                }
                              }
                            },
                            "trans_cityName": {
                              "terms": {
                                "field": "translation.name_'.$lang.'.keyword",
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
                              "location.city.name": {},
                              "translation.name_'.$lang.'": {},
                              "translation.city_'.$lang.'": {}
                            },
                            "pre_tags": [
                              "<em>"
                            ],
                            "post_tags": [
                              "</em>"
                            ]
                          },
                          "sort": [
                            {
                              "popularity": "desc",
                              "location.city.popularity": "desc",
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
    public function searchDealCity(ElasticSearchSC $elasticSearchSC)
    {
        if(!$elasticSearchSC->getQuery())
            $elasticSearchSC->setQuery( $this->prepareDealCitySearchQuery($elasticSearchSC) );
        //
        $queryResult = $this->search($elasticSearchSC);
        //
        return $queryResult;
    }

}
