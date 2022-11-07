<?php

namespace CorporateBundle\Controller;

use DealBundle\Entity\DealBooking;
use DealBundle\Entity\DealBookingPassengers;
use DealBundle\Entity\DealCity;
use DealBundle\Entity\DealDetails;
use DealBundle\Entity\DealImage;
use PaymentBundle\Entity\Payment;
use TTBundle\Entity\Webgeocities;
use TTBundle\Entity\Currency;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session;
use PaymentBundle\Model\Payment as PaymentObj;

class PackagesCorpoController extends CorporateController
{
    /*
     * This handles the lading page for corporate deals. This will display list of top destinations, top attractions and top deals.
     * It is also in that when you search a location/destination we handle the request.
     *
     * @param $seotitle
     * @param $seodescription
     * @param $seokeywords
     *
     * @return array of deals. Top Destinations, Top Attractions, Top Deals.
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function corporateActivitiesAndDealsAction($seotitle, $seodescription, $seokeywords)
    {
        $request = Request::createFromGlobals();

        $this->data['isMobile']             = $request->request->get('from_mobile', 0);
        $this->data['dealblocksearchIndex'] = 1;
        $this->data['pageBannerImage']      = $this->get("TTRouteUtils")->generateMediaURL('/media/images/dealsjumbotrone.png');
        $this->data['needpayment']          = 1;

        $this->data['tourURL']        = $this->generateLangRoute('_corporate_dealSearch_tours');
        $this->data['activityURL']    = $this->generateLangRoute('_corporate_dealSearch_activities');
        $this->data['attractionsURL'] = $this->generateLangRoute('_corporate_dealSearch_attractions');

        $params                = array();
        $topAttractionEncoded  = $this->get('DealServices')->getTopAttractionsPerApi();
        $topAttractionDecoded  = json_decode($topAttractionEncoded, true);
        $params['attractions'] = ($topAttractionDecoded['success']) ? $topAttractionDecoded['data'] : array();
        $params['langCode']    = $this->LanguageGet();
        $dealSC                = $this->get('DealServices')->getDealSearchCriteria($params);

        $attractions               = $this->get('DealServices')->getTopAttractions($dealSC);
        $decoded_attractions       = json_decode($attractions, true);
        $this->data['attractions'] = $decoded_attractions['data'];

        $this->data['needpayment']  = 1;
        $this->data['city']         = '';
        $this->data['startDate']    = '';
        $this->data['endDate']      = '';
        $this->data['webgeoCityId'] = 0;

        // If city was taken from autocomplete, it means it has webgeocity id
        if (isset($post['webgeo_city']) && !empty($post['webgeo_city'])) {
            $params['city']             = $post['webgeo_city'];
            $this->data['city']         = $post['city'];
            $this->data['webgeoCityId'] = $post['webgeo_city'];
        }
        // else check if there is a city that was just inputted manually
        elseif (isset($post['city']) && !empty($post['city'])) {
            $params['cityName'] = $post['city'];
            $this->data['city'] = $post['city'];
        }
        if (isset($post['startDate']) && isset($post['endDate'])) {
            $params['startDate']     = date("m/d/Y", strtotime($post['startDate']));
            $params['endDate']       = date("m/d/Y", strtotime($post['endDate']));
            $this->data['startDate'] = $post['startDate'];
            $this->data['endDate']   = $post['endDate'];
        }

        $params['dynamicSorting'] = true;
        $params['limit']          = $this->container->getParameter('AVERAGE_QUERY_LIMIT');

        $dealSC2                    = $this->get('DealServices')->getDealSearchCriteria($params);
        $destinations               = $this->get('DealServices')->getTopDestinations($dealSC2);
        $decoded_destinations       = json_decode($destinations, true);
        $this->data['destinations'] = $decoded_destinations['data'];

        $deals               = $this->get('DealServices')->getLandingPageTopTours($dealSC2);
        $decoded_deals       = json_decode($deals, true);
        $this->data['deals'] = $decoded_deals['data'];

        return $this->render('@Corporate/tours/corporate-activities-and-deals.twig', $this->data);
    }
    /*
     * This handles the landing page of airport-transport.
     *
     * @return layout for airport-trasnport
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     */

    public function corporateAirportTransportAction($srch = '', $seotitle, $seodescription, $seokeywords)
    {
        $request = Request::createFromGlobals();

        $this->data['tourURL']        = $this->generateLangRoute('_corporate_dealSearch_tours');
        $this->data['activityURL']    = $this->generateLangRoute('_corporate_dealSearch_activities');
        $this->data['attractionsURL'] = $this->generateLangRoute('_corporate_dealSearch_attractions');

        $dealServices             = $this->get('DealServices');
        $apiEncoded               = $dealServices->getDealApiSupplierByParam('City Discovery', 'name');
        $apiDecoded               = json_decode($apiEncoded, true);
        $this->data['apiId']      = $apiDecoded['data']['id'];
        $typeEncoded              = $dealServices->getDealTypeByParam('TRANSFERS', 'name');
        $typeDecoded              = json_decode($typeEncoded, true);
        $this->data['dealTypeId'] = $typeDecoded['data']['id'];

        //This is for step1 in transport page
        $countryEncoded          = $dealServices->getTransferCountryListing();
        $countryDecoded          = json_decode($countryEncoded, true);
        $this->data['countries'] = $countryDecoded['data'];

        //if country
        if ($request->query->has('country') && $request->query->get('country') != '') {
            $this->data['countrySelected'] = $request->query->get('country');
        }

        //This is for secure booking page country listing
        $countryEncoded                      = $dealServices->getCountryList();
        $countryDecoded                      = json_decode($countryEncoded, true);
        $this->data['countryList']           = $countryDecoded['data'];
        //This is for secure booking page mobile country codes
        $mobileCountryEncoded                = $dealServices->getMobileCountryCodeList();
        $mobileDecoded                       = json_decode($mobileCountryEncoded, true);
        $this->data['mobileCountryCodeList'] = $mobileDecoded['data'];

        return $this->render('@Corporate/tours/corporate-airport-transport.twig', $this->data);
    }
    /*
     * This will return a list of all cities available for a country.
     * This is used in ajax portion for step 1 of transfers steps.
     *
     * @return layout the list of cities for a specific country
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     */

    public function corporateAirportCitiesListingAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();

        $cityEncoded = $this->get('DealServices')->getTransferCityListingByCountry($post['country']);
        $cityDecoded = json_decode($cityEncoded, true);
        $data        = $cityDecoded['data'];

