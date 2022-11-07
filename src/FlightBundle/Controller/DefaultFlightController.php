<?php

namespace FlightBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use PaymentBundle\Model\Payment;

class DefaultFlightController extends \TTBundle\Controller\DefaultController
{
    
    public function seoKeywordFiller($seotitle, $seodescription, $seokeywords)
    {
        
        $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($seotitle);
        $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($seodescription);
        $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($seokeywords);
    }
    
    public function Action($seotitle, $seodescription, $seokeywords)
    {
        $this->data['needpayment']      = 1;
        $this->data['showHeaderSearch'] = 0;
        $request                        = $this->getRequest();
        $error                          = $request->query->get('error', null);
        $timedOut                       = $request->query->get('timedOut', null);
        
        $this->data['error']    = $error;
        $this->data['timedOut'] = $timedOut;
        $this->setHreflangLinks("/flight-booking");
        if ($this->data['aliasseo'] == '') {
            
            $this->seoKeywordFiller($seotitle, $seodescription, $seokeywords);
        }
        $getAirlines  = $this->getDoctrine()->getRepository('TTBundle:Airline')->findBy(array(), array('name' => 'ASC'));
        $airlineCount = sizeof($getAirlines);
        
        for ($i = 0; $i < $airlineCount; $i++) {
            $airlineInfo[$i]['code']     = $getAirlines[$i]->getCode();
            $airlineInfo[$i]['nameCode'] = $getAirlines[$i]->getName().' ('.$airlineInfo[$i]['code'].')';
        }
        
        $this->data['airline'] = $airlineInfo;
        
        return $this->render('@Flight/flight/flight-booking.twig', $this->data);
    }
    
    public function flightBookingNewAction($seotitle, $seodescription, $seokeywords)
    {
        if( $this->show_flights_block == 0 ) return $this->redirectToLangRoute('_welcome');
        //         echo 'testing 2019...';
        $this->data['isindexpage']    = 1;
        $this->data['pageBannerPano'] = 'flight';
        $this->data['flightPageName'] = 'flight-index';
        $this->setHreflangLinks($this->generateLangRoute('_flight_booking'), true, true);
        
        $mainEntityType_array          = $this->get('TTServices')->getMainEntityTypeGlobal( $this->data['LanguageGet'], $this->container->getParameter('PAGE_TYPE_FLIGHT'), -1 );
        $this->data['mainEntityArray'] = $this->get('TTServices')->getMainEntityTypeGlobalData( $this->data['LanguageGet'], $mainEntityType_array );
        $this->data['flightblocksearchIndex'] = 1;
        $this->data['hideblocksearchButtons'] = 1;
//        $this->data['pageBannerImage']        = $this->get("TTRouteUtils")->generateMediaURL('/media/images/index/book_flight_homepage_image.jpg');
        $this->data['pageBannerH2']           = $this->translator->trans('Book your Flight');
        if ($this->data['aliasseo'] == '') {
            $this->seoKeywordFiller($seotitle, $seodescription, $seokeywords);
        }
        //
        $this->data['PG_FLIGHT_BOOKING_FORM'] = true;
        
        //
        return $this->render('@Flight/flight/flight-booking_new.twig', $this->data);
    }
    
    public function flyToAirportAction($city, $airportName, $airportCode, $seotitle, $seodescription, $seokeywords)
    {
        
        $this->data['isindexpage'] = 1;
        $this->setHreflangLinks($this->generateLangRoute('_fly_to_airport', array('city' => $city, 'airportName' => $airportName, 'airportCode' => $airportCode)), true, true);
        
        $airportName = str_replace("+", " ", $airportName);
        $city        = str_replace("+", " ", $city);
        $airportInfo = $this->get('ReviewsServices')->getAirportByCodeInfo($airportCode);
        if ($airportInfo) {
            $airportName = $this->get('app.utils')->htmlEntityDecode($airportInfo->getName());
        } else {
            return $this->redirectToLangRoute('_flight_booking', array(), 301);
        }
        $this->data['input']                  = array('arrivalairport' => $airportName, 'arrivalairportC' => $airportCode);
        $this->data['flightblocksearchIndex'] = 1;
        $this->data['hideblocksearchButtons'] = 1;
        $sourcepath                           = 'media/hotels/hotelbooking/hotel-main-banner/';
        $sourcename                           = $airportInfo->getImage();
        $this->data['pageBannerImage']        = $this->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 1920, 878, 'airport1920878', $sourcepath, $sourcepath, 65);
        $this->data['pageBannerH2']           = $this->translator->trans('Fly to').' '.$city;
        $this->data['pageBannerH3']           = $airportName;
        if ($this->data['aliasseo'] == '') {
            $action_array   = array();
            $action_array[] = $this->get('app.utils')->htmlEntityDecode($city);
            $seotitle = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $action_array[] = $this->get('app.utils')->htmlEntityDecode($airportName);
            $seodescription = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $seokeywords = vsprintf($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'), $action_array);
            $this->seoKeywordFiller($seotitle, $seodescription, $seokeywords);
        }
        
        return $this->render('@Flight/flight/fly-to-airport.twig', $this->data);
    }

