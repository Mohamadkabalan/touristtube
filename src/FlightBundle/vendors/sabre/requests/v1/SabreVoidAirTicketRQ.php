<?php

namespace FlightBundle\vendors\sabre\requests\v1;

class SabreVoidAirTicketRQ extends SabreRequestHeader {

    public function voidAirTicketRequest($rph) {
        $voidTicketRequest = <<<EOR
    <SOAP-ENV:Body>
        <VoidTicketRQ Version="2.0.2" xmlns="http://webservices.sabre.com/sabreXML/2011/10" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
            <Ticketing RPH="$rph"/>
        </VoidTicketRQ>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOR;
        return $voidTicketRequest;
    }

}
