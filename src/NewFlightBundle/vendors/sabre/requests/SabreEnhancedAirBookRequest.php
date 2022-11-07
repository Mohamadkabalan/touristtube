<?php

namespace NewFlightBundle\vendors\sabre\requests;

class SabreEnhancedAirBookRequest extends SabreRequestHeader{

    public function enhancedAirBookRequest($enhancedAirBookRequest, $isAirPriceRqOnly = false) {
        
        $totalSegments = $enhancedAirBookRequest->getFlightItineraryModel()->getTotalSegment();
        $numberInParty = $enhancedAirBookRequest->getNumberInParty();
        $adultsQuantity = $enhancedAirBookRequest->getAdultsQuantity();
        $childrenQuantity = $enhancedAirBookRequest->getChildrenQuantity();
        $infantsQuantity = $enhancedAirBookRequest->getInfantsQuantity();
        $priceAmount = $enhancedAirBookRequest->getFlightItineraryModel()->getPrice();
        $currencyCode = 'AED';//$sabreVariables['CurrencyCode'];

        $airBookRequest = <<<EOR
           <SOAP-ENV:Body>
     <EnhancedAirBookRQ version="3.6.0" IgnoreOnError="true" HaltOnError="true" xmlns="http://services.sabre.com/sp/eab/v3_6">
EOR;
	if(!$isAirPriceRqOnly){
	$airBookRequest .= <<<EOR
        <OTA_AirBookRQ>
            <RetryRebook Option="true"/>

            <HaltOnStatus Code="NO"/>
            <!--<HaltOnStatus Code="NN"/>-->
            <HaltOnStatus Code="UC"/>
            <HaltOnStatus Code="LL"/>
            <HaltOnStatus Code="US"/>
            <OriginDestinationInformation>
EOR;
        $getFlightSegmentModel = $enhancedAirBookRequest->getFlightItineraryModel()->getFlightSegmentModel();
        
        foreach($getFlightSegmentModel as $i => $segment){

            $departureDateTime = $segment->getFlightDeparture()->getDateTime();
            $arrivalDateTime = $segment->getFlightArrival()->getDateTime();
            $flightNumber = $segment->getFlightNumber();
            $resBookDesigCode = $segment->getResBookDesigCode();
            $destinationLocation = $segment->getFlightArrival()->getAirportModel()->getCode();
            $marketingAirline = $segment->getMarketingAirline()->getAirlineCode();
            $operatingAirline = $segment->getOperatingAirline()->getAirlineCode();;
            $originLocation = $segment->getFlightDeparture()->getAirportModel()->getCode();

            $airBookRequest .= <<<EOR

                <FlightSegment DepartureDateTime="$departureDateTime" ArrivalDateTime="$arrivalDateTime" FlightNumber="$flightNumber" NumberInParty="$numberInParty" ResBookDesigCode="$resBookDesigCode" Status="NN">
                    <DestinationLocation LocationCode="$destinationLocation"/>
                    <MarketingAirline Code="$marketingAirline" FlightNumber="$flightNumber"/>
                    <OperatingAirline Code="$operatingAirline"/>
                    <OriginLocation LocationCode="$originLocation"/>
                </FlightSegment>
EOR;
        }
            $airBookRequest .= <<<EOR
            </OriginDestinationInformation>
            <RedisplayReservation WaitInterval="10000" NumAttempts="10"/>
        </OTA_AirBookRQ>
EOR;
	}
	$airBookRequest .= <<<EOR
	<OTA_AirPriceRQ>
            <PriceComparison AmountSpecified="$priceAmount"/>
            <PriceRequestInformation Retain="true">
                <OptionalQualifiers>
                    <PricingQualifiers CurrencyCode="$currencyCode">
EOR;
        if ($adultsQuantity > 0) {
            $airBookRequest .= <<<EOR
                        <PassengerType Code="ADT" Quantity="$adultsQuantity"/>
EOR;
        }
        if ($childrenQuantity > 0) {
            $airBookRequest .= <<<EOR
                        <PassengerType Code="CNN" Quantity="$childrenQuantity"/>
EOR;
        }
        if ($infantsQuantity > 0) {
            $airBookRequest .= <<<EOR
                        <PassengerType Code="INF" Quantity="$infantsQuantity"/>
EOR;
        }
            $airBookRequest .= <<<EOR
                    </PricingQualifiers>
                </OptionalQualifiers>
            </PriceRequestInformation>
        </OTA_AirPriceRQ>
        <PostProcessing IgnoreAfter="false">
            <RedisplayReservation WaitInterval="10000"/>
        </PostProcessing>
        <PreProcessing IgnoreBefore="true"/>
    </EnhancedAirBookRQ>
</SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOR;

        return $airBookRequest;
    }

}