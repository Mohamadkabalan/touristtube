<?php

namespace HotelBundle\Model;

/**
 * Description of HotelDivisionWith360Media
 *
 */
class HotelDivisionWith360Media extends HotelDivision
{
    private $hotel;
    private $image;

    public function __construct()
    {
        parent::__construct();

        $this->hotel = null;
        $this->image = null;
    }

    public function getHotel()
    {
        return $this->hotel;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setHotel(HotelInfo $hotel)
    {
        $this->hotel = $hotel;
        return $this;
    }

    public function setImage(HotelImage $image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get array format response of this instance
     * @return Array
     */
    public function toArray()
    {
        $toArray = array_merge(get_object_vars($this), parent::toArray());

        if (!empty($this->getHotel())) {
            $toArray['hotel'] = $this->getHotel()->toArray();
        }

        if (!empty($this->getImage())) {
            $toArray['image'] = $this->getImage()->toArray();
        }

        return $toArray;
    }
}
