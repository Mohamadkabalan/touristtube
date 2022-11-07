<?php

namespace HotelBundle\vendors\Amadeus;

use SoapVar;
use TTBundle\Utils\Utils;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Debug\Exception\ContextErrorException;
use HotelBundle\vendors\Amadeus\Config AS AmadeusConfig;
use HotelBundle\vendors\Amadeus\AmadeusNormalizer;
use HotelBundle\Model\HotelApiResponse;
use HotelBundle\Model\HotelCancellation;
use HotelBundle\Model\HotelBookingCriteria;
use HotelBundle\Model\HotelSC;

class AmadeusHandler extends AmadeusRequestTemplates
{
    private $config;
    private $amadeusNormalizer;
    private $DIR              = '';
    private $CLIENT           = '';
    private $OPT              = array();
    private $URL              = '';
    private $WSDL_ENVIRONMENT = '';
    private $DOM_XPATH        = array();
    private $SESSION_DATA     = array();    /* DO NOT USE this except for testing raw request/response */

    /**
     * The __construct when we make a new instance of AmadeusHandler class.
     *
     * @param Utils $utils
     * @param ContainerInterface $container
     */
    public function __construct(Utils $utils, ContainerInterface $container)
    {
        // initialize parameters
        $this->config            = new AmadeusConfig($container);
        $this->amadeusNormalizer = new AmadeusNormalizer($utils, $container, $this);

        $this->utils     = $utils;
        $this->container = $container;

        $this->logger           = $this->container->get('HotelLogger');
        $this->DIR              = $this->container->getParameter('CONFIG_SERVER_ROOT').'/assets/hotels/xml/amadeus/';
        $this->URL              = $this->config->endpoint_url;
        $this->WSDL_ENVIRONMENT = $this->config->wsdl_environment;

        $this->OPT = array(
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
            'trace' => true,
            'exceptions' => true,
            'cache_wsdl' => WSDL_CACHE_BOTH,
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
        );
    }

    /**
     * This method returns the AmadeusNormalizer instance.
     * @return AmadeusNormalizer    The instance.
     */
    public function getNormalizer()
    {
        return $this->amadeusNormalizer;
    }

    //*****************************************************************************************
    // Direct API Functions
    /**
     * This method calls the Amadeus API: Hotel_MultiSingleAvailability and Hotel_EnhancedPricing.
     *
     * @param Array $params The parameters sent to the API.
     * @param String $requestAPI    The API (e.g. Hotel_MultiSingleAvailability or Hotel_EnhancedPricing)
     * @return Array    The API results.
     */
    private function otaHotelAvail($params, $requestAPI)
    {
        $data = $this->otaHotelAvailXML($params);
        $data = new SoapVar($data, XSD_ANYXML);
        return $this->sendClientRequest($requestAPI, $data, $params);
    }

    /**
     * This method calls the Amadeus API: PNR_AddMultiElements.
     *
     * @param Array $params     The parameters sent to the API.
     * @param Boolean $create   Set to true if we do PNR creation
     * @param Boolean $commit   Set to true if we do PNR commit
     * @return Array            The API results.
     */
    private function pnrAddMultiElements($params, $create, $commit)
    {
        $data = $this->pnrAddMultiElementsXML($params, $create, $commit);
        $data = new \SoapVar($data, XSD_ANYXML);
        return $this->sendClientRequest('PNR_AddMultiElements', $data, $params);
    }

    /**
     * This method calls the Amadeus API: Hotel_Sell.
     *
     * @param Array $params  The parameters sent to the API.
     * @return Array    The API results.
     */
    private function hotelSell($params)
    {
        $data = $this->hotelSellXML($params);
        $data = new \SoapVar($data, XSD_ANYXML);
        return $this->sendClientRequest('Hotel_Sell', $data, $params);
    }

    /**
     * This method calls the Amadeus API: PNR_Retrieve specific to retrieval By Traveler's Surname.
     *
     * @param Array $params The parameters sent to the API.
     * @return Array    The API results.
     */
    public function pnrRetrieveByName($params)
    {
        $data = $this->pnrRetrieveByNameXML($params);
        $data = new \SoapVar($data, XSD_ANYXML);
        return $this->sendClientRequest('PNR_Retrieve', $data, $params);
    }

    /**
     * This method calls the Amadeus API: PNR_Retrieve.
     *
     * @param Array $params The parameters sent to the API.
     * @return Array    The API results.
     */
    public function pnrRetrieve($params)
    {
        $data = $this->pnrRetrieveXML($params);
        $data = new \SoapVar($data, XSD_ANYXML);
        return $this->sendClientRequest('PNR_Retrieve', $data, $params);
    }

    /**
     * This method calls the Amadeus API: PNR_Cancel.
     *
     * @param Array $params The parameters sent to the API.
     * @param Boolean $endSession   Set to true tell API that this is the last session call.
     * @return Array    The API results.
     */
    private function pnrCancel($params, $endSession = true)
    {
        $data = $this->pnrCancelXML($params);
        $data = new \SoapVar($data, XSD_ANYXML);
        return $this->sendClientRequest('PNR_Cancel', $data, $params, $endSession);
    }

