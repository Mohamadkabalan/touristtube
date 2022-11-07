<?php

namespace FlightBundle\vendors\sabre\requests\v1;

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
       <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
	<SOAP-ENV:Header>
		<m:MessageHeader xmlns:m="http://www.ebxml.org/namespaces/messageHeader">
			<m:From>
				<m:PartyId type="urn:x12.org:IO5:01">$party_id_from</m:PartyId>
			</m:From>
			<m:To>
				<m:PartyId type="urn:x12.org:IO5:01">$party_id_to</m:PartyId>
			</m:To>
			<m:CPAId>$ProjectIPCC</m:CPAId>
			<m:ConversationId>abc123</m:ConversationId>
			<m:Service m:type="OTA">SessionCreateRQ</m:Service>
			<m:Action>SessionCreateRQ</m:Action>
			<m:MessageData>
				<m:MessageId>$message_id</m:MessageId>
				<m:Timestamp>$timestamp</m:Timestamp>
				<m:TimeToLive>$timeToLive</m:TimeToLive>
			</m:MessageData>
			<m:DuplicateElimination/>
			<m:Description>Bargain Finder Max Service</m:Description>
		</m:MessageHeader>
		<wsse:Security xmlns:wsse="http://schemas.xmlsoap.org/ws/2002/12/secext">
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
