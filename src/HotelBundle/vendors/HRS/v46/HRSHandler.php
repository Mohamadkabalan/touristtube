<?php

namespace HotelBundle\vendors\HRS\v46;

use SoapClient;
use TTBundle\Utils\Utils;
use Symfony\Component\DependencyInjection\ContainerInterface;
use HotelBundle\vendors\HRS\Config as HRSConfig;
use HotelBundle\vendors\HRS\v46\HRSNormalizer;
use HotelBundle\Model\Hotel;
use HotelBundle\Model\HotelBooking;
use HotelBundle\Model\HotelApiResponse;
use HotelBundle\Model\HotelBookingCriteria;
use HotelBundle\Model\HotelAvailability;
use HotelBundle\Model\HotelSC;

if (!defined('RESPONSE_SUCCESS')) {
    define('RESPONSE_SUCCESS', false);
}
if (!defined('RESPONSE_ERROR')) {
    define('RESPONSE_ERROR', true);
}

class HRSHandler
{

    /**
     * The __construct when we make a new instance of HRSHandler class.
     *
     * @param Utils              $utils
     * @param ContainerInterface $container
     */
    public function __construct(Utils $utils, ContainerInterface $container)
    {
        // initialize parameters
        $this->config        = new HRSConfig($container);
        $this->HRSNormalizer = new HRSNormalizer($utils, $container);

        $this->utils     = $utils;
        $this->container = $container;

        $this->logger = $this->container->get('HotelLogger');

        $options = array(
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS | SOAP_ENC_ARRAY,
            'soap_version' => 'SOAP_1_1',
            'trace' => true,
            'exceptions' => true,
            'cache_wsdl' => WSDL_CACHE_BOTH,
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP
        );

        // Soap Client
        $this->URL    = $this->config->endpoint_url;
        $this->CLIENT = new SoapClient("./assets/hotels/xml/hrs/HRSService-v46.xml", $options);
        $this->CLIENT->__setLocation($this->URL);
    }

    //*****************************************************************************************
    // Direct API Functions
    /**
     * hotelDetailAvail call
     *
     * @param  HotelSC $hotelSC
     * @param int $userId
     *
     * @return
     */
    public function hotelDetailAvail(HotelSC $hotelSC, $userId = 0)
    {
        $params = array(
            'hotelKey' => $hotelSC->getHotelKey(),
            'fromDate' => $hotelSC->getFromDate(),
            'toDate' => $hotelSC->getToDate(),
            'roomCriteria' => $this->getRoomCriteria($hotelSC),
            'genericCriteria' => array(
                'returnDistances' => 'true',
                'returnRoomDescriptions' => 'split',
                'strictRoomTypeHandling' => 'false',
                'returnMainMedia' => 'false'
            ),
            'userId' => $userId,
            'hotelId' => $hotelSC->getHotelId()
        );

        // Get all params and assign it to it's own variable
        extract($params);

        //Soap Data
        $data = array(
            'hotelKeys' => $hotelKey,
            'availCriterion' => array(
                'from' => $fromDate,
                'to' => $toDate,
                'includeBreakfastPriceToDetermineCheapestOffer' => 1,
                'roomCriteria' => $roomCriteria
            ),
            'orderCriteria' => array(
                'orderKey' => 'price',
                'orderDirection' => 'ascending'
            )
        );

        return $this->sendRequest('hotelDetailAvail', $data, $params);
    }

    /**
     * hotelDetailSearch call
     *
     * @param HotelSC $hotelSC
     * @param $userId
     *
     * @return
     */
    private function hotelDetailSearch(HotelSC $hotelSC, $userId = 0)
    {
        $params = array(
            'hotelKey' => $hotelSC->getHotelKey(),
            'fromDate' => $hotelSC->getFromDate(),
            'toDate' => $hotelSC->getToDate(),
            'roomCriteria' => $this->getRoomCriteria($hotelSC),
            'genericCriteria' => array(
                'returnDistances' => 'true',
                'returnRoomDescriptions' => 'split',
                'strictRoomTypeHandling' => 'false',
                'returnMainMedia' => 'false'
            ),
            'userId' => $userId,
            'hotelId' => $hotelSC->getHotelId()
        );

        // Get all params and assign it to it's own variable
        extract($params);

        //Soap Data
        $data = array('hotelKeys' => $hotelKey);

        return $this->sendRequest('hotelDetailSearch', $data, $params);
    }

    /**
     * hotelReservation call
     *
     * @param $params
     *
     * @return
     */
    private function hotelReservation($params)
    {
        extract($params);

        $paymentMode = 'direct';

        //Soap Data
        $data = array(
            'hotelKey' => $hotelKey,
            'paymentMode' => $paymentMode,
            'reservationMode' => $reservationMode,
            'reservationCriterion' => $reservationCriterion,
            'reservationWish' => $reservationWish,
            'orderer' => $orderer,
            'creditCard' => $creditCard
        );

        if (isset($transactionId)) {
            $data['transactionId'] = $transactionId;
        }

        return $this->sendRequest('hotelReservation', $data, $params);
    }

