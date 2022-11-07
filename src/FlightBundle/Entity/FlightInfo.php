<?php

namespace FlightBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FlightInfo
 * 
 * @ORM\Entity
 * @ORM\Table(name="flight_info")
 */
class FlightInfo {

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
     * @ORM\Column(name="pnr_id", type="string", length=11, nullable=false)
     */
    private $pnrId;

    /**
     * @var decimal
     *
     * @ORM\Column(name="price", type="decimal", nullable=false)
     */
    private $price;

    /**
     * @var decimal
     *
     * @ORM\Column(name="display_price", type="decimal", nullable=false)
     */
    private $displayPrice;

    /**
     * @var decimal
     *
     * @ORM\Column(name="base_fare", type="decimal", nullable=false)
     */
    private $baseFare;

    /**
     * @var decimal
     *
     * @ORM\Column(name="display_base_fare", type="decimal", nullable=false)
     */
    private $displayBaseFare;

    /**
     * @var decimal
     *
     * @ORM\Column(name="taxes", type="decimal", nullable=false)
     */
    private $taxes;

    /**
     * @var decimal
     *
     * @ORM\Column(name="display_taxes", type="decimal", nullable=false)
     */
    private $displayTaxes;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=3, nullable=false)
     */
    private $currency;

    /**
     * @var string
     *
     * @ORM\Column(name="display_currency", type="string", length=3, nullable=false)
     */
    private $displayCurrency;

    /**
     * @var boolean
     *
     * @ORM\Column(name="refundable", type="boolean", nullable=false)
     */
    private $refundable;

    /**
     * @var boolean
     *
     * @ORM\Column(name="one_way", type="boolean", nullable=false)
     */
    private $oneWay;

    /**
     * @var boolean
     *
     * @ORM\Column(name="multi_destination", type="boolean", nullable=false)
     */
    private $multiDestination;

    /**
     * @var json
     *
     * @ORM\Column(name="penalties_info", type="json", nullable=true)
     */
    private $penaltiesInfo;



    /**
     * @ORM\OneToOne(targetEntity="PassengerNameRecord", inversedBy="flightInfo")
     * @ORM\JoinColumn(name="pnr_id", referencedColumnName="id")
     */
    private $passengerNameRecord;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
	return $this->id;
    }

    /**
     * Get passenger name record ID
     *
     * @return string
     */
    public function getPnrId() {
	return $this->pnrId;
    }

    /**
     * Set passenger name record ID
     *
     * @param string $pnrId
     */
    public function setPnrId($pnrId) {
	$this->pnrId = $pnrId;
    }

    /**
     * Get price
     *
     * @return decimal
     */
    public function getPrice() {
	return $this->price;
    }

    /**
     * Set price
     *
     * @param decimal $price
     */
    public function setPrice($price) {
	$this->price = $price;
    }

    /**
     * Get display price
     *
     * @return decimal
     */
    public function getDisplayPrice() {
	return $this->displayPrice;
    }

    /**
     * Set display price
     *
     * @param decimal $displayPrice
     */
    public function setDisplayPrice($displayPrice) {
	$this->displayPrice = $displayPrice;
    }

    /**
     * Get base fare
     *
     * @return decimal
     */
    public function getBaseFare() {
	return $this->baseFare;
    }

    /**
     * Set base fare
     *
     * @param decimal $baseFare
     */
    public function setBaseFare($baseFare) {
	$this->baseFare = $baseFare;
    }

    /**
     * Get display base fare
     *
     * @return decimal
     */
    public function getDisplayBaseFare() {
	return $this->displayBaseFare;
    }

    /**
     * Set display base fare
     *
     * @param decimal $displayBaseFare
     */
    public function setDisplayBaseFare($displayBaseFare) {
	$this->displayBaseFare = $displayBaseFare;
    }

    /**
     * Get taxes
     *
     * @return decimal
     */
    public function getTaxes() {
	return $this->taxes;
    }

    /**
     * Set taxes
     *
     * @param decimal $taxes
     */
    public function setTaxes($taxes) {
	$this->taxes = $taxes;
    }
    
    /**
     * Get display taxes
     *
     * @return decimal
     */
    public function getDisplayTaxes() {
	return $this->displayTaxes;
    }

    /**
     * Set display taxes
     *
     * @param decimal $displayTaxes
     */
    public function setDisplayTaxes($displayTaxes) {
	$this->displayTaxes = $displayTaxes;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency() {
	return $this->currency;
    }

    /**
     * Set currency
     *
     * @param string $currency
     */
    public function setCurrency($currency) {
	$this->currency = $currency;
    }

    /**
     * Get display currency
     *
     * @return string
     */
    public function getDisplayCurrency() {
	return $this->displayCurrency;
    }

    /**
     * Set display currency
     *
     * @param string $displayCurrency
     */
    public function setDisplayCurrency($displayCurrency) {
	$this->displayCurrency = $displayCurrency;
    }

    /**
     * Is refundable
     *
     * @return boolean
     */
    public function isRefundable() {
	return $this->refundable;
    }

    /**
     * Set refundable
     *
     * @param boolean $refundable
     */
    public function setRefundable($refundable) {
	$this->refundable = $refundable;
    }

    /**
     * Is one way
     *
     * @return boolean
     */
    public function isOneWay() {
	return $this->oneWay;
    }

    /**
     * Set one way
     *
     * @param boolean $oneWay
     */
    public function setOneWay($oneWay) {
	$this->oneWay = $oneWay;
    }

    /**
     * Is multi destination
     *
     * @return boolean
     */
    public function isMultiDestination() {
	return $this->multiDestination;
    }

    /**
     * Set multi destination
     *
     * @param boolean $multiDestination
     */
    public function setMultiDestination($multiDestination) {
	$this->multiDestination = $multiDestination;
    }




    /**
     * penalties Info
     *
     * @return json
     */
    public function getPenaltiesInfo() {
        return $this->penaltiesInfo;
    }

    /**
     * Set penalties Info
     *
     * @param json $penaltiesInfo
     */
    public function setPenaltiesInfo($penaltiesInfo) {
        $this->penaltiesInfo= $penaltiesInfo;
    }







    /**
     * Get passenger Name Record
     *
     * @return PassengerNameRecord
     */
    public function getPassengerNameRecord() {
	return $this->passengerNameRecord;
    }

    /**
     * Set passenger name record
     *
     * @param PassengerNameRecord $passengerNameRecord
     */
    public function setPassengerNameRecord(PassengerNameRecord $passengerNameRecord) {
	$this->passengerNameRecord = $passengerNameRecord;
    }

}
