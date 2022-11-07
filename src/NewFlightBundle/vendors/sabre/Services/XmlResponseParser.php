<?php
/**
 * Created by PhpStorm.
 * User: para-soft7
 * Date: 9/13/2018
 * Time: 6:40 PM
 */

namespace NewFlightBundle\vendors\sabre\Services;

use NewFlightBundle\Model\CreateSessionRS;
use NewFlightBundle\Model\FlightDetails;
use NewFlightBundle\Model\FlightItinerary;
use NewFlightBundle\Model\FlightStops;
use NewFlightBundle\Model\flightVO;
use NewFlightBundle\Model\FlightTicket;
use NewFlightBundle\Model\FlightSegment;
use NewFlightBundle\Model\PassengerNameRecord;
use NewFlightBundle\Model\PassengerInfoBaggage;
use NewFlightBundle\Model\Penalty;

use Sabre\Xml\Service;
use Sabre\Xml\Reader;
use Sabre\Xml\XMLReaderElement;
use NewFlightBundle\vendors\sabre\Services\sabreCreateSession;
use TTBundle\Utils\Utils;
use NewFlightBundle\Model\CreateBargainRequest;

if (!defined('RESPONSE_SUCCESS')) {
    define('RESPONSE_SUCCESS', false);
}
if (!defined('RESPONSE_ERROR')) {
    define('RESPONSE_ERROR', true);
}

