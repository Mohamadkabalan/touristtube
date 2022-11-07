<?php

namespace FlightBundle\Requests\Flights\Sabre\v1;

class SabreCreateSessionRequest {

    public function createSessionRequest($sabreVariables) {

        $conversationId = $sabreVariables['ConversationId'];
        $party_id_from = $sabreVariables['party_id_from'];
        $party_id_to = $sabreVariables['party_id_to'];
        $ProjectIPCC = $sabreVariables['ProjectIPCC'];
        $message_id = $sabreVariables['message_id'];
        $ProjectDomain = $sabreVariables['ProjectDomain'];
        $ProjectUserName = $sabreVariables['ProjectUserName'];
        $ProjectPassword = $sabreVariables['ProjectPassword'];
        $timestamp = $sabreVariables['Timestamp'];
        $timeToLive = $sabreVariables['TimeToLive'];

        $createSessionRequest = <<<EOR
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:eb="http://www.ebxml.org/namespaces/messageHeader" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:xsd="http://www.w3.org/1999/XMLSchema">
    <SOAP-ENV:Header>
        <eb:MessageHeader SOAP-ENV:mustUnderstand="1" eb:version="1.0">
            <eb:ConversationId>$conversationId</eb:ConversationId>
            <eb:From>
                    <eb:PartyId type="urn:x12.org:IO5:01">$party_id_from</eb:PartyId>
            </eb:From>
            <eb:To>
                    <eb:PartyId type="urn:x12.org:IO5:01">$party_id_to</eb:PartyId>
            </eb:To>
            <eb:CPAId>$ProjectIPCC</eb:CPAId>
            <eb:Service eb:type="Sabre">SessionCreateRQ</eb:Service>
            <eb:Action>SessionCreateRQ</eb:Action>
            <eb:MessageData>
                <eb:MessageId>$message_id</eb:MessageId>
                <eb:Timestamp>$timestamp</eb:Timestamp>
                <eb:TimeToLive>$timeToLive</eb:TimeToLive>
            </eb:MessageData>
        </eb:MessageHeader>
        <wsse:Security xmlns:wsse="http://schemas.xmlsoap.org/ws/2002/12/secext" xmlns:wsu="http://schemas.xmlsoap.org/ws/2002/12/utility">
            <wsse:UsernameToken>
                <wsse:Username>$ProjectUserName</wsse:Username>
                <wsse:Password>$ProjectPassword</wsse:Password>
                <Organization>$ProjectIPCC</Organization>
                <Domain>$ProjectDomain</Domain>
            </wsse:UsernameToken>
        </wsse:Security>
    </SOAP-ENV:Header>
    <SOAP-ENV:Body>
        <SessionCreateRQ>
            <POS>
                <Source PseudoCityCode="$ProjectIPCC"/>
            </POS>
        </SessionCreateRQ>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOR;

        return $createSessionRequest;
    }

}