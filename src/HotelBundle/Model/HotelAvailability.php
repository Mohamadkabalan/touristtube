<?php

namespace HotelBundle\Model;

/**
 * The HotelAvailability model class
 *
 *
 */
class HotelAvailability extends RspResponse
{
    private $hotelCount;
    private $availableHotels;

    /**
     * The __construct when we make a new instance of HotelAvailability class.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get hotelCount.
     * @return Integer
     */
    public function getHotelCount()
    {
        return $this->hotelCount;
    }

    /**
     * Get availableHotels
     * @return Array
     */
    public function getAvailableHotels()
    {
        return $this->availableHotels;
    }

    /**
     * Set hotelCount
     * @param Integer $hotelCount
     * @return $this
     */
    public function setHotelCount($hotelCount)
    {
        $this->hotelCount = $hotelCount;
        return $this;
    }

    /**
     * Set availableHotels
     * @param array $availableHotels
     * @return $this
     */
    public function setAvailableHotels(array $availableHotels)
    {
        $this->availableHotels = $availableHotels;
        if ($this->hotelCount < 1 && count($availableHotels) > 0) {
            $this->hotelCount = count($availableHotels);
        }

        return $this;
    }

    /**
     * Checks if we have available hotels or not
     * @return boolean
     */
    public function hasAvailableHotels()
    {
        return ($this->hotelCount > 0);
    }

    /**
     * Get array format response of this instance
     * @return type
     */
    public function toArray()
    {
        return array_merge(get_object_vars($this), parent::toArray());
    }
}