        $return['count']  = $data['count'];
        $return['output'] = $this->render('@Corporate/tours/corporate-airport-transport-step1.twig', $data)->getContent();
        $response         = new Response(json_encode($return));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /*
     * This will return a list of all airports for a specific country and city.
     * This is used in ajax portion for step 1 of transfers steps.
     *
     * @return layout the list of cities for a specific country
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     */

    public function corporateAirportListingAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();

        $airportObj     = $this->get('DealServices')->getDealTransferAirportListingCriteria($post);
        $airportEncoded = $this->get('DealServices')->getTransferAirportListing($airportObj);
        $airportDecoded = json_decode($airportEncoded, true);
        $data           = $airportDecoded['data'];

        $return['firstRecord'] = $data['firstRecord'];
        $return['count']       = $data['count'];
        if ($return['count']) {
            $return['output'] = $this->render('@Corporate/tours/corporate-airport-transport-step3.twig', $data)->getContent();
        }

        $response = new Response(json_encode($return));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /*
     * This returns the list of vehicles available for a transfer
     * Here we shall call getTransferVehicles() which rans TransferDisplay request to WorlAirport
     *
     * @return layout for list of vehicles
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     */

    public function corporateGetTransportVehiclesAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();

        $dealTransferObj        = $this->get('DealServices')->getDealTransferVehiclesListingCriteria($post);
        $vehiclesEncoded        = $this->get('DealServices')->getTransferVehicles($dealTransferObj);
        $vehiclesDecoded        = json_decode($vehiclesEncoded, true);
        $data                   = $vehiclesDecoded['data'];
        $ttTransferEncoded      = $this->get('DealServices')->getAllTtTransferType();
        $ttTransferDecoded      = json_decode($ttTransferEncoded, true);
        $data['ttTransferType'] = $ttTransferDecoded['data'];

        $return['count'] = $data['count'];
        if ($return['count']) {
            $return['output'] = $this->render('@Corporate/tours/corporate-airport-transport-step7.twig', $data)->getContent();
        }

        $response = new Response(json_encode($return));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /*
     * This handles the lading page for corporate attractions. This will display list of top destinations, top attractions and top deals.
     * It is also in that when you search a location/destination/dealName we handle the request.
     *
     * @param $seotitle
     * @param $seodescription
     * @param $seokeywords
     *
     * @return array of deals. Top Destinations, Top Attractions, Top Deals.
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function corporateAttractionsAction($seotitle, $seodescription, $seokeywords)
    {
        $request = Request::createFromGlobals();

        $this->data['page_menu_logo']       = 'attraction_corp';
        $this->data['isMobile']             = $request->request->get('from_mobile', 0);
        $this->data['dealblocksearchIndex'] = 1;
        $this->data['pageBannerImage']      = $this->get("TTRouteUtils")->generateMediaURL('/media/images/dealsjumbotrone.png');
        $this->data['needpayment']          = 1;

        $this->data['tourURL']        = $this->generateLangRoute('_corporate_dealSearch_tours');
        $this->data['activityURL']    = $this->generateLangRoute('_corporate_dealSearch_activities');
        $this->data['attractionsURL'] = $this->generateLangRoute('_corporate_dealSearch_attractions');

        $params                     = array();
        $topAttractionEncoded       = $this->get('DealServices')->getTopAttractionsPerApi();
        $topAttractionDecoded       = json_decode($topAttractionEncoded, true);
        $params['attractions']      = ($topAttractionDecoded['success']) ? $topAttractionDecoded['data'] : array();
        $params['langCode']         = $this->LanguageGet();
        $params['selectedCurrency'] = $this->data['selected_currency'];

        $dealSC                    = $this->get('DealServices')->getDealSearchCriteria($params);
        $attractions               = $this->get('DealServices')->getTopAttractions($dealSC);
        $decoded_attractions       = json_decode($attractions, true);
        $this->data['attractions'] = $decoded_attractions['data'];

        $this->data['needpayment']  = 1;
        $this->data['city']         = '';
        $this->data['startDate']    = '';
        $this->data['endDate']      = '';
        $this->data['webgeoCityId'] = 0;

        // If city was taken from autocomplete, it means it has webgeocity id
        if (isset($post['webgeo_city']) && !empty($post['webgeo_city'])) {
            $params['city']             = $post['webgeo_city'];
            $this->data['city']         = $post['city'];
            $this->data['webgeoCityId'] = $post['webgeo_city'];
        }
        // else check if there is a city that was just inputted manually
        elseif (isset($post['city']) && !empty($post['city'])) {
            $params['cityName'] = $post['city'];
            $this->data['city'] = $post['city'];
        }
        if (isset($post['startDate']) && isset($post['endDate'])) {
            $params['startDate']     = date("m/d/Y", strtotime($post['startDate']));
            $params['endDate']       = date("m/d/Y", strtotime($post['endDate']));
            $this->data['startDate'] = $post['startDate'];
            $this->data['endDate']   = $post['endDate'];
        }

        if ($request->query->has('success') && $request->query->get('success') == false && $request->query->has('message') && $request->query->get('message') != '') {
            $this->data['error'] = $request->query->get('message');
        }

        if ($request->query->has('reservationId') && $request->query->get('reservationId') != '') {
            $bookingId         = $request->query->get('reservationId');
            $bookingDetailsObj = $this->get('DealServices')->getDealBookingCriteria(array('bookingId' => $bookingId));
            $bookingDetails    = $this->get('DealServices')->getBookingDetails($bookingDetailsObj);
            $decoded_booking   = json_decode($bookingDetails, true);
            $bookingResult     = $decoded_booking['data'];

            if ($bookingResult) {
                if (isset($bookingResult['db_dealTypeId']) && $bookingResult['db_dealTypeId'] == $this->container->getParameter('DEAL_TYPE_TRANSFERS')) {
                    return $this->redirectToLangRoute('_corporate_airport_transport', array('country' => $bookingResult['dc_countryCode']));
                } else {
                    $packageEncoded = $this->get('DealServices')->getPackageById($bookingResult['db_dealDetailsId']);
                    $packageDecoded = json_decode($packageEncoded, true);
                    $dealResult     = $packageDecoded['data'];

                    if ($dealResult) {
                        return $this->corporateTourDetailsLinkRedirect($dealResult['dd_id']);
                    } else {
                        $this->data['city'] = $bookingResult['dc_cityName'];
                    }
                }
            } else {
                $this->data['error'] = $this->translator->trans('Reservation not found! Please try again.');
            }
        }

        $params['dynamicSorting'] = true;
        $params['limit']          = $this->container->getParameter('AVERAGE_QUERY_LIMIT');

        $dealSC2                    = $this->get('DealServices')->getDealSearchCriteria($params);
        $destinations               = $this->get('DealServices')->getTopDestinations($dealSC2);
        $decoded_destinations       = json_decode($destinations, true);
        $this->data['destinations'] = $decoded_destinations['data'];

        $deals               = $this->get('DealServices')->getLandingPageTopTours($dealSC2);
        $decoded_deals       = json_decode($deals, true);
        $this->data['deals'] = $decoded_deals['data'];

        return $this->render('@Corporate/tours/corporate-attractions.twig', $this->data);
    }
    /*
     * This handles the landing page of attractions-search
     *
     * @return layout for attractions-search
     */

