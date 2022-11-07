<?php

namespace FlightBundle\Services;

use mysql_xdevapi\Exception;
use TTBundle\Utils\Utils;
use FlightBundle\vendors\sabre\requests\v1\SabreCreateSessionRequest;
use FlightBundle\vendors\sabre\requests\v1\SabreCreateBargainRequest;
use FlightBundle\vendors\sabre\requests\v1\SabreEnhancedAirBookRequest;
use FlightBundle\vendors\sabre\requests\v1\SabrePassengerDetailsRequest;
use FlightBundle\vendors\sabre\requests\v1\SabreTravelItineraryReadRQ;
use FlightBundle\vendors\sabre\requests\v1\SabreRetrieveItineraryRQ;
use FlightBundle\vendors\sabre\requests\v1\SabreCloseSessionRequest;
use FlightBundle\vendors\sabre\requests\v1\SabreDesignatePrinterRequest;
use FlightBundle\vendors\sabre\requests\v1\SabreAirTicketRequest;
use FlightBundle\vendors\sabre\requests\v1\SabreEndTransactionRequest;
use FlightBundle\vendors\sabre\requests\v1\SabreOTACancelRequest;
use FlightBundle\vendors\sabre\requests\v1\SabreVoidAirTicketRQ;
use FlightBundle\vendors\sabre\requests\v1\SabreRefreshSessionRequest;
use FlightBundle\vendors\sabre\requests\v1\SabreCommandRequest;
use FlightBundle\vendors\sabre\requests\v1\SabrePassengerDetailsActions;
use FlightBundle\vendors\sabre\requests\v1\SabreQueueAccessRQ;
use FlightBundle\vendors\sabre\requests\v1\SabreContextChangeRQ;
use FlightBundle\vendors\sabre\requests\v1\SabreDeletePriceQuote;
use TTBundle\Services\EmailServices;
use FlightBundle\vendors\sabre\PassengerNormaliser;
use Symfony\Component\Security\Core\SecurityContext;
// use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

if (!defined('RESPONSE_SUCCESS'))
    define('RESPONSE_SUCCESS', 0);

if (!defined('RESPONSE_ERROR'))
    define('RESPONSE_ERROR', 1);

class SabreServices
{
    protected $createSessionRequest;

    protected $closeSessionRequest;

    protected $createdesignatePrinterRequest;

    protected $createAirTicketRequest;

    protected $createOTACancelRequest;

    protected $createEndTransactionRequest;

    protected $createBargainRequest;

    protected $createEnhancedAirBookRequest;

    protected $createPassengerDetailsRequest;

    protected $createTravelItineraryRequest;
    protected $createRetrieveItineraryRequest;

    protected $createVoidAirTicketRequest;

    protected $refreshSessionRequest;

    protected $commandRequest;

    protected $passengerDetailsEndTransactionRequest;

    protected $queueAccessRQ;

    protected $contextChangeRQ;

    protected $deletePriceQuoteRQ;

    protected $emailServices;

    protected $utils;

    public $TEST_MODE = true;

    private $URL = '';

    private $container;

    private $production_server = false;



    private $HTTP_AUTH_USER = null;

    private $HTTP_AUTH_PASSWORD = '';

    // private $HTTP_AUTH_URL = '/v2/auth/token';
    private $HTTP_AUTH_METHOD = 'none';
    // none, basic, digest
    private $ADDITIONAL_HEADERS = array(
        "Content-Type" => "text/xml;charset=UTF-8",
        "SOAPAction" => "OTA",
        "Connection" => "Keep-Alive",
        "Accept-Encoding" => "gzip"
    );

    protected $em;

    private $date;

    private $scm_service_host = "192.168.2.161";

    private $scm_service_port = 11111;

    private $scm_status_success = 0;

    private $scm_status_error = 1;

    // private $enableCancelation = false;
    // protected $requestStack;
    // private $enableCancelation = true;
    // private $enableRefundable = true;
    // private $defaultCurrency = "USD";
    // private $currencyPCC = "AED";
    private $connection_type_bfm = 1;

    private $connection_type_booking = 2;

    protected $securityContext;

    // private $enableCancelation = true;
    // private $enableRefundable = true;
    // private $discount = 73.46; // value in AED, equivalent of USD 20
    private $passengerNormaliser;

    public function __construct(Utils $utils, SabreCreateSessionRequest $createSessionRequest, SabreCreateBargainRequest $createBargainRequest, SabreEnhancedAirBookRequest $createEnhancedAirBookRequest, SabrePassengerDetailsRequest $createPassengerDetailsRequest, SabreTravelItineraryReadRQ $createTravelItineraryRequest, SabreCloseSessionRequest $closeSessionRequest, SabreDesignatePrinterRequest $createdesignatePrinterRequest, SabreAirTicketRequest $createAirTicketRequest, SabreEndTransactionRequest $createEndTransactionRequest, SabreOTACancelRequest $createOTACancelRequest, SabreVoidAirTicketRQ $createVoidAirTicketRequest, SabreRefreshSessionRequest $refreshSessionRequest, SabreCommandRequest $commandRequest, SabrePassengerDetailsActions $passengerDetailsEndTransactionRequest, SabreQueueAccessRQ $queueAccessRQ, EntityManager $em, $templating, EmailServices $emailservices, SabreContextChangeRQ $contextChangeRQ, SabreDeletePriceQuote $deletePriceQuoteRQ, ContainerInterface $container, PassengerNormaliser $passengerNormaliser, SecurityContext $securityContext, SabreRetrieveItineraryRQ $createRetrieveItineraryRequest)
    {

        $this->container = $container;
        $this->production_server = ($container->hasParameter('ENVIRONMENT') && $this->container->getParameter('ENVIRONMENT') == 'production');
        if ($this->production_server)
            $this->TEST_MODE = false;
        $this->URL =$container->getParameter('modules')['flights']['vendors']['sabre']['endpoint_url'];// ($this->TEST_MODE) ? $this->HTTP_TEST_URL : $this->HTTP_PROD_URL;
        $this->createSessionRequest = $createSessionRequest;
        $this->closeSessionRequest = $closeSessionRequest;
        $this->createBargainRequest = $createBargainRequest;
        $this->createEnhancedAirBookRequest = $createEnhancedAirBookRequest;
        $this->createPassengerDetailsRequest = $createPassengerDetailsRequest;
        $this->createTravelItineraryRequest = $createTravelItineraryRequest;
        $this->createRetrieveItineraryRequest = $createRetrieveItineraryRequest;
        $this->createdesignatePrinterRequest = $createdesignatePrinterRequest;
        $this->createAirTicketRequest = $createAirTicketRequest;
        $this->createOTACancelRequest = $createOTACancelRequest;
        $this->createEndTransactionRequest = $createEndTransactionRequest;
        $this->createVoidAirTicketRequest = $createVoidAirTicketRequest;
        $this->refreshSessionRequest = $refreshSessionRequest;
        $this->commandRequest = $commandRequest;
        $this->passengerDetailsEndTransactionRequest = $passengerDetailsEndTransactionRequest;
        $this->queueAccessRQ = $queueAccessRQ;
        $this->contextChangeRQ = $contextChangeRQ;
        $this->deletePriceQuoteRQ = $deletePriceQuoteRQ;
        $this->em = $em;
        $this->utils = $utils;
        $this->date = new \DateTime();
        $this->emailServices = $emailservices;
        $this->templating = $templating;
        $this->passengerNormaliser = $passengerNormaliser;
        $this->securityContext = $securityContext;
        // $this->requestStack = $requestStack;
        $this->currencyService = $this->container->get('CurrencyService');
    }

    /**
     *
     * A *description* This Function that handle ERROR responses From Sabre Provider, it parse the XML response, and get the error Message From it.
     *
     * @param string $responseText
     * @return Array
     */
    public function errorRsHandler($responseText)
    {

        /**
         *
         * @var array|null $errorRs is the array that contains the error parsed from XML response, in case of Error with Sabre SOAP API
         */
        $errorRs = array();
        /**
         *
         * @var boolean $found_error is a variable boolean default value is negative, it will be true in case an error is detected or an error response is given from the third party api Sabre
         */
        $found_error = false;

        $soap_env = 'SOAP-ENV';

        if (strpos($responseText, $soap_env, 1) === false) {
            $soap_env = 'soap-env';
        }
        /**
         *
         * @method string xmlString2domXpath(xml $responseText) function called from utiles for fast parsing of an XML, in this case is parsing the response, checking if the response contains an error node.
         */
        $responseText = preg_replace("/ApplicationResults xmlns=\"[^\"]+\"/m", "ApplicationResults", $responseText);

        $xpath = $this->utils->xmlString2domXPath($responseText);


        try {
            $faultCodeEl = $xpath->query("//$soap_env:Envelope/$soap_env:Body/$soap_env:Fault/faultcode");

            if ($faultCodeEl && $faultCodeEl->length) {
                $found_error = true;
                $faultCode = explode(".", $faultCodeEl->item(0)->nodeValue);
                $errorRs['faultcode'] = $faultCode[1];
            }
        } catch (\Exception $e) {
            $errorRs['error_message'] = $e->getMessage();
            $errorRs['trace'] = $e->getTraceAsString();
            $found_error = true;
            $errorRs['faultcode'] = "curlError";
        }


        try {
            $faultString = $xpath->query("//$soap_env:Envelope/$soap_env:Body/$soap_env:Fault/faultstring");

            if ($faultString && $faultString->length) {
                $found_error = true;
                $errorRs['message'] = $faultString->item(0)->nodeValue;
            }
        } catch (\Exception $e) {
            $found_error = true;
            $errorRs['message'] = 'operationTimedOut';
        }

        if (!$found_error) {
            try {

                $appResultsError = $xpath->query("//$soap_env:Envelope/$soap_env:Body/xmlns:EnhancedAirBookRS/xmlns:ApplicationResults/xmlns:Error");

                if ($appResultsError && $appResultsError->length) {
                    $appResultsError = $appResultsError->item(0);
                    $found_error = true;

                    $messageList = $xpath->query("./xmlns:SystemSpecificResults/xmlns:Message", $appResultsError);

                    if ($messageList && $messageList->length) {
                        $message = $messageList->item(0);

                        $errorRs['faultcode'] = $message->getAttribute('code');

                        $errorRs['message'] = $message->nodeValue;

                    }
                }
            } catch (\Exception $e) {

            }
            try {
                $retrieveItineraryError = $xpath->query("//soap-env:Envelope/soap-env:Body/stl19:GetReservationRS/stl19:Errors/stl19:Error");


                if ($retrieveItineraryError && $retrieveItineraryError->length) {
                    $retrieveItineraryError = $retrieveItineraryError->item(0);
                    $found_error = true;
                    $code = $xpath->query("./stl19:Code", $retrieveItineraryError);
                    if ($code && $code->length) {
                        $code = $code->item(0);
                        $errorRs['faultcode'] = $code->nodeValue;
                    }
                    $message = $xpath->query("./stl19:Message", $retrieveItineraryError);
                    if ($message && $message->length) {
                        $message = $message->item(0);
                        $errorRs['message'] = $message->nodeValue;
                    }

                }
            } catch (\Exception $e) {

            }
        }

        if ($found_error)
            $errorRs['status'] = 'error';

        return $errorRs;
    }

    /**
     * *description* this is a function that sends a request to Sabre to create a valid session, for testing we send directly, for production this function take valid session from Connection Pool Manager
     *
     * @param array $sabreVariables
     *            the variables required to create a valid session
     * @param int $user_id
     *            the user_id should be sent to the connection pool manager, in case the user is logged in, else the default is 0
     * @param int $connection_type
     *            connection_type parameter is to differentiate between session for browsing flight availbilty with the number 1, and the sessions for issuing a ticket that have the number 2
     * @param string $source
     *            the source is used by the connection pool manager, to now if the cronJob is using specific session, or normal user on the web
     * @return array this function it returns an array that containes AccessToken, ConversationId, .. . This response is used in other request.
     */
    public function createSabreSessionRequest($sabreVariables, $user_id = 0, $connection_type = 1, $source = 'web')
    {
        $data = array(
            "AccessToken" => "",
            "ConversationId" => "",
            "faultcode" => "",
            "status" => "",
            "message" => "",
            "request" => [],
            'response' => []
        );

        $sabreVariables['on_production_server'] = $this->production_server;

        if ($sabreVariables['on_production_server']) {

            $socket = @fsockopen($this->scm_service_host, $this->scm_service_port);
            if (!$socket) {
                $data['status'] = 'error';

                return $data;
            }

            fwrite($socket, json_encode(array(
                    'command' => 'get_connection',
                    'source' => $source,
                    'arguments' => array(
                        'connection_type' => $connection_type,
                        'user_id' => $user_id
                    )
                )) . "\n");
            $scm_response_string = '';
            while ($tmp_line = fgets($socket)) {
                $scm_response_string .= $tmp_line;
            }

            fclose($socket);

            $scm_response = json_decode($scm_response_string, true);

            if ($scm_response['status'] == $this->scm_status_error) {
                $data['status'] = 'error';

                return $data;
            }

            $data['connection_id'] = $scm_response['response']['id'];
            $data['AccessToken'] = $scm_response['response']['security_token'];
            $data['ConversationId'] = $scm_response['response']['conversation_id'];

            $data["status"] = "success";
            $data["message"] = "Completed";

            return $data;
        } else {


            $sessionRequest = $this->createSessionRequest->createSessionRequest($sabreVariables);

            $getSessionResponse = $this->utils->send_data($this->URL, $sessionRequest, \HTTP_Request2::METHOD_POST, array(
                'auth_method' => $this->HTTP_AUTH_METHOD,
                'username' => $this->HTTP_AUTH_USER,
                'password' => $this->HTTP_AUTH_PASSWORD
            ), $this->ADDITIONAL_HEADERS);

            // k. $getSessionResponse['response_text'] . '</textarea>';
            // echo '<textarea cols="120" rows="20">' . $getSessionResponse['request_body'] . '</textarea>';
            $data['request'] = $getSessionResponse['request_body'];
            $data['response'] = $getSessionResponse['response_text'];

            if ($getSessionResponse['response_error'] == RESPONSE_ERROR) {
                $data["status"] = 'error';
                return $data;
            }

            $errorRs = $this->errorRsHandler($getSessionResponse['response_text']);
            if ($errorRs) {
                $data['faultcode'] = $errorRs['faultcode'];
                $data['status'] = $errorRs['status'];
                $data['message'] = $errorRs['message'];
                return $data;
            }

            $xpath = $this->utils->xmlString2domXPath($getSessionResponse['response_text']);

            $accessTokenData = $xpath->query("//wsse:BinarySecurityToken");

            if ($accessTokenData && $accessTokenData->length) {
                $data['AccessToken'] = $accessTokenData->item(0)->nodeValue;
            }

            $conversationIdData = $xpath->query("//eb:ConversationId");

            if ($conversationIdData && $conversationIdData->length) {
                $data['ConversationId'] = $conversationIdData->item(0)->nodeValue;
            }
            $data["status"] = "success";
            $data["message"] = "Completed";

            return $data;
        }
    }

    /**
     * *description* this function communicated with the connection pool manager on the online version, it notice the connection manager to clean the session ().
     *
     * @param type $sabreVariables
     *            the variables required to create a valid session
     * @param string $source
     *            the source is used by the connection pool manager, to now if the cronJob is using specific session, or normal user on the web
     * @return array if the close is successfully done it will repy a completed message, so this session could be used by other.
     */
    public function cleanSabreSessionRequest($sabreVariables, $source = 'web')
    {
        $data = array(
            "faultcode" => "",
            "status" => "",
            "message" => "",
            "request" => [],
            'response' => []
        );

        $sabreVariables['on_production_server'] = $this->production_server;

        if (isset($sabreVariables['on_production_server']) && $sabreVariables['on_production_server']) {

            $socket = @fsockopen($this->scm_service_host, $this->scm_service_port);
            if (!$socket) {
                $data['status'] = 'error';

                return $data;
            }

            fwrite($socket, json_encode(array(
                    'command' => 'clean_token',
                    'source' => $source,
                    'arguments' => array(
                        'token' => $sabreVariables['access_token']
                    )
                )) . "\n");
            $scm_response_string = '';
            while ($tmp_line = fgets($socket)) {
                $scm_response_string .= $tmp_line;
            }

            fclose($socket);

            $scm_response = json_decode($scm_response_string, true);

            if ($scm_response['status'] == $this->scm_status_error) {
                $data['status'] = 'error';

                return $data;
            }

            $data["status"] = $scm_response['message'];
            $data["message"] = "completed";

            return $data;
        }
    }

    /**
     * *description* this function communicated with the connection pool manager on the online version, it notice the connection manager to release the session so it could be available for others.
     *
     * @param type $sabreVariables
     *            the variables required to create a valid session
     * @param string $source
     *            the source is used by the connection pool manager, to now if the cronJob is using specific session, or normal user on the web
     * @return array if the close is successfully done it will repy a completed message, so this session could be used by other.
     */
    public function closeSabreSessionRequest($sabreVariables, $source = 'web')
    {
        $data = array(
            "faultcode" => "",
            "status" => "",
            "message" => "",
            "request" => [],
            'response' => []
        );

        $sabreVariables['Service'] = "SessionCloseRQ";
        $sabreVariables['Action'] = "SessionCloseRQ";

        $sabreVariables['on_production_server'] = $this->production_server;

        if (isset($sabreVariables['on_production_server']) && $sabreVariables['on_production_server']) {

            $socket = @fsockopen($this->scm_service_host, $this->scm_service_port);
            if (!$socket) {
                $data['status'] = 'error';

                return $data;
            }

            $release_arguments = array(
                'token' => $sabreVariables['access_token']
            );

            if (isset($sabreVariables['lock']))
                $release_arguments['lock'] = true;

            fwrite($socket, json_encode(array(
                    'command' => 'release_token',
                    'source' => $source,
                    'arguments' => $release_arguments
                )) . "\n");
            $scm_response_string = '';
            while ($tmp_line = fgets($socket)) {
                $scm_response_string .= $tmp_line;
            }

            fclose($socket);

            $scm_response = json_decode($scm_response_string, true);

            if ($scm_response['status'] == $this->scm_status_error) {
                $data['status'] = 'error';

                return $data;
            }

            $data["status"] = $scm_response['message'];
            $data["message"] = "completed";

            return $data;
        } else {

            $closeSessionRequest = $this->closeSessionRequest->requestHeader($sabreVariables) . $this->closeSessionRequest->closeSessionRequest($sabreVariables);

            $getSessionCloseResponse = $this->utils->send_data($this->URL, $closeSessionRequest, \HTTP_Request2::METHOD_POST, array(
                'auth_method' => $this->HTTP_AUTH_METHOD,
                'username' => $this->HTTP_AUTH_USER,
                'password' => $this->HTTP_AUTH_PASSWORD
            ), $this->ADDITIONAL_HEADERS);

            // echo '<textarea cols="120" rows="20">' . $getSessionCloseResponse['response_text'] . '</textarea>';
            // echo '<textarea cols="120" rows="20">' . $getSessionCloseResponse['request_body'] . '</textarea>';
            $data['request'] = $getSessionCloseResponse['request_body'];
            $data['response'] = $getSessionCloseResponse['response_text'];

            if ($getSessionCloseResponse['response_error'] == RESPONSE_ERROR) {
                $data["status"] = 'error';
                return $data;
            }

            $errorRs = $this->errorRsHandler($getSessionCloseResponse['response_text']);
            if ($errorRs) {
                $data['faultcode'] = $errorRs['faultcode'];
                $data['status'] = $errorRs['status'];
                $data['message'] = $errorRs['message'];
                return $data;
            }

            $xpath = $this->utils->xmlString2domXPath($getSessionCloseResponse['response_text']);
            $closeSession = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:SessionCloseRS");
            $appResult = $closeSession->item(0)->getAttribute('status');

            $data["status"] = $appResult;
            $data["message"] = "completed";
            return $data;
        }
    }

