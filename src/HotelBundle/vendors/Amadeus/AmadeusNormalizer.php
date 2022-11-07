<?php

namespace HotelBundle\vendors\Amadeus;

use TTBundle\Utils\Utils;
use Symfony\Component\DependencyInjection\ContainerInterface;
use HotelBundle\vendors\Amadeus\AmadeusHandler;
use HotelBundle\Model\Hotel;
use HotelBundle\Model\HotelRoom;
use HotelBundle\Model\HotelCancellation;
use HotelBundle\Model\HotelAvailability;

class AmadeusNormalizer
{
    private $utils;
    private $container;
    private $handler;
    private $currencyService;
    private $templating;
    private $translator;

    /**
     * The __construct when we make a new instance of AmadeusNormalizer class.
     *
     * @param Utils $utils
     * @param ContainerInterface $container
     * @param AmadeusHandler $handler
     */
    public function __construct(Utils $utils, ContainerInterface $container, AmadeusHandler $handler)
    {
        $this->utils     = $utils;
        $this->container = $container;
        $this->handler   = $handler;

        $this->currencyService = $this->container->get('CurrencyService');
        $this->templating      = $this->container->get('templating');
        $this->translator      = $this->container->get('translator');
    }

    /**
     *  This method validates if API response failed or not.
     *
     * @param String $xml       The response XML.
     * @param Array $evaluation The variable where the information of the error is saved.
     * @return Boolean          TRUE if it false; otherwise FALSE.
     */
    public function evaluateForErrors($xml, &$evaluation)
    {
        $failed = false;

        $domDoc = new \DOMDocument;
        @$domDoc->loadXML($xml);

        if ($domDoc->childNodes->length === 0) {
            $failed              = true;
            $evaluation['error'] = array('code' => "Empty", 'message' => "XML is empty\n");
        } else {
            $warnings = $domDoc->getElementsByTagName('Warning');
            if ($warnings->length > 0) {
                $evaluation['warning'] = array('code' => '', 'message' => '');
                foreach ($warnings as $warning) {
                    $code                  = $warning->getAttribute('Type');
                    $evaluation['warning'] = array('code' => 'OTA_'.$code, 'message' => '');
                }
            }

            $errors = $domDoc->getElementsByTagName('Error');
            if ($errors->length > 0) {
                $failed              = true;
                $sessionErrorCodes   = array('784'); // Expecting more error codes will correspond to session being invalid
                $evaluation['error'] = array('code' => '', 'message' => '');
                foreach ($errors as $error) {
                    $code = $error->getAttribute('Code');
                    if ($code) {
                        $evaluation['error'] = array('code' => 'OTA_'.$code, 'message' => '');

                        if (in_array($code, $sessionErrorCodes)) {
                            // Treat it as session invalid so controller will be able to handle it
                            $evaluation['error'] = array('code' => 'OTA_SCM', 'message' => '');
                        }
                    }
                }
            }

            // This is for Hotel Sell
            if ($domDoc->getElementsByTagName('messageErrorInformation')->length > 0) {
                $category = $domDoc->getElementsByTagName('messageErrorInformation')->item(0)->getElementsByTagName('errorDetails')->item(0)->getElementsByTagName('errorCategory')->item(0)->nodeValue;
                switch (strtolower($category)) {
                    case 'ec':
                        $category = 'error';
                        $failed   = true;
                        break;
                    case 'wa':
                    case 'wec':
                        $category = 'warning';
                        break;
                    default:
                        $category = 'information';
                }

                $code = $domDoc->getElementsByTagName('messageErrorInformation')->item(0)->getElementsByTagName('errorDetails')->item(0)->getElementsByTagName('errorCode')->item(0)->nodeValue;

                $msg = '';
                if ($domDoc->getElementsByTagName('errorDescription')->length > 0) {
                    $msg = $domDoc->getElementsByTagName('errorDescription')->item(0)->getElementsByTagName('freeText')->item(0)->nodeValue;
                }

                $evaluation[$category] = array('code' => 'OTA_'.$code, 'message' => $msg);
            }

            // This is for PNR Commit, PNR Cancel
            if ($domDoc->getElementsByTagName('errorOrWarningCodeDetails')->length > 0) {
                $errorDetails = $domDoc->getElementsByTagName('errorOrWarningCodeDetails');
                foreach ($errorDetails as $errorDetail) {
                    $category = $errorDetail->getElementsByTagName('errorDetails')->item(0)->getElementsByTagName('errorCategory')->item(0)->nodeValue;
                    switch (strtolower($category)) {
                        case 'ec':
                            $category = 'error';
                            $failed   = true;
                            break;
                        case 'wa':
                        case 'wec':
                            $category = 'warning';
                            break;
                        default:
                            $category = 'information';
                    }

                    $code = $errorDetail->getElementsByTagName('errorDetails')->item(0)->getElementsByTagName('errorCode')->item(0)->nodeValue;

                    $msg         = array();
                    $existingMsg = (isset($evaluation[$category]['message'])) ? $evaluation[$category]['message'] : '';

                    if ($domDoc->getElementsByTagName('errorWarningDescription')->length > 0) {
                        $freeTexts = $domDoc->getElementsByTagName('errorWarningDescription')->item(0)->getElementsByTagName('freeText');
                        foreach ($freeTexts as $freeText) {
                            $msg[] = trim($freeText->nodeValue);
                        }
                    } else {
                        $msg[] = "";
                    }

                    if (!empty($existingMsg)) {
                        array_unshift($msg, $existingMsg);
                    }

                    $evaluation[$category] = array('code' => 'OTA_'.$code, 'message' => implode('; ', $msg));
                }
            }
        }

        return $failed;
    }

    /**
     * This method returns the correct \DOMXPath instance per provided XML response.
     *
     * @param String $xml
     * @param Mixed $domDoc If filled will be initialized with \DOMDocument instance
     * @return \DOMXPath
     */
    public function getXpath($xml, &$domDoc = null)
    {
        $rsType = $this->getResponseType($xml);
        $domDoc = new \DOMDocument('1.0', 'UTF-8');
        $domDoc->loadXML($xml);

        $xpath = new \DOMXPath($domDoc);
        switch (trim(strtoupper($rsType))) {
            case 'OTA_HOTELAVAILRS':
            case 'OTA_HOTELDESCRIPTIVEINFORS':
                $xpath->registerNamespace('soap', "http://schemas.xmlsoap.org/soap/envelope/");
                $xpath->registerNamespace('body', 'http://www.opentravel.org/OTA/2003/05');
                break;
            case 'HOTEL_SELLREPLY':
                $xpath->registerNamespace('soap', "http://schemas.xmlsoap.org/soap/envelope/");
                $xpath->registerNamespace('body', 'http://xml.amadeus.com/HBKRCR_15_4_1A');
                break;
            case 'PNR_LIST':
            case 'PNR_REPLY':
                $xpath->registerNamespace('soap', "http://schemas.xmlsoap.org/soap/envelope/");
                $xpath->registerNamespace('body', 'http://xml.amadeus.com/PNRACC_15_1_1A');
                break;
        }

        return $xpath;
    }

    /**
     * This method gets the response type of the provided XML response.
     *
     * @param String $xml
     */
    private function getResponseType($xml)
    {
        $responseTypes = array(
            'OTA_HotelAvailRS',
            'PNR_Reply',
            'Hotel_SellReply',
            'OTA_HotelDescriptiveInfoRS',
            'Security_SignOutReply',
            'PNR_List',
        );

        foreach ($responseTypes as $type) {
            if (stripos($xml, $type) !== FALSE) {
                return strtoupper($type);
            }
        }

        return "UNKNOWN";
    }

