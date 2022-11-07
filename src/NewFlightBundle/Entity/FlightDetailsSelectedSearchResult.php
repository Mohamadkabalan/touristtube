<?php

namespace NewFlightBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FlightDetailsSelectedSearchResult
 *
 * @ORM\Entity
 * @ORM\Table(name="flight_details_selected_search_result")
 */
class FlightDetailsSelectedSearchResult
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="flight_selected_id", type="integer", nullable=false)
     */
    private $flightSelectedId;

    /**
     * @var integer
     *
     * @ORM\Column(name="segment_number", type="integer", nullable=false)
     */
    private $segmentNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="from_location", type="string", length=3, nullable=false)
     */
    private $fromLocation;

    /**
     * @var string
     *
     * @ORM\Column(name="to_location", type="string", length=3, nullable=false)
     */
    private $toLocation;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_stop", type="integer", nullable=false)
     */
    private $isStop;

    /**
     * @var integer
     *
     * @ORM\Column(name="terminal_id", type="integer", nullable=true)
     */
    private $terminalId;

    /**
     * @var string
     *
     * @ORM\Column(name="operating_airline", type="string", length=255, nullable=false)
     */
    private $operatingAirline;

    /**
     * @var string
     *
     * @ORM\Column(name="marketing_airline", type="string", length=10, nullable=false)
     */
    private $marketingAirline;

    /**
     * @var integer
     *
     * @ORM\Column(name="flight_number", type="integer", nullable=false)
     */
    private $flightNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="res_book_design_code", type="string", length=100, nullable=false)
     */
    private $resBookDesignCode;

    /**
     * @var float
     *
     * @ORM\Column(name="duration", type="float", nullable=false)
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="fare_basis_code", type="string", length=30, nullable=false)
     */
    private $fareBasisCode;

    /**
     * @var string
     *
     * @ORM\Column(name="fare_calc_line", type="string", length=1000, nullable=false)
     */
    private $fareCalcLine;

    /**
     * @var decimal
     *
     * @ORM\Column(name="price", type="decimal", nullable=false)
     */
    private $price;

    /**
     * @var decimal
     *
     * @ORM\Column(name="base_fare", type="decimal", nullable=false)
     */
    private $baseFare;

    /**
     * @var decimal
     *
     * @ORM\Column(name="taxes", type="decimal", nullable=false)
     */
    private $taxes;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=255, nullable=false)
     */
    private $currency;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="departure_datetime", type="datetime", nullable=false)
     */
    private $departureDatetime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="arrival_datetime", type="datetime", nullable=false)
     */
    private $arrivalDatetime;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=11, nullable=false)
     */
    private $type;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get flightSelectedId
     *
     * @return integer
     */
    public function getFlightSelectedId()
    {
        return $this->flightSelectedId;
    }

    /**
     * Get segmentNumber
     *
     * @return integer
     */
    public function getSegmentNumber()
    {
        return $this->segmentNumber;
    }

    /**
     * Get fromLocation
     *
     * @return string
     */
    public function getFromLocation()
    {
        return $this->fromLocation;
    }

    /**
     * Get toLocation
     *
     * @return string
     */
    public function getToLocation()
    {
        return $this->toLocation;
    }

    /**
     * Get isStop
     *
     * @return integer
     */
    public function getIsStop()
    {
        return $this->isStop;
    }

    /**
     * Get terminalId
     *
     * @return integer
     */
    public function getTerminalId()
    {
        return $this->terminalId;
    }

    /**
     * Get operatingAirline
     *
     * @return string
     */
    public function getOperatingAirline()
    {
        return $this->operatingAirline;
    }

    /**
     * Get marketingAirline
     *
     * @return string
     */
    public function getMarketingAirline()
    {
        return $this->marketingAirline;
    }

    /**
     * Get flightNumber
     *
     * @return integer
     */
    public function getFlightNumber()
    {
        return $this->flightNumber;
    }

    /**
     * Get resBookDesignCode
     *
     * @return string
     */
    public function getResBookDesignCode()
    {
        return $this->resBookDesignCode;
    }

    /**
     * Get duration
     *
     * @return float
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Get fareBasisCode
     *
     * @return string
     */
    public function getFareBasisCode()
    {
        return $this->fareBasisCode;
    }

    /**
     * Get fareCalcLine
     *
     * @return string
     */
    public function getFareCalcLine()
    {
        return $this->fareCalcLine;
    }

    /**
     * Get price
     *
     * @return decimal
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get baseFare
     *
     * @return decimal
     */
    public function getBaseFare()
    {
        return $this->baseFare;
    }

    /**
     * Get taxes
     *
     * @return decimal
     */
    public function getTaxes()
    {
        return $this->taxes;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Get departureDatetime
     *
     * @return datetime
     */
    public function getDepartureDatetime()
    {
        return $this->departureDatetime;
    }

    /**
     * Get arrivalDatetime
     *
     * @return datetime
     */
    public function getArrivalDatetime()
    {
        return $this->arrivalDatetime;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set id
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set flightSelectedId
     *
     * @param integer $flightSelectedId
     */
    public function setFlightSelectedId($flightSelectedId)
    {
        $this->flightSelectedId = $flightSelectedId;
    }

    /**
     * Set segmentNumber
     *
     * @param integer $segmentNumber
     */
    public function setSegmentNumber($segmentNumber)
    {
        $this->segmentNumber = $segmentNumber;
    }

    /**
     * Set fromLocation
     *
     * @param string $fromLocation
     */
    public function setFromLocation($fromLocation)
    {
        $this->fromLocation = $fromLocation;
    }

    /**
     * Set toLocation
     *
     * @param string $toLocation
     */
    public function setToLocation($toLocation)
    {
        $this->toLocation = $toLocation;
    }

    /**
     * Set isStop
     *
     * @param integer $isStop
     */
    public function setIsStop($isStop)
    {
        $this->isStop = $isStop;
    }

    /**
     * Set terminalId
     *
     * @param integer $terminalId
     */
    public function setTerminalId($terminalId)
    {
        $this->terminalId = $terminalId;
    }

    /**
     * Set operatingAirline
     *
     * @param string $operatingAirline
     */
    public function setOperatingAirline($operatingAirline)
    {
        $this->operatingAirline = $operatingAirline;
    }

    /**
     * Set marketingAirline
     *
     * @param string $marketingAirline
     */
    public function setMarketingAirline($marketingAirline)
    {
        $this->marketingAirline = $marketingAirline;
    }

    /**
     * Set flightNumber
     *
     * @param integer $flightNumber
     */
    public function setFlightNumber($flightNumber)
    {
        $this->flightNumber = $flightNumber;
    }

    /**
     * Set resBookDesignCode
     *
     * @param string $resBookDesignCode
     */
    public function setResBookDesignCode($resBookDesignCode)
    {
        $this->resBookDesignCode = $resBookDesignCode;
    }

    /**
     * Set duration
     *
     * @param float $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * Set fareBasisCode
     *
     * @param string $fareBasisCode
     */
    public function setFareBasisCode($fareBasisCode)
    {
        $this->fareBasisCode = $fareBasisCode;
    }

    /**
     * Set fareCalcLine
     *
     * @param string $fareCalcLine
     */
    public function setFareCalcLine($fareCalcLine)
    {
        $this->fareCalcLine = $fareCalcLine;
    }

    /**
     * Set price
     *
     * @param decimal $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Set baseFare
     *
     * @param decimal $baseFare
     */
    public function setBaseFare($baseFare)
    {
        $this->baseFare = $baseFare;
    }

    /**
     * Set taxes
     *
     * @param decimal $taxes
     */
    public function setTaxes($taxes)
    {
        $this->taxes = $taxes;
    }

    /**
     * Set currency
     *
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * Set departureDatetime
     *
     * @param datetime $departureDatetime
     */
    public function setDepartureDatetime($departureDatetime)
    {
        $this->departureDatetime = $departureDatetime;
    }

    /**
     * Set arrivalDatetime
     *
     * @param datetime $arrivalDatetime
     */
    public function setArrivalDatetime($arrivalDatetime)
    {
        $this->arrivalDatetime = $arrivalDatetime;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}