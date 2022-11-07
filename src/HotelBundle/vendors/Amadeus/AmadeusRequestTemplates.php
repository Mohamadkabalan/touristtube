<?php

namespace HotelBundle\vendors\Amadeus;

class AmadeusRequestTemplates
{

    public function otaHotelAvailXML($params)
    {
        extract($params);

        $availRatesOnly         = (isset($availRatesOnly)) ? 'AvailRatesOnly="'.$availRatesOnly.'"' : '';
        $exactMatchOnly         = (isset($exactMatchOnly)) ? 'ExactMatchOnly="'.$exactMatchOnly.'"' : '';
        $rateDetailsInd         = (isset($rateDetailsInd)) ? 'RateDetailsInd="'.$rateDetailsInd.'"' : '';
        $searchCacheLevel       = (isset($searchCacheLevel)) ? 'SearchCacheLevel="'.$searchCacheLevel.'"' : '';
        $infoSource             = (isset($infoSource)) ? 'InfoSource="'.$infoSource.'"' : '';
        $availableOnlyIndicator = (isset($availableOnlyIndicator)) ? 'AvailableOnlyIndicator="'.$availableOnlyIndicator.'"' : '';
        $bestOnlyIndicator      = (isset($bestOnlyIndicator)) ? 'BestOnlyIndicator="'.$bestOnlyIndicator.'"' : '';
        $moreDataEchoToken      = (isset($moreDataEchoToken)) ? 'MoreDataEchoToken="'.$moreDataEchoToken.'"' : '';
        $maxResponses           = (isset($maxResponses)) ? 'MaxResponses="'.$maxResponses.'"' : '';
        $pricingMethod          = (isset($pricingMethod)) ? 'PricingMethod="'.$pricingMethod.'"' : '';

        $availRequestSegment = '';
        foreach ($segments as $segment) {
            if (isset($segment['hotelRefs'])) {
                $hotelRefs = array('hotelRefs' => $segment['hotelRefs']);
                unset($segment['hotelRefs']);

                $criteria = array($segment, $hotelRefs);
            } else {
                $criteria = array($segment);
            }

            $criterion = '';
            foreach ($criteria as $criterionParams) {
                $eachCriterion = $this->getCriterion($criterionParams);

                $criterion .= <<<EOR
                <Criterion ExactMatch="true">
                    $eachCriterion
                </Criterion>
EOR;
            }
            $availRequestSegment .= <<<EOR
        <AvailRequestSegment $infoSource $moreDataEchoToken>
            <HotelSearchCriteria $availableOnlyIndicator $bestOnlyIndicator >
                $criterion
            </HotelSearchCriteria>
        </AvailRequestSegment>
EOR;
        }

        $language = $this->getDefaultAPILanguage();
        $currency = $this->getDefaultAPICurrency();

        $body = <<<EOR
<OTA_HotelAvailRQ xmlns="http://www.opentravel.org/OTA/2003/05" EchoToken="$echoToken" Version="4.000" PrimaryLangID="$language" RequestedCurrency="$currency" SummaryOnly="$summaryOnly" RateRangeOnly="$rateRangeOnly" $searchCacheLevel $availRatesOnly $exactMatchOnly $rateDetailsInd $maxResponses $pricingMethod>
    <AvailRequestSegments>
        $availRequestSegment
    </AvailRequestSegments>
</OTA_HotelAvailRQ>
EOR;

        return $body;
    }

