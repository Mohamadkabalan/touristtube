<?php

namespace HotelBundle\Services;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use HotelBundle\Model\Hotel;
use HotelBundle\Model\HotelSC;
use HotelBundle\Model\HotelAvailability;
use HotelBundle\Model\HotelBooking;
use HotelBundle\Model\HotelBookingOrderer;
use HotelBundle\Model\HotelBookingCriteria;
use HotelBundle\Model\HotelBookingSC;
use HotelBundle\Model\HotelApiResponse;
use HotelBundle\Model\HotelRoom;
use HotelBundle\Model\HotelServiceConfig;
use HotelBundle\vendors\HRS\v46\HRSHandler;

class HRSServices
{
    private $em;
    private $logger;
    private $isRest                 = false;
    private $siteLanguage           = null;
    private $userId                 = 0;
    private $convertCurrency        = false;
    private $forBookForm            = false;
    private $selectedCurrency;
    private $useHotelPrice          = false;
    private $transactionSourceId;
    private $validTransactionSource = array();
    private $isPreview360           = false;

    /**
     * The class construct
     *
     * @param Utils $utils
     * @param ContainerInterface $container
     * @param EntityManager $em
     *
     * @return
     */
    public function __construct(Utils $utils, ContainerInterface $container, EntityManager $em)
    {
        global $GLOBAL_LANG;

        $this->utils     = $utils;
        $this->container = $container;
        $this->em        = $em;

        $this->currencyService = $this->container->get('CurrencyService');
        $this->emailServices   = $this->container->get('EmailServices');
        $this->logger          = $this->container->get('HotelLogger');
        $this->templating      = $this->container->get('templating');
        $this->translator      = $this->container->get('translator');

        $this->hotelRepo          = $this->em->getRepository('HotelBundle:CmsHotel');
        $this->hsRepo             = $this->em->getRepository('HotelBundle:CmsHotelSource');
        $this->hotelCityRepo      = $this->em->getRepository('HotelBundle:CmsHotelCity');
        $this->imageRepo          = $this->em->getRepository('HotelBundle:CmsHotelImage');
        $this->errorRepo          = $this->em->getRepository('HotelBundle:ErrorMessages');
        $this->reservationRepo    = $this->em->getRepository('HotelBundle:HotelReservation');
        $this->roomRepo           = $this->em->getRepository('HotelBundle:HotelRoomReservation');
        $this->searchRequestRepo  = $this->em->getRepository('HotelBundle:HotelSearchRequest');
        $this->searchResponseRepo = $this->em->getRepository('HotelBundle:HotelSearchResponse');

        $this->hotelSelectedCityRepo      = $this->em->getRepository('HotelBundle:HotelSelectedCity');
        $this->hotelSelectedCityImageRepo = $this->em->getRepository('HotelBundle:HotelSelectedCityImage');

        $this->hrs = new HRSHandler($utils, $container);

        $this->validTransactionSource['web'] = $this->container->getParameter('WEB_REFERRER');

        // set language
        $this->siteLanguage = (!isset($GLOBAL_LANG) || !$GLOBAL_LANG) ? 'en' : $GLOBAL_LANG;

        // set currency
        $selectedCurrency       = filter_input(INPUT_COOKIE, 'currency');
        $this->selectedCurrency = (empty($selectedCurrency)) ? $this->getAPICurrency() : $selectedCurrency;

        // set userId
        $loggedInUserInfo = $this->container->get('ApiUserServices')->tt_global_get('userInfo');
        if ($loggedInUserInfo) {
            $this->userId = intval($loggedInUserInfo['id']);
        } else {
            $loggedInUserInfo = $this->container->get('security.token_storage')->getToken()->getUser();
            if (is_object($loggedInUserInfo)) {
                $this->userId = $loggedInUserInfo->getId();
            }
        }
    }

    /**
     * This method sets the necessary flags and data for the service
     *
     * @param HotelServiceConfig $serviceConfig
     *
     * @return
     */
    public function initializeService(HotelServiceConfig $serviceConfig)
    {
        $this->transactionSourceId = $serviceConfig->getTransactionSourceId();
        $this->isRest              = $serviceConfig->getIsRest();
        $this->isPreview360        = $serviceConfig->isPreview360();

        if (in_array($serviceConfig->getPage(), array('BOOKING_DETAILS'))) {
            $this->useHotelPrice = true;
        }
    }

    //*****************************************************************************************
    // Pre-Booking Helper Functions
    /**
     * This method returns the default API currency
     *
     * @return
     */
    private function getAPICurrency()
    {
        return $this->container->getParameter('modules')['hotels']['vendors']['hrs']['default_currency'];
    }

    /**
     * This method return the default search criteria values
     *
     * @return Array The default search data.
     */
    public function getDefaultSearchValues()
    {
        return array(
            'hotelSearchRequestId' => 0,
            'hotelCityName' => '',
            'hotelId' => 0,
            'singleRooms' => 0,
            'doubleRooms' => 1,
            'adultCount' => 2,
            'childCount' => 0,
            'page' => 1,
            'sortBy' => '',
            'sortOrder' => '',
            'nbrStars' => '',
            'district' => '',
            'priceRange' => '',
            'distanceRange' => '',
            'newAjaxSearch' => '1',
            'country' => '',
            'longitude' => 0,
            'latitude' => 0,
            'entityType' => 0,
            'fromDate' => null,
            'toDate' => null,
            'locationId' => 0
        );
    }

    //*****************************************************************************************
    // Avail Functions
    /**
     * get Hotel Search Criteria
     *
     * @param $criteria
     *
     * @return
     */
    public function getHotelSearchCriteria($criteria)
    {
        $hotelSC = new HotelSC();

        if (!empty($criteria)) {
            $hotelSC->setHotelSearchRequestId(isset($criteria['hotelSearchRequestId']) ? $criteria['hotelSearchRequestId'] : 0);
            if (isset($criteria['city']) && is_array($criteria['city'])) {
                $hotelSC->getCity()->setName(isset($criteria['city']['name']) ? $criteria['city']['name'] : '');
                $hotelSC->getCity()->setId(isset($criteria['city']['id']) ? $criteria['city']['id'] : 0);
            } else {
                $hotelSC->getCity()->setName(isset($criteria['hotelCityName']) ? $criteria['hotelCityName'] : '');
                $hotelSC->getCity()->setId(isset($criteria['cityId']) ? $criteria['cityId'] : 0);
            }

            $hotelSC->setHotelKey(isset($criteria['hotelKey']) ? $criteria['hotelKey'] : 0);
            $hotelSC->setHotelCode(isset($criteria['hotelCode']) ? $criteria['hotelCode'] : 0);
            $hotelSC->setHotelId(isset($criteria['hotelId']) ? $criteria['hotelId'] : 0);
            $hotelSC->setLocationId(isset($criteria['locationId']) ? $criteria['locationId'] : 0);
            $hotelSC->setHotelName(isset($criteria['hotelName']) ? $criteria['hotelName'] : '');

            $hotelSC->setCountry(isset($criteria['country']) ? $criteria['country'] : '');
            $hotelSC->getIso3CountryCode(isset($criteria['countryCode']) ? $criteria['countryCode'] : '');
            if (empty($hotelSC->getIso3CountryCode()) && !empty($hotelSC->getCountry())) {
                $iso3CountryCode = $this->em->getRepository('TTBundle:CmsCountries')->getIso3CountryByCode($hotelSC->getCountry());
                $hotelSC->setIso3CountryCode($iso3CountryCode);
            }

            $hotelSC->setLongitude(isset($criteria['longitude']) ? $criteria['longitude'] : 0);
            $hotelSC->setLatitude(isset($criteria['latitude']) ? $criteria['latitude'] : 0);

            $hotelSC->setFromDate(isset($criteria['fromDate']) ? $criteria['fromDate'] : null);
            $hotelSC->setToDate(isset($criteria['toDate']) ? $criteria['toDate'] : null);

            if (isset($criteria['roomCriteria']) && !empty($criteria['roomCriteria'])) {
                // childCount to be implemented.
                $adultCount       = 0;
                $childCount       = 0;
                $doubleRoomsCount = 0;
                $singleRoomsCount = 0;

                foreach ($criteria['roomCriteria'] as $room) {
                    $adultCount += $room['adultCount'];
                    switch ($room['adultCount']) {
                        case 1:
                            $singleRoomsCount++;
                            break;
                        default:
                            $doubleRoomsCount++;
                            if (isset($room['childAccommodationCriteria']) && !empty($room['childAccommodationCriteria'])) {
                                $criteria['childAge'] = [];
                                $criteria['childBed'] = [];
                                foreach ($room['childAccommodationCriteria'] as $child) {
                                    $childCount++;
                                    $criteria['childAge'][$childCount] = $child['childAge'];
                                    $criteria['childBed'][$childCount] = $child['childAccommodation'];
                                }
                            }
                    }
                }

                $criteria['singleRooms'] = $singleRoomsCount;
                $criteria['doubleRooms'] = $doubleRoomsCount;
                $criteria['adultCount']  = $adultCount;
                $criteria['childCount']  = $childCount;
            }

            $hotelSC->setSingleRooms(isset($criteria['singleRooms']) ? $criteria['singleRooms'] : 0);
            $hotelSC->setDoubleRooms(isset($criteria['doubleRooms']) ? $criteria['doubleRooms'] : 1);
            $hotelSC->setAdultCount(isset($criteria['adultCount']) ? $criteria['adultCount'] : 2);
            $hotelSC->setChildCount(isset($criteria['childCount']) ? $criteria['childCount'] : 0);

            $childAge = (isset($criteria['childAge']) && !empty($criteria['childAge'])) ? $criteria['childAge'] : array();
            $childBed = (isset($criteria['childBed']) && !empty($criteria['childBed'])) ? $criteria['childBed'] : array();
            if (!is_array($childAge)) {
                $childAge = json_decode($childAge, true);
            }
            if (!is_array($childBed)) {
                $childBed = json_decode($childBed, true);
            }

            $hotelSC->setChildAge($childAge);
            $hotelSC->setChildBed($childBed);

            $hotelSC->setPage(isset($criteria['page']) ? $criteria['page'] : 1);
            $hotelSC->setLimit($this->container->getParameter('hotels')['search_results_per_page']);
            $hotelSC->setSortBy(isset($criteria['sortBy']) ? $criteria['sortBy'] : '');
            $hotelSC->setSortOrder(isset($criteria['sortOrder']) ? $criteria['sortOrder'] : '');
            $hotelSC->setNbrStars(!empty($criteria['nbrStars']) ? $criteria['nbrStars'] : array());

            $hotelSC->setDistrict(isset($criteria['district']) ? $criteria['district'] : array());
            $hotelSC->setDistanceRange(isset($criteria['distanceRange']) ? $criteria['distanceRange'] : '');
            $hotelSC->setDistance($this->container->getParameter('hotels')['radius_distance']); //Max is 300 KM
            $hotelSC->setMaxDistance(isset($criteria['maxDistance']) ? $criteria['maxDistance'] : 0);
            $hotelSC->setBudgetRange(isset($criteria['budgetRange']) ? $criteria['budgetRange'] : array());
            $hotelSC->setPriceRange(isset($criteria['priceRange']) ? $criteria['priceRange'] : array());
            $hotelSC->setMaxPrice(isset($criteria['maxPrice']) ? $criteria['maxPrice'] : 0);

            if (isset($criteria['selectedCurrency']) && !empty($criteria['selectedCurrency'])) {
                $this->selectedCurrency = $criteria['selectedCurrency'];
            }
            $hotelSC->setCurrency($this->selectedCurrency);

            $hotelSC->setEntityType(isset($criteria['entityType']) ? $criteria['entityType'] : 0);
            if ($hotelSC->getEntityType() != $this->container->getParameter('SOCIAL_ENTITY_CITY') && $hotelSC->getEntityType() != $this->container->getParameter('SOCIAL_ENTITY_HOTEL')) {
                $hotelSC->setGeoLocationSearch(true);
            }

            $hotelSC->setIsCancelable(isset($criteria['isCancelable']) ? intval($criteria['isCancelable']) : 0);
            $hotelSC->setHasBreakfast(isset($criteria['hasBreakfast']) ? intval($criteria['hasBreakfast']) : 0);
            $hotelSC->setHas360(isset($criteria['has360']) ? intval($criteria['has360']) : 0);

            $hotelSC->setPerimeter(isset($criteria['perimeter']) ? intval($criteria['perimeter']) : 0);
            $hotelSC->setFromMobile(isset($criteria['from_mobile']) ? intval($criteria['from_mobile']) : 0);
            $hotelSC->setRefererURL(isset($criteria['refererURL']) ? intval($criteria['refererURL']) : '');
        }

        return $hotelSC;
    }

    /**
     * hotels Availability
     *
     * @param  HotelServiceConfig $serviceConfig
     * @param  HotelSC            $hotelSC
     * @param  boolean            $curl
     *
     * @return
     */
    public function hotelsAvailability(HotelServiceConfig $serviceConfig, HotelSC $hotelSC, $curl = false)
    {
        $this->initializeService($serviceConfig);

        if (!empty($hotelSC->getHotelId())) {
            // Get locationId and hotelKey from TT if hotel name is queried directly
            $locationId = $this->hsRepo->getHotelSourceField('locationId', array('hotelId', $hotelSC->getHotelId()));
            $hotelKey   = $this->hsRepo->getHotelSourceField('sourceId', array('hotelId', $hotelSC->getHotelId()));
            $source     = $this->hsRepo->getHotelSourceField('source', array('hotelId', $hotelSC->getHotelId()));

            $hotelSC->setLocationId($locationId);
            $hotelSC->setHotelKey($hotelKey);
            $hotelSC->setHotelSource($source);
        }

        // Convert prices from any user selected currency to API currency since all price info saved to database are in API currency
        $apiCurrency = $this->getAPICurrency();

        if ($hotelSC->getCurrency() != $apiCurrency) {
            $conversionRate = $this->currencyService->getConversionRate($apiCurrency, $hotelSC->getCurrency());
        }

        if (!empty($hotelSC->getPriceRange()) && isset($conversionRate)) {
            $filterConversionRate = $this->currencyService->getConversionRate($hotelSC->getCurrency(), $apiCurrency);
            $priceRange           = $hotelSC->getPriceRange();

            if (isset($priceRange[0])) {
                if ($priceRange[0] > 0) {
                    $priceRange[0] = floor($this->currencyService->currencyConvert($priceRange[0], $filterConversionRate));
                }
            }

            if (isset($priceRange[1])) {
                $priceRange[1] = ceil($this->currencyService->currencyConvert($priceRange[1], $filterConversionRate));
            }

            $hotelSC->setPriceRange($priceRange);
        }

        // this should be an odd number so our pagination control will work
        $maxPageToDisplay = $this->container->getParameter('hotels')['max_pages_to_display_in_pagination'];
        $resultArray      = array();

        $resultArray['selectedPage']  = $hotelSC->getPage();
        $resultArray['newAjaxSearch'] = intval($hotelSC->isNewSearch());

        $availability = new HotelAvailability();
        if (!empty($hotelSC->getLocationId()) || !empty($hotelSC->getHotelId()) ||
            !empty($hotelSC->getCountry()) || !empty($hotelSC->getLongitude()) ||
            !empty($hotelSC->getLatitude())) {
            if (empty($hotelSC->getFromDate()) && empty($hotelSC->getToDate())) {
                $nightsCount  = 0;
                $availability = $this->getBestHotels($hotelSC);
            } else {
                $nightsCount = $this->utils->computeNights($hotelSC->getFromDate(), $hotelSC->getToDate());

                // Force API call for every search, except when filtering / sorting / paginating
                if ($hotelSC->isNewSearch()) {
                    $searchRequest = null;
                } else {
                    $searchRequest = $this->searchRequestRepo->getHotelSearchRequest($hotelSC);
                    if (!empty($searchRequest)) {
                        $availability = $this->getCachedHotelSearch($searchRequest, $hotelSC);
                    }
                }

                if (empty($searchRequest)) {
                    $hotelSC->setPage(1);

                    $hsrData                  = $hotelSC->toArray();
                    $hsrData['hotelCityCode'] = $hsrData['city']['code'];
                    $hsrData['hotelCityName'] = $hsrData['city']['name'];

                    // Save the search request
                    $searchRequest = $this->searchRequestRepo->insertHotelSearchRequest($hsrData);

                    if (!empty($searchRequest)) {
                        $hotelSC->setHotelSearchRequestId($searchRequest->getId());

                        // hrs api will always return no results when searching tt sourced hotels;
                        // on any case, we will still show it on the result list
                        if (!empty($hotelSC->getHotelId()) && $hotelSC->getHotelSource() == 'tt') {
                            $availability = $this->getCustomTTSourcedHotel($hotelSC);
                        } else {
                            // we get list of available hotels through HRS api hotelAvail
                            $availability = $this->getAvailableHotelsFromAPI($hotelSC);
                        }

                        if (!$availability->hasAvailableHotels()) {
                            $this->searchRequestRepo->deleteHotelSearchRequest($searchRequest->getId());
                        } else {
                            // update the general data for the search
                            $searchRequest->setMaxPrice(floatval($hotelSC->getMaxPrice()));
                            $searchRequest->setMaxDistance(floatval($hotelSC->getMaxDistance()));

                            $this->em->persist($searchRequest);
                            $this->em->flush();

                            // apply filter(s) on our results if provided
                            if (!$resultArray['newAjaxSearch']) {
                                $availability = $this->getCachedHotelSearch($searchRequest, $hotelSC);
                            }
                        }
                    }
                }
            }

            if (isset($conversionRate)) {
                $convertedHotels = array();
                foreach ($availability->getAvailableHotels() as &$hotel) {
                    $hotel['isoCurrency'] = $this->selectedCurrency;
                    $hotel['price']       = $this->currencyService->currencyConvert($hotel['price'], $conversionRate);

                    if ($this->isRest) {
                        $hotel['price']    = round($hotel['price'], 2);
                        $hotel['avgPrice'] = round($hotel['avgPrice'], 2);
                    }

                    $convertedHotels[] = $hotel;
                }
                $availability->setAvailableHotels($convertedHotels);

                $maxPrice = $this->currencyService->currencyConvert($hotelSC->getMaxPrice(), $conversionRate);
                $hotelSC->setMaxPrice($maxPrice);
            }

            if ($availability->hasAvailableHotels()) {
                if ($this->isRest) {
                    $resultArray                = array();
                    $resultArray['hotels']      = $availability->getAvailableHotels();
                    $resultArray['nightsCount'] = $nightsCount;
                    //$resultArray['entity_type'] = $this->container->getParameter('SOCIAL_ENTITY_HOTEL');
                } else {
                    $resultArray['maxPrice']         = $hotelSC->getMaxPrice();
                    $resultArray['hotelCount']       = $availability->getHotelCount();
                    $resultArray['maxDistance']      = $hotelSC->getMaxDistance();
                    $resultArray['nightsCount']      = $nightsCount;
                    $resultArray['maxPageToDisplay'] = $maxPageToDisplay;
                    $resultArray['isUserLoggedIn']   = $this->container->get('ApiUserServices')->isUserLoggedIn();
                    $resultArray['pageCount']        = ceil($availability->getHotelCount() / $hotelSC->getLimit());

                    $twigData                  = $resultArray;
                    $twigData['LanguageGet']   = $this->siteLanguage;
                    $twigData['hotels']        = $availability->getAvailableHotels();
                    $twigData['input']         = $hotelSC->toArray();
                    $twigData['entity_type']   = $this->container->getParameter('SOCIAL_ENTITY_HOTEL');
                    $twigData['pageSrc']       = $serviceConfig->getPageSrc();
                    $resultArray['mainLoop']   = $this->templating->render($serviceConfig->getTemplate('mainLoopTemplate'), $twigData);
                    $resultArray['pagination'] = $this->templating->render($serviceConfig->getTemplate('paginationTemplate'), $twigData);
                }
            }
        }

        if ($availability->hasError()) {
            $resultArray = ['code' => 400, 'error' => $availability->getStatus()->getErrorMessage()];
        } elseif (!$availability->hasAvailableHotels()) {
            $resultArray = ['code' => 204, 'error' => $this->getErrorMessage(array('code' => 'HOTEL_7'))];
        }

        return $this->utils->createJSONResponse($resultArray);
    }

    /**
     * Finalize data taken from db cache (hotel_search_response) to be displayed in search results and insert no-offer hotels on 4th and 14th row
     *
     * @param  $dbAvailableHotels
     * @param  $hotels
     * @param  $noOfferHotels
     * @param  $ctr
     * @param  $limit
     * @param  $hotelSC
     *
     * @return: Array of hotels with data for display in search results page
     */
    private function formatCachedHotelSearch($dbAvailableHotels, &$hotels, &$noOfferHotels, &$ctr, &$limit, HotelSC &$hotelSC)
    {
        foreach ($dbAvailableHotels as $hotel) {
            // $ctr will ensure that no offer hotels are placed on 4th and 14th row, the + 2 is counting the no offers itself
            if ($ctr > ($hotelSC->getLimit() + 2)) {
                $ctr = 1;
            }

            // For marketing purposes, we'll insert the no-offer hotels on 4th and 14th row
            if (!empty($noOfferHotels) && ($ctr == 4 || $ctr == 14)) {
                $noOfferHotel = array_shift($noOfferHotels);
                $hotels[]     = $this->getHotelTeaserData($noOfferHotel, $hotelSC);
                $ctr++;
                $limit++;
            }

            $hotel['distance']  = number_format($hotel['distance'] / 1000, 2);
            $hotel['distances'] = json_decode($hotel['distances'], true);

            $hotelSource          = (isset($hotelInfo['source'])) ? $hotelInfo['source'] : $this->hsRepo->getHotelSourceField('source', ['hotelId', $hotel['hotelId']]);
            $hotel['hotelSource'] = ($hotelSource) ? strtolower($hotelSource) : '';

            if ($this->isRest) {
                $hotel['mainImage']           = $hotel['mainImageMobile'];
                $hotel['hotelNameCleanTitle'] = $hotel['hotelNameURL'];
                $hotel['country']             = $hotel['country'];

                unset($hotel['hotelNameURL'], $hotel['hotelSource']);
            }
            unset($hotel['mainImageMobile'], $hotel['distance'], $hotel['hotelCode']);

            $hotel['has360'] = boolval($hotel['has360']);

            $hotels[] = $hotel;
            $ctr++;
        }

        return $hotels;
    }

