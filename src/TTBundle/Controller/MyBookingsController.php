<?php

namespace TTBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class MyBookingsController extends DefaultController
{
    private $enableCancelation = true;
    private $defaultCurrency   = "USD";
    private $currencyPCC       = "AED";

    /**
     * Handles my bookings page for new design
     *
     * @return twig
     */
    public function myBookingsAction($seotitle, $seodescription, $seokeywords)
    {
        if (!$this->data['isUserLoggedIn'] || !$this->get('ApiUserServices')->tt_global_isset('userInfo')) {
            return $this->redirectToLangRoute('_log_in');
        }
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($seotitle);
        }
        $commonCode       = $this->commonCode();
        $countItem        = $commonCode['countItem'];
        $pg_start_page    = 0;
        $page             = 1;
        $pg_limit_records = $commonCode['pg_limit_records'];
        $count            = $commonCode['count'];
        $class            = $commonCode['class'];
        $pagination       = '';
        $modules          = $commonCode['modules'];
        $userId           = $commonCode['userId'];

        $this->data['hotelModuleId']  = $commonCode['hotelModuleId'];
        $this->data['flightModuleId'] = $commonCode['flightModuleId'];
        $this->data['dealModuleId']   = $commonCode['dealModuleId'];
        $this->data['modules']        = $commonCode['modules'];
        $this->data['userId']         = $userId;
        $this->data['userEmail']         = $commonCode['userEmail'];
        $this->data['start']          = $pg_start_page;
        $userArr['id']=$commonCode['userId'];
        $userArr['email']=$commonCode['userEmail'];
        $params = array(
            'userId' => $commonCode['userId'],
            'userEmail' => $commonCode['userEmail'],
            'start' => $pg_start_page,
            'limit' => $commonCode['pg_limit_records'],
        );

        $allBookings              = $this->get('MyBookingServices')->getAllMyBookings($params);
        $this->data['myBookings'] = $allBookings;

        $params['count']    = 1;
        $countMyBookingList = $this->get('MyBookingServices')->getAllMyBookings($params);