    public function getCriterion($segment)
    {
        $searchBy      = '';
        $stayDateRange = '';
        $ratePlan      = '';
        $roomStay      = '';

        $distance = (isset($segment['distance'])) ? $segment['distance'] : 300;
        // Distance: max 300, DistanceMeasure: sort by distance, UnitOfMeasureCode: miles = 1, km = 2
        $radius   = '<Radius Distance="'.$distance.'" DistanceMeasure="DIS" UnitOfMeasureCode="2" />';

        if (isset($segment['hotelRefs']) && is_array($segment['hotelRefs'])) {
            $searchBy = '';
            foreach ($segment['hotelRefs'] as $hotelRefParam) {
                $hotelRef = '';
                foreach ($hotelRefParam as $key => $value) {
                    $hotelRef .= ucfirst($key).'="'.$value.'" ';
                }
                $searchBy .= "<HotelRef $hotelRef/>";
            }
        } elseif (isset($segment['hotelRef']) && is_array($segment['hotelRef'])) {
            $hotelRef = '';
            foreach ($segment['hotelRef'] as $key => $value) {
                $hotelRef .= ucfirst($key).'="'.$value.'" ';
            }
            $searchBy = "<HotelRef $hotelRef/>";
        } elseif (isset($segment['hotelName']) || isset($segment['hotelCityCode']) || isset($segment['hotelCode']) || isset($segment['chainCode'])) {
            // By Hotel Name, p29
            $hotelName = (isset($segment['hotelName'])) ? 'HotelName="'.$segment['hotelName'].'"' : '';

            // for MultiSource, location search is prioritized over chain code, city code, property code
            // By Area, p18, @ExtendedCitySearchIndicator - p30, p40
            $hotelCityCode = (isset($segment['hotelCityCode'])) ? 'HotelCityCode="'.$segment['hotelCityCode'].'"' : '';

            // By Chain, p40
            $hotelCode = (isset($segment['hotelCode'])) ? 'HotelCode="'.$segment['hotelCode'].'"' : '';

            // By Chain, p22, p40
            // needs to have a supporting location criteria,
            $chainCode = (isset($segment['chainCode'])) ? 'ChainCode="'.$segment['chainCode'].'"' : '';

            // For Enhanced Pricing
            $hotelCodeContext = (isset($segment['hotelCodeContext'])) ? 'HotelCodeContext="'.$segment['hotelCodeContext'].'"' : '';

            $searchBy = "<HotelRef $chainCode $hotelCityCode $hotelCode $hotelName $hotelCodeContext/>";
        } elseif (isset($segment['latitude']) && isset($segment['longitude'])) {
            // By GeoCodes, p38
            list($whole, $decimal) = explode('.', $segment['latitude']);
            $whole    = (abs($whole) != 0) ? $whole : (($segment['latitude'] < 0) ? '-' : '');
            $latitude = $whole.str_pad(substr($decimal, 0, 5), 5, '0');

            list($whole, $decimal) = explode('.', $segment['longitude']);
            $whole     = (abs($whole) != 0) ? $whole : (($segment['longitude'] < 0) ? '-' : '');
            $longitude = $whole.str_pad(substr($decimal, 0, 5), 5, '0');

            $searchBy = '<Position Latitude="'.$latitude.'" Longitude="'.$longitude.'"/>'.$radius;
        } elseif (isset($segment['address'])) {
            // By Address, p16
            $address = '';
            foreach ($segment['address'] as $key => $val) {
                $address .= "<".ucfirst($key).">$val</".ucfirst($key).">";
            }
            $searchBy = "<Address>$address/Address>";
        } elseif (isset($segment['stateProv']) && isset($segment['countryCode'])) {
            // By POR, p38
            $searchBy = '<RefPoint StateProv="'.$segment['stateProv'].'" CountryCode="'.$segment['countryCode'].'" />'.$radius;
        } elseif (isset($segment['locationCode']) && isset($segment['codeContext'])) {
            // By POR, p38
            $searchBy = '<CodeRef LocationCode="'.$segment['locationCode'].'" CodeContext="'.$segment['codeContext'].'" />'.$radius;
        }

        if (isset($segment['start']) && isset($segment['end'])) {
            // By Date, p13
            $stayDateRange = '<StayDateRange Start="'.$segment['start'].'" End="'.$segment['end'].'"/>';
        }

        if (isset($segment['ratePlanCandidate'])) {
            // For Hotel_EnhancedPricing
            $mealsIncluded = (isset($segment['ratePlanCandidate']['mealPlanCodes']) && $segment['ratePlanCandidate']['mealPlanCodes']) ? '<MealsIncluded MealPlanCodes="'.$segment['ratePlanCandidate']['mealPlanCodes'].'" />'
                    : '';
            $ratePlan      = '<RatePlanCandidates>
                <RatePlanCandidate RatePlanCode ="'.$segment['ratePlanCandidate']['ratePlanCode'].'">
                    '.$mealsIncluded.'
                </RatePlanCandidate>
            </RatePlanCandidates>';
        }

        if (isset($segment['roomStayCandidate'])) {
            $roomTypeCode = (isset($segment['roomStayCandidate']['roomTypeCode'])) ? 'RoomTypeCode="'.$segment['roomStayCandidate']['roomTypeCode'].'"' : '';
            $bookingCode  = (isset($segment['roomStayCandidate']['bookingCode'])) ? 'BookingCode="'.$segment['roomStayCandidate']['bookingCode'].'"' : '';

            $guestCount = '';
            // By Occupancy, p36
            if (isset($segment['roomStayCandidate']['guestCount'])) {
                foreach ($segment['roomStayCandidate']['guestCount'] as $guest) {
                    unset($age);
                    $age        = (isset($guest['age'])) ? 'Age="'.$guest['age'].'"' : '';
                    $guestCount .= '<GuestCount AgeQualifyingCode="'.$guest['ageQualifyingCode'].'" Count="'.$guest['count'].'" '.$age.'/>';
                }
            } else {
                $guestCount .= '<GuestCount />';
            }

            $roomStay = '<RoomStayCandidates>
                        <RoomStayCandidate RoomID="'.$segment['roomStayCandidate']['roomID'].'" Quantity="'.$segment['roomStayCandidate']['quantity'].'" '.$roomTypeCode.' '.$bookingCode.'>
                            <GuestCounts IsPerRoom="true">
                                '.$guestCount.'
                            </GuestCounts>
                        </RoomStayCandidate>
                    </RoomStayCandidates>';
        }

        // Add here the other info once we support it, make sure to order them appropriately
        $criterion = <<<EOR
$searchBy
                    $stayDateRange
                    $ratePlan
                    $roomStay
EOR;

        return $criterion;
    }

