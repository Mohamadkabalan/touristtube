<?php

namespace FlightBundle\Requests\Flights\Sabre\v1;

class SabreCloseSessionRequest extends SabreRequestHeader {

    public function closeSessionRequest($sabreVariables) {

        $ProjectIPCC = $sabreVariables['ProjectIPCC'];
       

        $closeSessionRequest = <<<EOR

    <SOAP-ENV:Body>
        <SessionCloseRQ>
            <POS>
                <Source PseudoCityCode="$ProjectIPCC"/>
            </POS>
        </SessionCloseRQ>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOR;

        return $closeSessionRequest;
    }

}
