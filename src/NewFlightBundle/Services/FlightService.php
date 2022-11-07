<?php
/**
 * Created by PhpStorm.
 * User: para-soft7
 * Date: 9/13/2018
 * Time: 6:37 PM
 */

namespace NewFlightBundle\Services;

use NewFlightBundle\Model\CreateEnhancedAirBookRequest;
use NewFlightBundle\Model\CreateBargainRequest;
use NewFlightBundle\Model\flightVO;
use NewFlightBundle\Model\PassengerNameRecord;
use NewFlightBundle\Model\PassengerDetails;
use NewFlightBundle\Model\FlightItinerary;
use NewFlightBundle\Model\FlightStops;
use NewFlightBundle\Model\FlightDetails;
use NewFlightBundle\Repository\FlightRepository;
use NewFlightBundle\vendors\sabre\Services\SabreService;
use PaymentBundle\Entity\Payment;
use PaymentBundle\Repository\PaymentRepository;
use PaymentBundle\Services\impl\ResponseStatusIdentifier;

use Symfony\Component\Translation\TranslatorInterface;

use NewFlightBundle\Model\PassengerDetailsRequest as PassengerDetailsRequestNew;
use NewFlightBundle\Model\FlightSegment;

use TTBundle\Services\UserServices;
use TTBundle\Services\CurrencyService;

use NewFlightBundle\Model\Airport;
use NewFlightBundle\Model\City;
use NewFlightBundle\Model\Airline;
use NewFlightBundle\Model\Cabin;
use NewFlightBundle\Model\PassengerInfoBaggage;
use TTBundle\Utils\Utils;

class FlightService
{
    /**
     * @var array $memoizeAirport
     */
    private $memoizeAirport = [];

    /**
     * @var array $memoizeCabin
     */
    private $memoizeCabin = [];

    /**
     * @var array $memoizeAirline
     */
    private $memoizeAirline = [];

    /**
     * @var FlightRepository
     */
    protected $flightRepository;

    /**
     * @var SabreService
     */
    protected $sabreService;

    /**
     * @var PaymentRepository
     */
    protected $paymentRepository;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var UserServices
     */
    protected $userServices;

    /**
     * @var Utils
     */
    private $utils;

    /**
     * @var CurrencyService
     */
    private $currencyService;

    public function __construct(FlightRepository $flightRepository, SabreService $sabreService, PaymentRepository $paymentRepository, TranslatorInterface $translator, UserServices $userServices, Utils $utils, CurrencyService $currencyService)
    {
        $this->flightRepository  = $flightRepository;
        $this->sabreService     = $sabreService;
        $this->paymentRepository = $paymentRepository;
        $this->translator         = $translator;
        $this->userServices      = $userServices;
        $this->utils = $utils;
        $this->currencyService = $currencyService;
    }

