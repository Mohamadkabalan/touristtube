<?php

namespace FlightBundle\vendors\sabre\requests\v1;

class SabreDesignatePrinterRequest extends SabreRequestHeader {

    public function DesignatePrinterRequest($rq = 1) {
        $designatePrinterRequest = <<<EOR

    <SOAP-ENV:Body>
        <ns:DesignatePrinterRQ ReturnHostCommand="true" Version="2.0.1" xmlns:ns="http://webservices.sabre.com/sabreXML/2011/10">
            <ns:Printers>
EOR;
	if ($rq === 1) {
	    $designatePrinterRequest .= <<<EOR
                <ns:Ticket CountryCode="TG" />
EOR;
	} else if($rq === 2) {
	    $designatePrinterRequest .= <<<EOR
                <ns:Hardcopy LNIATA="AE319D" />
EOR;
	}
	$designatePrinterRequest .= <<<EOR
            </ns:Printers>
        </ns:DesignatePrinterRQ>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>		
EOR;
        return $designatePrinterRequest;
    }

}