    /**
     * This method calls the HRS hotelAvail API and parses the response for hotel listing.
     *
     * @param  HotelSC &$hotelSC
     *
     * @return HotelAvailability
     */
    private function getAvailableHotelsFromAPI(HotelSC &$hotelSC)
    {
        $toreturn = new HotelAvailability();

        $hotelCount = 0;
        $hotels     = array();

        $results = $this->hrs->hotelAvail($hotelSC, true, $this->userId);

        if ($results->hasError()) {
            $toreturn->getStatus()->setError($results->getStatus()->getError());
        } else {
            if ($results->hasAvailableHotels()) {
                $apiHotels = $results->getAvailableHotels();

                // For all hotels returned by API, let's get its details from our database
                $dbAvailableHotels = $this->hsRepo->getHotelBySourceIdentifier(array_keys($apiHotels), $hotelSC->getHotelKey());
                $hotelCount        = count($dbAvailableHotels);

                // For display, we'll insert no offers betweeen the available hotels
                $hotels = $this->getDisplayableHotels($dbAvailableHotels, $hotelSC, true, $apiHotels);
            }
        }

        $toreturn->setHotelCount($hotelCount);
        $toreturn->setAvailableHotels($hotels);

        return $toreturn;
    }

    /**
     * get Custom TT Sourced Hotel
     *
     * @param  HotelSC &$hotelSC
     *
     * @return
     */
    private function getCustomTTSourcedHotel(HotelSC &$hotelSC)
    {
        $toreturn = new HotelAvailability();

        $hotelCount = 0;
        $hotels     = array();

        // For all hotels returned by API, let's get its details from our database
        $customTTSourcedHotel = $this->hsRepo->getHotelBySourceIdentifier(-1, -1, $hotelSC->getHotelId(), 1);
        if ($customTTSourcedHotel) {
            $hotels[] = $this->getHotelTeaserData($customTTSourcedHotel, $hotelSC, true);
        }

        $toreturn->setHotelCount(count($hotels));
        $toreturn->setAvailableHotels($hotels);

        return $toreturn;
    }

    /**
     * get Best Hotels
     *
     * @param  HotelSC &$hotelSC
     *
     * @return
     */
    private function getBestHotels(HotelSC &$hotelSC)
    {
        $toreturn = new HotelAvailability();

        $hotels = array();

        $dbAvailableHotels = $this->hsRepo->getHotelBySearchCriteria($hotelSC);
        foreach ($dbAvailableHotels as $hotel) {
            $hotel             = $this->getHotelTeaserData($hotel, $hotelSC);
            $hotel['priceTxt'] = '<img title="'.$this->translator->trans('Enter your check-in and check-out dates to view the hotel offers').'" src="'.$this->container->get("TTRouteUtils")->generateMediaURL('/media/images/dolars.png').'" class="margintopminus11" alt="Currency">';
            $hotels[]          = $hotel;
        }

        $hotelCount  = $this->hsRepo->getHotelBySearchCriteria($hotelSC, 'count');
        $maxDistance = $this->hsRepo->getHotelBySearchCriteria($hotelSC, 'maxDistance');

        $hotelSC->setMaxDistance(number_format($maxDistance / 1000, 2));

        $toreturn->setHotelCount($hotelCount);
        $toreturn->setAvailableHotels($hotels);

        return $toreturn;
    }

    /**
     * get Cached Hotel Search
     *
     * @param  [type]  &$searchRequest
     * @param  HotelSC &$hotelSC
     *
     * @return
     */
    private function getCachedHotelSearch(&$searchRequest, HotelSC &$hotelSC)
    {
        $toreturn = new HotelAvailability();

        $hotelCount = 0;
        $hotels     = array();

        if ($this->searchResponseRepo->getHotelSearchResponseByRequestIdOnly($searchRequest->getId(), true) > 0) {
            $hotelSC->setHotelSearchRequestId($searchRequest->getId());
            $hotelSC->setMaxPrice($searchRequest->getMaxPrice());
            $hotelSC->setMaxDistance($searchRequest->getMaxDistance());

            $hotelCount        = $this->searchResponseRepo->getHotelSearchResponse($hotelSC, 'count(res.id)');
            $dbAvailableHotels = $this->searchResponseRepo->getHotelSearchResponse($hotelSC);

            $hotels = $this->getDisplayableHotels($dbAvailableHotels, $hotelSC);
        } else {
            $searchRequest = null;
        }

        $toreturn->setHotelCount($hotelCount);
        $toreturn->setAvailableHotels($hotels);

        return $toreturn;
    }

    /**
     * get Displayable Hotels
     *
     * @param  [type]  $dbAvailableHotels
     * @param  HotelSC &$hotelSC
     * @param  boolean $newSearch
     * @param  array   $apiHotels
     *
     * @return
     */
    private function getDisplayableHotels($dbAvailableHotels, HotelSC &$hotelSC, $newSearch = false, $apiHotels = array())
    {
        $ctr           = 1;
        $limit         = 0;
        $hotels        = array();
        $noOfferHotels = array();

        $requestId = $hotelSC->getHotelSearchRequestId();

        if (!empty($apiHotels)) {
            $sourceIdentifier = array_keys($apiHotels);
        } else {
            $sourceIdentifier = array();
            $identifiers      = $this->searchResponseRepo->getHotelSearchResponseByRequestIdOnly($requestId, false, 'hotelKey');
            foreach ($identifiers as $id) {
                $sourceIdentifier[] = $id->getHotelKey();
            }
        }

        // Display the directly queried hotel on top if this is the first page on first load
        if (!empty($hotelSC->getHotelKey()) && !in_array($hotelSC->getHotelKey(), $sourceIdentifier)) {
            $sourceIdentifier[] = $hotelSC->getHotelKey();

            if ($newSearch == "1") {
                $selectedHotel = $this->hsRepo->getHotelBySourceIdentifier($hotelSC->getHotelKey(), $hotelSC->getHotelKey(), $hotelSC->getHotelId());

                // tt sourced hotel are not on the list of hotel response from api;
                // so will saved it on our hotel search response for pagination/filter/etc purposes
                $saveHotel = ($selectedHotel['source'] === 'tt') ? true : false;

                $hotels[] = $this->getHotelTeaserData($selectedHotel, $hotelSC, $saveHotel);
                $limit++;
                $ctr++;
            }
        }

        $specialEntityTypes = array(
            $this->container->getParameter('SOCIAL_ENTITY_LANDMARK'),
            $this->container->getParameter('SOCIAL_ENTITY_AIRPORT'),
            $this->container->getParameter('SOCIAL_ENTITY_DOWNTOWN'),
            $this->container->getParameter('SOCIAL_ENTITY_REGION')
        );

        // Get hotels that does not have offers
        if (!in_array($hotelSC->getEntityType(), $specialEntityTypes)) {
            $noOfferHotels = $this->hsRepo->getHotelBySearchCriteria($hotelSC, '', $sourceIdentifier);
            if (!$this->isRest) {
                $noOfferHotels = array_slice($noOfferHotels, 0, 2);
            }
        }

        // Please update the else loop whenever updating the loop inside this if
        if (!empty($apiHotels)) {
            foreach ($dbAvailableHotels as $hotel) {
                // $ctr will ensure that no offer hotels are placed on 4th and 14th row, the + 2 is counting the no offers itself
                if ($ctr > ($hotelSC->getLimit() + 2)) {
                    $ctr = 1;
                }

                // For marketing purposes, we'll insert the no-offer hotels on 4th and 14th row
                if (!empty($noOfferHotels) && ($ctr == 4 || $ctr == 14)) {
                    $noOfferHotel = array_shift($noOfferHotels);
                    $hotels[]     = $this->getHotelTeaserData($noOfferHotel, $hotelSC);
                    $ctr++;
                    $limit++;
                }

                $hotel = $this->getHotelTeaserData($hotel, $hotelSC, true, $apiHotels[$hotel['hotelKey']]);

                // Set the maximum price filter
                if ($hotel['price'] > $hotelSC->getMaxPrice()) {
                    // Price is ceiled in hotelAvail
                    $hotelSC->setMaxPrice($hotel['price']);
                }

                $hotels[] = $hotel;
                $ctr++;
            }
        } else {
            // Note that most of the params are passed by reference
            $this->formatCachedHotelSearch($dbAvailableHotels, $hotels, $noOfferHotels, $ctr, $limit, $hotelSC);
        }

        // If we only have 3 or 13 hotels, we would still insert those no offers on the 4th and 14th
        if (!empty($noOfferHotels) && ($ctr == 4 || $ctr == 14)) {
            $noOfferHotel = array_shift($noOfferHotels);
            $hotels[]     = $this->getHotelTeaserData($noOfferHotel, $hotelSC);
        }

        // For mobile requests, we return all, if not, return hotels for 1 page
        if (!$this->isRest && count($dbAvailableHotels) > $hotelSC->getLimit()) {
            $limit  += $hotelSC->getLimit();
            $hotels = array_slice($hotels, 0, $limit);
        }

        return $hotels;
    }

    /**
     * get Hotel Teaser Data
     *
     * @param  $hotelInfo
     * @param  HotelSC &$hotelSC
     * @param  boolean $save
     * @param  array   $apiInfo
     *
     * @return
     */
    private function getHotelTeaserData($hotelInfo, HotelSC &$hotelSC, $save = false, $apiInfo = array())
    {
        $requestId = $hotelSC->getHotelSearchRequestId();
        $hotel     = array(
            'hotelSearchRequestId' => $requestId,
            'hotelId' => $hotelInfo['hotelId'],
            'hotelKey' => $hotelInfo['hotelKey'],
            'hotelName' => $this->hotelRepo->getHotelNameById($hotelInfo['hotelId'], $this->siteLanguage),
            'hotelNameURL' => $this->utils->cleanTitleData($hotelInfo['hotelName']),
            'category' => $hotelInfo['category'],
            'district' => $hotelInfo['district'],
            'city' => $hotelInfo['city'],
            'country' => $hotelInfo['country'],
            'distance' => '0.00',
            'distances' => array(),
            'mapImageUrl' => $this->container->get('HotelsServices')->getMapImageUrl($hotelInfo['hotelId'], $requestId, $this->transactionSourceId, $this->container->getParameter('hotels')['page_src']['hrs']),
            'isoCurrency' => '',
            'price' => 0,
            'avgPrice' => 0,
            'cancelable' => 0,
            'breakfast' => 0,
            'has360' => boolval($hotelInfo['has360'])
        );

        $images                   = $this->getHotelMainImage($hotelInfo['hotelId'], null, 2);
        $hotel['mainImage']       = $images[0];
        $hotel['mainImageMobile'] = $images[1];

        if (!empty($apiInfo)) {
            $hotel['isoCurrency'] = (!empty($apiInfo['isoCurrency'])) ? $apiInfo['isoCurrency'] : $this->getAPICurrency();
            $hotel['price']       = $apiInfo['price'];
            $hotel['avgPrice']    = $apiInfo['avgPrice'];

            if ($hotel['isoCurrency'] !== $this->selectedCurrency) {
                $hotel['price']       = $this->getDisplayPrice(['isoCurrency' => $apiInfo['isoCurrency'], 'amount' => $apiInfo['price']], null, true, true);
                $hotel['avgPrice']    = $this->getDisplayPrice(['isoCurrency' => $apiInfo['isoCurrency'], 'amount' => $apiInfo['avgPrice']], null, true, true);
                $hotel['isoCurrency'] = $this->selectedCurrency;
            }

            $hotel['cancelable'] = $apiInfo['cancelable'];
            $hotel['breakfast']  = $apiInfo['breakfast'];
        }

        if ($hotelInfo['distanceFromDowntown'] > 0) {
            $hotel['distance'] = $downtown          = number_format($hotelInfo['distanceFromDowntown'] / 1000, 2);

            $hotel['distances']['downtown'] = array(
                'name' => $hotelInfo['downtown'],
                'distance' => $downtown
            );

            if ($downtown > $hotelSC->getMaxDistance()) {
                $hotelSC->setMaxDistance($downtown);
            }
        }

        if ($hotelInfo['distanceFromAirport'] > 0) {
            $hotel['distances']['airport'] = array(
                'name' => $hotelInfo['airport'],
                'distance' => number_format($hotelInfo['distanceFromAirport'] / 1000, 2)
            );
        }

        if ($hotelInfo['distanceFromTrainStation'] > 0) {
            $hotel['distances']['train'] = array(
                'name' => $hotelInfo['trainStation'],
                'distance' => number_format($hotelInfo['distanceFromTrainStation'] / 1000, 2)
            );
        }

        if ($save) {
            $dbHotel              = $hotel;
            $dbHotel['distances'] = json_encode($hotel['distances']);
            $this->searchResponseRepo->insertHotelSearchResponse($dbHotel);
        }

        $hotelSource          = (isset($hotelInfo['source'])) ? $hotelInfo['source'] : $this->hsRepo->getHotelSourceField('source', ['hotelId', $hotel['hotelId']]);
        $hotel['hotelSource'] = ($hotelSource) ? strtolower($hotelSource) : '';

        if ($this->isRest) {
            $hotel['mainImage']           = $hotel['mainImageMobile'];
            $hotel['hotelNameCleanTitle'] = $hotel['hotelNameURL'];
            $hotel['country']             = $hotel['country'];

            if (empty($hotel['distances'])) {
                $hotel['distances'] = '';
            }

            unset($hotel['hotelNameURL'], $hotel['hotelSource']);
        }
        unset($hotel['mainImageMobile'], $hotel['distance']);

        return $hotel;
    }

    //*****************************************************************************************
    // Offer Functions
    /**
     * hotelDetails
     *
     * @param $serviceConfig
     * @param $hotelSC
     *
     * @return
     */
    public function hotelDetails(HotelServiceConfig $serviceConfig, HotelSC $hotelSC)
    {
        $this->initializeService($serviceConfig);

        $hotelSC->setHotelNameURL($this->utils->cleanTitleData($hotelSC->getHotelName()));
        $hotelSC->getCity()->setName(str_replace("+", " ", $hotelSC->getHotelName()));

        $hotelKey   = $this->hsRepo->getHotelSourceField('sourceId', array('hotelId', $hotelSC->getHotelId()));
        $locationId = $this->hsRepo->getHotelSourceField('locationId', array('hotelId', $hotelSC->getHotelId()));

        $hotelSC->setHotelKey($hotelKey);
        $hotelSC->setLocationId($locationId);

        $returnData                        = array();
        $returnData['detailspage']         = 1;
        $returnData['input']               = $hotelSC->toArray();
        $returnData['input']['refererURL'] = $serviceConfig->getRoute('refererURL');

        $returnData['entity_type'] = $this->container->getParameter('SOCIAL_ENTITY_HOTEL');
        $returnData['nightsCount'] = $this->utils->computeNights($hotelSC->getFromDate(), $hotelSC->getToDate());

        $returnData['hotelDetails'] = $this->getHotelDBInformation($hotelSC->getHotelId(), 0, null, $hotelSC->getHotelSearchRequestId());
        if (!empty($returnData['hotelDetails'])) {
            $returnData['hotelDetails']['trustyou'] = $this->container->get('ReviewServices')->getMetaReview($this->hsRepo->getHotelSourceField('trustyouId', array('hotelId', $hotelSC->getHotelId())), $this->siteLanguage);

            $this->logger->addHotelActivityLog('HRS', 'hotel_details', $this->userId, array(
                'hotelName' => $returnData['hotelDetails']['name'],
                'hotelKey' => $returnData['hotelDetails']['hotelKey'],
                'hotelId' => $returnData['hotelDetails']['hotelId'],
                'name' => $returnData['hotelDetails']['name'],
                'city' => $returnData['hotelDetails']['city'],
                'country' => $returnData['hotelDetails']['country'],
                'locationId' => $returnData['input']['locationId'],
            ));
        }

        $returnData['offers_rates_refresh_timespan'] = $this->container->getParameter('modules')['hotels']['vendors']['hrs']['rates_refresh_timespan'];

        return $returnData;
    }

    /**
     * hotel Offers
     *
     * @param  HotelServiceConfig $serviceConfig
     * @param  HotelSC $hotelSC
     *
     * @return
     */
    public function hotelOffers(HotelServiceConfig $serviceConfig, HotelSC $hotelSC)
    {
        $serviceConfig->setPage('offers');

        $this->initializeService($serviceConfig);

        $this->convertCurrency = true;
        $this->forBookForm     = true;

        if (empty($hotelSC->getHotelName())) {
            $hotelSC->setHotelName($this->hotelRepo->getHotelNameById($hotelSC->getHotelId()));
        }

        $hotelSC->setHotelNameURL($this->utils->cleanTitleData($hotelSC->getHotelName()));
        $hotelSC->getCity()->setName(str_replace("+", " ", $hotelSC->getHotelName()));

        $hotelKey    = $this->hsRepo->getHotelSourceField('sourceId', array('hotelId', $hotelSC->getHotelId()));
        $locationId  = $this->hsRepo->getHotelSourceField('locationId', array('hotelId', $hotelSC->getHotelId()));
        $hotelSource = $this->hsRepo->getHotelSourceField('source', array('hotelId', $hotelSC->getHotelId()));

        $hotelSC->setHotelKey($hotelKey);
        $hotelSC->setLocationId($locationId);

        $data                   = array();
        $data['totalNumOffers'] = 0;
        $data['roomOffers']     = array();

        if (!$this->validateDates($hotelSC->getFromDate(), $hotelSC->getToDate(), 'offers')) {
            $data['error'] = $this->translator->trans("Invalid Check-In/Check-Out date.");
        } elseif ($hotelSource == 'tt') {
            $data['error'] = $this->translator->trans("There is no availability on the selected dates at this time.");
        } else {
            $logParams = array(
                'hotelName' => $this->hotelRepo->getHotelNameById($hotelSC->getHotelId(), $this->siteLanguage),
                'hotelKey' => $hotelSC->getHotelKey(),
                'hotelId' => $hotelSC->getHotelId(),
                'fromDate' => $hotelSC->getFromDate(),
                'toDate' => $hotelSC->getToDate(),
                'userId' => $this->userId
            );

            $response = $this->hrs->getHotelOffers($hotelSC, $logParams);

            if (!$response->isSuccess()) {
                // if we don't get offers in the response we show no offer message
                $data['error'] = $this->translator->trans('There is no availability on the selected dates at this time.');
            } else {
                $dataFromResponse = $this->getOfferResponse($response, $hotelSC);
                if (!empty($dataFromResponse)) {
                    $data = array_merge($data, $dataFromResponse);
                }
            }
        }

        if (empty($data['roomOffers'])) {
            // Let's get the hotel information instead
            $hoteFromAPI = $this->hrs->getHotelAPIInformation($hotelSC, $this->userId);

            $data['hotelDetails'] = $this->getHotelInformation($hoteFromAPI, $hotelSC->getHotelId(), 'offers');
        }

        $data['input']                    = $hotelSC;
        $data['selected_currency']        = $this->selectedCurrency;
        $data['NmbrRooms']                = $hotelSC->getSingleRooms() + $hotelSC->getDoubleRooms();
        $data['nightsCount']              = $this->utils->computeNights($hotelSC->getFromDate(), $hotelSC->getToDate());
        $data['hotelDetails']['trustyou'] = $this->container->get('ReviewServices')->getMetaReview($this->hsRepo->getHotelSourceField('trustyouId', array('hotelId', $hotelSC->getHotelId())), $this->siteLanguage);

        if ($this->isRest) {
            $data['hotelDetails']['fullTourURL'] = substr($this->container->get('router')->generate('_hotel_360_tour', ['name' => $data['hotelDetails']['hotelNameURL'], 'id' => $data['hotelDetails']['hotelId']]), 1);

            if (isset($data['error'])) {
                $returnKeys = array('hotelDetails', 'error');
            } else {
                $data['input'] = $this->getInputDataForMobile($hotelSC);
                $returnKeys    = array('error', 'hotelDetails', 'input', 'NmbrRooms', 'nightsCount', 'roomOffers', 'totalNumOffers', 'includedTaxAndFees');
            }
        } else {
            $data['LanguageGet'] = $this->siteLanguage;
            $data['pageSrc']     = $serviceConfig->getPageSrc();

            $data['offersLoop']            = $this->templating->render($serviceConfig->getTemplate('offersLoopTemplate'), $data);
            $data['hotelDistances']        = $this->templating->render($serviceConfig->getTemplate('hotelDistancesTemplate'), $data);
            $data['hotelReviewHighlights'] = $this->templating->render($serviceConfig->getTemplate('hotelReviewHighlightsTemplate'), $data);
            $data['hotelCreditCards']      = $this->templating->render($serviceConfig->getTemplate('hotelCreditCardsTemplate'), $data);
            $data['maxSingleRoomsCount']   = $hotelSC->getSingleRooms();
            $data['maxDoubleRoomsCount']   = $hotelSC->getDoubleRooms();

            $returnKeys = array('error', 'hotelDetails', 'roomOffers', 'includedTaxAndFees', 'offersLoop', 'hotelDistances', 'hotelReviewHighlights', 'hotelCreditCards', 'maxSingleRoomsCount', 'maxDoubleRoomsCount');
        }

        // Only return values with keys from $returnKeys
        $resultArray = array_intersect_key($data, array_flip($returnKeys));

        return $this->utils->createJSONResponse($resultArray);
    }

    /**
     * get Input Data For Mobile
     *
     * @param $hotelSC
     *
     * @return
     */
    private function getInputDataForMobile(HotelSC $hotelSC)
    {
        $toreturn = array(
            'hotelSearchRequestId' => $hotelSC->getHotelSearchRequestId(),
            'hotelCityName' => $hotelSC->getCity()->getName(),
            //            'city' => $hotelSC->getCity()->getName(),
            'hotelId' => $hotelSC->getHotelId(),
            'singleRooms' => $hotelSC->getSingleRooms(),
            'doubleRooms' => $hotelSC->getDoubleRooms(),
            'adultCount' => $hotelSC->getAdultCount(),
            'childCount' => $hotelSC->getChildCount(),
            'page' => $hotelSC->getPage(),
            'sortBy' => $hotelSC->getSortBy(),
            'sortOrder' => $hotelSC->getSortOrder(),
            'nbrStars' => $hotelSC->getNbrStars(),
            'district' => $hotelSC->getDistrict(),
            'priceRange' => $hotelSC->getPriceRange(),
            'distanceRange' => $hotelSC->getDistanceRange(),
            'newAjaxSearch' => $hotelSC->isNewSearch(),
            'country' => $hotelSC->getCountry(),
            'longitude' => $hotelSC->getLongitude(),
            'latitude' => $hotelSC->getLatitude(),
            'entityType' => $hotelSC->getEntityType(),
            'fromDate' => $hotelSC->getFromDate(),
            'toDate' => $hotelSC->getToDate(),
            'locationId' => $hotelSC->getLocationId(),
            'from_mobile' => $this->isRest,
            'hotelNameURL' => $hotelSC->getHotelName(),
            'hotelKey' => $hotelSC->getHotelKey(),
        );

        $childAge = $hotelSC->getChildAge();
        $childBed = $hotelSC->getChildBed();

        for ($i = 1; $i <= $this->container->getParameter('hotels')['max_child_count']; $i++) {
            $toreturn['childAge'.$i] = (isset($childAge[$i]) && !empty($childAge[$i])) ? $childAge[$i] : 0;
            $toreturn['childBed'.$i] = (isset($childBed[$i]) && !empty($childBed[$i])) ? $childBed[$i] : "parentsBed";
        }

        $toreturn['fromDateC'] = $hotelSC->getFromDate();
        $toreturn['toDateC']   = $hotelSC->getToDate();

        return $toreturn;
    }

