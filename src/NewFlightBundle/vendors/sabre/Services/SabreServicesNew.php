<?php
/**
 * Created by PhpStorm.
 * User: para-soft7
 * Date: 9/13/2018
 * Time: 6:39 PM
 */

namespace NewFlightBundle\vendors\sabre\Services;

use NewFlightBundle\Model\CreateBargainRequest;
use NewFlightBundle\Model\flightVO;

class SabreServicesNew
{
    protected $soapApiCaller;

    public function __construct(SoapApiCaller $soapApiCaller)
    {
        $this->soapApiCaller = $soapApiCaller;
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

            if ($response->getCode() == "No Results") {
                $response->setMessage('there is no flight available with these search criteria');
            }

            //close the session anyway
//            $this->soapApiCaller->closeSession();

        } else {
            $response->setStatus('error');
            $response->setMessage('unable to create a session');
        }

        return $response;
    }

    /*
     * Make API call to Enhance AirBook Request
     */
    public function pnrFormRequest($params)
    {
        // create session from sabre
        $createSession = $this->soapApiCaller->createSession();
        // if session is created successfully then make the enhanced airbook request
        if($createSession->getStatus() == true)
        {
            $pnrFormRequest = $this->soapApiCaller->createEnhancedAirBookRequest($params, $createSession);
            if($pnrFormRequest->getStatus() == true)
            {
                $response['success'] = $pnrFormRequest['success'];
                $response['data'] = $pnrFormRequest['message'];
            }

            //close the session anyway
//            $this->soapApiCaller->closeSession();

        }else{

            $response['success'] = false;
            $response['message'] = "unable to create a session";

        }

        return $response;
    }

    /*
     * Make API call to Create Passenger Name Record
     */
    public function createPassengerNameRecord($params)
    {
        // create session from sabre
        $createSession = $this->soapApiCaller->createSession();
        // if session is created successfully then make the enhanced airbook request
        if($createSession->getStatus() == true)
        {
            $createPNR = $this->soapApiCaller->createPassengerDetailsRequest($params, $createSession);
            if($createPNR->getStatus() == true)
            {
                $response['success'] = $createPNR['success'];
                $response['data'] = $createPNR['message'];
            }

            //close the session anyway
//            $this->soapApiCaller->closeSession();

        }else{

            $response['success'] = false;
            $response['message'] = "unable to create a session";

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
            $response['message'] = "unable to create a session";

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
            $response['message'] = "unable to create a session";

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
            $response['message'] = "unable to create a session";

        }

        return $response;
    }

    /*
     * Make API call to Air Ticket Request
     */
    public function airTicketRequest($sabreVariables, $passengers)
    {
        // create session from sabre
        $createSession = $this->soapApiCaller->createSession();
        // if session is created successfully then make the enhanced airbook request
        if($createSession->getStatus() == true)
        {

            /*** TODO ***
            These functions might be invoked here or into FlightServicesNew as part of Ticket Issuing process
                SabreContextChangeRequest
                SabreDesignatePrinterRequest
                SabreAirTicketRequest
                SabreEndTransactionRequest
            ****/

            $airTicketRequest = $this->soapApiCaller->airTicketRequest($sabreVariables, $passengers);
            if($airTicketRequest->getStatus() == true)
            {
                $response['success'] = $airTicketRequest['success'];
                $response['data'] = $airTicketRequest['message'];
            }

            //close the session anyway
//            $this->soapApiCaller->closeSession();

        }else{

            $response['success'] = false;
            $response['message'] = "unable to create a session";

        }

        return $response;
    }
}
