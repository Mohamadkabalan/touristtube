<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace HotelBundle\Model;

/**
 * The HotelModificationForm class
 *
 *
 */
class HotelModificationForm
{
    private $reference;
    private $controlNumber;
    private $fromDate;
    private $toDate;
    private $hotelDetails;
    private $reservationOffers;
    private $ordererDetails;
    private $creditCardDetails;

    /**
     * The __construct when we make a new instance of HotelModificationForm class.
     * @param Array $items  An array request that contains class's attribute/property and value pair (Optional).
     */
    public function __construct(Array $items = array())
    {
        //initialize class attributes/property
        $this->init($items);
    }

    /**
     * Get reference.
     * @return String
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Get controlNumber
     * @return String
     */
    public function getControlNumber()
    {
        return $this->controlNumber;
    }

    /**
     * Get fromDate.
     * @return String
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * Get toDate.
     * @return String
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * Get hotelDetails.
     * @return Array
     */
    public function getHotelDetails()
    {
        return $this->hotelDetails;
    }

    /**
     * Get reservationOffers.
     * @return Array
     */
    public function getReservationOffers()
    {
        return $this->reservationOffers;
    }

    /**
     * Get ordererDetails.
     * @return Array
     */
    public function getOrdererDetails()
    {
        return $this->ordererDetails;
    }

    /**
     * Get creditCardDetails.
     * @return Array
     */
    public function getCreditCardDetails()
    {
        return $this->creditCardDetails;
    }

    /**
     * Set reference.
     * @param String $reference
     * @return $this
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * Set controlNumber
     * @param String $controlNumber
     * @return $this
     */
    public function setControlNumber($controlNumber)
    {
        $this->controlNumber = $controlNumber;
        return $this;
    }

    /**
     * Set fromDate
     * @param String $fromDate
     * @return $this
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;
        return $this;
    }

    /**
     * Set toDate
     * @param String $toDate
     * @return $this
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;
        return $this;
    }

    /**
     * Set hotelDetails.
     * @param Array $hotelDetails
     * @return $this
     */
    public function setHotelDetails($hotelDetails)
    {
        $this->hotelDetails = $hotelDetails;
        return $this;
    }

    /**
     * Set reservationOffers.
     * @param Array $reservationOffers
     * @return $this
     */
    public function setReservationOffers($reservationOffers)
    {
        $this->reservationOffers = $reservationOffers;
        return $this;
    }

    /**
     * Set ordererDetails.
     * @param Array $ordererDetails
     * @return $this
     */
    public function setOrdererDetails($ordererDetails)
    {
        $this->ordererDetails = $ordererDetails;
        return $this;
    }

    /**
     * Set creditCardDetails.
     * @param Array $creditCardDetails
     * @return $this
     */
    public function setCreditCardDetails($creditCardDetails)
    {
        $this->creditCardDetails = $creditCardDetails;
        return $this;
    }

    /**
     * Initializes the class's attribute/property by providing an array listing a class's attribute-value pair.
     * @param array $items
     */
    private function init(Array $items = array())
    {
        foreach ($items as $key => $value) {
            if (property_exists($this, $key) && !empty($value)) {
                $this->{$key} = $value;
            }
        }
    }
}
