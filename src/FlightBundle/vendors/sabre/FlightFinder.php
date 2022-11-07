<?php

namespace FlightBundle\vendors\sabre;

use Symfony\Component\DependencyInjection\Container;
use FlightBundle\Services\SabreServices;
use Doctrine\ORM\EntityManager;
use TTBundle\Services\CurrencyService;
use TTBundle\Utils\Utils;
use TTBundle\Model\ElasticSearchSC;

/**
 * FlightFinder Class
 *
 * This class finds all available flights from the search criteria
 *
 *
 */
class FlightFinder
{
    private $onProductionServer      = false;
    private $defaultCurrency         = "USD";
    private $currencyPCC             = "AED";
    private $connection_type_bfm     = 1;
    private $connection_type_booking = 2;
    private $enableCancelation       = true;
    private $enableRefundable        = true;
    private $discount                = 73.46; // value in AED, equivalent of USD 20
    private $methodOneByID           = "OneById"; //this is not used for now( a test to push on tt.ttflight)
    private $methodOneByYourEmail    = "OneByYourEmail"; // this is a second test ( a test to make tt.ttflight working properly)
    private $pricePercentMargin      = 20;
    
    /**
     * @var Container
     */
    private $container;
    
    /**
     * @var EntityManager
     */
    private $entityManager;
    
    /**
     * @var SabreServices
     */
    private $sabreServices;
    
    /**
     * @var CurrencyService
     */
    private $currencyService;
    
    /**
     * @var FlightItineraryNormaliser
     */
    private $flightItineraryNormaliser;
    
    /**
     * @var translator
     */
    private $translator;
    
    /**
     * @var Utils
     */
    private $utils;
    
    public function __construct(Container $container, EntityManager $entityManager, SabreServices $sabreServices, CurrencyService $currencyService,
        FlightItineraryNormaliser $flightItineraryNormaliser, $translator, Utils $utils)
    {
        $this->container                 = $container;
        $this->entityManager             = $entityManager;
        $this->sabreServices             = $sabreServices;
        $this->currencyService           = $currencyService;
        $this->flightItineraryNormaliser = $flightItineraryNormaliser;
        $this->translator                = $translator;
        $this->utils                     = $utils;
        
        $this->onProductionServer = ($this->container->hasParameter('ENVIRONMENT') && $this->container->getParameter('ENVIRONMENT') == 'production');
    }
    
    /**
     * Search that handle the search for the flights
     */
    function search($request, $session, $param)
    {
        $session = $request->getSession();
        $search  = $session->get('_corporate_flight_search');
        
        $from_mobile       = $request->request->get('from_mobile');
        $departureAirportN = $request->request->get('departureairport');
        $arrivalairportN   = $request->request->get('arrivalairport');
        $departureAirport  = $request->request->get('departureairportC');

        $arrivalairport    = $request->request->get('arrivalairportC');
        $fromDate          = $request->request->get('fromDateC');
        $toDate            = $request->request->get('toDateC');



        $flexibleDate      = $request->request->get('flexibledate', 0);
        $oneWay            = intval($request->request->get('oneway', 0));
        $cabinSelect       = $request->request->get('cabinselect', '');
        $adultsSelect      = intval($request->request->get('adultsselect', 1));
        $childrenSelect    = intval($request->request->get('childsselect', 0));
        $infantsSelect     = intval($request->request->get('infantsselect', 0));
        $multiDestination  = intval($request->request->get('multidestination', 0));
        $chosenAirline     = $request->request->get('chosenAirline', '');
        $priority          = $request->request->get('priority', 0);
        $destinations      = array();
        $noLogo            = "no-logo.jpg";
        $note              = $search['note'];


        $departureAirportN = isset($departureAirportN) ? $departureAirportN : $search['departureairport'];
        $arrivalairportN   = isset($arrivalairportN) ? $arrivalairportN : $search['arrivalairport'];
        $departureAirport  = isset($departureAirport) ? $departureAirport : $search['departureairportC'];
        $arrivalairport    = isset($arrivalairport) ? $arrivalairport : $search['arrivalairportC'];

        $fromDate          = isset($fromDate) ? $fromDate : $search['fromDateC'];
        $toDate            = isset($toDate) ? $toDate : $search['toDateC'];
        $oneWay            = isset($oneWay) ? $oneWay : $search['oneway'];
        $note              = isset($note) ? $note : '';
        
        //$this->addFlightLog('Searched available flights with criteria: {criteria}', array('criteria' => $request->request->all()));
        
        if ($multiDestination) {
            $oneWay            = 1;
            $destinationsCount = 2;
            for ($i = 0; $i < $destinationsCount; $i++) {
                
                $departure_airport_multi_var = "departureairportC-$i";
                $departureAirportMulti       = $request->request->get($departure_airport_multi_var, '');
                
                $arrival_airport_multi_var = "arrivalairportC-$i";
                $arrivalAirportMulti       = $request->request->get($arrival_airport_multi_var, '');
                
                $from_date_multi_var = "fromDateC-$i";
                $fromDateMulti       = $request->request->get($from_date_multi_var, '');
                
                if ($departureAirportMulti == "" || $arrivalAirportMulti == "" || $fromDateMulti == "") break;
                
                $destinations[$i]["departure_airport"] = $departureAirportMulti;
                $destinations[$i]["arrival_airport"]   = $arrivalAirportMulti;
                $destinations[$i]["from_date"]         = $fromDateMulti.'T00:00:00';
            }
        }
        
        $return                      = [];
        $return['error']             = [];
        $return['departureAirportN'] = $departureAirportN;
        $return['arrivalairportN']   = $arrivalairportN;
        $return['departureAirport']  = $departureAirport;
        $return['arrivalairport']    = $arrivalairport;
        $return['fromDate']          = $fromDate;
        $return['toDate']            = $toDate;
        $return['flexibleDate']      = $flexibleDate;
        $return['cabinSelect']       = $cabinSelect;
        $cabinName                   = $this->container->get('FlightRepositoryServices')->FlightCabinFinder($cabinSelect);
        $return['cabinName']         = ($cabinName) ? $cabinName->getName() : $cabinSelect;
        $return['adultsSelect']      = $adultsSelect;
        $return['childrenSelect']    = $childrenSelect;
        $return['infantsSelect']     = $infantsSelect;
        $return['note']              = $note;
        
        if ($adultsSelect == 0) {
            $adultsSelect = 1;
        }
        
        if ($adultsSelect < $infantsSelect) {
            $return['error']['message'] = $this->translator->trans("The number of infants must not be greater than the number of adults!");
        }
        
        if ($adultsSelect > 1) {
            $return['ADT'] = '<br>'.$adultsSelect.' Adults';
        } else {
            $return['ADT'] = '<br>'.$adultsSelect.' Adult';
        }
        
        if ($childrenSelect > 1) {
            $return['CNN'] = '<br>'.$childrenSelect.' Children';
        } elseif ($childrenSelect == 1) {
            $return['CNN'] = '<br>'.$childrenSelect.' Child';
        } else {
            $return['CNN'] = '';
        }
        
        if ($infantsSelect > 1) {
            $return['INF'] = '<br>'.$infantsSelect.' Infants';
        } elseif ($infantsSelect == 1) {
            $return['INF'] = '<br>'.$infantsSelect.' Infant';
        } else {
            $return['INF'] = '';
        }
        
        $numberInParty = intval($adultsSelect + $childrenSelect);
        
        $return['airlines']          = '';
        $return['currency_code']     = '';
        $return['minimum_duration']  = 0;
        $return['maximum_duration']  = 0;
        $return['minimum_price']     = 0;
        $return['maximum_price']     = 0;
        $return['one_way']           = $oneWay;
        $return['multi_destination'] = $multiDestination;
        $return['enableCancelation'] = $this->enableCancelation;
        $return['enableRefundable']  = $this->enableRefundable;
        
        $getAirlines = $this->entityManager->getRepository('TTBundle:Airline')->findAll();
        
        $airlineCount = sizeof($getAirlines);
        
        for ($i = 0; $i < $airlineCount; $i++) {
            $airlineInfo[$i]['code']     = $getAirlines[$i]->getCode();
            $airlineInfo[$i]['nameCode'] = $getAirlines[$i]->getName().' ('.$airlineInfo[$i]['code'].')';
        }
        
        $return['ChosenAirlinesList'] = $airlineInfo;
        
        $service = $flexibleDate ? 'BargainFinderMax_ADRQ' : 'BargainFinderMaxRQ';
        $action  = $service;
        
        $sabreVariables = $this->sabreServices->getSabreConnectionVariables($this->onProductionServer);
        
        $create_session_response = $this->sabreServices
        ->createSabreSessionRequest(
            $sabreVariables, (isset($param['isUserLoggedIn']) ? $param['USERID'] : 0), $this->connection_type_bfm, ($from_mobile ? 'mobile' : 'web')
            );
        
        if ($create_session_response['status'] === 'success') {
            
            $accessToken            = $create_session_response['AccessToken'];
            $returnedConversationId = ($create_session_response['ConversationId'] == '') ? '@touristtube.com' : $create_session_response['ConversationId'];
            
            $sabreVariables['access_token']           = $accessToken;
            $sabreVariables['returnedConversationId'] = $returnedConversationId;
            
            $sabreVariables['Service'] = $service;
            $sabreVariables['Action']  = $action;
            
            $sabreVariables['OriginLocation']        = $departureAirport;
            $sabreVariables['DestinationLocation']   = $arrivalairport;
            $sabreVariables['FromDate']              = $fromDate.'T00:00:00';
            $sabreVariables['ToDate']                = $toDate.'T23:59:59';
            $sabreVariables['cabinPref']             = $cabinSelect;
            $sabreVariables['TripType']              = $oneWay ? 'OneWay' : 'Return';
            $sabreVariables['FlexibleDate']          = $flexibleDate;
            $sabreVariables['PassengerTypeAdults']   = $adultsSelect;
            $sabreVariables['PassengerTypeChildren'] = $childrenSelect;
            $sabreVariables['PassengerTypeInfants']  = $infantsSelect;
            $sabreVariables['chosenAirline']         = $chosenAirline;
            $sabreVariables['priority']              = $priority;
            
            $sabreVariables['MultiDestination'] = $multiDestination;
            $sabreVariables['destinations']     = $destinations;
            $sabreVariables['CurrencyCode']     = $this->currencyPCC; //$this->defaultCurrency;
            
            //$this->addFlightLog('flightBookingResultAction:: BargainFinderMaxRQ:: sabreVariables:: '.print_r($sabreVariables, true));
            $priced_itineraries = $this->sabreServices->createBargainRequest($sabreVariables); // , true); // temporarily enable debugging request and response for BFM requests
            //$this->addFlightLog('Getting API BargainFinderMaxRQ with response: '.$priced_itineraries['status']);
            //$this->addFlightLog('With criteria: {criteria}', array('criteria' => $priced_itineraries));
            
            if ($priced_itineraries['status'] == 'errors' || $priced_itineraries['status'] == 'error') {
                
                $this->sabreServices->closeSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
                //$this->addFlightLog('Requesting API SessionCloseRQ');
                if (($priced_itineraries['status'] == 'errors' || $priced_itineraries['status'] == 'error') && $from_mobile == 1) {
                    //$this->addFlightLog('Sending MobileRQ with response: '.$res);
                    $return['error']['message'] = $this->translator->trans("Could not connect to server please try again");
                } elseif ($priced_itineraries['status'] === 'error' && ($priced_itineraries['faultcode'] === 'InvalidSecurityToken' || $priced_itineraries['faultcode'] === 'InvalidEbXmlMessage')) {
                    $return['error']['redirect']             = [];
                    $return['error']['redirect']['route']    = '_flight_booking';
                    $return['error']['redirect']['timedOut'] = true;
                } elseif ($priced_itineraries['status'] === 'error') {
                    $return['error']['message'] = $priced_itineraries['message'];
                }
            }
            
            $segmentsArray               = array();
            $currency                    = "";
            $currencyCode                = filter_input(INPUT_COOKIE, 'currency');
            $return['selected_currency'] = ($currencyCode == "") ? $this->defaultCurrency : $currencyCode;
            
            $sequence_numberNew = 0;
            $conversionRate     = $this->currencyService->getConversionRate($this->currencyPCC, $currencyCode);
            
            $normalize_response = $this->flightItineraryNormaliser->normalize(
                $priced_itineraries, $this->discount, $currencyCode, $this->defaultCurrency, $this->currencyPCC, $oneWay, $fromDate.'T00:00:00', $flexibleDate, $toDate, $multiDestination, $numberInParty, $sabreVariables['salt']
                );
            
            
            if (!$normalize_response) {
                $return['no_data']       = true;
                $return['no_filter_css'] = 'no-filter';
            }
            
            $this->sabreServices->closeSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
            //$this->addFlightLog('Requesting API SessionCloseRQ');
            $return['segmentsArray']      = $normalize_response['itineraries'];
            $return['airlines']           = $normalize_response['airlines'];
            $return['currency']           = $currency ? $currency : 'AED';
            $return['currency_code']      = $currencyCode ? $currencyCode : 'USD';
            $return['minimum_duration']   = $normalize_response['durations']['minimumDuration'];
            $return['maximum_duration']   = $normalize_response['durations']['maximumDuration'];
            $return['minimum_price']      = $normalize_response['durations']['minimumPrice'];
            $return['maximum_price']      = $normalize_response['durations']['maximumPrice'];
            $return['num_in_party']       = $numberInParty;
            $return['one_way']            = $oneWay;
            $return['multi_destination']  = $multiDestination;
            $return['departure_airports'] = $normalize_response['departure_airports'];
            
            if ($oneWay == 0) {
                $return['outbound'] = $normalize_response['outbound']['itineraries'];
                $return['inbound']  = $normalize_response['inbound']['itineraries'];
            }
            
            /*
             $response = array();
             $response = $this->data;
             return $response;
             
             */
        } else {
            $return['error']['message'] = $create_session_response['message'];
            /*
             $response = array();
             $response = $this->data;
             return $response;
             */
        }
        
        if ($from_mobile == 1) {
            $return['from_mobile'] = 1;
            return $return;
        } else {
            
            return array_merge($param, $return);
        }
    }
    