    //*****************************************************************************************
    // HotelAvail Functions
    /**
     * This method parse Hotel_MultiSingleAvailability response for search result page.
     *
     * @param Array $responseArr Array of XML response.
     * @param HotelSC $hotelSC  The instance of HotelSC
     * @return HotelAvailability
     */
    public function parseAvailabilityResponse($responseArr, $hotelSC)
    {
        $toreturn = new HotelAvailability();

        $hotels = array();
        $errors = array();

        if (!empty($responseArr)) {
            foreach ($responseArr as $xml) {
                $result = array();
                $this->evaluateForErrors($xml, $result);

                if (!isset($result['error'])) {
                    $domDoc = null;
                    $xpath  = $this->getXpath($xml, $domDoc);

                    $roomStay = $domDoc->getElementsByTagName('RoomStay');
                    if ($roomStay->length) {
                        $hotelStay = $domDoc->getElementsByTagName('HotelStay');

                        foreach ($hotelStay as $hotel) {
                            $price      = 0;
                            $avgPrice   = 0;
                            $currency   = '';
                            $cancelable = 0;
                            $breakfast  = 0;

                            // retrieve basic information: hotelCode, hotelName, roomStayRPH, etc.
                            $hotelInfo = $this->getHotelStayBasicInformation($xpath, $hotel);
                            extract($hotelInfo);

                            foreach ($roomStayRPH as $rph) {
                                $roomStay = $this->getRoomStayByRPH($xpath, $rph);
                                if ($roomStay->length) {
                                    $room = $roomStay->item(0);

                                    $prepaidIndicator = $this->getPrepaidIndicator($xpath, $room);
                                    if ($hotelSC->isPrepaidOnly() && !$prepaidIndicator) {
                                        continue;
                                    }

                                    $rate = $this->getRoomStayRate($xpath, $room, false);

                                    // Total Price
                                    if (!$price || $rate['total']['hotelRate']['amount'] < $price) {
                                        $price    = $rate['total']['hotelRate']['amount'];
                                        $currency = $rate['total']['hotelRate']['currencyCode'];
                                    }

                                    // Avg Price
                                    if (!$avgPrice || $rate['daily']['hotelRate']['amount'] < $avgPrice) {
                                        $avgPrice = $rate['daily']['hotelRate']['amount'];
                                    }

                                    // Cancelable
                                    if (!$cancelable) {
                                        // if no cancellation policy indicated or if an absolute deadline is future date, then we consider it as cancelable

                                        $cancelable = $this->isRoomCancelable($xpath, $room);
                                        if (!$cancelable) {
                                            // then it's not cancelable because of some penalties, validate if we are still on the free-cancellation range out of that penalties
                                            $cancellationDeadline = $this->getRoomCancellationDeadline($xpath, $room);
                                            if ($cancellationDeadline) {
                                                $deadline = date_create($cancellationDeadline);
                                                $now      = date_create('now');
                                                if ($deadline > $now) {
                                                    $cancelable = 1;
                                                }
                                            }
                                        }
                                    }

                                    // Breakfast
                                    if (!$breakfast) {
                                        $breakfastInfo = $this->getRoomStayBreakfastInformation($xpath, $room);
                                        if (!empty($breakfastInfo)) {
                                            if ($breakfastInfo['hasBreakfast']) {
                                                $breakfast = 1;
                                            } elseif ($breakfastInfo['hasMealPlanCodes']) {
                                                $hotels[$hotelCode]['hasMealPlanCodes'] = 1;
                                                $hotels[$hotelCode]['mealPlanCodes']    = $breakfastInfo['mealPlanCodes'];
                                            }
                                        }
                                    }

                                    if ($price) {
                                        $hotels[$hotelCode]['price']        = $price;
                                        $hotels[$hotelCode]['avgPrice']     = $avgPrice;
                                        $hotels[$hotelCode]['currencyCode'] = $currency;
                                        $hotels[$hotelCode]['cancelable']   = $cancelable;
                                        $hotels[$hotelCode]['breakfast']    = $breakfast;

                                        $hotels[$hotelCode]['hotelCode']       = $hotelCode;
                                        $hotels[$hotelCode]['hotelName']       = $hotelName;
                                        $hotels[$hotelCode]['distance']        = 0.00;
                                        $hotels[$hotelCode]['distances']       = array();
                                        $hotels[$hotelCode]['mainImage']       = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/hotel-icon-image2.jpg');
                                        $hotels[$hotelCode]['mainImageMobile'] = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/hotel-icon-image2.jpg');

                                        $hotels[$hotelCode]['rate'] = $rate;
                                    }
                                }
                            }
                        }
                    } elseif (isset($result['warning'])) {
                        $errors[] = $result['warning'];
                    }
                }
            }
        }

        $toreturn->setAvailableHotels($hotels);
        if (!count($hotels)) {
            // return no availability message if we don't have hotel(s) returned
            $toreturn->getStatus()->setError($this->translator->trans('There is no availability on the selected dates at this time.'));
        }

        return $toreturn;
    }

    /**
     * This method process the hotel listing availability response.
     *
     * @param Mixed $data       The data which contains the list of XML response from API.
     * @param HotelSC $hotelSC  The instance of HotelSC
     * @return HotelAvailability
     */
    public function processHotelListingAvailabilityResponse($data, $hotelSC)
    {
        $toreturn = new HotelAvailability();

        if (is_array($data)) {
            $nData = new \HotelBundle\Model\RspApiResponse();
            $nData->setRequest($data['lastRequest']);
            $nData->setResponse(array($data['lastResponse']));

            if (isset($data['error'])) {
                $nData->getStatus()->setError($data['error']);
            }

            $data = $nData;
        }

        if ($data->hasError()) {
            $toreturn->setStatus($data->getStatus());
        } elseif (!empty($data->getResponse())) {
            $toreturn = $this->parseAvailabilityResponse($data->getResponse(), $hotelSC);
        }

        return $toreturn;
    }

    //*****************************************************************************************
    // Hotel Information Functions
    /**
     * This action retrieves hotel information from the XML response
     *
     * @param String $xmlResponse            The XML response
     * @param Boolean $returnContactInfo    Flag to return the hotel contact information.
     * @return Hotel[] $hotels                Array of Hotel object
     */
    public function processHotelDescriptiveInfoResponse($xmlResponse, $returnContactInfo)
    {
        $domDoc = null;
        $xpath  = $this->getXpath($xmlResponse, $domDoc);

        $hotels = array();
        foreach ($domDoc->getElementsByTagName('HotelDescriptiveContent') as $content) {
            $hotel = new Hotel();
            $hotel->setHotelCode($content->getAttribute('HotelCode'));

            // Distance
            $hotel->setDistanceRefPoints($xpath->evaluate('./body:AreaInfo/body:RefPoints/body:RefPoint', $content));

            // Description
            $hotel->setDescription($xpath->evaluate('string(./body:HotelInfo/body:Descriptions//body:MultimediaDescription[@InfoCode="17"][@AdditionalDetailCode="2"]//body:TextItem/body:Description)', $content));

            // Check-In / Check-Out
            $checkIn  = $xpath->evaluate('string(./body:Policies//body:PolicyInfo/@CheckInTime)', $content);
            $checkOut = $xpath->evaluate('string(./body:Policies//body:PolicyInfo/@CheckOutTime)', $content);
            if (!empty($checkIn)) {
                $checkIn = $this->utils->formatDate($checkIn, 'militaryTime');
            }
            if (!empty($checkOut)) {
                $checkOut = $this->utils->formatDate($checkOut, 'militaryTime');
            }
            $hotel->setCheckInEarliest($checkIn);
            $hotel->setCheckOutLatest($checkOut);

            // Credit Cards
            $uniqueCards  = array();
            $paymentCards = $xpath->evaluate('./body:Policies//body:PaymentCard', $content);
            foreach ($paymentCards as $pc) {
                $cc = $pc->getAttribute('CardCode');
                if (!in_array($cc, $uniqueCards)) {
                    $uniqueCards[] = $cc;
                }
            }
            $hotel->setAcceptedCreditCards($uniqueCards);

            // Amenities
            $hotel->addAmenityInfo($xpath->evaluate('./body:FacilityInfo/body:GuestRooms/body:GuestRoom[body:TypeRoom[@RoomTypeCode="ALL"]]//body:Amenity', $content), 'RMA', 'RoomAmenityCode', 'Room Amenities');

            // Recreation
            $hotel->addAmenityInfo($xpath->evaluate('./body:AreaInfo/body:Recreations', $content), 'RST', 'Code', 'Recreation');

            if ($returnContactInfo) {
                $hotel->setContacts(array(
                    'phones' => $xpath->evaluate('./body:ContactInfos//body:Phone', $content),
                ));
                $hotel->setEmail($xpath->evaluate('string(./body:ContactInfos//body:Email)', $content));
            }
            $hotels[] = $hotel;
        }

        return $hotels;
    }