    public function corporateAttractionsSearchAction($srch = '', $seotitle, $seodescription, $seokeywords)
    {
        $this->data['page_menu_logo'] = 'attraction_corp';

        $this->data['tourURL']        = $this->generateLangRoute('_corporate_dealSearch_tours');
        $this->data['activityURL']    = $this->generateLangRoute('_corporate_dealSearch_activities');
        $this->data['attractionsURL'] = $this->generateLangRoute('_corporate_dealSearch_attractions');

        return $this->render('@Corporate/tours/corporate-attractions-search.twig', $this->data);
    }

    /**
     *  Function handles booking - saving of deal_booking and payment data
     *
     * @param $seotitle
     * @param $seodescription
     * @param $seokeywords
     *
     */
    public function corporateBookNowAction($seotitle, $seodescription, $seokeywords)
    {
        $this->data['page_menu_logo'] = 'attraction_corp';
        $request                      = $this->get('request');
        $post                         = $request->request->all();
        $langCode                     = $this->LanguageGet();
        $post['selectedCurrency']     = $this->data['selected_currency'];
        $conversionRate               = $this->get('CurrencyService')->getConversionRate('USD', $post['selectedCurrency']);
        $newConvertedTotal            = $this->get('CurrencyService')->currencyConvert($post['totalPrice'], $conversionRate);
        $post['convertedTotalPrice']  = $newConvertedTotal;
        $post['formattedTotalPrice']  = number_format($newConvertedTotal, 2, '.', ',');

        $this->data['tourURL']        = $this->generateLangRoute('_corporate_dealSearch_tours');
        $this->data['activityURL']    = $this->generateLangRoute('_corporate_dealSearch_activities');
        $this->data['attractionsURL'] = $this->generateLangRoute('_corporate_dealSearch_attractions');

        // setting the user id for this booking by the logged in user
        $userId             = $this->userGetID();
        $userArray          = $this->get('UserServices')->getUserDetails(array('id' => $userId));
        $userCorpoAccountId = $userArray[0]['cu_corpoAccountId'];

        $mobileCountryEncoded                = $this->get('DealServices')->getMobileCountryCodeList();
        $mobileDecoded                       = json_decode($mobileCountryEncoded, true);
        $this->data['mobileCountryCodeList'] = $mobileDecoded['data'];

        if (empty($userId)) {
            $userId                     = $this->data['isUserLoggedIn'] = 0;
        } else {
            $this->data['isUserLoggedIn'] = 1;
        }

        $countryEncoded            = $this->get('DealServices')->getCountryList();
        $countryDecoded            = json_decode($countryEncoded, true);
        $this->data['countryList'] = $countryDecoded['data'];

        if (isset($post['bookNow'])) {
            $bookingDetails = array();

            //we need to assign post back to view
            foreach ($post as $key => $val) {
                $bookingDetails[$key] = $val;
            }

            $bookingDetails['userId']              = $userId;
            $bookingDetails['langCode']            = $langCode;
            $bookingDetails['accountId']           = $userCorpoAccountId;
            $bookingDetails['preferredCurrency']   = $this->get('CorpoAccountServices')->getAccountPreferredCurrency($userCorpoAccountId);
            $bookingDetails['transactionSourceId'] = $this->container->getParameter('CORPORATE_REFERRER');
            $bookingDetails['moduleId']            = $this->container->getParameter('MODULE_DEALS');

            //saving to deal_booking tables
            $bookingDetailsObj = $this->get('DealServices')->getDealBookingCriteria($bookingDetails);
            $resultEncoded     = $this->get('DealServices')->saveBookingData($bookingDetailsObj);
            $decoded_result    = json_decode($resultEncoded, true);
            $dealBookingId     = $decoded_result['data'];

            if ($dealBookingId) {
                return $this->redirectToLangRoute('otpRoute', array('module_id' => $bookingDetails['moduleId'], 'reservation_id' => $dealBookingId, 'user_id' => $userId));
            } else {
                $typeEncoded = $this->get('DealServices')->getDealTypeByParam($post['dealTypeId']);
                $typeDecoded = json_decode($typeEncoded, true);

                switch ($typeDecoded['data']['category']) {
                    case 'transfers':
                        //Redirect to corporate-airport-transport
                        return $this->redirectToRoute('_corporate_airport_transport', array('success' => false, 'errorMessage' => $this->container->get('translator')->trans('Booking has encountered an error!')));
                    default:
                        return $this->render('@Corporate/tours/corporate-book-now.twig', $this->data);
                }
            }
        } else {
            //if page gone through payment
            $transactionId = $request->query->get('transaction_id', '');
            if ($transactionId) {
                $bookingUuidEncoded = $this->get('DealServices')->findBookingByUuid($transactionId);
                $bookingUuidDecoded = json_decode($bookingUuidEncoded, true);
                $dealBookingObj     = $bookingUuidDecoded['data'];
                $packageId          = $dealBookingObj['dealDetailsId'];

                return $this->redirectToLangRoute('_corporate_tourDetails', array('packageId' => $packageId));
            } else {
                if (!isset($post['packageId'])) {
                    return $this->redirectToLangRoute('_corporate_attractions', array('success' => false, 'message' => $this->container->get('translator')->trans('Error! Invalid Package Id.')));
                }
                $packageId = $post['packageId'];
            }

            $mandatoryObj     = $this->get('DealServices')->getDealMandatoryFieldsCriteria($post);
            $dealBookingQuote = $this->get('DealServices')->saveMandatoryFieldAnswers($mandatoryObj);
            $packageEncoded   = $this->get('DealServices')->getPackageById($packageId);
            $packageDecoded   = json_decode($packageEncoded, true);
            $result           = $packageDecoded['data'];

            $post['dealType']      = $result['dt_category'];
            $post['dealTypeId']    = $result['dt_id'];
            $this->data['details'] = $post;

            return $this->render('@Corporate/tours/corporate-book-now.twig', $this->data);
        }
    }

