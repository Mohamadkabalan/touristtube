<?php

namespace DealBundle\Model;

/**
 *  DealTransferAirportPrice is the class for the transfer prices to be centralized in this class and called from relative classes
 *
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealTransferAirportPrice
{
    private $arrivalPriceId     = '';
    private $departurePriceId   = '';
    private $priceResort        = '';
    private $priceTotal         = '';
    private $priceTotalNet      = '';
    private $pricePerPaxCar     = '';
    private $priceCurrency      = '';
    private $priceType          = '';
    private $priceRoundtrip     = '';
    private $priceZip           = '';
    private $priceCarType       = '';
    private $priceMinPax        = '';
    private $priceMaxPax        = '';
    private $priceSeasonFrom    = '';
    private $priceSeasonTo      = '';
    private $priceCarCategory   = '';
    private $priceCarModel      = '';
    private $priceCarMinimumPax = '';
    private $amountFBC          = 0;
    private $amountSBC          = 0;
    private $amountACCurrency   = '';

    /**
     * Get arrivalPriceId
     * @return String
     */
    function getArrivalPriceId()
    {
        return $this->arrivalPriceId;
    }

    /**
     * Get departurePriceId
     * @return String
     */
    function getDeparturePriceId()
    {
        return $this->departurePriceId;
    }

    /**
     * Get priceResort
     * @return String
     */
    function getPriceResort()
    {
        return $this->priceResort;
    }

    /**
     * Get priceTotal
     * @return String
     */
    function getPriceTotal()
    {
        return $this->priceTotal;
    }

    /**
     * Get priceTotalNet
     * @return String
     */
    function getPriceTotalNet()
    {
        return $this->priceTotalNet;
    }

    /**
     * Get pricePerPaxCar
     * @return String
     */
    function getPricePerPaxCar()
    {
        return $this->pricePerPaxCar;
    }

    /**
     * Get priceCurrency
     * @return String
     */
    function getPriceCurrency()
    {
        return $this->priceCurrency;
    }

    /**
     * Get priceType
     * @return String
     */
    function getPriceType()
    {
        return $this->priceType;
    }

    /**
     * Get priceRoundtrip
     * @return String
     */
    function getPriceRoundtrip()
    {
        return $this->priceRoundtrip;
    }

    /**
     * Get priceZip
     * @return String
     */
    function getPriceZip()
    {
        return $this->priceZip;
    }

    /**
     * Get priceCarType
     * @return String
     */
    function getPriceCarType()
    {
        return $this->priceCarType;
    }

    /**
     * Get priceMinPax
     * @return String
     */
    function getPriceMinPax()
    {
        return $this->priceMinPax;
    }

    /**
     * Get priceMaxPax
     * @return String
     */
    function getPriceMaxPax()
    {
        return $this->priceMaxPax;
    }

    /**
     * Get priceSeasonFrom
     * @return String
     */
    function getPriceSeasonFrom()
    {
        return $this->priceSeasonFrom;
    }

    /**
     * Get priceSeasonTo
     * @return String
     */
    function getPriceSeasonTo()
    {
        return $this->priceSeasonTo;
    }

    /**
     * Get priceCarCategory
     * @return String
     */
    function getPriceCarCategory()
    {
        return $this->priceCarCategory;
    }

    /**
     * Get priceCarModel
     * @return String
     */
    function getPriceCarModel()
    {
        return $this->priceCarModel;
    }

    /**
     * Get priceCarMinimumPax
     * @return String
     */
    function getPriceCarMinimumPax()
    {
        return $this->priceCarMinimumPax;
    }

    /**
     * Get amountFBC
     * @return Integer
     */
    function getAmountFBC()
    {
        return $this->amountFBC;
    }

    /**
     * Get amountSBC
     * @return Integer
     */
    function getAmountSBC()
    {
        return $this->amountSBC;
    }

    /**
     * Get amountACCurrency
     * @return String
     */
    function getAmountACCurrency()
    {
        return $this->amountACCurrency;
    }

    /**
     * Set arrivalPriceId
     * @param String $arrivalPriceId
     */
    function setArrivalPriceId($arrivalPriceId)
    {
        $this->arrivalPriceId = $arrivalPriceId;
    }

    /**
     * Set departurePriceId
     * @param String $departurePriceId
     */
    function setDeparturePriceId($departurePriceId)
    {
        $this->departurePriceId = $departurePriceId;
    }

    /**
     * Set priceResort
     * @param String $priceResort
     */
    function setPriceResort($priceResort)
    {
        $this->priceResort = $priceResort;
    }

    /**
     * Set priceTotal
     * @param String $priceTotal
     */
    function setPriceTotal($priceTotal)
    {
        $this->priceTotal = $priceTotal;
    }

    /**
     * Set priceTotalNet
     * @param String $priceTotalNet
     */
    function setPriceTotalNet($priceTotalNet)
    {
        $this->priceTotalNet = $priceTotalNet;
    }

    /**
     * Set pricePerPaxCar
     * @param String $pricePerPaxCar
     */
    function setPricePerPaxCar($pricePerPaxCar)
    {
        $this->pricePerPaxCar = $pricePerPaxCar;
    }

    /**
     * Set priceCurrency
     * @param String $priceCurrency
     */
    function setPriceCurrency($priceCurrency)
    {
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * Set priceType
     * @param String $priceType
     */
    function setPriceType($priceType)
    {
        $this->priceType = $priceType;
    }

    /**
     * Set priceRoundtrip
     * @param String $priceRoundtrip
     */
    function setPriceRoundtrip($priceRoundtrip)
    {
        $this->priceRoundtrip = $priceRoundtrip;
    }

    /**
     * Set priceZip
     * @param String $priceZip
     */
    function setPriceZip($priceZip)
    {
        $this->priceZip = $priceZip;
    }

    /**
     * Set priceCarType
     * @param String $priceCarType
     */
    function setPriceCarType($priceCarType)
    {
        $this->priceCarType = $priceCarType;
    }

    /**
     * Set priceMinPax
     * @param String $priceMinPax
     */
    function setPriceMinPax($priceMinPax)
    {
        $this->priceMinPax = $priceMinPax;
    }

    /**
     * Set priceMaxPax
     * @param String $priceMaxPax
     */
    function setPriceMaxPax($priceMaxPax)
    {
        $this->priceMaxPax = $priceMaxPax;
    }

    /**
     * Set priceSeasonFrom
     * @param String $priceSeasonFrom
     */
    function setPriceSeasonFrom($priceSeasonFrom)
    {
        $this->priceSeasonFrom = $priceSeasonFrom;
    }

    /**
     * Set priceSeasonTo
     * @param String $priceSeasonTo
     */
    function setPriceSeasonTo($priceSeasonTo)
    {
        $this->priceSeasonTo = $priceSeasonTo;
    }

    /**
     * Set priceCarCategory
     * @param String $priceCarCategory
     */
    function setPriceCarCategory($priceCarCategory)
    {
        $this->priceCarCategory = $priceCarCategory;
    }

    /**
     * Set priceCarModel
     * @param String $priceCarModel
     */
    function setPriceCarModel($priceCarModel)
    {
        $this->priceCarModel = $priceCarModel;
    }

    /**
     * Set priceCarMinimumPax
     * @param String $priceCarMinimumPax
     */
    function setPriceCarMinimumPax($priceCarMinimumPax)
    {
        $this->priceCarMinimumPax = $priceCarMinimumPax;
    }

    /**
     * Set amountFBC
     * @param Integer $amountFBC
     */
    function setAmountFBC($amountFBC)
    {
        $this->amountFBC = $amountFBC;
    }

    /**
     * Set amountSBC
     * @param Integer $amountSBC
     */
    function setAmountSBC($amountSBC)
    {
        $this->amountSBC = $amountSBC;
    }

    /**
     * Set amountACCurrency
     * @param String $amountACCurrency
     */
    function setAmountACCurrency($amountACCurrency)
    {
        $this->amountACCurrency = $amountACCurrency;
    }

    /**
     * Get array format response of this instance
     * @return Array
     */
    public function toArray()
    {
        $toreturn = array();
        foreach ($this as $key => $value) {
            $toreturn[$key] = $value;
        }
        return $toreturn;
    }
}