<?php

namespace TTBundle\Repository;

use TTBundle\Model\ElasticSearchSC;
use TTBundle\Repository\Common\ElasticSearchCommon;
use Symfony\Bridge\Monolog\Logger;

class ElasticDictionaryRepository extends ElasticSearchCommon
{
	public function __construct(Logger $logger) {
        $this->logger        = $logger;
    }

    /**
     * Generate the elastic query for dictionary and return query of type string
     * @param array (criteria)
     * @return String $results
     */
    private function prepareDDSearchQuery(ElasticSearchSC $elasticSearchSC)
    {
        $query = null;
        if($elasticSearchSC != null && ($elasticSearchSC->getTerm() != '' || $elasticSearchSC->getCriteria() != ''))
        {
            $term = $elasticSearchSC->getTerm();
            $query = '
                        {
                          "query": {
                            "match": {
                              "keyword": "'.$term.'"
                            }
                          }
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
