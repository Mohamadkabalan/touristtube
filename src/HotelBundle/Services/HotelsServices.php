<?php

namespace HotelBundle\Services;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use PaymentBundle\Model\Payment as PaymentObj;
use HotelBundle\Entity\HotelRoomReservation;
use HotelBundle\Model\Hotel;
use HotelBundle\Model\HotelSC;
use HotelBundle\Model\HotelBookingCriteria;
use HotelBundle\Model\HotelApiResponse;
use HotelBundle\Model\HotelRoom;
use HotelBundle\Model\HotelRoomOffer;
use HotelBundle\Model\HotelCancellationForm;
use HotelBundle\Model\HotelRoomCancellationForm;
use HotelBundle\Model\HotelModificationForm;
use HotelBundle\Model\HotelServiceConfig;
use HotelBundle\vendors\TTApi\v1\TTApiHandler;
use HotelBundle\vendors\Amadeus\AmadeusHandler;
use HotelBundle\Entity\AmadeusHotelCity;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class HotelsServices
{
    private $em;
    private $logger;
    private $cityRepo;
    private $otaRepo;
    private $useTTApi                = true;
    private $enableThirdPartyPayment = true;
    private $TTApiHandler;
    private $isRest                  = false;
    private $siteLanguage            = null;
    private $userId                  = 0;
    private $convertCurrency         = false;
    private $selectedCurrency;
    private $transactionSourceId;
    private $validTransactionSource  = array();
    private $hotelHrsRepo;
    private $hotelRepo;
    private $pageSrc;
    public $amadeusNormalizer;

    /**
     * The __construct when we make a new instance of HotelsServices class.
     *
     * @param Utils              $utils
     * @param ContainerInterface $container
     * @param EntityManager      $em
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

        $this->hotelHrsRepo       = $this->em->getRepository('HotelBundle:CmsHotel');
        $this->hotelRepo          = $this->em->getRepository('HotelBundle:AmadeusHotel');
        $this->hsRepo             = $this->em->getRepository('HotelBundle:AmadeusHotelSource');
        $this->cityRepo           = $this->em->getRepository('HotelBundle:AmadeusHotelCity');
        $this->imageRepo          = $this->em->getRepository('HotelBundle:AmadeusHotelImage');
        $this->errorRepo          = $this->em->getRepository('HotelBundle:ErrorMessages');
        $this->reservationRepo    = $this->em->getRepository('HotelBundle:HotelReservation');
        $this->roomRepo           = $this->em->getRepository('HotelBundle:HotelRoomReservation');
        $this->searchRequestRepo  = $this->em->getRepository('HotelBundle:HotelSearchRequest');
        $this->searchResponseRepo = $this->em->getRepository('HotelBundle:HotelSearchResponse');
        $this->hotelPoiRepo       = $this->em->getRepository('HotelBundle:HotelPoi');
        $this->otaRepo            = $this->em->getRepository('HotelBundle:OtaCodes');

        $this->amadeus           = new AmadeusHandler($utils, $container);
        $this->amadeusNormalizer = $this->amadeus->getNormalizer();
        $this->TTApiHandler      = new TTApiHandler($utils, $container);

        $this->validTransactionSource['web']   = $this->container->getParameter('WEB_REFERRER');
        $this->validTransactionSource['corpo'] = $this->container->getParameter('CORPORATE_REFERRER');

        // set default transaction source to web
        $this->transactionSourceId = $this->validTransactionSource['web'];

        // set language
        $this->siteLanguage = (!isset($GLOBAL_LANG) || !$GLOBAL_LANG) ? 'en' : $GLOBAL_LANG;

        // set currency
        $selectedCurrency       = filter_input(INPUT_COOKIE, 'currency');
        $this->selectedCurrency = ($selectedCurrency == "") ? $this->amadeus->getDefaultAPICurrency() : $selectedCurrency;

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
     * @param HotelServiceConfig $serviceConfig
     */
    public function initializeService(HotelServiceConfig $serviceConfig)
    {
        $this->transactionSourceId = $serviceConfig->getTransactionSourceId();

        $this->isRest   = $serviceConfig->getIsRest();
        $this->useTTApi = $serviceConfig->isUseTTApi();
        $this->pageSrc  = $serviceConfig->getPageSrc();
    }

    /**
     * This method returns the selected currency
     */
    public function getCurrency()
    {
        return $this->selectedCurrency;
    }

    //*****************************************************************************************
    // Pre-Booking Helper Functions
    /**
     * This method creates a HotelSC object
     *
     * @param  array  $criteria
     * @return object HotelSC instance
     */
    public function getHotelSearchCriteria($criteria)
    {
        $hotelSC = new HotelSC();

        if (!empty($criteria)) {
            $hotelSC->setHotelSearchRequestId(isset($criteria['hotelSearchRequestId']) ? $criteria['hotelSearchRequestId'] : 0);
            if (isset($criteria['city'])) {
                $hotelSC->getCity()->setName(isset($criteria['city']['name']) ? $criteria['city']['name'] : '');
                $hotelSC->getCity()->setId(isset($criteria['city']['id']) ? $criteria['city']['id'] : 0);
            } else {
                $hotelSC->getCity()->setName(isset($criteria['hotelCityName']) ? $criteria['hotelCityName'] : '');
                $hotelSC->getCity()->setId(isset($criteria['cityId']) ? $criteria['cityId'] : 0);
            }

            $hotelSC->setHotelCode(isset($criteria['hotelCode']) ? $criteria['hotelCode'] : 0);
            $hotelSC->setHotelId(isset($criteria['hotelId']) ? $criteria['hotelId'] : 0);
            $hotelSC->setHotelName(isset($criteria['hotelName']) ? $criteria['hotelName'] : '');
            $hotelSC->setCountry(isset($criteria['country']) ? $criteria['country'] : '');
            $hotelSC->setLongitude(isset($criteria['longitude']) ? $criteria['longitude'] : 0);
            $hotelSC->setLatitude(isset($criteria['latitude']) ? $criteria['latitude'] : 0);

            $hotelSC->setFromDate(isset($criteria['fromDate']) ? $criteria['fromDate'] : null);
            $hotelSC->setToDate(isset($criteria['toDate']) ? $criteria['toDate'] : null);
            $hotelSC->setSingleRooms(isset($criteria['singleRooms']) ? $criteria['singleRooms'] : 0);
            $hotelSC->setDoubleRooms(isset($criteria['doubleRooms']) ? $criteria['doubleRooms'] : 1);
            $hotelSC->setAdultCount(isset($criteria['adultCount']) ? $criteria['adultCount'] : 2);
            $hotelSC->setChildCount(isset($criteria['childCount']) ? $criteria['childCount'] : 0);
            if (isset($criteria['childAge']) && !is_array($criteria['childAge'])) {
                $criteria['childAge'] = json_decode($criteria['childAge'], true);
            }
            $hotelSC->setChildAge(isset($criteria['childAge']) ? $criteria['childAge'] : array());

            $hotelSC->setPage(isset($criteria['page']) ? $criteria['page'] : 1);
            $hotelSC->setLimit($this->container->getParameter('hotels')['search_results_per_page']);
            $hotelSC->setSortBy(isset($criteria['sortBy']) ? $criteria['sortBy'] : '');
            $hotelSC->setSortOrder(isset($criteria['sortOrder']) ? $criteria['sortOrder'] : '');
            $hotelSC->setNbrStars(!empty($criteria['nbrStars']) ? $criteria['nbrStars'] : '');

            $hotelSC->setDistrict(isset($criteria['district']) ? $criteria['district'] : '');
            $hotelSC->setDistanceRange(isset($criteria['distanceRange']) ? $criteria['distanceRange'] : '');
            $hotelSC->setDistance($this->container->getParameter('hotels')['radius_distance']); //Max is 300 KM
            $hotelSC->setMaxDistance(isset($criteria['maxDistance']) ? $criteria['maxDistance'] : 0);
            $hotelSC->setBudgetRange(isset($criteria['budgetRange']) ? $criteria['budgetRange'] : array());
            $hotelSC->setPriceRange(isset($criteria['priceRange']) ? $criteria['priceRange'] : array());
            $hotelSC->setMaxPrice(isset($criteria['maxPrice']) ? $criteria['maxPrice'] : 0);

            $hotelSC->setCurrency((isset($criteria['selectedCurrency']) && !empty($criteria['selectedCurrency'])) ? $criteria['selectedCurrency'] : $this->selectedCurrency);

            $hotelSC->setEntityType(isset($criteria['entityType']) ? $criteria['entityType'] : 0);
            if ($hotelSC->getEntityType() != $this->container->getParameter('SOCIAL_ENTITY_CITY') && $hotelSC->getEntityType() != $this->container->getParameter('SOCIAL_ENTITY_HOTEL')) {
                $hotelSC->setGeoLocationSearch(true);
            }

            $hotelSC->setInfoSource(isset($criteria['infoSource']) ? $criteria['infoSource'] : '');
            $hotelSC->setSession(isset($criteria['session']) ? $criteria['session'] : json_encode(array()));

            $hotelSC->setIsCancelable(isset($criteria['isCancelable']) ? intval($criteria['isCancelable']) : 0);
            $hotelSC->setHasBreakfast(isset($criteria['hasBreakfast']) ? intval($criteria['hasBreakfast']) : 0);
            $hotelSC->setHas360(isset($criteria['has360']) ? intval($criteria['has360']) : 0);
        }

        return $hotelSC;
    }

    /**
     * This method retrieves the necessary data/criteria to redirect to the hotel details page.
     *
     * @param  Integer $reservationId
     * @return Object  HotelSC The hotel details search criteria
     */
    public function getHotelDetailsSearchCriteriaByReservationId($reservationId)
    {
        $toReturn = new HotelSC();

        // retrieve reservation information from DB
        $reservation = $this->reservationRepo->findOneById($reservationId);
        if ($reservation) {
            $hotelCode    = (!empty($reservation->getSource())) ? $reservation->getSource()->getHotelCode() : '';
            $hotelDetails = $this->getHotelInformation('hotel_details', $reservation->getHotelId(), 0, $hotelCode);

            $toReturn->setPublished($hotelDetails->isPublished());
            $toReturn->setHotelName($hotelDetails->getName());
            $toReturn->setHotelNameURL($hotelDetails->getHotelNameURL());
            $toReturn->setHotelCode($hotelDetails->getHotelCode());
            $toReturn->getCity()->setId($hotelDetails->getCity()->getId());
            $toReturn->getCity()->setName($hotelDetails->getCity()->getName());
            $toReturn->getCity()->setCode($hotelDetails->getCity()->getCode());
            $toReturn->setHotelId($hotelDetails->getHotelId());
            $toReturn->setFromDate($this->utils->formatDate($reservation->getFromDate()));
            $toReturn->setToDate($this->utils->formatDate($reservation->getToDate()));
            $toReturn->setSingleRooms($reservation->getSingleRooms());
            $toReturn->setDoubleRooms($reservation->getDoubleRooms());
        }

        return $toReturn;
    }

    /**
     * This formats an array to be used to create XML request based on search criteria
     *
     * @param  string         $searchType The type of search (e.g hotel, offer).
     * @param  object HotelSC $hotelSC    The search criteria object.
     * @return Array          containing the room criteria to be send to the API
     */
    private function getRoomCriteria($searchType, HotelSC $hotelSC)
    {
        // There's nothing in the API that specifies what would be the maximum guests per room
        // So assumming same with their samples, 2 adults and 1 child only
        $roomId         = 1;
        $roomCriteria   = array();
        $ageQualifier   = array(
            'adult' => $this->otaRepo->getOTACode('AQC', 'Adult'), // 10
            'child' => $this->otaRepo->getOTACode('AQC', 'Child') // 8
        );
        $doubleOccupant = $hotelSC->getAdultCount() - $hotelSC->getSingleRooms();
        $childAges      = (is_array($hotelSC->getChildAge())) ? $hotelSC->getChildAge() : json_decode($hotelSC->getChildAge(), true);

        $firstCriteria          = array();
        $firstCriteria['start'] = $hotelSC->getFromDate();
        $firstCriteria['end']   = $hotelSC->getToDate();

        if (!empty($hotelSC->getCity()->getCode())) {
            $firstCriteria['hotelCityCode'] = $hotelSC->getCity()->getCode();
        } elseif (!empty($hotelSC->getLongitude()) && !empty($hotelSC->getLatitude())) {
            $firstCriteria['longitude'] = $hotelSC->getLongitude();
            $firstCriteria['latitude']  = $hotelSC->getLatitude();
            $firstCriteria['distance']  = $hotelSC->getDistance();
        }

        if ($searchType == 'offer') {
            if (!empty($hotelSC->getHotelCode())) {
                $hotelCode = $hotelSC->getHotelCode();

                if (is_array($hotelCode)) {
                    if (count($hotelCode) == 1) {
                        $hotelCode = array_pop($hotelCode);
                    } else {
                        $hotelCode = array();
                        foreach ($hotelSC->getHotelCode() as $code) {
                            $hotelCode[] = array('hotelCode' => $code);
                        }
                    }
                }

                if (is_array($hotelCode)) {
                    $firstCriteria['hotelRefs'] = $hotelCode;
                } else {
                    $firstCriteria['hotelCode'] = $hotelCode;
                }
            }
        }

        if (!empty($hotelSC->getSingleRooms())) {
            if (isset($firstCriteria)) {
                $roomCriteria[$roomId] = $firstCriteria;
                unset($firstCriteria);
            }
            $roomCriteria[$roomId]['roomStayCandidate']['roomID']       = $roomId;
            $roomCriteria[$roomId]['roomStayCandidate']['quantity']     = $hotelSC->getSingleRooms();
            $roomCriteria[$roomId]['roomStayCandidate']['guestCount'][] = array(
                'count' => 1,
                'ageQualifyingCode' => $ageQualifier['adult']
            );
        }

        while ($doubleOccupant > 0) {
            $roomId = (!empty($hotelSC->getSingleRooms())) ? 2 : 1;
            for ($i = 0; $i < $hotelSC->getDoubleRooms() && $doubleOccupant > 0; $i++, $roomId++) {
                if (!isset($roomCriteria[$roomId])) {
                    if (isset($firstCriteria)) {
                        $roomCriteria[$roomId] = $firstCriteria;
                        unset($firstCriteria);
                    }

                    $roomCriteria[$roomId]['roomStayCandidate']['roomID']       = $roomId;
                    $roomCriteria[$roomId]['roomStayCandidate']['quantity']     = 1;
                    $roomCriteria[$roomId]['roomStayCandidate']['guestCount'][] = array(
                        'count' => 1,
                        'ageQualifyingCode' => $ageQualifier['adult']
                    );
                    $roomCriteria[$roomId]['count']                             = 1;

                    // Add children, maximum of 2
                    $chCount                  = 1;
                    $childAccommodation       = array();
                    $searchCriteriaChildCount = $hotelSC->getChildCount();

                    while (($chCount <= 2) && ($searchCriteriaChildCount > 0)) {
                        $childAge = array_shift($childAges);

                        // don't include on the availRequestSegment child with age less than 1
                        if (intval($childAge) < 1) {
                            $chCount++;
                            continue;
                        }

                        if (empty($childAccommodation)) {
                            $childAccommodation[0] = array(
                                'count' => 1,
                                'ageQualifyingCode' => $ageQualifier['child'],
                                'age' => $childAge
                            );
                        } elseif ($childAccommodation[0]['age'] == $childAge) {
                            $childAccommodation[0]['count'] ++;
                        } else {
                            $childAccommodation[1] = array(
                                'count' => 1,
                                'ageQualifyingCode' => $ageQualifier['child'],
                                'age' => $childAge
                            );
                        }
                        $roomCriteria[$roomId]['count'] ++;
                        $searchCriteriaChildCount--;
                        $chCount++;
                    }

                    if (!empty($childAccommodation)) {
                        $roomCriteria[$roomId]['roomStayCandidate']['guestCount'] = array_merge($roomCriteria[$roomId]['roomStayCandidate']['guestCount'], $childAccommodation);
                    }
                } else {
                    if ($roomCriteria[$roomId]['count'] >= 4) {
                        continue;
                    } else {
                        $roomCriteria[$roomId]['roomStayCandidate']['guestCount'][0]['count'] ++;
                        $roomCriteria[$roomId]['count'] ++;
                    }
                }
                $doubleOccupant--;
            }
        }

        $criteria = array();
        foreach ($roomCriteria as $room) {
            $match    = false;
            $guests   = $room['roomStayCandidate']['guestCount'];
            $tempRoom = json_encode($guests);

            foreach ($criteria as &$criterion) {
                // Loop thru final list of criteria if it's a duplicate, just increment count
                $tempCriterion = json_encode($criterion['roomStayCandidate']['guestCount']);

                if ($tempRoom === $tempCriterion) {
                    $criterion['roomStayCandidate']['quantity'] ++;
                    $match = true;
                    break;
                }
            }

            if (!$match) {
                $adult             = 0;
                $children          = 0;
                $room['guestInfo'] = '';

                foreach ($guests as $guest) {
                    if ($guest['ageQualifyingCode'] == $ageQualifier['child']) {
                        $children += $guest['count'];
                    } else {
                        $adult += $guest['count'];
                    }
                }

                // Maximum of 4 adults
                if ($adult == 1) {
                    $room['guestInfo'] = '1 Adult';
                } elseif ($adult == 2) {
                    $room['guestInfo'] = '2 Adults';
                } elseif ($adult == 3) {
                    $room['guestInfo'] = '3 Adults';
                } else {
                    $room['guestInfo'] = '4 Adults';
                }

                // Maximum of 2 children only
                if ($children == 1) {
                    $room['guestInfo'] .= ', 1 Child';
                } elseif ($children == 2) {
                    $room['guestInfo'] .= ', 2 Children';
                }

                $criteria[$room['roomStayCandidate']['roomID']] = $room;
            }
        }

        return $criteria;
    }

    /**
     * This method compares from/to dates and checks for validity
     *
     * @param  string  $from The from date
     * @param  string  $to   The to date
     * @param  String  $page The page (e.g. offers, etc).
     * @return Boolean TRUE if valid; otherwise FALSE.
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
     * This method calls the AjaxController::HotelSearchForAmadeus for a certain search term.
     *
     * @param  String $term The search term.
     * @return JSON
     */
    public function getSearchSuggestions($term)
    {
        $path['_controller'] = 'TTBundle:Ajax:HotelSearchForAmadeus';
        $query               = array(
            'term' => $term
        );

        $subRequest = $this->container->get('request_stack')->getCurrentRequest()->duplicate($query, null, $path);
        $response   = $this->container->get('http_kernel')->handle($subRequest, HttpKernelInterface::SUB_REQUEST);

        $retArr = array();
        if (!empty($response->getContent())) {
            $response = json_decode($response->getContent(), true);

            if (is_array($response)) {
                $retArr = array_map(function ($item) {
                    return array(
                        'entityType' => (isset($item['entityType'])) ? $item['entityType'] : null,
                        'cityId' => (isset($item['cityId'])) ? $item['cityId'] : 0,
                        'hotelId' => (isset($item['hotelId'])) ? $item['hotelId'] : 0,
                        'name' => (isset($item['name'])) ? $item['name'] : '',
                        'address' => (isset($item['address'])) ? $item['address'] : '',
                        'countryName' => (isset($item['countryName'])) ? $item['countryName'] : '',
                        'longitude' => (isset($item['longitude'])) ? $item['longitude'] : 0,
                        'latitude' => (isset($item['latitude'])) ? $item['latitude'] : 0
                    );
                }, $response);
            }
        }

        return $retArr;
    }

    //*****************************************************************************************
    // Avail Functions
    /**
     * This method process search request parameters to identify if we need to call API or get from our cached search response in database.
     * If date attributes of searchCriteria are empty, then get best hotels(via getBestHotels)
     * If sorting, pagination, filtering attributes of searchCriteria are specified, then we get hotels from cached search results
     * Else, we get hotels from APIs
     *
     * @param  HotelServiceConfig $serviceConfig The action data.
     * @param  HotelSC            $hotelSC       The search criteria object.
     * @return Formatted          response to be processed by AJAX receiver for direct display
     */
    public function hotelsAvailability(HotelServiceConfig $serviceConfig, HotelSC $hotelSC)
    {
        $this->initializeService($serviceConfig);

        $hotelSC->setUseTTApi($this->useTTApi);
        $hotelSC->setPrepaidOnly($serviceConfig->isPrepaidOnly());

        if (!empty($hotelSC->getHotelId())) {
            $hotelSC->getCity()->setId($this->cityRepo->getCityIdByHotelId($hotelSC->getHotelId()));
            $hotelSC->getCity()->setCode($this->cityRepo->getCityCodeByHotelId($hotelSC->getHotelId()));
        }

        $hotels      = array();
        $resultArray = array();

        if (!empty($hotelSC->getCity()->getId()) || !empty($hotelSC->getHotelId()) ||
            !empty($hotelSC->getCountry()) || !empty($hotelSC->getLongitude()) ||
            !empty($hotelSC->getLatitude())) {
            if (empty($hotelSC->getFromDate()) && empty($hotelSC->getToDate())) {
                $nightsCount = 0;

                list($hotelCount, $hotels) = $this->getBestHotels($hotelSC);
            } else {
                $nightsCount = $this->utils->computeNights($hotelSC->getFromDate(), $hotelSC->getToDate());

                // Force API call for every search, except when filtering / sorting / paginating
                if ($hotelSC->isNewSearch()) {
                    $searchRequest = null;
                } else {
                    if (!empty($hotelSC->getHotelSearchRequestId())) {
                        $searchRequest = $this->searchRequestRepo->getHotelSearchRequestById($hotelSC->getHotelSearchRequestId());
                    }

                    if (empty($searchRequest)) {
                        $searchRequest = $this->searchRequestRepo->getHotelSearchRequest($hotelSC);
                    }

                    if (!empty($searchRequest)) {
                        $hotelSC->setHotelSearchRequestId($searchRequest->getId());
                        list($hotelCount, $hotels) = $this->getCachedHotelSearch($searchRequest, $hotelSC);
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

                        // commenting amadeus api call and implement method call from our java endpoint
                        if (!$hotelSC->isUseTTApi()) {
                            $response = $this->getAvailableHotels($hotelSC);
                        } else {
                            $response = $this->getAvailableHotelsTTApi($hotelSC, $this->siteLanguage);
                        }

                        $hotelCount = $response->getHotelCount();
                        $hotels     = $response->getAvailableHotels();
                        $error      = $response->getStatus()->getError();

                        if (empty($hotels)) {
                            $this->searchRequestRepo->deleteHotelSearchRequest($searchRequest->getId());
                        } else {
                            // update the general data for the search
                            $searchRequest->setMaxPrice(floatval($hotelSC->getMaxPrice()));
                            $searchRequest->setMaxDistance(floatval($hotelSC->getMaxDistance()));

                            $this->em->persist($searchRequest);
                            $this->em->flush();
                        }
                    }
                }
            }

            // if no currency is selected in UI, we use the site-wide default currency
            $apiCurrency  = $this->amadeus->getDefaultAPICurrency();
            $siteCurrency = $hotelSC->getCurrency();

            if ($siteCurrency != $apiCurrency) {
                $conversionRate = $this->currencyService->getConversionRate($apiCurrency, $siteCurrency);

                if (!empty($hotels)) {
                    foreach ($hotels as &$hotel) {
                        $hotel['price']        = $this->currencyService->currencyConvert($hotel['price'], $conversionRate);
                        $hotel['currencyCode'] = $siteCurrency;
                    }
                }
                $hotelSC->setMaxPrice(ceil($this->currencyService->currencyConvert($hotelSC->getMaxPrice(), $conversionRate)));
            }

            if (!empty($hotels)) {
                $resultArray['hotelCount']      = $hotelCount;
                $resultArray['pageCount']       = ceil($hotelCount / $this->container->getParameter('hotels')['search_results_per_page']);
                $resultArray['maxPrice']        = $hotelSC->getMaxPrice();
                $resultArray['maxDistance']     = $hotelSC->getMaxDistance();
                $resultArray['selectedPage']    = $hotelSC->getPage();
                $resultArray['searchRequestId'] = $hotelSC->getHotelSearchRequestId();
                $resultArray['nightsCount']     = $nightsCount;

                if (empty($serviceConfig->getTemplate('mainLoopTemplate'))) {
                    $resultArray['hotels'] = $hotels;
                } else {
                    $resultArray['input']            = $hotelSC->toArray();
                    $resultArray['maxPageToDisplay'] = $this->container->getParameter('hotels')['max_pages_to_display_in_pagination']; // this should be an odd number so that our pagination control will work
                    $resultArray['LanguageGet']      = $this->siteLanguage;
                    $resultArray['isUserLoggedIn']   = $this->container->get('ApiUserServices')->isUserLoggedIn();

                    $twigData                  = $resultArray;
                    $twigData['hotels']        = $hotels;
                    $twigData['input']         = $resultArray['input'];
                    $twigData['entity_type']   = $this->container->getParameter('SOCIAL_ENTITY_HOTEL');
                    $twigData['pageSrc']       = $serviceConfig->getPageSrc();
                    $resultArray['mainLoop']   = $this->templating->render($serviceConfig->getTemplate('mainLoopTemplate'), $twigData);
                    $resultArray['pagination'] = $this->templating->render($serviceConfig->getTemplate('paginationTemplate'), $twigData);
                }
            }
        }

        if (!empty($error)) {
            $resultArray['error'] = $error;
        } elseif (empty($hotels)) {
            $resultArray['error'] = $this->errorRepo->getErrorMessage("HOTEL_7");
        }

        return $this->utils->createJSONResponse($resultArray);
    }

    /**
     * This method process response from Rest API Cron
     *
     * @param  HotelServiceConfig $serviceConfig The action data.
     * @param  Int                $requestId     The search request id
     * @param  Array              $responseArr   The array of XML response
     * @return object             HotelSC instance
     */
    public function processHotelSearchResponseFromRestAPI(HotelServiceConfig $serviceConfig, $requestId, $responseArr)
    {
        $results = array('status' => false);
        try {
            $searchRequest = $this->searchRequestRepo->findOneById($requestId);
            if ($searchRequest) {
                $this->initializeService($serviceConfig);

                $requestData                         = array();
                $requestData['hotelSearchRequestId'] = $searchRequest->getId();
                $requestData['hotelId']              = $searchRequest->getHotelId();
                $requestData['infoSource']           = $serviceConfig->getInfoSource();

                $hotelSC = $this->getHotelSearchCriteria($requestData);
                $hotelSC->setMaxPrice($searchRequest->getMaxPrice());
                $hotelSC->setMaxDistance($searchRequest->getMaxDistance());
                $hotelSC->setUseTTApi($this->useTTApi);

                $response = $this->parseAvailabilityResponse($responseArr, $hotelSC);
                $hotels   = $response->getAvailableHotels();

                // Insert them to hotel_search_response (call the needed repo method)
                $results['apiHotelCount'] = count($hotels);
                if ($results['apiHotelCount']) {
                    // For all hotels returned by API, let's get its details from our database
                    $apiHotelCodes                     = array_keys($hotels);
                    $dbAvailableHotels                 = $this->hsRepo->getHotelBySourceIdentifier('hotelCode', $apiHotelCodes, $hotelSC->getHotelCode());
                    $results['dbAvailableHotelsCount'] = count($dbAvailableHotels);

                    $dbAvailableHotelCodes = array();
                    foreach ($dbAvailableHotels as $dbHotel) {
                        $dbAvailableHotelCodes[] = $dbHotel->getHotelCode();

                        // Set the maximum price filter
                        if ($hotels[$dbHotel->getHotelCode()]['price'] > $hotelSC->getMaxPrice()) {
                            // Price is ceiled in hotelAvail
                            $hotelSC->setMaxPrice($hotels[$dbHotel->getHotelCode()]['price']);
                        }

                        // Set the maximum distance filter
                        if ($hotels[$dbHotel->getHotelCode()]['distance'] > $hotelSC->getMaxDistance()) {
                            // Distance
                            $hotelSC->setMaxDistance($hotels[$dbHotel->getHotelCode()]['distance']);
                        }

                        // process and insert hotel data to hotel_search_response table
                        $hotel = $this->getHotelTeaserData($dbHotel, $hotelSC, $requestId, true, $hotels[$dbHotel->getHotelCode()]);
                    }

                    $results['dbNotAvailableHotels'] = array();
                    $dbNotAvailableHotels            = array_diff($apiHotelCodes, $dbAvailableHotelCodes);

                    foreach ($dbNotAvailableHotels as $dbNotAvailableHotel) {
                        $results['dbNotAvailableHotels'][$dbNotAvailableHotel] = $hotels[$dbNotAvailableHotel];
                    }

                    // update the general data for the search
                    $searchRequest->setMaxPrice($hotelSC->getMaxPrice());
                    $searchRequest->setMaxDistance($hotelSC->getMaxDistance());

                    $this->em->persist($searchRequest);
                    $this->em->flush();
                }

                $results['status'] = true;
            } else {
                $results['error'] = 'search request not found';
            }
        } catch (\Exception $ex) {
            $results['error'] = $ex->getMessage();
            $results['trace'] = $ex->getTraceAsString();
        }

        return $results;
    }

    /**
     * This method finalize the data taken from db cache (hotel_search_response) to be displayed in search results and insert no-offer hotels on 4th and 14th row
     * This method also updates the passed parameter $searchCriteria attributes: maxDistance(via getHotelTeaserData).
     *
     * @param  Array   $dbAvailableHotels The list of available hotels from our database.
     * @param  Array   $hotels            The list of hotels returned by API.
     * @param  Array   $sourceIdentifier  The hotelIds.
     * @param  Integer $ctr               The counter.
     * @param  HotelSC $hotelSC           The search criteria object.
     * @param  Integer $requestId         The hotel search request id.
     * @return Array   of hotels.
     */
    private function formatCachedHotelSearch($dbAvailableHotels, &$hotels, $sourceIdentifier, &$ctr, HotelSC &$hotelSC, $requestId)
    {
        foreach ($dbAvailableHotels as $hotel) {
            // $ctr will ensure that no offer hotels are placed on 4th and 14th row, the + 2 is counting the no offers itself
            if ($ctr > ($this->container->getParameter('hotels')['search_results_per_page'] + 2)) {
                $ctr = 1;
            }

            // For marketing purposes, we'll insert the no-offer hotels on 4th and 14th row
            if ($ctr == 4 || $ctr == 14) {
                // insert 4th and 14th only if previous is a not a non-available hotel
                $prevHotel = $hotels[(count($hotels) - 1)];
                if (!empty($prevHotel) && !empty($prevHotel['price'])) {
                    $this->insertNonAvailableHotel($hotels, $hotelSC, $requestId, $ctr, $sourceIdentifier);
                }
            }

            $hotels[] = $this->getHotelTeaserData($hotel, $hotelSC, $hotelSC->getHotelSearchRequestId(), false);
            $ctr++;
        }

        return $hotels;
    }

    /**
     * This method prepares the parameters needed for Hotel_MultiSingleAvailability API call and calls it
     * This method also updates passed parameter $searchCriteria attributes: hotelCode(via parseAvailabilityResponse), maxPrice(via getDisplayableHotels), maxDistance(via getHotelTeaserData)
     *
     * @param  HotelSC $hotelSC The search criteria object.
     * @return Array   containing number of hotels found, array of hotels and string of error message (if any).
     */
    private function getAvailableHotels(HotelSC &$hotelSC)
    {
        if (empty($hotelSC->getCity()->getCode())) {
            $hotelSC->getCity()->setCode($this->cityRepo->getCityCodeByCityId($hotelSC->getCity()->getId()));
        }

        $params = array(
            'segments' => $this->getRoomCriteria('availability', $hotelSC),
            'infoSource' => $hotelSC->getInfoSource(), // MultiSource , Distribution, Leisure
            'availableOnlyIndicator' => 'true',
            'availRatesOnly' => 'true',
            'exactMatchOnly' => 'false',
            'echoToken' => 'MultiSingle',
            'summaryOnly' => 'true',
            'rateRangeOnly' => 'true',
            'rateDetailsInd' => 'true',
            'searchCacheLevel' => 'Live', //use Live instead of VeryRecent since we are accessing aggregator contents
            'bestOnlyIndicator' => 'true',
            'pricingMethod' => 'Average',
            'stateful' => false
        );

        $response = $this->amadeus->getAvailableHotels($params);

        $result = $this->getHotelListingAvailabilityResponse($response, $hotelSC);

        return $result;
    }

    /**
     * This method calls the TTHotelsRestApi and parses the response for hotel listing.
     * This method also updates passed parameter $searchCriteria attributes: hotelCode(via parseAvailabilityResponse), maxPrice(via getDisplayableHotels), maxDistance(via getHotelTeaserData)
     *
     * @param  HotelSC $hotelSC  The search criteria object.
     * @param  string  $language The site's language.
     * @return Array   containing Number of hotels found, array of hotels and string of error message (if any).
     */
    private function getAvailableHotelsTTApi(HotelSC &$hotelSC, $language)
    {
        $requestData = $hotelSC->toArray();

        $requestId                   = $hotelSC->getHotelSearchRequestId();
        $requestData['id']           = $requestId;
        $requestData['roomCriteria'] = $this->getRoomCriteria('availability', $hotelSC);

        // retrieve hotel codes if searching is by city or hotel
        if ($requestData['entityType'] == $this->container->getParameter('SOCIAL_ENTITY_CITY') || $requestData['entityType'] == $this->container->getParameter('SOCIAL_ENTITY_HOTEL')) {
            $requestData['hotelVendor']  = $this->hsRepo->getHotelVendorSourceByVendorName('amadeus');
            $requestData['hotelSources'] = $this->hsRepo->getHotelCodesBySearchCriteria($hotelSC);
        }

        $response = $this->TTApiHandler->getHotelsAvailability($language, $requestData, $hotelSC->getInfoSource());
        $result   = $this->getHotelListingAvailabilityResponse($response, $hotelSC);

        return $result;
    }

    /**
     * This method retrieves list of hotels from database based on given parameters (location and category).
     * This method also updates passed parameter $searchCriteria attributes: maxDistance(via Self, getHotelTeaserData)
     *
     * @param  HotelSC $hotelSC The search criteria object.
     * @return Array   containing number of hotels found, array of hotels
     */
    private function getBestHotels(HotelSC &$hotelSC)
    {
        $hotels            = array();
        $dbAvailableHotels = $this->hsRepo->getHotelBySearchCriteria($hotelSC);

        foreach ($dbAvailableHotels as $hotel) {
            $hotelInfo             = $this->getHotelTeaserData($hotel, $hotelSC);
            $hotelInfo['priceTxt'] = '<img alt="currency" title="'.$this->translator->trans('Enter your check-in and check-out dates to view the hotel offers').'" src="'.$this->container->get("TTRouteUtils")->generateMediaURL('/media/images/dolars.png').'" class="margintopminus11">';
            $hotels[]              = $hotelInfo;
        }

        $hotelCount = $this->hsRepo->getHotelBySearchCriteria($hotelSC, 'count');
        $hotelSC->setMaxDistance(number_format($hotelSC->getMaxDistance(), 2));

        return array($hotelCount, $hotels);
    }

    /**
     * This method retrieves list of hotels from database based on sorting / filter / pagination criteria
     * This method also updates pass parameter $searchCriteria attributes: maxPrice(via Self, getDisplayableHotels), maxDistance(via Self, getHotelTeaserData), priceRange, budgetRange
     *
     * @param  Array   $searchRequest Array of search request criteria from DB.
     * @param  HotelSC $hotelSC       The search criteria object from input fields.
     * @return Array   containing  number of hotels found, array of hotels
     */
    private function getCachedHotelSearch($searchRequest, HotelSC &$hotelSC)
    {
        $hotels = array();
        if ($this->searchResponseRepo->getHotelSearchResponseByRequestIdOnly($hotelSC->getHotelSearchRequestId(), true) > 0) {
            $apiCurrency = $this->amadeus->getDefaultAPICurrency();
            $hotelSC->setMaxPrice(ceil($searchRequest->getMaxPrice()));
            $hotelSC->setMaxDistance(ceil($searchRequest->getMaxDistance()));

            if (!empty($hotelSC->getPriceRange())) {
                $siteCurrency = $hotelSC->getCurrency(); // price filter value is passed depending on chosen site currency
                if ($siteCurrency != $apiCurrency) {
                    $conversionRate = $this->currencyService->getConversionRate($siteCurrency, $apiCurrency);
                    $priceRange     = $hotelSC->getPriceRange();
                    if ($priceRange[0] > 0) {
                        $priceRange[0] = floor($this->currencyService->currencyConvert($priceRange[0], $conversionRate));
                    }
                    $priceRange[1] = ceil($this->currencyService->currencyConvert($priceRange[1], $conversionRate));
                    $hotelSC->setPriceRange($priceRange);
                    $hotelSC->setMaxPrice($priceRange[1]);
                }
            }

            if (!empty($hotelSC->getBudgetRange())) {
                $siteCurrency = 'USD'; // budget filter value is always passed as USD
                if ($siteCurrency != $apiCurrency) {
                    $conversionRate = $this->currencyService->getConversionRate($siteCurrency, $apiCurrency);

                    $budgetRange = array();
                    foreach ($hotelSC->getBudgetRange() as $key => $value) {
                        list($min, $max) = explode('-', $value);
                        $min               = floor($this->currencyService->currencyConvert($min, $conversionRate));
                        $max               = ceil($this->currencyService->currencyConvert($max, $conversionRate));
                        $budgetRange[$key] = $min.'-'.$max;
                    }
                    $hotelSC->setBudgetRange($budgetRange);
                }
            }

            // retrieve total unique hotels per given hotel city code when using TTHotelsRestAPI
            if ($hotelSC->isUseTTApi()) {
                $hotelCount = $this->hsRepo->getTotalUniqueHotelsBySearchCriteria($hotelSC, $this->isRest);
            } else {
                $hotelCount = $this->searchResponseRepo->getHotelSearchResponse($hotelSC, 'count(res.id)');
            }

            $dbAvailableHotels = $this->searchResponseRepo->getHotelSearchResponseTTApi($hotelSC, $this->isRest);
            $hotels            = $this->getDisplayableHotels($dbAvailableHotels, $hotelSC, $hotelSC->getHotelSearchRequestId(), $hotelSC->isNewSearch());
        }

        return array($hotelCount, $hotels);
    }

    /**
     * This method prepare hotels to be displayed for the specific page requested.
     * This method also updates pass parameter $searchCriteria attributes: maxPrice, maxDistance(via getHotelTeaserData)
     *
     * @param  Array   $dbAvailableHotels The list of available hotels from our database.
     * @param  HotelSC $hotelSC           The search criteria object.
     * @param  Integer $requestId         The hotel search request id.
     * @param  Boolean $newSearch         Flags if we are doing a new search or not (Optional; default = false).
     * @param  Array   $apiHotels         The list of hotels returned by API.
     * @return Array   List of hotels for a page.
     */
    public function getDisplayableHotels($dbAvailableHotels, HotelSC &$hotelSC, $requestId, $newSearch = false, $apiHotels = array())
    {
        $ctr              = 1;
        //$limit            = 0;
        $hotels           = array();
        $noOfferHotels    = array();
        $sourceIdentifier = array();

        if (!empty($apiHotels)) {
            foreach ($dbAvailableHotels as $hotel) {
                $sourceIdentifier[] = $hotel->getHotelId();
            }
        } else {
            $identifiers = $this->searchResponseRepo->getHotelSearchResponseByRequestIdOnly($requestId, false, 'hotelId');
            foreach ($identifiers as $id) {
                $sourceIdentifier[] = $id->getHotelId();
            }
        }

        // Display the directly queried hotel on top if this is the first page on first load (searched hotel that does not have offers)
        if (!empty($hotelSC->getHotelId()) && !in_array($hotelSC->getHotelId(), $sourceIdentifier)) {
            $sourceIdentifier[] = $hotelSC->getHotelId();
            $apiData            = (isset($apiHotels[$hotelSC->getHotelCode()])) ? $apiHotels[$hotelSC->getHotelCode()] : array();

            if ($newSearch == "1") {
                $selectedHotel = $this->hsRepo->getHotelBySourceIdentifier('hotelId', $hotelSC->getHotelId(), $hotelSC->getHotelId());
                $hotels[]      = $this->getHotelTeaserData($selectedHotel, $hotelSC, $requestId, false, $apiData);
                //$limit++;
                $ctr++;
            }
        }

        if (!empty($apiHotels)) {
            foreach ($dbAvailableHotels as $hotel) {
                // $ctr will ensure that no offer hotels are placed on 4th and 14th row, the + 2 is counting the no offers itself
                if ($ctr > ($this->container->getParameter('hotels')['search_results_per_page'] + 2)) {
                    $ctr = 1;
                }

                // For marketing purposes, we'll insert the no-offer hotels on 4th and 14th row
                if ($ctr == 4 || $ctr == 14) {
                    // insert 4th and 14th only if previous is a not a non-available hotel
                    $prevHotel = $hotels[(count($hotels) - 1)];
                    if (!empty($prevHotel) && !empty($prevHotel['price'])) {
                        $this->insertNonAvailableHotel($hotels, $hotelSC, $requestId, $ctr, $sourceIdentifier);
                    }
                }

                $isSave   = false;
                $apiHotel = null;

                if (isset($apiHotels[$hotel->getHotelCode()]) && !empty($apiHotels[$hotel->getHotelCode()])) {
                    $isSave   = true;
                    $apiHotel = $apiHotels[$hotel->getHotelCode()];
                }

                $hotelData = $this->getHotelTeaserData($hotel, $hotelSC, $requestId, $isSave, $apiHotel);

                // Set the maximum price filter
                if ($hotelData['price'] > $hotelSC->getMaxPrice()) {
                    // Price is ceiled in hotelAvail
                    $hotelSC->setMaxPrice($hotelData['price']);
                }

                // Set the maximum distance filter
                if ($hotelData['distance'] > $hotelSC->getMaxDistance()) {
                    // Distance
                    $hotelSC->setMaxDistance($hotelData['distance']);
                }

                $hotels[] = $hotelData;
                $ctr++;
            }
        } else {
            // Note that most of the params are passed by reference
            $this->formatCachedHotelSearch($dbAvailableHotels, $hotels, $sourceIdentifier, $ctr, $hotelSC, $requestId);
        }

        // If we only have 3 or 13 hotels, we would still insert those no offers on the 4th and 14th
        if ($ctr == 4 || $ctr == 14) {
            $this->insertNonAvailableHotel($hotels, $hotelSC, $requestId, $ctr, $sourceIdentifier);
        }

        // For REST requests, we return all, if not, return hotels for 1 page
        if (!$this->isRest) {
            //$limit  += $this->container->getParameter('hotels')['search_results_per_page'];
            //$hotels = array_slice($hotels, 0, $limit);

            $limit = $this->container->getParameter('hotels')['search_results_per_page'] + 2;
            if (count($dbAvailableHotels) > $limit) {
                $hotels = array_slice($hotels, 0, $limit);
            }
        }

        return $hotels;
    }

    /**
     *
     * @param  array   $hotels           The list of hotels.
     * @param  HotelSC $hotelSC          The search criteria object.
     * @param  Integer $requestId        The hotel search request id.
     * @param  Integer $ctr              The counter.
     * @param  array   $sourceIdentifier The hotelIds.
     * @return $this
     */
    private function insertNonAvailableHotel(array &$hotels, HotelSC &$hotelSC, $requestId, &$ctr, array $sourceIdentifier = array())
    {
        // the data of the last hotel inserted will be our criteria for retrieving a no offer hotel.
        $lastHotel = $hotels[count($hotels) - 1];

        // the only important criteria in sorting in regards to non available hotels are the following: category and distance
        $nbrStars = $hotelSC->getNbrStars();
        if (empty($hotelSC->getNbrStars())) {
            $nbrStars = array($lastHotel['category']);
        } else {
            rsort($nbrStars);
            foreach ($nbrStars as $key => $star) {
                if ($star > $lastHotel['category']) {
                    unset($nbrStars[$key]);
                }
            }
        }

        $distanceRange = array($lastHotel['distance'], $hotelSC->getMaxDistance());

        $noOfferHotel = $this->hsRepo->getNonAvailableHotelsBySearchCriteria($hotelSC, (($ctr == 4) ? 0 : 1), $sourceIdentifier, $nbrStars, $distanceRange);

        if (!empty($noOfferHotel)) {
            $hotel    = $this->getHotelTeaserData($noOfferHotel, $hotelSC, $requestId);
            $hotels[] = $hotel;

            $ctr++;
            //$limit++;
        }

        return $this;
    }

    /**
     * This method finalize details needed to be displayed in search result page then save hotel_search_response table.
     * This method also updates pass parameter $searchCriteria attribute: maxDistance.
     *
     * @param  AmadeusHotelSource $hotelInfo Hotel information from database.
     * @param  HotelSC            $hotelSC   The search criteria object.
     * @param  Integer            $requestId The hotel search request id (Optional; default=0).
     * @param  Boolean            $save      If TRUE, save hotel to the database (Optional; default=false).
     * @param  type               $apiInfo   Hotel information from API (Optional; default=array()).
     * @return Array              of hotel data.
     */
    private function getHotelTeaserData($hotelInfo, HotelSC &$hotelSC, $requestId = 0, $save = false, $apiInfo = array())
    {
        $hotel = array(
            'hotelSearchRequestId' => intval($requestId),
            'hotelId' => intval($hotelInfo->getHotelId()),
            'hotelCode' => $hotelInfo->getHotelCode(),
            'hotelName' => $hotelInfo->getHotel()->getPropertyName(),
            'hotelNameURL' => $this->utils->cleanTitleData($hotelInfo->getHotel()->getPropertyName()),
            'category' => intval($hotelInfo->getHotel()->getStars()),
            'district' => $hotelInfo->getHotel()->getDistrict(),
            'city' => array(
                'id' => (!empty($hotelInfo->getHotel()->getCity()->getCityId())) ? intval($hotelInfo->getHotel()->getCity()->getCityId()) : 0,
                'code' => (!empty($hotelInfo->getHotel()->getCity()->getCode())) ? $hotelInfo->getHotel()->getCity()->getCode() : '',
                'name' => (!empty($hotelInfo->getHotel()->getCity()->getName())) ? $hotelInfo->getHotel()->getCity()->getName() : '',
            ),
            'country' => $hotelInfo->getHotel()->getCity()->getCountryName(),
            'distance' => 0.00,
            'distances' => array(),
            'mapImageUrl' => $this->getMapImageUrl($hotelInfo->getHotelId(), $requestId, $this->transactionSourceId),
            'currencyCode' => $hotelSC->getCurrency(),
            'price' => 0,
            'avgPrice' => 0,
            'cancelable' => 0,
            'breakfast' => 0,
            'has360' => $hotelInfo->getHotel()->has360(),
            'mainImage' => $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/hotel-icon-image2.jpg'),
            'mainImageMobile' => $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/hotel-icon-image2.jpg'),
        );

        $images                   = $this->getHotelMainImage($hotelInfo->getHotelId(), null, 2);
        $hotel['mainImage']       = $images[0];
        $hotel['mainImageMobile'] = $images[1];

        if ($hotelInfo instanceof \HotelBundle\Model\HotelTeaserData) {
            $hotel['hotelKey']     = $hotelInfo->getHotelKey();
            $hotel['distance']     = floatval($hotelInfo->getDistance());
            $hotel['distances']    = json_decode($hotelInfo->getDistances(), true);
            $hotel['currencyCode'] = $hotelInfo->getCurrencyCode();
            $hotel['price']        = floatval($hotelInfo->getPrice());
            $hotel['avgPrice']     = floatval($hotelInfo->getAvgPrice());
            $hotel['cancelable']   = intval($hotelInfo->isCancelable());
            $hotel['breakfast']    = intval($hotelInfo->hasBreakfast());

            if (!empty($hotelInfo->getMainImage())) {
                $hotel['mainImage'] = $hotelInfo->getMainImage();
            }

            if (!empty($hotelInfo->getMainImageMobile())) {
                $hotel['mainImageMobile'] = $hotelInfo->getMainImageMobile();
            }
        }

        if (!empty($apiInfo)) {
            $apiInfoFields = array(
                'currencyCode',
                'price',
                'avgPrice',
                'hotelCode',
                'cancelable',
                'breakfast',
                'distance',
                'distances'
            );

            foreach ($apiInfoFields as $apiInfoField) {
                if (isset($apiInfo[$apiInfoField]) && !empty($apiInfo[$apiInfoField])) {
                    $hotel[$apiInfoField] = $apiInfo[$apiInfoField];
                }
            }
        }

        // distances
        $hotelPoi = $this->hotelPoiRepo->getHotelPOI($hotelInfo->getHotelId());
        if (!empty($hotelPoi)) {
            foreach ($hotelPoi as $poi) {
                $distance    = floatval(number_format($poi->getDistance() / 1000, 2));
                $poiTypeName = strtolower(trim($poi->getDistancePoiTypeName()));

                switch ($poiTypeName) {
                    case 'downtown':
                        $hotel['distance'] = $distance;
                        if ($distance > $hotelSC->getMaxDistance()) {
                            $hotelSC->setMaxDistance($distance);
                        }
                        break;
                    case 'train station':
                        $poiTypeName = 'train';
                        break;
                }

                if ($distance) {
                    $hotel['distances'][$poiTypeName] = array(
                        'name' => $poi->getName(),
                        'distance' => $distance
                    );
                }
            }
        }

        if ($save) {
            $dbHotel = $hotel;

            $dbHotel['city']        = $hotel['city']['name'];
            $dbHotel['isoCurrency'] = $hotel['currencyCode'];

            // to prevent issues with mapImageUrl between: corporate and web; we won't save it on db and we will always generate one
            $dbHotel['mapImageUrl'] = "";

            unset($dbHotel['currencyCode']);

            // prevent saving default distance values for hotels with null distance.
            if (count($dbHotel['distances'])) {
                $dbHotel['distances'] = json_encode($hotel['distances']);
            } else {
                $dbHotel['distances'] = '';
            }

            $this->searchResponseRepo->insertHotelSearchResponse($dbHotel);
        }

        $hotelSource          = (!empty($hotelInfo->getSource())) ? $hotelInfo->getSource() : $this->hsRepo->getHotelSourceField('source', ['hotelId', $hotel['hotelId']]);
        $hotel['hotelSource'] = ($hotelSource) ? strtolower($hotelSource) : '';

        if ($this->isRest) {
            $hotel['mainImage'] = $hotel['mainImageMobile'];
            unset($hotel['hotelSource']);
        }
        unset($hotel['mainImageMobile']);

        // Check if the hotel has 360 or not
        $has360          = $this->has360($hotel['hotelId']);
        $hotel['has360'] = ($has360) ? true : false;

        return $hotel;
    }

    /**
     * This method parse Hotel_MultiSingleAvailability response for search result page.
     * This method also updates passed parameter $searchCriteria attributes: hotelCode.
     *
     * @param  array   $responseArr Array of XML availability search response
     * @param  HotelSC $hotelSC     The search criteria object
     * @return Array   containing array of hotels and array of error messages
     */
    private function parseAvailabilityResponse($responseArr, HotelSC &$hotelSC)
    {
        $toreturn = $this->amadeus->parseAvailabilityResponse($responseArr, $hotelSC);
        if (!$toreturn->hasError()) {
            $hotels = $this->prepareParsedHotelsForDisplay($toreturn->getAvailableHotels(), $hotelSC);
            $toreturn->setAvailableHotels($hotels);
            unset($hotels);
        }

        return $toreturn;
    }

    /**
     * This method prepares the parsed hotel availability response for display.
     * This method verifies if hotels really includes breakfast; converts room rates; and remove duplicates and select cheapest hotel.
     *
     * @param  array   $hotels  The list of parsed hotels
     * @param  HotelSC $hotelSC The search criteria object
     * @return Array   The list of hotels.
     */
    private function prepareParsedHotelsForDisplay(array $hotels, HotelSC &$hotelSC)
    {
        if (count($hotels)) {
            $duplicateHotels = array();
            foreach ($hotels as $hotelCode => &$hotel) {
                // hotel is considered to include breakfast as long as it has mealPlanCodes and not equal to: Room only and Self catering
                if (!$hotel['breakfast'] && (isset($hotel['hasMealPlanCodes']) && $hotel['hasMealPlanCodes'])) {
                    $mealPlan = $this->otaRepo->getOTAValue('MPT', $hotel['mealPlanCodes']);
                    if (!in_array($mealPlan, array('Room only', 'Self catering'))) {
                        $hotel['breakfast'] = 1;
                    }
                }

                // get converted room rate
                $rate                  = $this->getRoomRate($hotel['rate'], 'availability');
                $hotel['price']        = $rate['total']['convertedRate']['amount'];
                $hotel['currencyCode'] = $rate['total']['convertedRate']['currencyCode'];
                $hotel['avgPrice']     = $rate['daily']['convertedRate']['amount'];

                // For multisource search, it is possible that a certain hotel be returned multiple times (1 per chain)
                // For this case, we will only display details from the chain that offers the lowest rate
                $hotelId = intval($this->hsRepo->getHotelSourceField('hotelId', array('hotelCode', $hotelCode)));
                if (!isset($duplicateHotels[$hotelId])) {
                    // no duplicates found yet
                    $duplicateHotels[$hotelId]['hotelCode'] = $hotelCode;
                    $duplicateHotels[$hotelId]['price']     = $hotel['price'];
                } elseif ($hotel['price'] < $duplicateHotels[$hotelId]['price']) {
                    // the duplicate found is more cheaper so we remove the previous one
                    unset($hotels[$duplicateHotels[$hotelId]['hotelCode']]);
                    $duplicateHotels[$hotelId]['hotelCode'] = $hotelCode;
                    $duplicateHotels[$hotelId]['price']     = $hotel['price'];
                } elseif ($hotelCode != $duplicateHotels[$hotelId]['hotelCode']) {
                    // the duplicate found is more expensive, so we do not need this
                    unset($hotels[$hotelCode]);
                }

                if ($hotelSC->getHotelId() == $hotelId) {
                    $hotelSC->setHotelCode($duplicateHotels[$hotelId]['hotelCode']);
                }

                if (isset($hotels[$hotelCode]) && !empty($hotels[$hotelCode])) {
                    $hotels[$hotelCode]['hotelId'] = $hotelId;
                }
            }
        }

        return $hotels;
    }

    /**
     * This method returns the hotels to be displayed after processing the response from API.
     *
     * @param  Array             $response Array XML response from API.
     * @param  HotelSC           $hotelSC  The instance of HotelSC
     * @return HotelAvailability
     */
    public function getHotelListingAvailabilityResponse($response, HotelSC &$hotelSC)
    {
        $toreturn = $this->amadeus->getHotelListingAvailabilityResponse($response, $hotelSC);

        $hotels = $toreturn->getAvailableHotels();
        if (count($hotels)) {
            $hotels = $this->prepareParsedHotelsForDisplay($hotels, $hotelSC);

            // if hotel results from API is less than the defined limit, then we wait for a while for callback
            // to fill-in some hotel on our hotel_search_response table.
            if (count($hotels) < $hotelSC->getLimit()) {
                sleep(1);
            }

            // For all hotels returned by API, let's get its details from our database
            $dbAvailableHotels = $this->hsRepo->getHotelBySourceIdentifierTTApi(array_keys($hotels), $hotelSC);

            // get hotel count
            $hotelCount = 0;
            if (($hotelSC->getEntityType() == $this->container->getParameter('SOCIAL_ENTITY_CITY') || $hotelSC->getEntityType() == $this->container->getParameter('SOCIAL_ENTITY_HOTEL')) && $hotelSC->isUseTTApi()) {
                // get unique hotels per given search criteria
                $hotelSC->setGeoLocationSearch(false);
                $hotelCount = $this->hsRepo->getTotalUniqueHotelsBySearchCriteria($hotelSC, $this->isRest);
            } else {
                $hotelSC->setGeoLocationSearch(true);
                $hotelCount = count($dbAvailableHotels);
            }

            $toreturn->setHotelCount($hotelCount);

            // For display, we'll insert no offers betweeen the available hotels
            $hotels = $this->getDisplayableHotels($dbAvailableHotels, $hotelSC, $hotelSC->getHotelSearchRequestId(), true, $hotels);
            $toreturn->setAvailableHotels($hotels);
        }

        return $toreturn;
    }

    //*****************************************************************************************
    // Offer Functions
    /**
     * This method retrieves the data of a specific hotel.
     *
     * @param  HotelServiceConfig $serviceConfig The action data.
     * @param  HotelSC            $hotelSC       The search criteria object.
     * @return Array              The details to be displayed
     */
    public function hotelDetails(HotelServiceConfig $serviceConfig, HotelSC $hotelSC)
    {
        $this->initializeService($serviceConfig);

        $hotelSC->setHotelNameURL($this->utils->cleanTitleData($hotelSC->getHotelName()));
        $hotelSC->getCity()->setName(str_replace("+", " ", $hotelSC->getHotelName()));

        $returnData                        = array();
        $returnData['detailspage']         = 1;
        $returnData['input']               = $hotelSC->toArray();
        $returnData['input']['refererURL'] = $serviceConfig->getRoute('refererURL');

        $returnData['entity_type']  = $this->container->getParameter('SOCIAL_ENTITY_HOTEL');
        $returnData['nightsCount']  = $this->utils->computeNights($hotelSC->getFromDate(), $hotelSC->getToDate());
        $returnData['hotelDetails'] = $this->getHotelInformation('hotel_details', $hotelSC->getHotelId(), $hotelSC->getHotelSearchRequestId(), '', null, null, $returnData);

        $this->logger->addHotelActivityLog('HOTELS', 'hotel_details', $this->userId, array(
            'hotelName' => $hotelSC->getHotelName(),
            'hotelCode' => $hotelSC->getHotelCode(),
            'hotelId' => $returnData['hotelDetails']->getHotelId(),
            'name' => $returnData['hotelDetails']->getName(),
            'city' => $returnData['hotelDetails']->getCity()->getName(),
            'country' => $returnData['hotelDetails']->getCity()->getCountryName()
        ));

        $returnData['offers_rates_refresh_timespan'] = $this->container->getParameter('modules')['hotels']['vendors']['amadeus']['rates_refresh_timespan'];

        return $returnData;
    }

    /**
     * This method calls Hotel_MultiSingleAvailability (Live) API to get list of room offers and some details for a specific hotel.
     *
     * @param  HotelServiceConfig $serviceConfig The action data.
     * @param  HotelSC            $hotelSC       The search criteria object.
     * @return Formatted          response to be processed by AJAX receiver for direct display.
     */
    public function hotelOffers(HotelServiceConfig $serviceConfig, HotelSC $hotelSC)
    {
        $serviceConfig->setPage('offer');

        $this->initializeService($serviceConfig);

        $this->convertCurrency = true;

        if (empty($hotelSC->getHotelName())) {
            $hotelInfo = $this->hotelRepo->getHotelNameAndHotelCodeByHotelId($hotelSC->getHotelId());

            $hotelSC->setHotelName($hotelInfo->getName());
        }

        $hotelSC->setHotelNameURL($this->utils->cleanTitleData($hotelSC->getHotelName()));
        $hotelSC->getCity()->setName(str_replace("+", " ", $hotelSC->getHotelName()));

        $data                   = array();
        $data['totalNumOffers'] = 0;
        $data['roomOffers']     = array();

        if (!$this->validateDates($hotelSC->getFromDate(), $hotelSC->getToDate(), 'offers')) {
            $data['error'] = $this->translator->trans("Invalid Check-In/Check-Out date.");
        } elseif ($this->utils->computeNights($hotelSC->getFromDate(), $hotelSC->getToDate()) > $this->container->getParameter('hotels')['reservation_max_nights']) {
            $action_array   = array();
            $action_array[] = $this->container->getParameter('hotels')['reservation_max_nights'];
            $ms             = vsprintf($this->translator->trans("Reservations longer than %s nights are not possible."), $action_array);
            $data['error']  = $ms;
        } else {
            if (empty($hotelSC->getHotelCode())) {
                $hotelSC->setHotelCode($this->hsRepo->getHotelCodesByHotelId($hotelSC->getHotelId(), $hotelSC->getInfoSource()));
            } elseif (empty($hotelSC->getCity()->getCode())) {
                $hotelSC->getCity()->setCode($this->cityRepo->getCityCodeByHotelId($hotelSC->getHotelId()));
            }

            $data['availRequestSegment'] = $this->getRoomCriteria('offer', $hotelSC);
            $params                      = array(
                'segments' => $data['availRequestSegment'],
                'infoSource' => $hotelSC->getInfoSource(),
                'availableOnlyIndicator' => 'true',
                'availRatesOnly' => 'true',
                'exactMatchOnly' => 'false',
                'echoToken' => 'MultiSingle',
                'summaryOnly' => 'true',
                'rateRangeOnly' => 'true',
                'rateDetailsInd' => 'true',
                'searchCacheLevel' => 'Live',
                'bestOnlyIndicator' => 'false',
                'logName' => 'HotelMultiSingleAvailabilityLive',
                'stateful' => true,
                'hotelId' => $hotelSC->getHotelId(),
                'userId' => $this->userId
            );

            $logParams = array(
                'hotelName' => $hotelSC->getHotelName(),
                'hotelCode' => $hotelSC->getHotelCode(),
                'hotelId' => $hotelSC->getHotelId(),
                'fromDate' => $hotelSC->getFromDate(),
                'toDate' => $hotelSC->getToDate(),
            );

            $otaChildCOde = $this->otaRepo->getOTACode('AQC', 'Child');

            $response = $this->amadeus->getHotelOffers($params, $logParams, $this->getOtaPaymentTypes(), $otaChildCOde, $serviceConfig->isPrepaidOnly());

            if (!$response->isSuccess()) {
                // if we don't get offers in the response we show no offer message
                $data['error'] = $this->translator->trans('There is no availability on the selected dates at this time.');
            } else {
                $data['session']  = $response->getSession();
                $dataFromResponse = $this->getOfferResponse($serviceConfig, $response, $hotelSC, $data['availRequestSegment']);
                if (!empty($dataFromResponse)) {
                    $data = array_merge($data, $dataFromResponse);
                }
            }
        }

        if (empty($data['roomOffers'])) {
            $data['hotelDetails'] = $this->getHotelInformation('hotel_details', $hotelSC->getHotelId(), $hotelSC->getHotelSearchRequestId(), $hotelSC->getHotelCode());
        }

        $data['NmbrRooms'] = $hotelSC->getSingleRooms() + $hotelSC->getDoubleRooms();

        if (empty($serviceConfig->getTemplate('offersLoopTemplate'))) {
            $dbHotelDetails = $this->getHotelInformation('hotel_details', $hotelSC->getHotelId(), $hotelSC->getHotelSearchRequestId());

            $resultArray                 = array();
            $hotelDetails                = $dbHotelDetails->merge($data['hotelDetails'], false)->toArray();
            unset($hotelDetails['totalNumOffers'], $hotelDetails['roomOffers'], $hotelDetails['includedTaxAndFees'], $hotelDetails['gds'], $hotelDetails['groupSell']);
            $resultArray['hotelDetails'] = $hotelDetails;

            $resultArray['includedTaxAndFees'] = (isset($data['includedTaxAndFees'])) ? $data['includedTaxAndFees'] : [];
            $resultArray['nbrRooms']           = $data['NmbrRooms'];
            $resultArray['nbrOffers']          = $data['totalNumOffers'];
            $resultArray['roomOffers']         = $data['roomOffers'];
            $resultArray['isDistribution']     = (isset($data['gds'])) ? $data['gds'] : 0;
            $resultArray['requestSegment']     = $data['availRequestSegment'];
            $resultArray['session']            = (isset($data['session'])) ? $data['session'] : [];
            if (isset($data['error'])) {
                $resultArray['message'] = $data['error'];
            }
        } else {
            $data['nightsCount']         = $this->utils->computeNights($hotelSC->getFromDate(), $hotelSC->getToDate());
            $data['selected_currency']   = $this->selectedCurrency;
            $data['LanguageGet']         = $this->siteLanguage;
            $data['input']               = $hotelSC->toArray();
            $data['input']['refererURL'] = $serviceConfig->getRoute('refererURL');
            $data['hotelDetails']        = $data['hotelDetails']->toArray();
            $data['pageSrc']             = $serviceConfig->getPageSrc();

            $data['offersLoop']       = $this->templating->render($serviceConfig->getTemplate('offersLoopTemplate'), $data);
            $data['hotelAmenities']   = $this->templating->render($serviceConfig->getTemplate('hotelAmenitiesTemplate'), $data);
            $data['hotelFacilities']  = $this->templating->render($serviceConfig->getTemplate('hotelFacilitiesTemplate'), $data);
            $data['hotelDistances']   = $this->templating->render($serviceConfig->getTemplate('hotelDistancesTemplate'), $data);
            $data['hotelCreditCards'] = $this->templating->render($serviceConfig->getTemplate('hotelCreditCardsTemplate'), $data);

            $data['maxSingleRoomsCount'] = $hotelSC->getSingleRooms();
            $data['maxDoubleRoomsCount'] = $hotelSC->getDoubleRooms();

            $returnKeys = array('error', 'hotelDetails', 'roomOffers', 'includedTaxAndFees', 'offersLoop', 'hotelAmenities', 'hotelFacilities', 'hotelDistances', 'hotelCreditCards', 'maxSingleRoomsCount',
                'maxDoubleRoomsCount');

            if (!empty($serviceConfig->getTemplate('hotelReviewHighlightsTemplate'))) {
                $data['hotelReviewHighlights'] = $this->templating->render($serviceConfig->getTemplate('hotelReviewHighlightsTemplate'), $data);
                $returnKeys[]                  = 'hotelReviewHighlights';
            }

            $resultArray = array_intersect_key($data, array_flip($returnKeys));
        }

        return $this->utils->createJSONResponse($resultArray);
    }

    /**
     * This method is a wrapper function to process response from Hotel_MultiSingleAvailability (Live) for hotel detail page
     *
     * @param  HotelServiceConfig $serviceConfig
     * @param  HotelApiResponse   $response          The API response object.
     * @param  HotelSC            $hotelSC           The search criteria object.
     * @param  Array              $roomStayCandidate
     * @return Array              containing hotel information, hotel offers and other information needed for booking
     */
    private function getOfferResponse(HotelServiceConfig $serviceConfig, HotelApiResponse $response, HotelSC $hotelSC, $roomStayCandidate)
    {
        $toreturn = array();

        $hotel = $response->getData()['hotel'];
        if (!empty($hotel->getRoomOffers())) {
            $allowedHotelCodes = $this->hsRepo->getHotelCodesByHotelId($hotelSC->getHotelId(), $hotelSC->getInfoSource());

            // Get hotel information from response
            $toreturn['hotelDetails'] = $this->getHotelInformation($serviceConfig->getPage(), $hotelSC->getHotelId(), 0, $hotelSC->getHotelCode(), $response->getXmlResponse());

            // Hotel book iterator
            $toreturn['totalNumOffers'] = 0;

            //nightscount
            $nightsCount = $this->utils->computeNights($hotelSC->getFromDate(), $hotelSC->getToDate());

            // retrieve offer details (e.g. converted rate, category, etc.)
            $hotelRoomOffers = array();
            foreach ($hotel->getRoomOffers() as $hotelRoomOffer) {
                // Exclude offers from sources not on the db
                if (!in_array($hotelRoomOffer->getBookableInfo()['hotelRef']['hotelCode'], $allowedHotelCodes)) {
                    continue;
                }
                $roomGalleryTemplate = $serviceConfig->getTemplate('roomGalleryTemplate');

                $roomDetails                = array();
                $roomDetails['nightsCount'] = $nightsCount;

                $this->getRoomOfferDetails($hotelRoomOffer, $serviceConfig->getPage(), $roomStayCandidate, $roomGalleryTemplate, $toreturn['hotelDetails'], $roomDetails);

                $roomDetails['name']     = $roomDetails['category'] = $this->getRoomType($hotelRoomOffer);
                $roomDetails['roomId']   = $hotelRoomOffer->getRoomId();

                //Get extracted room size
                $roomSize                = $this->extractRoomSizeSqmFromText(explode('<br/>', $hotelRoomOffer->getDescription()));
                $roomDetails['roomSize'] = $roomSize;

                $hotelRoomOffers[] = $roomDetails;
            }

            // sort all offers by cheapest price
            usort($hotelRoomOffers, function ($a, $b) {
                if ($a['price'] == $b['price']) {
                    return 0;
                }

                return ($a['price'] > $b['price']) ? 1 : -1;
            });

            // group offers by category and remove duplicate offers.
            $maxSelectableRooms = array();
            foreach ($hotelRoomOffers as $hotelRoomOffer) {
                $roomId = $hotelRoomOffer['roomId'];

                // Group rooms per code
                $header = $hotelRoomOffer['type'].' '.$hotelRoomOffer['guestInfo'];
                $code   = strtolower(preg_replace('/([^a-zA-Z0-9])+/m', '', $header));
                if (!isset($toreturn['roomOffers'][$code]['header'])) {
                    $toreturn['roomOffers'][$code]['header'] = $header;
                    $maxSelectableRooms[$code]               = array();
                }

                // JS error handling - max selectable rooms per room type -  commented will have to find another way for this
                //$toreturn['maxSelectableRooms'][$roomId] = $roomStayCandidate[$roomId]['roomStayCandidate']['quantity'];
                $maxSelectableRooms[$code][$roomId] = $roomStayCandidate[$roomId]['roomStayCandidate']['quantity'];

                $roomName = strtolower(preg_replace('/([^a-zA-Z0-9])+/m', '_', $hotelRoomOffer['name']));
                if (!isset($toreturn['roomOffers'][$code][$roomName])) {
                    $toreturn['roomOffers'][$code][$roomName]             = array();
                    $toreturn['roomOffers'][$code][$roomName]['roomSize'] = array();
                }

                // get room sleeps image
                $hotelRoomOffer['roomSleepsImage'] = $this->getRoomSleepsImage($hotelRoomOffer);

                // Filter room offers
                $this->filterRoomOffers($hotelRoomOffer, $toreturn['roomOffers'][$code][$roomName], $toreturn['totalNumOffers']);
            }

            // determine max selectable room counts per room type and guest info
            $toreturn['maxSelectableRooms'] = array();
            foreach ($maxSelectableRooms as $code => $item) {
                $toreturn['maxSelectableRooms'][$code] = array_sum(array_values($item));
            }

            // filter category room sizes (each offer category will have a information/field like 'roomSize => "min - max"'.
            if (isset($toreturn['roomOffers']) && !empty($toreturn['roomOffers'])) {
                $this->filterRoomSizePerOfferCategory($toreturn['roomOffers']);
            }

            // GDS
            $toreturn['gds'] = $hotel->isGds();

            // Below reserve button - taxes
            $toreturn['includedTaxAndFees'] = array();
            foreach ($hotel->getIncludedTaxAndFees() as $tax) {
                $desc = 'Tax';
                $code = $this->otaRepo->getOTAValue('FTT', $tax['code']);
                if ($code) {
                    $desc = trim($code);
                } elseif (!empty($tax['desc'])) {
                    $desc = trim($tax['desc']);
                }

                if (!in_array($desc, array_column($toreturn['includedTaxAndFees'], 'description'))) {
                    $toreturn['includedTaxAndFees'][] = array(
                        'description' => $desc,
                        'inclusive' => ($tax['type'] == 'Inclusive') ? 'Included' : 'Excluded'
                    );
                }
            }
        }

        return $toreturn;
    }

    /**
     * This method removes duplicated offers and selects the cheapest when duplicates are found. Duplicates are identified by having the same info for: breakfast; cancelable; offer description; roomTypeCode and bedTypeCode.
     *
     * @param Array   $offer     The offer to be filtered before it's added to our filtered lists.
     * @param Array   $offerList The filtered offers.
     * @param Integer $counter   Will be updated with the total number of filtered offers. This will also update the 'counter' information in each offer.
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
                trim(strtolower($offer['bedTypeCode'])) == trim(strtolower($item['bedTypeCode']))) {
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
     * @param Array $roomOffers The room offers.
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

                $room['roomSize'] = $roomSize;
            }
        }
    }

    /**
     * This method return the sleep count and the image for a certain room type and category
     *
     * @param  Array $offer The offer.
     * @return Array The sleep count and the image
     */
    private function getRoomSleepsImage($offer)
    {
        $roomSleepsKeywords = $this->container->getParameter('hotels')['room_sleeps_keywords'];

        $count = 1;
        $image = "oneperson1.png";

        $type = trim(preg_replace("/\s+/m", " ", strtolower($offer['type'])));
        if ($type !== 'single rooms') {
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
    // Room Stay Information Functions
    /*
     * This methods return the accepted OTA payment mode
     * @return Array
     */
    private function getOtaPaymentTypes()
    {
        return array(
            'deposit' => $this->otaRepo->getOTACode('PMT', 'Deposit'), // 8
            'guaranteed' => $this->otaRepo->getOTACode('PMT', 'Guarantee') // 31
        );
    }

    /**
     * This method prepares data needed for room gallery pop-up.
     *
     * @param  HotelRoomOffer $hotelRoomOffer The room details.
     * @param  Hotel          $hotelDetails   The hotel details.
     * @param  String         $template       The template (i.e hotel-room-gallery.twig, Optional).
     * @return Rendered       twig or Array of data for rendering the gallery twig
     */
    private function getRoomGallery(HotelRoomOffer $hotelRoomOffer, $hotelDetails, $template = '')
    {
        $roomDetails = array(
            'counter' => $hotelRoomOffer->getCounter(),
            'name' => $hotelRoomOffer->getName()
        );

        if (!empty($hotelDetails->getFacilities())) {
            $facilityNames = array();
            foreach ($hotelDetails->getFacilities() as $fac) {
                $facilityNames[] = implode(', ', $fac['names']);
            }
            $roomDetails['facilities'] = implode(', ', $facilityNames);
        }

        if (!empty($hotelDetails->getImages())) {
            $roomImages  = array();
            $hotelImages = $hotelDetails->getImages();

            $imageCategoryOrder = array(
                'guest_room',
                'suite',
                'exterior_view',
                'lobby_view',
                'miscellaneous'
            );

            foreach ($imageCategoryOrder as $category) {
                if (isset($hotelImages[$category])) {
                    $roomImages = array_merge($roomImages, $hotelImages[$category]);
                }
            }

            $roomDetails['images'] = array_slice($roomImages, 0, 3);
        }

        if (!empty($template)) {
            return $this->templating->render($template, $roomDetails);
        } else {
            return $roomDetails;
        }
    }

    /**
     * This method parses the XML response and get data regarding cancellation, breakfast and prepayment
     *
     * @param  String    $requestingPage
     * @param  HotelRoom $hotelRoomOffer The room information object
     * @param  Array     $roomDetails    The parsed room details.
     * @return Array     List of room offer conditions.
     */
    private function getRoomOfferConditions($requestingPage, HotelRoom $hotelRoomOffer, &$roomDetails)
    {
        $conditions   = array();
        $forceConvert = true;

        $postBook = in_array($requestingPage, array('book', 'reservation_confirmation', 'booking_details', 'cancellation'));
        if ($postBook) {
            $forceConvert                        = false;
            $roomDetails['cancelRate']           = null;
            $roomDetails['cancelRateText']       = '';
            $roomDetails['cancellationDeadline'] = null;
        }

        // Room Type
        if ($hotelRoomOffer->isRoomTypeConverted() == 1) {
            list($rmt, $numRooms, $abt) = str_split($hotelRoomOffer->getRoomType());
            if (!empty($rmt) && !empty($numRooms) && !empty($abt)) {
                $rmt = $this->otaRepo->getOTAValue('RMT', $rmt);
                $abt = $this->otaRepo->getOTAValue('ABT', $abt);

                if (is_numeric($numRooms)) {
                    if ($numRooms > 1) {
                        $conditions['mainInfo']['roomType'] = "{$numRooms} {$abt}s";
                    } else {
                        $conditions['mainInfo']['roomType'] = "{$numRooms} {$abt}";
                    }
                } else {
                    $conditions['mainInfo']['roomType'] = "{$abt}";
                }
            }
        }

        // Breakfast
        $conditions['mainInfo']['breakfast'] = $this->translator->trans('Excluding Breakfast');
        if ($hotelRoomOffer->isWithBreakfast()) {
            $conditions['mainInfo']['breakfast'] = $this->translator->trans('Including Breakfast');
        } else {
            /* $mealCode = $this->otaRepo->getOTAValue('MPT', $hotelRoomOffer->getMealPlanCodes());
              if ($mealCode) {
              $conditions['mainInfo']['breakfast'] = $mealCode;
              } */
        }

        // Prepayment
        $prepaymentValueMode = $hotelRoomOffer->getPrepaymentValueMode();
        $depositText         = '';
        if (isset($prepaymentValueMode['price'])) {
            $depositText = 'Prepayment of '.$this->getDisplayPrice($prepaymentValueMode['price'], false, false, $forceConvert).' will be charged immediately to your credit card. ';
        } elseif (isset($prepaymentValueMode['nights'])) {
            $depositText = 'Prepayment equivalent to '.$prepaymentValueMode['nights'].' night(s) will be charged immediately to your credit card.';
        } elseif (isset($prepaymentValueMode['percent'])) {
            $depositText = 'Prepayment equivalent to '.$prepaymentValueMode['percent'].' percent of the total amount will be charged immediately to your credit card.';
        }

        // Payment Type
        $roomDetails['paymentType']        = $hotelRoomOffer->getPrepaymentType();
        $roomDetails['deposit']            = array('amount' => 0);
        $conditions['mainInfo']['deposit'] = '';
        if ($roomDetails['paymentType'] == 'deposit') {
            $conditions['mainInfo']['deposit'] = $this->translator->trans('You may need to make an advance payment.');
            if (isset($prepaymentValueMode['price'])) {
                $roomDetails['deposit'] = $prepaymentValueMode['price'];
            }
            if ($depositText) {
                $conditions['moreInfo']['deposit'][] = $depositText;
            }
        } elseif ($roomDetails['paymentType'] == 'guaranteed') {
            // put payOnSite at the top of the items for mainInfo
            $temp                                = $conditions['mainInfo'];
            $conditions['mainInfo']              = array();
            $conditions['mainInfo']['payOnSite'] = 'Pay at the hotel.';
            $conditions['mainInfo']              = array_merge($conditions['mainInfo'], $temp);

            $conditions['mainInfo']['deposit']   = 'Book without advance payment.';
            $conditions['moreInfo']['deposit'][] = 'No deposit will be charged. ';
        } elseif ($roomDetails['paymentType'] == 'onhold') {
            $conditions['mainInfo']['onHold']    = 'Your booking shall be guaranteed until '.$hotelRoomOffer->getPrepaymentHoldTime().'.';
            $conditions['mainInfo']['deposit']   = 'Book without advance payment.';
            $conditions['moreInfo']['deposit'][] = 'No deposit will be charged. ';
        }

        // Cancellation
        $now                       = date_create('now');
        $cancellationDeadline      = array();
        $roomDetails['cancelable'] = $hotelRoomOffer->isCancellable();

        $cancelText        = array();
        $specialCancelText = array();
        if (!empty($hotelRoomOffer->getCancellationPenalties())) {
            $latestDeadline = '';
            foreach ($hotelRoomOffer->getCancellationPenalties() as $penalty) {
                // Special cancellation description
                if ($penalty['description']) {
                    $desc = $this->filterCancellationPenaltyDescription($penalty['description']);
                    if (!empty($desc)) {
                        $specialCancelText[] = $desc;
                    }
                }

                // Cancellation fee
                $price      = array();
                $amountText = '';
                if (isset($penalty['price'])) {
                    $price      = $penalty['price'];
                    $amountText .= $this->getDisplayPrice($penalty['price'], false, false, $forceConvert).' will be charged.';
                } elseif (isset($penalty['nights'])) {
                    $price      = array('amount' => ($roomDetails['hotelRoomRate']['amount'] / $roomDetails['nightsCount']) * $penalty['nights']);
                    $amountText .= 'an equivalent rate of '.$penalty['nights'].' night(s) will be charged.';
                } elseif (isset($penalty['percent'])) {
                    $price      = array('amount' => ($roomDetails['hotelRoomRate']['amount'] * ($penalty['percent'] / 100)));
                    $amountText .= $penalty['percent'].' percent of the total amount will be charged.';
                }
                if ($amountText) {
                    $cancelText[] = $this->translator->trans('If cancelled or in case of no-show, ').$amountText;
                }

                // Cancellation deadline
                if ($penalty['absoluteDeadline']) {
                    $deadline = date_create($penalty['absoluteDeadline']);

                    if ($deadline > $now) {
                        $roomDetails['cancelable'] = true;

                        // get the latest date that reservation can be cancelled
                        if (!$latestDeadline || $deadline < date_create($latestDeadline)) {
                            $latestDeadline = $penalty['absoluteDeadline'];
                        }

                        // compile all cancellation deadlines and later we will sort them by date and display individual rate details according to deadline
                        $cancellationDeadline[$penalty['absoluteDeadline']] = array(
                            'cancelPrice' => $price,
                            'cancelText' => $amountText,
                            'specialCancelText' => $penalty['description'],
                        );
                    }
                }
            }
        }

        $conditions['mainInfo']['cancellation'] = '';
        if ($roomDetails['cancelable']) {
            //$conditions['mainInfo']['cancellation'] = $this->translator->trans('Cancel free of charge.');

            if ($cancellationDeadline) {
                $conditions['mainInfo']['cancellation'] = $this->translator->trans('Cancel free of charge until ').$this->utils->formatDate($latestDeadline, 'datetime').'.';

                if ($postBook) {
                    $conditions['moreInfo']['cancellation'][] = $this->translator->trans('Cancel free of charge until ').$this->utils->formatDate($latestDeadline, 'longDateTime').$this->translator->trans(' (hotel local time).');
                    $roomDetails['cancellationDeadline']      = $latestDeadline;
                    if (date_create($latestDeadline) > $now) {
                        $roomDetails['cancelRate']     = array('amount' => 0);
                        $roomDetails['cancelRateText'] = 'FREE';
                    }
                }

                ksort($cancellationDeadline);
                foreach ($cancellationDeadline as $deadline => $cancellationInfo) {
                    if ($postBook) {
                        $deadlineD = date_create($deadline);
                        if ($cancellationInfo['cancelText']) {
                            $afterDeadline                            = date_format(date_add($deadlineD, date_interval_create_from_date_string('1 second')), "Y-m-d\TH:i:sP");
                            $conditions['moreInfo']['cancellation'][] = $this->translator->trans('Cancellation fee from ').$this->utils->formatDate($afterDeadline, 'longDateTime').$this->translator->trans(' (hotel local time) : ').$cancellationInfo['cancelText'];
                        }
                        if (empty($roomDetails['cancelRate']) && ($deadlineD > $now)) {
                            $roomDetails['cancelRate']     = $cancellationInfo['cancelPrice'];
                            $roomDetails['cancelRateText'] = $cancellationInfo['cancelText'];
                        }
                    } else {
                        $conditions['moreInfo']['cancellation'][] = $this->translator->trans('Your booking can be cancelled until ').$this->utils->formatDate($deadline, 'datetime').$this->translator->trans(' (hotel local time).');

                        if ($cancellationInfo['cancelText']) {
                            $conditions['moreInfo']['cancellation'][] = $this->translator->trans('If cancelled later, or in case of no-show, ').$cancellationInfo['cancelText'];
                        }
                    }

                    if (!empty($cancellationInfo['specialCancelText'])) {
                        $desc = $this->filterCancellationPenaltyDescription($cancellationInfo['specialCancelText']);
                        if (!empty($desc)) {
                            $conditions['moreInfo']['cancellation'][] = $desc;
                        }
                    }
                }
            }
        } else {
            if ($cancelText) {
                $conditions['mainInfo']['cancellation']   = $this->translator->trans('Cancellation not free-of-charge.');
                $conditions['moreInfo']['cancellation'][] = implode("<br/>", $cancelText);
            }

            if (($roomDetails['paymentType'] == 'deposit') || ($roomDetails['paymentType'] == 'guaranteed')) {
                $conditions['moreInfo']['cancellation'][] = $this->translator->trans('A guaranteed booking can no longer be cancelled free of charge.');
            }
        }

        if ($specialCancelText) {
            $conditions['mainInfo']['cancellation']   = (isset($conditions['mainInfo']['cancellation'])) ? $conditions['mainInfo']['cancellation'] : $this->translator->trans('Special Cancellation Condition: ');
            $conditions['moreInfo']['cancellation'][] = $this->translator->trans('Special Cancellation Condition: ').implode("<br/>", $specialCancelText);
        }

        // if cancellation is empty on details page, inform the user that it will be available on the next page.
        if (!isset($conditions['moreInfo']['cancellation']) || empty($conditions['moreInfo']['cancellation'])) {
            if (!$postBook) {
                $conditions['moreInfo']['cancellation'][] = $this->translator->trans('Cancellation details will be available on the next page during booking.');
            }
        }

        return $conditions;
    }

    /**
     * This method filters the cancellation penalty description.
     *
     * @param  String $penaltyDesc The cancellation penalty description
     * @return String The filtered penalty description -- returns an empty string if penalty description is filtered-outs.
     */
    private function filterCancellationPenaltyDescription($penaltyDesc)
    {
        $toReturn = array();

        // stopwords
        $stopwords = $this->container->getParameter('hotels')['room_cancellation_penalty_description_stopwords'];
        $pattern   = '/('.implode('|', $stopwords).')/mi';

        $penaltyDesc = explode('<br/>', $penaltyDesc);
        foreach ($penaltyDesc as $desc) {
            $match = preg_match($pattern, $desc, $matches);
            if (!$match && !empty($desc)) {
                $toReturn[] = $desc;
            }
        }

        if (count($toReturn) > 0) {
            return implode('<br/>', $toReturn);
        }

        return "";
    }

    /**
     * This method prepares room offer for display.
     *
     * @param  HotelRoomOffer $hotelRoomOffer      The room information object
     * @param  String         $requestingPage
     * @param  Array          $roomStayCandidate   The room stay candidate from our room criteria. (Optional; default=array)
     * @param  String         $roomGalleryTemplate The template to render the room gallery. (Optional)
     * @param  Array          $hotelDetails        The hotel details (Optional; default=array).
     * @return Array          The room details.
     */
    private function getRoomOfferDetails(HotelRoomOffer $hotelRoomOffer, $requestingPage, $roomStayCandidate = array(), $roomGalleryTemplate = '', $hotelDetails = array(), &$roomDetails = array())
    {
        $roomDetails['counter'] = $hotelRoomOffer->getCounter();

        // Room type column - name
        $hotelRoomOffer->setName($this->getRoomType($hotelRoomOffer));
        $roomDetails['name'] = $hotelRoomOffer->getName();

        // Price column
        $rates = $this->getRoomRate($hotelRoomOffer->getRates());
        if (in_array($requestingPage, array('offer', 'book'))) {
            $roomDetails['price']      = $this->getDisplayPrice($rates['convertedRate'], false, true);
            $roomDetails['totalPrice'] = $this->getDisplayPrice($rates['convertedRate'], false, false, false, true, array('append_content' => array('after_currency_text' => '<br/>')));
        } else {
            $roomDetails['price']      = $this->getDisplayPrice($rates['hotelRate'], false, true);
            $roomDetails['totalPrice'] = $this->getDisplayPrice($rates['hotelRate'], false, false);
        }

        $roomDetails['hotelRoomRate'] = $rates['hotelRate'];
        $roomDetails['roomRate']      = $rates['convertedRate'];

        // Taxes
        $taxes                               = $this->getRoomTaxInformation($hotelRoomOffer->getIncludedTaxAndFees());
        $roomDetails['includedTaxesAndFees'] = $taxes;

        // Conditions column - more info popup - description
        $roomDetails['description'] = $hotelRoomOffer->getDescription();

        // Conditions column + more info popup
        $conditions = $this->getRoomOfferConditions($requestingPage, $hotelRoomOffer, $roomDetails);
        if (in_array($requestingPage, array('offer', 'book'))) {
            $roomDetails['conditions'] = $conditions;
        } else {
            // Breakfast
            $roomDetails['breakfastDetails'] = isset($conditions['mainInfo']['breakfast']) ? $conditions['mainInfo']['breakfast'] : '';

            // Prepayment
            //$roomDetails['prepaymentDetails'] = isset($conditions['moreInfo']['deposit']) ? $conditions['moreInfo']['deposit'] : array($conditions['mainInfo']['deposit']);
            if (isset($conditions['moreInfo']['deposit']) && !empty($conditions['moreInfo']['deposit'])) {
                $roomDetails['prepaymentDetails'] = $conditions['moreInfo']['deposit'];
            } elseif (!empty($conditions['mainInfo']['deposit'])) {
                $roomDetails['prepaymentDetails'] = array($conditions['mainInfo']['deposit']);
            }

            // Prepayment descriptions
            if (!empty($hotelRoomOffer->getPrepaymentDetails())) {
                $roomDetails['prepaymentDetails'][] = '<br/>Special Prepayment Condition: '.$hotelRoomOffer->getPrepaymentDetails();
            }

            // Cancellation
            //$roomDetails['cancellationDetails'] = isset($conditions['moreInfo']['cancellation']) ? $conditions['moreInfo']['cancellation'] : array($conditions['mainInfo']['cancellation']);
            if (isset($conditions['moreInfo']['cancellation']) && !empty($conditions['moreInfo']['cancellation'])) {
                $roomDetails['cancellationDetails'] = $conditions['moreInfo']['cancellation'];
            } elseif (!empty($conditions['mainInfo']['cancellation'])) {
                $roomDetails['cancellationDetails'] = array($conditions['mainInfo']['cancellation']);
            }
        }

        if (in_array($requestingPage, array('offer', 'book'))) {
            // Room type column - header, Max column
            $roomDetails['guestInfo']   = $roomStayCandidate[$hotelRoomOffer->getRoomId()]['guestInfo'];
            $roomDetails['type']        = ($roomDetails['guestInfo'] == '1 Adult') ? 'Single Rooms' : 'Double Rooms';
            $roomDetails['bedTypeCode'] = $hotelRoomOffer->getBedTypeCode();
            $roomDetails['roomType']    = $hotelRoomOffer->getRoomType();

            // No of rooms column - max dropdown count per room
            $roomDetails['maxRoomCount'] = ($hotelRoomOffer->getMaxRoomCount()) ? $hotelRoomOffer->getMaxRoomCount() : 1;

            // Data for Hotel Book
            $roomDetails['bookableInfo'] = $hotelRoomOffer->getBookableInfo();
        }

        if ($requestingPage == 'offer') {
            // Room type column - gallery - onclick of name
            $roomDetails['gallery'] = $this->getRoomGallery($hotelRoomOffer, $hotelDetails, $roomGalleryTemplate);
        } elseif ($requestingPage == 'book') {
            $roomDetails['roomStay'] = $hotelRoomOffer->getRoomOfferXml();
        }

        return $roomDetails;
    }

    /**
     * This method retrieves the room type of a specific offer as per OTA standard. Priority is given to the filtered category from description, then roomType, then roomTypeCode, then roomCategory.
     *
     * @param  HotelRoom $hotelRoomOffer The instance of DOMXpath from API response
     * @return String    The room type.
     */
    private function getRoomType(HotelRoom $hotelRoomOffer)
    {
        $result = '';

        $roomType     = $hotelRoomOffer->getRoomType();
        $roomTypeCode = $hotelRoomOffer->getRoomTypeCode();
        $roomCategory = $hotelRoomOffer->getRoomCategory();

        $filteredRoomCategory = $this->fetchCategoryFromDescription(explode('<br/>', $hotelRoomOffer->getDescription()));
        if (!empty($filteredRoomCategory)) {
            $result = $filteredRoomCategory;
        }

        if (empty($result)) {
            if (!empty($roomType) && isset($roomType[0])) {
                if ($roomType[0] != "*") {
                    $result = $this->otaRepo->getOTAValue('RMT', $roomType[0]);
                } elseif (!empty($roomTypeCode)) {
                    $result = $this->otaRepo->getOTAValue('RMT', $roomTypeCode[0]);
                }
            } elseif (!empty($roomCategory)) {
                $result = $this->otaRepo->getOTAValue('SEG', $roomCategory);
            }
        }

        // Replace "Unknown" and empty $result with a generic name "Guest Room"
        if (strtolower($result) == "unknown" || empty($result)) {
            $result = "Guest Room";
        }

        return ucfirst(trim(strtolower(preg_replace('/\s+/', ' ', $result))));
    }

    /**
     * This method fetches the room category from the room description free-text as per a pre-defined dictionary
     *
     * @param  Array  $descriptionTexts An array containing the Text elements from RoomRateDescription.
     * @return string The filtered category
     */
    private function fetchCategoryFromDescription(array $descriptionTexts)
    {
        $filteredCategory = '';

        // remove empty elements on the array
        $descriptionTexts = array_filter($descriptionTexts);

        $dictionaryArray = $this->container->getParameter('hotels')['room_categories_dictionary'];

        foreach ($descriptionTexts as $text) {
            // a word should be between empty spaces.
            $text = " ".strtolower($text)." ";

            // Remove some special chars and words
            $stopWords = $this->container->getParameter('hotels')['room_categories_stopwords'];
            foreach ($stopWords as $word) {
                $text = str_replace($word, " ", $text);
            }

            // remove room sizes
            $roomSizesReplacePattern = $this->container->getParameter('hotels')['room_categories_replacements']['room_sizes'];
            $text                    = preg_replace($roomSizesReplacePattern, '', $text);

            // separate any digit followed by a letter on any digitword (e.g 1king -> 1 king)
            $text = preg_replace('/\s*(\d+)([a-z]+)\s*/mi', " $1 $2 ", $text);

            // Replace shortcuts with meaningful words
            $shortcutReplaceArr = $this->container->getParameter('hotels')['room_categories_shortcuts'];
            foreach ($shortcutReplaceArr as $shortcut => $value) {
                $text = preg_replace("/\s+{$shortcut}([^a-z]|$)/i", " {$value} ", $text);
            }

            $keywords = explode(" ", $text);

            // remove empty items in our $keywords
            $keywords = array_filter($keywords);

            // Find the matches between the 2 arrays
            $matches = array_intersect($keywords, $dictionaryArray);

            // If there is only 1 Text element in descriptionTexts and there are min_words_count or less keywords in it, have the minimum match consideration as 1 else 2
            if (count($descriptionTexts) == 1 && count($keywords) <= $this->container->getParameter('hotels')['min_words_count']) {
                $minMatch = min(sizeof($keywords), $this->container->getParameter('hotels')['min_keywords_match_count']);
            } else {
                $minMatch = min(sizeof($keywords), $this->container->getParameter('hotels')['min_conditional_keywords_match_count']);
            }

            // Match is found
            if (sizeof($matches) >= $minMatch) {

                // Extract only the needed substring from text
                $startIndex = 9999;
                $endIndex   = -1;
                $keyLength  = 0;

                // Loop over the mathes to find the start and end positions of all matches
                foreach ($matches as $match) {
                    $match = " {$match} ";

                    // Find the position of each match inside text and update the start/end index of the substring
                    $position = strpos($text, $match);

                    if ($position < $startIndex) {
                        $startIndex = $position;
                    }

                    if ($position > $endIndex) {
                        // To avoid long name with useless information, because of the repetition of matched keywords, we assume that a room type name should not exceed the X words,
                        // therefore skip checking for matches after the predefined Xth word, and the characters distance is more than Y
                        if ($endIndex > -1) {
                            $segmentWords    = array();
                            $hasSegmentWords = preg_match_all("/\w(?<!\d)[\w'-]*/mi", substr($text, $startIndex, ($position - $startIndex + strlen($match))), $segmentWords);

                            if ($hasSegmentWords) {
                                // get the first matches
                                $segmentWords = array_shift($segmentWords);

                                // only include those matched words from our dictionary
                                $segmentWords = array_intersect($segmentWords, $dictionaryArray);
                            }

                            if (sizeof($segmentWords) > $this->container->getParameter('hotels')['matched_words_count_limit'] ||
                                ($position - $endIndex) > $this->container->getParameter('hotels')['matched_characters_count_limit']) {
                                break;
                            }
                        }

                        $endIndex  = $position;
                        $keyLength = strlen($match);
                    }
                }

                // Create a substring from startIndex to endIndex
                if ($startIndex != 9999 && $endIndex > -1 && $keyLength > 0) {
                    $filteredCategory = substr($text, $startIndex, ($endIndex - $startIndex + $keyLength));
                }

                break;
            }
        }

        // Replace some words on our category
        $wordsReplaceArr = $this->container->getParameter('hotels')['room_categories_replacements'];
        foreach ($wordsReplaceArr as $word => $value) {
            if ($word !== 'room_sizes') {
                $word             = str_replace('_', ' ', $word);
                $filteredCategory = str_replace($word, $value, $filteredCategory);
            }
        }

        // Make a final cleanup of filteredCategory
        $filteredCategory = trim(preg_replace("/\s{2,}/m", " ", $filteredCategory));

        // add a separator between two numbers
        $filteredCategory = trim(preg_replace("/(\d+)\s+(\d+)/m", "$1 - $2", $filteredCategory));

        return $filteredCategory;
    }

    /**
     * This method extracts the room size from the description free-text
     *
     * @param  Array $descriptionTexts An array containing the Text elements from RoomRateDescription.
     * @return Array The extracted sizes
     */
    private function extractRoomSizeSqmFromText($descriptionTexts)
    {
        $finalResult = array();

        foreach ($descriptionTexts as $text) {
            $result = array();
            $str    = preg_replace("/\s{2,}/m", " ", $text);

            $sqmRuleDictionary  = $this->container->getParameter('hotels')['room_extract_room_size_sqm_rule'];
            $sqftRuleDictionary = $this->container->getParameter('hotels')['room_extract_room_size_sqft_rule'];

            $sqmRule  = '/(\d+)\s*('.implode('|', $sqmRuleDictionary).')(s?)([^a-z0-9]|$)/mi';
            $sqftRule = '/(\d+)\s*('.implode('|', $sqftRuleDictionary).')([^a-z0-9]|$)/mi';

            preg_match_all($sqmRule, $str, $matches);

            if (sizeof($matches) > 1) {
                if (sizeof($matches[1]) > 0) {
                    $result = $matches[1];
                    if (!is_array($result)) {
                        $result = array($result);
                    }
                }
            }

            if (sizeof($result) == 0) {
                preg_match_all($sqftRule, $str, $matches);
                if (sizeof($matches) > 1) {
                    if (sizeof($matches[1]) > 0) {
                        $result = $matches[1];
                        if (!is_array($result)) {
                            $result = array($result);
                        }

                        foreach ($result as &$sqft) {
                            $sqft = $this->utils->convertSqftToSqm($sqft);
                            break;
                        }
                    }
                }
            }

            // only retrieve the first found instance per description that meets the minimum room size
            $minimum_room_size = $this->container->getParameter('hotels')['minimum_room_size'];
            foreach ($result as $roomSize) {
                if ($roomSize >= $minimum_room_size) {
                    $finalResult[] = $roomSize;
                }
            }

            if (sizeof($finalResult) > 0) {
                break;
            }
        }

        return $finalResult;
    }

    /**
     * This method retrieves, formats and convert prices from API response.
     *
     * @param  Array  $rate       The rate information parsed from API response.
     * @param  String $searchType The type of search (e.g availability).
     * @return Array  of room rate information.
     */
    private function getRoomRate(array $rate, $searchType = '')
    {
        $toCurrency = (($searchType != 'availability') && (!empty(filter_input(INPUT_COOKIE, 'currency')))) ? filter_input(INPUT_COOKIE, 'currency') : $this->amadeus->getDefaultAPICurrency();

        $rate['total']['convertedRate'] = $rate['total']['hotelRate'];
        if (!empty($rate['total']['hotelRate']['amount']) && ($rate['total']['hotelRate']['currencyCode'] != $toCurrency)) {
            $conversionRate                                 = $this->currencyService->getConversionRate($rate['total']['hotelRate']['currencyCode'], $toCurrency);
            $rate['total']['convertedRate']['amount']       = floatval($this->currencyService->currencyConvert($rate['total']['hotelRate']['amount'], $conversionRate));
            $rate['total']['convertedRate']['currencyCode'] = $toCurrency;
        }

        $rate['daily']['convertedRate'] = $rate['daily']['hotelRate'];
        if (!empty($rate['daily']['hotelRate']['amount']) && ($rate['daily']['hotelRate']['currencyCode'] != $toCurrency)) {
            $conversionRate                                 = $this->currencyService->getConversionRate($rate['daily']['hotelRate']['currencyCode'], $toCurrency);
            $rate['daily']['convertedRate']['amount']       = floatval($this->currencyService->currencyConvert($rate['daily']['hotelRate']['amount'], $conversionRate));
            $rate['daily']['convertedRate']['currencyCode'] = $toCurrency;
        }

        if ($searchType != 'availability') {
            $rate = (!empty($rate['total']['convertedRate']['amount'])) ? $rate['total'] : $rate['daily'];
        }

        return $rate;
    }

    /**
     * This method retrieves, formats and convert amounts of the room included tax and fees from API response.
     *
     * @param  array $taxes
     * @return array room tax information
     */
    private function getRoomTaxInformation(array $taxes)
    {
        $toReturn = array();
        foreach ($taxes as $tax) {
            $desc = '';
            $code = $this->otaRepo->getOTAValue('FTT', $tax['code']);
            if ($code) {
                $desc = trim($code);
            } elseif (!empty($tax['desc'])) {
                $desc = trim($tax['desc']);
            }

            if ($tax['amount']) {
                if ($tax['currencyCode'] != $this->selectedCurrency) {
                    $tax['amount']       = $this->getDisplayPrice($tax, false, false, true, false);
                    $tax['currencyCode'] = $this->selectedCurrency;
                }
            }

            $toReturn[] = array(
                'description' => $desc,
                'inclusive' => (strtolower($tax['type']) == 'inclusive') ? 'included' : 'excluded',
                'amount' => $tax['amount'],
                'currencyCode' => $tax['currencyCode'],
                'percent' => $tax['percent']
            );
        }

        return $toReturn;
    }

    //*****************************************************************************************
    // Booking Functions
    /**
     * This method checks if the reservation request is still available and valid.
     *
     * @param  Integer $reservationId
     * @param  Integer $userId
     * @return Array   The request information
     */
    public function checkReservationRequestAvailability($reservationId, $userId)
    {
        $this->userId = $userId;

        $requestInfo = array('isAvailable' => true);
        try {
            if (empty($reservationId)) {
                $requestInfo['error'] = 'Invalid reservation request id';
            } else {
                $reservation = $this->reservationRepo->findOneById($reservationId);
                if (empty($reservation)) {
                    $requestInfo['error'] = 'Reservation request not found';
                } else {
                    $requestInfo['reservation'] = $reservation;
                    $details                    = $reservation->getDetails();
                    if (empty($details)) {
                        $requestStatus = $reservation->getReservationStatus();
                        if ($requestStatus == $this->container->getParameter('hotels')['reservation_pending_approval']) {
                            $requestInfo['error'] = 'Reservation request is missing data needed to confirm booking';
                        } else {
                            $requestInfo['error'] = 'Reservation request is now in '.$requestStatus.' status.';
                        }
                    } else {
                        $details = json_decode($details, true);
                        if (!isset($details['availRequestSegment']) || empty($details['availRequestSegment'])) {
                            $requestInfo['error'] = 'Reservation availRequestSegment not found';
                        } else {
                            // get information of this specific hotel first, before we can retrieve the full rate details
                            $segments   = $details['availRequestSegment'];
                            $infoSource = $this->container->getParameter('modules')['hotels']['vendors']['amadeus']['infosource']['multisource'];
                            if (isset($details['infoSource']) && !empty($details['infoSource'])) {
                                $infoSource = $details['infoSource'];
                            }
                            $roomsReserved = $this->roomRepo->findByHotelReservationId($reservation->getId(), array('id' => 'ASC'));

                            $response = $this->amadeus->checkHotelOffersAvailability($infoSource, $segments, $reservation->getHotelId(), $this->userId, $roomsReserved);

                            if (!$response->isSuccess()) {
                                $requestInfo['error'] = $this->getErrorMessage($response->getError());
                            } else {
                                $requestInfo['enhancedPricing']['session'] = $response->getSession();
                            }
                        }
                    }
                }
            }

            if (empty($requestInfo['enhancedPricing'])) {
                $requestInfo['isAvailable'] = false;
            }
        } catch (\Exception $ex) {
            $requestInfo['isAvailable'] = false;
            $requestInfo['error']       = $ex->getMessage();
        }

        return $requestInfo;
    }

    /**
     * This method Check payment information of the hotel reservation request
     *
     * @param  HotelReservation $hotelReservation
     * @param  Integer          $userId
     * @param  Array            $session          The Amadeus session
     * @return String           An error if it occurs OR an empty string
     */
    public function checkPayment($hotelReservation, $session = null)
    {
        $error         = '';
        $transactionId = $hotelReservation->getPaymentUUID();
        if ($transactionId) {
            $payment = $this->container->get('PaymentServiceImpl')->getPaymentByUUID($transactionId);
            if (!$payment) {
                $error = $this->translator->trans('Error! No transaction found');
            }
        }
        // sign-out Amadeus session on payment error
        if (!empty($error)) {
            if (!$session) {
                $details = json_decode($hotelReservation->getDetails(), true);
                $session = $details['session'];
            }
            $this->signout($session, $hotelReservation->getHotelId(), $hotelReservation->getUserId());

            $this->reservationRepo->updateHotelBookingOnError($hotelReservation, $this->container->getParameter('hotels')['reservation_payment_failed']);

            if ($this->isRest) {
                $data = $this->container->get('PayFortServices')->responseData('', '00', '00103', $error);
                $this->logger->addHotelActivityLog('HOTELS', 'Sending MobileRQ with response: {criteria}', $hotelReservation->getUserId(), $data);
            }
        } elseif ($hotelReservation->getReservationStatus() == $this->container->getParameter('hotels')['reservation_pending_payment']) {
            $hotelReservation->setReservationStatus($this->container->getParameter('hotels')['reservation_payment_completed']);
            $this->em->persist($hotelReservation);
            $this->em->flush();
        }

        return $error;
    }

    /**
     * This method retrieve hotel reservation from the db
     *
     * @param  Array $requestData The request data.
     * @return Mixed HotelReservation; NULL if not found.
     */
    public function getHotelReservationRecord($requestData)
    {
        if (isset($requestData['transaction_id'])) {
            return $this->reservationRepo->findOneByPaymentUUID($requestData['transaction_id']);
        } elseif (isset($requestData['reference'])) {
            return $this->reservationRepo->getHotelReservation($requestData['reference']);
        } elseif (isset($requestData['reservationId'])) {
            return $this->reservationRepo->findOneById($requestData['reservationId']);
        }

        return null;
    }

    /**
     * This method does the initial payment of reservation
     *
     * @param  HotelReservation $hotelReservation
     * @param  String           $paymentType      The payment type (e.g. coa, cc).
     * @param  StringS          $userAgent        The user agent.
     * @return instance         of PaymentBundle\Model\Payment
     */
    public function getPaymentObject($hotelReservation, $paymentType, $userAgent)
    {
        $moduleTransactionId = $hotelReservation->getId(); //rand(5, 90505050505);
        // Note: setting paymentStatus, responseCode and responseMessage will be handled by PaymentServiceImpl::initializePayment
        // Call the payment service
        $paymentObj          = new PaymentObj();
        $paymentObj->setAmount($hotelReservation->getHotelGrandTotal());
        $paymentObj->setDisplayOriginalAmount($hotelReservation->getCustomerGrandTotal());

        $paymentObj->setCurrency($hotelReservation->getHotelCurrency());
        $paymentObj->setDisplayedCurrency($hotelReservation->getCustomerCurrency());

        $paymentObj->setCustomerEmail($hotelReservation->getEmail());
        $paymentObj->setModuleTransactionId($moduleTransactionId);
        $paymentObj->setCustomerFullName($hotelReservation->getFirstName()." ".$hotelReservation->getLastName());
        $paymentObj->setUserAgent($userAgent);
        $paymentObj->setCustomerIp($this->utils->getUserIP());

        $paymentObj->setCommand(PaymentObj::CMD_PROCESS_PAYMENT);

        $paymentObj->setModuleName('hotels');
        $paymentObj->setPaymentType($paymentType); // PaymentObj::CORPO_ON_ACCOUNT, PaymentObj::CREDIT_CARD
        $paymentObj->setTrxTypeId(PaymentObj::TRX_TYPE_HOTELS);

        return $paymentObj;
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
        $this->initializeService($serviceConfig);
        $this->convertCurrency           = 1;
        $this->convertCurrencyPriceFloor = 0;

        $toreturn                            = $this->hotelEnhancedPricing('book', $hotelBC);
        $toreturn['enableThirdPartyPayment'] = $this->enableThirdPartyPayment;

        $availRequestSegment = json_decode($hotelBC->getAvailRequestSegment(), true);
        reset($availRequestSegment);
        $firstKey            = key($availRequestSegment);
        if (isset($availRequestSegment[$firstKey]['hotelRefs'])) {
            unset($availRequestSegment[$firstKey]['hotelRefs']);
            $availRequestSegment[$firstKey]['hotelCityCode'] = $hotelBC->getHotelCityCode();
            $availRequestSegment[$firstKey]['hotelCode']     = $hotelBC->getHotelCode();
        }

        $toreturn['availRequestSegment'] = $availRequestSegment;

        if (!empty($toreturn) && !isset($toreturn['error'])) {
            if ($toreturn['reservationDetails']['ccRequired'] && empty($toreturn['hotelDetails']->getCreditCardDetails())) {
                // If payment type is guaranteed or deposit but hotel has no creditcard details, we'll give all possible choices, we assume that any type of credit card is acceptable
                $toreturn['hotelDetails']->setCreditCardDetails($this->utils->getCCDetails(array()));
            }

            $toreturn                   = array_merge($hotelBC->toArray(), $toreturn);
            $toreturn['reference']      = bin2hex(openssl_random_pseudo_bytes(16)); //used in web/email URLs
            $toreturn['ccValidityInfo'] = $this->utils->getCCValidityOptions();
            $toreturn['nightsCount']    = $this->utils->computeNights($hotelBC->getFromDate(), $hotelBC->getToDate());

            $toreturn['countryList']           = $this->container->get('CmsCountriesServices')->getCountryList();
            $toreturn['mobileCountryCodeList'] = $this->container->get('CmsCountriesServices')->getMobileCountryCodeList();

            $loggedInUserInfo = $this->container->get('ApiUserServices')->tt_global_get('userInfo');
            if ($loggedInUserInfo) {
                $toreturn['orderer']['firstName']   = $toreturn['guestFirstName'][0]      = $loggedInUserInfo['fname'];
                $toreturn['orderer']['lastName']    = $toreturn['guestLastName'][0]       = $loggedInUserInfo['lname'];
                $toreturn['orderer']['email']       = $toreturn['guestEmail'][0]          = $loggedInUserInfo['email'];
                $toreturn['orderer']['title']       = ($loggedInUserInfo['gender'] == 'F') ? 1 : 0;
                $toreturn['orderer']['iso3Country'] = $this->container->get('CmsCountriesServices')->getIso3CountryByCode($loggedInUserInfo['country']);
            } else {
                $toreturn['orderer']['iso3Country'] = $this->container->get('CmsCountriesServices')->getIso3CountryByIp($this->container->get('request')->getClientIp());
            }
        }

        $toreturn['refererURL'] = $hotelBC->getRefererURL();

        return $toreturn;
    }

    /**
     * This method updates the corporate reservation base on approval result
     *
     * @param HotelReservation $hotelReservation
     * @param Array            $result
     */
    public function updateCorporateReservationPaymentInfo($hotelReservation, $result)
    {
        $transactionId = null;
        if (isset($result["transaction_id"])) {
            $transactionId = $result["transaction_id"];

            // Save the returned payment_id to hotel reservation
            $hotelReservation->setPaymentUUID($transactionId);

            // update reservation status to 'PendingPayment' if reservation is approved
            if ($hotelReservation->getReservationStatus() == $this->container->getParameter('hotels')['reservation_request_approved']) {
                $hotelReservation->setReservationStatus($this->container->getParameter('hotels')['reservation_pending_payment']);
            }
        } else {
            // Change reservation status to PendingApproval.
            $hotelReservation->setReservationStatus($this->container->getParameter('hotels')['reservation_pending_approval']);
        }
        $this->em->persist($hotelReservation);
        $this->em->flush();

        return $transactionId;
    }

    /**
     * This method initiates the reservation process after saving the reservation info on the db.
     *
     * @param  HotelServiceConfig $serviceConfig    The action data.
     * @param  HotelReservation   $hotelReservation
     * @return Array              The error if any and the referrer URL.
     */
    public function processHotelReservationRequest(HotelServiceConfig $serviceConfig, $hotelReservation)
    {
        $this->initializeService($serviceConfig);

        $error      = '';
        $refererURL = '';

        // Step 1: HotelSell
        if ($hotelReservation->getReservationStatus() == $this->container->getParameter('hotels')['reservation_payment_completed']) {
            $details    = json_decode($hotelReservation->getDetails(), true);
            $refererURL = $details['refererURL'];

            list($reservationInfo, $hotelReservation) = $this->processReservationRequest($serviceConfig, $hotelReservation);
            if (isset($reservationInfo['error'])) {
                $error = $reservationInfo['error'];
            } elseif ($this->transactionSourceId == $this->validTransactionSource['corpo']) {
                // Update approval flow request
                $parameters = array(
                    'requestStatus' => $this->container->getParameter('CORPO_APPROVAL_APPROVED'),
                    'reservationId' => $hotelReservation->getId(),
                    'moduleId' => $this->container->getParameter('MODULE_HOTELS')
                );

                $this->container->get('CorpoApprovalFlowServices')->updatePendingRequestServices($parameters);
            }
        }

        if (!$error) {
            $reservationFailedStatus = array(
                $this->container->getParameter('hotels')['reservation_payment_failed'],
                $this->container->getParameter('hotels')['reservation_failed'],
                $this->container->getParameter('hotels')['reservation_payment_refunded']
            );

            if (in_array($hotelReservation->getReservationStatus(), $reservationFailedStatus)) {
                $error = $this->translator->trans("Reservation failed.");
            } elseif ($hotelReservation->getReservationStatus() == $this->container->getParameter('hotels')['reservation_pending_approval']) {
                $error = $this->translator->trans("Reservation not ready to be processed.");
            } elseif ($hotelReservation->getReservationStatus() == $this->container->getParameter('hotels')['reservation_pending_payment']) {
                $error = $this->translator->trans("Reservation pending payment.");
            }
        }

        // Step 2: Confirmation
        if (!$error && $hotelReservation->getReservationStatus() == $this->container->getParameter('hotels')['reservation_confirmed']) {
            if (!isset($reservationInfo)) {
                $reservationInfo = $this->getReservationInformation($hotelReservation->getReference(), 'reservation_confirmation');
            }

            if ($this->isRest) {
                return $reservationInfo;
            } elseif (isset($reservationInfo['error'])) {
                $error = $reservationInfo['error'];
            } else {
                $reservationInfo['entity_type'] = $this->container->getParameter('SOCIAL_ENTITY_HOTEL');
                $reservationInfo['reference']   = $hotelReservation->getReference();

                // for printView
                $reservationInfo['reservationDate'] = $reservationInfo['reservationDetails']['reservationDate'];
                $reservationInfo['recipient']       = array('email' => $reservationInfo['ordererDetails']['email'], 'name' => $reservationInfo['ordererDetails']['firstName']);

                $reservationInfo['refererURL'] = $refererURL;

                return $reservationInfo;
            }
        }

        return array('error' => $error, 'refererURL' => $refererURL);
    }

    /**
     * This method creates a HotelBookingCriteria object
     *
     * @param  array  $criteria
     * @return object HotelBookingCriteria instance
     */
    public function getHotelBookingCriteria($criteria)
    {
        $hotelBC = new HotelBookingCriteria();

        $hotelBC->setRefererURL((isset($criteria['refererURL'])) ? $criteria['refererURL'] : '');
        $hotelBC->setUserId((isset($criteria['userId'])) ? $criteria['userId'] : 0);
        $hotelBC->setFromDate($criteria['fromDate']);
        $hotelBC->setToDate($criteria['toDate']);
        $hotelBC->setHotelId($criteria['hotelId']);
        $hotelBC->setSession(json_decode($criteria['session'], true));
        $hotelBC->setAvailRequestSegment($criteria['availRequestSegment']);

        $hotelBC->setHotelCode(isset($criteria['hotelCode']) ? $criteria['hotelCode'] : '');
        $hotelBC->setReference(isset($criteria['reference']) ? $criteria['reference'] : '');
        $hotelBC->setTransactionSourceId(isset($criteria['transactionSourceId']) ? $criteria['transactionSourceId'] : '');
        $hotelBC->setRemarks(isset($criteria['remarks']) ? $this->getRemarks($criteria['remarks']) : '');
        $hotelBC->setPrepaid(isset($criteria['prepaidIndicator']) ? $criteria['prepaidIndicator'] : '');

        if (!empty($hotelBC->getHotelId()) && !empty($hotelBC->getHotelCode())) {
            $hotelSourceId = ($this->hsRepo->getHotelSourceId($hotelBC->getHotelId(), $hotelBC->getHotelCode()));
            if (!empty($hotelSourceId)) {
                $hotelBC->setSource($this->hsRepo->find($hotelSourceId));
            }
        }

        if (isset($criteria['reservationDetails']) && !empty($criteria['reservationDetails'])) {
            $reservationDetails = json_decode($criteria['reservationDetails'], true);

            $hotelBC->setReservationMode($reservationDetails['reservationMode']);
            $hotelBC->setCancelable($reservationDetails['cancelable']);

            $hotelBC->setDoubleRooms($reservationDetails['doubleCount']);
            $hotelBC->setSingleRooms($reservationDetails['singleCount']);

            $hotelBC->setHotelGrandTotal($reservationDetails['grandTotalPriceInclusiveHotelAmount']);
            $hotelBC->setHotelCurrency($reservationDetails['grandTotalPriceInclusiveHotelCurrency']);
            $hotelBC->setCustomerGrandTotal($reservationDetails['grandTotalPriceInclusiveCustomerAmount']);
            $hotelBC->setCustomerCurrency($reservationDetails['grandTotalPriceInclusiveCustomerCurrency']);
        }

        if (isset($criteria['offersSelected']) && !empty($criteria['offersSelected'])) {
            $hotelBC->setChainCode($criteria['chainCode']);
            $hotelBC->setHotelCityCode($criteria['hotelCityCode']);

            $offersSelected = json_decode($criteria['offersSelected'], true);
            $rooms          = array();
            foreach ($offersSelected as $key => $roomInfo) {
                $roomData = array(
                    'hotelRoomPrice' => $roomInfo['hotelRoomRate']['amount'],
                    'customerRoomPrice' => $roomInfo['roomRate']['amount'],
                    'guests' => array(
                        'firstName' => $criteria['guestFirstName'][$key],
                        'lastName' => $criteria['guestLastName'][$key],
                        'email' => $criteria['guestEmail'][$key],
                        'children' => array()
                    ),
                    'roomOfferDetail' => $roomInfo['roomStay'],
                    'roomInfo' => json_encode($roomInfo['bookableInfo']),
                );

                for ($i = 0; $i < 2; $i++) {
                    if (isset($criteria['childAge'][$key][$i])) {
                        $roomData['guests']['children'][] = array(
                            'childAge' => $criteria['childAge'][$key][$i],
                            'childFirstName' => $criteria['childFirstName'][$key][$i],
                            'childLastName' => $criteria['childLastName'][$key][$i],
                        );
                    }
                }

                $roomData['guests'] = json_encode($roomData['guests']);

                $rooms[$key] = $roomData;
            }
            $hotelBC->setRooms($rooms);

            $hotelBC->getOrderer()->setTitle($criteria['title']);
            $hotelBC->getOrderer()->setFirstName($criteria['firstName']);
            $hotelBC->getOrderer()->setLastName($criteria['lastName']);
            $hotelBC->getOrderer()->setCountry($criteria['country']);
            $hotelBC->getOrderer()->setDialingCode($criteria['mobileCountryCode']);
            $hotelBC->getOrderer()->setPhone($criteria['mobile']);
            $hotelBC->getOrderer()->setEmail($criteria['email']);

            $hotelBC->setDetails($this->amadeus->getDataForReservationRequest($criteria, $hotelBC));
        } elseif (isset($criteria['totalNumOffers']) && !empty($criteria['totalNumOffers'])) {
            $availRequestSegment  = json_decode($hotelBC->getAvailRequestSegment(), true);
            $bookableInfoSelected = array();
            $segments             = array();
            for ($x = 1; $x <= $criteria['totalNumOffers']; $x++) {
                $numRoomsSelected = $criteria['offer_select_'.$x];

                if ($numRoomsSelected != 0) {
                    $segment      = array();
                    $bookableInfo = json_decode($criteria['bookableInfo_'.$x], true);

                    $bookableInfoSelected[$bookableInfo['bookingCode']]             = $bookableInfo;
                    $bookableInfoSelected[$bookableInfo['bookingCode']]['quantity'] = $numRoomsSelected;

                    $segment['hotelRef'] = $bookableInfo['hotelRef'];
                    $segment['start']    = $hotelBC->getFromDate();
                    $segment['end']      = $hotelBC->getToDate();

                    // Set the hotelCode with the selected offer hotel code
                    if (empty($hotelBC->getChainCode())) {
                        $hotelBC->setChainCode($segment['hotelRef']['chainCode']);
                    }

                    if (empty($hotelBC->getHotelCityCode())) {
                        $hotelBC->setHotelCityCode($segment['hotelRef']['hotelCityCode']);
                    }

                    if (empty($hotelBC->getHotelCode())) {
                        $hotelBC->setHotelCode($segment['hotelRef']['hotelCode']);
                    }

                    $segment['guestInfo']                         = $availRequestSegment[$bookableInfo['roomID']]['guestInfo'];
                    $segment['roomStayCandidate']                 = $availRequestSegment[$bookableInfo['roomID']]['roomStayCandidate'];
                    $segment['roomStayCandidate']['quantity']     = $numRoomsSelected;
                    $segment['roomStayCandidate']['roomTypeCode'] = $bookableInfo['roomTypeCode'];
                    $segment['roomStayCandidate']['bookingCode']  = $bookableInfo['bookingCode'];

                    $segment['ratePlanCandidate']['ratePlanCode'] = $bookableInfo['ratePlanCode'];
                    if (isset($bookableInfo['mealPlanCodes'])) {
                        $segment['ratePlanCandidate']['mealPlanCodes'] = $bookableInfo['mealPlanCodes'];
                    }

                    // Hotel_Sell param
                    $segment['paymentType'] = $bookableInfo['paymentType'];

                    // I need the segments direct key to be RoomID to be used later in get Room Offer
                    // but in the event it is already set, we dont really care of it for get Room Offer
                    // but we need the data for Hotel_EnhancedPricing so make sure nothing is overwritten (9 = max searchable rooms)
                    $segmentID            = (!isset($segments[$bookableInfo['roomID']])) ? $bookableInfo['roomID'] : ($bookableInfo['roomID'] + 9 + $x);
                    $segments[$segmentID] = $segment;
                }
            }
            $hotelBC->setBookableInfoSelected($bookableInfoSelected);
            $hotelBC->setSegments($segments);
        }

        return $hotelBC;
    }

    /**
     * Refund the hotel reservation payment
     *
     * @param  HotelReservation $hotelReservation
     * @param  array            $cancellationFee
     * @return array
     */
    public function refundPayment($hotelReservation, $cancellationFee = array())
    {
        $refundData = array();
        $message    = '';

        $paymentUUID = $hotelReservation->getPaymentUUID();
        $payment     = $this->container->get('PaymentServiceImpl')->getPaymentByUUID($paymentUUID);

        if ($payment) {
            $this->logger->addHotelActivityLog('HOTELS', "Attempt to refund payment {$paymentUUID}", $this->userId);

            if ($payment->getPaymentType() == PaymentObj::CORPO_ON_ACCOUNT) {
                $accountTransaction = new \CorporateBundle\Model\AccountTransaction;
                $accountTransaction->setReservationId($hotelReservation->getId());
                $accountTransaction->setModuleId($this->container->getParameter('MODULE_HOTELS'));
                if (!empty($cancellationFee) && floatval($cancellationFee['amount']) > 0) {
                    $accountTransaction->setCurrency($cancellationFee['currencyCode']);
                    $accountTransaction->setcancellationFees($cancellationFee['amount']);
                }

                $refundData["success"] = $this->container->get('CorpoAccountTransactionsServices')->refundAccountTransactions($accountTransaction);
            } else {
                for ($attemptNumber = 1; $attemptNumber <= $this->container->getParameter('MAX_API_CALL_ATTEMPTS'); $attemptNumber++) {
                    $this->logger->addHotelActivityLog('HOTELS', "The refund[$attemptNumber] status: {criteria}", $this->userId, $refundData);

                    $refundData = $this->container->get('PaymentServiceImpl')->refund($paymentUUID);
                    if ($refundData["success"]) {
                        break;
                    }

                    if ($attemptNumber != $this->container->getParameter('MAX_API_CALL_ATTEMPTS')) {
                        usleep($this->container->getParameter('PAUSE_BETWEEN_RETRIES_US'));
                    }
                }
            }
            if ($refundData["success"]) {
                $this->reservationRepo->updateHotelBookingOnError($hotelReservation, $this->container->getParameter('hotels')['reservation_payment_refunded']);

                $message = $this->translator->trans(" You will be refunded");
            }
        }

        return $message;
    }

    /**
     * This method saves room, guests and payment details in database for later use when calling the actual reservation API
     *
     * @param  HotelServiceConfig   $serviceConfig The action data.
     * @param  HotelBookingCriteria $hotelBC       The request data.
     * @return Array                The error, The HotelReservation saved; and the payment URL.
     */
    public function saveReservationRequest(HotelServiceConfig $serviceConfig, HotelBookingCriteria $hotelBC)
    {
        $this->initializeService($serviceConfig);

        $error      = '';
        $paymentURL = '';

        // Save reservation
        $hotelBC->setReservationStatus($this->container->getParameter('hotels')['reservation_pending_payment']);
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

        // Add accountCurrencyAmount (account preffered currency) for corporate only
        if ($this->isRest) {
            $user      = $this->container->get('security.token_storage')->getToken()->getUser();
            $accountId = $user->getCorpoAccountId();
        } else {
            $sessionInfo = $this->container->get('CorpoAdminServices')->getLoggedInSessionInfo();
            $accountId   = $sessionInfo['accountId'];
        }
        if (!empty($accountId) && !empty($hotelBC->getHotelGrandTotal()) && !empty($hotelBC->getHotelCurrency())) {
            // If the account is corporate
            $preferredCurrency     = $this->container->get('CorpoAccountServices')->getAccountPreferredCurrency($accountId);
            $accountCurrencyAmount = $this->currencyService->exchangeAmount($hotelBC->getHotelGrandTotal(), $hotelBC->getHotelCurrency(), $preferredCurrency);

            if ($accountCurrencyAmount) {
                $hotelBC->setAccountCurrencyAmount($accountCurrencyAmount);
            }
        }

        $this->logger->addHotelActivityLog('HOTELS', 'reservation', $hotelBC->getUserId(), array(
            "offersSelectedCount" => count($hotelBC->getRooms()),
            "hotelName" => $hotelBC->getHotelName(),
            "criteria" => $hotelBC->toArray()
        ));

        $hotelReservation = $this->reservationRepo->saveReservation($hotelBC);

        if (!$hotelReservation) {
            // sign-out Amadeus session on db save error
            $this->signout($details['session'], $hotelBC->getHotelId(), $hotelBC->getUserId());
            $error = $this->translator->trans('Unable to save reservation');
        } else {
            // Save room reservation
            $roomStatus = $this->container->getParameter('hotels')['reservation_pending_payment'];
            foreach ($hotelBC->getRooms() as $roomData) {
                $roomData['hotelReservationId'] = $hotelReservation->getId();
                $roomData['reservationKey']     = null;
                $roomData['roomStatus']         = $roomStatus;

                $hotelRoomReservation = new HotelRoomReservation();
                $hotelRoomReservation = $this->roomRepo->saveRoomReservation($roomData, $hotelRoomReservation);
                if (!$hotelRoomReservation) {
                    $hotelReservation->setReservationStatus($this->container->getParameter('hotels')['reservation_failed']);
                    $hotelReservation->setDetails(null);
                    $this->em->persist($hotelReservation);
                    $this->em->flush();

                    $error = $this->translator->trans('Unable to save reservation rooms');
                    break;
                }
            }

            // Payment
            $fromCorporate = ($hotelBC->getTransactionSourceId() == $this->validTransactionSource['corpo']) ? 1 : 0;
            if (!$error && !$fromCorporate && $hotelBC->isPrepaid() && $this->enableThirdPartyPayment) {
                $paymentObj = $this->getPaymentObject($hotelReservation, PaymentObj::CREDIT_CARD, $serviceConfig->getUserAgent());

                // Init and payment
                $payInit     = $this->container->get('PaymentServiceImpl')->initializePayment($paymentObj);
                $paymentURL  = $payInit->getCallBackUrl();
                $paymentUUID = $payInit->getTransactionId();

                // Save the returned payment_id to hotel reservation
                $hotelReservation->setPaymentUUID($paymentUUID);
                $this->em->persist($hotelReservation);
                $this->em->flush();
            }
        }

        return array($error, $hotelReservation, $paymentURL);
    }

    /**
     * This method validates the data posted for booking and return the error message if any
     *
     * @param  array  $criteria
     * @return string
     */
    public function validateBookingRequest($criteria)
    {
        $error         = '';
        $roomTypeCount = 0;
        $selected      = 0;

        for ($x = 1; $x <= $criteria['totalNumOffers']; $x++) {
            $numRoomsSelected = $criteria['offer_select_'.$x];

            if ($numRoomsSelected != 0) {
                $selected += $numRoomsSelected;
                $roomTypeCount++;
                if ($roomTypeCount > 1) {
                    if ($criteria['gds']) {
                        $error = $this->translator->trans("For non-identical offers, please place a separate reservation for each offer type.");
                    } elseif ($selected > $this->container->getParameter('hotels')['max_nonidentical_rooms']) {
                        $action_array   = array();
                        $action_array[] = $this->container->getParameter('hotels')['max_nonidentical_rooms'];
                        $ms             = vsprintf($this->translator->trans("When booking multiple non-identical room types, the total number of selected rooms cannot exceed %s."), $action_array);
                        $error          = $ms;
                    }
                }
            }
        }

        return $error;
    }

    //*****************************************************************************************
    // Booking Helper Functions
    /**
     * This method finalizes special remarks text to be sent to Hotel_Sell API
     * @param  Array $remarks The remarks.
     * @return JSON
     */
    private function getRemarks(array $remarks)
    {
        if (isset($remarks['quietRoom']) && $remarks['quietRoom']) {
            $remarks['quietRoom'] = "I'd like a quiet room";
        }

        if (isset($remarks['nonSmoking']) && $remarks['nonSmoking']) {
            $remarks['nonSmoking'] = "Non-smoking room";
        }

        return json_encode($remarks);
    }

    /**
     * This method calls Hotel_EnhancedPricing API to get detailed rates for selected rooms.
     *
     * @param  String               $requestingPage
     * @param  HotelBookingCriteria $hotelBC        The HotelBookingCriteria object.
     * @return Array                The details parsed from Hotel_EnhancedPricing call.
     */
    public function hotelEnhancedPricing($requestingPage, HotelBookingCriteria $hotelBC)
    {
        $toreturn = array();

        $hotelDetails = $this->getHotelInformation($requestingPage, $hotelBC->getHotelId());
        $hotelDetails->setChainCode($hotelBC->getChainCode());
        $hotelDetails->setHotelCityCode($hotelBC->getHotelCityCode());
        $hotelDetails->setHotelCode($hotelBC->getHotelCode());

        $this->logger->addHotelActivityLog('HOTELS', 'booking', $this->userId, array(
            "offersSelectedCount" => count($hotelBC->getSegments()),
            "hotelName" => $hotelDetails->getName(),
            "hotelId" => $hotelDetails->getHotelId(),
            "hotelCode" => $hotelDetails->getHotelCode(),
            "fromDate" => $hotelBC->getFromDate(),
            "toDate" => $hotelBC->getToDate()
        ));

        if (!empty($hotelBC->getSegments())) {
            $otaChildCOde = $this->otaRepo->getOTACode('AQC', 'Child');

            $response = $this->amadeus->getHotelOffersEnhancedPricing($hotelBC, $this->getOtaPaymentTypes(), $otaChildCOde);

            if (!$response->isSuccess()) {
                $responseError         = $response->getError();
                $toreturn['error']     = $this->getErrorMessage($responseError);
                $toreturn['errorCode'] = $responseError['code'];
            } else {
                $toreturn = $this->getOfferPricingResponse($requestingPage, $hotelBC, $response);

                if (isset($toreturn['apiHotelInformation'])) {
                    $hotelDetails->merge($toreturn['apiHotelInformation']);
                    unset($toreturn['apiHotelInformation']);
                }
            }
            $toreturn['session']        = $response->getSession();
            $toreturn['requestSegment'] = $hotelBC->getSegments();
        }
        $toreturn['hotelDetails'] = $hotelDetails;

        return $toreturn;
    }

    /**
     * This method is a wrapper function to process Hotel_EnhancedPricing response.
     *
     * @param  String               $requestingPage
     * @param  Array                $requestData
     * @param  HotelBookingCriteria $hotelBC        The criteria object
     * @param  HotelApiResponse     $response       The response object
     * @return Array                Error or enhanced pricing information of selected offers for booking.
     */
    private function getOfferPricingResponse($requestingPage, HotelBookingCriteria $hotelBC, HotelApiResponse $response)
    {
        $roomStayCandidate    = $hotelBC->getSegments();
        $bookableInfoSelected = $hotelBC->getBookableInfoSelected();

        $returnData                 = array();
        $returnData['bookingCodes'] = array();

        $hotel = $response->getData()['hotel'];

        $nightsCount = $this->utils->computeNights($hotelBC->getFromDate(), $hotelBC->getToDate());

        if (!empty($hotel->getRoomOffers())) {
            // Get hotel information from response
            $returnData['prepaidIndicator'] = 0;
            foreach ($hotel->getRoomOffers() as $hotelRoomOffer) {
                $roomDetails                = array();
                $roomDetails['nightsCount'] = $nightsCount;
                $this->getRoomOfferDetails($hotelRoomOffer, $requestingPage, $roomStayCandidate, '', array(), $roomDetails);

                // bookingCodes
                $bookingCode = $roomDetails['bookableInfo']['bookingCode'];
                if (!in_array($bookingCode, $returnData['bookingCodes'])) {
                    $returnData['bookingCodes'][] = $bookingCode; // if more than 1 booking code, it will be processed as multiple non-identical sell, regardless if it is marked as group multiple sell
                }

                $numRoomsSelected = (isset($bookableInfoSelected[$bookingCode])) ? $bookableInfoSelected[$bookingCode]['quantity'] : 1;
                for ($i = 0; $i < $numRoomsSelected; $i++) {
                    $returnData['offersSelected'][] = $roomDetails;
                }

                // prepaidIndicator
                if ($hotelRoomOffer->isPrepaid()) {
                    $returnData['prepaidIndicator'] = '1';
                }
            }
            // GDS
            $returnData['gds'] = $hotel->isGds();

            // groupSell
            $returnData['groupSell'] = $hotel->getGroupSell();

            $returnData['apiHotelInformation'] = $this->getHotelAPIInformation($requestingPage, $hotelBC->getHotelCode(), $response->getXmlResponse());
            $returnData['reservationDetails']  = $this->getReservationDetails($requestingPage, $hotelBC, $returnData['offersSelected']);
        }

        return $returnData;
    }

    /**
     * This processes the reservation by performing API reservation process and sending notification email.
     *
     * @param  HotelServiceConfig $serviceConfig    The action data.
     * @param  HotelReservation   $hotelReservation
     * @return Array              Error details or reservation information.
     */
    private function processReservationRequest(HotelServiceConfig $serviceConfig, $hotelReservation)
    {
        $hotelDetails = $this->getHotelInformation($serviceConfig->getPage(), $hotelReservation->getHotelId(), 0, $hotelReservation->getSource()->getHotelCode());

        $apiReservation = $this->performAPIReservation($hotelReservation, $hotelDetails);
        if (isset($apiReservation['error'])) {
            $this->logger->addHotelActivityLog('HOTELS', 'Error hotel sell: '.$apiReservation['error'], $this->userId);
            $this->reservationRepo->updateHotelBookingOnError($hotelReservation, $this->container->getParameter('hotels')['reservation_failed']);

            // hotelSell not successfull, refund is necessary
            $apiReservation['error'] .= $this->refundPayment($hotelReservation);

            $toreturn = $apiReservation;
        } else {
            $reservationInfo = $this->getReservationInformation($hotelReservation->getReference(), 'reservation_confirmation', $hotelDetails);

            if (!isset($reservationInfo['error']) && isset($apiReservation['toNotify'])) {
                //Send confirmation email
                $emailVars                            = $reservationInfo;
                $emailVars['action']                  = 'reservation';
                $emailVars['hotelDetailsRouteName']   = $serviceConfig->getRoute('hotelDetailsRouteName');
                $emailVars['bookingDetailsRouteName'] = $serviceConfig->getRoute('bookingDetailsRouteName');
                $emailVars['reference']               = $hotelReservation->getReference();
                $emailVars['reservationDate']         = $reservationInfo['reservationDetails']['reservationDate'];

                $unique_recipient = array();
                foreach ($apiReservation['toNotify'] as $recipient) {
                    $emailVars['recipient'] = $recipient;
                    if (!in_array($recipient['email'], $unique_recipient)) {
                        $unique_recipient[] = $recipient['email'];

                        $msg = $this->templating->render($serviceConfig->getTemplate('confirmationEmailTemplate'), $emailVars);
                        $this->emailServices->addEmailData($recipient['email'], $msg, 'TouristTube Hotels - Your Booking', 'TouristTube.com', 0);
                    }
                }
            }
            $toreturn = $reservationInfo;
        }

        // get updated reservation from the db
        $hotelReservation = $this->em->getRepository('HotelBundle:HotelReservation')->getHotelReservation($hotelReservation->getReference());

        return array($toreturn, $hotelReservation);
    }

    /**
     * This method calls PNR_AddMultiElements, Hotel_Sell, PNR_AddMultiElements (Commit if necessary).
     *
     * @param  HotelReservation $hotelReservation
     * @param  Array            $hotelDetails     The hotel details.
     * @return Array            Error details or email notification recipients
     */
    private function performAPIReservation($hotelReservation, $hotelDetails)
    {
        $details = json_decode($hotelReservation->getDetails(), true);
        $session = $details['session'];
        $error   = '';

        $toNotify                 = array();
        $roomGuests               = array();
        $ordererIsGuest           = false;
        $number                   = $holderNumber             = 2;
        // check if reservee is allowed to approve of this reservation
        $reserveeAllowedToApprove = $this->isReserveeAllowedToApprove($hotelReservation->getUserId(), $hotelReservation->getTransactionSourceId());

        // notify also the approver if resevee is not allowed to approved
        if (!$reserveeAllowedToApprove) {
            $fromCorporate = ($hotelReservation->getTransactionSourceId() == $this->validTransactionSource['corpo']) ? 1 : 0;

            // since we are already performing reservation; we expect that the current user is an approver
            if ($fromCorporate) {
                $userArray = $this->container->get('UserServices')->getUserDetails(array('id' => $this->userId));
                if (isset($userArray[0]) && !empty($userArray[0])) {
                    $user = $userArray[0];

                    if ($user['cu_isCorporateAccount']) {
                        $toNotify[] = array('email' => $user['cu_youremail'], 'name' => $user['cu_fname'], 'withCancellationLink' => true, 'approver' => true);
                    }
                }
            }
        }

        // add the orderer info so we can use it to reference later in hotel sell
        $orderer = array(
            'title' => ($hotelReservation->getTitle() === 1) ? 'Ms' : 'Mr',
            'firstName' => $hotelReservation->getFirstName(),
            'lastName' => $hotelReservation->getLastName(),
            'email' => $hotelReservation->getEmail(),
            'repType' => 'BHN', // booking holder non occupant, update if it is included in the guest list
            'guestType' => 'RMN', // room main pax non occupant
            'children' => array(),
            'bookingCode' => '',
            'paymentType' => '0'
        );

        // add orderer as email recipient
        $toNotify[] = array('email' => $orderer['email'], 'name' => $orderer['firstName'], 'withCancellationLink' => $reserveeAllowedToApprove);

        $roomsQuery  = $this->roomRepo->createQueryBuilder('r')->where('r.hotelReservationId = :id')->setParameter('id', $hotelReservation->getId());
        $ordererRoom = $this->roomRepo->createQueryBuilder('r')->where('r.hotelReservationId = :id')->setParameter('id', $hotelReservation->getId())
                ->andWhere('r.guests LIKE :guests')->setParameter('guests', '%'.sprintf('"firstName":"%s","lastName":"%s"', $orderer['firstName'], $orderer['lastName']).'%')
                ->getQuery()->getResult();

        // if the orderer is included in the guest list, then get the equivalent room detail and prevent duplicates
        if (!empty($ordererRoom)) {
            $ordererRoom     = $ordererRoom[0];
            $ordererGuests   = json_decode($ordererRoom->getGuests(), true);
            $ordererRoomInfo = json_decode($ordererRoom->getRoomInfo(), true);

            $orderer['repType']     = 'BHO'; // booking holder occupant
            $orderer['guestType']   = 'RMO'; // room main pax occupant
            $orderer['bookingCode'] = $ordererRoomInfo['bookingCode'];
            $orderer['paymentType'] = $ordererRoomInfo['paymentType']; // Guaranteed = 1; Deposit = 2, OnHold/Not set = 0
            $ordererIsGuest         = true;
            $number++;

            foreach ($ordererGuests['children'] as $child) {
                $orderer['children'][$number++] = $child;
            }

            $roomGuests[] = $orderer;
            $roomsQuery->andWhere('r.id != :roomId')->setParameter('roomId', $ordererRoom->getId());
        } else {
            $number++;
        }

        $hotelRooms = $roomsQuery->getQuery()->getResult();
        foreach ($hotelRooms as $offer) {
            $guest    = json_decode($offer->getGuests(), true);
            $roomInfo = json_decode($offer->getRoomInfo(), true);

            // roomGuests shall always only include actual room guest, if orderer is not a guest, it should not be added in here
            $roomGuest = array(
                'firstName' => $guest['firstName'],
                'lastName' => $guest['lastName'],
                'repType' => ($details['groupSell']) ? 'BOP' : (($ordererIsGuest) ? 'BHO' : 'BHN'), // booking holder occupant / non-occupant
                'guestType' => ($details['groupSell']) ? 'ROP' : 'RMO', // room main occupant
                'children' => array(),
                'bookingCode' => $roomInfo['bookingCode'],
                'paymentType' => $roomInfo['paymentType']
            );

            foreach ($guest['children'] as $child) {
                $roomGuest['children'][$number++] = $child;
            }

            $roomGuests[] = $roomGuest;
            $toNotify[]   = array('email' => $guest['email'], 'name' => $guest['firstName'], 'withCancellationLink' => $reserveeAllowedToApprove);
        }

        if (!empty($ordererRoom)) {
            array_unshift($hotelRooms, $ordererRoom);
        }

        $pnrParams = array(
            'session' => $session,
            'orderer' => $orderer,
            'ordererIsGuest' => $ordererIsGuest,
            'roomGuests' => $roomGuests,
            'ordererContact' => array(
                array(
                    'type' => '7', // Contact
                    'value' => $hotelReservation->getDialingCode().$hotelReservation->getMobile()
                ),
                array(
                    'type' => 'P02', // Email
                    'value' => $hotelReservation->getEmail()
                ),
                array(
                    'type' => '2', // Address
                    'value' => $this->container->get('CmsCountriesServices')->getNameByIso3Code($hotelReservation->getCountry())
                )
            ),
            'stateful' => true,
            'groupSell' => $details['groupSell'],
            'hotelId' => $hotelReservation->getHotelId(),
            'userId' => $hotelReservation->getUserId(),
            'remarks' => json_decode($details['remarks'], true)
        );
        $pnr       = $this->amadeus->createBookingRecord($pnrParams, !$details['gds']);
        $session   = $pnr->getSession();

        if (!$pnr->isSuccess()) {
            $error = $this->getErrorMessage($pnr->getError());

            if ($hotelReservation->getTransactionSourceId() == $this->validTransactionSource['corpo']) {
                // Update approval flow request
                $parameters = array(
                    'requestStatus' => $this->container->getParameter('CORPO_APPROVAL_EXPIRED'),
                    'reservationId' => $hotelReservation->getId(),
                    'moduleId' => $this->container->getParameter('MODULE_HOTELS')
                );

                $this->container->get('CorpoApprovalFlowServices')->updatePendingRequestServices($parameters);
            }
        } else {
            $sellParams = array(
                'session' => $session,
                'orderer' => $orderer,
                'ordererIsGuest' => $ordererIsGuest,
                'roomGuests' => $roomGuests,
                'holderNumber' => $holderNumber,
                'vendorCode' => $details['vendorCode'],
                'ccHolderName' => $details['ccHolderName'],
                'cardNumber' => $details['cardNumber'],
                'expiryDate' => $details['expiryDate'],
                'securityId' => $details['securityId'],
                'stateful' => true,
                'groupSell' => $details['groupSell'],
                'formOfPaymentCode' => (!empty($details['prepaidIndicator'])) ? '9' : '1',
                'hotelId' => $hotelReservation->getHotelId(),
                'chainCode' => $details['chainCode'],
                'hotelCityCode' => $details['hotelCityCode'],
                'hotelCode' => $details['hotelCode'],
                'userId' => $hotelReservation->getUserId(),
                'hotelName' => $hotelDetails->getName()
            );

            $operationTime = new \DateTime;

            $sell    = $this->amadeus->confirmReservation($sellParams, $details['gds']);
            $session = $sell->getSession();

            if (!$sell->isSuccess()) {
                $error = $this->getErrorMessage($sell->getError());
                $this->sendEmailOnError('Booking', $operationTime, $hotelReservation);
            } else {
                $sellResults    = $sell->getData();
                $controlNumber  = $sellResults['controlNumber'];
                $reservationKey = $sellResults['reservationKey'];

                // Save reservation controlNumber
                $hotelReservation->setControlNumber($controlNumber);
                $hotelReservation->setReservationStatus($this->container->getParameter('hotels')['reservation_confirmed']);
                $hotelReservation->setDetails(null);
                $this->em->persist($hotelReservation);
                $this->em->flush();

                // Save room reservation controlNumber
                foreach ($hotelRooms as $key => $hotelRoomReservation) {
                    $roomKey = ($details['groupSell']) ? $reservationKey[0] : ((isset($reservationKey[$key])) ? $reservationKey[$key] : '');
                    $this->roomRepo->saveRoomReservation(array('reservationKey' => $roomKey, 'roomStatus' => $this->container->getParameter('hotels')['reservation_confirmed'], 'roomInfo' => null), $hotelRoomReservation);
                }

                return array('toNotify' => $toNotify);
            }
        }

        return array(
            'error' => $error,
            'session' => $session
        );
    }

    /**
     * This method validates if the reservation user id is allowed to approve or not.
     *
     * @param  Integer $reserveeUserId      The reservation user id.
     * @param  Integer $transactionSourceId The reservation transaction source id.
     * @return boolean
     */
    private function isReserveeAllowedToApprove($reserveeUserId, $transactionSourceId)
    {
        $fromCorporate = ($transactionSourceId == $this->validTransactionSource['corpo']) ? 1 : 0;
        if ($fromCorporate) {
            $userArray = $this->container->get('UserServices')->getUserDetails(array('id' => $reserveeUserId));
            if (isset($userArray[0]) && isset($userArray[0]['cu_corpoAccountId'])) {
                $userCorpoAccountId = $userArray[0]['cu_corpoAccountId'];

                return $this->container->get('CorpoApprovalFlowServices')->userAllowedToApprove($reserveeUserId, $userCorpoAccountId);
            } else {
                return false;
            }
        }

        return true;
    }

    //*****************************************************************************************
    // Booking Information Functions
    /**
     * This method retrieves the booking details.
     *
     * @param  HotelServiceConfig $serviceConfig The action data.
     * @param  String             $reference     The authentication token.
     * @return Array              The booking details.
     */
    public function bookingDetails(HotelServiceConfig $serviceConfig, $reference)
    {
        $this->initializeService($serviceConfig);

        $this->logger->addHotelActivityLog('HOTELS', 'booking_details', $this->userId, array("reference" => $reference));

        $reservationInfo = $this->getReservationInformation($reference, $serviceConfig->getPage());

        if (isset($reservationInfo['error'])) {
            $toreturn = array('error' => $reservationInfo['error']);
        } else {
            $toreturn = $reservationInfo;
        }

        return $toreturn;
    }

    /**
     * This method retrieves the reservation information.
     *
     * @param  String $reference      The reference.
     * @param  String $requestingPage The action data.
     * @param  Array  $hotelDetails
     * @return Array  The reservation information
     */
    private function getReservationInformation($reference, $requestingPage, $hotelDetails = array())
    {
        $return = array();

        if (empty($reference)) {
            $return['error'] = $this->translator->trans('Missing authentication token.');
        } else {
            //Fetch the reservation record to get controlNumber
            $reservation = $this->reservationRepo->getHotelReservation($reference);

            if (empty($reservation)) {
                $return['error'] = $this->translator->trans('Hotel reservation not found.');
            } else {
                $roomReservations = $this->roomRepo->findByReservationId($reservation->getId());
                foreach ($roomReservations as $roomNumber => $roomReservation) {
                    $savedRooms[$roomNumber] = array(
                        'reservationKey' => $roomReservation->getReservationKey(),
                        'roomStay' => $roomReservation->getRoomOfferDetail()
                    );
                }

                $response = $this->amadeus->getBookingRecord($reservation->getControlNumber(), $savedRooms, $this->getOtaPaymentTypes());

                if (!empty($response->getError())) {
                    $return['error'] = $this->getErrorMessage($response->getError());
                } elseif (!empty($response->getData())) {
                    $hotelRooms = $response->getData()['hotelRooms'];

                    // hotelDetails
                    if (empty($hotelDetails)) {
                        $hotelCode    = (!empty($reservation->getSource())) ? $reservation->getSource()->getHotelCode() : '';
                        $hotelDetails = $this->getHotelInformation($requestingPage, $reservation->getHotelId(), 0, $hotelCode);
                    }

                    $roomsToDisplay = array();
                    foreach ($roomReservations as $roomNumber => $roomObj) {
                        $roomDetails = $this->getReservedRoomDetails($requestingPage, $hotelRooms[$roomNumber], $roomObj, $reservation, $hotelDetails);

                        // reservationStatus
                        $reservationStatus = ucfirst($roomDetails['reservationStatus']);
                        if ($roomDetails['reservationStatus'] == 'canceled' && !empty($roomDetails['cancellation'])) {
                            $reservationStatus .= ": {$roomDetails['cancellation']}";
                        } elseif (isset($roomDetails['roomStatus']) && $roomDetails['roomStatus'] == 'Modified') {
                            $reservationStatus .= " - Room Modified";
                        }
                        $roomDetails['reservationStatus'] = $reservationStatus;

                        $roomDetails['hotelRoomId']                  = $roomObj->getReservationKey().'_'.$roomObj->getId();
                        $roomsToDisplay[$roomDetails['hotelRoomId']] = $roomDetails;
                    }

                    $return['roomsToDisplay']     = $roomsToDisplay;
                    $return['reservationDetails'] = $this->getReservationDetails($requestingPage, $reservation, $roomsToDisplay);
                    $return['hotelDetails']       = $hotelDetails;

                    // orderer
                    $return['ordererDetails'] = array(
                        'title' => $reservation->getTitle(),
                        'firstName' => $reservation->getFirstName(),
                        'lastName' => $reservation->getLastName(),
                        'iso3Country' => $reservation->getCountry(),
                        'phone' => $reservation->getDialingCode().$reservation->getMobile(),
                        'email' => $reservation->getEmail()
                    );
                }
            }
        }

        return $return;
    }

    /**
     * This method retrieves the reservation request details.
     *
     * @param  HotelServiceConfig $serviceConfig
     * @param  Integer            $id            The hotel reservation id.
     * @return Array              The reservation details.
     */
    public function getReservationRequestDetails(HotelServiceConfig $serviceConfig, $id)
    {
        $this->initializeService($serviceConfig);

        $requestingPage = 'pending_approval';
        $reservation    = $this->reservationRepo->find($id);

        $return                 = array();
        $return['hotelDetails'] = $this->getHotelInformation($requestingPage, $reservation->getHotelId(), 0, $reservation->getSource()->getHotelCode());

        $nightsCount = $this->utils->computeNights($reservation->getFromDate(), $reservation->getToDate());

        $roomsToDisplay   = array();
        $roomReservations = $this->roomRepo->findByReservationId($reservation->getId());
        $counter          = 1;
        foreach ($roomReservations as $roomObj) {
            $hotelRoomOffer = $this->amadeus->getRoomBooking($requestingPage, $counter, $roomObj->getRoomOfferDetail(), $this->getOtaPaymentTypes());

            $roomDetails                = array();
            $roomDetails['nightsCount'] = $nightsCount;
            $this->getRoomOfferDetails($hotelRoomOffer, $requestingPage, array(), '', array(), $roomDetails);

            // Guest Name
            $travellerInfo            = json_decode($roomObj->getGuests(), true);
            $roomDetails['guestName'] = sprintf("%s %s", $travellerInfo['firstName'], $travellerInfo['lastName']);

            $roomsToDisplay[] = $roomDetails;

            $counter++;
        }

        $return['rooms']              = $roomsToDisplay;
        $return['reservationDetails'] = $this->getReservationDetails($requestingPage, $reservation, $roomsToDisplay);

        return $return;
    }

    /**
     * This method prepares data for mobile.
     *
     * @param  Array $dataSource The reservation details and other hotel information.
     * @return Array The data formatted for use for mobile.
     */
    public function getRestBookingDetailsData($dataSource)
    {
        if (isset($dataSource['error'])) {
            return array('code' => 400, 'message' => $dataSource['error']);
        } else {
            $hotelDetails = array(
                'hotelCode' => $dataSource['hotelDetails']->getHotelCode(),
                'hotelId' => $dataSource['hotelDetails']->getHotelId(),
                'name' => $dataSource['hotelDetails']->getName(),
                'hotelNameURL' => $dataSource['hotelDetails']->getHotelNameURL(),
                'category' => $dataSource['hotelDetails']->getCategory(),
                'address' => $dataSource['hotelDetails']->getAddress(),
                'gpsLatitude' => $dataSource['hotelDetails']->getGpsLatitude(),
                'gpsLongitude' => $dataSource['hotelDetails']->getGpsLongitude(),
                'checkInEarliest' => $dataSource['hotelDetails']->getCheckInEarliest(),
                'checkOutLatest' => $dataSource['hotelDetails']->getCheckOutLatest(),
                'email' => $dataSource['hotelDetails']->getEmail(),
                'phone' => $dataSource['hotelDetails']->getPhone(),
                'fax' => $dataSource['hotelDetails']->getFax(),
                'acceptedCreditCards' => $dataSource['hotelDetails']->getAcceptedCreditCards(),
                'creditCardDetails' => $this->utils->getCCDetails($dataSource['hotelDetails']->getAcceptedCreditCards()),
                'creditCardSecurityCodeRequired' => $dataSource['hotelDetails']->getCreditCardSecurityCodeRequired(),
                'latitude' => $dataSource['hotelDetails']->getLatitude(),
                'longitude' => $dataSource['hotelDetails']->getLongitude(),
            );

            return array(
                'reservationDetails' => $dataSource['reservationDetails'],
                'hotelDetails' => $hotelDetails,
                'rooms' => array_values($dataSource['roomsToDisplay']),
            );
        }
    }

    //*****************************************************************************************
    // Booking Information Helper Functions
    /**
     * This method gives the cancellation information message.
     *
     * @param  Boolean $isCanceled
     * @param  Boolean $isCancelable
     * @return String  The cancellation information message.
     */
    public function getReservationCancellationInfo($isCanceled, $isCancelable)
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
     *  This method prepares the reservation details from the passed parameters.
     *
     * @param  String           $requestingPage
     * @param  HotelReservation $hotelReservation The HotelReservation object.
     * @param  Array            $offersSelected   The offers selected.
     * @return Array            The reservation details.
     */
    private function getReservationDetails($requestingPage, $hotelReservation, $offersSelected)
    {
        $checkInOutDateFormat = (in_array($requestingPage, array('book'))) ? 'long2' : 'long';

        $details                       = array();
        $details['canceled']           = true;
        $details['cancelable']         = true;
        $details['ccRequired']         = false;
        $details['prePaymentRequired'] = false;
        $details['reservationMode']    = 'standard';
        $details['checkIn']            = $this->utils->formatDate($hotelReservation->getFromDate(), $checkInOutDateFormat);
        $details['checkOut']           = $this->utils->formatDate($hotelReservation->getToDate(), $checkInOutDateFormat);

        $today                       = date_create('now');
        $totalPrepayment             = 0;
        $totalCancellationCost       = 0;
        $knownCancellationCost       = true;
        $earlistCancellationDeadline = '';

        $cancelledCustomerRoomTotalPrice = 0;
        $cancelledHotelRoomTotalPrice    = 0;

        $customerTotalPrice = 0;
        $customerCurrency   = '';
        $hotelTotalPrice    = 0;
        $hotelCurrency      = '';
        $singleCount        = 0;
        $doubleCount        = 0;
        $activeRoomsCount   = 0;

        foreach ($offersSelected as $offers) {
            switch ($requestingPage) {
                case 'book':
                    if ($offers['type'] == 'Single Rooms') {
                        $singleCount++;
                    } else {
                        $doubleCount++;
                    }
                    break;

                case 'reservation_confirmation':
                case 'cancellation':
                case 'booking_details':
                    $details['canceled'] = $details['canceled'] && $offers['canceled'];
                    if (isset($offers['cancellationFee'])) {
                        $totalCancellationCost += $offers['cancellationFee'];
                    }

                    if (isset($offers['deposit']['amount']) && !empty($offers['deposit']['amount'])) {
                        $totalPrepayment += $offers['deposit']['amount'];
                    }

                    if ($offers['cancelable'] && !empty($offers['cancellationDeadline'])) {
                        $roomCancellationDeadline = date_create($offers['cancellationDeadline']);
                        if ((empty($earlistCancellationDeadline) || $roomCancellationDeadline < $earlistCancellationDeadline) && $roomCancellationDeadline >= $today) {
                            $earlistCancellationDeadline = $roomCancellationDeadline;
                        }
                    }

                    if (isset($offers['cancelRate']['amount'])) {
                        $totalCancellationCost += $offers['cancelRate']['amount'];
                    } else {
                        $knownCancellationCost = false;
                    }

                    if ($offers['roomStatus'] != 'Canceled') {
                        $activeRoomsCount++;
                    } else {
                        $cancelledCustomerRoomTotalPrice += $offers['roomRate']['amount'];
                        $cancelledHotelRoomTotalPrice    += $offers['hotelRoomRate']['amount'];
                    }
                    break;
            }

            $customerTotalPrice += $offers['roomRate']['amount'];
            $hotelTotalPrice    += $offers['hotelRoomRate']['amount'];

            if (empty($customerCurrency)) {
                $customerCurrency = $offers['roomRate']['currencyCode'];
                $hotelCurrency    = $offers['hotelRoomRate']['currencyCode'];
            }

            if (!$details['prePaymentRequired'] && ($offers['paymentType'] == 'deposit')) {
                $details['prePaymentRequired'] = true;
                $details['ccRequired']         = true;
                $details['reservationMode']    = 'guaranteed'; // if deposit is required then we consider it as guaranteed
            } elseif ($offers['paymentType'] == 'guaranteed') {
                $details['ccRequired']      = true;
                $details['reservationMode'] = 'guaranteed';
            }
        }

        switch ($requestingPage) {
            case 'book':
                $details['singleCount'] = $singleCount;
                $details['doubleCount'] = $doubleCount;
                break;

            case 'reservation_confirmation':
            case 'cancellation':
                if ($knownCancellationCost) {
                    $details['totalCancellationFee'] = array('amount' => $totalCancellationCost, 'currencyCode' => $hotelCurrency);
                }
            case 'booking_details':
                $details['cancelable']       = !$details['canceled']; //Seems all reservations can be canceled on Amadeus. Of course consider the payments/refund for reservations that we have collected the payment
                $details['activeRoomsCount'] = $activeRoomsCount;
                $details['controlNumber']    = $hotelReservation->getControlNumber();
                $details['reservationId']    = $hotelReservation->getId();

                if ($details['prePaymentRequired']) {
                    $details['totalPrepayment'][] = $this->translator->trans('You may need to make an advance payment.');
                    if ($totalPrepayment > 0) {
                        $details['totalPrepayment'][] = $this->translator->trans('Total prepayment of ').$this->getDisplayPrice(array('amount' => $totalPrepayment, 'currencyCode' => $hotelCurrency), false).$this->translator->trans(' to the hotel with immediate charge to your credit card.');
                    }
                } else {
                    $details['totalPrepayment'][] = $this->translator->trans('No deposit will be charged. ');
                }

                if (!empty($earlistCancellationDeadline)) {
                    $details['freeCancellationWithIn'] = $this->utils->calculateDaysToDate($earlistCancellationDeadline);
                }
                if ($knownCancellationCost) {
                    if ($totalCancellationCost > 0) {
                        $details['totalCancellationCost'][] = $this->translator->trans('Cancellation not free-of-charge.');
                    }
                    $details['totalCancellationCost'][] = $this->translator->trans('Total cancellation fee of ').$this->getDisplayPrice(array('amount' => $totalCancellationCost, 'currencyCode' => $hotelCurrency), false);
                }
                $details['cancellation'] = $this->getReservationCancellationInfo($details['canceled'], $details['cancelable']);

                if (!$details['canceled'] && $cancelledCustomerRoomTotalPrice > 0) {
                    $customerTotalPrice = $customerTotalPrice - $cancelledCustomerRoomTotalPrice;
                    $hotelTotalPrice    = $hotelTotalPrice - $cancelledHotelRoomTotalPrice;
                }
            case 'pending_approval':
                $details['nbrNights']       = $this->utils->computeNights($hotelReservation->getFromDate(), $hotelReservation->getToDate());
                $details['nbrRooms']        = (!$details['canceled']) ? $activeRoomsCount : count($offersSelected);
                $details['reservationDate'] = $this->utils->formatDate($hotelReservation->getCreationDate(), 'long');
                $details['reference']       = $hotelReservation->getReference();
                break;
        }

        $details['grandTotalPriceInclusiveCustomerAmount']   = $customerTotalPrice;
        $details['grandTotalPriceInclusiveCustomerCurrency'] = $customerCurrency;

        $details['grandTotalPriceInclusiveCustomerDisplay'] = $this->getDisplayPrice(array('currencyCode' => $customerCurrency, 'amount' => $customerTotalPrice), true, false, true, false);
//        if ($this->isRest) {
//            $details['grandTotalPriceInclusiveCustomerDisplay'] = $this->getDisplayPrice(array('currencyCode' => $customerCurrency, 'amount' => $customerTotalPrice), true, false, true, false);
//        } else {
//            $details['grandTotalPriceInclusiveCustomerDisplay'] = $this->getDisplayPrice(array('currencyCode' => $customerCurrency, 'amount' => $customerTotalPrice));
//        }

        $details['grandTotalPriceInclusiveHotelAmount']   = $hotelTotalPrice;
        $details['grandTotalPriceInclusiveHotelCurrency'] = $hotelCurrency;
        $details['grandTotalPriceInclusiveHotelDisplay']  = $this->getDisplayPrice(array('currencyCode' => $hotelCurrency, 'amount' => $hotelTotalPrice), true, false, false, false);

        return $details;
    }

    /**
     * This method gives the reservation mode info message.
     *
     * @param  String $reservationMode The mode retrieved from the API response.
     * @return String The reservation mode info message.
     */
    public function getReservationModeInfo($reservationMode)
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
     * This method retrieves the reserved room details.
     *
     * @param  String               $requestingPage
     * @param  HotelRoom            $apiHotelRoom
     * @param  HotelRoomReservation $roomObj        The HotelRoomReservation from database.
     * @param  HotelReservation     $reservation    The HotelReservation object.
     * @param  Array                $hotelDetails   The hotel details
     * @return Array                The room details.
     */
    private function getReservedRoomDetails($requestingPage, HotelRoom $apiHotelRoom, $roomObj, $reservation, $hotelDetails)
    {
        $reservationKey = $roomObj->getReservationKey();

        $roomDetails = array();

        $from = ($apiHotelRoom->getFrom()) ? $apiHotelRoom->getFrom() : $reservation->getFromDate();
        $to   = ($apiHotelRoom->getTo()) ? $apiHotelRoom->getTo() : $reservation->getToDate();

        if (!empty($apiHotelRoom->getMaxRoomCount())) {
            $roomDetails['maxPersons'] = $apiHotelRoom->getMaxRoomCount();
        }

        // Room reservation number
        $roomDetails['reservationKey'] = $reservationKey;

        // Check-in / Check-out
        $roomDetails['from']     = $this->utils->formatDate($from);
        $roomDetails['to']       = $this->utils->formatDate($to);
        $roomDetails['checkIn']  = $this->utils->formatDate($from, 'long').' (from '.$hotelDetails->getCheckInEarliest().')';
        $roomDetails['checkOut'] = $this->utils->formatDate($to, 'long').' (until '.$hotelDetails->getCheckOutLatest().')';

        // Number of Nights
        $roomDetails['nightsCount'] = $this->utils->computeNights($from, $to);

        // Get more room info and add info to $roomDetails
        $this->getRoomOfferDetails($apiHotelRoom, $requestingPage, array(), '', array(), $roomDetails);

        // Guest Name
        $travellerInfo            = json_decode($roomObj->getGuests(), true);
        $roomDetails['guestName'] = sprintf("%s %s", $travellerInfo['firstName'], $travellerInfo['lastName']);
        foreach ($travellerInfo['children'] as $child) {
            $roomDetails['guestName'] .= ', '.sprintf("%s %s", $child['childFirstName'], $child['childLastName']);
        }

        // Reservation Status
        $roomDetails['reservationStatus'] = $apiHotelRoom->getStatus();
        $roomDetails['roomStatus']        = $roomObj->getRoomStatus();
        $roomDetails['canceled']          = ($roomDetails['reservationStatus'] == 'canceled') ? true : false;
        $roomDetails['cancelable']        = ($roomDetails['reservationStatus'] == 'completed' || $roomDetails['reservationStatus'] == 'canceled') ? false : $roomDetails['cancelable'];

        if ($roomDetails['canceled']) {
            $roomInfo                          = json_decode($roomObj->getRoomInfo(), true);
            $roomDetails['cancellationNumber'] = (isset($roomInfo['cancellationNumber'])) ? $roomInfo['cancellationNumber'] : '';
            $roomDetails['cancellation']       = (isset($roomInfo['cancellation'])) ? $this->utils->formatDate($roomInfo['cancellation'], 'long') : '';
        }

        // Rates
        $roomDetails['roomRate']      = array('currencyCode' => $reservation->getCustomerCurrency(), 'amount' => $roomObj->getCustomerRoomPrice());
        $roomDetails['hotelRoomRate'] = array('currencyCode' => $reservation->getHotelCurrency(), 'amount' => $roomObj->getHotelRoomPrice());

        // Headline / GuestInfo
        $roomDetails['roomHeadline'] = '';
        if ($roomDetails['name'] && $roomDetails['description']) {
            $roomDetails['roomHeadline'] = ucwords($roomDetails['name'].' - '.$roomDetails['description']);
        }
        $roomDetails['roomTypeInfo'] = array('guestInfo' => '');

        if ($requestingPage == 'reservation_confirmation') {
            $roomDetails['typeOfReservation'] = $this->getReservationModeInfo($roomDetails['paymentType']);
        }

        // Cancellation References
        $cancellationReference = $apiHotelRoom->getCancellationReference();

        $roomDetails['segmentIdentifier'] = $cancellationReference['segmentIdentifier'];
        $roomDetails['segmentNumber']     = $cancellationReference['segmentNumber'];

        return $roomDetails;
    }

    //*****************************************************************************************
    // Post-Booking Functions
    /**
     * This method prepares the modification page
     *
     * @param  HotelModificationForm $requestData The request data.
     * @return Array                 The modification details
     */
    public function hotelModification(HotelModificationForm $requestData)
    {
        $toreturn                   = array();
        $toreturn['fromDate']       = $requestData->getFromDate();
        $toreturn['toDate']         = $requestData->getToDate();
        $toreturn['hotelDetails']   = $requestData->getHotelDetails();
        $toreturn['offersSelected'] = $requestData->getReservationOffers();
        $toreturn['guestFirstName'] = array();
        $toreturn['guestLastName']  = array();
        $toreturn['guestEmail']     = array();

        foreach ($toreturn['offersSelected'] as &$offer) {
            $offer['bookableInfo']                        = array('Children' => array()); // will see where to get this data
            $offer['guestInfo']                           = ''; // not sure where to get this one
            $offer['conditions']['mainInfo']['breakfast'] = $offer['breakfastDetails'];

            $offer['conditions']['mainInfo']['cancellation'] = '';
            $offer['conditions']['moreInfo']['cancellation'] = $offer['cancellationDetails'];
            if (count($offer['cancellationDetails']) > 0) {
                $offer['conditions']['mainInfo']['cancellation'] = array_shift($offer['conditions']['moreInfo']['cancellation']);
            }

            $offer['conditions']['moreInfo']['deposit'] = $offer['prepaymentDetails'];

            $guests = $this->roomRepo->findByReservationKey($offer['reservationKey'])->getGuests();

            $offer['guests']              = json_decode($guests, true);
            $toreturn['guestFirstName'][] = $offer['guests']['firstName'];
            $toreturn['guestLastName'][]  = $offer['guests']['lastName'];
            $toreturn['guestEmail'][]     = $offer['guests']['email'];
        }

        $toreturn['reservationDetails'] = $this->getReservationDetails('modification_form', $this->reservationRepo->getHotelReservation($requestData->getReference()), $toreturn['offersSelected']);

        $orderer = $requestData->getOrdererDetails();

        // remove country code on phone
        if (isset($orderer['phone']) && !empty($orderer['phone'])) {
            $orderer['phone'] = trim(substr($orderer['phone'], strpos($orderer['phone'], ' ')));
        }

        $toreturn['nightsCount'] = $this->utils->computeNights($toreturn['fromDate'], $toreturn['toDate']);
        $toreturn['orderer']     = $orderer;

        $toreturn['ccValidityInfo'] = $this->utils->getCCValidityOptions();
        $creditCard                 = $requestData->getHotelDetails()['creditCardDetails'];

        if (isset($creditCard['expiryDate']) && !empty($creditCard['expiryDate'])) {
            $expiryDate = \DateTime::createFromFormat('my', $creditCard['expiryDate']);
            list($creditCard['expiryDateMonth'], $creditCard['expiryDateYear']) = explode('_', $expiryDate->format('m_Y'));
        }
        $toreturn['creditCard'] = $creditCard;

        if ($toreturn['reservationDetails']['ccRequired'] && empty($toreturn['hotelDetails']['creditCardDetails'])) {
            // If payment type is guaranteed or deposit but hotel has no creditcard details, we'll give all possible choices, we assume that any type of credit card is acceptable
            $toreturn['hotelDetails']['creditCardDetails'] = $this->utils->getCCDetails(array());
        }

        $toreturn['countryList']           = $this->container->get('CmsCountriesServices')->getCountryList();
        $toreturn['mobileCountryCodeList'] = $this->container->get('CmsCountriesServices')->getMobileCountryCodeList();

        return $toreturn;
    }

    /**
     * This method cancels a reservation and returns a json response with minimal information.
     *
     * @param $hotelReservationId   The reservation Id.
     * @return Json A custom json data.
     */
    public function hotelCancellationJson($hotelReservationId)
    {
        $toreturn = array();

        $hotelReservation = $this->reservationRepo->find($hotelReservationId);

        $request = new HotelCancellationForm();
        $request->setReference($hotelReservation->getReference());

        $resultsArray = $this->cancelReservation($request);

        $toreturn['success'] = $resultsArray['cancelled'];

        if ($toreturn['success']) {
            $toreturn['message'] = $this->translator->trans('The reservation is successfully cancelled.');
        } elseif (!empty($resultsArray['failed'])) {
            $failedRooms = array();
            foreach ($resultsArray['failed'] as $room) {
                $failedRooms[] = $room['name'];
            }
            $action_array        = array();
            $action_array[]      = implode(', ', $failedRooms);
            $ms                  = vsprintf($this->translator->trans("The reservation was cancelled partially. The rooms: %s were not cancelled."), $action_array);
            $toreturn['message'] = $ms;
        } else {
            $toreturn['message'] = $this->translator->trans('The reservation cancellation failed.');
        }

        $totalCancellationCost = 0;
        $currencyCode          = '';
        if (isset($resultsArray['cancellationInfo']['roomsCancelled'])) {
            foreach ($resultsArray['cancellationInfo']['roomsCancelled'] as $room) {
                if (isset($room['cancelRate']['amount'])) {
                    $totalCancellationCost += $room['cancelRate']['amount'];
                    if (empty($currencyCode) && isset($room['cancelRate']['currencyCode'])) {
                        $currencyCode = $room['cancelRate']['currencyCode'];
                    }
                }
            }
        }
        $toreturn['data']['cancellationFee'] = array(
            'amount' => $totalCancellationCost,
            'currencyCode' => !empty($currencyCode) ? $currencyCode : $hotelReservation->getHotelCurrency()
        );

        return json_encode($toreturn);
    }

    /**
     * This method cancels a reservation.
     *
     * @param  HotelCancellationFrom $requestData   The request data.
     * @param  String                $emailTemplate The cancellation email template (Optional; default = '@Hotel/hotel-cancellation-email.twig')
     * @return Array                 The cancellation details, status, etc.
     */
    public function cancelReservation(HotelCancellationForm $requestData, $emailTemplate = '@Hotel/hotel-cancellation-email.twig')
    {
        $toreturn              = array();
        $toreturn['cancelled'] = false;
        $toreturn['failed']    = array();

        // retrieve reservation information
        $reservationInfo = $this->getReservationInformation($requestData->getReference(), 'cancellation');
        extract($reservationInfo); // This extracts the array information and initializes it to variables: $roomsToDisplay; $reservationDetails; $hotelDetails; and $ordererDetails.

        $reservation = $this->reservationRepo->getHotelReservation($requestData->getReference());

        $withOneSuccessfulCancellation = 0;

        if (isset($reservationDetails) && (isset($reservationDetails['controlNumber']) && !empty($reservationDetails['controlNumber']))) {
            $requestData->setControlNumber($reservationDetails['controlNumber']);

            $toreturn['cancelled']        = true;
            $toreturn['cancellationInfo'] = array(
                'controlNumber' => $reservation->getControlNumber(),
                'hotelName' => $hotelDetails->getName(),
                'hotelAddress' => $hotelDetails->getAddress(),
                'hotelPhone' => $hotelDetails->getPhone(),
            );

            // rooms are not provided in the request; we assume we cancel whole itinerary
            if (!count($requestData->getRooms())) {
                /**
                 * Whole Reservation Cancellation
                 * as agreed we will be utilizing cancellation using segment reference;
                 * which means we will have to loop to all segment and do necessary
                 * cancellation processes
                 */
                $this->logger->addHotelActivityLog('HOTELS', 'cancellation', $requestData->getUserId(), array("hotelName" => $hotelDetails->getName(), "reference" => $requestData->getReference(), "criteria" => $reservationDetails));
                $status = 'Canceled';

                $uniqueReservationKeys = array();
                foreach ($roomsToDisplay as $item) {
                    if (!in_array($item['reservationKey'], $uniqueReservationKeys)) {
                        $room = new HotelRoomCancellationForm();
                        $room->setReservationKey($item['reservationKey']);
                        $room->setSegmentIdentifier($item['segmentIdentifier']);
                        $room->setSegmentNumber($item['segmentNumber']);

                        $requestData->addRoom($room);
                        unset($room);

                        $uniqueReservationKeys[] = $item['reservationKey'];
                    }
                }
                unset($uniqueReservationKeys);
            } else {
                /**
                 * Room Cancellation
                 */
                $this->logger->addHotelActivityLog('HOTELS', 'room_cancellation', $requestData->getUserId(), array("hotelName" => $hotelDetails->getName(), "reference" => $requestData->getReference(),
                    "criteria" => $reservationDetails));
                $status = 'Modified';
            }

            // cancel rooms
            $cancellationResults = array();
            $operationTime       = new \DateTime;

            $params = array(
                'hotelId' => $hotelDetails->getHotelId(),
                'hotelName' => $hotelDetails->getName(),
                'stateful' => false,
                'controlNumber' => $requestData->getControlNumber(),
            );

            foreach ($requestData->getRooms() as $room) {
                $params['reservationKey']    = $room->getReservationKey();
                $params['segmentIdentifier'] = $room->getSegmentIdentifier();
                $params['segmentNumber']     = $room->getSegmentNumber();

                $cancellationResults[$room->getReservationKey()] = $this->cancelRoom($requestData->getUserId(), $params);
            }

            // Only send email confirmation to those that are part of the itinerary before the room was canceled
            $toNotify = array();
            foreach ($this->roomRepo->getActiveRooms($reservationDetails['reservationId']) as $room) {
                $reservationKey = $room->getReservationKey();
                $hotelRoomId    = $reservationKey.'_'.$room->getId();
                if (isset($cancellationResults[$reservationKey])) {
                    $roomsToDisplay[$hotelRoomId] = array_merge($roomsToDisplay[$hotelRoomId], $cancellationResults[$reservationKey]->toArray());
                    if ($cancellationResults[$reservationKey]->hasError()) {
                        $toreturn['failed'][]  = $roomsToDisplay[$hotelRoomId];
                        $toreturn['cancelled'] = false;
                    } else {
                        $roomInfo = array(
                            'cancellation' => $roomsToDisplay[$hotelRoomId]['cancellation'],
                            'cancelRate' => $roomsToDisplay[$hotelRoomId]['cancelRate'],
                            'cancellationNumber' => $roomsToDisplay[$hotelRoomId]['cancellationNumber']
                        );

                        $room->setRoomStatus('Canceled');
                        $room->setRoomInfo(json_encode($roomInfo));
                        $this->em->persist($room);
                        $this->em->flush();

                        $withOneSuccessfulCancellation                    = 1;
                        $toreturn['cancellationInfo']['roomsCancelled'][] = $roomsToDisplay[$hotelRoomId];
                    }
                }

                // get guests to notify
                $guestArr = json_decode($room->getGuests(), true);
                $guests   = array_shift($guestArr);
                if (isset($guests['email']) && !empty($guests['email'])) {
                    $toNotify[] = array(
                        'firstName' => $guests['firstName'],
                        'lastName' => $guests['lastName'],
                        'email' => $guests['email'],
                    );
                }
            }

            /**
             * If cancellation is by whole itinerary, make sure to put status
             * to Modified in case not all rooms are canceled
             */
            if (count($toreturn['failed']) > 0) {
                if ($status === 'Canceled' && $withOneSuccessfulCancellation == 1) {
                    $status = 'Modified';

                    $toreturn['error'][] = $this->translator->trans('Whole itinerary cancellation failed -- not all reserved room are cancelled');
                }

                $this->sendEmailOnError('Cancelling', $operationTime, $this->reservationRepo->getHotelReservation($requestData->getReference()));
            }

            // make sure we have some reservation cancelled successfully
            if ($withOneSuccessfulCancellation == 1) {
                // Update the original reservation
                $hr = $this->reservationRepo->find($reservationDetails['reservationId']);
                $hr->setReservationStatus($status);
                $this->em->persist($hr);
                $this->em->flush();

                // Send also email to orderer's email for sending email confirmation.
                $toNotify[] = array('email' => $hr->getEmail(), 'firstName' => $hr->getFirstName(), 'lastName' => $hr->getLastName());

                //Send confirmation mails to unique emails
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
                    $toreturn['error'][] = $this->translator->trans('Reservation successfully cancelled. Unfortunately there was an error sending an email confirmation.');
                }
            }

            if (isset($toreturn['error']) && is_array($toreturn['error'])) {
                $toreturn['error'] = implode('; ', $toreturn['error']);
            }
        } else {
            $toreturn['error'] = $this->translator->trans("We cannot process your cancellation request due to missing information.");
        }

        if ($this->isRest) {
            $data = array('cancelled' => $toreturn['cancelled'], 'failed' => $toreturn['failed']);
            if (isset($toreturn['error'])) {
                $data['error'] = $toreturn['error'];
            }
            $toreturn = $data;
        }

        if ($withOneSuccessfulCancellation == 1) {
            // Cancellation fee is present, so we do a refund.
            if (isset($reservationDetails['totalCancellationFee'])) {
                $this->refundPayment($reservation, $reservationDetails['totalCancellationFee']);
            }
        }

        return $toreturn;
    }

    /**
     * This method modifies a reservation.
     *
     * @param  Array $requestData The request data.
     * @return Array The modification results, status, etc.
     */
    public function modifyReservation($requestData)
    {
        $results = array();

        $toreturn              = array();
        $toreturn['reference'] = $reference;

        $controlNumber = isset($requestData['controlNumber']) ? $requestData['controlNumber'] : 0;

        //Fetch the reservation record to get controlNumber
        $reservation = $this->reservationRepo->getHotelReservation($reference);

        if (empty($reservation)) {
            $toreturn['error'] = $this->translator->trans('Hotel reservation not found.');
        } else {
            $params = array(
                'controlNumber' => $controlNumber,
                'stateful' => true
            );

            $results['pnr'] = $pnr            = $this->amadeus->getBookingRecord($params);
            if (isset($pnr['error'])) {
                $toreturn['error'] = $this->getErrorMessage($pnr['error']);
            } elseif (empty($pnr['lastResponse'])) {
                $toreturn['error'] = $this->translator->trans("We cannot process your modification request due to missing information.");
            }
        }

        if (!isset($toreturn['error'])) {
            $params = array(
                'stateful' => true,
                'session' => $pnr['session']
            );

            $roomGuests                 = array();
            $roomGuests[0]['lastName']  = $requestData['lastName'];
            $roomGuests[0]['firstName'] = $requestData['firstName'];
            $roomGuests[0]['title']     = ($requestData['title'] == 0) ? 'Mr' : 'Ms/Mrs';

            $params = $this->amadeusNormalizer->getModificationParameters($pnr['lastResponse'], $params, $roomGuests);
            if (!empty($params)) {
                $results['pnrNameChange'] = $pnrNameChange            = $this->amadeus->pnrNameChange($params);

                if (isset($pnrNameChange['error'])) {
                    $toreturn['error'] = $this->getErrorMessage($pnrNameChange['error']);
                } else {
                    // contact information change
                    $params['session'] = $pnrNameChange['session'];

                    $params['elements'] = array(
                        array(
                            'type' => 5,
                            'value' => $this->container->get('CmsCountriesServices')->getNameByIso3Code($requestData['country'])
                        ),
                        array(
                            'type' => 'P02',
                            'value' => $requestData['email']
                        ),
                        array(
                            'type' => 7,
                            'value' => $requestData['mobile']
                        )
                    );

                    $params['optionCode']           = '0';
                    $params['controlNumber']        = $controlNumber;
                    $params['cancellationSegments'] = $this->amadeusNormalizer->getCancellationSegments($pnr['lastResponse'], $params['elements']);

                    if (count($params['cancellationSegments']) > 0) {
                        $results['pnrCancel'] = $pnrCancel            = $this->amadeus->cancelBooking($params, false);
                        if (!$pnrCancel->isSuccess()) {
                            $toreturn['error'] = $this->getErrorMessage($pnrCancel->getError());
                        } else {
                            $params['session'] = $pnrCancel->getSession();

                            if (isset($pnrCancel['lastResponse']) && !empty($pnrCancel['lastResponse'])) {
                                $params['ordererContact'] = $params['elements'];
                                $params['roomGuests']     = array();
                                $params['groupSell']      = '0';
                                $params['ordererIsGuest'] = false;

                                $results['pnrAdd'] = $pnrAdd            = $this->amadeus->createBookingRecord($params, false);
                                if (!$pnrAdd->isSuccess()) {
                                    $this->data['error'] = $this->getErrorMessage($pnrAdd->getError());
                                } else {
                                    $params['session'] = $pnrAdd->getSession();
                                }
                            }
                        }
                    }
                }

                // commit
                if (!isset($toreturn['error'])) {
                    $results['pnrCommit'] = $pnrCommit            = $this->amadeus->updateBookingRecord($params, false);
                    if (!$pnrCommit->isSuccess()) {
                        $toreturn['error'] = $this->getErrorMessage($pnrCommit->getError());
                    } else {
                        $params['session'] = $pnrCommit->getSession();
                    }
                }
            }

            // signout
            if (isset($params['session']) && $params['stateful']) {
                $results['signout'] = $signout            = $this->amadeus->securitySignOut($params);
            }
        }

        $toreturn['requestData'] = $requestData;
        $toreturn['results']     = $results;

        return $toreturn;
    }

    //*****************************************************************************************
    // Post-Booking Helper Functions
    /**
     * This method cancels a room in a reservation.
     *
     * @param  Integer $userId The user id.
     * @param  Array   $params The cancellation reference.
     * @return Arrray  The cancellation results, status, etc.
     */
    private function cancelRoom($userId, $params)
    {
        $toreturn = $this->amadeus->cancelRoom($params);
        if ($toreturn->hasError() && !empty($toreturn->getStatus()->getError())) {
            $error = $toreturn->getStatus()->getError();
            $toreturn->getStatus()->setError($this->getErrorMessage($error));
        }

        $this->logger->addBookingRequestLog('AMADEUS', 'cancellation', $userId, $params, $toreturn);

        return $toreturn;
    }

    /**
     * This method updates the reservation status and details
     * @param  Integer $reservationId The reservation Id.
     * @param  String  $status        The reservation status to set
     * @param  Array   $details       The details to set.
     * @return Boolean if successful or not.
     */
    public function updateReservationStatus($reservationId, $status, $details)
    {
        $reservation = $this->reservationRepo->findOneById($reservationId);
        if (empty($reservation)) {
            return false;
        } else {
            $reservation->setReservationStatus($status);
            $reservation->setDetails(json_encode($details));

            $this->em->persist($reservation);
            $this->em->flush();
        }

        return true;
    }

    //*****************************************************************************************
    // Hotel Information Functions
    /**
     * This method retrieves information of a specific hotel from database.
     *
     * @param  Integer $hotelId             The hotel id.
     * @param  Integer $requestId           The hotel search request id.
     * @param  String  $type                The type of information you want to retrieve.
     * @param  Integer $transactionSourceId
     * @return Array   Hotel data.
     */
    private function getHotelDBInformation($hotelId, $requestId = 0, $type = '', &$returnData = null)
    {
        $this->on_production_server = ($this->container->hasParameter('ENVIRONMENT') && $this->container->getParameter('ENVIRONMENT') == 'production');

        $hotelFromDB = $this->getHotelObject($hotelId);
        if (empty($hotelFromDB)) {
            $returnData['error'] = $this->errorRepo->getErrorMessage("HOTEL_5");
            $city                = new AmadeusHotelCity();
            $hotel               = new Hotel();
            $hotel->setCity($city);

            return $hotel;
        }

        $hotel = new Hotel();
        $hotel->setHotelId($hotelId);
        $hotel->setDescription($hotelFromDB->getDescription());
        $hotel->setPublished($hotelFromDB->getPublished());
        $hotel->setHotelSource($this->hsRepo->getHotelSourceField('source', ['hotelId', $hotelId]));

        // Main images
        $images = $this->getHotelMainImage($hotelId);
        $hotel->setMainImage($images[0]);
        $hotel->setMainImageBig($images[1]);

        //Check if the hotel has 360 or not so that we prioritize 360 over image
        $hotel->setHas360($this->has360($hotelId));

        if ($hotel->getHas360()) {
            $images = $this->getHotelImagesByType($hotelId);
        } else {
            $images = $this->getHotelImages($hotelId);
        }

        if ($type == 'reviews') {
            $hotelImages = array();
            foreach ($images as $images) {
                foreach ($images as $new_ar) {
                    $hotelImages[] = $new_ar;
                }
            }
            $images = $hotelImages;
        }
        $hotel->setImages($images);

        if ($type != 'basicOnly') {
            // Trust you
            $trustyou = $this->container->get('ReviewServices')->getMetaReview($this->hotelRepo->getTrustYouId($hotel->getHotelId()), $this->siteLanguage);
            if (!empty($trustyou)) {
                $hotel->setTrustyou($trustyou);
            }
        }

        if ($type == 'offers') {
            return $hotel;
        }

        $hotel->setName($hotelFromDB->getPropertyName());
        $hotel->setHotelNameURL($this->utils->cleanTitleData($hotelFromDB->getPropertyName()));
        $hotel->setNamealt($this->utils->cleanTitleDataAlt($hotelFromDB->getPropertyName()));
        $hotel->setCategory($hotelFromDB->getStars());
        $hotel->setStreet($hotelFromDB->getAddress1());
        $hotel->setDistrict($hotelFromDB->getDistrict());
        $hotel->setZipCode($hotelFromDB->getZipCode());
        $hotel->setCity($hotelFromDB->getCity());
        $hotel->setLatitude($hotelFromDB->getLatitude());
        $hotel->setLongitude($hotelFromDB->getLongitude());

        // Amenities
        $amenities = $this->getHotelDBAmenities($hotelId);
        if (!empty($amenities)) {
            $hotel->setAmenities($amenities);
        }

        // Facilities
        $facilities = $this->getHotelDBFacilities($hotelId);
        if (!empty($facilities)) {
            $hotel->setFacilities($facilities);
        }

        switch ($type) {
            case 'basicOnly':
                break;
            case 'hotel_details':
            case 'reviews':
            default:
                $this->em->clear();
                $hotelFromDB = $this->hotelRepo->findOneById($hotelId);

                $hotel->setMapImage($this->container->get('LocationImageServices')->returnMapLocationImage($this->container->getParameter('SOCIAL_ENTITY_HOTEL'), $hotelFromDB, 15, '278x204'));
                $hotel->setMapImageUrl($this->getMapImageUrl($hotelId, $requestId, $this->transactionSourceId));

                $hotel->setCancellationAndPrepayment($this->translator->trans("Cancellation and prepayment policies vary according to room type. Please enter the dates of your stay and check the conditions of your required room."));
                $hotel->setChildrenAndExtraBeds($this->translator->trans("No more than two children permitted per double room."));

                // Related Things-To-Do
                $cityId = $hotel->getCity()->getCityId();
                if ($cityId != 0) {
                    $Results          = $this->container->get('ThingsToDoServices')->getPoiTopList('', '', $cityId, 4, 'rand', $this->siteLanguage);
                    $nearbyAttraction = $Results['pois_array'];
                    $hotel->setNearbyAttraction($nearbyAttraction);
                }

                break;
        }

        return $hotel;
    }

    /**
     * This method returns the amenities of a certain hotel
     *
     * @return array of amenities
     */
    private function getHotelDBAmenities($hotelId)
    {
        $amenities      = array();
        $hotelAmenities = $this->em->getRepository('HotelBundle:CmsHotelFacility')->getHotelHighestFacilities($hotelId, $this->siteLanguage);
        foreach ($hotelAmenities as $am) {
            $icon = '';
            if (preg_match("/no.*smoking/i", $am['facilityName'])) {
                $icon = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/smokingsign.png');
            } elseif (preg_match("/wi.*fi/i", $am['facilityName'])) {
                $icon = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/wifisignal.png');
            }

            $amenities[] = array(
                'icon' => $icon,
                'name' => $am['facilityName']
            );
        }

        return $amenities;
    }

    /**
     * This method returns the facilities of a certain hotel
     *
     * @return array of facilities
     */
    private function getHotelDBFacilities($hotelId)
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

        $facilities = array_values($facilities);

        return $facilities;
    }

    /**
     * This method returns all the districts associated to a certain city/hotel (criteria)
     * @param  HotelSC $hotelSC The hotel search criteria.
     * @return Array   $districts      List of districts
     */
    public function getHotelDistricts(HotelSC $hotelSC)
    {
        $districts = array();

        $hotelDistricts = array();
        if (!empty($hotelSC->getCity()->getCode())) {
            $hotelDistricts = $this->hotelRepo->getDistricts($hotelSC->getCity()->getCode());
        } elseif ($hotelSC->getEntityType() == $this->container->getParameter('SOCIAL_ENTITY_HOTEL')) {
            $hotelCityCode  = $this->cityRepo->getCityCodeByHotelId($hotelSC->getHotelId());
            $hotelDistricts = $this->hotelRepo->getDistricts($hotelCityCode);
        } elseif ($hotelSC->getEntityType() == $this->container->getParameter('SOCIAL_ENTITY_CITY')) {
            $hotelDistricts = $this->hotelRepo->getDistrictsByCityId($hotelSC->getCity()->getId());
        }

        if (!empty($hotelDistricts)) {
            foreach ($hotelDistricts as $dist) {
                $districts[$dist->getDistrict()] = str_ireplace('Arrondissement', 'arr.', $dist->getDistrict());
            }
            natsort($districts);
        }

        return $districts;
    }

    /**
     * This method retrieves AmadeusHotel object from database.
     *
     * @param  Integer      $hotelId The hotel id.
     * @return AmadeusHotel Hotel object.
     */
    public function getHotelObject($hotelId)
    {
        // return $this->hotelRepo->findOneById($hotelId);
        return $this->hotelRepo->getHotelById($hotelId);
    }

    /**
     * This method retrieves hotel reviews
     *
     * @param  Integer $hotelId The hotel id.
     * @return Array   Review data.
     */
    public function getHotelReviews($hotelId)
    {
        return $this->container->get('ReviewServices')->getMetaReview($this->hotelRepo->getTrustYouId($hotelId, $this->siteLanguage, 'hotelReviews'));
    }

    /**
     * This method calls HotelDescriptiveInfo API to get detailed info of multiple or specific hotel
     *
     * @param  Array $requestingPage
     * @param  Mixed $hotelCodes     A hotel code or list of hotel codes.
     * @return Array Hotel information
     */
    private function getDataFromDescriptiveInfo($requestingPage, $hotelCodes)
    {
        $hotels = array();

        $returnContactInfo = in_array($requestingPage, array('reservation', 'reservation_confirmation', 'booking_details', 'cancellation'));
        $params            = array(
            'hotelCodes' => $hotelCodes,
            'minimalData' => ($requestingPage == 'avail') ? true : false,
            'stateful' => false,
            'userId' => $this->userId,
            'returnContactInfo' => $returnContactInfo
        );

        $response = $this->amadeus->getHotelDescriptiveInfo($params);

        if ($response->isSuccess()) {
            $hotelContents = $response->getData();
            foreach ($hotelContents as $hotel) {
                $details = array(
                    'distances' => array(),
                    'amenities' => array(),
                    'facilities' => array(),
                    'phone' => '',
                    'fax' => '',
                );

                // Distances
                $refPoints = $hotel->getDistanceRefPoints();
                foreach ($refPoints as $point) {
                    $dist = $point->getAttribute('Distance');
                    if ($dist != '') {
                        if (empty($details['distances'])) {
                            $details['distances']['unitOfMeasurement'] = 'km';
                        }
                        $uom = trim($this->otaRepo->getOTAValue('UOM', $point->getAttribute('UnitOfMeasureCode')));

                        $dist         = str_replace(',', '.', $dist);
                        $distance     = $this->utils->convertToKilometers($dist, $uom);
                        $distanceInfo = array(
                            'distance' => $distance,
                            'name' => trim($point->getAttribute('Name'))
                        );

                        $locationType = lcfirst(trim($this->otaRepo->getOTAValue('IPC', $point->getAttribute('IndexPointCode'))));

                        if (!isset($details['distances'][$locationType])) {
                            $details['distances'][$locationType][] = $distanceInfo;
                        } else {
                            $duplicate = false;
                            foreach ($details['distances'][$locationType] as $dist) {
                                if ($dist['name'] == $distanceInfo['name']) {
                                    $duplicate = true;
                                    break;
                                }
                            }
                            if (!$duplicate) {
                                $details['distances'][$locationType][] = $distanceInfo;
                            }
                        }
                    }
                }
                $hotel->setDistances($details['distances']);

                // Phone / Email
                if ($returnContactInfo) {
                    $contacts = $hotel->getContacts();

                    $this->getHotelPhones($contacts['phones'], $details);

                    $hotel->setPhone($details['phone']);
                    $hotel->setFax($details['fax']);
                }

                $hotels[$hotel->getHotelCode()] = $hotel;
            }
        }

        return $hotels;
    }

    /**
     * This method parse XML response from API to get specific hotel details only.
     *
     * @param  String $requestingPage
     * @param  String $hotelCode      The hotel code.
     * @param  String $xmlResponse    The API XML response (Optional)
     * @param  Hotel  $hotel          The hotel object (Optional)
     * @return Array  Hotel data.
     */
    private function getHotelAPIInformation($requestingPage, $hotelCode, $xmlResponse = null, $hotel = null)
    {
        if (empty($hotel)) {
            $hotel = new Hotel();
        }
        $details = array(
            'amenities' => array(),
            'facilities' => array(),
        );

        if ($hotel->getMainImage() != '/media/images/hotel-icon-image2.jpg') {
            $mainImage    = $hotel->getMainImage();
            $mainImageBig = $hotel->getMainImageBig();
        }

        $hotel->setHotelCode($hotelCode);

        if ($requestingPage != 'avail') {
            // if we have multiple $hotelCode; just get the first item since they just belong to same property but different provider (e.g. bedsonline, etc.)
            if (is_array($hotelCode)) {
                $hotelCode = $hotelCode[0];
            }

            $hotelDescriptiveInfo = $this->getDataFromDescriptiveInfo($requestingPage, array($hotelCode));
            if (isset($hotelDescriptiveInfo[$hotelCode])) {
                $descriptiveInfo = $hotelDescriptiveInfo[$hotelCode];
                $hotel->merge($descriptiveInfo);

                foreach ($hotel->getAmenityInfo() as $amenityInfo) {
                    $this->getHotelAmenitiesAndFacilities($amenityInfo['nodeList'], $amenityInfo['otaCategory'], $amenityInfo['nodeAttribute'], $amenityInfo['type'], $details);
                }

                if (!empty($descriptiveInfo->getAcceptedCreditCards())) {
                    // We will only consider credit card details from descriptive info if there is none given in rates section
                    $acceptedCreditCards = $descriptiveInfo->getAcceptedCreditCards();
                    $hotel->setAcceptedCreditCards(array());
                }
            }
        }

        if ($xmlResponse) {
            $hotelStayDetails = $this->amadeus->getHotelStayInformation($xmlResponse, $hotelCode);

            $hotel->merge($hotelStayDetails);

            // Amenities and Facilities
            foreach ($hotelStayDetails->getAmenityInfo() as $amenityInfo) {
                $this->getHotelAmenitiesAndFacilities($amenityInfo['nodeList'], $amenityInfo['otaCategory'], $amenityInfo['nodeAttribute'], $amenityInfo['type'], $details);
            }
        }

        $hotel->setAmenities($details['amenities']);
        $hotel->setFacilities($details['facilities']);

        if (count($hotel->getAmenities()) > 12) {
            $hotel->setAmenities(array_slice($hotel->getAmenities(), 0, 12));
        }

        if (empty($hotel->getAcceptedCreditCards()) && isset($acceptedCreditCards)) {
            $hotel->setAcceptedCreditCards($acceptedCreditCards);
        }

        if (!empty($hotel->getAcceptedCreditCards())) {
            $acceptedCreditCardCodes = array();
            $acceptedCreditCards     = $this->filterAcceptedCreditCards($this->utils->getCCDetails($hotel->getAcceptedCreditCards()), $acceptedCreditCardCodes);

            $hotel->setCreditCardDetails($acceptedCreditCards);
            $hotel->setAcceptedCreditCards($acceptedCreditCardCodes);
        }

        if (isset($mainImage) && ($hotel->getMainImage() == '/media/images/hotel-icon-image2.jpg')) {
            $hotel->setMainImage($mainImage);
            $hotel->setMainImageBig($mainImageBig);
        }

        return $hotel;
    }

    /**
     * This method filters credit cards to return only those with credit card image.
     * NOTE: If Utils::getCCDetails() is filled with all images; this method will no longer be necessary.
     *
     * @param  array $cc      The credit cards.
     * @param  array $ccCodes If supplied, it will be filled with the filtered credit card's codes
     * @return array The credit card that have credit card image.
     */
    private function filterAcceptedCreditCards(array $cc, array &$ccCodes = array())
    {
        $toReturn = array();
        foreach ($cc as $cards) {
            if (!empty($cards['image'])) {
                $toReturn[] = $cards;
                $ccCodes[]  = $cards['code'];
            }
        }

        return $toReturn;
    }

    /**
     * This method format. amenities and facilities array
     *
     * @param \DomeNodeList $amenities
     * @param String        $otaCategory  The OTA Category for the given amenity
     * @param String        $attribute    The attribute name
     * @param String        $facilityType The facility type.
     * @param Array         $details      This is filled-in or updated.
     */
    private function getHotelAmenitiesAndFacilities(\DOMNodeList $amenities, $otaCategory, $attribute, $facilityType, &$details)
    {
        // used by hotelOffersAction
        $names = array();
        foreach ($amenities as $amenity) {
            $code = $amenity->getAttribute($attribute);
            if ($code) {
                $icon = '';
                $name = $this->otaRepo->getOTAValue($otaCategory, $code);
                if (preg_match("/non-smoking/i", $name) || preg_match("/smoke-free/i", $name)) {
                    $icon = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/smokingsign.png');
                } elseif (preg_match("/wireless/i", $name) || preg_match("/internet/i", $name)) {
                    $icon = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/wifisignal.png');
                }
                $names[]                = $name;
                $details['amenities'][] = array('icon' => $icon, 'name' => $name);
            }
        }

        if (!empty($names)) {
            $details['facilities'][] = array('type' => $facilityType, 'names' => $names);
        }
    }

    /**
     * This method retrieves hotel information from DB and API
     *
     * @param  String $requestingPage
     * @param  String $hotelId
     * @param  String $hotelCode
     * @return Array  The hotel details
     */
    public function getHotelInformation($requestingPage, $hotelId, $requestId = 0, $hotelCode = '', $xmlResponse = null, $transactionSourceId = null, &$returnData = null)
    {
        if ($transactionSourceId) {
            $this->transactionSourceId = $transactionSourceId;
        }
        if (in_array($requestingPage, array('pending_approval', 'book', 'reservation', 'reservation_confirmation', 'booking_details', 'cancellation'))) {
            $type = 'basicOnly';
        } else {
            $type = $requestingPage;
        }

        $dbHotel = $this->getHotelDBInformation($hotelId, $requestId, $type, $returnData);
        if (empty($hotelCode)) {
            return $dbHotel;
        }

        $hotel = $this->getHotelAPIInformation($requestingPage, $hotelCode, $xmlResponse, $dbHotel);
        $hotel->setHotelSource($this->hsRepo->getHotelSourceField('source', ['hotelCode', $hotelCode]));

        if (empty($hotel->getHotelCode())) {
            $hotel->setHotelCode($hotelCode);
        }
        // we prioritize db description, amenities and facilities
        if (!empty($dbHotel->getDescription())) {
            $hotel->setDescription($dbHotel->getDescription());
        }
        if (!empty($dbHotel->getAmenities())) {
            $hotel->setAmenities($dbHotel->getAmenities());
        }
        if (!empty($dbHotel->getFacilities())) {
            $hotel->setFacilities($dbHotel->getFacilities());
        }

        return $hotel;
    }

    /**
     * This method retrieves contact number of a specific hotel
     *
     * @param \DomNodeList $contactNumbers
     * @param Array        $details        This gets filled up with the contact information.
     */
    private function getHotelPhones(\DOMNodeList $contactNumbers, &$details)
    {
        foreach ($contactNumbers as $contact) {
            $phoneTechType = $contact->getAttribute('PhoneTechType');
            $number        = $contact->getAttribute('PhoneNumber');
            $type          = $this->otaRepo->getOTAValue('PTT', $phoneTechType);

            if ($type && $number) {
                if ($type == 'Voice') {
                    $details['phone'] = $number;
                } elseif ($type == 'Fax') {
                    $details['fax'] = $number;
                }
                $details['numbers'][$type] = $number;
            }
        }
    }

    /**
     * This method calls the HotelsRepository to get hotel division categories.
     *
     * @param integer $hotelId
     * @param integer $categoryId
     * @param integer $divisionId
     * @param boolean $withSubDivisions
     * @param boolean $sortByGroup
     *
     * @return list
     */
    public function getHotelDivisions($hotelId, $categoryId = null, $divisionId = null, $withSubDivisions = false, $sortByGroup = false, $divisions_type = 'hotels')
    {
        $mediaType      = $this->container->getParameter('MEDIA_TYPE_360');
        $hotelDivisions = $this->hotelHrsRepo->getHotelDivisions($hotelId, $mediaType, $categoryId, $divisionId, $withSubDivisions, $sortByGroup);

        $divisions = array();

        $divisionsData = array();
        foreach ($hotelDivisions as $hotelDiv) {
            $divisionsData[] = array(
                "group_id" => $hotelDiv["group_id"],
                "group_name" => $hotelDiv["group_name"],
                "category_id" => $hotelDiv["category_id"],
                "category_name" => $hotelDiv["category_name"],
                "id" => $hotelDiv["id"],
                "name" => $hotelDiv["name"],
                "parent_id" => $hotelDiv["parent_id"],
                "parent_name" => $hotelDiv["parent_name"],
                "image" => $hotelDiv["image"],
                "media_type" => $hotelDiv["media_type"],
                "is_main_image" => $hotelDiv["is_main_image"],
                "settings" => json_decode($hotelDiv["media_settings"], true)
            );
        }

        // Convert it to a custom array
        if ($hotelDivisions) {
            $divisions = array(
                'type' => $divisions_type,
                'data' => array(
                    'id' => $hotelDivisions[0]['hotel_id'],
                    'name' => $hotelDivisions[0]['hotel_name'],
                    'logo' => $hotelDivisions[0]['hotel_logo'],
                    'country_code' => $hotelDivisions[0]['hotel_country_code'],
                    'divisions' => $divisionsData
                )
            );
        }

        return $divisions;
    }

    /**
     * This method calls the AMADEUS HotelsRepository to get hotel division categories.
     *
     * @param integer $hotelId
     * @param integer $categoryId
     * @param integer $divisionId
     * @param boolean $withSubDivisions
     * @param boolean $sortByGroup
     *
     * @return list
     */
    public function getHotelAmadeusDivisions($hotelId, $categoryId = null, $divisionId = null, $withSubDivisions = false, $sortByGroup = false)
    {
        $mediaType      = $this->container->getParameter('MEDIA_TYPE_360');
        $hotelDivisions = $this->hotelRepo->getHotelDivisions($hotelId, $mediaType, $categoryId, $divisionId, $withSubDivisions, $sortByGroup);

        // Prepare divisions data
        $divisionsData = array();
        foreach ($hotelDivisions as $hotelDiv) {
            $divisionsData[] = array(
                "group_id" => $hotelDiv->getCategory()->getGroup()->getId(),
                "group_name" => $hotelDiv->getCategory()->getGroup()->getName(),
                "category_id" => $hotelDiv->getCategory()->getId(),
                "category_name" => $hotelDiv->getCategory()->getName(),
                "id" => $hotelDiv->getId(),
                "name" => $hotelDiv->getName(),
                "parent_id" => $hotelDiv->getParent()->getId(),
                "parent_name" => $hotelDiv->getParent()->getName(),
                "image" => $hotelDiv->getImage()->getFilename(),
                "media_type" => $hotelDiv->getImage()->getMediaType(),
                "is_main_image" => $hotelDiv->getImage()->isDefaultPic(),
                "settings" => json_decode($hotelDiv->getImage()->getMediaSettings(), true)
            );
        }

        // Convert it to a custom array
        if ($hotelDivisions) {
            $divisions = array(
                'type' => 'hotels',
                'data' => array(
                    'id' => $hotelDivisions[0]->getHotel()->getId(),
                    'name' => $hotelDivisions[0]->getHotel()->getName(),
                    'logo' => $hotelDivisions[0]->getHotel()->getLogo(),
                    'country_code' => $hotelDivisions[0]->getHotel()->getCountryCode(),
                    'divisions' => $divisionsData
                )
            );

            return $divisions;
        }
    }

    /**
     * This method calls the HotelsRepository to fetch a related Things-To-Do per hotel city
     *
     * @param  Integer $hotelId
     * @return list
     */
    public function getHotel360ThingsToDo($hotelId)
    {
        return $this->hotelRepo->getHotel360ThingsToDo($hotelId);
    }

    //********************************************************************************************
    // Hotel Image functions
    /**
     * This method calls the HotelsRepository to check if a given hotel has 360 images or no
     *
     * @param  Integer $hotelId
     * @return boolean true if yes, false otherwise
     */
    public function has360($hotelId)
    {
        $mediaType = $this->container->getParameter('MEDIA_TYPE_360');

        return $this->imageRepo->has360($hotelId, $mediaType);
    }

    /**
     * This method retrieves hotel images.
     *
     * @param  Integer $hotelId
     * @param  Integer $limit
     * @param  Integer $user_id
     * @return Array   List of hotel images.
     */
    public function getHotelImages($hotelId)
    {
        $images = array();

        $hotelImages = $this->imageRepo->getHotelImages($hotelId);

        foreach ($hotelImages as $img) {
            $new_ar   = $this->createImgSource($img, 2);
            $category = strtolower(preg_replace("/([^a-zA-Z0-9]+|\s+)/", "_", trim($img->getLocation())));

            if (!is_array($new_ar)) {
                $new_ar = array($new_ar);
            }

            if (!isset($images[$category])) {
                $images[$category] = array();
            }

            $new_ar['user_id'] = $img->getUserId();
            $new_ar['id']      = $img->getId();

            $images[$category][] = $new_ar;
        }

        return $images;
    }

    /**
     * This method calls the HotelsRepository to retrieve the hotel images by mediaType
     *
     * @param  Integer $hotelId
     * @return Array   List
     */
    private function getHotelImagesByType($hotelId)
    {
        $images = array();

        // return HotelBundle\Model\HotelImage
        $hotelData = $this->imageRepo->getHotelImagesByType($hotelId, $this->container->getParameter('MEDIA_TYPE_360'));

        foreach ($hotelData as $data) {
            $hotel    = $data->getHotel();
            $division = $data->getDivision();
            $category = $division->getCategory();
            $parent   = $division->getParent();

            $imagePath = "hotels/".strtolower($hotel->getCountryCode())."/".$hotel->getId()."/".$category->getId()."/";

            if (!empty($parent->getId())) {
                $imagePath .= $parent->getId()."/";
            }

            $imagePath .= $division->getId()."/";

            $data->setLocation($imagePath);
            $generatedImages         = $this->createImgSource($data, 2, $this->container->getParameter('MEDIA_TYPE_360'));
            $generatedImages['info'] = $data->toArray();

            // Prepare the 360 preview image
            $media_360_base_path                    = $this->container->getParameter('MEDIA_360_BASE_PATH');
            $generatedImages['info']['preview_360'] = $this->container->get("TTMediaUtils")->createItemThumbs("360_Preview.jpg", $media_360_base_path.$imagePath, 0, 0, 1048, 588, 'hotels75HS1048588', $media_360_base_path.$imagePath, $media_360_base_path.$imagePath, 75);

            if (!isset($images[$category->getName()])) {
                $images[$category->getName()] = array();
            }

            $images[$category->getName()][] = $generatedImages;
        }

        return $images;
    }

    /**
     * This method creates an image sources.
     *
     * @param  Array   $image
     * @param  Integer $is_profile if default image (Optional; default=0).
     * @param  Integer $index      The index.
     * @return Array   The image set.
     */
    public function createImgSource($image, $is_profile = 0, $mediaType = 1)
    {
        $dir = $this->container->getParameter('CONFIG_SERVER_ROOT');

        if ($mediaType === $this->container->getParameter('MEDIA_TYPE_IMAGE')) {
            $source     = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/hotel-icon-image2.jpg');
            $sourcename = 'hotel-icon-image2.jpg';
            $sourcepath = 'media/images/';

            if (!empty($image) && !empty($image->getHotelId()) && !empty($image->getFilename())) {
                // retrieve dupePoolId
                $dupePoolId = $this->hotelRepo->getHotelDupePoolId($image->getHotelId());

                $sourcename = $image->getFilename();
                if (!empty($image->getLocation())) {
                    $img        = '/media/hotels/g'.$dupePoolId.'/'.$image->getLocation().'/'.$image->getFilename();
                    $sourcepath = 'media/hotels/g'.$dupePoolId.'/'.$image->getLocation().'/';
                } else {
                    $img        = '/media/hotels/g'.$dupePoolId.'/'.$image->getFilename();
                    $sourcepath = 'media/hotels/g'.$dupePoolId.'/';
                }

                $source = $this->container->get("TTRouteUtils")->generateMediaURL($img);
            }
        } else {
            $media_360_base_path = $this->container->getParameter('MEDIA_360_BASE_PATH');
            $sourcename          = $image->getFilename();
            $sourcepath          = $media_360_base_path.$image->getLocation();
        }

        if ($is_profile == 2) {
            $sourceProfile2 = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 290, 166, 'hotels65HS290166', $sourcepath, $sourcepath, 65);
            $sourceBig      = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 885, 468, 'hotels75HS885468', $sourcepath, $sourcepath, 75);
            $sourceProfile4 = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 345, 196, 'hotels65HS345196', $sourcepath, $sourcepath, 65);
            $imageSet       = array($sourceProfile2, $sourceBig, $sourceProfile4);
        } elseif ($is_profile == 1) {
            $sourceProfile  = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 139, 74, 'hotels65HS13974', $sourcepath, $sourcepath, 65);
            $sourceProfile2 = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 290, 166, 'hotels65HS290166', $sourcepath, $sourcepath, 65);
            $sourceProfile3 = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 108, 60, 'hotels65HS10860', $sourcepath, $sourcepath, 65);
            $sourceProfile4 = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 345, 196, 'hotels65HS345196', $sourcepath, $sourcepath, 65);
            $imageSet       = array($sourceProfile, $sourceProfile2, $sourceProfile3, $source, $sourceProfile4);
        } else {
            $sourceSmall  = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 78, 42, 'hotels50HS7842', $sourcepath, $sourcepath, 50);
            $sourceSmall2 = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 186, 100, 'hotels65HS186100', $sourcepath, $sourcepath, 65);
            $sourceBig    = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 885, 468, 'hotels75HS885468', $sourcepath, $sourcepath, 75);
            $imageSet     = array($sourceSmall, $sourceBig, $sourceSmall2, $source);
        }

        return $imageSet;
    }

    /**
     * This method retrieves the main image per hotel that is marked as default(main) to be shown.
     *
     * @param  Integer $hotelId    The hotel id.
     * @param  Integer $index
     * @param  Integer $is_profile
     * @return Array   The hotel main image.
     */
    public function getHotelMainImage($hotelId, $index = null, $is_profile = 2)
    {
        $hotelImage = $this->imageRepo->getHotelMainImage($hotelId);

        if ($hotelImage) {
            $mainImage = $this->createImgSource($hotelImage, $is_profile);
        } else {
            $mainImage = array($this->container->get("TTRouteUtils")->generateMediaURL('/media/images/hotel-icon-image2.jpg'), $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/hotel-icon-image2.jpg'));
        }

        if (isset($index)) {
            $mainImage = (isset($mainImage[$index])) ? $mainImage[$index] : $mainImage[0];
        }

        return $mainImage;
    }

    //*****************************************************************************************
    // Helper Functions
    /**
     * This method retrieves the error message (if not provided) for a certain error code.
     *
     * @param  Array  $error The error in the format array('code' => '', 'message' => '')
     * @return String The error message.
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
            $error['message'] = $this->translator->trans("Error encountered while processing your booking. ");
        }

        return $error['message'];
    }

    /**
     * This method generates a hotel details link/URL based on parameters provided.
     *
     * @param  String  $title
     * @param  Integer $id                  The hotel id.
     * @param  Integer $transactionSourceId
     * @param  Mixed   $minPriceOfferVars
     * @return String  The generated hotel detail link/URL
     */
    public function returnHotelDetailedLink($title, $id, $transactionSourceId = null, $minPriceOfferVars = null)
    {
        if (!empty($transactionSourceId)) {
            $this->transactionSourceId = $transactionSourceId;
        }
        $titled = $this->utils->cleanTitleData($title);
        $titled = str_replace('-', '+', $titled);
        $lnk    = $titled.'-'.$id;

        if ($minPriceOfferVars && is_array($minPriceOfferVars)) {
            $varIndex = 0;

            foreach ($minPriceOfferVars as $var => $value) {
                $lnk .= ($varIndex ? '&' : '?').$var.'='.$value;

                $varIndex++;
            }
        }

        if ($this->transactionSourceId == $this->validTransactionSource['corpo']) {
            return $this->utils->generateLangURL($this->siteLanguage, '/corporate/hotels/details-'.$lnk, 'corporate');
        } else {
            return $this->utils->generateLangURL($this->siteLanguage, '/hotel-detailsTT-'.$lnk);
        }
    }

    /**
     * This method generates a hotel reviews link/URL based on parameters provided.
     *
     * @param  String  $title
     * @param  Integer $id                  The hotel id.
     * @param  Integer $transactionSourceId
     * @return String  The generated hotel detail link/URL
     */
    public function returnHotelReviewsLink($title, $id, $transactionSourceId = null)
    {
        if (!empty($transactionSourceId)) {
            $this->transactionSourceId = $transactionSourceId;
        }

        $titled = $this->utils->cleanTitleData($title);
        $titled = str_replace('-', '+', $titled);
        $lnk    = $titled.'-'.$id;
        if ($this->transactionSourceId == $this->validTransactionSource['corpo']) {
            return $this->utils->generateLangURL($this->siteLanguage, '/corporate/hotels/reviews-'.$lnk, 'corporate');
        } else {
            return $this->utils->generateLangURL($this->siteLanguage, '/hotel-reviewsTT-'.$lnk);
        }
    }

    /**
     * In case of Amadeus booking/cancellation error, send an alert email to us
     *
     * @param  String           $operation
     * @param  \DateTime        $operationTime
     * @param  HotelReservation $hotelReservation
     * @return Array            list of email elements, subject, and message.
     */
    public function sendEmailOnError($operation, $operationTime, $hotelReservation)
    {
        $hotelName = $this->hotelRepo->find($hotelReservation->getHotelId())->getPropertyName();
        $checkIn   = $this->utils->formatDate($hotelReservation->getFromDate(), 'long');
        $checkOut  = $this->utils->formatDate($hotelReservation->getToDate(), 'long');
        $roomCount = $this->em->createQuery("SELECT COUNT(r.id) FROM HotelBundle:HotelRoomReservation r WHERE r.hotelReservationId = :id ")->setParameter('id', $hotelReservation->getId())->getSingleScalarResult();

        $subject = "Error Report: Hotel {$operation} Failed";

        $msg = "Name of guest(s): {$hotelReservation->getFirstName()} {$hotelReservation->getLastName()}<br/>";
        $msg .= "Hotel ID: {$hotelReservation->getHotelId()}<br/>";
        $msg .= "Hotel Name: {$hotelName}<br/>";
        $msg .= "Check-In: {$checkIn}<br/>";
        $msg .= "Check-Out: {$checkOut}<br/>";
        $msg .= "# of Rooms: {$roomCount}<br/>";
        $msg .= "Operation: {$operation}<br/>";
        $msg .= "Exact Date/Time of operation: {$this->utils->formatDate($operationTime, 'datetime')}<br/>";

        $recipients = $this->container->getParameter('modules')['hotels']['admin_emails'];

        foreach ($recipients as $recipient) {
            $this->emailServices->addEmailData($recipient, $msg, $subject, 'TouristTube.com', 0);
        }

        return array($subject, $msg);
    }

    /**
     * This method formats price according to requirements: either amount only, amount with currency or amount wrapped in HTML for currency conversion.
     *
     * @param  String  $price
     * @param  Boolean $returnNullValue
     * @param  Boolean $priceOnly
     * @param  Boolean $forceConvert
     * @param  Integer $priceFloor
     * @return String  The price.
     */
    private function getDisplayPrice($price, $returnNullValue = true, $priceOnly = false, $forceConvert = false, $priceFloor = true, $options = array())
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

        if (isset($this->convertCurrencyPriceFloor)) {
            $priceFloor = $this->convertCurrencyPriceFloor;
        }

        if ($this->convertCurrency || $forceConvert) {
            //Currency popup
            $siteCurrency = (!empty(filter_input(INPUT_COOKIE, 'currency'))) ? filter_input(INPUT_COOKIE, 'currency') : $this->selectedCurrency;

            if ($siteCurrency != "" && $siteCurrency != $price['currencyCode']) {
                if ($price['amount'] > 0) {
                    $conversionRate  = $this->currencyService->getConversionRate($price['currencyCode'], $siteCurrency);
                    $price['amount'] = $this->currencyService->currencyConvert($price['amount'], $conversionRate);
                }
                $price['currencyCode'] = $siteCurrency;
            }
        }

        $dataPrice = $price['amount'];

        if ($priceOnly) {
            return ($this->isRest) ? number_format(floatval($dataPrice), 2, null, '') : $dataPrice;
        } else {
            if (($price['amount'] <= 0) && !$returnNullValue) {
                $price['amount'] = '0.00';
            }

            if ($price['amount'] !== 0) {
                if ($this->convertCurrency || $forceConvert) {
                    if ($this->isRest) {
                        $dataPrice = number_format(floatval($dataPrice), 2, null, '');
                        $priceTxt  = "{$price['currencyCode']} {$dataPrice}";
                    } else {
                        if ($priceFloor) {
                            $price['amount'] = number_format(floor($price['amount']));
                        } else {
                            $price['amount'] = number_format($price['amount'], 2);
                        }

                        $priceTxt = '<span class = "price-convert" data-price = "'.$dataPrice.'">'
                            .$default_options['append_content']['before_currency_text']
                            .'<span class = "currency-convert-text '.$default_options['append_class']['currency_text'].'">'.$price['currencyCode'].'</span>'
                            .$default_options['append_content']['after_currency_text']
                            .'<span class = "price-convert-text '.$default_options['append_class']['price_text'].'">'.$price['amount'].'</span>'
                            .$default_options['append_content']['after_price_text']
                            .'</span>';
                    }
                } else {
                    $priceTxt = $price['currencyCode'].' '.number_format($price['amount'], 2);
                }
            }

            return $priceTxt;
        }
    }

    /**
     * This method signs-out the API session.
     *
     * @param Array   $session The API session.
     * @param Integer $hotelId The hotel id.
     * @param Integer $userId  The user id.
     */
    private function signout($session, $hotelId = 0, $userId = 0)
    {
        $signoutParams = array(
            'stateful' => true,
            'session' => $session,
            'hotelId' => $hotelId,
            'userId' => $userId
        );
        $this->amadeus->securitySignOut($signoutParams);
    }

    //********************************************************************************************
    // Mapping functions
    /**
     * This method retrieves the map data of a hotel.
     *
     * @param  Integer $hotelId             The hotel id.
     * @param  Integer $searchRequestId     The hotel search request id.
     * @param  Integer $transactionSourceId The transaction source id.
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
        $twigData['markerImage']     = ($this->validTransactionSource['corpo'] == $this->transactionSourceId) ? $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/pin_hot.png') : $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/pin_hot.png');
        $twigData['markerImageBlue'] = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/pin_hot_blue.png');
        $twigData['mapName']         = ' hotel: '.$row['name'];

        $mainImages = $this->getHotelMainImage($hotelId);

        $row['img']  = $mainImages[0];
        $row['link'] = $this->returnHotelDetailedLink($row['name'], $row['id']);

        $hotel_data[] = $row;

        if ($searchRequestId && $searchRequestId != 0) {
            $searchRequestList = $this->em->getRepository('HotelBundle:HotelSearchResponse')->findByHotelSearchRequestId($searchRequestId);
            foreach ($searchRequestList as $item) {
                if ($item->getHotelId() != $hotelId) {
                    $row        = $this->hotelRepo->getHotelDataById($item->getHotelId());
                    $mainImages = $this->getHotelMainImage($item->getHotelId());

                    $row['img']   = $mainImages[0];
                    $row['link']  = $this->returnHotelDetailedLink($row['name'], $row['id']);
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

    /**
     * This method returns the map image url
     *
     * @param  Integer $hotelId
     * @param  Integer $requestId
     * @param  Integer $trxSourceId
     * @param  String  $pageSrc
     * @return string
     */
    public function getMapImageUrl($hotelId, $requestId, $trxSourceId, $pageSrc = '')
    {
        $showOnMapUrl = '/hotels-show-on-map?hotelId='.$hotelId.'&requestId='.$requestId.'&trxSourceId='.$trxSourceId;
        if (!empty($pageSrc)) {
            $showOnMapUrl .= '&pageSrc='.$pageSrc;
        }
        if ($this->transactionSourceId == $this->validTransactionSource['corpo']) {
            return $this->utils->generateLangURL($this->siteLanguage, '/corporate'.$showOnMapUrl, 'corporate');
        } else {
            return $this->utils->generateLangURL($this->siteLanguage, $showOnMapUrl);
        }
    }
}