    /**
     * This method sends the hotelReservationInformatiAPI request
     *
     * @param $params
     *
     * @return
     */
    private function hotelReservationInformation($params)
    {
        $totalWaitingTime      = 0;
        $apiCallMaxAttempts    = $this->container->getParameter('MAX_API_CALL_ATTEMPTS') + 1;
        $apiCallSecondsToRetry = $this->container->getParameter('PAUSE_BETWEEN_RETRIES_US') / 1000000;

        //Soap Data
        $data = array(
            'reservationProcessKey' => $params['reservationProcessKey'],
            'reservationProcessPassword' => $params['reservationProcessPassword']
        );

        for ($attemptNumber = 1; $attemptNumber <= $apiCallMaxAttempts; $attemptNumber++) {
            $timeStart        = microtime(true);
            $results          = $this->sendRequest('hotelReservationInformation', $data, $params);
            $requestTime      = microtime(true) - $timeStart;
            $totalWaitingTime += $requestTime;

            if ($attemptNumber != 1) {
                $totalWaitingTime -= $apiCallSecondsToRetry;
            }

            $logParams                                   = $params;
            $logParams['apiCallAttemptNumber']           = $attemptNumber;
            $logParams['apiCallMaxAttempts']             = $apiCallMaxAttempts;
            $logParams['apiCallSecondsToRetry']          = $apiCallSecondsToRetry;
            $logParams['apiCallTimeSeconds']             = $requestTime;
            $logParams['apiCallTotalWaitingTimeSeconds'] = $totalWaitingTime;
            $logParams['status']                         = (!isset($results->error)) ? 'success' : 'failure';

            $this->logger->addHotelActivityLog('HRS', 'hotelReservationInformation', $logParams['userId'], $logParams);

            if (!isset($results->error)) {
                break;
            }

            if ($attemptNumber != $apiCallMaxAttempts) {
                usleep($this->container->getParameter('PAUSE_BETWEEN_RETRIES_US'));
            }
        }

        return $results;
    }

    /**
     * hotelReservationModification
     *
     * @param $params
     *
     * @return
     */
    private function hotelReservationModification($params)
    {
        // Get all params and assign it to it's own variable
        extract($params);

        $paymentMode = 'direct';

        //Soap Data
        $data = array(
            'hotelKey' => $hotelKey,
            'paymentMode' => $paymentMode,
            'reservationMode' => $reservationMode,
            'reservationCriterion' => $reservationCriterion,
            'reservationWish' => $reservationWish,
            'orderer' => $orderer,
            'reservationProcessKey' => $reservationProcessKey,
            'reservationProcessPassword' => $reservationProcessPassword
        );

        if (isset($creditCard) && !empty($creditCard)) {
            $data['creditCard'] = $creditCard;
        }

        if (isset($transactionId)) {
            $data['transactionId'] = $transactionId;
        }

        return $this->sendRequest('hotelReservationModification', $data, $params);
    }

    /**
     * hotelReservationCancellation
     *
     * @param $params
     *
     * @return
     */
    public function hotelReservationCancellation($params)
    {
        //Soap Data
        $data = array(
            'reservationProcessKey' => $params['reservationProcessKey'],
            'reservationProcessPassword' => $params['reservationProcessPassword']
        );

        return $this->sendRequest('hotelReservationCancellation', $data, $params);
    }

    //*****************************************************************************************
    // UI Integrated Functions
    /**
     * hotelAvail
     *
     * @param HotelSC $hotelSC
     * @param boolean $curl
     * @param $userId
     *
     * @return
     */
    public function hotelAvail(HotelSC $hotelSC, $curl = false, $userId = 0)
    {
        // initialize hotelAvail request params
        $specialEntityTypes = array(
            $this->container->getParameter('SOCIAL_ENTITY_LANDMARK'),
            $this->container->getParameter('SOCIAL_ENTITY_AIRPORT'),
            $this->container->getParameter('SOCIAL_ENTITY_DOWNTOWN')
        );

        $perimeter = (in_array($hotelSC->getEntityType(), $specialEntityTypes)) ? 10000 : 0;
        $hotelSC->setPerimeter($perimeter);

        $params = array(
            'fromDate' => $hotelSC->getFromDate(),
            'toDate' => $hotelSC->getToDate(),
            'locationId' => $hotelSC->getLocationId(),
            'longitude' => $hotelSC->getLongitude(),
            'latitude' => $hotelSC->getLatitude(),
            'perimeter' => $hotelSC->getPerimeter(),
            'roomCriteria' => $this->getRoomCriteria($hotelSC),
            'genericCriteria' => array(
                'returnHotels' => 'false',
                'returnFreeServices' => 'false',
                'returnMainMedia' => 'false',
                'strictRoomTypeHandling' => 'false'
            ),
            'userId' => $userId
        );

        if ($hotelSC->getEntityType() == $this->container->getParameter('SOCIAL_ENTITY_REGION')) {
            $regionName             = explode(',', $hotelSC->getCity()->getName());
            $params['locationName'] = $regionName[0];
            $params['country']      = $hotelSC->getIso3CountryCode();
        }

        // Get all params and assign it to it's own variable
        extract($params);

        //Soap Data
        $data = array(
            'availCriterion' => array(
                'from' => $fromDate,
                'to' => $toDate,
                'includeBreakfastPriceToDetermineCheapestOffer' => 1,
                'roomCriteria' => $roomCriteria
            )
        );

        // make sure to use location id when making a call to HRS hotelAvail api
        if (!empty($locationId)) {
            $data['searchCriterion']['locationCriterion']['locationId'] = $locationId;
        } else {
            if (!empty($longitude) && !empty($latitude)) {
                $data['searchCriterion']['locationCriterion']['geoPosition'] = array(
                    'longitude' => $longitude,
                    'latitude' => $latitude
                );
            } elseif (isset($locationName) && isset($country)) {
                $data['searchCriterion']['locationCriterion']['locationName']                     = $locationName;
                $data['searchCriterion']['locationCriterion']['locationLanguage']['iso3Language'] = 'ENG';
                $data['searchCriterion']['locationCriterion']['iso3Country']                      = $country;
            }
        }

        if (!empty($perimeter)) {
            $data['searchCriterion']['locationCriterion']['perimeter'] = $perimeter;
        }

        // send hotelAvail request
        $timeLog = array('api_call_time_start' => date('H:i:s', time()));

        $results = array();
        if ($curl) {
            $results = $this->sendRequestCurl('hotelAvail', $data, $params);
        } else {
            $results = $this->sendRequest('hotelAvail', $data, $params);
        }

        $timeLog['api_call_time_end'] = date('H:i:s', time());

        // normalize results
        if (isset($results['error'])) {
            $toReturn = new HotelAvailability();
            $toReturn->getStatus()->setError($results['error']);
        } else {
            $timeLog['parse_response_time_start'] = date('H:i:s', time());

            $toReturn = $this->HRSNormalizer->parseAvailabilityResponse($results);

            $timeLog['parse_response_time_end'] = date('H:i:s', time());
        }

        $this->logger->addHotelActivityLog('HRS', 'API Hotel Listing Search Time Info', $userId, $timeLog);

        return $toReturn;
    }

