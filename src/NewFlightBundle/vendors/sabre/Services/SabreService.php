<?php
/**
 * Created by PhpStorm.
 * User: para-soft7
 * Date: 9/13/2018
 * Time: 6:39 PM
 */

namespace NewFlightBundle\vendors\sabre\Services;

use NewFlightBundle\Model\CreateEnhancedAirBookRequest;
use NewFlightBundle\Model\CreateBargainRequest;
use NewFlightBundle\Model\flightVO;
use PaymentBundle\Entity\Payment;
use NewFlightBundle\Entity\PassengerNameRecord;

use NewFlightBundle\Model\PassengerDetailsRequest;
use Symfony\Component\Translation\TranslatorInterface;
use NewFlightBundle\vendors\sabre\Config as SabreConfig;

class SabreService
{
    protected $soapApiCaller;
    protected $translator;
    protected $sabreConfig;
    protected $productionServer;
    protected $pauseBetweenRetriesSecs;
    protected $timeLimitMins;
    protected $maxApiCallAttempts;
    protected $attemptNumber;
    public $TEST_MODE;

    public function __construct(SoapApiCaller $soapApiCaller, TranslatorInterface $translator, SabreConfig $sabreConfig)
    {
        $this->soapApiCaller     = $soapApiCaller;
        $this->translator        = $translator;
        $this->sabreConfig = $sabreConfig;

        $this->productionServer = $this->sabreConfig->PRODUCTION_SERVER;;
        $this->TEST_MODE   = $this->sabreConfig->TEST_MODE;

        $this->pauseBetweenRetriesSecs = $this->sabreConfig->PAUSE_BETWEEN_RETRIES_SECS;
        $this->timeLimitMins           = $this->sabreConfig->TIME_LIMIT_MINS;
        $this->maxApiCallAttempts      = $this->sabreConfig->MAX_API_CALL_ATTEMPTS;
        $this->attemptNumber           = $this->sabreConfig->ATTEMPT_NUMBER;
        $this->check_airline_locators   = $this->sabreConfig->CHECK_AIRLINE_LOCATORS;
        $this->check_first_segment_only = $this->sabreConfig->CHECK_FIRST_SEGMENT_ONLY;
        $this->fetch_airline_locators   = $this->sabreConfig->FETCH_AIRLINE_LOCATORS;
    }

    /*
     * Make Api Call to Bargain Finder Max
     */
    public function getFlightResult(CreateBargainRequest $bargainRequest)
    {
        $response = new flightVO();

        // create session from sabre
        $createSession = $this->soapApiCaller->createSession();

        // if session is created successfully then make the bfm request
        if ($createSession->getStatus() == true) {

            $response = $this->soapApiCaller->createBargainFinderMax($bargainRequest, $createSession);

            if ($response->getCode() == 101) {
                $response->setMessage($this->translator->trans('there is no flight available with these search criteria'));
            }

            //close the session anyway
//            $this->soapApiCaller->closeSession();

        } else {
            $response->setStatus('error');
            $response->setMessage($this->translator->trans('unable to create a session'));
        }

        return $response;
    }

    /*
     * Make API call to Enhance AirBook Request
     */
    public function enhancedAirBookRequest(CreateEnhancedAirBookRequest $enhancedAirBookRequest)
    {
        $response = new flightVO();

        // create session from sabre
        $createSession = $this->soapApiCaller->createSession();
        // if session is created successfully then make the enhanced airbook request
        if($createSession->getStatus() == true)
        {
            $enhancedAirBookRequest = $this->soapApiCaller->createEnhancedAirBookRequest($enhancedAirBookRequest, $createSession);

            if($enhancedAirBookRequest->getStatus() == "success")
            {
                $response->setStatus('success');
                $response->setData($enhancedAirBookRequest);
            }else{
                $response->setStatus('error');
                $response->setMessage($enhancedAirBookRequest->getMessage());
            }

            //close the session anyway
//            $this->soapApiCaller->closeSession();

        }else{

            $response->setStatus('error');
            $response->setMessage($this->translator->trans('unable to create a session'));

        }

        return $response;
    }

