<?php

namespace FlightBundle\vendors\sabre\requests\v1;

class SabreCommandRequest extends SabreRequestHeader {

    public function commandRequest($commandRq) {
        $command = ($commandRq = 1) ? 'W/TAA02DH ‡BAY3H' : 'W/TAA02DH ‡CAY3H';
        $commandLLSRQRequest = <<<EOR
<SabreCommandLLSRQ Version="2003A.TsabreXML1.6.1" xmlns="http://webservices.sabre.com/sabreXML/2003/07" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <Request Output="SCREEN" CDATA="false">
       <HostCommand>$command</HostCommand>
    </Request>
</SabreCommandLLSRQ>
EOR;

        return $commandLLSRQRequest;
    }

}
