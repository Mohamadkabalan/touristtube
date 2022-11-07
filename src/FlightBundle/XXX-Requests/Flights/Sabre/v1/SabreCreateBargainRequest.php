<?php

namespace FlightBundle\Requests\Flights\Sabre\v1; // cheating:: the format is compliant with v3, this is just a temporary tweak

class SabreCreateBargainRequest extends SabreRequestHeader {

    public function createBargainRequest($sabreVariables) {

        $ProjectIPCC = $sabreVariables['ProjectIPCC'];
        $companyName = $sabreVariables['CompanyName'];

        $departureAirport = $sabreVariables['OriginLocation'];
        $arrivalairport = $sabreVariables['DestinationLocation'];
        $fromDate = $sabreVariables['FromDate'];
        $toDate = $sabreVariables['ToDate'];
        $cabinSelect = $sabreVariables['cabinPref'];
        $tripType = $sabreVariables['TripType'];
        $flexibleDate = $sabreVariables['FlexibleDate'];
        $adultsSelect = $sabreVariables['PassengerTypeAdults'];
        $childrenSelect = $sabreVariables['PassengerTypeChildren'];
        $infantsSelect = $sabreVariables['PassengerTypeInfants'];
        $chosenAirline = $sabreVariables['chosenAirline'];
	$currencyCode = 'AED';//$sabreVariables['CurrencyCode'];
        $priority     = $sabreVariables['priority'];

        $multiDestination = $sabreVariables['MultiDestination'];
        if($multiDestination){
            $numTrips = 8;
            $numTripsWithRouting = 5;
        }else{
            $numTrips = 100;
            $numTripsWithRouting = 50;
        }
        $destinations = $sabreVariables['destinations'];
        $createBargainRequest = <<<EOR
<SOAP-ENV:Body>
    <OTA_AirLowFareSearchRQ xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" Version="3.3.0" ResponseType="OTA" ResponseVersion="3.3.0" AvailableFlightsOnly="true">
        <POS>
            <Source PseudoCityCode="$ProjectIPCC">
                <RequestorID ID="1" Type="1">
                    <CompanyName Code="$companyName" />
                </RequestorID>
            </Source>
        </POS>
        <OriginDestinationInformation RPH="1">
            <DepartureDateTime>$fromDate</DepartureDateTime>
            <OriginLocation LocationCode="$departureAirport"/>
            <DestinationLocation LocationCode="$arrivalairport"/>
	    <TPA_Extensions>
                <SegmentType Code="O" />
            </TPA_Extensions>
EOR;

        $createBargainRequest .= <<<EOR
        </OriginDestinationInformation>
EOR;
        if ($tripType === 'Return') {
            $createBargainRequest .= <<<EOR
        <OriginDestinationInformation RPH="2">
            <DepartureDateTime>$toDate</DepartureDateTime>
            <OriginLocation LocationCode="$arrivalairport"/>
            <DestinationLocation LocationCode="$departureAirport"/>
	    <TPA_Extensions>
                <SegmentType Code="O" />
            </TPA_Extensions>
EOR;
            $createBargainRequest .= <<<EOR
        </OriginDestinationInformation>
EOR;
        } else if ($multiDestination) {
            for ($i = 0; $i < count($destinations); $i++) {
                $departureAirportMulti = $destinations[$i]['departure_airport'];
                $arrivalAirportMulti = $destinations[$i]['arrival_airport'];
                $fromDateMulti = $destinations[$i]['from_date'];
                $count = 2 + $i;

                $createBargainRequest .= <<<EOR
        <OriginDestinationInformation RPH="$count">
            <DepartureDateTime>$fromDateMulti</DepartureDateTime>
            <OriginLocation LocationCode="$departureAirportMulti"/>
            <DestinationLocation LocationCode="$arrivalAirportMulti"/>
	    <TPA_Extensions>
                <SegmentType Code="O" />
            </TPA_Extensions>
EOR;

                $createBargainRequest .= <<<EOR
        </OriginDestinationInformation>
EOR;
            }
        }
        $createBargainRequest .= <<<EOR
        <TravelPreferences ValidInterlineTicket="true" ETicketDesired="true" Hybrid="true" LookForAlternatives="true">

EOR;
        if($chosenAirline != ''){
           $createBargainRequest .= <<<EOR
           <VendorPrefPairing Applicability="AtLeastOneSegment" PreferLevel="Preferred">
                <VendorPref Code="$chosenAirline" Type="Marketing"/>
            </VendorPrefPairing>
EOR;
            }


        if ($cabinSelect != '') {
            $createBargainRequest .= <<<EOR
            <CabinPref Cabin="$cabinSelect" PreferLevel="Preferred"/>
EOR;
        }
        $createBargainRequest .= <<<EOR
            <TPA_Extensions>
                <NumTrips Number='$numTrips'/>
                <NumTripsWithRouting Number="$numTripsWithRouting"/>
                <OnlineIndicator Ind="true"/>
                <TripType Value="$tripType"/>
                <!--<XOFares Value="false"/> -->
                <!--<ExemptAllTaxesAndFees Value="true"/>-->
                <ExcludeCallDirectCarriers Enabled="true"/>
            </TPA_Extensions>
            <AncillaryFees Enable="false" Summary="false" />
        </TravelPreferences>

        <TravelerInfoSummary>
            <AirTravelerAvail>
EOR;
        if ($adultsSelect > 0) {
            $createBargainRequest .= <<<EOR
                <PassengerTypeQuantity Code="ADT" Quantity="$adultsSelect">
                    <TPA_Extensions>
                        <VoluntaryChanges Match="Info"/>
                    </TPA_Extensions>
                </PassengerTypeQuantity>
EOR;
        }
        if ($childrenSelect > 0) {
            $createBargainRequest .= <<<EOR
                <PassengerTypeQuantity Code="CNN" Quantity="$childrenSelect">
                    <TPA_Extensions>
                        <VoluntaryChanges Match="Info"/>
                    </TPA_Extensions>
                </PassengerTypeQuantity>
EOR;
        }
        if ($infantsSelect > 0) {
            $createBargainRequest .= <<<EOR
                <PassengerTypeQuantity Code="INF" Quantity="$infantsSelect">
                    <TPA_Extensions>
                        <VoluntaryChanges Match="Info"/>
                    </TPA_Extensions>
                </PassengerTypeQuantity>
EOR;
        }
        $createBargainRequest .= <<<EOR
            </AirTravelerAvail>
            <PriceRequestInformation CurrencyCode="$currencyCode">
                <TPA_Extensions>
                    <Priority>
EOR;
        if ($priority == 1) {
            $createBargainRequest .= <<<EOR
                        <Price Priority="1"/>
                        <DirectFlights Priority="2"/>
                        <Time Priority="3"/>

EOR;
        } elseif ($priority == 2) {
            $createBargainRequest .= <<<EOR
                        <Price Priority="3"/>
                        <DirectFlights Priority="2"/>
                        <Time Priority="1"/>
EOR;
        }elseif($priority == 0){
             $createBargainRequest .= <<<EOR
                        <Price Priority="2"/>
                        <DirectFlights Priority="1"/>
                        <Time Priority="3"/>
EOR;
        }
        $createBargainRequest .= <<<EOR
                    <Vendor Priority="4"/>
                    </Priority>
                    <BrandedFareIndicators SingleBrandedFare="true" MultipleBrandedFares="true"/>
                </TPA_Extensions>
            </PriceRequestInformation>
        </TravelerInfoSummary>
        <TPA_Extensions>
            <IntelliSellTransaction>
EOR;
        if ($flexibleDate) {
            $createBargainRequest .= <<<EOR
                <RequestType Name="AD3"/>
EOR;
        } else {
            $createBargainRequest .= <<<EOR
                <RequestType Name="200ITINS"/>
EOR;
        }

        $createBargainRequest .= <<<EOR

                <CompressResponse Value="true"/>
		<ResponseSorting SortFaresInsideItin="true"/>
            </IntelliSellTransaction>
            <MultiTicket DisplayPolicy="SCHS"/>
        </TPA_Extensions>
    </OTA_AirLowFareSearchRQ>
</SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOR;

        return $createBargainRequest;
    }
}