    /*
     * make a call to the database to get Airline List
     */
    public function getFlightSearchInfo()
    {
        $getAirlineList = $this->flightRepository->getAirlineList();

        return $getAirlineList;
    }
    /**
     * Get Flight results based on the BargainRequest's entered
     * Re-populate the results with the actual data from the DB, i.e Airport, Airline, Stops, Price, Taxes, etc...
     *
     * @param CreateBargainRequest $bargainRequest
     * @return flightVO
     */
    public function getFlightBooking(CreateBargainRequest $bargainRequest)
    {
        //Re-populate Aiport City for BargainRequest
        $bargainRequest->setOriginLocation($this->populateDepartureAndArrivalData($bargainRequest->getOriginLocation()->getCode()));
        $bargainRequest->setDestinationLocation($this->populateDepartureAndArrivalData($bargainRequest->getDestinationLocation()->getCode()));

        $flightVO = new flightVO();

        $config = $this->sabreService->getConfig();
        $discount = $config->DISCOUNT;
        $currencyCode = $bargainRequest->getCurrencyCode();

        $searchResult = $this->sabreService->getFlightResult($bargainRequest);
        $departureAirports = [];

        if($searchResult->getStatus() == "success") {
            foreach ($searchResult->getData() as $itinerary) {
                $stops = [];
                $prevSegments = [];

                $itinerary->getFlightItineraryPricingInfo()->setDiscount($discount);
                $itinerary->getFlightItineraryPricingInfo()->setPrice($this->calculateFare($itinerary->getFlightItineraryPricingInfo()->getTotalFare(), $currencyCode, $discount));
                $itinerary->getFlightItineraryPricingInfo()->setTaxes($this->calculateTaxes($itinerary->getFlightItineraryPricingInfo()->getBaseTaxes(), $currencyCode));

                $flightSegments = $itinerary->getFlightSegment();

                foreach ($flightSegments as $segmentIndex => $segment) {
                    $departureAirport = $this->populateDepartureAndArrivalData($segment->getFlightDeparture()->getAirport()->getCode());
                    $segment->getFlightDeparture()->setAirport($departureAirport);

                    $arrivalAirport = $this->populateDepartureAndArrivalData($segment->getFlightArrival()->getAirport()->getCode());
                    $segment->getFlightArrival()->setAirport($arrivalAirport);

                    $marketingAirline = $this->populateMarketingAndOperatingAirlineData($segment->getMarketingAirline()->getAirlineCode());
                    $operatingAirline = $this->populateMarketingAndOperatingAirlineData($segment->getOperatingAirline()->getAirlineCode());

                    $segment->setMarketingAirline($marketingAirline);
                    $segment->setOperatingAirline($operatingAirline);

                    if ($segmentIndex) {
                        $flightStop = $this->getSegmentStops($segment, $prevSegments[count($prevSegments) - 1]);
                        $stops[] = $flightStop;
                        $segment->setFlightStops($flightStop);
                    }

                    $prevSegments[] = $segment;

                    $price = $itinerary->getFlightItineraryPricingInfo()->getPrice();
                    $cityName = $segment->getFlightDeparture()->getAirport()->getCity()->getName();
                    $airportCode = $flightSegments[0]->getFlightDeparture()->getAirport()->getCode();

                    //expose daparture airports by getting the lowest price
                    if (array_key_exists($airportCode,$departureAirports)) {
                        if ($departureAirports[$airportCode]['price'] > $price) {
                            $departureAirports[$airportCode] = [
                                'price' => $price,
                                'city' => $cityName
                            ];
                        }
                    }
                    else {
                        $departureAirports[$airportCode] = [
                            'price' => $price,
                            'city' => $cityName
                        ];
                    }
                }

                $itinerary->setFlightStops($stops);

            }
        }

        $flightVO->setCode($searchResult->getCode());
        $flightVO->setStatus($searchResult->getStatus());
        $flightVO->setMessage($searchResult->getMessage());
        $flightVO->setData(
            [
                'bargainRequest' => $bargainRequest,
                'itineraries' => $searchResult,
                'departureAirports' => $departureAirports,
                'currencyCode' => $currencyCode,
                'enable_refundable' => $config->ENABLE_REFUNDABLE
            ]
        );

        return $flightVO;
    }

    public function enhancedAirBookRequest(CreateEnhancedAirBookRequest $enhancedAirBookRequest)
    {
        $enhancedAirBookRequest = $this->sabreService->enhancedAirBookRequest($enhancedAirBookRequest);
        return $enhancedAirBookRequest;
    }

