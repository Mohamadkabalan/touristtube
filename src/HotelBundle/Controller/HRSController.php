<?php

namespace HotelBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class HRSController extends DefaultController
{
    private $errorRepo;
    private $hsRepo;
    private $hotelRepo;
    private $pageSrc;

    /**
     * This method sets the container to be called in this class and also initializes the needed repositories
     *
     * @param ContainerInterface $container
     *
     * @return null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->errorRepo          = $this->getDoctrine()->getRepository('HotelBundle:ErrorMessages');
        $this->hotelRepo          = $this->getDoctrine()->getRepository('HotelBundle:CmsHotel');
        $this->imageRepo          = $this->getDoctrine()->getRepository('HotelBundle:CmsHotelImage');
        $this->hsRepo             = $this->getDoctrine()->getRepository('HotelBundle:CmsHotelSource');
        $this->reservationRepo    = $this->getDoctrine()->getRepository('HotelBundle:HotelReservation');
        $this->roomRepo           = $this->getDoctrine()->getRepository('HotelBundle:HotelRoomReservation');
        $this->searchRequestRepo  = $this->getDoctrine()->getRepository('HotelBundle:HotelSearchRequest');
        $this->searchResponseRepo = $this->getDoctrine()->getRepository('HotelBundle:HotelSearchResponse');

        $this->service = $this->get('HRSServices');

        // set default transaction source to web
        $this->transactionSourceId = $this->container->getParameter('WEB_REFERRER');

        $this->pageSrc = $this->container->getParameter('hotels')['page_src']['hrs'];

        $this->initRoutePathsAndOtherData();
    }

    /**
     * This method initializes HotelServiceConfig object to be used when calling a service.
     *
     * @return \HotelBundle\Model\HotelServiceConfig
     */
    private function getHotelServiceConfig()
    {
        $hotelServiceConfig = new \HotelBundle\Model\HotelServiceConfig();
        $hotelServiceConfig->setTransactionSourceId($this->transactionSourceId);
        $hotelServiceConfig->setPageSrc($this->pageSrc);

        $fromMobile = $this->request->request->get('from_mobile', 0);
        if (!$fromMobile) {
            $fromMobile = $this->request->query->get('from_mobile', 0);
        }
        $hotelServiceConfig->setIsRest($fromMobile);

        $preview360 = $this->request->request->get('360_preview', false);
        if (!$preview360) {
            $preview360 = $this->request->query->get('360_preview', false);
        }
        $hotelServiceConfig->setPreview360($preview360);

        return $hotelServiceConfig;
    }

    /**
     * This method initializes necessary route paths and route names that is used by our twig/js/etc.
     *
     * @return
     */
    private function initRoutePathsAndOtherData()
    {
        $this->data['route_names'] = array(
            'hotel_booking_results' => '_hotel_booking_results'
        );

        $this->data['route_paths'] = array(
            'hotel_avail' => $this->get('router')->getRouteCollection()->get('_hotel_avail')->getPath(),
            'hotel_details' => $this->get('router')->getRouteCollection()->get('_hotel_details')->getPath(),
            'hotel_offers' => $this->get('router')->getRouteCollection()->get('_hotel_offers')->getPath(),
        );
    }