    /**
     * This method returns hotel information from the API
     *
     * @param  $hotelSC
     * @param  $userId
     *
     * @return
     */
    public function getHotelAPIInformation(HotelSC $hotelSC, $userId = 0)
    {
        $results = $this->hotelDetailSearch($hotelSC, $userId);

        if (isset($results->detailSearchHotels)) {
            $hotel = $this->HRSNormalizer->processHotelDetailInformation($results->detailSearchHotels[0]->hotelDetail);
        } else {
            $hotel = new Hotel();
        }

        return $hotel;
    }

    /**
     * This method returns the hotel offers
     *
     * @param  $hotelSC
     * @param  $logParams
     *
     * @return
     */
    public function getHotelOffers($hotelSC, $logParams)
    {
        $response = new HotelApiResponse;

        $totalNumOffers        = 0;
        $totalWaitingTime      = 0;
        $apiCallMaxAttempts    = $this->container->getParameter('MAX_API_CALL_ATTEMPTS') + 1;
        $apiCallSecondsToRetry = $this->container->getParameter('PAUSE_BETWEEN_RETRIES_US') / 1000000;

        for ($attemptNumber = 1; $attemptNumber <= $apiCallMaxAttempts; $attemptNumber++) {
            $timeStart        = microtime(true);
            $results          = $this->hotelDetailAvail($hotelSC, $logParams['userId']);
            $requestTime      = microtime(true) - $timeStart;
            $totalWaitingTime += $requestTime;

            if ($attemptNumber != 1) {
                $totalWaitingTime -= $apiCallSecondsToRetry;
            }

            if (isset($results->error)) {
                $response->setError($results->error->message);
            } elseif (isset($results->detailAvailHotelOffers) && !empty($results->detailAvailHotelOffers)) {
                // We only send one hotelkey, so expecting only one hotel offer, no need to loop
                $hotel = $this->HRSNormalizer->processHotelOffersResponse($results->detailAvailHotelOffers[0]);

                if (!empty($hotel->getRoomOffers())) {
                    $response->setSuccess(true);
                    $response->setData(array('hotel' => $hotel));

                    $totalNumOffers = $hotel->getTotalNumOffers();
                }
            }

            $logParams['apiCallAttemptNumber']           = $attemptNumber;
            $logParams['apiCallMaxAttempts']             = $apiCallMaxAttempts;
            $logParams['apiCallSecondsToRetry']          = $apiCallSecondsToRetry;
            $logParams['apiCallTimeSeconds']             = $requestTime;
            $logParams['apiCallTotalWaitingTimeSeconds'] = $totalWaitingTime;
            $logParams['totalNumOffers']                 = $totalNumOffers;
            $logParams['status']                         = ($response->isSuccess()) ? 'success' : 'failure';

            $this->logger->addHotelActivityLog('HRS', 'offers', $logParams['userId'], $logParams);

            if ($response->isSuccess()) {
                break;
            }

            if ($attemptNumber != $apiCallMaxAttempts) {
                usleep($this->container->getParameter('PAUSE_BETWEEN_RETRIES_US'));
            }
        }

        return $response;
    }

