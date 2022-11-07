<?php
namespace FlightBundle\vendors\sabre;

use FlightBundle\Controller\FlightController;
use Symfony\Component\DependencyInjection\Container;
use FlightBundle\Entity\PassengerNameRecord;
use FlightBundle\vendors\sabre\RequestDataHandler;
use FlightBundle\Form\Type\PassengerNameRecordType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use FlightBundle\Entity\PassengerDetail;
use FlightBundle\Entity\FlightDetail;
use FlightBundle\Entity\FlightInfo;
use PaymentBundle\Entity\Payment;
use PaymentBundle\Model\Payment as PaymentObj;
use PaymentBundle\Services\impl\PaymentServiceImpl;
use Symfony\Component\HttpFoundation\Response;

class SabreHandler
{
    private $discount=73;
    private $connection_type_booking = 2;
    private $defaultCurrency = "USD";
    private $currencyPCC     = "AED";
    private $methodOneByID   = "OneById";
    private $connection_type_bfm     = 1;
    private $enableCancelation       = true;
    private $container;
    private $requestDataHandler;
    private $em;
    private $translator;
    private $templating;
    private $flightController;
    private $methodOneByYourEmail    = "OneByYourEmail";
    private $production_server = false;

    public function __construct(Container $container, RequestDataHandler $requestDataHandler, EntityManager $em, FlightController $flightController)
    {
        $this->container = $container;
        $this->requestDataHandler = $requestDataHandler;
        $this->em = $em;
        $this->translator = $container->get('translator');
        $this->templating = $container->get('templating');
        $this->flightController = $flightController;
      //  $this->discount=$this->container->get('FlightRepositoryServices')->getFlightsDiscount();
    }

    public function setSabreVariables($requestData)
    {
        $sabreVariables = $this->container->get('SabreServices')->getSabreConnectionVariables($this->production_server);

        $sabreVariables['access_token']           = $requestData->getAccessToken();
        $sabreVariables['returnedConversationId'] = $requestData->getReturnedConversationId();
        $sabreVariables['total_segments']      	  = $requestData->getTotalSegments();
        $sabreVariables['DepartureDateTime']      = $requestData->getDepartureDateTime();
        $sabreVariables['ArrivalDateTime']        = $requestData->getArrivalDateTime();
        $sabreVariables['FlightNumber']           = $requestData->getFlightNumber();
        $sabreVariables['NumberInParty']          = $requestData->getNumberInParty();
        $sabreVariables['ResBookDesigCode']       = $requestData->getResBookDesigCode();
        $sabreVariables['DestinationLocation']    = $requestData->getDestinationLocation();
        $sabreVariables['MarketingAirline']       = $requestData->getAirlineCode();
        $sabreVariables['OperatingAirline']       = $requestData->getOperatingAirlineCode();
        $sabreVariables['OriginLocation']         = $requestData->getOriginLocation();
        $sabreVariables['AdultsQuantity']         = $requestData->getAdultsQuantity();
        $sabreVariables['ChildrenQuantity']       = $requestData->getChildrenQuantity();
        $sabreVariables['InfantsQuantity']        = $requestData->getInfantsQuantity();
        $sabreVariables['PriceAmount']            = $requestData->getOriginalPrice();// + $this->discount;
        $sabreVariables['CurrencyCode']           = $requestData->getCurrencyCode();

        return $sabreVariables;
    }

    public function updateSabreVariables($sabreVariables, $thisData)
    {
        $create_session_response = $this->container->get('SabreServices')->createSabreSessionRequest($sabreVariables, ($thisData->data['isUserLoggedIn'] ? $thisData->data['USERID'] : 0), $thisData->connection_type_booking, ($from_mobile
            ? 'mobile' : 'web'));
        $thisData->addFlightLog('Getting API SessionCreateRQ with response: '.$create_session_response['status']);
        $thisData->addFlightLog('With criteria: {criteria}', array('criteria' => $create_session_response));

        $sabreVariables['access_token']           = $create_session_response['AccessToken'];
        $sabreVariables['returnedConversationId'] = $create_session_response['ConversationId'];

        return $sabreVariables;
    }

    /**
     * Generates sabreVariables and creates session response
     * @param object/array $controller method/functions
     * @return array $sabreVariables
     **/
    public function createSessionResponse($controller)
    {
        $request     = $this->getRequest();
        $from_mobile = $request->query->get('from_mobile', '0');

        $sabreVariables = $this->container->get('SabreServices')->getSabreConnectionVariables($this->on_production_server);

        $create_session_response = $this->container->get('SabreServices')->createSabreSessionRequest($sabreVariables, ($controller->data['isUserLoggedIn'] ? $controller->data['USERID'] : 0), $controller->connection_type_booking, ($from_mobile
            ? 'mobile' : 'web'));

        $sabreVariables['access_token']           = $create_session_response['AccessToken'];
        $sabreVariables['returnedConversationId'] = $create_session_response['ConversationId'];

        return $sabreVariables;
    }

    /**
     * Process booking request data (normalise into object)
     * and then create PassengerNameRecord for Passengers
     * and Flight Details, then generate sabreVariables
     * @param object $request
     * @return object $requestData
     * @return array $passengersArray
     * @return object $passengerNameRecord
     * @return array $sabreVariables

     **/
    public function bookRequest($request)
    {

        $this->container->get('FlightServices')->addFlightLog('Selected booking result with criteria: {criteria}', array('criteria' => $request->request->all()));

        $requestData = $this->requestDataHandler->normaliseRequest($request);

        $passengers = $this->requestDataHandler->getPassengerDetails($requestData)->getPassengers();
        $passengersArray = $passengers[0];

        $passengerNameRecord = $this->container->get('FlightRepositoryServices')->addFlightsPassengersPNR($requestData, $passengersArray);

        $sabreVariables = $this->setSabreVariables($requestData);

        $response = array();
        $response['requestData'] = $requestData;
        $response['passengersArray'] = $passengersArray;
        $response['passengerNameRecord'] = $passengerNameRecord;
        $response['sabreVariables'] = $sabreVariables;

        return $response;
    }
    /**
     * This function will process Enhanced Air Booking Request for Sabre whiich is called during BOOK REQUEST
     * and will usually return into PRICE ERROR notice upon unsuccessful REQUEST or TIME OUT
     **/