    /**
     * This action retrieves hotel information from the XML response.
     *
     * @param String $xmlResponse            The XML response
     * @param String $hotelCode
     * @return Hotel $hotel                The Hotel object
     */
    public function processHotelStayInformation($xmlResponse, $hotelCode)
    {
        $hotel = new Hotel();

        $domDoc = null;
        $xpath  = $this->getXpath($xmlResponse, $domDoc);

        $roomStay  = $domDoc->getElementsByTagName('RoomStay');
        $hotelStay = $this->getHotelStay($xpath, $hotelCode);

        // Basic Property Info for HotelRef
        $basicPropertyInfo = array();
        foreach ($hotelStay->getElementsByTagName('BasicPropertyInfo')->item(0)->attributes as $attr) {
            $basicPropertyInfo[lcfirst($attr->nodeName)] = $attr->nodeValue;
        }
        $hotel->setBasicPropertyInfo($basicPropertyInfo);

        // Amenities - HAC
        $hotel->addAmenityInfo($xpath->evaluate('./body:BasicPropertyInfo/body:HotelAmenity', $hotelStay), 'HAC', 'Code', 'Hotel Amenities');

        // Amenities - RST
        $hotel->addAmenityInfo($xpath->evaluate('./body:BasicPropertyInfo/body:Recreation', $hotelStay), 'RST', 'Code', 'Recreation');

        $checkIn  = '';
        $checkOut = '';
        foreach ($roomStay as $room) {
            // Check-In / Check-Out
            if (empty($checkIn)) {
                $checkIn = $xpath->evaluate('string(./body:RatePlans//body:AdditionalDetail[@Type="61"]/body:DetailDescription/body:Text)', $room);
            }
            if (empty($checkOut)) {
                $checkOut = $xpath->evaluate('string(./body:RatePlans//body:AdditionalDetail[@Type="62"]/body:DetailDescription/body:Text)', $room);
            }

            // Credit Cards
            if (empty($hotel->getAcceptedCreditCards())) {
                // only get the first set of credit card details among all rooms
                $hotel->setAcceptedCreditCards($this->getRoomPaymentCards($xpath, $room));
            }
        }
        if (!empty($checkIn)) {
            $checkIn = $this->utils->formatDate($checkIn, 'militaryTime');
        }
        if (!empty($checkOut)) {
            $checkOut = $this->utils->formatDate($checkOut, 'militaryTime');
        }
        $hotel->setCheckInEarliest($checkIn);
        $hotel->setCheckOutLatest($checkOut);

        return $hotel;
    }

    /**
     * This method retrieves the (\DOMNode) <HotelStay/> from the \DOMXPath instance of our API response for a certain hotelCode.
     *
     * @param \DOMXPath $xpath  THE \DOMXPath instance from our API response.
     * @param String $hotelCode The hotel code
     * @return \DOMNode The (\DOMNode) <HotelStay/>
     */
    private function getHotelStay($xpath, $hotelCode)
    {
        $hotelStay = $xpath->evaluate('//body:HotelStay[body:BasicPropertyInfo[@HotelCode="'.$hotelCode.'"]]')->item(0);
        if (empty($hotelStay)) {
            $hotelStay = $xpath->evaluate('//body:HotelStay')->item(0);
        }
        return $hotelStay;
    }

    /**
     * This method returns the hotel basic information for a given DOMNode <HotelStay/> from our API response.
     *
     * @param DOMXPath $xpath       The DOMXpath instance from our API response
     * @param DOMNode $hotelStay    The DOMNode <HotelStay/>
     */
    private function getHotelStayBasicInformation($xpath, $hotelStay)
    {
        return array(
            'hotelCode' => $xpath->evaluate('string(./body:BasicPropertyInfo/@HotelCode)', $hotelStay),
            'hotelName' => $xpath->evaluate('string(./body:BasicPropertyInfo/@HotelName)', $hotelStay),
            'roomStayRPH' => explode(' ', $hotelStay->getAttribute('RoomStayRPH'))
        );
    }

    //*****************************************************************************************
    // Offers Functions
    /**
     * This method retrieves data from API XML response.
     *
     * @param String $xmlResponse       The API XML response.
     * @param Array $roomStayCandidate
     * @param Array $otaPaymentTypes
     * @param Boolean $prepaidOnly
     * @return Hotel
     */
    public function processHotelOffersResponse($xmlResponse, $roomStayCandidate, $otaPaymentTypes, $prepaidOnly = false)
    {
        $hotel = new Hotel;

        $domDoc = null;
        $xpath  = $this->getXpath($xmlResponse, $domDoc);

        $roomStay = $domDoc->getElementsByTagName('RoomStay');
        if ($roomStay->length) {
            $hotelRoomOffers = array();
            $counter         = 1;
            foreach ($roomStay as $room) {
                $prepaidIndicator = $this->getPrepaidIndicator($xpath, $room);
                if ($prepaidOnly && !$prepaidIndicator) {
                    continue;
                }

                $hotelRoomOffers[] = $this->getRoomStayDetails($xpath, $room, $counter, $otaPaymentTypes, 'offer', $roomStayCandidate);

                // GDS
                if (!$hotel->isGds('gds')) {
                    $hotel->setGds($this->isOfferGDS($room));
                }
                $counter++;
            }
            $hotel->setRoomOffers($hotelRoomOffers);

            /* // Below reserve button - taxes
              $taxes          = array();
              $roomOfferTaxes = $xpath->evaluate('./body:RoomRates/body:RoomRate[1]/body:Total/body:Taxes/body:Tax', $roomStay->item(0));
              foreach ($roomOfferTaxes as $tax) {
              $taxInfo         = array();
              $taxInfo['code'] = $tax->getAttribute('Code');

              $taxInfo['desc'] = '';
              $texts           = $xpath->evaluate('./TaxDescription/Text', $tax);
              if ($texts->length) {
              $taxInfo['desc'] = '';
              foreach ($texts as $text) {
              $taxInfo['desc'] .= $text->nodeValue.' ';
              }
              }
              $taxInfo['type'] = $tax->getAttribute('Type');
              $taxes[]         = $taxInfo;
              }
              $hotel->setIncludedTaxAndFees($taxes); */

            // Hotel book iterator
            $hotel->setTotalNumOffers($counter - 1);
        }
        return $hotel;
    }

    /**
     * This method is a wrapper function to process Hotel_EnhancedPricing response.
     *
     * @param String $xmlResponse           The API XML response.
     * @param Array $roomStayCandidate      The room stay candidate from our room criteria.
     * @param Array $otaPaymentTypes
     * @param Array $bookableInfoSelected   The booking information of the selected offers.
     * @return Array    Error or enhanced pricing information of selected offers for booking.
     */
    public function processHotelOffersEnhancedPricingResponse($xmlResponse, $roomStayCandidate, $otaPaymentTypes, $bookableInfoSelected)
    {
        $hotel = new Hotel;

        $domDoc = null;
        $xpath  = $this->getXpath($xmlResponse, $domDoc);

        $roomStay = $domDoc->getElementsByTagName('RoomStay');
        if ($roomStay->length) {
            $hotelRoomOffers = array();
            $counter         = 1;
            foreach ($roomStay as $room) {
                $hotelRoomOffer = $this->getRoomStayDetails($xpath, $room, $counter, $otaPaymentTypes, 'book', $roomStayCandidate, $domDoc);

                $bookingCode      = $hotelRoomOffer->getBookableInfo()['bookingCode'];
                $numRoomsSelected = (isset($bookableInfoSelected[$bookingCode])) ? $bookableInfoSelected[$bookingCode]['quantity'] : 1;
                $counter          += $numRoomsSelected;

                $hotelRoomOffers[] = $hotelRoomOffer;

                // GDS
                if (!$hotel->isGds('gds')) {
                    $hotel->setGds($this->isOfferGDS($room));
                }
                // groupSell
                if ($hotel->getGroupSell() === null) {
                    $marketCode = $room->getAttribute('MarketCode');
                    if ($marketCode) {
                        // I am ssumming that if value has Group or Package and we are booking for multiple rooms, then that is the indicator that this should be processed as Group Sell
                        if ((stripos($marketCode, 'Group') !== FALSE) || (stripos($marketCode, 'Package') !== FALSE)) {
                            $hotel->setGroupSell('1'); // group multiple sell
                        } else {
                            $hotel->setGroupSell('0'); // multiple independent sell
                        }
                    }
                }
            }
            $hotel->setRoomOffers($hotelRoomOffers);
        }

        return $hotel;
    }

