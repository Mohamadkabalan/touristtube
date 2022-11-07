<?php

namespace FlightBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Passenger
 *
 * This class serves as getter and setter for Passenger Details
 */

class Passenger
{

	private $pnr;
	private $pnrId;
	private $paymentUuid;
	private $pnrUserId;
	private $pnrFirstName;
	private $pnrSurname;
	private $pnrCountryOfResidence;
	private $pnrEmail;
	private $pnrMobile;
	private $pnrAlternativeNumber;
	private $pnrSpecialRequirement;
	private $pnrStatus;

	private $passengerType;
	private $passengerGender;
	private $passengerFirstName;
	private $passengerSurName;
	
	private $passengerDob;
	private $passengerFareCalcLine;
	private $passengerTicketNumber;
	private $passengerTicketRph;
	private $passengerTicketStatus;
	private $passengerLeavingBaggageInfo;
	private $passegnerReturningBaggageInfo;
	private $passengerPassportNo;
	private $passengerPassportExpiry;
	private $passengerPassportIssueCountry;
	private $passengerPassportNationalityCountry;
	private $passengerIdno;
	private $passengerName;
	
	private $passengers;

	private $message;
	private $messages;
	private $faultCode;
	private $pricingInfo;
	private $passengerTypePricingInfo;
    private $fareBasisCodes;

	private $request;
	private $response;

	private $airline_pnr;
	
	public function __construct()
	{
		$this->passengers = new ArrayCollection();
	}

	public function setPnr($pnr)
	{
		$this->pnr = $pnr;
	}

	public function getPnr(){
		return $this->pnr;
	}

	public function setPnrId($pnrId)
	{
		$this->pnrId = $pnrId;
	}

	public function getPnrId()
	{
		return $this->pnrId;
	}

	public function setPaymentUuid($paymentUuid)
	{
		$this->paymentUuid = $paymentUuid;
	}

	public function getPaymentUuid(){
		return $this->paymentUuid;
	}

	public function setPnrUserId($pnrUserId)
	{
		$this->pnrUserId = $pnrUserId;
	}

	public function getPnrUserId(){
		return $this->pnrUserId;
	}

	public function setPnrFirstName($pnrFirstName)
	{
		$this->pnrFirstName = $pnrFirstName;
	}

	public function getPnrFirstName(){
		return $this->pnrFirstName;
	}

	public function setPnrSurname($pnrSurname)
	{
		$this->pnrSurname = $pnrSurname;
	}

	public function getPnrSurname(){
		return $this->pnrSurname;
	}

	public function setPnrCountryOfResidence($pnrCountryOfResidence)
	{
		$this->pnrCountryOfResidence = $pnrCountryOfResidence;
	}

	public function getPnrCountryOfResidence(){
		return $this->pnrCountryOfResidence;
	}

	public function setPnrEmail($pnrEmail)
	{
		$this->pnrEmail = $pnrEmail;
	}

	public function getPnrEmail(){
		return $this->pnrEmail;
	}

	public function setPnrMobile($pnrMobile)
	{
		$this->pnrMobile = $pnrMobile;
	}

	public function getPnrMobile(){
		return $this->pnrMobile;
	}

	public function setPnrAlternativeNumber($pnrAlternativeNumber)
	{
		$this->pnrAlternativeNumber = $pnrAlternativeNumber;
	}

	public function getPnrAlternativeNumber(){
		return $this->pnrAlternativeNumber;
	}

	public function setPnrSpecialRequirement($pnrSpecialRequirement)
	{
		$this->pnrSpecialRequirement = $pnrSpecialRequirement;
	}

	public function getPnrSpecialRequirement(){
		return $this->pnrSpecialRequirement;
	}

	public function setPnrStatus($pnrStatus)
	{
		$this->pnrStatus = $pnrStatus;
	}

	public function getPnrStatus(){
		return $this->pnrStatus;
	}

	public function setPassengerType($passengerType)
	{
		$this->passengerType = $passengerType;
	}
	
	public function getPassengerType()
	{
		return $this->passengerType;
	}
	
	public function setPassengerGender($passengerGender)
	{
		$this->passengerGender = $passengerGender;
	}
	
	public function getPassengerGender()
	{
		return $this->passengerGender;
	}
	
	public function setPassengerFirstName($passengerFirstName)
	{
		$this->passengerFirstName = $passengerFirstName;
	}
	
	public function getPassengerFirstName()
	{
		return $this->passengerFirstName;
	}
	
	public function setPassengerSurname($passengerSurName)
	{
		$this->passengerSurName = $passengerSurName;
	}
	
	public function getPassengerSurname()
	{
		return $this->passengerSurName;
	}
	
	public function setPassengerDob($passengerDob)
	{
		$this->passengerDob = $passengerDob;
	}
	
	public function getPassengerDob()
	{
		return $this->passengerDob;
	}
	
