<?php

namespace RestBundle\Controller\hotels;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RestBundle\Controller\TTRestController;

class HRSController extends TTRestController
{

    /**
     * This method sets the container to be called in this class
     *
     * @return
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->request             = Request::createFromGlobals();
        $this->utils               = $this->get('app.utils');
        $this->logger              = $this->container->get('HotelLogger');
        $this->transactionSourceId = $this->container->getParameter('WEB_REFERRER');
        $this->pageSrc             = $this->container->getParameter('hotels')['page_src']['hrs'];
    }

    /**
     * This method initializes HotelServiceConfig object to be used when calling a service.
     * @return \HotelBundle\Model\HotelServiceConfig
     */
    private function getHotelServiceConfig()
    {
        $hotelServiceConfig = new \HotelBundle\Model\HotelServiceConfig();
        $hotelServiceConfig->setIsRest(true);
        $hotelServiceConfig->setTransactionSourceId($this->transactionSourceId);
        $hotelServiceConfig->setPageSrc($this->pageSrc);

        return $hotelServiceConfig;
    }

    /**
     * This method lists the available hotels as per the posted search criteria.
     *
     * @return data
     */
    public function listAction()
    {
        // specify required fields
        $requirements = array(
            'entityType',
            'fromDate',
            'toDate',
            'singleRooms',
            'doubleRooms',
            'adultCount',
            'childCount'
        );

        // fetch post json data
        $requestData = $this->fetchRequestData($requirements);

        $requestData['from_mobile'] = true;
        if (isset($requestData['stars']) && !empty($requestData['stars'])) {
            $requestData['nbrStars'] = [$requestData['stars']];
        } elseif (!isset($requestData['nbrStars'])) {
            $requestData['nbrStars'] = [];
        }

        if (isset($requestData['currencyCode'])) {
            $requestData['selectedCurrency'] = $requestData['currencyCode'];
        }

        $childAge = array();
        $childBed = array();

        $minIdx = (isset($requestData['childAge']) && !empty($requestData['childAge'])) ? min(array_keys($requestData['childAge'])) : 0;
        for ($i = 1; $i <= $this->container->getParameter('hotels')['max_child_count']; $i++) {
            $childIdx = ($minIdx == 0) ? ($i - 1) : $i;

            $childAge[$i] = (isset($requestData['childAge'][$childIdx]) && !empty($requestData['childAge'][$childIdx])) ? $requestData['childAge'][$childIdx] : 0;
            $childBed[$i] = (isset($requestData['childBed'][$childIdx]) && !empty($requestData['childBed'][$childIdx])) ? $requestData['childBed'][$childIdx] : "parentsBed";
        }

        $requestData['childAge'] = $childAge;
        $requestData['childBed'] = $childBed;

        $hotelSC = $this->get('HRSServices')->getHotelSearchCriteria($requestData);

        $hotelServiceConfig = $this->getHotelServiceConfig();

        return $this->get('HRSServices')->hotelsAvailability($hotelServiceConfig, $hotelSC);
    }

    /**
     * Method GET
     * This method returns the needed hotel details and available offers.
     *
     * @return data
     */
    public function offersAction($hotelId)
    {
        // specify required fields
        $requirements = array(
            array('name' => 'fromDate', 'required' => true, 'type' => 'string', 'constraints' => array('date' => true)),
            array('name' => 'toDate', 'required' => true, 'type' => 'string', 'constraints' => array('date' => true)),
            array('name' => 'singleRooms', 'required' => true, 'constraints' => array('gte' => 0)),
            array('name' => 'doubleRooms', 'required' => true, 'constraints' => array('gte' => 0)),
            array('name' => 'adultCount', 'required' => true, 'constraints' => array('gt' => 0)),
            array('name' => 'childCount', 'required' => true, 'constraints' => array('gte' => 0))
        );

        // fetch post json data
        $requestData = $this->fetchRequestData($requirements);

        $requestData['from_mobile'] = true;
        $requestData['hotelId']     = ($hotelId) ? $hotelId : $requestData['hotelId'];

        if (isset($requestData['currencyCode'])) {
            $requestData['selectedCurrency'] = $requestData['currencyCode'];
        }

        $childAge = array();
        $childBed = array();

        $minIdx = (isset($requestData['childAge']) && !empty($requestData['childAge'])) ? min(array_keys($requestData['childAge'])) : 0;
        for ($i = 1; $i <= $this->container->getParameter('hotels')['max_child_count']; $i++) {
            $childIdx = ($minIdx == 0) ? ($i - 1) : $i;

            $childAge[$i] = (isset($requestData['childAge'][$childIdx]) && !empty($requestData['childAge'][$childIdx])) ? $requestData['childAge'][$childIdx] : 0;
            $childBed[$i] = (isset($requestData['childBed'][$childIdx]) && !empty($requestData['childBed'][$childIdx])) ? $requestData['childBed'][$childIdx] : "parentsBed";
        }

        $requestData['childAge'] = $childAge;
        $requestData['childBed'] = $childBed;

        $hotelSC = $this->get('HRSServices')->getHotelSearchCriteria($requestData);

        $hotelServiceConfig = $this->getHotelServiceConfig();

        return $this->get('HRSServices')->hotelOffers($hotelServiceConfig, $hotelSC);
    }