    /**
     * this is temporary and could be deleted after we determine the proper file for the object setter
     */
    public function getPNRActionObject($searchId)
    {
        $selectedFlight = $this->flightRepository->getSelectedFlightDetails($searchId);

        $flightInfo = $selectedFlight['selectedSearchResult'];
        $flightSegments = $selectedFlight['detailsSelectedSearchResult'];

        $enhancedAirBookRequest = new CreateEnhancedAirBookRequest();

        $number_in_party = $flightInfo->getAdtCount() + $flightInfo->getCnnCount();
        $totalPrice = 0;

        $enhancedAirBookRequest->setNumberInParty($number_in_party);
        $enhancedAirBookRequest->setAdultsQuantity($flightInfo->getAdtCount());
        $enhancedAirBookRequest->setChildrenQuantity($flightInfo->getCnnCount());
        $enhancedAirBookRequest->setInfantsQuantity($flightInfo->getInfCount());

        $segments = array();

        foreach($flightSegments as $i => $segment){

            $flightSegment = new FlightSegment();

            $flightSegment->setSegmentNumber($i + 1);
            $flightSegment->setFlightNumber((int)$segment->getFlightNumber());
            $flightSegment->setDuration($segment->getDuration()); 
            $flightSegment->setFareBasisCode($segment->getFareBasisCode());
            $flightSegment->setCabinSelected($flightInfo->getCabinSelected()); 
            $flightSegment->setFlightType($flightInfo->getFlightType()); 
            $flightSegment->setResBookDesigCode($segment->setResBookDesignCode());
            $flightSegment->getFlightDeparture()->getAirportModel()->setCode($segment->getFromLocation());
            //$segment->getFlightDeparture()->setDateTime($departure_date_time_var);
            $segment->getFlightArrival()->getAirportModel()->setCode($segment->getToLocation());
            //$segment->getFlightArrival()->setDateTime($arrival_date_time_var);
            $segment->getMarketingAirline()->setAirlineCode($segment->getMarketingAirline());
            $segment->getOperatingAirline()->setAirlineCode($segment->getOperatingAirline());

            $segments[$i] = $flightSegment;

            $totalPrice += $segment->getPrice();
        }

        $enhancedAirBookRequest->getFlightItineraryModel()->setPrice($totalPrice);
        $enhancedAirBookRequest->getFlightItineraryModel()->setFlightSegmentModel($segments);

        return $enhancedAirBookRequest;
    }

    /**
     * this is temporary and could be deleted after we determine the proper file for the object setter
     */
    public function getPNRSubmissionActionObject($request)
    {
        if(!$request->request->has('passengerNameRecord')){
            return false;
        }

        $passengerDetailsRequest = new PassengerDetailsRequestNew();

        $passengers = $request->request->get('passengerNameRecord');

        foreach($passengers as $passenger)
        {
            $passengerDetail = new PassengerDetails();

            $passengerDetail->setType($passenger['type']);
            $passengerDetail->setGender($passenger['gender']);
            $passengerDetail->setFirstName($passenger['firstName']);
            $passengerDetail->setSurname($passenger['surname']);
            
            $dob_year = str_pad($passenger['dateOfBirth']['year'], 2, '0', STR_PAD_LEFT);
            $dob_month = str_pad($passenger['dateOfBirth']['month'], 2, '0', STR_PAD_LEFT);
            $dob_day = str_pad($passenger['dateOfBirth']['day'], 2, '0', STR_PAD_LEFT);
            
            $passengerDetail->setDateOfBirth($dob_year.'-'.$dob_month.'-'.$dob_day);

            if(isset($passenger['idNo'])){
                $passengerDetail->setIdNo($passenger['idNo']);
            }

            if(isset($passenger['passportNationalityCountry'])){
                $passengerDetail->getPassportNationalityCountry()->setId($passenger['passportNationalityCountry']);
            }

            if(isset($passenger['passportIssueCountry'])){
                $passengerDetail->getPassportIssuingCountry()->setId($passenger['passportIssueCountry']);
            }

            if(isset($passenger['passportNo'])){
                $passengerDetail->setPassportNumber($passenger['passportIssueCountry']);
            }

            if(isset($passenger['passportNo']['passportExpiry'])){
                $passport_year = str_pad($passenger['passportExpiry']['year'], 2, '0', STR_PAD_LEFT);
                $passport_month = str_pad($passenger['passportExpiry']['month'], 2, '0', STR_PAD_LEFT);
                $passport_day = str_pad($passenger['passportExpiry']['day'], 2, '0', STR_PAD_LEFT);
                $passengerDetail->setPassportExpiry($passport_year.'-'.$passport_month.'-'.$passport_day);
            }

            $passengers[] = $passengerDetail;
        }

        $passengerDetailsRequest->getPassengerNameRecordModel()->setPassengerDetailsModel($passengers);

        $passengerDetailsRequest->getPassengerNameRecordModel()->setFirstName($passenger['firstName']);
        $passengerDetailsRequest->getPassengerNameRecordModel()->setSurname($passenger['surname']);
        $passengerDetailsRequest->getPassengerNameRecordModel()->setEmail($passenger['email']);
        $passengerDetailsRequest->getPassengerNameRecordModel()->setMobileCountryCode($passenger['mobileCountryCode']);
        $passengerDetailsRequest->getPassengerNameRecordModel()->setMobileNumber($passenger['mobile']);
        $passengerDetailsRequest->getPassengerNameRecordModel()->setCountryOfResidence($passenger['countryOfResidence']);
        $passengerDetailsRequest->getPassengerNameRecordModel()->setAlternativeNumber($passenger['alternativeNumber']);
        $passengerDetailsRequest->getPassengerNameRecordModel()->setMembershipId($passenger['membership_id']);
        $passengerDetailsRequest->getPassengerNameRecordModel()->setSpecialRequirement($passenger['specialRequirement']);

        $passengerDetailsRequest->getMarketingAirlineModel->setAirlineCode($request->request->get('marketingAirline'));

        if($request->request->has('passportCheck'))
        {
            $passengerDetailsRequest->setPassportCheck($request->request->get('passportCheck'));
        }

        return $passengerDetailsRequest;
    }

