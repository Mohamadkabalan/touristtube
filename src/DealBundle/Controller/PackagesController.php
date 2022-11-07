<?php

namespace DealBundle\Controller;

use DealBundle\Entity\DealBooking;
use DealBundle\Entity\DealBookingPassengers;
use DealBundle\Entity\DealCity;
use DealBundle\Entity\DealDetails;
use DealBundle\Entity\DealImage;
use PaymentBundle\Entity\Payment;
use TTBundle\Entity\Webgeocities;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session;
use PaymentBundle\Model\Payment as PaymentObj;

class PackagesController extends \TTBundle\Controller\DefaultController
{
    /*
     * This handles the landing page of transfers(/airport-transport).
     *
     * @return layout for airport-trasnport
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     */

    public function airportTransportRedirectAction()
    {
        return $this->redirectToLangRoute('_airport_transport', array(), 301);
    }

    public function airportTransportAction()
    {
        $this->setHreflangLinks($this->generateLangRoute('_airport_transport'), true, true);
        $this->data['needpayment']    = $this->container->getParameter('PAYMENT_NEEDED');
        $dealServices                 = $this->get('DealServices');
        $apiEncoded                   = $dealServices->getDealApiSupplierByParam('City Discovery', 'name');
        $apiDecoded                   = json_decode($apiEncoded, true);
        $this->data['apiId']          = $apiDecoded['data']['id'];
        $typeEncoded                  = $dealServices->getDealTypeByParam('TRANSFERS', 'name');
        $typeDecoded                  = json_decode($typeEncoded, true);
        $this->data['dealTypeId']     = $typeDecoded['data']['id'];
        $this->data['isUserLoggedIn'] = ($this->data['isUserLoggedIn'] ? 1 : 0);

        //This is for step1 in transport page
        $countryEncoded                      = $dealServices->getTransferCountryListing();
        $countryDecoded                      = json_decode($countryEncoded, true);
        $this->data['countries']             = $countryDecoded['data'];
        //This is for secure booking page country listing
        $countryEncoded                      = $dealServices->getCountryList();
        $countryDecoded                      = json_decode($countryEncoded, true);
        $this->data['countryList']           = $countryDecoded['data'];
        //This is for secure booking page mobile country codes
        $mobileCountryEncoded                = $dealServices->getMobileCountryCodeList();
        $mobileDecoded                       = json_decode($mobileCountryEncoded, true);
        $this->data['mobileCountryCodeList'] = $mobileDecoded['data'];

        return $this->render('@Deal/deal/airport-transport.twig', $this->data);
    }
    /*
     * This returns the list of vehicles available for a transfer
     * Here we shall call getTransferVehicles() which rans TransferDisplay request to WorlAirport
     *
     * @return layout for list of vehicles
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     */

