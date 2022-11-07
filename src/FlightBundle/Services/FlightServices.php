<?php
namespace FlightBundle\Services;

use FlightBundle\Controller\FlightController;
use FlightBundle\vendors\sabre\PassengerNormaliser;
use FlightBundle\vendors\sabre\PassengerHandler;
use FlightBundle\vendors\sabre\SabreHandler;
use FlightBundle\vendors\sabre\FlightHandler;

use FlightBundle\Entity\PassengerNameRecord;
use FlightBundle\Entity\PassengerDetail;
use FlightBundle\Entity\FlightDetail;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class FlightServices
{
    protected $SabreflightServices;
    private $passengerNormaliser;
    private $em;
    private $translator;
    private $passengerHandler;
    private $sabreHandler;
    private $flightHandler;
    private $container;
    private $templating;
    
    public function __construct(FlightController $SabreflightServices, PassengerNormaliser $passengerNormaliser, EntityManager $em, Container $container, PassengerHandler $passengerHandler, SabreHandler $sabreHandler, FlightHandler $flightHandler)
    {
        $this->SabreflightServices = $SabreflightServices;
        $this->passengerNormaliser = $passengerNormaliser;
        $this->em = $em;
        $this->container = $container;
        $this->translator = $container->get('translator');
        $this->templating = $container->get('templating');
        $this->passengerHandler = $passengerHandler;
        $this->sabreHandler = $sabreHandler;
        $this->flightHandler = $flightHandler;
    }
    
    public function flightAvailabilitySearch()
    {
        $response = array();
        
        $response = $this->SabreflightServices->flightBookingResultAction();
        
        return $response;
        
    }
    public function flightAvailabilitySearchNew()
    {
        $response = array();
        
        $response = $this->SabreflightServices->flightBookingResultNewAction();

        return $response;
        
    }
    public function bookAvailableFlight($seotitle, $seodescription, $seokeywords, $isCorporate = 0)
    {
        $response = array();
 
        $response = $this->SabreflightServices->bookFlightAction($seotitle, $seodescription, $seokeywords, $isCorporate);
 
        return $response;
        
    }
    
    public function formSubmitedPnr($seotitle, $seodescription, $seokeywords, $isCorporate = 0)
    {
        
        $response = array();
     
        $response = $this->SabreflightServices->bookFlightAction($seotitle, $seodescription, $seokeywords, $isCorporate);
        
        return $response;
        
    }
    
    
    public function issueAirTicket()
    {
        return $this->SabreflightServices->issueAirTicketAction();        
    }
	
    // service for cancellation
    public function flightCancelation($seotitle, $seodescription, $seokeywords)
    {
        
        $response = array();
        
        $response = $this->SabreflightServices->flightCancelationAction($seotitle, $seodescription, $seokeywords);
        
        return $response;
        
    }
    public function flightDetails($seotitle, $seodescription, $seokeywords, $reservationId = 0)
    {

        $response = array();

        $response = $this->SabreflightServices->myFlightDetailsAction($seotitle, $seodescription, $seokeywords, $reservationId);

        return $response;

    }

   public function FlightReservationDetails($seotitle, $seodescription, $seokeywords,$reservationId)
    {

        $response = array();

        $response = $this->SabreflightServices->myFlightDetailsAction($seotitle, $seodescription, $seokeywords,$reservationId);

        return $response;

    }

    public function flightAvailabilityChecker($reservationId,$moduleId,$accountId,$transactionUserId,$requestServicesDetailsId,$seotitle, $seodescription, $seokeywords)
    {
        $response = array();
        
        $response = $this->SabreflightServices->flightDetailsCheckAction($reservationId,$moduleId,$accountId,$transactionUserId,$requestServicesDetailsId,$seotitle, $seodescription, $seokeywords);
       
        return $response;
        
    }

    public function sendEmailFromFlightDetails($controller, $email, $transaction_id)
    {
        $response = array();

        $payment = $this->em->getRepository('PaymentBundle:Payment')->find($transaction_id);
        $emailData = $this->addEmailData($controller, $payment, $transaction_id);
        
        $response = $this->SabreflightServices->sendEmailFromFlightDetailsAction($email, $emailData);

        return $response;
    }


    /**
     * This will generate email data as array, necessary for twig variables
     * @param object $payment, string $transaction_id
     * @return array $response
     **/
    public function addEmailData($controller, $payment, $transaction_id)
    {
        $response = array();

        $response = $this->passengerHandler->addEmailData($controller, $payment, $transaction_id);

        
        return $response;
    }
    
   public function bookRequest($request)
    {

        $this->addFlightLog('Selected booking result with criteria: {criteria}', array('criteria' => $request->request->all()));
	    $bookRequest = $this->sabreHandler->bookRequest($request);
	    
        return $bookRequest;
    }

   /**
    * Generate PIN and email
    *
    */
   public function generatePinToEmail($thisData, $user, $em)
   {
        if ($thisData->user_pin_validation_mode && $thisData->data['is_corporate_account'] && $user) {
                $new_pin = $this->container->get('app.utils')->randomString(7);

                $user->setCorporateAccountPin($new_pin);
                $em->persist($user);
                $em->flush();

                $email_data                = array('user_name' => $user->getYourusername(), 'pin' => $new_pin);
                $pin_message               = $this->templating->render('emails/email_pin.twig', $email_data);
                $pin_message_subject_title = $this->translator->trans('Your new TouristTube PIN');
                $this->container->get('EmailServices')->addEmailData($thisData->data['user_email'], $pin_message, $pin_message_subject_title, $pin_message_subject_title, 0);
            }
   }

 /*   public function getFlightsDiscount()
    {
        $discount = $this->container->get('FlightRepositoryServices')->getFlightsDiscount();
        return $discount;
    }*/

   public function createEnhancedAirBookRequest($sabreVariables, $requestData, $campaign_info)
   {

        $createEnhancedAirBookRequest = $this->sabreHandler->createEnhancedAirBookRequest($sabreVariables, $requestData, $campaign_info);
        return $createEnhancedAirBookRequest;
   }

   public function bookRequestFormSubmit($passengerNameRecord, $requestData, $request, $sabreVariables, $validUnusedCoupon, $campaign_info)
   {

        $bookRequestFormSubmit = $this->sabreHandler->bookRequestFormSubmit($passengerNameRecord, $requestData, $request, $sabreVariables, $validUnusedCoupon, $campaign_info);
     return  $bookRequestFormSubmit;
   }
    public function PnrCreation($passengerNameRecord, $requestData, $request, $sabreVariables, $validUnusedCoupon, $campaign_info)
    {

        $PnrCreation = $this->sabreHandler->PnrCreation($passengerNameRecord, $requestData, $request, $sabreVariables, $validUnusedCoupon, $campaign_info);

        $response['pnrId']=$PnrCreation['pnr']->getPnrId();
        $response['pnrStatus']=$PnrCreation['pnr']->getPnrStatus();
        $response['passengers']=$PnrCreation['pnr']->getPassengers();
        $response['message']=$PnrCreation['pnr']->getMessage();
        $response['messages']=$PnrCreation['pnr']->getMessages();
        $response['faultCode']=$PnrCreation['pnr']->getFaultCode();
        $response['pricingInfo']=$PnrCreation['pnr']->getPricingInfo();
        $response['fareBasisCodes']=$PnrCreation['pnr']->getFareBasisCodes();
        $response['airline_pnr']=$PnrCreation['pnr']->getAirlinePnr();
        if (!isset($PnrCreation["transaction_id"])) {
            $response["transaction_id"] = null;
        }else{
            $response["transaction_id"] = $PnrCreation['transaction_id'];
        }
        return $response;
    }
   
   public function getServiceHash($requestData, $request, $sabreVariables)
   {
	   $serviceHash = $this->sabreHandler->getServiceHash($requestData, $request, $sabreVariables);
	   return $serviceHash;
   }
   
   public function doflightCancelation($controller, $payment, $from_mobile)
   {
	   $flightCancelation = $this->sabreHandler->flightCancelation($controller, $payment, $from_mobile);
	   
	   return $flightCancelation;
   }
   
   public function processFlightCancelation($controller, $payment, $sabreVariables, $em, $pnrId, $from_mobile)
   {
	   $processFlightCancelation = $this->sabreHandler->processFlightCancelation($controller, $payment, $sabreVariables, $em, $pnrId, $from_mobile);
	   return $processFlightCancelation;
   }
   
   public function flightCancellationPayment($controller, $pnr, $uuid)
   {
	   $flightCancellationPayment = $this->sabreHandler->flightCancellationPayment($controller, $pnr, $uuid);
	   return $flightCancellationPayment;
   }
   
   public function createPnrApi($controller, $pnr, $pnrData, $methodOneByYourEmail, $currencyPCC, $discount, $couponCode)
   {
	   $createPnrApi = $this->sabreHandler->creatPnrAPI($controller, $pnr, $pnrData, $methodOneByYourEmail, $currencyPCC, $discount, $couponCode);
	   return $createPnrApi;
   }
   
   public function myFlightDetails($pnr, $uuid)
   {
	   $myFlightDetails = $this->sabreHandler->myFlightDetails($pnr, $uuid);
	   return $myFlightDetails;
   }

    /**
     * This will return payment object if condition is true
     * @param string $transactionId
     * @return object $payment
     **/
    public function validatePayment($transactionId)
    {
        $request  = Request::createFromGlobals();
        $payment = $this->em->getRepository('PaymentBundle:Payment')->findOneByUuid($transactionId);

        $from_mobile = $request->request->get('from_mobile');

        if (!$payment) {
            $error = $this->translator->trans('Error! No transaction found'); // Must refund payment
            if ($from_mobile == 1) {
                $response = $this->get('PayFortServices')->responseData('', '00', '00103', $error);
                $res      = new Response(json_encode($response));
                $this->addFlightLog('Sending MobileRQ with response: '.$res);
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }

            return $this->redirectToRoute('_flight_booking', array('error' => $error));
        }

        if ($payment->getStatus() != 14) {
            $error = $this->translator->trans('We are unable to issue your ticket. For any inquiry, kindly send us an email at flights-support@touristtube.com'); // Must refund payment
            if ($from_mobile == 1) {
                $response = $this->container->get('PayFortServices')->responseData('', '00', '00103', $error);
                $res      = new Response(json_encode($response));
                $this->addFlightLog('Sending MobileRQ with response: '.$res);
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }
            return $error;
            // return $this->render('@Flight/flight/flight-info.twig', $this->data);
        }

        return $payment;
    }
    
    public function isPassportRequired($requestData)
    {
		if (!$requestData)
			return 0;
		
       $internal_flights = $this->container->getParameter('INTERNAL_FLIGHTS');
       $international_flights = $this->container->getParameter('INTERNATIONAL_FLIGHTS');

        $passportRequired = 0;

        $originLocations = $requestData->getOriginLocation();
		
		if ($originLocations) {
			
			$destinationLocations = $requestData->getDestinationLocation();
			
			foreach($originLocations as $i => $originLocation) {
				
				// INTERNAL FLIGHT (same origin and destination code)
				if($originLocation == $destinationLocations[$i]) {
					
					if(in_array($originLocation, $internal_flights['countries'])) {
						
						$passportRequired = 1;
						
						break;
					}
				}
				else if(in_array($originLocation, $international_flights['countries']) || in_array($destinationLocations[$i], $international_flights['countries'])) {
					
					// INTERNATIONAL FLIGHT (use both codes to check)
					
					$passportRequired = 1;
					
					break;
				}
			}
		}
		
		if (!$passportRequired) {
			
			// continue checking using AIRLINE codes
			
			$airlineCodes = $requestData->getAirlineCode();
			
			if ($airlineCodes) {
				
				foreach($airlineCodes as $airlineCode) {
					
					if(in_array($airlineCode, $internal_flights['airlines']) || in_array($airlineCode, $international_flights['airlines'])) {
						
						$passportRequired = 1;
						
						break;
					}
				}
			}
		}

        /*
		foreach($requestData->getFlightSegments() as $segment)
        {
            $flightInfo = $segment['flight_info'];
            foreach($flightInfo as $info)
            {
                $departureCountry = $this->container->get('FlightRepositoryServices')->findCountry($info["origin_airport_code"])->getCountry();
                $arrivalCountry   = $this->container->get('FlightRepositoryServices')->findCountry($info["destination_airport_code"])->getCountry();

                if ($departureCountry == 'CN' && $arrivalCountry == 'CN') {
                        $passportRequired = 1;
                }
            }
        }
		*/
		
        return $passportRequired;
    }
    
    /**
     * this is created to add Log to the Flight, thi is a very usefull way to debbug a problem, and track the flight action
     * @param String $message
     * @param array $params
     * @param boolean $cleanParams
     */
    public function addFlightLog($message, $params = array(), $cleanParams = false)
    {
    if (!isset($params) || !is_array($params))
        $params = array();

    if ($params)
    {
        if ($cleanParams)
    {
            foreach (array_keys($params) as $param_key)
            {
                $this->SabreflightServices->cleanParams($params[$param_key]);
            }
    }

    foreach (array_keys($params) as $param_key)
    {
            $params[$param_key] = json_encode($params[$param_key]);
    }
    }
    $params['userId'] = ($this->container->get('ApiUserServices')->isUserLoggedIn() ? $this->SabreflightServices->userGetID() : 0);

        $logger = $this->container->get('monolog.logger.flights');
        $logger->info("\nUser {userId} - ".$message, $params);
    }

    /*
     * Check if transacrion made on Corproate site or not
     */
    public function isCorporateSiteSource($transactionId)
    {
        $pnrObject = $this->em->getRepository('FlightBundle:PassengerNameRecord')->findOneByPaymentUUID($transactionId);

        return ($pnrObject->getIsCorporateSite());
    }


    /*
     * Check if transacrion made on Corproate site or not
     */
    public function getPaymentTransactionUsingPnrID($pnrID)
    {
        $pnrObject = $this->em->getRepository('FlightBundle:PassengerNameRecord')->findOneById($pnrID);

        return ($pnrObject->getPaymentUUID());
    }

    /*
     * Get passenger name record using the pnr
     */
    public function getPassengerDetailsFromPnr($pnr,$transactionId)
    {
        $pnrObject = $this->em->getRepository('FlightBundle:PassengerNameRecord')->findOneBy(array('pnr' => $pnr, 'paymentUUID' => $transactionId));

        if($pnrObject)
        {
            $response['success'] = true;
            $passengerNumber = 1;

            //money Information
           $response['displayOriginalAmount'] =  $pnrObject->getFlightInfo()->getDisplayPrice();
           $response['displayCurrency'] =  $pnrObject->getFlightInfo()->getDisplayCurrency();
           $response['displayBaseFare'] =  $pnrObject->getFlightInfo()->getDisplayBaseFare();
           $response['displayTaxes'] =  $pnrObject->getFlightInfo()->getDisplayTaxes();

           // flight details

           if($pnrObject->getFlightInfo()->isOneWay()){
                $response['flight_type'] = 'Oneway';
           }elseif($pnrObject->getFlightInfo()->isMultiDestination()){
                $response['flight_type'] = 'Multidestination';
           }else{
                $response['flight_type'] = 'Roundtrip';
           }

           $response['tickets'] = array();
           $response['tickets']['count'] = 0;

        $passengerArray = $pnrObject->getFlightDetails();

            foreach($passengerArray as  $arrivalInformation) {


               if($arrivalInformation->getSegmentNumber() == 1)
               {
                   $response['departureDate'] =  $arrivalInformation->getDepartureDateTime()->format('D d/m');
                   $response['departureAirport'] =  $arrivalInformation->getDepartureAirport();
               }
                if($pnrObject->getFlightInfo()->isOneWay() == 0 && $pnrObject->getFlightInfo()->isMultiDestination() == 0)
                {
                    if($arrivalInformation->getType() == "returning")
                    {
                        $response['arrivalDate']  = $arrivalInformation->getDepartureDateTime()->format('D d/m');
                        $response['arrivalAirport']  = $arrivalInformation->getDepartureAirport();
                    }

                }else{

                    $response['arrivalDate']  = $arrivalInformation->getArrivalDateTime()->format('D d/m');
                    $response['arrivalAirport']  = $arrivalInformation->getArrivalAirport();
                }

            }

            foreach($pnrObject->getPassengerDetails() as $passengerDetail)
            {
                $details['geneder'] = $passengerDetail->getGender();
                $details['dob'] = $passengerDetail->getDateOfBirth();
                $details['firstName'] = $passengerDetail->getFirstName();
                $details['lastName'] = $passengerDetail->getSurname();
                $details['passengerId'] = $passengerDetail->getId();
                $details['gender'] = $passengerDetail->getGender();
                $details['passengerNumber'] = $passengerNumber.".1";

                switch($passengerDetail->getType()){
                    case 'ADT'; 
                        $response['tickets']['ADT'][] = $details['passengerNumber']; 
                        $response['tickets']['count']++; 
                        $details['type'] = 'Adult';
                    break;
                    case 'CNN'; 
                        $response['tickets']['CNN'][] = $details['passengerNumber']; 
                        $response['tickets']['count']++;
                        $details['type'] = 'Child'; 
                    break;
                    case 'INF';
                        $details['type'] = 'Infant'; 
                    break;
                }

                $passengerNumber++;
            $response['data'][] = $details;
            }
        }else{
            $response['success'] = false;
            $response['message'] = "No Record found concerning these criteria, please check them and try again";
        }

        return $response;
    }

    /*
     * Update PNR With Passport Details
     */
    public function updatePassengerDetailsPassportDetails($passportDetails)
    {

        $pnrObject = $this->em->getRepository('FlightBundle:PassengerNameRecord')->findOneBy(array('pnr' => $passportDetails['pnr'], 'paymentUUID' => $passportDetails['transactionId']));

        if($pnrObject)
        {
            $response['success'] = true;

            foreach($passportDetails['passportInfo'] as $key => $passportDetailsDetail)
            {

                $passengerDetails = $this->em->getRepository('FlightBundle:PassengerDetail')->findOneById($passportDetailsDetail['passengerId']);

                $passengerDetails->setPassportNo($passportDetailsDetail['passportNo']);

//                $passportExipry = \DateTime::createFromFormat('Y-m-d', vsprintf('%s %s %s', [$passportDetailsDetail['passportExpiry']['year'],$passportDetailsDetail['passportExpiry']['month'],$passportDetailsDetail['passportExpiry']['day']]));

                $passportDate = $passportDetailsDetail['passportExpiry']['year']."-".$passportDetailsDetail['passportExpiry']['month']."-".$passportDetailsDetail['passportExpiry']['day'];
                $passportExipry = new \DateTime($passportDate);

                $passportDetailsDetail['passportInfo']['ExpiryDate'] = $passportExipry;

                $passengerDetails->setPassportExpiry($passportExipry);

                $getIssueCountryCode = $this->em->getRepository('TTBundle:CmsCountries')->findOneById($passportDetailsDetail['passportIssueCountry']);

                $passportDetailsDetail['passportInfo']['issueCountryCode'] = $getIssueCountryCode->getCode();

                $passengerDetails->setPassportIssueCountry($getIssueCountryCode);

                $getNationalityCountryCode = $this->em->getRepository('TTBundle:CmsCountries')->findOneById($passportDetailsDetail['passportNationalityCountry']);

                $passportDetailsDetail['passportInfo']['nationalityCountryCode'] = $getNationalityCountryCode->getCode();

                $passengerDetails->setPassportNationalityCountry($getNationalityCountryCode);

                $passengerDetails->setIdNo($passportDetailsDetail['idNo']);


                $this->em->persist($passengerDetails);

            }
            $passportDetailsDetail['pnr'] = $passportDetails['pnr'];
            $passportDetailsDetail['transactionId'] = $passportDetails['transactionId'];
            $this->em->flush();
        }else{
            $response['success'] = false;
            $response['message'] = "No Record found concerning these criteria, please check them and try again";
        }
        return $passportDetailsDetail;
    }


        // service for cancellation
    public function flightCancelationAsService($reservationId)
    {

        $jsonResponse = array('success' => true, 'message' => '', 'data' => array('cancellationFee' =>  array('amount' => '', 'currency' => '')));

        $seotitle = '';
        $seodescription = '';
        $seokeywords = '';

        $response = $this->SabreflightServices->flightCancelationAction($seotitle, $seodescription, $seokeywords, NULL, 1 , $reservationId);

        if(isset($response['success']) && $response['success'] == false)
        {
            $jsonResponse['success'] = false;
            $jsonResponse['message'] = $response['error'];
        }

        return json_encode($jsonResponse);

    }
    
    public function getReservationCriteria()
    {
    	$request = Request::createFromGlobals();
	$response = array();
	    
	$response['departure_airport'] = "";
        $response['departure_airport_code'] = "";
        $response['arrival_airport'] = "";
        $response['arrival_airport_code'] = "";
        $response['cabin'] = "";
        $response['adt'] = $response['cnn'] = $response['inf'] = 0;
        $response['isOneWay'] = $response['isMultiDestination'] = 0;
        $response['roundTrip'] = 1;
        
        if($request->query->has('reservationId')){
	    
	        $reservationId = $request->query->get('reservationId');
	
	        $uuid = $this->getPaymentTransactionUsingPnrID($reservationId);
	        $pnr = $this->em->getRepository('PaymentBundle:Payment')->find(urldecode($uuid));
	            
	        $flightDetails = $this->myFlightDetails($pnr, $uuid);
	
	        $response['departure_airport'] = $flightDetails['flightSegments']['leaving']['flight_info'][0]['origin_airport'];
	        $response['departure_airport_code'] = $flightDetails['flightSegments']['leaving']['flight_info'][0]['origin_airport_code'];
	        $response['arrival_airport'] = $flightDetails['flightSegments']['leaving']['flight_info'][0]['destination_airport'];
	        $response['arrival_airport_code'] = $flightDetails['flightSegments']['leaving']['flight_info'][0]['destination_airport_code'];
	        $response['cabin'] = $flightDetails['flightSegments']['leaving']['flight_info'][0]['cabin'];
	                                  
	            foreach($flightDetails['passengersArray'] as $passenger){
	                switch($passenger['type']){
	                    case "ADT": $response['adt']++; break;
	                    case "CNN": $response['cnn']++; break;
	                    case "INF": $response['inf']++; break;
	                }
	            }
	            
	            $response['isOneWay'] = $pnr->getPassengerNameRecord()->getFlightInfo()->isOneWay();
	            $response['isMultiDestination'] = $pnr->getPassengerNameRecord()->getFlightInfo()->isMultiDestination();
	
	            if(!$response['isOneWay'] && !$response['isMultiDestination']){
	                $response['roundTrip'] = 1;
	            }else{ $response['roundTrip'] = 0; }
	            
	    }
            
        return $response;
    }

    public function findAirport($airport)
    {
        return $this->container->get('FlightRepositoryServices')->findAirport($airport);
    }

    public function findAirline($airline)
    {
        return $this->container->get('FlightRepositoryServices')->findAirline($airline);
    }

    public function FlightCabinFinder($cabin)
    {
        return $this->container->get('FlightRepositoryServices')->FlightCabinFinder($cabin);
    }

    public function cmsHotelCityInfo($cityId)
    {
        return $this->container->get('CommonRepositoryServices')->cmsHotelCityInfo($cityId);
    }
    
    public function cmsHotelImageInfo($sourceId)
    {
        return $this->container->get('CommonRepositoryServices')->cmsHotelImageInfo($sourceId);
    }

    public function setTicketsFromResponse($response)
    {
        if(is_array($response)){
            $response['ADT'] = $response['CNN'] = $response['tickets'] = "" ;

            if(isset($response['hiddenFields']['ADT'])){
                $adt = $response['hiddenFields']['ADT'];
                $response['ADT'] = ($adt > 1) ? $adt . ' Adults' : $adt . ' Adult';
            }

            if(isset($response['hiddenFields']['CNN'])){
                $cnn = $response['hiddenFields']['CNN'];
                $response['CNN'] = ($cnn > 1) ? $cnn . ' Children' : $cnn . ' Child';
            }

            if(isset($response['hiddenFields']['number_in_party'])){
                $number_in_party = $response['hiddenFields']['number_in_party'];
                $response['tickets'] = ($number_in_party > 1) ? $number_in_party . ' tickets' : $number_in_party . ' ticket';
            }
        }

        return $response;
    }

    /**
     * This function will check if flight details is valid based on Payment
     * This can be use to check if we could display the ticket or not
     *
     * NOTE: The field payment.type(we called it modules) is different from payment.payment_type(cc and coa)
     *
     * Criteria: payment.type = flights(of course)
     *
     *  IF payment.payment_type EQ coa(this is usualy from corporate)
     *      THEN make sure payment.response_message EQ BYPASSED
     *      AND make sure payment.command EQ PROCESS_PAYMENT
     *
     *  IF payment.payment_type EQ cc(credit card)
     *      THEN make sure payment.response_message EQ SUCCESS
     *      AND make sure payment.command EQ PURCHASE
     *
     * @param $payment
     *
     * @return boolean true|false
     */
    function checkFlightDetailsValidFromPayment($payment){

        if ($payment->getType() == 'flights'){

            $paymentType = $payment->getPaymentType();
            $responseMessage = $payment->getResponseMessage();
            $command = $payment->getCommand();

            if ($paymentType == 'coa' && $responseMessage == 'BYPASSED' && ($command == 'PURCHASE' || $command == 'PROCESS_PAYMENT')) {
                return true;
            }

            if ($paymentType == 'cc' && $responseMessage == 'SUCCESS' && $command == 'PURCHASE') {
                return true;
            }
        }

        return false;
    }

    /**
     * This function will take related_one_way object and convert it into sabreVariable for enhanced air book call
     *
     */
    function convertRelatedOneWayToSabre($related_one_way, $sabreVariables)
    {
        $air_itinerary = $related_one_way->air_itinerary->origin_destination_options;
        $related_one_way_keys = array();

        foreach($air_itinerary as $airItinerary)
        {
            foreach($airItinerary->flight_segments as $seg => $segment)
            {
                $related_one_way_keys[] = $seg;
                $sabreVariables['related_one_way']['total_segments'][$seg]         = $seg;
                $sabreVariables['related_one_way']['DepartureDateTime'][$seg]      = $segment->departure->date_time;
                $sabreVariables['related_one_way']['ArrivalDateTime'][$seg]        = $segment->arrival->date_time;
                $sabreVariables['related_one_way']['FlightNumber'][$seg]           = $segment->flight_number;
                $sabreVariables['related_one_way']['ResBookDesigCode'][$seg]       = $segment->res_book_desig_code;
                $sabreVariables['related_one_way']['DestinationLocation'][$seg]    = $segment->arrival->airport->location_code;
                $sabreVariables['related_one_way']['MarketingAirline'][$seg]       = $segment->marketing_airline->code;
                $sabreVariables['related_one_way']['OperatingAirline'][$seg]       = $segment->operating_airline->code;
                $sabreVariables['related_one_way']['OriginLocation'][$seg]         = $segment->departure->airport->location_code;
            }
            
            // let us build return_flights
            foreach($sabreVariables['total_segments'] as $k => $v)
            {

                if(isset($sabreVariables['related_one_way']) && !array_key_exists($k, $related_one_way_keys))
                {
                    $sabreVariables['return_flight']['total_segments'][$k]     = $k;
                    $sabreVariables['return_flight']['DepartureDateTime'][$k]  = $sabreVariables['DepartureDateTime'][$k];
                    $sabreVariables['return_flight']['ArrivalDateTime'][$k]    = $sabreVariables['ArrivalDateTime'][$k];
                    $sabreVariables['return_flight']['FlightNumber'][$k]       = $sabreVariables['FlightNumber'][$k];
                    $sabreVariables['return_flight']['ResBookDesigCode'][$k]   = $sabreVariables['ResBookDesigCode'][$k];
                    $sabreVariables['return_flight']['DestinationLocation'][$k]= $sabreVariables['DestinationLocation'][$k];
                    $sabreVariables['return_flight']['MarketingAirline'][$k]   = $sabreVariables['MarketingAirline'][$k];
                    $sabreVariables['return_flight']['OperatingAirline'][$k]   = $sabreVariables['OperatingAirline'][$k];
                    $sabreVariables['return_flight']['OriginLocation'][$k]     = $sabreVariables['OriginLocation'][$k];
                }
            }

        }
        return $sabreVariables;
    }

    public function setPassengersDetails($pnr)
    {
        $passengerNameRecord = new PassengerNameRecord();


        $passengerNameRecord->setFirstName($pnr['firstName']);

        $passengerNameRecord->setSurname($pnr['surname']);
        $passengerNameRecord->setEmail($pnr['email']);
        $passengerNameRecord->setMobile($pnr['mobile']);
        $passengerNameRecord->setMobileCountryCode($this->em->getRepository('TTBundle:CmsCountries')->find($pnr['countryOfResidence']));
        $passengerNameRecord->setCountryOfResidence($this->em->getRepository('TTBundle:CmsCountries')->find($pnr['countryOfResidence']));

        $passengersAppend = [];
        $flightsAppend = [];

        foreach ($pnr['passengerDetails'] as $passenger) {
            $passengerDetail = new PassengerDetail();
            $dob = $passenger['dateOfBirth'];
            $dobM = ($dob['month'] < 10) ? '0' . $dob['month'] : $dob['month'];
            $dobD = ($dob['day'] < 10) ? '0' . $dob['day'] : $dob['day'];

            $passengerDetail->setFirstName($passenger['firstName']);
            $passengerDetail->setSurname($passenger['surname']);
            $passengerDetail->setGender($passenger['gender']);
            $passengerDetail->setType($passenger['type']);
            $passengerDetail->setDateOfBirth(new \DateTime($dob['year'] . '-' . $dob['month'] . '-' . $dob['day']));
            $passengerDetail->setFareCalcLine($passenger['fareCalcLine']);

            $passengerDetail->setTicketRph($passenger['ticketRph']);
            $passengerDetail->setLeavingBaggageInfo($passenger['leavingBaggageInfo']);
            $passengerDetail->setReturningBaggageInfo($passenger['returningBaggageInfo']);

            $passengerDetail->setPassengerNameRecord($passengerNameRecord);
            $passengersAppend = $passengerDetail;

            $passengerNameRecord->setPassengerDetails($passengerDetail);

        }
        foreach ($pnr['flightDetails'] as $flight) {
            $flightDetails = new FlightDetail();

            $flightDetails->setSegmentNumber($flight['segmentNumber']);
            $flightDetails->setDepartureAirport($flight['departureAirport']);
            $flightDetails->setArrivalAirport($flight['arrivalAirport']);
            $flightDetails->setDepartureDateTime($flight['departureDateTime']);
            $flightDetails->setArrivalDateTime($flight['arrivalDateTime']);
            $flightDetails->setAirline($flight['airline']);
            $flightDetails->setOperatingAirline($flight['operatingAirline']);
            $flightDetails->setFlightNumber($flight['flightNumber']);
            $flightDetails->setCabin($flight['cabin']);
            $flightDetails->setFlightDuration($flight['flightDuration']);
            $flightDetails->setStopIndicator($flight['stopIndicator']);
            $flightDetails->setResBookDesignCode($flight['resBookDesignCode']);
            $flightDetails->setStopDuration($flight['stopDuration']);
            $flightDetails->setType($flight['type']);
            $flightDetails->setFareCalcLine($flight['fareCalcLine']);
            $flightDetails->setLeavingBaggageInfo($flight['leavingBaggageInfo']);
            $flightDetails->setReturningBaggageInfo($flight['returningBaggageInfo']);
            $flightDetails->setDepartureTerminalId($flight['departureTerminalId']);
            $flightDetails->setArrivalTerminalId($flight['arrivalTerminalId']);
            $flightDetails->setFareBasisCode($flight['fareBasisCode']);
            $flightDetails->setAirlinePnr($flight['airlinePnr']);
            $flightDetails->setPassengerNameRecord($passengerNameRecord);
            $flightsAppend = $flightDetails;
            $passengerNameRecord->setFlightDetails($flightDetails);

        }

        foreach ($passengersAppend as $pAppend) {
            $passengerNameRecord->addPassengerDetail($pAppend);
        }
        foreach ($flightsAppend as $fAppend) {
            $passengerNameRecord->addFlightDetail($fAppend);
        }
        return $passengerNameRecord;
    }
}
