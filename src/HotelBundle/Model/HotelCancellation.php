<?php

namespace HotelBundle\Model;

/**
 * The Hotel cancellation class
 *
 */
class HotelCancellation extends RspResponse
{
    private $reservationStatus;
    private $cancelable;
    private $cancellation;
    private $cancellationNumber;

    /**
     * The __construct when we make a new instance of HotelCancellation class.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get reservation status.
     * @return String
     */
    public function getReservationStatus()
    {
        return $this->reservationStatus;
    }

    /**
     * Get boolean true if cancelable; otherwise false
     * @return type
     */
    public function isCancelable()
    {
        return $this->cancelable;
    }

    /**
     * Get cancellation date
     * @return String
     */
    public function getCancellation()
    {
        return $this->cancellation;
    }

    /**
     * Get cancellation number
     * @return String
     */
    public function getCancellationNumber()
    {
        return $this->cancellationNumber;
    }

    /**
     * Set reservationStatus.
     * @param String $reservationStatus
     * @return $this
     */
    public function setReservationStatus($reservationStatus)
    {
        $this->reservationStatus = $reservationStatus;
        return $this;
    }

    /**
     * Set cancelable
     * @param Boolean $cancelable
     * @return $this
     */
    public function setCancelable($cancelable)
    {
        $this->cancelable = $cancelable;
        return $this;
    }

    /**
     * Set cancellation date
     * @param String $cancellation
     * @return $this
     */
    public function setCancellation($cancellation)
    {
        $this->cancellation = $cancellation;
        return $this;
    }

    /**
     * Set cancellationNumber
     * @param String $cancellationNumber
     * @return $this
     */
    public function setCancellationNumber($cancellationNumber)
    {
        $this->cancellationNumber = $cancellationNumber;
        return $this;
    }

    /**
     * Get array format response of this instance
     * @return Array
     */
    public function toArray()
    {
        return array_merge(get_object_vars($this), parent::toArray());
    }
}
