<?php
namespace FlightBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
/**
 * This class will serve as a getter and setter for requested data (GET/POST)
 **/
 
class RequestData
{
	private $totalSegments;
	private $flightSegments;
    private $departureDateTime;
    private $arrivalDateTime;
    private $flightNumber;
    private $numberInParty;
    private $resBookDesigCode;
    private $destinationLocation;
    private $airlineCode;
    private $airlineName;
    private $originLocation;
    private $operatingAirlineCode;
    private $adultsQuantity;
    private $childrenQuantity;
    private $infantsQuantity;
    private $accessToken;
    private $returnedConversationId;
    private $originalPrice;
    private $priceAmount;
    private $baseFare;
    private $taxes;
    private $currencyCode;
    private $selectedCurrency;
    private $refundable;
    private $oneWay;
    private $multiDestination;
    private $from_mobile;
    private $couponCode;
    private $displayedCurrency;
    private $conversionRate;
    private $displayedPrice;
    private $secToken;
    private $flightType;
    private $destinationLocationCity;
    private $destinationLocationAirport;
    private $originLocationAirport;
    private $originCity;
    private $stopIndicator;
    private $stopDuration;
    private $cabinCode;
    private $cabin;
    private $flightDuration;
    private $segmentNumber;
    private $cnn;
    private $adt;
    private $inf;
    private $operatingAirlineName;
    private $originTerminalId;
    private $destinationTerminalId;
    private $fareBasisCode;
    private $aircraft_type;
    private $segmentAmount;
    private $mileDistance;
    private $elapsedTime;
    private $penaltiesInfo;

    public function __construct()
    {
        /*$this->flightType = new ArrayCollection();
        $this->departureDateTime = new ArrayCollection();
        $this->arrivalDateTime = new ArrayCollection();
        $this->flightNumber = new ArrayCollection();
        $this->resBookDesigCode = new ArrayCollection();
        $this->destinationLocation = new ArrayCollection();
        $this->destinationLocationCity = new ArrayCollection();
        $this->destinationLocationAirport = new ArrayCollection();
        $this->airlineCode = new ArrayCollection();
        $this->airlineName = new ArrayCollection();
        $this->operatingAirlineCode = new ArrayCollection();
        $this->operatingAirlineName = new ArrayCollection();
        $this->originLocation = new ArrayCollection();
        $this->originLocationAirport = new ArrayCollection();
        $this->originCity = new ArrayCollection();
        $this->stopIndicator = new ArrayCollection();
        $this->stopDuration = new ArrayCollection();
        $this->cabinCode = new ArrayCollection();
        $this->cabin = new ArrayCollection();
        $this->flightDuration = new ArrayCollection();
        $this->segmentNumber = new ArrayCollection();
        $this->cnn = new ArrayCollection();
        $this->adt = new ArrayCollection();
        $this->inf = new ArrayCollection();*/
    }
    
    public function setTotalSegments($totalSegments)
    {
	    $this->totalSegments = $totalSegments;
    }
    
    public function getTotalSegments()
    {
	    return $this->totalSegments;
    }
    
    public function setFlightSegments($flightSegments)
    {
	    $this->flightSegments = $flightSegments;
    }
    
    public function getFlightSegments()
    {
	    return $this->flightSegments;
    }
    
    public function setDepartureDateTime($departureDateTime, $index)
    {
	    $this->departureDateTime[$index] = $departureDateTime;
    }
    
    public function getDepartureDateTime()
    {
	    return $this->departureDateTime;
    }
    
    public function setArrivalDateTime($arrivalDateTime, $index)
    {
	    $this->arrivalDateTime[$index] = $arrivalDateTime;
    }
    
    public function getArrivalDateTime()
    {
	    return $this->arrivalDateTime;
    }
    
    public function setFlightNumber($flightNumber, $index)
    {
	    $this->flightNumber[$index] = $flightNumber;
    }
    
    public function getFlightNumber()
    {
	    return $this->flightNumber;
    }
    
    public function setNumberInParty($numberInParty)
    {
	    $this->numberInParty = $numberInParty;
    }
    
    public function getNumberInParty()
    {
	    return $this->numberInParty;
    }
    
    public function setResBookDesigCode($resBookDesigCode, $index)
    {
	    $this->resBookDesigCode[$index] = $resBookDesigCode;
    }
    
    public function getResBookDesigCode()
    {
	    return $this->resBookDesigCode;
    }
    
    public function setDestinationLocation($destinationLocation, $index)
    {
	    $this->destinationLocation[$index] = $destinationLocation;
    }
    