    /**
     * This method prepares the data needed to render the best hotels in the world page
     *
     * @param $seotitle
     * @param $seodescription
     * @param $seokeywords
     *
     * @return Renders the twig
     */
    public function bestHotelsInTheWorldAction($seotitle, $seodescription, $seokeywords)
    {
        $this->setHreflangLinks($this->generateLangRoute('_best_hotels_in_the_world'), true, true);

        $this->data['isindexpage']     = 1;
        $this->data['hideblocksearch'] = 1;
        $this->data['pageBannerPano']  = 'hotel_360';

        $mainEntityType_array          = $this->get('TTServices')->getMainEntityTypeGlobal($this->data['LanguageGet'], $this->container->getParameter('PAGE_TYPE_BEST_HOTELS_IN_THE_WORLD'));
        $this->data['mainEntityArray'] = $this->get('TTServices')->getMainEntityTypeGlobalData($this->data['LanguageGet'], $mainEntityType_array);
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */ $seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */ $seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */ $seokeywords, array(), 'seo'));
        }

        return $this->render('@Hotel/hotel-best-in-world.twig', $this->data);
    }

    /**
     * This method prepares the data needed to render the main landing page of hotel booking
     *
     * @param $seotitle
     * @param $seodescription
     * @param $seokeywords
     *
     * @return Renders the main landing page of hotel booking
     */
    public function hotelBookingAction($seotitle, $seodescription, $seokeywords)
    {
        $this->data['pageSrc']                = $this->pageSrc;
        $this->data['isindexpage']            = 1;
        $this->data['hotelblocksearchIndex']  = 1;
        $this->data['hideblocksearchButtons'] = 1;
        //$this->data['pageBannerImage']        = $this->get("TTRouteUtils")->generateMediaURL('/media/images/index/book_hotel_homepage_image.jpg');
        $this->data['pageBannerPano']         = 'hotel';
        $this->data['pageBannerH2']           = $this->translator->trans('Book your Hotel');

        //uglify assets
        $this->data['uglifyHotelBookingAssets'] = 1;

        $this->setHreflangLinks($this->generateLangRoute('_hotel_booking'), true, true);

        $mainEntityType_array          = $this->get('TTServices')->getMainEntityTypeGlobal($this->data['LanguageGet'], $this->container->getParameter('PAGE_TYPE_HOTEL_BOOKING'));
        $this->data['mainEntityArray'] = $this->get('TTServices')->getMainEntityTypeGlobalData($this->data['LanguageGet'], $mainEntityType_array);
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */ $seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */ $seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */ $seokeywords, array(), 'seo'));
        }

        return $this->render('@Hotel/hotel-search.twig', $this->data);
    }

    /**
     * This method prepares the data needed to render the search results page (listing)
     *
     * @param $seotitle
     * @param $seodescription
     * @param $seokeywords
     *
     * @return Renders the search results page (listing)
     */
    public function hotelBookingResultsAction($seotitle, $seodescription, $seokeywords)
    {
        $this->data['pageSrc']                = $this->pageSrc;
        $this->data['hotelblocksearchIndex']  = 1;
        $this->data['hideblocksearchButtons'] = 1;
        $this->data['hotelupdatesearchIndex'] = 1;
        $this->data['bookingresults']         = true;
        $this->data['pageName']               = "hotel-booking-results";

        // Uglify assets
        $this->data['uglifyHotelBookingResultsAssets'] = 1;

        $requestData = array_merge($this->request->request->all(), $this->request->query->all());

        $this->data['page']           = 'list';
        $this->data['bookingresults'] = true;

        $this->setHreflangLinks("/hotel-booking-results");
        $this->logger->addHotelActivityLog('HRS', 'search', $this->getUserId(), $requestData);

        // Handle the special "tonight", "tomorrow" and "weekend" values
        if (isset($requestData['dates'])) {
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

        $hotelSC = $this->service->getHotelSearchCriteria($requestData);

        $this->data['input']          = $hotelSC->toArray();
        $this->data['input']['stars'] = (isset($requestData['stars'])) ? $requestData['stars'] : '';
        $this->data['needpayment']    = (empty($hotelSC->getFromDate()) && empty($hotelSC->getToDate())) ? 0 : 1;

        if (empty($hotelSC->getLocationId()) && empty($hotelSC->getHotelId()) && empty($hotelSC->getCountry()) && empty($hotelSC->getLongitude()) && empty($hotelSC->getLatitude())) {
            // Direct URL access without params
            $this->data['error'] = $this->service->getErrorMessage(array('code' => 'HOTEL_7'));
        } elseif ((isset($requestData['fromDate']) && isset($requestData['toDate'])) && !$this->service->validateDates($requestData['fromDate'], $requestData['toDate'], 'avail')) {
            $this->data['error'] = $this->translator->trans("Invalid Check-In/Check-Out date.");
        }

        if (!isset($this->data['error'])) {
            $this->data['districts'] = $this->service->getHotelDistricts($hotelSC);
        }

        // SEO
        if ($this->data['aliasseo'] == '') {
            $seoname = '';
            if ($hotelSC->getCity()->getName() != '') {
                $seoname = $this->get('app.utils')->htmlEntityDecode($hotelSC->getCity()->getName());
            }
            $action_array   = array();
            $action_array[] = $seoname;

            $action_text_display    = vsprintf($this->translator->trans(/** @Ignore */ $seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */ $seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display       = vsprintf($this->translator->trans(/** @Ignore */ $seokeywords, array(), 'seo'), $action_array);
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }

        return $this->handleResponse($this->data, '@Hotel/hotel-booking-results.twig', $this->request->query->get('from_mobile', 0));
    }

    /**
     * This is an ajax call to the HRS availability api i.e. hotelAvail
     *
     * @return
     */
    public function hotelAvailAction()
    {
        $requestData = array_merge($this->request->request->all(), $this->request->query->all());

        if (isset($requestData['stars']) && !empty($requestData['stars'])) {
            $requestData['nbrStars'] = [$requestData['stars']];
        } elseif (!isset($requestData['nbrStars'])) {
            $requestData['nbrStars'] = [];
        }

        if (isset($requestData['from_mobile']) && $requestData['from_mobile']) {
            $childAge = array();
            $childBed = array();
            for ($i = 1; $i <= 6; $i++) {
                $childAge[$i] = isset($requestData['childAge'.$i]) ? $requestData['childAge'.$i] : 0;
                $childBed[$i] = isset($requestData['childBed'.$i]) ? $requestData['childBed'.$i] : 'parentsBed';
            }
            $requestData['childAge'] = $childAge;
            $requestData['childBed'] = $childBed;
        }

        $hotelSC = $this->service->getHotelSearchCriteria($requestData);

        $hotelServiceConfig = $this->getHotelServiceConfig();
        $hotelServiceConfig->setTemplates(array(
            'mainLoopTemplate' => '@Hotel/hotel-booking-results-main-loop.twig',
            'paginationTemplate' => '@Hotel/hotel-booking-results-pagination.twig',
        ));

        return $this->service->hotelsAvailability($hotelServiceConfig, $hotelSC);
    }

    /**
     * This method prepares the data needed to render the hotel details page
     *
     * @param $name
     * @param $id
     * @param $seotitle
     * @param $seodescription
     * @param $seokeywords
     *
     * @return Renders the hotel details page
     */
    public function hotelDetailsAction($name, $id, $seotitle, $seodescription, $seokeywords)
    {
        $this->data['pageSrc']                = $this->pageSrc;
        $this->data['hotelblocksearchIndex']  = 1;
        $this->data['hideblocksearchButtons'] = 1;
        $this->data['pageName']               = "hotel-details";
        $this->data['detailspage']            = 1;


        $this->data['uglifyHotelDetailsAssets'] = 1;

        $request = array_merge($this->request->request->all(), $this->request->query->all());
        if (sizeof($request) > 0) {
            $this->data['hotelupdatesearchIndex'] = 1;
        } else {
            $this->data['hotelupdatesearchIndex'] = 0;
        }

        $request             = array_merge($this->service->getDefaultSearchValues(), $request);
        $this->data['input'] = $request;

        // Always set the hotelCityName, hotelKey, hotelId and locationId based on url
        if (!$id) {
            list($name, $id) = explode('-', $name);
        }

        $request['hotelId']   = ($id) ? $id : $request['hotelId'];
        $request['hotelName'] = ($name) ? $name : $request['hotelNameURL'];
        $hotelSC              = $this->service->getHotelSearchCriteria($request);

        $hotelServiceConfig = $this->getHotelServiceConfig();
        $hotelServiceConfig->setRoutes(array(
            'refererURL' => $this->generateLangRoute('_hotel_details', $request), // Provide url to restart when session gets expired
        ));

        $resultArray = $this->service->hotelDetails($hotelServiceConfig, $hotelSC);

        // Display pageNotFound if the hotel page does not exist
        if (empty($resultArray['hotelDetails'])) {
            return $this->pageNotFoundAction();
        }

        $this->data = array_merge($this->data, $resultArray);

        // Canonical link
        $this->setHreflangLinks($this->get('TTRouteUtils')->returnHotelDetailedLink($this->data['LanguageGet'], $this->data['hotelDetails']['name'], $id), true, true);

        //SEO
        if ($this->data['aliasseo'] == '' && !empty($this->data['hotelDetails'])) {
            $langCode     = $this->LanguageGet();
            $action_array = array();
            $seoname      = $this->get('app.utils')->htmlEntityDecode($this->data['hotelDetails']['name']);
            $seonameTitle = $seoname;
            $cityseo      = $this->get('app.utils')->htmlEntityDecode($this->data['hotelDetails']['city']);
            if (strlen($cityseo) > 11) {
                $cityseo = substr($cityseo, 0, 11);
            }

            $hotname_length = 40 - strlen($cityseo) - 3;
            if (strlen($seonameTitle) > $hotname_length) {
                $seonameTitle = substr($seonameTitle, 0, $hotname_length);
            }

            $hotname_length_new = strlen($cityseo) + 3 + strlen($seonameTitle);
            if ($resultArray['hotelDetails']['has360'] == 1 && $hotname_length_new < 33) {
                $cityseo .= ' '.$this->translator->trans('360 View');
            } else if ($resultArray['hotelDetails']['has360'] == 1 && $hotname_length_new < 38) {
                $cityseo .= ' 360';
            }

            $action_array[]         = $seonameTitle;
            $action_array[]         = $cityseo.' '.$this->data['hotelDetails']['countryCode'];
            $action_text_display    = vsprintf($this->translator->trans(/** @Ignore */ $seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
            $action_array           = array();
            if (strlen($seoname) > 28) {
                $seoname = substr($seoname, 0, 28);
            }
            $action_array[]               = $seoname;
            $action_array[]               = $cityseo;
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */ $seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
            $action_array                 = array();
            $action_array[]               = $this->get('app.utils')->htmlEntityDecode($this->data['hotelDetails']['city']);
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */ $seokeywords, array(), 'seo'), $action_array);
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }

        return $this->handleResponse($this->data, '@Hotel/hotel-details.twig', $this->request->query->get('from_mobile', 0));
    }

    /**
     * This method prepares the hotel review page
     *
     * @param $name
     * @param $id
     * @param $seotitle
     * @param $seodescription
     * @param $seokeywords
     *
     * @return
     */
    public function hotelReviewsAction($name, $id, $seotitle, $seodescription, $seokeywords)
    {
        $this->data['hotelblocksearchIndex']  = 1;
        $this->data['hideblocksearchButtons'] = 1;

        // If name is empty in URL, both {name} and {id} become considered as one variable inside {name}, fetch name and id correctly
        if (!$id) {
            list($name, $id) = explode('-', $name);
        }

        //uglify assets
        $this->data['uglifyHotelReviewsAssets'] = 1;
        $this->data['input']['hotelId']         = $id;
        $this->data['input']['entityType']      = $this->container->getParameter('SOCIAL_ENTITY_HOTEL');
        $this->data['input']['locationId']      = $this->hsRepo->getHotelSourceField('locationId', array('hotelId', $id));
        $this->data['hotelDetails']             = $this->service->getHotelInfoData($id, $this->LanguageGet());
        $this->data['input']['hotelNameURL']    = $this->data['hotelDetails']['hotelNameURL'];
        $this->data['input']['city']['name']    = $this->data['hotelDetails']['name'];
        $this->data['input']['hotelCityName']   = str_replace("+", " ", $this->data['hotelDetails']['name']);
        $this->data['input']['country']         = $this->data['hotelDetails']['country'];
        $this->data['input']['longitude']       = $this->data['hotelDetails']['longitude'];
        $this->data['input']['latitude']        = $this->data['hotelDetails']['latitude'];
        $this->data['discover_nam']             = $this->data['hotelDetails']['hotelNameURL'].'_'.$id.'_';
        $this->data['hotelDetails']['trustyou'] = $this->get('ReviewServices')->getMetaReview($this->hsRepo->getHotelSourceField('trustyouId', array('hotelId', $id)), $this->LanguageGet(), 'hotelReviews');
        // SEO
        $objects_name                           = $this->data['hotelDetails']['name'];
        $locationTextSeo                        = $this->data['hotelDetails']['city'];

        if (strlen($locationTextSeo) > 14) {
            $locationTextSeo = substr($locationTextSeo, 0, 14);
        }

        if ($this->data['hotelDetails']['countryCode'] && $this->data['hotelDetails']['countryCode'] != '') {
            if ($locationTextSeo != '') {
                $locationTextSeo .= ' ';
            }
            $locationTextSeo .= $this->data['hotelDetails']['countryCode'];
        }

        $this->data['details_link'] = $this->get('TTRouteUtils')->returnHotelDetailedLink($this->data['LanguageGet'], $this->data['hotelDetails']['name'], $this->data['input']['hotelId']);
        $this->setHreflangLinks($this->get('TTRouteUtils')->returnHotelReviewsLink($this->data['LanguageGet'], $this->data['hotelDetails']['name'], $this->data['input']['hotelId']), true, true);

        if ($this->data['aliasseo'] == '') {
            $action_array    = array();
            $objects_nameSEO = $objects_name;
            if (strlen($objects_nameSEO) > 20) {
                $objects_nameSEO = substr($objects_nameSEO, 0, 20);
            }
            $action_array[]         = $this->get('app.utils')->htmlEntityDecode($objects_nameSEO);
            $action_array[]         = $locationTextSeo;
            $action_text_display    = vsprintf($this->translator->trans(/** @Ignore */ $seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array      = array();
            $objects_nameSEO   = $objects_name;
            $objects_nameSEOTT = $this->get('app.utils')->htmlEntityDecodeSEO($objects_nameSEO);
            if (strlen($objects_nameSEOTT) > 35) {
                $objects_nameSEOTT = substr($objects_nameSEOTT, 0, 35);
            }
            $action_array[]               = $objects_nameSEOTT.' '.$locationTextSeo;
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */ $seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array              = array();
            $action_array[]            = $locationTextSeo;
            $action_array[]            = $this->get('app.utils')->htmlEntityDecode($objects_nameSEO);
            $action_text_display       = vsprintf($this->translator->trans(/** @Ignore */ $seokeywords, array(), 'seo'), $action_array);
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }

        $this->data['pageSrc'] = $this->pageSrc;

        return $this->render('@Hotel/hotel-reviews.twig', $this->data);
    }

    /**
     * This method prepares the hotel reviews pop-up
     *
     * @param $seotitle
     * @param $seodescription
     * @param $seokeywords
     *
     * @return
     */
    public function hotelReviewsPopupAction($seotitle, $seodescription, $seokeywords)
    {
        $id                    = intval($this->request->query->get('id', 0));
        $hotelFromDB           = $this->hotelRepo->getHotelById($id);
        $this->data['namealt'] = $this->get('app.utils')->cleanTitleDataAlt($hotelFromDB->getName());
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
     * This method prepares the hotel reviews images pop-up
     *
     * @return
     */
    public function hotelReviewsPopupImagesAction()
    {
        $hotelId               = intval($this->request->query->get('id', 0));
        $picid                 = intval($this->request->query->get('pic', 0));
        $hotelFromDB           = $this->hotelRepo->getHotelById($hotelId);
        $this->data['namealt'] = $this->get('app.utils')->cleanTitleDataAlt($hotelFromDB->getName());
        $hotelImages           = $this->imageRepo->getHotelImages($hotelId, null, -1);
        $imagesdetails         = array();

        foreach ($hotelImages as $img) {
            $new_ar = $this->get("HRSServices")->createImageSource($img, 0);

            if (!is_array($new_ar)) {
                $new_ar = array($new_ar);
            }

            $new_ar['user_id'] = $img['userId'];
            $new_ar['id']      = $img['id'];
            $imagesdetails[]   = $new_ar;
        }

        $this->data['imagesdetails'] = $imagesdetails;
        $this->data['picid']         = $picid;
        $this->data['trustyou']      = $this->get('ReviewServices')->getMetaReview($this->hsRepo->getHotelSourceField('trustyouId', array('hotelId', $hotelId)), $this->LanguageGet(), 'hotelReviews');

        return $this->render('@Hotel/hotel-reviews-popup-images.twig', $this->data);
    }

    /**
     * This is the api call to HRS to get the hotel details i.e hotelDetailAvail
     *
     * @param $name
     * @param $id
     *
     * @return
     */
    public function hotelOffersAction($name, $id)
    {
        $request = $this->request->request->all();

        // If name is empty in URL, both {name} and {id} become considered as one variable inside {name}, fetch name and id correctly
        if (!$id) {
            list($name, $id) = explode('-', $name);
        }

        $request['hotelId']   = ($id) ? $id : $request['hotelId'];
        $request['hotelName'] = ($name) ? $name : $request['hotelNameURL'];

        if (isset($request['from_mobile']) && $request['from_mobile']) {
            $childAge = array();
            $childBed = array();
            for ($i = 1; $i <= 6; $i++) {
                $childAge[$i] = isset($request['childAge'.$i]) ? $request['childAge'.$i] : 0;
                $childBed[$i] = isset($request['childBed'.$i]) ? $request['childBed'.$i] : 'parentsBed';
            }
            $request['childAge'] = $childAge;
            $request['childBed'] = $childBed;
        }

        $hotelSC = $this->service->getHotelSearchCriteria($request);

        $hotelServiceConfig = $this->getHotelServiceConfig();
        $hotelServiceConfig->addRoute('refererURL', $hotelSC->getRefererURL()); // Provide url to restart when session gets expired
        $hotelServiceConfig->setTemplates(array(
            'offersLoopTemplate' => '@Hotel/hotel-details-offers.twig',
            'hotelDistancesTemplate' => '@Hotel/hotel-distances.twig',
            'hotelCreditCardsTemplate' => '@Hotel/hotel-credit-cards.twig',
            'hotelReviewHighlightsTemplate' => '@Hotel/hotel-review-highlights.twig'
        ));

        return $this->service->hotelOffers($hotelServiceConfig, $hotelSC);
    }

    /**
     * This method renders the hotel book form
     *
     * @return
     */
    public function hotelBookAction()
    {
        $this->data['pageSrc'] = $this->pageSrc;

        //uglify assets
        $this->data['uglifyHotelBookAssets'] = 1;

        $requestData               = $this->request->request->all();
        $requestData['refererURL'] = $this->request->headers->get('referer');
        $requestData['userId']     = $this->getUserId();

        if (!isset($requestData['fromDate']) || !isset($requestData['toDate'])) {
            return $this->redirectToLangRoute('_hotel_booking');
        }

        $hotelBC     = $this->service->getHotelBookingCriteria($requestData);
        $resultArray = $this->service->hotelBook($this->getHotelServiceConfig(), $hotelBC);

        $this->data = array_merge($this->data, $resultArray);

        return $this->handleResponse($this->data, '@Hotel/hotel-book.twig', $this->request->request->get('from_mobile', 0));
    }

    /**
     * This method calls the HRS reservation api i.e. hotelReservation
     *
     * @return
     */
    public function hotelReservationAction()
    {
        $this->data['pageSrc']                 = $this->pageSrc;
        $this->data['hotelBookingRouteName']   = '_hotel_booking';
        $this->data['hotelDetailsRouteName']   = '_hotel_details';
        $this->data['bookingDetailsRouteName'] = '_booking_details';
        $this->data['cancellationRouteName']   = '_hotel_reservation_cancellation';
        $this->data['roomInfoTemplate']        = '@Hotel/hotel-room-offer-info.twig';

        //uglify assets
        $this->data['uglifyHotelReservationAssets'] = 1;

        $requestData = $this->request->request->all(); // should contain reference and transactionId

        $serviceConfig = $this->getHotelServiceConfig();

        $requestData['transactionSourceId'] = $this->transactionSourceId;
        $requestData['userId']              = $this->getUserId();

        $hotelBC = $this->service->getHotelBookingCriteria($requestData);

        $resultArray = $this->service->processHotelReservationRequest($serviceConfig, $hotelBC);

        $this->data = array_merge($this->data, $resultArray);

        return $this->handleResponse($this->data, '@Hotel/hotel-reservation.twig', $this->request->request->get('from_mobile', 0));
    }

    /**
     * This method prepares the booking details page of a single reservation
     *
     * @param $reference
     *
     * @return Renders the booking details twig
     */
    public function bookingDetailsAction($reference)
    {
        $from_mobile = $this->request->request->get('from_mobile', 0);

        $serviceConfig = $this->getHotelServiceConfig();
        $serviceConfig->setPage('BOOKING_DETAILS');

        $reservationInfo = $this->service->bookingDetails($serviceConfig, $reference);

        if (empty($from_mobile)) {
            //uglify assets
            $this->data['uglifyHotelBookingDetailsAssets'] = 1;

            $this->data['pageSrc']               = $this->pageSrc;
            $this->data['hotelDetailsRouteName'] = '_hotel_details';
            $this->data['cancellationRouteName'] = '_hotel_reservation_cancellation';
            $this->data['roomInfoTemplate']      = '@Hotel/hotel-room-offer-info.twig';

            $this->data = array_merge($this->data, $reservationInfo);
        } else {
            $this->data = $reservationInfo;
        }

        return $this->handleResponse($this->data, '@Hotel/hotel-booking-details.twig', $from_mobile);
    }

    /**
     * This method prepares the hotel modification page
     *
     * @param $reference
     *
     * @return Renders the hotel modification page
     */
    public function hotelModificationAction($reference)
    {
        //uglify assets
        $this->data['uglifyHotelModificationAssets'] = 1;

        $this->data['pageSrc'] = $this->pageSrc;

        $requestData              = $this->request->request->all();
        $requestData['reference'] = $reference;

        if (empty($requestData['reservationKey'])) {
            $this->data['error'] = 'Missing required information';
        } else {
            $this->data = array_merge($this->data, $this->service->hotelModification($requestData));
        }

        return $this->handleResponse($this->data, '@Hotel/hotel-modification.twig', $this->request->request->get('from_mobile', 0));
    }

    /**
     * This method prepares the hotel reservation modification page
     *
     * @param $reference
     *
     * @return Renders the hotel modification twig
     */
    public function hotelReservationModificationAction($reference)
    {
        $this->data['pageSrc']                 = $this->pageSrc;
        $this->data['hotelDetailsRouteName']   = '_hotel_details';
        $this->data['bookingDetailsRouteName'] = '_booking_details';

        //uglify assets
        $this->data['uglifyHotelReservationModificationAssets'] = 1;

        $from_mobile = $this->request->request->get('from_mobile', 0);

        //Fetch the reservation record to get reservationProcessKey and reservationProcessPassword
        $reservation = $this->reservationRepo->findOneByReference($reference);

        if (empty($reservation)) {
            $this->data['error'] = $this->translator->trans('Hotel reservation not found');
        }

        if (!isset($this->data['error'])) {
            // Compose parameter for the reservationModificationCall
            $params                        = $this->request->request->all();
            $params['reference']           = $reference;
            $params['transactionSourceId'] = $this->transactionSourceId;

            $hotelBC = $this->service->getHotelBookingCriteria($params);

            $resultArray = $this->service->processModificationRequest($this->getHotelServiceConfig(), $hotelBC);
            $this->data  = array_merge($this->data, $resultArray);
        }

        if (!$from_mobile && empty($this->data['error'])) {
            return $this->redirectToLangRoute('_hotel_change_confirmation', array('reference' => $reference), 301);
        }

        if ($from_mobile && !empty($this->data['error'])) {
            $this->data = array('error' => $this->data['error']);
        }

        return $this->handleResponse($this->data, '@Hotel/hotel-reservation-modification.twig', $from_mobile);
    }

    /**
     * This method renders the reservation modification page
     *
     * @param $reference
     *
     * @return
     */
    public function hotelChangeConfirmationAction($reference)
    {
        //uglify assets
        $this->data['uglifyHotelReservationModificationAssets'] = 1;

        $this->data['pageSrc']                 = $this->pageSrc;
        $this->data['reference']               = $reference;
        $this->data['hotelDetailsRouteName']   = '_hotel_details';
        $this->data['bookingDetailsRouteName'] = '_booking_details';

        $resultArray = $this->service->getReservationInformation($reference, 'RESERVATION_CONFIRMATION');
        $resultArray = $this->service->setupCongratsPage($resultArray);

        $this->data = array_merge($this->data, $resultArray);

        return $this->handleResponse($this->data, '@Hotel/hotel-reservation-modification.twig', 0);
    }

    /**
     * This method prepares the hotel cancellation page
     *
     * @param $reference
     *
     * @return
     */
    public function hotelCancellationAction($reference)
    {
        $this->data['pageSrc'] = $this->pageSrc;

        //uglify assets
        $this->data['uglifyHotelCancelAssets'] = 1;

        $cancelRoom = $this->request->request->get('cancelRoom', 0);
        if ($cancelRoom) {
            $cancelReservationKey = $this->request->request->get('reservationKey', 0);

            $this->data = array_merge($this->data, $this->service->hotelRoomCancellation($reference, $cancelReservationKey));
        }

        return $this->handleResponse($this->data, '@Hotel/hotel-cancel.twig', $this->request->request->get('from_mobile', 0));
    }

    /**
     * This method is the actual api call to modify/cancel rooms as needed
     *
     * @param $reference
     *
     * @return
     */
    public function hotelReservationCancellationAction($reference)
    {
        $this->data['pageSrc'] = $this->pageSrc;

        //uglify assets
        $this->data['uglifyHotelCancelConfirmationAssets'] = 1;

        // Compose parameter for the reservationCancellationCall
        $params                        = $this->request->request->all();
        $params['reference']           = $reference;
        $params['transactionSourceId'] = $this->transactionSourceId;
        if (isset($params['reservationKey'])) {
            $params['cancelReservationKey'] = $params['reservationKey'];
            unset($params['reservationKey']);
        }

        $resultArray = $this->service->processCancellationRequest($this->getHotelServiceConfig(), $params);

        $this->data = array_merge($this->data, $resultArray);

        $from_mobile = $this->request->request->get('from_mobile', 0);
        if (!$from_mobile) {
            $this->data['reference']  = $reference;
            $this->data['detailsURL'] = $this->generateLangRoute('_booking_details', array('reference' => $reference));
        }

        return $this->render('@Hotel/hotel-cancel-confirmation.twig', $this->data);
    }

    /**
     * This method renders the hotels-in pages
     *
     * @param $dest
     * @param $srch
     * @param $seotitle
     * @param $seodescription
     * @param $seokeywords
     *
     * @return
     */
    public function hotelsInAllAction($dest, $srch = '', $seotitle, $seodescription, $seokeywords)
    {
        $desti           = $dest.''.$srch;
        $dest_str        = urldecode($desti);
        $dest_str        = str_replace('/', '', $dest_str);
        $destination_str = explode('-', $dest_str);
        $last_record     = count($destination_str) - 1;

        $hoteldest = explode('_', $destination_str[$last_record]);
        $maxPage   = $this->container->getParameter('MAX_RECORD');

        if (isset($hoteldest[2])) {
            $page = $hoteldest[2];
        } else {
            $page = 1;
        }
        $hotelSelectedCity = false;
        if (strtoupper($hoteldest[0]) == "CO" && isset($hoteldest[1]) && $hoteldest[1] != '') {
            $options           = array(
                'country_code' => $hoteldest[1]
            );
            $hotelSelectedCity = $this->service->getHotelSelectedCityId($options);
        } elseif (intval($hoteldest[1]) > 0) {
            $options           = array(
                'id' => intval($hoteldest[1])
            );
            $hotelSelectedCity = $this->service->getHotelSelectedCityId($options);
        }
        if ($page == 1 && (strtoupper($hoteldest[0]) == "C" || strtoupper($hoteldest[0]) == "CO") && $hotelSelectedCity) {
            return $this->hotelMainAction($hotelSelectedCity[0], $seotitle, $seodescription, $seokeywords);
        } else {
            return $this->hotelsInAction($dest, $srch, $seotitle, $seodescription, $seokeywords);
        }
    }

    /**
     * This method renders the hotel-main twig
     *
     * @param  $hotelSelectedCity
     * @param  $seotitle
     * @param  $seodescription
     * @param  $seokeywords
     *
     * @return
     */
    private function hotelMainAction($hotelSelectedCity, $seotitle, $seodescription, $seokeywords)
    {
        $request                   = Request::createFromGlobals();
        $routepath                 = $this->getRoutePath($request);
        $this->data['isindexpage'] = 1;

        $best_hotels_max = 8; // The maximum number of hotels to display in section Best Hotels in ...

        $this->data['hotelblocksearchIndex']  = 1;
        $this->data['hideblocksearchButtons'] = 1;
        $this->data['pageBannerH2']           = $this->translator->trans('Book your Hotel');

        $this->data['pageSrc'] = $src                   = $this->request->query->get('src', '');
        $imagesPath            = $hotelSelectedCity['hc_imagesPath'];
        $locationId            = $hotelSelectedCity['hc_locationId'];
        $cityId                = $hotelSelectedCity['hc_cityId'];
        $link_name             = $stateName             = '';
        $stateCode             = '';
        $latitude              = '';
        $longitude             = '';
        $locationName          = '';
        $countrySearch         = $country               = $hotelSelectedCity['hc_countryCode'];
        $descHotelsin          = $descHotelsinTitle     = '';
        $stateCode             = $hotelSelectedCity['w_stateCode'];

        if ($hotelSelectedCity['w_countryCode'] != '') {
            $country = $hotelSelectedCity['w_countryCode'];
        }

        if ($cityId != 0) {
            $state_array = $this->get('CitiesServices')->worldStateInfo($country, $stateCode);
            if ($state_array) {
                $stateName = $state_array[0]->getStateName();
            }
            $locationName   = $hotelSelectedCity['w_name'];
            $latitude       = $hotelSelectedCity['w_latitude'];
            $longitude      = $hotelSelectedCity['w_longitude'];
            $link_name      = $this->get('app.utils')->htmlEntityDecode($locationName);
            $options_arr    = array(
                'show_main' => 0,
                'limit' => 40,
                'lang' => $this->data['LanguageGet'],
                'city_id' => $cityId
            );
            $thingstodoList = $this->container->get('ThingsToDoServices')->getThingstodoList($options_arr);
            foreach ($thingstodoList as $thingstodoInfo) {
                if (isset($thingstodoInfo['t_descHotelsin']) && $thingstodoInfo['t_descHotelsin']) {
                    if ($thingstodoInfo['ml_descHotelsin'] != '') {
                        $descHotelsin = $thingstodoInfo['ml_descHotelsin'];
                    } else {
                        $descHotelsin = $thingstodoInfo['t_descHotelsin'];
                    }
                    $descHotelsinTitle = $this->translator->trans('Hotels in').' <span>'.$locationName.'</span>';
                    break;
                }
            }
        } elseif ($country != '') {
            $locationName = $hotelSelectedCity['c_name'];
            $link_name    = $this->get('app.utils')->htmlEntityDecode($locationName);
            $latitude     = $hotelSelectedCity['c_latitude'];
            $longitude    = $hotelSelectedCity['c_longitude'];
        }

        $this->data['descHotelsin']      = $descHotelsin;
        $this->data['descHotelsinTitle'] = $descHotelsinTitle;

        $this->setHreflangLinks($this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], $link_name, $cityId, '', $country, 1), true, true);
        $sourcepath                        = 'media/hotels/hotelbooking/hotel-main-banner/';
        $this->data['pageBannerImage']     = $this->get("TTMediaUtils")->createItemThumbs($hotelSelectedCity['hc_image'], $sourcepath, 0, 0, 1920, 878, 'hotels70HS1920878', $sourcepath, $sourcepath, 70);
        $imagesPath                        = $hotelSelectedCity['hc_imagesPath'];
        $selectedlocation                  = $this->get('app.utils')->htmlEntityDecode($hotelSelectedCity['hc_name']);
        $this->data['input']['locationId'] = $locationId;

        $this->data['input']['city'] = [
            'id' => $cityId,
            'name' => $selectedlocation
        ];

        $this->data['input']['entityType'] = $this->container->getParameter('SOCIAL_ENTITY_CITY');
        $this->data['input']['latitude']   = $this->data['latitude']            = $latitude;
        $this->data['input']['longitude']  = $this->data['longitude']           = $longitude;
        $this->data['stars3price']         = $hotelSelectedCity['hc_avgStars3'];
        $this->data['stars4price']         = $hotelSelectedCity['hc_avgStars4'];
        $this->data['stars5price']         = $hotelSelectedCity['hc_avgStars5'];
        $this->data['discovername']        = $selectedlocation;

        $i                             = 0;
        $hotArray1                     = array();
        $hotArray2                     = array();
        $mapArray                      = array();
        $hotelSelectedCityImageRelated = $this->service->getHotelSelectedCityImages($hotelSelectedCity['hc_id']);

        foreach ($hotelSelectedCityImageRelated as $value) {
            $items            = array();
            $itemsmap         = array();
            $hotelId          = $value['hc_hotelId'];
            $items['name']    = $this->get('app.utils')->htmlEntityDecode($value['h_name']);
            $items['namealt'] = $this->get('app.utils')->cleanTitleDataAlt($value['h_name']);
            $items['stars']   = intval($value['h_stars']);
            $items['price']   = $value['hc_avgPrice'];
            $items['link']    = $this->get('TTRouteUtils')->returnHotelDetailedLink($this->data['LanguageGet'], $value['h_name'], $hotelId); // , $minPriceOfferVars);
            if ($items['stars'] < 1) {
                $items['stars'] = 1;
            } elseif ($items['stars'] > 6) {
                $items['stars'] = 6;
            }
            $items['address'] = $this->service->getAddress($value);
            $sourcepath       = 'media/hotels/hotelbooking/hotel-main-banner/'.$imagesPath;
            $items['img']     = $this->get("TTMediaUtils")->createItemThumbs($value['hc_image'], $sourcepath, 0, 0, 284, 162, 'hotels85HS284162', $sourcepath, $sourcepath, 70);
            if ($i < $best_hotels_max) {
                $hotArray1[] = $items;
            } else {
                $items['img'] = $this->get("TTMediaUtils")->createItemThumbs($value['hc_image'], $sourcepath, 0, 0, 156, 89, 'hotels85HS15689', $sourcepath, $sourcepath, 70);
                $hotArray2[]  = $items;
            }
            $itemsmap['id']          = $hotelId;
            $itemsmap['stars']       = $items['stars'];
            $itemsmap['image_pic']   = $this->get("TTMediaUtils")->createItemThumbs($value['hc_image'], $sourcepath, 0, 0, 72, 39, 'hotels85HS7239', $sourcepath, $sourcepath, 70);
            $itemsmap['link']        = $items['link'];
            $itemsmap['entity_type'] = $this->container->getParameter('SOCIAL_ENTITY_HOTEL');
            $itemsmap['entityId']    = $hotelId;
            $itemsmap['name']        = addslashes($items['name']);
            $itemsmap['latitude']    = $value['h_latitude'];
            $itemsmap['logitude']    = $value['h_longitude'];
            $mapArray[]              = $itemsmap;
            $i++;
        }

        $this->data['hotArray1'] = $hotArray1;
        $this->data['hotArray2'] = $hotArray2;
        $this->data['mapArray']  = $mapArray;
        $srch_option             = array(
            'term' => '',
            'locationId' => $locationId,
            'cityId' => $cityId,
            'countryCode' => $country,
            'sortBy' => 'stars',
            'from' => 1,
            'limit' => 4,
            'oldQuery' => 1,
            'sortbyOrder' => 'desc'
        );
        $url_source              = 'hotelMainAction - getHotelSearch - URL: '.$routepath;
        $cty_array1              = $this->get('ElasticServices')->getHotelSearchData($srch_option, $url_source);

        $this->data['allCityHotelsCount'] = 0;

        if ($cty_array1[1] > $best_hotels_max) {
            $this->data['allCityHotelsCount'] = $cty_array1[1];

            $this->data['allCityHotelsCountString'] = vsprintf($this->translator->trans('see all %s hotels', array(), 'messages'), array($this->data['allCityHotelsCount']));
        }

        $action_array                        = array();
        $action_array_alt                    = array();
        $action_array[]                      = '<span>'.$selectedlocation.'</span>';
        $action_array_alt[]                  = $selectedlocation;
        $this->data['discoverBestHotels']    = vsprintf($this->translator->trans('Discover the best hotels in %s', array(), 'messages'), $action_array);
        $this->data['discoverBestHotelsalt'] = vsprintf($this->translator->trans('Discover the best hotels in %s', array(), 'messages'), $action_array_alt);
        $this->data['placesWeLove']          = vsprintf($this->translator->trans('Places We Love in %s', array(), 'messages'), $action_array);
        $this->data['perfectDeal']           = vsprintf($this->translator->trans('Find the perfect Deal in %s', array(), 'messages'), $action_array);
        $action_array                        = array();
        $action_array[]                      = '<span>'.$selectedlocation.'?</span>';
        $this->data['lookingForDeal']        = vsprintf($this->translator->trans('Looking for deals in %s', array(), 'messages'), $action_array);

        $action_array     = array();
        $action_array_alt = array();
        if ($cityId != 0) {
            $action_array[]     = '<span>'.$selectedlocation.', '.$this->get('app.utils')->htmlEntityDecode($hotelSelectedCity['c_name']).'</span>';
            $action_array_alt[] = $selectedlocation.', '.$this->get('app.utils')->htmlEntityDecode($hotelSelectedCity['c_name']);
        } else {
            $action_array[]     = '<span>'.$selectedlocation.'</span>';
            $action_array_alt[] = $selectedlocation;
        }
        $this->data['bestHotels']    = vsprintf($this->translator->trans('Best Hotels in %s', array(), 'messages'), $action_array);
        $this->data['bestHotelsalt'] = $this->get('app.utils')->cleanTitleDataAlt(vsprintf($this->translator->trans('Best Hotels in %s', array(), 'messages'), $action_array_alt));

        $locationSearchId             = (!empty($src)) ? $cityId : $locationId;
        $action_array                 = array();
        $action_array[]               = '<br>';
        $this->data['PRICEGUARANTEE'] = vsprintf($this->translator->trans('PRICE%sGUARANTEE', array(), 'messages'), $action_array);
        $this->data['bestHotelsLink'] = $this->get('TTRouteUtils')->returnBookinSearchResultLink($this->data['LanguageGet'], $selectedlocation, $countrySearch, $locationSearchId, 0, '', '', '', '', 0, '', $src);
        $this->data['tonightLink']    = $this->get('TTRouteUtils')->returnBookinSearchResultLink($this->data['LanguageGet'], $selectedlocation, $countrySearch, $locationSearchId, 0, '', '', '', '', 0, 'tonight', $src);
        $this->data['tomorrowLink']   = $this->get('TTRouteUtils')->returnBookinSearchResultLink($this->data['LanguageGet'], $selectedlocation, $countrySearch, $locationSearchId, 0, '', '', '', '', 0, 'tomorrow', $src);
        $this->data['todaydata']      = date('M d');
        $this->data['tomorrowdata']   = date('M d', strtotime('tomorrow'));

        $fromDC1 = date('Y-m-d', strtotime('next Saturday'));
        $fromDC  = date('Y-m-d', strtotime('-1 day', strtotime($fromDC1)));
        $toDC    = date('Y-m-d', strtotime('+2 day', strtotime($fromDC)));

        $this->data['weekendLink']  = $this->get('TTRouteUtils')->returnBookinSearchResultLink($this->data['LanguageGet'], $selectedlocation, $countrySearch, $locationSearchId, 0, '', '', '', '', 0, 'weekend', $src);
        $this->data['weekenddata1'] = date('M d', strtotime($fromDC));
        $this->data['weekenddata2'] = date('M d', strtotime($toDC));

        $this->data['stars3Link'] = $this->get('TTRouteUtils')->returnBookinSearchResultLink($this->data['LanguageGet'], $selectedlocation, $countrySearch, $locationSearchId, 0, '', '', '', '', 3, '', $src);
        $this->data['stars4Link'] = $this->get('TTRouteUtils')->returnBookinSearchResultLink($this->data['LanguageGet'], $selectedlocation, $countrySearch, $locationSearchId, 0, '', '', '', '', 4, '', $src);
        $this->data['stars5Link'] = $this->get('TTRouteUtils')->returnBookinSearchResultLink($this->data['LanguageGet'], $selectedlocation, $countrySearch, $locationSearchId, 0, '', '', '', '', 5, '', $src);
        $page                     = 1;
        if ($this->data['aliasseo'] == '') {
            $realnameseoTitle = $seo_name         = $selectedlocation;
            if ($stateName != '') {
                $seo_name         .= ', '.$stateName;
                $realnameseoTitle = $seo_name;
            }
            if (strlen($realnameseoTitle) > 22) {
                $realnameseoTitle = substr($realnameseoTitle, 0, 22);
            }
            $seo_name               .= ' '.$country;
            $realnameseoTitle       .= ' '.$country;
            $action_array           = array();
            $action_array[]         = $realnameseoTitle;
            $action_text_display    = vsprintf($this->translator->trans(/** @Ignore */ $seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array                 = array();
            $action_array[]               = $seo_name;
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */ $seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array              = array();
            $action_array[]            = $seo_name;
            $action_text_display       = vsprintf($this->translator->trans(/** @Ignore */ $seokeywords, array(), 'seo'), $action_array);
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }
        $this->data['pageSrc'] = $this->container->getParameter('hotels')['page_src']['hrs'];

        return $this->render('@Hotel/hotel-main.twig', $this->data);
    }

    /**
     * This method prepares and renders the 360 full tour twig
     *
     * @param  $name
     * @param  $id
     *
     * @return Rendered twig (photos-360.twig)
     */
    public function view360FullTourAction($name, $id)
    {
        // If name is empty in URL, both {name} and {id} become considered as one variable inside {name}, fetch name and id correctly
        if (!$id) {
            list($name, $id) = explode('-', $name);
        }

        $results = $this->service->getHotelDivisions($id, null, null, false, true);

        $hotelData = json_decode($results);

        $menu360 = array();

        $mainData  = $hotelData->data;
        $divisions = $mainData->divisions;

        foreach ($divisions as $data) {
            $divId    = $data->id;
            $subDivId = null;

            if (isset($data->parent_id) && $data->parent_id != "") {
                $subDivId = $divId;
                $divId    = $data->parent_id;
            }

            $menu360[] = array(
                'name' => $data->name,
                'type' => 'hotels',
                'entityName' => $mainData->name,
                'country' => $mainData->country_code,
                'entityId' => $mainData->id,
                'divisionId' => $divId,
                'groupId' => $data->group_id,
                'groupName' => $data->group_name,
                'catgId' => $data->category_id,
                'subDivisionId' => $subDivId,
                'data_icon' => false
            );
        }

        $this->data['menu360'] = $menu360;

        $homeTT               = array();
        $homeTT[]             = array('name' => '', 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/images/360-photos/en-logo256.png'), 'link' => '/', 'title' => 'Tourist Tube');
        $this->data['homeTT'] = $homeTT;

        // Get related Things-To-Do
        $hotelThingsToDo = $this->service->getHotel360ThingsToDo($id);

        $menuTT = array();

        if ($hotelThingsToDo) {
            $title    = $hotelThingsToDo[0]['title'];
            $link     = str_replace(' ', '-', $title);
            $menuTT[] = array('name' => "$title", 'img' => '', 'link' => "/$link", 'title' => "$title");
        }

        $this->data['menuTT'] = $menuTT;

        // Page title
        $this->data['pageTitle'] = "360-photos ".$name;

        // Get hotel logo
        $media_360_base_path = $this->container->getParameter('MEDIA_HOTELS_LOGOS_BASE_PATH');
        $logo_path           = strtolower($mainData->country_code)."/";
        $this->data['logo']  = ($mainData->logo) ? $media_360_base_path.$logo_path.$mainData->logo : '';

        return $this->render('media_360/photos-360.twig', $this->data);
    }
}
