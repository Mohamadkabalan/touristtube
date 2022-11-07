<?php

namespace FlightBundle\Requests\Flights\Sabre\v1;

class SabreQueueAccessRQ extends SabreRequestHeader {

    public function queueAccessRq($ipcc, $queue_type = 50, $type = "count") {
        $queueAccess = <<<EOR
    <SOAP-ENV:Body>
        <QueueAccessRQ Version="2.0.8" xmlns="http://webservices.sabre.com/sabreXML/2011/10" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
            <QueueIdentifier Number="$queue_type" PseudoCityCode="$ipcc">
                <List Ind="true"/>
            </QueueIdentifier>
        </QueueAccessRQ>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOR;

        $queueCount = <<<EOR
    <SOAP-ENV:Body>
        <QueueCountRQ ReturnHostCommand="false" TimeStamp="2015-11-16T11:00:00-06:00" Version="2.2.0">
            <QueueInfo>
                <QueueIdentifier Number="$queue_type" PseudoCityCode="$ipcc" />
            </QueueInfo>
        </QueueCountRQ>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOR;
        return ($type === "count") ? $queueCount : $queueAccess;
    }

}