    /**
     * This method will parse the Node <PricedItinerary> and its elements inside
     *
     * @param xmlstring $xpathUnzipped
     * @param node <PricedItinerary>
     *
     * @return array of itineraries
     */
    protected function getPricedItinerary($xpathUnzipped, $pricedItineraries)
    {

        $priced_itineraries = [];


        if ($pricedItineraries && $pricedItineraries->length) {
            // echo "<br/><br/>found " . $pricedItineraries->length . " PricedItinerary elements<br/>";
            // <PricedItinerary>
            foreach ($pricedItineraries as $pricedItinerary) {
                $sequence_number = $pricedItinerary->getAttribute('SequenceNumber');

                $priced_itineraries[$sequence_number]['sequence_number'] = $sequence_number;
                $priced_itineraries[$sequence_number]['air_itinerary'] = array();

                $trip_type = $xpathUnzipped->query('./xmlns:AirItinerary/@DirectionInd', $pricedItinerary);
                if ($trip_type && $trip_type->length) {
                    $trip_type = $trip_type->item(0);
                    $priced_itineraries[$sequence_number]['air_itinerary']['trip_type'] = $trip_type->nodeValue;
                }
                // <OriginDestinationOptions>
                $originDestinationOptions = $xpathUnzipped->query('./xmlns:AirItinerary/xmlns:OriginDestinationOptions/xmlns:OriginDestinationOption', $pricedItinerary);
                if ($originDestinationOptions && $originDestinationOptions->length) {
                    $fi_index = 0;

                    // <OriginDestinationOption>
                    $n_options = $originDestinationOptions->length;

                    for ($od_index = 0; $od_index < $n_options; $od_index++) {
                        $originDestinationOption = $originDestinationOptions->item($od_index);

                        $priced_itineraries[$sequence_number]['air_itinerary']['origin_destination_options'][$od_index] = array(
                            'origin_destination_index' => $od_index
                        );
                        $origin_destination_option = &$priced_itineraries[$sequence_number]['air_itinerary']['origin_destination_options'][$od_index];

                        if ($originDestinationOption->hasAttribute('ElapsedTime')) {
                            $origin_destination_option['flight_duration'] = $originDestinationOption->getAttribute('ElapsedTime');
                        }

                        // <FlightSegments>
                        $flightSegments = $xpathUnzipped->query('./xmlns:FlightSegment', $originDestinationOption);

                        if ($flightSegments && $flightSegments->length) {
                            $n_flight_segments = $flightSegments->length;

                            for ($fs_index = 0; $fs_index < $n_flight_segments; $fs_index++) {
                                $flightSegment = $flightSegments->item($fs_index);

                                $origin_destination_option['flight_segments'][$fs_index]['flight_segment_index'] = $fs_index;
                                $flight_segment = &$origin_destination_option['flight_segments'][$fs_index];

                                foreach ($flightSegment->attributes as $flightSegmentAttribute) {

                                    $normalized_node_name = $this->utils->normalize_node_name($flightSegmentAttribute->name);
                                    $normalized_node_name_parts = explode('_', $normalized_node_name, 2);

                                    if ($normalized_node_name_parts[0] == 'arrival' || $normalized_node_name_parts[0] == 'departure') {
                                        $flight_segment[$normalized_node_name_parts[0]][$normalized_node_name_parts[1]] = $flightSegmentAttribute->value;
                                    } else {
                                        $flight_segment[$normalized_node_name] = $flightSegmentAttribute->value;
                                    }

                                    if ($pricedItinerary->getAttribute('MultipleTickets')) {

                                        // $flight_segment['res_book_desig_code'] = 'MultiTicket';
                                        $tickets = $xpathUnzipped->query('./xmlns:AirItineraryPricingInfo/xmlns:Tickets/xmlns:Ticket', $pricedItinerary);
                                        $n_ticket_number = $tickets->length;

                                        for ($ticket_index = 0; $ticket_index < $n_ticket_number; $ticket_index++) {

                                            $resBookingParse = $xpathUnzipped->query('./xmlns:AirItineraryPricingInfo/xmlns:Tickets/xmlns:Ticket/xmlns:AirItineraryPricingInfo/xmlns:PTC_FareBreakdowns/xmlns:PTC_FareBreakdown', $pricedItinerary);
                                            if ($resBookingParse && $resBookingParse->length) {

                                                foreach ($resBookingParse as $pi_index => $fareBreakdown) {
                                                    $resBookDesignCode = $xpathUnzipped->query('./xmlns:FareBasisCodes/xmlns:FareBasisCode', $fareBreakdown);
                                                    $flight_segment['res_book_desig_code'] = $resBookDesignCode->item(0)->getAttribute('BookingCode');
                                                }
                                            }
                                        }
                                    }

                                    $farebasiscode = $xpathUnzipped->query('./xmlns:AirItineraryPricingInfo/xmlns:PTC_FareBreakdowns/xmlns:PTC_FareBreakdown/xmlns:FareBasisCodes/xmlns:FareBasisCode', $pricedItinerary);

                                    if (!$farebasiscode->length) {
                                        $farebasiscode = $xpathUnzipped->query('./xmlns:TPA_Extensions/xmlns:AdditionalFares/xmlns:AirItineraryPricingInfo/xmlns:PTC_FareBreakdowns/xmlns:PTC_FareBreakdown/xmlns:FareBasisCodes/xmlns:FareBasisCode', $pricedItinerary);
                                    }
                                    if ($farebasiscode && $farebasiscode->length) {
                                        $flight_segment['fare_basis_code'] = $farebasiscode->item($fs_index)->nodeValue;
                                    }

                                }

                                foreach ($flightSegment->childNodes as $fsInfo) {
                                    $normalized_node_name = $this->utils->normalize_node_name($fsInfo->localName);
                                    $normalized_node_name_parts = explode('_', $normalized_node_name, 2);

                                    if ($normalized_node_name_parts[0] == 'arrival' || $normalized_node_name_parts[0] == 'departure') {
                                        $flight_segment[$normalized_node_name_parts[0]][$normalized_node_name_parts[1]] = array();

                                        $this->utils->fetch_node_info($fsInfo, $flight_segment[$normalized_node_name_parts[0]][$normalized_node_name_parts[1]]);
                                    } else {
                                        $flight_segment[$normalized_node_name] = array();

                                        $this->utils->fetch_node_info($fsInfo, $flight_segment[$normalized_node_name]);
                                    }
                                }

                                $priced_itineraries[$sequence_number]['fs'][$fi_index++] = &$origin_destination_option['flight_segments'][$fs_index];
                            }
                            // </FlightSegment>
                        }
                        // </FlightSegments>
                    } // </OriginDestinationOption>
                }

                // </OriginDestinationOptions>
                // <AirItineraryPricingInfo>
                if ($pricedItinerary->getAttribute('MultipleTickets')) {
                    $hanna = $xpathUnzipped->query('./xmlns:AirItineraryPricingInfo/xmlns:Tickets/xmlns:Ticket', $pricedItinerary);
                    $n_ticket_number = $hanna->length;

                    $totalFare = $xpathUnzipped->query('./xmlns:AirItineraryPricingInfo/xmlns:ItinTotalFare/xmlns:TotalFare', $pricedItinerary);
                    if ($totalFare && $totalFare->length) {
                        $this->utils->fetch_node_info($totalFare->item(0), $priced_itineraries[$sequence_number]);
                    }
                    $baseFare = $xpathUnzipped->query('./xmlns:AirItineraryPricingInfo/xmlns:Tickets/xmlns:Ticket/xmlns:AirItineraryPricingInfo/xmlns:ItinTotalFare/xmlns:EquivFare', $pricedItinerary);
                    if ($baseFare && $baseFare->length) {
                        $priced_itineraries[$sequence_number]['base_fare'] = $baseFare->item(0)->getAttribute('Amount');
                    }
                    // </BaseFare> EquivFare
                    // <Taxes>
                    $taxes = $xpathUnzipped->query('./xmlns:AirItineraryPricingInfo/xmlns:Tickets/xmlns:Ticket/xmlns:AirItineraryPricingInfo/xmlns:ItinTotalFare/xmlns:Taxes/xmlns:Tax', $pricedItinerary);
                    if ($taxes && $taxes->length) {
                        $priced_itineraries[$sequence_number]['taxes'] = $taxes->item(0)->getAttribute('Amount');
                    }

                    for ($fs_index = 0; $fs_index < $n_ticket_number; $fs_index++) {
                        $fareBreakdownList = $xpathUnzipped->query('./xmlns:AirItineraryPricingInfo/xmlns:Tickets/xmlns:Ticket/xmlns:AirItineraryPricingInfo/xmlns:PTC_FareBreakdowns/xmlns:PTC_FareBreakdown', $pricedItinerary);
                        if ($fareBreakdownList && $fareBreakdownList->length) {
                            foreach ($fareBreakdownList as $pi_index => $fareBreakdown) {
                                $this->utils->fetch_node_info($fareBreakdown->firstChild, $priced_itineraries[$sequence_number]['passenger_info'][$pi_index]);
                                $passengerType = $priced_itineraries[$sequence_number]['passenger_info'][$pi_index]['code'];

                                // start get penalties info
                                $penaltiesInfo = $xpathUnzipped->query('./xmlns:PassengerFare/xmlns:PenaltiesInfo', $fareBreakdown);
                                if ($penaltiesInfo && $penaltiesInfo->length) {
                                    $penaltyList = $xpathUnzipped->query('./xmlns:Penalty', $penaltiesInfo->item(0));

                                    if ($penaltyList && $penaltyList->length) {
                                        foreach ($penaltyList as $penalty) {
                                            $penalty_specs = array();
                                            foreach ($penalty->attributes as $penaltyAttribute) {
                                                if (in_array($penaltyAttribute->localName, array('Type', 'Applicability')))
                                                    continue;

                                                $penalty_specs[$penaltyAttribute->localName] = $penaltyAttribute->nodeValue;
                                            }

                                            $priced_itineraries[$sequence_number]['penaltiesInfo'][$passengerType][$penalty->getAttribute('Type')][$penalty->getAttribute('Applicability')] = $penalty_specs;
                                        }
                                    }
                                }
                                // end get penalties info

                                $fareCalcLine = $xpathUnzipped->query('./xmlns:TPA_Extensions/xmlns:FareCalcLine', $fareBreakdown);
                                $priced_itineraries[$sequence_number]['passenger_info'][$pi_index]['fare_calculation_line'] = $fareCalcLine->item(0)->getAttribute('Info');

                                $baggageInfoList = $xpathUnzipped->query('./xmlns:PassengerFare/xmlns:TPA_Extensions/xmlns:BaggageInformationList/xmlns:BaggageInformation', $fareBreakdown);

                                if ($baggageInfoList && $baggageInfoList->length) {
                                    foreach ($baggageInfoList as $bi_index => $baggageInfo) {
                                        $this->utils->fetch_node_info($baggageInfo->lastChild, $priced_itineraries[$sequence_number]['passenger_info'][$pi_index]['baggage_info'][$bi_index]);

                                    }
                                }
                            }

                            // </AirItinerary PricingInfo>
                            // <BaseFare> EquivFare
                            // </Taxes>
                            // <TotalFare>

                            // </TotalFare>
                            // <Endorsements>
                            $nonrefundable = $xpathUnzipped->query('./xmlns:AirItineraryPricingInfo/xmlns:Tickets/xmlns:Ticket/xmlns:AirItineraryPricingInfo/xmlns:PTC_FareBreakdowns/xmlns:PTC_FareBreakdown/xmlns:Endorsements', $pricedItinerary);
                            if ($nonrefundable && $nonrefundable->length) {
                                $priced_itineraries[$sequence_number]['non_refundable'] = $nonrefundable->item(0)->getAttribute('NonRefundableIndicator');
                            }
                            // </Endorsements>
                            // <SeatsRemaining>
                            // './xmlns:AirItineraryPricingInfo/xmlns:PTC_FareBreakdowns/xmlns:PTC_FareBreakdown/xmlns:FareInfos/xmlns:FareInfo/xmlns:TPA_Extensions/xmlns:SeatsRemaining'
                            $seatsRemaining = $xpathUnzipped->query('./xmlns:AirItineraryPricingInfo/xmlns:Tickets/xmlns:Ticket/xmlns:AirItineraryPricingInfo/xmlns:FareInfos/xmlns:FareInfo/xmlns:TPA_Extensions/xmlns:SeatsRemaining', $pricedItinerary);
                            if ($seatsRemaining && $seatsRemaining->length) {
                                $priced_itineraries[$sequence_number]['seats_remaining'] = $seatsRemaining->item(0)->getAttribute('Number');
                            }
                            // </SeatsRemaining>
                            // <Cabin>
                            $fareInfos = $xpathUnzipped->query('./xmlns:AirItineraryPricingInfo/xmlns:Tickets/xmlns:Ticket/xmlns:AirItineraryPricingInfo/xmlns:FareInfos/xmlns:FareInfo', $pricedItinerary);
                            if ($fareInfos && $fareInfos->length) {
                                foreach ($fareInfos as $fInof_index => $fareInfo) {
                                    $seatsRemaining = $xpathUnzipped->query('./xmlns:TPA_Extensions/xmlns:SeatsRemaining', $fareInfo);
                                    $priced_itineraries[$sequence_number]['fare_info'][$fInof_index]['seats_remaining'] = $seatsRemaining->item(0)->getAttribute('Number');

                                    $cabin = $xpathUnzipped->query('./xmlns:TPA_Extensions/xmlns:Cabin', $fareInfo);
                                    $priced_itineraries[$sequence_number]['fare_info'][$fInof_index]['cabin'] = $cabin->item(0)->getAttribute('Cabin');
                                }
                            }
                        }
                    }
                } else {

                    $fareBreakdownList = $xpathUnzipped->query('./xmlns:AirItineraryPricingInfo/xmlns:PTC_FareBreakdowns/xmlns:PTC_FareBreakdown', $pricedItinerary);

                    if (!$fareBreakdownList->length) {
                        $fareBreakdownList = $xpathUnzipped->query('./xmlns:TPA_Extensions/xmlns:AdditionalFares/xmlns:AirItineraryPricingInfo/xmlns:PTC_FareBreakdowns/xmlns:PTC_FareBreakdown', $pricedItinerary);
                    }
                    if ($fareBreakdownList && $fareBreakdownList->length) {

                        foreach ($fareBreakdownList as $pi_index => $fareBreakdown) {

                            $this->utils->fetch_node_info($fareBreakdown->firstChild, $priced_itineraries[$sequence_number]['passenger_info'][$pi_index]);
                            $passengerType = $priced_itineraries[$sequence_number]['passenger_info'][$pi_index]['code'];

                            // start get penalties info
                            $penaltiesInfo = $xpathUnzipped->query('./xmlns:PassengerFare/xmlns:PenaltiesInfo', $fareBreakdown);

                            if ($penaltiesInfo && $penaltiesInfo->length) {
                                $penaltyList = $xpathUnzipped->query('./xmlns:Penalty', $penaltiesInfo->item(0));

                                if ($penaltyList && $penaltyList->length) {
                                    foreach ($penaltyList as $penalty) {
                                        $penalty_specs = array();

                                        foreach ($penalty->attributes as $penaltyAttribute) {
                                            if (in_array($penaltyAttribute->localName, array('Type', 'Applicability')))
                                                continue;

                                            $penalty_specs[$penaltyAttribute->localName] = $penaltyAttribute->nodeValue;
                                        }

                                        $priced_itineraries[$sequence_number]['penaltiesInfo'][$passengerType][$penalty->getAttribute('Type')][$penalty->getAttribute('Applicability')] = $penalty_specs;
                                    }
                                }
                            }
                            // end get penalties info

                            $fareCalcLine = $xpathUnzipped->query('./xmlns:TPA_Extensions/xmlns:FareCalcLine', $fareBreakdown);

                            if ($fareCalcLine->item(0) == null) continue;

                            $priced_itineraries[$sequence_number]['passenger_info'][$pi_index]['fare_calculation_line'] = $fareCalcLine->item(0)->getAttribute('Info');

                            $baggageInfoList = $xpathUnzipped->query('./xmlns:PassengerFare/xmlns:TPA_Extensions/xmlns:BaggageInformationList/xmlns:BaggageInformation', $fareBreakdown);


                            if ($baggageInfoList && $baggageInfoList->length) {
                                foreach ($baggageInfoList as $bi_index => $baggageInfo) {
                                    $this->utils->fetch_node_info($baggageInfo->lastChild, $priced_itineraries[$sequence_number]['passenger_info'][$pi_index]['baggage_info'][$bi_index]);
                                }
                            }
                        }
                    }
                    // </AirItinerary PricingInfo>
                    // <BaseFare> EquivFare
                    $baseFare = $xpathUnzipped->query('./xmlns:AirItineraryPricingInfo/xmlns:ItinTotalFare/xmlns:EquivFare', $pricedItinerary);
                    if (!$baseFare->length) {
                        $baseFare = $xpathUnzipped->query('./xmlns:TPA_Extensions/xmlns:AdditionalFares/xmlns:AirItineraryPricingInfo/xmlns:ItinTotalFare/xmlns:EquivFare', $pricedItinerary);
                    }
                    if ($baseFare && $baseFare->length) {
                        $priced_itineraries[$sequence_number]['base_fare'] = $baseFare->item(0)->getAttribute('Amount');
                    }
                    // </BaseFare> EquivFare
                    // <Taxes>
                    $taxes = $xpathUnzipped->query('./xmlns:AirItineraryPricingInfo/xmlns:ItinTotalFare/xmlns:Taxes/xmlns:Tax', $pricedItinerary);
                    if (!$taxes->length) {
                        $taxes = $xpathUnzipped->query('./xmlns:TPA_Extensions/xmlns:AdditionalFares/xmlns:AirItineraryPricingInfo/xmlns:ItinTotalFare/xmlns:Taxes/xmlns:Tax', $pricedItinerary);
                    }
                    if ($taxes && $taxes->length) {
                        $priced_itineraries[$sequence_number]['taxes'] = $taxes->item(0)->getAttribute('Amount');
                    }
                    // </Taxes>
                    // <TotalFare>
                    $totalFare = $xpathUnzipped->query('./xmlns:AirItineraryPricingInfo/xmlns:ItinTotalFare/xmlns:TotalFare', $pricedItinerary);
                    if (!$totalFare->length) {
                        $totalFare = $xpathUnzipped->query('./xmlns:TPA_Extensions/xmlns:AdditionalFares/xmlns:AirItineraryPricingInfo/xmlns:ItinTotalFare/xmlns:TotalFare', $pricedItinerary);
                    }
                    if ($totalFare && $totalFare->length) {
                        $this->utils->fetch_node_info($totalFare->item(0), $priced_itineraries[$sequence_number]);
                    }
                    // </TotalFare>
                    // <Endorsements>
                    $nonrefundable = $xpathUnzipped->query('./xmlns:AirItineraryPricingInfo/xmlns:PTC_FareBreakdowns/xmlns:PTC_FareBreakdown/xmlns:Endorsements', $pricedItinerary);
                    if (!$nonrefundable->length) {
                        $nonrefundable = $xpathUnzipped->query('./xmlns:TPA_Extensions/xmlns:AdditionalFares/xmlns:AirItineraryPricingInfo/xmlns:PTC_FareBreakdowns/xmlns:PTC_FareBreakdown/xmlns:Endorsements', $pricedItinerary);
                    }
                    if ($nonrefundable && $nonrefundable->length) {
                        $priced_itineraries[$sequence_number]['non_refundable'] = $nonrefundable->item(0)->getAttribute('NonRefundableIndicator');
                    }
                    // </Endorsements>
                    // <SeatsRemaining>
                    // './xmlns:AirItineraryPricingInfo/xmlns:PTC_FareBreakdowns/xmlns:PTC_FareBreakdown/xmlns:FareInfos/xmlns:FareInfo/xmlns:TPA_Extensions/xmlns:SeatsRemaining'
                    $seatsRemaining = $xpathUnzipped->query('./xmlns:AirItineraryPricingInfo/xmlns:FareInfos/xmlns:FareInfo/xmlns:TPA_Extensions/xmlns:SeatsRemaining', $pricedItinerary);
                    if (!$seatsRemaining->length) {
                        $seatsRemaining = $xpathUnzipped->query('./xmlns:TPA_Extensions/xmlns:AdditionalFares/xmlns:AirItineraryPricingInfo/xmlns:FareInfos/xmlns:FareInfo/xmlns:TPA_Extensions/xmlns:SeatsRemaining', $pricedItinerary);
                    }
                    if ($seatsRemaining && $seatsRemaining->length) {
                        $priced_itineraries[$sequence_number]['seats_remaining'] = $seatsRemaining->item(0)->getAttribute('Number');
                    }
                    // </SeatsRemaining>
                    // <Cabin>
                    $fareInfos = $xpathUnzipped->query('./xmlns:AirItineraryPricingInfo/xmlns:FareInfos/xmlns:FareInfo', $pricedItinerary);
                    if (!$fareInfos->length) {
                        $fareInfos = $xpathUnzipped->query('./xmlns:TPA_Extensions/xmlns:AdditionalFares/xmlns:AirItineraryPricingInfo/xmlns:FareInfos/xmlns:FareInfo', $pricedItinerary);
                    }
                    if ($fareInfos && $fareInfos->length) {
                        foreach ($fareInfos as $fInof_index => $fareInfo) {
                            $seatsRemaining = $xpathUnzipped->query('./xmlns:TPA_Extensions/xmlns:SeatsRemaining', $fareInfo);
                            $priced_itineraries[$sequence_number]['fare_info'][$fInof_index]['seats_remaining'] = $seatsRemaining->item(0)->getAttribute('Number');

                            $cabin = $xpathUnzipped->query('./xmlns:TPA_Extensions/xmlns:Cabin', $fareInfo);
                            $priced_itineraries[$sequence_number]['fare_info'][$fInof_index]['cabin'] = $cabin->item(0)->getAttribute('Cabin');
                        }
                    }
                }
                // </SeatsRemaining>

                unset($priced_itineraries[$sequence_number]['fs']);
            }
            // </PricedItinerary>
        } // </PricedItineraries>

        return $priced_itineraries;
    }

