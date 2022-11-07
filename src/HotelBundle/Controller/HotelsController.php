<?php

namespace HotelBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class HotelsController extends DefaultController
{
    private $infoSource;
    private $transactionSourceId;
    private $pageSrc;

    /**
     * This method sets the ContainerInterface.
     *
     * @param ContainerInterface $container The container (Optional; default=null).
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->infoSource          = $this->container->getParameter('modules')['hotels']['vendors']['amadeus']['infosource']['multisource'];
        $this->transactionSourceId = $this->container->getParameter('WEB_REFERRER');
        $this->pageSrc             = $this->container->getParameter('hotels')['page_src']['hotels'];
        $this->em                  = $this->getDoctrine()->getManager();

        $this->initRoutePathsAndOtherData();
    }

    /**
     * This method initializes HotelServiceConfig object to be used when calling a service.
     * @return \HotelBundle\Model\HotelServiceConfig
     */
    private function getHotelServiceConfig()
    {
        $hotelServiceConfig = new \HotelBundle\Model\HotelServiceConfig();
        $hotelServiceConfig->setUseTTApi(true);
        $hotelServiceConfig->setTransactionSourceId($this->transactionSourceId);
        $hotelServiceConfig->setPageSrc($this->pageSrc);
        $hotelServiceConfig->setInfoSource($this->infoSource);
        $hotelServiceConfig->setUserAgent($this->request->headers->get('User-Agent'));

        return $hotelServiceConfig;
    }

    /**
     * This method initializes necessary route paths and route names that is used by our twig/js/etc.
     */
    private function initRoutePathsAndOtherData()
    {
        $this->data['route_names'] = array(
            'hotel_booking_results' => '_hotel_booking_results_tt'
        );

        $this->data['route_paths'] = array(
            'hotel_avail' => $this->get('router')->getRouteCollection()->get('_hotel_avail_tt')->getPath(),
            'hotel_details' => $this->get('router')->getRouteCollection()->get('_hotel_details_tt')->getPath(),
            'hotel_offers' => $this->get('router')->getRouteCollection()->get('_hotel_offers_tt')->getPath(),
        );
    }

    /**
     * This method is the entry point for Amadeus Hotel search.
     *
     * @param  String   $seotitle       The seo title.
     * @param  String   $seodescription The seo description.
     * @param  String   $seokeywords    The seo keywords.
     * @return Rendered twig (hotel-search.twig).
     */
    public function hotelSearchAction($seotitle, $seodescription, $seokeywords)
    {
        $this->data['isindexpage'] = 1;
        $this->data['pageSrc']     = $this->pageSrc;

        //uglify assets
        $this->data['uglifyHotelBookingAssets'] = 1;

        $this->data['hotelblocksearchIndex']  = 1;
        $this->data['hideblocksearchButtons'] = 1;
        //$this->data['pageBannerImage']        = $this->get("TTRouteUtils")->generateMediaURL('/media/images/index/book_hotel_homepage_image.jpg');
        $this->data['pageBannerPano']         = 'hotel';
        $this->data['pageBannerH2']           = $this->translator->trans('Book your Hotel');

        $this->data['page']  = 'search';
        $this->data['input'] = array();

        $this->setHreflangLinks("/hotel-bookingTT");
        $mainEntityType_array          = $this->get('TTServices')->getMainEntityTypeGlobal($this->data['LanguageGet'], $this->container->getParameter('PAGE_TYPE_HOTEL_BOOKING'));
        $this->data['mainEntityArray'] = $this->get('TTServices')->getMainEntityTypeGlobalData($this->data['LanguageGet'], $mainEntityType_array);

        $this->data['needpayment'] = 1;
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }

