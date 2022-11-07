<?php

namespace HotelBundle\vendors\HRS\v46;

use TTBundle\Utils\Utils;
use Symfony\Component\DependencyInjection\ContainerInterface;
use HotelBundle\Model\Hotel;
use HotelBundle\Model\HotelApiResponse;
use HotelBundle\Model\HotelRoom;
use HotelBundle\Model\HotelAvailability;

class HRSNormalizer
{
    private $utils;
    private $container;
    private $translator;

    /**
     * The __construct when we make a new instance of HRSNormalizer class.
     *
     * @param Utils              $utils
     * @param ContainerInterface $container
     */
    public function __construct(Utils $utils, ContainerInterface $container)
    {
        $this->utils      = $utils;
        $this->container  = $container;
        $this->translator = $this->container->get('translator');
    }

    //*****************************************************************************************
    // HotelAvail Functions
    /**
     * Parse Availability Response
     *
     * @param  array  $responseArr
     *
     * @return
     */
    public function parseAvailabilityResponse(array $responseArr)
    {
        $toReturn = new HotelAvailability();

        if (isset($responseArr['error'])) {
            $toReturn->getStatus()->setError($responseArr['error']);
        } elseif (isset($responseArr['hotelAvailHotelOffers']) && !empty($responseArr['hotelAvailHotelOffers'])) {
            $apiHotels                            = array();
            $responseArr['hotelAvailHotelOffers'] = $this->utils->encloseWithArray($responseArr['hotelAvailHotelOffers']);

            foreach ($responseArr['hotelAvailHotelOffers'] as $hot) {
                $price            = 0;
                $avgPrice         = 0;
                $cancelable       = 0;
                $breakfast        = 0;
                $uniqueRoomOffers = array();

                $hot['roomOffers'] = $this->utils->encloseWithArray($hot['roomOffers']);
                foreach ($hot['roomOffers'] as $rooms) {
                    // There are times when we query for multiple rooms for the same room type,
                    // and offers for the same room type are just the same so we dont need to loop the duplicate offers
                    if (in_array($rooms['room']['roomType'], $uniqueRoomOffers)) {
                        continue;
                    } else {
                        $uniqueRoomOffers[] = $rooms['room']['roomType'];
                    }

                    $rooms['offers'] = $this->utils->encloseWithArray($rooms['offers']);
                    foreach ($rooms['offers'] as $offers) {
                        // Among all offers, get the cheapest offer for this hotel, the cheapest is also the one to be displayed
                        if (empty($price) || ($offers['totalPriceInclusiveHotel']['amount'] < $price)) {
                            $price    = $offers['totalPriceInclusiveHotel']['amount'];
                            $avgPrice = $offers['averagePriceHotel']['amount'];
                            $currency = $offers['totalPriceInclusiveHotel']['isoCurrency'];
                        }

                        // hotel is flagged cancelable if at least one offer has a cancellationDeadline > NOW()
                        if (!$cancelable) {
                            $cancellationDeadline = $offers['cancellationDeadline'];
                            if (is_array($cancellationDeadline)) {
                                $cancellationDeadline = array_shift($cancellationDeadline);
                            }

                            if ($cancellationDeadline && (strtotime($cancellationDeadline) > strtotime('now'))) {
                                $cancelable = 1;
                            }
                        }

                        if (!$breakfast && in_array($offers['breakfastType'], array('inclusive', 'inclusiveHalfBoard', 'inclusiveFullBoard', 'allInclusive'))) {
                            $breakfast = 1;
                        }
                    }
                }

                $apiHotels[$hot['hotelKey']]['price']       = $price;
                $apiHotels[$hot['hotelKey']]['avgPrice']    = $avgPrice;
                $apiHotels[$hot['hotelKey']]['isoCurrency'] = $currency;
                $apiHotels[$hot['hotelKey']]['cancelable']  = $cancelable;
                $apiHotels[$hot['hotelKey']]['breakfast']   = $breakfast;
            }

            $toReturn->setAvailableHotels($apiHotels);
            if (!$toReturn->hasAvailableHotels()) {
                // return no availability message if we don't have hotel(s) returned
                $toreturn->getStatus()->setError($this->translator->trans('There is no availability on the selected dates at this time.'));
            }
        }

        return $toReturn;
    }