    /*
     * Make API call to Create Passenger Name Record
     */
    public function createPassengerNameRecord(PassengerDetailsRequest $passengerDetailsRequest)
    {
        $response = new flightVO();

        // create session from sabre
        $createSession = $this->soapApiCaller->createSession();

        // if session is created successfully then make the enhanced airbook request
        if($createSession->getStatus() == true)
        {   
            $createPassengerDetailsRequest = $this->soapApiCaller->createPassengerDetailsRequest($passengerDetailsRequest, $createSession);

            if($createPassengerDetailsRequest->getStatus() == 'success')
            {
                $response->setStatus($createPassengerDetailsRequest->getStatus());
                $response->setData($createPassengerDetailsRequest);
            }else{
                $response->setStatus($createPassengerDetailsRequest->getStatus());
                $response->setMessage($createPassengerDetailsRequest->getMessage());
            }

            //close the session anyway
//            $this->soapApiCaller->closeSession();

        }else{

            $response->setStatus('error');
            $response->setMessage($this->translator->trans('unable to create a session'));

        }

        return $response;
    }

    /*
     * Make API call to Context Change Request
     */
    public function contextChangeRequest()
    {
        // create session from sabre
        $createSession = $this->soapApiCaller->createSession();
        // if session is created successfully then make the enhanced airbook request
        if($createSession->getStatus() == true)
        {
            $contextChangeRequest = $this->soapApiCaller->contextChangeRequest();
            if($contextChangeRequest->getStatus() == true)
            {
                $response['success'] = $contextChangeRequest['success'];
                $response['data'] = $contextChangeRequest['message'];
            }

            //close the session anyway
//            $this->soapApiCaller->closeSession();

        }else{

            $response['success'] = false;
            $response['message'] = $this->translator->trans("unable to create a session");

        }

        return $response;
    }

    /*
     * Make API call to Travel Itinerary Read Request
     */
    public function travelItineraryReadRequest($sabreVariables, $pnrId)
    {
        // create session from sabre
        $createSession = $this->soapApiCaller->createSession();
        // if session is created successfully then make the enhanced airbook request
        if($createSession->getStatus() == true)
        {
            $travelItineraryReadRequest = $this->soapApiCaller->createTravelItineraryRequest($sabreVariables, $pnrId);
            if($travelItineraryReadRequest->getStatus() == true)
            {
                $response['success'] = $travelItineraryReadRequest['success'];
                $response['data'] = $travelItineraryReadRequest['message'];
            }

            //close the session anyway
//            $this->soapApiCaller->closeSession();

        }else{

            $response['success'] = false;
            $response['message'] = $this->translator->trans("unable to create a session");

        }

        return $response;
    }

    /*
     * Make API call to Designate Printer Request
     */
    public function designatePrinterRequest($sabreVariables, $rq)
    {

        // create session from sabre
        $createSession = $this->soapApiCaller->createSession();
        // if session is created successfully then make the enhanced airbook request
        if($createSession->getStatus() == true)
        {
            $designatePrinterRequest = $this->soapApiCaller->designatePrinterRequest($sabreVariables, $rq);
            if($designatePrinterRequest->getStatus() == true)
            {
                $response['success'] = $designatePrinterRequest['success'];
                $response['data'] = $designatePrinterRequest['message'];
            }

            //close the session anyway
//            $this->soapApiCaller->closeSession();

        }else{

            $response['success'] = false;
            $response['message'] = $this->translator->trans("unable to create a session");

        }

        return $response;
    }

    /*
     * Make API call for Air Ticket Request
     *
     * @params: payment object
     *
     * @return object
     *
     */

