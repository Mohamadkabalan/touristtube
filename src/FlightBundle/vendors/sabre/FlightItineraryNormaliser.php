<?php

namespace FlightBundle\vendors\sabre;

use FlightBundle\Model\FlightItinerary;
use FlightBundle\Model\FlightItinerarySegment;
use TTBundle\Utils\Utils;
use TTBundle\Utils\TTRouteUtils;
use TTBundle\Services\CurrencyService;
use FlightBundle\Repository\Flight\FlightRepositoryServices;
use FlightBundle\Model\AirlineInfo;
use FlightBundle\Model\FlightDeparture;
use FlightBundle\Model\FlightArrival;
use FlightBundle\Model\TpaExtensions;
use FlightBundle\Model\FlightItineraryPassengerInfo;
use FlightBundle\Model\FlightItineraryPassengerInfoBaggage;
use FlightBundle\Model\FlightItineraryFairInfo;

/**
 * Flight Itinerary Normaliser
 *
 * This class serves as parser from the response gevin from the Sabre.
 * This will also normalize the data into FlightItinerary Object
 *
 */
class FlightItineraryNormaliser {
    
    
    /**
     * @var Utils
     */
    private $utils;
    
    /**
     * @var CurrencyService
     */
    private $currencyService;
    
    /**
     * @var FlightRepositoryServices
     */
    private $flightRepositoryServices;
    
    /**
     * @var translator
     */
    private $translator;
    private $TTRouteUtils;
	
	private $airports_list;
	private $airlines_list;
	private $air_equip_type_list;
	private $cabin_list;
    
    public function __construct( Utils $utils, CurrencyService $currencyService, FlightRepositoryServices $flightRepositoryServices, $translator, TTRouteUtils $TTRouteUtils ){
        $this->utils = $utils;
        $this->currencyService = $currencyService;
        $this->flightRepositoryServices = $flightRepositoryServices;
        $this->translator = $translator;
        $this->TTRouteUtils = $TTRouteUtils;
		
		$this->airports_list = array();
		$this->airlines_list = array();
		$this->air_equip_type_list = array();
		$this->cabin_list = array();
    }
    
    /**
     * @var response
     */
    protected $response;
    
    /**
     *
     * @param $response Sabre Response, usually from Bargain Request
     *
     * Normalize response into readable format (Old data structure)
     *
     * @return mixed
     */
    function normalize( $response, $discount = 0, $currency_code = "", $default_currency = "", $currency_pcc = "", $oneWay = 1, $fromDate = "", $flexibleDate = "", $toDate = "", $multiDestination = "", $numberInParty, $salt)
    {
        if (empty($response['data']))
			return false;

		$normalizeData = $this->_normalize($response['data'],$discount,$currency_code,$default_currency,$currency_pcc,$oneWay,$fromDate,$flexibleDate,$toDate,$multiDestination,$numberInParty,$salt);

		$inboundAirlines = [];
		$outboundAirlines = [];
		
		if (!empty($response['inbound'])){

			$normalizeData['inbound'] = $this->_normalize($response['inbound'],$discount,$currency_code,$default_currency,$currency_pcc,$oneWay,$fromDate,$flexibleDate,$toDate,$multiDestination,$numberInParty,$salt);
			$inboundAirlines = $normalizeData['inbound']['airlines'];
		}
		if (!empty($response['outbound'])){

		    $normalizeData['outbound'] = $this->_normalize($response['outbound'],$discount,$currency_code,$default_currency,$currency_pcc,$oneWay,$fromDate,$flexibleDate,$toDate,$multiDestination,$numberInParty,$salt);
			$outboundAirlines = $normalizeData['outbound']['airlines'];
		}

		$normalizeData['airlines'] = array_merge($normalizeData['airlines'], $outboundAirlines);

//            $normalizeData['airlines'] = array_merge($normalizeData['airlines'], array_merge($inboundAirlines,$outboundAirlines));

		// free memory
		$this->airports_list = array();
		$this->airlines_list = array();
		$this->air_equip_type_list = array();
		$this->cabin_list = array();

		return $normalizeData;
    }
    