    /**
     *  Function handles Price Quotation
     *
     * @return price or error message
     */
    public function corporateQuoteBookingAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();

        $dealQuoteObj = $this->get('DealServices')->getDealQuotationCriteria($post);
        $quoteEncoded = $this->get('DealServices')->getQuotation($dealQuoteObj);
        $quoteDecoded = json_decode($quoteEncoded, true);

        if ($quoteDecoded['success']) {
            $quote = $quoteDecoded['data'];
        } else {
            $quote['errorMessage'] = $quoteDecoded['message'];
        }

        $response = new Response(json_encode($quote));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /*
     * This is the method called to load ActivityReviewList through AJAX.
     * Also handles sorting of review lists
     *
     * @return array of customer reviews
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function corporateGetMandatoryFieldsAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();

        if (!isset($post['packageId'])) {
            return $this->redirectToLangRoute('_corporate_attractions', array('success' => false, 'message' => $this->container->get('translator')->trans('Error! Invalid Package Id.')));
        }

        $packageId = $post['packageId'];
        if (isset($post['bookingQuoteId'])) {
            $quoteEncoded = $this->get('DealServices')->getBookingQuotesById($post['bookingQuoteId']);
            $quoteDecoded = json_decode($quoteEncoded, true);
            $quote        = $quoteDecoded['data'];
            if (!$quoteDecoded['success']) {
                return $this->corporateTourDetailsLinkRedirect($packageId);
            }

            //Take only the first Id cause Mandatory Field is just the same to all quote
            $this->data['mandatoryFields']    = $this->get('DealServices')->renderMandatoryFields(json_decode($quote[0]['dynamicFields']));
            //$this->data['transportation']  = $this->get('DealServices')->buildTransportationArray($bookingQuote);
            $this->data['bookingQuote']       = $quote;
            $this->data['packageId']          = $packageId;
            $this->data['numOfPassenger']     = $post['numOfPassenger'];
            $this->data['tourCode']           = $post['tourCode'];
            $this->data['bookingDate']        = $post['bookingDate'];
            $this->data['totalPrice']         = $post['totalPrice'];
            $this->data['currency']           = $post['currency'];
            $this->data['startingPlace']      = $post['startingPlace'];
            $this->data['dealHighlights']     = $post['dealHighlights'];
            $this->data['cancellationPolicy'] = $post['cancellationData'];

            $packageEncoded = $this->get('DealServices')->getPackageById($packageId, $this->LanguageGet());
            $packageDecoded = json_decode($packageEncoded, true);
            $dealResult     = $packageDecoded['data'];

            $dealName = (isset($dealResult['dealNameTrans']) && $dealResult['dealNameTrans']) ? $dealResult['dealNameTrans'] : $dealResult['dd_dealName'];
            $dealName = $this->get('app.utils')->cleanTitleData($dealName);
            $dealName = str_replace('+', '-', $dealName);

            $cityName = $this->get('app.utils')->cleanTitleData($dealResult['dc_cityName']);
            $cityName = str_replace('+', '-', $cityName);

            $this->data['dealName'] = $dealName;
            $this->data['cityName'] = $cityName;
            $this->data['category'] = $dealResult['dt_category'];

            return $this->render('@Corporate/tours/corporate-mandatory-fields.twig', $this->data);
        } else {
            return $this->corporateTourDetailsLinkRedirect($packageId);
        }
    }
    /*
     * This is will redirect the user to details page
     *
     * @return redirect
     * @author Anna Lou Parejo <anna.parejo@touristtube.com>
     */

    public function corporateTourDetailsLinkRedirect($packageId)
    {
        $packageEncoded = $this->get('DealServices')->getPackageById($packageId, $this->LanguageGet());
        $packageDecoded = json_decode($packageEncoded, true);

        if (!$packageDecoded['success']) {
            return $this->redirectToLangRoute('_corporate_attractions');
        }

        $dealResult = $packageDecoded['data'];
        $dealName   = (isset($dealResult['dealNameTrans']) && $dealResult['dealNameTrans']) ? $dealResult['dealNameTrans'] : $dealResult['dd_dealName'];
        $dealName   = $this->get('app.utils')->cleanTitleData($dealName);
        $dealName   = str_replace('+', '-', $dealName);

        $cityName = $this->get('app.utils')->cleanTitleData($dealResult['dc_cityName']);
        $cityName = str_replace('+', '-', $cityName);

        return $this->redirectToLangRoute('_corporate_tourDetails_'.$dealResult['dt_category'].'_', array('city' => $cityName, 'dealName' => $dealName, 'packageId' => $packageId));
    }

