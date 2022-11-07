<?php

namespace FlightBundle\vendors\sabre\requests\v1;

class SabrePassengerDetailsActions extends SabreRequestHeader {

    public function passengerDetailActions($testMode, $ipcc = "", $action = "retrieve", $pnrId = "") {
        switch ($action) {
            case "add":
                $queueNumber = 69;
                $ipcc = "AY3H";
                $fees = "FEES-1.5";
                $act = "ACTE-TICKETING";
                $endTransIndecator = "false";
                break;
            case "cancel":
                $queueNumber = 70;
                $ipcc = "AY3H";
                $fees = "FEES-6";
                $act = "ACTE-ANNULATION";
                $endTransIndecator = "false";
                break;
            case "retrieve":
                $queueNumber = 50;
                $endTransIndecator = "true";
                break;
            case "retrieve_cancel":
                $queueNumber = 51;
                $endTransIndecator = "false";
                break;
        }
        $pnrActions = <<<EOR
<SOAP-ENV:Body>
    <PassengerDetailsRQ version="3.3.0" HaltOnError="true" IgnoreOnError="true" xmlns="http://services.sabre.com/sp/pd/v3_3">
        <PostProcessing IgnoreAfter="false" RedisplayReservation="true">
            <EndTransactionRQ>
               <EndTransaction Ind="$endTransIndecator"/>
               <Source ReceivedFrom="TOURIST TUBE IBE"/>
            </EndTransactionRQ>
EOR;
        if (!$testMode) {
            $pnrActions .= <<<EOR
            <QueuePlaceRQ>
                <QueueInfo>
                   <QueueIdentifier PseudoCityCode="$ipcc" PrefatoryInstructionCode="11" Number="$queueNumber"/>
                   <UniqueID ID="$pnrId"/>
                </QueueInfo>
            </QueuePlaceRQ>
EOR;
        }
        $pnrActions .= <<<EOR
        </PostProcessing>
EOR;
        if (!$testMode  && $action !== "retrieve" && $action !== "retrieve_cancel") {
            $pnrActions .= <<<EOR
        <SpecialReqDetails>
            <AddRemarkRQ>
                <RemarkInfo>
                    <Remark Type="Invoice">
                        <Text>CLIENT-C103</Text>
                    </Remark>
                    <Remark Type="Invoice">
                        <Text>FACTAUTO-O</Text>
                    </Remark>
                    <Remark Type="Invoice">
                        <Text>ENVOI-E</Text>
                    </Remark>
                    <Remark Type="Invoice">
                        <Text>$fees</Text>
                    </Remark>
                    <Remark Type="Invoice">
                        <Text>$act</Text>
                    </Remark>
                </RemarkInfo>
            </AddRemarkRQ>       
        </SpecialReqDetails>
EOR;
        }
        $pnrActions .= <<<EOR
        <TravelItineraryAddInfoRQ>
            <AgencyInfo>
                <Ticketing PseudoCityCode="$ipcc" QueueNumber="$queueNumber" TicketType="7T-"/>
            </AgencyInfo>
        </TravelItineraryAddInfoRQ>
    </PassengerDetailsRQ>
</SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOR;
        return $pnrActions;
    }

}