    protected function _normalize( $response, $discount = 0, $currency_code = "", $default_currency = "", $currency_pcc = "", $oneWay = 1, $fromDate = "", $flexibleDate = "", $toDate = "", $multiDestination = "", $numberInParty, $salt)
    {

        $flightItineraries = $this->convertArrayIteniraryToObject($response);

        $currencyCode = ($currency_code == "") ? $default_currency : $currency_code;
        $conversionRate = $this->currencyService->getConversionRate($currency_pcc, $currencyCode);
        $noLogo = "no-logo.jpg";
        
        $minimumDuration = 0;
        $maximumDuration = 0;
        $minimumPrice = 0;
        $maximumPrice = 0;

        if ($flightItineraries){

            $itineraries = [];
            $segmentCount  = 0;
            $airlines = [];
            $departure_airports = [];

            foreach($flightItineraries as $itinerary => $flightItinerary){

                $secToken = [];

                $extra = $flightItinerary->getExtra();

                $flightSegments = $flightItinerary->getFlightSegments();
                $n_flight_segments = 1;

                $currency = (!$extra['currency_code']) ? $defaultCurrency : $extra['currency_code'];
                $providerPrice = $extra['amount'];
                $originalPrice = $providerPrice; //($providerPrice <= $discount) ? $providerPrice : ($providerPrice - $discount);
                $newConvertedPrice = ($originalPrice <= $discount) ? $originalPrice : ($originalPrice - $discount);
                $newConvertedPrice = $this->currencyService->currencyConvert($newConvertedPrice, $conversionRate);
                $newPrice = number_format($newConvertedPrice, 2, '.', ',');

                array_push($secToken, $numberInParty, $currency);
                array_push($secToken, $newPrice, $currencyCode, $newConvertedPrice, $originalPrice);

                foreach($flightSegments as $segmentKey => $segments){

                    $n_flight_segments = count($segments);

                    foreach($segments as $segment_index => $segment){


                        if ($minimumDuration == 0) {
                            $minimumDuration = $segment->getFlightDuration();
                        } else if ($minimumDuration >= $segment->getFlightDuration()) {
                            $minimumDuration = $segment->getFlightDuration();
                        }

                        if ($maximumDuration == 0) {
                            $maximumDuration = $segment->getFlightDuration();
                        } else if ($maximumDuration <= $segment->getFlightDuration()) {
                            $maximumDuration = $segment->getFlightDuration();
                        }

                        $departure_date = $this->utils->date_time_parts($segment->getFlightDeparture()->getDepartureDateTime(), $segment->getFlightDeparture()->getDepartureTimeZoneGmtOffset());
                        $arrival_date = $this->utils->date_time_parts($segment->getFlightArrival()->getArrivalDateTime(), $segment->getFlightArrival()->getArrivalTimeZoneGmtOffset());

                        $param = [];

						if (!isset($this->airlines_list[$segment->getAirlineInfo()->getMarketingAirlineCode()]))
							$this->airlines_list[$segment->getAirlineInfo()->getMarketingAirlineCode()] = $this->flightRepositoryServices->findAirline($segment->getAirlineInfo()->getMarketingAirlineCode());

                        $airline = $this->airlines_list[$segment->getAirlineInfo()->getMarketingAirlineCode()];

                        $param['airline_name'] = ($airline) ? $airline->getAlternativeBusinessName() : $segment->getAirlineInfo()->getMarketingAirlineCode();

						if (!isset($this->airlines_list[$segment->getAirlineInfo()->getOperatingAirlineCode()]))
							$this->airlines_list[$segment->getAirlineInfo()->getOperatingAirlineCode()] = $this->flightRepositoryServices->findAirline($segment->getAirlineInfo()->getOperatingAirlineCode());

                        $operatingAirline = $this->airlines_list[$segment->getAirlineInfo()->getOperatingAirlineCode()];

                        $param['operating_airline_name']  = ($operatingAirline) ? $operatingAirline->getAlternativeBusinessName() : $segment->getAirlineInfo()->getOperatingAirlineCode();

                        $airlineLogo = ($airline) ? $airline->getLogo() : $noLogo;

                        $param['airline_logo'] = $this->TTRouteUtils->generateMediaURL('/media/images/airline-logos/'.$airlineLogo);
                        $param['airline_logo_mobile'] = 'media/images/airline-logos/mobile/'.$airlineLogo;
                        $param['airline'] = $segment->getAirlineInfo()->getMarketingAirlineCode();
                        $param['operating_airline'] = $segment->getAirlineInfo()->getOperatingAirlineCode();
                        $param['flight_number'] = $segment->getFlightnumber();

                        array_push(
                            $secToken,
                            $param['flight_number'],
                            $param['airline'],
                            $param['operating_airline'],
                            $param['airline_name'],
                            $param['operating_airline_name']
                        );
                        
                        $param['departure_date_time'] = $departure_date['date'].'<br/>'.$departure_date['time'];
                        $param['departure_time'] = $departure_date['time'];
                        $param['departure_airport_code'] = $segment->getFlightDeparture()->getDepartureAirportLocationCode();
                        $param['departure_terminal_id'] =  $segment->getFlightDeparture()->getDepartureAirportTerminalId();
                        
						if (!isset($this->airports_list[$segment->getFlightDeparture()->getDepartureAirportLocationCode()]))
							$this->airports_list[$segment->getFlightDeparture()->getDepartureAirportLocationCode()] = $this->flightRepositoryServices->findAirport($segment->getFlightDeparture()->getDepartureAirportLocationCode());
                        
						$departureCityAirport = $this->airports_list[$segment->getFlightDeparture()->getDepartureAirportLocationCode()];
                        
                        $param['departure_airport_city'] = ($departureCityAirport == null) ? $segment->getFlightDeparture()->getDepartureAirportLocationCode() : $departureCityAirport->getCity();
                        $param['departure_airport_name'] = ($departureCityAirport == null) ? $segment->getFlightDeparture()->getDepartureAirportLocationCode() : $departureCityAirport->getName();
                        
						if (!isset($this->air_equip_type_list[$segment->getEquipmentAirEquipType()]))
						{
							$this->air_equip_type_list[$segment->getEquipmentAirEquipType()] = $this->flightRepositoryServices->getAircraftTypeInfo($segment->getEquipmentAirEquipType());
							
							if(!$this->air_equip_type_list[$segment->getEquipmentAirEquipType()])
								$this->air_equip_type_list[$segment->getEquipmentAirEquipType()] = array();
                        }
						
						if (!empty($this->air_equip_type_list[$segment->getEquipmentAirEquipType()]) && is_object($this->air_equip_type_list[$segment->getEquipmentAirEquipType()]))
							$param['aircraft_type'] = $this->air_equip_type_list[$segment->getEquipmentAirEquipType()]->getManufacturer().' - '.$this->air_equip_type_list[$segment->getEquipmentAirEquipType()]->getModel();
						else
							$param['aircraft_type'] = '';
						
                        array_push(
                            $secToken,
                            $param['departure_airport_code'],
                            $param['departure_airport_city'],
                            $param['departure_airport_name']
                        );
                        
                        $param['arrival_airport_code'] = $segment->getFlightArrival()->getArrivalAirportLocationCode();
                        $param['arrival_terminal_id'] =  $segment->getFlightArrival()->getArrivalAirportTerminalId();
						
						if (!isset($this->airports_list[$segment->getFlightArrival()->getArrivalAirportLocationCode()]))
							$this->airports_list[$segment->getFlightArrival()->getArrivalAirportLocationCode()] = $this->flightRepositoryServices->findAirport($segment->getFlightArrival()->getArrivalAirportLocationCode());
						
                        $arrivalCityAirport = $this->airports_list[$segment->getFlightArrival()->getArrivalAirportLocationCode()];
                        
                        $param['arrival_airport_city'] = ($arrivalCityAirport == null) ? $segment->getFlightArrival()->getArrivalAirportLocationCode() : $arrivalCityAirport->getCity();
                        $param['arrival_airport_name'] = ($arrivalCityAirport == null) ? $segment->getFlightArrival()->getArrivalAirportLocationCode() : $arrivalCityAirport->getName();
                        
                        array_push($secToken,
                            $param['arrival_airport_code'],
                            $param['arrival_airport_city'],
                            $param['arrival_airport_name']
                        );
                        
                        $param['arrival_date_time']     = $arrival_date['date'].'<br/>'.$arrival_date['time'];
                        $param['arrival_time'] = $arrival_date['time'];
                        $param['total_flight_segments'] = $flightItinerary->getTotalflightSegments();
                        
                        array_push(
                            $secToken,
                            $param['total_flight_segments']
                        );
                        
                        $param['segment_count'] = $segmentCount;
                        $param['original_departure_date_time'] = $segment->getFlightDeparture()->getDepartureDateTime();
                        
                        array_push(
                            $secToken,
                            $param['original_departure_date_time']
                        );
                        
                        $param['original_arrival_date_time'] = $segment->getFlightArrival()->getArrivalDateTime();
                        
                        array_push(
                            $secToken,
                            $param['original_arrival_date_time']
                            );
                        
                        $param['res_book_desig_code'] = $segment->getResBookDesigCode();
                        
                        array_push(
                            $secToken,
                            $param['res_book_desig_code']
                        );

                        $param['fare_basis_code'] = $segment->getFareBasisCode();
                        
                        array_push(
                            $secToken,
                            $param['fare_basis_code']
                        );
                        
                        $param['flight_type'] = ( $segment->getOriginDestinationIndex() == 1 && !$multiDestination)? "returning" : "leaving";
                        
                        array_push(
                            $secToken,
                            $param['flight_type']
                        );
                        
                        if (!isset($this->cabin_list[$extra['fare_info'][$segment_index]['cabin']]))
                            $this->cabin_list[$extra['fare_info'][$segment_index]['cabin']] = $this->flightRepositoryServices->FlightCabinFinder($extra['fare_info'][$segment_index]['cabin']);
                        
						$cabin = $this->cabin_list[$extra['fare_info'][$segment_index]['cabin']];

                        $param['cabin'] = ($cabin) ? $cabin->getName() : $extra['fare_info'][$segment_index]['cabin'];
                        $param['cabin_code'] = $extra['fare_info'][$segment_index]['cabin'];

                        array_push(
                            $secToken,
                            $param['cabin'],
                            $param['cabin_code']
                        );

                        $departureTimeInMinutes = $this->utils->getMinutesFromTime($departure_date['time']);
                        $arrivalTimeInMinutes   = $this->utils->getMinutesFromTime($arrival_date['time']);

                        $param['departure_time_minutes_stop'] = $departureTimeInMinutes;
                        $param['arrival_time_minutes_stop']   = $arrivalTimeInMinutes;
                        $param['mile_distance'] = $segment->getTpaExtensions()->getTpaExtensionsMileageAmount();
                        $param['elapsedTime'] = $segment->getElapsedTime();
                        if (!($n_flight_segments > 1 && $segment_index)) {
                            $param['flight_duration'] = $this->utils->duration_to_string($this->utils->mins_to_duration($segment->getFlightDuration()));

                            array_push(
                                $secToken,
                                $param['flight_duration']
                            );

                            $param['flight_duration_attr'] = $segment->getFlightDuration();
                            $param['departure_time_minutes'] = $departureTimeInMinutes;
                            $param['arrival_time_minutes'] = $arrivalTimeInMinutes;
                            $param['departure_time_css_class'] = ($segment->getOriginDestinationIndex() == 1) ? "returning-departure-time-minutes": "leaving-departure-time-minutes";
                            $param['arrival_time_css_class'] = ($segment->getOriginDestinationIndex() == 1) ? "returning-arrival-time-minutes": "leaving-arrival-time-minutes";
                            $param['duration_css_class'] = ($segment->getOriginDestinationIndex() == 1) ? "returning-duration": "leaving-duration";
                            $param['stops_css_class'] = ($segment->getOriginDestinationIndex() == 1) ? "returning-stops": "leaving-stops";
                            $param['stops'] = $n_flight_segments - 1;
                            $param['stop_duration'] = 0;

                            array_push(
                                $secToken,
                                $param['stop_duration']
                            );

                            $param['stop_city'] = "";
                            $param['stop_indicator'] = 0;

                            array_push(
                                $secToken,
                                $param['stop_indicator']
                            );

                        } else {
                            $param['flight_duration'] = "";

                            array_push(
                                $secToken,
                                $param['flight_duration']
                            );

                            $param['flight_duration_attr'] = "";
                            $param['departure_time_minutes'] = "";
                            $param['arrival_time_minutes'] = "";
                            $param['departure_time_css_class'] = "";
                            $param['arrival_time_css_class'] = "";
                            $param['duration_css_class'] = "";
                            $param['stops_css_class'] = "";
                            $param['stops'] = "";

                            $prevSegment = $segments[$segment_index - 1];
                            $prev_arrival_date = $this->utils->date_time_parts($prevSegment->getFlightArrival()->getArrivalDateTime(), $prevSegment->getFlightArrival()->getArrivalTimeZoneGmtOffset());
                            $prevArrivalTimeInMinutes   = $this->utils->getMinutesFromTime($prev_arrival_date['time']);
                            $stopDuration = $param['departure_time_minutes_stop'] - $prevArrivalTimeInMinutes;

                            $param['stop_duration'] = ($stopDuration > 0) ? $this->utils->duration_to_string($this->utils->mins_to_duration($stopDuration)): $this->utils->duration_to_string($this->utils->mins_to_duration(1440 + $stopDuration));

                            array_push(
                                $secToken,
                                $param['stop_duration']
                            );

							if (!isset($this->airports_list[$segment->getFlightDeparture()->getDepartureAirportLocationCode()]))
								$this->airports_list[$segment->getFlightDeparture()->getDepartureAirportLocationCode()] = $this->flightRepositoryServices->findAirport($segment->getFlightDeparture()->getDepartureAirportLocationCode());

							$stopCity = $this->airports_list[$segment->getFlightDeparture()->getDepartureAirportLocationCode()];

                            $action_array = array();
                            $action_array[] = ($stopCity == null) ? $segment->getFlightDeparture()->getDepartureAirportLocationCode() : $stopCity->getCity();
                            $action_array[] = $param['stop_duration'];
                            $action_text_display = vsprintf($this->translator->trans("Layover in %s for %s"), $action_array);

                            $param['stop_city'] = $action_text_display;
                            $param['stop_indicator'] = 1;

                            array_push(
                                $secToken,
                                $param['stop_indicator']
                            );
                        }
                        
                        if (!($param['total_flight_segments'] > 1 && ($segmentKey || $segment_index))) {
                            $param['price']      = $extra['currency_code'].' '.$newConvertedPrice;
                            $param['price_attr'] = $newConvertedPrice;
                            
                            if ($minimumPrice == 0) {
                                $minimumPrice = $newConvertedPrice;
                            } else if ($minimumPrice >= $newConvertedPrice) {
                                $minimumPrice = $newConvertedPrice;
                            }
                            
                            if ($maximumPrice == 0) {
                                $maximumPrice = $newConvertedPrice;
                            } else if ($maximumPrice <= $newConvertedPrice) {
                                $maximumPrice = $newConvertedPrice;
                            }
                        } else {
                            $param['price'] = "";
                            $param['price_attr'] = "";
                        }
                        
                        $airlineLogo = ($airline) ? $airline->getLogo() : $noLogo;
                        
                        if (!array_key_exists($segment->getAirlineInfo()->getMarketingAirlineCode(), $airlines)) {
                            $airlines[$segment->getAirlineInfo()->getMarketingAirlineCode()]['name'] = $param['airline_name'];
                            $airlines[$segment->getAirlineInfo()->getMarketingAirlineCode()]['amount_attr'] = $newConvertedPrice;
                            $airlines[$segment->getAirlineInfo()->getMarketingAirlineCode()]['amount'] = $newPrice;
                            $airlines[$segment->getAirlineInfo()->getMarketingAirlineCode()]['airline_logo'] = $this->TTRouteUtils->generateMediaURL('/media/images/airline-logos/'.$airlineLogo);
                            $airlines[$segment->getAirlineInfo()->getMarketingAirlineCode()]['airline_logo_mobile'] = 'media/images/airline-logos/'.$airlineLogo;
                        }
                        else if ($newConvertedPrice < $airlines[$segment->getAirlineInfo()->getMarketingAirlineCode()]['amount_attr']) {
                            $airlines[$segment->getAirlineInfo()->getMarketingAirlineCode()]['amount'] = $newPrice;
                            $airlines[$segment->getAirlineInfo()->getMarketingAirlineCode()]['amount_attr'] = $newConvertedPrice;
                        }
                        
                        
                        $itineraries[$itinerary]['segments'][$segmentKey][] = $param;
                        
                        $segmentsGlob = array();
                        $segmentsGlob['price'] = $newPrice;
                        $segmentsGlob['price_attr'] = $newConvertedPrice;
                        $segmentsGlob['original_price'] = $originalPrice;
                        $segmentsGlob['provider_price'] = $providerPrice;


						if (!isset($this->airports_list[$flightSegments[0][0]->getFlightDeparture()->getDepartureAirportLocationCode()]))
							$this->airports_list[$flightSegments[0][0]->getFlightDeparture()->getDepartureAirportLocationCode()] = $this->flightRepositoryServices->findAirport($flightSegments[0][0]->getFlightDeparture()->getDepartureAirportLocationCode());
						
                        $_departureCityAirport = $this->airports_list[$flightSegments[0][0]->getFlightDeparture()->getDepartureAirportLocationCode()];
						
                        $departure_airports[$flightSegments[0][0]->getFlightDeparture()->getDepartureAirportLocationCode()][$newConvertedPrice] = [
                            'price' => $newPrice,
                            'city' => $_departureCityAirport->getCity(),
                            'price_attr' => $newConvertedPrice
                        ];
                        
                        if (!isset($this->airlines_list[$flightSegments[0][0]->getAirlineInfo()->getMarketingAirlineCode()]))
							$this->airlines_list[$flightSegments[0][0]->getAirlineInfo()->getMarketingAirlineCode()] = $this->flightRepositoryServices->findAirline($flightSegments[0][0]->getAirlineInfo()->getMarketingAirlineCode());
						
                        $airline = $this->airlines_list[$flightSegments[0][0]->getAirlineInfo()->getMarketingAirlineCode()];
						
                        $segmentsGlob['airline_name'] = ($airline) ? $airline->getAlternativeBusinessName() : $flightSegments[0][0]->getAirlineInfo()->getMarketingAirlineCode();
                        
						if (!isset($this->airlines_list[$flightSegments[0][0]->getAirlineInfo()->getOperatingAirlineCode()]))
							$this->airlines_list[$flightSegments[0][0]->getAirlineInfo()->getOperatingAirlineCode()] = $this->flightRepositoryServices->findAirline($flightSegments[0][0]->getAirlineInfo()->getOperatingAirlineCode());
						
						$operatingAirline = $this->airlines_list[$flightSegments[0][0]->getAirlineInfo()->getOperatingAirlineCode()];
						
                        $segmentsGlob['operating_airline_name'] = ($operatingAirline) ? $operatingAirline->getAlternativeBusinessName() : $flightSegments[0][0]->getAirlineInfo()->getOperatingAirlineCode();

                        $segmentsGlob['airline_logo'] = $this->TTRouteUtils->generateMediaURL('/media/images/airline-logos/'.$airlineLogo);
                        $segmentsGlob['airline_logo_mobile'] = 'media/images/airline-logos/mobile/'.$airlineLogo;
                        $segmentsGlob['airline'] = $flightSegments[0][0]->getAirlineInfo()->getMarketingAirlineCode();
                        $segmentsGlob['operating_airline'] = $flightSegments[0][0]->getAirlineInfo()->getOperatingAirlineCode();
                        $segmentsGlob['flight_number'] = $flightSegments[0][0]->getFlightNumber();
                        
                        $departure_date = $this->utils->date_time_parts($flightSegments[0][0]->getFlightDeparture()->getDepartureDateTime(), $flightSegments[0][0]->getFlightDeparture()->getDepartureTimeZoneGmtOffset());
                        
                        $segmentsGlob['departure_lowest_original_date_time'] = $flightSegments[0][0]->getFlightDeparture()->getDepartureDateTime();
                        $lowestDepartureDate = new \DateTime($segmentsGlob['departure_lowest_original_date_time']);
                        $segmentsGlob['departure_lowest_date_time_obj'] = $lowestDepartureDate;
                        $segmentsGlob['departure_lowest_timestamp'] = $lowestDepartureDate->getTimeStamp();
                        $segmentsGlob['departure_date_time'] = $departure_date['date'].'<br/>'.$departure_date['time'];
                        $segmentsGlob['departure_date'] = $departure_date['date'];
                        $segmentsGlob['departure_time'] = $departure_date['time'];
                        
                        $departureTime = new \DateTime($departure_date['time']);
                        if($departureTime->format('H') >= 0 && $departureTime->format('H') < 5){
                            $segmentsGlob['departureTimeOfDay'] = 'early_morning';
                        }elseif($departureTime->format('H') >= 5 && $departureTime->format('H') < 12){
                            $segmentsGlob['departureTimeOfDay'] = 'morning';
                        }elseif($departureTime->format('H') >= 12 && $departureTime->format('H') < 18){
                            $segmentsGlob['departureTimeOfDay'] = 'afternoon';
                        }elseif($departureTime->format('H') >= 18 && $departureTime->format('H') <= 23){
                            $segmentsGlob['departureTimeOfDay'] = 'evening';
                        }

						if (!isset($this->airports_list[$flightSegments[0][0]->getFlightDeparture()->getDepartureAirportLocationCode()]))
							$this->airports_list[$flightSegments[0][0]->getFlightDeparture()->getDepartureAirportLocationCode()] = $this->flightRepositoryServices->findAirport($flightSegments[0][0]->getFlightDeparture()->getDepartureAirportLocationCode());
						
                        $destinationCityAirport = $this->airports_list[$flightSegments[0][0]->getFlightDeparture()->getDepartureAirportLocationCode()];
                        
                        $segmentsGlob['departure_airport_code'] = $flightSegments[0][0]->getFlightDeparture()->getDepartureAirportLocationCode();
                        $segmentsGlob['departure_airport_city'] = ($destinationCityAirport == null) ? $segmentsGlob['departure_airport_code'] : $destinationCityAirport->getCity();
                        $segmentsGlob['departure_airport_name'] = ($destinationCityAirport == null) ? $segmentsGlob['departure_airport_code'] : $destinationCityAirport->getName();
                        
                        $segmentsGlob['arrival_airport_code'] = $flightSegments[0][sizeof($flightSegments[0]) - 1]->getFlightArrival()->getArrivalAirportLocationCode();
                        
						if (!isset($this->airports_list[$segmentsGlob['arrival_airport_code']]))
							$this->airports_list[$segmentsGlob['arrival_airport_code']] = $this->flightRepositoryServices->findAirport($segmentsGlob['arrival_airport_code']);
						
                        $arrivalCityAirport = $this->airports_list[$segmentsGlob['arrival_airport_code']];
                        
                        $segmentsGlob['arrival_airport_city'] = ($arrivalCityAirport == null) ? $segmentsGlob['arrival_airport_code'] : $arrivalCityAirport->getCity();
                        $segmentsGlob['arrival_airport_name'] = ($arrivalCityAirport == null) ? $segmentsGlob['arrival_airport_code'] : $arrivalCityAirport->getName();
                        
                        
                        $departure_date = $this->utils->date_time_parts($flightSegments[0][0]->getFlightDeparture()->getDepartureDateTime(), $flightSegments[0][0]->getFlightDeparture()->getDepartureTimeZoneGmtOffset());
                        
                        $arrival_date = $this->utils->date_time_parts($flightSegments[0][sizeof($flightSegments[0]) - 1]->getFlightArrival()->getArrivalDateTime(), $flightSegments[0][sizeof($flightSegments[0]) - 1]->getFlightArrival()->getArrivalTimeZoneGmtOffset());
                        
                        $segmentsGlob['arrival_lowest_original_date_time'] = $flightSegments[0][sizeof($flightSegments[0]) - 1]->getFlightArrival()->getArrivalDateTime();
                        
                        $lowestArrivalDate = new \DateTime($segmentsGlob['arrival_lowest_original_date_time']);
                        
                        $segmentsGlob['arrival_lowest_date_time_obj'] = $lowestArrivalDate;
                        $segmentsGlob['arrival_lowest_timestamp']= $lowestArrivalDate->getTimeStamp();
                        $segmentsGlob['arrival_date_time'] = $arrival_date['date'].'<br/>'.$arrival_date['time'];
                        $segmentsGlob['arrival_date'] = $arrival_date['date'];
                        $segmentsGlob['arrival_time'] = $arrival_date['time'];
                        
                        $arrivalTime = new \DateTime($arrival_date['time']);
                        if($arrivalTime->format('H') >= 0 && $arrivalTime->format('H') < 5){
                            $segmentsGlob['arrivalTimeOfDay'] = 'early_morning';
                        }elseif($arrivalTime->format('H') >= 5 && $arrivalTime->format('H') < 12){
                            $segmentsGlob['arrivalTimeOfDay'] = 'morning';
                        }elseif($arrivalTime->format('H') >= 12 && $arrivalTime->format('H') < 18){
                            $segmentsGlob['arrivalTimeOfDay'] = 'afternoon';
                        }elseif($arrivalTime->format('H') >= 18 && $arrivalTime->format('H') <= 23){
                            $segmentsGlob['arrivalTimeOfDay'] = 'evening';
                        }
                        
                        $segmentsGlob['flight_duration'] = $this->utils->duration_to_string($this->utils->mins_to_duration($flightSegments[0][0]->getFlightDuration()));
                        $segmentsGlob['flight_duration_attr'] = $flightSegments[0][0]->getFlightDuration();
                        $segmentsGlob['stops'] = sizeof($flightSegments[0]) - 1;
                        $departureTimeInMinutes = $this->utils->getMinutesFromTime($departure_date['time']);
                        $segmentsGlob['departure_time_minutes'] = $departureTimeInMinutes;
                        $arrivalTimeInMinutes = $this->utils->getMinutesFromTime($arrival_date['time']);
                        $segmentsGlob['arrival_time_minutes'] = $arrivalTimeInMinutes;
                        $classExactDate = '';
                        
                        if ($oneWay == 1) {
                            if (date("M j Y", strtotime($fromDate)) == date("M j Y", strtotime($flightSegments[0][0]->getFlightDeparture()->getDepartureDateTime()))
                                && $flexibleDate ) {
                                    $classExactDate = 'fly_exactDate';
                                }
                        }
                        $segmentsGlob['mileage_amount'] = $segment->getTpaExtensions()->getTpaExtensionsMileageAmount();
                        $itineraries[$itinerary]['segmentsGlob'] = $segmentsGlob;
                        
                        $itineraries[$itinerary]['marketing_airline'] = $segment->getAirlineInfo()->getMarketingAirlineCode();
                        $itineraries[$itinerary]['operating_airline'] = $segment->getAirlineInfo()->getOperatingAirlineCode();
                        $itineraries[$itinerary]['penaltiesInfo'] =json_encode($extra['penaltiesInfo']);
                        $segmentCount += 1;
                    }
                    
                    $lastSegment = $flightSegments[sizeof($flightSegments) - 1];
                    
                    $segmentsGlob1 = array();
                    
                    if (!isset($this->airlines_list[$lastSegment[0]->getAirlineInfo()->getMarketingAirlineCode()]))
						$this->airlines_list[$lastSegment[0]->getAirlineInfo()->getMarketingAirlineCode()] = $this->flightRepositoryServices->findAirline($lastSegment[0]->getAirlineInfo()->getMarketingAirlineCode());
					
                    $airline = $this->airlines_list[$lastSegment[0]->getAirlineInfo()->getMarketingAirlineCode()];
					
                    $segmentsGlob1['airline_name1'] = ($airline) ? $airline->getAlternativeBusinessName() : $lastSegment[0]->getAirlineInfo()->getMarketingAirlineCode();
                    
					if (!isset($this->airlines_list[$lastSegment[0]->getAirlineInfo()->getOperatingAirlineCode()]))
						$this->airlines_list[$lastSegment[0]->getAirlineInfo()->getOperatingAirlineCode()] = $this->flightRepositoryServices->findAirline($lastSegment[0]->getAirlineInfo()->getOperatingAirlineCode());
					
                    $operatingAirline = $this->airlines_list[$lastSegment[0]->getAirlineInfo()->getOperatingAirlineCode()];
					
                    $segmentsGlob1['operating_airline_name1'] = ($operatingAirline) ? $operatingAirline->getAlternativeBusinessName() : $lastSegment[0]->getAirlineInfo()->getOperatingAirlineCode();
                    
                    $airlineLogo = ($airline) ? $airline->getLogo() : $noLogo;
                    $segmentsGlob1['airline_logo1'] = $this->TTRouteUtils->generateMediaURL('/media/images/airline-logos/'.$airlineLogo);
                    $segmentsGlob1['airline_logo_mobile1'] = 'media/images/airline-logos/mobile/'.$airlineLogo;
                    $segmentsGlob1['airline1'] = $lastSegment[0]->getAirlineInfo()->getMarketingAirlineCode();
                    $segmentsGlob1['operating_airline1'] = $lastSegment[0]->getAirlineInfo()->getOperatingAirlineCode();
                    $segmentsGlob1['flight_number1'] = $lastSegment[0]->getFlightNumber();
                    $segmentsGlob1['stops1'] = sizeof($lastSegment) - 1;
                    
                    $departure_date = $this->utils->date_time_parts($lastSegment[0]->getFlightDeparture()->getDepartureDateTime(), $lastSegment[0]->getFlightDeparture()->getDepartureTimeZoneGmtOffset());
                    
                    $segmentsGlob1['departure_date_time1'] = $departure_date['date'].'<br/>'.$departure_date['time'];
                    $segmentsGlob1['departure_date1'] = $departure_date['date'];
                    $segmentsGlob1['departure_time1'] = $departure_date['time'];
                    
                    $segmentsGlob1['departure_airport_code1'] = $lastSegment[0]->getFlightDeparture()->getDepartureAirportLocationCode();
					
					if (!isset($this->airports_list[$segmentsGlob1['departure_airport_code1']]))
						$this->airports_list[$segmentsGlob1['departure_airport_code1']] = $this->flightRepositoryServices->findAirport($segmentsGlob1['departure_airport_code1']);
					
                    $destinationCityAirport = $this->airports_list[$segmentsGlob1['departure_airport_code1']];
					
                    $segmentsGlob1['departure_airport_city1'] = ($destinationCityAirport == null) ? $segmentsGlob1['departure_airport_code1'] : $destinationCityAirport->getCity();
                    $segmentsGlob1['departure_airport_name1'] = ($destinationCityAirport == null) ? $segmentsGlob1['departure_airport_code1'] : $destinationCityAirport->getName();
                    
                    $segmentsGlob1['arrival_airport_code1'] = $lastSegment[sizeof($lastSegment) - 1]->getFlightArrival()->getArrivalAirportLocationCode();
					
					if (!isset($this->airports_list[$segmentsGlob1['arrival_airport_code1']]))
						$this->airports_list[$segmentsGlob1['arrival_airport_code1']] = $this->flightRepositoryServices->findAirport($segmentsGlob1['arrival_airport_code1']);
					
                    $arrivalCityAirport = $this->airports_list[$segmentsGlob1['arrival_airport_code1']];
					
                    $segmentsGlob1['arrival_airport_city1'] = ($arrivalCityAirport == null) ? $segmentsGlob1['arrival_airport_code1'] : $arrivalCityAirport->getCity();
                    $segmentsGlob1['arrival_airport_name1'] = ($arrivalCityAirport == null) ? $segmentsGlob1['arrival_airport_code1'] : $arrivalCityAirport->getName();
                    
                    $arrival_date = $this->utils->date_time_parts($lastSegment[sizeof($lastSegment)-1]->getFlightArrival()->getArrivalDateTime(), $lastSegment[sizeof($lastSegment)-1]->getFlightArrival()->getArrivalTimeZoneGmtOffset());
                    
                    $segmentsGlob1['arrival_date_time1'] = $arrival_date['date'].'<br/>'.$arrival_date['time'];
                    $segmentsGlob1['arrival_date1'] = $arrival_date['date'];
                    $segmentsGlob1['arrival_time1'] = $arrival_date['time'];
                    $segmentsGlob1['flight_duration1'] = $this->utils->duration_to_string($this->utils->mins_to_duration($lastSegment[0]->getFlightDuration()));
                    $segmentsGlob1['flight_duration_attr1'] = $lastSegment[0]->getFlightDuration();
                    $departureTimeInMinutes = $this->utils->getMinutesFromTime($departure_date['time']);
                    $segmentsGlob1['departure_time_minutes1'] = $departureTimeInMinutes;
                    $arrivalTimeInMinutes = $this->utils->getMinutesFromTime($arrival_date['time']);
                    $segmentsGlob1['arrival_time_minutes1'] = $arrivalTimeInMinutes;

                    if (date("M j Y", strtotime($fromDate)) == date("M j Y", strtotime($flightSegments[0][0]->getFlightDeparture()->getDepartureDateTime()))
                        && date("M j Y", strtotime($toDate)) == date("M j Y", strtotime($lastSegment[0]->getFlightDeparture()->getDepartureDateTime()))
                        && $flexibleDate ) {
                            $classExactDate = 'fly_exactDate';
                        }

                        $itineraries[$itinerary]['segmentsGlob']['classExactDate']    = $classExactDate;
                        $itineraries[$itinerary]['segmentsGlob']['segmentsGlobMulti'][] = $segmentsGlob1;
                }

                $itineraries[$itinerary]['non_refundable_css_class'] = $extra['non_refundable'] == "true" ? 'non-refundable' : 'refundable';
                $itineraries[$itinerary]['non_refundable'] = $extra['non_refundable'] == "true" ? $this->translator->trans('non refundable') : $this->translator->trans('refundable');
                $itineraries[$itinerary]['refundable'] = $extra['non_refundable'] == "true" ? 0 : 1;
                $itineraries[$itinerary]['other_data'] = $extra['non_refundable'] == "true" ? 'best_combination' : 'refundable_only';
                $baseFare = $this->currencyService->currencyConvert($extra['base_fare'] - $discount, $conversionRate);
                $itineraries[$itinerary]['provider_base_fare'] = $extra['base_fare'];
                $itineraries[$itinerary]['original_base_fare'] = $extra['base_fare'];
                $itineraries[$itinerary]['base_fare'] = number_format($baseFare, 2, '.', ',');
                $itineraries[$itinerary]['base_fare_attr'] = $baseFare;
                $taxes = $this->currencyService->currencyConvert($extra['taxes'], $conversionRate);
                $itineraries[$itinerary]['original_taxes'] = $extra['taxes'];
                $itineraries[$itinerary]['taxes'] = number_format($taxes, 2, '.', ',');
                $itineraries[$itinerary]['taxes_attr'] = $taxes;
                $itineraries[$itinerary]['seats_remaining']   = $extra['seats_remaining'];

                array_push(
                    $secToken,
                    $itineraries[$itinerary]['refundable'],
                    $itineraries[$itinerary]['base_fare'],
                    $itineraries[$itinerary]['taxes'],
                    $itineraries[$itinerary]['taxes_attr'],
                    $itineraries[$itinerary]['base_fare_attr'],
                    $itineraries[$itinerary]['original_base_fare'],
                    $itineraries[$itinerary]['original_taxes']
                );

                //passenger_info

                foreach ($extra['passenger_info'] as $passenger_info_index => $passengerInfo) {

                    $itineraries[$itinerary]['passenger_info'][$passenger_info_index]['type_code'] = $passengerInfo['code'];
                    $itineraries[$itinerary]['passenger_info'][$passenger_info_index]['quantity']  = $passengerInfo['quantity'];

                    //                    array_push(
                    //                        $secToken,
                    //                        $passengerInfo['quantity']
                    //                    );

                    $baggageInformation = '';
                    
                    if (isset($passengerInfo['baggage_info'])) {
                        foreach ($passengerInfo['baggage_info'] as $baggage_info_index => $baggageInfo) {
                            $flightType = ($baggage_info_index === 0) ? 'leaving_baggage_info' : 'returning_baggage_info';
                            if (isset($baggageInfo['pieces'])) {
                                $unit = ($baggageInfo['pieces'] == 1) ? " piece" : " pieces";
                                $baggageInformation = $baggageInfo['pieces'].$unit;
                            } else {
                                $baggageInformation = $baggageInfo['weight'].$baggageInfo['unit'];
                            }
                            $itineraries[$itinerary]['passenger_info'][$passenger_info_index][$flightType] = $baggageInformation;
                            
                            //                            array_push(
                            //                                $secToken,
                            //                                $itineraries[$itinerary]['passenger_info'][$passenger_info_index][$flightType]
                            //                            );
                        }
                    } else {
                        $itineraries[$itinerary]['passenger_info'][$passenger_info_index]['leaving_baggage_info']   = 0;
                        $itineraries[$itinerary]['passenger_info'][$passenger_info_index]['returning_baggage_info'] = 0;
                        
                        //                        array_push(
                        //                            $secToken,
                        //                            $itineraries[$itinerary]['passenger_info'][$passenger_info_index]['leaving_baggage_info'],
                        //                            $itineraries[$itinerary]['passenger_info'][$passenger_info_index]['returning_baggage_info']
                        //                        );
                    }
                    
                    $itineraries[$itinerary]['passenger_info'][$passenger_info_index]['fare_calculation_line'] = $passengerInfo['fare_calculation_line'];
                    
                    //                    array_push(
                    //                        $secToken,
                    //                        $passengerInfo['fare_calculation_line']
                    //                    );
                }

                array_push($secToken, $multiDestination, $oneWay);
                sort($secToken, SORT_STRING);
                $secTokenStr = trim(implode(" ", $secToken));

                $itineraries[$itinerary]['sec_token'] = crypt($secTokenStr, $salt);

                //added for price optimization
                $itineraries[$itinerary]['combinedData']     = $extra['combinedData'];
                $itineraries[$itinerary]['related_one_way']  = $extra['related_one_way'];
                $itineraries[$itinerary]['departure_flight'] = $extra['departure_flight'];
                $itineraries[$itinerary]['return_flight']    = $extra['return_flight'];
                $itineraries[$itinerary]['flight_type']      = $extra['flight_type'];
                //

                $itineraries[$itinerary]['selected_price_info'] = '';
                if (isset($extra['selected_price_info']) && $extra['selected_price_info']) {
                    $oneway_price           = $extra['selected_price_info']['original_price'];
                    $oneway_original_price  = $oneway_price; //($oneway_price <= $discount) ? $oneway_price : ($oneway_price - $discount);
                    $oneway_converted_price = ($oneway_original_price <= $discount) ? $oneway_original_price : ($oneway_original_price - $discount);
                    $oneway_converted_price = $this->currencyService->currencyConvert($oneway_original_price, $conversionRate);
                    $oneway_new_price       = number_format($oneway_converted_price, 2, '.', ',');
                    $extra['selected_price_info']['price']      = $oneway_new_price;
                    $extra['selected_price_info']['price_attr'] = $oneway_converted_price;

                    $itineraries[$itinerary]['selected_price']          = $oneway_new_price;
                    $itineraries[$itinerary]['selected_price_attr']     = $oneway_converted_price;
                    $itineraries[$itinerary]['selected_original_price'] = $oneway_original_price;
                    $itineraries[$itinerary]['selected_provider_price'] = $oneway_price;

                    //base fare conversion
                    $oneway_baseFare                                = $this->currencyService->currencyConvert($extra['selected_price_info']['original_base_fare'], $conversionRate);
                    $extra['selected_price_info']['base_fare']      = number_format($oneway_baseFare, 2, '.', ',');
                    $extra['selected_price_info']['base_fare_attr'] = $oneway_baseFare;

                    //taxes conversion
                    $oneway_taxes                                       = $this->currencyService->currencyConvert($extra['selected_price_info']['original_taxes'], $conversionRate);
                    $extra['selected_price_info']['taxes'] = number_format($oneway_taxes, 2, '.', ',');
                    $itineraries[$itinerary]['selected_original_taxes'] = $extra['selected_price_info']['original_taxes'];
                    $itineraries[$itinerary]['selected_taxes']          = number_format($oneway_taxes, 2, '.', ',');
                    $itineraries[$itinerary]['selected_taxes_attr']     = $oneway_taxes;

                    $itineraries[$itinerary]['selected_price_json']     = json_encode($extra['selected_price_info']);
                    $itineraries[$itinerary]['selected_price_source']   = $extra['selected_price_info']['source'];
                    $itineraries[$itinerary]['selected_price']          = $extra['selected_price_info']['price'];
                    $itineraries[$itinerary]['selected_price_attr']     = $extra['selected_price_info']['price_attr'];
                    $itineraries[$itinerary]['selected_base_fare']      = $extra['selected_price_info']['base_fare'];
                    $itineraries[$itinerary]['selected_base_fare_attr'] = $extra['selected_price_info']['base_fare_attr'];
                    $itineraries[$itinerary]['selected_taxes']          = $extra['selected_price_info']['taxes'];
                }
                $itineraries[$itinerary]['sequence_number'] = isset($extra['selected_price_info']['sequence_number']) ? $extra['selected_price_info']['sequence_number'] : $extra['sequence_number'];
            }

            $normalize_response = [
                'itineraries' => $itineraries,
                'airlines' => $airlines,
                'durations' => [
                    'minimumDuration' => $minimumDuration,
                    'maximumDuration' => $maximumDuration,
                    'minimumPrice' => number_format($minimumPrice, 2, '.', ''),
                    'maximumPrice' => number_format($maximumPrice, 2, '.', ''),
                ],
                'departure_airports' => $departure_airports
            ];

            return $normalize_response;
        }

        return false;
    }
    