    /**
     * This method calls the Amadeus API: Security_SignOut.
     *
     * @param Array $params The parameters sent to the API.
     * @return Array    The API results.
     */
    public function securitySignOut($params)
    {
        $params['logName'] = 'Hotel_SecuritySignOut';

        $data = $this->securitySignOutXML();
        $data = new \SoapVar($data, XSD_ANYXML);
        return $this->sendClientRequest('Security_SignOut', $data, $params, true);
    }

    /**
     * This method calls the Amadeus API: PNR_NameChange.
     *
     * @param Array $params The parameters sent to the API.
     * @return Array    The API results.
     */
    public function pnrNameChange($params)
    {
        $data = $this->pnrNameChangeXML($params);
        $data = new \SoapVar($data, XSD_ANYXML);
        return $this->sendClientRequest('PNR_NameChange', $data, $params);
    }

    /**
     * This method calls the Amadeus API: Hotel_DescriptiveInfo.
     *
     * @param Array $params The parameters sent to the API.
     * @param Boolean $log  Set to to true if logging is allowed (Optional; default=true).
     * @return Array    The API results.
     */
    private function hotelDescriptiveInfo($params, $log = true)
    {
        $data = $this->hotelDescriptiveInfoXML($params);
        $data = new \SoapVar($data, XSD_ANYXML);
        return $this->sendClientRequest('Hotel_DescriptiveInfo', $data, $params, false, $log);
    }

    /**
     * This method calls the Amadeus API: Hotel_CompleteReservationDetails.
     *
     * @param Array $params The parameters sent to the API.
     * @return Array    The API results.
     */
    public function hotelCompleteReservationDetails($params)
    {
        $params['logName'] = 'Hotel_CompleteReservationDetails';

        $data = $this->hotelCompleteReservationDetailsXML($params);
        $data = new \SoapVar($data, XSD_ANYXML);
        return $this->sendClientRequest('Hotel_CompleteReservationDetails', $data, $params);
    }

    //*****************************************************************************************
    // UI Integrated Functions
    /**
     * This method calls the Amadeus API: Hotel_MultiSingleAvailability to get available hotels
     *
     * @param Array $params The parameters sent to the API.
     * @return Array    The API results.
     */
    public function getAvailableHotels($params)
    {
        $params['logName'] = 'Hotel_ListAvail';
        return $this->otaHotelAvail($params, 'Hotel_MultiSingleAvailability');
    }

    /**
     * This method returns the hotels to be displayed after processing the response from API.
     *
     * @param Mixed $data   The data which contains the list of XML response from API.
     * @param HotelSC $hotelSC  The instance of HotelSC
     * @return HotelAvailability
     */
    public function getHotelListingAvailabilityResponse($data, $hotelSC)
    {
        return $toreturn = $this->amadeusNormalizer->processHotelListingAvailabilityResponse($data, $hotelSC);
    }

    /**
     * This is a wrapper method to AmadeusNormalizer::parseAvailabilityResponse.
     *
     * @param Array $responseArr Array of XML response.
     * @param HotelSC $hotelSC  The instance of HotelSC
     * @return HotelAvailability
     */
    public function parseAvailabilityResponse(array $responseArr, HotelSC $hotelSC)
    {
        return $this->amadeusNormalizer->parseAvailabilityResponse($responseArr, $hotelSC);
    }