    public function createEnhancedAirBookRequest($sabreVariables, $requestData, $campaign_info)
    {

        $response = array();
        $from_mobile = $requestData->getFromMobile();

        if (!isset($sabreVariables['use_same_connection']) || !$sabreVariables['use_same_connection'])
        {
            $create_session_response = $this->container->get('SabreServices')->createSabreSessionRequest($sabreVariables, ($this->flightController->data['isUserLoggedIn'] ? $this->flightController->data['USERID'] : 0), $this->connection_type_booking, ($from_mobile? 'mobile' : 'web'));

            $this->container->get('FlightServices')->addFlightLog('Getting API SessionCreateRQ with response: '.$create_session_response['status']);
            $this->container->get('FlightServices')->addFlightLog('With criteria: {criteria}', array('criteria' => $create_session_response));

            $sabreVariables['access_token'] = $create_session_response['AccessToken'];
            $sabreVariables['returnedConversationId'] = $create_session_response['ConversationId'];
        }

        $hiddenFields['access_token'] = $sabreVariables['access_token'];
        $hiddenFields['returnedConversationId'] = $sabreVariables['returnedConversationId'];

        $sabreVariables['Service'] = 'EnhancedAirBookRQ';
        $sabreVariables['Action']  = 'EnhancedAirBookRQ';

        $bookFlight = $this->container->get('SabreServices')->createEnhancedAirBookRequest($sabreVariables);

        $this->container->get('FlightServices')->addFlightLog('Getting API EnhancedAirBookRQ with response: '.$bookFlight['status']);
        $this->container->get('FlightServices')->addFlightLog('With criteria: {criteria}', array('criteria' => $bookFlight));

        if ($bookFlight['faultcode'] == 'InvalidSecurityToken' || $bookFlight['faultcode'] == 'InvalidEbXmlMessage') {

            $this->container->get('SabreServices')->closeSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
            $this->container->get('FlightServices')->addFlightLog('Close session - Enhanced Air Book RQ error and fault code: '.$bookFlight['faultcode']);

            if ($from_mobile) {
                $ret['data']    = array();
                $ret['status']  = '202';
                $ret['message'] = $this->translator->trans('Oops!! you have been timed-out. Please try to search again.');
                $res            = new Response(json_encode($ret));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            } else {
                return ['redirectToRoute' => ($this->container->get('app.utils')->isCorporateSite()?'_corporate_flight':'_flight_booking')];
                //return $this->redirectToRoute('_flight_booking', ['timedOut' => true]);
            }

        } elseif ($bookFlight['status'] == 'priceError' || $bookFlight['status'] == 'error') {

            $this->container->get('SabreServices')->closeSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
            $this->container->get('FlightServices')->addFlightLog('Close session - Enhanced Air Book RQ error: '.$bookFlight['status']);

            if ($from_mobile) {
                $ret['data']                 = array();
                $ret['status']               = '203';
                $ret['message']              = $this->translator->trans('The price is no longer available.');
                if (isset($campaign_info['target_helper_text'])) $ret['campaign_helper_text'] = $campaign_info['target_helper_text'];
                $res                         = new Response(json_encode($ret));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            } else {
                $this->flightController->data['hiddenFields'] = $hiddenFields;
                $this->flightController->data['priceError']           = true;
                if (isset($campaign_info['target_helper_text'])) $this->flightController->data['campaign_helper_text'] = $campaign_info['target_helper_text'];

                return $this->flightController->data;
                //return $this->render('@Flight/flight/book-flight.twig', $this->data);
            }
        }

        $response['hiddenFields'] = $hiddenFields;
        $response['bookFlight'] = $bookFlight;

        return $response;
    }

    public function PnrCreation($passengerNameRecord, $requestData, $request, $sabreVariables, $validUnusedCoupon, $campaign_info, $bookRequestFormSubmitAttemptNumber = 1)
    {

        $em = $this->em;
        //   $flightSegments = $requestData->getFlightSegments();
        $sabreVariables['Service'] = "PassengerDetailsRQ";
        $sabreVariables['Action'] = "PassengerDetailsRQ";
        $marketingAirline = $request->request->get('airline_code');//'EK';
        $pnr = null;

        $pnr = $this->container->get('SabreServices')->createPassengerDetailsRequest($sabreVariables, $request->request->get('passengerNameRecord'), $marketingAirline);

        $this->container->get('FlightServices')->addFlightLog('Requesting API SessionCloseRQ');
        $this->container->get('SabreServices')->closeSabreSessionRequest($sabreVariables, 1);

        $passengerNameRecord = $this->container->get('FlightRepositoryServices')->addFlightInfo($passengerNameRecord, $pnr, $requestData, $this->discount, $this->currencyPCC);


        $payment = $this->container->get('FlightRepositoryServices')->addPayment($passengerNameRecord, $pnr, $requestData, $this->discount, $this->currencyPCC, $request, $validUnusedCoupon, $campaign_info, $em, $this->methodOneByYourEmail);

        $paymentObj = $this->container->get('FlightRepositoryServices')->addPaymentObj($pnr, $payment['payment'], $payment['uuid'], $requestData, $this->currencyPCC, $payment['responseMessage'], $payment['responseCode'], $payment['paymentStatus'], $payment['displayTotalFare'], $request);
        $result = $this->flightController->paymentApproval($paymentObj, $this->container->getParameter('MODULE_FLIGHTS'));


        //  setcookie('TT-flight', '', 1);

        if (!isset($result["transaction_id"])) {
            $result["transaction_id"] = null;
        }

        $res['pnr'] = $pnr;
        $res['transaction_id'] = $result["transaction_id"];
        return $res;

    }
    /**
     * After the BOOK request, this where the creation of PassengerNameRecord is done including the DB persists action
     * after the user fill-out the FORM and click BUY button which includes the passenger details
     * and returns TRANSACTION_ID upon successful process including payments
     **/

