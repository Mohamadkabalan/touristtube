<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace TTBundle\Repository\Common;

use TTBundle\Model\ElasticSearchSC;
//use Elastica\Exception\ConnectionException as EConnectionException;
use Elasticsearch\ClientBuilder;
//use Elastica\Exception\ResponseException as EResponseException;
use Symfony\Bridge\Monolog\Logger;

	
/**
 * Description of ElasticSearchCommon
 *
 * @author malak
 */
class ElasticSearchCommon {

    private $hosts=NULL;
    private $max_api_call_attempts    = 3;
    private $pause_between_retries_us = 500000;
	private $connect_timeout_ms = 200;
    private $url_source=NULL;
    private $url_criteria=array();
	protected $logger;

    /**
     * execute curl elastic search
     *
     * @return response
     */
    protected function search(ElasticSearchSC $elasticSearchSC){

		$this->hosts = $elasticSearchSC->getHosts(); /*[
						[
							'host' => 'localhost',
							'port' => '9200',
							'scheme' => 'http',
							'user' => null,
							'pass' => null
						],
						[
							'host' => 'localhost',
						]
					];*/

		$this->url_source = $elasticSearchSC->getUrlSource();
        $this->url_criteria = $elasticSearchSC->getCriteria();
        $index = $elasticSearchSC->getIndex();
        $host = $elasticSearchSC->getHost();
        $serverPort = $elasticSearchSC->getPort();
        $type = $elasticSearchSC->getDocType();
        $query = $elasticSearchSC->getQuery();


        //$url = "http://".$host.":".$serverPort."/".$index."/".$type."/_search";
        //$result = $this->executeCURLCall($url, $query);

        $result = $this->executeCall($query, $index, $type);
		//
		return $result;
    }

    protected function executeCall($query, $index, $type)
    {
        $res   = array();
        $res['hits']['hits'] = array();
        $res['aggregations'] = array();
        $res['tt_exceptions'] = array();
        $res['hits']['total'] = 0;

		try {

			$selector = '\Elasticsearch\ConnectionPool\Selectors\RoundRobinSelector';
			$client = ClientBuilder::create()
						->setRetries($this->max_api_call_attempts)
						->setHosts($this->hosts)
						->setLogger($this->logger)
						->setSelector($selector)
						->setConnectionPool('\Elasticsearch\ConnectionPool\SniffingConnectionPool', [])
						->allowBadJSONSerialization()
						//->setConnectionPool('\Elasticsearch\ConnectionPool\StaticNoPingConnectionPool', [])
						->build();

			$searchParams = [
				'index' => $index,
				'type' => $type,
				'body' => $query
			];

			$res = $client->search($searchParams);

		} catch (Exception $e) {

			$this->logger->error("Error executing query on ES " . $query);
			$this->logger->error("Error executing query on ES ERROR: " . json_encode($e));


			//@TODO The Exception thrown from the client is never catched here and should be checked why, the below has been filed based on the old method just to not keep to catch empty
			$res['error_encountered'] = true;
			$res['criteria'] = $this->url_criteria;
			$res['tt_exceptions'] = $e;
			$res['url_source'] = $this->url_source;

			/*
			$previous = $e->getPrevious();
			if ($previous instanceof Elasticsearch\Common\Exceptions\MaxRetriesException) {
				echo "Max retries!";
			}
			*/
		}

		$res = json_encode($res);

		return $res;
	}

    protected function executeCURLCall($url, $query, $attempt_number = 1, $exception_stack = array())
    {
        $error_encountered = false;
        $error_query = false;
        $res_array   = array();
        $res_array['hits']['hits'] = array();
        $res_array['aggregations'] = array();
        $res_array['tt_exceptions'] = array();
        $res_array['hits']['total'] = 0;
        $res = json_encode($res_array);
        $curl = null;
        
        try {
            $header = array(
                'Content-Type: application/json'
            );
            
            
//             // DO NOT REMOVE
//             throw (new EConnectionException('Dummy test exception message', null, null));
//             // throw (new \Exception('Dummy test exception message'));
            
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS, $this->connect_timeout_ms);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			
            $res = curl_exec($curl);
            /*
            if (!curl_errno($curl)) {
              $info = curl_getinfo($curl);
              print_r($info);
            }
            */
        } catch (\Exception $e) {
            $error_encountered = true;
            $exception_stack[] = array('type' => get_class($e), 'exception' => $e->getMessage(), 'traceURL' => $e->getTraceAsString() );
        }
        if ($curl != null)
            curl_close($curl);

        if ($error_encountered && $attempt_number < $this->max_api_call_attempts) {
            usleep($this->pause_between_retries_us);

            $attempt_number++;

            return $this->executeCall($url, $query, $attempt_number, $exception_stack);
        }
        $resArray = json_decode($res,true);
        if(isset($resArray['error'])){
            if(isset($resArray['error']['root_cause'])){
                $error_query = true;
                $message = $resArray['error']['root_cause'][0]['reason'];
                $type = $resArray['error']['root_cause'][0]['type'];
                $exception_stack[] = array('type' => $type, 'exception' => $message, 'traceURL' => $this->url_source );
                $res_array['error_encountered'] = $error_query;
                $res_array['criteria'] = $this->url_criteria;
                $res_array['tt_exceptions'] = $exception_stack;
                $res_array['url_source'] = $this->url_source;
                $res = json_encode($res_array);
            }
        }else if($error_encountered){
            $res_array['error_encountered'] = $error_encountered;
            $res_array['criteria'] = $this->url_criteria;
            $res_array['tt_exceptions'] = $exception_stack;
            $res_array['url_source'] = $this->url_source;
            $res = json_encode($res_array);
        }else if(!isset($resArray['hits'])){
            $res_array['error_encountered'] = true;
            $message = $this->get('app.utils')->flatten_array($resArray);
            $exception_stack[] = array('type' => 'error no hits', 'exception' => $message, 'traceURL' => $this->url_source );
            $res_array['criteria'] = $this->url_criteria;
            $res_array['tt_exceptions'] = $exception_stack;
            $res_array['url_source'] = $this->url_source;
            $res = json_encode($res_array);
        }
        
        return $res;
    }

    public function flatten_array($data_array)
    {
        if (!$data_array) return '';
        
        $flattened_array = '';
        
        foreach ($data_array as $data_key => $data_value) {
            $flattened_array .= ($flattened_array ? ', ' : '').$data_key.':: ';
            
            if (is_array($data_value)) {
                $flattened_array .= $this->flatten_array($data_value);
            } else {
                $flattened_array .= $data_value;
            }
        }
        
        return $flattened_array;
    }
}
