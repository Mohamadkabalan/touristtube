<?php

namespace FlightBundle\vendors\sabre;

use FlightBundle\Model\RequestData;
use FlightBundle\Model\Passenger;
use Symfony\Component\DependencyInjection\Container;
use TTBundle\Utils\Utils;

class RequestDataHandler
{
    private $requestData;
    private $container;
    private $translator;
    private $passenger;
    private $utils;

    private $defaultCurrency = "USD";
    private $currencyPCC = "AED";

    public function __construct(RequestData $requestData, Container $container, Passenger $passenger, Utils $utils)
    {
        $this->requestData = $requestData;
        $this->container = $container;
        $this->translator = $container->get('translator');
        $this->passenger = $passenger;
        $this->utils = $utils;
    }
    // checking if the string is json format or no
    //        function isJSON($string){
    //
    //           return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    //        }

    public function normaliseRequest($request)
    {


        if ($request->request->has("flightrequest")) {

            //                if(!$this->isJSON($request->request->get("flightrequest")))
            //                {
            //
            //                }

            return $this->normaliseRequestNew($request);

        }
        else {

            $total_segments = array();

            if ($request->request->has('total_flight_segments-0')) {
                for ($i = 0; $i < $request->request->get('total_flight_segments-0', 0); $i++) {
                    $total_segments[$i] = $i;
                }
                //$this->requestData->setTotalSegments($request->request->get('total_flight_segments-0', 0));
            }
            else {
                $total_segments = array();
                foreach ($request->request->all() as $key => $segment) {
                    if (preg_match('/total\_flight\_segments\-(\d+)/is', $key, $segId)) {
                        $total_segments[$segId[1]] = $segId[1];
                    }
                }
                //$this->requestData->setTotalSegments($total_segments);
            }

            $this->requestData->setTotalSegments($total_segments);
            //$this->requestData->setTotalSegments($request->request->get('total_flight_segments-0', 0));
            $this->requestData->setNumberInParty($request->request->get("number_in_party", 0));
            $this->requestData->setAdultsQuantity($request->request->get("ADT", 0));
            $this->requestData->setChildrenQuantity($request->request->get("CNN", 0));
            $this->requestData->setInfantsQuantity($request->request->get("INF", 0));
            $this->requestData->setAccessToken($request->request->get("access_token", ""));
            $this->requestData->setReturnedConversationId($request->request->get("returnedConversationId", "@touristtube.com"));
            $this->requestData->setOriginalPrice($request->request->get("original_price", 0));
            $this->requestData->setPriceAmount($request->request->get("price_attr", 0));
            $this->requestData->setBaseFare($request->request->get('original_base_fare', 0));
            $this->requestData->setTaxes($request->request->get('original_taxes', 0));
            $this->requestData->setCurrencyCode($request->request->get('currency', $this->currencyPCC));
            $this->requestData->setSelectedCurrency(filter_input(INPUT_COOKIE, 'currency'));
            $this->requestData->setRefundable($request->request->get('refundable', 0));
            $this->requestData->setOneWay($request->request->get('one_way', 0));
            $this->requestData->setMultiDestination($request->request->get('multi_destination', 0));
            $this->requestData->setFromMobile($request->request->get('from_mobile', 0));
            $this->requestData->setCouponCode(trim($request->request->get('coupon_code', 0)));
            $this->requestData->setSecToken($request->request->get('sec_token'));
            $this->requestData->setPenaltiesInfo($request->request->get('penaltiesInfo'));

            if ($request->request->has('ADT')) {
                for ($i = 0; $i < $this->requestData->getAdultsQuantity(); $i++) {
                    $this->requestData->setADT(
                        [
                            'fare_calc_line' => $request->request->get("fare_calc_line_ADT", ""),
                            'leaving_baggage_info' => $request->request->get("leaving_baggage_info_ADT", 0),
                            'returning_baggage_info' => $request->request->get("returning_baggage_info_ADT", 0),
                        ]
                    );
                }

            }

            if ($request->request->has('CNN')) {
                for ($i = 0; $i < $this->requestData->getChildrenQuantity(); $i++) {
                    $this->requestData->setCNN(
                        [
                            'fare_calc_line' => $request->request->get("fare_calc_line_CNN", ""),
                            'leaving_baggage_info' => $request->request->get("leaving_baggage_info_CNN", 0),
                            'returning_baggage_info' => $request->request->get("returning_baggage_info_CNN", 0),
                        ]
                    );
                }
            }

            if ($request->request->has('INF')) {
                for ($i = 0; $i < $this->requestData->getInfantsQuantity(); $i++) {
                    $this->requestData->setINF(
                        [
                            'fare_calc_line' => $request->request->get("fare_calc_line_INF", ""),
                            'leaving_baggage_info' => $request->request->get("leaving_baggage_info_INF", 0),
                            'returning_baggage_info' => $request->request->get("returning_baggage_info_INF", 0),
                        ]
                    );
                }
            }

            $currencyService = null;
            $displayedCurrency = ($this->requestData->getSelectedCurrency() == "") ? $this->defaultCurrency : $this->requestData->getSelectedCurrency();
            $this->requestData->setDisplayedCurrency($displayedCurrency);

            $conversionRate = 1;
            if ($displayedCurrency != $this->currencyPCC) {
                $currencyService = $this->container->get('CurrencyService');

                $conversionRate = $currencyService->getConversionRate($this->currencyPCC, $displayedCurrency);
            }

            $this->requestData->setConversionRate($conversionRate);

            $displayedPrice = $this->requestData->getOriginalPrice();
            if ($conversionRate != 1) {
                $displayedPrice = $currencyService->currencyConvert($this->requestData->getOriginalPrice(), $conversionRate);
            }

            $this->requestData->setConversionRate($conversionRate);
            $this->requestData->setDisplayedPrice($displayedPrice);

            //for ($i = 0; $i < $this->requestData->getTotalSegments(); $i++) {
            if (!empty($total_segments)) {

                foreach ($total_segments as $i) {
                    $flight_type_var = "flight_type-$i";
                    $departure_date_time_var = "departure_date_time-$i";
                    $arrival_date_time_var = "arrival_date_time-$i";
                    $flight_number_var = "flight_number-$i";
                    $res_book_desig_code_var = "res_book_desig_code-$i";
                    $destination_location_var = "destination_location-$i";
                    $destination_location_city_var = "destination_location_city-$i";
                    $destination_location_airport_var = "destination_location_airport-$i";
                    $airline_code_var = "airline_code-$i";
                    $airline_name_var = "airline_name-$i";
                    $operating_airline_code_var = "operating_airline_code-$i";
                    $operating_airline_name_var = "operating_airline_name-$i";
                    $origin_location_var = "origin_location-$i";
                    $origin_location_airport_var = "origin_location_airport-$i";
                    $origin_city_var = "origin_location_city-$i";
                    $stop_indicator_var = "stop_indicator-$i";
                    $stop_duration_var = "stop_duration-$i";
                    $cabin_code_var = "cabin_code-$i";
                    $cabin_var = "cabin-$i";
                    $flight_duration_var = "flight_duration-$i";
                    $fare_basis_code = "fare_basis_code-$i";
                    $arrival_terminal = "arrival_terminal_id-$i";
                    $departure_terminal = "departure_terminal_id-$i";
                    $aircraft_type = "aircraft_type-$i";
                    $elapsedTime="elapsedTime-$i";

                    $this->requestData->setSegmentNumber($i + 1, $i);
                    $this->requestData->setFlightDuration($request->request->get($flight_duration_var, ''), $i);
                    $this->requestData->setCabin($request->request->get($cabin_var, ''), $i);
                    $this->requestData->setCabinCode($request->request->get($cabin_code_var, ''), $i);
                    $this->requestData->setStopDuration($request->request->get($stop_duration_var, ''), $i);
                    $this->requestData->setStopIndicator($request->request->get($stop_indicator_var, ''), $i);
                    $this->requestData->setOriginCity($request->request->get($origin_city_var, ''), $i);
                    $this->requestData->setOriginLocationAirport($request->request->get($origin_location_airport_var, ''), $i);
                    $this->requestData->setOriginLocation($request->request->get($origin_location_var, ''), $i);
                    $this->requestData->setOperatingAirlineName($request->request->get($operating_airline_name_var, ''), $i);
                    $this->requestData->setOperatingAirlineCode($request->request->get($operating_airline_code_var, ''), $i);
                    $this->requestData->setAirlineName($request->request->get($airline_name_var, ''), $i);
                    $this->requestData->setAirlineCode($request->request->get($airline_code_var, ''), $i);
                    $this->requestData->setDestinationLocationAirport($request->request->get($destination_location_airport_var, ''), $i);
                    $this->requestData->setDestinationLocationCity($request->request->get($destination_location_city_var, ''), $i);
                    $this->requestData->setDestinationLocation($request->request->get($destination_location_var, ''), $i);
                    $this->requestData->setResBookDesigCode($request->request->get($res_book_desig_code_var, ''), $i);
                    $this->requestData->setFlightNumber($request->request->get($flight_number_var, ''), $i);
                    $this->requestData->setArrivalDateTime($request->request->get($arrival_date_time_var, ''), $i);
                    $this->requestData->setDepartureDateTime($request->request->get($departure_date_time_var, ''), $i);
                    $this->requestData->setFlightType($request->request->get($flight_type_var, ''), $i);
                    $this->requestData->setFareBasisCode($request->request->get($fare_basis_code, ''), $i);
                    $this->requestData->setOriginTerminalId($request->request->get($departure_terminal, ''), $i);
                    $this->requestData->setDestinationTerminalId($request->request->get($arrival_terminal, ''), $i);
                    $this->requestData->setAircraftType($request->request->get($aircraft_type, ''), $i);
                    $this->requestData->setElapsedTime($request->request->get($elapsedTime, ''), $i);
                }
            }

            $flightSegments = $this->getRequestFlightSegments($this->requestData);
            $this->requestData->setFlightSegments($flightSegments);

            return $this->requestData;
        }

    }

