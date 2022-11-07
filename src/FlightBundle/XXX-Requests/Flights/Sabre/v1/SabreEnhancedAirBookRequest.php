<?php

namespace FlightBundle\Requests\Flights\Sabre\v1;

class SabreEnhancedAirBookRequest extends SabreRequestHeader{

    public function enhancedAirBookRequest($sabreVariables, $isAirPriceRqOnly = false) {

        $totalSegments = $sabreVariables['total_segments'];
        $departureDateTime = $sabreVariables['DepartureDateTime'];
        $arrivalDateTime = $sabreVariables['ArrivalDateTime'] ;
        $flightNumber = $sabreVariables['FlightNumber'];
        $numberInParty = $sabreVariables['NumberInParty'];
        $resBookDesigCode = $sabreVariables['ResBookDesigCode'];
        $destinationLocation = $sabreVariables['DestinationLocation'];
        $marketingAirline = $sabreVariables['MarketingAirline'];
        $operatingAirline = $sabreVariables['OperatingAirline'];
        $originLocation = $sabreVariables['OriginLocation'];

        $adultsQuantity = $sabreVariables['AdultsQuantity'];
        $childrenQuantity = $sabreVariables['ChildrenQuantity'];
        $infantsQuantity = $sabreVariables['InfantsQuantity'];
        $priceAmount = $sabreVariables['PriceAmount'];
        $currencyCode = 'AED';//$sabreVariables['CurrencyCode'];

        $airBookRequest = <<<EOR
<SOAP-ENV:Body>
    <EnhancedAirBookRQ xmlns="http://services.sabre.com/sp/eab/v3_9" version="3.9.0" IgnoreOnError="true" HaltOnError="true">

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
        for($i=0; $i < $totalSegments; $i++){
            $airBookRequest .= <<<EOR

                <FlightSegment DepartureDateTime="$departureDateTime[$i]" ArrivalDateTime="$arrivalDateTime[$i]" FlightNumber="$flightNumber[$i]" NumberInParty="$numberInParty" ResBookDesigCode="$resBookDesigCode[$i]" Status="NN">
                    <DestinationLocation LocationCode="$destinationLocation[$i]"/>
                    <MarketingAirline Code="$marketingAirline[$i]" FlightNumber="$flightNumber[$i]"/>
                    <OperatingAirline Code="$operatingAirline[$i]"/>
                    <OriginLocation LocationCode="$originLocation[$i]"/>
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
            <!--<MiscQualifiers>-->
                 <!--<MultiTicket Ind="true"/>-->
                 <!--<ValidationMethod>BSP</ValidationMethod>-->
             <!--</MiscQualifiers>-->
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