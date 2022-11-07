<?php

namespace FlightBundle\vendors\sabre;

use Doctrine\ORM\EntityManager;
use FlightBundle\Services\SabreServices;
use PaymentBundle\Repository\PaymentRepository;
use TTBundle\Utils\Utils;
use TTBundle\Services\CurrencyService;
use FlightBundle\Repository\Flight\FlightRepositoryServices;
use TTBundle\Services\EmailServices;
use TTBundle\Services\PayFortServices;
use Symfony\Component\DependencyInjection\Container;
use FlightBundle\Repository\Common\CommonRepositoryServices;
use PaymentBundle\Services\impl\ResponseStatusIdentifier;
use Symfony\Component\HttpFoundation\Request;
use TTBundle\Services\UserServices;
use CorporateBundle\Services\Admin\CorpoAccountServices;

/**
 * Flight Ticket Issuer
 * 
 * This class will issue a Ticket from the Flight Payment Model
 *   
 */
class FlightTicketIssuer {

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var SabreServices
     */
    protected $sabreServices;

    /**
     * @var paymentRepository
     */
    protected $paymentRepository;
    
    /**
     * @var EmailServices
     */
    private $emailServices;

    /**
     * @var Utils
     */
    private $utils;

    /**
     * @var FlightRepositoryServices
     */
    private $flightRepositoryServices;

    /**
     * @var translator
     */
    private $translator;

    /**
     * @var CommonRepositoryServices
     */
    private $commonRepositoryServices;

    /**
     * @var PayFortServices
     */
    private $payFortServices;

    /**
     * @var UserServices
     */
    private $userServices;  

    /**
     * @var CorpoAccountServices
     */
    private $corpoAccountServices;

    public function __construct(Container $container, EntityManager $entityManager, SabreServices $sabreServices, PaymentRepository $paymentRepository, EmailServices $emailServices, Utils $utils,  FlightRepositoryServices $flightRepositoryServices, $translator, CommonRepositoryServices $commonRepositoryServices, PayFortServices $payFortServices, UserServices $userServices, CorpoAccountServices $corpoAccountServices){
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->sabreServices = $sabreServices;
        $this->paymentRepository = $paymentRepository;
        $this->emailServices = $emailServices;
        $this->utils = $utils;
        $this->flightRepositoryServices = $flightRepositoryServices;
        $this->translator = $translator;
        $this->commonRepositoryServices = $commonRepositoryServices;
        $this->payFortServices = $payFortServices;
        $this->userServices = $userServices;
        $this->corpoAccountServices = $corpoAccountServices;
    }

