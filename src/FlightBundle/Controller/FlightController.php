<?php

namespace FlightBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use FlightBundle\Entity\PassengerNameRecord;
use FlightBundle\Entity\PassengerDetail;
use FlightBundle\Entity\FlightDetail;
use FlightBundle\Entity\FlightInfo;
use PaymentBundle\Entity\Payment;
use FlightBundle\Form\Type\PassengerNameRecordType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use PaymentBundle\Model\Payment as PaymentObj;
use PaymentBundle\Services\impl\PaymentServiceImpl;
use FlightBundle\vendors\sabre\FlightItineraryNormaliser;

class FlightController extends \TTBundle\Controller\DefaultController
{
    private $defaultCurrency              = "USD";
    private $currencyPCC                  = "AED";
    private $connection_type_bfm          = 1;
    private $connection_type_booking      = 2;
    private $enableCancelation            = true;
    private $enableRefundable             = true;
    private $discount                     = 73.46; // value in AED, equivalent of USD 20
    private $methodOneByID                = "OneById"; //this is not used for now( a test to push on tt.ttflight)
    private $methodOneByYourEmail         = "OneByYourEmail"; // this is a second test ( a test to make tt.ttflight working properly)
    private $pricePercentMargin           = 20;
    private $paymentGatewayFeedbackTiming = array('pause_between_retries_secs' => 10, 'time_limit_mins' => 1);
    // the following array is used when calling TravelItineraryRQ (SabreServices 'createTravelItineraryRequest method) w/ check_airline_locators set to true

