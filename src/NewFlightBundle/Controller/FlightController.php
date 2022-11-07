<?php
/**
 * Created by PhpStorm.
 * User: para-soft7
 * Date: 9/13/2018
 * Time: 6:35 PM
 */

namespace NewFlightBundle\Controller;

use TTBundle\Controller\DefaultController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use NewFlightBundle\Model\CreateEnhancedAirBookRequest;
use NewFlightBundle\Model\CreateBargainRequest;
use NewFlightBundle\Model\PassengerDetailsRequest;
use NewFlightBundle\Model\PassengerDetails;
use NewFlightBundle\Services\FlightService;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;

class FlightController extends DefaultController
{

    /**
     * @var FlightService $flightService
     */
    private $flightService;

    /**
     * Load Services
     *
     * @InjectParams({
     *      "flightService" = @Inject("FlightService"),
     * })
     */
    function __construct(FlightService $flightService)
    {
        $this->flightService = $flightService;
    }

    /*
     * render flight search form
     */
    public function flightBookingAction()
    {
        $getFlightSearchInfo = $this->flightService->getFlightSearchInfo();

        return $this->render('@../flight-booking.twig',$getFlightSearchInfo);
    }

    /**
     * This method is use for testing purpose. DELETE THIS ONCE ALL actions has been in place already
     * @param string $tripType
     * @return mixed
     */
    protected function mockTestSearchRequest($tripType = 'oneway')
    {
        if ($tripType == 'oneway'){
            $params = array(
                'departureairport' => array('Cebu City (CEB)'),
                'departureairportC' => array('CEB'),
                'arrivalairport' => array('Tacloban (TAC)'),
                'arrivalairportC' => array('TAC'),
                'fromDate' => array('2018-12-15'),
                'toDate' => array('2018-12-15'),
                'multidestination' => 0,
                'multidestinationC' => 0,
                'oneway' => 1,
                'cabinselect' => "Y",
                'adultsselect' => 1,
                'childsselect' => 0,
                'infantsselect' => 0,
                'flexibledate' => 0
            );
        }

        if ($tripType == 'roundtrip'){
            $params = array(
                'departureairport' => array('Cebu City (CEB)'),
                'departureairportC' => array('CEB'),
                'arrivalairport' => array('Tacloban (TAC)'),
                'arrivalairportC' => array('TAC'),
                'fromDate' => array('2019-03-10'),
                'toDate' => array('2019-03-15'),
                'multidestination' => 0,
                'multidestinationC' => 0,
                'oneway' => 0,
                'cabinselect' => "Y",
                'adultsselect' => 1,
                'childsselect' => 0,
                'infantsselect' => 0,
                'flexibledate' => 0
            );
        }

        if ($tripType == 'multi'){
            $params = array(
                'departureairport' => array('Cebu City (CEB)', 'Tacloban (TAC)', 'Manila (MNL)', 'Davao (DVO)'),
                'departureairportC' => array('CEB', 'TAC', 'MNL', 'DVO'),
                'arrivalairport' => array('Tacloban (TAC)', 'Manila (MNL)', 'Davao (DVO)', 'Cebu City (CEB)'),
                'arrivalairportC' => array('TAC', 'MNL', 'DVO', 'CEB'),
                'fromDate' => array('2019-01-18', '2019-01-29', '2019-02-04', '2019-02-12'),
                'toDate' => array('2019-01-18'),
                'multidestination' => 0,
                'multidestinationC' => 0,
                'oneway' => 0,
                'cabinselect' => "Y",
                'adultsselect' => 1,
                'childsselect' => 0,
                'infantsselect' => 0,
                'flexibledate' => 0
            );
        }

        return $params;
    }

    /*
     * Get Search Criteria and return the result
     */
    public function flightBookingResultAction($seotitle, $seodescription, $seokeywords)
    {
        $request = $this->getRequest();

        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }

        $params = $this->mockTestSearchRequest('roundtrip');

        $bargainRequest = new CreateBargainRequest();
        $bargainRequest->normalizePostRequest($params);
        $bargainRequest->setCurrencyCode($this->data['selected_currency']);

        $searchResult = $this->flightService->getFlightBooking($bargainRequest);

        if($searchResult->getStatus() == "error"){
            $this->addFlash('danger', $searchResult->getMessage());
        }

        $results = $searchResult->getData();

