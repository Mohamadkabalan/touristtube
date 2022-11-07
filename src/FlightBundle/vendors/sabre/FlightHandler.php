<?php
namespace FlightBundle\vendors\sabre;

use Symfony\Component\DependencyInjection\Container;
use FlightBundle\Entity\PassengerNameRecord;
use FlightBundle\Entity\PassengerDetail;
use FlightBundle\Entity\FlightDetail;
use FlightBundle\Entity\FlightInfo;
use FlightBundle\vendors\sabre\RequestDataHandler;
use FlightBundle\vendors\sabre\PassengerHandler;

class FlightHandler
{
    private $container;
    private $translator;
    private $requestDataHandler;
    private $passengerHandler;
	
	public function __construct(Container $container, RequestDataHandler $requestDataHandler, PassengerHandler $passengerHandler)
	{
		$this->container = $container;
		$this->translator = $container->get('translator');
        $this->requestDataHandler = $requestDataHandler;
        $this->passengerHandler = $passengerHandler;
	}

    public function bookRequest($request)
    {

        $requestData = $this->requestDataHandler->normaliseRequest($request);

        $passengerNameRecord = new PassengerNameRecord();

        $setFlightDetails = $this->setFlightDetails($passengerNameRecord, $requestData, $request);
        $setPassengers = $this->passengerHandler->setPassengers($setFlightDetails['passengerNameRecord'], $requestData, $request);
        
        $response['requestData'] = $setFlightDetails['requestData'];
        $response['passengerNameRecord'] = $setPassengers['passengerNameRecord'];
        $response['passengersArray'] = $setPassengers['passenger']->getPassengers();
        $response['flightSegments'] = $setFlightDetails['flightSegments'];
        $response['isChinaDomestic'] = $setFlightDetails['isChinaDomestic'];
        
        return $response;
    }
	