    public function createPassengerNameRecord(PassengerDetailsRequest $passengerDetailsRequest)
    {
        $createPassengerNameRecord = $this->sabreService->createPassengerNameRecord($passengerDetailsRequest);
        return $createPassengerNameRecord;
    }

    public function flightTicketIssuer($uuid)
    {
        $payment = $this->flightRepository->getPaymentByUuid($uuid);

        $pnr = new PassengerNameRecord();
        if (!$payment) {
            $error = $this->translator->trans('Error! No transaction found'); // Must refund payment
            $pnr->setStatus('error');
            $pnr->setMessage($error);
            return $pnr;
        }

        if (!ResponseStatusIdentifier::isCaptured($payment->getStatus())) {
            $error = $this->translator->trans('We are unable to issue your ticket. For any inquiry, kindly send us an email at flights-support@touristtube.com');
            $pnr->setStatus('error');
            $pnr->setMessage($error);
            return $pnr;
        }

        $pnrId         = $payment->getPassengerNameRecord()->getPnr();
        $transactionId = $payment->getUUID();
        $passengers    = $payment->getPassengerNameRecord()->getPassengerDetails();
        $userId        = $payment->getUserId();
        $userInfo      = $this->userServices->getUserInfoById($payment->getUserId(), false);

        $isCorporate = $payment->getPassengerNameRecord()->getIsCorporateSite();
        $pnr->setPaymentUUID($transactionId);
        $pnr->setPnr($pnrId);
        $pnr->setIsCorporateSite($isCorporate);

        foreach ($passengers as $key => $passenger) {
            $ticketNumber = $passenger->getTicketNumber();
            if ($ticketNumber != null && strlen($ticketNumber)) {
                $action_array                 = array();
                $action_array[]               = $transactionId;
                $action_array[]               = $pnrId;
                $ms = vsprintf($this->translator->trans("Attempt to issue tickets for UUID %s (PNR:: %s), redirecting user to the flights search page."), $action_array);
                $error = $ms;
                $pnr->setStatus('error');
                $pnr->setMessage($error);
                return $pnr;
            }
        }

        $airTicket = $this->sabreService->airTicketRequest($payment);

        $pnr->setStatus($airTicket->getStatus());
        $pnr->setCode($airTicket->getCode());
        $pnr->setMessage($airTicket->getMessage());

        if ($airTicket->getStatus() == 'success') {
            if ($airTicket->getTicketNumber()) {
                $tickets = $travelItineraryReadRequest->getTicketNumber();
                if (isset($tickets[$passenger->getTicketRph()])) {
                    $passenger->setTicketNumber($tickets[$passenger->getTicketRph()]);
                    $passenger->setTicketStatus('Success');
                    $this->flightRepository->updatePassengerDetail($passenger);
                }
            }
        } else {
            if ($userInfo[0]->getIsCorporateAccount()) {
                $payment->setStatus('99');
                $payment->setResponseMessage('BYPASSED');
                $payment->setResponseCode('99999');
                $payment->setCommand('PURCHASE');
                $payment->setUpdatedDate(new \DateTime("now"));
                $this->flightRepository->updatePayment($payment);
            } else {
                $maxApiCallAttempts      = $this->container->getParameter('MAX_API_CALL_ATTEMPTS');
                $attemptNumber           = $this->container->getParameter('ATTEMPT_NUMBER');
                $pauseBetweenRetriesSecs = $this->container->getParameter('PAUSE_BETWEEN_RETRIES_SECS');

                $params = array("uuid" => $transactionId, "operation" => "REFUND", "type" => 'flight');

                for ($attemptNumber; $attemptNumber <= $maxApiCallAttempts; $attemptNumber++) {
                    $refund     = $this->payFortServices->refundCaptureService($payment, $params);
                    $refundData = json_decode($refund, true);
                    if ($refundData['status'] == '06') {
                        $payment->setResponseMessage('Auto refunded');
                        break;
                    }
                    if ($attemptNumber != $maxApiCallAttempts) usleep($pauseBetweenRetriesSecs);
                }
            }
        }

        return $pnr;
    }