    public function normaliseRequestNew($request)
    {

        $jsonMain =$request->request->get("flightrequest");

        $mainSearchAndSelectedSegments = json_decode($jsonMain, true);

        $total_segments = array();
        $selectedSegments = $mainSearchAndSelectedSegments["segments"];

        $sizeOfSelectedSegments = sizeof($selectedSegments);

        for ($count = 0; $count < $sizeOfSelectedSegments; $count++) {
            $total_segments[] = $count + 1;
        }

        $this->requestData->setTotalSegments($total_segments);
        //$this->requestData->setTotalSegments($request->request->get('total_flight_segments-0', 0));
        $this->requestData->setNumberInParty($mainSearchAndSelectedSegments['main']['number_in_party']);
        $this->requestData->setAdultsQuantity($mainSearchAndSelectedSegments['main']['ADT']);
        $this->requestData->setChildrenQuantity(isset($mainSearchAndSelectedSegments['main']['CNN']) ? $mainSearchAndSelectedSegments['main']['CNN'] : 0);
        $this->requestData->setInfantsQuantity(isset($mainSearchAndSelectedSegments['main']['INF']) ? $mainSearchAndSelectedSegments['main']['INF'] : 0);
        $this->requestData->setAccessToken($request->request->get("access_token", ""));
        $this->requestData->setReturnedConversationId($request->request->get("returnedConversationId", "@touristtube.com"));

//         $this->requestData->setOriginalPrice($mainSearchAndSelectedSegments['main']['totalOriginalPrice']);
        $this->requestData->setOriginalPrice($request->request->get('provider_price'));
        $this->requestData->setPriceAmount($mainSearchAndSelectedSegments['main']['totalPriceAttr']);
        $this->requestData->setBaseFare($mainSearchAndSelectedSegments['main']['totalOriginalBaseFare']);
        $this->requestData->setTaxes($mainSearchAndSelectedSegments['main']['totalOriginalTaxes']);

        //$this->requestData->setCurrencyCode($mainSearchAndSelectedSegments['main']['currency_code']);
        $this->requestData->setCurrencyCode($mainSearchAndSelectedSegments['main']['currency']);
        $this->requestData->setSelectedCurrency(filter_input(INPUT_COOKIE, 'currency'));
        $this->requestData->setRefundable(isset($selectedSegments[0]['refundable']) ? (int)$selectedSegments[0]['refundable'] : 0);
        $this->requestData->setOneWay(isset($mainSearchAndSelectedSegments['main']['one_way']) ? (int)$mainSearchAndSelectedSegments['main']['one_way'] : 0);
        $this->requestData->setMultiDestination(isset($mainSearchAndSelectedSegments['main']['multi_destination']) ? (int)$mainSearchAndSelectedSegments['main']['multi_destination'] : 0);
        $this->requestData->setFromMobile(isset($selectedSegments[0]['from_mobile']) ? $selectedSegments[0]['from_mobile'] : 0);
        $this->requestData->setCouponCode(trim(isset($selectedSegments[0]['coupon_code']) ? $selectedSegments[0]['coupon_code'] : 0));
        $this->requestData->setSecToken($mainSearchAndSelectedSegments['main']['sec_token']);
        $this->requestData->setPenaltiesInfo($mainSearchAndSelectedSegments['main']['penaltiesInfo']);

        if (isset($mainSearchAndSelectedSegments['main']['ADT'])) {
            for ($i = 0; $i < $this->requestData->getAdultsQuantity(); $i++) {
                $this->requestData->setADT(
                    [
                        'fare_calc_line' => is_array($mainSearchAndSelectedSegments['main']['fare_calc_line_ADT']) ? $mainSearchAndSelectedSegments['main']['fare_calc_line_ADT'][0] : $mainSearchAndSelectedSegments['main']['fare_calc_line_ADT'] ,
                        'leaving_baggage_info' => is_array($mainSearchAndSelectedSegments['main']['leaving_baggage_info_ADT']) ?$mainSearchAndSelectedSegments['main']['leaving_baggage_info_ADT'][0] :$mainSearchAndSelectedSegments['main']['leaving_baggage_info_ADT'] ,
                        'returning_baggage_info' => isset($mainSearchAndSelectedSegments['main']['returning_baggage_info_ADT']) ?  is_array($mainSearchAndSelectedSegments['main']['returning_baggage_info_ADT']) ? $mainSearchAndSelectedSegments['main']['returning_baggage_info_ADT'][0]: $mainSearchAndSelectedSegments['main']['returning_baggage_info_ADT'] : 0,
                    ]
                );
            }

        }

        if (isset($mainSearchAndSelectedSegments['main']['CNN'])) {
            for ($i = 0; $i < $this->requestData->getChildrenQuantity(); $i++) {
                $this->requestData->setCNN(
                    [
                        'fare_calc_line' => is_array($mainSearchAndSelectedSegments['main']['fare_calc_line_CNN']) ?$mainSearchAndSelectedSegments['main']['fare_calc_line_CNN'][0] :$mainSearchAndSelectedSegments['main']['fare_calc_line_CNN'] ,
                        'leaving_baggage_info' => is_array($mainSearchAndSelectedSegments['main']['leaving_baggage_info_CNN']) ? $mainSearchAndSelectedSegments['main']['leaving_baggage_info_CNN'][0] : $mainSearchAndSelectedSegments['main']['leaving_baggage_info_CNN'] ,
                        'returning_baggage_info' => isset($mainSearchAndSelectedSegments['main']['returning_baggage_info_CNN']) ? is_array($mainSearchAndSelectedSegments['main']['returning_baggage_info_CNN']) ?$mainSearchAndSelectedSegments['main']['returning_baggage_info_CNN'][0] : $mainSearchAndSelectedSegments['main']['returning_baggage_info_CNN']  : 0,
                    ]
                );
            }

        }
        if (isset($mainSearchAndSelectedSegments['main']['INF'])) {
            for ($i = 0; $i < $this->requestData->getInfantsQuantity(); $i++) {
                $this->requestData->setINF(
                    [
                        'fare_calc_line' => is_array($mainSearchAndSelectedSegments['main']['fare_calc_line_INF']) ?$mainSearchAndSelectedSegments['main']['fare_calc_line_INF'][0] :$mainSearchAndSelectedSegments['main']['fare_calc_line_INF'] ,
                        'leaving_baggage_info' => is_array($mainSearchAndSelectedSegments['main']['leaving_baggage_info_INF']) ? $mainSearchAndSelectedSegments['main']['leaving_baggage_info_INF'][0] : $mainSearchAndSelectedSegments['main']['leaving_baggage_info_INF'],
                        'returning_baggage_info' => isset($mainSearchAndSelectedSegments['main']['returning_baggage_info_INF']) ? is_array($mainSearchAndSelectedSegments['main']['leaving_baggage_info_INF']) ? $mainSearchAndSelectedSegments['main']['leaving_baggage_info_INF'][0] : $mainSearchAndSelectedSegments['main']['leaving_baggage_info_INF'] : 0,
                    ]
                );
            }

        }

        $currencyService = null;
        $displayedCurrency = ($this->requestData->getSelectedCurrency() == "") ? $this->defaultCurrency : $this->requestData->getSelectedCurrency();
        $this->requestData->setDisplayedCurrency($displayedCurrency);

        $conversionRate = 1;
        if ($displayedCurrency != $this->currencyPCC) {
            $currencyService = $this->container->get('CurrencyService');

            $conversionRate = $currencyService->getConversionRate($this->currencyPCC, $displayedCurrency);
        }

        $this->requestData->setConversionRate($conversionRate);

        $displayedPrice = $this->requestData->getOriginalPrice();
        if ($conversionRate != 1) {
            $displayedPrice = $currencyService->currencyConvert($this->requestData->getOriginalPrice(), $conversionRate);
        }

        $this->requestData->setConversionRate($conversionRate);
        $this->requestData->setDisplayedPrice($displayedPrice);


        foreach ($selectedSegments as $i => $segments) {

            $flight_type_var = $segments["flight_type"];
            $departure_date_time_var = $segments["departure_date_time"];
            $arrival_date_time_var = $segments["arrival_date_time"];
            $flight_number_var = $segments["flight_number"];
            $res_book_desig_code_var = $segments["res_book_desig_code"];
            $destination_location_var = $segments["destination_location"];
            $destination_location_city_var = $segments["destination_location_city"];
            $destination_location_airport_var = stripslashes($segments["destination_location_airport"]);
            $airline_code_var = $segments["airline_code"];
            $airline_name_var = $segments["airline_name"];
            $operating_airline_code_var = $segments["operating_airline_code"];
            $operating_airline_name_var = $segments["operating_airline_name"];
            $origin_location_var = $segments["origin_location"];
            $origin_location_airport_var =stripslashes( $segments["origin_location_airport"]);
            $origin_city_var = $segments["origin_location_city"];
            $stop_indicator_var = $segments["stop_indicator"];
            $stop_duration_var = $segments["stop_duration"];
            $cabin_code_var = $segments["cabin_code"];
            $cabin_var = $segments["cabin"];
            $flight_duration_var = $segments["flight_duration"];
            $fare_basis_code = $segments["fare_basis_code"];
            $arrival_terminal = $segments["arrival_terminal_id"];
            $departure_terminal = $segments["departure_terminal_id"];
            $aircraft_type = $segments["aircraft_type"];
            $mile_distance = $segments["mile_distance"];
            $elapsedTime = $segments["elapsedTime"];

            if(isset($segments["segmentAmounts"]))
            {
                $segmentAmount = $segments["segmentAmounts"]["price_attr"];
            }else{
                $segmentAmount = 0;
            }

            $this->requestData->setSegmentNumber($i + 1, $i);
            $this->requestData->setFlightDuration($flight_duration_var, $i);
            $this->requestData->setCabin($cabin_var, $i);
            $this->requestData->setCabinCode($cabin_code_var, $i);
            $this->requestData->setStopDuration($stop_duration_var, $i);
            $this->requestData->setStopIndicator((int)$stop_indicator_var, $i);
            $this->requestData->setOriginCity($origin_city_var, $i);
            $this->requestData->setOriginLocationAirport($origin_location_airport_var, $i);
            $this->requestData->setOriginLocation($origin_location_var, $i);
            $this->requestData->setOperatingAirlineName($operating_airline_name_var, $i);
            $this->requestData->setOperatingAirlineCode($operating_airline_code_var, $i);
            $this->requestData->setAirlineName($airline_name_var, $i);
            $this->requestData->setAirlineCode($airline_code_var, $i);
            $this->requestData->setDestinationLocationAirport($destination_location_airport_var, $i);
            $this->requestData->setDestinationLocationCity($destination_location_city_var, $i);
            $this->requestData->setDestinationLocation($destination_location_var, $i);
            $this->requestData->setResBookDesigCode($res_book_desig_code_var, $i);
            $this->requestData->setFlightNumber((int)$flight_number_var, $i);
            $this->requestData->setArrivalDateTime($arrival_date_time_var, $i);
            $this->requestData->setDepartureDateTime($departure_date_time_var, $i);
            $this->requestData->setFlightType($flight_type_var, $i);
            $this->requestData->setFareBasisCode($fare_basis_code, $i);
            $this->requestData->setOriginTerminalId($departure_terminal, $i);
            $this->requestData->setDestinationTerminalId($arrival_terminal, $i);
            $this->requestData->setAircraftType($aircraft_type, $i);
            $this->requestData->setSegmentAmount($segmentAmount, $i);
            $this->requestData->setMileDistance($mile_distance, $i);
            $this->requestData->setElapsedTime($elapsedTime, $i);

        }

        $flightSegments = $this->getRequestFlightSegments($this->requestData);
        $this->requestData->setFlightSegments($flightSegments);

        // echo thre request data to check the access token

        return $this->requestData;
    }