    public function airTicketRequest($payment)
    {
        # STEP 1: Call to get a valid session
        $createSession = $this->soapApiCaller->createSession();
        if ($createSession->getStatus() != true) {
            $createSession->setStatus('error');
            $createSession->setMessage($createSession->getMessage());
            return $createSession;
        }

        # STEP 2: Change PCC to DANATA PCC
        $contextChange = $this->soapApiCaller->contextChangeRequest($createSession);
        if ($this->TEST_MODE) {
            $contextChange->setStatus('success');
        }

        if ($contextChange->getStatus() != 'success') {
            $contextChange->setStatus($contextChange->getStatus());
            $contextChange->setMessage($this->translator->trans('Error! can\'t change context'));
            return $contextChange;
        }

        # STEP 3: Sending Request with PNR. Getting all information about PNR w/o e-ticket
        $options = array(
            'check_airline_locators' => $this->check_airline_locators,
            'check_first_segment_only' => $this->check_first_segment_only,
            'fetch_airline_locators' => $this->fetch_airline_locators
        );

        $pnrId                = $payment->getPassengerNameRecord()->getPnr();
        $transactionId        = $payment->getUUID();
        $foundAirlineLocators = false;
        $startTime            = microtime(true);
        do {
            $travelItineraryRead = $this->soapApiCaller->createTravelItineraryRequest($pnrId, $options, $createSession);
            if ($travelItineraryRead->getStatus() == 'success') {
                $foundAirlineLocators = true;
                break;
            }
            sleep($this->pauseBetweenRetriesSecs);

            $this->attemptNumber++;
        } while ((time() - $startTime) < $this->timeLimitMins);

        if (!$foundAirlineLocators) {
            $travelItineraryRead->setStatus('error');
            $travelItineraryRead->setMessage($this->translator->trans('Error! can\'t get passenger name record'));
            return $travelItineraryRead;
        }

        # STEP 4: Prepare e-ticket with number 1 params
        $designatePrinterTicket = $this->soapApiCaller->designatePrinterRequest(1, $createSession);

        if ($this->TEST_MODE) {
            $designatePrinterTicket->setStatus('success');
        }

        if ($designatePrinterTicket->getStatus() != 'success') {
            $designatePrinterTicket->setStatus('error');
            $designatePrinterTicket->setMessage($this->translator->trans('Error! can\'t designate printer ticket'));
            return $designatePrinterTicket;
        }

        # STEP 5: Prepare e-ticket with number 2 params
        $designatePrinterHardCopy = $this->soapApiCaller->designatePrinterRequest(2, $createSession);
        if ($this->TEST_MODE) {
            $designatePrinterHardCopy->setStatus('success');
        }

        if ($designatePrinterHardCopy->getStatus() != 'success') {
            $designatePrinterHardCopy->setStatus('error');
            $designatePrinterHardCopy->setMessage($this->translator->trans('Error! can\'t designate printer hard copy'));
            return $designatePrinterHardCopy;
        }

        # STEP 6: Issue Ticket
        $passengers = $payment->getPassengerNameRecord()->getPassengerDetails();
        for ($this->attemptNumber = 1; $this->attemptNumber <= $this->maxApiCallAttempts; $this->attemptNumber++) {
            if ($this->attemptNumber > 1) $this->soapApiCaller->renewTimestamps();
            $airTicket    = $this->soapApiCaller->airTicketRequest($passengers, $createSession);
            $airTicketMsg = $airTicket->getMessage();

            if ($airTicketMsg && strpos($airTicketMsg, 'EACH PASSENGER MUST HAVE SSR FOID-0052') !== false) {
                $airTicket->setStatus('NotProcessed');
                $airTicket->setCode('FOID-0052');
                $airTicket->setMessage($this->translator->trans('EACH PASSENGER MUST HAVE SSR FOID-0052'));
                return $airTicket;
            }

            if ($this->attemptNumber <= $this->maxApiCallAttempts && $airTicketMsg && (strpos($airTicketMsg, 'IGN AND RETRY') !== false || strpos($airTicketMsg, 'USE IR TO IGNORE AND RETRIEVE PNR') !== false)) {
                $this->cleanSabreSessionRequest($createSession);
                usleep($this->pauseBetweenRetriesSecs);
                $airTicket->setStatus('error');
                $airTicket->setMessage($this->translator->trans('RETRY'));
                return $airTicket;
            }

            if ($airTicket->getStatus() == 'success') break;

            if ($this->attemptNumber != $this->maxApiCallAttempts) {
                usleep($this->pauseBetweenRetriesSecs);
            }
        }

        if ($this->TEST_MODE) {
            $airTicket->setStatus('success');
        }

        if ($airTicket->getStatus() != 'success') {
            $airTicket->setStatus('error');
            $airTicket->setMessage($this->translator->trans('Error! can\'t issue air ticket'));
            return $response;
        }

        # STEP 7: End TransactionRequest to save everything and become valid
        for ($this->attemptNumber = 1; $this->attemptNumber <= $this->maxApiCallAttempts; $this->attemptNumber++) {
            $endTransaction = $this->soapApiCaller->endTransactionRequest($createSession);
            if ($endTransaction->getStatus() == 'success' || $endTransaction->getStatus() == 'complete') break;

            if ($this->attemptNumber != $this->maxApiCallAttempts) usleep($this->pauseBetweenRetriesSecs);
        }

        if ($this->TEST_MODE) {
            $endTransaction->setStatus('success');
        }


        # STEP 8: Create Travel Itenerary Requests
        $travelItineraryReadTicketInfo = $this->soapApiCaller->createTravelItineraryRequest($pnrId, $options, $createSession);
        if ($travelItineraryReadTicketInfo->getStatus() != 'success') {
            $travelItineraryReadTicketInfo->setStatus('error');
            $travelItineraryReadTicketInfo->setMessage($this->translator->trans('Error! can\'t get ticket info'));
            $addLog = "Error! can't get ticket info (UUID:: $transactionId, PNR:: $pnrId) with criteria:: {criteria}";
            return $travelItineraryReadTicketInfo;
        }

        # STEP 9: Closing Sabre Session
        $closeSession = $this->soapApiCaller->closeSession($createSession);
        return $travelItineraryReadTicketInfo;
    }

