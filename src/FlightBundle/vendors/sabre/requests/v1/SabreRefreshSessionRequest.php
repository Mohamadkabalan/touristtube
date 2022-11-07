<?php

namespace FlightBundle\vendors\sabre\requests\v1;

class SabreRefreshSessionRequest extends SabreRequestHeader {

    public function refreshSessionRequest() {
        $timestamp = date("Y-m-d\TH:i:sP", strtotime("now"));
        $refreshSession = <<<EOR
    <SOAP-ENV:Body>
        <OTA_PingRQ TimeStamp="$timestamp" Version="1.0.0" xmlns="http://www.opentravel.org/OTA/2003/05">
            <EchoData> Are you there </EchoData>
        </OTA_PingRQ>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOR;
        return $refreshSession;
    }
}