    /**
     * This method calls the Amadeus API: Hotel_MultiSingleAvailability to get Hotel Offers
     *
     * @param Array $params             The parameters sent to the API.
     * @param Array $logParams
     * @param Array $otaPaymentTypes
     * @param Array $otaChildCOde
     * @param Boolean $prepaidOnly      Flag to return pre-paid only offers (Optional; Default: false)
     * @return HotelApiResponse
     */
    public function getHotelOffers($params, $logParams, $otaPaymentTypes, $otaChildCOde, $prepaidOnly = false)
    {
        $params['logName'] = 'Hotel_Offers';

        $response = new HotelApiResponse;

        $totalWaitingTime      = 0;
        $pauseBetweenRetriesUs = $this->container->getParameter('PAUSE_BETWEEN_RETRIES_US');
        $apiCallMaxAttempts    = $this->container->getParameter('MAX_API_CALL_ATTEMPTS') + 1;
        $apiCallSecondsToRetry = $pauseBetweenRetriesUs / 1000000;

        $roomStayCandidate              = $params['segments'];
        $roomStayCandidate['childCode'] = $otaChildCOde;

        for ($attemptNumber = 1; $attemptNumber <= $apiCallMaxAttempts; $attemptNumber++) {
            $totalNumOffers = 0;
            $timeStart      = microtime(true);
            $results        = $this->otaHotelAvail($params, 'Hotel_MultiSingleAvailability');
            $response->setSession($results['session']);
            $response->setXmlResponse($results['lastResponse']);

            if (isset($results['error'])) {
                $response->setError($results['error']);
            } else {
                $hotel = $this->amadeusNormalizer->processHotelOffersResponse($response->getXmlResponse(), $roomStayCandidate, $otaPaymentTypes, $prepaidOnly);

                if (!empty($hotel->getRoomOffers())) {
                    $response->setSuccess(true);
                    $response->setData(array('hotel' => $hotel));

                    $totalNumOffers = $hotel->getTotalNumOffers();
                }

                if (!$response->isSuccess() && isset($results['warning'])) {
                    $response->setError($results['warning']);
                    $this->securitySignOut(array('stateful' => true, 'session' => $results['session'], 'hotelId' => $params['hotelId'], 'userId' => $params['userId']));
                }
            }

            $requestTime      = microtime(true) - $timeStart;
            $totalWaitingTime += $requestTime;

            if ($attemptNumber != 1) {
                $totalWaitingTime -= $apiCallSecondsToRetry;
            }

            $logParams['apiCallAttemptNumber']           = $attemptNumber;
            $logParams['apiCallMaxAttempts']             = $apiCallMaxAttempts;
            $logParams['apiCallSecondsToRetry']          = $apiCallSecondsToRetry;
            $logParams['apiCallTimeSeconds']             = $requestTime;
            $logParams['apiCallTotalWaitingTimeSeconds'] = $totalWaitingTime;
            $logParams['totalNumOffers']                 = $totalNumOffers;
            $logParams['status']                         = ($response->isSuccess()) ? 'success' : 'failure';

            $this->logger->addHotelActivityLog('HOTELS', 'offers', $params['userId'], $logParams);

            if ($response->isSuccess()) {
                break;
            }

            if ($attemptNumber != $apiCallMaxAttempts) {
                usleep($pauseBetweenRetriesUs);
            }
        }

        return $response;
    }

    /**
     * This method calls the Amadeus API: Hotel_EnhancedPricing to get Hotel offer complete pricing information
     *
     * @param HotelBookingCriteria $hotelBC
     * @param Array $otaPaymentTypes
     * @param Array $otaChildCOde
     * @return HotelApiResponse
     */
    public function getHotelOffersEnhancedPricing($hotelBC, $otaPaymentTypes, $otaChildCOde)
    {
        $params = array(
            'session' => $hotelBC->getSession(),
            'segments' => $hotelBC->getSegments(),
            'echoToken' => 'Pricing',
            'summaryOnly' => 'false',
            'rateRangeOnly' => 'false',
            'availRatesOnly' => 'true',
            'stateful' => true,
            'hotelId' => $hotelBC->getHotelId(),
            'userId' => $hotelBC->getUserId(),
            'logName' => 'Hotel_OffersEnhancedPricing'
        );

        $response = new HotelApiResponse;

        $results = $this->otaHotelAvail($params, 'Hotel_EnhancedPricing');
        $response->setSession($results['session']);
        $response->setXmlResponse($results['lastResponse']);

        if (isset($results['error'])) {
            $response->setError($results['error']);
        } else {
            $roomStayCandidate              = $params['segments'];
            $roomStayCandidate['childCode'] = $otaChildCOde;

            $hotel = $this->amadeusNormalizer->processHotelOffersEnhancedPricingResponse($response->getXmlResponse(), $roomStayCandidate, $otaPaymentTypes, $hotelBC->getBookableInfoSelected());

            if (!empty($hotel->getRoomOffers())) {
                $response->setSuccess(true);
                $response->setData(array('hotel' => $hotel));
            }

            if (!$response->isSuccess() && isset($results['warning'])) {
                $response->setError($results['warning']);
                $this->securitySignOut(array('stateful' => true, 'session' => $results['session'], 'hotelId' => $params['hotelId'], 'userId' => $params['userId']));
            }
        }
        return $response;
    }