    //*****************************************************************************************
    // Hotel Information Functions
    /**
     * This action retrieves hotel information from the API response.
     *
     * @param type $hotelDetail The JSON response
     *
     * @return
     */
    public function processHotelDetailInformation($hotelDetail)
    {
        $hotelDetail = json_decode(json_encode($hotelDetail), true);

        $hotel = new Hotel();

        $hotel->setFax((isset($hotelDetail['fax'])) ? $hotelDetail['fax'] : '');
        $hotel->setEmail((isset($hotelDetail['email'])) ? $hotelDetail['email'] : '');
        $hotel->setPhone((isset($hotelDetail['phone'])) ? $hotelDetail['phone'] : '');

        $hotel->setAcceptedCreditCards((isset($hotelDetail['acceptedCreditCards'])) ? $hotelDetail['acceptedCreditCards'] : array());
        $hotel->setCreditCardSecurityCodeRequired((isset($hotelDetail['creditCardSecurityCodeRequired'])) ? $hotelDetail['creditCardSecurityCodeRequired'] : false);

        $checkInEarliest = '14:00';
        $checkOutLatest  = '12:00-13:00';
        if (isset($hotelDetail['checkInEarliest']) && !empty($hotelDetail['checkInEarliest'])) {
            $checkInEarliest = $this->utils->formatDate($hotelDetail['checkInEarliest'], 'militaryTime');
        }
        if (isset($hotelDetail['checkOutLatest']) && !empty($hotelDetail['checkOutLatest'])) {
            $checkOutLatest = $this->utils->formatDate($hotelDetail['checkOutLatest'], 'militaryTime');
        }
        $hotel->setCheckInEarliest($checkInEarliest);
        $hotel->setCheckOutLatest($checkOutLatest);

        $hotel->setDistances((isset($hotelDetail['distances'])) ? $hotelDetail['distances'] : array());
        $hotel->setFreeServices((isset($hotelDetail['freeServices'])) ? $hotelDetail['freeServices'] : array());

        return $hotel;
    }

    //*****************************************************************************************
    // Offers Functions
    /**
     * Process hotel offers response
     *
     * @param  JSON $responseJSON
     *
     * @return
     */
    public function processHotelOffersResponse($responseJSON)
    {
        $response = json_decode(json_encode($responseJSON), true);

        $maxRoomCount    = array();
        $counter         = 1;
        $hotelRoomOffers = array();

        if (isset($response['hotelDetail']) && !empty($response['hotelDetail'])) {
            $hotel = $this->processHotelDetailInformation($response['hotelDetail']);
        } else {
            $hotel = new Hotel;
        }

        if (isset($response['roomOfferDetails']) && !empty($response['roomOfferDetails'])) {
            foreach ($response['roomOfferDetails'] as $roomOffer) {
                $roomTypeInfo = $this->getRoomTypeInfo($roomOffer['room'], $maxRoomCount);

                $roomCode = $roomTypeInfo['code'];

                if (!isset($hotelRoomOffers[$roomCode])) {
                    foreach ($roomOffer['offerDetails'] as $offerDetail) {
                        $roomDetails = $this->getHotelRoomOffer($offerDetail, $roomTypeInfo, $counter);

                        $roomDetails->setBookableInfo($roomOffer['room']);
                        $roomDetails->setRoomOfferXml(json_encode($offerDetail));

                        if (!isset($hotelRoomOffers[$roomCode]['header'])) {
                            $hotelRoomOffers[$roomCode]['header'] = $roomDetails->getHeader();
                        }

                        $offerName = strtolower(preg_replace('/(\s|[^a-zA-Z0-9])+/m', '_', $roomDetails->getName()));
                        if (!isset($hotelRoomOffers[$roomCode][$offerName])) {
                            $hotelRoomOffers[$roomCode][$offerName] = array('roomSize' => array());
                        }

                        $hotelRoomOffers[$roomCode][$offerName][] = $roomDetails;
                        $counter++;
                    }
                }
            }

            // For offers that correspond to the same room criteria, user can add or remove number of rooms
            // But maximum rooms will only be as to how many searches for that room criteria was given
            foreach ($hotelRoomOffers as $code => &$room) {
                foreach ($room as $key => &$offers) {
                    if (strtolower($key) == 'header') {
                        continue;
                    }

                    foreach ($offers as $key1 => &$offer) {
                        if (strtolower($key1) == 'roomsize') {
                            continue;
                        }

                        $max = 4;
                        if ($offer->getRoomsLeftCount() > 0) {
                            $max = $offer->getRoomsLeftCount();
                        } elseif ($maxRoomCount[$code] > $max) {
                            $max = $maxRoomCount[$code];
                        }

                        $offer->setMaxRoomCount($max);
                    }
                }
            }

            $hotel->setRoomOffers($hotelRoomOffers);

            // Hotel book iterator
            $hotel->setTotalNumOffers($counter - 1);

            // Get taxes and fees on the first offer.
            // Assuming that they are all the same for all offers of a hotel since they want it displayed on top level.
            $taxes = array();
            if (isset($response['roomOfferDetails'][0]['offerDetails'][0]['taxesAndFees']['taxAndFeeDetails'])) {
                foreach ($response['roomOfferDetails'][0]['offerDetails'][0]['taxesAndFees']['taxAndFeeDetails'] as $tandF) {
                    if (!in_array($tandF['description'], array_column($taxes, 'description'))) {
                        $taxes[] = array(
                            'taxAndFeeType' => $tandF['taxAndFeeType'],
                            'description' => $tandF['description'],
                            'inclusive' => ($tandF['inclusive']) ? $this->translator->trans('Included') : $this->translator->trans('At the locally applicable rates')
                        );
                    }
                }
            }

            $hotel->setIncludedTaxAndFees($taxes);
        }

        return $hotel;
    }

