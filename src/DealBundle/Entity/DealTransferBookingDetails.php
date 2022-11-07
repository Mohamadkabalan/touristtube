<?php

namespace DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DealTransferBookingDetails
 *
 * @ORM\Table(name="deal_transfer_booking_details")
 * @ORM\Entity(repositoryClass="DealBundle\Repository\Deal\PackagesRepository")
 * @ORM\Entity
 */
class DealTransferBookingDetails
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="deal_booking_id", type="integer", nullable=false)
     */
    private $dealBookingId;

    /**
     * @var string
     *
     * @ORM\Column(name="arrival_price_id", type="string", length=30, nullable=true)
     */
    private $arrivalPriceId;

    /**
     * @var string
     *
     * @ORM\Column(name="departure_price_id", type="string", length=30, nullable=false)
     */
    private $departurePriceId;

    /**
     * @var string
     *
     * @ORM\Column(name="airport_name", type="string", length=255, nullable=false)
     */
    private $airportName;

    /**
     * @var string
     *
     * @ORM\Column(name="airport_code", type="string", length=30, nullable=false)
     */
    private $airportCode;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=false)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="service_code", type="string", length=5, nullable=false)
     */
    private $serviceCode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="arrival_date", type="date", nullable=true)
     */
    private $arrivalDate;

    /**
     * @var string
     *
     * @ORM\Column(name="arrival_time", type="string", length=20, nullable=true)
     */
    private $arrivalTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="departure_date", type="date", nullable=true)
     */
    private $departureDate;

    /**
     * @var string
     *
     * @ORM\Column(name="arrival_flight_details", type="string", length=255, nullable=true)
     */
    private $arrivalFlightDetails;

    /**
     * @var string
     *
     * @ORM\Column(name="arrival_from", type="string", length=255, nullable=true)
     */
    private $arrivalFrom;

    /**
     * @var string
     *
     * @ORM\Column(name="arrival_destination_address", type="string", length=255, nullable=true)
     */
    private $arrivalDestinationAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="departure_flight_details", type="string", length=255, nullable=true)
     */
    private $departureFlightDetails;

    /**
     * @var string
     *
     * @ORM\Column(name="depart_to", type="string", length=255, nullable=true)
     */
    private $departTo;

    /**
     * @var string
     *
     * @ORM\Column(name="departure_pickup_address", type="string", length=255, nullable=true)
     */
    private $departurePickupAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="service_type", type="string", length=45, nullable=true)
     */
    private $serviceType;

    /**
     * @var string
     *
     * @ORM\Column(name="car_model", type="string", length=255, nullable=true)
     */
    private $carModel;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    function getId()
    {
        return $this->id;
    }

    function getDealBookingId()
    {
        return $this->dealBookingId;
    }

    function getArrivalPriceId()
    {
        return $this->arrivalPriceId;
    }

    function getDeparturePriceId()
    {
        return $this->departurePriceId;
    }

    function getAirportName()
    {
        return $this->airportName;
    }

    function getAirportCode()
    {
        return $this->airportCode;
    }

    function getAddress()
    {
        return $this->address;
    }

    function getServiceCode()
    {
        return $this->serviceCode;
    }

    function getArrivalDate()
    {
        return $this->arrivalDate;
    }

    function getArrivalTime()
    {
        return $this->arrivalTime;
    }

    function getDepartureDate()
    {
        return $this->departureDate;
    }

    function getArrivalFlightDetails()
    {
        return $this->arrivalFlightDetails;
    }

    function getArrivalFrom()
    {
        return $this->arrivalFrom;
    }

    function getArrivalDestinationAddress()
    {
        return $this->arrivalDestinationAddress;
    }

    function getDepartureFlightDetails()
    {
        return $this->departureFlightDetails;
    }

    function getDepartTo()
    {
        return $this->departTo;
    }

    function getDeparturePickupAddress()
    {
        return $this->departurePickupAddress;
    }

    function getServiceType()
    {
        return $this->serviceType;
    }

    function getCarModel()
    {
        return $this->carModel;
    }

    function getCreatedAt()
    {
        return $this->createdAt;
    }

    function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setDealBookingId($dealBookingId)
    {
        $this->dealBookingId = $dealBookingId;
    }

    function setArrivalPriceId($arrivalPriceId)
    {
        $this->arrivalPriceId = $arrivalPriceId;
    }

    function setDeparturePriceId($departurePriceId)
    {
        $this->departurePriceId = $departurePriceId;
    }

    function setAirportName($airportName)
    {
        $this->airportName = $airportName;
    }

    function setAirportCode($airportCode)
    {
        $this->airportCode = $airportCode;
    }

    function setAddress($address)
    {
        $this->address = $address;
    }

    function setServiceCode($serviceCode)
    {
        $this->serviceCode = $serviceCode;
    }

    function setArrivalDate($arrivalDate)
    {
        $this->arrivalDate = $arrivalDate;
    }

    function setArrivalTime($arrivalTime)
    {
        $this->arrivalTime = $arrivalTime;
    }

    function setDepartureDate($departureDate)
    {
        $this->departureDate = $departureDate;
    }

    function setArrivalFlightDetails($arrivalFlightDetails)
    {
        $this->arrivalFlightDetails = $arrivalFlightDetails;
    }

    function setArrivalFrom($arrivalFrom)
    {
        $this->arrivalFrom = $arrivalFrom;
    }

    function setArrivalDestinationAddress($arrivalDestinationAddress)
    {
        $this->arrivalDestinationAddress = $arrivalDestinationAddress;
    }

    function setDepartureFlightDetails($departureFlightDetails)
    {
        $this->departureFlightDetails = $departureFlightDetails;
    }

    function setDepartTo($departTo)
    {
        $this->departTo = $departTo;
    }

    function setDeparturePickupAddress($departurePickupAddress)
    {
        $this->departurePickupAddress = $departurePickupAddress;
    }

    function setServiceType($serviceType)
    {
        $this->serviceType = $serviceType;
    }

    function setCarModel($carModel)
    {
        $this->carModel = $carModel;
    }

    function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function toArray()
    {
        $toreturn = array();
        foreach ($this as $key => $value) {
            $toreturn[$key] = $value;
        }
        return $toreturn;
    }
}