    /**
     * This method checks if the offers is still available
     *
     * @param String $infoSource
     * @param Array $segments
     * @param Integer $hotelId
     * @param Integer $userId
     * @param Array $roomsReserved      Array of HotelRoomReservation
     * @return HotelApiResponse
     */
    public function checkHotelOffersAvailability($infoSource, $segments, $hotelId, $userId, $roomsReserved)
    {
        $response = new HotelApiResponse();

        $params = array(
            'segments' => $segments,
            'infoSource' => $infoSource,
            'availableOnlyIndicator' => 'true',
            'availRatesOnly' => 'true',
            'exactMatchOnly' => 'false',
            'echoToken' => 'MultiSingle',
            'summaryOnly' => 'true',
            'rateRangeOnly' => 'true',
            'rateDetailsInd' => 'true',
            'searchCacheLevel' => 'Live',
            'bestOnlyIndicator' => 'false',
            'logName' => 'HotelMultiSingleAvailabilityLive',
            'stateful' => true,
            'hotelId' => $hotelId,
            'userId' => $userId,
            'logName' => 'Hotel_Availability'
        );

        // check hotel availability
        $hotelAvailability = $this->otaHotelAvail($params, 'Hotel_MultiSingleAvailability');
        if (isset($hotelAvailability['error'])) {
            $response->setError($hotelAvailability['error']);
        } else {
            // retrieve full rate details of each reserved room
            foreach ($roomsReserved as $key => $room) {
                $roomInfo = json_decode($room->getRoomInfo(), true);
                $segment  = &$segments[($key      += 1)];

                $segment['roomStayCandidate']['roomTypeCode'] = $roomInfo['roomTypeCode'];
                $segment['roomStayCandidate']['bookingCode']  = $roomInfo['bookingCode'];

                $segment['ratePlanCandidate'] = array(
                    'ratePlanCode' => $roomInfo['ratePlanCode'],
                    'mealPlanCodes' => $roomInfo['mealPlanCodes']
                );
            }

            $params = array(
                'session' => $hotelAvailability['session'],
                'segments' => $segments,
                'echoToken' => 'Pricing',
                'summaryOnly' => 'false',
                'rateRangeOnly' => 'false',
                'availRatesOnly' => 'true',
                'stateful' => true,
                'hotelId' => $hotelId,
                'userId' => $userId,
                'logName' => 'Hotel_OfferAvailability'
            );

            // check hotel offers availability
            $pricing           = $this->otaHotelAvail($params, 'Hotel_EnhancedPricing');
            $params['session'] = $pricing['session'];

            if (isset($pricing['error'])) {
                $response->setError($pricing['error']);
            } else {
                $response->setSuccess(true);
                $response->setSession($pricing['session']);
                // validate if offer availability and validity
                $xpath = $this->amadeusNormalizer->getXPath($pricing['lastResponse']);
                foreach ($roomsReserved as $room) {
                    $roomInfo        = json_decode($room->getRoomInfo(), true);
                    $roomOfferDetail = $room->getRoomOfferDetail();

                    $roomStay = $this->amadeusNormalizer->getRoomStayByBookingCode($xpath, $roomInfo['bookingCode']);
                    if ($roomStay->length > 0) {
                        $roomStay    = $roomStay->item(0);
                        $roomStayXml = $roomStay->ownerDocument->saveXML($roomStay);

                        if ($roomStayXml !== $roomOfferDetail) {
                            $response->setSuccess(false);
                            $response->setError(array('message' => "offer not valid since price/conditions changed"));
                        }
                    } else {
                        $response->setSuccess(false);
                        $response->setError(array('message' => "Offer not available anymore"));
                    }
                }
            }
        }

        if (!$response->isSuccess()) {
            // we only signout when we encounter issues, since session is used in our controller
            // to continue booking process... signout appropriately on the areas that calls this function
            $this->securitySignOut($params);
        }

        return $response;
    }

    /**
     * This method get data needed for performing API reservation from given params
     *
     * @param Array $requestData
     * @param HotelBookingCriteria $hotelBC
     * @return HotelApiResponse
     */
    public function getDataForReservationRequest($requestData, HotelBookingCriteria $hotelBC)
    {
        return array(
            'session' => $hotelBC->getSession(),
            'groupSell' => $requestData['groupSell'],
            'gds' => $requestData['gds'],
            'prepaidIndicator' => $requestData['prepaidIndicator'],
            'refererURL' => $hotelBC->getRefererURL(),
            'vendorCode' => (isset($requestData['ccType'])) ? $requestData['ccType'] : '',
            'ccHolderName' => (isset($requestData['ccCardHolder'])) ? $requestData['ccCardHolder'] : '',
            'cardNumber' => (isset($requestData['ccNumber'])) ? $requestData['ccNumber'] : '',
            'expiryDate' => (isset($requestData['ccExpiryMonth']) && isset($requestData['ccExpiryYear'])) ? implode(array(str_pad($requestData['ccExpiryMonth'], 2, '0', STR_PAD_LEFT), $requestData['ccExpiryYear']))
                : '',
            'securityId' => (isset($requestData['ccCVC'])) ? $requestData['ccCVC'] : '',
            'remarks' => $hotelBC->getRemarks(),
            'reservationMode' => $hotelBC->getReservationMode(),
            'chainCode' => $hotelBC->getChainCode(),
            'hotelCityCode' => $hotelBC->getHotelCityCode(),
            'hotelCode' => $hotelBC->getHotelCode(),
            'cancelable' => $hotelBC->isCancelable(),
            'infoSource' => $requestData['infoSource'],
            'availRequestSegment' => json_decode($hotelBC->getAvailRequestSegment(), true)
        );
    }