    /**
     * A search RoundTrip its a function that handle the request from the web, to return a Splited Result
     * @return mixed
     */
    function searchRoundTrip($request, $session, $param)
    {
        $session = $request->getSession();
        $search  = $session->get('_corporate_flight_search');
        
        $from_mobile       = $request->request->get('from_mobile');
        $departureAirportN = $request->request->get('departureairport');
        $arrivalairportN   = $request->request->get('arrivalairport');
        $departureAirport  = $request->request->get('departureairportC');
        $arrivalairport    = $request->request->get('arrivalairportC');
        $fromDate          = $request->request->get('fromDateC');
        $toDate            = $request->request->get('toDateC');
        $flexibleDate      = $request->request->get('flexibledate', 0);
        $oneWay            = intval($request->request->get('oneway', 0));
        $cabinSelect       = $request->request->get('cabinselect', '');
        $adultsSelect      = intval($request->request->get('adultsselect', 1));
        $childrenSelect    = intval($request->request->get('childsselect', 0));
        $infantsSelect     = intval($request->request->get('infantsselect', 0));
        $multiDestination  = intval($request->request->get('multidestination', 0));
        $chosenAirline     = $request->request->get('chosenAirline', '');
        $priority          = $request->request->get('priority', 0);
        $destinations      = array();
        $noLogo            = "no-logo.jpg";
        $note              = $search['note'];
        
        $departureAirportN = isset($departureAirportN) ? $departureAirportN : $search['departureairport'];
        $arrivalairportN   = isset($arrivalairportN) ? $arrivalairportN : $search['arrivalairport'];
        $departureAirport  = isset($departureAirport) ? $departureAirport : $search['departureairportC'];
        $arrivalairport    = isset($arrivalairport) ? $arrivalairport : $search['arrivalairportC'];
        $fromDate          = isset($fromDate) ? $fromDate : $search['fromDateC'];
        $toDate            = isset($toDate) ? $toDate : $search['toDateC'];
        $oneWay            = isset($oneWay) ? $oneWay : $search['oneway'];
        $note              = isset($note) ? $note : '';
        
        //$this->addFlightLog('Searched available flights with criteria: {criteria}', array('criteria' => $request->request->all()));
        
        if ($multiDestination) {
            $oneWay            = 1;
            $destinationsCount = 2;
            for ($i = 0; $i < $destinationsCount; $i++) {
                
                $departure_airport_multi_var = "departureairportC-$i";
                $departureAirportMulti       = $request->request->get($departure_airport_multi_var, '');
                
                $arrival_airport_multi_var = "arrivalairportC-$i";
                $arrivalAirportMulti       = $request->request->get($arrival_airport_multi_var, '');
                
                $from_date_multi_var = "fromDateC-$i";
                $fromDateMulti       = $request->request->get($from_date_multi_var, '');
                
                if ($departureAirportMulti == "" || $arrivalAirportMulti == "" || $fromDateMulti == "") break;
                
                $destinations[$i]["departure_airport"] = $departureAirportMulti;
                $destinations[$i]["arrival_airport"]   = $arrivalAirportMulti;
                $destinations[$i]["from_date"]         = $fromDateMulti.'T00:00:00';
            }
        }
        
        $return                      = [];
        $return['error']             = [];
        $return['departureAirportN'] = $departureAirportN;
        $return['arrivalairportN']   = $arrivalairportN;
        $return['departureAirport']  = $departureAirport;
        $return['arrivalairport']    = $arrivalairport;
        $return['fromDate']          = $fromDate;
        $return['toDate']            = $toDate;
        $return['flexibleDate']      = $flexibleDate;
        $return['cabinSelect']       = $cabinSelect;
        $cabinName                   = $this->container->get('FlightRepositoryServices')->FlightCabinFinder($cabinSelect);
        $return['cabinName']         = ($cabinName) ? $cabinName->getName() : $cabinSelect;
        $return['adultsSelect']      = $adultsSelect;
        $return['childrenSelect']    = $childrenSelect;
        $return['infantsSelect']     = $infantsSelect;
        $return['note']              = $note;
        $return['destinations']      = $destinations;
        $return['cookieRequest']      = isset($cookieRequest)? $cookieRequest : array();
		
        if ($adultsSelect == 0) {
            $adultsSelect = 1;
        }
        
        if ($adultsSelect < $infantsSelect) {
            $return['error']['message'] = $this->translator->trans("The number of infants must not be greater than the number of adults!");
        }
        
        if ($adultsSelect > 1) {
            $return['ADT'] = '<br>'.$adultsSelect.' Adults';
        } else {
            $return['ADT'] = '<br>'.$adultsSelect.' Adult';
        }
        
        if ($childrenSelect > 1) {
            $return['CNN'] = '<br>'.$childrenSelect.' Children';
        } elseif ($childrenSelect == 1) {
            $return['CNN'] = '<br>'.$childrenSelect.' Child';
        } else {
            $return['CNN'] = '';
        }
        
        if ($infantsSelect > 1) {
            $return['INF'] = '<br>'.$infantsSelect.' Infants';
        } elseif ($infantsSelect == 1) {
            $return['INF'] = '<br>'.$infantsSelect.' Infant';
        } else {
            $return['INF'] = '';
        }
        
        $numberInParty = intval($adultsSelect + $childrenSelect);
        
        $return['airlines']          = '';
        $return['currency_code']     = '';
        $return['minimum_duration']  = 0;
        $return['maximum_duration']  = 0;
        $return['minimum_price']     = 0;
        $return['maximum_price']     = 0;
        $return['one_way']           = $oneWay;
        $return['multi_destination'] = $multiDestination;
        $return['enableCancelation'] = $this->enableCancelation;
        $return['enableRefundable']  = $this->enableRefundable;
        
        $getAirlines = $this->entityManager->getRepository('TTBundle:Airline')->findAll();
        
        $airlineCount = sizeof($getAirlines);
        
        for ($i = 0; $i < $airlineCount; $i++) {
            $airlineInfo[$i]['code']     = $getAirlines[$i]->getCode();
            $airlineInfo[$i]['nameCode'] = $getAirlines[$i]->getName().' ('.$airlineInfo[$i]['code'].')';
        }
        
        $return['ChosenAirlinesList'] = $airlineInfo;
        
        $service = $flexibleDate ? 'BargainFinderMax_ADRQ' : 'BargainFinderMaxRQ';
        $action  = $service;
        
        $sabreVariables = $this->sabreServices->getSabreConnectionVariables($this->onProductionServer);
        
        $create_session_response = $this->sabreServices
        ->createSabreSessionRequest(
            $sabreVariables, (isset($param['isUserLoggedIn']) ? $param['USERID'] : 0), $this->connection_type_bfm, ($from_mobile ? 'mobile' : 'web')
            );
        
        if ($create_session_response['status'] === 'success') {
            
            $accessToken            = $create_session_response['AccessToken'];
            $returnedConversationId = ($create_session_response['ConversationId'] == '') ? '@touristtube.com' : $create_session_response['ConversationId'];
            
            $sabreVariables['access_token']           = $accessToken;
            $sabreVariables['returnedConversationId'] = $returnedConversationId;
            
            $sabreVariables['Service'] = $service;
            $sabreVariables['Action']  = $action;
            
            $sabreVariables['OriginLocation']        = $departureAirport;
            $sabreVariables['DestinationLocation']   = $arrivalairport;
            $sabreVariables['FromDate']              = $fromDate.'T00:00:00';
            $sabreVariables['ToDate']                = $toDate.'T23:59:59';
            $sabreVariables['cabinPref']             = $cabinSelect;
            $sabreVariables['TripType']              = $oneWay ? 'OneWay' : 'Return';
            $sabreVariables['FlexibleDate']          = $flexibleDate;
            $sabreVariables['PassengerTypeAdults']   = $adultsSelect;
            $sabreVariables['PassengerTypeChildren'] = $childrenSelect;
            $sabreVariables['PassengerTypeInfants']  = $infantsSelect;
            $sabreVariables['chosenAirline']         = $chosenAirline;
            $sabreVariables['priority']              = $priority;
            
            $sabreVariables['MultiDestination'] = $multiDestination;
            $sabreVariables['destinations']     = $destinations;
            
            $sabreVariables['CurrencyCode'] = $this->defaultCurrency;
            
            //$this->addFlightLog('flightBookingResultAction:: BargainFinderMaxRQ:: sabreVariables:: '.print_r($sabreVariables, true));
            $priced_itineraries = $this->sabreServices->createBargainRequest($sabreVariables); // , true); // temporarily enable debugging request and response for BFM requests
            //$this->addFlightLog('Getting API BargainFinderMaxRQ with response: '.$priced_itineraries['status']);
            //$this->addFlightLog('With criteria: {criteria}', array('criteria' => $priced_itineraries));
            
            if ($priced_itineraries['status'] == 'errors' || $priced_itineraries['status'] == 'error') {
                
                $this->sabreServices->closeSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
                //$this->addFlightLog('Requesting API SessionCloseRQ');
                if (($priced_itineraries['status'] == 'errors' || $priced_itineraries['status'] == 'error') && $from_mobile == 1) {
                    //$this->addFlightLog('Sending MobileRQ with response: '.$res);
                    $return['error']['message'] = $this->translator->trans("Could not connect to server please try again");
                } elseif ($priced_itineraries['status'] === 'error' && ($priced_itineraries['faultcode'] === 'InvalidSecurityToken' || $priced_itineraries['faultcode'] === 'InvalidEbXmlMessage')) {
                    $return['error']['redirect']             = [];
                    $return['error']['redirect']['route']    = '_flight_booking';
                    $return['error']['redirect']['timedOut'] = true;
                } elseif ($priced_itineraries['status'] === 'error') {
                    $return['error']['message'] = $priced_itineraries['message'];
                }
            }
            
            $segmentsArray               = array();
            $currency                    = "";
            $currencyCode                = filter_input(INPUT_COOKIE, 'currency');
            $return['selected_currency'] = ($currencyCode == "") ? $this->defaultCurrency : $currencyCode;
            
            $sequence_numberNew = 0;
            $conversionRate     = $this->currencyService->getConversionRate($this->currencyPCC, $currencyCode);
            
            $normalize_response = $this->flightItineraryNormaliser->normalize(
                $priced_itineraries, $this->discount, $currencyCode, $this->defaultCurrency, $this->currencyPCC, $oneWay, $fromDate.'T00:00:00', $flexibleDate, $toDate, $multiDestination, $numberInParty, $sabreVariables['salt']
                );
            
            
            if (!$normalize_response) {
                $return['no_data']       = true;
                $return['no_filter_css'] = 'no-filter';
            }
            
            $this->sabreServices->closeSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
            //$this->addFlightLog('Requesting API SessionCloseRQ');
            $return['segmentsArray']      = $normalize_response['itineraries'];
            $return['airlines']           = $normalize_response['airlines'];
            $return['currency']           = $currency ? $currency : 'AED';
            $return['currency_code']      = $currencyCode ? $currencyCode : 'USD';
            $return['minimum_duration']   = $normalize_response['durations']['minimumDuration'];
            $return['maximum_duration']   = $normalize_response['durations']['maximumDuration'];
            $return['minimum_price']      = $normalize_response['durations']['minimumPrice'];
            $return['maximum_price']      = $normalize_response['durations']['maximumPrice'];
            $return['num_in_party']       = $numberInParty;
            $return['one_way']            = $oneWay;
            $return['multi_destination']  = $multiDestination;
            $return['departure_airports'] = $normalize_response['departure_airports'];
            
            if ($oneWay == 0) {
                $return['outbound'] = $normalize_response['outbound']['itineraries'];
                $return['inbound']  = $normalize_response['inbound']['itineraries'];
            }
            
            /*
             $response = array();
             $response = $this->data;
             return $response;
             
             */
        } else {
            $return['error']['message'] = $create_session_response['message'];
            /*
             $response = array();
             $response = $this->data;
             return $response;
             */
        }
        
        if ($from_mobile == 1) {
            $return['from_mobile'] = 1;
            return $return;
        } else {
            
            return array_merge($param, $return);
        }
    }
    