    /**
     *  Proceed Payment after OTP Pin verification
     *
     * @param $module_id
     * @param $reservation_id
     * @param $user_id
     *
     * @return redirect
     */
    public function corporateProceedPaymentAction($module_id, $reservation_id, $user_id)
    {
        $bookingEncoded = $this->get('DealServices')->findBookingById($reservation_id);
        $bookingDecoded = json_decode($bookingEncoded, true);

        if (!$bookingDecoded['success']) {
            return $this->redirectToLangRoute('_corporate_attractions');
        }

        $dealBookingObj = $bookingDecoded['data'];
        $userArray      = $this->get('UserServices')->getUserDetails(array('id' => $user_id));

        if (!$userArray) {
            return $this->redirectToLangRoute('_corporate_attractions');
        }

        $userCorpoAccountId = $userArray[0]['cu_corpoAccountId'];
        $onAccountOrCC      = $this->get('CorpoAccountServices')->getCorpoAccountPaymentType($userCorpoAccountId);
        $onAccountCCType    = $onAccountOrCC['code'];

        $request = $this->get('request');

        //init payment
        $params                    = array();
        $params['onAccountCCType'] = $onAccountCCType;
        $params['bookingId']       = $reservation_id;
        $params['currency']        = $dealBookingObj['currency'];
        $params['totalPrice']      = $dealBookingObj['totalPrice'];
        $params['email']           = $dealBookingObj['email'];
        $params['firstName']       = $dealBookingObj['firstName'];
        $params['lastName']        = $dealBookingObj['lastName'];
        $params['userAgent']       = $request->headers->get('User-Agent');
        $params['customerIP']      = $this->get('app.utils')->getUserIP();

        $bookingDetailsObj = $this->get('DealServices')->getDealBookingCriteria($params);
        $paymentObj        = $this->get('DealServices')->savePaymentData($bookingDetailsObj); // getting payment object
        $result            = $this->paymentApproval($paymentObj, $module_id);

        if (!isset($result["transaction_id"])) $result["transaction_id"] = null;

        // save payment id in deal booking table for this booking
        if (isset($result["transaction_id"]) && !empty($result["transaction_id"])) {
            $updateBookingWithPayment = $this->get('DealServices')->updateBookingWithPayment($reservation_id, $result["transaction_id"]);
        }

        return $this->redirectDynamicRoute($result["callback_url"], array('transaction_id' => $result["transaction_id"]));
    }
    /*
     * This handles the landing page for All types
     * Added for SEO purposes
     *
     * @param $seotitle
     * @param $seodescription
     * @param $seokeywords
     *
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function corporateDealSearchAllAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->corporateDealSearchAction('', 'all', 0, '', $seotitle, $seodescription, $seokeywords);
    }
    /*
     *  This handles the landing page for Activities
     *  Added for SEO purposes
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function corporateSearchActivitiesAction()
    {
        return $this->corporateDealSearchAction('', 'activities');
    }
    /*
     *  This handles the landing page for Attractions
     *  Added for SEO purposes
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function corporateSearchToursAction()
    {
        return $this->corporateDealSearchAction('', 'tours');
    }
    /*
     *  This handles the landing page for tourSearch
     *  Added for SEO purposes
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function corporateSearchAttractionsAction()
    {
        return $this->corporateDealSearchAction('', 'attractions');
    }
    /*
     *  This handles the landing page for Attractions searching for DealName
     *  Added for SEO purposes
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function corporateDealNameSearchAttractionsAction($dealName = '')
    {
        return $this->corporateDealSearchAction('', 'attractions', 0, $dealName);
    }
    /*
     *  This handles the lading page for tourSearch. This will display list of avaiable packages.
     *  It is also in that when you search a location/destination we handle the request.
     *
     * @param $city - destination to search
     * @param $type - by defaults its 'all'
     * @param $priority - if 1 means we list data per priority
     *
     * @return array of deals
     * @author Anna Lou Parejo <anna.parejo@touristtube.com>
     */

    public function corporateDealSearchAction($city = '', $type = 'all', $priority = 0, $dealName = '')
    {
        $this->data['page_menu_logo']       = ($type == 'all') ? 'attraction_corp' : 'flight_corp';
        $this->data['page_menu_name']       = ($type == 'all') ? 'attractions' : $type;
        $this->data['dealSearchFormAction'] = $this->generateUrl('_corporate_dealSearch');

        $langCode = $this->LanguageGet();

        $this->data['tourURL']        = $this->generateLangRoute('_corporate_dealSearch_tours');
        $this->data['activityURL']    = $this->generateLangRoute('_corporate_dealSearch_activities');
        $this->data['attractionsURL'] = $this->generateLangRoute('_corporate_dealSearch_attractions');

        $this->data['langCode']  = $langCode;
        $this->data['type']      = $type;
        $this->data['priority']  = $priority;
        $this->data['hasSearch'] = 0;

        $post = Request::createFromGlobals();

        $citySearch = false;
        if (strlen(trim($city)) > 0) {
            $citySearch = true;
            $post->request->set('location', $city);
        }

        //dealName search
        if (strlen(trim($dealName)) > 0) {
            $post->request->set('dealName', str_replace('+', ' ', $dealName));
        }

        if ($post->request->has('dealId')) {
            if ($post->request->get('dealId') != '' && $post->request->get('dealId') != 0) {
                $packageId = $post->request->get('dealId');
                return $this->corporateTourDetailsLinkRedirect($packageId);
            }
        }

        $result                     = $params                     = array();
        $params['langCode']         = $langCode;
        $params['priority']         = $priority;
        $params['category']         = $type;
        $params['searchAll']        = false;
        $params['selectedCurrency'] = $this->data['selected_currency'];

        //setting initial value of min and max price
        $lowestPrice  = $this->container->getParameter('LOWEST_PRICE_SEARCH');
        $highestPrice = $this->container->getParameter('HIGHEST_PRICE_SEARCH');

        $autoSuggest = false;

        //refine search
        if ($post->request->has('location')) {
            $this->data['hasSearch'] = 1;
            if ($post->request->get('location') != '') {
                $this->data['city']   = $params['cityName']   = $post->request->get('location');
                $params['citySearch'] = $citySearch;

                if ($post->request->has('dealNameSearch')) {
                    $params['dealNameSearch'] = $params['dealName']       = $post->request->get('dealNameSearch');
                    $this->data['dealName']   = $params['dealName'];
                }
            } elseif ($post->request->get('dealName')) {
                $params['dealName']     = $this->data['dealName'] = $post->request->get('dealName');
            }

            if ($post->request->get('priceRange') != '') {
                $this->data['priceRange']   = $post->request->get('priceRange');
                $priceRange                 = explode(",", $this->data['priceRange']);
                $this->data['userMinPrice'] = $lowestPrice                = $params['minPrice']         = (int) $priceRange[0];
                $this->data['userMaxPrice'] = $highestPrice               = $params['maxPrice']         = (int) $priceRange[1];
            }
            //autosuggest search
        } elseif ($post->request->has('search')) {
            $autoSuggest = true;
            if ($post->request->get('cityId')) {
                $params['city']    = $post->request->get('cityId');
                $cityResultDecoded = $this->get('DealServices')->getDealCityInfoByCityId($params['city']);
                $decoded_city      = json_decode($cityResultDecoded, true);
                $cityInfo          = $decoded_city['data'];

                $this->data['city']   = $cityInfo['cityName'];
                $this->data['cityId'] = $post->request->get('cityId');
            } elseif ($post->request->has('attractionName')) {
                if ($post->request->get('attractionName') != '') {
                    $attractionName         = $post->request->get('attractionName');
                    $this->data['city']     = $params['dealName']     = $this->data['dealName'] = $attractionName;
                }
            }

            if ($post->request->get('startDate') && $post->request->get('endDate')) {
                $params['startDate']     = date("m/d/Y", strtotime($post->request->get('startDate')));
                $params['endDate']       = date("m/d/Y", strtotime($post->request->get('endDate')));
                $this->data['startDate'] = $params['startDate'];
                $this->data['endDate']   = $params['endDate'];
            }

            if ($post->request->has('types') && $post->request->get('types')) {
                $params['allTypes'] = true;
            }
        }// if were calling from deals page top attractions we pass dealName
        elseif ($post->request->has('dealName') && $post->request->get('dealName') != '') {
            $params['dealName']     = $this->data['dealName'] = $post->request->get('dealName');
        } else {
            //all deals
            $params['searchAll'] = true;
        }

        if ($post->query->has('success') && $post->query->get('success') == false && $post->query->has('message') && $post->query->get('message') != '') {
            $this->data['error'] = $post->query->get('message');
        }

        $dealSC               = $this->get('DealServices')->getDealSearchCriteria($params);
        $resultEncoded        = $this->get('DealServices')->dealSearch($dealSC);
        $decoded_result       = json_decode($resultEncoded, true);
        $result               = $decoded_result['data'];
        $this->data['result'] = $result;

        //if more than one results
        if ($decoded_result['data']['numRowsCnt'] > 1) {
            $lowestPrice  = isset($decoded_result['data']['minPrice']) ? $decoded_result['data']['minPrice'] : $lowestPrice;
            $highestPrice = isset($decoded_result['data']['maxPrice']) ? $decoded_result['data']['maxPrice'] : $highestPrice;
        } else {
            $this->data['result']['priceRange'] = $lowestPrice.','.$highestPrice;
        }

        if ($post->request->has('minPrice') && $post->request->get('minPrice') != 0) {
            $lowestPrice = $post->request->get('minPrice');
        }
        if ($post->request->has('maxPrice') && $post->request->get('maxPrice') != 0) {
            $highestPrice = $post->request->get('maxPrice');
        }

        $this->data['minPrice'] = $lowestPrice;
        $this->data['maxPrice'] = $highestPrice;

        $this->data['citySearch']  = $citySearch;
        $this->data['autoSuggest'] = $autoSuggest;
        return $this->render('@Corporate/tours/corporate-tour_search_index.twig', $this->data);
    }
    /*
     * This handles the LOAD MORE of deal search results
     * Works like pagination
     *
     * @return search results corporate_tour_search_load_more.twig
     * @author Anna Lou Parejo <anna.parejo@touristtube.com>
     */