    private $airlineLocatorsTiming = array('pause_between_retries_secs' => 30, 'time_limit_mins' => 9);

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
    }

    // seoKeyWordFiller is a function to make the page seo friendly
    public function seoKeywordFiller($seotitle, $seodescription, $seokeywords)
    {

        $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($seotitle);
        $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($seodescription);
        $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($seokeywords);
    }

    public function flightBookingAction($seotitle, $seodescription, $seokeywords)
    {

        $this->data['needpayment']      = 1;
        $this->data['showHeaderSearch'] = 0;

        $request  = $this->getRequest();
        $error    = $request->query->get('error', null);
        $timedOut = $request->query->get('timedOut', null);

        $this->data['error']    = $error;
        $this->data['timedOut'] = $timedOut;
        $this->setHreflangLinks("/flight-booking");
        if ($this->data['aliasseo'] == '') {

            $this->seoKeywordFiller($seotitle, $seodescription, $seokeywords);
        }
        $getAirlines  = $this->getDoctrine()->getRepository('TTBundle:Airline')->findAll();
        $airlineCount = sizeof($getAirlines);

        for ($i = 0; $i < $airlineCount; $i++) {
            $airlineInfo[$i]['code']     = $getAirlines[$i]->getCode();
            $airlineInfo[$i]['nameCode'] = $getAirlines[$i]->getName().' ('.$airlineInfo[$i]['code'].')';
        }

        $this->data['airlines'] = $airlineInfo;
        return $this->render('@Flight/flight/flight-booking.twig', $this->data);
    }

    /**
     * this action handle the first link concerning the flight search, is where the user insert chosen criteria, to search flight availbilty
     * @param string $seotitle keyword specific for SEO
     * @param string $seodescription keyword specific for SEO
     * @param string $seokeywords keyword specific for SEO
     * @return twig name flight-booking-result contains all the flight and their details
     */
    public function flightBookingResultAction($seotitle = '', $seodescription = '', $seokeywords = '')
    {
        $this->data['needpayment']      = 1;
        $this->data['showHeaderSearch'] = 0;

        $request = $this->getRequest();

        if ($this->data['aliasseo'] == '') {
            $this->seoKeywordFiller($seotitle, $seodescription, $seokeywords);
        }

        $search = $this->get('FlightFinder')->search($request, $request->getSession(), $this->data);

        if (isset($search['from_mobile']) && $search['from_mobile'] == 1) {

            return $search;
        }
        if (isset($search['error']['redirect'])) {

            $this->addErrorNotification($this->translator->trans("Error, while booking process, please repeat the proccess."), 0);

            return $this->redirectToRoute($search['error']['redirect']['route'], ['timedOut' => true]);
        }

        $response = $search;
        if (isset($response['error']['message'])) {
            $response['error'] = $response['error']['message'];
        } else {
            unset($response['error']);
        }

        return $response;
    }

    /**
     * this action handle the first link concerning the flight search, is where the user insert chosen criteria, to search flight availbilty
     * @param string $seotitle keyword specific for SEO
     * @param string $seodescription keyword specific for SEO
     * @param string $seokeywords keyword specific for SEO
     * @return twig name flight-booking-result contains all the flight and their details
     */
    public function flightBookingResultNewAction($seotitle = '', $seodescription = '', $seokeywords = '')
    {

        $request = $this->getRequest();

        if ($this->data['aliasseo'] == '') {
            $this->seoKeywordFiller($seotitle, $seodescription, $seokeywords);
        }

        $search = $this->get('FlightFinder')->searchNew($request, $request->getSession(), $this->data);

        if (is_array($search)) {
            $post = $request->request->all();
            if($post){
                $search['request'] = $post;
            }else if(isset($search['cookieRequest']) && $search['cookieRequest']){
                $search['request'] = $search['cookieRequest'];
            }
        }

        if (isset($search['error']['redirect'])) {
            $this->addErrorNotification($this->translator->trans("Error, while booking process, please repeat the proccess."), 0);
            return $this->redirectToRoute($search['error']['redirect']['route'], ['timedOut' => true]);
        }

        $response = $search;
        if (isset($response['error']['message'])) {
            $response['error'] = $response['error']['message'];
        } else {
            unset($response['error']);
        }

        return $response;

    }

    /**
     * @author Joel C. Llano
     *
     * This method will generate token from the Request Form
     * will use crypt — One-way string hashing
     *
     * @param Request $request
     * @param array $skip_keys
     * @return string token
     */
    protected function generateTokenFromRequestForm(Request $request, array $skip_keys = null)
    {
        $secToken = [];
        foreach ($request->request as $key => $value) {
            if (in_array($key, $skip_keys)) {
                continue;
            }
            $secToken[]        = $value;
            $secKeyToken[$key] = $value;
        }
        sort($secToken, SORT_STRING);
        $secTokenStr = trim(implode(" ", $secToken));

        $sabreVariables = $this->get('SabreServices')->getSabreConnectionVariables($this->on_production_server);

        return crypt($secTokenStr, $sabreVariables['salt']);
    }

    /**
     * this Action is called when a user select a flight and press the Book button, and filed all the personal information in the Form, the api workflow start's
     * @param string $seotitle keyword specific for SEO
     * @param string $seodescription keyword specific for SEO
     * @param string $seokeywords keyword specific for SEO
     * @return twig name book-flight with a submit form, a twig that contains a form to fill the user personal information to continun the process the form submit to the same URL
     */
    public function bookFlightAction($seotitle, $seodescription, $seokeywords)
    {



        $this->data['needpayment']      = 1;
        $this->data['showHeaderSearch'] = 0;

        if ($this->data['aliasseo'] == '') {
            $this->seoKeywordFiller($seotitle, $seodescription, $seokeywords);
        }

        $request =$this->getRequest();





        if ($request->request->has('response')) {
            $_response                 = $request->request->get('response');
            $this->data['your_search'] = (is_array($_response)) ? json_encode($_response) : html_entity_decode($_response);
        }

        $this->data['isCorpoSite'] = $this->container->get('app.utils')->isCorporateSite();

        $bookingRequest = $this->get('FlightServices')->bookRequest($request);

        $requestData         = $bookingRequest['requestData'];

        $passengersArray     = $bookingRequest['passengersArray'];
        $passengerNameRecord = $bookingRequest['passengerNameRecord'];
        $sabreVariables      = $bookingRequest['sabreVariables'];
        $from_mobile         = $requestData->getFromMobile();


        // Temporary Commented, show passport field always but not required
        $this->data['isPassportRequired'] = $this->get('FlightServices')->isPassportRequired($requestData);
        //$this->data['isPassportRequired'] = 1;

        $user = null;

        $this->data['user_email'] = "";

        if ($this->data['USERID']) {
            $user                     = $this->get('CommonRepositoryServices')->cmsUsersInfo($this->data['USERID'], $this->methodOneByID);
            $this->data['user_email'] = $user->getYouremail();
        }

        $form = $this->createForm(new PassengerNameRecordType($this, $passengersArray, $requestData), $passengerNameRecord);
        $form->handleRequest($request);

        $this->data['returnedConversationId'] = $sabreVariables['returnedConversationId'];
        $this->data['access_token']           = $sabreVariables['access_token'];


        //Generate sec_token, usually when booking is from split segments
        if (!$request->request->has('sec_token') && $request->request->has('tokenvalues')) {
            $skipFields = ['access_token', 'tokenvalues', 'returnedConversationId', 'passengerNameRecord', 'submit-booking', 'pass', 'coupon_code', 'pin'];
            $requestData->setSecToken($this->generateTokenFromRequestForm($request, $skipFields));
        }

        $serviceHash = $this->get('FlightServices')->getServiceHash($requestData, $request, $sabreVariables);

        if (isset($serviceHash['priceError'])) {
            $this->data['secTokenError'] = true;
            return $serviceHash;
        } else {
            $validUnusedCoupon = $serviceHash['validUnusedCoupon'];
            $campaign_info     = $serviceHash['campaign_info'];
            $hiddenFields      = $serviceHash['hiddenFields'];
        }

        $em = $this->getDoctrine()->getManager();

        /*dump($requestData);exit;*/

        if (!$form->isSubmitted() && !isset($_response['trip_review'])) {

            if($request->request->has('related_one_way') && !empty($request->request->get('related_one_way')) && $request->request->get('flight_type') == 'returning' && $request->request->get('combinedData') == 0){
                $convertRelatedOneWayToSabre = $this->get('FlightServices')->convertRelatedOneWayToSabre(json_decode($request->request->get('related_one_way')), $sabreVariables);
                $sabre_return_flight = array_merge($sabreVariables, $convertRelatedOneWayToSabre['return_flight']);
                $sabre_related_one_way = array_merge($sabreVariables, $convertRelatedOneWayToSabre['related_one_way']);

                $relatedOneWayRequest = $this->get('FlightServices')->createEnhancedAirBookRequest($sabre_related_one_way, $requestData, $campaign_info);
                //if success proceed to return flight enhanced air book request
                if($relatedOneWayRequest['bookFlight']['status'] == 'success'){
                    $createEnhancedAirBookRequest = $this->get('FlightServices')->createEnhancedAirBookRequest($sabre_return_flight, $requestData, $campaign_info);
                }else{
                    $createEnhancedAirBookRequest = $relatedOneWayRequest;
                }
            }else{
                $createEnhancedAirBookRequest = $this->get('FlightServices')->createEnhancedAirBookRequest($sabreVariables, $requestData, $campaign_info);

            }

            if (isset($createEnhancedAirBookRequest['redirectToRoute'])) {
                return $this->redirectToRoute($createEnhancedAirBookRequest['redirectToRoute'], ['timedOut' => true]);
            }

            if (isset($createEnhancedAirBookRequest['priceError'])) {
                $this->data = $createEnhancedAirBookRequest;
            }

            $hiddenFields += $createEnhancedAirBookRequest['hiddenFields'];
        } else if (($form->isSubmitted() && $form->isValid()) || isset($_response['trip_review'])) {


            $bookRequestFormSubmit = $this->get('FlightServices')->bookRequestFormSubmit($passengerNameRecord, $requestData, $request, $sabreVariables, $validUnusedCoupon, $campaign_info);
            if (is_object($bookRequestFormSubmit) && $bookRequestFormSubmit instanceof $this) {
                if (isset($bookRequestFormSubmit->data['error'])) {
                    $this->data['error'] = $bookRequestFormSubmit->data['error'];
                }

                if (isset($bookRequestFormSubmit->data['errors'])) {
                    $this->data['errors'] = $bookRequestFormSubmit->data['errors'];
                }
            } else {

                if (isset($bookRequestFormSubmit['redirectError'])) {
//                    echo "exit in bookREquest form after submittion";
//                    exit;
                    $this->addErrorNotification($this->translator->trans("Error, while booking process, please repeat the proccess."), 0);
                    return $this->redirectToRoute($bookRequestFormSubmit['redirectError'], ['timedOut' => true]);
                }

                return $bookRequestFormSubmit;
            }
        }


        // TODO:: add and adjust entries below for coupon and discount related stuff
        if ($from_mobile) {
            $ret['data']['flight_segments']    = $flightSegments;
            $ret['data']['passengersArray']    = $passengersArray;
            $ret['data']['price']              = $displayedPrice;
            $ret['data']['displayed_price']    = number_format($displayedPrice, 2, '.', ',');
            $ret['data']['original_price']     = $originalPrice;
            $ret['data']['currency']           = $currencyCode;
            $ret['data']['displayed_currency'] = $displayedCurrency;
            $ret['data']['params']             = $hiddenFields;
            $ret['status']                     = 200;
            $ret['message']                    = 'Success';

            if (isset($campaign_info['target_helper_text'])) {
                $ret['campaign_helper_text'] = $campaign_info['target_helper_text'];
            }

            $res = new Response(json_encode($ret));
            $this->addFlightLog('Sending MobileRQ with response: {response}', array('response' => $res));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $this->data['flight_segments']    = $requestData->getFlightSegments();
        $this->data['passengersArray']    = $passengersArray;
        $this->data['price']              = $requestData->getDisplayedPrice();

        $this->data['displayed_price']    = number_format($requestData->getDisplayedPrice(), 2, '.', ',');
        $this->data['original_price']     = $requestData->getOriginalPrice();
        $this->data['currency']           = $requestData->getCurrencyCode();

        $this->data['displayed_currency'] = $requestData->getDisplayedCurrency();

        $this->data['form']               = $form->createView();

        $this->data['hiddenFields']       = $hiddenFields;


        if (isset($campaign_info['target_helper_text'])) {
            $this->data['campaign_helper_text'] = $campaign_info['target_helper_text'];
        }

        $response = $this->data;

        return $response;
        //return $this->render('@Flight/flight/book-flight.twig', $this->data);


    }

    /**
     * After a payment is succes this Action is called, in this function we change the PCC number
     * from ours to DANATA's PCC we create Pnr w call designated printer for hard copy and for e-ticket.
     * then we issue the ticket, and at the end  we save everything using the endtransaction
     * and we get the ticket number by calling the same function CreateItiner.
     * @return twig that contains all the flight details
     */
    public function issueAirTicketAction($redirected_request = null, $issueAirTicketAttemptNumber = 1)
    {
        $request = ($redirected_request ? $redirected_request : $this->getRequest());

        $this->addFlightLog('Payment submitted with criteria: {criteria}', array('criteria' => $request->query->all()));

        $transactionId   = $request->query->get('transaction_id', '');
        $transactionType = $request->query->get('type', 'flight');
        $is_from_mobile  = $request->query->get('from_mobile', '0');

        $flightTicketIssuer = $this->get('FlightTicketIssuer');
        $ticket             = $flightTicketIssuer->issueTicket(
            $transactionId, 
            $transactionType, 
            $this->on_production_server, 
            $is_from_mobile, 
            $this->connection_type_booking, 
            $this->data, 
            $this->enableCancelation, 
            $this->max_api_call_attempts, 
            $this->pause_between_retries_us, 
            $this->airlineLocatorsTiming
        );

        if (isset($ticket['error'])){
            $payment = $this->getDoctrine()->getRepository('PaymentBundle:Payment')->findOneByUuid($transactionId);
            $reservationId = $payment->getPassengerNameRecord()->getId();
            $params = array(
                'requestStatus' => $this->container->getParameter('CORPO_APPROVAL_PENDING'),
                'moduleId' => $this->container->getParameter('MODULE_FLIGHTS'),
                'reservationId' => $reservationId
            );

            $this->get('CorpoApprovalFlowServices')->updatePendingRequestServices($params);    
        }

        // Check if api response contains `EACH PASSENGER MUST HAVE SSR FOID-0052`,
        // Many international airlines require a form of identification (FOID) to be present in the PNR before you can issue an electronic ticket.
        // WE need the user to go back to PNR page to enter each passenger passport/ID info
        if (isset($ticket['error']) && $ticket['error'] == "EACH PASSENGER MUST HAVE SSR FOID-0052") {
            return array(
                'redirect_to' => $ticket['re_route'],
                'error' => $ticket['error'],
                'code' => 'FOID-0052',
                'status' => 'NotProcessed',
                'transactionId' => $transactionId,
                'pnrId' => $ticket['pnrId']
            );
        }


        if (isset($ticket['re_route'])) {
            // return $this->redirectToRoute('_flight_booking', array('error' => $ticket['error']));

            return array('redirect_to' => '_flight_booking', 'error' => $ticket['error']);
        }

        return $ticket;
    }

    /**
     * when a cancellation button is pressed this action is caled, it first get all the created tickets and pnr under the pnr sent then it loop on each ticket and void each tickete then it cancel the pnr ,
     * finally it saves everything with endTransaction function
     * @param type $seotitle
     * @param type $seodescription
     * @param type $seokeywords
     * @return twig with the flight cancellation details
     */
    public function flightCancelationAction($seotitle, $seodescription, $seokeywords, $redirected_request = null, $flightCancelationAttemptNumber = 1, $reservationId = 0)
    {

        // TODO:: check if it's more appropriate to skip this function and directly redirect to 'My Bookings' page whenever (passenger_name_record.status == 'CANCELLED')

        $currentTime = new \DateTime('now', new \DateTimeZone('Asia/Dubai'));

        $request = ($redirected_request ? $redirected_request : $this->getRequest());

        if ($this->data['aliasseo'] == '') {

            $this->seoKeywordFiller($seotitle, $seodescription, $seokeywords);
        }

        if ($reservationId == 0) {
            $transactionId = $request->query->get('transaction_id');
        } else {

            $transactionId = $this->get('FlightServices')->getPaymentTransactionUsingPnrID($reservationId);
        }
        $ticket_list = $request->query->get('ticket_list', '');
        if ($ticket_list) $ticket_list = explode(',', $ticket_list);
        else $ticket_list = array();

        $from_mobile = $request->request->get('from_mobile', '0');

//        if (false && (!$this->data['isUserLoggedIn'] || !$this->get('ApiUserServices')->tt_global_isset('userInfo'))) {
//
//            if ($from_mobile) {
//                $data['passengers'] = [];
//                $data['flight']     = [];
//                $data['message']    = $this->translator->trans('Please Login');
//                $data['status']     = 333;
//
//                $response = new JsonResponse();
//                $response->setData($data);
//                return $response;
//            }
//
//            return $this->redirectToLangRoute('_log_in');
//        }

        $error = '';

        $payment = $this->getDoctrine()->getRepository('PaymentBundle:Payment')->findOneByUuid($transactionId);

        //check first if booking is already cancelled
        $passengers = $payment->getPassengerNameRecord()->getPassengerDetails();
        $passengerCancelled = false;
        foreach ($passengers as $passenger)
        {
            if ($passenger->getTicketStatus() == 'Cancelled' or $passenger->getTicketStatus() == 'VOIDED'){
                $passengerCancelled = true;
                break;
            }
        }

        if($passengerCancelled && ($payment->getResponseMessage() == 'CANCELLED' or $payment->getResponseMessage() == 'VOIDED')){
            $response            = array();
            $response            = $this->data;
            $response['success'] = false;
            $response['error']   = $this->translator->trans("Error! This Booking is already cancelled.");
            $response['transaction_id'] = $transactionId;
            return $response;
        }

        //$payment = $this->get('FlightServices')->validatePayment($transactionId);

//        $userInfo = $this->get('ApiUserServices')->tt_global_get('userInfo');

//        if ($userInfo && (($payment->getUserId() != $userInfo['id']) || ($payment->getPassengerNameRecord()->getEmail() != $userInfo['email'])))
//        {
//
//            $error = $this->translator->trans('No data available');
//            if ($from_mobile == 1) {
//                $response = $this->get('PayFortServices')->responseData('', '00', '00104', $error);
//                $res      = new Response(json_encode($response));
//                $res->headers->set('Content-Type', 'application/json');
//                return $res;
//            }
//            $response = array();
//            $response = $this->data;
//            $response['nodata'] = 1;
//
//            return $response;
//
//        }

        $getcreationdate  = $payment->getCreationDate();
        $lastTimeToCancel = $getcreationdate->setTime(23, 00);




        if ($currentTime <= $lastTimeToCancel) {

            $flightCancelation = $this->get('FlightServices')->doflightCancelation($this, $payment, $from_mobile);

            $this->data['passengers'] = $flightCancelation['passengersArray'];

            $this->data['flightSegment']     = $flightCancelation['flightSegments'];
            $this->data["pnr_id"]            = $flightCancelation['pnrId'];
            $this->data["multi_destination"] = $flightCancelation['multiDestination'];
            $this->data["one_way"]           = $flightCancelation['oneWay'];
            $this->data["email"]             = $payment->getPassengerNameRecord()->getEmail();

            $sabreVariables          = $this->get('SabreServices')->getSabreConnectionVariables($this->on_production_server);
            $create_session_response = $this->get('SabreServices')->createSabreSessionRequest($sabreVariables, ($this->data['isUserLoggedIn'] ? $this->data['USERID'] : 0), $this->connection_type_booking, ($from_mobile
                    ? 'mobile' : 'web'));

            $this->addFlightLog("Got new connection with criteria (UUID:: $transactionId):: {criteria}", array('criteria' => $create_session_response));

            $sabreVariables['access_token']           = $create_session_response['AccessToken'];
            $sabreVariables['returnedConversationId'] = $create_session_response['ConversationId'];

            $sabreVariables['Service'] = "ContextChangeLLSRQ";
            $sabreVariables['Action']  = "ContextChangeLLSRQ";

            $contextChange = $this->get('SabreServices')->contextChangeRequest($sabreVariables);
            $this->addFlightLog("Getting API ContextChangeLLSRQ (UUID:: $transactionId) with status:: ".$contextChange["status"]);
            $this->addFlightLog("With criteria (UUID:: $transactionId):: {criteria}", array('criteria' => $contextChange));

            if ($this->get('SabreServices')->TEST_MODE) {
                $contextChange["status"] = "success";
            }

            if ($contextChange["status"] === "success") {

                $em                       = $this->getDoctrine()->getManager();
                $processFlightCancelation = $this->get('FlightServices')->processFlightCancelation($this, $payment, $sabreVariables, $em, $flightCancelation['pnrId'], $from_mobile);
            } else {
                $error = $this->translator->trans("Error! can't change context");
                $this->addFlightLog("Error! can't change context with criteria (UUID:: $transactionId):: {criteria}", array('criteria' => $contextChange));
            }

            $this->get('SabreServices')->closeSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
            $this->data['enableCancelation'] = $this->enableCancelation;
        } else {

            $error = $this->translator->trans('Flight tickets purchased may be canceled online with full refund if cancelled before 19.00 GMT on the same day of purchase.');
            $error .= $this->translator->trans('For flight tickets modifications and other cancellations airlines ticketing policies apply.');
            $error .= $this->translator->trans('Kindly Contact: ').'flights-support@touristtube.com'.$this->translator->trans(' - Expected reply within 24 hours.');
        }

        if ($from_mobile == 1) {
            $response = array('data' => [], 'status' => '00', 'response_code' => '00103', 'response_message' => $error);
            $res      = new Response(json_encode($response));
            $this->addFlightLog("Sending MobileRQ cancel ticket (UUID:: $transactionId):: with response:: ".$res);
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $response            = array();
        $response            = $this->data;
        $response['success'] = true;
        if ($error != '') {
            $response['success'] = false;
            $response['error']   = $error;
        }
        $response['transaction_id'] = $transactionId;

        return $response;
        // return $this->render('@Flight/flight/flight-cancelation.twig', $this->data);
    }


    /**
     * CMS OLD
     * @return Response
     */
    public function getQueueInfoAction()
    {

        $request     = $this->getRequest();
        $from_mobile = $request->query->get('from_mobile', '0');

        $sabreVariables          = $this->get('SabreServices')->getSabreConnectionVariables($this->on_production_server);
        $create_session_response = $this->get('SabreServices')->createSabreSessionRequest($sabreVariables, ($this->data['isUserLoggedIn'] ? $this->data['USERID'] : 0), $this->connection_type_booking, ($from_mobile
                ? 'mobile' : 'web'));

        $sabreVariables['access_token']           = $create_session_response['AccessToken'];
        $sabreVariables['returnedConversationId'] = $create_session_response['ConversationId'];

//  $sabreVariables['Service'] = "PassengerDetailsRQ";
//  $sabreVariables['Action'] = "PassengerDetailsRQ";
//  $passengerNameRegord = $this->get('SabreServices')->passengerDetailActionsRequest($sabreVariables, "retrieve", "WJFBCE");
//         testing
        $sabreVariables['Service'] = "QueueAccessLLSRQ";
        $sabreVariables['Action']  = "QueueAccessLLSRQ";

        $queue_type = $request->query->get('queue_type', '50');

        $queueAccessRequest = $this->get('SabreServices')->queueAccessRequest($sabreVariables, $queue_type, "access");
        $this->debug($queueAccessRequest);

        //   $timeTest = $this->get('SabreServices')->paymentLastUpdateChecker();

        $this->get('SabreServices')->closeSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));

        $response = new Response(json_encode($queueAccessRequest));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * when the user is filling the form or is on the payment page, a popup will appear if he excceded the 12 minutes, with 2 button close session or refresh session this function is called when the refresh session button is pressed
     * @return Response
     */
    public function refreshSessionAction()
    {

        $sabreVariables = $this->get('SabreServices')->getSabreConnectionVariables($this->on_production_server);

        $request = $this->getRequest();

        $accessToken            = $request->request->get('access_token', '');
        $returnedConversationId = $request->request->get('returnedConversationId', '@touristtube.com');


        $sabreVariables['access_token']           = $accessToken;
        $sabreVariables['returnedConversationId'] = $returnedConversationId;

        $sabreVariables['Service'] = "OTA_PingRQ";
        $sabreVariables['Action']  = "OTA_PingRQ";
        $refreshSession            = $this->get('SabreServices')->refreshSessionRequest($sabreVariables);
        $this->addFlightLog('Refreshing session, with criteria: {criteria}', array('criteria' => $refreshSession));

        $response = new Response(json_encode($refreshSession));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * when the user is filling the form or is on the payment page, a popup will appear if he excceded the 12 minutes, with 2 button close session or refresh session this function is called when the Close session button is pressed
     * @return Response
     */
    public function closeSessionAction()
    {

        $sabreVariables = $this->get('SabreServices')->getSabreConnectionVariables($this->on_production_server);

        $request = $this->getRequest();

        $from_mobile = $request->request->get('from_mobile');

        $accessToken            = $request->request->get('access_token');
        $returnedConversationId = $request->request->get('returnedConversationId');

        $sabreVariables['access_token']           = $accessToken;
        $sabreVariables['returnedConversationId'] = $returnedConversationId;

        $closeSession = $this->get('SabreServices')->closeSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
        $this->addFlightLog('Closing session, with criteria: {criteria}', array('criteria' => $closeSession));

        $response = new Response(json_encode($closeSession));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    // never used can be deleted
    public function endTransactionAction()
    {

        $sabreVariables = $this->get('SabreServices')->getSabreConnectionVariables($this->on_production_server);

        $request = $this->getRequest();

        $accessToken            = $request->request->get('access_token');
        $returnedConversationId = $request->request->get('returnedConversationId');

        $sabreVariables['access_token']           = $accessToken;
        $sabreVariables['returnedConversationId'] = $returnedConversationId;

        $sabreVariables['Service'] = "EndTransactionLLSRQ";
        $sabreVariables['Action']  = "EndTransactionLLSRQ";
        $endTransaction            = $this->get('SabreServices')->endTransactionRequest($sabreVariables);
        $this->addFlightLog('Ending session, with criteria: {criteria}', array('criteria' => $endTransaction));

        $response = new Response(json_encode($endTransaction));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    
    /**
     * Submit for mobile  l submission bi aleb l l select a flight bass lal mobile cz bl mobile mafi form
     * @return Response
     */
    public function createPnrApiAction()
    {
        $pnrData = array();
        $content = $this->get("request")->getContent();

        if (!empty($content)) {
            $pnrData = json_decode($content, true);
        }

        $couponCode = (isset($pnrData['coupon_code']) && $pnrData['coupon_code'] ? trim($pnrData['coupon_code']) : 0);

        $sabreVariables                           = $this->container->get('SabreServices')->getSabreConnectionVariables($this->on_production_server);
        $sabreVariables['Service']                = "PassengerDetailsRQ";
        $sabreVariables['Action']                 = "PassengerDetailsRQ";
        $sabreVariables['access_token']           = $pnrData['access_token'];
        $sabreVariables['returnedConversationId'] = $pnrData['returnedConversationId'];

        $marketingAirline = $pnrData['passengerNameRecord']['flightDetails'][0]['airline'];

        $pnr = $this->container->get('SabreServices')->createPassengerDetailsRequest($sabreVariables, $pnrData['passengerNameRecord'], $marketingAirline, "create");
        $controller->addFlightLog('Getting API PassengerDetailsRQ with status:: '.$pnr["status"].($pnr['status'] == 'success' ? ', PNR:: '.$pnr['pnrId'] : ''));
        $controller->addFlightLog('With criteria: {criteria}', array('criteria' => $pnr));

        if ($pnr['faultcode'] == 'InvalidSecurityToken' || $pnr['faultcode'] == 'InvalidEbXmlMessage') {
            $data['data']    = array();
            $data['message'] = $this->translator->trans('Oops!! you have been timed-out. Please try to search again.');
            $data['status']  = 201;
        } elseif ($pnr["status"] == "error") {
            $data['data']    = array();
            $data['message'] = $pnr['message'];
            $data['status']  = 201;
        } elseif ($pnr["status"] == "errors") {
            $data['data']     = array();
            $data['message']  = 'error';
            $data['status']   = 201;
            $data['messages'] = $pnr['messages'];
        } elseif ($pnr["status"] == "success") {

            $createPnrApi   = $this->get('FlightServices')->createPnrApi($this, $pnr, $pnrData, $this->methodOneByYourEmail, $this->currencyPCC, $this->discount, $couponCode);
            $payment        = $createPnrApi['payment'];
            $sabreVariables = $createPnrApi['sabreVariables'];

            $em = $this->getDoctrine()->getManager();
            $em->persist($payment);

            $em->flush();

            $data['data']    = $payment->getUuid();
            $data['message'] = 'Success';
            $data['status']  = 200;
        } else {
            $data['data']    = '';
            $data['message'] = $this->translator->trans('Could\'t create passenger name record please try again');
            $data['status']  = 201;
        }

        $this->get('SabreServices')->closeSabreSessionRequest($sabreVariables, 'mobile');

        $res = new Response(json_encode($data));
        $this->addFlightLog('Sending MobileRQ with response: '.$res);
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    /**
     * this is created to add Log to the Flight, thi is a very usefull way to debbug a problem, and track the flight action
     * @param String $message
     * @param array $params
     * @param boolean $cleanParams
     */
    public function addFlightLog($message, $params = array(), $cleanParams = false)
    {
        if (!isset($params) || !is_array($params)) $params = array();

        if ($params) {
            if ($cleanParams) {
                foreach (array_keys($params) as $param_key) {
                    $this->cleanParams($params[$param_key]);
                }
            }

            foreach (array_keys($params) as $param_key) {
                $params[$param_key] = json_encode($params[$param_key]);
            }
        }
        $params['userId'] = ($this->data['isUserLoggedIn'])? $this->userGetID() : 0;

        $logger = $this->get('monolog.logger.flights');
        $logger->info("\nUser {userId} - ".$message, $params);
    }

    /**
     * this action is responsible for the link retrieve-pnr we send the pnr, and we can now if the pnr is cancelled if the pnr don't have segments, or we can see what are the e-tickets of this pnr, and all information needed
     * @return twig w page that is for debugging
     */
    public function retrievePnrAction()
    {
        $request           = $this->getRequest();
        $pnrId             = $request->query->get('pnr');
        $cancellation_flag = $request->query->get('cancellation_flag', 0);
        $from_mobile       = $request->query->get('from_mobile', 0);

        if ($pnrId && $pnrId != "") {
            $sabreVariables          = $this->get('SabreServices')->getSabreConnectionVariables($this->on_production_server);
            $create_session_response = $this->get('SabreServices')->createSabreSessionRequest($sabreVariables, ($this->data['isUserLoggedIn'] ? $this->data['USERID'] : 0), $this->connection_type_booking, ($from_mobile
                    ? 'mobile' : 'web'));

            $sabreVariables['access_token']           = $create_session_response['AccessToken'];
            $sabreVariables['returnedConversationId'] = $create_session_response['ConversationId'];

            if ($cancellation_flag) {
                $query = $this->getDoctrine()->getManager()->createQueryBuilder()
                    ->select('p')
                    ->from('PaymentBundle:Payment', 'p')
                    ->innerJoin('p.passengerNameRecord', 'pnr')
                    ->where('pnr.pnr = :pnrId')
                    ->setParameter('pnrId', $pnrId);

                $compositeObject = $query->getQuery()->getResult();
                $compositeObject = $compositeObject[0];

                $callStack = $this->get('SabreServices')->cancelPNR($compositeObject, $sabreVariables);

                foreach ($callStack as $stepIndex => $apiCallDetails) {
                    $this->addFlightLog("Cancellation[step $stepIndex]:: {apiCallDetails}", array('apiCallDetails' => $apiCallDetails));
                }
            }

            $sabreVariables['Service'] ="GetReservationRQ";// "TravelItineraryReadRQ";
            $sabreVariables['Action'] = "GetReservationRQ";//"TravelItineraryReadRQ";
            $travelItineraryRead = $this->container->get('SabreServices')->createRetrieveItineraryRequest($sabreVariables, $pnrId, array('fetch_airline_locators' => true));//$this->container->get('SabreServices')->createTravelItineraryRequest($sabreVariables, $pnrId);

            /*$sabreVariables['Service'] = "TravelItineraryReadRQ";
            $sabreVariables['Action']  = "TravelItineraryReadRQ";
            $travelItineraryRead = $this->get('SabreServices')->createTravelItineraryRequest($sabreVariables, $pnrId);*/

            /*
              if ($this->getUserName() == 'digitalDNA')
              $this->debug($travelItineraryRead);
             */

            $this->addFlightLog("retrievePnrAction:: Getting API TravelItineraryRQ (PNR:: $pnrId) with status:: ".$travelItineraryRead["status"]);

            $this->debug('<br/>tickets:: '.print_r($travelItineraryRead['tickets'], true));
            echo '<br/><br/><textarea cols="120" rows="20">'.$travelItineraryRead['request'].'</textarea>';
            echo '<br/><br/><textarea cols="120" rows="20">'.$travelItineraryRead['response'].'</textarea>';

            $this->get('SabreServices')->closeSabreSessionRequest($sabreVariables);
        }
        return $this->render('@Flight/flight/flight-cancelation.twig', $this->data);
    }

    /**
     * this is not functional right now it will be soon
     * @param type $seotitle
     * @param type $seodescription
     * @param type $seokeywords
     * @return \TTBundle\Controller\JsonResponse
     */
    public function flightCancellationPaymentAction($seotitle, $seodescription, $seokeywords)
    {

        if ($this->data['aliasseo'] == '') {

            $this->seoKeywordFiller($seotitle, $seodescription, $seokeywords);
        }

        $request  = Request::createFromGlobals();
        $userInfo = $this->get('ApiUserServices')->tt_global_get('userInfo');
        $uuid     = $request->query->get('transaction_id');

        $this->data['showHeaderSearch'] = 0;
        $this->data['isUserLoggedIn']   = ($this->data['isUserLoggedIn'])? 1 : 0;

        $passengersArray = array();
        $isMobile        = $request->request->get('from_mobile', 0);

        $pnr = $this->getDoctrine()->getRepository('PaymentBundle:Payment')->find(urldecode($uuid));
        //$pnr = $this->get('FlightServices')->validatePayment(urldecode($uuid));

        $checkOwnerShip = $userInfo && (($pnr->getUserId() == $userInfo['id']) || ($pnr->getPassengerNameRecord()->getEmail() == $userInfo['email']) ? 1 : 0);

        $pnrStatus                 = $pnr->getPassengerNameRecord()->getStatus();
        $flightCancellationPayment = $this->get('FlightServices')->flightCancellationPayment($this, $pnr, $uuid);


        $parameters             = $flightCancellationPayment['parameters'];
        $signature              = $flightCancellationPayment['signature'];
        $passengersArray        = $flightCancellationPayment['passengersArray'];
        $flightSegments         = $flightCancellationPayment['flightSegments'];
        $masterPassParameters   = $flightCancellationPayment['masterPassParameters'];
        $visaCheckOutParameters = $flightCancellationPayment['visaCheckOutParameters'];


        $parameters['signature'] = $signature;

        $this->data['error']             = $request->query->get('response_message');
        $this->data['passengers']        = $passengersArray;
        $this->data['flight']            = $flightSegments;
        $this->data['message']           = $this->translator->trans('Success');
        $this->data['enableCancelation'] = $this->enableCancelation;
        $this->data['checkOwnerShip']    = $checkOwnerShip;
        $this->data['pnr_status']        = $pnrStatus;
        $this->addFlightLog('Getting API PayFortServices_Signature with response: '.$signature);


        $payFortServiceParams = $this->get('PayFortServices')->getServiceParameters($payment, $parameters); // what if payment is null ?
        $this->addFlightLog('[cancellation] PayFortServices_Params with response:: {criteria}', array('criteria' => $payFortServiceParams));


        $this->data['checkoutURL'] = $this->get('PayFortServices')->getcheckOutURL();
        $this->data['parameters']  = $parameters;


        $this->data['masterpass_parameters'] = $masterPassParameters;

        $this->data['visa_checkout_parameters'] = $visaCheckOutParameters;

        # --------- PATMENT GATEWAY STUFF HERE ------------#

        if ($isMobile) {
            $data['passengers']     = $passengersArray;
            $data['flight']         = $flightSegments;
            $data['message']        = $this->translator->trans('Success');
            $data['checkOwnerShip'] = $checkOwnerShip;
            $data['pnr_status']     = $pnrStatus;

            $response = new JsonResponse();
            $response->setData($data);
            return $response;
        } else {

            $this->data['error']             = $request->query->get('response_message');
            $this->data['passengers']        = $passengersArray;
            $this->data['flight']            = $flightSegments;
            $this->data['message']           = $this->translator->trans('Success');
            $this->data['enableCancelation'] = $this->enableCancelation;
            $this->data['checkOwnerShip']    = $checkOwnerShip;
            $this->data['pnr_status']        = $pnrStatus;

            $this->data['card_expiration_year_start'] = date('Y');

            //if ($pnr->getUserId() == $userId) {
            return $this->render('@Flight/flight/flight-cancellation-payment.twig', $this->data);
            /*
              } else {
              return $this->render('@Flight/Default/flight-no-data.twig', $this->data);
              }
             */
        }
    }

    public function myFlightDetailsAction($seotitle, $seodescription, $seokeywords = null, $reservationId = 0)
    {

        $userInfo = $this->get('ApiUserServices')->tt_global_get('userInfo');
        $request  = $this->getRequest();
        if ($request->get('forwardToEmail')) {
            $forwardToEmail = $request->get('forwardToEmail');
            $this->get('FlightServices')->sendEmailFromFlightDetails($this, $forwardToEmail, $request->query->get('transaction_id'));
            //return $this->redirectToRoute('_flight_details', ['transaction_id' => $request->query->get('transaction_id')]);
        }

        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($seotitle);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($seodescription);
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($seokeywords);
        }

        $request = Request::createFromGlobals();

        if ($reservationId == 0) {
            $uuid = $request->query->get('transaction_id');
        } else {

            $uuid = $this->get('FlightServices')->getPaymentTransactionUsingPnrID($reservationId);
        }
        $this->data['showHeaderSearch'] = 0;
        $this->data['isUserLoggedIn']   = ($this->data['isUserLoggedIn'])? 1 : 0;

        $isMobile = $request->request->get('from_mobile', 0);

        $pnr = $this->getDoctrine()->getRepository('PaymentBundle:Payment')->find(urldecode($uuid));
        //$pnr = $this->get('FlightServices')->validatePayment(urldecode($uuid));

        if (!$pnr) {
            return $this->redirectToLangRoute('_my_bookings');
        }

        $checkOwnerShip = $userInfo && (($pnr->getUserId() == $userInfo['id']) || ($pnr->getPassengerNameRecord()->getEmail() == $userInfo['email']) ? 1 : 0);

        $pnrStatus = $pnr->getPassengerNameRecord()->getStatus();

        $myFlightDetails = $this->get('FlightServices')->myFlightDetails($pnr, $uuid);
        $passengersArray = $myFlightDetails['passengersArray'];
        $flightSegments  = $myFlightDetails['flightSegments'];

        //}
        if ($isMobile) {
            $data['passengers']     = $passengersArray;
            $data['flight']         = $flightSegments;
            $data['message']        = $this->translator->trans('Success');
            $data['checkOwnerShip'] = $checkOwnerShip;
            $data['pnr_status']     = $pnrStatus;
            $data['payment']        = $pnr;

            $response = new JsonResponse();
            $response->setData($data);
            return $response;
        } else {
            $this->data['passengers']        = $passengersArray;
            $this->data['flight']            = $flightSegments;
            $this->data['message']           = $this->translator->trans('Success');
            $this->data['enableCancelation'] = $this->enableCancelation;
            $this->data['checkOwnerShip']    = $checkOwnerShip;
            $this->data['pnr_status']        = $pnrStatus;
            $this->data['payment']           = $pnr;

            $response = array();
            $response = $this->data;
            return $response;
            //if ($pnr->getUserId() == $userId) {
            //return $this->render('@Flight/flight/flight-detailed.twig', $this->data);
            /* } else {
              return $this->render('@Flight/flight/flight-no-data.twig', $this->data);
              } */

            return $this->data;
        }
    }

    /**
     * flightDetailsCheckAction is a function used to check if the flight is available or not,, it takes the trxId from the url ( which is the pnrId )
     * and get all the needed infromation to perform the EnhancedAirBookRequest , and based on the response of this request, we redirect the use to the search result
     * in case of price error, or continue to the payment in case of success
     * @param type $seotitle
     * @param type $seodescription
     * @param type $seokeywords
     * @return redirection
     */
    public function flightDetailsCheckAction($reservationId, $moduleId, $accountId, $transactionUserId, $requestServicesDetailsId, $seotitle, $seodescription, $seokeywords)
    {

        $request      = Request::createFromGlobals();
        $pnrId        = $reservationId; //$request->query->get('reservationId');
        $getPnrRecord = $this->getDoctrine()->getRepository('FlightBundle:PassengerNameRecord')->find($pnrId);

        $getFlightDetails = $this->getDoctrine()->getRepository('FlightBundle:FlightDetail')->findByPnrId($pnrId);
        $totalSegments    = count($getFlightDetails);

        $uuid = $getPnrRecord->getPaymentUUID();

        $pnr              = $this->getDoctrine()->getRepository('PaymentBundle:Payment')->find(urldecode($uuid));
        $passengerDetails = $pnr->getPassengerNameRecord()->getPassengerDetails();
        $numberInParty    = count($passengerDetails);

        $adultsQuantity   = 0;
        $childrenQuantity = 0;
        $infantsQuantity  = 0;

        for ($i = 0; $i < $numberInParty; $i++) {

            if ($passengerDetails[$i]->getType() == 'ADT') {
                $adultsQuantity++;
            }
            if ($passengerDetails[$i]->getType() == 'CNN') {
                $childrenQuantity++;
            }
            if ($passengerDetails[$i]->getType() == 'INF') {
                $infantsQuantity++;
            }
        }

        for ($i = 0; $i < $totalSegments; $i++) {
            $originLocation[]       = $getFlightDetails[$i]->getDepartureAirport();
            $destinationLocation[]  = $getFlightDetails[$i]->getArrivalAirport();
            $resBookDesigCode[]     = $getFlightDetails[$i]->getResBookDesignCode();
            $departureDateTime[]    = $getFlightDetails[$i]->getDepartureDateTime()->format('Y-m-d\TH:i:s');
            $arrivalDateTime[]      = $getFlightDetails[$i]->getArrivalDateTime()->format('Y-m-d\TH:i:s');
            $flightNumber[]         = $getFlightDetails[$i]->getFlightNumber();
            $airlineCode[]          = $getFlightDetails[$i]->getAirline();
            $operatingAirlineCode[] = $getFlightDetails[$i]->getOperatingAirline();
        }

        $originalPrice = $pnr->getAmount();
        $currencyCode  = $pnr->getCurrency();

        $status = $pnr->getPassengerNameRecord()->getStatus();

        $sabreVariables = $this->get('SabreServices')->getSabreConnectionVariables($this->on_production_server);

        $totalSegments = array_map('strval', str_split($totalSegments));

        $sabreVariables['total_segments']      = $totalSegments;
        $sabreVariables['DepartureDateTime']   = $departureDateTime;
        $sabreVariables['ArrivalDateTime']     = $arrivalDateTime;
        $sabreVariables['FlightNumber']        = $flightNumber;
        $sabreVariables['NumberInParty']       = $numberInParty;
        $sabreVariables['ResBookDesigCode']    = $resBookDesigCode;
        $sabreVariables['DestinationLocation'] = $destinationLocation;
        $sabreVariables['MarketingAirline']    = $airlineCode;
        $sabreVariables['OperatingAirline']    = $operatingAirlineCode;
        $sabreVariables['OriginLocation']      = $originLocation;
        $sabreVariables['AdultsQuantity']      = $adultsQuantity;
        $sabreVariables['ChildrenQuantity']    = $childrenQuantity;
        $sabreVariables['InfantsQuantity']     = $infantsQuantity;
        $sabreVariables['PriceAmount']         = $originalPrice + $this->discount;
        $sabreVariables['CurrencyCode']        = $currencyCode;


        $create_session_response = $this->get('SabreServices')->createSabreSessionRequest($sabreVariables, ($this->data['isUserLoggedIn'] ? $this->data['USERID'] : 0), $this->connection_type_booking, ('web'));
        $this->addFlightLog('Getting API SessionCreateRQ with response: '.$create_session_response['status']);
        $this->addFlightLog('With criteria: {criteria}', array('criteria' => $create_session_response));

        $sabreVariables['access_token']           = $create_session_response['AccessToken'];
        $sabreVariables['returnedConversationId'] = $create_session_response['ConversationId'];

        $hiddenFields['access_token']           = $sabreVariables['access_token'];
        $hiddenFields['returnedConversationId'] = $sabreVariables['returnedConversationId'];

        $sabreVariables['price_percent_margin'] = $this->pricePercentMargin;
        $sabreVariables['Service']              = 'EnhancedAirBookRQ';
        $sabreVariables['Action']               = 'EnhancedAirBookRQ';
        $bookFlight                             = $this->get('SabreServices')->createEnhancedAirBookRequest($sabreVariables);
        $this->addFlightLog('Getting API EnhancedAirBookRQ with response: '.$bookFlight['status']);
        $this->addFlightLog('With criteria: {criteria}', array('criteria' => $bookFlight));

        $this->addFlightLog('bookFlight:: amountSpecified:: '.$bookFlight['amountSpecified'].', amountReturned:: '.$bookFlight['amountReturned']);

        $session  = $this->getRequest()->getSession();
        $all      = array();

        if ($bookFlight['faultcode'] == 'InvalidSecurityToken' || $bookFlight['faultcode'] == 'InvalidEbXmlMessage') {

            $this->get('SabreServices')->closeSabreSessionRequest($sabreVariables, ('web'));
            $this->addFlightLog('Close session - Enhanced Air Book RQ error and fault code: '.$bookFlight['faultcode']);

            return $this->redirectToRoute('_corporate_flight_search', ['timedOut' => true]);
        } elseif ($bookFlight['status'] == 'priceError' || $bookFlight['status'] == 'error') {

            if ($bookFlight['status'] == 'priceError')
                    $this->addFlightLog('bookFlight:: priceError:: amountSpecified:: '.$bookFlight['amountSpecified'].', amountReturned:: '.$bookFlight['amountReturned'].', soap_response:: {soap_response}', array(
                    'soap_response' => $bookFlight));

            $params = array(
                'requestStatus' => $this->container->getParameter('CORPO_APPROVAL_EXPIRED'),
                'moduleId' => $this->container->getParameter('MODULE_FLIGHTS'),
                'reservationId' => $pnrId
            );


            $this->get('CorpoApprovalFlowServices')->updatePendingRequestServices($params);

            $this->get('SabreServices')->closeSabreSessionRequest($sabreVariables, ('web'));
            $this->addFlightLog('Close session - Enhanced Air Book RQ error: '.$bookFlight['status']);

            $flightDetail   = $pnr->getPassengerNameRecord()->getFlightDetails();
            $flightSegments = $this->get('MyBookingServices')->flightDetails($flightDetail);

            $fromDate = new \DateTime($flightSegments['leaving']['flight_info'][0]['raw_departure_date']);
            $toDate   = new \DateTime($flightSegments['leaving']['flight_info'][0]['raw_arrival_date']);

            if (isset($flightSegments['returning'])) {
                $toDate = new \DateTime($flightSegments['returning']['flight_info'][0]['raw_departure_date']);
            }

            $now = new \DateTime();

            if ($fromDate < $now) {
                return $this->redirectToRoute('_corporate_account_waiting_approval');
            }

            $isOneWay = $pnr->getPassengerNameRecord()->getFlightInfo()->isOneWay();

            $all['departureairport']  = $flightSegments['leaving']['flight_info'][0]['origin_airport'];
            $all['departureairportC'] = $flightSegments['leaving']['flight_info'][0]['origin_airport_code'];
            $all['arrivalairport']    = $flightSegments['leaving']['flight_info'][0]['destination_airport'];
            $all['arrivalairportC']   = $flightSegments['leaving']['flight_info'][0]['destination_airport_code'];
            $all['fromDateC']         = $fromDate->format('Y-m-d');
            $all['toDateC']           = $toDate->format('Y-m-d');
            $all['cabinselect']       = ((!empty($flightSegments['leaving']['flight_info'][0]['cabin']) ? 'Y' : 'N'));
            $all['oneway']            = ($isOneWay ? "1" : "");
            $all['multidestination']  = ($pnr->getPassengerNameRecord()->getFlightInfo()->isMultiDestination() ? "1" : "");
            $all['note']              = 'Your chosen Flight is Expired, please re-select it from the result below, or choose a new one, sorry for any inconvenience';

            if ($isOneWay) {
                $all['flightNumber'] = $flightSegments['leaving']['flight_info'][0]['flight_number'];
            }

            $session->set('_corporate_flight_search', $all);


            return $this->redirectDynamicRoute('_corporate_flight_search');
        } elseif ($status == "SUCCESS") {
            $userId             = $this->userGetID();
            $userArray          = $this->get('UserServices')->getUserDetails(array('id' => $userId));
            $userCorpoAccountId = $userArray[0]['cu_corpoAccountId'];

            $getDefaultPaymentType  = $this->get('CorpoAccountServices')->getCorpoAccountPaymentType($userCorpoAccountId);
            $defaultPaymentTypeCode = $getDefaultPaymentType['code'];

            $result                   = array();
            $result["transaction_id"] = $uuid;
            if ($defaultPaymentTypeCode == 'cc') {
                $result["callback_url"] = '_paymentview';
            } elseif ($defaultPaymentTypeCode == 'coa') {
                $result["callback_url"] = '_corpo_on_account_payment_process';
            }

            $session->set('_corporate_flight_search', $all);


            return $this->redirectDynamicRoute($result["callback_url"], array('transaction_id' => $result["transaction_id"]));
        }
    }

    public function sendEmailFromFlightDetailsAction($email, $emailData)
    {
        $msg = $this->renderView('emails/flight_email_confirmation_new.twig', array('emailData' => $emailData));

        $this->get('EmailServices')->addEmailData($email, $msg, $this->translator->trans('TouristTube Travel Confirmation'), $this->translator->trans('TouristTube Travel Confirmation'), 0);

        $this->addFlightLog('Creating flight email confirmation with criteria: {criteria}', array('criteria' => (array) $emailData));
    }

    public function redirectRoute($route, $params = array())
    {
        if ($this->isRouteExists($route)) return $this->redirectToRoute($route, $params);
        else {
            $key = "?";
            if (strpos($route, '?') !== false) $key = "";
            return $this->redirect($route.$key.http_build_query($params));
        }
    }

    public function paymentApproval($payment, $moduleId)
    {

        $result = parent::paymentApproval($payment, $moduleId);

        return $result;
    }
}