    /**
     *
     * @return mixed
     */
    function searchNew($request, $session, $param)
    {

        $session = $request->getSession();
        $search  = $session->get('_corporate_flight_search');
			
		$post = $request->request->all();

        // retrieves a $_COOKIE value
        $flightCookie = json_decode($request->cookies->get('TT-flight'),true);
        if($flightCookie)
        {
            $cookiefilters = array();
            if($flightCookie){
                foreach($flightCookie['filter'] as $flightFilter){
                    $cookiefilters[$flightFilter['id']] = $flightFilter['value'];
                }
            }            
        }

        $from_mobile           = $request->request->get('from_mobile');
        $departureAirportN_arr = $request->request->get('departureairport');
        $arrivalairportN_arr   = $request->request->get('arrivalairport');
        $departureAirport_arr  = $request->request->get('departureairportC');
        $arrivalairport_arr    = $request->request->get('arrivalairportC');

        $fromDate_arr          = $request->request->get('fromDate');

        $toDate_arr            = $request->request->get('toDate');
        $flexibleDate          = intval($request->request->get('flexibledate', 0));
        $oneWay                = intval($request->request->get('oneway', 0));
        $cabinSelect           = $request->request->get('cabinselect', '');
        $adultsSelect          = intval($request->request->get('adultsselect', 1));
        $childrenSelect        = intval($request->request->get('childsselect', 0));
        $infantsSelect         = intval($request->request->get('infantsselect', 0));
        $multiDestination      = intval($request->request->get('multidestination', 0));
        $chosenAirline         = $request->request->get('chosenAirline', '');
        $priority              = intval($request->request->get('priority', 0));
        $destinations          = array();
        $noLogo                = "no-logo.jpg";


        $note = isset($search['note']) ? $search['note'] : '';
        
        $departureAirportN = $departureAirportN_arr[0];
        $departureAirport  = $departureAirport_arr[0];
        $arrivalairportN   = $arrivalairportN_arr[0];
        $arrivalairport    = $arrivalairport_arr[0];
        
        $fromDate = $fromDate_arr[0];

        //multidestination fromDates
        if (count($fromDate_arr) > count($toDate_arr)) {
            $idx    = count($fromDate_arr) - 1;
            $toDate = $fromDate_arr[$idx];
        } else {
            $toDate = $toDate_arr[0];
        }

        $departureAirportN = isset($departureAirportN) ? $departureAirportN : $search['departureairport'];
        $arrivalairportN   = isset($arrivalairportN) ? $arrivalairportN : $search['arrivalairport'];
        $departureAirport  = isset($departureAirport) ? $departureAirport : $search['departureairportC'];
        $arrivalairport    = isset($arrivalairport) ? $arrivalairport : $search['arrivalairportC'];
        $fromDate          = isset($fromDate) ? $fromDate : $search['fromDateC'];
        $toDate            = isset($toDate) ? $toDate : $search['toDateC'];
        $oneWay            = isset($oneWay) ? $oneWay : $search['oneway'];
        $note              = isset($note) ? $note : '';
        
        $cabinSelect = empty($cabinSelect) ? 'Y' : $cabinSelect;

        //if post is not from flight booking form, then we need to get it from cookies
        $cookieRequest = array();
        if(!$post){
            $departureAirport  = $cookiefilters['departureairportC_1'];
            $arrivalairport    = $cookiefilters['arrivalairportC_1'];
            $fromDate          = $cookiefilters['fromDate_1'];
            $toDate            = $cookiefilters['toDate_1'];
            $oneWay            = $cookiefilters['oneway'];            
            $cabinSelect       = empty($cookiefilters['cabinselect'])? 'Y' : $cookiefilters['cabinselect'];
            $adultsSelect      = $cookiefilters['adultsselect'];
            $childrenSelect    = $cookiefilters['childsselect'];
            $infantsSelect     = $cookiefilters['infantsselect'];
            $multiDestination  = $cookiefilters['multiDestination'];
            $multiDestinationC = $cookiefilters['multiDestinationC'];
            $flexibleDate      = $cookiefilters['flexibledate'];

            if($multiDestination){
                for($i = 2; $i <= $multiDestinationC; $i++){
                    $destinations[] = [
                        'departure_airport' => $cookiefilters['departureairportC_'.$i],
                        'arrival_airport' => $cookiefilters['arrivalairportC_'.$i],
                        'from_date' => $cookiefilters['fromDate_'.$i].'T00:00:00'
                    ];
                }

            }

            $cookieRequest['departureairport']  =  array($cookiefilters['departureairport_1']);
            $cookieRequest['departureairportC'] =  array($departureAirport);
            $cookieRequest['arrivalairport']    =  array($cookiefilters['arrivalairportC_1']);
            $cookieRequest['arrivalairportC']   =  array($arrivalairport);
            $cookieRequest['fromDate']          =  array($fromDate);
            $cookieRequest['toDate']            =  array($toDate);
            $cookieRequest['multidestination']  =  $multiDestination;
            $cookieRequest['multidestinationC'] =  $multiDestinationC;
            $cookieRequest['oneway']            =  $oneWay;
            $cookieRequest['cabinselect']       =  $cabinSelect;
            $cookieRequest['adultsselect']      =  $adultsSelect;
            $cookieRequest['childsselect']      =  $childrenSelect;
            $cookieRequest['infantsselect']     =  $infantsSelect;
            $cookieRequest['flexibledate']      =  $flexibleDate;
            $cookieRequest['destinations']      =  $destinations;
        }

        //$this->addFlightLog('Searched available flights with criteria: {criteria}', array('criteria' => $request->request->all()));

        if ($multiDestination) {
            $oneWay            = 1;
            $destinationsCount = 2;
            
            if (count($departureAirport_arr) > 1) {

                unset($departureAirport_arr[0]); //remove first index since it is assigned to $departureAirport

                foreach ($departureAirport_arr as $i => $value) {

                    $destinations[] = [
                        'departure_airport' => $value,
                        'arrival_airport' => $arrivalairport_arr[$i],
                        'from_date' => $fromDate_arr[$i].'T00:00:00'
                    ];
                }


            }
        }

        $return                      = [];
        $return['error']             = [];
        $return['departureAirportN'] = $departureAirportN;
        $return['arrivalairportN']   = $arrivalairportN;
        $return['departureAirport']  = $departureAirport;
        $return['arrivalairport']    = $arrivalairport;
        $return['fromDate']          = $fromDate;
        $return['toDate']            = $toDate;
        $return['flexibleDate']      = $flexibleDate;
        $return['cabinSelect']       = $cabinSelect;
        $cabinName                   = $this->container->get('FlightRepositoryServices')->FlightCabinFinder($cabinSelect);
        $return['cabinName']         = ($cabinName) ? $cabinName->getName() : $cabinSelect;
        $return['adultsSelect']      = $adultsSelect;
        $return['childrenSelect']    = $childrenSelect;
        $return['infantsSelect']     = $infantsSelect;
        $return['note']              = $note;
        $return['destinations']      = $destinations;
        $return['cookieRequest']      = isset($cookieRequest)? $cookieRequest : array();
        
        if ($adultsSelect == 0) {
            $adultsSelect = 1;
        }
        
        if ($adultsSelect < $infantsSelect) {
            $return['error']['message'] = $this->translator->trans("The number of infants must not be greater than the number of adults!");
        }
        
        if ($adultsSelect > 1) {
            $return['ADT'] = ''.$adultsSelect.' Adults';
        } else {
            $return['ADT'] = ''.$adultsSelect.' Adult';
        }
        
        if ($childrenSelect > 1) {
            $return['CNN'] = ''.$childrenSelect.' Children';
        } elseif ($childrenSelect == 1) {
            $return['CNN'] = ''.$childrenSelect.' Child';
        } else {
            $return['CNN'] = '';
        }
        
        if ($infantsSelect > 1) {
            $return['INF'] = ''.$infantsSelect.' Infants';
        } elseif ($infantsSelect == 1) {
            $return['INF'] = ''.$infantsSelect.' Infant';
        } else {
            $return['INF'] = '';
        }

        $numberInParty = intval($adultsSelect + $childrenSelect);

        $return['airlines']          = '';
        $return['currency_code']     = '';
        $return['minimum_duration']  = 0;
        $return['maximum_duration']  = 0;
        $return['minimum_price']     = 0;
        $return['maximum_price']     = 0;
        $return['one_way']           = $oneWay;
        $return['multi_destination'] = $multiDestination;
        $return['enableCancelation'] = $this->enableCancelation;
        $return['enableRefundable']  = $this->enableRefundable;
        
        $getAirlines = $this->entityManager->getRepository('TTBundle:Airline')->findAll();
        
        $airlineCount = sizeof($getAirlines);
        
        for ($i = 0; $i < $airlineCount; $i++) {
            $airlineInfo[$i]['code']     = $getAirlines[$i]->getCode();
            $airlineInfo[$i]['nameCode'] = $getAirlines[$i]->getName().' ('.$airlineInfo[$i]['code'].')';
        }
        
        $return['ChosenAirlinesList'] = $airlineInfo;
        
        $service = $flexibleDate ? 'BargainFinderMax_ADRQ' : 'BargainFinderMaxRQ';
        $action  = $service;
        
        $sabreVariables = $this->sabreServices->getSabreConnectionVariables($this->onProductionServer);

        $create_session_response = $this->sabreServices
        ->createSabreSessionRequest(
            $sabreVariables, (isset($param['isUserLoggedIn']) ? $param['USERID'] : 0), $this->connection_type_bfm, ($from_mobile ? 'mobile' : 'web')
            );

        if ($create_session_response['status'] === 'success') {
            
            $accessToken            = $create_session_response['AccessToken'];
            $returnedConversationId = ($create_session_response['ConversationId'] == '') ? '@touristtube.com' : $create_session_response['ConversationId'];
            
            $sabreVariables['access_token']           = $accessToken;
            $sabreVariables['returnedConversationId'] = $returnedConversationId;
            
            $sabreVariables['Service'] = $service;
            $sabreVariables['Action']  = $action;
            
            $sabreVariables['OriginLocation']        = $departureAirport;
            $sabreVariables['DestinationLocation']   = $arrivalairport;
            $sabreVariables['FromDate']              = $fromDate.'T00:00:00';
            $sabreVariables['ToDate']                = $toDate.'T23:59:59';
            $sabreVariables['cabinPref']             = $cabinSelect;
            $sabreVariables['TripType']              = $oneWay ? 'OneWay' : 'Return';
            $sabreVariables['FlexibleDate']          = $flexibleDate;
            $sabreVariables['PassengerTypeAdults']   = $adultsSelect;
            $sabreVariables['PassengerTypeChildren'] = $childrenSelect;
            $sabreVariables['PassengerTypeInfants']  = $infantsSelect;
            $sabreVariables['chosenAirline']         = $chosenAirline;
            $sabreVariables['priority']              = $priority;
            
            $sabreVariables['MultiDestination'] = $multiDestination;
            $sabreVariables['destinations']     = $destinations;
            
            $sabreVariables['CurrencyCode'] = $this->currencyPCC; //$this->defaultCurrency;

            //$this->addFlightLog('flightBookingResultAction:: BargainFinderMaxRQ:: sabreVariables:: '.print_r($sabreVariables, true));
            $priced_itineraries = $this->sabreServices->createBargainRequest($sabreVariables); // , true); // temporarily enable debugging request and response for BFM requests


            //echo '<textarea id="debug_bargain_response" isOneWay="' . $oneWay . '" style="display: ;" cols="120" rows="20">' . json_encode($priced_itineraries) . '</textarea>';
            
            //$this->addFlightLog('Getting API BargainFinderMaxRQ with response: '.$priced_itineraries['status']);
            //$this->addFlightLog('With criteria: {criteria}', array('criteria' => $priced_itineraries));
            
            if ($priced_itineraries['status'] == 'errors' || $priced_itineraries['status'] == 'error') {
                
                $this->sabreServices->closeSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
                //$this->addFlightLog('Requesting API SessionCloseRQ');
                if (($priced_itineraries['status'] == 'errors' || $priced_itineraries['status'] == 'error') && $from_mobile == 1) {
                    //$this->addFlightLog('Sending MobileRQ with response: '.$res);
                    $return['error']['message'] = $this->translator->trans("Could not connect to server please try again");
                } elseif ($priced_itineraries['status'] === 'error' && ($priced_itineraries['faultcode'] === 'InvalidSecurityToken' || $priced_itineraries['faultcode'] === 'InvalidEbXmlMessage')) {
                    $return['error']['redirect']             = [];
                    $return['error']['redirect']['route']    = '_flight_booking';
                    $return['error']['redirect']['timedOut'] = true;
                } elseif ($priced_itineraries['status'] === 'error') {
                    $return['error']['message'] = $priced_itineraries['message'];
                }
            }
            
            $segmentsArray               = array();
            $currency                    = "";
            $currencyCode                = filter_input(INPUT_COOKIE, 'currency');
            $return['selected_currency'] = ($currencyCode == "") ? $this->defaultCurrency : $currencyCode;
            
            $sequence_numberNew = 0;
            $conversionRate     = $this->currencyService->getConversionRate($this->currencyPCC, $currencyCode);

// echo "<pre id='debug_sabreVariables_TripType'>" . $sabreVariables['TripType'] . "</pre>";

            /* Round Trip Price Optimization
            if ($sabreVariables['TripType'] == 'Return') {
                //if (isset($priced_itineraries['data']) && $priced_itineraries['data']) {
                $combinedData       = $this->getListFromCombined($priced_itineraries);

                
                 //@TODO RETURN ALL THE AVAILABLE FLIGHTS TO THE RETURN STEP
                 //NON COMBINED OFFERS ARE REMOVED UNTIL WE SOLVE THE ENHANCEDAIRBOOK REQUEST ISSUE
                 
                $priced_itinerariesTemp = $priced_itineraries;

                $priced_itinerariesTemp = array();
                $priced_itinerariesTemp["data"] = $priced_itineraries["data"];
                $priced_itinerariesTemp["inbound"] = array();
                $priced_itinerariesTemp["outbound"] = array();

                $priced_itineraries = $this->getUnionLists($priced_itinerariesTemp, $combinedData);
                //}
            }*/


            //echo '<textarea id="debug_bargain_response_after_union" isOneWay="' . $oneWay . '" style="display: ;" cols="120" rows="20">' . json_encode($priced_itineraries) . '</textarea>';

            $normalize_response = $this->flightItineraryNormaliser->normalize(
                $priced_itineraries, $this->discount, $currencyCode, $this->defaultCurrency, $this->currencyPCC, $oneWay, $fromDate.'T00:00:00', $flexibleDate, $toDate, $multiDestination, $numberInParty, $sabreVariables['salt']
                );

            if (!$normalize_response) {
                $return['no_data']       = true;
                $return['no_filter_css'] = 'no-filter';
            }

            $this->sabreServices->closeSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
            //$this->addFlightLog('Requesting API SessionCloseRQ');
            
            /* oneWay: 0 -- Return
             * oneWay: 1 -- one way / Multidestinations
             */
            //if ($oneWay == 0) {
              //  $return['outbound'] = $normalize_response['outbound']['itineraries'];
              //  $return['inbound']  = $normalize_response['inbound']['itineraries'];
            //} else {
            $return['outbound'] = $normalize_response['itineraries'];
            //}
            $return['airlines']           = $normalize_response['airlines'];
            $return['currency']           = ( ($currency && $currency!="") ? $currency : $this->currencyPCC);
            $return['currency_code']      = $currencyCode ? $currencyCode : $this->defaultCurrency;
            $return['minimum_duration']   = $normalize_response['durations']['minimumDuration'];
            $return['maximum_duration']   = $normalize_response['durations']['maximumDuration'];
            $return['minimum_price']      = $normalize_response['durations']['minimumPrice'];
            $return['maximum_price']      = $normalize_response['durations']['maximumPrice'];
            $return['num_in_party']       = $numberInParty;
            $return['one_way']            = $oneWay;
            $return['multi_destination']  = $multiDestination;
            $return['departure_airports'] = $normalize_response['departure_airports'];

        } else {
            $return['error']['message'] = $create_session_response['message'];
        }
        if ($from_mobile == 1) {
            $return['from_mobile'] = 1;
            return $return;
        } else {
            return array_merge($param, $return);
        }
    }
    
