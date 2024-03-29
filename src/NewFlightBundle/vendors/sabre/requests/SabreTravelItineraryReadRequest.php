<?php

namespace NewFlightBundle\vendors\sabre\requests;

class SabreTravelItineraryReadRequest extends SabreRequestHeader {

    public function travelItineraryRequest($pnrId) {
        $TIRequest = <<<EOR
    <SOAP-ENV:Body>
      <TravelItineraryReadRQ Version="3.6.0" xmlns="http://services.sabre.com/res/tir/v3_6" >
         <MessagingDetails>
            <SubjectAreas>
               <SubjectArea>DEFAULT</SubjectArea>
            </SubjectAreas>
         </MessagingDetails>
         <UniqueID ID="$pnrId"/>
      </TravelItineraryReadRQ>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOR;

        return $TIRequest;
    }
}