    /**
     * get Offer Response
     *
     * @param $response
     * @param $hotelSC
     *
     * @return
     */
    private function getOfferResponse(HotelApiResponse $response, HotelSC $hotelSC)
    {
        $toreturn = array();

        $toreturn['totalNumOffers']     = 0;
        $toreturn['roomOffers']         = array();
        $toreturn['includedTaxAndFees'] = array();

        $hotel = $response->getData()['hotel'];
        if (!empty($hotel->getRoomOffers())) {
            $roomOffers = array();
            foreach ($hotel->getRoomOffers() as $code => $hotelRooms) {
                foreach ($hotelRooms as $offerName => $offers) {
                    if (strtolower($offerName) == 'header') {
                        $roomOffers[$code][$offerName] = $offers;
                        continue;
                    }

                    foreach ($offers as $key => $hotelRoomOffer) {
                        if (strtolower($key) == 'roomsize') {
                            $roomOffers[$code][$offerName][$key] = $hotelRoomOffer;
                            continue;
                        }

                        $roomDetails                = $this->getRoomOfferDetail('OFFERS', $hotelRoomOffer);
                        $roomDetails['counter']     = $hotelRoomOffer->getCounter();
                        $roomDetails['header']      = $hotelRoomOffer->getHeader();
                        $roomDetails['roomsLeft']   = $hotelRoomOffer->getRoomsLeftCount();
                        $roomDetails['room']        = json_encode($hotelRoomOffer->getBookableInfo());
                        $roomDetails['offerDetail'] = $hotelRoomOffer->getRoomOfferXml();
                        $roomDetails['cancelable']  = $hotelRoomOffer->isCancellable();

                        $rateInfo             = $hotelRoomOffer->getRates();
                        $roomDetails['rates'] = $this->getRoomRates($rateInfo); // New UI design does not have rates popup anymore, remove the whole function once Android has updated
                        $roomDetails['price'] = $this->getDisplayPrice($rateInfo['totalPriceInclusiveHotel'], false, true);

                        $roomImages = $this->getRoomImages($roomDetails, $hotelSC->getHotelId());
                        foreach ($roomImages as $img) {
                            $roomDetails['images'][] = $this->createImageSource($img, 0, null);
                        }

                        $roomDetails['gallery'] = $this->getRoomGallery($roomDetails, $hotelSC->getHotelId());

                        //Get extracted room size
                        $roomSize                = $hotelRoomOffer->getRoomSize();
                        $roomDetails['roomSize'] = $roomSize;

                        // get room sleeps image
                        $roomDetails['roomSleepsImage'] = $this->getRoomSleepsImage($roomDetails);

                        // Max room count
                        $roomDetails['maxRoomCount'] = $hotelRoomOffer->getMaxRoomCount();

                        // Filter room offers
                        if (!isset($roomOffers[$code][$offerName])) {
                            $roomOffers[$code][$offerName] = [];
                        }

                        $this->filterRoomOffers($roomDetails, $roomOffers[$code][$offerName], $toreturn['totalNumOffers']);
                    }
                }
            }

            // filter category room sizes (each offer category will have a information/field like 'roomSize => "min - max"'.
            if (isset($roomOffers) && !empty($roomOffers)) {
                $this->filterRoomSizePerOfferCategory($roomOffers);
            }

            $toreturn['roomOffers']         = $roomOffers;
            $toreturn['includedTaxAndFees'] = $hotel->getIncludedTaxAndFees();
        }

        $toreturn['hotelDetails'] = $this->getHotelInformation($hotel, $hotelSC->getHotelId(), 'offers', 0, 3, $hotelSC->getHotelSearchRequestId(), -1);

        return $toreturn;
    }

    //*****************************************************************************************
    // Offer Information Functions
    /**
     * This method removes duplicated offers and selects the cheapest when duplicates are found. Duplicates are identified by having the same info for: breakfast; cancelable; category; offer description.
     *
     * @param Array   $offer     The offer to be filtered before it's added to our filtered lists.
     * @param Array   $offerList The filtered offers.
     * @param Integer $counter   Will be updated with the total number of filtered offers. This will also update the 'counter' information in each offer.
     *
     * @return
     */
    private function filterRoomOffers(array $offer, array &$offerList, &$counter)
    {
        $hasDuplicate = 0;
        foreach ($offerList as $key => $item) {
            if (strtolower($key) == 'roomsize') {
                continue;
            }

            if ($offer['cancelable'] == $item['cancelable'] &&
                trim(strtolower($offer['conditions']['mainInfo']['breakfast'])) == trim(strtolower($item['conditions']['mainInfo']['breakfast'])) &&
                trim(strtolower($offer['category'])) == trim(strtolower($item['category'])) &&
                trim(strtolower($offer['description'])) == trim(strtolower($item['description']))) {
                $hasDuplicate = 1;
                if (floatval($offer['price']) < floatval($item['price'])) {
                    $offer['counter'] = $item['counter'];
                    $offerList[$key]  = $offer;
                }

                break;
            }
        }

        // no duplicate found as per business criteria, then add it on our list of offers.
        if ($hasDuplicate == 0) {
            $counter += 1;

            $offer['counter'] = $counter;
            $offerList[]      = $offer;
        }
    }

    /**
     * This method will update the $roomOffers and provide the min-max range room size information for all the offers
     * for each offer category.
     *
     * @param $roomOffers
     *
     * @return
     */
    private function filterRoomSizePerOfferCategory(&$roomOffers)
    {
        // Update our $roomOffers category's roomSize to describe the min-max room size range of the filtered offers it includes.
        foreach ($roomOffers as $code => &$rooms) {
            foreach ($rooms as $roomName => &$room) {
                // skip those that are not an offer category
                if (strtolower($roomName) == 'header') {
                    continue;
                }

                // Loop through all the already filtered offers of this category.
                // The $room['roomSize'] is temporarily an array which will contain distinct room sizes of
                // all the offers of this category --later this will only contain the "min-max" room size range
                // base on the distinct room sizes it contains.
                // Other number indexed items (e.g. $room[0]) is the offer of this category.
                foreach ($room as $key => &$offer) {
                    // skip those that are not an offer
                    if (strtolower($key) == 'roomsize') {
                        continue;
                    }

                    $room['roomSize'] = array_merge($room['roomSize'], $offer['roomSize']);

                    // remove the roomSize information on each of our offer since it's not needed on the response.
                    unset($offer['roomSize']);
                }

                // After we get all the distinct room sizes of all offers on this category; we need to get the offer room size range.
                $roomSize = "";
                if (!empty($room['roomSize'])) {
                    $min = min($room['roomSize']);
                    $max = max($room['roomSize']);

                    $roomSize = "{$min} m<sup>2</sup>";
                    if ($min != $max) {
                        $roomSize .= " - {$max} m<sup>2</sup>";
                    }

                    $roomSize = "({$roomSize})";
                }

                if ($this->isRest) {
                    $roomSize = str_replace('<sup>2</sup>', '²', $roomSize);
                }

                $room['roomSize'] = $roomSize;
            }
        }
    }

    /**
     * This method prepares the breakfast string shown to the user
     *
     * @param $requestingPage
     * @param $breakfastType
     * @param $breakfastRates
     *
     * @return The breakfast string
     */
    private function getBreakfastDetails($requestingPage, $breakfastType, $breakfastRates)
    {
        $breakfast = '';

        switch ($breakfastType) {
            case 'inclusive':
            case 'inclusiveHalfBoard':
            case 'inclusiveFullBoard':
            case 'allInclusive':
                $breakfast = $this->translator->trans('Including breakfast.');
                break;
            case 'notAvailable':
                $breakfast = $this->translator->trans('Meals not available.');
                break;
            case 'unknown':
            case 'variant':
            case 'exclusive':
            default:
                $breakfast = $this->translator->trans('Excluding breakfast');

                //$rate  = (in_array($requestingPage, array('OFFERS', 'MODIFICATION'))) ? $breakfastRates['breakfastPriceCustomer'] : $breakfastRates['breakfastPriceHotel'];
                $rate  = $breakfastRates['breakfastPriceHotel'];
                $price = $this->getDisplayPrice($rate);

                $priceTxt = $this->getDisplayPrice($rate, true, true);
                if ($price && $priceTxt > 0) {
                    $breakfast .= ' (costs '.$price.' at the hotel per person per night).';
                }
                break;
        }

        return $breakfast;
    }

    /**
     * This method returns the conditions of a room offer
     *
     * @param $requestingPage
     * @param $hotelRoomOffer
     *
     * @return Array $conditions
     */
    private function getRoomOfferConditions($requestingPage, HotelRoom $hotelRoomOffer)
    {
        $conditions      = array();
        $prepaymentFee   = '';
        $cancellationFee = '';

        //$useHotelPrice = (!in_array($requestingPage, array('OFFERS')));
        $useHotelPrice = true;
        $forceConvert  = (in_array($requestingPage, array('BOOK_FORM')));
        $rateInfo      = $hotelRoomOffer->getRates();

        // Hot Deal
        if ($hotelRoomOffer->getRoomOfferType() == 'Hot') {
            $conditions['mainInfo']['hotDeal'] = $this->translator->trans('Lowest price guaranteed.');
        }

        // Breakfast
        $conditions['mainInfo']['breakfast'] = $this->getBreakfastDetails($requestingPage, $hotelRoomOffer->getBreakfastType(), $hotelRoomOffer->getBreakfastRates());

        // Prepayment
        $paymentType    = $hotelRoomOffer->getPrepaymentType();
        $prepaymentMode = $hotelRoomOffer->getPrepaymentValueMode();
        if ($paymentType == 'deposit') {
            $conditions['mainInfo']['prepayment'] = $this->translator->trans('You may need to make an advance payment.');

            if (isset($prepaymentMode['percent'])) {
                $prepaymentFee     = $this->getFeesByPercentage($prepaymentMode['percent'], $rateInfo, $useHotelPrice);
                $prepaymentFeeText = $this->getFeesByPercentage($prepaymentMode['percent'], $rateInfo, $useHotelPrice, $forceConvert);

                $conditions['moreInfo']['prepayment'] = array(sprintf("%s %s %s.", $this->translator->trans('Prepayment of'), $prepaymentFeeText, $this->translator->trans('to the hotel with immediate charge to your credit card')));
            }
        } else {
            // put payOnSite at the top of the items for mainInfo
            $temp                                = $conditions['mainInfo'];
            $conditions['mainInfo']              = array();
            $conditions['mainInfo']['payOnSite'] = $this->translator->trans('Pay at the hotel.');
            $conditions['mainInfo']              = array_merge($conditions['mainInfo'], $temp);

            $conditions['mainInfo']['prepayment'] = $this->translator->trans('Book without advance payment.');
            $conditions['moreInfo']['prepayment'] = array($this->translator->trans('No deposit will be charged. '));
        }

        // Cancellation
        // For scenarios that don't fall into any of the conditions below, no message shall be shown
        // The API has not mentioned any details as to how to process the other scenarios
        // and we haven't yet found cases in hrs.com that match those scenario that we could apply on our system
        // Update these if necessary when new info has been given
        $penalties = $hotelRoomOffer->getCancellationPenalties();
        if ($penalties['percent'] > 0) {
            $cancellationFee     = $this->getFeesByPercentage($penalties['percent'], $rateInfo, $useHotelPrice);
            $cancellationFeeText = $this->getFeesByPercentage($penalties['percent'], $rateInfo, $useHotelPrice, $forceConvert);
        }

        if ($hotelRoomOffer->isCancellable()) {
            if ($this->isRest || !in_array($requestingPage, array('OFFERS', 'MODIFICATION'))) {
                $infoKey = ($this->isRest) ? 'bookInfo' : 'moreInfo';

                $deadline = $this->utils->formatDate($penalties['absoluteDeadline'], 'longDateTime');

                $conditions['mainInfo']['cancellation'] = '';
                $conditions[$infoKey]['cancellation'][] = $this->translator->trans('Cancel free of charge until ').$deadline.$this->translator->trans(' (hotel local time) : ')
                    .$this->getFeesByPercentage(0, $rateInfo, $useHotelPrice, $forceConvert);

                if (isset($cancellationFeeText)) {
                    $afterCancelDeadline                    = date_format(date_add(date_create($penalties['absoluteDeadline']), date_interval_create_from_date_string('1 second')), "Y-m-d\TH:i:sP");
                    $conditions[$infoKey]['cancellation'][] = $this->translator->trans('Cancellation fee from ').$this->utils->formatDate($afterCancelDeadline, 'longDateTime').$this->translator->trans(' (hotel local time) : ').$cancellationFeeText;
                }
            }

            if (in_array($requestingPage, array('OFFERS', 'MODIFICATION'))) {
                $deadline = $this->utils->formatDate($penalties['absoluteDeadline'], 'datetime');

                $conditions['mainInfo']['cancellation']   = $this->translator->trans('Cancel free of charge until ').$deadline.'.';
                $conditions['moreInfo']['cancellation'][] = $this->translator->trans('Your booking can be cancelled until ').$deadline.$this->translator->trans(' (Hotel - local time).');

                if ($penalties['percent'] > 0) {
                    $conditions['moreInfo']['cancellation'][] = $this->translator->trans('If cancelled or modified later, or in case of no-show, ').$penalties['percent'].$this->translator->trans(' percent of the first night will be charged. ');
                }
            }

            $today = time();
            if ((!isset($dateCancelled) && $today < strtotime($penalties['absoluteDeadline'])) || (isset($dateCancelled) && strtotime($dateCancelled) < strtotime($penalties['absoluteDeadline']))) {
                // Override the price assignment above if it's not yet past cancellation deadline
                $cancellationFee = $this->translator->trans('FREE');
            }
        } else {
            // Cancellation data is void in this section (cancellationDeadline / averageCancellationFeePercent) so no need to consider them
            $conditions['mainInfo']['cancellation'] = $this->translator->trans('Cancellation not free-of-charge.');

            if (isset($cancellationFeeText)) {
                $guaranteeSpeil                           = $this->translator->trans('A guaranteed booking can no longer be cancelled free of charge.');
                $conditions['moreInfo']['cancellation'][] = $guaranteeSpeil;

                if ($this->isRest) {
                    $conditions['bookInfo']['cancellation'][] = $guaranteeSpeil;
                }

                if (($this->isRest || !in_array($requestingPage, array('OFFERS', 'MODIFICATION'))) && $hotelRoomOffer->getPrepaymentType() == 'deposit') {

                    $infoKey = ($this->isRest) ? 'bookInfo' : 'moreInfo';

                    $conditions[$infoKey]['cancellation'][] = $this->translator->trans('An advance payment of ').$cancellationFeeText.$this->translator->trans(' is required to secure this reservation. This amount will be charged to your credit card immediately by the hotel.');

                    if ($penalties['nonRefundableRate']) {
                        $conditions[$infoKey]['cancellation'][] = $this->translator->trans('The advance cannot be refunded in the event of cancellation or non-arrival.');
                    }
                    // We don't have any guarantee that the hotel will refund how much amount if nonRefundableRate = false due to lacking info, to be safe no info shall be displayed

                    $conditions[$infoKey]['cancellation'][] = $this->translator->trans('Cancellation fee of ').$cancellationFeeText;
                }
            }
        }
        if (isset($penalties['description'])) {
            $specicalCondition = $this->translator->trans('Special Cancellation Condition: ').$penalties['description'];

            $conditions['moreInfo']['cancellation'][] = $specicalCondition;
            if ($this->isRest) {
                $conditions['bookInfo']['cancellation'][] = $specicalCondition;
            }
        }

        // Rooms Left
        if ($hotelRoomOffer->getRoomsLeftCount() > 0) {
            $conditions['mainInfo']['roomsLeft'] = sprintf("%s %s %s %s.", $this->translator->trans('Only'), $hotelRoomOffer->getRoomsLeftCount(), (($hotelRoomOffer->getRoomsLeftCount() > 1) ? $this->translator->trans('rooms')
                    : $this->translator->trans('room')), $this->translator->trans('left'));
        }

        $conditions['prepaymentFee']   = $prepaymentFee;
        $conditions['cancellationFee'] = $cancellationFee;

        return $conditions;
    }

    /**
     * This method gets the room offer details
     *
     * @param $requestingPage
     * @param $hotelRoomOffer
     *
     * @return $room - An array of room offer details
     */
    private function getRoomOfferDetail($requestingPage, HotelRoom $hotelRoomOffer)
    {
        $roomDetails                 = array();
        $roomDetails['type']         = $hotelRoomOffer->getRoomType();
        $roomDetails['name']         = $hotelRoomOffer->getName();
        $roomDetails['category']     = $hotelRoomOffer->getName();
        $roomDetails['description']  = $hotelRoomOffer->getDescription();
        $roomDetails['offerType']    = $hotelRoomOffer->getRoomOfferType();
        $rateInfo                    = $hotelRoomOffer->getRates();
        $roomDetails['nightlyPrice'] = $this->getDisplayPrice($rateInfo['averageRoomPriceHotel'], false);
        $roomDetails['totalPrice']   = $this->getDisplayPrice($rateInfo['totalPriceInclusiveHotel'], false, false, false, array('append_content' => array('after_currency_text' => '<br/>')));
        $roomDetails['conditions']   = $this->getRoomOfferConditions($requestingPage, $hotelRoomOffer);

        return $roomDetails;
    }

    /**
     * This method returns the room rates
     *
     * @param $offerDetail
     *
     * @return data
     */
    private function getRoomRates($offerDetail)
    {
        $rates = array();

        // All details in here is for one room for all nights
        // If # of rooms is increased, have a js to process it
        foreach ($offerDetail['rates'] as $rate) {
            if (!is_numeric($rate['rateKey']) && !isset($rates['label'])) {
                // There are times when rateLabel is not used could not find any other generic logic
                $rates['label'] = $rate['rateLabel'];
            }

            if (is_array($rate['roomPriceHotel']) && ($rate['roomPriceHotel']['amount'] > 0)) {
                $price = $this->getDisplayPrice($rate['roomPriceHotel']);
            } else {
                $price = $this->getDisplayPrice($offerDetail['averageRoomPriceHotel']);
            }

            $startDate = str_replace('+', ' ', $rate['from']);
            $endDate   = str_replace('+', ' ', $rate['to']);
            while (strtotime($startDate) < strtotime($endDate)) {
                $nextDate = date("Y-m-d H:i:s", strtotime("+1 day", strtotime($startDate)));

                $rates['breakdown'][] = array(
                    'period' => $this->utils->formatDate($startDate, 'collapsed').' - '.$this->utils->formatDate($nextDate, 'collapsed'),
                    'breakfast' => $this->getBreakfastDetails('OFFERS', $rate['breakfastType'], $rate),
                    'price' => $price
                );

                $startDate = $nextDate;
            }

            $rates['total']       = $this->getDisplayPrice($offerDetail['totalPriceHotel']);
            $rates['totalIncTax'] = $this->getDisplayPrice($offerDetail['totalPriceInclusiveHotel']);
        }

        return $rates;
    }

    /**
     * This method prepares the room gallery pop-up on the hotel details page
     *
     * @param $roomDetails
     * @param $hotelId
     * @param $template
     *
     * @return Renders the room gallery twig
     */
    private function getRoomGallery(&$roomDetails, $hotelId, $template = '')
    {
        $toreturn = array(
            'counter' => $roomDetails['counter'],
            'name' => $roomDetails['name'],
        );

        $facilityNames = array();
        $facilities    = $this->getFacilities($hotelId);
        foreach ($facilities as $fac) {
            $facilityNames[] = implode(', ', $fac['names']);
        }

        $toreturn['facilities'] = implode(', ', $facilityNames);

        $toreturn['images'] = array();
        if (isset($roomDetails['images'])) {
            $toreturn['images'] = $roomDetails['images'];
        }

        if (!empty($template)) {
            return $this->templating->render($template, $toreturn);
        } else {
            return $toreturn;
        }
    }

    /**
     * This method gets the images per room type
     *
     * @param $roomDetails
     * @param $hotelId
     *
     * @return $roomImages
     */
    private function getRoomImages($roomDetails, $hotelId)
    {
        $roomType = array();
        $name     = '';

        if ($this->siteLanguage == 'en') {
            $name = $roomDetails['name'];
        }

        if (!empty($name)) {
            $roomType[] = strtolower(preg_replace('/(\s|[^a-zA-Z0-9])/m', '', $name));
        }

        $type       = strtolower(preg_replace('/\s/', '', $roomDetails['type']));
        $roomType[] = $type;
        $roomType[] = $type."room";
        $roomType[] = 'exteriorview';

        $roomImages = $this->imageRepo->getHotelRoomImages($hotelId, array_unique($roomType), 3);

        return $roomImages;
    }

    /**
     * This method return the sleep count and the image for a certain room type and category
     *
     * @param  Array $offer The offer.
     *
     * @return Array The sleep count and the image
     */
    private function getRoomSleepsImage($offer)
    {
        $roomSleepsKeywords = $this->container->getParameter('hotels')['room_sleeps_keywords'];

        $count = 1;
        $image = "oneperson1.png";

        $type = 'single';
        if (isset($offer['room']) && !empty($offer['room'])) {
            $room = json_decode($offer['room'], true);
            $type = strtolower($room['roomType']);
        }

        if ($type !== 'single') {
            $image = "twoperson1.png";

            $category = preg_replace("/\s*/", '', $offer['category']);
            foreach ($roomSleepsKeywords as $keyword) {
                $keyword = preg_replace("/\s*/", '', $keyword);
                $match   = preg_match_all("/({$keyword})/mi", $category);

                if ($match) {
                    $match = preg_match("/(2|two)/mi", $keyword);
                    if ($match) {
                        $count = 2;
                    } else {
                        $count = 3;
                    }

                    break;
                }
            }
        }

        return array(
            'sleep' => $count,
            'image' => $image
        );
    }

