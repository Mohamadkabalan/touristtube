<?php
namespace FlightBundle\Model;

/**
 * This class will set email data to be used when sending flight details to email
 **/
class EmailData
{
	private $flightSegments;
	private $passengerDetails;
	private $price;
	private $currency;
	private $discountedPrice;
	private $baseFare;
	private $taxes;
	private $pnr;
	private $transactionId;
	private $specialRequirement;
	private $email;
	private $refundable;
	private $oneWay;
	private $multiDestination;
	private $enableCancelation;
	private $hotels;
	private $airlinePnr;

	public function setFlightSegments($flightSegments)
	{
		$this->flightSegments = $flightSegments;
	}

	public function getFlightSegments()
	{
		return $this->flightSegments;
	}

	public function setPassengerDetails($passengerDetails)
	{
		$this->passengerDetails = $passengerDetails;
	}

	public function getPassengerDetails()
	{
		return $this->passengerDetails;
	}

	public function setPrice($price)
	{
		$this->price = $price;
	}

	public function getPrice()
	{
		return $this->price;
	}

	public function setCurrency($currency)
	{
		$this->currency = $currency;
	}

	public function getCurrency()
	{
		return $this->currency;
	}

	public function setDiscountedPrice($discountedPrice)
	{
		$this->discountedPrice = $discountedPrice;
	}

	public function getDiscountedPrice()
	{
		return $this->discountedPrice;
	}

	public function setBaseFare($baseFare)
	{
		$this->baseFare = $baseFare;
	}

	public function getBaseFare()
	{
		return $this->baseFare;
	}

	public function setTaxes($taxes)
	{
		$this->taxes = $taxes;
	}

	public function getTaxes()
	{
		return $this->taxes;
	}

	public function setPnr($pnr)
	{
		$this->pnr = $pnr;
	}

	public function getPnr()
	{
		return $this->pnr;
	}

	public function setTransactionId($transactionId)
	{
		$this->transactionId = $transactionId;
	}

	public function getTransactionId()
	{
		return $this->transactionId;
	}

	public function setSpecialRequirement($specialRequirement)
	{
		$this->specialRequirement = $specialRequirement;
	}

	public function getSpecialRequirement()
	{
		return $this->specialRequirement;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setRefundable($refundable)
	{
		$this->refundable = $refundable;
	}

	public function getRefundable()
	{
		return $this->refundable;
	}

	public function setOneWay($oneWay)
	{
		$this->oneWay = $oneWay;
	}

	public function getOneWay()
	{
		return $this->oneWay;
	}

	public function setMultiDestination($multiDestination)
	{
		$this->multiDestination = $multiDestination;
	}

	public function getMultiDestination()
	{
		return $this->multiDestination;
	}

	public function setEnableCancelation($enableCancelation)
	{
		$this->enableCancelation = $enableCancelation;
	}

	public function getEnableCancelation()
	{
		return $this->enableCancelation;
	}

	public function setHotels($hotels)
	{
		$this->hotels = $hotels;
	}

	public function getHotels()
	{
		return $this->hotels;
	}

	public function setAirlinePnr($airlinePnr)
	{
		$this->airlinePnr = $airlinePnr;
	}

	public function getAirlinePnr()
	{
		return $this->airlinePnr;
	}
}
