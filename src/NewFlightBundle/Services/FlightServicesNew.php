<?php
/**
 * Created by PhpStorm.
 * User: para-soft7
 * Date: 9/13/2018
 * Time: 6:37 PM
 */

namespace NewFlightBundle\Services;


use NewFlightBundle\vendors\sabre\Services\SabreServicesNew;
use NewFlightBundle\Repository\FlightRepositoryNew;
use NewFlightBundle\Model\flightVO;
use NewFlightBundle\Model\CreateBargainRequest;

class FlightServicesNew
{

    protected $sabreServices;
    protected $flightRepository;


    public function __construct(FlightRepositoryNew $flightRepository, SabreServicesNew $sabreServices)
    {
        $this->flightRepository = $flightRepository;
        $this->sabreServices = $sabreServices;
    }

    /*
     * make a call to the database to get Airline List
     */
    public function getFlightSearchInfo()
    {
        $getAirlineList = $this->flightRepository->getAirlineList();

        return $getAirlineList;
    }
    /*
     * first we handle all the needed parameter then we call the service from the vendor, always we return the response under the form success and data /message
     */
    public function getFlightBooking(CreateBargainRequest $bargainRequest)
    {
        return $this->sabreServices->getFlightResult($bargainRequest);
    }

    public function pnrFormRequest($requestObj)
    {
        $pnrFormRequest = $this->sabreServices->pnrFormRequest($response);

        if($pnrFormRequest['success'])
        {
            $response['success'] = true;
            $response['data'] = $pnrFormRequest['data'];

        }else{
            //$response['success'] = false;
            //$response['message'] = "there is no flight available with these search criteria ";
            $response->setStatus('error');
            $response->setMessage("there is no flight available with these search criteria ");
            return $response;
        }
        return $response;
    }

    public function createPassengerNameRecord($requestObj)
    {
        $createPassengerNameRecord = $this->sabreServices->createPassengerNameRecord($requestObj);

        if($createPassengerNameRecord['success'])
        {
            $response['success'] = true;
            $response['data'] = $createPassengerNameRecord['data'];

        }else{
            //$response['success'] = false;
            //$response['message'] = "there is no flight available with these search criteria ";
            $response->setStatus('error');
            $response->setMessage("there is no flight available with these search criteria ");
            return $response;
        }
        return $response;
    }

    public function flightTicketIssuer($params)
    {
        $travelItineraryReadRequest = $this->sabreServices->travelItineraryReadRequest($sabreVariables, $pnrId);

        /*** TODO ***
        These functions might be invoked here or into SabreServicesNew as part of Ticket Issuing process
            SabreContextChangeRequest
            SabreDesignatePrinterRequest
            SabreAirTicketRequest
            SabreEndTransactionRequest
        ****/

        if($travelItineraryReadRequest['success'])
        {
            $response['success'] = true;
            $response['data'] = $travelItineraryReadRequest['data'];

        }else{
            //$response['success'] = false;
            //$response['message'] = "there is no flight available with these search criteria ";
            $response->setStatus('error');
            $response->setMessage("there is no flight available with these search criteria ");
            return $response;
        }
        return $response;
    }
}