    //*****************************************************************************************
    // Booking Functions
    /**
     * This method creates a HotelBookingCriteria object
     *
     * @param  array  $criteria
     *
     * @return object HotelBookingCriteria instance
     */
    public function getHotelBookingCriteria($criteria)
    {
        $this->convertCurrency = true;

        $computeTotals = false;
        if (isset($criteria['hotelDetailsSerialize'])) {
            $hotelDetails = json_decode($criteria['hotelDetailsSerialize'], true);
        } else {
            $hotelDetails = $criteria['hotelDetails'];
        }

        $hotelBC = new HotelBookingCriteria();
        $hotelBC->setPageSource($this->container->getParameter('hotels')['page_src']['hrs']);
        $hotelBC->setHotelDetails($hotelDetails);
        $hotelBC->setHotelName($hotelDetails['name']);
        $hotelBC->setRefererURL((isset($criteria['refererURL'])) ? $criteria['refererURL'] : '');
        $hotelBC->setUserId((isset($criteria['userId'])) ? $criteria['userId'] : $this->userId);
        $hotelBC->setFromDate((isset($criteria['fromDate'])) ? $criteria['fromDate'] : '');
        $hotelBC->setToDate((isset($criteria['toDate'])) ? $criteria['toDate'] : '');

        $hotelBC->setHotelCode(isset($criteria['hotelKey']) ? $criteria['hotelKey'] : '');
        $hotelBC->setReference(isset($criteria['reference']) ? $criteria['reference'] : '');
        $hotelBC->setTransactionId(isset($criteria['transactionId']) ? $criteria['transactionId'] : '');

        // We sort out the criteria passed by the book form, modification form or room cancellation form
        if ((isset($criteria['offersSelectedSerialize']) && !empty($criteria['offersSelectedSerialize'])) || isset($criteria['reservationKey']) || isset($criteria['cancelReservationKey'])) {
            $hotelId              = $hotelDetails['hotelId'];
            $guestFirstName       = isset($criteria['guestFirstName']) ? $criteria['guestFirstName'] : null;
            $guestLastName        = isset($criteria['guestLastName']) ? $criteria['guestLastName'] : null;
            $guestEmail           = isset($criteria['guestEmail']) ? $criteria['guestEmail'] : null;
            $arrivalTime          = isset($criteria['arrival_time']) ? $criteria['arrival_time'] : null;
            $criteria['ccNumber'] = $this->cleanCCNumber($criteria['ccNumber']);

            $forModificationReservationKey = isset($criteria['reservationKey']) ? $criteria['reservationKey'] : null;
            $forCancellationReservationKey = isset($criteria['cancelReservationKey']) ? $criteria['cancelReservationKey'] : null;

            // The criteria is from modification form or room cancellation form
            if ($forModificationReservationKey || $forCancellationReservationKey) {
                $hotelBooking = $this->getHotelBooking($hotelBC->getReference(), false);

                $hotelBC->setHotelReservationId($hotelBooking->getHotelReservationId());
                $hotelBC->setUserId($hotelBooking->getUserId());
                $hotelBC->setControlNumber($hotelBooking->getControlNumber());
                $hotelBC->setBookingPassword($hotelBooking->getBookingPassword());
                $hotelBC->setFromDate($hotelBooking->getFromDate()->format('Y-m-d'));
                $hotelBC->setToDate($hotelBooking->getToDate()->format('Y-m-d'));

                $hotelBC->setDoubleRooms($hotelBooking->getDoubleRooms());
                $hotelBC->setSingleRooms($hotelBooking->getSingleRooms());

                $hotelBC->setHotelGrandTotal($hotelBooking->getHotelGrandTotal());
                $hotelBC->setHotelCurrency($hotelBooking->getHotelCurrency());
                $hotelBC->setCustomerGrandTotal($hotelBooking->getCustomerGrandTotal());
                $hotelBC->setCustomerCurrency($hotelBooking->getCustomerCurrency());

                $roomOffers   = [];
                $roomCriteria = [];
                foreach ($hotelBooking->getRooms() as $dbRoom) {
                    $dbRoomOfferDetail               = json_decode($dbRoom->getRoomOfferDetail(), true);
                    $dbRoomOfferDetail['room']['id'] = $dbRoom->getReservationKey();

                    $roomOffers[$dbRoom->getReservationKey()] = array(
                        'room' => $dbRoomOfferDetail['room'],
                        'offerDetail' => $dbRoomOfferDetail['offerDetail'],
                        'reservationPersons' => json_decode($dbRoom->getGuests(), true),
                    );

                    $roomCriteria[] = $dbRoomOfferDetail['room'];
                }

                $hotelBC->setRoomCriteria($roomCriteria);

                if ($forModificationReservationKey) {
                    $hotelBC->setTargetRoomReservationKey($forModificationReservationKey);
                    unset($roomOffers[$forModificationReservationKey]['reservationPersons']);
                } else {
                    $hotelBC->setTargetRoomReservationKey($forCancellationReservationKey);
                    unset($roomOffers[$forCancellationReservationKey]);
                }

                $criteria['title']             = $hotelBooking->getOrderer()->getTitle();
                $criteria['firstName']         = $hotelBooking->getOrderer()->getFirstName();
                $criteria['lastName']          = $hotelBooking->getOrderer()->getLastName();
                $criteria['country']           = $hotelBooking->getOrderer()->getCountry();
                $criteria['mobileCountryCode'] = $hotelBooking->getOrderer()->getDialingCode();
                $criteria['mobile']            = $hotelBooking->getOrderer()->getPhone();
                $criteria['email']             = $hotelBooking->getOrderer()->getEmail();

                $ignoreOldGuestInfo = false;
            }
            // The criteria is from book form
            else {
                $roomOffers         = json_decode($criteria['offersSelectedSerialize'], true);
                $computeTotals      = true;
                $ignoreOldGuestInfo = true;
            }

            $hotelBC->setHotelCode($hotelDetails['hotelKey']);
            $hotelBC->setTransactionSourceId(isset($criteria['transactionSourceId']) ? $criteria['transactionSourceId'] : '');
            $hotelBC->setHotelId($hotelId);
            $hotelBC->setReservationMode($criteria['reservationMode']);
            $hotelBC->setAvailRequestSegment(array(
                'from' => $hotelBC->getFromDate(),
                'to' => $hotelBC->getToDate(),
                'reservationRoomOfferDetailCriteria' => $this->getReservationRoomOfferDetailCriteria($roomOffers, $guestFirstName, $guestLastName, $guestEmail, $arrivalTime, $ignoreOldGuestInfo)
            ));

            $wish            = (isset($criteria['wish'])) ? $criteria['wish'] : array();
            $wish            = (isset($criteria['remarks'])) ? $criteria['remarks'] : $wish;
            $reservationWish = (isset($criteria['remarks']['reservationWish'])) ? $criteria['remarks']['reservationWish'] : '';
            $hotelBC->setRemarks($this->getReservationWish($reservationWish, $wish));

            $hotelBC->getOrderer()->setTitle((isset($criteria['title'])) ? $criteria['title'] : '');
            $hotelBC->getOrderer()->setFirstName((isset($criteria['first_name'])) ? $criteria['first_name'] : $criteria['firstName']);
            $hotelBC->getOrderer()->setLastName((isset($criteria['last_name'])) ? $criteria['last_name'] : $criteria['lastName']);
            $hotelBC->getOrderer()->setCountry($criteria['country']);
            $hotelBC->getOrderer()->setDialingCode(((isset($criteria['mobile_country_code'])) ? $criteria['mobile_country_code'] : $criteria['mobileCountryCode']));
            $hotelBC->getOrderer()->setPhone($criteria['mobile']);
            $hotelBC->getOrderer()->setEmail($criteria['email']);

            $hotelBC->setRooms($roomOffers);
            $hotelBC->setDetails($this->hrs->getDataForReservationRequest($criteria, $hotelBC));

            if ($computeTotals) {
                // Reservation Details
                $singleCount                                 = 0;
                $doubleCount                                 = 0;
                $grandTotalPriceInclusiveCustomer            = 0;
                $grandTotalPriceInclusiveCustomerIsoCurrency = '';
                $grandTotalPriceInclusiveHotel               = 0;
                $grandTotalPriceInclusiveHotelIsoCurrency    = '';

                foreach ($hotelBC->getRooms() as $room) {
                    $grandTotalPriceInclusiveCustomer += $room['offerDetail']['totalPriceInclusiveCustomer']['amount'];
                    $grandTotalPriceInclusiveHotel    += $room['offerDetail']['totalPriceInclusiveHotel']['amount'];

                    if (empty($grandTotalPriceInclusiveCustomerIsoCurrency)) {
                        $grandTotalPriceInclusiveCustomerIsoCurrency = $room['offerDetail']['totalPriceInclusiveCustomer']['isoCurrency'];
                        $grandTotalPriceInclusiveHotelIsoCurrency    = $room['offerDetail']['totalPriceInclusiveHotel']['isoCurrency'];
                    }

                    switch ($room['room']['roomType']) {
                        case 'guest1':
                        case 'single':
                            $singleCount++;
                            break;

                        default:
                            $doubleCount++;
                            break;
                    }
                }

                $hotelBC->setDoubleRooms($doubleCount);
                $hotelBC->setSingleRooms($singleCount);

                $hotelBC->setHotelGrandTotal($grandTotalPriceInclusiveHotel);
                $hotelBC->setHotelCurrency($grandTotalPriceInclusiveHotelIsoCurrency);
                $hotelBC->setCustomerGrandTotal($grandTotalPriceInclusiveCustomer);
                $hotelBC->setCustomerCurrency($grandTotalPriceInclusiveCustomerIsoCurrency);
            }
        }
        // We sort out the selected offers from hotel details page
        elseif (isset($criteria['totalNumOffers']) && !empty($criteria['totalNumOffers'])) {
            $hotelId = $this->hsRepo->getHotelSourceField('hotelId', array('sourceId', $criteria['hotelKey']));
            $hotelBC->setHotelId($hotelId);

            $offersSelected = array();
            for ($x = 1; $x <= $criteria['totalNumOffers']; $x++) {
                $numRoomsSelected = $criteria['offer_select_'.$x];

                if ($numRoomsSelected != 0) {
                    $offerDetailJSON = $criteria['offerDetail_'.$x];
                    $offerDetail     = json_decode($offerDetailJSON, true);
                    $room            = json_decode($criteria['room_'.$x], true);

                    for ($i = 1; $i <= $numRoomsSelected; $i++) {
                        $hotelRoomOffer = $this->hrs->getRoomStayDetails($offerDetail, $room);
                        $hotelRoomOffer->setBookableInfo($room);
                        $hotelRoomOffer->setRoomOfferXml($offerDetailJSON);

                        $offersSelected[] = $hotelRoomOffer;
                    }
                }
            }
            $hotelBC->setRooms($offersSelected);

            $hotelBC->setTransactionId($this->utils->GUID());
            $hotelBC->setReference(bin2hex(openssl_random_pseudo_bytes(16)));
        }

        return $hotelBC;
    }

    /**
     * This method retrieves the necessary data for displaying hotel booking form
     *
     * @param HotelServiceConfig   $serviceConfig The action data.
     * @param HotelBookingCriteria $hotelBC       The HotelBookingCriteria object.
     *
     * @return Array The data needed for reservation and for displaying guest and payment form
     */
    public function hotelBook(HotelServiceConfig $serviceConfig, HotelBookingCriteria $hotelBC)
    {
        $this->convertCurrency = true;

        list($hotelDetails, $offersSelected, $reservationDetails) = $this->prebook($hotelBC);

        $toreturn                  = array();
        $toreturn['transactionId'] = $hotelBC->getTransactionId();
        $toreturn['reference']     = $hotelBC->getReference();
        $toreturn['fromDate']      = $hotelBC->getFromDate();
        $toreturn['toDate']        = $hotelBC->getToDate();
        $toreturn['refererURL']    = $hotelBC->getRefererURL();

        $toreturn['ccValidityInfo'] = $this->utils->getCCValidityOptions();
        $toreturn['nightsCount']    = $this->utils->computeNights($hotelBC->getFromDate(), $hotelBC->getToDate());

        $toreturn['countryList']           = $this->container->get('CmsCountriesServices')->getCountryList();
        $toreturn['mobileCountryCodeList'] = $this->container->get('CmsCountriesServices')->getMobileCountryCodeList();

        $toreturn['hotelDetails'] = $hotelDetails;

        $this->logger->addHotelActivityLog('HRS', 'offer_selected', $this->userId, array(
            "offersSelectedCount" => count($offersSelected),
            "hotelName" => $toreturn['hotelDetails']['name'],
            "hotelId" => $hotelBC->getHotelId(),
            "hotelKey" => $hotelBC->getHotelCode(),
            "fromDate" => $hotelBC->getFromDate(),
            "toDate" => $hotelBC->getToDate()
        ));

        $loggedInUserInfo = $this->container->get('ApiUserServices')->tt_global_get('userInfo');

        // get logged in user info
        if ($loggedInUserInfo) {
            $offersSelected[0]['reservationPersons'][0]['bedType'] = 'regularBed';

            $toreturn['orderer']['firstName']                        = $offersSelected[0]['reservationPersons'][0]['firstName'] = $loggedInUserInfo['fname'];
            $toreturn['orderer']['lastName']                         = $offersSelected[0]['reservationPersons'][0]['lastName']  = $loggedInUserInfo['lname'];
            $toreturn['orderer']['email']                            = $offersSelected[0]['reservationPersons'][0]['email']     = $loggedInUserInfo['email'];
            $toreturn['orderer']['title']                            = ($loggedInUserInfo['gender'] == 'F') ? 1 : 0;
            $toreturn['orderer']['iso3Country']                      = $this->container->get('CmsCountriesServices')->getIso3CountryByCode($loggedInUserInfo['country']);
        } else {
            $toreturn['orderer']['iso3Country'] = $this->container->get('CmsCountriesServices')->getIso3CountryByIp($this->container->get('request')->getClientIp());
        }

        $toreturn['offersSelected']     = $offersSelected;
        $toreturn['reservationDetails'] = $reservationDetails;

        return $toreturn;
    }

    /**
     * prebook
     *
     * @param $hotelBC
     *
     * @return
     */
    public function prebook($hotelBC)
    {
        $hotelDetails = array_merge($this->getHotelDBInformation($hotelBC->getHotelId(), 3), $hotelBC->getHotelDetails());

        $hotelBooking = new HotelBooking;
        $hotelBooking->setReference($hotelBC->getReference());
        $hotelBooking->setFromDate($hotelBC->getFromDate());
        $hotelBooking->setToDate($hotelBC->getToDate());

        $roomsToDisplay = array();
        $offersSelected = array();
        foreach ($hotelBC->getRooms() as $hotelRoomOffer) {
            $offersSelected[] = array(
                'room' => $hotelRoomOffer->getBookableInfo(),
                'offerDetail' => json_decode($hotelRoomOffer->getRoomOfferXml(), true),
                'details' => $this->getRoomOfferDetail('BOOK_FORM', $hotelRoomOffer),
                'roomTypeInfo' => $hotelRoomOffer->getRoomTypeInfo()
            );
            $roomsToDisplay[] = $this->getReservedRoomDetails('BOOK_FORM', $hotelRoomOffer, $hotelBooking, $hotelDetails);
        }

        $reservationDetails = $this->getReservationDetails('BOOK_FORM', $hotelBooking, $hotelDetails, $roomsToDisplay);

        return array($hotelDetails, $offersSelected, $reservationDetails);
    }

    /**
     * process Hotel Reservation Request
     *
     * @param $serviceConfig
     * @param $hotelBC
     *
     * @return
     */
    public function processHotelReservationRequest(HotelServiceConfig $serviceConfig, HotelBookingCriteria $hotelBC)
    {
        $this->convertCurrency = false;

        $this->initializeService($serviceConfig);

        $toreturn  = array();
        $toNotify  = array();
        $recipient = array('email' => $hotelBC->getOrderer()->getEmail(), 'name' => $hotelBC->getOrderer()->getFirstName());

        $hotelReservation = $this->reservationRepo->findOneByReference($hotelBC->getReference());

        if (empty($hotelReservation)) {
            $responseData = $this->performAPIReservation($hotelBC);

            if (!empty($responseData['error'])) {
                return $responseData;
            } else {
                $hotelReservation = $this->reservationRepo->findOneBy(array(
                    'reservationProcessKey' => $responseData['reservationProcessKey'],
                    'reservationProcessPassword' => $responseData['reservationProcessPassword']
                ));
                if (empty($hotelReservation)) {
                    // Save reservation
                    $hotelBC->setReservationStatus($this->container->getParameter('hotels')['reservation_confirmed']);
                    $hotelReservation = $this->saveReservation($hotelBC);

                    $hotelReservation->setReservationProcessKey($responseData['reservationProcessKey']);
                    $hotelReservation->setReservationProcessPassword($responseData['reservationProcessPassword']);
                    $hotelReservation->setDetails(null);
                    $this->em->persist($hotelReservation);
                    $this->em->flush();

                    //Save room reservation
                    foreach ($responseData['roomOfferDetails'] as $roomOfferDetail) {
                        $roomData = array(
                            'hotelReservationId' => $hotelReservation->getId(),
                            'reservationProcessKey' => $responseData['reservationProcessKey'],
                            'reservationProcessPassword' => $responseData['reservationProcessPassword'],
                            'reservationKey' => $roomOfferDetail['reservationKey'],
                            'hotelRoomPrice' => $roomOfferDetail['offerDetail']['totalPriceInclusiveHotel']['amount'],
                            'customerRoomPrice' => $roomOfferDetail['offerDetail']['totalPriceInclusiveCustomer']['amount'],
                            'guests' => json_encode($roomOfferDetail['reservationPersons']),
                            'roomStatus' => $this->container->getParameter('hotels')['reservation_confirmed'],
                            'roomOfferDetail' => json_encode([
                                'room' => $roomOfferDetail['room'],
                                'offerDetail' => $roomOfferDetail['offerDetail'],
                                'reservationWish' => $hotelBC->getRemarks(),
                                'reservationMode' => $hotelBC->getReservationMode()
                            ])
                        );
                        $this->roomRepo->saveRoomReservation($roomData);

                        if (isset($roomOfferDetail['reservationPersons'][0]['email']) && !empty($roomOfferDetail['reservationPersons'][0]['email'])) {
                            $toNotify[] = array(
                                'email' => $roomOfferDetail['reservationPersons'][0]['email'],
                                'name' => $roomOfferDetail['reservationPersons'][0]['firstName'],
                            );
                        }
                    }
                    // add orderer as email recipient
                    $toNotify[] = $recipient;
                }
            }
        }

        $reservationInfo = $this->getReservationInformation($hotelReservation->getReference(), 'RESERVATION_CONFIRMATION');

        //Send confirmation email
        if (!empty($toNotify)) {
            $this->sendConfirmationEmail('reservation', $toNotify, $reservationInfo);
        }

        if ($this->isRest) {
            $toreturn = $this->getDataForMobile($reservationInfo);
        } else {
            $toreturn = $this->setupCongratsPage($reservationInfo);
        }
        return $toreturn;
    }

    /**
     * This method calls HRS hotelReservation API Call
     *
     * @param  HotelBookingCriteria $hotelBC
     *
     * @return Array                Error details or email notification recipients
     */
    private function performAPIReservation(HotelBookingCriteria $hotelBC)
    {
        $toreturn          = array();
        $toreturn['error'] = '';

        $response = $this->hrs->confirmReservation($hotelBC);

        if (!$response->isSuccess()) {
            $toreturn['refererURL'] = $hotelBC->getRefererURL();
            $toreturn['error']      = $response->getError();

            if ($this->isRest) {
                $params              = $hotelBC->getDetails();
                $params['hotelName'] = $hotelBC->getHotelName();

                $this->logger->cleanParams($params);
                $toreturn = array('params' => $params, 'error' => $response->getError());
            }
        } else {
            $toreturn = $response->getData();
        }

        return $toreturn;
    }

    /**
     * get Reservation Room Offer Detail Criteria
     *
     * @param $offers_selected
     * @param $guestFirstName
     * @param $guestLastName
     * @param $guestEmail
     * @param $arrival_time
     * @param $ignoreOldGuestInfo
     *
     * @return
     */
    private function getReservationRoomOfferDetailCriteria($offers_selected, $guestFirstName = null, $guestLastName = null, $guestEmail = null, $arrival_time = null, $ignoreOldGuestInfo = false)
    {
        $return  = array();
        $bedType = 'regularBed';
        $index   = 0;

        foreach ($offers_selected as $offer) {
            $reservationPersons = array();

            if (!$ignoreOldGuestInfo && !empty($offer['reservationPersons'])) {
                foreach ($offer['reservationPersons'] as $person) {
                    if (!empty($person['firstName']) && !empty($person['lastName'])) {
                        $reservationPersons[] = $person;
                    }
                }
            } else {
                if (!empty($guestFirstName[$index]) && !empty($guestLastName[$index])) {
                    $reservationPersons[] = array(
                        'firstName' => $guestFirstName[$index],
                        'lastName' => $guestLastName[$index],
                        'bedType' => $bedType,
                        'email' => (isset($guestEmail[$index]) && !empty($guestEmail[$index])) ? $guestEmail[$index] : ''
                    );
                    $index++;
                }
            }

            $criteria = array(
                'reservationPersons' => $reservationPersons,
                'room' => $offer['room'],
                'offerDetail' => $offer['offerDetail']
            );

            if (!empty($arrival_time)) {
                $criteria['checkInTime'] = $arrival_time;
            }

            $return[] = $criteria;
        }

        return $return;
    }

    /**
     * Wrapper method to save hotel booking to database (hotel_reservation)
     *
     * @param $hotelBC
     * @param $id
     *
     * @return: HotelReservation object
     */
    private function saveReservation($hotelBC, $id = null)
    {
        $hotelBC->setControlNumber(null);

        // Add the amountFBC and amountSBC
        if (!empty($hotelBC->getHotelGrandTotal()) && !empty($hotelBC->getHotelCurrency())) {
            $amountFBC = $this->currencyService->exchangeAmount($hotelBC->getHotelGrandTotal(), $hotelBC->getHotelCurrency(), $this->container->getParameter('FBC_CODE'));
            $amountSBC = $this->currencyService->exchangeAmount($hotelBC->getHotelGrandTotal(), $hotelBC->getHotelCurrency(), $this->container->getParameter('SBC_CODE'));

            if ($amountFBC) {
                $hotelBC->setAmountFbc($amountFBC);
            }
            if ($amountSBC) {
                $hotelBC->setAmountSbc($amountSBC);
            }
        }

        $hotelReservation = $this->reservationRepo->saveReservation($hotelBC, $id);

        return $hotelReservation;
    }

