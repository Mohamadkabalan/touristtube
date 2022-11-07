<?php

namespace TTBundle\Repository;

use TTBundle\Model\ElasticSearchSC;
use TTBundle\Repository\Common\ElasticSearchCommon;

class ElasticHotelRepository extends ElasticSearchCommon
{
    /**
     * Generate the elastic query for hotels and return query of type string
     * @param array (criteria)
     * @return String $results
     */
    private function prepareDDSearchQuery(ElasticSearchSC $elasticSearchSC)
    {
        $query = null;
        $sizeOfRecords = '';
        $fromRecords = ''; 
        $sortByQuery = '';
        $subQuery = '';
        $termQuery = '';
        $aggsQuery = '';
        $lang = 'en';
        
        if ($elasticSearchSC != null && ($elasticSearchSC->getTerm() != '' || $elasticSearchSC->getCriteria() != ''))
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
			
            if (isset($criteria['sortBy'])) {
                $sortBy = $criteria['sortBy'];    
                if ($sortBy) {
                    if (isset($criteria['sortGeolocation'])) {
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
					
                    $sortByQuery = '
                        "sort": [
                          {
                            "'.$sortBy.'": "desc",
                            "_score": "desc"
                          }
                        ]';
                }
            }else {
                $sortByQuery = '
                    "sort": [
                      {
                        "_score": "desc",
                        "popularity": "desc"
                      }
                    ]';
            }
			
			$subQueryStrings = array();
			
            if (isset($criteria['cityId']) && $criteria['cityId'] != '') {
                
                $subQueryStrings['cityId'] = '{
                                  "match": {
                                    "location.city.id": "'.$criteria['cityId'].'"
                                  }
                                }';
			}
			
			if (isset($criteria['cityName'])) {
                
                $subQueryStrings['cityName'] = '{
                                  "match": {
                                    "location.city.name": "'.$criteria['cityName'].'"
                                  }
                                }';
            }
			
			if (isset($criteria['locationId']) && $criteria['locationId']) {
				
				$subQueryStrings['locationId'] = '{
								"match": {
									"location.id": "'.$criteria['locationId'].'"
								}
							}';
			}
			
			if (isset($criteria['countryCode'])) {
                
                $subQueryStrings['countryCode'] = '{
                                        "match": {
                                          "location.country.code": "'.$criteria['countryCode'].'"
                                        }
                                      }';
            }
			
            if (isset($criteria['countryName'])) {
                
                $subQueryStrings['countryName'] = '{
                                        "match": {
                                          "location.country.name": "'.$criteria['countryName'].'"
                                        }
                                      }';
            }
			
            if (isset($criteria['vendorId'])) {
                $ids = implode($criteria['vendorId'],',');
                $subQueryStrings['vendorId'] = ',"filter": [ { "terms": {"available_vendors.id": ['.$ids.']}}] ';
            }
			
            if (isset($criteria['imageExists']) && $criteria['imageExists']) {
				
				$subQueryStrings['imageExists'] = ' {
                                        "exists" : { "field" : "media.id" }
                                      }';
            }
			
            if(isset($criteria['oldQuery']) && $criteria['oldQuery'] == 1) {
				
                $termQuery = '"query" : {
                                "dis_max": {
                                  "queries": [
                                    {
                                      "bool": {
                                        "must": [
                                          ';
				
				$delimiter = '';
				
				if ($subQueryStrings)
				{
					foreach ($subQueryStrings as $subKey => $subQueryString)
					{
						if (in_array($subKey, array('locationId', 'imageExists')))
							continue;
						
						$subQuery .= $delimiter.$subQueryString;
						
						$delimiter = ',';
					}
				}
				else
				{
					$subQuery = '{
                                  "match_all": {}
                                }';
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
			else if ($term) {
                $termQuery = '"query" : {
                                "bool":{
                                  "must": {
                                    "dis_max": {
                                       "queries": [
                                           {
                                            "match":{
                                              "name":{
                                                "query": "'.$term.'", 
                                                "operator": "and", 
                                                "prefix_length": 2, 
                                                "fuzziness": 2,
                                                "max_expansions":2, 
                                                "boost": 2
                                              }
                                            }
                                           },
                                         {
                                           "match": {
                                             "name": {
                                               "query": "'.$term.'",
                                               "fuzziness": 1,
                                               "max_expansions":2,
                                               "prefix_length": 2,
                                               "boost": 1.5
                                             }
                                           }
                                         },
                                           {
                                            "match":{
                                              "name_key":{
                                                "query": "'.$term.'",
                                                "operator": "and",
                                                "boost": 50
                                              }
                                            }
                                           },
                                           {
                                            "match":{
                                              "translation.name_'.$lang.'":{
                                                "query": "'.$term.'",
                                                "operator": "and",
                                                "prefix_length": 2,
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
                                                "operator": "and",
                                                "prefix_length": 2,
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
                                                "prefix_length": 2,
                                                "fuzziness": 1,
                                                "boost": 2
                                              }
                                            }
                                           }
                                         ]
                                       }
                                     }'; 
                                if(isset($subQueryStrings['vendorId'])){
                                    $termQuery.= $subQueryStrings['vendorId'];

                                }
                                $termQuery .='}
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
                                      },
                                      "translation.name_'.$lang.'": {},
                                      "location.city.name": {},
                                      "location.country.name": {}
                                    },
                                    "pre_tags": ["<em>"],
                                    "post_tags": ["</em>"]
                                 }';
            }
            
            if(isset($criteria['aggs'])){
                $aggs = $criteria['aggs'];
                $aggsQuery = '  "aggs" : {
                                  "'.$aggs.'" : {
                                    "terms" : {
                                        "field" : "'.$aggs.'",
                                        "size": 10
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
            if($termQuery != ''){
                $query .= $termQuery.' , ';
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
