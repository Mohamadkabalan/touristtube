<?php
namespace FlightBundle\vendors\sabre;

use FlightBundle\Model\Passenger;
use FlightBundle\Model\EmailData;
use TTBundle\Utils\Utils;
use Symfony\Component\DependencyInjection\Container;

class PassengerNormaliser
{
	private $passenger;
	private $utils;
	private $emailData;
	private $container;

	public function __construct(Passenger $passenger, Utils $utils, EmailData $emailData, Container $container)
	{
		$this->passenger = $passenger;
		$this->utils = $utils;
		$this->emailData = $emailData;
		$this->container = $container;
        $this->currencyService = $this->container->get('CurrencyService');
	}

	public function createPassengerDetailsRequest($passengerDetails, $getPassengerDetailsRequest)
	{
		//$data = array("faultcode" => "", "status" => "", "message" => "", "messages" => "", "pnrId" => "", "pricingInfo" => [], "request" => $getPassengerDetailsRequest['request_body'], "response" => $getPassengerDetailsRequest['response_text']);

//	        echo '<textarea cols="120" rows="20">' . $getPassengerDetailsRequest['response_text'] . '</textarea>';
//	        echo '<textarea cols="120" rows="20">' . $getPassengerDetailsRequest['request_body'] . '</textarea>';

		$this->passenger->setMessage(null);
		
		$this->passenger->setRequest($getPassengerDetailsRequest['request_body']);
		$this->passenger->setResponse($getPassengerDetailsRequest['response_text']);

        if ($getPassengerDetailsRequest['response_error'] == RESPONSE_ERROR)
        {
            //$data["status"] = 'error';
            //$data['message'] = 'Could not connect to server please try again';
            $this->passenger->setPnrStatus('error');
            $this->passenger->setMessage('Could not connect to server please try again');
            return $this->passenger;
        }

		$errorRs = $this->errorRsHandler($getPassengerDetailsRequest['response_text']);
		if ($errorRs)
		{
		    /*$data['faultcode'] = $errorRs['faultcode'];
		    $data['status'] = $errorRs['status'];
		    $data['message'] = $errorRs['message'];*/
		    $this->passenger->setFaultCode($errorRs['faultcode']);
		    $this->passenger->setPnrStatus($errorRs['status']);
		    $this->passenger->setMessage($errorRs['message']);
		    return $this->passenger;
		}



		$xpath = $this->utils->xmlString2domXPath($getPassengerDetailsRequest['response_text']);
		$responseNode = $xpath->query("/soap-env:Envelope/soap-env:Body/xmlns:PassengerDetailsRS");
		$responseNode = $responseNode->item(0);

		$appResults = $responseNode->firstChild;
		
		$this->passenger->setPnrStatus(strtolower($appResults->firstChild->localName));
		
		$pnr = $xpath->query('./xmlns:ItineraryRef', $responseNode);
		
		if ($pnr && $pnr->length)
		{
			$this->passenger->setPnrId($pnr->item(0)->getAttribute('ID'));
		}
		else if ($this->passenger->getPnrStatus() == 'success')
		{
			$this->passenger->setPnrStatus('error');
			
			$this->passenger->setMessage($appResults->firstChild->nextSibling->firstChild->firstChild->nodeValue);
		}
		
		if ($this->passenger->getPnrStatus() == 'success')
		{
		    $pricingInfo = array('BaseFare' => 0, 'EquivFare' => 0, 'Taxes' => 0, 'TotalFare' => 0, 'CurrencyCode' => '');
			$passengerTypePricingInfo = array();
			// $fareBasisCodes = array();
			
			$itineraryInfo = $xpath->query('./xmlns:TravelItineraryReadRS/xmlns:TravelItinerary/xmlns:ItineraryInfo', $responseNode);
			
			if ($itineraryInfo && $itineraryInfo->length)
			{
				$itineraryInfo = $itineraryInfo->item(0);
				
				$itineraryPricing = $xpath->query('./xmlns:ItineraryPricing', $itineraryInfo);
				
				if ($itineraryPricing && $itineraryPricing->length)
				{
                    $itineraryPricing = $itineraryPricing->item(0);
					
					$airItineraryPricingInfos = $xpath->query('./xmlns:PriceQuote/xmlns:PricedItinerary/xmlns:AirItineraryPricingInfo', $itineraryPricing);

					if ($airItineraryPricingInfos && $airItineraryPricingInfos->length)
					{

						foreach ($airItineraryPricingInfos as $airItineraryPricingInfo)
						{
							$currentPassengerType = 'ADT';
							
							$passengerTypeQuantity = $xpath->query('./xmlns:PassengerTypeQuantity', $airItineraryPricingInfo);
							if ($passengerTypeQuantity && $passengerTypeQuantity->length)
							{
								$passengerTypeQuantity = $passengerTypeQuantity->item(0);
								
								$currentPassengerType = $passengerTypeQuantity->getAttribute('Code');
								
								$passengerTypePricingInfo[$currentPassengerType]['Quantity'] = (int) $passengerTypeQuantity->getAttribute('Quantity');
								
								$passengerTypePricingInfo[$currentPassengerType]['BaseFare'] = array('CurrencyCode' => '', 'Amount' => 0);
								$passengerTypePricingInfo[$currentPassengerType]['EquivFare'] = array('CurrencyCode' => '', 'Amount' => 0); 
								$passengerTypePricingInfo[$currentPassengerType]['Taxes'] = array('CurrencyCode' => '', 'Amount' => 0); 
								$passengerTypePricingInfo[$currentPassengerType]['TotalFare'] = array('CurrencyCode' => '', 'Amount' => 0);
							}
							
							$itinTotalFares = $xpath->query('./xmlns:ItinTotalFare', $airItineraryPricingInfo);

							if ($itinTotalFares && $itinTotalFares->length)
							{
								$currencyCode = $xpath->query('./xmlns:TotalFare', $itinTotalFares->item(0));
                                $currencyCode = $currencyCode->item(0)->getAttribute('CurrencyCode');
								
								foreach ($itinTotalFares as $itinTotalFare)
								{
									foreach ($itinTotalFare->childNodes as $fareNode)
									{
										switch ($fareNode->localName)
										{
											case 'Taxes':
												$passengerTypePricingInfo[$currentPassengerType][$fareNode->localName]['Amount'] = $this->currencyService->exchangeAmount($fareNode->firstChild->getAttribute('Amount'), $currencyCode, 'AED');
												break;
											/*
											case 'TotalFare':
												$passengerTypePricingInfo[$currentPassengerType]['CurrencyCode'] = $fareNode->getAttribute('CurrencyCode');
											*/
											case 'BaseFare':
                                                $passengerTypePricingInfo[$currentPassengerType]['BaseFare']=$fareNode->getAttribute('Amount');
                                                break;

											default:
												if ($fareNode->hasAttribute('Amount') && array_key_exists($fareNode->localName, $passengerTypePricingInfo[$currentPassengerType]))
												{
													$passengerTypePricingInfo[$currentPassengerType][$fareNode->localName]['CurrencyCode'] = 'AED'; // $fareNode->getAttribute('CurrencyCode');
													$passengerTypePricingInfo[$currentPassengerType][$fareNode->localName]['Amount'] = $this->currencyService->exchangeAmount($fareNode->getAttribute('Amount'), $currencyCode, 'AED');
												}
										}
									}
								}
								
								$passengerTypePricingInfo[$currentPassengerType]['Taxes']['CurrencyCode'] = $passengerTypePricingInfo[$currentPassengerType]['TotalFare']['CurrencyCode'];
							}

							// Populate FareBasis Code from the PTC_FareBreakdown
							$flightSegments = $xpath->query('./xmlns:PTC_FareBreakdown/xmlns:FlightSegment', $airItineraryPricingInfo);
							
							if($flightSegments && $flightSegments->length > 0)
							{
								foreach($flightSegments as $segment)
								{
									if (!$segment->hasAttribute('SegmentNumber'))
										continue;
									
									$segment_number = $segment->getAttribute('SegmentNumber');
									$fareBasisCode = $xpath->query('./xmlns:FareBasis', $segment);
									
									if($fareBasisCode && $fareBasisCode->length)
										$passengerTypePricingInfo[$currentPassengerType]['FareBasisCode'][$segment_number] = $fareBasisCode->item(0)->getAttribute('Code');
										// $fareBasisCodes[$currentPassengerType][$segment_number] = $fareBasisCode->item(0)->getAttribute('Code');
									
									$passengerTypePricingInfo[$currentPassengerType]['ResBookDesigCode'][$segment_number] = $segment->getAttribute('ResBookDesigCode');
								}
							}
						}
						
						// $this->passenger->setFareBasisCodes($fareBasisCodes);

					}


                    $priceQuoteTotals = $xpath->query('./xmlns:PriceQuoteTotals', $itineraryPricing);

                    if ($priceQuoteTotals && $priceQuoteTotals->length)
                    {
                        $priceQuoteTotals = $priceQuoteTotals->item(0);

                        foreach ($priceQuoteTotals->childNodes as $fareNode)
                        {
                            switch ($fareNode->localName)
                            {

                                case 'Taxes':
                                    $pricingInfo[$fareNode->localName] = $this->currencyService->exchangeAmount($fareNode->firstChild->getAttribute('Amount'), $currencyCode, 'AED');
                                    break;
                                case 'BaseFare':

                                    $pricingInfo[$fareNode->localName] = $fareNode->getAttribute('Amount');
                                    break;
                                default:
                                    if ($fareNode->hasAttribute('Amount') && array_key_exists($fareNode->localName, $pricingInfo))
                                        $pricingInfo[$fareNode->localName] = $this->currencyService->exchangeAmount($fareNode->getAttribute('Amount'), $currencyCode, 'AED');
                            }
                            $pricingInfo["CurrencyCode"]='AED';

                        }

                         $this->passenger->setPricingInfo($pricingInfo);
                    }
					
                    $this->passenger->setPassengerTypePricingInfo($passengerTypePricingInfo);

                    $this->passenger->setPricingInfo($pricingInfo);
				}
				
				/*
				// No airline locator can be retrieved at this stage!
				$flightSegment = $xpath->query('./xmlns:ReservationItems/xmlns:Item/xmlns:FlightSegment', $itineraryInfo);

				if($flightSegment && $flightSegment->length > 0)
				{
					$airline_locators = [];
					
					foreach($flightSegment as $segment)
					{
						$segment_number = $segment->getAttribute('SegmentNumber');
						$supplierRef = $xpath->query('./xmlns:SupplierRef', $segment);
						
						if($supplierRef && $supplierRef->length)
						{
							$airline_locator = explode('*', $supplierRef->item(0)->getAttribute('ID'));
							
							if (count($airline_locator) == 2)
								$airline_locators[(int) $segment_number] = $airline_locator[1];
							else
								$airline_locators[(int) $segment_number] = '';
						}
					}
					
					$this->passenger->setAirlinePnr($airline_locators);
				}
				*/
			}
		}
		else if ($this->passenger->getMessage() == null)
		{
			if ($this->passenger->getPnrStatus() == 'error')
			{
				$this->passenger->setMessage($appResults->firstChild->firstChild->firstChild->nodeValue);
			}
			else if ($this->passenger->getPnrStatus() == 'errors')
			{
				$messages = array();
				foreach ($appResults->firstChild->childNodes as $error)
				{
				   $messages[] = $error->getAttribute('ShortText');
				}
				
				$this->passenger->setMessages($messages);
			}
		}

		return $this->passenger;
	}

