<?php

namespace HotelBundle\vendors\TTApi\v1;

use TTBundle\Utils\Utils;
use Symfony\Component\DependencyInjection\ContainerInterface;
use HotelBundle\vendors\TTApi\Config AS TTApiConfig;
use HotelBundle\Model\RspApiResponse;

class TTApiHandler
{
    private $config;

    /**
     * The __construct when we make a new instance of TTApiHandler class.
     *
     * @param Utils $utils
     * @param ContainerInterface $container
     */
    public function __construct(Utils $utils, ContainerInterface $container)
    {
        $this->utils     = $utils;
        $this->container = $container;

        $this->logger = $this->container->get('HotelLogger');

        // initialize config parameters
        $this->config = new TTApiConfig($container);
    }

    /**
     * This method calls the TTApi to get all available hotels based on the search criteria.
     *
     * @param Array $languageGet    The language.
     * @param Array $requestData    The request data (e.g. room criteria, etc).
     * @param String $infoSource    The info source or source type (Optional; default='MutliSource').
     * @return Array List of hotels.
     */
    public function getHotelsAvailability($languageGet, $requestData, $infoSource = 'MultiSource')
    {
        $result = new RspApiResponse();

        // initialize params
        $params = $this->initCriteria($languageGet, $requestData, $infoSource);

        $attempts = 1;
        while ($attempts <= $this->config->max_attempts) {
            // get access token
            $auth = $this->getAuthToken();

            if (!$auth->hasError()) {
                $header = array(
                    "Cache-Control: no-cache",
                    "Content-Type: application/json"
                );

                $url           = "hotels/getHotelsAvailability?env=".$this->config->env."&access_token={$auth->getResponse()['access_token']}&batch=".$this->config->batch_request;
                $attemptParams = json_encode($params);

                $result = $this->sendRequest($attemptParams, $header, $url, __FUNCTION__, $attempts);
                if (isset($result->getResponse()['data'])) {
                    $result->setResponse($result->getResponse()['data']);
                }
            } else {
                $result->setStatus($auth->getStatus());
            }

            if (!$result->hasError()) {
                break;
            }

            $attempts++;
            sleep(1);
        }

        return $result;
    }

    /**
     * This method calls the TTApi to get the access_token.
     *
     * @return Array    TTApi credentials.
     */
    private function getAuthToken()
    {
        $result = new RspApiResponse();

        $params = array(
            'grant_type' => $this->config->grant_type,
            'username' => $this->config->username,
            'password' => $this->config->password
        );

        $header = array(
            "Authorization: Basic dHJ1c3RlZC1hcHA6c2VjcmV0",
            "Cache-Control: no-cache",
            "content-type: multipart/form-data"
        );

        $attempts = 1;
        while ($attempts <= $this->config->max_attempts) {
            $result = $this->sendRequest($params, $header, 'uaa/oauth/token', __FUNCTION__, $attempts);
            if (!$result->hasError()) {
                break;
            }

            $attempts++;
            sleep(1);
        }

        return $result;
    }