    //*****************************************************************************************
    // RoomOffers Functions
    /**
     * This method returns the correct room type
     *
     * @param array $room
     * @param array $maxRoomCount
     *
     * @return
     */
    public function getRoomTypeInfo($room, &$maxRoomCount = array())
    {
        $return              = array();
        $return['guestInfo'] = '';

        switch ($room['roomType']) {
            case 'single':
                $return['code'] = 'guest1';
                $return['type'] = $this->translator->trans('Single Rooms');
                break;

            case 'double':
                $return['code'] = 'guest2';
                $return['type'] = $this->translator->trans('Double Rooms');

                if (isset($room['childAccommodationCriteria'])) {
                    $ch = array('parentsBed' => 0, 'extraBed' => 0);

                    foreach ($room['childAccommodationCriteria'] as $cac) {
                        $ch[$cac['childAccommodation']] ++;
                    }

                    if ($ch['extraBed'] == 2) {
                        $return['code']      = 'guest5';
                        $return['guestInfo'] = $this->translator->trans('2 children in extra beds');
                    } elseif ($ch['extraBed'] == 1) {
                        if ($ch['parentsBed'] == 1) {
                            $return['code']      = 'guest6';
                            $return['guestInfo'] = $this->translator->trans("1 child in parent's bed, 1 child in extra bed");
                        } else {
                            $return['code']      = 'guest7';
                            $return['guestInfo'] = $this->translator->trans('1 child in extra bed');
                        }
                    } elseif ($ch['parentsBed'] == 1) {
                        $return['code']      = 'guest8';
                        $return['guestInfo'] = $this->translator->trans("1 child in parent's bed");
                    }
                }
                break;

            case 'double+1':
                $return['code']      = 'guest3';
                $return['type']      = $this->translator->trans('Double Rooms');
                $return['guestInfo'] = $this->translator->trans('extra bed for 3rd person');

                if (isset($room['childAccommodationCriteria'])) {
                    if ($room['childAccommodationCriteria'][0]['childAccommodation'] == 'extraBed') {
                        $return['code']      = 'guest9';
                        $return['guestInfo'] = $this->translator->trans(", 1 child in extra bed");
                    } else {
                        $return['code']      = 'guest10';
                        $return['guestInfo'] = $this->translator->trans(", 1 child in parent's bed");
                    }
                }
                break;

            case 'double+2':
                $return['code']      = 'guest4';
                $return['type']      = $this->translator->trans('Double Rooms');
                $return['guestInfo'] = $this->translator->trans('extra bed for 3rd and 4th person');
                break;
        }

        $return['guestInfo']           = (!empty($return['guestInfo'])) ? '('.$return['guestInfo'].')' : '';
        $maxRoomCount[$return['code']] = (isset($maxRoomCount[$return['code']])) ? ($maxRoomCount[$return['code']] + 1) : 1;

        return $return;
    }

