<?php
namespace FlightBundle\vendors\sabre;

use FlightBundle\Model\EmailData;
use FlightBundle\Model\RequestData;
use FlightBundle\Model\Passenger;
use Symfony\Component\DependencyInjection\Container;
use FlightBundle\Entity\PassengerNameRecord;
use FlightBundle\Entity\PassengerDetail;
use FlightBundle\Entity\FlightDetail;
use FlightBundle\Entity\FlightInfo;

class PassengerHandler
{
	private $emailData;
	private $container;
	private $requestData;
	private $translator;
	private $passenger;
	
	private $defaultCurrency = "USD";
    private $currencyPCC     = "AED";
	
	public function __construct(EmailData $emailData, Container $container, RequestData $requestData, Passenger $passenger)
	{
		$this->emailData = $emailData;
		$this->container = $container;
		$this->requestData = $requestData;
		$this->translator = $container->get('translator');
		$this->passenger = $passenger;
	}
	
	/**
	 * this will return email data object to be used for sending of flight details to email
	 * @param object $payment, string $transactionId
	 * @return object $emailData
	 **/
	public function addEmailData($controller, $payment, $transactionId)
	{
	$multiDestination = $payment->getPassengerNameRecord()->getFlightInfo()->isMultiDestination();

        $pnrId      = $payment->getPassengerNameRecord()->getPnr();
        $passengers = $payment->getPassengerNameRecord()->getPassengerDetails();

        $passengersArray = array();
        foreach ($passengers as $key => $passenger) {

            $passengersArray[$key]['first_name']             = $passenger->getFirstName();
            $passengersArray[$key]['surname']                = $passenger->getSurname();
            $passengersArray[$key]['type']                   = $passenger->getType();
            $passengersArray[$key]['fare_calc_line']         = $passenger->getFareCalcLine();
            $passengersArray[$key]['leaving_baggage_info']   = $passenger->getLeavingBaggageInfo();
            $passengersArray[$key]['returning_baggage_info'] = $passenger->getReturningBaggageInfo() == null ? "" : $passenger->getReturningBaggageInfo();
            $passengersArray[$key]['ticket_number']          = ($passenger->getTicketNumber() != null?$passenger->getTicketNumber():'');

        }
  
        $flightSegments = array();
        $flightDetail   = $payment->getPassengerNameRecord()->getFlightDetails();
        $airlinePnr = array();
        $stop_index = 0;

        foreach ($flightDetail as $index => $flight) {
            
            $flightInfo       = array();
            $departureAirport = $this->container->get('FlightServices')->findAirport($flight->getDepartureAirport());
            $arrivalAirport   = $this->container->get('FlightServices')->findAirport($flight->getArrivalAirport());

            if ($multiDestination) {
                $flightSegments[$flight->getType()]['destination_city'] = ($departureAirport) ? $departureAirport->getCity() : $flight->getArrivalAirport();
                $flightSegments[$flight->getType()]['country_code']     = ($departureAirport) ? $departureAirport->getCountry() : "";
                $flightSegments[$flight->getType()]['city_id']          = ($departureAirport) ? $departureAirport->getCityId() : 0;
            } else {
                $flightSegments[$flight->getType()]['destination_city'] = ($arrivalAirport) ? $arrivalAirport->getCity() : $flight->getArrivalAirport();
                $flightSegments[$flight->getType()]['country_code']     = ($arrivalAirport) ? $arrivalAirport->getCountry() : "";
                $flightSegments[$flight->getType()]['city_id']          = ($arrivalAirport) ? $arrivalAirport->getCityId() : 0;
            }

            if (!$flight->getStopIndicator()) {
                $flightSegments[$flight->getType()]['origin_city']         = ($departureAirport) ? $departureAirport->getCity() : $flight->getDepartureAirport();
                $flightSegments[$flight->getType()]['departure_main_date'] = ($arrivalAirport) ? $flight->getDepartureDateTime()->format('M j Y') : $flight->getArrivalAirport();
            }

            $mainAirline = $this->container->get('FlightServices')->findAirline($flight->getAirline());
            $flightSegments[$flight->getType()]['main_airline'] = ($mainAirline) ? $mainAirline->getAlternativeBusinessName() : $flight->getAirline();

            $flightInfo['departure_date']      = $flight->getDepartureDateTime()->format('M j Y');
            $flightInfo['departure_time']      = $flight->getDepartureDateTime()->format('H\:i');
            $flightInfo['origin_city']         = ($departureAirport) ? $departureAirport->getCity() : "";
            $flightInfo['origin_airport']      = ($departureAirport) ? $departureAirport->getName() : "";
            $flightInfo['origin_airport_code'] = $flight->getDepartureAirport();

            $flightInfo['arrival_date']             = $flight->getArrivalDateTime()->format('M j Y');
            $flightInfo['arrival_time']             = $flight->getArrivalDateTime()->format('H\:i');
            $flightInfo['destination_airport_code'] = $flight->getArrivalAirport();
            $flightInfo['destination_airport']      = ($arrivalAirport) ? $arrivalAirport->getName() : "";
            $flightInfo['destination_city']         = ($arrivalAirport) ? $arrivalAirport->getCity() : "";

            $deptime = $flight->getDepartureDateTime();
            $arrtime = $flight->getArrivalDateTime();
            $diff = $deptime->diff($arrtime);
            $flightInfo['flight_duration_attr'] = (($diff->h > 0) ? sprintf('%02d', $diff->h) . 'h ' : '') . sprintf('%02d', $diff->i) . 'm';

            $airlineName                 = $this->container->get('FlightServices')->findAirline($flight->getAirline());
            $flightInfo['airline_name']  = ($airlineName) ? $airlineName->getAlternativeBusinessName() : $flight->getAirline();
            $flightInfo['flight_number'] = $flight->getFlightNumber();
            $flightInfo['airline_code']  = $flight->getAirline();

            $flightInfo['airline_pnr'] = $flight->getAirlinePnr();
            $airlinePnr[$flight->getAirlinePnr()] = $flightInfo['airline_name'];

            $operatingAirlineName                 = $this->container->get('FlightServices')->findAirline($flight->getOperatingAirline());
            $flightInfo['operating_airline_code'] = $flight->getOperatingAirline();
            $flightInfo['operating_airline_name'] = ($operatingAirlineName) ? $operatingAirlineName->getAlternativeBusinessName() : $flight->getAirline();

            $cabinName                     = $this->container->get('FlightServices')->FlightCabinFinder($flight->getCabin());
            $flightInfo['cabin']           = ($cabinName) ? $cabinName->getName() : $flight->getCabin();
            $flightInfo['flight_duration'] = $flight->getFlightDuration();
            $flightInfo['stop_indicator']  = $flight->getStopIndicator();
            $flightInfo['stop_duration']  = $flight->isStopDuration();
            $flightInfo['stop_info']       = "";
            $flightInfo['departure_terminal_id'] = $flight->getDepartureTerminalId();
            $flightInfo['arrival_terminal_id'] = $flight->getArrivalTerminalId();

            if(empty($flightInfo['departure_terminal_id'])){
                $flightInfo['departure_terminal'] = '';
            }else{
                $flightInfo['departure_terminal'] = 'Terminal ' . $flightInfo['departure_terminal_id'];
            }

            if(empty($flightInfo['arrival_terminal_id'])){
                $flightInfo['arrival_terminal'] = '';
            }else{
                $flightInfo['arrival_terminal'] = 'Terminal ' . $flightInfo['arrival_terminal_id'];
            }

            if($flight->getType() == "leaving"){
                $flightInfo['leaving_baggage_info'] = $flight->getLeavingBaggageInfo();
            }elseif($flight->getType() == "returning"){
                $flightInfo['returning_baggage_info'] = $flight->getReturningBaggageInfo();
            }

            if ($flightInfo['stop_indicator'] == 1 && $multiDestination){ $flightSegments[$flight->getType()]['flight_info'][$stop_index - 1]['stop_info'][] = $flightInfo;
            }else{ 
                $stop_index++;
                $flightSegments[$flight->getType()]['flight_info'][] = $flightInfo;
            }
        }

        //emailData['flight_segments'] = $flightSegments;
        $this->emailData->setFlightSegments($flightSegments);
        //$emailData['passenger_details'] = $passengersArray;
        $this->emailData->setPassengerDetails($passengersArray);

        //$emailData['price'] = $payment->getPassengerNameRecord()->getFlightInfo()->getDisplayPrice();
        $this->emailData->setPrice($payment->getPassengerNameRecord()->getFlightInfo()->getDisplayPrice());
        //$emailData['currency'] = $payment->getPassengerNameRecord()->getFlightInfo()->getDisplayCurrency();
        $this->emailData->setCurrency($payment->getPassengerNameRecord()->getFlightInfo()->getDisplayCurrency());

        if ($payment->getCouponCode() && $payment->getDisplayAmount() != $payment->getDisplayOriginalAmount()) {
        	//$emailData['discounted_price'] = round($payment->getDisplayAmount() + 0, 2);
        	$this->emailData->setDiscountedPrice(round($payment->getDisplayAmount() + 0, 2));
        }

        //$emailData['base_fare'] = $payment->getPassengerNameRecord()->getFlightInfo()->getDisplayBaseFare();
        $this->emailData->setBaseFare($payment->getPassengerNameRecord()->getFlightInfo()->getDisplayBaseFare());
        //$emailData['taxes'] = $payment->getPassengerNameRecord()->getFlightInfo()->getDisplayTaxes();
        $this->emailData->setTaxes($payment->getPassengerNameRecord()->getFlightInfo()->getDisplayTaxes());
        //$emailData['pnr'] = $pnrId;
        $this->emailData->setPnr($pnrId);
        //$emailData['transaction_id'] = $transactionId;
        $this->emailData->setTransactionId($transactionId);
        //$emailData['special_requirement'] = $payment->getPassengerNameRecord()->getSpecialRequirement();
        $this->emailData->setSpecialRequirement($payment->getPassengerNameRecord()->getSpecialRequirement());
        //$emailData['email'] = $payment->getPassengerNameRecord()->getEmail();
        $this->emailData->setEmail($payment->getPassengerNameRecord()->getEmail());
        //$emailData['refundable'] = $payment->getPassengerNameRecord()->getFlightInfo()->isRefundable();
        $this->emailData->setRefundable($payment->getPassengerNameRecord()->getFlightInfo()->isRefundable());
        //$emailData['one_way'] = $payment->getPassengerNameRecord()->getFlightInfo()->isOneWay();
         $this->emailData->setOneWay($payment->getPassengerNameRecord()->getFlightInfo()->isOneWay());
        //$emailData['multi_destination'] = $multiDestination;
        $this->emailData->setMultiDestination($multiDestination);
        $this->emailData->setAirlinePnr($airlinePnr);

        $countryCode   = $flightSegments['leaving']['country_code'];
        $cityId        = $flightSegments['leaving']['city_id'];
        $getLocationId = $this->container->get('FlightServices')->cmsHotelCityInfo($cityId);
        $locationId    = ($getLocationId) ? $getLocationId->getLocationId() : 0;
        $hotelCityInfo = ($locationId > 0 || $countryCode != '' || !empty($countryCode)) ? [] : [];

        $hotels_array = [];
        if ($hotelCityInfo && count($hotelCityInfo) > 0) {
            foreach ($hotelCityInfo[0] as $v_item) {
                $varr            = array();
                $stars           = ceil($v_item['_source']['stars']);
                if ($stars > 5) $stars           = 5;
                    $varr['stars']   = $stars;
                    $varr['id']      = intval($v_item['_source']['id']);
                    $varr['name']    = $this->container->get('app.utils')->htmlEntityDecode($v_item['_source']['name']);
                    $varr['namealt'] = $this->container->get('app.utils')->cleanTitleDataAlt($v_item['_source']['name']);
                    $varr['title']   = addslashes($varr['name']);
                    $varr['link']    = $this->container->get('TTRouteUtils')->returnHotelDetailedLink('en', $v_item['_source']['name'], $v_item['_source']['id']);
                    $def_array       = $this->container->get('FlightServices')->cmsHotelImageInfo($v_item['_source']['id']);
                    $imgarr          = array();
                    if (sizeof($def_array) > 0) {
                        $imgarr = $def_array[0];
                    }
                    $varr['img']    = $this->container->get("HRSServices")->createImageSource($imgarr, 1, 1);
                    $hotels_array[] = $varr;
                }
            }

            //$emailData['enableCancelation'] = true;
            $this->emailData->setEnableCancelation(true);
            //$emailData['hotels'] = $hotels_array;
            $this->emailData->setHotels($hotels_array);

            return $this->emailData;
	}
	
	public function bookFlightRequestData($request)
	{
        
        $passengerNameRecord = new PassengerNameRecord();
        
        //$setFlightDetails = $this->flightHandler->setFlightDetails($passengerNameRecord, $this->requestData, $request);
        $setPassengers = $this->setPassengers($setFlightDetails['passengerNameRecord'], $this->requestData, $request);
        
        $response['requestData'] = $setFlightDetails['requestData'];
        $response['passengerNameRecord'] = $setPassengers['passengerNameRecord'];
        $response['passengersArray'] = $setPassengers['passenger']->getPassengers();
        $response['flightSegments'] = $setFlightDetails['flightSegments'];
        $response['isChinaDomestic'] = $setFlightDetails['isChinaDomestic'];
        
        return $response;
        //return $this->requestData;
	}
	
	public function setPassengers($passengersArray)
	{
     	return $this->passengers->setPassengers($passengersArray);   
	}
}