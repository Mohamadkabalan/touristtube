<?php

namespace NewFlightBundle\vendors\sabre\requests;

class SabreRequestHeader {

    public function requestHeader($sabreVariables,$createSession) {

        $accessToken = $createSession->getAccessToken(); //$sabreVariables['access_token'];
        $conversationId = $createSession->getReturnedConversationId(); //$sabreVariables['returnedConversationId'];
        $party_id_from = $sabreVariables->getPartyIdFrom();
        $party_id_to = $sabreVariables->getPartyIdTo();
        $ProjectIPCC = $sabreVariables->getProjectIPCC();
        $service = $sabreVariables->getService();
        $action = $sabreVariables->getAction();
        $message_id = $sabreVariables->getMessageId();      
        $timestamp = $sabreVariables->getTimestamp();
        $timeToLive = $sabreVariables->getTimeToLive();

        $envelopeHeader = <<<EOR
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:eb="http://www.ebxml.org/namespaces/messageHeader" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:xsd="http://www.w3.org/1999/XMLSchema">
    <SOAP-ENV:Header>
        <eb:MessageHeader SOAP-ENV:mustUnderstand="1" eb:version="1.0">
            <eb:From>
                <eb:PartyId type="urn:x12.org:IO5:01">$party_id_from</eb:PartyId>
            </eb:From>
            <eb:To>
                 <eb:PartyId type="urn:x12.org:IO5:01">$party_id_to</eb:PartyId>
            </eb:To>
            <eb:CPAId>$ProjectIPCC</eb:CPAId>
            <eb:ConversationId>$conversationId</eb:ConversationId>
            <eb:Service eb:type="Sabre">$service</eb:Service>
            <eb:Action>$action</eb:Action>
            <eb:MessageData>
                <eb:MessageId>$message_id</eb:MessageId>
                <eb:Timestamp>$timestamp</eb:Timestamp>
                <eb:TimeToLive>$timeToLive</eb:TimeToLive>
            </eb:MessageData>
        </eb:MessageHeader>
        <wsse:Security xmlns:wsse="http://schemas.xmlsoap.org/ws/2002/12/secext" xmlns:wsu="http://schemas.xmlsoap.org/ws/2002/12/utility">
            <wsse:BinarySecurityToken valueType="String" EncodingType="wsse:Base64Binary">$accessToken</wsse:BinarySecurityToken>
        </wsse:Security>
    </SOAP-ENV:Header>
EOR;

        return $envelopeHeader;
    }

}