    /**
     * This method get data needed for performing API reservation from given parameters
     *
     * @param  Array                $criteria
     * @param  HotelBookingCriteria $hotelBC
     *
     * @return Array
     */
    public function getDataForReservationRequest($criteria, HotelBookingCriteria $hotelBC)
    {
        $params = array(
            'hotelKey' => $hotelBC->getHotelCode(),
            'hotelId' => $hotelBC->getHotelId(),
            'reservationMode' => $hotelBC->getReservationMode(),
            'reservationCriterion' => $hotelBC->getAvailRequestSegment(),
            'reservationWish' => $hotelBC->getRemarks(),
            'orderer' => array(
                'title' => ($hotelBC->getOrderer()->getTitle() != '') ? (($hotelBC->getOrderer()->getTitle()) ? 'Ms' : 'Mr') : '',
                'firstName' => $hotelBC->getOrderer()->getFirstName(),
                'lastName' => $hotelBC->getOrderer()->getLastName(),
                'email' => $hotelBC->getOrderer()->getEmail(),
                'iso3Country' => $hotelBC->getOrderer()->getCountry(),
            ),
            'creditCard' => array(
                'cardHolder' => $criteria['ccCardHolder'],
                'number' => $criteria['ccNumber'],
                'organisation' => $criteria['ccType'], //$ccType,
                'valid' => $this->getCCValidity($criteria['ccExpiryMonth'], $criteria['ccExpiryYear']), //$ccExpiryMonth, $ccExpiryYear),
                'securityCode' => $criteria['ccCVC'] //$ccCVC
            ),
            'genericCriteria' => array('returnRoomDescriptions' => 'split'),
            'transactionId' => $hotelBC->getTransactionId(),
            'userId' => $hotelBC->getUserId(),
        );

        if (!empty($hotelBC->getOrderer()->getPhone())) {
            $params['orderer']['phone'] = $hotelBC->getOrderer()->getDialingCode().' '.$hotelBC->getOrderer()->getPhone();
        }

        return $params;
    }

    /**
     * This method confirms reservation.
     *
     * @param  $hotelBC
     *
     * @return HotelApiResponse The API results.
     */
    public function confirmReservation(HotelBookingCriteria $hotelBC)
    {
        $params              = $hotelBC->getDetails();
        $params['hotelName'] = $hotelBC->getHotelName();
        $params['userId']    = $hotelBC->getUserId();
        $params['hotelId']   = $hotelBC->getHotelId();

        $this->logger->addHotelActivityLog('HRS', 'RESERVATION', $hotelBC->getUserId(), array(
            "offersSelectedCount" => count($hotelBC->getRooms()),
            "hotelName" => $hotelBC->getHotelName(),
            "criteria" => $params
        ));

        $results = $this->hotelReservation($params);

        $this->logger->addBookingRequestLog('HRS', 'reservation', $hotelBC->getUserId(), $params, $results);

        return $this->HRSNormalizer->processReservationResponse($params, $results);
    }

    /**
     * getBookingRecord
     *
     * @param  $hotelBooking
     * @param  $includeCanceledRooms
     *
     * @return
     */
    public function getBookingRecord(HotelBooking $hotelBooking, $includeCanceledRooms)
    {
        $response = new HotelApiResponse();

        $results = $this->hotelReservationInformation(array(
            'reservationProcessKey' => $hotelBooking->getControlNumber(),
            'reservationProcessPassword' => $hotelBooking->getBookingPassword(),
            'hotelKey' => $hotelBooking->getHotelCode(),
            'genericCriteria' => array('returnRoomDescriptions' => 'split'),
            'userId' => $hotelBooking->getUserId(),
            'hotelId' => $hotelBooking->getHotelId()
        ));

        if (isset($results->error)) {
            $response->setError($results->error->message);
        } else {
            $hotelDetails = $this->HRSNormalizer->processHotelDetailInformation($results->reservationInformationRoomDetails[0]->hotelDetail);

            $hotelRooms = array();
            $keyMap     = array();
            foreach ($results->reservationInformationRoomDetails as $roomDetails) {
                $roomDetails = json_decode(json_encode($roomDetails), true);

                if (!empty($roomDetails['reservationRoomOfferDetail']['predecessorReservationKey'])) {
                    $keyMap[$roomDetails['reservationRoomOfferDetail']['predecessorReservationKey']] = $roomDetails['reservationRoomOfferDetail']['reservationKey'];
                }

                $hotelRoom = $this->processRoomBooking($roomDetails, $hotelBooking);

                $hotelRooms[$hotelRoom->getReservationKey()] = $hotelRoom;
            }

            $roomDetails = array();
            if ($includeCanceledRooms) {
                foreach ($hotelBooking->getRooms() as $reservationKey => $dbRoom) {
                    if (isset($hotelRooms[$reservationKey]) || isset($keyMap[$reservationKey])) {
                        continue;
                    }

                    // all rooms here are expected as cancelled rooms.
                    if ($dbRoom->getRoomStatus() != $this->container->getParameter('hotels')['reservation_canceled']) {
                        // this is the case that not all rooms are booked during reservation even if reservation is successfull.
                        // Thus, reservation information returns not all rooms that are reserved.
                        // for now we will not include these rooms since below process will throw a lot of error.
                        continue;
                    }

                    $dbRoomInfo                                = json_decode($dbRoom->getRoomInfo(), true);
                    $roomDetails['cancellation']               = $dbRoomInfo['roomReservedInfo']['cancellation'];
                    $roomDetails['reservationRoomOfferDetail'] = $dbRoomInfo['roomOfferDetail'];

                    $hotelRooms[$reservationKey] = $this->processRoomBooking($roomDetails, $hotelBooking);
                }
            }

            $response->setSuccess(true);
            $response->setData(array(
                'hotelDetails' => $hotelDetails,
                'reservedRoomInfo' => $hotelRooms,
                'creditCardDetails' => json_decode(json_encode($results->reservationInformationRoomDetails[0]->creditCard), true)
            ));
        }

        return $response;
    }

