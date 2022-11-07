<?php

namespace HotelBundle\Model;

/**
 * Description of Hotel360ThingsToDo
 *
 */
class HotelImage
{
    private $id;
    private $userId;
    private $filename;
    private $location;
    private $defaultPic;
    private $mediaType;
    private $mediaSettings;
    private $hotel;
    private $division;

    public function __construct()
    {
        $this->hotel    = null;
        $this->division = null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function isDefaultPic()
    {
        return $this->defaultPic;
    }

    public function getMediaType()
    {
        return $this->mediaType;
    }

    public function getMediaSettings()
    {
        return $this->mediaSettings;
    }

    /**
     * Get hotel.
     * @return HotelInfo
     */
    public function getHotel()
    {
        return $this->hotel;
    }

    /**
     * Get hotelDivision.
     * @return HotelDivision
     */
    public function getDivision()
    {
        return $this->division;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    public function setDefaultPic($defaultPic)
    {
        $this->defaultPic = $defaultPic;
        return $this;
    }

    public function setMediaType($mediaType)
    {
        $this->mediaType = $mediaType;
        return $this;
    }

    public function setMediaSettings($mediaSettings)
    {
        $this->mediaSettings = $mediaSettings;
        return $this;
    }

    public function setHotel(HotelInfo $hotel)
    {
        $this->hotel = $hotel;
        return $this;
    }

    public function setDivision(HotelDivision $division)
    {
        $this->division = $division;
        return $this;
    }

    /**
     * Get array format response of this instance
     * @return Array
     */
    public function toArray()
    {
        $toArray = get_object_vars($this);

        if (!empty($this->getHotel())) {
            $toArray['hotel'] = $this->getHotel()->toArray();
        }

        if (!empty($this->getDivision())) {
            $toArray['division'] = $this->getDivision()->toArray();
        }

        return $toArray;
    }
}