    /**
     * This method initializes the criteria/params use when calling TTApi availability.
     *
     * @param string $languageGet   The site language
     * @param Array $requestData    The request data (room criteria, etc).
     * @param String $infoSource    The info source or source type.
     * @return Array    The criteria/params.
     */
    private function initCriteria($languageGet, $requestData, $infoSource)
    {
        $hotelRooms = array();

        if (isset($requestData['roomCriteria']) && !empty($requestData['roomCriteria'])) {
            foreach ($requestData['roomCriteria'] as $room) {
                $guests = $room['roomStayCandidate']['guestCount'];

                // get adult
                $adult     = array_shift($guests);
                $hotelRoom = array(
                    'quantity' => $room['roomStayCandidate']['quantity'],
                    'nbAdults' => $adult['count']
                );

                // get children
                if (count($guests) > 0) {
                    $hotelRoom['children'] = array();
                    foreach ($guests as $guest) {
                        if (isset($guest['age'])) {
                            $hotelRoom['children'][] = array('age' => $guest['age']);
                        }
                    }
                }

                $hotelRooms[] = $hotelRoom;
            }
        }

        $criteria = array(
            'requestId' => $requestData['id'], // the PK of hotel_search_request
            'callBack' => array(
                'endPointUrl' => $this->utils->generateLangURL($languageGet, '/hotels/searchRestApiCallback')
            ),
            'sourceType' => strtoupper($infoSource),
            'startDate' => $requestData['fromDate'],
            'endDate' => $requestData['toDate'],
            'city' => array(
                'code' => ''
            ),
            'vendors' => '',
            'hotelRooms' => $hotelRooms
        );

        if (isset($requestData['hotelVendor']) && !empty($requestData['hotelVendor'])) {
            $criteria['vendors'] = array();
            foreach ($requestData['hotelVendor'] AS $vendor) {
                $criteria['vendors'][] = $vendor->toArray();
            }
        }

        if ($requestData['entityType'] == $this->container->getParameter('SOCIAL_ENTITY_CITY') || $requestData['entityType'] == $this->container->getParameter('SOCIAL_ENTITY_HOTEL')) {
            $criteria['hotels'] = array();
            foreach ($requestData['hotelSources'] AS $hotelSource) {
                $criteria['hotels'][] = $hotelSource->toArray();
            }
        } else {
            // search by longitude and latitude
            if (!empty($requestData['longitude']) && !empty($requestData['latitude'])) {
                $criteria['geolocation'] = array(
                    'latitude' => $requestData['latitude'],
                    'longitude' => $requestData['longitude']
                );

                if (!empty($requestData['distance'])) {
                    $criteria['geolocation']['radius'] = $requestData['distance'];
                }
            }
        }

        return $criteria;
    }

    /**
     * This method is responsible for calling the Hotels TTApi
     *
     * @param Mixed $params The curl post data.
     * @param Array $header The curl headers.
     * @param String $url   The curl url.
     * @param String $requestAPI    The api or operation making the call.
     * @param Number $attempt   Identifies the current attempt number.
     * @return Array    The TTApi results.
     */
    private function sendRequest($params, $header, $url, $requestAPI = '', $attempt = 1)
    {
        $result = new RspApiResponse();
        $result->setRequest($params);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_PORT => $this->config->port,
            CURLOPT_URL => $this->config->url."{$url}",
            CURLOPT_SSL_VERIFYPEER => $this->config->ssl_verify_peer,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HTTPHEADER => $header, 
			CURLOPT_FOLLOWLOCATION => true
        ));

        $response = curl_exec($curl);
        $error    = curl_error($curl);

        //$info           = curl_getinfo($curl);
        //$result['info'] = $info;

        if (!$error) {
            $response = json_decode($response, true);

            $error = '';
            if (isset($response['error_description'])) {
                $error = $response['error_description'];
            } elseif (isset($response['exception'])) {
                $error = $response['exception'];
            }

            if (trim($error)) {
                $result->getStatus()->setError($response['error'].": ".$error);
            } else {
                $result->setResponse($response);
            }
        } else {
            $result->getStatus()->setError($error);
        }

        // log
        if (!$requestAPI) {
            $requestAPI = __FUNCTION__;
        }

        if (is_string($params) && is_array(json_decode($params, true))) {
            $params = json_decode($params, true);
        }

        //$result['attempts'] = $attempt;

        $log['timeStart']    = $params['timeStart'] = date('YmdHis', time());
        $log['errorLogFile'] = $this->logger->getLogFile('amadeus', $requestAPI, $params);
        $log['log']          = print_r(array_merge($result->toArray(), array('params' => $params)), true);

        $this->logger->info($log['errorLogFile'], 'log', $log['log']);

        curl_close($curl);

        $result->getStatus()->setErrorLogFile($log['errorLogFile']);
        return $result;
    }
}