    /**
     * Checks whether a booking has been cancelled or not
     *
     * @param  $hotelBC
     *
     * @return
     */
    public function isBookingCanceled(HotelBookingCriteria $hotelBC)
    {
        $canceled = true;

        $results = $this->hotelReservationInformation(array(
            'reservationProcessKey' => $hotelBC->getControlNumber(),
            'reservationProcessPassword' => $hotelBC->getBookingPassword(),
            'hotelKey' => $hotelBC->getHotelCode(),
            'genericCriteria' => array('returnRoomDescriptions' => 'split'),
            'userId' => $hotelBC->getUserId(),
            'hotelId' => $hotelBC->getHotelId()
        ));

        if ($results && !isset($results->error)) {
            foreach ($results->reservationInformationRoomDetails as $room_details) {
                if ($room_details->reservationStatus != 'canceled') {
                    $canceled = false;
                    break;
                }
            }
        }
        return $canceled;
    }

    /**
     * This method modifies a reservation
     *
     * @param  $action
     * @param  $hotelBC
     *
     * @return
     */
    public function modifyReservation($action, HotelBookingCriteria $hotelBC)
    {
        $this->logger->addHotelActivityLog('HRS', $action, $hotelBC->getUserId(), array("reference" => $hotelBC->getReference()));

        $params                               = $hotelBC->getDetails();
        $params['reservationProcessKey']      = $hotelBC->getControlNumber();
        $params['reservationProcessPassword'] = $hotelBC->getBookingPassword();
        $params['userId']                     = $hotelBC->getUserId();
        $params['hotelId']                    = $hotelBC->getHotelId();

        $results = $this->hotelReservationModification($params);

        $params['hotelName'] = $hotelBC->getHotelName();
        $this->logger->cleanParams($params);
        $this->logger->addBookingRequestLog('HRS', $action, $hotelBC->getUserId(), $params, $results);

        return $this->HRSNormalizer->processReservationResponse($params, $results);
    }

    /**
     * getCancelledRoomReservationInfo
     *
     * @param  $roomObj
     * @param  $hotelBC
     *
     * @return
     */
    public function getCancelledRoomReservationInfo(\HotelBundle\Entity\HotelRoomReservation $roomObj, HotelBookingCriteria $hotelBC)
    {
        $results = $this->hotelReservationInformation(array(
            'reservationProcessKey' => $roomObj->getReservationProcessKey(),
            'reservationProcessPassword' => $roomObj->getReservationProcessPassword(),
            'genericCriteria' => array('returnRoomDescriptions' => 'split'),
            'userId' => $hotelBC->getUserId(),
            'hotelId' => $hotelBC->getHotelId()
        ));

        if (isset($results->error)) {
            return array('error' => $results->error->message);
        } elseif ($results) {
            foreach ($results->reservationInformationRoomDetails as $room_details) {
                if ($room_details->reservationRoomOfferDetail->predecessorReservationKey == $roomObj->getReservationKey() || $room_details->reservationRoomOfferDetail->reservationKey == $roomObj->getReservationKey()) {
                    $roomReservedInfo = json_decode(json_encode($room_details), true);
                    $roomOfferDetail  = $roomReservedInfo['reservationRoomOfferDetail'];
                    unset($roomReservedInfo['reservationRoomOfferDetail']);
                    unset($roomOfferDetail['reservationPersons']);

                    return array('reservationKey' => $roomOfferDetail['reservationKey'], 'roomOfferDetail' => $roomOfferDetail, 'roomReservedInfo' => $roomReservedInfo);
                }
            }
        }

        return array();
    }

    //*****************************************************************************************
    // Room Offer Info
    /**
     *
     * @param  Array  $offerDetail
     * @param  Array  $room
     * @param  String $requestingPage
     *
     * @return
     */
    public function getRoomStayDetails($offerDetail, $room, $requestingPage = 'OFFERS')
    {
        $roomTypeInfo = $this->HRSNormalizer->getRoomTypeInfo($room);

        $hotelRoom = $this->HRSNormalizer->getHotelRoomOffer($offerDetail, $roomTypeInfo, 0, $requestingPage);
        $hotelRoom->setHeader(array('roomHeadline' => ucwords($room['roomType'].' - '.$hotelRoom->getName())));

        return $hotelRoom;
    }

    /**
     * Process Room Booking
     *
     * @param  $roomDetails
     * @param  $hotelBooking
     *
     * @return
     */
    private function processRoomBooking($roomDetails, HotelBooking $hotelBooking)
    {
        $cancellation              = $roomDetails['cancellation'];
        $reservationKey            = $roomDetails['reservationRoomOfferDetail']['reservationKey'];
        $predecessorReservationKey = $roomDetails['reservationRoomOfferDetail']['predecessorReservationKey'];
        $room                      = $roomDetails['reservationRoomOfferDetail']['room'];
        $offerDetail               = $roomDetails['reservationRoomOfferDetail']['offerDetail'];

        $roomObj = null;
        if (!empty($predecessorReservationKey) && isset($hotelBooking->getRooms()[$predecessorReservationKey])) {
            $roomObj = $hotelBooking->getRooms()[$predecessorReservationKey];
        } elseif (isset($hotelBooking->getRooms()[$reservationKey])) {
            $roomObj = $hotelBooking->getRooms()[$reservationKey];
        }

        if (!empty($roomObj)) {
            $roomOfferDetail = json_decode($roomObj->getRoomOfferDetail(), true);

            $room        = $roomOfferDetail['room'];
            $offerDetail = $roomOfferDetail['offerDetail'];
        }

        $hotelRoom = $this->getRoomStayDetails($offerDetail, $room, 'reservationInfoPage');
        $hotelRoom->setReservationKey($reservationKey); // this is the predecessorReservationKey if the room is canceled, reservationKey otherwise
        $hotelRoom->setMaxRoomCount(($room['roomType'] == 'double') ? 2 : 1);

        // Guest Name
        $travellerInfo = json_decode($roomObj->getGuests(), true)[0];
        $hotelRoom->setGuestName(sprintf("%s %s", $travellerInfo['firstName'], $travellerInfo['lastName']));
        $hotelRoom->setGuestEmail($travellerInfo['email']);

        // Cancellation
        $hotelRoom->setCancellationDate($cancellation);

        // Room Status
        $hotelRoom->setStatus($roomObj->getRoomStatus());

        return $hotelRoom;
    }