    /**
     * Cancel Flight
     *
     * @param string $uuid
     * @return flightVO
     */
    public function cancelFlight($uuid){

        $response =  new flightVO;
        $payment = $this->flightRepository->getPaymentByUuid($uuid);

        if ($payment instanceof Payment){
            //TODO: make sure to meet the criteria:
            //1. Payment.type = flights
            //2. its not cancelled yet?
            //3. any other?
            $cancelFlight = $this->sabreService->cancelFlight($payment->getPassengerNameRecord());

            return $cancelFlight;
        }
        else {
            $response->setStatus('error');
            $response->setMessage('Unable to find Payment record');

            return $response;
        }
    }

    /**
     * This method populate flightSegment Departure and Arrival Data based on Airport Code
     * We use Memoize caching technique for performance reasons
     *
     * @param string $airportCode
     * @return Airport Object
     */
    private function populateDepartureAndArrivalData($airportCode){

        if (!isset($this->memoizeAirport[$airportCode])) {
            $this->memoizeAirport[$airportCode] = $this->flightRepository->findAirport($airportCode);;
        }

        $airport = new Airport();
        $city = new City();

        if ($this->memoizeAirport[$airportCode] instanceof \TTBundle\Entity\Airport) {
            $airport->setName($this->memoizeAirport[$airportCode]->getName());
            $airport->setCode($airportCode);
            $city->setName($this->memoizeAirport[$airportCode]->getCity());
            $airport->setCity($city);
        }

        return $airport;
    }

    /**
     * This method populate flightSegment Marketing and Operating Airline Data based on Airline Code
     * We use Memoize caching technique for performance reasons
     *
     * @param string $airlineCode
     * @return Airline Object
     */
    private function populateMarketingAndOperatingAirlineData($airlineCode){

        if (!isset($this->memoizeAirline[$airlineCode])) {
            $this->memoizeAirline[$airlineCode] = $this->flightRepository->findAirline($airlineCode);;
        }

        $airline = new Airline();
        $airline->setAirlineCode($airlineCode);

        if ($this->memoizeAirline[$airlineCode] instanceof \TTBundle\Entity\Airline) {
            $airline->setAirlineName($this->memoizeAirline[$airlineCode]->getAlternativeBusinessName());
        } else {
            $airline->setAirlineName($airlineCode);
        }

        return $airline;
    }