    /**
     * Get the list of departures from the combined list (full details)
     * This set contains the best flight offers from API
     * combinedData: 1 came from combined sets
     * combinedData: 0 came from standalone set
     *
     * @return array
     */
    private function getListFromCombined($itineraries)
    {
        $outbound = $inbound  = $data     = [];
        if(isset($itineraries['data']) && count($itineraries['data']) > 0 )
        {
            foreach ($itineraries['data'] as $itinerary) {
                //add a flag so we know where the list came from: 0 for standalone, 1 for combined data
                $itinerary['combinedData']     = 1;
                $departureFlight               = $itinerary['air_itinerary']['origin_destination_options'][0];
                $returnFlight                  = $itinerary['air_itinerary']['origin_destination_options'][1];
                $itinerary['return_flight']    = [];
                $itinerary['departure_flight'] = [];
                
                foreach ($itinerary['air_itinerary']['origin_destination_options'] as $key => $segment) {
                    if ($key == 0) {
                        //get only departure segment
                        $itinerary['air_itinerary']['origin_destination_options']   = [];
                        $itinerary['air_itinerary']['origin_destination_options'][] = $departureFlight;
                        
                        //this is needed so we can have its reference and comparing for return flights
                        $itinerary['departure_flight'] = [];
                        $itinerary['return_flight'][]  = $returnFlight;
                        $itinerary['flight_type']      = 'outbound';
                        $outbound[]                    = $itinerary;
                    } else {
                        //get only returning segment
                        $itinerary['air_itinerary']['origin_destination_options']   = [];
                        $itinerary['air_itinerary']['origin_destination_options'][] = $returnFlight;
                        //this is needed so we can have its reference and comparing for departure flights
                        $itinerary['departure_flight'][]                            = $departureFlight;
                        $itinerary['return_flight']                                 = [];
                        $itinerary['flight_type']                                   = 'inbound';
                        
                        $inbound[] = $itinerary;
                    }
                }
            }
            
            if (!empty($outbound)) {
                $outbound         = $this->removeItineraryDuplicates($outbound);
                $data['outbound'] = $outbound;
            }
            if (!empty($inbound)) {
                $data['inbound'] = $inbound;
            }
        }
        return $data;
    }
    