    public function bookRequestFormSubmit($passengerNameRecord, $requestData, $request, $sabreVariables, $validUnusedCoupon, $campaign_info, $bookRequestFormSubmitAttemptNumber = 1)
    {



        $em = $this->em;
        $user = $this->container->get('CommonRepositoryServices')->cmsUsersInfo($this->flightController->data['USERID'], $this->methodOneByID);

        $pin_mismatch = false;
        $flightSegments = $requestData->getFlightSegments();
        $from_mobile = $requestData->getFromMobile();

        $this->container->get('FlightServices')->addFlightLog("Flight booking requested[attemptNumber:: $bookRequestFormSubmitAttemptNumber] with criteria:: {criteria}", array('criteria' => $request->request->all()));

        /*
		if ($bookRequestFormSubmitAttemptNumber == 1 && $this->flightController->user_pin_validation_mode && $this->flightController->data['is_corporate_account'] && $user) {
			$pin = $request->request->get("pin", 0);

			if (!$pin || ($pin && $pin != $user->getCorporateAccountPin())) {

				$pin_mismatch = true;
				$this->flightController->data['pin_mismatch'] = $this->translator->trans('PIN mismatch');

				return $this->flightController->data;
			}
		}
		*/

//                exit;
        if (!$pin_mismatch) {
            $sabreVariables['Service'] = "PassengerDetailsRQ";
            $sabreVariables['Action']  = "PassengerDetailsRQ";
            $marketingAirline = $flightSegments['leaving']['flight_info'][0]['airline_code'];//'EK';

            $pnr = null;

            $operation_specs = array('IR' => 'USE IR TO IGNORE AND RETRIEVE PNR', 'INVALID_PRICE_QUOTE' => 'PQ RECORD NUMBER NOT VALID');

            for ($attemptNumber = 1; $attemptNumber <= $this->flightController->max_api_call_attempts; $attemptNumber++)
            {
                $pnr = $this->container->get('SabreServices')->createPassengerDetailsRequest($sabreVariables, $request->request->get('passengerNameRecord'), $marketingAirline);

                $this->container->get('FlightServices')->addFlightLog("Getting API PassengerDetailsRQ[$attemptNumber, ".$this->flightController->max_api_call_attempts."] with status:: ".$pnr->getPnrStatus().($pnr->getPnrStatus() == 'success' ? ', PNR:: '.$pnr->getPnrId() : ''));
                $this->container->get('FlightServices')->addFlightLog("With criteria[$attemptNumber, ".$this->flightController->max_api_call_attempts."]:: {criteria}", array('criteria' => array('request' => $pnr->getRequest(), 'response' => $pnr->getResponse())));

                if ($pnr->getFaultCode() && in_array($pnr->getFaultCode(), array('InvalidSecurityToken', 'InvalidEbXmlMessage'))) {

                    $response['redirectError'] = ($this->container->get('app.utils')->isCorporateSite()?'_corporate_flight':'_flight_booking');

                    $this->container->get('SabreServices')->cleanSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
                    $this->container->get('FlightServices')->addFlightLog("Requested API SessionCloseRQ[$attemptNumber, ".$this->flightController->max_api_call_attempts."]:: redirecting to ".$response['redirectError']);

                    return $response;
                }

                $operation_type = null;

                if ($pnr->getMessage())
                {
                    foreach ($operation_specs as $op_code => $op_message)
                    {
                        if (strpos($pnr->getMessage(), $op_message) !== false)
                        {
                            $operation_type = $op_code;

                            break;
                        }
                    }
                }

                if ($bookRequestFormSubmitAttemptNumber <= $this->flightController->max_api_call_attempts && $operation_type != null)
                {
                    $this->container->get('SabreServices')->cleanSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
                    $this->container->get('FlightServices')->addFlightLog("Clean Session[$attemptNumber, $bookRequestFormSubmitAttemptNumber]:: book request form submit, Received ".$operation_specs[$operation_type].", Retrying in a while.");

                    usleep($this->flightController->pause_between_retries_us);

                    if ($operation_type == 'INVALID_PRICE_QUOTE')
                    {
                        $sabreVariables['use_same_connection'] = true;

                        $this->createEnhancedAirBookRequest($sabreVariables, $requestData, $campaign_info);
                    }

                    $bookRequestFormSubmitAttemptNumber++;

                    return $this->bookRequestFormSubmit($passengerNameRecord, $requestData, $request, $sabreVariables, $validUnusedCoupon, $campaign_info, $bookRequestFormSubmitAttemptNumber);
                }

                if ($pnr->getPnrStatus() == 'success')
                    break;

                if ($attemptNumber != $this->flightController->max_api_call_attempts)
                    usleep($this->flightController->pause_between_retries_us);
            }

            if ($pnr->getPnrStatus() == 'error') {

                $this->flightController->data['error'] = $pnr->getMessage();
                return $this->flightController;

            } elseif ($pnr->getPnrStatus() == 'errors') {

                $this->flightController->data['errors'] = $pnr->getMessages();
                return $this->flightController;

            } elseif ($pnr->getPnrStatus() == 'success' && !$request->request->has('forwardToReviewTrip')) {

                $this->container->get('SabreServices')->closeSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
                $this->container->get('FlightServices')->addFlightLog('Requesting API SessionCloseRQ');


                $passengerNameRecord = $this->container->get('FlightRepositoryServices')->addFlightInfo($passengerNameRecord, $pnr, $requestData, $this->discount, $this->currencyPCC);

                $payment = $this->container->get('FlightRepositoryServices')->addPayment($passengerNameRecord, $pnr, $requestData, $this->discount, $this->currencyPCC, $request, $validUnusedCoupon, $campaign_info, $em, $this->methodOneByYourEmail);

                $paymentObj = $this->container->get('FlightRepositoryServices')->addPaymentObj($pnr, $payment['payment'], $payment['uuid'], $requestData, $this->currencyPCC, $payment['responseMessage'], $payment['responseCode'], $payment['paymentStatus'], $payment['displayTotalFare'], $request);

                $result = $this->flightController->paymentApproval($paymentObj, $this->container->getParameter('MODULE_FLIGHTS'));

                //  setcookie('TT-flight', '', 1);

                if (!isset($result["transaction_id"])) {
                    $result["transaction_id"] = null;
                }

                return $result;
            }
        }
    }


    /**
     * This will process the form validation using security token against encrypted FORM fields
     * and is being called during BOOK REQUEST action
     **/

