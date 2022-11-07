<?php

namespace RestBundle\Controller\flights;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use mysql_xdevapi\Result;
use PaymentBundle\Services\impl\ResponseStatusIdentifier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use RestBundle\Controller\TTRestController;
use RestBundle\Services;
use MarkWilson\XmlToJson\XmlToJsonConverter;
use Symfony\Component\HttpFoundation\ParameterBag;

class FlightsController extends TTRestController
{

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->request = Request::createFromGlobals();
    }

    /**
     * This method returns the matching items of the term searched (auto-complete).
     *
     * @param String $term The searched term
     * @return data
     */
    public function searchAirportAction()
    {
        $term = $this->request->query->get('term');

        if (!isset($term) && empty($term)) {
            throw new HttpException(403, $this->translator->trans("Missing term parameter."));
        }

        $return = $this->get('FlightServices')->getSearchSuggestions($term);

        if (empty($return)) {
            $response = new Response();
            $response->setStatusCode(204, $this->translator->trans("No data found."));
            return $response;
        } else {
            return $return;
        }
    }


    /**
     * This method returns the flights based on search criteria origin,destination,Departing Date, Returning Date .
     *
     * @param String $term The searched term
     * @return data
     */
    public function searchFlightsAction(Request $request)
    {

        // specify required fields
        $requirements = array(
            "departureAirport",
            "departureAirportC",
            "arrivalAirport",
            "arrivalAirportC",
            "fromDate",
            "toDate",
            "multiDestination",
            "multiDestinationC",
            "oneWay",
            "cabin",
            "adults",
            "childs",
            "infants",
            "flexibleDate"
        );

        // fetch post json data
        $requestData = $this->fetchRequestData($requirements);


        $bag = new ParameterBag(array());
        $bag->set("departureairport", $requestData['departureAirport']);
        $bag->set("departureairportC", $requestData['departureAirportC']);
        $bag->set("arrivalairport", $requestData['arrivalAirport']);
        $bag->set("arrivalairportC", $requestData['arrivalAirportC']);
        $bag->set("fromDate", $requestData['fromDate']);
        $bag->set("toDate", $requestData['toDate']);
        $bag->set("multidestination", $requestData['multiDestination']);
        $bag->set("multidestinationC", $requestData['multiDestinationC']);
        $bag->set("oneway", $requestData['oneWay']);
        $bag->set("cabinselect", $requestData['cabin']);
        $bag->set("adultsselect", $requestData['adults']);
        $bag->set("childsselect", $requestData['childs']);
        $bag->set("infantsselect", $requestData['infants']);
        $bag->set("flexibledate", $requestData['flexibleDate']);

        $request->request = $bag;

        $Result = $this->get('FlightFinder')->searchNew($request, $request->getSession(), $this->data);

        if (!empty($Result['outbound'])) {
            $Result['status'] = 'success';
            $Result['message'] = 'data found for your search criteria';
            $response = new Response(json_encode($Result));
            $response->setStatusCode(200);
            return $response;
        } //elseif (empty($Result['outbound']) && isset($Result['no_data'])) {
          else{
            $Result['status'] = 'failed';
            $Result['message'] = 'no data found for your search criteria';

            $response = new Response(json_encode($Result));
            $response->setStatusCode(500);
            return $response;
        }


    }

    /**
     * This method will Tell Sabre to book/reserve a certain number of seats on a specific flight, but without reserving any specific seat (no seat selection is done at this stage).
     *
     */
    public function airBookAction(Request $request)
    {
        // specify required fields
        $requirements = array(
            "userId",
            "totalSegments",
            "departureDateTime",
            "arrivalDateTime",
            "flightNumber",
            "numberInParty",
            "resBookDesigCode",
            "destinationLocation",
            "marketingAirline",
            "operatingAirline",
            "originLocation",
            "adultsQuantity",
            "childrenQuantity",
            "infantsQuantity",
            "priceAmount",
            "currencyCode"
        );

        // fetch post json data
        $requestData = $this->fetchRequestData($requirements);

        $bag = new ParameterBag(array());
        $bag->set("userID", $requestData['userId']);
        $bag->set("total_segments", $requestData['totalSegments']);
        $bag->set("DepartureDateTime", $requestData['departureDateTime']);
        $bag->set("ArrivalDateTime", $requestData['arrivalDateTime']);
        $bag->set("FlightNumber", $requestData['flightNumber']);
        $bag->set("NumberInParty", $requestData['numberInParty']);
        $bag->set("ResBookDesigCode", $requestData['resBookDesigCode']);
        $bag->set("DestinationLocation", $requestData['destinationLocation']);
        $bag->set("MarketingAirline", $requestData['marketingAirline']);
        $bag->set("OperatingAirline", $requestData['operatingAirline']);
        $bag->set("OriginLocation", $requestData['originLocation']);
        $bag->set("AdultsQuantity", $requestData['adultsQuantity']);
        $bag->set("ChildrenQuantity", $requestData['childrenQuantity']);
        $bag->set("InfantsQuantity", $requestData['infantsQuantity']);
        $bag->set("PriceAmount", $requestData['priceAmount']);
        $bag->set("CurrencyCode", $requestData['currencyCode']);

        $request->request = $bag;

        $bookFlight = $this->get('FlightsServices')->airBookAction($request);

        if ($bookFlight['status'] == 'priceError') {
            $response['status'] = 'failed';
            $response['message'] = 'The Price is not available any more';
            $response = new Response(json_encode($response));
            $response->setStatusCode(400);
        } elseif ($bookFlight['status'] == 'error') {
            $response['status'] = 'failed';
            $response['message'] = $bookFlight['message'];
            $response = new Response(json_encode($response));
            $response->setStatusCode(400);
        } elseif ($bookFlight['status'] == 'success') {
            $response['status'] = 'success';
            $response["hiddenFields"] = $bookFlight['hiddenFields'];
            $response["amount"] = $bookFlight['amount'];
            $response["currency"] = $bookFlight['currency'];

            //$bookFlight['response'];   response


            //$response["amountReturned"] = $bookFlight['amountReturned'];
            //$response["amountSpecified"] = $bookFlight['amountSpecified'];
            $response['message'] = 'Your Flights Has been reserved successfully';
            $response = new Response(json_encode($response));
            $response->setStatusCode(200);
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Please check your network administrator';
            $response = new Response(json_encode($response));
            $response->setStatusCode(400);
        }
        return $response;
    }


    public function generateTokenFromRequestForm(Request $request, array $skip_keys = null)
    {

        $on_production_server = ($this->container->hasParameter('ENVIRONMENT') && $this->container->getParameter('ENVIRONMENT') == 'production');
        $secToken = [];
        foreach ($request->request as $key => $value) {
            if (in_array($key, $skip_keys)) {
                continue;
            }
            $secToken[] = $value;
            $secKeyToken[$key] = $value;
        }
        sort($secToken, SORT_STRING);
        $secTokenStr = trim(implode(" ", $secToken));

        $sabreVariables = $this->get('SabreServices')->getSabreConnectionVariables($on_production_server);

        return crypt($secTokenStr, $sabreVariables['salt']);
    }


    /**
     * POST Method
     *
     * This endpoints allows to search flights.<br />
     *
     * ### Sample json Request ###
     *
     * <code>
     *  {
     *      "departureairport": "Mactan-Cebu International",
     *      "departureairportC": "CEB",
     *      "arrivalairport": "Ninoy Aquino Intl",
     *      "arrivalairportC": "MNL",
     *      "fromDateC":"2018-04-19",
     *      "toDateC":"",
     *      "cabinselect":"",
     *      "adultsselect":1,
     *      "childsselect": 0,
     *      "infantsselect": 0,
     *      "departureairport-0":"Francisco Bangoy International",
     *      "departureairportC-0": "DVO",
     *      "arrivalairport-0":"Ninoy Aquino Intl",
     *      "arrivalairportC-0":"MNL",
     *      "fromDateC-0":"2018-04-30",
     *      "departureairport-1":"D.Z. Romualdez",
     *      "departureairportC-1":"TAC",
     *      "arrivalairport-1":"Mactan-Cebu International",
     *      "arrivalairportC-1":"CEB",
     *      "fromDateC-1":"2018-05-10",
     *      "priority": 0,
     *      "chosenAirline":"",
     *      "oneway":1,
     *      "multidestination":1
     *  }
     * </code>
     *
     * statusCodes={
     *   200 = "OK",
     *   403 = "Access Denied",
     *   400 = "Error",
     * }
     *
     * @return json
     */
    public function searchAction(Request $request)
    {
        $search = $this->get('FlightFinder')->search($request, $request->getSession(), []);

        if (isset($search['error']['redirect'])) {
            return $this->view(array('error' => array('status' => 400, 'message' => "Server Error, Please try again")), 400);
        }

        if (isset($search['error']['message'])) {
            return $this->view(array('error' => array('status' => 400, 'message' => "Server Error, Please try again")), 400);
        } else {
            unset($search['error']);
        }

        return $this->handleView($this->view($search, 200));
    }

    public function bookAction(Request $request)
    {

        $bookRequest = $this->get('FlightServices')->bookRequest($request);
        $requestData = $bookRequest['requestData'];

        $sabreVariables = $bookRequest['sabreVariables'];
        $getCampaingHash = $this->get('FlightServices')->getServiceHash($requestData, $request, $sabreVariables);
        if (isset($getCampaingHash['priceError'])) {
            return [
                'message' => "The price is no longer available.",
                'status' => 203
            ];
        } else {

            $validUnusedCoupon = $getCampaingHash['validUnusedCoupon'];
            $campaign_info = $getCampaingHash['campaign_info'];
            $hiddenFields = $getCampaingHash['hiddenFields'];

            $createEnhancedAirBookRequest = $this->get('FlightServices')->createEnhancedAirBookRequest($sabreVariables, $requestData, $campaign_info);

            if (isset($createEnhancedAirBookRequest['priceError']) || !isset($createEnhancedAirBookRequest['hiddenFields'])) {

                return [
                    'message' => "The price is no longer available.",
                    'status' => 203
                ];
            } else {
                $hiddenFields += $createEnhancedAirBookRequest['hiddenFields'];
            }

            $response = [
                'flight_segments' => $requestData->getFlightSegments(),
                'passengers' => $bookRequest['passengersArray'],
                'price' => $requestData->getDisplayedPrice(),
                'displayedPrice' => number_format($requestData->getDisplayedPrice(), 2, '.', ','),
                'original_price' => $requestData->getOriginalPrice(),
                'currency' => $requestData->getCurrencyCode(),
                'displayed_currency' => $requestData->getDisplayedCurrency(),
                'params' => $hiddenFields,
                'status' => 200,
                'message' => 'Success'
            ];

            return $response;
        }
    }

    /**
     * This method will Generates/returns a PNR (a.k.a. Passenger Name Record, this is the Sabre GDS booking number).
     *
     */
    public function PnrCreationAction(Request $request)
    {
        $requirements = array(
            "passengerNameRecord",
            "access_token",
            "penaltiesInfo",
            "refundable",
            "multi_destination",
            "one_way",

        );

        // fetch post json data
        $requestData = $this->fetchRequestData($requirements);


        $em = $this->getDoctrine()->getManager();


        $bookRequest = $this->get('FlightServices')->bookRequest($request);

        $requestData = $bookRequest['requestData'];

        $sabreVariables = $bookRequest['sabreVariables'];

        $validUnusedCoupon = false;//$getCampaingHash['validUnusedCoupon'];
        $campaign_info = null;//$getCampaingHash['campaign_info'];


        $pnr = $request->request->get('passengerNameRecord');

        $passengerNameRecord = $this->get('FlightServices')->setPassengersDetails($pnr);
        $PnrCreation = $this->get('FlightServices')->PnrCreation($passengerNameRecord, $requestData, $request, $sabreVariables, $validUnusedCoupon, $campaign_info);

        $response = new Response(json_encode($PnrCreation));
        $response->setStatusCode(200);

        return $response;

    }


    public function retrieveItineraryAction(Request $request)
    {

        if ($request->request->has("pnrId")) {
            $pnrId = $request->request->get("pnrId");
            $connection_type_booking = 2;
            $on_production_server = ($this->container->hasParameter('ENVIRONMENT') && $this->container->getParameter('ENVIRONMENT') == 'production');
            $sabreVariables = $this->get('SabreServices')->getSabreConnectionVariables($on_production_server);

            $create_session_response = $this->get('SabreServices')->createSabreSessionRequest($sabreVariables, 1, $connection_type_booking, 'web');

            $sabreVariables['access_token'] = $create_session_response['AccessToken'];
            $sabreVariables['returnedConversationId'] = $create_session_response['ConversationId'];
            $sabreVariables['Service'] = "GetReservationRQ";
            $sabreVariables['Action'] = "GetReservationRQ";
            $retrieveItinerary = $this->get('SabreServices')->createRetrieveItineraryRequest($sabreVariables, $pnrId);
            $this->get('SabreServices')->closeSabreSessionRequest($sabreVariables, 'mobile');
            $response = new Response(json_encode($retrieveItinerary));
            $response->setStatusCode(200);
            return $response;
        } else {
            $data['status'] = 'error';
            $data['message'] = "You request Does Not Contain PNR ID";
            $response = new Response(json_encode($data));
            $response->setStatusCode(500);
            return $response;
        }
    }

    public function getItineraryAction(Request $request)
    {

        if ($request->request->has("pnrId")) {
            $pnrId = $request->request->get("pnrId");
            $connection_type_booking = 2;
            $on_production_server = ($this->container->hasParameter('ENVIRONMENT') && $this->container->getParameter('ENVIRONMENT') == 'production');
            $sabreVariables = $this->get('SabreServices')->getSabreConnectionVariables($on_production_server);
            $create_session_response = $this->get('SabreServices')->createSabreSessionRequest($sabreVariables, 1, $connection_type_booking, 'web');
            $sabreVariables['access_token'] = $create_session_response['AccessToken'];
            $sabreVariables['returnedConversationId'] = $create_session_response['ConversationId'];
            $sabreVariables['Service'] = "GetReservationRQ";
            $sabreVariables['Action'] = "GetReservationRQ";
            $retrieveItinerary = $this->get('SabreServices')->createTravelItineraryRequest($sabreVariables, $pnrId);
            $this->get('SabreServices')->closeSabreSessionRequest($sabreVariables, 'mobile');
            $response = new Response(json_encode($retrieveItinerary));
            $response->setStatusCode(200);
            return $response;
        } else {
            $data['status'] = 'error';
            $data['message'] = "You request Does Not Contain Access Token";
            $response = new Response(json_encode($data));
            $response->setStatusCode(500);
            return $response;
        }
    }

    /**
     *used to issue air tickets
     *
     */
    public function AirTicketAction(Request $request)
    {

        // specify required fields
        $requirements = array(
            "transactionId"
        );

        // fetch post json data
        $requestData = $this->fetchRequestData($requirements);
        $transactionId = $requestData['transactionId'];
        if(isset($requestData['paymentAmount']) && isset($requestData['paymentCurrency'])){
            $paymentInfo        = $this->get('PaymentServiceImpl')->getPaymentInformation($transactionId);
            $dbTableAmount=$paymentInfo->getAmount();
            $dbTableCurrency= $paymentInfo->getCurrency();

            if($dbTableAmount!=$requestData['paymentAmount'] || $dbTableCurrency!= $requestData['paymentCurrency']){
                $error = $this->translator->trans('Payment Information is not accurate .');
                $error .= $this->translator->trans('Kindly Contact: ') . 'flights-support@touristtube.com' . $this->translator->trans(' - Expected reply within 24 hours.');
                $response = new Response(json_encode(array('code' => 400, 'status' => $error)));
                $response->setStatusCode(400);
                return $response;
            }
        }




        $transactionType = $request->query->get('type', 'flight');
        $is_from_mobile = $request->query->get('from_mobile', '0');

        $on_production_server = ($this->container->hasParameter('ENVIRONMENT') && $this->container->getParameter('ENVIRONMENT') == 'production');

        $flightTicketIssuer = $this->get('FlightTicketIssuer');
        $ticket = $flightTicketIssuer->issueTicket(
            $transactionId, $transactionType, $on_production_server, $is_from_mobile, 2, $this->data, true, 3, 500000, array('pause_between_retries_secs' => 10, 'time_limit_mins' => 5)
        );


        if (isset($ticket['re_route'])) {
            $response = new Response(json_encode(array('code' => 400, 'status' => "error", 'message' => "Server Error, Please try again")));
            $response->setStatusCode(400);
            return $response;
        }

        $response = new Response(json_encode(array('code' => 200, 'status' => "success", 'message' => "Your ticket Reserved Succesfully")));
        $response->setStatusCode(200);
        return $response;
    }

    public function viewFlightDetailAction(Request $request)
    {
        $requirements = array(
            "transactionId"
        );

        // fetch post json data
        $requestData = $this->fetchRequestData($requirements);
        $transaction_Id =$uuid= $requestData['transactionId'];


        $pnr = $this->getDoctrine()->getRepository('PaymentBundle:Payment')->find(urldecode($uuid));
        $getcreationdate  = $pnr->getCreationDate();
        $lastTimeToCancel = $getcreationdate->setTime(23, 00);
        $currentTime = new \DateTime('now', new \DateTimeZone('Asia/Dubai'));
        if ($currentTime <= $lastTimeToCancel) { $flightCanBeCancelled=1;}else{ $flightCanBeCancelled=0;}
        if (!$pnr) {
            $data['status'] = 'error';
            $data['code'] = 400;
            $data['message'] = 'invalid transaction';

            $res = new Response(json_encode($data));
            $res->setStatusCode(400);
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }


        $pnrStatus = $pnr->getPassengerNameRecord()->getStatus();

        $myFlightDetails = $this->get('FlightServices')->myFlightDetails($pnr, $uuid);
        $passengersArray = $myFlightDetails['passengersArray'];
        $flightSegments  = $myFlightDetails['flightSegments'];


            $data['passengers']     = $passengersArray;
            $data['flight']         = $flightSegments;
            $data['message']        = $this->translator->trans('Success');

            $data['pnr_status']     = $pnrStatus;


        foreach ($data['flight'] as $key => $flight) {
            if (isset($flight['flight_info'])) {
                foreach ($flight['flight_info'] as $k1 => $flight_info) {
                    if (isset($flight_info['stop_info'])) {

                        $depMin                                                       = $this->get('app.utils')->getMinutesFromTime($flight_info['departure_time']);

                        $arrMin                                                       = $this->get('app.utils')->getMinutesFromTime($flight_info['arrival_time']);

                        $minsDuration                                                 = $this->get('app.utils')->mins_to_duration($arrMin - $depMin);
                        $data['flight'][$key]['flight_info'][$k1]['raw_duration'] =$flight_info['elapsedTime'];

                        foreach ($flight_info['stop_info'] as $k2 => $stop_info) {
                            $sdepMin                                                                        = $this->get('app.utils')->getMinutesFromTime($stop_info['departure_time']);

                            $sarrMin                                                                        = $this->get('app.utils')->getMinutesFromTime($stop_info['arrival_time']);

                            $sminsDuration                                                                  = $this->get('app.utils')->mins_to_duration($sarrMin - $sdepMin);

                            $data['flight'][$key]['flight_info'][$k1]['stop_info'][$k2]['raw_duration'] = $stop_info['elapsedTime'];
                        }
                    }
                }
            }
        }


        $data['isFlightDetailsValidFromPayment'] = $this->get('FlightServices')->checkFlightDetailsValidFromPayment($pnr);
        $data['code'] = 200;
        $data['status'] = 'success';
        $data['message'] = 'Your reservation is booked and confirmed. There is no need to call us to reconfirm this reservation';
        $data['flightCanBeCancelled']=$flightCanBeCancelled;
        $res = new Response(json_encode($data));
        $res->setStatusCode(200);
        $res->headers->set('Content-Type', 'application/json');
        return $res;

    }

    public function cancelFlightAction(Request $request)
    {
        $requirements = array(
            "transactionId"
        );

        // fetch post json data
        $requestData = $this->fetchRequestData($requirements);

        $on_production_server = ($this->container->hasParameter('ENVIRONMENT') && $this->container->getParameter('ENVIRONMENT') == 'production');
        $currentTime = new \DateTime('now', new \DateTimeZone('Asia/Dubai'));

        $transactionId = $request->request->get('transactionId');

        $ticket_list = $request->query->get('ticket_list', '');
        if ($ticket_list) $ticket_list = explode(',', $ticket_list);
        else $ticket_list = array();

        $payment = $this->getDoctrine()->getRepository('PaymentBundle:Payment')->findOneByUuid($transactionId);

        if (!$payment) {
            $error = $this->translator->trans("Error! TransactionId Not Found");
            $response = array('data' => [], 'status' => '00', 'response_code' => '00103', 'response_message' => $error);

            $res = new Response(json_encode($response));
            $res->setStatusCode(400);
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }
        //check first if booking is already cancelled
        $passengers = $payment->getPassengerNameRecord()->getPassengerDetails();
        $passengerCancelled = false;

        foreach ($passengers as $passenger) {

            if ($passenger->getTicketStatus() == 'Cancelled'  or $passenger->getTicketStatus() == 'VOIDED') {
                $passengerCancelled = true;
                break;
            }
        }

        if ($passengerCancelled && ($payment->getResponseMessage() == 'CANCELLED' or $payment->getResponseMessage() == 'VOIDED')) {
            $error = $this->translator->trans("Error! This Booking is already cancelled.");
            $response = array('data' => [], 'status' => '00', 'response_code' => '00103', 'response_message' => $error);

            $res = new Response(json_encode($response));
            $res->setStatusCode(400);
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $getcreationdate = $payment->getCreationDate();
        $lastTimeToCancel = $getcreationdate->setTime(23, 00);

        $from_mobile = 1;
        $error = '';

        if ($currentTime <= $lastTimeToCancel) {

            $flightCancelation = $this->get('FlightServices')->doflightCancelation($this, $payment, $from_mobile);

            $this->data['passengers'] = $flightCancelation['passengersArray'];

            $this->data['flightSegment'] = $flightCancelation['flightSegments'];
            $this->data["pnr_id"] = $flightCancelation['pnrId'];
            $this->data["multi_destination"] = $flightCancelation['multiDestination'];
            $this->data["one_way"] = $flightCancelation['oneWay'];
            $this->data["email"] = $payment->getPassengerNameRecord()->getEmail();

            $sabreVariables = $this->get('SabreServices')->getSabreConnectionVariables($on_production_server);
            $create_session_response = $this->get('SabreServices')->createSabreSessionRequest($sabreVariables, 0, 2, ($from_mobile ? 'mobile' : 'web'));


            $sabreVariables['access_token'] = $create_session_response['AccessToken'];
            $sabreVariables['returnedConversationId'] = $create_session_response['ConversationId'];

            $sabreVariables['Service'] = "ContextChangeLLSRQ";
            $sabreVariables['Action'] = "ContextChangeLLSRQ";

            $contextChange = $this->get('SabreServices')->contextChangeRequest($sabreVariables);

            if ($this->container->hasParameter('ENVIRONMENT') && $this->container->getParameter('ENVIRONMENT') == 'development') {
                $contextChange["status"] = "success";
            }

            if ($contextChange["status"] === "success") {

                $em = $this->getDoctrine()->getManager();
                $processFlightCancelation = $this->get('FlightServices')->processFlightCancelation($this, $payment, $sabreVariables, $em, $flightCancelation['pnrId'], $from_mobile);

                $response = $processFlightCancelation->getContent();
                $res = new Response($response);

                $res->setStatusCode(200);
                $res->headers->set('Content-Type', 'application/json');
                return $res;

            } else {

                $error = $this->translator->trans("Error! can't change context");
                $response = array('data' => [], 'status' => '00', 'response_code' => '00103', 'response_message' => $error);
                $res = new Response(json_encode($response));
                $res->setStatusCode(400);
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }

            $this->get('SabreServices')->closeSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));

        } else {

            $error = $this->translator->trans('Flight tickets purchased may be canceled online with full refund if cancelled before 19.00 GMT on the same day of purchase.');
            $error .= $this->translator->trans('For flight tickets modifications and other cancellations airlines ticketing policies apply.');
            $error .= $this->translator->trans('Kindly Contact: ') . 'flights-support@touristtube.com' . $this->translator->trans(' - Expected reply within 24 hours.');

            $response = array('data' => [], 'status' => '00', 'response_code' => '00103', 'response_message' => $error);
            $res = new Response(json_encode($response));
            $res->setStatusCode(400);
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }


    }

    public function myBookingFlightsAction(Request $request)
    {
        $requirements = array(
            "userId",
            "userEmail"
        );

        // fetch post json data
        $requestData = $this->fetchRequestData($requirements);

        $userArr['id']=$userId=$requestData['userId'];
        $userArr['email']=$userEmail=$requestData['userEmail'];

        $bookingStatus=(isset($requestData['bookingStatus'])) ? $requestData['bookingStatus'] : '';
        $fromDate=(isset($requestData['fromDate'])) ? $requestData['fromDate'] : '';
        $toDate=(isset($requestData['toDate'])) ? $requestData['toDate'] : '';
        $showMore=(isset($requestData['showMore'])) ? $requestData['showMore'] : '';
        $page=(isset($requestData['page'])) ? $requestData['page'] : '';

        $data = array();

        switch ($bookingStatus) {
            case 1:
            case 3:
                $count = $this->get('MyBookingServices')->getPastUpcomingFLightsCount($userArr, $bookingStatus, $fromDate, $toDate);
                break;
            case 2:
                $count = $this->get('MyBookingServices')->getCancelledFLightsCount($userArr, $fromDate, $toDate);
                break;
            default:
                $count = $this->get('MyBookingServices')->getFlightsCount($userArr, $fromDate, $toDate);
                break;
        }
        $showMore = ($showMore && intval($count) > 3) ? 1 : 0;
        $pagebig = intval($page);
        $limit = ($showMore) ? 10 : 3;
        $page = $pagebig;
        $offset = $page * $limit;
        $flightBookings = array("flightBookings" => $this->get('MyBookingServices')->myBookingsSearchFlight($userArr, $bookingStatus, $limit, $offset, $fromDate, $toDate));
        $flightBookings['total_flights_past'] = $this->get('MyBookingServices')->getPastUpcomingFLightsCount($userArr, 1, $fromDate, $toDate);
        $flightBookings['total_flights_cancelled'] = $this->get('MyBookingServices')->getCancelledFLightsCount($userArr, $fromDate, $toDate);
        $flightBookings['total_flights_upcoming'] = $this->get('MyBookingServices')->getPastUpcomingFLightsCount($userArr, 2, $fromDate, $toDate);
        $flightBookings['total_flights'] = $flightBookings['total_flights_past'] + $flightBookings['total_flights_cancelled'] + $flightBookings['total_flights_upcoming'];


        $data += $flightBookings;
        $data['status'] = 'success';
        $res = new Response(json_encode($data));
        $res->setStatusCode(200);
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function createPnrAction(Request $request)
    {


        $em = $this->getDoctrine()->getManager();
        $bookRequest = $this->get('FlightServices')->bookRequest($request);
        $requestData = $bookRequest['requestData'];
        $sabreVariables = $bookRequest['sabreVariables'];
        $passengerNameRecord = $bookRequest['passengerNameRecord'];
        $getCampaingHash = $this->get('FlightServices')->getServiceHash($requestData, $request, $sabreVariables);

        if (isset($getCampaingHash['priceError'])) {
            return [
                'message' => "The price is no longer available.",
                'status' => 203
            ];
        } else {

            $validUnusedCoupon = $getCampaingHash['validUnusedCoupon'];
            $campaign_info = $getCampaingHash['campaign_info'];
            $hiddenFields = $getCampaingHash['hiddenFields'];

            $pnr = $request->request->get('passengerNameRecord');
            $passengerNameRecord->setFirstName($pnr['firstName']);
            $passengerNameRecord->setSurname($pnr['surname']);
            $passengerNameRecord->setEmail($pnr['email']);
            $passengerNameRecord->setMobile($pnr['mobile']);
            $passengerNameRecord->setCountryOfResidence($em->getRepository('TTBundle:CmsCountries')->find($pnr['countryOfResidence']));

            $passengerDetail = $passengerNameRecord->getPassengerDetails();
            $passengersAppend = [];
            $i = 0;
            foreach ($pnr['passengerDetails'] as $passenger) {

                $dob = $passenger['dateOfBirth'];
                $dobM = ($dob['month'] < 10) ? '0' . $dob['month'] : $dob['month'];
                $dobD = ($dob['day'] < 10) ? '0' . $dob['day'] : $dob['day'];

                $passengerDetail[$i]->setFirstName($passenger['firstName']);
                $passengerDetail[$i]->setSurname($passenger['surname']);
                $passengerDetail[$i]->setGender($passenger['gender']);
                $passengerDetail[$i]->setDateOfBirth(new \DateTime($dob['year'] . '-' . $dob['month'] . '-' . $dob['day']));
                $passengersAppend[$i] = $passengerDetail[$i];
                $passengerNameRecord->removePassengerDetail($passengerDetail[$i]);
                $i++;
            }

            foreach ($passengersAppend as $pAppend) {
                $passengerNameRecord->addPassengerDetail($pAppend);
            }

            $bookRequestFormSubmit = $this->get('FlightServices')->bookRequestFormSubmit($passengerNameRecord, $requestData, $request, $sabreVariables, $validUnusedCoupon, $campaign_info);

            if (isset($bookRequestFormSubmit['redirectError'])) {
                return [
                    'status' => 203,
                    'message' => 'Session timed out'
                ];
            } elseif (isset($bookRequestFormSubmit['error']) || isset($bookRequestFormSubmit['errors'])) {
                return [
                    'status' => 203,
                    'message' => (isset($bookRequestFormSubmit['error']) ? $bookRequestFormSubmit['error'] : $bookRequestFormSubmit['errors'])
                ];
            } else {
                return [
                    'status' => 200,
                    'transaction_id' => $bookRequestFormSubmit["transaction_id"]
                ];
            }
        }
    }


    public function issueAirTicketAction(Request $request)
    {


        $transactionId = $request->request->get('transaction_id');
        $transactionType = $request->query->get('type', 'flight');
        $is_from_mobile = $request->query->get('from_mobile', '0');

        $on_production_server = ($this->container->hasParameter('ENVIRONMENT') && $this->container->getParameter('ENVIRONMENT') == 'production');

        $flightTicketIssuer = $this->get('FlightTicketIssuer');
        $ticket = $flightTicketIssuer->issueTicket(
            $transactionId, $transactionType, $on_production_server, $is_from_mobile, 2, $this->data, true, 3, 500000, array('pause_between_retries_secs' => 10, 'time_limit_mins' => 5)
        );


        if (isset($ticket['re_route'])) {
            return $this->view(array('error' => array('status' => 400, 'message' => "Server Error, Please try again")), 400);
        }

        return $this->handleView($this->view($ticket, 200));
    }

    public function detailsAction(Request $request)
    {
        $uuid = $request->query->get('transaction_id', 0);
        $pnr = $this->get('FlightServices')->validatePayment(urldecode($uuid));

        if (!$pnr) {
            return [
                'error' => true,
                'message' => 'Error in getting your flight details'
            ];
        }

        $myFlightDetails = $this->get('FlightServices')->myFlightDetails($pnr, $uuid);
        $passengersArray = $myFlightDetails['passengersArray'];
        $flightSegments = $myFlightDetails['flightSegments'];

        return [
            'passengerNameRecord' => [
                'passengerDetails' => $passengersArray,
                'firstName' => $pnr->getPassengerNameRecord()->getFirstName(),
                'surname' => $pnr->getPassengerNameRecord()->getSurname(),
                'countryOfResidence' => $pnr->getPassengerNameRecord()->getCountryOfResidence(),
                'email' => $pnr->getPassengerNameRecord()->getEmail(),
                'mobile' => $pnr->getPassengerNameRecord()->getMobile(),
                'alternativeNumber' => $pnr->getPassengerNameRecord()->getAlternativeNumber(),
                'specialRequirement' => $pnr->getPassengerNameRecord()->getSpecialRequirement()
            ],
            'flight_segments' => $flightSegments
        ];
    }


    /**
     * This method fetch and validate the json data posted in array format,
     *
     * @param Array $requirements The requirements.
     * @return Array    The data.
     */
    public function fetchRequestData(array $requirements = array())
    {
        $data = array();
        $content = $this->get("request")->getContent();
        if (!empty($content)) {
            $data = json_decode($content, true);
        }

        // validate fetched request data
        $this->validateFetchedRequestData($data, $requirements);

        return $data;
    }

    /**
     * This method validates the fetched data per provided requirements.
     *
     * @param array $fetchedData The fetched data.
     * @param array $requirements The requirements.
     */
    public function validateFetchedRequestData(array $fetchedData, array $requirements)
    {
        foreach ($requirements as $field) {
            if (is_array($field)) {
                $this->validateConstraints($fetchedData, $field);
            } else {
                if (!isset($fetchedData[$field]) || (empty($fetchedData[$field]) && $fetchedData[$field] != "0")) {
                    $action_array = array();
                    $action_array[] = $field;
                    $ms = vsprintf($this->translator->trans("%s is required."), $action_array);
                    throw new HttpException(403, $ms);
                }
            }
        }
    }

    /**
     * This method validates the fetched data per provided requirements.
     *
     * @param array $fetchedData The fetched data.
     * @param array $requirements The requirements.
     */
    public function validateConstraints(array $fetchedData, array $requirements)
    {
        $validator = $this->container->get('validator');

        $constraints = array();
        $name = $requirements['name'];
        $required = false;

        // validate required
        if (isset($requirements['required']) && $requirements['required']) {
            $required = true;
            if (!isset($fetchedData[$name])) {
                $action_array = array();
                $action_array[] = $name;
                $ms = vsprintf($this->translator->trans("Missing %s parameter."), $action_array);
                throw new HttpException(403, $ms);
            }
        }

        if (($required && !isset($requirements['nullable'])) || (isset($requirements['nullable']) && $requirements['nullable'] === false)) {
            $constraints[] = new \Symfony\Component\Validator\Constraints\NotNull();
            $constraints[] = new \Symfony\Component\Validator\Constraints\NotBlank();
        }

        if (isset($requirements['type'])) {
            $constraints[] = new \Symfony\Component\Validator\Constraints\Type(array('type' => $requirements['type']));
        }

        if (isset($requirements['constraints']) && count($requirements['constraints']) > 0) {
            foreach ($requirements['constraints'] as $constraintName => $params) {
                switch ($constraintName) {
                    case 'date':
                        $constraints[] = new \Symfony\Component\Validator\Constraints\Date();
                        break;
                    case 'email':
                        $constraints[] = new \Symfony\Component\Validator\Constraints\Email();
                        break;
                    case 'gt':
                        $constraints[] = new \Symfony\Component\Validator\Constraints\GreaterThan($params);
                        break;
                    case 'gte':
                        $constraints[] = new \Symfony\Component\Validator\Constraints\GreaterThanOrEqual($params);
                        break;
                    default:
                        break;
                }
            }
        }

        if (count($constraints) > 0 && isset($fetchedData[$name])) {
            $error = $validator->validateValue($fetchedData[$name], $constraints);
            if (count($error) > 0) {
                $action_array = array();
                $action_array[] = $name;
                $ms = vsprintf($this->translator->trans("Invalid %s parameter."), $action_array);
                throw new HttpException(403, $ms . " $error");
            }
        }
    }
    public function addFlightLog($message, $params = array(), $cleanParams = false)
    {
        if (!isset($params) || !is_array($params)) $params = array();

        if ($params) {
            if ($cleanParams) {
                foreach (array_keys($params) as $param_key) {
                    $this->cleanParams($params[$param_key]);
                }
            }

            foreach (array_keys($params) as $param_key) {
                $params[$param_key] = json_encode($params[$param_key]);
            }
        }
         $params['userId'] =0;//this->data['isUserLoggedIn'])? $this->userGetID() : 0;

        $logger = $this->get('monolog.logger.flights');
        $logger->info("\nUser {userId} - ".$message, $params);
    }

}