    public function pnrAddMultiElementsXML($params, $create, $commit)
    {
        extract($params);
        $number             = 1;
        $dataQualifier      = 'OT';
        $travellerInfo      = '';
        $ordererContactInfo = '';
        $ticket             = '';
        $remarkInfo         = '';

        if (!$create) {
            $pnrActions = <<<EOR
            <pnrActions>
                <optionCode>11</optionCode>
                <optionCode>30</optionCode>
             </pnrActions>
EOR;
        } else {
            $pnrActions = <<<EOR
            <pnrActions>
                <optionCode>0</optionCode>
             </pnrActions>
EOR;
            if ($groupSell && !$ordererIsGuest) {
                array_unshift($roomGuests, $orderer);
            }

            foreach ($roomGuests as $guest) {
                extract($guest);

                $travellerInfo .= <<<EOR
                <travellerInfo>
                    <elementManagementPassenger>
                        <reference>
                            <qualifier>PR</qualifier>
                            <number>$number</number>
                        </reference>
                        <segmentName>NM</segmentName>
                    </elementManagementPassenger>
                    <passengerData>
                        <travellerInformation>
                            <traveller>
                                <surname>$lastName</surname>
                                <quantity>1</quantity>
                            </traveller>
                            <passenger>
                                <firstName>$firstName</firstName>
                                <type>ADT</type>
                            </passenger>
                        </travellerInformation>
                    </passengerData>
                </travellerInfo>
EOR;

                if (isset($children) && !empty($children)) {
                    $number++;
                    foreach ($children as $childNumber => $child) {
                        extract($child);

                        $travellerInfo .= <<<EOR
                        <travellerInfo>
                            <elementManagementPassenger>
                                <reference>
                                    <qualifier>PR</qualifier>
                                    <number>$number</number>
                                </reference>
                                <segmentName>NM</segmentName>
                            </elementManagementPassenger>
                            <passengerData>
                                <travellerInformation>
                                    <traveller>
                                        <surname>$childLastName</surname>
                                        <quantity>1</quantity>
                                    </traveller>
                                    <passenger>
                                        <firstName>$childFirstName</firstName>
                                        <type>CHD</type>
                                    </passenger>
                                </travellerInformation>
                            </passengerData>
                        </travellerInfo>
EOR;
                    }
                    unset($children);
                }

                $number++;
            }

            $number = 1;
            foreach ($ordererContact as $contact) {
                extract($contact);

                $referenceForDataElement = '';
                if ($type == 'P02') {
                    $referenceForDataElement = <<<EOR
                    <referenceForDataElement>
                        <reference>
                            <qualifier>PR</qualifier>
                            <number>1</number>
                        </reference>
                    </referenceForDataElement>
EOR;
                }

                $ordererContactInfo .= <<<EOR
                <dataElementsIndiv>
                    <elementManagementData>
                        <reference>
                            <qualifier>$dataQualifier</qualifier>
                            <number>$number</number>
                        </reference>
                        <segmentName>AP</segmentName>
                    </elementManagementData>
                    <freetextData>
                        <freetextDetail>
                            <subjectQualifier>3</subjectQualifier>
                            <type>$type</type>
                        </freetextDetail>
                        <longFreetext>$value</longFreetext>
                    </freetextData>
                    $referenceForDataElement
                </dataElementsIndiv>
EOR;
                // commented all contact should have a reference.number of 1 as per last email for booking with children
                //$number++;
            }
        }

        if ($commit) {
            $number++;
            $ticket = <<<EOR
            <dataElementsIndiv>
                <elementManagementData>
                    <reference>
                        <qualifier>OT</qualifier>
                        <number>$number</number>
                    </reference>
                    <segmentName>TK</segmentName>
                </elementManagementData>
                <ticketElement>
                    <passengerType>PAX</passengerType>
                    <ticket>
                        <indicator>OK</indicator>
                    </ticket>
                </ticketElement>
            </dataElementsIndiv>
EOR;
        }

        $number++;
        $receiveFrom = <<< EOR
        <dataElementsIndiv>
            <elementManagementData>
                <reference>
                    <qualifier>$dataQualifier</qualifier>
                    <number>$number</number>
                </reference>
                <segmentName>RF</segmentName>
            </elementManagementData>
            <freetextData>
                <freetextDetail>
                    <subjectQualifier>3</subjectQualifier>
                    <type>P22</type>
                </freetextDetail>
                <longFreetext>TouristTube</longFreetext>
            </freetextData>
        </dataElementsIndiv>
EOR;

        if (isset($remarks)) {
            $number++;
            foreach ($remarks as $remark) {
                if (!empty($remark)) {
                    $remarkInfo .= <<<EOR
                    <dataElementsIndiv>
                        <elementManagementData>
                            <reference>
                                <qualifier>$dataQualifier</qualifier>
                                <number>$number</number>
                            </reference>
                            <segmentName>RM</segmentName>
                        </elementManagementData>
                        <miscellaneousRemark>
                            <remarks>
                                <type>RM</type>
                                <freetext>$remark</freetext>
                            </remarks>
                        </miscellaneousRemark>
                    </dataElementsIndiv>
EOR;
                }
            }
        }

        $body = <<<EOR
        <PNR_AddMultiElements xmlns="http://xml.amadeus.com/PNRADD_15_1_1A">
            $pnrActions
            $travellerInfo
            <dataElementsMaster>
                <marker1 />
                $ordererContactInfo
                $ticket
                $receiveFrom
                $remarkInfo
            </dataElementsMaster>
        </PNR_AddMultiElements>
EOR;

        return $body;
    }