    public function getRequestFlightSegments($requestData)
    {
        $flightSegments = array();
        $multiCount = 0;

        $departureDateTime = $requestData->getDepartureDateTime();
        $arrivalDateTime = $requestData->getArrivalDateTime();
        $destinationLocation = $requestData->getDestinationLocation();
        $destinationLocationCity = $requestData->getDestinationLocationCity();
        $originCity = $requestData->getOriginCity();
        $originMainAirport = $requestData->getOriginLocation();
        $mainAirline = $requestData->getAirlineName();
        $flightNumber = $requestData->getFlightNumber();
        $destinationLocationAirport = $requestData->getDestinationLocationAirport();
        $airlineCode = $requestData->getAirlineCode();
        $airlineName = $requestData->getAirlineName();
        $operatingAirlineCode = $requestData->getOperatingAirlineCode();
        $operatingAirlineName = $requestData->getOperatingAirlineName();
        $originLocationAirport = $requestData->getOriginLocationAirport();
        $stopIndicator = $requestData->getStopIndicator();
        $stopDuration = $requestData->getStopDuration();
        $cabinCode = $requestData->getCabinCode();
        $cabin = $requestData->getCabin();
        $flightDuration = $requestData->getFlightDuration();

        $flightType = $requestData->getFlightType();
        $departure_terminal = $requestData->getOriginTerminalId();
        $arrival_terminal = $requestData->getDestinationTerminalId();
        $aircraft_type = $requestData->getAircraftType();
        $segmentAmount = $requestData->getSegmentAmount();
        $mileDistance = $requestData->getMileDistance();
        $elapsedTime = $requestData->getElapsedTime();

        $prev_flight_type = '';
        //for( $i = 0; $i < $requestData->getTotalSegments(); $i++ )
        foreach ($requestData->getTotalSegments() as $i => $segId) {
            $flightInformation = array();
            $flightSegments[$flightType[$i]]["one_way"] = $requestData->getOneWay();
            $flightSegments[$flightType[$i]]["multi_destination"] = $requestData->getMultiDestination();
            $flightSegments[$flightType[$i]]["departure_main_date"] = date("M j Y", strtotime($departureDateTime[$i]));
            $flightSegments[$flightType[$i]]["destination_main_airport"] = $destinationLocation[$i];
            $flightSegments[$flightType[$i]]["destination_city"] = $destinationLocationCity[$i];
            $flightSegments[$flightType[$i]]["origin_city"] = $originCity[$i];
            $flightSegments[$flightType[$i]]["origin_main_airport"] = $originMainAirport[$i];
            $flightSegments[$flightType[$i]]["main_airline"] = $mainAirline[$i];
            $flightSegments[$flightType[$i]]["segment_amount"] = $segmentAmount[$i];
            $flightInformation["departure_date"] = date("D j M", strtotime($departureDateTime[$i]));
            $flightInformation["departure_time"] = date("H\:i", strtotime($departureDateTime[$i]));
            $flightInformation["arrival_date"] = date("D j M", strtotime($arrivalDateTime[$i]));
            $flightInformation["arrival_time"] = date("H\:i", strtotime($arrivalDateTime[$i]));
            $flightInformation["flight_number"] = $flightNumber[$i];
            $flightInformation["destination_airport_code"] = $destinationLocation[$i];
            $flightInformation["destination_city"] = $destinationLocationCity[$i];
            $flightInformation["destination_airport"] = $destinationLocationAirport[$i];
            $flightInformation["airline_code"] = $airlineCode[$i];
            $flightInformation["airline_name"] = $airlineName[$i];
            $flightInformation["operating_airline_code"] = $operatingAirlineCode[$i];
            $flightInformation["operating_airline_name"] = $operatingAirlineName[$i];
            $flightInformation["origin_airport_code"] = $originMainAirport[$i];
            $flightInformation["origin_airport"] = $originLocationAirport[$i];
            $flightInformation["origin_city"] = $originCity[$i];
            $flightInformation["stop_indicator"] = $stopIndicator[$i];
            $flightInformation["segment_amount"] = $segmentAmount[$i];
            $flightInformation["mile_distance"] = $mileDistance[$i];
            if ($flightInformation["stop_indicator"]) {
                $flightInformation["stop_duration"] = $stopDuration[$i];
            }
            $flightInformation["cabin_code"] = $cabinCode[$i];
            $flightInformation["cabin"] = $cabin[$i];
            $flightInformation["flight_duration"] = $flightDuration[$i];
            $flightInformation["departure_terminal"] = (empty($departure_terminal[$i])) ? '' : $this->translator->trans('Terminal ').$departure_terminal[$i];
            $flightInformation["arrival_terminal"] = (empty($arrival_terminal[$i])) ? '' : $this->translator->trans('Terminal ').$arrival_terminal[$i];
            $flightInformation["aircraft_type"] = $aircraft_type[$i];

            $diff = strtotime($arrivalDateTime[$i]) - strtotime($departureDateTime[$i]);

            $flightInformation["trip_review"] = [
                'departure_date' => date("D. M j", strtotime($departureDateTime[$i])),
                'arrival_date' => $this->translator->trans('Arrives ').date("D, M j", strtotime($arrivalDateTime[$i])),
                'departure_time' => date("g\:ia", strtotime($departureDateTime[$i])),
                'arrival_time' => date("g\:ia", strtotime($arrivalDateTime[$i])),
                'duration' => $this->utils->duration_to_string($this->utils->mins_to_duration($elapsedTime[$i])),
            ];
            $flightInformation["elapsedTme"] = $elapsedTime[$i];
            if ($requestData->getMultiDestination()) {
                if ($flightInformation["stop_indicator"]) {
                    $flightSegments[$prev_flight_type]["flight_info"][$multiCount - 1]['stop_segment'][] = $flightInformation;
                }
                else {
                    $prev_flight_type = $flightType[$i];
                    $flightInformation['stop_segment'] = '';
                    $flightSegments[$prev_flight_type]["flight_info"][] = $flightInformation;
                    $multiCount++;
                }
            }
            else {
                if ($flightInformation["stop_indicator"]) {
                    $multiCount = sizeof($flightSegments[$flightType[$i]]["flight_info"]);
                    $flightSegments[$prev_flight_type]["flight_info"][$multiCount - 1]['stop_segment'][] = $flightInformation;
                }
                else {
                    $prev_flight_type = $flightType[$i];
                    $flightInformation['stop_segment'] = '';
                    $flightSegments[$prev_flight_type]["flight_info"][] = $flightInformation;
                }

            }
        }

        return $flightSegments;

    }