    public function corporateGetLoadMoreAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();

        $postVal = array('cityName' => 'location', 'minPrice' => 'minPrice', 'maxPrice' => 'maxPrice',
            'category' => 'dealType', 'offSet' => 'offSet', 'langCode' => 'langCode', 'startDate' => 'startDate',
            'endDate' => 'endDate', 'hasSearch' => 'hasSearch', 'dealName' => 'dealName',
            'dealNameSearch' => 'dealNameSearch', 'selectedCurrency' => 'selectedCurrency', 'city' => 'cityId'
        );

        $result = $params = array();
        foreach ($postVal as $key => $val) {
            if (isset($post[$val]) && $post[$val]) {
                $params[$key] = $post[$val];
            }
        }

        $dealSC               = $this->get('DealServices')->getDealSearchCriteria($params);
        $resultEncoded        = $this->get('DealServices')->dealSearch($dealSC);
        $decoded_result       = json_decode($resultEncoded, true);
        $result               = $decoded_result['data'];
        $this->data['result'] = $result;
        $return               = $this->render('@Corporate/tours/corporate_tour_search_load_more.twig', $this->data)->getContent();

        $jsonArr                = array();
        $jsonArr['twigResults'] = $return;
        $jsonArr['numRowCnt']   = $result['numRowsCnt'];
        $response               = new Response(json_encode($jsonArr));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /*
     * This handles the landing page for the corporate details of each package.
     *
     * @param $city
     * @param $dealName
     * @param $packageId
     *
     * @return details of a particular deal from deal_details table
     * @author Anna Lou Parejo <anna.parejo@touristtube.com>
     */

    public function corporateTourDetailsAction($city, $dealName, $packageId)
    {
        $langCode         = $this->LanguageGet();
        $selectedCurrency = $this->data['selected_currency'];

        $this->data['tourURL']        = $this->generateLangRoute('_corporate_dealSearch_tours');
        $this->data['activityURL']    = $this->generateLangRoute('_corporate_dealSearch_activities');
        $this->data['attractionsURL'] = $this->generateLangRoute('_corporate_dealSearch_attractions');

        $packageEncoded = $this->get('DealServices')->getPackageById($packageId, $langCode);
        $packageDecoded = json_decode($packageEncoded, true);
        $result         = $packageDecoded['data'];

        if (!$packageDecoded['success']) {
            return $this->redirectToLangRoute('_corporate_dealSearch');
        }

        $getDetails         = array();
        $getDetailsResponse = $this->get('DealServices')->getDetails($result['dd_dealCode'], $langCode, $selectedCurrency);
        $getDetailsJson     = json_decode($getDetailsResponse, true);

        if (!$getDetailsJson['success']) {
            $getDetails['errorMessage'] = $getDetailsJson['message'];
        } else {
            $getDetails = $getDetailsJson['data'];
        }

        $getDetails['dealCategory'] = $result['dt_category'];
        $this->data['details']      = $getDetails;
        $this->data['returnUrl']    = $this->get('app.utils')->generateLangURL($langCode, '/corporate/'.$result['dt_category'], 'corporate');
        $this->data['mapKey']       = $this->container->getParameter('MAP_KEY')[0];
        $this->data['apiId']        = $result['dd_dealApiId'];
        $this->data['packageId']    = $packageId;

        return $this->render('@Corporate/tours/corporate-tour-details.twig', $this->data);
    }

    public function corporateAttractionsFormAction($packageId, $seotitle, $seodescription, $seokeywords)
    {
        return $this->render('@Corporate/tours/corporate-attractions-form.twig', $this->data);
    }
    /*
     * This method gets the booking box per date you select in the deal details page.
     * If deal is not available and error message will be returned.
     *
     * @return booking box tour_booking_box.twig template
     * @author Anna Lou H. Parejo <firas.boukarroum@touristtube.com>
     */

    public function corporatePriceDetailsAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();

        $dealPriceObj  = $this->get('DealServices')->getDealPriceOptionCriteria($post);
        $resultEncoded = $this->get('DealServices')->getPriceDetails($dealPriceObj);
        $resultDecoded = json_decode($resultEncoded, true);

        if ($resultDecoded['success']) {
            $priceDetails              = $resultDecoded['data'];
            $priceDetails['packageId'] = $post['packageId'];
        } else {
            $priceDetails['errorMessage'] = $resultDecoded['message'];
        }

        $return   = $this->render('@Corporate/tours/corporate-tour-booking-box.twig', $priceDetails)->getContent();
        $response = new Response(json_encode($return));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /*
     * This is the method called to load ActivityReviewList through AJAX.
     * Also handles sorting of review lists
     *
     * @return array of customer reviews
     * @author Anna Lou Parejo <anna.parejo@touristtube.com>
     */