    /**
     * This method creates a PNR.
     *
     * @param Array $params         The parameters sent to the API.
     * @param Boolean $commit       Set to true if we need to commit the PNR
     * @return HotelApiResponse     The API results.
     */
    public function createBookingRecord($params, $commit)
    {
        $response = new HotelApiResponse();

        $params['logName'] = 'Hotel_CreateBookingRecord';

        $pnr     = $this->pnrAddMultiElements($params, true, $commit);
        $session = $pnr['session'];

        if (isset($pnr['error'])) {
            $response->setError($pnr['error']);
        } else {
            $domDoc   = new \DOMDocument('1.0', 'UTF-8');
            $domDoc->loadXML($pnr['lastResponse']);
            $pnrReply = $domDoc->getElementsByTagName('PNR_Reply');
            if ($pnrReply->length) {
                $response->setSuccess(true);
            } elseif (isset($pnr['warning'])) {
                $response->setError($pnr['warning']);
                $this->securitySignOut(array(
                    'stateful' => true,
                    'session' => $session,
                    'hotelId' => $params['hotelId'],
                    'userId' => $params['userId']
                ));
            }
        }
        $response->setSession($session);
        return $response;
    }

    /**
     * This method updates a PNR.
     *
     * @param Array $params         The parameters sent to the API.
     * @param Boolean $commit       Set to true if we need to commit the PNR
     * @return HotelApiResponse     The API results.
     */
    public function updateBookingRecord($params, $commit)
    {
        $response = new HotelApiResponse();

        $params['logName'] = 'Hotel_UpdateBookingRecord';

        $pnrCommit = $this->pnrAddMultiElements($params, false, $commit);
        $session   = $pnrCommit['session'];

        if (isset($pnrCommit['error'])) {
            $response->setError($pnrCommit['error']);
        } else {
            $response->setSuccess(true);
        }
        $response->setSession($session);
        return $response;
    }

    /**
     * This method confirms reservation.
     *
     * @param Array $params         The parameters sent to the API.
     * @param Boolean $updatePNR    Set to true if we need to update the PNR
     * @return HotelApiResponse     The API results.
     */
    public function confirmReservation($params, $updatePNR)
    {
        $response = new HotelApiResponse();

        $params['logName'] = 'Hotel_BookingRequest';

        $sell    = $this->hotelSell($params);
        $session = $sell['session'];

        $results = array();

        if (isset($sell['error'])) {
            $response->setError($sell['error']);
        } else {
            $sellResponse = $this->amadeusNormalizer->getSellResponse($sell['lastResponse'], $updatePNR);
            if ($sellResponse['success']) {
                if (!$updatePNR) {
                    $response->setSuccess(true);
                    $results['controlNumber']  = $sellResponse['controlNumber'];
                    $results['reservationKey'] = $sellResponse['reservationKey'];
                } else {
                    $pnrCommitParams = array(
                        'session' => $session,
                        'stateful' => true,
                        'hotelId' => $params['hotelId'],
                        'userId' => $params['userId']
                    );

                    $pnrCommit = $this->pnrAddMultiElements($pnrCommitParams, false, true);
                    $session   = $pnrCommit['session'];

                    if (isset($pnrCommit['error'])) {
                        // Warnings returned at EOT are considered as errors (EC code). However, they are not blocking, and agent can recommit and PNR would be successfully committed.
                        $pnrCommit = $this->pnrAddMultiElements($pnrCommitParams, false, true);
                        if (isset($pnrCommit['error'])) {
                            $response->setError($pnrCommit['error']);
                        }
                    }

                    if (empty($response->getError())) {
                        $response->setSuccess(true);
                        $this->amadeusNormalizer->getPnrControlNumber($pnrCommit, $results['controlNumber'], $results['reservationKey']);
                    }
                }
                if ($response->isSuccess()) {
                    $params['controlNumber'] = $results['controlNumber'];
                    $response->setData($results);
                }
                $this->logger->addBookingRequestLog('Hotels', 'reservation', $params['userId'], $params, $sell);
            } elseif (isset($sell['warning'])) {
                $response->setError($sell['warning']);
            }
            $this->securitySignOut(array(
                'stateful' => true,
                'session' => $session,
                'hotelId' => $params['hotelId'],
                'userId' => $params['userId']
            ));
        }

        $response->setSession($session);
        return $response;
    }

    /**
     * This method retrieves booking PNR data from the Amadeus.
     *
     * @param String $controlNumber             The reservation control number
     * @param Array $savedRooms                 The room info from the db.
     * @param Array $otaPaymentTypes
     * @param Boolean $getCompleteInfoFromAPI   Flag to call get complete room info from API (Optional; Default: false)
     * @return HotelApiResponse                 The API results.
     */
    public function getBookingRecord($controlNumber, $savedRooms, $otaPaymentTypes, $getCompleteInfoFromAPI = false)
    {
        $response = new HotelApiResponse();

        $params  = array(
            'controlNumber' => $controlNumber,
            'stateful' => $getCompleteInfoFromAPI,
            'logName' => 'Hotel_BookingRecord'
        );
        $results = $this->pnrRetrieve($params);

        if (isset($results['error'])) {
            $response->setError($results['error']);
        } else {
            $params['session'] = $results['session'];

            $hotelRooms = $this->amadeusNormalizer->processBookingRecordResponse($results['lastResponse'], $savedRooms, $otaPaymentTypes, $getCompleteInfoFromAPI, $params);

            $response->setSuccess(true);
            $response->setData(array('hotelRooms' => $hotelRooms));
        }
        // end session
        if (!empty($params['session']) && $params['stateful']) {
            $this->securitySignOut($params);
        }

        return $response;
    }