	public function setFlightDetails($passengerNameRecord, $requestData, $request)
	{
	$multiCount      = 0;
	$isChinaDomestic = 0;
	$flightSegments  = array();
	$departureDateTime      = array();
        $arrivalDateTime        = array();
        $flightNumber           = array();

        $resBookDesigCode       = array();
        $destinationLocation    = array();
        $airlineCode            = array();
        $airlineName            = array();
        $originLocation         = array();
        $operatingAirlineCode   = array();
		
        for ($i = 0; $i < $requestData->getTotalSegments(); $i++) {

            $flightDetail      = new FlightDetail();
            $flightDetail->setSegmentNumber($i + 1);
            $flightInformation = array();
            $flight_type_var   = "flight_type-$i";
            $flightType        = $request->request->get($flight_type_var, '');
            $flightDetail->setType($flightType);

            $flightSegments[$flightType]["one_way"]             = $requestData->getOneWay();
            $flightSegments[$flightType]["multi_destination"]   = $requestData->getMultiDestination();
            // Departure date and time
            $departure_date_time_var                            = "departure_date_time-$i";
            $departureDateTime[]                                = $request->request->get($departure_date_time_var, '');
            $flightSegments[$flightType]["departure_main_date"] = date("M j Y", strtotime($departureDateTime[$i]));
            $flightInformation["departure_date"]                = date("D j M", strtotime($departureDateTime[$i]));
            $flightInformation["departure_time"]                = date("H\:i", strtotime($departureDateTime[$i]));
            $flightDetail->setDepartureDateTime($departureDateTime[$i]);

            // Arrival date and time
            $arrival_date_time_var             = "arrival_date_time-$i";
            $arrivalDateTime[]                 = $request->request->get($arrival_date_time_var, '');
            $flightInformation["arrival_date"] = date("D j M", strtotime($arrivalDateTime[$i]));
            $flightInformation["arrival_time"] = date("H\:i", strtotime($arrivalDateTime[$i]));
            $flightDetail->setArrivalDateTime($arrivalDateTime[$i]);

            // Flight number
            $flight_number_var                  = "flight_number-$i";
            $flightNumber[]                     = $request->request->get($flight_number_var, '');
            $flightInformation["flight_number"] = $request->request->get($flight_number_var, '');

            // Res book code
            $res_book_desig_code_var = "res_book_desig_code-$i";
            $resBookDesigCode[]      = $request->request->get($res_book_desig_code_var, '');
            $flightDetail->setResBookDesignCode($resBookDesigCode[$i]);


            // Destination location
            $destination_location_var                                = "destination_location-$i";
            $destinationLocation[]                                   = $request->request->get($destination_location_var, '');
            $flightSegments[$flightType]["destination_main_airport"] = $request->request->get($destination_location_var, '');
            $flightInformation["destination_airport_code"]           = $request->request->get($destination_location_var, '');
            $flightDetail->setArrivalAirport($destinationLocation[$i]);

            $destination_location_city_var                   = "destination_location_city-$i";
            $flightSegments[$flightType]["destination_city"] = $request->request->get($destination_location_city_var, '');
            $flightInformation["destination_city"]           = $request->request->get($destination_location_city_var, '');

            $destination_location_airport_var         = "destination_location_airport-$i";
            $flightInformation["destination_airport"] = $request->request->get($destination_location_airport_var, '');

            // Marketing airline code
            $airline_code_var                  = "airline_code-$i";
            $airlineCode[]                     = $request->request->get($airline_code_var, '');
            $flightInformation["airline_code"] = $request->request->get($airline_code_var, '');

            $flightDetail->setAirline($airlineCode[$i]);
            $flightDetail->setFlightNumber($flightNumber[$i]);

            // Marketing airline name
            $airline_name_var                  = "airline_name-$i";
            $airlineName[]                     = $request->request->get($airline_name_var, '');
            $flightInformation["airline_name"] = $request->request->get($airline_name_var, '');

            // operating airline code
            $operating_airline_code_var                  = "operating_airline_code-$i";
            $operatingAirlineCode[]                      = $request->request->get($operating_airline_code_var, '');
            $flightInformation["operating_airline_code"] = $request->request->get($operating_airline_code_var, '');

            $flightDetail->setOperatingAirline($operatingAirlineCode[$i]);

            // operating airline name
            $operating_airline_name_var                  = "operating_airline_name-$i";
            $flightInformation["operating_airline_name"] = $request->request->get($operating_airline_name_var, '');

            // Origin location
            $origin_location_var                      = "origin_location-$i";
            $originLocation[]                         = $request->request->get($origin_location_var, '');
            $flightInformation["origin_airport_code"] = $request->request->get($origin_location_var, '');
            $flightDetail->setDepartureAirport($originLocation[$i]);

            $origin_location_airport_var         = "origin_location_airport-$i";
            $flightInformation["origin_airport"] = $request->request->get($origin_location_airport_var, '');

            $origin_city_var                  = "origin_location_city-$i";
            $flightInformation["origin_city"] = $request->request->get($origin_city_var, '');

            // Stop indicator
            $stop_indicator_var                  = "stop_indicator-$i";
            $flightInformation["stop_indicator"] = $request->request->get($stop_indicator_var, 0);
            $flightDetail->setStopIndicator($flightInformation["stop_indicator"]);

            // Stop duration and city name
            $stop_duration_var = "stop_duration-$i";
            if ($flightInformation["stop_indicator"]) {
                $flightInformation["stop_duration"] = $request->request->get($stop_duration_var, 0);
                $flightDetail->setStopDuration($flightInformation["stop_duration"]);
            } else {
                $origin_location_city_var = "origin_location_city-$i";
                $originCity               = $request->request->get($origin_location_city_var, '');

                $originMainAirport = $request->request->get($origin_location_var, '');
                $mainAirline       = $flightInformation["airline_name"];
            }

            // cabin
            $cabin_code_var                  = "cabin_code-$i";
            $flightInformation["cabin_code"] = $request->request->get($cabin_code_var, '');
            $flightDetail->setCabin($flightInformation["cabin_code"]);

            $cabin_var                  = "cabin-$i";
            $flightInformation["cabin"] = $request->request->get($cabin_var, '');

            // flight_duration
            $flight_duration_var                  = "flight_duration-$i";
            $flightInformation["flight_duration"] = $request->request->get($flight_duration_var, '');
            $flightDetail->setFlightDuration($flightInformation["flight_duration"]);

            $flightSegments[$flightType]["origin_city"]         = $originCity;
            $flightSegments[$flightType]["origin_main_airport"] = $originMainAirport;
            $flightSegments[$flightType]["main_airline"]        = $mainAirline;

            if ($requestData->getMultiDestination()) {
                if ($flightInformation["stop_indicator"]) {
                    $flightSegments[$flightType]["flight_info"][$multiCount - 1]['stop_segment'][] = $flightInformation;
                } else {
                    $flightInformation['stop_segment']            = '';
                    $flightSegments[$flightType]["flight_info"][] = $flightInformation;
                    $multiCount++;
                }
            } else {
                $flightSegments[$flightType]["flight_info"][] = $flightInformation;
            }

            $departureCountry = $this->container->get('FlightRepositoryServices')->findCountry($flightInformation["origin_airport_code"])->getCountry();
            $arrivalCountry   = $this->container->get('FlightRepositoryServices')->findCountry($flightInformation["destination_airport_code"])->getCountry();

            if ($departureCountry == 'CN' && $arrivalCountry == 'CN') {
                $isChinaDomestic = 1;
            }

            $passengerNameRecord->addFlightDetail($flightDetail);
        }
        
        $requestData->setFlightSegments($flightSegments);
        $requestData->setDepartureDateTime($departureDateTime);
        $requestData->setArrivalDateTime($arrivalDateTime);
        $requestData->setFlightNumber($flightNumber);
        $requestData->setResBookDesigCode($resBookDesigCode);
        $requestData->setDestinationLocation($destinationLocation);
        $requestData->setAirlineCode($airlineCode);
        $requestData->setAirlineName($airlineName);
        $requestData->setOriginLocation($originLocation);
        $requestData->setOperatingAirlineCode($operatingAirlineCode);
        
        $response = array();
        $response['passengerNameRecord'] = $passengerNameRecord;
        $response['flightSegments'] = $flightSegments;
        $response['isChinaDomestic'] = $isChinaDomestic;
        $response['requestData'] = $requestData;
        
        return $response;
	}
	
