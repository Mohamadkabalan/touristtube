<?php

namespace FlightBundle\vendors\sabre\requests\v1;

class SabreRetrieveItineraryRQ extends SabreRequestHeader {

    public function retrieveItineraryRequest($pnrId) {

        $TIRequest = <<<EOR
    <SOAP-ENV:Body>
        <GetReservationRQ  Version="1.19.0" xmlns="http://webservices.sabre.com/pnrbuilder/v1_19">
	        <Locator>$pnrId</Locator>
	        <RequestType>Stateful</RequestType>
	        <ReturnOptions>
	            <SubjectAreas>
		        	<SubjectArea>PRICE_QUOTE</SubjectArea>
		        </SubjectAreas>
		        <ViewName>FullWithOpenRes</ViewName>
	        	<ResponseFormat>STL</ResponseFormat>
	        </ReturnOptions>
        </GetReservationRQ>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOR;

        return $TIRequest;
    }
}