	/**
     * 
     * A *description* This Function that handle ERROR responses From Sabre Provider, it parse the XML response, and get the error Message From it. 
     * @param string $responseText
     * @return Array
     */
    public function errorRsHandler($responseText) {
        /**
         * @var array|null $errorRs is the array that contains the error parsed from XML response, in case of Error with Sabre SOAP API
         */
		$errorRs = array();
		
		/**
		 * @var boolean $found_error is a variable boolean defalt value is negative, it will be true in case an error is detected or an error response is given from the third party api Sabre
		 */
		$found_error = false;
		
		$soap_env = 'SOAP-ENV';
		
		if (strpos($responseText, $soap_env, 1) === false) {
		    $soap_env = 'soap-env';
		}
		
		/**
		 * @method string xmlString2domXpath(xml $responseText) function called from utils for fast parsing of an XML, in this case we check if the response contains an error node.
		 */
		$xpath = $this->utils->xmlString2domXPath($responseText);
		
		try {
		    $faultCodeEl = $xpath->query("//$soap_env:Envelope/$soap_env:Body/$soap_env:Fault/faultcode");

		    if ($faultCodeEl && $faultCodeEl->length) {
			$found_error = true;
			$faultCode = explode(".", $faultCodeEl->item(0)->nodeValue);
			$errorRs['faultcode'] = $faultCode[1];
		    }
		} catch (\Exception $e) {
		    $errorRs['error_message'] = $e->getMessage();
		    $errorRs['trace'] = $e->getTraceAsString();
		    $found_error = true;
		    $errorRs['faultcode'] = "curlError";
		}
		
		try {
		    $faultString = $xpath->query("//$soap_env:Envelope/$soap_env:Body/$soap_env:Fault/faultstring");

		    if ($faultString && $faultString->length) {
			$found_error = true;
			$errorRs['message'] = $faultString->item(0)->nodeValue;
		    }
		} catch (\Exception $e) {
		    $found_error = true;
		    $errorRs['message'] = 'operationTimedOut';
		}
		
		if ($found_error)
		    $errorRs['status'] = 'error';
		
		return $errorRs;
    }

}
