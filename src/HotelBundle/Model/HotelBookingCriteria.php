<?php

namespace HotelBundle\Model;

/**
 * Description of HotelBookingCriteria
 *
 */
class HotelBookingCriteria extends HotelBooking
{
    private $transactionId;
    private $targetRoomReservationKey;
    private $refererURL;
    private $chainCode     = '';
    private $hotelCityCode = '';
    private $availRequestSegment;
    private $hotelDetails  = array();

    /**
     * The session response from API.
     * @var Array
     */
    private $session;
    private $bookableInfoSelected;
    private $segments = array();
    private $details;
    private $prepaid;

    /**
     * Get transactionId.
     * @return String
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set transactionId.
     * @param String $transactionId
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * Get targetRoomReservationKey.
     * @return String
     */
    public function getTargetRoomReservationKey()
    {
        return $this->targetRoomReservationKey;
    }

    /**
     * Set targetRoomReservationKey.
     * @param String $targetRoomReservationKey
     */
    public function setTargetRoomReservationKey($targetRoomReservationKey)
    {
        $this->targetRoomReservationKey = $targetRoomReservationKey;
    }

    /**
     * Get refererURL.
     * @return String
     */
    public function getRefererURL()
    {
        return $this->refererURL;
    }

    /**
     * Set refererURL.
     * @param String $refererURL
     */
    public function setRefererURL($refererURL)
    {
        $this->refererURL = $refererURL;
    }

    /**
     * Get chainCode.
     * @return String
     */
    public function getChainCode()
    {
        return $this->chainCode;
    }

    /**
     * Set chainCode.
     * @param String $chainCode
     */
    public function setChainCode($chainCode)
    {
        $this->chainCode = $chainCode;
    }

    /**
     * Get hotelCityCode.
     * @return String
     */
    public function getHotelCityCode()
    {
        return $this->hotelCityCode;
    }

    /**
     * Set hotelCityCode.
     * @param String $hotelCityCode
     */
    public function setHotelCityCode($hotelCityCode)
    {
        $this->hotelCityCode = $hotelCityCode;
    }

    /**
     * Get availRequestSegment.
     * @return JSON
     */
    public function getAvailRequestSegment()
    {
        return $this->availRequestSegment;
    }

    /**
     * Set availRequestSegment.
     * @param JSON $availRequestSegment
     */
    public function setAvailRequestSegment($availRequestSegment)
    {
        $this->availRequestSegment = $availRequestSegment;
    }

    /**
     * Get hotelDetails.
     * @return array
     */
    public function getHotelDetails()
    {
        return $this->hotelDetails;
    }

    /**
     * Set hotelDetails.
     * @param array $hotelDetails
     */
    public function setHotelDetails($hotelDetails)
    {
        $this->hotelDetails = $hotelDetails;
    }

    /**
     * Get session.
     * @return array
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Set session.
     * @param array $session
     */
    public function setSession(array $session)
    {
        $this->session = $session;
    }

    /**
     * Get bookableInfoSelected.
     * @return array
     */
    public function getBookableInfoSelected()
    {
        return $this->bookableInfoSelected;
    }

    /**
     * Set bookableInfoSelected
     * @param array $bookableInfoSelected
     */
    public function setBookableInfoSelected(array $bookableInfoSelected)
    {
        $this->bookableInfoSelected = $bookableInfoSelected;
    }

    /**
     * Get booking segments.
     * @return type
     */
    public function getSegments()
    {
        return $this->segments;
    }

    /**
     * Set booking segments.
     * @param type $segments
     */
    public function setSegments($segments)
    {
        $this->segments = $segments;
    }

    /**
     * Get details.
     * @return array
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set details.
     * @param array $details
     */
    public function setDetails(array $details)
    {
        $this->details = $details;
    }

    /**
     * Get prepaid.
     * @return Boolean
     */
    public function isPrepaid()
    {
        return $this->prepaid;
    }

    /**
     * Set prepaid.
     * @param type $prepaid
     */
    public function setPrepaid($prepaid)
    {
        $this->prepaid = $prepaid;
    }

    /**
     * Get array format response of this instance.
     * @return Array
     */
    public function toArray()
    {
        $toreturn = parent::toArray();
        foreach ($this as $key => $value) {
            $toreturn[$key] = $value;
        }
        return $toreturn;
    }
}