    //*****************************************************************************************
    // RoomStay Functions
    /**
     * This method returns the cancellation deadline of a certain DOMNode <RoomStay/> from our API response.
     *
     * @param DOMXPath $xpath   The DOMXPath instance of our API response.
     * @param DOMNode $room     The DOMNode <RoomStay/>
     * @return String           The cancellation deadline.
     */
    private function getRoomCancellationDeadline($xpath, $room)
    {
        $cancelPenalties = $xpath->evaluate('./body:RatePlans/body:RatePlan/body:CancelPenalties', $room);
        return $xpath->evaluate('string(./body:CancelPenalty[@PolicyCode="Cancellation"]/body:Deadline/@AbsoluteDeadline)', $cancelPenalties->item(0));
    }

    /**
     * This method returns the (\DOMNodeList) <RoomStay/> for a certain bookingCode from the API response.
     *
     * @param \DOMXPath $xpath
     * @param String $bookingCode
     * @return \DOMNodeList
     */
    public function getRoomStayByBookingCode($xpath, $bookingCode)
    {
        return $xpath->evaluate("//soap:Body/body:OTA_HotelAvailRS/body:RoomStays/body:RoomStay[body:RoomRates/body:RoomRate/@BookingCode=\"{$bookingCode}\"]");
    }

    /**
     * This method retrieves, formats and convert prices from API response
     *
     * @param DOMXPath $xpath       The instance of DOMXpath from API response
     * @param DOMNode $room         The RoomStay node.
     * @param Boolean $withMarkup
     * @return Array of room rate information.
     */
    private function getRoomStayRate($xpath, $room, $withMarkup = true)
    {
        // used by hotelAvailAction, hotelOffersAction
        $rateInfo = array();

        // Not all have values in AmountBeforeTax nor AmountAfterTax, so we'll prioritize depending on value passed
        $taxState = ($withMarkup) ? array('AmountIncludingMarkup', 'AmountAfterTax', 'AmountBeforeTax') : array('AmountBeforeTax', 'AmountAfterTax');
        foreach ($taxState as $state) {
            if (empty($rateInfo['total']['hotelRate']['amount'])) {

                $amount       = 0;
                $currencyCode = '';
                $totals       = $xpath->evaluate('./body:RoomRates/body:RoomRate[1]/body:Rates/body:Rate/body:Total', $room);
                if ($totals->length) {
                    foreach ($totals as $total) {
                        if (!$amount || $total->getAttribute($state) < $amount) {
                            $amount       = $total->getAttribute($state);
                            $currencyCode = $total->getAttribute('CurrencyCode');
                        }
                    }
                } else {
                    $totals = $xpath->evaluate('./body:RoomRates/body:RoomRate[1]/body:Total', $room);

                    foreach ($totals as $total) {
                        if (!$amount || $total->getAttribute($state) < $amount) {
                            $amount       = $total->getAttribute($state);
                            $currencyCode = $total->getAttribute('CurrencyCode');
                        }
                    }
                }

                $rateInfo['total']['hotelRate']['amount']       = floatval($amount);
                $rateInfo['total']['hotelRate']['currencyCode'] = $currencyCode;
            }

            if (empty($rateInfo['daily']['hotelRate']['amount'])) {
                $amount       = 0;
                $currencyCode = '';
                $daily        = $xpath->evaluate('./body:RoomRates/body:RoomRate[1]//body:Base', $room);

                foreach ($daily as $day) {
                    if (!$amount || $day->getAttribute($state) < $amount) {
                        $amount       = $day->getAttribute($state);
                        $currencyCode = $day->getAttribute('CurrencyCode');
                    }
                }

                $rateInfo['daily']['hotelRate']['amount']       = floatval($amount);
                $rateInfo['daily']['hotelRate']['currencyCode'] = $currencyCode;
            }
        }
        return $rateInfo;
    }

    /**
     * This method returns the breakfast information of a certain DOMNode <RoomStay/> from our API response.
     *
     * @param DOMXPath $xpath   The DOMXPath instance of our API response.
     * @param DOMNode $room The DOMNode <RoomStay/>
     * @return Mixed    The list of breakfast information or NULL if room don't include breakfast.
     */
    private function getRoomStayBreakfastInformation($xpath, $room)
    {
        $result = null;

        $mealPlan = $xpath->evaluate('./body:RatePlans/body:RatePlan/body:MealsIncluded', $room);
        if ($mealPlan->length) {
            $meal   = $mealPlan->item(0);
            $result = array(
                'hasBreakfast' => (($meal->getAttribute('Breakfast') == 1) ? true : false),
                'hasMealPlanCodes' => $meal->hasAttribute('MealPlanCodes'),
                'mealPlanCodes' => $meal->getAttribute('MealPlanCodes')
            );
        }

        return $result;
    }

    /**
     * This method returns the DOMNodeList <RoomStay/> for a certain RPH from our API response.
     *
     * @param DOMXPath $xpath   The DOMXPath instance of our API response
     * @param String $rph       The RPH.
     * @return DOMNodeList
     */
    private function getRoomStayByRPH($xpath, $rph)
    {
        return $xpath->evaluate('//body:RoomStay[@RPH="'.$rph.'"]');
    }

    /**
     * This check if certain DOMNode <RoomStay/> from our API response is cancelable.
     *
     * @param DOMXPath $xpath   The DOMXPath instance of our API response.
     * @param DOMNode $room The DOMNode <RoomStay/>
     * @param Mixed $penalties    If filled, it will be initialized with DOMNodeList of RatePlans.RatePlan.CancelPenalties from our API response (Optional; default=null).
     * @return Boolean  true if room is cancelable; otherwise false
     */
    private function isRoomCancelable($xpath, $room, &$penalties = null)
    {
        $cancelable = false;

        $cancelPenalties = $xpath->evaluate('./body:RatePlans/body:RatePlan/body:CancelPenalties', $room);
        if (!$cancelPenalties->length || ($cancelPenalties->item(0)->getAttribute('CancelPolicyIndicator') == 'No') || ($cancelPenalties->item(0)->getAttribute('CancelPolicyIndicator') == '0')) {
            $cancelable = true;
        } else {
            if (!is_null($penalties)) {
                $penalties     = array();
                $cancelPenalty = $xpath->evaluate('./body:CancelPenalty[@PolicyCode="Cancellation" or @PolicyCode="ConvertedCancel"]', $cancelPenalties->item(0));
                foreach ($cancelPenalty as $cancel) {
                    $penalty = array();

                    // Penalty Description
                    $specialText        = '';
                    $penaltyDescription = $cancel->getElementsByTagName('PenaltyDescription');
                    foreach ($penaltyDescription as $description) {
                        foreach ($description->getElementsByTagName('Text') as $text) {
                            $specialText .= $text->nodeValue."<br/>";
                        }
                    }
                    $penalty['description'] = $specialText;

                    // Fee
                    $amountPercent = $cancel->getElementsByTagName('AmountPercent');
                    if ($amountPercent->length) {
                        $fixedAmount        = $amountPercent->item(0)->getAttribute('Amount');
                        $currency           = $amountPercent->item(0)->getAttribute('CurrencyCode');
                        $penalty['nights']  = $amountPercent->item(0)->getAttribute('NmbrOfNights');
                        $penalty['percent'] = $amountPercent->item(0)->getAttribute('Percent');

                        if ($fixedAmount) {
                            $penalty['price'] = array('amount' => $fixedAmount, 'currencyCode' => $currency);
                        }
                    }

                    // Deadline
                    $penalty['absoluteDeadline'] = $xpath->evaluate('string(./body:Deadline/@AbsoluteDeadline)', $cancel);

                    $penalties[] = $penalty;
                }
            }
        }

        return $cancelable;
    }