class XmlResponseParser
{
    /**
     * @var Utils
     */
    protected $utils;

    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }

    public function parseCreateSessionResponse($sabreCreateSessionResponse)
    {
        $createdSession = new CreateSessionRS();

        $xpath = $this->utils->xmlString2domXPath($sabreCreateSessionResponse['response_text']);

        $accessTokenData = $xpath->query("//wsse:BinarySecurityToken");

        if ($accessTokenData && $accessTokenData->length) {
            $createdSession->setAccessToken($accessTokenData->item(0)->nodeValue);
        }

        $conversationIdData = $xpath->query("//eb:ConversationId");

        if ($conversationIdData && $conversationIdData->length) {
            $createdSession->setReturnedConversationId($conversationIdData->item(0)->nodeValue);
        }
        $createdSession->setStatus(true);
        $createdSession->setMessage("A Sabre Session is created successfully !");

        return $createdSession;
    }

    /**
     * Closing Session
     *
     * @param type $sabreVariables the variables required to close session

     * @return object
     */
    public function parseCloseResponse($closeSessionResponse)
    {
        if (empty($closeSessionResponse['response_text'])) {
            die("Please specify xml file to parse.\n");
        }

        $response = $this->errorRsHandler($closeSessionResponse['response_text']);
        if ($response->getStatus() == 'error') {
            return $response;
        }

        $xpath        = $this->utils->xmlString2domXPath($closeSessionResponse['response_text']);
        $closeSession = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:SessionCloseRS");
        $appResult    = $closeSession->item(0)->getAttribute('status');

        $response->setStatus($appResult);
        $response->setStatus("completed");
        return $response;
    }
    /*
     * BFM parser: this function is called to parse FROM Bargian Request
     * Responsible to get search result of available flights
     *
     * @param array $bargainCompressedResponse: Response from createBargainRequest
     */

    public function parseBargainFinderResponse($bargainCompressedResponse, CreateBargainRequest $bargainRequest)
    {
        if (empty($bargainCompressedResponse['response_text'])) {
            die("Please specify xml file to parse.\n");
        }

        $error = $this->errorRsHandler($bargainCompressedResponse['response_text']);

        if ($error->getStatus() == 'error') {
            return $error;
        }

        $xpath = $this->utils->xmlString2domXPath($bargainCompressedResponse['response_text']);

        $encodedData  = $xpath->query("/SOAP-ENV:Envelope/SOAP-ENV:Body/xmlns:CompressedResponse");
        $decodedData  = base64_decode($encodedData->item(0)->nodeValue, true);
        $unzippedData = gzdecode($decodedData);

        $countIx  = 0;
        $response = array();

        $xml = new \XMLReader();
        $xml->XML($unzippedData);

        if ($xml === false) {
            die('Unable to load and parse the xml file');
        }

        if ($bargainRequest->getTripType() == 'Return') {
            while ($xml->read() && $xml->name !== 'SimpleOneWayItineraries');
            while ($xml->name === 'SimpleOneWayItineraries') {
                $element = new \SimpleXMLElement($xml->readOuterXML());
                $rph     = strval($element->attributes()->RPH);
                foreach ($element->PricedItinerary as $pricedItinerary) {
                    $flightItinerary = $this->getPricedItinerary($pricedItinerary, $rph);
                    $response[]      = $flightItinerary;
                    $countIx++;
                }
                $xml->next('SimpleOneWayItineraries');
            }
        } else {
            while ($xml->read() && $xml->name !== 'PricedItinerary');
            while ($xml->name === 'PricedItinerary') {
                $element         = new \SimpleXMLElement($xml->readOuterXML());
                $flightItinerary = $this->getPricedItinerary($element);
                $response[]      = $flightItinerary;
                $countIx++;
                $xml->next('PricedItinerary');
                unset($element);
            }
        }

        $xml->close();

        $memGetUsage      = memory_get_usage() / 1024;
        $memGetUsageTrue  = memory_get_usage(true) / 1024;
        $memPeakUsage     = memory_get_peak_usage() / 1024;
        $memPeakUsageTrue = memory_get_peak_usage(true) / 1024;

        echo "Number of items=$countIx\n";
        echo "memory_get_usage() =".$memGetUsage." kb\n";
        echo "memory_get_usage(true) =".$memGetUsageTrue." kb\n";
        echo "memory_get_peak_usage() =".$memPeakUsage." kb\n";
        echo "memory_get_peak_usage(true) =".$memPeakUsageTrue." kb\n";
        $memoryUsage = $this->memory_get_process_usage();
        echo "custom memory_get_process_usage() =".$memoryUsage." kb\n";

        $flightVO = new flightVO();

        if (!empty($response)) {
            $flightVO->setCode(100); //Found
            $flightVO->setStatus('success');
            $flightVO->setData($response);
        }
        else {
            $flightVO->setCode(101); //No Results
            $flightVO->setStatus('success');
            $flightVO->setData($response);
        }

        return $flightVO;
    }
    /*
     * Parsing PricedItinerary elements
     *
     * @param $elements object
     * @param $rph integer (RPH = 1: outbound, RPH = 2: inbound)
     *
     * @return object
     */

    public function getPricedItinerary($element, $rph = 0)
    {

        $flightItinerary = new FlightItinerary();
        $flightItinerary->setStatus('success');
        $flightItinerary->setMessage('completed');

        if ($rph == 1) {
            $flightItinerary->setOutbound(true);
        } elseif ($rph == 2) {
            $flightItinerary->setInbound(true);
        }

        $flightItinerary->setFlightType(strval($element->AirItinerary->attributes()->DirectionInd));
        $flightItinerary->setTotalDuration(strval($element->AirItinerary->OriginDestinationOptions->OriginDestinationOption->attributes()->ElapsedTime));

        $baseFare = $element->AirItineraryPricingInfo->ItinTotalFare->BaseFare;

        if ($baseFare) {
            $flightItinerary->getFlightItineraryPricingInfo()->setBaseFare(strval($baseFare->attributes()->Amount));
        }

        $fareConstruction = $element->AirItineraryPricingInfo->ItinTotalFare->FareConstruction;

        if ($fareConstruction) {
            $flightItinerary->getFlightItineraryPricingInfo()->setFareConstruction(strval($fareConstruction->attributes()->Amount));
        }

        $taxes = $element->AirItineraryPricingInfo->ItinTotalFare->Taxes->Tax;

        if ($taxes) {
            $flightItinerary->getFlightItineraryPricingInfo()->setBaseTaxes(strval($taxes->attributes()->Amount));
        }

        $equivFare = $element->AirItineraryPricingInfo->ItinTotalFare->EquivFare;

        if ($equivFare) {
            $flightItinerary->getFlightItineraryPricingInfo()->setEquivFare(strval($equivFare->attributes()->Amount));
        }

        $totalFare = $element->AirItineraryPricingInfo->ItinTotalFare->TotalFare;

        if ($totalFare) {
            $flightItinerary->getFlightItineraryPricingInfo()->setTotalFare(strval($totalFare->attributes()->Amount));
            $flightItinerary->getCurrency()->setCode(strval($totalFare->attributes()->CurrencyCode));
        }

        //penalties and baggage allowance
        $penaltiesArr      = array();
        $baggageInfoArr    = array();
        $penaltyCnt        = 0;
        $baggageInfoCnt    = 0;
        $ptcFareBreakdowns = $element->AirItineraryPricingInfo->PTC_FareBreakdowns;
        if ($ptcFareBreakdowns) {
            foreach ($ptcFareBreakdowns->PTC_FareBreakdown->PassengerFare as $passengerFare) {
                foreach ($passengerFare->PenaltiesInfo->Penalty as $penaltyInfo) {
                    $penalty        = new Penalty;
                    $conditionApply = ($penaltyInfo->attributes()->Changeable) ? 'true' : 'false';

                    $penalty->setType(strval($penaltyInfo->attributes()->Type));
                    $penalty->setApplicability(strval($penaltyInfo->attributes()->Applicability));
                    $penalty->setConditionApply($conditionApply);
                    $penalty->setAmount(strval($penaltyInfo->attributes()->Amount));
                    $penalty->setCurrency(strval($penaltyInfo->attributes()->CurrencyCode));
                    $penaltyCnt++;
                    $penaltiesArr[$penaltyCnt] = $penalty;
                }
                foreach ($passengerFare->TPA_Extensions->BaggageInformationList->BaggageInformation as $baggageInformation) {
                    $baggage = new PassengerInfoBaggage();
                    if ($baggageInformation->Allowance->attributes()->Pieces) {
                        $baggage->setWeight(strval($baggageInformation->Allowance->attributes()->Pieces));
                    }
                    if ($baggageInformation->Allowance->attributes()->Weight) {
                        $baggage->setWeight(strval($baggageInformation->Allowance->attributes()->Weight));
                    }
                    if ($baggageInformation->Allowance->attributes()->Unit) {
                        $baggage->setUnit(strval($baggageInformation->Allowance->attributes()->Unit));
                    }
                    $baggageInfoCnt++;
                    $baggageInfoArr[$baggageInfoCnt] = $baggage;
                }
            }
        }

        //flight segments
        $flightSegArr = array();
        $segmentCnt   = 0;

        $originDestinationOptions = $element->AirItinerary->OriginDestinationOptions;
        foreach ($element->AirItinerary->OriginDestinationOptions->OriginDestinationOption as $originDestinationOption) {
            foreach ($originDestinationOption->FlightSegment as $flightSeg) {

                $flightSegment = new FlightSegment();
                $flightSegment->setDuration(strval($flightSeg->attributes()->ElapsedTime));
                $flightSegment->setFlightNumber(strval($flightSeg->attributes()->FlightNumber));
                $flightSegment->getFlightDeparture()->setDateTime(strval($flightSeg->attributes()->DepartureDateTime));
                $flightSegment->getFlightDeparture()->setTimezoneGmtOffset(strval($flightSeg->DepartureTimeZone->attributes()->GMTOffset));
                $flightSegment->getFlightDeparture()->getAirport()->setCode(strval($flightSeg->DepartureAirport->attributes()->LocationCode));
                $flightSegment->getFlightDeparture()->getAirport()->setTerminalId(strval($flightSeg->DepartureAirport->attributes()->TerminalID));
                $flightSegment->getFlightArrival()->setDateTime(strval($flightSeg->attributes()->ArrivalDateTime));
                $flightSegment->getFlightArrival()->setTimezoneGmtOffset(strval($flightSeg->ArrivalTimeZone->attributes()->GMTOffset));
                $flightSegment->getFlightArrival()->getAirport()->setCode(strval($flightSeg->ArrivalAirport->attributes()->LocationCode));
                $flightSegment->getFlightArrival()->getAirport()->setTerminalId(strval($flightSeg->ArrivalAirport->attributes()->TerminalID));
                $flightSegment->getOperatingAirline()->setAirlineCode(strval($flightSeg->OperatingAirline->attributes()->Code));
                $flightSegment->getMarketingAirline()->setAirlineCode(strval($flightSeg->MarketingAirline->attributes()->Code));
                $flightSegment->setPenalty($penaltiesArr);
                $flightSegment->setBaggageAllowance($baggageInfoArr);

                $flightSegArr[$segmentCnt] = $flightSegment;

                $segmentCnt ++;
            }
        }

        $flightItinerary->setFlightSegment($flightSegArr);
        $flightItinerary->setTotalSegment($segmentCnt);

        return $flightItinerary;
    }

    /**
     * Parsing createEnhancedAirBookResponse
     *
     * @param array $enhancedAirBookResponse: Response from createEnhancedAirBookRequest
     *
     * @return Object that contains status that's success in case of success and totalAmount with the currency Code
     */
    public function parseEnhancedAirBookResponse($enhancedAirBookResponse)
    {
        if (empty($enhancedAirBookResponse['response_text'])) {
            die("Please specify xml file to parse.\n");
        }

        $error = $this->errorRsHandler($enhancedAirBookResponse['response_text']);
        if ($error->getStatus() == 'error') {
            return $error;
        }

        $xpath        = $this->utils->xmlString2domXPath($enhancedAirBookResponse['response_text']);
        $responseNode = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:EnhancedAirBookRS");
        $responseNode = $responseNode->item(0);
        $appResults   = $responseNode->firstChild;
        $appResult    = strtolower($appResults->firstChild->localName);

        $flightItinerary = new FlightItinerary();
        $flightItinerary->setStatus($appResult);

        //if error then returned
        if ($appResult == 'error') {
            $flightDetails->setMessage($appResults->firstChild->firstChild->firstChild->nodeValue);
            return $flightItinerary;
        }

        $priceComparison = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:EnhancedAirBookRS/xmlns:OTA_AirPriceRS/xmlns:PriceComparison");
        $flightItinerary->setPrice(strval($priceComparison->item(0)->getAttribute('AmountReturned')));

        $pricedItinerary = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:EnhancedAirBookRS/xmlns:OTA_AirPriceRS/xmlns:PriceQuote/xmlns:PricedItinerary");
        if ($pricedItinerary && $pricedItinerary->length) {
            $flightItinerary->setPrice(strval($pricedItinerary->item(0)->getAttribute('TotalAmount')));
            $flightItinerary->getCurrencyModel()->setCode(strval($pricedItinerary->item(0)->getAttribute('CurrencyCode')));
        }

        return $flightItinerary;
    }

    /**
     * Parsing passengerDetailsResponse
     *
     * @param array $passengerDetailsResponse: Response from passengerDetailsRequest
     *
     * @return Object get status and get the pnr code created by sabre
     */
    public function parsePassengerDetailsResponse($passengerDetailsResponse)
    {
        if (empty($passengerDetailsResponse['response_text'])) {
            die("Please specify xml file to parse.\n");
        }

        $error = $this->errorRsHandler($passengerDetailsResponse['response_text']);
        if ($error->getStatus() == 'error') {
            return $error;
        }

        $xpath = $this->utils->xmlString2domXPath($passengerDetailsResponse['response_text']);

        $responseNode = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:PassengerDetailsRS");
        $responseNode = $responseNode->item(0);
        $appResults   = $responseNode->firstChild;
        $appResult    = strtolower($appResults->firstChild->localName);

        $flightDetails = new FlightDetails();
        $flightDetails->setStatus($appResult);

        if ($appResult == 'error') {
            $flightDetails->setMessage($appResults->firstChild->firstChild->firstChild->nodeValue);
        } elseif ($appResult == 'errors') {
            $messages = array();
            foreach ($appResults->firstChild->childNodes as $error) {
                $messages[] = $error->getAttribute('ShortText');
            }
            $flightDetails->setMessage($messages);
        } else {
            $pnr = $xpath->query('./xmlns:ItineraryRef', $responseNode);
            if ($pnr && $pnr->length) {
                $flightDetails->getPassengerNameRecordModel()->setPNR($pnr->item(0)->getAttribute('ID'));
            } else {
                $flightDetails->setStatus('error');
                $flightDetails->setMessage($responseNode->firstChild->firstChild->nextSibling->firstChild->firstChild->nodeValue);
            }

            $flightSegment = $xpath->query('./xmlns:TravelItineraryReadRS/xmlns:TravelItinerary/xmlns:ItineraryInfo/xmlns:ReservationItems/xmlns:Item/xmlns:FlightSegment', $responseNode);
            if ($flightSegment && $flightSegment->length > 0) {
                $segmentArr = [];
                foreach ($flightSegment as $segment) {
                    $segment_number = $segment->getAttribute('SegmentNumber');
                    $supplier       = $xpath->query('./xmlns:SupplierRef', $segment);
                    if ($supplier && $supplier->length) {
                        $ref                               = explode('*', $supplier->item(0)->getAttribute('ID'));
                        $segmentArr[(int) $segment_number] = (sizeof($ref) > 1) ? $ref[1] : $ref[0];
                    }
                }
                $flightDetails->getPassengerNameRecordModel()->setAirlinePNR($segmentArr);
                $itineraryPricing = $xpath->query('./xmlns:TravelItineraryReadRS/xmlns:TravelItinerary/xmlns:ItineraryInfo/xmlns:ItineraryPricing', $responseNode);

                $pricingInfo = array();
                if ($itineraryPricing && $itineraryPricing->length) {
                    $itineraryPricing = $itineraryPricing->item(0);
                    $totals           = $xpath->query('./xmlns:PriceQuoteTotals', $itineraryPricing);
                    if ($totals && $totals->length) {
                        foreach ($totals as $total) {
                            foreach ($total->childNodes as $value) {
                                if ($value->nodeName == 'TotalFare') {
                                    $flightDetails->getFlightIteneraryGroupedModel()->setPrice($value->getAttribute('Amount'));
                                    $pricingInfo['TotalFare'] = $value->getAttribute('Amount');
                                }
                            }
                        }
                    }

                    if (!$pricingInfo['TotalFare']) {
                        $totalFare = $xpath->query('./xmlns:PriceQuote/xmlns:PricedItinerary/xmlns:AirItineraryPricingInfo/xmlns:ItinTotalFare/xmlns:TotalFare', $itineraryPricing);
                        if ($totalFare && $totalFare->length) {
                            $totalFare = $totalFare->item(0);
                            $flightDetails->getFlightIteneraryGroupedModel()->setPrice($totalFare->getAttribute('Amount'));
                            $flightDetails->getFlightIteneraryGroupedModel()->setCurrency($totalFare->getAttribute('CurrencyCode'));
                        }
                    }
                }
            }
        }

        return $flightDetails;
    }

    /**
     * This function we change our PCC, to the IATA Provider PCC, in this case we are changing from our PCC, to DANATA PCC to issue ticket.
     *
     * @param array $contextChangeResponse
     *
     * @return object contains the main result is the status
     */
    public function parseContextChangeResponse($contextChangeResponse)
    {
        if (empty($contextChangeResponse['response_text'])) {
            die("Please specify xml file to parse.\n");
        }

        $response = $this->errorRsHandler($contextChangeResponse['response_text']);
        if ($response->getStatus() == 'error') {
            return $response;
        }

        $xpath         = $this->utils->xmlString2domXPath($contextChangeResponse['response_text']);
        $checkResponse = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:ContextChangeRS");
        $appResults    = $checkResponse->item(0)->firstChild;
        $status        = strtolower($appResults->firstChild->localName);

        $response->setStatus($status);

        if ($status == 'error') {
            $response->setMessage($appResults->firstChild->firstChild->firstChild->nodeValue);
        }

        return $response;
    }

    /**
     * This function parse response from createTravelItineraryRequest
     *
     * @param array $travelItineraryResponse
     * @param array $options
     *
     * @return object contains all the information about the PNR
     */
    public function parseTravelItineraryResponse($travelItineraryResponse, $options)
    {
        if (empty($travelItineraryResponse['response_text'])) {
            die("Please specify xml file to parse.\n");
        }

        $response = $this->errorRsHandler($travelItineraryResponse['response_text']);
        if ($response->getStatus() == 'error') {
            return $response;
        }

        $xpath                 = $this->utils->xmlString2domXPath($travelItineraryResponse['response_text']);
        $itineraryReadResponse = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:TravelItineraryReadRS");
        $itineraryReadResponse = $itineraryReadResponse->item(0);
        $appResult             = $itineraryReadResponse->firstChild->firstChild;
        $status                = strtolower($appResult->localName);

        $response->setStatus($status);
        if ($status == 'error') {
            $response->setMessage($appResult->firstChild->nodeValue);
            return $response;
        } elseif ($status == 'errors') {
            $messages = array();
            foreach ($appResult->childNodes as $error) {
                $messages[] = $error->getAttribute('ShortText');
            }
            $response->setMessage($messages);
            return $response;
        }

        //if success, then parse the response
        $flightTicket = new FlightTicket();
        $flightTicket->setStatus($status);

        $itineraryInfo = $xpath->query("./xmlns:TravelItinerary/xmlns:ItineraryInfo", $itineraryReadResponse);
        $itineraryInfo = $itineraryInfo->item(0);

        if ($options['check_airline_locators']) {
            $reservationItems = $xpath->query('./xmlns:ReservationItems/xmlns:Item', $itineraryInfo);
            if ($reservationItems && $reservationItems->length) {
                /*
                 * flightSegments holds its own status, it won't affect the status value of $data; it has been set this way to allow the caller to ignore the information it holds, even if related options have been given.
                 * The status of flightSegments was also added so that one can differentiate between the success of the API call, and the Airline Locators existence/pattern.
                 * When option check_airline_locators is false, no flight_segments key will be returned in the response, because in that case, there's no need for such data.
                 */
                $segmentsArr = array();
                foreach ($reservationItems as $riIndex => $reservationItem) {

                    $rph = $reservationItem->getAttribute('RPH');

                    $flightSegment = $xpath->query('./xmlns:FlightSegment', $reservationItem);
                    if (!$flightSegment || !$flightSegment->length) continue;

                    $flightSegment = $flightSegment->item(0);

                    $segment = new FlightSegment();
                    $segment->setSegmentNumber($flightSegment->getAttribute('SegmentNumber'));

                    // eTicket is an attribute of FlightSegment, it's a boolean flag, it must hold the value "true"
                    if (!$flightSegment->hasAttribute('eTicket') || $flightSegment->getAttribute('eTicket') != "true") {
                        $segment->setStatus('error');
                        $segment->setCode(1);
                        $segment->setMessage('No eTicket flag found, or invalid eTicket flag value');
                        $segmentsArr[$rph] = $segment;
                        break;
                    }
                    // SupplierRef of each FlightSegment must contain an ID attribute, which must contain the Airline Locators (a string formatted as XXXX*YYYYYY)
                    $supplier_ref = $xpath->query('./xmlns:SupplierRef', $flightSegment);
                    if (!$supplier_ref || !$supplier_ref->length) {
                        $segment->setStatus('error');
                        $segment->setCode(2);
                        $segment->setMessage('No Airline Locator found');
                        $segmentsArr[$rph] = $segment;
                        break;
                    }

                    if (!$supplier_ref->item(0)->hasAttribute('ID')) {
                        $segment->setStatus('error');
                        $segment->setCode(3);
                        $segment->setMessage('No Airline Locator found');
                        $segmentsArr[$rph] = $segment;
                        break;
                    }

                    $airlineLocators = $supplier_ref->item(0)->getAttribute('ID');
                    if ($options['fetch_airline_locators']) {
                        $matching_result = preg_match('/([^*]{4})\*([^*]{6})/', $airlineLocators, $locators);
                        if ($matching_result !== false && $matching_result) {
                            $segment->getOperatingAirline()->setId($locators[1]);
                            $segment->getOperatingAirline()->setAirlineName($locators[0]);
                            $segment->getOperatingAirline()->setAirlineCode($locators[2]);
                        } else {
                            $segment->setStatus('error');
                            $segment->setCode(4);
                            $segment->setMessage("Airline Locator [RPH:: $rph]:: pattern mismatch:: $airline_locators");
                            $segmentsArr[$rph] = $segment;
                            break;
                        }
                    } else {
                        // no need to fetch the Airline Locators, just check for the pattern
                        $matching_result   = preg_match('/[^*]{4}\*[^*]{6}/', $airline_locators);
                        $segment->setStatus('error');
                        $segment->setCode(4);
                        $segmentsArr[$rph] = $segment;
                        break;
                    }
                    $segmentsArr[$rph] = $segment;
                }
                if ($segmentsArr) $flightTicket->setFlightSegmentModel($segmentsArr);
            }
        }
        else {
            /** When we need to extract tickets, we don't need to check for Airline Locator(s),
             * as we should have checked it earlier in the ticket issuing process.
             */
            $tickets = $xpath->query("./xmlns:Ticketing", $itineraryInfo);
            if ($tickets && $tickets->length) {
                $ticketNum = array();
                foreach ($tickets as $ticket) {
                    if ($ticket->getAttribute('RPH') && intval($ticket->getAttribute('RPH') > 1)) {
                        $ticketNum[$ticket->getAttribute('RPH')] = substr($ticket->getAttribute('eTicketNumber'), 3, 13);
                    }
                }
                $flightTicket->setTicketNumber($ticketNum);
            }
        }

        return $flightTicket;
    }

    /**
     * This function parse the response from DesignatePrinterRequest
     *
     * @param array $designatePrinterResponse
     *
     * @return object contains the main result is the status
     */
    public function parseDesignatePrinterResponse($designatePrinterResponse)
    {
        if (empty($designatePrinterResponse['response_text'])) {
            die("Please specify xml file to parse.\n");
        }

        $response = $this->errorRsHandler($designatePrinterResponse['response_text']);
        if ($response->getStatus() == 'error') {
            return $response;
        }

        $xpath         = $this->utils->xmlString2domXPath($designatePrinterResponse['response_text']);
        $checkResponse = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:DesignatePrinterRS");
        $appResults    = $checkResponse->item(0)->firstChild;
        $status        = strtolower($appResults->firstChild->localName);

        $response->setStatus($status);
        if ($status == 'error') {
            $response->setMessage($appResults->firstChild->firstChild->nodeValue);
        }

        return $response;
    }

    /**
     * This function is to parse the issued ticket chosen by the user from airTicketRequest
     *
     * @param array $airTicketResponseResponse
     *
     * @return object that result is the status, message
     */
    public function parseAirTicketResponseResponse($airTicketResponseResponse)
    {
        if (empty($airTicketResponseResponse['response_text'])) {
            die("Please specify xml file to parse.\n");
        }

        $response = $this->errorRsHandler($airTicketResponseResponse['response_text']);
        if ($response->getStatus() == 'error') {
            return $response;
        }

        $xpath         = $this->utils->xmlString2domXPath($airTicketResponseResponse['response_text']);
        $checkResponse = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:AirTicketRS");
        $appResults    = $checkResponse->item(0)->firstChild;
        $status        = $appResults->getAttribute('status');

        $response->setStatus($status);
        if ($status != 'Complete') {
            $response->setMessage($appResults->firstChild->firstChild->nodeValue);
        }

        return $response;
    }

    /**
     * Parsing response from EndTransactionRequest
     *
     * @param string $endTransactionRespone
     *
     * @return PassengerNameRecord object
     */
    public function parseEndTransactionRespone($endTransactionRespone)
    {
        if (empty($endTransactionRespone['response_text'])) {
            die("Please specify xml file to parse.\n");
        }

        $response = $this->errorRsHandler($endTransactionRespone['response_text']);
        if ($response->getStatus() == 'error') {
            return $response;
        }

        $xpath        = $this->utils->xmlString2domXPath($endTransactionRespone['response_text']);
        $responseNode = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:EndTransactionRS");
        $responseNode = $responseNode->item(0);

        $appResults = $responseNode->firstChild;
        $status     = strtolower($appResults->getAttribute('status'));

        $passengerNameRecord = new PassengerNameRecord();
        $passengerNameRecord->setStatus($status);

        if ($status != 'complete') {
            $passengerNameRecord->setMessage($appResults->firstChild->firstChild->nodeValue);
        }

        if (strtolower($appResults->firstChild->localName) == 'success') {
            $pnr = $xpath->query("./xmlns:ItineraryRef", $responseNode);
            if ($pnr && $pnr->length) {
                $passengerNameRecord->setPNR($pnr->item(0)->getAttribute('ID'));
            } else {
                $passengerNameRecord->setStatus('error');
                $passengerNameRecord->setMessage('PNR not defined');
            }
        }
        return $passengerNameRecord;
    }

    /**
     * This Function that handle ERROR responses From Sabre Provider, it parse the XML response, and get the error Message From it.
     * @param string $responseText
     * @return object
     */
    public function errorRsHandler($responseText)
    {
        $xpath      = $this->utils->xmlString2domXPath($responseText);
        $foundError = false;
        $flightVO   = new flightVO();
        $flightVO->setStatus('success');

        if (isset($responseText['response_error']) && $responseText['response_error'] == RESPONSE_ERROR) {
            $foundError = true;
            $flightVO->setMessage('Could not connect to server please try again');
        } else {
            $soap_env = 'SOAP-ENV';
            if (strpos($responseText, $soap_env, 1) === false) {
                $soap_env = 'soap-env';
            }

            try {
                $faultCodeEl = $xpath->query("//$soap_env:Envelope/$soap_env:Body/$soap_env:Fault/faultcode");

                if ($faultCodeEl && $faultCodeEl->length) {
                    $foundError = true;
                    $faultCode  = explode(".", $faultCodeEl->item(0)->nodeValue);
                    $flightVO->setCode($faultCode[1]);
                }
            } catch (\Exception $e) {
                $flightVO->setCode("curlError");
                $flightVO->setMessage($e->getMessage());
                $foundError = true;
            }

            try {
                $faultString = $xpath->query("//$soap_env:Envelope/$soap_env:Body/$soap_env:Fault/faultstring");

                if ($faultString && $faultString->length) {
                    $foundError = true;
                    $flightVO->setMessage($faultString->item(0)->nodeValue);
                }
            } catch (\Exception $e) {
                $foundError = true;
                $flightVO->setMessage('operationTimedOut');
            }
        }

        if ($foundError) $flightVO->setStatus('error');

        return $flightVO;
    }

    /**
     * Returns memory usage from /proc<PID>/status in bytes.
     *
     * @return int|bool sum of VmRSS and VmSwap in bytes. On error returns false.
     */
    public function memory_get_process_usage()
    {
        $status = file_get_contents('/proc/'.getmypid().'/status');

        $matchArr = array();
        preg_match_all('~^(VmRSS|VmSwap):\s*([0-9]+).*$~im', $status, $matchArr);

        if (!isset($matchArr[2][0]) || !isset($matchArr[2][1])) {
            return false;
        }

        return intval($matchArr[2][0]) + intval($matchArr[2][1]);
    }

    /**
     * Parse response from VoidTicketRQ API
     * @param $response
     *
     * @return object flightVO
     */
    public function parseVoidAirTicketResponse($response) {

        $flightVO   = new flightVO();

        if (empty($response['response_text'])) {
            die("Please specify xml file to parse.\n");
        }

        $error = $this->errorRsHandler($response['response_text']);

        if ($error->getStatus() == 'error') {
            return $error;
        }

        $xpath = $this->utils->xmlString2domXPath($response['response_text']);

        $responseNode = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:VoidTicketRS");
        $responseNode = $responseNode->item(0);

        $appResults = $responseNode->firstChild;

        $data['app_results'] = array(
            'status' => $appResults->getAttribute('status'),
            'message' => ''
        );

        if ($appResults->getAttribute('status') != 'Complete') {
            $message = $xpath->query("./stl:Error/stl:SystemSpecificResults/stl:Message", $appResults);

            if ($message && $message->length) {
                $data['app_results']['message'] = $message->item(0)->nodeValue;
            }
        }

        $flightVO->setStatus(strtolower($appResults->firstChild->localName));

        if (strpos($data['app_results']['message'], 'NUMBER PREVIOUSLY VOIDED') !== false) {
            $flightVO->setStatus('success');
        }

        return $flightVO;

    }

    /**
     * Parse response from OTA_CancelRQ API
     * @param $response
     *
     * @return object flightVO
     */
    public function parseOTACancelResponse($response) {

        $flightVO   = new flightVO();

        if (empty($response['response_text'])) {
            die("Please specify xml file to parse.\n");
        }

        $error = $this->errorRsHandler($response['response_text']);

        if ($error->getStatus() == 'error') {
            return $error;
        }

        if ($response['response_error']) {
            $flightVO->setStatus('error');
        }

        $xpath = $this->utils->xmlString2domXPath($response['response_text']);
        $checkResponse = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:OTA_CancelRS");
        $appResult = $checkResponse->item(0)->firstChild->firstChild->localName;

        $flightVO->setStatus(strtolower($appResult));

        return $flightVO;
    }

}