    /**
     * 
     * @param $response Sabre Response, usually from Bargain Request
     *
     * Ticket Issuer method
     * 
     * @return mixed
     */
    function issueTicket($transactionId, $transactionType, $on_production_server, $from_mobile, $connection_type_booking, $global_param, $enableCancelation, $max_api_call_attempts, $pause_between_retries_us, $airlineLocatorsTiming, $issueAirTicketAttemptNumber = 1)
    {



        //$logger = $this->container->get('monolog.logger.flights');

        $engine = $this->container->get('templating');

        $error = '';

        $payment = $this->paymentRepository->findOneByUuid($transactionId);
        if (!$payment) {
            $error = $this->translator->trans('Error! No transaction found'); // Must refund payment

            //return $this->redirectToRoute('_flight_booking', array('error' => $error));
            return array(
                'error' => $error,
                're_route' => '_flight_booking'
            );
        } 

        if ( !ResponseStatusIdentifier::isCaptured($payment->getStatus())) {
            $error = $this->translator->trans('We are unable to issue your ticket. For any inquiry, kindly send us an email at flights-support@touristtube.com'); // Must refund payment
            $response = array();
            $response = $global_param;
            $response["transaction_id"] = $transactionId;
            return $response;
        }

        $pnrId = $payment->getPassengerNameRecord()->getPnr();
        $passengers = $payment->getPassengerNameRecord()->getPassengerDetails();

        foreach ($passengers as $key => $passenger) {
            $ticket_number = $passenger->getTicketNumber();
            if ($ticket_number != null && strlen($ticket_number)) {
                //$this->addFlightLog("Attempt to issue tickets for UUID $transactionId (PNR:: $pnrId), redirecting user to the flights search page.");
                //return $this->redirectToRoute('_flight_booking');
                 return array(
                    'error' => "Attempt to issue tickets for UUID $transactionId (PNR:: {$pnrId}), redirecting user to the flights search page.",
                    're_route' => '_flight_booking'
                );
            }
        }

        $sabreVariables = $this->sabreServices->getSabreConnectionVariables($on_production_server);

        $create_session_response = $this->sabreServices->createSabreSessionRequest(
            $sabreVariables, (isset($global_param['isUserLoggedIn']) ? $global_param['USERID'] : 0),
            $connection_type_booking, 
            ($from_mobile ? 'mobile' : 'web')
        );

        $sabreVariables['access_token'] = $create_session_response['AccessToken'];
        $sabreVariables['returnedConversationId'] = $create_session_response['ConversationId'];

		$this->addFlightLog("Called API SessionCreateRQ (UUID:: $transactionId, PNR:: $pnrId) with status:: ".$create_session_response['status']);
		$this->addFlightLog("With criteria (UUID:: $transactionId, PNR:: $pnrId):: {criteria}", array('criteria' => $create_session_response));

        $multiDestination = $payment->getPassengerNameRecord()->getFlightInfo()->isMultiDestination();

        $email = $payment->getPassengerNameRecord()->getEmail();

        $passengersArray = array();
        foreach ($passengers as $key => $passenger) {
            $passengersArray[$key]['first_name']             = $passenger->getFirstName();
            $passengersArray[$key]['surname']                = $passenger->getSurname();
            $passengersArray[$key]['type']                   = $passenger->getType();
            $passengersArray[$key]['gender']                 = $passenger->getGender();
            $passengersArray[$key]['dateOfBirth']            = $passenger->getDateofBirth();
            $passengersArray[$key]['fare_calc_line']         = $passenger->getFareCalcLine();
            $passengersArray[$key]['leaving_baggage_info']   = $passenger->getLeavingBaggageInfo();
            $passengersArray[$key]['returning_baggage_info'] = $passenger->getReturningBaggageInfo() == null ? "" : $passenger->getReturningBaggageInfo();
            $passengersArray[$key]['ticket_number']          = $passenger->getTicketNumber();
        }
		
		$contextChange = array('status' => 'success');
		
		if ($issueAirTicketAttemptNumber == 1)
		{
			$sabreVariables['Service'] = "ContextChangeLLSRQ";
			$sabreVariables['Action']  = "ContextChangeLLSRQ";

			$contextChange = $this->sabreServices->contextChangeRequest($sabreVariables);
			
			$this->addFlightLog("Called API ContextChangeLLSRQ (UUID:: $transactionId, PNR:: $pnrId) with status:: ".$contextChange["status"]);
			$this->addFlightLog("With criteria (UUID:: $transactionId, PNR:: $pnrId):: {criteria}", array('criteria' => $contextChange));
			
			if ($this->sabreServices->TEST_MODE) {
				$contextChange['status'] = 'success';
			}
		}
		
        if ($contextChange['status'] == 'success') {
            $sabreVariables['Service'] = "GetReservationRQ";
            $sabreVariables['Action']  = "GetReservationRQ";

            $travelItineraryRead = array(); // initialize to an empty array, just so that this variable can be in scope after the do/while loop below
            $attemptNumber       = 1;
            $startTime           = microtime(true);
			
            /*
              keep calling TravelItineraryReadRQ w/ check_airline_locators set to true until we get a response with each segment's::
              1. @eTicket="true"
              2. SupplierRef/@ID="XXXX*YYYYYY" (Airline Locator)

              stop the calls either when the above elements are provided and well formatted, or the execution time exceeds the value in  $this->airlineLocatorsTiming['time_limit_mins']
            */
			$found_airline_locators = false;
			
            do
			{
                //$travelItineraryRead = $this->sabreServices->createTravelItineraryRequest($sabreVariables,$pnrId, array('check_airline_locators' => true, 'fetch_airline_locators' => true));
                $travelItineraryRead = $this->sabreServices->createRetrieveItineraryRequest($sabreVariables, $pnrId, array('fetch_airline_locators' => true));
				
                $this->addFlightLog("Called API GetReservationRQ[$attemptNumber] (UUID:: $transactionId, PNR:: $pnrId) with status:: ".$travelItineraryRead["status"]);
                $this->addFlightLog("With criteria (UUID:: $transactionId, PNR:: $pnrId):: {criteria}", array('criteria' => $travelItineraryRead));
				
                if ($travelItineraryRead['status'] == 'success' && $travelItineraryRead['flight_segments']['status'] == 'success')
				{
					$found_airline_locators = true;

                    // update flight details with the correct airline pnr locator
                    $flightDetails = $payment->getPassengerNameRecord()->getFlightDetails();
					
					if ($flightDetails)
					{
						foreach($flightDetails as $flightSegment => $flightDetail)
						{
							// if (!isset($travelItineraryRead['flight_segments']['segments'][$flightDetail->getSegmentNumber()]))
							if (!isset($travelItineraryRead['flight_segments']['segments'][$flightSegment + 1]))
								continue;
							
							// $flightDetail->setAirlinePnr($travelItineraryRead['flight_segments']['segments'][$flightDetail->getSegmentNumber()]['airline_locator']);
							$flightDetail->setAirlinePnr($travelItineraryRead['flight_segments']['segments'][$flightSegment + 1]['airline_locator']);
						}
					}
					
                    break;
                }

                sleep($airlineLocatorsTiming['pause_between_retries_secs']);

                $attemptNumber++;
            }
            while ((time() - $startTime) < ($airlineLocatorsTiming['time_limit_mins'] * 60));
			
			if (!$found_airline_locators)
				$travelItineraryRead['status'] = 'error';
			
            if ($travelItineraryRead['status'] == 'success') {
                $sabreVariables['Service'] = "DesignatePrinterLLSRQ";
                $sabreVariables['Action']  = "DesignatePrinterLLSRQ";

                $designatePrinterTicket = $this->sabreServices->designatePrinterRequest($sabreVariables, 1);

                $this->addFlightLog("Called API DesignatePrinterRQ e-ticket (UUID:: $transactionId, PNR:: $pnrId) with status:: ".$designatePrinterTicket["status"]);
                $this->addFlightLog("With criteria (UUID:: $transactionId, PNR:: $pnrId):: {criteria}", array('criteria' => $designatePrinterTicket));

                if ($this->sabreServices->TEST_MODE) {
                    $designatePrinterTicket['status'] = 'success';
                }

                if ($designatePrinterTicket['status'] == 'success') {


                    $designatePrinterHardCopy = $this->sabreServices->designatePrinterRequest($sabreVariables, 2);

                    $this->addFlightLog("Called API DesignatePrinterRQ hard copy (UUID:: $transactionId, PNR:: $pnrId) with status:: ".$designatePrinterHardCopy["status"]);
                    $this->addFlightLog("With criteria (UUID:: $transactionId, PNR:: $pnrId):: {criteria}", array('criteria' => $designatePrinterHardCopy));

                    if ($this->sabreServices->TEST_MODE) {
                        $designatePrinterHardCopy['status'] = 'success';
                    }
                    if ($designatePrinterHardCopy['status'] == 'success') {
                        $sabreVariables['Service'] = "AirTicketLLSRQ";
                        $sabreVariables['Action']  = "AirTicketLLSRQ";

                        for ($attemptNumber = 1; $attemptNumber <= $max_api_call_attempts; $attemptNumber++) {
							
							if ($attemptNumber > 1)
								$this->sabreServices->renewTimestamps($sabreVariables);

                            $airTicket = $this->sabreServices->airTicketRequest($sabreVariables, $passengersArray);
							
							$this->addFlightLog("Called AirTicketRQ[$attemptNumber, $issueAirTicketAttemptNumber] (UUID:: $transactionId, PNR:: $pnrId) with status:: ".$airTicket["status"]);
                            $this->addFlightLog("With criteria[$attemptNumber, $issueAirTicketAttemptNumber] (UUID:: $transactionId, PNR:: $pnrId):: {criteria}", array('criteria' => $airTicket));
							
                            // Check if api response contains `EACH PASSENGER MUST HAVE SSR FOID-0052`,
                            // Many international airlines require a form of identification (FOID) to be present in the PNR before you can issue an electronic ticket.
                            // WE need the user to go back to PNR page to enter each passenger passport/ID info
                            if (isset($airTicket['app_results']['message']) && strpos($airTicket['app_results']['message'], 'EACH PASSENGER MUST HAVE SSR FOID-0052') !== false) {
                                return array(
                                    'error' => "EACH PASSENGER MUST HAVE SSR FOID-0052",
                                    'status' => "NotProcessed",
                                    're_route' => '_process-passport',
                                    'transactionId' => $transactionId,
                                    'pnrId' => $pnrId
                                );
                            }
							
							if ($issueAirTicketAttemptNumber <= $max_api_call_attempts && $airTicket['app_results']['message'] && (strpos($airTicket['app_results']['message'], 'IGN AND RETRY') !== false || strpos($airTicket['app_results']['message'], 'USE IR TO IGNORE AND RETRIEVE PNR') !== false))
							{
								$this->sabreServices->cleanSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
								$this->addFlightLog("Called Clean session (UUID:: $transactionId, PNR:: $pnrId) - Issue Air Ticket");
								
								usleep($pause_between_retries_us);
								
								$this->addFlightLog("issueTicket[$attemptNumber, $issueAirTicketAttemptNumber] (UUID:: $transactionId, PNR:: $pnrId):: Received 'IGN AND RETRY' / 'USE IR TO IGNORE AND RETRIEVE PNR', Retrying");
								
								$issueAirTicketAttemptNumber++;
								
								return $this->issueTicket($transactionId, $transactionType, $on_production_server, $from_mobile, $connection_type_booking, $global_param, $enableCancelation, $max_api_call_attempts, $pause_between_retries_us, $airlineLocatorsTiming, $issueAirTicketAttemptNumber);
							}
							
                            if ($airTicket['status'] == 'success') {
                                break;
                            }

                            if ($attemptNumber != $max_api_call_attempts){
                                usleep($pause_between_retries_us);
                            }
                        }

                        if ($this->sabreServices->TEST_MODE) {
                            $airTicket['status'] = 'success';
                        }

                        if ($airTicket['status'] == 'success') {
                            
                            $sabreVariables['Service'] = "EndTransactionLLSRQ";
                            $sabreVariables['Action']  = "EndTransactionLLSRQ";
							
							for ($attemptNumber = 1; $attemptNumber <= $max_api_call_attempts; $attemptNumber++)
							{
								$endTransaction = $this->sabreServices->endTransactionRequest($sabreVariables);
								
								$this->addFlightLog("Called API EndTransactionRQ[$attemptNumber, $issueAirTicketAttemptNumber] (UUID:: $transactionId, PNR:: $pnrId) with status:: ".$endTransaction["status"].($endTransaction['status'] == 'success' ? ', PNR:: '.($endTransaction['pnr']) : ''));
								$this->addFlightLog("With criteria[$attemptNumber, $issueAirTicketAttemptNumber] (UUID:: $transactionId, PNR:: $pnrId):: {criteria}", array('criteria' => $endTransaction));
								
								if ($endTransaction['status'] == 'success')
									break;
								
								if ($attemptNumber != $max_api_call_attempts)
									usleep($pause_between_retries_us);
							}


                            if ($this->sabreServices->TEST_MODE) {
                                $endTransaction['status'] = 'success';
                            }
							
							/*
								Perform multiple EndTransaction attempts in case of error, but do not end the flow if the last attempt is failed.
								Failure to do so in previous versions resulted in tickets being issued and invoiced by the IATA issuer but not delivered to the client.
							*/
                            if (true || $endTransaction['status'] == 'success') {
                                $sabreVariables['Service'] = "GetReservationRQ";//TravelItineraryReadRQ
                                $sabreVariables['Action']  = "GetReservationRQ";//TravelItineraryReadRQ

                                if ($this->sabreServices->TEST_MODE) {
                                    $endTransaction['status'] = 'success';
                                }

                               // $travelItineraryReadTicketInfo = $this->sabreServices->createTravelItineraryRequest($sabreVariables, $pnrId);
                                $travelItineraryReadTicketInfo = $this->sabreServices->createRetrieveItineraryRequest($sabreVariables, $pnrId, array('fetch_airline_locators' => true));

                                $this->addFlightLog("Called API TravelItineraryRQ with ticketing info (UUID:: $transactionId, PNR:: $pnrId) with status:: ".$travelItineraryReadTicketInfo["status"].', tickets:: ['.($travelItineraryReadTicketInfo['tickets']? $this->utils->flatten_array($travelItineraryReadTicketInfo['tickets']) : '').']');
                                $this->addFlightLog("With criteria (UUID:: $transactionId, PNR:: $pnrId):: {criteria}", array('criteria' => $travelItineraryReadTicketInfo));

                                if ($travelItineraryReadTicketInfo['status'] == 'success') {
                                    $tickets = $travelItineraryReadTicketInfo["tickets"];
                                    foreach ($passengers as $key => $passenger) {
                                        if (isset($tickets[$passenger->getTicketRph()])) {
                                            $passenger->setTicketNumber($tickets[$passenger->getTicketRph()]);
                                            $passenger->setTicketStatus('Success');
                                            $passengersArray[$key]['ticket_number'] = $tickets[$passenger->getTicketRph()];

                                            $em = $this->entityManager; // ->getManager();

                                            $em->persist($passenger);

                                            $em->flush();
                                        }
                                    }

                                    
                                    $flightSegments = array();
                                    $flightDetail   = $payment->getPassengerNameRecord()->getFlightDetails();

                                    foreach ($flightDetail as $index => $flight) {
                                        $flightInfo       = array();
                                        $departureAirport = $this->flightRepositoryServices->findAirport($flight->getDepartureAirport());
                                        $arrivalAirport   = $this->flightRepositoryServices->findAirport($flight->getArrivalAirport());

                                        if ($multiDestination) {
                                            $flightSegments[$flight->getType()]['destination_city'] = ($departureAirport) ? $departureAirport->getCity() : $flight->getArrivalAirport();
                                            $flightSegments[$flight->getType()]['country_code'] = ($departureAirport) ? $departureAirport->getCountry() : "";
                                            $flightSegments[$flight->getType()]['city_id'] = ($departureAirport) ? $departureAirport->getCityId() : 0;
                                        } else {
                                            $flightSegments[$flight->getType()]['destination_city'] = ($arrivalAirport) ? $arrivalAirport->getCity() : $flight->getArrivalAirport();
                                            $flightSegments[$flight->getType()]['country_code'] = ($arrivalAirport) ? $arrivalAirport->getCountry() : "";
                                            $flightSegments[$flight->getType()]['city_id'] = ($arrivalAirport) ? $arrivalAirport->getCityId() : 0;
                                        }

                                        if (!$flight->getStopIndicator()) {
                                            $flightSegments[$flight->getType()]['origin_city'] = ($departureAirport) ? $departureAirport->getCity() : $flight->getDepartureAirport();
                                            $flightSegments[$flight->getType()]['departure_main_date'] = ($arrivalAirport) ? $flight->getDepartureDateTime()->format('M j Y') : $flight->getArrivalAirport();
                                        }

                                        $mainAirline = $this->flightRepositoryServices->findAirline($flight->getAirline());
                                        $flightSegments[$flight->getType()]['main_airline'] = ($mainAirline) ? $mainAirline->getAlternativeBusinessName() : $flight->getAirline();

                                        $flightInfo['departure_date'] = $flight->getDepartureDateTime()->format('M j Y');
                                        $flightInfo['departure_time']  = $flight->getDepartureDateTime()->format('H\:i');
                                        $flightInfo['origin_city'] = ($departureAirport) ? $departureAirport->getCity() : "";
                                        $flightInfo['origin_airport'] = ($departureAirport) ? $departureAirport->getName() : "";
                                        $flightInfo['origin_airport_code'] = $flight->getDepartureAirport();

                                        $flightInfo['arrival_date']  = $flight->getArrivalDateTime()->format('M j Y');
                                        $flightInfo['arrival_time']   = $flight->getArrivalDateTime()->format('H\:i');
                                        $flightInfo['destination_airport_code'] = $flight->getArrivalAirport();
                                        $flightInfo['destination_airport'] = ($arrivalAirport) ? $arrivalAirport->getName() : "";
                                        $flightInfo['destination_city']  = ($arrivalAirport) ? $arrivalAirport->getCity() : "";

                                        $airlineName  = $this->flightRepositoryServices->findAirline($flight->getAirline());
                                        $flightInfo['airline_name']  = ($airlineName) ? $airlineName->getAlternativeBusinessName() : $flight->getAirline();
                                        $flightInfo['flight_number'] = $flight->getFlightNumber();
                                        $flightInfo['airline_code']  = $flight->getAirline();

                                        $operatingAirlineName = $this->flightRepositoryServices->findAirline($flight->getOperatingAirline());
                                        $flightInfo['operating_airline_code'] = $flight->getOperatingAirline();
                                        $flightInfo['operating_airline_name'] = ($operatingAirlineName) ? $operatingAirlineName->getAlternativeBusinessName() : $flight->getAirline();

                                        $cabinName = $this->flightRepositoryServices->FlightCabinFinder($flight->getCabin());
                                        $flightInfo['cabin'] = ($cabinName) ? $cabinName->getName() : $flight->getCabin();
                                        $flightInfo['flight_duration'] = $flight->getFlightDuration();
                                        $flightInfo['stop_indicator'] = $flight->getStopIndicator();
                                        $flightInfo['stop_info'] = "";
                                        $flightInfo['elapsedTime'] = $flight->getElapsedTime();

                                        if ($flightInfo['stop_indicator'] == 1 && $multiDestination) {
                                            $flightSegments[$flight->getType()]['flight_info'][$index - 1]['stop_info'][] = $flightInfo;
                                        } else {
                                            $flightSegments[$flight->getType()]['flight_info'][] = $flightInfo;
                                        }
                                    }

//                                    $emailData['flight_segments'] = $flightSegments;
//                                    $emailData['passenger_details'] = $passengersArray;
//
//                                    $emailData['price'] = $payment->getPassengerNameRecord()->getFlightInfo()->getDisplayPrice();
//                                    $emailData['currency'] = $payment->getPassengerNameRecord()->getFlightInfo()->getDisplayCurrency();

                                    if ($payment->getCouponCode() && $payment->getDisplayAmount() != $payment->getDisplayOriginalAmount()) {
                                        $emailData['discounted_price'] = round($payment->getDisplayAmount() + 0, 2);
                                    }

//                                    $emailData['base_fare']  = $payment->getPassengerNameRecord()->getFlightInfo()->getDisplayBaseFare();
//                                    $emailData['taxes'] = $payment->getPassengerNameRecord()->getFlightInfo()->getDisplayTaxes();
//                                    $emailData['pnr'] = $pnrId;
//                                    $emailData['transaction_id'] = $transactionId;
//                                    $emailData['special_requirement'] = $payment->getPassengerNameRecord()->getSpecialRequirement();
//                                    $emailData['email'] = $email;
//                                    $emailData['refundable'] = $payment->getPassengerNameRecord()->getFlightInfo()->isRefundable();
//                                    $emailData['one_way'] = $payment->getPassengerNameRecord()->getFlightInfo()->isOneWay();
//                                    $emailData['multi_destination'] = $multiDestination;

//                                    $countryCode = $emailData['flight_segments']['leaving']['country_code'];
//                                    $cityId = $emailData['flight_segments']['leaving']['city_id'];
//                                    $getLocationId = $this->commonRepositoryServices->cmsHotelCityInfo($cityId);
//                                    $locationId = ($getLocationId) ? $getLocationId->getLocationId() : 0;
                                    
                                    $emailData = $this->container->get('FlightServices')->addEmailData($this, $payment, $transactionId);
                                    $msg = $engine->render('emails/flight_email_confirmation_new.twig', array('emailData' => $emailData));
                                    
                                    $this->emailServices->addEmailData($emailData->getEmail(), $msg, $this->translator->trans('TouristTube Travel Confirmation'), $this->translator->trans('TouristTube Travel Confirmation'), 0);

                                    //copy of email confirmation if defined
                                    if($this->container->hasParameter('accounting_email')){
                                        
                                        $accountName = '';

                                        if(!empty($global_param['USERID'])){
                                            $userArray = $this->container->get('UserServices')->getUserDetails(array('id' => $global_param['USERID']));
                                            $accountName = (isset($userArray[0]['accountName'])) ? $userArray[0]['accountName'] . ': ' : '';
                                        }
                                        
                                        $predefined_email = $this->container->getParameter('accounting_email');
                                        $subject = $accountName . $this->translator->trans('TouristTube Travel Confirmation');

                                        $this->emailServices->addEmailData($predefined_email, $msg, $subject, $subject, 0);
                                    }
                                    //sending to corporate email as well
                                    if (isset($global_param['is_corporate_account']) && $global_param['is_corporate_account']) {
                                        $userId = $payment->getUserId();
                                        $userArray = $this->userServices->getUserDetails(array('id' => $userId));
                                        $corpoEmail = $userArray[0]['corporateEmail'];

                                        $this->emailServices->addEmailData($corpoEmail, $msg, $this->translator->trans('TouristTube Travel Confirmation'), $this->translator->trans('TouristTube Travel Confirmation'), 0);

                                        //sending invoice
                                        $this->corpoAccountServices->sendInvoice($transactionId);
                                    }

                                    $this->addFlightLog("Created flight email confirmation (UUID:: $transactionId, PNR:: $pnrId) with criteria:: {criteria}", array('criteria' => $emailData));
									
									// check what would be returned by GetReservationRS (check for the tickets in the response)
									$sabreVariables['Service'] = "GetReservationRQ";
									$sabreVariables['Action']  = "GetReservationRQ";
									$getReservationRequest = $this->sabreServices->createRetrieveItineraryRequest($sabreVariables, $pnrId);
									
									$this->addFlightLog("Called API GetReservationRQ[$attemptNumber] (UUID:: $transactionId, PNR:: $pnrId) with status:: ".$getReservationRequest["status"]);
									$this->addFlightLog("With criteria (UUID:: $transactionId, PNR:: $pnrId):: {criteria}", array('criteria' => $getReservationRequest));
                                } else {
                                    $error = $this->translator->trans('Error! can\'t get ticket info');
                                    $this->addFlightLog("Error! can't get ticket info (UUID:: $transactionId, PNR:: $pnrId) with criteria:: {criteria}", array('criteria' => $travelItineraryReadTicketInfo));
                                }
                            } else {
                                $error = $this->translator->trans('Error! can\'t save transaction');
                                $this->addFlightLog("Error! can't save transaction (UUID:: $transactionId, PNR:: $pnrId) with criteria:: {criteria}", array('criteria' => $endTransaction));
                            }
                        } else {
                            $error = $this->translator->trans('Error! can\'t issue air ticket');
                            $this->addFlightLog("Error! can't issue air ticket (UUID:: $transactionId, PNR:: $pnrId) with criteria:: {criteria}", array('criteria' => $airTicket));
                        }
                    } else {
                        $error = $this->translator->trans('Error! can\'t designate printer hard copy');
                        $this->addFlightLog("Error! can\'t designate printer hard copy (UUID:: $transactionId, PNR:: $pnrId) with criteria:: {criteria}", array('criteria' => $designatePrinterHardCopy));
                    }
                } else {
                    $error = $this->translator->trans('Error! can\'t designate printer ticket');
                    $this->addFlightLog("Error! can't designate printer ticket (UUID:: $transactionId, PNR:: $pnrId) with criteria:: {criteria}", array('criteria' => $designatePrinterTicket));
                }
            } else {
                $error = $this->translator->trans('Error! can\'t get passenger name record');
                $this->addFlightLog("Error! can't get passenger name record (UUID:: $transactionId, PNR:: $pnrId) with criteria:: {criteria}", array('criteria' => $travelItineraryRead));
            }
        } else {
            $error = $this->translator->trans('Error! can\'t change context');
            $this->addFlightLog("Error! can't change context (UUID:: $transactionId, PNR:: $pnrId) with criteria:: {criteria}", array('criteria' => $contextChange));
        }

        if ($error !== "") {
            if (isset($global_param['is_corporate_account']) && $global_param['is_corporate_account']) {
                $payment->setStatus('99');
                $payment->setResponseMessage('BYPASSED');
                $payment->setResponseCode('99999');
                $payment->setCommand('PURCHASE');
                $payment->setUpdatedDate(new \DateTime("now"));

                $em = $this->entityManager;
                $em->persist($payment);

                $em->flush();
            } else {

                $params = array("uuid" => $transactionId, "operation" => "REFUND", "type" => $transactionType);

                for ($attemptNumber = 1; $attemptNumber <= $max_api_call_attempts; $attemptNumber++) {
                    //$refund     = $this->payFortServices->refundCaptureService($payment, $params);
                    $refund  =  $this->container->get('PaymentServiceImpl')->refund($transactionId);
                    //$refundData = json_decode($refund, true);
                    $this->addFlightLog("Called Refund[$attemptNumber] (UUID:: $transactionId, PNR:: $pnrId):: {criteria}", array('criteria' => $refund['data']));
                    if (isset($refund['success']) && $refund['success'] == 1) {
                        $payment->setResponseMessage('Auto refunded');
                        break;
                    }

                    if ($attemptNumber != $max_api_call_attempts) usleep($pause_between_retries_us);
                }
            }

            $this->sabreServices->closeSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
            $this->addFlightLog("Called Close session (UUID:: $transactionId, PNR:: $pnrId) - Issue Air Ticket");

            $global_param['error'] = $error;
            $global_param['message'] = $this->translator->trans("You will be refunded");

            $this->addFlightLog("Error issuing ticket:: (UUID:: $transactionId, PNR:: $pnrId) ".$error);

            $global_param['customer_name'] = $payment->getPassengerNameRecord()->getFirstName().' '.$payment->getPassengerNameRecord()->getSurname();

            $msg = $engine->render('emails/flight_error_message.twig', $global_param);
            $this->emailServices->addEmailData($email, $msg, $this->translator->trans('Ticketing Error'), $this->translator->trans('Ticketing Error'), 0);

            //return $this->redirectToRoute('_flight_booking', array('error' => $global_param['error']));
            return array(
                'error' => $global_param['error'],
                're_route' => '_flight_booking'
            );
        }


        $this->sabreServices->closeSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
        $this->addFlightLog("Called Close session (UUID:: $transactionId, PNR:: $pnrId) - Issue Air Ticket");

        if ($payment->getCouponCode()){
            $this->container->get('TTServices')->addNewCoupon(
                $payment->getCampaignId(), 
                $payment->getCouponCode(), 
                $transactionId, 
                $this->container->getParameter('SOCIAL_ENTITY_FLIGHT')
            );
        }
        
        return array('transaction_id' => $transactionId, 'iscorpo' => (isset($global_param['is_corporate_account']) && $global_param['is_corporate_account']));
    }
	
     /**
     * this is created to add Log to the Flight, thi is a very usefull way to debbug a problem, and track the flight action
     * @param String $message
     * @param array $params
     * @param boolean $cleanParams
     */
    public function addFlightLog($message, $params = array(), $cleanParams = false)
    {
        if ($cleanParams) {
            if (isset($params['criteria'])) {
                $this->cleanParams($params['criteria']);
            }
        }
        if (isset($params['criteria'])) {
            $params['criteria'] = json_encode($params['criteria']);
        }
     //   $params["userId"] = ($global_param['isUserLoggedIn']) ? $global_param['USERID'] : 0;
        $logger = $this->container->get('monolog.logger.flights');
        $params["userId"] = 0;
        $logger->info("\nUser {userId} - ".$message, $params);
    }


}
