<?php

namespace FlightBundle\Requests\Flights\Sabre\v1;

class SabreEndTransactionRequest extends SabreRequestHeader {

    public function endTransactionRq() {

        $EndTransactionRequest = <<<EOR

    <SOAP-ENV:Body>  
      <ns:EndTransactionRQ Version="2.0.5" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sec="http://schemas.xmlsoap.org/ws/2002/12/secext" xmlns:mes="http://www.ebxml.org/namespaces/messageHeader" xmlns:ns="http://webservices.sabre.com/sabreXML/2011/10" ReturnHostCommand="true">
        <ns:EndTransaction Ind="true" />
        <ns:Source ReceivedFrom="TOURIST TUBE SWS"/>
      </ns:EndTransactionRQ>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOR;

        return $EndTransactionRequest;
    }

}