        if ($countMyBookingList) {
            $countItem  = $countMyBookingList;
            $pagination = $this->get('TTRouteUtils')->getRelatedDiscoverPagination($countItem, $pg_limit_records, $page, $count, $class);
        }
        $this->data['pagination'] = $pagination;
        $this->data['totalCount'] = $countMyBookingList;
        return $this->render('mybookings/my-bookings.twig', $this->data);
    }

    /**
     * Handles my bookings page for new design (filters and pagination)
     * AJAX call
     *
     * @return Json response
     */
    public function myBookingsFilterNewAction()
    {



        $request = $this->get('request');
        $post    = $request->request->all();

        $pg_start_page = 1;
        if (isset($post['start']) && $post['start']) {
            $pg_start_page = $post['start'];
        }

        $commonCode       = $this->commonCode();
        $countItem        = $commonCode['countItem'];
        $page             = $pg_start_page;
        $pg_limit_records = $commonCode['pg_limit_records'];
        $count            = $commonCode['count'];
        $class            = $commonCode['class'];
        $pagination       = '';
        $pg_start_page    = ( $pg_start_page * $pg_limit_records ) - $pg_limit_records;
        $modules          = $commonCode['modules'];
        $userId           = $commonCode['userId'];

        $this->data['hotelModuleId']  = $commonCode['hotelModuleId'];
        $this->data['flightModuleId'] = $commonCode['flightModuleId'];
        $this->data['dealModuleId']   = $commonCode['dealModuleId'];
        $this->data['modules']        = $commonCode['modules'];
        $this->data['userId']         = $userId;
        $this->data['start']          = $pg_start_page;

        $params = array(
            'userId' => $commonCode['userId'],
            'userEmail' => $commonCode['userEmail'],
            'start' => $pg_start_page ,
            'limit' => $commonCode['pg_limit_records'],
        );
        if (isset($post['fromDate']) && $post['fromDate']) {
            $fromDate           = new \DateTime($post['fromDate']);
            $params['fromDate'] = $fromDate->format('Y-m-d');
        }
        if (isset($post['toDate']) && $post['toDate']) {
            $toDate           = new \DateTime($post['toDate']);
            $params['toDate'] = $toDate->format('Y-m-d');
        }

        if (isset($post['flights']) && $post['flights']) {
            $params['types'][] = $this->container->getParameter('MODULE_FLIGHTS');
        }

        if (isset($post['hotels']) && $post['hotels']) {
            $params['types'][] = $this->container->getParameter('MODULE_HOTELS');
        }

        if (isset($post['attractions']) && $post['attractions']) {
            $params['types'][] = $this->container->getParameter('MODULE_DEALS');
        }

        if (isset($post['status']) && $post['status']) {
            switch ($post['status']) {
                case 'past':
                    $params['past']     = 1;
                    $params['status']     = 1;
                    break;
                case 'cancelled':
                    $params['canceled'] = 1;
                    $params['status']     = 2;
                    break;
                case 'upcoming':
                    $params['future']   = 1;
                    $params['status']     = 3;
                    break;
                default:
                    break;
            }
        }

        $allBookings              = $this->get('MyBookingServices')->getAllMyBookings($params);


        $this->data['myBookings'] = $allBookings;

        $params['count']    = 1;
        $countMyBookingList = $this->get('MyBookingServices')->getAllMyBookings($params);

        if ($countMyBookingList) {
            $countItem  = $countMyBookingList;
            $pagination = $this->get('TTRouteUtils')->getRelatedDiscoverPagination($countItem, $pg_limit_records, $page, $count, $class);
        }

        $this->data['pagination'] = $pagination;
        $this->data['totalCount'] = $countMyBookingList;

 /*       $response = new Response(json_encode($allBookings));
        $response->headers->set('Content-Type', 'application/json');
        return $response;*/
        $twigResponse = $this->render('mybookings/my-bookings-info.twig', $this->data)->getContent();

        $return                 = array();
        $return['twigResponse'] = $twigResponse;
        $return['pagination']   = $pagination;
        $return['totalCount']   = $countMyBookingList;
        $response = new Response(json_encode($return));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * function that returns an array full of variable needed in my booking page
     *
     * @return array
     */
    private function commonCode()
    {
        $commonArray = array();

        $userId           = $this->data['USERID'];
        $userEmail           = $this->data['USEREMAIL'];
        $pg_limit_records = $this->container->getParameter('TRAVEL_APPROVAL_NUMBER_OF_RECORDS');
        $count            = $this->container->getParameter('TRAVEL_APPROVAL_PAGINATION_COUNT');
        $class            = "approval_pagination";
        $countItem        = 0;

        $modules        = array(
            [
                'name' => $this->translator->trans('All Bookings'),
                'id' => $this->container->getParameter('MODULE_DEFAULT')
            ],
            [
                'name' => $this->translator->trans('Flights'),
                'id' => $this->container->getParameter('MODULE_FLIGHTS')
            ],
            [
                'name' => $this->translator->trans('Hotels'),
                'id' => $this->container->getParameter('MODULE_HOTELS')
            ],
            [
                'name' => $this->translator->trans('Deals'),
                'id' => $this->container->getParameter('MODULE_DEALS')
            ]
        );
        $hotelModuleId  = $this->container->getParameter('MODULE_HOTELS');
        $flightModuleId = $this->container->getParameter('MODULE_FLIGHTS');
        $dealModuleId   = $this->container->getParameter('MODULE_DEALS');

        $commonArray['userId']           = $userId;
        $commonArray['userEmail']           = $userEmail;
        $commonArray['pg_limit_records'] = $pg_limit_records;
        $commonArray['count']            = $count;
        $commonArray['class']            = $class;
        $commonArray['countItem']        = $countItem;
        $commonArray['modules']          = $modules;
        $commonArray['hotelModuleId']    = $hotelModuleId;
        $commonArray['flightModuleId']   = $flightModuleId;
        $commonArray['dealModuleId']     = $dealModuleId;

        return $commonArray;
    }

    public function myBookingsSearchInvoiceAction()
    {

        $request  = Request::createFromGlobals();
        $isMobile = $request->request->get('from_mobile', 0);
        $valid    = true;

        $data  = array();
        $type  = $request->request->get('bookingType');
        $query = $request->request->get('type');

        if ($this->data['isUserLoggedIn']) {
            if ($isMobile) {
                $userEmail = ($isMobile) ? $request->request->get('user_email', 0) : $this->userGetEmail();
                $userId    = ($isMobile) ? $request->request->get('userId', 0) : $this->userGetID();

                if (empty($userEmail)) {
                    $data['error'] = $this->translator->trans('User email required.');
                    $valid         = false;
                } elseif (empty($userId)) {
                    $data['error'] = $this->translator->trans('Logged In UserId required.');
                    $valid         = false;
                }
            }

            //Flights Section
            if (in_array($query, array('flight', 'both'))) {
                $userInfo         = $this->get('ApiUserServices')->tt_global_get('userInfo');
                $userArr['id']    = $userInfo['id'];
                $userArr['email'] = $userInfo['email'];

                $userArr['invoiceDate_to']   = $request->request->get('invoiceDate_to');
                $userArr['invoiceDate_from'] = $request->request->get('invoiceDate_from');

                $count          = $this->get('MyBookingServices')->getFlightsListInvoiceCount($userArr);
                $showMoreFlight = ($request->request->get('showMoreFlight') && intval($count[0][1]) > 10) ? 1 : 0;

                $pagebig = intval($request->request->get('page', 1));

                if (!$isMobile) {
                    if ($pagebig < 1) $pagebig = 1;
                    $page    = $pagebig - 1;
                    $limit   = (!$showMoreFlight) ? 10 : 3;
                }
                else {
                    $limit = ($showMoreFlight) ? 10 : 3;
                    $page  = $pagebig;
                }

                $offset = $page * $limit;

                if (!$isMobile) {
                    $flightBookings = array(
                        'flightPaging' => $this->get('TTRouteUtils')->getRelatedDiscoverPagination($count, 10, $pagebig),
                        'flightBookings' => $this->render('default/my-bookings-flight-loop.twig', array("flightBookings" => $this->get('MyBookingServices')->myBookingsSearchInvoice($userArr, $type, $limit, $offset),
                            'showmore' => $showMoreFlight))->getContent()
                    );
                } else {
                    $flightBookings = array("flightBookings" => $this->get('MyBookingServices')->myBookingsSearchInvoice($userArr, $type, $limit, $offset));
                }

                $data += $flightBookings;
            }

            if ($isMobile && isset($data['error'])) {
                $data = array('error' => $data['error']);
            }
        } else {
            $data = array('error' => $this->translator->trans('Please Login'));
        }
        $res = new Response(json_encode($data));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function myFlightBookingApiAction()
    {
        $response = new JsonResponse();
        $request  = $this->getRequest();

        $isMobile = $request->request->get('from_mobile', 0);

        $pagebig                       = intval($request->request->get('page', 1));
        if ($pagebig < 1) $pagebig                       = 1;
        $page                          = $pagebig - 1;
        $limit                         = 10;
        $offset                        = $page * $limit;
        $responseArr                   = array();
        $all_info                      = array();
        $responseArr['showmore']       = 0;
        $responseArr['flightBookings'] = '';
        $search_paging_output          = '';
        if ($this->data['isUserLoggedIn']) {
            $userInfo = $this->get('ApiUserServices')->tt_global_get('userInfo');
            $userId   = $userInfo['id'];

            $userArr['id']    = $userId;
            $userArr['email'] = $userInfo['email'];

            $total = $this->get('MyBookingServices')->getFlightsCount($userArr);

            $records = $this->get('MyBookingServices')->getFlightsList($userArr, 0, $limit, $offset);

            if ($total > 0) {
                $responseArr['flightBookings'] = $this->get('MyBookingServices')->myFlightBookings($records);

                if (!$isMobile) $search_paging_output = $this->get('TTRouteUtils')->getRelatedDiscoverPagination($total, $limit, $pagebig);
            } else {
                $responseArr['no_flight_bookings'] = 1;
            }
            $all_info['status']  = 200;
            $all_info['message'] = "success";
        } else {
            $all_info['status']  = 401;
            $all_info['message'] = $this->translator->trans("Please login");
        }

        if ($isMobile) {
            $responseArr['paging'] = $search_paging_output;
            $all_info              = $responseArr;
        } else {
            $all_info['data']   = $this->render('default/my-bookings-flight-loop.twig', $responseArr)->getContent();
            $all_info['paging'] = $search_paging_output;
        }


        $response->setData($all_info);
        return $response;
    }

    public function myFlightDetailsAction($seotitle, $seodescription, $seokeywords)
    {
        $userInfo = $this->get('ApiUserServices')->tt_global_get('userInfo');
        $request  = $this->getRequest();
        if ($request->get('forwardToEmail')) {
            $forwardToEmail = $request->get('forwardToEmail');
            $this->get('FlightServices')->sendEmailFromFlightDetails($this, $forwardToEmail, $request->query->get('transaction_id'));
            return $this->redirectToRoute('_flight_details', ['transaction_id' => $request->query->get('transaction_id')]);
        }

        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($seotitle);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($seodescription);
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($seokeywords);
        }

        $request = Request::createFromGlobals();

        $uuid                           = $request->query->get('transaction_id');
        $this->data['showHeaderSearch'] = 0;
        $this->data['isUserLoggedIn']   = ($this->data['isUserLoggedIn'] ? 1 : 0);



        $passengersArray = array();
        $isMobile        = $request->request->get('from_mobile', 0);

        /* if (!$this->data['isUserLoggedIn'] || !$this->get('ApiUserServices')->tt_global_isset('userInfo')) {
          if ($isMobile) {
          $data['passengers'] = [];
          $data['flight'] = [];
          $data['message'] = $this->translator->trans('Please Login');
          $data['status'] = 333;

          $response = new JsonResponse();
          $response->setData($data);
          return $response;
          }
          return $this->redirectToLangRoute('_log_in');
          } */


        // $userId = $userInfo['id'];
        //if ($this->data['isUserLoggedIn']) {
        $pnr = $this->getDoctrine()->getRepository('PaymentBundle:Payment')->find(urldecode($uuid));

        if (!$pnr) {
            return $this->redirectToLangRoute('_my_bookings');
        }

        $checkOwnerShip = $userInfo && (($pnr->getUserId() == $userInfo['id']) || ($pnr->getPassengerNameRecord()->getEmail() == $userInfo['email']) ? 1 : 0);

        $passengers     = $pnr->getPassengerNameRecord()->getPassengerDetails();
        $flightDetail   = $pnr->getPassengerNameRecord()->getFlightDetails();
        $pnrStatus      = $pnr->getPassengerNameRecord()->getStatus();
        $flightSegments = $this->get('MyBookingServices')->flightDetails($flightDetail);

        $userEmail = $pnr->getPassengerNameRecord()->getEmail();

        $now                = new \DateTime();
        $raw_departure_date = new \DateTime($flightSegments['leaving']['flight_info'][0]['raw_departure_date']);

        if ($raw_departure_date < $now) {
            $flightSegments['upcoming'] = 0;
        } else {
            $flightSegments['upcoming'] = 1;
        }

        $flightSegments['leaving']['transaction_id']   = $uuid;
        $flightSegments['leaving']['multiDestination'] = $pnr->getPassengerNameRecord()->getFlightInfo()->isMultiDestination();

        foreach ($passengers as $key => $passenger) {
            $passengersArray[$key]['first_name']             = $passenger->getFirstName();
            $passengersArray[$key]['surname']                = $passenger->getSurname();
            $passengersArray[$key]['type']                   = $passenger->getType();
            $passengersArray[$key]['fare_calc_line']         = $passenger->getFareCalcLine();
            $passengersArray[$key]['leaving_baggage_info']   = $passenger->getLeavingBaggageInfo();
            $passengersArray[$key]['returning_baggage_info'] = $passenger->getReturningBaggageInfo() == null ? "" : $passenger->getReturningBaggageInfo();
            $passengersArray[$key]['ticket_number']          = $passenger->getTicketNumber();
        }

        $flightSegments['base_fare'] = number_format($pnr->getPassengerNameRecord()->getFlightInfo()->getDisplayBaseFare(), 2, '.', ',');
        $flightSegments['taxes']     = number_format($pnr->getPassengerNameRecord()->getFlightInfo()->getDisplayTaxes(), 2, '.', ',');

        //$priceInfo = $this->get('PayFortServices')->getPriceInfo($pnr);

        $flightSegments['price']         = number_format($pnr->getPassengerNameRecord()->getFlightInfo()->getDisplayPrice(), 2, '.', ',');
        $flightSegments['currency_code'] = $pnr->getPassengerNameRecord()->getFlightInfo()->getDisplayCurrency();
        $symbol                          = $this->getDoctrine()->getRepository('TTBundle:CurrencyRate')->findOneBycurrencyCode($flightSegments['currency_code']);

        if ($pnr->getCouponCode() && $pnr->getDisplayAmount() != $pnr->getDisplayOriginalAmount()) {
            $flightSegments['discounted_price'] = round($pnr->getDisplayAmount() + 0, 2);
        }

        $flightSegments['currency']   = $symbol ? $symbol->getSymbol() : $flightSegments['currency_code'];
        $flightSegments['refundable'] = $pnr->getPassengerNameRecord()->getFlightInfo()->isRefundable();
        $flightSegments['pnr_id']     = $pnr->getPassengerNameRecord()->getPnr();
        $flightSegments['id']         = $pnr->getPassengerNameRecord()->getId();

        //}
        if ($isMobile) {
            $data['passengers']     = $passengersArray;
            $data['flight']         = $flightSegments;
            $data['message']        = $this->translator->trans('Success');
            $data['checkOwnerShip'] = $checkOwnerShip;
            $data['pnr_status']     = $pnrStatus;

            $response = new JsonResponse();
            $response->setData($data);
            return $response;
        } else {
            $this->data['passengers']        = $passengersArray;
            $this->data['flight']            = $flightSegments;
            $this->data['message']           = $this->translator->trans('Success');
            $this->data['enableCancelation'] = $this->enableCancelation;
            $this->data['checkOwnerShip']    = $checkOwnerShip;
            $this->data['pnr_status']        = $pnrStatus;
            //if ($pnr->getUserId() == $userId) {
            return $this->render('@Flight/flight/flight-detailed.twig', $this->data);
            /* } else {
              return $this->render('@Flight/flight/flight-no-data.twig', $this->data);
              } */
        }
    }

    public function myBookingsSearchAction()
    {

        $request  = Request::createFromGlobals();
        $isMobile = $request->request->get('from_mobile', 0);
        $valid    = true;

        $data  = array();
        $type  = $request->request->get('bookingType');
        $query = $request->request->get('type');

        if ($this->data['isUserLoggedIn']) {
            if ($isMobile) {
                $userEmail = ($isMobile) ? $request->request->get('user_email', 0) : $this->userGetEmail();
                $userId    = ($isMobile) ? $request->request->get('userId', 0) : $this->userGetID();

                if (empty($userEmail)) {
                    $data['error'] = $this->translator->trans('User email required.');
                    $valid         = false;
                } elseif (empty($userId)) {
                    $data['error'] = $this->translator->trans('Logged In UserId required.');
                    $valid         = false;
                }
            }

            // Hotels Section
            if ($valid && (in_array($query, array('hotel', 'both')))) {
                $data += $this->myBookingsSearchHotel();
            }

            // Deal Section
            if ($valid && (in_array($query, array('deal', 'both')))) {
                $option         = array();
                $option['id']   = $this->userGetID();
                $option['type'] = $type;
                $data           += $this->myBookingsDealSearch($option);
            }

            //Flights Section
            if (in_array($query, array('flight', 'both'))) {
                $userInfo         = $this->get('ApiUserServices')->tt_global_get('userInfo');
                $userArr['id']    = $userInfo['id'];
                $userArr['email'] = $userInfo['email'];

                switch ($type) {
                    case 1:
                    case 3:
                        $count = $this->get('MyBookingServices')->getPastUpcomingFLightsCount($userArr, $type);
                        break;
                    case 2:
                        $count = $this->get('MyBookingServices')->getCancelledFLightsCount($userArr);
                        break;
                    default:
                        $count = $this->get('MyBookingServices')->getFlightsCount($userArr);
                        break;
                }

                $showMoreFlight = ($request->request->get('showMoreFlight') && intval($count) > 3) ? 1 : 0;

                $pagebig = intval($request->request->get('page', 1));

                if (!$isMobile) {
                    if ($pagebig < 1) $pagebig = 1;
                    $page    = $pagebig - 1;
                    $limit   = (!$showMoreFlight) ? 10 : 3;
                }
                else {
                    $limit = ($showMoreFlight) ? 10 : 3;
                    $page  = $pagebig;
                }

                $offset = $page * $limit;

                if (!$isMobile) {
                    $flightBookings = array(
                        'flightPaging' => $this->get('TTRouteUtils')->getRelatedDiscoverPagination($count, 10, $pagebig),
                        'flightBookings' => $this->render('default/my-bookings-flight-loop.twig', array("flightBookings" => $this->get('MyBookingServices')->myBookingsSearchFlight($userArr, $type, $limit, $offset),
                            'showmore' => $showMoreFlight, 'LanguageGet' => $this->LanguageGet()))->getContent()
                    );
                } else {
                    $flightBookings                            = array("flightBookings" => $this->get('MyBookingServices')->myBookingsSearchFlight($userArr, $type, $limit, $offset));
                    $flightBookings['total_flights_past']      = $this->get('MyBookingServices')->getPastUpcomingFLightsCount($userArr, 1);
                    $flightBookings['total_flights_cancelled'] = $this->get('MyBookingServices')->getCancelledFLightsCount($userArr);
                    $flightBookings['total_flights_upcoming']  = $this->get('MyBookingServices')->getPastUpcomingFLightsCount($userArr, 2);
                    $flightBookings['total_flights']           = $flightBookings['total_flights_past'] + $flightBookings['total_flights_cancelled'] + $flightBookings['total_flights_upcoming'];
                }

                $data += $flightBookings;
            }

            if ($isMobile && isset($data['error'])) {
                $data = array('error' => $data['error']);
            }
        } else {
            $data = array('error' => $this->translator->trans('Please Login'));
        }
        $res = new Response(json_encode($data));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function myBookingsSearchHotel()
    {
        // Only used on web version.
        $request = Request::createFromGlobals();

        $page        = $request->request->get('page', 0);
        $bookingType = $request->request->get('bookingType', 0);
        $showMore    = $request->request->get('showMore', 0);
        $isMobile    = $request->request->get('from_mobile', 0);

        $limit       = ($isMobile && !$showMore) ? 3 : 10;
        $currentpage = $page;
        if ($currentpage < 0) {
            $currentpage = 0;
        }

        $allInfo  = array();
        $options  = array();
        $optionsC = array('n_results' => true);

        $userEmail = ($isMobile) ? $request->request->get('user_email', 0) : $this->userGetEmail();
        $userId    = ($isMobile) ? $request->request->get('userId', 0) : $this->userGetID();

        $options['email']   = $optionsC['email']  = $userEmail;
        $options['userId']  = $optionsC['userId'] = $userId;

        if (!empty($options)) {
            $options['page']  = $currentpage;
            $options['limit'] = $limit;

            if (!empty($bookingType)) {
                switch ($bookingType) {
                    case 1:
                        $options['past']      = 1;
                        $optionsC['past']     = 1;
                        $options['order']     = array('fromDate' => 'DESC');
                        break;
                    case 2:
                        $options['canceled']  = 1;
                        $optionsC['canceled'] = 1;
                        $options['order']     = array('fromDate' => 'DESC');
                        break;
                    case 3:
                        $options['future']    = 1;
                        $optionsC['future']   = 1;
                        $options['order']     = array('fromDate' => 'ASC');
                        break;
                }
            }
            $this->data['hotelBookings'] = $this->searchHotelBookings($options);

            if (!$isMobile) {
                $page = $page + 1;
                if ($page < 1) {
                    $page = 1;
                }
                $hotelBookingsInfoCount = $this->searchHotelBookings($optionsC);
                $searchPagingOutput     = $this->get('TTRouteUtils')->getRelatedDiscoverPagination($hotelBookingsInfoCount, $limit, $page, 5);

                $this->data['showMore'] = ($showMore && intval($hotelBookingsInfoCount) > 3) ? 1 : 0;

                $allInfo = array(
                    'paging' => $searchPagingOutput,
                    'hotelBookings' => $this->render('@Hotel/hotel-my-bookings-row.twig', $this->data)->getContent()
                );
            } else {
                $allInfo = array(
                    'hotelBookings' => $this->data['hotelBookings'],
                    'total_hotels_past' => 0,
                    'total_hotels_cancelled' => 0,
                    'total_hotels_upcoming' => 0,
                    'total_hotels' => 0
                );
                if (!empty($bookingType)) {
                    array_pop($optionsC);
                }
                $allInfo['total_hotels'] = $this->searchHotelBookings($optionsC);

                $optionsC['past']             = 1;
                $allInfo['total_hotels_past'] = $this->searchHotelBookings($optionsC);

                array_pop($optionsC);
                $optionsC['canceled']              = 1;
                $allInfo['total_hotels_cancelled'] = $this->searchHotelBookings($optionsC);

                array_pop($optionsC);
                $optionsC['future']               = 1;
                $allInfo['total_hotels_upcoming'] = $this->searchHotelBookings($optionsC);
            }
        }

        return $allInfo;
    }

    /**
     * This method will retrieve all/upcomming/cancelled/past DEAL booking through AJAX call
     *
     * @param $criteria to be filtered (all/upcomming/cancelled/past)
     * @return json formatted response
     * */
    public function myBookingsDealSearch($criteria = array())
    {

        $bookingInfo = array();

        if (empty($criteria)) {
            return $bookingInfo;
        }

        $criteria['showDetails'] = 1;

        switch ($criteria['type']) {
            case 1:
                $criteria['type'] = 'past';
                break;
            case 2:
                $criteria['type'] = 'cancelled';
                break;
            case 3:
                $criteria['type'] = 'future';
                break;
            default:
                $criteria['type'] = 'details';
                break;
        }

        $dealBookings = $this->get('MyBookingServices')->getMyDealBookings($criteria);

        if (count($dealBookings) > 0) {

            $this->data['dealBookings'] = $dealBookings;

            $userArr['type']          = 'all';
            $this->data['totalDeals'] = $this->get('MyBookingServices')->getMyDealBookings($criteria);

            $userArr['type']                = 'future';
            $this->data['totalFutureDeals'] = $this->get('MyBookingServices')->getMyDealBookings($criteria);

            $userArr['type']              = 'past';
            $this->data['totalPastDeals'] = $this->get('MyBookingServices')->getMyDealBookings($criteria);

            $userArr['type']                   = 'cancelled';
            $this->data['totalCancelledDeals'] = $this->get('MyBookingServices')->getMyDealBookings($criteria);

            $this->data['showMore'] = ($this->data['totalDeals'] > 3) ? 1 : 0;
        } else {
            $this->data['totalDeals']          = 0;
            $this->data['totalFutureDeals']    = 0;
            $this->data['totalPastDeals']      = 0;
            $this->data['totalCancelledDeals'] = 0;
            $this->data['showMore']            = 0;
        }
        $bookingInfo = array(
            'dealBookings' => $this->render('default/my-bookings-deals-loop.twig', $this->data)->getContent()
        );

        return $bookingInfo;
    }

    public function searchHotelBookings($options)
    {
        $where      = '';
        $whereArray = array();
        $params     = array();
        $nlimit     = '';
        $skip       = 0;

        $em = $this->getDoctrine()->getManager();

        // WHERE
        $whereArray[]     = '(hr.email = :email OR hr.userId = :userId)';
        $params['email']  = $options['email'];
        $params['userId'] = $options['userId'];
        $whereArray[]     = $em->createQueryBuilder()->expr()->eq('hr.transactionSourceId', $this->getDoctrine()->getRepository('TTBundle:TransactionSource')->findOneByCode('web')->getId());
        if (isset($options['canceled']) && !is_null($options['canceled'])) {
            $whereArray[]             = $em->createQueryBuilder()->expr()->eq('hr.reservationStatus', ':canceledStatus');
            $params['canceledStatus'] = $this->container->getParameter('hotels')['reservation_canceled'];
        } elseif (isset($options['past']) && !is_null($options['past'])) {
            $whereArray[]        = $em->createQueryBuilder()->expr()->in('hr.reservationStatus', array($this->container->getParameter('hotels')['reservation_confirmed'], $this->container->getParameter('hotels')['reservation_modified']));
            $whereArray[]        = $em->createQueryBuilder()->expr()->lt('hr.fromDate', ':todayDate');
            $params['todayDate'] = date('Y-m-d');
        } elseif (isset($options['future']) && !is_null($options['future'])) {
            $whereArray[]        = $em->createQueryBuilder()->expr()->in('hr.reservationStatus', array($this->container->getParameter('hotels')['reservation_confirmed'], $this->container->getParameter('hotels')['reservation_modified']));
            $whereArray[]        = $em->createQueryBuilder()->expr()->gte('hr.fromDate', ':todayDate');
            $params['todayDate'] = date('Y-m-d');
        } else {
            $whereArray[] = $em->createQueryBuilder()->expr()->in('hr.reservationStatus', array(
                $this->container->getParameter('hotels')['reservation_confirmed'],
                $this->container->getParameter('hotels')['reservation_modified'],
                $this->container->getParameter('hotels')['reservation_canceled']
            ));
        }

        if (!empty($whereArray)) {
            $where = implode(" AND ", $whereArray);
        }

        // LIMIT
        if (isset($options['limit']) && !is_null($options['limit'])) {
            $nlimit = intval($options['limit']);
            $skip   = intval($options['page']) * $nlimit;
        }

        if (!isset($options['n_results'])) {
            $langCode  = $this->LanguageGet();
            $hotelName = ($langCode != 'en') ? 'COALESCE(ml.name, ch.name)' : 'ch.name';

            $qb = $em->createQueryBuilder()
                ->select('hr.id, hr.reference, hr.reservationProcessKey, hr.reservationProcessPassword, hr.controlNumber, '
                    .'hr.customerCurrency, hr.customerGrandTotal, '
                    .'hr.hotelCurrency, hr.hotelGrandTotal, '
                    .'hr.reservationStatus, hr.fromDate, hr.toDate, '
                    .'hr.hotelId, COALESCE(ah.propertyName, '.$hotelName.') AS hotelName, ch.stars')
                ->from('HotelBundle:HotelReservation', 'hr')
                ->leftJoin('HotelBundle:CmsHotel', 'ch', 'WITH', 'ch.id = hr.hotelId AND hr.reservationProcessKey IS NOT NULL')
                ->leftJoin('HotelBundle:AmadeusHotel', 'ah', 'WITH', 'ah.id = hr.hotelId AND hr.reservationProcessKey IS NULL');

            if ($langCode != 'en') {
                $qb->leftJoin('HotelBundle:MlHotel', 'ml', 'WITH', 'ch.id = ml.hotelId and ml.langCode=:langCode');
                $params['langCode'] = $langCode;
            }

            if (!empty($where)) {
                $qb->where("$where");
            }

            if (!empty($params)) {
                $qb->setParameters($params);
            }

            if (!empty($options['order'])) {
                foreach ($options['order'] as $col => $dir) {
                    $qb->orderBy('hr.'.$col, $dir);
                }
            } else {
                $qb->orderBy('hr.creationDate', 'DESC');
            }

            if (!empty($nlimit) && !is_null($nlimit)) {
                $qb->setMaxResults($nlimit)->setFirstResult($skip);
            }

            $query = $qb->getQuery();
            $res   = $query->getScalarResult();

            $reservationIds = array();
            $bookings       = array();
            foreach ($res as $key => $value) {
                $fromDate = strtotime($value['fromDate']);
                $toDate   = strtotime($value['toDate']);
                if ($value['reservationProcessKey']) {
                    $mainImage = $this->get("HRSServices")->getHotelMainImage($value['hotelId']);
                } else {
                    $mainImage = $this->get("HRSServices")->getHotelMainImage(0);
                }
                $reservationIds[] = $value['id'];

                $bookings[$value['id']] = array(
                    'reference' => $value['reference'],
                    'hotelId' => $value['hotelId'],
                    'hotelDetails' => array(
                        'name' => $value['hotelName'],
                        'stars' => $value['stars'],
                        'mainImage' => $mainImage[1],
                        'hotelNameURL' => $this->get('app.utils')->cleanTitleData($value['hotelName']),
                    ),
                    'reservationProcessKey' => $value['reservationProcessKey'],
                    'reservationProcessPassword' => $value['reservationProcessPassword'],
                    'controlNumber' => $value['controlNumber'],
                    'customerCurrency' => $value['customerCurrency'],
                    'customerGrandTotal' => $value['customerGrandTotal'],
                    'roomCustomerGrandTotal' => 0,
                    'hotelCurrency' => $value['hotelCurrency'],
                    'hotelGrandTotal' => $value['hotelGrandTotal'],
                    'roomHotelGrandTotal' => 0,
                    'reservationStatus' => $value['reservationStatus'],
                    'fromDate' => array(
                        'day' => date('d', $fromDate),
                        'monthAndYear' => date('M Y', $fromDate),
                        'dayOfWeek' => date('l', $fromDate),
                    ),
                    'toDate' => array(
                        'day' => date('d', $toDate),
                        'monthAndYear' => date('M Y', $toDate),
                        'dayOfWeek' => date('l', $toDate),
                    ),
                );
            }

            $qb = $em->createQueryBuilder()
                ->select('hrr.hotelReservationId, hrr.hotelRoomPrice, hrr.customerRoomPrice, hrr.roomStatus')
                ->from('HotelBundle:HotelRoomReservation', 'hrr')
                ->innerJoin('HotelBundle:HotelReservation', 'hr', 'WITH', 'hr.id = hrr.hotelReservationId')
                ->where("hrr.hotelReservationId IN (:hotelReservationIds) AND hr.reservationStatus != 'Canceled'")
                ->setParameter('hotelReservationIds', $reservationIds);

            $query = $qb->getQuery();
            $res   = $query->getScalarResult();
            foreach ($res as $value) {
                if ($value['roomStatus'] != 'Canceled') {
                    $bookings[$value['hotelReservationId']]['roomCustomerGrandTotal'] += $value['customerRoomPrice'];
                    $bookings[$value['hotelReservationId']]['roomHotelGrandTotal']    += $value['hotelRoomPrice'];
                }
            }

            foreach ($bookings as $key => $value) {
                if ($value['reservationStatus'] != 'Canceled' && $value['roomCustomerGrandTotal'] > 0) {
                    $bookings[$key]['customerGrandTotal'] = $bookings[$key]['roomCustomerGrandTotal'];
                    $bookings[$key]['hotelGrandTotal']    = $bookings[$key]['roomHotelGrandTotal'];
                }
                unset($bookings[$key]['roomCustomerGrandTotal']);
                unset($bookings[$key]['roomHotelGrandTotal']);
            }

            return array_values($bookings);
        } else {
            $qr = "SELECT COUNT(hr.id) FROM HotelBundle:HotelReservation hr ";
            if (!empty($where)) {
                $qr .= "WHERE $where";
            }
            $qb  = $em->createQuery($qr);
            $qb->setParameters($params);
            $res = $qb->getSingleScalarResult();
            return $res;
        }
    }

    public function userGetEmail()
    {
        if (!$this->data['isUserLoggedIn']) {
            return false;
        }
        if (!$this->get('ApiUserServices')->tt_global_isset('userInfo')) {
            return false;
        }
        $userInfo = $this->get('ApiUserServices')->tt_global_get('userInfo');
        return $userInfo['email'];
    }
}