    /**
     * This method retrieves booking PNR data from the Amadeus.
     *
     * @param String $requestingPage
     * @param Int $counter
     * @param String $roomStayXml               The RoomStay XML
     * @param Array $otaPaymentTypes
     * @return HotelRoom
     */
    public function processRoomBooking($requestingPage, $counter, $roomStayXml, $otaPaymentTypes)
    {
        $roomStayXpath = $this->getDBRoomStayXpath($roomStayXml);
        $room          = $roomStayXpath->evaluate('//body:RoomStay')->item(0);
        return $this->getRoomStayDetails($roomStayXpath, $room, $counter, $otaPaymentTypes, $requestingPage);
    }

    /**
     * This method checks if an offer is from GDS or from Aggregator.
     *
     * @param \DOMNode $room    The <RoomStay/> from the API.
     * @return Integer          1 if GDS; otherwise 0
     */
    private function isOfferGDS($room)
    {
        $src = $room->getAttribute('SourceOfBusiness');
        return (stripos($src, 'Two Step') !== FALSE) ? 1 : 0;
    }

    /**
     * This method returns the \DOMXPath from the roomStayXML in our DB.
     *
     * @param String $dbRoomStayXML The roomStayXML in our DB.
     * @return \DOMXPath    The \DOMXPath instance.
     */
    private function getDBRoomStayXpath($dbRoomStayXML)
    {
        $domDoc = new \DOMDocument('1.0', 'UTF-8');
        $domDoc->loadXML(sprintf('<OTA_HotelAvailRS xmlns="http://www.opentravel.org/OTA/2003/05">%s</OTA_HotelAvailRS>', $dbRoomStayXML));

        $xpath = new \DOMXPath($domDoc);
        $xpath->registerNamespace('body', 'http://www.opentravel.org/OTA/2003/05');

        return $xpath;
    }

    /**
     *  This method retrieves the RatePlans.RatePlan@PrepaidIndicator value.
     *
     * @param \DOMXPath $xpath  The instance of \DOMXpath from the API response.
     * @param \DOMNode $room    The <RoomStay/> from the API.
     * @return String           The pre-paid indicator
     */
    private function getPrepaidIndicator($xpath, $room)
    {
        return $xpath->evaluate('string(./body:RatePlans/body:RatePlan[1]/@PrepaidIndicator)', $room);
    }

    /**
     * This method retrieves the accepted room payment cards of a certain DOMNode <RoomStay/> from our API response.
     *
     * @param DOMXPath $xpath   The DOMXPath instance of API response.
     * @param DOMNode $room The DOMNode <RoomStay/>
     * @return Array    The accepted room payment cards.
     */
    private function getRoomPaymentCards($xpath, $room)
    {
        $acceptedCards = array();

        // only get the first set of credit card details among all rooms
        $cards = $xpath->evaluate('./body:RoomRates//body:PaymentCard/@CardCode', $room);
        foreach ($cards as $card) {
            $acceptedCards[] = $card->nodeValue;
        }

        if (empty($acceptedCards)) {
            // We only get data from rateplans if no data from roomrates
            $cards = $xpath->evaluate('./body:RatePlans//body:PaymentCard/@CardCode', $room);
            foreach ($cards as $card) {
                $acceptedCards[] = $card->nodeValue;
            }
        }

        return $acceptedCards;
    }

    /**
     * This method retrieves the data needed for booking.
     *
     * @param \DOMXPath $xpath  The \DOMXPath instance from our API response
     * @param \DOMNode $room    The (\DOMNode) <RoomStay/>
     * @param Integer $roomId   The room id.
     * @param String $paymentType   The payment type (e.g. deposit, guaranteed, etc).
     * @param Array $roomStayCandidate  The roomStayCandidates data.
     * @return Array            The information needed for booking.
     */
    private function getRoomStayBookableInfo($xpath, $room, $roomId, $paymentType, $roomStayCandidate)
    {
        // Data for Hotel Book
        $rph            = $xpath->evaluate('string(./@RPH)', $room);
        $offerHotelStay = $xpath->evaluate('//body:HotelStay[contains(@RoomStayRPH,"'.$rph.'")]')->item(0);
        $roomRate       = $xpath->evaluate('./body:RoomRates/body:RoomRate', $room)->item(0);

        $bookableInfo = array(
            'hotelRef' => array(
                'chainCode' => $xpath->evaluate('string(./body:BasicPropertyInfo/@ChainCode)', $offerHotelStay),
                'hotelCityCode' => $xpath->evaluate('string(./body:BasicPropertyInfo/@HotelCityCode)', $offerHotelStay),
                'hotelCode' => $xpath->evaluate('string(./body:BasicPropertyInfo/@HotelCode)', $offerHotelStay),
                'hotelCodeContext' => $xpath->evaluate('string(./body:BasicPropertyInfo/@HotelCodeContext)', $offerHotelStay)
            ),
            'roomID' => $roomId,
            'bookingCode' => $roomRate->getAttribute('BookingCode'),
            'roomTypeCode' => $roomRate->getAttribute('RoomTypeCode'),
            'ratePlanCode' => $roomRate->getAttribute('RatePlanCode'),
            'mealPlanCodes' => $xpath->evaluate('string(./body:RatePlans/body:RatePlan/body:MealsIncluded/@MealPlanCodes)', $room),
            'paymentType' => ($paymentType == 'deposit') ? '2' : (($paymentType == 'guaranteed') ? '1' : '0'), // Hotel_Sell params
            'children' => array()
        );

        // Data for Hotel Book - Child age for Hotel_Sell guestList
        $childCode = $roomStayCandidate['childCode'];
        foreach ($roomStayCandidate[$roomId]['roomStayCandidate']['guestCount'] as $guest) {
            if ($guest['ageQualifyingCode'] == $childCode) {
                $bookableInfo['children'][] = $guest['age'];
            }
        }
        return $bookableInfo;
    }