    /**
     * This method retrieves booking PNR data from the Amadeus.
     *
     * @param String $requestingPage
     * @param Integer $counter
     * @param String $roomStayXml               The RoomStay XML
     * @param Array $otaPaymentTypes
     * @return HotelRoom
     */
    public function getRoomBooking($requestingPage, $counter, $roomStayXml, $otaPaymentTypes)
    {
        return $this->amadeusNormalizer->processRoomBooking($requestingPage, $counter, $roomStayXml, $otaPaymentTypes);
    }

    /**
     * This method retrieves hotel information from the given xmlResponse
     *
     * @param String $xmlResponse       The XML response
     * @param Bool $returnContactInfo   Flag to get contact info
     * @return Array                    The hotel information
     */
    public function getHotelDescriptiveInfo($params)
    {
        $response = new HotelApiResponse();

        $params['logName'] = (count($params['hotelCodes']) > 1) ? 'HotelDescriptiveInfo' : 'HotelDescriptiveInfoSingleProperty';

        $results = $this->hotelDescriptiveInfo($params);

        if (isset($results['error'])) {
            $response->setError($results['error']);
        } else {
            $hotelContents = $this->amadeusNormalizer->processHotelDescriptiveInfoResponse($results['lastResponse'], $params['returnContactInfo']);

            $response->setSuccess(true);
            $response->setData($hotelContents);
        }
        return $response;
    }

    /**
     * This method retrieves hotel information from the given xmlResponse
     *
     * @param String $xmlResponse   The XML response
     * @return Array                The hotel information
     */
    public function getHotelStayInformation($xmlResponse, $hotelCode)
    {
        return $this->amadeusNormalizer->processHotelStayInformation($xmlResponse, $hotelCode);
    }

    /**
     * This method cancels a booking record.
     *
     * @param Array $params         The cancellation reference.
     * @return HotelApiResponse     The API results.
     */
    public function cancelBooking($params)
    {
        $response = new HotelApiResponse();

        $params['logName'] = 'Hotel_CancelBooking';

        $pnrCancel = $this->pnrCancel($params);
        $session   = $pnrCancel['session'];

        if (isset($pnrCancel['error'])) {
            $response->setError($pnrCancel['error']);
        } elseif (isset($pnrCancel['lastResponse']) && !empty($pnrCancel['lastResponse'])) {
            $response->setSuccess(true);
        } else {
            $response->setError(array('message' => 'No response'));
        }
        $response->setSession($session);
        return $response;
    }

    /**
     * This method cancels a room in a reservation.
     *
     * @param Array $params The cancellation reference.
     * @return Arrray   The cancellation results, status, etc.
     */
    public function cancelRoom($params)
    {
        $toreturn = new HotelCancellation();
        //$failed   = true;

        if ($params['segmentIdentifier'] && $params['segmentNumber']) {
            $response = $this->pnrCancel($params);
            $toreturn = $this->amadeusNormalizer->processCancellationResponse($response, $params['reservationKey']);
        } else {
            $toreturn->getStatus()->setStatus(false);
        }

        if ($toreturn->hasError()) {
            if (empty($toreturn->getStatus()->getError())) {
                $toreturn->getStatus()->setError(array('code' => '', 'message' => 'failed'));
            }

            $toreturn->getStatus()->setErrorLogFile(((isset($response['errorLogFile'])) ? $response['errorLogFile'] : ''));
        }

        return $toreturn;
    }