    public function getTransportVehiclesAction()
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
            $return['output'] = $this->render('@Deal/deal/airport-transport-step7.twig', $data)->getContent();
        }

        $response = new Response(json_encode($return));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /*
     * This will return a list of all cities available for a country.
     * This is used in ajax portion for step 1 of transfers steps.
     *
     * @return layout the list of cities for a specific country
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     */

    public function airportCitiesListingAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();

        $cityEncoded = $this->get('DealServices')->getTransferCityListingByCountry($post['country']);
        $cityDecoded = json_decode($cityEncoded, true);
        $data        = $cityDecoded['data'];

        $return['count']  = $data['count'];
        $return['output'] = $this->render('@Deal/deal/airport-transport-step1.twig', $data)->getContent();
        $response         = new Response(json_encode($return));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /*
     * This is a temporary page for the layout of MadatoryFields.
     *
     * @TODO - We can delete this function later on.
     * @return layout of MadatoryFields
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function attractionFormRedirectAction()
    {
        return $this->redirectToLangRoute('_attraction_form', array(), 301);
    }

    public function attractionFormAction()
    {
        return $this->render('@Deal/deal/attraction-form.twig', $this->data);
    }
    /*
     * This will return a list of all airports for a specific country and city.
     * This is used in ajax portion for step 1 of transfers steps.
     *
     * @return layout the list of cities for a specific country
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     */

    public function airportListingAction()
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
            $return['output'] = $this->render('@Deal/deal/airport-transport-step3.twig', $data)->getContent();
        }

        $response = new Response(json_encode($return));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /*
     * This handles the lading page for deals. This will display list of top destinations, top attractions and top deals.
     * It is also in that when you search a location/destination we handle the request.
     *
     * @param $page
     * @param $seotitle
     * @param $seodescription
     * @param $seokeywords
     *
     * @return array of deals. Top Destinations, Top Attractions, Top Deals.
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function dealsRedirectAction($page, $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_deals', array('page' => $page), 301);
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

    public function dealSearchAllRedirectAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_dealSearch', array(), 301);
    }

    public function dealSearchAllAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->dealSearchAction('', 'all', 0, '', $seotitle, $seodescription, $seokeywords);
    }
    /*
     * This handles the landing page for Activities
     * Added for SEO purposes
     *
     * @param $seotitle
     * @param $seodescription
     * @param $seokeywords
     *
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function dealSearchActivitiesRedirectAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_dealSearch_activities', array(), 301);
    }

    public function dealSearchActivitiesAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->dealSearchAction('', 'activities', 0, '', $seotitle, $seodescription, $seokeywords);
    }
    /*
     * This handles the landing page for Attractions
     * Added for SEO purposes
     *
     * @param $seotitle
     * @param $seodescription
     * @param $seokeywords
     *
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function dealSearchAttractionsRedirectAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_dealSearch_attractions', array(), 301);
    }

    public function dealSearchAttractionsAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->dealSearchAction('', 'attractions', 0, '', $seotitle, $seodescription, $seokeywords);
    }
    /*
     * This handles the landing page for Attractions searching for DealName
     * Added for SEO purposes
     *
     * @param $seotitle
     * @param $seodescription
     * @param $seokeywords
     *
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function dealNameSearchAttractionsRedirectAction($dealName = '', $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_dealSearch_attractions_dealName', array('dealName' => $dealName), 301);
    }

    public function dealNameSearchAttractionsAction($dealName = '', $seotitle, $seodescription, $seokeywords)
    {
        return $this->dealSearchAction('', 'attractions', 0, $dealName, $seotitle, $seodescription, $seokeywords);
    }

    public function dealsAction($dealName = '', $seotitle, $seodescription, $seokeywords)
    {
        $request                              = Request::createFromGlobals();
        $this->data['dealsPageName']          = 'deals-index';
        $this->data['isMobile']               = $request->request->get('from_mobile', 0);
        $this->data['isindexpage']            = 1;
        $this->setHreflangLinks($this->generateLangRoute('_deals'), true, true);
        
        $mainEntityType_array                 = $this->get('TTServices')->getMainEntityTypeGlobal( $this->data['LanguageGet'], $this->container->getParameter('PAGE_TYPE_DEALS'));
        $this->data['mainEntityArray']        = $this->get('TTServices')->getMainEntityTypeGlobalData( $this->data['LanguageGet'], $mainEntityType_array,$this->container->getParameter('PAGE_TYPE_DEALS'));
        $this->data['dealblocksearchIndex']   = 1;
        $this->data['hideblocksearchButtons'] = 1;
        $this->data['pageBannerImage']        = $this->get("TTRouteUtils")->generateMediaURL('/media/images/index/attractions_homepage_image.jpg');
        $this->data['pageBannerH2']           = $this->translator->trans('Book an attraction');
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }

        $params                     = array();
        $params['langCode']         = $this->LanguageGet();
        $params['selectedCurrency'] = $this->data['selected_currency'];
        $dealSC                     = $this->get('DealServices')->getDealSearchCriteria($params);
        $deals                      = $this->get('DealServices')->getLandingPageTopTours($dealSC);
        $decoded_deals              = json_decode($deals, true);
        $this->data['deals']        = $decoded_deals['data'];

        return $this->render('@Deal/deal/deals.twig', $this->data);
    }

    public function dealSearchAction($city = '', $type = 'all', $priority = 0, $dealName = '', $seotitle, $seodescription, $seokeywords)
    {
        
        $this->data['dealblocksearchIndex']   = 1;
        $this->data['hideblocksearchButtons'] = 1;
        $this->data['dealsPageName']          = 'deals-search-results';

        $this->setHreflangLinks($this->generateLangRoute('_cityDealSearch', array('city' => $city, 'type' => $type, 'priority' => $priority, 'dealName' => $dealName)), true, true);
        $this->data['dealSearchFormAction'] = $this->generateLangRoute('_dealSearch');

        $this->data['defaultURL']     = $this->generateLangRoute('_dealSearch');
        $this->data['tourURL']        = $this->generateLangRoute('_dealSearch_tours');
        $this->data['activityURL']    = $this->generateLangRoute('_dealSearch_activities');
        $this->data['attractionsURL'] = $this->generateLangRoute('_dealSearch_attractions');

        if ($this->data['aliasseo'] == '') {
            $action_array           = array();
            $action_array[]         = $this->get('app.utils')->htmlEntityDecode($city);
            $action_text_display    = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display       = vsprintf($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'), $action_array);
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }

        $post     = Request::createFromGlobals();
        $langCode = $this->LanguageGet();

        if ($post->query->has('reservationId') && $post->query->get('reservationId') != '') {
            $bookingId         = $post->query->get('reservationId');
            $bookingDetailsObj = $this->get('DealServices')->getDealBookingCriteria(array('bookingId' => $bookingId));
            $bookingDetails    = $this->get('DealServices')->getBookingDetails($bookingDetailsObj);
            $decoded_booking   = json_decode($bookingDetails, true);
            $bookingResult     = $decoded_booking['data'];

            if ($bookingResult) {
                if (isset($bookingResult['db_dealTypeId']) && $bookingResult['db_dealTypeId'] == $this->container->getParameter('DEAL_TYPE_TRANSFERS')) {
                    return $this->redirectToLangRoute('_airport_transport');
                } else {
                    $packageEncoded = $this->get('DealServices')->getPackageById($bookingResult['db_dealDetailsId']);
                    $packageDecoded = json_decode($packageEncoded, true);
                    $dealResult     = $packageDecoded['data'];

                    if ($dealResult) {
                        return $this->tourDetailsLinkRedirect($dealResult['dd_id']);
                    } else {
                        $this->data['city'] = $bookingResult['dc_cityName'];
                    }
                }
            } else {
                $this->data['error'] = $this->translator->trans('Reservation not found! Please try again.');
            }
        }

        $citySearch = false;
        if (strlen(trim($city)) > 0) {
            $citySearch = true;
            $post->request->set('location', $city);
        }

        //dealName search
        if (strlen(trim($dealName)) > 0) {
            $post->request->set('dealName', str_replace('+', ' ', $dealName));
        }

        // search field values
        $this->data['city']         = '';
        $this->data['location']     = '';
        $this->data['days']         = '';
        $this->data['startDate']    = '';
        $this->data['endDate']      = '';
        $this->data['webgeoCityId'] = $this->container->getParameter('INITIAL_WEBGEOCITY_ID');
        $this->data['type']         = $type;
        $this->data['citySearch']   = $citySearch;
        $this->data['priority']     = $priority;
        $this->data['hasSearch']    = $this->container->getParameter('HAS_SEARCH_INITIAL');
        $this->data['langCode']     = $langCode;

        //setting initial value of min and max price
        $lowestPrice              = $this->container->getParameter('LOWEST_PRICE_SEARCH');
        $highestPrice             = $this->container->getParameter('HIGHEST_PRICE_SEARCH');
        $this->data['minPrice']   = $lowestPrice;
        $this->data['maxPrice']   = $highestPrice;
        $this->data['priceRange'] = $lowestPrice.','.$highestPrice;

        $params                     = array();
        $params['langCode']         = $langCode;
        $params['priority']         = $priority;
        $params['category']         = $type;
        $params['searchAll']        = false;
        $params['selectedCurrency'] = $this->data['selected_currency'];

        //if dealId exists, then redirect to details page
        if ($post->request->has('dealId') && !$post->request->get('cityId')) {
            if ($post->request->get('dealId') != '' && $post->request->get('dealId') != 0) {
                $dealId = $post->request->get('dealId');
                return $this->tourDetailsLinkRedirect($dealId);
            }
        }

        // refine search inputs
        if ($post->request->has('location')) {
            if ($post->request->get('location') != '') {
                // this line is for the template to retain value of refine search
                $this->data['city']   = $params['cityName']   = $post->request->get('location');
                $params['citySearch'] = $citySearch;

                if ($post->request->has('dealNameSearch')) {
                    $this->data['dealName']   = $params['dealNameSearch'] = $params['dealName']       = $post->request->get('dealNameSearch');
                }
            }

            // refine search minimum price
            if ($post->request->get('priceRange') != '') {
                $this->data['priceRange']   = $post->request->get('priceRange');
                $priceRange                 = explode(",", $this->data['priceRange']);
                $this->data['userMinPrice'] = $lowestPrice                = $params['minPrice']         = (int) $priceRange[0];
                $this->data['userMaxPrice'] = $highestPrice               = $params['maxPrice']         = (int) $priceRange[1];
            }
        } elseif ($post->request->has('search')) {
            // search autocomplete of city and dates
            // this line is for the template to retain value of search
            $this->data['hasSearch'] = $this->container->getParameter('HAS_SEARCH');
            $this->data['cityId']    = $post->request->get('cityId');
            $params['allTypes']      = false;

            // If city was taken from autocomplete, it means it has webgeocity id
            if ($post->request->get('cityId')) {
                $params['city']    = $post->request->get('cityId');
                $cityResultDecoded = $this->get('DealServices')->getDealCityInfoByCityId($params['city']);
                $decoded_city      = json_decode($cityResultDecoded, true);
                $cityInfo          = $decoded_city['data'];

                $this->data['city'] = $cityInfo['cityName'];
            } elseif ($post->request->has('attractionName')) {
                if ($post->request->get('attractionName') != '') {
                    $attractionName         = $post->request->get('attractionName');
                    $this->data['dealName'] = $params['dealName']     = $this->data['city']     = $attractionName;
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
        }
        // if were calling from deals page top attractions we pass dealName
        elseif ($post->request->has('dealName') && $post->request->get('dealName') != '') {
            $params['dealName']     = $this->data['dealName'] = $post->request->get('dealName');
        } else {
            $params['searchAll'] = true;
        }

        $dealSC               = $this->get('DealServices')->getDealSearchCriteria($params);
        $result               = $this->get('DealServices')->dealSearch($dealSC);
        $decoded_result       = json_decode($result, true);
        $this->data['result'] = $decoded_result['data'];

        //if more than one results
        if ($decoded_result['data']['numRowsCnt'] > 1) {
            $lowestPrice              = isset($decoded_result['data']['minPrice']) ? $decoded_result['data']['minPrice'] : $lowestPrice;
            $highestPrice             = isset($decoded_result['data']['maxPrice']) ? $decoded_result['data']['maxPrice'] : $highestPrice;
            $this->data['minPrice']   = $lowestPrice;
            $this->data['maxPrice']   = $highestPrice;
            $this->data['priceRange'] = $lowestPrice.','.$highestPrice;
        }

        //SET THE DEFAULT BANNER IMAGE IN CASE OF CITY IMAGE NOT FOUND IN THE BANNER IMAGES
        $this->data['pageBannerImage'] = $this->container->getParameter('PAGE_BANNER_DEFAULT_IMAGE');

        //SET THE BANNER IMAGE IN CASE OF CITY IMAGE FOUND IN THE BANNER IMAGES
        $cityNameBck           = strtolower($this->data['city']);
        $availableCitiesImages = array('paris', 'barcelona', 'beirut', 'dubai', 'istanbul', 'lisbon', 'milan', 'new-york', 'rome', 'singapore');
        if (in_array($cityNameBck, $availableCitiesImages)) {
            $this->data['pageBannerImage'] = $this->get("TTRouteUtils")->generateMediaURL('/media/images/deals/'.$cityNameBck.'.jpg');
        }

        return $this->render('@Deal/deal/deals-list.twig', $this->data);
    }

    public function dealsDetailsRedirectAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_deals_details_new', array(), 301);
    }

    public function dealsDetailsAction($city, $dealName, $packageId, $seotitle, $seodescription, $seokeywords)
    {
        
        $this->data['dealblocksearchIndex']   = 1;
        $this->data['hideblocksearchButtons'] = 1;
        $this->data['dealsPageName']          = 'deals-details';
        $selectedCurrency                     = $this->data['selected_currency'];
        $langCode                             = $this->LanguageGet();
        $packageEncoded                       = $this->get('DealServices')->getPackageById($packageId, $langCode);
        $packageDecoded                       = json_decode($packageEncoded, true);
        $result                               = $packageDecoded['data'];

        if (!$packageDecoded['success']) {
            return $this->redirectToLangRoute('_dealSearch');
        }
        $this->setHreflangLinks($this->tourDetailsLinkRedirect($packageId, false), true);

        if ($this->data['aliasseo'] == '') {
            $action_array = array();

            $action_array[] = $this->get('app.utils')->htmlEntityDecode($dealName);

            $action_text_display    = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array                 = array();
            $action_array[]               = $this->get('app.utils')->htmlEntityDecode($city);
            $action_array[]               = $this->get('app.utils')->htmlEntityDecode($dealName);
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array = array();

            $action_array[]            = $this->get('app.utils')->htmlEntityDecode($city);
            $action_text_display       = vsprintf($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'), $action_array);
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }

        $getDetails         = array();
        $getDetailsResponse = $this->get('DealServices')->getDetails($result['dd_dealCode'], $langCode, $selectedCurrency);
        $getDetailsJson     = json_decode($getDetailsResponse, true);

        if (!$getDetailsJson['success']) {
            $getDetails['errorMessage'] = $getDetailsJson['message'];
            $this->data['returnUrl']    = $this->get('app.utils')->generateLangURL($langCode, '/'.$result['dt_category'], 'deals');
        } else {
            $getDetails = $getDetailsJson['data'];
        }

        $getDetails['dealCategory'] = $result['dt_category'];
        $this->data['mapKey']       = $this->container->getParameter('MAP_KEY')[0];
        $this->data['apiId']        = $result['dd_dealApiId'];
        $this->data['packageId']    = $packageId;
        $this->data['details']      = $getDetails;

        return $this->render('@Deal/deal/deals-details.twig', $this->data);
    }

    public function dealsBookingDetailsRedirectAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_deals_booking_details', array(), 301);
    }

    public function dealsBookNowAction($seotitle, $seodescription, $seokeywords)
    {
        
        $this->data['dealsPageName'] = 'deals-book-now';
        $this->data['needpayment']   = $this->container->getParameter('PAYMENT_NEEDED');
        $request                     = $this->get('request');
        $post                        = $request->request->all();
        $langCode                    = $this->LanguageGet();
        $post['selectedCurrency']    = $this->data['selected_currency'];
        $conversionRate              = $this->get('CurrencyService')->getConversionRate('USD', $post['selectedCurrency']);
        $newConvertedTotal           = $this->get('CurrencyService')->currencyConvert($post['totalPrice'], $conversionRate);
        $post['convertedTotalPrice'] = $newConvertedTotal;
        $post['formattedTotalPrice'] = number_format($newConvertedTotal, 2, '.', ',');

        // setting the user id for this booking by the logged in user
        $userId = $this->userGetID();
        if (empty($userId)) {
            $userId                     = $this->data['isUserLoggedIn'] = 0;
        } else {
            $this->data['isUserLoggedIn'] = 1;
        }

        $countryEncoded            = $this->get('DealServices')->getCountryList();
        $countryDecoded            = json_decode($countryEncoded, true);
        $this->data['countryList'] = $countryDecoded['data'];

        $mobileCountryEncoded                = $this->get('DealServices')->getMobileCountryCodeList();
        $mobileDecoded                       = json_decode($mobileCountryEncoded, true);
        $this->data['mobileCountryCodeList'] = $mobileDecoded['data'];

        if (isset($post['bookNow'])) {
            $bookingDetails = array();

            //we need to assign post back to view
            foreach ($post as $key => $val) {
                $bookingDetails[$key] = $val;
            }

            $bookingDetails['userAgent']           = $request->headers->get('User-Agent');
            $bookingDetails['customerIP']          = $this->get('app.utils')->getUserIP();
            $bookingDetails['userId']              = $userId;
            $bookingDetails['langCode']            = $langCode;
            $bookingDetails['transactionSourceId'] = $this->container->getParameter('WEB_REFERRER');

            $bookingDetailsObj = $this->get('DealServices')->getDealBookingCriteria($bookingDetails);
            $resultEncoded     = $this->get('DealServices')->saveBookingData($bookingDetailsObj);
            $decoded_result    = json_decode($resultEncoded, true);
            $dealBookingId     = $decoded_result['data'];

            if ($dealBookingId) {

                $bookingEncoded = $this->get('DealServices')->findBookingById($dealBookingId);
                $bookingDecoded = json_decode($bookingEncoded, true);
                $dealBookingObj = $bookingDecoded['data'];

                $bookingDetailsObj->getCommonSC()->getPackage()->setCurrency($dealBookingObj['currency']);
                $bookingDetailsObj->setBookingId($dealBookingId);
                $bookingDetailsObj->setOnAccountCCType('cc');

                $paymentObj = $this->get('DealServices')->savePaymentData($bookingDetailsObj); // getting payment object
                $module_id  = $this->container->getParameter('MODULE_DEALS');
                $result     = $this->paymentApproval($paymentObj, $module_id);

                if (!isset($result["transaction_id"])) $result["transaction_id"] = null;

                // save payment id in deal booking table for this booking
                if (isset($result["transaction_id"]) && !empty($result["transaction_id"])) {
                    $updateBookingWithPayment = $this->get('DealServices')->updateBookingWithPayment($dealBookingId, $result["transaction_id"]);
                }

                //return $this->redirectDynamicRoute($result["callback_url"], array('transaction_id' => $result["transaction_id"]));
                return $this->redirectDynamicRoute('_deals_booking', array('transaction_id' => $result["transaction_id"]));
            } else {
                return $this->render('@Deal/deal/deals-bookNow.twig', $this->data);
            }
        } else {
            //if page gone through payment
            $transactionId = $request->query->get('transaction_id', '');
            if ($transactionId) {
                $bookingEncoded = $this->get('DealServices')->findBookingByUuid($transactionId);
                $bookingDecoded = json_decode($bookingEncoded, true);
                $dealBookingObj = $bookingDecoded['data'];
                $packageId      = $dealBookingObj['dealDetailsId'];
            } else {
                if (!isset($post['packageId'])) {
                    return $this->redirectToLangRoute('_deals', array('success' => false, 'message' => $this->translator->trans('Error! Invalid Package Id.')));
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

            $this->setHreflangLinks($this->tourDetailsLinkRedirect($packageId, false), true);
            return $this->render('@Deal/deal/deals-bookNow.twig', $this->data);
        }
        return $this->render('@Deal/deal/deals-bookNow.twig', $this->data);
    }

    public function dealsBookingStepsNewAction($dealName = '', $seotitle, $seodescription, $seokeywords)
    {
        
        return $this->render('@Deal/deal/deals-booking-steps_new.twig', $this->data);
    }
    /*
     * This handles the deal search results through AJAX for the NEW design
     *
     * @return search results tour_search_load_more_results.twig
     * @author Anna Lou Parejo <anna.parejo@touristtube.com>
     */

    public function getLoadMoreResultsListAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();

        $postVal = array('cityName' => 'location', 'minPrice' => 'minPrice', 'maxPrice' => 'maxPrice',
            'category' => 'dealType', 'offSet' => 'offSet', 'langCode' => 'langCode', 'startDate' => 'startDate',
            'endDate' => 'endDate', 'hasSearch' => 'hasSearch', 'dealName' => 'dealName', 'categoryIds' => 'categoryIds',
            'dealNameSearch' => 'dealNameSearch', 'selectedCurrency' => 'selectedCurrency', 'city' => 'cityId'
        );

        $params = array();
        foreach ($postVal as $key => $val) {
            if (isset($post[$val]) && $post[$val]) {
                $params[$key] = $post[$val];
            }
            if (isset($post['dealType']) && $post['dealType'] == 'all') {
                $params['allTypes'] = true;
            }
        }

        $dealSC               = $this->get('DealServices')->getDealSearchCriteria($params);
        $resultEncoded        = $this->get('DealServices')->dealSearch($dealSC);
        $decoded_result       = json_decode($resultEncoded, true);
        $result               = $decoded_result['data'];
        $this->data['result'] = $result;

        $return = $this->render('@Deal/deal/deal-list-search-results.twig', $this->data)->getContent();

        $jsonArr                = array();
        $jsonArr['twigResults'] = $return;
        $jsonArr['numRowCnt']   = $result['numRowsCnt'];
        $jsonArr['categoryIds'] = (isset($result['categoryIds']) && !empty($result['categoryIds'])) ? $result['categoryIds'] : array();
        $response               = new Response(json_encode($jsonArr));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /*
     * This handles the landing page for tourSearch
     * Added for SEO purposes
     *
     * @param $seotitle
     * @param $seodescription
     * @param $seokeywords
     *
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function dealSearchToursRedirectAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_dealSearch_tours', array(), 301);
    }

    public function dealSearchToursAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->dealSearchAction('', 'tours', 0, '', $seotitle, $seodescription, $seokeywords);
    }
    /*
     * This handles the landing page for tourSearch.
     * This will display list of avaiable packages of selected or all types.
     * Search can also be done by using the location or city.
     *
     * @param $city - destination to search
     * @param $type - by defaults its 'all' can be 'attractions','activities','transfers'...
     * @param $priority - if 1 means we list data per priority where we order by priority first
     * @param $dealName - dealName to be searched
     * @param $seotitle
     * @param $seodescription
     * @param $seokeywords
     *
     * @return array of deals
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function dealSearchRedirectAction($city = '', $type = 'all', $priority = 0, $dealName = '', $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_cityDealSearch', array('city' => $city, 'type' => $type, 'priority' => $priority, 'dealName' => $dealName), 301);
    }
    /*
     * This handles the landing page for the details of each package.
     *
     * @param $packageId
     * @param $city
     * @param $dealName
     * @param $seotitle
     * @param $seodescription
     * @param $seokeywords
     *
     * @return details of a particular deal from deal_details table
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function tourDetails1RedirectAction($packageId, $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_tourDetails', array('packageId' => $packageId), 301);
    }

    public function tourDetailsAttractionsRedirectAction($city, $dealName, $packageId, $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_tourDetails_attractions', array('city' => $city, 'dealName' => $dealName, 'packageId' => $packageId), 301);
    }

    public function tourDetailsToursRedirectAction($city, $dealName, $packageId, $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_tourDetails_tours', array('city' => $city, 'dealName' => $dealName, 'packageId' => $packageId), 301);
    }

    public function tourDetailsRedirectAction($city, $dealName, $packageId, $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_tourDetails_activities', array('city' => $city, 'dealName' => $dealName, 'packageId' => $packageId), 301);
    }

    /*
     * This method gets the booking box per date you select in the deal details page.
     * If deal is not available and error message will be returned.
     *
     * @return booking box tour_booking_box.twig template
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getTourPriceDetailsAction()
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

        $return   = $this->render('@Deal/deal/tour-booking-box.twig', $priceDetails)->getContent();
        $response = new Response(json_encode($return));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /*
     * This is the method verify the price after selecting the date
     *
     * @return price or error message
     */

    public function quoteBookingAction()
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

    public function getMandatoryFieldsRedirectAction()
    {
        return $this->redirectToLangRoute('_getMandatoryFields', array(), 301);
    }

    public function getMandatoryFieldsAction()
    {
        $this->data['dealsPageName'] = 'deals-mandatory-fields';

        $request = $this->get('request');
        $post    = $request->request->all();

        if (!isset($post['packageId'])) {
            return $this->redirectToLangRoute('_deals', array('success' => false, 'message' => $this->translator->trans('Error! Invalid Package Id.')));
        }

        $packageId = $post['packageId'];
        if (isset($post['bookingQuoteId'])) {
            $quoteEncoded = $this->get('DealServices')->getBookingQuotesById($post['bookingQuoteId']);
            $quoteDecoded = json_decode($quoteEncoded, true);
            $quote        = $quoteDecoded['data'];
            if (!$quoteDecoded['success']) {
                return $this->tourDetailsLinkRedirect($packageId);
            }

            //Take only the first Id cause Mandatory Field is just the same to all quote
            $this->data['mandatoryFields']    = $this->get('DealServices')->renderMandatoryFieldsNew(json_decode($quote[0]['dynamicFields']));
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
            $this->data['returnUrl']          = $this->tourDetailsLinkRedirect($packageId);

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

            $this->setHreflangLinks($this->tourDetailsLinkRedirect($packageId, false), true);
            return $this->render('@Deal/deal/deals-mandatory-fields.twig', $this->data);
        } else {
            return $this->tourDetailsLinkRedirect($packageId);
        }
    }
    /*
     * This is will redirect the user to details page
     *
     * @param $packageId
     * @param $autoRedirect - means we run redirect directly, IF false we only return url
     *
     * @return redirect
     * @author Anna Lou Parejo <anna.parejo@touristtube.com>
     */

    public function tourDetailsLinkRedirect($packageId, $autoRedirect = true)
    {
        $packageEncoded = $this->get('DealServices')->getPackageById($packageId, $this->LanguageGet());
        $packageDecoded = json_decode($packageEncoded, true);

        if (!$packageDecoded['success']) {
            return $this->redirectToLangRoute('_dealSearch');
        }

        $dealResult = $packageDecoded['data'];
        $dealName   = (isset($dealResult['dealNameTrans']) && $dealResult['dealNameTrans']) ? $dealResult['dealNameTrans'] : $dealResult['dd_dealName'];
        $dealName   = $this->get('app.utils')->cleanTitleData($dealName);
        $dealName   = str_replace('+', '-', $dealName);

        $cityName = $this->get('app.utils')->cleanTitleData($dealResult['dc_cityName']);
        $cityName = str_replace('+', '-', $cityName);

        if ($autoRedirect) {
            return $this->redirectToLangRoute('_tourDetails_'.$dealResult['dt_category'], array('city' => $cityName, 'dealName' => $dealName, 'packageId' => $packageId));
        } else {
            return $this->generateLangRoute('_tourDetails_'.$dealResult['dt_category'], array('city' => $cityName, 'dealName' => $dealName, 'packageId' => $packageId));
        }
    }
    /*
     * This is the method called to load ActivityReviewList through AJAX.
     * Also handles sorting of review lists
     *
     * @return array of customer reviews
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getActivityReviewsAction()
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
     * This method is the landing page to finalize hotel booking. Asks for guest and payment details.
     *
     * @return Redirect as per transaction source
     */
    public function paymentSuccessRedirectAction()
    {
        return $this->redirectToLangRoute('_deals_payment_success', array(), 301);
    }

    public function paymentSuccessAction()
    {
        $requestData = $this->get('request')->query->all();

        $dealReservation = $this->get('DealServices')->findBookingByUuid($requestData['transaction_id']);

        if (!$dealReservation) {
            //if error, just redirect to deals
            return $this->redirectToLangRoute('_deals');
        }

        $bookingDecoded = json_decode($dealReservation, true);
        $dealBookingObj = $bookingDecoded['data'];

        if ($dealBookingObj['transactionSourceId'] == $this->container->getParameter('CORPORATE_REFERRER')) {
            return $this->redirectToLangRoute('_corporate_deals_booking_process', $requestData);
        } else {
            return $this->redirectToLangRoute('_deals_booking', $requestData);
        }
    }

    /**
     * This method handles the callBack from PaymentBundle when a payment fails
     *
     * @return Redirect or render reservation as per transaction source
     */
    public function paymentFailedRedirectAction()
    {
        return $this->redirectToLangRoute('_deals_payment_failure', array(), 301);
    }

    public function paymentFailedAction()
    {
        $requestData     = $this->request->query->all();
        $dealReservation = $this->get('DealServices')->findBookingByUuid($requestData['transaction_id']);

        if (!$dealReservation) {
            //if error, just redirect to deals
            return $this->redirectToLangRoute('_deals');
        }

        $bookingDecoded = json_decode($dealReservation, true);
        $dealBookingObj = $bookingDecoded['data'];

        if ($dealBookingObj['transactionSourceId'] == $this->container->getParameter('CORPORATE_REFERRER')) {
            return $this->redirectToLangRoute('_corporate_book_now', $requestData);
        } else {
            return $this->redirectToLangRoute('_deals_book_now', $requestData);
        }
    }
    /*
     * This method is the actual process booking from the vendors API after payment confirmation from our payment gateway
     *
     * @return $results['redirectUrl'] / _deals
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function processBookingRedirectAction()
    {
        return $this->redirectToLangRoute('_deals_booking', array(), 301);
    }

    public function processBookingAction()
    {
        $request         = $this->get('request');
        $transactionId   = $request->query->get('transaction_id', '');
        $transactionType = $request->query->get('type', 'package');
        $from_mobile     = $request->query->get('from_mobile', '0');

        if (empty($transactionId)) {
            return;
        }

        $payment        = $this->get('DealServices')->findPaymentByUuid($transactionId);
        $bookingEncoded = $this->get('DealServices')->findBookingByUuid($transactionId);
        $bookingDecoded = json_decode($bookingEncoded, true);
        $dealBookingObj = $bookingDecoded['data'];

        if (!$payment || !$bookingDecoded['success']) {
            //release the hold amount of money
            $this->get('PaymentServiceImpl')->voidOnHoldPayment($transactionId);
            return $this->redirectToRoute('_deals', array('error' => $this->translator->trans('Error! No transaction found')));
        }

        $params                    = array();
        $params['paymentRequired'] = false;
        $params['bookingId']       = $dealBookingObj['id'];

        $bookingDetailsObj = $this->get('DealServices')->getDealBookingCriteria($params);
        $resultsEncoded    = $this->get('DealServices')->processBooking($bookingDetailsObj);
        $resultsDecoded    = json_decode($resultsEncoded, true);
        $results           = $resultsDecoded['data'];

        if (isset($results['redirectUrl']) && isset($results['redirectParams'])) {
            return $this->redirectToLangRoute($results['redirectUrl'], $results['redirectParams']);
        }

        //if error, just redirect to deals
        return $this->redirectToLangRoute('_deals');
    }

    /**
     * This method will retrieve booking details and handles cancellation
     *
     * @param $bookingId
     * @author Anna Lou H. Parejo <anna.parejo@touristtube.com>
     */
    public function cancelBookingAction($bookingId = 0)
    {
        if (!$bookingId || $bookingId == 0) {
            return false;
        }

        $bookingEncoded    = $this->get('DealServices')->getBookingDataForCancellation($bookingId);
        $bookingDecoded    = json_decode($bookingEncoded, true);
        $bookingDetailsObj = $bookingDecoded['data'];
        $resultEncoded     = $this->get('DealServices')->cancelBooking($bookingDetailsObj['bookingReference'], $bookingDetailsObj['email'], $bookingDetailsObj['dealType']);
        $decoded_result    = json_decode($resultEncoded, true);
        $cancelResults     = $decoded_result['data'];

        $cancelDetails = array();
        if (isset($cancelResults['errorCode']) && !empty($cancelResults['errorCode'])) {
            $cancelDetails['error'] = $this->translator->trans(/** @Ignore */$cancelResults['errorMessage']);
        } else {
            $cancelDetails['db_bookingStatus'] = $cancelResults['bookingStatus'];
            $cancelDetails['success']          = $this->translator->trans('Your booking has been successfully cancelled!');
        }

        return $cancelDetails;
    }

    public function cityActivitiesRedirectAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_city_activities', array(), 301);
    }

    public function cityActivitiesAction($seotitle, $seodescription, $seokeywords)
    {
        $this->setHreflangLinks($this->generateLangRoute('_city_activities'), true, true);
        
        $mainEntityType_array          = $this->get('TTServices')->getMainEntityTypeGlobal( $this->data['LanguageGet'], $this->container->getParameter('PAGE_TYPE_CITY_ACTIVITIES'));
        $this->data['mainEntityArray'] = $this->get('TTServices')->getMainEntityTypeGlobalData( $this->data['LanguageGet'], $mainEntityType_array);
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }
        return $this->render('@Deal/city-activities.twig', $this->data);
    }

    public function attractionsSkipTheLineRedirectAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_attractions_skip_the_line', array(), 301);
    }

    public function attractionsSkipTheLineAction($seotitle, $seodescription, $seokeywords)
    {
        $this->setHreflangLinks($this->generateLangRoute('_attractions_skip_the_line'), true, true);
        
        $mainEntityType_array          = $this->get('TTServices')->getMainEntityTypeGlobal( $this->data['LanguageGet'], $this->container->getParameter('PAGE_TYPE_ATTRACTIONS_SKIP_THE_LINE'));
        $this->data['mainEntityArray'] = $this->get('TTServices')->getMainEntityTypeGlobalData( $this->data['LanguageGet'], $mainEntityType_array);
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }
        return $this->render('@Deal/attractions-skip-the-line.twig', $this->data);
    }

    public function dealsBookingDetailsAction($bookingId = 0, $seotitle, $seodescription, $seokeywords)
    {
        $this->data['dealsPageName'] = 'deals-booking-details';

        if ($bookingId == 0) {
            return $this->redirectToLangRoute('_my_bookings');
        }

        $request                      = $this->get('request');
        $post                         = $request->request->all();
        $criteria                     = array();
        $criteria['bookingId']        = $bookingId;
        $criteria['selectedCurrency'] = $this->data['selected_currency'];

        $this->setHreflangLinks($this->generateLangRoute('_deals_booking_details', array('bookingId' => $bookingId)), true, true);
        

        $bookingObj                      = $this->get('DealServices')->getDealBookingCriteria($criteria);
        $bookingDetailsEncoded           = $this->get('DealServices')->getBookingDetails($bookingObj);
        $bookingDetailsDecoded           = json_decode($bookingDetailsEncoded, true);
        $bookingDetails                  = $bookingDetailsDecoded['data'];
        $bookingDetails['bookingStatus'] = $bookingDetails['db_bookingStatus'];

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

            $cancelEncoded = $this->get('DealServices')->cancelBooking($bookingDetailsObj['bookingReference'], $bookingDetailsObj['email'], $bookingDetailsObj['dealType']);
            $cancelDecoded = json_decode($cancelEncoded, true);

            if (!$cancelDecoded['success']) {
                $bookingDetails['error'] = $this->translator->trans($cancelDecoded['message']);
            } else {
                $cancelResults             = $cancelDecoded['data'];
                $bookingDetails            = array_merge($bookingDetails, $cancelResults);
                $bookingDetails['success'] = $this->translator->trans('Your booking has been successfully cancelled!');
            }
        }

        //we dont have a DealDetails data for transfers
        if ($bookingDetails['dt_category'] != 'transfers') {
            $bookingDetails['tourDetailsUrl'] = $this->tourDetailsLinkRedirect($bookingDetails['db_dealDetailsId'], false);
        }
        $bookingDetails['db_dealDescription'] = nl2br($bookingDetails['db_dealDescription']);
        $this->data['bookingDetails']         = $bookingDetails;
        $this->data['isUserLoggedIn']         = ($this->data['isUserLoggedIn'] ? 1 : 0);
        return $this->render('@Deal/deal/deals-booking-details.twig', $this->data);
    }
}