    public function getServiceHash($requestData, $request, $sabreVariables)
    {
        $hiddenFields = [];
        $secToken     = [];
        //TODO: we should update the corporate to be compatible with the new version
        // this condition is made to be compatible also with the old version/ corprate
        if($request->request->has('flightrequest'))
        {
            $jsonMain = $request->request->get('flightrequest');
            $segmentsMain = json_decode($jsonMain, true);

            $startFlightIndex = end($segmentsMain['flightIndex']);
            $segments = $segmentsMain['segments'];
            $main = $segmentsMain['main'];

            $numberInParty=$main['number_in_party'];
            $currency=$main['currency'];
            $newPrice=$main['price'];
            $currencyCode=$main['currency_code'];
            $newConvertedPrice=$main['price_attr'];
            $originalPrice=$main['original_price'];

            array_push($secToken, $numberInParty, $currency);
            array_push($secToken, $newPrice, $currencyCode, $newConvertedPrice, $originalPrice);

            for($i = $startFlightIndex; $i < sizeof($segments); $i++) {
                $selectedSegment = $segments[$i];
                //
                array_push(
                    $secToken,
                    $selectedSegment['flight_number'],
                    $selectedSegment['airline_code'],
                    $selectedSegment['operating_airline_code'],
                    $selectedSegment['airline_name'],
                    $selectedSegment['operating_airline_name']
                );

                array_push(
                    $secToken,
                    $selectedSegment['origin_location'],
                    $selectedSegment['origin_location_city'],
                    $selectedSegment['origin_location_airport']
                );

                array_push($secToken,
                    $selectedSegment['destination_location'],
                    $selectedSegment['destination_location_city'],
                    $selectedSegment['destination_location_airport']
                );

                array_push(
                    $secToken,
                    $selectedSegment['total_flight_segments']
                );

                array_push(
                    $secToken,
                    $selectedSegment['departure_date_time']
                );

                array_push(
                    $secToken,
                    $selectedSegment['arrival_date_time']
                );

                array_push(
                    $secToken,
                    $selectedSegment['res_book_desig_code']
                );

                array_push(
                    $secToken,
                    $selectedSegment['fare_basis_code']
                );

                array_push(
                    $secToken,
                    $selectedSegment['flight_type']
                );

                array_push(
                    $secToken,
                    $selectedSegment['cabin'],
                    $selectedSegment['cabin_code']
                );

                array_push(
                    $secToken,
                    $selectedSegment['flight_duration']
                );

                if(!isset($selectedSegment['stop_duration']) || $selectedSegment['stop_duration']=="")
                    $selectedSegment['stop_duration'] = "0";
                array_push(
                    $secToken,
                    $selectedSegment['stop_duration']
                );

                if(!isset($selectedSegment['stop_indicator']) || $selectedSegment['stop_indicator']=="")
                    $selectedSegment['stop_indicator'] = "0";
                array_push(
                    $secToken,
                    $selectedSegment['stop_indicator']
                );

                if(!isset($selectedSegment['refundable']) || $selectedSegment['refundable']=="")
                    $selectedSegment['refundable'] = "0";
                array_push(
                    $secToken,
                    $selectedSegment['refundable']
                );

            }

            array_push(
                $secToken,
                $main['base_fare'],
                $main['taxes'],
                $main['taxes_attr'],
                $main['base_fare_attr'],
                $main['original_base_fare'],
                $main['original_taxes']
            );

            $multiDestination = "0";
            if(isset($main['multidestination']) && $main['multidestination']!="")
                $multiDestination = $main['multidestination'];
            $oneWay = "0";
            if(isset($main['oneway']) && $main['oneway']!="")
                $oneWay = $main['oneway'];
            //
            array_push($secToken, $multiDestination, $oneWay);
        }

        foreach ($request->request as $key => $hiddenField) {
            if ($key === 'passengerNameRecord' || $key === 'response' || preg_match('/_terminal_id/is', $key) || $key === 'forwardToReviewTrip') continue;

            $hiddenFields[$key] = $hiddenField;

            if (in_array($key, array('submit-booking', 'sec_token', 'from_mobile', 'access_token', 'returnedConversationId', 'pass', 'coupon_code', 'pin', 'tokenvalues','flightrequest', 'aircraft_type')))
                continue;

            if(!$request->request->has('flightrequest'))
            {
                $secToken[] = $hiddenField;
            }

        }

        sort($secToken, SORT_STRING);

        $secTokenStr = trim(implode(" ", $secToken));
        $secTokenStrCrypt = crypt($secTokenStr, $sabreVariables['salt']);

        //  $this->debug($secToken);
        //  $this->debug($request->request);
        //  echo $request->request->get('sec_token') . " = " . crypt($secTokenStr, $sabreVariables['salt']);

        $validUnusedCoupon = false;
        $couponCode = $requestData->getCouponCode();
        $from_mobile = $requestData->getFromMobile();

        $campaign_info = false;
        if ($couponCode) {
            $campaign_info = $this->flightController->validUnusedCouponsCampaign($couponCode, $this->container->getParameter('SOCIAL_ENTITY_FLIGHT'));

            $validUnusedCoupon = ($campaign_info !== false);
        } else {
            $campaign_info = $this->container->get('TTServices')->activeCampaigns(array('target_entity_type_id' => $this->container->getParameter('SOCIAL_ENTITY_FLIGHT')), false);

            if ($campaign_info) $campaign_info = $campaign_info[0];
        }

        if ($campaign_info !== false && $campaign_info) {
            if ($campaign_info['target_helper_text']) $campaign_info['target_helper_text'] = $this->translator->trans($campaign_info['target_helper_text']);
            else unset($campaign_info['target_helper_text']);
        }

        if (!$this->container->get('app.utils')->hash_equals($requestData->getSecToken(), $secTokenStrCrypt)) {
            if ($from_mobile == 1) {
                $ret['data']                 = array();
                $ret['status']               = '201';
                $ret['message']              = $this->translator->trans('The request could not be completed.');
                if (isset($campaign_info['target_helper_text'])) $ret['campaign_helper_text'] = $campaign_info['target_helper_text'];
                $res                         = new Response(json_encode($ret));
                $this->container->get('FlightServices')->addFlightLog('Sending MobileRQ with response: '.$res);
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            } else {
                $this->flightController->data['priceError']           = true;
                if (isset($campaign_info['target_helper_text'])) $this->flightController->data['campaign_helper_text'] = $campaign_info['target_helper_text'];
                $response                           = array();
                $response                           = $this->flightController->data;

                return $response;
            }
        }

        $response = array();
        $response['secTokenStr'] = $secTokenStr;
        $response['validUnusedCoupon'] = $validUnusedCoupon;
        $response['campaign_info'] = $campaign_info;
        $response['hiddenFields'] = $hiddenFields;

        return $response;
    }