    public function corporateActivityReviewsAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();

        $resultEncoded  = $this->get('DealServices')->getActivityReviews($post['activityId'], $post['sorting']);
        $decoded_result = json_decode($resultEncoded, true);
        $reviewResults  = $decoded_result['data'];

        $response = new Response(json_encode($reviewResults));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * This method will retrieve booking details and handles cancellation
     *
     * @param $bookingId
     * @author Anna Lou Parejo <anna.parejo@touristtube.com>
     */
    public function corporateDealsBookingDetailsAction($bookingId = 0)
    {
        $this->data['page_menu_logo'] = 'attraction_corp';

        $this->data['tourURL']        = $this->generateLangRoute('_corporate_dealSearch_tours');
        $this->data['activityURL']    = $this->generateLangRoute('_corporate_dealSearch_activities');
        $this->data['attractionsURL'] = $this->generateLangRoute('_corporate_dealSearch_attractions');

        $request = $this->get('request');
        $post    = $request->request->all();

        $criteria                     = array();
        $criteria['bookingId']        = $bookingId;
        $criteria['selectedCurrency'] = $this->data['selected_currency'];

        $bookingDetailsObj     = $this->get('DealServices')->getDealBookingCriteria($criteria);
        $bookingDetailsEncoded = $this->get('DealServices')->getBookingDetails($bookingDetailsObj);
        $bookingDetailsDecoded = json_decode($bookingDetailsEncoded, true);
        $bookingDetails        = $bookingDetailsDecoded['data'];

        if ($bookingId == 0 || !$bookingDetailsDecoded['success']) {
            return $this->redirectToLangRoute('_corporate');
        }

        if ($request->query->has('success') && $request->query->get('success') == false && $request->query->has('message') && $request->query->get('message') != '') {
            $bookingDetails['error'] = $request->query->get('message');
        }

        $policyDetails = json_decode($bookingDetails['db_cancellationPolicy'], true);
        if ($policyDetails) {
            $cancellationPolicyObj             = $this->get('DealServices')->getDealCancellationPolicy($policyDetails);
            $resultEncoded                     = $this->get('DealServices')->parseCancellationPolicy($cancellationPolicyObj);
            $decoded_result                    = json_decode($resultEncoded, true);
            $this->data['cancellationDetails'] = $decoded_result['data'];
        }

        //booking cancellation
        if (isset($post['cancel'])) {
            $resultEncoded  = $this->get('DealServices')->cancelBooking($bookingDetails['db_bookingReference'], $bookingDetails['db_email'], $bookingDetails['dt_category']);
            $decoded_result = json_decode($resultEncoded, true);

            if (!$decoded_result['success']) {
                $bookingDetails['error'] = $this->translator->trans($decoded_result['message']);
            } else {
                $params                  = array();
                $params['requestStatus'] = $this->container->getParameter('CORPO_APPROVAL_CANCELED');
                $params['reservationId'] = $bookingId;
                $params['moduleId']      = $this->container->getParameter('MODULE_DEALS');
                $crsResult               = $this->get('CorpoApprovalFlowServices')->updatePendingRequestServices($params);

                $cancelResults                      = $decoded_result['data'];
                $bookingDetails['db_bookingStatus'] = $cancelResults['bookingStatus'];
                $bookingDetails['success']          = $this->translator->trans('Your booking has been successfully cancelled!');
            }
        }

        $this->data['bookingDetails'] = $bookingDetails;
        return $this->render('@Corporate/tours/corporate-deals-booking-details.twig', $this->data);
    }

    /**
     * This method is the actual process booking from CityDiscovery API
     *
     * @author Anna Lou Parejo <anna.parejo@touristtube.com>
     */
    public function corporateProcessBookingAction()
    {
        $request          = $this->get('request');
        $transactionId    = $request->query->get('transaction_id', '');
        $requestServiceId = $request->query->get('requestServiceId', '1');

        if (empty($transactionId)) {
            return;
        }

        $paymentRequired = true;
        $error           = '';
        $payment         = $this->get('DealServices')->findPaymentByUuid($transactionId);
        $bookingEncoded  = $this->get('DealServices')->findBookingByUuid($transactionId);
        $bookingDecoded  = json_decode($bookingEncoded, true);
        $dealBookingObj  = $bookingDecoded['data'];

        $userId = $this->userGetID();
        if (empty($userId)) {
            $userId = 0;
        }
        $userArray          = $this->get('UserServices')->getUserDetails(array('id' => $userId));
        $userCorpoAccountId = $userArray[0]['cu_corpoAccountId'];

        $onAccountOrCCObj = $this->get('CorpoAccountServices')->getCorpoAccountPaymentType($userCorpoAccountId);
        $onAccountOrCC    = $onAccountOrCCObj['code'];

        $params                       = array();
        $params['userId']             = $userId;
        $params['moduleId']           = $payment->getModuleId();
        $params['reservationId']      = $payment->getModuleTransactionId();
        $params['userCorpoAccountId'] = $userCorpoAccountId;
        $params['paymentType']        = $onAccountOrCC;
        $params['bookingId']          = $dealBookingObj['id'];

        if (!$payment || !$bookingDecoded['success']) {
            $parameters['requestStatus'] = $this->container->getParameter('CORPO_APPROVAL_CANCELED');
            $crsResult                   = $this->get('CorpoApprovalFlowServices')->updatePendingRequestServices($parameters);
            if ($onAccountOrCC == 'cc') {
                //refund the amount of money
                $this->get('PaymentServiceImpl')->voidOnHoldPayment($transactionId);
            }

            $errorMsg = $this->translator->trans('Error! No transaction found');
            return $this->redirectToRoute('_corporate_attractions', array('success' => false, 'message' => $errorMsg));
        }

        $params['paymentRequired'] = true;
        $params['bookingObj']      = $dealBookingObj['id'];
        $bookingDetailsObj         = $this->get('DealServices')->getDealBookingCriteria($params);
        $resultsEncoded            = $this->get('DealServices')->processBooking($bookingDetailsObj);
        $resultsDecoded            = json_decode($resultsEncoded, true);
        $results                   = $resultsDecoded['data'];

        if (isset($results['redirectUrl']) && isset($results['redirectParams'])) {
            return $this->redirectToLangRoute($results['redirectUrl'], $results['redirectParams']);
        }

        //if error, just redirect to deals
        return $this->redirectToLangRoute('_corporate_dealSearch');
    }
    /*
     * This is just a method as landing page.
     *
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     */