    /**
     * this function is called to make the Bargian Request, responsible to get search result of available flights
     *
     * @param array $sabreVariables
     *            sabre variable contains, a valid session after calling createSession, and the apiname that should be called in the request Header
     * @param type $debug
     *            a variable for debuggin to get the result in an array called $data['debug']
     * @return array array that contains all available flights with their info, like price, refundable status, number of stops, date and time, airports, airline, and other mandatory information
     */
    public function createBargainRequest($sabreVariables, $debug = false)
    {
        $bargainRequest = $this->createBargainRequest->requestHeader($sabreVariables) . $this->createBargainRequest->createBargainRequest($sabreVariables);

        $getBargainResponse = $this->utils->send_data($this->URL, $bargainRequest, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS, array(
            "protocol_version" => "1.1",
            "connect_timeout" => 0,
            "timeout" => 0
        ));

        $data = array(
            "data" => "",
            "faultcode" => "",
            "status" => "success",
            "message" => "",
            "messages" => '',
            "request" => $getBargainResponse['request_body'],
            "response" => $getBargainResponse['response_text']
        );

        if ($debug)
            $data['debug'] = $getBargainResponse;

        $userId = 0;

        $userLogged = $this->securityContext->getToken()->getUser();
        if (is_object($userLogged)) {
            $userId = $userLogged->getId();
        }

        $this->utils->addFlightLog("BFM request:: " . $getBargainResponse['request_body'], array('userId' => $userId));

        if ($getBargainResponse['response_error'] == RESPONSE_ERROR) {
            $data['status'] = 'error';

            $this->utils->addFlightLog("BFM response status:: HTTP " . $getBargainResponse['response_status'], array('userId' => $userId));

            return $data;
        }

        $xpathUnzipped = null;

        $errorRs = $this->errorRsHandler($getBargainResponse['response_text']);

        if ($errorRs) {
            $data['faultcode'] = $errorRs['faultcode'];
            $data['status'] = $errorRs['status'];
            $data['message'] = $errorRs['message'];
            // return $data;
        } else {
            $xpath = $this->utils->xmlString2domXPath($getBargainResponse['response_text']);

            $encodedData = $xpath->query("/SOAP-ENV:Envelope/SOAP-ENV:Body/xmlns:CompressedResponse");
            $decodedData = base64_decode($encodedData->item(0)->nodeValue, true);
            $unzippedData = gzdecode($decodedData);

//echo '<textarea id="debug_flight_bargain_request" cols="120" rows="20" style="display: none;">' . $getBargainResponse['request_body'] . '</textarea>';
//echo '<textarea id="debug_flight_bargain_response_unzippeddata" cols="120" rows="20" style="display: none;">' . $unzippedData . '</textarea>';
// echo '<textarea id="debug_flight_bargain_response_text" cols="120" rows="20" style="display: none;">' . $getBargainResponse['response_text'] . '</textarea>';


            $xpathUnzipped = $this->utils->xmlString2domXPath($unzippedData);


            $checkResponse = $xpathUnzipped->query("//xmlns:OTA_AirLowFareSearchRS");

            $appResult = $checkResponse->item(0)->firstChild;

            $data["status"] = strtolower($appResult->localName);

            if ($data["status"] == 'errors') {
                foreach ($appResult->childNodes as $error) {
                    $data['messages'][] = $error->getAttribute('ShortText');
                }
                // return $data;
            }
        }

        $this->utils->addFlightLog("BFM response status:: " . $data["status"], array('userId' => $userId));
        $this->utils->addFlightLog("BFM response:: " . $getBargainResponse['response_text'], array('userId' => $userId));

        if ($data['status'] != 'success')
            return $data;
        $pricedItineraries = $xpathUnzipped->query("//xmlns:OTA_AirLowFareSearchRS/xmlns:PricedItineraries/xmlns:PricedItinerary");
        $priced_itineraries = $this->getPricedItinerary($xpathUnzipped, $pricedItineraries);

        $data["status"] = "success";
        $data["message"] = "completed";
        $data["data"] = $priced_itineraries;
        $data["trip_type"] = $sabreVariables['TripType'];

        # Grouped Itineraries to outbound and inbound flights
        # This applies to a Two-Way Flights only
        # This logic will add elements outbound and inbound to the array results
        # RPH = 1 (outbound), RPH = 2 (inbound)
        /*if ($data["trip_type"] == "Return"){
            $oneWayItineraries = $xpathUnzipped->query("//xmlns:OTA_AirLowFareSearchRS/xmlns:OneWayItineraries/xmlns:SimpleOneWayItineraries");

            if ($oneWayItineraries && $oneWayItineraries->length) {
                foreach ($oneWayItineraries as $oneWayItinerary) {

                    $pricedItineraries = $xpathUnzipped->query('./xmlns:PricedItinerary', $oneWayItinerary);
                    $rph = $oneWayItinerary->getAttribute('RPH');
                    $priced_itineraries = $this->getPricedItinerary($xpathUnzipped, $pricedItineraries);

                    if ($rph == 1){
                        $data["outbound"] = $priced_itineraries;
                    }

                    if ($rph == 2){
                        $data["inbound"] = $priced_itineraries;
                    }
                }
            }
        }*/

        return $data;
    }

    /**
     * this function call the api enhanced to hold seats for users who press's book button after choosing one flight from the result of createBargainRequest
     *
     * @param array $sabreVariables
     *            sabre variable contains, a valid session after calling createSession, and the apiname that should be called in the request Header
     * @return array return's array that contains status that's success in case of success, and the totalAmount with the currency Code
     */
    public function createEnhancedAirBookRequest($sabreVariables)
    {

        $bookingRequest = $this->createEnhancedAirBookRequest->requestHeader($sabreVariables) . $this->createEnhancedAirBookRequest->enhancedAirBookRequest($sabreVariables);

        $connection_config = array(
            'connection_timeout' => 30,
            'timeout' => 60
        );

        $getBookingResponse = $this->utils->send_data($this->URL, $bookingRequest, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS, $connection_config);
/*dump($getBookingResponse);exit;*/
        $userId = 0;
        $userLogged = $this->securityContext->getToken()->getUser();
        if (is_object($userLogged)) {
            $userId = $userLogged->getId();
        }

        $this->utils->addFlightLog("EnhancedAirBook request:: " . $getBookingResponse['request_body'], array('userId' => $userId));
        $this->utils->addFlightLog("EnhancedAirBook response:: " . $getBookingResponse['response_text'], array('userId' => $userId));

        $data = array(
            "amount" => "",
            "currency" => "",
            "faultcode" => "",
            "status" => "",
            "message" => "",
            "messages" => '',
            "request" => $getBookingResponse['request_body'],
            "response" => $getBookingResponse['response_text']
        );
//
// echo '<textarea id="debug_flight_enhanceairbook_response" style="display: ;" cols="120" rows="20">' . $getBookingResponse['response_text'] . '</textarea>';
// echo '<textarea id="debug_flight_enhanceairbook_request_1" style="display: ;" cols="120" rows="20">' . $bookingRequest . '</textarea>';
// echo '<textarea id="debug_flight_enhanceairbook_request" style="display: ;" cols="120" rows="20">' . $getBookingResponse['request_body'] . '</textarea>';

        $errorRs = $this->errorRsHandler($getBookingResponse['response_text']);

        if ($errorRs) {

            $data['faultcode'] = $errorRs['faultcode'];
            $data['status'] = $errorRs['status'];
            $data['message'] = $errorRs['message'];
            return $data;
        }

        if ($getBookingResponse['response_error'] == RESPONSE_ERROR) {

            $data["status"] = 'error';
            return $data;
        }



        $xpath = $this->utils->xmlString2domXPath($getBookingResponse['response_text']);
        $checkResponse = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:EnhancedAirBookRS");
        $appResult = $checkResponse->item(0)->firstChild->firstChild->localName;
        $data["status"] = strtolower($appResult);

        if ($data["status"] == "error")
                  return $data;

        $otaAirPriceRS = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:EnhancedAirBookRS/xmlns:OTA_AirPriceRS");

        if ($otaAirPriceRS && $otaAirPriceRS->length) {
            $otaAirPriceRS = $otaAirPriceRS->item(0);

            //   $priceComparison = $xpath->query("./xmlns:PriceComparison", $otaAirPriceRS);
            //   if ($priceComparison && $priceComparison->length)
            //   {
            //       $data['amountReturned'] = $amountReturned = $priceComparison->item(0)->getAttribute('AmountReturned');
            //       $data['amountSpecified'] = $amountSpecified = $priceComparison->item(0)->getAttribute('AmountSpecified');
            //   }

            // $data['amountReturned'] = $amountReturned;
            // $data['amountSpecified'] = $amountSpecified;

            // if ((isset($sabreVariables['price_percent_margin']) && $amountReturned >= ($amountSpecified * (1 + ($sabreVariables['price_percent_margin'] / 100)))) || (!isset($sabreVariables['price_percent_margin']) && $amountReturned > $amountSpecified)) {
            // $data['amount'] = "";
            // $data['currency'] = "";
            // $data['status'] = "priceError";
            // $data['message'] = "Amount returned is greater than the amount specified";
            // return $data;
            // }

            $pricedItinerary = $xpath->query("./xmlns:PriceQuote/xmlns:PricedItinerary", $otaAirPriceRS);
            if ($pricedItinerary && $pricedItinerary->length) {
                //$data['currency'] = $pricedItinerary->item(0)->getAttribute('CurrencyCode');
                $data['currency'] = 'AED';
                $data['amount'] = $this->currencyService->exchangeAmount($pricedItinerary->item(0)->getAttribute('TotalAmount'), $pricedItinerary->item(0)->getAttribute('CurrencyCode'), 'AED');

                $data['status'] = 'success';

                return $data;
            }
        }
    }

    /**
     * After holding the seats by calling the createEnhancedBookRequest, this function is responsible to create a PNR id for this passenger, based on the information subimeted by the user using the form
     *
     * @param array $sabreVariables
     *            sabre variable contains, a valid session after calling createSession, and the apiname that should be called in the request Header
     * @param array $request
     *            this array contains all the information submited in the form that is filled by the user
     * @param string $marketingAirline
     *            the airline name
     * @return array get status, and get the pnr code created by sabre
     */
    public function createPassengerDetailsRequest($sabreVariables, $request, $marketingAirline = "")
    {


        $passengerDetails = $this->createPassengerDetailsRequest->requestHeader($sabreVariables) . $this->createPassengerDetailsRequest->passengerDetailsRequest($request, $marketingAirline);

        $getPassengerDetailsRequest = $this->utils->send_data($this->URL, $passengerDetails, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);

        return $this->passengerNormaliser->createPassengerDetailsRequest($passengerDetails, $getPassengerDetailsRequest);
        /*
         * $data = array("faultcode" => "", "status" => "", "message" => "", "messages" => "", "pnrId" => "", "pricingInfo" => [], "request" => $getPassengerDetailsRequest['request_body'], "response" => $getPassengerDetailsRequest['response_text']);
         *
         * // echo '<textarea cols="120" rows="20">' . $getPassengerDetailsRequest['response_text'] . '</textarea>';
         * // echo '<textarea cols="120" rows="20">' . $getPassengerDetailsRequest['request_body'] . '</textarea>';
         *
         *
         * $errorRs = $this->errorRsHandler($getPassengerDetailsRequest['response_text']);
         * if ($errorRs) {
         * $data['faultcode'] = $errorRs['faultcode'];
         * $data['status'] = $errorRs['status'];
         * $data['message'] = $errorRs['message'];
         * return $data;
         * }
         *
         * if ($getPassengerDetailsRequest['response_error'] == RESPONSE_ERROR) {
         * $data["status"] = 'error';
         * $data['message'] = 'Could not connect to server please try again';
         * return $data;
         * }
         *
         * $xpath = $this->utils->xmlString2domXPath($getPassengerDetailsRequest['response_text']);
         * $checkResponse = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:PassengerDetailsRS");
         * $appResult = $checkResponse->item(0)->firstChild->firstChild;
         *
         * $data["status"] = strtolower($appResult->localName);
         *
         * if ($data["status"] == "success") {
         *
         * $pricingInfo = array("BaseFare" => 0, "EquivFare" => 0, "Taxes" => 0, "TotalFare" => 0, "CurrencyCode" => "");
         *
         * $xpath = $this->utils->xmlString2domXPath($getPassengerDetailsRequest['response_text']);
         * $totals = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:PassengerDetailsRS/xmlns:TravelItineraryReadRS/xmlns:TravelItinerary/xmlns:ItineraryInfo/xmlns:ItineraryPricing/xmlns:PriceQuoteTotals");
         *
         * if ($totals && $totals->length) {
         * foreach ($totals as $total) {
         * foreach ($total->childNodes as $value) {
         * if ($value->nodeName === "Taxes") {
         * $pricingInfo[$value->nodeName] = $value->firstChild->getAttribute('Amount');
         * } else {
         * $pricingInfo[$value->nodeName] = $value->getAttribute('Amount');
         * }
         * }
         * }
         * }
         *
         * $totalFare = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:PassengerDetailsRS/xmlns:TravelItineraryReadRS/xmlns:TravelItinerary/xmlns:ItineraryInfo/xmlns:ItineraryPricing/xmlns:PriceQuote/xmlns:PricedItinerary/xmlns:AirItineraryPricingInfo/xmlns:ItinTotalFare/xmlns:TotalFare");
         * if ($totalFare && $totalFare->length) {
         * if ($pricingInfo["TotalFare"] === 0) {
         * $pricingInfo["TotalFare"] = $totalFare->getAttribute('Amount');
         * }
         * $pricingInfo["CurrencyCode"] = $totalFare->item(0)->getAttribute('CurrencyCode');
         * }
         * $pnr = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:PassengerDetailsRS/xmlns:ItineraryRef");
         * if ($pnr && $pnr->length) {
         * $pnrId = $pnr->item(0)->getAttribute('ID');
         * $data["pnrId"] = $pnrId;
         * } else {
         * $data["status"] = "error";
         * $data['message'] = $checkResponse->item(0)->firstChild->firstChild->nextSibling->firstChild->firstChild->nodeValue;
         * }
         * $data['pricingInfo'] = $pricingInfo;
         * } elseif ($data["status"] == "error") {
         * $data['message'] = $appResult->firstChild->firstChild->nodeValue;
         * } elseif ($data["status"] === "errors") {
         * foreach ($appResult->childNodes as $error) {
         * $data['messages'][] = $error->getAttribute('ShortText');
         * }
         * }
         *
         * return $data;
         */
    }

    /**
     * To check with Mazen
     *
     * @param type $sabreVariables
     * @param type $action
     * @param type $pnrId
     * @return string
     */
    public function passengerDetailActionsRequest($sabreVariables, $action, $pnrId)
    {
        $passengerDetails = $this->passengerDetailsEndTransactionRequest->requestHeader($sabreVariables) . $this->passengerDetailsEndTransactionRequest->passengerDetailActions($testMode = $this->TEST_MODE, $sabreVariables['ProjectIPCC'], $action, $pnrId);

        $getPassengerDetailsRequest = $this->utils->send_data($this->URL, $passengerDetails, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);

        $data = array(
            "faultcode" => "",
            "status" => "",
            "message" => "",
            "messages" => "",
            "pnrId" => "",
            "tickets" => [],
            "request" => $getPassengerDetailsRequest['request_body'],
            "response" => $getPassengerDetailsRequest['response_text']
        );

        if ($getPassengerDetailsRequest['response_error'] == RESPONSE_ERROR) {
            $data["status"] = 'error';
            $data['message'] = 'Could not connect to server please try again';

            return $data;
        }

        // echo '<textarea cols="120" rows="20">' . $getPassengerDetailsRequest['response_text'] . '</textarea>';
        // echo '<textarea cols="120" rows="20">' . $getPassengerDetailsRequest['request_body'] . '</textarea>';

        $errorRs = $this->errorRsHandler($getPassengerDetailsRequest['response_text']);
        if ($errorRs) {
            $data['faultcode'] = $errorRs['faultcode'];
            $data['status'] = $errorRs['status'];
            $data['message'] = $errorRs['message'];
            return $data;
        }

        $xpath = $this->utils->xmlString2domXPath($getPassengerDetailsRequest['response_text']);
        $checkResponse = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:PassengerDetailsRS");
        $appResult = $checkResponse->item(0)->firstChild->firstChild;

        $data["status"] = strtolower($appResult->localName);

        if ($data["status"] == "success") {
            $pnr = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:PassengerDetailsRS/xmlns:ItineraryRef");
            if ($pnr && $pnr->length) {
                $pnrId = $pnr->item(0)->getAttribute('ID');
                $data["pnrId"] = $pnrId;
            }
            $tickets = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:PassengerDetailsRS/xmlns:TravelItineraryReadRS/xmlns:TravelItinerary/xmlns:ItineraryInfo/xmlns:Ticketing");
            if ($tickets && $tickets->length) {
                foreach ($tickets as $ticket) {
                    if ($ticket->getAttribute('RPH') && intval($ticket->getAttribute('RPH') > 1))
                        $data['tickets'][$ticket->getAttribute('RPH')] = substr($ticket->getAttribute('eTicketNumber'), 3, 13);
                }
            }
        } elseif ($data["status"] == "error") {
            $data['message'] = $appResult->firstChild->firstChild->nodeValue;
        } elseif ($data["status"] === "errors") {
            foreach ($appResult->childNodes as $error) {
                $data['messages'][] = $error->getAttribute('ShortText');
            }
        }
        return $data;
    }

