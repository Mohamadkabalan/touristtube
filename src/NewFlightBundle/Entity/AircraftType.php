<?php

namespace NewFlightBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AircraftType
 * 
 * @ORM\Entity
 * @ORM\Table(name="aircraft_type")
 */
class AircraftType {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="manufacturer", type="string", length=50, nullable=false)
     * @ORM\Id
     */
    private $manufacturer;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=50, nullable=false)
     */
    private $model;

    /**
     * @var string
     *
     * @ORM\Column(name="wake", type="string", length=1, nullable=true)
     */
    private $wake;

    /**
     * @var string
     *
     * @ORM\Column(name="iata_code", type="string", length=3, nullable=false)
     */
    private $iataCode;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get manufacturer
     *
     * @return string
     */
    public function getManufacturer() {
        return $this->manufacturer;
    }

    /**
     * Set manufacturer
     *
     * @param string $manufacturer
     */
    public function setManufacturer($manufacturer) {
        $this->manufacturer = $manufacturer;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel() {
        return $this->model;
    }

    /**
     * Set model
     *
     * @param string $model
     */
    public function setModel($model) {
        $this->model = $model;
    }

    /**
     * Get wake
     *
     * @return string
     */
    public function getWake() {
        return $this->wake;
    }

    /**
     * Set wake
     *
     * @param string $wake
     */
    public function setWake($wake) {
        $this->wake = $wake;
    }

    /**
     * Get iataCode
     *
     * @return string
     */
    public function getIataCode() {
        return $this->iataCode;
    }

    /**
     * Set iataCode
     *
     * @param string $iataCode
     */
    public function setIataCode($iataCode) {
        $this->iataCode = $iataCode;
    }
}