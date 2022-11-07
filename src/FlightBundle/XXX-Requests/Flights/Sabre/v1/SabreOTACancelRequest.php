<?php

namespace FlightBundle\Requests\Flights\Sabre\v1;

class SabreOTACancelRequest extends SabreRequestHeader {

    public function OTACancelRequest() {
        $OTACancelRequest = <<<EOR
<SOAP-ENV:Body>  
  <OTA_CancelRQ Version="2.0.1" xmlns="http://webservices.sabre.com/sabreXML/2011/10" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
      <Segment Type="entire"/>
  </OTA_CancelRQ>
</SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOR;

        return $OTACancelRequest;
    }

}
