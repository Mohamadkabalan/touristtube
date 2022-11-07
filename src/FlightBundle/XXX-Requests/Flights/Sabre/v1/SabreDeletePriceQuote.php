<?php

namespace FlightBundle\Requests\Flights\Sabre\v1;

class SabreDeletePriceQuote extends SabreRequestHeader {

    public function deletePriceQuote() {
        $deletePriceQuoteRequest = <<<EOR
<DeletePriceQuoteRQ xmlns="http://webservices.sabre.com/sabreXML/2011/10" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"ReturnHostCommand="true" TimeStamp="2016-05-23T13:30:00-6:00" Version="2.1.0">
    <AirItineraryPricingInfo>
	<Record All="true"/>
    </AirItineraryPricingInfo>
</DeletePriceQuoteRQ>

EOR;

        return $deletePriceQuoteRequest;
    }

}