	public function setHiddenFieldsSecToken($thisData, $requestData, $request)
	{
		$hiddenFields = [];
        $secToken     = [];
        foreach ($request->request as $key => $hiddenField) {
            if ($key === 'passengerNameRecord') continue;

            $hiddenFields[$key] = $hiddenField;

            if ($key === 'submit-booking' || $key === 'sec_token' || $key === 'from_mobile' || $key == 'access_token' || $key == 'returnedConversationId' || $key == "pass" || $key == 'coupon_code' || $key
                == 'pin') continue;

            $secToken[] = $hiddenField;
        }

        sort($secToken, SORT_STRING);
        $secTokenStr = trim(implode(" ", $secToken));

        //	$this->debug($secToken);
        //	$this->debug($request->request);
        //	echo $request->request->get('sec_token') . " = " . crypt($secTokenStr, $sabreVariables['salt']);

        $validUnusedCoupon = false;

        $campaign_info = false;
        $couponCode = $requestData->getCouponCode();
        if ($couponCode) {
            $campaign_info = $thisData->validUnusedCouponsCampaign($couponCode, $this->container->getParameter('SOCIAL_ENTITY_FLIGHT'));

            $validUnusedCoupon = ($campaign_info !== false);
        } else {
            $campaign_info = $this->container->get('TTServices')->activeCampaigns(array('target_entity_type_id' => $this->container->getParameter('SOCIAL_ENTITY_FLIGHT')), false);

            if ($campaign_info) $campaign_info = $campaign_info[0];
        }
        if ($campaign_info !== false && $campaign_info) {
            if ($campaign_info['target_helper_text']) $campaign_info['target_helper_text'] = $this->translator->trans($campaign_info['target_helper_text']);
            else unset($campaign_info['target_helper_text']);
        }
        
        $response = new \StdClass();
        $response->hiddenFields = $hiddenFields;
        $response->secTokenStr = $secTokenStr;
        $response->campaign_info = $campaign_info;
        
        return $response;
	}

}