    public function createRetrieveItineraryRequest($sabreVariables, $pnrId, $options = array(), $source = 'web')
    {

        $travelItinerary = $this->createRetrieveItineraryRequest->requestHeader($sabreVariables) . $this->createRetrieveItineraryRequest->retrieveItineraryRequest($pnrId);
        $connection_config = array(
            'connection_timeout' => 30,
            'timeout' => 60
        );
        $retrieveItineraryRequest = $this->utils->send_data($this->URL, $travelItinerary, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS, $connection_config);


        $data = array(
            "faultcode" => '',
            "status" => 'success',
            "message" => '',
            "messages" => '',
            "pnrId" => $pnrId,
            "tickets" => array(),
            "request" => $retrieveItineraryRequest['request_body'],
            "response" => $retrieveItineraryRequest['response_text'],
            "flight_segments" => array()
        );

        if ($source == 'cronjob') {
            echo "\n\nPNR:: $pnrId " . $retrieveItineraryRequest['request_body'];
            echo "\n\nPNR:: $pnrId " . $retrieveItineraryRequest['response_text'];
        }

        if ($retrieveItineraryRequest['response_error'] == RESPONSE_ERROR) {
            $data["status"] = 'error';
            $data['message'] = 'Could not connect to server please try again';
            return $data;
        }

        $errorRs = $this->errorRsHandler($retrieveItineraryRequest['response_text']);
        if ($errorRs) {

            $data['faultcode'] = $errorRs['faultcode'];
            $data['status'] = $errorRs['status'];
            $data['message'] = $errorRs['message'];
            return $data;
        }

        if (!is_array($options))
            $options = array();

        $default_options = array('fetch_airline_locators' => false);

        $effective_options = array_merge($default_options, $options);

        $xpath = $this->utils->xmlString2domXPath($retrieveItineraryRequest['response_text']);

        /*if ($effective_options['fetch_airline_locators']) {*/

            // $data['flight_segments'] = array('status' => 'success', 'RPH' => array());
            $data['flight_segments'] = array('status' => 'success', 'segments' => array());

            $air_segments = $xpath->query("/soap-env:Envelope/soap-env:Body/stl19:GetReservationRS/stl19:Reservation/stl19:PassengerReservation/stl19:Segments/stl19:Segment/stl19:Air");

            if ($air_segments && $air_segments->length) {
                foreach ($air_segments as $air_segment) {

                    // RPH attribute is optional for both SegmentType.PNRB/Segment and SegmentType.PNRB/Segment/Air (ref:: PNRBuilderTypes_v1.19.0.xsd)
                    $rph = ($air_segment->hasAttribute('RPH') ? $air_segment->getAttribute('RPH') : $air_segment->getAttribute('sequence'));

                    /*
                        TravelItinerary.ItineraryInfo.ReservationItems.Item.FlightSegment.SegmentNumber
                        changed to GetReservationRS.Reservation.PassengerReservation.Segments.Segment.Air.sequence (misspelled as seqence)
                        (ref:: Travel Itinerary Read 3.10 to GetReservation 1.19 Migration Guide, page 23 of document:: TIR_to_GetRes_migration_guide.pdf)
                    */

                    // $data['flight_segments']['RPH'][$rph] = array('segment_number' => $air_segment->getAttribute('sequence'));
                    $segment_number = (int)$air_segment->getAttribute('sequence');
                    $data['flight_segments']['segments'][$segment_number] = array('RPH' => $rph);


                    $eTicketNodeList = $xpath->query('./stl19:Eticket', $air_segment);

                    if (!$eTicketNodeList || !$eTicketNodeList->length || strtolower($eTicketNodeList->item(0)->nodeValue) != 'true') {
                        $data['flight_segments']['status'] = 'error';
                        $data['flight_segments']['error_code'] = 1;
                        $data['flight_segments']['error_message'] = 'No eTicket flag found, or invalid eTicket flag value';

                        break;
                    }


                    $airlineLocatorNodeList = $xpath->query('./stl19:AirlineRefId', $air_segment);

                    if ($airlineLocatorNodeList && $airlineLocatorNodeList->length) {
                        $airline_locator_string = $airlineLocatorNodeList->item(0)->nodeValue;

                        $matching_result = preg_match('/([^*]{4})\*([^*]{3,})/', $airline_locator_string, $locators);

                        if ($matching_result !== false && $matching_result) {
                            // $data['flight_segments']['RPH'][$rph]['airline_locator'] = $locators[2];
                            $data['flight_segments']['segments'][$segment_number]['airline_locator'] = $locators[2];
                        } else {
                            $data['flight_segments']['status'] = 'error';
                            $data['flight_segments']['error_code'] = 3;
                            $data['flight_segments']['error_message'] = 'No Airline Locator found';
                            $data['flight_segments']['error_message_details'] = "Airline Locator [RPH:: $rph]:: pattern mismatch:: $airline_locator_string";

                            break;
                        }
                    } else {
                        $data['flight_segments']['status'] = 'error';
                        $data['flight_segments']['error_code'] = 2;
                        $data['flight_segments']['error_message'] = 'No Airline Locator found';

                        break;
                    }

                }
            }
            try {
                // when we need to extract tickets, we don't need to check for Airline Locator(s), as we should have checked it earlier in the ticket issuing process.
                $Passengers = $xpath->query("/soap-env:Envelope/soap-env:Body/stl19:GetReservationRS/stl19:Reservation/stl19:PassengerReservation/stl19:Passengers/stl19:Passenger");

                if ($Passengers && $Passengers->length) {
                    $i = 2;
                    foreach ($Passengers as $Passenger) {

                        $Tickets = $xpath->query('./stl19:TicketingInfo/stl19:TicketDetails', $Passenger);


                        foreach ($Tickets as $Ticket) {

                            $TicketNumber = $xpath->query('./stl19:TicketNumber', $Ticket);
                            $TicketNumber = $TicketNumber->item(0)->nodeValue;
                            $data['tickets'][$i] = $TicketNumber;

                        }

                        $i += 1;
                    }
                }
            } catch (Exception $e) {

            }
        /*}*/

        return $data;
    }

    /**
     * in this function a PNR id is sent to the itinerary api to get all inforamtion about this PNR id, in case the ticket is not issued, the ticket is empty, else the tickets contains ticket number
     *
     * @param array $sabreVariables
     *            sabre variable contains, a valid session after calling createSession, and the apiname that should be called in the request Header
     * @param string $pnrId
     *            pnrId get from createPassengerDetails
     * @param array $options
     *            Provide options like check_airline_locators (boolean, when false (default value), this function retrieves the tickets), check_first_segment_only (boolean), and fetch_airline_locators (boolean, use true to fetch the values of the Airline Locators)
     * @param string $source
     *            the source if mobile or web that they are using this function
     * @return array that contains all the information about the PNR
     */
    public function createTravelItineraryRequest($sabreVariables, $pnrId, $options = array(), $source = 'web')
    {
        $travelItinerary = $this->createTravelItineraryRequest->requestHeader($sabreVariables) . $this->createTravelItineraryRequest->travelItineraryRequest($pnrId);

        $getTravelItineraryRequest = $this->utils->send_data($this->URL, $travelItinerary, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);

        $data = array(
            "faultcode" => "",
            "status" => "",
            "message" => "",
            "messages" => "",
            "pnrId" => "",
            "tickets" => [],
            "request" => $getTravelItineraryRequest['request_body'],
            "response" => $getTravelItineraryRequest['response_text']
        );

        // echo '<textarea cols="120" rows="20">' . $getTravelItineraryRequest['response_text'] . '</textarea>';
        // echo '<textarea cols="120" rows="20">' . $getTravelItineraryRequest['request_body'] . '</textarea>';
        // exit();

        if ($source == 'cronjob') {
            echo "\n\nPNR:: $pnrId " . $getTravelItineraryRequest['request_body'];
            echo "\n\nPNR:: $pnrId " . $getTravelItineraryRequest['response_text'];
        }

        if ($getTravelItineraryRequest['response_error'] == RESPONSE_ERROR) {
            $data["status"] = 'error';
            return $data;
        }

        $errorRs = $this->errorRsHandler($getTravelItineraryRequest['response_text']);
        if ($errorRs) {
            $data['faultcode'] = $errorRs['faultcode'];
            $data['status'] = $errorRs['status'];
            $data['message'] = $errorRs['message'];
            return $data;
        }

        if (!is_array($options))
            $options = array();

        $default_options = array(
            'check_airline_locators' => false,
            'check_first_segment_only' => false,
            'fetch_airline_locators' => false
        );

        $effective_options = array_merge($default_options, $options);

        if ($effective_options['fetch_airline_locators'])
            $effective_options['check_airline_locators'] = true;

        $xpath = $this->utils->xmlString2domXPath($getTravelItineraryRequest['response_text']);

        $travelItineraryReadResponse = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:TravelItineraryReadRS");
        $travelItineraryReadResponse = $travelItineraryReadResponse->item(0);

        $appResult = $travelItineraryReadResponse->firstChild->firstChild;

        $data["status"] = strtolower($appResult->localName);

        if ($data["status"] == 'success') {
            /*
             * $pnr = $xpath->query("./xmlns:TravelItinerary/xmlns:ItineraryRef", $travelItineraryReadResponse);
             * if ($pnr && $pnr->length) {
             * $pnrId = $pnr->item(0)->getAttribute('ID');
             *
             * }
             */

            $data["pnrId"] = $pnrId;

            $itineraryInfo = $xpath->query("./xmlns:TravelItinerary/xmlns:ItineraryInfo", $travelItineraryReadResponse);
            $itineraryInfo = $itineraryInfo->item(0);

            if ($effective_options['check_airline_locators']) {
                $reservationItems = $xpath->query('./xmlns:ReservationItems/xmlns:Item', $itineraryInfo);

                if ($reservationItems && $reservationItems->length) {
                    /*
                     * flightSegments holds its own status, it won't affect the status value of $data; it has been set this way to allow the caller to ignore the information it holds, even if related options have been given.
                     * The status of flightSegments was also added so that one can differentiate between the success of the API call, and the Airline Locators existence/pattern.
                     * When option check_airline_locators is false, no flight_segments key will be returned in the response, because in that case, there's no need for such data.
                     */
                    $flightSegments = array(
                        'status' => 'success',
                        'RPH' => array()
                    );

                    foreach ($reservationItems as $riIndex => $reservationItem) {
                        $rph = $reservationItem->getAttribute('RPH');

                        if ($effective_options['check_first_segment_only'] && $rph != 1)
                            break;

                        $flightSegments['RPH'][$rph] = array();

                        $flightSegment = $xpath->query('./xmlns:FlightSegment', $reservationItem);

                        if (!$flightSegment || !$flightSegment->length)
                            continue;

                        $flightSegment = $flightSegment->item(0);

                        $flightSegments['RPH'][$rph]['segment_number'] = $flightSegment->getAttribute('SegmentNumber');

                        // eTicket is an attribute of FlightSegment, it's a boolean flag, it must hold the value "true"
                        if (!$flightSegment->hasAttribute('eTicket') || $flightSegment->getAttribute('eTicket') != "true") {
                            $flightSegments['status'] = 'error';
                            $flightSegments['error_code'] = 1;
                            $flightSegments['error_message'] = 'No eTicket flag found, or invalid eTicket flag value';

                            break;
                        }

                        $flightSegments['RPH'][$rph]['eTicket'] = $flightSegment->getAttribute('eTicket');

                        // SupplierRef of each FlightSegment must contain an ID attribute, which must contain the Airline Locators (a string formatted as XXXX*YYYYYY)
                        $supplier_ref = $xpath->query('./xmlns:SupplierRef', $flightSegment);
                        if (!$supplier_ref || !$supplier_ref->length) {
                            $flightSegments['status'] = 'error';
                            $flightSegments['error_code'] = 2;
                            $flightSegments['error_message'] = 'No Airline Locator found';

                            break;
                        }

                        if (!$supplier_ref->item(0)->hasAttribute('ID')) {
                            $flightSegments['status'] = 'error';
                            $flightSegments['error_code'] = 3;
                            $flightSegments['error_message'] = 'No Airline Locator found';

                            break;
                        }

                        $airline_locators = $supplier_ref->item(0)->getAttribute('ID');

                        if ($effective_options['fetch_airline_locators']) {
                            $matching_result = preg_match('/([^*]{4})\*([^*]{3,})/', $airline_locators, $locators);

                            if ($matching_result !== false && $matching_result) {
                                $flightSegments['RPH'][$rph]['airline_locators'] = $locators;
                            } else {
                                $flightSegments['status'] = 'error';
                                $flightSegments['error_code'] = 4;
                                $flightSegments['error_message'] = "Airline Locator [RPH:: $rph]:: pattern mismatch:: $airline_locators";
                                $flightSegments['error_message'] = 'No Airline Locator found';

                                break;
                            }
                        } else {
                            // no need to fetch the Airline Locators, just check for the pattern
                            $matching_result = preg_match('/[^*]{4}\*[^*]{3,}/', $airline_locators);

                            if ($matching_result === false || !$matching_result) {
                                $flightSegments['status'] = 'error';
                                $flightSegments['error_code'] = 4;
                                $flightSegments['RPH'][$rph]['airline_locators_check'] = 0;

                                break;
                            }
                        }
                    }

                    $data['flight_segments'] = $flightSegments;
                }
            } else {
                // when we need to extract tickets, we don't need to check for Airline Locator(s), as we should have checked it earlier in the ticket issuing process.

                $tickets = $xpath->query("./xmlns:Ticketing", $itineraryInfo);

                if ($tickets && $tickets->length) {
                    foreach ($tickets as $ticket) {
                        if ($ticket->getAttribute('RPH') && intval($ticket->getAttribute('RPH') > 1))
                            $data['tickets'][$ticket->getAttribute('RPH')] = substr($ticket->getAttribute('eTicketNumber'), 3, 13);
                    }
                }
            }
        } elseif ($data["status"] == "error") {
            $data['message'] = $appResult->firstChild->firstChild->nodeValue;
        } elseif ($data["status"] === "errors") {
            foreach ($appResult->childNodes as $error) {
                $data['messages'][] = $error->getAttribute('ShortText');
            }
        }

        return $data;
    }

    /**
     * designate Printer is when we reserve a printer, to print the ticket, depending on the paramter $rq we know if the request is a hardcopy printer, or an e-ticket printer
     *
     * @param array $sabreVariables
     *            sabre variable contains, a valid session after calling createSession, and the apiname that should be called in the request Header
     * @param int $rq
     *            this parameter decide if the printer is a hard copy printer or an e-ticket printer, if $rq = 1 e-ticket printer, if $rq = 2 hardcopy printer
     * @return array contains the request_bpdy and response_body, only for debugging, main result is the status
     */
    public function designatePrinterRequest($sabreVariables, $rq)
    {
        $designatePrinter = $this->createdesignatePrinterRequest->requestHeader($sabreVariables) . $this->createdesignatePrinterRequest->DesignatePrinterRequest($rq);

        $getDesignatePrinterRequest = $this->utils->send_data($this->URL, $designatePrinter, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);

        $data = array(
            "faultcode" => "",
            "status" => "",
            "message" => "",
            "request" => $getDesignatePrinterRequest['request_body'],
            'response' => $getDesignatePrinterRequest['response_text']
        );

        // echo '<textarea cols="120" rows="20">' . $getDesignatePrinterRequest['response_text'] . '</textarea>';
        // echo '<textarea cols="120" rows="20">' . $getDesignatePrinterRequest['request_body'] . '</textarea>';

        if ($getDesignatePrinterRequest['response_error'] == RESPONSE_ERROR) {
            $data["status"] = 'error';
            return $data;
        }

        $errorRs = $this->errorRsHandler($getDesignatePrinterRequest['response_text']);
        if ($errorRs) {
            $data['faultcode'] = $errorRs['faultcode'];
            $data['status'] = $errorRs['status'];
            $data['message'] = $errorRs['message'];
            return $data;
        }

        $xpath = $this->utils->xmlString2domXPath($getDesignatePrinterRequest['response_text']);

        $checkResponse = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:DesignatePrinterRS");
        $appResult = $checkResponse->item(0)->firstChild->firstChild->localName;

        $data["status"] = strtolower($appResult);

        return $data;
    }

    /**
     * this function is to issue the ticket choosen by the user, and issued using the IATA IPCC.
     *
     * @param array $sabreVariables
     *            sabre variable contains, a valid session after calling createSession, and the apiname that should be called in the request Header
     * @param array $passengersArray
     *            this array contains information if the passenger is Adult, Children or infant to build the request
     * @return array contains the request_bpdy and response_body, only for debugging, main result is the status
     */
    public function airTicketRequest($sabreVariables, $passengersArray)
    {
        $airTicket = $this->createAirTicketRequest->requestHeader($sabreVariables) . $this->createAirTicketRequest->airTicketRq($passengersArray);

        $getAirTicketRequest = $this->utils->send_data($this->URL, $airTicket, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);

        $data = array(
            "faultcode" => "",
            "status" => "",
            "message" => "",
            "request" => $getAirTicketRequest['request_body'],
            "response" => $getAirTicketRequest['response_text']
        );

        // echo '<textarea cols="120" rows="20">' . $getAirTicketRequest['response_text'] . '</textarea>';
        // echo '<textarea cols="120" rows="20">' . $getAirTicketRequest['request_body'] . '</textarea>';

        if ($getAirTicketRequest['response_error'] == RESPONSE_ERROR) {
            $data["status"] = 'error';
            return $data;
        }

        $errorRs = $this->errorRsHandler($getAirTicketRequest['response_text']);

        if ($errorRs) {
            $data['faultcode'] = $errorRs['faultcode'];
            $data['status'] = $errorRs['status'];
            $data['message'] = $errorRs['message'];
            return $data;
        }

        $xpath = $this->utils->xmlString2domXPath($getAirTicketRequest['response_text']);

        $responseNode = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:AirTicketRS");
        $responseNode = $responseNode->item(0);

        $appResults = $responseNode->firstChild;
        $data['app_results'] = array(
            'status' => $appResults->getAttribute('status'),
            'message' => ''
        );

        if ($data['app_results']['status'] != 'Complete') {
            $message = $xpath->query("./stl:Error/stl:SystemSpecificResults/stl:Message", $appResults);

            if ($message && $message->length) {
                $data['app_results']['message'] = $message->item(0)->nodeValue;
            }
        }

        $data['status'] = strtolower($appResults->firstChild->localName);

        return $data;
    }

    /**
     * this is an IMPORTANT function, this is where all the workflow, of issuing a ticket or anyother worflow with sabre is saved, if this request is not sent is like we did nothing, this is the save function.
     *
     * @param array $sabreVariables
     *            sabre variable contains, a valid session after calling createSession, and the apiname that should be called in the request Header
     * @param string $source
     *            the source is used by the connection pool manager, to now if the cronJob is using specific session, or normal user on the web
     * @return array contains the request_bpdy and response_body, only for debugging, main result is the status
     */
    public function endTransactionRequest($sabreVariables, $source = 'web')
    {
        $endTransaction = $this->createEndTransactionRequest->requestHeader($sabreVariables) . $this->createEndTransactionRequest->endTransactionRq();

        $getEndTransactionRequest = $this->utils->send_data($this->URL, $endTransaction, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);

        $data = array(
            "faultcode" => "",
            "status" => "",
            "message" => "",
            "pnr" => "",
            "request" => $getEndTransactionRequest['request_body'],
            "response" => $getEndTransactionRequest['response_text']
        );

        // echo '<textarea cols="120" rows="20">' . $getEndTransactionRequest['response_text'] . '</textarea>';
        // echo '<textarea cols="120" rows="20">' . $getEndTransactionRequest['request_body'] . '</textarea>';

        if ($source == 'cronjob') {
            echo "\n\n" . $getEndTransactionRequest['request_body'];
            echo "\n\n" . $getEndTransactionRequest['response_text'];
        }

        if ($getEndTransactionRequest['response_error'] == RESPONSE_ERROR) {
            $data["status"] = 'error';
            return $data;
        }

        $errorRs = $this->errorRsHandler($getEndTransactionRequest['response_text']);
        if ($errorRs) {
            $data['faultcode'] = $errorRs['faultcode'];
            $data['status'] = $errorRs['status'];
            $data['message'] = $errorRs['message'];
            return $data;
        }

        $xpath = $this->utils->xmlString2domXPath($getEndTransactionRequest['response_text']);

        $responseNode = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:EndTransactionRS");
        $responseNode = $responseNode->item(0);

        $appResults = $responseNode->firstChild;
        $data['app_results'] = array(
            'status' => $appResults->getAttribute('status'),
            'message' => ''
        );

        if ($data['app_results']['status'] != 'Complete') {
            $message = $xpath->query("./stl:Error/stl:SystemSpecificResults/stl:Message", $appResults);

            if ($message && $message->length) {
                $data['app_results']['message'] = $message->item(0)->nodeValue;
            }
        }

        $data['status'] = strtolower($appResults->firstChild->localName);

        if ($data['status'] == 'success') {
            $pnr = $xpath->query("./xmlns:ItineraryRef", $responseNode);
            $data['pnr'] = $pnr->item(0)->getAttribute('ID');
        }

        return $data;
    }