    public function flightsNewAction($seotitle, $seodescription, $seokeywords, Request $request)
    {


        if ($request->request->has('from_mobile') && $request->request->get('from_mobile') == 1) {
            $response = $this->get('FlightServices')->flightAvailabilitySearch();

            $res = new Response(json_encode($response));
            $res->headers->set('Content-Type', 'application/json');
            
            return $res;
        }
        
        if ($request->request->get('multidestination') == 1) {
            /*
             we need to set isBooking variable to 1, so it will proceed
             directly to flight review page instead of next-flight page
             for multidestination flight request
             */
            $this->data['isbooking'] = 1;
        }
        
        // Check if flight is TwoWay, Ensure Returning Date is not LessThan the Departure Date
        if (!$request->get('oneway')) {
            $fromDate = new \DateTime($request->get('fromDate')[0]);
            $toDate   = new \DateTime($request->get('toDate')[0]);
            
            if ($toDate < $fromDate) {
                $this->addWarningNotification($this->translator->trans("There are no flights that match your search criteria."), 0);
                return $this->redirectToLangRoute('_flight_booking', array());
            }
        }
        
        $this->data['flightblocksearchIndex'] = 1;
        $this->data['hideblocksearchButtons'] = 1;
        $this->data['flightPageName']         = 'flight-search-results';
        
        $response = $this->get('FlightServices')->flightAvailabilitySearchNew();

        if (!isset($response['outbound'])) {
            $this->addWarningNotification($this->translator->trans("We were not able to get results for you, please try again later or change your criteria"), 0);
            
            return $this->redirectToLangRoute('_flight_booking', array());
        }
        
        if (isset($response['no_data']) && $response['no_data']) {
            $this->addWarningNotification($this->translator->trans("There are no flights that match your search criteria."), 0);
            
            return $this->redirectToLangRoute('_flight_booking', array());
        }
        
        if (isset($response['no_data']) && $response['no_data']) {
            $this->addWarningNotification($this->translator->trans("There are no flights that match your search criteria."), 0);
            
            return $this->redirectToLangRoute('_flight_booking', array());
        }
        
        $response['departureCityAirport'] = $response['arrivalCityAirport']   = '';
        
        if (!empty($response)) {
            if (isset($response['departure_airports'])) {
                foreach ($response['departure_airports'] as $code => $deptair) {
                    ksort($deptair);
                    $response['departure_airports'][$code] = reset($deptair);
                }
            }

            $destinations = $response['destinations'];
            $arrival_airport = (!empty($destinations)) ? $destinations[count($destinations)-1]['arrival_airport'] : $response['arrivalairport'];
            
            $_deptCityAirport = $this->get('FlightRepositoryServices')->findAirport($response['departureAirport']);
            $_arriCityAirport = $this->get('FlightRepositoryServices')->findAirport($arrival_airport);
            
            $response['departureCityAirport'] = $_deptCityAirport->getCity();
            $response['arrivalCityAirport']   = $_arriCityAirport->getCity();
            
            /*
             * Fix for POP-UP details title for return flight
             if($request->request->has('return_flight') && !empty($request->request->get('return_flight')))
             {   //we need to invert the departure and arrival meaning a return flight
             $response['departureCityAirport'] = $_arriCityAirport->getCity();
             $response['arrivalCityAirport']   = $_deptCityAirport->getCity();
             }else //show default
             {
             $response['departureCityAirport'] = $_deptCityAirport->getCity();
             $response['arrivalCityAirport']   = $_arriCityAirport->getCity();
             }
             */
            
        }
        
        if ($this->data['aliasseo'] == '') {
            $this->seoKeywordFiller($seotitle, $seodescription, $seokeywords);
        }
        
        $userId             = $this->userGetID();
        $userArray          = $this->get('UserServices')->getUserDetails(array('id' => $userId));
        $userCorpoAccountId = $userArray[0]['cu_corpoAccountId'];
        
        $this->data['is_corporate_account_with_self_approval'] = $this->get('CorpoApprovalFlowServices')->userAllowedToApprove($userId, $userCorpoAccountId);
        
        foreach ($response['outbound'] as $key => $outbound) {
            //[0] because we have one segment record per outbound record
            $segmentRec = $outbound["segments"][0][0];
            
            $segmentRec['price_attr'] = (isset($outbound['selected_price']) && $outbound['selected_price']) ? $outbound['selected_price_attr'] : $segmentRec['price_attr'];
            
            if (!isset($response['minimum_price']) || $segmentRec['price_attr'] < $response['minimum_price']) {
                $response['minimum_price'] = $segmentRec['price_attr'];
            }
        }
        
        if(sizeof($response['airlines']) > 1){
            
            /** we need to use the prices provided by $airlines
             in order to fix MIN and MAX price from outbound/inbound
             NOTE: I'm not touching other portion of the PRICE codes as I
             don't want to get code conflicts from other tasks worked by ANNA/JOEL
             **/
            
            $priceFix  = array_column($response['airlines'], 'amount_attr');
            $response['minimum_price'] = min($priceFix);
            $response['maximum_price'] = max($priceFix);
            
        }

        $this->data['response'] = $response;

        return $this->render('@Flight/flight/flights_new.twig', $this->data);
    }
    
