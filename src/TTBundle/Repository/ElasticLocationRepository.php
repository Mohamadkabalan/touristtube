<?php

namespace TTBundle\Repository;

use TTBundle\Model\ElasticSearchSC;
use TTBundle\Repository\Common\ElasticSearchCommon;

class ElasticLocationRepository extends ElasticSearchCommon
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
        $queryCountryCode = '';
        $lang = 'en';
        $onlyCity ="";
        $matchType = "";

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

            if(isset($criteria['onlyCity']) && $criteria['onlyCity'] == 1){
                $cityOnly = $criteria['onlyCity'];
                $onlyCity = '{ 
                                "match": {
                                    "type":  "city"
                                } 
                            },';
            }

            if(isset($criteria['type'])){
                $type = $criteria['typre'];
                if($type){
                    $matchType = '{ 
                                    "match": {
                                        "type": {
                                            "query": "' . $type . '"
                                        }
                                    } 
                                },';
                }
            }

            if(isset($criteria['countryCode'])){
                $countryCode = $criteria['countryCode'];
                if($countryCode){
                    $queryCountryCode = '{
                                            "match": {
                                                "contryCode": "'.$countryCode.'"
                                            }
                                        },';
                }
            }

            $query = '{
                ' . $sizeOfRecords .'
                ' . $fromRecords .'
                "query": {
                    "bool": {
                        "must": ['
                                . $onlyCity
                                . $matchType
                                . $queryCountryCode 
                        . '

                            {
                            	"bool": {
        
                                	"should": [
                                        {
                                            "query_string": {
                                                "default_field": "name",
                                                "query": "' . $term . '*"
                                            }
                                        },
                                        {
                                            "match": {
                                                "name": {
                                                    "query": "' . $term . '",
                                                    "operator": "and",
                                                    "fuzziness": 3,
                                                    "max_expansions": 20,
                                                    "prefix_length": 1
                                                }
                                            }
                                        },
                                        {
                                            "match": {
                                                "name_key": {
                                                    "query": "' . $term . '",
                                                    "boost": 10
                                                }
                                            }
                                        }
                                   ]

                            	}
                            }
                        ]
                    }
                },
                "sort": [
                    {
                        "_score": {
                            "order": "desc"
                        }
                    },
                    {
                        "popularity": {
                            "order": "desc"
                        }
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