    /**
     * this function is made to send a request to sabre to Cancel PNR, this is used in case of cancellation of a flight, or in case of cronJob found a Pnr that is not finished, didn't pay
     *
     * @param array $sabreVariables
     *            sabre variable contains, a valid session after calling createSession, and the apiname that should be called in the request Header
     * @param string $source
     *            the source is used by the connection pool manager, to now if the cronJob is using specific session, or normal user on the web
     * @return array contains the request_bpdy and response_body, only for debugging, main result is the status
     */
    public function OTACancelRequest($sabreVariables, $source = 'web')
    {
        $OTACancel = $this->createOTACancelRequest->requestHeader($sabreVariables) . $this->createOTACancelRequest->OTACancelRequest();

        $getOTACancelRequest = $this->utils->send_data($this->URL, $OTACancel, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);

        $data = array(
            "faultcode" => "",
            "status" => "",
            "message" => "",
            "request" => $getOTACancelRequest['request_body'],
            "response" => $getOTACancelRequest['response_text']
        );

        // echo '<textarea cols="120" rows="20">' . $getOTACancelRequest['response_text'] . '</textarea>';
        // echo '<textarea cols="120" rows="20">' . $getOTACancelRequest['request_body'] . '</textarea>';

        if ($source == 'cronjob') {
            echo "\n\n" . $getOTACancelRequest['request_body'];
            echo "\n\n" . $getOTACancelRequest['response_text'];
        }

        if ($getOTACancelRequest['response_error'] == RESPONSE_ERROR) {
            $data["status"] = 'error';
            return $data;
        }

        $errorRs = $this->errorRsHandler($getOTACancelRequest['response_text']);
        if ($errorRs) {
            $data['faultcode'] = $errorRs['faultcode'];
            $data['status'] = $errorRs['status'];
            $data['message'] = $errorRs['message'];

            return $data;
        }

        $xpath = $this->utils->xmlString2domXPath($getOTACancelRequest['response_text']);
        $checkResponse = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:OTA_CancelRS");
        $appResult = $checkResponse->item(0)->firstChild->firstChild->localName;

        $data["status"] = strtolower($appResult);

        return $data;
    }

    /**
     * After calling the createItinarary, and we get all the pnr, and related tickets and pnrs to it,
     *
     * @param array $sabreVariables
     *            sabre variable contains, a valid session after calling createSession, and the apiname that should be called in the request Header
     * @param string $ticketRPH
     *            sending for each ticket the ticketRPH, to be canceled
     * @return array contains the request_bpdy and response_body, only for debugging, main result is the status
     */
    public function voidAirTicketRequest($sabreVariables, $ticketRPH)
    {
        $voidAirTicket = $this->createVoidAirTicketRequest->requestHeader($sabreVariables) . $this->createVoidAirTicketRequest->voidAirTicketRequest($ticketRPH);

        $getVoidAirTicket = $this->utils->send_data($this->URL, $voidAirTicket, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);

        $data = array(
            "faultcode" => "",
            "status" => "",
            "message" => "",
            "request" => $getVoidAirTicket['request_body'],
            "response" => $getVoidAirTicket['response_text']
        );

        // echo '<textarea cols="120" rows="20">' . $getVoidAirTicket['response_text'] . '</textarea>';
        // echo '<textarea cols="120" rows="20">' . $getVoidAirTicket['request_body'] . '</textarea>';

        if ($getVoidAirTicket['response_error'] == RESPONSE_ERROR) {
            $data["status"] = 'error';
            return $data;
        }

        $errorRs = $this->errorRsHandler($getVoidAirTicket['response_text']);
        if ($errorRs) {
            $data['faultcode'] = $errorRs['faultcode'];
            $data['status'] = $errorRs['status'];
            $data['message'] = $errorRs['message'];
            return $data;
        }

        $xpath = $this->utils->xmlString2domXPath($getVoidAirTicket['response_text']);

        $responseNode = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:VoidTicketRS");
        $responseNode = $responseNode->item(0);

        $appResults = $responseNode->firstChild;
        $data['app_results'] = array(
            'status' => $appResults->getAttribute('status'),
            'message' => ''
        );

        if ($data['app_results']['status'] != 'Complete') {
            $message = $xpath->query("./stl:Error/stl:SystemSpecificResults/stl:Message", $appResults);

            if ($message && $message->length) {
                $data['app_results']['message'] = $message->item(0)->nodeValue;
            }
        }

        $data['status'] = strtolower($appResults->firstChild->localName);

        if (strpos($data['app_results']['message'], 'NUMBER PREVIOUSLY VOIDED') !== false)
            $data['status'] = 'success';

        return $data;
    }

    /**
     * this function is used by the connection pool manager, and in case the user on the web took more the 12 min when filling personal information, this function in case the user pressed stay , it will send a refresh session to sabre, to make this session valid for another 15 minute
     *
     * @param array $sabreVariables
     *            sabre variable contains, a valid session after calling createSession, and the apiname that should be called in the request Header
     * @return array the status is the main attribute that we are looking for
     */
    public function refreshSessionRequest($sabreVariables)
    {
        $refreshSession = $this->refreshSessionRequest->requestHeader($sabreVariables) . $this->refreshSessionRequest->refreshSessionRequest();

        $getRefreshSessionData = $this->utils->send_data($this->URL, $refreshSession, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);

        $data = array(
            "faultcode" => "",
            "status" => "",
            "message" => "",
            "request" => $getRefreshSessionData['request_body'],
            "response" => $getRefreshSessionData['response_text']
        );

        // echo '<textarea cols="120" rows="20">' . $getRefreshSessionData['response_text'] . '</textarea>';
        // echo '<textarea cols="120" rows="20">' . $getRefreshSessionData['request_body'] . '</textarea>';

        if ($getRefreshSessionData['response_error'] == RESPONSE_ERROR) {
            $data["status"] = 'error';
            return $data;
        }

        $errorRs = $this->errorRsHandler($getRefreshSessionData['response_text']);
        if ($errorRs) {
            $data['faultcode'] = $errorRs['faultcode'];
            $data['status'] = $errorRs['status'];
            $data['message'] = $errorRs['message'];
            return $data;
        }

        $xpath = $this->utils->xmlString2domXPath($getRefreshSessionData['response_text']);
        $checkResponse = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:OTA_PingRS");
        $appResult = $checkResponse->item(0)->firstChild->localName;

        $data["status"] = strtolower($appResult);

        return $data;
    }

    /**
     * this is a function that used by CMD an IATA provider to check the status of pnr and tickets issued.
     *
     * @param type $sabreVariables
     * @param type $queue_type
     * @param type $type
     * @return string
     */
    public function queueAccessRequest($sabreVariables, $queue_type, $type)
    {
        $airTicket = $this->queueAccessRQ->requestHeader($sabreVariables) . $this->queueAccessRQ->queueAccessRq($sabreVariables['ProjectIPCC'], $queue_type, $type);

        $getQueueAccessRQ = $this->utils->send_data($this->URL, $airTicket, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);

        $data = array(
            "faultcode" => "",
            "status" => "",
            "message" => "",
            "request" => $getQueueAccessRQ['request_body'],
            "response" => $getQueueAccessRQ['response_text']
        );

        // echo '<textarea cols="120" rows="20">' . $getQueueAccessRQ['response_text'] . '</textarea>';
        // echo '<textarea cols="120" rows="20">' . $getQueueAccessRQ['request_body'] . '</textarea>';

        if ($getQueueAccessRQ['response_error'] == RESPONSE_ERROR) {
            $data["status"] = 'error';
            return $data;
        }

        $errorRs = $this->errorRsHandler($getQueueAccessRQ['response_text']);

        if ($errorRs) {
            $data['faultcode'] = $errorRs['faultcode'];
            $data['status'] = $errorRs['status'];
            $data['message'] = $errorRs['message'];
            return $data;
        }

        $xpath = $this->utils->xmlString2domXPath($getQueueAccessRQ['response_text']);

        $checkResponse = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:QueueAccessRS");
        $appResult = $checkResponse->item(0)->firstChild;

        $data["status"] = strtolower($appResult->firstChild->localName);
        $data["message"] = ($data["status"] === 'error') ? $appResult->firstChild->firstChild->firstChild->nodeValue : $appResult->firstChild->getAttribute('status');

        return $data;
    }

    /**
     * in this function we change our PCC, to the IATA Provider PCC, in this case we are changing from our PCC, to DANATA PCC to issue ticket.
     *
     * @param array $sabreVariables
     *            sabre variable contains, a valid session after calling createSession, and the apiname that should be called in the request Header
     * @return array contains the request_bpdy and response_body, only for debugging, main result is the status
     */
    public function contextChangeRequest($sabreVariables)
    {
        $contextChange = $this->contextChangeRQ->requestHeader($sabreVariables) . $this->contextChangeRQ->contextChangeRequest();

        $getContextChange = $this->utils->send_data($this->URL, $contextChange, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);

        // echo '<textarea cols="120" rows="20">' . $getContextChange['response_text'] . '</textarea>';
        // echo '<textarea cols="120" rows="20">' . $getContextChange['request_body'] . '</textarea>';

        $data = array(
            "faultcode" => "",
            "status" => "",
            "message" => "",
            "request" => $getContextChange['request_body'],
            "response" => $getContextChange['response_text']
        );

        if ($getContextChange['response_error'] == RESPONSE_ERROR) {
            $data["status"] = 'error';
            return $data;
        }

        $errorRs = $this->errorRsHandler($getContextChange['response_text']);
        if ($errorRs) {
            $data['faultcode'] = $errorRs['faultcode'];
            $data['status'] = $errorRs['status'];
            $data['message'] = $errorRs['message'];
            return $data;
        }

        $xpath = $this->utils->xmlString2domXPath($getContextChange['response_text']);
        $checkResponse = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:ContextChangeRS");
        $appResult = $checkResponse->item(0)->firstChild->firstChild->localName;

        $data["status"] = strtolower($appResult);


        return $data;
    }

    public function deletePriceQuote($sabreVariables)
    {
        $deletePrice = $this->deletePriceQuoteRQ->requestHeader($sabreVariables) . $this->deletePriceQuoteRQ->deletePriceQuote();

        $getDeletePrice = $this->utils->send_data($this->URL, $deletePrice, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);

        // echo '<textarea cols="120" rows="20">' . $getDeletePrice['response_text'] . '</textarea>';
        // echo '<textarea cols="120" rows="20">' . $getDeletePrice['request_body'] . '</textarea>';

        $data = array(
            "faultcode" => "",
            "status" => "",
            "message" => "",
            "request" => $getDeletePrice['request_body'],
            "response" => $getDeletePrice['response_text']
        );

        if ($getDeletePrice['response_error'] == RESPONSE_ERROR) {
            $data["status"] = 'error';
            return $data;
        }

        $errorRs = $this->errorRsHandler($getDeletePrice['response_text']);
        if ($errorRs) {
            $data['faultcode'] = $errorRs['faultcode'];
            $data['status'] = $errorRs['status'];
            $data['message'] = $errorRs['message'];
            return $data;
        }

        $xpath = $this->utils->xmlString2domXPath($getDeletePrice['response_text']);
        $checkResponse = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:DeletePriceQuoteRS");
        $appResult = $checkResponse->item(0)->firstChild->firstChild->localName;

        $data["status"] = strtolower($appResult);

        return $data;
    }

    /**
     * cancel PNR
     */
    public function cancelPNR($compositeObject, $sabreVariables, $source = 'web')
    {
        $pnrId = $compositeObject->getPassengerNameRecord()->getPnr();

        if ($source == 'cronjob')
            echo "PNR:: " . $pnrId;

        $callStack = array();

        $sabreVariables['Service'] ="GetReservationRQ";// "TravelItineraryReadRQ";
        $sabreVariables['Action'] = "GetReservationRQ";//"TravelItineraryReadRQ";
        $travelItineraryRead = $this->container->get('SabreServices')->createRetrieveItineraryRequest($sabreVariables, $pnrId, array('fetch_airline_locators' => true));
/*      $sabreVariables['Service'] = "TravelItineraryReadRQ";
        $sabreVariables['Action'] = "TravelItineraryReadRQ";
        $travelItineraryRead = $this->createTravelItineraryRequest($sabreVariables, $pnrId, null, $source);*/

        if ($source != 'cronjob')
            $callStack[] = $travelItineraryRead;

        if ($source == 'cronjob')
            echo "\ntravelItineraryRead " . $travelItineraryRead["status"] . "\n";

        if ($travelItineraryRead["status"] == 'success') {
            // Cancel PNR Without ticket
            // Make OTA_CancellRequest IF Exists
            $sabreVariables['Service'] = "OTA_CancelLLSRQ";
            $sabreVariables['Action'] = "OTA_CancelLLSRQ";

            $cancelRq = $this->OTACancelRequest($sabreVariables, $source);

            if ($source == 'cronjob')
                echo "\nOTACancelRequest " . $cancelRq['status'] . "\n";
            else
                $callStack[] = $cancelRq;

            if ($cancelRq['status'] == 'success') {
                $sabreVariables['Service'] = "EndTransactionLLSRQ";
                $sabreVariables['Action'] = "EndTransactionLLSRQ";

                $endtransaction = $this->endTransactionRequest($sabreVariables, $source);

                if ($source == 'cronjob')
                    echo "\nEND Transaction " . $endtransaction['status'] . "\n";
                else
                    $callStack[] = $endtransaction;

                if ($endtransaction['status'] == 'success') {
                    $compositeObject->getPassengerNameRecord()->setStatus("CANCELLED");
                    $compositeObject->setResponseMessage(($source == 'cronjob' ? 'AUTO_CANCEL' : 'REQUESTED_CANCEL'));

                    $this->em->persist($compositeObject);
                    $this->em->flush();
                }
            }
        }

        return $callStack;
    }

    /**
     * this is a void function that is used from cronJob to check the PNR that are created, and had more than 15 minute and not procced to the payment
     *
     * @param string $source
     *            the source is used by the connection pool manager, to now if the cronJob is using specific session, or normal user on the web
     */
    public function paymentLastUpdateChecker($source = 'web')
    {
        $current_time = new \DateTime("now");

        echo $current_time->format('Y-m-d H:i:s');
        echo "\n";

        $pnrList = $this->pnrListToCancel();

        if ($pnrList == null || !$pnrList) {
            echo "\nFound no PNRs to cancel\n";
            return;
        }
        //
        // $sabreVariables = $this->getSabreConnectionVariables(true);
        // $create_session_response = $this->createSabreSessionRequest($sabreVariables, 0, 2, $source);
        //
        // $sabreVariables['access_token'] = $create_session_response['AccessToken'];
        // $sabreVariables['returnedConversationId'] = $create_session_response['ConversationId'];

        $sabreVariables = $this->getSabreConnectionVariables($this->production_server);
        $create_session_response = $this->createSabreSessionRequest($sabreVariables, 0, 2, $source);

        $sabreVariables['access_token'] = $create_session_response['AccessToken'];
        $sabreVariables['returnedConversationId'] = $create_session_response['ConversationId'];

        foreach ($pnrList as $pnr) {

            $pnrId = $pnr->getPassengerNameRecord()->getPnr();
            echo "PNR:: " . $pnrId;
            $sabreVariables['Service'] ="GetReservationRQ";// "TravelItineraryReadRQ";
            $sabreVariables['Action'] = "GetReservationRQ";//"TravelItineraryReadRQ";
            $travelItineraryRead = $this->container->get('SabreServices')->createRetrieveItineraryRequest($sabreVariables, $pnrId, array('fetch_airline_locators' => true));
            //$travelItineraryRead = $this->createTravelItineraryRequest($sabreVariables, $pnrId, null, $source);
            echo "\n";
            echo " travelItineraryRead[PNR:: $pnrId]:: status:: " . $travelItineraryRead["status"];
            echo "\n";
            if ($travelItineraryRead["status"] == 'success') {
                // Cancel PNR Without ticket
                // Make OTA_CancellRequest IF Exists
                $sabreVariables['Service'] = "OTA_CancelLLSRQ";
                $sabreVariables['Action'] = "OTA_CancelLLSRQ";
                $cancelRq = $this->OTACancelRequest($sabreVariables, $source);
                echo " OTACancelRequest[PNR:: $pnrId]:: status::  " . $cancelRq['status'];
                echo "\n";
                if ($cancelRq['status'] == 'success') {

                    $sabreVariables['Service'] = "EndTransactionLLSRQ";
                    $sabreVariables['Action'] = "EndTransactionLLSRQ";
                    $endtransaction = $this->endTransactionRequest($sabreVariables, $source);
                    echo "END Transaction[PNR:: $pnrId]:: status:: " . $endtransaction['status'];
                    echo "\n";
                    if ($endtransaction['status'] == 'success') {

                        $pnr->getPassengerNameRecord()->setStatus("CANCELLED");
                        $pnr->setResponseMessage("AUTO_CANCEL");
                        $this->em->persist($pnr);
                        $this->em->flush();
                    }
                }
            }
        }

        $this->closeSabreSessionRequest($sabreVariables, $source);
    }

    /*
     * TODO:: check what the name should better be
     * TODO:: check what time constraints should be applied
     */
    /**
     * this is a query that is used by the function paymentLAstUpdateChecker to find expired Pnr's who didn't make a paymenet
     *
     * @return array that contains pnr
     */
    public function pnrListToCancel()
    {
        $current_time_Minus_12 = new \DateTime("now");
        $current_time_Minus_12->modify("-12 minutes");
        echo "\n12 minutes back:: " . $current_time_Minus_12->format('Y-m-d H:i:s');
        echo "\n";
        /*
         * $current_time_Minus_24 = new \DateTime("now");
         * $current_time_Minus_24->modify("-24 minutes");
         * echo "\n";
         * echo $current_time_Minus_24->format('Y-m-d H:i:s');
         * echo "\n";
         */

        $query = $this->em->createQueryBuilder()
            ->select('p')
            ->from('PaymentBundle:Payment', 'p')
            ->innerJoin('p.passengerNameRecord', 'pnr')
            ->innerJoin('pnr.passengerDetails', 'pd')
            ->
            // ->where('NOT (p.status = :paymentSuccess OR (p.status = :paymentRefund AND p.responseMessage = :refundRespMsg))')
            andWhere('p.updatedDate < :date12')
            /*
              ->andWhere('p.updatedDate >= :date24')
             */
            ->andWhere('p.type = :type')
            ->
            // ->andWhere('p.command = :command')
            andWhere("p.responseMessage NOT IN ('AUTO_CANCEL', 'REQUESTED_CANCEL')")
            ->andWhere('pnr.status = :pnrStatus')
            ->andWhere('((pd.ticketNumber IS NULL) OR (pd.ticketNumber = :ticketNumber))')
            ->
            // ->andWhere('pnr.creationDate >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY)')
            andWhere('pnr.creationDate >= :pnrCreationDateConstraint')
            ->
            // ->setParameter('paymentSuccess', '14')
            // ->setParameter('paymentRefund', '06')
            // ->setParameter('refundRespMsg', 'Refunded')
            setParameter('date12', $current_time_Minus_12)
            /*
              ->setParameter('date24', $current_time_Minus_24)
             */
            ->setParameter('type', 'flights')
            ->
            // ->setParameter('command', 'PURCHASE')
            // ->setParameter('msg', "'AUTO_CANCEL', 'REQUESTED_CANCEL'")
            setParameter('pnrStatus', 'SUCCESS')
            ->setParameter('ticketNumber', '')
            ->setParameter('pnrCreationDateConstraint', new \DateTime('-7 days'));

        $getQuery = $query->getQuery();
        $getResult = $getQuery->getResult();
        return $getResult;
    }