    public function getDestinationLocation()
    {
	    return $this->destinationLocation;
    }

    public function setDestinationLocationCity($destinationLocationCity, $index)
    {
        $this->destinationLocationCity[$index] = $destinationLocationCity;
    }
    
    public function getDestinationLocationCity()
    {
        return $this->destinationLocationCity;
    }

    public function setDestinationLocationAirport($destinationLocationAirport, $index)
    {
        $this->destinationLocationAirport[$index] = $destinationLocationAirport;
    }
    
    public function getDestinationLocationAirport()
    {
        return $this->destinationLocationAirport;
    }
    
    public function setAirlineCode($airlineCode, $index)
    {
	    $this->airlineCode[$index] = $airlineCode;
    }
    
    public function getAirlineCode()
    {
	    return $this->airlineCode;
    }
    
    public function setAirlineName($airlineName, $index)
    {
	    $this->airlineName[$index] = $airlineName;
    }
    
    public function getAirlineName()
    {
	    return $this->airlineName;
    }
    
    public function setOriginLocation($originLocation, $index)
    {
	    $this->originLocation[$index] = $originLocation;
    }
    
    public function getOriginLocation()
    {
	    return $this->originLocation;
    }

    public function setOriginCity($originCity, $index)
    {
        $this->originCity[$index] = $originCity;
    }
    
    public function getOriginCity()
    {
        return $this->originCity;
    }

    public function setStopIndicator($stopIndicator, $index)
    {
        $this->stopIndicator[$index] = $stopIndicator;
    }
    
    public function getStopIndicator()
    {
        return $this->stopIndicator;
    }

    public function setStopDuration($stopDuration, $index)
    {
        $this->stopDuration[$index] = $stopDuration;
    }
    
    public function getStopDuration()
    {
        return $this->stopDuration;
    }

    public function setOriginLocationAirport($originLocationAirport, $index)
    {
        $this->originLocationAirport[$index] = $originLocationAirport;
    }
    
    public function getOriginLocationAirport()
    {
        return $this->originLocationAirport;
    }
    
    public function setOperatingAirlineCode($operatingAirlineCode, $index)
    {
	    $this->operatingAirlineCode[$index] = $operatingAirlineCode;
    }
    
    public function getOperatingAirlineCode()
    {
	    return $this->operatingAirlineCode;
    }

    public function setOperatingAirlineName($operatingAirlineName, $index)
    {
        $this->operatingAirlineName[$index] = $operatingAirlineName;
    }
    
    public function getOperatingAirlineName()
    {
        return $this->operatingAirlineName;
    }
    
    public function setAdultsQuantity($adultsQuantity)
    {
	    $this->adultsQuantity = $adultsQuantity;
    }
    
    public function getAdultsQuantity()
    {
	    return $this->adultsQuantity;
    }
    
    public function setChildrenQuantity($childrenQuantity)
    {
	    $this->childrenQuantity = $childrenQuantity;
    }
    
    public function getChildrenQuantity()
    {
	    return $this->childrenQuantity;
    }
    
    public function setInfantsQuantity($infantsQuantity)
    {
	    $this->infantsQuantity = $infantsQuantity;
    }
    
    public function getInfantsQuantity()
    {
	    return $this->infantsQuantity;
    }
    
    public function setAccessToken($accessToken)
    {
	    $this->accessToken = $accessToken;
    }
    
    public function getAccessToken()
    {
	    return $this->accessToken;
    }
    
    public function setReturnedConversationId($returnedConversationId)
    {
	    $this->returnedConversationId = $returnedConversationId;
    }
    
    public function getReturnedConversationId()
    {
	    return $this->returnedConversationId;
    }
    
    public function setOriginalPrice($originalPrice)
    {
	    $this->originalPrice = $originalPrice;
    }
    
    public function getOriginalPrice()
    {
	    return $this->originalPrice;
    }
    
    public function setPriceAmount($priceAmount)
    {
	    $this->priceAmount = $priceAmount;
    }
    