    /**
     * This method retrieves the room details from the RoomStay API response.
     *
     * @param DOMXPath $xpath               The instance of \DOMXpath of $domDoc.
     * @param DOMNode $room                 The RoomStay node.
     * @param Integer $counter
     * @param Array $otaPaymentTypes
     * @param String $requestingPage
     * @param Array $roomStayCandidate      The room stay candidate from our room criteria. (Optional)
     * @param DOMDocument $domDoc           The instance of \DOMDocument of the API XML response. (Optional)
     * @return Array                        The room details.
     */
    private function getRoomStayDetails($xpath, $room, $counter, $otaPaymentTypes, $requestingPage, $roomStayCandidate = array(), $domDoc = null)
    {
        $hotelRoomOffer = new HotelRoom();
        $hotelRoomOffer->setCounter($counter);

        $roomID = $xpath->evaluate('string(./body:RoomRates/body:RoomRate[1]/body:RoomRateDescription/@CreatorID)', $room);
        $hotelRoomOffer->setRoomId(($roomID) ? $roomID : 1);

        // Rates
        $hotelRoomOffer->setRates($this->getRoomStayRate($xpath, $room));

        // Taxes
        $taxes = $this->getRoomStayTaxInformation($xpath, $room);
        $hotelRoomOffer->setIncludedTaxAndFees($taxes);

        // Description
        $description = array();
        foreach ($xpath->evaluate('./body:RoomRates/body:RoomRate/body:RoomRateDescription/body:Text', $room) as $desc) {
            if (!empty($desc->nodeValue)) {
                $description[] = $desc->nodeValue;
            }
        }

        if (count($description) > 0) {
            $hotelRoomOffer->setDescription(implode('<br/>', $description));
        }

        // Room type e.g Deluxe, Superior
        $hotelRoomOffer->setRoomTypeConverted($xpath->evaluate('string(./body:RoomTypes/body:RoomType/@IsConverted)', $room));
        $hotelRoomOffer->setRoomType($xpath->evaluate('string(./body:RoomTypes/body:RoomType/@RoomType)', $room));
        $hotelRoomOffer->setRoomTypeCode($xpath->evaluate('string(./body:RoomRates/body:RoomRate/@RoomTypeCode)', $room));
        $hotelRoomOffer->setRoomCategory($xpath->evaluate('string(./body:RoomTypes/body:RoomType/@RoomCategory)', $room));
        $hotelRoomOffer->setBedTypeCode($xpath->evaluate('string(./body:RoomTypes/body:RoomType/@BedTypeCode)', $room));

        // Breakfast
        $breakfastInfo = $this->getRoomStayBreakfastInformation($xpath, $room);
        $hotelRoomOffer->setWithBreakfast($breakfastInfo['hasBreakfast']);
        $hotelRoomOffer->setMealPlanCodes($breakfastInfo['mealPlanCodes']);

        // Prepayment
        $paymentCode    = '';
        $holdTime       = '';
        $prepaymentMode = array();
        $payment        = $xpath->evaluate('./body:RoomRates//body:GuaranteePayment', $room);
        if ($payment->length) {
            // only get the first guarantee block
            $paymentCode = $payment->item(0)->getAttribute('PaymentCode');
            $holdTime    = $payment->item(0)->getAttribute('HoldTime');

            $amountPercent = $payment->item(0)->getElementsByTagName('AmountPercent');
            if ($amountPercent->length) {
                $fixedAmount               = $amountPercent->item(0)->getAttribute('Amount');
                $currency                  = $amountPercent->item(0)->getAttribute('CurrencyCode');
                $prepaymentMode['nights']  = $amountPercent->item(0)->getAttribute('NmbrOfNights');
                $prepaymentMode['percent'] = $amountPercent->item(0)->getAttribute('Percent');
                if ($fixedAmount) {
                    $prepaymentMode['price'] = array('amount' => $fixedAmount, 'currencyCode' => $currency);
                }
            }

            if (!in_array($requestingPage, array('offer', 'book'))) {
                // Prepayment descriptions
                $paymentDescription = $payment->item(0)->getElementsByTagName('Description');
                if ($paymentDescription->length > 0) {
                    $desc = '';
                    foreach ($paymentDescription as $description) {
                        $desc .= $description->getElementsByTagName('Text')->item(0)->nodeValue." ";
                    }
                    $hotelRoomOffer->setPrepaymentDetails($desc);
                }
            }
        } else {
            $payment = $xpath->evaluate('./body:RatePlans/body:RatePlan/body:Guarantee', $room);
            if ($payment->length) {
                $paymentCode = $payment->item(0)->getAttribute('GuaranteeCode');
                $holdTime    = $payment->item(0)->getAttribute('HoldTime');
            }
        }
        $paymentType = '';
        if ($paymentCode == $otaPaymentTypes['deposit']) {
            $paymentType = 'deposit';
        } elseif ($paymentCode == $otaPaymentTypes['guaranteed']) {
            $paymentType = 'guaranteed';
        } elseif ($holdTime) {
            $paymentType = 'onhold';
        }
        //        $hotelRoomOffer->setPrepaymentCode($paymentCode);
        $hotelRoomOffer->setPrepaymentHoldTime($holdTime);
        $hotelRoomOffer->setPrepaymentType($paymentType);
        $hotelRoomOffer->setPrepaymentValueMode($prepaymentMode);

        // Cancellation
        $penalties = array();
        $hotelRoomOffer->setCancellable($this->isRoomCancelable($xpath, $room, $penalties));
        $hotelRoomOffer->setCancellationPenalties($penalties);

        if (in_array($requestingPage, array('offer', 'book'))) {
            // No of rooms - max dropdown count per room
            $hotelRoomOffer->setMaxRoomCount($xpath->evaluate('string(./body:RoomRates/body:RoomRate[1]/@NumberOfUnits)', $room));

            // Data for Hotel Book
            $hotelRoomOffer->setBookableInfo($this->getRoomStayBookableInfo($xpath, $room, $hotelRoomOffer->getRoomId(), $hotelRoomOffer->getPrepaymentType(), $roomStayCandidate));
            $hotelRoomOffer->setPrepaid($this->getPrepaidIndicator($xpath, $room));
        }
        if ($requestingPage == 'book') {
            $hotelRoomOffer->setRoomOfferXml($domDoc->saveXML($room));
        }

        return $hotelRoomOffer;
    }

    /**
     * This method retrieves the room tax information from the RoomStay API response.
     *
     * @param DOMXPath $xpath   The instance of \DOMXpath of $domDoc.
     * @param DOMNode $room The RoomStay node.
     * @return Array    The room tax information.
     */
    private function getRoomStayTaxInformation($xpath, $room)
    {
        $taxes          = array();
        $roomOfferTaxes = $xpath->evaluate('./body:RoomRates/body:RoomRate[1]/body:Total/body:Taxes/body:Tax', $room);
        foreach ($roomOfferTaxes as $tax) {
            $taxInfo                 = array();
            $taxInfo['code']         = $tax->getAttribute('Code');
            $taxInfo['amount']       = $tax->getAttribute('Amount');
            $taxInfo['percent']      = $tax->getAttribute('Percent');
            $taxInfo['currencyCode'] = $tax->getAttribute('CurrencyCode');

            $taxInfo['desc'] = '';
            $texts           = $xpath->evaluate('./TaxDescription/Text', $tax);
            if ($texts->length) {
                $taxInfo['desc'] = '';
                foreach ($texts as $text) {
                    $taxInfo['desc'] .= $text->nodeValue.' ';
                }
            }
            $taxInfo['type'] = $tax->getAttribute('Type');
            $taxes[]         = $taxInfo;
        }

        return $taxes;
    }

    //*****************************************************************************************
    // Booking Functions
    /**
     * This method retrieves control number for whole reservation and per room confirmation for GDS booking
     *
     * @param Array $results            The results from API
     * @param String $controlNumber     This is filled-in with the control number parsed from API.
     * @param String $reservationKey    This is filled-in with the reservation key parsed from API.
     */
    public function getPnrControlNumber($results, &$controlNumber, &$reservationKey)
    {
        $xpath = $this->getXpath($results['lastResponse']);

        $controlNumber = $xpath->evaluate('string(//soap:Body/body:PNR_Reply/body:pnrHeader/body:reservationInfo/body:reservation/body:controlNumber)');

        $reservationKey = array();
        $roomInfos      = $xpath->evaluate('//soap:Body/body:PNR_Reply/body:originDestinationDetails/body:itineraryInfo');
        foreach ($roomInfos as $info) {
            $reservationKey[] = $xpath->evaluate('string(./body:hotelReservationInfo/body:cancelOrConfirmNbr/body:reservation/body:controlNumber)', $info);
        }
    }

    /**
     * This method retrieves sell response
     *
     * @param string $xmlResponse   The XML response
     * @return Array containing control numbers of each room
     */
    public function getSellResponse($xmlResponse, $updatePNR)
    {
        $domDoc = new \DOMDocument('1.0', 'UTF-8');
        $domDoc->loadXML($xmlResponse);

        $xpath = new \DOMXPath($domDoc);
        $xpath->registerNamespace('soap', "http://schemas.xmlsoap.org/soap/envelope/");
        $xpath->registerNamespace('body', 'http://xml.amadeus.com/HBKRCR_15_4_1A');

        $qty    = $xpath->evaluate('string(//soap:Body/body:Hotel_SellReply/body:bookingTypeIndicator/body:numberOfRooms/body:quantity)');
        $status = $xpath->evaluate('string(//soap:Body/body:Hotel_SellReply/body:bookingTypeIndicator/body:numberOfRooms/body:statusCode)');

        $results = array('success' => false);
        if (($qty > 0) && (($status == 'HK') || $status == '')) {
            $results['success'] = true;
            if (!$updatePNR) {
                $this->getSellControlNumbers($xpath, $results['controlNumber'], $results['reservationKey']);
            }
        }
        return $results;
    }

