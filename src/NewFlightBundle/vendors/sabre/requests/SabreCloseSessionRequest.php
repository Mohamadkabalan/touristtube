<?php

namespace NewFlightBundle\vendors\sabre\requests;

class SabreCloseSessionRequest extends SabreRequestHeader {

    public function closeSessionRequest($sabreVariables) {

        $ProjectIPCC = $sabreVariables->getProjectIPCC();       
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