	public function setPassengerFareCalcLine($passengerFareCalcLine)
	{
		$this->passengerFareCalcLine = $passengerFareCalcLine;
	}
	
	public function getPassengerFareCalcLine()
	{
		return $this->passengerFareCalcLine;
	}
	
	public function setPassengerTicketNumber($passengerTicketNumber)
	{
		$this->passengerTicketNumber = $passengerTicketNumber;
	}
	
	public function getPassengerTicketNumber()
	{
		return $this->passengerTicketNumber;
	}
	
	public function setPassengerTicketRph($passengerTicketRph)
	{
		$this->passengerTicketRph = $passengerTicketRph;
	}
	
	public function getPassengerTicketRph()
	{
		return $this->passengerTicketRph;
	}
	
	public function setPassengerTicketStatus($passengerTicketStatus)
	{
		$this->passengerTicketStatus = $passengerTicketStatus;
	}
	
	public function getPassengerTicketStatus()
	{
		return $this->passengerTicketStatus;
	}
	
	
	public function setPassengerLeavingBaggageInfo($passengerLeavingBaggageInfo)
	{
		$this->passengerLeavingBaggageInfo = $passengerLeavingBaggageInfo;
	}
	
	public function getPassengerLeavingBaggageInfo()
	{
		return $this->passengerLeavingBaggageInfo;
	}
	
	public function setPassengerReturningBaggageInfo($passegnerReturningBaggageInfo)
	{
		$this->passegnerReturningBaggageInfo = $passegnerReturningBaggageInfo;
	}
	
	public function getPassengerReturningBaggageInfo()
	{
		return $this->passegnerReturningBaggageInfo;
	}
	
	public function setPassengerPassportNo($passengerPassportNo)
	{
		$this->passengerPassportNo = $passengerPassportNo;
	}
	
	public function getPassengerPassportNo()
	{
		return $this->passengerPassportNo;
	}
	
	public function setPassengerPassportExpiry($passengerPassportExpiry)
	{
		$this->passengerPassportExpiry = $passengerPassportExpiry;
	}
	
	public function getPassengerPassportExpiry()
	{
		return $this->passengerPassportExpiry;
	}
	
	public function setPassengerPassportIssueCountry($passengerPassportIssueCountry)
	{
		$this->passengerPassportIssueCountry = $passengerPassportIssueCountry;
	}
	
	public function getPassengerPassportIssueCountry()
	{
		return $this->passengerPassportIssueCountry;
	}
	
	public function setPassengerPassportNationalityCountry($passengerPassportNationalityCountry)
	{
		$this->passengerPassportNationalityCountry = $passengerPassportNationalityCountry;
	}
	
	public function getPassengerPassportNationalityCountry()
	{
		return $this->passengerPassportNationalityCountry;
	}
	
	public function setPassengerIdno($passengerIdno)
	{
		$this->passengerIdno = $passengerIdno;
	}
	
	public function getPassengerIdno()
	{
		return $this->passengerIdno;
	}
	
	public function setPassengerName($passengerName)
	{
		$this->passengerName = $passengerName;
	}
	
	public function getPassengerName()
	{
		return $this->passengerName;
	}

	public function setMessage($message)
	{
		$this->message = $message;
	}

	public function getMessage()
	{
		return $this->message;
	}

	public function setMessages($messages)
	{
		$this->messages = $messages;
	}

	public function getMessages()
	{
		return $this->messages;
	}

	public function setFaultCode($faultCode)
	{
		$this->faultCode = $faultCode;
	}

	public function getFaultCode()
	{
		return $this->faultCode;
	}

	public function setPricingInfo($pricingInfo)
	{
		$this->pricingInfo = $pricingInfo;
	}

	public function getPricingInfo()
	{
		return $this->pricingInfo;
	}
	
	public function getPassengerTypePricingInfo()
	{
		return $this->passengerTypePricingInfo;
	}
	
	public function setPassengerTypePricingInfo($passengerTypePricingInfo)
	{
		$this->passengerTypePricingInfo = $passengerTypePricingInfo;
	}

	public function setResponse($response)
	{
		$this->response = $response;
	}

	public function getResponse()
	{
		return $this->response;
	}

	public function setRequest($request)
	{
		$this->request = $request;
	}

	public function getRequest()
	{
		return $this->request;
	}
	
	public function setPassengers($passengers)
	{
		$this->passengers[] = $passengers;
	}
	
	public function getPassengers()
	{
		return $this->passengers;
	}

	public function setAirlinePnr($airline_pnr)
	{
		$this->airline_pnr = $airline_pnr;
	}
	
	public function getAirlinePnr()
	{
		return $this->airline_pnr;
	}

    public function setFareBasisCodes($fareBasisCodes)
    {
        $this->fareBasisCodes = $fareBasisCodes;
    }

    public function getFareBasisCodes()
    {
        return $this->fareBasisCodes;
    }

}