    /**
     * Get hotel room offers
     *
     * @param  Array $offerDetail
     * @param $roomTypeInfo
     * @param $counter
     * @param $requestingPage
     *
     * @return
     */
    public function getHotelRoomOffer($offerDetail, $roomTypeInfo, $counter = 0, $requestingPage = 'OFFERS')
    {
        $hotelRoomOffer = new HotelRoom();
        $hotelRoomOffer->setCounter($counter);

        // Room Type Info
        $hotelRoomOffer->setRoomTypeInfo($roomTypeInfo);

        // Get offer category
        $category    = $this->getOfferName($offerDetail);
        $description = $offerDetail['roomDescriptions'][0]['roomDescriptionDetailsLong'];

        $roomSize = $this->extractRoomSizeSqmFromText(explode('<br/>', $description));

        if (in_array($requestingPage, array('OFFERS'))) {
            $filteredRoomCategory = $this->fetchCategoryFromDescription(explode('<br/>', $description));

            if (!empty($filteredRoomCategory)) {
                $category = $filteredRoomCategory;
            } else {
                // put offer under a custom category called "Suite" if size is >= our defined suite_room_size constant
                // and if parsed $category is 'standard room'
                if (count($roomSize) > 0) {
                    $maxRoomSize = max($roomSize);
                    if ($maxRoomSize >= $this->container->getParameter('hotels')['suite_room_size'] && strtolower($category) == 'standard room') {
                        $category = "Suite";
                    }
                }
            }

            // Replace "Unknown" and empty $category with a generic name "Guest Room"
            if (strtolower($category) == "unknown" || empty($category)) {
                $category = "Guest Room";
            }

            $category = ucfirst(trim(strtolower(preg_replace('/\s+/', ' ', $category))));

            $hotelRoomOffer->setRoomCategory($category); // formerly category
            $hotelRoomOffer->setHeader(array('title' => $roomTypeInfo['type'], 'subTitle' => $roomTypeInfo['guestInfo']));
        }

        $hotelRoomOffer->setRoomType($this->getRoomDescriptionType($offerDetail)); // formerly type
        $hotelRoomOffer->setName($category);
        $hotelRoomOffer->setDescription($description);
        $hotelRoomOffer->setRoomSize($roomSize);
        $hotelRoomOffer->setRoomOfferType($this->getOfferType($offerDetail));
        $hotelRoomOffer->setRoomsLeftCount((!$offerDetail['roomsLeft']) ? 0 : $offerDetail['roomsLeft']);

        // Rates
        $rateInfo       = array(
            'rates' => $offerDetail['rates'],
            'averageRoomPriceCustomer' => $offerDetail['averageRoomPriceCustomer'],
            'totalPriceCustomer' => $offerDetail['totalPriceCustomer'],
            'totalPriceInclusiveCustomer' => $offerDetail['totalPriceInclusiveCustomer'],
            'averageRoomPriceHotel' => $offerDetail['averageRoomPriceHotel'],
            'totalPriceHotel' => $offerDetail['totalPriceHotel'],
            'totalPriceInclusiveHotel' => $offerDetail['totalPriceInclusiveHotel'],
            'guaranteedReservationOnly' => $offerDetail['guaranteedReservationOnly'],
            'creditCardReservationOnly' => $offerDetail['creditCardReservationOnly'],
        );
        // Breakfast
        $breakfastRates = array(
            'breakfastPriceHotel' => (isset($offerDetail['rates']['breakfastPriceHotel'])) ? $offerDetail['rates']['breakfastPriceHotel'] : $offerDetail['averageBreakfastPriceHotel'],
            'breakfastPriceCustomer' => (isset($offerDetail['rates']['breakfastPriceCustomer'])) ? $offerDetail['rates']['breakfastPriceCustomer'] : $offerDetail['averageBreakfastPriceCustomer'],
        );

        $hotelRoomOffer->setRates(array_merge($rateInfo, $breakfastRates));
        $hotelRoomOffer->setBreakfastRates($breakfastRates);
        $hotelRoomOffer->setBreakfastType($offerDetail['breakfastType']);

        // Prepayment
        // @API: If the value for prepayRate is set to true this element thereby indicates a rate for which the customer needs to pay in advance, typically immediately after booking.
        $prepaymentMode = array();
        if ($offerDetail['prepayRate']) {
            $paymentType = 'deposit';

            $prepaymentMode['percent'] = ($offerDetail['averagePrepaymentPercent'] > 0) ? $offerDetail['averagePrepaymentPercent'] : 100;
        } else {
            $paymentType = 'guaranteed';
        }
        $hotelRoomOffer->setPrepaymentType($paymentType);
        $hotelRoomOffer->setPrepaymentValueMode($prepaymentMode);

        // Cancellation
        $hotelRoomOffer->setCancellable(($offerDetail['cancelable']) ? true : false);
        $hotelRoomOffer->setCancellationPenalties($this->getCancellationPenalties($offerDetail));

        return $hotelRoomOffer;
    }