    /**
     * This method prepares the data needed for the congrats page
     *
     * @param $reservationDetails
     *
     * @return data
     */
    public function setupCongratsPage($reservationDetails)
    {
        $toreturn = array();

        if (isset($reservationDetails['error'])) {
            $toreturn = $reservationDetails;
        } else {
            // Hotel Details
            $toreturn['hotelDetails'] = array(
                'hotelId' => $reservationDetails['hotelDetails']['hotelId'],
                'hotelKey' => $reservationDetails['hotelDetails']['hotelKey'],
                'email' => $reservationDetails['hotelDetails']['email'],
                'name' => $reservationDetails['hotelDetails']['name'],
                'category' => $reservationDetails['hotelDetails']['category'],
                'address' => $reservationDetails['hotelDetails']['address'],
                'phone' => $reservationDetails['hotelDetails']['phone'],
                'fax' => $reservationDetails['hotelDetails']['fax'],
                'checkInEarliest' => $reservationDetails['hotelDetails']['checkInEarliest'],
                'checkOutLatest' => $reservationDetails['hotelDetails']['checkOutLatest'],
                'hotelNameURL' => $reservationDetails['hotelDetails']['hotelNameURL'],
                'gpsLatitude' => $reservationDetails['hotelDetails']['gpsLatitude'],
                'gpsLongitude' => $reservationDetails['hotelDetails']['gpsLongitude'],
                'acceptedCreditCards' => $reservationDetails['hotelDetails']['acceptedCreditCards'],
                'creditCardDetails' => $this->utils->getCCDetails($reservationDetails['hotelDetails']['acceptedCreditCards']),
                'creditCardSecurityCodeRequired' => $reservationDetails['hotelDetails']['creditCardSecurityCodeRequired']
            );
            if (isset($reservationDetails['hotelDetails']['facilities'])) {
                $toreturn['hotelDetails']['facilities'] = $reservationDetails['hotelDetails']['facilities'];
            }
            if (isset($reservationDetails['hotelDetails']['amenities'])) {
                $toreturn['hotelDetails']['amenities'] = $reservationDetails['hotelDetails']['amenities'];
            }
            unset($reservationDetails['hotelDetails']);

            // Entity
            $toreturn['entity_type'] = $this->container->getParameter('SOCIAL_ENTITY_HOTEL');
            $toreturn['hideAddBag']  = (empty($this->hoteUserId)) ? 0 : 1;

            // Orderer
            $toreturn['ordererDetails'] = $reservationDetails['ordererDetails'];

            // Reservation Details
            $toreturn['reservationDetails'] = $reservationDetails['reservationDetails'];

            // Rooms Info
            $toreturn['roomsToDisplay'] = $reservationDetails['roomsToDisplay'];

            // Email
            $toreturn['reference']       = $reservationDetails['reservationDetails']['reference'];
            $toreturn['reservationDate'] = $reservationDetails['reservationDetails']['reservationDate'];
            $toreturn['recipient']       = array('email' => $reservationDetails['ordererDetails']->getEmail(), 'name' => $reservationDetails['ordererDetails']->getFirstName());
        }
        return $toreturn;
    }

    /**
     * This method prepares data for mobile.
     *
     * @param  Array $dataSource The reservation details and other hotel information.
     *
     * @return Array The data formatted for use for mobile.
     */
    private function getDataForMobile($dataSource)
    {
        if (isset($dataSource['error'])) {
            return array('error' => $dataSource['error']);
        } else {
            $hotelDetails = array(
                'hotelKey' => $dataSource['hotelDetails']['hotelKey'],
                'hotelId' => $dataSource['hotelDetails']['hotelId'],
                'name' => $dataSource['hotelDetails']['name'],
                'hotelNameCleanTitle' => $dataSource['hotelDetails']['hotelNameURL'],
                'category' => $dataSource['hotelDetails']['category'],
                'address' => $dataSource['hotelDetails']['address'],
                'gpsLatitude' => $dataSource['hotelDetails']['gpsLatitude'],
                'gpsLongitude' => $dataSource['hotelDetails']['gpsLongitude'],
                'checkInEarliest' => $dataSource['hotelDetails']['checkInEarliest'],
                'checkOutLatest' => $dataSource['hotelDetails']['checkOutLatest'],
                'email' => $dataSource['hotelDetails']['email'],
                'phone' => $dataSource['hotelDetails']['phone'],
                'fax' => $dataSource['hotelDetails']['fax'],
                'acceptedCreditCards' => $dataSource['hotelDetails']['acceptedCreditCards'],
                'creditCardDetails' => $this->utils->getCCDetails($dataSource['hotelDetails']['acceptedCreditCards'], $this->isRest),
                'creditCardSecurityCodeRequired' => $dataSource['hotelDetails']['creditCardSecurityCodeRequired'],
                'latitude' => $dataSource['hotelDetails']['latitude'],
                'longitude' => $dataSource['hotelDetails']['longitude'],
            );

            return array(
                'reservationDetails' => $dataSource['reservationDetails'],
                'hotelDetails' => $hotelDetails,
                'roomsToDisplay' => $dataSource['roomsToDisplay'],
            );
        }
    }

    //*****************************************************************************************
    // Booking Information Functions
    /**
     * This method retrieves the booking details.
     *
     * @param  HotelServiceConfig $serviceConfig The action data.
     * @param  String             $reference     The authentication token.
     *
     * @return Array              The booking details.
     */
    public function bookingDetails(HotelServiceConfig $serviceConfig, $reference)
    {
        $this->initializeService($serviceConfig);

        $this->logger->addHotelActivityLog('HRS', 'BOOKING_DETAILS', $this->userId, array("reference" => $reference));

        $bookingInfo = $this->getReservationInformation($reference, 'BOOKING_DETAILS', null, true);

        if ($this->isRest) {
            $bookingInfo = $this->getDataForMobile($bookingInfo);
        }

        return $bookingInfo;
    }

    /**
     * This method retrieves the reservation information.
     *
     * @param  String       $reference            The reservation reference.
     * @param  String       $requestingPage       The requesting page.
     * @param  integer      $imageIndex           The image assigned to our hotelDetails as per specified image index.
     * @param  boolean      $includeCanceledRooms If to include canceled rooms or not.
     *
     * @return Array        The reservation information.
     */
    public function getReservationInformation($reference, $requestingPage, $imageIndex = null, $includeCanceledRooms = true)
    {
        $return = array();

        if (empty($reference)) {
            $return['error'] = $this->translator->trans('Missing authentication token');
        } else {
            //Fetch the reservation record
            $hotelBooking = $this->getHotelBooking($reference, $includeCanceledRooms);
            if (empty($hotelBooking)) {
                $return['error'] = $this->translator->trans('Hotel reservation not found.');
            } else {
                $response = $this->hrs->getBookingRecord($hotelBooking, $includeCanceledRooms);
                if (!$response->isSuccess()) {
                    $return['error'] = $response->getError();
                } else {
                    $responseData     = $response->getData();
                    $hotelDetailsType = ($this->isRest) ? 'fromMobile' : 'basicOnly';

                    // Only one hotel can be processed per reservation, so we do not need to loop thru the entire room details to get hotel details
                    $return['hotelDetails'] = $this->getHotelInformation($responseData['hotelDetails'], $hotelBooking->getHotelId(), $hotelDetailsType, 3, $imageIndex, 0);

                    // Only one orderer per reservation
                    $return['ordererDetails'] = $hotelBooking->getOrderer();

                    // Only one credit card per reservation
                    // $return['creditCardDetails'] = $responseData['creditCardDetails'];

                    $roomsToDisplay = array();
                    foreach ($responseData['reservedRoomInfo'] as $apiHotelRoom) {
                        $roomsToDisplay[$apiHotelRoom->getReservationKey()] = $this->getReservedRoomDetails($requestingPage, $apiHotelRoom, $hotelBooking, $return['hotelDetails']);
                    }

                    $return['reservationDetails'] = $this->getReservationDetails($requestingPage, $hotelBooking, $return['hotelDetails'], $roomsToDisplay);

                    if (in_array($requestingPage, array('MODIFICATION'))) {
                        $return['reservedRoomInfo'] = $responseData['reservedRoomInfo'];
                    } else {
                        if (in_array($requestingPage, array('CANCELLATION'))) {
                            $return['hotelBooking'] = $hotelBooking;
                        }

                        $return['roomsToDisplay'] = $roomsToDisplay;
                    }
                }
            }
        }

        return $return;
    }

    /**
     * Get a hotel booking
     *
     * @param  $reference
     * @param  $includeCanceledRooms
     *
     * @return
     */
    private function getHotelBooking($reference, $includeCanceledRooms)
    {
        $reservation = $this->reservationRepo->getHotelReservation($reference);

        if (empty($reservation)) {
            return null;
        } else {
            $hotelKey = $this->hsRepo->getHotelSourceField('sourceId', array('hotelId', $reservation->getHotelId()));

            $hotelBooking = new HotelBooking();
            $hotelBooking->setUserId($reservation->getUserId());
            $hotelBooking->setHotelReservationId($reservation->getId());
            $hotelBooking->setReference($reservation->getReference());
            $hotelBooking->setControlNumber($reservation->getReservationProcessKey());
            $hotelBooking->setBookingPassword($reservation->getReservationProcessPassword());

            $hotelBooking->setDoubleRooms($reservation->getDoubleRooms());
            $hotelBooking->setSingleRooms($reservation->getSingleRooms());
            $hotelBooking->setHotelGrandTotal($reservation->getHotelGrandTotal());
            $hotelBooking->setHotelCurrency($reservation->getHotelCurrency());
            $hotelBooking->setCustomerGrandTotal($reservation->getCustomerGrandTotal());
            $hotelBooking->setCustomerCurrency($reservation->getCustomerCurrency());

            // Hotel Info
            $hotelBooking->setHotelId($reservation->getHotelId());
            $hotelBooking->setHotelCode($hotelKey);

            // Orderer
            $orderer = new HotelBookingOrderer();
            $orderer->setTitle($reservation->getTitle());
            $orderer->setFirstName($reservation->getFirstName());
            $orderer->setLastName($reservation->getLastName());
            $orderer->setCountry($reservation->getCountry());
            $orderer->setPhone($reservation->getMobile());
            $orderer->setEmail($reservation->getEmail());
            $orderer->setDialingCode($reservation->getDialingCode());
            $hotelBooking->setOrderer($orderer);

            // Dates
            $hotelBooking->setBookingDate($reservation->getCreationDate());
            $hotelBooking->setFromDate($reservation->getFromDate());
            $hotelBooking->setToDate($reservation->getToDate());

            // Active Rooms
            $reservedRoomInfoFromDB = array();
            foreach ($this->roomRepo->getActiveRooms($hotelBooking->getHotelReservationId()) as $dbHotelRoom) {
                $reservedRoomInfoFromDB[$dbHotelRoom->getReservationKey()] = $dbHotelRoom;
            }
            $hotelBooking->setActiveRoomsCount(count($reservedRoomInfoFromDB));

            // Cancelled rooms
            if ($includeCanceledRooms) {
                foreach ($this->roomRepo->getCanceledRooms($hotelBooking->getHotelReservationId()) as $dbHotelRoom) {
                    $reservedRoomInfoFromDB[$dbHotelRoom->getReservationKey()] = $dbHotelRoom;
                }
            }
            $hotelBooking->setRooms($reservedRoomInfoFromDB);

            // Reservation wish
            $roomOfferDetail = reset($reservedRoomInfoFromDB);
            $roomOfferDetail = json_decode($roomOfferDetail->getRoomOfferDetail(), true);
            $hotelBooking->setRemarks((isset($roomOfferDetail['reservationWish'])) ? $roomOfferDetail['reservationWish'] : '');
            $hotelBooking->setReservationMode((isset($roomOfferDetail['reservationMode'])) ? $roomOfferDetail['reservationMode'] : 'guaranteed');

            // Reservation Status
            $hotelBooking->setReservationStatus($reservation->getReservationStatus());

            return $hotelBooking;
        }
    }

    /**
     * This method gives the cancellation information message.
     *
     * @param  Boolean $isCanceled
     * @param  Boolean $isCancelable
     *
     * @return String  The cancellation information message.
     */
    private function getReservationCancellationInfo($isCanceled, $isCancelable)
    {
        if ($isCanceled) {
            return $this->translator->trans('Reservation cancelled.');
        } elseif ($isCancelable) {
            return $this->translator->trans('Reservation cancelable.');
        } else {
            return $this->translator->trans('Your reservation includes non-cancellable rooms and hence cannot be cancelled.');
        }
    }

    /**
     * Get reservation details
     *
     * @param  $requestingPage
     * @param  $hotelBooking
     * @param  $hotelDetails
     * @param  $reservationOffers
     *
     * @return
     */
    private function getReservationDetails($requestingPage, HotelBooking $hotelBooking, $hotelDetails, $reservationOffers)
    {
        $computeTotals        = (!in_array($requestingPage, array('MODIFICATION')));
        $checkInOutDateFormat = (in_array($requestingPage, array('BOOK_FORM'))) ? 'long2' : 'long';

        $details = array();

        $details['reservationId']              = $hotelBooking->getHotelReservationId();
        $details['reservationDate']            = $this->utils->formatDate($hotelBooking->getBookingDate(), 'long');
        $details['reference']                  = $hotelBooking->getReference();
        $details['reservationProcessKey']      = $hotelBooking->getControlNumber();
        $details['reservationProcessPassword'] = $hotelBooking->getBookingPassword();
        $details['hotelKey']                   = $hotelBooking->getHotelCode();

        $details['singleCount']      = $hotelBooking->getSingleRooms();
        $details['doubleCount']      = $hotelBooking->getDoubleRooms();
        $details['nbrRooms']         = count($reservationOffers);
        $details['activeRoomsCount'] = $hotelBooking->getActiveRoomsCount();
        $details['checkIn']          = $this->utils->formatDate($hotelBooking->getFromDate(), $checkInOutDateFormat);
        $details['checkOut']         = $this->utils->formatDate($hotelBooking->getToDate(), $checkInOutDateFormat);
        $details['nbrNights']        = $this->utils->computeNights($hotelBooking->getFromDate(), $hotelBooking->getToDate());

        $details['canceled']           = true;
        $details['cancelable']         = true;
        $details['prePaymentRequired'] = false;

        $guaranteedReservationOnly = false;
        $creditCardReservationOnly = false;

        $grandTotalPriceInclusiveCustomer            = 0;
        $grandTotalPriceInclusiveCustomerIsoCurrency = '';
        $grandTotalPriceInclusiveHotel               = 0;
        $grandTotalPriceInclusiveHotelIsoCurrency    = '';

        foreach ($reservationOffers as $room) {
            $details['canceled']           = $details['canceled'] && !empty($room['cancellation']);
            $details['cancelable']         = $details['cancelable'] && ($room['cancelable'] || (!$room['cancelable'] && !empty($room['cancellation'])));
            $details['prePaymentRequired'] = $details['prePaymentRequired'] || $room['prepayRate'];

            $guaranteedReservationOnly = $guaranteedReservationOnly || $room['guaranteedReservationOnly'] || (isset($room['reservationMode']) && $room['reservationMode'] == 'guaranteed');
            $creditCardReservationOnly = $creditCardReservationOnly || $room['creditCardReservationOnly'] || $room['prepayRate'];

            if ($computeTotals && (($details['activeRoomsCount'] > 0 && empty($room['cancellation'])) || ($details['activeRoomsCount'] == 0))) {
                $grandTotalPriceInclusiveCustomer += $room['totalPriceInclusiveCustomer']['amount'];
                $grandTotalPriceInclusiveHotel    += $room['totalPriceInclusiveHotel']['amount'];

                if (empty($grandTotalPriceInclusiveCustomerIsoCurrency)) {
                    $grandTotalPriceInclusiveCustomerIsoCurrency = $room['totalPriceInclusiveCustomer']['isoCurrency'];
                    $grandTotalPriceInclusiveHotelIsoCurrency    = $room['totalPriceInclusiveHotel']['isoCurrency'];
                }
            }
        }

        $details['reservationMode'] = ($guaranteedReservationOnly) ? 'guaranteed' : 'standard';
        // $details['reservationWishDecoded'] = $hotelBooking->getRemarks(); //$this->decodeReservationWish($hotelReservation['reservationWish'], true);

        if ($computeTotals) {
            $forceConvert = (in_array($requestingPage, array('BOOK_FORM')));

            $details['grandTotalPriceInclusiveCustomerAmount']   = $grandTotalPriceInclusiveCustomer;
            $details['grandTotalPriceInclusiveCustomerCurrency'] = $grandTotalPriceInclusiveCustomerIsoCurrency;
            $details['grandTotalPriceInclusiveHotelAmount']      = $grandTotalPriceInclusiveHotel;
            $details['grandTotalPriceInclusiveHotelCurrency']    = $grandTotalPriceInclusiveHotelIsoCurrency;

            $details['grandTotalPriceInclusiveCustomerDisplay'] = $this->getDisplayPrice(array('isoCurrency' => $grandTotalPriceInclusiveCustomerIsoCurrency, 'amount' => $grandTotalPriceInclusiveCustomer), true, false, true);
            $details['grandTotalPriceInclusiveHotelDisplay']    = $this->getDisplayPrice(array('isoCurrency' => $grandTotalPriceInclusiveHotelIsoCurrency, 'amount' => $grandTotalPriceInclusiveHotel), true, false, $forceConvert);

            // Payment Details
            $totalPrepayment             = 0;
            $totalCancellationCost       = 0;
            $earlistCancellationDeadline = '';
            foreach ($reservationOffers as $room) {
                if (isset($room['prepaymentFee'])) {
                    $totalPrepayment += $this->getPriceFromString($room['prepaymentFee']);
                }
                if (isset($room['cancellationFee']) && $room['cancellationFee'] != 'FREE') {
                    $totalCancellationCost += $this->getPriceFromString($room['cancellationFee']);
                }
                if ($room['roomStatus'] != $this->container->getParameter('hotels')['reservation_canceled'] && $room['cancelable'] && !empty($room['cancellationDeadline'])) {
                    $roomCancellationDeadline = date_create($room['cancellationDeadline']);
                    if (empty($earlistCancellationDeadline) || $roomCancellationDeadline < $earlistCancellationDeadline) {
                        $earlistCancellationDeadline = $roomCancellationDeadline;
                    }
                }
            }
            $details['prepaymentFee'] = $this->getDisplayPrice(array('isoCurrency' => $grandTotalPriceInclusiveHotelIsoCurrency, 'amount' => $totalPrepayment));
            if ($totalPrepayment > 0) {
                $details['totalPrepayment'][] = $this->translator->trans('Total prepayment of ').$this->getDisplayPrice(array('amount' => $totalPrepayment, 'isoCurrency' => $grandTotalPriceInclusiveHotelIsoCurrency), false).$this->translator->trans(' to the hotel with immediate charge to your credit card.');
            } else {
                $details['totalPrepayment'][] = $this->translator->trans('No deposit will be charged. ');
            }
            if ($totalCancellationCost > 0) {
                $details['totalCancellationCost'][] = $this->translator->trans('Cancellation not free-of-charge.');
            }
            $details['totalCancellationCost'][] = $this->translator->trans('Total cancellation fee of ').$this->getDisplayPrice(array('amount' => $totalCancellationCost, 'isoCurrency' => $grandTotalPriceInclusiveHotelIsoCurrency), false);
            if (!empty($earlistCancellationDeadline)) {
                $freeCancellationWithIn = $this->utils->calculateDaysToDate($earlistCancellationDeadline);
                if ($freeCancellationWithIn) {
                    $details['freeCancellationWithIn'] = $freeCancellationWithIn;
                }
            }
        }

        $details['cancellation'] = $this->getReservationCancellationInfo($details['canceled'], $details['cancelable']);

        $details['ccRequired']     = $guaranteedReservationOnly || $creditCardReservationOnly;
        $details['ccCodeRequired'] = $hotelDetails['creditCardSecurityCodeRequired'];

        $details['reservationStatus'] = ($details['canceled']) ? 'Canceled' : 'Confirmed';

        return $details;
    }

    /**
     * This method gives the reservation mode info message.
     *
     * @param  String $reservationMode The mode retrieved from the API response.
     *
     * @return String The reservation mode info message.
     */
    private function getReservationModeInfo($reservationMode)
    {
        switch ($reservationMode) {
            case 'deposit':
            case 'guaranteed':
                $info = $this->translator->trans('Guaranteed reservation (Arrival before or after 6 p.m. local time)');
                break;

            case 'standard':
                $info = $this->translator->trans('Standard reservation - Arrival before 6 p.m. (local time) required. There is no right to the room if you arrive later.');
                break;
            default:
                $info = $reservationMode;
                break;
        }

        return $info;
    }

    /**
     * Get reservation payment details
     *
     * @param  $rooms
     *
     * @return
     */
    private function getReservationPaymentDetails($rooms)
    {
        $totalPrepayment             = 0;
        $totalCancellationCost       = 0;
        $earlistCancellationDeadline = '';
        $currency                    = '';

        $reservationDetails = array();

        foreach ($rooms as $room) {
            if (isset($room['prepaymentDetails']['price'])) {
                $p = explode(' ', $room['prepaymentDetails']['price']);
                if (isset($p[1])) {
                    $totalPrepayment += floatval(preg_replace('/[^\d.]/', '', $p[1]));
                }
            }
            if (isset($room['cancellationDetails']['price']) && $room['cancellationDetails']['price'] != 'FREE') {
                $c = explode(' ', $room['cancellationDetails']['price']);
                if (isset($c[1])) {
                    $totalCancellationCost += floatval(preg_replace('/[^\d.]/', '', $c[1]));
                }
            }
            if ($room['reservationStatus'] != 'canceled' && $room['cancelable'] && !empty($room['cancellationDeadline'])) {
                $roomCancellationDeadline = date_create($room['cancellationDeadline']);
                if (empty($earlistCancellationDeadline) || $roomCancellationDeadline < $earlistCancellationDeadline) {
                    $earlistCancellationDeadline = $roomCancellationDeadline;
                }
            }
            if (empty($currency)) {
                $currency = $room['totalPriceInclusiveHotel']['isoCurrency'];
            }
        }
        if ($totalPrepayment > 0) {
            $reservationDetails['totalPrepayment'][] = $this->translator->trans('Total prepayment of ').$this->getDisplayPrice(array('amount' => $totalPrepayment, 'isoCurrency' => $currency), false).$this->translator->trans(' to the hotel with immediate charge to your credit card.');
        } else {
            $reservationDetails['totalPrepayment'][] = $this->translator->trans('No deposit will be charged. ');
        }

        if ($totalCancellationCost > 0) {
            $reservationDetails['totalCancellationCost'][] = $this->translator->trans('Cancellation not free-of-charge.');
        }
        $reservationDetails['totalCancellationCost'][] = $this->translator->trans('Total cancellation fee of ').$this->getDisplayPrice(array('amount' => $totalCancellationCost, 'isoCurrency' => $currency), false);

        if (!empty($earlistCancellationDeadline)) {
            $freeCancellationWithIn = $this->utils->calculateDaysToDate($earlistCancellationDeadline);
            if ($freeCancellationWithIn) {
                $reservationDetails['freeCancellationWithIn'] = $freeCancellationWithIn;
            }
        }

        return $reservationDetails;
    }