    /**
     * This method Cabin based from the code
     * We use Memoize caching technique for performance reasons
     *
     * @param string $cabinCode
     * @return Cabin Object
     */
    private function populateCabinData($cabinCode)
    {

        if (!isset($this->memoizeCabin[$cabinCode])) {
            $this->memoizeCabin[$cabinCode] = $this->flightRepository->findCabin($cabinCode);
        }

        $cabin = new Cabin();

        if ($this->memoizeCabin[$cabinCode] instanceof \FlightBundle\Entity\FlightCabin) {
            $cabin->setName($this->memoizeCabin[$cabinCode]->getName());
            $cabin->setCode($cabinCode);
        }

        return $cabin;
    }

    /**
     * This method Get flight stops of FlightSegment
     *
     * @param FlightSegment $flightSegment
     * @return FlightStops Object
     */
    private function getSegmentStops(FlightSegment $currentSegment, FlightSegment $prevSegment){

        $departureDate = $this->utils->date_time_parts($currentSegment->getFlightDeparture()->getDateTime(), $currentSegment->getFlightDeparture()->getTimeZoneGmtOffset());

        $departureTimeInMinutes = $this->utils->getMinutesFromTime($departureDate['time']);

        $prevArrivalDate = $this->utils->date_time_parts($prevSegment->getFlightArrival()->getDateTime(), $prevSegment->getFlightArrival()->getTimeZoneGmtOffset());
        $prevArrivalTimeInMinutes   = $this->utils->getMinutesFromTime($prevArrivalDate['time']);
        $stopDuration = $departureTimeInMinutes - $prevArrivalTimeInMinutes;

        $stopDuration = ($stopDuration > 0) ? $this->utils->duration_to_string($this->utils->mins_to_duration($stopDuration)): $this->utils->duration_to_string($this->utils->mins_to_duration(1440 + $stopDuration));

        $flightStops = new FlightStops();
        $flightStops->setStops(1);
        $flightStops->setCity($prevSegment->getFlightArrival()->getAirport()->getCity());

        $flightStops->setMessage(
            vsprintf(
                $this->translator->trans("Layover in %s for %s"),
                array(
                    $prevSegment->getFlightArrival()->getAirport()->getCity()->getName(),
                    $stopDuration
                )
            )
        );

        return $flightStops;
    }

    /**
     * This method calculates fare, usually the total base fare
     *
     * @param float $fare
     * @param string $currencyCode
     * @param float $discount
     *
     * @return float the converted fare
     */
    function calculateFare($fare, $currencyCode, $discount)
    {
        $config = $this->sabreService->getConfig();

        $defaultCurrency = $config->DEFAULT_CURRENCY;
        $currencyPCC = $config->CURRENCY_PCC;

        $currencyCode = $currencyCode ? $currencyCode : $defaultCurrency;
        $conversionRate = $this->currencyService->getConversionRate($currencyPCC, $currencyCode);

        $originalDiscountedPrice = ( $fare <= $discount) ? $fare : ($fare - $discount);

        $newConvertedFare = $this->currencyService->currencyConvert($originalDiscountedPrice, $conversionRate);

        return number_format($newConvertedFare, 2, '.', ',');
    }

    /**
     * This method calculates taxes, usually the total base taxes
     *
     * @param float $taxes
     * @param string $currencyCode
     *
     * @return float the converted taxes
     */
    function calculateTaxes($taxes, $currencyCode)
    {
        $config = $this->sabreService->getConfig();

        $defaultCurrency = $config->DEFAULT_CURRENCY;
        $currencyPCC = $config->CURRENCY_PCC;

        $currencyCode = $currencyCode ? $currencyCode : $defaultCurrency;

        $conversionRate = $this->currencyService->getConversionRate($currencyPCC, $currencyCode);

        $newConvertedTaxes = $this->currencyService->currencyConvert($taxes, $conversionRate);

        return number_format($newConvertedTaxes, 2, '.', ',');;
    }