    /**
     * Cancel Flight
     *
     * @param PassengerNameRecord $passengerNameRecord
     *
     * @return flightVO
     */
    public function cancelFlight(PassengerNameRecord $passengerNameRecord){

        $response = new flightVO();
        $response->setStatus('success');

        // create session from sabre
        $session = $this->soapApiCaller->createSession();

        // if session is created successfully then make the TravelItineraryAPI request
        if ($session->getStatus() == true) {

            $options = array(
                'check_airline_locators' => false,
                'check_first_segment_only' => true,
                'fetch_airline_locators' => true
            );
            // Call TravelItineraryReadRQ API
            $travelItineraryResponse = $this->soapApiCaller->createTravelItineraryRequest($passengerNameRecord->getPnr(), $options, $session);
            // Get the tickets
            foreach($travelItineraryResponse as $ticket) {
                //Call voidAirTicket API
                $voidTicketResponse = $this->soapApiCaller->voidAirTicket($ticket->getRph(), $session);
                if ($voidTicketResponse->getStatus() == "success"){
                    //Call OTACancelRequest API
                    $cancelRq = $this->soapApiCaller->OTACancelRequest($session);
                    if ($cancelRq->getStatus() == "success") {
                        // Call  EndTransactionRQ API
                        $endRequest = $this->soapApiCaller->endTransactionRequest($session);
                        if ($endRequest->getStatus() == "success"){

                            //TODO: if cancel status is successful update the DB, payment, PassendegerDetails?
                            // Please check the old Logic on how to update the DB during cancellation

                            //TODO: add error exception?

                            //finally close the session
                            $closeSession = $this->soapApiCaller->closeSession($session);

                        }
                    }
                }
            }

        } else {
            $response->setStatus('error');
            $response->setMessage($this->translator->trans('unable to create a session'));
        }

        return $response;
    }

    /**
     * Function communicated with the connection pool manager on the online version, it notice the connection manager to clean the session ().
     *
     * @param string $session: required to create a valid session
     * @param string $source: the source is used by the connection pool manager, to now if the cronJob is using specific session, or normal user on the web
     *
     * @return object if the close is successfully done it will reply a completed message, so this session could be used by other.
     */
    public function cleanSabreSessionRequest($session, $source = 'web')
    {
        if ($this->productionServer) {
            $response = new flightVO();
            $socket   = @fsockopen($this->container->getParameter('SCM_SERVICE_HOST'), $this->container->getParameter('SCM_SERVICE_PORT'));
            if (!$socket) {
                $response->setStatus('error');
                return $response;
            }

            fwrite($socket, json_encode(array(
                    'command' => 'clean_token',
                    'source' => $source,
                    'arguments' => array(
                        'token' => $session->getAccessToken()
                    )
                ))."\n");
            $scm_response_string = '';
            while ($tmp_line            = fgets($socket)) {
                $scm_response_string .= $tmp_line;
            }

            fclose($socket);
            $scm_response = json_decode($scm_response_string, true);

            if ($scm_response['status'] == $this->scm_status_error) {
                $response->setStatus('error');
                return $response;
            }

            $response->setStatus($scm_response['message']);
            $response->setMessage($this->translator->trans("completed"));
            return $response;
        }
    }

    /**
     * @return SabreConfig
     */
    function getConfig(){
        return $this->sabreConfig;
    }

}