    public function hotelSellXML($params)
    {
        $groupSell             = false;
        $textOptions           = '';
        $groupIndicator        = '';
        $roomStayData          = '';
        $representativeParties = '';
        $roomLists             = '';
        extract($params);

        //extract hotelcode for hotel sell
        $hotelCode = str_replace("{$chainCode}{$hotelCityCode}", '', $hotelCode);

        $number = $holderNumber;
        if ($groupSell && !$ordererIsGuest) {
            extract($orderer);

            $representativeParties .= <<<EOR
            <representativeParties>
                <occupantList>
                    <passengerReference>
                        <type>$repType</type>
                        <value>$number</value>
                    </passengerReference>
                </occupantList>
            </representativeParties>
EOR;
            $number++;
        }

        foreach ($roomGuests as $key => $room) {
            extract($room);

            $guaranteeOrDeposit = '';
            if (!empty($paymentType)) {
                $groupCreditCardInfo = '';
                if ($formOfPaymentCode === '1') {
                    $groupCreditCardInfo = <<<EOR
                    <groupCreditCardInfo>
                        <creditCardInfo>
                            <ccInfo>
                                <vendorCode>$vendorCode</vendorCode>
                                <cardNumber>$cardNumber</cardNumber>
                                <securityId>$securityId</securityId>
                                <expiryDate>$expiryDate</expiryDate>
                                <ccHolderName>$ccHolderName</ccHolderName>
                            </ccInfo>
                        </creditCardInfo>
                    </groupCreditCardInfo>
EOR;
                }

                $guaranteeOrDeposit = <<<EOR
                <guaranteeOrDeposit>
                    <paymentInfo>
                        <paymentDetails>
                            <formOfPaymentCode>$formOfPaymentCode</formOfPaymentCode>
                            <paymentType>$paymentType</paymentType>
                            <serviceToPay>3</serviceToPay>
                        </paymentDetails>
                    </paymentInfo>
                    $groupCreditCardInfo
                </guaranteeOrDeposit>
EOR;
            }

            $representativeParties .= <<<EOR
            <representativeParties>
                <occupantList>
                    <passengerReference>
                        <type>$repType</type>
                        <value>$number</value>
                    </passengerReference>
                </occupantList>
            </representativeParties>
EOR;

            $guestList = <<<EOR
            <guestList>
                <occupantList>
                    <passengerReference>
                        <type>$guestType</type>
                        <value>$number</value>
                    </passengerReference>
                </occupantList>
            </guestList>
EOR;
            if (isset($children) && !empty($children)) {
                foreach ($children as $childNumber => $child) {
                    extract($child);

                    $number++;
                    $representativeParties .= <<<EOR
                    <representativeParties>
                        <occupantList>
                            <passengerReference>
                                <type>BOP</type>
                                <value>$number</value>
                            </passengerReference>
                        </occupantList>
                    </representativeParties>
EOR;

                    $guestList .= <<<EOR
                    <guestList>
                        <occupantList/>
                        <age>
                            <quantityDetails>
                                <qualifier>AGE</qualifier>
                                <value>$childAge</value>
                            </quantityDetails>
                        </age>
                    </guestList>
EOR;
                }
            }

            $roomList = <<<EOR
            <roomList>
                <markerRoomstayQuery/>
                <roomRateDetails>
                    <marker/>
                    <hotelProductReference>
                        <referenceDetails>
                            <type>BC</type>
                            <value>$bookingCode</value>
                        </referenceDetails>
                    </hotelProductReference>
                    <markerOfExtra/>
                </roomRateDetails>
                $guaranteeOrDeposit
                $guestList
            </roomList>
EOR;

            if ($groupSell) {
                $roomLists .= $roomList;
            } else {
                $roomStayData .= <<<EOR
                <roomStayData>
                    <markerRoomStayData/>
                    <globalBookingInfo>
                        <markerGlobalBookingInfo>
                            <hotelReference>
                                <chainCode>$chainCode</chainCode>
                                <cityCode>$hotelCityCode</cityCode>
                                <hotelCode>$hotelCode</hotelCode>
                            </hotelReference>
                        </markerGlobalBookingInfo>
                        $textOptions
                        $representativeParties
                    </globalBookingInfo>
                    $roomList
                </roomStayData>
EOR;
            }

            $number++;
        }

        if ($groupSell) {
            $roomStayData .= <<<EOR
            <roomStayData>
                <markerRoomStayData/>
                <globalBookingInfo>
                    <markerGlobalBookingInfo>
                        <hotelReference>
                            <chainCode>$chainCode</chainCode>
                            <cityCode>$hotelCityCode</cityCode>
                            <hotelCode>$hotelCode</hotelCode>
                        </hotelReference>
                    </markerGlobalBookingInfo>
                    $textOptions
                    $representativeParties
                </globalBookingInfo>
                $roomLists
            </roomStayData>
EOR;
        }

        if (count($roomGuests) > 1) {
            $groupIndicator = <<<EOR
            <groupIndicator>
                <statusDetails>
                    <indicator>GR</indicator>
                    <action>1</action>
                </statusDetails>
            </groupIndicator>
EOR;
        }

        /**
         * change:
         * <reference>
         *      <type>OT</type>
         *      <value>$holderNumber</value>
         * </reference>
         * to:
         * <reference>
         *      <type>OT</type>
         *      <value>1</value>
         * </reference>
         */
        $body = <<<EOR
        <Hotel_Sell xmlns="http://xml.amadeus.com/HBKRCQ_15_4_1A">
            $groupIndicator
            <travelAgentRef>
                <status>APE</status>
                <reference>
                    <type>OT</type>
                    <value>1</value>
                </reference>
            </travelAgentRef>
            $roomStayData
        </Hotel_Sell>
EOR;

        return $body;
    }