        $this->data['flightblocksearchIndex'] = 1;
        $this->data['hideblocksearchButtons'] = 1;
        $this->data['flightPageName'] = 'flight-search-results';
        $this->data['results'] = $results;
        $this->data['bargainRequest'] = $results['bargainRequest'];
        $this->data['enable_refundable'] = $results['enable_refundable'];

        return $this->render('@NewFlight/flight/results.twig', $this->data);
    }

    /*
     * get information of the selected flight
     */
    public function flightSelectedResultAction(Request $request)
    {
        $params = $request->request->all();

        $submitSelectedFlight = $this->flightService->submitSelectedResult($params);

        if(!$submitSelectedFlight['success'])
        {
            $this->addFlash('danger', $submitSelectedFlight['message']);
            return $this->render('@../flight-price-error.twig',$submitSelectedFlight);
        }
        return $this->redirectToRoute(flightPNRAction);
    }
    /*
     * Open Passenger Name Record
     */
    public function flightPNRAction(Request $request)
    {
        $params = $request->request->all();
        $searchId = $params['searchId'];

        $enhancedAirBookRequest = $this->flightService->getPNRActionObject($searchId);
        $creatEnhancedAirBookRequest = $this->flightService->enhancedAirBookRequest($enhancedAirBookRequest);

        return $this->render('@../flight-passenger-name-record.twig',$params);
    }
    /*
     * flight pnr submission
     */
    public function flightPNRSubmissionAction(Request $request)
    {
        
        $passengerDetailsRequest = $this->flightService->getPNRSubmissionActionObject($request);
        $createPassengerNameRecord = $this->flightService->createPassengerNameRecord($passengerDetailsRequest);
        
        if(!$createPassengerNameRecord->getStatus() == 'success')
        {
            $this->addFlash('danger', $submitPNR['message']);
            return $this->render('@../flight-passenger-name-record.twig',$submitPNR);
        }
        return $this->redirectToRoute(flightIssueTicketAction);
    }
    /*
     * make issue ticket
     */

    public function flightIssueTicketAction(Request $request)
    {
        $transactionId = $request->query->get('transaction_id');
        $issueTicket   = $this->flightService->flightTicketIssuer($transactionId);

        /*
         * Check if api response contains `EACH PASSENGER MUST HAVE SSR FOID-0052`,
         * Many international airlines require a form of identification (FOID) to be present in the PNR before you can issue an electronic ticket.
         * WE need the user to go back to PNR page to enter each passenger passport/ID info
         */
        if ($issueTicket->getStatus() == 'error') {
            $errorMsg = $issueTicket->getMessage();
            if ($errorMsg == 'EACH PASSENGER MUST HAVE SSR FOID-0052') {
                return $this->redirectToRoute('_process-passport', array(
                        'error' => $errorMsg,
                        'code' => $issueTicket->getCode(),
                        'status' => $issueTicket->getStatus(),
                        'transactionId' => $issueTicket->getPaymentUUID(),
                        'pnrId' => $issueTicket->getPnr()
                        )
                );
            }
            return $this->redirectToRoute('_flight_booking', array('error' => $errorMsg));
        }

        if ($issueTicket->getIsCorporateSite()) {
            return $this->redirectToRoute('_corporate_flight_details', array('transaction_id' => $issueTicket->getPaymentUUID()));
        } else {
            return $this->redirectToRoute('_flight_details', array('transaction_id' => $issueTicket->getPaymentUUID()));
        }
    }

    /**
     * Get flight details
     *
     */
    public function flightDetailsAction($seotitle, $seodescription, $seokeywords)
    {
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }

        $transactionId = $this->getRequest()->query->get('transaction_id');
        $response      = $this->flightService->getFlightDetails($transactionId);

        $this->data['flightPageName'] = 'flight-booking-details';
        $this->data['response']       = $response->getData();

        return $this->render('@NewFlight/flight/flight-details.twig', $this->data);
    }

    /*
     * make flight cancellation
     */
    public function flightCancellationAction(Request $request)
    {
        $params = $request->request->all();
        $params['uuid'] = '201F4117-E042-4CF5-87F1-59F63AC15A6C';

        $cancelFlight =  $this->flightService->cancelFlight($params['uuid']);

        if($cancelFlight->getStatus() == "error")
        {
            $this->addFlash('danger', $cancelFlight->getMessage());
        }

        return $this->render('@../flight-details.twig',$cancelFlight);
    }
}