    //*****************************************************************************************
    // Helper Functions
    /**
     *  This method makes Amadeus API call.
     *
     * @param String $requestAPI    The Amadeus API to call
     * @param type $functionData    The \SoapVar XML request
     * @param type $params          The parameters (e.g. session, stateful, other data needed on creation of soap headers).
     * @param type $endSession      Tells Amadeus API during call that this is the last session (Optional; default=false).
     * @param type $log             This allows logging if set to TRUE (Optional; default=true)
     * @return Array                The API results.
     */
    private function sendClientRequest($requestAPI, $functionData, $params, $endSession = false, $log = true)
    {
        $results = array();

        $wsdl         = $this->getWsdlFor($requestAPI);
        $this->CLIENT = new \SoapClient($wsdl, $this->OPT);

        try {
            $responseHeaders = array();
            $headers         = $this->createSoapHeaders($wsdl, $requestAPI, $params, $endSession);
            $this->CLIENT->__soapCall($requestAPI, array($functionData), $this->OPT, $headers, $responseHeaders);
            $this->amadeusNormalizer->evaluateForErrors($this->CLIENT->__getLastResponse(), $results);
        } catch (\SoapFault $sf) {
            $results['error'] = array('code' => 'soapfault', 'message' => $sf->getMessage());
            if (stripos($results['error']['message'], 'session') !== false && stripos($results['error']['message'], 'inactive') !== false) {
                $results['error'] = array('code' => 'OTA_SCM', 'message' => '');
            }
        } catch (\Exception $e) {
            $results['error'] = array('code' => 'exception', 'message' => $e->__toString());
        } catch (ContextErrorException $e) {
            $results['error'] = array('code' => 'exception', 'message' => $e->__toString());
        }

        // extract session info
        // SESSION_DATA is only for test purposes, since session revolves in this class only, for actual ui usage, we will return the session to controller via $results
        $results['session'] = $params['session']  = $this->SESSION_DATA = array();
        if (($params['stateful'] === true) && isset($responseHeaders['Session'])) {
            $session = json_decode(json_encode($responseHeaders['Session']), true);
            if ($session['TransactionStatusCode'] != 'End') {
                $sessionData = array(
                    'sessionId' => $session['SessionId'],
                    'sequenceNumber' => $session['SequenceNumber'],
                    'securityToken' => $session['SecurityToken']
                );

                $results['session'] = $params['session']  = $this->SESSION_DATA = $sessionData;
            }
        }

        $results['lastRequest']  = $this->CLIENT->__getLastRequest();
        $results['lastResponse'] = $this->CLIENT->__getLastResponse();

        if ($log) {
            $title = (isset($params['logName'])) ? $params['logName'] : str_replace('_', '', $requestAPI);
            if (isset($results['session']['sessionId'])) {
                $logName                     = $results['session']['sessionId'];
                $params['compileSessionLog'] = 1;
            }

            if (!isset($logName)) {
                $logName = $title;
            }

            $params['timeStart']     = date('YmdHis', time());
            $results['errorLogFile'] = $this->logger->getLogFile('amadeus', $logName, $params);

            $this->logger->info($results['errorLogFile'], $title, '-------------------------------------');

            $this->logger->info($results['errorLogFile'], 'request', $this->filterLogContent($results['lastRequest']));

            $this->logger->info($results['errorLogFile'], 'response', $results['lastResponse']);
        }


        if (isset($results['error'])) {
            if ($log) {
                $this->logger->error($results['errorLogFile'], $results['error']);
            }

            if ($params['stateful'] && !empty($params['session'])) {
                $params['stateful'] = false;
                $this->securitySignOut($params);
            }
        }

        return $results;
    }

    /**
     * This method will clear a content before it is added as a log.
     * @param String $content
     * @return String   The filtered content
     */
    private function filterLogContent($content)
    {
        $filters = array(
            array(
                'pattern' => "/(<cardNumber>)(\d+)(<\/cardNumber>)/mi",
                'replacement' => '${1}****************${3}'
            ),
            array(
                'pattern' => "/(<securityId>)(\d+)(<\/securityId>)/mi",
                'replacement' => '${1}***${3}'
            )
        );

        foreach ($filters as $filter) {
            $content = preg_replace($filter['pattern'], $filter['replacement'], $content);
        }

        return $content;
    }

    /**
     * This method creates the needed Amadeus soap headers
     *
     * @param String $wsdl  The WSDL path.
     * @param String $functionName  The function name or operation.
     * @param Array $params The parameters (session, stateful, etc).
     * @param Boolean $endSession   Tells Amadeus API during call that this is the last session.
     * @return \SoapHeader[]
     */
    private function createSoapHeaders($wsdl, $functionName, $params, $endSession)
    {
        $headers = array();

        $authParams = array(
            'wsap' => '1ASIWTOTOUT',
            'userId' => 'WSOUTTOT', //Also known as 'Originator' for Soap Header 1 & 2 WSDL's
            'passwordLength' => null,
            'passwordData' => 'e0ZZwYqt', // 'AMAWEB8884',
            'officeId' => 'DXBAD38TF', //The Amadeus Office Id you want to sign in to - must be open on your WSAP.
            'dutyCode' => 'SU',
            'originatorTypeCode' => 'U',
            'organizationId' => null,
            'nonceBase' => $this->utils->randomString(22)
        );

        $headers[] = new \SoapHeader('http://www.w3.org/2005/08/addressing', 'MessageID', $this->utils->GUID());

        $action    = $this->getNodeFromXML($wsdl, $functionName, 'string(//wsdl:operation[./@name="%s"]/soap:operation/@soapAction)');
        $headers[] = new \SoapHeader('http://www.w3.org/2005/08/addressing', 'Action', $action);

        $endpoint  = $this->getNodeFromXML($wsdl, $functionName, 'string(/wsdl:definitions/wsdl:service/wsdl:port/soap:address/@location)');
        $headers[] = new \SoapHeader('http://www.w3.org/2005/08/addressing', 'To', $endpoint);

        if (!isset($params['session']) || empty($params['session'])) {
            //Authentication header
            $t              = microtime(true);
            $micro          = sprintf("%03d", ($t - floor($t)) * 1000);
            $creation       = new \DateTime('now', new \DateTimeZone('UTC'));
            $creationString = $creation->format("Y-m-d\TH:i:s:").$micro.'Z';

            $functionNonce = substr(sha1($authParams['nonceBase'].$creationString, true), 0, 16);
            $encodedNonce  = base64_encode($functionNonce);
            $digest        = base64_encode(sha1($functionNonce.$creationString.sha1($authParams['passwordData'], true), true));

            $securityHeaderXml = $this->securityHeaderXML($authParams['userId'], $encodedNonce, $digest, $creationString);
            $headers[]         = new \SoapHeader("{$this->DIR}oasis-200401-wss-wssecurity-secext-1.0.xsd", 'Security', new \SoapVar($securityHeaderXml, XSD_ANYXML));

            if ($params['stateful'] === true) {
                //Amadeus Session Header: No session exist but stateful: start session!
                $sessionHeaderXml = $this->sessionHeaderXML(null, 'Start');
                $headers[]        = new \SoapHeader('http://xml.amadeus.com/2010/06/Session_v3', 'Session', new \SoapVar($sessionHeaderXml, XSD_ANYXML));
            }

            //Amadeus security header
            $amadeusSecurityHeaderXml = $this->securityHostedHeaderXML($authParams['officeId'], $authParams['originatorTypeCode'], 1, $authParams['dutyCode']);
            $headers[]                = new \SoapHeader('http://xml.amadeus.com/2010/06/Security_v1', 'AMA_SecurityHostedUser', new \SoapVar($amadeusSecurityHeaderXml, XSD_ANYXML));
        } else {
            //Provide session header to continue or terminate session
            $statusCode = ($endSession) ? 'End' : 'InSeries';
            $params['session']['sequenceNumber'] ++;

            $sessionHeaderXml = $this->sessionHeaderXML($params['session'], $statusCode);
            $headers[]        = new \SoapHeader('http://xml.amadeus.com/2010/06/Session_v3', 'Session', new \SoapVar($sessionHeaderXml, XSD_ANYXML));
        }

        return $headers;
    }