    public function flightReviewTripAction($seotitle, $seodescription, $seokeywords)
    {

        $this->data['flightPageName'] = 'flight-review';
        $noLogo                       = "no-logo.jpg";
        $request                      = $this->getRequest();

        $_request                     = $request->request->all();
        $hiddenFields = [];
        
        foreach ($_request as $k => $req) {
            if ($k === 'passengerNameRecord' || $k === "response" || $k === "flight_segments" || $k === "forwardToReviewTrip") {
                continue;
            }
            $hiddenFields[$k] = $req;
            unset($_request[$k]);
        }
        $jsonMain = $request->request->get("flightrequest");

        $flightRequest = json_decode($jsonMain, true);
        $this->data['totalPrice'] = $hiddenFields['price_attr'];

        /* we need to inject value from flightRequest related_one_way into hiddenFields */
        if(isset($flightRequest['main']['related_one_way'])){
            $hiddenFields['related_one_way'] = $flightRequest['main']['related_one_way'];
        }

        $_request['hiddenFields'] = $hiddenFields;
        $requestData = $this->get('RequestDataHandler')->normaliseRequestNew($request);
        $_request['flight_segments'] = $requestData->getFlightSegments();
        foreach ($_request['flight_segments'] as $k => $segment) {
            if (is_array($segment)) {
                if (is_array($segment['flight_info'])) {
                    foreach ($segment['flight_info'] as $i => $info) {
                        $airline = $this->get('FlightServices')->findAirline($info['airline_code']);
                        $airlineLogo = ($airline) ? $airline->getLogo() : $noLogo;
                        $_request['flight_segments'][$k]['flight_info'][$i]['airline_logo'] = $this->get("TTRouteUtils")->generateMediaURL('/media/images/airline-logos/'.$airlineLogo);
                    }
                }
            }
        }

        $this->data                        = array_merge($this->data, $_request);
        $this->data['response']['request'] = $flightRequest['main'];
        
        $this->data['flightblocksearchIndex'] = 1;
        $this->data['hideblocksearchButtons'] = 1;


        return $this->render('@Flight/flight/flight-review-trip.twig', $this->data);
    }
    