    /**
     * Remove departure duplicates from the above list having the same (Airline, Flight number, Date) keeping the record with the lowest price
     *
     * @return array
     */
    public function removeItineraryDuplicates($itineraries)
    {
        $results       = $sets          = [];
        $selectSegment = null;
        $set_index     = 0;
        for ($i = 0; $i < sizeof($itineraries); $i++) {
            
            $value         = $itineraries[$i];
            $selectSegment = $value;
            
            if (isset($value['air_itinerary']['origin_destination_options'])) {
                $segments   = $value['air_itinerary']['origin_destination_options'][0]['flight_segments'];
                $segment    = $segments[0];
                $flightInfo = [
                    'flightNumber' => $segment['operating_airline']['flight_number'],
                    'departureAirport' => $segment['departure']['airport']['location_code'],
                    'departureDateTime' => $segment['departure']['date_time'],
                    'compare' => array()
                ];
                
                $price = $value['amount'];
            } else {
                $segments   = $value['segments'][0];
                $segment    = $segments[0];
                $flightInfo = [
                    'flightNumber' => $segment['flight_number'],
                    'departureAirport' => $segment['departure_airport_code'],
                    'departureDateTime' => $segment['original_departure_date_time'],
                    'compare' => array()
                ];
                
                $price = $value['segmentsGlob']['price'];
            }
            
            foreach ($segments as $segment) {
                $flightInfo['compare'][] = isset($value['air_itinerary']['origin_destination_options']) ? $segment['operating_airline']['flight_number'] : $segment['flight_number'];
                $flightInfo['compare'][] = isset($value['air_itinerary']['origin_destination_options']) ? $segment['departure']['airport']['location_code'] : $segment['departure_airport_code'];
                $flightInfo['compare'][] = isset($value['air_itinerary']['origin_destination_options']) ? $segment['departure']['date_time'] : $segment['original_departure_date_time'];
            }
            
            $flightInfo['compare'] = implode('_', $flightInfo['compare']);
            if (in_array($flightInfo['compare'], $sets)) {
                $selectSegment = null;
                continue;
            }
            
            for ($k = ($i + 1); $k < sizeof($itineraries); $k++) {
                
                $valueCompare = $itineraries[$k];
                
                if (isset($valueCompare['air_itinerary']['origin_destination_options'])) {
                    $segmentsCompare   = $valueCompare['air_itinerary']['origin_destination_options'][0]['flight_segments'];
                    $segmentCompare    = $segmentsCompare[0];
                    $flightInfoCompare = [
                        'flightNumber' => $segmentCompare['operating_airline']['flight_number'],
                        'departureAirport' => $segmentCompare['departure']['airport']['location_code'],
                        'departureDateTime' => $segmentCompare['departure']['date_time'],
                        'compare' => array()
                    ];
                    
                    $priceCompare = $valueCompare['amount'];
                } else {
                    $segmentsCompare = $valueCompare['segments'][0];
                    $segmentCompare  = $segmentsCompare[0];
                    
                    $flightInfoCompare = [
                        'flightNumber' => $segmentCompare['flight_number'],
                        'departureAirport' => $segmentCompare['departure_airport_code'],
                        'departureDateTime' => $segmentCompare['original_departure_date_time'],
                        'compare' => array()
                    ];
                    
                    $priceCompare = $valueCompare['segmentsGlob']['price'];
                }
                
                foreach ($segmentsCompare as $segmentCompare) {
                    $flightInfoCompare['compare'][] = isset($valueCompare['air_itinerary']['origin_destination_options']) ? $segmentCompare['operating_airline']['flight_number'] : $segment['flight_number'];
                    $flightInfoCompare['compare'][] = isset($valueCompare['air_itinerary']['origin_destination_options']) ? $segmentCompare['departure']['airport']['location_code'] : $segment['departure_airport_code'];
                    $flightInfoCompare['compare'][] = isset($valueCompare['air_itinerary']['origin_destination_options']) ? $segmentCompare['departure']['date_time'] : $segment['original_departure_date_time'];
                }
                
                $flightInfoCompare['compare'] = implode('_', $flightInfoCompare['compare']);
                
                if (in_array($flightInfoCompare['compare'], $sets)) continue;
                
                if ($flightInfoCompare['compare'] != "" && $flightInfoCompare['compare'] == $flightInfo['compare']) {
                    if ($priceCompare < $price) $selectSegment = $valueCompare;
                }
            }
            if ($selectSegment != null) {
                $results[] = $selectSegment;
                $sets[]    = $flightInfo['compare'];
            }
        }
        return $results;
    }
    