    /**
     * This method retrieves the reserved room details.
     *
     * @param  String       $requestingPage
     * @param  HotelRoom    $apiHotelRoom
     * @param  HotelBooking $hotelBooking   The HotelReservation object.
     * @param  Array        $hotelDetails   The hotel details
     *
     * @return Array        The room details.
     */
    private function getReservedRoomDetails($requestingPage, HotelRoom $apiHotelRoom, HotelBooking $hotelBooking, $hotelDetails)
    {
        $roomOfferDetail = $this->getRoomOfferDetail($requestingPage, $apiHotelRoom);

        $roomInfo = array();

        // Room reservation number
        $roomInfo['reservationKey'] = $apiHotelRoom->getReservationKey();

        $roomInfo['name']              = $apiHotelRoom->getName(); // $this->getOfferName($roomOfferDetail['offerDetail']);
        $roomInfo['roomHeadline']      = $apiHotelRoom->getHeader()['roomHeadline'];
        $roomInfo['description']       = $apiHotelRoom->getDescription();
        $roomInfo['roomTypeInfo']      = $apiHotelRoom->getRoomTypeInfo(); //$this->getRoomType($roomOfferDetail['room']);
        $roomInfo['maxPersons']        = $apiHotelRoom->getMaxRoomCount();
        $roomInfo['reservationWish']   = $hotelBooking->getRemarks();
        $roomInfo['typeOfReservation'] = $this->getReservationModeInfo($hotelBooking->getReservationMode());

        // Check-in / Check-out
        $roomInfo['from']        = $this->utils->formatDate($hotelBooking->getFromDate());
        $roomInfo['to']          = $this->utils->formatDate($hotelBooking->getToDate());
        $roomInfo['checkIn']     = $this->utils->formatDate($hotelBooking->getFromDate(), 'long').' (from '.$hotelDetails['checkInEarliest'].')';
        $roomInfo['checkOut']    = $this->utils->formatDate($hotelBooking->getToDate(), 'long').' (until '.$hotelDetails['checkOutLatest'].')';
        $roomInfo['nightsCount'] = $this->utils->computeNights($hotelBooking->getFromDate(), $hotelBooking->getToDate());

        // Rates
        $roomInfo['totalPriceInclusiveHotel']    = $apiHotelRoom->getRates()['totalPriceInclusiveHotel'];
        //$roomInfo['totalPriceInclusiveCustomer'] = $apiHotelRoom->getRates()['totalPriceInclusiveCustomer'];
        $roomInfo['totalPriceInclusiveCustomer'] = [
            'amount' => $this->getDisplayPrice(['isoCurrency' => $roomInfo['totalPriceInclusiveHotel']['isoCurrency'], 'amount' => $roomInfo['totalPriceInclusiveHotel']['amount']], null, true, true),
            'isoCurrency' => $this->selectedCurrency
        ];

        $roomInfo['cancelable'] = (empty($apiHotelRoom->getCancellationDate()) && $apiHotelRoom->isCancellable());
        $roomInfo['totalPrice'] = $this->getDisplayPrice($roomInfo['totalPriceInclusiveHotel'], false);

        // Breakfast
        $roomInfo['breakfastDetails'] = $roomOfferDetail['conditions']['mainInfo']['breakfast'];

        // Prepayment
        $roomInfo['prepaymentDetails']         = $roomOfferDetail['conditions']['moreInfo']['prepayment'];
        $roomInfo['prepayRate']                = ($apiHotelRoom->getPrepaymentType() == 'deposit');
        $roomInfo['prepaymentFee']             = $roomOfferDetail['conditions']['prepaymentFee'];
        $roomInfo['guaranteedReservationOnly'] = $apiHotelRoom->getRates()['guaranteedReservationOnly'];
        $roomInfo['creditCardReservationOnly'] = $apiHotelRoom->getRates()['creditCardReservationOnly'];

        // Cancelation
        $roomInfo['cancellationDeadline'] = $apiHotelRoom->getCancellationPenalties()['absoluteDeadline'];

        $roomInfo['cancellationDetails'] = '';
        if (in_array($requestingPage, array('MODIFICATION'))) {
            $roomInfo['cancellationDetails'] = (isset($roomOfferDetail['conditions']['mainInfo']['cancellation'])) ? $roomOfferDetail['conditions']['mainInfo']['cancellation'] : '';
        } else {
            $roomInfo['cancellationDetails'] = (isset($roomOfferDetail['conditions']['moreInfo']['cancellation'])) ? $roomOfferDetail['conditions']['moreInfo']['cancellation'] : '';
        }

        $roomInfo['cancellationFee'] = $roomOfferDetail['conditions']['cancellationFee'];
        $roomInfo['cancellation']    = (!empty($apiHotelRoom->getCancellationDate())) ? $this->utils->formatDate($apiHotelRoom->getCancellationDate(), 'long') : '';

        // Guest
        $roomInfo['guestName']  = $apiHotelRoom->getGuestName();
        $roomInfo['guestEmail'] = $apiHotelRoom->getGuestEmail();

        // Room Status
        $roomInfo['roomStatus'] = $apiHotelRoom->getStatus();

        // Reservation Status
        $reservationStatus = ($apiHotelRoom->getStatus() == $this->container->getParameter('hotels')['reservation_confirmed']) ? 'Reserved' : $apiHotelRoom->getStatus();
        if ($reservationStatus == $this->container->getParameter('hotels')['reservation_canceled']) {
            $reservationStatus .= ": {$roomInfo['cancellation']}";
        } elseif ($reservationStatus == $this->container->getParameter('hotels')['reservation_modified']) {
            $reservationStatus = "Reserved - Room Modified";
        }
        $roomInfo['reservationStatus'] = $reservationStatus;

        return $roomInfo;
    }

    /**
     * This method prepares the special request and concatenates it with the special shortcut-ed requests
     *
     * @param  $reservationWish
     * @param  $wish
     *
     * @return $roomWish
     */
    private function getReservationWish($reservationWish, $wish)
    {
        $roomWish = '';

        if (isset($reservationWish)) {
            $roomWish .= $reservationWish;
        }

        if (isset($wish['quietRoom']) && !empty($wish['quietRoom'])) {
            $roomWish .= '|'."I would like a quiet room.";
        }

        if (isset($wish['nonSmoking']) && !empty($wish['nonSmoking'])) {
            $roomWish .= '|'."Non-smoking room.";
        }

        return $roomWish;
    }

    /**
     * This method decodes a reservation wish
     *
     * @param  $reservationWish
     * @param  $forEmail
     *
     * @return
     */
    private function decodeReservationWish($reservationWish, $forEmail = false)
    {
        if ($forEmail) {
            $roomWishes = '';

            foreach (explode('|', $reservationWish) as $eachRoomWish) {
                if (!empty($eachRoomWish)) {
                    $roomWishes .= '- '.$eachRoomWish.'<br/>';
                }
            }

            return $roomWishes;
        } else {
            $roomWishes = array();

            foreach (explode('|', $reservationWish) as $eachRoomWish) {
                switch ($eachRoomWish) {
                    case "I would like a quiet room.":
                        $roomWishes['quietRoom'] = 1;
                        break;

                    case "Non-smoking room.":
                        $roomWishes['nonSmoking'] = 1;
                        break;

                    default:
                        $roomWishes['reservationWish'] = $eachRoomWish;
                        break;
                }
            }

            return $roomWishes;
        }
    }

    //*****************************************************************************************
    // Post-Booking Functions
    /**
     * Hotel Modification
     *
     * @param  $requestData
     *
     * @return
     */
    public function hotelModification($requestData)
    {
        $toreturn = array();

        $reservationKey = $requestData['reservationKey'];
        $reference      = $requestData['reference'];

        $hotelBookingInformation = $this->getReservationInformation($reference, 'MODIFICATION', null, false);

        $toreturn['reference']      = $reference;
        $toreturn['reservationKey'] = $reservationKey;
        $toreturn['transactionId']  = $this->utils->GUID();

        $toreturn['hotelDetails'] = $hotelBookingInformation['hotelDetails'];

        $toreturn['orderer']                = $hotelBookingInformation['ordererDetails']->toArray();
        $toreturn['orderer']['iso3Country'] = $toreturn['orderer']['country'];
        unset($toreturn['orderer']['country'], $toreturn['orderer']['dialingCode']);

        $toreturn['reservationDetails'] = $hotelBookingInformation['reservationDetails'];
        $toreturn['nightsCount']        = $hotelBookingInformation['reservationDetails']['nbrNights'];

        $dbRoomToModify  = $this->roomRepo->findByReservationKey($reservationKey);
        $roomOfferDetail = json_decode($dbRoomToModify->getRoomOfferDetail(), true);
        $apiHotelRoom    = $hotelBookingInformation['reservedRoomInfo'][$reservationKey];

        $selectedOfferToModify = array(
            $reservationKey => array(
                'room' => $roomOfferDetail['room'],
                'offerDetail' => $roomOfferDetail['offerDetail'],
                'reservationPersons' => json_decode($dbRoomToModify->getGuests(), true),
                'details' => array(
                    'name' => $apiHotelRoom->getName(),
                    'conditions' => $this->getRoomOfferConditions('MODIFICATION', $apiHotelRoom),
                ),
                'roomTypeInfo' => $apiHotelRoom->getRoomTypeInfo(),
            )
        );

        $toreturn['offersSelected'] = $selectedOfferToModify;
        $toreturn['wish']           = '';
        if (isset($roomOfferDetail['reservationWish']) && !empty($roomOfferDetail['reservationWish'])) {
            $toreturn['wish'] = $this->decodeReservationWish($roomOfferDetail['reservationWish']);
        }

        $toreturn['countryList']           = $this->container->get('CmsCountriesServices')->getCountryList();
        $toreturn['mobileCountryCodeList'] = $this->container->get('CmsCountriesServices')->getMobileCountryCodeList();
        $toreturn['ccValidityInfo']        = $this->utils->getCCValidityOptions();

        return $toreturn;
    }

    /**
     * Processes Modification Request
     *
     * @param  $serviceConfig
     * @param  $hotelBC
     *
     * @return
     */
    public function processModificationRequest(HotelServiceConfig $serviceConfig, HotelBookingCriteria $hotelBC)
    {
        $this->initializeService($serviceConfig);

        $toreturn = array();

        $reservationInfo = $this->modifyHotelReservation('MODIFICATION', $hotelBC);
        if (isset($reservationInfo['error'])) {
            $toreturn = $reservationInfo;
        } elseif ($this->isRest) {
            $toreturn = $this->getDataForMobile($reservationInfo);
        }

        return $toreturn;
    }

    /**
     * Modifies Hotel Reservation
     *
     * @param  $action
     * @param  $hotelBC
     *
     * @return
     */
    private function modifyHotelReservation($action, HotelBookingCriteria $hotelBC)
    {
        $toreturn       = array('reference' => $hotelBC->getReference());
        $toNotify       = array();
        $toNotifyCancel = array();

        $availabilityCheck = $this->checkOfferAvailability($hotelBC);
        if (isset($availabilityCheck['error'])) {
            $toreturn['error'] = $availabilityCheck['error'];
        } else {
            $response = $this->hrs->modifyReservation($action, $hotelBC);

            if (!$response->isSuccess()) {
                $toreturn['error'] = $response->getError();

                $this->updateReservation($hotelBC);
            } else {
                $responseData = $response->getData();

                // Save to TT
                $hotelBC->setReservationStatus($this->container->getParameter('hotels')['reservation_modified']);
                $hotelReservation = $this->saveReservation($hotelBC, $hotelBC->getHotelReservationId());

                $hotelReservation->setReservationProcessKey($responseData['reservationProcessKey']);
                $hotelReservation->setReservationProcessPassword($responseData['reservationProcessPassword']);
                $this->em->persist($hotelReservation);
                $this->em->flush();

                foreach ($responseData['roomOfferDetails'] as $roomOfferDetail) {
                    $hotelRoomReservation = $this->roomRepo->findOneBy(array('hotelReservationId' => $hotelBC->getHotelReservationId(), 'reservationKey' => $roomOfferDetail['room']['id']));
                    if (!empty($hotelRoomReservation)) {
                        $roomData = array(
                            'reservationProcessKey' => $responseData['reservationProcessKey'],
                            'reservationProcessPassword' => $responseData['reservationProcessPassword'],
                            'reservationKey' => $roomOfferDetail['reservationKey'],
                            'guests' => json_encode($roomOfferDetail['reservationPersons']),
                            'roomOfferDetail' => json_encode([
                                'room' => $roomOfferDetail['room'],
                                'offerDetail' => $roomOfferDetail['offerDetail'],
                                'reservationWish' => $hotelBC->getRemarks(),
                                'reservationMode' => $hotelBC->getReservationMode()
                            ])
                        );

                        if ($roomOfferDetail['room']['id'] == $hotelBC->getTargetRoomReservationKey()) {
                            $roomData['roomStatus'] = $this->container->getParameter('hotels')['reservation_modified'];
                        }

                        $this->roomRepo->saveRoomReservation($roomData, $hotelRoomReservation);

                        if (isset($roomOfferDetail['reservationPersons'][0]['email']) && !empty($roomOfferDetail['reservationPersons'][0]['email'])) {
                            $toNotify[] = array(
                                'email' => $roomOfferDetail['reservationPersons'][0]['email'],
                                'name' => $roomOfferDetail['reservationPersons'][0]['firstName'],
                            );
                        }
                    }
                }

                $cancelledRoomInfo = null;
                if ($action == 'ROOM_CANCELLATION') {
                    // Update the cancelled room reservation
                    $room = $this->roomRepo->findOneBy(array('hotelReservationId' => $hotelBC->getHotelReservationId(), 'reservationKey' => $hotelBC->getTargetRoomReservationKey()));
                    if (!empty($room)) {
                        $cancelledRoomInfo = $this->hrs->getCancelledRoomReservationInfo($room, $hotelBC);

                        $room->setRoomStatus($this->container->getParameter('hotels')['reservation_canceled']);
                        $room->setReservationKey($cancelledRoomInfo['reservationKey']);

                        unset($cancelledRoomInfo['reservationKey']);
                        $room->setRoomInfo(json_encode($cancelledRoomInfo));

                        $this->em->flush();
                    }

                    // notify orderer and cancelled room guest
                    $cancelledRoom = json_decode($room->getGuests(), true);

                    $toNotifyCancel[] = array('email' => $hotelBC->getOrderer()->getEmail(), 'firstName' => $hotelBC->getOrderer()->getFirstName(), 'lastName' => $hotelBC->getOrderer()->getLastName());
                    $toNotifyCancel[] = array('email' => $cancelledRoom[0]['email'], 'firstName' => $cancelledRoom[0]['firstName'], 'lastName' => $cancelledRoom[0]['lastName']);
                }

                $reservationInfo = $this->getReservationInformation($hotelReservation->getReference(), 'RESERVATION_CONFIRMATION');

                if (!empty($toNotifyCancel)) {
                    $reservationInfo['cancelled']        = false;
                    $reservationInfo['failed']           = array();
                    $reservationInfo['cancellationInfo'] = array(
                        'reservationKey' => $hotelBC->getTargetRoomReservationKey(),
                        'reservationProcessKey' => $reservationInfo['reservationDetails']['reservationProcessKey'],
                        'reservationProcessPassword' => $reservationInfo['reservationDetails']['reservationProcessPassword'],
                        'email' => $reservationInfo['ordererDetails']->getEmail(),
                        'guest' => $reservationInfo['ordererDetails']->getFirstName().' '.$reservationInfo['ordererDetails']->getLastName(),
                        'hotelName' => $reservationInfo['hotelDetails']['name'],
                        'hotelAddress' => $reservationInfo['hotelDetails']['address'],
                        'hotelPhone' => $reservationInfo['hotelDetails']['phone'],
                        'roomsCancelled' => array()
                    );

                    $sent = $this->sendConfirmationEmail('cancel_booking', $toNotifyCancel, $reservationInfo);

                    if (!$sent) {
                        $toreturn['error'] = $this->translator->trans('Reservation successfully cancelled. Unfortunately there was an error sending an email confirmation.');
                    }
                }

                // add orderer as email recipient
                $toNotify[] = array('email' => $hotelBC->getOrderer()->getEmail(), 'name' => $hotelBC->getOrderer()->getFirstName());

                //Send confirmation email
                $this->sendConfirmationEmail('change_booking', $toNotify, $reservationInfo);

                if ($cancelledRoomInfo) {
                    $reservationInfo['cancellationInfo']['roomsCancelled'][$hotelBC->getTargetRoomReservationKey()] = $cancelledRoomInfo;
                }

                $toreturn = $reservationInfo;
            }
        }

        return $toreturn;
    }

    /**
     * This method checks if the offer is still available
     *
     * @param  $hotelBC
     *
     * @return
     */
    private function checkOfferAvailability(HotelBookingCriteria $hotelBC)
    {
        $toreturn = ['status' => 1];
        if (empty($hotelBC->getRoomCriteria())) {
            $toreturn['status'] = 0;
        } else {
            $params = array(
                'hotelId' => $hotelBC->getHotelId(),
                'hotelKey' => $hotelBC->getHotelCode(),
                'fromDate' => $hotelBC->getFromDate(),
                'toDate' => $hotelBC->getToDate(),
                'roomCriteria' => $hotelBC->getRoomCriteria(),
                'genericCriteria' => array(
                    'returnDistances' => 'true',
                    'returnRoomDescriptions' => 'split',
                    'strictRoomTypeHandling' => 'false',
                    'returnMainMedia' => 'false'
                )
            );

            $hotelSC = $this->getHotelSearchCriteria($params);
            $results = $this->hrs->hotelDetailAvail($hotelSC, $this->userId);

            if (isset($results->error)) {
                $toreturn['status'] = -1;
                $toreturn['error']  = $results->error->message;
            } elseif (!isset($results->detailAvailHotelOffers) || empty($results->detailAvailHotelOffers)) {
                $toreturn['status'] = 0;
            } else {
                $responseArr = json_decode(json_encode($results->detailAvailHotelOffers[0]), true);
                foreach ($responseArr['roomOfferDetails'] as $roomOffer) {
                    foreach ($roomOffer['offerDetails'] as $offerDetail) {
                        $availableOffers[] = $this->formatOfferDetails(json_encode($offerDetail));
                    }
                }
            }

            if (empty($availableOffers)) {
                $toreturn['status'] = 0;
            } else {
                foreach ($hotelBC->getRooms() as $roomDetails) {
                    $offerDetail = $this->formatOfferDetails(json_encode($roomDetails['offerDetail']));
                    $found       = false;
                    foreach ($availableOffers as &$offer) {
                        if (strcmp($offer['json'], $offerDetail['json']) === 0) {
                            $offer['roomsLeft'] --;
                            $found = true;
                            if ($offer['roomsLeft'] < 0) {
                                $toreturn['status'] = 0;
                            }
                            break;
                        }
                    }
                    if (!$found) {
                        $toreturn['status'] = 0;
                        break;
                    }
                }
            }
        }

        if ($toreturn['status'] === 0) {
            $toreturn['error'] = $this->translator->trans('Your room cannot be modified or cancelled because the rates have changed. If you wish, you can cancel your reservation by following the link below and then doing a new one.');
        }

        return $toreturn;
    }

    /**
     * This method formats the offer details
     *
     * @param  $value
     *
     * @return
     */
    private function formatOfferDetails($value)
    {
        $cleaned = [];

        $re = '/{"key":"roomsLeft","values":\["(\d*)"\]}/';

        // remove roomsLeft info
        $json = preg_replace('/("cancellationDeadline":"([0-9]*|-|T|:)*)(\.[\d]*)(\+[0-9]{2}:[0-9]{2})/', '$1$4', $value);
        $json = preg_replace('/"roomsLeft":([\d*]|null),/', '', $json);

        $cleaned['json']      = preg_replace($re, '', $json);
        preg_match($re, $value, $cleaned['roomsLeft']);
        $cleaned['roomsLeft'] = (isset($cleaned['roomsLeft'][1])) ? $cleaned['roomsLeft'][1] : 4;

        // remove availCount
        $cleaned['json'] = preg_replace('/,{"key":"availCount","values":\["[0-9]"\]}/', '', $cleaned['json']);

        return $cleaned;
    }

    /**
     * This method updates the hotel reservation records after calling the reservation information API
     *
     * @param  HotelBookingCriteria $hotelBC
     *
     * @return Update the records or return an error
     */
    private function updateReservation(HotelBookingCriteria $hotelBC)
    {
        // check if we need to change reservation status
        $canceled = $this->hrs->isBookingCanceled($hotelBC);

        if ($canceled) {
            // Flag whole reservation as cancelled
            $hr = $this->reservationRepo->find($hotelBC->getHotelReservationId());
            $hr->setReservationStatus($this->container->getParameter('hotels')['reservation_canceled']);

            // Update all rooms as cancelled
            foreach ($this->roomRepo->findByReservationId($hotelBC->getHotelReservationId()) as $room) {
                $room->setRoomStatus($this->container->getParameter('hotels')['reservation_canceled']);
            }

            $this->em->flush();
        }
    }