    public function pnrRetrieveByNameXML($params)
    {
        extract($params);

        $body = <<<EOR
<PNR_Retrieve xmlns="http://xml.amadeus.com/PNRRET_17_1_1A">
    <retrievalFacts>
	<retrieve>
	    <type>3</type>
	    <option1>A</option1>
	</retrieve>
	<personalFacts>
	    <travellerInformation>
		<traveller>
		    <surname>$lastName</surname>
		</traveller>
	    </travellerInformation>
	</personalFacts>
    </retrievalFacts>
</PNR_Retrieve>
EOR;
        return $body;
    }

    public function pnrRetrieveXML($params)
    {
        extract($params);

        $body = <<<EOR
<PNR_Retrieve xmlns="http://xml.amadeus.com/PNRRET_15_1_1A">
    <retrievalFacts>
	<retrieve>
	    <type>2</type>
	</retrieve>
	<reservationOrProfileIdentifier>
	    <reservation>
		<controlNumber>$controlNumber</controlNumber>
	    </reservation>
	</reservationOrProfileIdentifier>
    </retrievalFacts>
</PNR_Retrieve>
EOR;
        return $body;
    }

    public function hotelCompleteReservationDetailsXML($params)
    {
        extract($params);

        $request = <<<EOR
<Hotel_CompleteReservationDetails xmlns="http://xml.amadeus.com/HCRDRQ_07_1_1A">
  <retrievalKeyGroup>
	<retrievalKey>
	  <reservation>
		<controlNumber>$controlNumber</controlNumber>
		<controlType>P</controlType>
	  </reservation>
	</retrievalKey>
	<tattooID>
	  <referenceDetails>
		<type>$pnrType</type>
		<value>$pnrTattoo</value>
	  </referenceDetails>
	</tattooID>
  </retrievalKeyGroup>
</Hotel_CompleteReservationDetails>
EOR;
        return $request;
    }

