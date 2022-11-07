<?php

namespace HotelBundle\Model;

/**
 * Description of HotelRoomCancellationForm
 *
 *
 */
class HotelRoomCancellationForm
{
    private $reservationKey;
    private $segmentIdentifier;
    private $segmentNumber;

    /**
     * Get reservationKey.
     * @return String
     */
    public function getReservationKey()
    {
        return $this->reservationKey;
    }

    /**
     * Get segmentIdentifier.
     * @return String
     */
    public function getSegmentIdentifier()
    {
        return $this->segmentIdentifier;
    }

    /**
     * Get segmentNumber.
     * @return String
     */
    public function getSegmentNumber()
    {
        return $this->segmentNumber;
    }

    /**
     * Set reservationKey.
     * @param String $reservationKey
     * @return $this
     */
    public function setReservationKey($reservationKey)
    {
        $this->reservationKey = $reservationKey;
        return $this;
    }

    /**
     * Set segmentIdentifier
     * @param String $segmentIdentifier
     * @return $this
     */
    public function setSegmentIdentifier($segmentIdentifier)
    {
        $this->segmentIdentifier = $segmentIdentifier;
        return $this;
    }

    /**
     * Set segmentNumber
     * @param String $segmentNumber
     * @return $this
     */
    public function setSegmentNumber($segmentNumber)
    {
        $this->segmentNumber = $segmentNumber;
        return $this;
    }
}