    public function cmsIssueChecker($source)
    {
        $getAllPendingTicket = $this->em->getRepository('FlightBundle:PassengerDetail')->findByticketStatus("Pending");

        $sabreVariables = $this->getSabreConnectionVariables(true);
        $create_session_response = $this->createSabreSessionRequest($sabreVariables, 0, 1, $source);
        $sabreVariables['access_token'] = $create_session_response['AccessToken'];
        $sabreVariables['returnedConversationId'] = $create_session_response['ConversationId'];
        // $this->addFlightLog('Called API SessionCreateRQ with status:: '.$create_session_response['status']);

        foreach ($getAllPendingTicket as $event) {

            $pnrId = $event->getPassengerNameRecord()->getPnr();
            echo $pnrId;
            $sabreVariables['Service'] = "PassengerDetailsRQ";
            $sabreVariables['Action'] = "PassengerDetailsRQ";
            $retrieve = $this->passengerDetailActionsRequest($sabreVariables, "retrieve", $pnrId);
            $numberoftickets = count($retrieve['tickets']);
            echo "\n";
            echo $numberoftickets;
            echo "\n";
            if (($retrieve['status'] != "error" || $retrieve['status'] != "errors" || $retrieve['faultcode'] != '') || $numberoftickets > 0) {

                $passengers = $event->getPassengerNameRecord()->getPassengerDetails();
                $transationId = $event->getPassengerNameRecord()->getPaymentUUID();
                $passengersArray = array();
                foreach ($passengers as $key => $passenger) {
                    $passengersArray[$key]['first_name'] = $passenger->getFirstName();
                    $passengersArray[$key]['surname'] = $passenger->getSurname();
                    $passengersArray[$key]['type'] = $passenger->getType();
                    $passengersArray[$key]['gender'] = $passenger->getGender();
                    $passengersArray[$key]['dateOfBirth'] = $passenger->getDateofBirth();
                    $passengersArray[$key]['fare_calc_line'] = $passenger->getFareCalcLine();
                    $passengersArray[$key]['leaving_baggage_info'] = $passenger->getLeavingBaggageInfo();
                    $passengersArray[$key]['returning_baggage_info'] = $passenger->getReturningBaggageInfo() == null ? "" : $passenger->getReturningBaggageInfo();
                    $passengersArray[$key]['ticket_number'] = $passenger->getTicketNumber();
                }

                foreach ($retrieve["tickets"] as $key => $ticketNum) {
                    if ($key == $event->getTicketRph()) {
                        $event->setTicketStatus('Success');
                        $event->setTicketNumber($ticketNum);
                    }
                }
                $this->em->persist($event);
                $this->em->flush();

                $multiDestination = $event->getPassengerNameRecord()
                    ->getFlightInfo()
                    ->isMultiDestination();
                $flightDetail = $event->getPassengerNameRecord()->getFlightDetails();

                $flightSegments = array();

                foreach ($flightDetail as $index => $flight) {
                    $flightInfo = array();
                    $departureAirport = $this->em->getRepository('TTBundle:Airport')->findOneByAirportCode($flight->getDepartureAirport());
                    $arrivalAirport = $this->em->getRepository('TTBundle:Airport')->findOneByAirportCode($flight->getArrivalAirport());

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
                    $mainAirline = $this->em->getRepository('TTBundle:Airline')->findOneByCode($flight->getAirline());
                    $flightSegments[$flight->getType()]['main_airline'] = ($mainAirline) ? $mainAirline->getAlternativeBusinessName() : $flight->getAirline();

                    $flightInfo['departure_date'] = $flight->getDepartureDateTime()->format('M j Y');
                    $flightInfo['departure_time'] = $flight->getDepartureDateTime()->format('H\:i');
                    $flightInfo['origin_city'] = ($departureAirport) ? $departureAirport->getCity() : "";
                    $flightInfo['origin_airport'] = ($departureAirport) ? $departureAirport->getName() : "";
                    $flightInfo['origin_airport_code'] = $flight->getDepartureAirport();

                    $flightInfo['arrival_date'] = $flight->getArrivalDateTime()->format('M j Y');
                    $flightInfo['arrival_time'] = $flight->getArrivalDateTime()->format('H\:i');
                    $flightInfo['destination_airport_code'] = $flight->getArrivalAirport();
                    $flightInfo['destination_airport'] = ($arrivalAirport) ? $arrivalAirport->getName() : "";
                    $flightInfo['destination_city'] = ($arrivalAirport) ? $arrivalAirport->getCity() : "";

                    $airlineName = $this->em->getRepository('TTBundle:Airline')->findOneByCode($flight->getAirline());
                    $flightInfo['airline_name'] = ($airlineName) ? $airlineName->getAlternativeBusinessName() : $flight->getAirline();
                    $flightInfo['flight_number'] = $flight->getFlightNumber();
                    $flightInfo['airline_code'] = $flight->getAirline();

                    $operatingAirlineName = $this->em->getRepository('TTBundle:Airline')->findOneByCode($flight->getOperatingAirline());
                    $flightInfo['operating_airline_code'] = $flight->getOperatingAirline();
                    $flightInfo['operating_airline_name'] = ($operatingAirlineName) ? $operatingAirlineName->getAlternativeBusinessName() : $flight->getAirline();

                    $cabinName = $this->em->getRepository('FlightBundle:FlightCabin')->findOneByCode($flight->getCabin());
                    $flightInfo['cabin'] = ($cabinName) ? $cabinName->getName() : $flight->getCabin();
                    $flightInfo['flight_duration'] = $flight->getFlightDuration();
                    $flightInfo['stop_indicator'] = $flight->getStopIndicator();
                    $flightInfo['stop_info'] = "";

                    if ($flightInfo['stop_indicator'] == 1 && $multiDestination)
                        $flightSegments[$flight->getType()]['flight_info'][$index - 1]['stop_info'][] = $flightInfo;
                    else
                        $flightSegments[$flight->getType()]['flight_info'][] = $flightInfo;
                }

                $emailData['flight_segments'] = $flightSegments;
                $emailData['passenger_details'] = $passengersArray;
                $emailData['price'] = $event->getPassengerNameRecord()
                    ->getFlightInfo()
                    ->getPrice();
                $emailData['currency'] = $event->getPassengerNameRecord()
                    ->getFlightInfo()
                    ->getCurrency();
                $emailData['base_fare'] = $event->getPassengerNameRecord()
                    ->getFlightInfo()
                    ->getBaseFare();
                $emailData['taxes'] = $event->getPassengerNameRecord()
                    ->getFlightInfo()
                    ->getTaxes();
                $emailData['pnr'] = $pnrId;
                $emailData['transaction_id'] = $transationId;
                $emailData['special_requirement'] = $event->getPassengerNameRecord()->getSpecialRequirement();
                $emailData['email'] = $event->getPassengerNameRecord()->getEmail();
                $emailData['refundable'] = $event->getPassengerNameRecord()
                    ->getFlightInfo()
                    ->isRefundable();
                $emailData['one_way'] = $event->getPassengerNameRecord()
                    ->getFlightInfo()
                    ->isOneWay();
                $emailData['multi_destination'] = $multiDestination;

                $emailData['enableCancelation'] = $this->enableCancelation;

                $msg = $this->templating->render('emails/flight_email_ticket.twig', $emailData);

                $this->emailServices->addEmailData($emailData['email'], $msg, 'TouristTube Travel Ticket', 'TouristTube Travel Ticket', 0);
            }
        }

        $this->closeSabreSessionRequest($sabreVariables, $source);
    }

    /**
     * this function to get the Sabre Connection variable, that contains the header of each envolopped send to the Flight Provider Sabre,
     *
     * @param boolean $on_production_server
     *            to change from sabre test server to sabre production server, this variable is used, so it checks if the enviroment is a production so it uses the production links of sabre, else it uses the test links
     * @return array of sabre connection variable that are sent in the head of each request
     */
    public function getSabreConnectionVariables($on_production_server = false)
    {
        $options = array(
            'on_production_server' => $on_production_server
        );

        $global_id = $this->utils->GUID();

        $options['ProjectUserName'] = $this->container->getParameter('modules')['flights']['vendors']['sabre']['project_user_name'];//($this->TEST_MODE) ? 302504 : 141258;
        $options['CompanyName'] =$this->container->getParameter('modules')['flights']['vendors']['sabre']['company_name']; //'TN';
        $options['ProjectIPCC'] =$this->container->getParameter('modules')['flights']['vendors']['sabre']['project_ipcc'];// ($this->TEST_MODE) ? '02DH' : 'YV4I';
        $options['ProjectPCC'] =$this->container->getParameter('modules')['flights']['vendors']['sabre']['project_pcc'];// '9J3H';
        $options['ProjectPassword'] =$this->container->getParameter('modules')['flights']['vendors']['sabre']['project_password'];// ($this->TEST_MODE) ? 'WS671891' : 'WS328537';
        $options['ProjectDomain'] =$this->container->getParameter('modules')['flights']['vendors']['sabre']['project_domain'];// 'DEFAULT';
        $options['ConversationId'] = $global_id . '@touristtube.com';
        $options['message_id'] ='mid:' . $global_id . '@touristtube.com';
        $options['party_id_from'] = 'touristtube.com';
        $options['party_id_to'] = 'webservices.sabre.com';
        $options['Timestamp'] = date("Y-m-d\TH:i:s\Z", strtotime("now"));
        $options['TimeToLive'] = date("Y-m-d\TH:i:s\Z", strtotime("now +15 minutes"));
        $options['salt'] = md5($this->container->getParameter('modules')['flights']['vendors']['sabre']['salt_key']);

        return $options;
    }

    /**
     * Renew timestamp values for a given array of Sabre variables.
     *
     * @param array $sabreVariables
     *            Array of Sabre variables for which to renew the timestamps
     */
    public function renewTimestamps(&$sabreVariables)
    {
        $sabreVariables['Timestamp'] = date("Y-m-d\TH:i:s\Z", strtotime("now"));
        $sabreVariables['TimeToLive'] = date("Y-m-d\TH:i:s\Z", strtotime("now +15 minutes"));
    }