    public function flightCancelation($controller, $payment, $from_mobile)
    {
        $response = array();

        $passengers       = $payment->getPassengerNameRecord()->getPassengerDetails();
        $pnrId            = $payment->getPassengerNameRecord()->getPnr();
        $multiDestination = $payment->getPassengerNameRecord()->getFlightInfo()->isMultiDestination();
        $oneWay           = $payment->getPassengerNameRecord()->getFlightInfo()->isOneWay();

        $flightSegments = array();
        $flightDetail   = $payment->getPassengerNameRecord()->getFlightDetails();

        foreach ($flightDetail as $index => $flight) {
            $flightInfo       = array();
            $departureAirport = $this->container->get('FlightRepositoryServices')->findAirport($flight->getDepartureAirport());
            $arrivalAirport   = $this->container->get('FlightRepositoryServices')->findAirport($flight->getArrivalAirport());

            $flightSegments[$flight->getType()]['destination_city'] = ($arrivalAirport) ? $arrivalAirport->getCity() : $flight->getArrivalAirport();

            $flightSegments[$flight->getType()]['destination_city'] = ($arrivalAirport) ? $arrivalAirport->getCity() : $flight->getArrivalAirport();

            if (!$flight->getStopIndicator()) {
                $flightSegments[$flight->getType()]['origin_city']         = ($departureAirport) ? $departureAirport->getCity() : $flight->getDepartureAirport();
                $flightSegments[$flight->getType()]['departure_main_date'] = ($arrivalAirport) ? $flight->getDepartureDateTime()->format('M j Y') : $flight->getArrivalAirport();
            }

            $mainAirline                                        = $this->container->get('FlightRepositoryServices')->findAirline($flight->getAirline());
            $flightSegments[$flight->getType()]['main_airline'] = ($mainAirline) ? $mainAirline->getAlternativeBusinessName() : $flight->getAirline();

            $flightInfo['departure_date']      = $flight->getDepartureDateTime()->format('D m, M');
            $flightInfo['departure_time']      = $flight->getDepartureDateTime()->format('g\:i A');
            $flightInfo['origin_city']         = ($departureAirport) ? $departureAirport->getCity() : $flight->getDepartureAirport();
            $flightInfo['origin_airport']      = ($departureAirport) ? $departureAirport->getName() : $flight->getDepartureAirport();
            $flightInfo['origin_airport_code'] = $flight->getDepartureAirport();

            $flightInfo['arrival_date']             = $flight->getArrivalDateTime()->format('D m, M');
            $flightInfo['arrival_time']             = $flight->getArrivalDateTime()->format('g\:i A');
            $flightInfo['destination_airport_code'] = $flight->getArrivalAirport();
            $flightInfo['destination_airport']      = ($arrivalAirport) ? $arrivalAirport->getName() : $flight->getArrivalAirport();
            $flightInfo['destination_city']         = ($arrivalAirport) ? $arrivalAirport->getCity() : $flight->getArrivalAirport();

            $airlineName                          = $this->container->get('FlightRepositoryServices')->findAirline($flight->getAirline());
            $flightInfo['airline_name']           = ($airlineName) ? $airlineName->getAlternativeBusinessName() : $flight->getAirline();
            $flightInfo['flight_number']          = $flight->getFlightNumber();
            $flightInfo['airline_code']           = $flight->getAirline();
            $flightInfo['operating_airline_code'] = $flight->getOperatingAirline();

            $cabinName                     = $this->container->get('FlightRepositoryServices')->FlightCabinFinder($flight->getCabin());
            $flightInfo['cabin']           = ($cabinName) ? $cabinName->getName() : $flight->getCabin();
            $flightInfo['flight_duration'] = $flight->getFlightDuration();
            $flightInfo['stop_indicator']  = $flight->getStopIndicator();
            $flightInfo['stop_info']       = "";

            if ($flightInfo['stop_indicator'] == 1 && $multiDestination) $flightSegments[$flight->getType()]['flight_info'][$index - 1]['stop_info'][] = $flightInfo;
            else $flightSegments[$flight->getType()]['flight_info'][]                          = $flightInfo;
        }

        $passengersArray = array();

        foreach ($passengers as $key => $passenger) {
            $passengersArray[$key]['first_name']             = $passenger->getFirstName();
            $passengersArray[$key]['surname']                = $passenger->getSurname();
            $passengersArray[$key]['type']                   = $passenger->getType();
            $passengersArray[$key]['fare_calc_line']         = $passenger->getFareCalcLine();
            $passengersArray[$key]['leaving_baggage_info']   = $passenger->getLeavingBaggageInfo();
            $passengersArray[$key]['returning_baggage_info'] = $passenger->getReturningBaggageInfo() == null ? "" : $passenger->getReturningBaggageInfo();
            $passengersArray[$key]['ticket_number']          = $passenger->getTicketNumber();
        }

        $flightSegments['base_fare']     = $payment->getPassengerNameRecord()->getFlightInfo()->getDisplayBaseFare();
        $flightSegments['taxes']         = $payment->getPassengerNameRecord()->getFlightInfo()->getDisplayTaxes();
        $flightSegments['price']         = $payment->getPassengerNameRecord()->getFlightInfo()->getDisplayPrice();
        $flightSegments['currency_code'] = $payment->getPassengerNameRecord()->getFlightInfo()->getDisplayCurrency();
        $getCurrencySymbole              = $this->container->get('CommonRepositoryServices')->currencyInfo($flightSegments['currency_code']);
        $flightSegments['currency']      = $getCurrencySymbole->getSymbol();

        $response['passengersArray'] = $passengersArray;
        $response['flightSegments'] = $flightSegments;
        $response['pnrId'] = $pnrId;
        $response['multiDestination'] = $multiDestination;
        $response['oneWay'] = $oneWay;

        return $response;
    }