    /**
     * Sort the list based from the LOWEST price
     * This is not used for now but maybe it will be in the future
     *
     *
     * @return array
     */
    private function sortPriceIteneraryList($itineraries)
    {
        $dataType = ['inbound', 'outbound'];
        foreach ($dataType as $type) {
            $sorted = [];
            foreach ($itineraries[$type]['itineraries'] as $key => $value) {
                $sorted[] = $value['segmentsGlob']['price'];
            }
            array_multisort($sorted, SORT_ASC, $itineraries[$type]['itineraries']);
        }
        
        return $itineraries;
    }
    
    /**
     * Get all the flights records from the standalone departure list
     * Create a new UNION list from the first Extracted one and only the one from the second list which do not exist in the first one
     * We need to add the records from standalone to the matched record from the combined extracted records. So each combined record
     * might have assigned a record from standalone list having the lowest price
     * Now at this stage we have a non duplicate list of departures from the combined list as priority with the additional flights we have from the Standalone Departures
     * The most important is to flag the records if it's from combined or standalone list (for the records from combined list, we should add the related exact record from departure standalone list with the full details)
     *
     * @return array
     */
    private function getUnionLists($allList, $combined)
    {
        $data           = [];
        $data['data']   = $allList['data'];
        //Now at this stage we have a non duplicate list of departures from the combined list as priority
        // with the additional flights we have from the Standalone Departures
        $standAloneData = isset($allList['outbound']) ? $allList['outbound'] : [];
        $combinedData   = isset($combined['outbound']) ? $combined['outbound'] : [];
        if (!empty($standAloneData) && !empty($combinedData)) {
            $data['outbound'] = $this->createUnionLists($standAloneData, $combinedData);
        } elseif (!empty($standAloneData) && empty($combinedData)) {
            $data['outbound'] = $standAloneData;
        } elseif (empty($standAloneData) && !empty($combinedData)) {
            $data['outbound'] = $combinedData;
        } else {
            $data['outbound'] = [];
        }
        
        /* we just need to merge combined and standalone inbound/return
         * adding flag so we know where it came from
         * we need to reprocess it since keys are based from sequece number and combined and standalone keys might be duplicate
         * we need to get all returns from combined and standalone */
        if(isset($allList['inbound']))
        {
            foreach ($allList['inbound'] as $inbound) {
                $inbound['combinedData'] = 0;
                $combined['inbound'][]   = $inbound;
            }
        }
        $data['inbound'] = $combined['inbound'];
        
        return $data;
    }
    