    //*****************************************************************************************
    // Helper Functions
    /**
     * sendRequestCurl
     *
     * @param  $function
     * @param  $data
     * @param  $params
     *
     * @return
     */
    private function sendRequestCurl($function, $data, $params = array())
    {
        $results = array();

        if (isset($params['genericCriteria']) && !empty($params['genericCriteria'])) {
            $data['genericCriteria'] = array();
            foreach ($params['genericCriteria'] as $name => $value) {
                $data['genericCriteria'][] = array(
                    'key' => $name,
                    'value' => $value
                );
            }
        }

        $request = $this->formatRequest($function, $data);
        $headers = array(
            'Content-Type' => 'text/xml; charset="utf-8"',
            'Accept' => 'text/xml',
            'Content-Length' => strlen($request),
        );

        $response     = $this->utils->send_data($this->URL, $request, \HTTP_Request2::METHOD_POST, array(), $headers);
        $responseText = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response['response_text']);

        $prevSetting = libxml_use_internal_errors(true);
        try {
            $responseXML = (empty($responseText)) ? '' : new \SimpleXMLElement($responseText);
            $error       = $this->errorHandler($response, $responseXML);

            if (!$error) {
                $responseObject = $responseXML->xpath('//envBody/com'.$function.'Response/'.$function.'Response');
                if (empty($responseObject)) {
                    $results = array('error' => $this->getErrorMessage('server_down'));
                } else {
                    $responseObject = $responseObject[0];
                    $results        = json_decode(json_encode($responseObject), true);
                }
            } else {
                $results = array('error' => $error);
            }
        } catch (\Exception $e) {
            $error   = $this->getErrorMessage('exception', $e->getMessage(), false, true);
            $results = array('error' => $error);
        }
        libxml_clear_errors();
        libxml_use_internal_errors($prevSetting);

        $params['timeStart']     = date('YmdHis', time());
        $results['errorLogFile'] = $this->logger->getLogFile('hrs', $function, $params);

        // Log Request
        $this->logger->info($results['errorLogFile'], 'request', $request);

        // Log Response
        $this->logger->info($results['errorLogFile'], 'response', $responseText);

        if (isset($results['error'])) {
            // Log Error
            $this->logger->error($results['errorLogFile'], array('message' => $error));
        }