    public function processFlightCancelation($controller, $payment, $sabreVariables, $em, $pnrId, $from_mobile, $flightCancellationAttemptNumber = 1)
    {
        $transactionId = $payment->getUuid();

        $sabreVariables['Service'] ="GetReservationRQ";// "TravelItineraryReadRQ";
        $sabreVariables['Action']  ="GetReservationRQ";// "TravelItineraryReadRQ";
        $travelItineraryRead = $this->container->get('SabreServices')->createRetrieveItineraryRequest($sabreVariables, $pnrId, array('fetch_airline_locators' => true));//$this->container->get('SabreServices')->createTravelItineraryRequest($sabreVariables, $pnrId);

        $controller->addFlightLog("Retrieving PNR API TravelItineraryReadRQ (UUID:: $transactionId, PNR:: $pnrId) with status:: ".$travelItineraryRead["status"]);
        $controller->addFlightLog("With criteria (UUID:: $transactionId, PNR:: $pnrId):: {criteria}", array('criteria' => $travelItineraryRead));

        if ($this->container->get('SabreServices')->TEST_MODE) {
            $travelItineraryRead["status"] = "success";
        }

        // TODO:: Fix ticket_list. We've already implemented, in the old implementation, a way to make users be able to select which tickets they wish to cancel.
        $ticket_list = array(); // List of tickets to cancel. In the old implementation, this was in SabreController, taken from $request->query->get('ticket_list', '')

        if ($ticket_list)
            $ticket_list = explode(',', $ticket_list);
        else
            $ticket_list = array();

        $db_valid_tickets = array();

        $passengers = $payment->getPassengerNameRecord()->getPassengerDetails();

        foreach ($passengers as $passenger)
        {
            if ($passenger->getTicketStatus() == 'Cancelled' OR $passenger->getTicketStatus() == 'VOIDED')
                continue;

            $db_valid_tickets[] = $passenger->getTicketNumber();
        }

        // TODO:: restore the line below once you fix ticket_list above.
        // $ticket_list = array_intersect($db_valid_tickets, $ticket_list); // Retain only tickets that we wish to cancel that are not yet cancelled.

        $ticket_list = $db_valid_tickets; // remove this when you fix ticket_list above. For the moment, consider the available valid (not yet cancelled) list of tickets.



        $cancelled_tickets = array(); // This will hold the list of tickets that have been successfully cancelled during the servicing of the current request.

        if ($travelItineraryRead["status"] == 'success') {


            // Cancel PNR with tickets
            $sabreVariables['Service'] = "VoidTicketLLSRQ";
            $sabreVariables['Action']  = "VoidTicketLLSRQ";
            $tickets = $travelItineraryRead['tickets'];

            $hasError = false;
            $voidAirTicket = array('faultcode' => '', 'status' => '', 'message' => '');

            foreach ($tickets as $RPH => $ticket)
            {
                if ($ticket_list && !in_array($ticket, $ticket_list))
                    continue;

                for ($i = 0; $i <= 1; $i++)
                {
                    $voidAirTicket = $this->container->get('SabreServices')->voidAirTicketRequest($sabreVariables, $RPH);


                    $controller->addFlightLog(($i == 1?'Voided':'Confirmed voiding')." ticket $ticket - API VoidTicketLLSRQ (UUID:: $transactionId, PNR:: $pnrId) with status:: ".$voidAirTicket['status']);
                    $controller->addFlightLog("With criteria (UUID:: $transactionId, PNR:: $pnrId):: {criteria}", array('criteria' => $voidAirTicket));

                    if ($voidAirTicket['status'] == 'error')
					{
						$hasError = true;
						break;
					}
                }

                if ($hasError) {
                    break;
                }

                if (!in_array($ticket, $cancelled_tickets))
                    $cancelled_tickets[] = $ticket;
            }

            if ($this->container->get('SabreServices')->TEST_MODE) {
                $voidAirTicket["status"] = "success";
            }

            if ($voidAirTicket['status'] == 'success') {
                $sabreVariables['Service'] = "OTA_CancelLLSRQ";
                $sabreVariables['Action']  = "OTA_CancelLLSRQ";
                $cancelRq = $this->container->get('SabreServices')->OTACancelRequest($sabreVariables);


                $controller->addFlightLog("Cancelled PNR segments - API OTA_CancelLLSRQ (UUID:: $transactionId, PNR:: $pnrId) with status:: ".$cancelRq['status']);
                $controller->addFlightLog("With criteria (UUID:: $transactionId, PNR:: $pnrId) :: {criteria}", array('criteria' => $cancelRq));

                if ($this->container->get('SabreServices')->TEST_MODE) {
                    $voidAirTicket["status"] = "success";
                }

                if ($cancelRq['status'] == 'success') {
                    $sabreVariables['Service'] = "EndTransactionLLSRQ";
                    $sabreVariables['Action']  = "EndTransactionLLSRQ";

                    for ($attemptNumber = 1; $attemptNumber <= $controller->max_api_call_attempts; $attemptNumber++)
                    {
                        $endTransaction = $this->container->get('SabreServices')->endTransactionRequest($sabreVariables);
                        $controller->addFlightLog("Saved transaction - API EndTransactionRQ[$attemptNumber, $flightCancellationAttemptNumber] (UUID:: $transactionId, PNR:: $pnrId) with status:: ".$endTransaction['status'].($endTransaction['status'] == 'success' ? ', PNR:: '.$endTransaction['pnr']:''));
                        $controller->addFlightLog("With criteria[$attemptNumber, $flightCancellationAttemptNumber] (UUID:: $transactionId, PNR:: $pnrId):: {criteria}", array('criteria' => $endTransaction));

                        if ($flightCancellationAttemptNumber <= $controller->max_api_call_attempts && $endTransaction['app_results']['message'] && (strpos($endTransaction['app_results']['message'], 'IGN AND RETRY') !== false || strpos($endTransaction['app_results']['message'], 'USE IR TO IGNORE AND RETRIEVE PNR') !== false))
                        {
                            $this->container->get('SabreServices')->cleanSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
                            $controller->addFlightLog("Cleaned session[$attemptNumber, $flightCancellationAttemptNumber] (UUID:: $transactionId, PNR:: $pnrId) - Flight Cancellation - Received 'IGN AND RETRY' / 'USE IR TO IGNORE AND RETRIEVE PNR', Retrying in a while.");

                            usleep($controller->pause_between_retries_us);

                            $controller->addFlightLog("processFlightCancelation[$attemptNumber, $flightCancellationAttemptNumber] (UUID:: $transactionId, PNR:: $pnrId) [".($ticket_list?$this->container->get('app.utils')->flatten_array($ticket_list):'')."]:: Received 'IGN AND RETRY' / 'USE IR TO IGNORE AND RETRIEVE PNR', Retrying...");
							
							$flightCancellationAttemptNumber++;
							
                            return $this->processFlightCancelation($controller, $payment, $sabreVariables, $em, $pnrId, $from_mobile, $flightCancellationAttemptNumber);
                        }

                        if ($endTransaction['status'] == 'success')
                            break;

                        if ($attemptNumber != $controller->max_api_call_attempts)
                            usleep($controller->pause_between_retries_us);
                    }

                    if ($this->container->get('SabreServices')->TEST_MODE) {
                        $endTransaction["status"] = "success";
                    }

                    if ($endTransaction['status'] == 'success') {
                        // $passengers = $payment->getPassengerNameRecord()->getPassengerDetails();

                        foreach ($passengers as $key => $passenger) {

                            if (in_array($passenger->getTicketNumber(), $ticket_list)) // ensure we wished to cancel this ticket

                                $passenger->setTicketStatus('VOIDED');//Cancelled

                        }

                        // !$ticket_list is being used here to permit cancelling all tickets when we send the cancellation request without specifying a ticket.
                        // So sending a request to cancel tickets for a specific PNR or UUID (transactionId) without specifying the ticket list means cancel all tickets for this transaction/PNR.
                        // Set the PNR status to CANCELLED only if all tickets for this PNR/transaction have been cancelled. Otherwise, the PNR status should remain intact.
                        if (!$ticket_list || count($cancelled_tickets) == count($db_valid_tickets))
                            $payment->getPassengerNameRecord()->setStatus("VOIDED");//CANCELLED

                        if ($controller->data['is_corporate_account']) {
                            $payment->setStatus('88');
                            $payment->setResponseCode('88888');
                            $payment->setResponseMessage('VOIDED');
                            $payment->setCommand('PURCHASE');
                            $payment->setUpdatedDate(new \DateTime("now"));

                            //$em = $this->getDoctrine()->getManager();
                            $em->persist($payment);

                            $em->flush();
                        } else {
                            $params = array("uuid" => $transactionId, "operation" => "REFUND", "type" => 'flight');

                            for ($attemptNumber = 1; $attemptNumber <= $controller->max_api_call_attempts; $attemptNumber++) {

                                //$refund = $this->container->get('PayFortServices')->refundCaptureService($payment, $params);
                                //$refundData = json_decode($refund, true);
                                $refund  =  $this->container->get('PaymentServiceImpl')->refund($transactionId);
                                $controller->addFlightLog("Refund[$attemptNumber] (UUID:: $transactionId, PNR:: $pnrId) status:: {criteria}", array('criteria' => $refund['data']));

                                if (isset($refund['success']) && $refund['success'] == 1) {
                                    break;
                                }

                                if ($attemptNumber != $controller->max_api_call_attempts) usleep($controller->pause_between_retries_us);
                            }
                        }

                        $controller->data['enableCancelation'] = $this->enableCancelation;

                        $emailData = $this->container->get('FlightServices')->addEmailData($controller, $payment, $transactionId);
                        $msg = $this->templating->render('emails/flight_email_cancellation_new.twig', array('emailData' => $emailData));
                        $title = $this->translator->trans('TouristTube Flight Cancellation');

                        $this->container->get('EmailServices')->addEmailData($controller->data["email"], $msg, $title, $title, 0);

                        //sending to accounting email as well
                        if (isset($controller->data['is_corporate_account']) && $controller->data['is_corporate_account']) {
                            $userId = $payment->getUserId();
                            $userArray = $this->container->get('UserServices')->getUserDetails(array('id' => $userId));
                            $accountingEmail = $userArray[0]['accountingEmail'];

                            if($accountingEmail){
                                $subject = $userArray[0]['accountName'] . ': ' . $title;
                                $this->container->get('EmailServices')->addEmailData($accountingEmail, $msg, $subject, $title, 0);
                            }
                        }
                        if ($from_mobile == 1) {

                            $this->container->get('SabreServices')->closeSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
                            $controller->addFlightLog("Flight Cancellation:: MobileRQ - Close session[$attemptNumber, $flightCancellationAttemptNumber] (UUID:: $transactionId, PNR:: $pnrId) - Flight Cancellation");

                            $response = array('data' => [], 'status' => '14', 'response_code' => '14000', 'response_message' => 'Success');
                            $res = new Response(json_encode($response));
                            // $this->container->get('FlightServices')->addFlightLog
                            $controller->addFlightLog("Sending MobileRQ cancel ticket (UUID:: $transactionId, PNR:: $pnrId) with response:: ".$res);
                            $res->headers->set('Content-Type', 'application/json');
                            return $res;
                        }

                        $response = array();
                        $response = $controller->data;
                        $response['nodata'] = 0;
                        return $response;
                    } else {
                        $error = $this->translator->trans("Error! can't save transaction");
                        $controller->addFlightLog("Error! can't save transaction with criteria (UUID:: $transactionId, PNR:: $pnrId):: {criteria}", array('criteria' => $endTransaction));
                    }
                } else {
                    $error = $this->translator->trans("Error! can't cancel PNR");
                    $controller->addFlightLog("Error! can't cancel PNR with criteria (UUID:: $transactionId, PNR:: $pnrId):: {criteria}", array('criteria' => $cancelRq));
                }
            } else {
                $error = $this->translator->trans("Error! can't void air ticket");
                $controller->addFlightLog("Error! can't void air ticket with criteria (UUID:: $transactionId, PNR:: $pnrId):: {criteria}", array('criteria' => $voidAirTicket));
            }

        } else {
            $error = $this->translator->trans("Error! can't get passenger name record");
            $controller->addFlightLog("Error! can't get passenger name record with criteria (UUID:: $transactionId, PNR:: $pnrId):: {criteria}", array('criteria' => $travelItineraryRead));
        }

        $this->container->get('SabreServices')->closeSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
        $controller->addFlightLog("Flight Cancellation:: Close session[$attemptNumber, $flightCancellationAttemptNumber] (UUID:: $transactionId, PNR:: $pnrId) - Flight Cancellation");
    }