    /**
     * Create a new UNION list from the first Extracted one and only the one from the second list which do not exist in the first one
     * We need to add the records from standalone to the matched record from the combined extracted records. So each combined record
     * might have assigned a record from standalone list having the lowest price
     *
     * @return array
     */
    private function createUnionLists($standAloneData, $combinedData)
    {
        $currencyCode = filter_input(INPUT_COOKIE, 'currency');
        $currencyCode = ($currencyCode == "") ? $this->defaultCurrency : $currencyCode;
        $conversionRate = $this->currencyService->getConversionRate($this->currencyPCC, $currencyCode);
        //
        //
        foreach ($standAloneData as $key => $standAlone) {
            //
            $ttPrice = $standAlone['amount'];
            if($ttPrice >= $this->discount) $ttPrice = $ttPrice - $this->discount;
            //
            $standAlone['tt_price'] = $this->currencyService->currencyConvert($ttPrice, $conversionRate);
            $standAlone['tt_baseFare'] = $this->currencyService->currencyConvert($standAlone['base_fare'], $conversionRate);
            $standAlone['tt_taxes'] = $this->currencyService->currencyConvert($standAlone['taxes'], $conversionRate);
            //
            $standAloneSegments = $standAlone['air_itinerary']['origin_destination_options'][0]['flight_segments'];
            $standAloneSegment  = $standAloneSegments[0];
            $standAloneInfo     = [
                'flightNumber' => $standAloneSegment['operating_airline']['flight_number'],
                'departureAirport' => $standAloneSegment['departure']['airport']['location_code'],
                'departureDateTime' => $standAloneSegment['departure']['date_time'],
                'price' => $standAlone['amount'],
                'baseFare' => $standAlone['base_fare'],
                'taxes' => $standAlone['taxes'],
                'tt_price' => $standAlone['tt_price'],
                'tt_baseFare' => $standAlone['tt_baseFare'],
                'tt_taxes' => $standAlone['tt_taxes'],
                'compare' => ""
            ];
            
            foreach ($standAloneSegments as $standAloneSegment) {
                $standAloneInfo['compare'] .= $standAloneSegment['operating_airline']['flight_number']."_".$standAloneSegment['departure']['airport']['location_code']."_".$standAloneSegment['departure']['date_time'];
            }
            
            $isExists = false;
            foreach ($combinedData as $arrKey => $combined) {
                //
                $ttPrice = $combined['amount'];
                if($ttPrice >= $this->discount) $ttPrice = $ttPrice - $this->discount;
                //
                $combined['tt_price'] = $this->currencyService->currencyConvert($ttPrice, $conversionRate);
                $combined['tt_baseFare'] = $this->currencyService->currencyConvert($combined['base_fare'], $conversionRate);
                $combined['tt_taxes'] = $this->currencyService->currencyConvert($combined['taxes'], $conversionRate);
                //
                $combinedSegments = $combined['air_itinerary']['origin_destination_options'][0]['flight_segments'];
                $combinedSegment  = $combinedSegments[0];
                $combinedInfo     = [
                    'flightNumber' => $combinedSegment['operating_airline']['flight_number'],
                    'departureAirport' => $combinedSegment['departure']['airport']['location_code'],
                    'departureDateTime' => $combinedSegment['departure']['date_time'],
                    'price' => $combined['amount'],
                    'baseFare' => $combined['base_fare'],
                    'taxes' => $combined['taxes'],
                    'tt_price' => $combined['tt_price'],
                    'tt_baseFare' => $combined['tt_baseFare'],
                    'tt_taxes' => $combined['tt_taxes'],
                    'compare' => ""
                ];
                
                foreach ($combinedSegments as $combinedSegment) {
                    $combinedInfo['compare'] .= $combinedSegment['operating_airline']['flight_number']."_".$combinedSegment['departure']['airport']['location_code']."_".$combinedSegment['departure']['date_time'];
                }
                
                if ($standAloneInfo['compare'] == $combinedInfo['compare']) {
                    $isExists = true;
                    
                    //for reference, we need to store related one way.
                    $combined['related_one_way'] = $standAlone;
                    
                    //Listing of Departure flights should take MIN(existing full combined price , assigned departure price) for display
                    // and it should be flagged which price has been taken
                    if (isset($combined['flight_type']) && $combined['flight_type'] == 'outbound') {
                        //we need to check the price which one has the best offer
                        if ($standAloneInfo['price'] < $combinedInfo['price']) {
                            $combined['selected_price']['provider_price']     = $standAloneInfo['price'];
                            $combined['selected_price']['original_price']     = $standAloneInfo['price'];
                            $combined['selected_price']['original_taxes']     = $standAloneInfo['taxes'];
                            $combined['selected_price']['original_base_fare'] = $standAloneInfo['baseFare'];
                            $combined['selected_price']['source']             = "oneway";
                        } else {
                            $combined['selected_price']['provider_price']     = $combinedInfo['price'];
                            $combined['selected_price']['original_price']     = $combinedInfo['price'];
                            $combined['selected_price']['original_taxes']     = $combinedInfo['taxes'];
                            $combined['selected_price']['original_base_fare'] = $combinedInfo['baseFare'];
                            $combined['selected_price']['source']             = "offer";
                        }
                    }
                    
                    //override what's in the current value of combined so list will be updated
                    if (!isset($combinedData[$arrKey]['selected_price']) || $combinedData[$arrKey]['selected_price']['original_price'] > $combined['selected_price']['original_price'])
                        $combinedData[$arrKey] = $combined;
                }
            }
            
            //if it doesnt exists, then add it to the list
            if (!$isExists) {
                //add a flag so we know where the list came from: 0 for standalone, 1 for combined data
                $standAlone['combinedData'] = 0;
                $combinedData[]             = $standAlone;
            }
        }
        
        return $combinedData;
    }
    
    /**
     * Remove duplicates from the list having the same (Airline, Flight number, Date) keeping the record with the lowest price
     * In checking for duplicates we need to compare both departure and return for duplicates
     *
     * @return array
     */
    public function removeDuplicatesFromCombined($priced_itineraries)
    {
        //         echo 'removeDuplicatesFromCombined....<pre>';
        //         var_dump(count($priced_itineraries['data']));
        //         echo '</pre>';
        
        $results       = $data          = $bestPriceData = $sets          = [];
        
        $set_index = 0;
        foreach ($priced_itineraries['data'] as $key => $value) {
            //outbound
            $outboundSegement = $value['air_itinerary']['origin_destination_options'][0]['flight_segments'];
            $outboundCnt      = count($outboundSegement);
            
            $outbound = [
                'departureAirport' => $outboundSegement[0]['departure']['airport']['location_code'],
                'departureDateTime' => $outboundSegement[0]['departure']['date_time'],
                'flightNum' => $outboundSegement[0]['operating_airline']['flight_number'],
                'arrivalAirport' => $outboundSegement[$outboundCnt - 1]['arrival']['airport']['location_code'],
                'arrivalDateTime' => $outboundSegement[$outboundCnt - 1]['arrival']['date_time'],
                'count' => $outboundCnt
            ];
            
            //inbound
            $inboundSegement = $value['air_itinerary']['origin_destination_options'][1]['flight_segments'];
            $inboundCnt      = count($inboundSegement);
            
            $inbound = [
                'departureAirport' => $inboundSegement[0]['departure']['airport']['location_code'],
                'departureDateTime' => $inboundSegement[0]['departure']['date_time'],
                'flightNum' => $inboundSegement[0]['operating_airline']['flight_number'],
                'arrivalAirport' => $inboundSegement[$inboundCnt - 1]['arrival']['airport']['location_code'],
                'arrivalDateTime' => $inboundSegement[$inboundCnt - 1]['arrival']['date_time'],
                'count' => $inboundCnt
            ];
            
            $price = $value['amount'];
            
            $set_key = [
                $outbound['departureAirport'],
                $outbound['departureDateTime'],
                $outbound['flightNum'],
                $outbound['arrivalAirport'],
                $outbound['arrivalDateTime'],
                $inbound['departureAirport'],
                $inbound['departureDateTime'],
                $inbound['flightNum'],
                $inbound['arrivalAirport'],
                $inbound['arrivalDateTime'],
            ];
            $set_key = implode('_', $set_key);
            
            $value['item_key'] = $set_key;
            if (!in_array($set_key, $sets)) {
                $sets[$set_index]          = $set_key;
                $bestPriceData[$set_index] = [
                    'outbound' => $outbound,
                    'inbound' => $inbound,
                    'price' => $price,
                    'data' => $value
                ];
                
                $results[$set_index] = $value;
                $set_index++;
            } else {
                $index = array_search($set_key, $sets);
                if (isset($bestPriceData[$index])) {
                    if ($bestPriceData[$index]['price'] > $price) {
                        $bestPriceData[$index]['price'] = $price;
                        $results[$index]                = $value;
                    }
                }
            }
        }
        
        $data['data'] = $results;
        return $data;
    }
    