    public function departurePopupAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->render('@Flight/flight/departure_detailed_popup.twig', $this->data);
    }
    
    public function flightBookingResultAction($seotitle = '', $seodescription = '', $seokeywords = '')
    {
        
        $response = array();
        //  $response =  $this->forward('FlightBundle:Flight:flightBookingResult');
        $response = $this->get('FlightServices')->flightAvailabilitySearch();
        
        if (isset($response['from_mobile']) && $response['from_mobile'] == 1) {
            $res = new Response(json_encode($response));
            $res->headers->set('Content-Type', 'application/json');
            
            return $res;
        }
        
        $userId             = $this->userGetID();
        $userArray          = $this->get('UserServices')->getUserDetails(array('id' => $userId));
        $userCorpoAccountId = $userArray[0]['cu_corpoAccountId'];
        
        $response['is_corporate_account_with_self_approval'] = $this->get('CorpoApprovalFlowServices')->userAllowedToApprove($userId, $userCorpoAccountId);
        
        
        //        if (!$response['is_corporate_account_with_self_approval']) {
        //            $this->addWarningNotification("Airline booking is temporarily disabled because of the payment gateway upgrade, use your corporate account for booking if you have one, otherwise send a request to <a href='mailto:sa@touristtube.com' title='{{ 'sa@touristtube.com' }}'>sa@touristtube.com</a>", 0);
        //        }
        
        return $this->render('@Flight/flight/flight-booking-result.twig', $response);
    }
    
    public function flightBookingResultNewAction($seotitle = '', $seodescription = '', $seokeywords = '')
    {
        $response = array();
        
        //  $response =  $this->forward('FlightBundle:Flight:flightBookingResult');
        $response = $this->get('FlightServices')->flightAvailabilitySearchNew();
        
        
        return $this->render('@Flight/flight/flight-booking-result.twig', $response);
    }
    
    public function bookAvailableFlightAction($seotitle, $seodescription, $seokeywords)
    {
        $response = array();
        
        $response = $this->get('FlightServices')->bookAvailableFlight($seotitle, $seodescription, $seokeywords);
        
        return $this->render('@Flight/flight/book-flight.twig', $response);
    }
    
    public function bookAvailableFlightNewAction($seotitle, $seodescription, $seokeywords, Request $request)
    {
        $this->data['flightPageName'] = 'flight-book-flight';
        $response                     = array();
        
        $jsonMain = $request->request->get("flightrequest");
        
        $flightRequest = json_decode($jsonMain, true);
        $response = $this->get('FlightServices')->bookAvailableFlight($seotitle, $seodescription, $seokeywords);

        if (is_array($response)) {
            $response['totalPrice']    = $flightRequest['main']['price_attr'];
            $response['totalBaseFare'] = $flightRequest['main']['base_fare_attr'];
            $response['totalTaxes']    = $flightRequest['main']['taxes_attr'];

            $ticket_response = $this->get('FlightServices')->setTicketsFromResponse($response);

            //$response['forwardToReviewTrip'] = 0;

            $this->data = array_merge($this->data, array_merge($response, $ticket_response));

        } else {

            $this->data['priceError'] = true;

        }




        return $this->render('@Flight/flight/book-flight_new.twig', $this->data);
    }
    
    public function pnrFormSubmitAction($seotitle = '', $seodescription = '', $seokeywords = '')
    {
        $response = array();
        $response = $this->get('FlightServices')->bookAvailableFlight($seotitle, $seodescription, $seokeywords, 0);
        
        /* if (is_array($response) && (isset($response['error']) || isset($response['errors']) || isset($response['pin_mismatch']) || isset($response['priceError']))) {
         return $this->render('@Flight/flight/book-flight.twig', $response);
         } else {
         
         // return $response;
         return $this->redirectToRoute($response['callback_url'], array('transaction_id' => $response["transaction_id"]));
         } */
        if (is_array($response) && (isset($response['error']) || isset($response['errors']) || isset($response['pin_mismatch']) || isset($response['priceError']))) {
            $this->addErrorNotification($this->translator->trans("Error, while booking process, please repeat the process."), 0);
            $this->data['flightPageName'] = 'flight-book-flight';
            return $this->render('@Flight/flight/book-flight_new.twig', $response);
        } else {
            if (isset($response["transaction_id"])) {
                $transaction_id      = $response["transaction_id"];
                $passengerNameRecord = $this->getDoctrine()->getManager()->getRepository('FlightBundle:PassengerNameRecord')->findOneByPaymentUUID($transaction_id);
                
                return $this->redirectToLangRoute('otpRoute', array('module_id' => $this->getParameter('MODULE_FLIGHTS'), 'reservation_id' => $passengerNameRecord->getId(), 'user_id' => $this->userGetID()));
            } else {
                return $this->redirectToRoute($response['callback_url']);
            }
        }
    }
    
    public function pnrFormSubmitNewAction($seotitle = '', $seodescription = '', $seokeywords = '', Request $request)
    {
        $response = array();

        $response = $this->get('FlightServices')->bookAvailableFlight($seotitle, $seodescription, $seokeywords, 0);

        $jsonMain = $request->request->get("flightrequest");
        
        $flightRequest = json_decode($jsonMain, true);


        if (is_array($response) && (isset($response['error']) || isset($response['errors']) || isset($response['pin_mismatch']) || isset($response['priceError']))) {
            
            $ticket_response = $this->get('FlightServices')->setTicketsFromResponse($response);
            
            $response['totalPrice']    = $flightRequest['main']['totalPriceAttr'];
            $response['totalBaseFare'] = $flightRequest['main']['totalBaseFare'];
            $response['totalTaxes']    = $flightRequest['main']['totalTaxes'];
            //$this->debug($response);
            //$this->addErrorNotification($this->translator->trans("Error, while booking process, please repeat the process."), 0);
            //$this->data['flightPageName'] = 'flight-book-flight';
            //return $this->render('@Flight/flight/book-flight_new.twig', array_merge(array_merge($response, $this->data), $ticket_response));
			$this->addErrorNotification($this->translator->trans("Error while booking, please try again."), 5);
            return $this->redirectToRoute('_flight_booking_result');
        }
        else {

            if (is_array($response)) {
                if (isset($response["transaction_id"])) {
                    $transaction_id      = $response["transaction_id"];
                    $passengerNameRecord = $this->getDoctrine()->getManager()->getRepository('FlightBundle:PassengerNameRecord')->findOneByPaymentUUID($transaction_id);
                    
                    $userId = ($this->data['isUserLoggedIn'] ? $this->userGetID() : 0);
                    
                    if (!$userId) {
                        return $this->redirectToRoute('_paymentview', array('transaction_id' => $transaction_id));
                    }
                    
                    return $this->redirectToLangRoute('otpRoute', array('module_id' => $this->getParameter('MODULE_FLIGHTS'), 'reservation_id' => $passengerNameRecord->getId(), 'user_id' => $userId));
                } else {
					if( isset($response['callback_url']) && ($response['callback_url']=='_waiting_approval') ){
						return $this->redirectToRoute( '_waiting_approval' );
					}else{
						$this->addErrorNotification($this->translator->trans("We were unable to complete your booking, please try again."), 5);
                    	return $this->redirectToRoute('_flight_booking_result');
					}
                    //$this->addErrorNotification($this->translator->trans("Error, while booking process, please repeat the process."), 0);
                    //$this->data['flightPageName'] = 'flight-book-flight';
                    //return $this->render('@Flight/flight/book-flight_new.twig', array_merge($response, $this->data));
                }
            } else {
                return $response;
            }
        }
    }


    public function issueAirTicketAction()
    {
        $response = array();
        //  $response =  $this->forward('FlightBundle:Flight:flightBookingResult');
        $response = $this->get('FlightServices')->issueAirTicket();
        
        if (isset($response['redirect_to'])) {
            $this->addErrorNotification($this->translator->trans("Error, while issuing a ticket, please repeat the process."), 0);
            return $this->redirectToRoute($response['redirect_to'], array('error' => $response['error']));
        }
        
        if ($this->get('FlightServices')->isCorporateSiteSource($response['transaction_id'])) {
            return $this->redirectToRoute('_corporate_flight_details', $response);
        } else {
            return $this->redirectToRoute('_flight_details', $response);
        }
    }
    
    public function issueAirTicketNewAction()
    {
        $response = array();
        //  $response =  $this->forward('FlightBundle:Flight:flightBookingResult');
        $response = $this->get('FlightServices')->issueAirTicket();
        
        if (isset($response['redirect_to'])) {
            
            // Check If error is `EACH PASSENGER MUST HAVE SSR FOID-0052`
            if ($response['error'] == 'EACH PASSENGER MUST HAVE SSR FOID-0052') {
                return $this->redirectToRoute($response['redirect_to'], array(
                    'error' => $response['error'],
                    'code' => $response['code'],
                    'status' => $response['status'],
                    'transactionId' => $response['transactionId'],
                    'pnrId' => $response['pnrId']
                )
                    );
            }
            
            $this->addErrorNotification($this->translator->trans("Error, while issuing a ticket, please repeat the process."), 0);
            
            return $this->redirectToRoute($response['redirect_to'], array('error' => $response['error']));
        }
        
        if ($this->get('FlightServices')->isCorporateSiteSource($response['transaction_id'])) {
            
            return $this->redirectToRoute('_corporate_flight_details', $response);
        } else {
            return $this->redirectToRoute('_flight_details', $response);
        }
    }
    
    public function flightCancelationAction($seotitle, $seodescription, $seokeywords)
    {

        //cancelation
        $response = array();
        $response = $this->get('FlightServices')->flightCancelation($seotitle, $seodescription, $seokeywords);
        
        if (isset($response['nodata']) && $response['nodata'] == 1) {
            return $this->render('@Flight/flight/flight-no-data.twig', $response);
        } elseif (isset($response['nodata']) && $response['nodata'] == 0) {
            
            return $this->redirectToLangRoute('_my_bookings', array());
        } else {

            $response['showfooter'] = 0;
            return $this->render('@Flight/flight/flight-cancelation.twig', $response);
        }
    }
    
    public function myFlightCheckerAction($seotitle = '', $seodescription = '', $seokeywords = '')
    {
        return $this->get('FlightServices')->flightCheckDetails($seotitle, $seodescription, $seokeywords);
    }
    
    public function flightSearchResultCheckerAction($seotitle = '', $seodescription = '', $seokeywords = '')
    {
        return $this->get('FlightServices')->flightCheckResult($seotitle, $seodescription, $seokeywords);
    }
    
    public function FlightDetailsAction($seotitle, $seodescription, $seokeywords)
    {
        
        $response = array();
        
        $response = $this->get('FlightServices')->flightDetails($seotitle, $seodescription, $seokeywords);
        
        return $this->render('@Flight/flight/flight-detailed.twig', $response);
    }
    
    public function accountWaitingApprovalAction()
    {
        return $this->render('@Flight/flight/waiting-approval.twig', $this->data);
    }
    /*
     * Proceed issue ticket after OTP
     */
    
    public function proceedPaymentAction($module_id, $reservation_id, $user_id)
    {
        $passengerNameRecord = $this->getDoctrine()->getManager()->getRepository('FlightBundle:PassengerNameRecord')->find($reservation_id);
        
        if ($passengerNameRecord) {
            
            $transactionId = $passengerNameRecord->getPaymentUUID();
            
            $paymentType = $passengerNameRecord->getPayment()->getPaymentType();
            
            if ($paymentType == Payment::CORPO_ON_ACCOUNT) {
                return $this->redirectDynamicRoute("_corpo_on_account_payment_process", array('transaction_id' => $transactionId));
            } elseif ($paymentType == Payment::CREDIT_CARD) {
                
                return $this->redirectDynamicRoute("_paymentview", array('transaction_id' => $transactionId));
            }
        }
    }
    /*
     * Multiple Destination Controller
     */
    
    public function multipleDestinationViewAction($seotitle, $seodescription, $seokeywords, Request $request)
    {
        $this->data['flightblocksearchIndex'] = 1;
        $this->data['hideblocksearchButtons'] = 1;
        $this->data['flightPageName']         = 'flight-search-results';
        
        if ($this->container->has('profiler')) {
            $this->container->get('profiler')->disable();
        }
        
        $jsonMain = $request->request->get("flightrequest", false);
        
        if (!$jsonMain) {
            
            $this->addWarningNotification("Error found while getting flight availability, please try again ", 0);
            
            return $this->redirectToLangRoute('_flight_booking', array());
        }
        
        $mainSearchAndSelectedSegments = json_decode($jsonMain, true);
        
        if (isset($mainSearchAndSelectedSegments['segmentsInbound']) && $mainSearchAndSelectedSegments['segmentsInbound'] != '') {
            $this->data['isbooking'] = 1;
            $response['isReturn'] = 1;
            
            $decodedSelectedSegments = $this->getReturnSegments($mainSearchAndSelectedSegments);
            
            $response['outbound'] = $decodedSelectedSegments; //json_decode($mainSearchAndSelectedSegments['segmentsInbound'], true);
            
            $response['departureAirport']  = $mainSearchAndSelectedSegments['main']['departureairportC'][0];
            $response['departureAirportN'] = $mainSearchAndSelectedSegments['main']['departureairport'][0];
            
            $response['arrivalairportN'] = $mainSearchAndSelectedSegments['main']['arrivalairport'][0];
            $response['arrivalairport']  = $mainSearchAndSelectedSegments['main']['arrivalairportC'][0];
            
            $response['fromDate']     = $mainSearchAndSelectedSegments['main']['fromDate'][0];
            $response['toDate']       = $mainSearchAndSelectedSegments['main']['toDate'][0];
            $response['flexibleDate'] = $mainSearchAndSelectedSegments['main']['arrivalairport'][0];
            
            $response['cabinSelect'] = $mainSearchAndSelectedSegments['main']['cabinselect'];
            $response['cabinName']   = $mainSearchAndSelectedSegments['main']['response[cabinName]'];
            
            $response['adultsSelect']   = $mainSearchAndSelectedSegments['main']['adultsselect'];
            $response['childrenSelect'] = $mainSearchAndSelectedSegments['main']['childsselect'];
            $response['infantsSelect']  = $mainSearchAndSelectedSegments['main']['infantsselect'];
            
            $response['one_way']           = 0;
            $response['multi_destination'] = 0;
            
            $response['note'] = '';
            
            $response['destinations'] = [];
            
            $response['ADT'] = $mainSearchAndSelectedSegments['main']['response[ADT]'];
            $response['CNN'] = $mainSearchAndSelectedSegments['main']['response[CNN]'];
            $response['INF'] = $mainSearchAndSelectedSegments['main']['response[INF]'];
            
            // [0] because all the result should have the same currency
            $response['currency_code'] = $mainSearchAndSelectedSegments['main']['currency_code'];
            $response['currency']      = $mainSearchAndSelectedSegments['main']['currency_code'];
            
            $response['num_in_party'] = $mainSearchAndSelectedSegments['main']['number_in_party'];
            
            foreach ($response['outbound'] as $key => $outbound) {
                //[0] because we have one segment record per outbound record
                $segmentRec = $outbound["segments"][0][0];
                
                if (!isset($response['minimum_price']) || $segmentRec['price_attr'] < $response['minimum_price']) {
                    $response['minimum_price'] = $segmentRec['price_attr'];
                }
                
                if (!isset($response['maximum_price']) || $segmentRec['price_attr'] > $response['maximum_price']) {
                    $response['maximum_price'] = $segmentRec['price_attr'];
                }
                
                if (!isset($response['duration']) || $segmentRec['flight_duration_attr'] < $response['minimum_duration']) {
                    $response['minimum_duration'] = $segmentRec['flight_duration_attr'];
                }
                
                if (!isset($response['duration']) || $segmentRec['flight_duration_attr'] > $response['maximum_duration']) {
                    $response['maximum_duration'] = $segmentRec['flight_duration_attr'];
                }
                $price                                                                                            = round($segmentRec['price_attr'], 2);
                $response['departure_airports'][$segmentRec['departure_airport_code']][$segmentRec['price_attr']] = array('price' => $price, 'city' => $segmentRec['departure_airport_city'], 'price_attr' => $segmentRec['price_attr']);
                
                if(!isset($response['airlines'][$segmentRec['airline']])){
                    $response['airlines'][$segmentRec['airline']] = array('name' => $segmentRec['airline_name'], 'amount_attr' => $segmentRec['price_attr'], 'amount' => $price, 'airline_logo' => $segmentRec['airline_logo']);
                }elseif($segmentRec['price_attr'] < $response['airlines'][$segmentRec['airline']]['amount_attr']){
                    $response['airlines'][$segmentRec['airline']]['amount'] = $price;
                    $response['airlines'][$segmentRec['airline']]['amount_attr'] = $segmentRec['price_attr'];
                }
            }
            
            $response['enableCancelation'] = true; //$this->enableCancelation;
            $response['enableRefundable']  = true; //$this->enableRefundable;
        } else {
            
            $segementIndex = isset($mainSearchAndSelectedSegments['nextSegment']) ? $mainSearchAndSelectedSegments['nextSegment'] : 1;
            
            if (isset($mainSearchAndSelectedSegments['main']['arrivalairport'][$segementIndex])) {
                $request->request->set('arrivalairport', array($mainSearchAndSelectedSegments['main']['arrivalairport'][$segementIndex]));
            }
            
            if (isset($mainSearchAndSelectedSegments['main']['arrivalairportC'][$segementIndex])) {
                $request->request->set('arrivalairportC', array($mainSearchAndSelectedSegments['main']['arrivalairportC'][$segementIndex]));
            }
            
            if (isset($mainSearchAndSelectedSegments['main']['departureairport'][$segementIndex])) {
                $request->request->set('departureairport', array($mainSearchAndSelectedSegments['main']['departureairport'][$segementIndex]));
            }
            
            if (isset($mainSearchAndSelectedSegments['main']['departureairportC'][$segementIndex])) {
                $request->request->set('departureairportC', array($mainSearchAndSelectedSegments['main']['departureairportC'][$segementIndex]));
            }
            
            if (isset($mainSearchAndSelectedSegments['main']['fromDate'][$segementIndex])) {
                $request->request->set('fromDate', array($mainSearchAndSelectedSegments['main']['fromDate'][$segementIndex]));
            }
            
            if (isset($mainSearchAndSelectedSegments['main']['toDate'][0])) {
                $request->request->set('toDate', array($mainSearchAndSelectedSegments['main']['toDate'][0]));
            }
            
            $request->request->set('oneway', 1);
            
            if (isset($mainSearchAndSelectedSegments['main']['cabinselect'])) {
                $request->request->set('cabinselect', $mainSearchAndSelectedSegments['main']['cabinselect']);
            }
            
            if (sizeof($mainSearchAndSelectedSegments['main']['departureairport']) == $segementIndex + 1) {
                $this->data['isbooking'] = 1;
            }
            
            $response = $this->get('FlightServices')->flightAvailabilitySearchNew();
            
            if (!isset($response['outbound'])) {
                $this->addWarningNotification($this->translator->trans("We were not able to get results for you, please try again later or change your criteria"), 0);
                return $this->redirectToLangRoute('_flight_booking', array());
            }
            
            $response['departureCityAirport'] = $response['arrivalCityAirport']   = '';
        }
        
        $response['request']         = $mainSearchAndSelectedSegments['main'];
        $response['selectedRequest'] = $mainSearchAndSelectedSegments;
        
        if (isset($response['no_data']) && $response['no_data']) {
            
            $this->addWarningNotification("There are no flights that match your search criteria.", 0);
            return $this->redirectToLangRoute('_flight_booking', array());
        }
        
        if (!empty($response)) {
            if (isset($response['departure_airports'])) {
                foreach ($response['departure_airports'] as $code => $deptair) {
                    ksort($deptair);
                    $response['departure_airports'][$code] = reset($deptair);
                }
            }
            
            $_deptCityAirport = $this->get('FlightRepositoryServices')->findAirport($response['departureAirport']);
            $_arriCityAirport = $this->get('FlightRepositoryServices')->findAirport($response['arrivalairport']);
            
            $response['departureCityAirport'] = $_deptCityAirport->getCity();
            $response['arrivalCityAirport']   = $_arriCityAirport->getCity();
        }
        
        if ($this->data['aliasseo'] == '') {
            $this->seoKeywordFiller($seotitle, $seodescription, $seokeywords);
        }
        
        $userId             = $this->userGetID();
        $userArray          = $this->get('UserServices')->getUserDetails(array('id' => $userId));
        $userCorpoAccountId = $userArray[0]['cu_corpoAccountId'];
        
        $this->data['is_corporate_account_with_self_approval'] = $this->get('CorpoApprovalFlowServices')->userAllowedToApprove($userId, $userCorpoAccountId);
        //        if (!$this->data['is_corporate_account_with_self_approval']) {
        //            $this->addWarningNotification("Airline booking is temporarily disabled because of the payment gateway upgrade, use your corporate account for booking if you have one, otherwise send a request to <a href='mailto:sa@touristtube.com' title='{{ 'sa@touristtube.com' }}'>sa@touristtube.com</a>", 0);
        //        }
        
        if(isset($response['airlines']) && sizeof($response['airlines']) > 1){
            /** we need to use the prices provided by $airlines
             in order to fix MIN and MAX price from outbound/inbound
             NOTE: I'm not touching other portion of the PRICE codes as I
             don't want to get code conflicts from other tasks worked by ANNA/JOEL
             **/
            $priceFix  = array_column($response['airlines'], 'amount_attr');
            $response['minimum_price'] = min($priceFix);
            $response['maximum_price'] = max($priceFix);
        }
        
        $this->data['response'] = $response;
        
        return $this->render('@Flight/flight/flights_new.twig', $this->data);
    }
    /*
     * Round Trip Price Optimization
     *
     * combinedData: 1 came from combined sets (offers from sabre)
     * combinedData: 0 came from standalone set
     */
    public function getReturnSegments($mainSearchAndSelectedSegments)
    {

        $decodedSelectedSegments       = json_decode($mainSearchAndSelectedSegments['segmentsInbound'], true);

        $selectedDepartureFromCombined = ($mainSearchAndSelectedSegments['main']['combinedData']) ? $mainSearchAndSelectedSegments['main']['combinedData'] : 0;
        
        $departure       = array();
        $main            = $mainSearchAndSelectedSegments['main'];
        $selectedSegment = $mainSearchAndSelectedSegments['segments'][0];
        
        $related_one_way = $main['related_one_way'];
        $related_one_way = (isset($related_one_way) && $related_one_way) ? json_decode($related_one_way, true) : array();
        
        $departure['departure_airport']   = $main['departureairportC'][0];
        $departure['departure_date_time'] = $selectedSegment['departure_date_time'];
        $departure['flight_number']       = $selectedSegment['flight_number'];
        $departure['set_key']             = implode(" ", $departure);
        
        $departure['price']           = $main['price'];
        $departure['price_attr']      = $main['price_attr'];
        $departure['base_fare']       = $main['base_fare'];
        $departure['taxes']           = $main['taxes_attr'];
        $departure['related_one_way'] = $related_one_way;
        $departure['selected_price']  = (isset($main['selected_price']) && $main['selected_price']) ? json_decode($main['selected_price'][0], true) : array();
        
        if (strpos($departure['price'], ',') !== false) {
            $departure['price'] = str_replace(',', '', $departure['price']);
        }
        
        $returnSegments   = $departureFlights = [];

        foreach ($decodedSelectedSegments as $key => $selectedSegments) {
            $selectedSegments["segments"][0][0]['flight_type']               = "returning";
            $selectedSegments["passenger_info"][0]['returning_baggage_info'] = $selectedSegments["passenger_info"][0]['leaving_baggage_info'];
            
            $return                 = array();
            $return['price']        = $selectedSegments['segmentsGlob']['price'];
            $return['base_fare']    = $selectedSegments['base_fare_attr'];
            $return['taxes']        = $selectedSegments['taxes_attr'];
            $return['combinedData'] = $selectedSegments['combinedData'];
            
            //Consider this step only If the selected Departure is from combined list and having associated standalone records
            $isSameDeparture = false;
            if ($selectedDepartureFromCombined) {
                
                $departureFlights           = isset($selectedSegments['departure_flight']) ? json_decode($selectedSegments['departure_flight'], true) : [];
                $return['departureFlights'] = $departureFlights;
                
                if (strpos($return['price'], ',') !== false) {
                    $return['price'] = str_replace(',', '', $return['price']);
                }
                
                if (isset($return['combinedData']) && $return['combinedData'] != 0) {
                    //Both Departure and Returns are from combined offers
                    $isSameDeparture = $this->checkIfSameDeparture($departure, $return);
                    //Combined list having the same selected Departure
                    if (!$isSameDeparture) continue;
                    
                    $price    = $return['price'] - $departure['price'];
                    $baseFare = 0;//$return['base_fare'] - $departure['base_fare'];
                    $taxes    = 0;//$return['taxes'] - $departure['taxes'];
                    $selectedSegments['taxes_attr'] = 0;
                    $selectedSegments['base_fare_attr'] = 0;
                } else if(isset($related_one_way) && !empty($related_one_way)) {
                    
                    //Departure is from combined offers, while the return is not
                    //If the Return record is from Standalone list then we should consider the standalone pricing for calculation
                    if ($departure['selected_price']['source'] == 'offer') {
                        //If Departure selected price was from Combined list then PRICE = Return Price + (Departure Standalone Price - Departure Combined Price)
                        $price    = $return['price'] + ($related_one_way['tt_price'] - $departure['selected_price']['price']);
                        $baseFare = $return['base_fare'];// + ($related_one_way['tt_baseFare'] - $departure['selected_price']['base_fare']);
                        $taxes    = $return['taxes'];// + ($related_one_way['tt_taxes'] - $departure['selected_price']['taxes']);
                    } else {
                        //If Departure selected price was from Standalone list then PRICE = Return Price
                        $price    = $return['price'];
                        $baseFare = $return['base_fare'];
                        $taxes    = $return['taxes'];
                    }
                } else continue;
                
                //we wont be including negative prices
                if ($price < 0) continue;
                
                $selectedSegments['segmentsGlob']['price']      = number_format($price, 2, '.', ',');
                $selectedSegments['segmentsGlob']['price_attr'] = $price;
                $selectedSegments['base_fare']                  = $baseFare;
                $selectedSegments['taxes']                      = $taxes;
                $returnSegments[]                               = $selectedSegments;
            } else {
                //If Departure selected price was from Standalone list > If selected Departure was from the Standalone list, So we should ONLY get Return records from Standalone
                // then PRICE = Return Price
                if ($selectedSegments['combinedData'] == 0 || !empty($selectedSegments['related_one_way']) ) {
                    $returnSegments[] = $selectedSegments;
                }
            }
        }
        
        //Get all the flights from the standalone returns list, eliminating duplicates from the previous extracted list
        //by keeping the flight with the lowest price (always flagging the records by combined or not)
        
        $returnSegments = $this->get('FlightFinder')->removeItineraryDuplicates($returnSegments);
        
        return $returnSegments;
    }
    
    /*
     * Same selected Departure flight from the previous step
     * THEREFORE WHEN IT COMES TO RETURNS FROM COMBINED SETS I SUGGEST THAT YOU JUST TAKE THE RETURN OF THE SELECTED COMBINED DEPARTURE,
     * AND PAY ATTENTION A SAME DEPARTURE FLIGHT MIGHT HAVE DIFFERENT FLIGHTS SO WE SHOULD LIST THEM ALL FOR THE SAME DEPARTURE
     */
    
    public function checkIfSameDeparture($departure, $return)
    {
        $result = false;
        
        //nothing to compare
        if (!isset($return['departureFlights']) || empty($return['departureFlights'])) return $result;
        
        foreach ($return['departureFlights'] as $key => $value) {
            $segment                                   = $value['flight_segments'][0];
            $referenceDeparture                        = [];
            $referenceDeparture['departure_airport']   = $segment['departure']['airport']['location_code'];
            $referenceDeparture['departure_date_time'] = $segment['departure']['date_time'];
            $referenceDeparture['flight_number']       = $segment['flight_number']; // $segment['operating_airline']['flight_number'];
            
            if ($departure['departure_airport'] == $referenceDeparture['departure_airport'] &&
                $departure['departure_date_time'] == $referenceDeparture['departure_date_time'] &&
                $departure['flight_number'] == $referenceDeparture['flight_number']
                ) {
                    $result = true;
                    break;
                }
        }
        return $result;
    }
    
    public function FlightDetailsNewAction($seotitle, $seodescription, $seokeywords)
    {
        $response = array();
        $response = $this->get('FlightServices')->flightDetails($seotitle, $seodescription, $seokeywords);

        foreach ($response['flight'] as $key => $flight) {
            if (isset($flight['flight_info'])) {
                foreach ($flight['flight_info'] as $k1 => $flight_info) {
                    if (isset($flight_info['stop_info'])) {
                        
                        $depMin                                                       = $this->get('app.utils')->getMinutesFromTime($flight_info['departure_time']);

                        $arrMin                                                       = $this->get('app.utils')->getMinutesFromTime($flight_info['arrival_time']);

                        $minsDuration                                                 = $this->get('app.utils')->mins_to_duration($arrMin - $depMin);
                     $response['flight'][$key]['flight_info'][$k1]['raw_duration'] =$flight_info['elapsedTime'];
                        
                        foreach ($flight_info['stop_info'] as $k2 => $stop_info) {
                            $sdepMin                                                                        = $this->get('app.utils')->getMinutesFromTime($stop_info['departure_time']);

                           $sarrMin                                                                        = $this->get('app.utils')->getMinutesFromTime($stop_info['arrival_time']);

                            $sminsDuration                                                                  = $this->get('app.utils')->mins_to_duration($sarrMin - $sdepMin);

                            $response['flight'][$key]['flight_info'][$k1]['stop_info'][$k2]['raw_duration'] = $stop_info['elapsedTime'];
                        }
                    }
                }
            }
        }
        
        $response['flightPageName']                  = 'flight-booking-details';
        $response['isFlightDetailsValidFromPayment'] = $this->get('FlightServices')->checkFlightDetailsValidFromPayment($response['payment']);
        
        $this->data = $response;

          return $this->render('@Flight/flight/flight-details_new1.twig', $this->data);

    }
    
    public function FlightDetailsNew1Action()
    {   
        return $this->render('@Flight/flight/flight-details_new1.twig', $this->data);
    }
}
