<?php

namespace CorporateBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class HotelsCorpoController extends CorporateController
{
    private $infoSource;
    private $transactionSourceId;
    private $pageSrc;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->infoSource          = $this->container->getParameter('modules')['hotels']['vendors']['amadeus']['infosource']['leisure'];
        $this->transactionSourceId = $this->container->getParameter('CORPORATE_REFERRER');
        $this->pageSrc             = $this->container->getParameter('hotels')['page_src']['corpo'];
        $this->em                  = $this->getDoctrine()->getManager();
        $this->request             = Request::createFromGlobals();

        $this->initRoutePathsAndOtherData();
    }

    /**
     * This method initializes HotelServiceConfig object to be used when calling a service.
     * @return \HotelBundle\Model\HotelServiceConfig
     */
    private function getHotelServiceConfig()
    {
        $hotelServiceConfig = new \HotelBundle\Model\HotelServiceConfig();
        $hotelServiceConfig->setUseTTApi(true);
        $hotelServiceConfig->setTransactionSourceId($this->transactionSourceId);
        $hotelServiceConfig->setUserAgent($this->request->headers->get('User-Agent'));
        $hotelServiceConfig->setPrepaidOnly(true);
        $hotelServiceConfig->setPageSrc($this->pageSrc);

        return $hotelServiceConfig;
    }

    /**
     * This method initializes necessary route paths and route names that is used by our twig/js/etc.
     */
    private function initRoutePathsAndOtherData()
    {
        $this->data['route_names'] = array(
            'hotel_booking_results' => '_corporate_hotel_search_results'
        );

        $this->data['route_paths'] = array(
            'hotel_avail' => $this->get('router')->getRouteCollection()->get('_corporate_hotel_avail')->getPath(),
            'hotel_details' => $this->get('router')->getRouteCollection()->get('_corporate_hotel_details')->getPath(),
            'hotel_offers' => $this->get('router')->getRouteCollection()->get('_corporate_hotel_offers')->getPath(),
        );
    }

    /**
     * This method prepares the landing page of the corporate hotels section
     *
     * @return Renders the landing page twig
     */
    public function hotelSearchAction()
    {
        $this->data['pageSrc'] = $this->pageSrc;

        //uglify assets
        $this->data['uglifyHotelBookingAssets'] = 1;

        $this->data['hotelblocksearchIndex']  = 1;
        $this->data['hideblocksearchButtons'] = 1;

        $this->data['page']  = 'search';
        $this->data['input'] = array();

        // When reservationId is present (from book again this hotel link), either populate the search form (from the data in hotel_reservation) or redirect to the hotel detail page
        $reservationId = $this->request->query->get('reservationId');

        if (!empty($reservationId)) {
            $criteria = $this->get('HotelsServices')->getHotelDetailsSearchCriteriaByReservationId($reservationId);

            $this->data['input'] = array(
                'city' => array(
                    'name' => $criteria->getCity()->getName()
                ),
                'hotelId' => $criteria->getHotelId(),
                'singleRooms' => $criteria->getSingleRooms(),
                'doubleRooms' => $criteria->getDoubleRooms(),
                'entityType' => $this->container->getParameter('SOCIAL_ENTITY_HOTEL')
            );

            if ($criteria->isPublished()) {
                return $this->redirectToLangRoute('_corporate_hotel_details', array('name' => $criteria->getHotelNameURL(), 'id' => $criteria->getHotelId()));
            }
        }

        return $this->render('@Corporate/hotels/hotel-search.twig', $this->data);
    }

    /**
     * This method prepares the listing page of the corporate hotels section
     *
     * @return Renders the listing page twig
     */
    public function hotelBookingResultsAction()
    {
        $this->data['pageSrc']                = $this->pageSrc;
        $this->data['hotelblocksearchIndex']  = 1;
        $this->data['hideblocksearchButtons'] = 1;
        $this->data['bookingresults']         = true;
        $this->data['hotelupdatesearchIndex'] = 1;
        $this->data['pageName']               = "hotel-booking-results";

        //uglify assets
        $this->data['uglifyHotelBookingResultsAssets'] = 1;

        $hotelSC = $this->get('HotelsServices')->getHotelSearchCriteria($this->request->query->all());

        $this->data['input'] = $hotelSC->toArray();

        if (empty($hotelSC->getCity()->getId()) && empty($hotelSC->getHotelId()) && empty($hotelSC->getCountry()) && empty($hotelSC->getLongitude()) && empty($hotelSC->getLatitude())) {
            // Direct URL access without params
            $this->data['error'] = $this->translator->trans("Please select your destination.");
        } elseif (!$this->get('HotelsServices')->validateDates($hotelSC->getFromDate(), $hotelSC->getToDate(), 'avail')) {
            $this->data['error'] = $this->translator->trans("Invalid Check-In/Check-Out date.");
        } elseif ($this->get('app.utils')->computeNights($hotelSC->getFromDate(), $hotelSC->getToDate()) > $this->container->getParameter('hotels')['reservation_max_nights']) {
            $action_array        = array();
            $action_array[]      = $this->container->getParameter('hotels')['reservation_max_nights'];
            $ms                  = vsprintf($this->translator->trans("Reservations longer than %s nights are not possible."), $action_array);
            $this->data['error'] = $ms;
        }

        if (!isset($this->data['error'])) {
            $this->data['districts'] = $this->get('HotelsServices')->getHotelDistricts($hotelSC);
        }

        return $this->render('@Corporate/hotels/hotel-booking-results.twig', $this->data);
    }

    /**
     * This method retrieves available hotels based on the search criteria hotels section of the listing page of the corporate hotels section
     *
     * @return JSON encoded data which contains list of available hotels
     */
    public function hotelAvailAction()
    {
        $requestData               = array_merge($this->request->request->all(), $this->request->query->all());
        $requestData['infoSource'] = $this->infoSource;
        $hotelSC                   = $this->get('HotelsServices')->getHotelSearchCriteria($requestData);

        $hotelServiceConfig = $this->getHotelServiceConfig();
        $hotelServiceConfig->setTemplates(array(
            'mainLoopTemplate' => '@Corporate/hotels/hotel-booking-results-main-loop.twig',
            'paginationTemplate' => '@Corporate/hotels/hotel-booking-results-pagination.twig',
        ));

        return $this->get('HotelsServices')->hotelsAvailability($hotelServiceConfig, $hotelSC);
    }

    /**
     * This method prepares the hotel details page of the corporate hotels section
     *
     * @return Renders the hotel details page twig
     */
    public function hotelDetailsAction($name, $id)
    {
        $this->data['pageSrc'] = $this->pageSrc;

        $this->data['hotelblocksearchIndex']  = 1;
        $this->data['hideblocksearchButtons'] = 1;
        $this->data['pageName']               = "hotel-details";

        $this->data['showHeaderSearch'] = 0;

        //uglify assets
        $this->data['uglifyHotelDetailsAssets'] = 1;

        $request = array_merge($this->request->request->all(), $this->request->query->all());
        if (sizeof($request) > 0) {
            $this->data['hotelupdatesearchIndex'] = 1;
        } else {
            $this->data['hotelupdatesearchIndex'] = 0;
        }

        // If name is empty in URL, both {name} and {id} become considered as one variable inside {name}, fetch name and id correctly
        if (!$id && $name) {
            list($name, $id) = explode('-', $name);
        }

        $request['hotelId']   = ($id) ? $id : $request['hotelId'];
        $request['hotelName'] = ($name) ? $name : $request['hotelNameURL'];
        $hotelSC              = $this->get('HotelsServices')->getHotelSearchCriteria($request);

        $hotelServiceConfig = $this->getHotelServiceConfig();
        $hotelServiceConfig->setRoutes(array(
            'refererURL' => $this->generateLangRoute('_corporate_hotel_details', $request), // Provide url to restart when session gets expired
        ));

        $resultArray = $this->get('HotelsServices')->hotelDetails($hotelServiceConfig, $hotelSC);
        $this->data  = array_merge($this->data, $resultArray);

        //Put page title the hotel name
        $this->data['pageTitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($request['hotelName']);

        if (isset($request['msg'])) {
            $this->data['msg'] = $request['msg'];
        }

        return $this->render('@Corporate/hotels/hotel-details.twig', $this->data);
    }

    /**
     * This method retrieves room offers from the given hotel for the offers section of the hotel details page of the corporate hotels section
     *
     * @return JSON encoded data which contains hotel information and offers
     */
    public function hotelOffersAction($name, $id)
    {
        $request = $this->request->request->all();

        // If name is empty in URL, both {name} and {id} become considered as one variable inside {name}, fetch name and id correctly
        if (!$id) {
            list($name, $id) = explode('-', $name);
        }

        $request['hotelId']    = ($id) ? $id : $request['hotelId'];
        $request['hotelName']  = ($name) ? $name : $request['hotelNameURL'];
        $request['infoSource'] = $this->infoSource;
        $hotelSC               = $this->get('HotelsServices')->getHotelSearchCriteria($request);

        $hotelServiceConfig = $this->getHotelServiceConfig();
        $hotelServiceConfig->addRoute('refererURL', $request['refererURL']); // Provide url to restart when session gets expired
        $hotelServiceConfig->setTemplates(array(
            'offersLoopTemplate' => '@Corporate/hotels/hotel-details-offers.twig',
            'hotelAmenitiesTemplate' => '@Corporate/hotels/hotel-amenities.twig',
            'hotelFacilitiesTemplate' => '@Corporate/hotels/hotel-facilities.twig',
            'hotelDistancesTemplate' => '@Corporate/hotels/hotel-distances.twig',
            'hotelCreditCardsTemplate' => '@Corporate/hotels/hotel-credit-cards.twig',
            'roomGalleryTemplate' => '@Corporate/hotels/hotel-room-gallery.twig',
        ));

        return $this->get('HotelsServices')->hotelOffers($hotelServiceConfig, $hotelSC);
    }

    /**
     * This method prepares the hotel book page of the corporate hotels section
     *
     * @return Renders the hotel book page twig
     */
    public function hotelBookAction()
    {
        $this->data['pageSrc'] = $this->pageSrc;

        //uglify assets
        $this->data['uglifyHotelBookAssets'] = 1;

        $requestData = $this->request->request->all();

        if (!$this->get('app.utils')->isCorporateSite()) {
            $error = $this->translator->trans("Corporate booking must be done on corporate domain.");
        } else if (!isset($requestData['fromDate'])) {
            return $this->redirectToLangRoute('_corporate_hotel_search');
        }

        $this->data['showHeaderSearch'] = 0;

        if (empty($error)) {
            $error = $this->get('HotelsServices')->validateBookingRequest($requestData);
        }

        if (!empty($error)) {
            $this->data['error'] = $error;
        } else {
            $requestData['userId'] = intval($this->userGetID());

            $hotelBC     = $this->get('HotelsServices')->getHotelBookingCriteria($requestData);
            $resultArray = $this->get('HotelsServices')->hotelBook($this->getHotelServiceConfig(), $hotelBC);

            if (isset($resultArray['offersSelected']) && !empty($resultArray['offersSelected'])) {
                $this->data = array_merge($this->data, $resultArray);
            } elseif (isset($resultArray['error'])) {
                $this->data['error']      = $resultArray['error'];
                $this->data['errorCode']  = $resultArray['errorCode'];
                $this->data['refererURL'] = $resultArray['refererURL'];
            }
        }

        return $this->render('@Corporate/hotels/hotel-book.twig', $this->data);
    }

    /**
     * This method sends booking request from hotel book page of the corporate hotels section
     *
     * @return
     */
    public function hotelReservationAction()
    {
        $this->data['pageSrc']                 = $this->pageSrc;
        $this->data['hotelblocksearchIndex']   = 1;
        $this->data['hideblocksearchButtons']  = 1;
        $this->data['hotelBookingRouteName']   = '_corporate_hotel_search';
        $this->data['hotelDetailsRouteName']   = '_corporate_hotel_details';
        $this->data['bookingDetailsRouteName'] = '_corporate_booking_details';
        $this->data['cancellationRouteName']   = '_corporate_hotel_reservation_cancellation';
        $this->data['roomInfoTemplate']        = '@Corporate/hotels/hotel-room-offer-info.twig';

        //uglify assets
        $this->data['uglifyHotelReservationAssets'] = 1;

        $error           = '';
        $reservationInfo = null;

        $requestData   = array_merge($this->request->request->all(), $this->request->query->all());
        $reference     = (isset($requestData['reference'])) ? $requestData['reference'] : '';
        $transactionId = $this->request->query->get('transaction_id', '');

        if (!$this->get('app.utils')->isCorporateSite()) {
            $error = $this->translator->trans("Corporate booking must be done on corporate domain.");
        } else if (empty($reference) && empty($transactionId)) {
            return $this->redirectToLangRoute('_corporate_hotel_search');
        }

        $serviceConfig = $this->getHotelServiceConfig();

        $hotelReservation = $this->get('HotelsServices')->getHotelReservationRecord($requestData);

        // Step 1: Save to DB
        $otpVerification = 0;
        if (empty($hotelReservation)) {
            $requestData['infoSource']          = $this->infoSource;
            $requestData['transactionSourceId'] = $this->transactionSourceId;
            $requestData['userId']              = intval($this->userGetID());

            $hotelBC = $this->get('HotelsServices')->getHotelBookingCriteria($requestData);

            list($error, $hotelReservation) = $this->get('HotelsServices')->saveReservationRequest($serviceConfig, $hotelBC);

            $otpVerification = 1;
        } else if ($this->transactionSourceId != $hotelReservation->getTransactionSourceId()) {
            $error = $this->translator->trans("Invalid reservation transaction source.");
        }

        // Step 2: Send OTP Verification
        if (empty($error) && $otpVerification == 1) {
            return $this->redirectToLangRoute('otpRoute', array('module_id' => $this->container->getParameter('MODULE_HOTELS'), 'reservation_id' => $hotelReservation->getId(), 'user_id' => intval($this->userGetID())));
        }

        if ($hotelReservation->getReservationStatus() == $this->container->getParameter('hotels')['reservation_pending_approval']) {
            return $this->redirectDynamicRoute('_corporate_account_waiting_approval');
        }

        // Complete reservation request
        if (empty($error)) {
            $serviceConfig->setPage('reservation');
            $serviceConfig->setRoutes(array(
                'hotelDetailsRouteName' => $this->data['hotelDetailsRouteName'],
                'bookingDetailsRouteName' => $this->data['bookingDetailsRouteName']));
            $serviceConfig->setTemplates(array('confirmationEmailTemplate' => '@Corporate/hotels/hotel-confirmation-email.twig'));

            $reservationInfo = $this->get('HotelsServices')->processHotelReservationRequest($serviceConfig, $hotelReservation);

            if (isset($reservationInfo['error'])) {
                $error = $reservationInfo['error'];
            } else {
                $this->data = array_merge($this->data, $reservationInfo);
            }
        }

        $this->data['hideAddBag'] = ($this->data['isUserLoggedIn']) ? 0 : 1;
        $this->data['error']      = $error;

        // Whatever error is encountered, we redirect them back to details page
        $this->data['refererURL'] = (isset($requestData['refererURL'])) ? $requestData['refererURL'] : $reservationInfo['refererURL'];

        return $this->render('@Corporate/hotels/hotel-reservation.twig', $this->data);
    }

    /**
     *  Proceed Payment after OTP Pin verification
     *
     * @param integer $module_id
     * @param integer $reservation_id
     * @param integer $user_id
     *
     * @return redirect
     */
    public function hotelProceedPaymentAction($module_id, $reservation_id, $user_id)
    {
        if ($module_id == $this->container->getParameter('MODULE_HOTELS')) {
            return $this->processCorporateBookingPayment($reservation_id, $user_id);
        }
    }

    /**
     * This method process the payment of corporate booking
     *
     * @param HotelReservation $hotelReservation
     * @param Integer $transactionUserId
     * @return Array
     */
    private function processCorporateBookingPayment($reservationId, $userId, $transactionUserId = null)
    {
        $hotelReservation = $this->em->getRepository('HotelBundle:HotelReservation')->findOneById($reservationId);

        $moduleId  = $this->container->getParameter('MODULE_HOTELS');
        $userAgent = $this->request->headers->get('User-Agent');

        //Get the logged in user data
        $loggedInSessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $accountId           = $loggedInSessionInfo['accountId'];

        // Get the account default payment type
        $accountPaymentType = $this->get('CorpoAccountServices')->getCorpoAccountPaymentType($accountId);

        // Initialize payment
        $paymentObj = $this->get('HotelsServices')->getPaymentObject($hotelReservation, $accountPaymentType['code'], $userAgent);

        // check if we have pending request service details for this reservation
        $crsResult = $this->get('CorpoAdminServices')->getPendingRequestDetailsId(array('reservationId' => $hotelReservation->getId(), 'moduleId' => $moduleId));
        $result    = array();
        if (empty($crsResult)) {
            $result = $this->paymentApproval($paymentObj, $moduleId);
        } else {
            // approver check
            if ($this->get('CorpoApprovalFlowServices')->allowedToApproveForUser($userId, $transactionUserId, $accountId)) {
                $payInit = $this->get('PaymentServiceImpl')->initializePayment($paymentObj);

                $result["callback_url"]   = $payInit->getCallBackUrl();
                $result["transaction_id"] = $payInit->getTransactionId();
            } else {
                $result["callback_url"] = "_corporate_account_waiting_approval";
            }
        }

        $transactionId = $this->get('HotelsServices')->updateCorporateReservationPaymentInfo($hotelReservation, $result);

        $msg = null;
        if (isset($result["params"]) && isset($result["params"]["msg"]) && $result["params"]["msg"] != '') $msg = $result["params"]["msg"];

        // Send to payment
        return $this->redirectDynamicRoute($result["callback_url"], array('transaction_id' => $transactionId, 'msg' => $msg));
    }

    /**
     * This method prepares the booking details page
     *
     * @return Renders the booking details twig
     */
    public function bookingDetailsAction($reference)
    {
        //uglify assets
        $this->data['uglifyHotelBookingDetailsAssets'] = 1;

        $this->data['page'] = 'booking_details';

        $serviceConfig = $this->getHotelServiceConfig();
        $serviceConfig->setPage('booking_details');

        $reservationInfo = $this->get('HotelsServices')->bookingDetails($serviceConfig, $reference);
        $this->data      = array_merge($this->data, $reservationInfo);

        $this->data['pageSrc']               = $this->pageSrc;
        $this->data['hotelDetailsRouteName'] = '_corporate_hotel_details';
        $this->data['cancellationRouteName'] = '_corporate_hotel_reservation_cancellation';
        $this->data['roomInfoTemplate']      = '@Corporate/hotels/hotel-room-offer-info.twig';

        return $this->render('@Corporate/hotels/hotel-booking-details.twig', $this->data);
    }

    /**
     * This method calls the cancellation API and renders the appropriate twig
     *
     * @return
     */
    public function hotelReservationCancellationAction($reference)
    {
        //uglify assets
        $this->data['uglifyHotelCancelConfirmationAssets'] = 1;

        $request = new \HotelBundle\Model\HotelCancellationForm();
        $request->setUserId(intval($this->userGetID()));
        $request->setReference($reference);

        $reservationKey = $this->request->request->get('reservationKey');
        $roomsToDisplay = $this->request->request->get('roomsToDisplay');

        if (!empty($reservationKey)) {
            if (!empty($roomsToDisplay)) {
                $rooms = json_decode($roomsToDisplay, true);
                foreach ($rooms as $item) {
                    $room = new \HotelBundle\Model\HotelRoomCancellationForm();
                    $room->setReservationKey($item['reservationKey']);
                    $room->setSegmentIdentifier($item['segmentIdentifier']);
                    $room->setSegmentNumber($item['segmentNumber']);

                    $request->addRoom($room);
                }
            }
        }

        $resultsArray = $this->get('HotelsServices')->cancelReservation($request, '@Corporate/hotels/hotel-cancellation-email.twig');

        $this->data               = array_merge($this->data, $resultsArray);
        $this->data['reference']  = $reference;
        $this->data['detailsURL'] = $this->generateLangRoute('_corporate_booking_details', array('reference' => $reference));

        return $this->render('@Corporate/hotels/hotel-cancel-confirmation.twig', $this->data);
    }

    /**
     * This method prepares the hotel reviews page of the corporate hotels section
     *
     * @return Renders the hotel reviews page twig
     */
    public function hotelReviewsAction($name, $id)
    {
        $this->data['pageSrc']                = $this->pageSrc;
        $this->data['hotelblocksearchIndex']  = 1;
        $this->data['hideblocksearchButtons'] = 1;

        //uglify assets
        $this->data['uglifyHotelReviewsAssets'] = 1;

        $hotelDetails = $this->get('HotelsServices')->getHotelInformation('reviews', $id, 0, '', null, $this->transactionSourceId);

        $request                  = array();
        $request['entityType']    = $this->container->getParameter('SOCIAL_ENTITY_HOTEL');
        $request['hotelId']       = $hotelDetails->getHotelId();
        $request['hotelCityCode'] = $hotelDetails->getCity()->getCode();
        $request['cityId']        = $hotelDetails->getCity()->getId();
        $request['hotelCityName'] = str_replace("+", " ", $hotelDetails->getName());
        $request['country']       = $hotelDetails->getCity()->getCountryName();
        $request['longitude']     = $hotelDetails->getLongitude();
        $request['latitude']      = $hotelDetails->getLatitude();
        //$request['nbrStars']      = $hotelDetails->getCategory();

        $this->data['input'] = $this->get('HotelsServices')->getHotelSearchCriteria($request)->toArray();

        $this->data['hotelDetails'] = $hotelDetails;
        $this->data['details_link'] = $this->get('HotelsServices')->returnHotelDetailedLink($hotelDetails->getName(), $hotelDetails->getHotelId(), $this->transactionSourceId);

        return $this->render('@Corporate/hotels/hotel-reviews.twig', $this->data);
    }

    /**
     * This method calls HotelsServices and checks the availability of the same exact room offer that was chosen. It then continues the flow as per the approval flow settings.
     *
     * @param integer $reservationId
     * @param integer $accountId
     * @param integer $userId
     * @param integer $transactionUserId
     * @param integer $requestServicesDetailsId
     *
     */
    public function checkReservationAvailabilityAction($reservationId, $accountId, $userId, $transactionUserId, $requestServicesDetailsId)
    {
        $requestInfo = $this->get('HotelsServices')->checkReservationRequestAvailability($reservationId, $userId);

        extract($requestInfo); // create variables $isAvailable, $reservation, $enhancedPricing

        $details = json_decode($reservation->getDetails(), true);

        //Check if still available.
        if ($isAvailable) {
            // Call the service to update the request status to approved.
            $parameters = array(
                'requestStatus' => $this->container->getParameter('CORPO_APPROVAL_APPROVED'),
                'reservationId' => $reservationId,
                'moduleId' => $this->container->getParameter('MODULE_HOTELS')
            );

            $this->get('CorpoApprovalFlowServices')->updatePendingRequestServices($parameters);

            // We also need to change the session on amadeus-details as well, since Hotel_Sell needs an active session.
            $details['session'] = $enhancedPricing['session'];

            // Update reservation status from 'PendingApproval' to 'RequestApproved'; $details having the updated session;  and continue booking process.
            $this->get('HotelsServices')->updateReservationStatus($reservationId, $this->container->getParameter('hotels')['reservation_request_approved'], $details);

            return $this->processCorporateBookingPayment($reservationId, $userId, $transactionUserId);
        } else {
            // Make sure reservation status is 'PendingApproval' before doing update to the request status
            if ($reservation->getReservationStatus() == $this->container->getParameter('hotels')['reservation_pending_approval']) {
                // Call the service to update the request status to expired.
                $parameters = array(
                    'requestStatus' => $this->container->getParameter('CORPO_APPROVAL_EXPIRED'),
                    'reservationId' => $reservationId,
                    'moduleId' => $this->container->getParameter('MODULE_HOTELS')
                );

                $this->get('CorpoApprovalFlowServices')->updatePendingRequestServices($parameters);

                // Update reservation status from 'PendingApproval' to 'ReservationFailed' and set details to null.
                $this->get('HotelsServices')->updateReservationStatus($reservationId, $this->container->getParameter('hotels')['reservation_failed'], NULL);
            }

            // Return to main search
            $msg         = $this->translator->trans('The selected room offer is not available anymore. If you wish, you can have a look at the below up-to-date room offers and continue.');
            $referrerURL = sprintf("%s&msg=%s", $details['refererURL'], $msg);
            return $this->redirectDynamicRoute($referrerURL);
        }
    }

    /**
     * This method calls HotelsServices to fetch the details and then renders a detailed twig about the reservation request including room details.
     *
     * @param integer $reservationId
     *
     * @return Renders the hotel reservation request twig
     */
    public function getReservationRequestDetailsAction($reservationId)
    {
        //uglify assets
        $this->data['uglifyHotelBookingDetailsAssets'] = 1;

        $reservationInfo = $this->get('HotelsServices')->getReservationRequestDetails($this->getHotelServiceConfig(), $reservationId);
        $this->data      = array_merge($this->data, $reservationInfo);

        return $this->render('@Corporate/hotels/hotel-reservation-request-details.twig', $this->data);
    }
}