        // The 18 top destinations featured on the landing page
        $topDestinations        = array();
        $topDestinations[]      = array('name' => 'Paris', 'link' => $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], 'Paris', 1818316, '', '', 1, 'TT'), 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/hotels/hotelbooking/paris.jpg'));
        $topDestinations[]      = array('name' => 'London', 'link' => $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], 'London', 1829266, '', '', 1, 'TT'), 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/hotels/hotelbooking/london.jpg'));
        $topDestinations[]      = array('name' => 'Prague', 'link' => $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], 'Prague', 1596791, '', '', 1, 'TT'), 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/hotels/hotelbooking/prague.jpg'));
        $topDestinations[]      = array('name' => 'Dubai', 'link' => $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], 'Dubai', 1060078, '', '', 1, 'TT'), 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/hotels/hotelbooking/dubai.jpg'));
        $topDestinations[]      = array('name' => 'Las Vegas', 'link' => $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], 'Las Vegas', 945234, '', '', 1, 'TT'), 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/hotels/hotelbooking/las-vegas.jpg'));
        $topDestinations[]      = array('name' => 'New York', 'link' => $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], 'New York', 937776, '', '', 1, 'TT'), 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/hotels/hotelbooking/new-york.jpg'));
        $topDestinations[]      = array('name' => 'Barcelona', 'link' => $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], 'Barcelona', 1736746, '', '', 1, 'TT'), 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/hotels/hotelbooking/barcelona.jpg'));
        $topDestinations[]      = array('name' => 'Beijing', 'link' => $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], 'Beijing', 1384846, '', '', 1, 'TT'), 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/hotels/hotelbooking/beijing.jpg'));
        $topDestinations[]      = array('name' => 'Rome', 'link' => $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], 'Rome', 2211494, '', '', 1, 'TT'), 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/hotels/hotelbooking/rome.jpg'));
        $topDestinations[]      = array('name' => 'Berlin', 'link' => $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], 'Berlin', 1668543, '', '', 1, 'TT'), 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/hotels/hotelbooking/berlin.jpg'));
        $topDestinations[]      = array('name' => 'Monaco', 'link' => $this->get('TTRouteUtils')->returnBookinSearchResultLink($this->data['LanguageGet'], 'Monaco', '', 2377282, 0, '', '', '', '', 0, '', 'TT'),
            'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/hotels/hotelbooking/monaco.jpg'));
        $topDestinations[]      = array('name' => 'Vienna', 'link' => $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], 'Vienna', 1115785, '', '', 1, 'TT'), 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/hotels/hotelbooking/vienna.jpg'));
        $topDestinations[]      = array('name' => 'Dublin', 'link' => $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], 'Dublin', 2081665, '', '', 1, 'TT'), 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/hotels/hotelbooking/dublin.jpg'));
        $topDestinations[]      = array('name' => 'Athens', 'link' => $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], 'Athens', 1861327, '', '', 1, 'TT'), 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/hotels/hotelbooking/athens.jpg'));
        $topDestinations[]      = array('name' => 'san francisco', 'link' => $this->get('TTRouteUtils')->returnBookinSearchResultLink($this->data['LanguageGet'], 'san francisco', '', 945345, 0, '', '', '', '', 0, '', 'TT'),
            'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/hotels/hotelbooking/san-francisco.jpg'));
        $topDestinations[]      = array('name' => 'bali', 'link' => $this->get('TTRouteUtils')->returnBookinSearchResultLink($this->data['LanguageGet'], 'bali', '', 248860, 0, '', '', '', '', 0, '', 'TT'),
            'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/hotels/hotelbooking/bali.jpg'));
        $topDestinations[]      = array('name' => 'Sydney', 'link' => $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], 'Sydney', 1135903, '', '', 1, 'TT'), 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/hotels/hotelbooking/sydney.jpg'));
        $topDestinations[]      = array('name' => 'Singapore', 'link' => $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], 'Singapore', 2449847, '', '', 1, 'TT'), 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/hotels/hotelbooking/singapore.jpg'));
        $this->data['discover'] = $topDestinations;

        return $this->render('@Hotel/hotel-search.twig', $this->data);
    }

    /**
     * This method is the landing page for search results (hotel listing).
     *
     * @param  String   $seotitle       The seo title.
     * @param  String   $seodescription The seo description.
     * @param  String   $seokeywords    The seo keywords.
     * @return Rendered twig (hotel-booking-results.twig).
     */
    public function hotelBookingResultsAction($seotitle, $seodescription, $seokeywords)
    {
        $this->data['pageSrc']                = $this->pageSrc;
        $this->data['hotelblocksearchIndex']  = 1;
        $this->data['hideblocksearchButtons'] = 1;
        $this->data['hotelupdatesearchIndex'] = 1;
        $this->data['pageName']               = "hotel-booking-results";

        //uglify assets
        $this->data['uglifyHotelBookingResultsAssets'] = 1;

        $requestData                  = $this->request->query->all();
        $this->data['page']           = 'list';
        $this->data['bookingresults'] = true;

        $this->setHreflangLinks("/hotel-booking-resultsTT");
        $this->logger->addHotelActivityLog('HOTELS', 'search', $this->getUserId(), $this->request->query->all());

        if (isset($requestData['dates'])) {
            // Handle the special "tonight", "tomorrow" and "weekend" values
            if ($requestData['dates'] == 'tonight') {
                $fromD = date('Y-m-d');
                $toD   = date('Y-m-d', strtotime('tomorrow'));
            } elseif ($requestData['dates'] == 'tomorrow') {
                $fromD = date('Y-m-d', strtotime('tomorrow'));
                $toD   = date('Y-m-d', strtotime('+1 day', strtotime($fromD)));
            } elseif ($requestData['dates'] == 'weekend') {
                $fromD1 = date('Y-m-d', strtotime('next Saturday'));
                $fromD  = date('Y-m-d', strtotime('-1 day', strtotime($fromD1)));
                $toD    = date('Y-m-d', strtotime('+2 day', strtotime($fromD)));
            }

            $requestData['fromDate'] = $fromD;
            $requestData['toDate']   = $toD;
        }
        $hotelSC = $this->get('HotelsServices')->getHotelSearchCriteria($requestData);

        $this->data['input']          = $hotelSC->toArray();
        $this->data['input']['stars'] = $this->request->query->get('stars', 0);
        $this->data['needpayment']    = (empty($hotelSC->getFromDate()) && empty($hotelSC->getToDate())) ? 0 : 1;

        if (empty($hotelSC->getCity()->getId()) && empty($hotelSC->getHotelId()) && empty($hotelSC->getCountry()) && empty($hotelSC->getLongitude()) && empty($hotelSC->getLatitude())) {
            // Direct URL access without params
            $this->data['error'] = $this->get('HotelsServices')->getErrorMessage(array('code' => 'HOTEL_7'));
        } elseif (!$this->get('HotelsServices')->validateDates($hotelSC->getFromDate(), $hotelSC->getToDate(), 'avail')) {
            $this->data['error'] = $this->translator->trans("Invalid Check-In/Check-Out date.");
        } elseif ($this->get('app.utils')->computeNights($hotelSC->getFromDate(), $hotelSC->getToDate()) > $this->container->getParameter('hotels')['reservation_max_nights']) {
            $action_array        = array();
            $action_array[]      = $this->container->getParameter('hotels')['reservation_max_nights'];
            $this->data['error'] = vsprintf($this->translator->trans("Reservations longer than %s nights are not possible."), $action_array);
        }

        if (!isset($this->data['error'])) {
            $this->data['districts'] = $this->get('HotelsServices')->getHotelDistricts($hotelSC);
        }

        //SEO
        if ($this->data['aliasseo'] == '') {
            $seoname = '';
            if ($hotelSC->getCity()->getName() != '') {
                $seoname = $this->get('app.utils')->htmlEntityDecode($hotelSC->getCity()->getName());
            }
            $action_array           = array();
            $action_array[]         = $seoname;
            $action_text_display    = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display       = vsprintf($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'), $action_array);
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }

        return $this->render('@Hotel/hotel-booking-results.twig', $this->data);
    }

    /**
     * This method is called through AJAX to do API related process to get hotel listing.
     *
     * @return Array List of hotels that matches the search criteria
     */
    public function hotelAvailAction()
    {
        $requestData = array_merge($this->request->request->all(), $this->request->query->all());

        if (isset($requestData['stars']) && !empty($requestData['stars'])) {
            $requestData['nbrStars'] = [$requestData['stars']];
        } elseif (!isset($requestData['nbrStars'])) {
            $requestData['nbrStars'] = [];
        }

        $requestData['infoSource'] = $this->infoSource;
        $hotelSC                   = $this->get('HotelsServices')->getHotelSearchCriteria($requestData);

        $hotelServiceConfig = $this->getHotelServiceConfig();
        $hotelServiceConfig->setTemplates(array(
            'mainLoopTemplate' => '@Hotel/hotel-booking-results-main-loop.twig',
            'paginationTemplate' => '@Hotel/hotel-booking-results-pagination.twig',
        ));

        return $this->get('HotelsServices')->hotelsAvailability($hotelServiceConfig, $hotelSC);
    }

    /**
     * This method is the landing page for hotel details.
     *
     * @return Rendered twig (hotel-details.twig)
     */
    public function hotelDetailsAction($name, $id, $seotitle, $seodescription, $seokeywords)
    {
        $this->data['pageSrc']                = $this->pageSrc;
        $this->data['hotelblocksearchIndex']  = 1;
        $this->data['hideblocksearchButtons'] = 1;
        $this->data['pageName']               = "hotel-details";

        $this->data['needpayment']      = 1;
        $this->data['showHeaderSearch'] = 0;

        //uglify assets
        $this->data['uglifyHotelDetailsAssets'] = 1;

        $request = array_merge($this->request->request->all(), $this->request->query->all());
        if (sizeof($request) > 0) {
            $this->data['hotelupdatesearchIndex'] = 1;
        } else {
            $this->data['hotelupdatesearchIndex'] = 0;
        }

        // If name is empty in URL, both {name} and {id} become considered as one variable inside {name}, fetch name and id correctly
        if (!$id && $name) {
            list($name, $id) = explode('-', $name);
        }

        // Canonical link
        $this->setHreflangLinks($this->get('HotelsServices')->returnHotelDetailedLink($name, $id), true, true);

        $request['hotelId']   = ($id) ? $id : $request['hotelId'];
        $request['hotelName'] = ($name) ? $name : $request['hotelNameURL'];
        $hotelSC              = $this->get('HotelsServices')->getHotelSearchCriteria($request);

        $hotelServiceConfig = $this->getHotelServiceConfig();
        $hotelServiceConfig->setRoutes(array(
            'refererURL' => $this->generateLangRoute('_hotel_details_tt', $request), // Provide url to restart when session gets expired
        ));

        $resultArray = $this->get('HotelsServices')->hotelDetails($hotelServiceConfig, $hotelSC);

        // Display pageNotFound if the hotel page does not exist
        if ($resultArray['hotelDetails']->getHotelId() == 0) {
            return $this->pageNotFoundAction();
        }

        $this->data = array_merge($this->data, $resultArray);

        //SEO
        if ($this->data['aliasseo'] == '' && !empty($this->data['hotelDetails'])) {
            $action_array = array();
            $seoname      = $this->get('app.utils')->htmlEntityDecode($this->data['hotelDetails']->getName());
            $seonameTitle = $seoname;
            $cityseo      = $this->get('app.utils')->htmlEntityDecode($this->data['hotelDetails']->getCity()->getName());
            if (strlen($cityseo) > 11) {
                $cityseo = substr($cityseo, 0, 11);
            }

            $hotname_length = 40 - strlen($cityseo) - 3;
            if (strlen($seonameTitle) > $hotname_length) {
                $seonameTitle = substr($seonameTitle, 0, $hotname_length);
            }

            $hotname_length_new = strlen($cityseo) + 3 + strlen($seonameTitle);
            if ($resultArray['hotelDetails']->getHas360() == 1 && $hotname_length_new < 33) {
                $cityseo .= ' '.$this->translator->trans('360 View');
            } else if ($resultArray['hotelDetails']->getHas360() == 1 && $hotname_length_new < 38) {
                $cityseo .= ' 360';
            }

            $action_array[]         = $seonameTitle;
            $action_array[]         = $cityseo.' '.$this->data['hotelDetails']->getCity()->getCountryCode();
            $action_text_display    = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
            $action_array           = array();
            if (strlen($seoname) > 28) {
                $seoname = substr($seoname, 0, 28);
            }
            $action_array[]               = $seoname;
            $action_array[]               = $cityseo;
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
            $action_array                 = array();
            $action_array[]               = $this->get('app.utils')->htmlEntityDecode($this->data['hotelDetails']->getCity()->getName());
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'), $action_array);
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }

        return $this->render('@Hotel/hotel-details.twig', $this->data);
    }

    /**
     * This method is called through AJAX to do API related process to get hotel detail and room offers.
     *
     * @return Array The hotel details and lists of offers.
     */
    public function hotelOffersAction($name, $id)
    {
        $request = $this->request->request->all();

        // If name is empty in URL, both {name} and {id} become considered as one variable inside {name}, fetch name and id correctly
        if (!$id) {
            list($name, $id) = explode('-', $name);
        }

        $request['hotelId']    = ($id) ? $id : $request['hotelId'];
        $request['hotelName']  = ($name) ? $name : $request['hotelNameURL'];
        $request['infoSource'] = $this->infoSource;
        $hotelSC               = $this->get('HotelsServices')->getHotelSearchCriteria($request);

        $hotelServiceConfig = $this->getHotelServiceConfig();
        $hotelServiceConfig->addRoute('refererURL', $request['refererURL']); // Provide url to restart when session gets expired
        $hotelServiceConfig->setTemplates(array(
            'offersLoopTemplate' => '@Hotel/hotel-details-offers.twig',
            'hotelAmenitiesTemplate' => '@Hotel/hotel-amenities.twig',
            'hotelFacilitiesTemplate' => '@Hotel/hotel-facilities.twig',
            'hotelDistancesTemplate' => '@Hotel/hotel-distances.twig',
            'hotelCreditCardsTemplate' => '@Hotel/hotel-credit-cards.twig',
            'hotelReviewHighlightsTemplate' => '@Hotel/hotel-review-highlights.twig'
        ));

        return $this->get('HotelsServices')->hotelOffers($hotelServiceConfig, $hotelSC);
    }

    /**
     * This method renders the landing page of the hotel reviews
     *
     * @param integer $hotelId The hotel id.
     *
     * @return
     */
    public function hotelReviewsAction($name, $id, $seotitle, $seodescription, $seokeywords)
    {
        $this->data['hotelblocksearchIndex']  = 1;
        $this->data['hideblocksearchButtons'] = 1;
        $this->data['showHeaderSearch']       = 0;

        // If name is empty in URL, both {name} and {id} become considered as one variable inside {name}, fetch name and id correctly
        if (!$id) {
            list($name, $id) = explode('-', $name);
        }

        //uglify assets
        $this->data['uglifyHotelReviewsAssets'] = 1;

        $hotelDetails = $this->get('HotelsServices')->getHotelInformation('reviews', $id, 0, '', null, $this->transactionSourceId);

        $request                  = array();
        $request['entityType']    = $this->container->getParameter('SOCIAL_ENTITY_HOTEL');
        $request['hotelCityName'] = str_replace("+", " ", $hotelDetails->getName());
        $request['hotelId']       = $hotelDetails->getHotelId();
        $request['hotelCityCode'] = $hotelDetails->getCity()->getCode();
        $request['cityId']        = $hotelDetails->getCity()->getId();
        $request['country']       = $hotelDetails->getCity()->getCountryName();
        $request['longitude']     = $hotelDetails->getLongitude();
        $request['latitude']      = $hotelDetails->getLatitude();
        //$request['nbrStars']      = $hotelDetails->getCategory();

        $this->data['input'] = $this->get('HotelsServices')->getHotelSearchCriteria($request)->toArray();

        $this->data['hotelDetails'] = $hotelDetails;
        $this->data['details_link'] = $this->get('HotelsServices')->returnHotelDetailedLink($hotelDetails->getName(), $hotelDetails->getHotelId());
        $this->setHreflangLinks($this->get('HotelsServices')->returnHotelReviewsLink($hotelDetails->getName(), $hotelDetails->getHotelId()), true, true);

        // SEO
        $objects_name    = $hotelDetails->getName();
        $locationTextSeo = $hotelDetails->getCity()->getName();
        if (strlen($locationTextSeo) > 14) {
            $locationTextSeo = substr($locationTextSeo, 0, 14);
        }
        if ($hotelDetails->getCity()->getCountryCode() && $hotelDetails->getCity()->getCountryCode() != '') {
            if ($locationTextSeo != '') {
                $locationTextSeo .= ' ';
            }
            $locationTextSeo .= $hotelDetails->getCity()->getCountryCode();
        }
        if ($this->data['aliasseo'] == '') {
            $action_array    = array();
            $objects_nameSEO = $objects_name;
            if (strlen($objects_nameSEO) > 20) {
                $objects_nameSEO = substr($objects_nameSEO, 0, 20);
            }
            $action_array[]         = $this->get('app.utils')->htmlEntityDecode($objects_nameSEO);
            $action_array[]         = $locationTextSeo;
            $action_text_display    = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array      = array();
            $objects_nameSEO   = $objects_name;
            $objects_nameSEOTT = $this->get('app.utils')->htmlEntityDecodeSEO($objects_nameSEO);
            if (strlen($objects_nameSEOTT) > 35) {
                $objects_nameSEOTT = substr($objects_nameSEOTT, 0, 35);
            }
            $action_array[]               = $objects_nameSEOTT.' '.$locationTextSeo;
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array              = array();
            $action_array[]            = $locationTextSeo;
            $action_array[]            = $this->get('app.utils')->htmlEntityDecode($objects_nameSEO);
            $action_text_display       = vsprintf($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'), $action_array);
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }

        $this->data['pageSrc'] = $this->pageSrc;

        return $this->render('@Hotel/hotel-reviews.twig', $this->data);
    }

    /**
     * This method renders the pop-up to submit custom reviews
     *
     * @return Rendered twig
     */
    public function hotelReviewsPopupAction()
    {
        $id                    = intval($this->request->query->get('id', 0));
        $hotelFromDB           = $this->get('HotelsServices')->getHotelObject($id);
        $this->data['namealt'] = $this->get('app.utils')->cleanTitleDataAlt($hotelFromDB->getPropertyName());
        $this->data['id']      = $id;
        $toRateArr             = array();

        $irating = $this->get('ReviewsServices')->socialRated($this->data['USERID'], $id, $this->container->getParameter('SOCIAL_ENTITY_HOTEL'), $this->container->getParameter('SOCIAL_HOTEL_RATE_TYPE_CLEANLINESS'));
        if (!$irating) {
            $irating = 0;
        }
        $toRateArr[] = array('id' => $this->container->getParameter('SOCIAL_HOTEL_RATE_TYPE_CLEANLINESS'), 'val' => $irating, 'name' => $this->translator->trans('Cleanliness'));

        $irating = $this->get('ReviewsServices')->socialRated($this->data['USERID'], $id, $this->container->getParameter('SOCIAL_ENTITY_HOTEL'), $this->container->getParameter('SOCIAL_HOTEL_RATE_TYPE_CONFORT'));
        if (!$irating) {
            $irating = 0;
        }
        $toRateArr[] = array('id' => $this->container->getParameter('SOCIAL_HOTEL_RATE_TYPE_CONFORT'), 'val' => $irating, 'name' => $this->translator->trans('Comfort'));

        $irating = $this->get('ReviewsServices')->socialRated($this->data['USERID'], $id, $this->container->getParameter('SOCIAL_ENTITY_HOTEL'), $this->container->getParameter('SOCIAL_HOTEL_RATE_TYPE_LOCATION'));
        if (!$irating) {
            $irating = 0;
        }
        $toRateArr[] = array('id' => $this->container->getParameter('SOCIAL_HOTEL_RATE_TYPE_LOCATION'), 'val' => $irating, 'name' => $this->translator->trans('Location'));

        $irating = $this->get('ReviewsServices')->socialRated($this->data['USERID'], $id, $this->container->getParameter('SOCIAL_ENTITY_HOTEL'), $this->container->getParameter('SOCIAL_HOTEL_RATE_TYPE_FACILITIES'));
        if (!$irating) {
            $irating = 0;
        }
        $toRateArr[] = array('id' => $this->container->getParameter('SOCIAL_HOTEL_RATE_TYPE_FACILITIES'), 'val' => $irating, 'name' => $this->translator->trans('Facilities'));

        $irating = $this->get('ReviewsServices')->socialRated($this->data['USERID'], $id, $this->container->getParameter('SOCIAL_ENTITY_HOTEL'), $this->container->getParameter('SOCIAL_HOTEL_RATE_TYPE_STAFF'));
        if (!$irating) {
            $irating = 0;
        }
        $toRateArr[] = array('id' => $this->container->getParameter('SOCIAL_HOTEL_RATE_TYPE_STAFF'), 'val' => $irating, 'name' => $this->translator->trans('Staff'));

        $irating = $this->get('ReviewsServices')->socialRated($this->data['USERID'], $id, $this->container->getParameter('SOCIAL_ENTITY_HOTEL'), $this->container->getParameter('SOCIAL_HOTEL_RATE_TYPE_MONEY'));
        if (!$irating) {
            $irating = 0;
        }
        $toRateArr[]             = array('id' => $this->container->getParameter('SOCIAL_HOTEL_RATE_TYPE_MONEY'), 'val' => $irating, 'name' => $this->translator->trans('Value for money'));
        $this->data['toRateArr'] = $toRateArr;

        return $this->render('@Hotel/hotel-reviews-popup.twig', $this->data);
    }

    /**
     * This method renders the pop-up to upload custom images
     *
     * @return Rendered twig
     */
    public function hotelReviewsPopupImagesAction()
    {
        $hotelId     = intval($this->request->query->get('id', 0));
        $hotelFromDB = $this->get('HotelsServices')->getHotelObject($hotelId);

        $imagesdetails = array();
        foreach ($this->get('HotelsServices')->getHotelImages($hotelId) as $images) {
            foreach ($images as $new_ar) {
                $imagesdetails[] = $new_ar;
            }
        }

        $this->data['picid']         = intval($this->request->query->get('pic', 0));
        $this->data['namealt']       = $this->get('app.utils')->cleanTitleDataAlt($hotelFromDB->getPropertyName());
        $this->data['imagesdetails'] = $imagesdetails;
        $this->data['trustyou']      = $this->get('HotelsServices')->getHotelReviews($hotelId);

        return $this->render('@Hotel/hotel-reviews-popup-images.twig', $this->data);
    }

    /**
     * This method is the landing page to finalize hotel booking. Asks for guest and payment details.
     *
     * @return Rendered twig (hotel-book.twig)
     */
    public function hotelBookAction()
    {
        $this->data['hotelblocksearchIndex']  = 1;
        $this->data['hideblocksearchButtons'] = 1;
        $this->data['pageSrc']                = $this->pageSrc;

        //uglify assets
        $this->data['uglifyHotelBookAssets'] = 1;

        $requestData = $this->request->request->all();

        if (!isset($requestData['fromDate'])) {
            return $this->redirectToLangRoute('_hotel_booking_tt');
        }

        $this->data['showHeaderSearch'] = 0;

        $error = $this->get('HotelsServices')->validateBookingRequest($requestData);

        if (!empty($error)) {
            $this->data['error'] = $error;
        } else {
            $requestData['userId'] = $this->getUserId();

            $hotelBC     = $this->get('HotelsServices')->getHotelBookingCriteria($requestData);
            $resultArray = $this->get('HotelsServices')->hotelBook($this->getHotelServiceConfig(), $hotelBC);

            $this->data = array_merge($this->data, $resultArray);
        }

        return $this->render('@Hotel/hotel-book.twig', $this->data);
    }

    /**
     * This method is the landing page to finalize hotel booking. Asks for guest and payment details.
     *
     * @return Redirect as per transaction source
     */
    public function paymentSuccessAction()
    {
        $requestData = $this->request->query->all();

        $this->logger->addHotelActivityLog('HOTELS', 'payment', $this->getUserId(), $requestData);

        $hotelReservation = $this->get('HotelsServices')->getHotelReservationRecord($requestData);

        if (!$hotelReservation) {
            $this->data['error'] = $this->translator->trans("Reservation request not found.");

            // when hotel reservation record is not found, check if successful payment
            // if yes, refund payment
            $this->data['error'] .= $this->get('HotelsServices')->refundPayment($hotelReservations);

            return $this->render('@Hotel/hotel-reservation.twig', $this->data);
        }

        $this->get('HotelsServices')->checkPayment($hotelReservation);

        if ($hotelReservation->getTransactionSourceId() == $this->container->getParameter('CORPORATE_REFERRER')) {
            return $this->redirectToLangRoute('_corporate_hotel_reservation', $requestData);
        } else {
            return $this->redirectToLangRoute('_hotel_reservation_tt', $requestData);
        }
    }

    /**
     * This method handles the callBack from PaymentBundle when a payment fails
     *
     * @return Redirect or render reservation as per transaction source
     */
    public function paymentFailedAction()
    {
        $requestData = $this->request->query->all();

        $this->logger->addHotelActivityLog('HOTELS', 'payment', $this->getUserId(), $requestData);

        $hotelReservation = $this->get('HotelsServices')->getHotelReservationRecord($requestData);

        $this->data['error'] = $this->get('HotelsServices')->checkPayment($hotelReservation);

        if ($hotelReservation->getTransactionSourceId() == $this->container->getParameter('CORPORATE_REFERRER')) {
            return $this->redirectToLangRoute('_corporate_hotel_reservation', $requestData);
        } else {
            return $this->render('@Hotel/hotel-reservation.twig', $this->data);
        }
    }

    /**
     * This method calls the HotelsServices and prepares the data needed for the interactive Map including the hotel location
     *
     * @return Rendered twig (GMaps.twig)
     */
    public function showOnMapAction()
    {
        $requestData = $this->request->query->all();

        if (isset($requestData['pageSrc']) && $requestData['pageSrc'] == $this->container->getParameter('hotels')['page_src']['hrs']) {
            $twigData = $this->get('HRSServices')->showOnMap($requestData['hotelId'], $requestData['requestId'], $requestData['trxSourceId']);
        } else {
            $twigData = $this->get('HotelsServices')->showOnMap($requestData['hotelId'], $requestData['requestId'], $requestData['trxSourceId']);
        }
        return $this->render('gmaps\GMaps.twig', $twigData);
    }

    /**
     * This method is the landing page to reserve and confirm booked hotel.
     *
     * @return Rendered twig (hotel-reservation.twig)
     */
    public function hotelReservationAction()
    {
        $this->data['pageSrc']                 = $this->pageSrc;
        $this->data['hotelblocksearchIndex']   = 1;
        $this->data['hideblocksearchButtons']  = 1;
        $this->data['hotelBookingRouteName']   = '_hotel_booking_tt';
        $this->data['hotelDetailsRouteName']   = '_hotel_details_tt';
        $this->data['bookingDetailsRouteName'] = '_booking_details_tt';
        $this->data['cancellationRouteName']   = '_hotel_reservation_cancellation_tt';
        $this->data['roomInfoTemplate']        = '@Hotel/hotel-room-offer-info.twig';

        //uglify assets
        $this->data['uglifyHotelReservationAssets'] = 1;

        $error           = '';
        $reservationInfo = null;

        $requestData   = array_merge($this->request->request->all(), $this->request->query->all());
        $reference     = $this->request->request->get('reference', '');
        $transactionId = $this->request->query->get('transaction_id', '');

        if (empty($reference) && empty($transactionId)) {
            return $this->redirectToLangRoute('_hotel_booking_tt');
        }

        $serviceConfig = $this->getHotelServiceConfig();

        $hotelReservation = $this->get('HotelsServices')->getHotelReservationRecord($requestData);

        // Step 1: Save to DB
        if (empty($hotelReservation)) {
            $requestData['infoSource']          = $this->infoSource;
            $requestData['transactionSourceId'] = $this->transactionSourceId;
            $requestData['userId']              = $this->getUserId();

            $hotelBC = $this->get('HotelsServices')->getHotelBookingCriteria($requestData);

            list($error, $hotelReservation, $paymentURL) = $this->get('HotelsServices')->saveReservationRequest($serviceConfig, $hotelBC);

            if (!$error && $hotelBC->isPrepaid()) {
                // send to payment
                return $this->redirectDynamicRoute($paymentURL, array('transaction_id' => $hotelReservation->getPaymentUUID()));
            } else {
                $hotelReservation->setReservationStatus($this->container->getParameter('hotels')['reservation_payment_completed']);
                $this->em->persist($hotelReservation);
                $this->em->flush();
            }
        } elseif ($this->transactionSourceId != $hotelReservation->getTransactionSourceId()) {
            $error = $this->translator->trans("Invalid reservation transaction source.");
        }

        // Complete reservation request
        if (!$error) {
            $serviceConfig->setPage('reservation');
            $serviceConfig->setRoutes(array(
                'hotelDetailsRouteName' => $this->data['hotelDetailsRouteName'],
                'bookingDetailsRouteName' => $this->data['bookingDetailsRouteName']));
            $serviceConfig->setTemplates(array('confirmationEmailTemplate' => '@Hotel/hotel-confirmation-email.twig'));

            $reservationInfo = $this->get('HotelsServices')->processHotelReservationRequest($serviceConfig, $hotelReservation);

            if (isset($reservationInfo['error'])) {
                $error = $reservationInfo['error'];
            } else {
                $this->data = array_merge($this->data, $reservationInfo);
            }
        }

        $this->data['hideAddBag'] = ($this->data['isUserLoggedIn']) ? 0 : 1;
        $this->data['error']      = $error;
        // Whatever error is encountered, we redirect them back to details page
        $this->data['refererURL'] = (isset($requestData['refererURL'])) ? $requestData['refererURL'] : $reservationInfo['refererURL'];

        return $this->render('@Hotel/hotel-reservation.twig', $this->data);
    }

    /**
     * This method is the landing page for booking details
     *
     * @return Rendered twig (hotel-booking-details.twig)
     */
    public function bookingDetailsAction($reference)
    {
        $this->data['page'] = 'booking_details';

        $serviceConfig = $this->getHotelServiceConfig();
        $serviceConfig->setPage('booking_details');

        $reservationInfo = $this->get('HotelsServices')->bookingDetails($serviceConfig, $reference);
        $this->data      = array_merge($this->data, $reservationInfo);

        //uglify assets
        $this->data['uglifyHotelBookingDetailsAssets'] = 1;

        $this->data['pageSrc']               = $this->pageSrc;
        $this->data['hotelDetailsRouteName'] = '_hotel_details_tt';
        $this->data['cancellationRouteName'] = '_hotel_reservation_cancellation_tt';
        $this->data['roomInfoTemplate']      = '@Hotel/hotel-room-offer-info.twig';

        return $this->render('@Hotel/hotel-booking-details.twig', $this->data);
    }

    /**
     * This method cancels a given reservation and renders the appropriate twig.
     *
     * @return Rendered twig (hotel-cancel-confirmation.twig).
     */
    public function hotelReservationCancellationAction($reference)
    {
        //uglify assets
        $this->data['uglifyHotelCancelConfirmationAssets'] = 1;

        $request = new \HotelBundle\Model\HotelCancellationForm();
        $request->setUserId(intval($this->userGetID()));
        $request->setReference($reference);

        $reservationKey = $this->request->request->get('reservationKey');
        $roomsToDisplay = $this->request->request->get('roomsToDisplay');

        if (!empty($reservationKey)) {
            if (!empty($roomsToDisplay)) {
                $rooms = json_decode($roomsToDisplay, true);
                foreach ($rooms as $item) {
                    $room = new \HotelBundle\Model\HotelRoomCancellationForm();
                    $room->setReservationKey($item['reservationKey']);
                    $room->setSegmentIdentifier($item['segmentIdentifier']);
                    $room->setSegmentNumber($item['segmentNumber']);

                    $request->addRoom($room);
                }
            }
        }

        $resultsArray = $this->get('HotelsServices')->cancelReservation($request, '@Hotel/hotel-cancellation-email.twig');

        $this->data               = array_merge($this->data, $resultsArray);
        $this->data['reference']  = $reference;
        $this->data['detailsURL'] = $this->generateLangRoute('_booking_details_tt', array('reference' => $reference));

        return $this->render('@Hotel/hotel-cancel-confirmation.twig', $this->data);
    }

    /**
     * This method will be called by our TT Rest API. It will take the data and insert them accordingly to hotel_search_response to be rendered/paginated.
     *
     * @return Response
     */
    public function hotelSearchRestApiCallbackAction()
    {
        $data    = array();
        $content = $this->get("request")->getContent();
        if (!empty($content)) {
            $data = json_decode($content, true);
        }

        // Read/Parse the data that the Hotels RestApi posted
        if ((isset($data['data']) && !empty($data['data'])) && (isset($data['requestId']) && !empty($data['requestId']))) {
            $results = $this->get('HotelsServices')->processHotelSearchResponseFromRestAPI($this->getHotelServiceConfig(), $data['requestId'], $data['data']);
        } else {
            $results['status'] = false;
            $results['error']  = 'no data or requestId found';
        }

        $results['post_data'] = $data;

        $errorLogFile = $this->logger->getLogFile('rest', __FUNCTION__, array(
            'hotelId' => ($data && isset($data['requestId']) ? $data['requestId'] : ''),
            'userId' => $this->getUserId(),
            'timeStart' => date('Ymd', time())
        ));

        $json_results = json_encode($results);

        $this->logger->info($errorLogFile, 'RESULTS', print_r($json_results, true));

        return new Response($json_results);
    }

    /**
     * This method prepares and renders the 360 full tour twig
     *
     * @return Rendered twig (photos-360.twig)
     */
    public function view360FullTourAction($name, $id)
    {
        // If name is empty in URL, both {name} and {id} become considered as one variable inside {name}, fetch name and id correctly
        if (!$id) {
            list($name, $id) = explode('-', $name);
        }

        $results = $this->get('HotelsServices')->getHotelDivisions($id, null, null, false, true);

        $menu360 = array();

        foreach ($results['data']['divisions'] as $data) {
            $divId    = $data['id'];
            $subDivId = null;

            if (isset($data['parent_id']) && $data['parent_id'] != "") {
                $subDivId = $divId;
                $divId    = $data['parent_id'];
            }

            $menu360[] = array(
                'name' => $data['name'],
                'type' => 'hotels',
                'entityName' => $results['data']['name'],
                'country' => $results['data']['country_code'],
                'entityId' => $results['data']['id'],
                'divisionId' => $divId,
                'groupId' => $data['group_id'],
                'groupName' => $data['group_name'],
                'catgId' => $data['category_id'],
                'subDivisionId' => $subDivId,
                'data_icon' => false
            );
        }

        $this->data['menu360'] = $menu360;

        $homeTT               = array();
        $homeTT[]             = array('name' => '', 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/images/360-photos/en-logo256.png'), 'link' => '/', 'title' => 'Tourist Tube');
        $this->data['homeTT'] = $homeTT;

        // Get related Things-To-Do
        $hotelThingsToDo = $this->get('HotelsServices')->getHotel360ThingsToDo($id);

        $menuTT = array();

        if ($hotelThingsToDo) {
            $title    = $hotelThingsToDo[0]->getTitle();
            $link     = str_replace(' ', '-', $title);
            $menuTT[] = array('name' => "$title", 'img' => '', 'link' => "/$link", 'title' => "$title");
        }

        $this->data['menuTT'] = $menuTT;

        // Page title
        $this->data['pageTitle'] = "360-photos ".$name;

        // Get hotel logo
        $media_360_base_path = $this->container->getParameter('MEDIA_360_BASE_PATH');
        $logo_path           = "hotels/".strtolower($results['data']['country_code'])."/".$id."/";
        $this->data['logo']  = $media_360_base_path.$logo_path.$results['data']['logo'];

        return $this->render('media_360/photos-360.twig', $this->data);
    }
}
