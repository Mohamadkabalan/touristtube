<?php

namespace TTBundle\Entity;

//
use Doctrine\ORM\Mapping as ORM;

/**
 * currency_rate
 *
 * @ORM\Table(name="currency_rate")
 * @ORM\Entity(repositoryClass="TTBundle\Repository\CurrencyRepository")
 */
class CurrencyRate {

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
     * @ORM\Column(name="currency_name", type="string", length=255, nullable=false)
     */
    private $currencyName;

    /**
     * @var string
     *
     * @ORM\Column(name="currency_code", type="string", length=255, nullable=false)
     */
    private $currencyCode;

    /**
     * @var string
     *
     * @ORM\Column(name="symbol", type="string", length=100, nullable=false)
     */
    private $symbol;
    
    /**
     * @var float
     *
     * @ORM\Column(name="currency_rate", type="float", nullable=false)
     */
    private $currencyRate;

    /**
     * @var datetime
     *
     * @ORM\Column(name="last_update", type="datetime", nullable=false)
     */
    private $lastUpdate;

    /**
     * The top currency
     * @var boolean
     *
     * @ORM\Column(name="top_currency", type="boolean")
     */
    private $topCurrency;
    
    /**
     * The used by tt
     * @var boolean
     *
     * @ORM\Column(name="used", type="boolean")
     */
    private $used;
    
    /**
     * The dispalyed order
     * @var boolean
     *
     * @ORM\Column(name="display_order", type="integer")
     */
    private $displayOrder;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set currencyName
     *
     * @param string currencyName
     *
     * @return string
     */
    public function setCurrencyName($currencyName) {
        $this->currencyName = $currencyName;

        return $this;
    }

    /**
     * Get currencyName
     *
     * @return string
     */
    public function getCurrencyName() {
        return $this->currencyName;
    }

    /**
     * Set currencyCode
     *
     * @param string currencyCode
     *
     * @return string
     */
    public function setCurrencyCode($currencyCode) {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    /**
     * Get currencyCode
     *
     * @return string
     */
    public function getCurrencyCode() {
        return $this->currencyCode;
    }
    
    /**
     * Set symbol
     *
     * @param string symbol
     *
     * @return string
     */
    public function setSymbol($symbol) {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * Get symbol
     *
     * @return string
     */
    public function getSymbol() {
        return $this->symbol;
    }

    /**
     * Set currencyRate
     *
     * @param float currencyRate
     *
     * @return float
     */
    public function setCurrencyRate($currencyRate) {
        $this->currencyRate = $currencyRate;

        return $this;
    }

    /**
     * Get currencyRate
     *
     * @return float
     */
    public function getCurrencyRate() {
        return $this->currencyRate;
    }

    /**
     * Set lastUpdate
     *
     * @param datetime lastUpdate
     *
     * @return datetime
     */
    public function setLastUpdate($lastUpdate) {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * Get lastUpdate
     *
     * @return datetime
     */
    public function getLastUpdate() {
        return $this->lastUpdate;
    }

    /**
     * Is top currency
     *
     * @return boolean
     */
    function isTopCurrency() {
        return $this->topCurrency;
    }

    /**
     * Set top currency
     *
     * @param boolean $topCurrency
     */
    function setTopCurrency($topCurrency) {
        $this->topCurrency = $topCurrency;
    }

     /**
     * Is used by tt
     *
     * @return boolean
     */
    function isUsed() {
        return $this->topCurrency;
    }

    /**
     * Set used
     *
     * @param boolean $used
     */
    function setUsed($used) {
        $this->used = $used;
    }
    
    /**
     * The order displayed
     *
     * @return integer
     */
    function getDisplayOrder() {
        return $this->displayOrder;
    }

    /**
     * Set display order
     *
     * @param boolean $displayOrder
     */
    function setDisplayOrder($displayOrder) {
        $this->displayOrder = $displayOrder;
    }
}