    public function getPriceAmount()
    {
	    return $this->priceAmount;
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
    
    public function setCurrencyCode($currencyCode)
    {
	    $this->currencyCode = $currencyCode;
    }
    
    public function getCurrencyCode()
    {
	    return $this->currencyCode;
    }
    
    public function setSelectedCurrency($selectedCurrency)
    {
	    $this->selectedCurrency = $selectedCurrency;
    }
    
    public function getSelectedCurrency()
    {
	    return $this->selectedCurrency;
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
    
    public function setFromMobile($from_mobile)
    {
	    $this->from_mobile = $from_mobile;
    }
    
    public function getFromMobile()
    {
	    return $this->from_mobile;
    }
    
    public function setCouponCode($couponCode)
    {
	    $this->couponCode = $couponCode;
    }
    
    public function getCouponCode()
    {
	    return $this->couponCode;
    }
    
    public function setDisplayedCurrency($displayedCurrency)
    {
	    $this->displayedCurrency = $displayedCurrency;
    }
    
    public function getDisplayedCurrency()
    {
	    return $this->displayedCurrency;
    }
    
    public function setConversionRate($conversionRate)
    {
	    $this->conversionRate = $conversionRate;
    }
    
    public function getConversionRate()
    {
	    return $this->conversionRate;
    }
    
    public function setDisplayedPrice($displayedPrice)
    {
	    $this->displayedPrice = $displayedPrice;
    }
    
    public function getDisplayedPrice()
    {
	    return $this->displayedPrice;
    }
    
    public function setSecToken($secToken)
    {
	    $this->secToken = $secToken;
    }
    
    public function getSecToken()
    {
	    return $this->secToken;
    }
    
    public function setFlightType($flightType, $index)
    {
        $this->flightType[$index] = $flightType;
    }

    public function getFlightType()
    {
        return $this->flightType;
    }

    public function setCabinCode($cabinCode, $index)
    {
        $this->cabinCode[$index] = $cabinCode;
    }

    public function getCabinCode()
    {
        return $this->cabinCode;
    }

    public function setCabin($cabin, $index)
    {
        $this->cabin[$index] = $cabin;
    }

    public function getCabin()
    {
        return $this->cabin;
    }

    public function setFlightDuration($flightDuration, $index)
    {
        $this->flightDuration[$index] = $flightDuration;
    }

    public function getFlightDuration()
    {
        return $this->flightDuration;
    }

    public function setSegmentNumber($segmentNumber, $index)
    {
        $this->segmentNumber[$index] = $segmentNumber;
    }

    public function getSegmentNumber()
    {
        return $this->segmentNumber;
    }
    
    public function setCNN($cnn)
    {
	    $this->cnn[] = $cnn;
    }
    
    public function getCNN()
    {
	    return $this->cnn;
    }
    
    public function setADT($adt)
    {
	    $this->adt[] = $adt;
    }
    
    public function getADT()
    {
	    return $this->adt;
    }
    public function setINF($inf)
    {
	    $this->inf[] = $inf;
    }
    
    public function getINF()
    {
	    return $this->inf;
    }

    public function setOriginTerminalId($originTerminalId, $index)
    {
        $this->originTerminalId[$index] = $originTerminalId;
    }
    
    public function getOriginTerminalId()
    {
        return $this->originTerminalId;
    }

    public function setDestinationTerminalId($destinationTerminalId, $index)
    {
        $this->destinationTerminalId[$index] = $destinationTerminalId;
    }
    
    public function getDestinationTerminalId()
    {
        return $this->destinationTerminalId;
    }

    public function setFareBasisCode($fareBasisCode, $index)
    {
        $this->fareBasisCode[$index] = $fareBasisCode;
    }

    public function getFareBasisCode()
    {
        return $this->fareBasisCode;
    }

    public function setAircraftType($aircraft_type, $index)
    {
        $this->aircraft_type[$index] = $aircraft_type;
    }

    public function getAircraftType()
    {
        return $this->aircraft_type;
    }

    /**
     * @return mixed
     */
    public function getSegmentAmount()
    {
        return $this->segmentAmount;
    }

    /**
     * @param mixed $segmentAmount
     */
    public function setSegmentAmount($segmentAmount, $index)
    {
        $this->segmentAmount[$index] = $segmentAmount;
    }

    /**
     * @return integer
     */
    public function getMileDistance()
    {
        return $this->mileDistance;
    }

    /**
     * @param integer $mileDistance
     */
    public function setMileDistance($mileDistance, $index)
    {
        $this->mileDistance[$index] = $mileDistance;
    }

    /**
     * @return integer
     */
    public function getElapsedTime()
    {
        return $this->elapsedTime;
    }

    /**
     * @param integer $elapsedTime
     */
    public function setElapsedTime($elapsedTime, $index)
    {
        $this->elapsedTime[$index] = $elapsedTime;
    }

    /**
     * @return string
     */
    public function getPenaltiesInfo()
    {
        return $this->penaltiesInfo;
    }

    /**
     * @param string $penaltiesInfo
     */
    public function setPenaltiesInfo($penaltiesInfo)
    {
        $this->penaltiesInfo = $penaltiesInfo;
    }

}