    public function flightCancellationPayment($controller, $pnr, $uuid)
    {
        $passengers     = $pnr->getPassengerNameRecord()->getPassengerDetails();
        $flightDetail   = $pnr->getPassengerNameRecord()->getFlightDetails();
        $flightSegments = $this->container->get('MyBookingServices')->flightDetails($flightDetail);

        $userEmail = $pnr->getPassengerNameRecord()->getEmail();

        $now                = new \DateTime();
        $raw_departure_date = new \DateTime($flightSegments['leaving']['flight_info'][0]['raw_departure_date']);

        if ($raw_departure_date < $now) {
            $flightSegments['upcoming'] = 0;
        } else {
            $flightSegments['upcoming'] = 1;
        }

        $flightSegments['leaving']['transaction_id']   = $uuid;
        $flightSegments['leaving']['multiDestination'] = $pnr->getPassengerNameRecord()->getFlightInfo()->isMultiDestination();

        $passengersArray = array();

        foreach ($passengers as $key => $passenger) {
            $passengersArray[$key]['first_name']             = $passenger->getFirstName();
            $passengersArray[$key]['surname']                = $passenger->getSurname();
            $passengersArray[$key]['type']                   = $passenger->getType();
            $passengersArray[$key]['fare_calc_line']         = $passenger->getFareCalcLine();
            $passengersArray[$key]['leaving_baggage_info']   = $passenger->getLeavingBaggageInfo();
            $passengersArray[$key]['returning_baggage_info'] = $passenger->getReturningBaggageInfo() == null ? "" : $passenger->getReturningBaggageInfo();
            $passengersArray[$key]['ticket_number']          = $passenger->getTicketNumber();
        }

        $flightSegments['base_fare'] = number_format($pnr->getPassengerNameRecord()->getFlightInfo()->getDisplayBaseFare(), 2, '.', ',');
        $flightSegments['taxes']     = number_format($pnr->getPassengerNameRecord()->getFlightInfo()->getDisplayTaxes(), 2, '.', ',');

        //$priceInfo = $this->get('PayFortServices')->getPriceInfo($pnr);

        $flightSegments['price']         = number_format($pnr->getPassengerNameRecord()->getFlightInfo()->getDisplayPrice(), 2, '.', ',');
        $flightSegments['currency_code'] = $pnr->getPassengerNameRecord()->getFlightInfo()->getDisplayCurrency();
        $symbol                          = $this->container->get('CommonRepositoryServices')->currencyInfo($flightSegments['currency_code']);

        if ($pnr->getCouponCode() && $pnr->getDisplayAmount() != $pnr->getDisplayOriginalAmount()) {
            $flightSegments['discounted_price'] = round($pnr->getDisplayAmount() + 0, 2);
        }

        $flightSegments['currency']   = $symbol ? $symbol->getSymbol() : $flightSegments['currency_code'];
        $flightSegments['refundable'] = $pnr->getPassengerNameRecord()->getFlightInfo()->isRefundable();
        $flightSegments['pnr_id']     = $pnr->getPassengerNameRecord()->getPnr();
        $flightSegments['id']         = $pnr->getPassengerNameRecord()->getId();

        //}
        # --------- PATMENT GATEWAY STUFF HERE ------------#

        $payment                 = $this->container->get('FlightServices')->validatePayment($uuid);
        $parameters              = $this->container->get('PayFortServices')->tokenizationService($uuid, $payment->getType());
        $signature               = $this->container->get('PayFortServices')->generateSignature($parameters, 'RQ');

        $payFortServiceParams = $this->container->get('PayFortServices')->getServiceParameters($payment, $parameters);
        $controller->addFlightLog('PayFortServices_Params with response:: {criteria}', array('criteria' => $payFortServiceParams));


        $masterPassParameters                   = $payFortServiceParams;
        $masterPassParameters['digital_wallet'] = 'MASTERPASS';
        $masterPassParameters['signature']      = $this->container->get('PayFortServices')->generateSignature($masterPassParameters, 'RQ');
        $controller->addFlightLog('PayFortServices_MasterSignature:: '.$masterPassParameters['signature']);

        $visaCheckOutParameters                   = $payFortServiceParams;
        unset($visaCheckOutParameters['cart_details']);
        $visaCheckOutParameters['digital_wallet'] = 'VISA_CHECKOUT';
        $visaCheckOutParameters['signature']      = $this->container->get('PayFortServices')->generateSignature($visaCheckOutParameters, 'RQ');
        $controller->addFlightLog('PayFortServices_VISASignature:: '.$visaCheckOutParameters['signature']);

        $response = array();
        $response['flightSegments'] = $flightSegments;
        $response['passengersArray'] = $passengersArray;
        $response['payment'] = $payment;
        $response['parameters'] = $parameters;
        $response['signature'] = $signature;
        $response['masterPassParameters'] =  $masterPassParameters;
        $response['visaCheckOutParameters'] = $visaCheckOutParameters;

        return $response;
    }