    /**
     * This method sends the appropriate confirmation/cancellation email
     *
     * @param  $action
     * @param  $toNotify
     * @param  $reservationInfo
     *
     * @return
     */
    private function sendConfirmationEmail($action, $toNotify, $reservationInfo)
    {
        extract($reservationInfo); // creates $reservationDetails, $ordererDetails, $hotelDetails, $roomsToDisplay, cancellationInfo(cancel_booking), cancelled(cancel_booking), failed(cancel_booking)

        if ($action == 'change_booking') {
            $subject  = 'TouristTube Hotels - Your Modified Booking';
            $template = '@Hotel/hotel-confirmation-email.twig';
        } elseif ($action == 'cancel_booking') {
            $subject  = 'TouristTube Hotels - Booking Canceled';
            $template = '@Hotel/hotel-cancellation-email.twig';
        } else {
            $subject  = 'TouristTube Hotels - Your Booking';
            $template = '@Hotel/hotel-confirmation-email.twig';
        }

        $emailVars           = array();
        $emailVars['action'] = $action;

        if ($action == 'cancel_booking') {
            $emailVars['cancelled']        = $cancelled;
            $emailVars['failed']           = $failed;
            $emailVars['cancellationInfo'] = $cancellationInfo;
        } else {
            $emailVars['hotelDetailsRouteName']   = '_hotel_details';
            $emailVars['bookingDetailsRouteName'] = '_booking_details';
            $emailVars['reference']               = $reservationDetails['reference'];
            $emailVars['reservationDetails']      = $reservationDetails;
            $emailVars['reservationDate']         = $reservationDetails['reservationDate'];
            $emailVars['ordererDetails']          = $ordererDetails;
            $emailVars['hotelDetails']            = $hotelDetails;
            $emailVars['roomsToDisplay']          = $roomsToDisplay;
        }

        $sent             = true;
        $unique_recipient = array();
        foreach ($toNotify as $recipient) {
            if (!in_array($recipient['email'], $unique_recipient)) {
                $unique_recipient[]     = $recipient['email'];
                $emailVars['recipient'] = $recipient;

                $msg  = $this->templating->render($template, $emailVars);
                $sent = $sent & $this->emailServices->AddEmailData($recipient['email'], $msg, $subject, 'TouristTube.com', 0);
            }
        }

        return $sent;
    }

    /**
     * Hotel Room Cancellation
     *
     * @param  $reference
     * @param  $cancelReservationKey
     *
     * @return
     */
    public function hotelRoomCancellation($reference, $cancelReservationKey)
    {
        $toreturn = array();

        if (empty($cancelReservationKey)) {
            $toreturn['error'] = $this->translator->trans('Missing required information.');
        } else {
            $hotelBookingInformation = $this->getReservationInformation($reference, 'CANCELLATION', null, false);

            if (isset($hotelBookingInformation['error'])) {
                $toreturn = $hotelBookingInformation;
            } else {
                $toreturn['reservationDetails'] = $hotelBookingInformation['reservationDetails'];
                $toreturn['hotelDetails']       = $hotelBookingInformation['hotelDetails'];

                $toreturn['cancelReservationKey'] = $cancelReservationKey;
                $toreturn['transactionId']        = $this->utils->GUID();
                $toreturn['ccValidityInfo']       = $this->utils->getCCValidityOptions();
            }
        }

        return $toreturn;
    }

    /**
     * Process Cancellation Request
     *
     * @param  $serviceConfig
     * @param  $criteria
     * @param  $emailTemplate
     *
     * @return
     */
    public function processCancellationRequest(HotelServiceConfig $serviceConfig, $criteria, $emailTemplate = '@Hotel/hotel-cancellation-email.twig')
    {
        $toreturn = array('reference' => $criteria['reference']);

        $this->initializeService($serviceConfig);

        $cancelReservationKey = (isset($criteria['cancelReservationKey'])) ? $criteria['cancelReservationKey'] : null;
        $reference            = $criteria['reference'];

        $hotelBookingInformation = $this->getReservationInformation($reference, 'CANCELLATION', null, false);

        // validation
        if (isset($hotelBookingInformation['error'])) {
            $toreturn = $hotelBookingInformation;
        } elseif ($hotelBookingInformation['reservationDetails']['canceled']) {
            $toreturn['error'] = $this->translator->trans("Reservation already cancelled");
        } elseif ($cancelReservationKey) {
            // validate room cancellation
            if (!isset($hotelBookingInformation['roomsToDisplay'][$cancelReservationKey])) {
                $toreturn['error'] = $this->translator->trans("Reservation key invalid");
            } elseif ($hotelBookingInformation['reservationDetails']['ccRequired'] && (
                !isset($criteria['ccCardHolder']) || empty($criteria['ccCardHolder']) ||
                !isset($criteria['ccNumber']) || empty($criteria['ccNumber']) ||
                !isset($criteria['ccType']) || empty($criteria['ccType']) ||
                !isset($criteria['ccExpiryMonth']) || empty($criteria['ccExpiryMonth']) ||
                !isset($criteria['ccExpiryYear']) || empty($criteria['ccExpiryYear']))) {
                $toreturn['error'] = $this->translator->trans("Credit card information missing.");
            } elseif ($hotelBookingInformation['reservationDetails']['ccRequired'] && $hotelBookingInformation['reservationDetails']['ccCodeRequired'] && ((!isset($criteria['ccCVC']) || empty($criteria['ccCVC'])))) {
                $toreturn['error'] = $this->translator->trans("Credit card security code information required.");
            }
        }

        // if valid, process the request
        if (!isset($toreturn['error'])) {
            // Room Reservation Cancellation
            if ($cancelReservationKey) {
                $criteria['hotelDetails']    = $hotelBookingInformation['hotelDetails'];
                $criteria['reservationMode'] = $hotelBookingInformation['reservationDetails']['reservationMode'];

                $hotelBC = $this->getHotelBookingCriteria($criteria);

                $toreturn = $this->cancelRoomReservation($hotelBC);
            }
            // Whole Reservation Cancellation
            else {
                $toreturn = $this->cancelWholeReservation($hotelBookingInformation, $emailTemplate);
            }
        }

        return $toreturn;
    }

    /**
     * Cancel Room Reservation
     *
     * @param  HotelBookingCriteria $hotelBC
     *
     * @return
     */
    private function cancelRoomReservation(HotelBookingCriteria $hotelBC)
    {
        return $this->modifyHotelReservation('ROOM_CANCELLATION', $hotelBC);
    }

    /**
     * This method cancels the whole reservation itinerary
     *
     * @param  array $reservationInfo The reservation information.
     * @param String $emailTemplate The email template to use for sending confirmation email.
     *
     * @return Array
     */
    private function cancelWholeReservation($reservationInfo, $emailTemplate = '@Hotel/hotel-cancellation-email.twig')
    {
        extract($reservationInfo); // This extracts the array information and initializes it to variables: $roomsToDisplay; $reservationDetails; $hotelDetails; $ordererDetails; and $transactionId.

        $toreturn = array();

        $status        = $this->container->getParameter('hotels')['reservation_canceled'];
        $transactionId = $this->utils->GUID();

        $toreturn['cancelled']        = false;
        $toreturn['failed']           = array();
        $toreturn['cancellationInfo'] = array(
            'reservationProcessKey' => $reservationDetails['reservationProcessKey'],
            'reservationProcessPassword' => $reservationDetails['reservationProcessPassword'],
            'email' => $ordererDetails->getEmail(),
            'guest' => $ordererDetails->getFirstName().' '.$ordererDetails->getLastName(),
            'hotelName' => $hotelDetails['name'],
            'hotelAddress' => $hotelDetails['address'],
            'hotelPhone' => $hotelDetails['phone'],
            'roomsCancelled' => array()
        );

        $this->logger->addHotelActivityLog('HRS', 'cancellation', $this->userId, array(
            "hotelName" => $hotelDetails['name'],
            "reference" => $reservationDetails['reference'],
            "transactionId" => $transactionId,
            "criteria" => $reservationDetails
        ));

        $reservationDetails['userId']  = $this->userId;
        $reservationDetails['hotelId'] = $hotelDetails['hotelId'];

        $results = $this->hrs->hotelReservationCancellation($reservationDetails);

        $this->logger->addBookingRequestLog('HRS', 'cancellation', $this->userId, array_merge($reservationDetails, array('hotelId' => $hotelDetails['hotelId'], 'hotelName' => $hotelDetails['name'],
            'transactionId' => $transactionId)), $results);

        if (isset($results->error)) {
            $toreturn['error'] = $results->error->message;
        } elseif (isset($results->cancellationStatus)) {
            $cancellationStatus = json_decode(json_encode($results->cancellationStatus), true);

            foreach ($roomsToDisplay as $key => $value) {
                if (!empty($value['cancellation'])) {
                    unset($roomsToDisplay[$key]);
                }
            }

            // One cancellationStatus per room reserved, so we need to check that all rooms have been cancelled
            // Else, let's display a message stating what happened
            $rooms = $roomsToDisplay;

            $roomsCancelled = array();
            foreach ($cancellationStatus as $key => $cancellation) {
                // cancelled room on our response are ordered in a way that the first room
                // on our cancellation status relates to the first item in our $roomsToDisplay
                $room = array_shift($rooms);

                if ($cancellation['cancellationResultCode'] != 0) {
                    $toreturn['failed'][] = array_merge($room, $cancellation);
                } else {
                    $roomsCancelled[$room['reservationKey']] = array_merge($room, $cancellation);
                }
            }

            if (empty($toreturn['failed'])) {
                // update reservation's non canceled room's status and  send email confirmation for each rooms
                $toNotify = array();
                foreach ($hotelBooking->getRooms() as &$room) {
                    $reservationKey = $room->getReservationKey();

                    // update room's reservation status
                    $room->setRoomStatus($status);

                    // update room's reservation info
                    if (isset($roomsCancelled[$reservationKey])) {
                        $cancellationInfo = $roomsCancelled[$reservationKey];
                        $roomInfo         = [
                            'reservationKey' => ((isset($cancellationInfo['reservationKey'])) ? $cancellationInfo['reservationKey'] : ''),
                            'cancellationKey' => ((isset($cancellationInfo['cancellationKey'])) ? $cancellationInfo['cancellationKey'] : '')
                        ];

                        $room->setRoomInfo(json_encode($roomInfo));
                    }

                    if (isset($roomsToDisplay[$reservationKey])) {
                        $roomsToDisplay[$reservationKey] = array_merge($roomsToDisplay[$reservationKey], array('roomStatus' => $status));
                        $roomsCancelled[$reservationKey] = $roomsToDisplay[$reservationKey];
                    }

                    $guests = json_decode($room->getGuests(), true);
                    $guests = array_shift($guests);

                    if (isset($guests['email']) && !empty($guests['email'])) {
                        $toNotify[] = array(
                            'firstName' => $guests['firstName'],
                            'lastName' => $guests['lastName'],
                            'email' => $guests['email'],
                        );
                    }
                }

                if (!empty($roomsCancelled)) {
                    $toreturn['cancellationInfo']['roomsCancelled'] = array_values($roomsCancelled);
                }

                // Send also email confirmation to orderer's email.
                $orderer    = $hotelBooking->getOrderer();
                $toNotify[] = array('email' => $orderer->getEmail(), 'firstName' => $orderer->getFirstName(), 'lastName' => $orderer->getLastName());

                // update reservation status
                $hr = $this->reservationRepo->find($hotelBooking->getHotelReservationId());
                $hr->setReservationStatus($status);

                $this->em->flush();

                // Send confirmation emails
                $sent      = true;
                $emailSent = array();
                foreach ($toNotify as $recipient) {
                    if ($recipient['email'] && !in_array($recipient['email'], $emailSent)) {
                        $emailSent[]           = $recipient['email'];
                        $toreturn['recipient'] = $recipient;

                        $msg  = $this->templating->render($emailTemplate, $toreturn);
                        $sent = $sent && $this->emailServices->addEmailData($recipient['email'], $msg, 'TouristTube Hotels - Booking Canceled', 'TouristTube.com', 0);
                    }
                }

                if (!$sent) {
                    $toreturn['error'] = $this->translator->trans('Reservation successfully cancelled. Unfortunately there was an error sending an email confirmation.');
                }

                $toreturn['cancelled'] = true;
            }
        }

        if ($this->isRest) {
            $restData = array('cancelled' => $toreturn['cancelled'], 'failed' => $toreturn['failed']);
            if (isset($toreturn['error'])) {
                $restData['error'] = $toreturn['error'];
            }

            $toreturn = $restData;
        }

        return $toreturn;
    }