    /**
     * This method returns the default API language.
     *
     * @return String   The default API language
     */
    public function getDefaultAPILanguage()
    {
        return $this->config->default_api_language;
    }

    /**
     * This method returns the default API currency.
     *
     * @return String   The default API currency
     */
    public function getDefaultAPICurrency()
    {
        return $this->config->default_api_currency;
    }

    /**
     * This method retrieves the correct WSDL path for a certain function name or operation.
     *
     * @param type $functionName
     * @return String   The WSDL path for the specified function name or operation.
     */
    private function getWsdlFor($functionName)
    {
        $wsdl = '';
        $sep  = DIRECTORY_SEPARATOR;

        switch (strtolower($functionName)) {
            case 'hotel_calendarview':
            case 'hotel_enhancedpricing':
            case 'hotel_enhancedsingleavail':
            case 'hotel_multiavailability':
            case 'hotel_multisingleavailability':
                $wsdl = "{$this->DIR}{$this->WSDL_ENVIRONMENT}{$sep}{$this->WSDL_ENVIRONMENT}_HotelAvailability_2.0_4.0.wsdl";
                break;
            case 'hotel_contentnotifreport':
            case 'hotel_descriptiveinfo':
                $wsdl = "{$this->DIR}{$this->WSDL_ENVIRONMENT}{$sep}{$this->WSDL_ENVIRONMENT}_HotelContent_1.0_4.0.wsdl";
                break;
            case 'command_cryptic':
            case 'hotel_completereservationdetails':
            case 'hotel_sell':
            case 'pnr_addmultielements':
            case 'pnr_cancel':
            case 'pnr_namechange':
            case 'pnr_retrieve':
            case 'pnr_retrieve2':
            case 'security_authenticate':
            case 'security_signout':
                $wsdl = "{$this->DIR}{$this->WSDL_ENVIRONMENT}{$sep}{$this->WSDL_ENVIRONMENT}.wsdl";
                break;
        }

        return $wsdl;
    }

    /**
     * This method retrieves an attribute/element value.
     *
     * @param String $wsdl  The WSDL path.
     * @param String $functionName  The function name or operation.
     * @param type $path    The XPath used to retrieve a specific attribute/element value.
     * @return String   The XPath value.
     */
    private function getNodeFromXML($wsdl, $functionName, $path)
    {
        $function = strtolower(trim($functionName));

        if (!isset($this->DOM_XPATH[$function])) {
            $wsdlContent = file_get_contents($wsdl);

            $domDoc = new \DOMDocument('1.0', 'UTF-8');
            $domDoc->loadXML($wsdlContent);

            $domXpath = new \DOMXPath($domDoc);
            $domXpath->registerNamespace('wsdl', "http://schemas.xmlsoap.org/wsdl/");
            $domXpath->registerNamespace('soap', "http://schemas.xmlsoap.org/wsdl/soap/");

            $this->DOM_XPATH[$function] = $domXpath;
        }

        $domXpath = $this->DOM_XPATH[$function];
        $xpath    = sprintf($path, $functionName);
        return $domXpath->evaluate($xpath);
    }
}
