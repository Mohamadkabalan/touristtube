<?php

namespace NewFlightBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FlightBaggageInfo
 *
 * @ORM\Entity
 * @ORM\Table(name="flight_baggage_info")
 */
class FlightBaggageInfo
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
     * @ORM\Column(name="flight_selected_details_id", type="integer", nullable=false)
     */
    private $flightSelectedDetailsId;

    /**
     * @var integer
     *
     * @ORM\Column(name="pieces", type="integer", nullable=true)
     */
    private $pieces;

    /**
     * @var weight
     *
     * @ORM\Column(name="weight", type="integer", nullable=true)
     */
    private $weight;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=5, nullable=true)
     */
    private $unit;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

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
     * Get flightSelectedDetailsId
     *
     * @return integer
     */
    public function getFlightSelectedDetailsId()
    {
        return $this->flightSelectedDetailsId;
    }

    /**
     * Get pieces
     *
     * @return integer
     */
    public function getPieces()
    {
        return $this->pieces;
    }

    /**
     * Get weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Get unit
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
     * Set flightSelectedDetailsId
     *
     * @param integer $flightSelectedDetailsId
     */
    public function setFlightSelectedDetailsId($flightSelectedDetailsId)
    {
        $this->flightSelectedDetailsId = $flightSelectedDetailsId;
    }

    /**
     * Set pieces
     *
     * @param integer $pieces
     */
    public function setPieces($pieces)
    {
        $this->pieces = $pieces;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * Set unit
     *
     * @param string $unit
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}