    public function hotelDescriptiveInfoXML($params)
    {
        extract($params);
        $hotels = '';

        if ($minimalData) {
            $returnData = <<<EOR
	<HotelInfo SendData = "true" />
	<AreaInfo SendRefPoints = "true" />
	<MultimediaObjects SendData = "true" />
EOR;
        } else {
            $returnData = <<<EOR
	<HotelInfo SendData = "true" />
        <FacilityInfo SendGuestRooms = "true" />
	<Policies SendPolicies="true" />
	<AreaInfo SendRefPoints = "true" SendRecreations = "true" />
	<ContactInfo SendData = "true" />
	<MultimediaObjects SendData = "true" />
EOR;
        }

        foreach ($hotelCodes as $hotelCode) {
            $hotels .= <<<EOR
      <HotelDescriptiveInfo HotelCode="$hotelCode">
         $returnData
      </HotelDescriptiveInfo>
EOR;
        }

        $request = <<< EOR
<OTA_HotelDescriptiveInfoRQ EchoToken="withParsing" Version="6.001" PrimaryLangID="en">
   <HotelDescriptiveInfos>
	$hotels
   </HotelDescriptiveInfos>
</OTA_HotelDescriptiveInfoRQ>
EOR;
        return $request;
    }

    public function pnrCancelXML($params)
    {
        $entryType = 'I';
        $element   = '';

        $optionCode        = 11;
        $segmentIdentifier = '';
        $segmentNumber     = '';

        extract($params);

        if (!isset($cancellationSegments) && ($segmentIdentifier && $segmentNumber)) {
            $cancellationSegments = array(
                array(
                    'segmentIdentifier' => $segmentIdentifier,
                    'segmentNumber' => $segmentNumber
                )
            );
        }

        if (isset($cancellationSegments) && is_array($cancellationSegments) && count($cancellationSegments) > 0) {
            $entryType = 'E';
            foreach ($cancellationSegments as $segment) {
                extract($segment);
                if ($segmentIdentifier && $segmentNumber) {
                    $element .= <<<EOS
<element>
    <identifier>$segmentIdentifier</identifier>
    <number>$segmentNumber</number>
 </element>
EOS;
                }
            }
        }


        $request = <<<EOR
<PNR_Cancel xmlns="http://xml.amadeus.com/PNRXCL_08_3_1A">
    <reservationInfo>
       <reservation>
	  <controlNumber>$controlNumber</controlNumber>
       </reservation>
    </reservationInfo>
    <pnrActions>
       <optionCode>$optionCode</optionCode>
    </pnrActions>
    <cancelElements>
       <entryType>$entryType</entryType>
       $element
    </cancelElements>
</PNR_Cancel>
EOR;

        return $request;
    }