    //*****************************************************************************************
    // User Booking List Functions
    /**
     * get User Bookings
     *
     * @param  $serviceConfig
     * @param  $hotelBookingSC
     *
     * @return
     */
    public function getUserBookings(HotelServiceConfig $serviceConfig, HotelBookingSC $hotelBookingSC)
    {
        $this->initializeService($serviceConfig);

        $page        = $hotelBookingSC->getPage();
        $bookingType = $hotelBookingSC->getBookingStatus();
        $showMore    = $hotelBookingSC->getShowMore();
        $isMobile    = $hotelBookingSC->getIsRest();

        $limit       = ($isMobile && !$showMore) ? 3 : 10;
        $currentpage = $page;
        if ($currentpage < 0) {
            $currentpage = 0;
        }

        $allInfo  = array();
        $options  = array();
        $optionsC = array('n_results' => true);

        $options['email']   = $optionsC['email']  = $hotelBookingSC->getUserEmail();
        $options['userId']  = $optionsC['userId'] = $hotelBookingSC->getUserId();

        if (!empty($options)) {
            $options['page']  = $currentpage;
            $options['limit'] = $limit;

            $options['fromDate'] = $hotelBookingSC->getFromDate();
            $options['toDate']   = $hotelBookingSC->getToDate();

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

            $hotelBookings = $this->searchHotelBookings($options);

            if ($isMobile) {
                $allInfo = array(
                    'hotelBookings' => $hotelBookings,
                    'total_hotels_returned' => 0,
                    'total_hotels_past' => 0,
                    'total_hotels_cancelled' => 0,
                    'total_hotels_upcoming' => 0,
                    'total_hotels' => 0
                );

                unset($options['limit']);
                $options['n_results']             = true;
                $allInfo['total_hotels_returned'] = $this->searchHotelBookings($options);

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
     * search Hotel Bookings
     *
     * @param  $options
     *
     * @return
     */
    public function searchHotelBookings($options)
    {
        $options['transactionSourceId'] = $this->em->getRepository('TTBundle:TransactionSource')->findOneByCode('web')->getId();

        $res = $this->reservationRepo->searchUserHotelBookings($this->siteLanguage, $options);

        if (!isset($options['n_results'])) {
            $reservationIds = array();
            $bookings       = array();
            foreach ($res as $key => $value) {
                $fromDate = strtotime($value['fromDate']);
                $toDate   = strtotime($value['toDate']);
                if ($value['reservationProcessKey']) {
                    $mainImage = $this->getHotelMainImage($value['hotelId']);
                } else {
                    $mainImage = $this->getHotelMainImage(0);
                }
                $reservationIds[] = $value['id'];

                $bookings[$value['id']] = array(
                    'reference' => $value['reference'],
                    'hotelId' => $value['hotelId'],
                    'hotelDetails' => array(
                        'name' => $value['hotelName'],
                        'stars' => $value['stars'],
                        'mainImage' => $mainImage[1],
                        'hotelNameURL' => $this->utils->cleanTitleData($value['hotelName']),
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
                    'checkIn' => array(
                        'day' => date('d', $fromDate),
                        'monthAndYear' => date('M Y', $fromDate),
                        'dayOfWeek' => date('D', $fromDate),
                    ),
                    'checkOut' => array(
                        'day' => date('d', $toDate),
                        'monthAndYear' => date('M Y', $toDate),
                        'dayOfWeek' => date('D', $toDate),
                    ),
                );
            }

            $res = $this->roomRepo->getUserHotelBookingRoomInformation($reservationIds);

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
            return $res;
        }
    }

    //*****************************************************************************************
    // Hotel Information Functions
    /**
     * Returns the hotel districts
     *
     * @param  HotelSC &$hotelSC
     *
     * @return
     */
    public function getHotelDistricts(HotelSC &$hotelSC)
    {
        $toreturn = array();

        $locationId = $hotelSC->getLocationId();
        if (!$locationId) {
            $locationId = $this->hsRepo->getHotelSourceField('locationId', array('hotelId', $hotelSC->getHotelId()));
        }

        $hotelDistricts = $this->hsRepo->getDistricts($locationId);
        if (!empty($hotelDistricts)) {
            foreach ($hotelDistricts as $dist) {
                $toreturn[$dist['district']] = str_ireplace('Arrondissement', 'arr.', $dist['district']);
            }
            natsort($toreturn);
        }

        $hotelSC->setLocationId($locationId);

        return $toreturn;
    }

    /**
     * Returns basic hotel details
     *
     * @param  $hotelId
     * @param  $lang
     * @param  $imageLimit
     * @param  $imageIndex
     * @param  $requestId
     *
     * @return The details of the hotel
     */
    public function getHotelInfoData($hotelId, $lang = 'en', $imageLimit = 0, $imageIndex = null, $requestId = 0)
    {
        $details                 = array();
        $hotelFromDB             = $this->hotelRepo->getHotelDataById($hotelId, $lang);
        $details['hotelId']      = $hotelId;
        $details['name']         = $hotelFromDB['name'];
        $details['hotelNameURL'] = $this->utils->cleanTitleData($hotelFromDB['name']);
        $details['namealt']      = $this->utils->cleanTitleDataAlt($hotelFromDB['name']);
        $details['category']     = $hotelFromDB['stars'];
        $details['address']      = $this->getAddress($hotelFromDB);
        $details['gpsLatitude']  = $this->utils->decToDMS($hotelFromDB['latitude'], 1);
        $details['gpsLongitude'] = $this->utils->decToDMS($hotelFromDB['longitude'], 1);
        $details['namealt']      = $this->utils->cleanTitleDataAlt($hotelFromDB['name']);
        $details['description']  = $hotelFromDB['description'];
        $details['district']     = $hotelFromDB['district'];
        $details['city']         = $hotelFromDB['city'];
        $details['countryCode']  = $hotelFromDB['countryCode'];
        $details['country']      = $hotelFromDB['country'];
        $details['latitude']     = $hotelFromDB['latitude'];
        $details['longitude']    = $hotelFromDB['longitude'];

        $details['distances']    = array();
        $details['freeServices'] = array();

        $details['mainImage']    = $this->getHotelMainImage($hotelId, 0);
        $details['mainImageBig'] = $this->getHotelMainImage($hotelId, 3);
        $details['mapImageUrl']  = $this->container->get('HotelsServices')->getMapImageUrl($hotelId, $requestId, $this->transactionSourceId, $this->container->getParameter('hotels')['page_src']['hrs']);

        $hotelImages = $this->imageRepo->getHotelImages($hotelId, $imageLimit, -1);
        foreach ($hotelImages as $img) {
            $new_ar = $this->createImageSource($img, 0, $imageIndex);
            if (!is_array($new_ar)) {
                $new_ar = array($new_ar);
            }
            $new_ar['user_id']   = $img['userId'];
            $new_ar['id']        = $img['id'];
            $details['images'][] = $new_ar;
        }

        return $details;
    }

    /**
     * Returns basic hotel information
     *
     * @param $hoteFromAPI
     * @param $hotelId
     * @param $requestingPage
     * @param $imageLimit
     * @param $imageIndex
     * @param $requestId
     * @param $getAllImage
     *
     * @return
     */
    private function getHotelInformation(Hotel $hoteFromAPI, $hotelId, $requestingPage, $imageLimit = 0, $imageIndex = null, $requestId = 0, $getAllImage = 0)
    {
        $dbHotelDetails = array();

        if (!in_array($requestingPage, array('MODIFICATION'))) {
            $dbHotelDetails = $this->getHotelDBInformation($hotelId, $imageLimit, $imageIndex, $requestId, $requestingPage, $getAllImage);
        }

        $dbHotelDetails['fax']                 = $hoteFromAPI->getFax();
        $dbHotelDetails['email']               = $hoteFromAPI->getEmail();
        $dbHotelDetails['phone']               = $hoteFromAPI->getPhone();
        $dbHotelDetails['acceptedCreditCards'] = $hoteFromAPI->getAcceptedCreditCards();

        if ($hoteFromAPI->getAcceptedCreditCards()) {
            $dbHotelDetails['creditCardDetails'] = $this->utils->getCCDetails($dbHotelDetails['acceptedCreditCards'], $this->isRest);
        }

        $dbHotelDetails['creditCardSecurityCodeRequired'] = $hoteFromAPI->getCreditCardSecurityCodeRequired();
        $dbHotelDetails['checkInEarliest']                = $hoteFromAPI->getCheckInEarliest();
        $dbHotelDetails['checkOutLatest']                 = $hoteFromAPI->getCheckOutLatest();

        if (in_array($requestingPage, array('OFFERS'))) {
            $dbHotelDetails['distances']    = $hoteFromAPI->getDistances();
            $dbHotelDetails['freeServices'] = $hoteFromAPI->getFreeServices();
        }

        return $dbHotelDetails;
    }

    /**
     * This method gets basic hotel database information
     *
     * @param $hotelId
     * @param $imageLimit
     * @param $imageIndex
     * @param $requestId
     * @param $type
     * @param $getAllImage
     *
     * @return
     */
    private function getHotelDBInformation($hotelId, $imageLimit, $imageIndex = null, $requestId = 0, $type = '', $getAllImage = 0)
    {
        $details = array();

        $details['hotelKey'] = $this->hsRepo->getHotelSourceField('sourceId', array('hotelId', $hotelId));

        if (empty($details['hotelKey'])) {
            return array();
        }

        $hotelFromDB = $this->hotelRepo->getHotelDataById($hotelId, $this->siteLanguage);
        $hotelObject = $this->hotelRepo->getHotelById($hotelId);

        // Mini google-map
        $details['mapImageSource'] = $this->container->get('LocationImageServices')->returnMapLocationImage($this->container->getParameter('SOCIAL_ENTITY_HOTEL'), $hotelObject, 15, '278x204');
        if ($this->isRest) {
            $details['mapImageSource'] = substr($details['mapImageSource'], (stripos($details['mapImageSource'], '/media') + 1));
        }

        //Basic hotel details
        $details['hotelId']      = $hotelId;
        $details['name']         = $hotelFromDB['nameTrans'];
        $details['hotelNameURL'] = $this->utils->cleanTitleData($hotelFromDB['name']);
        $details['namealt']      = $this->utils->cleanTitleDataAlt($hotelFromDB['name']);
        $details['category']     = $hotelFromDB['stars'];
        $details['street']       = $hotelFromDB['street'];
        $details['district']     = $hotelFromDB['district'];
        $details['zipCode']      = $hotelFromDB['zipCode'];
        $details['city']         = $hotelFromDB['city'];
        $details['country']      = $hotelFromDB['country'];
        $details['address']      = $this->getAddress($hotelFromDB);
        $details['gpsLatitude']  = $this->utils->decToDMS($hotelFromDB['latitude'], 1);
        $details['gpsLongitude'] = $this->utils->decToDMS($hotelFromDB['longitude'], 1);
        $details['hotelSource']  = strtolower($hotelFromDB['source']);
        $details['isActive']     = intval($hotelFromDB['isActive']);

        $cityId = intval($hotelFromDB['cityId']);

        // These data will be updated if there is data from API call
        $details['checkInEarliest']                = '14:00';
        $details['checkOutLatest']                 = '12:00-13:00';
        $details['email']                          = '';
        $details['phone']                          = '';
        $details['fax']                            = '';
        $details['acceptedCreditCards']            = array();
        $details['creditCardDetails']              = array();
        $details['creditCardSecurityCodeRequired'] = false;

        // Amenities
        $hotelAmenities = $this->em->getRepository('HotelBundle:CmsHotelFacility')->getHotelHighestFacilities($hotelId, $this->siteLanguage);
        foreach ($hotelAmenities as $am) {
            $icon = '';
            if (preg_match("/no.*smoking/i", $am['facilityName'])) {
                $icon = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/smokingsign.png');
            } elseif (preg_match("/wi.*fi/i", $am['facilityName'])) {
                $icon = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/wifisignal.png');
            }

            $details['amenities'][] = array(
                'icon' => $icon,
                'name' => $am['facilityName']
            );
        }

        // Facilities
        $facilities = $this->getFacilities($hotelId);
        if (!empty($facilities)) {
            $details['facilities'] = $facilities;
        }

        switch ($type) {
            case 'basicOnly':
                break;
            case 'fromMobile':
                $details['latitude']    = floatval($hotelFromDB['latitude']);
                $details['longitude']   = floatval($hotelFromDB['longitude']);
                break;
            case 'offers':
            default:
                $details['namealt']     = $this->utils->cleanTitleDataAlt($hotelFromDB['name']);
                $details['description'] = $hotelFromDB['description'];
                $details['district']    = $hotelFromDB['district'];
                $details['city']        = $hotelFromDB['city'];
                $details['countryCode'] = $hotelFromDB['countryCode'];
                $details['country']     = $hotelFromDB['country'];
                $details['latitude']    = $hotelFromDB['latitude'];
                $details['longitude']   = $hotelFromDB['longitude'];

                $details['distances']    = array();
                $details['freeServices'] = array();

                $details['mainImage']    = $this->getHotelMainImage($hotelId, 4);
                $details['mainImageBig'] = $this->getHotelMainImage($hotelId, 3);
                $details['mapImageUrl']  = $this->container->get('HotelsServices')->getMapImageUrl($hotelId, $requestId, $this->transactionSourceId, $this->container->getParameter('hotels')['page_src']['hrs']);

                $details['cancellationAndPrepayment'] = $this->translator->trans("Cancellation and prepayment policies vary according to room type. Please enter the dates of your stay and check the conditions of your required room.");
                $details['childrenAndExtraBeds']      = $this->translator->trans("No more than two children permitted per double room. Only children under the age of 13 may share their parents' bed.");

                // Hotel Photos
                // IF (hotel has 360 images OR 360 preview is true) AND it's not a REST request, THEN we will prioritize 360 images over standard hotel images
                // ELSE we will use standard hotel images.
                $has360            = (boolval($hotelFromDB['has360']) || $this->isPreview360) ? true : false;
                $details['has360'] = $has360;

                if ($has360 && !$this->isRest) {
                    $details['images'] = $this->getHotelImagesByType($hotelId, $this->container->getParameter('MEDIA_TYPE_360'));
                } else {
                    $hotelImages = $this->imageRepo->getHotelImages($hotelId, $imageLimit, $getAllImage);
                    foreach ($hotelImages as $img) {
                        $new_ar = $this->createImageSource($img, 0, $imageIndex);
                        if (!is_array($new_ar)) {
                            $new_ar = array($new_ar);
                        }
                        $new_ar['user_id']   = $img['userId'];
                        $new_ar['id']        = $img['id'];
                        $details['images'][] = $new_ar;
                    }
                }

                // Related Things-To-Do
                if ($cityId != 0) {
                    $Results                     = $this->container->get('ThingsToDoServices')->getPoiTopList('', '', $cityId, 4, 'rand', $this->siteLanguage, $this->isRest);
                    $details['nearbyAttraction'] = $Results['pois_array'];
                }

                break;
        }

        return $details;
    }

    /**
     * This method prepares the full address line as a concatenation of several address elements
     *
     * @param  Mixed  $hotel The Hotel Data.
     *
     * @return String A comma-separated full address.
     */
    public function getAddress($hotel)
    {
        if (is_array($hotel)) {
            $address = array(
                $hotel['street'],
                $hotel['district'],
                $hotel['zipCode'],
                $hotel['city'],
                $hotel['country']
            );
        } else {
            $address = array(
                $hotel->getStreet(),
                $hotel->getDistrict(),
                $hotel->getZipCode(),
                $hotel->getCity(),
                $this->container->get('CmsCountriesServices')->getNameByIso3Code($hotel->getIso3CountryCode())
            );
        }

        return implode(", ", array_filter($address));
    }

    /**
     * This method returns the facilities of a certain hotel
     *
     * @param $hotelId
     *
     * @return array of facilities
     */
    private function getFacilities($hotelId)
    {
        $facilities      = array();
        $hotelFacilities = $this->em->getRepository('HotelBundle:CmsHotelFacility')->getHotelFacilities($hotelId, $this->siteLanguage);

        foreach ($hotelFacilities as $fac) {
            if (!isset($facilities[$fac['typeId']])) {
                $facilities[$fac['typeId']] = array(
                    'type' => $fac['typeName'],
                    'names' => array()
                );
            }
            $facilities[$fac['typeId']]['names'][] = $fac['facilityName'];
        }

        return $facilities;
    }

    //********************************************************************************************
    // Hotel Image functions
    /**
     * This method returns the hotel's main image
     *
     * @param $hotelId
     * @param $index
     * @param $is_profile
     *
     * @return
     */
    public function getHotelMainImage($hotelId, $index = null, $is_profile = 1)
    {
        $hotelImage = $this->imageRepo->getHotelMainImage($hotelId);

        if ($hotelImage) {
            $mainImage = $this->createImageSource($hotelImage, $is_profile);
        } else {
            $mainImage = array($this->container->get("TTRouteUtils")->generateMediaURL('/media/images/hotel-icon-image2.jpg', null, $this->isRest), $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/hotel-icon-image2.jpg', null, $this->isRest));
        }

        if (isset($index)) {
            $mainImage = (isset($mainImage[$index])) ? $mainImage[$index] : $mainImage[0];
        }

        return $mainImage;
    }

    /**
     * This method creates the image thumbs
     *
     * @param $image
     * @param $is_profile
     * @param $index
     *
     * @return
     */
    public function createImageSource($image, $is_profile = 0, $index = null)
    {
        $source     = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/hotel-icon-image2.jpg', null, $this->isRest);
        $sourcename = 'hotel-icon-image2.jpg';
        $sourcepath = 'media/images/';

        if (isset($image) && sizeof($image) && !empty($image['hotelId']) && !empty($image['imageSource'])) {
            $sourcename = $image['imageSource'];
            if (!empty($image['imageLocation'])
            ) {
                $img        = '/media/hotels/'.$image['hotelId'].'/'.$image['imageLocation'].'/'.$image['imageSource'];
                $sourcepath = 'media/hotels/'.$image['hotelId'].'/'.$image['imageLocation'].'/';
            } else {
                $img        = '/media/hotels/'.$image['hotelId'].'/'.$image['imageSource'];
                $sourcepath = 'media/hotels/'.$image['hotelId'].'/';
            }

            $source = $this->container->get("TTRouteUtils")->generateMediaURL($img, null, $this->isRest);
        }

        if ($is_profile == 2) {
            $sourceProfile2 = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 290, 166, 'hotels65HS290166', $sourcepath, $sourcepath, 65, $this->isRest);
            $sourceBig      = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 885, 468, 'hotels75HS885468', $sourcepath, $sourcepath, 75, $this->isRest);
            $imageSet       = array($sourceProfile2, $sourceBig);
        } elseif ($is_profile == 1) {
            $sourceProfile  = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 139, 74, 'hotels65HS13974', $sourcepath, $sourcepath, 65, $this->isRest);
            $sourceProfile2 = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 290, 166, 'hotels65HS290166', $sourcepath, $sourcepath, 65, $this->isRest);
            $sourceProfile3 = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 108, 60, 'hotels65HS10860', $sourcepath, $sourcepath, 65, $this->isRest);
            $sourceProfile4 = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 284, 159, 'hotels65HS284159', $sourcepath, $sourcepath, 65, $this->isRest);
            $imageSet       = array($sourceProfile, $sourceProfile2, $sourceProfile3, $source, $sourceProfile4);
        } else {
            $sourceSmall  = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 134, 72, 'hotels50HS13472', $sourcepath, $sourcepath, 50, $this->isRest);
            $sourceSmall2 = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 186, 100, 'hotels65HS186100', $sourcepath, $sourcepath, 65, $this->isRest);
            $sourceBig    = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 885, 468, 'hotels75HS885468', $sourcepath, $sourcepath, 75, $this->isRest);
            $imageSet     = array($sourceSmall, $sourceBig, $sourceSmall2, $source);
        }

        if (isset($index)) {
            $imageSet = (isset($imageSet[$index])) ? $imageSet[$index] : $imageSet[0];
        }

        return $imageSet;
    }

    //********************************************************************************************
    // 360 Tour Methods
    /**
     * This method calls the HRSRepository to retrieve the hotel images by mediaType
     *
     * @param  Integer $hotelId
     * @param  Integer $mediaType
     *
     * @return Array   List
     */
    private function getHotelImagesByType($hotelId, $mediaType)
    {
        $images = array();

        if ($mediaType == $this->container->getParameter('MEDIA_TYPE_360')) {
            $hotelData = $this->imageRepo->getHotelImagesByType($hotelId, $mediaType);

            foreach ($hotelData as $data) {
                $imagePath = "hotels/".strtolower($data['countryCode'])."/".$data['hotelId']."/".$data['categoryId']."/";

                if (isset($data['parentId']) && !empty($data['parentId'])) {
                    $imagePath .= $data['parentId']."/";
                }

                $imagePath .= $data['divisionId']."/";

                $data['sourcePath']      = $imagePath;
                $generatedImages         = $this->createImgSourceFor360($data);
                $generatedImages['info'] = $data;

                // Prepare the 360 preview image
                $media_360_base_path                    = $this->container->getParameter('MEDIA_360_BASE_PATH');
                $generatedImages['info']['preview_360'] = $this->container->get("TTMediaUtils")->createItemThumbs("360_Preview.jpg", $media_360_base_path.$imagePath, 0, 0, 895, 503, 'hotels75HS895503', $media_360_base_path.$imagePath, $media_360_base_path.$imagePath, 75);

                $images[] = $generatedImages;
            }
        }

        return $images;
    }

    /**
     * This method creates an image source for 360 tour.
     *
     * @param Array   $image
     * @param Integer $index The index.
     *
     * @return Array The image set.
     */
    private function createImgSourceFor360($image, $index = null)
    {
        $media_360_base_path = $this->container->getParameter('MEDIA_360_BASE_PATH');
        $sourcename          = $image['imageSource'];
        $sourcepath          = $media_360_base_path.$image['sourcePath'];

        $sourceProfile2 = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 290, 166, 'hotels65HS290166', $sourcepath, $sourcepath, 65);
        $sourceBig      = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 895, 503, 'hotels75HS895503', $sourcepath, $sourcepath, 75);
        $imageSet       = array($sourceProfile2, $sourceBig);

        if (isset($index)) {
            $imageSet = (isset($imageSet[$index])) ? $imageSet[$index] : $imageSet[0];
        }

        return $imageSet;
    }

    /**
     * This method calls the HRSRepository to fetch a related Things-To-Do per hotel city
     *
     * @param  Integer $hotelId
     *
     * @return list
     */
    public function getHotel360ThingsToDo($hotelId)
    {
        return $this->hotelRepo->getHotel360ThingsToDo($hotelId);
    }

    /**
     * This method calls the HRSRepository to get hotel division categories.
     *
     * @param integer $hotelId
     * @param integer $categoryId
     * @param integer $divisionId
     * @param boolean $withSubDivisions
     * @param boolean $sortByGroup
     *
     * @return list
     */
    public function getHotelDivisions($hotelId, $categoryId = null, $divisionId = null, $withSubDivisions = false, $sortByGroup = false)
    {
        $mediaType      = $this->container->getParameter('MEDIA_TYPE_360');
        $hotelDivisions = $this->hotelRepo->getHotelDivisions($hotelId, $mediaType, $categoryId, $divisionId, $withSubDivisions, $sortByGroup);

        // Convert it to a custom array
        if ($hotelDivisions) {
            $data = array_map(function ($a) {
                unset($a['hotel_id'], $a['hotel_name'], $a['hotel_logo'], $a['hotel_country_code']);

                return $a;
            }, $hotelDivisions);

            $return = array(
                'type' => 'hotels',
                'data' => array(
                    'id' => $hotelDivisions[0]['hotel_id'],
                    'name' => $hotelDivisions[0]['hotel_name'],
                    'logo' => $hotelDivisions[0]['hotel_logo'],
                    'country_code' => $hotelDivisions[0]['hotel_country_code'],
                    'divisions' => $data
                )
            );

            return json_encode($return, JSON_PRETTY_PRINT);
        }
    }

    //********************************************************************************************
    // Mapping functions
    /**
     * This method retrieves the map data of a hotel.
     *
     * @param  Integer $hotelId             The hotel id.
     * @param  Integer $searchRequestId     The hotel search request id.
     * @param  Integer $transactionSourceId The transaction source id.
     *
     * @return Array   The hotel map data.
     */
    public function showOnMap($hotelId, $searchRequestId, $transactionSourceId)
    {
        $this->transactionSourceId = $transactionSourceId;

        $hotelId    = intval($hotelId);
        $hotel_data = array();

        $row = $this->hotelRepo->getHotelDataById($hotelId);

        $twigData                    = array();
        $twigData['type']            = 'ht';
        $twigData['showInfobox']     = 1;
        $twigData['markerImage']     = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/pin_hot.png');
        $twigData['markerImageBlue'] = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/pin_hot_blue.png');
        $twigData['mapName']         = ' hotel: '.$row['name'];

        $mainImages = $this->getHotelMainImage($hotelId);

        $row['img']  = $mainImages[0];
        $row['link'] = $this->container->get('TTRouteUtils')->returnHotelDetailedLink($this->siteLanguage, $row['name'], $row['id']); //$this->returnHotelDetailedLink($row['name'], $row['id']);

        $hotel_data[] = $row;

        if ($searchRequestId && $searchRequestId != 0) {
            $searchRequestList = $this->em->getRepository('HotelBundle:HotelSearchResponse')->findByHotelSearchRequestId($searchRequestId);
            foreach ($searchRequestList as $item) {
                if ($item->getHotelId() != $hotelId) {
                    $row        = $this->hotelRepo->getHotelDataById($item->getHotelId());
                    $mainImages = $this->getHotelMainImage($item->getHotelId());

                    $row['img']   = $mainImages[0];
                    $row['link']  = $this->container->get('TTRouteUtils')->returnHotelDetailedLink($this->siteLanguage, $row['name'], $row['id']); //$this->returnHotelDetailedLink($row['name'], $row['id']);
                    $row['price'] = $item->getPrice().' '.$item->getIsoCurrency();
                    $hotel_data[] = $row;
                } else {
                    $hotel_data[0]['price'] = $item->getPrice().' '.$item->getIsoCurrency();
                }
            }
        }

        $twigData['LanguageGet'] = $this->siteLanguage;
        $twigData['data']        = $hotel_data;
        $twigData['latdefault']  = $hotel_data[0]['latitude'];
        $twigData['longdefault'] = $hotel_data[0]['longitude'];
        $twigData['zoomdefault'] = 14;

        return $twigData;
    }

    //*****************************************************************************************
    // Helper Functions
    /**
     * Cleans the CC number
     *
     * @param $cc
     *
     * @return
     */
    private function cleanCCNumber($cc)
    {
        return preg_replace('/\s/', '', $cc);
    }

    /**
     * Format price according to requirements: either amount only, amount with currency or amount wrapped in HTML for currency conversion
     *
     * @param $price
     * @param $null
     * @param $priceOnly
     * @param $forceConvert
     * @param $options
     *
     * @return $priceTxt Price string
     */
    private function getDisplayPrice($price, $null = true, $priceOnly = false, $forceConvert = false, $options = array())
    {
        $default_options = array(
            'append_content' => array(
                'before_currency_text' => '',
                'after_currency_text' => ' ',
                'after_price_text' => '',
            ),
            'append_class' => array(
                'currency_text' => 'pink font-25',
                'price_text' => 'pink',
            ),
            'clear_appended_class' => false
        );

        $default_options = array_replace_recursive($default_options, $options);
        if ($default_options['clear_appended_class']) {
            $default_options['append_class'] = array(
                'currency_text' => '',
                'price_text' => '',
            );
        }

        $priceTxt = '';
        $price    = json_decode(json_encode($price), true);

        if ($this->convertCurrency || $forceConvert) {
            //Currency popup
            $siteCurrency = (!empty(filter_input(INPUT_COOKIE, 'currency'))) ? filter_input(INPUT_COOKIE, 'currency') : $this->selectedCurrency;

            if ($siteCurrency != "" && $siteCurrency != $price['isoCurrency']) {
                if ($price['amount'] > 0) {
                    $conversionRate  = $this->container->get('CurrencyService')->getConversionRate($price['isoCurrency'], $this->selectedCurrency);
                    $price['amount'] = $this->container->get('CurrencyService')->currencyConvert($price['amount'], $conversionRate);
                }
                $price['isoCurrency'] = $this->selectedCurrency;
            }
        }

        $dataPrice = $price['amount'];

        if ($priceOnly) {
            return ($this->isRest) ? number_format(floatval($dataPrice), 2, null, '') : $dataPrice;
        } else {
            if (($price['amount'] <= 0) && !$null) {
                $price['amount'] = '0.00';
            }

            if ($this->convertCurrency || $forceConvert) {
                if ($this->isRest) {
                    $dataPrice = number_format(floatval($dataPrice), 2, null, '');
                    $priceTxt  = "{$price['isoCurrency']} {$dataPrice}";
                } else {
                    $amount   = ($forceConvert) ? number_format($price['amount'], 2) : number_format(floor($price['amount']));
                    $priceTxt = '<span class="price-convert" data-price="'.$dataPrice.'">'
                        .$default_options['append_content']['before_currency_text']
                        .'<span class="currency-convert-text '.$default_options['append_class']['currency_text'].'">'.$price['isoCurrency'].'</span>'
                        .$default_options['append_content']['after_currency_text']
                        .'<span class="price-convert-text '.$default_options['append_class']['price_text'].'">'.$amount.'</span>'
                        .$default_options['append_content']['after_price_text']
                        .'</span>';
                }
            } else {
                $priceTxt = $price['isoCurrency'].' '.number_format($price['amount'], 2);
            }

            return $priceTxt;
        }
    }

    /**
     * Get Fees by percentage
     *
     * @param $percent
     * @param $offerDetail
     * @param $hotel
     * @param $forceConvert
     *
     * @return
     */
    private function getFeesByPercentage($percent, $offerDetail, $hotel = 0, $forceConvert = false)
    {
        if ($hotel || $this->useHotelPrice) {
            $amount = $this->getDisplayPrice(array(
                'isoCurrency' => $offerDetail['totalPriceInclusiveHotel']['isoCurrency'],
                'amount' => round($offerDetail['totalPriceInclusiveHotel']['amount'] * ($percent * .01), 2)
                ), false, false, $forceConvert);
        } else {
            $amount = $this->getDisplayPrice(array(
                'isoCurrency' => $offerDetail['totalPriceInclusiveCustomer']['isoCurrency'],
                'amount' => round($offerDetail['totalPriceInclusiveCustomer']['amount'] * ($percent * .01), 2)
                ), false, false, $forceConvert);
        }

        return $amount;
    }

    /**
     * Adding a new cms hotel image
     *
     * @param $user_id
     * @param $hotel_id
     * @param $filename
     * @param $location
     *
     * @return
     */
    public function addHotelImage($user_id, $hotel_id, $filename, $location = '')
    {
        return $this->imageRepo->addHotelImage($user_id, $hotel_id, $filename, $location);
    }

    /**
     * Get Error Message
     *
     * @param array $error
     *
     * @return
     */
    public function getErrorMessage(array $error)
    {
        if (!isset($error['message'])) {
            $error['message'] = '';
        }

        if ((isset($error['code']) && !empty($error['code'])) && empty($error['message'])) {
            $error['message'] = $this->errorRepo->getErrorMessage($error['code']);
        }

        if (empty($error['message'])) {
            $error['message'] = $this->translator->trans("Error encountered while processing your booking.");
        }

        return $error['message'];
    }

    /**
     * Get price from string
     *
     * @param $str
     *
     * @return
     */
    private function getPriceFromString($str)
    {
        $p = explode(' ', $str);
        if (isset($p[1])) {
            return floatval(preg_replace('/[    ^\d.]/', '', $p[1]));
        }
        return 0;
    }

    /**
     * Validate Dates
     *
     * @param  $from
     * @param  $to
     * @param  $page
     *
     * @return
     */
    public function validateDates($from, $to, $page)
    {
        if (($from && !$to) || (!$from && $to) || ($page == 'offers' && !$from && !$to)) {
            return false;
        }
        if ($from && $to) {
            if (!$this->utils->validateDate($from) || !$this->utils->validateDate($to)) {
                return false;
            } else {
                $fromDate = date_create($from);
                $toDate   = date_create($to);
                $today    = date_create('today');
                if ($toDate < $fromDate || $toDate == $fromDate || $fromDate < $today || $toDate <= $today) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get CmsHotelCity info
     *
     * @param  $cityId The cityId
     * @param  $locationId The locationId
     *
     * @return The table row info
     */
    public function getCmsHotelCityInfo($cityId = 0, $locationId = 0)
    {
        return $this->hotelCityRepo->getCmsHotelCityInfo($cityId, $locationId);
    }

    /**
     * Get Selected Images of Hotels-In
     *
     * @param  $HotelSelectedCityId The cityId
     *
     * @return
     */
    public function getHotelSelectedCityImages($HotelSelectedCityId)
    {
        return $this->hotelSelectedCityImageRepo->getHotelSelectedCityImages($HotelSelectedCityId);
    }

    /**
     * Get Selected Hotel City Id
     *
     * @param  $srch_options
     *
     * @return
     */
    public function getHotelSelectedCityId($srch_options = array())
    {
        return $this->hotelSelectedCityRepo->getHotelSelectedCityId($srch_options);
    }

    /**
     * Deletes an image added by a user
     *
     * @param  $id
     * @param  $userId
     *
     * @return
     */
    public function deleteUserAddedImage($id, $userId)
    {
        return $this->imageRepo->deleteUserAddedImage($id, $userId);
    }
}
