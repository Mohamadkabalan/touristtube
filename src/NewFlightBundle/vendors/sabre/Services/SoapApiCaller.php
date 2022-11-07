<?php
/**
 * Created by PhpStorm.
 * User: para-soft7
 * Date: 9/13/2018
 * Time: 6:40 PM
 */

namespace NewFlightBundle\vendors\sabre\Services;

use NewFlightBundle\vendors\sabre\requests\SabreRequestHeader;
use TTBundle\Utils\Utils;
use NewFlightBundle\vendors\sabre\requests\SabreCreateSessionRequest;
use NewFlightBundle\vendors\sabre\requests\SabreCreateBargainRequest;
use NewFlightBundle\vendors\sabre\requests\SabreEnhancedAirBookRequest;
use NewFlightBundle\vendors\sabre\requests\SabrePassengerDetailsRequest;
use NewFlightBundle\vendors\sabre\requests\SabreContextChangeRequest;
use NewFlightBundle\vendors\sabre\requests\SabreTravelItineraryReadRequest;
use NewFlightBundle\vendors\sabre\requests\SabreDesignatePrinterRequest;
use NewFlightBundle\vendors\sabre\requests\SabreAirTicketRequest;
use NewFlightBundle\vendors\sabre\requests\SabreEndTransactionRequest;
use NewFlightBundle\vendors\sabre\requests\SabreCloseSessionRequest;
use NewFlightBundle\vendors\sabre\requests\SabreVoidAirTicketRQ;
use NewFlightBundle\vendors\sabre\requests\SabreOTACancelRequest;
use NewFlightBundle\Model\APICredentials;
use NewFlightBundle\Model\CreateBargainRequest;
use NewFlightBundle\vendors\sabre\Config as SabreConfig;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SoapApiCaller
{
    protected $utils;
    protected $xmlResponseParse;
    protected $sabreConfig;

    // none, basic, digest
    private $ADDITIONAL_HEADERS = array(
        "Content-Type" => "text/xml;charset=UTF-8",
        "SOAPAction" => "OTA",
        "Connection" => "Keep-Alive",
        "Accept-Encoding" => "gzip"
    );

    public function __construct(XmlResponseParser $xmlResponseParser, Utils $utils, SabreConfig $sabreConfig)
    {
        $this->xmlResponseParse = $xmlResponseParser;
        $this->utils            = $utils;
        $this->sabreConfig      = $sabreConfig;

        $this->TEST_MODE          = $this->sabreConfig->TEST_MODE;
        $this->HTTP_AUTH_METHOD   = $this->sabreConfig->HTTP_AUTH_METHOD;
        $this->URL                = ($this->TEST_MODE) ? $this->sabreConfig->HTTP_TEST_URL : $this->sabreConfig->HTTP_PROD_URL;
        $this->HTTP_AUTH_USER     = ($this->TEST_MODE) ? $this->sabreConfig->HTTP_TEST_USERNAME : $this->sabreConfig->HTTP_PROD_USERNAME;
        $this->HTTP_AUTH_PASSWORD = ($this->TEST_MODE) ? $this->sabreConfig->HTTP_TEST_PASSWORD : $this->sabreConfig->HTTP_PROD_PASSWORD;
        $this->PROJECTIPCC        = ($this->TEST_MODE) ? $this->sabreConfig->PROJECTIPCC_TEST : $this->sabreConfig->PROJECTIPCC_PROD;
        $this->PROJECTPASSWORD    = ($this->TEST_MODE) ? $this->sabreConfig->PROJECTPASSWORD_TEST : $this->sabreConfig->PROJECTPASSWORD_PROD;
        $this->PROJECTUSERNAME    = ($this->TEST_MODE) ? $this->sabreConfig->PROJECTUSERNAME_TEST : $this->sabreConfig->PROJECTUSERNAME_PROD;
        $this->PROJECTDOMAIN      = $this->sabreConfig->PROJECTDOMAIN;
        $this->PROJECTPCC         = $this->sabreConfig->PROJECTPCC;
        $this->PARTY_ID_FROM      = $this->sabreConfig->PARTY_ID_FROM;
        $this->PARTY_ID_TO        = $this->sabreConfig->PARTY_ID_TO;
        $this->COMPANYNAME        = $this->sabreConfig->COMPANYNAME;
    }

    public function createSession()
    {
        //Get sabre Variables for requests
        $createSessionRequest = $this->getSabreConnectionVariables();

        $sessionRequest = new SabreCreateSessionRequest();

        $sessionRequestXml = $sessionRequest->createSessionRequest($createSessionRequest);

        $getSessionResponse = $this->utils->send_data($this->URL, $sessionRequestXml, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);

        // parse response
        $parsedResponse = $this->xmlResponseParse->parseCreateSessionResponse($getSessionResponse);

        return $parsedResponse;
    }

    // close session request
    public function closeSession($session, $source = 'web')
    {
        // create sabre request header
        $sabreRequestHeader = new SabreRequestHeader();

        // create SabreCloseSessionRequest
        $closeSessionReq = new SabreCloseSessionRequest();

        $sabreVariables = $this->getSabreConnectionVariables();
        $sabreVariables->setService('SessionCloseRQ');
        $sabreVariables->setAction('SessionCloseRQ');

        $closeSession = $sabreRequestHeader->requestHeader($sabreVariables, $session).$closeSessionReq->closeSessionRequest($sabreVariables);

        $getCloseSessionRequest = $this->utils->send_data($this->URL, $closeSession, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
            ), $this->ADDITIONAL_HEADERS);

        // parse response
        echo "<br/> parsedResponse:";
        $parsedResponse = $this->xmlResponseParse->parseCloseResponse($getCloseSessionRequest);
        return $parsedResponse;
    }

    // Send a bargain finder max to get the availability
    public function createBargainFinderMax(CreateBargainRequest $bargainRequest, $createSession)
    {
        // create sabre request header
        $sabreRequestHeader = new SabreRequestHeader();

        // create sabre bfm request
        $createBFMRequest = new SabreCreateBargainRequest();

        $sabreVariables = $this->getSabreConnectionVariables();
        //$createBargainRequestParams = $this->getCreateBargainRequestParams($params);

        $sabreVariables->setService('BargainFinderMaxRQ');
        $sabreVariables->setAction('BargainFinderMaxRQ');

        $xmlBargainRequest = $sabreRequestHeader->requestHeader($sabreVariables,$createSession) . $createBFMRequest->createBargainRequest($sabreVariables, $bargainRequest);

        $getBFMResponse = $this->utils->send_data($this->URL, $xmlBargainRequest, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);
        //parse response

        echo "<br/> parsedResponse:";
        $parsedResponse = $this->xmlResponseParse->parseBargainFinderResponse($getBFMResponse, $bargainRequest);

        return $parsedResponse;
    }

    /**
     * Renew timestamp values for a given array of Sabre variables.
     *
     */
    public function renewTimestamps()
    {
        $sabreVariables = $this->getSabreConnectionVariables();
        $sabreVariables->setService('SessionCloseRQ');
        $sabreVariables->setAction('SessionCloseRQ');

        $sabreVariables->setTimestamp(date("Y-m-d\TH:i:s\Z", strtotime("now")));
        $sabreVariables->setTimeToLive(date("Y-m-d\TH:i:s\Z", strtotime("now +15 minutes")));
        return $sabreVariables;
    }

    /**
     * this function to get the Sabre Connection variable, that contains the header of each envelope send to the Flight Provider Sabre,
     *
     * @param boolean $on_production_server
     *            to change from sabre test server to sabre production server, this variable is used, so it checks if the enviroment is a production so it uses the production links of sabre, else it uses the test links
     * @return array of sabre connection variable that are sent in the head of each request
     */
    public function getSabreConnectionVariables($on_production_server = false)
    {
        /* $options = array(
          'on_production_server' => $on_production_server
          ); */

        $global_id = $this->utils->GUID();

        $ConversationId = $global_id.'@touristtube.com';
        $message_id     = 'mid:'.$global_id.'@touristtube.com';
        $Timestamp      = date("Y-m-d\TH:i:s\Z", strtotime("now"));
        $TimeToLive     = date("Y-m-d\TH:i:s\Z", strtotime("now +15 minutes"));
        $salt           = md5('TT497219');

        $apiCredentials = new APICredentials();

        $apiCredentials->setOnProductionServer($on_production_server);
        $apiCredentials->setIsTestMode($this->TEST_MODE);
        $apiCredentials->setProjectUserName($this->PROJECTUSERNAME);
        $apiCredentials->setCompanyName($this->COMPANYNAME);
        $apiCredentials->setProjectIPCC($this->PROJECTIPCC);
        $apiCredentials->setProjectPCC($this->PROJECTPCC);
        $apiCredentials->setProjectPassword($this->PROJECTPASSWORD);
        $apiCredentials->setProjectDomain($this->PROJECTDOMAIN);
        $apiCredentials->setConversationId($ConversationId);
        $apiCredentials->setMessageId($message_id);
        $apiCredentials->setPartyIdFrom($this->PARTY_ID_FROM);
        $apiCredentials->setPartyIdTo($this->PARTY_ID_TO);
        $apiCredentials->setTimestamp($Timestamp);
        $apiCredentials->setTimeToLive($TimeToLive);
        $apiCredentials->setSalt($salt);

        return $apiCredentials;    
    }

    /**
     * this function call the api enhanced to hold seats for users who press's book button after choosing one flight from the result of createBargainRequest
     *
     * @param object $enhancedAirBookRequest
     * @param object $session
     * @return array return's array that contains status that's success in case of success, and the totalAmount with the currency Code
     */
    public function createEnhancedAirBookRequest($enhancedAirBookRequest, $session)
    {
        // create sabre request header
        $sabreRequestHeader = new SabreRequestHeader();

        // create sabre enhanced air book request
        $sabreEnhancedAirBookRequest = new SabreEnhancedAirBookRequest();

        $sabreVariables = $this->getSabreConnectionVariables();
        $sabreVariables->setService('EnhancedAirBookRQ');
        $sabreVariables->setAction('EnhancedAirBookRQ');

        $airBookRequest = $sabreRequestHeader->requestHeader($sabreVariables, $session) . $sabreEnhancedAirBookRequest->enhancedAirBookRequest($enhancedAirBookRequest);

        $enhancedAirBookResponse = $this->utils->send_data($this->URL, $airBookRequest, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);

        // parse response
        //echo "<br/> parsedResponse:";
        $parsedResponse = $this->xmlResponseParse->parseEnhancedAirBookResponse($enhancedAirBookResponse);

        return $parsedResponse;

    }

    /**
     * After holding the seats by calling the createEnhancedBookRequest, this function is responsible to create a PNR id for this passenger, based on the information subimeted by the user using the form
     *
     * @param array $passengerDetailsRequest
     * @param array $session
     *
     * @return array get status, and get the pnr code created by sabre
     */
    public function createPassengerDetailsRequest($passengerDetailsRequest, $session)
    {
        // create sabre request header
        $sabreRequestHeader = new SabreRequestHeader();

        // create sabre enhanced air book request
        $sabrePassengerDetailsRequest = new SabrePassengerDetailsRequest();

        $sabreVariables = $this->getSabreConnectionVariables();
        $sabreVariables->setService('PassengerDetailsRQ');
        $sabreVariables->setAction('PassengerDetailsRQ');

        $passengerDetails = $sabreRequestHeader->requestHeader($sabreVariables, $session) . $sabrePassengerDetailsRequest->passengerDetailsRequest($passengerDetailsRequest);

        $getPassengerDetailsResponse = $this->utils->send_data($this->URL, $passengerDetails, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);
  
        // parse response
        //echo "<br/> parsedResponse:";
        $parsedResponse = $this->xmlResponseParse->parsePassengerDetailsResponse($getPassengerDetailsResponse);

        return $parsedResponse;
    }

    /**
     * in this function we change our PCC, to the IATA Provider PCC, in this case we are changing from our PCC, to DANATA PCC to issue ticket.
     *
     * @param object $session
     *
     * @return array contains the request_bpdy and response_body, only for debugging, main result is the status
     */
    public function contextChangeRequest($session)
    {
        // create sabre request header
        $sabreRequestHeader = new SabreRequestHeader();

        // create sabre enhanced air book request
        $sabreContextChangeRequest = new SabreContextChangeRequest();

        $sabreVariables = $this->getSabreConnectionVariables();
        $sabreVariables->setService('ContextChangeLLSRQ');
        $sabreVariables->setAction('ContextChangeLLSRQ');

        $contextChange = $sabreRequestHeader->requestHeader($sabreVariables, $session).$sabreContextChangeRequest->contextChangeRequest();

        $getContextChange = $this->utils->send_data($this->URL, $contextChange, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);
        
        // parse response
        echo "<br/> parsedResponse:";
        $parsedResponse = $this->xmlResponseParse->parseContextChangeResponse($getContextChange);

        return $parsedResponse;

    }

    /**
     * in this function a PNR id is sent to the itinerary api to get all inforamtion about this PNR id, in case the ticket is not issued, the ticket is empty, else the tickets contains ticket number
     *
     * @param string $pnrId
     *            pnrId get from createPassengerDetails
     * @param array $options
     *            Provide options like check_airline_locators (boolean, when false (default value), this function retrieves the tickets), check_first_segment_only (boolean), and fetch_airline_locators (boolean, use true to fetch the values of the Airline Locators)
     * @param object $session
     * @param string $source
     *            the source if mobile or web that they are using this function
     * @return array that contains all the information about the PNR
     */
    public function createTravelItineraryRequest($pnrId, $options = array(), $session, $source = 'web')
    {
        // create sabre request header
        $sabreRequestHeader = new SabreRequestHeader();

        // create sabre enhanced air book request
        $sabreTravelItineraryReadRequest = new SabreTravelItineraryReadRequest();

        $sabreVariables = $this->getSabreConnectionVariables();
        $sabreVariables->setService('TravelItineraryReadRQ');
        $sabreVariables->setAction('TravelItineraryReadRQ');

        $travelItinerary = $sabreRequestHeader->requestHeader($sabreVariables, $session).$sabreTravelItineraryReadRequest->travelItineraryRequest($pnrId);

        $getTravelItineraryRequest = $this->utils->send_data($this->URL, $travelItinerary, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);

        // parse response
        echo "<br/> ....parsedResponse:";
        $parsedResponse = $this->xmlResponseParse->parseTravelItineraryResponse($getTravelItineraryRequest, $options);

        return $parsedResponse;

    }

    /**
     * designate Printer is when we reserve a printer, to print the ticket, depending on the parameter $rq we know if the request is a hardcopy printer, or an e-ticket printer
     *
     * @param integer $rq this parameter decide if the printer is a hard copy printer or an e-ticket printer, if $rq = 1 e-ticket printer, if $rq = 2 hardcopy printer
     * @param object $session
     *
     * @return array contains the request_bpdy and response_body, only for debugging, main result is the status
     */
    public function designatePrinterRequest($rq, $session)
    {
        // create sabre request header
        $sabreRequestHeader = new SabreRequestHeader();

        $sabreDesignatePrinteRequest = new SabreDesignatePrinterRequest();

        $sabreVariables = $this->getSabreConnectionVariables();
        $sabreVariables->setService('DesignatePrinterLLSRQ');
        $sabreVariables->setAction('DesignatePrinterLLSRQ');

        $designatePrinter = $sabreRequestHeader->requestHeader($sabreVariables, $session).$sabreDesignatePrinteRequest->DesignatePrinterRequest($rq);

        $getDesignatePrinterRequest = $this->utils->send_data($this->URL, $designatePrinter, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);

        // parse response
        echo "<br/> parsedResponse:";
        $parsedResponse = $this->xmlResponseParse->parseDesignatePrinterResponse($getDesignatePrinterRequest);

        return $parsedResponse;
    }

    /**
     * this function is to issue the ticket choosen by the user, and issued using the IATA IPCC.
     *
     * @param object $session
     * @param object $passengers: contains information if the passenger is Adult, Children or infant to build the request
     *
     * @return array contains the request_bpdy and response_body, only for debugging, main result is the status
     */
    public function airTicketRequest($passengers, $session)
    {
        // create sabre request header
        $sabreRequestHeader    = new SabreRequestHeader();
        $sabreAirTicketRequest = new SabreAirTicketRequest();

        $sabreVariables = $this->getSabreConnectionVariables();
        $sabreVariables->setService('AirTicketLLSRQ');
        $sabreVariables->setAction('AirTicketLLSRQ');

        $airTicket = $sabreRequestHeader->requestHeader($sabreVariables, $session).$sabreAirTicketRequest->airTicketRq($passengers);

        $getAirTicketRequest = $this->utils->send_data($this->URL, $airTicket, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);
        
        // parse response
        echo "<br/> parsedResponse:";
        $parsedResponse = $this->xmlResponseParse->parseAirTicketResponseResponse($getAirTicketRequest);

        return $parsedResponse;
    }

    /**
     * this is an IMPORTANT function, this is where all the workflow, of issuing a ticket or anyother worflow with sabre is saved, if this request is not sent is like we did nothing, this is the save function.
     *
     * @param object $session
     * @param string $source
     *            the source is used by the connection pool manager, to now if the cronJob is using specific session, or normal user on the web
     * @return array contains the request_bpdy and response_body, only for debugging, main result is the status
     */
    public function endTransactionRequest($session, $source = 'web')
    {
        // create sabre request header
        $sabreRequestHeader = new SabreRequestHeader();

        $sabreEndTransactionRequest = new SabreEndTransactionRequest();

        $sabreVariables = $this->getSabreConnectionVariables();
        $sabreVariables->setService('EndTransactionLLSRQ');
        $sabreVariables->setAction('EndTransactionLLSRQ');

        $endTransaction = $sabreRequestHeader->requestHeader($sabreVariables, $session).$sabreEndTransactionRequest->endTransactionRq();

        $getEndTransactionRequest = $this->utils->send_data($this->URL, $endTransaction, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);

        // parse response
        echo "<br/> parsedResponse:";
        $parsedResponse = $this->xmlResponseParse->parseEndTransactionRespone($getEndTransactionRequest);

        return $parsedResponse;
    }

    /**
     * Call Void Ticket API
     * @param       $rph
     * @param       $session
     * @param array $options
     *
     * @return mixed
     */
    public function voidAirTicket($rph, $session, $options = array())
    {
        // create sabre request header
        $sabreRequestHeader = new SabreRequestHeader();

        // create sabre VoidAirTicket request
        $sabreVoidAirTicketRQ = new SabreVoidAirTicketRQ();

        $sabreVariables = $this->getSabreConnectionVariables();

        $sabreVariables->setService('VoidTicketLLSRQ');
        $sabreVariables->setAction('VoidTicketLLSRQ');

        $voidAirTicketRequest = $sabreRequestHeader->requestHeader($sabreVariables, $session) . $sabreVoidAirTicketRQ->voidAirTicketRequest($rph);

        $getVoidAirTicketRequest = $this->utils->send_data($this->URL, $voidAirTicketRequest, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);

        // parse response
        echo "<br/> parsedResponse:";
        $parsedResponse = $this->xmlResponseParse->parseVoidAirTicketResponse($getVoidAirTicketRequest);

        return $parsedResponse;

    }

    /**
     * Call OTACancel API
     *
     * @param       $session
     * @param array $options
     *
     * @return mixed
     */
    public function OTACancelRequest($session, $options = array())
    {
        // create sabre request header
        $sabreRequestHeader = new SabreRequestHeader();

        // create sabre OTACancel request
        $sabreOTACancelRQ = new SabreOTACancelRequest();

        $sabreVariables = $this->getSabreConnectionVariables();

        $sabreVariables->setService('OTA_CancelLLSRQ');
        $sabreVariables->setAction('OTA_CancelLLSRQ');

        $otaCancelRequest = $sabreRequestHeader->requestHeader($sabreVariables, $session) . $sabreOTACancelRQ->OTACancelRequest();

        $getOTACancelRequest = $this->utils->send_data($this->URL, $otaCancelRequest, \HTTP_Request2::METHOD_POST, array(
            'auth_method' => $this->HTTP_AUTH_METHOD,
            'username' => $this->HTTP_AUTH_USER,
            'password' => $this->HTTP_AUTH_PASSWORD
        ), $this->ADDITIONAL_HEADERS);

        // parse response
        echo "<br/> parsedResponse:";
        $parsedResponse = $this->xmlResponseParse->parseOTACancelResponse($getOTACancelRequest);

        return $parsedResponse;

    }
}