    public function sessionHeaderXML($sessionData, $statusCode)
    {
        $session_str = '';

        if (!empty($sessionData)) {
            extract($sessionData);
            $session_str = <<<EOR
<awsse:SessionId>$sessionId</awsse:SessionId>
<awsse:SequenceNumber>$sequenceNumber</awsse:SequenceNumber>
<awsse:SecurityToken>$securityToken</awsse:SecurityToken>
EOR;
        }

        $xml = <<<EOR
<awsse:Session xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3" TransactionStatusCode="$statusCode">
	$session_str
</awsse:Session>
EOR;

        return $xml;
    }

    public function securityHeaderXML($originator, $nonce, $pwDigest, $creationTimeString)
    {
        $xml = <<< EOR
<oas:Security xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
    <oas:UsernameToken xmlns:oas1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" oas1:Id="UsernameToken-1">
	<oas:Username>$originator</oas:Username>
	<oas:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">$nonce</oas:Nonce>
	<oas:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">$pwDigest</oas:Password>
	<oas1:Created>$creationTimeString</oas1:Created>
    </oas:UsernameToken>
</oas:Security>
EOR;
        return $xml;
    }

    public function securityHostedHeaderXML($officeId, $originatorTypeCode, $posType, $dutyCode)
    {
        $xml = <<<EOR
<AMA_SecurityHostedUser xmlns="http://xml.amadeus.com/2010/06/Security_v1">
    <UserID AgentDutyCode="$dutyCode" POS_Type="$posType" PseudoCityCode="$officeId" RequestorType="$originatorTypeCode"/>
</AMA_SecurityHostedUser>
EOR;
        return $xml;
    }

    public function securitySignOutXML()
    {
        $xml = <<<EOR
<Security_SignOut></Security_SignOut>
EOR;
        return $xml;
    }

    public function pnrNameChangeXML($params)
    {
        extract($params);

        $travellerInfo = '';
        foreach ($roomGuests as $guest) {
            extract($guest);

            $travellerInfo .= <<<EOR
<enhancedPassengerInformation>
    <enhancedTravellerNameInfo>
       <travellerNameInfo>
	  <quantity>1</quantity>
	  <type>ADT</type>
	  <uniqueCustomerIdentifier>$number</uniqueCustomerIdentifier>
       </travellerNameInfo>
       <otherPaxNamesDetails>
	  <surname>$lastName</surname>
	  <givenName>$firstName</givenName>
	  <title>$title</title>
       </otherPaxNamesDetails>
    </enhancedTravellerNameInfo>
 </enhancedPassengerInformation>
EOR;
        }

        $request = <<<EOR
<PNR_NameChange xmlns="http://xml.amadeus.com/NMEREQ_14_1_1A">
   <transactionCode>
      <actionRequestCode>UPD</actionRequestCode>
   </transactionCode>
   <enhancedPassengerGroup>
      <elementManagementPassenger>
         <reference>
            <type>$pnrReferenceQualifier</type>
            <value>$pnrReferenceNumber</value>
         </reference>
      </elementManagementPassenger>
      $travellerInfo
   </enhancedPassengerGroup>
</PNR_NameChange>
EOR;
        return $request;
    }
}
