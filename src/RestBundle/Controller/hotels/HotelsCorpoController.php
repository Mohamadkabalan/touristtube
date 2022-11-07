<?php

namespace RestBundle\Controller\hotels;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RestBundle\Controller\TTRestController;

class HotelsCorpoController extends TTRestController
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
        $this->infoSource          = $this->container->getParameter('modules')['hotels']['vendors']['amadeus']['infosource']['leisure'];
        $this->transactionSourceId = $this->container->getParameter('CORPORATE_REFERRER');
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
        $hotelServiceConfig->setPrepaidOnly(true);
        $hotelServiceConfig->setUserAgent($this->request->headers->get('User-Agent'));
        $hotelServiceConfig->setTransactionSourceId($this->transactionSourceId);

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

        $requestData['infoSource'] = $this->infoSource;
        $hotelSC                   = $this->get('HotelsServices')->getHotelSearchCriteria($requestData);

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

        $requestData['hotelId']          = $hotelId;
        $requestData['selectedCurrency'] = (isset($requestData['currencyCode'])) ? $requestData['currencyCode'] : '';
        $requestData['infoSource']       = $this->infoSource;

        $hotelSC = $this->get('HotelsServices')->getHotelSearchCriteria($requestData);

        if (!$this->get('HotelsServices')->validateDates($hotelSC->getFromDate(), $hotelSC->getToDate(), 'offers')) {
            throw new HttpException(400, $this->translator->trans("Invalid Check-In/Check-Out date."));
        }

        $hotelServiceConfig = $this->getHotelServiceConfig();

        return $this->get('HotelsServices')->hotelOffers($hotelServiceConfig, $hotelSC);
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

        if (!$requestData['isPrepaid'] || $requestData['isDistribution']) {
            return array("code" => 400, "message" => $this->translator->trans("Invalid offers selected for booking"));
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
        $processPayment  = 0;
        $otpVerification = 0;
        if (empty($hotelReservation)) {
            $hotelSource = $this->getDoctrine()->getRepository('HotelBundle:AmadeusHotelSource')->getHotelBySourceIdentifier('hotelCode', $requestData['hotelCode'], $requestData['hotelCode']);

            $requestData['remarks']             = array('reservationWish' => '');
            $requestData['chainCode']           = $hotelSource->getChain();
            $requestData['hotelCityCode']       = substr($hotelSource->getHotelCode(), 2, 3);
            $requestData['session']             = json_encode($requestData['session']);
            $requestData['availRequestSegment'] = json_encode($requestData['requestSegment']);
            $requestData['reservationDetails']  = json_encode($requestData['reservationDetails']);
            $requestData['offersSelected']      = json_encode($requestData['selectedOffers']);
            $requestData['prepaidIndicator']    = $requestData['isPrepaid'];
            $requestData['groupSell']           = $requestData['isGroup'];
            $requestData['gds']                 = $requestData['isDistribution'];
            $requestData['reference']           = $requestData['reference'];

            $hotelBC = $this->get('HotelsServices')->getHotelBookingCriteria($requestData);

            list($error, $hotelReservation) = $this->get('HotelsServices')->saveReservationRequest($this->getHotelServiceConfig(), $hotelBC);

            $otpVerification = 1;
        }

        if (!empty($error)) {
            return array("code" => 400, "message" => $error);
        }

        if ($otpVerification || $hotelReservation->getReservationStatus() == $this->container->getParameter('hotels')['reservation_pending_payment']) {
            $paymentMethod = $this->checkPaymentType($hotelReservation->getHotelGrandTotal(), $hotelReservation->getHotelCurrency());

            if ($paymentMethod['otp']) {
                $paymentMethod['reservationId'] = $hotelReservation->getId();
                $paymentMethod['moduleId']      = $this->container->getParameter('MODULE_HOTELS');
                return $paymentMethod;
            } else {
                $processPayment = 1;
            }
        }

        if (!$error && $processPayment === 1) {
            $transactionUserId = $hotelReservation->getUserId();
            return $this->processCorporateBookingPayment($hotelReservation, $transactionUserId);
        }

        // send to reservation
        return $this->forwardToRoute('_rest_hotels_corpo_reservation', array('reference' => $requestData['reference']));
    }

    /**
     *  Proceed Payment after OTP Pin verification
     *
     * @param integer $reservationId
     *
     * @return redirect
     */
    public function hotelProceedPaymentAction($reservationId)
    {
        $hotelReservation = $this->get('HotelsServices')->getHotelReservationRecord(array('reservationId' => $reservationId));
        if (!empty($hotelReservation)) {
            if ($hotelReservation->getReservationStatus() == $this->container->getParameter('hotels')['reservation_pending_payment']) {
                return $this->processCorporateBookingPayment($hotelReservation, $hotelReservation->getUserId());
            } else {
                return $this->forwardToRoute('_rest_hotels_corpo_reservation', array('reference' => $hotelReservation->getReference()));
            }
        } else {
            return array("code" => 400, "message" => $this->translator->trans("Invalid reservation id."));
        }
    }

    /**
     * This method process the payment of corporate booking
     *
     * @param HotelReservation $hotelReservation
     * @param Integer $transactionUserId
     * @param Boolean $processCOA
     * @return Array
     */
    private function processCorporateBookingPayment($hotelReservation, $transactionUserId, $processCOA = true)
    {
        //Get the logged in user account id
        $user      = $this->get('security.token_storage')->getToken()->getUser();
        $accountId = $user->getCorpoAccountId();

        // Get the account default payment type
        $accountPaymentType = $this->container->get('CorpoAccountServices')->getCorpoAccountPaymentType($accountId);

        // Initialize payment
        $this->setIsRestCorporate(); // Set isRestCorporate to true to bypass Utils:isCorporateSite() when coming from Corporate REST

        $paymentObj = $this->get('HotelsServices')->getPaymentObject($hotelReservation, $accountPaymentType['code'], $this->request->headers->get('User-Agent'));
        $result     = $this->processPayment($paymentObj, $hotelReservation->getId(), $this->container->getParameter('MODULE_HOTELS'), $transactionUserId);

        $this->get('HotelsServices')->updateCorporateReservationPaymentInfo($hotelReservation, $result);

        $msg = null;
        if (isset($result["params"]) && isset($result["params"]["msg"]) && $result["params"]["msg"] != '') $msg = $result["params"]["msg"];

        if ($processCOA && $result['message'] == 'needs_coa_payment') {
            return $this->forwardToRoute($result['callback_url'], array('transaction_id' => $result['transaction_id']));
        }

        return $result;
    }

    /**
     * This method sends booking request from hotel book page of the corporate hotels section
     *
     * @return
     */
    public function reservationAction()
    {
        $requestData       = $this->get("request")->query->all();
        $reference         = (isset($requestData['reference'])) ? $requestData['reference'] : '';
        $transactionId     = $this->get("request")->query->get('transaction_id', '');
        $transactionUserId = $this->get("request")->query->get('transactionUserId', '');

        if (empty($reference) && empty($transactionId)) {
            throw new HttpException(403, $this->translator->trans("Missing parameters"));
        }

        $hotelReservation = $this->get('HotelsServices')->getHotelReservationRecord($requestData);

        if (empty($hotelReservation)) {
            throw new HttpException(400, $this->translator->trans("Reservation request not found"));
        } else if ($this->transactionSourceId != $hotelReservation->getTransactionSourceId()) {
            throw new HttpException(400, $this->translator->trans("Invalid reservation transaction source."));
        }

        if ($hotelReservation->getReservationStatus() == $this->container->getParameter('hotels')['reservation_request_approved']) {
            return $this->processCorporateBookingPayment($hotelReservation, $transactionUserId);
        }

        if ($hotelReservation->getReservationStatus() == $this->container->getParameter('hotels')['reservation_pending_approval']) {
            return array("statusCode" => $this->container->getParameter('hotels')['reservation_pending_approval'], "message" => $this->translator->trans("Thank you. You will be notified via email once your reservation request is approved."));
        }

        if ($hotelReservation->getReservationStatus() == $this->container->getParameter('hotels')['reservation_pending_payment']) {
            return array("statusCode" => $this->container->getParameter('hotels')['reservation_pending_payment'], "message" => $this->translator->trans("Thank you. We have received your reservation request. The request is awaiting your payment."));
        }

        // Step 2, 3 & 4
        $serviceConfig = $this->getHotelServiceConfig();
        $serviceConfig->setPage('reservation');
        $serviceConfig->setTemplates(array('confirmationEmailTemplate' => '@Corporate/hotels/hotel-confirmation-email.twig'));

        $reservationInfo = $this->get('HotelsServices')->processHotelReservationRequest($serviceConfig, $hotelReservation);
        $reservationInfo = $this->get('HotelsServices')->getRestBookingDetailsData($reservationInfo);

        return $reservationInfo;
    }

    /**
     * This method calls HotelsServices to fetch the reservation request details.
     *
     * @param integer $reservationId
     *
     * @return Return the reservation request details
     */
    public function viewReservationDetailsAction($reservationId)
    {
        $this->data['selected_currency'] = $this->get('HotelsServices')->getCurrency();

        $reservationInfo                 = array_merge($this->data, $this->get('HotelsServices')->getReservationRequestDetails($this->getHotelServiceConfig(), $reservationId));
        $reservationInfo['hotelDetails'] = $reservationInfo['hotelDetails']->toArray();

        return $reservationInfo;
    }

    /**
     * This method calls HotelsServices and checks the availability of the same exact room offer that was chosen. It then continues the flow as per the approval flow settings.
     *
     * @param integer $reservationId
     *
     */
    public function checkReservationAvailabilityAction($reservationId)
    {
        // specify required fields
        $requirements = array(
            array('name' => 'accountId', 'required' => false, 'type' => 'integer'),
            array('name' => 'userId', 'required' => false, 'type' => 'integer'),
            array('name' => 'transactionUserId', 'required' => true, 'type' => 'integer'),
            array('name' => 'requestServicesDetailsId', 'required' => false, 'type' => 'integer'),
        );

        // fetch post json data
        $requestData = $this->fetchRequestData($requirements);
        extract($requestData); // create variables for those posted json param (e.g. $accountId, $userId, $transactionUserId, $requestServicesDetailsId)

        $userId = $this->userGetID();

        $requestInfo = $this->get('HotelsServices')->checkReservationRequestAvailability($reservationId, $userId);
        extract($requestInfo); // create variables $isAvailable, $reservation, $enhancedPricing

        $details = json_decode($reservation->getDetails(), true);

        //Check if still available.
        if ($isAvailable) {
            $result = $this->processCorporateBookingPayment($reservation, $transactionUserId, false);

            if ($result['message'] == 'corporate_account_waiting_approval') {
                return array('otp' => false, 'success' => false, 'message' => $this->translator->trans('This user is not allowed to approve the reservation request.'));
            } else {
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

                $paymentMethod = $this->checkPaymentType($reservation->getHotelGrandTotal(), $reservation->getHotelCurrency());

                $paymentMethod['reservationId'] = $reservation->getId();
                $paymentMethod['moduleId']      = $this->container->getParameter('MODULE_HOTELS');
                return $paymentMethod;
            }
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

            // Return msg accordingly
            return array('otp' => false, 'success' => false, 'message' => $this->translator->trans('The selected room offer is not available anymore.'));
        }
    }
}