    /**
     * Method POST
     * This method returns the complete pricing info of the selected offers for booking.
     *
     * @return data
     */
    public function prebook($requestData)
    {
        $prebook                 = array();
        $prebook['fromDate']     = $requestData['fromDate'];
        $prebook['toDate']       = $requestData['toDate'];
        $prebook['hotelDetails'] = $requestData['hotelDetails'];
        $prebook['hotelKey']     = $requestData['hotelDetails']['hotelKey'];

        $selectedOffers = $requestData['selectedOffers'];

        $counter = 0;
        foreach ($selectedOffers as $offer) {
            $counter++;
            $prebook['offer_select_'.$counter] = $offer['quantity'];
            $prebook['offerDetail_'.$counter]  = $offer['offerDetail'];
            $prebook['room_'.$counter]         = $offer['room'];
        }
        $prebook['totalNumOffers'] = $counter;

        $hotelBC = $this->get('HRSServices')->getHotelBookingCriteria($prebook);
        // For REST, we only need the $offersSelected and $reservationDetails from the returned data
        list(, $offersSelected, $reservationDetails) = $this->get('HRSServices')->preBook($hotelBC);

        $returnData                    = array();
        $returnData['selectedOffers']  = $offersSelected;
        $returnData['reference']       = $reservationDetails['reference']; //used in web/email URLs
        $returnData['ccRequired']      = ($reservationDetails['ccRequired']) ? 1 : 0;
        $returnData['ccCodeRequired']  = ($reservationDetails['ccCodeRequired']) ? 1 : 0;
        $returnData['reservationMode'] = $reservationDetails['reservationMode'];

        return $returnData;
    }

    /**
     * This method processes booking request.
     *
     * @return data
     */
    public function reservationAction()
    {
        // specify required fields
        $requirements = array(
            array('name' => 'title', 'required' => true, 'type' => 'integer'),
            array('name' => 'firstName', 'required' => true, 'type' => 'string'),
            array('name' => 'lastName', 'required' => true, 'type' => 'string'),
            array('name' => 'email', 'required' => true, 'type' => 'string', 'constraints' => array('email' => 1)),
            array('name' => 'country', 'required' => true, 'type' => 'string'),
            array('name' => 'mobileCountryCode', 'required' => true, 'type' => 'string'),
            //array('name' => 'mobile', 'required' => true, 'type' => 'string'),
            array('name' => 'guestFirstName', 'required' => true, 'type' => 'array'),
            array('name' => 'guestLastName', 'required' => true, 'type' => 'array'),
            array('name' => 'ccType', 'required' => true),
            array('name' => 'ccCardHolder', 'required' => true),
            array('name' => 'ccNumber', 'required' => true),
            array('name' => 'ccExpiryMonth', 'required' => true),
            array('name' => 'ccExpiryYear', 'required' => true),
            array('name' => 'ccCVC', 'required' => true),
            array('name' => 'fromDate', 'required' => true, 'type' => 'string', 'constraints' => array('date' => 1)),
            array('name' => 'toDate', 'required' => true, 'type' => 'string', 'constraints' => array('date' => 1)),
            array('name' => 'hotelDetails', 'required' => true, 'type' => 'array'),
            array('name' => 'selectedOffers', 'required' => true, 'type' => 'array'),
            array('name' => 'transactionId', 'required' => true, 'type' => 'string'),
        );

        // fetch post json data
        $requestData = $this->fetchRequestData($requirements);

        for ($key = 0; $key < count($requestData['selectedOffers']); $key++) {
            if (!isset($requestData['guestFirstName'][$key]) || empty($requestData['guestFirstName'][$key]) || !isset($requestData['guestLastName'][$key]) || empty($requestData['guestLastName'][$key])) {
                return array("code" => 400, "message" => $this->translator->trans("Invalid guest params"));
            }
        }

        //prebook
        $prebook = $this->prebook($requestData);

        $serviceConfig = $this->getHotelServiceConfig();

        $requestData['reference']               = $prebook['reference'];
        $requestData['ccRequired']              = $prebook['ccRequired'];
        $requestData['ccCodeRequired']          = $prebook['ccCodeRequired'];
        $requestData['reservationMode']         = $prebook['reservationMode'];
        $requestData['offersSelectedSerialize'] = json_encode($prebook['selectedOffers']);
        $requestData['transactionSourceId']     = $this->transactionSourceId;
        $requestData['userId']                  = $this->userGetID();
        if (isset($requestData['arrivalTime'])) {
            $requestData['arrival_time'] = $requestData['arrivalTime'];
            unset($requestData['arrivalTime']);
        }
        unset($requestData['selectedOffers']);

        $hotelBC = $this->get('HRSServices')->getHotelBookingCriteria($requestData);

        $toreturn = $this->get('HRSServices')->processHotelReservationRequest($serviceConfig, $hotelBC);

        return $toreturn;
    }