        return $results;
    }

    /**
     * errorHandler
     *
     * @param  $response
     * @param  $xml
     *
     * @return
     */
    private function errorHandler($response, $xml)
    {
        $error = false;

        if ($response['response_error'] == RESPONSE_ERROR) {
            $error = true;
        }

        if ($error && empty($xml)) {
            $error = $response['reason_phrase'];
        } elseif (empty($xml)) {
            $error = $this->getErrorMessage('server_down', '', false, true);
        } else {
            try {
                // There might be other error structure that's out there, update here when necessary
                $hrsException      = $xml->xpath('//envBody/envFault/detail/ns2HRSException');
                $hrsExceptionArray = json_decode(json_encode($hrsException), true);

                if (!empty($hrsExceptionArray)) {
                    $error = $this->getErrorMessage($hrsExceptionArray[0]['code'], $hrsExceptionArray[0]['message'], false, true);
                } elseif ($error) {
                    $error = $response['response_text'];
                }
            } catch (\Exception $e) {
                $error = $this->getErrorMessage('exception', $e->getMessage(), false, true);
            }
        }

        return $error;
    }

    /**
     * formatRequest
     *
     * @param  $function
     * @param  $data
     *
     * @return
     */
    private function formatRequest($function, $data)
    {
        $data = array_merge($this->initializeRequestData(), $data);

        // Convert array data to XML
        $dataXML = new \SimpleXMLElement('<'.$function.'Request></'.$function.'Request>');
        $this->arrayToXML($data, $dataXML);

        // Remove the unnecessary xml version tag
        $dom         = dom_import_simplexml($dataXML);
        $requestData = $dom->ownerDocument->saveXML($dom->ownerDocument->documentElement);

        // Complete the XML request envelope, mind the first tag should not have tabs or spaces
        $request = <<<EOL
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:com="com.hrs.soap.hrs">
    <soapenv:Header/>
    <soapenv:Body>
        <com:$function>
            $requestData
        </com:$function>
    </soapenv:Body>
</soapenv:Envelope>
EOL;

        return $request;
    }

    /**
     * arrayToXML
     *
     * @param  $data
     * @param  $xmlData
     * @param  $parentKey
     *
     * @return
     */
    private function arrayToXML($data, &$xmlData, $parentKey = '')
    {
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                // If we have a numeric key, that means this data is defined as 0..n or 1..n, tag it appropriately
                $key = $parentKey;
            }

            if (is_array($value)) {
                if ($this->hasStringKeys($value)) {
                    $subnode = $xmlData->addChild($key);
                    $this->arrayToXML($value, $subnode);
                } else {
                    $this->arrayToXML($value, $xmlData, $key);
                }
            } else {
                if (is_bool($value)) {
                    $value = ($value) ? 1 : 0;
                }
                $xmlData->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

    // SoapClient Helper Functions
    /**
     * sendRequest
     *
     * @param  $function
     * @param  $data
     * @param  $params
     *
     * @return
     */
    private function sendRequest($function, $data, $params = array())
    {
        $results = array();

        if (isset($params['genericCriteria']) && !empty($params['genericCriteria'])) {
            $data['genericCriteria'] = array();
            foreach ($params['genericCriteria'] as $name => $value) {
                $data['genericCriteria'][] = array(
                    'key' => $name,
                    'value' => $value
                );
            }
        }

        // Include HRSRequest Data in Soap Request
        $data = array_merge($this->initializeRequestData($function), $data);

        $timeout     = $this->config->request_timeout;
        $origTimeout = ini_get('default_socket_timeout');
        ini_set('default_socket_timeout', $timeout);

        try {
            $time_start = microtime(true);
            $results    = $this->CLIENT->$function($data);
        } catch (\SoapFault $sf) {
            $time_request = (microtime(true) - $time_start);
            if ($time_request > $timeout) {
                $results['error'] = $this->getErrorMessage('timeout');
            } else {
                $lastResponse = $this->CLIENT->__getLastResponse();
                $lastResponse = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $lastResponse);
                $xml          = simplexml_load_string($lastResponse);
                if ($xml === false) {
                    $results['error'] = $this->getErrorMessage('server_down');
                } else {
                    $response = json_decode(json_encode($xml), true);

                    if (isset($response['envBody']['envFault']['detail']['ns2HRSException'])) {
                        $hrsException     = $response['envBody']['envFault']['detail']['ns2HRSException'];
                        $results['error'] = $this->getErrorMessage($hrsException['code'], $hrsException['message']);
                    } else {
                        $results['error'] = $this->getErrorMessage('server_down');
                    }
                }
            }
        } catch (\Exception $e) {
            $results['error'] = $this->getErrorMessage('exception', $e->getMessage());
        }

        ini_set('default_socket_timeout', $origTimeout);

        $params['timeStart'] = date('YmdHis', time());
        $errorLogFile        = $this->logger->getLogFile('hrs', $function, $params);

        // Log Request
        $this->logger->info($errorLogFile, 'request', $this->CLIENT->__getLastRequest(), true);

        // Log Response
        $this->logger->info($errorLogFile, 'response', $this->CLIENT->__getLastResponse());

        if (is_array($results) && isset($results['error'])) {
            // Log Error
            $this->logger->error($errorLogFile, $results['error']);
            $results['errorLogFile'] = $errorLogFile;
        }

        // We need to standardize the return into object
        return (object) $results;
    }

    // Generic Helper Functions
    /**
     * formatChild
     *
     * @param  HotelSC $hotelSC
     *
     * @return
     */
    private function formatChild(HotelSC $hotelSC)
    {
        $toReturn = array();

        if ($hotelSC->getChildCount() > 0) {
            $childAges = $hotelSC->getChildAge();
            $childBeds = $hotelSC->getChildBed();

            for ($i = 1; $i <= $hotelSC->getChildCount(); $i++) {
                $toReturn[] = array('age' => $childAges[$i], 'bed' => $childBeds[$i]);
            }
        }

        return $toReturn;
    }

    /**
     * getCCValidity
     *
     * @param  $month
     * @param  $year
     *
     * @return
     */
    public function getCCValidity($month, $year)
    {
        return date('m/y', mktime(0, 0, 0, $month, 1, $year));
    }

    /**
     * getErrorMessage
     *
     * @param  $code
     * @param  $msg
     * @param  $object
     * @param  $msgOnly
     *
     * @return
     */
    private function getErrorMessage($code, $msg = '', $object = true, $msgOnly = false)
    {
        $translator = $this->container->get('translator');
        $error      = array('code' => $code, 'message' => '');

        if (empty($this->ERROR_MAP)) {
            $this->ERROR_MAP = array(
                '-5000' => $translator->trans('There was an error trying to process the reservation. Please try again.'),
                '-5002' => $translator->trans('Reservation not possible because the room rate is not available anymore. Please refresh your search and try again.'),
                '-5003' => $translator->trans('The hotel room rate is not available anymore. Please try again.'),
                '-5200' => $translator->trans('There was an error while trying to process the cancellation.'),
                '-5203' => $translator->trans('The reservation is not cancelable.'),
                '3601' => $translator->trans('Invalid Check-In/Check-Out date.'),
                '4002' => $translator->trans('The hotel key is invalid.'),
                '4102' => $translator->trans('The credit card number you entered is invalid.'),
                '4109' => $translator->trans('We were unable to process your credit card.'),
                'exception' => $translator->trans('There was a problem connecting to our hotel booking system. Please try again.'),
                'timeout' => $translator->trans('A timeout occured while connecting to our hotel booking system. Please try again.'),
                'server_down' => $translator->trans('The server is not responding at the moment. We should be back shortly.'),
                '-5100' => $translator->trans('There was a problem loading your reservation information. Please try again.')
            );
        }

        if ($code == '-5000' && (strpos($msg, 'INVALID CREDIT CARD NUMBER') !== false)) {
            $error['message'] = $translator->trans("Hotel reservation failed. ").$this->ERROR_MAP['4102'];
        } elseif (isset($this->ERROR_MAP[$code])) {
            $error['message'] = $this->ERROR_MAP[$code];
        } else {
            $error['message'] = $translator->trans("An unknown error was encountered while connecting to our hotel booking system. Please try again later.");
        }

        if ($msgOnly) {
            $error = $error['message'];
        }

        if ($object) {
            return (object) $error;
        } else {
            return $error;
        }
    }

    /**
     * This method returns the site language code
     *
     * @return
     */
    private function getLanguageCode()
    {
        global $GLOBAL_LANG;

        switch ($GLOBAL_LANG) {
            case 'fr': $langCode = 'FRA';
                break;
            case 'in': $langCode = 'HIN';
                break;
            case 'cn': $langCode = 'ZHO';
                break;
            default: $langCode = 'ENG';
                break;
        }

        return $langCode;
    }

    /**
     * Returns the Room Criteria
     *
     * @param  $hotelSC
     *
     * @return data
     */
    private function getRoomCriteria(HotelSC $hotelSC)
    {
        $roomCriteria = array();

        $tempChild      = $this->formatChild($hotelSC);
        $doubleOccupant = $hotelSC->getAdultCount() - $hotelSC->getSingleRooms();

        // Looping thru adults assigned to double rooms
        while ($doubleOccupant > 0) {

            // Then we loop thru the number of double rooms
            for ($i = 0, $id = 1; $i < $hotelSC->getDoubleRooms() && $doubleOccupant > 0; $i++, $id++) {

                // For each iteration, we define the necessary $roomCriteria with the basic values including child accommodation if available
                // Else if already defined, we increment the adult count for the room and change the room type depending on the adult count
                // We've already done some trapping on client side, so we expect maximum of 2 extra guests per double room
                // Child is also considered as extra guest but should not be included in adultCount
                if (!isset($roomCriteria[$i])) {
                    $roomCriteria[$i] = array(
                        'id' => $id,
                        'roomType' => 'double',
                        'adultCount' => 1
                    );

                    $keys       = (!empty($tempChild)) ? array_keys($tempChild) : array();
                    $ctr        = $chCount    = $parentsBed = 0;

                    while ($chCount < 2 && !empty($tempChild) && isset($keys[$ctr]) && isset($tempChild[$keys[$ctr]])) {
                        $ch = $tempChild[$keys[$ctr]];
                        if ($parentsBed > 0 && $ch['bed'] == 'parentsBed') {
                            $ctr++;
                            continue;
                        }

                        $roomCriteria[$i]['childAccommodationCriteria'][] = array(
                            'childAge' => $ch['age'],
                            'childAccommodation' => $ch['bed']
                        );

                        if ($ch['bed'] == 'parentsBed') {
                            $parentsBed++;
                        }

                        unset($tempChild[$keys[$ctr]]);

                        $ctr++;
                        $chCount++;
                    }
                } else {
                    $chCount = (isset($roomCriteria[$i]['childAccommodationCriteria'])) ? count($roomCriteria[$i]['childAccommodationCriteria']) : 0;

                    if (($roomCriteria[$i]['adultCount'] + $chCount) >= 4) {
                        continue;
                    }

                    $roomCriteria[$i]['adultCount'] ++;

                    switch ($roomCriteria[$i]['adultCount']) {
                        case 1:
                        case 2: $roomCriteria[$i]['roomType'] = 'double';
                            break;
                        case 3: $roomCriteria[$i]['roomType'] = 'double+1';
                            break;
                        case 4: $roomCriteria[$i]['roomType'] = 'double+2';
                            break;
                    }
                }

                $doubleOccupant--;
            }
        }

        // Then let's set up room criteria for single rooms
        $id     = $hotelSC->getDoubleRooms() + 1;
        $single = array();

        for ($i = 0; $i < $hotelSC->getSingleRooms(); $i++, $id++) {
            $single[] = array(
                'id' => $id,
                'roomType' => 'single',
                'adultCount' => 1
            );
        }

        $roomCriteria = array_merge($single, $roomCriteria);

        return $roomCriteria;
    }

    /**
     * hasStringKeys
     *
     * @param  $array
     *
     * @return
     */
    private function hasStringKeys(array $array)
    {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    /**
     * This method prepares the basic request data
     *
     * @param  $function
     *
     * @return
     */
    private function initializeRequestData($function = '')
    {
        $iso3Language = $this->getLanguageCode();
        switch ($function) {
            case 'hotelDetailAvail':
                $iso3Language = 'ENG';
                break;
        }

        // All requests extend HRSRequest Data, hence initializing it in one place
        $data = array(
            'credentials' => array(
                'clientType' => $this->config->client_type,
                'clientKey' => $this->config->client_key,
                'clientPassword' => $this->config->client_password
            ),
            'locale' => array(
                'language' => array(
                    'iso3Language' => $iso3Language
                ),
                'iso3Country' => $this->config->default_language,
                'isoCurrency' => $this->config->default_currency
            )
        );

        return $data;
    }
}
