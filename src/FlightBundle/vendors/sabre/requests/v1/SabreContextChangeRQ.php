<?php

namespace FlightBundle\vendors\sabre\requests\v1;

class SabreContextChangeRQ extends SabreRequestHeader {

    public function contextChangeRequest() {

        $contextChangeRequest = <<<EOR
    <SOAP-ENV:Body>
      <ContextChangeRQ xmlns="http://webservices.sabre.com/sabreXML/2011/10" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ReturnHostCommand="false" Version="2.0.3">
        <ChangeAAA PseudoCityCode="P5M7"/>
      </ContextChangeRQ>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOR;

        return $contextChangeRequest;
    }

}