    public function getPassengerDetails($requestData)
    {
        $passengersArray = array();
        for ($i = 0; $i < $requestData->getAdultsQuantity(); $i++) {
            $adt = $requestData->getADT();
            $pitem = array();
            $pitem['type'] = 'ADT';
            $pitem['name'] = $this->translator->trans('Adult');
            $pitem['fare_calc_line'] = $adt[$i]['fare_calc_line'];
            $pitem['leaving_baggage_info'] = $adt[$i]['leaving_baggage_info'];
            $pitem['returning_baggage_info'] = $adt[$i]['returning_baggage_info'];
            $passengersArray[] = $pitem;
        }
        for ($i = 0; $i < $requestData->getChildrenQuantity(); $i++) {
            $cnn = $requestData->getCNN();
            $pitem = array();
            $pitem['type'] = 'CNN';
            $pitem['name'] = $this->translator->trans('Child');
            $pitem['fare_calc_line'] = $cnn[$i]['fare_calc_line'];
            $pitem['leaving_baggage_info'] = $cnn[$i]['leaving_baggage_info'];
            $pitem['returning_baggage_info'] = $cnn[$i]['returning_baggage_info'];
            $passengersArray[] = $pitem;
        }
        for ($i = 0; $i < $requestData->getInfantsQuantity(); $i++) {
            $inf = $requestData->getINF();
            $pitem = array();
            $pitem['type'] = 'INF';
            $pitem['name'] = $this->translator->trans('Infant');
            $pitem['fare_calc_line'] = $inf[$i]['fare_calc_line'];
            $pitem['leaving_baggage_info'] = $inf[$i]['leaving_baggage_info'];
            $pitem['returning_baggage_info'] = $inf[$i]['returning_baggage_info'];
            $passengersArray[] = $pitem;
        }

        $this->passenger->setPassengers($passengersArray);

        return $this->passenger;
    }

}