    /*
     * public function getFlightSearchCriteria()
     * {
     * $request = $this->requestStack->getCurrentRequest();
     *
     * $searchCireteria = array();
     *
     * $searchCireteria['from_mobile'] = $request->get('from_mobile');
     * $searchCireteria['departureAirportN'] = $request->get('departureairport', '');
     * $searchCireteria['arrivalairportN'] = $request->get('arrivalairport', '');
     * $searchCireteria['departureAirport'] = $request->get('departureairportC', '');
     * $searchCireteria['arrivalairport'] = $request->get('arrivalairportC', '');
     * $searchCireteria['fromDate'] = $request->get('fromDateC', '');
     * $searchCireteria['toDate'] = $request->get('toDateC', '');
     * $searchCireteria['flexibleDate'] = $request->get('flexibledate', 0);
     * $searchCireteria['oneWay'] = intval($request->get('oneway', 0));
     * $searchCireteria['cabinSelect'] = $request->get('cabinselect', '');
     * $searchCireteria['adultsSelect'] = intval($request->get('adultsselect', 1));
     * $searchCireteria['childrenSelect'] = intval($request->get('childsselect', 0));
     * $searchCireteria['infantsSelect'] = intval($request->get('infantsselect', 0));
     * $searchCireteria['multiDestination'] = intval($request->get('multidestination', 0));
     * $searchCireteria['destinations'] = array();
     * $searchCireteria['noLogo'] = "no-logo.jpg";
     *
     * if ($searchCireteria['multiDestination']) {
     * $searchCireteria['oneWay'] = 1;
     *
     * $searchCireteria['destinations'] = $this->getParametersForMultiDestination();
     * }
     * if ($getFlightSearchCrieteria['adultsSelect'] == 0) {
     * $getFlightSearchCrieteria['adultsSelect'] = 1;
     * }
     * if ($getFlightSearchCrieteria['adultsSelect'] < $getFlightSearchCrieteria['infantsSelect']) {
     * $searchCireteria['error'] = $this->translator->trans("The number of infants must not be greater than the number of adults!");
     *
     * return $searchCireteria['error'];
     * }
     * $getFlightSearchCrieteria['numberInParty'] = intval($getFlightSearchCrieteria['adultsSelect'] + $getFlightSearchCrieteria['childrenSelect']);
     * $getFlightSearchCrieteria['airlines'] = '';
     * $getFlightSearchCrieteria['currency_code'] = '';
     * $getFlightSearchCrieteria['minimum_duration'] = 0;
     * $getFlightSearchCrieteria['maximum_duration'] = 0;
     * $getFlightSearchCrieteria['minimum_price'] = 0;
     * $getFlightSearchCrieteria['maximum_price'] = 0;
     *
     * return $searchCireteria;
     * }
     *
     */
    /*
     * public function getParametersForMultiDestination()
     * {
     * $request = $this->requestStack->getCurrentRequest();
     * $destinationsCount = 2;
     * for ($i = 0; $i < $destinationsCount; $i++) {
     * $departure_airport_multi_var = "departureairportC-$i";
     * $departureAirportMulti = $request->get($departure_airport_multi_var, '');
     *
     * $arrival_airport_multi_var = "arrivalairportC-$i";
     * $arrivalAirportMulti = $request->get($arrival_airport_multi_var, '');
     *
     * $from_date_multi_var = "fromDateC-$i";
     * $fromDateMulti = $request->get($from_date_multi_var, '');
     *
     * if ($departureAirportMulti === "" || $arrivalAirportMulti === "" || $fromDateMulti === "")
     * break;
     *
     * $destinations[$i]["departure_airport"] = $departureAirportMulti;
     * $destinations[$i]["arrival_airport"] = $arrivalAirportMulti;
     * $destinations[$i]["from_date"] = $fromDateMulti . 'T00:00:00';
     * }
     * return $destinations;
     * }
     *
     */
    /*
     * public function flightBookingResultWorkflow()
     * {
     * $service = $searchCireteria['flexibleDate'] ? 'BargainFinderMax_ADRQ' : 'BargainFinderMaxRQ';
     * $action = $service;
     * // Get a Valid Connection
     * $sabreVariables = $this->get('SabreServices')->getSabreConnectionVariables($this->on_production_server);
     * $create_session_response = $this->get('SabreServices')->createSabreSessionRequest($sabreVariables, ($this->data['isUserLoggedIn'] ? $this->data['USERID'] : 0), $this->connection_type_bfm, ($from_mobile ? 'mobile' : 'web'));
     * if ($create_session_response['status'] === 'success')
     * {
     *
     * $accessToken = $create_session_response['AccessToken'];
     * $returnedConversationId = ($create_session_response['ConversationId'] == '') ? '@touristtube.com' : $create_session_response['ConversationId'];
     * $sabreVariables = $this->setSabreVariablesForBargianRequest($service, $accessToken, $returnedConversationId);
     * //Call Bargain SOAP API
     * $priced_itineraries = $this->get('SabreServices')->createBargainRequest($sabreVariables);
     * // IN CASE OF ERROR IN BARGAIN API
     * if ($priced_itineraries['status'] === 'errors' || $priced_itineraries['status'] === 'error')
     * {
     *
     * $this->get('SabreServices')->closeSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
     * if (($priced_itineraries['status'] === 'errors' || $priced_itineraries['status'] === 'error') && $from_mobile == 1)
     * {
     * $ret['status'] = 'error';
     * $ret['message'] = $this->translator->trans('Could not connect to server please try again');
     * $res = new Response(json_encode($ret));
     * $res->headers->set('Content-Type', 'application/json');
     * return $res;
     *
     * }
     * elseif ($priced_itineraries['status'] === 'error' && ($priced_itineraries['faultcode'] === 'InvalidSecurityToken' || $priced_itineraries['faultcode'] === 'InvalidEbXmlMessage')) {
     * //TODO IN CONTROLLER
     * //return $this->redirectToRoute('_flight_booking', ['timedOut' => true]);
     * $errormsg = array('timedOut' => true);
     * return $errormsg;
     *
     * } elseif ($priced_itineraries['status'] === 'error') {
     * $errormsg = array('error' => $priced_itineraries['message']);
     * // return $this->render('@Flight/flight/flight-booking-result.twig', $errormsg);
     * return $errormsg;
     * }
     * }
     *
     * }
     *
     * }
     * *
     */
    /*
     * public function setSabreVariablesForBargianRequest($service,$accessToken,$returnedConversationId,$searchCireteria)
     * {
     * $sabreVariables['access_token'] = $accessToken;
     * $sabreVariables['returnedConversationId'] = $returnedConversationId;
     *
     * $sabreVariables['Service'] = $service;
     * $sabreVariables['Action'] = $service;
     *
     * $sabreVariables['OriginLocation'] = $searchCireteria['departureAirport'];
     * $sabreVariables['DestinationLocation'] = $searchCireteria['arrivalairport'];
     * $sabreVariables['FromDate'] = $searchCireteria['fromDate'] . 'T00:00:00';
     * $sabreVariables['ToDate'] = $searchCireteria['toDate'] . 'T23:59:59';
     * $sabreVariables['cabinPref'] = $searchCireteria['cabinSelect'];
     * $sabreVariables['TripType'] = $searchCireteria['oneWay'] ? 'OneWay' : 'Return';
     * $sabreVariables['FlexibleDate'] = $searchCireteria['flexibleDate'];
     * $sabreVariables['PassengerTypeAdults'] = $searchCireteria['adultsSelect'];
     * $sabreVariables['PassengerTypeChildren'] = $searchCireteria['childrenSelect'];
     * $sabreVariables['PassengerTypeInfants'] = $searchCireteria['infantsSelect'];
     *
     * $sabreVariables['MultiDestination'] = $searchCireteria['multiDestination'];
     * $sabreVariables['destinations'] = $searchCireteria['destinations'];
     * // $sabreVariables['CurrencyCode'] = $this->defaultCurrency;
     *
     * }
     * *
     *
     */
    /*
     * public function flightBookingResultService()
     * {
     * //POST PARAMS FOR SEARCH CRIETERIA
     * /* this should be deleted if the fetch of the cireteria works from the SabreService
     * $from_mobile = $request->request->get('from_mobile');
     * $departureAirportN = $request->request->get('departureairport', '');
     * $arrivalairportN = $request->request->get('arrivalairport', '');
     * $departureAirport = $request->request->get('departureairportC', '');
     * $arrivalairport = $request->request->get('arrivalairportC', '');
     * $fromDate = $request->request->get('fromDateC', '');
     * $toDate = $request->request->get('toDateC', '');
     * $flexibleDate = $request->request->get('flexibledate', 0);
     * $oneWay = intval($request->request->get('oneway', 0));
     * $cabinSelect = $request->request->get('cabinselect', '');
     * $adultsSelect = intval($request->request->get('adultsselect', 1));
     * $childrenSelect = intval($request->request->get('childsselect', 0));
     * $infantsSelect = intval($request->request->get('infantsselect', 0));
     * $multiDestination = intval($request->request->get('multidestination', 0));
     * $destinations = array();
     * $noLogo = "no-logo.jpg";
     *
     *
     * $getFlightSearchCrieteria = $this->get('SabreServices')->getFlightSearchCriteria();
     *
     * $this->addFlightLog('Searched available flights with criteria: {criteria}', array('criteria' => $request->request->all()));
     * // this section is added to the getFlightSearchCrieteria Service
     *
     * if ($multiDestination) {
     * $oneWay = 1;
     * $destinationsCount = 2;
     * for ($i = 0; $i < $destinationsCount; $i++) {
     * $departure_airport_multi_var = "departureairportC-$i";
     * $departureAirportMulti = $request->request->get($departure_airport_multi_var, '');
     *
     * $arrival_airport_multi_var = "arrivalairportC-$i";
     * $arrivalAirportMulti = $request->request->get($arrival_airport_multi_var, '');
     *
     * $from_date_multi_var = "fromDateC-$i";
     * $fromDateMulti = $request->request->get($from_date_multi_var, '');
     *
     * if ($departureAirportMulti === "" || $arrivalAirportMulti === "" || $fromDateMulti === "")
     * break;
     *
     * $destinations[$i]["departure_airport"] = $departureAirportMulti;
     * $destinations[$i]["arrival_airport"] = $arrivalAirportMulti;
     * $destinations[$i]["from_date"] = $fromDateMulti . 'T00:00:00';
     * }
     * }
     *
     *
     * // $this->data
     *
     * $this->data['departureAirportN'] = $getFlightSearchCrieteria['departureAirportN'];
     * $this->data['arrivalairportN'] = $getFlightSearchCrieteria['arrivalairportN'];
     * $this->data['departureAirport'] = $getFlightSearchCrieteria['departureAirport'];
     * $this->data['arrivalairport'] = $getFlightSearchCrieteria['arrivalairport'];
     * $this->data['fromDate'] = $getFlightSearchCrieteria['fromDate'];
     * $this->data['toDate'] = $getFlightSearchCrieteria['toDate'];
     * $this->data['flexibleDate'] = $getFlightSearchCrieteria['flexibleDate'];
     * $this->data['cabinSelect'] = $getFlightSearchCrieteria['cabinSelect'];
     * $this->data['adultsSelect'] = $getFlightSearchCrieteria['adultsSelect'];
     * $this->data['childrenSelect'] = $getFlightSearchCrieteria['childrenSelect'];
     * $this->data['infantsSelect'] = $getFlightSearchCrieteria['infantsSelect'];
     *
     * if ($getFlightSearchCrieteria['adultsSelect'] == 0) {
     * $getFlightSearchCrieteria['adultsSelect'] = 1;
     * }
     * if ($getFlightSearchCrieteria['adultsSelect'] < $getFlightSearchCrieteria['infantsSelect']) {
     * $this->data['error'] = $this->translator->trans("The number of infants must not be greater than the number of adults!");
     * }
     *
     *
     * $numberInParty = intval($adultsSelect + $childrenSelect);
     *
     *
     * // %$this->data, added the missing items to the function of getFlightSearchCrieteria
     *
     * $this->data['airlines'] = '';
     * $this->data['currency_code'] = '';
     * $this->data['minimum_duration'] = 0;
     * $this->data['maximum_duration'] = 0;
     * $this->data['minimum_price'] = 0;
     * $this->data['maximum_price'] = 0;
     * $this->data['one_way'] = $getFlightSearchCrieteria['oneWay'];
     * $this->data['multi_destination'] = $getFlightSearchCrieteria['multiDestination'];
     * $this->data['enableCancelation'] = $this->enableCancelation;
     * $this->data['enableRefundable'] = $this->enableRefundable;
     *
     * //Added to flightBookingResultWorkflow
     *
     * $service = $flexibleDate ? 'BargainFinderMax_ADRQ' : 'BargainFinderMaxRQ';
     * $action = $service;
     *
     * $sabreVariables = $this->get('SabreServices')->getSabreConnectionVariables($this->on_production_server);
     * $create_session_response = $this->get('SabreServices')->createSabreSessionRequest($sabreVariables, ($this->data['isUserLoggedIn'] ? $this->data['USERID'] : 0), $this->connection_type_bfm, ($from_mobile ? 'mobile' : 'web'));
     *
     *
     * $this->addFlightLog('(' . ($this->on_production_server ? 'PROD' : 'DEV') . ') flightBookingResultAction:: CreateSessionRS:: ' . print_r($create_session_response, true)); // REMOVE_ME
     * $this->addFlightLog('Called API SessionCreateRQ with status:: ' . $create_session_response['status']);
     * $this->addFlightLog('With criteria: {criteria}', array('criteria' => $create_session_response));
     * // Added to flightBookingResultWorkflow
     * if ($create_session_response['status'] === 'success') {
     *
     * $accessToken = $create_session_response['AccessToken'];
     * $returnedConversationId = ($create_session_response['ConversationId'] == '') ? '@touristtube.com' : $create_session_response['ConversationId'];
     *
     * // ADDED to setSabreVariablesForBargianRequest
     * $sabreVariables['access_token'] = $accessToken;
     * $sabreVariables['returnedConversationId'] = $returnedConversationId;
     *
     * $sabreVariables['Service'] = $service;
     * $sabreVariables['Action'] = $action;
     *
     * $sabreVariables['OriginLocation'] = $departureAirport;
     * $sabreVariables['DestinationLocation'] = $arrivalairport;
     * $sabreVariables['FromDate'] = $fromDate . 'T00:00:00';
     * $sabreVariables['ToDate'] = $toDate . 'T23:59:59';
     * $sabreVariables['cabinPref'] = $cabinSelect;
     * $sabreVariables['TripType'] = $oneWay ? 'OneWay' : 'Return';
     * $sabreVariables['FlexibleDate'] = $flexibleDate;
     * $sabreVariables['PassengerTypeAdults'] = $adultsSelect;
     * $sabreVariables['PassengerTypeChildren'] = $childrenSelect;
     * $sabreVariables['PassengerTypeInfants'] = $infantsSelect;
     *
     * $sabreVariables['MultiDestination'] = $multiDestination;
     * $sabreVariables['destinations'] = $destinations;
     * $sabreVariables['CurrencyCode'] = $this->defaultCurrency;
     *
     *
     * $this->addFlightLog('flightBookingResultAction:: BargainFinderMaxRQ:: sabreVariables:: ' . print_r($sabreVariables, true));
     * // Added To flightBookingResultWorkflow
     *
     * $priced_itineraries = $this->get('SabreServices')->createBargainRequest($sabreVariables); // , true); // temporarily enable debugging request and response for BFM requests
     *
     * // $this->addFlightLog('flightBookingResultAction:: BargainFinderMaxRS:: priced_itineraries:: ' . print_r($priced_itineraries, true)); // REMOVE_ME
     * $this->addFlightLog('Getting API BargainFinderMaxRQ with response: ' . $priced_itineraries['status']);
     * $this->addFlightLog('With criteria: {criteria}', array('criteria' => $priced_itineraries));
     *
     *
     * if ($priced_itineraries['status'] === 'errors' || $priced_itineraries['status'] === 'error') {
     *
     * $this->get('SabreServices')->closeSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
     *
     * $this->addFlightLog('Requesting API SessionCloseRQ');
     * // Added to FlightBookingResultWorkflow
     *
     * if (($priced_itineraries['status'] === 'errors' || $priced_itineraries['status'] === 'error') && $from_mobile == 1) {
     * $ret['status'] = 'error';
     * $ret['message'] = $this->translator->trans('Could not connect to server please try again');
     * $res = new Response(json_encode($ret));
     *
     * $this->addFlightLog('Sending MobileRQ with response: ' . $res);
     *
     * $res->headers->set('Content-Type', 'application/json');
     * return $res;
     *
     * } elseif ($priced_itineraries['status'] === 'error' && ($priced_itineraries['faultcode'] === 'InvalidSecurityToken' || $priced_itineraries['faultcode'] === 'InvalidEbXmlMessage')) {
     * return $this->redirectToRoute('_flight_booking', ['timedOut' => true]);
     * } elseif ($priced_itineraries['status'] === 'error') {
     * $this->data['error'] = $priced_itineraries['message'];
     * return $this->render('@Flight/flight/flight-booking-result.twig', $this->data);
     * }
     *
     * elseif ($priced_itineraries['status'] === 'errors') {
     * $this->data['errors'] = $priced_itineraries['messages'];
     * return $this->render('default/flight-booking-result.twig', $this->data);
     * }
     * }
     *
     * $airlines = array();
     * $segmentsArray = array();
     * $currency = "";
     * $currencyCode = filter_input(INPUT_COOKIE, 'currency');
     * $this->data['selected_currency'] = ($currencyCode == "") ? $this->defaultCurrency : $currencyCode;
     * $minimumDuration = 0;
     * $maximumDuration = 0;
     * $minimumPrice = 0;
     * $maximumPrice = 0;
     * $sequence_numberNew = 0;
     * $conversionRate = $this->get('CurrencyService')->getConversionRate($this->currencyPCC, $currencyCode);
     *
     * if (is_array($priced_itineraries['data']) && count($priced_itineraries['data']) > 0) {
     * foreach ($priced_itineraries['data'] as $priced_itinerary) {
     * $segmentsGlob = array();
     * $segmentsGlobMulti = array();
     * $segmentsdata = array();
     * $n_total_flight_segments = $this->get('app.utils')->count_total_subs($priced_itinerary['air_itinerary']['origin_destination_options'], 'flight_segments');
     * $n_flight_segments = 1;
     * $currency = $priced_itinerary['currency_code'];
     * $currencyCode = ($currencyCode == "") ? $this->defaultCurrency : $currencyCode;
     * $segmentCount = 0;
     * $secToken = [];
     * array_push($secToken, $numberInParty, $currency);
     *
     * //rudy
     * $flight_segmentsnode1 = $priced_itinerary['air_itinerary']['origin_destination_options'];
     * $flight_segmentsnode = $flight_segmentsnode1[0]['flight_segments'];
     *
     * $originalPrice = $priced_itinerary['amount'] - $this->discount;
     * $newConvertedPrice = $this->get('CurrencyService')->currencyConvert($originalPrice, $conversionRate);
     * $newPrice = number_format($newConvertedPrice, 2, '.', ',');
     * $segmentsGlob['price'] = $newPrice;
     * $segmentsGlob['price_attr'] = $newConvertedPrice;
     * $segmentsGlob['original_price'] = $originalPrice;
     * array_push($secToken, $newPrice, $currencyCode, $newConvertedPrice, $originalPrice);
     * $airline = $this->get('FlightRepositoryServices')->findAirline($flight_segmentsnode[0]['marketing_airline']['code']);
     * $segmentsGlob['airline_name'] = ($airline) ? $airline->getAlternativeBusinessName() : $flight_segmentsnode[0]['marketing_airline']['code'];
     * $operatingAirline = $this->get('FlightRepositoryServices')->findAirline($flight_segmentsnode[0]['operating_airline']['code']);
     * $segmentsGlob['operating_airline_name'] = ($operatingAirline) ? $operatingAirline->getAlternativeBusinessName() : $flight_segmentsnode[0]['operating_airline']['code'];
     *
     * // $bigim = $airline->getLogo();
     * // $dimagepath = 'media/images/airline-logos/';
     * // $segmentsGlob['airline_logo'] = $this->createItemThumbs($bigim, $dimagepath, 0, 0, 88, 50, 'airline-88-50');
     *
     * $airlineLogo = ($airline) ? $airline->getLogo() : $noLogo;
     * $segmentsGlob['airline_logo'] = '/media/images/airline-logos/' . $airlineLogo;
     * $segmentsGlob['airline_logo_mobile'] = '/media/images/airline-logos/mobile/' . $airlineLogo;
     * $segmentsGlob['airline'] = $flight_segmentsnode[0]['marketing_airline']['code'];
     * $segmentsGlob['operating_airline'] = $flight_segmentsnode[0]['operating_airline']['code'];
     * $segmentsGlob['flight_number'] = $flight_segmentsnode[0]['flight_number'];
     * $departure_gmt_offset = '';
     * if (isset($flight_segmentsnode[0]['departure']['time_zone']) && isset($flight_segmentsnode[0]['departure']['time_zone']['gmt_offset']))
     * $departure_gmt_offset = $flight_segmentsnode[0]['departure']['time_zone']['gmt_offset'];
     * $departure_date = $this->get('app.utils')->date_time_parts($flight_segmentsnode[0]['departure']['date_time'], $departure_gmt_offset);
     * // Added by Joel for Filter by Time
     * $segmentsGlob['departure_lowest_original_date_time'] = $flight_segmentsnode[0]['departure']['date_time'];
     * $lowestDepartureDate = new \DateTime($segmentsGlob['departure_lowest_original_date_time']);
     * $segmentsGlob['departure_lowest_date_time_obj'] = $lowestDepartureDate;
     * $segmentsGlob['departure_lowest_timestamp'] = $lowestDepartureDate->getTimeStamp();
     * $segmentsGlob['departure_date_time'] = $departure_date['date'] . '<br/>' . $departure_date['time'];
     * $segmentsGlob['departure_date'] = $departure_date['date'];
     * $segmentsGlob['departure_time'] = $departure_date['time'];
     *
     * $segmentsGlob['departure_airport_code'] = $flight_segmentsnode[0]['departure']['airport']['location_code'];
     *
     * $destinationCityAirport = $this->get('FlightRepositoryServices')->findAirport($segmentsGlob['departure_airport_code']);
     * $segmentsGlob['departure_airport_city'] = ($destinationCityAirport == null) ? $segmentsGlob['departure_airport_code'] : $destinationCityAirport->getCity();
     * $segmentsGlob['departure_airport_name'] = ($destinationCityAirport == null) ? $segmentsGlob['departure_airport_code'] : $destinationCityAirport->getName();
     *
     * $segmentsGlob['arrival_airport_code'] = $flight_segmentsnode[sizeof($flight_segmentsnode) - 1]['arrival']['airport']['location_code'];
     * $arrivalCityAirport = $this->get('FlightRepositoryServices')->findAirport($segmentsGlob['arrival_airport_code']);
     * $segmentsGlob['arrival_airport_city'] = ($arrivalCityAirport == null) ? $segmentsGlob['arrival_airport_code'] : $arrivalCityAirport->getCity();
     * $segmentsGlob['arrival_airport_name'] = ($arrivalCityAirport == null) ? $segmentsGlob['arrival_airport_code'] : $arrivalCityAirport->getName();
     *
     * $arrival_gmt_offset = '';
     * if (isset($flight_segmentsnode[sizeof($flight_segmentsnode) - 1]['arrival']['time_zone']) && isset($flight_segmentsnode[sizeof($flight_segmentsnode) - 1]['arrival']['time_zone']['gmt_offset']))
     * $arrival_gmt_offset = $flight_segmentsnode[sizeof($flight_segmentsnode) - 1]['arrival']['time_zone']['gmt_offset'];
     *
     * $arrival_date = $this->get('app.utils')->date_time_parts($flight_segmentsnode[sizeof($flight_segmentsnode) - 1]['arrival']['date_time'], $arrival_gmt_offset);
     *
     * $segmentsGlob['arrival_lowest_original_date_time'] = $flight_segmentsnode[sizeof($flight_segmentsnode) - 1]['arrival']['date_time'];
     *
     * $lowestArrivalDate = new \DateTime($segmentsGlob['arrival_lowest_original_date_time']);
     *
     * $segmentsGlob['arrival_lowest_date_time_obj'] = $lowestArrivalDate;
     * $segmentsGlob['arrival_lowest_timestamp'] = $lowestArrivalDate->getTimeStamp();
     * $segmentsGlob['arrival_date_time'] = $arrival_date['date'] . '<br/>' . $arrival_date['time'];
     * $segmentsGlob['arrival_date'] = $arrival_date['date'];
     * $segmentsGlob['arrival_time'] = $arrival_date['time'];
     * $segmentsGlob['flight_duration'] = $this->get('app.utils')->duration_to_string($this->get('app.utils')->mins_to_duration($flight_segmentsnode1[0]['flight_duration']));
     * $segmentsGlob['flight_duration_attr'] = $flight_segmentsnode1[0]['flight_duration'];
     * $segmentsGlob['stops'] = sizeof($flight_segmentsnode) - 1;
     * $departureTimeInMinutes = $this->get('app.utils')->getMinutesFromTime($departure_date['time']);
     * $segmentsGlob['departure_time_minutes'] = $departureTimeInMinutes;
     * $arrivalTimeInMinutes = $this->get('app.utils')->getMinutesFromTime($arrival_date['time']);
     * $segmentsGlob['arrival_time_minutes'] = $arrivalTimeInMinutes;
     * $classExactDate = '';
     * if ($oneWay == 1) {
     * if (date("M j Y", strtotime($sabreVariables['FromDate'])) == date("M j Y", strtotime($flight_segmentsnode[0]['departure']['date_time'])) && $flexibleDate) {
     * $classExactDate = 'fly_exactDate';
     * }
     * }
     * for ($i = 1; $i < sizeof($flight_segmentsnode1); $i++) {
     * $flight_segmentsnodeMulti = $flight_segmentsnode1[$i];
     * $segmentsGlob1 = array();
     * $airline = $this->get('FlightRepositoryServices')->findAirline($flight_segmentsnodeMulti['flight_segments'][0]['marketing_airline']['code']);
     * $segmentsGlob1['airline_name1'] = ($airline) ? $airline->getAlternativeBusinessName() : $flight_segmentsnodeMulti['flight_segments'][0]['marketing_airline']['code'];
     * $operatingAirline = $this->get('FlightRepositoryServices')->findAirline($flight_segmentsnodeMulti['flight_segments'][0]['operating_airline']['code']);
     * $segmentsGlob1['operating_airline_name1'] = ($operatingAirline) ? $operatingAirline->getAlternativeBusinessName() : $flight_segmentsnodeMulti['flight_segments'][0]['operating_airline']['code'];
     * // $bigim = $airline->getLogo();
     * // $dimagepath = 'media/images/airline-logos/';
     * // $segmentsGlob1['airline_logo1'] = $this->createItemThumbs($bigim, $dimagepath, 0, 0, 88, 50, 'airline-88-50');
     * $airlineLogo = ($airline) ? $airline->getLogo() : $noLogo;
     * $segmentsGlob1['airline_logo1'] = '/media/images/airline-logos/' . $airlineLogo;
     * $segmentsGlob1['airline_logo_mobile1'] = '/media/images/airline-logos/mobile/' . $airlineLogo;
     * $segmentsGlob1['airline1'] = $flight_segmentsnodeMulti['flight_segments'][0]['marketing_airline']['code'];
     * $segmentsGlob1['operating_airline1'] = $flight_segmentsnodeMulti['flight_segments'][0]['operating_airline']['code'];
     * $segmentsGlob1['flight_number1'] = $flight_segmentsnodeMulti['flight_segments'][0]['flight_number'];
     * $segmentsGlob1['stops1'] = sizeof($flight_segmentsnodeMulti['flight_segments']) - 1;
     * $departure_gmt_offset = '';
     * if (isset($flight_segmentsnodeMulti['flight_segments'][0]['departure']['time_zone']) && isset($flight_segmentsnodeMulti['flight_segments'][0]['departure']['time_zone']['gmt_offset']))
     * $departure_gmt_offset = $flight_segmentsnodeMulti['flight_segments'][0]['departure']['time_zone']['gmt_offset'];
     * $departure_date = $this->get('app.utils')->date_time_parts($flight_segmentsnodeMulti['flight_segments'][0]['departure']['date_time'], $departure_gmt_offset);
     * $segmentsGlob1['departure_date_time1'] = $departure_date['date'] . '<br/>' . $departure_date['time'];
     * $segmentsGlob1['departure_date1'] = $departure_date['date'];
     * $segmentsGlob1['departure_time1'] = $departure_date['time'];
     *
     * $segmentsGlob1['departure_airport_code1'] = $flight_segmentsnodeMulti['flight_segments'][0]['departure']['airport']['location_code'];
     * $destinationCityAirport = $this->get('FlightRepositoryServices')->findAirport($segmentsGlob1['departure_airport_code1']);
     * $segmentsGlob1['departure_airport_city1'] = ($destinationCityAirport == null) ? $segmentsGlob1['departure_airport_code1'] : $destinationCityAirport->getCity();
     * $segmentsGlob1['departure_airport_name1'] = ($destinationCityAirport == null) ? $segmentsGlob1['departure_airport_code1'] : $destinationCityAirport->getName();
     *
     * $segmentsGlob1['arrival_airport_code1'] = $flight_segmentsnodeMulti['flight_segments'][sizeof($flight_segmentsnodeMulti['flight_segments']) - 1]['arrival']['airport']['location_code']; //$flight_segmentsnode[0]['departure']['airport']['location_code'];
     * $arrivalCityAirport = $this->get('FlightRepositoryServices')->findAirport($segmentsGlob1['arrival_airport_code1']);
     * $segmentsGlob1['arrival_airport_city1'] = ($arrivalCityAirport == null) ? $segmentsGlob1['arrival_airport_code1'] : $arrivalCityAirport->getCity();
     * $segmentsGlob1['arrival_airport_name1'] = ($arrivalCityAirport == null) ? $segmentsGlob1['arrival_airport_code1'] : $arrivalCityAirport->getName();
     *
     * $arrival_gmt_offset = '';
     * if (isset($flight_segmentsnodeMulti['flight_segments'][sizeof($flight_segmentsnodeMulti['flight_segments']) - 1]['arrival']['time_zone']) && isset($flight_segmentsnodeMulti['flight_segments'][sizeof($flight_segmentsnodeMulti['flight_segments']) - 1]['arrival']['time_zone']['gmt_offset']))
     * $arrival_gmt_offset = $flight_segmentsnodeMulti['flight_segments'][sizeof($flight_segmentsnodeMulti['flight_segments']) - 1]['arrival']['time_zone']['gmt_offset'];
     *
     * $arrival_date = $this->get('app.utils')->date_time_parts($flight_segmentsnodeMulti['flight_segments'][sizeof($flight_segmentsnodeMulti['flight_segments']) - 1]['arrival']['date_time'], $arrival_gmt_offset);
     * $segmentsGlob1['arrival_date_time1'] = $arrival_date['date'] . '<br/>' . $arrival_date['time'];
     * $segmentsGlob1['arrival_date1'] = $arrival_date['date'];
     * $segmentsGlob1['arrival_time1'] = $arrival_date['time'];
     * $segmentsGlob1['flight_duration1'] = $this->get('app.utils')->duration_to_string($this->get('app.utils')->mins_to_duration($flight_segmentsnodeMulti['flight_duration']));
     * $segmentsGlob1['flight_duration_attr1'] = $flight_segmentsnodeMulti['flight_duration'];
     * $departureTimeInMinutes = $this->get('app.utils')->getMinutesFromTime($departure_date['time']);
     * $segmentsGlob1['departure_time_minutes1'] = $departureTimeInMinutes;
     * $arrivalTimeInMinutes = $this->get('app.utils')->getMinutesFromTime($arrival_date['time']);
     * $segmentsGlob1['arrival_time_minutes1'] = $arrivalTimeInMinutes;
     *
     * if (date("M j Y", strtotime($sabreVariables['FromDate'])) == date("M j Y", strtotime($flight_segmentsnode1[0]['flight_segments'][0]['departure']['date_time'])) && date("M j Y", strtotime($sabreVariables['ToDate'])) == date("M j Y", strtotime($flight_segmentsnodeMulti['flight_segments'][0]['departure']['date_time'])) && $flexibleDate) {
     * $classExactDate = 'fly_exactDate';
     * }
     * $segmentsGlobMulti[] = $segmentsGlob1;
     * }
     * /* } else {
     * if (date("M j Y", strtotime($sabreVariables['FromDate'])) == date("M j Y", strtotime($flight_segmentsnode[0]['departure']['date_time'])) && $flexibleDate) {
     * $classExactDate = 'fly_exactDate';
     * }
     * }
     * $segmentsGlob['classExactDate'] = $classExactDate;
     * $segmentsGlob['segmentsGlobMulti'] = $segmentsGlobMulti;
     *
     * //rudy
     * foreach ($priced_itinerary['air_itinerary']['origin_destination_options'] as $origin_destination_index => $origin_destination_option) {
     * $n_flight_segments = count($origin_destination_option['flight_segments']);
     * if ($minimumDuration == 0) {
     * $minimumDuration = $origin_destination_option['flight_duration'];
     * } else if ($minimumDuration >= $origin_destination_option['flight_duration']) {
     * $minimumDuration = $origin_destination_option['flight_duration'];
     * }
     *
     * if ($maximumDuration == 0) {
     * $maximumDuration = $origin_destination_option['flight_duration'];
     * } else if ($maximumDuration <= $origin_destination_option['flight_duration']) {
     * $maximumDuration = $origin_destination_option['flight_duration'];
     * }
     *
     * foreach ($origin_destination_option['flight_segments'] as $flight_segment_index => $flight_segment) {
     * $departure_airport = $flight_segment['departure']['airport'];
     * $arrival_airport = $flight_segment['arrival']['airport'];
     *
     * $departure_gmt_offset = '';
     *
     * if (isset($flight_segment['departure']['time_zone']) && isset($flight_segment['departure']['time_zone']['gmt_offset']))
     * $departure_gmt_offset = $flight_segment['departure']['time_zone']['gmt_offset'];
     *
     * $departure_date = $this->get('app.utils')->date_time_parts($flight_segment['departure']['date_time'], $departure_gmt_offset);
     *
     * $arrival_gmt_offset = '';
     *
     * if (isset($flight_segment['arrival']['time_zone']) && isset($flight_segment['arrival']['time_zone']['gmt_offset']))
     * $arrival_gmt_offset = $flight_segment['arrival']['time_zone']['gmt_offset'];
     *
     * $arrival_date = $this->get('app.utils')->date_time_parts($flight_segment['arrival']['date_time'], $arrival_gmt_offset);
     *
     * $airline = $this->get('FlightRepositoryServices')->findAirline($flight_segment['marketing_airline']['code']);
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['airline_name'] = ($airline) ? $airline->getAlternativeBusinessName() : $flight_segment['marketing_airline']['code'];
     * $operatingAirline = $this->get('FlightRepositoryServices')->findAirline($flight_segment['operating_airline']['code']);
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['operating_airline_name'] = ($operatingAirline) ? $operatingAirline->getAlternativeBusinessName() : $flight_segment['operating_airline']['code'];
     * // $bigim = $airline->getLogo();
     * // $dimagepath = 'media/images/airline-logos/';
     * // $segmentsdata[$origin_destination_index][$flight_segment_index]['airline_logo'] = $this->createItemThumbs($bigim, $dimagepath, 0, 0, 88, 50, 'airline-88-50');
     * $airlineLogo = ($airline) ? $airline->getLogo() : $noLogo;
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['airline_logo'] = '/media/images/airline-logos/' . $airlineLogo;
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['airline_logo_mobile'] = '/media/images/airline-logos/mobile/' . $airlineLogo;
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['airline'] = $flight_segment['marketing_airline']['code'];
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['operating_airline'] = $flight_segment['operating_airline']['code'];
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['flight_number'] = $flight_segment['flight_number'];
     * array_push($secToken, $flight_segment['flight_number'], $flight_segment['marketing_airline']['code'], $flight_segment['operating_airline']['code'], $segmentsdata[$origin_destination_index][$flight_segment_index]['airline_name'], $segmentsdata[$origin_destination_index][$flight_segment_index]['operating_airline_name']);
     *
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['departure_date_time'] = $departure_date['date'] . '<br/>' . $departure_date['time'];
     *
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['departure_airport_code'] = $departure_airport['location_code'];
     * $departureCityAirport = $this->get('FlightRepositoryServices')->findAirport($departure_airport['location_code']);
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['departure_airport_city'] = ($departureCityAirport == null) ? $departure_airport['location_code'] : $departureCityAirport->getCity();
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['departure_airport_name'] = ($departureCityAirport == null) ? $departure_airport['location_code'] : $departureCityAirport->getName();
     * array_push($secToken, $departure_airport['location_code'], $segmentsdata[$origin_destination_index][$flight_segment_index]['departure_airport_city'], $segmentsdata[$origin_destination_index][$flight_segment_index]['departure_airport_name']);
     *
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['arrival_airport_code'] = $arrival_airport['location_code'];
     * $arrivalCityAirport = $this->get('FlightRepositoryServices')->findAirport($arrival_airport['location_code']);
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['arrival_airport_city'] = ($arrivalCityAirport == null) ? $arrival_airport['location_code'] : $arrivalCityAirport->getCity();
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['arrival_airport_name'] = ($arrivalCityAirport == null) ? $arrival_airport['location_code'] : $arrivalCityAirport->getName();
     * array_push($secToken, $arrival_airport['location_code'], $segmentsdata[$origin_destination_index][$flight_segment_index]['arrival_airport_city'], $segmentsdata[$origin_destination_index][$flight_segment_index]['arrival_airport_name']);
     *
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['arrival_date_time'] = $arrival_date['date'] . '<br/>' . $arrival_date['time'];
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['total_flight_segments'] = $n_total_flight_segments;
     * array_push($secToken, $n_total_flight_segments);
     *
     * // Hidden fields
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['segment_count'] = $segmentCount;
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['original_departure_date_time'] = $flight_segment['departure']['date_time'];
     * array_push($secToken, $flight_segment['departure']['date_time']);
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['original_arrival_date_time'] = $flight_segment['arrival']['date_time'];
     * array_push($secToken, $flight_segment['arrival']['date_time']);
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['res_book_desig_code'] = $flight_segment['res_book_desig_code'];
     * array_push($secToken, $flight_segment['res_book_desig_code']);
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['flight_type'] = ($origin_destination_option["origin_destination_index"] == 1 && !$multiDestination) ? "returning" : "leaving";
     * array_push($secToken, $segmentsdata[$origin_destination_index][$flight_segment_index]['flight_type']);
     * $cabin = $this->get('FlightRepositoryServices')->FlightCabinFinder($priced_itinerary['fare_info'][$flight_segment_index]['cabin']);
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['cabin'] = ($cabin) ? $cabin->getName() : $priced_itinerary['fare_info'][$flight_segment_index]['cabin'];
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['cabin_code'] = $priced_itinerary['fare_info'][$flight_segment_index]['cabin'];
     * array_push($secToken, $segmentsdata[$origin_destination_index][$flight_segment_index]['cabin'], $segmentsdata[$origin_destination_index][$flight_segment_index]['cabin_code']);
     *
     * $departureTimeInMinutes = $this->get('app.utils')->getMinutesFromTime($departure_date['time']);
     * $arrivalTimeInMinutes = $this->get('app.utils')->getMinutesFromTime($arrival_date['time']);
     *
     * // Time in minutes to calculate stop duration
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['departure_time_minutes_stop'] = $departureTimeInMinutes;
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['arrival_time_minutes_stop'] = $arrivalTimeInMinutes;
     *
     * if (!($n_flight_segments > 1 && $flight_segment_index)) {
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['flight_duration'] = $this->get('app.utils')->duration_to_string($this->get('app.utils')->mins_to_duration($origin_destination_option['flight_duration']));
     * array_push($secToken, $segmentsdata[$origin_destination_index][$flight_segment_index]['flight_duration']);
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['flight_duration_attr'] = $origin_destination_option['flight_duration'];
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['departure_time_minutes'] = $departureTimeInMinutes;
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['arrival_time_minutes'] = $arrivalTimeInMinutes;
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['departure_time_css_class'] = ($origin_destination_option["origin_destination_index"] == 1) ? "returning-departure-time-minutes" : "leaving-departure-time-minutes";
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['arrival_time_css_class'] = ($origin_destination_option["origin_destination_index"] == 1) ? "returning-arrival-time-minutes" : "leaving-arrival-time-minutes";
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['duration_css_class'] = ($origin_destination_option["origin_destination_index"] == 1) ? "returning-duration" : "leaving-duration";
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['stops_css_class'] = ($origin_destination_option["origin_destination_index"] == 1) ? "returning-stops" : "leaving-stops";
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['stops'] = $n_flight_segments - 1;
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['stop_duration'] = 0;
     * array_push($secToken, $segmentsdata[$origin_destination_index][$flight_segment_index]['stop_duration']);
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['stop_city'] = "";
     * // Hidden field
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['stop_indicator'] = 0;
     * array_push($secToken, $segmentsdata[$origin_destination_index][$flight_segment_index]['stop_indicator']);
     * } else {
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['flight_duration'] = "";
     * array_push($secToken, $segmentsdata[$origin_destination_index][$flight_segment_index]['flight_duration']);
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['flight_duration_attr'] = "";
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['departure_time_minutes'] = "";
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['arrival_time_minutes'] = "";
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['departure_time_css_class'] = "";
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['arrival_time_css_class'] = "";
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['duration_css_class'] = "";
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['stops_css_class'] = "";
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['stops'] = "";
     * $stopDuration = $segmentsdata[$origin_destination_index][$flight_segment_index]['departure_time_minutes_stop'] - $segmentsdata[$origin_destination_index][$flight_segment_index - 1]['arrival_time_minutes_stop'];
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['stop_duration'] = ($stopDuration > 0) ? $this->get('app.utils')->duration_to_string($this->get('app.utils')->mins_to_duration($stopDuration)) : $this->get('app.utils')->duration_to_string($this->get('app.utils')->mins_to_duration(1440 + $stopDuration));
     * array_push($secToken, $segmentsdata[$origin_destination_index][$flight_segment_index]['stop_duration']);
     * $stopCity = $this->get('FlightRepositoryServices')->findAirport($departure_airport['location_code']);
     * $action_array = array();
     * $action_array[] = ($stopCity == null) ? $departure_airport['location_code'] : $stopCity->getCity();
     * $action_array[] = $segmentsdata[$origin_destination_index][$flight_segment_index]['stop_duration'];
     * $action_text_display = vsprintf($this->translator->trans("Layover in %s for %s", array(), 'seo'), $action_array);
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['stop_city'] = $action_text_display;
     * // Hidden field
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['stop_indicator'] = 1;
     * array_push($secToken, $segmentsdata[$origin_destination_index][$flight_segment_index]['stop_indicator']);
     * }
     *
     * if (!($n_total_flight_segments > 1 && ($origin_destination_index || $flight_segment_index))) {
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['price'] = $priced_itinerary['currency_code'] . ' ' . $newConvertedPrice;
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['price_attr'] = $newConvertedPrice;
     *
     * if ($minimumPrice == 0) {
     * $minimumPrice = $newConvertedPrice;
     * } else if ($minimumPrice >= $newConvertedPrice) {
     * $minimumPrice = $newConvertedPrice;
     * }
     *
     * if ($maximumPrice == 0) {
     * $maximumPrice = $newConvertedPrice;
     * } else if ($maximumPrice <= $newConvertedPrice) {
     * $maximumPrice = $newConvertedPrice;
     * }
     * } else {
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['price'] = "";
     * $segmentsdata[$origin_destination_index][$flight_segment_index]['price_attr'] = "";
     * }
     *
     * if (!array_key_exists($flight_segment['marketing_airline']['code'], $airlines)) {
     * $airlines[$flight_segment['marketing_airline']['code']]['name'] = ($airline) ? $airline->getAlternativeBusinessName() : $flight_segment['marketing_airline']['code'];
     * $airlines[$flight_segment['marketing_airline']['code']]['amount_attr'] = $newConvertedPrice;
     * $airlines[$flight_segment['marketing_airline']['code']]['amount'] = $newPrice;
     * } else if ($airlines[$flight_segment['marketing_airline']['code']] <= $newPrice) {
     * $airlines[$flight_segment['marketing_airline']['code']]['name'] = ($airline) ? $airline->getAlternativeBusinessName() : $flight_segment['marketing_airline']['code'];
     * $airlines[$flight_segment['marketing_airline']['code']]['amount'] = $newConvertedPrice;
     * $airlines[$flight_segment['marketing_airline']['code']]['amount_attr'] = $newPrice;
     * }
     *
     * $segmentsArray[$sequence_numberNew]['segments'] = $segmentsdata;
     * $segmentsArray[$sequence_numberNew]['segmentsGlob'] = $segmentsGlob;
     * $segmentsArray[$sequence_numberNew]['non_refundable_css_class'] = $priced_itinerary['non_refundable'] == "true" ? 'non-refundable' : 'refundable';
     * $segmentsArray[$sequence_numberNew]['non_refundable'] = $priced_itinerary['non_refundable'] == "true" ? $this->translator->trans('non refundable') : $this->translator->trans('refundable');
     * $segmentsArray[$sequence_numberNew]['refundable'] = $priced_itinerary['non_refundable'] == "true" ? 0 : 1;
     * $baseFare = $this->get('CurrencyService')->currencyConvert($priced_itinerary['base_fare'] - $this->discount, $conversionRate);
     * $segmentsArray[$sequence_numberNew]['original_base_fare'] = $priced_itinerary['base_fare'];
     * $segmentsArray[$sequence_numberNew]['base_fare'] = number_format($baseFare, 2, '.', ',');
     * $segmentsArray[$sequence_numberNew]['base_fare_attr'] = $baseFare;
     * $taxes = $this->get('CurrencyService')->currencyConvert($priced_itinerary['taxes'], $conversionRate);
     * $segmentsArray[$sequence_numberNew]['original_taxes'] = $priced_itinerary['taxes'];
     * $segmentsArray[$sequence_numberNew]['taxes'] = number_format($taxes, 2, '.', ',');
     * $segmentsArray[$sequence_numberNew]['taxes_attr'] = $taxes;
     *
     * $segmentsArray[$sequence_numberNew]['marketing_airline'] = $flight_segment['marketing_airline']['code'];
     * $segmentsArray[$sequence_numberNew]['operating_airline'] = $flight_segment['operating_airline']['code'];
     * $segmentsArray[$sequence_numberNew]['seats_remaining'] = $priced_itinerary['seats_remaining'];
     *
     * $segmentCount++;
     * }
     * }
     * array_push($secToken, $segmentsArray[$sequence_numberNew]['refundable'], $segmentsArray[$sequence_numberNew]['base_fare'], $segmentsArray[$sequence_numberNew]['taxes'], $segmentsArray[$sequence_numberNew]['taxes_attr'], $segmentsArray[$sequence_numberNew]['base_fare_attr'], $segmentsArray[$sequence_numberNew]['original_base_fare'], $segmentsArray[$sequence_numberNew]['original_taxes']);
     * foreach ($priced_itinerary['passenger_info'] as $passenger_info_index => $passengerInfo) {
     * $segmentsArray[$sequence_numberNew]['passenger_info'][$passenger_info_index]['type_code'] = $passengerInfo['code'];
     * $segmentsArray[$sequence_numberNew]['passenger_info'][$passenger_info_index]['quantity'] = $passengerInfo['quantity'];
     * array_push($secToken, $passengerInfo['quantity']);
     * $baggageInformation = '';
     * if (isset($passengerInfo['baggage_info'])) {
     * foreach ($passengerInfo['baggage_info'] as $baggage_info_index => $baggageInfo) {
     * $flightType = ($baggage_info_index === 0) ? 'leaving_baggage_info' : 'returning_baggage_info';
     * if (isset($baggageInfo['pieces'])) {
     * $unit = ($baggageInfo['pieces'] == 1) ? " piece" : " pieces";
     * $baggageInformation = $baggageInfo['pieces'] . $unit;
     * } else {
     * $baggageInformation = $baggageInfo['weight'] . $baggageInfo['unit'];
     * }
     * $segmentsArray[$sequence_numberNew]['passenger_info'][$passenger_info_index][$flightType] = $baggageInformation;
     * array_push($secToken, $segmentsArray[$sequence_numberNew]['passenger_info'][$passenger_info_index][$flightType]);
     * }
     * } else {
     * $segmentsArray[$sequence_numberNew]['passenger_info'][$passenger_info_index]['leaving_baggage_info'] = 0;
     * $segmentsArray[$sequence_numberNew]['passenger_info'][$passenger_info_index]['returning_baggage_info'] = 0;
     * array_push($secToken, $segmentsArray[$sequence_numberNew]['passenger_info'][$passenger_info_index]['leaving_baggage_info'], $segmentsArray[$sequence_numberNew]['passenger_info'][$passenger_info_index]['returning_baggage_info']);
     * }
     * $segmentsArray[$sequence_numberNew]['passenger_info'][$passenger_info_index]['fare_calculation_line'] = $passengerInfo['fare_calculation_line'];
     * array_push($secToken, $passengerInfo['fare_calculation_line']);
     * }
     * array_push($secToken, $multiDestination, $oneWay);
     * sort($secToken, SORT_STRING);
     * $secTokenStr = implode(" ", $secToken);
     * $segmentsArray[$sequence_numberNew]['sec_token'] = crypt($secTokenStr, $sabreVariables['salt']);
     * $sequence_numberNew++;
     * }
     * } else {
     * $this->data['no_data'] = true;
     * $this->data['no_filter_css'] = 'no-filter';
     * }
     *
     * $this->get('SabreServices')->closeSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
     * $this->addFlightLog('Requesting API SessionCloseRQ');
     *
     * if ($from_mobile == 1) {
     * $ret['segmentsArray'] = $segmentsArray;
     * $ret['airlines'] = $airlines;
     * $ret['currency'] = $currency;
     * $ret['currency_code'] = $currencyCode;
     * $ret['minimum_duration'] = $minimumDuration;
     * $ret['maximum_duration'] = $maximumDuration;
     * $ret['minimum_price'] = $minimumPrice;
     * $ret['maximum_price'] = $maximumPrice;
     * $ret['one_way'] = $oneWay;
     * $ret['multi_destination'] = $multiDestination;
     * $ret['num_in_party'] = $numberInParty;
     * $ret['access_token'] = $sabreVariables['access_token'];
     * $ret['returned_conversation_id'] = $returnedConversationId;
     * $res = new Response(json_encode($ret));
     * $this->addFlightLog('Sending MobileRQ with response: {response}', array('response' => $res));
     * $res->headers->set('Content-Type', 'application/json');
     * return $res;
     * } else {
     * $this->data['segmentsArray'] = $segmentsArray;
     * $this->data['airlines'] = $airlines;
     * $this->data['currency'] = $currency;
     * $this->data['currency_code'] = $currencyCode;
     * $this->data['minimum_duration'] = $minimumDuration;
     * $this->data['maximum_duration'] = $maximumDuration;
     * $this->data['minimum_price'] = $minimumPrice;
     * $this->data['maximum_price'] = $maximumPrice;
     * $this->data['num_in_party'] = $numberInParty;
     * $this->data['one_way'] = $oneWay;
     * $this->data['multi_destination'] = $multiDestination;
     * return $this->render('@Flight/flight/flight-booking-result.twig', $this->data);
     * }
     * } else {
     * if ($from_mobile == 1) {
     * $ret['error'] = $create_session_response['message'];
     * $res = new Response(json_encode($ret));
     * $this->addFlightLog('Sending MobileRQ with response: {response}', array('response' => $res));
     * $res->headers->set('Content-Type', 'application/json');
     * return $res;
     * }
     *
     * $this->data['error'] = $create_session_response['message'];
     * return $this->render('@Flight/flight/flight-booking-result.twig', $this->data);
     * }
     *
     *
     * }
     */
}
