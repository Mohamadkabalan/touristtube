<?php

namespace NewFlightBundle\vendors\sabre\requests;

class SabreAirTicketRequest extends SabreRequestHeader {

    public function airTicketRq($passengers) {

        $record = 0;
        $previous_type = '';
        $airTicketRequest = <<<EOR
<SOAP-ENV:Body>
    <ns:AirTicketRQ NumResponses="1" ReturnHostCommand="true" Version="2.7.0" xmlns:ns="http://webservices.sabre.com/sabreXML/2011/10">
        <ns:OptionalQualifiers>
	    <ns:FOP_Qualifiers>
	       <ns:BasicFOP Type="CA">
	       </ns:BasicFOP>
	    </ns:FOP_Qualifiers>
	    <ns:MiscQualifiers>
		<ns:Ticket Type="ETR"/>
	    </ns:MiscQualifiers>
	    <ns:PricingQualifiers>
		<ns:PriceQuote>
EOR;
        foreach ($passengers as $passengerDetail) {
            if ($passengerDetail->getType() !== $previous_type)
	    {
                $record++;
            $airTicketRequest .= <<<EOR
            <ns:Record Number="$record"/>
EOR;
	    }
            $previous_type = $passengerDetail->getType();
        }
        $airTicketRequest .= <<<EOR
           </ns:PriceQuote>
        </ns:PricingQualifiers>
     </ns:OptionalQualifiers>
  </ns:AirTicketRQ>
</SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOR;
        return $airTicketRequest;
    }

}
