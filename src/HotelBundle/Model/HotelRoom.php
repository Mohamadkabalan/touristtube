<?php

namespace HotelBundle\Model;

/**
 * The hotel room model class
 *
 */
class HotelRoom extends HotelRoomOffer
{
    private $reservationKey = null;
    private $from           = null;
    private $to             = null;
    private $guestName;
    private $guestEmail;
    private $status;
    private $cancellationReference;
    private $cancellationDate;

    /**
     * Get reservationKey.
     * @return String
     */
    public function getReservationKey()
    {
        return $this->reservationKey;
    }

    /**
     * Set reservationKey.
     * @param String $reservationKey
     */
    public function setReservationKey($reservationKey)
    {
        $this->reservationKey = $reservationKey;
    }

    /**
     * Get room begin date time.
     * @return String
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set room begin date time.
     * @param String $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * Get room end date time.
     * @return String
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set room end date time.
     * @param String $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * Get guestName.
     * @return String
     */
    public function getGuestName()
    {
        return $this->guestName;
    }

    /**
     * Set guestName
     * @param String $guestName
     */
    public function setGuestName($guestName)
    {
        $this->guestName = $guestName;
    }

    /**
     * Get guestEmail.
     * @return String
     */
    public function getGuestEmail()
    {
        return $this->guestEmail;
    }

    /**
     * Set guestEmail
     * @param String $guestEmail
     */
    public function setGuestEmail($guestEmail)
    {
        $this->guestEmail = $guestEmail;
    }

    /**
     * Get room itinerary status.
     * @return String
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set room itinerary status.
     * @param type $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get room cancellation reference.
     * @return array
     */
    public function getCancellationReference()
    {
        return $this->cancellationReference;
    }

    /**
     * Set room cancellation reference.
     * @param array $cancellationReference
     */
    public function setCancellationReference(array $cancellationReference)
    {
        $this->cancellationReference = $cancellationReference;
    }

    /**
     * Get cancellationDate.
     * @return array
     */
    public function getCancellationDate()
    {
        return $this->cancellationDate;
    }

    /**
     * Set cancellationDate.
     * @param array $cancellationDate
     */
    public function setCancellationDate($cancellationDate)
    {
        $this->cancellationDate = $cancellationDate;
    }
}