    /**
     * This method fetches the room category from the room description free-text as per a pre-defined dictionary
     *
     * @param  Array  $descriptionTexts An array containing the Text elements from RoomRateDescription.
     *
     * @return string The filtered category
     */
    public function fetchCategoryFromDescription(array $descriptionTexts)
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
     *
     * @return Array The extracted sizes
     */
    private function extractRoomSizeSqmFromText($descriptionTexts)
    {
        $finalResult = array();

        foreach ($descriptionTexts as $text) {
            $result = array();
            $str    = preg_replace('/\s{2,}/m', " ", $text);

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
     * This method returns the offer name that is displayed on our twig design
     *
     * @param  Array $offerDetail
     *
     * @return
     */
    public function getOfferName($offerDetail)
    {
        return ucwords($offerDetail['roomDescriptions'][0]['roomDescription']);
    }

    /**
     * Returns the Room Description Type
     *
     * @param  Array $offerDetail
     *
     * @return
     */
    private function getRoomDescriptionType($offerDetail)
    {
        return $offerDetail['roomDescriptions'][0]['roomDescriptionType'];
    }

    /**
     * This method returns the main offer type of a certain room offer
     *
     * @param  Array $offerDetail
     *
     * @return
     */
    private function getOfferType($offerDetail)
    {
        return ($offerDetail['hotDeal']) ? 'Hot' : (($offerDetail['flexOffer']) ? 'Flex' : (($offerDetail['basicOffer']) ? 'Basic' : ''));
    }

    /**
     * Get the cancellation penalties
     *
     * @param  Array $offerDetail
     *
     * @return
     */
    public function getCancellationPenalties($offerDetail)
    {
        // For scenarios that don't fall into any of the conditions below, no message shall be shown
        // The API has not mentioned any details as to how to process the other scenarios
        // and we haven't yet found cases in hrs.com that match those scenario that we could apply on our system
        // Update these if necessary when new info has been given
        $penalties = array();

        if ($offerDetail['averageCancellationFeePercent'] > 0) {
            $cancellationPercent = $offerDetail['averageCancellationFeePercent'];
        } elseif ($offerDetail['guaranteedReservationOnly']) {
            $cancellationPercent = 100;
        } else {
            // Reservation is cancelable, and it is not a guaranteedreservation, which means no cost at all if canceled
            $cancellationPercent = 0;
        }

        $penalties['percent']           = $cancellationPercent;
        $penalties['absoluteDeadline']  = $offerDetail['cancellationDeadline'];
        $penalties['nonRefundableRate'] = $offerDetail['nonRefundableRate'];

        if (isset($offerDetail['rates']) && !empty($offerDetail['rates'][0]['priceComment'])) {
            $penalties['description'] = $offerDetail['rates'][0]['priceComment'];
        }

        return $penalties;
    }

    //*****************************************************************************************
    // Booking Functions
    /**
     * Processes the reservation response
     *
     * @param  Array $params
     * @param  Array $results
     *
     * @return
     */
    public function processReservationResponse($params, $results)
    {
        $response   = new HotelApiResponse();
        $returnData = array();

        if (isset($results->error)) {
            $response->setError($results->error->message);
        } elseif ($results) {
            $response->setSuccess(true);

            $returnData = array(
                'reservationProcessKey' => $results->reservationProcessKey,
                'reservationProcessPassword' => $results->reservationProcessPassword,
            );

            $results->reservationRoomOfferDetails = json_decode(json_encode($results->reservationRoomOfferDetails), true);
            foreach ($results->reservationRoomOfferDetails as $roomIndex => $roomOfferDetail) {
                if (empty($roomOfferDetail['predecessorReservationKey']) && $roomOfferDetail['room']['id'] != 1) {
                    $roomOfferDetail['oldReservationKey'] = $roomOfferDetail['room']['id'];
                } else {
                    $roomOfferDetail['oldReservationKey'] = $roomOfferDetail['predecessorReservationKey'];
                }

                $roomOfferDetail['offerDetail'] = $params['reservationCriterion']['reservationRoomOfferDetailCriteria'][$roomIndex]['offerDetail'];
                $roomOfferDetail['room']        = $params['reservationCriterion']['reservationRoomOfferDetailCriteria'][$roomIndex]['room'];

                $roomOfferDetail['reservationStatus'] = ($roomOfferDetail['reservationRoomResultCode'] == '0') ? 'reserved' : 'reservation failed';

                $returnData['roomOfferDetails'][$roomIndex] = $roomOfferDetail;
            }

            $response->setData($returnData);
        }
        return $response;
    }
}