    /** Get flight details
     *
     * @param $uuid or transactionId
     *
     * @return object
     */
    public function getFlightDetails($uuid)
    {
        $payment = $this->flightRepository->getPaymentByUuid($uuid);

        $response = new flightVO();
        $response->setStatus('success');
        if (!$payment) {
            $error = $this->translator->trans('Error! No transaction found');
            $response->setStatus('error');
            $response->setCode(100);
            $response->setMessage($error);
            return $response;
        }

        $details      = new FlightDetails();
        $validPayment = $this->checkFlightDetailsValidFromPayment($payment);
        $pnr          = $payment->getPassengerNameRecord();

        $details->getPassengerNameRecord()->setIsValidForPayment($validPayment);
        $details->getPassengerNameRecord()->setPaymentUUID($payment->getUUID());
        $details->getPassengerNameRecord()->setPnr($pnr->getPnr());
        $details->getPassengerNameRecord()->setUserId($payment->getUserId());
        $details->getPassengerNameRecord()->setFirstName($pnr->getFirstName());
        $details->getPassengerNameRecord()->setSurname($pnr->getSurname());
        $details->getPassengerNameRecord()->setCountryOfResidence($pnr->getCountryOfResidence());
        $details->getPassengerNameRecord()->setEmail($pnr->getEmail());
        $details->getPassengerNameRecord()->setMobileCountryCode($pnr->getMobileCountryCode());
        $details->getPassengerNameRecord()->setSpecialRequirement($pnr->getSpecialRequirement());
        $details->getPassengerNameRecord()->setMembershipId($pnr->getMembershipId());

        //getting passenger's data
        $passengers   = $payment->getPassengerNameRecord()->getPassengerDetails();
        $passengerArr = array();
        foreach ($passengers as $key => $passenger) {
            $passengerDetail = new PassengerDetails();
            $passengerDetail->setFirstName($passenger->getFirstName());
            $passengerDetail->setSurname($passenger->getSurname());
            $passengerDetail->setGender($passenger->getGender());
            $passengerDetail->setType($passenger->getType());
            $passengerDetail->setPassportNumber($passenger->getPassportNo());
            $passengerDetail->setPassportExpiry($passenger->getPassportExpiry());
            $passengerDetail->setIdNo($passenger->getIdNo());
            $passengerArr[]  = $passengerDetail;
        }

        $details->getPassengerNameRecord()->setPassengerDetails($passengerArr);

        $flightDetail    = $pnr->getFlightDetails();
        $flightSegements = array();
        $refundable      = $pnr->getFlightInfo()->isRefundable();
        foreach ($flightDetail as $flight) {
            $segment = new FlightSegment();
            $segment->setRefundable($refundable);
            $segment->setFlightNumber($flight->getFlightNumber());
            $segment->setIsStop($flight->getStopIndicator());
            $segment->setDuration($flight->getFlightDuration());
            $segment->setFlightType($flight->getType());

            //cabin data
            $cabinData = $this->populateCabinData($flight->getCabin());
            $segment->setCabinSelected($cabinData->getName());

            //adding flightstops
            $segment->getFlightStops()->setIndicator($flight->getStopIndicator());
            $segment->getFlightStops()->setDuration($flight->isStopDuration());

            //airline details
            $airlineData = $this->populateMarketingAndOperatingAirlineData($flight->getOperatingAirline());
            $segment->getOperatingAirline()->setAirlineCode($airlineData->getAirlineCode());
            $segment->getOperatingAirline()->setAirlineName($airlineData->getAirlineName());

            //departure details
            $departureAirport  = $this->populateDepartureAndArrivalData($flight->getDepartureAirport());
            $departureTerminal = ($departureAirport->getTerminalId()) ? 'Terminal '.$departureAirport->getTerminalId() : '';
            $segment->getFlightDeparture()->setDateTime($flight->getDepartureDateTime()->format('Y-m-d H:i:s'));
            $segment->getFlightDeparture()->getAirport()->getCity()->setName($departureAirport->getCity()->getName());
            $segment->getFlightDeparture()->getAirport()->getCity()->setCode($departureAirport->getCity()->getCode());
            $segment->getFlightDeparture()->getAirport()->setName($departureAirport->getName());
            $segment->getFlightDeparture()->getAirport()->setCode($departureAirport->getCode());
            $segment->getFlightDeparture()->getAirport()->setTerminalId($departureTerminal);

            //arrival details
            $arrivalAirport  = $this->populateDepartureAndArrivalData($flight->getArrivalAirport());
            $arrivalTerminal = ($arrivalAirport->getTerminalId()) ? 'Terminal '.$arrivalAirport->getTerminalId() : '';
            $segment->getFlightArrival()->setDateTime($flight->getArrivalDateTime()->format('Y-m-d H:i:s'));
            $segment->getFlightArrival()->getAirport()->getCity()->setName($arrivalAirport->getCity()->getName());
            $segment->getFlightArrival()->getAirport()->getCity()->setCode($arrivalAirport->getCity()->getCode());
            $segment->getFlightArrival()->getAirport()->setName($arrivalAirport->getName());
            $segment->getFlightArrival()->getAirport()->setCode($arrivalAirport->getCode());
            $segment->getFlightArrival()->getAirport()->setTerminalId($arrivalTerminal);

            //baggage allowance
            $baggages = array();
            if ($flight->getLeavingBaggageInfo()) {
                $baggageAllowance = new PassengerInfoBaggage();
                $baggageAllowance->setWeight($flight->getLeavingBaggageInfo());
                $baggageAllowance->setIsReturning(0);
                $baggages[]       = $baggageAllowance;
            }
            if ($flight->getReturningBaggageInfo()) {
                $baggageAllowance = new PassengerInfoBaggage();
                $baggageAllowance->setWeight($flight->getReturningBaggageInfo());
                $baggageAllowance->setIsReturning(1);
                $baggages[]       = $baggageAllowance;
            }
            $segment->setBaggageAllowance($baggages);
            $flightSegements[] = $segment;
        }

        $details->getFlightIteneraryGrouped()->getFlightItenerary()->setFlightSegment($flightSegements);

        $details->getFlightIteneraryGrouped()->getFlightItenerary()->getFlightItineraryPricingInfo()->setPrice(number_format($pnr->getFlightInfo()->getDisplayPrice(), 2, '.', ','));
        $details->getFlightIteneraryGrouped()->getFlightItenerary()->getFlightItineraryPricingInfo()->setBaseFare(number_format($pnr->getFlightInfo()->getDisplayBaseFare(), 2, '.', ','));
        $details->getFlightIteneraryGrouped()->getFlightItenerary()->getFlightItineraryPricingInfo()->setTaxes(number_format($pnr->getFlightInfo()->getDisplayTaxes(), 2, '.', ','));
        $details->getFlightIteneraryGrouped()->getFlightItenerary()->getCurrency()->setCode($pnr->getFlightInfo()->getDisplayCurrency());

        //@TODO: Please work on exception REST API once document is ready and approved for development
        $response->setStatus('success');
        $response->setData($details);

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
     *  IF payment.payment_type EQ coa(this is usually from corporate)
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
    public function checkFlightDetailsValidFromPayment($payment)
    {
        if ($payment->getType() == 'flights') {

            $paymentType     = $payment->getPaymentType();
            $responseMessage = $payment->getResponseMessage();
            $command         = $payment->getCommand();

            if ($paymentType == 'coa' && $responseMessage == 'BYPASSED' && ($command == 'PURCHASE' || $command == 'PROCESS_PAYMENT')) {
                return true;
            }

            if ($paymentType == 'cc' && $responseMessage == 'SUCCESS' && $command == 'PURCHASE') {
                return true;
            }
        }

        return false;
    }

}