    /**
     * This method retrieves the control number of each room confirmation for Aggregator booking on the Hotel_Sell API response.
     *
     * @param \DOMXPath $xpath          The \DOMXPath of Hotel_Sell API response.
     * @param String $controlNumber     This is filled-in with the control number parsed from API.
     * @param String $reservationKey    This is filled-in with the reservation key parsed from API.
     * @return Array containing control numbers of each room
     */
    private function getSellControlNumbers($xpath, &$controlNumber, &$reservationKey)
    {
        $controlNumber = $xpath->evaluate('string(//soap:Body/body:Hotel_SellReply/body:roomStayData/body:pnrInfo/body:reservationControlInfoPNR/body:reservation/body:controlNumber)');

        $reservationKey = array();
        $roomInfos      = $xpath->evaluate('//soap:Body/body:Hotel_SellReply/body:roomStayData');
        foreach ($roomInfos as $info) {
            $reservationKey[] = $xpath->evaluate('string(./body:globalBookingInfo/body:bookingInfo/body:reservation/body:controlNumber)', $info);
        }
    }

    //*****************************************************************************************
    // Booking Information Functions
    /**
     * This method retrieves the date.
     *
     * @param \DOMXpath $xpath              The \DOMXpath instance from API response.
     * @param \DomDone $dateTimeInfoParam   The node where date is parse.
     * @return String   The date.
     */
    public function getDate($xpath, $dateTimeInfoParam)
    {
        $hour    = $xpath->evaluate('string(./body:hour)', $dateTimeInfoParam);
        $minutes = $xpath->evaluate('string(./body:minutes)', $dateTimeInfoParam);

        $toreturn             = array('dateTime' => sprintf("%s/%s/%s", $xpath->evaluate('string(./body:month)', $dateTimeInfoParam), $xpath->evaluate('string(./body:day)', $dateTimeInfoParam), $xpath->evaluate('string(./body:year)', $dateTimeInfoParam)),
            'timeMode' => '');
        $toreturn['dateTime'] .= sprintf(" %s:%s:00", ($hour) ? $hour : '00', ($minutes) ? $minutes : '00');

        return $toreturn['dateTime'];
    }

    /**
     * This method gives the readable itinerary status base on the status code from API response.
     *
     * @param type $status  The status code from the API response.
     * @return String       The itinerary status.
     */
    public function getItineraryStatus($status)
    {
        switch ($status) {
            case "HK": return "reserved";
            case "HX": return "canceled";
            case "B": return "completed";
//	    case "NN": return "onhold";

            default: return $status;
        }
    }

    /**
     * This method retrieves data from the XML response
     *
     * @param type $xmlResponse
     * @param type $savedRooms
     * @param type $otaPaymentTypes
     * @param type $getCompleteInfoFromAPI
     * @param type $params
     * @return Array $hotelRooms            Array of HotelRooms
     */
    public function processBookingRecordResponse($xmlResponse, $savedRooms, $otaPaymentTypes, $getCompleteInfoFromAPI = false, &$params = null)
    {
        $hotelRooms = array();

        $domDoc = null;
        $xpath  = $this->getXpath($xmlResponse, $domDoc);

        $counter = 1;
        foreach ($savedRooms as $roomNumber => $roomReservation) {

            $reservationKey = $roomReservation['reservationKey'];

            $apiHotelRoom = $this->processRoomBooking('booking_details', $counter, $roomReservation['roomStay'], $otaPaymentTypes);

            // Itinerary
            $xpath_string      = "//body:originDestinationDetails/body:itineraryInfo[body:hotelReservationInfo/body:cancelOrConfirmNbr/body:reservation/body:controlNumber='{$reservationKey}']";
            $itineraryInfoNode = $xpath->query($xpath_string);
            if ($itineraryInfoNode->length >= 1) {
                if ($itineraryInfoNode->length == 1) {
                    $itineraryInfo = $itineraryInfoNode->item(0);
                } else {
                    $itineraryInfo = $itineraryInfoNode->item($roomNumber);
                }
                $apiHotelRoom->setFrom($this->getDate($xpath, $xpath->evaluate('./body:hotelReservationInfo/body:requestedDates/body:beginDateTime', $itineraryInfo)->item(0)));
                $apiHotelRoom->setTo($this->getDate($xpath, $xpath->evaluate('./body:hotelReservationInfo/body:requestedDates/body:endDateTime', $itineraryInfo)->item(0)));
                $status = $xpath->evaluate('string(./body:relatedProduct[last()]/body:status)', $itineraryInfo);

                if ($getCompleteInfoFromAPI) {
                    $params['pnrType']   = $xpath->evaluate('string(./body:elementManagementItinerary/body:reference/body:qualifier)', $itineraryInfo);
                    $params['pnrTattoo'] = $xpath->evaluate('string(./body:elementManagementItinerary/body:reference/body:number)', $itineraryInfo);

                    $completeInfoFromAPI = $this->getAPICompleteReservationDetails($params);
                    $params['session']   = $completeInfoFromAPI['session'];

                    if (isset($completeInfoFromAPI['roomReservationDetails'])) {
                        $completeInfoFromAPI = $completeInfoFromAPI['roomReservationDetails'];

                        if (isset($completeInfoFromAPI['maxPersons'])) {
                            $apiHotelRoom->setMaxRoomCount($completeInfoFromAPI['maxPersons']);
                        }
                    }
                }
            } else {
                $status = 'HX'; // Room is canceled
            }
            $apiHotelRoom->setStatus($this->getItineraryStatus($status));

            // Cancellation References
            $apiHotelRoom->setCancellationReference($this->getRoomCancellationReference($xpath, $reservationKey));

            $hotelRooms[$roomNumber] = $apiHotelRoom;

            $counter++;
        }

        // end session
        if (!empty($params['session']) && $params['stateful']) {
            $this->handler->securitySignOut($params);
        }
        return $hotelRooms;
    }

    /**
     * This retrieves the reservation details from API.
     *
     * @param Array $params
     * @return Array    The reservation details.
     */
    private function getAPICompleteReservationDetails($params)
    {
        $results = $this->handler->hotelCompleteReservationDetails($params);

        $domDoc = new \DOMDocument('1.0', 'UTF-8');
        $domDoc->loadXML($results['lastResponse']);
        $xpath  = new \DOMXPath($domDoc);
        $xpath->registerNamespace('soap', "http://schemas.xmlsoap.org/soap/envelope/");
        $xpath->registerNamespace('body', 'http://xml.amadeus.com/HCRDRR_07_1_1A');

        $toreturn = array('session' => $results['session']);

        foreach ($xpath->evaluate('//body:Hotel_CompleteReservationDetailsReply/body:hotelSalesRequCategorySection') as $salesInfo) {
            switch ($xpath->evaluate('string(./body:pricingCategory/body:itemDescriptionType)', $salesInfo)) {
                case 'INC': // Rate Inclusions / Extras
                    $ruleDetailsType = $xpath->evaluate('string(./body:rulesSection/body:rulesInformation/body:ruleDetails/body:type)', $salesInfo);
                    switch ($ruleDetailsType) {
                        case 'OCC': // Maximum occupancy
                            $toreturn['roomReservationDetails']['maxPersons'] = $xpath->evaluate('string(./body:rulesSection/body:rulesInformation/body:ruleDetails/body:quantity)', $salesInfo);
                            break;
                    }
                    break;
            }
        }

        return $toreturn;
    }

    /**
     * This method retrieves the room cancellation reference.
     *
     * @param \DOMXPath $xpath          The \DOMXPath instance from PNR_Retrieve API response.
     * @param String $reservationKey    The reservation key.
     * @return Array                    The cancellation reference (e.g the segmentIdentifier and segmentNumber).
     */
    private function getRoomCancellationReference($xpath, $reservationKey)
    {
        $results = array('segmentIdentifier' => '', 'segmentNumber' => '');

        // get segment identifier and number base on provided reservationKey
        $itineraryInfoNode = $xpath->query("//soap:Body/body:PNR_Reply/body:originDestinationDetails/body:itineraryInfo[body:hotelReservationInfo/body:cancelOrConfirmNbr/body:reservation/body:controlNumber='{$reservationKey}']");

        if ($itineraryInfoNode->length > 0) {
            $results['segmentIdentifier'] = $xpath->evaluate('string(./body:elementManagementItinerary/body:reference/body:qualifier)', $itineraryInfoNode->item(0));
            $results['segmentNumber']     = $xpath->evaluate('string(./body:elementManagementItinerary/body:reference/body:number)', $itineraryInfoNode->item(0));
        }

        return $results;
    }

