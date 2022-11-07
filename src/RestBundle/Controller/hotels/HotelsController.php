<?php

namespace RestBundle\Controller\hotels;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RestBundle\Controller\TTRestController;
use RestBundle\Model\RestBookingResponseVO;

class HotelsController extends TTRestController
{
    private $infoSource;

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
        $this->infoSource          = $this->container->getParameter('modules')['hotels']['vendors']['amadeus']['infosource']['multisource'];
        $this->transactionSourceId = $this->container->getParameter('WEB_REFERRER');
    }

    /**
     * This method initializes HotelServiceConfig object to be used when calling a service.
     * @return \HotelBundle\Model\HotelServiceConfig
     */
    private function getHotelServiceConfig()
    {
        $hotelServiceConfig = new \HotelBundle\Model\HotelServiceConfig();
        $hotelServiceConfig->setIsRest(true);
        $hotelServiceConfig->setUseTTApi(true);
        $hotelServiceConfig->setUserAgent($this->request->headers->get('User-Agent'));
        $hotelServiceConfig->setTransactionSourceId($this->transactionSourceId);

        return $hotelServiceConfig;
    }

    /**
     * This method returns the matching items of the term searched (auto-complete).
     *
     * @param String $term The searched term
     * @return data
     */
    public function searchAction()
    {
        $term = $this->request->query->get('term');

        if (!isset($term) && empty($term)) {
            throw new HttpException(403, $this->translator->trans("Missing term parameter."));
        }

        $return = $this->get('HotelsServices')->getSearchSuggestions($term);

        if (empty($return)) {
            $response = new Response();
            $response->setStatusCode(204, $this->translator->trans("No data found."));
            return $response;
        }

        return $return;
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

        $requestData['infoSource']       = $this->infoSource;
        $requestData['selectedCurrency'] = (isset($requestData['currencyCode'])) ? $requestData['currencyCode'] : '';
        $hotelSC                         = $this->get('HotelsServices')->getHotelSearchCriteria($requestData);

        return $this->get('HotelsServices')->hotelsAvailability($this->getHotelServiceConfig(), $hotelSC);
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

        $requestData = $this->request->query->all();
        $this->validateFetchedRequestData($requestData, $requirements);

        $roomCount = (int) $requestData['singleRooms'] + (int) $requestData['doubleRooms'];
        if ($roomCount <= 0) {
            throw new HttpException(403, $this->translator->trans("Invalid total room count."));
        }

        $requestData['hotelId']          = $hotelId;
        $requestData['selectedCurrency'] = (isset($requestData['currencyCode'])) ? $requestData['currencyCode'] : '';
        $requestData['infoSource']       = $this->infoSource;

        $hotelSC = $this->get('HotelsServices')->getHotelSearchCriteria($requestData);

        if (!$this->get('HotelsServices')->validateDates($hotelSC->getFromDate(), $hotelSC->getToDate(), 'offers')) {
            throw new HttpException(400, $this->translator->trans("Invalid Check-In/Check-Out date."));
        }

        return $this->get('HotelsServices')->hotelOffers($this->getHotelServiceConfig(), $hotelSC);
    }

    /**
     * Method POST
     * This method returns the complete pricing info of the selected offers for booking.
     *
     * @return data
     */
    public function completePricingInfoAction($hotelId)
    {
        // specify required fields
        $requirements = array(
            array('name' => 'session', 'required' => true, 'type' => 'array'),
            array('name' => 'requestSegment', 'required' => true, 'type' => 'array'),
            array('name' => 'selectedOffers', 'required' => true, 'type' => 'array'),
            array('name' => 'fromDate', 'required' => true, 'type' => 'string', 'constraints' => array('date' => 1)),
            array('name' => 'toDate', 'required' => true, 'type' => 'string', 'constraints' => array('date' => 1)),
            array('name' => 'isDistribution', 'required' => true),
        );

        // fetch post json data
        $requestData            = $this->fetchRequestData($requirements);
        $requestData['hotelId'] = $hotelId;
        $requestData['userId']  = $this->userGetID();

        $selectedOffers = $requestData['selectedOffers'];

        $pricingCriteria = array(
            'userId' => $requestData['userId'],
            'fromDate' => $requestData['fromDate'],
            'toDate' => $requestData['toDate'],
            'hotelId' => $requestData['hotelId'],
            'session' => json_encode($requestData['session']),
            'availRequestSegment' => json_encode($requestData['requestSegment']),
            'totalNumOffers' => count($selectedOffers),
            'gds' => $requestData['isDistribution'],
        );

        $counter = 1;
        foreach ($selectedOffers as $offer) {
            $pricingCriteria['offer_select_'.$counter] = $offer['quantity'];
            $pricingCriteria['bookableInfo_'.$counter] = json_encode($offer['bookableInfo']);
            $counter++;
        }

        $error = $this->get('HotelsServices')->validateBookingRequest($pricingCriteria);
        if (!empty($error)) {
            throw new HttpException(400, $error);
        }

        $pricing = $this->get('HotelsServices')->hotelEnhancedPricing('book', $this->get('HotelsServices')->getHotelBookingCriteria($pricingCriteria));
        if (isset($pricing['error'])) {
            return array('code' => 400, 'message' => 'Error encountered when retrieving selected offers complete pricing info: '.$pricing['error']);
        }

        $returnData                       = array();
        $returnData['reference']          = bin2hex(openssl_random_pseudo_bytes(16)); //used in web/email URLs
        $returnData['hotelCode']          = $pricing['hotelDetails']->getHotelCode();
        $returnData['isPrepaid']          = $pricing['prepaidIndicator'];
        $returnData['isGroup']            = $pricing['groupSell'];
        $returnData['isDistribution']     = $pricing['gds'];
        $returnData['session']            = $pricing['session'];
        $returnData['requestSegment']     = $pricing['requestSegment'];
        $returnData['reservationDetails'] = $pricing['reservationDetails'];
        $returnData['selectedOffers']     = $pricing['offersSelected'];

        return $returnData;
    }

    /**
     * Method POST
     * This method books a given hotel room(s).
     *
     * @return data
     */
    public function bookAction($hotelId)
    {
        $error = '';

        // specify required fields
        $requirements = array(
            array('name' => 'session', 'required' => true, 'type' => 'array'),
            array('name' => 'requestSegment', 'required' => true, 'type' => 'array'),
            array('name' => 'selectedOffers', 'required' => true, 'type' => 'array'),
            array('name' => 'reservationDetails', 'required' => true, 'type' => 'array'),
            array('name' => 'hotelCode', 'required' => true, 'type' => 'string'),
            array('name' => 'isPrepaid', 'required' => true),
            array('name' => 'isGroup', 'required' => true),
            array('name' => 'isDistribution', 'required' => true),
            array('name' => 'reference', 'required' => true, 'type' => 'string'),
            array('name' => 'fromDate', 'required' => true, 'type' => 'string', 'constraints' => array('date' => 1)),
            array('name' => 'toDate', 'required' => true, 'type' => 'string', 'constraints' => array('date' => 1)),
            array('name' => 'title', 'required' => true, 'type' => 'integer'),
            array('name' => 'firstName', 'required' => true, 'type' => 'string'),
            array('name' => 'lastName', 'required' => true, 'type' => 'string'),
            array('name' => 'email', 'required' => true, 'type' => 'string', 'constraints' => array('email' => 1)),
            array('name' => 'country', 'required' => true, 'type' => 'string'),
            array('name' => 'mobileCountryCode', 'required' => true, 'type' => 'string'),
            array('name' => 'mobile', 'required' => true, 'type' => 'string'),
            array('name' => 'guestFirstName', 'required' => true, 'type' => 'array'),
            array('name' => 'guestLastName', 'required' => true, 'type' => 'array'),
            array('name' => 'guestEmail', 'required' => true, 'type' => 'array'),
            array('name' => 'childAge', 'required' => true, 'nullable' => true, 'type' => 'array'),
            array('name' => 'childFirstName', 'required' => true, 'nullable' => true, 'type' => 'array'),
            array('name' => 'childLastName', 'required' => true, 'nullable' => true, 'type' => 'array')
        );

        // fetch post json data
        $requestData = $this->fetchRequestData($requirements);

        if (!$requestData['isPrepaid']) {
            $requirements = array(
                array('name' => 'ccType', 'required' => true),
                array('name' => 'ccCardHolder', 'required' => true),
                array('name' => 'ccNumber', 'required' => true),
                array('name' => 'ccExpiryMonth', 'required' => true),
                array('name' => 'ccExpiryYear', 'required' => true),
                array('name' => 'ccCVC', 'required' => true),
            );
            $this->validateFetchedRequestData($requestData, $requirements);
        }

        for ($key = 0; $key < count($requestData['selectedOffers']); $key++) {
            if (!isset($requestData['guestFirstName'][$key]) || empty($requestData['guestFirstName'][$key]) || !isset($requestData['guestLastName'][$key]) || empty($requestData['guestLastName'][$key])) {
                return array("code" => 400, "message" => $this->translator->trans("Invalid guest params"));
            }
        }

        if (!empty($requestData['childAge']) && (empty($requestData['childFirstName']) || empty($requestData['childFirstName']))) {
            return array("code" => 400, "message" => $this->translator->trans("Invalid child guest params"));
        }

        $requestData['hotelId']             = $hotelId;
        $requestData['userId']              = $this->userGetID();
        $requestData['infoSource']          = $this->infoSource;
        $requestData['transactionSourceId'] = $this->transactionSourceId;

        $hotelReservation = $this->get('HotelsServices')->getHotelReservationRecord($requestData);

        // Step 1: Save to DB
        if (empty($hotelReservation)) {
            $hotelFromDB = $this->get('HotelsServices')->getHotelObject($requestData['hotelId']);
            $hotelSource = $this->getDoctrine()->getRepository('HotelBundle:AmadeusHotelSource')->getHotelBySourceIdentifier('hotelCode', $requestData['hotelCode'], $requestData['hotelCode']);

            $requestData['remarks']             = array('reservationWish' => '');
            $requestData['chainCode']           = $hotelSource->getChain();
            $requestData['hotelCityCode']       = $hotelFromDB->getCity()->getCode();
            $requestData['session']             = json_encode($requestData['session']);
            $requestData['availRequestSegment'] = json_encode($requestData['requestSegment']);
            $requestData['reservationDetails']  = json_encode($requestData['reservationDetails']);
            $requestData['offersSelected']      = json_encode($requestData['selectedOffers']);
            $requestData['prepaidIndicator']    = $requestData['isPrepaid'];
            $requestData['groupSell']           = $requestData['isGroup'];
            $requestData['gds']                 = $requestData['isDistribution'];
            $requestData['reference']           = $requestData['reference'];

            $hotelBC = $this->get('HotelsServices')->getHotelBookingCriteria($requestData);

            list($error, $hotelReservation, ) = $this->get('HotelsServices')->saveReservationRequest($this->getHotelServiceConfig(), $hotelBC);

            if (!$error) {
                if ($hotelBC->isPrepaid()) {
                    return array(
                        "success" => true,
                        "message" => 'needs_cc_payment',
                        "transaction_id" => $hotelReservation->getPaymentUUID(),
                        "reservation_id" => $hotelReservation->getId(),
                        "module_id" => $this->container->getParameter('MODULE_HOTELS'),
                        "amount" => $hotelReservation->getHotelGrandTotal(),
                        "currency" => $hotelReservation->getHotelCurrency()
                    );
                } else {
                    $this->em = $this->getDoctrine()->getManager();
                    $hotelReservation->setReservationStatus($this->container->getParameter('hotels')['reservation_payment_completed']);
                    $this->em->persist($hotelReservation);
                    $this->em->flush();
                }
            }
        } else if ($this->transactionSourceId != $hotelReservation->getTransactionSourceId()) {
            $error = $this->translator->trans("Invalid reservation transaction source.");
        }

        if ($error) {
            return array("code" => 400, "message" => $error);
        }

        // send to reservation
        return $this->forwardToRoute('_rest_hotels_reservation', array('reference' => $requestData['reference']));
    }

    /**
     * This method is the landing page to finalize hotel booking. Asks for guest and payment details.
     *
     * @return Redirect as per transaction source
     */
    public function paymentSuccessAction()
    {
        $requestData = $this->get("request")->query->all();

        $this->logger->addHotelActivityLog('HOTELS', 'payment', $this->userGetID(), $requestData);

        $hotelReservation = $this->get('HotelsServices')->getHotelReservationRecord($requestData);

        if (!$hotelReservation) {
            $error['error'] = $this->translator->trans("Reservation request not found.");
            // when hotel reservation record is not found, check if successful payment
            // if yes, refund payment
            $error['error'] .= $this->get('HotelsServices')->refundPayment($hotelReservation);

            return array('message' => $error['error']);
        }

        $this->get('HotelsServices')->checkPayment($hotelReservation);

        $routes = $this->container->get('router')->getRouteCollection();

        if ($hotelReservation->getTransactionSourceId() == $this->container->getParameter('CORPORATE_REFERRER')) {
            $path['_controller'] = $routes->get('_rest_hotels_corpo_reservation')->getDefaults()['_controller'];
            return $this->forward($path['_controller'], array(), $requestData);
        } else {
            $path['_controller'] = $routes->get('_rest_hotels_reservation')->getDefaults()['_controller'];
            return $this->forward($path['_controller'], array(), $requestData);
        }
    }

    /**
     * This method handles the callBack from PaymentBundle when a payment fails
     *
     * @return Redirect or render reservation as per transaction source
     */
    public function paymentFailedAction()
    {
        $requestData = $this->get("request")->query->all();

        $this->logger->addHotelActivityLog('HOTELS', 'payment', $this->userGetID(), $requestData);

        $hotelReservation = $this->get('HotelsServices')->getHotelReservationRecord($requestData);

        $error = $this->get('HotelsServices')->checkPayment($hotelReservation);

        if ($hotelReservation->getTransactionSourceId() == $this->container->getParameter('CORPORATE_REFERRER')) {
            $routes              = $this->container->get('router')->getRouteCollection();
            $path['_controller'] = $routes->get('_rest_hotels_corpo_reservation')->getDefaults()['_controller'];
            return $this->forward($path['_controller'], array(), $requestData);
        } else {
            return array('message' => $error);
        }
    }

    /**
     * This method sends booking request from hotel book page of the corporate hotels section
     *
     * @return
     */
    public function reservationAction()
    {
        $requestData   = $this->get("request")->query->all();
        $reference     = (isset($requestData['reference'])) ? $requestData['reference'] : '';
        $transactionId = $this->get("request")->query->get('transaction_id', '');

        if (empty($reference) && empty($transactionId)) {
            return array("code" => 403, "message" => $this->translator->trans("Missing parameters"));
        }

        $hotelReservation = $this->get('HotelsServices')->getHotelReservationRecord($requestData);

        if (empty($hotelReservation)) {
            return array("code" => 400, "message" => $this->translator->trans("Reservation request not found"));
        } else if ($this->transactionSourceId != $hotelReservation->getTransactionSourceId()) {
            return array("code" => 400, "message" => $this->translator->trans("Invalid reservation transaction source."));
        }

        // Step 2, 3 & 4
        $serviceConfig = $this->getHotelServiceConfig();
        $serviceConfig->setPage('reservation');
        $serviceConfig->setRoutes(array(
            'hotelDetailsRouteName' => '_hotel_details_tt',
            'bookingDetailsRouteName' => '_booking_details_tt'));
        $serviceConfig->setTemplates(array('confirmationEmailTemplate' => '@Hotel/hotel-confirmation-email.twig'));

        $reservationInfo = $this->get('HotelsServices')->processHotelReservationRequest($serviceConfig, $hotelReservation);
        $reservationInfo = $this->get('HotelsServices')->getRestBookingDetailsData($reservationInfo);

        if (!$hotelReservation->getPaymentUUID()) {
            $response = new RestBookingResponseVO;
            $response->setSuccess(true);
            $response->setMessage("success");

            if (isset($reservationInfo['code']) && $reservationInfo['code'] == 400) {
                $response->setSuccess(false);
                $response->setMessage($reservationInfo['message']);
            } else {
                $response->setHotelsBookingVO($reservationInfo);
            }
            return $response;
        } else {
            return $reservationInfo;
        }
    }

    /**
     * Method GET
     * This method retrieves the details of a booking.
     *
     * @param string $reference
     *
     * @return
     */
    public function bookingDetailsAction($reference)
    {
        $response = new RestBookingResponseVO;
        $response->setSuccess(true);
        $response->setMessage("success");

        if (!empty($reference)) {
            $serviceConfig = $this->getHotelServiceConfig();
            $serviceConfig->setPage('booking_details');

            $reservationInfo = $this->get('HotelsServices')->bookingDetails($serviceConfig, $reference);
            $reservationInfo = $this->get('HotelsServices')->getRestBookingDetailsData($reservationInfo);

            if (isset($reservationInfo['code']) && $reservationInfo['code'] == 400) {
                $response->setSuccess(false);
                $response->setMessage($reservationInfo['message']);
            } else {
                $response->setHotelsBookingVO($reservationInfo);
            }
        }
        return $response;
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
        $requestData = $this->fetchRequestData($requirements);

        $request = new \HotelBundle\Model\HotelCancellationForm();
        $request->setReference($requestData['reference']);

        $this->get('HotelsServices')->cancelReservation($request);

        return $this->bookingDetailsAction($requestData['reference']);
    }

    /**
     * This method will be called by our TT Rest API. It will return the hotel divisions and available media if any.
     *
     * @param integer  $hotelId
     * @param integer  $categoryId
     * @param integer  $divisionId
     * @param boolean  $withSubDivisions
     * @return object  $results response
     */
    public function divisionsAction($hotelId)
    {
        $request = Request::createFromGlobals();

        $categoryId       = $request->get('categoryId');
        $divisionId       = $request->get('divisionId');
        $withSubDivisions = $request->get('withSubDivisions');

        $results = $this->get('HotelsServices')->getHotelDivisions($hotelId, $categoryId, $divisionId, $withSubDivisions);

        if ($results) {
            return $results;
        } else {
            $response = new Response();
            $response->setStatusCode(204, $this->translator->trans("No data found."));
            return $response;
        }
    }
}