    /**
     * This method returns booking details.
     *
     * @return data
     */
    public function bookingDetailsAction($reference)
    {
        $serviceConfig = $this->getHotelServiceConfig();
        $serviceConfig->setPage('BOOKING_DETAILS');

        $reservationInfo = $this->get('HRSServices')->bookingDetails($serviceConfig, $reference);

        return $reservationInfo;
    }

    /**
     * This method cancels a given booking.
     *
     * @return data
     */
    public function cancelAction()
    {
        // specify required fields
        $requirements = array(
            array('name' => 'reference', 'required' => true, 'type' => 'string'),
        );

        // fetch post json data
        $params                        = $this->fetchRequestData($requirements);
        $params['transactionSourceId'] = $this->transactionSourceId;
        if (isset($params['reservationKey'])) {
            $requirements = array(
                array('name' => 'ccType', 'required' => true),
                array('name' => 'ccCardHolder', 'required' => true),
                array('name' => 'ccNumber', 'required' => true),
                array('name' => 'ccExpiryMonth', 'required' => true),
                array('name' => 'ccExpiryYear', 'required' => true),
                array('name' => 'ccCVC', 'required' => true),
            );
            $this->validateFetchedRequestData($params, $requirements);

            $params['cancelReservationKey'] = $params['reservationKey'];
            unset($params['reservationKey']);
        }

        $toreturn = $this->get('HRSServices')->processCancellationRequest($this->getHotelServiceConfig(), $params);
        if (!isset($toreturn['error'])) {
            return $this->bookingDetailsAction($params['reference']);
        }

        return $toreturn;
    }

    /**
     * This method fetches a given user's hotel bookings.
     *
     * @return data
     */
    public function getUserBookingsAction()
    {
        // specify required fields
        $requirements = array(
            array('name' => 'userId', 'required' => true, 'type' => 'integer'),
            array('name' => 'userEmail', 'required' => true, 'type' => 'string', 'constraints' => array('email' => 1)),
            array('name' => 'bookingStatus', 'required' => true, 'type' => 'integer'),
            array('name' => 'fromDate', 'type' => 'string', 'constraints' => array('date' => 1)),
            array('name' => 'toDate', 'type' => 'string', 'constraints' => array('date' => 1)),
            array('name' => 'page', 'required' => true, 'type' => 'integer'),
            array('name' => 'showMore', 'required' => true, 'type' => 'integer'),
        );

        // fetch post json data
        $params = $this->fetchRequestData($requirements);

        $hotelBookingSC = new \HotelBundle\Model\HotelBookingSC();
        $hotelBookingSC->setUserId($params['userId']);
        $hotelBookingSC->setUserEmail($params['userEmail']);
        $hotelBookingSC->setBookingStatus($params['bookingStatus']);
        $hotelBookingSC->setFromDate($params['fromDate']);
        $hotelBookingSC->setToDate($params['toDate']);
        $hotelBookingSC->setPage($params['page']);
        $hotelBookingSC->setShowMore($params['showMore']);
        $hotelBookingSC->setIsRest(1);

        $userInfo = $this->container->get('UserServices')->getUserInfoById($params['userId']);
        if ($userInfo['cu_youremail'] != $params['userEmail']) {
            return array("code" => 400, "message" => $this->translator->trans("UserId and UserEmail do not match."));
        }

        $toreturn = $this->get('HRSServices')->getUserBookings($this->getHotelServiceConfig(), $hotelBookingSC);

        return $toreturn;
    }
}