    //*****************************************************************************************
    // Cancellation Functions
    /**
     * This method normalizes the PNR_Cancel response.
     *
     * @param Array $response           The AmadeusHandler::pnrCancel() response.
     * @param String $reservationKey    The reservation key.
     * @return HotelCancellation
     */
    public function processCancellationResponse($response, $reservationKey)
    {
        $result = new HotelCancellation();
        if (isset($response['error'])) {
            $result->getStatus()->setError($response['error']);
        } elseif (!empty($response['lastResponse'])) {
            $xpath = $this->getXpath($response['lastResponse']);

            // having cancellation number in PNR_Cancel is the way to know if cancellation is successful or not
            $cancellationNumber = $this->getCancellationNumber($xpath, $reservationKey);
            if (!empty($cancellationNumber)) {
                $result->setReservationStatus('Canceled');
                $result->setCancelable(false);
                $result->setCancellation($this->getPnrUpdateDate($xpath)); // cancellation date is the same pnr last modified date
                $result->setCancellationNumber($cancellationNumber);
            }

            unset($xpath);
        }

        return $result;
    }

    //*****************************************************************************************
    // PNR Functions
    /**
     * This method retrieves modification parameters from XML response
     *
     * @param String $xmlResponse
     * @param Array $params
     * @param Array $roomGuests
     * @return Array
     */
    public function getModificationParameters($xmlResponse, $params, $roomGuests)
    {
        $toreturn = array();

        $xpath = $this->getXpath($xmlResponse);

        $travellerInfos = $this->getPnrTravellerInfo($xpath);
        if ($travellerInfos->length > 0) {
            $toreturn                          = $params;
            // name change
            $toreturn['pnrReferenceQualifier'] = $this->getPnrReferenceQualifier($xpath, $travellerInfos->item(0));
            $toreturn['pnrReferenceNumber']    = $this->getPnrReferenceNumber($xpath, $travellerInfos->item(0));

            $roomGuests[]           = $this->getPNRTravellerInformation($xpath, $travellerInfos->item(0));
            $toreturn['roomGuests'] = $roomGuests;
        }

        return $toreturn;
    }

    /**
     * This method retrieves the cancellation segments from the XML response
     *
     * @param String $xmlResponse
     * @param Array $params
     * @return Array
     */
    public function getCancellationSegments($xmlResponse, $params)
    {
        $cancellationSegments = array();

        $xpath = $this->getXpath($xmlResponse);

        foreach ($params as $element) {
            extract($element);

            $segmentIdentifier = '';
            $segmentNumber     = '';

            $node = $this->getPNRDataElementsInDivFreeTextDetailType($xpath, $type);
            if ($node->length > 0) {
                $segmentIdentifier = $this->getPNRSegmentIdentifier($xpath, $node->item(0));
                $segmentNumber     = $this->getPNRSegmentNumber($xpath, $node->item(0));

                $cancellationSegments[] = array(
                    'segmentIdentifier' => $segmentIdentifier,
                    'segmentNumber' => $segmentNumber,
                );
            }
        }

        return $cancellationSegments;
    }

    /**
     * This method retrieves the cancellation number from the API response.
     *
     * @param \DOMXpath $domXpath   instance of \DOMXpath from PNR_Cancel API response.
     * @param String $reservationKey    The reservation key.
     * @return String   The cancellation number.
     */
    private function getCancellationNumber($domXpath, $reservationKey)
    {
        $result = '';

        $xpath_string      = "//soap:Body/body:PNR_Reply/body:dataElementsMaster/body:dataElementsIndiv[contains(body:miscellaneousRemarks/body:remarks/body:freetext, '{$reservationKey}')]";
        $dataElementsIndiv = $domXpath->query($xpath_string);
        if ($dataElementsIndiv->length > 0) {
            $result = $domXpath->evaluate('string(./body:miscellaneousRemarks/body:remarks/body:freetext)', $dataElementsIndiv->item(0));
        }

        return $result;
    }

    /**
     * This method retrieves the PNR update or modification date from PNR API response.
     *
     * @param \DOMXPath $domXpath   The \DOMXPath instance for PNR
     * @return String   The PNR update/modification date.
     */
    private function getPnrUpdateDate($domXpath)
    {
        $pnrLastUpdateDate = "0000-00-00 00:00:00";
        $reservationNode   = $domXpath->evaluate("//soap:Body/body:PNR_Reply/body:pnrHeader/body:reservationInfo/body:reservation");
        if ($reservationNode->length > 0) {
            $reservationDate = $domXpath->evaluate('string(./body:date)', $reservationNode->item(0));
            $reservationTime = $domXpath->evaluate('string(./body:time)', $reservationNode->item(0));

            $date              = date_create_from_format('dmy Hi', "{$reservationDate} {$reservationTime}");
            $pnrLastUpdateDate = $date->format('Y-m-d H:i:sP');
        }
        return $pnrLastUpdateDate;
    }

    /**
     * This method retrieves the \DOMNodeList of <travellerInfo/> from the PNR API response.
     *
     * @param \DOMXPath $xpath
     * @return \DOMNodeList List of <travellerInfo/> from PNR API response.
     */
    private function getPnrTravellerInfo($xpath)
    {
        return $xpath->evaluate('//soap:Body/body:PNR_Reply/body:travellerInfo');
    }

    /**
     * This method retrieves the elementManagementPassenger.reference.qualifier from the PNR API response.
     *
     * @param \DOMXpath $xpath
     * @param \DOMNode $travellerInfo
     * @return String   The PNR reference qualifier.
     */
    private function getPnrReferenceQualifier($xpath, $travellerInfo)
    {
        return $xpath->evaluate('string(./body:elementManagementPassenger/body:reference/body:qualifier)', $travellerInfo);
    }

    /**
     * This method retrieves the elementManagementPassenger.reference.number from the PNR API response.
     *
     * @param \DOMXpath $xpath
     * @param \DOMNode $travellerInfo
     * @return String   The PNR reference number.
     */
    private function getPnrReferenceNumber($xpath, $travellerInfo)
    {
        return $xpath->evaluate('string(./body:elementManagementPassenger/body:reference/body:number)', $travellerInfo);
    }

    /**
     * This method retrieves basic traveler information from PNR API response.
     *
     * @param \DOMXPath $xpath
     * @param \DOMNode $travellerInfo
     * @return Array    The traveler basic information.
     */
    private function getPNRTravellerInformation($xpath, $travellerInfo)
    {
        return array(
            'number' => $xpath->evaluate('string(./body:elementManagementPassenger/body:lineNumber)', $travellerInfo),
            'lastName' => $xpath->evaluate('string(./body:passengerData/body:travellerInformation/body:traveller/body:surname)', $travellerInfo),
            'firstName' => $xpath->evaluate('string(./body:passengerData/body:travellerInformation/body:passenger/body:firstName)', $travellerInfo),
            'title' => 'Mr'
        );
    }

    /**
     * This method retrieves the PNR <dataElementsIndiv/> segment in the PNR API response for a certain freetextDetail.type.
     *
     * @param \DOMXPath $xpath
     * @param String $type
     * @return \DOMNodeList The list of <dataElementsIndiv/>.
     */
    private function getPNRDataElementsInDivFreeTextDetailType($xpath, $type)
    {
        return $xpath->evaluate("//soap:Body/body:PNR_Reply/body:dataElementsMaster/body:dataElementsIndiv[body:otherDataFreetext/body:freetextDetail/body:type='{$type}']");
    }

    /**
     * This method retrieves the PNR segment identifier for a given (\DOMNode) <dataElementsIndiv/>.
     *
     * @param \DOMXPath $xpath
     * @param \DOMNode $dataElementsInDiv
     */
    private function getPNRSegmentIdentifier($xpath, $dataElementsInDiv)
    {
        return $xpath->evaluate('string(./body:elementManagementData/body:reference/body:qualifier)', $dataElementsInDiv);
    }

    /**
     * This method retrieves the PNR segment number for a given (\DOMNode) <dataElementsIndiv/>.
     *
     * @param \DOMXPath $xpath
     * @param \DOMNode $dataElementsInDiv
     */
    private function getPNRSegmentNumber($xpath, $dataElementsInDiv)
    {
        return $xpath->evaluate('string(./body:elementManagementData/body:reference/body:number)', $dataElementsInDiv);
    }
}