    public function createPnrApi($controller, $pnr, $pnrData, $methodOneByYourEmail, $currencyPCC, $discount, $couponCode)
    {
        $createPnrApi = $this->container->get('FlightRepositoryServices')->creatPnrAPI($controller, $pnr, $pnrData, $methodOneByYourEmail, $currencyPCC, $discount);

        $payment = $createPnrApi['payment'];
        $displayTotalFare = $createPnrApi['displayTotalFare'];
        $displayedCurrency = $createPnrApi['displayedCurrency'];

        $campaign_info = false;
        if ($couponCode) $campaign_info = $controller->validUnusedCouponsCampaign($couponCode, $this->container->getParameter('SOCIAL_ENTITY_FLIGHT'));

        if ($campaign_info === false) {
            $payment->setAmount($pnr['pricingInfo']['TotalFare']);
            $payment->setDisplayAmount($displayTotalFare);
        } else {
            $discountedAmountInfo = $controller->applyDiscount($campaign_info['c_discountId'], $campaign_info['currency_code'], $currencyPCC, $pnr['pricingInfo']['TotalFare']);

            if ($discountedAmountInfo['status']) {
                $payment->setAmount($discountedAmountInfo['amount']);

                $discountedAmountInfo = $controller->applyDiscount($campaign_info['c_discountId'], $campaign_info['currency_code'], $displayedCurrency, $displayTotalFare);
                $payment->setDisplayAmount($discountedAmountInfo['amount']);

                $payment->setCampaignId($campaign_info['c_id']);
                $payment->setCouponCode($couponCode);
            }
        }

        $response = array();

        $response['payment'] = $payment;
    }

    public function myFlightDetails($pnr, $uuid)
    {
        $passengersArray = array();

        $passengers     = $pnr->getPassengerNameRecord()->getPassengerDetails();
        $flightDetail   = $pnr->getPassengerNameRecord()->getFlightDetails();
        $flightSegments = $this->container->get('MyBookingServices')->flightDetails($flightDetail);

        $userEmail = $pnr->getPassengerNameRecord()->getEmail();

        $now                = new \DateTime();
        $raw_departure_date = new \DateTime($flightSegments['leaving']['flight_info'][0]['raw_departure_date']);

        if ($raw_departure_date < $now) {
            $flightSegments['upcoming'] = 0;
        } else {
            $flightSegments['upcoming'] = 1;
        }

        $flightSegments['leaving']['transaction_id']   = $uuid;
        $flightSegments['leaving']['multiDestination'] = $pnr->getPassengerNameRecord()->getFlightInfo()->isMultiDestination();

        foreach ($passengers as $key => $passenger) {
            $passengersArray[$key]['first_name']             = $passenger->getFirstName();
            $passengersArray[$key]['surname']                = $passenger->getSurname();
            $passengersArray[$key]['type']                   = $passenger->getType();
            $passengersArray[$key]['fare_calc_line']         = $passenger->getFareCalcLine();
            $passengersArray[$key]['leaving_baggage_info']   = $passenger->getLeavingBaggageInfo();
            $passengersArray[$key]['returning_baggage_info'] = $passenger->getReturningBaggageInfo() == null ? "" : $passenger->getReturningBaggageInfo();
            $passengersArray[$key]['ticket_number']          = $passenger->getTicketNumber();
        }

        $flightSegments['base_fare'] = number_format($pnr->getPassengerNameRecord()->getFlightInfo()->getDisplayBaseFare(), 2, '.', ',');
        $flightSegments['taxes']     = number_format($pnr->getPassengerNameRecord()->getFlightInfo()->getDisplayTaxes(), 2, '.', ',');

        //$priceInfo = $this->get('PayFortServices')->getPriceInfo($pnr);

        $flightSegments['price']         = number_format($pnr->getPassengerNameRecord()->getFlightInfo()->getDisplayPrice(), 2, '.', ',');
        $flightSegments['currency_code'] = $pnr->getPassengerNameRecord()->getFlightInfo()->getDisplayCurrency();
        //$symbol                          = $this->getDoctrine()->getRepository('TTBundle:CurrencyRate')->findOneBycurrencyCode($flightSegments['currency_code']);
        $symbol                          = $this->container->get('CommonRepositoryServices')->currencyInfo($flightSegments['currency_code']);

        if ($pnr->getCouponCode() && $pnr->getDisplayAmount() != $pnr->getDisplayOriginalAmount()) {
            $flightSegments['discounted_price'] = round($pnr->getDisplayAmount() + 0, 2);
        }

        $flightSegments['currency']   = $symbol ? $symbol->getSymbol() : $flightSegments['currency_code'];
        $flightSegments['refundable'] = $pnr->getPassengerNameRecord()->getFlightInfo()->isRefundable();
        $flightSegments['pnr_id']     = $pnr->getPassengerNameRecord()->getPnr();
        $flightSegments['id']         = $pnr->getPassengerNameRecord()->getId();

        $response = array();

        $response['flightSegments'] = $flightSegments;
        $response['passengersArray'] = $passengersArray;

        return $response;
    }

}