    //        foreach ($priced_itineraries['data'] as $key => $value) {
    //            $lowestPrice = [];
    //            if (empty($results)) {
    //                $results[] = $value;
    //            } else {
    //                //outbound
    //                $outboundSegement_1            = $value['air_itinerary']['origin_destination_options'][0]['flight_segments'];
    //                $outboundSeg_cnt_1             = count($outboundSegement_1);
    //                $outbound_departure_airport_1  = $outboundSegement_1[0]['departure']['airport']['location_code'];
    //                $outbound_departure_dateTime_1 = $outboundSegement_1[0]['departure']['date_time'];
    //                $outbound_flightNumber_1       = $outboundSegement_1[0]['operating_airline']['flight_number'];
    //
    //                //we need to consider stops. the final destination is the stop
    //                $outbound_arrival_airport_1  = $outboundSegement_1[$outboundSeg_cnt_1 - 1]['arrival']['airport']['location_code'];
    //                $outbound_arrival_dateTime_1 = $outboundSegement_1[$outboundSeg_cnt_1 - 1]['arrival']['date_time'];
    //
    //                //inbound
    //                $inboundSegement_1            = $value['air_itinerary']['origin_destination_options'][1]['flight_segments'];
    //                $inboundSeg_cnt_1             = count($inboundSegement_1);
    //                $inbound_departure_airport_1  = $inboundSegement_1[0]['departure']['airport']['location_code'];
    //                $inbound_departure_dateTime_1 = $inboundSegement_1[0]['departure']['date_time'];
    //                $inbound_flightNumber_1       = $inboundSegement_1[0]['operating_airline']['flight_number'];
    //
    //                //we need to consider stops. the final destination is the stops
    //                $inbound_arrival_airport_1  = $inboundSegement_1[$inboundSeg_cnt_1 - 1]['arrival']['airport']['location_code'];
    //                $inbound_arrival_dateTime_1 = $inboundSegement_1[$inboundSeg_cnt_1 - 1]['arrival']['date_time'];
    //
    //                $price_1     = $value['amount'];
    //                $isUnique    = true;
    //                $lowestPrice = [];
    //
    //                //then we need to compare (Airline, Flight number, Date) from the existing to check if there's dups
    //
    //                foreach ($results as $arrKey => $resVal) {
    //                    $outboundSegement_2 = $resVal['air_itinerary']['origin_destination_options'][0]['flight_segments'];
    //                    $outboundSeg_cnt_2  = count($outboundSegement_2);
    //
    //                    $outbound_departure_airport_2  = $outboundSegement_2[0]['departure']['airport']['location_code'];
    //                    $outbound_departure_dateTime_2 = $outboundSegement_2[0]['departure']['date_time'];
    //                    $outbound_flightNumber_2       = $outboundSegement_2[0]['operating_airline']['flight_number'];
    //
    //                    //we need to consider stops. the final destination is the stop
    //                    $outbound_arrival_airport_2  = $outboundSegement_2[$outboundSeg_cnt_2 - 1]['arrival']['airport']['location_code'];
    //                    $outbound_arrival_dateTime_2 = $outboundSegement_2[$outboundSeg_cnt_2 - 1]['arrival']['date_time'];
    //
    //                    //inbound
    //                    $inboundSegement_2 = $value['air_itinerary']['origin_destination_options'][1]['flight_segments'];
    //                    $inboundSeg_cnt_2  = count($inboundSegement_2);
    //
    //                    $inbound_departure_airport_2  = $inboundSegement_2[0]['departure']['airport']['location_code'];
    //                    $inbound_departure_dateTime_2 = $inboundSegement_2[0]['departure']['date_time'];
    //                    $inbound_flightNumber_2       = $inboundSegement_2[0]['operating_airline']['flight_number'];
    //
    //                    //we need to consider stops. the final destination is the stops
    //                    $inbound_arrival_airport_2  = $inboundSegement_2[$inboundSeg_cnt_2 - 1]['arrival']['airport']['location_code'];
    //                    $inbound_arrival_dateTime_2 = $inboundSegement_2[$inboundSeg_cnt_2 - 1]['arrival']['date_time'];
    //
    //                    $price_2 = $resVal['amount'];
    //
    //                    if ($inbound_departure_airport_1 == $inbound_departure_airport_2 &&
    //                        $inbound_departure_dateTime_1 == $inbound_departure_dateTime_2 &&
    //                        $inbound_arrival_airport_1 == $inbound_arrival_airport_2 &&
    //                        $inbound_arrival_dateTime_1 == $inbound_arrival_dateTime_2 &&
    //                        $inbound_flightNumber_1 == $inbound_flightNumber_2 &&
    //                        $outbound_departure_airport_1 == $outbound_departure_airport_2 &&
    //                        $outbound_departure_dateTime_1 == $outbound_departure_dateTime_2 &&
    //                        $outbound_arrival_airport_1 == $outbound_arrival_airport_2 &&
    //                        $outbound_arrival_dateTime_1 == $outbound_arrival_dateTime_2 &&
    //                        $outbound_flightNumber_1 == $outbound_flightNumber_2
    //                    ) {
    //                        $isUnique = false;
    //                        // Among all dups, get the cheapest fare for flights, the cheapest is also the one to be displayed
    //                        //Else just retain whatever be in the results array
    //                        if ($price_1 < $price_2) {
    //                            $results[$arrKey] = $value;
    //                        }
    //                    }
    //                }
    //                if ($isUnique) {
    //                    $results[] = $value;
    //                }
    //                else {
    //                    //We need to check if w/c price is lower. this is to assure that we are getting the lowest
    //                    //Else just retain what is stored in the result array OR do nothing. Our results array is good
    ////                    if (!empty($lowestPrice)) {
    ////                        $priceKey              = array_keys($lowestPrice);
    ////                        $results[$priceKey[0]] = $lowestPrice[$priceKey[0]];
    ////                    }
    //                }
    //    }
    //}
    //        echo 'removeDuplicatesFromCombined....<pre>';
    //        var_dump($results);
    //        echo '</pre>';
    //        exit;
    //        $data['data'] = $results;
    //        return $data;
    //}
    /**
     *
     * @return mixed
     */
    function searchAirport($request)
    {
        
        $term = ltrim($term);
        $term = rtrim($term);
        
        $ElasticSearchSC = new ElasticSearchSC();
        if ($term) {
            $ElasticSearchSC->setTerm($term);
        }
        $ElasticSearchSC->setCriteria($criteria);
        $ElasticSearchSC->setUrlSource('searchAirport - airportsSearch');
        $queryStringResult = json_decode($this->get('ElasticServices')->airportsSearch($ElasticSearchSC), true);
        $this->get('ElasticServices')->checkElasticErrorLog($queryStringResult);
        $retDoc            = $queryStringResult;
        $Result            = array();
        
        $ret             = array();
        $res             = array();
        $retarr          = array();
        $aretarr         = array();
        $grouping_arr    = array();
        $grouping_result = array();
        $count           = 0;
        $Result          = $retDoc['hits']['hits'];
        $aggregation     = $retDoc['aggregations']['cityId']['buckets'];
        
        foreach ($aggregation as $agg) {
            if ($agg['doc_count'] > 1) {
                $res[] = $agg['key'];
            }
        }
        
        foreach ($Result as $document) {
            $city_id = $document['_source']['location']['city']['id'];
            if (in_array($city_id, $res)) {
                $city_id                      = $document['_source']['location']['city']['id'];
                $grouping_arr['c'.$city_id][] = $document['_source'];
            } else {
                $retarr['type']         = 'airport';
                $retarr['name']         = $document['_source']['name'];
                $retarr['airport_code'] = $document['_source']['code'];
                $retarr['address']      = $document['_source']['location']['city']['name']." , ".$document['_source']['location']['country']['name'];
                $aretarr[]              = $retarr;
            }
        }
        $retarr2  = array();
        $aretarr2 = array();
        if (sizeof($grouping_arr) > 0) {
            foreach ($grouping_arr as $key => $value) {
                $city_id  = str_replace('c', '', $key);
                $cityInfo = $this->getWorldCitiespopInfo($city_id);
                
                if (!$cityInfo) {
                    continue;
                }
                
                $countrycode   = $cityInfo[0]->getCountryCode();
                $statecode     = $cityInfo[0]->getStateCode();
                $state_array   = $this->getWorldStateInfo($countrycode, $statecode);
                $country_array = $this->getCountryGetInfo($countrycode);
                $cityName      = $this->utils->htmlEntityDecode($cityInfo[0]->getName());
                $stateName     = $state_array[0]->getStateName();
                $countryName   = $this->utils->htmlEntityDecode($country_array->getName());
//                $Groupes       = $this->getAllAirportCityElastic($city_id);
                $Groupes       = $this->get('ElasticServices')->getAllAirportCityElastic($city_id);
                $values        = $Groupes['hits']['hits'];
                
                foreach ($values as $group) {
                    if ($city_id == $group['_source']['location']['city']['id']) {
                        $retarr2[] = array(
                            'name' => $group['_source']['name'],
                            'airport_code' => $group['_source']['code']
                        );
                    }
                }
                
                $aretarr2[] = array('type' => 'city', 'id' => $city_id, 'name' => $cityName." , ".$stateName." , ".$countryName, 'airports' => $retarr2);
                $retarr2    = array();
            }
        }
        $ret = array_merge($aretarr2, $aretarr);
        //}
        
        return $ret;
    }
    
    public function getWorldCitiespopInfo($id)
    {
        $em = $this->entityManager;
        $qb = $em->createQueryBuilder('wc')
        ->select('w,c')
        ->from('TTBundle:Webgeocities', 'w')
        ->innerJoin('TTBundle:CmsCountries', 'c', 'WITH', 'c.code=w.countryCode')
        ->where('w.id = :id')
        ->setParameter(':id', $id);
        
        $query = $qb->getQuery();
        
        return $query->getResult();
    }
    
    public function getWorldStateInfo($country_code, $state_code)
    {
        $em    = $this->entityManager;
        $qb    = $em->createQueryBuilder('ST')
        ->select('s,c')
        ->from('TTBundle:States', 's')
        ->innerJoin('TTBundle:CmsCountries', 'c', 'WITH', 'c.code=s.countryCode')
        ->where('s.countryCode=:CountryCode AND s.stateCode=:StateCode')
        ->setParameter(':CountryCode', $country_code)
        ->setParameter(':StateCode', $state_code);
        $query = $qb->getQuery();
        return $query->getResult();
    }
    
    public function getCountryGetInfo($country_code)
    {
        $em    = $this->entityManager;
        $qb    = $em->createQueryBuilder('CO')
        ->select('s')
        ->from('TTBundle:CmsCountries', 's')
        ->where('s.code=:CountryCode')
        ->setParameter(':CountryCode', $country_code);
        $query = $qb->getQuery();
        $row   = $query->getResult();
        if (sizeof($row) >= 0) {
            return $row[0];
        } else {
            return array();
        }
    }
    
//    public function getAllAirportCityElastic($city)
//    {
//        $ElasticSearchSC   = new ElasticSearchSC();
//        $criteria          = array(
//            'cityId' => $city
//        );
//        $ElasticSearchSC->setCriteria($criteria);
//        $ElasticSearchSC->setUrlSource('getAllAirportCityElastic - airportsSearch');
//        $queryStringResult = json_decode($this->get('ElasticServices')->airportsSearch($ElasticSearchSC), true);
//        $this->checkElasticErrorLog($queryStringResult);
//        $retDoc            = $queryStringResult;
//        if (!$retDoc || empty($retDoc)) {
//            $retDoc = array();
//        }
//        return $retDoc;
//    }
}