    public function corporateAccountWaitingApprovalAction()
    {
        $this->data['page_menu_logo'] = 'attraction_corp';
        return $this->render('@Corporate/corporate/pending-approval.twig', $this->data);
    }
    /*
     * This is the method called when approval of a booking
     *
     * @param $reservationId
     * @param $accountId
     * @param $userId
     * @param $transactionUserId
     * @param $requestServicesDetailsId
     *
     * @return redirect to appropriate url
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     */

    public function proceedBookingWithApprovalAction($reservationId, $accountId, $userId, $transactionUserId, $requestServicesDetailsId)
    {
        $request = $this->get('request');

        if (isset($reservationId) && !empty($reservationId)) {
            $resultEncoded  = $this->get('DealServices')->checkDealAvailability($reservationId);
            $decoded_result = json_decode($resultEncoded, true);

            if ($decoded_result['success']) {
                $isAvailable = $decoded_result['data'];

                $params                             = array();
                $params['bookingId']                = $reservationId;
                $params['accountId']                = $accountId;
                $params['userId']                   = $userId;
                $params['transactionUserId']        = $transactionUserId;
                $params['requestServicesDetailsId'] = $requestServicesDetailsId;
                $params['isAvailable']              = $isAvailable;
                $params['userAgent']                = $request->headers->get('User-Agent');

                $bookingObj = $this->get('DealServices')->getDealBookingCriteria($params);
                $results    = $this->get('DealServices')->processPendingBookingApproval($bookingObj);

                if (isset($results["callback_url"]) && $results["callback_url"]) {
                    return $this->redirectDynamicRoute($results["callback_url"], array('transaction_id' => $results["transaction_id"]));
                } else {
                    return $this->redirectToLangRoute($results["redirectUrl"], $results["redirectParams"]);
                }
            } else {
                $bookingEncoded                     = $this->get('DealServices')->findBookingById($reservationId);
                $bookingDecoded                     = json_decode($bookingEncoded, true);
                $bookingDetailsObj                  = $bookingDecoded['data'];
                $params                             = array();
                $params['reservationId']            = $reservationId;
                $params['requestServicesDetailsId'] = $requestServicesDetailsId;
                $params['dealCityId']               = $bookingDetailsObj['dealCityId'];

                $bookingApprovalObj = $this->get('DealServices')->getDealBookingCriteria($params);
                $results            = $this->get('DealServices')->processExpiredBookingApproval($bookingApprovalObj);
                return $this->redirectToRoute($results['redirectUrl'], $results['redirectParams']);
            }
        } else {
            $errorMsg = $this->translator->trans('You are not allowed to approve this request');
            return $this->redirectToLangRoute('_corporate_deals_booking_details', array('bookingId' => $reservationId, 'success' => false, 'message' => $errorMsg));
        }
    }

    /**
     * This method will retrieve booking details for the approver of the booking
     *
     * @param $reservationId
     * @author Firas Boukarroum <firas.boukarroum@touristtube.com>
     */
    public function corporateDealsBookingApprovalDetailsAction($reservationId = 0)
    {
        $this->data['page_menu_logo'] = 'attraction_corp';
        $request                      = $this->get('request');
        $post                         = $request->request->all();

        $criteria              = array();
        $criteria['bookingId'] = $reservationId;

        $bookingDetailsObj     = $this->get('DealServices')->getDealBookingCriteria($criteria);
        $bookingDetailsEncoded = $this->get('DealServices')->getBookingDetails($bookingDetailsObj);
        $bookingDetailsDecoded = json_decode($bookingDetailsEncoded, true);
        $bookingDetails        = $bookingDetailsDecoded['data'];

        if ($reservationId == 0 || !$bookingDetailsDecoded['success']) {
            $errorMsg = $this->translator->trans('Booking does not not exist in our records');
            return $this->redirectToRoute('_corporate_attractions', array('success' => false, 'message' => $errorMsg));
        }

        $this->data['bookingDetails'] = $bookingDetails;
        return $this->render('@Corporate/tours/corporate-deals-booking-approval-details.twig', $this->data);
    }

    /**
     * This method will retrieve booking details and handles cancellation
     *
     * @param $bookingId
     * @author Anna Lou H. Parejo <anna.parejo@touristtube.com>
     * */
    public function corporateViewBookingDetailsAction($bookingId = 0)
    {
        $request               = $this->get('request');
        $post                  = $request->request->all();
        $criteria              = array();
        $criteria['bookingId'] = $bookingId;

        if ($bookingId == 0) {
            //return $this->redirectToLangRoute('_my_bookings');
        }

        $bookingDetailsObj     = $this->get('DealServices')->getDealBookingCriteria($criteria);
        $bookingDetailsEncoded = $this->get('DealServices')->getBookingDetails($bookingDetailsObj);
        $bookingDetailsDecoded = json_decode($bookingDetailsEncoded, true);
        $bookingDetails        = $bookingDetailsDecoded['data'];

        if (($this->userGetID() != $bookingDetails['db_userId']) ||
            !$this->data['isUserLoggedIn'] || !$this->get('ApiUserServices')->tt_global_isset('userInfo')) {
            //return $this->redirectToLangRoute('_log_in');
        }

        $policyDetails = json_decode($bookingDetails['db_cancellationPolicy'], true);
        if ($policyDetails) {
            $cancellationPolicyObj             = $this->get('DealServices')->getDealCancellationPolicy($policyDetails);
            $resultEncoded                     = $this->get('DealServices')->parseCancellationPolicy($cancellationPolicyObj);
            $decoded_result                    = json_decode($resultEncoded, true);
            $this->data['cancellationDetails'] = $decoded_result['data'];
        }

        //booking cancellation
        if (isset($post['cancel'])) {
            $bookingEncoded    = $this->get('DealServices')->getBookingDataForCancellation($bookingId);
            $bookingDecoded    = json_decode($bookingEncoded, true);
            $bookingDetailsObj = $bookingDecoded['data'];
            $cancelEncoded     = $this->get('DealServices')->cancelBooking($bookingDetailsObj['bookingReference'], $bookingDetailsObj['email'], $bookingDetailsObj['dealType']);
            $cancelDecoded     = json_decode($cancelEncoded, true);
            $cancelResults     = $cancelDecoded['data'];
            $bookingDetails    = array_merge($bookingDetails, $cancelResults);
        }

        $bookingDetails['db_dealDescription'] = nl2br($bookingDetails['db_dealDescription']);
        $this->data['bookingDetails']         = $bookingDetails;

        return $this->render('@Corporate/tours/corporate-tour-booking-details.twig', $this->data);
    }
}