    /**
     * This method convert response from API into object, usually an arrays of itinerary
     *
     * @param array $itineraries
     * @return array|mixed
     */
    protected function convertArrayIteniraryToObject($itineraries){

        $flightItineraries = array();

        foreach ( $itineraries as $itinerary ) {

            
            $flightItinerary = new FlightItinerary();
            $flightItinerary->setSegmentNumber($itinerary['sequence_number']);
            $flightItinerary->setTripType($itinerary['air_itinerary']['trip_type']);
            
            $extra = array();
            $extra['passenger_info'] = $itinerary['passenger_info'];
            $extra['base_fare'] = $itinerary['base_fare'];
            $extra['taxes'] = $itinerary['taxes'];
            $extra['amount'] = $itinerary['amount'];
            $extra['currency_code'] = $itinerary['currency_code'];
            $extra['decimal_places'] = $itinerary['decimal_places'];
            $extra['non_refundable'] = $itinerary['non_refundable'];
            $extra['seats_remaining'] = $itinerary['seats_remaining'];
            $extra['fare_info'] = $itinerary['fare_info'];
            $extra['combinedData']        = isset($itinerary['combinedData']) ? $itinerary['combinedData'] : 0;
            $extra['related_one_way']     = isset($itinerary['related_one_way']) ? json_encode($itinerary['related_one_way']) : '';
            $extra['departure_flight']    = isset($itinerary['departure_flight']) ? json_encode($itinerary['departure_flight']) : '';
            $extra['return_flight']       = isset($itinerary['return_flight']) ? json_encode($itinerary['return_flight']) : '';
            $extra['selected_price_info'] = isset($itinerary['selected_price']) ? $itinerary['selected_price'] : '';
            $extra['flight_type']         = isset($itinerary['flight_type']) ? $itinerary['flight_type'] : '';
            $extra['sequence_number']     = isset($itinerary['sequence_number']) ? $itinerary['sequence_number'] : 0;
            $extra['penaltiesInfo']=$itinerary['penaltiesInfo'];

            $flightItinerary->setExtra($extra);
            
            foreach ($itinerary['passenger_info'] as $passenger_info) {
                $passengerInfo = new  FlightItineraryPassengerInfo();
                $passengerInfo->setCode($passenger_info['code']);
                $passengerInfo->setQuantity($passenger_info['quantity']);
                $passengerInfo->setFareCalculationLine($passenger_info['fare_calculation_line']);
                
                if (isset($passenger_info['baggage_info'])) {
                    foreach ($passenger_info['baggage_info'] as $baggage_info) {
                        $baggaggeInfo = new  FlightItineraryPassengerInfoBaggage();
                        
                        if(isset($baggage_info['weight']) && $baggage_info['unit']){
                            $baggaggeInfo->setWeight($baggage_info['weight']);
                            $baggaggeInfo->setUnit($baggage_info['unit']);
                            $passengerInfo->setBaggage($baggaggeInfo);
                        }
                        elseif(isset($baggage_info['pieces']))
                        {
                            $baggaggeInfo->setPieces($baggage_info['pieces']);
                            $passengerInfo->setBaggage($baggaggeInfo);
                        }
                    }
                }
                
                $flightItinerary->setPassengerInfo($passengerInfo);
            }
            $flightItinerary->setBaseFare($itinerary['base_fare']);
            $flightItinerary->setTaxes($itinerary['taxes']);
            $flightItinerary->setAmount($itinerary['amount']);
            $flightItinerary->setCurrencyCode($itinerary['currency_code']);
            $flightItinerary->setDecimalPlaces($itinerary['decimal_places']);
            $flightItinerary->setNonRefundable($itinerary['non_refundable']);
            $flightItinerary->setSeatsRemaining($itinerary['seats_remaining']);
            $flightItinerary->setPenaltiesInfo($itinerary['penaltiesInfo']);
            foreach ($itinerary['fare_info'] as $fare_info) {
                $fairInfo = new  FlightItineraryFairInfo();
                $fairInfo->setSeatsRemaining($fare_info['seats_remaining']);
                $fairInfo->setCabin($fare_info['cabin']);
                $flightItinerary->setFairInfo($fairInfo);
            }
            
            $air_itineraries = $itinerary['air_itinerary'];
            
            foreach( $air_itineraries['origin_destination_options'] as $origin_destination_options ){
                foreach ($origin_destination_options['flight_segments'] as $flight_segments){
                    
                    $flightItinerarySegment = new FlightItinerarySegment();
                    $airlineInfo = new AirlineInfo();
                    $flightDeparture = new FlightDeparture();
                    $flightArrival = new FlightArrival();
                    $tpaExtensions = new TpaExtensions();
                    
                    $flightItinerarySegment->setOriginDestinationIndex($origin_destination_options['origin_destination_index']);
                    $flightItinerarySegment->setFlightDuration($origin_destination_options['flight_duration']);
                    
                    $flightItinerarySegment->setFlightSegmentIndex($flight_segments['flight_segment_index']);
                    $flightDeparture->setDepartureDateTime($flight_segments['departure']['date_time']);
                    $flightDeparture->setDepartureAirportLocationCode($flight_segments['departure']['airport']['location_code']);
                    
                    if(isset($flight_segments['departure']['airport']['terminal_id'])){
                        $flightDeparture->setDepartureAirportTerminalId($flight_segments['departure']['airport']['terminal_id']);
                    }
                    
                    if (isset($flight_segments['departure']['time_zone']['gmt_offset'])){
                        $flightDeparture->setDepartureTimeZoneGmtOffset($flight_segments['departure']['time_zone']['gmt_offset']);
                    }
                    
                    $flightArrival->setArrivalDateTime($flight_segments['arrival']['date_time']);
                    $flightArrival->setArrivalAirportLocationCode($flight_segments['arrival']['airport']['location_code']);
                    
                    if(isset($flight_segments['arrival']['airport']['terminal_id'])){
                        $flightArrival->setArrivalAirportTerminalId($flight_segments['arrival']['airport']['terminal_id']);
                    }
                    
                    if (isset($flight_segments['arrival']['time_zone']['gmt_offset'])){
                        $flightArrival->setArrivalTimeZoneGmtOffset($flight_segments['arrival']['time_zone']['gmt_offset']);
                    }
                    
                    $flightItinerarySegment->setStopQuantity($flight_segments['stop_quantity']);
                    $flightItinerarySegment->setFlightNumber($flight_segments['flight_number']);
                    $flightItinerarySegment->setResBookDesigCode($flight_segments['res_book_desig_code']);
                    $flightItinerarySegment->setElapsedTime($flight_segments['elapsed_time']);
                    $flightItinerarySegment->setFareBasisCode($flight_segments['fare_basis_code']);
                    
                    $airlineInfo->setOperatingAirlineCode($flight_segments['operating_airline']['code']);
                    $airlineInfo->setOperatingAirlineFlightNumber($flight_segments['operating_airline']['flight_number']);
                    $airlineInfo->setMarketingAirlineCode($flight_segments['marketing_airline']['code']);
                    
                    $flightItinerarySegment->setEquipmentAirEquipType($flight_segments['equipment']['air_equip_type']);
                    $flightItinerarySegment->setMarriageGrp($flight_segments['marriage_grp']);
                    
                    $tpaExtensions->setTpaExtensionsETicketInd($flight_segments['tpa_extensions']['e_ticket']['ind']);
                    $tpaExtensions->setTpaExtensionsMileageAmount($flight_segments['tpa_extensions']['mileage']['amount']);
                    
                    $flightItinerarySegment->setAirlineInfo($airlineInfo);
                    $flightItinerarySegment->setFlightDeparture($flightDeparture);
                    $flightItinerarySegment->setFlightArrival($flightArrival);
                    $flightItinerarySegment->setTpaExtensions($tpaExtensions);
                    
                    $flightItinerary->setFlightItinerarySegment($flightItinerarySegment);
                }
                
                $flightItinerary->setFlightSegments($flightItinerary->getFlightItinerarySegment());
                
                $flightItinerary->flightItinerarySegment = array();
            }
            
            $flightItineraries[] = $flightItinerary;
        }
        
        return $flightItineraries;
    }
}
